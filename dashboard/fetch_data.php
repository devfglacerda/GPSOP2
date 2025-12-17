<?php
// fetch_data.php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0); 

require_once 'conexao.php';

try {
    $conn->set_charset("utf8");

    // ---------------------------------------------------------
    // 1. RECEBIMENTO E SANITIZAÇÃO DOS FILTROS
    // ---------------------------------------------------------
    $filtro_solicitante  = isset($_POST['usuario_id']) ? $_POST['usuario_id'] : 0; 
    $status_filtro       = isset($_POST['status_id']) ? $_POST['status_id'] : '';
    
    // Filtros Removidos: Gerencia e Profissional
    
    $filtro_tipo       = isset($_POST['tipo_id']) ? $_POST['tipo_id'] : 0;
    $filtro_prioridade = isset($_POST['prioridade_id']) ? $_POST['prioridade_id'] : 'all';

    $data_inicio = !empty($_POST['data_inicio']) ? $_POST['data_inicio'] : date('Y-01-01');
    $data_fim    = !empty($_POST['data_fim']) ? $_POST['data_fim'] : date('Y-12-31');
    $usar_periodo = (isset($_POST['usar_periodo']) && $_POST['usar_periodo'] === 'true');

    // ---------------------------------------------------------
    // 2. CARREGAMENTO DOS MAPAS (NOMES)
    // ---------------------------------------------------------
    $mapa_setores = [];
    $mapa_status  = [];
    $mapa_tipos   = []; 

    $tabela_nova_existe = false;
    $check_table = $conn->query("SHOW TABLES LIKE 'sisvalores'");
    if ($check_table && $check_table->num_rows > 0) {
        $tabela_nova_existe = true;
    }

    if ($tabela_nova_existe) {
        $res_setor = $conn->query("SELECT sisvalor_valor_id, sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'Setor'");
        if ($res_setor) { while ($row = $res_setor->fetch_assoc()) { $mapa_setores[trim($row['sisvalor_valor_id'])] = trim($row['sisvalor_valor']); $mapa_setores[intval($row['sisvalor_valor_id'])] = trim($row['sisvalor_valor']); } }
        $res_status = $conn->query("SELECT sisvalor_valor_id, sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'StatusTarefa'");
        if ($res_status) { while ($row = $res_status->fetch_assoc()) { $mapa_status[intval($row['sisvalor_valor_id'])] = trim($row['sisvalor_valor']); } }
        $res_tipo = $conn->query("SELECT sisvalor_valor_id, sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'TipoTarefa'");
        if ($res_tipo) { while ($row = $res_tipo->fetch_assoc()) { $mapa_tipos[intval($row['sisvalor_valor_id'])] = trim($row['sisvalor_valor']); } }
    } 
    
    // Fallbacks
    if (empty($mapa_setores)) {
        $sql_old = "SELECT sysval_value FROM sysvals WHERE sysval_title = 'Setor' LIMIT 1";
        $res_old = $conn->query($sql_old);
        if ($res_old && $row = $res_old->fetch_assoc()) {
            $linhas = preg_split('/\r\n|\r|\n/', $row['sysval_value']);
            foreach ($linhas as $linha) {
                if (strpos($linha, '|') !== false) {
                    list($chave, $valor) = explode('|', $linha);
                    $mapa_setores[trim($chave)] = trim($valor);
                    $mapa_setores[intval($chave)] = trim($valor);
                }
            }
        }
    }
    
    // Mapa Usuários
    $user_sql = "SELECT u.usuario_login, ct.contato_primeiro_nome, ct.contato_ultimo_nome FROM usuarios u LEFT JOIN contatos ct ON u.usuario_contato = ct.contato_id";
    $user_res = $conn->query($user_sql);
    if ($user_res) {
        while ($u = $user_res->fetch_assoc()) {
            $nome = trim($u['contato_primeiro_nome'] . ' ' . $u['contato_ultimo_nome']);
            if (empty($nome)) $nome = $u['usuario_login'];
            $mapa_setores[trim($u['usuario_login'])] = $nome;
        }
    }

    // ---------------------------------------------------------
    // 3. MONTAR AS LISTAS PARA OS FILTROS DO FRONTEND
    // ---------------------------------------------------------
    
    // A) LISTA DE SOLICITANTES
    $lista_filtro = [];
    $setor_sql = "SELECT DISTINCT p.projeto_setor FROM projetos p INNER JOIN tarefas t ON p.projeto_id = t.tarefa_projeto WHERE p.projeto_ativo = 1 AND p.projeto_template = 0 AND p.projeto_setor IS NOT NULL AND p.projeto_setor != '' AND p.projeto_setor != '0' ORDER BY p.projeto_setor ASC";
    $setor_res = $conn->query($setor_sql);
    if ($setor_res) {
        while ($row = $setor_res->fetch_assoc()) {
            $cod = trim($row['projeto_setor']);
            if (empty($cod) || $cod === 'null' || $cod == '0') continue;
            $nome_exibicao = isset($mapa_setores[$cod]) ? $mapa_setores[$cod] : (isset($mapa_setores[intval($cod)]) ? $mapa_setores[intval($cod)] : ((is_numeric($cod) && $cod > 99) ? "Matrícula ".$cod : "Setor ".$cod));
            $lista_filtro[] = ['usuario_id' => $cod, 'usuario_login' => $nome_exibicao];
        }
    }

    // LISTAS DE GERENCIAS E PROFISSIONAIS REMOVIDAS

    // D) LISTA DE STATUS
    $lista_status = [];
    $status_res = $conn->query("SELECT DISTINCT t.tarefa_status FROM tarefas t INNER JOIN projetos p ON t.tarefa_projeto = p.projeto_id WHERE p.projeto_ativo = 1 AND p.projeto_template = 0 ORDER BY t.tarefa_status ASC");
    if ($status_res) { while ($row = $status_res->fetch_assoc()) { $cod = intval($row['tarefa_status']); $lista_status[] = ['id' => $cod, 'nome' => (isset($mapa_status[$cod]) ? $mapa_status[$cod] : "Status ".$cod)]; } }

    // E) LISTA DE TIPOS
    $lista_tipos = [];
    $tipo_res = $conn->query("SELECT DISTINCT t.tarefa_tipo FROM tarefas t INNER JOIN projetos p ON t.tarefa_projeto = p.projeto_id WHERE p.projeto_ativo = 1 AND p.projeto_template = 0 ORDER BY t.tarefa_tipo ASC");
    if ($tipo_res) { while ($row = $tipo_res->fetch_assoc()) { $cod = intval($row['tarefa_tipo']); if ($cod == 0) continue; $lista_tipos[] = ['id' => $cod, 'nome' => (isset($mapa_tipos[$cod]) ? $mapa_tipos[$cod] : "Tipo ".$cod)]; } }

    // F) LISTA DE PRIORIDADES
    $lista_prioridades = [];
    $prio_res = $conn->query("SELECT DISTINCT t.tarefa_prioridade FROM tarefas t INNER JOIN projetos p ON t.tarefa_projeto = p.projeto_id WHERE p.projeto_ativo = 1 AND p.projeto_template = 0 ORDER BY t.tarefa_prioridade DESC");
    if ($prio_res) { while ($row = $prio_res->fetch_assoc()) { $cod = intval($row['tarefa_prioridade']); $nm = 'Normal'; if ($cod < 0) $nm = 'Baixa'; if ($cod > 0) $nm = 'Alta'; if ($cod <= -2) $nm = 'Muito Baixa'; if ($cod >= 2) $nm = 'Muito Alta'; $lista_prioridades[] = ['id' => $cod, 'nome' => $nm]; } }

    // ---------------------------------------------------------
    // 4. QUERY PRINCIPAL (DADOS DO DASHBOARD)
    // ---------------------------------------------------------
    $sql = "
        SELECT 
            t.tarefa_id, t.tarefa_nome as demanda, t.tarefa_descricao, t.tarefa_fim as data_fim,
            t.tarefa_status as status_cod, t.tarefa_prioridade as prioridade_cod, t.tarefa_tipo as tipo_cod,
            p.projeto_nome as projeto, p.projeto_setor
        FROM tarefas t
        LEFT JOIN projetos p ON t.tarefa_projeto = p.projeto_id
        LEFT JOIN tarefa_designados td ON t.tarefa_id = td.tarefa_id 
        WHERE p.projeto_ativo = 1 AND p.projeto_template = 0 AND t.tarefa_dinamica = 0
    ";

    // --- APLICAÇÃO DOS FILTROS ---

    // 1. Solicitante
    if ($filtro_solicitante != '0' && $filtro_solicitante != '') {
        $fs = $conn->real_escape_string($filtro_solicitante);
        $sql .= " AND p.projeto_setor = '$fs' ";
    }

    // Filtros de Gerencia e Profissional REMOVIDOS do WHERE

    // 4. Outros filtros (Tipo, Prioridade, Status, Data)
    if ($filtro_tipo != '0' && $filtro_tipo != '') { $ft = intval($filtro_tipo); $sql .= " AND t.tarefa_tipo = $ft "; }
    if ($filtro_prioridade !== 'all' && $filtro_prioridade !== '') { $fp = intval($filtro_prioridade); $sql .= " AND t.tarefa_prioridade = $fp "; }
    
    if (strlen($status_filtro) > 0) {
        $ids = explode(',', $status_filtro);
        $ids_validos = []; $filtrar_nulos = false;
        foreach($ids as $id) { $id = trim($id); if ($id === '0' || $id === '') $filtrar_nulos = true; elseif (is_numeric($id)) $ids_validos[] = intval($id); }
        $clauses = [];
        if (count($ids_validos) > 0) $clauses[] = "t.tarefa_status IN (" . implode(',', $ids_validos) . ")";
        if ($filtrar_nulos) $clauses[] = "(t.tarefa_status = 0 OR t.tarefa_status IS NULL)";
        if (count($clauses) > 0) $sql .= " AND (" . implode(' OR ', $clauses) . ") ";
    }

    if ($usar_periodo) {
        $dt_ini = $conn->real_escape_string($data_inicio);
        $dt_fim = $conn->real_escape_string($data_fim);
        $sql .= " AND (t.tarefa_fim >= '$dt_ini' AND t.tarefa_fim <= '$dt_fim') ";
    }

    $sql .= " GROUP BY t.tarefa_id ORDER BY t.tarefa_fim ASC LIMIT 50000";
    
    $result = $conn->query($sql);
    
    $tasks = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cod_setor = trim($row['projeto_setor']);
            if (empty($cod_setor) || $cod_setor == '0' || $cod_setor == 'null') continue; 
            $nome_solicitante = isset($mapa_setores[$cod_setor]) ? $mapa_setores[$cod_setor] : (isset($mapa_setores[intval($cod_setor)]) ? $mapa_setores[intval($cod_setor)] : ((is_numeric($cod_setor) && $cod_setor > 99) ? "Matrícula ".$cod_setor : "Setor ".$cod_setor));
            $cod_status = $row['status_cod'];
            $nome_status = isset($mapa_status[$cod_status]) ? $mapa_status[$cod_status] : "Status ".$cod_status;
            if (($cod_status == 0 || $cod_status == '') && !isset($mapa_status[0])) $nome_status = 'Não informado';
            $cod_tp = $row['tipo_cod'];
            $nome_cat = isset($mapa_tipos[$cod_tp]) ? $mapa_tipos[$cod_tp] : "Geral";
            $p = intval($row['prioridade_cod']);
            $nm_prio = 'Normal'; if ($p < 0) $nm_prio = 'Baixa'; if ($p > 0) $nm_prio = 'Alta'; if ($p <= -2) $nm_prio = 'Muito Baixa'; if ($p >= 2) $nm_prio = 'Muito Alta';
            $desc_tooltip = strip_tags($row['tarefa_descricao']);
            if(empty($desc_tooltip)) $desc_tooltip = "Sem descrição detalhada.";

            $tasks[] = [
                'id' => $row['tarefa_id'], 'projeto' => $row['projeto'], 'demanda' => $row['demanda'],
                'descricao' => $desc_tooltip, 'solicitante' => $nome_solicitante, 'status' => $nome_status,
                'categoria' => $nome_cat, 'prioridade' => $nm_prio,
                'data' => ($row['data_fim']) ? date('d/m/Y', strtotime($row['data_fim'])) : '-'
            ];
        }
    }

    echo json_encode([
        'success' => true, 'tasks' => $tasks, 'lista_usuarios' => $lista_filtro,
        'lista_status' => $lista_status, 'lista_tipos' => $lista_tipos, 'lista_prioridades' => $lista_prioridades
    ]);

} catch (Exception $e) { echo json_encode(['error' => $e->getMessage()]); }
if(isset($conn)) $conn->close();
?>