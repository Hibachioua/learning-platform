<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
    header('location:login.php');
}

// Récupérer l'ID de l'administrateur
$select_admin = $conn->query("SELECT id FROM admin LIMIT 1");
$admin_id = $select_admin->fetchColumn();

// Traitement de la demande d'acceptation ou de refus pour les utilisateurs
if(isset($_POST['action_user'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action_user'];

    if($action == 'accept') {
        // Supprimer le compte de l'utilisateur correspondant
        $delete_user = $conn->prepare("DELETE FROM users WHERE id = (SELECT user_id FROM deletion_requests WHERE id = ?)");
        $delete_user->execute([$request_id]);
    }

    // Supprimer la demande de la table deletion_requests
    $delete_request = $conn->prepare("DELETE FROM deletion_requests WHERE id = ?");
    $delete_request->execute([$request_id]);

    // Mettre à jour la demande avec l'ID de l'administrateur
    $update_request = $conn->prepare("UPDATE deletion_requests SET admin_id = ? WHERE id = ?");
    $update_request->execute([$admin_id, $request_id]);

    // Rediriger pour éviter la soumission en double
    header('location: dashboard.php');
}

// Récupérer les demandes de suppression en attente pour les utilisateurs
$select_deletion_requests = $conn->query("SELECT d.id AS request_id, u.name AS user_name FROM deletion_requests d LEFT JOIN users u ON d.user_id = u.id WHERE d.admin_id IS NULL");
$deletion_requests = $select_deletion_requests->fetchAll();
?>
<?php

$select_admin = $conn->query("SELECT id FROM admin LIMIT 1");
$admin_id = $select_admin->fetchColumn();

if(isset($_POST['action_tutor'])) {
  $request = $_POST['request'];
  $action = $_POST['action_tutor'];

  if($action == 'accepter') {
      // Supprimer le compte du tuteur correspondant
      $delete_tutor = $conn->prepare("DELETE FROM tutors WHERE id = (SELECT tutor_id FROM deletion_tutors WHERE request_id = ?)");
      $delete_tutor->execute([$request]);
  }

  // Supprimer la demande de la table deletion_tutors
  $delete_request = $conn->prepare("DELETE FROM deletion_tutors WHERE request_id = ?");
  $delete_request->execute([$request]);

  // Mettre à jour la demande avec l'ID de l'administrateur
  $update_request = $conn->prepare("UPDATE deletion_tutors SET admin_id = ? WHERE request_id = ?");
  $update_request->execute([$admin_id, $request]);

  // Rediriger pour éviter la soumission en double
  header('location: dashboard.php');
}

// Récupérer les demandes de suppression des tuteurs avec les noms des comptes associés
$select_deletion_requests_tutors = $conn->query("SELECT dt.request_id, dt.tutor_id, dt.tutor_name, t.name AS tutor_name FROM deletion_tutors dt LEFT JOIN tutors t ON dt.tutor_id = t.id");
$deletion_requests_tutors = $select_deletion_requests_tutors->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body>
<?php include '../components/admin_header.php'; ?>
<div class="deletion-requests">
    <h1 class="attente"><center>Deletion requests for students</center></h1>
    <ul >
        <?php foreach ($deletion_requests as $request) : ?>
            <li >Student <?= $request['user_name']; ?> <i> waiting for his account to be deleted</i>
                <form action="" method="post">
                    <input type="hidden" name="request_id" value="<?= $request['request_id']; ?>">
                    <button type="submit" class="accept" name="action_user" value="accept">Accepter</button>
                    <button type="submit" class="reject" name="action_user" value="reject">Refuser</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<h1 class="attente"><center>Deletion requests for tutors</center></h1>
    <ul>
        <?php foreach ($deletion_requests_tutors as $dm) : ?>
            <li> Teacher <?= $dm['tutor_name']; ?>  <i>waiting for his account to be deleted</i>
            <form action="" method="post"> 
                    <input type="hidden" name="request" value="<?= $dm['request_id']; ?>"> 
                    <button type="submit" class="accept"  name="action_tutor" value="accepter">Accepter</button>
                    <button type="submit" class="reject" name="action_tutor" value="refuser">Refuser</button>
                </form>
          </li>
        <?php endforeach; ?>
    </ul>
<script src="../js/admin_script.js"></script>
</body>
</html>
