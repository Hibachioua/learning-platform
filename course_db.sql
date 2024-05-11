
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `admin` (`id`, `name`, `email`, `password`, `image`) VALUES
(1, 'Admin', 'chioua.hiba1@gmail.com', 'ab28cfc74820d6462adabc4f2c4221b803a83507', 'cDM2EJ51so4lEcXg3O0M.avif');

CREATE TABLE `Announcements` (
  `announcement_id` int(11) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'deactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `bookmark` (
  `user_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `comments` (
  `id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `content` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `prerequisites` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `video` varchar(100) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `content` (`id`, `tutor_id`, `playlist_id`, `title`, `description`, `prerequisites`, `keywords`, `video`, `thumb`, `date`, `status`) VALUES
('MNTBzCBOoGfP7ii5HRoG', '1', 'X4fKXwWjtOO7rV3iZ6Om', 'Chapitre 1: Les pointeurs', 'Après ce cours,vous devez être capables de travailler avec les pointeurs.', 'Vous devez avoir des notions sur les tableaux.', 'C avancé,Tableaux,Pointeurs', 'gZ24YPQaQjtbjlHBJNL9.pdf', 'ScrZH8BgX3om5nNOCLPf.png', '2024-05-11', 'active');


CREATE TABLE `deletion_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `deletion_tutors` (
  `request_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `tutor_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `playlist` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'deactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `playlist` (`id`, `tutor_id`, `title`, `description`, `thumb`, `date`, `status`) VALUES
('X4fKXwWjtOO7rV3iZ6Om', '1', 'C Avancé', 'Dans ce cours, vous serez capables de comprendre tous ce qui est en relation avec le langage de programmation C', 'cJz0NyuulJnFrzCiVzAE.png', '2024-05-11', 'active');



CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `tutors` (`id`, `name`, `email`, `password`, `image`) VALUES
(1, 'Khalid Manssouri', 'khalid@gmail.com', '188a381a68579ab6419f6d0d1be2d01eb3158b32', 'RzfxlTJNEcjGMoWFdaFJ.jpg');



CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`) VALUES
(1, 'Ahmed Jaber', 'ahmed.jaber@gmail.com', 'c38ae1eb311400460911a30ad83ae2513ba00cad', '54O4Il1xiSkAPQcDzdDa.jpg');


ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `Announcements`
  ADD PRIMARY KEY (`announcement_id`);


ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `Announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

