<?php

   // Inclusion du fichier de connexion à la base de données
   include '../components/connect.php';

   // Vérification de l'existence du cookie 'tutor_id'
   if(isset($_COOKIE['tutor_id'])){
      $tutor_id = $_COOKIE['tutor_id'];
   }else{
      $tutor_id = '';
      // Redirection vers la page de connexion si le cookie 'tutor_id' n'existe pas
      header('location:login.php');
   }

   // Vérification si le formulaire de mise à jour du profil a été soumis
   if(isset($_POST['submit'])){

      // Sélection des informations du tuteur à mettre à jour
      $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
      $select_tutor->execute([$tutor_id]);
      $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

      // Récupération du mot de passe et de l'image précédents du tuteur
      $prev_pass = $fetch_tutor['password'];
      $prev_image = $fetch_tutor['image'];

      // Récupération des données saisies dans le formulaire
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING); // Nettoyage du nom
      $profession = $_POST['profession'];
      $profession = filter_var($profession, FILTER_SANITIZE_STRING); 
      $email = $_POST['email'];
      $email = filter_var($email, FILTER_SANITIZE_STRING); 
      
      // Mise à jour du nom si le champ n'est pas vide
      if(!empty($name)){
         $update_name = $conn->prepare("UPDATE `tutors` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $tutor_id]);
         $message[] = 'Nom mis à jour avec succès !';
      }

      // Mise à jour de l'email si le champ n'est pas vide et si l'email n'est pas déjà pris
      if(!empty($email)){
         $select_email = $conn->prepare("SELECT email FROM `tutors` WHERE id = ? AND email = ?");
         $select_email->execute([$tutor_id, $email]);
         if($select_email->rowCount() > 0){
            $message[] = 'Email déjà utilisé !';
         }else{
            $update_email = $conn->prepare("UPDATE `tutors` SET email = ? WHERE id = ?");
            $update_email->execute([$email, $tutor_id]);
            $message[] = 'Email mis à jour avec succès !';
         }
      }

      // Traitement de la mise à jour de l'image
      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING); // Nettoyage du nom de l'image
      $ext = pathinfo($image, PATHINFO_EXTENSION);
      $rename = unique_id().'.'.$ext;
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = '../uploaded_files/'.$rename;

      // Mise à jour de l'image si le champ n'est pas vide et si la taille de l'image est valide
      if(!empty($image)){
         if($image_size > 2000000){
            $message[] = 'Taille de l\'image trop grande !';
         }else{
            $update_image = $conn->prepare("UPDATE `tutors` SET `image` = ? WHERE id = ?");
            $update_image->execute([$rename, $tutor_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            // Suppression de l'ancienne image si elle existe et si elle est différente de la nouvelle
            if($prev_image != '' AND $prev_image != $rename){
               unlink('../uploaded_files/'.$prev_image);
            }
            $message[] = 'Image mise à jour avec succès !';
         }
      }

      // Traitement de la mise à jour du mot de passe
      $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
      $old_pass = sha1($_POST['old_pass']);
      $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
      $new_pass = sha1($_POST['new_pass']);
      $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
      $cpass = sha1($_POST['cpass']);
      $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

      // Vérification de l'ancien mot de passe et mise à jour du nouveau mot de passe s'il est valide
      if($old_pass != $empty_pass){
         if($old_pass != $prev_pass){
            $message[] = 'Ancien mot de passe incorrect !';
         }elseif($new_pass != $cpass){
            $message[] = 'Le nouveau mot de passe et la confirmation ne correspondent pas !';
         }else{
            if($new_pass != $empty_pass){
               $update_pass = $conn->prepare("UPDATE `tutors` SET password = ? WHERE id = ?");
               $update_pass->execute([$cpass, $tutor_id]);
               $message[] = 'Mot de passe mis à jour avec succès !';
            }else{
               $message[] = 'Veuillez entrer un nouveau mot de passe !';
            }
         }
      }

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>
   

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container" style="min-height: calc(100vh - 19rem);">

   <!-- Formulaire de mise à jour du profil -->
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Modifier le profil</h3>
      <div class="flex">
         <div class="col">
            <p>Votre nom </p>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name']; ?>" maxlength="50"  class="box">
            <p>Votre profession </p>
            <select name="profession" class="box">
               <option value="" selected><?= $fetch_profile['profession']; ?></option>
               <option value="developer">developer</option>
               <option value="desginer">desginer</option>
               <option value="musician">musician</option>
               <option value="biologist">biologist</option>
               <option value="teacher">teacher</option>
               <option value="engineer">engineer</option>
               <option value="lawyer">lawyer</option>
               <option value="accountant">accountant</option>
               <option value="doctor">doctor</option>
               <option value="journalist">journalist</option>
               <option value="photographer">photographer</option>
            </select>
            <p>Votre email </p>
            <input type="email" name="email" placeholder="<?= $fetch_profile['email']; ?>" maxlength="20"  class="box">
         </div>
         <div class="col">
            <p>Ancien mot de passe :</p>
            <input type="password" name="old_pass" placeholder="Entrez votre ancien mot de passe" maxlength="20"  class="box">
            <p>Nouveau mot de passe :</p>
            <input type="password" name="new_pass" placeholder="Entrez votre nouveau mot de passe" maxlength="20"  class="box">
            <p>Confirmer le nouveau mot de passe :</p>
            <input type="password" name="cpass" placeholder="Confirmez votre nouveau mot de passe" maxlength="20"  class="box">
         </div>
      </div>
      <p>Mettre à jour l'image :</p>
      <input type="file" name="image" accept="image/*"  class="box">
      <input type="submit" name="submit" value="Mettre à jour maintenant" class="btn">
   </form>

</section>
<script src="../js/admin_script.js"></script>
   
</body>
</html>
