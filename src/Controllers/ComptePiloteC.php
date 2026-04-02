<?php

namespace App\Controllers;

use App\Models\ComptePiloteM;
use App\Models\CompteEtudiantM;
use App\Models\CandidatureM;
use App\Models\OffreM;
use function App\Services\pagination;

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
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 3) {
            header('Location:CompteConnexion ');
            exit;
        }

        $page2 = isset($_GET['page2']) ? (int)$_GET['page2'] : 1;
        $parpage2 = isset($_GET['parpage2']) ? (int)$_GET['parpage2'] : 12;

        $page1 = isset($_GET['page1']) ? (int)$_GET['page1'] : 1;
        $parpage1 = isset($_GET['parpage1']) ? (int)$_GET['parpage1'] : 12;

        $InfosPilote = $this->modelCompteEtudiant->getNometGroupeUser($_SESSION['id']);
        $NbEtudiant = $this->modelComptePilote->getNbEtudiantsDansGroupesPilote($_SESSION['id']);

        $Groupes = $this->modelComptePilote->getGroupesPilote($page1, $parpage1, $_SESSION['id']);
        $InfosCandidatures = $this->modelComptePilote->getCandidaturesEtudiantsPilote($page2, $parpage2, $_SESSION['id']);

        $NbGroupes = $this->modelComptePilote->getNbGroupesPilote($_SESSION['id']);
        $pagination1 = pagination($NbGroupes, $page1, $parpage1, '/ComptePilote', $_GET, '1');

        $total2 = $this->modelComptePilote->getNbCandidaturesEtudiantsPilote($_SESSION['id']);
        $pagination2 = pagination($total2, $page2, $parpage2, '/ComptePilote', $_GET, '2');

        echo $this->templateEngine->render('Compte/ComptePilote.html.twig', [
            'InfosPilote' => $InfosPilote,
            'NbEtudiant' => $NbEtudiant,
            'NbGroupes' => $NbGroupes,
            'Groupes' => $Groupes,
            'Candidatures' => $InfosCandidatures,

            'page2' => $page2,
            'parpage2' => $parpage2,
            'total2' => $total2,

            'pagination2' => $pagination2,

            'page1' => $page1,
            'parpage1' => $parpage1,
            'total1' => $NbGroupes,

            'pagination1' => $pagination1
        ]);
    }

    public function creerGroupe(): void
    {
        if (!isset($_SESSION['id'])) {
            header('Location:CompteConnexion');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nomGroupe = trim($_POST['groupName']);
            if (!empty($nomGroupe)) {
                $idGroupe = $this->modelComptePilote->createGroupe($nomGroupe);
                $this->modelComptePilote->addPiloteToGroupe($_SESSION['id'], $idGroupe);
            }
        }
        header('Location: /ComptePilote');
        exit;
    }


}