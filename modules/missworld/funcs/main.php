<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_IS_MOD_MISSWORLD')) {
    exit('Stop!!!');
}

// Lấy dữ liệu
$array_data = [];

$per_page = 12;
$page = $nv_Request->get_int('page', 'get', 1);

// Gọi CSDL để lấy dữ liệu
$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . "_" . $module_data . "_rows");
$sql = $db->sql();
$total = $db->query($sql)->fetchColumn();

$db->select('*')->order("id DESC")->limit($per_page)->offset(($page - 1) * $per_page);

$sql = $db->sql();
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_data[$row['id']] = $row;
}

$page_title = $lang_module['main'];
// Gọi hàm xử lý giao diện
$contents = nv_theme_missworld_list($array_data,$page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';