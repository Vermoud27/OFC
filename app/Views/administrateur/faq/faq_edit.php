<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une FAQ</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/admin/faqCU.css">
</head>
<body>

<div class="form-container">
    <h1>Modifier une question</h1>
    <form action="<?= base_url('/faq/update/' . $faq['id_faq']) ?>" method="post">
        <label for="question">Question</label>
        <input type="text" id="question" name="question" value="<?= esc($faq['question']) ?>" required>
        
        <label for="reponse">Réponse</label>
        <textarea id="reponse" name="reponse" required><?= esc($faq['reponse']) ?></textarea>
        
        <div style="display: flex; justify-content: space-between;">
            <a href="<?= base_url('/faq/admin') ?>" class="btn-secondary">Annuler</a>
            <button type="submit">Modifier</button>
        </div>
    </form>
</div>

</body>
</html>
