<?php
$pageTitle = "Créer un ticket - Support-ticket";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<section class="py-5">
    <div class="container" style="max-width: 720px;">
        <h1 class="section-title text-start mb-2">Créer un ticket de support</h1>
        <p class="text-muted mb-4">
            Signalez votre problème informatique en remplissant le formulaire ci-dessous.
        </p>

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
            <form action="traitement-ticket.php" method="POST">
                <h4 class="mb-3">Informations utilisateur</h4>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control" required
                            placeholder="Entrez votre nom"
                            pattern="[A-Za-zÀ-ÿ\s\-']+"
                            title="Uniquement des lettres, espaces, tirets ou apostrophes"
                            value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" required
                                placeholder="Entrez votre prénom"
                                pattern="[A-Za-zÀ-ÿ\s\-']+"
                                title="Uniquement des lettres, espaces, tirets ou apostrophes"
                                value="<?= htmlspecialchars($old['prenom'] ?? '') ?>">
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <input type="email" name="email" id="email" class="form-control" required
                               placeholder="exemple@email.com"
                               pattern="[^@\s]+@[^@\s]+\.[a-zA-Z]{2,}"
                               title="Exemple : nom@domaine.com"
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="service" class="form-label">Service</label>
                        <input type="text" class="form-control" id="service" name="service"
                               placeholder="Votre service"
                               value="<?= htmlspecialchars($old['service'] ?? '') ?>" required>
                    </div>
                </div>

                <hr>
                <h4 class="mb-3">Informations sur le problème</h4>
                <div class="mb-3">
                    <label for="sujet" class="form-label">Objet du ticket</label>
                    <input type="text" class="form-control" id="sujet" name="sujet"
                           placeholder="Ex : Mon ordinateur ne démarre plus"
                           value="<?= htmlspecialchars($old['sujet'] ?? '') ?>" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                    <label for="categorie" class="form-label">Catégorie</label>
                    <select class="form-select" id="categorie" name="categorie" required>
                        <option value="" disabled <?= empty($old['categorie']) ? 'selected' : '' ?>>-- Choisir une catégorie --</option>
                        <?php foreach (['Matériel', 'Logiciel', 'Réseau', 'Messagerie', 'Compte utilisateur', 'Autre'] as $cat): ?>
                            <option value="<?= $cat ?>" <?= ($old['categorie'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="categorieAutreWrapper" class="mt-2 d-none">
                        <input type="text" class="form-control" id="categorie_autre" name="categorie_autre"
                            placeholder="Précisez la catégorie"
                            value="<?= htmlspecialchars($old['categorie_autre'] ?? '') ?>">
                    </div>
                </div>
                    <div class="col-md-6">
                        <label for="priorite" class="form-label">Priorité</label>
                        <select class="form-select" id="priorite" name="priorite" required>
                            <option value="" disabled <?= empty($old['priorite']) ? 'selected' : '' ?>>-- Choisir une priorité --</option>
                            <?php foreach (['Basse', 'Normale', 'Haute', 'Critique'] as $prio): ?>
                                <option value="<?= $prio ?>" <?= ($old['priorite'] ?? '') === $prio ? 'selected' : '' ?>><?= $prio ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="description" class="form-label">Description du problème</label>
                    <textarea class="form-control" id="description" name="description" rows="6"
                              placeholder="Décrivez votre problème en détail..."
                              required><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="piece_jointe" class="form-label">Pièce jointe</label>
                    <input type="file" name="piece_jointe" id="piece_jointe" class="form-control">
                 <div class="form-text">
                    Formats acceptés : JPG, JPEG, PNG, PDF — Taille maximale : 2 Mo.
                </div>
</div>

                <button type="submit" class="btn btn-accent">Envoyer le ticket</button>
            </form>
        </div>
    </div>
</section>

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
    toggleAutre(); // état initial, utile après un retour d'erreur
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>