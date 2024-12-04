<?php
session_start();

// Verificar se o usuário é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: login.php');
    exit;
}

// Conectar ao banco
$conn = new mysqli("localhost", "root", "", "portal_noticias");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Alterar status da notícia
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'aceitar') {
        $status = 'aprovada';
    } elseif ($action === 'recusar') {
        $status = 'recusada';
    }

    if (isset($status)) {
        $sql = "UPDATE noticias SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Buscar matérias pendentes
$sql = "SELECT id, titulo, nome FROM noticias WHERE status = 'pendente'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Frijole&family=Inter:wght@100..900&family=Knewave&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="adm.css">
    <title>Área do Administrador</title>
</head>
<body>
<header>
        <div class="logo">loopmusic</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </header>

    <h3>Notícias Pendentes</h3>

    <table border="1">
        <tr>
            <th>Título</th>
            <th>Nome do Escritor</th>
            <th>Ações</th>
        </tr>

        <?php while ($noticia = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($noticia['titulo']); ?></td>
                <td><?php echo htmlspecialchars($noticia['nome']); ?></td>
                <td>
                    <a href="adm.php?id=<?php echo $noticia['id']; ?>&action=aceitar">Aceitar</a> |
                    <a href="adm.php?id=<?php echo $noticia['id']; ?>&action=recusar">Recusar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
