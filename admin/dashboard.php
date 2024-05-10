<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- lien CDN pour Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
   
<section class="dashboard">

   <!-- Titre du tableau des enseignants -->
   <h1 class="heading">Tableau de bord</h1>
    <h2 class="heading" >Enseignants</h2>
         <!-- Tableau des enseignants -->
         <table class="content-table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Mot de passe</th>
                  
               </tr>
            </thead>
            <tbody>
               <!-- Boucle pour afficher les données des enseignants -->
               <?php foreach ($tutors as $tutor) { ?>
                  <tr>
                     <td><?php echo $tutor['id']; ?></td>
                     <td><?php echo $tutor['name']; ?></td>
                     <td><?php echo $tutor['email']; ?></td>
                     <td><?php echo $tutor['password']; ?></td>
                    
                  </tr>
               <?php } ?>
            </tbody>
         </table>
      
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
      <br>
   <h2 class="heading" >Étudiants</h2>
   <!-- Tableau des étudiants -->
   <table class="content-table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Mot de passe</th>
                 
               </tr>
            </thead>
            <tbody>
               <!-- Boucle pour afficher les données des étudiants -->
               <?php foreach ($users as $user) { ?>
                  <tr>
                     <td><?php echo $user['id']; ?></td>
                     <td><?php echo $user['name']; ?></td>
                     <td><?php echo $user['email']; ?></td>
                     <td><?php echo $user['password']; ?></td>
                   
                  </tr>
               <?php } ?>
            </tbody>
         </table> 
</section>

<!-- lien vers le fichier JavaScript personnalisé -->
<script src="../js/admin_script.js"></script>

</body>
</html>
