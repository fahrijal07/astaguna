copy Kode dibaawah ini supaya ngehapus database lama dan buat database baru
kalo ada bug atau eror bisa tanyaain di grup 

cd C:/xampp/mysql/bin

mysql -u root

DROP DATABASE IF EXIST astaguna;

CREATE DATABASE astaguna;

use astaguna;

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `item_type` enum('paket','item') NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `notes` text DEFAULT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` enum('paket','lauk','sayur','nasi','pendamping') NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `category`, `image_url`, `is_active`, `created_at`) VALUES
(1, 'Ayam goreng bumbu Jawa', 'Ayam goreng dengan bumbu khas Jawa', 7000.00, 'lauk', NULL, 1, '2025-12-10 05:15:39'),
(2, 'Ayam Katsu', 'Ayam katsu krispi', 6000.00, 'lauk', NULL, 1, '2025-12-10 05:15:39'),
(3, 'Ayam Bakar Kecap', 'Ayam bakar dengan bumbu kecap manis', 8000.00, 'lauk', NULL, 1, '2025-12-10 05:15:39'),
(4, 'Ayam Krispi', 'Ayam goreng krispi', 6000.00, 'lauk', NULL, 1, '2025-12-10 05:15:39'),
(5, 'Lele Bakar / Goreng', 'Lele pilihan bakar atau goreng', 7000.00, 'lauk', NULL, 1, '2025-12-10 05:15:39'),
(6, 'Perkedel kentang', 'Perkedel kentang gurih', 2500.00, 'lauk', NULL, 1, '2025-12-10 05:15:39'),
(7, 'Ayam Lodho', 'Ayam lodho khas Tulungagung', 9000.00, 'lauk', NULL, 1, '2025-12-10 05:15:39'),
(8, 'Telur Balado', 'Telur balado pedas', 4000.00, 'lauk', NULL, 1, '2025-12-10 05:15:39'),
(9, 'Sayur Sop', 'Sayur sop segar', 2000.00, 'sayur', NULL, 1, '2025-12-10 05:15:39'),
(10, 'Sayur Asem', 'Sayur asem khas Jawa', 2000.00, 'sayur', NULL, 1, '2025-12-10 05:15:39'),
(11, 'Urap Sayur', 'Urap sayur dengan kelapa parut', 2500.00, 'sayur', NULL, 1, '2025-12-10 05:15:39'),
(12, 'Pecel Sambel Kacang', 'Pecel dengan sambal kacang', 3000.00, 'sayur', NULL, 1, '2025-12-10 05:15:39'),
(13, 'Lalapan + Sambel', 'Lalapan segar dengan sambal', 2000.00, 'sayur', NULL, 1, '2025-12-10 05:15:39'),
(14, 'Kerupuk', 'Kerupuk renyah', 1000.00, 'pendamping', NULL, 1, '2025-12-10 05:15:39'),
(15, 'Tempe/Tahu Bacem', 'Tempe atau tahu bacem manis', 2500.00, 'pendamping', NULL, 1, '2025-12-10 05:15:39'),
(16, 'Tahu/Tempe Goreng', 'Tahu atau tempe goreng', 1500.00, 'pendamping', NULL, 1, '2025-12-10 05:15:39'),
(17, 'Nasi Putih', 'Nasi putih pulen', 3000.00, 'nasi', NULL, 1, '2025-12-10 05:15:39'),
(18, 'Nasi Kuring', 'Nasi kuning khas Jawa', 4500.00, 'nasi', NULL, 1, '2025-12-10 05:15:39'),
(19, 'Nasi Uduk', 'Nasi uduk gurih', 5000.00, 'nasi', NULL, 1, '2025-12-10 05:15:39');

CREATE TABLE `menu_paket` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `review_count` int(11) DEFAULT 0,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `menu_paket` (`id`, `name`, `description`, `price`, `rating`, `review_count`, `is_available`, `created_at`) VALUES
(1, 'Paket A - \"Sego Kampung\"', 'Paket lengkap dengan ayam goreng bumbu kuning, urap sayur, tempe bacem, dan sambal terasi', 15000.00, 4.90, 209, 1, '2025-12-10 05:15:40'),
(2, 'Paket B - \"Sego Kremesan\"', 'Paket dengan ayam krispi kremes, sayur sop Jawa, tahu/tempe goreng', 12000.00, 4.40, 156, 1, '2025-12-10 05:15:40'),
(3, 'Paket C - \"Sego Asem Manis\"', 'Paket ayam bakar kecap manis dengan sayur asem Jawa dan lalapan', 15000.00, 4.10, 123, 1, '2025-12-10 05:15:40'),
(4, 'Paket D - \"Sego Gudangan\"', 'Paket ayam katsu dengan sayuran dan sambal pilihan', 12000.00, 3.70, 89, 1, '2025-12-10 05:15:40'),
(5, 'Paket E - \"Menu Mantan Ndeso\" (premium)', 'Paket premium dengan ayam lodho, sambal pecel, dan nasi kuning', 0.00, 0.00, 0, 1, '2025-12-10 05:15:40');

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `order_code` varchar(20) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','ready','delivered','cancelled') DEFAULT 'pending',
  `delivery_address` text DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_time` time DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `cart_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `service_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orders` (`id`, `session_id`, `order_code`, `customer_name`, `customer_phone`, `customer_email`, `total_price`, `status`, `delivery_address`, `delivery_date`, `delivery_time`, `notes`, `payment_method`, `bank`, `cart_total`, `service_fee`, `grand_total`, `created_at`, `updated_at`) VALUES
(7, NULL, 'AGA-20251212-7681', 'dddd', '99999', '', 7000.00, 'pending', 'iud', '2025-12-12', '13:29:00', 'jjj', 'cod', '', 0.00, 0.00, 0.00, '2025-12-12 06:30:22', '2025-12-12 06:30:22'),
(8, NULL, 'AGA-20251212-8496', 'dddd', '99999', '', 39000.00, 'pending', 'dddddd', '2025-12-12', '13:45:00', '', 'cod', '', 0.00, 0.00, 0.00, '2025-12-12 06:45:45', '2025-12-12 06:45:45'),
(9, 'cart_693bb00b9e9613.20566368', 'AC-20251212-635F8C', 'dddd', '008786756546', NULL, 0.00, '', 'jjjjj', '2025-12-12', NULL, NULL, 'cod', NULL, 36000.00, 0.00, 36000.00, '2025-12-12 07:57:54', '2025-12-12 07:57:54'),
(10, 'cart_693bb00b9e9613.20566368', 'AC-20251212-70BC3D', 'dddd', '008786756546', NULL, 0.00, '', 'jjjjj', '2025-12-12', NULL, NULL, 'cod', NULL, 36000.00, 0.00, 36000.00, '2025-12-12 07:59:14', '2025-12-12 07:59:14'),
(11, 'cart_693bb00b9e9613.20566368', 'AC-20251212-E4C717', 'jjjj', '008786756546', NULL, 0.00, '', 'uuuuu', '2025-12-12', NULL, NULL, 'cod', NULL, 15000.00, 0.00, 15000.00, '2025-12-12 07:59:52', '2025-12-12 07:59:52'),
(12, 'cart_6939086ac88ea9.16110129', 'AC-20251212-EFBB66', 'hoao[oa[', '98740183748321', NULL, 0.00, '', 'dwiud', NULL, NULL, NULL, 'cod', NULL, 6000.00, 0.00, 6000.00, '2025-12-12 08:26:16', '2025-12-12 08:26:16');

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_type` enum('item','paket') NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `order_items` (`id`, `order_id`, `item_type`, `item_id`, `item_name`, `item_price`, `qty`, `subtotal`, `created_at`) VALUES
(1, 10, 'paket', 1, 'Paket A - \"Sego Kampung\"', 15000.00, 2, 30000.00, '2025-12-12 07:59:14'),
(2, 10, 'item', 2, 'Ayam Katsu', 6000.00, 1, 6000.00, '2025-12-12 07:59:14'),
(3, 11, 'paket', 1, 'Paket A - \"Sego Kampung\"', 15000.00, 1, 15000.00, '2025-12-12 07:59:52'),
(4, 12, 'item', 2, 'Ayam Katsu', 6000.00, 1, 6000.00, '2025-12-12 08:26:16');

CREATE TABLE `paket_items` (
  `paket_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `paket_items` (`paket_id`, `menu_item_id`, `quantity`) VALUES
(1, 1, 1),
(1, 12, 1),
(1, 18, 1),
(2, 4, 1),
(2, 10, 1),
(2, 19, 1),
(3, 3, 1),
(3, 11, 1),
(3, 14, 1);

ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `customer_service_messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `menu_paket`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `idx_orders_session_id` (`session_id`),
  ADD KEY `idx_orders_order_code` (`order_code`);

ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

ALTER TABLE `paket_items`
  ADD PRIMARY KEY (`paket_id`,`menu_item_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

ALTER TABLE `customer_service_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `menu_paket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

ALTER TABLE `paket_items`
  ADD CONSTRAINT `paket_items_ibfk_1` FOREIGN KEY (`paket_id`) REFERENCES `menu_paket` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paket_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

CREATE TABLE ulasan (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    rating INT(1) NOT NULL,
    text TEXT NOT NULL 
);