
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- customer_category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `customer_category`;

CREATE TABLE `customer_category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(45) NOT NULL,
    `is_default` TINYINT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `customer_category_U_1` (`code`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- customer_customer_category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `customer_customer_category`;

CREATE TABLE `customer_customer_category`
(
    `customer_id` INTEGER NOT NULL,
    `customer_category_id` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`customer_id`),
    INDEX `idx_customer_customer_category_customer_category_id` (`customer_category_id`),
    CONSTRAINT `customer_customer_category_FK_1`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- customer_category_i18n
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `customer_category_i18n`;

CREATE TABLE `customer_category_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT 'en_US' NOT NULL,
    `title` VARCHAR(255),
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `customer_category_i18n_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `customer_category` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
