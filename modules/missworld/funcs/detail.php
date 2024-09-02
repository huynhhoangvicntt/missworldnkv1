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

$array = [];

$id = $nv_Request->get_int('id', 'get', 0);
if ($id > 0) {
    // Lấy thông tin thí sinh
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id = " . $id;
    $result = $db->query($sql);
    if (!$row = $result->fetch()) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=main');
    }
    
    // Tính toán xếp hạng
    $sql_rank = "SELECT 
                    (SELECT COUNT(DISTINCT vote) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows 
                     WHERE vote > " . $row['vote'] . ") + 1 AS rank";
    $result_rank = $db->query($sql_rank);
    $row['rank'] = $result_rank->fetchColumn();
    
    // Xử lý tiếp theo
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
    
    // Thêm thông tin xếp hạng vào mảng $row
    $row['rank_text'] = $lang_module['current_rank'] . ': ' . $row['rank'];

    $row['dob'] = empty($row['dob']) ? '' : nv_date('d/m/Y', $row['dob']);

    // Kiểm tra xem có phải là yêu cầu AJAX không
    $is_ajax = $nv_Request->isset_request('ajax', 'get');

    // Pagination for voting history
    $page = $nv_Request->get_int('page', 'get', 1);
    $per_page = 2;
    $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $id;

    // Get total number of votes
    $sql_count = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes WHERE contestant_id = " . $id;
    $total_votes = $db->query($sql_count)->fetchColumn();

    // Get paginated voting history
    $sql_votes = "SELECT v.email, v.vote_time 
        FROM " . NV_PREFIXLANG . "_" . $module_data . "_votes v
        WHERE v.contestant_id = " . $id . " 
        ORDER BY v.vote_time DESC 
        LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;

    $result_votes = $db->query($sql_votes);
    $voting_history = [];
    while ($vote = $result_votes->fetch()) {
        // Ẩn 3 ký tự của email
        $email = $vote['email'];
        $atpos = strpos($email, '@');
        if ($atpos !== false) {
            $username = substr($email, 0, $atpos);
            $domain = substr($email, $atpos);

            if (strlen($username) > 3) {
                $hidden_username = substr($username, 0, -3) . '***';
            } else {
                $hidden_username = '***' . substr($username, 3);
            }

            $hidden_email = $hidden_username . $domain;
        } else {
            // Trường hợp email không hợp lệ
            $hidden_email = $email;
        }

        $voting_history[] = [
            'email' => $hidden_email,
            'vote_time' => nv_date('H:i d/m/Y', $vote['vote_time'])
        ];
    }
    $row['voting_history'] = $voting_history;

    // Generate pagination
    $generate_page = nv_generate_page($base_url, $total_votes, $per_page, $page);

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATA', $row);

    // Xử lý lịch sử bình chọn
    if (!empty($row['voting_history'])) {
        $voting_history_content = '';
        foreach ($row['voting_history'] as $vote) {
            $voting_history_content .= '<tr>
                <td class="voter-email"><div class="email-wrapper" title="' . $vote['email'] . '"><span class="email-text">' . $vote['email'] . '</span></div></td>
                <td class="vote-time">' . $vote['vote_time'] . '</td>
            </tr>';
        }
        $xtpl->assign('VOTING_HISTORY_CONTENT', $voting_history_content);
        $xtpl->parse('main.voting_history');
    } else {
        $xtpl->parse('main.no_votes');
    }

    // Xử lý phân trang
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    if ($is_ajax) {
        $voting_history_content = '';
        if (!empty($row['voting_history'])) {
            $voting_history_content .= '<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="col-voter-email">' . $lang_module['voter_email'] . '</th>
                            <th class="col-vote-time">' . $lang_module['vote_time'] . '</th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach ($row['voting_history'] as $vote) {
                $voting_history_content .= '<tr>
                    <td class="voter-email"><div class="email-wrapper" title="' . $vote['email'] . '"><span class="email-text">' . $vote['email'] . '</span></div></td>
                    <td class="vote-time">' . $vote['vote_time'] . '</td>
                </tr>';
            }
            $voting_history_content .= '</tbody></table></div>';
        } else {
            $voting_history_content = '<p class="no-votes-message">' . $lang_module['no_votes_yet'] . '</p>';
        }
        
        $json = [
            'content' => $voting_history_content,
            'pagination' => $generate_page
        ];
        
        nv_jsonOutput($json);
    }
} else {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=main');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';