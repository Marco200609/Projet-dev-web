<?php

namespace App\Controllers;

use App\Models\CandidatureM;
use App\Models\OffreM;

class CandidatureC
{
    private $modelCandidature;
    private $templateEngine;
    private $cheminUpload = __DIR__ . '/../../Uploads/';

    public function __construct($templateEngine)
    {
        $this->modelCandidature = new CandidatureM();
        $this->templateEngine = $templateEngine;
    }

    public function TestCandidature($cv_file, $lettre_motivation) : array
    {
        $extension = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.oasis.opendocument.text",
            "image/jpeg",
            "image/png"
        ];

        $errors = [];

        if ($cv_file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur l'upload";
        }
        if (!in_array($cv_file['type'], $extension)) {
            $errors[] = "Type de fichier incorrect";
        }
        if ($cv_file['size'] > 3*1024**2) {
            $errors[] = "taille maximum du fichier de 3 Mo";
        }
        if (strlen($lettre_motivation) < 10) {
            $errors[]= "Erreur lors de taille pour la lettre de motivation";
        }
        return $errors;
    }

    public function PageCandidature($id_offre) : void
    {
        if (!session_status() || !isset($_SESSION['id'])) {
            header('Location: /CompteConnexion');
            exit();
        } elseif ($_SESSION['role'] !== 1) {
            header('Location: /Offres');
            exit();
        }
        $offre = (new OffreM())->getCandidatOffre($id_offre);
        if (!$offre) {
            header('Location: /offres');
            exit();
        }
        echo $this->templateEngine->render('candidature.html.twig', ['offre' => $offre]);
    }

    public function FormAddCandidature() : void
    {
        $id_offre = $_POST['id_offre'] ?? '';

        if (!session_status() || !isset($_SESSION['id'])) {
            header('Location: /CompteConnexion');
            exit();
        } elseif ($_SESSION['role'] !== 1) {
            header('Location: /Offres');
            exit();
        } else {
            $id_user = $_SESSION['id'];
        }
        if (!isset($_FILES['cv'])) {
            $offre = (new OffreM())->getCandidatOffre($id_offre);
            if (!$offre) {
                header('Location: /offres');
                exit();
            }
            $errors = ['veuillez choisir un fichier'];
            echo $this->templateEngine->render('candidature.html.twig', ['offre' => $offre, 'errors' => $errors]);
            exit();
        } else {
            $file = $_FILES['cv'];
            $lettre_motivation = $_POST['lettre_motivation'];
            $errors = $this->TestCandidature($file, $lettre_motivation);
            if ($errors) {
                $offre = (new OffreM())->getCandidatOffre($id_offre);
                if (!$offre) {
                    header('Location: /offres');
                    exit();
                }
                echo $this->templateEngine->render('candidature.html.twig', ['offre' => $offre, 'errors' => $errors]);
            }
            $nom = sha1(uniqid(rand(), true)) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!move_uploaded_file($file['tmp_name'], $this->cheminUpload . $nom)) {
                $offre = (new OffreM())->getCandidatOffre($id_offre);
                if (!$offre) {
                    header('Location: /offres');
                    exit();
                }
                $errors[] = 'Erreur lors du stockage du fichier';
                echo $this->templateEngine->render('candidature.html.twig', ['offre' => $offre, 'errors' => $errors]);
                exit();
            }
            if (!$this->modelCandidature->AddCandidature($id_user, $id_offre, $nom, $lettre_motivation))
            {
                $offre = (new OffreM())->getCandidatOffre($id_offre);
                if (!$offre) {
                    header('Location: /offres');
                    exit();
                }
                $errors[] = 'Une erreur est survenue';
                echo $this->templateEngine->render('candidature.html.twig', ['offre' => $offre, 'errors' => $errors]);
                exit();
            }
            header('Location: /detail_offre/' . $id_offre);
        }
    }


}