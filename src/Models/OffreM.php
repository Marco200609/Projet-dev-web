<?php

namespace App\Models;

use PDO;

class OffreM extends PdoM
{

    public function getNbOffre($nom_offre = '', $ville = '', $nom_entreprise = '', $domaines = [], $contrats = [], $competence = [], $visible=1, $pause=0) : int
    {
        $sql = "SELECT COUNT(DISTINCT offre.id_offre) FROM offre
                                LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                                LEFT JOIN adresse ON offre.id_adresse_fk = adresse.id_adresse
                                LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                LEFT JOIN competence_offre ON offre.id_offre = competence_offre.id_offre_fk
                                LEFT JOIN competences ON competence_offre.id_competence_fk = competences.competence
                                LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
                                WHERE offre.titre LIKE ?
                                AND villes.nom_ville LIKE ?
                                AND entreprise.nom LIKE ?
                                AND offre.visible = ?
                                AND offre.Pause = ?";

        $params = [
            "%$nom_offre%",
            "%$ville%",
            "%$nom_entreprise%",
            "$visible",
            "$pause"
        ];

        // filtres simples
        if (!empty($domaines)) {
            $sql .= " AND offre.domaine IN (" . implode(',', array_fill(0, count($domaines), '?')) . ")";
            $params = array_merge($params, $domaines);
        }

        if (!empty($contrats)) {
            $sql .= " AND contrat.nom_contrat IN (" . implode(',', array_fill(0, count($contrats), '?')) . ")";
            $params = array_merge($params, $contrats);
        }

        if (!empty($competence)) {
            $sql .= " AND competences.competence IN (" . implode(',', array_fill(0, count($competence), '?')) . ")";
            $params = array_merge($params, $competence);
        }

        $rq = $this->pdo->prepare($sql);
        $rq->execute($params);
        return $rq->fetchColumn();
    }
    public function getOffres($page, $parpage, $nom_offre = '', $ville = '', $nom_entreprise = '', $domaines = [], $contrats = [], $competence = [], $id=0, $visible=1, $pause=0) : array
    {
        $page = (int)$page;
        $parpage = (int)$parpage;

        if ($parpage ==-1) {
            $parpage = $this->getNbOffre();
        }
        $start = ($page - 1) * $parpage;

        $sql = "SELECT offre.id_offre, offre.titre, offre.date_creation,
                   IF(offre.date_creation > NOW() - INTERVAL 3 DAY, 1, 0) AS new,
                   entreprise.nom,
                   villes.nom_ville,
                   offre.domaine,
                   contrat.nom_contrat,
                   (
                    SELECT GROUP_CONCAT(competences.competence ORDER BY competences.competence)
                    FROM (
                        SELECT competences.competence
                        FROM competence_offre
                        JOIN competences
                            ON competence_offre.id_competence_fk = competences.id_competence
                        WHERE competence_offre.id_offre_fk = offre.id_offre
                        ORDER BY competences.competence
                        LIMIT 3
                    ) AS competences
                ) AS competences,
                   (SELECT COUNT(*) FROM whishlist w WHERE w.id_offre_fk = offre.id_offre AND w.id_utilisateur_fk = ?) AS in_wishlist
            FROM offre
                     LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                     LEFT JOIN adresse ON offre.id_adresse_fk = adresse.id_adresse
                     LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                     LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
            WHERE offre.titre LIKE ?
              AND villes.nom_ville LIKE ?
              AND entreprise.nom LIKE ?
              AND offre.visible = ?
              AND offre.Pause = ?";

        $params = [
            $id,
            "%$nom_offre%",
            "%$ville%",
            "%$nom_entreprise%",
            "$visible",
            "$pause"
        ];

        // filtres simples
        if (!empty($domaines)) {
            $sql .= " AND offre.domaine IN (" . implode(',', array_fill(0, count($domaines), '?')) . ")";
            $params = array_merge($params, $domaines);
        }

        if (!empty($contrats)) {
            $sql .= " AND contrat.nom_contrat IN (" . implode(',', array_fill(0, count($contrats), '?')) . ")";
            $params = array_merge($params, $contrats);
        }

        if (!empty($competence)) {
            $sql .= " AND competences.competence IN (" . implode(',', array_fill(0, count($competence), '?')) . ")";
            $params = array_merge($params, $competence);
        }

        $sql .= " GROUP BY offre.id_offre, offre.titre, offre.date_creation, entreprise.nom, villes.nom_ville, offre.domaine, contrat.nom_contrat
                ORDER BY in_wishlist DESC, offre.date_creation DESC
                LIMIT $start, $parpage";

        $rq = $this->pdo->prepare($sql);
        $rq->execute($params);

        return $rq->fetchAll();
    }

    public function getOffrewishlist($page, $parpage, $id_user) : array
    {
        $page = (int)$page;
        $parpage = (int)$parpage;

        if ($parpage ==-1) {
            $parpage = $this->getNbOffre();
        }
        $start = ($page - 1) * $parpage;

        /*$rq = $this->pdo->prepare("SELECT offre.id_offre, offre.titre, offre.date_creation,
                   IF(offre.date_creation > NOW() - INTERVAL 3 DAY, 1, 0) AS new,
                   entreprise.nom,
                   villes.nom_ville,
                   offre.domaine,
                   contrat.nom_contrat,
                   GROUP_CONCAT(competences.competence) AS competences,
                    (SELECT COUNT(*) FROM whishlist w WHERE w.id_offre_fk = offre.id_offre AND w.id_utilisateur_fk = :id_user) AS in_wishlist
            FROM whishlist
                     JOIN offre ON whishlist.id_offre_fk = offre.id_offre
                     LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                     LEFT JOIN adresse ON offre.id_adresse_fk = adresse.id_adresse
                     LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                     LEFT JOIN competence_offre ON offre.id_offre = competence_offre.id_offre_fk
                     LEFT JOIN competences ON competence_offre.id_competence_fk = competences.id_competence
                     LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
            WHERE whishlist.id_utilisateur_fk = :id_user
              AND offre.visible = 1
              AND offre.Pause = 0
            GROUP BY offre.id_offre, offre.titre, offre.date_creation, entreprise.nom, villes.nom_ville, offre.domaine, contrat.nom_contrat
            ORDER BY offre.date_creation DESC");*/

        $rq = $this->pdo->prepare("SELECT offre.id_offre, offre.titre, offre.date_creation,
                   IF(offre.date_creation > NOW() - INTERVAL 3 DAY, 1, 0) AS new,
                   entreprise.nom,
                   villes.nom_ville,
                   offre.domaine,
                   contrat.nom_contrat,
                   (
                    SELECT GROUP_CONCAT(competences.competence ORDER BY competences.competence)
                    FROM (
                        SELECT competences.competence
                        FROM competence_offre
                        JOIN competences
                            ON competence_offre.id_competence_fk = competences.id_competence
                        WHERE competence_offre.id_offre_fk = offre.id_offre
                        ORDER BY competences.competence
                        LIMIT 3
                    ) AS competences
                ) AS competences,
                    (SELECT COUNT(*) FROM whishlist w WHERE w.id_offre_fk = offre.id_offre AND w.id_utilisateur_fk = :id_user) AS in_wishlist
            FROM whishlist
                     JOIN offre ON whishlist.id_offre_fk = offre.id_offre
                     LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                     LEFT JOIN adresse ON offre.id_adresse_fk = adresse.id_adresse
                     LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                     LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
            WHERE whishlist.id_utilisateur_fk = :id_user
              AND offre.visible = 1
              AND offre.Pause = 0
            GROUP BY offre.id_offre, offre.titre, offre.date_creation, entreprise.nom, villes.nom_ville, offre.domaine, contrat.nom_contrat
            ORDER BY offre.date_creation DESC
            LIMIT :start, :parpage");
                $rq->bindValue(':start', $start, PDO::PARAM_INT);
        $rq->bindValue(':parpage', $parpage, PDO::PARAM_INT);
        $rq->bindValue(':id_user', $id_user);
        $rq->execute();
        return $rq->fetchAll();
    }

    public function getNbOffreWishlist($id_user) : int
    {
        $rq = $this->pdo->prepare("SELECT COUNT(*) FROM whishlist WHERE id_utilisateur_fk = :id_user");
        $rq->bindValue(':id_user', $id_user);
        $rq->execute();
        return $rq->fetchColumn();
    }

    public function getDetailOffre($id_offre, $id_user=0) : array
    {
        $rq = $this->pdo->prepare("SELECT offre.id_offre, offre.titre, offre.date_creation, offre.descriptif,
                                   IF(offre.date_creation > NOW() - INTERVAL 3 DAY, 1, 0) AS new,
                                   entreprise.nom,
                                   villes.nom_ville,
                                   offre.domaine,
                                   contrat.nom_contrat,
                                   GROUP_CONCAT(competences.competence) AS competences,
                                   (SELECT COUNT(*) FROM whishlist w WHERE w.id_offre_fk = offre.id_offre AND w.id_utilisateur_fk = :id_user) AS in_wishlist
                                FROM offre
                                     LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                                     LEFT JOIN adresse ON offre.id_adresse_fk = adresse.id_adresse
                                     LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                     LEFT JOIN competence_offre ON offre.id_offre = competence_offre.id_offre_fk
                                     LEFT JOIN competences ON competence_offre.id_competence_fk = competences.id_competence
                                     LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
                                WHERE offre.id_offre = :id_offre
                                AND offre.visible = 1
                                AND offre.Pause = 0
                                    ");
        $rq->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();
        return $rq->fetch();
    }

    public function getFormOffre($id_offre) : array
    {
        $rq = $this->pdo->prepare("SELECT offre.titre,
                                offre.descriptif,
                                entreprise.nom,
                                adresse.adresse,
                                villes.nom_ville,
                                departement.departement,
                                pays.nom_pays,
                                offre.domaine,
                                contrat.nom_contrat,
                                contact.email,
                                contact.telephone,
                                offre.duree,
                                unite_temps.nom_unite,
                                GROUP_CONCAT(competences.competence) AS competences
                                FROM offre
                                    LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                                    LEFT JOIN adresse ON offre.id_adresse_fk = adresse.id_adresse
                                    LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                    LEFT JOIN departement ON villes.id_departement_fk = departement.id_departement
                                    LEFT JOIN pays ON departement.id_pays_fk = pays.id_pays
                                    LEFT JOIN competence_offre ON offre.id_offre = competence_offre.id_offre_fk
                                    LEFT JOIN competences ON competence_offre.id_competence_fk = competences.id_competence
                                    LEFT JOIN contrat ON offre.id_contrat_fk = contrat.id_contrat
                                    LEFT JOIN contact ON offre.id_contact_recrutement_fk = contact.id_contact
                                    LEFT JOIN unite_temps ON offre.id_unite_duree_fk = unite_temps.id_unite
                                WHERE offre.id_offre = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();
        return $rq->fetch();
    }

    public function getCandidatOffre($id_offre) : ?array
    {
        $rq = $this->pdo->prepare("SELECT offre.titre, offre.id_offre, offre.domaine, villes.nom_ville, entreprise.nom, entreprise.id_entreprise
                                FROM offre
                                LEFT JOIN entreprise ON offre.id_entreprise_fk = entreprise.id_entreprise
                                LEFT JOIN adresse ON offre.id_adresse_fk = adresse.id_adresse
                                LEFT JOIN villes ON adresse.id_ville_fk = villes.id_ville
                                WHERE offre.id_offre = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();
        $result = $rq->fetch();
        return $result === false ? null : $result;
    }

    public function changewishlist($id_offre, $id_utilisateur) : bool
    {
        $rq = $this->pdo->prepare("SELECT COUNT(*) FROM whishlist WHERE id_offre_fk = :id_offre AND id_utilisateur_fk = :id_utilisateur");
        $rq->execute([':id_offre' => $id_offre, ':id_utilisateur' => $id_utilisateur]);
        $present = $rq->fetchColumn() > 0;
        if (!$present) {
            $rq = $this->pdo->prepare("INSERT INTO whishlist (id_offre_fk, id_utilisateur_fk) VALUES (:id_offre, :id_utilisateur)");
        } else {
            $rq = $this->pdo->prepare("DELETE FROM whishlist WHERE id_offre_fk = :id_offre AND id_utilisateur_fk = :id_utilisateur");
        }
        $rq->bindValue(':id_offre', $id_offre);
        $rq->bindValue(':id_utilisateur', $id_utilisateur);
        $rq->execute();
        return !$present;
    }

    public function addOffre($titre, $pays, $departement, $ville, $adresse, $domaine, $contrat, $entreprise, $mail, $telephone, $competences, $unite_dure=null, $duree=null, $descriptif=null) : int
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

            //6. Contrat
            $contratModel = new ContratM();
            $id_contrat = $contratModel->getIdContrat($contrat);

            //7. Entreprise
            $entrepriseModel = new EntrepriseM();
            $id_entreprise = $entrepriseModel->getIdEntreprise($entreprise);
            if (!$id_entreprise) {
                return 0;
            }

            //8. unite durée
            if ($unite_dure !== null) {
                $rq = $this->pdo->prepare("SELECT id_unite FROM unite_temps WHERE nom_unite = :unite");
                $rq->bindValue(':unite', $unite_dure);
                $rq->execute();
                $id_unite_dure = $rq->fetchColumn() ?: null; // null si non trouvée
            } else {
                $id_unite_dure = null;
            }

            // 9. offre
            if ($duree === '' || !isset($duree)) {
                $duree = null;
            }

            $rq = $this->pdo->prepare("INSERT INTO offre (titre, date_creation, duree, descriptif, domaine, id_contrat_fk, id_unite_duree_fk, id_adresse_fk, id_entreprise_fk, id_contact_recrutement_fk)
                                        VALUES (:titre, NOW(), :duree, :descriptif, :domaine, :id_contrat_fk, :id_unite_duree_fk, :id_adresse_fk, :id_entreprise_fk, :id_contact_recrutement_fk)");
            $rq->bindValue(':titre', $titre);
            $rq->bindValue(':duree', $duree, is_null($duree) ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $rq->bindValue(':descriptif', $descriptif);
            $rq->bindValue(':domaine', $domaine);

            $rq->bindValue(':id_contrat_fk', $id_contrat);
            $rq->bindValue(':id_unite_duree_fk', $id_unite_dure);
            $rq->bindValue(':id_adresse_fk', $id_adresse);
            $rq->bindValue(':id_entreprise_fk', $id_entreprise);
            $rq->bindValue(':id_contact_recrutement_fk', $id_contact);
            if (!$rq->execute()) {
                $this->pdo->rollBack();
                echo $rq->errorInfo()[2];
                return 0;
            }
            $id_offre = $this->pdo->lastInsertId();

            //10. competences
            $competencesModel = new CompetenceM();
            $competencesModel->addCompetencesOffre($id_offre, $competences, $this->pdo);

            $this->pdo->commit();
            return $id_offre;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return 0;
        }
    }

    public function updateOffre($id_offre, $titre, $pays, $departement, $ville, $adresse, $domaine, $contrat, $entreprise, $mail, $telephone, $competences, $unite_dure=null, $duree=null, $descriptif=null) : bool
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

            //6. Contrat
            $contratModel = new ContratM();
            $id_contrat = $contratModel->getIdContrat($contrat);

            //7. Entreprise
            $entrepriseModel = new EntrepriseM();
            $id_entreprise = $entrepriseModel->getIdEntreprise($entreprise);
            if (!$id_entreprise) {
                return false;
            }

            //8. unite durée
            if ($unite_dure !== null) {
                $rq = $this->pdo->prepare("SELECT id_unite FROM unite_temps WHERE nom_unite = :unite");
                $rq->bindValue(':unite', $unite_dure);
                $rq->execute();
                $id_unite_dure = $rq->fetchColumn() ?: null; // null si non trouvée
            } else {
                $id_unite_dure = null;
            }

            // 9. offre
            $rq = $this->pdo->prepare("UPDATE offre SET titre = :titre, duree = :duree, descriptif = :descriptif, domaine = :domaine, id_contrat_fk = :id_contrat_fk, id_unite_duree_fk = :id_unite_duree_fk, id_adresse_fk = :id_adresse_fk, id_entreprise_fk = :id_entreprise_fk, id_contact_recrutement_fk = :id_contact_recrutement_fk WHERE id_offre = :id_offre");
            $rq->bindValue(':titre', $titre);
            $rq->bindValue(':duree', $duree);
            $rq->bindValue(':descriptif', $descriptif);
            $rq->bindValue(':domaine', $domaine);

            $rq->bindValue(':id_contrat_fk', $id_contrat);
            $rq->bindValue(':id_unite_duree_fk', $id_unite_dure);
            $rq->bindValue(':id_adresse_fk', $id_adresse);
            $rq->bindValue(':id_entreprise_fk', $id_entreprise);
            $rq->bindValue(':id_contact_recrutement_fk', $id_contact);
            $rq->bindValue(':id_offre', $id_offre);
            $rq->execute();

            //10. competences
            $competencesModel = new CompetenceM();
            $competencesModel->addCompetencesOffre($id_offre, $competences, $this->pdo);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }

    }

    public function deleteOffre($id_offre) : void
    {
        //delete wishlist
        $rq = $this->pdo->prepare("DELETE FROM whishlist WHERE id_offre_fk = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();

        //delete offre
        $rq = $this->pdo->prepare("DELETE FROM offre WHERE id_offre = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();
    }


    /**
     * Permet de savoir si l'offre est approuvé
     *
     * @param $id_offre
     * @return bool
     */
    public function getOffreVisible($id_offre) : bool
    {
        $rq = $this->pdo->prepare("SELECT visible FROM offre WHERE id_offre = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();
        return (bool) $rq->fetchColumn();
    }

    /**
     * Permet à l'admin ou le pilote d'aprouver une offre
     *
     * @param $id_offre
     * @return void
     */
    public function setOffreVisible($id_offre) : void
    {
        $rq = $this->pdo->prepare("UPDATE offre SET visible = 1 WHERE id_offre = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();
    }

    /**
     * Permet de récuperer si l'offre est en pause ou non (invisible ou non sur le site)
     *
     * @param $id_offre
     * @return bool
     */
    public function getOffrePause($id_offre) : bool
    {
        $rq = $this->pdo->prepare("SELECT Pause FROM offre WHERE id_offre = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();
        return (bool) $rq->fetchColumn();
    }

    /**
     * Permet de rendre une offre invisible sur le site sans la supprimer
     *
     * @param $id_offre
     * @return void
     */
    public function setOffrePause($id_offre) : void
    {
        $rq = $this->pdo->prepare("UPDATE offre SET Pause = 1 WHERE id_offre = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();
    }

    /**
     * Permet de rendre une offre visible sur le site
     *
     * @param $id_offre
     * @return void
     */
    public function unsetOffrePause($id_offre) : void
    {
        $rq = $this->pdo->prepare("UPDATE offre SET Pause = 0 WHERE id_offre = :id_offre");
        $rq->bindValue(':id_offre', $id_offre);
        $rq->execute();

    }


    public function getDomaine() : array
    {
        $rq = $this->pdo->query("SELECT DISTINCT domaine FROM offre");
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getOffre() : array
    {
        $rq = $this->pdo->query("SELECT DISTINCT titre FROM offre ");
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUnitesDurees() : array
    {
        $rq = $this->pdo->query("SELECT DISTINCT nom_unite FROM unite_temps");
        return $rq->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updatePauseStatus($id, $state) {
        $db = \App\Models\Database::getConnection(); // Ajuste selon ta méthode de connexion
        $sql = "UPDATE offre SET Pause = :state WHERE id_offre = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            'state' => $state,
            'id'    => $id
        ]);
    }
}