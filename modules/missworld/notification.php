<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

/**
 * File này dùng để lấy tiêu đề, link cho thông báo
 */

// Lấy ngôn ngữ admin của module
$lang_siteinfo = nv_get_lang_module($mod);

// Tạo title và link cho biến data
$data['title'] = sprintf($lang_siteinfo['notify_new_vote'], $data['content']['voter_name'], $data['content']['contestant_name']);
$data['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=' . $data['content']['alias'];
