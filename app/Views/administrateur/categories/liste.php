<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des catégories</title>
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
            <?php foreach ($fav as $categorie): ?>

                <b><span>Nom : </span><?= $categorie['nom'] ?></b>
                <p><span>Quantité de produits de cette catégorie vendu : </span><?= $categorie['total_quantite'] ?></p>
                
                <br>
            <?php endforeach; ?>    
        </div>

        <!-- Liste des categories -->
        <div class="product-section">
            <a href="/admin/categories/creation/"><button class="add-product-btn">➕ Ajouter une catégorie</button></a>
            <h2>Liste des Catégories</h2>

            <div class="product-grid">

                <?php foreach ($categories as $categorie): ?>

                    <!-- Carte Produit 1 -->
                    <div class="product-card">
                        <div class="product-buttons">
                            <a href="/admin/categories/modification/<?= $categorie['id_categorie'] ?>" >
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <a href="/admin/categories/supprimer/<?= $categorie['id_categorie'] ?>" >
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>

                        <div class="product-info">
                            <p><span>Nom : </span><?= $categorie['nom'] ?></p>
                            <p><span>Description : </span><?= $categorie['description'] ?></p>
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
