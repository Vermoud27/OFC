<?php
namespace App\Controllers;

use App\Models\UtilisateurModel;
use CodeIgniter\I18n\Time;

class SignupController extends BaseController
{
    public function index()
    {
        return view('login/signup');
    }

    public function enregistrer()
    {
        helper(['form']);

        // Règles de validation
        $rules = [
            'email' => 'required|valid_email|is_unique[utilisateurs.email]',
            'password' => 'required|min_length[6]',
            'confirmpassword' => 'required|matches[password]',
        ];

        if ($this->validate($rules)) {
            $model = new UtilisateurModel();

            // Générer un jeton unique
            $token = bin2hex(random_bytes(16));

            // Enregistrement temporaire de l'utilisateur
            $model->save([
                'email' => $this->request->getPost('email'),
                'mot_de_passe' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'token_modif_mdp' => $token,
                'reset_token_expiration' => Time::now()->addHours(24),
            ]);

            // Envoi de l'e-mail de validation
            $this->sendValidationEmail($this->request->getPost('email'), $token);

            return redirect()->to('login/signin')->with('success', 'Un e-mail de validation vous a été envoyé. Veuillez vérifier votre boîte de réception.');
        } else {
            return view('login/signup', [
                'validation' => $this->validator
            ]);
        }
    }

    private function sendValidationEmail($email, $token)
    {
        // Initialiser le service d'email
        $emailService = \Config\Services::email();

        // URL de validation avec le jeton
        $validationLink = site_url("login/signup/activateAccount/$token");

        // Message d'e-mail au format HTML
        $message = "
            <html>
            <head>
                <title>Validez votre inscription</title>
            </head>
            <body>
                <p>Bonjour,</p>
                <p>Merci de vous être inscrit sur notre site. Pour valider votre compte, veuillez cliquer sur le lien suivant :</p>
                <p><a href='$validationLink'>Valider mon inscription</a></p>
                <p>Ce lien expirera dans 24 heures.</p>
                <p>Si vous n'avez pas demandé cette inscription, veuillez ignorer cet e-mail.</p>
            </body>
            </html>
        ";

        // Configurer l'email
        $emailService->setTo($email);
        $emailService->setFrom('no-reply@votre-site.com', 'Validation d\'inscription');
        $emailService->setSubject('Validez votre inscription');
        $emailService->setMessage($message);
        $emailService->setMailType('html'); // Indiquer que le contenu est au format HTML

        // Envoyer l'email et vérifier l'envoi
        if (!$emailService->send()) {
            // Enregistrer les erreurs si l'envoi échoue
            log_message('error', $emailService->printDebugger(['headers']));
        }
    }

    public function activateAccount($token)
    {
        $model = new UtilisateurModel();

        // Rechercher l'utilisateur par son jeton
        $user = $model->where('token_modif_mdp', $token)
            ->where('reset_token_expiration >', Time::now())
            ->first();

        if ($user) {
            // Activer l'utilisateur et supprimer le jeton
            $model->update($user['idutilisateur'], [
                'is_active' => TRUE, // Marquer le compte comme activé
                'token_modif_mdp' => null,
                'reset_token_expiration' => null,
            ]);
            

            return redirect()->to('login/signin')->with('success', 'Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.');
        } else {
            return redirect()->to('login/signup')->with('error', 'Lien de validation invalide ou expiré.');
        }
    }
}
