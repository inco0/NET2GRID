<?php

use PHPUnit\Framework\TestCase;
use App\Utilities\Helper;

final class getTimestampTest extends TestCase{
    
    public function testGetTimestamp(): void{
        $message = "abcdfg.hijkl213dsadsa$@#!";
        $expected = "hijkl213dsadsa$@#!";
        $result = Helper::getTimestamp($message);
        $this->assertEquals($expected, $result);
    }
}