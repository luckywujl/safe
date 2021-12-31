-- MySQL dump 10.13  Distrib 8.0.16, for linux-glibc2.12 (x86_64)
--
-- Host: localhost    Database: safe_haxxj_com
-- ------------------------------------------------------
-- Server version	8.0.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `safe_admin`
--

DROP TABLE IF EXISTS `safe_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '昵称',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '登录IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  `department_id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '管辖部门',
  `company_id` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_admin`
--

LOCK TABLES `safe_admin` WRITE;
/*!40000 ALTER TABLE `safe_admin` DISABLE KEYS */;
INSERT INTO `safe_admin` VALUES (1,'admin','Admin','e811f73b10887a36a7bfd1ba8788bb3a','ada35f','/assets/img/avatar.png','admin@admin.com',0,1640401702,'127.0.0.1',1491635035,1640401702,'d6390578-d3c4-4e1d-931a-18f3bc2492ea','normal',NULL,'2'),(2,'1001','安全管理员','ce211d1bdc4dae836de3cda8b62c8215','h45cUg','/assets/img/avatar.png','1001@qq.com',0,1640423348,'127.0.0.1',1634451527,1640423348,'7a25addc-d442-4eda-bf69-bf21f6116615','normal',NULL,'2'),(3,'2001','2001','abcf98d9e5bfea1de27ac31220b3710c','eHzvDM','/assets/img/avatar.png','2001@qq.com',0,1634654660,'127.0.0.1',1634654641,1634654660,'99241773-63b5-44cf-b500-c10bcb3ffa92','normal',NULL,'3'),(4,'3001','安全员','cee55a073dad14524e0e79520bb781d3','CwxT3e','/assets/img/avatar.png','3001@qq.com',0,NULL,NULL,1635595599,1635595599,'','normal',NULL,'2'),(5,'zhangde','张昨','59539498de232599ff0d5ee715ca6def','tCZQm3','/assets/img/avatar.png','15358690088@qq.com',0,NULL,NULL,1640340556,1640360095,'','normal',NULL,'2');
/*!40000 ALTER TABLE `safe_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_admin_log`
--

DROP TABLE IF EXISTS `safe_admin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '日志标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'User-Agent',
  `createtime` int(10) DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_admin_log`
--

LOCK TABLES `safe_admin_log` WRITE;
/*!40000 ALTER TABLE `safe_admin_log` DISABLE KEYS */;
INSERT INTO `safe_admin_log` VALUES (6,0,'Unknown','/aycZbSNkuR.php/index/logout','','{\"__token__\":\"***\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640360553),(7,1,'admin','/aycZbSNkuR.php/index/login','登录','{\"__token__\":\"***\",\"username\":\"admin\",\"password\":\"***\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640360556),(8,1,'admin','/aycZbSNkuR.php/addon/install','插件管理','{\"name\":\"geetest\",\"force\":\"0\",\"uid\":\"25581\",\"token\":\"***\",\"version\":\"1.0.1\",\"faversion\":\"1.2.2.20211011_beta\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640360761),(9,1,'admin','/aycZbSNkuR.php/addon/install','插件管理','{\"name\":\"loginvideo\",\"force\":\"0\",\"uid\":\"25581\",\"token\":\"***\",\"version\":\"1.0.1\",\"faversion\":\"1.2.2.20211011_beta\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640360793),(10,0,'Unknown','/aycZbSNkuR.php/index/logout','','{\"__token__\":\"***\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640360800),(12,0,'Unknown','/aycZbSNkuR.php/index/logout','','{\"__token__\":\"***\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640360847),(16,0,'Unknown','/aycZbSNkuR.php/index/logout','','{\"__token__\":\"***\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640360993),(21,1,'admin','/aycZbSNkuR.php/index/login.html','登录','{\"__token__\":\"***\",\"username\":\"admin\",\"password\":\"***\",\"captcha\":\"ok\",\"geetest_challenge\":\"a7de763d57268b7d8c59db25f6b98112\",\"geetest_validate\":\"358fff08e039fbee29a82e8714726e6b\",\"geetest_seccode\":\"358fff08e039fbee29a82e8714726e6b|jordan\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397316),(22,1,'admin','/aycZbSNkuR.php/command/get_field_list','在线命令管理','{\"table\":\"safe_admin\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397325),(23,1,'admin','/aycZbSNkuR.php/command/get_controller_list','在线命令管理','{\"q_word\":[\"\"],\"pageNumber\":\"1\",\"pageSize\":\"10\",\"andOr\":\"OR \",\"orderBy\":[[\"name\",\"ASC\"]],\"searchTable\":\"tbl\",\"showField\":\"name\",\"keyField\":\"id\",\"searchField\":[\"name\"],\"name\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397326),(24,1,'admin','/aycZbSNkuR.php/command/get_controller_list','在线命令管理','{\"q_word\":[\"\"],\"pageNumber\":\"2\",\"pageSize\":\"10\",\"andOr\":\"OR \",\"orderBy\":[[\"name\",\"ASC\"]],\"searchTable\":\"tbl\",\"showField\":\"name\",\"keyField\":\"id\",\"searchField\":[\"name\"],\"name\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397328),(25,1,'admin','/aycZbSNkuR.php/command/get_controller_list','在线命令管理','{\"q_word\":[\"\"],\"pageNumber\":\"3\",\"pageSize\":\"10\",\"andOr\":\"OR \",\"orderBy\":[[\"name\",\"ASC\"]],\"searchTable\":\"tbl\",\"showField\":\"name\",\"keyField\":\"id\",\"searchField\":[\"name\"],\"name\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397330),(26,1,'admin','/aycZbSNkuR.php/command/get_controller_list','在线命令管理','{\"q_word\":[\"\"],\"pageNumber\":\"3\",\"pageSize\":\"10\",\"andOr\":\"OR \",\"orderBy\":[[\"name\",\"ASC\"]],\"searchTable\":\"tbl\",\"showField\":\"name\",\"keyField\":\"id\",\"searchField\":[\"name\"],\"name\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397335),(27,1,'admin','/aycZbSNkuR.php/command/command/action/command','在线命令管理','{\"commandtype\":\"menu\",\"allcontroller\":\"0\",\"delete\":\"0\",\"force\":\"0\",\"controllerfile_text\":\"\",\"controllerfile\":\"trouble\\/trouble\\/Recevie.php\",\"action\":\"command\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397337),(28,1,'admin','/aycZbSNkuR.php/command/command/action/command','在线命令管理','{\"commandtype\":\"menu\",\"allcontroller\":\"0\",\"delete\":\"0\",\"force\":\"1\",\"controllerfile_text\":\"\",\"controllerfile\":\"trouble\\/trouble\\/Recevie.php\",\"action\":\"command\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397339),(29,1,'admin','/aycZbSNkuR.php/command/command/action/command','在线命令管理','{\"commandtype\":\"menu\",\"allcontroller\":\"0\",\"delete\":\"0\",\"force\":\"1\",\"controllerfile_text\":\"\",\"controllerfile\":\"trouble\\/trouble\\/Recevie.php\",\"action\":\"command\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397340),(30,1,'admin','/aycZbSNkuR.php/command/command/action/execute','在线命令管理','{\"commandtype\":\"menu\",\"allcontroller\":\"0\",\"delete\":\"0\",\"force\":\"1\",\"controllerfile_text\":\"\",\"controllerfile\":\"trouble\\/trouble\\/Recevie.php\",\"action\":\"execute\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397342),(31,1,'admin','/aycZbSNkuR.php/auth/group/roletree','权限管理 / 角色组','{\"id\":\"6\",\"pid\":\"1\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397350),(32,1,'admin','/aycZbSNkuR.php/auth/group/edit/ids/6?dialog=1','权限管理 / 角色组 / 编辑','{\"dialog\":\"1\",\"__token__\":\"***\",\"row\":{\"rules\":\"1,7,8,9,10,11,13,14,15,16,17,23,24,25,26,27,28,29,30,31,32,33,34,40,41,42,43,44,45,46,47,48,49,50,67,68,69,70,71,72,73,74,75,76,77,78,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,112,113,114,115,116,117,118,119,120,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,163,164,165,166,167,168,169,170,178,179,180,181,182,183,184,185,186,198,199,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,376,377,378,379,380,381,382,390,391,392,393,394,395,396,398,399,400,401,402,403,404,405,406,407,408,409,410,412,413,414,415,416,417,418,420,421,422,423,424,425,427,428,429,443,444,445,446,447,448,449,450,451,452,453,454,455,456,457,458,459,460,461,462,463,464,465,466,467,468,469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,500,2,5,66,85,111,128,197,375,374,397,411,419\",\"pid\":\"1\",\"name\":\"企业管理员\",\"status\":\"normal\"},\"ids\":\"6\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640397368),(37,0,'Unknown','/aycZbSNkuR.php/index/logout','','{\"__token__\":\"***\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640401689),(38,1,'admin','/aycZbSNkuR.php/index/login','登录','{\"__token__\":\"***\",\"username\":\"admin\",\"password\":\"***\",\"captcha\":\"ok\",\"geetest_challenge\":\"a577d3c29e754b9c30bb54a253fafede\",\"geetest_validate\":\"4a04cde8e781d9e7d87727bc9542ead0\",\"geetest_seccode\":\"4a04cde8e781d9e7d87727bc9542ead0|jordan\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640401702),(39,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/together','隐患排查 / 隐患排查 / 接警处理 / 并案','{\"page\":\"1\",\"ids\":[\"153\"],\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640401729),(40,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/together','隐患排查 / 隐患排查 / 接警处理 / 并案','{\"page\":\"1\",\"ids\":[\"153\"],\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640401791),(41,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/dotogether','隐患排查 / 隐患排查 / 接警处理','{\"ids\":[\"153\"],\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640401872),(42,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/dotogether','隐患排查 / 隐患排查 / 接警处理','{\"ids\":[\"153\"],\"maincode\":\"YH2021120005\",\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640402517),(43,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/dotogether','隐患排查 / 隐患排查 / 接警处理','{\"ids\":[\"153\"],\"maincode\":\"YH2021120005\",\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640402534),(44,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/dotogether','隐患排查 / 隐患排查 / 接警处理','{\"ids\":[\"153\"],\"maincode\":\"YH2021120005\",\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640402629),(45,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/dotogether','隐患排查 / 隐患排查 / 接警处理','{\"ids\":[\"153\"],\"maincode\":\"\",\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640402647),(46,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/dotogether','隐患排查 / 隐患排查 / 接警处理','{\"ids\":[\"153\"],\"maincode\":\"155\",\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640402699),(48,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/dotogether','隐患排查 / 隐患排查 / 接警处理','{\"ids\":[\"153\"],\"maincode\":\"YH2021120005\",\"mainid\":\"155\",\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640403168),(50,1,'admin','/aycZbSNkuR.php/trouble/trouble/recevie/dotogether','隐患排查 / 隐患排查 / 接警处理','{\"ids\":\"153\",\"maincode\":\"YH2021120005\",\"mainid\":\"155\",\"action\":\"success\",\"reply\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36 UOS',1640403566),(68,2,'1001','/aycZbSNkuR.php/auth/adminlog/del','权限管理 / 管理员日志 / 删除','{\"action\":\"del\",\"ids\":\"67\",\"params\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640417539),(69,2,'1001','/aycZbSNkuR.php/ajax/upload','','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640418564),(70,0,'Unknown','/aycZbSNkuR.php/index/logout','','{\"__token__\":\"***\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640420177),(71,2,'1001','/aycZbSNkuR.php/index/login','登录','{\"__token__\":\"***\",\"username\":\"1001\",\"password\":\"***\",\"captcha\":\"ok\",\"geetest_challenge\":\"49d10b2d736a0b9244fc79e7e13ea1c7\",\"geetest_validate\":\"7c90616911c56c6c425d57e0e91af3cf\",\"geetest_seccode\":\"7c90616911c56c6c425d57e0e91af3cf|jordan\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640420986),(72,0,'Unknown','/aycZbSNkuR.php/index/logout','','{\"__token__\":\"***\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640421019),(73,2,'1001','/aycZbSNkuR.php/index/login','登录','{\"__token__\":\"***\",\"username\":\"1001\",\"password\":\"***\",\"captcha\":\"ok\",\"geetest_challenge\":\"81aa6d92463229aaf250c73d9353a0bd\",\"geetest_validate\":\"75b97d92de84a6c406ce507baf1b09fa\",\"geetest_seccode\":\"75b97d92de84a6c406ce507baf1b09fa|jordan\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640423348),(74,2,'1001','/aycZbSNkuR.php/user/department/jstree','人员管理 / 部门管理','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640424579),(75,2,'1001','/aycZbSNkuR.php/message/category/jstree','学习通告 / 通告分类','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640425073),(76,2,'1001','/aycZbSNkuR.php/training/category/jstree','在线培训 / 培训分类','{\"type\":\"main\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640425075),(77,2,'1001','/aycZbSNkuR.php/kaoshi/subject/jstree','考试系统 / 科目管理','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640425080),(78,2,'1001','/aycZbSNkuR.php/kaoshi/subject/jstree','考试系统 / 科目管理','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640425170),(79,2,'1001','/aycZbSNkuR.php/user/department/jstree','人员管理 / 部门管理','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640427446),(80,2,'1001','/aycZbSNkuR.php/user/user/edit/ids/33?dialog=1','人员管理 / 人员资料 / 编辑','{\"dialog\":\"1\",\"__token__\":\"***\",\"row\":{\"id\":\"33\",\"group_id\":\"4\",\"department_id\":\"3\",\"username\":\"zhangjianshan\",\"password\":\"***\",\"jobnumber\":\"04001\",\"nickname\":\"张建山\",\"gender\":\"1\",\"mobile\":\"18932312010\",\"job\":\"施工安全员\",\"usertype\":\"临时工\",\"isspecial\":\"否\",\"score\":\"1\",\"birthday\":\"1978-12-05\",\"email\":\"1234895@qq.com\",\"avatar\":\"\",\"idphoto\":\"\",\"address\":\"淮阴区\",\"status\":\"normal\",\"idcard\":\"320821197812050110\",\"education\":\"专科\",\"age\":\"43\",\"contact\":\"徐斌\",\"contactphone\":\"15358691188\",\"relation\":\"朋友\"},\"ids\":\"33\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640428004),(81,2,'1001','/aycZbSNkuR.php/materials/category/jstree','学习资料 / 资料分类','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640429918),(82,2,'1001','/aycZbSNkuR.php/message/category/jstree','学习通告 / 通告分类','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640430192),(83,2,'1001','/aycZbSNkuR.php/training/category/jstree','在线培训 / 培训分类','{\"type\":\"course\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640430320),(84,2,'1001','/aycZbSNkuR.php/training/category/jstree','在线培训 / 培训分类','{\"type\":\"main\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640430334),(85,2,'1001','/aycZbSNkuR.php/training/index/jstree','在线培训 / 培训统计','{\"type\":\"\"}','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640430686),(86,2,'1001','/aycZbSNkuR.php/user/department/jstree','人员管理 / 部门管理','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640430687),(87,2,'1001','/aycZbSNkuR.php/kaoshi/subject/jstree','考试系统 / 科目管理','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640431418),(88,2,'1001','/aycZbSNkuR.php/kaoshi/subject/jstree','考试系统 / 科目管理','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640431419),(89,2,'1001','/aycZbSNkuR.php/kaoshi/subject/jstree','考试系统 / 科目管理','[]','127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.231 Safari/537.36 Qaxbrowser',1640431421);
/*!40000 ALTER TABLE `safe_admin_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_area`
--

DROP TABLE IF EXISTS `safe_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_area` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) DEFAULT NULL COMMENT '父id',
  `shortname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '简称',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名称',
  `mergename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '全称',
  `level` tinyint(4) DEFAULT NULL COMMENT '层级 0 1 2 省市区县',
  `pinyin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '拼音',
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '长途区号',
  `zip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮编',
  `first` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '首字母',
  `lng` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '纬度',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='地区表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_area`
--

LOCK TABLES `safe_area` WRITE;
/*!40000 ALTER TABLE `safe_area` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_attachment`
--

DROP TABLE IF EXISTS `safe_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_attachment` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '类别',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '文件名称',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `mimetype` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '文件 sha1编码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='附件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_attachment`
--

LOCK TABLES `safe_attachment` WRITE;
/*!40000 ALTER TABLE `safe_attachment` DISABLE KEYS */;
INSERT INTO `safe_attachment` VALUES (125,'',2,0,'/uploads/20211225/6b22224409a4e9103b97259371cfcf2c.mp4','','','mp4',0,'vokoscreen-2021-11-15_09-42-37.mp4',298713,'video/mp4','',1640418563,1640418563,1640418563,'local','5850954a099cade1123993715af13098213faec8');
/*!40000 ALTER TABLE `safe_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_auth_group`
--

DROP TABLE IF EXISTS `safe_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父组别',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_auth_group`
--

LOCK TABLES `safe_auth_group` WRITE;
/*!40000 ALTER TABLE `safe_auth_group` DISABLE KEYS */;
INSERT INTO `safe_auth_group` VALUES (1,0,'Admin group','*',1491635035,1491635035,'normal'),(6,1,'企业管理员','1,7,8,9,10,11,13,14,15,16,17,23,24,25,26,27,28,29,30,31,32,33,34,40,41,42,43,44,45,46,47,48,49,50,67,68,69,70,71,72,73,74,75,76,77,78,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,112,113,114,115,116,117,118,119,120,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,163,164,165,166,167,168,169,170,178,179,180,181,182,183,184,185,186,198,199,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,376,377,378,379,380,381,382,390,391,392,393,394,395,396,398,399,400,401,402,403,404,405,406,407,408,409,410,412,413,414,415,416,417,418,420,421,422,423,424,425,427,428,429,443,444,445,446,447,448,449,450,451,452,453,454,455,456,457,458,459,460,461,462,463,464,465,466,467,468,469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,500,2,5,66,85,111,128,197,375,374,397,411,419',1634451484,1640397368,'normal'),(7,6,'安全员','1,7,8,9,10,11,13,14,15,16,17,23,24,25,26,27,28,29,30,31,32,33,34,40,41,42,43,44,45,46,47,48,49,50,67,68,69,70,71,72,73,74,75,76,77,78,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,112,113,114,115,116,117,118,119,120,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,163,164,165,166,167,168,169,170,178,179,180,181,182,183,184,185,186,198,199,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,376,377,378,379,380,381,382,390,391,392,393,394,395,396,398,399,400,401,402,403,404,405,406,407,408,409,410,411,412,413,414,415,416,417,418,419,420,421,422,423,424,425,427,428,429,443,444,445,446,447,448,449,450,451,452,453,454,455,456,457,458,459,460,461,462,463,464,465,466,467,468,469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,2,5,66,85,111,128,197,375,374,397',1635595553,1640397368,'normal'),(8,6,'部门负责人','1,7,8,9,10,11,13,14,15,16,17,23,24,25,26,27,28,29,30,31,32,33,34,40,41,42,43,44,45,46,47,48,49,50,67,68,69,70,71,72,73,74,75,76,77,78,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,112,113,114,115,116,117,118,119,120,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,163,164,165,166,167,168,169,170,178,179,180,181,182,183,184,185,186,198,199,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,376,377,378,379,380,381,382,390,391,392,393,394,395,396,398,399,400,401,402,403,404,405,406,407,408,409,410,411,412,413,414,415,416,417,418,419,420,421,422,423,424,425,427,428,429,443,444,445,446,447,448,449,450,451,452,453,454,455,456,457,458,459,460,461,462,463,464,465,466,467,468,469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,2,5,66,85,111,128,197,375,374,397',1640340960,1640397368,'normal');
/*!40000 ALTER TABLE `safe_auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_auth_group_access`
--

DROP TABLE IF EXISTS `safe_auth_group_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '会员ID',
  `group_id` int(10) unsigned NOT NULL COMMENT '级别ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限分组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_auth_group_access`
--

LOCK TABLES `safe_auth_group_access` WRITE;
/*!40000 ALTER TABLE `safe_auth_group_access` DISABLE KEYS */;
INSERT INTO `safe_auth_group_access` VALUES (1,1),(2,6),(3,6),(4,7),(5,7);
/*!40000 ALTER TABLE `safe_auth_group_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_auth_rule`
--

DROP TABLE IF EXISTS `safe_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图标',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则URL',
  `condition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '条件',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `menutype` enum('addtabs','blank','dialog','ajax') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '菜单类型',
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '扩展属性',
  `py` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '拼音首字母',
  `pinyin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '拼音',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `pid` (`pid`),
  KEY `weigh` (`weigh`)
) ENGINE=InnoDB AUTO_INCREMENT=501 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='节点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_auth_rule`
--

LOCK TABLES `safe_auth_rule` WRITE;
/*!40000 ALTER TABLE `safe_auth_rule` DISABLE KEYS */;
INSERT INTO `safe_auth_rule` VALUES (1,'file',0,'dashboard','Dashboard','fa fa-dashboard','','','Dashboard tips',1,NULL,'','kzt','kongzhitai',1491635035,1491635035,143,'normal'),(2,'file',0,'general','General','fa fa-cogs','','','',1,NULL,'','cggl','changguiguanli',1491635035,1491635035,137,'normal'),(3,'file',0,'category','Category','fa fa-leaf','','','Category tips',1,NULL,'','flgl','fenleiguanli',1491635035,1491635035,119,'normal'),(4,'file',0,'addon','Addon','fa fa-rocket','','','Addon tips',1,NULL,'','cjgl','chajianguanli',1491635035,1491635035,0,'normal'),(5,'file',0,'auth','Auth','fa fa-group','','','',1,NULL,'','qxgl','quanxianguanli',1491635035,1491635035,99,'normal'),(6,'file',2,'general/config','Config','fa fa-cog','','','Config tips',1,NULL,'','xtpz','xitongpeizhi',1491635035,1491635035,60,'normal'),(7,'file',2,'general/attachment','Attachment','fa fa-file-image-o','','','Attachment tips',1,NULL,'','fjgl','fujianguanli',1491635035,1491635035,53,'normal'),(8,'file',2,'general/profile','Profile','fa fa-user','','','',1,NULL,'','grzl','gerenziliao',1491635035,1491635035,34,'normal'),(9,'file',5,'auth/admin','Admin','fa fa-user','','','Admin tips',1,NULL,'','glygl','guanliyuanguanli',1491635035,1491635035,118,'normal'),(10,'file',5,'auth/adminlog','Admin log','fa fa-list-alt','','','Admin log tips',1,NULL,'','glyrz','guanliyuanrizhi',1491635035,1491635035,113,'normal'),(11,'file',5,'auth/group','Group','fa fa-group','','','Group tips',1,NULL,'','jsz','juesezu',1491635035,1491635035,109,'normal'),(12,'file',5,'auth/rule','Rule','fa fa-bars','','','Rule tips',1,NULL,'','cdgz','caidanguize',1491635035,1491635035,104,'normal'),(13,'file',1,'dashboard/index','View','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,136,'normal'),(14,'file',1,'dashboard/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,135,'normal'),(15,'file',1,'dashboard/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,133,'normal'),(16,'file',1,'dashboard/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,134,'normal'),(17,'file',1,'dashboard/multi','Multi','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,132,'normal'),(18,'file',6,'general/config/index','View','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,52,'normal'),(19,'file',6,'general/config/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,51,'normal'),(20,'file',6,'general/config/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,50,'normal'),(21,'file',6,'general/config/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,49,'normal'),(22,'file',6,'general/config/multi','Multi','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,48,'normal'),(23,'file',7,'general/attachment/index','View','fa fa-circle-o','','','Attachment tips',0,NULL,'','','',1491635035,1491635035,59,'normal'),(24,'file',7,'general/attachment/select','Select attachment','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,58,'normal'),(25,'file',7,'general/attachment/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,57,'normal'),(26,'file',7,'general/attachment/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,56,'normal'),(27,'file',7,'general/attachment/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,55,'normal'),(28,'file',7,'general/attachment/multi','Multi','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,54,'normal'),(29,'file',8,'general/profile/index','View','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,33,'normal'),(30,'file',8,'general/profile/update','Update profile','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,32,'normal'),(31,'file',8,'general/profile/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,31,'normal'),(32,'file',8,'general/profile/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,30,'normal'),(33,'file',8,'general/profile/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,29,'normal'),(34,'file',8,'general/profile/multi','Multi','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,28,'normal'),(35,'file',3,'category/index','View','fa fa-circle-o','','','Category tips',0,NULL,'','','',1491635035,1491635035,142,'normal'),(36,'file',3,'category/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,141,'normal'),(37,'file',3,'category/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,140,'normal'),(38,'file',3,'category/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,139,'normal'),(39,'file',3,'category/multi','Multi','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,138,'normal'),(40,'file',9,'auth/admin/index','View','fa fa-circle-o','','','Admin tips',0,NULL,'','','',1491635035,1491635035,117,'normal'),(41,'file',9,'auth/admin/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,116,'normal'),(42,'file',9,'auth/admin/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,115,'normal'),(43,'file',9,'auth/admin/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,114,'normal'),(44,'file',10,'auth/adminlog/index','View','fa fa-circle-o','','','Admin log tips',0,NULL,'','','',1491635035,1491635035,112,'normal'),(45,'file',10,'auth/adminlog/detail','Detail','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,111,'normal'),(46,'file',10,'auth/adminlog/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,110,'normal'),(47,'file',11,'auth/group/index','View','fa fa-circle-o','','','Group tips',0,NULL,'','','',1491635035,1491635035,108,'normal'),(48,'file',11,'auth/group/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,107,'normal'),(49,'file',11,'auth/group/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,106,'normal'),(50,'file',11,'auth/group/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,105,'normal'),(51,'file',12,'auth/rule/index','View','fa fa-circle-o','','','Rule tips',0,NULL,'','','',1491635035,1491635035,103,'normal'),(52,'file',12,'auth/rule/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,102,'normal'),(53,'file',12,'auth/rule/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,101,'normal'),(54,'file',12,'auth/rule/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,100,'normal'),(55,'file',4,'addon/index','View','fa fa-circle-o','','','Addon tips',0,NULL,'','','',1491635035,1491635035,0,'normal'),(56,'file',4,'addon/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(57,'file',4,'addon/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(58,'file',4,'addon/del','Delete','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(59,'file',4,'addon/downloaded','Local addon','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(60,'file',4,'addon/state','Update state','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(63,'file',4,'addon/config','Setting','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(64,'file',4,'addon/refresh','Refresh','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(65,'file',4,'addon/multi','Multi','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(66,'file',0,'user','人员管理','fa fa-user-circle','','','',1,'addtabs','','rygl','renyuanguanli',1491635035,1640167154,5,'normal'),(67,'file',66,'user/user','人员资料','fa fa-user','','','所有学员资料，可批量导入学员资料，批量修改学员所在部门和所在组别',1,'addtabs','','ryzl','renyuanziliao',1491635035,1640167191,2,'normal'),(68,'file',67,'user/user/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1491635035,1634804091,0,'normal'),(69,'file',67,'user/user/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1491635035,1634804091,0,'normal'),(70,'file',67,'user/user/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1491635035,1634804091,0,'normal'),(71,'file',67,'user/user/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1491635035,1634804091,0,'normal'),(72,'file',67,'user/user/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1491635035,1634804091,0,'normal'),(73,'file',66,'user/group','学员分组','fa fa-users','','','根据学员的具体情况，制定不同的分组类型，便于统一安排培训',1,'addtabs','','xyfz','xueyuanfenzu',1491635035,1635934606,3,'normal'),(74,'file',73,'user/group/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(75,'file',73,'user/group/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(76,'file',73,'user/group/index','View','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(77,'file',73,'user/group/del','Del','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(78,'file',73,'user/group/multi','Multi','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(79,'file',66,'user/rule','User rule','fa fa-circle-o','','','',1,NULL,'','hygz','huiyuanguize',1491635035,1491635035,0,'normal'),(80,'file',79,'user/rule/index','View','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(81,'file',79,'user/rule/del','Del','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(82,'file',79,'user/rule/add','Add','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(83,'file',79,'user/rule/edit','Edit','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(84,'file',79,'user/rule/multi','Multi','fa fa-circle-o','','','',0,NULL,'','','',1491635035,1491635035,0,'normal'),(85,'file',0,'training','在线培训','fa fa-suitcase','','','',1,'addtabs','','zxpx','zaixianpeixun',1634268947,1637380279,3,'normal'),(86,'file',85,'training/category','培训分类','fa fa-list','','','为所有培训制定类型和为课程制定分类',1,'addtabs','','pxfl','peixunfenlei',1634268947,1635934745,5,'normal'),(87,'file',86,'training/category/import','导入','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1634268947,1634268947,0,'normal'),(88,'file',86,'training/category/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268947,1634268947,0,'normal'),(89,'file',86,'training/category/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268947,1634268947,0,'normal'),(90,'file',86,'training/category/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634268947,1634268947,0,'normal'),(91,'file',86,'training/category/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268947,1634268947,0,'normal'),(92,'file',86,'training/category/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268947,1634268947,0,'normal'),(93,'file',85,'training/course','课程管理','fa fa-youtube-play','','','制定具体课程及课程简介',1,'addtabs','','kcgl','kechengguanli',1634268947,1635934795,4,'normal'),(94,'file',93,'training/course/import','导入','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1634268947,1634268947,0,'normal'),(95,'file',93,'training/course/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268947,1634268947,0,'normal'),(96,'file',93,'training/course/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268947,1634268947,0,'normal'),(97,'file',93,'training/course/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634268947,1634268947,0,'normal'),(98,'file',93,'training/course/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268947,1634268947,0,'normal'),(99,'file',93,'training/course/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268947,1634268947,0,'normal'),(100,'file',85,'training/main','培训管理','fa fa-book','','','将不同组合的课程打包制作成为具体的培训计划，并可为培训计划指定学员对象',1,'addtabs','','pxgl','peixunguanli',1634268947,1635934850,3,'normal'),(101,'file',100,'training/main/import','导入','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1634268947,1634268947,0,'normal'),(102,'file',100,'training/main/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268947,1634268947,0,'normal'),(103,'file',100,'training/main/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268947,1634268947,0,'normal'),(104,'file',100,'training/main/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634268947,1634268947,0,'normal'),(105,'file',100,'training/main/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268947,1634268947,0,'normal'),(106,'file',100,'training/main/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268947,1634268947,0,'normal'),(107,'file',85,'training/index','培训统计','fa fa-bar-chart','','','培训情况统计',1,'addtabs','','pxtj','peixuntongji',1634268947,1635934870,1,'normal'),(108,'file',107,'training/index/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268947,1634268947,0,'normal'),(109,'file',85,'training/config','站点配置','fa fa-gears','','','',1,NULL,'','zdpz','zhandianpeizhi',1634268947,1634268947,0,'normal'),(110,'file',109,'training/config/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268947,1634268947,0,'normal'),(111,'file',0,'kaoshi','考试系统','fa fa-file-text-o','','','',1,'addtabs','','ksxt','kaoshixitong',1634268973,1637380264,2,'normal'),(112,'file',111,'kaoshi/subject','科目管理','fa fa-th-large','','','为考试制定不同的科目',1,'addtabs','','kmgl','kemuguanli',1634268973,1635934908,0,'normal'),(113,'file',112,'kaoshi/subject/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268973,1634268973,0,'normal'),(114,'file',112,'kaoshi/subject/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1634268973,1634268973,0,'normal'),(115,'file',112,'kaoshi/subject/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268973,1634268973,0,'normal'),(116,'file',112,'kaoshi/subject/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634268973,1634268973,0,'normal'),(117,'file',112,'kaoshi/subject/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268973,1634268973,0,'normal'),(118,'file',112,'kaoshi/subject/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1634268973,1634268973,0,'normal'),(119,'file',112,'kaoshi/subject/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1634268973,1634268973,0,'normal'),(120,'file',112,'kaoshi/subject/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268973,1634268973,0,'normal'),(121,'file',111,'kaoshi/student','学生管理','fa fa-mortar-board','','','',1,'addtabs','','xsgl','xueshengguanli',1634268973,1636545678,0,'hidden'),(122,'file',121,'kaoshi/student/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268973,1634268973,0,'normal'),(123,'file',121,'kaoshi/student/import','导入','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1634268973,1634268973,0,'normal'),(124,'file',121,'kaoshi/student/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268973,1634268973,0,'normal'),(125,'file',121,'kaoshi/student/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634268973,1634268973,0,'normal'),(126,'file',121,'kaoshi/student/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268973,1634268973,0,'normal'),(127,'file',121,'kaoshi/student/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268973,1634268973,0,'normal'),(128,'file',111,'kaoshi/examination','考试管理','fa fa-list','','','',1,'addtabs','','ksgl','kaoshiguanli',1634268973,1637380256,0,'normal'),(129,'file',128,'kaoshi/examination/questions','试题管理','fa fa-file','','','在不同的考试科目下录入考题，考题类型有单选，多选，判断，支持图片',1,'addtabs','','stgl','shitiguanli',1634268973,1635934962,0,'normal'),(130,'file',129,'kaoshi/examination/questions/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268973,1636889910,0,'normal'),(131,'file',129,'kaoshi/examination/questions/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1634268973,1636889910,0,'normal'),(132,'file',129,'kaoshi/examination/questions/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268973,1636889910,0,'normal'),(133,'file',129,'kaoshi/examination/questions/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634268973,1636889910,0,'normal'),(134,'file',129,'kaoshi/examination/questions/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268973,1636889910,0,'normal'),(135,'file',129,'kaoshi/examination/questions/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1634268973,1636889910,0,'normal'),(136,'file',129,'kaoshi/examination/questions/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1634268973,1636889910,0,'normal'),(137,'file',129,'kaoshi/examination/questions/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268973,1636889910,0,'normal'),(138,'file',129,'kaoshi/examination/questions/import','导入','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1634268973,1636889910,0,'normal'),(139,'file',128,'kaoshi/examination/exams','考卷管理','fa fa-paste','','','在不同的科目下，将多道考题组合成为一份考卷，组合方式支持指定考题和随机抽题。考卷类型有练习和正式考试两种，',1,'addtabs','','kjgl','kaojuanguanli',1634268973,1635935099,0,'normal'),(140,'file',139,'kaoshi/examination/exams/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268973,1634268973,0,'normal'),(141,'file',139,'kaoshi/examination/exams/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1634268973,1634268973,0,'normal'),(142,'file',139,'kaoshi/examination/exams/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268973,1634268973,0,'normal'),(143,'file',139,'kaoshi/examination/exams/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634268973,1634268973,0,'normal'),(144,'file',139,'kaoshi/examination/exams/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268973,1634268973,0,'normal'),(145,'file',139,'kaoshi/examination/exams/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1634268973,1634268973,0,'normal'),(146,'file',139,'kaoshi/examination/exams/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1634268973,1634268973,0,'normal'),(147,'file',139,'kaoshi/examination/exams/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268973,1634268973,0,'normal'),(148,'file',128,'kaoshi/examination/plan','计划安排','fa fa-hourglass-start','','','',1,NULL,'','jhap','jihuaanpai',1634268973,1634268973,0,'normal'),(149,'file',148,'kaoshi/examination/plan/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268973,1634268973,0,'normal'),(150,'file',148,'kaoshi/examination/plan/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1634268973,1634268973,0,'normal'),(151,'file',148,'kaoshi/examination/plan/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268973,1634268973,0,'normal'),(152,'file',148,'kaoshi/examination/plan/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634268973,1634268973,0,'normal'),(153,'file',148,'kaoshi/examination/plan/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268973,1634268973,0,'normal'),(154,'file',148,'kaoshi/examination/plan/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1634268973,1634268973,0,'normal'),(155,'file',148,'kaoshi/examination/plan/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1634268973,1634268973,0,'normal'),(156,'file',148,'kaoshi/examination/plan/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268973,1634268973,0,'normal'),(157,'file',128,'kaoshi/examination/user_plan','学生安排','fa fa-group','','','',1,'addtabs','','xsap','xueshenganpai',1634268973,1636545694,0,'hidden'),(158,'file',157,'kaoshi/examination/user_plan/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634268973,1634268973,0,'normal'),(159,'file',157,'kaoshi/examination/user_plan/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634268973,1634268973,0,'normal'),(160,'file',157,'kaoshi/examination/user_plan/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634268973,1634268973,0,'normal'),(161,'file',157,'kaoshi/examination/user_plan/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634268973,1634268973,0,'normal'),(162,'file',157,'kaoshi/examination/user_plan/edit','修改','fa fa-circle-o','','','',0,NULL,'','xg','xiugai',1634268973,1634268973,0,'normal'),(163,'file',111,'kaoshi/examination/user_exams','统计管理','fa fa-line-chart','','','',1,NULL,'','tjgl','tongjiguanli',1634268973,1634268973,0,'normal'),(164,'file',163,'kaoshi/examination/plan/study','学习统计','fa fa-book','','','',1,NULL,'','xxtj','xuexitongji',1634268973,1634268973,0,'normal'),(165,'file',163,'kaoshi/examination/plan/exam','考试统计','fa fa-laptop','','','',1,NULL,'','kstj','kaoshitongji',1634268973,1634268973,0,'normal'),(166,'file',163,'kaoshi/examination/user_exams/studyrank','学习排行榜','fa fa-line-chart','','','',1,NULL,'','xxphb','xuexipaihangbang',1634268973,1634268973,0,'normal'),(167,'file',163,'kaoshi/examination/user_exams/examrank','考试排行榜','fa fa-graduation-cap','','','',1,NULL,'','ksphb','kaoshipaihangbang',1634268973,1634268973,0,'normal'),(168,'file',163,'kaoshi/examination/user_exams/users','参与学生','fa fa-users','','','',0,NULL,'','cyxs','canyuxuesheng',1634268973,1634268973,0,'normal'),(169,'file',163,'kaoshi/examination/user_exams/index','答题记录','fa fa-list','','','',0,NULL,'','dtjl','datijilu',1634268973,1634268973,0,'normal'),(170,'file',163,'kaoshi/examination/user_exams/answercard','答题卡','fa fa-file-text-o','','','',0,NULL,'','dtk','datika',1634268973,1634268973,0,'normal'),(171,'file',0,'command','在线命令管理','fa fa-terminal','','','',1,NULL,'','zxmlgl','zaixianminglingguanli',1634369574,1634369574,0,'normal'),(172,'file',171,'command/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634369574,1634369574,0,'normal'),(173,'file',171,'command/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634369574,1634369574,0,'normal'),(174,'file',171,'command/detail','详情','fa fa-circle-o','','','',0,NULL,'','xq','xiangqing',1634369574,1634369574,0,'normal'),(175,'file',171,'command/execute','运行','fa fa-circle-o','','','',0,NULL,'','yx','yunxing',1634369574,1634369574,0,'normal'),(176,'file',171,'command/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634369574,1634369574,0,'normal'),(177,'file',171,'command/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634369574,1634369574,0,'normal'),(178,'file',66,'user/department','部门管理','fa fa-circle-o','','','对企业部门资料进行设置',1,'addtabs','','bmgl','bumenguanli',1634369682,1634458033,4,'normal'),(179,'file',178,'user/department/import','导入','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1634369682,1634369682,0,'normal'),(180,'file',178,'user/department/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1634369682,1634369682,0,'normal'),(181,'file',178,'user/department/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1634369682,1634369682,0,'normal'),(182,'file',178,'user/department/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1634369682,1634369682,0,'normal'),(183,'file',178,'user/department/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1634369682,1634369682,0,'normal'),(184,'file',178,'user/department/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1634369682,1634369682,0,'normal'),(185,'file',67,'user/user/import','导入','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1634369682,1634369682,0,'normal'),(186,'file',67,'user/user/batch','批量设置','fa fa-circle-o','','','',0,NULL,'','plsz','piliangshezhi',1634369682,1634369682,0,'normal'),(197,'file',66,'user/plan','学员安排','fa fa-user','','','为学员安排培训和考试计划',1,'addtabs','','xyap','xueyuananpai',1635561300,1636545633,0,'normal'),(198,'file',197,'user/plan/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1635561300,1635563217,0,'normal'),(199,'file',197,'user/plan/selectmain','选择培训','fa fa-circle-o','','','',0,NULL,'','xzpx','xuanzepeixun',1635561300,1635563217,0,'normal'),(200,'file',197,'user/plan/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1635561300,1635563217,0,'normal'),(201,'file',197,'user/plan/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1635561300,1635563217,0,'normal'),(202,'file',197,'user/plan/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1635561300,1635563217,0,'normal'),(203,'file',197,'user/plan/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1635561300,1635563217,0,'normal'),(204,'file',197,'user/plan/clearmain','全清培训','fa fa-circle-o','','','',0,NULL,'','qqpx','quanqingpeixun',1635563217,1635563217,0,'normal'),(205,'file',197,'user/plan/selectkaoshi','选择考试','fa fa-circle-o','','','',0,NULL,'','xzks','xuanzekaoshi',1635563217,1635563217,0,'normal'),(206,'file',197,'user/plan/clearkaoshi','全清考试','fa fa-circle-o','','','',0,NULL,'','qqks','quanqingkaoshi',1635563217,1635563217,0,'normal'),(207,'file',0,'setting','系统设置','fa fa-list','','','',1,'addtabs','','xtsz','xitongshezhi',1636866402,1640164675,0,'normal'),(208,'file',207,'setting/company','公司信息','fa fa-circle-o','','','设置公司信息',1,'addtabs','','gsxx','gongsixinxi',1636866403,1636867220,0,'normal'),(209,'file',208,'setting/company/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1636866403,1636866403,0,'normal'),(210,'file',208,'setting/company/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1636866403,1636866403,0,'normal'),(211,'file',208,'setting/company/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1636866403,1636866403,0,'normal'),(212,'file',208,'setting/company/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1636866403,1636866403,0,'normal'),(213,'file',208,'setting/company/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1636866403,1636866403,0,'normal'),(214,'file',208,'setting/company/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1636866403,1636866403,0,'normal'),(215,'file',0,'materials','学习资料','fa fa-list','','','',1,'addtabs','','xxzl','xuexiziliao',1637379821,1637380298,4,'normal'),(216,'file',215,'materials/category','资料分类','fa fa-circle-o','','','建立学习资料分类',1,'addtabs','','zlfl','ziliaofenlei',1637379821,1637380345,0,'normal'),(217,'file',216,'materials/category/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1637379821,1637379821,0,'normal'),(218,'file',216,'materials/category/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1637379821,1637379821,0,'normal'),(219,'file',216,'materials/category/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1637379821,1637379821,0,'normal'),(220,'file',216,'materials/category/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1637379821,1637379821,0,'normal'),(221,'file',216,'materials/category/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1637379821,1637379821,0,'normal'),(222,'file',216,'materials/category/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1637379821,1637379821,0,'normal'),(223,'file',215,'materials/info','学习资料','fa fa-circle-o','','','管理学习资料',1,'addtabs','','xxzl','xuexiziliao',1637379936,1637380359,0,'normal'),(224,'file',223,'materials/info/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1637379936,1637379936,0,'normal'),(225,'file',223,'materials/info/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1637379936,1637379936,0,'normal'),(226,'file',223,'materials/info/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1637379936,1637379936,0,'normal'),(227,'file',223,'materials/info/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1637379936,1637379936,0,'normal'),(228,'file',223,'materials/info/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1637379936,1637379936,0,'normal'),(229,'file',223,'materials/info/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1637379936,1637379936,0,'normal'),(230,'file',223,'materials/info/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1637379936,1637379936,0,'normal'),(231,'file',223,'materials/info/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1637379936,1637379936,0,'normal'),(232,'file',223,'materials/info/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1637379936,1637379936,0,'normal'),(233,'file',0,'message','学习通告','fa fa-list','','','',1,'addtabs','','xxtg','xuexitonggao',1638072875,1638073005,4,'normal'),(234,'file',233,'message/category','通告分类','fa fa-circle-o','','','设置通知的分类',1,'addtabs','','tgfl','tonggaofenlei',1638072875,1638073282,0,'normal'),(235,'file',234,'message/category/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1638072875,1638072875,0,'normal'),(236,'file',234,'message/category/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638072875,1638072875,0,'normal'),(237,'file',234,'message/category/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638072875,1638072875,0,'normal'),(238,'file',234,'message/category/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638072875,1638072875,0,'normal'),(239,'file',234,'message/category/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638072875,1638072875,0,'normal'),(240,'file',234,'message/category/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638072875,1638072875,0,'normal'),(241,'file',233,'message/info','通告管理','fa fa-circle-o','','','通知内容管理',1,'addtabs','','tggl','tonggaoguanli',1638072875,1638072955,0,'normal'),(242,'file',241,'message/info/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1638072875,1638072875,0,'normal'),(243,'file',241,'message/info/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638072875,1638072875,0,'normal'),(244,'file',241,'message/info/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638072875,1638072875,0,'normal'),(245,'file',241,'message/info/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638072875,1638072875,0,'normal'),(246,'file',241,'message/info/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638072875,1638072875,0,'normal'),(247,'file',241,'message/info/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638072875,1638072875,0,'normal'),(248,'file',241,'message/info/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638072875,1638072875,0,'normal'),(249,'file',241,'message/info/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638072875,1638072875,0,'normal'),(250,'file',241,'message/info/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638072875,1638072875,0,'normal'),(251,'file',0,'third','第三方登录管理','fa fa-users','','','',1,NULL,'','dsfdlgl','disanfangdengluguanli',1638514590,1638514590,0,'normal'),(252,'file',251,'third/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638514590,1638514590,0,'normal'),(253,'file',251,'third/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638514590,1638514590,0,'normal'),(254,'file',0,'suisunwechat','公众号管理','fa fa-list','','','',1,NULL,'','gzhgl','gongzhonghaoguanli',1638523680,1638523680,0,'normal'),(255,'file',254,'suisunwechat/source','关键词管理','fa fa-gears','','','',1,NULL,'','gjcgl','guanjianciguanli',1638523680,1638523680,0,'normal'),(256,'file',255,'suisunwechat/source/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(257,'file',255,'suisunwechat/source/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(258,'file',255,'suisunwechat/source/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(259,'file',255,'suisunwechat/source/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(260,'file',255,'suisunwechat/source/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(261,'file',255,'suisunwechat/source/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638523680,1638523680,0,'normal'),(262,'file',255,'suisunwechat/source/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638523680,1638523680,0,'normal'),(263,'file',255,'suisunwechat/source/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638523680,1638523680,0,'normal'),(264,'file',255,'suisunwechat/source/select','选择链接','fa fa-circle-o','','','',0,NULL,'','xzlj','xuanzelianjie',1638523680,1638523680,0,'normal'),(265,'file',254,'suisunwechat/material','素材管理','fa fa-gears','','','',1,NULL,'','scgl','sucaiguanli',1638523680,1638523680,0,'normal'),(266,'file',265,'suisunwechat/material/wechat_image_text','图文消息','fa fa-gears','','','',1,NULL,'','twxx','tuwenxiaoxi',1638523680,1638523680,0,'normal'),(267,'file',266,'suisunwechat/material/wechat_image_text/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(268,'file',266,'suisunwechat/material/wechat_image_text/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(269,'file',266,'suisunwechat/material/wechat_image_text/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(270,'file',266,'suisunwechat/material/wechat_image_text/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(271,'file',266,'suisunwechat/material/wechat_image_text/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(272,'file',266,'suisunwechat/material/wechat_image_text/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638523680,1638523680,0,'normal'),(273,'file',266,'suisunwechat/material/wechat_image_text/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638523680,1638523680,0,'normal'),(274,'file',266,'suisunwechat/material/wechat_image_text/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638523680,1638523680,0,'normal'),(275,'file',265,'suisunwechat/material/wechat_image','图片消息','fa fa-gears','','','',1,NULL,'','tpxx','tupianxiaoxi',1638523680,1638523680,0,'normal'),(276,'file',275,'suisunwechat/material/wechat_image/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(277,'file',275,'suisunwechat/material/wechat_image/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(278,'file',275,'suisunwechat/material/wechat_image/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(279,'file',275,'suisunwechat/material/wechat_image/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(280,'file',275,'suisunwechat/material/wechat_image/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(281,'file',275,'suisunwechat/material/wechat_image/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638523680,1638523680,0,'normal'),(282,'file',275,'suisunwechat/material/wechat_image/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638523680,1638523680,0,'normal'),(283,'file',275,'suisunwechat/material/wechat_image/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638523680,1638523680,0,'normal'),(284,'file',265,'suisunwechat/material/wechat_video','视频消息','fa fa-gears','','','',1,NULL,'','spxx','shipinxiaoxi',1638523680,1638523680,0,'normal'),(285,'file',284,'suisunwechat/material/wechat_video/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(286,'file',284,'suisunwechat/material/wechat_video/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(287,'file',284,'suisunwechat/material/wechat_video/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(288,'file',284,'suisunwechat/material/wechat_video/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(289,'file',284,'suisunwechat/material/wechat_video/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(290,'file',284,'suisunwechat/material/wechat_video/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638523680,1638523680,0,'normal'),(291,'file',284,'suisunwechat/material/wechat_video/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638523680,1638523680,0,'normal'),(292,'file',284,'suisunwechat/material/wechat_video/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638523680,1638523680,0,'normal'),(293,'file',265,'suisunwechat/material/wechat_voice','音频消息','fa fa-gears','','','',1,NULL,'','ypxx','yinpinxiaoxi',1638523680,1638523680,0,'normal'),(294,'file',293,'suisunwechat/material/wechat_voice/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(295,'file',293,'suisunwechat/material/wechat_voice/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(296,'file',293,'suisunwechat/material/wechat_voice/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(297,'file',293,'suisunwechat/material/wechat_voice/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(298,'file',293,'suisunwechat/material/wechat_voice/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(299,'file',293,'suisunwechat/material/wechat_voice/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638523680,1638523680,0,'normal'),(300,'file',293,'suisunwechat/material/wechat_voice/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638523680,1638523680,0,'normal'),(301,'file',293,'suisunwechat/material/wechat_voice/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638523680,1638523680,0,'normal'),(302,'file',265,'suisunwechat/material/wechat_article','图文文章','fa fa-gears','','','',1,NULL,'','twwz','tuwenwenzhang',1638523680,1638523680,0,'normal'),(303,'file',302,'suisunwechat/material/wechat_article/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(304,'file',302,'suisunwechat/material/wechat_article/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(305,'file',302,'suisunwechat/material/wechat_article/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(306,'file',302,'suisunwechat/material/wechat_article/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(307,'file',302,'suisunwechat/material/wechat_article/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(308,'file',302,'suisunwechat/material/wechat_article/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638523680,1638523680,0,'normal'),(309,'file',302,'suisunwechat/material/wechat_article/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638523680,1638523680,0,'normal'),(310,'file',302,'suisunwechat/material/wechat_article/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638523680,1638523680,0,'normal'),(311,'file',254,'suisunwechat/wechat','菜单管理','fa fa-gears','','','',1,NULL,'','cdgl','caidanguanli',1638523680,1638523680,0,'normal'),(312,'file',311,'suisunwechat/wechat/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(313,'file',311,'suisunwechat/wechat/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(314,'file',311,'suisunwechat/wechat/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(315,'file',311,'suisunwechat/wechat/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(316,'file',311,'suisunwechat/wechat/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(317,'file',311,'suisunwechat/wechat/getmenu','Getmenu','fa fa-circle-o','','','',0,NULL,'','G','Getmenu',1638523680,1638523680,0,'normal'),(318,'file',311,'suisunwechat/wechat/fans_user','Fans_user','fa fa-circle-o','','','',0,NULL,'','Fu','Fansuser',1638523680,1638523680,0,'normal'),(319,'file',311,'suisunwechat/wechat/fans','Fans','fa fa-circle-o','','','',0,NULL,'','F','Fans',1638523680,1638523680,0,'normal'),(320,'file',311,'suisunwechat/wechat/menu','Menu','fa fa-circle-o','','','',0,NULL,'','cd','caidan',1638523680,1638523680,0,'normal'),(321,'file',254,'suisunwechat/wechat_auto_reply','自动回复','fa fa-gears','','','',1,NULL,'','zdhf','zidonghuifu',1638523680,1638523680,0,'normal'),(322,'file',321,'suisunwechat/wechat_auto_reply/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(323,'file',321,'suisunwechat/wechat_auto_reply/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(324,'file',321,'suisunwechat/wechat_auto_reply/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(325,'file',321,'suisunwechat/wechat_auto_reply/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(326,'file',321,'suisunwechat/wechat_auto_reply/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(327,'file',254,'suisunwechat/customer','客服中心','fa fa-gears','','','',1,NULL,'','kfzx','kefuzhongxin',1638523680,1638523680,0,'normal'),(328,'file',327,'suisunwechat/service_admin','客服列表','fa fa-gears','','','',1,NULL,'','kflb','kefuliebiao',1638523680,1638523680,0,'normal'),(329,'file',328,'suisunwechat/service_admin/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(330,'file',328,'suisunwechat/service_admin/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(331,'file',328,'suisunwechat/service_admin/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(332,'file',328,'suisunwechat/service_admin/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(333,'file',328,'suisunwechat/service_admin/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(334,'file',327,'suisunwechat/service_list','信息列表','fa fa-gears','','','',1,NULL,'','xxlb','xinxiliebiao',1638523680,1638523680,0,'normal'),(335,'file',334,'suisunwechat/service_list/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(336,'file',334,'suisunwechat/service_list/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(337,'file',334,'suisunwechat/service_list/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(338,'file',334,'suisunwechat/service_list/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(339,'file',334,'suisunwechat/service_list/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(340,'file',254,'suisunwechat/wechat_fan','粉丝数据','fa fa-gears','','','',1,NULL,'','fssj','fensishuju',1638523680,1638523680,0,'normal'),(341,'file',340,'suisunwechat/wechat_fans','粉丝管理','fa fa-gears','','','',1,NULL,'','fsgl','fensiguanli',1638523680,1638523680,0,'normal'),(342,'file',341,'suisunwechat/wechat_fans/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(343,'file',341,'suisunwechat/wechat_fans/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(344,'file',341,'suisunwechat/wechat_fans/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(345,'file',341,'suisunwechat/wechat_fans/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(346,'file',341,'suisunwechat/wechat_fans/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(347,'file',340,'suisunwechat/wechat_fans_tag','标签管理','fa fa-gears','','','',1,NULL,'','bqgl','biaoqianguanli',1638523680,1638523680,0,'normal'),(348,'file',347,'suisunwechat/wechat_fans_tag/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(349,'file',347,'suisunwechat/wechat_fans_tag/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(350,'file',347,'suisunwechat/wechat_fans_tag/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(351,'file',347,'suisunwechat/wechat_fans_tag/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(352,'file',347,'suisunwechat/wechat_fans_tag/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(353,'file',347,'suisunwechat/wechat_fans_tag/sync','同步','fa fa-circle-o','','','',0,NULL,'','tb','tongbu',1638523680,1638523680,0,'normal'),(354,'file',347,'suisunwechat/wechat_fans_tag/changetag','添加用户标签','fa fa-circle-o','','','',0,NULL,'','tjyhbq','tianjiayonghubiaoqian',1638523680,1638523680,0,'normal'),(355,'file',347,'suisunwechat/wechat_fans_tag/removetag','添加用户标签','fa fa-circle-o','','','',0,NULL,'','tjyhbq','tianjiayonghubiaoqian',1638523680,1638523680,0,'normal'),(356,'file',340,'suisunwechat/wechat_usersummary','用户分析','fa fa-gears','','','',1,NULL,'','yhfx','yonghufenxi',1638523680,1638523680,0,'normal'),(357,'file',356,'suisunwechat/wechat_usersummary/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(358,'file',356,'suisunwechat/wechat_usersummary/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(359,'file',356,'suisunwechat/wechat_usersummary/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(360,'file',356,'suisunwechat/wechat_usersummary/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(361,'file',356,'suisunwechat/wechat_usersummary/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(362,'file',356,'suisunwechat/wechat_usersummary/init_summary','数据采集','fa fa-circle-o','','','',0,NULL,'','sjcj','shujucaiji',1638523680,1638523680,0,'normal'),(363,'file',356,'suisunwechat/wechat_usersummary/getData','获取统计数据','fa fa-circle-o','','','',0,NULL,'','hqtjsj','huoqutongjishuju',1638523680,1638523680,0,'normal'),(364,'file',254,'suisunwechat/wechat_broadcasting','消息群发','fa fa-gears','','','',1,NULL,'','xxqf','xiaoxiqunfa',1638523680,1638523680,0,'normal'),(365,'file',364,'suisunwechat/wechat_broadcasting/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638523680,1638523680,0,'normal'),(366,'file',364,'suisunwechat/wechat_broadcasting/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638523680,1638523680,0,'normal'),(367,'file',364,'suisunwechat/wechat_broadcasting/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638523680,1638523680,0,'normal'),(368,'file',364,'suisunwechat/wechat_broadcasting/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638523680,1638523680,0,'normal'),(369,'file',364,'suisunwechat/wechat_broadcasting/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638523680,1638523680,0,'normal'),(370,'file',364,'suisunwechat/wechat_broadcasting/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638523680,1638523680,0,'normal'),(371,'file',364,'suisunwechat/wechat_broadcasting/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638523680,1638523680,0,'normal'),(372,'file',364,'suisunwechat/wechat_broadcasting/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638523680,1638523680,0,'normal'),(373,'file',364,'suisunwechat/wechat_broadcasting/preview','预览','fa fa-circle-o','','','',0,NULL,'','yl','yulan',1638523680,1638523680,0,'normal'),(374,'file',0,'trouble','隐患排查','fa fa-list','','','',1,'addtabs','','yhpc','yinhuanpaicha',1638614247,1640164683,1,'normal'),(375,'file',374,'trouble/base','基础信息','fa fa-chevron-right','','','',1,'addtabs','','jcxx','jichuxinxi',1638614247,1638616420,0,'normal'),(376,'file',375,'trouble/base/type','隐患类型','fa fa-circle-o','','','设置常用隐患类型，便于对隐患分级管理',1,'addtabs','','yhlx','yinhuanleixing',1638614247,1638615968,3,'normal'),(377,'file',376,'trouble/base/type/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1638614247,1638614247,0,'normal'),(378,'file',376,'trouble/base/type/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638614247,1638614247,0,'normal'),(379,'file',376,'trouble/base/type/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638614247,1638614247,0,'normal'),(380,'file',376,'trouble/base/type/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638614247,1638614247,0,'normal'),(381,'file',376,'trouble/base/type/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638614247,1638614247,0,'normal'),(382,'file',376,'trouble/base/type/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638614247,1638614247,0,'normal'),(390,'file',375,'trouble/base/expression','隐患现象','fa fa-circle-o','','','设置常见的隐患现象',1,'addtabs','','yhxx','yinhuanxianxiang',1638614326,1638615955,5,'normal'),(391,'file',390,'trouble/base/expression/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1638614326,1638614326,0,'normal'),(392,'file',390,'trouble/base/expression/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638614326,1638614326,0,'normal'),(393,'file',390,'trouble/base/expression/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638614326,1638614326,0,'normal'),(394,'file',390,'trouble/base/expression/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638614326,1638614326,0,'normal'),(395,'file',390,'trouble/base/expression/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638614326,1638614326,0,'normal'),(396,'file',390,'trouble/base/expression/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638614326,1638614326,0,'normal'),(397,'file',375,'trouble/base/point','隐患点信息','fa fa-circle-o','','','设置信息点台账',1,'addtabs','','yhdxx','yinhuandianxinxi',1638614813,1638614840,0,'normal'),(398,'file',397,'trouble/base/point/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1638614813,1639886035,0,'normal'),(399,'file',397,'trouble/base/point/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638614813,1639886035,0,'normal'),(400,'file',397,'trouble/base/point/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638614813,1639886035,0,'normal'),(401,'file',397,'trouble/base/point/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638614813,1639886035,0,'normal'),(402,'file',397,'trouble/base/point/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638614813,1639886035,0,'normal'),(403,'file',397,'trouble/base/point/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638614813,1639886035,0,'normal'),(404,'file',375,'trouble/base/area','区域信息','fa fa-circle-o','','','设置隐患点所在区域信息，方便按区域管理',1,'addtabs','','qyxx','quyuxinxi',1638615915,1638616027,2,'normal'),(405,'file',404,'trouble/base/area/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1638615915,1638615915,0,'normal'),(406,'file',404,'trouble/base/area/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638615915,1638615915,0,'normal'),(407,'file',404,'trouble/base/area/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638615915,1638615915,0,'normal'),(408,'file',404,'trouble/base/area/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638615915,1638615915,0,'normal'),(409,'file',404,'trouble/base/area/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638615915,1638615915,0,'normal'),(410,'file',404,'trouble/base/area/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638615915,1638615915,0,'normal'),(411,'file',374,'trouble/trouble','隐患排查','fa fa-chevron-right','','','',1,'addtabs','','yhpc','yinhuanpaicha',1638705187,1638705669,0,'normal'),(412,'file',411,'trouble/trouble/main','告警信息','fa fa-circle-o','','','查看所有告警信息详细以及处理过程等全部信息',1,'addtabs','','gjxx','gaojingxinxi',1638705187,1639998997,1,'normal'),(413,'file',412,'trouble/trouble/main/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1638705187,1638705187,0,'normal'),(414,'file',412,'trouble/trouble/main/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638705187,1638705187,0,'normal'),(415,'file',412,'trouble/trouble/main/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638705187,1638705187,0,'normal'),(416,'file',412,'trouble/trouble/main/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638705187,1638705187,0,'normal'),(417,'file',412,'trouble/trouble/main/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638705187,1638705187,0,'normal'),(418,'file',412,'trouble/trouble/main/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638705187,1638705187,0,'normal'),(419,'file',411,'trouble/trouble/recevie','接警处理','fa fa-circle-o','','','处理路人告警，人工接警，批量导入隐患信息，警情梳理',1,'addtabs','','jjcl','jiejingchuli',1638754560,1639997047,2,'normal'),(420,'file',419,'trouble/trouble/recevie/import','导入','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1638754560,1640397342,0,'normal'),(421,'file',419,'trouble/trouble/recevie/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1638754560,1640397342,0,'normal'),(422,'file',419,'trouble/trouble/recevie/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1638754560,1640397342,0,'normal'),(423,'file',419,'trouble/trouble/recevie/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1638754560,1640397342,0,'normal'),(424,'file',419,'trouble/trouble/recevie/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1638754560,1640397342,0,'normal'),(425,'file',419,'trouble/trouble/recevie/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1638754560,1640397342,0,'normal'),(427,'file',419,'trouble/trouble/recevie/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1638865718,1639992655,0,'normal'),(428,'file',419,'trouble/trouble/recevie/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1638865718,1639992655,0,'normal'),(429,'file',419,'trouble/trouble/recevie/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1638865718,1639992655,0,'normal'),(442,'file',397,'trouble/base/point/getpoint','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1639886035,1639886035,0,'normal'),(443,'file',397,'trouble/base/point/downqrpic','下载全部二维码','fa fa-circle-o','','','',0,NULL,'','xzqbewm','xiazaiquanbuerweima',1639886035,1639886035,0,'normal'),(444,'file',397,'trouble/base/point/downqrcode','下载二维码','fa fa-circle-o','','','',0,NULL,'','xzewm','xiazaierweima',1639886035,1639886035,0,'normal'),(445,'file',419,'trouble/trouble/recevie/verify','接警','fa fa-circle-o','','','',0,NULL,'','jj','jiejing',1639992655,1640397342,0,'normal'),(446,'file',419,'trouble/trouble/recevie/cancelverify','取消接警','fa fa-circle-o','','','',0,NULL,'','qxjj','quxiaojiejing',1639992655,1640397342,0,'normal'),(447,'file',374,'trouble/report','隐患报表','fa fa-chevron-right','','','',1,'addtabs','','yhbb','yinhuanbaobiao',1640083359,1640083558,0,'normal'),(448,'file',447,'trouble/report/daily','隐患日报','fa fa-circle-o','','','查看隐患日报表，周报表，月报表或指定时间段内的报表',1,'addtabs','','yhrb','yinhuanribao',1640083359,1640083441,0,'normal'),(449,'file',448,'trouble/report/daily/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1640083359,1640083361,0,'normal'),(450,'file',448,'trouble/report/daily/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1640083359,1640083361,0,'normal'),(451,'file',448,'trouble/report/daily/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1640083359,1640083361,0,'normal'),(452,'file',448,'trouble/report/daily/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1640083359,1640083361,0,'normal'),(453,'file',448,'trouble/report/daily/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1640083359,1640083361,0,'normal'),(454,'file',448,'trouble/report/daily/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1640083359,1640083361,0,'normal'),(455,'file',448,'trouble/report/daily/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1640083359,1640083361,0,'normal'),(456,'file',448,'trouble/report/daily/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1640083359,1640083361,0,'normal'),(457,'file',448,'trouble/report/daily/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1640083359,1640083361,0,'normal'),(458,'file',447,'trouble/report/typical','典型隐患报表','fa fa-circle-o','','','对隐患排查信息进行汇总，对高发典型隐患梳理，查看典型隐患报表',1,'addtabs','','dxyhbb','dianxingyinhuanbaobiao',1640084407,1640084527,0,'normal'),(459,'file',458,'trouble/report/typical/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1640084407,1640084407,0,'normal'),(460,'file',458,'trouble/report/typical/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1640084407,1640084407,0,'normal'),(461,'file',458,'trouble/report/typical/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1640084407,1640084407,0,'normal'),(462,'file',458,'trouble/report/typical/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1640084407,1640084407,0,'normal'),(463,'file',458,'trouble/report/typical/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1640084407,1640084407,0,'normal'),(464,'file',458,'trouble/report/typical/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1640084407,1640084407,0,'normal'),(465,'file',458,'trouble/report/typical/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1640084407,1640084407,0,'normal'),(466,'file',458,'trouble/report/typical/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1640084407,1640084407,0,'normal'),(467,'file',458,'trouble/report/typical/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1640084407,1640084407,0,'normal'),(468,'file',447,'trouble/report/depart','隐患部门报表','fa fa-circle-o','','','查看隐患高发的部门信息报表',1,'addtabs','','yhbmbb','yinhuanbumenbaobiao',1640084407,1640084568,0,'normal'),(469,'file',468,'trouble/report/depart/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1640084407,1640084407,0,'normal'),(470,'file',468,'trouble/report/depart/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1640084407,1640084407,0,'normal'),(471,'file',468,'trouble/report/depart/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1640084407,1640084407,0,'normal'),(472,'file',468,'trouble/report/depart/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1640084407,1640084407,0,'normal'),(473,'file',468,'trouble/report/depart/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1640084407,1640084407,0,'normal'),(474,'file',468,'trouble/report/depart/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1640084407,1640084407,0,'normal'),(475,'file',468,'trouble/report/depart/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1640084407,1640084407,0,'normal'),(476,'file',468,'trouble/report/depart/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1640084407,1640084407,0,'normal'),(477,'file',468,'trouble/report/depart/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1640084407,1640084407,0,'normal'),(478,'file',447,'trouble/report/duration','隐患时效报表','fa fa-circle-o','','','查看隐患处理时效的报表',1,'addtabs','','yhsxbb','yinhuanshixiaobaobiao',1640084407,1640084593,0,'normal'),(479,'file',478,'trouble/report/duration/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1640084407,1640084407,0,'normal'),(480,'file',478,'trouble/report/duration/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1640084407,1640084407,0,'normal'),(481,'file',478,'trouble/report/duration/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1640084407,1640084407,0,'normal'),(482,'file',478,'trouble/report/duration/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1640084407,1640084407,0,'normal'),(483,'file',478,'trouble/report/duration/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1640084407,1640084407,0,'normal'),(484,'file',478,'trouble/report/duration/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1640084407,1640084407,0,'normal'),(485,'file',478,'trouble/report/duration/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1640084407,1640084407,0,'normal'),(486,'file',478,'trouble/report/duration/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1640084407,1640084407,0,'normal'),(487,'file',478,'trouble/report/duration/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1640084407,1640084407,0,'normal'),(488,'file',447,'trouble/report/type','隐患类型报表','fa fa-circle-o','','','梳理出隐患类型的汇总表报',1,'addtabs','','yhlxbb','yinhuanleixingbaobiao',1640084407,1640084622,0,'normal'),(489,'file',488,'trouble/report/type/import','Import','fa fa-circle-o','','','',0,NULL,'','dr','daoru',1640084407,1640084407,0,'normal'),(490,'file',488,'trouble/report/type/index','查看','fa fa-circle-o','','','',0,NULL,'','zk','zhakan',1640084407,1640084407,0,'normal'),(491,'file',488,'trouble/report/type/recyclebin','回收站','fa fa-circle-o','','','',0,NULL,'','hsz','huishouzhan',1640084407,1640084407,0,'normal'),(492,'file',488,'trouble/report/type/add','添加','fa fa-circle-o','','','',0,NULL,'','tj','tianjia',1640084407,1640084407,0,'normal'),(493,'file',488,'trouble/report/type/edit','编辑','fa fa-circle-o','','','',0,NULL,'','bj','bianji',1640084407,1640084407,0,'normal'),(494,'file',488,'trouble/report/type/del','删除','fa fa-circle-o','','','',0,NULL,'','sc','shanchu',1640084407,1640084407,0,'normal'),(495,'file',488,'trouble/report/type/destroy','真实删除','fa fa-circle-o','','','',0,NULL,'','zssc','zhenshishanchu',1640084407,1640084407,0,'normal'),(496,'file',488,'trouble/report/type/restore','还原','fa fa-circle-o','','','',0,NULL,'','hy','huanyuan',1640084407,1640084407,0,'normal'),(497,'file',488,'trouble/report/type/multi','批量更新','fa fa-circle-o','','','',0,NULL,'','plgx','pilianggengxin',1640084407,1640084407,0,'normal'),(498,'file',419,'trouble/trouble/recevie/getlog','Getlog','fa fa-circle-o','','','',0,NULL,'','G','Getlog',1640397342,1640397342,0,'normal'),(499,'file',419,'trouble/trouble/recevie/getcode','Getcode','fa fa-circle-o','','','',0,NULL,'','G','Getcode',1640397342,1640397342,0,'normal'),(500,'file',419,'trouble/trouble/recevie/together','并案','fa fa-circle-o','','','',0,NULL,'','ba','bingan',1640397342,1640397342,0,'normal');
/*!40000 ALTER TABLE `safe_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_category`
--

DROP TABLE IF EXISTS `safe_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `flag` set('hot','index','recommend') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自定义名称',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `weigh` (`weigh`,`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_category`
--

LOCK TABLES `safe_category` WRITE;
/*!40000 ALTER TABLE `safe_category` DISABLE KEYS */;
INSERT INTO `safe_category` VALUES (1,0,'page','官方新闻','news','recommend','/assets/img/qrcode.png','','','news',1491635035,1491635035,1,'normal'),(2,0,'page','移动应用','mobileapp','hot','/assets/img/qrcode.png','','','mobileapp',1491635035,1491635035,2,'normal'),(3,2,'page','微信公众号','wechatpublic','index','/assets/img/qrcode.png','','','wechatpublic',1491635035,1491635035,3,'normal'),(4,2,'page','Android开发','android','recommend','/assets/img/qrcode.png','','','android',1491635035,1491635035,4,'normal'),(5,0,'page','软件产品','software','recommend','/assets/img/qrcode.png','','','software',1491635035,1491635035,5,'normal'),(6,5,'page','网站建站','website','recommend','/assets/img/qrcode.png','','','website',1491635035,1491635035,6,'normal'),(7,5,'page','企业管理软件','company','index','/assets/img/qrcode.png','','','company',1491635035,1491635035,7,'normal'),(8,6,'page','PC端','website-pc','recommend','/assets/img/qrcode.png','','','website-pc',1491635035,1491635035,8,'normal'),(9,6,'page','移动端','website-mobile','recommend','/assets/img/qrcode.png','','','website-mobile',1491635035,1491635035,9,'normal'),(10,7,'page','CRM系统 ','company-crm','recommend','/assets/img/qrcode.png','','','company-crm',1491635035,1491635035,10,'normal'),(11,7,'page','SASS平台软件','company-sass','recommend','/assets/img/qrcode.png','','','company-sass',1491635035,1491635035,11,'normal'),(12,0,'test','测试1','test1','recommend','/assets/img/qrcode.png','','','test1',1491635035,1491635035,12,'normal'),(13,0,'test','测试2','test2','recommend','/assets/img/qrcode.png','','','test2',1491635035,1491635035,13,'normal');
/*!40000 ALTER TABLE `safe_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_command`
--

DROP TABLE IF EXISTS `safe_command`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_command` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '类型',
  `params` varchar(1500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '参数',
  `command` varchar(1500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '命令',
  `content` text COMMENT '返回结果',
  `executetime` int(10) unsigned DEFAULT NULL COMMENT '执行时间',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `status` enum('successed','failured') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'failured' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='在线命令表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_command`
--

LOCK TABLES `safe_command` WRITE;
/*!40000 ALTER TABLE `safe_command` DISABLE KEYS */;
INSERT INTO `safe_command` VALUES (1,'crud','[\"--table=safe_user_department\",\"--controller=user\\/department\"]','php think crud --table=safe_user_department --controller=user/department','Build Successed',1634369669,1634369669,1634369669,'successed'),(2,'menu','[\"--controller=user\\/Department\"]','php think menu --controller=user/Department','Build Successed!',1634369682,1634369682,1634369683,'successed'),(3,'crud','[\"--force=1\",\"--table=safe_user\",\"--controller=user\\/user\",\"--relation=safe_user_department\",\"--relationmode=belongsto\",\"--relationforeignkey=department_id\",\"--relationprimarykey=id\",\"--relationfields=name\",\"--relation=safe_user_group\",\"--relationmode=belongsto\",\"--relationforeignkey=group_id\",\"--relationprimarykey=id\",\"--relationfields=name\"]','php think crud --force=1 --table=safe_user --controller=user/user --relation=safe_user_department --relationmode=belongsto --relationforeignkey=department_id --relationprimarykey=id --relationfields=name --relation=safe_user_group --relationmode=belongsto --relationforeignkey=group_id --relationprimarykey=id --relationfields=name','system table can\'t be crud',1634690746,1634690746,1634690746,'failured'),(4,'menu','[\"--controller=user\\/User\"]','php think menu --controller=user/User','Build Successed!',1634804090,1634804090,1634804091,'successed'),(5,'crud','[\"--table=safe_user\",\"--controller=training\\/plan\",\"--relation=safe_user_department\",\"--relationmode=belongsto\",\"--relationforeignkey=department_id\",\"--relationprimarykey=id\",\"--relation=safe_user_group\",\"--relationmode=belongsto\",\"--relationforeignkey=group_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_user --controller=training/plan --relation=safe_user_department --relationmode=belongsto --relationforeignkey=department_id --relationprimarykey=id --relation=safe_user_group --relationmode=belongsto --relationforeignkey=group_id --relationprimarykey=id','system table can\'t be crud',1635388667,1635388667,1635388667,'failured'),(6,'menu','[\"--controller=training\\/Plan\"]','php think menu --controller=training/Plan',NULL,1635390395,1635390395,1635390395,'failured'),(7,'menu','[\"--controller=training\\/Plan\"]','php think menu --controller=training/Plan',NULL,1635390408,1635390408,1635390408,'failured'),(8,'menu','[\"--controller=training\\/Plan\"]','php think menu --controller=training/Plan',NULL,1635390416,1635390416,1635390416,'failured'),(9,'menu','[\"--controller=training\\/Plan\"]','php think menu --controller=training/Plan','Build Successed!',1635390648,1635390648,1635390649,'successed'),(10,'menu','[\"--controller=training\\/Plan\"]','php think menu --controller=training/Plan','Build Successed!',1635466945,1635466945,1635466946,'successed'),(11,'menu','[\"--controller=training\\/Plan\"]','php think menu --controller=training/Plan','Build Successed!',1635466995,1635466995,1635466995,'successed'),(12,'menu','[\"--controller=training\\/Plan\"]','php think menu --controller=training/Plan','Build Successed!',1635554526,1635554526,1635554526,'successed'),(13,'menu','[\"--delete=1\",\"--controller=training\\/Plan\"]','php think menu --delete=1 --controller=training/Plan','training/plan\ntraining/plan/index\ntraining/plan/selectmain\nAre you sure you want to delete all those menu?  Type \'yes\' to continue: \nOperation is aborted!',1635561089,1635561089,1635561089,'failured'),(14,'menu','[\"--controller=user\\/Plan\"]','php think menu --controller=user/Plan','Build Successed!',1635561299,1635561299,1635561300,'successed'),(15,'menu','[\"--controller=user\\/Plan\"]','php think menu --controller=user/Plan','Build Successed!',1635563217,1635563217,1635563217,'successed'),(16,'crud','[\"--table=safe_training_main\",\"--controller=train\\/mainlist\",\"--relation=safe_training_category\",\"--relationmode=belongsto\",\"--relationforeignkey=training_category_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_training_main --controller=train/mainlist --relation=safe_training_category --relationmode=belongsto --relationforeignkey=training_category_id --relationprimarykey=id','Build Successed',1635683372,1635683372,1635683372,'successed'),(17,'crud','[\"--table=safe_training_main\",\"--controller=training\\/mainlist\",\"--relation=safe_training_category\",\"--relationmode=belongsto\",\"--relationforeignkey=training_category_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_training_main --controller=training/mainlist --relation=safe_training_category --relationmode=belongsto --relationforeignkey=training_category_id --relationprimarykey=id','Build Successed',1635683471,1635683471,1635683471,'successed'),(18,'crud','[\"--force=1\",\"--table=safe_kaoshi_subject\",\"--controller=kaoshi\\/subject\",\"--model=KaoshiSubject\"]','php think crud --force=1 --table=safe_kaoshi_subject --controller=kaoshi/subject --model=KaoshiSubject','Build Successed',1635821298,1635821298,1635821298,'successed'),(19,'crud','[\"--table=safe_company_info\",\"--controller=setting\\/company\"]','php think crud --table=safe_company_info --controller=setting/company','Build Successed',1636866390,1636866390,1636866390,'successed'),(20,'menu','[\"--controller=setting\\/Company\"]','php think menu --controller=setting/Company','Build Successed!',1636866402,1636866402,1636866403,'successed'),(21,'menu','[\"--controller=kaoshi\\/examination\\/Questions\"]','php think menu --controller=kaoshi/examination/Questions','Build Successed!',1636889910,1636889910,1636889911,'successed'),(22,'crud','[\"--table=safe_kaoshi_plan\",\"--controller=kaoshi\\/examination\\/planlist\"]','php think crud --table=safe_kaoshi_plan --controller=kaoshi/examination/planlist','Build Successed',1637226513,1637226513,1637226513,'successed'),(23,'crud','[\"--force=1\",\"--table=safe_materials_category\",\"--controller=materials\\/category\"]','php think crud --force=1 --table=safe_materials_category --controller=materials/category','Build Successed',1637379783,1637379783,1637379783,'successed'),(24,'menu','[\"--controller=materials\\/Category\"]','php think menu --controller=materials/Category','Build Successed!',1637379821,1637379821,1637379821,'successed'),(25,'crud','[\"--force=1\",\"--table=safe_materials_info\",\"--controller=materials\\/info\",\"--relation=safe_materials_category\",\"--relationmode=belongsto\",\"--relationforeignkey=materials_category_id\",\"--relationprimarykey=id\"]','php think crud --force=1 --table=safe_materials_info --controller=materials/info --relation=safe_materials_category --relationmode=belongsto --relationforeignkey=materials_category_id --relationprimarykey=id','Build Successed',1637379925,1637379925,1637379925,'successed'),(26,'menu','[\"--controller=materials\\/Info\"]','php think menu --controller=materials/Info','Build Successed!',1637379936,1637379936,1637379936,'successed'),(27,'crud','[\"--force=1\",\"--table=safe_materials_info\",\"--controller=materials\\/info\"]','php think crud --force=1 --table=safe_materials_info --controller=materials/info','Build Successed',1637386173,1637386173,1637386174,'successed'),(28,'crud','[\"--table=safe_message_category\",\"--controller=message\\/category\"]','php think crud --table=safe_message_category --controller=message/category','Build Successed',1638072818,1638072818,1638072818,'successed'),(29,'crud','[\"--table=safe_message_info\",\"--controller=message\\/info\",\"--relation=safe_message_category\",\"--relationmode=belongsto\",\"--relationforeignkey=category_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_message_info --controller=message/info --relation=safe_message_category --relationmode=belongsto --relationforeignkey=category_id --relationprimarykey=id','Build Successed',1638072861,1638072861,1638072861,'successed'),(30,'menu','[\"--controller=message\\/Category\",\"--controller=message\\/Info\"]','php think menu --controller=message/Category --controller=message/Info','Build Successed!',1638072874,1638072874,1638072875,'successed'),(31,'crud','[\"--table=safe_training_record\",\"--controller=training\\/record\",\"--relation=safe_training_course\",\"--relationmode=belongsto\",\"--relationforeignkey=training_course_id\",\"--relationprimarykey=id\",\"--relation=safe_training_main\",\"--relationmode=belongsto\",\"--relationforeignkey=training_main_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_training_record --controller=training/record --relation=safe_training_course --relationmode=belongsto --relationforeignkey=training_course_id --relationprimarykey=id --relation=safe_training_main --relationmode=belongsto --relationforeignkey=training_main_id --relationprimarykey=id','Build Successed',1638435438,1638435438,1638435438,'successed'),(32,'crud','[\"--table=safe_training_record\",\"--controller=training\\/record\",\"--relation=safe_training_course\",\"--relationmode=belongsto\",\"--relationforeignkey=training_course_id\",\"--relationprimarykey=id\",\"--relation=safe_training_main\",\"--relationmode=belongsto\",\"--relationforeignkey=training_main_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_training_record --controller=training/record --relation=safe_training_course --relationmode=belongsto --relationforeignkey=training_course_id --relationprimarykey=id --relation=safe_training_main --relationmode=belongsto --relationforeignkey=training_main_id --relationprimarykey=id','controller already exists!\nIf you need to rebuild again, use the parameter --force=true',1638435440,1638435440,1638435440,'failured'),(33,'crud','[\"--force=1\",\"--table=safe_training_record\",\"--controller=training\\/record\",\"--relation=safe_training_course\",\"--relationmode=belongsto\",\"--relationforeignkey=training_course_id\",\"--relationprimarykey=id\",\"--relation=safe_training_main\",\"--relationmode=belongsto\",\"--relationforeignkey=training_main_id\",\"--relationprimarykey=id\"]','php think crud --force=1 --table=safe_training_record --controller=training/record --relation=safe_training_course --relationmode=belongsto --relationforeignkey=training_course_id --relationprimarykey=id --relation=safe_training_main --relationmode=belongsto --relationforeignkey=training_main_id --relationprimarykey=id','Build Successed',1638435454,1638435454,1638435454,'successed'),(34,'crud','[\"--table=safe_kaoshi_user_plan\",\"--controller=kaoshi\\/examination\\/kaoshirecord\",\"--relation=safe_kaoshi_user_exams\",\"--relationmode=belongsto\",\"--relationforeignkey=id\",\"--relationprimarykey=user_plan_id\",\"--relation=safe_kaoshi_plan\",\"--relationmode=belongsto\",\"--relationforeignkey=plan_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_kaoshi_user_plan --controller=kaoshi/examination/kaoshirecord --relation=safe_kaoshi_user_exams --relationmode=belongsto --relationforeignkey=id --relationprimarykey=user_plan_id --relation=safe_kaoshi_plan --relationmode=belongsto --relationforeignkey=plan_id --relationprimarykey=id','Build Successed',1638438573,1638438573,1638438574,'successed'),(35,'crud','[\"--table=safe_trouble_type\",\"--controller=trouble\\/base\\/type\"]','php think crud --table=safe_trouble_type --controller=trouble/base/type','Build Successed',1638614234,1638614234,1638614234,'successed'),(36,'menu','[\"--controller=trouble\\/base\\/Type\"]','php think menu --controller=trouble/base/Type','Build Successed!',1638614247,1638614247,1638614247,'successed'),(37,'crud','[\"--table=safe_trouble_plan\",\"--controller=trouble\\/base\\/plan\"]','php think crud --table=safe_trouble_plan --controller=trouble/base/plan','Build Successed',1638614283,1638614283,1638614283,'successed'),(38,'crud','[\"--table=safe_trouble_expression\",\"--controller=trouble\\/base\\/expression\"]','php think crud --table=safe_trouble_expression --controller=trouble/base/expression','Build Successed',1638614305,1638614305,1638614305,'successed'),(39,'menu','[\"--controller=trouble\\/base\\/Plan\",\"--controller=trouble\\/base\\/Expression\"]','php think menu --controller=trouble/base/Plan --controller=trouble/base/Expression','Build Successed!',1638614326,1638614326,1638614326,'successed'),(40,'crud','[\"--table=safe_trouble_point\",\"--controller=trouble\\/base\\/point\"]','php think crud --table=safe_trouble_point --controller=trouble/base/point','Build Successed',1638614800,1638614800,1638614800,'successed'),(41,'menu','[\"--controller=trouble\\/base\\/Point\"]','php think menu --controller=trouble/base/Point','Build Successed!',1638614812,1638614812,1638614813,'successed'),(42,'crud','[\"--force=1\",\"--table=safe_trouble_point\",\"--controller=trouble\\/base\\/point\"]','php think crud --force=1 --table=safe_trouble_point --controller=trouble/base/point','Build Successed',1638614993,1638614993,1638614993,'successed'),(43,'crud','[\"--table=safe_trouble_area\",\"--controller=trouble\\/base\\/area\"]','php think crud --table=safe_trouble_area --controller=trouble/base/area','Build Successed',1638615900,1638615900,1638615900,'successed'),(44,'menu','[\"--controller=trouble\\/base\\/Area\"]','php think menu --controller=trouble/base/Area','Build Successed!',1638615915,1638615915,1638615915,'successed'),(45,'crud','[\"--force=1\",\"--table=safe_trouble_point\",\"--controller=trouble\\/base\\/point\",\"--relation=safe_trouble_area\",\"--relationmode=belongsto\",\"--relationforeignkey=point_area_id\",\"--relationprimarykey=id\",\"--relation=safe_user_department\",\"--relationmode=belongsto\",\"--relationforeignkey=point_department_id\",\"--relationprimarykey=id\"]','php think crud --force=1 --table=safe_trouble_point --controller=trouble/base/point --relation=safe_trouble_area --relationmode=belongsto --relationforeignkey=point_area_id --relationprimarykey=id --relation=safe_user_department --relationmode=belongsto --relationforeignkey=point_department_id --relationprimarykey=id','Build Successed',1638695104,1638695104,1638695105,'successed'),(46,'crud','[\"--table=safe_trouble_main\",\"--controller=trouble\\/trouble\\/main\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=company_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_trouble_main --controller=trouble/trouble/main --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=company_id --relationprimarykey=id','Build Successed',1638705108,1638705108,1638705108,'successed'),(47,'menu','[\"--controller=trouble\\/trouble\\/Main\"]','php think menu --controller=trouble/trouble/Main','Build Successed!',1638705187,1638705187,1638705187,'successed'),(48,'crud','[\"--force=1\",\"--table=safe_trouble_main\",\"--controller=trouble\\/trouble\\/recevie\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=trouble_type_id\",\"--relationprimarykey=id\"]','php think crud --force=1 --table=safe_trouble_main --controller=trouble/trouble/recevie --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=trouble_type_id --relationprimarykey=id','Build Successed',1638754542,1638754542,1638754542,'successed'),(49,'menu','[\"--controller=trouble\\/trouble\\/Recevie\"]','php think menu --controller=trouble/trouble/Recevie','Build Successed!',1638754559,1638754559,1638754560,'successed'),(50,'crud','[\"--table=safe_trouble_log\",\"--controller=trouble\\/trouble\\/log\"]','php think crud --table=safe_trouble_log --controller=trouble/trouble/log','Build Successed',1638861034,1638861034,1638861035,'successed'),(51,'menu','[\"--controller=trouble\\/trouble\\/Recevie\"]','php think menu --controller=trouble/trouble/Recevie','Build Successed!',1638864759,1638864759,1638864759,'successed'),(52,'menu','[\"--controller=trouble\\/trouble\\/Recevie\"]','php think menu --controller=trouble/trouble/Recevie','Build Successed!',1638864916,1638864916,1638864916,'successed'),(53,'menu','[\"--delete=1\",\"--controller=trouble\\/trouble\\/Recevie\"]','php think menu --delete=1 --controller=trouble/trouble/Recevie','trouble/trouble/recevie\ntrouble/trouble/recevie/add\ntrouble/trouble/recevie/del\ntrouble/trouble/recevie/edit\ntrouble/trouble/recevie/import\ntrouble/trouble/recevie/index\ntrouble/trouble/recevie/multi\nAre you sure you want to delete all those menu?  Type \'yes\' to continue: \nOperation is aborted!',1638865042,1638865042,1638865042,'failured'),(54,'menu','[\"--controller=trouble\\/trouble\\/Recevie\"]','php think menu --controller=trouble/trouble/Recevie','Build Successed!',1638865057,1638865057,1638865058,'successed'),(55,'crud','[\"--force=1\",\"--table=safe_trouble_main\",\"--controller=trouble\\/trouble\\/recevie\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=trouble_type_id\",\"--relationprimarykey=id\"]','php think crud --force=1 --table=safe_trouble_main --controller=trouble/trouble/recevie --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=trouble_type_id --relationprimarykey=id','Build Successed',1638865693,1638865693,1638865693,'successed'),(56,'menu','[\"--controller=trouble\\/trouble\\/Recevie\"]','php think menu --controller=trouble/trouble/Recevie','Build Successed!',1638865718,1638865718,1638865718,'successed'),(57,'crud','[\"--table=safe_trouble_main\",\"--controller=trouble\\/trouble\\/dispatch\",\"--headingfilterfield=main_status\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=trouble_type_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_trouble_main --controller=trouble/trouble/dispatch --headingfilterfield=main_status --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=trouble_type_id --relationprimarykey=id','Build Successed',1638870594,1638870594,1638870594,'successed'),(58,'menu','[\"--controller=trouble\\/trouble\\/Dispatch\"]','php think menu --controller=trouble/trouble/Dispatch','Build Successed!',1638870612,1638870612,1638870612,'successed'),(59,'menu','[\"--controller=trouble\\/trouble\\/Dispatch\"]','php think menu --controller=trouble/trouble/Dispatch','Build Successed!',1638872596,1638872596,1638872596,'successed'),(60,'menu','[\"--controller=trouble\\/trouble\\/Dispatch\"]','php think menu --controller=trouble/trouble/Dispatch','Build Successed!',1638872773,1638872773,1638872773,'successed'),(61,'menu','[\"--controller=trouble\\/base\\/Point\"]','php think menu --controller=trouble/base/Point','Build Successed!',1639886035,1639886035,1639886035,'successed'),(62,'menu','[\"--controller=trouble\\/trouble\\/Recevie\"]','php think menu --controller=trouble/trouble/Recevie','Build Successed!',1639992654,1639992654,1639992655,'successed'),(63,'crud','[\"--table=safe_trouble_main\",\"--controller=trouble\\/report\\/daily\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=trouble_type_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_trouble_main --controller=trouble/report/daily --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=trouble_type_id --relationprimarykey=id','Build Successed',1640083347,1640083347,1640083347,'successed'),(64,'menu','[\"--controller=trouble\\/report\\/Daily\"]','php think menu --controller=trouble/report/Daily','Build Successed!',1640083359,1640083359,1640083359,'successed'),(65,'menu','[\"--controller=trouble\\/report\\/Daily\"]','php think menu --controller=trouble/report/Daily','Build Successed!',1640083360,1640083360,1640083361,'successed'),(66,'crud','[\"--table=safe_trouble_main\",\"--controller=trouble\\/report\\/typical\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=trouble_type_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_trouble_main --controller=trouble/report/typical --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=trouble_type_id --relationprimarykey=id','Build Successed',1640084195,1640084195,1640084196,'successed'),(67,'crud','[\"--table=safe_trouble_main\",\"--controller=trouble\\/report\\/depart\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=trouble_type_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_trouble_main --controller=trouble/report/depart --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=trouble_type_id --relationprimarykey=id','Build Successed',1640084225,1640084225,1640084225,'successed'),(68,'crud','[\"--table=safe_trouble_main\",\"--controller=trouble\\/report\\/duration\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=trouble_type_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_trouble_main --controller=trouble/report/duration --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=trouble_type_id --relationprimarykey=id','Build Successed',1640084308,1640084308,1640084308,'successed'),(69,'crud','[\"--table=safe_trouble_main\",\"--controller=trouble\\/report\\/type\",\"--relation=safe_trouble_point\",\"--relationmode=belongsto\",\"--relationforeignkey=point_id\",\"--relationprimarykey=id\",\"--relation=safe_trouble_type\",\"--relationmode=belongsto\",\"--relationforeignkey=trouble_type_id\",\"--relationprimarykey=id\"]','php think crud --table=safe_trouble_main --controller=trouble/report/type --relation=safe_trouble_point --relationmode=belongsto --relationforeignkey=point_id --relationprimarykey=id --relation=safe_trouble_type --relationmode=belongsto --relationforeignkey=trouble_type_id --relationprimarykey=id','Build Successed',1640084369,1640084369,1640084369,'successed'),(70,'menu','[\"--controller=trouble\\/report\\/Typical\",\"--controller=trouble\\/report\\/Depart\",\"--controller=trouble\\/report\\/Duration\",\"--controller=trouble\\/report\\/Type\"]','php think menu --controller=trouble/report/Typical --controller=trouble/report/Depart --controller=trouble/report/Duration --controller=trouble/report/Type','Build Successed!',1640084406,1640084406,1640084408,'successed'),(71,'menu','[\"--controller=trouble\\/trouble\\/Recevie\"]','php think menu --controller=trouble/trouble/Recevie','Build Successed!',1640397342,1640397342,1640397342,'successed');
/*!40000 ALTER TABLE `safe_command` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_company_info`
--

DROP TABLE IF EXISTS `safe_company_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_company_info` (
  `company_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '公司信息ID',
  `company_name` varchar(45) DEFAULT NULL COMMENT '公司名称',
  `company_nickname` varchar(45) DEFAULT NULL COMMENT '公司简称',
  `company_tel` varchar(45) DEFAULT NULL COMMENT '公司电话',
  `company_address` varchar(100) DEFAULT NULL COMMENT '公司地址',
  `company_websit` varchar(45) DEFAULT NULL COMMENT '网站',
  `company_regtime` int(10) DEFAULT NULL COMMENT '注册时间',
  `company_status` enum('0','1') DEFAULT NULL COMMENT '公司状态:0=禁用,1=正常',
  `company_type` enum('0','1','2') DEFAULT NULL COMMENT '公司类型:0=标准版,1=专业版,2=企业版',
  `company_asofdate` varchar(45) DEFAULT NULL COMMENT '截止日期',
  `company_remark` varchar(45) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_company_info`
--

LOCK TABLES `safe_company_info` WRITE;
/*!40000 ALTER TABLE `safe_company_info` DISABLE KEYS */;
INSERT INTO `safe_company_info` VALUES (2,'方洋能源科技有限公司','方洋能源安全','0518-12345678','连云港','http://192.168.0.4:9010',1640359578,NULL,NULL,'',NULL);
/*!40000 ALTER TABLE `safe_company_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_config`
--

DROP TABLE IF EXISTS `safe_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量名',
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '分组',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '变量值',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '变量字典数据',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '扩展属性',
  `setting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '配置',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_config`
--

LOCK TABLES `safe_config` WRITE;
/*!40000 ALTER TABLE `safe_config` DISABLE KEYS */;
INSERT INTO `safe_config` VALUES (1,'name','basic','Site name','请填写站点名称','string','方洋安全','','required','',''),(2,'beian','basic','Beian','粤ICP备15000000号-1','string','','','','',''),(3,'cdnurl','basic','Cdn url','如果全站静态资源使用第三方云储存请配置该值','string','','','','',''),(4,'version','basic','Version','如果静态资源有变动请重新配置该值','string','1.0.3','','required','',''),(5,'timezone','basic','Timezone','','string','Asia/Shanghai','','required','',''),(6,'forbiddenip','basic','Forbidden ip','一行一条记录','text','','','','',''),(7,'languages','basic','Languages','','array','{\"backend\":\"zh-cn\",\"frontend\":\"zh-cn\"}','','required','',''),(8,'fixedpage','basic','Fixed page','请尽量输入左侧菜单栏存在的链接','string','dashboard','','required','',''),(9,'categorytype','dictionary','Category type','','array','{\"default\":\"Default\",\"page\":\"Page\",\"article\":\"Article\",\"test\":\"Test\"}','','','',''),(10,'configgroup','dictionary','Config group','','array','{\"basic\":\"Basic\",\"email\":\"Email\",\"dictionary\":\"Dictionary\",\"user\":\"User\",\"example\":\"Example\"}','','','',''),(11,'mail_type','email','Mail type','选择邮件发送方式','select','1','[\"请选择\",\"SMTP\"]','','',''),(12,'mail_smtp_host','email','Mail smtp host','错误的配置发送邮件会导致服务器超时','string','smtp.qq.com','','','',''),(13,'mail_smtp_port','email','Mail smtp port','(不加密默认25,SSL默认465,TLS默认587)','string','465','','','',''),(14,'mail_smtp_user','email','Mail smtp user','（填写完整用户名）','string','10000','','','',''),(15,'mail_smtp_pass','email','Mail smtp password','（填写您的密码或授权码）','string','password','','','',''),(16,'mail_verify_type','email','Mail vertify type','（SMTP验证方式[推荐SSL]）','select','2','[\"无\",\"TLS\",\"SSL\"]','','',''),(17,'mail_from','email','Mail from','','string','10000@qq.com','','','',''),(18,'attachmentcategory','dictionary','Attachment category','','array','{\"category1\":\"Category1\",\"category2\":\"Category2\",\"custom\":\"Custom\"}','','','','');
/*!40000 ALTER TABLE `safe_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_ems`
--

DROP TABLE IF EXISTS `safe_ems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_ems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '事件',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '邮箱',
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邮箱验证码表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_ems`
--

LOCK TABLES `safe_ems` WRITE;
/*!40000 ALTER TABLE `safe_ems` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_ems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_kaoshi_exams`
--

DROP TABLE IF EXISTS `safe_kaoshi_exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_kaoshi_exams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(11) unsigned NOT NULL COMMENT '管理员ID',
  `subject_id` mediumint(8) unsigned NOT NULL COMMENT '科目ID',
  `exam_name` varchar(120) NOT NULL COMMENT '卷名',
  `settingdata` text NOT NULL COMMENT '考卷设置',
  `questionsdata` text COMMENT '考卷题目',
  `pass` int(11) NOT NULL DEFAULT '0' COMMENT '及格线',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总分',
  `type` enum('2','1') NOT NULL DEFAULT '1' COMMENT '类型:1=随机组卷,2=自定义组卷',
  `keyword` varchar(240) NOT NULL COMMENT '关键字',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) unsigned NOT NULL COMMENT '更新时间',
  `deletetime` int(11) unsigned DEFAULT NULL COMMENT '删除时间',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`),
  KEY `examstatus` (`status`),
  KEY `examtype` (`type`,`admin_id`),
  KEY `examsubject` (`subject_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_kaoshi_exams`
--

LOCK TABLES `safe_kaoshi_exams` WRITE;
/*!40000 ALTER TABLE `safe_kaoshi_exams` DISABLE KEYS */;
INSERT INTO `safe_kaoshi_exams` VALUES (1,1,1,'公司级学习考核卷','[{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"50\",\"question_ids\":\"1\"},{\"type\":\"3\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"50\",\"question_ids\":\"2\"}]','[{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"50\",\"question_ids\":\"1\",\"timu\":[{\"id\":1,\"question\":\"\\u8bf7\\u95ee\\u4e09\\u7ea7\\u5b89\\u5168\\u6559\\u80b2\\u5305\\u62ec\\u54ea\\u4e09\\u7ea7\\uff1f\",\"selectdata\":\"[{\\\"key\\\":\\\"A\\\",\\\"value\\\":\\\"\\\\u56fd\\\\u5bb6\\\\u7ea7\\\\uff0c\\\\u7701\\\\u7ea7\\\\uff0c\\\\u5e02\\\\u7ea7\\\"},{\\\"key\\\":\\\"B\\\",\\\"value\\\":\\\"\\\\u516c\\\\u53f8\\\\u7ea7\\\\uff0c\\\\u90e8\\\\u95e8\\\\u7ea7\\\\uff0c\\\\u73ed\\\\u7ec4\\\\u7ea7\\\"},{\\\"key\\\":\\\"C\\\",\\\"value\\\":\\\"\\\\u7701\\\\u7ea7\\\\uff0c\\\\u5e02\\\\u7ea7\\\\uff0c\\\\u53bf\\\\u7ea7\\\"},{\\\"key\\\":\\\"D\\\",\\\"value\\\":\\\"\\\\u5e02\\\\u7ea7\\\\uff0c\\\\u533a\\\\u7ea7\\\\uff0c\\\\u8857\\\\u9053\\\"}]\",\"type\":\"1\",\"annex\":\"\",\"answer\":\"B\",\"selectnumber\":4,\"describe\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ea7\\u7ec4\",\"level\":\"1\"}]},{\"type\":\"3\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"50\",\"question_ids\":\"2\",\"timu\":[{\"id\":2,\"question\":\"\\u8fd9\\u4e2a\\u4eba\\u957f\\u5f97\\u5e05\\u4e0d\\u5e05\\uff1f\",\"selectdata\":\"[{\\\"key\\\":\\\"A\\\",\\\"value\\\":\\\"\\\\u5bf9\\\"},{\\\"key\\\":\\\"B\\\",\\\"value\\\":\\\"\\\\u9519\\\"}]\",\"type\":\"3\",\"annex\":\"\\/uploads\\/20211019\\/489af80e5c10bebbe303f58cf14f5529.jpg\",\"answer\":\"A\",\"selectnumber\":2,\"describe\":\"\\u5f53\\u7136\\u5f88\\u5e05\",\"level\":\"1\"}]}]',0,100,'1','',1,1634624042,1637305429,NULL,'2'),(2,2,1,'food','[{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"1\"},{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"1\"},{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"1\"},{\"type\":\"3\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"2\"},{\"type\":\"3\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"2\"}]','[{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"1\",\"timu\":[{\"id\":1,\"question\":\"\\u8bf7\\u95ee\\u4e09\\u7ea7\\u5b89\\u5168\\u6559\\u80b2\\u5305\\u62ec\\u54ea\\u4e09\\u7ea7\\uff1f\",\"selectdata\":\"[{\\\"key\\\":\\\"A\\\",\\\"value\\\":\\\"\\\\u56fd\\\\u5bb6\\\\u7ea7\\\\uff0c\\\\u7701\\\\u7ea7\\\\uff0c\\\\u5e02\\\\u7ea7\\\"},{\\\"key\\\":\\\"B\\\",\\\"value\\\":\\\"\\\\u516c\\\\u53f8\\\\u7ea7\\\\uff0c\\\\u90e8\\\\u95e8\\\\u7ea7\\\\uff0c\\\\u73ed\\\\u7ec4\\\\u7ea7\\\"},{\\\"key\\\":\\\"C\\\",\\\"value\\\":\\\"\\\\u7701\\\\u7ea7\\\\uff0c\\\\u5e02\\\\u7ea7\\\\uff0c\\\\u53bf\\\\u7ea7\\\"},{\\\"key\\\":\\\"D\\\",\\\"value\\\":\\\"\\\\u5e02\\\\u7ea7\\\\uff0c\\\\u533a\\\\u7ea7\\\\uff0c\\\\u8857\\\\u9053\\\"}]\",\"type\":\"1\",\"annex\":\"\",\"answer\":\"B\",\"selectnumber\":4,\"describe\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ea7\\u7ec4\",\"level\":\"1\"}]},{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"1\",\"timu\":[{\"id\":1,\"question\":\"\\u8bf7\\u95ee\\u4e09\\u7ea7\\u5b89\\u5168\\u6559\\u80b2\\u5305\\u62ec\\u54ea\\u4e09\\u7ea7\\uff1f\",\"selectdata\":\"[{\\\"key\\\":\\\"A\\\",\\\"value\\\":\\\"\\\\u56fd\\\\u5bb6\\\\u7ea7\\\\uff0c\\\\u7701\\\\u7ea7\\\\uff0c\\\\u5e02\\\\u7ea7\\\"},{\\\"key\\\":\\\"B\\\",\\\"value\\\":\\\"\\\\u516c\\\\u53f8\\\\u7ea7\\\\uff0c\\\\u90e8\\\\u95e8\\\\u7ea7\\\\uff0c\\\\u73ed\\\\u7ec4\\\\u7ea7\\\"},{\\\"key\\\":\\\"C\\\",\\\"value\\\":\\\"\\\\u7701\\\\u7ea7\\\\uff0c\\\\u5e02\\\\u7ea7\\\\uff0c\\\\u53bf\\\\u7ea7\\\"},{\\\"key\\\":\\\"D\\\",\\\"value\\\":\\\"\\\\u5e02\\\\u7ea7\\\\uff0c\\\\u533a\\\\u7ea7\\\\uff0c\\\\u8857\\\\u9053\\\"}]\",\"type\":\"1\",\"annex\":\"\",\"answer\":\"B\",\"selectnumber\":4,\"describe\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ea7\\u7ec4\",\"level\":\"1\"}]},{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"1\",\"timu\":[{\"id\":1,\"question\":\"\\u8bf7\\u95ee\\u4e09\\u7ea7\\u5b89\\u5168\\u6559\\u80b2\\u5305\\u62ec\\u54ea\\u4e09\\u7ea7\\uff1f\",\"selectdata\":\"[{\\\"key\\\":\\\"A\\\",\\\"value\\\":\\\"\\\\u56fd\\\\u5bb6\\\\u7ea7\\\\uff0c\\\\u7701\\\\u7ea7\\\\uff0c\\\\u5e02\\\\u7ea7\\\"},{\\\"key\\\":\\\"B\\\",\\\"value\\\":\\\"\\\\u516c\\\\u53f8\\\\u7ea7\\\\uff0c\\\\u90e8\\\\u95e8\\\\u7ea7\\\\uff0c\\\\u73ed\\\\u7ec4\\\\u7ea7\\\"},{\\\"key\\\":\\\"C\\\",\\\"value\\\":\\\"\\\\u7701\\\\u7ea7\\\\uff0c\\\\u5e02\\\\u7ea7\\\\uff0c\\\\u53bf\\\\u7ea7\\\"},{\\\"key\\\":\\\"D\\\",\\\"value\\\":\\\"\\\\u5e02\\\\u7ea7\\\\uff0c\\\\u533a\\\\u7ea7\\\\uff0c\\\\u8857\\\\u9053\\\"}]\",\"type\":\"1\",\"annex\":\"\",\"answer\":\"B\",\"selectnumber\":4,\"describe\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ea7\\u7ec4\",\"level\":\"1\"}]},{\"type\":\"3\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"2\",\"timu\":[{\"id\":2,\"question\":\"\\u8fd9\\u4e2a\\u4eba\\u957f\\u5f97\\u5e05\\u4e0d\\u5e05\\uff1f\",\"selectdata\":\"[{\\\"key\\\":\\\"A\\\",\\\"value\\\":\\\"\\\\u5bf9\\\"},{\\\"key\\\":\\\"B\\\",\\\"value\\\":\\\"\\\\u9519\\\"}]\",\"type\":\"3\",\"annex\":\"\\/uploads\\/20211019\\/489af80e5c10bebbe303f58cf14f5529.jpg\",\"answer\":\"A\",\"selectnumber\":2,\"describe\":\"\\u5f53\\u7136\\u5f88\\u5e05\",\"level\":\"1\"}]},{\"type\":\"3\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"2\",\"timu\":[{\"id\":2,\"question\":\"\\u8fd9\\u4e2a\\u4eba\\u957f\\u5f97\\u5e05\\u4e0d\\u5e05\\uff1f\",\"selectdata\":\"[{\\\"key\\\":\\\"A\\\",\\\"value\\\":\\\"\\\\u5bf9\\\"},{\\\"key\\\":\\\"B\\\",\\\"value\\\":\\\"\\\\u9519\\\"}]\",\"type\":\"3\",\"annex\":\"\\/uploads\\/20211019\\/489af80e5c10bebbe303f58cf14f5529.jpg\",\"answer\":\"A\",\"selectnumber\":2,\"describe\":\"\\u5f53\\u7136\\u5f88\\u5e05\",\"level\":\"1\"}]}]',0,100,'2','',1,1635954239,1636124614,NULL,'2'),(3,2,1,'公司级安全教育考核','[{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"20\",\"question_ids\":\"\"},{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"60\",\"question_ids\":\"\"}]',NULL,60,80,'1','',1,1636686724,1637305561,NULL,'2'),(4,2,1,'现场考试','[{\"type\":\"1\",\"level\":\"1\",\"number\":\"1\",\"mark\":\"100\",\"question_ids\":\"\"}]',NULL,100,100,'1','现场考试',1,1638493919,1638493919,NULL,'2');
/*!40000 ALTER TABLE `safe_kaoshi_exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_kaoshi_plan`
--

DROP TABLE IF EXISTS `safe_kaoshi_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_kaoshi_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL COMMENT '科目ID',
  `exam_id` int(11) NOT NULL COMMENT '试卷ID',
  `exam_pic` mediumblob COMMENT '试卷图片',
  `plan_name` varchar(120) NOT NULL COMMENT '名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '考场类型:0=正式,1=学习',
  `hours` int(10) NOT NULL DEFAULT '60' COMMENT '学习时长',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '考试次数',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) unsigned NOT NULL COMMENT '更新时间',
  `deletetime` int(11) unsigned DEFAULT NULL COMMENT '删除时间',
  `limit` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '参与人员:0=全体学生,1=指定学生',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`),
  KEY `basicsubjectid` (`subject_id`),
  KEY `type` (`type`),
  KEY `end_time` (`endtime`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='考场表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_kaoshi_plan`
--

LOCK TABLES `safe_kaoshi_plan` WRITE;
/*!40000 ALTER TABLE `safe_kaoshi_plan` DISABLE KEYS */;
INSERT INTO `safe_kaoshi_plan` VALUES (9,1,3,NULL,'全体练习',0,60,0,1636518026,1639282826,1636518041,1638433883,NULL,0,'2'),(8,1,1,NULL,'练习',1,60,0,1636517967,1639282767,1636517986,1638433924,NULL,1,'2'),(10,1,3,'','公司级安全教育考核计划',0,10,1,1636686777,1639278777,1636686882,1638496953,NULL,0,'2'),(11,1,3,NULL,'公司级安全教育考核模拟卷',1,20,0,1636686888,1639278888,1636686921,1638433869,NULL,0,'2'),(12,1,4,_binary '/uploads/20211128/73185983ff4f8974a5d01b2417c5da1b.png,/uploads/20211128/e6c20e46af558e8c307a414f64346559.png,/uploads/20211128/8b9786448eb3e6c8d56d00c412904f25.png,/uploads/20211120/489af80e5c10bebbe303f58cf14f5529.jpg','首场线下考试',2,60,1,1638497545,1638583945,1638497579,1638498517,NULL,1,'2'),(13,1,4,_binary '/uploads/20211128/e6c20e46af558e8c307a414f64346559.png','第二场线下考试',2,60,0,1638499423,1638585823,1638499442,1638499442,NULL,0,'2');
/*!40000 ALTER TABLE `safe_kaoshi_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_kaoshi_questions`
--

DROP TABLE IF EXISTS `safe_kaoshi_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_kaoshi_questions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) unsigned NOT NULL COMMENT '出题人ID',
  `subject_id` int(11) unsigned NOT NULL COMMENT '科目名称',
  `type` enum('3','2','1') NOT NULL DEFAULT '1' COMMENT '类型',
  `question` text NOT NULL COMMENT '题目',
  `selectdata` text NOT NULL COMMENT '选项',
  `selectnumber` tinyint(11) unsigned NOT NULL DEFAULT '0' COMMENT '选项数量',
  `answer` text NOT NULL COMMENT '答案',
  `describe` text COMMENT '答案解析',
  `level` enum('3','2','1') NOT NULL DEFAULT '1' COMMENT '等级:1=易,2=中,3=难',
  `status` enum('2','1') NOT NULL DEFAULT '1' COMMENT '状态:1=显示,2=隐藏',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `updatetime` int(11) unsigned NOT NULL COMMENT '更新时间',
  `deletetime` int(11) unsigned DEFAULT NULL COMMENT '删除时间',
  `annex` varchar(255) DEFAULT NULL COMMENT '题目附件',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`),
  KEY `questioncreatetime` (`createtime`),
  KEY `questiontype` (`type`),
  KEY `questionstatus` (`status`),
  KEY `questionuserid` (`admin_id`),
  KEY `questionlevel` (`level`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='试题表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_kaoshi_questions`
--

LOCK TABLES `safe_kaoshi_questions` WRITE;
/*!40000 ALTER TABLE `safe_kaoshi_questions` DISABLE KEYS */;
INSERT INTO `safe_kaoshi_questions` VALUES (61,2,1,'3','公司级安全教育是否必须参加？','[{\"key\":\"A\",\"value\":\"\\u5bf9\"},{\"key\":\"B\",\"value\":\"\\u9519\"}]',2,'A','必须要学习的','1','1',1640359511,1640359511,NULL,NULL,'2'),(52,2,1,'3','公司级安全教育是否必须参加？','[{\"key\":\"A\",\"value\":\"\\u5bf9\"},{\"key\":\"B\",\"value\":\"\\u9519\"}]',2,'A',NULL,'1','1',1636890551,1636890551,NULL,NULL,'2'),(53,2,1,'1','这个人长得帅不帅？','[{\"key\":\"A\",\"value\":\"\\u5e05\"},{\"key\":\"B\",\"value\":\"\\u4e0d\\u5e05\"}]',2,'A',NULL,'1','1',1636890551,1636890551,NULL,NULL,'2'),(54,2,2,'1','请问三级安全教育包括哪三级？','[{\"key\":\"A\",\"value\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ec4\\u7ea7\"},{\"key\":\"B\",\"value\":\"\\u56fd\\u5bb6\\u7ea7\\uff0c\\u7701\\u7ea7\\uff0c\\u5e02\\u7ea7\"},{\"key\":\"C\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"}]',3,'B','','1','1',1636890551,1640256677,NULL,'','2'),(55,2,1,'3','公司级安全教育是否必须参加？','[{\"key\":\"A\",\"value\":\"\\u5bf9\"},{\"key\":\"B\",\"value\":\"\\u9519\"}]',2,'A','必须要学习的','1','1',1640255536,1640256650,NULL,'','2'),(56,2,1,'1','这个人长得帅不帅？','[{\"key\":\"A\",\"value\":\"\\u5e05\"},{\"key\":\"B\",\"value\":\"\\u4e0d\\u5e05\"}]',2,'A','长得这么好看，肯定帅','1','1',1640255536,1640256734,NULL,'','2'),(57,2,1,'1','请问三级安全教育包括哪三级？','[{\"key\":\"A\",\"value\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ec4\\u7ea7\"},{\"key\":\"B\",\"value\":\"\\u56fd\\u5bb6\\u7ea7\\uff0c\\u7701\\u7ea7\\uff0c\\u5e02\\u7ea7\"},{\"key\":\"C\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"}]',3,'B','企业三级','1','1',1640255536,1640257421,1640257421,'/uploads/20211221/4972f68c44bb5a23d89073cec7f97f9c.jpg','2'),(58,2,1,'3','公司级安全教育是否必须参加？','[{\"key\":\"A\",\"value\":\"\\u5bf9\"},{\"key\":\"B\",\"value\":\"\\u9519\"}]',2,'A','必须要学习的','1','1',1640255602,1640257421,1640257421,'','2'),(59,2,1,'1','这个人长得帅不帅？','[{\"key\":\"A\",\"value\":\"\\u5e05\"},{\"key\":\"B\",\"value\":\"\\u4e0d\\u5e05\"}]',2,'A','长得这么好看，肯定帅','1','1',1640255602,1640257552,NULL,'/uploads/20211223/44d6115cf8c0f73dad0a89bd27a87332.jpeg','2'),(60,2,1,'1','请问三级安全教育包括哪三级？','[{\"key\":\"A\",\"value\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ec4\\u7ea7\"},{\"key\":\"B\",\"value\":\"\\u56fd\\u5bb6\\u7ea7\\uff0c\\u7701\\u7ea7\\uff0c\\u5e02\\u7ea7\"},{\"key\":\"C\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"},{\"key\":\"D\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u7ec4\"}]',4,'B','企业三级','1','1',1640255602,1640257415,1640257415,'/uploads/20211223/44d6115cf8c0f73dad0a89bd27a87332.jpeg','2'),(49,2,1,'3','公司级安全教育是否必须参加？','[{\"key\":\"A\",\"value\":\"\\u5bf9\"},{\"key\":\"B\",\"value\":\"\\u9519\"}]',2,'A',NULL,'1','1',1636890538,1636890538,NULL,NULL,'2'),(50,2,1,'1','这个人长得帅不帅？','[{\"key\":\"A\",\"value\":\"\\u5e05\"},{\"key\":\"B\",\"value\":\"\\u4e0d\\u5e05\"}]',2,'A',NULL,'1','1',1636890538,1636890539,NULL,NULL,'2'),(51,2,1,'1','请问三级安全教育包括哪三级？','[{\"key\":\"A\",\"value\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ec4\\u7ea7\"},{\"key\":\"B\",\"value\":\"\\u56fd\\u5bb6\\u7ea7\\uff0c\\u7701\\u7ea7\\uff0c\\u5e02\\u7ea7\"},{\"key\":\"C\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"},{\"key\":\"C\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"}]',4,'B',NULL,'1','1',1636890538,1636890539,NULL,NULL,'2'),(62,2,1,'1','这个人长得帅不帅？','[{\"key\":\"A\",\"value\":\"\\u5e05\"},{\"key\":\"B\",\"value\":\"\\u4e0d\\u5e05\"}]',2,'A','长得这么好看，肯定帅','1','1',1640359511,1640359511,NULL,NULL,'2'),(33,0,0,'1','请问三级安全教育包括哪三级？','[{\"key\":\"A\",\"value\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ec4\\u7ea7\"},{\"key\":\"B\",\"value\":\"\\u56fd\\u5bb6\\u7ea7\\uff0c\\u7701\\u7ea7\\uff0c\\u5e02\\u7ea7\"},{\"key\":\"C\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"},{\"key\":\"\",\"value\":false}]',4,'B',NULL,'1','1',44489,1636889592,1636889592,NULL,'2'),(31,0,0,'1','公司级安全教育是否必须参加？','[{\"key\":\"A\",\"value\":\"\\u5bf9\"},{\"key\":\"B\",\"value\":\"\\u9519\"},{\"key\":\"\",\"value\":false}]',2,'A',NULL,'1','1',44512,1636889592,1636889592,NULL,'2'),(32,0,0,'1','这个人长得帅不帅？','[{\"key\":\"A\",\"value\":\"\\u5e05\"},{\"key\":\"B\",\"value\":\"\\u4e0d\\u5e05\"},{\"key\":\"\",\"value\":false}]',2,'A',NULL,'1','1',44489,1636889592,1636889592,NULL,'2'),(63,2,1,'2','请问三级安全教育包括哪三级？','[{\"key\":\"A\",\"value\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ec4\\u7ea7\"},{\"key\":\"B\",\"value\":\"\\u56fd\\u5bb6\\u7ea7\\uff0c\\u7701\\u7ea7\\uff0c\\u5e02\\u7ea7\"},{\"key\":\"C\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"},{\"key\":\"D\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"}]',4,'A,B','企业三级','1','1',1640359511,1640359511,NULL,NULL,'2'),(46,2,1,'3','公司级安全教育是否必须参加？','[{\"key\":\"A\",\"value\":\"\\u5bf9\"},{\"key\":\"B\",\"value\":\"\\u9519\"}]',2,'A',NULL,'1','1',1636889650,1636889650,NULL,NULL,'2'),(47,2,1,'1','这个人长得帅不帅？','[{\"key\":\"A\",\"value\":\"\\u5e05\"},{\"key\":\"B\",\"value\":\"\\u4e0d\\u5e05\"}]',2,'A',NULL,'1','1',1636889650,1636889650,NULL,NULL,'2'),(48,2,1,'1','请问三级安全教育包括哪三级？','[{\"key\":\"A\",\"value\":\"\\u516c\\u53f8\\u7ea7\\uff0c\\u90e8\\u95e8\\u7ea7\\uff0c\\u73ed\\u7ec4\\u7ea7\"},{\"key\":\"B\",\"value\":\"\\u56fd\\u5bb6\\u7ea7\\uff0c\\u7701\\u7ea7\\uff0c\\u5e02\\u7ea7\"},{\"key\":\"C\",\"value\":\"\\u73ed\\u7ec4\\u7ea7\\uff0c\\u8f66\\u95f4\\u7ea7\\uff0c\\u516c\\u53f8\\u7ec4\"}]',4,'B',NULL,'1','1',1636889650,1636889650,NULL,NULL,'2');
/*!40000 ALTER TABLE `safe_kaoshi_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_kaoshi_subject`
--

DROP TABLE IF EXISTS `safe_kaoshi_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_kaoshi_subject` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL COMMENT '父ID',
  `pname` varchar(45) DEFAULT NULL COMMENT '上级科目',
  `subject_name` varchar(255) NOT NULL COMMENT '科目名称',
  `icon` varchar(100) DEFAULT NULL COMMENT '图片',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `deletetime` int(11) DEFAULT NULL COMMENT '删除时间',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_kaoshi_subject`
--

LOCK TABLES `safe_kaoshi_subject` WRITE;
/*!40000 ALTER TABLE `safe_kaoshi_subject` DISABLE KEYS */;
INSERT INTO `safe_kaoshi_subject` VALUES (1,0,NULL,'三级安全教育','fa fa-bomb',7,1634623850,NULL,'2'),(2,1,'三级安全教育','班组长安全教育','',6,1635817645,NULL,'2'),(3,0,NULL,'特种作业人员教育复审教育','',5,1635817701,NULL,'2'),(4,0,NULL,'全员安全教育','',4,1635817715,NULL,'2'),(5,0,NULL,'复工教育','',3,1635817725,NULL,'2'),(6,0,NULL,'四新教育','fa fa-battery-three-quarters',2,1635817736,NULL,'2'),(7,0,NULL,'其它安全教育','',1,1635817750,NULL,'2'),(9,3,'特种作业人员教育复审教育','11','1',9,1637381112,NULL,'2');
/*!40000 ALTER TABLE `safe_kaoshi_subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_kaoshi_user_exams`
--

DROP TABLE IF EXISTS `safe_kaoshi_user_exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_kaoshi_user_exams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_plan_id` int(11) unsigned NOT NULL COMMENT '用户计划ID',
  `questionsdata` text NOT NULL COMMENT '题目',
  `answersdata` text COMMENT '答案',
  `real_answersdata` text NOT NULL COMMENT '正确答案',
  `scorelistdata` varchar(255) DEFAULT NULL COMMENT '题目得分',
  `exam_pic` mediumblob COMMENT '考卷图片',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总分',
  `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '状态:0=进行中,1=已完成',
  `usetime` int(11) NOT NULL DEFAULT '0' COMMENT '使用时间',
  `starttime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `lasttime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上一次答题时间',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_kaoshi_user_exams`
--

LOCK TABLES `safe_kaoshi_user_exams` WRITE;
/*!40000 ALTER TABLE `safe_kaoshi_user_exams` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_kaoshi_user_exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_kaoshi_user_plan`
--

DROP TABLE IF EXISTS `safe_kaoshi_user_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_kaoshi_user_plan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `plan_id` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态:0=未完成,1=已完成',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `basicid` (`plan_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=238 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_kaoshi_user_plan`
--

LOCK TABLES `safe_kaoshi_user_plan` WRITE;
/*!40000 ALTER TABLE `safe_kaoshi_user_plan` DISABLE KEYS */;
INSERT INTO `safe_kaoshi_user_plan` VALUES (237,20,11,0,NULL);
/*!40000 ALTER TABLE `safe_kaoshi_user_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_kaoshi_wrong`
--

DROP TABLE IF EXISTS `safe_kaoshi_wrong`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_kaoshi_wrong` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `subject_id` int(11) unsigned NOT NULL COMMENT '学科ID',
  `questions_ids` varchar(255) DEFAULT NULL COMMENT '题目ID',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_kaoshi_wrong`
--

LOCK TABLES `safe_kaoshi_wrong` WRITE;
/*!40000 ALTER TABLE `safe_kaoshi_wrong` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_kaoshi_wrong` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_materials_category`
--

DROP TABLE IF EXISTS `safe_materials_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_materials_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `pname` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '父类名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='资料分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_materials_category`
--

LOCK TABLES `safe_materials_category` WRITE;
/*!40000 ALTER TABLE `safe_materials_category` DISABLE KEYS */;
INSERT INTO `safe_materials_category` VALUES (1,0,'安全法规',NULL,'fa fa-battery-three-quarters','','安全法规',1637382128,1637382600,1,'',2),(2,0,'PDF资料','安全法规','fa fa-bold','','PDF资料',1637382334,1637382596,2,'',2),(3,0,'安全生产','无','fa fa-bandcamp','','',1638007948,1638007948,3,'',2),(4,0,'文明施工',NULL,'fa fa-bank','','文明施工',1638008766,1640360141,4,'',2);
/*!40000 ALTER TABLE `safe_materials_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_materials_info`
--

DROP TABLE IF EXISTS `safe_materials_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_materials_info` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `materials_category_id` int(11) DEFAULT NULL COMMENT '分类ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `coverimage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '封面',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '物理路径',
  `user_ids` mediumblob COMMENT '指定学员',
  `user_group_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '指定分组',
  `admin_id` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '创建人',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` enum('hidden','normal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='学习资料';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_materials_info`
--

LOCK TABLES `safe_materials_info` WRITE;
/*!40000 ALTER TABLE `safe_materials_info` DISABLE KEYS */;
INSERT INTO `safe_materials_info` VALUES (1,2,'测试','/uploads/20211120/8b9786448eb3e6c8d56d00c412904f25.png','测试','测试','/uploads/20211120/d9c35fee6fa2fbc6505eda6a73644291.pdf',_binary '20','1',NULL,1637389706,1637389706,NULL,1,'normal',1),(2,2,'法规','/uploads/20211120/44d6115cf8c0f73dad0a89bd27a87332.jpeg','法规','国家安全生产法规','/uploads/20211120/d9c35fee6fa2fbc6505eda6a73644291.pdf',_binary '1','1',NULL,1637395780,1637395780,NULL,2,'normal',1),(3,2,'测试资料','/uploads/20211120/961bed67300fefec24cb5b65b2343cb6.jpg','测试资料','测试资料','/uploads/20211120/7c0287ea55132081fae8b0af618b9ea2.jpg','','','企业管理员',1637396885,1637397193,NULL,3,'normal',1),(4,3,'imya','/uploads/20211120/489af80e5c10bebbe303f58cf14f5529.jpg','sss','dfsdfs','/uploads/20211120/d9c35fee6fa2fbc6505eda6a73644291.pdf',_binary '20,19,5,4,3,1','','企业管理员',1637397299,1638008229,NULL,4,'normal',2),(5,2,'测试2','','要','桍','/uploads/20211224/f13afd9b39fdaad43d00ecdfbe08a41f.xls',_binary '20','','企业管理员',1637399311,1640337392,NULL,5,'normal',2);
/*!40000 ALTER TABLE `safe_materials_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_message_category`
--

DROP TABLE IF EXISTS `safe_message_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_message_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '分类名称',
  `pname` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '父类名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='通告分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_message_category`
--

LOCK TABLES `safe_message_category` WRITE;
/*!40000 ALTER TABLE `safe_message_category` DISABLE KEYS */;
INSERT INTO `safe_message_category` VALUES (1,0,'例检通告',NULL,'fa fa-beer','','正常的检查通知',1638073813,1638074433,1,'',2);
/*!40000 ALTER TABLE `safe_message_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_message_info`
--

DROP TABLE IF EXISTS `safe_message_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_message_info` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL COMMENT '分类ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `coverimage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '封面',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '通知内容',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '物理路径',
  `user_ids` mediumblob COMMENT '指定学员',
  `user_r_ids` mediumblob COMMENT '已阅学员',
  `user_group_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '指定分组',
  `admin_id` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '创建人',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` enum('hidden','normal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='通告内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_message_info`
--

LOCK TABLES `safe_message_info` WRITE;
/*!40000 ALTER TABLE `safe_message_info` DISABLE KEYS */;
INSERT INTO `safe_message_info` VALUES (7,1,'学习通知','','学习通告','参加学习啊','',_binary '20,19,5,4,3,1',_binary '3','','企业管理员',1639221859,1640337220,NULL,7,'normal',2);
/*!40000 ALTER TABLE `safe_message_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_sms`
--

DROP TABLE IF EXISTS `safe_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号',
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IP',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短信验证码表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_sms`
--

LOCK TABLES `safe_sms` WRITE;
/*!40000 ALTER TABLE `safe_sms` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_article`
--

DROP TABLE IF EXISTS `safe_suisunwechat_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '名称',
  `media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '公众号媒体ID',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_article`
--

LOCK TABLES `safe_suisunwechat_article` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_article_detail`
--

DROP TABLE IF EXISTS `safe_suisunwechat_article_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_article_detail` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `article_id` int(10) NOT NULL DEFAULT '0' COMMENT '文章ID',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '标题',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '封面图',
  `thumb_media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '封面图ID',
  `author` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '作者',
  `show_cover` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '是否在文章内容显示封面图片',
  `digest` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '摘要',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '内容',
  `source_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '原文链接',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章内容';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_article_detail`
--

LOCK TABLES `safe_suisunwechat_article_detail` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_article_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_article_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_auto_reply`
--

DROP TABLE IF EXISTS `safe_suisunwechat_auto_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_auto_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `keywordcontent` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' COMMENT '关键词',
  `eventkey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '响应事件',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自动回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_auto_reply`
--

LOCK TABLES `safe_suisunwechat_auto_reply` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_auto_reply` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_auto_reply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_broadcasting`
--

DROP TABLE IF EXISTS `safe_suisunwechat_broadcasting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_broadcasting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `msgid` int(10) DEFAULT '0' COMMENT '信息id',
  `type` enum('text','textandphoto','image','video','voice','article') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'text' COMMENT '消息类型:text=文本,textandphoto=图文,image=图片,video=视频,voice=音频',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '消息内容',
  `openids` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '接收人',
  `tags` longtext CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '接收组',
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '1' COMMENT '状态:0=失败,1=成功',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息群发';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_broadcasting`
--

LOCK TABLES `safe_suisunwechat_broadcasting` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_broadcasting` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_broadcasting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_image`
--

DROP TABLE IF EXISTS `safe_suisunwechat_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_image` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '公众号媒体ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '名称',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '图片',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片消息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_image`
--

LOCK TABLES `safe_suisunwechat_image` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_imagetext`
--

DROP TABLE IF EXISTS `safe_suisunwechat_imagetext`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_imagetext` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `desc` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '摘要',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '封面',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '链接',
  `items` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '图文列表',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图文消息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_imagetext`
--

LOCK TABLES `safe_suisunwechat_imagetext` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_imagetext` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_imagetext` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_lang`
--

DROP TABLE IF EXISTS `safe_suisunwechat_lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_lang` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `lang` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '简称',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '国家',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='语言简称';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_lang`
--

LOCK TABLES `safe_suisunwechat_lang` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_lang` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_lang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_service_admin`
--

DROP TABLE IF EXISTS `safe_suisunwechat_service_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_service_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `openid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客服openid',
  `currentid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '当前服务人员openid',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客服姓名',
  `status` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '状态,0:空闲,1:服务中',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_service_admin`
--

LOCK TABLES `safe_suisunwechat_service_admin` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_service_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_service_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_service_history`
--

DROP TABLE IF EXISTS `safe_suisunwechat_service_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_service_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `content` text COMMENT '发送内容',
  `aopenid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客服openid',
  `uopenid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客户openid',
  `is_admin` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_service_history`
--

LOCK TABLES `safe_suisunwechat_service_history` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_service_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_service_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_service_log`
--

DROP TABLE IF EXISTS `safe_suisunwechat_service_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_service_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `reply` text COMMENT '回复内容',
  `content` text COMMENT '发送内容',
  `aopenid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客服openid',
  `uopenid` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '客户openid',
  `status` enum('0','1') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '状态,0:等待中,1:已回复',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_service_log`
--

LOCK TABLES `safe_suisunwechat_service_log` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_service_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_service_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_video`
--

DROP TABLE IF EXISTS `safe_suisunwechat_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_video` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '公众号媒体ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '名称',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '描述',
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '封面',
  `thumb_media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '封面图媒体ID',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '视频',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频消息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_video`
--

LOCK TABLES `safe_suisunwechat_video` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_video` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_voice`
--

DROP TABLE IF EXISTS `safe_suisunwechat_voice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_voice` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `media_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '公众号媒体ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '名称',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '音频',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='音频消息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_voice`
--

LOCK TABLES `safe_suisunwechat_voice` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_voice` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_voice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_wechat`
--

DROP TABLE IF EXISTS `safe_suisunwechat_wechat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_wechat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` enum('conditional','base') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'base' COMMENT '菜单类型:base=基本菜单,conditional=个性化菜单',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `content` text COMMENT '内容',
  `matchrule` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '个性化信息',
  `menuid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '微信菜单ID',
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0' COMMENT '发布状态:0=未发布,1=已发布',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_wechat`
--

LOCK TABLES `safe_suisunwechat_wechat` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_wechat_config`
--

DROP TABLE IF EXISTS `safe_suisunwechat_wechat_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_wechat_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '配置类型',
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '配置内容',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公众号配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_wechat_config`
--

LOCK TABLES `safe_suisunwechat_wechat_config` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_wechat_fans`
--

DROP TABLE IF EXISTS `safe_suisunwechat_wechat_fans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_wechat_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `nickname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '粉丝昵称',
  `headimgurl` varchar(255) DEFAULT NULL COMMENT '粉丝头像',
  `openid` varchar(255) DEFAULT NULL COMMENT 'openid',
  `sex` int(11) NOT NULL DEFAULT '0' COMMENT '性别',
  `country` varchar(255) DEFAULT NULL COMMENT '国家',
  `province` varchar(255) DEFAULT NULL COMMENT '省',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `tagids` varchar(255) DEFAULT NULL COMMENT '粉丝标签',
  `subscribe` enum('0','1') DEFAULT NULL COMMENT '关注状态:0=取消关注,1=已关注',
  `subscribe_time` int(11) DEFAULT NULL COMMENT '关注时间',
  `createtime` int(11) NOT NULL COMMENT '创建时间',
  `updatetime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='粉丝管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_wechat_fans`
--

LOCK TABLES `safe_suisunwechat_wechat_fans` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_fans` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_fans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_wechat_fans_tag`
--

DROP TABLE IF EXISTS `safe_suisunwechat_wechat_fans_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_wechat_fans_tag` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_id` int(10) DEFAULT NULL COMMENT '标签ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '名称',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_wechat_fans_tag`
--

LOCK TABLES `safe_suisunwechat_wechat_fans_tag` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_fans_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_fans_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_wechat_source`
--

DROP TABLE IF EXISTS `safe_suisunwechat_wechat_source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_wechat_source` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `eventkey` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '事件标识',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '素材名称',
  `type` enum('text','video','image','audio','textandphoto') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'text' COMMENT '消息类型:text=文本,textandphoto=图文,image=图文,video=视频,audio=音频',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '消息内容',
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '1' COMMENT '是否启用:0=不启用,1=启用',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='响应资源';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_wechat_source`
--

LOCK TABLES `safe_suisunwechat_wechat_source` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_source` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_source` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_suisunwechat_wechat_usersummary`
--

DROP TABLE IF EXISTS `safe_suisunwechat_wechat_usersummary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_suisunwechat_wechat_usersummary` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `time` int(10) NOT NULL COMMENT '统计时间',
  `user_source` int(10) NOT NULL COMMENT '用户渠道',
  `new_user` int(10) NOT NULL COMMENT '新增用户',
  `cancel_user` int(10) NOT NULL COMMENT '取消关注',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户数据分析';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_suisunwechat_wechat_usersummary`
--

LOCK TABLES `safe_suisunwechat_wechat_usersummary` WRITE;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_usersummary` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_suisunwechat_wechat_usersummary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_test`
--

DROP TABLE IF EXISTS `safe_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) DEFAULT '0' COMMENT '管理员ID',
  `category_id` int(10) unsigned DEFAULT '0' COMMENT '分类ID(单选)',
  `category_ids` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分类ID(多选)',
  `week` enum('monday','tuesday','wednesday') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '星期(单选):monday=星期一,tuesday=星期二,wednesday=星期三',
  `flag` set('hot','index','recommend') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标志(多选):hot=热门,index=首页,recommend=推荐',
  `genderdata` enum('male','female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'male' COMMENT '性别(单选):male=男,female=女',
  `hobbydata` set('music','reading','swimming') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '爱好(多选):music=音乐,reading=读书,swimming=游泳',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `images` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片组',
  `attachfile` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '附件',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述',
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省市',
  `json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置:key=名称,value=值',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '价格',
  `views` int(10) unsigned DEFAULT '0' COMMENT '点击',
  `startdate` date DEFAULT NULL COMMENT '开始日期',
  `activitytime` datetime DEFAULT NULL COMMENT '活动时间(datetime)',
  `year` year(4) DEFAULT NULL COMMENT '年',
  `times` time DEFAULT NULL COMMENT '时间',
  `refreshtime` int(10) DEFAULT NULL COMMENT '刷新时间(int)',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `switch` tinyint(1) DEFAULT '0' COMMENT '开关',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  `state` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '状态值:0=禁用,1=正常,2=推荐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='测试表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_test`
--

LOCK TABLES `safe_test` WRITE;
/*!40000 ALTER TABLE `safe_test` DISABLE KEYS */;
INSERT INTO `safe_test` VALUES (1,0,12,'12,13','monday','hot,index','male','music,reading','我是一篇测试文章','<p>我是测试内容</p>','/assets/img/avatar.png','/assets/img/avatar.png,/assets/img/qrcode.png','/assets/img/avatar.png','关键字','描述','广西壮族自治区/百色市/平果县','{\"a\":\"1\",\"b\":\"2\"}',0.00,0,'2017-07-10','2017-07-10 18:24:45',2017,'18:24:45',1491635035,1491635035,1491635035,NULL,0,1,'normal','1');
/*!40000 ALTER TABLE `safe_test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_third`
--

DROP TABLE IF EXISTS `safe_third`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_third` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `platform` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '第三方应用',
  `apptype` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '应用类型',
  `unionid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '第三方UNIONID',
  `openname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '第三方会员昵称',
  `openid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '第三方OPENID',
  `access_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT 'AccessToken',
  `refresh_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'RefreshToken',
  `expires_in` int(10) unsigned DEFAULT '0' COMMENT '有效期',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `logintime` int(10) unsigned DEFAULT NULL COMMENT '登录时间',
  `expiretime` int(10) unsigned DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform` (`platform`,`openid`),
  KEY `user_id` (`user_id`,`platform`),
  KEY `unionid` (`platform`,`unionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='第三方登录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_third`
--

LOCK TABLES `safe_third` WRITE;
/*!40000 ALTER TABLE `safe_third` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_third` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_training_category`
--

DROP TABLE IF EXISTS `safe_training_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_training_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `pname` varchar(45) DEFAULT NULL COMMENT '上级科目',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自定义名称',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `weigh` (`weigh`,`id`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='分类管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_training_category`
--

LOCK TABLES `safe_training_category` WRITE;
/*!40000 ALTER TABLE `safe_training_category` DISABLE KEYS */;
INSERT INTO `safe_training_category` VALUES (1,0,NULL,'main','三级安全教育','fa fa-list-ol','','三级教育','',1634364989,1634364989,7,'normal',2),(2,0,NULL,'main','班组长安全教育','fa fa-eercast','','班组长安全教育','',1634365853,1636685001,6,'normal',2),(3,0,NULL,'main','特种作业人员培训复审教育','fa fa-medkit','','特种作业人员培训复审教育','',1634365959,1634378590,5,'normal',2),(4,0,NULL,'main','全员安全教育','fa fa-address-book-o','','全员安全教育','',1634378614,1634378614,4,'normal',2),(5,0,NULL,'main','复工教育','fa fa-battery-full','','复工教育','',1634378640,1634378640,3,'normal',2),(6,0,NULL,'main','四新教育','fa fa-black-tie','','四新教育','',1634378662,1634378662,2,'normal',2),(7,0,NULL,'main','其它安全教育','fa fa-bandcamp','','其它安全教育','',1634378680,1635924413,1,'normal',2),(8,0,NULL,'course','视频课程','fa fa-desktop','','','',1634604314,1634604314,8,'normal',2),(9,8,NULL,'course','自制课程','fa fa-address-book','自制课程','','',1634612830,1634612830,9,'normal',2),(10,8,NULL,'course','宣传片','fa fa-balance-scale','','','',1634626691,1634626691,10,'normal',2),(11,1,NULL,'main','公司级安全教育','fa fa-battery-half','公司级','入厂前安全教育','',1636685045,1636685045,13,'normal',2),(12,1,NULL,'main','部门级安全教育','fa fa-book','部门级安全教育','分配到对应部门前进行的安全教育','',1636685088,1636685088,12,'normal',2),(13,1,NULL,'main','班组级安全教育','fa fa-bed','班组级','入职前的安全教育','',1636685117,1636685117,11,'normal',2),(14,0,NULL,'course','安全法规','','','','',1637314052,1637314052,14,'normal',2),(15,2,NULL,'main','11','1','1','1','',1637380871,1637380871,15,'normal',2);
/*!40000 ALTER TABLE `safe_training_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_training_course`
--

DROP TABLE IF EXISTS `safe_training_course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_training_course` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `training_category_id` int(11) DEFAULT NULL COMMENT '分类ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `videofile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL',
  `duration` int(255) DEFAULT NULL COMMENT '视频时长',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `speaker` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '主讲人',
  `praise` int(10) DEFAULT NULL COMMENT '点赞',
  `playtimes` int(10) DEFAULT NULL COMMENT '播放次数',
  `admin_id` int(10) DEFAULT NULL COMMENT '创建人ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) DEFAULT NULL COMMENT '上传时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` enum('hidden','normal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='课程管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_training_course`
--

LOCK TABLES `safe_training_course` WRITE;
/*!40000 ALTER TABLE `safe_training_course` DISABLE KEYS */;
INSERT INTO `safe_training_course` VALUES (1,8,'在线学习系统介绍','/uploads/20211019/ad7f8daef1e657f7825fb619e4624f21.mp4',13,'系统介绍','一个测试视频+修改','吴俊雷',NULL,36,NULL,1634604539,1638431077,1634604325,NULL,2,'normal',NULL),(2,10,'反电信诈骗宣传片','/uploads/20211019/b7b98b452a1dc924b8893bad8c135ad0.mp4',93,'反电信诈骗宣传片','反电信诈骗宣传片','无',NULL,32,NULL,1634626658,1638431098,1634626619,NULL,1,'normal',NULL),(3,9,'厂级教育','/uploads/20211112/2ecc2e2d31b0aa18c5b731f5b4c1a7c5.mp4',67,'厂级教育','厂级教育','张先生',NULL,11,NULL,1636685252,1638436972,1636685198,NULL,3,'normal',NULL),(4,9,'线下安全教育','/uploads/20211112/2ecc2e2d31b0aa18c5b731f5b4c1a7c5.mp4',67,'线下安全教育','在小会议室完成','张德同',NULL,NULL,NULL,1638239709,1638239709,1638239660,NULL,4,'normal',NULL);
/*!40000 ALTER TABLE `safe_training_course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_training_main`
--

DROP TABLE IF EXISTS `safe_training_main`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_training_main` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `training_category_id` int(11) DEFAULT NULL COMMENT '分类ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `training_course_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '课程ID',
  `duration` int(10) DEFAULT '0' COMMENT '视频时长?',
  `coverimage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '封面',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `user_ids` mediumblob COMMENT '指定学员',
  `user_group_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '指定分组',
  `admin_id` varchar(255) DEFAULT NULL COMMENT '创建人',
  `starttime` int(10) DEFAULT NULL COMMENT '开始时间',
  `endtime` int(10) DEFAULT NULL COMMENT '结束时间',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` enum('hidden','normal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'normal' COMMENT '状态',
  `type` enum('offline','online') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'online' COMMENT '培训形式',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='培训管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_training_main`
--

LOCK TABLES `safe_training_main` WRITE;
/*!40000 ALTER TABLE `safe_training_main` DISABLE KEYS */;
INSERT INTO `safe_training_main` VALUES (5,1,'厂级安全线下培训','3,4',134,'/uploads/20211118/7c0287ea55132081fae8b0af618b9ea2.jpg','厂级安全线下培训','培训时间定在明天下午，小会议室进行。',_binary '20,19,5,4,3,1','','企业管理员',1638251289,1641707289,1637214575,1638427326,NULL,5,'normal','offline',2),(6,1,'厂级安全培训在线培训','3,2,1',173,'/uploads/20211118/ffbdabe1c2b351d6cdc0da2fe8b6d71b.jpg','厂级安全培训在线培训','厂级安全培训在线培训',_binary '20','','企业管理员',1637215372,1639288972,1637215421,1640166377,NULL,6,'normal','online',2),(7,1,'无视频','',0,'/uploads/20211118/489af80e5c10bebbe303f58cf14f5529.jpg','无视频','无视频',_binary '20,19,5,4,3,1','','企业管理员',1637218903,1637823703,1637218935,1637220387,NULL,7,'normal','offline',2),(8,1,'第一次视频在线培训','3',67,'/uploads/20211219/7c0287ea55132081fae8b0af618b9ea2.jpg','第一次','第一次',_binary '19,5,4,3,1,33','','企业管理员',1637221255,1638690055,1637221321,1640354467,NULL,8,'normal','offline',2);
/*!40000 ALTER TABLE `safe_training_main` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_training_main_log`
--

DROP TABLE IF EXISTS `safe_training_main_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_training_main_log` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL COMMENT '用户ID',
  `training_main_id` int(10) DEFAULT NULL COMMENT '培训ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='培训完成记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_training_main_log`
--

LOCK TABLES `safe_training_main_log` WRITE;
/*!40000 ALTER TABLE `safe_training_main_log` DISABLE KEYS */;
INSERT INTO `safe_training_main_log` VALUES (12,1,8,1638437042,1638437042,2),(16,1,5,1638437975,1638437975,2);
/*!40000 ALTER TABLE `safe_training_main_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_training_record`
--

DROP TABLE IF EXISTS `safe_training_record`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_training_record` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL COMMENT '用户ID',
  `training_main_id` int(10) DEFAULT NULL COMMENT '培训ID',
  `training_course_id` int(10) DEFAULT NULL COMMENT '课程ID',
  `studytime` int(10) DEFAULT '0' COMMENT '已学时长',
  `progress` int(3) unsigned DEFAULT '0' COMMENT '学习进度',
  `complete` tinyint(1) DEFAULT '0' COMMENT '是否完成',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='课程学习记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_training_record`
--

LOCK TABLES `safe_training_record` WRITE;
/*!40000 ALTER TABLE `safe_training_record` DISABLE KEYS */;
INSERT INTO `safe_training_record` VALUES (35,1,8,3,67,100,1,1638436977,1638437042,2),(42,1,5,3,13,100,1,1638437949,1638437975,2),(43,1,5,4,13,100,1,1638437949,1638437975,2);
/*!40000 ALTER TABLE `safe_training_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_trouble_area`
--

DROP TABLE IF EXISTS `safe_trouble_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_trouble_area` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '区域ID',
  `area_name` varchar(45) DEFAULT NULL COMMENT '区域名称',
  `area_description` varchar(255) DEFAULT NULL COMMENT '区域描述',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='区域信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_trouble_area`
--

LOCK TABLES `safe_trouble_area` WRITE;
/*!40000 ALTER TABLE `safe_trouble_area` DISABLE KEYS */;
INSERT INTO `safe_trouble_area` VALUES (1,'新城区','海天路与合围区','2');
/*!40000 ALTER TABLE `safe_trouble_area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_trouble_expression`
--

DROP TABLE IF EXISTS `safe_trouble_expression`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_trouble_expression` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '现象ID',
  `trouble_expression` varchar(255) DEFAULT NULL COMMENT '隐患现象',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='隐患现象';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_trouble_expression`
--

LOCK TABLES `safe_trouble_expression` WRITE;
/*!40000 ALTER TABLE `safe_trouble_expression` DISABLE KEYS */;
INSERT INTO `safe_trouble_expression` VALUES (1,'导线破损','2');
/*!40000 ALTER TABLE `safe_trouble_expression` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_trouble_log`
--

DROP TABLE IF EXISTS `safe_trouble_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_trouble_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `main_id` int(10) DEFAULT NULL COMMENT '隐患ID',
  `log_time` int(10) DEFAULT NULL COMMENT '日志时间',
  `log_operator` varchar(45) DEFAULT NULL COMMENT '操作人员',
  `log_content` varchar(45) DEFAULT NULL COMMENT '操作内容',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=527 DEFAULT CHARSET=utf8 COMMENT='隐患处理日志';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_trouble_log`
--

LOCK TABLES `safe_trouble_log` WRITE;
/*!40000 ALTER TABLE `safe_trouble_log` DISABLE KEYS */;
INSERT INTO `safe_trouble_log` VALUES (517,150,1640357275,'张建山','隐患报警信息已经完成接警，并已派单，等待处理...',2),(518,151,1640357275,'张建山','隐患报警信息已经完成接警，并已派单，等待处理...',2),(519,152,1640357275,'张建山','隐患报警信息已经完成接警，并已派单，等待处理...',2),(520,153,1640357275,'张建山','隐患报警信息已经完成接警，并已派单，等待处理...',2),(521,154,1640359591,'张建山','隐患报警信息已经完成接警，并已派单，等待处理...',2),(522,155,1640359591,'张建山','隐患报警信息已经完成接警，并已派单，等待处理...',2),(523,154,1640396115,'安全管理员','隐患报警信息已经取消派单，等待重新派单...',2),(524,153,1640416420,'安全管理员','隐患报警信息已经并案处理，具体进度见：YH2021120005',2),(525,154,1640416589,'安全管理员','隐患报警信息已经并案处理，具体进度见：YH2021120003',2),(526,153,1640416746,'安全管理员','隐患报警信息已经并案处理，具体进度见：YH2021120005',2);
/*!40000 ALTER TABLE `safe_trouble_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_trouble_main`
--

DROP TABLE IF EXISTS `safe_trouble_main`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_trouble_main` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '隐患告警ID',
  `main_code` varchar(45) DEFAULT NULL COMMENT '信息编号',
  `point_id` int(10) DEFAULT NULL COMMENT '隐患点',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `finishtime` int(10) DEFAULT NULL COMMENT '完结时间',
  `firstduration` double DEFAULT NULL COMMENT '首次跟进时间长',
  `finishduration` double DEFAULT NULL COMMENT '完结时长',
  `source_type` enum('0','1','2') DEFAULT NULL COMMENT '告警来源',
  `trouble_type_id` int(10) DEFAULT NULL COMMENT '隐患类型',
  `trouble_expression` varchar(255) DEFAULT NULL COMMENT '隐患现象',
  `description` varchar(255) DEFAULT NULL COMMENT '隐患描述',
  `trouble_pic` mediumblob COMMENT '告警照片',
  `process_pic` mediumblob COMMENT '处理过程照片',
  `finish_pic` mediumblob COMMENT '完结照片',
  `main_status` enum('0','1','2','3','4','5','6','7','8','9') DEFAULT NULL COMMENT '告警状态:0=草稿,1=已接警,2=已派单,3=正在处理,4=任务流转,5=正在复核,6=完成复核,7=完结,8=并案,9=作废',
  `informer_name` varchar(45) DEFAULT NULL COMMENT '报警人姓名',
  `informer` varchar(45) DEFAULT NULL COMMENT '报警人',
  `recevier` varchar(45) DEFAULT NULL COMMENT '处警人',
  `liabler` varchar(45) DEFAULT NULL COMMENT '隐患责任人',
  `processer` varchar(45) DEFAULT NULL COMMENT '处置人',
  `checker` varchar(45) DEFAULT NULL COMMENT '复核人',
  `insider` varchar(45) DEFAULT NULL COMMENT '抄送人',
  `together_id` varchar(255) DEFAULT NULL COMMENT '并案ID',
  `together_code` varchar(255) DEFAULT NULL COMMENT '并案编号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8 COMMENT='隐患告警信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_trouble_main`
--

LOCK TABLES `safe_trouble_main` WRITE;
/*!40000 ALTER TABLE `safe_trouble_main` DISABLE KEYS */;
INSERT INTO `safe_trouble_main` VALUES (150,'YH2021120001',513,1640355873,1640357275,NULL,NULL,0.39,1,'1',5,'测试','测试一下图片',_binary '/uploads/20211224/4c2aa7b0e48ae5830923f140e9bf00cf.jpg',NULL,NULL,'1','01003-李先生(12345678901)','3','张建山','4,3,1,5',NULL,NULL,'4,3',NULL,NULL,NULL,2),(151,'YH2021120002',510,1640356044,1640357275,NULL,NULL,0.34,NULL,'2',5,'再试','',_binary '/uploads/20211224/dfe8ea9eaae4445982361f4a30bf5827.jpg','','','1','01003-李先生(12345678901)','3','张建山','4,3,1,5',NULL,NULL,'4,3',NULL,NULL,NULL,2),(152,'YH2021120003',431,1640355126,1640416589,NULL,NULL,0.6,NULL,'0',6,'导线破损','折','','','','0','01002-朱德同(12345665432)','4,3,3','张建山','4,3,1,5',NULL,NULL,'4,3,1,5','154,154',',YH2021120006,YH2021120006','备注',2),(153,'YH2021120004',511,1640355126,1640357275,NULL,NULL,455654.78,NULL,'1',7,'导线破损','再来一单',_binary '/uploads/20211224/dfe8ea9eaae4445982361f4a30bf5827.jpg,/uploads/20211224/06c3dfdcfefeb2cf298ae09a9913ca34.jpg','','','8','01002-朱德同(12345665432)','4','张建山','4,3,1,5',NULL,NULL,'4,3,1,5,19',NULL,NULL,'好的',2),(154,'YH2021120006',431,1640359578,1640414376,NULL,NULL,0,NULL,'0',6,'导线破损','折',NULL,'','','8','01002-朱德同(12345665432)','3','张建山','',NULL,NULL,'','152','','备注',2),(155,'YH2021120005',511,1640359578,1640416746,NULL,NULL,0,NULL,'1',7,'导线破损','再来一单',NULL,'','','1','01002-朱德同(12345665432)','4,,4,4','张建山','4,3,1,5',NULL,NULL,'4,3,1,5,19','153,153,153',',YH2021120004,YH2021120004','好的',2);
/*!40000 ALTER TABLE `safe_trouble_main` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_trouble_point`
--

DROP TABLE IF EXISTS `safe_trouble_point`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_trouble_point` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `point_code` varchar(45) NOT NULL COMMENT '隐患点编码',
  `point_name` varchar(45) DEFAULT NULL COMMENT '隐患点名称',
  `point_description` varchar(255) DEFAULT NULL COMMENT '隐患点描述',
  `point_address` varchar(255) DEFAULT NULL COMMENT '隐患点地址',
  `point_position` varchar(255) DEFAULT NULL COMMENT '隐患点位置',
  `point_department_id` int(10) DEFAULT NULL COMMENT '所属部门',
  `point_area_id` int(10) DEFAULT NULL COMMENT '所属区域',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=517 DEFAULT CHARSET=utf8 COMMENT='隐患点信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_trouble_point`
--

LOCK TABLES `safe_trouble_point` WRITE;
/*!40000 ALTER TABLE `safe_trouble_point` DISABLE KEYS */;
INSERT INTO `safe_trouble_point` VALUES (365,'01051','35KV变电站','东城35KV变电站','东城纬一路与经一路交叉口','101.37,125.81',3,1,'2'),(366,'01052','36KV变电站','东城36KV变电站','东城纬一路与经一路交叉口','101.37,125.82',3,1,'2'),(367,'01053','37KV变电站','东城37KV变电站','东城纬一路与经一路交叉口','101.37,125.83',3,1,'2'),(368,'01054','38KV变电站','东城38KV变电站','东城纬一路与经一路交叉口','101.37,125.84',3,1,'2'),(369,'01055','39KV变电站','东城39KV变电站','东城纬一路与经一路交叉口','101.37,125.85',3,1,'2'),(370,'01056','40KV变电站','东城40KV变电站','东城纬一路与经一路交叉口','101.37,125.86',3,1,'2'),(371,'01057','41KV变电站','东城41KV变电站','东城纬一路与经一路交叉口','101.37,125.87',3,1,'2'),(372,'01058','42KV变电站','东城42KV变电站','东城纬一路与经一路交叉口','101.37,125.88',3,1,'2'),(373,'01059','43KV变电站','东城43KV变电站','东城纬一路与经一路交叉口','101.37,125.89',3,1,'2'),(374,'01060','44KV变电站','东城44KV变电站','东城纬一路与经一路交叉口','101.37,125.90',3,1,'2'),(375,'01061','45KV变电站','东城45KV变电站','东城纬一路与经一路交叉口','101.37,125.91',3,1,'2'),(376,'01062','46KV变电站','东城46KV变电站','东城纬一路与经一路交叉口','101.37,125.92',3,1,'2'),(377,'01063','47KV变电站','东城47KV变电站','东城纬一路与经一路交叉口','101.37,125.93',3,1,'2'),(378,'01064','48KV变电站','东城48KV变电站','东城纬一路与经一路交叉口','101.37,125.94',3,1,'2'),(379,'01065','49KV变电站','东城49KV变电站','东城纬一路与经一路交叉口','101.37,125.95',3,1,'2'),(380,'01066','50KV变电站','东城50KV变电站','东城纬一路与经一路交叉口','101.37,125.96',3,1,'2'),(381,'01067','51KV变电站','东城51KV变电站','东城纬一路与经一路交叉口','101.37,125.97',3,1,'2'),(382,'01068','52KV变电站','东城52KV变电站','东城纬一路与经一路交叉口','101.37,125.98',3,1,'2'),(383,'01069','53KV变电站','东城53KV变电站','东城纬一路与经一路交叉口','101.37,125.99',3,1,'2'),(384,'01070','54KV变电站','东城54KV变电站','东城纬一路与经一路交叉口','101.37,125.100',3,1,'2'),(385,'01071','55KV变电站','东城55KV变电站','东城纬一路与经一路交叉口','101.37,125.101',3,1,'2'),(386,'01072','56KV变电站','东城56KV变电站','东城纬一路与经一路交叉口','101.37,125.102',3,1,'2'),(387,'01073','57KV变电站','东城57KV变电站','东城纬一路与经一路交叉口','101.37,125.103',3,1,'2'),(388,'01074','58KV变电站','东城58KV变电站','东城纬一路与经一路交叉口','101.37,125.104',3,1,'2'),(389,'01075','59KV变电站','东城59KV变电站','东城纬一路与经一路交叉口','101.37,125.105',3,1,'2'),(390,'01076','60KV变电站','东城60KV变电站','东城纬一路与经一路交叉口','101.37,125.106',3,1,'2'),(391,'01077','61KV变电站','东城61KV变电站','东城纬一路与经一路交叉口','101.37,125.107',3,1,'2'),(392,'01078','62KV变电站','东城62KV变电站','东城纬一路与经一路交叉口','101.37,125.108',3,1,'2'),(393,'01079','63KV变电站','东城63KV变电站','东城纬一路与经一路交叉口','101.37,125.109',3,1,'2'),(394,'01080','64KV变电站','东城64KV变电站','东城纬一路与经一路交叉口','101.37,125.110',3,1,'2'),(395,'01081','65KV变电站','东城65KV变电站','东城纬一路与经一路交叉口','101.37,125.111',3,1,'2'),(396,'01082','66KV变电站','东城66KV变电站','东城纬一路与经一路交叉口','101.37,125.112',3,1,'2'),(397,'01083','67KV变电站','东城67KV变电站','东城纬一路与经一路交叉口','101.37,125.113',3,1,'2'),(398,'01084','68KV变电站','东城68KV变电站','东城纬一路与经一路交叉口','101.37,125.114',3,1,'2'),(399,'01085','69KV变电站','东城69KV变电站','东城纬一路与经一路交叉口','101.37,125.115',3,1,'2'),(400,'01086','70KV变电站','东城70KV变电站','东城纬一路与经一路交叉口','101.37,125.116',3,1,'2'),(401,'01087','71KV变电站','东城71KV变电站','东城纬一路与经一路交叉口','101.37,125.117',3,1,'2'),(402,'01088','72KV变电站','东城72KV变电站','东城纬一路与经一路交叉口','101.37,125.118',3,1,'2'),(403,'01089','73KV变电站','东城73KV变电站','东城纬一路与经一路交叉口','101.37,125.119',3,1,'2'),(404,'01090','74KV变电站','东城74KV变电站','东城纬一路与经一路交叉口','101.37,125.120',3,1,'2'),(405,'01091','75KV变电站','东城75KV变电站','东城纬一路与经一路交叉口','101.37,125.121',3,1,'2'),(406,'01092','76KV变电站','东城76KV变电站','东城纬一路与经一路交叉口','101.37,125.122',3,1,'2'),(407,'01093','77KV变电站','东城77KV变电站','东城纬一路与经一路交叉口','101.37,125.123',3,1,'2'),(408,'01094','78KV变电站','东城78KV变电站','东城纬一路与经一路交叉口','101.37,125.124',3,1,'2'),(409,'01095','79KV变电站','东城79KV变电站','东城纬一路与经一路交叉口','101.37,125.125',3,1,'2'),(410,'01096','80KV变电站','东城80KV变电站','东城纬一路与经一路交叉口','101.37,125.126',3,1,'2'),(411,'01097','81KV变电站','东城81KV变电站','东城纬一路与经一路交叉口','101.37,125.127',3,1,'2'),(412,'01098','82KV变电站','东城82KV变电站','东城纬一路与经一路交叉口','101.37,125.128',3,1,'2'),(413,'01099','83KV变电站','东城83KV变电站','东城纬一路与经一路交叉口','101.37,125.129',3,1,'2'),(414,'01100','84KV变电站','东城84KV变电站','东城纬一路与经一路交叉口','101.37,125.130',3,1,'2'),(415,'01101','85KV变电站','东城85KV变电站','东城纬一路与经一路交叉口','101.37,125.131',3,1,'2'),(416,'01102','86KV变电站','东城86KV变电站','东城纬一路与经一路交叉口','101.37,125.132',3,1,'2'),(417,'01103','87KV变电站','东城87KV变电站','东城纬一路与经一路交叉口','101.37,125.133',3,1,'2'),(418,'01104','88KV变电站','东城88KV变电站','东城纬一路与经一路交叉口','101.37,125.134',3,1,'2'),(419,'01105','89KV变电站','东城89KV变电站','东城纬一路与经一路交叉口','101.37,125.135',3,1,'2'),(420,'01106','90KV变电站','东城90KV变电站','东城纬一路与经一路交叉口','101.37,125.136',3,1,'2'),(421,'01107','91KV变电站','东城91KV变电站','东城纬一路与经一路交叉口','101.37,125.137',3,1,'2'),(422,'01108','92KV变电站','东城92KV变电站','东城纬一路与经一路交叉口','101.37,125.138',3,1,'2'),(423,'01109','93KV变电站','东城93KV变电站','东城纬一路与经一路交叉口','101.37,125.139',3,1,'2'),(424,'01110','94KV变电站','东城94KV变电站','东城纬一路与经一路交叉口','101.37,125.140',3,1,'2'),(425,'01111','95KV变电站','东城95KV变电站','东城纬一路与经一路交叉口','101.37,125.141',3,1,'2'),(426,'01112','96KV变电站','东城96KV变电站','东城纬一路与经一路交叉口','101.37,125.142',3,1,'2'),(427,'01113','97KV变电站','东城97KV变电站','东城纬一路与经一路交叉口','101.37,125.143',3,1,'2'),(428,'01114','98KV变电站','东城98KV变电站','东城纬一路与经一路交叉口','101.37,125.144',3,1,'2'),(429,'01115','99KV变电站','东城99KV变电站','东城纬一路与经一路交叉口','101.37,125.145',3,1,'2'),(430,'01116','100KV变电站','东城100KV变电站','东城纬一路与经一路交叉口','101.37,125.146',3,1,'2'),(431,'01117','101KV变电站','东城101KV变电站','东城纬一路与经一路交叉口','101.37,125.147',3,1,'2'),(432,'01118','102KV变电站','东城102KV变电站','东城纬一路与经一路交叉口','101.37,125.148',3,1,'2'),(433,'01119','103KV变电站','东城103KV变电站','东城纬一路与经一路交叉口','101.37,125.149',3,1,'2'),(434,'01120','104KV变电站','东城104KV变电站','东城纬一路与经一路交叉口','101.37,125.150',3,1,'2'),(435,'01121','105KV变电站','东城105KV变电站','东城纬一路与经一路交叉口','101.37,125.151',3,1,'2'),(436,'01122','106KV变电站','东城106KV变电站','东城纬一路与经一路交叉口','101.37,125.152',3,1,'2'),(437,'01123','107KV变电站','东城107KV变电站','东城纬一路与经一路交叉口','101.37,125.153',3,1,'2'),(438,'01124','108KV变电站','东城108KV变电站','东城纬一路与经一路交叉口','101.37,125.154',3,1,'2'),(439,'01125','109KV变电站','东城109KV变电站','东城纬一路与经一路交叉口','101.37,125.155',3,1,'2'),(440,'01126','110KV变电站','东城110KV变电站','东城纬一路与经一路交叉口','101.37,125.156',3,1,'2'),(441,'01127','111KV变电站','东城111KV变电站','东城纬一路与经一路交叉口','101.37,125.157',3,1,'2'),(442,'01128','112KV变电站','东城112KV变电站','东城纬一路与经一路交叉口','101.37,125.158',3,1,'2'),(443,'01129','113KV变电站','东城113KV变电站','东城纬一路与经一路交叉口','101.37,125.159',3,1,'2'),(444,'01130','114KV变电站','东城114KV变电站','东城纬一路与经一路交叉口','101.37,125.160',3,1,'2'),(445,'01131','115KV变电站','东城115KV变电站','东城纬一路与经一路交叉口','101.37,125.161',3,1,'2'),(446,'01132','116KV变电站','东城116KV变电站','东城纬一路与经一路交叉口','101.37,125.162',3,1,'2'),(447,'01133','117KV变电站','东城117KV变电站','东城纬一路与经一路交叉口','101.37,125.163',3,1,'2'),(448,'01134','118KV变电站','东城118KV变电站','东城纬一路与经一路交叉口','101.37,125.164',3,1,'2'),(449,'01135','119KV变电站','东城119KV变电站','东城纬一路与经一路交叉口','101.37,125.165',3,1,'2'),(450,'01136','120KV变电站','东城120KV变电站','东城纬一路与经一路交叉口','101.37,125.166',3,1,'2'),(451,'01137','121KV变电站','东城121KV变电站','东城纬一路与经一路交叉口','101.37,125.167',3,1,'2'),(452,'01138','122KV变电站','东城122KV变电站','东城纬一路与经一路交叉口','101.37,125.168',3,1,'2'),(453,'01139','123KV变电站','东城123KV变电站','东城纬一路与经一路交叉口','101.37,125.169',3,1,'2'),(454,'01140','124KV变电站','东城124KV变电站','东城纬一路与经一路交叉口','101.37,125.170',3,1,'2'),(455,'01141','125KV变电站','东城125KV变电站','东城纬一路与经一路交叉口','101.37,125.171',3,1,'2'),(456,'01142','126KV变电站','东城126KV变电站','东城纬一路与经一路交叉口','101.37,125.172',3,1,'2'),(457,'01143','127KV变电站','东城127KV变电站','东城纬一路与经一路交叉口','101.37,125.173',3,1,'2'),(458,'01144','128KV变电站','东城128KV变电站','东城纬一路与经一路交叉口','101.37,125.174',3,1,'2'),(459,'01145','129KV变电站','东城129KV变电站','东城纬一路与经一路交叉口','101.37,125.175',3,1,'2'),(460,'01146','130KV变电站','东城130KV变电站','东城纬一路与经一路交叉口','101.37,125.176',3,1,'2'),(461,'01147','131KV变电站','东城131KV变电站','东城纬一路与经一路交叉口','101.37,125.177',3,1,'2'),(462,'01148','132KV变电站','东城132KV变电站','东城纬一路与经一路交叉口','101.37,125.178',3,1,'2'),(463,'01149','133KV变电站','东城133KV变电站','东城纬一路与经一路交叉口','101.37,125.179',3,1,'2'),(464,'01150','134KV变电站','东城134KV变电站','东城纬一路与经一路交叉口','101.37,125.180',3,1,'2'),(465,'01151','135KV变电站','东城135KV变电站','东城纬一路与经一路交叉口','101.37,125.181',3,1,'2'),(466,'01152','136KV变电站','东城136KV变电站','东城纬一路与经一路交叉口','101.37,125.182',7,1,'2'),(467,'01153','137KV变电站','东城137KV变电站','东城纬一路与经一路交叉口','101.37,125.183',3,1,'2'),(468,'01154','138KV变电站','东城138KV变电站','东城纬一路与经一路交叉口','101.37,125.184',3,1,'2'),(469,'01155','139KV变电站','东城139KV变电站','东城纬一路与经一路交叉口','101.37,125.185',3,1,'2'),(470,'01156','140KV变电站','东城140KV变电站','东城纬一路与经一路交叉口','101.37,125.186',3,1,'2'),(471,'01157','141KV变电站','东城141KV变电站','东城纬一路与经一路交叉口','101.37,125.187',3,1,'2'),(472,'01158','142KV变电站','东城142KV变电站','东城纬一路与经一路交叉口','101.37,125.188',3,1,'2'),(473,'01159','143KV变电站','东城143KV变电站','东城纬一路与经一路交叉口','101.37,125.189',3,1,'2'),(474,'01160','144KV变电站','东城144KV变电站','东城纬一路与经一路交叉口','101.37,125.190',3,1,'2'),(475,'01161','145KV变电站','东城145KV变电站','东城纬一路与经一路交叉口','101.37,125.191',3,1,'2'),(476,'01162','146KV变电站','东城146KV变电站','东城纬一路与经一路交叉口','101.37,125.192',3,1,'2'),(477,'01163','147KV变电站','东城147KV变电站','东城纬一路与经一路交叉口','101.37,125.193',3,1,'2'),(478,'01164','148KV变电站','东城148KV变电站','东城纬一路与经一路交叉口','101.37,125.194',3,1,'2'),(479,'01165','149KV变电站','东城149KV变电站','东城纬一路与经一路交叉口','101.37,125.195',3,1,'2'),(480,'01166','150KV变电站','东城150KV变电站','东城纬一路与经一路交叉口','101.37,125.196',3,1,'2'),(481,'01167','151KV变电站','东城151KV变电站','东城纬一路与经一路交叉口','101.37,125.197',3,1,'2'),(482,'01168','152KV变电站','东城152KV变电站','东城纬一路与经一路交叉口','101.37,125.198',3,1,'2'),(483,'01169','153KV变电站','东城153KV变电站','东城纬一路与经一路交叉口','101.37,125.199',3,1,'2'),(484,'01170','154KV变电站','东城154KV变电站','东城纬一路与经一路交叉口','101.37,125.200',3,1,'2'),(485,'01171','155KV变电站','东城155KV变电站','东城纬一路与经一路交叉口','101.37,125.201',3,1,'2'),(486,'01172','156KV变电站','东城156KV变电站','东城纬一路与经一路交叉口','101.37,125.202',3,1,'2'),(487,'01173','157KV变电站','东城157KV变电站','东城纬一路与经一路交叉口','101.37,125.203',3,1,'2'),(488,'01174','158KV变电站','东城158KV变电站','东城纬一路与经一路交叉口','101.37,125.204',3,1,'2'),(489,'01175','159KV变电站','东城159KV变电站','东城纬一路与经一路交叉口','101.37,125.205',3,1,'2'),(490,'01176','160KV变电站','东城160KV变电站','东城纬一路与经一路交叉口','101.37,125.206',3,1,'2'),(491,'01177','161KV变电站','东城161KV变电站','东城纬一路与经一路交叉口','101.37,125.207',3,1,'2'),(492,'01178','162KV变电站','东城162KV变电站','东城纬一路与经一路交叉口','101.37,125.208',3,1,'2'),(493,'01179','163KV变电站','东城163KV变电站','东城纬一路与经一路交叉口','101.37,125.209',3,1,'2'),(494,'01180','164KV变电站','东城164KV变电站','东城纬一路与经一路交叉口','101.37,125.210',3,1,'2'),(495,'01181','165KV变电站','东城165KV变电站','东城纬一路与经一路交叉口','101.37,125.211',3,1,'2'),(496,'01182','166KV变电站','东城166KV变电站','东城纬一路与经一路交叉口','101.37,125.212',3,1,'2'),(497,'01183','167KV变电站','东城167KV变电站','东城纬一路与经一路交叉口','101.37,125.213',3,1,'2'),(498,'01184','168KV变电站','东城168KV变电站','东城纬一路与经一路交叉口','101.37,125.214',3,1,'2'),(499,'01185','169KV变电站','东城169KV变电站','东城纬一路与经一路交叉口','101.37,125.215',3,1,'2'),(500,'01186','170KV变电站','东城170KV变电站','东城纬一路与经一路交叉口','101.37,125.216',3,1,'2'),(501,'01187','171KV变电站','东城171KV变电站','东城纬一路与经一路交叉口','101.37,125.217',3,1,'2'),(502,'01188','172KV变电站','东城172KV变电站','东城纬一路与经一路交叉口','101.37,125.218',3,1,'2'),(503,'01189','173KV变电站','东城173KV变电站','东城纬一路与经一路交叉口','101.37,125.219',3,1,'2'),(504,'01190','174KV变电站','东城174KV变电站','东城纬一路与经一路交叉口','101.37,125.220',3,1,'2'),(505,'01191','175KV变电站','东城175KV变电站','东城纬一路与经一路交叉口','101.37,125.221',3,1,'2'),(506,'01192','176KV变电站','东城176KV变电站','东城纬一路与经一路交叉口','101.37,125.222',3,1,'2'),(507,'01193','177KV变电站','东城177KV变电站','东城纬一路与经一路交叉口','101.37,125.223',3,1,'2'),(508,'01194','178KV变电站','东城178KV变电站','东城纬一路与经一路交叉口','101.37,125.224',3,1,'2'),(509,'01195','179KV变电站','东城179KV变电站','东城纬一路与经一路交叉口','101.37,125.225',3,1,'2'),(510,'01196','180KV变电站','东城180KV变电站','东城纬一路与经一路交叉口','101.37,125.226',3,1,'2'),(511,'01197','181KV变电站','东城181KV变电站','东城纬一路与经一路交叉口','101.37,125.227',3,1,'2'),(512,'01198','182KV变电站','东城182KV变电站','东城纬一路与经一路交叉口','101.37,125.228',3,1,'2'),(513,'01199','183KV变电站','东城183KV变电站','东城纬一路与经一路交叉口','101.37,125.229',3,1,'2'),(514,'01200','184KV变电站','东城184KV变电站','东城纬一路与经一路交叉口','101.37,125.230',3,1,'2'),(515,'01201','185KV变电站','东城185KV变电站','东城纬一路与经一路交叉口','101.37,125.231',3,1,'2'),(516,'01202','186KV变电站','东城186KV变电站','东城纬一路与经一路交叉口','101.37,125.232',3,1,'2');
/*!40000 ALTER TABLE `safe_trouble_point` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_trouble_type`
--

DROP TABLE IF EXISTS `safe_trouble_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_trouble_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '类型ID',
  `trouble_type` varchar(45) NOT NULL COMMENT '隐患类型',
  `plan_content` varchar(45) DEFAULT NULL COMMENT '预案',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='隐患类型';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_trouble_type`
--

LOCK TABLES `safe_trouble_type` WRITE;
/*!40000 ALTER TABLE `safe_trouble_type` DISABLE KEYS */;
INSERT INTO `safe_trouble_type` VALUES (5,'1级隐患','1000','2'),(6,'2级隐患','1100','2'),(7,'3级隐患','1110','2'),(8,'4级隐患','1111','2');
/*!40000 ALTER TABLE `safe_trouble_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_user`
--

DROP TABLE IF EXISTS `safe_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组别ID',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '姓名',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '头像',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '格言',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '安全学分',
  `successions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '连续登录天数',
  `maxsuccessions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '最大连续登录天数',
  `prevtime` int(10) DEFAULT NULL COMMENT '上次登录时间',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `joinip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '加入IP',
  `jointime` int(10) DEFAULT NULL COMMENT '加入时间',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'Token',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  `verification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  `jobnumber` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '工号',
  `department_id` int(10) DEFAULT NULL COMMENT '部门ID',
  `education` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '学历',
  `contact` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '紧急联系人',
  `relation` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '与紧急联系人关系',
  `contactphone` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '紧急联系电话',
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '家庭住址',
  `idcard` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证号',
  `idphoto` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '身份证照片',
  `usertype` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '工作性质',
  `job` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '职务',
  `isspecial` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '是否特种作业人员',
  `studytime` int(10) DEFAULT '0' COMMENT '学员学时',
  `age` int(3) DEFAULT NULL COMMENT '年龄',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_user`
--

LOCK TABLES `safe_user` WRITE;
/*!40000 ALTER TABLE `safe_user` DISABLE KEYS */;
INSERT INTO `safe_user` VALUES (1,1,'admin','吴俊雷','b9629205eb5e1d6b44fd4dd0f4e0099b','K9ZJvX','luckywujl@163.com','13888888888','/uploads/20211019/f73f4f5fd0ac5f753901d5cc86f3172c.jpg',0,1,'1978-12-05','快快乐乐上班',0.00,140,1,1,1639795299,1639821025,'192.168.0.6',0,'127.0.0.1',1491635035,0,1640358963,'','normal','',2,'01001',3,'本科','李得','朋友','13801409049','江苏连云港连云区','32082119781205011X','/uploads/20211019/36b3dde8136a153e8e0620048393b29e.jpg,/uploads/20211020/ffbdabe1c2b351d6cdc0da2fe8b6d71b.jpg','合同工','施工技术员','是',93,43),(3,1,'Mrli','李先生','8c21421050368e5378612eda58a246a6','tNLsJ9','01003@qq.com','12345678901','',0,1,'2021-12-15','',0.00,100,3,3,1640329559,1640330050,'127.0.0.1',0,'',NULL,1634740855,1640330050,'','normal','',2,'01003',4,'中专','张三','朋友','232','连云区','320892','/uploads/20211021/7c0287ea55132081fae8b0af618b9ea2.jpg','临时用工','技术员','否',85,NULL),(4,1,'zdt','朱德同','61a2036ff28554e26f788a68fe112280','nhNYBm','01002@qq.com','12345665432','',0,1,'2021-10-01','',0.00,0,1,1,1638098166,1639656188,'192.168.0.6',0,'',1634807015,1634807015,1639656188,'','normal','',2,'01002',4,'本科','张一山','松松垮垮','12345654321','福地路','3209081929392993','/uploads/20211021/961bed67300fefec24cb5b65b2343cb6.jpg','临时用工','施工安全员','是',NULL,NULL),(5,4,'zf','左飞','13fe06e9936f99163708b79f79647fb5','o2KiZE','01004@qq.com','13801409049','',0,1,'2021-10-01','',0.00,10,1,1,NULL,1634827900,'192.168.0.101',0,'',1634817785,1634817785,1634827900,'','normal','',2,'01004',4,'专科','左东','磊','1333','长江东路','320','','临时用工','安装工','否',NULL,NULL),(19,4,'mrshi1','施金华1','02c94e317a4e914cd949f84d920a8bf2','XeTNrk','01005@qq.com','18932312011','/uploads/20211023/fe9c7059bba548f47e8338d3ff0328f1.jpg',0,0,'1977-06-28','',0.00,0,1,1,1634987026,1634987150,'192.168.0.101',0,'',1634986993,1634986993,1640358963,'','normal','',2,'01005',4,'专科','吴俊雷','配偶','15358691188','颐高广场2-1003','320811197706280040','','合同制用工','工程师','否',NULL,44),(20,4,'zhangde','张昨','5246d2a3a5939518d0bfe1fa0958463d','chSK3N','15358690088@qq.com','15358690088','',0,1,'1986-05-09','',0.00,20,1,2,1640424038,1640424104,'127.0.0.1',0,'',1636683993,1636683993,1640424104,'','normal','',2,'03001',8,'大专','张','大','12345678901','承德公馆','320811198605090025','','临时工','施工','否',NULL,35),(33,4,'zhangjianshan','张建山','48c671492d2fa0983afd59e7924daf42','nbah78','1234895@qq.com','18932312010','',0,1,'1978-12-05','',0.00,1,1,1,NULL,NULL,'',0,'',1640308857,1640308857,1640428004,'','normal','',2,'04001',3,'专科','徐斌','朋友','15358691188','淮阴区','320821197812050110','','临时工','施工安全员','否',2,43);
/*!40000 ALTER TABLE `safe_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_user_department`
--

DROP TABLE IF EXISTS `safe_user_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_user_department` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `pname` varchar(30) DEFAULT NULL COMMENT '上级部门',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自定义名称',
  `leader` varchar(45) DEFAULT NULL COMMENT '部门负责人',
  `person` varchar(45) DEFAULT NULL COMMENT '部门安全员',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  `company_id` varchar(45) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='部门表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_user_department`
--

LOCK TABLES `safe_user_department` WRITE;
/*!40000 ALTER TABLE `safe_user_department` DISABLE KEYS */;
INSERT INTO `safe_user_department` VALUES (1,0,NULL,'企业部门','能源一部','','','','',NULL,NULL,1634370549,1634802189,1,'normal','2'),(2,1,'能源一部','企业部门','施工一队','fa fa-barcode','','','','20','19',1634452977,1638599978,2,'normal','2'),(3,2,'施工一队','企业部门','施工一组','','','','','1,5','4,3',1634454003,1638880589,3,'normal','2'),(4,1,'能源一部','企业部门','施工二组','fa fa-battery','','','','1,20','4',1634455405,1638599831,4,'normal','2'),(6,0,NULL,'企业部门','工程部','fa fa-adjust','工程部','','',NULL,NULL,1634656484,1634656484,6,'normal','3'),(7,4,'施工二组','企业部门','大吃大喝','fa fa-address-book-o','大五','大','','4','',1636680568,1638599941,7,'normal','2'),(8,1,'能源一部','企业部门','施工三组','fa fa-adjust','张得','负责施工及维护','','4,1','4,5',1636683689,1638587612,8,'normal','2');
/*!40000 ALTER TABLE `safe_user_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_user_group`
--

DROP TABLE IF EXISTS `safe_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '权限节点',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '状态',
  `company_id` int(10) DEFAULT NULL COMMENT '数据归属',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_user_group`
--

LOCK TABLES `safe_user_group` WRITE;
/*!40000 ALTER TABLE `safe_user_group` DISABLE KEYS */;
INSERT INTO `safe_user_group` VALUES (1,'默认组','1,2,3,4,5,6,7,8,9,10,11,12',1491635035,1491635035,'normal',2),(2,'新入职组','',1634724978,1634724978,'normal',2),(3,'待转正组','',1634725047,1634725047,'normal',2),(4,'临时作业组','1,3,5,6,7,8',1636683753,1636683753,'normal',2);
/*!40000 ALTER TABLE `safe_user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_user_money_log`
--

DROP TABLE IF EXISTS `safe_user_money_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_user_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员余额变动表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_user_money_log`
--

LOCK TABLES `safe_user_money_log` WRITE;
/*!40000 ALTER TABLE `safe_user_money_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_user_money_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_user_rule`
--

DROP TABLE IF EXISTS `safe_user_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_user_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL COMMENT '父ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名称',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标题',
  `remark` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `ismenu` tinyint(1) DEFAULT NULL COMMENT '是否菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员规则表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_user_rule`
--

LOCK TABLES `safe_user_rule` WRITE;
/*!40000 ALTER TABLE `safe_user_rule` DISABLE KEYS */;
INSERT INTO `safe_user_rule` VALUES (1,0,'index','Frontend','',1,1491635035,1491635035,1,'normal'),(2,0,'api','API Interface','',1,1491635035,1491635035,2,'normal'),(3,1,'user','User Module','',1,1491635035,1491635035,12,'normal'),(4,2,'user','User Module','',1,1491635035,1491635035,11,'normal'),(5,3,'index/user/login','Login','',0,1491635035,1491635035,5,'normal'),(6,3,'index/user/register','Register','',0,1491635035,1491635035,7,'normal'),(7,3,'index/user/index','User Center','',0,1491635035,1491635035,9,'normal'),(8,3,'index/user/profile','Profile','',0,1491635035,1491635035,4,'normal'),(9,4,'api/user/login','Login','',0,1491635035,1491635035,6,'normal'),(10,4,'api/user/register','Register','',0,1491635035,1491635035,8,'normal'),(11,4,'api/user/index','User Center','',0,1491635035,1491635035,10,'normal'),(12,4,'api/user/profile','Profile','',0,1491635035,1491635035,3,'normal');
/*!40000 ALTER TABLE `safe_user_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_user_score_log`
--

DROP TABLE IF EXISTS `safe_user_score_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_user_score_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '变更积分',
  `before` int(10) NOT NULL DEFAULT '0' COMMENT '变更前积分',
  `after` int(10) NOT NULL DEFAULT '0' COMMENT '变更后积分',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员积分变动表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_user_score_log`
--

LOCK TABLES `safe_user_score_log` WRITE;
/*!40000 ALTER TABLE `safe_user_score_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_user_score_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_user_token`
--

DROP TABLE IF EXISTS `safe_user_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_user_token` (
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `expiretime` int(10) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员Token表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_user_token`
--

LOCK TABLES `safe_user_token` WRITE;
/*!40000 ALTER TABLE `safe_user_token` DISABLE KEYS */;
INSERT INTO `safe_user_token` VALUES ('0b15953788fc1df6709b8047593a580ccfbf41d3',3,1639299080,1641891080),('2e9bc8cb58b6b7cb1121fa375823224537effb88',20,1638178483,1640770483),('353a580b101631f5d091d198148c211b4f5323d1',3,1638154729,1640746729),('49445c8f15c09c74bd5959580d17601a84f01df8',3,1638954081,1641546081),('84d33aeecb851973487a1e150e21ac7ec8a4b92c',3,1639825889,1642417889),('8e48130d4113851a7a83d94de11bab89ca3edf09',3,1640164866,1642756866),('9dd69171dfffc49e73825b210d2ee429271d1540',3,1639297903,1641889903),('9f6ca83fd60e21a12e0a635b0165fce7fe73ebdf',3,1640167380,1642759380),('a8a05eabac6f8e7800bfdf1a0f69438a24fbc3cb',20,1638954634,1641546634),('b873bd5433268e78c582c1fa4bd925b67a61533c',20,1640424104,1643016104),('c256533bca794278448e204c46f5316a13f0ced6',1,1638431311,1641023311),('cd0d26988bd55caa43ae2d56af8450d124be82fd',20,1640421646,1643013646),('cdcaec260da467dee87f83ad5b3a03d84dda35d6',20,1638417889,1641009889),('d0737b49ef0b427e86028427597b3c6a0f857151',3,1640329559,1642921559);
/*!40000 ALTER TABLE `safe_user_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `safe_version`
--

DROP TABLE IF EXISTS `safe_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `safe_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `oldversion` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '旧版本号',
  `newversion` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '新版本号',
  `packagesize` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包大小',
  `content` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '升级内容',
  `downloadurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '下载地址',
  `enforce` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '强制更新',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='版本表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `safe_version`
--

LOCK TABLES `safe_version` WRITE;
/*!40000 ALTER TABLE `safe_version` DISABLE KEYS */;
/*!40000 ALTER TABLE `safe_version` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-12-25 19:50:21
