CREATE TABLE IF NOT EXISTS `hives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `harvest_cycle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cycle_number` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `hive_id` int(11) NOT NULL,
  `start_of_cycle` date NOT NULL,
  `end_of_cycle` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_harvest_cycle_hive` (`hive_id`),
  CONSTRAINT `fk_harvest_cycle_hive` FOREIGN KEY (`hive_id`) REFERENCES `hives` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `user_harvest_cycle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hive_id` int(11) NOT NULL,
  `cycle_number` int(11) NOT NULL,
  `start_of_cycle` date NOT NULL,
  `end_of_cycle` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_harvest_cycle_hive` (`hive_id`),
  CONSTRAINT `fk_user_harvest_cycle_hive` FOREIGN KEY (`hive_id`) REFERENCES `hives` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'info',
  `noti_seen` enum('seen','unseen') DEFAULT 'unseen',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
