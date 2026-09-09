<?php

session_start();

require_once "../config/database.php";

if (isset($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } else {

        $sql = "SELECT id, nom, email, password 
                FROM administrateurs 
                WHERE email = :email 
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            "email" => $email
        ]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin["password"])) {

            session_regenerate_id(true);

            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_nom"] = $admin["nom"];
            $_SESSION["admin_email"] = $admin["email"];

            header("Location: index.php");
            exit;

        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion administrateur</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-4">

            <div class="card p-4">

                <h1 class="h3 text-center mb-4">
                    Connexion administrateur
                </h1>

                <?php if ($error !== ""): ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                    </div>

                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control"
                            required
                            value="<?= htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        >

                    </div>

                    <div class="mb-3">

                        <label for="password" class="form-label">
                            Mot de passe
                        </label>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            required
                        >

                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Se connecter
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>

</html>