<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_MISSWORLD', true);

require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

// Đoạn này dùng để chặn người dùng tự ý truy cập vào /viewcat/ hoặc /detail/
if (in_array($op, ['detail', 'viewcat'])) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}

// Tạo biến này nếu xem theo danh mục
$catid = 0;

// Các biến sử dụng phân trang
$page = 1;
$per_page = 10;
$per_page_detail = 6;

// Xử lý điều khiển các op
if ($op == 'main') {
    // Phân trang tại trang main hoặc có liên kết tĩnh của danh mục (xem theo danh mục)
    if (isset($array_op[0])) {
        if (preg_match('/^page\-([0-9]+)$/', $array_op[0], $m)) {
            // Phân trang
            $page = intval($m[1]);
        } else {
            // Duyệt danh mục xem liên kết tĩnh nào khớp
            foreach ($global_array_cats as $cat) {
                if ($cat['alias'] == $array_op[0]) {
                    $catid = $cat['id'];
                    break;
                }
            }
            // Không tìm thấy danh mục nào tức liên kết không tồn tại
            if (empty($catid)) {
                nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
            }
            // Set $op sang viewcat để xử lý
            $op = 'viewcat';
        }
    }

    // Phân trang tại trang cat hoặc có liên kết tĩnh của bài viết (xem chi tiết bài viết)
    if (isset($array_op[1])) {
        if (preg_match('/^page\-([0-9]+)$/', $array_op[1], $m)) {
            // Phân trang
            $page = intval($m[1]);
        } else {
            // Set $op sang detail để xử lý
            $op = 'detail';
        }
    }
}

// Định nghĩa RSS nếu module có hỗ trợ
if ($module_info['rss']) {
    // RSS chính toàn module
    $rss[] = [
        'title' => $module_info['custom_title'],
        'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss']
    ];

    // RSS của danh mục nếu có
    foreach ($global_array_cats as $cat) {
        $rss[] = [
            'title' => $module_info['custom_title'] . ' - ' . $cat['title'],
            'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss'] . '/' . $cat['alias']
        ];
    }
}

// Tạo breadcrumb khi có thông tin danh mục
if ($catid) {
    $parentid = $catid;
    while ($parentid > 0) {
        $cat = $global_array_cats[$parentid];
        $array_mod_title[] = [
            'catid' => $parentid,
            'title' => $cat['title'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $cat['alias']
        ];
        $parentid = $cat['parentid'];
    }
    krsort($array_mod_title, SORT_NUMERIC);
}