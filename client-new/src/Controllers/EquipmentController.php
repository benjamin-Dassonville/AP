<?php

class EquipmentController {
    public function handleRequest($get, $post) {
        AuthManager::requireRole('gestionnaire');

        $step = $get['step'] ?? 'select_client';
        $clientId = intval($post['client_id'] ?? ($get['client_id'] ?? 0));
        $selectedContract = intval($post['contract_id'] ?? 0);
        $selectedEquipments = $post['equipment'] ?? [];
        $message = '';
        $error = '';
        $clients = [];
        $client = null;
        $contracts = [];
        $unassigned = [];
        $assigned = [];

        // Charger la liste des clients pour l'étape 1
        $clientRepo = new ClientRepository();
        $clients = $clientRepo->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($step === 'select_client' && $clientId > 0) {
                $client = $clientRepo->getById($clientId);
                if ($client) {
                    $step = 'choose_contract_and_eq';
                } else {
                    $error = "Client N°$clientId introuvable.";
                    $step = 'select_client';
                }
            } elseif ($step === 'choose_contract_and_eq' && $selectedContract > 0 && !empty($selectedEquipments)) {
                try {
                    $db = Database::getInstance()->getConnection();
                    $db->beginTransaction();
                    $stmt = $db->prepare('UPDATE Materiel SET Numero_de_Contrat = ? WHERE Numero_de_Serie = ? AND Numero_Client = ?');
                    $count = 0;
                    foreach ($selectedEquipments as $serie) {
                        $stmt->execute([$selectedContract, $serie, $clientId]);
                        $count++;
                    }
                    $db->commit();
                    $message = "$count matériel(s) associé(s) au contrat N°$selectedContract avec succès !";
                    $step = 'done';
                } catch (PDOException $e) {
                    $error = "Erreur lors de l'association : " . $e->getMessage();
                    $step = 'choose_contract_and_eq';
                }
            } elseif ($step === 'choose_contract_and_eq' && $selectedContract > 0 && empty($selectedEquipments)) {
                $error = "Veuillez sélectionner au moins un matériel à associer.";
            }
        }

        // Charger les données client si on est à l'étape 2
        if ($step === 'choose_contract_and_eq' && $clientId > 0) {
            if (!$client) {
                $client = $clientRepo->getById($clientId);
            }
            if ($client) {
                $contracts = getClientContracts($clientId);
                $unassigned = getUnassignedEquipment($clientId);
                $assigned = getAssignedEquipment($clientId);
            }
        }

        // Recharger les infos client pour l'étape done
        if ($step === 'done' && $clientId > 0 && !$client) {
            $client = $clientRepo->getById($clientId);
        }

        return [
            'step' => $step,
            'clientId' => $clientId,
            'selectedContract' => $selectedContract,
            'selectedEquipments' => $selectedEquipments,
            'message' => $message,
            'error' => $error,
            'clients' => $clients,
            'client' => $client,
            'contracts' => $contracts,
            'unassigned' => $unassigned,
            'assigned' => $assigned
        ];
    }
}
