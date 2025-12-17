<?php
// planner.php
require_once 'conexao.php'; 
session_start();

header('Content-Type: text/html; charset=utf-8');
$conn->set_charset("utf8");
ini_set('display_errors', 0); 

if (!isset($_SESSION['dashboard_usuario_id'])) { header("Location: login.php"); exit; }

$sql_depts = "SELECT dept_id, dept_nome FROM depts WHERE dept_id IN (7, 26, 27, 28, 29, 30) ORDER BY dept_nome ASC";
$res_depts = $conn->query($sql_depts);
$lista_depts = [];
if ($res_depts) { while($row = $res_depts->fetch_assoc()) { $lista_depts[] = $row; } }

$sql_users = "
    SELECT u.usuario_id, u.usuario_login, c.contato_nomecompleto, c.contato_nomeguerra, c.contato_dept
    FROM usuarios u
    LEFT JOIN contatos c ON u.usuario_contato = c.contato_id
    WHERE u.usuario_id > 0 AND u.usuario_ativo = 1 
    AND c.contato_dept IN (7, 26, 27, 28, 29, 30)
    ORDER BY c.contato_nomecompleto ASC
";
$res_users = $conn->query($sql_users);
$lista_usuarios = [];
if ($res_users) {
    while($u = $res_users->fetch_assoc()) {
        $nome = $u['contato_nomecompleto'];
        if(empty($nome)) $nome = $u['contato_nomeguerra'];
        if(empty($nome)) $nome = $u['usuario_login'];
        $lista_usuarios[] = ['id' => $u['usuario_id'], 'nome' => $nome, 'dept_id' => $u['contato_dept']];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planejador Inteligente - GPSOP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <style>
        :root { --pbi-yellow: #F2C811; --pbi-dark: #252423; --pbi-gray-bg: #EAEAEA; --pbi-blue: #0078D4; }
        body { font-family: 'Segoe UI', sans-serif; background-color: var(--pbi-gray-bg); margin: 0; padding: 0; height: 100vh; display: flex; flex-direction: column; overflow: hidden; }
        
        .top-bar { background-color: var(--pbi-dark); color: white; padding: 0 20px; height: 48px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 20; flex-shrink: 0; }
        .logo-area { display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 18px; }
        .user-area { font-size: 13px; opacity: 0.9; }

        .main-container { display: flex; flex: 1; overflow: hidden; }
        
        .sidebar { width: 280px; background-color: #F8F9FA; border-right: 1px solid #CCC; padding: 20px; display: flex; flex-direction: column; gap: 20px; z-index: 10; box-shadow: 2px 0 5px rgba(0,0,0,0.05); }
        .sidebar h3 { margin: 0 0 10px 0; font-size: 14px; text-transform: uppercase; color: #666; border-bottom: 2px solid var(--pbi-yellow); padding-bottom: 5px; }
        .filter-group label { font-size: 12px; font-weight: 700; color: #333; display: block; margin-bottom: 5px; }
        .filter-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; background: white; font-size: 13px; }
        .btn-load { width: 100%; padding: 10px; background-color: var(--pbi-blue); color: white; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; transition: 0.2s; text-transform: uppercase; font-size: 12px; margin-top: 10px; }
        .btn-load:hover { background-color: #005a9e; }

        .canvas { flex: 1; padding: 30px; overflow-y: auto; background-color: #EAEAEA; }
        .paper-card { background: white; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; min-height: 200px; }
        .card-header { padding: 15px 20px; border-bottom: 1px solid #eee; background: white; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 16px; font-weight: 700; color: var(--pbi-dark); display: flex; align-items: center; gap: 8px; }
        
        .task-list-container { padding: 0; }
        
        /* --- GRID AJUSTADO --- */
        .grid-template {
            display: grid; 
            /* Aumentei a coluna de Data Final para 150px e mantive Duração em 80px */
            grid-template-columns: 30px 1fr 90px 110px 100px 150px 80px; 
            align-items: center;
            gap: 15px; /* ESPAÇAMENTO ENTRE COLUNAS ADICIONADO */
        }

        .task-header-row { 
            padding: 10px 20px; background: #F3F2F1; font-size: 11px; font-weight: 700; color: #666; border-bottom: 1px solid #e1e1e1; text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        .task-list { list-style: none; padding: 0; margin: 0; }
        
        .task-item { 
            padding: 12px 20px; border-bottom: 1px solid #f0f0f0; transition: background 0.1s; background: white; 
        }
        .task-item:hover { background-color: #f8f9fa; }
        .task-item:last-child { border-bottom: none; }
        
        .ui-sortable-helper { box-shadow: 0 5px 15px rgba(0,0,0,0.2); background: #fff !important; transform: scale(1.01); border: 1px solid #ccc; }
        .ui-sortable-placeholder { background: #f0f0f0; border: 2px dashed #ccc; height: 60px; visibility: visible !important; }

        .drag-handle { cursor: grab; color: #999; font-size: 16px; display: flex; justify-content: center; }
        .drag-handle:hover { color: #333; }

        .task-info strong { display: block; color: #333; font-size: 14px; margin-bottom: 2px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; }
        .task-info small { color: #888; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; text-align: center; width: 100%; }
        .st-blue { background: #E6F2FF; color: #0078D4; border: 1px solid #cce4ff; }
        .st-yellow { background: #FFF4CE; color: #795e00; border: 1px solid #ffeebb; }

        .prio-badge { font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 4px; }
        .prio-high { color: #D83B01; }
        .prio-normal { color: #107C10; }
        .prio-low { color: #605E5C; }

        .date-input { 
            padding: 6px 10px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-family: 'Segoe UI', sans-serif; 
            color: #333; 
            cursor: pointer; 
            transition: 0.2s; 
            width: 100%; /* Ocupa 100% da coluna de 150px */
            box-sizing: border-box; /* Garante que padding não estoure a largura */
            text-align:center; 
        }
        .date-input:focus { border-color: var(--pbi-blue); box-shadow: 0 0 0 2px rgba(0,120,212,0.2); outline: none; }
        
        .date-static { font-size: 13px; color: #666; text-align: center; }
        
        .duration-text { 
            font-size: 13px; 
            font-weight: 600; 
            color: #333; 
            text-align: center; 
            background: #f0f0f0; 
            padding: 6px; 
            border-radius: 4px; 
            width: 100%; /* Ocupa a coluna */
            box-sizing: border-box;
        }

        #loader { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 999; justify-content: center; align-items: center; flex-direction: column; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid var(--pbi-yellow); border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-bottom: 15px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div id="loader">
    <div class="spinner"></div>
    <div id="loader-msg" style="font-weight:600; color:#333;">Processando...</div>
</div>

<div class="top-bar">
    <div class="logo-area"><span style="color:var(--pbi-yellow)">📅</span> Planejador Inteligente</div>
    <div class="user-area">Arraste e solte para reordenar</div>
</div>

<div class="main-container">
    <aside class="sidebar">
        <h3>Filtros de Seleção</h3>
        <div class="filter-group">
            <label>1. Gerência</label>
            <select id="deptSelect">
                <option value="">-- Todos os Setores --</option>
                <?php foreach($lista_depts as $d): ?>
                    <option value="<?php echo $d['dept_id']; ?>"><?php echo $d['dept_nome']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>2. Profissional (Ativos)</label>
            <select id="userSelect" disabled><option value="">-- Selecione o Setor Primeiro --</option></select>
        </div>
        <div style="margin-top:auto; font-size:11px; color:#888; line-height:1.4;">
            <strong>Dica:</strong><br>Arraste uma tarefa para mudar sua posição. As datas serão recalculadas automaticamente em cascata a partir da nova posição.
        </div>
        <button onclick="loadTasks()" class="btn-load">Carregar Cronograma</button>
    </aside>

    <main class="canvas">
        <div class="paper-card">
            <div class="card-header">
                <div class="card-title">Cronograma de Demandas</div>
                <div style="font-size:12px; color:#666;" id="info-header">Selecione um profissional</div>
            </div>
            <div id="tasksContainer" class="task-list-container">
                <div style="padding: 50px; text-align: center; color: #999;">
                    <img src="https://cdn-icons-png.flaticon.com/512/747/747310.png" width="64" style="opacity:0.3; margin-bottom:15px;">
                    <br>Nenhum dado carregado.
                </div>
            </div>
        </div>
    </main>
</div>

<script>
const allUsers = <?php echo json_encode($lista_usuarios); ?>;

function filterUsers() {
    let selectedDept = $('#deptSelect').val();
    let $userSel = $('#userSelect');
    $userSel.empty().append('<option value="">-- Selecione o Profissional --</option>');
    let count = 0;
    allUsers.forEach(u => {
        if (selectedDept === "" || u.dept_id == selectedDept) {
            $userSel.append(`<option value="${u.id}">${u.nome}</option>`);
            count++;
        }
    });
    if(count === 0) { $userSel.append('<option value="" disabled>Nenhum profissional ativo</option>'); $userSel.prop('disabled', true); } else { $userSel.prop('disabled', false); }
}

$('#deptSelect').on('change', filterUsers);
filterUsers();

function loadTasks() {
    let uid = $('#userSelect').val();
    let userName = $('#userSelect option:selected').text();
    if(!uid) { alert('Por favor, selecione um profissional.'); return; }
    $('#info-header').text('Visualizando: ' + userName);
    $('#tasksContainer').html('<div style="padding:40px; text-align:center; color:#666;">Carregando dados...</div>');
    
    $.post('update_cascade.php', { action: 'list', user_id: uid }, function(data) { 
        $('#tasksContainer').html(data); 
        initSortable(); 
    });
}

function initSortable() {
    $("#sortableList").sortable({
        handle: ".drag-handle",
        placeholder: "ui-sortable-placeholder",
        axis: "y",
        update: function(event, ui) {
            let taskOrder = $(this).sortable('toArray', { attribute: 'data-id' });
            let userId = $('#userSelect').val();
            
            $('#loader-msg').text('Reordenando e recalculando datas...');
            $('#loader').css('display', 'flex');
            
            $.post('update_cascade.php', { action: 'reorder', order: taskOrder, user_id: userId }, function(response) {
                $('#loader').hide();
                try {
                    let res = JSON.parse(response);
                    if(res.success) { loadTasks(); } else { alert('Erro ao reordenar: ' + res.error); }
                } catch(e) { console.log(response); }
            });
        }
    });
}

$(document).on('change', '.date-input', function() {
    let $input = $(this);
    let taskId = $input.data('id');
    let newDate = $input.val();
    let oldDate = $input.data('old');
    let userId = $('#userSelect').val();
    
    if(!newDate) return; 
    if(!confirm(`⚠️ Alterar a data final recauculará toda a sequência abaixo. Continuar?`)) {
        $input.val(oldDate); return;
    }
    $('#loader-msg').text('Processando alterações...');
    $('#loader').css('display', 'flex');
    $.post('update_cascade.php', { action: 'update', task_id: taskId, new_date: newDate, old_date: oldDate, user_id: userId }, function(response) {
        $('#loader').hide();
        loadTasks();
    });
});
</script>
</body>
</html>