<?php
/**
 * add_equipment.php
 * UI to select a client, view their contracts and unassigned equipment,
 * and link selected equipment to a chosen contract.
 */
require_once 'auth.php';
requireRole('gestionnaire');
require_once __DIR__ . '/utils.php';

$step = $_GET['step'] ?? 'select_client';
$clientId = intval($_POST['client_id'] ?? 0);
$selectedContract = intval($_POST['contract_id'] ?? 0);
$selectedEquipments = $_POST['equipment'] ?? [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 'select_client' && $clientId > 0) {
        $step = 'choose_contract_and_eq';
    } elseif ($step === 'choose_contract_and_eq' && $selectedContract > 0 && !empty($selectedEquipments)) {
        // Perform the update for each selected equipment
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('UPDATE Materiel SET Numero_de_Contrat = ? WHERE Numero_de_Serie = ? AND Numero_Client = ?');
        foreach ($selectedEquipments as $serie) {
            $stmt->execute([$selectedContract, $serie, $clientId]);
        }
        $pdo->commit();
        $message = 'Le(s) matériel(s) ont été associés au contrat.';
        $step = 'done';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout de matériel à un contrat - CashCash</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard-page">
    <?php include 'components/header.php'; ?>
    
    <div class="dashboard-content">
        <div class="container">
            <div class="dashboard-title">
                <h2>Ajout de matériel à un contrat</h2>
                <p class="dashboard-subtitle">Associer du matériel à un contrat existant</p>
            </div>
            
            <?php include 'components/nav_gestionnaire.php'; ?>
            
            <div class="table-container" style="padding: 2rem;">
    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if ($step === 'select_client'): ?>
        <form method="post" action="add_equipment.php?step=select_client">
            <label for="client_id">Sélectionner le client (Numéro) :</label>
            <input type="number" id="client_id" name="client_id" required>
            <button type="submit">Continuer</button>
        </form>
    <?php elseif ($step === 'choose_contract_and_eq'): ?>
        <?php
        $client = getClient($clientId);
        if (!$client) {
            echo '<p style="color:red;">Client introuvable.</p>';
        } else {
            $contracts = getClientContracts($clientId);
            $unassigned = getUnassignedEquipment($clientId);
        ?>
        <h2>Client : <?php echo htmlspecialchars($client['Raison_Sociale']); ?> (<?php echo $clientId; ?>)</h2>
        <form method="post" action="add_equipment.php?step=choose_contract_and_eq">
            <input type="hidden" name="client_id" value="<?php echo $clientId; ?>">
            <label for="contract_id">Choisir un contrat :</label>
            <select id="contract_id" name="contract_id" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($contracts as $c): ?>
                    <option value="<?php echo $c['Numero_de_Contrat']; ?>">
                        Contrat #<?php echo $c['Numero_de_Contrat']; ?> - Échéance <?php echo $c['Date_echeance']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <h3>Matériel hors contrat disponible</h3>
            <?php if (empty($unassigned)): ?>
                <p>Aucun matériel disponible à associer.</p>
            <?php else: ?>
                <?php foreach ($unassigned as $eq): ?>
                    <div>
                        <input type="checkbox" name="equipment[]" value="<?php echo $eq['Numero_de_Serie']; ?>" id="eq_<?php echo $eq['Numero_de_Serie']; ?>">
                        <label for="eq_<?php echo $eq['Numero_de_Serie']; ?>">
                            <?php echo htmlspecialchars($eq['Reference_Interne'] . ' - Série ' . $eq['Numero_de_Serie']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <button type="submit">Lier le(s) matériel(s) au contrat</button>
        </form>
        <?php } ?>
    <?php elseif ($step === 'done'): ?>
        <p>Opération terminée.</p>
    <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
