<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de Bundle</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
</head>
<body>
    <div class="container">

        <!-- Formulaire de modification -->
        <div class="form-container">
        <h1>Modifier un code promo</h1>

            <?php echo form_open('/admin/codes-promos/modifier/' . $codepromo['id_codepromo'], ['enctype' => 'multipart/form-data']); ?>

                <div>
                    <?php echo form_label('Code Promo', 'code'); ?>
                    <?php echo form_input('code', set_value('code', $codepromo['code']), 'required'); ?>
                    <?= validation_show_error('code') ?>
                </div>

                <div class="grid-2-columns">
                    <div>
                        <?php echo form_label('Réduction en € (optionnelle)', 'valeur'); ?>
                        <?php echo form_input('valeur', set_value('valeur', $codepromo['valeur'] ?? ''), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '1',
                        ]); ?>
                        <?= validation_show_error('valeur') ?>
                    </div>
                    <div>
                        <?php echo form_label('Réduction en % (optionnelle)', 'pourcentage'); ?>
                        <?php echo form_input('pourcentage', set_value('pourcentage', $codepromo['pourcentage'] ?? ''), [
                            'type' => 'number',
                            'min' => '0',
                            'step' => '1',
                        ]); ?>
                        <?= validation_show_error('pourcentage') ?>
                    </div>
                </div>
            
                <div class="grid-2-columns">
                    <div>
                        <?php echo form_label('Date de début', 'date_debut'); ?>
                        <?php echo form_input([
                            'type' => 'date',
                            'name' => 'date_debut',
                            'value' => set_value('date_debut', $codepromo['date_debut']),
                            'required' => true,
                            'id' => 'date_debut'
                        ]); ?>
                        <?= validation_show_error('date_debut') ?>
                    </div>

                    <div>
                        <?php echo form_label('Date de fin', 'date_fin'); ?>
                        <?php echo form_input([
                            'type' => 'date',
                            'name' => 'date_fin',
                            'value' => set_value('date_fin', $codepromo['date_fin']),
                            'required' => true,
                            'id' => 'date_fin'
                        ]); ?>
                        <?= validation_show_error('date_fin') ?>
                    </div>
                </div>

                <div>
                    <?php echo form_label('Nombre maximal d\'utilisations', 'utilisation_max'); ?>
                    <?php echo form_input('utilisation_max', set_value('utilisation_max', $codepromo['utilisation_max'] ?? ''), [
                        'type' => 'number',
                        'min' => '0',
                        'step' => '1',
                    ]); ?>
                    <?= validation_show_error('utilisation_max') ?>
                </div>

                <div>
                    <?php echo form_label('Conditions d\'utilisation (optionnelles)', 'conditions_utilisation'); ?>
                    <?php echo form_textarea('conditions_utilisation', set_value('conditions_utilisation', $codepromo['conditions_utilisation']  ?? '')); ?>
                    <?= validation_show_error('conditions_utilisation') ?>
                </div>

                <div>
                    <?= form_label('Actif :', 'actif') ?>
                    <?= form_dropdown(
                        'actif', 
                        [ 
                            'TRUE' => 'Oui', 
                            'FALSE' => 'Non' 
                        ], 
                        $codepromo['actif'],
                        ['id' => 'actif', 'required' => 'required'] 
                    ) ?>
                </div>

                <div class="actions">
                    <button type="submit" class="submit-btn">Mettre à jour le code promo</button>
                    <button type="button" class="cancel-btn" onclick="window.location.href='/admin/codes-promos';">Annuler</button>
                </div>
            <?php echo form_close(); ?>

        </div>
    </div>

</body>
</html>
