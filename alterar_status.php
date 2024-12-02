<?php
session_start();

// Verificar se o usuário é um administrador
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header('Location: login.php');
    exit;
}

// Verificar se os parâmetros necessários estão presentes
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Definir o novo status baseado na ação (aceitar ou recusar)
    if ($action === 'aceitar') {
        $status = 'aprovada';
    } elseif ($action === 'recusar') {
        $status = 'recusada';
    } else {
        // Se a ação for inválida, redirecionar ou mostrar erro
        echo "Ação inválida.";
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

    // Atualizar o status da matéria
    $sql = "UPDATE noticias SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // Redirecionar de volta para a página do administrador após atualizar o status
        header('Location: adm.php');
        exit;
    } else {
        echo "Erro ao atualizar o status da matéria.";
    }

    $stmt->close();
    $conn->close();
} else {
    // Caso não existam os parâmetros esperados
    echo "Parâmetros inválidos.";
}
?>
