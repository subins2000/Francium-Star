CREATE TABLE IF NOT EXISTS `Fr_star` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `rate_id` varchar(40) NOT NULL,
  `user_id` varchar(40) NOT NULL,
  `rate` float NOT NULL,
  `rated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
