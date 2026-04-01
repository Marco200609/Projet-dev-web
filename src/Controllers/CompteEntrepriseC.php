<?php

namespace App\Controllers;

use App\Models\CandidatureM;
use App\Models\CompteEntrepriseM;
use App\Models\ComptePiloteM;
use App\Models\EntrepriseM;
use App\Models\OffreM;

class CompteEntrepriseC
{
    private $modelCompteEntreprise;
    private $templateEngine;
    private $modelCandidature;
    private $modelOffre;

    public function __construct($templateEngine)
    {
        $this->modelCompteEntreprise = new CompteEntrepriseM();

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

        $InfosEntreprise = $this->modelCompteEntreprise->getInfosEntreprise($_SESSION['id']);
        $id_entreprise = $this->modelCompteEntreprise->getUtilisateurEntreprise($_SESSION['id']);
        $NbOffres = $this->modelCompteEntreprise->getNbOffres($id_entreprise);
        $OffresActives = $this->modelCompteEntreprise->getOffresEnCours($id_entreprise);
        $OffresEnPause = $this->modelCompteEntreprise->getOffresEnPause($id_entreprise);

        echo $this->templateEngine->render('Compte/CompteEntreprise.html.twig', [
            'InfosEntreprise' => $InfosEntreprise,
            'NbOffres' => $NbOffres,
            'OffresActives' => $OffresActives,
            'OffresEnPause' => $OffresEnPause,

        ]);
    }


}