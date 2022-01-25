<?php

use PHPUnit\Framework\TestCase;
use App\Utilities\Helper;

final class getValueTest extends TestCase{
    
    public function testGetValue(): void{
        $message = "abcdfg.hijkl213dsadsa$@#!";
        $expected = "abcdfg";
        $result = Helper::getValue($message);
        $this->assertEquals($expected, $result);
    }
}