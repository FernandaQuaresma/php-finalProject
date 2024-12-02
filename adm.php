<?php
session_start();

// Verificar se o usuário é um administrador
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: login.php');
    exit;
}

// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portal_noticias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Processar a aprovação ou recusa de uma notícia
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action']; // "aceitar" ou "recusar"

    if ($action === 'aceitar') {
        $status = 'aprovada';
    } elseif ($action === 'recusar') {
        $status = 'recusada';
    }

    // Atualizar o status da notícia
    $sql = "UPDATE noticias SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();

    // Redirecionar após a atualização
    header("Location: adm.php");
    exit;
}

// Buscar todas as matérias pendentes
$sql = "SELECT * FROM noticias WHERE status = 'pendente'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row['titulo'] . "</h3>";
        echo "<p>" . $row['resumo'] . "</p>";
        echo "<img src='" . $row['imagem'] . "' alt='Imagem da matéria'>";
        echo "<form method='POST' action='alterar_status.php'>";
        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        echo "<button type='submit' name='status' value='aprovado'>Aprovar</button>";
        echo "<button type='submit' name='status' value='rejeitado'>Rejeitar</button>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "Não há matérias pendentes.";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <h2 class="title">Área do Administrador</h2>

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
                <a href="alterar_status.php?id=<?php echo $noticia['id']; ?>&action=aceitar">Aceitar</a> |
<a href="alterar_status.php?id=<?php echo $noticia['id']; ?>&action=recusar">Recusar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="logout.php">Sair</a></p>
</body>
</html>

<?php
$conn->close();
?>  
