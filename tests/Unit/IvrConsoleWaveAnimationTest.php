<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class IvrConsoleWaveAnimationTest extends TestCase
{
    public function test_fix_markers_present_in_source(): void
    {
        $path = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, 'resources/views/klearcom/ivr-console.blade.php');
        $this->assertFileExists($path);
        $src = file_get_contents($path);
        $this->assertNotFalse($src);
        $this->assertStringContainsString('is-active', $src);
        $this->assertStringContainsString('.wave.is-active', $src);
        $this->assertStringContainsString('endCall', $src);
        $this->assertStringContainsString('startCall', $src);
    }
}
