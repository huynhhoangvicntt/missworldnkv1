<?php

/**
 * @Project VIDEO SCIENTIFIC RESEARCH 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2016 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 10 Jun 2016 02:20:31 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

global $lang_module, $module_info, $module_file, $global_array_level, $global_array_sector, $nv_Request;

$xtpl = new XTemplate('block.search.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php');
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', 'search');

$search = array();
$search['key'] = $nv_Request->get_title('q', 'get', '');
$search['levelid'] = $nv_Request->get_int('l', 'get', 0);
$search['sectorid'] = $nv_Request->get_int('s', 'get', 0);

$xtpl->assign('SEARCH_KEY', $search['key']);

foreach ($global_array_level as $level) {
    $level['selected'] = $search['levelid'] == $level['levelid'] ? ' selected="selected"' : '';

    $xtpl->assign('LEVEL', $level);
    $xtpl->parse('main.level');
}

foreach ($global_array_sector as $sector) {
    $sector['selected'] = $search['sectorid'] == $sector['sectorid'] ? ' selected="selected"' : '';

    $xtpl->assign('SECTOR', $sector);
    $xtpl->parse('main.sector');
}

$xtpl->parse('main');
$content = $xtpl->text('main');
