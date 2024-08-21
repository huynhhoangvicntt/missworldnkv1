<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['dashboard'];

$xtpl = new XTemplate('dashboard.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

// Lấy tổng số thí sinh
$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows";
$total_contestants = $db->query($sql)->fetchColumn();
$xtpl->assign('TOTAL_CONTESTANTS', number_format($total_contestants));

// Lấy tổng số lượt bình chọn
$sql = "SELECT SUM(vote) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows";
$total_votes = $db->query($sql)->fetchColumn();
$xtpl->assign('TOTAL_VOTES', number_format($total_votes));

// Lấy số đo trung bình
$sql = "SELECT 
    ROUND(AVG(height), 2) as avg_height, 
    ROUND(AVG(chest), 2) as avg_chest, 
    ROUND(AVG(waist), 2) as avg_waist, 
    ROUND(AVG(hips), 2) as avg_hips 
    FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows";
$stmt = $db->prepare($sql);
$stmt->execute();
$avg_measurements = $stmt->fetch();

$xtpl->assign('AVG_HEIGHT', $avg_measurements['avg_height']);
$xtpl->assign('AVG_CHEST', $avg_measurements['avg_chest']);
$xtpl->assign('AVG_WAIST', $avg_measurements['avg_waist']);
$xtpl->assign('AVG_HIPS', $avg_measurements['avg_hips']);

// Lấy 5 thí sinh theo số lượt bình chọn
$sql = "SELECT fullname, vote FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows ORDER BY vote DESC LIMIT 5";
$result = $db->query($sql);
$rank = 1;
while ($row = $result->fetch()) {
    $xtpl->assign('TOP_CONTESTANT', [
        'rank' => $rank++,
        'fullname' => $row['fullname'],
        'vote' => number_format($row['vote'])
    ]);
    $xtpl->parse('main.top_contestant');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';