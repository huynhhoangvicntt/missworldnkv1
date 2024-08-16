<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$savesetting = $nv_Request->get_int('savesetting', 'post', 0);
if (!empty($savesetting)) {
    $array_config = [];
    $array_config['per_page'] = $nv_Request->get_int('per_page', 'post', 0);
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA', $module_config[$module_name]);
if (!empty($error)) {
    $xtpl->assign('error', $error);
    $xtpl->parse('main.error');
}

// So thí sinh hiển thị trên 1 trang
for ($i = 3; $i <= 24; ++$i) {
  $xtpl->assign('PER_PAGE', [
      'key' => $i,
      'title' => $i,
      'selected' => $i == $module_config[$module_name]['per_page'] ? ' selected="selected"' : ''
  ]);
  $xtpl->parse('main.per_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';