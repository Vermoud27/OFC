<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gammes</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/pageProduits.css">
    <link rel="stylesheet" href="/assets/css/notif.css">
    <script src="/assets/js/panier.js"></script>
</head>

<main>
    <!-- Affichage des flashdata sous forme de notification -->
    <div id="notifications" class="notifications">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="notification is-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="notification is-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
    </div>

    <section class="products">
        <h1>Nos Gammes</h1>
        <div class="product-grid">

            <?php if (!empty($gammes) && is_array($gammes)): ?>
                <?php foreach ($gammes as $gamme): ?>
                    <div class="product-card">
                        <a href="/produits/<?= $gamme['id_gamme'] ?>">
                            <?php if (!empty($gamme['image'])): ?>
                                <div class="product-images">
                                    <img src="<?= $gamme['image'] ?>" alt="Image Gamme"
                                        id="image-<?= $gamme['id_gamme'] ?>">
                                </div>
                            <?php else: ?>
                                <div class="product-images">
                                    <img src="/assets/img/produits/placeholder.png" alt="Aucune image disponible">
                                </div>
                            <?php endif; ?>
                            
                            <h2><?= $gamme['nom'] ?></h2>
                            <p><?= $gamme['description'] ?></p>
                            <p><?= $gamme['prixttc'] ?> €</p>
                        </a>
                        <button onclick="updateQuantityGamme(<?= $gamme['id_gamme'] ?>, 1);">Ajouter au panier</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <br>
    <div class="footer">
        <?= $pager->links('default', 'perso') ?>
    </div>
</main>

<!-- Script pour masquer les notifications après 4 secondes -->
<script src="/assets/js/notif.js"></script>
<?php
require 'footer.php';
?>