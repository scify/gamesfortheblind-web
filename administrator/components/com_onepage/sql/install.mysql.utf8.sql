
CREATE TABLE IF NOT EXISTS `#__onepage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `code` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='';

CREATE TABLE IF NOT EXISTS `#__onepage_design` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pageid` int(11) NOT NULL,
  `json` text NOT NULL,
  `type` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='';

CREATE TABLE IF NOT EXISTS `#__onepage_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `onepage_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_type` int(11) NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `link` text NOT NULL,
  `value` mediumtext NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='';

CREATE TABLE IF NOT EXISTS `#__onepage_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `defaultcode` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='';


INSERT INTO `#__onepage_type` (`id`, `type`, `name`, `class`, `defaultcode`) VALUES
(1, 'columns_desi', 'Columns', 'st-column', '{"type":"columns_desi","content":[{"type":"column_item_desi","content":[],"attr":{"col":"4"}},{"type":"column_item_desi","content":[],"attr":{"col":"4"}},{"type":"column_item_desi","content":[],"attr":{"col":"4"}}],"attr":{}}'),
(2, 'divider_desi', 'Divider', 'st-divider', '{"type":"divider_desi","content":" ","attr":{"style":"","margin":"0"}}'),
(3, 'moduleid_desi', 'Module', 'st-module', '{"type":"moduleid_desi","content":"","attr":{"id":"0"}}'),
(4, 'pageitem_desi', 'Page Item', 'st-pageitem', '{"type":"pageitem_desi","content":"","attr":{"id":"0"}}');
