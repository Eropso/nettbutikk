CREATE TABLE `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `email` varchar(254) NOT NULL,
 `first_name` varchar(50) NOT NULL,
 `last_name` varchar(50) NOT NULL,
 `password` char(255) NOT NULL,
 `reg_date` datetime NOT NULL DEFAULT current_timestamp(),
 `role` enum('user','admin') NOT NULL DEFAULT 'user',
 PRIMARY KEY (`id`),
 UNIQUE KEY `email` (`email`)
)

CREATE TABLE `products` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(200) NOT NULL,
 `description` mediumtext NOT NULL,
 `price` decimal(7,2) NOT NULL,
 `rrp` decimal(7,2) NOT NULL DEFAULT 0.00,
 `quantity` int(11) NOT NULL,
 `img` mediumtext NOT NULL,
 `date_added` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`)
)


INSERT INTO `products` (`id`, `title`, `description`, `price`, `rrp`, `quantity`, `img`, `date_added`) VALUES
(1, 'T-shirt', '<p>High quality Cotton. Thick material GSM 500. Made in Italy.</p>', '29.99', '10.00', 10, 'images/tshirt.png', '2025-01-01 00:00:00'),
(2, 'Sweatpants', '<p>Cotton poly blend. Made in Italy. Minimum Wage Workers.</p>', '20.99', '8.99', 34, 'images/sweatpants.webp', '2025-01-01 00:00:00')
