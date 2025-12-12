CREATE TABLE Type_Materiel (
    Reference_Interne VARCHAR(50) PRIMARY KEY,
    Libelle_Type_materiel VARCHAR(255) NOT NULL
);

CREATE TABLE Materiel (
    Numero_de_Serie VARCHAR(50) PRIMARY KEY,
    Date_de_vente DATE NOT NULL,
    Date_d_installation DATE,
    Prix_de_Vente DECIMAL(10,2),
    Emplacement VARCHAR(255),
    Reference_Interne VARCHAR(50) NOT NULL,
    CONSTRAINT fk_materiel_type FOREIGN KEY (Reference_Interne) REFERENCES Type_Materiel(Reference_Interne)
);

CREATE TABLE Client (
    Numero_Client INT PRIMARY KEY AUTO_INCREMENT,
    Raison_Sociale VARCHAR(255) NOT NULL,
    Siren VARCHAR(14),
    Code_Ape VARCHAR(10),
    Adresse TEXT,
    Telephone_Client VARCHAR(20),
    Email VARCHAR(255),
    Duree_Deplacement INT,
    Distance_KM DECIMAL(6,2)
);

CREATE TABLE Contrat_de_maintenance (
    Numero_de_Contrat INT PRIMARY KEY AUTO_INCREMENT,
    Date_signature DATE NOT NULL,
    Date_echeance DATE NOT NULL,
    Numero_Client INT NOT NULL,
    CONSTRAINT fk_contrat_client FOREIGN KEY (Numero_Client) REFERENCES Client(Numero_Client)
);

CREATE TABLE Type_Contrat (
    RefTypeContrat VARCHAR(50) PRIMARY KEY,
    DelaiIntervention INT,
    TauxApplicable DECIMAL(5,2)
);

ALTER TABLE Contrat_de_maintenance
ADD COLUMN RefTypeContrat VARCHAR(50),
ADD CONSTRAINT fk_contrat_type FOREIGN KEY (RefTypeContrat) REFERENCES Type_Contrat(RefTypeContrat);

CREATE TABLE Agence (
    Numero_Agence INT PRIMARY KEY AUTO_INCREMENT,
    Nom_agence VARCHAR(255) NOT NULL,
    Adresse_agence TEXT,
    Telephone_Agence VARCHAR(20)
);

CREATE TABLE Employe (
    Matricule INT PRIMARY KEY AUTO_INCREMENT,
    Nom_Employe VARCHAR(255) NOT NULL,
    Prenom_Employe VARCHAR(255),
    Adresse_Employe TEXT,
    Date_Embauche DATE NOT NULL
);

CREATE TABLE Technicien (
    Matricule INT PRIMARY KEY,
    Telephone_Mobile VARCHAR(20),
    Qualification VARCHAR(255),
    Date_Obtention DATE,
    CONSTRAINT fk_technicien_employe FOREIGN KEY (Matricule) REFERENCES Employe(Matricule)
);

CREATE TABLE Intervention (
    Numero_Intervent INT PRIMARY KEY AUTO_INCREMENT,
    Date_Visite DATE NOT NULL,
    Heure_Visite TIME NOT NULL,
    Matricule_Technicien INT NOT NULL,
    Numero_Client INT NOT NULL,
    Numero_de_Serie VARCHAR(50) NOT NULL,
    Temps_Passe INT,
    Commentaire TEXT,
    CONSTRAINT fk_intervention_technicien FOREIGN KEY (Matricule_Technicien) REFERENCES Technicien(Matricule),
    CONSTRAINT fk_intervention_client FOREIGN KEY (Numero_Client) REFERENCES Client(Numero_Client),
    CONSTRAINT fk_intervention_materiel FOREIGN KEY (Numero_de_Serie) REFERENCES Materiel(Numero_de_Serie)
);

ALTER TABLE Client
ADD COLUMN Numero_Agence INT,
ADD CONSTRAINT fk_client_agence FOREIGN KEY (Numero_Agence) REFERENCES Agence(Numero_Agence);

ALTER TABLE Technicien
ADD COLUMN Numero_Agence INT,
ADD CONSTRAINT fk_technicien_agence FOREIGN KEY (Numero_Agence) REFERENCES Agence(Numero_Agence);

ALTER TABLE Materiel
ADD COLUMN Numero_Client INT,
ADD CONSTRAINT fk_materiel_client FOREIGN KEY (Numero_Client) REFERENCES Client(Numero_Client);

ALTER TABLE Materiel
ADD COLUMN Numero_de_Contrat INT,
ADD CONSTRAINT fk_materiel_contrat FOREIGN KEY (Numero_de_Contrat) REFERENCES Contrat_de_maintenance(Numero_de_Contrat);

CREATE TABLE Controler (
    Numero_Intervent INT,
    Numero_de_Serie VARCHAR(50),
    Temps_Passe INT,
    Commentaire TEXT,
    PRIMARY KEY (Numero_Intervent, Numero_de_Serie),
    CONSTRAINT fk_controler_intervention FOREIGN KEY (Numero_Intervent) REFERENCES Intervention(Numero_Intervent),
    CONSTRAINT fk_controler_materiel FOREIGN KEY (Numero_de_Serie) REFERENCES Materiel(Numero_de_Serie)
);