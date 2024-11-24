-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Nov 2024 pada 14.32
-- Versi server: 8.0.38
-- Versi PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `backup_users`
--

CREATE TABLE `backup_users` (
  `id_user` int NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_level` tinyint(1) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `backup_users`
--

INSERT INTO `backup_users` (`id_user`, `customer_name`, `username`, `password`, `id_level`) VALUES
(1, 'Este', 'kuda', '$2y$10$srh66XnjsomXYvmJkd3.3uE2DaypRuN3LHgPouHhw/oNm6KreWHmq', 2),
(2, 'Yanto', 'Este', '$2y$10$TdjEe7RwLk1fOpZ87ztmKe.KTe3qTM0z4Vs.l524xvMuAGziD5dGK', 2),
(3, 'tete', 'tete', '$2y$10$e/alrb0WcQyX1T2w5QS2ru2TDBish.sZdKKgsRixwqdoxeHU7D9Ea', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `id_masakan` int NOT NULL,
  `quantity` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `cart`
--

INSERT INTO `cart` (`id`, `id_user`, `id_masakan`, `quantity`, `created_at`) VALUES
(4, 12, 9, 4, '2024-11-21 06:46:39'),
(19, 12, 12, 1, '2024-11-22 01:56:26'),
(20, 12, 13, 1, '2024-11-22 01:56:28'),
(21, 19, 14, 1, '2024-11-22 02:22:25'),
(39, 2, 9, 3, '2024-11-24 11:22:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart_backup`
--

CREATE TABLE `cart_backup` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer_order`
--

CREATE TABLE `customer_order` (
  `id_order` int NOT NULL,
  `no_meja` int NOT NULL,
  `tanggal` date NOT NULL,
  `id_user` int DEFAULT NULL,
  `keterangan` text,
  `status_order` tinyint(1) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `customer_order`
--

INSERT INTO `customer_order` (`id_order`, `no_meja`, `tanggal`, `id_user`, `keterangan`, `status_order`, `customer_name`, `total_amount`) VALUES
(1, 1, '2024-11-14', 1, 'Special instructions', 1, NULL, '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_order`
--

CREATE TABLE `detail_order` (
  `id_detail_order` int NOT NULL,
  `id_order` int DEFAULT NULL,
  `id_masakan` int DEFAULT NULL,
  `keterangan` text,
  `status_detail_order` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `level`
--

CREATE TABLE `level` (
  `id_level` int NOT NULL,
  `nama_level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `level`
--

INSERT INTO `level` (`id_level`, `nama_level`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Owner'),
(4, 'Waiter'),
(5, 'Cashier');

-- --------------------------------------------------------

--
-- Struktur dari tabel `masakan`
--

CREATE TABLE `masakan` (
  `id_masakan` int NOT NULL,
  `nama_masakan` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status_masakan` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `masakan`
--

INSERT INTO `masakan` (`id_masakan`, `nama_masakan`, `harga`, `image`, `status_masakan`) VALUES
(9, 'rendang', '30000.00', 'uploads/rendang.jpg', 1),
(11, 'rujak', '10000.00', 'uploads/673aaa284d629_rujak-gorengan-di-meja-diplomatik-indonesia-china-guptiVGS9Y.jpg', 0),
(12, 'rawon', '10000.00', 'uploads/673ada538e28b_tips-bikin-rawon-seenak-warung-makan.jpg', 0),
(13, 'jawa', '3000.00', 'uploads/673bf32882972_Cooked-Japanese-rice-being-lifted-with-chopsticks-shutterstock-sw.jpg', 1),
(14, 'Sate', '12000.00', 'uploads/673fead130804_sate.jpg', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id_order` int NOT NULL,
  `id_user` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_masakan` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `total_amount`, `created_at`, `id_masakan`, `quantity`, `status`, `harga`) VALUES
(3, 2, '20000.00', '2024-11-21 17:10:02', 11, 9, 'not_received', '10000.00'),
(5, 2, '390000.00', '2024-11-21 20:48:09', 9, 13, 'received', '30000.00'),
(6, 2, '10000.00', '2024-11-21 20:48:09', 11, 1, 'completed', '10000.00'),
(7, 2, '10000.00', '2024-11-21 20:48:09', 12, 1, 'canceled', '10000.00'),
(8, 2, '3000.00', '2024-11-21 20:48:09', 13, 4, 'not_received', '3000.00'),
(9, 20, '30000.00', '2024-11-22 03:09:19', 9, 1, 'pending', '30000.00'),
(10, 20, '12000.00', '2024-11-22 03:09:19', 14, 1, 'pending', '12000.00'),
(11, 2, '90000.00', '2024-11-22 03:10:12', 9, 3, 'pending', '30000.00'),
(12, 2, '36000.00', '2024-11-22 03:10:12', 14, 3, 'pending', '12000.00'),
(13, 2, '6000.00', '2024-11-22 03:10:12', 13, 2, 'pending', '3000.00'),
(14, 2, '60000.00', '2024-11-22 03:13:45', 9, 2, 'pending', '30000.00'),
(15, 2, '20000.00', '2024-11-22 03:13:45', 11, 2, 'pending', '10000.00'),
(16, 2, '24000.00', '2024-11-24 04:58:10', 14, 2, 'pending', '12000.00'),
(17, 2, '3000.00', '2024-11-24 04:58:10', 13, 1, 'received', '3000.00'),
(18, 2, '50000.00', '2024-11-24 05:01:35', 11, 5, 'pending', '10000.00'),
(19, 2, '30000.00', '2024-11-24 09:35:41', 9, 1, 'pending', '30000.00'),
(20, 2, '10000.00', '2024-11-24 09:35:41', 11, 1, 'pending', '10000.00'),
(21, 2, '10000.00', '2024-11-24 09:35:42', 12, 1, 'pending', '10000.00'),
(22, 2, '10000.00', '2024-11-24 10:42:23', 11, 1, 'pending', '10000.00'),
(23, 2, '30000.00', '2024-11-24 10:42:23', 9, 1, 'pending', '30000.00'),
(24, 2, '30000.00', '2024-11-24 10:53:02', 9, 1, 'pending', '30000.00'),
(25, 2, '6000.00', '2024-11-24 11:02:25', 13, 2, 'pending', '3000.00'),
(26, 23, '30000.00', '2024-11-24 11:53:14', 9, 1, 'pending', '30000.00'),
(27, 23, '10000.00', '2024-11-24 11:53:14', 11, 1, 'pending', '10000.00'),
(28, 23, '10000.00', '2024-11-24 11:53:14', 12, 1, 'pending', '10000.00'),
(29, 23, '6000.00', '2024-11-24 11:53:14', 13, 2, 'pending', '3000.00'),
(30, 23, '24000.00', '2024-11-24 11:53:14', 14, 2, 'pending', '12000.00');

--
-- Trigger `orders`
--
DELIMITER $$
CREATE TRIGGER `calculate_total_amount` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
    DECLARE dish_price DECIMAL(10, 2);
    
    
    SELECT harga INTO dish_price FROM masakan WHERE id_masakan = NEW.id_masakan;
    
    
    SET NEW.total_amount = dish_price * NEW.quantity;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `id_order` int NOT NULL,
  `id_masakan` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reports`
--

CREATE TABLE `reports` (
  `id_report` int NOT NULL,
  `id_user` int NOT NULL,
  `report_content` text NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `reports`
--

INSERT INTO `reports` (`id_report`, `id_user`, `report_content`, `role`, `created_at`) VALUES
(1, 2, 'test', 'User', '2024-11-24 13:24:39'),
(2, 12, 'ses', '2', '2024-11-24 13:28:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_order` int DEFAULT NULL,
  `tanggal` date NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_order`, `tanggal`, `total_bayar`) VALUES
(1, 1, '2024-11-14', '15000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `id_level` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama_user`, `id_level`) VALUES
(1, 'admin_user', 'admin', 'Admin User', 1),
(2, 'regular_user', 'user', 'Regular User', 2),
(3, 'kuta', 'kuta', 'Yaki', NULL),
(4, 'kara', 'kara', 'kuro', NULL),
(5, 'tete', 'tete', 'tete', NULL),
(6, 'yari', 'yari', 'yari', NULL),
(10, 'please', 'please', 'please', 3),
(11, 'Kayaka', 'Kayaka', 'Kayaka', NULL),
(12, 'qwe', 'qwe', 'qwe', 2),
(13, 'wait', 'wait', 'wait', 4),
(15, 'katan', 'katan', 'katan', NULL),
(16, 'yea', 'yea', 'yea', 4),
(17, 'ter', 'ter', 'ter', 4),
(18, 'qweee', 'qwe', 'qweee', 2),
(19, 'yeko', 'yeko', 'yeko', 2),
(20, 'user', 'user', 'user', 2),
(21, 'yapp', 'tapp', 'yapp', 5),
(22, 'yapp', 'tapp', 'yapp', 5),
(23, 'uy', 'uy', 'uy', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama_user` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_level` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama_user`, `username`, `password`, `id_level`) VALUES
(2, 'kura', 'kura', '$2y$10$/E9NHSu5SXPdhCiJiOVlo.uqwv/hig4VjGUpBasgp9pLwq5h2MdkG', 4),
(4, 'yabai', 'yabai', '$2y$10$EpkKMX6I7r4wf0b0CS.ob./rGfhMjHnGGJKc.AlLjG6WqvIqLm.ua', 5),
(5, 'soka', 'soka', '$2y$10$UOPAT8FZ67TB8IjfsDSbLuQ.6SgbLusXnEQtfZCCNhLQWCehJgMDO', 2),
(6, 'yep', 'yep', 'yep', 2),
(7, 'hash', 'hash', 'hash', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `workers`
--

CREATE TABLE `workers` (
  `id_worker` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` enum('owner','cashier','waiter') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `workers`
--

INSERT INTO `workers` (`id_worker`, `username`, `password`, `name`, `role`) VALUES
(1, 'owner1', 'ownerpass', 'John Doe', 'owner'),
(2, 'cashier1', 'cashierpass', 'Jane Smith', 'cashier'),
(3, 'waiter1', 'waiterpass', 'Alice Brown', 'waiter');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `backup_users`
--
ALTER TABLE `backup_users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_masakan` (`id_masakan`);

--
-- Indeks untuk tabel `cart_backup`
--
ALTER TABLE `cart_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `customer_order`
--
ALTER TABLE `customer_order`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `detail_order`
--
ALTER TABLE `detail_order`
  ADD PRIMARY KEY (`id_detail_order`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `fk_detail_order_masakan` (`id_masakan`);

--
-- Indeks untuk tabel `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id_level`);

--
-- Indeks untuk tabel `masakan`
--
ALTER TABLE `masakan`
  ADD PRIMARY KEY (`id_masakan`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `fk_order_items` (`id_masakan`);

--
-- Indeks untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id_report`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_order` (`id_order`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_level` (`id_level`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_level` (`id_level`);

--
-- Indeks untuk tabel `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id_worker`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `backup_users`
--
ALTER TABLE `backup_users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `cart_backup`
--
ALTER TABLE `cart_backup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `customer_order`
--
ALTER TABLE `customer_order`
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `detail_order`
--
ALTER TABLE `detail_order`
  MODIFY `id_detail_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `level`
--
ALTER TABLE `level`
  MODIFY `id_level` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `masakan`
--
ALTER TABLE `masakan`
  MODIFY `id_masakan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reports`
--
ALTER TABLE `reports`
  MODIFY `id_report` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `workers`
--
ALTER TABLE `workers`
  MODIFY `id_worker` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_masakan`) REFERENCES `masakan` (`id_masakan`);

--
-- Ketidakleluasaan untuk tabel `customer_order`
--
ALTER TABLE `customer_order`
  ADD CONSTRAINT `customer_order_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `detail_order`
--
ALTER TABLE `detail_order`
  ADD CONSTRAINT `detail_order_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `customer_order` (`id_order`),
  ADD CONSTRAINT `fk_detail_order_masakan` FOREIGN KEY (`id_masakan`) REFERENCES `masakan` (`id_masakan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items` FOREIGN KEY (`id_masakan`) REFERENCES `masakan` (`id_masakan`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`id_masakan`) REFERENCES `masakan` (`id_masakan`);

--
-- Ketidakleluasaan untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `customer_order` (`id_order`);

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `level` (`id_level`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `level` (`id_level`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
