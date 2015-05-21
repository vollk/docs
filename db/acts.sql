CREATE TABLE IF NOT EXISTS `acts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `number` INT(11) NOT NULL,
  `partner` int(11) NOT NULL,
  `sum` DECIMAL(10,2) NOT NULL,
  `desc` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `acts`
ADD CONSTRAINT `fk_acts_partner` FOREIGN KEY (`partner`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;