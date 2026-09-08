<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tickets.php');
    exit;
}

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header('Location: tickets.php');
    exit;
}

$categoriesStandard = ['Matériel', 'Logiciel', 'Réseau', 'Messagerie', 'Compte utilisateur', 'Autre'];
$priorites = ['Basse', 'Normale', 'Haute', 'Critique'];
$statuts = ['Nouveau', 'En cours', 'En attente', 'Résolu', 'Fermé'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categorie = trim($_POST['categorie'] ?? '');
    $categorieAutre = trim($_POST['categorie_autre'] ?? '');
    $priorite = trim($_POST['priorite'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($categorie === '') {
        $errors[] = "La catégorie est obligatoire.";
    } elseif ($categorie === 'Autre') {
        if ($categorieAutre === '') {
            $errors[] = "Merci de préciser la catégorie.";
        } else {
            $categorie = $categorieAutre;
        }
    }

    if (!in_array($priorite, $priorites, true)) {
        $errors[] = "Priorité invalide.";
    }

    if (!in_array($statut, $statuts, true)) {
        $errors[] = "Statut invalide.";
    }

    if ($description === '') {
        $errors[] = "La description est obligatoire.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "UPDATE tickets SET categorie = ?, priorite = ?, statut = ?, description = ? WHERE id = ?"
        );
        $stmt->execute([$categorie, $priorite, $statut, $description, $id]);

        $_SESSION['message'] = "Le ticket #TK-" . str_pad($id, 5, '0', STR_PAD_LEFT) . " a été mis à jour.";
        header('Location: ticket-details.php?id=' . $id);
        exit;
    }

    // en cas d'erreur, on réaffiche le formulaire avec les valeurs saisies
    $ticket['categorie'] = $categorie === '' ? ($_POST['categorie'] ?? '') : $categorie;
    $ticket['priorite'] = $priorite;
    $ticket['statut'] = $statut;
    $ticket['description'] = $description;
    $categorieAutreValue = $categorieAutre;
} else {
    $categorieAutreValue = in_array($ticket['categorie'], $categoriesStandard, true) ? '' : $ticket['categorie'];
}

$categorieSelectValue = in_array($ticket['categorie'], $categoriesStandard, true) ? $ticket['categorie'] : 'Autre';

$pageTitle = "Modifier le ticket - Support-ticket";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<main>
<section class="py-5">
    <div class="container" style="max-width: 720px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="section-title text-start mb-0">Modifier le ticket</h1>
            <a href="ticket-details.php?id=<?= $id ?>" class="btn btn-outline-dark btn-sm">&larr; Retour</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="step-card">
            <p class="ticket-card-id mb-3" style="color: var(--black); font-size: 1.2rem;">
                #TK-<?= str_pad($id, 5, '0', STR_PAD_LEFT) ?> — <?= htmlspecialchars($ticket['sujet']) ?>
            </p>

            <form method="POST" action="update-ticket.php">
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="categorie" class="form-label">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie" required>
                            <?php foreach ($categoriesStandard as $cat): ?>
                                <option value="<?= $cat ?>" <?= $categorieSelectValue === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="categorieAutreWrapper" class="mt-2 <?= $categorieSelectValue === 'Autre' ? '' : 'd-none' ?>">
                            <input type="text" class="form-control" id="categorie_autre" name="categorie_autre"
                                   placeholder="Précisez la catégorie"
                                   value="<?= htmlspecialchars($categorieAutreValue) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="priorite" class="form-label">Priorité</label>
                        <select class="form-select" id="priorite" name="priorite" required>
                            <?php foreach ($priorites as $p): ?>
                                <option value="<?= $p ?>" <?= $ticket['priorite'] === $p ? 'selected' : '' ?>><?= $p ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select class="form-select" id="statut" name="statut" required>
                        <?php foreach ($statuts as $s): ?>
                            <option value="<?= $s ?>" <?= $ticket['statut'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Description du problème</label>
                    <textarea class="form-control" id="description" name="description" rows="6"
                              required><?= htmlspecialchars($ticket['description']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-accent">Enregistrer les modifications</button>
                <a href="ticket-details.php?id=<?= $id ?>" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
</section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('categorie');
    const wrapper = document.getElementById('categorieAutreWrapper');
    const input = document.getElementById('categorie_autre');

    function toggleAutre() {
        const isAutre = select.value === 'Autre';
        wrapper.classList.toggle('d-none', !isAutre);
        input.required = isAutre;
    }

    select.addEventListener('change', toggleAutre);
    toggleAutre();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>