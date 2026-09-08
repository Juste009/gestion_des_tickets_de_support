<?php
$pageTitle = "Consulter un ticket - Support-ticket";
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$saisie = trim($_GET['numero'] ?? '');
$ticket = null;
$erreur = null;

if ($saisie !== '') {
    $id = (int) preg_replace('/[^0-9]/', '', $saisie); // "TK-00002", "00002" ou "2" → 2

    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$id]);
        $ticket = $stmt->fetch();
    }

    if (!$ticket) {
        $erreur = "Aucun ticket ne correspond au numéro « " . htmlspecialchars($saisie) . " ».";
    }
}

// Correspondance statut → classe de badge Bootstrap (couleur)
$statutClasses = [
    'Nouveau'    => 'bg-secondary',
    'En cours'   => 'bg-primary',
    'En attente' => 'bg-warning text-dark',
    'Résolu'     => 'bg-success',
    'Fermé'      => 'bg-dark',
];
?>

<main>
<section class="py-5">
    <div class="container" style="max-width: 720px;">
        <h1 class="section-title text-start mb-2">Consulter un ticket</h1>
        <p class="text-muted mb-4">
            Saisissez votre numéro de ticket (ex : <strong>TK-00002</strong>) pour suivre son évolution.
        </p>

        <div class="step-card mb-4">
            <form action="ticket.php" method="GET" class="d-flex gap-2">
                <input type="text" name="numero" class="form-control"
                       placeholder="Ex : TK-00002"
                       value="<?= htmlspecialchars($saisie) ?>" required>
                <button type="submit" class="btn btn-accent">Rechercher</button>
            </form>
        </div>

        <?php if ($erreur): ?>
            <div class="alert alert-danger"><?= $erreur ?></div>
        <?php endif; ?>

        <?php if ($ticket): ?>
            <div class="step-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h2 class="ticket-card-id mb-0" style="color: var(--black);">
                        #TK-<?= str_pad($ticket['id'], 5, '0', STR_PAD_LEFT) ?>
                    </h2>
                    <span class="badge <?= $statutClasses[$ticket['statut']] ?? 'bg-secondary' ?>">
                        <?= htmlspecialchars($ticket['statut']) ?>
                    </span>
                </div>

                <hr>
                <h5>Client</h5>
                <p class="mb-1"><strong>Nom :</strong> <?= htmlspecialchars($ticket['nom'] . ' ' . $ticket['prenom']) ?></p>
                <p class="mb-1"><strong>Email :</strong> <?= htmlspecialchars($ticket['email']) ?></p>
                <p class="mb-0"><strong>Service :</strong> <?= htmlspecialchars($ticket['service']) ?></p>

                <hr>
                <h5>Problème</h5>
                <p class="mb-1"><strong>Sujet :</strong> <?= htmlspecialchars($ticket['sujet']) ?></p>
                <p class="mb-1"><strong>Catégorie :</strong> <?= htmlspecialchars($ticket['categorie']) ?></p>
                <p class="mb-0"><strong>Priorité :</strong> <?= htmlspecialchars($ticket['priorite']) ?></p>

                <hr>
                <h5>Description</h5>
                <p class="mb-0" style="white-space: pre-line;"><?= htmlspecialchars($ticket['description']) ?></p>

                <hr>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">
                    Créé le <?= date('d/m/Y à H:i', strtotime($ticket['created_at'])) ?>
                    · Dernière mise à jour le <?= date('d/m/Y à H:i', strtotime($ticket['updated_at'])) ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>