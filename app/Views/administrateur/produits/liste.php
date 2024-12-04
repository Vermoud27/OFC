<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des produits</title>
    <link rel="stylesheet" href="/assets/css/admin/liste.css">
    <link rel="stylesheet" href="/assets/css/admin/entete.css">
</head>
<body>
    <header>
        <nav>
            <ul class="navigation">
                <li><a href="/admin/produits" class="nav-link">Produits</a></li>
                <li><a href="/admin/categories" class="nav-link">Catégories</a></li>
                <li><a href="/admin/ingredients" class="nav-link">Ingrédients</a></li>
                <li><a href="/admin/gammes" class="nav-link">Gammes</a></li>
                <li><a href="/admin/bundles" class="nav-link">Bundles</a></li>
                <li><a href="/admin/codes-promos" class="nav-link">Codes Promo</a></li>
                <li><a href="/faq/admin" class="nav-link">FAQ</a></li>
            </ul>
        </nav>
    </header>
   
    <div class="container">
        <!-- Panel de statistiques -->
        <div class="panel">
            <h2>Statistiques</h2>
            <div class="stats">
                <p>Total des produits : 150</p>
                <p>Produits en rupture : 20</p>
                <p>Chiffre d'affaires : 12 500€</p>
            </div>
            <h2>Informations</h2>
            <p>Ce tableau affiche les produits disponibles en stock et leurs détails respectifs.</p>
        </div>

        <!-- Liste des produits -->
        <div class="product-section">
            <a href="/admin/produits/creation/"><button class="add-product-btn">➕ Ajouter un produit</button></a>
            <h2>Liste des Produits</h2>

            <div class="product-grid">

                <?php foreach ($produits as $produit): ?>

                    <!-- Carte Produit 1 -->
                    <div class="product-card">
                        <div class="product-buttons">
                        <a href="/admin/produits/modification/<?= $produit['id_produit'] ?>" ><button class="edit-btn">✏️</button></a>
                            <a href="/admin/produits/desactiver/<?= $produit['id_produit'] ?>" ><button class="delete-btn">🗑️</button></a>
                        </div>

                        <?php if (!empty($produit['images'])): ?>
                            <div class="image-gallery">
                                <div class="image-wrapper">
                                    <button class="prev-btn" onclick="changeImage(<?= $produit['id_produit'] ?>, -1)">⬅️</button>
                                    <img src="<?= $produit['images'][0]['chemin'] ?>" alt="Image Produit" class="product-image" id="image-<?= $produit['id_produit'] ?>">
                                    <button class="next-btn" onclick="changeImage(<?= $produit['id_produit'] ?>, 1)">➡️</button>                                
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="image-gallery">
                                <div class="image-wrapper">
                                    <img src="/assets/img/user.png" alt="Aucune image disponible" class="product-image">
                                </div>
                            </div>
                        <?php endif; ?> 

                        <div class="product-info">
                            <p><span>Nom : </span><?= $produit['nom'] ?></p>
                            <p><span>Description : </span><?= $produit['description'] ?></p>
                            <p><span>Prix HT : </span><?= $produit['prixht'] ?></p>
                            <p><span>Prix TTC : </span><?= $produit['prixttc'] ?></p>
                            <p><span>Quantité en stock : </span><?= $produit['qte_stock'] ?></p>
                            <p><span>Unité : </span> <?= $produit['unite_mesure'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="footer">
                <?= $pager->links('default','perso') ?>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour changer l'image en cliquant sur les flèches
        function changeImage(productId, direction) {
            // Récupérer l'élément image
            var productImages = <?= json_encode($produits) ?>;
            var images = productImages.find(product => product.id_produit == productId).images;
            var currentImage = document.getElementById('image-' + productId);
            var currentImageSrc = currentImage.src;
            
            // Trouver l'index de l'image actuellement affichée
            var currentIndex = images.findIndex(image => currentImageSrc.includes(image.chemin));
            
            // Calculer l'index suivant
            var newIndex = (currentIndex + direction + images.length) % images.length;

            // Mettre à jour l'image affichée
            currentImage.src = images[newIndex].chemin;
        }
    </script>
</body>
</html>
