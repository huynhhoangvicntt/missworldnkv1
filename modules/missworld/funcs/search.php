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

/**
 * @param string $string
 * @param string $mode
 * @return string
 */
function nv_substr_clean($string, $mode = 'lr')
{
    $strlen = nv_strlen($string);
    $pos_bg = nv_strpos($string, ' ') + 1;
    $pos_en = nv_strrpos($string, ' ');
    if ($mode == 'l') {
        $string = '...' . nv_substr($string, $pos_bg, $strlen - $pos_bg);
    } elseif ($mode == 'r') {
        $string = nv_substr($string, 0, $strlen - $pos_en) . '...';
    } elseif ($mode == 'lr') {
        $string = '...' . nv_substr($string, $pos_bg, $pos_en - $pos_bg) . '...';
    }

    return $string;
}

/**
 * @param string $str
 * @param string $keyword
 * @return string
 */
function BoldKeywordInStr($str, $keyword)
{
    $str = nv_br2nl($str);
    $str = nv_nl2br($str, ' ');
    $str = nv_unhtmlspecialchars(strip_tags(trim($str)));

    if (empty($keyword)) {
        return nv_clean60($str, 300);
    }

    $array_keyword = [
        $keyword,
        nv_EncString($keyword)
    ];
    $array_keyword = array_unique($array_keyword);

    $pos = false;
    $pattern = [];
    foreach ($array_keyword as $k) {
        $_k = function_exists('searchPatternByLang') ? searchPatternByLang(nv_preg_quote($k)) : nv_preg_quote($k);
        $pattern[] = $_k;
        if (!$pos and preg_match('/^(.*?)' . $_k . '/is', $str, $matches)) {
            $strlen = nv_strlen($str);
            $kstrlen = nv_strlen($k);
            $residual = $strlen - 300;
            if ($residual > 0) {
                $lstrlen = nv_strlen($matches[1]);
                $rstrlen = $strlen - $lstrlen - $kstrlen;

                $medium = round((300 - $kstrlen) / 2);
                if ($lstrlen <= $medium) {
                    $str = nv_clean60($str, 300);
                } elseif ($rstrlen <= $medium) {
                    $str = nv_substr($str, $residual, 300);
                    $str = nv_substr_clean($str, 'l');
                } else {
                    $str = nv_substr($str, $lstrlen - $medium, $strlen - $lstrlen + $medium);
                    $str = nv_substr($str, 0, 300);
                    $str = nv_substr_clean($str, 'lr');
                }
            }

            $pos = true;
        }
    }

    if (!$pos) {
        return nv_clean60($str, 300);
    }

    $pattern = '/(' . implode('|', $pattern) . ')/is';

    return preg_replace($pattern, '<span class="keyword">$1</span>', $str);
}

// Các biến cần thiết: Tiêu đề, từ khóa, mô tả
$page_title = $module_info['funcs'][$op]['func_site_title'];
$key_words = $module_info['keywords'];
$description = $module_info['description'];

// Các biến cần thiết: Link của trang
$page_url = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$canonicalUrl = getCanonicalUrl($page_url);

// Tạo breadcrumbs
$array_mod_title[] = [
    'catid' => 0,
    'title' => $module_info['funcs'][$op]['func_custom_name'],
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
];

// Phần tìm kiếm
$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', '');

$is_search = !empty($array_search['q']);

$page = $nv_Request->get_absint('page', 'get', 1);

$array = [];
$generate_page = '';
$num_items = 0;

if ($is_search) {
    // Không index các kết quả tìm kiếm.
    $nv_BotManager->setPrivate();

    // Truy vấn CSDL để lấy thí sinh
    $db->sqlreset()->from(NV_PREFIXLANG . '_' . $module_data . '_rows');

    // Điều kiện lấy thí sinh
    $where = [];
    $where[] = 'status=1';

    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $dblikekey = $db->dblikeescape($array_search['q']);
    $where[] = "(fullname LIKE '%" . $dblikekey . "%' OR keywords LIKE '%" . $dblikekey . "%')";

    $db->where(implode(' AND ', $where));

    $db->select('COUNT(id)');

    // Tổng số thí sinh
    $num_items = $db->query($db->sql())->fetchColumn();

    // Khống chế đánh số trang tùy ý
    $urlappend = '&amp;page=';
    betweenURLs($page, ceil($num_items / $per_page), $base_url, $urlappend, $prevPage, $nextPage);

    // Lấy danh sách thí sinh
    $db->select('id, fullname, alias, keywords, image, is_thumb, dob, height, chest, waist, hips, vote, rank');
    $db->order('id DESC')->limit($per_page)->offset(($page - 1) * $per_page);

    $result = $db->query($db->sql());

    while ($row = $result->fetch()) {
        // Xác định ảnh đại diện
        if ($row['is_thumb'] == 1) {
            $row['thumb'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['image'];
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
        } elseif ($row['is_thumb'] == 2) {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
            $row['thumb'] = $row['image'];
        } elseif ($row['is_thumb'] == 3) {
            $row['thumb'] = $row['image'];
        } else {
            $row['thumb'] = $row['image'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/default.jpg';
        }
        
        // Xác định link thí sinh
        if ($global_config['rewrite_enable']) {
            $row['link'] = NV_BASE_SITEURL . $module_name . '/' . $row['alias'] . $global_config['rewrite_exturl'];
        } else {
            $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];
        }

        $row['title_text'] = $row['fullname'];
        $row['title'] = BoldKeywordInStr($row['fullname'], $array_search['q']);
        $row['description'] = BoldKeywordInStr($row['keywords'], $array_search['q']);

        $array[$row['id']] = $row;
    }

    // Phân trang
    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
}

// Gọi hàm xử lý giao diện
$contents = nv_theme_missworld_search($array, $generate_page, $is_search, $num_items, $array_search);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';