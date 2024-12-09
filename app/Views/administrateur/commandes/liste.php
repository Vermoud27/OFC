<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste commandes</title>
    <link rel="stylesheet" href="/assets/css/admin/liste.css">
    <link rel="stylesheet" href="/assets/css/admin/navbar_admin.css">
</head>

<body>
    <?php require APPPATH . 'Views/administrateur/header_admin.php'; ?>

    <div class="container">
        <!-- Panel de statistiques -->
        <div class="panel">
            <h2>Statistiques</h2>
            <div class="statistics">
                <h3>Nombre de commandes</h3>
                <ul>
                    <li><strong>En attente :</strong> <?= $statistiques['en_attente'] ?> commandes</li>
                    <li><strong>Expédiées :</strong> <?= $statistiques['expedie'] ?> commandes</li>
                    <li><strong>Livrées :</strong> <?= $statistiques['livre'] ?> commandes</li>
                    <li><strong>Terminées :</strong> <?= $statistiques['fini'] ?> commandes</li>
                    <li><strong>Annulées :</strong> <?= $statistiques['annule'] ?> commandes</li>
                    <li><strong>Total :</strong> <?= $statistiques['total'] ?> commandes</li>
                </ul>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="product-section">
            <a href="/admin/codes-promos/creation/"><button class="add-product-btn">➕ Ajouter un code promo</button></a>
            <h2>Liste des commandes</h2>

            <div class="product-grid">

                <?php foreach ($commandes as $commande): ?>

                    <!-- Carte Produit 1 -->
                    <div class="product-card">

                        <div class="product-buttons">
                            <a href="commande/produits/<?= $commande['id_commande'] ?>" >
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </div>

                        <div class="product-info">
                            <p><span>ID : </span><?= $commande['id_commande'] ?></p>
                            <p><span>Email utilisateur : </span><?= $commande['mail'] ?></p>
                            <p><span>Date de création : </span><?= date('d/m/Y H:i', strtotime($commande['date_creation'])); ?>
                            <p><span>Status : </span><?= $commande['statut'] ?> </p>
                            <p><span>Adresse : </span><?= $commande['informations'] ?> </p>
                            <p><span>Prix : </span><?= $commande['prixpromo'] ?: $commande['prixttc'] ?> €</p>

                            
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