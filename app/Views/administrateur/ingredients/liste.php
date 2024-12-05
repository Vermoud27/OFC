<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des ingrédients</title>
    <link rel="stylesheet" href="/assets/css/admin/liste.css">
    <link rel="stylesheet" href="/assets/css/admin/navbar_admin.css">
</head>

<body>
    <?php require APPPATH . 'Views/administrateur/header_admin.php'; ?>

    <?php if (isset($_GET['warning']) && isset($_GET['id'])): ?>
        <div class="alert alert-warning">
            Cet ingrédient est lié à des produits. Voulez-vous le supprimer ?
            <form action="/admin/ingredients/supprimer/<?= htmlspecialchars($_GET['id']) ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit" name="confirm" value="yes">Oui, Supprimer</button>
                <a href="/admin/ingredients"><button type="button">Annuler</button></a>
            </form>
        </div>
    <?php endif; ?>


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
            <a href="/admin/ingredients/creation/"><button class="add-product-btn">➕ Ajouter un ingrédient</button></a>
            <h2>Liste des Ingrédients</h2>

            <div class="product-grid">

                <?php foreach ($ingredients as $ingredient): ?>

                    <!-- Carte Produit 1 -->
                    <div class="product-card">
                        <div class="product-buttons">
                            <a href="/admin/ingredients/modification/<?= $ingredient['id_ingredient'] ?>" ><button class="edit-btn">✏️</button></a>
                            <a href="/admin/ingredients/supprimer/<?= $ingredient['id_ingredient'] ?>" ><button class="delete-btn">🗑️</button></a>
                        </div>

                        <div class="product-info">
                            <p><span>Nom : </span><?= $ingredient['nom'] ?></p>
                            <p><span>Provenance : </span><?= $ingredient['provenance'] ?></p>
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
