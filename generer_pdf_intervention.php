<?php
/**
 * generer_pdf_intervention.php
 * Génération de PDF pour les fiches d'intervention
 */

require_once 'auth.php';
requireLogin();

// Vérifier que l'utilisateur a les droits (technicien ou gestionnaire)
$user_role = getCurrentUserRole();
$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die('ID d\'intervention manquant');
}

$intervention_id = $_GET['id'];

try {
    // Récupération complète des données de l'intervention
    $stmt = $pdo->prepare("
        SELECT 
            fi.*,
            c.Raison_Sociale,
            c.Siren,
            c.Code_Ape,
            c.Adresse as client_adresse,
            c.Telephone_Client,
            c.Email as client_email,
            c.Duree_Deplacement,
            c.Distance_KM,
            CONCAT(u.prenom, ' ', u.nom) as technicien_nom,
            u.email as technicien_email,
            t.Telephone_Mobile as technicien_tel,
            t.Qualification as technicien_qualification,
            m.Numero_de_Serie,
            m.Date_de_vente,
            m.Date_d_installation,
            m.Prix_de_Vente,
            m.Emplacement,
            tm.Libelle_Type_materiel,
            cm.Numero_de_Contrat,
            cm.Date_signature,
            cm.Date_echeance,
            tc.RefTypeContrat,
            tc.DelaiIntervention,
            tc.TauxApplicable
        FROM Fiche_Intervention fi
        LEFT JOIN Client c ON fi.client_id = c.Numero_Client
        LEFT JOIN Utilisateur u ON fi.technicien_id = u.id
        LEFT JOIN Technicien t ON u.matricule_employe = t.Matricule
        LEFT JOIN Materiel m ON fi.equipement_id = m.Numero_de_Serie
        LEFT JOIN Type_Materiel tm ON m.Reference_Interne = tm.Reference_Interne
        LEFT JOIN Contrat_de_maintenance cm ON m.Numero_de_Contrat = cm.Numero_de_Contrat
        LEFT JOIN Type_Contrat tc ON cm.RefTypeContrat = tc.RefTypeContrat
        WHERE fi.id = ?
    ");
    $stmt->execute([$intervention_id]);
    $intervention = $stmt->fetch();
    
    if (!$intervention) {
        die('Intervention non trouvée');
    }
    
    // Vérifier les permissions (technicien ne peut voir que ses interventions)
    if ($user_role === 'technicien' && $intervention['technicien_id'] != $user_id) {
        die('Accès refusé');
    }
    
} catch (PDOException $e) {
    die('Erreur: ' . $e->getMessage());
}

// Chargement de TCPDF
require_once('vendor/tcpdf/tcpdf.php');

// Création d'une classe personnalisée pour l'en-tête et le pied de page
class InterventionPDF extends TCPDF {
    
    public $intervention_data;
    
    // En-tête
    public function Header() {
        // Logo
        $logo_path = __DIR__ . '/assets/logo-cashcash.png';
        if (file_exists($logo_path)) {
            $this->Image($logo_path, 15, 10, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        
        // Titre de l'entreprise
        $this->SetFont('helvetica', 'B', 20);
        $this->SetTextColor(0, 102, 204);
        $this->SetXY(60, 15);
        $this->Cell(0, 10, 'CashCash', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        
        // Sous-titre
        $this->SetFont('helvetica', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY(60, 23);
        $this->Cell(0, 5, 'Solutions de Maintenance Professionnelle', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        
        // Numéro d'intervention en haut à droite
        if (isset($this->intervention_data['numero_intervention'])) {
            $this->SetFont('helvetica', 'B', 14);
            $this->SetTextColor(0, 102, 204);
            $this->SetXY(130, 15);
            $this->Cell(0, 10, $this->intervention_data['numero_intervention'], 0, false, 'R', 0, '', 0, false, 'T', 'M');
            
            // Date d'intervention
            $this->SetFont('helvetica', '', 9);
            $this->SetTextColor(80, 80, 80);
            $this->SetXY(130, 23);
            $date_formatted = date('d/m/Y', strtotime($this->intervention_data['date_intervention']));
            $this->Cell(0, 5, 'Date: ' . $date_formatted, 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
        
        // Ligne de séparation
        $this->SetDrawColor(0, 102, 204);
        $this->SetLineWidth(0.5);
        $this->Line(15, 35, 195, 35);
        
        $this->SetY(40);
    }
    
    // Pied de page
    public function Footer() {
        $this->SetY(-20);
        
        // Ligne de séparation
        $this->SetDrawColor(0, 102, 204);
        $this->SetLineWidth(0.3);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        
        // Texte du pied de page
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        
        // Gauche : Date de génération
        $this->Cell(0, 5, 'Document généré le ' . date('d/m/Y à H:i'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        
        // Droite : Numéro de page
        $this->Cell(0, 5, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// Création du PDF
$pdf = new InterventionPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Passage des données d'intervention à la classe
$pdf->intervention_data = $intervention;

// Configuration du document
$pdf->SetCreator('CashCash');
$pdf->SetAuthor('CashCash - Système de gestion');
$pdf->SetTitle('Fiche d\'intervention - ' . $intervention['numero_intervention']);
$pdf->SetSubject('Fiche d\'intervention');

// Marges
$pdf->SetMargins(15, 45, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(15);

// Ajout d'une page
$pdf->AddPage();

// ===== TITRE PRINCIPAL =====
$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetTextColor(0, 102, 204);
$pdf->Cell(0, 10, 'FICHE D\'INTERVENTION', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
$pdf->Ln(5);

// ===== SECTION INTERVENTION =====
// Titre de section
$pdf->SetFillColor(0, 102, 204);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, '  INFORMATIONS DE L\'INTERVENTION', 0, 1, 'L', 1, '', 0, false, 'T', 'M');
$pdf->Ln(2);

// Contenu
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', '', 10);

// Titre de l'intervention
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, html_entity_decode($intervention['titre'], ENT_QUOTES, 'UTF-8'), 0, 1, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(2);

// Grille d'infos
$pdf->SetFillColor(245, 245, 245);

// Ligne 1
$pdf->Cell(45, 7, 'Statut :', 0, 0, 'L', 1);
$statut_text = ucfirst(str_replace('_', ' ', $intervention['statut']));
$pdf->Cell(45, 7, $statut_text, 0, 0, 'L', 0);
$pdf->Cell(45, 7, 'Priorité :', 0, 0, 'L', 1);
$pdf->Cell(45, 7, ucfirst($intervention['priorite']), 0, 1, 'L', 0);

// Ligne 2
$pdf->Cell(45, 7, 'Date d\'intervention :', 0, 0, 'L', 1);
$pdf->Cell(45, 7, date('d/m/Y à H:i', strtotime($intervention['date_intervention'])), 0, 0, 'L', 0);
$pdf->Cell(45, 7, 'Date de création :', 0, 0, 'L', 1);
$pdf->Cell(45, 7, date('d/m/Y à H:i', strtotime($intervention['date_creation'])), 0, 1, 'L', 0);

$pdf->Ln(2);

// Description
if (!empty($intervention['description'])) {
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 6, 'Description :', 0, 1, 'L', 0);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, html_entity_decode($intervention['description'], ENT_QUOTES, 'UTF-8'), 0, 'L', 0, 1, '', '', true, 0, false, true, 0, 'T', false);
}

$pdf->Ln(3);

// ===== ALERTE SI PRÉSENTE =====
if ($intervention['alerte_active'] && !empty($intervention['alerte_message'])) {
    $pdf->SetFillColor(255, 243, 205);
    $pdf->SetDrawColor(243, 156, 18);
    $pdf->SetTextColor(156, 90, 0);
    $pdf->SetFont('helvetica', 'B', 10);
    
    $pdf->SetLineWidth(0.5);
    $pdf->Rect(15, $pdf->GetY(), 180, 8, 'DF');
    $pdf->Cell(0, 8, '⚠ ALERTE : ' . html_entity_decode($intervention['alerte_message'], ENT_QUOTES, 'UTF-8'), 0, 1, 'C', 0);
    $pdf->Ln(3);
}

// ===== SECTION CLIENT =====
$pdf->SetFillColor(0, 102, 204);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, '  INFORMATIONS CLIENT', 0, 1, 'L', 1, '', 0, false, 'T', 'M');
$pdf->Ln(2);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(245, 245, 245);

// Raison sociale
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, html_entity_decode($intervention['Raison_Sociale'], ENT_QUOTES, 'UTF-8'), 0, 1, 'L', 0);
$pdf->SetFont('helvetica', '', 10);

// Ligne 1
if (!empty($intervention['Siren']) || !empty($intervention['Code_Ape'])) {
    $pdf->Cell(45, 6, 'SIREN :', 0, 0, 'L', 1);
    $pdf->Cell(45, 6, $intervention['Siren'] ?? 'N/A', 0, 0, 'L', 0);
    $pdf->Cell(45, 6, 'Code APE :', 0, 0, 'L', 1);
    $pdf->Cell(45, 6, $intervention['Code_Ape'] ?? 'N/A', 0, 1, 'L', 0);
}

// Adresse
if (!empty($intervention['client_adresse'])) {
    $pdf->Cell(45, 6, 'Adresse :', 0, 0, 'L', 1);
    $pdf->MultiCell(135, 6, html_entity_decode($intervention['client_adresse'], ENT_QUOTES, 'UTF-8'), 0, 'L', 0, 1, '', '', true, 0, false, true, 0, 'T', false);
}

// Contacts
if (!empty($intervention['Telephone_Client']) || !empty($intervention['client_email'])) {
    $pdf->Cell(45, 6, 'Téléphone :', 0, 0, 'L', 1);
    $pdf->Cell(45, 6, $intervention['Telephone_Client'] ?? 'N/A', 0, 0, 'L', 0);
    $pdf->Cell(45, 6, 'Email :', 0, 0, 'L', 1);
    $pdf->Cell(45, 6, $intervention['client_email'] ?? 'N/A', 0, 1, 'L', 0);
}

// Distance et durée
if (!empty($intervention['Distance_KM']) || !empty($intervention['Duree_Deplacement'])) {
    $pdf->Cell(45, 6, 'Distance :', 0, 0, 'L', 1);
    $pdf->Cell(45, 6, ($intervention['Distance_KM'] ?? 'N/A') . ' km', 0, 0, 'L', 0);
    $pdf->Cell(45, 6, 'Durée de déplacement :', 0, 0, 'L', 1);
    $pdf->Cell(45, 6, ($intervention['Duree_Deplacement'] ?? 'N/A') . ' min', 0, 1, 'L', 0);
}

$pdf->Ln(3);

// ===== SECTION TECHNICIEN =====
if (!empty($intervention['technicien_nom'])) {
    $pdf->SetFillColor(0, 102, 204);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 8, '  TECHNICIEN ASSIGNÉ', 0, 1, 'L', 1, '', 0, false, 'T', 'M');
    $pdf->Ln(2);
    
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFillColor(245, 245, 245);
    
    $pdf->Cell(45, 6, 'Nom :', 0, 0, 'L', 1);
    $pdf->Cell(135, 6, html_entity_decode($intervention['technicien_nom'], ENT_QUOTES, 'UTF-8'), 0, 1, 'L', 0);
    
    if (!empty($intervention['technicien_qualification'])) {
        $pdf->Cell(45, 6, 'Qualification :', 0, 0, 'L', 1);
        $pdf->Cell(135, 6, html_entity_decode($intervention['technicien_qualification'], ENT_QUOTES, 'UTF-8'), 0, 1, 'L', 0);
    }
    
    if (!empty($intervention['technicien_tel']) || !empty($intervention['technicien_email'])) {
        $pdf->Cell(45, 6, 'Téléphone :', 0, 0, 'L', 1);
        $pdf->Cell(45, 6, $intervention['technicien_tel'] ?? 'N/A', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'Email :', 0, 0, 'L', 1);
        $pdf->Cell(45, 6, $intervention['technicien_email'] ?? 'N/A', 0, 1, 'L', 0);
    }
    
    $pdf->Ln(3);
}

// ===== SECTION ÉQUIPEMENT =====
if (!empty($intervention['Numero_de_Serie'])) {
    $pdf->SetFillColor(0, 102, 204);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 8, '  ÉQUIPEMENT CONCERNÉ', 0, 1, 'L', 1, '', 0, false, 'T', 'M');
    $pdf->Ln(2);
    
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFillColor(245, 245, 245);
    
    // Type et numéro de série
    $pdf->Cell(45, 6, 'Type de matériel :', 0, 0, 'L', 1);
    $pdf->Cell(135, 6, html_entity_decode($intervention['Libelle_Type_materiel'] ?? 'N/A', ENT_QUOTES, 'UTF-8'), 0, 1, 'L', 0);
    
    $pdf->Cell(45, 6, 'Numéro de série :', 0, 0, 'L', 1);
    $pdf->Cell(135, 6, $intervention['Numero_de_Serie'], 0, 1, 'L', 0);
    
    // Emplacement
    if (!empty($intervention['Emplacement'])) {
        $pdf->Cell(45, 6, 'Emplacement :', 0, 0, 'L', 1);
        $pdf->Cell(135, 6, html_entity_decode($intervention['Emplacement'], ENT_QUOTES, 'UTF-8'), 0, 1, 'L', 0);
    }
    
    // Dates et prix
    if (!empty($intervention['Date_de_vente']) || !empty($intervention['Date_d_installation'])) {
        $pdf->Cell(45, 6, 'Date de vente :', 0, 0, 'L', 1);
        $pdf->Cell(45, 6, $intervention['Date_de_vente'] ? date('d/m/Y', strtotime($intervention['Date_de_vente'])) : 'N/A', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'Date d\'installation :', 0, 0, 'L', 1);
        $pdf->Cell(45, 6, $intervention['Date_d_installation'] ? date('d/m/Y', strtotime($intervention['Date_d_installation'])) : 'N/A', 0, 1, 'L', 0);
    }
    
    if (!empty($intervention['Prix_de_Vente'])) {
        $pdf->Cell(45, 6, 'Prix de vente :', 0, 0, 'L', 1);
        $pdf->Cell(135, 6, number_format($intervention['Prix_de_Vente'], 2, ',', ' ') . ' €', 0, 1, 'L', 0);
    }
    
    $pdf->Ln(3);
}

// ===== SECTION CONTRAT =====
if (!empty($intervention['Numero_de_Contrat'])) {
    $pdf->SetFillColor(0, 102, 204);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 8, '  CONTRAT DE MAINTENANCE', 0, 1, 'L', 1, '', 0, false, 'T', 'M');
    $pdf->Ln(2);
    
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFillColor(245, 245, 245);
    
    $pdf->Cell(45, 6, 'Numéro de contrat :', 0, 0, 'L', 1);
    $pdf->Cell(135, 6, $intervention['Numero_de_Contrat'], 0, 1, 'L', 0);
    
    if (!empty($intervention['RefTypeContrat'])) {
        $pdf->Cell(45, 6, 'Type de contrat :', 0, 0, 'L', 1);
        $pdf->Cell(135, 6, $intervention['RefTypeContrat'], 0, 1, 'L', 0);
    }
    
    if (!empty($intervention['Date_signature']) || !empty($intervention['Date_echeance'])) {
        $pdf->Cell(45, 6, 'Date de signature :', 0, 0, 'L', 1);
        $pdf->Cell(45, 6, $intervention['Date_signature'] ? date('d/m/Y', strtotime($intervention['Date_signature'])) : 'N/A', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'Date d\'échéance :', 0, 0, 'L', 1);
        
        $echeance_text = 'N/A';
        $echeance_color = [0, 0, 0];
        if ($intervention['Date_echeance']) {
            $echeance_text = date('d/m/Y', strtotime($intervention['Date_echeance']));
            // Vérifier si le contrat est expiré
            if (strtotime($intervention['Date_echeance']) < time()) {
                $echeance_color = [220, 53, 69]; // Rouge
                $echeance_text .= ' (EXPIRÉ)';
            }
        }
        $pdf->SetTextColor($echeance_color[0], $echeance_color[1], $echeance_color[2]);
        $pdf->Cell(45, 6, $echeance_text, 0, 1, 'L', 0);
        $pdf->SetTextColor(0, 0, 0);
    }
    
    if (!empty($intervention['DelaiIntervention'])) {
        $pdf->Cell(45, 6, 'Délai d\'intervention :', 0, 0, 'L', 1);
        $pdf->Cell(135, 6, $intervention['DelaiIntervention'] . ' jours', 0, 1, 'L', 0);
    }
    
    if (!empty($intervention['TauxApplicable'])) {
        $pdf->Cell(45, 6, 'Taux applicable :', 0, 0, 'L', 1);
        $pdf->Cell(135, 6, $intervention['TauxApplicable'] . ' %', 0, 1, 'L', 0);
    }
    
    $pdf->Ln(3);
}

// ===== SECTION OBSERVATIONS =====
$pdf->SetFillColor(245, 245, 245);
$pdf->SetTextColor(0, 102, 204);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, '  OBSERVATIONS ET TRAVAUX RÉALISÉS', 0, 1, 'L', 1, '', 0, false, 'T', 'M');
$pdf->Ln(2);

$pdf->SetTextColor(80, 80, 80);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->MultiCell(0, 5, 'Cette section est à compléter par le technicien lors de l\'intervention.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0, 'T', false);
$pdf->Ln(1);

// Cadre pour les observations
$pdf->SetDrawColor(200, 200, 200);
$pdf->SetLineWidth(0.3);
$pdf->Rect(15, $pdf->GetY(), 180, 40, 'D');
$pdf->Ln(42);

// ===== SECTION SIGNATURE =====
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetTextColor(0, 0, 0);

// Deux colonnes pour les signatures
$y_position = $pdf->GetY();

// Signature technicien
$pdf->SetXY(15, $y_position);
$pdf->Cell(85, 6, 'Signature du technicien', 0, 1, 'L', 0);
$pdf->SetXY(15, $y_position + 8);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(85, 5, 'Date : ___/___/______', 0, 1, 'L', 0);
$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(15, $y_position + 25, 80, $y_position + 25);

// Signature client
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetXY(110, $y_position);
$pdf->Cell(85, 6, 'Signature du client', 0, 1, 'R', 0);
$pdf->SetXY(110, $y_position + 8);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(85, 5, 'Date : ___/___/______', 0, 1, 'R', 0);
$pdf->Line(130, $y_position + 25, 195, $y_position + 25);

// Génération du PDF
$filename = 'Fiche_Intervention_' . $intervention['numero_intervention'] . '_' . date('Ymd') . '.pdf';
$pdf->Output($filename, 'D'); // 'D' pour forcer le téléchargement

exit;
?>
