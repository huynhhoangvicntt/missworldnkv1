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
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_votes;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_email_verifications;";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
id smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
fullname varchar(190) NOT NULL COMMENT 'Tên thí sinh',
alias varchar(190) NOT NULL COMMENT 'Liên kết tĩnh không trùng',
dob int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Ngày sinh',
address varchar(190) NOT NULL COMMENT 'Địa chỉ',
height DECIMAL(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Chiều cao',
chest DECIMAL(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Số đo vòng ngực',
waist DECIMAL(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Số đo vòng eo',
hips DECIMAL(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Số đo vòng mông',
email varchar(190) NOT NULL DEFAULT '' COMMENT 'Địa chỉ email',
image varchar(255) NOT NULL DEFAULT '' COMMENT 'Ảnh đại diện',
is_thumb tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 là không có ảnh, 1 ảnh asset, 2 ảnh upload 3 ảnh remote',
keywords text NOT NULL COMMENT 'Từ khóa, phân cách bởi dấu phảy',
vote int(11) NOT NULL DEFAULT '0' COMMENT 'Lượt bình chọn',
time_add int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian thêm',
time_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian sửa',
weight smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Thứ tự',
comment_hits int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Số bình luận',
status tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Trạng thái 1 bật 0 tắt',
rank smallint(5) NOT NULL DEFAULT '0' COMMENT 'Xếp hạng',
PRIMARY KEY (id),
UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_votes (
id int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
contestant_id smallint(5) unsigned NOT NULL COMMENT 'ID thí sinh',
userid int(11) unsigned DEFAULT NULL COMMENT 'ID người dùng',
voter_name varchar(190) NOT NULL COMMENT 'Tên người bình chọn',
email varchar(190) NOT NULL DEFAULT '' COMMENT 'Địa chỉ email',
vote_time int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian bình chọn',
is_verified tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Xác thực 1 rồi 0 chưa',
PRIMARY KEY (id),
KEY contestant_id (contestant_id),
KEY userid (userid),
UNIQUE KEY unique_vote (contestant_id, email)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_email_verifications (
id int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
email varchar(190) NOT NULL COMMENT 'Địa chỉ email',
verification_code varchar(10) NOT NULL COMMENT 'Mã xác minh',
contestant_id smallint(5) unsigned NOT NULL COMMENT 'ID thí sinh',
voter_name varchar(190) NOT NULL COMMENT 'Tên người bình chọn',
created_at int(11) unsigned NOT NULL COMMENT 'Tạo lúc',
expires_at int(11) unsigned NOT NULL COMMENT 'Hết hạn lúc',
PRIMARY KEY (id),
UNIQUE KEY email_contestant (email, contestant_id),
KEY expires_at (expires_at)
) ENGINE=MyISAM";

$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'per_page', '12')";

// Tạo CSDL nếu dùng chức năng bình luận. Nếu không bình luận thì bỏ đoạn này
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_postcomm', '1')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_comm', '-1')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'view_comm', '6')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'setcomm', '4')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'activecomm', '1')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'emailcomm', '0')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'adminscomm', '')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'sortcomm', '0')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'captcha_area_comm', '1')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'perpagecomm', '5')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'timeoutcomm', '360')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowattachcomm', '0')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'alloweditorcomm', '0')";
