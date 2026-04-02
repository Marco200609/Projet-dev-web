<?php

namespace App\Models;
use PDO;

class ContactM extends PdoM
{
    public function getIdContact($mail, $telephone) : int
    {
        $rq = $this->pdo->prepare("SELECT id_contact FROM contact WHERE email = :mail AND telephone = :telephone");
        $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
        $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getContactById($id_contact) : array
    {
        $rq = $this->pdo->prepare("SELECT contact.email, contact.email FROM contact WHERE id_contact = :id");
        $rq->bindValue(':id', $id_contact, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetchColumn();
    }

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

    public function updateContact($mail, $telephone, $id_contact) : bool
    {
        $rq = $this->pdo->prepare("UPDATE contact SET email = :mail, telephone = :telephone WHERE id_contact = :id");
        $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
        $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
        $rq->bindValue(':id', $id_contact, PDO::PARAM_INT);
        return $rq->execute();
    }

    public function deleteContact($id_contact) : bool
    {
        $rq = $this->pdo->prepare("DELETE FROM contact WHERE id_contact = :id");
        $rq->bindValue(':id', $id_contact, PDO::PARAM_INT);
        return $rq->execute();
    }
}
