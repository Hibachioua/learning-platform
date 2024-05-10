<?php

// Inclusion du fichier de connexion à la base de données
include '../components/connect.php';

// Vérification si le formulaire de connexion a été soumis
if(isset($_POST['submit'])){

   // Récupération de l'email et du mot de passe du formulaire
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING); // Nettoyage de l'email
   $pass = sha1($_POST['pass']); // Hashage du mot de passe
   $pass = filter_var($pass, FILTER_SANITIZE_STRING); // Nettoyage du mot de passe

   // Requête pour sélectionner l'administrateur avec l'email et le mot de passe fournis
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ? AND password = ? LIMIT 1");
   $select_admin->execute([$email, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);
   
   // Vérification si l'administrateur existe dans la base de données
   if($select_admin->rowCount() > 0){
      // Création du cookie pour l'ID de l'administrateur et redirection vers le tableau de bord
      setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
      header('location:dashboard.php');
   }else{
      // Message d'erreur si l'email ou le mot de passe est incorrect
      $message[] = 'Email ou mot de passe incorrect !';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="padding-left: 0;">

<?php
// Affichage des messages d'erreur s'il y en a
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

<!-- Section pour se -->

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Bienvenue Admin !</h3>
      <p>Votre email <span>*</span></p>
      <input type="email" name="email" placeholder="Entrez votre email" maxlength="40" required class="box">
      <p>Votre mot de passe <span>*</span></p>
      <input type="password" name="pass" placeholder="Entrez votre mot de passe" maxlength="40" required class="box">
      
      <input type="submit" name="submit" value="Se connecter" class="btn">
   </form>

</section>

   
</body>
</html>
