<?php
// Parâmetros de conexão com o banco de dados
$servername = "localhost"; // Ou o endereço do seu servidor MySQL
$username = "root"; // Usuário do banco de dados (padrão no XAMPP é 'root')
$password = ""; // Senha do banco de dados (padrão no XAMPP é vazio)
$dbname = "portal_noticias"; // Nome do banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtendo os dados do formulário
    $nome = $_POST['nome'] ?? null;
    $tipo_usuario = $_POST['tipo_usuario'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;

    // Validando os campos
    if (!$nome || !$tipo_usuario || !$email || !$senha) {
        die("Por favor, preencha todos os campos.");
    }

    // A senha será armazenada em texto puro (não recomendado para segurança)
    $senha_hash = $senha; // Senha em texto puro (não seguro, mas de acordo com seu pedido)

    // Inserindo no banco de dados
    $sql = "INSERT INTO usuarios (nome, tipo_usuario, email, senha) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $nome, $tipo_usuario, $email, $senha_hash);

    if ($stmt->execute()) {
        // Redirecionando conforme o tipo de usuário
        if ($tipo_usuario == 'administrador') {
            header('Location: adm.php'); // Redireciona para a página do administrador
        } elseif ($tipo_usuario == 'escritor') {
            header('Location: escritor.php'); // Redireciona para a página do escritor
        }
        exit; // Para garantir que o script pare de ser executado
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Frijole&family=Inter:wght@100..900&family=Knewave&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <title>Cadastro</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <header>
        <div class="logo">loopmusic</div>
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
    </header>

    <h2 class="title">Cadastro</h2>

    <div class="form-container">
        <form id="formCadastro" method="POST" action="cadastro.php">
            <label for="nome">Nome</label>
            <div class="input-container">
                <div class="icon-placeholder"><i class="bi bi-person-fill"></i></div>
                <input type="text" id="nome" name="nome" placeholder="Seu nome completo" required>
            </div>

            <label for="user-type">Selecione sua área:</label>
            <div class="input-container">
                <div class="icon-placeholder"><i class="bi bi-person-raised-hand"></i></div>
                <select id="user-type" name="tipo_usuario">
                 <option value="escritor">Escritor</option>
                 <option value="administrador">Administrador</option>
                </select>
            </div>

            <label for="email">E-mail</label>
            <div class="input-container">
                <div class="icon-placeholder">
                    <i class="bi bi-envelope-fill"></i> 
                </div>
                <input type="email" id="email" name="email" placeholder="Seu e-mail" required>
            </div>
            
            <label for="senha">Senha</label>
            <div class="input-container">
                <div class="icon-placeholder"><i class="bi bi-key"></i></div>
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
            </div>

            <button type="submit">Cadastrar</button>
            <p><a href="login.php">Já tem uma conta? Faça login</a></p>
        </form>
    </div>
</body>
</html>
