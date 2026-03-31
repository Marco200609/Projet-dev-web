<?php

namespace App\Controllers;

class ConnexionInsC
{
    private $templateEngine;

    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
    }

    public function page_connexion() {
        echo $this->templateEngine->render('Connexion.html.twig');
    }
    public function page_inscription() {
        echo $this->templateEngine->render('ChoixInscription.html.twig');
    }

    public function page_inscription_pilote()
    {
        echo $this->templateEngine->render('InscriptionPilote.html.twig');
    }

    public function page_inscription_etudiant()
    {
        echo $this->templateEngine->render('InscriptionEtudiant.html.twig');
    }

    public function pageIntermediaire_inscription_entreprise()
    {
        echo $this->templateEngine->render('ChoixIntermediaireEntreprise.html.twig');
    }

    public function page_inscription_recherche_entreprise()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $premier_compte = str_contains($uri, '/Ajouter');

        echo $this->templateEngine->render(
            'InscriptionRechercheEntreprise.html.twig',
            ['premier_compte' => $premier_compte]
        );    }

    public function page_inscription_admin()
    {
        echo $this->templateEngine->render('InscriptionAdmin.html.twig');
    }

    public function page_inscription_attente() {
        $role = $_GET['role'] ?? null;
        echo $this->templateEngine->render('InscriptionAttente.html.twig',
        ['role'=>$role]);
    }

    public function form_inscription() {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'] ?? null;
        $mot_de_passe = $_POST['password'];
        $role = $_POST['role'];
        $groupe = $_POST['groupe'] ?? null;
        $linkedin = $_POST['linkedin'] ?? null;

        $premier_compte = $_POST['premier_compte'] ?? 0;
        $code_entreprise = $_POST['code_entreprise'] ?? null;


        $model = new \App\Models\ConnexionInsM();

            $id_user = $model->set_id_user(
                $nom,
                $prenom,
                $mot_de_passe,
                $role,
                $email,
                $telephone,
                $groupe,
                $linkedin
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

        $user = $model->get_id_user($email, $mot_de_passe, null);

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
}
