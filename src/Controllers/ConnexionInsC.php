<?php

namespace App\Controllers;

class ConnexionInsC
{



    public function __construct($templateEngine) {
        $this->templateEngine = $templateEngine;
    }

    public function page_connexion() {
        echo $this->templateEngine->render('Connexion.html.twig');
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

        header("Location: /connexion");
        exit;
    }

    public function form_connexion() {
        $email = $_POST['email'];
        $mot_de_passe = $_POST['password'];

        $model = new \App\Models\ConnexionInsM();

        $user = $model->get_id_user($email, $mot_de_passe);

        if ($user) {
            session_start();

            $_SESSION['id'] = $user['id_utilisateur'];
            $_SESSION['role'] = $user['id_permission'];

            header("Location: /accueil");
            exit;
        }

    }
}
