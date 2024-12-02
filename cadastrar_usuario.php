<?php
// Incluindo a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtendo os dados do formulário
    $nome = $_POST['nome'] ?? null; // Adicionado o campo 'nome'
    $tipo_usuario = $_POST['tipo_usuario'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;

    // Validando os campos
    if (!$nome || !$tipo_usuario || !$email || !$senha) {
        die("Por favor, preencha todos os campos.");
    }

    // Inserindo no banco de dados (senha em texto puro)
    $sql = "INSERT INTO usuarios (nome, tipo_usuario, email, senha) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $nome, $tipo_usuario, $email, $senha); // Alterado para incluir 'nome'

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso! <a href='login.html'>Faça login</a>";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
