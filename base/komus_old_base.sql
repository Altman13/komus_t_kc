-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               10.1.37-MariaDB - mariadb.org binary distribution
-- Операционная система:         Win32
-- HeidiSQL Версия:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных komus_old
CREATE DATABASE IF NOT EXISTS `komus_old` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `komus_old`;

-- Дамп структуры для таблица komus_old.calls
CREATE TABLE IF NOT EXISTS `calls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) unsigned DEFAULT NULL,
  `begin_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `recall_time` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `contacts_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cot_komus_calls_cot_komus_contacts_idx` (`contacts_id`)
) ENGINE=MyISAM AUTO_INCREMENT=612 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.calls: 0 rows
/*!40000 ALTER TABLE `calls` DISABLE KEYS */;
/*!40000 ALTER TABLE `calls` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.contacts
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creation_time` datetime DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `organization` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `fio` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `subcategory` varchar(255) DEFAULT NULL,
  `question` text,
  `allow_call` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `comment` text CHARACTER SET cp1251,
  `regions_id` int(11) unsigned NOT NULL,
  `users_user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cot_komus_contacts_regions1_idx` (`regions_id`),
  KEY `fk_contacts_users1_idx` (`users_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=607 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.contacts: 0 rows
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_auth
CREATE TABLE IF NOT EXISTS `cot_auth` (
  `auth_id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_groupid` int(11) NOT NULL DEFAULT '0',
  `auth_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `auth_option` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `auth_rights` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `auth_rights_lock` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `auth_setbyuserid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`auth_id`),
  KEY `auth_groupid` (`auth_groupid`),
  KEY `auth_code` (`auth_code`)
) ENGINE=MyISAM AUTO_INCREMENT=313 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_auth: 0 rows
/*!40000 ALTER TABLE `cot_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_auth` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_cache
CREATE TABLE IF NOT EXISTS `cot_cache` (
  `c_name` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `c_realm` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cot',
  `c_expire` int(11) NOT NULL DEFAULT '0',
  `c_auto` tinyint(4) NOT NULL DEFAULT '1',
  `c_value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`c_name`,`c_realm`),
  KEY `c_realm` (`c_realm`),
  KEY `c_name` (`c_name`),
  KEY `c_expire` (`c_expire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_cache: 0 rows
/*!40000 ALTER TABLE `cot_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_cache` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_cache_bindings
CREATE TABLE IF NOT EXISTS `cot_cache_bindings` (
  `c_event` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `c_id` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `c_realm` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cot',
  `c_type` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`c_event`,`c_id`,`c_realm`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_cache_bindings: 0 rows
/*!40000 ALTER TABLE `cot_cache_bindings` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_cache_bindings` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_config
CREATE TABLE IF NOT EXISTS `cot_config` (
  `config_owner` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'core',
  `config_cat` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_subcat` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_order` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '00',
  `config_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_type` tinyint(4) NOT NULL DEFAULT '0',
  `config_value` text COLLATE utf8_unicode_ci NOT NULL,
  `config_default` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_variants` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `config_donor` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  KEY `config_owner` (`config_owner`,`config_cat`),
  KEY `config_owner_2` (`config_owner`,`config_cat`,`config_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_config: 0 rows
/*!40000 ALTER TABLE `cot_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_config` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_contact
CREATE TABLE IF NOT EXISTS `cot_contact` (
  `contact_id` int(12) NOT NULL AUTO_INCREMENT,
  `contact_author` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `contact_authorid` int(12) DEFAULT NULL,
  `contact_date` int(12) NOT NULL,
  `contact_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `contact_subject` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `contact_text` text COLLATE utf8_unicode_ci NOT NULL,
  `contact_val` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `contact_reply` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_contact: 0 rows
/*!40000 ALTER TABLE `cot_contact` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_contact` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_core
CREATE TABLE IF NOT EXISTS `cot_core` (
  `ct_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `ct_code` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ct_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ct_version` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ct_state` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `ct_lock` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ct_plug` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ct_id`),
  KEY `ct_code` (`ct_code`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_core: 0 rows
/*!40000 ALTER TABLE `cot_core` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_core` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_extra_fields
CREATE TABLE IF NOT EXISTS `cot_extra_fields` (
  `field_location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field_html` text COLLATE utf8_unicode_ci NOT NULL,
  `field_variants` text COLLATE utf8_unicode_ci NOT NULL,
  `field_params` text COLLATE utf8_unicode_ci NOT NULL,
  `field_default` text COLLATE utf8_unicode_ci NOT NULL,
  `field_required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `field_enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `field_parse` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'HTML',
  `field_description` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `field_location` (`field_location`),
  KEY `field_name` (`field_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_extra_fields: 0 rows
/*!40000 ALTER TABLE `cot_extra_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_extra_fields` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_groups
CREATE TABLE IF NOT EXISTS `cot_groups` (
  `grp_id` int(11) NOT NULL AUTO_INCREMENT,
  `grp_alias` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grp_level` tinyint(4) NOT NULL DEFAULT '1',
  `grp_disabled` tinyint(4) NOT NULL DEFAULT '0',
  `grp_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grp_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grp_icon` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grp_ownerid` int(11) NOT NULL DEFAULT '0',
  `grp_maintenance` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`grp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_groups: 0 rows
/*!40000 ALTER TABLE `cot_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_groups` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_groups_users
CREATE TABLE IF NOT EXISTS `cot_groups_users` (
  `gru_userid` int(11) NOT NULL DEFAULT '0',
  `gru_groupid` int(11) NOT NULL DEFAULT '0',
  `gru_state` tinyint(4) NOT NULL DEFAULT '0',
  `gru_extra1` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gru_extra2` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  UNIQUE KEY `gru_groupid` (`gru_groupid`,`gru_userid`),
  KEY `gru_userid` (`gru_userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_groups_users: 0 rows
/*!40000 ALTER TABLE `cot_groups_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_groups_users` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_bases
CREATE TABLE IF NOT EXISTS `cot_komus_bases` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `import_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=FIXED;

-- Дамп данных таблицы komus_old.cot_komus_bases: 0 rows
/*!40000 ALTER TABLE `cot_komus_bases` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_bases` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_calls
CREATE TABLE IF NOT EXISTS `cot_komus_calls` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `begin_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `recall_time` datetime DEFAULT NULL,
  `phone_recall` varchar(255) DEFAULT NULL,
  `call_status` tinyint(2) NOT NULL DEFAULT '0',
  `is_last_call` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `billing_begin` datetime DEFAULT NULL,
  `billing_duration` tinyint(4) DEFAULT NULL,
  `count_calls` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `status_int` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=612 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_calls: 0 rows
/*!40000 ALTER TABLE `cot_komus_calls` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_calls` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_conditions
CREATE TABLE IF NOT EXISTS `cot_komus_conditions` (
  `node_id` int(11) unsigned NOT NULL,
  `if_visited_node` int(11) unsigned DEFAULT NULL,
  `if_field` int(11) unsigned DEFAULT NULL,
  `if_field_value` int(11) unsigned DEFAULT NULL,
  `to_node` int(11) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=FIXED;

-- Дамп данных таблицы komus_old.cot_komus_conditions: 0 rows
/*!40000 ALTER TABLE `cot_komus_conditions` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_conditions` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_contacts
CREATE TABLE IF NOT EXISTS `cot_komus_contacts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `creation_time` datetime DEFAULT NULL,
  `recall_time` datetime DEFAULT NULL,
  `comment` text CHARACTER SET cp1251,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `count_calls` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `in_work` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `base_id` int(11) unsigned NOT NULL DEFAULT '0',
  `project_id` int(11) unsigned NOT NULL DEFAULT '0',
  `allow_call` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_block` int(11) DEFAULT '1',
  `status` int(11) DEFAULT '0',
  `status_int` int(11) DEFAULT NULL,
  `data_recall` datetime DEFAULT NULL,
  `time_zone` int(11) DEFAULT '0',
  `is_finish` int(11) DEFAULT NULL,
  `is_tema` int(11) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `fio` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `question` text,
  `is_manager` int(11) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `service` int(11) DEFAULT NULL,
  `servicetxt` varchar(255) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `citytype` int(11) DEFAULT NULL,
  `is_type` int(11) DEFAULT NULL,
  `is_transfercall` int(11) DEFAULT NULL,
  `subtype` int(11) DEFAULT NULL,
  `emailtxt` varchar(3000) DEFAULT NULL,
  `emailtemplate` int(11) DEFAULT NULL,
  `phonetransfer` varchar(50) DEFAULT NULL,
  `is_email` int(11) DEFAULT NULL,
  `sendedemail` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=607 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_contacts: 0 rows
/*!40000 ALTER TABLE `cot_komus_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_contacts` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_count_calls
CREATE TABLE IF NOT EXISTS `cot_komus_count_calls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator` int(11) DEFAULT NULL,
  `nocall` int(11) DEFAULT '0',
  `recall` int(11) DEFAULT '0',
  `badcall` int(11) DEFAULT '0',
  `anketa1` int(11) DEFAULT '0',
  `anketa2` int(11) DEFAULT '0',
  `anketa3` int(11) DEFAULT NULL,
  `anketa4` int(11) DEFAULT NULL,
  `anketa5` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы komus_old.cot_komus_count_calls: 0 rows
/*!40000 ALTER TABLE `cot_komus_count_calls` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_count_calls` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_delivery
CREATE TABLE IF NOT EXISTS `cot_komus_delivery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `working_day` int(5) unsigned DEFAULT NULL,
  `day_off` int(11) unsigned DEFAULT NULL,
  `sort` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_delivery: 0 rows
/*!40000 ALTER TABLE `cot_komus_delivery` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_delivery` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_filtr
CREATE TABLE IF NOT EXISTS `cot_komus_filtr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_filtr: 0 rows
/*!40000 ALTER TABLE `cot_komus_filtr` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_filtr` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_forms
CREATE TABLE IF NOT EXISTS `cot_komus_forms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) unsigned NOT NULL,
  `node_id` int(11) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `save` enum('1') DEFAULT '1',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_forms: 0 rows
/*!40000 ALTER TABLE `cot_komus_forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_forms` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_forms_fields
CREATE TABLE IF NOT EXISTS `cot_komus_forms_fields` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `title` text,
  `name` varchar(255) DEFAULT NULL,
  `form_id` int(11) unsigned NOT NULL,
  `reference_id` int(11) unsigned NOT NULL DEFAULT '0',
  `required` enum('0','1') NOT NULL DEFAULT '0',
  `empty_string` enum('0','1') NOT NULL DEFAULT '0',
  `sort` tinyint(2) unsigned DEFAULT NULL,
  `save` enum('0','1') DEFAULT '1',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_forms_fields: 0 rows
/*!40000 ALTER TABLE `cot_komus_forms_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_forms_fields` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_forms_types
CREATE TABLE IF NOT EXISTS `cot_komus_forms_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_forms_types: 0 rows
/*!40000 ALTER TABLE `cot_komus_forms_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_forms_types` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_nodes
CREATE TABLE IF NOT EXISTS `cot_komus_nodes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `node_text` text,
  `project_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_nodes: 0 rows
/*!40000 ALTER TABLE `cot_komus_nodes` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_nodes` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_projects
CREATE TABLE IF NOT EXISTS `cot_komus_projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `first_node_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_projects: 0 rows
/*!40000 ALTER TABLE `cot_komus_projects` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_projects` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_references
CREATE TABLE IF NOT EXISTS `cot_komus_references` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_references: 0 rows
/*!40000 ALTER TABLE `cot_komus_references` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_references` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_references_items
CREATE TABLE IF NOT EXISTS `cot_komus_references_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `reference_id` int(11) unsigned DEFAULT NULL,
  `sort` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `phone` varchar(500) DEFAULT NULL,
  `is_show` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_references_items: 0 rows
/*!40000 ALTER TABLE `cot_komus_references_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_references_items` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_regions
CREATE TABLE IF NOT EXISTS `cot_komus_regions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `subcode` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы komus_old.cot_komus_regions: 0 rows
/*!40000 ALTER TABLE `cot_komus_regions` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_regions` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_statuses
CREATE TABLE IF NOT EXISTS `cot_komus_statuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sort` tinyint(4) unsigned DEFAULT NULL,
  `category` tinyint(2) unsigned NOT NULL,
  `changeable` enum('1') DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=cp1251 PACK_KEYS=0 ROW_FORMAT=DYNAMIC;

-- Дамп данных таблицы komus_old.cot_komus_statuses: 0 rows
/*!40000 ALTER TABLE `cot_komus_statuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_statuses` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_timezones
CREATE TABLE IF NOT EXISTS `cot_komus_timezones` (
  `city` varchar(255) DEFAULT NULL,
  `zone` tinyint(2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы komus_old.cot_komus_timezones: 0 rows
/*!40000 ALTER TABLE `cot_komus_timezones` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_timezones` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_komus_user_project
CREATE TABLE IF NOT EXISTS `cot_komus_user_project` (
  `user_id` int(11) unsigned NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы komus_old.cot_komus_user_project: 0 rows
/*!40000 ALTER TABLE `cot_komus_user_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_komus_user_project` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_logger
CREATE TABLE IF NOT EXISTS `cot_logger` (
  `log_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `log_date` int(11) NOT NULL DEFAULT '0',
  `log_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `log_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `log_group` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'def',
  `log_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=700 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_logger: 0 rows
/*!40000 ALTER TABLE `cot_logger` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_logger` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_online
CREATE TABLE IF NOT EXISTS `cot_online` (
  `online_id` int(11) NOT NULL AUTO_INCREMENT,
  `online_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `online_lastseen` int(11) NOT NULL DEFAULT '0',
  `online_location` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_subloc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_userid` int(11) NOT NULL DEFAULT '0',
  `online_shield` int(11) NOT NULL DEFAULT '0',
  `online_action` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `online_hammer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`online_id`),
  KEY `online_lastseen` (`online_lastseen`),
  KEY `online_userid` (`online_userid`),
  KEY `online_name` (`online_name`)
) ENGINE=MyISAM AUTO_INCREMENT=13003 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_online: 0 rows
/*!40000 ALTER TABLE `cot_online` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_online` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_pages
CREATE TABLE IF NOT EXISTS `cot_pages` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `page_state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `page_cat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_text` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `page_author` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `page_ownerid` int(11) NOT NULL DEFAULT '0',
  `page_date` int(11) NOT NULL DEFAULT '0',
  `page_begin` int(11) NOT NULL DEFAULT '0',
  `page_expire` int(11) NOT NULL DEFAULT '0',
  `page_file` tinyint(4) DEFAULT NULL,
  `page_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_size` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_count` mediumint(8) unsigned DEFAULT '0',
  `page_rating` decimal(5,2) NOT NULL DEFAULT '0.00',
  `page_filecount` mediumint(8) unsigned DEFAULT '0',
  `page_parser` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`page_id`),
  KEY `page_cat` (`page_cat`),
  KEY `page_alias` (`page_alias`),
  KEY `page_state` (`page_state`),
  KEY `page_date` (`page_date`),
  FULLTEXT KEY `page_search` (`page_text`,`page_title`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_pages: 0 rows
/*!40000 ALTER TABLE `cot_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_pages` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_plugins
CREATE TABLE IF NOT EXISTS `cot_plugins` (
  `pl_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `pl_hook` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_code` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_part` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pl_order` tinyint(3) unsigned NOT NULL DEFAULT '10',
  `pl_active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `pl_module` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pl_id`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_plugins: 0 rows
/*!40000 ALTER TABLE `cot_plugins` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_plugins` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_structure
CREATE TABLE IF NOT EXISTS `cot_structure` (
  `structure_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `structure_area` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_tpl` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `structure_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_icon` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `structure_locked` tinyint(4) NOT NULL DEFAULT '0',
  `structure_count` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`structure_id`),
  KEY `structure_code` (`structure_code`),
  KEY `structure_path` (`structure_path`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_structure: 0 rows
/*!40000 ALTER TABLE `cot_structure` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_structure` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_updates
CREATE TABLE IF NOT EXISTS `cot_updates` (
  `upd_param` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `upd_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`upd_param`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_updates: 0 rows
/*!40000 ALTER TABLE `cot_updates` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_updates` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.cot_users
CREATE TABLE IF NOT EXISTS `cot_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_banexpire` int(11) DEFAULT '0',
  `user_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_maingrp` int(11) NOT NULL DEFAULT '4',
  `user_country` char(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_text` text COLLATE utf8_unicode_ci,
  `user_timezone` decimal(2,1) NOT NULL DEFAULT '0.0',
  `user_birthdate` date NOT NULL DEFAULT '0000-00-00',
  `user_gender` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'U',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_hideemail` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `user_theme` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_scheme` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_lang` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_regdate` int(11) NOT NULL DEFAULT '0',
  `user_lastlog` int(11) NOT NULL DEFAULT '0',
  `user_lastvisit` int(11) NOT NULL DEFAULT '0',
  `user_lastip` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_logcount` int(10) unsigned NOT NULL DEFAULT '0',
  `user_sid` char(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_sidtime` int(11) NOT NULL DEFAULT '0',
  `user_lostpass` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_auth` text COLLATE utf8_unicode_ci,
  `user_token` char(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_firstname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_lastname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_status` enum('1','2','3') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 - активен, 2 - перерыв, 3 - занят',
  `readnews` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_password` (`user_password`),
  KEY `user_regdate` (`user_regdate`)
) ENGINE=MyISAM AUTO_INCREMENT=1147 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.cot_users: 0 rows
/*!40000 ALTER TABLE `cot_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `cot_users` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.groups_users
CREATE TABLE IF NOT EXISTS `groups_users` (
  `id` int(11) NOT NULL,
  `groups` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gru_groupid` (`id`),
  KEY `gru_userid` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.groups_users: 0 rows
/*!40000 ALTER TABLE `groups_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups_users` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.maillog
CREATE TABLE IF NOT EXISTS `maillog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DATETIME_` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=459 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.maillog: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `maillog` DISABLE KEYS */;
/*!40000 ALTER TABLE `maillog` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.regions
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `subcode` tinyint(2) unsigned DEFAULT NULL,
  `timezones_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cot_komus_regions_cot_komus_timezones1_idx` (`timezones_id`)
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы komus_old.regions: 0 rows
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.timezones
CREATE TABLE IF NOT EXISTS `timezones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- Дамп данных таблицы komus_old.timezones: 0 rows
/*!40000 ALTER TABLE `timezones` DISABLE KEYS */;
/*!40000 ALTER TABLE `timezones` ENABLE KEYS */;

-- Дамп структуры для таблица komus_old.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_password` varchar(32) CHARACTER SET utf8 NOT NULL,
  `user_email` varchar(64) CHARACTER SET utf8 NOT NULL,
  `user_firstname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user_lastname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user_gender` char(1) CHARACTER SET utf8 NOT NULL,
  `user_birthdate` date NOT NULL,
  `user_lastvisit` datetime NOT NULL,
  `user_ban` tinyint(1) DEFAULT NULL,
  `groups_users_id` int(11) NOT NULL,
  `timezones_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_password` (`user_password`),
  KEY `fk_users_groups_users1_idx` (`groups_users_id`),
  KEY `fk_users_timezones1_idx` (`timezones_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1147 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы komus_old.users: 0 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
