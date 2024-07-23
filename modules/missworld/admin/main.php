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

$per_page = 4;
$page = $nv_Request->get_int('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

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

// Bien tim kiem
$data_search = [
    "q" => nv_substr($nv_Request->get_title('q', 'get', ''), 0, 100),
    "from" => nv_substr($nv_Request->get_title('from', 'get', ''), 0, 100),
    "to" => nv_substr($nv_Request->get_title('to', 'get', ''), 0, 100),
];

$where = [];
if (!empty($data_search['q'])) {
    $base_url .= "&amp;q=" . urlencode($data_search['q']);
    $where[] = "(
        fullname LIKE '%" . $db->dblikeescape($data_search['q']) . "%' OR
        keywords LIKE '%" . $db->dblikeescape($data_search['q']) . "%' OR
        address LIKE '%" . $db->dblikeescape($data_search['q']) . "%'
    )";
}
if (!empty($data_search['from'])) {
    unset($match);
    if (preg_match("/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['from'], $match)) {
        $from = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
        $where[] = "time_add >= " . $from;
        $base_url .= "&amp;from=" . $data_search['from'];
    }
}
if (!empty($data_search['to'])) {
    unset($match);
    if (preg_match("/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['to'], $match)) {
        $to = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
        $where[] = "time_add <= " . $to;
        $base_url .= "&amp;to=" . $data_search['to'];
    }
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('DATA', $array);
$xtpl->assign('OP', $op);

$xtpl->assign('LINK_ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=players');

$xtpl->assign('DATA_SEARCH', $data_search);
// $xtpl->assign('SEARCH', $array_search);

// Hiển thị dữ liệu
if (!empty($array)) {
    $i = ($page - 1) * $per_page;
    foreach ($array as $value) {
        // Chuyển ngày tháng từ số sang text
        $value['dob'] = empty($value['dob']) ? '' : nv_date('d/m/Y', $value['dob']);
        
        $value['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=players&amp;id=' . $value['id'];
        $value['time_add'] = nv_date('d/m/Y H:i', $value['time_add']);
        $value['time_update'] = $value['time_update'] ? nv_date('d/m/Y H:i', $value['time_update']) : '';

        if (!empty($value['image'])) {
            $value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $value['image'];
        } else {
            $value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . 'default_1.jpg';
        }
      
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