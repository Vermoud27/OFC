<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UtilisateurModel;

class SigninController extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/ControllerOFC');
        }
    
        return view('login/signin');
    }
    
    public function loginAuth()
    {
        $session = session();
        $model = new UtilisateurModel();
    
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        // Rechercher l'utilisateur par email
        $user = $model->where('mail', $email)->first();
    
        if ($user) {
            if (password_verify($password, $user['mdp'])) {
                if ($user['is_active'] == 'f') {
                    $session->setFlashdata('error', 'Votre compte n\'est pas encore activé. Veuillez vérifier vos e-mails.');
                    return redirect()->to('/signin');
                }
    
                // Stocker les informations utilisateur dans la session
                $session->set([
                    'idutilisateur' => $user['id_utilisateur'],
                    'email' => $user['mail'],
                    'isLoggedIn' => true,
                    'role' => $user['role'],
                ]);
    
                return redirect()->to('/ControllerOFC'); // Page d'accueil
            } else {
                $session->setFlashdata('error', 'Mot de passe incorrect.');
                return redirect()->to('/signin');
            }
        } else {
            $session->setFlashdata('error', 'Adresse e-mail introuvable.');
            return redirect()->to('/signin');
        }
    }
    
    
    public function logout()
    {
        $session = session();
        $session->destroy();
    
        return redirect()->to('/signin')->with('success', 'Vous avez été déconnecté avec succès.');
    }
    
}
