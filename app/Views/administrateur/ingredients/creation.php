<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'Ingrédient</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Formulaire de création -->
        <div class="form-container">
            <h1>Créer un ingrédient</h1>
            <?php echo form_open('/admin/ingredients/creer', ['enctype' => 'multipart/form-data']); ?>

                <div>
                    <?php echo form_label('Nom *', 'nom'); ?>
                    <?php echo form_input('nom', set_value('nom'), 'required'); ?>
                    <?= validation_show_error('nom') ?>
                </div>
          

                <div>
                    <?php echo form_label('Provenance', 'provenance'); ?>
                    <?php echo form_input('provenance', set_value('provenance')); ?>
                    <?= validation_show_error('provenance') ?>
                </div>

                <div class="actions">
                    <button type="submit" class="submit-btn">Ajouter l'ingrédient</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/ingredients';">Annuler</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</body>
</html>
