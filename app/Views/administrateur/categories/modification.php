<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de Catégorie</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
</head>
<body>
    <div class="container">
       
        <!-- Formulaire de modification -->
        <div class="form-container">
            <h1>Modifier une Catégorie</h1>
            <?php echo form_open('/admin/categories/modifier/' . $categorie['id_categorie'], ['enctype' => 'multipart/form-data']); ?>

                <div>
                    <?php echo form_label('Nom', 'nom'); ?>
                    <?php echo form_input('nom', set_value('nom', $categorie['nom']), 'required'); ?>
                    <?= validation_show_error('nom') ?>
                </div>
                
                <?php echo form_label('Description', 'description'); ?>
                <?php echo form_textarea('description', set_value('description', $categorie['description']), 'required'); ?>
                <?= validation_show_error('description') ?>

                <div class="actions">
                    <button type="submit" class="submit-btn">Mettre à jour la catégorie</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/categories';">Annuler</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</body>
</html>
