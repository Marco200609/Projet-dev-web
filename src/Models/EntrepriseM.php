<?php

namespace App\Models;

use PDO;


class EntrepriseM extends PdoM
{
    public function getEntreprises($page, $parpage, $nom = '', $ville = '') : array
    {
        if ($parpage ==-1) {
            $parpage = $this->getNbEntreprises($nom, $ville);
        }
        $start = ($page - 1) * $parpage;

        $rq = $this->pdo->prepare("SELECT entreprise.id_entreprise, entreprise.nom, entreprise.logo, villes.nom_ville, AVG(note_entreprise.note)AS note, COUNT(offre.id_offre) AS nb_offre FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    LEFT JOIN note_entreprise ON entreprise.id_entreprise = note_entreprise.id_entreprise_fk
                                    LEFT JOIN offre ON entreprise.id_entreprise = offre.id_entreprise_fk
                                    WHERE entreprise.nom LIKE :nom AND villes.nom_ville LIKE :ville AND entreprise.visible = 1
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

    public function getNbEntreprises( $nom = '', $ville = '') {
        $rq = $this->pdo->prepare("SELECT COUNT(*) FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    WHERE entreprise.nom LIKE :nom AND villes.nom_ville LIKE :ville AND entreprise.visible = 1");
        $rq->bindValue(':nom', '%' . $nom . '%', PDO::PARAM_STR);
        $rq->bindValue(':ville', '%' . $ville . '%', PDO::PARAM_STR);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getDetailEntreprise($id) : array
    {        $rq = $this->pdo->prepare("SELECT entreprise.nom, entreprise.logo, villes.nom_ville, AVG(note_entreprise.note)AS note, COUNT(offre.id_offre) AS nb_offre, entreprise.descriptif, entreprise.nb_employe, contact.email FROM entreprise
                                    LEFT JOIN adresse ON entreprise.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    LEFT JOIN note_entreprise ON entreprise.id_entreprise = note_entreprise.id_entreprise_fk
                                    LEFT JOIN offre ON entreprise.id_entreprise = offre.id_entreprise_fk
                                    LEFT JOIN contact ON entreprise.id_contact_fk = contact.id_contact
                                    WHERE entreprise.id_entreprise = :id AND entreprise.visible = 1
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
                                             ORDER BY offre.date_creation DESC, id_offre DESC
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
        $rq = $this->pdo->prepare("SELECT nom FROM entreprise
                                    WHERE visible = 1");
        $rq->execute();
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getIdEntreprise($nom) : ?int
    {
        $rq = $this->pdo->prepare("SELECT id_entreprise FROM entreprise WHERE nom = :nom");
        $rq->bindValue(':nom', $nom, PDO::PARAM_STR);
        $rq->execute();
        $id = $rq->fetchColumn();
        return $id ? (int)$id : null;
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
        $rq->bindValue(':id', $id, PDO::PARAM_INT);
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
            $paysModel = new PaysM();
            $id_pays = $paysModel->getOrCreatePays($pays);

            // 2. Département
            $departementModel = new DepartementM();
            $id_departement = $departementModel->getOrCreateDepartement($departement, $id_pays);

            // 3. Ville
            $villeModel = new VilleM();
            $id_ville = $villeModel->getOrCreateVille($ville, $id_departement);

            // 4. Adresse
            $adresseModel = new AdresseM();
            $id_adresse = $adresseModel->getOrCreateAdresse($adresse, $id_ville);

            // 5. Contact
            $contactModel = new ContactM();
            $id_contact = $contactModel->getOrCreateContact($mail, $telephone);

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
            $paysModel = new PaysM();
            $id_pays = $paysModel->getOrCreatePays($pays);

            // 2. Département
            $departementModel = new DepartementM();
            $id_departement = $departementModel->getOrCreateDepartement($departement, $id_pays);

            // 3. Ville
            $villeModel = new VilleM();
            $id_ville = $villeModel->getOrCreateVille($ville, $id_departement);

            // 4. Adresse
            $adresseModel = new AdresseM();
            $id_adresse = $adresseModel->getOrCreateAdresse($adresse, $id_ville);

            // 5. Contact
            $contactModel = new ContactM();
            $id_contact = $contactModel->getOrCreateContact($mail, $telephone);

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
    /*
     * Supprime une entreprise et toutes les données associées (adresse, contact, offres, notes)
     * */
        public function deleteEntreprise($id): bool
    {
        try {
            $this->pdo->beginTransaction();

            // Supprimer les notes associées
            $rq = $this->pdo->prepare("DELETE FROM note_entreprise WHERE id_entreprise_fk = :id");
            $rq->bindValue(':id', $id, PDO::PARAM_INT);
            $rq->execute();

            // Supprimer les offres associées
            $rq = $this->pdo->prepare("DELETE FROM offre WHERE id_entreprise_fk = :id");
            $rq->bindValue(':id', $id, PDO::PARAM_INT);
            $rq->execute();

            // Supprimer l'entreprise
            $rq = $this->pdo->prepare("DELETE FROM entreprise WHERE id_entreprise = :id");
            $rq->bindValue(':id', $id, PDO::PARAM_INT);
            $rq->execute();

            // ToDo : supprimer l'adresse et le contact si ils ne sont plus utilisés par d'autres entreprises

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}