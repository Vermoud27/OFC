<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class FooterController extends Controller
{
    /**
     * Affiche la page des mentions légales.
     */
    public function mentionlegales()
    {
        return view('pageFooter/mentionLegale');
    }

    /**
     * Affiche la page de la politique de confidentialité.
     */
    public function polconf()
    {
        return view('pageFooter/polConf');
    }

    /**
     * Affiche la page de la politique de remboursement.
     */
    public function polremb()
    {
        return view('pageFooter/polRemb');
    }

    /**
     * Affiche la page de la politique de cookies.
     */
    public function polcook()
    {
        return view('pageFooter/polCook');
    }

    /**
     * Affiche la page RGPD.
     */
    public function RGPD()
    {
        return view('pageFooter/rgpd');
    }

    /**
     * Affiche la page des conditions d'utilisation.
     */
    public function condutil()
    {
        return view('pageFooter/condutil');
    }

    /**
     * Affiche la page des conditions de vente.
     */
    public function condvente()
    {
        return view('pageFooter/condVente');
    }
}
