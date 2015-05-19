CREATE TABLE IF NOT EXISTS `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `inn` varchar(256),
  `kpp` varchar(256),
  `address` varchar(1024),
  `acc`varchar(256),
  `kor_acc` varchar(256),
  `bank` varchar(256),
  `bik` varchar(256),
  `phone` varchar(256),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;