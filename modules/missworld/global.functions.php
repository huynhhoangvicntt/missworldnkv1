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

function nv_vote_contestant($contestant_id, $voter_name, $email, $userid = 0)
{
    global $db, $module_data, $lang_module, $user_info;

    $contestant_id = intval($contestant_id);

    // Kiểm tra tồn tại thí sinh
    $sql = "SELECT id, vote FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $contestant_id;
    $result = $db->query($sql);
    $contestant = $result->fetch();

    if (empty($contestant)) {
        return array('success' => false, 'message' => $lang_module['contestant_not_exist']);
    }

    // Kiểm tra xem người dùng đã bỏ phiếu chưa
    $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes WHERE contestant_id=" . $contestant_id . " AND email='" . $db->dblikeescape($email) . "'";
    $has_voted = $db->query($sql)->fetchColumn();

    if ($has_voted) {
        return array('success' => false, 'message' => $lang_module['already_voted']);
    }

    // Cập nhật số lượt bình chọn
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET vote = vote + 1 WHERE id=" . $contestant_id;
    $db->query($sql);

    // Thêm bản ghi bình chọn
    $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_votes 
            (contestant_id, userid, voter_name, email, vote_time, is_verified) 
            VALUES 
            (" . $contestant_id . ", 
            " . $userid . ", 
            " . $db->quote($voter_name) . ", 
            " . $db->quote($email) . ", 
            " . NV_CURRENTTIME . ",
            1)";
    $db->query($sql);

    // Lấy số vote mới
    $sql = "SELECT vote FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $contestant_id;
    $new_vote_count = $db->query($sql)->fetchColumn();

    return array('success' => true, 'message' => $lang_module['vote_success'], 'newVoteCount' => $new_vote_count);
}

function nv_create_email_verification($contestant_id, $voter_name, $email, $verification_code) {
    global $db, $module_data, $lang_module;

    $expires_in = 600; // Hết hạn 10 phút
    $expiration_time = NV_CURRENTTIME + $expires_in;

    // Kiểm tra số lần gửi mã xác minh
    $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
            WHERE email = " . $db->quote($email) . " 
            AND created_at > " . (NV_CURRENTTIME - 3600); // Kiểm tra trong 1 giờ qua
    $resend_count = $db->query($sql)->fetchColumn();

    if ($resend_count >= 3) {
        return array('success' => false, 'message' => $lang_module['verification_limit_reached']);
    }

    $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
            (email, verification_code, contestant_id, voter_name, created_at, expires_at) 
            VALUES 
            (" . $db->quote($email) . ", 
            " . $db->quote($verification_code) . ", 
            " . intval($contestant_id) . ", 
            " . $db->quote($voter_name) . ", 
            " . NV_CURRENTTIME . ", 
            " . $expiration_time . ")";

    if ($db->query($sql)) {
        return array('success' => true, 'expires_in' => $expires_in);
    } else {
        return array('success' => false, 'message' => $lang_module['verification_create_fail']);
    }
}

function nv_send_verification_email($email, $verification_code, $expires_in)
{
    global $global_config, $module_name, $lang_module;

    $subject =  $lang_module['verification_email_subject'];
    $message = $lang_module['verification_code_is'] . ' ' . $verification_code . "\n";
    $message .= $lang_module['code_expires_in'] . ' ' . ($expires_in / 60) . ' ' . $lang_module['minutes'] . "\n";
    $message .= $lang_module['enter_code_to_complete_voting'];
    $message = nl2br($message);

    $from = array($global_config['site_name'], $global_config['site_email']);
    $is_sent = nv_sendmail($from, $email, $subject, $message);

    if ($is_sent) {
        return array('success' => true, 'message' => $lang_module['email_sent_success']);
    } else {
        return array('success' => false, 'message' => $lang_module['email_send_fail']);
    }
}

function nv_verify_and_vote($contestant_id, $email, $verification_code)
{
    global $db, $module_data, $lang_module;

    try {
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
                WHERE email = " . $db->quote($email) . " 
                AND verification_code = " . $db->quote($verification_code) . " 
                AND contestant_id = " . intval($contestant_id) . " 
                AND expires_at > " . NV_CURRENTTIME;

        $verification = $db->query($sql)->fetch();

        if (empty($verification)) {
            return array('success' => false, 'message' => $lang_module['verification_invalid']);
        }

        // Xóa bản ghi mã xác minh
        $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications WHERE id = " . $verification['id']);

        // Tiến hành bình chọn
        return nv_vote_contestant($contestant_id, $verification['voter_name'], $email);
    } catch (Exception $e) {
        error_log("Error in nv_verify_and_vote: " . $e->getMessage());
        return array('success' => false, 'message' => $lang_module['verification_vote_error']);
    }
}

function nv_resend_verification_code($email, $contestant_id)
{
    global $db, $module_data, $lang_module;

    // Kiểm tra xem có yêu cầu xác minh cũ không
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
            WHERE email = " . $db->quote($email) . " 
            AND contestant_id = " . intval($contestant_id) . " 
            ORDER BY created_at DESC LIMIT 1";
    $old_verification = $db->query($sql)->fetch();

    if (empty($old_verification) || $old_verification['expires_at'] < NV_CURRENTTIME) {
        // Kiểm tra số lần gửi mã xác minh trong 1 giờ qua
        $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
                WHERE email = " . $db->quote($email) . " 
                AND created_at > " . (NV_CURRENTTIME - 3600);
        $resend_count = $db->query($sql)->fetchColumn();

        if ($resend_count >= 3) {
            return array('success' => false, 'message' => $lang_module['verification_limit_reached']);
        }

        // Tạo mã xác minh mới
        $verification_code = nv_genpass(6);
        $result = nv_create_email_verification($contestant_id, $old_verification['voter_name'], $email, $verification_code);
        
        if ($result['success']) {
            return nv_send_verification_email($email, $verification_code, $result['expires_in']);
        } else {
            return $result;
        }
    } else {
        return array('success' => false, 'message' => $lang_module['verification_still_valid']);
    }
}