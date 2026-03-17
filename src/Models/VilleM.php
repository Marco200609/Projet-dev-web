<?php

namespace App\Models;
use PDO;

class VilleM extends PdoM
{
    public function getIdVille($ville, $id_departement) : array
    {
        $rq = $this->pdo->prepare("SELECT id_ville FROM villes WHERE nom_ville = :ville AND id_departement_fk = :departement");
        $rq->bindValue(':ville', $ville, PDO::PARAM_STR);
        $rq->bindValue(':departement', $id_departement, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

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

    public function deleteVille($id_ville) : bool
    {
        $rq = $this->pdo->prepare("DELETE FROM villes WHERE id_ville = :id");
        $rq->bindValue(':id', $id_ville, PDO::PARAM_INT);
        return $rq->execute();
    }

    public function getVilleEntreprises() :array
    {
        $rq = $this->pdo->prepare("SELECT DISTINCT villes.nom_ville FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville");
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

}