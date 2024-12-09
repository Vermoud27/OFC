<?php

namespace App\Controllers;

use App\Models\CommentModel;

class CommentaireController extends BaseController
{
    public function supprimer($id_commentaire)
    {
        if (session()->get('role') !== 'Admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Action non autorisée']);
        }
    
        $commentModel = new CommentModel();
        $deleted = $commentModel->delete($id_commentaire);
    
        if ($deleted) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
        
}
