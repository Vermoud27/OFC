<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nous contacter</title>
    </style>
</head>
<body>
    <div class="container">
        <h1>Nous contacter</h1>
        <form action="/send-contact" method="POST">
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="form-group">
                <label for="message">Description</label>
                <textarea id="message" name="message" placeholder="Description" required></textarea>
            </div>
            <button type="submit" class="btn-submit">Envoyer</button>
        </form>
    </div>
</body>
</html>
