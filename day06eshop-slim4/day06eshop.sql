-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3333
-- Generation Time: Apr 28, 2020 at 02:49 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `day06eshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `id` int(11) NOT NULL,
  `sessionId` varchar(100) NOT NULL,
  `addedTS` timestamp NOT NULL DEFAULT current_timestamp(),
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cartitems`
--

INSERT INTO `cartitems` (`id`, `sessionId`, `addedTS`, `productId`, `quantity`) VALUES
(1, 'equcg7q73rq8r3r644p037ajdr', '2020-04-28 05:30:57', 15, 4);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'simple'),
(2, 'letters'),
(3, 'steel'),
(4, 'extra');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `addedTS` timestamp NOT NULL DEFAULT current_timestamp(),
  `unitPrice` decimal(10,2) NOT NULL,
  `pictureFilePath` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `categoryId`, `name`, `description`, `addedTS`, `unitPrice`, `pictureFilePath`) VALUES
(1, 4, 'Tokay gecko', 'Nulla ut erat id mauris vulputate elementum. Nullam varius. Nulla facilisi. Cras non velit nec nisi vulputate nonummy.', '2020-04-01 23:57:58', '20.66', '/products_image/extra/alex-loup-UxMUMFgUxos-unsplash.png'),
(2, 4, 'Elephant, african', 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Mauris viverra diam vitae quam. Suspendisse potenti. Nullam porttitor lacus at turpis. Donec posuere metus vitae ipsum. Aliquam non mauris.', '2020-04-10 23:58:05', '10.95', '/products_image/extra/anna-auza-9jOG5ehOUKk-unsplash.png'),
(3, 4, 'Bulbul, black-eyed', 'Nam ultrices, libero non mattis pulvinar, nulla pede ullamcorper augue, a suscipit nulla elit ac nulla. Sed vel enim sit amet nunc viverra dapibus. Nulla suscipit ligula in lacus.', '2020-04-20 23:58:11', '15.96', '/products_image/extra/igor-starkov-_Bn9b3rr6Gk-unsplash.png'),
(4, 4, 'Striped hyena', 'Aenean auctor gravida sem. Praesent id massa id nisl venenatis lacinia. Aenean sit amet justo.', '2020-04-06 23:58:13', '12.31', '/products_image/extra/nacho-carretero-molero--rOBMkOXfzY-unsplash.png'),
(5, 4, 'Crane, wattled', 'Duis ac nibh. Fusce lacus purus, aliquet at, feugiat non, pretium quis, lectus. Suspendisse potenti.', '2020-04-01 23:58:15', '23.86', '/products_image/extra/tereza-ruba-TXEzCPcsJ0Y-unsplash.png'),
(6, 2, 'Pine squirrel', 'Nullam porttitor lacus at turpis. Donec posuere metus vitae ipsum.', '2020-04-08 23:58:17', '12.71', '/products_image/letters/andrea-davis-M6ZjGPJz-lk-unsplash.png'),
(7, 2, 'Pine squirrel', 'Cras non velit nec nisi vulputate nonummy. Maecenas tincidunt lacus at velit. Vivamus vel nulla eget eros elementum pellentesque. Quisque porta volutpat erat.', '2020-04-08 23:58:19', '16.35', '/products_image/letters/dead-angel-ho7YapthDTA-unsplash.png'),
(8, 2, 'Fox, cape', 'Etiam pretium iaculis justo. In hac habitasse platea dictumst. Etiam faucibus cursus urna.', '2020-04-07 23:58:20', '14.64', '/products_image/letters/erik-mclean-t3y4Dzbtxrs-unsplash.png'),
(9, 2, 'Jackal, indian', 'Maecenas tincidunt lacus at velit. Vivamus vel nulla eget eros elementum pellentesque. Quisque porta volutpat erat. Quisque erat eros, viverra eget, congue eget, semper rutrum, nulla. Nunc purus.', '2020-04-26 23:58:24', '17.10', '/products_image/letters/jon-tyson-Dn9HWGyQ5sc-unsplash.png'),
(10, 2, 'Tortoise, desert', 'Quisque id justo sit amet sapien dignissim vestibulum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla dapibus dolor vel est. Donec odio justo, sollicitudin ut, suscipit a, feugiat et, eros.', '2020-04-07 23:58:26', '22.51', '/products_image/letters/joshua-hanks-ug08AOk_5XY-unsplash.png'),
(11, 2, 'Common mynah', 'Nulla mollis molestie lorem. Quisque ut erat.', '2020-04-08 23:58:27', '10.07', '/products_image/letters/karly-jones-PCGcQsbYkRk-unsplash.png'),
(12, 2, 'Sloth bear', 'Etiam vel augue. Vestibulum rutrum rutrum neque. Aenean auctor gravida sem.', '2020-04-07 23:58:29', '22.97', '/products_image/letters/olena-sergienko-gL9W8bS86ig-unsplash.png'),
(13, 2, 'Heron, green', 'Aenean fermentum. Donec ut mauris eget massa tempor convallis.', '2020-04-02 23:58:30', '18.98', '/products_image/letters/robert-bahn-Dej8aN2MLls-unsplash.png'),
(14, 2, 'White-necked raven', 'Suspendisse ornare consequat lectus. In est risus, auctor sed, tristique in, tempus sit amet, sem. Fusce consequat.', '2020-04-13 23:58:33', '14.98', '/products_image/letters/sean-benesh-fRwq7bV0zZM-unsplash.png'),
(15, 1, 'Arctic tern', 'Nullam orci pede, venenatis non, sodales sed, tincidunt eu, felis. Fusce posuere felis sed lacus. Morbi sem mauris, laoreet ut, rhoncus aliquet, pulvinar sed, nisl. Nunc rhoncus dui vel sem.', '2020-03-17 23:58:35', '18.87', '/products_image/simple/brittany-bendabout-WEwXOLAlwT4-unsplash.png'),
(16, 1, 'Penguin, little blue', 'Mauris lacinia sapien quis libero. Nullam sit amet turpis elementum ligula vehicula consequat. Morbi a ipsum. Integer a nibh. In quis justo.', '2020-04-06 23:58:39', '14.82', '/products_image/simple/claudiu-hegedus-jt4ybatU9wk-unsplash.png'),
(17, 1, 'Snow goose', 'Phasellus sit amet erat. Nulla tempus. Vivamus in felis eu sapien cursus vestibulum. Proin eu mi. Nulla ac enim.', '2020-03-15 23:58:41', '16.46', '/products_image/simple/gabi-miranda-niAkR1H24tE-unsplash.png'),
(18, 1, 'Bandicoot, southern brown', 'Vestibulum ac est lacinia nisi venenatis tristique. Fusce congue, diam id ornare imperdiet, sapien urna pretium nisl, ut volutpat sapien arcu sed augue. Aliquam erat volutpat. In congue. Etiam justo.', '2020-04-14 23:58:45', '23.97', '/products_image/simple/irina-ba-77gCbK37r80-unsplash.png'),
(19, 1, 'Knob-nosed goose', 'Aliquam sit amet diam in magna bibendum imperdiet. Nullam orci pede, venenatis non, sodales sed, tincidunt eu, felis. Fusce posuere felis sed lacus.', '2020-04-09 23:58:47', '20.32', '/products_image/simple/martin-widenka-0rI80lQco18-unsplash.png'),
(20, 1, 'Mourning collared dove', 'In hac habitasse platea dictumst. Morbi vestibulum, velit id pretium iaculis, diam erat fermentum justo, nec condimentum neque sapien placerat ante.', '2020-04-01 23:58:49', '15.77', '/products_image/simple/matt-hoffman-PZkzQBMePL4-unsplash.png'),
(21, 1, 'Bird, black-throated butcher', 'Phasellus id sapien in sapien iaculis congue. Vivamus metus arcu, adipiscing molestie, hendrerit at, vulputate vitae, nisl. Aenean lectus. Pellentesque eget nunc. Donec quis orci eget orci vehicula condimentum.', '2020-04-11 23:58:51', '21.50', '/products_image/simple/nick-de-partee-bdbsWLjAYnw-unsplash.png'),
(22, 1, 'North American red fox', 'Nulla ut erat id mauris vulputate elementum. Nullam varius. Nulla facilisi. Cras non velit nec nisi vulputate nonummy. Maecenas tincidunt lacus at velit.', '2020-04-18 23:58:53', '20.17', '/products_image/simple/olasz-andras-XHedS4EFUmg-unsplash.png'),
(23, 1, 'Ox, musk', 'Cras mi pede, malesuada in, imperdiet et, commodo vulputate, justo. In blandit ultrices enim. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Proin interdum mauris non ligula pellentesque ultrices. Phasellus id sapien in sapien iaculis congue.', '2020-04-17 23:58:55', '11.35', '/products_image/simple/oriol-pascual-ZnOpWI5gUbs-unsplash.png'),
(24, 1, 'Yellow-billed stork', 'Mauris sit amet eros. Suspendisse accumsan tortor quis turpis. Sed ante. Vivamus tortor.', '2020-04-02 23:58:57', '10.08', '/products_image/simple/ronise-daluz-OCtiq3sbTvo-unsplash.png'),
(25, 1, 'Blue shark', 'Nulla justo. Aliquam quis turpis eget elit sodales scelerisque.', '2020-04-04 23:58:59', '15.92', '/products_image/simple/selina-thomas-T-kJRC_xqFI-unsplash.jpg'),
(26, 1, 'Caribou', 'Maecenas pulvinar lobortis est. Phasellus sit amet erat. Nulla tempus. Vivamus in felis eu sapien cursus vestibulum. Proin eu mi.', '2020-04-13 23:59:05', '22.64', '/products_image/simple/steven-erixon-bt_ceI2v7aA-unsplash.png'),
(27, 1, 'Goose, knob-nosed', 'Donec dapibus. Duis at velit eu est congue elementum.', '2020-04-14 23:59:07', '15.24', '/products_image/simple/tom-crew-INY4JowWde4-unsplash.png'),
(28, 3, 'Cow, scottish highland', 'Integer tincidunt ante vel ipsum. Praesent blandit lacinia erat.', '2020-04-03 23:59:09', '16.32', '/products_image/steel/anne-nygard-dLLFxSmrxQ4-unsplash.png'),
(29, 3, 'Partridge, coqui', 'Morbi non lectus. Aliquam sit amet diam in magna bibendum imperdiet. Nullam orci pede, venenatis non, sodales sed, tincidunt eu, felis.', '2020-04-07 23:59:11', '16.36', '/products_image/steel/ethan-cull-WKRfEoN-weA-unsplash.png'),
(30, 3, 'Vulture, griffon', 'Morbi a ipsum. Integer a nibh. In quis justo. Maecenas rhoncus aliquam lacus. Morbi quis tortor id nulla ultrices aliquet.', '2020-04-04 23:59:13', '12.18', '/products_image/steel/evan-wise-ip63sgZnDsE-unsplash.png'),
(31, 3, 'Bald eagle', 'Morbi sem mauris, laoreet ut, rhoncus aliquet, pulvinar sed, nisl. Nunc rhoncus dui vel sem. Sed sagittis. Nam congue, risus semper porta volutpat, quam pede lobortis ligula, sit amet eleifend pede libero quis orci. Nullam molestie nibh in lectus.', '2020-04-01 23:59:15', '17.11', '/products_image/steel/jacek-dylag-UOnGskzaAwE-unsplash.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `isAdmin` enum('true','false') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `isAdmin`) VALUES
(1, 'johndoe', 'johndoe@example.com', 'q1w2E#', 'false'),
(4, 'janedoe', 'janedoe@example.com', 'q1w2E#', 'false'),
(5, 'hahahaha', 'hahahaha@example.com', 'q1w2E#', 'false'),
(6, 'jandoe', 'jandoe@example.com', 'q1w2E#', 'false'),
(7, 'asdfasdf', 'jadoe@example.com', 'q1w2E#', 'false');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sessionId` (`sessionId`,`productId`),
  ADD KEY `fk_products_cartItems` (`productId`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pictureFilePath` (`pictureFilePath`),
  ADD KEY `categoryId` (`categoryId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartitems`
--
ALTER TABLE `cartitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `fk_products_cartItems` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_categories_products` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
