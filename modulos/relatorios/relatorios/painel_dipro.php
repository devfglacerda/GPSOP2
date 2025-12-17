<?php
if (!defined('BASE_DIR')) {
    define('BASE_DIR', 'D:/xampp/htdocs/GPSOP_2023');
}
require_once BASE_DIR . '/config.php';
global $Aplic;
if (!isset($Aplic) || !method_exists($Aplic, 'carregarCalendarioJS')) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erro: Objeto $Aplic ou método carregarCalendarioJS não definido.']);
    exit;
}
$Aplic->carregarCalendarioJS();

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$fetch_url = 'dashboard/fetch_data.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Demandas - DIPRO</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .header h2 { margin: 0; color: #333; }
        .filter-box { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 15px; }
        .filter-box label { font-weight: bold; margin-right: 5px; }
        .filter-box select, .filter-box input[type="text"], .filter-box input[type="checkbox"] { padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .filter-box select { min-width: 200px; }
        .action-btn { background: #4e79a7; color: #fff; border: none; border-radius: 4px; padding: 8px 16px; cursor: pointer; font-weight: bold; }
        .action-btn:hover { background: #3a5f8b; }
        .export-btn { background: #28a745; margin-left: 10px; }
        .export-btn:hover { background: #218838; }
        .dashboard-container { display: flex; flex-wrap: wrap; gap: 20px; }
        .report-card, .chart-card, .summary-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 20px; flex: 1; min-width: 300px; }
        .chart-card { max-width: 600px; height: 400px; }
        .summary-card { max-width: 400px; }
        #taskTableTemp, #summaryTable { width: 100%; font-size: 14px; }
        .no-data { text-align: center; color: #666; font-style: italic; }
        .pagination { margin-top: 10px; }
        .pagination button { padding: 5px 10px; margin-right: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Painel de Demandas - DIPRO</h2>
            <div>
                <button class="action-btn" id="applyFiltersBtn">Aplicar Filtros</button>
                <button class="action-btn export-btn" id="exportCSVBtn">Exportar CSV</button>
                <button class="action-btn export-btn" id="exportPDFBtn">Exportar PDF</button>
            </div>
        </div>
        <div class="filter-box">
            <div><label>Projeto:</label><select id="projeto_id" name="projeto_id"><option value="0">Todos os Projetos</option></select></div>
            <div><label>De:</label><input type="text" id="data_inicio" value="01/01/2025"><input type="hidden" id="reg_data_inicio" name="reg_data_inicio" value="20250101"></div>
            <div><label>Até:</label><input type="text" id="data_fim" value="31/12/2025"><input type="hidden" id="reg_data_fim" name="reg_data_fim" value="20251231"></div>
            <div><label>Usuário:</label><input type="hidden" id="usuario_id" name="usuario_id" value="0"><input type="text" id="nome_usuario" value="Usuário Desconhecido" readonly><a href="javascript:popUsuario();"><img src="icones/usuarios.gif" alt="Selecionar Usuário"></a></div>
            <div><label>Status:</label><select id="status_id" name="status_id"><option value="0">Todos os Status</option></select></div>
            <div><label>Prioridade:</label><select id="prioridade_id" name="prioridade_id"><option value="0">Todas as Prioridades</option></select></div>
            <div><input type="checkbox" id="usar_periodo" name="usar_periodo"><label for="usar_periodo">Usar o período</label></div>
        </div>
        <div class="dashboard-container">
            <div class="report-card"><table id="taskTableTemp" class="display"><thead><tr><th>ID</th><th>Nome</th><th>Início</th><th>Fim</th><th>Status</th><th>Prioridade</th><th>%</th></tr></thead><tbody></tbody></table><div class="pagination" id="pagination"></div></div>
            <div class="chart-card"><canvas id="demandChart"></canvas></div>
            <div class="chart-card"><canvas id="statusChart"></canvas></div>
            <div class="summary-card"><table id="summaryTable" class="display"><thead><tr><th>Usuário</th><th>Total</th></tr></thead><tbody></tbody></table></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="dashboard/dashboard.js"></script>
    <script>
        let currentPage = 1;
        const perPage = 50;

        function loadData(page = 1) {
            const data = {
                projeto_id: $('#projeto_id').val(),
                data_inicio: $('#reg_data_inicio').val(),
                data_fim: $('#reg_data_fim').val(),
                usuario_id: $('#usuario_id').val(),
                status_id: $('#status_id').val(),
                prioridade_id: $('#prioridade_id').val(),
                usar_periodo: $('#usar_periodo').is(':checked'),
                page: page,
                per_page: perPage
            };
            $.post('<?php echo $fetch_url; ?>', data, function(response) {
                console.log('Resposta recebida:', response); // Depuração
                if (response.error) {
                    alert('Erro: ' + response.error);
                    return;
                }
                // Remove DataTables temporariamente
                $('#taskTableTemp').off().empty().removeAttr('id').attr('id', 'taskTableTemp');
                $('#taskTableTemp tbody').empty();
                if (response.tasks.length === 0) {
                    $('#taskTableTemp tbody').append('<tr><td colspan="7" class="no-data">Nenhum dado encontrado</td></tr>');
                } else {
                    response.tasks.forEach(task => {
                        $('#taskTableTemp tbody').append(
                            `<tr><td>${task.tarefa_id}</td><td>${task.tarefa_nome || 'Sem nome'}</td><td>${task.tarefa_inicio}</td><td>${task.tarefa_fim}</td><td>${task.tarefa_status}</td><td>${task.tarefa_prioridade}</td><td>${task.tarefa_percentagem}</td></tr>`
                        );
                    });
                }
                $('#summaryTable tbody').empty();
                response.summary.forEach(sum => {
                    $('#summaryTable tbody').append(
                        `<tr><td>${sum.tarefa_dono}</td><td>${sum.total}</td></tr>`
                    );
                });
                updatePagination(response.total, response.page, response.per_page);
            }, 'json').fail(function(xhr, status, error) {
                console.error('Erro na requisição:', error, xhr.responseText); // Depuração
            });
        }

        function updatePagination(total, current, perPage) {
            const totalPages = Math.ceil(total / perPage);
            $('#pagination').empty();
            if (current > 1) {
                $('#pagination').append('<button onclick="loadData(' + (current - 1) + ')">Anterior</button>');
            }
            for (let i = 1; i <= totalPages; i++) {
                $('#pagination').append('<button onclick="loadData(' + i + ')">' + i + '</button>');
            }
            if (current < totalPages) {
                $('#pagination').append('<button onclick="loadData(' + (current + 1) + ')">Próximo</button>');
            }
            $('#pagination button').removeClass('active').filter('[onclick*="loadData(' + current + ')"]').addClass('active');
        }

        $('#applyFiltersBtn').click(() => loadData(1));
        $(document).ready(() => {
            console.log('Documento pronto, chamando loadData'); // Depuração
            loadData(1);
        });
    </script>
</body>
</html>