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
$sql = "SELECT id, titulo, resumo, imagem, nome FROM noticias WHERE status = 'pendente'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adm.css"> <!-- Reutilizando o CSS do index -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Frijole&family=Inter:wght@100..900&family=Knewave&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <title>Área do Administrador</title>
</head>
<body>
<header>
        <h1 class="logo">LoopMusic</h1>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="logout.php">logout</a></li>
        </ul>
    </header>

<div class="container">
    <h2 class="text-box-noticias">
        <span>Notícias Pendentes</span>
    </h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($noticia = $result->fetch_assoc()): ?>
            <div class="text-box">
                <div class="noticia">
                    <img src="<?php echo htmlspecialchars($noticia['imagem']); ?>" alt="Imagem da notícia" class="img-noticia">
                    <div>
                        <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($noticia['resumo']); ?></p>
                        <p><i class="bi bi-person-fill"></i> <strong>Por </strong><?php echo htmlspecialchars($noticia['nome']); ?></p>
                        <div class="botoes-acoes">
                            <a href="adm.php?id=<?php echo $noticia['id']; ?>&action=aceitar" class="botao aceitar">Aceitar</a>
                            <a href="adm.php?id=<?php echo $noticia['id']; ?>&action=recusar" class="botao recusar">Recusar</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-box-noticias">Nenhuma notícia pendente.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>