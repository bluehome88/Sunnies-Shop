CREATE TABLE IF NOT EXISTS `PREFIX_afterpay_cart_process` (
  `id` INT NOT NULL ,
  `timestamp` INT NOT NULL ,
  PRIMARY KEY (`id`)
  ) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `PREFIX_afterpay_order` (
  `id` INT NOT NULL ,
  `order_id` VARCHAR(60) NOT NULL,
  `token` varchar(32) NOT NULL,
  `country_code` varchar(2) NULL,
  `ps_order_id` varchar(60) NULL,
  PRIMARY KEY (`id`, `order_id`)
  ) ENGINE = InnoDB;