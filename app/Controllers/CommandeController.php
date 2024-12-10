<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CodePromoModel;
use App\Models\CommandeModel;
use App\Models\DetailsCommandeModel;
use App\Models\ProduitModel;
use App\Models\UtilisateurModel;

class CommandeController extends BaseController
{
    public function __construct()
    {
        helper(['form']);
    }

    public function index()
    {
        $commandeModel = new CommandeModel();

        $statuts = $this->request->getGet('statuts');

        if (!empty($statuts)) {
            // Si des statuts sont sélectionnés, les inclure dans la requête
            $commandeModel->select('commande.*, utilisateur.mail')
            ->join('utilisateur', 'utilisateur.id_utilisateur = commande.id_utilisateur')
            ->whereIn('statut', $statuts);
        } else {
            // Sinon, exclure les statuts 'fini' et 'annulé' par défaut
            $commandeModel->select('commande.*, utilisateur.mail')
            ->join('utilisateur', 'utilisateur.id_utilisateur = commande.id_utilisateur')
            ->whereNotIn('statut', ['fini', 'annulé']);
        }

        $commandes = $commandeModel->orderBy('id_commande')->paginate(9);

        // Calculer les statistiques (nombre de commandes dans chaque état)
        $statistiques = [
            'en_attente' => $commandeModel->where('statut', 'en attente')->countAllResults(),
            'expedie' => $commandeModel->where('statut', 'expédié')->countAllResults(),
            'fini' => $commandeModel->where('statut', 'fini')->countAllResults(),
            'annule' => $commandeModel->where('statut', 'annulé')->countAllResults(),
            'total' => $commandeModel->countAllResults(), // Total des commandes
        ];

        // Passer les données à la vue
        $data['commandes'] = $commandes;
        $data['pager'] = \Config\Services::pager();
        $data['statistiques'] = $statistiques;
        $data['statutsSelectionnes'] = $statuts ?? ['en attente']; 
        $data['tousStatuts'] = ['en attente', 'expédié', 'fini', 'annulé']; 

        return view('administrateur/commandes/liste', $data);
    }


    public function modifier($id)
    {
        $commandeModel = new CommandeModel();

        $this->validate([
            'statut' => 'required',
        ]);

        $data = [
            'statut' => $this->request->getPost('statut'),
        ];

        $commandeModel->update($id, $data);

        return redirect()->to('admin/commandes');
    }

    public function annuler($id)
    {
        $session = session();

        $commandeModel = new CommandeModel();

        $commande = $commandeModel->find($id);

        if (!$commande) {
            $session->setFlashdata('error', 'Commande introuvable.');
            return redirect()->to('/commande');
        }

        // Vérifier si la commande appartient à l'utilisateur connecté
        $userId = session()->get('idutilisateur');
        if ($commande['id_utilisateur'] !== $userId) {
            $session->setFlashdata('error', 'Vous ne pouvez pas annuler cette commande.');
            return redirect()->to('/commande');
        }

        $data = [
            'statut' => 'annulé',
        ];

        $commandeModel->update($id, $data);

        $session->setFlashdata('success', 'Commande annulée avec succès.');
        return redirect()->to('/commande');
    }

    public function enregistrerCommande()
    {
        $session = session();
        $panier = $this->getPanier();
        $userId = $session->get('idutilisateur');

        $isValid = $this->validate([
            'nom' => 'required|max_length[50]',
            'adresse' => 'required|max_length[100]',
            'code_postal' => 'required|max_length[100]',
            'ville' => 'required|max_length[100]',
        ]);

        if (!$isValid) {
            return view('recapitulatif', [
                'validation' => \Config\Services::validation(),
            ]);
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'adresse' => $this->request->getPost('adresse'),
            'code_postal' => $this->request->getPost('code_postal'),
            'ville' => $this->request->getPost('ville'),
        ];

        $prixpromo = $this->request->getPost('prix_total') ?: null;
        $idpromo = $this->request->getPost('idpromo') ?: null;

        if (!empty($idpromo)) {
            $codepromoModel = new CodePromoModel();
            $code = $codepromoModel->find($idpromo);
            $util = $code['utilisation_actuelle'] + 1;
            $codepromoModel->update($idpromo, ['utilisation_actuelle' => $util]);
        }

        $utilisateurModel = new UtilisateurModel();
        $utilisateurModel->update($userId, $data);

        $utilisateur = $utilisateurModel->find($userId);

        // Calculer le prix total TTC de la commande
        $prixTotalHT = 0;
        $prixTotalTTC = 0;

        $produitModel = new ProduitModel();
        $produits = [];
        foreach ($panier as $idProduit => $quantite) {
            $produit = $produitModel->find($idProduit);
            if ($produit) {
                $produit['quantite'] = $quantite;
                $produits[] = $produit;
            }
        }

        foreach ($produits as $produit) {
            $prixTotalHT += $produit['prixht'] * $produit['quantite'];
            $prixTotalTTC += $produit['prixttc'] * $produit['quantite'];
        }

        // Envoyer les emails de confirmation
        if (!$this->sendEmail(session()->get('email'), 'client')) {
            $session->setFlashdata('error', 'Erreur lors de l\'envoi de l\'email de confirmation au client.');
            return redirect()->to('/');
        }

        if (!$this->sendEmail('ofc99935@gmail.com', 'admin')) {
            $session->setFlashdata('error', 'Erreur lors de l\'envoi de l\'email de confirmation à l\'administrateur.');
            return redirect()->to('/');
        }

        // Enregistrer la commande
        $commandeModel = new CommandeModel();
        $commandeData = [
            'date_creation' => date('Y-m-d H:i:s'),
            'statut' => 'en attente', // Statut initial
            'prixht' => $prixTotalHT,
            'prixttc' => $prixTotalTTC,
            'prixpromo' => $prixpromo < 0 ? 0 : $prixpromo,
            'id_utilisateur' => $userId,
            'informations' => $utilisateur['adresse'] . ' ' . $utilisateur['code_postal'] . ' ' . $utilisateur['ville'],
        ];

        $commandeId = $commandeModel->insert($commandeData);

        // Enregistrer les détails de la commande
        $detailsCommandeModel = new DetailsCommandeModel();

        foreach ($produits as $produit) {
            $detailsCommandeData = [
                'id_commande' => $commandeId,
                'id_produit' => $produit['id_produit'],
                'quantite' => $produit['quantite'],
            ];

            // Ajouter les détails de commande
            $detailsCommandeModel->insert($detailsCommandeData);
        }

        $response = service('response');
        $response->setCookie('panier', '', time() - 3600);
        $response->setCookie('code_promo', '', time() - 3600);

        $response->send();

        // Rediriger vers la page de confirmation
        session()->setFlashdata('success', 'Votre commande a été validée avec succès après paiement.');
        return redirect()->to('/');
    }

    private function sendEmail($to, $type)
    {
        $email = \Config\Services::email();

        switch ($type) {
            case 'client':
                $subject = 'Confirmation de votre commande';
                $name = 'Confirmation de commande';
                $message = '<p>Bonjour,</p>';
                $message .= '<p>Nous vous confirmons la réception de votre commande. Nous vous remercions pour votre confiance.</p>';
                $message .= '<p>Votre commande sera traitée dans les plus brefs délais.</p>';
                $message .= '<p>Cordialement,</p>';
                $message .= '<p>L\'équipe OFC</p>';
                break;

            case 'admin':
                $subject = 'Nouvelle commande passée';
                $name = 'Commande Client';
                $message = '<p>Bonjour,</p>';
                $message .= '<p>Une nouvelle commande a été passée par un client.</p>';
                $message .= '<p>Veuillez vérifier les détails de la commande dans le système d\'administration.</p>';
                $message .= '<p>Cordialement,</p>';
                $message .= '<p>L\'équipe OFC</p>';
                break;

            default:
                return;
        }

        $email->setTo($to);
        $email->setFrom('no-reply@ofc-naturel.fr', $name);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->setMailType('html');

        // Ajoutez cette partie pour récupérer les erreurs d'envoi
        if (!$email->send()) {
            log_message('error', 'Échec de l\'envoi de l\'e-mail : ' . $email->printDebugger(['headers']));
            return false;
        }
        return true;
    }

    private function getPanier(): array
    {
        $request = service('request');
        $cookie = $request->getCookie('panier');

        if (!$cookie) {
            return [];
        }

        $panier = json_decode($cookie, true);
        return is_array($panier) ? $panier : [];
    }

    public function mescommandes()
    {
        $commandeModel = new CommandeModel();

        $statuts = $this->request->getGet('statuts');

        $commandeModel->where('id_utilisateur', session()->get('idutilisateur'));

        if (!empty($statuts)) {
            // Si des statuts sont sélectionnés, les inclure dans la requête
            $commandeModel->whereIn('statut', $statuts);
        } else {
            // Sinon, exclure les statuts 'fini' et 'annulé' par défaut
            $commandeModel->whereNotIn('statut', ['fini', 'annulé']);
        }

        $commandes = $commandeModel->orderBy('id_commande')->paginate(9);

        $data['commandes'] = $commandes;
        $data['pager'] = \Config\Services::pager();
        $data['statutsSelectionnes'] = $statuts ?? ['en attente', 'expédié']; 
        $data['tousStatuts'] = ['en attente', 'expédié', 'fini']; 

        return view('/commande', $data);
    }

    public function afficherProduitsCommande($idCommande, $admin = false)
    {
        $commandeModel = new CommandeModel();
        $produitModel = new ProduitModel();

        // Récupère la commande et les produits associés
        $commande = $commandeModel->find($idCommande);
        $produits = $produitModel
            ->select('produit.*, details_commande.*')
            ->join('details_commande', 'produit.id_produit = details_commande.id_produit')
            ->where('details_commande.id_commande', $idCommande)
            ->findAll();

        // Passer les données à la vue
        $data['commande'] = $commande;
        $data['produits'] = $produits;

        if ($admin) {
            return view('administrateur/commandes/detail', $data);
        }

        return view('detailcommande', $data);
    }

}
