<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$module_version = [
    'name' => 'Missworld',
    'modfuncs' => 'main,detail,search',
    'change_alias'=> 'main,detail,search',
    'submenu' => 'main,detail,search',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.02',
    'date' => 'Tuesday, August 6, 2022 8:00:00 PM GMT+07:00',
    'author' => 'HUYNH HOANG VI <hoangvicntt2k@gmail.com>',
    'note' => 'Module Missworld',
    'uploads_dir' => [
        $module_upload
    ]
];