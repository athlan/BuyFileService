CREATE TABLE `downloads` (
  `order_id` varchar(36) NOT NULL,
  `download_date` datetime NOT NULL,
  `ip` text NOT NULL,
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `orders` (
  `id` varchar(36) NOT NULL,
  `creation_date` datetime NOT NULL,
  `creation_ip` text NOT NULL,
  `paid_date` datetime DEFAULT NULL,
  `email` text NOT NULL,
  `invoice_requested` tinyint(1) NOT NULL,
  `invoice_title` text,
  `invoice_address` text,
  `invoice_nip` text,
  `product_id` text NOT NULL,
  `product_price` float(10,2) NOT NULL,
  `product_currency` text NOT NULL,
  `product_quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `payment_gateway_events` (
  `id` varchar(36) NOT NULL,
  `gateway_id` text NOT NULL,
  `event_name` text NOT NULL,
  `occurrence_date` datetime NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
