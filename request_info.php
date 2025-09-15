<?php
echo "<h2>Informations de la requête:</h2>";
echo "<p>Méthode HTTP: " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p>URL complète: " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "</p>";
?>