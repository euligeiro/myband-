<?php
// Definir título da página
$page_title = "À propos";

// Incluir header
require 'header.php';
?>

        <section style="max-width: 900px; margin: 2rem auto; padding: 1rem; text-align: justify;">
            <h1 style="text-align:center; margin-bottom:1rem;">À propos du groupe</h1>
            <p>
                Fondé en 2015, <strong><?php echo generate_bandname(); ?></strong> est un groupe de rock
                passionné par les sons puissants et les mélodies entraînantes. Inspiré par les grands
                classiques des années 70 et 80, le groupe propose une expérience scénique intense
                où énergie et complicité avec le public se rencontrent.
            </p>
            <p>
                Le groupe est composé de cinq musiciens : un chanteur charismatique, une guitariste
                soliste virtuose, un bassiste créatif, un batteur explosif et un claviériste qui apporte
                une touche moderne à chaque composition.
            </p>
            <p>
                Leur objectif est simple : partager leur passion de la musique et faire vibrer le public
                à travers des concerts inoubliables. 🎶
            </p>
        </section>

<?php 
// Incluir footer
require 'footer.php'; 
?>