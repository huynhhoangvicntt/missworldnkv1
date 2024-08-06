<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
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
        $module_upload,
        $module_name,
        $module_name . '/images'
    ]
];