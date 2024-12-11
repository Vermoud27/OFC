<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de bundle</title>
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
            <h1>Créer un bundle</h1>
            <?php echo form_open('/admin/bundles/creer', ['enctype' => 'multipart/form-data']); ?>

            <div class="grid-full">
                <?php echo form_label('Nom *', 'nom'); ?>
                <?php echo form_input('nom', set_value('nom'), 'required'); ?>
                <?= validation_show_error('nom') ?>
            </div>

            <div class="grid-full">
                <?php echo form_label('Description *', 'description'); ?>
                <?php echo form_textarea('description', set_value('description'), 'required'); ?>
                <?= validation_show_error('description') ?>
            </div>

            <div>
                <?php echo form_label('Prix (€) *', 'prix'); ?>
                <?php echo form_input('prix', set_value('prix'), [
                    'type' => 'number',
                    'min' => '0',
                    'step' => '0.01',
                    'required' => 'required'
                ]); ?>
                <?= validation_show_error('prix') ?>
            </div>

            <div class="actions">
                <button type="submit" class="submit-btn">Ajouter le bundle</button>
                <button type="button" class="cancel-btn"
                    onclick="window.location.href='/admin/bundles';">Annuler</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</body>
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

</html>