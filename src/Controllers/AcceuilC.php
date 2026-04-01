<?php

namespace App\Controllers;

use App\Models\CompteEtudiantM;
use App\Models\OffreM;
use App\Models\EntrepriseM;
use App\Models\CompetenceM;
use App\Models\ContratM;

class AcceuilC 
{
    private $modelCompteEtudiant;
    private $modelOffre;
    private $modelEntreprise;
    private $templateEngine;
    private $modelCompetence;
    private $modelContrat;
    

    public function __construct($templateEngine)
    {
        $this->modelCompteEtudiant = new CompteEtudiantM();
        $this->modelOffre = new OffreM();
        $this->modelEntreprise = new EntrepriseM();
        $this->modelCompetence = new CompetenceM();
        $this->modelContrat = new ContratM();

        $this->templateEngine = $templateEngine;
    }
    
//    public function PageAcceuil()
//    {Affiche le nombre d'offres, d'entreprises et de juniors recrutés Grace à des méthodes de chaque modele correspondant à la statistique voulue
    public function PageAcceuil()
    {
        $NbOffres = $this->modelOffre->getNbOffre();
        $NbEntreprises = $this->modelEntreprise->getNbEntreprises();
        $NbJuniors = $this->modelCompteEtudiant->getNbEtudiant();
        
        $liste_competences = $this->modelCompetence->getListeCompetences();
        $liste_contrats = $this->modelContrat->getListeContrat();
        $liste_domaines = $this->modelOffre->getDomaine();

        return $this->templateEngine->render('Acceuil/Acceuil.html.twig', [
            'NbOffres' => $NbOffres,
            'NbEntreprises' => $NbEntreprises,
            'NbJuniors' => $NbJuniors,

            'liste_competences' => $liste_competences,
            'liste_contrats' => $liste_contrats,
            'liste_domaines' => $liste_domaines
            ]);
    }

    public function PageMentionsLegales()
    {
        return $this->templateEngine->render('Acceuil/MentionsLegales.html.twig');
    }
}

