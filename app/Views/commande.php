<?php
require 'header.php';
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes commandes</title>
    <link rel="stylesheet" href="/assets/css/commande.css">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
</head>

<body>

    <div class="container">

        <!-- Liste des commandes -->
        <div class="product-section">
            <h2>Liste de mes commandes</h2>

            <div class="product-grid">

                <?php foreach ($commandes as $commande): ?>

                    <!-- Carte Produit 1 -->
                    <div class="product-card">
                    <div class="product-buttons">
                        <a href="javascript:void(0)" onclick="confirmerAnnulation(<?= $commande['id_commande'] ?>)">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </div>


                        <div class="product-info">
                            <p><span>ID : </span><?= $commande['id_commande'] ?></p>
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

<script>
    function confirmerAnnulation(idCommande) {
        const confirmation = confirm("Êtes-vous sûr de vouloir annuler cette commande ?");
        if (confirmation) {
            // Redirige vers l'URL d'annulation si l'utilisateur confirme
            window.location.href = `/commande/annuler/${idCommande}`;
        }
    }
</script>


</html>

<?php
require 'footer.php';
?>