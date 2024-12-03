<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos de l'entreprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/entreprise.css">
</head>

<body>
    <?php require APPPATH . 'Views/header.php'; ?>

    <div class="content">
        <!-- Bloc Histoire de l'entreprise -->
        <div class="section">
            <h1>Histoire de l’entreprise</h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.
            </p>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.
            </p>
        </div>

        <!-- Bloc Description de l'entreprise -->
        <div class="section">
            <h1>Description de l’entreprise</h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                deserunt mollit anim id est laborum.
            </p>
        </div>

        <!-- Bloc Carte et réseaux sociaux -->
        <div class="map-section">
            <h1>Venez chez OFC Naturel !</h1>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2654.6212109440864!2d0.10567651596040634!3d49.493897979354195!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e02dfc46209b89%3A0x947187ba6d4026c2!2sLe%20Havre!5e0!3m2!1sen!2sfr!4v1693314547744!5m2!1sen!2sfr"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <p>1 rue de Bruneval, Le Havre 76610</p>
            <p>06 85 42 03 84</p>
        </div>

        <!-- Bloc Contact -->
        <div class="contact-section">
            <h1>Nous contacter</h1>
            <form action="/send-contact" method="POST">
                <input type="text" name="prenom" placeholder="Prénom" required>
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <textarea name="message" placeholder="Description" required></textarea>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>

    <?php require APPPATH . 'Views/footer.php'; ?>
</body>

</html>