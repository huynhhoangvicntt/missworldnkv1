<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $lang_module['player_manager'];
$array = [];

// Gọi CSDL để lấy dữ liệu
$query = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_players ORDER BY weight ASC");
while ($row = $query->fetch()) {
    $array[$row['id']] = $row;
}

// Xóa
if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_absint('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_players WHERE id=" . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }

    // Xóa
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_players WHERE id=" . $id;
    $db->query($sql);

    // Cập nhật thứ tự
    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_players ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;

    while ($row = $result->fetch()) {
        ++$weight;
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_players SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_PLAYER', json_encode($array), $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    nv_htmlOutput("OK");
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('DATA', $array);
$xtpl->assign('OP', $op);
$xtpl->assign('LINK_ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=players');

// Hiển thị dữ liệu
$num = sizeof($array);
if (!empty($array)) {
    foreach ($array as $value) {
        $value['dob'] = nv_date('d/m/Y', $value['dob']); 
        $value['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=players&amp;id=' . $value['id'];
        $value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $value['image'];
        $value['add_time'] = nv_date('d/m/Y H:i', $value['add_time']);
        $value['edit_time'] = $value['edit_time'] ? nv_date('d/m/Y H:i', $value['edit_time']) : '';
        
        for ($i = 1; $i <= $num; ++$i) {
        $xtpl->assign('WEIGHT', [
            'w' => $i,
            'selected' => ($i == $value['weight']) ? ' selected="selected"' : ''
        ]);

        $xtpl->parse('main.loop.weight');
    }
        $xtpl->assign('DATA', $value);
        $xtpl->parse('main.loop');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';