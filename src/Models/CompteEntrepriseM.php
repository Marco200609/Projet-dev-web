<?php

namespace App\Models;

use PDO;

class CompteEntrepriseM extends PdoM
{

    public function getInfosEntreprise($id): array | bool
    {
        $rq = $this->pdo->prepare("SELECT utilisateur.id_entrprise_fk, entreprise.nom, entreprise.nb_employe, entreprise.descriptif, adresse.adresse FROM utilisateur 
                          LEFT JOIN entreprise ON entreprise.id_entreprise = utilisateur.id_entrprise_fk
                          LEFT JOIN adresse ON adresse.id_adresse = entreprise.id_adresse_fk
                          WHERE utilisateur.id_utilisateur = :id AND adresse.id_adresse = entreprise.id_adresse_fk
        ");

        $rq->bindValue(':id', $id, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }

    public function getUtilisateurEntreprise($id): int
    {
        $rq = $this->pdo->prepare("SELECT utilisateur.id_entrprise_fk FROM utilisateur
                                         WHERE utilisateur.id_utilisateur = :id
        ");

        $rq->bindValue(':id', $id, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getNbOffres($id_entreprise): array
    {
        $rq = $this->pdo->prepare("SELECT COUNT(offre.id_offre) AS nb_offres FROM offre
            WHERE offre.id_entreprise_fk = :id_entreprise
        ");
        $rq->bindValue(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }

    public function getOffresEnCours($page, $parpage, $id_entreprise): array
    {
        $page = (int)$page;
        $parpage = (int)$parpage;

        if ($parpage ==-1) {
            $parpage = $this->getNbOffreEnCours($id_entreprise);
        }
        $start = ($page - 1) * $parpage;

        $rq = $this->pdo->prepare("
        SELECT offre.id_offre, offre.titre, contrat.nom_contrat, COUNT(candidature.id_candidature) AS nb_cand
        FROM offre
        LEFT JOIN contrat ON contrat.id_contrat = offre.id_contrat_fk
        LEFT JOIN candidature ON candidature.id_offre_fk = offre.id_offre
        WHERE offre.id_entreprise_fk = :id_entreprise AND offre.visible = 1 AND offre.Pause = 0
        GROUP BY offre.id_offre
        LIMIT :start, :parpage
    ");
        $rq->bindValue(':start', $start, PDO::PARAM_INT);
        $rq->bindValue(':parpage', $parpage, PDO::PARAM_INT);
        $rq->bindValue(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOffresEnPause($page, $parpage, $id_entreprise): array
    {
        $page = (int)$page;
        $parpage = (int)$parpage;

        if ($parpage ==-1) {
            $parpage = $this->getNbOffreEnPause($id_entreprise);
        }
        $start = ($page - 1) * $parpage;

        $rq = $this->pdo->prepare("
        SELECT offre.id_offre, offre.titre, contrat.nom_contrat, COUNT(candidature.id_candidature) AS nb_cand
        FROM offre
        LEFT JOIN contrat ON contrat.id_contrat = offre.id_contrat_fk
        LEFT JOIN candidature ON candidature.id_offre_fk = offre.id_offre
        WHERE offre.id_entreprise_fk = :id_entreprise AND offre.visible = 1 AND offre.Pause = 1
        GROUP BY offre.id_offre
        LIMIT :start, :parpage
    ");
        $rq->bindValue(':start', $start, PDO::PARAM_INT);
        $rq->bindValue(':parpage', $parpage, PDO::PARAM_INT);
        $rq->bindValue(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNbOffreEnCours($id_entreprise): int
    {
        $rq = $this->pdo->prepare("SELECT COUNT(offre.id_offre) AS nb_offres FROM offre
            WHERE offre.id_entreprise_fk = :id_entreprise AND offre.visible = 1 AND offre.Pause = 0
        ");
        $rq->bindValue(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return (int)$rq->fetchColumn();
    }

    public function getNbOffreEnPause($id_entreprise): int
    {
        $rq = $this->pdo->prepare("SELECT COUNT(offre.id_offre) AS nb_offres FROM offre
            WHERE offre.id_entreprise_fk = :id_entreprise AND offre.visible = 1 AND offre.Pause = 1
        ");
        $rq->bindValue(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return (int)$rq->fetchColumn();
    }

    /**
     * Candidatures
     */
    public function getCandidaturesATraiter($id_entreprise): array
    {
        $rq = $this->pdo->prepare("
            SELECT 
                utilisateur.nom, 
                utilisateur.prenom, 
                offre.titre AS poste, 
                candidature.id_candidature, 
                candidature.date_candidature,
                candidature.cv, 
                candidature.lettre_motivation
            FROM candidature
            JOIN utilisateur ON candidature.id_utilisateur_fk = utilisateur.id_utilisateur
            JOIN offre ON candidature.id_offre_fk = offre.id_offre
            WHERE offre.id_entreprise_fk = :id
            ORDER BY candidature.date_candidature DESC
        ");
        $rq->bindValue(':id', $id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }


}