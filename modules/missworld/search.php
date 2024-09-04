<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_IS_MOD_SEARCH')) {
    exit('Stop!!!');
}

$db_slave->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_missworld_rows')
    ->where('status=1 AND (' . nv_like_logic('fullname', $dbkeyword, $logic) . ' OR ' . nv_like_logic('keywords', $dbkeyword, $logic) . ')');
$num_items = $db->query($db_slave->sql())->fetchColumn();

if ($num_items) {
    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=detail';

    $db->select('*')
        ->limit($limit)
        ->offset(($page - 1) * $limit);
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        $result_array[] = [
            'link' => $link . change_alias($row['fullname']) . $row['id'] . $global_config['rewrite_exturl'],
            'title' => BoldKeywordInStr($row['fullname'], $key, $logic),
            'content' => BoldKeywordInStr($row['fullname'] . ' ' . $row['keywords'], $key, $logic)
        ];
    }
}
