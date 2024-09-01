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
id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
fullname varchar(190) NOT NULL,
alias varchar(190) NOT NULL,
dob int(11) unsigned NOT NULL DEFAULT '0',
address varchar(190) NOT NULL,
height DECIMAL(5,2) NOT NULL DEFAULT '0.00',
chest DECIMAL(5,2) NOT NULL DEFAULT '0.00',
waist DECIMAL(5,2) NOT NULL DEFAULT '0.00',
hips DECIMAL(5,2) NOT NULL DEFAULT '0.00',
email varchar(190) NOT NULL DEFAULT '',
image varchar(255) NOT NULL DEFAULT '',
keywords text NOT NULL,
vote int(11) NOT NULL DEFAULT '0',
time_add int(11) unsigned NOT NULL DEFAULT '0',
time_update int(11) unsigned NOT NULL DEFAULT '0',
weight smallint(4) unsigned NOT NULL DEFAULT '0',
status tinyint(1) NOT NULL DEFAULT '1',
rank smallint(5) NOT NULL DEFAULT '0',
PRIMARY KEY (id),
UNIQUE KEY alias (alias)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_votes (
id int(11) unsigned NOT NULL AUTO_INCREMENT,
contestant_id smallint(5) unsigned NOT NULL,
userid int(11) unsigned DEFAULT NULL,
voter_name varchar(190) NOT NULL,
email varchar(190) NOT NULL DEFAULT '',
vote_time int(11) unsigned NOT NULL DEFAULT '0',
is_verified tinyint(1) NOT NULL DEFAULT '0',
PRIMARY KEY (id),
KEY contestant_id (contestant_id),
KEY userid (userid),
UNIQUE KEY unique_vote (contestant_id, email)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_email_verifications (
id int(11) unsigned NOT NULL AUTO_INCREMENT,
email varchar(190) NOT NULL,
verification_code varchar(10) NOT NULL,
contestant_id smallint(5) unsigned NOT NULL,
voter_name varchar(190) NOT NULL,
created_at int(11) unsigned NOT NULL,
expires_at int(11) unsigned NOT NULL,
PRIMARY KEY (id),
UNIQUE KEY email_contestant (email, contestant_id),
KEY expires_at (expires_at)
) ENGINE=MyISAM";

$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'per_page', '12')";
