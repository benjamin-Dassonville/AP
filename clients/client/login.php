<?php

require_once 'auth.php';

if (isLoggedIn()) {
    redirectToDashboard();
}

$error = '';
$role_param = $_GET['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $expected_role = $_POST['role'] ?? '';
    
    if ($username && $password) {
        $user = authenticateUser($username, $password);
        
        if ($user) {
            if ($expected_role && $user['role'] !== $expected_role) {
                $error = "Ce compte n'est pas un compte " . ucfirst($expected_role) . ".";
            } else {
                loginUser($user);
                redirectToDashboard();
            }
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

$page_title = 'Connexion CashCash';
if ($role_param === 'gestionnaire') {
    $page_title = 'Connexion Gestionnaire';
} elseif ($role_param === 'technicien') {
    $page_title = 'Connexion Technicien';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <h1>CashCash</h1>
                <p class="login-subtitle">
                    <?php if ($role_param): ?>
                        Connexion <?= ucfirst(htmlspecialchars($role_param)) ?>
                    <?php else: ?>
                        Connexion
                    <?php endif; ?>
                </p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <strong>⚠️ Erreur:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied'): ?>
                <div class="alert alert-error">
                    <strong>⚠️ Accès refusé:</strong> Vous n'avez pas les permissions nécessaires.
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <input type="hidden" name="role" value="<?= htmlspecialchars($role_param) ?>">
                
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required 
                        autofocus
                        placeholder="Entrez votre nom d'utilisateur"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        placeholder="Entrez votre mot de passe"
                    >
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    Se connecter
                </button>
            </form>
            
            <div class="login-footer">
                <a href="index.php" class="back-link">← Retour à la sélection</a>
                
                <div class="credentials-hint">
                    <p><small>Comptes de test:</small></p>
                    <p><small><strong>Gestionnaire:</strong> gestionnaire / password123</small></p>
                    <p><small><strong>Technicien:</strong> technicien1 / password123</small></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
