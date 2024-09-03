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

function nv_theme_missworld_main($array_data, $generate_page, $keyword)
{
    global $module_name, $lang_module, $lang_global, $module_info, $page_config, $module_upload, $module_file, $op;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('KEYWORD', $keyword);

    if(!empty($array_data)){
        $images_default = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/default.jpg';
        foreach($array_data as $value){
            $value['dob'] = empty($value['dob']) ? '' : nv_date('d/m/Y', $value['dob']);
            $value['url_view'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $value['id'];
            if (!empty($value['image'])) {
                $value['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $value['image'];
            } else {
                $value['image'] = $images_default;
            }
            $xtpl->assign('DATA', $value);
            $xtpl->parse('main.loop');
        }
        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.generate_page');
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_missworld_detail($array_data)
{
    global $module_info, $lang_module, $module_file, $module_upload;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

    // Xử lý hình ảnh mặc định
    $images_default = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/default.jpg';
    if (empty($array_data['image'])) {
        $array_data['image'] = $images_default;
    }

    $xtpl->assign('DATA', $array_data);

    // Xử lý hiển thị lịch sử bình chọn
    if (!empty($array_data['voting_history'])) {
        foreach ($array_data['voting_history'] as $vote) {
            $xtpl->assign('VOTE', $vote);
            $xtpl->parse('main.voting_history.loop');
        }
        $xtpl->parse('main.voting_history');
    } else {
        $xtpl->parse('main.no_votes');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}