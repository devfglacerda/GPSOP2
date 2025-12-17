<?php
if (!defined('BASE_DIR')) {
    die('Acesso não autorizado.');
}

// Configurações globais e inicialização
global $Aplic, $cal_sdf, $ver_todos_projetos;
$Aplic->carregarCalendarioJS();

// Parâmetros da requisição
$projeto_id = getParam($_REQUEST, 'projeto_id', 0);
$mostrarNomeProjeto = nome_projeto($projeto_id);
$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', 0);
$reg_data_inicio = getParam($_REQUEST, 'reg_data_inicio', 0);
$reg_data_fim = getParam($_REQUEST, 'reg_data_fim', 0);
$usuario_id = getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$log_pdf = getParam($_REQUEST, 'log_pdf', 0);
$usar_periodo = getParam($_REQUEST, 'usar_periodo', 0);

$data_inicio = intval($reg_data_inicio) ? new CData($reg_data_inicio) : new CData(date('Y') . '-01-01');
$data_fim = intval($reg_data_fim) ? new CData($reg_data_fim) : new CData(date('Y') . '-12-31');
if (!$reg_data_inicio) $data_inicio->subtrairIntervalo(new Data_Intervalo('14,0,0,0'));
$data_fim->setTime(23, 59, 59);

echo '<input type="hidden" name="fazer_relatorio" value="0" />';

$data = new CData();
$titulo = 'Demandas - DIPRO';

if (!$dialogo) {
    echo '<div class="container">';
    echo '<div class="header">';
       echo '<h2>' . htmlspecialchars($titulo) . '</h2>';
    echo '<a href="javascript: void(0);" onclick="env.target=\'popup\'; env.dialogo.value=1; env.pdf.value=0; env.sem_cabecalho.value=0; env.submit();" class="print-btn">';
    echo ' Imprimir';
    echo '</a>';
    echo '</div>';
    echo '<div class="filter-box">';
    echo '<table class="filtro-tabela">';
    echo '<tr>';
    echo '<td>';
    echo '<label>De:</label>';
    echo '<input type="text" name="data_inicio" id="data_inicio" value="' . ($data_inicio ? $data_inicio->format('%d/%m/%Y') : '') . '" class="texto" />';
    echo '<input type="hidden" name="reg_data_inicio" id="reg_data_inicio" value="' . ($data_inicio ? $data_inicio->format('%Y%m%d') : '') . '" />';
    echo '<a href="javascript: void(0);"><img id="f_btn1" src="' . acharImagem('calendario.gif') . '" style="vertical-align:middle" width="18" height="12" alt="Calendário" border="0" /></a>';
    echo '</td>';
    echo '<td>';
    echo '<label>Até:</label>';
    echo '<input type="text" name="data_fim" id="data_fim" value="' . ($data_fim ? $data_fim->format('%d/%m/%Y') : '') . '" class="texto" />';
    echo '<input type="hidden" name="reg_data_fim" id="reg_data_fim" value="' . ($data_fim ? $data_fim->format('%Y%m%d') : '') . '" />';
    echo '<a href="javascript: void(0);"><img id="f_btn2" src="' . acharImagem('calendario.gif') . '" style="vertical-align:middle" width="18" height="12" alt="Calendário" border="0" /></a>';
    echo '</td>';
    echo '<td>';
    echo '<label>Usuário:</label>';
    echo '<input type="hidden" id="usuario_id" name="usuario_id" value="0" />';
    echo '<input type="text" id="nome_usuario" name="nome_usuario" value="' . nome_om($usuario_id, $Aplic->getPref('om_usuario')) . '" class="texto" readonly />';
    echo '<a href="javascript: void(0);" onclick="popUsuario();">' . imagem('icones/usuarios.gif', 'Selecionar Usuário') . '</a>';
    echo '</td>';
    echo '<td>';
    echo '<input type="checkbox" name="usar_periodo" id="usar_periodo" ' . ($usar_periodo ? 'checked="checked"' : '') . ' />';
    echo '<label for="usar_periodo">Usar o período</label>';
    echo '</td>';
    echo '<td>';
    echo '<button class="action-btn" onclick="env.fazer_relatorio.value=1; env.target=\'\'; env.dialogo.value=0; env.sem_cabecalho.value=0; env.pdf.value=0; env.submit();">Exibir</button>';
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
} else {
    echo '<h2>' . htmlspecialchars($titulo) . '</h2>';
}

if ($fazer_relatorio || $dialogo) {
    $sql = new BDConsulta;

    $lista_projetos = false;
    if ($Aplic->profissional && $projeto_id) {
        require_once BASE_DIR . '/modulos/projetos/funcoes_pro.php';
        $vetor = ($projeto_id ? array($projeto_id => $projeto_id) : array());
        portfolio_projetos($projeto_id, $vetor);
        $lista_projetos = implode(',', $vetor);
    }

    $sql->adTabela('tarefas', 't');
    $sql->esqUnir('projetos', 'pr', 't.tarefa_projeto = pr.projeto_id');
    $sql->esqUnir('usuarios', 'u', 'pr.projeto_responsavel = u.usuario_id');
    $sql->esqUnir('cias', 'cias', 'pr.projeto_cia = cias.cia_id');
    $sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
    $sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = t.tarefa_id');
    $sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
    $sql->adOnde('pr.projeto_template=0 OR pr.projeto_template IS NULL');
    $sql->adOnde('tarefa_percentagem = 100');
    $sql->adOnde('pr.projeto_id IS NOT NULL');
    $sql->adOnde('pr.projeto_ativo = 1');

    if ($usuario_id > 0) {
        $sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = t.tarefa_id');
        $sql->adOnde('ut.usuario_id ='.$usuario_id.' OR tarefa_dono='.$usuario_id);
    }
    if ($lista_projetos === false && $projeto_id != 0) $sql->adOnde('t.tarefa_projeto ='.$projeto_id);
    else if($lista_projetos) $sql->adOnde('t.tarefa_projeto IN ('.$lista_projetos.')');
    $sql->adOnde('t.tarefa_dinamica = 0');
    $sql->adOnde('tarefa_duracao > 0');
    if ($usar_periodo) {
        $sql->adOnde('tarefa_fim >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'' );
        $sql->adOnde('tarefa_fim <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'' );
    }
    $sql->adOrdem('projeto_setor ASC');
    $sql->adOrdem('projeto_nome ASC');

    $tarefas = $sql->ListaChave('tarefa_id');
    $sql->limpar();
    $vetor_nome = array();
    $qnt = 0;
    $current_date = new CData('2025-06-12 14:20:00');

    // Contar demandas e encontrar data mais antiga e tipo de tarefa mais antiga por projeto
    $demandas_por_projeto = array();
    $data_mais_antiga_por_projeto = array();
    $tipo_mais_antiga_por_projeto = array();
    $tipo = getSisValor('TipoTarefa');
    foreach ($tarefas as $tarefa) {
        $projeto_id = $tarefa['tarefa_projeto'];
        if (!isset($demandas_por_projeto[$projeto_id])) {
            $demandas_por_projeto[$projeto_id] = 0;
            // Inicializar com null e só atribuir se a tarefa não estiver 100% completa
            $data_mais_antiga_por_projeto[$projeto_id] = null;
            $tipo_mais_antiga_por_projeto[$projeto_id] = '';
        }
        $demandas_por_projeto[$projeto_id]++;
        // Considerar a data de início apenas se tarefa_percentagem < 100
        if ($tarefa['tarefa_percentagem'] < 100 && $tarefa['tarefa_inicio']) {
            $data_atual = new CData($tarefa['tarefa_inicio']);
            if (!$data_mais_antiga_por_projeto[$projeto_id] || $data_atual < $data_mais_antiga_por_projeto[$projeto_id]) {
                $data_mais_antiga_por_projeto[$projeto_id] = $data_atual;
                $tipo_mais_antiga_por_projeto[$projeto_id] = $tarefa['tarefa_tipo'] && isset($tipo[$tarefa['tarefa_tipo']]) ? $tipo[$tarefa['tarefa_tipo']] : '';
            }
        }
    }

    echo '<div class="report-container">';
    echo '<div class="report-card">';
    echo '<table class="relatorio-tabela">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>DEMANDAS</th>';
    echo '<th>TAREFAS</th>';
    echo '<th>SOLICITANTE</th>';
    echo '<th>TIPO DE TAREFA</th>';
    echo '<th>STATUS</th>';
    echo '<th>DATA</th>';
    echo '<th>DIAS</th>';
    echo '<th>PRIORIDADE</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $current_project = null;
    foreach ($tarefas as $tarefa) {
        $qnt++;
        $data_termino = $tarefa['tarefa_fim'] ? new CData($tarefa['tarefa_fim']) : null;
        $data_inicio_tarefa = $tarefa['tarefa_inicio'] ? new CData($tarefa['tarefa_inicio']) : null;

        $pending_days = 'N/A';
        if ($data_inicio_tarefa) {
            $start_date_str = $data_inicio_tarefa->format('%Y-%m-%d');
            $current_date_str = $current_date->format('%Y-%m-%d');
            $start_date = new DateTime($start_date_str);
            $current_date_dt = new DateTime($current_date_str);
            $interval = $current_date_dt->diff($start_date);
            $pending_days = $interval->days;
        }

        if (!isset($vetor_nome[$tarefa['tarefa_dono']])) {
            $vetor_nome[$tarefa['tarefa_dono']] = link_usuario($tarefa['tarefa_dono'], '', '', 'esquerda');
        }

        $tipo1 = getSisValor('Setor');
        $status = getSisValor('StatusTarefa');

        // Verificar se o projeto existe antes de exibir
        $projeto_nome = link_projeto($tarefa['tarefa_projeto']);
        if ($projeto_nome === "Projeto com ID {$tarefa['tarefa_projeto']} não existe!") {
            continue;
        }

        // Mostrar o nome do projeto apenas na primeira ocorrência
        if ($current_project !== $tarefa['tarefa_projeto']) {
            echo '<tr>';
            echo '<td><b>' . $projeto_nome . '</b></td>';
            echo '<td><b>' . $demandas_por_projeto[$tarefa['tarefa_projeto']] . '</b></td>';
            echo '<td><b>' . ($tarefa['projeto_setor'] && isset($tipo1[$tarefa['projeto_setor']]) ? $tipo1[$tarefa['projeto_setor']] : ' ') . '</b></td>';
            echo '<td><b>' . $tipo_mais_antiga_por_projeto[$tarefa['tarefa_projeto']] . '</b></td>';
            echo '<td>' . ($tarefa['tarefa_status'] && isset($status[$tarefa['tarefa_status']]) ? $status[$tarefa['tarefa_status']] : ' ') . '</td>';
            echo '<td>' . ($data_mais_antiga_por_projeto[$tarefa['tarefa_projeto']] ? $data_mais_antiga_por_projeto[$tarefa['tarefa_projeto']]->format('%d/%m/%Y') : '') . '</td>';
            echo '<td>' . $pending_days . '</td>';
            echo '<td>' . prioridade($tarefa['tarefa_prioridade']) . '</td>';
            echo '</tr>';
            $current_project = $tarefa['tarefa_projeto'];
        }
    }

    if (!$qnt) {
        echo '<tr><td colspan="8"><p class="no-data">Nenhuma tarefa encontrada</p></td></tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    // --- Cálculo dos dados por solicitante ---
    $solicitantes = array();
    $projetos_por_solicitante = array();
    $tipo1 = getSisValor('Setor');
    foreach ($tarefas as $tarefa) {
        $solicitante = $tarefa['projeto_setor'] && isset($tipo1[$tarefa['projeto_setor']]) ? $tipo1[$tarefa['projeto_setor']] : 'Desconhecido';
        if (!isset($solicitantes[$solicitante])) {
            $solicitantes[$solicitante] = 0;
        }
        $solicitantes[$solicitante]++;
        $projeto_id = $tarefa['tarefa_projeto'];
        if (!isset($projetos_por_solicitante[$solicitante])) {
            $projetos_por_solicitante[$solicitante] = array();
        }
        $projetos_por_solicitante[$solicitante][$projeto_id] = true; // garante unicidade
    }

    // --- Preparar dados para tabela e gráfico ---
    $labels = array_keys($solicitantes);
    $demandas = array();     // Projetos distintos (Demandas)
    $subdemandas = array();  // Tarefas (Subdemandas)
    $total_demandas = 0;
    foreach ($labels as $label) {
        $d = isset($projetos_por_solicitante[$label]) ? count($projetos_por_solicitante[$label]) : 0;
        $s = isset($solicitantes[$label]) ? $solicitantes[$label] : 0;
        $demandas[] = $d;
        $subdemandas[] = $s;
        $total_demandas += $d;
    }
    $colors = array('#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc948', '#b07aa1', '#ff9da7', '#9c755f', '#bab0ac');

    // --- Gráfico com base nas Demandas ---
    echo '<div class="chart-container">';
    echo '<div class="chart-card">';
    echo '<canvas id="taskChart"></canvas>';
    echo '</div>';

  // --- Quadro resumo: Demandas antes de Subdemandas ---
echo '<div class="summary-card">';
echo '<table class="solicitantes-tabela">';
echo '<thead><tr><th>SOLICITANTE</th><th>DEMANDAS</th><th>TAREFAS</th><th>PORCENTAGEM</th></tr></thead>';
echo '<tbody>';
$total_demandas_sum = 0;
$total_subdemandas_sum = 0;
foreach ($labels as $index => $label) {
    $d = $demandas[$index];
    $s = $subdemandas[$index];
    $percentage = $total_demandas > 0 ? ($d / $total_demandas * 100) : 0;
    echo '<tr>';
    echo '<td>' . htmlspecialchars($label) . '</td>';
    echo '<td>' . $d . '</td>';
    echo '<td>' . $s . '</td>';
    echo '<td>' . number_format($percentage, 2) . '%</td>';
    echo '</tr>';
    $total_demandas_sum += $d;
    $total_subdemandas_sum += $s;
}
echo '</tbody>';
echo '<tfoot>';
echo '<tr>';
echo '<td><b>Total</b></td>';
echo '<td><b>' . $total_demandas_sum . '</b></td>';
echo '<td><b>' . $total_subdemandas_sum . '</b></td>';
echo '<td><b>100.00%</b></td>';
echo '</tr>';
echo '</tfoot>';
echo '</table>';
echo '</div>'; // summary-card

    // --- Script do gráfico ---
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '    var ctx = document.getElementById("taskChart").getContext("2d");';
    echo '    if (ctx) {';
    echo '        var taskChart = new Chart(ctx, {';
    echo '            type: "pie",';
    echo '            data: {';
    echo '                labels: ' . json_encode($labels) . ',';
    echo '                datasets: [{';
    echo '                    data: ' . json_encode($demandas) . ',';
    echo '                    backgroundColor: ' . json_encode($colors) . ',';
    echo '                    borderWidth: 1,';
    echo '                    borderColor: "#fff",';
    echo '                    hoverOffset: 10';
    echo '                }]';
    echo '            },';
    echo '            options: {';
    echo '                responsive: true,';
    echo '                maintainAspectRatio: false,';
    echo '                plugins: {';
    echo '                    legend: {';
    echo '                        position: "right",';
    echo '                        labels: {';
    echo '                            font: { size: 14, family: "Arial", weight: "bold" }';
    echo '                        }';
    echo '                    },';
    echo '                    tooltip: {';
    echo '                        backgroundColor: "#333",';
    echo '                        titleColor: "#fff",';
    echo '                        bodyColor: "#fff",';
    echo '                        borderColor: "#fff",';
    echo '                        borderWidth: 1,';
    echo '                        padding: 12,';
    echo '                        callbacks: {';
    echo '                            label: function(tooltipItem) {';
    echo '                                var value = tooltipItem.raw;';
    echo '                                var percentage = ((value / ' . $total_demandas . ') * 100).toFixed(2) + "%";';
    echo '                                return tooltipItem.label + ": " + value + " (" + percentage + ")";';
    echo '                            }';
    echo '                        }';
    echo '                    }';
    echo '                },';
    echo '                title: {';
    echo '                    display: true,';
    echo '                    text: "Demandas por Solicitante",';
    echo '                    font: { size: 18, family: "Arial", weight: "bold" }';
    echo '                }';
    echo '            }';
    echo '        });';
    echo '    } else {';
    echo '        console.log("Erro: Contexto do canvas não encontrado.");';
    echo '    }';
    echo '});';
    echo '</script>';
}

if (!$dialogo) {
    echo '</div>';
}
?>

<style>
.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.header h2 {
    margin: 0;
    font-family: Arial, sans-serif;
    color: #333;
}
.print-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #4e79a7;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    text-decoration: none;
    font-family: Arial, sans-serif;
    font-weight: bold;
}
.print-btn:hover {
    background: #3a5f8b;
}
.filter-box {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}
.filtro-tabela {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}
.filtro-tabela td {
    padding: 8px;
    border: none;
    vertical-align: middle;
}
.filtro-tabela td label {
    font-weight: bold;
    margin-right: 5px;
}
.texto {
    padding: 6px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: Arial, sans-serif;
}
.action-btn {
    background: #4e79a7;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    cursor: pointer;
    font-family: Arial, sans-serif;
    font-weight: bold;
}
.action-btn:hover {
    background: #3a5f8b;
}
.report-container {
    margin-top: 20px;
}
.report-card, .chart-card, .summary-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    padding: 20px;
    margin-bottom: 20px;
}
.chart-container {
    display: flex;
    gap: 20px;
    margin-top: 20px;
    flex-wrap: wrap;
}
.chart-card {
    flex: 1;
    min-width: 400px;
    max-width: 600px;
    height: 400px;
}
.summary-card {
    flex: 1;
    min-width: 300px;
    max-width: 400px;
    overflow-x: auto;
}
.relatorio-tabela {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}
.relatorio-tabela th {
    padding: 12px;
    background: #4e79a7;
    color: #fff;
    text-align: center;
    position: sticky;
    top: 0;
    font-weight: bold;
}
.relatorio-tabela td {
    padding: 10px;
    border-bottom: 1px solid #e0e0e0;
    text-align: center;
}
.relatorio-tabela tr:nth-child(even) {
    background: #f9f9f9;
}
.relatorio-tabela tr:hover {
    background: #e0f7fa;
}
.solicitantes-tabela {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}
.solicitantes-tabela th, .solicitantes-tabela td {
    padding: 10px;
    border: 1px solid #e0e0e0;
    text-align: left;
}
.solicitantes-tabela th {
    background: #4e79a7;
    color: #fff;
}
.solicitantes-tabela tr:nth-child(even) {
    background: #f9f9f9;
}
.no-data {
    text-align: center;
    color: #666;
    font-style: italic;
}
</style>

<script type="text/javascript">
function expandir_colapsar(campo) {
    var element = document.getElementById(campo);
    element.style.display = element.style.display ? '' : 'none';
}
function setData(frm_nome, f_data) {
    var campo_data = eval('document.' + frm_nome + '.' + f_data);
    var campo_data_real = eval('document.' + frm_nome + '.' + 'reg_' + f_data);
    if (campo_data.value.length > 0) {
        if (parsfimData(campo_data.value) == null) {
            alert('Data inválida. Redigite, por favor.');
            campo_data_real.value = '';
            campo_data.style.backgroundColor = 'red';
        } else {
            campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
            campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
            campo_data.style.backgroundColor = '';
        }
    } else {
        campo_data_real.value = '';
    }
}
function popUsuario(campo) {
    if (window.parent.gpwebApp) {
        parent.gpwebApp.popUp('Usuário', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id=' + document.getElementById('usuario_id').value, window.setUsuario, window);
    } else {
        window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id=' + document.getElementById('usuario_id').value, 'Usuário', 'height=500,width=500,resizable,scrollbars=yes,left=0,top=0');
    }
}
function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia) {
    document.getElementById('usuario_id').value = usuario_id;
    document.getElementById('nome_usuario').value = posto + ' ' + nome + (funcao ? ' - ' + funcao : '') + (nome_cia && <?php echo $Aplic->getPref('om_usuario') ?> ? ' - ' + nome_cia : '');
}
var cal1 = Calendario.setup({
    trigger: "f_btn1",
    inputField: "reg_data_inicio",
    date: <?php echo $data_inicio->format("%Y%m%d") ?>,
    selection: <?php echo $data_inicio->format("%Y%m%d") ?>,
    onSelect: function(cal1) {
        var date = cal1.selection.get();
        if (date) {
            date = Calendario.intToDate(date);
            document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
            document.getElementById("reg_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
        }
        cal1.hide();
    }
});
var cal2 = Calendario.setup({
    trigger: "f_btn2",
    inputField: "reg_data_fim",
    date: <?php echo $data_fim->format("%Y%m%d") ?>,
    selection: <?php echo $data_fim->format("%Y%m%d") ?>,
    onSelect: function(cal2) {
        var date = cal2.selection.get();
        if (date) {
            date = Calendario.intToDate(date);
            document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
            document.getElementById("reg_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
        }
        cal2.hide();
    }
});
</script>