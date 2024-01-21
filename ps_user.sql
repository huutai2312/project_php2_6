-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th1 21, 2024 lúc 03:01 PM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ps_project_php2_6`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ps_user`
--

CREATE TABLE `ps_user` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` int DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` int DEFAULT NULL,
  `is_admin` int NOT NULL DEFAULT '0' COMMENT '1: admin\r\n0: customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `ps_user`
--

INSERT INTO `ps_user` (`id`, `name`, `first_name`, `last_name`, `age`, `company`, `email`, `password`, `address`, `address2`, `phone`, `city`, `country`, `postal_code`, `is_admin`) VALUES
(1, 'nht', 'aaa', 'bb', 20, 'XDNT PHAT DAT', 'a@gmail.com', '$2y$10$QLeajlno6I16CRoVRRuNN.6Hd2GDKyP7rFfisO8HbmMtb4mIz13Ee', 'asdavasdv', 'arebdfba', 987654321, 'hcm', '', 70000, 1),
(2, 'abc', '', '', 0, '', 'b@gmail.com', '$2y$10$y2FXN5PUfE6SU36isSSKBOjKIgL5fgxhxWaD5ZO5XUc1eCnjONzAO', '', '', 0, '', '', 0, 0),
(3, 'ndc', '', '', 0, '', 'c@gmail.com', '$2y$10$VYkSoYnjSGFvDNom3WtB/.zqeyzG1rs.fN0hmaKH4kM6cD4vmpab.', '', '', 0, '', '', 0, 0),
(4, 'sd', '', '', 0, '', 'd@gmail.com', '$2y$10$lEsWOAH4DR/gHXLs1jpHVOEFlYPGf/qt9MFrUBjMVU8RU1sMZaFgC', '', '', 0, '', '', 0, 0),
(5, 'ddd', '', '', 0, '', 'd@gmail.com', '$2y$10$9elR1WupfJAiS3Bi/oBGvurIB.w0W1Z2TrSk4ULiPY4U954/J3ToG', '', '', 0, '', '', 0, 0),
(6, 'dddd', '', '', 0, '', 'hhh@gmail.com', '$2y$10$EdFPXn0Rlaw85CJbpUajoujWvtpGSTIx3lVr1mBDD9bzltfXIkewa', '', '', 0, '', '', 0, 0),
(7, 'j', '', '', 0, '', 'j@gmail.com', '$2y$10$cpbU5UMrt/tllOijVB5w0O3opbhsR5bv8RfrR8mNIAq4r2tlFZmRG', '', '', 0, '', '', 0, 0),
(8, ',sjfbv', '', '', 0, '', 'n@gmail.com', '$2y$10$/IxiUJ8dpucotVDcXuvi6uZCkcbg29tOnms5youmHSl099V5XxBNG', '', '', 0, '', '', 0, 0),
(9, 'fgng', NULL, NULL, NULL, NULL, '12@gmail.com', '$2y$10$Pe/SJFLJtjzxRsWCuhBuiO7BoSAuFs7PwT904s2cKKKVXJZdHbOTm', '', NULL, 0, NULL, NULL, NULL, 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ps_user`
--
ALTER TABLE `ps_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ps_user`
--
ALTER TABLE `ps_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
