<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class WaveformIdleStateTest extends TestCase
{
    public function test_fix_markers_present_in_source(): void
    {
        $path = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, 'src/components/SoftphonePanel/Waveform.js');
        $this->assertFileExists($path);
        $src = file_get_contents($path);
        $this->assertNotFalse($src);
        $this->assertStringContainsString('isCallActive', $src);
        $this->assertStringContainsString('callStatus', $src);
        $this->assertStringContainsString('setInterval', $src);
        $this->assertStringContainsString('waveform--animating', $src);
        $this->assertStringContainsString('waveform--idle', $src);
    }
}
