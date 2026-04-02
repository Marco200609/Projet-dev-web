<?php

namespace App\Controllers;
use App\Models\VilleM;

class VilleC
{
    private $modelVille;

        public function __construct()
        {
            $this->modelVille = new VilleM();
        }

    /**
     * Récupère les villes associées aux offres d'emploi et les retourne au format JSON.
     *
     * @return void
     */
    public function getVilleOffre() : void
    {
        $ville = $_GET['ville'] ?? '';
        $villes = $this->modelVille->getVilleOffres($ville);
        header('Content-Type: application/json');
        echo json_encode($villes);
    }

    /**
     * Récupère les villes associées aux entreprises et les retourne au format JSON.
     *
     * @return void
     */
    public function getVilleEntreprise() : void
    {
        $ville = $_GET['ville'] ?? '';
        $villes = $this->modelVille->getVilleEntreprises($ville);
        header('Content-Type: application/json');
        echo json_encode($villes);
    }

}