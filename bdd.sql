create table competences
(
    id_competence int auto_increment
        primary key,
    competence    varchar(255) not null,
    constraint Competences_unique
        unique (competence)
);

create table contact
(
    id_contact int auto_increment
        primary key,
    email      varchar(255) not null,
    telephone  varchar(20)  null
);

create table contrat
(
    id_contrat  int auto_increment
        primary key,
    nom_contrat varchar(50) not null,
    constraint contrat_unique
        unique (nom_contrat)
);

create table groupe
(
    id_groupe  int auto_increment
        primary key,
    nom_groupe varchar(100) not null,
    constraint Groupe_unique
        unique (nom_groupe)
);

create table pays
(
    id_pays  int auto_increment
        primary key,
    nom_pays varchar(56) not null,
    constraint nom_pays
        unique (nom_pays)
);

create table departement
(
    id_departement int auto_increment
        primary key,
    departement    varchar(50) not null,
    id_pays_fk     int         not null,
    constraint departement_pays_id_pays_fk
        foreign key (id_pays_fk) references pays (id_pays)
);

create table unite_temps
(
    id_unite  int auto_increment
        primary key,
    nom_unite varchar(50) not null,
    nb_jours  int         not null
);

create table villes
(
    id_ville          int auto_increment
        primary key,
    nom_ville         varchar(100) not null,
    id_departement_fk int          not null,
    constraint Villes_pays_id_pays_fk
        foreign key (id_departement_fk) references departement (id_departement)
);

create table adresse
(
    id_adresse  int auto_increment
        primary key,
    adresse     varchar(255) not null,
    id_ville_fk int          not null,
    constraint Adresse_villes_id_ville_fk
        foreign key (id_ville_fk) references villes (id_ville)
);

create table entreprise
(
    id_entreprise int auto_increment
        primary key,
    nom           varchar(255)         not null,
    logo          varchar(255)         null,
    descriptif    text                 null,
    nb_employe    int                  null,
    visible       tinyint(1) default 0 not null,
    id_adresse_fk int                  not null,
    id_contact_fk int                  not null,
    constraint Entreprise_adresse_id_adresse_fk
        foreign key (id_adresse_fk) references adresse (id_adresse),
    constraint Entreprise_email_id_email_fk
        foreign key (id_contact_fk) references contact (id_contact)
);

create table offre
(
    id_offre                  int auto_increment
        primary key,
    titre                     varchar(255)         not null,
    date_creation             date                 not null,
    duree                     int                  null,
    descriptif                text                 not null,
    domaine                   varchar(100)         not null,
    visible                   tinyint(1) default 0 not null,
    Pause                     tinyint(1) default 0 not null,
    id_contrat_fk             int                  not null,
    id_unite_duree_fk         int                  null,
    id_adresse_fk             int                  null,
    id_entreprise_fk          int                  not null,
    id_contact_recrutement_fk int                  not null,
    constraint offre_adresse_id_adresse_fk
        foreign key (id_adresse_fk) references adresse (id_adresse),
    constraint offre_contact_id_contact_fk
        foreign key (id_contact_recrutement_fk) references contact (id_contact),
    constraint offre_contrat_id_contrat_fk
        foreign key (id_contrat_fk) references contrat (id_contrat),
    constraint offre_entreprise_id_entreprise_fk
        foreign key (id_entreprise_fk) references entreprise (id_entreprise),
    constraint offre_unite_temps_id_unite_fk
        foreign key (id_unite_duree_fk) references unite_temps (id_unite)
);

create table competence_offre
(
    id_offre_fk      int not null,
    id_competence_fk int not null,
    constraint competence_offre_competences_id_competence_fk
        foreign key (id_competence_fk) references competences (id_competence),
    constraint competence_offre_offre_id_offre_fk
        foreign key (id_offre_fk) references offre (id_offre)
);

create table utilisateur
(
    id_utilisateur  int auto_increment
        primary key,
    nom             varchar(100)         not null,
    prenom          varchar(50)          not null,
    mot_de_passe    varchar(255)         not null,
    id_permission   int                  not null,
    approuve        tinyint(1) default 0 not null,
    id_contact_fk   int                  not null,
    id_entrprise_fk int                  null,
    constraint utilisateur_contact_id_contact_fk
        foreign key (id_contact_fk) references contact (id_contact),
    constraint utilisateur_entreprise_id_entreprise_fk
        foreign key (id_entrprise_fk) references entreprise (id_entreprise)
);

create table candidature
(
    id_candidature    int auto_increment
        primary key,
    cv                varchar(255) not null,
    lettre_motivation text         not null,
    date_candidature  datetime     not null,
    id_utilisateur_fk int          not null,
    id_offre_fk       int          not null,
    constraint candidature_offre_id_offre_fk
        foreign key (id_offre_fk) references offre (id_offre),
    constraint candidature_utilisateur_id_utilisateur_fk
        foreign key (id_utilisateur_fk) references utilisateur (id_utilisateur)
);

create table groupe_utilisateur
(
    id_utilisateur_fk int not null,
    id_groupe_fk      int not null,
    constraint groupe_utilisateur_groupe_id_groupe_fk
        foreign key (id_groupe_fk) references groupe (id_groupe),
    constraint groupe_utilisateur_utilisateur_id_utilisateur_fk
        foreign key (id_utilisateur_fk) references utilisateur (id_utilisateur)
);

create table note_entreprise
(
    id_utilisateur_fk int not null,
    id_entreprise_fk  int not null,
    note              int not null,
    constraint note_entreprise_entreprise_id_entreprise_fk
        foreign key (id_entreprise_fk) references entreprise (id_entreprise),
    constraint note_entreprise_utilisateur_id_utilisateur_fk
        foreign key (id_utilisateur_fk) references utilisateur (id_utilisateur)
);

create table whishlist
(
    id_offre_fk       int not null,
    id_utilisateur_fk int not null,
    constraint Whishlist_offre_id_offre_fk
        foreign key (id_offre_fk) references offre (id_offre),
    constraint Whishlist_utilisateur_id_utilisateur_fk
        foreign key (id_utilisateur_fk) references utilisateur (id_utilisateur)
);

create index idx_wishlist_offre_user
    on whishlist (id_offre_fk, id_utilisateur_fk);
