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

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

$array_data = [];

// Lấy alias từ URL
$alias = $nv_Request->get_title('alias', 'get', '');
if (empty($alias)) {
    $alias = isset($array_op[0]) ? $array_op[0] : '';
}

if (!empty($alias)) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE alias=" . $db->quote($alias);
    $result = $db->query($sql);
    $row = $result->fetch();
    
    if (empty($row)) {
        nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
    }
    
    // Cập nhật tiêu đề trang
    $page_title = !empty($row['fullname']) ? $row['fullname'] : $page_title;
    $key_words = !empty($row['keywords']) ? $row['keywords'] : $key_words;
    $description = !empty($row['description']) ? $row['description'] : $description;
    
    // Tính toán xếp hạng
    $sql_rank = "SELECT (SELECT COUNT(DISTINCT vote) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE vote > " . $row['vote'] . ") + 1 AS rank";
    $result_rank = $db->query($sql_rank);
    $row['rank'] = $result_rank->fetchColumn();
    
    // Xử lý hình ảnh
    $images_default = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/default.jpg';
    if (!empty($row['image'])) {
        $image_path = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'];
        if (file_exists($image_path)) {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
        } else {
            $row['image'] = $images_default;
        }
    } else {
        $row['image'] = $images_default;
    }
    
    $row['rank_text'] = $lang_module['current_rank'] . ': ' . $row['rank'];
    $row['dob'] = empty($row['dob']) ? '' : nv_date('d/m/Y', $row['dob']);

    // Lấy 20 phiếu bầu gần nhất
    $sql_votes = "SELECT v.email, v.vote_time FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes v WHERE v.contestant_id=" . $row['id'] . " ORDER BY v.vote_time DESC LIMIT 20";
    $result_votes = $db->query($sql_votes);
    $voting_history = [];
    
    while ($vote = $result_votes->fetch()) {
        $email = $vote['email'];
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
            'vote_time' => nv_date('H:i d/m/Y', $vote['vote_time'])
        ];
    }
    
    $row['voting_history'] = $voting_history;

    // Xử lý bình luận
    if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
        define('NV_COMM_ID', $row['id']);
        define('NV_COMM_AREA', $module_info['funcs'][$op]['func_id']);
        
        $allowed = $module_config[$module_name]['allowed_comm'];
        if ($allowed == '-1') {
            $allowed = '4';
        }
        require_once NV_ROOTDIR . '/modules/comment/comment.php';
        $checkss = md5($module_name . '-' . NV_COMM_AREA . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX);

        $content_comment = nv_comment_module($module_name, $checkss, NV_COMM_AREA, NV_COMM_ID, $allowed, 1);
        $row['comment_content'] = $content_comment;
    } else {
        $row['comment_content'] = '';
    }

    $array_data = $row;
} else {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
}

$contents = nv_theme_missworld_detail($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';