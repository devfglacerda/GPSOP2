<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $config;

$impresso=getParam($_REQUEST, 'impresso', null);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$financeiro=getParam($_REQUEST, 'financeiro', '');
$baseline_id=getParam($_REQUEST, 'baseline_id', null);



if ($financeiro=='undefined') $financeiro=null; ;

if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
	$portfolio=ser_portfolio($projeto_id);
	if (!$portfolio) $portfolio=$projeto_id;
	}
else $portfolio=$projeto_id;

$eh_portfolio=($portfolio!=$projeto_id);



$tipo=getParam($_REQUEST, 'tipo', '');
$unidade=getSisValor('TipoUnidade');

$sql = new BDConsulta;

$siafi=($Aplic->profissional && $tipo!='estimado' && $Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso'));
if ($siafi){
	$sql->adTabela('financeiro_config');
	$sql->adCampo('financeiro_config_valor');
	$sql->adOnde('financeiro_config_campo = \'organizacao\'');
	$tipo_organizacao = $sql->Resultado();
	$sql->limpar();
	if ($tipo_organizacao=='sema_mt') $siafi=false;
	}

if (!$impresso) echo '<table width="100%"><tr><td width="10%">&nbsp;</td><td width="80% align="center"><center><h1>'.($tipo=='estimado' ? 'Custos Estimados' : 'Gastos').($financeiro ? ' ('.ucfirst($financeiro).')' : '').'  - '.link_projeto($projeto_id, '', '', '', '',true).'</h1></center></td>'.(!$impresso ? '<td align="right" width="10%">'.dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a planilha.').'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha\', 1000, 600, \'m=projetos&a=planilha&dialogo=1&impresso=1&projeto_id='.$projeto_id.'&tipo='.$tipo.'\', null, window);' : 'window.open(\'./index.php?m=projetos&a=planilha&dialogo=1&impresso=1&projeto_id='.$projeto_id.'&tipo='.$tipo.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td>' : '').'</tr></table>';
else {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';

	$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos', 'projetos');
	$sql->adCampo('projeto_id, projeto_cia, projeto_nome, projeto_codigo');
	$sql->adOnde('projeto_id = '.(int)$projeto_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$dados = $sql->Linha();
	$sql->limpar();
	
	$dados['titulo_cabecalho']=($tipo=='estimado' ? 'CUSTOS ESTIMADOS' : 'GASTOS').($financeiro ? ' ('.ucfirst($financeiro).')' : '');
	
	
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_projeto_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
		
	echo '<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	}



if (!$impresso) echo estiloTopoCaixa();
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 '.(!$impresso ? 'class="std2"' : '').'>';
echo '<tr><td valign="top" align="center">';

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();


if ($tipo=='estimado'){
	$sql->adTabela('tarefa_custos', 't');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=tarefa_custos_tarefa');
	$sql->adCampo('t.*,((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor, tarefa_custos_quantidade AS quantidade, tarefa_projeto');
	$sql->adOnde('t.tarefa_custos_tarefa IN (SELECT tarefa_id from tarefas WHERE tarefa_projeto IN ('.$portfolio.'))');
	$sql->adOrdem('tarefa_projeto, tarefa_custos_tarefa, tarefa_custos_ordem');	
	if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
	}
else {
	$sql->adTabela('tarefa_gastos', 't');
	$sql->adCampo('t.*, tarefa_projeto');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=tarefa_gastos_tarefa');
	$sql->adOnde('t.tarefa_gastos_tarefa IN (select tarefa_id from tarefas WHERE tarefa_projeto IN ('.$portfolio.'))');
	if ($financeiro=='empenhado') $sql->adCampo('((tarefa_gastos_empenhado*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor, tarefa_gastos_empenhado AS quantidade');
	elseif ($financeiro=='liquidado') $sql->adCampo('((tarefa_gastos_liquidado*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor, tarefa_gastos_liquidado AS quantidade');
	elseif ($financeiro=='pago') $sql->adCampo('((tarefa_gastos_pago*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor, tarefa_gastos_pago AS quantidade');
	else $sql->adCampo('((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor, tarefa_gastos_quantidade AS quantidade');	

	if ($financeiro=='empenhado') $sql->adOnde('tarefa_gastos_empenhado > 0');
	elseif ($financeiro=='liquidado') $sql->adOnde('tarefa_gastos_liquidado > 0');
	elseif ($financeiro=='pago') $sql->adOnde('tarefa_gastos_pago > 0');
	else $sql->adOnde('tarefa_gastos_quantidade > 0');
	
	$sql->adOnde('tarefa_gastos_custo > 0');
	if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
	
	$sql->adOrdem('tarefa_projeto, tarefa_gastos_tarefa, tarefa_gastos_ordem');
	}
$linhas= $sql->Lista();
$qnt=0;
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>
<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
<th>'.dica('Unidade', 'A unidade de referência para o item.').'Un.'.dicaF().'</th>
<th width="40">'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>'.
($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
<th width="100">'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>'.
($siafi ? '<th>'.dica('NE', 'A nota de empenho relacionada a este item.').'NE'.dicaF().'</th>' : '').
'</tr>';






$total=array();
$custo=array();
$tarefa=null;
$projeto=null;

foreach ($linhas as $linha) {
	
	if ($eh_portfolio && ($projeto!=$linha['tarefa_projeto'])){
			echo '<tr><td align="left" colspan=20><b>'.link_projeto($linha['tarefa_projeto']).'</b></td></tr>';
			$projeto=$linha['tarefa_projeto'];
			$qnt=0;
			}
	
	
	
	
	if ($tipo=='estimado'){
		if ($tarefa!=$linha['tarefa_custos_tarefa']){
			echo '<tr><td align="left" colspan=20>'.link_tarefa($linha['tarefa_custos_tarefa']).'</td></tr>';
			$tarefa=$linha['tarefa_custos_tarefa'];
			$qnt=0;
			}
		if (isset($linha['tarefa_custos_data_inicio'])) $data = new CData($linha['tarefa_custos_data_inicio']);
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['tarefa_custos_nome'].'</td>';
		echo '<td align="left">'.($linha['tarefa_custos_descricao'] ? $linha['tarefa_custos_descricao'] : '&nbsp;').'</td>';
		echo '<td style="white-space: nowrap">'.$unidade[$linha['tarefa_custos_tipo']].'</td>';
		echo '<td align="right" style="white-space: nowrap">'.number_format($linha['quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['tarefa_custos_moeda']].' '.number_format($linha['tarefa_custos_custo'], 2, ',', '.').'</td>';
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['tarefa_custos_bdi'], 2, ',', '.').'</td>';
		
		$nd=($linha['tarefa_custos_categoria_economica'] && $linha['tarefa_custos_grupo_despesa'] && $linha['tarefa_custos_modalidade_aplicacao'] ? $linha['tarefa_custos_categoria_economica'].'.'.$linha['tarefa_custos_grupo_despesa'].'.'.$linha['tarefa_custos_modalidade_aplicacao'].'.' : '').$linha['tarefa_custos_nd'];
		
		echo '<td style="white-space: nowrap">'.$nd.'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['tarefa_custos_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['tarefa_custos_codigo'] ? $linha['tarefa_custos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['tarefa_custos_fonte'] ? $linha['tarefa_custos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['tarefa_custos_regiao'] ? $linha['tarefa_custos_regiao'] : '&nbsp;').'</td>'; 
		
		echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['tarefa_custos_usuario'],'','','esquerda').'</td>';
		echo '</tr>';
		
		
		if (isset($custo[$linha['tarefa_custos_moeda']][$nd])) $custo[$linha['tarefa_custos_moeda']][$nd] += (float)$linha['valor'];
		else $custo[$linha['tarefa_custos_moeda']][$nd]=(float)$linha['valor'];
		
		if (isset($total[$linha['tarefa_custos_moeda']])) $total[$linha['tarefa_custos_moeda']]+=$linha['valor'];
		else $total[$linha['tarefa_custos_moeda']]=$linha['valor']; 
	
		}
	else{
		if ($tarefa!=$linha['tarefa_gastos_tarefa']){
			echo '<tr><td align="left" colspan=20>'.link_tarefa($linha['tarefa_gastos_tarefa']).'</td></tr>';
			$tarefa=$linha['tarefa_gastos_tarefa'];
			$qnt=0;
			}
		if (isset($linha['tarefa_gastos_data_inicio'])) $data = new CData($linha['tarefa_gastos_data_inicio']);
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['tarefa_gastos_nome'].'</td>';
		echo '<td align="left">'.($linha['tarefa_gastos_descricao'] ? $linha['tarefa_gastos_descricao'] : '&nbsp;').'</td>';
		echo '<td style="white-space: nowrap">'.$unidade[$linha['tarefa_gastos_tipo']].'</td>';
		echo '<td align="right" style="white-space: nowrap">'.number_format($linha['quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['tarefa_gastos_moeda']].' '.number_format($linha['tarefa_gastos_custo'], 2, ',', '.').'</td>';
		
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['tarefa_gastos_bdi'], 2, ',', '.').'</td>';
		$nd=($linha['tarefa_gastos_categoria_economica'] && $linha['tarefa_gastos_grupo_despesa'] && $linha['tarefa_gastos_modalidade_aplicacao'] ? $linha['tarefa_gastos_categoria_economica'].'.'.$linha['tarefa_gastos_grupo_despesa'].'.'.$linha['tarefa_gastos_modalidade_aplicacao'].'.' : '').$linha['tarefa_gastos_nd'];
		
		echo '<td style="white-space: nowrap">'.$nd.'</td>';
		echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['tarefa_gastos_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['tarefa_gastos_codigo'] ? $linha['tarefa_gastos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['tarefa_gastos_fonte'] ? $linha['tarefa_gastos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['tarefa_gastos_regiao'] ? $linha['tarefa_gastos_regiao'] : '&nbsp;').'</td>'; 
		
		echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['tarefa_gastos_usuario'],'','','esquerda').'</td>';
		
		
		if ($siafi){
			echo '<td style="white-space: nowrap">';
			$sql->adTabela('financeiro_gasto');
			$sql->esqUnir('financeiro_rel_nelista', 'financeiro_rel_nelista', 'financeiro_rel_nelista_id=financeiro_gasto_relnelista');
			$sql->esqUnir('financeiro_nelista', 'financeiro_nelista', 'financeiro_nelista_id=financeiro_rel_nelista_nelista');
			$sql->esqUnir('financeiro_ne', 'financeiro_ne', 'financeiro_ne_id=financeiro_nelista_ne');
			$sql->adCampo('CONE, financeiro_nelista.NUITEM, financeiro_ne_id');
			$sql->adOnde('financeiro_gasto_tarefagasto='.(int)$linha['tarefa_gastos_id']);
			$nes = $sql->lista();
			$sql->limpar();
			$qnt_ne=0;
			foreach($nes as $ne) echo ($qnt_ne++ ? '<br>' : '').'<a href="javascript: void(0);" onclick="popNE('.$ne['financeiro_ne_id'].')">'.$ne['CONE'].'|'.$ne['NUITEM'].'</a>';
			echo '</td>';
			}
		echo '</tr>';
		
		if (isset($custo[$linha['tarefa_gastos_moeda']][$nd])) $custo[$linha['tarefa_gastos_moeda']][$nd] += (float)$linha['valor'];
		else $custo[$linha['tarefa_gastos_moeda']][$nd]=(float)$linha['valor'];
		
		if (isset($total[$linha['tarefa_gastos_moeda']])) $total[$linha['tarefa_gastos_moeda']]+=$linha['valor'];
		else $total[$linha['tarefa_gastos_moeda']]=$linha['valor']; 
		
		} 
	}

$tem_total=false;
foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
	
if ($tem_total) {
	foreach ($custo as $tipo_moeda => $linha) {
		echo '<tr><td colspan="'.($config['bdi'] ? 7 : 6).'" class="std" align="right">';
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda], 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}	
	}
if (!$qnt) echo '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';

echo '</table></td></tr>';
if ($tipo=='estimado'){
	$sql->adTabela('tarefa_gastos', 'tg');
	$sql->esqUnir('tarefas', 't', 't.tarefa_id = tg.tarefa_gastos_tarefa');
	$sql->esqUnir('projetos', 'p', 't.tarefa_projeto = p.projeto_id');
	$sql->adCampo('sum(tg.tarefa_gastos_custo) as total_gastos');
	$sql->adOnde('p.projeto_id IN ('.$portfolio.')'); 
	
	if ($financeiro=='empenhado') $sql->adOnde('tarefa_gastos_empenhado > 0');
	elseif ($financeiro=='liquidado') $sql->adOnde('tarefa_gastos_liquidado > 0');
	elseif ($financeiro=='pago') $sql->adOnde('tarefa_gastos_pago > 0');
	
	$gasto= $sql->Resultado();
	$sql->limpar();
	}
else {
	$sql->adTabela('tarefa_custos', 'tg');
	$sql->esqUnir('tarefas', 't', 't.tarefa_id = tg.tarefa_custos_tarefa');
	$sql->esqUnir('projetos', 'p', 't.tarefa_projeto = p.projeto_id');
	$sql->adCampo('sum(tg.tarefa_custos_custo) as total_custos');
	$sql->adOnde('p.projeto_id IN ('.$portfolio.')'); 
	$custo= $sql->Resultado();
	$sql->limpar();
	}	
echo '<tr><td><table width="100%"><tr>'.(!$Aplic->profissional ? '<td align="left">'.botao('fechar', 'Fechar','Fechar esta tela.','','window.opener = window; window.close()').'</td>' : '');
$link='';
if (isset($gasto) && $gasto && !$impresso) {
	$link='window.open(\'./index.php?m=projetos&a=planilha&dialogo=1&projeto_id='.$projeto_id.($financeiro ? '&financeiro='.$financeiro : '').'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')';
	echo '<td align="right">'.
	botao('gasto', 'Gastos','Clique para ver a planilha de gastos realizados.','',$link).'</td>';
	}
elseif (isset($custo) && $custo && !$impresso) { 
	$link='window.open(\'./index.php?m=projetos&a=planilha&dialogo=1&projeto_id='.$projeto_id.($financeiro ? '&financeiro='.$financeiro : '').'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')';
	echo '<td align="right">'.
	botao('estimado', 'Custos Estimados','Clique para ver a planilha de custos estimados.','',$link).'</td>';
	}
echo '</tr></table></td></tr>';
echo '</td></tr></table></form>';
if (!$impresso) echo estiloFundoCaixa();
elseif (!($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';	
?>
<script type="text/javascript">

function popNE(financeiro_ne_id){
	window.parent.gpwebApp.popUp("Nota de Empenho", 950, 700, 'm=financeiro&a=siafi_ne_detalhe_pro&dialogo=1&projeto_id='+<?php echo $projeto_id?>+'&financeiro_ne_id='+financeiro_ne_id, null, window);
	}	
</script>	