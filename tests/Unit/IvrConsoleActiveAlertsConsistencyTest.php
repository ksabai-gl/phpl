<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-24 — Active alerts summary must stay consistent with Watch/Fail badges
 * on the IVR console (no hardcoded all-clear while regression/journey shows Watch).
 *
 * Pure Unit test: reads Blade from disk; no Laravel application boot.
 */
class IvrConsoleActiveAlertsConsistencyTest extends TestCase
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

        $this->assertFileExists($path, 'IVR console Blade must exist for SCRUM-24 gate markers');
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

    public function testActiveAlertsSummaryHasStableIdAndDefaultAllClear(): void
    {
        $markup = $this->markupBeforeScript();

        $this->assertStringContainsString('id="activeAlertsSummary"', $markup);
        $this->assertMatchesRegularExpression(
            '/id="activeAlertsSummary"[^>]*>No critical IVR failures</',
            $markup
        );
    }

    public function testIdleFirstPaintHasNoWatchWhileActiveAlertsAllClear(): void
    {
        $journey = $this->journeyMarkup();
        $regression = $this->regressionMarkup();

        $this->assertSame(6, preg_match_all('/class="status idle"/', $journey));
        $this->assertStringNotContainsString('>Watch<', $journey);
        $this->assertStringNotContainsString('class="status warn"', $journey);
        $this->assertStringNotContainsString('>Watch<', $regression);
        $this->assertStringNotContainsString('class="status warn"', $regression);
        $this->assertMatchesRegularExpression(
            '/id="activeAlertsSummary"[^>]*>No critical IVR failures</',
            $this->markupBeforeScript()
        );
    }

    public function testActiveAlertsSummaryUpdaterExistsAndReadsWatchFail(): void
    {
        $script = $this->scriptBlock();

        $this->assertStringContainsString('function updateActiveAlertsSummary()', $script);
        $this->assertStringContainsString("activeAlertsSummary.textContent = 'No critical IVR failures'", $script);
        $this->assertStringContainsString('Watch alert', $script);
        $this->assertStringContainsString("el.classList.contains('warn')", $script);
        $this->assertStringContainsString("el.classList.contains('fail')", $script);
        $this->assertStringContainsString('updateActiveAlertsSummary();', $script);
        $this->assertStringContainsString('function markRegressionWatch(i)', $script);
        $this->assertStringContainsString("setBadge(regressionStatuses[i], 'Watch', 'warn')", $script);
    }
}
