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
    <script src="/assets/js/panier.js"></script>
</head>

<main>
    <section class="products">
        <h1>Nos Produits</h1>
        <div class="product-grid">

            <?php if (!empty($produits) && is_array($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <div class="product-card">
                    <?php if (!empty($produit['images'])): ?>
                        <div class="product-images">
                            <img src="<?= $produit['images'][0]['chemin'] ?>" alt="Image Produit" id="image-<?= $produit['id_produit'] ?>">
                        </div>
                    <?php else: ?>
                        <div class="product-images">
                            <img src="/assets/img/user.png" alt="Aucune image disponible">
                        </div>
                    <?php endif; ?>

                    

                    <h2><?= $produit['nom'] ?></h2>
                    <p><?= $produit['description'] ?></p>
                    <p><?= $produit['prixttc'] ?> €</p>
                    <button onclick="updateQuantity(<?= $produit['id_produit'] ?>, 1)">Ajouter au panier</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>
    </section>
</main>

<?php 
require 'footer.php';
?>