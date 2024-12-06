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
    <link rel="stylesheet" href="/assets/css/notif.css">
    <script src="/assets/js/panier.js"></script>
</head>

<body>
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
        <h1>Nos Produits</h1>
        <div class="product-grid">

            <?php if (!empty($produits) && is_array($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <div class="product-card" 
                         data-url="/produit/<?= $produit['id_produit'] ?>" 
                         onclick="handleCardClick(event, this)"> 
                        
                        <!-- Image du produit -->
                        <?php if (!empty($produit['images'])): ?>
                            <div class="product-images">
                                <img src="<?= $produit['images'][0]['chemin'] ?>" alt="Image Produit" 
                                     id="image-<?= $produit['id_produit'] ?>">
                            </div>
                        <?php else: ?>
                            <div class="product-images">
                                <img src="/assets/img/produits/placeholder.png" alt="Aucune image disponible">
                            </div>
                        <?php endif; ?>

                        <!-- Détails du produit -->
                        <h2><?= htmlspecialchars($produit['nom']) ?></h2>
                        <p><?= htmlspecialchars($produit['description']) ?></p>
                        <p><?= number_format($produit['prixttc'], 2) ?> €</p>

                        <!-- Bouton "Ajouter au panier" -->
                        <button class="add-to-cart" 
                                onclick="updateQuantity(<?= $produit['id_produit'] ?>, 1); event.stopPropagation();">
                            Ajouter au panier
                        </button>
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

<!-- Script pour gérer les clics -->
<script>
    // Fonction pour gérer les clics sur les cartes
    function handleCardClick(event, cardElement) {
        // Si le clic ne provient pas du bouton "Ajouter au panier"
        const target = event.target;
        if (!target.classList.contains('add-to-cart')) {
            // Rediriger vers l'URL de la carte
            const url = cardElement.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        }
    }
</script>

<!-- Script pour masquer les notifications après 4 secondes -->
<script src="/assets/js/notif.js"></script>
</body>
</html>
<?php
require 'footer.php';
?>
