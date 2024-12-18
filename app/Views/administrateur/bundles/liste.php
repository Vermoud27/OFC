<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bundles</title>
    <link rel="stylesheet" href="/assets/css/admin/liste.css">
    <link rel="stylesheet" href="/assets/css/admin/navbar_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require APPPATH . 'Views/administrateur/header_admin.php'; ?>
   
    <div class="container">
        <!-- Panel de statistiques -->
        <div class="panel">
            <h2>Les favoris</h2>
            <?php foreach ($fav as $bundle): ?>

                <b><span>Nom : </span><?= $bundle['nom'] ?></b>
                <p><span>Quantité vendu : </span><?= $bundle['total_quantite'] ?></p>
                
                <br>
            <?php endforeach; ?>    
        </div>

        <!-- Liste des produits -->
        <div class="product-section">
            <a href="/admin/bundles/creation/"><button class="add-product-btn">➕ Ajouter un bundle</button></a>
            <h2>Liste des Bundles</h2>

            <div class="product-grid">

                <?php foreach ($bundles as $bundle): ?>

                    <!-- Carte Produit 1 -->
                    <div class="product-card">
                        <div class="product-buttons">
                            <a href="/admin/bundles/modification/<?= $bundle['id_bundle'] ?>" >
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <a href="/admin/bundles/supprimer/<?= $bundle['id_bundle'] ?>" >
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>

                        <div class="product-info">
                            <p><span>Description : </span><?= $bundle['description'] ?></p>
                            <p><span>Prix : </span><?= $bundle['prix'] ?> €</p>
                            <p><span>Nombre de produits différents : </span><?= $bundle['produit_count'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="footer">
                <?= $pager->links('default','perso') ?>
            </div>
        </div>
    </div>

</body>
</html>
