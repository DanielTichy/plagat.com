DROP TABLE IF EXISTS `#__perchadownloadsattach`;

CREATE TABLE `#__perchadownloadsattach` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(200) NOT NULL,
  `file` varchar(255) NOT NULL, 
  `description` text,
  `createdate` date,
  `publishdate` date,
  `articleid` int(11) NULL,
  `articleid_name` varchar(200) NOT NULL, 	
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  ;

