<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * File này dùng để gọi khi bình luận trên bài đăng hoặc xóa bình luận, duyệt bình luận trong quản trị
 * Cập nhật lại thống kê số bình luận cho bài đăng.
 * Xóa nếu không dùng chức năng bình luận
 */

try {
    // Tính số bình luận
    $sql = "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_comment
    WHERE module=" . $db->quote($row['module']) . " AND id=" . $row['id'] . " AND status=1";
    $num_comments = $db->query($sql)->fetchColumn();

    // Cập nhật thống kê cho thí sinh
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $mod_info['module_data'] . "_rows SET comment_hits=" . $num_comments . " WHERE id=" . $row['id'];
    $db->query($sql);
} catch (Exception $e) {
    trigger_error(print_r($e, true));
}
