<?php

namespace App\Controllers;
use App\Models\EntrepriseM;

class EntrepriseC
{
    private $model;
    private $templateEngine;

    public function __construct($templateEngine)
    {
        $this->model = new EntrepriseM();
        $this->templateEngine = $templateEngine;
    }

    public function PageEntreprise()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $parpage = isset($_GET['parpage']) ? (int)$_GET['parpage'] : 12;
        $entreprise = $_GET['entreprise'] ?? '';
        $ville = $_GET['ville'] ?? '';

        $entreprises = $this->model->getEntreprises($page, $parpage, $entreprise, $ville);
        $total = $this->model->getNbEntreprises();
        $nom_entreprises = $this->model->getNomEntreprises();
        $ville_entreprises = $this->model->getVilleEntreprises();

        echo $this->templateEngine->render('entreprise.html.twig', [
            'entreprises' => $entreprises,
            'page' => $page,
            'parpage' => $parpage,
            'total' => $total,
            'entreprise' => $entreprise,
            'ville' => $ville,
            'nom_entreprises' => $nom_entreprises,
            'ville_entreprises' => $ville_entreprises
        ]);
    }

    public function PageDetailEntreprise($id)
    {
        $entreprise = $this->model->getDetailEntreprise($id);
        echo $this->templateEngine->render('detail_entreprise.html.twig', ['entreprise' => $entreprise]);
    }

    public function PageAddEntreprise()
    {
        echo $this->templateEngine->render('add_entreprise.html.twig');
    }

    public function FormRechercheEntreprise(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $ville = $_POST['ville'] ?? '';
            header('Location: /entreprises?nom=' . urlencode($nom) . '&ville=' . urlencode($ville));
            exit();
        }
        header('Location: /entreprises');
    }

    public function FormAddEntreprise(){
        // $nom, $logo, $pays, $departement, $ville, $adresse, $mail, $telephone, $nb_employe, $description

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['nom']) || !isset($_POST['logo']) || !isset($_POST['pays']) || !isset($_POST['departement']) || !isset($_POST['ville']) || !isset($_POST['adresse']) || !isset($_POST['mail']) || !isset($_POST['telephone']) || !isset($_POST['nb_employe']) || !isset($_POST['description'])) {
                header('Location: /entreprises/add');
                exit();
            }
            $nom = $_POST['nom'] ?? '';
            $logo = $_POST['logo'] ?? '';
            $pays = $_POST['pays'] ?? '';
            $departement = $_POST['departement'] ?? '';
            $ville = $_POST['ville'] ?? '';
            $adresse = $_POST['adresse'] ?? '';
            $mail = $_POST['mail'] ?? '';
            $telephone = $_POST['telephone'] ?? '';
            $nb_employe = $_POST['nb_employe'] ?? '';
            $description = $_POST['description'] ?? '';
            if ($this->model->setEntreprise($nom, $logo, $pays, $departement, $ville, $adresse, $mail, $telephone, $nb_employe, $description)) {
                header('Location: /compte/entreprise');
                exit();
            } else {
                header('Location: /entreprises/add');
                exit();
            }
        }
    }

    public function FormAddNote(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id_entreprise']) || !isset($_POST['note']) || !isset($_POST['id_utilisateur'])) {
                header('Location: /entreprises');
                exit();
            }
            if ($this->model->setNote($_POST['note'], $_POST['id_entreprise'], $_POST['id_utilisateur'] )) {
                header('Location: /entreprises/' . $_POST['id_entreprise']);
                exit();
            } else {
                header('Location: /entreprises/' . $_POST['id_entreprise']);
                exit();
            }
        }
    }
}