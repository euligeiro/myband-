<?php
// Fichier de connexion à la base de données

function getDBConnection() {
    $host = 'localhost';
    $dbname = 'myband';
    $username = 'myband_user';
    $password = 'secure_password'; 
    
    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch(PDOException $e) {
        die("Erreur de connexion à la base de données: " . $e->getMessage());
    }
}
?>