<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$allow_func = [
    'main',
    'config',
    'players',
    'voters',
];

$submenu['config'] = $lang_module['config_manager'];
$submenu['players'] = $lang_module['player_manager'];
$submenu['voters'] = $lang_module['voter_manager'];