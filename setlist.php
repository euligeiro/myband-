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
    die("Erro ao buscar dados: " . $e->getMessage());
}
?>

<section class="setlist-section">
    <h1 class="setlist-title">Set List</h1>
    <div class="setlist-container">
        <div style="overflow-x: auto;">
            <table class="setlist-table">
                <thead>
                    <tr>
                        <th>
                            <a href="?order_by=title&order_dir=<?php echo ($order_by === 'title' ? $next_order_dir : 'ASC'); ?>">
                                TITLE
                                <?php if ($order_by === 'title'): ?>
                                    <span class="sort-arrow">
                                        <?php echo ($order_dir === 'ASC') ? '▲' : '▼'; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?order_by=artist&order_dir=<?php echo ($order_by === 'artist' ? $next_order_dir : 'ASC'); ?>">
                                ARTIST(S)
                                <?php if ($order_by === 'artist'): ?>
                                    <span class="sort-arrow">
                                        <?php echo ($order_dir === 'ASC') ? '▲' : '▼'; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="?order_by=style&order_dir=<?php echo ($order_by === 'style' ? $next_order_dir : 'ASC'); ?>">
                                STYLE
                                <?php if ($order_by === 'style'): ?>
                                    <span class="sort-arrow">
                                        <?php echo ($order_dir === 'ASC') ? '▲' : '▼'; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($songs) > 0): ?>
                        <?php foreach ($songs as $song): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($song['title']); ?></td>
                                <td><?php echo htmlspecialchars($song['artist']); ?></td>
                                <td><?php echo htmlspecialchars($song['style']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No songs in setlist.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>