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

function nv_missworld_list($array_data)
{
    global $module_name, $lang_module, $lang_global, $module_info, $meta_property, $client_info, $page_config, $global_config, $module_upload;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $num = sizeof($array_data);
    if(!empty($array_data)){
        foreach($array_data as $value){
            $value['dob'] = nv_date('d/m/Y', $value['dob']); 
            $value['image'] = NV_BASE_SITEURL .NV_UPLOADS_DIR . '/' . $module_upload . '/' . $value['image'];
            $xtpl->assign('DATA', $value);
            $xtpl->parse('main.loop');
        }
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}