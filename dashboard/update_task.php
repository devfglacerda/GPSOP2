<?php
// update_task.php
session_start(); // Inicia sessão para pegar o usuário logado
header('Content-Type: application/json; charset=utf-8');
require_once 'conexao.php';

// Força o padrão do banco
$conn->set_charset("latin1"); 

try {
    // 1. Verifica login
    if (!isset($_SESSION['dashboard_usuario_id'])) {
        throw new Exception("Usuário não logado.");
    }
    $usuario_id = $_SESSION['dashboard_usuario_id'];

    // 2. VERIFICAÇÃO DE SEGURANÇA (PERFIL 11)
    // Mesmo que o JS esconda o select, validamos no backend se o usuário TEM permissão
    $sqlPerm = "SELECT 1 FROM perfil_usuario WHERE perfil_usuario_usuario = ? AND perfil_usuario_perfil = 11";
    $stmtPerm = $conn->prepare($sqlPerm);
    $stmtPerm->bind_param("i", $usuario_id);
    $stmtPerm->execute();
    $resultPerm = $stmtPerm->get_result();
    
    if ($resultPerm->num_rows === 0) {
        throw new Exception("Acesso Negado: Você não tem permissão para alterar dados.");
    }
    $stmtPerm->close();

    // 3. Recebe e valida dados
    $tarefa_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $tipo      = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $valor     = isset($_POST['valor']) ? intval($_POST['valor']) : 0;

    if ($tarefa_id <= 0) throw new Exception("ID da tarefa inválido.");

    // Só permite alterar prioridade (conforme sua última solicitação)
    if ($tipo === 'prioridade') {
        $sql = "UPDATE tarefas SET tarefa_prioridade = ? WHERE tarefa_id = ?";
    } 
    else {
        throw new Exception("Apenas a prioridade pode ser alterada.");
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("Erro SQL: " . $conn->error);

    $stmt->bind_param("ii", $valor, $tarefa_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Atualizado com sucesso']);
    } else {
        throw new Exception("Erro ao atualizar: " . $stmt->error);
    }
    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

if(isset($conn)) $conn->close();
?>