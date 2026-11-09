<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boggle</title>
</head>
<body>
<h1>Le site est momentanément indisponible durant la maintenance.</h1>
</body>
</html>