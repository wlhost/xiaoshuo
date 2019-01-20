-- ----------------------------
-- Table structure for xwx_author
-- ----------------------------
DROP TABLE IF EXISTS `xwx_author`;
CREATE TABLE `xwx_author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(30) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `author_name` (`author_name`) USING BTREE,
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for xwx_banner
-- ----------------------------
DROP TABLE IF EXISTS `xwx_banner`;
CREATE TABLE `xwx_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pic_name` varchar(50) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `book_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for xwx_book
-- ----------------------------
DROP TABLE IF EXISTS `xwx_book`;
CREATE TABLE `xwx_book` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `book_name` varchar(50) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `last_time` int(11) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `tags` varchar(100) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `end` tinyint(4) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `click` bigint(20) DEFAULT NULL,
  `src` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `book_name` (`book_name`) USING BTREE,
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`),
  KEY `last_time` (`last_time`),
  KEY `tags` (`tags`),
  KEY `end` (`end`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for xwx_category
-- ----------------------------
DROP TABLE IF EXISTS `xwx_category`;
CREATE TABLE `xwx_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(50) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  `gender` varchar(10) NOT NULL,
  UNIQUE KEY `cate_name` (`cate_name`) USING BTREE,
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`),
  KEY `gender` (`gender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for xwx_chapter
-- ----------------------------
DROP TABLE IF EXISTS `xwx_chapter`;
CREATE TABLE `xwx_chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_name` varchar(255) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `book_id` bigint(20) NOT NULL,
  `isvip` tinyint(4) DEFAULT NULL,
  `order` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `chapter_name` (`chapter_name`(250)),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`),
  KEY `book_id` (`book_id`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for xwx_tag
-- ----------------------------
DROP TABLE IF EXISTS `xwx_tag`;
CREATE TABLE `xwx_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tag_name` (`tag_name`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for xwx_admin
-- ----------------------------
DROP TABLE IF EXISTS `xwx_admin`;
CREATE TABLE `xwx_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(12) NOT NULL,
  `password` varchar(100) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT NULL,
  `last_login_ip` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

