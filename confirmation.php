<?php
session_start();
$numeroTicket = $_SESSION["ticket_confirme"] ?? null;
unset($_SESSION["ticket_confirme"]);

if (!$numeroTicket) {
    header("Location: create-ticket.php");
    exit;
}

$pageTitle = "Ticket créé - Support-ticket";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="py-5">
    <div class="container text-center" style="max-width: 560px;">
        <div class="step-card">
            <h1 class="section-title">Ticket créé avec succès !</h1>
            <p class="text-muted mb-3">Votre numéro de ticket est :</p>
            <p class="ticket-card-id" style="font-size: 2rem;">#<?= htmlspecialchars($numeroTicket) ?></p>
            <p class="text-muted">
                Conservez ce numéro : il vous permettra de suivre l'évolution de votre demande
                depuis la page « Consulter un ticket ».
            </p>
            <a href="ticket.php" class="btn btn-accent me-2">Consulter ce ticket</a>
            <a href="create-ticket.php" class="btn btn-outline-secondary">Créer un autre ticket</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>