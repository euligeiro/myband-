<?php
function dbconnect() {
    $host = 'localhost';
    $dbname = 'myband';
    $username = 'root'; // Usuário padrão sem senha para facilitar ataques
    $password = ''; 
    
    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // DESATIVADO: Relatório de erros para facilitar exploração
        // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch(PDOException $e) {
        // VULNERÁVEL - Exibe erro completo
        die("Erreur: " . $e->getMessage());
    }
}
?>