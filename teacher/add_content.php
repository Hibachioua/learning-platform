<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

if (isset($_POST['submit'])) {

    $id = unique_id();
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $playlist = $_POST['playlist'];
    $playlist = filter_var($playlist, FILTER_SANITIZE_STRING);
    $prerequisites = $_POST['prerequisites']; // Ajout de la récupération des prérequis

    // Nouvelle variable pour stocker les mots-clés
    $keywords = '';

    // Vérifier si des mots-clés ont été saisis
    if (isset($_POST['keywords'])) {
        $keywords = $_POST['keywords'];
    }

    // Nettoyer les mots-clés et les séparer par des virgules
    $keywords = filter_var($keywords, FILTER_SANITIZE_STRING);
    $keywordsArray = explode(',', $keywords);

    // Vérifier si des mots-clés ont été saisis
    if (!empty($keywordsArray)) {
        // Concaténer les mots-clés avec des virgules
        $keywords = implode(',', $keywordsArray);
    }

    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING);
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename_thumb = unique_id() . '.' . $thumb_ext;
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_folder = '../uploaded_files/' . $rename_thumb;

    $video = $_FILES['video']['name'];
    $video = filter_var($video, FILTER_SANITIZE_STRING);
    $video_ext = pathinfo($video, PATHINFO_EXTENSION);
    $rename_video = unique_id() . '.' . $video_ext;
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = '../uploaded_files/' . $rename_video;

    // Vérification du téléchargement des fichiers (thumb et video)
    $thumb_uploaded = move_uploaded_file($thumb_tmp_name, $thumb_folder);
    $video_uploaded = move_uploaded_file($video_tmp_name, $video_folder);

    // Vérification des champs requis et insertion des données dans la base de données
    if ($status !== '' && $title !== '') {
        $add_content = $conn->prepare("INSERT INTO `content`(id, tutor_id, playlist_id, title, description, video, thumb, status, prerequisites, keywords) VALUES(?,?,?,?,?,?,?,?,?,?)");
        $add_content->execute([$id, $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $status, $prerequisites, $keywords]);
        $message[] = 'New course uploaded!';
    } else {
        $message[] = 'Please fill in the required fields: Video Status and Video Title.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../css/teacher_style.css">
</head>

<body>

    <?php include '../components/teacher_header.php'; ?>

    <section class="video-form">
        <h1 class="heading">Upload Content</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <p>Course Status <span>*</span></p>
            <select name="status" class="box" required>
                <option value="" selected disabled>Status</option>
                <option value="active">Active</option>
                <option value="deactive">Desactive</option>
            </select>
            <p>Course Title <span>*</span></p>
            <input type="text" name="title" maxlength="100" required placeholder="Enter course title" class="box">
            <p>Prerequisites</p>
            <textarea name="prerequisites" class="box" placeholder="Enter prerequisites" maxlength="1000" cols="30"
                rows="10"></textarea>
            <!-- Champ pour les mots-clés -->
            <p>Keywords: (Separate multiple keywords with commas ,)</p>
            <input type="text" id="keywords" name="keywords" placeholder="Enter keywords for the course" class="box"
                required>
            <p>Course Description</p>
            <textarea name="description" class="box" placeholder="Write description" maxlength="1000" cols="30"
                rows="10"></textarea>
            <p>Course Playlist</p>
            <select name="playlist" class="box">
                <option value="" disabled selected>Select playlist</option>
                <?php
                $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
                $select_playlists->execute([$tutor_id]);
                if ($select_playlists->rowCount() > 0) {
                    while ($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
                <?php
                    }
                } else {
                    echo '<option value="" disabled>No playlist created yet!</option>';
                }
                ?>
            </select>
            <p>Select Picture</p>
            <input type="file" name="thumb" accept="image/*" class="box">
            <p>Select Document (pdf/ppt/else)</p>
            <input type="file" name="video" accept="*" class="box">
            <input type="submit" value="Upload Document" name="submit" class="btn">
        </form>
        <!-- Affichage des messages -->
        <?php if (isset($message)) : ?>
        <div class="message">
            <?php foreach ($message as $msg) : ?>
            <p><?= $msg; ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>

    <script src="../js/teacher_script.js"></script>

</body>

</html>