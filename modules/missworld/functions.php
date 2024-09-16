<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_MISSWORLD', true);

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

// Đoạn này dùng để chặn người dùng tự ý truy cập vào /detail/
if ($op == 'detail') {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}

// Các biến sử dụng phân trang
$page = 1;
$per_page = 10;
$per_page_detail = 6;

// Xử lý điều khiển các op
if ($op == 'main') {
    // Phân trang tại trang main hoặc có liên kết tĩnh của thí sinh
    if (isset($array_op[0])) {
        if (preg_match('/^page\-([0-9]+)$/', $array_op[0], $m)) {
            // Phân trang
            $page = intval($m[1]);
        } else {
            // Kiểm tra xem có phải là alias của thí sinh không
            $alias = $array_op[0];
            $op = 'detail';
        }
    }

    // Phân trang tại trang detail
    if (isset($array_op[1])) {
        if (preg_match('/^page\-([0-9]+)$/', $array_op[1], $m)) {
            // Phân trang
            $page = intval($m[1]);
        }
    }
}

// Định nghĩa RSS nếu module có hỗ trợ
if ($module_info['rss']) {
    $rss[] = [
        'title' => $module_info['custom_title'],
        'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss']
    ];
}
