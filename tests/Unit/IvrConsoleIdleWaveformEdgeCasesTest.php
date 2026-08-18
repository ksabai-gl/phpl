<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-22 / SCRUM-15 — P4 edge / regression markers for idle waveform gate.
 * Pure Unit test (no Laravel boot) — suitable for ephemeral PHPUnit PHAR.
 */
class IvrConsoleIdleWaveformEdgeCasesTest extends TestCase
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

    public function test_bounce_keyframes_still_defined(): void
    {
        $this->assertMatchesRegularExpression(
            '/@keyframes\s+bounce\s*\{/',
            $this->blade,
            'bounce keyframes must remain for live-call animation'
        );
    }

    public function test_wave_el_bound_via_get_element_by_id(): void
    {
        $this->assertStringContainsString(
            "document.getElementById('wave')",
            $this->blade,
            'Script must bind waveEl to #wave'
        );
    }

    public function test_start_call_is_idempotent_guarded(): void
    {
        $this->assertMatchesRegularExpression(
            '/async function startCall\(\)\s*\{\s*if\s*\(\s*inCall\s*\)\s*return;/s',
            $this->blade,
            'startCall must no-op when already in call (edge: double click)'
        );
    }

    public function test_end_call_restores_idle_call_state_label(): void
    {
        $this->assertStringContainsString(
            "callState.textContent = 'IDLE · ready to dial'",
            $this->blade,
            'endCall must restore IDLE label used by softphone status'
        );
    }

    public function test_default_idle_label_present_in_markup(): void
    {
        $this->assertStringContainsString(
            'IDLE · ready to dial',
            $this->blade,
            'Initial markup must show IDLE status'
        );
    }

    public function test_animation_delays_only_under_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.wave\.is-active\s+\.bar:nth-child\(odd\)\s*\{\s*animation-delay:/s',
            $this->blade,
            'Odd-bar delay must be gated under .wave.is-active'
        );
        $this->assertMatchesRegularExpression(
            '/\.wave\.is-active\s+\.bar:nth-child\(3n\)\s*\{\s*animation-delay:/s',
            $this->blade,
            '3n-bar delay must be gated under .wave.is-active'
        );
    }

    public function test_dial_button_toggles_start_and_end_call(): void
    {
        $this->assertMatchesRegularExpression(
            '/dialBtn\.addEventListener\(\s*[\'"]click[\'"]\s*,\s*\(\)\s*=>\s*\{\s*if\s*\(\s*inCall\s*\)\s*endCall\(\);\s*else\s*startCall\(\);/s',
            $this->blade,
            'Dial button must toggle startCall/endCall (impact: softphone control)'
        );
    }

    /** SCRUM-15 — waveform must pause during in-call idle (Awaiting DTMF). */
    public function test_awaiting_dtmf_clears_is_active_while_in_call(): void
    {
        $this->assertMatchesRegularExpression(
            '/Awaiting DTMF[\s\S]{0,200}waveEl\.classList\.remove\(\s*[\'"]is-active[\'"]\s*\)/s',
            $this->blade,
            'After Awaiting DTMF, is-active must be cleared (idle within connected call)'
        );
    }

    /** SCRUM-15 — DTMF activity must resume waveform animation. */
    public function test_dtmf_pad_readds_is_active_on_keypress(): void
    {
        $this->assertMatchesRegularExpression(
            '/getElementById\(\s*[\'"]pad[\'"]\s*\)[\s\S]{0,250}waveEl\.classList\.add\(\s*[\'"]is-active[\'"]\s*\)/s',
            $this->blade,
            'DTMF pad handler must re-add is-active when keys are pressed'
        );
    }
}
