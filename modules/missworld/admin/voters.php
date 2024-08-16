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

$page_title = $lang_module['voter_manager'];

$content = "Main";

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';