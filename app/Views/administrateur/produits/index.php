<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .form-container {
            flex: 1;
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            display: none; /* Masqué par défaut */
        }

        .list-container {
            flex: 2;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-container form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .form-container form input,
        .form-container form select,
        .form-container form textarea {
            width: 100%;
            margin-top: 5px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container form button {
            margin-top: 15px;
            padding: 10px;
            width: 100%;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container form button:hover {
            background: #2980b9;
        }

        .form-container .close-button {
            background: #e74c3c;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background: #3498db;
            color: white;
        }

        .toggle-button {
            margin-bottom: 15px;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .toggle-button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Liste des produits -->
        <div class="list-container">
            <h2>Liste des Produits</h2>
            <button class="toggle-button" onclick="toggleForm('create-form-container')">Ajouter un produit</button>            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix HT</th>
                        <th>Prix TTC</th>
						<th>Quantité en stock</th>
						<th>Unitée de mesure</th>
						<th>Promotion</th>
						<th>Catégorie</th>
						<th>Gamme</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td><?= $produit['id_produit'] ?></td>
                            <td><?= $produit['nom'] ?></td>
                            <td><?= $produit['description'] ?></td>
                            <td><?= $produit['prixht'] ?></td>
                            <td><?= $produit['prixttc'] ?></td>
							<td><?= $produit['qte_stock'] ?></td>
							<td><?= $produit['unite_mesure'] ?></td>
							<td><?= $produit['promotion'] ?></td>
							<td><?= $produit['id_categorie'] ?></td>
							<td><?= $produit['id_gamme'] ?></td>
                            <td>
								<button class="toggle-button" onclick="toggleForm('edit-form-container'); editProduct(<?= htmlspecialchars(json_encode($produit), ENT_QUOTES, 'UTF-8') ?>)">Modifier</button>
								|
                                <a href="/admin/produits/supprimer/<?= $produit['id_produit'] ?>" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Formulaire de création -->
        <div id="create-form-container" class="form-container">
            <h2>Créer un Produit</h2>
			<button class="close-button" onclick="closeForm('create-form-container')">Fermer</button>
			<?php echo form_open('/admin/produits/creer'); ?>
			
			<?php echo form_label('Nom', 'nom'); ?>
			<?php echo form_input('nom', set_value('nom'), 'required'); ?>
			<?= validation_show_error('nom') ?>

			<?php echo form_label('Description', 'description'); ?>
			<?php echo form_textarea('description', set_value('description'), 'required'); ?>
			<?= validation_show_error('description') ?>

			<?php echo form_label('Prix HT', 'prixht'); ?>
			<?php echo form_input('prixht', set_value('prixht'), [
				'type' => 'number',
				'min' => '0',
				'step' => '0.01',
				'required' => 'required'
			]); ?>
			<?= validation_show_error('prixht') ?>

			<?php echo form_label('Prix TTC', 'prixttc'); ?>
			<?php echo form_input('prixttc', set_value('prixttc'), [
				'type' => 'number',
				'min' => '0',
				'step' => '0.01',
				'required' => 'required'
			]); ?>
			<?= validation_show_error('prixttc') ?>

			<?php echo form_label('Quantité en stock', 'qte_stock'); ?>
			<?php echo form_input('qte_stock', set_value('qte_stock'), [
				'type' => 'number',
				'min' => '0',
				'step' => '1',
				'required' => 'required'
			]); ?>
			<?= validation_show_error('qte_stock') ?>

			<?php echo form_label('Unité de mesure', 'unite_mesure'); ?>
			<?php 
			$options = [
				'g' => 'g',
				'mL' => 'mL',
			];
			echo form_dropdown('unite_mesure', $options, set_value('unite_mesure'), 'required'); 
			?>
			<?= validation_show_error('unite_mesure') ?>

			<?php echo form_submit('submit', 'Créer'); ?>

			<?php echo form_close(); ?>
        </div>

		<!-- Formulaire de modification -->
		<div id="edit-form-container" class="form-container">
			<h2>Modifier un Produit</h2>
			<button class="close-button" onclick="closeForm('edit-form-container')">Fermer</button>
			<?php echo form_open('/admin/produits/modifier'); ?>
			<input type="hidden" id="edit-id_produit" name="id_produit" />

			<?php echo form_label('Nom', 'nom'); ?>
			<?php echo form_input('nom', set_value('nom'), ['id' => 'edit-nom', 'required' => 'required']); ?>

			<?php echo form_label('Description', 'description'); ?>
			<?php echo form_textarea('description', set_value('description'), ['id' => 'edit-description', 'required' => 'required']); ?>

			<?php echo form_label('Prix HT', 'prixht'); ?>
			<?php echo form_input('prixht', set_value('prixht'), ['id' => 'edit-prixht', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required']); ?>

			<?php echo form_label('Prix TTC', 'prixttc'); ?>
			<?php echo form_input('prixttc', set_value('prixttc'), ['id' => 'edit-prixttc', 'type' => 'number', 'min' => '0', 'step' => '0.01', 'required' => 'required']); ?>

			<?php echo form_label('Quantité en stock', 'qte_stock'); ?>
			<?php echo form_input('qte_stock', set_value('qte_stock'), ['id' => 'edit-qte_stock', 'type' => 'number', 'min' => '0', 'step' => '1', 'required' => 'required']); ?>

			<?php echo form_label('Unité de mesure', 'unite_mesure'); ?>
			<?php 
			$options = [
				'g' => 'g',
				'mL' => 'mL',
			];
			echo form_dropdown('unite_mesure', $options, set_value('unite_mesure'), ['id' => 'edit-unite_mesure', 'required' => 'required']); 
			?>
			
			<?php echo form_submit('submit', 'Modifier'); ?>
			<?php echo form_close(); ?>
		</div>

    </div>

	<script>
		function toggleForm(formId) {
			document.querySelectorAll('.form-container').forEach(form => {
				form.style.display = 'none'; // Cache tous les formulaires
			});
			document.getElementById(formId).style.display = 'block'; // Affiche le formulaire sélectionné
		}

		function closeForm(formId) {
			document.getElementById(formId).style.display = 'none'; // Cache le formulaire sélectionné
		}

		function editProduct(produit) {
			// Remplir les champs du formulaire de modification avec les valeurs du produit
			document.getElementById('edit-id_produit').value = produit.id_produit;
			document.getElementById('edit-nom').value = produit.nom;
			document.getElementById('edit-description').value = produit.description;
			document.getElementById('edit-prixht').value = produit.prixht;
			document.getElementById('edit-prixttc').value = produit.prixttc;
			document.getElementById('edit-qte_stock').value = produit.qte_stock;
			document.getElementById('edit-unite_mesure').value = produit.unite_mesure;

			// Si vous avez des options supplémentaires, comme la catégorie ou la gamme
			document.getElementById('edit-id_categorie').value = produit.id_categorie;
			document.getElementById('edit-id_gamme').value = produit.id_gamme;

			// Afficher le formulaire de modification
			toggleForm('edit-form-container');
		}

	</script>
</body>
</html>
