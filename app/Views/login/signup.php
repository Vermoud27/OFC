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
            <h1>Créer un compte</h1>

            <!-- Ouverture du formulaire avec le helper form_open() -->
            <?= form_open('/signup') ?>

                <!-- Champ E-mail -->
                <div class="form-group">
                    <?= form_input([
                        'type' => 'email',
                        'name' => 'email',
                        'placeholder' => 'E-mail *',
                        'value' => old('email'),
                        'required' => 'required'
                    ]) ?>
                    <small><?= validation_show_error('email') ?></small>
                </div>

                <!-- Champ Mot de passe -->
                <div class="form-group">
                    <?= form_password([
                        'name' => 'password',
                        'placeholder' => 'Mot de passe *',
                        'required' => 'required'
                    ]) ?>
                    <small><?= validation_show_error('password') ?></small>
                </div>

                <!-- Champ Confirmation mot de passe -->
                <div class="form-group">
                    <?= form_password([
                        'name' => 'password_confirmation',
                        'placeholder' => 'Confirmation mot de passe *',
                        'required' => 'required'
                    ]) ?>
                    <small><?= validation_show_error('password_confirmation') ?></small>
                </div>

                <!-- Champ Nom -->
                <div class="form-group">
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'first_name',
                        'placeholder' => 'Nom *',
                        'value' => old('first_name'),
                        'required' => 'required'
                    ]) ?>
                    <small><?= validation_show_error('first_name') ?></small>
                </div>

                <!-- Champ Prénom -->
                <div class="form-group">
                    <?= form_input([
                        'type' => 'text',
                        'name' => 'last_name',
                        'placeholder' => 'Prénom *',
                        'value' => old('last_name'),
                        'required' => 'required'
                    ]) ?>
                    <small><?= validation_show_error('last_name') ?></small>
                </div>

                <!-- Champ Numéro de téléphone -->
                <div class="form-group">
                    <?= form_input([
                        'type' => 'tel',
                        'name' => 'phone',
                        'placeholder' => 'Numéro de téléphone',
                        'value' => old('phone')
                    ]) ?>
                    <small><?= validation_show_error('phone') ?></small>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="btn-signup">Inscription</button>

                <!-- Lien de connexion -->
                <div class="login-link">
                    Déjà un compte ? <a href="/signin">Connectez-vous</a>
                </div>

            <?= form_close() ?> <!-- Fermeture du formulaire -->
        </div>
    </div>

    <!-- Script pour masquer les notifications après 4 secondes -->
    <script src="/assets/js/notif.js"></script>
</body>

</html>
