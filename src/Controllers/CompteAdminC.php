<?php

namespace App\Controllers;

use App\Models\CompteAdminM;
use App\Models\CompteEtudiantM;
use App\Models\OffreM;
use App\Models\EntrepriseM;
use function App\Services\pagination;

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
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 4) {
            header('Location:CompteConnexion ');
            exit;
        }

        $page1 = isset($_GET['page1']) ? (int)$_GET['page1'] : 1;
        $parpage1 = isset($_GET['parpage1']) ? (int)$_GET['parpage1'] : 12;

        $page2 = isset($_GET['page2']) ? (int)$_GET['page2'] : 1;
        $parpage2 = isset($_GET['parpage2']) ? (int)$_GET['parpage2'] : 12;

        $page3 = isset($_GET['page3']) ? (int)$_GET['page3'] : 1;
        $parpage3 = isset($_GET['parpage3']) ? (int)$_GET['parpage3'] : 12;

        $NbEtudiants = $this->modelCompteEtudiant->getNbEtudiant();
        $NbEntreprises = $this->modelCompteAdmin->getNbEntreprises();
        $NbPilotes = $this->modelCompteAdmin->getNbPilotes();
        $NomComplet = $this->modelCompteEtudiant->getNometGroupeUser($_SESSION['id']);


        $InfosOffre = $this->modelCompteAdmin->getOffresEnAttente($page1, $parpage1);

        $InfosEntreprise = $this->modelCompteAdmin->getEntreprisesEnAttente($page2, $parpage2);

        $PiloteInfo = $this->modelCompteAdmin->getPiloteInfo($page3, $parpage3);

        $total1 = $this->modelCompteAdmin->getNbOffreEnAttente();
        $total2 = $this->modelCompteAdmin->getNbEntrepriseEnAttente();
        $total3 = $this->modelCompteAdmin->getNbPiloteEnAttente();

        $pagination1 = pagination($total1, $page1, $parpage1, '/CompteAdmin', $_GET, '1');
        $pagination2 = pagination($total2, $page2, $parpage2, '/CompteAdmin', $_GET, '2');
        $pagination3 = pagination($total3, $page3, $parpage3, '/CompteAdmin', $_GET, '3');

        echo $this->templateEngine->render('Compte/CompteAdmin.html.twig', [
            'NbEtudiants' => $NbEtudiants,
            'NbEntreprises' => $NbEntreprises,
            'NbPilotes' => $NbPilotes,
            'NomComplet' => $NomComplet,
            'InfosOffre'=> $InfosOffre,
            'InfosEntreprise'=> $InfosEntreprise,
            'PiloteInfo' => $PiloteInfo,

            'page2' => $page2,
            'parpage2' => $parpage2,
            'total2' => $total2,

            'pagination2' => $pagination2,

            'page1' => $page1,
            'parpage1' => $parpage1,
            'total1' => $total1,

            'pagination1' => $pagination1,

            'page3' => $page3,
            'parpage3' => $parpage3,
            'total3' => $total3,

            'pagination3' => $pagination3
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