<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste commandes</title>
    <link rel="stylesheet" href="/assets/css/admin/liste.css">
    <link rel="stylesheet" href="/assets/css/admin/navbar_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
<style>

.filter-section label {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
}

.filter-section select {
    width: 100%;
    padding: 5px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.filter-section button {
    margin-top: 10px;
    width: 100%;
    padding: 5px 15px;
    font-size: 14px;
    color: white;
    border: none;
    border-radius: 5px;
    background-color: #D2691E;
    transition: background-color 0.3s ease;
}

.filter-section button:hover {
    background-color: #A0522D;
}
    </style>
</head>

<body>
    <?php require APPPATH . 'Views/administrateur/header_admin.php'; ?>

    <div class="container">
        <!-- Panel de statistiques -->
        <div class="panel">
<!-- Section de filtrage à gauche -->
<div class="filter-section">
        <form method="get" action="/admin/commandes">
            <label for="statuts">Filtrer par statut :</label>
            <select name="statuts[]" id="statuts" multiple>
                <?php foreach ($tousStatuts as $statut): ?>
                    <option value="<?= $statut ?>" 
                        <?= in_array($statut, $statutsSelectionnes) ? 'selected' : '' ?>>
                        <?= ucfirst($statut) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrer</button>
        </form>
    </div>

            <h2>Statistiques</h2>
            <div class="statistics">
                <h3>Nombre de commandes</h3>
                <ul>
                    <li><strong>En attente :</strong> <?= $statistiques['en_attente'] ?> commandes</li>
                    <li><strong>Expédiées :</strong> <?= $statistiques['expedie'] ?> commandes</li>
                    <li><strong>Terminées :</strong> <?= $statistiques['fini'] ?> commandes</li>
                    <li><strong>Annulées :</strong> <?= $statistiques['annule'] ?> commandes</li>
                    <li><strong>Total :</strong> <?= $statistiques['total'] ?> commandes</li>
                </ul>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="product-section">
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