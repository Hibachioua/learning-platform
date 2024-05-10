<?php

include '../components/connect.php';

// Vérification si le formulaire de suppression a été soumis
if(isset($_POST['delete'])){
   // Récupération de l'ID de la playlist à supprimer
   $delete_id = $_POST['playlist_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING); 

   // Vérification si la playlist existe dans la base de données
   $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
   $verify_playlist->execute([$delete_id]);

   if($verify_playlist->rowCount() > 0){
      // Suppression de la vignette de la playlist
      $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
      $delete_playlist_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded_files/'.$fetch_thumb['thumb']);
      
      // Suppression des signets associés à la playlist
      $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
      $delete_bookmark->execute([$delete_id]);
      
      // Suppression de la playlist
      $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
      $delete_playlist->execute([$delete_id]);
      
      $message[] = 'Playlist supprimée !';
   }else{
      $message[] = 'La playlist a déjà été supprimée !';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlists</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="playlists">

   <h1 class="heading">Playlists ajoutées</h1>

   <div class="box-container">

      <?php
         // Sélection de toutes les playlists dans la base de données
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` ORDER BY date DESC");
         $select_playlist->execute();
         // Vérification s'il y a des playlists
         if($select_playlist->rowCount() > 0){
            // Boucle pour afficher chaque playlist
            while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
               $playlist_id = $fetch_playlist['id'];
               // Comptage du nombre de vidéos dans la playlist
               $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
               $count_videos->execute([$playlist_id]);
               $total_videos = $count_videos->rowCount();
               // Sélection des informations du tuteur associé à la playlist
               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_playlist['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <!-- Affichage de la boîte de la playlist -->
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i>
               <span style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_playlist['status']; ?></span>
            </div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
        <div class="thumb">
            <span><?= $total_videos; ?></span>
            <img src="../uploaded_files/<?= $fetch_playlist['thumb']; ?>" alt="">
        </div>
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <p class="description"><?= $fetch_playlist['description']; ?></p>
         <a href="view_playlist.php?get_id=<?= $playlist_id; ?>" class="btn">Voir la playlist</a>
         <!-- Formulaire pour supprimer la playlist -->
         <form action="" method="post">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <button type="submit" name="delete" class="btn-delete"><i class="fas fa-trash-alt"></i>Supprimer</button>
         </form>
      </div>
      <?php
         } 
      }else{
         // Message si aucune playlist n'a été ajoutée
         echo '<p class="empty">Aucune playlist n\'a été ajoutée pour le moment !</p>';
      }
      ?>

   </div>

</section>

<!-- Script pour raccourcir les descriptions des playlists -->
<script>
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });
</script>

<!-- Inclusion du fichier JavaScript personnalisé -->
<script src="../js/admin_script.js"></script>

</body>
</html>
