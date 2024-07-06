<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_MISSWORLD')) {
    exit('Stop!!!');
}

/**
 * @param array $array
 * @param array  $row
 * @param array $generate_page
 * @return string
 */
function nv_theme_main($array, $generate_page)
{
    global $lang_module, $lang_global, $module_info, $module_name, $module_file, $op_file, $module_upload, $array_onecat2s, $op, $num, $module_data, $db;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('OP', $op_file);
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_onecat2s ORDER BY weight ASC";
    $array_onecat2s = $db->query($sql)->fetchAll();
    $num = sizeof($array_onecat2s);
    foreach ($array_onecat2s as $row) {
        $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $row['id'];
        $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR. '/' . $module_upload. '/' . $row['image'];
        $row['url_view'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $row['id'];
        $row['status_render'] = empty($row['status']) ? '' : ' checked="checked"';
    
        for ($i = 1; $i <= $num; ++$i) {
            $xtpl->assign('WEIGHT', [
                'w' => $i,
                'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
            ]);
    
            $xtpl->parse('main.loop.weight');
        }
    
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.loop');
    }

    // Gọi hàm xử lý chung giao diện dạng danh sách, xuất ra HTML
    $xtpl->assign('HTML', nv_theme_item_list($array));

   

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_detail($array, $generate_page)
{
    global $lang_module, $lang_global, $module_info, $module_name, $module_file, $op_file, $module_upload, $array_onecat2s, $op, $num, $module_data, $db, $row;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $xtpl->assign('ROW', $row);
   

    // Gọi hàm xử lý chung giao diện dạng danh sách, xuất ra HTML
    $xtpl->assign('HTML', nv_theme_item_list($array));

   

    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**
 * @param array $array
 * @return string
 */
function nv_theme_item_list($array)
{
    global $lang_module, $lang_global, $module_info;

    $xtpl = new XTemplate('item-list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    foreach ($array as $row) {
        $row['add_time'] = nv_date('H:i d/m/Y', $row['add_time']);
        $row['title_text'] = $row['title_text'] ?? $row['title'];

        $xtpl->assign('ROW', $row);

        if (!empty($row['thumb'])) {
            $xtpl->parse('main.loop.image');
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}