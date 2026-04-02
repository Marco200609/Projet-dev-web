<?php

namespace App\Models;

class ConnexionInsM extends PdoM
{

    const ROLE_ETUDIANT = 1;
    const ROLE_ENTREPRISE = 2;
    const ROLE_PILOTE = 3;
    const ROLE_ADMIN = 4;

    public function get_id_user($email, $mot_de_passe) {
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

    public function get_id_groupe_by_nom($nom_groupe)
    {
        $sql = "SELECT id_groupe 
                FROM groupe 
                WHERE nom_groupe = :nom_groupe";
        $rq = $this->pdo->prepare($sql);
        $rq->execute([
            'nom_groupe' => $nom_groupe
        ]);
            return $rq->fetch();
    }

    public function set_id_user($nom, $prenom, $mot_de_passe, $id_permission, $email, $telephone, $groupe, $linkedin, $code_entreprise)
//        Pour quand l'utilisateur s'inscrit
    {
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        $approuve = ($id_permission == self::ROLE_ETUDIANT) ? 1 : 0;

        $sql_contact = "INSERT INTO contact (email, telephone)
                        VALUES (:email, :telephone)";

        $rq = $this->pdo->prepare($sql_contact);
        $rq->execute(['email' => $email,
                        'telephone' => $telephone]);

        $id_contact = $this->pdo->lastInsertId();

        $sql_user = "INSERT INTO utilisateur (nom, prenom, mot_de_passe, id_permission,id_contact_fk, approuve)
                    VALUES (:nom, :prenom, :mot_de_passe, :id_permission, :id_contact, :approuve)";

        $rq = $this->pdo->prepare($sql_user);
        $rq->execute(['nom' => $nom,
            'prenom' => $prenom,
            'mot_de_passe' => $mot_de_passe_hash,
            'id_permission' => $id_permission,
            'id_contact' => $id_contact,
            'approuve' => $approuve]);

        $id_user = $this->pdo->lastInsertId();

        if ($code_entreprise !== null && $code_entreprise !== "") {
            $sql_entreprise = "UPDATE utilisateur 
                       SET id_entrprise_fk = (
                           SELECT id_entreprise 
                           FROM entreprise 
                           WHERE code_entreprise = :code_entreprise
                       )
                       WHERE id_utilisateur = :id_user";

            $rq = $this->pdo->prepare($sql_entreprise);
            $rq->execute([
                'code_entreprise' => $code_entreprise,
                'id_user' => $id_user
            ]);
        }


        if ($groupe !== null && $groupe !== "") {

            $groupeData = $this->get_id_groupe_by_nom($groupe);

            if (!$groupeData) {
                $this->pdo->rollBack();
                return false;
            }

            $id_groupe = $groupeData['id_groupe'];
            $sql_groupe = "INSERT INTO groupe_utilisateur (id_utilisateur_fk, id_groupe_fk)
                            VALUES (:user, :groupe)";

            $rq = $this->pdo->prepare($sql_groupe);
            $rq->execute([
                'user' => $id_user,
                'groupe' => $id_groupe
                ]);
        }

        if ($linkedin !== null && $linkedin !== "") {
            $sql_linkedin = "UPDATE utilisateur SET linkedin = :linkedin WHERE id_utilisateur = :id_user";
            $rq = $this->pdo->prepare($sql_linkedin);
            $rq->execute([
                'id_user' => $id_user,
                'linkedin' => $linkedin
            ]);
        }

        if ($telephone !== null && $telephone !== "") {
            $sql_telephone = "UPDATE contact SET telephone = :telephone WHERE id_contact = :id_contact";
            $rq = $this->pdo->prepare($sql_telephone);
            $rq->execute([
                'id_contact' => $id_contact,
                'telephone' => $telephone
            ]);
        }

        return $id_user;
    }

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

        $sql = "UPDATE utilisateur
            SET id_permission = 1
            WHERE id_utilisateur = :id";

        $rq = $this->pdo->prepare($sql);
        $rq->execute(['id' => $id_etudiant]);
    }
}