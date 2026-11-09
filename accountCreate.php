<?php
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo']);
    $email  = trim($_POST['email']);
    $mdp    = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, mot_de_passe_hash) VALUES (?, ?) RETURNING id_user");
        $stmt->execute([$pseudo, $mdp]);
        $idUser = $stmt->fetchColumn();

        $pdo->prepare("INSERT INTO profils (id_user, email) VALUES (?, ?)")->execute([$idUser, $email]);
        $pdo->commit();

        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log($e->getMessage());
        $error = "Pseudo ou email déjà utilisé.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de compte</title>
</head>
<body>
<?php if ($error) echo "<p>$error</p>"; ?>
<form method="POST">
    <input type="text" name="pseudo" placeholder="Pseudo" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="mdp" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>