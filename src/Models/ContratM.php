<?php

namespace App\Models;
Use PDO;

class ContratM extends PdoM
{
    /**
     * Récupère la liste de tous les types de contrats distincts présents dans la table "contrat"
     * @return array
     */
    public function getListeContrat(): array
    {
        $rq = $this->pdo->prepare("SELECT DISTINCT nom_contrat FROM contrat");
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Récupère l'ID d'un type de contrat à partir de son nom
     * @param $contrat
     * @return int
     */
    public function getIdContrat($contrat): int
    {
        $rq = $this->pdo->prepare("SELECT id_contrat FROM contrat WHERE nom_contrat = :contrat");
        $rq->bindValue(":contrat", $contrat);
        $rq->execute();
        return $rq->fetchColumn();
    }

}