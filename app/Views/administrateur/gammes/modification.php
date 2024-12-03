<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de Gamme</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">

    <style>
        .container {
            display: flex;
            gap: 30px;
        }
        .product-list {
            flex: 1;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .product-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-list th, .product-list td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .product-list button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .product-list button:hover {
            background-color: #0056b3;
        }
        .form-container {
            flex: 2;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">

    <div class="product-list">
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
                    <?php foreach ($produits as $produit): ?>
                        <?php if (is_null($produit['id_gamme'])): ?>
                            <tr>
                                <td><?= htmlspecialchars($produit['nom']) ?></td>
                                <td><?= htmlspecialchars($produit['prixttc']) ?> €</td>
                                <td>
                                    <form action="/admin/gammes/ajouter-produit/<?= $gamme['id_gamme'] ?>" method="post">
                                        <input type="hidden" name="produit_id" value="<?= $produit['id_produit'] ?>">
                                        <button type="submit">+</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Liste des produits assignés à la gamme -->
        
            <h3>Produits Assignés à la Gamme</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix TTC</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <?php if ($produit['id_gamme'] == $gamme['id_gamme']): ?>
                            <tr>
                                <td><?= htmlspecialchars($produit['nom']) ?></td>
                                <td><?= htmlspecialchars($produit['prixttc']) ?> €</td>
                                <td>
                                    <form action="/admin/gammes/enlever-produit/<?= $gamme['id_gamme'] ?>" method="post">
                                        <input type="hidden" name="produit_id" value="<?= $produit['id_produit'] ?>">
                                        <button type="submit">-</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        
       
       
        <!-- Formulaire de modification -->
        <div class="form-container">
            <h1>Modifier une Gamme</h1>
            <?php echo form_open('/admin/gammes/modifier/' . $gamme['id_gamme'], ['enctype' => 'multipart/form-data']); ?>

                <div>
                    <?php echo form_label('Nom', 'nom'); ?>
                    <?php echo form_input('nom', set_value('nom', $gamme['nom']), 'required'); ?>
                    <?= validation_show_error('nom') ?>
                </div>
                
                <div class="grid-full">
                    <?php echo form_label('Description', 'description'); ?>
                    <?php echo form_textarea('description', set_value('description', $gamme['description']), 'required'); ?>
                    <?= validation_show_error('description') ?>
                </div>

                <div class="grid-2-columns">
                    <div>
                        <?php echo form_label('Prix HT', 'prixht'); ?>
                        <?php echo form_input('prixht', set_value('prixht', $gamme['prixht']), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '0.01',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('prixht') ?>
                    </div>
                    <div>
                        <?php echo form_label('Prix TTC', 'prixttc'); ?>
                        <?php echo form_input('prixttc', set_value('prixttc', $gamme['prixttc']), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '0.01',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('prixttc') ?>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="submit-btn">Mettre à jour la gamme</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/gammes';">Annuler</button>
                </div>

            <?php echo form_close(); ?>
        </div>
    </div>

</body>
</html>
