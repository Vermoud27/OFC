<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\FAQModel;

class FaqController extends Controller
{
    public function index()
    {
        $faqModel = new FaqModel(); // Instanciez le modèle

        // Récupérez les 10 premières questions
        $data['faqs'] = $faqModel->orderBy('id_faq', 'ASC')->findAll(10);

        // Chargez la vue avec les questions
        return view('faq', $data);
    }

    public function sendMail()
    {
        // Charger le service Email
        $emailService = \Config\Services::email();

        // Récupérer les données du formulaire
        $fromEmail = $this->request->getPost('email'); 
        $prenom = $this->request->getPost('prenom');
        $nom = $this->request->getPost('nom');
        $message = $this->request->getPost('message');

        // Contenu du message
        $emailContent = "
            Vous avez reçu une question depuis la FAQ de :\n
            Prénom : $prenom\n
            Nom : $nom\n
            Email : $fromEmail\n\n
            Message :\n$message
        ";

        // Configurer l'email
        $emailService->setFrom('ofc99935@gmail.com', 'OFC Naturel');
        $emailService->setTo('ofc99935@gmail.com');
        $emailService->setSubject('Nouvelle question posé dans la FAQ');
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