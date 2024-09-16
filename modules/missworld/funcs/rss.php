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

$channel = [];
$items = [];

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];
$atomlink = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss'];

$db->sqlreset()
->select('id, fullname, alias, dob, address, height, chest, waist, hips, image, keywords, vote, time_add')
->order('time_add DESC')
->limit(30);

$where = [];
$where[] = 'status = 1';

$db->from(NV_PREFIXLANG . "_" . $module_data . "_rows")->where(implode(' AND ', $where));


if ($module_info['rss']) {
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        // Xác định link chi tiết
        $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];

        // Xác định ảnh đại diện
        $row['image'] = !empty($row['image']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'] : '';
        $row['rss_image'] = !empty($row['image']) ? '<img src="' . $row['image'] . '" width="100" align="left" border="0">' : '';

        // Tạo mô tả
        $description = $row['rss_image'];
        $description .= '<p><strong>' . $lang_module['fullname'] . ':</strong> ' . $row['fullname'] . '</p>';
        $description .= '<p><strong>' . $lang_module['dob'] . ':</strong> ' . date('d/m/Y', $row['dob']) . '</p>';
        $description .= '<p><strong>' . $lang_module['address'] . ':</strong> ' . $row['address'] . '</p>';
        $description .= '<p><strong>' . $lang_module['height'] . ':</strong> ' . $row['height'] . ' cm</p>';
        $description .= '<p><strong>' . $lang_module['measurements'] . ':</strong> ' . $row['chest'] . '-' . $row['waist'] . '-' . $row['hips'] . '</p>';
        $description .= '<p><strong>' . $lang_module['vote'] . ':</strong> ' . $row['vote'] . '</p>';

        $items[] = [
            'title' => $row['fullname'],
            'link' => $row['link'],
            'guid' => $module_name . '_' . $row['id'],
            'description' => $description,
            'pubdate' => $row['time_add']
        ];
    }
}

nv_rss_generate($channel, $items, $atomlink);
exit();