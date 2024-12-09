<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des codes promo</title>
    <link rel="stylesheet" href="/assets/css/admin/liste.css">
    <link rel="stylesheet" href="/assets/css/admin/navbar_admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php require APPPATH . 'Views/administrateur/header_admin.php'; ?>

    <div class="container">
        <!-- Panel de statistiques -->
        <div class="panel">
            <h2>Statistiques des Codes Promotionnels</h2>
            <ul>
                <li>Total des codes actifs : <?= $stats['total_actifs'] ?></li>
                <li>Total des codes inactifs : <?= $stats['total_inactifs'] ?></li>
            </ul>

            <br>
            <h2>Codes Expirés ou Utilisés au Maximum</h2>
            <?php if (!empty($stats['codes_expirés_ou_max'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Date de fin</th>
                            <th>Utilisations</th>
                            <th>Limite d'utilisations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['codes_expirés_ou_max'] as $code): ?>
                            <tr>
                                <td><?= htmlspecialchars($code['nom']) ?></td>
                                <td><?= htmlspecialchars($code['date_fin']) ?></td>
                                <td><?= htmlspecialchars($code['utilisations']) ?></td>
                                <td><?= htmlspecialchars($code['nb_max_utilisations']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucun code expiré ou utilisé au maximum pour le moment.</p>
            <?php endif; ?>
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
                            <a href="/admin/codes-promos/modification/<?= $code['id_codepromo'] ?>">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <a href="/admin/codes-promos/supprimer/<?= $code['id_codepromo'] ?>">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
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