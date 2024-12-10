<?php

namespace App\Controllers;

use App\Models\NewsletterModel;
use App\Models\UtilisateurModel;

class NewsletterController extends BaseController
{
    public function inscrire()
    {
        // Validation des données
        $rules = [
            'email' => 'required|valid_email|is_unique[newsletter.email]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Adresse email invalide, déjà inscrite ou non reconnue.');
        }

        // Récupérer l'email depuis le formulaire
        $email = $this->request->getPost('email');

        // Vérification dans la table utilisateur
        $utilisateurModel = new UtilisateurModel();
        $utilisateur = $utilisateurModel->where('mail', $email)->first();

        if (!$utilisateur) {
            // Si l'email n'existe pas dans la table utilisateur
            return redirect()->back()->with('error', 'Seules les adresses liées à un compte utilisateur peuvent s\'inscrire.');
        }

        // Enregistrer dans la base de données
        $newsletterModel = new NewsletterModel();
        $newsletterModel->save(['email' => $email]);

        // Envoyer un email de bienvenue
        if (!$this->envoyerEmailBienvenue($email)) {
            return redirect()->back()->with('error', 'Inscription réussie, mais échec de l\'envoi de l\'email.');
        }

        return redirect()->back()->with('success', 'Vous êtes inscrit à la newsletter avec succès.');
    }

    /**
     * Méthode pour envoyer un email de bienvenue
     *
     * @param string $email
     * @return bool
     */
    private function envoyerEmailBienvenue($email)
    {
        $emailService = \Config\Services::email();

        $emailService->setTo($email);
        $emailService->setFrom('no-reply@votredomaine.com', 'Votre Boutique');
        $emailService->setSubject('Bienvenue dans notre newsletter !');
        $emailService->setMessage("
            <h2>Bienvenue dans notre newsletter !</h2>
            <p>Merci de vous être inscrit. Vous recevrez nos dernières actualités et offres spéciales.</p>
            <br><p>Cordialement,<br><strong>L'équipe OFC Naturel</strong></p>
        ");
        $emailService->setMailType('html');

        return $emailService->send();
    }
}
