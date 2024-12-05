<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Mes Informations</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/assets/css/edit_profile.css') ?>">
</head>

<body>
    <div class="container">
        <h1>Modifier Mes Informations</h1>

        <div class="container profile-container">
            <form method="post" action="<?= base_url('/profile/update') ?>">
                <div class="profile-details">
                    <div class="profile-field">
                        <label for="nom" class="field-label">Nom :</label>
                        <input type="text" id="nom" name="nom" class="field-input" value="<?= esc($utilisateur['nom']) ?>" required>
                    </div>
                    <div class="profile-field">
                        <label for="prenom" class="field-label">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" class="field-input" value="<?= esc($utilisateur['prenom']) ?>" required>
                    </div>
                    <div class="profile-field">
                        <label for="telephone" class="field-label">Téléphone :</label>
                        <input type="text" id="telephone" name="telephone" class="field-input" value="<?= esc($utilisateur['telephone']) ?>">
                    </div>
                    <div class="profile-field">
                        <label for="adresse" class="field-label">Adresse :</label>
                        <input type="text" id="adresse" name="adresse" class="field-input" value="<?= esc($utilisateur['adresse']) ?>">
                    </div>
                </div>

                <div class="profile-actions">
                    <button type="submit" class="btn-submit">Enregistrer les modifications</button>
                    <a href="<?= base_url('/profile') ?>" class="btn-return">Retour</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
