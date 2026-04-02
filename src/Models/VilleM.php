<?php

namespace App\Models;
use PDO;

class VilleM extends PdoM
{
    /**
     * Récupère l'ID d'une ville en fonction de son nom et de l'ID du département
     *
     * @param $ville
     * @param $id_departement
     * @return int
     */
    public function getIdVille($ville, $id_departement) : int
    {
        $rq = $this->pdo->prepare("SELECT id_ville FROM villes WHERE nom_ville = :ville AND id_departement_fk = :departement");
        $rq->bindValue(':ville', $ville, PDO::PARAM_STR);
        $rq->bindValue(':departement', $id_departement, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    /**
     * Récupère l'ID d'une ville ou la crée si elle n'existe pas
     *
     * @param $ville
     * @param $id_departement
     * @return int
     */
    public function getOrCreateVille($ville, $id_departement) : int
    {
        $id_ville = $this->getIdVille($ville, $id_departement);
        if (!$id_ville) {
            $rq = $this->pdo->prepare("INSERT INTO villes (nom_ville, id_departement_fk) VALUES (:ville, :departement)");
            $rq->bindValue(':ville', $ville, PDO::PARAM_STR);
            $rq->bindValue(':departement', $id_departement, PDO::PARAM_INT);
            $rq->execute();
            $id_ville = $this->pdo->lastInsertId();
        }
        return $id_ville;
    }

    /**
     * Supprime une ville en fonction de son ID
     *
     * @param $id_ville
     * @return bool
     */
    public function deleteVille($id_ville) : bool
    {
        $rq = $this->pdo->prepare("DELETE FROM villes WHERE id_ville = :id");
        $rq->bindValue(':id', $id_ville, PDO::PARAM_INT);
        return $rq->execute();
    }

    /**
     * Récupère la liste des villes où se trouvent les entreprises, avec un filtre sur le début du nom de la ville
     *
     * @param $debut
     * @return array
     */
    public function getVilleEntreprises($debut='') :array
    {
        $rq = $this->pdo->prepare("SELECT DISTINCT villes.nom_ville FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    WHERE villes.nom_ville LIKE :debut");
        $rq->bindValue(':debut', $debut.'%', PDO::PARAM_STR);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Récupère la liste des villes où se trouvent les offres, avec un filtre sur le début du nom de la ville
     *
     * @param $debut
     * @return array
     */
    public function getVilleOffres($debut='') :array
    {
        $rq = $this->pdo->prepare("SELECT DISTINCT villes.nom_ville FROM offre
                                    LEFT JOIN adresse ON offre.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    WHERE villes.nom_ville LIKE :debut");
        $rq->bindValue(':debut', $debut.'%', PDO::PARAM_STR);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }
}
