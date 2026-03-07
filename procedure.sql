create
    definer = devuser@`%` procedure ajouter_entreprise(IN nom_entreprise varchar(255), IN lien_logo varchar(255),
                                                       IN num_tel varchar(20), IN mail varchar(255),
                                                       IN adresse_entre varchar(255), IN nom_departement varchar(50),
                                                       IN pays_entre varchar(56), IN ville varchar(100))
BEGIN
        DECLARE id_pays_var INT DEFAULT NULL;
        DECLARE id_departement_var INT DEFAULT NULL;
        DECLARE  id_ville_var INT DEFAULT NULL;
        DECLARE  id_adresse_var INT DEFAULT NULL;

        DECLARE id_mail_var INT DEFAULT NULL;
        DECLARE id_num_tel_var INT DEFAULT NULL;
        DECLARE id_entreprise_var INT DEFAULT NULL;

        -- récuperer ou insérer le pays
        SELECT id_pays INTO id_pays_var FROM pays WHERE nom_pays = pays_entre;
        IF id_pays_var IS NULL THEN
            INSERT INTO pays (nom_pays) VALUES (pays_entre);
            SET id_pays_var = LAST_INSERT_ID();
        END IF;

        -- récuperer ou insérer le département
        SELECT id_departement INTO id_departement_var FROM departement WHERE departement = nom_departement AND id_pays_fk = id_pays_var;
        IF id_departement_var IS NULL THEN
            INSERT INTO departement (departement, id_pays_fk) VALUES (nom_departement, id_pays_var);
            SET id_departement_var = LAST_INSERT_ID();
        END IF;

        -- récuperer ou insérer la ville
        SELECT id_ville INTO id_ville_var FROM villes WHERE nom_ville = ville AND id_departement_fk = id_departement_var;
        IF id_ville_var IS NULL THEN
            INSERT INTO villes (nom_ville, id_departement_fk) VALUES (ville, id_departement_var);
            SET id_ville_var = LAST_INSERT_ID();
        END IF;

        -- récuperer ou insérer l'adresse
        SELECT id_adresse INTO id_adresse_var FROM adresse WHERE adresse = adresse_entre AND id_ville_fk = id_ville_var;
        IF id_adresse_var IS NULL THEN
            INSERT INTO adresse (adresse, id_ville_fk) VALUES (adresse, id_ville_var);
            SET id_adresse_var = LAST_INSERT_ID();
        END IF;

        -- recuperer ou insérer le email
        SELECT id_email INTO id_mail_var FROM email WHERE email = mail;
        IF id_mail_var IS NULL THEN
            INSERT INTO email (email) VALUES (mail);
            SET id_mail_var = LAST_INSERT_ID();
        END IF;

        -- recuperer ou insérer le num_tel
        SELECT id_telephone INTO id_num_tel_var FROM telephone WHERE numero = num_tel;
        IF id_num_tel_var IS NULL THEN
            INSERT INTO telephone (numero) VALUES (num_tel);
            SET id_num_tel_var = LAST_INSERT_ID();
        END IF;

        -- insérer l'entreprise (si pas présente)
        SELECT id_entreprise INTO id_entreprise_var FROM entreprise WHERE nom = nom_entreprise AND id_email_fk = id_mail_var AND id_telephone_fk = id_num_tel_var AND id_adresse_fk = id_adresse_var;
        IF id_entreprise_var IS NULL THEN
            INSERT INTO entreprise (nom, logo, id_email_fk, id_telephone_fk, id_adresse_fk) VALUES (nom_entreprise, lien_logo, id_mail_var, id_num_tel_var, id_adresse_var);
        END IF;

    end;
