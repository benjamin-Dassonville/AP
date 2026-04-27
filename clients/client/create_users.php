<?php
require_once 'config.php';

// Mot de passe à hasher
$password = 'password123';
$password_hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Création des utilisateurs de test</h2>";
echo "<p>Mot de passe utilisé : <strong>password123</strong></p>";
echo "<p>Hash généré : <code>" . htmlspecialchars($password_hash) . "</code></p>";
echo "<hr>";

try {
    // Vider la table Utilisateur (attention : supprime tous les utilisateurs existants)
    $pdo->exec("DELETE FROM Utilisateur");
    echo "<p style='color: orange;'>✓ Table Utilisateur vidée</p>";
    
    // Insérer les nouveaux utilisateurs
    $stmt = $pdo->prepare("
        INSERT INTO Utilisateur (username, password_hash, role, nom, prenom, email) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    // Gestionnaire
    $stmt->execute([
        'gestionnaire',
        $password_hash,
        'gestionnaire',
        'Dupont',
        'Marie',
        'marie.dupont@cashcash.fr'
    ]);
    echo "<p style='color: green;'>✓ Gestionnaire créé : <strong>gestionnaire</strong> / password123</p>";
    
    // Technicien 1
    $stmt->execute([
        'technicien1',
        $password_hash,
        'technicien',
        'Martin',
        'Pierre',
        'pierre.martin@cashcash.fr'
    ]);
    echo "<p style='color: green;'>✓ Technicien 1 créé : <strong>technicien1</strong> / password123</p>";
    
    // Technicien 2
    $stmt->execute([
        'technicien2',
        $password_hash,
        'technicien',
        'Durand',
        'Sophie',
        'sophie.durand@cashcash.fr'
    ]);
    echo "<p style='color: green;'>✓ Technicien 2 créé : <strong>technicien2</strong> / password123</p>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>✅ SUCCÈS ! Les utilisateurs ont été créés</h3>";
    echo "<p><strong>Vous pouvez maintenant vous connecter avec :</strong></p>";
    echo "<ul>";
    echo "<li><strong>Gestionnaire :</strong> gestionnaire / password123</li>";
    echo "<li><strong>Technicien :</strong> technicien1 / password123</li>";
    echo "<li><strong>Technicien :</strong> technicien2 / password123</li>";
    echo "</ul>";
    
    echo "<p><a href='index.php' style='display: inline-block; background: #0066CC; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;'>→ Aller à la page de connexion</a></p>";
    
    // Vérification
    echo "<hr>";
    echo "<h3>Vérification des utilisateurs créés :</h3>";
    $users = $pdo->query("SELECT id, username, role, nom, prenom, email FROM Utilisateur")->fetchAll();
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Username</th><th>Rôle</th><th>Nom</th><th>Prénom</th><th>Email</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td><strong>" . htmlspecialchars($user['username']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($user['role']) . "</td>";
        echo "<td>" . htmlspecialchars($user['nom']) . "</td>";
        echo "<td>" . htmlspecialchars($user['prenom']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'><strong>❌ ERREUR :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Assurez-vous que la table Utilisateur existe dans votre base de données.</p>";
}
?>
