USE CashCash;

CREATE TABLE IF NOT EXISTS Utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('gestionnaire', 'technicien') NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    matricule_employe INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    active BOOLEAN DEFAULT TRUE,
    CONSTRAINT fk_utilisateur_employe FOREIGN KEY (matricule_employe) REFERENCES Employe(Matricule) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Fiche_Intervention (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_intervention VARCHAR(50) UNIQUE NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    statut ENUM('en_attente', 'en_cours', 'terminee', 'annulee') DEFAULT 'en_attente',
    priorite ENUM('basse', 'normale', 'haute', 'urgente') DEFAULT 'normale',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_intervention DATETIME NOT NULL,
    date_cloture DATETIME NULL,
    technicien_id INT NULL,
    client_id INT NOT NULL,
    equipement_id VARCHAR(50) NULL,
    commentaire_interne TEXT,
    alerte_active BOOLEAN DEFAULT FALSE,
    alerte_message VARCHAR(500),
    created_by INT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_fiche_technicien FOREIGN KEY (technicien_id) REFERENCES Utilisateur(id) ON DELETE SET NULL,
    CONSTRAINT fk_fiche_client FOREIGN KEY (client_id) REFERENCES Client(Numero_Client) ON DELETE CASCADE,
    CONSTRAINT fk_fiche_equipement FOREIGN KEY (equipement_id) REFERENCES Materiel(Numero_de_Serie) ON DELETE SET NULL,
    CONSTRAINT fk_fiche_creator FOREIGN KEY (created_by) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

CREATE INDEX idx_utilisateur_role ON Utilisateur(role);
CREATE INDEX idx_fiche_statut ON Fiche_Intervention(statut);
CREATE INDEX idx_fiche_technicien ON Fiche_Intervention(technicien_id);
CREATE INDEX idx_fiche_date ON Fiche_Intervention(date_intervention);

INSERT INTO Utilisateur (username, password_hash, role, nom, prenom, email) VALUES
('gestionnaire', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gestionnaire', 'Dupont', 'Marie', 'marie.dupont@cashcash.fr'),
('technicien1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Martin', 'Pierre', 'pierre.martin@cashcash.fr'),
('technicien2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'Durand', 'Sophie', 'sophie.durand@cashcash.fr');

INSERT INTO Fiche_Intervention 
    (numero_intervention, titre, description, statut, priorite, date_intervention, technicien_id, client_id, created_by, alerte_active, alerte_message)
VALUES
    ('INT-2025-001', 'Maintenance préventive - Imprimante HP', 'Vérification et nettoyage de l\'imprimante', 'en_attente', 'normale', '2025-12-10 14:00:00', 2, 1, 1, FALSE, NULL),
    ('INT-2025-002', 'Réparation urgente - Scanner', 'Scanner bloqué, intervention urgente requise', 'en_cours', 'urgente', '2025-12-06 09:00:00', 2, 1, 1, TRUE, 'Attention: matériel hors contrat, facturation à prévoir'),
    ('INT-2025-003', 'Installation nouveau matériel', 'Installation et configuration', 'en_attente', 'normale', '2025-12-15 10:30:00', 3, 1, 1, FALSE, NULL);
