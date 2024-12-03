<?php

namespace App\Controllers;

use App\Models\FAQModel;

class ControllerOFC extends BaseController
{
    public function index(): string
    {
        //return view('pageAccueil');

        $faqModel = new FaqModel(); // Instanciez le modèle

        // Récupérez les 10 premières questions
        $data['faqs'] = $faqModel->orderBy('id_faq', 'ASC')->findAll(10);

        // Chargez la vue avec les questions
        return view('pageAccueil', $data);
    }
}

?>  