<?php

namespace App\Controllers;
use App\Models\EntrepriseM;
use JetBrains\PhpStorm\NoReturn;

class EntrepriseC
{
    private $model;
    private $templateEngine;

    public function __construct($templateEngine)
    {
        $this->model = new EntrepriseM();
        $this->templateEngine = $templateEngine;
    }

    public function TestsFormEntreprise() : array
    {
        $errors = [];

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

        // Validation
        if (strlen($nom) < 1 || strlen($nom) > 100) {
            $errors[] = "Nom invalide";
        }
        if (!filter_var($logo, FILTER_VALIDATE_URL)) {
            $errors[] = "URL du logo invalide";
        }
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }
        if (!preg_match('/^[0-9]{10}$/', $telephone)) {
            $errors[] = "Téléphone invalide";
        }
        if (!is_numeric($nb_employe) || (int)$nb_employe <= 0) {
            $errors[] = "Nombre d'employés invalide";
        }
        if (strlen($description) < 20) {
            $errors[] = "Description trop courte";
        }
        foreach (['pays', 'departement', 'ville', 'adresse'] as $field) {
            if (empty($$field) || strlen($$field) > 255) {
                $errors[] = ucfirst($field) . " invalide";
            }
        }

        if (!empty($errors)) {
            return ["error" => $errors];
            }
        return [ 'nom' => $nom, 'logo' => $logo , 'pays' => $pays, 'departement' => $departement, 'nom_ville' => $ville, 'adresse' => $adresse, 'email' => $mail, 'telephone' => $telephone, 'nb_employe' => $nb_employe, 'description' => $description ];
    }

    public function PageEntreprise(): void
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

    public function PageDetailEntreprise($id): void
    {
        $entreprise = $this->model->getDetailEntreprise($id);
        $nb_note = $this->model->getNbNote($id);
        if ($_SESSION['id_utilisateur'] ?? null) {
            $note_user = $this->model->getNoteUser($id, $_SESSION['id_utilisateur']);
        } else {
            $note_user = null;
        }
        echo $this->templateEngine->render('detail_entreprise.html.twig', [
            'entreprise' => $entreprise,
            'nb_note' => $nb_note,
            'note_user' => $note_user,
            'session' => $_SESSION
        ]);
    }

    public function PageAddEntreprise(): void
    {
        echo $this->templateEngine->render('add_entreprise.html.twig');
    }

    public function PageUpdateEntreprise($id): void
    {
        $entreprise = $this->model->getFormEntreprises($id);
        echo $this->templateEngine->render('add_entreprise.html.twig',
            ['ent' => $entreprise,
            'update' => true,
            'id_entreprise' => $id]);

    }

    public function FormAddEntreprise(): void
    {
        // $nom, $logo, $pays, $departement, $ville, $adresse, $mail, $telephone, $nb_employe, $description
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $var = $this->TestsFormEntreprise();
            if (isset($var['error'])) {
                echo $this->templateEngine->render('add_entreprise.html.twig', [
                    'errors' => $var['error']
                ]);
                exit();
            }

            // Stockage des données brutes
            if ($this->model->addEntreprise($var['nom'], $var['logo'], $var['pays'], $var['departement'], $var['nom_ville'], $var['adresse'], $var['email'], $var['telephone'], $var['nb_employe'], $var['description'])) {
                // ToDo modifier le lien
                header('Location: /compte/entreprise');
                exit();
            } else {
                $errors[] = "L'entreprise existe déjà ou une erreur est survenue";
                echo $this->templateEngine->render('add_entreprise.html.twig', [
                    'errors' => $errors
                ]);
                exit();
            }
        } else {
            echo $this->templateEngine->render('add_entreprise.html.twig');
            exit();
        }
    }

    public function FormAddNote(): void
    {
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

    public function FormUpdateEntreprise(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_entreprise'] ?? '';
            $var = $this->TestsFormEntreprise();
            if (isset($var['error'])) {
                $entreprise = $this->model->getDetailEntreprise($id);
                echo $this->templateEngine->render('add_entreprise.html.twig', [
                    'errors' => $var['error'],
                    'id_entreprise' => $_POST['id_entreprise'],
                    'update' => false,
                    'ent' => $entreprise,
                ]);
                exit();
            }

            // Stockage des données brutes
            if ($this->model->updateEntreprise($id, $var['nom'], $var['logo'], $var['pays'], $var['departement'], $var['nom_ville'], $var['adresse'], $var['email'], $var['telephone'], $var['nb_employe'], $var['description'])) {
                // ToDo modifier le lien
                header('Location: /compte/entreprise');
                exit();
            } else {
                $errors[] = "Une erreur est survenue";
                $entreprise = $this->model->getDetailEntreprise($id);
                echo $this->templateEngine->render('add_entreprise.html.twig', [
                    'errors' => $errors,
                    'update' => true,
                    'id_entreprise' => $_POST['id_entreprise'],
                    'ent' => $entreprise
                ]);
                exit();
            }
        } else {
            echo $this->templateEngine->render('add_entreprise.html.twig');
            exit();
        }
    }
}