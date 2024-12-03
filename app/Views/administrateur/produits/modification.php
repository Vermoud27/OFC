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
                <input type="hidden" name="existing_images[]" value="<?= $image['id_image'] ?>"> <!-- Champ caché pour les images existantes -->
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
                        <select id="categorie" name="categorie" >
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
                        <input list="ingredient-option" type="text" id="ingredient" name="ingredient" placeholder="Rechercher un ingrédient...">
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
                            
                        </ul>

                    </div>
                    <input type="hidden" id="ingredients" name="ingredients">
                    <input type="hidden" name="deleted_ingredients[]" id="deleted-ingredients">
                </div>

                <div class="grid-3-columns">
                    <div>
                    <?php echo form_label('Contenu', 'contenu'); ?>
                        <?php echo form_input('contenu', set_value('contenu',$produit['contenu']), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '1',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('contenu') ?>
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
        const addImageBtn = document.getElementById('add-image-btn');
        const imageInput = document.getElementById('image-input');
        const thumbnails = document.getElementById('thumbnails');
        const mainImage = document.getElementById('main-image').querySelector('img');
        const formContainer = document.querySelector('form'); 

        addImageBtn.addEventListener('click', () => {
            imageInput.click();
        });

        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                // Créer une miniature
                const thumbnailContainer = document.createElement('div');
                thumbnailContainer.classList.add('thumbnail');

                const img = document.createElement('img');
                img.src = e.target.result;

                // Ajouter un événement pour changer l'image principale
                img.addEventListener('click', () => {
                    mainImage.src = e.target.result;
                });

                // Bouton de suppression
                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = "×";
                deleteBtn.classList.add('delete-btn');
                deleteBtn.addEventListener('click', () => {
                    thumbnails.removeChild(thumbnailContainer);

                    // Supprimer l'input file correspondant
                    const hiddenInput = document.querySelector(`input[data-filename="${file.name}"]`);
                    if (hiddenInput) {
                        formContainer.removeChild(hiddenInput);
                    }

                    if (thumbnails.children.length > 0) {
                        mainImage.src = thumbnails.children[0].querySelector('img').src;
                    } else {
                        mainImage.src = "https://via.placeholder.com/300x300?text=Aperçu";
                    }
                });

                thumbnailContainer.appendChild(img);
                thumbnailContainer.appendChild(deleteBtn);
                thumbnails.appendChild(thumbnailContainer);

                // Mettre à jour l'image principale
                mainImage.src = e.target.result;

                // Ajouter un champ input type="file" caché au formulaire
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'file';
                hiddenInput.name = 'images[]';
                hiddenInput.style.display = 'none';
                hiddenInput.files = imageInput.files; // Associe directement le fichier
                hiddenInput.setAttribute('data-filename', file.name); // Identifiant unique pour suppression
                formContainer.appendChild(hiddenInput);
            };

            reader.readAsDataURL(file);
        });

        function removeImage(imageId) {
            const thumbnails = document.getElementById('thumbnails');
            const thumbnailToRemove = document.querySelector(`.thumbnail button[onclick="removeImage(${imageId})"]`).parentElement;
            thumbnails.removeChild(thumbnailToRemove);

            // Ajouter un champ caché pour signaler la suppression
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deleted_images[]'; // Tableau des images supprimées
            hiddenInput.value = imageId;
            document.querySelector('form').appendChild(hiddenInput);

            // Mettre à jour l'image principale si elle est supprimée
            const mainImage = document.getElementById('main-image').querySelector('img');
            if (mainImage.src === thumbnailToRemove.querySelector('img').src && thumbnails.children.length > 0) {
                mainImage.src = thumbnails.children[0].querySelector('img').src;
            } else if (thumbnails.children.length === 0) {
                mainImage.src = "https://via.placeholder.com/300x300?text=Aperçu";
            }
        }

    </script>

    <script>
        const ingredientInput = document.getElementById('ingredient');
        const ingredientList = document.getElementById('ingredient-list');
        const ingredientHiddenInput = document.getElementById('ingredients');
        const addIngredientBtn = document.getElementById('add-ingredient-btn');

        // Récupérer les ingrédients déjà ajoutés (s'ils existent)
        let currentIngredients = JSON.parse(ingredientHiddenInput.value || '[]');

        // Ajouter les ingrédients existants dans la liste
        currentIngredients.forEach(ingredient => {
            const listItem = document.createElement('li');
            listItem.textContent = ingredient;

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'ingredients[]'; // Tableau pour les ingrédients
            hiddenInput.value = ingredient;
            listItem.appendChild(hiddenInput);

            const removeBtn = document.createElement('button');
            removeBtn.textContent = '×';
            removeBtn.classList.add('remove-btn');
            removeBtn.addEventListener('click', () => {
                ingredientList.removeChild(listItem);
                currentIngredients = currentIngredients.filter(ing => ing !== ingredient);
                ingredientHiddenInput.value = JSON.stringify(currentIngredients); // Mettre à jour le champ caché
            });
            listItem.appendChild(removeBtn);

            ingredientList.appendChild(listItem);
        });

        addIngredientBtn.addEventListener('click', () => {
            const ingredientName = ingredientInput.value.trim();
            if (ingredientName && !currentIngredients.includes(ingredientName)) {
                // Créer un nouvel élément de liste pour l'ingrédient
                const listItem = document.createElement('li');
                listItem.textContent = ingredientName;

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'ingredients[]'; // Tableau pour les ingrédients
                hiddenInput.value = ingredientName;
                listItem.appendChild(hiddenInput);

                const removeBtn = document.createElement('button');
                removeBtn.textContent = '×';
                removeBtn.classList.add('remove-btn');
                removeBtn.addEventListener('click', () => {
                    ingredientList.removeChild(listItem);
                    currentIngredients = currentIngredients.filter(ing => ing !== ingredientName);
                    ingredientHiddenInput.value = JSON.stringify(currentIngredients); // Mettre à jour le champ caché
                });
                listItem.appendChild(removeBtn);

                ingredientList.appendChild(listItem);

                // Ajouter l'ingrédient à la liste des ingrédients
                currentIngredients.push(ingredientName);
                ingredientHiddenInput.value = JSON.stringify(currentIngredients); // Mettre à jour le champ caché

                // Réinitialiser le champ de saisie
                ingredientInput.value = '';
            }
        });

        // Initialiser le champ caché avec les ingrédients existants
        const existingIngredients = <?= json_encode(array_column($ingredientsMis, 'nom')); ?>; // Charger les ingrédients existants
        currentIngredients = existingIngredients || [];
        ingredientHiddenInput.value = JSON.stringify(currentIngredients); // Mettre à jour le champ caché

        // Ajouter les ingrédients existants à la liste visible
        existingIngredients.forEach(ingredient => {
            const listItem = document.createElement('li');
            listItem.textContent = ingredient;

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'ingredients[]'; // Tableau pour les ingrédients
            hiddenInput.value = ingredient;
            listItem.appendChild(hiddenInput);

            const removeBtn = document.createElement('button');
            removeBtn.textContent = '×';
            removeBtn.classList.add('remove-btn');
            removeBtn.addEventListener('click', () => {
                ingredientList.removeChild(listItem);
                currentIngredients = currentIngredients.filter(ing => ing !== ingredient);
                ingredientHiddenInput.value = JSON.stringify(currentIngredients); // Mettre à jour le champ caché
            });
            listItem.appendChild(removeBtn);

            ingredientList.appendChild(listItem);
        });

    </script>

</body>
</html>
