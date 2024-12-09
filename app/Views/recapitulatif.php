<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Récapitulatif de la commande</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/recapitulatif.css">
  <link rel="stylesheet" href="/assets/css/header.css">
  <link rel="stylesheet" href="/assets/css/footer.css">
  <script
    src="https://www.paypal.com/sdk/js?client-id=AS8iRQzyxk6_XP_ucKTr6SwHHRg9KNiPd8M3siho7JE8RVJI3545R2ZxUjT44gYKmFE0yezU3jL9x05x&currency=EUR"></script>
  <script src="/assets/js/paypal.js"></script>
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
                <td>-<?= number_format($code_promo['valeur'] + $code_promo['pourcentage'], 2) . $symbole ?></td>
                <td><?= number_format($totalPromo, 2) ?> €</td>
              </tr>

              <input type="hidden" id="prix_total" name="prix_total"
                value="<?= htmlspecialchars(number_format($totalPromo, 2, '.', '')) ?>">
              <input type="hidden" id="idpromo" name="idpromo"
                value="<?= htmlspecialchars($code_promo['id_codepromo']) ?>">

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
          <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($utilisateur['adresse']) ?>"
            required>
        </div>
        <div>
          <label for="code_postal">Code postal :</label>
          <input type="text" id="code_postal" name="code_postal"
            value="<?= htmlspecialchars($utilisateur['code_postal']) ?>" required>
        </div>
        <div>
          <label for="ville">Ville :</label>
          <input type="text" id="ville" name="ville" value="<?= htmlspecialchars($utilisateur['ville']) ?>" required>
        </div>
      </div>
    </div>

    <!-- <div class="button-container">
      <button type="submit" form="finalForm">Confirmer la commande</button>
    </div> -->

  </form>

  <div id="paypal-payment-button"></div>

  <script>
    // Script Paypal
    const totalPromo = <?= isset($totalPromo) ? json_encode($totalPromo) : '0.00' ?>;

    if (totalPromo > 0) {
      // Paiement avec PayPal si le montant est supérieur à zéro
      paypal.Buttons({
        style: {
          color: 'blue'
        },
        createOrder: function (data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: totalPromo
              }
            }]
          });
        },
        onApprove: function (data, actions) {
          return actions.order.capture().then(function (details) {
            console.log(details);
            // Rediriger après paiement réussi
            window.location.replace("/commande/enregistrer");
          });
        }
      }).render('#paypal-payment-button');
    } else {
      // Si le montant est nul, afficher un bouton manuel pour valider la commande
      document.getElementById('paypal-payment-button').innerHTML = `
            <p style="color: green;">Votre commande est gratuite.</p>
            <button type="submit" form="finalForm" style="padding: 10px; background-color: #28a745; color: #fff; border: none; cursor: pointer;">
                Valider la commande
            </button>
        `;

      // Ajouter un événement pour rediriger lorsque l'utilisateur clique sur le bouton
      document.getElementById('validate-free-order').addEventListener('click', () => {
        window.location.replace("/commande/enregistrer");
      });
    }
  </script>

  <?php
  require 'footer.php';
  ?>