<?php
// upload.php
session_start();
require('dbconnect.php');

$connexion = dbconnect(); 

// Gére l'upload de fichier
if(isset($_POST["formlyricsaction"]) && $_POST["formlyricsaction"] == "upload") {
    
    $song_id = $_POST["formsongid"];
    $target_dir = "lyrics/";
    
    
    if(isset($_FILES["lyricsfile"]) && $_FILES["lyricsfile"]["error"] == 0) {
        
        $file = $_FILES["lyricsfile"];
        $fileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        
        if($fileType == "pdf") {
            $filename = uniqid() . "_" . $file["name"];
            $target_file = $target_dir . $filename;
            
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $sql = "UPDATE setlist SET lyrics = :lyrics WHERE id = :id";
                $query = $connexion->prepare($sql);
                $query->bindValue(':lyrics', $filename, PDO::PARAM_STR);
                $query->bindValue(':id', $song_id, PDO::PARAM_INT);
                $query->execute();
                
                $_SESSION['success_message'] = "Fichier uploadé avec succès!";
            }
        }
    }
}

// Supprimer les paroles 
else if(isset($_POST["formlyricsaction"]) && $_POST["formlyricsaction"] == "remove") {
    
    $song_id = $_POST["formsongid"];
    
    $sql = "UPDATE setlist SET lyrics = NULL WHERE id = :id";
    $query = $connexion->prepare($sql);
    $query->bindValue(':id', $song_id, PDO::PARAM_INT);
    $query->execute();
    
    $_SESSION['success_message'] = "Paroles supprimées!";
}

header("Location: setlist.php");
exit();
?>