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

CREATE TABLE `orders` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `order_date` datetime NOT NULL DEFAULT current_timestamp(),
 `total_amount` decimal(10,2) NOT NULL,
 `status` enum('processing','shipped','completed') NOT NULL DEFAULT 'processing',
 PRIMARY KEY (`id`)
)


CREATE TABLE `order_items` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `order_id` int(11) NOT NULL,
 `product_id` int(11) NOT NULL,
 `quantity` int(11) NOT NULL,
 `price` decimal(10,2) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `order_id` (`order_id`),
 CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) 



INSERT INTO `products` (`id`, `title`, `description`, `price`, `rrp`, `quantity`, `img`, `date_added`) VALUES
(1, 'Hoodie', '<p>High quality Cotton. Thick material GSM 500. Made in Italy.</p>', '29.99', '10.00', 10, 'images/hoodie.png', '2025-01-01 00:00:00'),
(2, 'Sweatpants', '<p>Cotton poly blend. Made in Italy. Minimum Wage Workers.</p>', '20.99', '8.99', 34, 'images/pants.png', '2025-01-01 00:00:00')
