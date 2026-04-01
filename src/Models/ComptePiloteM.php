<?php

namespace App\Models;

use PDO;

class ComptePiloteM extends PdoM
{
    public function getInfosPilote($id) : array
    {
        $rq = $this->pdo->prepare("SELECT utilisateur.id_utilisateur, utilisateur.nom, utilisateur.prenom groupe.groupe FROM utilisateur
                                         WHERE utilisateur.id_utilisateur = :id;");

        $rq->bindValue(':id', $id , PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }

    public function getNbEtudiantsDansGroupesPilote($id_pilote) : int
    {
        $sql = "SELECT COUNT(DISTINCT utilisateur.id_utilisateur) FROM utilisateur
        JOIN groupe_utilisateur ON utilisateur.id_utilisateur = groupe_utilisateur.id_utilisateur_fk
        WHERE groupe_utilisateur.id_groupe_fk IN (
            SELECT groupe_utilisateur.id_groupe_fk FROM groupe_utilisateur
            WHERE groupe_utilisateur.id_utilisateur_fk = :id_pilote
        )
        AND utilisateur.id_permission = 1";  // ← Ajout du filtre pour les étudiants

        $rq = $this->pdo->prepare($sql);
        $rq->bindValue(':id_pilote', $id_pilote, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getNbGroupesPilote($id_pilote) : int
    {
        $sql = "SELECT COUNT(DISTINCT groupe_utilisateur.id_groupe_fk) FROM groupe_utilisateur
            WHERE groupe_utilisateur.id_utilisateur_fk = :id_pilote";

        $rq = $this->pdo->prepare($sql);
        $rq->bindValue(':id_pilote', $id_pilote, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }
    public function getGroupesPilote($id_pilote) : array
    {
        $sql = "SELECT 
                groupe.id_groupe, 
                groupe.nom_groupe, 
                COUNT(utilisateur_membre.id_utilisateur) as nb_etudiants
            FROM groupe
            JOIN groupe_utilisateur AS liaison_pilote ON groupe.id_groupe = liaison_pilote.id_groupe_fk
            LEFT JOIN groupe_utilisateur AS liaison_membres ON groupe.id_groupe = liaison_membres.id_groupe_fk
            LEFT JOIN utilisateur AS utilisateur_membre ON liaison_membres.id_utilisateur_fk = utilisateur_membre.id_utilisateur
            AND utilisateur_membre.id_permission = 1
            WHERE liaison_pilote.id_utilisateur_fk = :id_pilote
            GROUP BY groupe.id_groupe, groupe.nom_groupe
            ORDER BY groupe.nom_groupe";

        $rq = $this->pdo->prepare($sql);
        $rq->bindValue(':id_pilote', $id_pilote, PDO::PARAM_INT);
        $rq->execute();

        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEtudiantsGroupe($id_groupe) : array
    {
        $sql = "SELECT utilisateur.id_utilisateur, utilisateur.prenom, utilisateur.nom
            FROM utilisateur
            JOIN groupe_utilisateur ON utilisateur.id_utilisateur = groupe_utilisateur.id_utilisateur_fk
            WHERE groupe_utilisateur.id_groupe_fk = :id_groupe
            AND utilisateur.id_permission = 1
            ORDER BY utilisateur.nom, utilisateur.prenom";

        $rq = $this->pdo->prepare($sql);
        $rq->bindValue(':id_groupe', $id_groupe, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCandidaturesEtudiantsPilote($id_pilote) : array
    {
        $sql = "SELECT 
                utilisateur.nom, 
                utilisateur.prenom, 
                groupe.nom_groupe, 
                entreprise.nom AS nom_entreprise, 
                offre.titre AS nom_offre, 
                contrat.nom_contrat AS type_offre, 
                candidature.date_candidature
            FROM utilisateur
            JOIN groupe_utilisateur AS gu_etudiant ON utilisateur.id_utilisateur = gu_etudiant.id_utilisateur_fk
            JOIN groupe ON gu_etudiant.id_groupe_fk = groupe.id_groupe
            JOIN groupe_utilisateur AS gu_pilote ON groupe.id_groupe = gu_pilote.id_groupe_fk
            JOIN candidature ON utilisateur.id_utilisateur = candidature.id_utilisateur_fk
            JOIN offre ON candidature.id_offre_fk = offre.id_offre
            JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
            JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
            WHERE gu_pilote.id_utilisateur_fk = :id_pilote AND utilisateur.id_permission = 1
            ORDER BY candidature.date_candidature DESC";

        $rq = $this->pdo->prepare($sql);
        $rq->bindValue(':id_pilote', $id_pilote, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createGroupe($nomGroupe): int
    {
        $sql = "INSERT INTO groupe (nom_groupe) VALUES (:nom)";
        $rq = $this->pdo->prepare($sql);
        $rq->bindValue(':nom', $nomGroupe, PDO::PARAM_STR);
        $rq->execute();

        return $this->pdo->lastInsertId();
    }

    public function addPiloteToGroupe($idPilote, $idGroupe): void
    {
        $sql = "INSERT INTO groupe_utilisateur (id_utilisateur_fk, id_groupe_fk)
            VALUES (:idPilote, :idGroupe)";

        $rq = $this->pdo->prepare($sql);
        $rq->bindValue(':idPilote', $idPilote, PDO::PARAM_INT);
        $rq->bindValue(':idGroupe', $idGroupe, PDO::PARAM_INT);
        $rq->execute();
    }

}