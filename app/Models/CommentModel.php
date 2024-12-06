<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'commentaire';
    protected $primaryKey = 'id_commentaire';
    protected $allowedFields = ['id_utilisateur', 'id_produit', 'contenu', 'date_commentaire'];

    public function getCommentsByProductId($id_produit)
    {
        return $this->where('id_produit', $id_produit)
                    ->orderBy('date_commentaire', 'DESC')
                    ->findAll();
    }
}
