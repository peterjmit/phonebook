CREATE TABLE `people` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `numbers` (
  `id` int(11) NOT NULL,
  `number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `people_numbers` (
  `people_id` int(11) NOT NULL,
  `number_id` int(11) NOT NULL,
  `sort` tinyint(1) NOT NULL,
  PRIMARY KEY (`people_id`,`number_id`),
  KEY `IDX_PEOPLE_ID_NUMBERS` (`people_id`),
  KEY `IDX_NUMBER_ID_PEOPLE` (`number_id`),
  CONSTRAINT `FK_PEOPLE_ID_NUMBERS` FOREIGN KEY (`people_id`) REFERENCES `people` (`id`),
  CONSTRAINT `FK_NUMBER_ID_PEOPLE` FOREIGN KEY (`number_id`) REFERENCES `numbers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
