<?php

require_once '../src/Core/auth.php';

$controller = new AuthController();
$data = $controller->handleLogin($_GET, $_POST);
extract($data);

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
            </div>
        </div>
    </div>
</body>
</html>
