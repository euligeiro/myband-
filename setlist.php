<?php
// Definir título da página
$page_title = "Setlist";

// Incluir header
require 'header.php';

// Conexão com a base de dados
$db = getDBConnection();

// Parâmetros de ordenação
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'title';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'ASC';

// Validar parâmetros de ordenação
$allowed_orders = ['title', 'artist', 'style'];
if (!in_array($order_by, $allowed_orders)) {
    $order_by = 'title';
}
$allowed_directions = ['ASC', 'DESC'];
if (!in_array($order_dir, $allowed_directions)) {
    $order_dir = 'ASC';
}
$next_order_dir = ($order_dir === 'ASC') ? 'DESC' : 'ASC';

// Buscar músicas
try {
    $query = $db->prepare("SELECT * FROM setlist ORDER BY $order_by $order_dir");
    $query->execute();
    $songs = $query->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur lors de la récupération des données: " . $e->getMessage());
}

// Verificar mensagens de sucesso/erro
$success_msg = '';
$error_msg = '';
if (isset($_SESSION['success'])) {
    $success_msg = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $error_msg = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Verificar se o usuário está logado
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];
?>

<section class="setlist-section">
    <div class="setlist-header">
        <h1 class="setlist-title">Set List</h1>
        
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success">
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-error">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="setlist-container">
        <div class="table-header">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search..." onkeyup="filterSongs()">
            </div>
            <?php if ($is_logged_in): ?>
            <div class="actions-container">
                <button onclick="addSong()" class="add-song-btn">+ Ajouter une chanson</button>
            </div>
            <?php endif; ?>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="setlist-table">
                <thead>
                    <tr>
                        <th>
                            <a href="?order_by=title&order_dir=<?php echo ($order_by === 'title' ? $next_order_dir : 'ASC'); ?>">
                                TITLE <?php if ($order_by === 'title') echo ($order_dir === 'ASC') ? '▲' : '▼'; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?order_by=artist&order_dir=<?php echo ($order_by === 'artist' ? $next_order_dir : 'ASC'); ?>">
                                ARTIST(S) <?php if ($order_by === 'artist') echo '£'; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?order_by=style&order_dir=<?php echo ($order_by === 'style' ? $next_order_dir : 'ASC'); ?>">
                                STYLE <?php if ($order_by === 'style') echo '~'; ?>
                            </a>
                        </th>
                        <?php if ($is_logged_in): ?>
                        <th class="actions-header">ACTIONS</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($songs) > 0): ?>
                        <?php foreach ($songs as $song): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($song['title']); ?></td>
                                <td><?php echo htmlspecialchars($song['artist']); ?></td>
                                <td><?php echo htmlspecialchars($song['style']); ?></td>
                                <?php if ($is_logged_in): ?>
                                <td class="actions-cell">
                                    <a href="#" onclick="editSong(<?php echo $song['id']; ?>, '<?php echo htmlspecialchars($song['title'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($song['artist'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($song['style'], ENT_QUOTES); ?>')" class="edit-btn">✓</a>
                                    <a href="#" onclick="deleteSong(<?php echo $song['id']; ?>)" class="delete-btn">✗</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo $is_logged_in ? 4 : 3; ?>" class="no-songs">Aucune chanson dans le setlist.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal para adicionar/editar música -->
<div id="songModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('songModal').style.display='none'">&times;</span>
        <h2 id="modalTitle">Ajouter une chanson</h2>
        <form class="modal-form" id="songForm" method="POST" action="manage_song.php" onsubmit="return validateSongForm()">
            <input type="hidden" id="songId" name="songId" value="">
            <div>
                <label for="title">Titre:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="artist">Artiste(s):</label>
                <input type="text" id="artist" name="artist" required>
            </div>
            <div>
                <label for="style">Style:</label>
                <input type="text" id="style" name="style" required>
            </div>
            <button type="submit" name="saveSong">Enregistrer</button>
        </form>
    </div>
</div>

<script>
function filterSongs() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toUpperCase();
    const table = document.querySelector('.setlist-table');
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let found = false;
        
        const columnsToSearch = <?php echo $is_logged_in ? 3 : 3; ?>;
        for (let j = 0; j < columnsToSearch; j++) {
            if (td[j] && td[j].innerText.toUpperCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        tr[i].style.display = found ? '' : 'none';
    }
}

function addSong() {
    document.getElementById('modalTitle').innerText = 'Ajouter une chanson';
    document.getElementById('songId').value = '';
    document.getElementById('title').value = '';
    document.getElementById('artist').value = '';
    document.getElementById('style').value = '';
    document.getElementById('songModal').style.display = 'block';
}

function editSong(id, title, artist, style) {
    document.getElementById('modalTitle').innerText = 'Modifier la chanson';
    document.getElementById('songId').value = id;
    document.getElementById('title').value = title;
    document.getElementById('artist').value = artist;
    document.getElementById('style').value = style;
    document.getElementById('songModal').style.display = 'block';
}

function deleteSong(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette chanson?')) {
        window.location.href = 'manage_song.php?delete=' + id;
    }
}

function validateSongForm() {
    const title = document.getElementById('title').value.trim();
    const artist = document.getElementById('artist').value.trim();
    const style = document.getElementById('style').value.trim();
    
    if (!title || !artist || !style) {
        alert('Tous les champs sont obligatoires');
        return false;
    }
    
    return true;
}

window.onclick = function(event) {
    const loginModal = document.getElementById('loginModal');
    const songModal = document.getElementById('songModal');
    
    if (event.target == loginModal) {
        loginModal.style.display = 'none';
    }
    if (event.target == songModal) {
        songModal.style.display = 'none';
    }
}
</script>

<?php require 'footer.php'; ?>