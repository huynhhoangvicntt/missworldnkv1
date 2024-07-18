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
$per_page = 12;
$page = $nv_Request->get_int('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

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

// Gọi CSDL để lấy dữ liệu
$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . "_" . $module_data . "_players");
$sql = $db->sql();
$total = $db->query($sql)->fetchColumn();

$db->select('*')->order("id DESC")->limit($per_page)->offset(($page - 1) * $per_page);

$sql = $db->sql();
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array[$row['id']] = $row;
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('DATA', $array);
$xtpl->assign('OP', $op);
$xtpl->assign('LINK_ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=players');

// Hiển thị dữ liệu
$num = sizeof($array);
if (!empty($array)) {
    $i = ($page - 1) * $per_page;
    foreach ($array as $value) {
        $value['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=players&amp;id=' . $value['id'];
        $value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $value['image'];
        $value['time_add'] = nv_date('d/m/Y H:i', $value['time_add']);
        $value['time_update'] = $value['time_update'] ? nv_date('d/m/Y H:i', $value['time_update']) : '';
        $value['stt'] = $i + 1;
      
        $xtpl->assign('DATA', $value);
        $xtpl->parse('main.loop');
        $i++;
    }
}

// Xuất phân trang
$generate_page = nv_generate_page($base_url, $total, $per_page, $page);
$xtpl->assign('GENERATE_PAGE', $generate_page);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';