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
$per_page = $module_config[$module_name]['per_page'];

// Xử lý điều khiển các op
if (empty($op) || $op == 'main') {
    $op = 'main';
    
    if (isset($array_op[0]) && !empty($array_op[0])) {
        if (preg_match('/^page\-([0-9]+)$/', $array_op[0], $m)) {
            $page = intval($m[1]);
        } else {
            $op = 'detail';
            $alias = $array_op[0];
        }
    }
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

// Định nghĩa RSS nếu module có hỗ trợ
if ($module_info['rss']) {
    $rss[] = array(
        'title' => $module_info['custom_title'],
        'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss']
    );
}