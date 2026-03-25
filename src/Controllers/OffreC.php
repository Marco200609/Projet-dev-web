<?php

namespace App\Controllers;
use App\Models\EntrepriseM;
use App\Models\OffreM;
use App\Models\VilleM;
use App\Models\CompetenceM;
use App\Models\ContratM;

use function App\Services\pagination;

class OffreC
{
    private $modelOffre;
    private $templateEngine;

    public function __construct($templateEngine)
    {
        $this->modelOffre = new OffreM();
        $this->templateEngine = $templateEngine;
    }

    public function TestsFormOffre($titre, $pays, $departement, $ville, $adresse, $domaine, $contrat, $entreprise, $mail, $telephone, $competences, $descriptif, $unite_duree=null, $duree=null) : array
    {
        $errors = [];

        // Validation
        if (strlen($titre) < 1 || strlen($titre) > 255) {
            $errors[] = "Titre offre invalide";
        }
        foreach (['pays', 'departement', 'ville', 'adresse'] as $field) {
            if (empty($$field) || strlen($$field) > 255) {
                $errors[] = ucfirst($field) . " invalide";
            }
        }
        if(strlen($domaine) < 1 || strlen($domaine) > 100) {
            $errors[] = "Domaine invalide";
        }
        $modelContrat = new ContratM();
        $liste_contrats = $modelContrat->getListeContrat();
        if(!in_array($contrat, $liste_contrats)) {
            $errors[] = "Contrat invalide";
        }
        if(strlen($entreprise) < 1 || strlen($entreprise) > 255) {
            $errors[] = "Entreprise invalide";
        }
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }
        if (!preg_match('/^[0-9]{10}$/', $telephone)) {
            $errors[] = "Téléphone invalide";
        }
        foreach ($competences as $competence) {
            if (strlen($competence) < 1 || strlen($competence) > 255) {
                $errors[] = "Competence invalide";
            }
        }
        if (strlen($descriptif) < 20) {
            $errors[] = "Description trop courte";
        }
        if (!in_array($unite_duree, $this->modelOffre->getUnitesDurees()) && $unite_duree != null) {
            $errors[] = "Unite dure invalide";
        }
        if ($duree != '' && (!is_numeric($duree) || (int)$duree <= 0)) {
            $errors[] = "Durée invalide";
        }

        if (!empty($errors)) {
            return ["error" => $errors];
        }
        return [ 'titre' => $titre,
            'pays' => $pays,
            'departement' => $departement,
            'ville' => $ville,
            'adresse' => $adresse,
            'domaine' => $domaine,
            'contrat' => $contrat,
            'entreprise' => $entreprise,
            'mail' => $mail,
            'telephone' => $telephone,
            'competences' => $competences,
            'descriptif' => $descriptif,
            'unite_duree' => $unite_duree,
            'duree' => $duree
            ];
    }

    public function ChangeWishlist() : void
    {
        if (!isset($_SESSION['id'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        $id_offre = isset($_POST['id_offre']) ? (int)$_POST['id_offre'] : 0;
        $id_user = (int)$_SESSION['id'];
        if ($id_offre > 0) {
            $this->modelOffre->changewishlist($id_offre, $id_user);
        }
        // Redirige vers la page précédente (offres)
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
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

        $offres = $this->modelOffre->getOffres($page, $parpage, $nom_offre, $ville, $entreprise, $domaines, $contrats, $competences, $id_user);

        $total = $this->modelOffre->getNbOffre($nom_offre, $ville, $entreprise, $domaines, $contrats, $competences);

        $liste_nom_entreprises = $modelEntreprise->getNomEntreprises();
        $liste_ville_offres = $modelVille->getVilleOffres();

        $modelCompetence = new CompetenceM();
        $liste_competences = $modelCompetence->getListeCompetences();

        $modelContrat = new ContratM();
        $liste_contrats = $modelContrat->getListeContrat();

        $liste_domaines = $this->modelOffre->getDomaine();

        $pagination = pagination($total, $page, $parpage, '/offres', $_GET);

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

            'pagination' => $pagination
        ]);
    }

    public function PageDetailOffre($id_offre) : void
    {
        if (session_status()) {
            $id_user = $_SESSION['id_user'] ?? 0;
        } else {
            $id_user = 0;
        }
        $offre = $this->modelOffre->getDetailOffre($id_offre, $id_user);

        if (empty($offre['titre'])) {
            header('location: /404');
        }
        echo $this->templateEngine->render('detail_offre.html.twig', [
            'off' => $offre,
            'user_id' => $id_user
        ]);
    }

    public function PageFormAddOffre() : void
    {
        $unites_duree = $this->modelOffre->getUnitesDurees();
        $modelContrat = new ContratM();
        $liste_contrats = $modelContrat->getListeContrat();
        echo $this->templateEngine->render('add_offre.html.twig', ['unites_duree' =>$unites_duree, 'liste_contrats' => $liste_contrats]);
    }

    public function PageFormUpdateOffre($id) : void
    {
        $modelContrat = new ContratM();
        $liste_contrats = $modelContrat->getListeContrat();
        $unites_duree = $this->modelOffre->getUnitesDurees();
        $offre = $this->modelOffre->getFormOffre($id);
        echo $this->templateEngine->render('add_offre.html.twig',
            ['off' => $offre,
                'update' => true,
                'id_offre' => $id,
                'unites_duree' =>$unites_duree,
                'liste_contrats' => $liste_contrats]);
    }

    public function FormAddOffre() : void
    {
        $unites_duree = $this->modelOffre->getUnitesDurees();
        $modelContrat = new ContratM();
        $liste_contrats = $modelContrat->getListeContrat();

        // $titre, $pays, $departement, $ville, $adresse, $domaine, $contrat, $entreprise, $mail, $telephone, $competences, $unite_dure=null, $duree=null, $descriptif=null
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST["titre"] ?? '';
            $pays = $_POST['pays'] ?? '';
            $departement = $_POST['departement'] ?? '';
            $ville = $_POST['ville'] ?? '';
            $adresse = $_POST['adresse'] ?? '';

            $domaines = $_POST['domaine'] ?? '';
            $contrat = $_POST['contrat'] ?? '';
            $competences =  explode(',', $_POST['competences']) ?? '';

            $entreprise = $_POST['entreprise'] ?? '';
            $mail = $_POST['mail'] ?? '';
            $telephone = $_POST['telephone'] ?? '';

            $unite_duree = ($_POST['unite_duree'] ?? '') !== 'Aucune' ? $_POST['unite_duree'] : null;
            $duree = $_POST['duree'] ?? '';
            $descriptif = $_POST['descriptif'] ?? '';

            $var = $this->TestsFormOffre($titre, $pays, $departement, $ville, $adresse, $domaines, $contrat, $entreprise, $mail, $telephone, $competences, $descriptif, $unite_duree, $duree);
            if (isset($var['error'])) {
                echo $this->templateEngine->render('add_offre.html.twig', [
                    'errors' => $var['error'],
                    'unites_duree' =>$unites_duree,
                    'liste_contrats' => $liste_contrats
                ]);
                exit();
            }

            // Stockage des données brutes
            if ($this->modelOffre->addOffre($var['titre'], $var['pays'], $var['departement'], $var['ville'], $var['adresse'], $var['domaine'], $var['contrat'], $var['entreprise'], $var['mail'], $var['telephone'], $var['competences'], $var['unite_duree'], $var['duree'], $var['descriptif'])) {
                // ToDo modifier le lien
                header('Location: /compte/entreprise');
                exit();
            } else {
                $errors[] = "Une erreur est survenue";
                echo $this->templateEngine->render('add_offre.html.twig', [
                    'errors' => $errors,
                    'unites_duree' =>$unites_duree,
                    'liste_contrats' => $liste_contrats
                ]);
                exit();
            }
        } else {
            echo $this->templateEngine->render('add_offre.html.twig', ['unites_duree' =>$unites_duree, 'liste_contrats' => $liste_contrats]);
            exit();
        }
    }

    public function FormUpdateOffre($id) : void
    {
        $unites_duree = $this->modelOffre->getUnitesDurees();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $titre = $_POST["titre"] ?? '';
            $pays = $_POST['pays'] ?? '';
            $departement = $_POST['departement'] ?? '';
            $ville = $_POST['ville'] ?? '';
            $adresse = $_POST['adresse'] ?? '';

            $domaines = $_POST['domaine'] ?? '';
            $contrat = $_POST['contrat'] ?? '';
            $competences =  explode(',', $_POST['competences']) ?? '';

            $entreprise = $_POST['entreprise'] ?? '';
            $mail = $_POST['mail'] ?? '';
            $telephone = $_POST['telephone'] ?? '';

            $unite_duree = ($_POST['unite_duree'] ?? '') !== 'Aucune' ? $_POST['unite_duree'] : null;
            $duree = $_POST['duree'] ?? '';
            $descriptif = $_POST['descriptif'] ?? '';

            $var = $this->TestsFormOffre($titre, $pays, $departement, $ville, $adresse, $domaines, $contrat, $entreprise, $mail, $telephone, $competences, $descriptif, $unite_duree, $duree);

            if (isset($var['error'])) {
                $offre = $this->modelOffre->getFormOffre($id);
                echo $this->templateEngine->render('add_offre.html.twig', [
                    'errors' => $var['error'],
                    'id_offre' => $_POST['id_offre'],
                    'update' => false,
                    'unites_duree' =>$unites_duree,
                    'off' => $offre
                ]);
                exit();
            }

            // Stockage des données brutes
            if ($this->modelOffre->updateOffre($id, $var['titre'], $var['pays'], $var['departement'], $var['ville'], $var['adresse'], $var['domaine'], $var['contrat'], $var['entreprise'], $var['mail'], $var['telephone'], $var['competences'], $var['unite_duree'], $var['duree'], $var['descriptif'])) {
                // ToDo modifier le lien
                echo $var['contrat'];
                exit();
                header('Location: /compte/entreprise');
                exit();
            } else {
                $errors[] = "Une erreur est survenue";
                $offre = $this->modelOffre->getFormOffre($id);
                echo $this->templateEngine->render('add_offre.html.twig', [
                    'errors' => $errors,
                    'id_offre' => $_POST['id_offre'],
                    'update' => false,
                    'unites_duree' =>$unites_duree,
                    'off' => $offre
                ]);
                exit();
            }
        } else {
            echo $this->templateEngine->render('add_offre.html.twig', ['unites_duree' =>$unites_duree]);
            exit();
        }
    }
}
