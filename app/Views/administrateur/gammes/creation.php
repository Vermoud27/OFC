<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Gamme</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
</head>
<body>
    <div class="container">
        <!-- Formulaire de création -->
        <div class="form-container">
            <h1>Créer une Gamme</h1>
            <?php echo form_open('/admin/gammes/creer', ['enctype' => 'multipart/form-data']); ?>

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

                <div class="grid-2-columns">
                    <div>
                        <?php echo form_label('Prix HT', 'prixht'); ?>
                        <?php echo form_input('prixht', set_value('prixht'), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '0.01',
                            'required' => 'required'
                        ]); ?>
                        <?= validation_show_error('prixht') ?>
                    </div>
                    <div>
                        <?php echo form_label('Prix TTC', 'prixttc'); ?>
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
                    <button type="submit" class="submit-btn">Ajouter l'ingrédient</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/gammes';">Annuler</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

</body>
</html>
