<?php
require 'db.php';

if (!isset($_GET['token'])) {
    die("Jeton de sécurité manquant.");
}

$tokenHash = hash('sha256', $_GET['token']);

$stmt = $pdo->prepare("SELECT id_user FROM utilisateurs WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->execute([$tokenHash]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Ce lien de réinitialisation est invalide ou a expiré.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    $update = $pdo->prepare(
            "UPDATE utilisateurs SET mot_de_passe_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id_user = ?"
    );
    $update->execute([$new_password, $user['id_user']]);

    header('Location: login.php');
    exit;
}
?>