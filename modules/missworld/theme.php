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

function nv_theme_missworld_list($array_data, $page)
{
    global $module_name, $lang_module, $lang_global, $module_info, $page_config, $module_upload, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $num = sizeof($array_data);
    if(!empty($array_data)){
        foreach($array_data as $value){
            $value['dob'] = empty($value['dob']) ? '' : nv_date('d/m/Y', $value['dob']);
            $value['url_view'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $value['id'];
            if (!empty($value['image'])) {
                $value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $value['image'];
            } else {
                $value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . 'default.jpg';
            }
            $xtpl->assign('DATA', $value);
            $xtpl->parse('main.loop');
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}
function nv_theme_missworld_detail($array_data)
{
    global $module_name, $lang_module, $lang_global, $module_info, $page_config, $module_upload, $op;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $xtpl->assign('DATA', $array_data);
    
    $xtpl->parse('main');
    return $xtpl->text('main');
}