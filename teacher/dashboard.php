<?php
include '../components/connect.php';

if (!isset($_COOKIE['tutor_id'])) {
    header('location:login.php');
    exit();
}

$tutor_id = $_COOKIE['tutor_id'];

// Prepared statement to avoid SQL injection, for PDO
$stmt = $conn->prepare("SELECT u.name, u.email, p.title AS playlist_title, c.comment FROM users u
                        INNER JOIN bookmark b ON u.id = b.user_id
                        INNER JOIN playlist p ON b.playlist_id = p.id
                        LEFT JOIN comments c ON u.id = c.user_id AND b.playlist_id = c.content_id
                        WHERE p.tutor_id = ?");
$stmt->bindParam(1, $tutor_id);
$stmt->execute();

$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents = $select_contents->rowCount();

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();


$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_comments->execute([$tutor_id]);
$total_comments = $select_comments->rowCount();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/teacher_style.css">
</head>
<body>
    <?php include '../components/teacher_header.php'; ?>
    <section class="dashboard">
    <h1 class="heading">Dashboard</h1>
    <h2 class="sub-heading">Statistics</h2>
             <table class="content-table">
                <thead>
                <tr>
                    <th class="table-content">Total Contents</th>
                    <th>Total Playlists</th>
                    <th>Total Comments</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $total_contents; ?></td>
                        <td><?= $total_playlists; ?></td>
                        <td><?= $total_comments; ?></td>
                    </tr>
                </tbody>
            </table>

    
        <h2 class="sub-heading">Subscriptions</h2>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Student's name</th>
                            <th>Email</th>
                            <th>Playlist's name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['playlist_title']) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        


    </section>
    <script src="../js/teacher_script.js"></script>
</body>
</html>
