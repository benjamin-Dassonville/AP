<?php
/**
 * logout.php
 * Déconnexion de l'utilisateur
 */

require_once '../src/Core/auth.php';

$controller = new AuthController();
$controller->handleLogout();
?>
