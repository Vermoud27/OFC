<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;

class ProfileController extends BaseController
{
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/signin')->with('error', 'Vous devez être connecté pour accéder à votre profil.');
        }

        // Charger le modèle des utilisateurs
        $utilisateurModel = new UtilisateurModel();

        // Récupérer l'email de l'utilisateur depuis la session
        $email = session()->get('email');

        // Rechercher l'utilisateur dans la base de données
        $utilisateur = $utilisateurModel->where('mail', $email)->first();

        // Si l'utilisateur n'est pas trouvé, rediriger avec un message d'erreur
        if (!$utilisateur) {
            return redirect()->to('/signin')->with('error', 'Utilisateur non trouvé.');
        }

        // Charger la vue avec les données utilisateur
        return view('profile', ['utilisateur' => $utilisateur]);
    }

    public function update()
    {
        helper(['form']);

        // Règles de validation
        $rules = [
            'nom' => 'required',
            'prenom' => 'required',
            'telephone' => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'adresse' => 'permit_empty',
            'code_postal' => 'permit_empty',
            'ville' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'telephone' => $this->request->getPost('telephone'),
            'adresse' => $this->request->getPost('adresse'),
            'code_postal' => $this->request->getPost('code_postal'),
            'ville' => $this->request->getPost('ville'),
        ];

        $model = new UtilisateurModel();
        $userId = session()->get('idutilisateur');

        $test = $model->update($userId, $data);

        if ($test) {
            return redirect()->to('/profile')->with('success', 'Vos informations ont été mises à jour avec succès.');
        } else {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    public function edit()
    {
        $model = new UtilisateurModel();
        $userId = session()->get('idutilisateur');

        $utilisateur = $model->find($userId);

        if (!$utilisateur) {
            return redirect()->to('/profile')->with('error', 'Utilisateur introuvable.');
        }

        return view('edit_profile', ['utilisateur' => $utilisateur]);
    }

    public function logout()
    {
        // Supprime toutes les données de session
        session()->destroy();

        // Redirige vers la page d'accueil ou la page désirée
        return redirect()->to('/');
    }
}
