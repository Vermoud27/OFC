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
        return view('/faq', $data);
    }

    public function admin()
    {
        $faqModel = new FaqModel();

        // Récupère toutes les FAQ
        $data['faqs'] = $faqModel->findAll();

        // Charge la vue admin
        return view('administrateur/faq/faq_admin', $data);
    }

    public function viewCreate()
    {
        return view('administrateur/faq/faq_create');
    }

    public function create()
    {

        // Debugging : Si nous arrivons ici, la requête est bien en POST
        $faqModel = new FaqModel();

        // Récupération des données du formulaire
        $question = $this->request->getPost('question');
        $reponse = $this->request->getPost('reponse');

        // Validation basique
        if (empty($question) || empty($reponse)) {
            return redirect()->back()->with('error', 'Les champs Question et Réponse sont obligatoires.');
        }

        // Insertion dans la base de données
        $faqModel->insert([
            'question' => $question,
            'reponse' => $reponse,
        ]);

        return redirect()->to('/faq/admin')->with('success', 'Question créée avec succès.');

    }

    public function edit($id)
    {
        $faqModel = new FaqModel();

        // Récupère les données de la FAQ
        $faq = $faqModel->find($id);

        if (!$faq) {
            return redirect()->to('/faq/admin')->with('error', 'Question non trouvée.');
        }

        $data['faq'] = $faq;

        // Charge la vue de modification
        return view('administrateur/faq/faq_edit', $data);
    }

    public function update($id)
    {
        $faqModel = new FaqModel();

        // Récupère les données du formulaire
        $question = $this->request->getPost('question');
        $reponse = $this->request->getPost('reponse');

        // Met à jour la FAQ
        $faqModel->update($id, [
            'question' => $question,
            'reponse' => $reponse,
        ]);

        return redirect()->to('/faq/admin')->with('success', 'Question mise à jour avec succès.');
    }

    public function delete($id)
    {
        $faqModel = new FaqModel();

        // Supprime la FAQ
        $faqModel->delete($id);

        return redirect()->to('/faq/admin')->with('success', 'Question supprimée avec succès.');
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