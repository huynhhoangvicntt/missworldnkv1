<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
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
