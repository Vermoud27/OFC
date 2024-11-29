<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application - Gestion des tâches</title>
  <?php
  $request = service('request');
  $theme = $request->getCookie('theme');
  ?>
  <link rel="stylesheet" href="/assets/css/styles<?= $theme ?>.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
  <div class="header">
    <div class="profile-logout">
      <img src="/assets/img/logo.png" alt="Logo" class="logo-img" />

      <a href="/tache/nouveau">
        <button class="btn-creation">Créer une tâche</button>
      </a>
    </div>

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

    <div class="profile-logout">
      <a href="/user/profile" style="text-decoration: none;">
        <img src="/assets/img/user.png" alt="Profil" style="width: 60px; height: 60px; vertical-align: middle;">
      </a>

      <a href="/logout">
        <button class="btn-deconnexion">Déconnexion</button>
      </a>
    </div>
  </div>

  <div class="filters">
    <form id="filter-form" method="get" action="<?= site_url('ControllerSGT'); ?>">
      <div class="filter-group">
        <input list="task-names" name="task-name" placeholder="Nom de la tâche" value="<?= esc($recherche); ?>"
          id="task-name">
        <datalist id="task-names">
          <?php if (!empty($tachesUtil) && is_array($tachesUtil)): ?>
            <?php foreach ($tachesUtil as $tache): ?>
              <option value="<?= esc($tache['titre']); ?>"></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </datalist>
      </div>
      <div class="filter-group">
        <input type="date" name="due-date" value="<?= esc($date); ?>" id="due-date">
      </div>
      <div class="filter-group">
        <select name="priority" id="priority">
          <option value="0" <?= $priorite == '0' ? 'selected' : ''; ?>>Toutes les priorités</option>
          <option value="1" <?= $priorite == '1' ? 'selected' : ''; ?>>Basse</option>
          <option value="2" <?= $priorite == '2' ? 'selected' : ''; ?>>Moyenne</option>
          <option value="3" <?= $priorite == '3' ? 'selected' : ''; ?>>Haute</option>
        </select>
      </div>
      <div class="filter-group">
        <select name="filter-dropdown" id="filter-dropdown">
          <option value="date_echeance" <?= $trierpar == 'date_echeance' ? 'selected' : ''; ?>>Date d'échéance</option>
          <option value="titre" <?= $trierpar == 'titre' ? 'selected' : ''; ?>>Nom</option>
          <option value="priorite" <?= $trierpar == 'priorite' ? 'selected' : ''; ?>>Priorité</option>
        </select>
      </div>
      <div class="filter-group">
        <select name="sortOrder" id="sortOrder">
          <option value="ASC" <?= $sens == 'ASC' ? 'selected' : ''; ?>>Croissant</option>
          <option value="DESC" <?= $sens == 'DESC' ? 'selected' : ''; ?>>Décroissant</option>
        </select>
      </div>
      <input type="hidden" name="show-completed-tasks" value="0">
      <div class="filter-group">
        <label for="show-completed-tasks">Afficher les tâches terminées</label>
        <input type="checkbox" name="show-completed-tasks" id="show-completed-tasks" value="1" <?= $showCompletedTasks == 1 ? 'checked' : ''; ?>>


      </div>
      <div class="filter-group">
        <button type="button" id="reset-filters" class="reset-button"
          onclick="location.href='<?= site_url('ControllerSGT/resetFilters'); ?>'">
          Réinitialiser les filtres
        </button>
      </div>
    </form>
  </div>

  <div class="main-content">
    <div class="left-panel">


      <div class="commentaires-container">
        <p class="no-commentaire">Aucun commentaire disponible</p>
      </div>
    </div>

    <div class="right-panel">
      <table class="task-table">
        <thead>
          <tr>
            <th>Fini</th>
            <th>Nom de la tâche</th>
            <th>Date Échéance</th>
            <th>Priorités</th>
            <th>Modifier</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $priorityClass = '';
          if (!empty($taches) && is_array($taches)): ?>
            <?php foreach ($taches as $tache):

              switch ($tache['priorite']) {
                case 3:
                  $priorityClass = 'priority-high'; // Classe pour priorité haute
                  break;
                case 2:
                  $priorityClass = 'priority-medium'; // Classe pour priorité moyenne
                  break;
                case 1:
                  $priorityClass = 'priority-low'; // Classe pour priorité basse
                  break;
                default:
                  $priorityClass = 'priority-undefined'; // Classe pour priorité non définie
                  break;
              }
              ?>

              <tr data-id="<?= $tache['idtache'] ?>" class="task-row">
                <!-- Affichage du statut -->
                <td>
                  <input type="checkbox" class="status-checkbox" id="status-<?= $tache['idtache'] ?>"
                    data-id="<?= $tache['idtache'] ?>" <?= $tache['status'] === 't' ? 'checked' : ''; ?>>
                  <label for="status-<?= $tache['idtache'] ?>" class="checkbox-label"></label>
                </td>
                <td><?= esc($tache['titre']) ?></td>
                <td>
                  <?php
                  // Créer un objet IntlDateFormatter pour formater la date
                  $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
                  $formatter->setPattern('d MMMM yyyy'); // Format jj mois yyyy
                  $date = DateTime::createFromFormat('Y-m-d', $tache['date_echeance']);
                  if ($date) {
                    $currentDate = new DateTime();
                    if ($date < $currentDate && $tache['status'] == 'f') {
                      echo $formatter->format($date) . ' <i class="fa fa-exclamation-triangle" style="color: red;"></i> ';
                    } else {
                      echo $formatter->format($date);
                    }
                  } else {
                    echo 'Date invalide';
                  }
                  ?>
                </td>
                <!-- Affichage de la priorité avec la classe de priorité -->
                <td class="priority-cell <?= $priorityClass ?>"> <!-- Ajout de la classe $priorityClass ici -->
                  <?php
                  switch ($tache['priorite']) {
                    case 3:
                      echo 'Haute';
                      break;
                    case 2:
                      echo 'Moyenne';
                      break;
                    case 1:
                      echo 'Basse';
                      break;
                    default:
                      echo 'Non défini';
                      break;
                  }
                  ?>
                </td>
                <td>
                  <button class="btn-modiff"
                    onclick="window.location.href='<?= site_url('TacheController/modification/' . $tache['idtache']); ?>'">
                    Modifier
                  </button>
                </td>

              </tr>

            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>


      </table>
    </div>

  </div>

  <div class="footer">
    <?= $pager->links('default','perso') ?>
  </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function () {
      // Soumettre automatiquement le formulaire lorsqu'un champ change
      $('#filter-form input, #filter-form select').on('change', function () {
        $('#filter-form').submit();  // Soumettre automatiquement le formulaire
      });

      // Réinitialiser les filtres lorsque le bouton est cliqué
      $('#reset-filters').on('click', function () {
        // Réinitialiser tous les champs du formulaire à leurs valeurs par défaut
        $('#filter-form')[0].reset();  // Remet tous les champs à leur état par défaut

        // Réinitialiser la case à cocher "Afficher les tâches terminées" (non cochée par défaut)
        $('#show-completed-tasks').prop('checked', false);  // Décoche la case

        // Réinitialiser les champs des filtres à des valeurs par défaut spécifiques si nécessaire
        $('#priority').val('0'); // Valeur par défaut pour la priorité
        $('#filter-dropdown').val('date_echeance'); // Valeur par défaut pour le tri
        $('#sortOrder').val('ASC'); // Valeur par défaut pour l'ordre de tri
        $('#task-name').val(''); // Réinitialiser le champ nom de tâche
        $('#due-date').val(''); // Réinitialiser le champ de date

        // Soumettre le formulaire après réinitialisation
        $('#filter-form').submit(); // Soumettre le formulaire avec les valeurs par défaut
      });
    });



  </script>

  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

  <script>
    document.querySelectorAll('.task-row').forEach(row => {
      row.addEventListener('click', function () {
        // Supprimer la classe 'selected' de toutes les lignes
        document.querySelectorAll('.task-row').forEach(r => r.classList.remove('selected'));

        // Ajouter la classe 'selected' à la ligne cliquée
        this.classList.add('selected');

        const idTache = this.getAttribute('data-id'); // Récupérer l'idTache depuis l'attribut data-id

        // Récupérer l'URL actuelle
        const currentUrl = window.location.href;

        // Créer un objet URL à partir de l'URL actuelle
        const url = new URL(currentUrl);

        // Mettre à jour le paramètre 'idTache' dans l'URL
        url.searchParams.set('idTache', idTache);

        // Mettre à jour l'URL sans recharger la page
        window.history.pushState({ path: url.toString() }, '', url.toString());  // Modifie l'URL


        // Si un idTache est présent dans l'URL, récupérez les commentaires associés
        if (idTache) {
          fetch(`/ControllerSGT/getComments/${idTache}`)
            .then(response => response.json())
            .then(data => {
              const container = document.querySelector('.commentaires-container');
              container.innerHTML = ''; // Efface les anciens commentaires

              if (data.status === 'success' && data.comments.length > 0) {
                // Si des commentaires sont présents, les afficher
                data.comments.forEach(commentaire => {
                  const commentDiv = document.createElement('div');
                  commentDiv.classList.add('commentaire');
                  commentDiv.innerHTML = `
                                <p class="date">${moment(commentaire.datecréation).format('DD-MM-YYYY HH:mm:ss')}</p>
                                <p class="texte">${commentaire.texte}</p>
                            `;
                  container.appendChild(commentDiv);
                });
              } else {
                // Si aucun commentaire, afficher un message
                container.innerHTML = '<p class="no-commentaire">Aucun commentaire disponible</p>';
              }
            })
            .catch(error => {
              console.error('Erreur AJAX:', error);
            });
        }
      });

      // Ajoute un gestionnaire pour le double-clic
      row.addEventListener('dblclick', function () {
        const idTache = this.getAttribute('data-id'); // Récupérer l'idTache depuis l'attribut data-id
        const modificationUrl = `/TacheController/modification/${idTache}`;  // Créer l'URL de modification

        // Rediriger vers la page de modification
        window.location.href = modificationUrl;
      });
    });

  </script>
  <!-- Script pour masquer les notifications après 4 secondes -->
  <script src="/assets/js/notif.js"></script>

  <script>
    $(document).ready(function () {
      // Quand la case à cocher change
      $('.status-checkbox').on('change', function () {
        var taskId = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;  // Si cochée, envoie 1, sinon 0

        // Envoie AJAX pour mettre à jour le statut
        $.ajax({
          url: '<?= site_url('TacheController/modifierStatus/') ?>' + taskId,
          method: 'POST',
          data: { status: status },
          success: function (response) {
            console.log('Statut mis à jour');
          },
          error: function () {
            console.error('Erreur de mise à jour');
          }
        });
      });
    });
  </script>

</body>

</html>