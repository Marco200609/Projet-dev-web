<?php

namespace App\Controllers;

use App\Models\ComptePiloteM;
use App\Models\CompteEtudiantM;
use App\Models\CandidatureM;
use App\Models\OffreM;

class ComptePiloteC
{
    private $modelComptePilote;
    private $templateEngine;
    private $modelCandidature;
    private $modelOffre;
    private $modelCompteEtudiant;

    public function __construct($templateEngine)
    {
        $this->modelComptePilote = new ComptePiloteM();

        $this->modelCompteEtudiant = new CompteEtudiantM();

        $this->modelCandidature = new CandidatureM();

        $this->modelOffre = new OffreM();

        $this->templateEngine = $templateEngine;
    }

    public function PageComptePilote(): void
    {
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 1) {
            header('Location:CompteConnexion ');
            exit;
        }

        $InfosPilote = $this->modelCompteEtudiant->getNometGroupeUser($_SESSION['id']);
        $NbEtudiant = $this->modelComptePilote->getNbEtudiantsDansGroupesPilote($_SESSION['id']);
        $NbGroupes = $this->modelComptePilote->getNbGroupesPilote($_SESSION['id']);
        $Groupes = $this->modelComptePilote->getGroupesPilote($_SESSION['id']);
        $InfosCandidatures = $this->modelComptePilote->getCandidaturesEtudiantsPilote($_SESSION['id']);

        echo $this->templateEngine->render('Compte/ComptePilote.html.twig', [
            'InfosPilote' => $InfosPilote,
            'NbEtudiant' => $NbEtudiant,
            'NbGroupes' => $NbGroupes,
            'Groupes' => $Groupes,
            'Candidatures' => $InfosCandidatures
        ]);


    }
}