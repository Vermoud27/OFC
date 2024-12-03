<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ContactController extends Controller
{
    public function sendMail()
    {
        // Charger le service Email
        $emailService = \Config\Services::email();

        // Récupérer les données du formulaire
        $fromEmail = $this->request->getPost('email'); // Email de l'utilisateur
        $prenom = $this->request->getPost('prenom');
        $nom = $this->request->getPost('nom');
        $message = $this->request->getPost('message');

        // Contenu du message
        $emailContent = "
            Vous avez reçu un message de :\n
            Prénom : $prenom\n
            Nom : $nom\n
            Email : $fromEmail\n\n
            Message :\n$message
        ";

        // Configurer l'email
        $emailService->setFrom('ofc99935@gmail.com', 'OFC Naturel');
        $emailService->setTo('ofc99935@gmail.com');                 
        $emailService->setSubject('Nouveau message depuis le formulaire de contact');
        $emailService->setMessage($emailContent);

        // Tenter d'envoyer l'email
        if ($emailService->send()) {
            return redirect()->back()->with('success', 'Votre message a été envoyé avec succès.');
        } else {
            // Récupérer les erreurs
            $data = $emailService->printDebugger(['headers']);
            log_message('error', $data);
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer plus tard.');
        }
    }
}
