<?php
// Inclusion du fichier de connexion à la base de données
include 'components/connect.php';

// Vérification si le cookie 'tutor_id' est défini, sinon redirection vers la page de connexion
if(isset($_COOKIE['tutor_id'])){
    $tutor_id = $_COOKIE['tutor_id'];
}else{
    $tutor_id = '';
    header('location:login.php');
}

// Sélection du nom du tuteur basé sur son ID
$select_tutor = $conn->prepare("SELECT name FROM tutors WHERE id = ?");
$select_tutor->execute([$tutor_id]);
$tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
$tutor_name = $tutor['name'];

$message = '';

// Vérification si la demande de suppression de tuteur a été soumise
if(isset($_POST['delete_teacher'])) {
    // Insertion de la demande de suppression dans la table 'deletion_tutors'
    $insert_request = $conn->prepare("INSERT INTO deletion_tutors (tutor_id, name) VALUES (?, ?)");
    $insert_request->execute([$tutor_id, $tutor_name]);
    
    // Vérification si la demande a été acceptée ou refusée par l'administrateur
    $select_request_status = $conn->prepare("SELECT admin_id FROM deletion_tutors WHERE tutor_id = ?");
    $select_request_status->execute([$tutor_id]);
    $request_status = $select_request_status->fetchColumn();
    
    if($request_status) {
        // Si la demande est acceptée, rediriger l'utilisateur vers la page home.php
        header('location: home.php');
        exit(); // Terminer le script pour éviter toute exécution supplémentaire
    } else {
        // Si la demande est toujours en attente ou a été refusée, vérifier si elle a été refusée par l'administrateur
        $select_request_status = $conn->prepare("SELECT admin_id FROM deletion_tutors WHERE tutor_id = ?");
        $select_request_status->execute([$tutor_id]);
        $request_status = $select_request_status->fetchColumn();

        if(!$request_status) {
            // Si la demande a été refusée, le message de confirmation doit disparaître
            $message = '';
        } else {
            // Si la demande est toujours en attente, afficher le message
            $message = "Your deletion request has been sent to the administrator for review";
        }
    }
}
?>
