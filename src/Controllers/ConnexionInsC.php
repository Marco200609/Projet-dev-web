<?php

namespace App\Controllers;

use App\Models\ConnexionInsM;

class ConnexionInsC
{
    private $templateEngine;

    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
    }

    public function page_connexion() {
        if (isset($_SESSION['id']) && isset($_SESSION['role'])) {

            $role = (string)$_SESSION['role'];

            $templates = [
                '1' => 'Compte/CompteEtudiant.html.twig',
                '2' => 'Compte/CompteEntreprise.html.twig',
                '3' => 'Compte/ComptePilote.html.twig',
                '4' => 'Compte/CompteAdmin.html.twig'
            ];

            if (array_key_exists($role, $templates)) {
                echo $this->templateEngine->render($templates[$role]);
                return;
            }
        }

        echo $this->templateEngine->render('Compte/Connexion.html.twig');
    }

    public function page_inscription() {
        echo $this->templateEngine->render('/Compte/ChoixInscription.html.twig');
    }

    public function page_inscription_pilote()
    {
        echo $this->templateEngine->render('/Compte/InscriptionPilote.html.twig');
    }

    public function page_inscription_etudiant()
    {
        echo $this->templateEngine->render('/Compte/InscriptionEtudiant.html.twig');
    }

    public function pageIntermediaire_inscription_entreprise()
    {
        echo $this->templateEngine->render('/Compte/ChoixIntermediaireEntreprise.html.twig');
    }

    public function page_inscription_recherche_entreprise()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $premier_compte = str_contains($uri, '/Ajouter');

        echo $this->templateEngine->render(
            '/Compte/InscriptionRechercheEntreprise.html.twig',
            ['premier_compte' => $premier_compte]
        );
    }

    public function page_inscription_admin()
    {
        echo $this->templateEngine->render('/Compte/InscriptionAdmin.html.twig');
    }

    public function page_inscription_attente() {
        $role = $_GET['role'] ?? null;
        echo $this->templateEngine->render('/Compte/InscriptionAttente.html.twig',
        ['role'=>$role]);
    }

    public function form_inscription() {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? null;
        $mot_de_passe = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? null;
        $groupe = $_POST['groupe'] ?? null;
        $linkedin = $_POST['linkedin'] ?? null;

        $premier_compte = $_POST['premier_compte'] ?? 0;
        $code_entreprise = $_POST['codeEntreprise'] ?? null;


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $var = $this->TestFormInscription($code_entreprise);
            if (isset($var['errors'])) {
                if((int)$role===2 && (int)$premier_compte === 1) {
                    $inscription_page = '/Compte/InscriptionRechercheEntreprise.html.twig';
                }
                elseif ((int)$role === 2) {
                    $inscription_page = '/Compte/InscriptionRechercheEntreprise.html.twig';
                }
                else {
                    $inscription_page = match((int)$role) {
                        1 => '/Compte/InscriptionEtudiant.html.twig',
                        3 => '/Compte/InscriptionPilote.html.twig',
                        default => '/Compte/InscriptionEtudiant.html.twig'
                    };
                }

                echo $this->templateEngine->render($inscription_page,
                [
                    'errors'=>$var['errors'],
                    'nom'=>$nom,
                    'prenom'=>$prenom,
                    'email'=>$email,
                    'telephone'=>$telephone,
                    'linkedin'=>$linkedin,
                    'role'=>$role,
                    'code_entreprise'=>$code_entreprise,
                    'premier_compte'=>$premier_compte
                ]);
                exit;
            }
        }

        $model = new \App\Models\ConnexionInsM();

            $id_user = $model->set_id_user(
                $nom,
                $prenom,
                $mot_de_passe,
                $role,
                $email,
                $telephone,
                $groupe,
                $linkedin,
                $code_entreprise,
            );

            if ($premier_compte == 1) {
               header ("Location: /entreprises/add");
               exit;
            }

            else {
                header("Location: /CompteInscription/Attente?role=" . $role);
                exit;
            }
    }

    public function form_connexion() {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['password'];

        $model = new \App\Models\ConnexionInsM();

        $user = $model->get_id_user($email, $mot_de_passe);

        if ($user) {
            $_SESSION['id'] = $user['id_utilisateur'];
            $_SESSION['role'] = $user['id_permission'];

            header("Location: /");
            exit;
        }

        else {
            header("Location: /CompteConnexion");
            exit;
        }
    }

    public function form_deconnexion() {
        $_SESSION = [];
        session_destroy();
        header("Location: /");
        exit;
    }

    public function TestFormInscription($code_entreprise = null)
    {
        $errors = [];

        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $mot_de_passe = $_POST['password'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? null;
        $groupe = $_POST['groupe'] ?? null;
        $linkedin = $_POST['linkedin'] ?? null;

        if (mb_strlen($nom) < 1 || mb_strlen($nom) > 100) {
            $errors[] = "Nom invalide";
        }

        if (mb_strlen($prenom) < 1 || mb_strlen($prenom) > 100) {
            $errors[] = "Prénom invalide";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }

        if ($telephone && !preg_match('/^[0-9]{10}$/', $telephone)) {
            $errors[] = "Numéro de téléphone invalide";
        }


        if (mb_strlen($mot_de_passe) < 8 || !preg_match('/[a-z]/', $mot_de_passe) || !preg_match('/[A-Z]/', $mot_de_passe) || !preg_match('/[0-9]/', $mot_de_passe)) {
            $errors[] = "Mot de passe invalide";
        }

        if ($code_entreprise) {
            $modeleEntreprise = new \App\Models\EntrepriseM();
            $code_entreprise_existe = $modeleEntreprise->get_code_entreprise($code_entreprise);

            if (!$code_entreprise_existe) {
                $errors[] = "Code entreprise invalide";
            }
        }

        if ($linkedin && (!filter_var($linkedin, FILTER_VALIDATE_URL) || !preg_match('/linkedin\.com\/in/', $linkedin))) {
            $errors[] = "URL du logo invalide";
        }


        if (!empty($errors)) {
            return ["errors" => $errors];
        }
        return [
            'nom'=>$nom,
            'prenom'=>$prenom,
            'mot de passe'=> $mot_de_passe,
            'email'=>$email,
            'telephone'=>$telephone,
            'groupe'=>$groupe,
            'linkedin'=>$linkedin
        ];
    }
}
