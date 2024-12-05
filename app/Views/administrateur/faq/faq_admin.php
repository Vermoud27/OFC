<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin FAQ</title>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- DataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- Nos CSS -->
    <link rel="stylesheet" href="/assets/css/admin/faq.css">
    <link rel="stylesheet" href="/assets/css/admin/navbar_admin.css">
    <link rel="stylesheet" href="/assets/css/notif.css">
</head>

<body>
    <?php require APPPATH . 'Views/administrateur/header_admin.php'; ?>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Administration des FAQ</h2>
            <a href="<?= base_url('faq/create') ?>" class="btn-create">Créer une question</a>
        </div>

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
        <table id="faqTable" class="display">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Réponse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td><?= $faq['question'] ?></td>
                        <td><?= $faq['reponse'] ?></td>
                        <td>
                            <a href="<?= base_url('faq/edit/' . $faq['id_faq']) ?>" class="btn-modifier"><i
                                    class="fas fa-edit"></i> Modifier</a>
                            <form action="<?= base_url('faq/delete/' . $faq['id_faq']) ?>" method="post"
                                style="display:inline;"><button type="submit" class="btn-supprimer"><i
                                        class="fas fa-trash"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $('#faqTable').DataTable({
                language: {
                    url: "/assets/lang/fr-FR.json",
                },
                pageLength: 10
            });
        });
    </script>

    <!-- Script pour masquer les notifications après 4 secondes -->
    <script src="/assets/js/notif.js"></script>
</body>

</html>