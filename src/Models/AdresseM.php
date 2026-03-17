<?php

namespace App\Models;
use PDO;

class AdresseM extends PdoM
{
    public function getIdAdresse($adresse, $id_ville) : array
    {
        $rq = $this->pdo->prepare("SELECT id_adresse FROM adresse WHERE adresse = :adresse AND id_ville_fk = :ville");
        $rq->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $rq->bindValue(':ville', $id_ville, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getOrCreateAdresse($adresse, $id_ville) : int
    {
        $id_adresse = $this->getIdAdresse($adresse, $id_ville);
        if (!$id_adresse) {
            $rq = $this->pdo->prepare("INSERT INTO adresse (adresse, id_ville_fk) VALUES (:adresse, :ville)");
            $rq->bindValue(':adresse', $adresse, PDO::PARAM_STR);
            $rq->bindValue(':ville', $id_ville, PDO::PARAM_INT);
            $rq->execute();
            $id_adresse = $this->pdo->lastInsertId();
        }
        return $id_adresse;
    }

    public function deleteAdresse($id_adresse) : bool
    {
        $rq = $this->pdo->prepare("DELETE FROM adresse WHERE id_adresse = :id");
        $rq->bindValue(':id', $id_adresse, PDO::PARAM_INT);
        return $rq->execute();
    }
}