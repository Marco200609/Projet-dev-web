<?php

namespace App\Models;
Use PDO;

class ContratM extends PdoM
{
    public function getListeContrat(): array
    {
        $rq = $this->pdo->prepare("SELECT DISTINCT nom_contrat FROM contrat");
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getIdContrat($contrat): int
    {
        $rq = $this->pdo->prepare("SELECT id_contrat FROM contrat WHERE nom_contrat = :contrat");
        $rq->bindValue(":contrat", $contrat);
        $rq->execute();
        return $rq->fetchColumn();
    }

}