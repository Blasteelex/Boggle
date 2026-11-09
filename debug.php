<?php
require 'db.php';

echo "<p>Base : " . $pdo->query("SELECT current_database()")->fetchColumn() . "</p>";
echo "<p>Host configuré : " . htmlspecialchars(getenv('DB_HOST')) . "</p>";

$rows = $pdo->query(
    "SELECT u.id_user, u.pseudo, p.email
     FROM utilisateurs u LEFT JOIN profils p ON p.id_user = u.id_user"
)->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>"; print_r($rows); echo "</pre>";