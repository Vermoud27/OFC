<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/panier.css">
    <link rel="stylesheet" href="/assets/css/notif.css">
    <script src="/assets/js/panier.js"></script>
</head>

<body>
    <!-- Affichage des flashdata sous forme de notification -->
    <div id="notifications" class="notifications">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="notification is-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="notification is-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
    </div>
    <h1>Mon Panier</h1>
    <div class="container">
        <!-- Articles -->
        <div class="cart-items">
            <?php if (!empty($produits)): ?>
                <?php foreach ($produits as $produit): ?>
                    <div class="cart-item" data-id="<?= htmlspecialchars($produit['id_produit']) ?>">
                    <img src="<?= htmlspecialchars($produit['images'][0]['chemin'] ?? '/assets/img/default.png') ?>"
                        alt="<?= htmlspecialchars($produit['nom']) ?>">
                    <div class="cart-item-details">
                        <h2><?= htmlspecialchars($produit['nom']) ?></h2>
                        <p><?= htmlspecialchars($produit['contenu']) . ' ' . htmlspecialchars($produit['unite_mesure']) ?></p>
                        <p class="stock-status">En stock</p>
                        <div class="quantity">
                            <button onclick="updateQuantity(<?= $produit['id_produit'] ?>, -1)">-</button>
                            <p><?= htmlspecialchars($produit['quantite']) ?></p>
                            <button onclick="updateQuantity(<?= $produit['id_produit'] ?>, 1)">+</button>
                        </div>
                    </div>
                    <div class="cart-item-right">  
                        <p class="price"><?= number_format($produit['prixttc'], 2) ?> €</p>
                        <button class="sup" onclick="retirerProduit(<?= $produit['id_produit'] ?>)"></button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class ="empty">Votre panier est vide.</p>
            <?php endif; ?>
        </div>

        <script>
            function updateQuantity(productId, delta) {
    const requestBody = { id_produit: productId, delta: delta };

    // Faire un appel AJAX au serveur pour mettre à jour la quantité
    fetch('/panier/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(requestBody),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Met à jour l'affichage de la quantité
            const quantityElement = document.querySelector(`.cart-item[data-id="${productId}"] .quantity p`);
            const priceElement = document.querySelector(`.cart-item[data-id="${productId}"] .price`);
            const totalElement = document.querySelector('.price-ttc');

            if (quantityElement && priceElement) {
                quantityElement.textContent = data.newQuantity;
                priceElement.textContent = `${data.newPrice.toFixed(2)} €`;
            }

            // Met à jour le total
            if (totalElement) {
                totalElement.textContent = `${data.totalTTC.toFixed(2)} €`;
            }
        } else {
            alert(data.message || 'Erreur lors de la mise à jour de la quantité.');
        }
    })
    .catch(error => console.error('Erreur lors de la requête:', error));
}
</script>
            


        <!-- Bouton Vider le panier -->
        <button class="clear-cart" onclick="location.href='/panier/vider'">Vider le panier</button>

        <!-- Récapitulatif -->
        <div class="cart-summary">
            <?php
            $totalTTC = 0;
            foreach ($produits as $produit) {
                $totalTTC += $produit['prixttc'] * $produit['quantite'];
            }
            ?>
            <h2>Récapitulatif :</h2>
            <p>Prix TTC : <span class="price-ttc"><?= number_format($totalTTC, 2) ?> €</span></p>
            <div class="promo-code">
                <input type="text" placeholder="Saisir un code promo">
                <button>Appliquer</button>
            </div>
            <button class="checkout">Commander</button>
        </div>

    </div>
</body>
<!-- Script pour masquer les notifications après 4 secondes -->
<script src="/assets/js/notif.js"></script>
</html>

<?php
require 'footer.php';
?>