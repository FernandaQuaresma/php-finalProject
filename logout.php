<?php
session_start(); // Iniciar a sessão

// Destruir todas as variáveis de sessão
session_unset();

// Destruir a sessão
session_destroy();

// Redirecionar para a página de login
header('Location: login.php');
exit;
?>
