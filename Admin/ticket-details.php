<?php
$pageTitle = "Détail du ticket - Support-ticket";
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

if (!isset($_GET['id'])) {
    header('Location: tickets.php');
    exit;
}
$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header('Location: tickets.php');
    exit;
}

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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="section-title text-start mb-0">Détail du ticket</h1>
            <a href="tickets.php" class="btn btn-outline-dark btn-sm">&larr; Retour à la liste</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="step-card ticket-detail">
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
            <p class="text-muted mb-4" style="font-size: 0.85rem;">
                Créé le <?= date('d/m/Y à H:i', strtotime($ticket['created_at'])) ?>
                · Dernière mise à jour le <?= date('d/m/Y à H:i', strtotime($ticket['updated_at'])) ?>
            </p>

            <a href="update-ticket.php?id=<?= $ticket['id'] ?>" class="btn btn-accent">Modifier ce ticket</a>
        </div>
    </div>
</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>