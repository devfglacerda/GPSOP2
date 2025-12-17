<?php
// dashboard.php
session_start();

// Verifica se está logado
if (!isset($_SESSION['dashboard_usuario_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Pega a permissão da sessão (Define se pode ver o botão ou não)
$pode_editar = isset($_SESSION['dashboard_pode_editar']) && $_SESSION['dashboard_pode_editar'] === true;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Demandas - GPSOP</title>
    <link rel="shortcut icon" href="/estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/x-icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <script>
        const USER_CAN_EDIT = <?php echo $pode_editar ? 'true' : 'false'; ?>;
    </script>

    <style>
        /* --- Base --- */
        :root { --pbi-yellow: #F2C811; --pbi-dark: #252423; --pbi-gray-bg: #EAEAEA; --pbi-text: #333333; --pbi-blue: #0078D4; }
        body { font-family: 'Segoe UI', sans-serif; background-color: var(--pbi-gray-bg); color: var(--pbi-text); margin: 0; padding: 0; height: 100vh; display: flex; flex-direction: column; overflow: hidden; }
        
        /* --- Top Bar --- */
        .top-bar { background-color: var(--pbi-dark); color: white; padding: 0 20px; height: 48px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 20; flex-shrink: 0; }
        .user-area { display: flex; align-items: center; gap: 15px; font-size: 13px; }
        .btn-logout { color: #F2C811; text-decoration: none; font-weight: bold; border: 1px solid #F2C811; padding: 4px 10px; border-radius: 4px; transition: all 0.2s; }
        .btn-logout:hover { background-color: #F2C811; color: #252423; }

        /* --- Layout --- */
        .main-container { display: flex; flex: 1; overflow: hidden; position: relative; }
        
        /* --- Sidebar --- */
        .sidebar { width: 280px; background-color: #F0F0F0; border-right: 1px solid #CCC; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; z-index: 10; flex-shrink: 0; }
        .filter-group label { font-size: 12px; font-weight: 600; display: block; margin-bottom: 4px; }
        .filter-group select, .filter-group input[type="text"] { width: 100%; padding: 6px; border: 1px solid #999; border-radius: 2px; box-sizing: border-box; }
        .btn-update { background-color: var(--pbi-dark); color: var(--pbi-yellow); border: none; padding: 10px; font-weight: 600; cursor: pointer; margin-top: 10px; text-transform: uppercase; font-size: 12px; }
        .btn-update:hover { background-color: black; }
        
        /* --- Dropdown Customizado --- */
        .custom-dropdown { position: relative; width: 100%; }
        .dropdown-trigger { width: 100%; padding: 6px 8px; border: 1px solid #999; background: white; font-size: 13px; border-radius: 2px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; box-sizing: border-box; }
        .dropdown-trigger:hover { border-color: var(--pbi-dark); }
        .dropdown-content { display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #999; border-top: none; max-height: 250px; overflow-y: auto; z-index: 1000; box-shadow: 0 4px 6px rgba(0,0,0,0.15); padding: 5px; overscroll-behavior: contain; }
        .dropdown-content.show { display: block; }
        .dropdown-actions { border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 5px; display: flex; gap: 10px; justify-content: center; }
        .link-action { font-size: 11px; color: var(--pbi-blue); cursor: pointer; text-decoration: underline; border:none; background:none; }
        .checkbox-item { display: flex; align-items: center; padding: 4px; cursor: pointer; }
        .checkbox-item:hover { background-color: #f0f0f0; }
        .checkbox-item input { margin-right: 8px; cursor: pointer; width: auto !important; }
        .checkbox-item label { margin: 0; cursor: pointer; font-weight: normal; font-size: 13px; width: 100%; user-select: none; }

        /* --- Canvas --- */
        .canvas { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .kpi-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; flex-shrink: 0; }
        .kpi-card { background: white; padding: 15px; border-left: 5px solid; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .kpi-title { font-size: 11px; color: #666; text-transform: uppercase; font-weight: 700; }
        .kpi-value { font-size: 28px; font-weight: bold; color: var(--pbi-dark); margin-top: 5px; }
        
        .charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; height: 340px; flex-shrink: 0; }
        .pbi-visual { background: white; padding: 15px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #ddd; display: flex; flex-direction: column; overflow: hidden; height: auto; }
        .visual-header { font-size: 14px; font-weight: 700; color: #333; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; flex-shrink: 0; }
        .chart-container { position: relative; flex: 1; min-height: 250px; } 

        table.dataTable { width: 100% !important; margin-bottom: 0 !important; }
        table.dataTable thead th { background-color: white !important; border-bottom: 2px solid #333 !important; font-size: 12px; text-transform: uppercase; }
        .table-container { flex: 0 0 auto; height: auto !important; min-height: 0 !important; margin-bottom: 20px; display: block; }
        .dataTables_scrollBody { overflow-y: auto !important; }

        td.dt-control { text-align: center; cursor: pointer; background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center; }
        tr.shown td.dt-control { background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center; }
        .child-table { width: 100%; margin: 5px 0; background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 4px; }
        .child-table th { background-color: #e9ecef !important; color: #495057 !important; font-size: 11px !important; padding: 8px !important; }
        .child-table td { background-color: #fff !important; font-size: 12px !important; padding: 8px !important; border-bottom: 1px solid #eee; }
        .status-dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; }
        .dataTables_wrapper .dataTables_paginate { padding-top: 10px; }
        .edit-select { width: 100%; padding: 4px; border: 1px solid #ccc; border-radius: 4px; font-size: 11px; background-color: white; cursor: pointer; }
        .edit-select:focus { border-color: var(--pbi-blue); outline: none; }

        /* --- Tooltip Global --- */
        #global-tooltip { display: none; position: fixed; z-index: 999999; width: 300px; background-color: #fff; border: 1px solid #E86C00; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border-radius: 4px; pointer-events: none; font-size: 12px; color: #333; }
        .tooltip-header { background-color: #E86C00; color: white; padding: 6px 10px; font-weight: bold; border-radius: 3px 3px 0 0; }
        .tooltip-body { padding: 10px; line-height: 1.4; white-space: pre-wrap; }
        .tooltip-trigger { cursor: help; border-bottom: 1px dotted #999; color: inherit; text-decoration: none; }
        .tooltip-trigger:hover { color: #E86C00; }

        @media (max-width: 768px) {
            body { height: auto; overflow-y: auto; }
            .main-container { flex-direction: column; overflow: visible; }
            .top-bar { flex-direction: column; height: auto; padding: 10px; text-align: center; gap: 10px; }
            .user-area { width: 100%; justify-content: center; }
            .sidebar { width: 100%; height: auto; border-right: none; border-bottom: 2px solid #ccc; padding: 15px; box-sizing: border-box; gap: 10px; }
            .filter-group { display: inline-block; width: 48%; margin-right: 1%; margin-bottom: 10px; vertical-align: top; }
            .btn-update { width: 100%; }
            .canvas { padding: 10px; overflow: visible; }
            .kpi-row { grid-template-columns: 1fr 1fr; gap: 10px; }
            .kpi-value { font-size: 20px; }
            .charts-row { grid-template-columns: 1fr; height: auto; display: flex; flex-direction: column; }
            .pbi-visual { margin-bottom: 20px; height: auto; }
            .chart-container { min-height: 250px; }
            .table-container { height: auto !important; min-height: 0; margin-bottom: 30px; overflow-x: auto; }
            table.dataTable { min-width: 600px; } 
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <div><h2>Painel de Demandas - GPSOP</h2></div>
        <div class="user-area">
            <div>Olá, <b><?php echo $_SESSION['dashboard_usuario_nome']; ?></b></div>
            <div style="font-size: 11px; opacity: 0.8;">| <?php echo date('d/m/Y'); ?></div>
            <a href="?logout=1" class="btn-logout">SAIR</a>
        </div>
    </div>

    <div class="main-container">
        <aside class="sidebar">
            <div style="font-weight:700; border-bottom:2px solid #F2C811; padding-bottom:5px; margin-bottom: 10px;">FILTROS</div>
            
            <div class="filter-group">
                <label>Solicitante (Setor)</label>
                <select id="usuario_id"><option value="0">Carregando...</option></select>
            </div>

            <div class="filter-group">
                <label>Tipo da Tarefa</label>
                <select id="tipo_id"><option value="0">Todos</option></select>
            </div>

            <div class="filter-group">
                <label>Prioridade</label>
                <select id="prioridade_id"><option value="all">Todas</option></select>
            </div>
            
            <div class="filter-group">
                <label>De:</label>
                <input type="text" id="data_inicio" value="01/01/2025">
                <input type="hidden" id="reg_data_inicio" value="2025-01-01">
            </div>
            
            <div class="filter-group">
                <label>Até:</label>
                <input type="text" id="data_fim" value="31/12/2025">
                <input type="hidden" id="reg_data_fim" value="2025-12-31">
            </div>
            
            <div class="filter-group">
                <label>Status (Selecione)</label>
                <div class="custom-dropdown" id="statusDropdown">
                    <div class="dropdown-trigger" id="statusTrigger">
                        <span id="statusText">Todos</span><span class="arrow">▼</span>
                    </div>
                    <div class="dropdown-content" id="statusContent">
                        <div class="dropdown-actions">
                            <button class="link-action" id="btnMarkAll">Marcar Todos</button>
                            <button class="link-action" id="btnUnmarkAll">Limpar</button>
                        </div>
                        <div id="statusListContainer"><div style="font-size:12px; color:#666; padding:5px;">Carregando...</div></div>
                    </div>
                </div>
            </div>
            
            <div class="filter-group">
                <input type="checkbox" id="usar_periodo" checked> <span style="font-size:12px">Filtrar período</span>
            </div>
            <button id="applyFiltersBtn" class="btn-update">Aplicar Filtros</button>
            
            <?php if ($pode_editar): ?>
            <div style="margin-top: 15px; text-align: center; border-top: 1px solid #ccc; padding-top: 15px;">
                <a href="planner.php" target="_blank" style="text-decoration: none; color: #333; font-size: 13px; display: flex; align-items: center; justify-content: center; gap: 8px; background: #fff; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <span style="font-size: 16px;">📅</span> 
                    <strong>Planejador Inteligente</strong>
                </a>
            </div>
            <?php endif; ?>

        </aside>

        <main class="canvas">
            
            <div class="kpi-row">
                <div class="kpi-card" style="border-color: #0078D4;"><div class="kpi-title">TOTAL</div><div class="kpi-value" id="kpi-total">0</div></div>
                <div class="kpi-card" style="border-color: #F2C811;"><div class="kpi-title">EM ANDAMENTO</div><div class="kpi-value" id="kpi-andamento">0</div></div>
                <div class="kpi-card" style="border-color: #107C10;"><div class="kpi-title">A INICIAR</div><div class="kpi-value" id="kpi-iniciar">0</div></div>
                <div class="kpi-card" style="border-color: #D83B01;"><div class="kpi-title">PARALISADA</div><div class="kpi-value" id="kpi-paralisada">0</div></div>
                <div class="kpi-card" style="border-color: #666;"><div class="kpi-title">CONCLUÍDAS</div><div class="kpi-value" id="kpi-concluidas">0</div></div>
            </div>

            <div class="charts-row">
                <div class="pbi-visual">
                    <div class="visual-header">Status das Demandas</div>
                    <div class="chart-container"><canvas id="statusChart"></canvas></div>
                </div>
                <div class="pbi-visual">
                    <div class="visual-header">Demandas por Solicitante (Top 10)</div>
                    <div class="chart-container"><canvas id="solicitanteChart"></canvas></div>
                </div>
            </div>

            <div class="charts-row">
                <div class="pbi-visual">
                    <div class="visual-header">Resumo por Categoria (Tipo)</div>
                    <div class="chart-container"><canvas id="categoriaChart"></canvas></div>
                </div>
                <div class="pbi-visual">
                    <div class="visual-header">Prioridade das Demandas</div>
                    <div class="chart-container"><canvas id="prioridadeChart"></canvas></div>
                </div>
            </div>

            <div class="pbi-visual table-container">
                <div class="visual-header">Detalhamento das Tarefas</div>
                <table id="taskTable" class="display" style="width:100%">
                    <thead><tr><th style="width: 30px;"></th><th>Projeto</th><th>Qtd. Demandas</th><th>Solicitante</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="pbi-visual table-container" style="height: auto !important; min-height: 200px !important;">
                <div class="visual-header">Resumo por Solicitante</div>
                <table id="summaryTable" class="display" style="width:100%">
                    <thead><tr><th>Solicitante</th><th>Quantidade</th><th>% do Total</th></tr></thead>
                    <tbody></tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="global-tooltip">
        <div class="tooltip-header">Descrição</div>
        <div class="tooltip-body" id="global-tooltip-body"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="dashboard.js"></script>
</body>
</html>