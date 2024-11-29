<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProduitModel;

class ProduitController extends BaseController
{
    protected $produitModel;

    public function __construct()
    {
        $this->produitModel = new ProduitModel();
		helper(['form']);
    }

    public function index()
    {
        $data['produits'] = $this->produitModel->findAll();
        return view('administrateur/produits/index', $data);
    }
	
    public function creer()
    {
		$this->produitModel->save([
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
			'prixht' => $this->request->getPost('prixht'),
			'prixttc' => $this->request->getPost('prixttc'),
			'qte_stock' => $this->request->getPost('qte_stock'),
			'unite_mesure' => $this->request->getPost('unite_mesure'),
		]);

		return redirect()->to('admin/produits');
    }

    public function modifier()
    {
		$id = $this->request->getPost(index: 'id_produit');
		
		$this->produitModel->update($id, [
			'nom' => $this->request->getPost('nom'),
			'description' => $this->request->getPost('description'),
			'prixht' => $this->request->getPost('prixht'),
			'prixttc' => $this->request->getPost('prixttc'),
			'qte_stock' => $this->request->getPost('qte_stock'),
			'unite_mesure' => $this->request->getPost('unite_mesure'),
			'promotion' => $this->request->getPost('promotion'),
			'id_categorie' => $this->request->getPost('id_categorie'),
			'id_gamme' => $this->request->getPost('id_gamme')
		]);
		return redirect()->to('admin/produits');
    }

    public function supprimer($id)
    {
        $this->produitModel->delete($id);
        return redirect()->to('admin/produits');
    }
}
