<?php
/**
 * add_equipment.php
 * Interface d'association de matériel à un contrat de maintenance
 */
require_once '../src/Core/auth.php';
require_once '../src/Core/utils.php';

$controller = new EquipmentController();
$data = $controller->handleRequest($_GET, $_POST);
extract($data);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de matériel à un contrat - CashCash</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* ===== Stepper ===== */
        .stepper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0;
            margin-bottom: 2.5rem;
            padding: 0 1rem;
        }
        .stepper-step {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .stepper-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            background: var(--bg-secondary);
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
            transition: var(--transition);
            flex-shrink: 0;
        }
        .stepper-circle.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(0, 71, 171, 0.15);
        }
        .stepper-circle.done {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }
        .stepper-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        .stepper-label.active { color: var(--primary-color); font-weight: 600; }
        .stepper-label.done { color: var(--success-color); }
        .stepper-line {
            width: 60px;
            height: 3px;
            background: var(--border-color);
            margin: 0 1rem;
            border-radius: 2px;
        }
        .stepper-line.done { background: var(--success-color); }

        /* ===== Client Card Grid ===== */
        .client-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }
        .client-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.25rem;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        .client-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .client-card-number {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .client-card-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        .client-card-info {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        .client-card-btn {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            opacity: 0;
            transition: var(--transition);
        }
        .client-card:hover .client-card-btn { opacity: 1; }

        /* ===== Info Banner ===== */
        .info-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border-radius: var(--border-radius-lg);
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .info-banner-content h3 { color: white; margin: 0 0 0.25rem 0; font-size: 1.3rem; }
        .info-banner-content p { color: rgba(255,255,255,0.85); margin: 0; font-size: 0.95rem; }

        /* ===== Contract Cards ===== */
        .contract-radio { display: none; }
        .contract-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 1.25rem;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }
        .contract-card:hover { border-color: var(--primary-light); box-shadow: var(--shadow-sm); }
        .contract-radio:checked + .contract-card {
            border-color: var(--primary-color);
            background: rgba(0, 71, 171, 0.03);
            box-shadow: 0 0 0 3px rgba(0, 71, 171, 0.12);
        }
        .contract-radio:checked + .contract-card::after {
            content: "✓";
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: var(--primary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .contract-number { font-weight: 700; color: var(--primary-color); font-size: 1.05rem; }
        .contract-dates {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }
        .contract-badge {
            display: inline-block;
            margin-top: 0.5rem;
        }

        /* ===== Equipment Checklist ===== */
        .eq-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }
        .eq-item:hover { border-color: var(--primary-light); background: rgba(0, 71, 171, 0.02); }
        .eq-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--primary-color);
            cursor: pointer;
            flex-shrink: 0;
        }
        .eq-item-info { flex: 1; }
        .eq-item-serie { font-weight: 600; color: var(--text-primary); }
        .eq-item-ref { font-size: 0.85rem; color: var(--text-secondary); }
        .eq-item-location { font-size: 0.8rem; color: var(--text-light); margin-top: 0.15rem; }

        /* ===== Assigned Equipment Table ===== */
        .assigned-section { margin-top: 2rem; }
        .section-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0 1.5rem;
        }
        .section-divider span {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .section-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        /* ===== Success Card ===== */
        .success-card {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
        }
        .success-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #d4edda;
            color: var(--success-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1.5rem;
        }
        .success-card h3 { color: var(--success-color); font-size: 1.5rem; margin-bottom: 0.5rem; }
        .success-card p { color: var(--text-secondary); margin-bottom: 2rem; }
        .success-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

        /* ===== Select All ===== */
        .select-all-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: var(--bg-secondary);
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .select-all-bar label { cursor: pointer; font-weight: 500; display: flex; align-items: center; gap: 0.5rem; }
        .select-all-bar input[type="checkbox"] { accent-color: var(--primary-color); width: 18px; height: 18px; }
        .eq-count { color: var(--text-secondary); font-size: 0.85rem; }
    </style>
</head>
<body class="dashboard-page">
    <?php include '../src/Views/components/header.php'; ?>
    
    <div class="dashboard-content">
        <div class="container">
            <div class="dashboard-title">
                <h2>🔧 Gestion du matériel</h2>
                <p class="dashboard-subtitle">Associer du matériel client à un contrat de maintenance</p>
            </div>
            
            <?php include '../src/Views/components/nav_gestionnaire.php'; ?>

            <!-- ===== STEPPER ===== -->
            <div class="stepper">
                <div class="stepper-step">
                    <div class="stepper-circle <?= $step === 'select_client' ? 'active' : ($step !== 'select_client' ? 'done' : '') ?>">
                        <?= $step !== 'select_client' ? '✓' : '1' ?>
                    </div>
                    <span class="stepper-label <?= $step === 'select_client' ? 'active' : ($step !== 'select_client' ? 'done' : '') ?>">Client</span>
                </div>
                <div class="stepper-line <?= $step !== 'select_client' ? 'done' : '' ?>"></div>
                <div class="stepper-step">
                    <div class="stepper-circle <?= $step === 'choose_contract_and_eq' ? 'active' : ($step === 'done' ? 'done' : '') ?>">
                        <?= $step === 'done' ? '✓' : '2' ?>
                    </div>
                    <span class="stepper-label <?= $step === 'choose_contract_and_eq' ? 'active' : ($step === 'done' ? 'done' : '') ?>">Contrat & Matériel</span>
                </div>
                <div class="stepper-line <?= $step === 'done' ? 'done' : '' ?>"></div>
                <div class="stepper-step">
                    <div class="stepper-circle <?= $step === 'done' ? 'done' : '' ?>">
                        <?= $step === 'done' ? '✓' : '3' ?>
                    </div>
                    <span class="stepper-label <?= $step === 'done' ? 'done' : '' ?>">Confirmation</span>
                </div>
            </div>

            <!-- ===== MESSAGES ===== -->
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <strong>⚠️ Erreur:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- ===== ÉTAPE 1 : Sélection du client ===== -->
            <?php if ($step === 'select_client'): ?>
                <div class="table-container" style="padding: 0;">
                    <div class="table-header">
                        <h3 class="table-title">Sélectionner un client</h3>
                        <div class="table-actions" style="width: 300px;">
                            <input type="text" id="clientSearch" class="form-control" 
                                   placeholder="🔍 Rechercher (Nom, N°, SIREN...)" 
                                   onkeyup="filterClients()" 
                                   style="width:100%; padding: 0.65rem 1rem; border: 2px solid var(--border-color); border-radius: var(--border-radius); font-size: 0.95rem;">
                        </div>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="client-grid" id="clientGrid">
                            <?php foreach ($clients as $c): ?>
                                <form method="post" action="add_equipment.php?step=select_client" style="display:contents;">
                                    <input type="hidden" name="client_id" value="<?= $c['Numero_Client'] ?>">
                                    <button type="submit" class="client-card" style="text-align:left; font-family:inherit; background:white;">
                                        <span class="client-card-number">N°<?= $c['Numero_Client'] ?></span>
                                        <div class="client-card-name"><?= htmlspecialchars($c['Raison_Sociale']) ?></div>
                                        <div class="client-card-info">
                                            <?php if (!empty($c['Siren'])): ?>
                                                SIREN: <?= htmlspecialchars($c['Siren']) ?>
                                            <?php endif; ?>
                                            <?php if (!empty($c['Code_Ape'])): ?>
                                                 · APE: <?= htmlspecialchars($c['Code_Ape']) ?>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($c['Adresse'])): ?>
                                            <div class="client-card-info" style="margin-top: 0.25rem;">
                                                📍 <?= htmlspecialchars($c['Adresse']) ?>
                                            </div>
                                        <?php endif; ?>
                                        <span class="client-card-btn btn btn-sm btn-primary">Sélectionner →</span>
                                    </button>
                                </form>
                            <?php endforeach; ?>
                        </div>
                        <?php if (empty($clients)): ?>
                            <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                <p style="font-size: 2rem; margin-bottom: 1rem;">📋</p>
                                <p>Aucun client enregistré dans la base de données.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <script>
                function filterClients() {
                    var input = document.getElementById("clientSearch");
                    var filter = input.value.toUpperCase();
                    var cards = document.querySelectorAll(".client-card");
                    cards.forEach(function(card) {
                        var text = card.textContent || card.innerText;
                        card.closest("form").style.display = text.toUpperCase().indexOf(filter) > -1 ? "" : "none";
                    });
                }
                </script>

            <!-- ===== ÉTAPE 2 : Sélection du contrat et matériel ===== -->
            <?php elseif ($step === 'choose_contract_and_eq' && $client): ?>
                
                <!-- Bannière Client -->
                <div class="info-banner">
                    <div class="info-banner-content">
                        <h3>🏢 N°<?= $client['Numero_Client'] ?> — <?= htmlspecialchars($client['Raison_Sociale']) ?></h3>
                        <p>
                            <?php if (!empty($client['Adresse'])): ?>
                                📍 <?= htmlspecialchars($client['Adresse']) ?>
                            <?php endif; ?>
                            <?php if (!empty($client['Telephone_Client'])): ?>
                                · 📞 <?= htmlspecialchars($client['Telephone_Client']) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <a href="add_equipment.php" class="btn btn-sm btn-outline" style="border-color: white; color: white;">← Changer de client</a>
                </div>

                <form method="post" action="add_equipment.php?step=choose_contract_and_eq">
                    <input type="hidden" name="client_id" value="<?= $clientId ?>">
                    
                    <!-- Contrats -->
                    <div class="section-divider">
                        <span>📄 Contrats de maintenance (<?= count($contracts) ?>)</span>
                    </div>
                    
                    <?php if (empty($contracts)): ?>
                        <div class="alert alert-warning">
                            <strong>Aucun contrat</strong> — Ce client ne possède aucun contrat de maintenance actif. 
                            Veuillez d'abord créer un contrat avant d'y associer du matériel.
                        </div>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                            <?php foreach ($contracts as $c): ?>
                                <?php
                                    $daysLeft = null;
                                    $isExpired = false;
                                    if (!empty($c['Date_echeance'])) {
                                        $daysLeft = (int)((strtotime($c['Date_echeance']) - time()) / 86400);
                                        $isExpired = $daysLeft < 0;
                                    }
                                ?>
                                <label>
                                    <input type="radio" name="contract_id" value="<?= $c['Numero_de_Contrat'] ?>" class="contract-radio" 
                                           <?= $selectedContract == $c['Numero_de_Contrat'] ? 'checked' : '' ?>
                                           required>
                                    <div class="contract-card">
                                        <div class="contract-number">Contrat N°<?= $c['Numero_de_Contrat'] ?></div>
                                        <div class="contract-dates">
                                            <?php if (!empty($c['Date_signature'])): ?>
                                                <span>📅 Signé: <?= date('d/m/Y', strtotime($c['Date_signature'])) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($c['Date_echeance'])): ?>
                                                <span>⏰ Échéance: <?= date('d/m/Y', strtotime($c['Date_echeance'])) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($daysLeft !== null): ?>
                                            <div class="contract-badge">
                                                <?php if ($isExpired): ?>
                                                    <span class="badge badge-danger">Expiré (<?= abs($daysLeft) ?>j)</span>
                                                <?php elseif ($daysLeft <= 60): ?>
                                                    <span class="badge badge-warning"><?= $daysLeft ?>j restants</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success"><?= $daysLeft ?>j restants</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Matériel hors contrat -->
                    <div class="section-divider">
                        <span>📦 Matériel hors contrat disponible (<?= count($unassigned) ?>)</span>
                    </div>

                    <?php if (empty($unassigned)): ?>
                        <div class="alert alert-info">
                            <strong>Aucun matériel disponible</strong> — Tout le matériel de ce client est déjà rattaché à un contrat.
                        </div>
                    <?php else: ?>
                        <div class="select-all-bar">
                            <label>
                                <input type="checkbox" id="selectAll" onclick="toggleAll(this)">
                                Tout sélectionner
                            </label>
                            <span class="eq-count" id="eqCount">0 sélectionné(s)</span>
                        </div>
                        <div style="display: grid; gap: 0.75rem; margin-bottom: 2rem;">
                            <?php foreach ($unassigned as $eq): ?>
                                <label class="eq-item">
                                    <input type="checkbox" name="equipment[]" value="<?= $eq['Numero_de_Serie'] ?>" onchange="updateCount()">
                                    <div class="eq-item-info">
                                        <div class="eq-item-serie">N° Série: <?= htmlspecialchars($eq['Numero_de_Serie']) ?></div>
                                        <div class="eq-item-ref">Réf: <?= htmlspecialchars($eq['Reference_Interne'] ?? 'N/A') ?></div>
                                        <?php if (!empty($eq['Emplacement'])): ?>
                                            <div class="eq-item-location">📍 <?= htmlspecialchars($eq['Emplacement']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($eq['Date_de_vente'])): ?>
                                        <span class="badge badge-secondary"><?= date('d/m/Y', strtotime($eq['Date_de_vente'])) ?></span>
                                    <?php endif; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Bouton de soumission -->
                    <?php if (!empty($contracts) && !empty($unassigned)): ?>
                        <div style="text-align: center; padding: 1rem 0 2rem;">
                            <button type="submit" class="btn btn-primary btn-lg" style="min-width: 300px;">
                                ✅ Associer le matériel au contrat
                            </button>
                        </div>
                    <?php endif; ?>
                </form>

                <!-- Matériel déjà sous contrat -->
                <?php if (!empty($assigned)): ?>
                    <div class="section-divider">
                        <span>🔗 Matériel déjà sous contrat (<?= count($assigned) ?>)</span>
                    </div>
                    <div class="table-container">
                        <div class="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        <th>N° Série</th>
                                        <th>Référence</th>
                                        <th>Emplacement</th>
                                        <th>N° Contrat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($assigned as $eq): ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($eq['Numero_de_Serie']) ?></strong></td>
                                            <td><?= htmlspecialchars($eq['Reference_Interne'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($eq['Emplacement'] ?? 'N/A') ?></td>
                                            <td><span class="badge badge-primary">Contrat N°<?= $eq['Numero_de_Contrat'] ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <script>
                function toggleAll(source) {
                    var checkboxes = document.querySelectorAll('input[name="equipment[]"]');
                    checkboxes.forEach(function(cb) { cb.checked = source.checked; });
                    updateCount();
                }
                function updateCount() {
                    var checked = document.querySelectorAll('input[name="equipment[]"]:checked').length;
                    var total = document.querySelectorAll('input[name="equipment[]"]').length;
                    document.getElementById('eqCount').textContent = checked + ' sélectionné(s) sur ' + total;
                    document.getElementById('selectAll').checked = (checked === total && total > 0);
                }
                </script>

            <!-- ===== ÉTAPE 3 : Confirmation ===== -->
            <?php elseif ($step === 'done'): ?>
                <div class="success-card">
                    <div class="success-icon">✓</div>
                    <h3>Association réussie !</h3>
                    <p><?= htmlspecialchars($message) ?></p>
                    <?php if ($client): ?>
                        <p style="color: var(--text-secondary); margin-bottom: 2rem;">
                            Client: <strong>N°<?= $client['Numero_Client'] ?> — <?= htmlspecialchars($client['Raison_Sociale']) ?></strong>
                        </p>
                    <?php endif; ?>
                    <div class="success-actions">
                        <form method="post" action="add_equipment.php?step=select_client">
                            <input type="hidden" name="client_id" value="<?= $clientId ?>">
                            <button type="submit" class="btn btn-primary">🔧 Continuer avec ce client</button>
                        </form>
                        <a href="add_equipment.php" class="btn btn-outline">← Choisir un autre client</a>
                        <a href="dashboard_gestionnaire.php" class="btn btn-secondary" style="color: var(--text-primary);">🏠 Tableau de bord</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
