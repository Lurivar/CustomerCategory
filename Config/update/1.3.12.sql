CREATE TABLE IF NOT EXISTS `customer_category_order`
(
    `order_id` INTEGER NOT NULL,
    `customer_category_id` INTEGER NOT NULL,
    PRIMARY KEY (`order_id`),
    INDEX `FI_customer_category_order_customer_category_id` (`customer_category_id`),
    CONSTRAINT `fk_customer_category_order_customer_category_order_id`
    FOREIGN KEY (`order_id`)
    REFERENCES `order` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_customer_category_order_customer_category_id`
    FOREIGN KEY (`customer_category_id`)
    REFERENCES `customer_category` (`id`)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

