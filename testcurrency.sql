DROP TABLE IF EXISTS `oc_user`;
CREATE TABLE IF NOT EXISTS `oc_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(9) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `image` varchar(255) NOT NULL,
  `code` varchar(40) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `oc_user` (`user_id`, `user_group_id`, `username`, `password`, `salt`, `firstname`, `lastname`, `email`, `image`, `code`, `ip`, `status`, `date_added`) VALUES
	(1, 1, 'admin', 'f03cb719f8552153299c5412683457f422325cca', 'KMBR1le92', 'John', 'Doe', 'dollar20@mail.ru', '', '', '::1', 1, '2018-04-04 08:01:30');
    
DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `valuteID` varchar(6) NOT NULL COMMENT 'идентификатор валюты',
  `numCode` varchar(3) NOT NULL COMMENT 'числовой код валюты',
  `charCode` varchar(3) NOT NULL COMMENT 'буквенный код валюты',
  `name` varchar(50) NOT NULL COMMENT 'наименование валюты',
  `value` double(15,4) NOT NULL COMMENT 'значение курса',
  `date` date NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
