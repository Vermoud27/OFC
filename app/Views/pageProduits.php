<?php
require 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/pageProduits.css">
    <link rel="stylesheet" href="/assets/css/notif.css">
    <script src="/assets/js/panier.js"></script>
</head>

<body>
    <main>
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

        <form method="get" action="/produits">
            <div class="filters">
                <!-- Filtre par Catégorie -->
                <label for="categorie">Catégorie :</label>
                <select name="categorie" id="categorie" onchange="handleFilterChange()">
                    <option value="">Tous</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?= $categorie['nom'] ?>" <?= isset($selectedCategorie) && $selectedCategorie == $categorie['nom'] ? 'selected' : '' ?>>
                            <?= ucfirst($categorie['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Filtre par prix -->
                <label for="prix">Prix :</label>
                <select name="prix" id="prix" onchange="handleSortChange('prix'); handleFilterChange()">
                    <option value="">Tous</option>
                    <option value="asc" <?= isset($selectedPrix) && $selectedPrix == 'asc' ? 'selected' : '' ?>>Prix
                        croissant</option>
                    <option value="desc" <?= isset($selectedPrix) && $selectedPrix == 'desc' ? 'selected' : '' ?>>Prix
                        décroissant</option>
                </select>

                <!-- Filtre par popularité -->
                <label for="popularite">Popularité :</label>
                <select name="popularite" id="popularite"
                    onchange="handleSortChange('popularite'); handleFilterChange()">
                    <option value="">Tous</option>
                    <option value="asc" <?= isset($selectedPopularite) && $selectedPopularite == 'asc' ? 'selected' : '' ?>>Moins populaires</option>
                    <option value="desc" <?= isset($selectedPopularite) && $selectedPopularite == 'desc' ? 'selected' : '' ?>>Plus populaires</option>
                </select>

                <!-- Filtre par ingrédients -->
                <label for="ingredients">Ingrédients :</label>
                <input type="text" list="ingredients-list" name="ingredients" id="ingredients"
                    value="<?= isset($selectedIngredients) ? htmlspecialchars($selectedIngredients, ENT_QUOTES, 'UTF-8') : '' ?>"
                    placeholder="Rechercher des ingrédients" onchange="handleFilterChange()" autocomplete="off">
                <datalist id="ingredients-list">
                    <?php foreach ($ingredients as $ingredient): ?>
                        <option value="<?= htmlspecialchars($ingredient['nom'], ENT_QUOTES, 'UTF-8') ?>"></option>
                    <?php endforeach; ?>
                </datalist>


                <!-- Bouton de soumission -->
                <button type="submit" id="submitBtn" hidden>Filtrer</button>
            </div>
        </form>


        <section class="products">
            <h1>Nos Produits</h1>
            <div class="product-grid">

                <?php if (!empty($produits) && is_array($produits)): ?>
                    <?php foreach ($produits as $produit): ?>
                        <div class="product-card" data-url="/produit/<?= $produit['id_produit'] ?>"
                            onclick="handleCardClick(event, this)">

                            <!-- Image du produit -->
                            <?php if (!empty($produit['images'])): ?>
                                <div class="product-images">
                                    <img src="<?= $produit['images'][0]['chemin'] ?>" alt="Image Produit"
                                        id="image-<?= $produit['id_produit'] ?>">
                                </div>
                            <?php else: ?>
                                <div class="product-images">
                                    <img src="/assets/img/produits/placeholder.png" alt="Aucune image disponible">
                                </div>
                            <?php endif; ?>

                            <!-- Détails du produit -->
                            <h2><?= htmlspecialchars($produit['nom']) ?></h2>
                            <p><?= htmlspecialchars($produit['description']) ?></p>
                            <p><?= number_format($produit['prixttc'], 2) ?> €</p>

                            <?php if (isset($produit['quantite'])): ?>
                                <p>Quantité dans le bundle : <?= htmlspecialchars($produit['quantite']) ?></p>
                            <?php endif; ?>
                            <!-- Bouton "Ajouter au panier" -->
                            <button class="add-to-cart"
                                onclick="updateQuantity(<?= $produit['id_produit'] ?>, 1); event.stopPropagation();">
                                Ajouter au panier
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </section>

        <br>
        <div class="footer">
            <?= $pager->links('default', 'perso') ?>
        </div>
    </main>

    <!-- Script pour gérer les clics -->
    <script>
        // Fonction pour gérer les clics sur les cartes
        function handleCardClick(event, cardElement) {
            // Si le clic ne provient pas du bouton "Ajouter au panier"
            const target = event.target;
            if (!target.classList.contains('add-to-cart')) {
                // Rediriger vers l'URL de la carte
                const url = cardElement.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            }
        }
    </script>
    <script>
        function handleSortChange(changedField) {
            const prixField = document.getElementById('prix');
            const populariteField = document.getElementById('popularite');

            if (changedField === 'prix') {
                // Si le tri par prix est modifié, réinitialiser popularité à sa valeur par défaut
                if (prixField.value !== "") {
                    populariteField.value = ""; // Réinitialise le tri par popularité
                }
            } else if (changedField === 'popularite') {
                // Si le tri par popularité est modifié, réinitialiser prix à sa valeur par défaut
                if (populariteField.value !== "") {
                    prixField.value = ""; // Réinitialise le tri par prix
                }
            }
        }

        // Initialiser l'état des filtres au chargement de la page
        document.addEventListener("DOMContentLoaded", function () {
            handleSortChange('prix');
            handleSortChange('popularite');
        });


        // Fonction qui simule un clic sur le bouton de soumission lorsque l'un des filtres change
        function handleFilterChange() {
            // Récupérer le bouton de soumission
            const submitBtn = document.getElementById('submitBtn');

            // Déclencher un clic sur le bouton de soumission
            submitBtn.click();
        }

        // Initialiser l'état des filtres au chargement de la page
        document.addEventListener("DOMContentLoaded", function () {
            handleSortChange('prix');
            handleSortChange('popularite');
        });
    </script>

    <!-- Script pour masquer les notifications après 4 secondes -->
    <script src="/assets/js/notif.js"></script>
</body>

</html>
<?php
require 'footer.php';
?>