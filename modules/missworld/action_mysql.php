<?php

/**
 * @Project Module Missworld
 * @Author HUYNH HOANG VI (hoangvicntt2k@gmail.com)
 * @Copyright (C) 2024 HUYNH HOANG VI. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate March 01, 2024, 08:00:00 AM
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_voters;";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
id smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
fullname varchar(190) NOT NULL COMMENT 'Họ và tên thí sinh',
alias varchar(190) NOT NULL COMMENT 'Liên kết tĩnh không trùng',
dob int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Ngày sinh',
address varchar(190) NOT NULL COMMENT 'Địa chỉ',
height smallint(5) NOT NULL DEFAULT '0' COMMENT 'Chiều cao',
chest smallint(5) NOT NULL DEFAULT '0' COMMENT 'Số đo vòng ngực',
waist smallint(5) NOT NULL DEFAULT '0' COMMENT 'Số đo vòng eo',
hips smallint(5) NOT NULL DEFAULT '0' COMMENT 'Số đo vòng mông',
email varchar(190) NOT NULL DEFAULT '' COMMENT 'Địa chỉ email',
image varchar(255) NOT NULL DEFAULT '' COMMENT 'Ảnh hồ sơ',
keywords text NOT NULL COMMENT 'Từ khóa, phân cách bởi dấu phảy',
vote int(11) NOT NULL DEFAULT '0' COMMENT 'Số lượt bình chọn',
time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo lúc',
time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Cập nhật lúc',
weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Sắp thứ tự',
status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái 1 bật 0 tắt',
PRIMARY KEY (id),
UNIQUE KEY alias (alias)
) ENGINE=InnoDB";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_votes (
id int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
contestant_id smallint(5) unsigned NOT NULL COMMENT 'ID của thí sinh được bình chọn',
userid int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Id người dùng có tài khoản',
fullname varchar(190) NOT NULL COMMENT 'Họ tên người bình chọn',
email varchar(190) NOT NULL DEFAULT '' COMMENT 'Email người bình chọn',
vote_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian bình chọn',
PRIMARY KEY (id),
KEY contestant_id (contestant_id),
KEY userid (userid),
UNIQUE KEY unique_vote (contestant_id, email)
) ENGINE=InnoDB";

$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'per_page', '12')";