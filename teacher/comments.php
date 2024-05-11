<?php

// Inclure le fichier de connexion à la base de données
include '../components/connect.php';

// Vérifier si le cookie 'tutor_id' est défini, sinon rediriger vers la page de connexion
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}

// Vérifier si le formulaire d'ajout de commentaire est soumis et si le champ de texte du commentaire est rempli
if(isset($_POST['add_comment']) && isset($_POST['comment_box'])){

    if($tutor_id != ''){ 
        $id = unique_id(); // Générer un identifiant unique pour le nouveau commentaire
        $comment_box = $_POST['comment_box'];
        $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING); // Filtrer le contenu du commentaire
        $content_id = $_POST['content_id'];
        $content_id = filter_var($content_id, FILTER_SANITIZE_STRING); // Filtrer l'ID du contenu
        
        // Vérifier si l'ID du contenu est valide
        $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
        $select_content->execute([$content_id]);
        $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
        if($select_content->rowCount() > 0){
            // Récupérer l'ID du tuteur associé au contenu
            $tutor_id = $fetch_content['tutor_id'];
            
            // Déterminer l'ID parent pour le nouveau commentaire
            $parent_id = null; // Initialiser l'ID parent à null par défaut
            $select_last_comment = $conn->prepare("SELECT id FROM `comments` WHERE content_id = ? ORDER BY date DESC LIMIT 1");
            $select_last_comment->execute([$content_id]);
            $fetch_last_comment = $select_last_comment->fetch(PDO::FETCH_ASSOC);
            if($select_last_comment->rowCount() > 0){
                $parent_id = $fetch_last_comment['id']; // Définir l'ID parent comme l'ID du dernier commentaire
            }
            
            // Insérer le nouveau commentaire
            $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, tutor_id, comment, parent_id) VALUES(?,?,?,?,?,?)");
            $insert_comment->execute([$id, $content_id, null, $tutor_id, $comment_box, $parent_id]);
            $message[] = 'New comment added!';
            
            // Rediriger vers la même page pour éviter la resoumission du formulaire lors du rafraîchissement de la page
            header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
            exit();
        }else{
            $message[] = 'Invalid content ID!';
        }
    }else{
        $message[] = 'Please login first!';
    }
}

// Vérifier si le formulaire de suppression de commentaire est soumis
if(isset($_POST['delete_comment'])){

   $delete_id = $_POST['comment_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING); // Filtrer l'ID du commentaire à supprimer

   // Vérifier si le commentaire existe
   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
   $verify_comment->execute([$delete_id]);

   if($verify_comment->rowCount() > 0){
      // Supprimer le commentaire
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'Comment deleted successfully!';
   }else{
      $message[] = 'Comment already deleted!';
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <!-- Lien CDN vers Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Lien vers le fichier CSS personnalisé -->
    <link rel="stylesheet" href="../css/teacher_style.css">

</head>

<body>

    <!-- Inclure l'en-tête du professeur -->
    <?php include '../components/teacher_header.php'; ?>

    <section class="comments">

        <h1 class="heading">User Comments</h1>

        <div class="show-comments">
            <?php
         // Sélectionner les commentaires associés au tuteur connecté
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
         $select_comments->execute([$tutor_id]);
         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
               // Récupérer les détails du contenu associé à chaque commentaire
               $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
               $select_content->execute([$fetch_comment['content_id']]);
               $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
      ?>
            <div class="box" style="<?php if($fetch_comment['tutor_id'] == $tutor_id){echo 'order:-1;';} ?>">
                <div class="content"><span><?= $fetch_comment['date']; ?></span>
                    <p> - <?= $fetch_content['title']; ?> - </p><a
                        href="view_content.php?get_id=<?= $fetch_content['id']; ?>">View content</a>
                </div>
                <p class="text"><?= $fetch_comment['comment']; ?></p>
                <!-- Formulaire pour ajouter un nouveau commentaire -->
                <form action="" method="post" class="add-comment">
                 <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>">
                 <textarea name="comment_box" required placeholder="Write your comment..." maxlength="1000" cols="30" rows="10"></textarea>
                 <input type="submit" value="Add Comment" name="add_comment" class="inline-btn">
               </form>

   <!--     <form action="" method="post">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
            <button type="submit" name="delete_comment" class="inline-delete-btn"
            onclick="return confirm('Delete this comment?');">Delete a Comment</button>
        </form>-->
            </div>
            <?php
       }
      }else{
         echo '<p class="empty">No comments added yet!</p>';
      }
      ?>
        </div>

    </section>

    <script src="../js/teacher_script.js"></script>

</body>

</html>
