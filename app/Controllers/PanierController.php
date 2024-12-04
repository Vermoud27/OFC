<?php

namespace App\Controllers;

use App\Models\FAQModel;

class PanierController extends BaseController
{
    public function index(): string
    {
        return view('panier');
    }
}

?>  