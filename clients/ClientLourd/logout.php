<?php
/**
 * logout.php
 * Déconnexion de l'utilisateur
 */

require_once 'auth.php';

logoutUser();

// Rediriger vers la page d'accueil
header('Location: index.php?logged_out=1');
exit;
?>
