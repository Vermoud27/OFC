<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/assets/css/profile.css') ?>">
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
        <div class="header">
            <h1>Mon Profil</h1>
            <a href="/logout" class="btn-logout">Déconnexion</a>
        </div>
        <div class="container profile-container">
            <div class="profile-details">
                <div class="profile-field">
                    <span class="field-label">Nom :</span>
                    <span class="field-value"><?= esc($utilisateur['nom']) ?></span>
                </div>
                <div class="profile-field">
                    <span class="field-label">Prénom :</span>
                    <span class="field-value"><?= esc($utilisateur['prenom']) ?></span>
                </div>
                <div class="profile-field">
                    <span class="field-label">Email :</span>
                    <span class="field-value"><?= esc($utilisateur['mail']) ?></span>
                </div>
                <div class="profile-field">
                    <span class="field-label">Téléphone :</span>
                    <span class="field-value">
                        <?= isset($utilisateur['telephone']) && !empty($utilisateur['telephone'])
                            ? preg_replace('/(\d{2})/', '$1 ', $utilisateur['telephone'])
                            : 'Pas de numéro de téléphone renseigné...' ?>
                    </span>
                </div>
                <div class="profile-field">
                    <span class="field-label">Adresse :</span>
                    <span class="field-value">
                        <?= isset($utilisateur['adresse']) && !empty($utilisateur['adresse'])
                            ? esc($utilisateur['adresse'])
                            : 'Pas d\'adresse renseignée...' ?>
                    </span>
                </div>
                <div class="profile-field">
                    <span class="field-label">Code Postal :</span>
                    <span class="field-value">
                        <?= isset($utilisateur['code_postal']) && !empty($utilisateur['code_postal'])
                            ? esc($utilisateur['code_postal'])
                            : 'Pas de code postal de renseignée...' ?>
                    </span>
                </div>
                <div class="profile-field">
                    <span class="field-label">Ville :</span>
                    <span class="field-value">
                        <?= isset($utilisateur['ville']) && !empty($utilisateur['ville'])
                            ? esc($utilisateur['ville'])
                            : 'Pas de ville de renseignée...' ?>
                    </span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="/" class="btn-return">Retour à l'accueil</a>
                <a href="/profile/edit" class="btn-edit">Modifier mes informations</a>
            </div>
        </div>
        <div class="profile-password-container">
            <div class="profile-actions">
                <a href="/profile/edit-password" class="btn-edit">Modifier mon mot de passe</a>
            </div>
        </div>
    </div>
    <!-- Script pour masquer les notifications après 4 secondes -->
    <script src="/assets/js/notif.js"></script>
</body>

</html>