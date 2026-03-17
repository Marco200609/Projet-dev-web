<?php

namespace App\Models;
use PDO;

class NoteM extends PdoM
{
    public function getNoteUser($id_entreprise, $id_user) {
        $rq = $this->pdo->prepare("SELECT note FROM note_entreprise WHERE id_entreprise_fk = :id_entreprise AND id_utilisateur_fk = :id_user");
        $rq->bindValue(':id_entreprise', (int)$id_entreprise, PDO::PARAM_INT);
        $rq->bindValue(':id_user', (int)$id_user, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getNbNote($id_entreprise)
    {
        $rq = $this->pdo->prepare("SELECT COUNT(*) FROM note_entreprise WHERE id_entreprise_fk = :id_entreprise");
        $rq->bindValue(':id_entreprise', (int)$id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getMoyenneNoteEntreprise($id_entreprise) : int
    {
        $rq = $this->pdo->prepare("SELECT AVG(note) FROM note_entreprise WHERE id_entreprise_fk = :id_entreprise");
        $rq->bindValue(':id_entreprise', (int)$id_entreprise, PDO::PARAM_INT);
        $rq->execute();
        return round($rq->fetchColumn());
    }

    public function setNote($note, $id_entreprise, $id_user) : bool
    {
        try{
            $this->pdo->beginTransaction();
            $rq = $this->pdo->prepare("DELETE FROM note_entreprise WHERE id_entreprise_fk = :id_entreprise AND id_utilisateur_fk = :id_user");
            $rq->bindValue(':id_entreprise', (int)$id_entreprise, PDO::PARAM_INT);
            $rq->bindValue(':id_user', (int)$id_user, PDO::PARAM_INT);
            $rq->execute();
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
        $rq = $this->pdo->prepare("INSERT INTO note_entreprise (note, id_entreprise_fk, id_utilisateur_fk) VALUES (:note, :id_entreprise, :id_user)");
        $rq->bindValue(':note', (int)$note, PDO::PARAM_INT);
        $rq->bindValue(':id_entreprise', (int)$id_entreprise, PDO::PARAM_INT);
        $rq->bindValue(':id_user', (int)$id_user, PDO::PARAM_INT);
        $rq->execute();
        return true;
    }
}