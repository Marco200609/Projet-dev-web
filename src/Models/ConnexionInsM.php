<?php

namespace App\Models;

class ConnexionInsM extends PdoM
{

    const ROLE_ETUDIANT = 1;
    const ROLE_ENTREPRISE = 2;
    const ROLE_PILOTE = 3;
    const ROLE_ADMIN = 4;

    public function get_id_user($email, $mot_de_passe, $_permission) {
        $sql = "SELECT utilisateur.id_utilisateur, utilisateur.mot_de_passe, utilisateur.id_permission
                FROM utilisateur
                JOIN contact ON utilisateur.id_contact_fk = contact.id_contact
                WHERE contact.email = :email";

        $rq = $this->pdo->prepare($sql);
        $rq->execute([
            'email' => $email
        ]);

        $user = $rq->fetch();

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            return $user;
        }

        return false;
    }

    public function set_id_user($nom, $prenom, $mot_de_passe, $id_permission, $email, $telephone, $groupe)
//        Pour quand l'utilisateur s'inscrit
    {
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        $sql_contact = "INSERT INTO contact (email, telephone)
                        VALUES (:email, :telephone)";

        $rq = $this->pdo->prepare($sql_contact);
        $rq->execute(['email' => $email,
                        'telephone' => $telephone]);

        $id_contact = $this->pdo->lastInsertId();

        $sql_user = "INSERT INTO utilisateur (nom, prenom, mot_de_passe, id_permission,id_contact_fk)
                    VALUES (:nom, :prenom, :mot_de_passe, :id_permission, :id_contact)";

        $rq = $this->pdo->prepare($sql_user);
        $rq->execute(['nom' => $nom,
            'prenom' => $prenom,
            'mot_de_passe' => $mot_de_passe_hash,
            'id_permission' => $id_permission,
            'id_contact' => $id_contact]);

        $id_user = $this->pdo->lastInsertId();

        if ($groupe !== null) {
            $sql_groupe = "INSERT INTO groupe_utilisateur (id_utilisateur_fk, id_groupe_fk)
                            VALUES (:user, :groupe)";

            $rq = $this->pdo->prepare($sql_groupe);
            $rq->execute([
                'user' => $id_user,
                'groupe' => $groupe
            ]);
        }
        return $id_user;
    }

    //si y a que 1 admin --> ne sert à rien
    public function set_user_admin($id_admin) {
        //$id_permission = 4
        $sql = "UPDATE utilisateur
            SET id_permission = 4
            WHERE id_utilisateur = :id";

        $rq = $this->pdo->prepare($sql);
        $rq->execute(['id' => $id_admin]);
    }

    //--> sert potentiellement  si l'admin veut changer la permission d'1 compte
    public function set_user_pilote($id_pilote) {
        //$id_permission = 3

        $sql = "UPDATE utilisateur
            SET id_permission = 3
            WHERE id_utilisateur = :id";

        $rq = $this->pdo->prepare($sql);
        $rq->execute(['id' => $id_pilote]);
    }

    public function set_user_entreprise($id_entreprise) {
        //$id_permission = 2
        $sql = "UPDATE utilisateur
            SET id_permission = 2
            WHERE id_utilisateur = :id";

        $rq = $this->pdo->prepare($sql);
        $rq->execute(['id' => $id_entreprise]);
    }

    public function set_user_etudiant($id_etudiant) {
        //$id_permission = 1
        // si c un étudiant il a un nom de groupe obligatoire à remplir --> nom de groupe du pilote à vérifier
        // --> le pilote pourra supprimer l'étudiant si finalement il est pas dans son groupe

        $sql = "UPDATE utilisateur
            SET id_permission = 1
            WHERE id_utilisateur = :id";

        $rq = $this->pdo->prepare($sql);
        $rq->execute(['id' => $id_etudiant]);
    }
}