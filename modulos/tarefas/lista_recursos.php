<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$baseline_id=getParam($_REQUEST, 'baseline_id', 0);


$icone_ver_obs=imagem('icones/msg10000.gif','Ver Observações','Clique neste ícone '.imagem('icones/msg10000.gif').' para ver as observações');

$sql = new BDConsulta;

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();


$sql->adTabela('projetos');
if ($tarefa_id) $sql->esqUnir('tarefas','tarefas', 'projetos.projeto_id=tarefas.tarefa_projeto');
$sql->adCampo('projeto_moeda');
if ($tarefa_id) $sql->adOnde('tarefa_id ='.(int)$tarefa_id);	
else $sql->adOnde('projeto_id ='.(int)$projeto_id);	
$projeto_moeda=$sql->Resultado();
$sql->limpar();
	
$divisor_cotacao=($projeto_moeda!=1 ? cotacao($projeto_moeda, date('Y-m-d')) : 1);


$sql->adTabela(($baseline_id ? 'baseline_' : '').'recurso_tarefa', 'recurso_tarefa');
$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id=recurso_tarefa_tarefa');
$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id = recurso_tarefa_recurso');
$sql->adCampo('recurso_tarefa_id, recursos.recurso_id, recurso_tipo, recurso_tarefa_custo, recurso_tarefa_tarefa, recurso_tarefa_recurso, recurso_nivel_acesso, recurso_tarefa_aprovou, recurso_tarefa_aprovado, recurso_tarefa_valor_hora, formatar_data(recurso_tarefa_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(recurso_tarefa_fim, \'%d/%m/%Y %H:%i\') AS fim, recurso_tarefa_duracao, recurso_tarefa_quantidade, recurso_tarefa_percentual, formatar_data(recurso_tarefa_data, \'%d/%m/%Y %H:%i\') AS data_aprovacao');
$sql->adCampo('(SELECT count(custo_observacao_id) FROM custo_observacao WHERE custo_observacao_recurso_tarefa=recurso_tarefa_id) AS qnt_obs');
if ($tarefa_id) $sql->adOnde('recurso_tarefa_tarefa = '.(int)$tarefa_id);
else $sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$sql->adOrdem('recurso_tarefa_tarefa, recurso_tarefa_ordem');
$recursos = $sql->Lista();
$sql->limpar();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="tbl1">
	<th>'.dica('Nome','Nome do recurso alocado.').'Nome'.dicaF().'</th>
	<th>'.dica('Início','Início da alocação do recurso.').'Início'.dicaF().'</th>
	<th>'.dica('Término','Término da alocação do recurso.').'Término'.dicaF().'</th>
	<th>'.dica('Horas','Total de horas úteis na alocação do recurso.').'Horas'.dicaF().'</th>
	<th>'.dica('Quantidade','Quantidade do recurso alocado.').'Quant.'.dicaF().'</th>
	<th>'.dica('Percentual','Percentual do recurso alocado.').'%'.dicaF().'</th>
	<th>'.dica('Valor hora', 'O valor da hora de alocação do recurso.').'Valor/hora'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor unitário do recurso.').'Valor/unit.'.dicaF().'</th>
	<th>'.dica('Custo Extra','Custo extra do recurso alocado.').'Custo Extra'.dicaF().'</th>
	<th>'.dica('Aprovado','Se o recurso alocado foi aprovado.').'Aprov.'.dicaF().'</th>';
	echo '<th>'.dica('Observação','Observação sobre a alocação do recurso alocado.').'Obs.'.dicaF().'</th>';
	echo '</tr>';
foreach ($recursos as $linha1) {
	if ($linha1['recurso_tarefa_recurso'] && !isset($recurso[$linha1['recurso_tarefa_recurso']])) $recurso[$linha1['recurso_tarefa_recurso']]=link_recurso($linha1['recurso_tarefa_recurso']);
	if ($linha1['recurso_tarefa_aprovou'] && !isset($usuario[$linha1['recurso_tarefa_aprovou']])) $usuario[$linha1['recurso_tarefa_aprovou']]=link_usuario($linha1['recurso_tarefa_aprovou']);

	echo '<tr>';
	echo '<td>'.($linha1['recurso_tarefa_recurso']  ? $recurso[$linha1['recurso_tarefa_recurso']] : '&nbsp;').'</td>';
	echo '<td align=center style="white-space: nowrap" width=110>'.($linha1['recurso_tipo']!=5 ? $linha1['inicio'] : '').'</td>';
	echo '<td align=center style="white-space: nowrap" width=110>'.($linha1['recurso_tipo']!=5 ? $linha1['fim'] : '').'</td>';
	echo '<td align=right style="white-space: nowrap" width=50>'.($linha1['recurso_tipo']!=5 ? number_format($linha1['recurso_tarefa_duracao'], 2, ',', '.') : '').'</td>';
  echo '<td align=right>'.number_format($linha1['recurso_tarefa_quantidade'], 2, ',', '.').'</td>';
 	echo '<td align=right style="white-space: nowrap" width=30>'.($linha1['recurso_tipo'] < 4 ? $linha1['recurso_tarefa_percentual'] : '').'</td>';
	echo '<td align=right style="white-space: nowrap" width=70>'.($linha1['recurso_tipo'] < 4 ? number_format($linha1['recurso_tarefa_valor_hora'], 2, ',', '.') : '').'</td>';
	echo '<td align=right style="white-space: nowrap" width=70>'.($linha1['recurso_tipo']==4 ? number_format($linha1['recurso_tarefa_custo'], 2, ',', '.') : '').'</td>';
	if ($linha1['recurso_tipo'] < 4){
		//gastos extras
		$sql->adTabela('recurso_tarefa_custo');
		$sql->adCampo('SUM((recurso_tarefa_custo_quantidade*recurso_tarefa_custo_valor*recurso_tarefa_custo_cotacao)*((100+recurso_tarefa_custo_bdi)/100))');
		$sql->adOnde('recurso_tarefa_custo_recurso_tarefa='.(int)$linha1['recurso_tarefa_id']);
		$gasto_extra=$sql->Resultado();
		$sql->limpar();
		echo '<td align=right>'.($gasto_extra > 0 ? '<a href="javascript:void(0);" onclick="popGastoExtra('.$linha1['recurso_tarefa_id'].')">' : '').$moedas[$projeto_moeda].' '.number_format($gasto_extra/$divisor_cotacao, 2, ',', '.').($gasto_extra > 0 ? '</a>' : '').'</td>';
		}
	else echo '<td align=right></td>';
	echo '<td align=center width=30>'.($linha1['recurso_tarefa_aprovado'] > 0  ? 'Sim' : ($linha1['recurso_tarefa_aprovado'] < 0 ? 'Não' :'&nbsp;')).'</td>';
	
	echo '<td width=16 align=center style="white-space: nowrap">'.($linha1['qnt_obs'] ? '<a href="javascript:void(0);" onclick="ver_observacao('.$linha1['recurso_tarefa_id'].')">'.$icone_ver_obs.'</a>' : '').'</td>';
	
	echo '</tr>';
	}
echo '</table>';
?>
<script type="text/JavaScript">
	
function ver_observacao(recurso_tarefa_id) {
	parent.gpwebApp.popUp('Observações', 1000, 600, 'm=projetos&a=aprovar_custos_observacao_pro&dialogo=1&editar=0&recurso_tarefa_id='+recurso_tarefa_id, null, window);
	}			

function popGastoExtra(recurso_tarefa_id) {
	parent.gpwebApp.popUp('Gastos Extras', 800, 600, 'm=projetos&a=aprovar_recurso_custo_extra_pro&dialogo=1&recurso_tarefa_id='+recurso_tarefa_id, null, window);
	}

</script>
