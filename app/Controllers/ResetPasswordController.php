<?php
namespace App\Controllers;
use App\Models\UtilisateursModel;
use CodeIgniter\Controller;
class ResetPasswordController extends Controller
{
    public function index($token)
    {
        helper(['form']);
        $userModel = new UtilisateursModel();
        $user = $userModel->where('token_modif_mdp', $token)
            ->where('reset_token_expiration >', date('Y-m-d H:i:s'))
            ->first();
        if ($user) {
            return view('reset_password', ['token' => $token]);
        } else {
            return 'Lien de réinitialisation non valide.';
        }
    }
    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');
    
        $userModel = new UtilisateursModel();
        $user = $userModel->where('token_modif_mdp', $token)
                          ->where('reset_token_expiration >', date('Y-m-d H:i:s'))
                          ->first();
    
        if ($user && $password === $confirmPassword) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Mettre à jour le mot de passe et réactiver le compte
            $userModel->update($user['idutilisateur'], [
                'mot_de_passe' => $hashedPassword,
                'token_modif_mdp' => null,
                'reset_token_expiration' => null,
                'is_active' => TRUE,
            ]);
    
            session()->setFlashdata('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return redirect()->to('/signin');
        } else {
            session()->setFlashdata('error', 'Erreur lors de la réinitialisation du mot de passe.');
            return redirect()->to('/reset-password/' . $token);
        }
    }    
}