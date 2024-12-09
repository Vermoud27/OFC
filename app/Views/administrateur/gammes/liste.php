<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Gammes</title>
    <link rel="stylesheet" href="/assets/css/admin/liste.css">
    <link rel="stylesheet" href="/assets/css/admin/navbar_admin.css">
</head>

<body>
    <?php require APPPATH . 'Views/administrateur/header_admin.php'; ?>
   
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
                            <p><span>Prix HT : </span><?= $gamme['prixht'] ?></p>
                            <p><span>Prix TTC : </span><?= $gamme['prixttc'] ?></p>
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
