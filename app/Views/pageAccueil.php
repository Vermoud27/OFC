<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFC Naturel</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/pageAccueil.css">
    <script src="/assets/js/faq.js"></script>
</head>
<body>
<img src="/assets/img/logo/fondOFC.png" alt="Banniere" class="fondOFC">
 <!-- Conteneur principal du carrousel -->
 <h2> Les Favoris du moments </h2>
 <div class="carousel-wrapper">
    <div class="carousel-container">
        <div class="product">
            <a href="/InfoProduitController">
                <img src="/assets/img/produits/huile_baobab_1.jpeg" alt="Huile Baobab">
            </a>
            <p>Huile Baobab</p>
            <p>25,99 €</p>
            <button>Acheter</button>
        </div>
        <div class="product">
            <img src="/assets/img/produits/roll_on_1.jpeg" alt="Roll On">
            <p>Roll On</p>
            <p>13,99 €</p>
            <button>Acheter</button>
        </div>
        <div class="product">
            <img src="/assets/img/produits/savon_rouge_1.jpeg" alt="Savon Rouge">
            <p>Savon Rouge</p>
            <p>9,99 €</p>
            <button>Acheter</button>
        </div>
        <div class="product">
            <img src="/assets/img/produits/gommage_clarifiant.jpeg" alt="Gommage Clarifiant">
            <p>Gommage Clarifiant</p>
            <p>30,99 €</p>
            <button>Acheter</button>
        </div>
    </div>
</div>
<div class="container">
    <div class="gamme">
        <div class="gamme-image">
            <img src="/assets/img/produits/bundle_soin_1.jpeg" alt="Produits de la gamme 1">
            <div class="overlay">
                <button class="button">Découvrez la gamme 1</button>
            </div>
        </div>
    </div>
    <div class="gamme">
        <div class="gamme-image">
            <img src="/assets/img/produits/bundle_huile.jpeg" alt="Produits de la gamme 2">
            <div class="overlay">
                <button class="button">Découvrez la gamme 2</button>
            </div>
        </div>
    </div>
</div>
<h2> Les témoignages de nos clients ! </h2>
<div class="customer-carousel">
  <div class="customer-carousel-wrapper">
    <div class="customer-story-container">
      <div class="image-section">
        <img src="/assets/img/customers/customer_1.png" alt="Image Client" class="customer-image">
      </div>
      <div class="text-section">
        <h2 class="subtitle">"Peau éclatante"<br>Je n'ai jamais eu une peau aussi belle !</h2>
        <p class="description">
          “Depuis que j'utilise la Crème Baobab, ma peau est incroyablement lisse et lumineuse. C'est vraiment un miracle en pot !”
        </p>
      </div>
    </div>

    <div class="customer-story-container">
      <div class="image-section">
        <img src="/assets/img/customers/customer_2.png" alt="Image Client" class="customer-image">
      </div>
      <div class="text-section">
        <h2 class="subtitle">"Résultats incroyables"<br>Je recommande vivement !</h2>
        <p class="description">
          “Le roll-on anti-cerne est absolument incroyable. Mes cernes et poches sont visiblement réduites et ma peau est plus ferme et rajeunie.”
        </p>
      </div>
    </div>
    <div class="customer-story-container">
      <div class="image-section">
        <img src="/assets/img/customers/customer_3.png" alt="Image Client" class="customer-image">
      </div>
      <div class="text-section">
        <h2 class="subtitle">"Peau claire et fraîche"<br>La meilleure routine de soin !</h2>
        <p class="description">
          “Le Masque Clarifiant au Nila a complètement transformé ma routine de soins. Il nettoie en douceur et laisse ma peau fraîche et nette.”
        </p>
      </div>
    </div>
  </div>
</div>
<div class="faq-container">
  <h2>Vos grandes questions</h2>
  <?php if (!empty($faqs)): ?>
      <?php 
      $count = 0; // Initialiser un compteur
      foreach ($faqs as $faq): 
          if ($count >= 2) break; // Arrêter après 2 questions
          $count++;
      ?>
          <div class="faq-item">
              <div class="faq-question">
                  <?= esc($faq['question']); ?>
                  <span class="arrow">▼</span>
              </div>
              <div class="faq-answer">
                  <?= esc($faq['reponse']); ?> 
              </div>
          </div>
      <?php endforeach; ?>
  <?php else: ?>
      <p>Aucune question n'a été trouvée dans la FAQ.</p>
  <?php endif; ?>
  <a href= "<?= base_url('/faq') ?>">
      <p class="more"> Voir plus ... </p>
  </a>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  let index = 0;
  const carouselItems = document.querySelectorAll('.customer-story-container');
  const totalItems = carouselItems.length;
  const carouselWrapper = document.querySelector('.customer-carousel-wrapper');
  
  // Fonction pour afficher le carousel suivant
  function showNextItem() {
    // Incrémenter l'index et revenir à 0 si nécessaire
    index = (index + 1) % totalItems;

    // Appliquer une transformation pour faire défiler horizontalement
    carouselWrapper.style.transform = `translateX(-${index * 33}%)`;
  }

  // Initialiser le carousel
  setInterval(showNextItem, 10000); // 5 secondes entre les transitions
});

</script>

<script>
const carouselContainer = document.querySelector('.carousel-container');
const products = Array.from(document.querySelectorAll('.product'));
const productWidth = products[0].offsetWidth; // Largeur d'un produit
const productMargin = parseInt(getComputedStyle(products[0]).marginRight, 10); // Marge entre les produits
const speed = 0.7; // Vitesse de défilement (ajustez selon vos préférences)
let offset = 0; // Décalage initial du carrousel
let isPaused = false; // Variable pour savoir si l'animation est en pause

// Fonction pour cloner les produits et maintenir un carrousel fluide
function duplicateProducts() {
    const visibleWidth = carouselContainer.offsetWidth;

    // Duplique les produits jusqu'à ce que la largeur totale soit suffisante pour deux fois la taille de l'écran visible
    while (carouselContainer.scrollWidth < visibleWidth * 2) {
        products.forEach(product => {
            const clone = product.cloneNode(true);
            carouselContainer.appendChild(clone);
        });
    }

    // Ajouter les événements à tous les produits, y compris les clones
    const allProducts = Array.from(carouselContainer.querySelectorAll('.product'));
    allProducts.forEach(product => {
        product.addEventListener('mouseover', pauseCarousel); // Met en pause lorsque la souris survole
        product.addEventListener('mouseout', resumeCarousel); // Reprend l'animation lorsque la souris quitte
    });
}

// Fonction pour gérer l'animation
function startCarousel() {
    function animate() {
        if (isPaused) return; // Si le carrousel est en pause, on arrête l'animation

        offset -= speed; // Déplace vers la gauche

        // Si l'extrémité gauche du produit actuel quitte le carrousel, réinitialise la position
        if (Math.abs(offset) >= (productWidth + productMargin)) {
            offset = 0; // Réinitialise le décalage
            // Déplace le premier produit à la fin pour maintenir le flux
            carouselContainer.appendChild(carouselContainer.firstElementChild);
        }

        // Applique le décalage
        carouselContainer.style.transform = `translateX(${offset}px)`;

        requestAnimationFrame(animate); // Prochaine frame
    }

    requestAnimationFrame(animate);
}

// Fonction pour mettre en pause le carrousel
function pauseCarousel() {
    isPaused = true; // Met en pause l'animation
}

// Fonction pour reprendre l'animation
function resumeCarousel() {
    if (isPaused) {
        isPaused = false; // Reprend l'animation
        startCarousel();  // Redémarre l'animation si elle était en pause
    }
}

// Initialisation du carrousel
function setupCarousel() {
    duplicateProducts(); // Assure qu'il y a assez de produits
    startCarousel(); // Lance l'animation
}

// Démarrage
setupCarousel();

</script>
</body>  
<?php 
require 'footer.php';
?>
