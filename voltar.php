<?php
/**
 * Arquivo responsável por gerenciar a navegação segura de "Voltar"
 * Evita o erro de reenvio de formulário (Tela Branca)
 */

require_once 'base.php';
require_once BASE_DIR.'/incluir/sessao.php';

// Inicia sessão para acessar o histórico
sessaoIniciar(array('Aplic'));

if (isset($_SESSION['gpweb_historico']) && count($_SESSION['gpweb_historico']) > 1) {
    // 1. Remove a página atual (onde o usuário clicou no botão voltar)
    array_pop($_SESSION['gpweb_historico']);
    
    // 2. Pega a página anterior (o destino)
    $urlDestino = end($_SESSION['gpweb_historico']);
    
    // 3. Redireciona
    header("Location: " . $urlDestino);
    exit;
} else {
    // Se não tiver histórico, manda para o início do sistema
    header("Location: index.php");
    exit;
}
?>