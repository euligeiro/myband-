<?php
session_start();

/**
 * Generate random bandname
 */
function generate_bandname(){
    // Se já existe um nome na sessão, retorna ele
    if (isset($_SESSION['band_name']) && !empty($_SESSION['band_name'])) {
        return $_SESSION['band_name'];
    }
    
    $list1 = array(
        "", "Weed","Speed","Smoke", "Shred", "Spit", "Carnal", "Riff", "Rock", 
        "Eletric", "Black", "Saint", "The", "Acid", "Kink","Bong", "The Church of", 
        "Flame", "Sacred", "Lord", "Burning", "Red", "Sapphire", "Night", "Cannabis", 
        "Doom", "Lizard", "Dope", "Worship", "Sludge", "Heavy", "Lady", "Toxic", "Bad", 
        "Monster", "Suck", "Leather", "Warrior", "Snow", "Orange", "Banshee", "Devil", 
        "The Dark", "Smoking", "Funeral", "Vapor", "Toke", "Goat", "Unholy", "Eternal", 
        "Spirit", "Stoner", "Pot", "Blood", "Intersteller", "Sacrificial", "Fuzz", "Tone");

    $list2 = array(
        " King"," Queen"," Lizard", "lord"," Jesus", " Fire", " Wizard", " Destroy", 
        "s", " Ripper", " Sluts", "", " Flame", " Witch", " Sabbath", " Stalker", 
        " Acid", " Burn Out", "opolis", " Warden", " Fettish", " Kink", " Pills", 
        " Sky", " Ash", " Sadist", " Masochist", " Preist", " Sacrafice", " Slayer", 
        " Crown", " Bitch", " Thunder", " Masculinity", " Patriarch", " Strike", 
        " Powder", " City", " Sayer", " Seer", " Mask", " Warrior", " Theif", 
        " Cult", " Occult", " Goblin", " Spit", "killer", " Pyre", " Thunder", 
        " Gas", " Fog", " Blaze", " Sacrafice", " Master", " Sucker", " Whip", 
        "zilla", " Sweat", " Eater", " Magnet", " Sword", " Axe", " Caravan", " Fang", 
        " Void", " Misery", " Stoner", " Junkie", " Marijuana", " Breather", "ess", " Tone", 
        " Ritual", " Weed", " Preistess");

    $name = $list1[array_rand($list1, 1)]."".$list2[array_rand($list2, 1)];
    
    // Salva na sessão para uso futuro
    $_SESSION['band_name'] = $name;
    
    return $name;
}

/**
 * Generate random logo 
 */
function generate_bandlogo(){
    // Se já existe um logo na sessão, retorna ele
    if (isset($_SESSION['band_logo']) && !empty($_SESSION['band_logo'])) {
        return $_SESSION['band_logo'];
    }
    
    $logo_dir = 'logos/';

    // mettre les fichiers dans un tableau
    $logos = array();
    
    // Scanner le dossier des logos
    if (is_dir($logo_dir)) {
        $files = scandir($logo_dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && !is_dir($logo_dir . $file)) {
                $logos[] = $file;
            }
        }
    }
    
    // Choisir aléatoirement un logo
    if (count($logos) > 0) {
        $random_logo = $logos[array_rand($logos)];
        $logo_path = $logo_dir . $random_logo;

        // Salva na sessão para uso futuro
        $_SESSION['band_logo'] = $logo_path;
        
        return $logo_path;
    } else {
        $default_logo = 'logos/default.png';
        $_SESSION['band_logo'] = $default_logo;
        return $default_logo; // Logo par défaut si aucun trouvé
    }
}
?>