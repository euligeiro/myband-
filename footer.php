    </main>
    
    <footer>
        <p>
            <?php
            date_default_timezone_set('Europe/Paris');
            $mois = [
                1 => "janvier", "février", "mars", "avril", "mai", "juin",
                     "juillet", "août", "septembre", "octobre", "novembre", "décembre"
            ];
            $jour   = date("d");
            $moisFR = $mois[(int)date("n")];
            $annee  = date("Y");
            $heure  = date("H:i");
            echo "Nous sommes le $jour $moisFR $annee, il est $heure";
            ?>
        </p>
    </footer>
</body>
</html>