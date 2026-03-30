<?php

namespace App\Controllers;

use App\Models\CompteAdminM;
use App\Models\CandidatureM;
use App\Models\OffreM;

class CompteAdminC
{
    private $modelCompteAdmin;
    private $templateEngine;
    private $modelCandidature;
    private $modelOffre;

    public function __construct($templateEngine)
    {
        $this->modelCompteAdmin = new CompteAdminM();

        $this->modelCandidature = new CandidatureM();

        $this->modelOffre = new OffreM();

        $this->templateEngine = $templateEngine;
    }

    public function PageCompteAdmin(): void
    {
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 1) {
            header('Location:CompteConnexion ');
            exit;
        }


        echo $this->templateEngine->render('/Compte/CompteAdmin.html.twig', [

        ]);
    }

}