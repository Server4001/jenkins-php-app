CREATE TABLE IF NOT EXISTS dogs (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `breed` VARCHAR(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `IDX_NAME` (`name`),
  INDEX `IDX_BREED` (`breed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
