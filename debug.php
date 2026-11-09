<?php
require 'db.php';

try {
    $pdo->beginTransaction();
    $id = $pdo->query("INSERT INTO utilisateurs (pseudo, mot_de_passe_hash) VALUES ('test_debug', 'x') RETURNING id_user")->fetchColumn();
    $pdo->exec("INSERT INTO profils (id_user, email) VALUES ($id, 'test_debug@example.com')");
    $pdo->commit();
    echo "<p>Insertion OK, id = $id</p>";
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<pre>";
print_r($pdo->query("SELECT u.id_user, u.pseudo, p.email FROM utilisateurs u LEFT JOIN profils p ON p.id_user = u.id_user")->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";