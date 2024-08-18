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

$contestant_id = $nv_Request->get_int('contestant_id', 'get', 0);

// Nếu contestant_id được cung cấp, hiển thị lượt bình chọn cho thí sinh đó
if ($contestant_id > 0) {
    $sql = "SELECT id, fullname FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id = " . $contestant_id;
    $contestant = $db->query($sql)->fetch();

    if (empty($contestant)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=voters');
    }

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=voters&contestant_id=' . $contestant_id;

    $db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . "_" . $module_data . "_votes")->where('contestant_id = ' . $contestant_id);
} else {
    // Hiển thị tất cả lượt bình chọn
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=voters';

    $db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . "_" . $module_data . "_votes");
}

$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);

$num_items = $db->query($db->sql())->fetchColumn();

$db->select('v.*, r.fullname AS contestant_name')
   ->from(NV_PREFIXLANG . '_' . $module_data . '_votes v')
   ->join('LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_rows r ON v.contestant_id = r.id')
   ->order('v.vote_time DESC')
   ->limit($per_page)
   ->offset(($page - 1) * $per_page);

if ($contestant_id > 0) {
    $db->where('v.contestant_id = ' . $contestant_id);
}

$result = $db->query($db->sql());

$xtpl = new XTemplate('voters.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

if ($contestant_id > 0) {
    $xtpl->assign('CONTESTANT', $contestant);
    $xtpl->parse('main.contestant_votes');
} else {
    $xtpl->parse('main.all_votes');
}

while ($row = $result->fetch()) {
    $row['vote_time'] = nv_date('H:i d/m/Y', $row['vote_time']);
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';