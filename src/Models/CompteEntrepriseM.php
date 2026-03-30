<?php

namespace App\Models;

use PDO;

class CompteEntrepriseM extends PdoM
{
    public function getStats($id_entreprise): array
    {
        $rq = $this->pdo->prepare("
        SELECT 
            COUNT(o.id_offre) AS nb_offres
        FROM offre o
        WHERE o.id_entreprise_fk = :id
    ");
        $rq->bindValue(':id', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }


    public function getOffresEnCours($id_entreprise): array
    {
        $rq = $this->pdo->prepare("
            SELECT o.id_offre, o.titre, co.nom_contrat, COUNT(ca.id_candidature) AS nb_cand
            FROM offre o
            JOIN contrat co ON o.id_contrat_fk = co.id_contrat
            LEFT JOIN candidature ca ON o.id_offre = ca.id_offre_fk
            WHERE o.id_entreprise_fk = :id
            GROUP BY o.id_offre
        ");
        $rq->bindValue(':id', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Candidatures
     */
    public function getCandidaturesATraiter($id_entreprise): array
    {
        $rq = $this->pdo->prepare("
            SELECT 
                u.nom, u.prenom, o.titre AS poste, 
                ca.id_candidature, ca.date_candidature,
                ca.cv, ca.lettre_motivation
            FROM candidature ca
            JOIN utilisateur u ON ca.id_utilisateur_fk = u.id_utilisateur
            JOIN offre o ON ca.id_offre_fk = o.id_offre
            WHERE o.id_entreprise_fk = :id
            ORDER BY ca.date_candidature DESC
        ");
        $rq->bindValue(':id', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOffresEnPause($id_entreprise): int
    {
        $rq = $this->pdo->prepare("
        SELECT COUNT(*) AS nb_pause
        FROM offre
        WHERE id_entreprise_fk = :id
          AND statut = 'pause'
    ");
        $rq->bindValue(':id', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        $result = $rq->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['nb_pause'] ?? 0);
    }
}