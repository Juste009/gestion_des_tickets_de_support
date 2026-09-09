<?php
require_once "../config/database.php";
require_once "auth.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tickets.php");
    exit;
}
$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;

if ($id <= 0) {
    header("Location: tickets.php");
    exit;
}
try {
    $sql = "DELETE FROM tickets WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "id" => $id
    ]);
    header("Location: tickets.php");
    exit;
} catch (PDOException $e) {
    echo "Une erreur est survenue lors de la suppression.";
}