<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-25 — IVR softphone #phone must lock while a test call is active.
 * Pure Unit test (no Laravel boot) — suitable for ephemeral PHPUnit PHAR.
 */
class IvrConsolePhoneLockGateTest extends TestCase
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

    public function test_phone_input_exists_in_markup(): void
    {
        $this->assertMatchesRegularExpression(
            '/<input\s+id="phone"/',
            $this->blade,
            '#phone input must exist'
        );
    }

    public function test_phone_el_bound_via_get_element_by_id(): void
    {
        $this->assertStringContainsString(
            "document.getElementById('phone')",
            $this->blade,
            'Script must bind phoneEl to #phone'
        );
    }

    public function test_start_call_disables_phone_input(): void
    {
        $this->assertStringContainsString(
            'phoneEl.disabled = true',
            $this->blade,
            'startCall must disable #phone while inCall'
        );
    }

    public function test_end_call_reenables_phone_input(): void
    {
        $this->assertStringContainsString(
            'phoneEl.disabled = false',
            $this->blade,
            'endCall must re-enable #phone when call ends'
        );
    }

    public function test_phone_lock_uses_null_safe_guards(): void
    {
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*phoneEl\s*\)\s*phoneEl\.disabled\s*=\s*true/',
            $this->blade,
            'startCall must null-check phoneEl before disable'
        );
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*phoneEl\s*\)\s*phoneEl\.disabled\s*=\s*false/',
            $this->blade,
            'endCall must null-check phoneEl before re-enable'
        );
    }

    public function test_start_call_sets_in_call_before_disabling_phone(): void
    {
        $this->assertMatchesRegularExpression(
            '/async function startCall\(\)\s*\{\s*if\s*\(\s*inCall\s*\)\s*return;\s*inCall\s*=\s*true;\s*if\s*\(\s*phoneEl\s*\)\s*phoneEl\.disabled\s*=\s*true;/s',
            $this->blade,
            'inCall must be set true before #phone is disabled'
        );
    }

    public function test_pad_still_gates_on_in_call(): void
    {
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*!\s*key\s*\|\|\s*!\s*inCall\s*\)\s*return;/',
            $this->blade,
            'DTMF pad must continue to require inCall'
        );
    }

    public function test_start_call_is_idempotent_guarded(): void
    {
        $this->assertMatchesRegularExpression(
            '/async function startCall\(\)\s*\{\s*if\s*\(\s*inCall\s*\)\s*return;/s',
            $this->blade,
            'startCall must no-op when already in call'
        );
    }

    public function test_dial_button_text_toggles_with_call_lifecycle(): void
    {
        $this->assertStringContainsString(
            "dialBtn.textContent = 'End test'",
            $this->blade,
            'startCall must switch dial button to End test'
        );
        $this->assertStringContainsString(
            "dialBtn.textContent = 'Start test'",
            $this->blade,
            'endCall must restore dial button to Start test'
        );
    }
}
