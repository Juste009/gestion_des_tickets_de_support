<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: create-ticket.php");
    exit;
}

$nom = trim($_POST["nom"] ?? "");
$prenom = trim($_POST["prenom"] ?? "");
$email = trim($_POST["email"] ?? "");
$service = trim($_POST["service"] ?? "");
$sujet = trim($_POST["sujet"] ?? "");
$categorie = trim($_POST["categorie"] ?? "");
$priorite = trim($_POST["priorite"] ?? "");
$description = trim($_POST["description"] ?? "");

$erreurs = [];

if ($nom === "") {
    $erreurs[] = "Le nom est obligatoire.";
}

if ($prenom === "") {
    $erreurs[] = "Le prénom est obligatoire.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erreurs[] = "L'adresse e-mail est invalide.";
}

if ($service === "") {
    $erreurs[] = "Le service est obligatoire.";
}

if ($sujet === "") {
    $erreurs[] = "L'objet du ticket est obligatoire.";
}

if ($categorie === "") {
    $erreurs[] = "La catégorie est obligatoire.";
}

if ($priorite === "") {
    $erreurs[] = "La priorité est obligatoire.";
}

if ($description === "") {
    $erreurs[] = "La description est obligatoire.";
}

if (!empty($erreurs)) {
    echo "<h2>Erreurs</h2>";

    foreach ($erreurs as $erreur) {
        echo "<p>" . htmlspecialchars($erreur) . "</p>";
    }

    echo '<a href="create-ticket.php">Retour au formulaire</a>';
    exit;
}

try {
    $sql = "INSERT INTO tickets 
            (nom, prenom, email, service, sujet, categorie, priorite, description, statut)
            VALUES 
            (:nom, :prenom, :email, :service, :sujet, :categorie, :priorite, :description, 'Nouveau')";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":nom" => $nom,
        ":prenom" => $prenom,
        ":email" => $email,
        ":service" => $service,
        ":sujet" => $sujet,
        ":categorie" => $categorie,
        ":priorite" => $priorite,
        ":description" => $description
    ]);

    $id = $pdo->lastInsertId();

    $numero_ticket = "TK-" . str_pad($id, 5, "0", STR_PAD_LEFT);

    echo "<h2>Ticket créé avec succès</h2>";
    echo "<p>Votre numéro de ticket est : <strong>#" . htmlspecialchars($numero_ticket) . "</strong></p>";
    echo "<p>Statut : <strong>Nouveau</strong></p>";
    echo '<a href="create-ticket.php">Créer un autre ticket</a>';

} catch (PDOException $e) {
    echo "Erreur lors de l'enregistrement du ticket : " . htmlspecialchars($e->getMessage());
}
?>