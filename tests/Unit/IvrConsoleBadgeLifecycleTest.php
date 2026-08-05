<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-23 — IVR console journey/regression badges must stay Idle/Pending
 * until startCall advances the lifecycle (no static Watch/Pass on first paint).
 *
 * Pure Unit test: reads Blade from disk; no Laravel application boot.
 */
class IvrConsoleBadgeLifecycleTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();

        $path = dirname(__DIR__, 2).DIRECTORY_SEPARATOR
            .'resources'.DIRECTORY_SEPARATOR
            .'views'.DIRECTORY_SEPARATOR
            .'klearcom'.DIRECTORY_SEPARATOR
            .'ivr-console.blade.php';

        $this->assertFileExists($path, 'IVR console Blade must exist for SCRUM-23 gate markers');
        $this->blade = (string) file_get_contents($path);
    }

    private function markupBeforeScript(): string
    {
        $parts = preg_split('/<script\b/i', $this->blade, 2);

        return $parts[0] ?? $this->blade;
    }

    private function journeyMarkup(): string
    {
        if (! preg_match('/id="journey"(.*?)id="wave"/s', $this->markupBeforeScript(), $m)) {
            $this->fail('Could not isolate #journey markup');
        }

        return $m[1];
    }

    private function regressionMarkup(): string
    {
        if (! preg_match('/id="regressionTests"(.*?)<\/div>\s*<\/div>\s*<div class="panel">/s', $this->markupBeforeScript(), $m)) {
            $this->fail('Could not isolate #regressionTests markup');
        }

        return $m[1];
    }

    private function scriptBlock(): string
    {
        if (! preg_match('/<script\b[^>]*>(.*)<\/script>/si', $this->blade, $m)) {
            $this->fail('Could not isolate inline script block');
        }

        return $m[1];
    }

    public function testJourneyNodesDefaultToIdleNotWatchOrPass(): void
    {
        $journey = $this->journeyMarkup();

        $this->assertSame(6, preg_match_all('/class="status idle"/', $journey));
        $this->assertSame(6, preg_match_all('/>Idle</', $journey));
        $this->assertDoesNotMatchRegularExpression('/class="status warn"/', $journey);
        $this->assertStringNotContainsString('>Watch<', $journey);
        $this->assertDoesNotMatchRegularExpression('/class="status"[^>]*>Pass</', $journey);
    }

    public function testRegressionRowsDefaultToPendingNotWatchOrPass(): void
    {
        $regression = $this->regressionMarkup();

        $this->assertSame(4, preg_match_all('/class="status pending"/', $regression));
        $this->assertSame(4, preg_match_all('/>Pending</', $regression));
        $this->assertStringNotContainsString('>Watch<', $regression);
        $this->assertDoesNotMatchRegularExpression('/class="status"[^>]*>Pass</', $regression);
        $this->assertDoesNotMatchRegularExpression('/class="status warn"/', $regression);
    }

    public function testBadgeLifecycleHelpersExist(): void
    {
        $script = $this->scriptBlock();

        $this->assertStringContainsString('function setBadge(el, text, kind)', $script);
        $this->assertStringContainsString('function resetJourneyBadges()', $script);
        $this->assertStringContainsString('function resetRegressionBadges()', $script);
        $this->assertStringContainsString('function setActive(index)', $script);
        $this->assertStringContainsString("setBadge(el, 'Pending', 'pending')", $script);
        $this->assertStringContainsString("setBadge(n.querySelector('.status'), 'Idle', 'idle')", $script);
    }

    public function testStartAndEndCallResetBadgesBeforeProgress(): void
    {
        $script = $this->scriptBlock();

        $this->assertMatchesRegularExpression(
            '/async function startCall\(\)\s*\{[\s\S]*?resetJourneyBadges\(\);[\s\S]*?resetRegressionBadges\(\);/',
            $script
        );
        $this->assertMatchesRegularExpression(
            '/function endCall\(\)\s*\{[\s\S]*?resetJourneyBadges\(\);[\s\S]*?resetRegressionBadges\(\);/',
            $script
        );
    }

    public function testWatchOnlyAssignedDuringActiveIdentityStepOrRegressionMarker(): void
    {
        $script = $this->scriptBlock();

        $this->assertStringContainsString("index === 3 ? 'Watch' : 'Running'", $script);
        $this->assertStringContainsString("setBadge(regressionStatuses[i], 'Watch', 'warn')", $script);
        $this->assertStringContainsString('function markRegressionWatch(i)', $script);

        $markup = $this->markupBeforeScript();
        $this->assertStringNotContainsString('>Watch<', $markup);
        $this->assertStringNotContainsString('class="status warn"', $this->journeyMarkup());
        $this->assertStringNotContainsString('class="status warn"', $this->regressionMarkup());
    }
}
