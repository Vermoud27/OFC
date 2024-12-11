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
                            <p class="price"><?= number_format($produit['prixttc'] * $produit['quantite'], 2) ?> €</p>
                            <button class="sup" onclick="retirerProduit(<?= $produit['id_produit'] ?>)"></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>




            <!-- GAMMES -->
            <?php if (!empty($gammes)): ?>
                <?php foreach ($gammes as $gamme): ?>
                    <!-- Pour chaque gamme -->
                    <div class="cart-gamme" data-id="<?= htmlspecialchars($gamme['id_gamme']) ?>"
                        data-prix-ttc="<?= htmlspecialchars($gamme['prixttc']) ?>">
                        <ul class="gamme-produits">
                            <?php foreach ($produitsParGamme[$gamme['id_gamme']] as $produitGamme): ?>
                                <li data-stock="<?= htmlspecialchars($produitGamme['qte_stock'] ?? 0) ?>">
                                    <?= htmlspecialchars($produitGamme['nom']) ?> - Stock :
                                    <?= htmlspecialchars($produitGamme['qte_stock']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="quantity">
                            <button onclick="updateQuantityGamme(<?= htmlspecialchars($gamme['id_gamme']) ?>, -1)">-</button>
                            <p><?= htmlspecialchars($gamme['quantite']) ?></p>
                            <button onclick="updateQuantityGamme(<?= htmlspecialchars($gamme['id_gamme']) ?>, 1)">+</button>
                        </div>
                        <p class="price"><?= number_format($gamme['prixttc'] * $gamme['quantite'], 2) ?> €</p>
                        <button class="sup" onclick="retirerGamme(<?= $gamme['id_gamme'] ?>)"></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        



            <!-- BUNDLES -->
            <?php if (!empty($bundles)): ?>
                <?php foreach ($bundles as $bundle): ?>
                    <!-- Pour chaque bundle -->
                    <div class="cart-bundle" data-id="<?= htmlspecialchars($bundle['id_bundle']) ?>"
                        data-prix-ttc="<?= htmlspecialchars($bundle['prix']) ?>">
                        <ul class="bundle-produits">
                            <?php foreach ($produitsParBundle[$bundle['id_bundle']] as $produitBundle): ?>
                                <li data-stock="<?= htmlspecialchars($produitBundle['qte_stock'] ?? 0) ?>">
                                    <?= htmlspecialchars($produitBundle['nom']) ?> - Stock :
                                    <?= htmlspecialchars($produitBundle['qte_stock']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="quantity">
                            <button onclick="updateQuantityBundle(<?= htmlspecialchars($bundle['id_bundle']) ?>, -1)">-</button>
                            <p><?= htmlspecialchars($bundle['quantite']) ?></p>
                            <button onclick="updateQuantityBundle(<?= htmlspecialchars($bundle['id_bundle']) ?>, 1)">+</button>
                        </div>
                        <p class="price"><?= number_format($bundle['prix'] * $bundle['quantite'], 2) ?> €</p>
                        <button class="sup" onclick="retirerBundle(<?= $bundle['id_bundle'] ?>)"></button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>


            <!-- MESSAGE SI RIEN DANS PANIER -->
            <?php if (empty($produits) && empty($bundle) && empty($gammes)): ?>
                <p class="empty">Votre panier est vide.</p>
            <?php endif; ?>

        </div>


        <script>
            function updateQuantity(productId, delta) {
                const quantityElement = document.querySelector(`.cart-item[data-id="${productId}"] .quantity p`);
                let currentQuantity = parseInt(quantityElement.textContent, 10);

                // Calcul de la nouvelle quantité
                let newQuantity = currentQuantity + delta;

                // Récupérer la quantité en stock
                const stockQuantity = parseInt(document.querySelector(`.cart-item[data-id="${productId}"] .stock-status`).dataset.stock, 10);

                // Suppression du produit quand quantité <= 0
                if (newQuantity <= 0) {
                    retirerProduit(productId);
                    return;  // On arrête la fonction ici
                }

                // Si la nouvelle quantité dépasse le stock disponible, afficher un message d'erreur
                if (newQuantity > stockQuantity) {
                    alert(`Vous ne pouvez pas dépasser la quantité en stock de ${stockQuantity} article(s).`);
                    return;
                }

                // Mise à jour de l'affichage de la quantité
                quantityElement.textContent = newQuantity;

                // Envoi de la mise à jour au serveur via fetch
                const requestBody = { id_produit: productId, delta: delta };

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
                            // Mettre à jour le prix du produit dans la cellule
                            const priceElement = document.querySelector(`.cart-item[data-id="${productId}"] .price`);
                            const updatedPrice = parseFloat(data.newPrice).toFixed(2); // Assurez-vous que le prix est un nombre valide

                            if (priceElement) {
                                priceElement.textContent = `${updatedPrice} €`;
                            }

                            // Recalculer le total TTC du panier après mise à jour
                            updateCartSummary();
                        } else {
                            alert(data.message || 'Erreur lors de la mise à jour de la quantité.');
                        }
                    })
                    .catch(error => console.error('Erreur lors de la requête:', error));
            }



            function updateQuantityGamme(gammeId, delta) {
                console.log('ID Gamme:', gammeId); // Log
                console.log('Delta:', delta); // Log

                const gammeElement = document.querySelector(`.cart-gamme[data-id="${gammeId}"]`);
                if (!gammeElement) {
                    console.error("Erreur : gamme introuvable.");
                    return;
                }

                // Calculer la quantité maximale autorisée pour cette gamme
                const maxQuantity = calculateGammeMaxQuantity(gammeId);
                if (maxQuantity === 0) {
                    console.error("Aucun produit disponible pour cette gamme.");
                    return;
                }

                // Récupérer l'élément contenant la quantité actuelle
                const quantityElement = gammeElement.querySelector('.quantity p');
                let currentQuantity = parseInt(quantityElement.textContent, 10) || 0;

                // Calcul de la nouvelle quantité
                const newQuantity = currentQuantity + delta;

                // Vérification des limites
                if (newQuantity <= 0) {
                    retirerGamme(gammeId); // Si la quantité atteint zéro, on retire la gamme
                    return;  // On arrête la fonction ici, car la gamme est retirée
                }

                if (newQuantity > maxQuantity) {
                    console.error(`Vous ne pouvez pas dépasser la quantité maximale de ${maxQuantity} pour cette gamme.`);
                    return;
                }

                // Mise à jour de la quantité dans l'interface
                quantityElement.textContent = newQuantity;

                // Récupérer le prix unitaire de la gamme
                const unitPrice = parseFloat(gammeElement.getAttribute('data-prix-ttc')); // Assurez-vous que le prix est stocké comme un attribut data-prix-ttc

                // Calcul du prix total pour cette gamme
                const newTotalPrice = (unitPrice * newQuantity).toFixed(2);

                // Mise à jour du prix total dans l'interface
                const priceElement = gammeElement.querySelector('.price');
                if (priceElement) {
                    priceElement.textContent = `${newTotalPrice} €`; // Affichage du nouveau prix
                }

                // Recalcul du total TTC pour toutes les gammes et produits dans le panier
                updateCartSummary();

                // Envoyer les données au serveur pour synchronisation
                const requestBody = { id_gamme: gammeId, delta: delta };

                fetch('/panier/updateGamme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestBody),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log("Quantité mise à jour avec succès.");
                        } else {
                            console.error("Erreur serveur:", data.message);
                            // Remettre la quantité et le prix précédents en cas d'erreur
                            quantityElement.textContent = currentQuantity;
                            priceElement.textContent = `${(unitPrice * currentQuantity).toFixed(2)} €`;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la requête:', error);
                    })
                    .finally(() => {
                        // Actions à faire indépendamment du succès ou de l'échec
                        console.log("Requête terminée.");
                    });
            }



            function updateQuantityBundle(bundleId, delta) {

                const bundleElement = document.querySelector(`.cart-bundle[data-id="${bundleId}"]`);
                if (!bundleElement) {
                    console.error("Erreur : bundle introuvable.");
                    return;
                }

                // Calculer la quantité maximale autorisée pour cette gamme
                const maxQuantity = calculateBundleMaxQuantity(bundleId);
                if (maxQuantity === 0) {
                    console.error("Aucun produit disponible pour ce bundle.");
                    return;
                }

                // Récupérer l'élément contenant la quantité actuelle
                const quantityElement = bundleElement.querySelector('.quantity p');
                let currentQuantity = parseInt(quantityElement.textContent, 10) || 0;

                // Calcul de la nouvelle quantité
                const newQuantity = currentQuantity + delta;

                // Vérification des limites
                if (newQuantity <= 0) {
                    retirerBundle(bundleId); // Si la quantité atteint zéro, on retire la gamme
                    return;  // On arrête la fonction ici, car la gamme est retirée
                }

                if (newQuantity > maxQuantity) {
                    console.error(`Vous ne pouvez pas dépasser la quantité maximale de ${maxQuantity} pour ce bundle.`);
                    return;
                }

                // Mise à jour de la quantité dans l'interface
                quantityElement.textContent = newQuantity;

                // Récupérer le prix unitaire du bundle
                const unitPrice = parseFloat(bundleElement.getAttribute('data-prix-ttc')); // Assurez-vous que le prix est stocké comme un attribut data-prix-ttc

                // Calcul du prix total pour cette gamme
                const newTotalPrice = (unitPrice * newQuantity).toFixed(2);

                // Mise à jour du prix total dans l'interface
                const priceElement = bundleElement.querySelector('.price');
                if (priceElement) {
                    priceElement.textContent = `${newTotalPrice} €`; // Affichage du nouveau prix
                }

                // Recalcul du total TTC pour toutes les gammes et produits dans le panier
                updateCartSummary();

                // Envoyer les données au serveur pour synchronisation
                const requestBody = { id_bundle: bundleId, delta: delta };

                fetch('/panier/updateBundle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestBody),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log("Quantité mise à jour avec succès.");
                        } else {
                            console.error("Erreur serveur:", data.message);
                            // Remettre la quantité et le prix précédents en cas d'erreur
                            quantityElement.textContent = currentQuantity;
                            priceElement.textContent = `${(unitPrice * currentQuantity).toFixed(2)} €`;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la requête:', error);
                    })
                    .finally(() => {
                        // Actions à faire indépendamment du succès ou de l'échec
                        console.log("Requête terminée.");
                    });
                }



            // Fonction pour recalculer et mettre à jour le total TTC du panier
            function updateCartSummary() {
                let totalTTC = 0;

                // Calculer les prix des produits
                document.querySelectorAll('.cart-item').forEach(cartItem => {
                    const quantity = parseInt(cartItem.querySelector('.quantity p').textContent, 10) || 0;
                    const price = parseFloat(cartItem.querySelector('.price').textContent.replace(' €', '').replace(',', '.')) || 0;
                    totalTTC += price;
                });

                // Calculer les prix des gammes
                document.querySelectorAll('.cart-gamme').forEach(gammeElement => {
                    const quantity = parseInt(gammeElement.querySelector('.quantity p').textContent, 10) || 0;
                    const price = parseFloat(gammeElement.querySelector('.price').textContent.replace(' €', '').replace(',', '.')) || 0;
                    totalTTC += price;
                });

                // Calculer les prix des bundles
                document.querySelectorAll('.cart-bundle').forEach(bundleElement => {
                    const quantity = parseInt(bundleElement.querySelector('.quantity p').textContent, 10) || 0;
                    const price = parseFloat(bundleElement.querySelector('.price').textContent.replace(' €', '').replace(',', '.')) || 0;
                    totalTTC += price;
                });

                // Mettre à jour le total dans le récapitulatif
                const totalElement = document.querySelector('.price-ttc');
                if (totalElement) {
                    totalElement.textContent = `${totalTTC.toFixed(2)} €`;
                }
            }



            function calculateGammeMaxQuantity(gammeId) {
                const gammeElement = document.querySelector(`.cart-gamme[data-id="${gammeId}"]`);
                if (!gammeElement) return 0;

                const produits = gammeElement.querySelectorAll('.gamme-produits li');
                let maxQuantity = Infinity;

                produits.forEach(produit => {
                    const stock = parseInt(produit.getAttribute('data-stock'), 10) || 0;
                    maxQuantity = Math.min(maxQuantity, stock);
                });

                return maxQuantity === Infinity ? 0 : maxQuantity;
            }

            function calculateBundleMaxQuantity(bundleId) {
                const bundleElement = document.querySelector(`.cart-bundle[data-id="${bundleId}"]`);
                if (!bundleElement) return 0;

                const produits = bundleElement.querySelectorAll('.bundle-produits li');
                let maxQuantity = Infinity;

                produits.forEach(produit => {
                    const stock = parseInt(produit.getAttribute('data-stock'), 10) || 0;
                    maxQuantity = Math.min(maxQuantity, stock);
                });

                return maxQuantity === Infinity ? 0 : maxQuantity;
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
            foreach ($bundles as $bundle) {
                $totalTTC += $bundle['prix'] * $bundle['quantite'];
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

    <?php
    require 'footer.php';
    ?>
</body>
<!-- Script pour masquer les notifications après 4 secondes -->
<script src="/assets/js/notif.js"></script>

</html>