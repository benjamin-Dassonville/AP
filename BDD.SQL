-- Création de la base de données (optionnel, à adapter selon votre SGBD)
-- CREATE DATABASE CashCash;
-- USE CashCash;

-- 1. Table Type Matériel
CREATE TABLE Type_Materiel (
    Reference_Interne VARCHAR(50) PRIMARY KEY,
    Libelle_Type_materiel VARCHAR(255) NOT NULL
);

-- 2. Table Matériel
CREATE TABLE Materiel (
    Numero_de_Serie VARCHAR(50) PRIMARY KEY,
    Date_de_vente DATE NOT NULL,
    Date_d_installation DATE,
    Prix_de_Vente DECIMAL(10,2),
    Emplacement VARCHAR(255),
    Reference_Interne VARCHAR(50) NOT NULL,
    CONSTRAINT fk_materiel_type FOREIGN KEY (Reference_Interne) REFERENCES Type_Materiel(Reference_Interne)
);

-- 3. Table Client
CREATE TABLE Client (
    Numero_Client INT PRIMARY KEY AUTO_INCREMENT,
    Raison_Sociale VARCHAR(255) NOT NULL,
    Siren VARCHAR(14),
    Code_Ape VARCHAR(10),
    Adresse TEXT,
    Telephone_Client VARCHAR(20),
    Email VARCHAR(255),
    Duree_Deplacement INT, -- en minutes ou heures ?
    Distance_KM DECIMAL(6,2)
);

-- 4. Table Contrat de maintenance
CREATE TABLE Contrat_de_maintenance (
    Numero_de_Contrat INT PRIMARY KEY AUTO_INCREMENT,
    Date_signature DATE NOT NULL,
    Date_echeance DATE NOT NULL,
    Numero_Client INT NOT NULL,
    CONSTRAINT fk_contrat_client FOREIGN KEY (Numero_Client) REFERENCES Client(Numero_Client)
);

-- 5. Table Type Contrat
CREATE TABLE Type_Contrat (
    RefTypeContrat VARCHAR(50) PRIMARY KEY,
    DelaiIntervention INT, -- en jours ?
    TauxApplicable DECIMAL(5,2)
);

-- 6. Table Caractériser (relation entre Contrat de maintenance et Type Contrat)
-- Relation 1,1 - 0,n : un contrat a un type, un type peut être associé à plusieurs contrats.
-- On ajoute la clé étrangère dans la table Contrat_de_maintenance.
ALTER TABLE Contrat_de_maintenance
ADD COLUMN RefTypeContrat VARCHAR(50),
ADD CONSTRAINT fk_contrat_type FOREIGN KEY (RefTypeContrat) REFERENCES Type_Contrat(RefTypeContrat);

-- 7. Table Agence
CREATE TABLE Agence (
    Numero_Agence INT PRIMARY KEY AUTO_INCREMENT,
    Nom_agence VARCHAR(255) NOT NULL,
    Adresse_agence TEXT,
    Telephone_Agence VARCHAR(20)
);

-- 8. Table Employé
CREATE TABLE Employe (
    Matricule INT PRIMARY KEY AUTO_INCREMENT,
    Nom_Employe VARCHAR(255) NOT NULL,
    Prenom_Employe VARCHAR(255),
    Adresse_Employe TEXT,
    Date_Embauche DATE NOT NULL
);

-- 9. Table Technicien (sous-type de Employé)
-- Ici, on utilise une relation d'héritage : Technicien hérite de Employé.
-- La clé primaire de Technicien est aussi sa clé étrangère vers Employé.
CREATE TABLE Technicien (
    Matricule INT PRIMARY KEY,
    Telephone_Mobile VARCHAR(20),
    Qualification VARCHAR(255),
    Date_Obtention DATE,
    CONSTRAINT fk_technicien_employe FOREIGN KEY (Matricule) REFERENCES Employe(Matricule)
);

-- 10. Table Intervention
CREATE TABLE Intervention (
    Numero_Intervent INT PRIMARY KEY AUTO_INCREMENT,
    Date_Visite DATE NOT NULL,
    Heure_Visite TIME NOT NULL,
    Matricule_Technicien INT NOT NULL,
    Numero_Client INT NOT NULL,
    Numero_de_Serie VARCHAR(50) NOT NULL,
    Temps_Passe INT, -- en minutes ?
    Commentaire TEXT,
    CONSTRAINT fk_intervention_technicien FOREIGN KEY (Matricule_Technicien) REFERENCES Technicien(Matricule),
    CONSTRAINT fk_intervention_client FOREIGN KEY (Numero_Client) REFERENCES Client(Numero_Client),
    CONSTRAINT fk_intervention_materiel FOREIGN KEY (Numero_de_Serie) REFERENCES Materiel(Numero_de_Serie)
);

-- 11. Table Signer (relation entre Client et Intervention)
-- Relation 0,n - 1,1 : un client peut signer plusieurs interventions, une intervention est signée par un seul client.
-- Cette relation est déjà gérée par la clé étrangère Numero_Client dans la table Intervention.

-- 12. Table Gérer (relation entre Client et Agence)
-- Relation 1,1 - 1,n : un client est géré par une agence, une agence gère plusieurs clients.
-- Ajout de la clé étrangère dans la table Client.
ALTER TABLE Client
ADD COLUMN Numero_Agence INT,
ADD CONSTRAINT fk_client_agence FOREIGN KEY (Numero_Agence) REFERENCES Agence(Numero_Agence);

-- 13. Table Travailler (relation entre Technicien et Agence)
-- Relation 1,1 - 1,n : un technicien travaille pour une agence, une agence a plusieurs techniciens.
-- Ajout de la clé étrangère dans la table Technicien.
ALTER TABLE Technicien
ADD COLUMN Numero_Agence INT,
ADD CONSTRAINT fk_technicien_agence FOREIGN KEY (Numero_Agence) REFERENCES Agence(Numero_Agence);

-- 14. Table Posséder (relation entre Client et Matériel)
-- Relation 1,n - 1,1 : un client possède plusieurs matériels, un matériel est possédé par un seul client.
-- Ajout de la clé étrangère dans la table Materiel.
ALTER TABLE Materiel
ADD COLUMN Numero_Client INT,
ADD CONSTRAINT fk_materiel_client FOREIGN KEY (Numero_Client) REFERENCES Client(Numero_Client);

-- 15. Table Couvrir (relation entre Contrat de maintenance et Matériel)
-- Relation 1,n - 0,1 : un contrat couvre plusieurs matériels, un matériel peut être couvert par 0 ou 1 contrat.
-- Ajout de la clé étrangère dans la table Materiel.
ALTER TABLE Materiel
ADD COLUMN Numero_de_Contrat INT,
ADD CONSTRAINT fk_materiel_contrat FOREIGN KEY (Numero_de_Contrat) REFERENCES Contrat_de_maintenance(Numero_de_Contrat);

-- 16. Table Controler (relation entre Intervention et Matériel)
-- Relation 1,n - 0,n : une intervention contrôle plusieurs matériels, un matériel peut être contrôlé par plusieurs interventions.
-- Cette relation nécessite une table d'association.
CREATE TABLE Controler (
    Numero_Intervent INT,
    Numero_de_Serie VARCHAR(50),
    Temps_Passe INT, -- redondant avec Intervention ? À vérifier selon la logique métier.
    Commentaire TEXT,
    PRIMARY KEY (Numero_Intervent, Numero_de_Serie),
    CONSTRAINT fk_controler_intervention FOREIGN KEY (Numero_Intervent) REFERENCES Intervention(Numero_Intervent),
    CONSTRAINT fk_controler_materiel FOREIGN KEY (Numero_de_Serie) REFERENCES Materiel(Numero_de_Serie)
);

-- Remarque : La relation "Controler" est représentée comme une association avec des attributs.
-- Si "Temps_Passe" et "Commentaire" sont spécifiques à chaque contrôle d'un matériel lors d'une intervention,
-- alors cette table d'association est nécessaire.
-- Sinon, si ces attributs sont liés à l'intervention globale, ils peuvent rester dans la table Intervention.

-- Fin du script