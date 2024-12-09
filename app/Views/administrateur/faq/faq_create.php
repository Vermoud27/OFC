<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une question</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/admin/faqCU.css">
    <link rel="stylesheet" href="/assets/css/notif.css">
</head>

<body>
    <div class="container-create">
        <h1>Créer une question</h1>
        <!-- Affichage des flashdata sous forme de notification -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="notification is-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/faq/create') ?>" method="post">
            <label for="question">Question *</label>
            <input type="text" id="question" name="question" placeholder="Entrez votre question" required>
            <label for="reponse">Réponse *</label>
            <textarea id="reponse" name="reponse" placeholder="Entrez votre réponse" required></textarea>
            <div style="display: flex; justify-content: space-between;">
                <a href="<?= base_url('/faq/admin') ?>" class="btn-secondary">Annuler</a>
                <button type="submit">Ajouter</button>
            </div>
        </form>
    </div>
    <!-- Script pour masquer les notifications après 4 secondes -->
    <script src="/assets/js/notif.js"></script>
</body>

</html>