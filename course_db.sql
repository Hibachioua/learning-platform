--Creating the necessary tables
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `bookmark` (
  `user_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `comments` (
  `id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `content` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `video` varchar(100) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE `playlist` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `tutors` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(90) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `comment_replies` (
  `comment_id` INT NOT NULL AUTO_INCREMENT,
  `tutor_id` INT NOT NULL ,
  `reply_content` varchar(888) NOT NULL ,
  PRIMARY KEY(`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `deletion_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `Announcements` (
    `announcement_id` INT PRIMARY KEY AUTO_INCREMENT,
    `tutor_id` varchar(20) NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `content` TEXT NOT NULL,
    `status` varchar(20) NOT NULL DEFAULT 'deactive',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `deletion_tutors` (
  `request_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `tutor_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--insert admin
INSERT INTO `admin`( `name`, `email`, `password`, `image`) VALUES ('Admin','chioua.hiba1@gmail.com','ab28cfc74820d6462adabc4f2c4221b803a83507','cDM2EJ51so4lEcXg3O0M.avif');
COMMIT;





password:
admin@

ALTER TABLE `content`
ADD COLUMN `prerequisites` TEXT NOT NULL AFTER `description`;

ALTER TABLE `content`
ADD COLUMN `keywords` VARCHAR(255) NOT NULL AFTER `description`;

-- ajout de colonne
ALTER TABLE deletion_tutors
ADD COLUMN admin_id INT;


DROP TABLE course_db.comment_replies;

ALTER TABLE course_db.comments
ADD COLUMN parent_id varchar(250);



ALTER TABLE course_db.comments MODIFY date TIMESTAMP;

ALTER TABLE `comments` CHANGE `user_id` `user_id` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
