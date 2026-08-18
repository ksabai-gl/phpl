<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-15 QA Validation — bug fix, regression, impact, and edge-case markers.
 * Pure Unit test (no Laravel boot) — suitable for ephemeral PHPUnit PHAR.
 */
class IvrConsoleScrum15QaValidationTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $path = dirname(__DIR__, 2).'/resources/views/klearcom/ivr-console.blade.php';
        $this->assertFileExists($path);
        $contents = file_get_contents($path);
        $this->assertNotFalse($contents);
        $this->blade = $contents;
    }

    /** Bug fix: SCRUM-15 markers document idle-pause intent. */
    public function test_scrum15_comments_present(): void
    {
        $this->assertStringContainsString('SCRUM-15: connected but idle', $this->blade);
        $this->assertStringContainsString('SCRUM-15: DTMF = call activity', $this->blade);
    }

    /** Bug fix: setWaveActive helper available for future refactors. */
    public function test_set_wave_active_helper_defined(): void
    {
        $this->assertMatchesRegularExpression(
            '/function setWaveActive\(active\)\s*\{\s*waveEl\.classList\.toggle\(\s*[\'"]is-active[\'"]\s*,\s*!!active\s*\);\s*\}/s',
            $this->blade,
            'setWaveActive must toggle is-active via boolean guard'
        );
    }

    /** Regression: startCall still activates waveform during connecting phase. */
    public function test_start_call_still_adds_is_active_before_idle_pause(): void
    {
        $this->assertMatchesRegularExpression(
            '/async function startCall\(\)[\s\S]{0,400}waveEl\.classList\.add\(\s*[\'"]is-active[\'"]\s*\)/s',
            $this->blade,
            'startCall must add is-active during live/connecting phase'
        );
    }

    /** Edge: sales branch (key 1) returns to in-call idle — waveform pauses again. */
    public function test_sales_key_one_clears_is_active_after_branch(): void
    {
        $this->assertMatchesRegularExpression(
            '/key === [\'"]1[\'"][\\s\\S]{0,300}waveEl\\.classList\\.remove\\(\\s*[\'"]is-active[\'"]\\s*\\)/s',
            $this->blade,
            'Sales branch must pause waveform when returning to idle await'
        );
    }

    /** Edge: unmapped DTMF keys pause waveform (regression coverage path). */
    public function test_other_dtmf_keys_clear_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/Option noted[\\s\\S]{0,120}waveEl\\.classList\\.remove\\(\\s*[\'"]is-active[\'"]\\s*\\)/s',
            $this->blade,
            'Unmapped keypad options must pause waveform after activity pulse'
        );
    }

    /** Impact: pad handler ignores keypress when not in call (no spurious animation). */
    public function test_pad_handler_guards_on_in_call(): void
    {
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*!key\s*\|\|\s*!inCall\s*\)\s*return;/',
            $this->blade,
            'Pad handler must no-op when idle or missing key'
        );
    }

    /** Impact: endCall still clears is-active (IDLE state regression). */
    public function test_end_call_clears_is_active_before_idle_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/function endCall\(\)\s*\{[\s\S]{0,120}waveEl\.classList\.remove\(\s*[\'"]is-active[\'"]\s*\)/s',
            $this->blade,
            'endCall must remove is-active when returning to IDLE'
        );
    }

    /** Edge: support journey (key 2) keeps waveform active during multi-step flow. */
    public function test_support_key_two_does_not_immediately_clear_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/key === [\'"]2[\'"] && step <= 1[\s\S]{0,600}(?!waveEl\.classList\.remove)[\s\S]*setActive\(2\)/s',
            $this->blade,
            'Support branch must not immediately pause waveform during active journey'
        );
    }

    /** Regression: Awaiting DTMF message unchanged (operator UX anchor). */
    public function test_awaiting_dtmf_message_intact(): void
    {
        $this->assertStringContainsString(
            '<span class="t-warn">Awaiting DTMF</span>',
            $this->blade,
            'Awaiting DTMF prompt must remain for operator guidance'
        );
    }
}
