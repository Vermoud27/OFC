<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nom Produit</title>
  <link rel="stylesheet" href="/assets/css/header.css">
  <link rel="stylesheet" href="/assets/css/footer.css">
  <link rel="stylesheet" href="/assets/css/infoProduit.css">

</head>
<?php
require 'header.php';
?>
<body>
  <div class="container">
      <h1>Accueil - Nom Produit</h1>
    <main>
      <div class="product">
        <div class="product-images">
          <button class="nav-btn">&lt;</button>
          <img src="/assets/img/produits/gommage_clarifiant.jpeg" alt="Produit 1" class="main-image">
          <button class="nav-btn">&gt;</button>
        </div>
        <div class="product-details">
          <h2>Produit 1</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <select>
            <option>50 cl</option>
          </select>
          <div class="info-grid">
            <div class="composition">
              <h3>Composition</h3>
              <ul>
                <li>lorem ipsum</li>
                <li>lorem ipsum</li>
                <li>lorem ipsum</li>
                <li>lorem ipsum</li>
              </ul>
            </div>
            <div class="payment-delivery">
              <h3>Paiement et livraison</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
          </div>
          <button class="add-to-cart">Ajouter au panier</button>
        </div>
      </div>
      <div class="user-reviews">
        <div class="review">
          <h4>Utilisateur 1</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
        <div class="review">
          <h4>Utilisateur 1</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
        <button class="see-more">Voir plus</button>
      </div>
      <div class="related-products">
        <img src="/assets/img/produits/gommage_eclat_1.jpeg" alt="Produit lié 1">
        <img src="/assets/img/produits/huile_baobab_3.jpeg" alt="Produit lié 2">
        <img src="/assets/img/produits/masque_safran_1.jpeg" alt="Produit lié 3">
        <img src="/assets/img/produits/savon_bleu.jpeg" alt="Produit lié 4">
      </div>
    </main>
  </div>
    
<?php 
require 'footer.php';
?>
