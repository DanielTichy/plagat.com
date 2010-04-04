DROP TABLE IF EXISTS `#__file_upload`;

CREATE TABLE IF NOT EXISTS `#__file_upload`(
    `id` INT( 5 ) NOT NULL auto_increment,
    `fname` VARCHAR( 100 ) NULL DEFAULT NULL,
    `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY  (`id`)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;