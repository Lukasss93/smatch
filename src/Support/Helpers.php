<?php

use Lukasss93\Smatch\Smatch;

if (!function_exists('smatch')) {
    function smatch($value): Smatch
    {
        return Smatch::source($value);
    }
}
