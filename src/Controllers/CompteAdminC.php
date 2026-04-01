<?php

namespace App\Controllers;

use App\Models\CompteAdminM;
use App\Models\CompteEtudiantM;
use App\Models\OffreM;
use App\Models\EntrepriseM;

class CompteAdminC
{
    private $modelCompteAdmin;
    private $templateEngine;
    private $modelCompteEtudiant;
    private $modelOffre;
    private $modelEntreprise;

    public function __construct($templateEngine)
    {
        $this->modelCompteAdmin = new CompteAdminM();

        $this->modelOffre = new OffreM();

        $this->modelCompteEtudiant = new CompteEtudiantM();

        $this->modelEntreprise = new EntrepriseM();

        $this->templateEngine = $templateEngine;
    }

    public function PageCompteAdmin(): void
    {
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 1) {
            header('Location:CompteConnexion ');
            exit;
        }

        $NbEtudiants = $this->modelCompteEtudiant->getNbEtudiant();
        $NbEntreprises = $this->modelCompteAdmin->getNbEntreprises();
        $NbPilotes = $this->modelCompteAdmin->getNbPilotes();
        $NomComplet = $this->modelCompteEtudiant->getNometGroupeUser($_SESSION['id']);
        $InfosOffre = $this->modelCompteAdmin->getOffresEnAttente();
        $InfosEntreprise = $this->modelCompteAdmin->getEntreprisesEnAttente();
        $PiloteInfo = $this->modelCompteAdmin->getPiloteInfo();

        echo $this->templateEngine->render('Compte/CompteAdmin.html.twig', [
            'NbEtudiants' => $NbEtudiants,
            'NbEntreprises' => $NbEntreprises,
            'NbPilotes' => $NbPilotes,
            'NomComplet' => $NomComplet,
            'InfosOffre'=> $InfosOffre,
            'InfosEntreprise'=> $InfosEntreprise,
            'PiloteInfo' => $PiloteInfo,
        ]);
    }

    public function AcceptOffre($id)
    {
        $this->modelCompteAdmin->AcceptOffre((int)$id);

        header('Location: /CompteAdmin');
        exit;
    }

    public function DeleteOffre($id)
    {
        $this->modelOffre->deleteOffre((int)$id);

        header('Location: /CompteAdmin');
        exit;
    }

    public function AcceptEntreprise($id)
    {
        $this->modelCompteAdmin->AcceptEntreprise((int)$id);

        header('Location: /CompteAdmin');
        exit;
    }

    public function DeleteEntreprise($id)
    {
        $this->modelEntreprise->deleteEntreprise((int)$id);

        header('Location: /CompteAdmin');
        exit;
    }

    public function AcceptPilote($id)
    {
        $this->modelCompteAdmin->AcceptPilote((int)$id);

        header('Location: /CompteAdmin');
        exit;
    }

    public function DeletePilote($id)
    {
        $this->modelCompteAdmin->deletePilote((int)$id);

        header('Location: /CompteAdmin');
        exit;
    }

}