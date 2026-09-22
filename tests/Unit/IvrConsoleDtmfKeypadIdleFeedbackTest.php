<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-97 / SCRUM-98 — DTMF keypad must give idle feedback (disabled styling or transcript warning).
 * Pure Unit test (no Laravel boot) — reads ivr-console.blade.php from disk.
 */
class IvrConsoleDtmfKeypadIdleFeedbackTest extends TestCase
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

    public function test_idle_pad_css_marks_keys_disabled(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.pad\.is-idle\s+\.key\s*\{[^}]*opacity:\s*0\.55/s',
            $this->blade,
            'Idle pad keys must be visually de-emphasised'
        );
        $this->assertMatchesRegularExpression(
            '/\.pad\.is-idle\s+\.key\s*\{[^}]*cursor:\s*not-allowed/s',
            $this->blade,
            'Idle pad keys must show not-allowed cursor'
        );
    }

    public function test_set_pad_idle_toggles_class_and_aria_disabled(): void
    {
        $this->assertStringContainsString('function setPadIdle(idle)', $this->blade);
        $this->assertStringContainsString("padEl.classList.toggle('is-idle', !!idle)", $this->blade);
        $this->assertStringContainsString("padEl.setAttribute('aria-disabled', idle ? 'true' : 'false')", $this->blade);
    }

    public function test_on_pad_key_click_warns_before_test_start(): void
    {
        $this->assertMatchesRegularExpression(
            '/async function onPadKeyClick\(event\)\s*\{[\s\S]*?if\s*\(\s*!inCall\s*\)\s*\{[\s\S]*?Start a test first[\s\S]*?to send DTMF tones/s',
            $this->blade,
            'Idle DTMF press must show transcript warning (SCRUM-97 AC)'
        );
    }

    public function test_in_call_dtmf_still_logs_sent_marker(): void
    {
        $this->assertMatchesRegularExpression(
            '/async function onPadKeyClick\(event\)[\s\S]*?line\(\'DTMF <span class="t-ok">\' \+ key \+ \'<\/span> sent\'\)/s',
            $this->blade,
            'In-call DTMF path must remain for regression'
        );
    }

    public function test_start_and_end_call_toggle_pad_idle_state(): void
    {
        $this->assertMatchesRegularExpression(
            '/async function startCall\(\)[\s\S]*?setPadIdle\(false\)/s',
            $this->blade,
            'startCall must activate pad for DTMF'
        );
        $this->assertMatchesRegularExpression(
            '/function endCall\(\)[\s\S]*?setPadIdle\(true\)/s',
            $this->blade,
            'endCall must return pad to idle styling'
        );
    }

    public function test_ensure_pad_key_handler_rebinds_click_and_starts_idle(): void
    {
        $this->assertStringContainsString('function ensurePadKeyHandler()', $this->blade);
        $this->assertStringContainsString("fresh.addEventListener('click', onPadKeyClick)", $this->blade);
        $this->assertMatchesRegularExpression(
            '/function ensurePadKeyHandler\(\)[\s\S]*?setPadIdle\(true\)/s',
            $this->blade,
            'Pad handler install must default pad to idle'
        );
        $this->assertStringContainsString('ensurePadKeyHandler()', $this->blade);
        $this->assertStringContainsString('__softphoneBooted', $this->blade);
    }

    public function test_pad_markup_present_for_softphone_keypad(): void
    {
        $this->assertMatchesRegularExpression(
            '/<div\s+class="pad"\s+id="pad">/',
            $this->blade,
            'DTMF pad container must exist in markup'
        );
    }
}