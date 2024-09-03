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

include NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

// Xử lý các yêu cầu AJAX
if ($nv_Request->isset_request('action', 'post')) {
    try {
        $action = $nv_Request->get_string('action', 'post', '');
        
        if ($action === 'check_user') {
            if (defined('NV_IS_USER')) {
                $user_info = isset($user_info) ? $user_info : array();
                nv_jsonOutput(array(
                    'success' => true,
                    'isLoggedIn' => true,
                    'fullname' => isset($user_info['full_name']) ? $user_info['full_name'] : '',
                    'email' => isset($user_info['email']) ? $user_info['email'] : ''
                ));
            } else {
                nv_jsonOutput(array(
                    'success' => true,
                    'isLoggedIn' => false
                ));
            }
        } elseif ($action === 'vote') {
            $contestant_id = $nv_Request->get_int('contestant_id', 'post', 0);
            $voter_name = $nv_Request->get_title('voter_name', 'post', '');
            $email = $nv_Request->get_title('email', 'post', '');

            // Kiểm tra dữ liệu đầu vào
            $errors = [];
            if (empty($voter_name)) {
                $errors[] = $lang_module['fullname_empty_error'];
            }
            if (empty($email)) {
                $errors[] = $lang_module['email_empty_error'];
            } elseif (nv_check_valid_email($email) != '') {
                $errors[] = $lang_module['email_invalid_error'];
            }

            if (!empty($errors)) {
                nv_jsonOutput(array('success' => false, 'message' => implode(', ', $errors)));
            }

            // Kiểm tra trạng thái đăng nhập
            if (defined('NV_IS_USER')) {
                // Người dùng đã đăng nhập - giữ nguyên logic ban đầu
                $result = nv_vote_contestant($contestant_id, $voter_name, $email, $user_info['userid']);
                nv_jsonOutput($result);
            } else {
                // Người dùng chưa đăng nhập
                $vote_status = nv_check_vote_status($contestant_id, $email);
                
                if ($vote_status === 'voted_for_contestant') {
                    nv_jsonOutput(array('success' => false, 'message' => $lang_module['already_voted']));
                } else {
                    // Kiểm tra xem có mã xác minh đang chờ không
                    $pending_verification = nv_check_pending_verification($email, $contestant_id);
                    if ($pending_verification) {
                        nv_jsonOutput(array('success' => true, 'requiresVerification' => true, 'message' => $lang_module['verification_pending']));
                    } else {
                        // Người dùng chưa bình chọn cho thí sinh này - bắt đầu quá trình xác minh
                        $verification_code = nv_genpass(6);
                        $result = nv_create_email_verification($contestant_id, $voter_name, $email, $verification_code);
                        if ($result['success']) {
                            $email_result = nv_send_verification_email($email, $verification_code, $result['expires_in']);
                            if ($email_result['success']) {
                                nv_jsonOutput(array('success' => true, 'requiresVerification' => true, 'message' => $lang_module['email_verification']));
                            } else {
                                nv_jsonOutput(array('success' => false, 'message' => $lang_module['email_verification_failed']));
                            }
                        } else {
                            nv_jsonOutput($result);
                        }
                    }
                }
            }
        } elseif ($action === 'verify') {
            $contestant_id = $nv_Request->get_int('contestant_id', 'post', 0);
            $email = $nv_Request->get_title('email', 'post', '');
            $verification_code = $nv_Request->get_title('verification_code', 'post', '');

            $result = nv_verify_and_vote($contestant_id, $email, $verification_code);
            nv_jsonOutput($result);
        } elseif ($action === 'resend_verification') {
            $email = $nv_Request->get_title('email', 'post', '');
            $contestant_id = $nv_Request->get_int('contestant_id', 'post', 0);
            
            $result = nv_resend_verification_code($email, $contestant_id);
            nv_jsonOutput($result);
        } elseif ($action === 'delete_verification') {
            $email = $nv_Request->get_title('email', 'post', '');
            $contestant_id = $nv_Request->get_int('contestant_id', 'post', 0);
            
            $result = nv_delete_verification_code($email, $contestant_id);
            nv_jsonOutput($result);
        } else {
            nv_jsonOutput(array('success' => false, 'message' => $lang_module['invalid_action']));
        }
    } catch (Exception $e) {
        error_log("Error in AJAX request: " . $e->getMessage());
        nv_jsonOutput(array('success' => false, 'message' => $lang_module['error_occurred']));
    }
}

// Xử lý hiển thị danh sách thí sinh
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$array = [];
$per_page = 12;
$page = $nv_Request->get_int('page', 'get', 1);

// Lấy tổng số thí sinh
$keyword = $nv_Request->get_title('keyword', "get", '');
$db->sqlreset()
   ->select('COUNT(*)')
   ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
   ->where("(fullname LIKE " . $db->quote('%' . $keyword . '%') . " OR keywords LIKE " . $db->quote('%' . $keyword . '%') . ") AND status = 1");
$total = $db->query($db->sql())->fetchColumn();

// Lấy danh sách thí sinh cho trang hiện tại
$db->select('*')
   ->where('status = 1')
   ->order('id DESC')  // Sắp xếp theo id giảm dần
   ->limit($per_page)
   ->offset(($page - 1) * $per_page);

$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $row['alias'];
    $array_data[$row['id']] = $row;
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
if ($keyword != '') {
    $base_url .= '&keyword = ' . $keyword;
}
// Tạo phân trang
$generate_page = nv_generate_page($base_url, $total, $per_page, $page);

$contents = nv_theme_missworld_main($array_data, $generate_page, $keyword);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';