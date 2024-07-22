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

/**
 * File này hỗ trợ tìm kiếm nhanh tại module seek.
 * Nếu không có tìm kiếm nhanh ở seek thì xóa đi
 */
$db->sqlreset()
->select('COUNT(*)')
->from(NV_PREFIXLANG . '_' . $m_values['module_data'] . '_rows')
->where('(' . nv_like_logic('title', $dbkeywordhtml, $logic) . ' OR ' . nv_like_logic('description', $dbkeyword, $logic) . ') AND status=1');

$num_items = $db->query($db->sql())->fetchColumn();

if ($num_items) {
    // Lấy danh mục ra để xuất link
    $array_cat_alias = [];
    $sql = "SELECT id, alias FROM " . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_cats WHERE status=1";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $array_cat_alias[$row['id']] = $row['alias'];
    }

    $db->select('id, title, alias, catid, description')
    ->order('add_time DESC')
    ->limit($limit)
    ->offset(($page - 1) * $limit);
    $result = $db->query($db->sql());
    while ($row = $result->fetch()) {
        if (isset($array_cat_alias[$row['catid']])) {
            $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=' . $array_cat_alias[$row['catid']] . '/' . $row['alias'] . $global_config['rewrite_exturl'];
        } else {
            $row['link'] = '#';
        }
        $result_array[] = [
            'link' => $row['link'],
            'title' => BoldKeywordInStr($row['title'], $key, $logic),
            'content' => BoldKeywordInStr($row['description'], $key, $logic)
        ];
    }
}