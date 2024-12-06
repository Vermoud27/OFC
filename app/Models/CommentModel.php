<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'commentaire';
    protected $primaryKey = 'id_commentaire';
    protected $allowedFields = ['id_utilisateur', 'id_produit', 'contenu', 'date_commentaire'];

    public function getCommentsByProductId($id_produit, $all = false)
    {
        $builder = $this->db->table($this->table)
            ->select('commentaire.*, utilisateur.nom, utilisateur.prenom')
            ->join('utilisateur', 'utilisateur.id_utilisateur = commentaire.id_utilisateur')
            ->where('commentaire.id_produit', $id_produit)
            ->orderBy('date_commentaire', 'DESC');

        if (!$all) {
            $builder->limit(5);
        }

        return $builder->get()->getResultArray();
    }

}
