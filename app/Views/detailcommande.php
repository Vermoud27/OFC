<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la commande</title>
    <link rel="stylesheet" href="/assets/css/detailcommande.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
</head>

<body>

    <div class="container">

        <!-- Détails de la commande -->
        <div class="commande-section">
            <h2>Détails de la commande #<?= $commande['id_commande'] ?></h2>

            <div class="commande-info">
                <p><span>ID : </span><?= $commande['id_commande'] ?></p>
                <p><span>Date de création : </span><?= date('d/m/Y H:i', strtotime($commande['date_creation'])) ?></p>
                <p><span>Status : </span><?= $commande['statut'] ?></p>
                <p><span>Adresse de livraison : </span><?= $commande['informations'] ?></p>
                <p><span>Prix total : </span><?= $commande['prixpromo'] ?: $commande['prixttc'] ?> €</p>
            </div>
        </div>

        <!-- Liste des produits de la commande -->
        <div class="product-grid">
            <h2>Produits de la commande</h2>

            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td><?= htmlspecialchars($produit['nom']) ?></td>
                            <td><?= $produit['quantite'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php foreach ($gammes as $gamme): ?>
                        <tr>
                            <td><?= htmlspecialchars($gamme['nom']) ?></td>
                            <td><?= $gamme['quantite'] ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php foreach ($bundles as $bundle): ?>
                        <tr>
                            <td><?= htmlspecialchars($bundle['nom']) ?></td>
                            <td><?= $bundle['quantite'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="retour">
        <a href="/commande" class="button">Retour à mes commandes</a>
    </div>

</body>

</html>

<?php
require 'footer.php';
?>
