<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-84 — residual idle-waveform hardening on top of the merged SCRUM-22 gate.
 * Pure Unit test (no Laravel boot) — suitable for ephemeral PHPUnit PHAR.
 */
class IvrConsoleScrum84IdleWaveformHardenTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $path = dirname(__DIR__, 2).'/resources/views/klearcom/ivr-console.blade.php';
        $this->assertFileExists($path, 'IVR console blade must exist on the fix branch');
        $contents = file_get_contents($path);
        $this->assertNotFalse($contents);
        $this->blade = $contents;
    }

    public function test_bounce_remains_gated_under_wave_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.wave\.is-active\s+\.bar\s*\{[^}]*animation:\s*bounce/s',
            $this->blade,
            'Bounce must stay scoped to .wave.is-active .bar (SCRUM-22 gate)'
        );
    }

    public function test_start_call_null_guards_wave_el(): void
    {
        $this->assertStringContainsString(
            "if (waveEl) waveEl.classList.add('is-active')",
            $this->blade,
            'startCall must null-guard waveEl before enabling bounce'
        );
    }

    public function test_end_call_null_guards_wave_el(): void
    {
        $this->assertStringContainsString(
            "if (waveEl) waveEl.classList.remove('is-active')",
            $this->blade,
            'endCall must null-guard waveEl before disabling bounce'
        );
    }

    public function test_start_call_aborts_after_await_if_ended(): void
    {
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($this->blade, 'if (!inCall) return;'),
            'startCall must abort after each await when the operator already ended the call'
        );
    }

    public function test_wave_markup_still_defaults_idle(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<div\s+class="wave is-active"/',
            $this->blade,
            'Wave must not ship with is-active in markup'
        );
    }
}
