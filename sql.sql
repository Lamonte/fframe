CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permname` varchar(40) NOT NULL,
  `description` varchar(140) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `permissions` (`id`, `permname`, `description`) VALUES
(1, 'admin_access', 'Has access to the administration panel.');

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `roles` (`id`, `rolename`) VALUES
(1, 'owner'),
(2, 'admin'),
(3, 'user');

CREATE TABLE IF NOT EXISTS `role_perms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleid` int(11) NOT NULL,
  `permid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `role_perms` (`id`, `roleid`, `permid`) VALUES
(1, 1, 1);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- pw is: test (lol)

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'Lamonte', '098f6bcd4621d373cade4e832627b4f6');

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

INSERT INTO `user_roles` (`id`, `userid`, `roleid`) VALUES
(1, 1, 1),
(2, 1, 2);
