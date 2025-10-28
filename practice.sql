-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 
-- 伺服器版本: 10.1.22-MariaDB
-- PHP 版本： 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `practice`
--

-- --------------------------------------------------------

--
-- 資料表結構 `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `pdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `activity`
--

INSERT INTO `activity` (`id`, `title`, `description`, `pdate`) VALUES
(1, '迎新茶會', '新生入學第一週，由系學會舉辦的迎新活動，包含破冰遊戲與師長介紹。', '2025-10-05 14:00:00'),
(2, '系友座談會', '邀請畢業系友回校分享職場經驗與升學建議，提供學生實務方向參考。', '2025-10-12 18:30:00'),
(3, '企業參訪', '安排同學參訪知名科技公司，了解產業發展趨勢與企業文化。', '2025-10-18 09:00:00'),
(4, '中秋烤肉晚會', '全系同學共同參與的聯誼活動，增進彼此交流與團隊感。', '2025-10-25 17:30:00'),
(5, '期中考慰勞週', '期中考結束後，由系學會舉辦輕鬆茶會，提供飲料與點心，紓解壓力。', '2025-11-02 12:00:00');

-- --------------------------------------------------------

--
-- 資料表結構 `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `company` varchar(45) NOT NULL,
  `content` text NOT NULL,
  `pdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `job`
--

INSERT INTO `job` (`id`, `company`, `content`, `pdate`) VALUES
(2, '樹德資訊', '誠徵雲端工程師，一年工作經驗以上', '2025-10-19'),
(3, '伯達資訊', '誠徵雲端工程師，三年工作經驗以上', '2025-10-20'),
(4, '利瑪竇資訊', '誠徵雲端工程師，三年工作經驗以上', '2025-10-25'),
(5, '輔雲科技', '誠徵雲端工程師，一年工作經驗以上', '2025-10-25'),
(6, '輔雲科技', '誠徵程式設計師，一年工作經驗以上', '2025-10-25'),
(7, '羅耀拉科技', '誠徵程式設計師，無經驗可。', '2025-10-31'),
(8, '羅耀拉科技', '誠徵雲端工程師，無經驗可。', '2025-11-05'),
(9, '樹德資訊', '誠徵專案經理，三年工作經驗以上。', '2025-11-05'),
(10, '伯達資訊', '誠徵專案經理，三年工作經驗以上。', '2025-11-05'),
(11, '羅耀拉科技', '誠徵專案經理，三年工作經驗以上。', '2025-11-07');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `account` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` char(1) CHARACTER SET ascii NOT NULL DEFAULT 'U',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `user`
--

INSERT INTO `user` (`account`, `password`, `name`, `role`, `created_at`) VALUES
('M', 'pw', '管理員', 'M', '2025-10-28 14:14:29'),
('T1', 'pw1', '吳老師', 'T', '2025-10-21 13:35:31'),
('user1', 'pw1', '小明', 'S', '2025-10-21 13:35:31'),
('user2', 'pw2', '小華', 'S', '2025-10-21 13:35:31'),
('user3', 'pw3', '小美', 'S', '2025-10-21 13:35:31'),
('user4', 'pw4', '小強', 'S', '2025-10-21 13:35:31');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`account`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用資料表 AUTO_INCREMENT `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
