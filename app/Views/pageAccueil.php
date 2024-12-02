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
    <link rel="stylesheet" href="/assets/css/pageAccueil.css">
</head>
<body>


 <!-- Conteneur principal du carrousel -->
 <h1> Les Favoris du moments </h
 <div class="carousel-wrapper">
    <div class="carousel-container">
        <div class="product">
            <img src="/assets/img/produits/huile_baobab_1.jpeg" alt="Huile Baobab">
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


    
<?php 
require 'footer.php';
?>
