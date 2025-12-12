<?php
require_once 'auth.php';
requireRole('gestionnaire');
require_once __DIR__ . '/utils.php';

$xmlOutput = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = intval($_POST['numero_client'] ?? 0);
    if ($clientId <= 0) {
        $error = 'Veuillez saisir un numéro de client valide.';
    } else {
        $xmlOutput = generateClientEquipmentXML($clientId);
        if ($xmlOutput === '') {
            $error = "Aucun client trouvé avec le numéro $clientId.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur XML - CashCash</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard-page">
    <?php include 'components/header.php'; ?>
    
    <div class="dashboard-content">
        <div class="container">
            <div class="dashboard-title">
                <h2>Générateur XML</h2>
                <p class="dashboard-subtitle">Générer un fichier XML des équipements par client</p>
            </div>
            
            <?php include 'components/nav_gestionnaire.php'; ?>
            
            <div class="table-container" style="padding: 2rem;">
    <form method="post" action="generate_xml.php">
        <label for="numero_client">Numéro du client :</label>
        <input type="number" id="numero_client" name="numero_client" required>
        <button type="submit">Générer XML</button>
    </form>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($xmlOutput): ?>
        <h2>Résultat XML</h2>
        <pre style="background:#f0f0f0;padding:1rem;overflow:auto;"><?php echo htmlspecialchars($xmlOutput); ?></pre>
        <form method="post" action="generate_xml.php?download=1">
            <input type="hidden" name="numero_client" value="<?php echo $clientId; ?>">
            <button type="submit">Télécharger le XML</button>
        </form>
    <?php endif; ?>
            </div>
        </div>
    </div>
<?php
if (isset($_GET['download']) && $xmlOutput) {
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="client_' . $clientId . '.xml"');
    echo $xmlOutput;
    exit;
}
?>
</body>
</html>
