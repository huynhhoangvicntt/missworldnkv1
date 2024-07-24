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

// Xóa
if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_absint('id', 'post', 0);

    // Kiểm tra tồn tại
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id;
    $array = $db->query($sql)->fetch();
    if (empty($array)) {
        nv_htmlOutput('NO_' . $id);
    }

    // Xóa
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id;
    $db->query($sql);

    // Cập nhật thứ tự
    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;

    while ($row = $result->fetch()) {
        ++$weight;
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($sql);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_PLAYER', json_encode($array), $admin_info['admin_id']);
    $nv_Cache->delMod($module_name);

    nv_htmlOutput("OK");
}

$page_title = $lang_module['main'];

$array = [];
$per_page = 3;
$page = $nv_Request->get_int('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

// Phần tìm kiếm
$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', '');
$array_search['from'] = $nv_Request->get_title('f', 'get', '');
$array_search['to'] = $nv_Request->get_title('t', 'get', '');

// Xử lý dữ liệu tìm kiếm
if (preg_match('/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/', $array_search['from'], $m)) {
    $array_search['from'] = mktime(0, 0, 0, intval($m[2]), intval($m[1]), intval($m[3]));
} else {
    $array_search['from'] = 0;
}
if (preg_match('/^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})$/', $array_search['to'], $m)) {
    $array_search['to'] = mktime(23, 59, 59, intval($m[2]), intval($m[1]), intval($m[3]));
} else {
    $array_search['to'] = 0;
}

// Gọi CSDL để lấy dữ liệu
$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . "_" . $module_data . "_rows");

$where = [];
if (!empty($array_search['q'])) {
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $dblikekey = $db->dblikeescape($array_search['q']);
    $where[] = "(fullname LIKE '%" . $dblikekey . "%' OR keywords LIKE '%" . $dblikekey . "%')";
}
if (!empty($array_search['from'])) {
    $base_url .= '&amp;f=' . nv_date('d-m-Y', $array_search['from']);
    $where[] = "dob>=" . $array_search['from'];
}
if (!empty($array_search['to'])) {
    $base_url .= '&amp;t=' . nv_date('d-m-Y', $array_search['to']);
    $where[] = "dob<=" . $array_search['to'];
}

if (!empty($where)) {
    $db->where(implode(' AND ', $where));
}

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

$xtpl->assign('LINK_ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rows');

// Chuyển tìm kiếm sang ngày tháng
$array_search['from'] = empty($array_search['from']) ? '' : nv_date('d-m-Y', $array_search['from']);
$array_search['to'] = empty($array_search['to']) ? '' : nv_date('d-m-Y', $array_search['to']);

$xtpl->assign('SEARCH', $array_search);

// Hiển thị dữ liệu
if (!empty($array)) {
    $i = ($page - 1) * $per_page;
    foreach ($array as $value) {
        $value['dob'] = empty($value['dob']) ? '' : nv_date('d/m/Y', $value['dob']);
        $value['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=rows&amp;id=' . $value['id'];
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
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';