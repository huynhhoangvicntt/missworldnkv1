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

/**
 * File lấy cây thư mục rss khi vào module feeds
 * Nếu không có thư mục rss thì xóa file này
 */
$rssarray = [];
$sql = "SELECT id, fullname, alias
FROM " . NV_PREFIXLANG . "_" . $mod_data . "_rows
WHERE status=1 ORDER BY weight ASC, time_add DESC";

$list = $nv_Cache->db($sql, '', $mod_name);
if (!empty($list)) {
    foreach ($list as $value) {
        $value['catid'] = $value['id'];
        $value['parentid'] = 0;
        $value['title'] = $value['fullname'];
        $value['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod_name . '&amp;' . NV_OP_VARIABLE . '=' . $mod_info['alias']['rss'] . '/' . $value['alias'];
        $rssarray[] = $value;
    }
}