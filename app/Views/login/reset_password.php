<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/reset_password.css">
    <link rel="stylesheet" href="/assets/css/notif.css">
</head>

<body>
    <!-- Affichage des flashdata sous forme de notification -->
    <div id="notifications" class="notifications">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="notification is-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="container">
        <h1>Réinitialisez votre mot de passe</h1>
        <p>Veuillez entrer un nouveau mot de passe pour votre compte.</p>
        <form action="/reset-password/updatePassword" method="post">
            <input type="hidden" name="token" value="<?= esc($token) ?>">
            <div class="form-group">
                <input type="password" name="password" placeholder="Nouveau mot de passe" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" required>
            </div>
            <button type="submit" class="btn-submit">Réinitialiser</button>
        </form>
        <a href="/signin" class="cancel-link">Annuler</a>
    </div>

    <!-- Script pour masquer les notifications après 4 secondes -->
    <script src="/assets/js/notif.js"></script>

</body>

</html>