<?php
// Détermine la page active pour surligner le bon lien
$current = basename($_SERVER['PHP_SELF']);
$estConnecte = isset($_SESSION['admin_id']);
?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top ticket-navbar">
  <div class="container">
    <a class="navbar-brand ticket-brand" href="/support-ticket/index.php">
      Support<span class="brand-accent">-ticket</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#mainNav" aria-controls="mainNav"
      aria-expanded="false" aria-label="Ouvrir la navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item">
          <a class="nav-link <?= $current === 'index.php' ? 'active' : '' ?>" href="/support-ticket/index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current === 'create-ticket.php' ? 'active' : '' ?>" href="/support-ticket/create-ticket.php">Créer un ticket</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current === 'ticket.php' ? 'active' : '' ?>" href="/support-ticket/ticket.php">Consulter un ticket</a>
        </li>

        <?php if ($estConnecte): ?>
          <li class="nav-item">
            <a class="nav-link <?= $current === 'index.php' && str_contains($_SERVER['REQUEST_URI'], '/admin/') ? 'active' : '' ?>"
               href="/support-ticket/admin/index.php">Administration</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-outline-light ms-lg-2" href="/support-ticket/admin/logout.php">Déconnexion</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="btn btn-accent ms-lg-2" href="/support-ticket/admin/login.php">Administration</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>