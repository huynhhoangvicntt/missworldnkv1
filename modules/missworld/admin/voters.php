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

// Xóa bỏ 1 hoặc nhiều
if ($nv_Request->get_title('delete', 'post', '') === NV_CHECK_SESSION) {
    $id = $nv_Request->get_int('vote_id', 'post', 0);
    $listid = $nv_Request->get_title('listid', 'post', '');
    $listid = $listid . ',' . $id;
    $listid = array_filter(array_unique(array_map('intval', explode(',', $listid))));

    foreach ($listid as $id) {
        // Kiểm tra tồn tại
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes WHERE id=" . $id;
        $vote = $db->query($sql)->fetch();
        if (!empty($vote)) {
            // Lấy contestant_id trước khi xóa bình chọn
            $contestant_id = $vote['contestant_id'];

            // Ghi log
            nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DELETE_VOTE', json_encode($vote), $admin_info['admin_id']);

            // Xóa thông báo liên quan đến vote này
            nv_delete_notification(NV_LANG_DATA, $module_name, 'new_vote', $contestant_id);

            // Xóa
            $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes WHERE id=" . $id;
            $db->query($sql);

            // Cập nhật lượt bình chọn
            if ($contestant_id) {
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET vote = vote - 1 WHERE id = ' . $contestant_id);
            }
        }
    }

    $nv_Cache->delMod($module_name);
    nv_htmlOutput("OK");
}

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

$db->select('v.id as vote_id, v.*, r.fullname AS contestant_name')
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
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('OP', $op);

if ($contestant_id > 0) {
    $xtpl->assign('CONTESTANT', $contestant);
    $xtpl->parse('main.contestant_votes');
} else {
    $xtpl->parse('main.all_votes');
    $xtpl->parse('main.contestant_column');
}

while ($row = $result->fetch()) {
    $row['vote_time'] = nv_date('H:i d/m/Y', $row['vote_time']);
    $xtpl->assign('ROW', $row);
    if ($contestant_id == 0) {
        $xtpl->parse('main.loop.contestant_name');
    }
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
