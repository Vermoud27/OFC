<?php

namespace App\Controllers;

use App\Models\RatingModel;
use App\Models\CommentModel;

class RatingController extends BaseController
{

    public function submitRating()
    {
        $idProduit = $this->request->getPost('idProduit');
        $idUtilisateur = $this->request->getPost('id_utilisateur');
        $rating = $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');
        
        $ratingModel = new RatingModel();
        $commentModel = new CommentModel();
        
        // Vérifier si un avis existe déjà pour cet utilisateur et cet article
        $existingRating = $ratingModel->where('id_produit', $idProduit)
                                      ->where('id_utilisateur', $idUtilisateur)
                                      ->first();
        
        // Données à sauvegarder pour la note
        $data = [
            'id_produit' => $idProduit,
            'id_utilisateur' => $idUtilisateur,
            'valeur' => $rating,
        ];
    
        // Données à sauvegarder pour le commentaire
        $dataComm = [
            'id_produit' => $idProduit,
            'id_utilisateur' => $idUtilisateur,
            'contenu' => $comment,
        ];
    
        // Si un avis existe déjà, on met à jour la note
        if ($existingRating) {
            $ratingModel->update($existingRating['id_rating'], $data);
            // Envoi de flashdata pour la note
            session()->setFlashdata('success_rating', 'Votre note a été mise à jour.');
        } else {
            // Sinon, on crée un nouvel avis pour la note
            $ratingModel->save($data);
            // Envoi de flashdata pour la note
            session()->setFlashdata('success_rating', 'Votre note a été soumise.');
        }
    
        // Si un commentaire est soumis, on le sauvegarde
        if (!empty($comment)) {
            $commentModel->save($dataComm);
            // Envoi de flashdata pour le commentaire
            session()->setFlashdata('success_comment', 'Votre commentaire a été soumis avec succès.');
        }
    
        // Rediriger vers la page du produit après la soumission
        return redirect()->to('/produit/' . $idProduit);
    }    

    public function getRatings($productId)
    {
        $ratingModel = new RatingModel(); // Assurez-vous que ce modèle existe

        // Récupérer les avis associés à l'ID du produit
        $ratings = $ratingModel->where('product_id', $productId)->findAll();

        if ($ratings) {
            return $this->response->setJSON(['success' => true, 'ratings' => $ratings]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Aucun avis trouvé.']);
        }
    }
}
?>