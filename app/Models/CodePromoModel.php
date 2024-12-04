<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePromoModel extends Model
{
    protected $table = 'codepromo';
    protected $primaryKey = 'id_codepromo';

    // Champs autorisés pour les opérations d'écriture
    protected $allowedFields = [
        'code',
        'valeur',
        'pourcentage',
        'actif',
        'date_debut',
        'date_fin',
        'utilisation_max',
        'utilisation_actuelle',
        'conditions'
    ];

    /**
     * Vérifie si un code promo est valide.
     * 
     * @param string $code
     * @return array|null
     */
    public function verifierCodePromo(string $code)
    {
        $now = date('Y-m-d H:i:s');

        return $this->where('code', $code)
            ->where('actif', true)
            ->where('date_debut <=', $now)
            ->where('date_fin >=', $now)
            ->where('(utilisation_max IS NULL OR utilisation_actuelle < utilisation_max)')
            ->first();
    }

    /**
     * Marque une utilisation du code promo.
     * 
     * @param int $id
     * @return bool
     */
    public function incrementerUtilisation(int $id)
    {
        $codePromo = $this->find($id);

        if ($codePromo && 
            ($codePromo['utilisation_max'] === null || 
             $codePromo['utilisation_actuelle'] < $codePromo['utilisation_max'])) {
            return $this->update($id, [
                'utilisation_actuelle' => $codePromo['utilisation_actuelle'] + 1
            ]);
        }

        return false;
    }
}
