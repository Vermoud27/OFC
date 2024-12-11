<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bundles</title>
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
        <h1>Nos Bundles</h1>
        <div class="product-grid">

            <?php if (!empty($bundles) && is_array($bundles)): ?>
                <?php foreach ($bundles as $bundle): ?>
                    <div class="product-card">
                        <a href="/produits/bundles/<?= $bundle['id_bundle'] ?>">
                            <?php if (!empty($bundle['images'])): ?>
                                <div class="product-images">
                                    <img src="<?= $bundle['images'][0]['chemin'] ?>" alt="Image Gamme"
                                        id="image-<?= $bundle['id_bundle'] ?>">
                                </div>
                            <?php else: ?>
                                <div class="product-images">
                                    <img src="/assets/img/produits/placeholder.png" alt="Aucune image disponible">
                                </div>
                            <?php endif; ?>
                            
                            <p><?= $bundle['description'] ?></p>
                            <p><?= $bundle['prix'] ?> €</p>
                        </a>
                        <button onclick="updateQuantityBundle(<?= $bundle['id_bundle'] ?>, 1);">Ajouter au panier</button>
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