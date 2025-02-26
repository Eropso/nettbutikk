CREATE TABLE IF NOT EXISTS `products` (
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

INSERT INTO `products` (`id`, `title`, `description`, `price`, `rrp`, `quantity`, `img`, `date_added`) VALUES
(1, 'Smart Watch', '<p>Unique watch made with stainless steel, ideal for those that prefer interative watches.</p>\r\n<h3>Features</h3>\r\n<ul>\r\n<li>Powered by Android with built-in apps.</li>\r\n<li>Adjustable to fit most.</li>\r\n<li>Long battery life, continuous wear for up to 2 days.</li>\r\n<li>Lightweight design, comfort on your wrist.</li>\r\n</ul>', '29.99', '0.00', 10, 'watch.jpg', '2025-01-01 00:00:00'),
(2, 'Wallet', '', '14.99', '19.99', 34, 'wallet.jpg', '2025-01-01 00:00:00'),
(3, 'Headphones', '', '19.99', '0.00', 23, 'headphones.jpg', '2025-01-01 00:00:00'),
(4, 'Digital Camera', '', '69.99', '0.00', 7, 'camera.jpg', '2025-01-01 00:00:00');