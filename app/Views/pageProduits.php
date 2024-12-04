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
            <?php if (!empty($produits) && is_array($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <div class="product-card">
                    <?php if (!empty($images)): ?>
                        <div class="product-images">
                            <div class="image-wrapper">
                                <button class="nav-btn" onclick="changeImage(-1)">&lt;</button>
                                <img src="<?= htmlspecialchars($images[0]['chemin']) ?>" alt="Image Produit" class="product-image" id="product-image">
                                <button class="nav-btn" onclick="changeImage(1)">&gt;</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="product-images">
                            <div class="image-wrapper">
                                <img src="/assets/img/user.png" alt="Aucune image disponible" class="product-image">
                            </div>
                        </div>
                    <?php endif; ?>

                    <h2><?= $produit['nom'] ?></h2>
                    <p><?= $produit['description'] ?></p>
                    <p><?= $produit['prixttc'] ?> €</p>
                    <button>Ajouter au panier</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>


<?php 
require 'footer.php';
?>