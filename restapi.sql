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
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `users`
--

-- default password is mypass
-- http digest authentication
-- password hashing mechanism is: MD5(username:realm:password)

INSERT INTO `users` (`id`, `username`, `password`, `token`, `last_update`) VALUES
(1, 'admin', 'ba728c48f7bc3e4f076030f9a7f56805', '34f09cf61f0069f99dac5ce21acb056b577f8e0a86b8b5d468602fa0870fa51636a3c7504e133bbf', '2013-10-17 09:17:48');
