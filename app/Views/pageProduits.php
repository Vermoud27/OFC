<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/pageProduits.css">
</head>

<main>
    <section class="products">
        <h1>Nos Produits</h1>
        <div class="product-grid">
            <div class="product-card">
                <img src="https://via.placeholder.com/150" alt="Produit 1">
                <h2>Produit 1</h2>
                <p class="description">Une courte description du produit 1.</p>
                <p class="price">19,99 €</p>
                <button>Ajouter au panier</button>
            </div>
            <div class="product-card">
                <img src="https://via.placeholder.com/150" alt="Produit 2">
                <h2>Produit 2</h2>
                <p class="description">Une courte description du produit 2.</p>
                <p class="price">29,99 €</p>
                <button>Ajouter au panier</button>
            </div>
            <div class="product-card">
                <img src="https://via.placeholder.com/150" alt="Produit 3">
                <h2>Produit 3</h2>
                <p class="description">Une courte description du produit 3.</p>
                <p class="price">39,99 €</p>
                <button>Ajouter au panier</button>
            </div>
        </div>
    </section>
</main>


<?php 
require 'footer.php';
?>