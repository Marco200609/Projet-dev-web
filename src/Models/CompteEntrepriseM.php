<?php
namespace App\Models;

use PDO;

class CompteEntrepriseM extends PdoM
{
    /**
     * Récupère les infos de base de l'entreprise et du recruteur
     */
    public function getInfosEntreprise($id_entreprise): array
    {
        $rq = $this->pdo->prepare("
            SELECT e.nom, e.descriptif, v.nom_ville, c.email, u.nom AS recruteur_nom, u.prenom AS recruteur_prenom
            FROM entreprise e
            JOIN adresse a ON e.id_adresse_fk = a.id_adresse
            JOIN villes v ON a.id_ville_fk = v.id_ville
            JOIN contact c ON e.id_contact_fk = c.id_contact
            JOIN utilisateur u ON u.id_entrprise_fk = e.id_entreprise
            WHERE e.id_entreprise = :id
            LIMIT 1
        ");
        $rq->bindValue(':id', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Récupère les statistiques (Nombre d'offres, total candidatures)
     */
    public function getStats($id_entreprise): array
    {
        $rq = $this->pdo->prepare("
            SELECT 
                COUNT(DISTINCT o.id_offre) AS nb_offres,
                COUNT(ca.id_candidature) AS total_candidatures
            FROM entreprise e
            LEFT JOIN offre o ON e.id_entreprise = o.id_entreprise_fk
            LEFT JOIN candidature ca ON o.id_offre = ca.id_offre_fk
            WHERE e.id_entreprise = :id
        ");
        $rq->bindValue(':id', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Liste des offres publiées par l'entreprise
     */
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
     * Liste des candidatures à traiter
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
}