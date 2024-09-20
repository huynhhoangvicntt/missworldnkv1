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
    global $module_name, $lang_module, $lang_global, $module_info, $module_file, $global_config, $nv_Request, $op;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);

    // Tạo form tìm kiếm bằng cách sử dụng hàm nv_theme_search
    $array_search = [
        'q' => $nv_Request->get_title('q', 'get', ''),
        'from' => $nv_Request->get_title('f', 'get', ''),
        'to' => $nv_Request->get_title('t', 'get', '')
    ];
    $search_form = nv_theme_missworld_search([], '', false, 0, $array_search);
    $xtpl->assign('SEARCH_FORM', $search_form);
    $xtpl->parse('main.search_form');

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

function nv_theme_missworld_search($array, $generate_page, $is_search, $num_items, $array_search)
{
    global $lang_module, $lang_global, $module_info, $module_name, $module_file, $op_file, $global_config, $op;

    $xtpl = new XTemplate('search.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('OP', $op_file);

    if (!$global_config['rewrite_enable']) {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php');
        $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
        $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
        $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
        $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
        $xtpl->assign('OP', $op);
        $xtpl->parse('main.no_rewrite');
    } else {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=search');
    }

    // Chuyển tìm kiếm sang ngày tháng
    $array_search['from'] = empty($array_search['from']) ? '' : nv_date('d-m-Y', $array_search['from']);
    $array_search['to'] = empty($array_search['to']) ? '' : nv_date('d-m-Y', $array_search['to']);

    $xtpl->assign('SEARCH', $array_search);

    if (!$is_search) {
        $xtpl->parse('main.please');
    } elseif (empty($array)) {
        $xtpl->parse('main.empty');
    } else {
        $xtpl->assign('NUM_ITEMS', number_format($num_items, 0, ',', '.'));

        // Gọi hàm xử lý chung giao diện dạng danh sách, xuất ra HTML
        $xtpl->assign('HTML', nv_theme_item_list($array));
        $xtpl->parse('main.data');
    }

    // Phân trang nếu có
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * @param array $array
 * @return string
 */
function nv_theme_item_list($array)
{
    global $lang_module, $lang_global, $module_info, $module_name, $global_config;

    $xtpl = new XTemplate('item-list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    foreach ($array as $row) {
        $row['link'] = NV_BASE_SITEURL . $module_name . '/' . $row['alias'] . $global_config['rewrite_exturl'];
        $row['dob'] = empty($row['dob']) ? '' : nv_date('d/m/Y', $row['dob']);
        // $row['title_text'] = $row['title_text'] ?? $row['fullname'];

        $xtpl->assign('ROW', $row);

        if (!empty($row['thumb'])) {
            $xtpl->parse('main.loop.image');
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_missworld_detail($array_data)
{
    global $lang_module, $lang_global, $module_info, $module_name, $module_file, $op_file;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('OP', $op_file);

    // Xử lý số lượng bình luận
    $array_data['comment_hits'] = number_format($array_data['comment_hits'], 0, ',', '.');

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

    // Xử lý phần bình luận
    if (!empty($array_data['comment_content'])) {
        $xtpl->parse('main.comment');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
