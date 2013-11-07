SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `restapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `token` char(80) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `users`
--

-- default password is mypass
-- http digest authentication
-- password hashing mechanism is: MD5(username:realm:password)

INSERT INTO `users` (`id`, `username`, `password`, `token`, `last_update`, `last_access`) VALUES
(1, 'admin', 'ba728c48f7bc3e4f076030f9a7f56805', '720c9cb333f459c02dc4256347f2a6484730c7cbcba822627e12ed9c1a9f9f8500cce0a7fe1e637d', '2013-11-07 04:22:08', '2013-11-07 12:22:08');
