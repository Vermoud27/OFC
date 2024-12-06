<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nom Produit</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/header.css">
  <link rel="stylesheet" href="/assets/css/footer.css">
  <link rel="stylesheet" href="/assets/css/infoProduit.css">
  <link rel="stylesheet" href="/assets/css/notif.css">
  <script src="/assets/js/panier.js"></script>
</head>
<?php
require 'header.php';
?>

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
  <div class="container">
    <h1>Accueil - <?= $produit['nom'] ?></h1>
    <main>
      <div class="product">

        <?php if (!empty($images)): ?>
          <div class="product-images">
            <div class="image-wrapper">
              <?php if (count($images) > 1): ?>
                <button class="nav-btn" onclick="changeImage(-1)">&lt;</button>
              <?php endif; ?>

              <img src="<?= htmlspecialchars($images[0]['chemin']) ?>" alt="Image Produit" class="product-image"
                id="product-image">

              <?php if (count($images) > 1): ?>
                <button class="nav-btn" onclick="changeImage(1)">&gt;</button>
              <?php endif; ?>
            </div>
          </div>
        <?php else: ?>
          <div class="product-images">
            <div class="image-wrapper">
              <img src="/assets/img/produits/placeholder.png" alt="Aucune image disponible" class="product-image">
            </div>
          </div>
        <?php endif; ?>


        <div class="product-details">
          <h2><?= $produit['nom'] ?></h2>
          <p><?= $produit['description'] ?></p>
          <p><?= $produit['contenu'] ?><?= $produit['unite_mesure'] ?></p>
          <div class="info-grid">
            <div class="composition">
              <h3>Composition</h3>
              <?php if (!empty($ingredients)): ?>
                <ul>
                  <?php foreach ($ingredients as $ingredient): ?>
                    <li>
                      <?= htmlspecialchars($ingredient['nom']) ?> (Provenance :
                      <?= htmlspecialchars($ingredient['provenance']) ?>)
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php else: ?>
                <p>Aucun ingrédient associé à ce produit.</p>
              <?php endif; ?>
            </div>
            <div class="payment-delivery">
              <h3>Paiement et livraison</h3>
              <p>Prix : <?= $produit['prixttc'] ?> €</p>
            </div>
          </div>
          <button onclick="updateQuantity(<?= $produit['id_produit'] ?>, 1)" class="add-to-cart">Ajouter au
            panier</button>
        </div>
      </div>
      <div class="user-reviews">
        <?php if (!empty($commentaires)): ?>
          <?php foreach ($commentaires as $commentaire): ?>
            <div class="review" data-comment-id="<?= $commentaire['id_commentaire'] ?>">
              <h4><?= htmlspecialchars($commentaire['prenom']) . ' ' . htmlspecialchars($commentaire['nom']) ?>
                - <span class="comment-date"><?= date('d/m/Y H:i', strtotime($commentaire['date_commentaire'])) ?></span>
              </h4>
              <p><?= htmlspecialchars($commentaire['contenu']) ?></p>
              <?php if (session()->get('role') == 'Admin'): ?>
                <a href="#" class="delete-comment-button" data-comment-id="<?= $commentaire['id_commentaire'] ?>">
                  <i class="fas fa-trash-alt"></i>
                </a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aucun commentaire pour ce produit.</p>
        <?php endif; ?>
        <?php if (!$all && count($commentaires) >= 5): ?>
          <a href="?all_comments=true" class="see-more">Voir plus</a>
        <?php endif; ?>
      </div>
      <div class="related-products">
        <img src="/assets/img/produits/gommage_eclat_1.jpeg" alt="Produit lié 1">
        <img src="/assets/img/produits/huile_baobab_3.jpeg" alt="Produit lié 2">
        <img src="/assets/img/produits/masque_safran_1.jpeg" alt="Produit lié 3">
        <img src="/assets/img/produits/savon_bleu.jpeg" alt="Produit lié 4">
      </div>
    </main>
  </div>
  <!-- Script pour masquer les notifications après 4 secondes -->
  <script src="/assets/js/notif.js"></script>
  <script>
    // Tableau des chemins d'images
    var images = <?= json_encode(array_column($images, 'chemin')) ?>;
    var currentIndex = 0;

    function changeImage(direction) {
      // Calculer l'index suivant
      currentIndex = (currentIndex + direction + images.length) % images.length;

      // Mettre à jour la source de l'image affichée
      document.getElementById('product-image').src = images[currentIndex];
    }
  </script>
  <!-- Script pour supprimer un commentaire -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.delete-comment-button').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          const commentId = btn.getAttribute('data-comment-id');
          if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
            fetch('/CommentaireController/supprimer/' + commentId, {
              method: 'GET'
            })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  // Trouver le bloc review associé et le supprimer
                  const review = document.querySelector('.review[data-comment-id="' + commentId + '"]');
                  if (review) {
                    review.remove();
                  }
                } else {
                  alert(data.message || 'Erreur lors de la suppression.');
                }
              })
              .catch(err => console.error(err));
          }
        });
      });
    });
  </script>

  <?php
  require 'footer.php';
  ?>