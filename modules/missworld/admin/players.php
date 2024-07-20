<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['player_manager'];
$array['dob'] = $check = $to_person = $to_recipient = $error = '';

// Lấy liên kết tĩnh
if ($nv_Request->get_title('changealias', 'post', '') === NV_CHECK_SESSION) {
    $fullname = $nv_Request->get_title('fullname', 'post', '');
    $id = $nv_Request->get_absint('id', 'post', 0);

    $alias = strtolower(change_alias($fullname));

    $stmt = $db->prepare("SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_players WHERE id !=" . $id . " AND alias = :alias");
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn()) {
        $weight = $db->query("SELECT MAX(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_players")->fetchColumn();
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
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_players WHERE id = " . $id;
    $result = $db->query($sql);
    $array = $result->fetch();

    if (empty($array)) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
    }

    $is_edit = true;
    $page_title = $lang_module['player_edit'];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id ;
} else {
    $array = [
        'id' => 0,
        'fullname' => '',
        'alias' => '',
        'dob' => 0,
        'address' => '',
        'height' => 0,
        'chest' => 0,
        'waist' => 0,
        'hips' => 0,
        'email' => '',
        'image' => '',
        'vote' => 0,
    ];

    $page_title = $lang_module['player_add'];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

if ($nv_Request->get_title('save', 'post', '') === NV_CHECK_SESSION) {
    $is_submit_form = true;
    $array['fullname'] = nv_substr($nv_Request->get_title('fullname', 'post', ''), 0, 190);
    $array['alias'] = nv_substr($nv_Request->get_title('alias', 'post', ''), 0, 190);
    // $array['dob'] = $nv_Request->get_title('cfg_date', 'post', '');
     // Ngày tháng
     $array['dob'] = $nv_Request->get_title('dob', 'post', '', '');

     if (!empty($array['dob'])) {
         unset($m);
         if (preg_match("/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['dob'], $m)) {
             $array['dob'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
         } else {
             die($lang_module['in_result_errday']);
         }
     } else {
         $array['dob'] = '';
     }
    $array['address'] = nv_substr($nv_Request->get_title('address', 'post', ''), 0, 190);
    $array['height'] = $nv_Request->get_int('height', 'post', 0);
    $array['chest'] = $nv_Request->get_int('chest', 'post', 0);
    $array['waist'] = $nv_Request->get_int('waist', 'post', 0);
    $array['hips'] = $nv_Request->get_int('hips', 'post', 0);
    $array['email'] = nv_substr($nv_Request->get_title('email', 'post', ''), 0, 190);
    $array['image'] = nv_substr($nv_Request->get_string('image', 'post', ''), 0, 255);
    $array['vote'] = $nv_Request->get_int('vote', 'post', 0);

    // Xử lý dữ liệu
    $array['alias'] = empty($array['alias']) ? change_alias($array['fullname']) : change_alias($array['alias']);

   
   
    if (nv_is_file($array['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $array['image'] = substr($array['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
         $array['image'] = '';
        // $array['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . 'default_1.png';
    }

    // Kiểm tra trùng
    $is_exists = false;
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_players WHERE alias = :alias" . ($id ? ' AND id != ' . $id : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
    $sth->execute();
    
    if ($sth->fetchColumn()) {
        $is_exists = true;
    }

    if (empty($array['fullname'])) {
        $error[] = $lang_module['player_error_title'];
    } elseif ($is_exists) {
        $error[] = $lang_module['player_error_exists'];
    }
    if (empty($array['dob'])) {
        $error[] = $lang_module['player_error_dob'];
    } elseif ($is_exists) {
        $error[] = $lang_module['player_error_exists'];
    }

    if (empty($error)) {
        if (!$id) {
            $sql = "SELECT MAX(weight) weight FROM " . NV_PREFIXLANG . "_" . $module_data . "_players";
            $weight = intval($db->query($sql)->fetchColumn()) + 1;

            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_players (
                fullname, alias, dob, address, height, chest, waist, hips, email, image, vote, weight, time_add, time_update
            ) VALUES (
                :fullname, :alias, :dob, :address, :height, :chest, :waist, :hips, :email, :image, :vote, " . $weight . ", " . NV_CURRENTTIME . ", 0
            )";
        } else {
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_players SET
            fullname = :fullname, alias = :alias, dob = :dob, address = :address, height = :height, chest = :chest, waist = :waist, hips = :hips, email = :email, image = :image, vote = :vote, time_update = " . NV_CURRENTTIME . "
            WHERE id = " . $id;
        }

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':fullname', $array['fullname'], PDO::PARAM_STR);
            $sth->bindParam(':alias', $array['alias'], PDO::PARAM_STR);
            $sth->bindParam(':dob', $array['dob'], PDO::PARAM_INT);
            $sth->bindParam(':address', $array['address'], PDO::PARAM_STR);
            $sth->bindParam(':height', $array['height'], PDO::PARAM_INT);
            $sth->bindParam(':chest', $array['chest'], PDO::PARAM_INT);
            $sth->bindParam(':waist', $array['waist'], PDO::PARAM_INT);
            $sth->bindParam(':hips', $array['hips'], PDO::PARAM_INT);
            $sth->bindParam(':email', $array['email'], PDO::PARAM_STR);
            $sth->bindParam(':image', $array['image'], PDO::PARAM_STR);
            $sth->bindParam(':vote', $array['vote'], PDO::PARAM_INT);
            $sth->execute();

            if ($id) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_PLAYER', json_encode($array), $admin_info['userid']);
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_PLAYER', json_encode($array), $admin_info['userid']);
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

$xtpl = new XTemplate('players.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $form_action);
$xtpl->assign('DATA', $array);
$xtpl->assign('UPLOAD_CURRENT', $currentpath);
$xtpl->assign('UPLOAD_PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('OP', $op);

if ($array['dob'] != '') {
    $array['dob'] = date("d.m.Y", $array['dob']);
}
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