<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

function nv_vote_contestant($contestant_id, $fullname, $email)
{
    global $db, $user_info, $module_data;

    $contestant_id = intval($contestant_id);

    // Kiểm tra tồn tại thí sinh
    $sql = "SELECT id, vote FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $contestant_id;
    $result = $db->query($sql);
    $contestant = $result->fetch();

    if (empty($contestant)) {
        return array('success' => false, 'message' => 'Thí sinh không tồn tại.');
    }

    // Kiểm tra xem người dùng đã bỏ phiếu chưa
    $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes WHERE contestant_id=" . $contestant_id . " AND email='" . $db->dblikeescape($email) . "'";
    $has_voted = $db->query($sql)->fetchColumn();

    if ($has_voted) {
        return array('success' => false, 'message' => 'Bạn đã bình chọn cho thí sinh này rồi.');
    }

    // Cập nhật số lượt bình chọn
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET vote = vote + 1 WHERE id=" . $contestant_id;
    $db->query($sql);

    $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_votes 
            (contestant_id, userid, fullname, email, vote_time) 
            VALUES 
            (" . $contestant_id . ", 
            " . (defined('NV_IS_USER') ? $user_info['userid'] : 0) . ", 
            " . $db->quote($fullname) . ", 
            " . $db->quote($email) . ", 
            " . NV_CURRENTTIME . ")";
    $db->query($sql);

    $new_vote_count = $contestant['vote'] + 1;

    return array('success' => true, 'message' => 'Bình chọn thành công.', 'newVoteCount' => $new_vote_count);
}