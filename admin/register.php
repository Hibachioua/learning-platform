<?php

   include '../components/connect.php';

   // Vérification si le formulaire d'inscription a été soumis
   if(isset($_POST['submit'])){

      // Génération d'un ID unique pour l'administrateur
      $id = unique_id();
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING); // Nettoyage du nom
      $email = $_POST['email'];
      $email = filter_var($email, FILTER_SANITIZE_STRING); 
      $pass = sha1($_POST['pass']); // Hashage du mot de passe
      $pass = filter_var($pass, FILTER_SANITIZE_STRING); 
      $cpass = sha1($_POST['cpass']); // Hashage du mot de passe de confirmation
      $cpass = filter_var($cpass, FILTER_SANITIZE_STRING); 

      // Récupération des informations sur l'image téléchargée
      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING); 
      $ext = pathinfo($image, PATHINFO_EXTENSION);
      $rename = unique_id().'.'.$ext; 
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = '../uploaded_files/'.$rename;

      // Vérification si l'email est déjà utilisé par un autre administrateur
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ?");
      $select_admin->execute([$email]);
      
      if($select_admin->rowCount() > 0){
         $message[] = 'Email déjà utilisé !';
      }else{
         // Vérification si les mots de passe correspondent
         if($pass != $cpass){
            $message[] = 'Les mots de passe ne correspondent pas !';
         }else{
            // Insertion d'un nouvel administrateur dans la base de données
            $insert_admin = $conn->prepare("INSERT INTO `admin`(id, name, email, password, image) VALUES(?,?,?,?,?)");
            $insert_admin->execute([$id, $name, $email, $cpass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder); 
            $message[] = 'Nouvel administrateur enregistré ! Veuillez vous connecter maintenant';
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
   <title>register</title>
   

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
   // Affichage des messages d'erreur ou de succès
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message form">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

   <section class="form-container">

      <!-- Formulaire d'inscription d'un nouvel administrateur -->
      <form class="register" action="" method="post" enctype="multipart/form-data">
         <h3>Enregistrer un nouvel administrateur</h3>
         <div class="flex">
            <div class="col">
               <p>Votre nom <span>*</span></p>
               <input type="text" name="name" placeholder="Entrez votre nom" maxlength="50" required class="box">
               <p>Votre email <span>*</span></p>
               <input type="email" name="email" placeholder="Entrez votre email" maxlength="20" required class="box">
            </div>
            <div class="col">
               <p>Votre mot de passe <span>*</span></p>
               <input type="password" name="pass" placeholder="Entrez votre mot de passe" maxlength="20" required class="box">
               <p>Confirmer le mot de passe <span>*</span></p>
               <input type="password" name="cpass" placeholder="Confirmez votre mot de passe" maxlength="20" required class="box">
               <p>Sélectionnez une image <span>*</span></p>
               <input type="file" name="image" accept="image/*" required class="box">
            </div>
         </div>
         <!-- Lien pour se connecter si l'utilisateur a déjà un compte -->
         <p class="link">Vous avez déjà un compte? <a href="login.php">Connectez-vous maintenant</a></p>
         <input type="submit" name="submit" value="S'inscrire maintenant" class="btn">
      </form>

   </section>

   <script>

      // Script pour activer ou désactiver le mode sombre enregistré dans le stockage local
      let darkMode = localStorage.getItem('dark-mode');
      let body = document.body;

      const enabelDarkMode = () =>{
         body.classList.add('dark');
         localStorage.setItem('dark-mode', 'enabled');
      }

      const disableDarkMode = () =>{
         body.classList.remove('dark');
         localStorage.setItem('dark-mode', 'disabled');
      }

      if(darkMode === 'enabled'){
         enabelDarkMode();
      }else{
         disableDarkMode();
      }

   </script>
   
</body>
</html>
