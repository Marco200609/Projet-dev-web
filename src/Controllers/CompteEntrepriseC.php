<?php

namespace App\Controllers;

use App\Models\CompteEntrepriseM;

class CompteEntrepriseC
{
    private $modelCompte;
    private $twig;

    public function __construct($twig)
    {
        // On instancie le modèle spécifique au compte
        $this->modelCompte = new CompteEntrepriseM();
        $this->twig = $twig;
    }

    /**
     * Affiche la page principale du dashboard entreprise
     */
    public function CompteEntreprise(): void
    {
        // 1. Récupération de l'ID de l'entreprise.
        // En prod, on utilise $_SESSION['id_entreprise'].
        // Pour tes tests, on peut en forcer un (ex: 1).
        if (!isset($_SESSION['id_entreprise'])) {
            // Optionnel : Forcer un ID pour le debug si la session n'est pas prête
            $id_entreprise = 1;
        } else {
            $id_entreprise = $_SESSION['id_entreprise'];
        }

        // 2. Récupération des données via le Model
        $infos        = $this->modelCompte->getInfosEntreprise($id_entreprise);
        $stats        = $this->modelCompte->getStats($id_entreprise);
        $offres       = $this->modelCompte->getOffresEnCours($id_entreprise);
        $candidatures = $this->modelCompte->getCandidaturesATraiter($id_entreprise);

        // 3. Rendu de la vue avec Twig
        // Assure-toi que le nom du fichier .twig correspond
        echo $this->twig->render('CompteEntreprise.html.twig', [
            'entreprise'   => $infos,
            'stats'        => $stats,
            'offres'       => $offres,
            'candidatures' => $candidatures,
            'session'      => $_SESSION
        ]);
    }

    /**
     * Méthode pour mettre une offre en pause (si tu cliques sur le bouton pause)
     */
    public function PauseOffre($id_offre): void
    {
        // Logique pour modifier le statut "Pause" dans la table offre
        // Puis redirection
        header('Location: /CompteEntreprise');
        exit();
    }
}