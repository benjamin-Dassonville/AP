<?php
/**
 * helpers.php
 * Fonctions utilitaires d'affichage partagées entre les vues
 */

function getStatutBadge($statut) {
    $badges = [
        'en_attente' => 'badge-warning',
        'en_cours' => 'badge-info',
        'terminee' => 'badge-success',
        'annulee' => 'badge-secondary'
    ];
    return $badges[$statut] ?? 'badge-secondary';
}

function getStatutLabel($statut) {
    return ucfirst(str_replace('_', ' ', $statut));
}

function getPrioriteBadge($priorite) {
    $badges = [
        'basse' => 'badge-secondary',
        'normale' => 'badge-info',
        'haute' => 'badge-warning',
        'urgente' => 'badge-danger'
    ];
    return $badges[$priorite] ?? 'badge-secondary';
}

function formatClientDisplay($clientId, $clientNom) {
    if ($clientId) {
        return htmlspecialchars('N°' . $clientId . ' - ' . $clientNom);
    }
    return 'N/A';
}

function formatDate($date, $format = 'd/m/Y H:i') {
    return date($format, strtotime($date));
}
?>
