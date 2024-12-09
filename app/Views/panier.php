<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
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
                    <div class="cart-item" data-id="<?= htmlspecialchars($produit['id_produit']) ?>"
                        data-stock="<?= htmlspecialchars($produit['qte_stock']) ?>">
                        <img src="<?= htmlspecialchars($produit['images'][0]['chemin'] ?? '/assets/img/produits/placeholder.png') ?>"
                            alt="<?= htmlspecialchars($produit['nom']) ?>">
                        <div class="cart-item-details">
                            <h2><?= htmlspecialchars($produit['nom']) ?></h2>
                            <p><?= htmlspecialchars($produit['contenu']) . ' ' . htmlspecialchars($produit['unite_mesure']) ?>
                            </p>
                            <p class="stock-status" data-stock="<?= htmlspecialchars($produit['qte_stock']) ?>">
                                En stock (<?= htmlspecialchars($produit['qte_stock']) ?>)
                            </p>

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
            <?php endif; ?>




           <!-- GAMMES -->
        <?php if (!empty($gammes)): ?>
            <?php foreach ($gammes as $gamme): ?>
<!-- Pour chaque gamme -->
<div class="cart-gamme" data-id="<?= htmlspecialchars($gamme['id_gamme']) ?>">
    <img src="<?= htmlspecialchars($gamme['images'][0]['chemin'] ?? '/assets/img/produits/placeholder.png') ?>" alt="<?= htmlspecialchars($gamme['nom']) ?>">
    <div class="cart-gamme-details">
        <h2><?= htmlspecialchars($gamme['nom']) ?></h2>
        <p><strong>Composition de la gamme :</strong></p>
        
        <!-- Liste des produits dans la gamme -->
        <ul>
            <div class="gamme-produits">
                <?php foreach ($produitsParGamme[$gamme['id_gamme']] as $produitGamme): ?>
                    <li>
                        <?= htmlspecialchars($produitGamme['nom']) ?> / 
                        Stock : <?= htmlspecialchars($produitGamme['qte_stock']) ?> / 
                        Quantité achetée : <?= htmlspecialchars($produitGamme['quantite'] ?? '0') ?>
                    </li>
                <?php endforeach; ?>
            </div>
        </ul>

        <!-- Gestion de la quantité -->
        <div class="quantity">
            <button onclick="updateQuantity(<?= htmlspecialchars($gamme['id_gamme']) ?>, -1, true)">-</button>
            <p><?= htmlspecialchars($panier['gammes'][$gamme['id_gamme']] ?? 1) ?></p>
            <button onclick="updateQuantity(<?= htmlspecialchars($gamme['id_gamme']) ?>, 1, true)">+</button>
        </div>
    </div>
    <div class="cart-item-right">
        <p class="price"><?= number_format($gamme['prixttc'] ?? 0, 2) ?> €</p>
        <button class="sup" onclick="retirerProduit(<?= htmlspecialchars($gamme['id_gamme']) ?>)">Supprimer</button>
    </div>
</div>

            <!-- Pour chaque gamme -->
<div class="cart-gamme" data-id="<?= htmlspecialchars($gamme['id_gamme']) ?>">
    <img src="<?= htmlspecialchars($gamme['images'][0]['chemin'] ?? '/assets/img/produits/placeholder.png') ?>" alt="<?= htmlspecialchars($gamme['nom']) ?>">
    <div class="cart-gamme-details">
        <h2><?= htmlspecialchars($gamme['nom']) ?></h2>
        <p><strong>Composition de la gamme :</strong></p>
        
        <!-- Liste des produits dans la gamme -->
        <ul>
            <div class="gamme-produits">
                <?php foreach ($produitsParGamme[$gamme['id_gamme']] as $produitGamme): ?>
                    <li>
                        <?= htmlspecialchars($produitGamme['nom']) ?> / 
                        Stock : <?= htmlspecialchars($produitGamme['qte_stock']) ?> / 
                        Quantité achetée : <?= htmlspecialchars($produitGamme['quantite'] ?? '0') ?>
                    </li>
                <?php endforeach; ?>
            </div>
        </ul>

        <!-- Gestion de la quantité -->
        <div class="quantity">
            <button onclick="updateQuantity(<?= htmlspecialchars($gamme['id_gamme']) ?>, -1, true)">-</button>
            <p><?= htmlspecialchars($panier['gammes'][$gamme['id_gamme']] ?? 1) ?></p>
            <button onclick="updateQuantity(<?= htmlspecialchars($gamme['id_gamme']) ?>, 1, true)">+</button>
        </div>
    </div>
    <div class="cart-item-right">
        <p class="price"><?= number_format($gamme['prixttc'] ?? 0, 2) ?> €</p>
        <button class="sup" onclick="retirerProduit(<?= htmlspecialchars($gamme['id_gamme']) ?>)">Supprimer</button>
    </div>
</div>



                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (empty($produits) && empty($gammes)): ?>
                <p class="empty">Votre panier est vide.</p>
            <?php endif; ?>
        </div>





        <script>
        function updateQuantity(id, delta, isGamme = false) {
    let quantityElement;

    // Cibler l'élément de la quantité en fonction du type (produit ou gamme)
    if (isGamme) {
        quantityElement = document.querySelector(`.cart-gamme[data-id="${id}"] .quantity p`);
    } else {
        quantityElement = document.querySelector(`.cart-item[data-id="${id}"] .quantity p`);
    }

    // Si l'élément de quantité n'existe pas, arrêter la fonction
    if (!quantityElement) return;

    let currentQuantity = parseInt(quantityElement.textContent);
    let newQuantity = currentQuantity + delta;

    // Empêcher la quantité de descendre en dessous de zéro
    if (newQuantity < 0) {
        alert("La quantité ne peut pas être inférieure à 0.");
        return;
    }

    // Récupérer la quantité en stock (la quantité maximale autorisée)
    const stockQuantity = parseInt(quantityElement.closest('.cart-item, .cart-gamme').dataset.stock || 0);

    // Vérifier que la quantité ne dépasse pas le stock disponible
    if (newQuantity > stockQuantity) {
        alert(`Vous ne pouvez pas dépasser la quantité en stock de ${stockQuantity} article(s).`);
        return;
    }

    // Mettre à jour l'affichage de la quantité
    quantityElement.textContent = newQuantity;

    // Préparer les données pour la mise à jour côté serveur
    const requestBody = { id_produit: id, delta: delta, is_gamme: isGamme };

    // Faire l'appel AJAX pour mettre à jour la quantité côté serveur
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
            // Mettre à jour le prix si nécessaire
            const priceElement = document.querySelector(`.cart-item[data-id="${id}"] .price, .cart-gamme[data-id="${id}"] .price`);
            if (priceElement) {
                priceElement.textContent = `${data.newPrice.toFixed(2)} €`;
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
            foreach ($gammes as $gamme) {
                $totalTTC += $gamme['prixttc'] * $gamme['quantite'];
            }
            ?>

            <h2>Récapitulatif :</h2>

            <?php if (isset($messagePromo)): ?>

                <?php if (isset($code_promo)): ?>
                    <h3><?= $code_promo['code'] ?></h3>
                    <p class="promo-message"><?= htmlspecialchars($messagePromo) ?></p>
                    <s>Prix TTC : <span class="price-ttc"><?= number_format($totalTTC, 2) ?> €</span></s>
                    <p>Nouveau Prix : <span class="price-ttc"><?= number_format($totalPromo, 2) ?> €</span></p>
                <?php else: ?>
                    <p class="promo-message"><?= htmlspecialchars($messagePromo) ?></p>
                    <p>Prix TTC : <span class="price-ttc"><?= number_format($totalTTC, 2) ?> €</span></p>
                <?php endif; ?>

            <?php else: ?>
                <p>Prix TTC : <span class="price-ttc"><?= number_format($totalTTC, 2) ?> €</span></p>
            <?php endif; ?>

            <form action="/panier/appliquerPromo" method="post">
                <div class="promo-code">
                    <input type="text" name="code_promo" placeholder="Saisir un code promo" required>
                    <button type="submit">Appliquer</button>
                </div>
            </form>


            <a href="/panier/commande"><button class="checkout">Commander</button></a>
        </div>

    </div>
</body>
<!-- Script pour masquer les notifications après 4 secondes -->
<script src="/assets/js/notif.js"></script>

</html>

<?php
require 'footer.php';
?>