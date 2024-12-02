<?php
session_start(); // Iniciar a sessão

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portal_noticias";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'] ?? null;
    $tipo_usuario = $_POST['tipo_usuario'] ?? null;

    // Validar os campos
    if (!$email || !$senha || !$tipo_usuario) {
        $error_message = "Por favor, preencha todos os campos.";
    } else {
        // Consultar o banco de dados
        $sql = "SELECT id, senha, tipo_usuario FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email); // Previne SQL Injection
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Comparar a senha diretamente
            if ($senha === $user['senha']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario']; // Adicionar tipo de usuário na sessão

                // Redirecionar com base no tipo de usuário
                if ($user['tipo_usuario'] === 'administrador') {
                    header('Location: adm.php');
                } elseif ($user['tipo_usuario'] === 'escritor') {
                    header('Location: escritor.php');
                }
                exit;
            } else {
                $error_message = "Senha incorreta.";
            }
        } else {
            $error_message = "Usuário não encontrado.";
        }

        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Coiny&family=Frijole&family=Inter:wght@100..900&family=Knewave&family=Oswald:wght@200..700&family=Trade+Winds&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="form.css">
    <title>Login</title>
</head>
<body>
    <header>
        <div class="logo">loopmusic</div>
        <ul>
            <li><a href="index.html">Home</a></li>
        </ul>
    </header>

    <h2 class="title">Login</h2>

    <div class="form-container">
        <form id="formLogin" method="POST" action="">
            <!-- Exibir mensagem de erro se houver -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <label for="user-type">Selecione sua área:</label>
            <div class="input-container">
                <div class="icon-placeholder"><i class="bi bi-person-raised-hand"></i></div>
                <select id="user-type" name="tipo_usuario" required>
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
                <div class="icon-placeholder">
                    <i class="bi bi-lock-fill"></i>
                </div>
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
            </div>

            <button type="submit">Entrar</button>
            <p><a href="cadastro.php">Cadastre-se</a></p>
        </form>
    </div>
</body>
</html>
