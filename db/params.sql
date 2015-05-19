CREATE TABLE IF NOT EXISTS `params` (
  `name` varchar(100) NOT NULL,
  `value` varchar(256) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;