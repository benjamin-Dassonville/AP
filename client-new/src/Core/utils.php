<?php
require_once dirname(__DIR__, 2) . '/config.php';

function getClient(int $clientId): ?array {
    $repo = new ClientRepository();
    return $repo->getById($clientId);
}

function getClientContracts(int $clientId): array {
    $repo = new ContratRepository();
    return $repo->getClientContracts($clientId);
}

function getUnassignedEquipment(int $clientId): array {
    $repo = new MaterielRepository();
    return $repo->getUnassignedEquipment($clientId);
}

function getAssignedEquipment(int $clientId): array {
    $repo = new MaterielRepository();
    return $repo->getAssignedEquipment($clientId);
}

function getContractWithDays(int $contractId): ?array {
    $repo = new ContratRepository();
    return $repo->getContractWithDays($contractId);
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
