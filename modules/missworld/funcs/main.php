<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_MISSWORLD')) {
    exit('Stop!!!');
}

// Các biến cần thiết: Tiêu đề, từ khóa, mô tả
$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

// Các biết cần thiết: Link của trang
$page_url = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
if ($page > 1) {
    $page_url .= '&amp;' . NV_OP_VARIABLE . '=page-' . $page;
    $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    if (!empty($description)) {
        $description .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    }
}
$canonicalUrl = getCanonicalUrl($page_url);

// Truy vấn CSDL để lấy tin
$db->sqlreset()->from(NV_PREFIXLANG . '_' . $module_data . '_players');

// Điều kiện lấy tin
$where = [];

$db->select('COUNT(id)')->where(implode(' AND ', $where));

// Tổng số tin
$num_items = $db->query($db->sql())->fetchColumn();

// Khống chế đánh số trang tùy ý
$urlappend = '&amp;' . NV_OP_VARIABLE . '=page-';
betweenURLs($page, ceil($num_items / $per_page), $base_url, $urlappend, $prevPage, $nextPage);

// Lấy danh sách tin
$db->select('fullname, alias, dob, height, measurements, email, image, vote, weight, add_time, edit_time');
$db->order('add_time DESC')->limit($per_page)->offset(($page - 1) * $per_page);

$result = $db->query($db->sql());

$array = [];
$query = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_missworld_players");
while ($row = $query->fetch()){
    $array_data[$row["id"]] = $row;

}

// Phân trang
$generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
// Gọi hàm xử lý giao diện
$contents = nv_theme_main($array, $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';