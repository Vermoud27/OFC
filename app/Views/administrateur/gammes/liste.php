<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Gammes</title>
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
            <?php foreach ($fav as $gamme): ?>

                <b><span>Nom : </span><?= $gamme['nom'] ?></b>
                <p><span>Quantité de produit vendu de cette gamme : </span><?= $gamme['total_quantite'] ?></p>
                
                <br>
            <?php endforeach; ?>    
        </div>

        <!-- Liste des produits -->
        <div class="product-section">
            <a href="/admin/gammes/creation/"><button class="add-product-btn">➕ Ajouter une gamme</button></a>
            <h2>Liste des Gammes</h2>

            <div class="product-grid">

                <?php foreach ($gammes as $gamme): ?>

                    <!-- Carte Produit 1 -->
                    <div class="product-card">
                        <div class="product-buttons">
                            <a href="/admin/gammes/modification/<?= $gamme['id_gamme'] ?>" >
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <a href="/admin/gammes/supprimer/<?= $gamme['id_gamme'] ?>" >
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>

                        <div class="product-info">
                            <p><span>Nom : </span><?= $gamme['nom'] ?></p>
                            <p><span>Description : </span><?= $gamme['description'] ?></p>
                            <p><span>Prix HT : </span><?= $gamme['prixht'] ?> €</p>
                            <p><span>Prix TTC : </span><?= $gamme['prixttc'] ?> €</p>
                            <p><span>Nombre de produits différents : </span><?= $gamme['produit_count'] ?></p>
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
