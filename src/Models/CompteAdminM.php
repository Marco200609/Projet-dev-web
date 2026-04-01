<?php

namespace App\Models;

use PDO;

class CompteAdminM extends PdoM
{
    public function getNbEntreprises() : int
    {
        $rq = $this->pdo->prepare("SELECT COUNT(*) FROM entreprise");
        $rq->execute();
        return $rq->fetchColumn();
    }
    public function getNbPilotes() : int
    {
        $rq = $this->pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE id_permission = 3");
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getOffresEnAttente() : array
    {
        $sql = "SELECT entreprise.nom, contrat.nom_contrat, offre.id_offre, offre.titre, offre.date_creation, offre.domaine, offre.visible FROM offre
            INNER JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
            LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
            WHERE offre.visible = 0 
            ORDER BY offre.date_creation DESC";

        $rq = $this->pdo->prepare($sql);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function AcceptOffre(int $id_offre): bool
    {
        $sql = "UPDATE offre SET visible = 1 WHERE id_offre = ?";
        $rq = $this->pdo->prepare($sql);
        return $rq->execute([$id_offre]);
    }

    public function getEntreprisesEnAttente() : array
    {
        $sql = "SELECT entreprise.nom, entreprise.id_entreprise, adresse.adresse, contact.email, contact.telephone FROM entreprise
            LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
            LEFT JOIN contact ON entreprise.id_contact_fk = contact.id_contact
            WHERE entreprise.visible = 0";

        $rq = $this->pdo->prepare($sql);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function AcceptEntreprise(int $id_entreprise): bool
    {
        $sql = "UPDATE entreprise SET visible = 1 WHERE id_entreprise = ?";
        $rq = $this->pdo->prepare($sql);
        return $rq->execute([$id_entreprise]);
    }

    public function getPiloteInfo() : array
    {
        $sql = "SELECT utilisateur.nom, utilisateur.prenom, utilisateur.id_utilisateur, contact.email, contact.telephone FROM utilisateur
            LEFT JOIN contact ON utilisateur.id_contact_fk = contact.id_contact
            WHERE utilisateur.approuve = 0";

        $rq = $this->pdo->prepare($sql);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function AcceptPilote(int $id_utilisateur): bool
    {
        $sql = "UPDATE utilisateur SET approuve = 1, id_permission = 3 WHERE id_utilisateur = ?";
        $rq = $this->pdo->prepare($sql);
        return $rq->execute([$id_utilisateur]);
    }

    public function DeletePilote(int $id_utilisateur): bool
    {
        $sql = "DELETE FROM utilisateur WHERE id_utilisateur = ?";
        $rq = $this->pdo->prepare($sql);
        return $rq->execute([$id_utilisateur]);
    }




}