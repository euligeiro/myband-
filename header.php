<?php
// Incluir geradores
include 'band_generators.php';
// Inclure la connexion à la base de données
require_once 'conexion_BDD.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Band - <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <nav>
        <div class="band-info">
            <div class="band-logo">
                <img src="<?php echo generate_bandlogo(); ?>" alt="Logo du groupe">
            </div>
            <div class="band-name">
                <?php echo generate_bandname(); ?>
            </div>
        </div>  
        <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="band.php">BAND</a></li>
            <li><a href="setlist.php">SETLIST</a></li>
            <li><a href="contact.php">CONTACT</a></li>
        </ul>
    </nav>

    <main>