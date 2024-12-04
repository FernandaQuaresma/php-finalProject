<?php
session_start();

// Verificar autenticação e permissão
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'escritor') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexão ao banco
    $conn = new mysqli("localhost", "root", "", "portal_noticias");

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Dados do formulário
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
    $resumo = $_POST['resumo'];
    $nome = $_POST['nome'];
    $status = 'pendente';
    $imagem = null;

    // Upload da imagem (opcional)
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $diretorio = 'uploads/imagens/';
        $imagemNome = basename($_FILES['imagem']['name']);
        $caminhoImagem = $diretorio . $imagemNome;

        $tipoImagem = strtolower(pathinfo($caminhoImagem, PATHINFO_EXTENSION));
        $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($tipoImagem, $tiposPermitidos)) {
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
                $imagem = $caminhoImagem;
            }
        }
    }

    // Inserir no banco
    $sql = "INSERT INTO noticias (titulo, imagem, texto, resumo, nome, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $titulo, $imagem, $texto, $resumo, $nome, $status);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    // Redirecionar após o envio
    header('Location: escritor.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Frijole&family=Inter:wght@100..900&family=Knewave&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <title>Área Escritor</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <header>
        <div class="logo">loopmusic</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </header>

    <h2 class="title">Área do Escritor</h2>

    <div class="form-container">
    <form method="POST" enctype="multipart/form-data">
        <label for="titulo">Insira o título</label>
        <div class="input-container">
            <div class="icon-placeholder"><i class="bi bi-blockquote-left"></i></div>
            <input type="text" id="titulo" name="titulo" required>
        </div>

        <label for="imagem">Insira a imagem</label>
        <div class="input-container">
            <div class="icon-placeholder"><i class="bi bi-images"></i></div>
            <input type="file" id="imagem" name="imagem" required>
        </div>

        <label for="texto">Insira o texto da sua matéria</label>
        <div class="input-container">
            <div class="icon-placeholder"><i class="bi bi-blockquote-left"></i></div>
            <textarea id="texto" name="texto" required></textarea>
        </div>

        <label for="resumo">Insira o resumo da sua matéria</label>
        <div class="input-container">
            <div class="icon-placeholder"><i class="bi bi-blockquote-left"></i></div>
            <input type="text" id="resumo" name="resumo" required>
        </div>

        <label for="nome">Insira seu nome</label>
        <div class="input-container">
            <div class="icon-placeholder"><i class="bi bi-person-circle"></i></div>
            <input type="text" id="nome" name="nome" required>
        </div>

        <button type="submit">Enviar ao admin</button>
    </form>
    </div>

    <p><a href="logout.php">Sair</a></p>
</body>
</html>
