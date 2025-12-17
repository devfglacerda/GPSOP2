<?php
// auth.php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'conexao.php'; 

// Força latin1 para compatibilidade com banco antigo
$conn->set_charset("latin1"); 

$response = ['success' => false, 'message' => 'Erro desconhecido'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

    if (empty($usuario) || empty($senha)) {
        echo json_encode(['success' => false, 'message' => 'Preencha usuário e senha.']);
        exit;
    }

    // 1. Verifica Usuário e Senha
    $sql = "SELECT 
                u.usuario_id, 
                u.usuario_login, 
                u.usuario_contato, 
                c.contato_nomeguerra, 
                c.contato_posto
            FROM usuarios u
            LEFT JOIN contatos c ON u.usuario_contato = c.contato_id
            WHERE u.usuario_login = ? 
            AND u.usuario_senha = MD5(?) 
            AND u.usuario_ativo = 1 
            LIMIT 1";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $usuario, $senha);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $usuario_id = $user_data['usuario_id'];

            // ---------------------------------------------------------
            // 2. VERIFICAÇÃO DE PERFIS (SEGURANÇA ADICIONAL)
            // ---------------------------------------------------------
            $perfis = [];
            $sql_perfil = "SELECT perfil_usuario_perfil FROM perfil_usuario WHERE perfil_usuario_usuario = ?";
            if ($stmt_p = $conn->prepare($sql_perfil)) {
                $stmt_p->bind_param("i", $usuario_id);
                $stmt_p->execute();
                $res_p = $stmt_p->get_result();
                while($row_p = $res_p->fetch_assoc()){
                    $perfis[] = (int)$row_p['perfil_usuario_perfil'];
                }
                $stmt_p->close();
            }

            // REGRA 1: Bloqueia Perfil 18
            if (in_array(18, $perfis)) {
                echo json_encode(['success' => false, 'message' => 'Acesso negado para o seu perfil (Restrição 18).']);
                exit;
            }

            // REGRA 2: Verifica permissão de edição (Perfil 11)
            $pode_editar = in_array(11, $perfis);

            // ---------------------------------------------------------
            // 3. CRIA A SESSÃO
            // ---------------------------------------------------------
            $_SESSION['dashboard_usuario_id'] = $usuario_id;
            
            $nome_exibicao = trim($user_data['contato_posto'] . ' ' . $user_data['contato_nomeguerra']);
            if (empty($nome_exibicao)) $nome_exibicao = $user_data['usuario_login'];
            
            $_SESSION['dashboard_usuario_nome'] = utf8_encode($nome_exibicao);
            $_SESSION['dashboard_usuario_login'] = $user_data['usuario_login'];
            
            // Salva a permissão na sessão para usar no resto do sistema
            $_SESSION['dashboard_pode_editar'] = $pode_editar;

            $response = [
                'success' => true,
                'message' => 'Login realizado com sucesso!',
                'redirect' => 'dashboard.php'
            ];
        } else {
            $response = ['success' => false, 'message' => 'Usuário ou senha incorretos.'];
        }
        $stmt->close();
    } else {
        $response = ['success' => false, 'message' => 'Erro no banco: ' . $conn->error];
    }
}

echo json_encode($response);
$conn->close();
?>