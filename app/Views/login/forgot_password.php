<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/forgot_password.css">
</head>
<body>
    <div class="container">
        <h1>Réinitialisez votre mot de passe</h1>
        <p>Veuillez entrer votre adresse e-mail pour recevoir un lien de réinitialisation.</p>
        <form action="/forgot-password" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="E-mail" required>
            </div>
            <button type="submit" class="btn-submit">Envoyer</button>
        </form>
        <a href="/signin" class="cancel-link">Annuler</a>
    </div>
</body>
</html>
