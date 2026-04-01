<?php

namespace App\Controllers;

class ConnexionInsC
{
    private $templateEngine;
    private $model;

    public function __construct($templateEngine, $model = null) {
        $this->templateEngine = $templateEngine;
        $this->model = $model ?? new \App\Models\ConnexionInsM();
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

        $id_user = $this->model->set_id_user(
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
        header("Location: /");
        exit;
    }
}
