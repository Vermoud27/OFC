<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des produits</title>
    <link rel="stylesheet" href="/assets/css/admin/liste.css">
    <link rel="stylesheet" href="/assets/css/admin/navbar_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require APPPATH . 'Views/administrateur/header_admin.php'; ?>
    <div class="container">
        <!-- Panel de statistiques -->
        <div class="panel">

        <div class="filter-section">
            <form method="get" action="/admin/produits">
                <label for="filtre">Afficher :</label>
                <select name="filtre" id="filtre" onchange="this.form.submit()">
                    <option value="t" <?= $filtre === 't' ? 'selected' : '' ?>>Actifs</option>
                    <option value="f" <?= $filtre === 'f' ? 'selected' : '' ?>>Inactifs</option>
                </select>
            </form>
        </div>

            <h2>Les favoris</h2>
            <?php foreach ($fav as $produit): ?>

                <b><span>Nom : </span><?= $produit['nom'] ?></b>
                <p><span>Quantité vendu : </span><?= $produit['total_quantite'] ?></p>
                
                <br>
            <?php endforeach; ?>    
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
                            <a href="/admin/produits/modification/<?= $produit['id_produit'] ?>">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <a href="/admin/produits/desactiver/<?= $produit['id_produit'] ?>">
                                <?= $filtre === 't' ? '<i class="fa-solid fa-trash-can"></i>' : '<i class="fa-regular fa-circle-up"></i>' ?>
                            </a>
                        </div>

                        <?php if (!empty($produit['images'])): ?>
                            <div class="image-gallery">
                                <div class="image-wrapper">
                                    <?php if (count($produit['images']) > 1): ?>
                                        <button class="prev-btn" onclick="changeImage(<?= $produit['id_produit'] ?>, -1)">&lt;</button>
                                    <?php endif; ?>
                                    
                                    <img src="<?= $produit['images'][0]['chemin'] ?>" alt="Image Produit" class="product-image"
                                        id="image-<?= $produit['id_produit'] ?>">

                                    <?php if (count($produit['images']) > 1): ?>
                                        <button class="next-btn" onclick="changeImage(<?= $produit['id_produit'] ?>, 1)">&gt;</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="image-gallery">
                                <div class="image-wrapper">
                                    <img src="/assets/img/produits/placeholder.png" alt="Aucune image disponible" class="product-image">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="product-info">
                            <p><span>Nom : </span><?= $produit['nom'] ?></p>
                            <p><span>Description : </span><?= $produit['description'] ?></p>
                            <p><span>Prix HT : </span><?= $produit['prixht'] ?> €</p>
                            <p><span>Prix TTC : </span><?= $produit['prixttc'] ?> €</p>
                            <p><span>Quantité en stock : </span><?= $produit['qte_stock'] ?></p>
                            <p><span>Unité : </span> <?= $produit['unite_mesure'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="footer">
                <?= $pager->links('default', 'perso') ?>
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