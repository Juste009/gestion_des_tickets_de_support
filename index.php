<?php
$pageTitle = "Support-ticket – Signalez votre problème informatique";
require_once __DIR__ . '/includes/header.php'; // <head>, liens CSS/Bootstrap — géré par le binôme
require_once __DIR__ . '/includes/navbar.php';
?>
<!-- HERO -->
<header class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <p class="hero-eyebrow">Support informatique interne</p>
        <h1 class="hero-title">
          Besoin d'aide ? Signalez votre problème en quelques clics.
        </h1>
        <p class="hero-subtitle">
          Créez un ticket, suivez son traitement et retrouvez son statut à tout moment
          grâce à son numéro de suivi.
        </p>
        <a href="create-ticket.php" class="btn btn-accent btn-lg">Créer un ticket</a>
      </div>
      <div class="col-lg-5 d-none d-lg-flex justify-content-end">
        <div class="hero-ticket-card">
          <span class="badge badge-status">EN COURS</span>
          <p class="ticket-card-id">#TK-00025</p>
          <p class="ticket-card-subject">Imprimante hors service</p>
          <p class="ticket-card-meta">Matériel · Priorité haute</p>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- COMMENT ÇA MARCHE -->
<section class="section-steps">
  <div class="container">
    <h2 class="section-title">Comment ça marche ?</h2>
    <div class="row g-4 mt-2">
      <div class="col-md-4">
        <div class="step-card">
          <span class="step-index">01</span>
          <h3>Créer un ticket</h3>
          <p>Décrivez votre problème via un formulaire simple et rapide.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step-card">
          <span class="step-index">02</span>
          <h3>Analyse du problème</h3>
          <p>Le support technique consulte et prend en charge votre demande.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="step-card">
          <span class="step-index">03</span>
          <h3>Résolution</h3>
          <p>Vous suivez l'évolution jusqu'à la résolution complète.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CATÉGORIES -->
<section class="section-categories">
  <div class="container">
    <h2 class="section-title">Catégories de problèmes</h2>
    <div class="row g-4 mt-2">
      <?php
      $categories = [
        ['Matériel', 'Ordinateur, imprimante, périphériques'],
        ['Logiciel', 'Applications, installations, bugs'],
        ['Réseau', 'Connexion, Wi-Fi, VPN'],
        ['Messagerie', 'Accès et envoi/réception d\'e-mails'],
        ['Accès / compte', 'Mot de passe, permissions'],
        ['Autre', 'Toute autre demande technique'],
      ];
      foreach ($categories as $cat): ?>
        <div class="col-sm-6 col-lg-4">
          <div class="category-card">
            <h3><?= htmlspecialchars($cat[0]) ?></h3>
            <p><?= htmlspecialchars($cat[1]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; // géré par le binôme ?>