<?php

namespace App\Controllers;
use App\Models\EntrepriseM;
use App\Models\OffreM;
use App\Models\VilleM;

class OffreC
{
    private $modelOffre;
    private $templateEngine;

    public function __construct($templateEngine)
    {
        $this->modelOffre = new OffreM();
        $this->templateEngine = $templateEngine;
    }

    public function PageOffres() : void
    {
        $modelVille = new VilleM();
        $modelEntreprise = new EntrepriseM();

        if (session_status()) {
            $id_user = $_SESSION['id_user'] ?? 0;
        } else {
            $id_user = 0;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $parpage = isset($_GET['parpage']) ? (int)$_GET['parpage'] : 12;
        $entreprise = $_GET['entreprise'] ?? '';
        $ville = $_GET['ville'] ?? '';
        $nom_offre = $_GET['nomOffre'] ?? '';
        $domaines = $_GET['domaines'] ?? '';
        $contrats = $_GET['contrats'] ?? '';
        $competences = $_GET['competences'] ?? '';

        $domaines = $domaines ? explode(',', $domaines) : [];
        $contrats = $contrats ? explode(',', $contrats) : [];
        $competences = $competences ? explode(',', $competences) : [];

        $offres = $this->modelOffre->getOffres($page, $parpage, $nom_offre, $ville, $entreprise, $domaines, $contrats, $competences, $id_user);

        $total = $this->modelOffre->getNbOffre($nom_offre, $ville, $entreprise, $domaines, $contrats, $competences);

        $liste_nom_entreprises = $modelEntreprise->getNomEntreprises();
        $liste_ville_offres = $modelVille->getVilleOffres();
        $liste_competences = $this->modelOffre->getCompetence();
        $liste_contrats = $this->modelOffre->getContrat();
        $liste_domaines = $this->modelOffre->getDomaine();



        echo $this->templateEngine->render('page_offres.html.twig', [
            'offres' => $offres,

            'page' => $page,
            'parpage' => $parpage,
            'total' => $total,

            'entreprise' => $entreprise,
            'ville' => $ville,
            'nom_offre' => $nom_offre,
            'domaines' => $domaines,
            'contrats' => $contrats,
            'competences' => $competences,

            'liste_nom_entreprises' => $liste_nom_entreprises,
            'liste_competences' => $liste_competences,
            'liste_ville_offres' => $liste_ville_offres,
            'liste_contrats' => $liste_contrats,
            'liste_domaines' => $liste_domaines,
        ]);
    }
}