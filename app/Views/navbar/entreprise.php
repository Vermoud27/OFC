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
        <div class="section" id="info">
        <h1>L'Histoire d'OFC Naturel : </h1>
        <h1> Une Entreprise Inspirée par la Nature et l’Héritage </h1>
        <p>
            <strong>OFC Naturel</strong> est une entreprise qui incarne l’ambition, la passion et l’héritage culturel de sa fondatrice, <strong>Diahomba GASSAMA</strong>. 
            L’aventure commence le <strong>17 mai 2013</strong>, lorsque Diahomba, alors infirmière, décide de prendre un virage audacieux dans sa vie professionnelle. 
        </p>
        <br/>
        <p>
            Bien que son métier reflète son désir d’aider les autres, elle ne s'y sentait pas pleinement épanouie. Une petite voix en elle lui rappelait des souvenirs 
            précieux de son enfance, passés aux côtés de sa mère en Guinée.
        </p>
        <br/>
        <p>
            Depuis son plus jeune âge, sa mère l'avait initiée à l’art de prendre soin de soi en utilisant les trésors naturels de leur terre natale, tels que :
        </p>
        <br/>
        <ul>
            <li>Le beurre de karité</li>
            <li>Les huiles végétales</li>
            <li>Les plantes médicinales</li>
        </ul>
        <br/>
        <p>
            Ces souvenirs, empreints de tradition et de respect pour la nature, ont guidé Diahomba vers sa vocation : <em>partager ces richesses naturelles avec le monde.</em>
        </p>
        <br/>
        <p>
            En 2013, elle décide de se lancer. Avec ses économies, elle importe un kilo de beurre de karité directement de Guinée. Déterminée et passionnée, 
            elle divise ce kilo en dix portions qu’elle propose à son entourage. Grâce au bouche-à-oreille, tout le stock est vendu en une semaine. 
            Ce succès fulgurant lui donne la force de continuer.
        </p>
        <br/>
        <br/>
        <h2>Une Expansion Progressive</h2>
        <p>
            Au fil des années, OFC Naturel se développe progressivement. Diahomba diversifie son offre avec des produits naturels venant de différents pays africains, tels que :
        </p>
        <br/>
        <ul>
            <li>Huile de baobab</li>
            <li>Savon noir</li>
            <li>Huiles essentielles</li>
        </ul>
        <br/>
        <p>
            En <strong>2019</strong>, un tournant majeur s’opère : Diahomba collabore avec un laboratoire spécialisé pour créer la première gamme de produits signée OFC Naturel : 
            <strong>la gamme au Nila</strong>. Inspirée de l’indigo naturel traditionnellement utilisé en Afrique de l’Ouest, cette gamme allie savoir-faire ancestral et innovation cosmétique.
        </p>
        <br/>
        <p>
            Aujourd’hui, OFC Naturel célèbre la richesse des traditions africaines tout en offrant des solutions naturelles et authentiques pour le bien-être de sa clientèle.
        </p>
    </div>

    <div class="section" id="info">
        <h1>Description de OFC Naturel</h1>
        <p>
            <strong>OFC Naturel</strong> est une marque artisanale et écoresponsable spécialisée dans les cosmétiques naturels. Basée au Havre, elle propose des produits conçus à partir :
        </p>
        <br/>
        <ul>
            <li>D’ingrédients biologiques et naturels</li>
            <li>D’actifs comme le karité, le nila et l’huile d’argan</li>
            <li>Issus de coopératives locales, récoltés de manière équitable</li>
        </ul>
        <br/>
        <br/>
        <h2>Engagements</h2>
        <p>OFC Naturel repose sur trois piliers fondamentaux :</p>
        <br/>
        <ul>
            <li><strong>Authenticité</strong> : Des produits fabriqués de manière artisanale</li>
            <li><strong>Écoresponsabilité</strong> : Emballages recyclables ou biodégradables</li>
            <li><strong>Transparence</strong> : Communication claire sur l’origine et la composition</li>
        </ul>
        <br/>
        <br/>
        <h2>Distribution</h2>
        <p>
            Actuellement, la marque est distribuée via :
        </p>
        <br/>
        <ul>
            <li>Une boutique au 1 rue de Bruneval, Le Havre</li>
            <li>Des salons professionnels et marchés artisanaux</li>
            <li>(Prochainement) Un site web pour toucher une audience plus large</li>
        </ul>
        <br/>
        <p>
            Avec son positionnement unique, OFC Naturel s’adresse à une clientèle soucieuse de consommer de manière responsable, tout en recherchant des soins efficaces et respectueux de la peau.
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
        <div id="contact" class="contact-section">
            <h1>Nous contacter</h1>
            <form action="<?= base_url('/contact/send') ?>" method="POST">
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