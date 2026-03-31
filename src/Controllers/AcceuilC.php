<?php

namespace App\Controllers;

use App\Models\CompteEtudiantM;
use App\Models\OffreM;
use App\Models\EntrepriseM;

class AcceuilC 
{
    private $modelCompteEtudiant;
    private $modelOffre;
    private $modelEntreprise;
    private $templateEngine;

    public function __construct($templateEngine)
    {
        $this->modelCompteEtudiant = new CompteEtudiantM();
        $this->modelOffre = new OffreM();
        $this->modelEntreprise = new EntrepriseM();

        $this->templateEngine = $templateEngine;
    }

    public function PageAcceuil()
    {
        $NbOffres = $this->modelOffre->getNbOffre();

        echo $this->templateEngine->render('Acceuil/Acceuil.html.twig', [
            'NbOffres' => $NbOffres
        ]);
    }
}

