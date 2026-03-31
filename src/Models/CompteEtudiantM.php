<?php

namespace App\Models;

use PDO;

class CompteEtudiantM extends PdoM
{
    public function getNometGroupeUser($id) : array | bool
    {
        $rq = $this->pdo->prepare("SELECT utilisateur.nom, utilisateur.prenom, groupe.nom_groupe FROM utilisateur
                                         LEFT JOIN groupe_utilisateur ON groupe_utilisateur.id_utilisateur_fk = utilisateur.id_utilisateur
                                         LEFT JOIN groupe ON groupe.id_groupe = groupe_utilisateur.id_groupe_fk
                                         WHERE utilisateur.id_utilisateur = :id;");

        $rq->bindValue(':id', $id , PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }

    public function getNomPilote($id) : array | bool
    {
        $rq = $this->pdo->prepare("SELECT utilisateur.id_utilisateur, utilisateur.nom, utilisateur.prenom FROM utilisateur
                                         JOIN groupe_utilisateur ON utilisateur.id_utilisateur = groupe_utilisateur.id_utilisateur_fk
                                         WHERE id_groupe_fk IN (
                                            SELECT groupe_utilisateur.id_groupe_fk FROM groupe_utilisateur
                                            JOIN utilisateur ON groupe_utilisateur.id_utilisateur_fk = utilisateur.id_utilisateur
                                            WHERE utilisateur.id_utilisateur = :id)
                                         AND utilisateur.id_permission = 3;");

        $rq->bindValue(':id', $id , PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }

    public function getNbCandidatures($id) : int
    {
        $rq = $this->pdo->prepare("SELECT COUNT(*) FROM candidature
                                        WHERE id_utilisateur_fk = :id ");
        $rq->bindValue(':id', $id, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getNbEtudiant($id) : int
    {
        $rq = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE utilisateur.id_utilisateur = :id ");

        $rq->bindValue(':id', $id, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getInfosEtudiant($id) : array | bool
    {
        $rq = $this->pdo->prepare("SELECT utilisateur.nom, utilisateur.prenom, contact.email, utilisateur.linkedin FROM utilisateur
                                         JOIN contact ON utilisateur.id_contact_fk = contact.id_contact
                                         WHERE utilisateur.id_utilisateur = :id;");

        $rq->bindValue(':id', $id , PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }

}