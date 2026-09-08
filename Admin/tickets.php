<?php
$pageTitle = "Liste des tickets - Support-ticket";
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

// Pagination
$par_page = 10;
$page_actuelle = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page_actuelle - 1) * $par_page;

// Recherche et filtres
$recherche = trim($_GET['recherche'] ?? '');
$filtre_statut = trim($_GET['statut'] ?? '');
$filtre_priorite = trim($_GET['priorite'] ?? '');

$conditions = [];
$params = [];

if ($recherche !== '') {
    $like = "%$recherche%";
    $recherche_id = (int) preg_replace('/[^0-9]/', '', $recherche); // pour chercher par numéro "TK-00003"
    $conditions[] = "(sujet LIKE ? OR nom LIKE ? OR prenom LIKE ? OR email LIKE ? OR id = ?)";
    array_push($params, $like, $like, $like, $like, $recherche_id > 0 ? $recherche_id : 0);
}

if ($filtre_statut !== '') {
    $conditions[] = "statut = ?";
    $params[] = $filtre_statut;
}

if ($filtre_priorite !== '') {
    $conditions[] = "priorite = ?";
    $params[] = $filtre_priorite;
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets $where");
$stmt->execute($params);
$total_tickets = $stmt->fetchColumn();

$sql = "SELECT * FROM tickets $where ORDER BY created_at DESC LIMIT $par_page OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll();

$total_pages = (int) ceil($total_tickets / $par_page);

$statutClasses = [
    'Nouveau'    => 'bg-secondary',
    'En cours'   => 'bg-primary',
    'En attente' => 'bg-warning text-dark',
    'Résolu'     => 'bg-success',
    'Fermé'      => 'bg-dark',
];

// Reconstruit les paramètres GET actuels (hors "page") pour les liens de pagination
$query_base = array_filter([
    'recherche' => $recherche,
    'statut'    => $filtre_statut,
    'priorite'  => $filtre_priorite,
]);
?>

<main>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="section-title text-start mb-0">Liste des tickets</h1>
        </div>

        <form method="GET" class="step-card mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Recherche</label>
                    <input type="text" name="recherche" class="form-control"
                           placeholder="Numéro, sujet, nom ou e-mail..."
                           value="<?= htmlspecialchars($recherche) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <?php foreach (['Nouveau', 'En cours', 'En attente', 'Résolu', 'Fermé'] as $s): ?>
                            <option value="<?= $s ?>" <?= $filtre_statut === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Priorité</label>
                    <select name="priorite" class="form-select">
                        <option value="">Toutes</option>
                        <?php foreach (['Basse', 'Normale', 'Haute', 'Critique'] as $p): ?>
                            <option value="<?= $p ?>" <?= $filtre_priorite === $p ? 'selected' : '' ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-accent w-100">OK</button>
                </div>
            </div>
            <?php if ($recherche !== '' || $filtre_statut !== '' || $filtre_priorite !== ''): ?>
                <a href="tickets.php" class="d-inline-block mt-2 small">Réinitialiser les filtres</a>
            <?php endif; ?>
        </form>

        <div class="step-card">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sujet</th>
                            <th>Catégorie</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($tickets) === 0): ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucun ticket trouvé.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tickets as $t): ?>
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
                                    <td>
                                        <a href="ticket-details.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-dark">Voir</a>
                                        <a href="update-ticket.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-accent">Modifier</a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#confirmDelete<?= $t['id'] ?>">
                                            Supprimer
                                        </button>

                                        <div class="modal fade" id="confirmDelete<?= $t['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmer la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Supprimer le ticket
                                                        <strong>#TK-<?= str_pad($t['id'], 5, '0', STR_PAD_LEFT) ?></strong>
                                                        (« <?= htmlspecialchars($t['sujet']) ?> ») ?
                                                        Cette action est irréversible.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="delete-ticket.php" method="POST" class="mb-0">
                                                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center mb-0 mt-3">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i === $page_actuelle ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="?page=<?= $i ?>&<?= http_build_query($query_base) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>