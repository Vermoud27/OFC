<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des codes promo</title>
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
            <p>Ce tableau affiche les codes promo et leurs détails respectifs.</p>
        </div>

        <!-- Liste des produits -->
        <div class="product-section">
            <a href="/admin/codes-promos/creation/"><button class="add-product-btn">➕ Ajouter un code promo</button></a>
            <h2>Liste codes promo</h2>

            <div class="product-grid">

                <?php foreach ($codes as $code): ?>

                    <!-- Carte Produit 1 -->
                    <div class="product-card">
                        <div class="product-buttons">
                            <a href="/admin/codes-promos/modification/<?= $code['id_codepromo'] ?>"><button
                                    class="edit-btn">✏️</button></a>
                            <a href="/admin/codes-promos/supprimer/<?= $code['id_codepromo'] ?>"><button
                                    class="delete-btn">🗑️</button></a>
                        </div>

                        <div class="product-info">
                            <p><span>Code : </span><?= $code['code'] ?></p>
                            <p><span>Actif : </span><?= $code['actif'] ? 'Oui' : 'Non' ?></p>
                            <p><span>Valeur : </span><?= $code['valeur'] ?> €</p>
                            <p><span>Pourcentage :
                                </span><?= isset($code['pourcentage']) && !empty($code['pourcentage']) ? $code['pourcentage'] . '%' : 'Pas de pourcentage' ?>
                            </p>
                            <p><span>Description : </span><?= $code['conditions'] ?></p>
                            <p>
                                <span>Actif du : </span><?= date('d/m/Y', strtotime($code['date_debut'])) ?><span> au :
                                </span><?= date('d/m/Y', strtotime($code['date_fin'])) ?>
                            </p>
                            <p><span>Utilisé </span><?= $code['utilisation_actuelle'] ?><span> fois sur
                                </span></span><?= $code['utilisation_max'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="footer">
                <?= $pager->links('default', 'perso') ?>
            </div>
        </div>
    </div>

</body>

</html>