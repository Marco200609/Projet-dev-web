<?php

namespace App\Models;
Use PDO;

class PaysM extends PdoM
{
    public function getIdPays($pays) : array
    {
        $rq = $this->pdo->prepare("SELECT id_pays FROM pays WHERE nom_pays = :pays");
        $rq->bindValue(':pays', $pays, PDO::PARAM_STR);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getOrCreatePays($pays) : int
    {
        $id_pays = $this->getIdPays($pays);
        if (!$id_pays) {
            $rq = $this->pdo->prepare("INSERT INTO pays (nom_pays) VALUES (:pays)");
            $rq->bindValue(':pays', $pays, PDO::PARAM_STR);
            $rq->execute();
            $id_pays = $this->pdo->lastInsertId();
        }
        return $id_pays;
    }

    public function deletePays($id_pays) : bool
    {
        $rq = $this->pdo->prepare("DELETE FROM pays WHERE id_pays = :id");
        $rq->bindValue(':id', $id_pays, PDO::PARAM_INT);
        return $rq->execute();
    }
}