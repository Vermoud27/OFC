<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/assets/css/change_password.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/css/notif.css') ?>">
</head>

<body>
    <!-- Affichage des flashdata sous forme de notification -->
    <div id="notifications" class="notifications">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="notification is-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="notification is-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="container">
        <h1>Modifier mon mot de passe</h1>
        <form action="update-password" method="post">
            <div class="form-group">
                <label for="current-password">Mot de passe actuel</label>
                <input type="password" id="current-password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new-password">Nouveau mot de passe</label>
                <input type="password" id="new-password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Changer mon mot de passe</button>
        </form>
        <a href="/profile" class="back-link">Retour au profil</a>
    </div>
</body>
<!-- Script pour masquer les notifications après 4 secondes -->
<script src="/assets/js/notif.js"></script>

</html>