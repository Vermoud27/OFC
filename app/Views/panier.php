<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Panier</title>
  <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
<link rel="stylesheet" href="/assets/css/panier.css">
</head>
<body>
  <div class="container">
    <h1>Mon Panier</h1>
    <div class="cart-item">
      <img src="/assets/img/produits/savon_rouge_4.jpeg" alt="Produit 1">
      <div class="cart-item-details">
        <h2>Produit1</h2>
        <p>hk68464654</p>
        <p>500g</p>
        <p class="stock-status">en stock</p>
        <div class="quantity">
          <button>-</button>
          <input type="text" value="1">
          <button>+</button>
        </div>
      </div>
      <p class="price">44.50 €</p>
    </div>

    <div class="cart-item">
      <img src="/assets/img/produits/huile_baobab_1.jpeg" alt="Produit 2">
      <div class="cart-item-details">
        <h2>Produit2</h2>
        <p>hk68464654</p>
        <p>500g</p>
        <p class="stock-status">en stock</p>
        <div class="quantity">
          <button>-</button>
          <input type="text" value="1">
          <button>+</button>
        </div>
      </div>
      <p class="price">44.50 €</p>
    </div>

    <div class="promo-code">
      <input type="text" placeholder="Saisir un code promo">
      <button>Envoyer</button>
    </div>

    <div class="total">
      <p>Total : <span>89 €</span></p>
    </div>
    <button class="checkout">Commander</button>
  </div>
</body>
</html>

<?php 
require 'footer.php';
?>