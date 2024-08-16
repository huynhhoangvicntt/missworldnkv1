<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['contestant_manager'];

// Lấy liên kết tĩnh
if ($nv_Request->get_title('changealias', 'post', '') === NV_CHECK_SESSION) {
    $fullname = $nv_Request->get_title('fullname', 'post', '');
    $id = $nv_Request->get_absint('id', 'post', 0);

    $alias = strtolower(change_alias($fullname));

    $stmt = $db->prepare("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id !=" . $id . " AND alias = :alias");
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn()) {
        $weight = $db->query("SELECT MAX(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows")->fetchColumn();
        $weight = intval($weight) + 1;
        $alias = $alias . '-' . $weight;
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $alias;
    include NV_ROOTDIR . '/includes/footer.php';
} 

$array = $error = [];
$is_submit_form = $is_edit = false;
$id = $nv_Request->get_absint('id', 'get', 0);
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload;

if (!empty($id)) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id = " . $id;
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
    }

    $is_edit = true;
    $page_title = $lang_module['contestant_edit'];
    $array['dob'] = nv_date('d/m/Y', $array['dob']);
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id ;
} else {
    $array = [
        'id' => 0,
        'fullname' => '',
        'alias' => '',
        'dob' => '',
        'address' => '',
        'height' => null,
        'chest' => null,
        'waist' => null,
        'hips' => null,
        'email' => '',
        'image' => '',
        'keywords' => '',
        'vote' => 0,
    ];

    $page_title = $lang_module['contestant_add'];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

if ($nv_Request->get_title('save', 'post', '') === NV_CHECK_SESSION) {
    $is_submit_form = true;
    $array['fullname'] = nv_substr($nv_Request->get_title('fullname', 'post', ''), 0, 190);
    $array['alias'] = nv_substr($nv_Request->get_title('alias', 'post', ''), 0, 190);
 
    // Ngày tháng
    $array['dob'] = $nv_Request->get_title('dob', 'post', '');
    $array['dob_timestamp'] = 0;
    if (!empty($array['dob'])) {
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array['dob'], $m)) {
            $array['dob_timestamp'] = mktime(0, 0, 0, intval($m[2]), intval($m[1]), intval($m[3]));
        } else {
            $error[] = $lang_module['dob_invalid_error'];
        }
    }

    // Lấy email
    $array['email'] = $nv_Request->get_title('email', 'post', '');
    if (!empty($array['email'])) {
        $email = nv_check_valid_email($array['email'], true);
        if (empty($email[0])) {
            $array['email'] = $email[1];
        } else {
            $error[] = $email[0];
        }
    }
    
    $array['address'] = nv_substr($nv_Request->get_title('address', 'post', ''), 0, 190);
    $array['height'] = $nv_Request->get_int('height', 'post', null);
    $array['chest'] = $nv_Request->get_int('chest', 'post', null);
    $array['waist'] = $nv_Request->get_int('waist', 'post', null);
    $array['hips'] = $nv_Request->get_int('hips', 'post', null);
    $array['email'] = nv_substr($nv_Request->get_title('email', 'post', ''), 0, 190);
    $array['image'] = nv_substr($nv_Request->get_string('image', 'post', ''), 0, 255);
    $array['keywords'] = $nv_Request->get_title('keywords', 'post', '');
    $array['vote'] = $nv_Request->get_int('vote', 'post', 0);

    // Xử lý dữ liệu
    $array['alias'] = empty($array['alias']) ? change_alias($array['fullname']) : change_alias($array['alias']);

    if (nv_is_file($array['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $array['image'] = substr($array['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
         $array['image'] = '';
    }

    // Kiểm tra trùng
    $is_exists = false;
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE alias = :alias" . ($id ? ' AND id != ' . $id : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
    $sth->execute();
    
    if ($sth->fetchColumn()) {
        $is_exists = true;
    }

    if (empty($array['fullname'])) {
        $error[] = $lang_module['fullname_empty_error'];
    } 
    if ($is_exists) {
        $error[] = $lang_module['fullname_error_exists'];
    }

    if (empty($array['dob'])) {
        $error[] = $lang_module['dob_empty_error'];
    }

    foreach (['height', 'chest', 'waist', 'hips'] as $field) {
        if (empty($array[$field])) {
            $error[$field] = $lang_module[$field . '_empty_error'];
        } elseif ($array[$field] <= 0) {
            $error[$field] = $lang_module[$field . '_invalid_error'];
        }
    }

    foreach (['height', 'chest', 'waist', 'hips'] as $field) {
        if ($array[$field] === 0) {
            $array[$field] = null;
        }
    }
    if (empty($error)) {
        if (!$id) {
            $sql = "SELECT MAX(weight) weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows";
            $weight = intval($db->query($sql)->fetchColumn()) + 1;

            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_rows (
                fullname, alias, dob, address, height, chest, waist, hips, email, image, keywords, vote, weight, time_add, time_update
            ) VALUES (
                :fullname, :alias, :dob, :address, :height, :chest, :waist, :hips, :email, :image, :keywords, :vote, " . $weight . ", " . NV_CURRENTTIME . ", 0
            )";
        } else {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET
                fullname = :fullname, alias = :alias, dob = :dob, address = :address, height = :height, chest = :chest, waist = :waist, hips = :hips, email = :email, image = :image, keywords = :keywords, vote = :vote, time_update = " . NV_CURRENTTIME . "
                WHERE id = " . $id;
        }

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':fullname', $array['fullname'], PDO::PARAM_STR);
            $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
            $sth->bindParam(':dob', $array['dob_timestamp'], PDO::PARAM_INT);
            $sth->bindParam(':address', $array['address'], PDO::PARAM_STR);
            $sth->bindParam(':height', $array['height'], PDO::PARAM_INT);
            $sth->bindParam(':chest', $array['chest'], PDO::PARAM_INT);
            $sth->bindParam(':waist', $array['waist'], PDO::PARAM_INT);
            $sth->bindParam(':hips', $array['hips'], PDO::PARAM_INT);
            $sth->bindParam(':email', $array['email'], PDO::PARAM_STR);
            $sth->bindParam(':image', $array['image'], PDO::PARAM_STR);
            $sth->bindParam(':keywords', $array['keywords'], PDO::PARAM_STR, strlen($array['keywords']));
            $sth->bindParam(':vote', $array['vote'], PDO::PARAM_INT);
            $sth->execute();

            if ($id) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_CONTESTANT', json_encode($array), $admin_info['userid']);
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_CONTESTANT', json_encode($array), $admin_info['userid']);
            }
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        } catch (PDOException $e) {
            trigger_error(print_r($e, true));
            $error[] = $lang_module['errorsave'];
        }
    }
}

if (!empty($array['image']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
   $array['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array['image'];
   $currentpath = substr(dirname($array['image']), strlen(NV_BASE_SITEURL));
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('DATA', $array);
$xtpl->assign('UPLOAD_CURRENT', $currentpath);
$xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('OP', $op);

// Hiển thị lỗi
if (!empty($error)) {
   $xtpl->assign('ERROR', implode('<br />', $error));
   $xtpl->parse('main.error');
}

// Tự động lấy alias mỗi khi thêm tiêu đề
if (empty($array['alias'])) {
   $xtpl->parse('main.getalias');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';