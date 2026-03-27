<?php

namespace App\Controllers;

use App\Models\CompteEtudiantM;
use App\Models\CandidatureM;

class CompteEtudiantC
{
    private $modelCompteEtudiant;
    private $templateEngine;
    private $modelCandidature;

    public function __construct($templateEngine)
    {
        $this->modelCompteEtudiant = new CompteEtudiantM();

        $this->modelCandidature = new CandidatureM();

        $this->templateEngine = $templateEngine;
    }

    public function PageCompteEtudiant(): void
    {
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 1) {
            header('Location:CompteConnexion ');
            exit;
        }

        $NomEtGroupe = $this->modelCompteEtudiant->getNometGroupeUser($_SESSION['id']);
        $NomPilote = $this->modelCompteEtudiant->getNomPilote($_SESSION['id']);

        echo $this->templateEngine->render('CompteEtudiant.html.twig', [
            'NomEtGroupe' => $NomEtGroupe,
            'NomPilote' => $NomPilote,

        ]);
    }

}