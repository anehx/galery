USE `galery`;

SET CHARSET 'utf8';

CREATE TABLE `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
);

CREATE TABLE `galery` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id_name_UNIQUE` (`user_id`, `name`),
    FOREIGN KEY(`user_id`) REFERENCES user(`id`) ON DELETE CASCADE
);

CREATE TABLE `image` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `galery_id` INT NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name_UNIQUE` (`name`),
    FOREIGN KEY(`galery_id`) REFERENCES galery(`id`) ON DELETE CASCADE
);

CREATE TABLE `tag` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `user_id_name_UNIQUE` (`user_id`, `name`),
    FOREIGN KEY(`user_id`) REFERENCES user(`id`) ON DELETE CASCADE
);

CREATE TABLE `image_tag` (
    `image_id` INT NOT NULL,
    `tag_id` INT NOT NULL,
    PRIMARY KEY (`image_id`, `tag_id`),
    FOREIGN KEY(`image_id`) REFERENCES image(`id`) ON DELETE CASCADE,
    FOREIGN KEY(`tag_id`) REFERENCES tag(`id`) ON DELETE CASCADE
);
