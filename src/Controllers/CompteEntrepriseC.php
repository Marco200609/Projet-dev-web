<?php

namespace App\Controllers;

use App\Models\CandidatureM;
use App\Models\CompteEntrepriseM;
use App\Models\ComptePiloteM;
use App\Models\EntrepriseM;
use App\Models\OffreM;
use function App\Services\pagination;

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
        // ToDo changer l'id user
        if (!isset($_SESSION['id']) || !session_status() || $_SESSION['role'] !== 1) {
            header('Location:CompteConnexion ');
            exit;
        }

        $page2 = isset($_GET['page2']) ? (int)$_GET['page2'] : 1;
        $parpage2 = isset($_GET['parpage2']) ? (int)$_GET['parpage2'] : 12;

        $page1 = isset($_GET['page1']) ? (int)$_GET['page1'] : 1;
        $parpage1 = isset($_GET['parpage1']) ? (int)$_GET['parpage1'] : 12;

        $InfosEntreprise = $this->modelCompteEntreprise->getInfosEntreprise($_SESSION['id']);
        $id_entreprise = $this->modelCompteEntreprise->getUtilisateurEntreprise($_SESSION['id']);

        $NbOffres = $this->modelCompteEntreprise->getNbOffres($id_entreprise);

        $OffresActives = $this->modelCompteEntreprise->getOffresEnCours($page1, $parpage1, $id_entreprise);
        $OffresEnPause = $this->modelCompteEntreprise->getOffresEnPause($page2, $parpage2, $id_entreprise);

        $total1 = $this->modelCompteEntreprise->getNbOffreEnCours($id_entreprise);
        $pagination1 = pagination($total1, $page1, $parpage1, '/CompteEntreprise', $_GET, '1');

        $total2 = $this->modelCompteEntreprise->getNbOffreEnPause($id_entreprise);
        $pagination2 = pagination($total2, $page2, $parpage2, '/CompteEtrenprise', $_GET, '2');

        echo $this->templateEngine->render('Compte/CompteEntreprise.html.twig', [
            'InfosEntreprise' => $InfosEntreprise,
            'NbOffres' => $NbOffres,
            'OffresActives' => $OffresActives,
            'OffresEnPause' => $OffresEnPause,

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