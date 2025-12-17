<?php
// update_cascade.php
require_once 'conexao.php';
$conn->set_charset("utf8");
date_default_timezone_set('America/Sao_Paulo');

$action = isset($_POST['action']) ? $_POST['action'] : '';

// =================================================================================
// CONFIGURAÇÃO DA ORDEM
// Ajustei para garantir que Paralisada caia no Grupo 3, mesmo tendo %
// =================================================================================
$sql_order_by = "
    ORDER BY 
    CASE 
        -- GRUPO 1: APENAS Andamento/Executando (Explicitamente)
        WHEN (
            (SELECT sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'StatusTarefa' AND sisvalor_valor_id = t.tarefa_status LIMIT 1) LIKE '%Andamento%' 
            OR 
            (SELECT sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'StatusTarefa' AND sisvalor_valor_id = t.tarefa_status LIMIT 1) LIKE '%Execu%'
        ) THEN 1
        
        -- GRUPO 2: A INICIAR
        WHEN (
            (SELECT sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'StatusTarefa' AND sisvalor_valor_id = t.tarefa_status LIMIT 1) LIKE '%A iniciar%'
            OR 
            ((t.tarefa_percentagem = 0 OR t.tarefa_percentagem IS NULL) AND t.tarefa_status NOT IN (SELECT sisvalor_valor_id FROM sisvalores WHERE sisvalor_titulo = 'StatusTarefa' AND (sisvalor_valor LIKE '%Paralisada%' OR sisvalor_valor LIKE '%Pend%')))
        ) THEN 2
        
        -- GRUPO 3: OUTROS (Paralisada, Pendência, Concluída - Jogados para o final)
        ELSE 3
    END ASC,

    -- ORDENAÇÃO SECUNDÁRIA
    CASE 
        WHEN (
            (SELECT sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'StatusTarefa' AND sisvalor_valor_id = t.tarefa_status LIMIT 1) LIKE '%A iniciar%'
        ) THEN t.tarefa_inicio
        ELSE t.tarefa_fim
    END ASC
";

// =================================================================================
// FUNÇÕES DE DIAS ÚTEIS
// =================================================================================
function getDiasUteis($dtInicio, $dtFim) {
    if (!$dtInicio || !$dtFim) return 0;
    $inicio = new DateTime($dtInicio);
    $fim = new DateTime($dtFim);
    $inicio->setTime(0,0,0); $fim->setTime(0,0,0);
    if ($inicio > $fim) return 1;
    $dias = 0;
    $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim->modify('+1 day')); 
    foreach ($periodo as $dt) {
        if ($dt->format('N') < 6) $dias++;
    }
    return $dias;
}

function proximoDiaUtil($dataBase) {
    $d = new DateTime($dataBase);
    do { $d->modify('+1 day'); } while ($d->format('N') >= 6);
    return $d;
}

function adicionarDiasUteis($dataBase, $diasParaAdicionar) {
    $d = new DateTime($dataBase);
    while ($d->format('N') >= 6) { $d->modify('+1 day'); }
    for ($i = 0; $i < $diasParaAdicionar; $i++) {
        $d->modify('+1 day');
        while ($d->format('N') >= 6) { $d->modify('+1 day'); }
    }
    return $d;
}

// =================================================================================
// 1. LISTAR TAREFAS
// =================================================================================
if ($action == 'list') {
    $user_id = intval($_POST['user_id']);

    $sql = "
        SELECT 
            t.tarefa_id, t.tarefa_nome, t.tarefa_fim, t.tarefa_inicio, 
            t.tarefa_percentagem, t.tarefa_prioridade, p.projeto_nome, t.tarefa_ordem, t.tarefa_status,
            (SELECT sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'StatusTarefa' AND sisvalor_valor_id = t.tarefa_status LIMIT 1) as nome_status
        FROM tarefas t
        INNER JOIN projetos p ON t.tarefa_projeto = p.projeto_id
        INNER JOIN tarefa_designados td ON t.tarefa_id = td.tarefa_id
        WHERE p.projeto_ativo = 1
        AND td.usuario_id = $user_id
        AND (t.tarefa_percentagem < 100 OR t.tarefa_percentagem IS NULL)
        AND t.tarefa_fim IS NOT NULL 
        $sql_order_by
    ";

    $res = $conn->query($sql);
    
    if($res && $res->num_rows > 0) {
        echo '<div class="task-header-row grid-template">
                <div style="text-align:center">☰</div>
                <div>TAREFA / PROJETO</div>
                <div style="text-align:center">PRIORIDADE</div>
                <div style="text-align:center">STATUS</div>
                <div style="text-align:center">INÍCIO (Ref)</div>
                <div style="text-align:center">DATA FINAL</div>
                <div style="text-align:center">DURAÇÃO (Útil)</div>
              </div>';
        
        echo '<ul class="task-list" id="sortableList">';
        
        while($row = $res->fetch_assoc()) {
            $data_fim_val = ($row['tarefa_fim']) ? date('Y-m-d', strtotime($row['tarefa_fim'])) : '';
            $data_ini_show = ($row['tarefa_inicio']) ? date('d/m/Y', strtotime($row['tarefa_inicio'])) : '--/--/----';

            $dias_display = '-';
            if ($row['tarefa_inicio'] && $row['tarefa_fim']) {
                $dias_uteis = getDiasUteis($row['tarefa_inicio'], $row['tarefa_fim']);
                $dias_display = $dias_uteis . 'd';
            }

            $nome_status_temp = isset($row['nome_status']) ? $row['nome_status'] : '';
            $status_real = mb_strtolower($nome_status_temp, 'UTF-8');
            $perc = intval($row['tarefa_percentagem']);
            
            $badge_class = 'st-blue'; 
            $row_style = ''; 

            if (strpos($status_real, 'andamento') !== false || strpos($status_real, 'execu') !== false) {
                $badge_class = 'st-yellow';
                $row_style = 'style="background-color:#fffdf5; border-left: 3px solid #F2C811;"'; 
            } 
            elseif (strpos($status_real, 'pend') !== false) { 
                $badge_class = 'st-pink'; 
            }
            elseif (strpos($status_real, 'paralisada') !== false || strpos($status_real, 'suspensa') !== false) {
                $badge_class = 'st-red'; 
            }
            elseif (strpos($status_real, 'conclu') !== false) {
                $badge_class = 'st-green';
            }

            $badge_text = (!empty($row['nome_status'])) ? $row['nome_status'] : 'Status '.$row['tarefa_status'];
            if ($perc > 0 && $perc < 100) $badge_text .= " ($perc%)";

            $prio = intval($row['tarefa_prioridade']);
            $prio_html = ($prio > 0) ? '<span class="prio-badge prio-high">▲ Alta</span>' : (($prio < 0) ? '<span class="prio-badge prio-low">▼ Baixa</span>' : '<span class="prio-badge prio-normal">● Normal</span>');

            echo '<li class="task-item grid-template" '.$row_style.' data-id="'.$row['tarefa_id'].'">';
            echo '<div class="drag-handle">☰</div>';
            echo '<div class="task-info"><strong>'. htmlspecialchars($row['tarefa_nome']) .'</strong><small>'. htmlspecialchars($row['projeto_nome']) .'</small></div>';
            echo '<div style="text-align:center">'. $prio_html .'</div>';
            echo '<div style="text-align:center"><span class="status-badge '.$badge_class.'">'. $badge_text .'</span></div>';
            echo '<div class="date-static">'. $data_ini_show .'</div>';
            echo '<div style="text-align:center"><input type="date" class="date-input" value="'.$data_fim_val.'" data-id="'.$row['tarefa_id'].'" data-old="'.$data_fim_val.'"></div>';
            echo '<div class="duration-text" title="Dias Úteis">'. $dias_display .'</div>';
            echo '</li>';
        }
        echo '</ul>';
        
        echo '<style>
            .st-red { background: #FDE7E9; color: #A80000; border: 1px solid #ffb3b3; }
            .st-green { background: #DFF6DD; color: #107C10; border: 1px solid #ccebc5; }
            .st-pink { background: #FFEBF5; color: #C00065; border: 1px solid #ffb3d9; }
        </style>';
        
        echo '<div style="padding:15px; text-align:right; font-size:12px; color:#888;">Total: '.$res->num_rows.' demandas</div>';
    } else {
        echo '<div style="padding:50px; text-align:center; color:#888;"><h4>Tudo limpo!</h4><p>Nenhuma tarefa pendente encontrada.</p></div>';
    }
    exit;
}

// =================================================================================
// 2. ATUALIZAR
// =================================================================================
if ($action == 'update') {
    $task_id = intval($_POST['task_id']);
    $user_id = intval($_POST['user_id']);
    $new_date_fim = isset($_POST['new_date']) ? $_POST['new_date'] : '';
    
    if ($new_date_fim) {
        $conn->query("UPDATE tarefas SET tarefa_fim = '$new_date_fim' WHERE tarefa_id = $task_id");
        recalcularCascataCompleta($conn, $user_id);
    }
    echo json_encode(array('success' => true));
    exit;
}

// =================================================================================
// 3. REORDENAR
// =================================================================================
if ($action == 'reorder') {
    $user_id = intval($_POST['user_id']);
    $order_list = isset($_POST['order']) ? $_POST['order'] : [];

    if (!empty($order_list)) {
        foreach ($order_list as $index => $tarefa_id) {
            $tid = intval($tarefa_id);
            $conn->query("UPDATE tarefas SET tarefa_ordem = ".intval($index)." WHERE tarefa_id = $tid");
        }
        recalcularCascataCompleta($conn, $user_id);
    }
    echo json_encode(array('success' => true));
    exit;
}

// =================================================================================
// FUNÇÃO DE RECÁLCULO (CORRIGIDA)
// =================================================================================
function recalcularCascataCompleta($conn, $user_id) {
    global $sql_order_by;

    $sql = "
        SELECT 
            t.tarefa_id, t.tarefa_inicio, t.tarefa_fim, t.tarefa_status, t.tarefa_percentagem,
            (SELECT sisvalor_valor FROM sisvalores WHERE sisvalor_titulo = 'StatusTarefa' AND sisvalor_valor_id = t.tarefa_status LIMIT 1) as nome_status
        FROM tarefas t
        INNER JOIN tarefa_designados td ON t.tarefa_id = td.tarefa_id
        WHERE td.usuario_id = $user_id 
        AND (t.tarefa_percentagem < 100 OR t.tarefa_percentagem IS NULL)
        $sql_order_by
    ";
    $res = $conn->query($sql);
    
    $referencia_data = null;

    if ($res) {
        while($row = $res->fetch_assoc()) {
            $tid = $row['tarefa_id'];
            
            // Verifica se a tarefa é "movível" (A Iniciar ou Em Andamento)
            $nome_status = isset($row['nome_status']) ? mb_strtolower($row['nome_status'], 'UTF-8') : '';
            $eh_movivel = false;

            if (strpos($nome_status, 'iniciar') !== false || 
                strpos($nome_status, 'andamento') !== false || 
                strpos($nome_status, 'execu') !== false) {
                $eh_movivel = true;
            }
            // Fallback: se status vazio mas 0% ou >0%, assume movível
            if (empty($nome_status) && $row['tarefa_percentagem'] < 100) {
                $eh_movivel = true;
            }

            // --- CORREÇÃO DO LOOP ---
            
            // 1. Se a tarefa NÃO é movível (Paralisada, Pendência), IGNORAR COMPLETAMENTE.
            // Não atualizamos datas, e não definimos ela como referência para a próxima.
            if (!$eh_movivel) {
                continue;
            }

            // 2. Se for a primeira tarefa MOVÍVEL encontrada na lista
            if ($referencia_data === null) {
                if ($row['tarefa_fim']) {
                    $referencia_data = new DateTime($row['tarefa_fim']);
                } else {
                    $referencia_data = new DateTime(); 
                }
                // Como é a "âncora" da fila ativa, não recalculamos o início dela,
                // apenas usamos o fim dela como base para a próxima.
                continue; 
            }

            // 3. Se chegou aqui, é uma tarefa movível subsequente que deve ser ajustada
            
            $dias_duracao = 1;
            if ($row['tarefa_inicio'] && $row['tarefa_fim']) {
                $dias_duracao = getDiasUteis($row['tarefa_inicio'], $row['tarefa_fim']);
            }
            if ($dias_duracao < 1) $dias_duracao = 1;

            // Recalcula datas baseado na tarefa anterior VÁLIDA
            $novo_inicio_obj = proximoDiaUtil($referencia_data->format('Y-m-d'));
            $novo_fim_obj = adicionarDiasUteis($novo_inicio_obj->format('Y-m-d'), $dias_duracao - 1);

            $str_inicio = $novo_inicio_obj->format('Y-m-d H:i:s');
            $str_fim    = $novo_fim_obj->format('Y-m-d H:i:s');

            $conn->query("UPDATE tarefas SET tarefa_inicio = '$str_inicio', tarefa_fim = '$str_fim' WHERE tarefa_id = $tid");
            
            // Atualiza referência para a próxima da fila
            $referencia_data = $novo_fim_obj;
        }
    }
}
?>