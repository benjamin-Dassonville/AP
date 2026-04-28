<?php

class AuthManager {
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
    }

    public static function hasRole($role) {
        return self::isLoggedIn() && $_SESSION['user_role'] === $role;
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }

    public static function requireRole($role) {
        self::requireLogin();
        if (!self::hasRole($role)) {
            header('Location: login.php?error=access_denied');
            exit;
        }
    }

    public static function loginUser(Utilisateur $user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_nom'] = $user->nom;
        $_SESSION['user_prenom'] = $user->prenom;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['login_time'] = time();
    }

    public static function logoutUser() {
        session_unset();
        session_destroy();
    }

    public static function getCurrentUserName() {
        if (self::isLoggedIn()) {
            return $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
        }
        return 'Invité';
    }

    public static function getCurrentUserRole() {
        return $_SESSION['user_role'] ?? null;
    }

    public static function redirectToDashboard() {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
        
        session_write_close();
        if (self::hasRole('gestionnaire')) {
            header('Location: dashboard_gestionnaire.php');
            exit;
        } elseif (self::hasRole('technicien')) {
            header('Location: dashboard_technicien.php');
            exit;
        } else {
            self::logoutUser();
            header('Location: login.php?error=access_denied');
            exit;
        }
    }
}
