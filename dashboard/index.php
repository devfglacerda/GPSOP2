<?php
// index.php
session_start();

// 1. Verifica se o usuário JÁ está logado
if (isset($_SESSION['dashboard_usuario_id'])) {
    // Se sim, manda direto para o Dashboard
    header("Location: dashboard.php");
    exit;
}

// 2. Se não estiver logado, manda para a tela de Login
header("Location: login.php");
exit;
?>