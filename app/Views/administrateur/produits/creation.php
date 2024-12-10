<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Produit</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Galerie d'images -->
        <div class="gallery">
            <div class="main-image" id="main-image">
                <img src="https://via.placeholder.com/300x300?text=Aperçu" alt="Image principale">
            </div>
            <div class="thumbnails" id="thumbnails">
                <!-- Miniatures dynamiques -->
            </div>
            <button type="button" id="add-image-btn">Ajouter une image</button>
            <input type="file" id="image-input" accept="image/*" style="display: none;">
        </div>

        <!-- Formulaire de création -->
        <div class="form-container">
            <h1>Créer un Produit</h1>
            <?php echo form_open('/admin/produits/creer', ['enctype' => 'multipart/form-data']); ?>

                <div class="grid-2-columns">
                    <div>
                        <?php echo form_label('Nom *', 'nom'); ?>
                        <?php echo form_input('nom', set_value('nom'), 'required'); ?>
                        <?= validation_show_error('nom') ?>
                    </div>
                    <div>
                        <label for="categorie">Catégorie</label>
                        <select id="categorie" name="categorie">
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories as $categorie) : ?>
                                <option value="<?= $categorie['id_categorie']; ?>">
                                    <?= $categorie['nom']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
          

                <div class="grid-full">
                    <?php echo form_label('Description *', 'description'); ?>
                    <?php echo form_textarea('description', set_value('description'), 'required'); ?>
                    <?= validation_show_error('description') ?>
                </div>
        

                <div class="grid-2-columns">
                    <div>
                        <label for="ingredient">Ajouter un ingrédient</label>
                        <input list="ingredient-option" type="text" id="ingredient" name="ingredient" placeholder="Rechercher un ingrédient..." autocomplete="off">
                        <datalist id="ingredient-option">
                            <?php foreach ($ingredients as $ingredient): ?>
                                <option value="<?= esc($ingredient['nom']); ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <button type="button" id="add-ingredient-btn">Ajouter</button>
                    </div>

                    <div>
                        <label for="ingredient-list">Liste des ingrédients</label>
                        <ul class="ingredient-list" id="ingredient-list">
                            <!-- Les ingrédients ajoutés seront affichés ici -->
                        </ul>
                    </div>
                </div>
          

                <div class="grid-3-columns">
                    <div>
                        <?php echo form_label('Contenu *', 'contenu'); ?>
                        <?php echo form_input('contenu', set_value('contenu'), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '1',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('contenu') ?>
                    </div>
                    <div>
                        <?php echo form_label('Unité de mesure *', 'unite_mesure'); ?>
                        <?php 
                        $options = [
                            'g' => 'g',
                            'mL' => 'mL',
                            'u' => 'u',
                        ];
                        echo form_dropdown('unite_mesure', $options, set_value('unite_mesure'), 'required'); ?>
                        <?= validation_show_error('unite_mesure') ?>
                    </div>
                   
                    <div>
                        <?php echo form_label('Quantité en stock *', 'qte_stock'); ?>
                        <?php echo form_input('qte_stock', set_value('qte_stock'), [
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
                        <?php echo form_label('Prix HT (€) *', 'prixht'); ?>
                        <?php echo form_input('prixht', set_value('prixht'), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '0.01',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('prixht') ?>
                    </div>
                    <div>
                        <?php echo form_label('Prix TTC (€) *', 'prixttc'); ?>
                        <?php echo form_input('prixttc', set_value('prixttc'), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '0.01',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('prixttc') ?>
                    </div>
                </div>
             
                <div class="actions">
                    <button type="submit" class="submit-btn">Ajouter le produit</button>
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
const formContainer = document.querySelector('form'); // Récupère le formulaire principal

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

    </script>

<script>
        const addIngredientBtn = document.getElementById('add-ingredient-btn');
        const ingredientInput = document.getElementById('ingredient');
        const ingredientList = document.getElementById('ingredient-list');

        addIngredientBtn.addEventListener('click', () => {
            const ingredientName = ingredientInput.value.trim();
        if (ingredientName) {
            // Ajouter un nouvel ingrédient à la liste
            const listItem = document.createElement('li');
            listItem.textContent = ingredientName;

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'ingredients[]'; // Tableau pour les ingrédients
            hiddenInput.value = ingredientName;

            listItem.appendChild(hiddenInput);

            // Ajouter un bouton de suppression
            const removeBtn = document.createElement('button');
            removeBtn.textContent = '×';
            removeBtn.classList.add('remove-btn');
            removeBtn.addEventListener('click', () => {
                ingredientList.removeChild(listItem);
            });
            listItem.appendChild(removeBtn);

            ingredientList.appendChild(listItem);
            ingredientInput.value = '';
        }
    });
    </script>

</body>
</html>
