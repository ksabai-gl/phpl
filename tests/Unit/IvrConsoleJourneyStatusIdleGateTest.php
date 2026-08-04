<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Idle-state gate for IVR journey status badges (SCRUM-17).
 * Ensures the blade base markup does not paint Pass/Watch before Start test.
 */
class IvrConsoleJourneyStatusIdleGateTest extends TestCase
{
    private function blade(): string
    {
        $path = dirname(__DIR__, 2) . '/resources/views/klearcom/ivr-console.blade.php';
        $this->assertFileExists($path);

        return (string) file_get_contents($path);
    }

    private function journeyMarkup(string $blade): string
    {
        $this->assertMatchesRegularExpression(
            '/<div class="journey" id="journey">[\s\S]*?<div class="panel console">/',
            $blade,
            'Journey map markup not found in IVR console blade'
        );

        if (!preg_match('/<div class="journey" id="journey">([\s\S]*?)<div class="panel console">/', $blade, $m)) {
            $this->fail('Unable to extract #journey markup');
        }

        return $m[1];
    }

    public function testJourneyStatusesArePendingAtIdleLoad(): void
    {
        $journey = $this->journeyMarkup($this->blade());

        $this->assertStringNotContainsString('>Pass<', $journey);
        $this->assertStringNotContainsString('>Watch<', $journey);
        $this->assertStringNotContainsString('status warn', $journey);
        $this->assertDoesNotMatchRegularExpression('/class="node active"/', $journey);
        $this->assertGreaterThanOrEqual(6, substr_count($journey, 'status pending'));
    }

    public function testRuntimeHelpersDriveStatusLifecycle(): void
    {
        $blade = $this->blade();

        $this->assertStringContainsString('function resetJourneyStatuses()', $blade);
        $this->assertStringContainsString('resetJourneyStatuses();', $blade);
        $this->assertMatchesRegularExpression('/function endCall\(\)[\s\S]*?resetJourneyStatuses\(\);/', $blade);
        $this->assertMatchesRegularExpression('/async function startCall\(\)[\s\S]*?resetJourneyStatuses\(\);/', $blade);
    }
}