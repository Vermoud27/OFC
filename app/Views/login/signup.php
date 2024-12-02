<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/signup.css">
    <link rel="stylesheet" href="/assets/css/notif.css">
</head>

<body>
    <!-- Affichage des messages flash -->
    <section class="section">
        <div class="container">
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
    </section>
    <!-- Formulaire d'inscription -->
    <div class="container">
        <div class="left-section"></div>
        <div class="right-section">
            <h1>Sign In</h1>
            <form action="/signup" method="POST">
                <div class="form-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Mot de passe" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirmation" placeholder="Confirmation mot de passe"
                        required>
                </div>
                <div class="form-group">
                    <input type="text" name="first_name" placeholder="Nom" required>
                </div>
                <div class="form-group">
                    <input type="text" name="last_name" placeholder="Prénom" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Numéro de téléphone" required>
                </div>
                <button type="submit" class="btn-signup">Inscription</button>
                <div class="login-link">
                    Déjà un compte ? <a href="/signin">Connectez-vous</a>
                </div>
            </form>
        </div>
    </div>
    <!-- Script pour masquer les notifications après 4 secondes -->
    <script src="/assets/js/notif.js"></script>
</body>

</html>