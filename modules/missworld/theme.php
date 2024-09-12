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

function nv_theme_missworld_main($array_data, $generate_page)
{
    global $module_name, $lang_module, $lang_global, $module_info, $module_file;
    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);

    if(!empty($array_data)){
        foreach($array_data as $value){
            $value['dob'] = empty($value['dob']) ? '' : nv_date('d/m/Y', $value['dob']);
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
    global $module_info, $lang_module, $module_file, $module_name;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATA', $array_data);
    $xtpl->assign('BACK_URL', NV_BASE_SITEURL . $module_name);

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