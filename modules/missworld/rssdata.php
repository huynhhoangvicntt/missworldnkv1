<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_IS_MOD_RSS')) {
    exit('Stop!!!');
}

$rssarray = [];

// Vì không có cấu trúc phân cấp, chúng ta sẽ tạo một mục RSS duy nhất cho toàn bộ dữ liệu
$rssarray[] = array(
    'catid' => 0,
    'parentid' => 0,
    'title' => $lang_module['rss_all_items'], // Giả sử có biến ngôn ngữ này, nếu không hãy thay bằng một string cụ thể
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss']
);
