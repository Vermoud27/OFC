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
        <!-- Formulaire de création -->
        <div class="form-container">
            <h1>Créer un bundle</h1>
            <?php echo form_open('/admin/bundles/creer', ['enctype' => 'multipart/form-data']); ?>

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
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/bundles';">Annuler</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</body>
</html>
