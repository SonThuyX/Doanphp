-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 26, 2025 lúc 01:05 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `sthuyx1`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `image`, `title`, `description`, `created_at`) VALUES
(1, 'banner1.jpg', '', NULL, '2025-03-04 16:27:15'),
(2, 'banner4.jpg', '', '', '2025-03-04 16:27:15'),
(3, 'banner5.jpg', '', '', '2025-03-04 16:27:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'CPU', '2025-03-03 08:12:52'),
(2, 'VGA', '2025-03-03 08:12:52'),
(3, 'SSD', '2025-03-03 08:12:52'),
(4, 'HDD', '2025-03-03 08:12:52'),
(5, 'Case', '2025-03-03 08:12:52'),
(6, 'Màn hình', '2025-03-21 11:14:32'),
(7, 'RAM', '2025-03-21 11:16:04'),
(8, 'Mainboard', '2025-03-21 11:22:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `address`, `payment_method`, `status`, `created_at`) VALUES
(1, 4, 2000000.00, '3', 'cod', 'completed', '2025-03-21 11:48:46'),
(2, 3, 500000.00, '1', 'cod', 'completed', '2025-03-21 12:14:50'),
(3, 4, 500000.00, '1', 'cod', 'completed', '2025-03-21 16:31:22'),
(4, 4, 2800000.00, '2', 'cod', 'completed', '2025-03-25 21:43:15'),
(5, 4, 28000000.00, '4', 'cod', 'completed', '2025-03-25 21:43:28'),
(6, 4, 500000.00, '4', 'cod', 'completed', '2025-03-25 21:47:25'),
(7, 4, 500000.00, '4', 'cod', 'pending', '2025-03-25 22:00:13'),
(8, 4, 500000.00, '4', 'cod', 'completed', '2025-03-25 22:06:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 10, 1, 2000000.00),
(2, 2, 6, 1, 500000.00),
(3, 3, 6, 1, 500000.00),
(4, 4, 14, 1, 2800000.00),
(5, 5, 4, 1, 28000000.00),
(6, 6, 6, 1, 500000.00),
(7, 7, 6, 1, 500000.00),
(8, 8, 6, 1, 500000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `promotion_id` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `purchases` int(11) DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `category_id`, `promotion_id`, `views`, `purchases`, `stock`) VALUES
(1, 'Intel Core i7-13700K', 9500000.00, 'i713700k.jpg', 'Bộ vi xử lý Intel Core i7 thế hệ 13 dành cho chơi game và làm việc chuyên nghiệp, tốc độ xung nhịp tối đa 5.4GHz, 16 nhân 24 luồng.', 1, NULL, 19, 2, 50),
(2, 'AMD Ryzen 9 7950X', 14500000.00, 'ryzen9.jpg', 'Bộ vi xử lý AMD Ryzen 9 thế hệ mới nhất, 16 nhân 32 luồng, xung nhịp tối đa 5.7GHz, phù hợp cho mọi tác vụ từ chơi game đến dựng hình 3D.', 1, NULL, 15, 0, 30),
(3, 'NVIDIA GeForce RTX 4080', 32000000.00, 'rtx4080.jpg', 'Card đồ họa NVIDIA RTX 4080 với 16GB GDDR6X, hỗ trợ ray tracing và DLSS 3, lý tưởng cho chơi game 4K.', 2, NULL, 24, 1, 20),
(4, 'AMD Radeon RX 7900 XTX', 28000000.00, 'RX7900XTX.jpg', 'Card đồ họa AMD Radeon RX 7900 XTX với 24GB GDDR6, hiệu suất cao cho chơi game và sáng tạo nội dung, hỗ trợ công nghệ FSR 3.', 2, NULL, 35, 1, 15),
(5, 'NVIDIA GeForce RTX 3060', 8500000.00, 'rtx3060.jpg', 'Card đồ họa tầm trung RTX 3060 với 12GB GDDR6, phù hợp cho chơi game Full HD và làm việc đồ họa cơ bản.', 2, NULL, 1, 0, 10),
(6, 'HDD Western Digital red 1TB', 500000.00, 'hdd.jpg', 'Hãng: WD\r\nLoại ổ cứng: Sata 3 (5.5 inch)\r\nRất nhanh với tốc độ đọc ghi tuần tự có thể lên đến 100Mb/s và 200Mb/s', 4, NULL, 16226, 9, 100),
(7, 'SSD CS900 120gb/240gb/480gb', 1000000.00, 'ssd.jpg', 'Hãng: PNY\r\nLoại ổ cứng: Sata 3 (2.5 inch)\r\nRất nhanh với tốc độ đọc ghi tuần tự có thể lên đến 535Mb/s và 500Mb/s', 3, NULL, 2, 0, 80),
(8, 'Vỏ thùng máy tính', 3000000.00, '3.png', 'Màu đen\r\nCó fan led làm mát\r\nChống bụi và côn trùng', 5, NULL, 2, 0, 60),
(9, 'Vỏ thùng máy tính (Màu Trắng)', 500000.00, 'case.jpg', 'Màu trắng\r\nCó fan led làm mát\r\nChống bụi và côn trùng', 5, NULL, 1, 0, 40),
(10, 'CPU Intel Core i5-11400f', 2000000.00, 'i511400f.jpg', NULL, 1, NULL, 80, 2, 50),
(11, 'NVIDIA GeForce GTX 1660S', 2700000.00, '1660s.png', NULL, 2, NULL, 10, 0, 25),
(12, 'Mainboard B560m asrock (White)', 2000000.00, 'B560M Pro4.png', NULL, 8, NULL, 2, 0, 30),
(13, 'Cặp ram 16gx2 DDR4 3200Mhz có LED RGB', 1500000.00, 'RAM-16gb-ddr4-3200mhz.jpg', NULL, 7, NULL, 3, 0, 70),
(14, 'Màn hình LG24GS60F 180Hz IPS ', 2800000.00, 'LG27GS60F_D_.jpg', NULL, 6, NULL, 4, 1, 40),
(15, 'Ổ cứng SSD Samsung 990 Pro 1TB M.2 NVMe PCIe 4.0', 1700000.00, 'ssd1tb.png', NULL, 3, NULL, 1, 0, 60);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(16, 6, 3, 4, '2', '2025-03-21 19:14:52'),
(17, 6, 4, 3, '2', '2025-03-21 23:31:24'),
(18, 14, 4, 2, '3', '2025-03-26 04:43:18'),
(19, 6, 4, 4, '4', '2025-03-26 04:47:59'),
(20, 6, 4, 3, '4', '2025-03-26 04:53:24'),
(21, 6, 4, 5, '4', '2025-03-26 05:00:18'),
(22, 6, 4, 4, '4', '2025-03-26 05:06:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sthuyx` tinyint(1) DEFAULT 0,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `full_name`, `address`, `phone`, `password`, `is_admin`, `created_at`, `sthuyx`, `avatar`) VALUES
(1, '1', '2@gmail.com', NULL, NULL, NULL, '$2y$10$SFZy9YrCNTs0i3bROGNaPO0dACDpRw0i9dgW/z64/e3SYfPL5uRJy', 0, '2025-03-03 07:59:16', 0, NULL),
(2, '4', '4@gmail.com', NULL, NULL, NULL, '$2y$10$F/XxDBQCsOYol/tad0AKounrqP2y1QKjaG2yvIi57NUup92UPC50e', 0, '2025-03-05 17:08:50', 0, NULL),
(3, 'trang', 'trang@gmail.com', NULL, NULL, NULL, '$2y$10$pgQG/0BVXLD2UOwLR2eYpuLwCwv0eF0XIAQ4TyiMAxs4ms4sBYKj6', 0, '2025-03-05 07:01:20', 0, NULL),
(4, 'sthuyx', 'sthuyx@gmail.com', 'Bin', '222', '0931645056', '$2y$10$aNUzu3Ea7U5v/GYSI.H5OO2txDk1bHUJJDUPM2gxQOWYdC5HZgq/G', 1, '2025-03-04 14:45:25', 0, '4_1742548790.jpg');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order` (`order_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`),
  ADD KEY `promotion_id` (`promotion_id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`);

--
-- Các ràng buộc cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD CONSTRAINT `promotions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `promotions_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
