<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-22 — softphone waveform must not bounce while IDLE.
 * Reuses proven SCRUM-12/14/15 .wave.is-active gate.
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
            'Idle .bar rule must not animate (regression for SCRUM-22)'
        );
    }

    public function test_animation_delay_rules_are_gated_under_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.wave\.is-active\s+\.bar:nth-child\(odd\)\s*\{[^}]*animation-delay/s',
            $this->blade,
            'Odd-bar delay must be gated under .wave.is-active'
        );
        $this->assertMatchesRegularExpression(
            '/\.wave\.is-active\s+\.bar:nth-child\(3n\)\s*\{[^}]*animation-delay/s',
            $this->blade,
            '3n-bar delay must be gated under .wave.is-active'
        );

        preg_match_all('/([^{]+)\{([^}]*)\}/s', $this->blade, $blocks, PREG_SET_ORDER);
        foreach ($blocks as $block) {
            $selector = $block[1];
            $body = $block[2];
            if (!str_contains($selector, '.bar')) {
                continue;
            }
            if (!preg_match('/animation(?:-delay)?\s*:/', $body)) {
                continue;
            }
            $this->assertStringContainsString(
                'is-active',
                $selector,
                'Animated .bar rules must be gated under is-active'
            );
        }
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

    public function test_wave_el_is_bound_to_wave_element(): void
    {
        $this->assertStringContainsString(
            "document.getElementById('wave')",
            $this->blade,
            'waveEl must resolve the #wave element used for is-active toggling'
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
    }
}
