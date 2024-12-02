<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Catégorie</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
</head>
<body>
    <div class="container">
        <!-- Formulaire de création -->
        <div class="form-container">
            <h1>Créer une Catégorie</h1>
            <?php echo form_open('/admin/categories/creer', ['enctype' => 'multipart/form-data']); ?>

                <div>
                    <?php echo form_label('Nom', 'nom'); ?>
                    <?php echo form_input('nom', set_value('nom'), 'required'); ?>
                    <?= validation_show_error('nom') ?>
                </div>
          

                <div class="grid-full">
                    <?php echo form_label('Description', 'description'); ?>
                    <?php echo form_textarea('description', set_value('description'), 'required'); ?>
                    <?= validation_show_error('description') ?>
                </div>
        

                <div class="actions">
                    <button type="submit" class="submit-btn">Ajouter le produit</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/categories';">Annuler</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</body>
</html>
