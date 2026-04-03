<?php

namespace App\Controllers;
use App\Models\EntrepriseM;
use App\Models\NoteM;
use App\Models\OffreM;
use App\Models\VilleM;

use function App\Services\pagination;

class EntrepriseC
{
    private $modelEntreprise;
    private $templateEngine;
    private $modelNote;

    public function __construct($templateEngine)
    {
        $this->modelEntreprise = new EntrepriseM();

        $this->modelNote = new NoteM();

        $this->templateEngine = $templateEngine;
    }

    /**
     * Valide les données du formulaire d'entreprise et retourne un tableau de données valides ou des erreurs.
     *
     * @return array|array[]
     */
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

    /**
     * Récupère les entreprises associées aux offres d'emploi et les retourne au format JSON.
     *
     * @return void
     */
    public function getEntreprisesOffres() : void
    {
        $nom_ent = $_GET['entreprise'] ?? '';
        $entreprises = $this->modelEntreprise->getNomEntreprises($nom_ent, true);
        header('Content-Type: application/json');
        echo json_encode($entreprises);
    }

    /**
     * Récupère les entreprises associées aux entreprises et les retourne au format JSON.
     *
     * @return void
     */
    public function getEntreprisesEntreprises() : void
    {
        $nom_ent = $_GET['entreprise'] ?? '';
        $entreprises = $this->modelEntreprise->getNomEntreprises($nom_ent, false);
        header('Content-Type: application/json');
        echo json_encode($entreprises);
    }

    /**
     * Affiche la page des entreprises avec pagination et filtres de recherche.
     *
     * @return void
     */
    public function PageEntreprise(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $parpage = isset($_GET['parpage']) ? (int)$_GET['parpage'] : 12;
        $entreprise = $_GET['entreprise'] ?? '';
        $ville = $_GET['ville'] ?? '';

        $total = $this->modelEntreprise->getNbEntreprises($entreprise, $ville);

        $pagination = pagination($total, $page, $parpage, '/entreprises', $_GET);

        $entreprises = $this->modelEntreprise->getEntreprises($page, $parpage, $entreprise, $ville);

        echo $this->templateEngine->render('Entreprise/entreprise.html.twig', [
            'page' => $page,
            'parpage' => $parpage,
            'total' => $total,
            'entreprise' => $entreprise,
            'ville' => $ville,

            'entreprises' => $entreprises,

            'pagination' => $pagination,
        ]);
    }

    /**
     * Affiche la page de détail d'une entreprise avec ses informations, sa note et ses offres d'emploi associées.
     *
     * @param $id
     * @return void
     */
    public function PageDetailEntreprise($id): void
    {
        $modelOffre = new OffreM();
        $entreprise = $this->modelEntreprise->getDetailEntreprise($id);
        if (empty($entreprise['nom'])) {
            header('location: /404');
        }
        $nb_note = $this->modelNote->getNbNote($id);
        if (isset($_SESSION['id'])) {
            $note_user = $this->modelNote->getNoteUser($id, $_SESSION['id']);
        } else {
            $note_user = false;
        }

        $offres = $modelOffre->getOffres(1, 2, nom_entreprise:$entreprise['nom'], id:$_SESSION['id'] ?? 0);

        echo $this->templateEngine->render('Entreprise/detail_entreprise.html.twig', [
            'entreprise' => $entreprise,
            'nb_note' => $nb_note,
            'note_user' => $note_user,
            'id_role' => $_SESSION['role'] ?? 0,
            'offres' => $offres
        ]);
    }

    /**
     * Affiche la page d'ajout d'une entreprise pour les utilisateurs ayant les rôles 3, 4 ou 5. Redirige vers la page de connexion si l'utilisateur n'est pas connecté ou n'a pas les rôles requis.
     *
     * @return void
     */
    public function PageAddEntreprise(): void
    {
        if (!session_status() ||!isset($_SESSION['id']) || !($_SESSION['role'] == 3 || $_SESSION['role'] == 4 || $_SESSION['role'] == 5)) {
            header('Location: /CompteConnexion');
            exit();
        } else {
            echo $this->templateEngine->render('Compte/add_entreprise.html.twig', ['premier_compte'=>1]);
        }
    }

    /**
     * Affiche la page de mise à jour d'une entreprise pour les utilisateurs ayant les rôles 3, 4 ou 5. Redirige vers la page de connexion si l'utilisateur n'est pas connecté ou n'a pas les rôles requis. Récupère les informations de l'entreprise à mettre à jour et les affiche dans le formulaire.
     *
     * @param $id
     * @return void
     */
    public function PageUpdateEntreprise($id): void
    {
        if (!session_status() || !isset($_SESSION['id']) || !($_SESSION['role'] == 3 || $_SESSION['role'] == 4 || $_SESSION['role'] == 5)) {
            header('Location: /CompteConnexion');
            exit();
        } else {
        $entreprise = $this->modelEntreprise->getFormEntreprises($id);
        echo $this->templateEngine->render('Compte/add_entreprise.html.twig',
            ['ent' => $entreprise,
                'update' => true,
                'id_entreprise' => $id]);
        }
    }

    /**
     * Traite le formulaire d'ajout d'une entreprise. Valide les données du formulaire, ajoute l'entreprise à la base de données et redirige vers la page de l'entreprise ou la page d'attente d'inscription en fonction du contexte. Affiche les erreurs de validation si les données sont invalides.
     *
     * @return void
     */
    public function FormAddEntreprise(): void
    {
        // $nom, $logo, $pays, $departement, $ville, $adresse, $mail, $telephone, $nb_employe, $description
        if (!session_status() || !isset($_SESSION['id']) || !($_SESSION['role'] == 3 || $_SESSION['role'] == 4 || $_SESSION['role'] == 5)) {
            header('Location: /CompteConnexion');
            exit();
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $premier_compte = $_POST['premier_compte'] ?? 0;
                $var = $this->TestsFormEntreprise();
                if (isset($var['error'])) {
                    echo $this->templateEngine->render('Compte/add_entreprise.html.twig', [
                        'errors' => $var['error']
                    ]);
                    exit();
                }

                // Stockage des données brutes
                if ($this->modelEntreprise->addEntreprise($var['nom'], $var['logo'], $var['pays'], $var['departement'], $var['nom_ville'], $var['adresse'], $var['email'], $var['telephone'], $var['nb_employe'], $var['description'])) {
                    if ($premier_compte) {
                        header('location: /CompteInscription/Attente?role=3');
                        exit;
                    }

                    header('Location: /CompteEtreprise');
                    exit();
                } else {
                    $errors[] = "L'entreprise existe déjà ou une erreur est survenue";
                    echo $this->templateEngine->render('Compte/add_entreprise.html.twig', [
                        'errors' => $errors
                    ]);
                    exit();
                }
            } else {
                echo $this->templateEngine->render('Compte/add_entreprise.html.twig');
                exit();
            }
        }
    }

    /**
     * Traite le formulaire d'ajout d'une note pour une entreprise. Valide les données du formulaire, ajoute la note à la base de données et redirige vers la page de détail de l'entreprise. Redirige vers la page des entreprises si les données sont invalides ou si l'utilisateur n'est pas connecté ou n'a pas les rôles requis.
     *
     * @return void
     */
    public function FormAddNote(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id_entreprise']) || !session_status() || !isset($_POST['note']) || !isset($_SESSION['id']) || ($_SESSION['role'] != 3 && $_SESSION['role'] != 4)) {
                header('Location: /entreprises');
                exit();
            }
            if ($this->modelNote->setNote($_POST['note'], $_POST['id_entreprise'], $_SESSION['id'] )) {
                header('Location: /detail_entreprise/' . $_POST['id_entreprise']);
                exit();
            } else {
                header('Location: /404');
                exit();
            }
        } else {
            header('Location: /entreprises');
        }
    }

    /**
     * Traite le formulaire de mise à jour d'une entreprise. Valide les données du formulaire, met à jour l'entreprise dans la base de données et redirige vers la page de détail de l'entreprise. Affiche les erreurs de validation si les données sont invalides ou redirige vers la page de connexion si l'utilisateur n'est pas connecté ou n'a pas les rôles requis.
     *
     * @return void
     */
    public function FormUpdateEntreprise(): void
    {
        if (!session_status() || !isset($_SESSION['id']) || !($_SESSION['role'] == 3 || $_SESSION['role'] == 4 || $_SESSION['role'] == 5)) {
            header('Location: /CompteConnexion');
            exit();
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id_entreprise'] ?? '';
                $var = $this->TestsFormEntreprise();
                if (isset($var['error'])) {
                    $entreprise = $this->modelEntreprise->getDetailEntreprise($id);
                    echo $this->templateEngine->render('Compte/add_entreprise.html.twig', [
                        'errors' => $var['error'],
                        'id_entreprise' => $_POST['id_entreprise'],
                        'update' => false,
                        'ent' => $entreprise,
                    ]);
                    exit();
                }

                // Stockage des données brutes
                if ($this->modelEntreprise->updateEntreprise($id, $var['nom'], $var['logo'], $var['pays'], $var['departement'], $var['nom_ville'], $var['adresse'], $var['email'], $var['telephone'], $var['nb_employe'], $var['description'])) {
                    header('Location: /CompteEntreprise');
                    exit();
                } else {
                    $errors[] = "Une erreur est survenue";
                    $entreprise = $this->modelEntreprise->getDetailEntreprise($id);
                    echo $this->templateEngine->render('Compte/add_entreprise.html.twig', [
                        'errors' => $errors,
                        'update' => true,
                        'id_entreprise' => $_POST['id_entreprise'],
                        'ent' => $entreprise
                    ]);
                    exit();
                }
            } else {
                echo $this->templateEngine->render('Compte/add_entreprise.html.twig');
                exit();
            }
        }
    }
}