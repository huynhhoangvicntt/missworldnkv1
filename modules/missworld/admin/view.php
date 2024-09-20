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

/**
 * File này để lấy link bài đăng khi click vào 1 comment từ module comment.
 * Xóa bỏ nếu không dùng chức năng comment
 */

// Module comment trả về 2 giá trị này
$id = $nv_Request->get_int('id', 'get', 0);
$area = $nv_Request->get_int('area', 'get', 0);

$sql = "SELECT alias FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id . " AND status=1";
$row = $db->query($sql)->fetch();

if (!empty($row)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl']);
}

nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'], 404);
