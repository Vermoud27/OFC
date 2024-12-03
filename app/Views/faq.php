<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - OFC Naturel</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/faq.css">
    <script src="/assets/js/faq.js"></script>
</head>

<body>
    <?php require APPPATH . 'Views/header.php'; ?>

    <div class="faq-container">
        <h1 class="faq-title">Vos grandes questions</h1>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                Sed ut perspiciatis ?
                <span class="arrow">▼</span>
            </div>
            <div class="faq-answer">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.
            </div>
        </div>
    </div>

    <div class="contact-section">
        <h2>Vous ne trouvez pas votre question ?</h2>
        <form action="/send-contact" method="POST">
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <textarea name="message" placeholder="Description" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>

    <?php require APPPATH . 'Views/footer.php'; ?>
</body>

</html>