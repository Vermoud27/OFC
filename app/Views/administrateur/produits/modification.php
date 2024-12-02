<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de Produit</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
</head>
<body>
    <div class="container">
        <!-- Galerie d'images -->
        <div class="gallery">
            <div class="main-image" id="main-image">
                <!-- Affichage de l'image principale -->
                <img src="<?= !empty($images) ? base_url($images[0]['chemin']) : 'https://via.placeholder.com/300x300?text=Aperçu' ?>" alt="Image principale">
            </div>
            <div class="thumbnails" id="thumbnails">
                <!-- Affichage des vignettes -->
                <?php foreach ($images as $image): ?>
                    <div class="thumbnail">
                        <img src="<?= base_url($image['chemin']) ?>" alt="Image miniature">
                        <button class="delete-btn" onclick="removeImage(<?= $image['id_image'] ?>)">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-image-btn">Ajouter une image</button>
            <input type="file" id="image-input" accept="image/*" style="display: none;">
        </div>

        <!-- Formulaire de modification -->
        <div class="form-container">
            <h1>Modifier un Produit</h1>
            <?php echo form_open('/admin/produits/modifier/' . $produit['id_produit'], ['enctype' => 'multipart/form-data']); ?>

                <div class="grid-2-columns">
                    <div>
                        <?php echo form_label('Nom', 'nom'); ?>
                        <?php echo form_input('nom', set_value('nom', $produit['nom']), 'required'); ?>
                        <?= validation_show_error('nom') ?>
                    </div>
                    
					<div>
                        <label for="categorie">Catégorie</label>
                        <select id="categorie" name="categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories as $categorie) : ?>
                                <option value="<?= $categorie['id_categorie']; ?>" <?= ($categorie['id_categorie'] == $produit['id_categorie'] ) ? 'selected' : ''; ?>>
                                    <?= $categorie['nom']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid-full">
                    <?php echo form_label('Description', 'description'); ?>
                    <?php echo form_textarea('description', set_value('description', $produit['description']), 'required'); ?>
                    <?= validation_show_error('description') ?>
                </div>

                <div class="grid-2-columns">
                    <div>
                        <label for="ingredient">Ajouter un ingrédient</label>
                        <input type="text" id="ingredient" name="ingredient" placeholder="Rechercher un ingrédient...">
                        <datalist id="ingredient-option">
                            <?php foreach ($ingredients as $ingredient): ?>
                                <option value="<?= esc($ingredient['nom']); ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
						<button type="button" id="add-ingredient-btn" style="align-self: center;">Ajouter</button>
                    </div>
                    <div>
                        <label for="ingredient-list">Liste des ingrédients</label>
                        <ul class="ingredient-list" id="ingredient-list">
							<?php foreach ($ingredientsMis as $ingredient) : ?>
                                <li value="<?= $ingredient['id_ingredient']; ?>">
                                    <?= $ingredient['nom']; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="grid-3-columns">
                    <div>
                        <label for="quantity">Quantité</label>
                        <input type="number" id="quantity" name="quantity" value="<?= set_value('unite_mesure', $produit['unite_mesure']) ?>">
                    </div>
                    <div>
                        <?php echo form_label('Unité de mesure', 'unite_mesure'); ?>
                        <?php 
                        $options = [
                            'g' => 'g',
                            'mL' => 'mL',
                        ];
                        echo form_dropdown('unite_mesure', $options, set_value('unite_mesure', $produit['unite_mesure']), 'required'); ?>
                        <?= validation_show_error('unite_mesure') ?>
                    </div>
                   
                    <div>
                        <?php echo form_label('Quantité en stock', 'qte_stock'); ?>
                        <?php echo form_input('qte_stock', set_value('qte_stock', $produit['qte_stock']), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '1',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('qte_stock') ?>
                    </div>
                </div>

                <div class="grid-2-columns">
                    <div>
                        <?php echo form_label('Prix HT', 'prixht'); ?>
                        <?php echo form_input('prixht', set_value('prixht', $produit['prixht']), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '0.01',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('prixht') ?>
                    </div>
                    <div>
                        <?php echo form_label('Prix TTC', 'prixttc'); ?>
                        <?php echo form_input('prixttc', set_value('prixttc', $produit['prixttc']), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '0.01',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('prixttc') ?>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="submit-btn">Mettre à jour le produit</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/produits';">Annuler</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <script>
        // Ajoutez la logique JavaScript nécessaire pour la gestion des images et des ingrédients
    </script>

</body>
</html>
