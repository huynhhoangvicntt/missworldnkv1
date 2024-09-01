<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);

/**
 * Format a decimal number with specified precision
 * 
 * @param float $value The number to format
 * @param int $decimals The number of decimal points (default: 2)
 * @return string Formatted number as a string
 */
function nv_missworld_format_decimal($value, $decimals = 2) {
    return number_format((float)$value, $decimals, '.', '');
}
