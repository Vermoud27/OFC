<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UtilisateurModel;
class ForgotPasswordController extends Controller
{
    public function index()
    {
        helper(['form']);
        return view('login/forgot_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $userModel = new UtilisateurModel();
        $user = $userModel->where('email', $email)->first();
    
        if ($user) {
            $token = bin2hex(random_bytes(16));
            $expiration = date('Y-m-d H:i:s', strtotime('+3 hours'));
    
            // Mise à jour du compte avec le token et désactivation
            $userModel->update($user['idutilisateur'], [
                'token_modif_mdp' => $token,
                'reset_token_expiration' => $expiration,
                'is_active' => FALSE,
            ]);
    
            // Envoyer l'e-mail
            $resetLink = site_url("reset-password/$token");
            $message = "Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='$resetLink'>$resetLink</a>";
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setFrom('lucaslanglois76.ll@gmail.com', 'Réinitialisation de mot de passe');
            $emailService->setSubject('Réinitialisation de mot de passe');
            $emailService->setMessage($message);
            $emailService->setMailType('html');
            if ($emailService->send()) {
                session()->setFlashdata('success', 'Un lien de réinitialisation a été envoyé à votre adresse e-mail.');
            } else {
                session()->setFlashdata('error', 'Erreur lors de l\'envoi de l\'e-mail.');
            }
        } else {
            session()->setFlashdata('error', 'Adresse e-mail non trouvée.');
        }
    
        return redirect()->to('login/signin');
    }
    
}
