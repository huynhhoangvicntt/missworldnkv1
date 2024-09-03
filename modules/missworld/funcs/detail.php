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

$array_data = [];

$id = $nv_Request->get_int('id', 'get', 0);

if ($id > 0) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id;
    $result = $db->query($sql);
    $row = $result->fetch();
    
    if (empty($row)) {
        nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main");
    }
    
    // Calculate rank
    $sql_rank = "SELECT (SELECT COUNT(DISTINCT vote) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE vote>" . $row['vote'] . ") + 1 AS rank";
    $result_rank = $db->query($sql_rank);
    $row['rank'] = $result_rank->fetchColumn();
    
    // Process image
    if (!empty($row['image'])) {
        $image_path = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'];
        $row['image'] = (file_exists($image_path)) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'] : '';
    }
    
    $row['rank_text'] = $lang_module['current_rank'] . ': ' . $row['rank'];
    $row['dob'] = empty($row['dob']) ? '' : nv_date('d/m/Y', $row['dob']);

    // Get recent votes
    $sql_votes = "SELECT v.email, v.vote_time 
                  FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes v
                  WHERE v.contestant_id=" . $id . " 
                  ORDER BY v.vote_time DESC 
                  LIMIT 20";
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
    $array_data = $row;
} else {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main");
}

$contents = nv_theme_missworld_detail($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';