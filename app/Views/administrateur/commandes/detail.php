<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de la commande</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="form-container">

			<div class="grid-2-columns">
				<div>
					<h2>Détails de la commande #<?= $commande['id_commande'] ?></h2>

					<div class="commande-info">
						<p><span>ID : </span><?= $commande['id_commande'] ?></p>
						<p><span>Date de création : </span><?= date('d/m/Y H:i', strtotime($commande['date_creation'])) ?></p>
						<p><span>Status : </span><?= $commande['statut'] ?></p>
						<p><span>Adresse de livraison : </span><?= $commande['informations'] ?></p>
						<p><span>Prix total : </span><?= $commande['prixpromo'] ?: $commande['prixttc'] ?> €</p>
					</div>

					<!-- Modification du statut -->
					<form method="post" action="/admin/commandes/modifier/<?= $commande['id_commande'] ?>">
				
						<label for="statut-<?= $commande['id_commande'] ?>">Modifier le statut :</label>
						<div class="form-group">
							<select id="statut-<?= $commande['id_commande'] ?>" name="statut">
								<option value="en attente" <?= $commande['statut'] === 'en attente' ? 'selected' : '' ?>>En attente</option>
								<option value="expédié" <?= $commande['statut'] === 'expédié' ? 'selected' : '' ?>>Expédié</option>
								<option value="fini" <?= $commande['statut'] === 'fini' ? 'selected' : '' ?>>Fini</option>
								<option value="annulé" <?= $commande['statut'] === 'annulé' ? 'selected' : '' ?>>Annulé</option>
							</select>
						
						<button type="submit" class="modify-status-btn">Modifier</button>
						</div>
					</form>
				</div>

				<div>
					<h2>Produits de la commande</h2>
					<table>
						<thead>
							<tr>
								<th>Nom</th>
								<th>Quantité</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($produits as $produit): ?>
								<tr>
									<td><?= htmlspecialchars($produit['nom']) ?></td>
									<td><?= $produit['quantite'] ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<div class="retour">
						<a href="/admin/commandes" class="button">Retour à mes commandes</a>
					</div>
				</div>
			</div>
        </div>
    </div>

</body>

</html>