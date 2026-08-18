<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-84 QA Validation — idle hang-up race, regression, impact, and edges
 * for the Klearcom IVR waveform harden. Pure Unit (no Laravel boot).
 */
class IvrConsoleScrum84QaValidationTest extends TestCase
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

    public function test_wave_element_exists_idle_without_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+class="wave"\s+id="wave"/',
            $this->blade,
            'Softphone wave must render as idle #wave without is-active'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<div\s+class="wave is-active"/',
            $this->blade,
            'Wave markup must not ship with is-active (idle bounce bug)'
        );
    }

    public function test_bounce_animation_is_gated_under_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.wave\.is-active\s+\.bar\s*\{[^}]*animation:\s*bounce/s',
            $this->blade,
            'Bounce must stay scoped to .wave.is-active .bar'
        );
        preg_match('/(?:^|\n)\s*\.bar\s*\{[^}]*\}/s', $this->blade, $idleBar);
        $this->assertNotEmpty($idleBar, 'Base .bar rule must exist');
        $this->assertStringNotContainsString(
            'animation:',
            $idleBar[0],
            'Idle .bar rule must not set animation (regression of SCRUM-22 gate)'
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

    public function test_no_unguarded_wave_el_class_list_mutation(): void
    {
        preg_match_all('/waveEl\.classList\.(add|remove)/', $this->blade, $all);
        preg_match_all('/if \(waveEl\) waveEl\.classList\.(add|remove)/', $this->blade, $guarded);
        $this->assertSame(
            count($all[0]),
            count($guarded[0]),
            'Every waveEl.classList add/remove must be null-guarded'
        );
        $this->assertGreaterThanOrEqual(2, count($guarded[0]));
    }

    public function test_hangup_during_connecting_aborts_before_in_call_label(): void
    {
        $this->assertMatchesRegularExpression(
            '/await sleep\(700\);\s*if\s*\(\s*!inCall\s*\)\s*return;\s*callState\.textContent\s*=\s*[\'"]IN CALL/s',
            $this->blade,
            'IN CALL must not be set if the operator hung up during CONNECTING'
        );
    }

    public function test_hangup_during_answer_aborts_before_dtmf_prompt(): void
    {
        $this->assertMatchesRegularExpression(
            '/await sleep\(900\);\s*if\s*\(\s*!inCall\s*\)\s*return;/s',
            $this->blade,
            'Second await must abort before the journey prompt when already idle'
        );
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($this->blade, 'if (!inCall) return;'),
            'startCall must abort after each await when already ended'
        );
    }

    public function test_end_call_clears_in_call_before_removing_wave(): void
    {
        $this->assertMatchesRegularExpression(
            '/function endCall\(\)\s*\{\s*inCall\s*=\s*false;\s*if\s*\(\s*waveEl\s*\)\s*waveEl\.classList\.remove/s',
            $this->blade,
            'endCall must clear inCall first so in-flight startCall aborts'
        );
        $this->assertStringContainsString(
            "callState.textContent = 'IDLE · ready to dial'",
            $this->blade,
            'endCall must restore IDLE label'
        );
        $this->assertStringContainsString(
            "dialBtn.textContent = 'Start test'",
            $this->blade,
            'endCall must restore Start test label'
        );
    }

    public function test_start_call_still_sets_connecting_and_is_idempotent(): void
    {
        $this->assertMatchesRegularExpression(
            '/async function startCall\(\)\s*\{\s*if\s*\(\s*inCall\s*\)\s*return;/s',
            $this->blade,
            'startCall must no-op when already in call'
        );
        $this->assertStringContainsString(
            "callState.textContent = 'CONNECTING · live network'",
            $this->blade,
            'startCall must still show CONNECTING before the first await'
        );
    }

    public function test_helpers_line_set_active_sleep_still_defined(): void
    {
        $this->assertMatchesRegularExpression('/function line\s*\(/', $this->blade);
        $this->assertMatchesRegularExpression('/function setActive\s*\(/', $this->blade);
        $this->assertMatchesRegularExpression('/function sleep\s*\(/', $this->blade);
        $this->assertStringContainsString("document.getElementById('wave')", $this->blade);
    }

    public function test_dial_button_toggles_start_and_end_call(): void
    {
        $this->assertMatchesRegularExpression(
            '/dialBtn\.addEventListener\(\s*[\'"]click[\'"]\s*,\s*\(\)\s*=>\s*\{\s*if\s*\(\s*inCall\s*\)\s*endCall\(\);\s*else\s*startCall\(\);/s',
            $this->blade,
            'Dial button must still toggle startCall/endCall'
        );
    }

    public function test_dtmf_pad_ignores_keys_while_idle(): void
    {
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*!key\s*\|\|\s*!inCall\s*\)\s*return;/',
            $this->blade,
            'Keypad must ignore DTMF while idle (impact: pad handler)'
        );
    }

    public function test_empty_phone_trim_still_dials(): void
    {
        $this->assertStringContainsString(
            "document.getElementById('phone').value.trim()",
            $this->blade,
            'Dial still reads trimmed phone (edge: empty number)'
        );
    }
}
