<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
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
    <section class="section">
        <div class="container">
            <h1 class="title has-text-centered">Nouveau mot de passe</h1>
            <p class="subtitle has-text-centered">Veuillez entrer votre nouveau mot de passe.</p>
            <div class="columns is-centered">
                <div class="column is-half">
                    <form action="/reset-password/updatePassword" method="post">
                        <input type="hidden" name="token" value="<?= esc($token) ?>">
                        <div class="field">
                            <label class="label">Nouveau mot de passe</label>
                            <div class="control">
                                <input class="input" type="password" name="password" placeholder="Nouveau mot de passe"
                                    required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Confirmer le mot de passe</label>
                            <div class="control">
                                <input class="input" type="password" name="confirm_password"
                                    placeholder="Confirmez le mot de passe" required>
                            </div>
                        </div>
                        <div class="field is-grouped">
                            <div class="control">
                                <a href="/signin" class="button is-light">Annuler</a>
                            </div>
                            <div class="control">
                                <button type="submit" class="button is-danger">Envoyer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Script pour masquer les notifications après 4 secondes -->
    <script src="/assets/js/notif.js"></script>

</body>

</html>