<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-12 — softphone waveform must not bounce while IDLE.
 * Pure Unit test (no Laravel boot) — suitable for ephemeral PHPUnit PHAR.
 */
class IvrConsoleIdleWaveformGateTest extends TestCase
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

    public function test_bounce_animation_is_gated_under_wave_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.wave\.is-active\s+\.bar\s*\{[^}]*animation:\s*bounce/s',
            $this->blade,
            'Bounce must be scoped to .wave.is-active .bar'
        );
    }

    public function test_base_bar_rule_does_not_set_animation(): void
    {
        $this->assertMatchesRegularExpression(
            '/(?<!is-active )\.bar\s*\{([^}]*)\}/s',
            $this->blade,
            'Base .bar rule must exist'
        );
        preg_match('/(?<!is-active )\.bar\s*\{([^}]*)\}/s', $this->blade, $m);
        $this->assertArrayHasKey(1, $m);
        $this->assertStringNotContainsString(
            'animation',
            $m[1],
            'Idle .bar rule must not animate (regression for SCRUM-12)'
        );
    }

    public function test_start_call_adds_is_active_on_wave(): void
    {
        $this->assertStringContainsString(
            "waveEl.classList.add('is-active')",
            $this->blade,
            'startCall must enable waveform animation'
        );
    }

    public function test_end_call_removes_is_active_from_wave(): void
    {
        $this->assertStringContainsString(
            "waveEl.classList.remove('is-active')",
            $this->blade,
            'endCall must disable waveform animation (return to IDLE)'
        );
    }

    public function test_wave_markup_defaults_to_idle_without_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+class="wave"\s+id="wave"/',
            $this->blade,
            'Wave element must start without is-active'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<div\s+class="wave[^"]*is-active/',
            $this->blade,
            'Wave markup must not ship with is-active by default'
        );
        $this->assertStringContainsString('IDLE · ready to dial', $this->blade);
    }
}
