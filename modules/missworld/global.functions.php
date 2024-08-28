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
    global $db, $module_data;

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
            (contestant_id, userid, voter_name, email, vote_time, is_verified) 
            VALUES 
            (" . $contestant_id . ", 
            " . $userid . ", 
            " . $db->quote($voter_name) . ", 
            " . $db->quote($email) . ", 
            " . NV_CURRENTTIME . ",
            1)";
    $db->query($sql);

    $new_vote_count = $contestant['vote'] + 1;

    return array('success' => true, 'message' => 'Bình chọn thành công.', 'newVoteCount' => $new_vote_count);
}

function nv_create_email_verification($contestant_id, $voter_name, $email, $verification_code) {
    global $db, $module_data;

    $expires_in = 600; // Hết hạn 10 phút
    $expiration_time = NV_CURRENTTIME + $expires_in;

    // Kiểm tra số lần gửi mã xác minh
    $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
            WHERE email = " . $db->quote($email) . " 
            AND created_at > " . (NV_CURRENTTIME - 3600); // Kiểm tra trong 1 giờ qua
    $resend_count = $db->query($sql)->fetchColumn();

    if ($resend_count >= 3) {
        return array('success' => false, 'message' => 'Bạn đã yêu cầu gửi lại mã xác minh quá nhiều lần. Vui lòng thử lại sau 1 giờ.');
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
        return array('success' => false, 'message' => 'Không thể tạo yêu cầu xác minh email.');
    }
}

function nv_send_verification_email($email, $verification_code, $expires_in)
{
    global $global_config, $module_name;

    $subject = "Xác minh email để hoàn tất bình chọn";
    $message = "Mã xác minh của bạn là: " . $verification_code;
    $message .= "\nMã này sẽ hết hạn sau " . ($expires_in / 60) . " phút.";
    $message .= "\nVui lòng nhập mã này vào trang web để hoàn tất quá trình bình chọn.";

    $from = array($global_config['site_name'], $global_config['site_email']);
    $is_sent = nv_sendmail($from, $email, $subject, $message);

    if ($is_sent) {
        return array('success' => true, 'message' => 'Email đã được gửi thành công');
    } else {
        return array('success' => false, 'message' => 'Không thể gửi email xác minh.');
    }
}

function nv_verify_and_vote($contestant_id, $email, $verification_code)
{
    global $db, $module_data;

    try {
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
                WHERE email = " . $db->quote($email) . " 
                AND verification_code = " . $db->quote($verification_code) . " 
                AND contestant_id = " . intval($contestant_id) . " 
                AND expires_at > " . NV_CURRENTTIME;

        $verification = $db->query($sql)->fetch();

        if (empty($verification)) {
            return array('success' => false, 'message' => 'Mã xác minh không hợp lệ hoặc đã hết hạn.');
        }

        // Xóa bản ghi mã xác minh
        $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications WHERE id = " . $verification['id']);

        // Tiến hành bình chọn
        $result = nv_vote_contestant($contestant_id, $verification['voter_name'], $email);
        
        return $result;
    } catch (Exception $e) {
        error_log("Error in nv_verify_and_vote: " . $e->getMessage());
        return array('success' => false, 'message' => 'Đã xảy ra lỗi khi xác minh và bình chọn. Vui lòng thử lại sau.');
    }
}

function nv_resend_verification_code($email, $contestant_id)
{
    global $db, $module_data;

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
            return array('success' => false, 'message' => 'Bạn đã yêu cầu gửi lại mã xác minh quá nhiều lần. Vui lòng thử lại sau 1 giờ.');
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
        return array('success' => false, 'message' => 'Mã xác minh cũ vẫn còn hiệu lực. Vui lòng kiểm tra email của bạn.');
    }
}