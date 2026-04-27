<?php
require_once __DIR__ . '/config.php';

function getClient(int $clientId): ?array {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM Client WHERE Numero_Client = ?');
    $stmt->execute([$clientId]);
    $client = $stmt->fetch();
    return $client ?: null;
}

function getClientContracts(int $clientId): array {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM Contrat_de_maintenance WHERE Numero_Client = ? ORDER BY Date_echeance ASC');
    $stmt->execute([$clientId]);
    return $stmt->fetchAll();
}

function getUnassignedEquipment(int $clientId): array {
    global $pdo;
    $stmt = $pdo->prepare('SELECT m.* FROM Materiel m WHERE m.Numero_Client = ? AND (m.Numero_de_Contrat IS NULL OR m.Numero_de_Contrat = 0)');
    $stmt->execute([$clientId]);
    return $stmt->fetchAll();
}

function getAssignedEquipment(int $clientId): array {
    global $pdo;
    $stmt = $pdo->prepare('SELECT m.* FROM Materiel m WHERE m.Numero_Client = ? AND m.Numero_de_Contrat IS NOT NULL AND m.Numero_de_Contrat <> 0');
    $stmt->execute([$clientId]);
    return $stmt->fetchAll();
}

function getContractWithDays(int $contractId): ?array {
    global $pdo;
    $stmt = $pdo->prepare('SELECT c.*, DATEDIFF(c.Date_echeance, CURDATE()) AS days_before FROM Contrat_de_maintenance c WHERE c.Numero_de_Contrat = ?');
    $stmt->execute([$contractId]);
    $contract = $stmt->fetch();
    return $contract ?: null;
}

function xmlEscape(string $value): string {
    return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
}

function generateClientEquipmentXML(int $clientId): string {
    $client = getClient($clientId);
    if (!$client) {
        return '';
    }
    $assigned = getAssignedEquipment($clientId);
    $unassigned = getUnassignedEquipment($clientId);

    $xml = new SimpleXMLElement('<client/>');
    $xml->addAttribute('numero', $clientId);
    $xml->addChild('raison_sociale', xmlEscape($client['Raison_Sociale'] ?? ''));

    $under = $xml->addChild('sousContrat');
    foreach ($assigned as $eq) {
        $item = $under->addChild('materiel');
        $item->addChild('numSerie', xmlEscape($eq['Numero_de_Serie'] ?? ''));
        $item->addChild('refInterne', xmlEscape($eq['Reference_Interne'] ?? ''));
        $item->addChild('libelle', xmlEscape($eq['Reference_Interne'] ?? ''));
        $item->addChild('date_vente', xmlEscape($eq['Date_de_vente'] ?? ''));
        $item->addChild('date_installation', xmlEscape($eq['Date_d_installation'] ?? ''));
        $item->addChild('prix_vente', xmlEscape($eq['Prix_de_Vente'] ?? ''));
        $item->addChild('emplacement', xmlEscape($eq['Emplacement'] ?? ''));
        $contract = getContractWithDays($eq['Numero_de_Contrat']);
        $item->addChild('nbJourAvantEcheance', $contract ? $contract['days_before'] : '');
    }

    $outside = $xml->addChild('horsContrat');
    foreach ($unassigned as $eq) {
        $item = $outside->addChild('materiel');
        $item->addChild('numSerie', xmlEscape($eq['Numero_de_Serie'] ?? ''));
        $item->addChild('refInterne', xmlEscape($eq['Reference_Interne'] ?? ''));
        $item->addChild('libelle', xmlEscape($eq['Reference_Interne'] ?? ''));
        $item->addChild('date_vente', xmlEscape($eq['Date_de_vente'] ?? ''));
        $item->addChild('date_installation', xmlEscape($eq['Date_d_installation'] ?? ''));
        $item->addChild('prix_vente', xmlEscape($eq['Prix_de_Vente'] ?? ''));
        $item->addChild('emplacement', xmlEscape($eq['Emplacement'] ?? ''));
    }

    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->formatOutput = true;
    return $dom->saveXML();
}
?>
