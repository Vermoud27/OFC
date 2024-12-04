<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Code Promo</title>
    <link rel="stylesheet" href="/assets/css/admin/creation.css">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h1>Créer un code promo</h1>
            <form method="post" action="<?= base_url('/admin/codes-promos/creer') ?>">
                <div>
                    <label for="code">Code Promo</label>
                    <input type="text" id="code" name="code" placeholder="Entrez le code promo" required>
                </div>

                <div class="grid-2-columns">
                    <div>
                        <label for="valeur">Réduction en € (optionnelle)</label>
                        <input type="number" id="valeur" name="valeur" placeholder="Entrez la réduction en euros">
                    </div>
                    <div>
                        <label for="pourcentage">Réduction en % (optionnelle)</label>
                        <input type="number" id="pourcentage" name="pourcentage"
                            placeholder="Entrez la réduction en pourcentage">
                    </div>
                </div>

                <div class="grid-2-columns">
                    <div>
                        <label for="date_debut">Date de début</label>
                        <input type="datetime-local" id="date_debut" name="date_debut" required>
                    </div>
                    <div>
                        <label for="date_fin">Date de fin</label>
                        <input type="datetime-local" id="date_fin" name="date_fin" required>
                    </div>
                </div>

                <div>
                    <label for="max_utilisations">Nombre maximal d'utilisations</label>
                    <input type="number" id="max_utilisations" name="max_utilisations"
                        placeholder="Entrez le nombre maximal d'utilisations">
                </div>

                <div>
                    <label for="conditions_utilisation">Conditions d'utilisation (optionnelles)</label>
                    <textarea id="conditions_utilisation" name="conditions_utilisation" rows="3"
                        placeholder="Entrez les conditions d'utilisation"></textarea>
                </div>

                <div>
                    <label for="actif">Actif :</label>
                    <select name="actif" id="actif" required>
                        <option value="TRUE">Oui</option>
                        <option value="FALSE">Non</option>
                    </select>
                </div>

                <div class="actions">
                    <button type="submit">Créer</button>
                    <button type="button"
                        onclick="window.location='<?= base_url('/admin/codes-promos') ?>';">Annuler</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>