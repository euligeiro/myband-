<?php
require 'conexion_BDD.php';

try {
    $db = getDBConnection();
    echo "✅ Connexion à la base de données réussie!";
} catch(PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage();
}
?>