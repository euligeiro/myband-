<?php
require('band_generators.php');
require('dbconnect.php');

//Load Session Variables
session_start();


//check disconnect
if (isset($_GET["disconnect"])){
    if ($_GET["disconnect"]==1){
        unset($_SESSION["login"]);
    }
}



//check credentiels
if (isset($_POST['login'])){
    if (isset($_POST["password"])){
        
        $sql="SELECT COUNT(*) FROM members"; 

        $connexion=dbconnect(); 
        if(!$connexion->query($sql)) {
            echo "Pb d'accès à la bdd"; 
        }
        else{
            
            /* Query Prepare */
            $sql = "SELECT * FROM members WHERE login = :login AND password=:password";


            $query = $connexion->prepare($sql);
            $query->bindValue(':login', $_POST['login'], PDO::PARAM_STR);
            $query->bindValue(':password', $_POST['password'], PDO::PARAM_STR);
            $query->execute();
            $members_array = $query->fetchAll();

            $row_count = count($members_array);

            // Check the number of rows that match the SELECT statement 
            if($row_count==1) 
            {
                $member_row = $members_array[0];
                $_SESSION['login'] = $member_row['login'];
                $_SESSION['user_id'] = $member_row['id'];
                $_SESSION['admin'] = $member_row['admin'];
                $_SESSION['email'] = $member_row['email'];
            }
        }

        $connexion=null;
    }
}



//set admin var = true if user is logged
$admin = false;
$guest = true;
$member = false;

if (isset($_SESSION["login"])){
    $guest = false;
    $member = true;
    if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) {
        $admin = true;
    }
}


//manage band name & logo
if (!isset($_SESSION["bandname"])){
    $_SESSION["bandname"] = generate_bandname() ;
}

if (!isset($_SESSION["bandlogo"])){
    $_SESSION["bandlogo"] = generate_bandlogo() ;
}

?>

<html>
<head>
<title>My Band</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">

    <!-- Font Awesome CSS -->
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.3.1/css/all.css'>


  <link rel="stylesheet" href="myband.css">

  <script>
    /**
     * Display authentication modal form : 
     */
    function authenticate() {
        // Display loginModal div and display it
        let modal = document.getElementById('loginModal');
        modal.style.display='block';
    }

    /**
     * Disconnection
     */
    function disconnect() {
        window.location.href = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" + '?disconnect=1';
    }
  </script>

  
</head>
<body>
    <!-- NAV BAR -->
    <div class="navbar">
        <ul>
            <li class="brandlogo"><img height="75" src="logo.php?logo=<?php echo $_SESSION['bandlogo']  ;?>"/></li>
            <li class="brandtext">&nbsp;&nbsp;<?php echo $_SESSION["bandname"] ;?></li>
        
            <li style="float:right;"><a href="contact.php">CONTACT</a></li>
            <?php 
                if ($admin){ 
                    ?>
                    <li style="float:right;"><a href="#" onclick="disconnect();">DISCONNECT</a></li>
                    <?php
                }
                else{
                    ?>
                    <li style="float:right;"><a href="#" onclick="authenticate();">CONNECT</a></li>
                    <?php
                }
            ?>
            <li style="float:right;"><a href="setlist.php">SETLIST</a></li>
            
            <li style="float:right"><a href="index.php">HOME</a></li>
        
        </ul>

    </div>

    <!-- Authentication Form DIV -->
    <div id="loginModal" class="modal">
  
        <form id="loginForm" class="modal-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="dlgheadcontainer">
                <span onclick="document.getElementById('loginModal').style.display='none'" class="close" title="Close Modal">&times;</span>
                    <h1>Log-in !</h1>
            </div>

            <div class="dlgcontainer">
                <label for="uname"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="login" id="login" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password" id="password" required>
                    
                <button type="submit" class="okbtn">Login</button>
                <button type="button" onclick="document.getElementById('loginModal').style.display='none'" class="cancelbtn">Cancel</button>

            </div>

        </form>
    </div>
    
