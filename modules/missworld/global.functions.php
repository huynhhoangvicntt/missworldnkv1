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

/**
 * nv_check_vote_status
 * 
 * Kiểm tra trạng thái bình chọn của người dùng cho một thí sinh cụ thể
 *
 * @param int $contestant_id ID của thí sinh
 * @param string $email Email của người bình chọn
 * @return string Trạng thái bình chọn ('voted_for_contestant', 'not_voted')
 */
function nv_check_vote_status($contestant_id, $email)
{
    global $db, $module_data;

    $sql = "SELECT contestant_id FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes WHERE email = :email AND contestant_id = :contestant_id";
    $sth = $db->prepare($sql);
    $sth->bindParam(':email', $email, PDO::PARAM_STR);
    $sth->bindParam(':contestant_id', $contestant_id, PDO::PARAM_INT);
    $sth->execute();
    
    if ($sth->fetchColumn()) {
        return 'voted_for_contestant';
    }

    return 'not_voted';
}

/**
 * nv_vote_contestant
 * 
 * Thực hiện bình chọn cho thí sinh
 *
 * @param int $contestant_id ID của thí sinh
 * @param string $voter_name Tên người bình chọn
 * @param string $email Email của người bình chọn
 * @param int $userid ID của người dùng (nếu đã đăng nhập)
 * @return array Kết quả bình chọn
 */
function nv_vote_contestant($contestant_id, $voter_name, $email, $userid = 0)
{
    global $db, $module_data, $lang_module, $module_name;

    $contestant_id = intval($contestant_id);

    // Kiểm tra tồn tại thí sinh
    $sql = "SELECT id, vote, fullname, alias FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $contestant_id;
    $result = $db->query($sql);
    $contestant = $result->fetch();

    if (empty($contestant)) {
        return ['success' => false, 'message' => $lang_module['contestant_not_exist']];
    }

    // Kiểm tra xem người dùng đã bỏ phiếu chưa
    $vote_status = nv_check_vote_status($contestant_id, $email);
    if ($vote_status === 'voted_for_contestant') {
        return ['success' => false, 'message' => $lang_module['already_voted']];
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
    $vote_id = $db->insert_id($sql);

    if ($vote_id) {
        // Tạo thông báo cho admin
        $notify_content = [
            'voter_name' => $voter_name,
            'contestant_name' => $contestant['fullname'],
            'contestant_id' => $contestant_id,
            'alias' => $contestant['alias'] // Thêm alias vào nội dung thông báo
        ];
        nv_insert_notification($module_name, 'new_vote', $notify_content, $contestant_id);
    }

    // Lấy số vote mới
    $sql = "SELECT vote FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $contestant_id;
    $new_vote_count = $db->query($sql)->fetchColumn();

    return ['success' => true, 'message' => $lang_module['vote_success'], 'newVoteCount' => $new_vote_count];
}

/**
 * nv_create_email_verification
 * 
 * Tạo mã xác minh email
 *
 * @param int $contestant_id ID của thí sinh
 * @param string $voter_name Tên người bình chọn
 * @param string $email Email của người bình chọn
 * @param string $verification_code Mã xác minh
 * @return array Kết quả tạo mã xác minh
 */
function nv_create_email_verification($contestant_id, $voter_name, $email, $verification_code) {
    global $db, $module_data, $lang_module;

    $expires_in = 600; // Hết hạn 10 phút
    $expiration_time = NV_CURRENTTIME + $expires_in;

    // Xóa các mã xác minh cũ cho email và contestant_id này
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
            WHERE email = " . $db->quote($email) . " 
            AND contestant_id = " . intval($contestant_id);
    $db->query($sql);

    // Kiểm tra số lần gửi mã xác minh
    $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
            WHERE email = " . $db->quote($email) . " 
            AND created_at > " . (NV_CURRENTTIME - 3600); // Kiểm tra trong 1 giờ qua
    $resend_count = $db->query($sql)->fetchColumn();

    if ($resend_count >= 3) {
        return ['success' => false, 'message' => $lang_module['verification_limit_reached']];
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
        return ['success' => true, 'expires_in' => $expires_in];
    } else {
        return ['success' => false, 'message' => $lang_module['verification_create_fail']];
    }
}

/**
 * nv_send_verification_email
 * 
 * Gửi email xác minh
 *
 * @param string $email Email của người bình chọn
 * @param string $verification_code Mã xác minh
 * @param int $expires_in Thời gian hết hạn (giây)
 * @return array Kết quả gửi email
 */
function nv_send_verification_email($email, $verification_code, $expires_in)
{
    global $global_config, $module_name, $lang_module;

    $subject =  $lang_module['verification_email_subject'];
    $message = $lang_module['verification_code_is'] . ' ' . $verification_code . "\n";
    $message .= $lang_module['code_expires_in'] . ' ' . ($expires_in / 60) . ' ' . $lang_module['minutes'] . "\n";
    $message .= $lang_module['enter_code_to_complete_voting'];
    $message = nl2br($message);

    $from = [$global_config['site_name'], $global_config['site_email']];
    $is_sent = nv_sendmail($from, $email, $subject, $message);

    if ($is_sent) {
        return ['success' => true, 'message' => $lang_module['email_sent_success']];
    } else {
        return ['success' => false, 'message' => $lang_module['email_send_fail']];
    }
}

/**
 * nv_verify_and_vote
 * 
 * Xác minh mã và thực hiện bình chọn
 *
 * @param int $contestant_id ID của thí sinh
 * @param string $email Email của người bình chọn
 * @param string $verification_code Mã xác minh
 * @return array Kết quả xác minh và bình chọn
 */
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
            return ['success' => false, 'message' => $lang_module['verification_invalid']];
        }

        // Xóa bản ghi mã xác minh
        $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications WHERE id = " . $verification['id']);

        // Tiến hành bình chọn
        return nv_vote_contestant($contestant_id, $verification['voter_name'], $email);
    } catch (Exception $e) {
        error_log("Error in nv_verify_and_vote: " . $e->getMessage());
        return ['success' => false, 'message' => $lang_module['verification_vote_error']];
    }
}

/**
 * nv_resend_verification_code
 * 
 * Gửi lại mã xác minh
 *
 * @param string $email Email của người bình chọn
 * @param int $contestant_id ID của thí sinh
 * @return array Kết quả gửi lại mã xác minh
 */
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
            return ['success' => false, 'message' => $lang_module['verification_limit_reached']];
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
        return ['success' => false, 'message' => $lang_module['verification_still_valid']];
    }
}

/**
 * nv_delete_verification_code
 * 
 * Xóa mã xác minh
 *
 * @param string $email Email của người bình chọn
 * @param int $contestant_id ID của thí sinh
 * @return array Kết quả xóa mã xác minh
 */
function nv_delete_verification_code($email, $contestant_id)
{
    global $db, $module_data, $lang_module;

    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
            WHERE email = " . $db->quote($email) . " 
            AND contestant_id = " . intval($contestant_id);
    
    $db->query($sql);

    return ['success' => true, 'message' => $lang_module['verification_code_deleted']];
}

/**
 * nv_check_pending_verification
 * 
 * Kiểm tra xem có mã xác minh đang chờ xử lý không
 *
 * @param string $email Email của người bình chọn
 * @param int $contestant_id ID của thí sinh
 * @return bool Có mã xác minh đang chờ hay không
 */
function nv_check_pending_verification($email, $contestant_id)
{
    global $db, $module_data;

    $sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_email_verifications 
            WHERE email = :email AND contestant_id = :contestant_id AND expires_at > " . NV_CURRENTTIME;
    $sth = $db->prepare($sql);
    $sth->bindParam(':email', $email, PDO::PARAM_STR);
    $sth->bindParam(':contestant_id', $contestant_id, PDO::PARAM_INT);
    $sth->execute();
    
    return $sth->fetchColumn() > 0;
}

/**
 * Định dạng số đo 3 vòng
 * 
 * @param float $chest Số đo vòng ngực
 * @param float $waist Số đo vòng eo
 * @param float $hips Số đo vòng hông
 * @return string Chuỗi số đo đã định dạng
 */
function formatMeasurements($chest, $waist, $hips) {
    return number_format($chest, 2) . ' - ' . number_format($waist, 2) . ' - ' . number_format($hips, 2);
}

/**
 * nv_get_voting_history()
 * 
 * @param int $contestant_id
 * @return array
 */
function nv_get_voting_history($contestant_id)
{
    global $db, $module_data;

    $sql = "SELECT email, vote_time FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes 
            WHERE contestant_id = :contestant_id 
            ORDER BY vote_time DESC 
            LIMIT 20";

    $sth = $db->prepare($sql);
    $sth->bindParam(':contestant_id', $contestant_id, PDO::PARAM_INT);
    $sth->execute();

    $voting_history = [];
    while ($row = $sth->fetch()) {
        $email = $row['email'];
        $atpos = strpos($email, '@');
        
        if ($atpos !== false) {
            $username = substr($email, 0, $atpos);
            $domain = substr($email, $atpos);
            $hidden_username = (strlen($username) > 3) ? substr($username, 0, -3) . '***' : '***' . substr($username, 3);
            $hidden_email = $hidden_username . $domain;
        } else {
            $hidden_email = $email;
        }

        $voting_history[] = [
            'email' => $hidden_email,
            'vote_time' => nv_date('H:i d/m/Y', $row['vote_time'])
        ];
    }

    return $voting_history;
}

/**
 * nv_get_contestant_vote_count()
 * 
 * @param int $contestant_id
 * @return array
 */
function nv_get_contestant_vote_count($contestant_id) {
    global $db, $module_data;

    $sql = "SELECT vote FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id = " . intval($contestant_id);
    $result = $db->query($sql);
    
    return $result->fetchColumn();
}
