<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif de la commande</title>
    <link rel="stylesheet" href="/assets/css/recapitulatif.css">
	<link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
	<script src="https://www.paypalobjects.com/api/checkout.js"></script>
</head>
<body>
    <h1>Récapitulatif de votre commande</h1>

	<form method="post" action="/commande/enregistrer" id="finalForm">

    <div class="container">
        <!-- Tableau des produits -->
        <div class="table-container">
            <h2>Produits</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit): ?>
                        <tr>
                            <td><?= htmlspecialchars($produit['nom']) ?></td>
                            <td><?= htmlspecialchars($produit['quantite']) ?></td>
                            <td><?= number_format($produit['prixttc'], 2) ?> €</td>
                            <td><?= number_format($produit['total'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>

                    <?php
                      $totalTTC = 0;
                      foreach ($produits as $produit) {
                          $totalTTC += $produit['prixttc'] * $produit['quantite'];
                      }
                    ?>

                    <?php if (isset($symbole)): ?>
                      <tr>
                        <td colspan="3"><strong>Total TTC :</strong></td>
                        <td><strong><?= number_format($totalTTC, 2) ?> €</strong></td>
                      </tr>
                      <tr>
                          <td><?= htmlspecialchars($code_promo['code']) ?></td>
                          <td> </td>
                          <td>-<?= number_format($code_promo['valeur'] + $code_promo['pourcentage'], 2) . $symbole?></td>
                          <td><?= number_format($totalPromo, 2) ?> €</td>
                      </tr>

                      <input type="hidden" id="prix_total" name="prix_total" value="<?= htmlspecialchars(number_format($totalPromo, 2, '.', '')) ?>">
                      <input type="hidden" id="idpromo" name="idpromo" value="<?= htmlspecialchars($code_promo['id_codepromo']) ?>">

                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total TTC :</strong></td>
                        <td><strong><?= number_format($totalPromo, 2) ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Adresse de livraison -->
        <div class="info-container">
            <h2>Adresse de livraison</h2>
              <div>
                  <label for="nom">Nom :</label>
                  <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
              </div>
              <div>
                  <label for="adresse">Adresse :</label>
                  <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($utilisateur['adresse']) ?>" required>
              </div>
              <div>
                  <label for="code_postal">Code postal :</label>
                  <input type="text" id="code_postal" name="code_postal" value="<?= htmlspecialchars($utilisateur['code_postal']) ?>" required>
              </div>
              <div>
                  <label for="ville">Ville :</label>
                  <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($utilisateur['ville']) ?>" required>
              </div>
        </div>
    </div>

	<div class="button-container">
        <button type="submit" form="finalForm">Confirmer la commande</button>
    </div>

	</form>

<div id="bouton-paypal"></div>

	  <script>
	    	paypal.Button.render({
	      env: 'sandbox', // Ou 'production',
	      commit: true, // Affiche le bouton  "Payer maintenant"
	      style: {
	        color: 'gold', // ou 'blue', 'silver', 'black'
	        size: 'responsive' // ou 'small', 'medium', 'large'
	        // Autres options de style disponibles ici : https://developer.paypal.com/docs/integration/direct/express-checkout/integration-jsv4/customize-button/
	      },
	      payment: function() {
	        // On crée une variable contenant le chemin vers notre script PHP côté serveur qui se chargera de créer le paiement
	        var CREATE_URL = '/paypal_create_payment.php';
	        // On exécute notre requête pour créer le paiement
	        return paypal.request.post(CREATE_URL)
	          .then(function(data) { // Notre script PHP renvoie un certain nombre d'informations en JSON (vous savez, grâce à notre echo json_encode(...) dans notre script PHP !) qui seront récupérées ici dans la variable "data"
	            if (data.success) { // Si success est vrai (<=> 1), on peut renvoyer l'id du paiement généré par PayPal et stocké dans notre data.paypal_reponse (notre script en aura besoin pour poursuivre le processus de paiement)
	               return data.paypal_response.id;   
	            } else { // Sinon, il y a eu une erreur quelque part. On affiche donc à l'utilisateur notre message d'erreur généré côté serveur et passé dans le paramètre data.msg, puis on retourne false, ce qui aura pour conséquence de stopper net le processus de paiement.
	               alert(data.msg);
	               return false;   
	            }
	         });
	      },
	      onAuthorize: function(data, actions) {
	        // On indique le chemin vers notre script PHP qui se chargera d'exécuter le paiement (appelé après approbation de l'utilisateur côté client).
	        var EXECUTE_URL = '/paypal_execute_payment.php';
	        // On met en place les données à envoyer à notre script côté serveur
	        // Ici, c'est PayPal qui se charge de remplir le paramètre data avec les informations importantes :
	        // - paymentID est l'id du paiement que nous avions précédemment demandé à PayPal de générer (côté serveur) et que nous avions ensuite retourné dans notre fonction "payment"
	        // - payerID est l'id PayPal de notre client
	        // Ce couple de données nous permettra, une fois envoyé côté serveur, d'exécuter effectivement le paiement (et donc de recevoir le montant du paiement sur notre compte PayPal).
	        // Attention : ces données étant fournies par PayPal, leur nom ne peut pas être modifié ("paymentID" et "payerID").
	        var data = {
	          paymentID: data.paymentID,
	          payerID: data.payerID
	        };
	        // On envoie la requête à notre script côté serveur
	        return paypal.request.post(EXECUTE_URL, data)
	          .then(function (data) { // Notre script renverra une réponse (du JSON), à nouveau stockée dans le paramètre "data"
	          if (data.success) { // Si le paiement a bien été validé, on peut par exemple rediriger l'utilisateur vers une nouvelle page, ou encore lui afficher un message indiquant que son paiement a bien été pris en compte, etc.
	            // Exemple : window.location.replace("Une url quelconque");
	            alert("Paiement approuvé ! Merci !");
	          } else {
	            // Sinon, si "success" n'est pas vrai, cela signifie que l'exécution du paiement a échoué. On peut donc afficher notre message d'erreur créé côté serveur et stocké dans "data.msg".
	            alert(data.msg);
	          }
	        });
	      },
	      onCancel: function(data, actions) {
	        alert("Paiement annulé : vous avez fermé la fenêtre de paiement.");
	      },
	      onError: function(err) {
	        alert("Paiement annulé : une erreur est survenue. Merci de bien vouloir réessayer ultérieurement.");
	      }
	    }, '#bouton-paypal');
	  </script>


<?php
require 'footer.php';
?>