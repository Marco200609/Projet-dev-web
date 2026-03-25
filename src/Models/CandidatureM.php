<?php

namespace App\Models;
Use PDO;

class CandidatureM extends PdoM
{
    public function AddCandidature($id_user, $id_offre, $cv, $lettre_motivation) : bool
    {
        try{
            $this->pdo->beginTransaction();
            $rq = $this->pdo->prepare("INSERT INTO candidature (cv, lettre_motivation, date_candidature, id_offre_fk, id_utilisateur_fk) 
                                        VALUES (:cv, :lettre_motivation, NOW() ,:id_offre, :id_user)");
            $rq->bindValue(':cv', $cv, PDO::PARAM_STR);
            $rq->bindValue(':lettre_motivation', $lettre_motivation, PDO::PARAM_STR);
            $rq->bindValue(':id_offre', $id_offre, PDO::PARAM_INT);
            $rq->bindValue(':id_user', $id_user, PDO::PARAM_INT);
            $rq->execute();
            $this->pdo->commit();
            return true;
        }
        catch (\Exception $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    public function getCandidature($id_user, $id_offre) : array
    {
        $rq = $this->pdo->prepare("SELECT candidature.cv, candidature.lettre_motivation, candidature.date_candidature FROM candidature
                                        WHERE candidature.id_utilisateur_fk = :id_user AND candidature.id_offre_fk = :id_offre");
        $rq->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $rq->bindValue(':id_offre', $id_offre, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCandidature($id_user, $id_offre) : bool
    {
        try{
            $this->pdo->beginTransaction();
            $rq = $this->pdo->prepare("DELETE FROM candidature WHERE id_utilisateur_fk = :id_user AND id_offre_fk = :id_offre");
            $rq->bindValue(':id_user', $id_user, PDO::PARAM_INT);
            $rq->bindValue(':id_offre', $id_offre, PDO::PARAM_INT);
            $rq->execute();
            $this->pdo->commit();
            return true;
        }  catch (\Exception $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return false;
        }
    }
}