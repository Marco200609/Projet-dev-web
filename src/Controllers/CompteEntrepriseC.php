<?php

namespace App\Controllers;

use App\Models\CandidatureM;
use App\Models\CompteEntrepriseM;
use App\Models\ComptePiloteM;
use App\Models\EntrepriseM;
use App\Models\OffreM;

class CompteEntrepriseC
{
    private $modelComptePilote;
    private $templateEngine;
    private $modelCandidature;
    private $modelOffre;

    public function __construct($templateEngine)
    {
        $this->modelComptePilote = new ComptePiloteM();

        $this->modelCandidature = new CandidatureM();

        $this->modelOffre = new OffreM();

        $this->templateEngine = $templateEngine;
    }

    public function PageCompteEntreprise(): void
    {
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 1) {
            header('Location:CompteConnexion ');
            exit;
        }


        echo $this->templateEngine->render('Compte/CompteEntreprise.html.twig', [

        ]);
    }


}