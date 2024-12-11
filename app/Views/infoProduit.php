<?php
require 'header.php';
?>

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


<body>
  <!-- Affichage des messages flash -->
  <section class="section">
    <div class="container">
      <div id="notifications" class="notifications">
        <?php if (session()->getFlashdata('success_rating')): ?>
          <div class="notification is-success">
            <?= session()->getFlashdata('success_rating') ?>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success_comment')): ?>
          <div class="notification is-success">
            <?= session()->getFlashdata('success_comment') ?>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error_rating')): ?>
          <div class="notification is-danger">
            <?= session()->getFlashdata('error_rating') ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

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

        <div class="rating-section">
          <h2>Notes Produit</h2>
          <div class="rating-overview">
            <h3><?= number_format($averageRating, 1) ?> / 5</h3>
            <div class="stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <i class="fa-star<?= $i <= round($averageRating) ? ' fa-solid' : ' fa-regular' ?>"
                  style="<?= $i <= round($averageRating) ? 'color: #FFD43B;' : '' ?>"></i>
              <?php endfor; ?>
            </div>
            <p><?= $totalRatings ?> Notes</p>
          </div>
          <button class="rate-review-btn">Noter / Commenter le Produit</button>
        </div>

        <div class="popup-overlay hidden">
          <div class="popup">
            <button class="close-popup">&times;</button>
            <h3>Soumettre votre avis</h3>

            <?php echo form_open('/submitRating', ['enctype' => 'multipart/form-data']); ?>

            <div class="stars-input">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <i class="fa-star rate-star <?= (isset($existingRating) && (int) $existingRating['valeur'] >= $i) ? 'fa-solid selected' : 'fa-regular' ?>"
                  data-value="<?= $i ?>"></i>
              <?php endfor; ?>
            </div>

            <input type="hidden" name="rating" id="rating-value" value="<?= isset($existingRating) ? (int) $existingRating['valeur'] : 0 ?>">
            <input type="hidden" name="id_utilisateur" value="<?= session()->get('idutilisateur') ?>">
            <input type="hidden" name="idProduit" value="<?= $produit['id_produit'] ?>">

            <div class="comment-input">
              <?php echo form_label('Commentaire', 'comment'); ?>
              <?php echo form_textarea('comment', set_value('comment')); ?>
              <?= validation_show_error('comment') ?>
            </div>
            <div>
              <button type="submit"
                class="submit-review"><?= isset($existingRating) ? 'Mettre à jour ma note / Mettre un commentaire' : 'Ajouter une note / un commentaire' ?></button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>

        <div class="product-details">
          <h2><?= $produit['nom'] ?></h2>
          <p><?= $produit['description'] ?></p>
          <p><?= $produit['contenu'] ?><?= $produit['unite_mesure'] ?></p>
          <p>Quantité en stock : <?= $produit['qte_stock'] ?></p>
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
      <h2>Découvrez de nouveaux produits</h2>
      <div class="related-products">

        <div class="random-products-grid">
          <?php foreach ($produitsAleatoires as $produitAleatoire): ?>
              <div class="random-product">
                  <a href="/produit/<?= $produitAleatoire['id_produit'] ?>">
                      <img src="<?= $produitAleatoire['images'][0]['chemin'] ?? '/assets/img/produits/placeholder.png'?>" alt="<?= htmlspecialchars($produitAleatoire['nom']) ?>">
                      <p><?= htmlspecialchars($produitAleatoire['nom']) ?></p>
                      <p><?= $produitAleatoire['prixttc'] ?> €</p>
                  </a>
              </div>
          <?php endforeach; ?>
        </div>
        
      </div>
    </main>
  </div>

  <!-- Script pour masquer les notifications après 4 secondes -->
  <script src="/assets/js/notif.js"></script>

  <!-- Script pour afficher les images -->
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

  <!-- Script pour le rating -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const rateReviewBtn = document.querySelector('.rate-review-btn');
      const popupOverlay = document.querySelector('.popup-overlay');
      const closePopupBtn = document.querySelector('.close-popup');
      const stars = document.querySelectorAll('.rate-star');
      const ratingValueInput = document.getElementById('rating-value');
      const reviewForm = document.getElementById('review-form');

      // Assurez-vous que ces éléments existent vraiment dans le HTML
      if (!popupOverlay || !closePopupBtn) {
        console.error("Impossible de trouver la pop-up ou le bouton pour la fermer.");
        return;
      }

      // Ouvrir la pop-up
      rateReviewBtn.addEventListener('click', () => {
        popupOverlay.classList.remove('hidden');
      });

      // Fermer la pop-up en cliquant sur la croix
      closePopupBtn.addEventListener('click', () => {
        popupOverlay.classList.add('hidden');
      });

      // Fermer la pop-up en cliquant sur l'overlay sombre
      popupOverlay.addEventListener('click', (e) => {
        if (e.target === popupOverlay) {
          popupOverlay.classList.add('hidden');
        }
      });

      // Fonction pour colorer les étoiles jusqu'à une valeur donnée
      function updateStars(value) {
        stars.forEach((star, index) => {
          if (index < value) {
            star.classList.add('fa-solid');
            star.classList.remove('fa-regular');
            star.style.color = '#FFD43B'; // Jaune
          } else {
            star.classList.remove('fa-solid');
            star.classList.add('fa-regular');
            star.style.color = '#000000'; // Retour à la couleur par défaut
          }
        });
      }

      // Initialisation : Appliquer la note existante (si elle existe)
      const existingRating = parseInt(ratingValueInput.value);
      if (existingRating > 0) {
        updateStars(existingRating);
      }

      // Sélection dynamique des étoiles
      stars.forEach(star => {
        star.addEventListener('click', () => {
          const value = parseInt(star.getAttribute('data-value'));
          ratingValueInput.value = value; // Mettre à jour la valeur de l'input caché
          updateStars(value); // Mettre à jour les étoiles visuellement
        });
      });
    });

  </script>

  <script>
    function sendReview() {
      window.location.href = `/submitRating`;
    }
  </script>

  <?php
  require 'footer.php';
  ?>