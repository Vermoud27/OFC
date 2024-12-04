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

    public function logout()
    {
        // Supprime toutes les données de session
        session()->destroy();

        // Redirige vers la page d'accueil ou la page désirée
        return redirect()->to('/');
    }
}
