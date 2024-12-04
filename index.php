<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portal_noticias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Buscar notícias aprovadas
$sql = "SELECT titulo, resumo, imagem, nome FROM noticias WHERE status = 'aprovada'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Frijole&family=Inter:wght@100..900&family=Knewave&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <title>Home Page</title>
</head>

<body>
    <header>
        <h1 class="logo">LoopMusic</h1>

        <ul>
            <li><a href="login.php">Login Funcionários</a></li>
            <li><a href="contato.html">Contato</a></li>
        </ul>
    </header>

    <div class="text-box">
        <h2>
            <span>Quem somos?</span><br>
            No LoopMusic, somos apaixonados por música e dedicados a trazer as últimas notícias, tendências e lançamentos de todos os estilos. Nosso objetivo é conectar você ao que há de mais relevante no mundo musical, com conteúdos selecionados por uma equipe que vive e respira música. Seja qual for o seu ritmo, aqui você encontra o que está bombando no cenário musical. Entre no loop e fique por dentro de tudo!
        </h2>
    </div>
<section class=container>
    <div class="text-box-noticias">
        <h2>
            <span>Notícias</span>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($noticia = $result->fetch_assoc()): ?>
                <div class="noticia">
    <img src="<?php echo htmlspecialchars($noticia['imagem']); ?>" alt="Imagem da notícia" class="img-noticia">
    <div>
        <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
        <p><?php echo htmlspecialchars($noticia['resumo']); ?></p>
        <p><i class="bi bi-person-fill"></i> <strong>Por </strong> <?php echo htmlspecialchars($noticia['nome']); ?></p>
    </div>
</div>

            <?php endwhile; ?>
        <?php else: ?>
            <p>Não há notícias disponíveis no momento.</p>
        <?php endif; ?>
    </div>
        </section>
</body>
</html>

<?php
$conn->close();
?>
