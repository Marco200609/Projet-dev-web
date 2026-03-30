<?php

namespace App\Controllers;

use App\Models\CompteEntrepriseM;
use App\Models\EntrepriseM;

class CompteEntrepriseC
{
    private $modelCompte;
    private $entrepriseModel;
    private $twig;

    public function __construct($twig)
    {
        $this->modelCompte = new CompteEntrepriseM();
        $this->entrepriseModel = new EntrepriseM(); // Utilisation du model entreprise
        $this->twig = $twig;
    }

    public function CompteEntreprise(): void
    {
        // 1. ID entreprise
        if (!isset($_SESSION['id_entreprise'])) {
            $id_entreprise = 1; // debug
        } else {
            $id_entreprise = $_SESSION['id_entreprise'];
        }

        // 2. Données
        $infos        = $this->entrepriseModel->getDetailEntreprise($id_entreprise); // ✅ remplace ancienne méthode
        $stats        = $this->modelCompte->getStats($id_entreprise);
        $offres       = $this->modelCompte->getOffresEnCours($id_entreprise);
        $candidatures = $this->modelCompte->getCandidaturesATraiter($id_entreprise);
        $offres = [
            ['id_offre' => 1, 'titre' => 'Développeur Fullstack', 'contrat' => 'Alternance', 'nb_cand' => 12],
            ['id_offre' => 2, 'titre' => 'Cloud Architect', 'contrat' => 'Stage', 'nb_cand' => 3]
        ];

        // 3. Vue
        echo $this->twig->render('CompteEntreprise.html.twig', [
            'entreprise'   => $infos,
            'stats'        => $stats,
            'offres'       => $offres,
            'candidatures' => $candidatures,
            'session'      => $_SESSION
        ]);

        $nb_pause = $this->modelCompte->getOffresEnPause($id_entreprise);

        echo $this->twig->render('CompteEntreprise.html.twig', [
            'entreprise'   => $infos,
            'stats'        => $stats,
            'offres'       => $offres,
            'nb_pause'     => $nb_pause,
            'session'      => $_SESSION
        ]);
    }

    public function PauseOffre($id_offre): void
    {
        // TODO: update statut offre
        header('Location: /CompteEntreprise');
        exit();
    }
}