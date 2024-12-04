<?php

namespace App\Controllers;

use App\Models\FAQModel;

class ProduitsController extends BaseController
{
    public function index(): string
    {
        return view('pageProduits');
    }
}

?>  