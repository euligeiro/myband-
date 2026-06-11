<?php
require('header.php');

$connexion=dbconnect(); 

/* Manage set list actions */
if (isset($_POST["formsongaction"])){

    $action = $_POST["formsongaction"];
    
    if ($action == "add"){

        $title = $_POST["title"];
        $artist = $_POST["artist"];
        $style = $_POST["style"];

        $sql = "INSERT INTO setlist (`title`, `artist`, `style`) VALUES(:title, :artist, :style )";
        $query = $connexion->prepare($sql);
        $query->bindValue(':title', htmlspecialchars($title), PDO::PARAM_STR);
        $query->bindValue(':artist', htmlspecialchars($artist), PDO::PARAM_STR);
        $query->bindValue(':style', htmlspecialchars($style), PDO::PARAM_STR);

        // execute insert sql
        $query->execute();

    }
    else if ($action=="modify"){

        $title = $_POST["title"];
        $artist = $_POST["artist"];
        $style = $_POST["style"];

        $id = $_POST["formsongid"];

        $sql = "UPDATE setlist SET `title` = :title, `artist`=:artist, `style`=:style WHERE id=:id";
        $query = $connexion->prepare($sql);
        $query->bindValue(':title', htmlspecialchars($title), PDO::PARAM_STR);
        $query->bindValue(':artist', htmlspecialchars($artist), PDO::PARAM_STR);
        $query->bindValue(':style', htmlspecialchars($style), PDO::PARAM_STR);
        $query->bindValue(':id', $id, PDO::PARAM_STR);

        // execute insert sql
        $query->execute();

    }
    else{

        $id = $_POST["formsongid"];

        $sql = "DELETE FROM setlist WHERE id=:id";
        //echo $sql." ".$id;
        $query = $connexion->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_STR);

        $query->execute();

    }

}


/* Querying Set List from DB */
$columns = array('title','artist','style');
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

$up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order); 
$asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
$add_class = ' class="highlight"';

$sql = "SELECT * from setlist ORDER BY " .  $column . " " . $sort_order;

if(!$connexion->query($sql)) {
  echo "Pb d'accès à la bdd"; 
}
else{ 
  ?> 

  <div class="main">
  <!-- Titre -->
    <header class="intro">
        <h1> Set List </h1>
    </header>

    <script>
        /** Search Song Filter Function */
        function searchFunction() {
            var value = document.querySelector("#searchInput").value.toLowerCase();
            document.querySelectorAll("#songTable tbody tr").forEach((tr)=>{
                tr.style.display = (tr.innerText.toLowerCase().indexOf(value)> -1)?'':'none';
            });
        }

         /** Add Or Modify JS Function (using addUpdateSongForm) */
        function addormodifySong(action, id, title, artist, style) {
            // Use hidden input (formsongaction) of addUpdateSongForm to store action (add or update) ==> it will put action in $_POST['formsongaction']
            document.querySelector("#addUpdateSongForm").elements["formsongaction"].value = action;
            
            if (action=="add"){
                // set Text to "Add"
                document.querySelector('#addUpdateSongModalLabel').innerText="Add Song";
            }
            else{
                // set Text to "Edit"
                document.querySelector('#addUpdateSongModalLabel').innerText="Edit Song";
                
                // pre-fill inputs
                document.querySelector("#addUpdateSongForm").elements["title"].value = title;
                document.querySelector("#addUpdateSongForm").elements["artist"].value = artist;
                document.querySelector("#addUpdateSongForm").elements["style"].value = style;

                // Use hidden input (formsongid) of addUpdateSongForm to store song's id ==> it will put id in $_POST['formsongid']
                document.querySelector("#addUpdateSongForm").elements["formsongid"].value = id;
            }
            
            // display modal form
            let modal = document.getElementById('addUpdateSongModal');
            modal.style.display='block';
        }

        /** JS function called before submitting add/update song form to check empty values */
        function check(){
            let valid=true;

            if (document.querySelector("#addUpdateSongForm").elements["title"].value.trim() == "") {
                valid=false;
            }
            if (document.querySelector("#addUpdateSongForm").elements["artist"].value.trim() == "") {
                valid=false;
            }
            if (document.querySelector("#addUpdateSongForm").elements["style"].value.trim() == "") {
                valid=false;
            }
            
            if (!valid){
                return false;
            }
            else{
                return true;
            }
        }

         /** Remove Song JS Function (using removeSongForm) */
         function removeSong(id) {
            // Use hidden input (formsongaction) of removeSongForm to store action (remove) ==> it will put action in $_POST['formsongaction']
            document.querySelector("#removeSongForm").elements["formsongaction"].value = "remove";
            // Use hidden input (formsongid) of removeSongForm to store song's id ==> it will put id in $_POST['formsongid']
            document.querySelector("#removeSongForm").elements["formsongid"].value = id;


            // display modal form
            let modal = document.getElementById('removeSongModal');
            modal.style.display='block';
        }

         /** Fonction pour uploader les paroles */
        function uploadLyrics(id, title) {
            document.querySelector("#uploadLyricsForm input[name='formsongid']").value = id;
            document.querySelector("#uploadLyricsModalLabel").innerText = "Uploader les paroles pour : " + title;
            
            let modal = document.getElementById('uploadLyricsModal');
            modal.style.display='block';
        }

        /** Fonction pour supprimer les paroles */
        function removeLyrics(id, title) {
            document.querySelector("#removeLyricsForm input[name='formsongid']").value = id;
            document.querySelector("#removeLyricsModalLabel").innerText = "Supprimer les paroles pour : " + title;
            
            let modal = document.getElementById('removeLyricsModal');
            modal.style.display='block';
        }

    </script>

    <!-- Add or Update Song Form DIV -->
    <div id="addUpdateSongModal" class="modal">
  
        <form id="addUpdateSongForm" onsubmit="return check();"  class="modal-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="dlgheadcontainer">
                <span onclick="document.getElementById('addUpdateSongModal').style.display='none'" class="close" title="Close Modal">&times;</span>
                    <h1 id="addUpdateSongModalLabel">Song Edit :</h1>
            </div>

            <div class="dlgcontainer">
                <input type="hidden" name="formsongaction" id="addorupdate">
                <input type="hidden" name="formsongid" id="formsongid" >


                <label for="uname"><b>Song Title :</b></label>
                <input type="text" name="title" id="songtitle" placeholder="Song Title">

                <label for="psw"><b>Song Artist :</b></label>
                <input type="text" name="artist" id="songartist" placeholder="Song Artist">

                <label for="psw"><b>Style :</b></label>
                <input type="text" name="style" id="songstyle" placeholder="Style">
                    
                <button type="submit" class="okbtn">Apply</button>
                <button type="button" onclick="document.getElementById('addUpdateSongModal').style.display='none'" class="cancelbtn">Cancel</button>

            </div>

        </form>
    </div>

<!-- Formulaire d'upload de paroles -->
    <div id="uploadLyricsModal" class="modal">
        <form id="uploadLyricsForm" class="modal-content animate" action="upload.php" method="post" enctype="multipart/form-data">
            <div class="dlgheadcontainer">
                <span onclick="document.getElementById('uploadLyricsModal').style.display='none'" class="close" title="Fermer">&times;</span>
                <h1 id="uploadLyricsModalLabel">Uploader les paroles</h1>
            </div>

            <div class="dlgcontainer">
                <input type="hidden" name="formlyricsaction" value="upload">
                <input type="hidden" name="formsongid" value="">

                <label for="lyricsfile"><b>Sélectionner un fichier PDF :</b></label>
                <input type="file" name="lyricsfile" accept=".pdf" required>
                
                <div style="margin: 10px 0; padding: 10px; background-color: #f8f9fa; border-radius: 4px;">
                    <small><b>Format accepté :</b> PDF uniquement</small><br>
                    <small><b>Taille maximale :</b> 5MB</small>
                </div>
                    
                <button type="submit" class="okbtn">Uploader</button>
                <button type="button" onclick="document.getElementById('uploadLyricsModal').style.display='none'" class="cancelbtn">Annuler</button>
            </div>
        </form>
    </div>

    <!-- Formulaire de suppression de paroles -->
    <div id="removeLyricsModal" class="modal">
        <form id="removeLyricsForm" class="modal-content animate" action="upload.php" method="post">
            <div class="dlgheadcontainer">
                <span onclick="document.getElementById('removeLyricsModal').style.display='none'" class="close" title="Fermer">&times;</span>
                <h1 id="removeLyricsModalLabel">Supprimer les paroles</h1>
            </div>

            <div class="dlgcontainer">
                <input type="hidden" name="formlyricsaction" value="remove">
                <input type="hidden" name="formsongid" value="">

                <p>Êtes-vous sûr de vouloir supprimer ces paroles ?</p>
                <button type="submit" class="okbtn">Oui</button>
                <button type="button" onclick="document.getElementById('removeLyricsModal').style.display='none'" class="cancelbtn">Non</button>
            </div>
        </form>
    </div>

    <!-- Set List Table & Search filter -->
    <div class="row">
        <div class="col-sm">
            <table id="songTable" style="width:90%;margin: auto;">
                <thead>
                    <tr>
                        <th class="headersearch" colspan="5"><input type="text" class="searchinput" id="searchInput" onkeyup="searchFunction()" placeholder="Search .."></th>
                    </tr>
                    <tr>
                        <th class="headersort"><a href="./setlist.php?column=title&order=<?php echo $asc_or_desc; ?>">TITLE <i class="fas fa-sort<?php echo $column == 'title' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                        <th class="headersort"><a href="./setlist.php?column=artist&order=<?php echo $asc_or_desc; ?>">ARTIST(S) <i class="fas fa-sort<?php echo $column == 'artist' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                        <th class="headersort"><a href="./setlist.php?column=style&order=<?php echo $asc_or_desc; ?>">STYLE <i class="fas fa-sort<?php echo $column == 'style' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                     <?php if ($member) { ?>
                      <th class="headersort">LYRICS</th>
                      <?php } ?>
                      <!-- Colonne PAROLES - visible pour les membres et admin -->
                        <?php if ($member || $admin) { ?>
                        <th class="headersort">PAROLES (PDF)</th>
                        <?php } ?>
                        
                        <?php if ($admin){ ?>
                            <th colspan="2" class="headersort">
                                <button onclick="addormodifySong('add');" type='button' class='addbtn'><i class='fa fa-plus'></i></button>
                            </th>
                        <?php } ?>

                    </tr>
                </thead>
                <tbody>
                <?php 
                    foreach ($connexion->query($sql) as $row) {
                        echo "<tr><td>".$row['title']."</td> <td>".
                                        $row['artist']."</td> <td>".
                                        $row['style']."</td> ";

                          // Colonne PAROLES - pour les membres et admin
if ($member || $admin) {
    echo "<td class='align-middle'>";
    if ($row['lyrics'] != NULL) {
        echo "<a href='download_lyrics.php?lyricsPDF=".$row['lyrics']."' class='okbtn' title='Télécharger'><i class='fa fa-download'></i></a>";
        
        if ($admin) {
            echo " <button onclick=\"removeLyrics(".$row['id'].", '".addslashes($row['title'])."')\" type='button' class='cancelbtn' title='Supprimer'><i class='fa fa-trash'></i></button>";
        }
    } else {
        if ($admin) {
            echo "<button onclick=\"uploadLyrics(".$row['id'].", '".addslashes($row['title'])."')\" type='button' class='addbtn' title='Uploader'><i class='fa fa-upload'></i></button>";
        } else {
            echo "-";
        }
    }
    echo "</td>";
}
                        if ($admin){
                            echo "<td class='align-middle'><button onclick=\"addormodifySong('modify', " . $row['id'] . ", '".addslashes($row['title'])."', '".addslashes($row['artist'])."', '".addslashes($row['style'])."');\" type='button' class='editbtn'><i class='fa fa-pen'></i></button></td>"; 
                            echo "<td class='align-middle'><button onclick='removeSong(" .  $row['id'] . ");' type='button' class='cancelbtn'><i class='fa fa-trash'></i></button></td></tr>" ;
                        }
                        else{
                            echo "</tr>";
                        }
                    }
                ?> 
                </tbody>
            </table>
        </div>
    </div>


</div>
  
<?php
}


require('footer.php');
?>