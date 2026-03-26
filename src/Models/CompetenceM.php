<?php

namespace App\Models;
Use PDO;

class CompetenceM extends PdoM
{
    public function addCompetencesOffre($id_offre, $competences, $pdoinstance) : void
    {
        foreach ($competences as $competence) {
            $this->insertCompetence($competence);

            $id = $this->getIdCompetence($competence);

            $rq = $pdoinstance->prepare("INSERT IGNORE INTO competence_offre(id_offre_fk, id_competence_fk) VALUES (:id_offre, :id_competence)");
            $rq->bindValue(':id_offre', $id_offre);
            $rq->bindValue(':id_competence', $id);
            $rq->execute();
        }
    }

    /**
     * @param $competence
     * @return int
     */
    public function getIdCompetence($competence): int
    {
        $rq = $this->pdo->prepare("SELECT id_competence FROM competences WHERE competence = :competence");
        $rq->bindValue(':competence', $competence);
        $rq->execute();
        return $rq->fetch()['id_competence'];
    }

    /**
     * @param $competence
     * @return void
     */
    public function insertCompetence($competence): void
    {
        $rq = $this->pdo->prepare("INSERT IGNORE INTO competences(competence) VALUES (:competence)");
        $rq->bindValue(':competence', $competence);
        $rq->execute();
    }

    public function getListeCompetences() : array
    {
        $rq = $this->pdo->query("SELECT DISTINCT competence FROM competences");
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }
}