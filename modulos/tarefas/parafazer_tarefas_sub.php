<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb.
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $perms, $usuario_id, $podeEditar, $tab, $podeExcluir, $podeEditar, $tarefa_status;

$sql = new BDConsulta;
$coletivo=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$usuario_id);
$podeEditar = $podeEditar;

// Parâmetros de ordenação
$tarefa_ordenar_item1=getParam($_REQUEST, 'tarefa_ordenar_item1', '');
$tarefa_ordenar_tipo1=getParam($_REQUEST, 'tarefa_ordenar_tipo1', '');
$tarefa_ordenar_item2=getParam($_REQUEST, 'tarefa_ordenar_item2', '');
$tarefa_ordenar_tipo2=getParam($_REQUEST, 'tarefa_ordenar_tipo2', '');
$tarefa_ordenar_ordem1 = intval(getParam($_REQUEST, 'tarefa_ordenar_ordem1', 0));
$tarefa_ordenar_ordem2 = intval(getParam($_REQUEST, 'tarefa_ordenar_ordem2', 0));
$status = getSisValor('StatusTarefa');

// Query Principal
$sql->adTabela('tarefas', 'ta');
$sql->esqUnir('projetos', 'pr','pr.projeto_id=tarefa_projeto');
$sql->esqUnir('tarefa_designados', 'td','td.tarefa_id = ta.tarefa_id');
$sql->esqUnir('usuario_tarefa_marcada', 'tp', 'tp.tarefa_id = ta.tarefa_id and tp.usuario_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id));
$sql->adCampo('DISTINCT ta.tarefa_id, tarefa_prioridade, tarefa_percentagem, tarefa_dinamica, tarefa_status, tarefa_marcada, tarefa_inicio, tarefa_fim, tarefa_projeto, tarefa_marco, tarefa_duracao, tarefa_acesso');
$sql->adCampo('projeto_nome, pr.projeto_id, projeto_cor');
$sql->adCampo('diferenca_data(tarefa_fim,tarefa_inicio) as dias');
$sql->adCampo('tarefa_marcada');
$sql->adOnde('projeto_template = 0 OR projeto_template IS NULL');
$sql->adOnde('( ta.tarefa_percentagem < 100 OR ta.tarefa_percentagem IS NULL)');
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('(td.usuario_id '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).' OR tarefa_dono '.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.$usuario_id).')');
$sql->adOrdem('ta.tarefa_inicio');
$tarefas = $sql->Lista();
$sql->limpar();

// Cálculos de datas
for ($j = 0, $j_cmp = count($tarefas); $j < $j_cmp; $j++) {
	if (!$tarefas[$j]['tarefa_fim']) {
		if (!$tarefas[$j]['tarefa_inicio']) {
			$tarefas[$j]['tarefa_inicio'] = null; 
			$tarefas[$j]['tarefa_fim'] = null;
		} 
		else $tarefas[$j]['tarefa_fim'] = calcFimPorInicioEDuracao($tarefas[$j]);
	}
}

$prioridades = array('2' =>'muito alta', '1' => 'alta', '0' => 'normal', '-1' => 'baixa', '-2' => 'muito baixa');
$tipoDuracao = getSisValor('TipoDuracaoTarefa');
$agora = new CData();

foreach ($tarefas as $tId => $tarefa) {
	$sinal = 1;
	$inicio = intval($tarefa['tarefa_inicio']) ? new CData($tarefa['tarefa_inicio']) : null;
	$fim = intval($tarefa['tarefa_fim']) ? new CData($tarefa['tarefa_fim']) : null;
	if (!$fim && $inicio) {
		$fim = $inicio;
		$fim->adSegundos($tarefa['tarefa_duracao'] * $tarefa['tarefa_duracao_tipo'] * 3600);
	}
	if ($fim && $agora->after($fim)) $sinal = -1;
	$dias = $fim ? $agora->dataDiferenca($fim) * $sinal : null;
	$tarefas[$tId]['tarefa_fazer_em'] = $dias;
}

// Ordenação
if ($tarefa_ordenar_item1 != '') {
	if ($tarefa_ordenar_item2 != '' && $tarefa_ordenar_item1 != $tarefa_ordenar_item2) $tarefas = vetor_ordenar($tarefas, $tarefa_ordenar_item1, $tarefa_ordenar_ordem1, $tarefa_ordenar_tipo1, $tarefa_ordenar_item2, $tarefa_ordenar_ordem2, $tarefa_ordenar_tipo2);
	else $tarefas = vetor_ordenar($tarefas, $tarefa_ordenar_item1, $tarefa_ordenar_ordem1, $tarefa_ordenar_tipo1);
} 
else { 
	for ($j = 0, $j_cmp = count($tarefas); $j < $j_cmp; $j++) {
		if (!$tarefas[$j]['tarefa_fim']) {	
			if (!$tarefas[$j]['tarefa_inicio']) {
				$tarefas[$j]['tarefa_inicio'] = null; 
				$tarefas[$j]['tarefa_fim'] = null;
			} 
			else $tarefas[$j]['tarefa_fim'] = calcFimPorInicioEDuracao($tarefas[$j]);
		}
	}
}

// --- INICIO ESTILOS MODERNOS ---
?>
<style>
    /* Variáveis de estilo */
    :root {
        --primary-color: #2b7a9e; /* Azul profissional ou cor da marca */
        --bg-light: #f8f9fa;
        --text-dark: #333;
        --border-color: #e0e0e0;
        --white: #ffffff;
        
        /* Cores de Status - Mais suaves */
        --status-future: #ffffff;
        --status-ontime: #e6eedd;
        --status-should-start: #fff3cd;
        --status-late: #f8d7da;
    }

    .modern-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        background-color: var(--bg-light);
    }

    .modern-card {
        background: var(--white);
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden; /* Para bordas arredondadas na tabela */
        margin-bottom: 20px;
        border: 1px solid var(--border-color);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .modern-table th {
        background-color: var(--primary-color);
        color: white;
        padding: 12px 10px;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #1f5c7a;
    }

    .modern-table th a {
        color: white !important;
        text-decoration: none;
    }

    .modern-table td {
        padding: 10px 10px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-dark);
        vertical-align: middle;
    }

    .modern-table tr:hover td {
        background-color: #f1f1f1 !important; /* Force hover override */
        cursor: pointer;
    }

    /* Utilitários para Status */
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        margin-right: 10px;
        border: 1px solid transparent;
    }

    .badge-future { background-color: var(--white); border: 1px solid #ccc; color: #666; }
    .badge-ontime { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .badge-should-start { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .badge-late { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    .legend-container {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: var(--white);
        border-top: 1px solid var(--border-color);
        align-items: center;
        flex-wrap: wrap;
    }

    /* Ajuste para ícones/inputs na tabela */
    .modern-table input[type="checkbox"] {
        cursor: pointer;
    }
    
    /* Remover bordas e backgrounds antigos se possível via CSS */
    .modern-table tr[bgcolor] {
        background-color: transparent; /* Tenta limpar cores hardcoded do PHP */
    }
</style>

<div class="modern-container">

<?php
echo '<form name="frm_botoes" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';
echo '<input type="hidden" name="mostrar_form" value="1" />';
echo '</form>';

// Início do Card
echo '<div class="modern-card">';
echo '<form name="frm_tarefas" id="frm_tarefas" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';

// Tabela estilizada
echo '<table class="modern-table">';
echo '<thead>';
echo '<tr>';
echo '<th width="10"></th>'; // Checkbox
echo '<th width="10">'.dica('Reg. Ocorr&ecirc;ncia', 'Registrar ocorr&ecirc;ncia').'R'.dicaF().'</th>';
echo '<th width="20">'.dica('%', 'Percentual realizado').ordenar_por_item_titulo('%', 'tarefa_percentagem', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th width="15" align="center">'.dica('Prio', 'Prioridade').ordenar_por_item_titulo('P', 'tarefa_prioridade', SORT_NUMERIC, '&a=parafazer').dicaF().'</th>';
echo '<th>'.ordenar_por_item_titulo('Tarefa', 'tarefa_nome', SORT_STRING, '&a=parafazer').'</th>';
echo '<th>'.ordenar_por_item_titulo('Projeto', 'tarefa_projeto', SORT_NUMERIC, '&a=parafazer').'</th>';
echo '<th style="white-space: nowrap" width="100">'.ordenar_por_item_titulo('Status', 'tarefa_status', SORT_NUMERIC, '&a=parafazer').'</th>';
echo '<th style="white-space: nowrap" width="120">'.ordenar_por_item_titulo('In&iacute;cio', 'tarefa_inicio', SORT_NUMERIC, '&a=parafazer').'</th>';
echo '<th style="white-space: nowrap">'.ordenar_por_item_titulo('Dura&ccedil;&atilde;o', 'tarefa_duracao', SORT_NUMERIC, '&a=parafazer').'</th>';
echo '<th style="white-space: nowrap" width="120">'.ordenar_por_item_titulo('T&eacute;rmino', 'tarefa_fim', SORT_NUMERIC, '&a=parafazer').'</th>';
echo '<th style="white-space: nowrap">'.ordenar_por_item_titulo('Prazo', 'tarefa_fazer_em', SORT_NUMERIC, '&a=parafazer').'</th>';
echo '<th style="white-space: nowrap">'.ordenar_por_item_titulo('Dias', 'dias', SORT_NUMERIC, '&a=parafazer').'</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$historico_ativo = false;
$saida='';

// NOTA: A função mostrarTarefa gera TRs e TDs. O CSS tentará estilizá-los, 
// mas se mostrarTarefa usar bgcolor hardcoded, o estilo .modern-table tr:hover pode conflitar levemente.
foreach ($tarefas as $tarefa) $saida.=mostrarTarefa($tarefa, 0, false, true);

echo $saida;

if (!count($tarefas)) {
    echo '<tr><td colspan="12" style="text-align:center; padding: 20px; color: #777;">';
    echo 'Nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'].'.';
    echo '</td></tr>';
}

echo '</tbody>';
echo '</table>';
echo '</form>';

// Nova Legenda Moderna
echo '<div class="legend-container">';
echo '<strong>Legenda:</strong> ';
echo '<span class="status-badge badge-future" title="A data de início ainda não ocorreu">'.ucfirst($config['tarefa']).' Futuro</span>';
echo '<span class="status-badge badge-ontime" title="Iniciada e dentro do prazo">No Prazo</span>';
echo '<span class="status-badge badge-should-start" title="Deveria ter iniciado (0% executada)">Deveria Iniciar</span>';
echo '<span class="status-badge badge-late" title="Data de término já passou">Atrasada</span>';
echo '</div>'; // fim legend-container

echo '</div>'; // fim modern-card
echo '</div>'; // fim modern-container

if (isset($_REQUEST['usuario_id']))	echo '<script LANGUAGE="javascript">document.frm_botoes.submit();</script>'; 
?>

<script type="text/javascript">
// Mantive o JS original para garantir funcionalidade, ajustando apenas a lógica visual se necessário

function iluminar_tds(linha,alto,id){
	if(document.getElementsByTagName){
		var tcs=linha.getElementsByTagName('td');
		var nome_celula='';
		if(!id)check=false;
		else{
			var f=eval('document.frm_tarefas');
			var check=eval('f.selecionado_tarefa_'+id+'.checked')
		}
        // Cores convertidas para HEX mais suaves ou classes
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
                // Ajuste de cores para combinar com o CSS novo
				if(alto==3) tcs[j].style.background='#fffff0'; // Futuro (amarelo bem claro)
				else if(alto==2||check) tcs[j].style.background='#fae3e5'; // Marcado/Erro (vermelho claro)
				else if(alto==1) tcs[j].style.background='#f0fff4'; // Ok (verde claro)
				else tcs[j].style.background=''; // Limpa para usar CSS padrão
			}
		}
	}
}
	
var estah_marcado;

function selecionar_caixa(box,id,linha_id,nome_formulario){
	var f=eval('document.'+nome_formulario);
	var check=eval('f.'+box+'_'+id+'.checked');
	boxObj=eval('f.elements["'+box+'_'+id+'"]');
	if((estah_marcado&&boxObj.checked&&!boxObj.disabled)||(!estah_marcado&&!boxObj.checked&&!boxObj.disabled)){linha=document.getElementById(linha_id);
		boxObj.checked=true;
		iluminar_tds(linha,2,id);
		}
	else if((estah_marcado&&!boxObj.checked&&!boxObj.disabled)||(!estah_marcado&&boxObj.checked&&!boxObj.disabled)){
		linha=document.getElementById(linha_id);
		boxObj.checked=false;
		iluminar_tds(linha,3,id);
		}
	}	

</script>