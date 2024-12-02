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
$sql = "SELECT titulo, resumo, imagem FROM noticias WHERE status = 'aprovada'";
$result = $conn->query($sql);

// Defina o nome do autor (exemplo: 'João da Silva')
$nome = 'João da Silva';  // Ou o nome que você deseja exibir
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Frijole&family=Inter:wght@100..900&family=Knewave&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <title>home page</title>
</head>

<body>
    <header>
        <h1 class="logo">loopmusic</h1>

        <ul>
            <li><a href="login.php">Login funcionários</a></li>
            <li><a href="contato.html">Contato</a></li>
        </ul>
    </header>
    <div class="text-box">
        <h2>
            <span>Quem somos?</span>  
            No LoopMusic, somos
            apaixonados por
            música e dedicados a
            trazer as últimas
            notícias, tendências e
            lançamentos de
            todos os estilos. Nosso
            objetivo é conectar
            você ao que há de
            mais relevante no
            mundo musical, com
            conteúdos
            selecionados por uma
            equipe que vive e
            respira música. Seja
            qual for o seu ritmo,
            aqui você encontra o
            que está bombando
            no cenário musical.
            Entre no loop e fique
            por dentro de tudo!
        </h2>
    </div>

    <div class="text-box">
        <h2>Notícias</h2>
        <?php while ($noticia = $result->fetch_assoc()): ?>
            <div class="noticia">
                <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                <img src="<?php echo $noticia['imagem']; ?>" alt="Imagem da notícia">
                <p><?php echo htmlspecialchars($noticia['resumo']); ?></p>
                <p><strong>Autor:</strong> <?php echo htmlspecialchars($nome); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>

<?php
$conn->close();
?>
