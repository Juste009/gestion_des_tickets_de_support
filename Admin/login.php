<?php
require_once __DIR__ . '/../config/database.php'; // fait déjà session_start()

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
        $stmt = $pdo->prepare("SELECT id, nom, email, password FROM administrateurs WHERE email = :email LIMIT 1");
        $stmt->execute(["email" => $email]);
        $admin = $stmt->fetch();

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

$pageTitle = "Connexion administrateur - Support-ticket";
require_once __DIR__ . '/../includes/header.php';
// pas de navbar ici : l'utilisateur n'est pas encore identifié
?>

<main>
<section class="py-5 d-flex align-items-center" style="min-height: 80vh;">
    <div class="container" style="max-width: 520px;">
        <div class="step-card p-5">
            <h1 class="section-title mb-2" style="font-size: 1.9rem;">Connexion administrateur</h1>
            <p class="text-muted mb-4">
                Accédez au tableau de bord pour gérer les tickets de support.
            </p>

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-lg" required
                           value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password"
                               class="form-control form-control-lg" required>
                        <button type="button" id="togglePassword" class="btn btn-outline-dark d-flex align-items-center" aria-label="Afficher le mot de passe">
                            <svg id="iconEyeOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="iconEyeClosed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.6 21.6 0 0 1 5.06-6.06"></path>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a21.6 21.6 0 0 1-2.34 3.5"></path>
                                <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-accent btn-lg w-100">Se connecter</button>
            </form>
        </div>
    </div>
</section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('iconEyeOpen');
    const eyeClosed = document.getElementById('iconEyeClosed');

    toggleBtn.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';

        eyeOpen.style.display = isHidden ? 'none' : 'block';
        eyeClosed.style.display = isHidden ? 'block' : 'none';

        toggleBtn.setAttribute('aria-label', isHidden ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
