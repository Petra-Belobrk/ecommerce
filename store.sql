-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Oct 01, 2019 at 06:06 AM
-- Server version: 10.1.41-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_title`) VALUES
(1, 'Example category 1'),
(2, 'Example category 2'),
(3, 'Test1'),
(5, 'Design'),
(6, 'Procedural PHP'),
(7, 'Test1');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_amount` float NOT NULL,
  `order_transaction` varchar(255) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `order_currency` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_amount`, `order_transaction`, `order_status`, `order_currency`) VALUES
(1, 345, '34535432', 'Completed', 'USA'),
(11, 345, '34535432', 'Completed', 'USA'),
(12, 345, '34535432', 'Completed', 'USA'),
(13, 345, '34535432', 'Completed', 'USA'),
(14, 345, '34535432', 'Completed', 'USA'),
(15, 345, '34535432', 'Completed', 'USA'),
(16, 345, '34535432', 'Completed', 'USA'),
(17, 345, '34535432', 'Completed', 'USA'),
(18, 345, '34535432', 'Completed', 'USA');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `product_category_id` int(11) NOT NULL,
  `product_price` float NOT NULL,
  `product_description` text NOT NULL,
  `short_desc` text NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_title`, `product_category_id`, `product_price`, `product_description`, `short_desc`, `product_image`, `product_quantity`) VALUES
(3, 'Project 3', 1, 25, 'fsdca', 'd', 'images-35.jpg', 2),
(4, 'Novo', 2, 45.5, 'Nulla facilisi. Duis sit amet lacinia lectus, et tincidunt enim. In finibus eu ipsum non lacinia. Aliquam egestas nunc a mauris sodales ultricies. Fusce viverra condimentum pellentesque. Quisque consectetur viverra orci, in finibus libero suscipit vel. Integer sapien mauris, maximus id lorem quis, dictum commodo mauris. Nulla velit magna, ullamcorper eget odio eget, volutpat tempus lectus. ', 'Nulla facilisi.', '_large_image_4.jpg', 30),
(5, 'jos nesto', 2, 85.85, 'afdx', 'fybysds<y', 'images-9.jpg', 60),
(6, 'zerro', 2, 25, 'fds', 'dtsfds wedsd', 'images-37.jpg', 0),
(7, 'Some title', 2, 12, 'Spicy jalapeno bacon ipsum dolor amet bresaola nulla labore esse meatloaf, burgdoggen frankfurter drumstick pork chop turducken prosciutto laborum t-bone in. Cupim biltong nostrud tri-tip, aliqua ribeye alcatra. Sirloin ham hock pork porchetta, minim culpa sunt ipsum short loin pork belly incididunt spare', 'Spicy jalapeno bacon ipsum dolor', 'images-11.jpg', 10),
(8, 'Something', 2, 8.56, 'ribs filet mignon ex lorem. Flank beef ribs nulla cow venison minim sirloin buffalo esse short ribs filet mignon strip steak consectetur duis. Cow consectetur laborum ea turducken ham tenderloin incididunt qui porchetta. Shankle pork chop dolore short ribs tri-tip enim kevin.', 'ribs filet mignon ex lorem', 'images-18.jpg', 2),
(9, 'Bacon', 5, 5.55, 'Andouille tail non, prosciutto et capicola kevin ullamco pork dolore laboris deserunt. Enim voluptate short loin mollit magna salami prosciutto porchetta deserunt cillum ground round ad filet mignon tempor. Irure picanha dolor beef. Proident beef swine enim. Fugiat nulla ut proident, tempor sint bacon duis. Shoulder cupim elit shank velit shankle.', 'Andouille tail non, prosciutto et capicola', 'images-12.jpg', 6),
(10, 'Meatsweats', 3, 2.18, 'Voluptate cupidatat in, shoulder nisi esse spare ribs ut capicola laboris. Dolor filet mignon velit, enim pastrami consectetur tail tongue pork loin. Velit shank cillum, nulla sirloin ham ullamco capicola pariatur salami aliquip. Elit ribeye jowl veniam beef cow turducken ex commodo pariatur sunt ut consequat consectetur kevin. Swine do meatball, sirloin shoulder tongue esse id biltong rump enim kielbasa burgdoggen hamburger.', 'Voluptate cupidatat in, shoulder nisi esse', 'images-39.jpg', 6),
(11, 'Jsjmmand', 2, 5.86, 'Sunt aute spare ribs proident ut. In prosciutto aute leberkas, incididunt landjaeger t-bone deserunt in pig consequat tempor flank sirloin et. Beef swine burgdoggen t-bone. Sunt pork belly filet mignon cupidatat commodo aute cillum short loin.', 'Sunt aute spare ribs proident ut. ', 'images-1.jpg', 78);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_price` float NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `product_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `product_id`, `order_id`, `product_price`, `product_title`, `product_quantity`) VALUES
(17, 1, 14, 98.99, '', 2),
(18, 2, 14, 50.02, '', 1),
(19, 1, 15, 98.99, 'Product 1', 2),
(20, 2, 15, 50.02, 'Product 2', 1),
(21, 1, 16, 98.99, 'Product 1', 2),
(22, 2, 17, 50.02, 'Product 2', 1),
(23, 1, 18, 98.99, 'Product 1', 3);

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `slide_id` int(11) NOT NULL,
  `slide_title` varchar(255) NOT NULL,
  `slide_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`slide_id`, `slide_title`, `slide_image`) VALUES
(1, 'Test', 'images-9.jpg'),
(5, 'llolo', 'images-14.jpg'),
(6, 'gfds', 'images-21.jpg'),
(8, '2', 'images-34.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `user_email`, `user_password`, `user_image`) VALUES
(1, 'Arafel', 'arafel@net.hr', '123', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`slide_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `slide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
