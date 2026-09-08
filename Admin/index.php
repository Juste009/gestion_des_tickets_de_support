<?php
$pageTitle = "Tableau de bord - Support-ticket";
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

$ticketsOuverts = $pdo->query(
    "SELECT COUNT(*) FROM tickets WHERE statut NOT IN ('Résolu', 'Fermé')"
)->fetchColumn();

$ticketsResolus = $pdo->query(
    "SELECT COUNT(*) FROM tickets WHERE statut = 'Résolu'"
)->fetchColumn();

$ticketsUrgents = $pdo->query(
    "SELECT COUNT(*) FROM tickets WHERE priorite IN ('Haute','Critique')"
)->fetchColumn();

$ticketsDuJour = $pdo->query(
    "SELECT COUNT(*) FROM tickets WHERE DATE(created_at) = CURDATE()"
)->fetchColumn();

$derniersTickets = $pdo->query(
    "SELECT id, sujet, categorie, priorite, statut, created_at 
     FROM tickets ORDER BY created_at DESC LIMIT 5"
)->fetchAll();

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
    <div class="container">
        <h1 class="section-title text-start mb-4">Tableau de bord</h1>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="step-card text-center">
                    <span class="step-index"><?= $ticketsOuverts ?></span>
                    <h3>Tickets ouverts</h3>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="step-card text-center">
                    <span class="step-index"><?= $ticketsResolus ?></span>
                    <h3>Tickets résolus</h3>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="step-card text-center">
                    <span class="step-index"><?= $ticketsUrgents ?></span>
                    <h3>Tickets urgents</h3>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="step-card text-center">
                    <span class="step-index"><?= $ticketsDuJour ?></span>
                    <h3>Tickets du jour</h3>
                </div>
            </div>
        </div>

        <div class="step-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Derniers tickets</h2>
                <a href="tickets.php" class="btn btn-accent btn-sm">Voir tous les tickets</a>
            </div>

            <?php if (empty($derniersTickets)): ?>
                <div class="alert alert-info mb-0">Aucun ticket enregistré pour le moment.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sujet</th>
                                <th>Catégorie</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($derniersTickets as $t): ?>
                                <tr>
                                    <td class="ticket-card-id" style="font-size: 1rem; margin: 0;">
                                        #TK-<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td><?= htmlspecialchars($t['sujet']) ?></td>
                                    <td><?= htmlspecialchars($t['categorie']) ?></td>
                                    <td><?= htmlspecialchars($t['priorite']) ?></td>
                                    <td>
                                        <span class="badge <?= $statutClasses[$t['statut']] ?? 'bg-secondary' ?>">
                                            <?= htmlspecialchars($t['statut']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>