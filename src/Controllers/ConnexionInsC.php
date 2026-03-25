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

    public function page_inscription_entreprise()
    {
        echo $this->templateEngine->render('InscriptionEntreprise.html.twig');
    }

    public function page_inscription_attente() {
        echo $this->templateEngine->render('InscriptionAttente.html.twig');
    }

    public function form_inscription() {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $mot_de_passe = $_POST['password'];
        $role = $_POST['role'];

        $model = new \App\Models\ConnexionInsM();

        $id_user = $model->set_id_user(
            $nom,
            $prenom,
            $mot_de_passe,
            $role,
            $email,
            $telephone,
            null
        );

        header("Location: /CompteInscription/Attente");
        exit;
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
}
