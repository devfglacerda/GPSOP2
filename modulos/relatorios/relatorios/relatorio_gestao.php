<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] - Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $ver_todos_projetos;
$Aplic->carregarCalendarioJS();
$mostrarNomeProjeto = nome_projeto($projeto_id);
$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', 0);
$reg_data_inicio = getParam($_REQUEST, 'reg_data_inicio', 0);
$reg_data_fim = getParam($_REQUEST, 'reg_data_fim', 0);
$usuario_id = getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$log_pdf = getParam($_REQUEST, 'log_pdf', 0);
$usar_periodo = getParam($_REQUEST, 'usar_periodo', 0);
$data_inicio = intval($reg_data_inicio) ? new CData($reg_data_inicio) : new CData('2020-01-01');
$data_fim = intval($reg_data_fim) ? new CData($reg_data_fim) : new CData('2025-12-31');
if (!$reg_data_inicio) $data_inicio->subtrairIntervalo(new Data_Intervalo('14,0,0,0'));
$data_fim->setTime(23, 59, 59);

echo '<input type="hidden" name="fazer_relatorio" value="0" />';

$data = new CData();

$titulo = 'Lista de '.$config['tarefas'].($usuario_id ? ' finalizadas pelo '.link_usuario($usuario_id): ' finalizadas por todos '.$config['genero_usuario'].'s '.$config['usuarios']).($projeto_id && (!$ver_todos_projetos) ? ' n'.$config['genero_projeto'].' '.$config['projeto'].' '.$mostrarNomeProjeto : ' em tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos']);
if (!$dialogo){
    echo '<table width="100%">';
    echo '<tr><td width="22"> </td>';
    echo '<td align="center">';
    echo '<font size="4"><center>'.$titulo.'</center></font>';
    echo '</td>';
    echo ($dialogo ? '' : '<td width="32">'.dica('Imprimir o relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'<a href="javascript: void(0);" onclick="env.target=\'popup\'; env.dialogo.value=1; env.pdf.value=0; env.sem_cabecalho.value=0; env.submit();"><img src="'.acharImagem('imprimir.png').'" border=0 width="32" heigth="32" /></a>'.dicaF().'</td>');
    echo '</tr>';
    echo '</table>';
}
else if ($Aplic->profissional) {
    include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
    include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
    $dados = array();
    $dados['projeto_cia'] = $Aplic->usuario_cia;
    $sql->adTabela('artefatos_tipo');
    $sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
    $sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
    $sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
    $linha = $sql->linha();
    $sql->limpar();
    $campos = unserialize($linha['artefato_tipo_campos']);
    
    $modelo = new Modelo;
    $modelo->set_modelo_tipo(1);
    foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
    $tpl = new Template($linha['artefato_tipo_html'], false, false, false, true);
    $modelo->set_modelo($tpl);
    echo '<table align="left" cellspacing=0 cellpadding=0 width=100%><tr><td>';
    for ($i = 1; $i <= $modelo->quantidade(); $i++){
        $campo = 'campo_'.$i;
        $tpl->$campo = $modelo->get_campo($i);
    } 
    echo $tpl->exibir($modelo->edicao); 
    echo '</td></tr></table>';
    echo '<font size="4"><center>'.$titulo.'</center></font>';
}
else echo '<font size="4"><center>'.$titulo.'</center></font>';

if (!$dialogo){
    echo estiloTopoCaixa();
    echo '<table cellspacing=0 cellpadding="4" border=0 width="100%" class="std">';
    echo '<tr><td align="left" style="white-space: nowrap">';
    echo dica('Data Inicial', 'Digite ou escolha no calendário a data de início da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'De:'.dicaF().'<input type="hidden" name="reg_data_inicio" id="reg_data_inicio" value="'.($data_inicio ? $data_inicio->format('%Y%m%d') : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de início da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF();
    echo dica('Data Final', 'Digite ou escolha no calendário a data final da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Até:'.dicaF().'<input type="hidden" name="reg_data_fim" id="reg_data_fim" value="'.($data_fim ? $data_fim->format('%Y%m%d') : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\');" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF();
    echo dica('Filtro por '.ucfirst($config['usuario']), 'Selecione na caixa à direita para qual '.$config['usuario'].' deseja visualizar os resultados.').ucfirst($config['usuario']).': '.dicaF().'<input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a>';
    echo '<input type="checkbox" style="vertical-align:middle" name="usar_periodo" id="usar_periodo" '.($usar_periodo ? 'checked="checked"' : '').' />'.dica('Usar o Período', 'Selecione esta caixa para exibir o resultado da pesquisa na faixa de tempo selecionada.').'Usar o período'.dicaF();
    echo '</td><td align="right" style="white-space: nowrap">'.botao('exibir', 'Exibir', 'Exibir o resultado da pesquisa.','','env.fazer_relatorio.value=1; env.target=\'\'; env.dialogo.value=0; env.sem_cabecalho.value=0; env.pdf.value=0; env.submit();').'</td></tr>';
}

if ($fazer_relatorio || $dialogo) {
    $sql = new BDConsulta;
    
    $lista_projetos = false;
    if($Aplic->profissional && $projeto_id){
        require_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
        $vetor = ($projeto_id ? array($projeto_id => $projeto_id) : array());
        portfolio_projetos($projeto_id, $vetor);
        $lista_projetos = implode(',', $vetor);
    }

    $sql->adTabela('tarefas', 't');    
    $sql->esqUnir('projetos', 'pr', 't.tarefa_projeto = pr.projeto_id');
    $sql->esqUnir('usuarios', 'u', 'pr.projeto_responsavel = u.usuario_id');
    $sql->esqUnir('cias', 'cias', 'pr.projeto_cia = cias.cia_id');
    $sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
    $sql->adOnde('pr.projeto_template=0 OR pr.projeto_template IS NULL');
    $sql->adOnde('tarefa_percentagem = 100');
    if ($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta) $sql->esqUnir('projeto_gestao', 'projeto_gestao', 'pr.projeto_id=projeto_gestao_projeto');
    
    if ($filtro_criterio){
        $sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=projeto_gestao.projeto_gestao_pratica');
        $sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
        $sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
    }
        
    if ($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta) {
        $filtragem = array();
        if ($filtro_criterio) $filtragem[] = 'pratica_item_criterio IN ('.$filtro_criterio.')';
        if ($filtro_perspectiva) $filtragem[] = 'projeto_gestao_perspectiva IN ('.$filtro_perspectiva.')';
        if ($filtro_tema) $filtragem[] = 'projeto_gestao_tema IN ('.$filtro_tema.')';
        if ($filtro_objetivo) $filtragem[] = 'projeto_gestao_objetivo IN ('.$filtro_objetivo.')';
        if ($filtro_fator) $filtragem[] = 'projeto_gestao_fator IN ('.$filtro_fator.')';
        if ($filtro_estrategia) $filtragem[] = 'projeto_gestao_estrategia IN ('.$filtro_estrategia.')';
        if ($filtro_meta) $filtragem[] = 'projeto_gestao_meta IN ('.$filtro_meta.')';
        if (count($filtragem)) $sql->adOnde(implode(' OR ', $filtragem));
    }   

    if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
    if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
    if (!$portfolio && !$portfolio_pai) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
    elseif($portfolio && !$portfolio_pai)  $sql->adOnde('pr.projeto_portfolio=1 AND (pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL)');
    if ($portfolio_pai){
        $sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
        $sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
    }
    if ($favorito_id){
        $sql->internoUnir('favorito_lista', 'favorito_lista', 'pr.projeto_id=favorito_lista_campo');
        $sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
        $sql->adOnde('favorito.favorito_id IN ('.$favorito_id.')');
    }
    if($dept_id) $sql->esqUnir('projeto_depts', 'projeto_depts', 'projeto_depts.projeto_id = pr.projeto_id');
    if (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');        
    if ($projetostatus){
        if ($projetostatus == -1) $sql->adOnde('projeto_ativo = 1');
        elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo = 0');
        elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
    }   
    else $sql->adOnde('projeto_ativo = 1'); 
    if($dept_id) $sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.')');  
    if ($cia_id  && !$lista_cias && !$favorito_id) $sql->adOnde('pr.projeto_cia = '.(int)$cia_id);
    elseif ($lista_cias && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.')');
    if ($projeto_tipo > -1) $sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
    if ($projeto_setor) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
    if ($projeto_segmento) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
    if ($projeto_intervencao) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
    if ($projeto_tipo_intervencao) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
    if ($supervisor) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
    if ($autoridade) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
    if ($responsavel) $sql->adOnde('pr.projeto_responsavel IN ('.$responsavel.')');
    if (trim($pesquisar_texto)) $sql->adOnde('pr.projeto_nome LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_objetivos LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_como LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_codigo LIKE \'%'.$pesquisar_texto.'%\'');
    $sql->adOnde('projeto_template = 0');

    $sql->adCampo('tarefa_percentagem, tarefa_prioridade, projeto_setor, t.tarefa_id, tarefa_projeto, tarefa_fim, tarefa_inicio, tarefa_duracao, projeto_nome, projeto_descricao, u.usuario_login, u.usuario_id, tarefa_dono, tarefa_tipo, tarefa_status');
    if ($usuario_id > 0) {
        $sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = t.tarefa_id');
        $sql->adOnde('ut.usuario_id ='.$usuario_id.' OR tarefa_dono='.$usuario_id);
    }
    if ($lista_projetos === false && $projeto_id != 0) $sql->adOnde('tarefa_projeto ='.$projeto_id);
    else if($lista_projetos){
        $sql->adOnde('tarefa_projeto IN ('.$lista_projetos.')');
    }
    $sql->adOnde('t.tarefa_dinamica = 0');
    $sql->adOnde('tarefa_duracao > 0');
    if ($usar_periodo){
        $sql->adOnde('tarefa_fim >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
        $sql->adOnde('tarefa_fim <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'');
    }
    $sql->adOrdem('projeto_nome ASC');
    $sql->adOrdem('tarefa_fim ASC');
    
    $tarefas = $sql->ListaChave('tarefa_id');
    $sql->limpar();

    // Definir o mapeamento de tipos de tarefa para nomes de atividades
    $tipo_map = [
        1 => 'Projeto',
        2 => 'Análise de projeto',
        5 => 'Vistoria e Parecer',
        6 => 'Utilização de tabela Seinfra',
        7 => 'Orçamento',
        10 => 'Análise de Orçamento',
        11 => 'Revisão de Orçamento',
        13 => 'Relatório Técnico',
        14 => 'Revisão de projeto - as built'
    ];

    // Inicializar o array de exibição com todas as atividades possíveis
    $atividades_exibicao = array_fill_keys(array_values($tipo_map), 0);
    $atividades_exibicao['Sem Tipo'] = 0;

    // Contar as tarefas por tipo
    $total = 0;
    foreach ($tarefas as $tarefa) {
        $tipo_id = $tarefa['tarefa_tipo'];
        $nome_atividade = isset($tipo_map[$tipo_id]) ? $tipo_map[$tipo_id] : 'Sem Tipo';
        $atividades_exibicao[$nome_atividade]++;
        $total++;
    }

    // Calcular as porcentagens
    $porcentagens = [];
    foreach ($atividades_exibicao as $atividade => $quantidade) {
        $porcentagens[$atividade] = $total > 0 ? ($quantidade / $total) * 100 : 0;
    }

    // Exibir a tabela
    if (!$dialogo) echo '<tr><td>';
    echo '<table cellspacing=0 cellpadding="4" border=0 class="tbl1" align="center">';
    echo '<tr align="center"><th>ATIVIDADE</th><th>QUANTIDADE</th><th>PORCENTAGEM</th></tr>';
    foreach ($atividades_exibicao as $atividade => $quantidade) {
        $percentagem = number_format($porcentagens[$atividade], 2, ',', '.');
        echo '<tr align="center">';
        echo '<td align="left">' . htmlspecialchars($atividade) . '</td>';
        echo '<td>' . $quantidade . '</td>';
        echo '<td>' . $percentagem . '%</td>';
        echo '</tr>';
    }
    echo '<tr align="center"><th>TOTAL</th><th>' . $total . '</th><th>100,00%</th></tr>';
    echo '</table>';

    // Exibir o gráfico de pizza
    echo '<canvas id="pizzaChart" style="max-width: 400px; max-height: 400px; margin: 20px auto; display: block;"></canvas>';
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>
        var ctx = document.getElementById("pizzaChart").getContext("2d");
        var pizzaChart = new Chart(ctx, {
            type: "pie",
            data: {
                labels: [';
    foreach ($atividades_exibicao as $atividade => $quantidade) {
        echo '"' . addslashes($atividade) . '",';
    }
    echo '],
                datasets: [{
                    data: [';
    foreach ($atividades_exibicao as $quantidade) {
        echo $quantidade . ',';
    }
    echo '],
                    backgroundColor: [
                        "rgba(54, 162, 235, 0.8)",  // Azul (Projeto)
                        "rgba(255, 99, 132, 0.8)",  // Vermelho (Sem Tipo)
                        "rgba(255, 206, 86, 0.8)",  // Amarelo (Análise de projeto)
                        "rgba(75, 192, 192, 0.8)",  // Ciano (Utilização de tabela Seinfra)
                        "rgba(153, 102, 255, 0.8)", // Roxo (Orçamento)
                        "rgba(255, 159, 64, 0.8)",  // Laranja (Revisão de Orçamento)
                        "rgba(201, 203, 207, 0.8)", // Cinza (Análise de Orçamento)
                        "rgba(54, 162, 235, 0.5)",  // Azul claro (Relatório Técnico)
                        "rgba(75, 192, 192, 0.5)",  // Ciano claro (Revisão de projeto - as built)
                        "rgba(255, 206, 86, 0.5)"   // Amarelo claro (Vistória e Parecer)
                    ],
                    borderColor: [
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 99, 132, 1)",
                        "rgba(255, 206, 86, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(153, 102, 255, 1)",
                        "rgba(255, 159, 64, 1)",
                        "rgba(201, 203, 207, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(255, 206, 86, 1)"
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "bottom"
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || "";
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = ((value / total) * 100).toFixed(2) + "%";
                                return label + ": " + value + " (" + percentage + ")";
                            }
                        }
                    }
                }
            }
        });
    </script>';

    if (!$dialogo) {
        echo '</td></tr></table>';    
        echo estiloFundoCaixa();    
    }
}
?>

<script type="text/javascript">
function setData(frm_nome, f_data) {
    campo_data = eval('document.' + frm_nome + '.' + f_data);
    campo_data_real = eval('document.' + frm_nome + '.' + 'reg_' + f_data);
    if (campo_data.value.length > 0) {
        if ((parsfimData(campo_data.value)) == null) {
            alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
            campo_data_real.value = '';
            campo_data.style.backgroundColor = 'red';
        } 
        else {
            campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
            campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
            campo_data.style.backgroundColor = '';
        }
    } 
    else campo_data_real.value = '';
}

function popUsuario(campo) {
    if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
    else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
    document.getElementById('usuario_id').value = usuario_id;
    document.getElementById('nome_usuario').value = posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');    
}

var cal1 = Calendario.setup({
    trigger    : "f_btn1",
    inputField : "reg_data_inicio",
    date :  <?php echo $data_inicio->format("%Y%m%d")?>,
    selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
        var date = cal1.selection.get();
        if (date){
            date = Calendario.intToDate(date);
            document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
            document.getElementById("reg_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
        }
        cal1.hide(); 
    }
});

var cal2 = Calendario.setup({
    trigger : "f_btn2",
    inputField : "reg_data_fim",
    date : <?php echo $data_fim->format("%Y%m%d")?>,
    selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) { 
        var date = cal2.selection.get();
        if (date){
            date = Calendario.intToDate(date);
            document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
            document.getElementById("reg_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
        }
        cal2.hide(); 
    }
});
</script>