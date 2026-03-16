<?php

namespace App\Models;

use PDO;

class EntrepriseM extends PdoM
{
    public function getEntreprises($page, $parpage, $nom = '', $ville = '') : array
    {
        if ($parpage ==-1) {
            $parpage = $this->getNbEntreprises();
        }
        $start = ($page - 1) * $parpage;

        $rq = $this->pdo->prepare("SELECT entreprise.id_entreprise, entreprise.nom, entreprise.logo, villes.nom_ville, AVG(note_entreprise.note)AS note, COUNT(offre.id_offre) AS nb_offre FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    LEFT JOIN note_entreprise ON entreprise.id_entreprise = note_entreprise.id_entreprise_fk
                                    LEFT JOIN offre ON entreprise.id_entreprise = offre.id_entreprise_fk
                                    WHERE entreprise.nom LIKE :nom AND villes.nom_ville LIKE :ville
                                    GROUP BY entreprise.id_entreprise
                                    ORDER BY entreprise.nom asc
                                    LIMIT :start, :parpage
                                    ");
        $rq->bindValue(':start', (int)$start, PDO::PARAM_INT);
        $rq->bindValue(':parpage', (int)$parpage, PDO::PARAM_INT);
        $rq->bindValue(':nom', '%' . $nom . '%', PDO::PARAM_STR);
        $rq->bindValue(':ville', '%' . $ville . '%', PDO::PARAM_STR);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNbEntreprises() {
        return $this->pdo->query("SELECT COUNT(*) FROM entreprise")->fetchColumn();
    }

/*    public function getIdEntreprise($nom)
    {
        $rq = $this->pdo->prepare("SELECT id_entreprise FROM entreprise 
                                    WHERE entreprise.nom = :nom ");
        $rq->bindValue(':nom', $nom, PDO::PARAM_STR);
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_ASSOC);
    }*/

    public function getDetailEntreprise($id) : array
    {        $rq = $this->pdo->prepare("SELECT entreprise.nom, entreprise.logo, villes.nom_ville, AVG(note_entreprise.note)AS note, COUNT(offre.id_offre) AS nb_offre, entreprise.descriptif, entreprise.nb_employe, contact.email FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    LEFT JOIN note_entreprise ON entreprise.id_entreprise = note_entreprise.id_entreprise_fk
                                    LEFT JOIN offre ON entreprise.id_entreprise = offre.id_entreprise_fk
                                    LEFT JOIN contact ON entreprise.id_contact_fk = contact.id_contact
                                    WHERE entreprise.id_entreprise = :id
                                    GROUP BY entreprise.id_entreprise");
        $rq->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $rq->execute();
        $entreprise = $rq->fetch(PDO::FETCH_ASSOC);
        // 3 domaines différents des 3 dernières offres
        $rq2 = $this->pdo->prepare("SELECT DISTINCT domaine
                                    FROM (
                                             SELECT domaine
                                             FROM offre
                                             WHERE id_entreprise_fk = :id
                                             ORDER BY date_debut DESC, id_offre DESC
                                         ) AS last_offres
                                    LIMIT 3");
        $rq2->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $rq2->execute();
        $domaines = $rq2->fetchAll(PDO::FETCH_COLUMN);

        $entreprise['domaines_offres'] = $domaines;
        return $entreprise;
    }

    public function getNomEntreprises() :array
    {
        $rq = $this->pdo->prepare("SELECT nom FROM entreprise");
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getVilleEntreprises() :array
    {
        $rq = $this->pdo->prepare("SELECT DISTINCT villes.nom_ville FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville");
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getFormEntreprises($id) :array
    {
        $rq = $this->pdo->prepare("SELECT entreprise.nom, entreprise.logo, adresse.adresse, villes.nom_ville, departement.departement, pays.nom_pays, entreprise.descriptif, entreprise.nb_employe, contact.email, contact.telephone FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    LEFT JOIN departement ON villes.id_departement_fk = departement.id_departement
                                    LEFT JOIN pays ON departement.id_pays_fk = pays.id_pays
                                    LEFT JOIN contact ON entreprise.id_contact_fk = contact.id_contact
                                    WHERE entreprise.id_entreprise = :id");
        $rq->bindParam(':id', $id, PDO::PARAM_INT);
        $rq->execute();
        return $rq->fetch();
    }

    public function addEntreprise($nom, $logo, $pays, $departement, $ville, $adresse, $mail, $telephone, $nb_employe, $description) : bool
    {
        $rq = $this->pdo->prepare("SELECT id_entreprise FROM entreprise WHERE nom = :name");
        $rq->bindValue(':name', $nom, PDO::PARAM_STR);
        $rq->execute();
        $n = $rq->fetchColumn();
        if ($n) {
            return false; // L'entreprise existe déjà
        }

        try {
            $this->pdo->beginTransaction();

            // 1. Pays
            $rq = $this->pdo->prepare("SELECT id_pays FROM pays WHERE nom_pays = :pays");
            $rq->bindValue(':pays', $pays, PDO::PARAM_STR);
            $rq->execute();
            $id_pays = $rq->fetchColumn();
            if (!$id_pays) {
                $rq = $this->pdo->prepare("INSERT INTO pays (nom_pays) VALUES (:pays)");
                $rq->bindValue(':pays', $pays, PDO::PARAM_STR);
                $rq->execute();
                $id_pays = $this->pdo->lastInsertId();
            }

            // 2. Département
            $rq = $this->pdo->prepare("SELECT id_departement FROM departement WHERE departement = :departement AND id_pays_fk = :pays");
            $rq->bindValue(':departement', $departement, PDO::PARAM_STR);
            $rq->bindValue(':pays', $id_pays, PDO::PARAM_INT);
            $rq->execute();
            $id_departement = $rq->fetchColumn();
            if (!$id_departement) {
                $rq = $this->pdo->prepare("INSERT INTO departement (departement, id_pays_fk) VALUES (:departement, :pays)");
                $rq->bindValue(':departement', $departement, PDO::PARAM_STR);
                $rq->bindValue(':pays', $id_pays, PDO::PARAM_INT);
                $rq->execute();
                $id_departement = $this->pdo->lastInsertId();
            }

            // 3. Ville
            $rq = $this->pdo->prepare("SELECT id_ville FROM villes WHERE nom_ville = :ville AND id_departement_fk = :departement");
            $rq->bindValue(':ville', $ville, PDO::PARAM_STR);
            $rq->bindValue(':departement', $id_departement, PDO::PARAM_INT);
            $rq->execute();
            $id_ville = $rq->fetchColumn();
            if (!$id_ville) {
                $rq = $this->pdo->prepare("INSERT INTO villes (nom_ville, id_departement_fk) VALUES (:ville, :departement)");
                $rq->bindValue(':ville', $ville, PDO::PARAM_STR);
                $rq->bindValue(':departement', $id_departement, PDO::PARAM_INT);
                $rq->execute();
                $id_ville = $this->pdo->lastInsertId();
            }

            // 4. Adresse
            $rq = $this->pdo->prepare("SELECT id_adresse FROM adresse WHERE adresse = :adresse AND id_ville_fk = :ville");
            $rq->bindValue(':adresse', $adresse, PDO::PARAM_STR);
            $rq->bindValue(':ville', $id_ville, PDO::PARAM_INT);
            $rq->execute();
            $id_adresse = $rq->fetchColumn();
            if (!$id_adresse) {
                $rq = $this->pdo->prepare("INSERT INTO adresse (adresse, id_ville_fk) VALUES (:adresse, :ville)");
                $rq->bindValue(':adresse', $adresse, PDO::PARAM_STR);
                $rq->bindValue(':ville', $id_ville, PDO::PARAM_INT);
                $rq->execute();
                $id_adresse = $this->pdo->lastInsertId();
            }

            // 5. Contact
            $rq = $this->pdo->prepare("SELECT id_contact FROM contact WHERE email = :mail AND telephone = :telephone");
            $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
            $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
            $rq->execute();
            $id_contact = $rq->fetchColumn();
            if (!$id_contact) {
                $rq = $this->pdo->prepare("INSERT INTO contact (email, telephone) VALUES (:mail, :telephone)");
                $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
                $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
                $rq->execute();
                $id_contact = $this->pdo->lastInsertId();
            }

            // 6. Entreprise
            $rq = $this->pdo->prepare("INSERT INTO entreprise (nom, logo, descriptif, nb_employe, id_adresse_fk, id_contact_fk) VALUES (:nom, :logo, :description, :nb_employe, :id_adresse, :id_contact)");
            $rq->bindValue(':nom', $nom, PDO::PARAM_STR);
            $rq->bindValue(':logo', $logo, PDO::PARAM_STR);
            $rq->bindValue(':description', $description, PDO::PARAM_STR);
            $rq->bindValue(':nb_employe', $nb_employe, PDO::PARAM_INT);
            $rq->bindValue(':id_adresse', $id_adresse, PDO::PARAM_INT);
            $rq->bindValue(':id_contact', $id_contact, PDO::PARAM_INT);
            $rq->execute();

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function updateEntreprise($id, $nom, $logo, $pays, $departement, $ville, $adresse, $mail, $telephone, $nb_employe, $description): bool
    {

        try {
            $this->pdo->beginTransaction();

            // 1. Pays
            $rq = $this->pdo->prepare("SELECT id_pays FROM pays WHERE nom_pays = :pays");
            $rq->bindValue(':pays', $pays, PDO::PARAM_STR);
            $rq->execute();
            $id_pays = $rq->fetchColumn();
            if (!$id_pays) {
                $rq = $this->pdo->prepare("INSERT INTO pays (nom_pays) VALUES (:pays)");
                $rq->bindValue(':pays', $pays, PDO::PARAM_STR);
                $rq->execute();
                $id_pays = $this->pdo->lastInsertId();
            }

            // 2. Département
            $rq = $this->pdo->prepare("SELECT id_departement FROM departement WHERE departement = :departement AND id_pays_fk = :pays");
            $rq->bindValue(':departement', $departement, PDO::PARAM_STR);
            $rq->bindValue(':pays', $id_pays, PDO::PARAM_INT);
            $rq->execute();
            $id_departement = $rq->fetchColumn();
            if (!$id_departement) {
                $rq = $this->pdo->prepare("INSERT INTO departement (departement, id_pays_fk) VALUES (:departement, :pays)");
                $rq->bindValue(':departement', $departement, PDO::PARAM_STR);
                $rq->bindValue(':pays', $id_pays, PDO::PARAM_INT);
                $rq->execute();
                $id_departement = $this->pdo->lastInsertId();
            }

            // 3. Ville
            $rq = $this->pdo->prepare("SELECT id_ville FROM villes WHERE nom_ville = :ville AND id_departement_fk = :departement");
            $rq->bindValue(':ville', $ville, PDO::PARAM_STR);
            $rq->bindValue(':departement', $id_departement, PDO::PARAM_INT);
            $rq->execute();
            $id_ville = $rq->fetchColumn();
            if (!$id_ville) {
                $rq = $this->pdo->prepare("INSERT INTO villes (nom_ville, id_departement_fk) VALUES (:ville, :departement)");
                $rq->bindValue(':ville', $ville, PDO::PARAM_STR);
                $rq->bindValue(':departement', $id_departement, PDO::PARAM_INT);
                $rq->execute();
                $id_ville = $this->pdo->lastInsertId();
            }

            // 4. Adresse
            $rq = $this->pdo->prepare("SELECT id_adresse FROM adresse WHERE adresse = :adresse AND id_ville_fk = :ville");
            $rq->bindValue(':adresse', $adresse, PDO::PARAM_STR);
            $rq->bindValue(':ville', $id_ville, PDO::PARAM_INT);
            $rq->execute();
            $id_adresse = $rq->fetchColumn();
            if (!$id_adresse) {
                $rq = $this->pdo->prepare("INSERT INTO adresse (adresse, id_ville_fk) VALUES (:adresse, :ville)");
                $rq->bindValue(':adresse', $adresse, PDO::PARAM_STR);
                $rq->bindValue(':ville', $id_ville, PDO::PARAM_INT);
                $rq->execute();
                $id_adresse = $this->pdo->lastInsertId();
            }

            // 5. Contact
            $rq = $this->pdo->prepare("SELECT id_contact FROM contact WHERE email = :mail AND telephone = :telephone");
            $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
            $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
            $rq->execute();
            $id_contact = $rq->fetchColumn();
            if (!$id_contact) {
                $rq = $this->pdo->prepare("INSERT INTO contact (email, telephone) VALUES (:mail, :telephone)");
                $rq->bindValue(':mail', $mail, PDO::PARAM_STR);
                $rq->bindValue(':telephone', $telephone, PDO::PARAM_STR);
                $rq->execute();
                $id_contact = $this->pdo->lastInsertId();
            }

            // 6. Entreprise
            $rq = $this->pdo->prepare("UPDATE entreprise
                                        SET nom = :nom, logo = :logo, descriptif = :description, nb_employe = :nb_employe, id_adresse_fk = :id_adresse, id_contact_fk = :id_contact
                                        WHERE id_entreprise = :id");
            $rq->bindValue(':nom', $nom, PDO::PARAM_STR);
            $rq->bindValue(':logo', $logo, PDO::PARAM_STR);
            $rq->bindValue(':description', $description, PDO::PARAM_STR);
            $rq->bindValue(':nb_employe', $nb_employe, PDO::PARAM_INT);
            $rq->bindValue(':id_adresse', $id_adresse, PDO::PARAM_INT);
            $rq->bindValue(':id_contact', $id_contact, PDO::PARAM_INT);
            $rq->bindValue(':id', $id, PDO::PARAM_INT);
            $rq->execute();

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

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

    public function setNote($note, $id_entreprise, $id_user) : bool
    {
        try{
            $this->pdo->beginTransaction();
            $rq = $this->pdo->prepare("DELETE FROM note_entreprise WHERE id_entreprise_fk = :id_entreprise AND id_user_fk = :id_user");
            $rq->bindValue(':id_entreprise', (int)$id_entreprise, PDO::PARAM_INT);
            $rq->bindValue(':id_user', (int)$id_user, PDO::PARAM_INT);
            $rq->execute();
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
             return false;
        }
        $rq = $this->pdo->prepare("INSERT INTO note_entreprise (note, id_entreprise_fk, id_user_fk) VALUES (:note, :id_entreprise, :id_user)");
        $rq->bindValue(':note', (int)$note, PDO::PARAM_INT);
        $rq->bindValue(':id_entreprise', (int)$id_entreprise, PDO::PARAM_INT);
        $rq->bindValue(':id_user', (int)$id_user, PDO::PARAM_INT);
        $rq->execute();
        return true;
    }
}