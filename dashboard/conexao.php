<?php
// Arquivo: conexao.php
// Esse arquivo "engana" o sistema antigo, conectando direto ao banco
require_once 'config.php'; 

if (!isset($config) || empty($config)) {
    die(json_encode(['error' => 'Erro: config.php não carregou as credenciais.']));
}

try {
    $conn = new mysqli(
        $config['hospedadoBd'], 
        $config['usuarioBd'], 
        $config['senhaBd'], 
        $config['nomeBd']
    );

    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>