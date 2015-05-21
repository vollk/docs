CREATE TABLE IF NOT EXISTS `bills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `number` INT(11) NOT NULL,
  `partner` int(11) NOT NULL,
  `status` BOOL NOT NULL, -- 0 - не оплачен, 1 - оплачен,
  `sum` DECIMAL(10,2) NOT NULL,
  `desc` TEXT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `bills`
ADD CONSTRAINT `fk_bills_partner` FOREIGN KEY (`partner`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;