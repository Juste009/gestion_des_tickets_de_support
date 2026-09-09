<?php
session_start();
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
$categorieAutre = trim($_POST["categorie_autre"] ?? "");
$priorite = trim($_POST["priorite"] ?? "");
$description = trim($_POST["description"] ?? "");

$erreurs = [];
$nomPattern = '/^[A-Za-zÀ-ÿ\s\-\']+$/';

if ($nom === "") {
    $erreurs[] = "Le nom est obligatoire.";
} elseif (!preg_match($nomPattern, $nom)) {
    $erreurs[] = "Le nom ne doit contenir que des lettres, espaces, tirets ou apostrophes.";
}

if ($prenom === "") {
    $erreurs[] = "Le prénom est obligatoire.";
} elseif (!preg_match($nomPattern, $prenom)) {
    $erreurs[] = "Le prénom ne doit contenir que des lettres, espaces, tirets ou apostrophes.";
}

if ($email === "") {
    $erreurs[] = "L'e-mail est obligatoire.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/\.[a-zA-Z]{2,}$/', $email)) {
    $erreurs[] = "L'adresse e-mail n'est pas valide (ex : nom@domaine.com).";
}

if ($service === "") {
    $erreurs[] = "Le service est obligatoire.";
}

if ($sujet === "") {
    $erreurs[] = "L'objet du ticket est obligatoire.";
}

if ($categorie === "") {
    $erreurs[] = "La catégorie est obligatoire.";
} elseif ($categorie === "Autre") {
    if ($categorieAutre === "") {
        $erreurs[] = "Merci de préciser la catégorie.";
    } else {
        $categorie = $categorieAutre;
    }
}

if ($priorite === "") {
    $erreurs[] = "La priorité est obligatoire.";
}

if ($description === "") {
    $erreurs[] = "La description est obligatoire.";
}

$nomFichier = null;

if (isset($_FILES["piece_jointe"]) && $_FILES["piece_jointe"]["error"] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES["piece_jointe"]["error"] !== UPLOAD_ERR_OK) {
        $erreurs[] = "Une erreur est survenue lors de l'envoi de la pièce jointe.";
    } else {

        $fichier = $_FILES["piece_jointe"];

        $tailleMax = 2 * 1024 * 1024;

        if ($fichier["size"] > $tailleMax) {
            $erreurs[] = "La pièce jointe ne doit pas dépasser 2 Mo.";
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fichier["tmp_name"]);
        finfo_close($finfo);

        $mimesAutorises = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "application/pdf" => "pdf"
        ];

        if (!isset($mimesAutorises[$mime])) {
            $erreurs[] = "Format de fichier non autorisé. Formats acceptés : JPG, JPEG, PNG et PDF.";
        }

        if (empty($erreurs)) {

            $extension = $mimesAutorises[$mime];

            $nomFichier = bin2hex(random_bytes(16)) . "." . $extension;

            $dossierUpload = __DIR__ . "/uploads/";

            if (!is_dir($dossierUpload)) {
                mkdir($dossierUpload, 0755, true);
            }

            $cheminFichier = $dossierUpload . $nomFichier;

            if (!move_uploaded_file($fichier["tmp_name"], $cheminFichier)) {
                $erreurs[] = "Impossible d'enregistrer la pièce jointe.";
                $nomFichier = null;
            }
        }
    }
}

if (!empty($erreurs)) {
    $_SESSION["errors"] = $erreurs;

    $_SESSION["old"] = [
        "nom" => $nom,
        "prenom" => $prenom,
        "email" => $email,
        "service" => $service,
        "sujet" => $sujet,
        "categorie" => $_POST["categorie"] ?? "",
        "categorie_autre" => $categorieAutre,
        "priorite" => $priorite,
        "description" => $description
    ];

    header("Location: create-ticket.php");
    exit;
}

try {

    $sql = "INSERT INTO tickets 
            (nom, prenom, email, service, sujet, categorie, priorite, description, statut, piece_jointe)
            VALUES 
            (:nom, :prenom, :email, :service, :sujet, :categorie, :priorite, :description, 'Nouveau', :piece_jointe)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":nom" => $nom,
        ":prenom" => $prenom,
        ":email" => $email,
        ":service" => $service,
        ":sujet" => $sujet,
        ":categorie" => $categorie,
        ":priorite" => $priorite,
        ":description" => $description,
        ":piece_jointe" => $nomFichier
    ]);

    $id = $pdo->lastInsertId();

    $numeroTicket = "TK-" . str_pad($id, 5, "0", STR_PAD_LEFT);

    $_SESSION["ticket_confirme"] = $numeroTicket;

    header("Location: confirmation.php");
    exit;

} catch (PDOException $e) {

    if ($nomFichier !== null) {
        $fichierSupprimer = __DIR__ . "/uploads/" . $nomFichier;

        if (file_exists($fichierSupprimer)) {
            unlink($fichierSupprimer);
        }
    }

    $_SESSION["errors"] = [
        "Une erreur est survenue lors de l'enregistrement. Réessaie."
    ];

    $_SESSION["old"] = $_POST;

    header("Location: create-ticket.php");
    exit;
}