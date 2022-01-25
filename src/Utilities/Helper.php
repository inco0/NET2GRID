<?php

namespace App\Utilities;

/**
A static helper class that has some constants and helpful functions used across the project.
*/
Class Helper{
    const hostname = 'https://a831bqiv1d.execute-api.eu-west-1.amazonaws.com/dev/results';
    
    /**
     * Uses the gmp library as the converted gatewayEui value is over 32 bits and it overflows
     * @param type $hex_str The hexadecimal string
     * @return string The string converted into decimal
     */
    private static function convertHexToDec($hex_str): string{
        $dec_value = gmp_init($hex_str);
        return gmp_strval($dec_value);
    }
    
    /* Manual method
    public static function convertHexToDec($hex_str): string{
            $dec_value = gmp_init(0);
            $length = strlen($hex_str);
            for ($i = $length - 1; $i >= 0; $i--){
                    $ascii_code = ord($hex_str[$i]);
                    if ($ascii_code >= 97)
                            $hex_value = 10 + $ascii_code - 97;
                    else
                            $hex_value = 0 + $ascii_code - 48;
                    $base_exponent_mul = gmp_mul($hex_value, gmp_pow(16, $length - 1 - $i));
                    $dec_value = gmp_add($dec_value, $base_exponent_mul);
            }
            return gmp_strval($dec_value);
    }*/
    
    /**
     * Converts the attributes to decimal and concatenates them 
     * @param array $hex_api_assoc_array An associative array with the attribute names as keys and their respective hexadecimal value
     * @return string The valid routing key after every attribute has been converted to decimal and concatenated
     */
    public static function getRoutingKey(array $hex_api_assoc_array): string{
            $members_to_convert = array("gatewayEui", "profileId", "endpointId", "clusterId", "attributeId");
            $new_gateway_hex = "0x" . $hex_api_assoc_array["gatewayEui"];
            $routing_key = self::convertHexToDec($new_gateway_hex); 
            for ($i = 1; $i < sizeof($members_to_convert); $i++){
                $member = $members_to_convert[$i];
                $routing_key = $routing_key . "." . self::convertHexToDec($hex_api_assoc_array[$member]);
            }
            return $routing_key;
    }
    
    public static function getValue(string $message): string{
        list($value, $timestamp) = explode(".", $message, 2);
        return $value;
    }
    
    public static function getTimestamp(string $message): string{
        list($value, $timestamp) = explode(".", $message, 2);
        return $timestamp;
    }
}