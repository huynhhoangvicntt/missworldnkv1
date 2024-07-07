<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_players;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_voters;";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_players (
id smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
fullname varchar(190) NOT NULL COMMENT 'Họ và tên thí sinh',
alias varchar(190) NOT NULL COMMENT 'Liên kết tĩnh không trùng',
dob int(11) NOT NULL DEFAULT '0' COMMENT 'Ngày sinh',
height smallint(5) NOT NULL DEFAULT '0' COMMENT 'Chiều cao',
chest smallint(5) NOT NULL COMMENT 'Số đo vòng ngực',
waist smallint(5) NOT NULL COMMENT 'Số đo vòng eo',
hips smallint(5) NOT NULL COMMENT 'Số đo vòng hông',
email varchar(190) NOT NULL COMMENT 'Địa chỉ email',
image varchar(255) NOT NULL COMMENT 'Ảnh hồ sơ',
vote int(11) NOT NULL DEFAULT '0' COMMENT 'Số lượt bình chọn',
add_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
edit_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian sửa',
weight smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Thứ tự',
PRIMARY KEY (id),
UNIQUE KEY alias (alias)
) ENGINE=InnoDB";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_voters (
id smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
userid smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Id người dùng có tài khoản',
fullname varchar(190) NOT NULL COMMENT 'Họ tên người bình chọn',
email varchar(190) NOT NULL COMMENT 'Email người bình chọn',
vote_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian bình chọn',
PRIMARY KEY (id),
UNIQUE KEY email (email),
KEY userid (userid)
) ENGINE=InnoDB";

$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'per_page', '12')";