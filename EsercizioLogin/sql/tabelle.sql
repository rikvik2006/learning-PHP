use bussanoriccardo;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)

INSERT INTO user (username, email, password) VALUES ('rikvik2006', 'r@r.com', '$2y$10$o1ygi0ARzFa.B3FAcO8DHudrIIDPg9ihqtq7e3mBfj4PbB8xrc8be');