<?php
session_start();
require_once 'conexion_BDD.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = getDBConnection();
    
    // Adicionar ou editar música
    if (isset($_POST['saveSong'])) {
        $title = trim($_POST['title']);
        $artist = trim($_POST['artist']);
        $style = trim($_POST['style']);
        $songId = $_POST['songId'];
        
        // Validação básica
        if (empty($title) || empty($artist) || empty($style)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires";
            header('Location: setlist.php');
            exit;
        }
        
        try {
            if (empty($songId)) {
                // Inserir nova música
                $query = $db->prepare("INSERT INTO setlist (title, artist, style) VALUES (:title, :artist, :style)");
            } else {
                // Atualizar música existente
                $query = $db->prepare("UPDATE setlist SET title = :title, artist = :artist, style = :style WHERE id = :id");
                $query->bindParam(':id', $songId);
            }
            
            $query->bindParam(':title', $title);
            $query->bindParam(':artist', $artist);
            $query->bindParam(':style', $style);
            $query->execute();
            
            $_SESSION['success'] = empty($songId) ? "Chanson ajoutée avec succès" : "Chanson modifiée avec succès";
        } catch(PDOException $e) {
            $_SESSION['error'] = "Erreur lors de l'enregistrement: " . $e->getMessage();
        }
    }
    
    header('Location: setlist.php');
    exit;
}

// Excluir música (via GET)
if (isset($_GET['delete'])) {
    $songId = $_GET['delete'];
    
    try {
        $db = getDBConnection();
        $query = $db->prepare("DELETE FROM setlist WHERE id = :id");
        $query->bindParam(':id', $songId);
        $query->execute();
        
        $_SESSION['success'] = "Chanson supprimée avec succès";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
    }
    
    header('Location: setlist.php');
    exit;
}

// Se não for POST ou GET válido, redirecionar
header('Location: setlist.php');
exit;
?>