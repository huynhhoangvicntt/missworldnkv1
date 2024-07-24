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

// Lấy dữ liệu
$array_data = [];

$query = $db->query('SELECT * FROM ' . NV_PREFIXLANG .'_missworld_content ORDER BY weight ASC');
while ($row = $query->fetch()) {
    $array_data[$row['id']] = $row;
   
}
//$page_title =
// Gọi hàm xử lý giao diện
$contents = nv_missworld_list($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';