<?php

namespace App\Models;

use PDO;

class OffreM extends PdoM
{

    public function getNbOffre($nom_offre = '', $ville = '', $nom_entreprise = '', $domaines = [], $contrats = [], $competence = []) : int
    {
        $sql = "SELECT COUNT(*) FROM offre
                                LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                                LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                LEFT JOIN competence_offre ON offre.id_offre = competence_offre.id_offre_fk
                                LEFT JOIN competences ON competence_offre.id_competence_fk = competences.competence
                                LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
                                WHERE offre.titre LIKE ?
                                AND villes.nom_ville LIKE ?
                                AND entreprise.nom LIKE ?";

        $params = [
            "%$nom_offre%",
            "%$ville%",
            "%$nom_entreprise%"
        ];

        // filtres simples
        if (!empty($domaines)) {
            $sql .= " AND offre.domaine IN (" . implode(',', array_fill(0, count($domaines), '?')) . ")";
            $params = array_merge($params, $domaines);
        }

        if (!empty($contrats)) {
            $sql .= " AND contrat.nom_contrat IN (" . implode(',', array_fill(0, count($contrats), '?')) . ")";
            $params = array_merge($params, $contrats);
        }

        if (!empty($competence)) {
            $sql .= " AND competences.competence IN (" . implode(',', array_fill(0, count($competence), '?')) . ")";
            $params = array_merge($params, $competence);
        }

        $rq = $this->pdo->prepare($sql);
        $rq->execute($params);
        return $rq->fetchColumn();
    }
    public function getOffres($page, $parpage, $nom_offre = '', $ville = '', $nom_entreprise = '', $domaines = [], $contrats = [], $competence = [], $id=0) : array
    {
        $page = (int)$page;
        $parpage = (int)$parpage;

        if ($parpage ==-1) {
            $parpage = $this->getNbOffre();
        }
        $start = ($page - 1) * $parpage;

        $sql = "SELECT offre.id_offre, offre.titre, offre.date_creation,
                   IF(offre.date_creation > NOW() - INTERVAL 3 DAY, 1, 0) AS new,
                   entreprise.nom,
                   villes.nom_ville,
                   offre.domaine,
                   contrat.nom_contrat,
                   GROUP_CONCAT(competences.competence) AS competences,
                   (SELECT COUNT(*) FROM whishlist w WHERE w.id_offre_fk = offre.id_offre AND w.id_utilisateur_fk = ?) AS in_wishlist
            FROM offre
                     LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                     LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                     LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                     LEFT JOIN competence_offre ON offre.id_offre = competence_offre.id_offre_fk
                     LEFT JOIN competences ON competence_offre.id_competence_fk = competences.id_competence
                     LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
            WHERE offre.titre LIKE ?
              AND villes.nom_ville LIKE ?
              AND entreprise.nom LIKE ?";

        $params = [
            $id,
            "%$nom_offre%",
            "%$ville%",
            "%$nom_entreprise%"
        ];

        // filtres simples
        if (!empty($domaines)) {
            $sql .= " AND offre.domaine IN (" . implode(',', array_fill(0, count($domaines), '?')) . ")";
            $params = array_merge($params, $domaines);
        }

        if (!empty($contrats)) {
            $sql .= " AND contrat.nom_contrat IN (" . implode(',', array_fill(0, count($contrats), '?')) . ")";
            $params = array_merge($params, $contrats);
        }

        if (!empty($competence)) {
            $sql .= " AND competences.competence IN (" . implode(',', array_fill(0, count($competence), '?')) . ")";
            $params = array_merge($params, $competence);
        }

        $sql .= " GROUP BY offre.id_offre, offre.titre, offre.date_creation, entreprise.nom, villes.nom_ville, offre.domaine, contrat.nom_contrat
                ORDER BY in_wishlist DESC, offre.date_creation DESC
                LIMIT $start, $parpage";

        $rq = $this->pdo->prepare($sql);
        $rq->execute($params);

        return $rq->fetchAll();
    }




    public function getDomaine() : array
    {
        $rq = $this->pdo->query("SELECT DISTINCT domaine FROM offre");
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getContrat() : array
    {
        $rq = $this->pdo->query("SELECT DISTINCT nom_contrat FROM contrat");
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getCompetence() : array
    {
        $rq = $this->pdo->query("SELECT DISTINCT competence FROM competences");
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getOffre() : array
    {
        $rq = $this->pdo->query("SELECT DISTINCT titre FROM offre ");
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }
}