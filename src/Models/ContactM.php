<?php

namespace App\Models;
use PDO;

class ContactM extends PdoM
{
    /**
     * Récupère l'ID du contact à partir de son email et de son numéro de téléphone, ou retourne 0 si aucun contact ne correspond
     *
     * @param $mail
     * @param $telephone
     * @return int
     */
    public function getIdContact($mail, $telephone) : int
    {
        $rq = $this->pdo->prepare("SELECT id_contact FROM contact WHERE email = :mail AND telephone = :telephone");
        $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
        $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
        $rq->execute();
        return $rq->fetchColumn();
    }

    /**
     * Récupère les informations d'un contact à partir de son ID
     * @param $id_contact
     * @return array
     */
    public function getContactById($id_contact) : array
    {
        $rq = $this->pdo->prepare("SELECT contact.email, contact.email FROM contact WHERE id_contact = :id");
        $rq->bindValue(':id', $id_contact, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

    /**
     * Récupère l'ID du contact à partir de son email et de son numéro de téléphone, ou crée un nouveau contact et retourne son ID si aucun contact ne correspond
     * @param $mail
     * @param $telephone
     * @return int
     */
    public function getOrCreateContact($mail, $telephone) : int
    {
        $id_contact = $this->getIdContact($mail, $telephone);
        if (!$id_contact) {
            $rq = $this->pdo->prepare("INSERT INTO contact (email, telephone) VALUES (:mail, :telephone)");
            $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
            $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
            $rq->execute();
            $id_contact = $this->pdo->lastInsertId();
        }
        return $id_contact;
    }

    /**
     * Met à jour les informations d'un contact existant dans la base de données
     *
     * @param $mail
     * @param $telephone
     * @param $id_contact
     * @return bool
     */
    public function updateContact($mail, $telephone, $id_contact) : bool
    {
        $rq = $this->pdo->prepare("UPDATE contact SET email = :mail, telephone = :telephone WHERE id_contact = :id");
        $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
        $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
        $rq->bindValue(':id', $id_contact, PDO::PARAM_INT);
        return $rq->execute();
    }

    /**
     * Supprime un contact de la base de données en fonction de son ID
     *
     * @param $id_contact
     * @return bool
     */
    public function deleteContact($id_contact) : bool
    {
        $rq = $this->pdo->prepare("DELETE FROM contact WHERE id_contact = :id");
        $rq->bindValue(':id', $id_contact, PDO::PARAM_INT);
        return $rq->execute();
    }
}
