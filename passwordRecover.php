<?php
require 'db.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT id_user FROM profils WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(32));

        // On stocke seulement le hash du token ; NOW() évite les soucis de fuseau horaire
        $update = $pdo->prepare(
                "UPDATE utilisateurs SET reset_token = ?, reset_expires = NOW() + INTERVAL '1 hour' WHERE id_user = ?"
        );
        $update->execute([hash('sha256', $token), $user['id_user']]);

        $resetLink = getenv('APP_URL') . "/passwordReset.php?token=" . $token;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('SMTP_USER');
            $mail->Password   = getenv('SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int) getenv('SMTP_PORT');
            $mail->CharSet    = 'UTF-8';
            $mail->Timeout    = 10;

            $mail->setFrom(getenv('SMTP_FROM'), 'Boggle Admin');
        } catch (Exception $e) {
            error_log("Erreur mail : " . $mail->ErrorInfo);
        }
    }
    // Même message dans tous les cas (on ne révèle pas si l'email existe)
    $msg = "Si l'adresse existe, un email a été envoyé.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récupération</title>
</head>
<body>
<?php if (isset($msg)) echo "<p>" . htmlspecialchars($msg) . "</p>"; ?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Envoyer le lien</button>
</form>
<a href="login.php">Retour à la connexion</a>
</body>
</html>
