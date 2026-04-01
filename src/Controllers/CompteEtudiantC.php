<?php

namespace App\Controllers;

use App\Models\CompteEtudiantM;
use App\Models\CandidatureM;
use App\Models\OffreM;
use function App\Services\pagination;

class CompteEtudiantC
{
    private $modelCompteEtudiant;
    private $templateEngine;
    private $modelCandidature;
    private $modelOffre;

    public function __construct($templateEngine)
    {
        $this->modelCompteEtudiant = new CompteEtudiantM();

        $this->modelCandidature = new CandidatureM();

        $this->modelOffre = new OffreM();

        $this->templateEngine = $templateEngine;
    }

    public function PageCompteEtudiant(): void
    {
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 1) {
            header('Location:CompteConnexion ');
            exit;
        }

        $page2 = isset($_GET['page2']) ? (int)$_GET['page2'] : 1;
        $parpage2 = isset($_GET['parpage2']) ? (int)$_GET['parpage2'] : 12;

        $page1 = isset($_GET['page1']) ? (int)$_GET['page1'] : 1;
        $parpage1 = isset($_GET['parpage1']) ? (int)$_GET['parpage1'] : 12;

        $NomEtGroupe = $this->modelCompteEtudiant->getNometGroupeUser($_SESSION['id']);
        $NomPilote = $this->modelCompteEtudiant->getNomPilote($_SESSION['id']);
        $NbCandidatures = $this->modelCompteEtudiant->getNbCandidatures($_SESSION['id']);
        $InfosOffre = $this->modelOffre->getDetailOffre($_SESSION['id']);

        $Candidatures = $this->modelCandidature->getCandidaturesUtilisateur($_SESSION['id'], $page2, $parpage2);
        $Wishlist = $this->modelOffre->getOffreWishlist($page1, $parpage1, $_SESSION['id']);

        $total1 = $this->modelOffre->getNbOffreWishlist($_SESSION['id']);
        $pagination1 = pagination($total1, $page1, $parpage1, '/CompteEtudiant', $_GET, '1');

        $total2 = $this->modelCandidature->getNbCandidatureUtilisateur($_SESSION['id']);
        $pagination2 = pagination($total2, $page2, $parpage2, '/CompteEtudiant', $_GET, '2');


        echo $this->templateEngine->render('Compte/CompteEtudiant.html.twig', [
            'NomEtGroupe' => $NomEtGroupe,
            'NomPilote' => $NomPilote,
            'NbCandidatures' => $NbCandidatures,
            'candidatures' => $Candidatures,
            'wishlist' => $Wishlist,
            'id_role' => $_SESSION['role'],

            'page2' => $page2,
            'parpage2' => $parpage2,
            'total2' => $total2,

            'pagination2' => $pagination2,

            'page1' => $page1,
            'parpage1' => $parpage1,
            'total1' => $total1,

            'pagination1' => $pagination1
        ]);
    }

}