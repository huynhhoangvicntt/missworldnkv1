<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$allow_func = [
    'main',
    'content',
    'voters',
    'dashboard',
    'config',
];

$submenu['content'] = $lang_module['contestant_manager'];
$submenu['voters'] = $lang_module['voter_manager'];
$submenu['dashboard'] = $lang_module['dashboard'];
$submenu['config'] = $lang_module['config_manager'];