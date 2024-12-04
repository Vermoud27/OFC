<?php
namespace App\Controllers;

use App\Models\UtilisateurModel;
use CodeIgniter\I18n\Time;

class SignupController extends BaseController
{
    public function index()
    {
        helper(['form']);
        return view('login/signup');
    }

    public function enregistrer()
    {
        helper(['form']);
    
        // Règles de validation avec messages personnalisés
        $rules = [
            'email' => [
                'rules' => 'required|valid_email|is_unique[utilisateur.mail]',
                'errors' => [
                    'required' => 'L\'adresse email est obligatoire.',
                    'valid_email' => 'Veuillez entrer une adresse email valide.',
                    'is_unique' => 'Cette adresse email est déjà utilisée. Veuillez en choisir une autre.'
                ],
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Le mot de passe est obligatoire.',
                    'min_length' => 'Le mot de passe doit contenir au moins 6 caractères.'
                ],
            ],
            'password_confirmation' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'La confirmation du mot de passe est obligatoire.',
                    'matches' => 'Les mots de passe ne correspondent pas.'
                ],
            ],
            'first_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Le prénom est obligatoire.',
                ],
            ],
            'last_name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Le nom de famille est obligatoire.',
                ],
            ],
            'phone' => [
                'rules' => 'permit_empty|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'numeric' => 'Le numéro de téléphone doit être composé uniquement de chiffres.',
                    'min_length' => 'Le numéro de téléphone doit comporter au moins 10 chiffres.',
                    'max_length' => 'Le numéro de téléphone ne doit pas dépasser 15 chiffres.'
                ],
            ],
        ];
    
        if ($this->validate($rules)) {
            $model = new UtilisateurModel();
    
            // Générer un jeton unique
            $token = bin2hex(random_bytes(16));
    
            // Enregistrement temporaire de l'utilisateur
            $model->save([
                'mail' => $this->request->getPost('email'),
                'mdp' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'nom' => $this->request->getPost('first_name'),
                'prenom' => $this->request->getPost('last_name'),
                'role' => 'Client',
                'telephone' => $this->request->getPost('phone') ?? null,
                'reset_token' => $token,
                'reset_token_expiration' => Time::now()->addHours(24),
            ]);
    
            // Envoi de l'e-mail de validation
            $this->sendValidationEmail($this->request->getPost('email'), $token);
    
            session()->setFlashdata('success', 'Un e-mail de validation vous a été envoyé. Veuillez vérifier votre boîte de réception.');
            return redirect()->to('signin');
        } else {
            // Gestion des erreurs avec flashdata
            $errors = $this->validator->getErrors();
            foreach ($errors as $field => $message) {
                session()->setFlashdata('error_' . $field, $message);
            }
    
            // Redirection vers la vue d'inscription avec les messages d'erreur
            return redirect()->back()->withInput();
        }
    }
    

    private function sendValidationEmail($email, $token)
    {
        // Initialiser le service d'email
        $emailService = \Config\Services::email();

        // URL de validation avec le jeton
        $validationLink = site_url("signup/activateAccount/$token");

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
        $user = $model->where('reset_token', $token)
            ->where('reset_token_expiration >', Time::now())
            ->first();

        if ($user) {
            // Activer l'utilisateur et supprimer le jeton
            $model->update($user['id_utilisateur'], [
                'is_active' => TRUE, 
                'reset_token' => null,
                'reset_token_expiration' => null,
            ]);
            

            return redirect()->to('/signin')->with('success', 'Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.');
        } else {
            return redirect()->to('/signup')->with('error', 'Lien de validation invalide ou expiré.');
        }
    }
}
