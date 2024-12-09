<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de Bundle</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">

</head>
<body>
    <div class="container">

    <div class="product-list">
    <!-- Section des produits non assignés -->
    <h3>Produits Non Assignés</h3>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prix</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits_non_assignes as $produit): ?>
                <tr>
                    <td><?= htmlspecialchars($produit['nom']) ?></td>
                    <td><?= htmlspecialchars($produit['prixttc']) ?> €</td>
                    <td>
                        <form action="/admin/bundles/ajouter-produit/<?= $bundle['id_bundle'] ?>" method="post">
                            <input type="hidden" name="produit_id" value="<?= $produit['id_produit'] ?>">
                            <input type="number" name="quantite" value="1" min="1" required>
                            <button type="submit">+</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Section des produits assignés au bundle -->
    <h3>Produits Assignés au Bundle</h3>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prix TTC</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits_assignes as $produit): ?>
                <tr>
                    <td><?= htmlspecialchars($produit['nom']) ?></td>
                    <td><?= htmlspecialchars($produit['prixttc']) ?> €</td>
                    <td><?= htmlspecialchars($produit['quantite']) ?></td>
                    <td>
                        <form action="/admin/bundles/enlever-produit/<?= $bundle['id_bundle'] ?>" method="post">
                            <input type="hidden" name="produit_id" value="<?= $produit['id_produit'] ?>">
                            <button type="submit">-</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


        
       
       
        <!-- Formulaire de modification -->
        <div class="form-container">
            <h1>Modifier un bundle</h1>
            <?php echo form_open('/admin/bundles/modifier/' . $bundle['id_bundle'], ['enctype' => 'multipart/form-data']); ?>

                <div class="grid-full">
                    <?php echo form_label('Description *', 'description'); ?>
                    <?php echo form_textarea('description', set_value('description', $bundle['description']), 'required'); ?>
                    <?= validation_show_error('description') ?>
                </div>

                
                <div>
                    <?php echo form_label('Prix (€) *', 'prix'); ?>
                    <?php echo form_input('prix', set_value('prix', $bundle['prix']), [
                        'type' => 'number',
                        'min' => '0',
                        'step' => '0.01',
                        'required' => 'required'
                    ]); ?>
                    <?= validation_show_error('prix') ?>
                </div>

                <div class="actions">
                    <button type="submit" class="submit-btn">Mettre à jour le bundle</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/bundles';">Annuler</button>
                </div>

            <?php echo form_close(); ?>
        </div>
    </div>

</body>
</html>
