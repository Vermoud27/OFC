<?php

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'id_rating';
    protected $allowedFields = ['id_utilisateur', 'id_produit', 'valeur', 'date_rating'];

    public function getAverageRating($idProduit)
    {
        return $this->where('id_produit', $idProduit)
                    ->selectAvg('valeur', 'average')
                    ->get()
                    ->getRow()
                    ->average ?? 0;
    }

    public function getTotalRatings($idProduit)
    {
        return $this->where('id_produit', $idProduit)->countAllResults();
    }
}
