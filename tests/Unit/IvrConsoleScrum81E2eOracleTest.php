<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-81 — E2E oracle (selector + IDLE contract) for Klearcom IVR waveform.
 * Browser coverage lives in tests/cypress/integration/klearcom-ivr-idle-waveform.cy.js.
 * This PHPUnit file is Laravel-free so ephemeral PHPUnit can lock the same AC.
 */
class IvrConsoleScrum81E2eOracleTest extends TestCase
{
    private string $blade;

    protected function setUp(): void
    {
        parent::setUp();
        $path = dirname(__DIR__, 2).'/resources/views/klearcom/ivr-console.blade.php';
        $this->assertFileExists($path, 'IVR console blade must exist');
        $contents = file_get_contents($path);
        $this->assertNotFalse($contents);
        $this->blade = $contents;
    }

    public function test_e2e_selectors_exist_for_idle_waveform_journey(): void
    {
        $this->assertStringContainsString('id="wave"', $this->blade);
        $this->assertStringContainsString('id="dialBtn"', $this->blade);
        $this->assertStringContainsString('id="callState"', $this->blade);
        $this->assertMatchesRegularExpression('/<div\s+class="wave"\s+id="wave"/', $this->blade);
        $this->assertStringContainsString('class="bar"', $this->blade);
    }

    public function test_idle_markup_has_no_is_active_on_wave(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/<div\s+class="wave is-active"/',
            $this->blade,
            'IDLE markup must not include is-active on #wave'
        );
        $this->assertStringContainsString('IDLE · ready to dial', $this->blade);
    }

    public function test_dial_control_labels_match_start_and_end_test(): void
    {
        $this->assertStringContainsString('Start test', $this->blade);
        $this->assertStringContainsString("dialBtn.textContent = 'End test'", $this->blade);
        $this->assertStringContainsString("dialBtn.textContent = 'Start test'", $this->blade);
        $this->assertStringContainsString("waveEl.classList.add('is-active')", $this->blade);
        $this->assertStringContainsString("waveEl.classList.remove('is-active')", $this->blade);
    }

    public function test_bounce_animation_only_under_wave_is_active(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.wave\.is-active\s+\.bar\s*\{[^}]*animation:\s*bounce/s',
            $this->blade
        );
    }
}
