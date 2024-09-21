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

$page_title = $lang_module['config'];

$array_config = [];

if ($nv_Request->isset_request('save', 'post')) {
    $array_config['per_page'] = $nv_Request->get_int('per_page', 'post', 20);

    $sth = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_config SET config_value = :config_value WHERE config_name = 'per_page'");
    $sth->bindParam(':config_value', $array_config['per_page'], PDO::PARAM_INT);
    $sth->execute();

    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$array_config['per_page'] = $db->query('SELECT config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config WHERE config_name = "per_page"')->fetchColumn();

if (empty($array_config['per_page'])) {
    $array_config['per_page'] = 20; // Giá trị mặc định nếu không có trong CSDL
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('DATA', $array_config);

// Số thí sinh hiển thị trên 1 trang
for ($i = 5; $i <= 30; ++$i) {
    $xtpl->assign('PER_PAGE', [
        'key' => $i,
        'title' => $i,
        'selected' => $i == $array_config['per_page'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.per_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
