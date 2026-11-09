<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare(
            "SELECT u.id_user, u.mot_de_passe_hash
     FROM utilisateurs u JOIN profils p ON p.id_user = u.id_user
     WHERE p.email = ?"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mot_de_passe_hash'])) {
        $_SESSION['user_id'] = $user['id_user'];
        header('Location: boggle.php');
        exit;
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
<?php if(isset($error)) echo "<p>$error</p>"; ?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>
<p><a href="passwordRecover.php">Mot de passe oublie ?</a></p>
<p><a href="accountCreate.php">Creer un compte</a></p>
</body>
</html>