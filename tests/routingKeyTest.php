<?php

use PHPUnit\Framework\TestCase;
use App\Utilities\Helper;

final class routingKeyTest extends TestCase{
    
    public function testRoutingKey(): void{
        $arr = ["gatewayEui" => "84df0c00841cef93", "profileId" => "0x0104", "endpointId" => "0x0b","clusterId" => "0x003", "attributeId" => "0x312fffff"];
        $this->assertEquals("9574384529168986003.260.11.3.825229311", Helper::getRoutingKey($arr));
    }
}
