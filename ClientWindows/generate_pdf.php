<?php
/**
 * generate_pdf.php
 * Generates a PDF listing clients with contracts expiring within the next 60 days.
 * Uses TCPDF library (must be installed in vendor/tcpdf).
 */
require_once 'auth.php';
requireRole('gestionnaire');
require_once __DIR__ . '/utils.php';

// Path to TCPDF library – adjust if needed
$tcpdfPath = __DIR__ . '/vendor/tcpdf/tcpdf.php';
if (!file_exists($tcpdfPath)) {
    die('TCPDF library not found. Please install it in vendor/tcpdf.');
}
require_once $tcpdfPath;

// Fetch contracts expiring within 60 days
$stmt = $pdo->prepare(
    'SELECT c.Numero_de_Contrat, c.Date_echeance, c.Numero_Client, cl.Raison_Sociale, cl.Adresse, cl.Telephone_Client, cl.Email
     FROM Contrat_de_maintenance c
     JOIN Client cl ON c.Numero_Client = cl.Numero_Client
     WHERE DATEDIFF(c.Date_echeance, CURDATE()) BETWEEN 0 AND 60
     ORDER BY c.Date_echeance ASC'
);
$stmt->execute();
$rows = $stmt->fetchAll();

// Create PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('CashCash');
$pdf->SetTitle('Relance - Contrats à échéance');
$pdf->SetHeaderData('', 0, 'CashCash - Relance', 'Contrats expirant dans ≤ 60 jours');
$pdf->setHeaderFont(['helvetica', '', 12]);
$pdf->setFooterFont(['helvetica', '', 10]);
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage();

// Table header
$tbl = '<table border="1" cellpadding="4"><thead><tr style="background-color:#f2f2f2;">
        <th>Raison Sociale</th>
        <th>Adresse</th>
        <th>Numéro Contrat</th>
        <th>Date Échéance</th>
        <th>Jours Restants</th>
    </tr></thead><tbody>';

foreach ($rows as $row) {
    $daysLeft = (int)date_diff(date_create($row['Date_echeance']), new DateTime())->format('%r%a');
    $tbl .= '<tr>' .
        '<td>' . htmlspecialchars($row['Raison_Sociale']) . '</td>' .
        '<td>' . htmlspecialchars($row['Adresse']) . '</td>' .
        '<td>' . $row['Numero_de_Contrat'] . '</td>' .
        '<td>' . $row['Date_echeance'] . '</td>' .
        '<td>' . $daysLeft . '</td>' .
        '</tr>';
}
$tbl .= '</tbody></table>';

$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Output('relance_contrats.pdf', 'I'); // inline display
?>
