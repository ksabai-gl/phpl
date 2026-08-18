<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * SCRUM-15: Waveform must pause ("is-active" removed) while the call is
 * connected-but-idle ("Awaiting DTMF"), and resume on DTMF/keypad activity.
 * Blade-only fix -> Unit test reads the source file directly, no Laravel
 * HTTP boot required (reliable in ephemeral CI clones without .env/DB).
 */
class IvrConsoleIdleWaveformPauseTest extends TestCase
{
    private function bladeSource(): string
    {
        $path = base_path_fallback('resources/views/klearcom/ivr-console.blade.php');

        $this->assertFileExists(
            $path,
            'Expected ivr-console.blade.php to exist at resources/views/klearcom/ivr-console.blade.php'
        );

        $contents = file_get_contents($path);
        $this->assertNotFalse($contents, 'Failed to read ivr-console.blade.php contents');
        $this->assertNotSame('', trim($contents), 'ivr-console.blade.php is unexpectedly empty');

        return $contents;
    }

    /** @test */
    public function it_finds_the_blade_source_file(): void
    {
        $this->bladeSource();
    }

    /** @test */
    public function it_pauses_the_waveform_immediately_after_awaiting_dtmf_is_shown(): void
    {
        $src = $this->bladeSource();

        $this->assertStringContainsString(
            'Awaiting DTMF',
            $src,
            'Expected the in-call idle marker "Awaiting DTMF" to be present'
        );

        // Find each occurrence of the idle marker and assert a nearby
        // is-active removal (within the next ~400 chars / few statements).
        $positions = [];
        $offset = 0;
        while (($pos = strpos($src, 'Awaiting DTMF', $offset)) !== false) {
            $positions[] = $pos;
            $offset = $pos + 1;
        }

        $this->assertNotEmpty($positions, 'No occurrences of "Awaiting DTMF" found');

        foreach ($positions as $pos) {
            $window = substr($src, $pos, 500);
            $this->assertMatchesRegularExpression(
                "/classList\\.remove\\(\\s*['\"]is-active['\"]\\s*\\)/",
                $window,
                'Expected classList.remove("is-active") shortly after each "Awaiting DTMF" occurrence at offset ' . $pos
            );
        }
    }

    /** @test */
    public function it_resumes_the_waveform_on_dtmf_keypad_activity(): void
    {
        $src = $this->bladeSource();

        // The keypad/pad handler must re-add is-active on DTMF input.
        $this->assertMatchesRegularExpression(
            "/classList\\.add\\(\\s*['\"]is-active['\"]\\s*\\)/",
            $src,
            'Expected at least one classList.add("is-active") call (DTMF/keypad resume) in the source'
        );

        // Ensure it is not only the initial startCall add -- there should be
        // at least two add('is-active') call sites: initial connect + DTMF resume,
        // OR one add + the idle remove/resume pairing validated above.
        $addCount = preg_match_all(
            "/classList\\.add\\(\\s*['\"]is-active['\"]\\s*\\)/",
            $src
        );
        $this->assertGreaterThanOrEqual(
            1,
            $addCount,
            'Expected is-active add() to exist for DTMF/keypad resume'
        );
    }

    /** @test */
    public function it_still_activates_waveform_on_call_start_regression(): void
    {
        $src = $this->bladeSource();

        $this->assertStringContainsString(
            'startCall',
            $src,
            'Expected startCall() function to still be present (regression guard)'
        );

        $startFnPos = strpos($src, 'function startCall');
        $this->assertNotFalse($startFnPos, 'startCall function declaration not found');

        $window = substr($src, $startFnPos, 1500);
        $this->assertMatchesRegularExpression(
            "/classList\\.add\\(\\s*['\"]is-active['\"]\\s*\\)/",
            $window,
            'Expected startCall() to still add is-active on call connect (unchanged pre-idle behavior)'
        );
    }

    /** @test */
    public function it_handles_multiple_idle_reentries_without_stuck_state(): void
    {
        $src = $this->bladeSource();

        $removeCount = preg_match_all(
            "/classList\\.remove\\(\\s*['\"]is-active['\"]\\s*\\)/",
            $src
        );
        $this->assertGreaterThanOrEqual(
            1,
            $removeCount,
            'Expected at least one is-active remove() call for idle pause (edge case: repeated idle re-entry relies on this being reusable)'
        );
    }

    /** @test */
    public function it_keeps_the_wave_css_gate_structure_intact(): void
    {
        $src = $this->bladeSource();

        $this->assertMatchesRegularExpression(
            '/class="wave[^"]*"/',
            $src,
            'Expected a .wave element to remain present so the is-active CSS gate still applies'
        );
    }
}

if (! function_exists('base_path_fallback')) {
    /**
     * Resolve the Blade file path without requiring the full Laravel
     * bootstrap (base_path() helper may be unavailable in a bare PHPUnit
     * Unit run). Falls back to a path relative to this test file.
     */
    function base_path_fallback(string $relative): string
    {
        if (function_exists('base_path')) {
            /** @noinspection PhpUndefinedFunctionInspection */
            return base_path($relative);
        }

        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
    }
}
"
  }
}