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

// Xử lý các yêu cầu AJAX
if ($nv_Request->isset_request('action', 'post')) {
    $action = $nv_Request->get_string('action', 'post', '');
    
    if ($action === 'check_user') {
        if (defined('NV_IS_USER')) {
            $user_info = isset($user_info) ? $user_info : [];
            nv_jsonOutput([
                'success' => true,
                'isLoggedIn' => true,
                'fullname' => isset($user_info['full_name']) ? $user_info['full_name'] : '',
                'email' => isset($user_info['email']) ? $user_info['email'] : ''
            ]);
        } else {
            nv_jsonOutput([
                'success' => true,
                'isLoggedIn' => false
            ]);
        }
    } elseif ($action === 'vote') {
        $contestant_id = $nv_Request->get_int('contestant_id', 'post', 0);
        $voter_name = $nv_Request->get_title('voter_name', 'post', '');
        $email = $nv_Request->get_title('email', 'post', '');

        // Kiểm tra dữ liệu đầu vào
        $error = array();
        if (empty($voter_name)) {
            $error[] = $lang_module['fullname_empty_error'];
        }
        if (empty($email)) {
            $error[] = $lang_module['email_empty_error'];
        } elseif (nv_check_valid_email($email) != '') {
            $error[] = $lang_module['email_invalid_error'];
        }

        if (!empty($error)) {
            $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
            $xtpl->assign('ERROR', implode('<br />', $error));
            $xtpl->parse('main.error');
            $error_content = $xtpl->text('main.error');
            nv_jsonOutput(['success' => false, 'error_content' => $error_content]);
        } else {
            if (defined('NV_IS_USER')) {
                $result = nv_vote_contestant($contestant_id, $voter_name, $email, $user_info['userid']);
                if (!isset($result['isToast'])) {
                    $result['isToast'] = true;
                }
            } else {
                $vote_status = nv_check_vote_status($contestant_id, $email);
                
                if ($vote_status === 'voted_for_contestant') {
                    $result = [
                        'success' => false,
                        'message' => $lang_module['already_voted'],
                        'isToast' => true
                    ];
                } else {
                    $pending_verification = nv_check_pending_verification($email, $contestant_id);
                    if ($pending_verification) {
                        $result = [
                            'success' => true,
                            'requiresVerification' => true,
                            'message' => $lang_module['verification_pending'],
                            'isToast' => true
                        ];
                    } else {
                        $verification_code = nv_genpass(6);
                        $result = nv_create_email_verification($contestant_id, $voter_name, $email, $verification_code);
                        if ($result['success']) {
                            $email_result = nv_send_verification_email($email, $verification_code, $result['expires_in']);
                            if ($email_result['success']) {
                                $result = [
                                    'success' => true,
                                    'requiresVerification' => true,
                                    'message' => $lang_module['email_verification'],
                                    'isToast' => true
                                ];
                            } else {
                                $result = [
                                    'success' => false,
                                    'message' => $lang_module['email_verification_failed'],
                                    'isToast' => true
                                ];
                            }
                        }
                    }
                }
            }
            nv_jsonOutput($result);
        }
    } elseif ($action === 'verify') {
        $contestant_id = $nv_Request->get_int('contestant_id', 'post', 0);
        $email = $nv_Request->get_title('email', 'post', '');
        $verification_code = $nv_Request->get_title('verification_code', 'post', '');

        $result = nv_verify_and_vote($contestant_id, $email, $verification_code);
        $result['isToast'] = true;
        nv_jsonOutput($result);
    } elseif ($action === 'resend_verification') {
        $email = $nv_Request->get_title('email', 'post', '');
        $contestant_id = $nv_Request->get_int('contestant_id', 'post', 0);
        
        $result = nv_resend_verification_code($email, $contestant_id);
        $result['isToast'] = true;
        nv_jsonOutput($result);
    } elseif ($action === 'delete_verification') {
        $email = $nv_Request->get_title('email', 'post', '');
        $contestant_id = $nv_Request->get_int('contestant_id', 'post', 0);
        
        $result = nv_delete_verification_code($email, $contestant_id);
        $result['isToast'] = true;
        nv_jsonOutput($result);
    } else {
        nv_jsonOutput(['success' => false, 'message' => $lang_module['invalid_action'], 'isToast' => true]);
    }
}

// Xử lý hiển thị danh sách thí sinh
$page_title = $module_info['fullname'];
$key_words = $module_info['keywords'];
$description = $module_info['body_dimensions'];

$array_data = [];
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
$db->select('*')->order('id DESC')->limit($per_page)->offset(($page - 1) * $per_page);

$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $row['alias'];
    $array_data[$row['id']] = $row;
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
if ($keyword != '') {
    $base_url .= '&amp;keyword=' . urlencode($keyword);
}

// Tạo phân trang
$generate_page = nv_generate_page($base_url, $total, $per_page, $page);

$contents = nv_theme_missworld_main($array_data, $generate_page, $keyword);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';