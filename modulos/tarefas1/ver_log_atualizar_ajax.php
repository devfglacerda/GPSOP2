<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');

$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);	

if ($Aplic->profissional) {
	require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
	require_once BASE_DIR.'/modulos/tarefas/tarefas_projeto_ajax_pro.php';
	}
require_once BASE_DIR.'/modulos/tarefas/funcoes.php';

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

	
function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	

$xajax->registerFunction("mudar_nd_ajax");
	
	
function incluir_custo_ajax(
		$log_id=null,
		$uuid=null,  
		$custo_id=null, 
		$custo_nome=null,
		$custo_tipo=null,
		$custo_quantidade=null,
		$custo_custo=null,
		$custo_descricao=null,
		$custo_nd=null,
		$custo_categoria_economica=null,
		$custo_grupo_despesa=null,
		$custo_modalidade_aplicacao=null,
		$custo_data_limite=null,
		$custo_codigo=null,
		$custo_fonte=null,
		$custo_regiao=null,
		$custo_bdi=null,
		$custo_ptres=null,
		$custo_pi=null,
		$custo_gasto=null,
		$custo_moeda=null,
		$custo_data_moeda=null
		){
			
	global $Aplic;
	
	$custo_cotacao=($custo_moeda > 1 ? cotacao($custo_moeda, $custo_data_moeda) : 1);	

	$custo_nome=previnirXSS(utf8_decode($custo_nome));
	$custo_descricao=previnirXSS(utf8_decode($custo_descricao));
	
	$custo_codigo=previnirXSS(utf8_decode($custo_codigo));
	$custo_fonte=previnirXSS(utf8_decode($custo_fonte));
	$custo_regiao=previnirXSS(utf8_decode($custo_regiao));
	
	$sql = new BDConsulta;

	if ($custo_id){
		$sql->adTabela('custo');
		$sql->adAtualizar('custo_nome', $custo_nome);	
		$sql->adAtualizar('custo_tipo', $custo_tipo);
		$sql->adAtualizar('custo_quantidade', float_americano($custo_quantidade));
		$sql->adAtualizar('custo_custo', float_americano($custo_custo));
		$sql->adAtualizar('custo_descricao', $custo_descricao);
		$sql->adAtualizar('custo_nd', $custo_nd);
		$sql->adAtualizar('custo_categoria_economica', $custo_categoria_economica);
		$sql->adAtualizar('custo_grupo_despesa', $custo_grupo_despesa);
		$sql->adAtualizar('custo_modalidade_aplicacao', $custo_modalidade_aplicacao);
		$sql->adAtualizar('custo_data_limite', $custo_data_limite);
		$sql->adAtualizar('custo_ptres', $custo_ptres);
		$sql->adAtualizar('custo_pi', $custo_pi);
		$sql->adAtualizar('custo_gasto', $custo_gasto);
		$sql->adAtualizar('custo_usuario', $Aplic->usuario_id);
		$sql->adAtualizar('custo_data', date('Y-m-d H:i:s'));
		$sql->adAtualizar('custo_codigo', $custo_codigo);
		$sql->adAtualizar('custo_fonte', $custo_fonte);
		$sql->adAtualizar('custo_regiao', $custo_regiao);
		$sql->adAtualizar('custo_bdi', float_americano($custo_bdi));
		
		$sql->adAtualizar('custo_moeda', $custo_moeda);
		$sql->adAtualizar('custo_data_moeda', $custo_data_moeda);
		$sql->adAtualizar('custo_cotacao', $custo_cotacao);
		
		$sql->adOnde('custo_id ='.(int)$custo_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {	
		$sql->adTabela('custo');
		$sql->adCampo('MAX(custo_ordem)');
		if ($log_id) $sql->adOnde('custo_log ='.(int)$log_id);	
		else $sql->adOnde('custo_uuid =\''.$uuid.'\'');	
		
		if ($custo_gasto) $sql->adOnde('custo_gasto=1');
		else $sql->adOnde('custo_gasto!=1');
		
	  $qnt = (int)$sql->Resultado();
	  $sql->limpar();

		$sql->adTabela('custo');
		if ($log_id) $sql->adInserir('custo_log', $log_id);
		else $sql->adInserir('custo_uuid', $uuid);
		$sql->adInserir('custo_nome', $custo_nome);	
		$sql->adInserir('custo_tipo', $custo_tipo);
		$sql->adInserir('custo_quantidade', float_americano($custo_quantidade));
		$sql->adInserir('custo_custo', float_americano($custo_custo));
		$sql->adInserir('custo_descricao', $custo_descricao);
		$sql->adInserir('custo_nd', $custo_nd);
		$sql->adInserir('custo_categoria_economica', $custo_categoria_economica);
		$sql->adInserir('custo_grupo_despesa', $custo_grupo_despesa);
		$sql->adInserir('custo_modalidade_aplicacao', $custo_modalidade_aplicacao);
		$sql->adInserir('custo_data_limite', $custo_data_limite);
		
		if ($custo_codigo) $sql->adInserir('custo_codigo', $custo_codigo);
		if ($custo_fonte) $sql->adInserir('custo_fonte', $custo_fonte);
		if ($custo_regiao) $sql->adInserir('custo_regiao', $custo_regiao);
		$sql->adInserir('custo_bdi', float_americano($custo_bdi));
		
		$sql->adInserir('custo_moeda', $custo_moeda);
		$sql->adInserir('custo_data_moeda', $custo_data_moeda);
		$sql->adInserir('custo_cotacao', $custo_cotacao);
		
		if ($custo_ptres) $sql->adInserir('custo_ptres', $custo_ptres);
		if ($custo_pi) $sql->adInserir('custo_pi', $custo_pi);
		$sql->adInserir('custo_gasto', $custo_gasto);
		$sql->adInserir('custo_usuario', $Aplic->usuario_id);
		$sql->adInserir('custo_data', date('Y-m-d H:i:s'));
		$sql->adInserir('custo_ordem', ++$qnt);
		$sql->exec();
		}
	$saida=atualizar_custos($log_id, $uuid, $custo_gasto);
	$objResposta = new xajaxResponse();
	$objResposta->assign(($custo_gasto ? "combo_gasto" : "combo_custo"),"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_custo_ajax");

function excluir_custo_ajax($custo_id, $log_id, $uuid, $gasto){
	$sql = new BDConsulta;
	$sql->setExcluir('custo');
	$sql->adOnde('custo_id='.(int)$custo_id);
	$sql->exec();
	$saida=atualizar_custos($log_id, $uuid, $gasto);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_custo_ajax");	

$unidade=getSisValor('TipoUnidade');


function atualizar_custos($log_id=null, $uuid=null, $gasto=0){
	global $config, $unidade, $Aplic, $moedas, $exibir2;

	$sql = new BDConsulta;
	$sql->adTabela('custo');
	$sql->adCampo('custo.*, ((custo_quantidade*custo_custo)*((100+custo_bdi)/100)) AS valor');
	if ($log_id) $sql->adOnde('custo_log ='.(int)$log_id);	
	else $sql->adOnde('custo_uuid =\''.$uuid.'\'');	
	if ($gasto) $sql->adOnde('custo_gasto=1');
	else $sql->adOnde('custo_gasto!=1');
	$sql->adOrdem('custo_ordem');
	$linhas=$sql->Lista();
	$sql->limpar();
	$qnt=0;
	
	$ptres=0;
	$pi=0;
	foreach($linhas as $linha){
		if ($linha['custo_ptres']) $ptres++;
		if ($linha['custo_pi']) $pi++;
		}
	
	$saida='';
	
	$saida.= '<table cellpadding=0 cellspacing=0 class="tbl1">';
	if ($gasto) $saida.= '<tr><th colspan=20>Planilha de Gastos Efetuados</th></tr>';
	else $saida.= '<tr><th colspan=20>Planilha de Custos Estimados</th></tr>';
	$saida.= '<tr><th></th>
	<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de referência para o item.').'Un.'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
	(isset($exibir2['codigo']) && $exibir2['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir2['fonte']) && $exibir2['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir2['regiao']) && $exibir2['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').'
	<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>
	<th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
	($pi ? '<th>'.dica('PI', 'PI do item.').'PI'.dicaF().'</th>' : '').
	($ptres ? '<th>'.dica('PTRES', 'PTRES do item.').'PTRES'.dicaF().'</th>' : '').'
	<th></th></tr>';

	$total=array();
	$custo=array();
	foreach ($linhas as $linha) {
		$saida.= '<tr align="center">';

		$saida.= '<td width="40" align="right">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverPrimeiro\', '.($gasto ? 'true' : 'false').');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaCima\', '.($gasto ? 'true' : 'false').');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaBaixo\', '.($gasto ? 'true' : 'false').');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverUltimo\', '.($gasto ? 'true' : 'false').');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';

		$saida.= '<td align="left">'.++$qnt.' - '.$linha['custo_nome'].'</td>';
		$saida.= '<td align="left">'.($linha['custo_descricao'] ? $linha['custo_descricao'] : '&nbsp;').'</td>';
		$saida.= '<td>'.$unidade[$linha['custo_tipo']].'</td><td>'.number_format($linha['custo_quantidade'], 2, ',', '.').'</td>';
		$saida.= '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['custo_custo'], 2, ',', '.').'</td>';
		if ($config['bdi']) $saida.= '<td align="right">'.number_format($linha['custo_bdi'], 2, ',', '.').'</td>';
		$nd=($linha['custo_categoria_economica'] && $linha['custo_grupo_despesa'] && $linha['custo_modalidade_aplicacao'] ? $linha['custo_categoria_economica'].'.'.$linha['custo_grupo_despesa'].'.'.$linha['custo_modalidade_aplicacao'].'.' : '').$linha['custo_nd'];
		$saida.= '<td>'.$nd.'</td>';
		$saida.= '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
	
		if (isset($exibir2['codigo']) && $exibir2['codigo']) $saida.='<td align="center">'.($linha['custo_codigo'] ? $linha['custo_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir2['fonte']) && $exibir2['fonte']) $saida.='<td align="center">'.($linha['custo_fonte'] ? $linha['custo_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir2['regiao']) && $exibir2['regiao']) $saida.='<td align="center">'.($linha['custo_regiao'] ? $linha['custo_regiao'] : '&nbsp;').'</td>';  
		 
		
		$saida.= '<td align="left" style="white-space: nowrap">'.link_usuario($linha['custo_usuario'],'','','esquerda').'</td>';
		$saida.= '<td>'.($linha['custo_data_limite']? retorna_data($linha['custo_data_limite'],false) : '&nbsp;').'</td>';
		if ($pi) $saida.= '<td align="center">'.$linha['custo_pi'].'</td>';
		if ($ptres) $saida.= '<td align="center">'.$linha['custo_ptres'].'</td>';

		$saida.= '<td width="32">';
		$saida.= dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item.').'<a href="javascript:void(0);" onclick="javascript:editar_custo('.$linha['custo_id'].', '.($gasto ? 'true' : 'false').'	);">'.imagem('icones/editar.gif').'</a>'.dicaF();
		$saida.= dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item.').'<a href="javascript:void(0);" onclick="javascript:excluir_custo('.$linha['custo_id'].', '.($gasto ? 'true' : 'false').');">'.imagem('icones/remover.png').'</a>'.dicaF();
		$saida.= '</td>';	

		$saida.= '</tr>';

		if (isset($custo[$linha['custo_moeda']][$nd])) $custo[$linha['custo_moeda']][$nd] += (float)$linha['valor'];
		else $custo[$linha['custo_moeda']][$nd]=(float)$linha['valor'];
		
		if (isset($total[$linha['custo_moeda']])) $total[$linha['custo_moeda']]+=$linha['valor'];
		else $total[$linha['custo_moeda']]=$linha['valor']; 
		}

	$tem_total=false;
	foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
		
	if ($tem_total) {
		foreach ($custo as $tipo_moeda => $linha) {
			$saida.= '<tr><td colspan="'.($config['bdi'] ? 8 : 7).'" class="std" align="right">';
			foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
			$saida.= '<br><b>Total</td><td align="right">';	
			foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio, 2, ',', '.');
			$saida.= '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda], 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
			}	
		}		
	if (!$qnt) $saida.= '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';			
	$saida.= '</table>';
	return $saida;
	}

function exibir_custos($log_id=null, $uuid=''){
	$saida=atualizar_custos($log_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir_custos");
	
function mudar_posicao_custo($ordem, $custo_id, $direcao, $log_id=0, $uuid='', $gasto=false){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $custo_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('custo');
		$sql->adOnde('custo_id != '.(int)$custo_id);
		if ($log_id) $sql->adOnde('custo_log ='.(int)$log_id);	
		else $sql->adOnde('custo_uuid =\''.$uuid.'\'');	
		if ($gasto) $sql->adOnde('custo_gasto=1');
		else $sql->adOnde('custo_gasto!=1');
		$sql->adOrdem('custo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('custo');
			$sql->adAtualizar('custo_ordem', $novo_ui_ordem);
			$sql->adOnde('custo_id = '.(int)$custo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('custo');
					$sql->adAtualizar('custo_ordem', $idx);
					$sql->adOnde('custo_id = '.(int)$acao['custo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('custo');
					$sql->adAtualizar('custo_ordem', $idx + 1);
					$sql->adOnde('custo_id = '.(int)$acao['custo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_custos($log_id, $uuid, $gasto);
	$objResposta = new xajaxResponse();
	$objResposta->assign(($gasto ? "combo_gasto" : "combo_custo"),"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_custo");	
	
	
	

function editar_custo($custo_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('custo');
	$sql->adCampo('custo.*');
	$sql->adOnde('custo_id = '.(int)$custo_id);
	$linha=$sql->Linha();
	$sql->limpar();
	
	$objResposta->assign("custo_id","value", $custo_id);
	$data = new CData($linha['custo_data_limite']);
	$objResposta->assign("custo_data_limite","value", $data->format('%Y-%m-%d'));
	$objResposta->assign("data_texto","value", $data->format('%d/%m/%Y'));	
	$objResposta->assign("custo_quantidade","value", float_brasileiro($linha['custo_quantidade']));	
	$objResposta->assign("custo_bdi","value", number_format($linha['custo_bdi'], 2, ',', '.'));	
	$objResposta->assign("custo_custo","value", float_brasileiro($linha['custo_custo']));	
	$objResposta->assign("custo_categoria_economica","value", $linha['custo_categoria_economica']);
	$objResposta->assign("custo_grupo_despesa","value", $linha['custo_grupo_despesa']);
	$objResposta->assign("custo_modalidade_aplicacao","value", $linha['custo_modalidade_aplicacao']);
	$nd=vetor_nd($linha['custo_nd'], true, null, 3 ,$linha['custo_categoria_economica'], $linha['custo_grupo_despesa'], $linha['custo_modalidade_aplicacao']);
	$saida=selecionaVetor($nd, 'custo_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', $linha['custo_nd']);
	$objResposta->assign("combo_nd","innerHTML", $saida);
	$objResposta->assign("custo_nome","value", utf8_encode($linha['custo_nome']));
	$objResposta->assign("custo_descricao","value", utf8_encode($linha['custo_descricao']));
	
	$objResposta->assign("custo_pi","value", utf8_encode($linha['custo_pi']));	
	$objResposta->assign("custo_ptres","value", utf8_encode($linha['custo_ptres']));	
	
	$objResposta->assign("custo_codigo","value", utf8_encode($linha['custo_codigo']));
	$objResposta->assign("custo_fonte","value", utf8_encode($linha['custo_fonte']));
	$objResposta->assign("custo_regiao","value", utf8_encode($linha['custo_regiao']));
	
	$objResposta->assign("custo_moeda","value", $linha['custo_moeda']);
	$objResposta->assign("custo_data_moeda","value", $linha['custo_data_moeda']);
	//$objResposta->assign("combo_data_moeda","style.display", ($linha['custo_moeda']==1 ? 'none' : ''));
	$objResposta->assign("data5_texto","value", retorna_data($linha['custo_data_moeda'], false));
	$objResposta->assign("apoio1","value", utf8_encode($linha['custo_descricao']));	
	$objResposta->assign("custo_gasto","value", $linha['custo_gasto']);
	$objResposta->assign("custo_gasto","disabled", true);
	
	$objResposta->assign("total","innerHTML", '<b>'.number_format(($linha['custo_custo']*$linha['custo_quantidade'])*((100+$linha['custo_bdi'])/100), 2, ',', '.').'</b>');
	return $objResposta;
	}	
$xajax->registerFunction("editar_custo");		



function atualizar_arquivo($log_id=0){
    
    $sql = new BDConsulta;
    
    //arquivo anexo
    $sql->adTabela('log_arquivo');
    $sql->adCampo('log_arquivo_id, log_arquivo_usuario, log_arquivo_data, log_arquivo_ordem, log_arquivo_nome, log_arquivo_endereco');
    $sql->adOnde('log_arquivo_log='.(int)$log_id);
    $sql->adOrdem('log_arquivo_ordem ASC');
    $arquivos=$sql->Lista();
    $sql->limpar();
    $saida='<table cellspacing=0 cellpadding=0>';
    
    if ($arquivos && count($arquivos)) $saida.='<tr><td colspan=2>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</td></tr>';
    foreach ($arquivos as $arquivo) {
        $saida.= '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
        $saida.= '<td style="white-space: nowrap" width="40" align="center">';
        $saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
        $saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
        $saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
        $saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
        $saida.= '</td>';
        $saida.= '<td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=praticas&a=log_download&sem_cabecalho=1&log_arquivo_id='.$arquivo['log_arquivo_id'].'\');">'.$arquivo['log_arquivo_nome'].'</a></td>';
        $saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_arquivo('.$arquivo['log_arquivo_id'].');}">'.imagem('icones/remover.png').'</a></td>';
        $saida.= '</tr></table></td></tr>';
        }
    $saida.='</table>';
    return $saida;
    }

function mudar_posicao_arquivo($ordem, $log_arquivo_id, $direcao, $log_id=0){
    //ordenar membro da equipe
    $sql = new BDConsulta;
    if($direcao && $log_arquivo_id) {
        $novo_ui_ordem = $ordem;
        $sql->adTabela('log_arquivo');
        $sql->adOnde('log_arquivo_id != '.(int)$log_arquivo_id);
        $sql->adOnde('log_arquivo_log = '.(int)$log_id);
        $sql->adOrdem('log_arquivo_ordem');
        $membros = $sql->Lista();
        $sql->limpar();
        
        if ($direcao == 'moverParaCima') {
            $outro_novo = $novo_ui_ordem;
            $novo_ui_ordem--;
            } 
        elseif ($direcao == 'moverParaBaixo') {
            $outro_novo = $novo_ui_ordem;
            $novo_ui_ordem++;
            } 
        elseif ($direcao == 'moverPrimeiro') {
            $outro_novo = $novo_ui_ordem;
            $novo_ui_ordem = 1;
            } 
        elseif ($direcao == 'moverUltimo') {
            $outro_novo = $novo_ui_ordem;
            $novo_ui_ordem = count($membros) + 1;
            }
        if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
            $sql->adTabela('log_arquivo');
            $sql->adAtualizar('log_arquivo_ordem', $novo_ui_ordem);
            $sql->adOnde('log_arquivo_id = '.(int)$log_arquivo_id);
            $sql->exec();
            $sql->limpar();
            $idx = 1;
            foreach ($membros as $acao) {
                if ((int)$idx != (int)$novo_ui_ordem) {
                    $sql->adTabela('log_arquivo');
                    $sql->adAtualizar('log_arquivo_ordem', $idx);
                    $sql->adOnde('log_arquivo_id = '.(int)$acao['log_arquivo_id']);
                    $sql->exec();
                    $sql->limpar();
                    $idx++;
                    } 
                else {
                    $sql->adTabela('log_arquivo');
                    $sql->adAtualizar('log_arquivo_ordem', $idx + 1);
                    $sql->adOnde('log_arquivo_id = '.(int)$acao['log_arquivo_id']);
                    $sql->exec();
                    $sql->limpar();
                    $idx = $idx + 2;
                    }
                }        
            }
        }
    
    $saida=atualizar_arquivo($log_id);
    $objResposta = new xajaxResponse();
    $objResposta->assign("combo_arquivos","innerHTML", utf8_encode($saida));
    return $objResposta;
    }
$xajax->registerFunction("mudar_posicao_arquivo");

function excluir_arquivo($log_arquivo_id=0, $log_id=0){    
    global $config;
    
    $sql = new BDConsulta;
    
    
    $sql->adTabela('log_arquivo');
    $sql->adCampo('log_arquivo_endereco, log_arquivo_local, log_arquivo_nome_real');
    $sql->adOnde('log_arquivo_id='.(int)$log_arquivo_id);
    $arquivos=$sql->Lista();
    $sql->limpar();
    
    $base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
    
    foreach($arquivos as $chave => $arquivo){
			if ($arquivo['log_arquivo_local']) @unlink($base_dir.'/arquivos/'.$arquivo['log_arquivo_local'].$arquivo['log_arquivo_nome_real']);
			else @unlink($base_dir.'/arquivos/log/'.$arquivo['log_arquivo_endereco']);
			}

    
    
    $sql->setExcluir('log_arquivo');
    $sql->adOnde('log_arquivo_id='.(int)$log_arquivo_id);
    $sql->exec();
    
    $saida=atualizar_arquivo($log_id);
    $objResposta = new xajaxResponse();
    $objResposta->assign("combo_arquivos","innerHTML", utf8_encode($saida));
    return $objResposta;
    }    
$xajax->registerFunction("excluir_arquivo");
 

function calcular_duracao($inicio, $fim, $cia_id){
    global $config, $Aplic, $projeto_id, $tarefa_id;
    
    if ($Aplic->profissional){
    	$cache = CTarefaCache::getInstance();
  		$horas = $cache->horasPeriodo($inicio, $fim, $cia_id, $projeto_id, $tarefa_id);
  		}
  	else {
  		$horas = horas_periodo($inicio, $fim, $cia_id, null, $projeto_id, null, $tarefa_id);
  		}	
  
    $objResposta = new xajaxResponse();
    $resultado=(float)$horas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
    $objResposta->assign("tarefa_duracao","value", float_brasileiro($resultado));
    return $objResposta;
    }
$xajax->registerFunction("calcular_duracao");    

function data_final_periodo($inicio, $dias, $cia_id){
         global $Aplic, $projeto_id, $tarefa_id;
        
        
        $horas=abs(float_americano($dias)*config('horas_trab_diario'));
        
       
       
        if ($Aplic->profissional){
        	$cache = CTarefaCache::getInstance();
       	 	$data_final = $cache->deslocaDataPraFrente($inicio, $horas, $cia_id, $projeto_id, $tarefa_id);
        	$data_final = $cache->ajustaInicioPeriodo($data_final, $cia_id, $projeto_id, $tarefa_id);
        	}
        else{
        	$data_final = calculo_data_final_periodo($inicio, $horas, $cia_id, null, $projeto_id, null, $tarefa_id);
     			}	
        
        
        $data=new CData($data_final);
        $objResposta = new xajaxResponse();
        $objResposta->assign("oculto_data_fim","value", $data->format("%Y-%m-%d"));
        $objResposta->assign("data_fim","value", $data->format("%d/%m/%Y"));
        $objResposta->assign("hora_fim","value", $data->format("%H"));
        $objResposta->assign("minuto_fim","value", $data->format("%M"));
        return $objResposta;
        }    
$xajax->registerFunction("data_final_periodo");    




$xajax->processRequest();

?>
