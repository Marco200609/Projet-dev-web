<?php

namespace App\Models;
Use PDO;

class DepartementM extends PdoM
{
    /**
     * Récupère l'ID d'un département à partir de son nom et de l'ID du pays auquel il appartient
     *
     * @param $departement
     * @param $id_pays
     * @return int
     */
    public function getIdDepartement($departement, $id_pays) : int
    {
        $rq = $this->pdo->prepare("SELECT id_departement FROM departement WHERE departement = :departement AND id_pays_fk = :pays");
        $rq->bindValue(':departement', $departement, PDO::PARAM_STR);
        $rq->bindValue(':pays', $id_pays, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    /**
     * Récupère l'ID d'un département à partir de son nom et de l'ID du pays auquel il appartient, ou crée un nouveau département et retourne son ID si aucun département ne correspond
     *
     * @param $departement
     * @param $id_pays
     * @return int
     */
    public function getOrCreateDepartement($departement, $id_pays) : int
    {
        $id_departement = $this->getIdDepartement($departement, $id_pays);
        if (!$id_departement) {
            $rq = $this->pdo->prepare("INSERT INTO departement (departement, id_pays_fk) VALUES (:departement, :pays)");
            $rq->bindValue(':departement', $departement, PDO::PARAM_STR);
            $rq->bindValue(':pays', $id_pays, PDO::PARAM_INT);
            $rq->execute();
            $id_departement = $this->pdo->lastInsertId();
        }
        return $id_departement;
    }

    /**
     * Supprime un département de la base de données
     *
     * @param $id_departement
     * @return bool
     */
    public function deleteDepartement($id_departement) : bool
    {
        $rq = $this->pdo->prepare("DELETE FROM departement WHERE id_departement = :id");
        $rq->bindValue(':id', $id_departement, PDO::PARAM_INT);
        return $rq->execute();
    }
}