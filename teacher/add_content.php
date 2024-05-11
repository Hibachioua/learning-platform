<?php

// Inclusion du fichier de connexion à la base de données
include '../components/connect.php';

// Vérification si le cookie tutor_id est défini, sinon redirection vers la page de connexion
if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
    exit(); // Assurez-vous que le script s'arrête après la redirection
}

// Vérification si le formulaire est soumis
if (isset($_POST['submit'])) {

    // Génération d'un identifiant unique pour le cours
    $id = unique_id();
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING); // Nettoyage du statut
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING); // Nettoyage du titre
    $description = $_POST['description'];
    $description = filter_var($description, FILTER_SANITIZE_STRING); // Nettoyage de la description
    $playlist = $_POST['playlist'];
    $playlist = filter_var($playlist, FILTER_SANITIZE_STRING); // Nettoyage de la playlist
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

    // Traitement de l'image (thumb)
    $thumb = $_FILES['thumb']['name'];
    $thumb = filter_var($thumb, FILTER_SANITIZE_STRING); // Nettoyage du nom du fichier
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename_thumb = unique_id() . '.' . $thumb_ext;
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_folder = '../uploaded_files/' . $rename_thumb;

    // Traitement de la vidéo (ou autre document)
    $video = $_FILES['video']['name'];
    $video = filter_var($video, FILTER_SANITIZE_STRING); // Nettoyage du nom du fichier
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
        $message[] = 'Nouveau cours téléchargé !';
    } else {
        $message[] = 'Veuillez remplir les champs requis : Statut de la vidéo et Titre de la vidéo.';
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>

    <!-- Lien CDN pour Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Lien du fichier CSS personnalisé -->
    <link rel="stylesheet" href="../css/teacher_style.css">
</head>

<body>

    <?php include '../components/teacher_header.php'; ?>

    <section class="video-form">
        <h1 class="heading">Télécharger du contenu</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <p>Statut du cours <span>*</span></p>
            <select name="status" class="box" required>
                <option value="" selected disabled>Statut</option>
                <option value="active">Actif</option>
                <option value="deactive">Désactivé</option>
            </select>
            <p>Titre du cours <span>*</span></p>
            <input type="text" name="title" maxlength="100" required placeholder="Entrez le titre du cours" class="box">
            <p>Pré-requis</p>
            <textarea name="prerequisites" class="box" placeholder="Entrez les pré-requis" maxlength="1000" cols="30" rows="10"></textarea>
            <!-- Champ pour les mots-clés -->
            <p>Mots-clés : (Séparer plusieurs mots-clés par des virgules ,)</p>
            <input type="text" id="keywords" name="keywords" placeholder="Entrez les mots-clés pour le cours" class="box" required>
            <p>Description du cours</p>
            <textarea name="description" class="box" placeholder="Rédigez la description" maxlength="1000" cols="30" rows="10"></textarea>
            <p>Playlist du cours</p>
            <select name="playlist" class="box">
                <option value="" disabled selected>Sélectionnez une playlist</option>
                <?php
                // Récupération des playlists pour le tutor_id actuel
                $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
                $select_playlists->execute([$tutor_id]);
                if ($select_playlists->rowCount() > 0) {
                    while ($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
                <?php
                    }
                } else {
                    echo '<option value="" disabled>Aucune playlist créée pour le moment !</option>';
                }
                ?>
            </select>
            <p>Sélectionner une image</p>
            <input type="file" name="thumb" accept="image/*" class="box">
            <p>Sélectionner un document (pdf/ppt/autre)</p>
            <input type="file" name="video" accept="*" class="box">
            <input type="submit" value="Télécharger le document" name="submit" class="btn">
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
