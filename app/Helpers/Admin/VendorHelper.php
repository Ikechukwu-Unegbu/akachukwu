<?php

namespace App\Helpers\Admin;

class VendorHelper
{
    public static function removeTokenPrefix($string)
    {
        $prefix = "Token : ";
        if (strpos($string, $prefix) === 0) {
            return substr($string, strlen($prefix));
        }
        return $string;
    }
}
