<?php

namespace App\Models;
Use PDO;

class DepartementM extends PdoM
{
    public function getIdDepartement($departement, $id_pays) : int
    {
        $rq = $this->pdo->prepare("SELECT id_departement FROM departement WHERE departement = :departement AND id_pays_fk = :pays");
        $rq->bindValue(':departement', $departement, PDO::PARAM_STR);
        $rq->bindValue(':pays', $id_pays, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

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

    public function deleteDepartement($id_departement) : bool
    {
        $rq = $this->pdo->prepare("DELETE FROM departement WHERE id_departement = :id");
        $rq->bindValue(':id', $id_departement, PDO::PARAM_INT);
        return $rq->execute();
    }
}