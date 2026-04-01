<?php

namespace App\Controllers;

class ConnexionInsC
{
    private $templateEngine;
    private $model;
    private $entrepriseModel; // Nouveau

    public function __construct($templateEngine, $model = null, $entrepriseModel = null) {
        $this->templateEngine = $templateEngine;
        $this->model = $model ?? new \App\Models\ConnexionInsM();
        $this->entrepriseModel = $entrepriseModel ?? new \App\Models\EntrepriseM();
    }

    public function page_connexion() {
        return $this->templateEngine->render('/Compte/Connexion.html.twig');
    }
    public function page_inscription() {
        return $this->templateEngine->render('/Compte/ChoixInscription.html.twig');
    }

    public function page_inscription_pilote()
    {
        return $this->templateEngine->render('/Compte/InscriptionPilote.html.twig');
    }

    public function page_inscription_etudiant()
    {
        return $this->templateEngine->render('/Compte/InscriptionEtudiant.html.twig');
    }

    public function pageIntermediaire_inscription_entreprise()
    {
        return $this->templateEngine->render('/Compte/ChoixIntermediaireEntreprise.html.twig');
    }

    public function page_inscription_recherche_entreprise()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $premier_compte = str_contains($uri, '/Ajouter');

        return $this->templateEngine->render(
            '/Compte/InscriptionRechercheEntreprise.html.twig',
            ['premier_compte' => $premier_compte]
        );    
    }

    public function page_inscription_admin()
    {
        return $this->templateEngine->render('/Compte/InscriptionAdmin.html.twig');
    }

    public function page_inscription_attente() {
        $role = $_GET['role'] ?? null;
        return $this->templateEngine->render('/Compte/InscriptionAttente.html.twig',
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
                    };
                }

                return $this->templateEngine->render($inscription_page,
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
            }
        }

        $id_user = $this->model->set_id_user(
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
            return "/entreprises/add";
        }

        else {
            return "/CompteInscription/Attente?role=" . $role;
        }
    }

    public function form_connexion() {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['password'];
    
        $user = $this->model->get_id_user($email, $mot_de_passe, null);

        if ($user) {
            $_SESSION['id'] = $user['id_utilisateur'];
            $_SESSION['role'] = $user['id_permission'];

            return "/";
        }

        else {
            return "/CompteConnexion";
        }
    }

    public function form_deconnexion() {
        $_SESSION = [];
        session_destroy();
        return "/";
    }

    public function TestFormInscription($code_entreprise = null)
    {
        $errors = [];

        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $mot_de_passe = $_POST['password'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? null ;
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

        if (!empty($code_entreprise)) {
            $code_entreprise_existe = $this->entrepriseModel->get_code_entreprise($code_entreprise);

            if (!$code_entreprise_existe) {
                $errors[] = "Code entreprise invalide";
            }
        }

        if ($linkedin && (!filter_var($linkedin, FILTER_VALIDATE_URL) || !preg_match('/linkedin\.com\/in/', $linkedin))) {
            $errors[] = "URL LinkedIn invalide";
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
