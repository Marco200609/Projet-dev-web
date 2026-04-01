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


//    public function PageAcceuil()
//    {Affiche le nombre d'offres, d'entreprises et de juniors recrutés Grace à des méthodes de chaque modele correspondant à la statistique voulue
    public function PageAcceuil()
    {
        $NbOffres = $this->modelOffre->getNbOffre();
        $NbEntreprises = $this->modelEntreprise->getNbEntreprises();
        $NbJuniors = $this->modelCompteEtudiant->getNbEtudiant();

        return $this->templateEngine->render('Acceuil/Acceuil.html.twig', [
            'NbOffres' => $NbOffres,
            'NbEntreprises' => $NbEntreprises,
            'NbJuniors' => $NbJuniors
        ]);
    }

    public function PageMentionsLegales()
    {
        return $this->templateEngine->render('Acceuil/MentionsLegales.html.twig');
    }
}

