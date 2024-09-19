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

// Lấy ra thông tin thí sinh xem chi tiết
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE alias=" . $db->quote($alias);
$row = $db->query($sql)->fetch();
if (empty($row)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}

// Các biến cần thiết: Tiêu đề, từ khóa, mô tả
$page_title = $row['fullname'];
$key_words = $row['keywords'];
$description = $row['fullname'] . ' - ' . $lang_module['height'] . ': ' . $row['height'] . 'cm';

// Các biến cần thiết: Link của trang
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
$canonicalUrl = getCanonicalUrl($page_url);

// Meta OG chia sẻ facebook, zalo, dữ liệu có cấu trúc
$structured_data = [
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => $row['fullname'],
    'description' => $description,
    'url' => $canonicalUrl,
    'datePublished' => date('c', $row['time_add']),
    'author' => [[
        '@type' => 'Organization',
        'name' => $global_config['site_name'],
        'url' => NV_MY_DOMAIN,
    ]]
];
if (!empty($row['time_update'])) {
    $structured_data['dateModified'] = date('c', $row['time_update']);
}

if ($row['is_thumb'] == 3) {
    $meta_property['og:image'] = $row['image'];
    $structured_data['image'] = [$meta_property['og:image']];
} elseif ($row['is_thumb'] > 0) {
    $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
    $structured_data['image'] = [$meta_property['og:image']];
} elseif (!empty($module_info['site_logo'])) {
    $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_info['site_logo'];
    $structured_data['image'] = [$meta_property['og:image']];
}

$row['structured_data'] = json_encode($structured_data, JSON_PRETTY_PRINT);

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

    $row['comment_content'] = nv_comment_module($module_name, $checkss, NV_COMM_AREA, NV_COMM_ID, $allowed, 1);
} else {
    $row['comment_content'] = '';
}

// Xử lý thông tin thêm của thí sinh
$row['dob'] = empty($row['dob']) ? '' : nv_date('d/m/Y', $row['dob']);
$row['height'] = number_format($row['height'], 2);
$row['chest'] = number_format($row['chest'], 2);
$row['waist'] = number_format($row['waist'], 2);
$row['hips'] = number_format($row['hips'], 2);

// Tính toán xếp hạng
$sql_rank = "SELECT (SELECT COUNT(DISTINCT vote) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE vote > " . $row['vote'] . ") + 1 AS rank";
$result_rank = $db->query($sql_rank);
$row['rank'] = $result_rank->fetchColumn();

// Xử lý hình ảnh
if ($row['is_thumb'] == 1) {
    $row['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['image'];
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
} elseif ($row['is_thumb'] == 2) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
    $row['thumb'] = $row['image'];
} elseif ($row['is_thumb'] == 3) {
    $row['thumb'] = $row['image'];
} else {
    $row['thumb'] = $row['image'] = '';
}

if (empty($row['image'])) {
    $row['image'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/default.jpg';
    $row['thumb'] = $row['image'];
}

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

// Gọi hàm xử lý giao diện
$contents = nv_theme_missworld_detail($row);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';