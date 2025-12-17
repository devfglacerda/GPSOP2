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



function incluir_custo(
  $recurso_tarefa_id=0,
	$recurso_tarefa_custo_id=0,
	$recurso_tarefa_custo_tipo='',
	$recurso_tarefa_custo_nome='',
	$recurso_tarefa_custo_data_limite='',
	$recurso_tarefa_custo_quantidade='',
	$recurso_tarefa_custo_valor='',
	$recurso_tarefa_custo_descricao='',
	$recurso_tarefa_custo_nd='',
	$recurso_tarefa_custo_categoria_economica='',
	$recurso_tarefa_custo_grupo_despesa='',
	$recurso_tarefa_custo_modalidade_aplicacao='',
	$recurso_tarefa_custo_codigo=null,
	$recurso_tarefa_custo_fonte=null,
	$recurso_tarefa_custo_regiao=null,
	$recurso_tarefa_custo_bdi=null,
	$recurso_tarefa_custo_moeda=null,
	$recurso_tarefa_custo_data_moeda=null
	){

	global $Aplic;

	$recurso_tarefa_custo_cotacao=($recurso_tarefa_custo_moeda > 1 ? cotacao($recurso_tarefa_custo_moeda, $recurso_tarefa_custo_data_moeda) : 1);	

	$sql = new BDConsulta;
	$recurso_tarefa_custo_nome=previnirXSS(utf8_decode($recurso_tarefa_custo_nome));
	$recurso_tarefa_custo_descricao=previnirXSS(utf8_decode($recurso_tarefa_custo_descricao));
	$recurso_tarefa_custo_categoria_economica=previnirXSS(utf8_decode($recurso_tarefa_custo_categoria_economica));
	$recurso_tarefa_custo_grupo_despesa=previnirXSS(utf8_decode($recurso_tarefa_custo_grupo_despesa));
	$recurso_tarefa_custo_modalidade_aplicacao=previnirXSS(utf8_decode($recurso_tarefa_custo_modalidade_aplicacao));
	
	$recurso_tarefa_custo_codigo=previnirXSS(utf8_decode($recurso_tarefa_custo_codigo));
	$recurso_tarefa_custo_fonte=previnirXSS(utf8_decode($recurso_tarefa_custo_fonte));
	$recurso_tarefa_custo_regiao=previnirXSS(utf8_decode($recurso_tarefa_custo_regiao));
	
	//verificar se já existe
	$sql->adTabela('recurso_tarefa_custo');
	$sql->adCampo('count(recurso_tarefa_custo_id)');
	$sql->adOnde('recurso_tarefa_custo_id ='.(int)$recurso_tarefa_custo_id);
  $ja_existe = (int)$sql->Resultado();
  $sql->limpar();
	if ($ja_existe){
		$sql->adTabela('recurso_tarefa_custo');
		$sql->adAtualizar('recurso_tarefa_custo_usuario', $Aplic->usuario_id);
		$sql->adAtualizar('recurso_tarefa_custo_tipo', $recurso_tarefa_custo_tipo);
		$sql->adAtualizar('recurso_tarefa_custo_nome', $recurso_tarefa_custo_nome);
		$sql->adAtualizar('recurso_tarefa_custo_data_limite', $recurso_tarefa_custo_data_limite);
		$sql->adAtualizar('recurso_tarefa_custo_quantidade', float_americano($recurso_tarefa_custo_quantidade));
		$sql->adAtualizar('recurso_tarefa_custo_valor', float_americano($recurso_tarefa_custo_valor));
		$sql->adAtualizar('recurso_tarefa_custo_descricao', $recurso_tarefa_custo_descricao);
		$sql->adAtualizar('recurso_tarefa_custo_nd', $recurso_tarefa_custo_nd);
		$sql->adAtualizar('recurso_tarefa_custo_categoria_economica', $recurso_tarefa_custo_categoria_economica);
		$sql->adAtualizar('recurso_tarefa_custo_descricao', $recurso_tarefa_custo_descricao);
		$sql->adAtualizar('recurso_tarefa_custo_grupo_despesa', $recurso_tarefa_custo_grupo_despesa);
		$sql->adAtualizar('recurso_tarefa_custo_modalidade_aplicacao', $recurso_tarefa_custo_modalidade_aplicacao);
		$sql->adAtualizar('recurso_tarefa_custo_data', date("Y-m-d H:i:s"));
		$sql->adAtualizar('recurso_tarefa_custo_codigo', $recurso_tarefa_custo_codigo);
		$sql->adAtualizar('recurso_tarefa_custo_fonte', $recurso_tarefa_custo_fonte);
		$sql->adAtualizar('recurso_tarefa_custo_regiao', $recurso_tarefa_custo_regiao);
		$sql->adAtualizar('recurso_tarefa_custo_bdi', float_americano($recurso_tarefa_custo_bdi));
		$sql->adAtualizar('recurso_tarefa_custo_moeda', $recurso_tarefa_custo_moeda);
		$sql->adAtualizar('recurso_tarefa_custo_data_moeda', $recurso_tarefa_custo_data_moeda);
		$sql->adAtualizar('recurso_tarefa_custo_cotacao', $recurso_tarefa_custo_cotacao);
		
		$sql->adOnde('recurso_tarefa_custo_id ='.$recurso_tarefa_custo_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {
		$sql->adTabela('recurso_tarefa_custo');
		$sql->adCampo('count(recurso_tarefa_custo_id) AS soma');
		$sql->adOnde('recurso_tarefa_custo_recurso_tarefa ='.$recurso_tarefa_id);
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
		$sql->adTabela('recurso_tarefa_custo');
		$sql->adInserir('recurso_tarefa_custo_usuario', $Aplic->usuario_id);
		$sql->adInserir('recurso_tarefa_custo_recurso_tarefa', $recurso_tarefa_id);
		$sql->adInserir('recurso_tarefa_custo_ordem', $soma_total);
		$sql->adInserir('recurso_tarefa_custo_tipo', $recurso_tarefa_custo_tipo);
		$sql->adInserir('recurso_tarefa_custo_nome', $recurso_tarefa_custo_nome);
		$sql->adInserir('recurso_tarefa_custo_data_limite', $recurso_tarefa_custo_data_limite);
		$sql->adInserir('recurso_tarefa_custo_quantidade', float_americano($recurso_tarefa_custo_quantidade));
		$sql->adInserir('recurso_tarefa_custo_valor', float_americano($recurso_tarefa_custo_valor));
		$sql->adInserir('recurso_tarefa_custo_descricao', $recurso_tarefa_custo_descricao);
		$sql->adInserir('recurso_tarefa_custo_nd', $recurso_tarefa_custo_nd);
		$sql->adInserir('recurso_tarefa_custo_categoria_economica', $recurso_tarefa_custo_categoria_economica);
		$sql->adInserir('recurso_tarefa_custo_descricao', $recurso_tarefa_custo_descricao);
		$sql->adInserir('recurso_tarefa_custo_grupo_despesa', $recurso_tarefa_custo_grupo_despesa);
		$sql->adInserir('recurso_tarefa_custo_modalidade_aplicacao', $recurso_tarefa_custo_modalidade_aplicacao);
		$sql->adInserir('recurso_tarefa_custo_data', date("Y-m-d H:i:s"));
		if ($recurso_tarefa_custo_codigo) $sql->adInserir('recurso_tarefa_custo_codigo', $recurso_tarefa_custo_codigo);
		if ($recurso_tarefa_custo_fonte) $sql->adInserir('recurso_tarefa_custo_fonte', $recurso_tarefa_custo_fonte);
		if ($recurso_tarefa_custo_regiao) $sql->adInserir('recurso_tarefa_custo_regiao', $recurso_tarefa_custo_regiao);
		if ($recurso_tarefa_custo_bdi) $sql->adInserir('recurso_tarefa_custo_bdi', float_americano($recurso_tarefa_custo_bdi));
		$sql->adInserir('recurso_tarefa_custo_moeda', $recurso_tarefa_custo_moeda);
		$sql->adInserir('recurso_tarefa_custo_data_moeda', $recurso_tarefa_custo_data_moeda);
		$sql->adInserir('recurso_tarefa_custo_cotacao', $recurso_tarefa_custo_cotacao);
		$sql->exec();
		$sql->limpar();
		}
	$saida=atualizar_custos($recurso_tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_custo");




function excluir_custo($recurso_tarefa_custo_id, $recurso_tarefa_id){
	$sql = new BDConsulta;
	$sql->setExcluir('recurso_tarefa_custo');
	$sql->adOnde('recurso_tarefa_custo_id='.$recurso_tarefa_custo_id);
	$sql->exec();
	$saida=atualizar_custos($recurso_tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_custo");

function atualizar_custos($recurso_tarefa_id){
	global $config, $moedas, $exibir;
	$sql = new BDConsulta;

	$sql->adTabela('recurso_tarefa_custo');
	$sql->adOnde('recurso_tarefa_custo_recurso_tarefa = '.(int)$recurso_tarefa_id);
	$sql->adCampo('recurso_tarefa_custo.*, ((recurso_tarefa_custo_quantidade*recurso_tarefa_custo_valor)*((100+recurso_tarefa_custo_bdi)/100)) AS valor');
	$sql->adOrdem('recurso_tarefa_custo_ordem');
	$gastos=$sql->ListaChave('recurso_tarefa_custo_id');
	$sql->limpar();
	
	$saida='';
	$qnt=0;
	$total=array();
	$custo=array();
	$unidade=getSisValor('TipoUnidade');
	if (count($gastos)) {
	$saida.= '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="tbl1"><tr><th></th>
	<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de referência para o item.').'Un.'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
	(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
	'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>
	<th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
	'<th></th></tr>';
		foreach ($gastos as $recurso_tarefa_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td style="white-space: nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.$linha['recurso_tarefa_custo_ordem'].', '.$linha['recurso_tarefa_custo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.$linha['recurso_tarefa_custo_ordem'].', '.$linha['recurso_tarefa_custo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.$linha['recurso_tarefa_custo_ordem'].', '.$linha['recurso_tarefa_custo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.$linha['recurso_tarefa_custo_ordem'].', '.$linha['recurso_tarefa_custo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left">'.++$qnt.' - '.$linha['recurso_tarefa_custo_nome'].'</td>';
			$saida.= '<td align="left">'.($linha['recurso_tarefa_custo_descricao'] ? $linha['recurso_tarefa_custo_descricao'] : '&nbsp;').'</td>';
			$saida.= '<td>'.(isset($unidade[$linha['recurso_tarefa_custo_tipo']]) ? $unidade[$linha['recurso_tarefa_custo_tipo']] : '').'</td>';
			$saida.='<td>'.number_format($linha['recurso_tarefa_custo_quantidade'], 2, ',', '.').'</td>';
			$saida.= '<td align="right">'.$moedas[$linha['recurso_tarefa_custo_moeda']].' '.number_format($linha['recurso_tarefa_custo_valor'], 2, ',', '.').'</td>';
			if ($config['bdi']) $saida.= '<td align="right">'.number_format($linha['recurso_tarefa_custo_bdi'], 2, ',', '.').'</td>';
			$nd=($linha['recurso_tarefa_custo_categoria_economica'] && $linha['recurso_tarefa_custo_grupo_despesa'] && $linha['recurso_tarefa_custo_modalidade_aplicacao'] ? $linha['recurso_tarefa_custo_categoria_economica'].'.'.$linha['recurso_tarefa_custo_grupo_despesa'].'.'.$linha['recurso_tarefa_custo_modalidade_aplicacao'].'.' : '').$linha['recurso_tarefa_custo_nd'];
			$saida.= '<td>'.$nd.'</td>';
			$saida.= '<td align="right">'.$moedas[$linha['recurso_tarefa_custo_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
			
			if (isset($exibir['codigo']) && $exibir['codigo']) $saida.='<td align="center">'.($linha['recurso_tarefa_custo_codigo'] ? $linha['recurso_tarefa_custo_codigo'] : '&nbsp;').'</td>';
			if (isset($exibir['fonte']) && $exibir['fonte']) $saida.='<td align="center">'.($linha['recurso_tarefa_custo_fonte'] ? $linha['recurso_tarefa_custo_fonte'] : '&nbsp;').'</td>';
			if (isset($exibir['regiao']) && $exibir['regiao']) $saida.='<td align="center">'.($linha['recurso_tarefa_custo_regiao'] ? $linha['recurso_tarefa_custo_regiao'] : '&nbsp;').'</td>'; 
			
			$saida.= '<td>'.link_usuario($linha['recurso_tarefa_custo_usuario'],'','','esquerda').'</td>';
				
			$saida.= '<td>'.($linha['recurso_tarefa_custo_data_limite']? retorna_data($linha['recurso_tarefa_custo_data_limite'],false) : '&nbsp;').'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_custo('.$linha['recurso_tarefa_custo_id'].');">'.imagem('icones/editar.gif').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_custo('.$linha['recurso_tarefa_custo_id'].');}">'.imagem('icones/remover.png').'</a></td>';
			$saida.= '</tr>';
			
			if (isset($custo[$linha['recurso_tarefa_custo_moeda']][$nd])) $custo[$linha['recurso_tarefa_custo_moeda']][$nd] += (float)$linha['valor'];
			else $custo[$linha['recurso_tarefa_custo_moeda']][$nd]=(float)$linha['valor'];
			
			if (isset($total[$linha['recurso_tarefa_custo_moeda']])) $total[$linha['recurso_tarefa_custo_moeda']]+=$linha['valor'];
			else $total[$linha['recurso_tarefa_custo_moeda']]=$linha['valor']; 
			
			}
			
		$tem_total=false;
		foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
			
		if ($tem_total) {
			foreach ($custo as $tipo_moeda => $linha) {
				$saida.= '<tr><td colspan="'.($config['bdi'] ? 7 : 6).'" class="std" align="right">';
				foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
				$saida.= '<br><b>Total</td><td align="right">';	
				foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio, 2, ',', '.');
				$saida.= '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda], 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
				}	
			}		
		if (!$qnt) $saida.= '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';				
				
			
		$saida.= '</table></td></tr></table>';
		}
	return $saida;
	}
$xajax->registerFunction("atualizar_custos");


function mudar_posicao_custo($recurso_tarefa_custo_ordem, $recurso_tarefa_custo_id, $direcao, $recurso_tarefa_id=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$recurso_tarefa_id) {
		$novo_ui_recurso_tarefa_custo_ordem = $recurso_tarefa_custo_ordem;
		$sql->adTabela('recurso_tarefa_custo');
		$sql->adOnde('recurso_tarefa_custo_id != '.$recurso_tarefa_custo_id);
		$sql->adOnde('recurso_tarefa_custo_recurso_tarefa = '.$recurso_tarefa_id);
		$sql->adOrdem('recurso_tarefa_custo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();

		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_recurso_tarefa_custo_ordem;
			$novo_ui_recurso_tarefa_custo_ordem--;
			}
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_recurso_tarefa_custo_ordem;
			$novo_ui_recurso_tarefa_custo_ordem++;
			}
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_recurso_tarefa_custo_ordem;
			$novo_ui_recurso_tarefa_custo_ordem = 1;
			}
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_recurso_tarefa_custo_ordem;
			$novo_ui_recurso_tarefa_custo_ordem = count($membros) + 1;
			}
		if ($novo_ui_recurso_tarefa_custo_ordem && ($novo_ui_recurso_tarefa_custo_ordem <= count($membros) + 1)) {
			$sql->adTabela('recurso_tarefa_custo');
			$sql->adAtualizar('recurso_tarefa_custo_ordem', $novo_ui_recurso_tarefa_custo_ordem);
			$sql->adOnde('recurso_tarefa_custo_id = '.$recurso_tarefa_custo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_recurso_tarefa_custo_ordem) {
					$sql->adTabela('recurso_tarefa_custo');
					$sql->adAtualizar('recurso_tarefa_custo_ordem', $idx);
					$sql->adOnde('recurso_tarefa_custo_id = '.$acao['recurso_tarefa_custo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('recurso_tarefa_custo');
					$sql->adAtualizar('recurso_tarefa_custo_ordem', $idx + 1);
					$sql->adOnde('recurso_tarefa_custo_id = '.$acao['recurso_tarefa_custo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}

	$saida=atualizar_custos($recurso_tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_custo");





function exibir_custo($recurso_tarefa_id){
	$saida=atualizar_custos($recurso_tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_custos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir_custo");

function editar_custo($recurso_tarefa_id){
	global $config;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('recurso_tarefa_custo');
	$sql->adCampo('recurso_tarefa_custo.*');
	$sql->adOnde('recurso_tarefa_custo_id = '.(int)$recurso_tarefa_id);
	$linha=$sql->Linha();
	$sql->limpar();
	$saida='';

	$objResposta->assign("recurso_tarefa_custo_id","value", $recurso_tarefa_id);
	$objResposta->assign("recurso_tarefa_custo_nome","value", utf8_encode($linha['recurso_tarefa_custo_nome']));
	$objResposta->assign("recurso_tarefa_custo_tipo","value", utf8_encode($linha['recurso_tarefa_custo_tipo']));
	$objResposta->assign("data_texto","value", retorna_data($linha['recurso_tarefa_custo_data_limite'], false));
	$objResposta->assign("recurso_tarefa_custo_data_limite","value", $linha['recurso_tarefa_custo_data_limite']);
	$objResposta->assign("recurso_tarefa_custo_quantidade","value", ($linha['recurso_tarefa_custo_quantidade'] ? float_brasileiro($linha['recurso_tarefa_custo_quantidade']) : ''));
	$objResposta->assign("recurso_tarefa_custo_valor","value", ($linha['recurso_tarefa_custo_valor'] ? float_brasileiro($linha['recurso_tarefa_custo_valor']) : ''));
	$objResposta->assign("recurso_tarefa_custo_nd","value", utf8_encode($linha['recurso_tarefa_custo_nd']));
	$objResposta->assign("recurso_tarefa_custo_categoria_economica","value", utf8_encode($linha['recurso_tarefa_custo_categoria_economica']));
	$objResposta->assign("recurso_tarefa_custo_grupo_despesa","value", utf8_encode($linha['recurso_tarefa_custo_grupo_despesa']));
	$objResposta->assign("recurso_tarefa_custo_modalidade_aplicacao","value", utf8_encode($linha['recurso_tarefa_custo_modalidade_aplicacao']));
	$objResposta->assign("recurso_tarefa_custo_codigo","value", utf8_encode($linha['recurso_tarefa_custo_codigo']));
	$objResposta->assign("recurso_tarefa_custo_fonte","value", utf8_encode($linha['recurso_tarefa_custo_fonte']));
	$objResposta->assign("recurso_tarefa_custo_regiao","value", utf8_encode($linha['recurso_tarefa_custo_regiao']));
	$objResposta->assign("recurso_tarefa_custo_bdi","value", ($linha['recurso_tarefa_custo_bdi'] ? number_format($linha['recurso_tarefa_custo_bdi'], 2, ',', '.') : ''));
	$objResposta->assign("recurso_tarefa_custo_moeda","value", $linha['recurso_tarefa_custo_moeda']);
	$objResposta->assign("recurso_tarefa_custo_data_moeda","value", $linha['recurso_tarefa_custo_data_moeda']);
	$objResposta->assign("data6_texto","value", retorna_data($linha['recurso_tarefa_custo_data_moeda'], false));
	$objResposta->assign("texto_apoio_custo_descricao","value", utf8_encode($linha['recurso_tarefa_custo_descricao']));	
	$objResposta->assign("total","innerHTML", '<b>'.$config["simbolo_moeda"].number_format(($linha['recurso_tarefa_custo_valor']*$linha['recurso_tarefa_custo_quantidade'])*((100+$linha['recurso_tarefa_custo_bdi'])/100), 2, ',', '.').'</b>');
	
	$nd=vetor_nd($linha['recurso_tarefa_custo_nd'], true, null, 3 ,$linha['recurso_tarefa_custo_categoria_economica'], $linha['recurso_tarefa_custo_grupo_despesa'], $linha['recurso_tarefa_custo_modalidade_aplicacao']);
	$saida=selecionaVetor($nd, 'recurso_tarefa_custo_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', $linha['recurso_tarefa_custo_nd']);
	$objResposta->assign("combo_nd","innerHTML", $saida);
	

	return $objResposta;
	}
$xajax->registerFunction("editar_custo");
























function mudar_posicao_recurso($ordem, $recurso_tarefa_id, $direcao, $tarefa_id=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$recurso_tarefa_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('recurso_tarefa');
		$sql->adOnde('recurso_tarefa_id !='.(int)$recurso_tarefa_id);
		$sql->adOnde('recurso_tarefa_tarefa ='.(int)$tarefa_id);
		$sql->adOrdem('recurso_tarefa_ordem');
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
			$sql->adTabela('recurso_tarefa');
			$sql->adAtualizar('recurso_tarefa_ordem', $novo_ui_ordem);
			$sql->adOnde('recurso_tarefa_id='.(int)$recurso_tarefa_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('recurso_tarefa');
					$sql->adAtualizar('recurso_tarefa_ordem', $idx);
					$sql->adOnde('recurso_tarefa_id='.(int)$acao['recurso_tarefa_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('recurso_tarefa');
					$sql->adAtualizar('recurso_tarefa_ordem', $idx + 1);
					$sql->adOnde('recurso_tarefa_id='.(int)$acao['recurso_tarefa_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_recurso($tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("lista_recursos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_recurso");	


function excluir_recurso($recurso_tarefa_id, $tarefa_id){
	$sql = new BDConsulta;
	$sql->setExcluir('recurso_tarefa');
	$sql->adOnde('recurso_tarefa_id='.(int)$recurso_tarefa_id);
	$sql->exec();
	$saida=atualizar_recurso($tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("lista_recursos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_recurso");	

function atualizar_recurso($tarefa_id=0){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('recurso_tarefa');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id=recurso_tarefa_recurso');
	$sql->adOnde('recurso_tarefa_tarefa = '.(int)$tarefa_id);
	$sql->adCampo('recurso_tarefa_id, recursos.recurso_id, recurso_tipo, recurso_nome, recurso_tarefa_ordem, recurso_tarefa_id, recurso_tarefa_obs, recurso_tarefa_quantidade, recurso_tarefa_percentual, recurso_tarefa_valor_hora, recurso_tarefa_custo, formatar_data(recurso_tarefa_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(recurso_tarefa_fim, \'%d/%m/%Y %H:%i\') AS fim, recurso_tarefa_duracao, recurso_tarefa_aprovou, formatar_data(recurso_tarefa_data, \'%d/%m/%Y %H:%i\') AS data_aprovou');
	$sql->adOrdem('recurso_tarefa_ordem');
	$recurso=$sql->ListaChave('recurso_tarefa_id');
	$sql->limpar();
	$saida='';
	if (count($recurso)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left width=100%><tr>
		<th></th>
		<th>'.dica('Recurso', 'O nome do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Recurso'.dicaF().'</th>
		<th>'.dica('Início', 'A data de início de alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Início'.dicaF().'</th>
		<th>'.dica('Término', 'A data de término de alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Término'.dicaF().'</th>
		<th>'.dica('Horas', 'Total de horas úteis na alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Horas'.dicaF().'</th>
		<th>'.dica('Quantidade', 'A quantidade do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Qnt.'.dicaF().'</th>
		<th>'.dica('Percentagem', 'A percentagem de uso do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'%'.dicaF().'</th>
		<th>'.dica('Valor hora', 'O valor da hora de alocação do recurso.').'Valor/hora'.dicaF().'</th>
		<th>'.dica('Valor Unitário', 'O valor unitário do recurso.').'Valor/unit.'.dicaF().'</th>
		<th>'.dica('Aprovado', 'Se a alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].' se encontra aprovado pelo responsável pelo recurso.').'Aprov.'.dicaF().'</th>
		<th>'.dica('Data da Aprovação', 'A data em que alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].' foi provada pelo responsável pelo recurso.').'DA'.dicaF().'</th>
		<th>'.dica('Observação', 'Observação sobre o recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Obs.'.dicaF().'</th>
		<th></th></tr>';
		foreach ($recurso as $recurso_tarefa_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td style="white-space: nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left">'.($linha['recurso_tipo']< 4 ? '<a href="javascript:void(0);" onclick="ver_gastos('.$linha['recurso_tarefa_id'].')">' : '').$linha['recurso_nome'].($linha['recurso_tipo'] < 4 ? '</a>' : '').'</td>';
			$saida.= '<td align=center style="white-space: nowrap" width=110>'.($linha['recurso_tipo']!=5 ? $linha['inicio'] : '').'</td>';
			$saida.= '<td align=center style="white-space: nowrap" width=110>'.($linha['recurso_tipo']!=5 ? $linha['fim'] : '').'</td>';
			$saida.= '<td align=right style="white-space: nowrap" width=50>'.($linha['recurso_tipo']!=5 ? number_format($linha['recurso_tarefa_duracao'], 2, ',', '.') : '').'</td>';
	    $saida.= '<td align=right style="white-space: nowrap" width=50>'.number_format($linha['recurso_tarefa_quantidade'], 2, ',', '.').'</td>';
	    $saida.= '<td align=right style="white-space: nowrap" width=30>'.($linha['recurso_tipo'] < 4 ? $linha['recurso_tarefa_percentual'] : '').'</td>';
			$saida.= '<td align=right style="white-space: nowrap" width=70>'.($linha['recurso_tipo'] < 4 ? number_format($linha['recurso_tarefa_valor_hora'], 2, ',', '.') : '').'</td>';
			$saida.= '<td align=right style="white-space: nowrap" width=70>'.($linha['recurso_tipo']==4 ? number_format($linha['recurso_tarefa_custo'], 2, ',', '.') : '').'</td>';
			$saida.= '<td align=center style="white-space: nowrap" width=25>'.($linha['recurso_tarefa_aprovou'] ? 'Sim' : 'Não').'</td>';
			$saida.= '<td style="white-space: nowrap" width=25>'.($linha['data_aprovou'] ? $linha['data_aprovou'] : '&nbsp;').'</td>';
			$saida.= '<td>'.($linha['recurso_tarefa_obs'] ? $linha['recurso_tarefa_obs'] : '&nbsp;').'</td>';
			$saida.= '<td style="white-space: nowrap" width=16>'.($linha['recurso_tarefa_aprovou']!=1 ? '<a href="javascript: void(0);" onclick="editar_trabalho('.$linha['recurso_tarefa_id'].');">'.imagem('icones/editar.gif').'</a><a href="javascript: void(0);" onclick="if (confirm(\''.'Tem certeza que deseja excluir este período trabalhado?'.'\')) {excluir_trabalho('.$linha['recurso_tarefa_id'].');}">'.imagem('icones/remover.png').'</a>' : '').'</td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}

	return $saida;
	}

function editar_recurso($recurso_tarefa_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('recurso_tarefa');
	$sql->adCampo('recurso_tarefa_recurso');
	$sql->adOnde('recurso_tarefa_id = '.(int)$recurso_tarefa_id);
	$recurso_id=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("recurso_tarefa_id","value", $recurso_tarefa_id);
	$objResposta->assign("recurso_id","value", utf8_encode($recurso_id));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_recurso");		



	
function recurso_tipo($recurso_id){
	$sql = new BDConsulta;
	$sql->adTabela('recursos');
	$sql->adCampo('recurso_tipo');
	$sql->adOnde('recurso_id='.(int)$recurso_id);
	$recurso_tipo = $sql->Resultado();
	$sql->limpar();
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("recurso_tipo","value", $recurso_tipo);
	return $objResposta;
	}	
$xajax->registerFunction("recurso_tipo");	
		
		
		
		
		
		
function detalhes_recurso($recurso_id=null, $tarefa_id=null, $posicao){
	global $Aplic, $config;
	$sql = new BDConsulta;
	$tipo=getSisValor('TipoRecurso');

	$sql->adTabela('recursos');
	$sql->esqUnir('sisvalores','sisvalores','sisvalores.sisvalor_valor_id=recursos.recurso_nd');
	$sql->adCampo('sisvalor_valor AS nd');
	$sql->adCampo('recursos.*');
	$sql->adOnde('recurso_id = '.(int)$recurso_id);
	$linha = $sql->Linha();	
	$sql->limpar();
	
	$saida = '<table cellspacing="4" cellpadding="2" border=0>';
	if ($linha['recurso_tipo']==5){
		$saida .= '<tr><td colspan=20><table class="tbl1" cellpadding=0 cellspacing=1>';
		$saida .= '<tr><th width="60">EVENTO</th><th>ESF</th><th width="60">PTRES</th><th width="100">FONTE</th><th width="100">ND</th><th width="60">UGR</th><th width="110">PI</th><th width="100">VALOR</th></tr>';
		$saida .='<tr><td align=center>'.($linha['recurso_ev'] ? $linha['recurso_ev']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_esf'] ? $linha['recurso_esf']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_ptres'] ? $linha['recurso_ptres']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_fonte'] ? $linha['recurso_fonte']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_nd'] ? $linha['recurso_nd']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_ugr'] ? $linha['recurso_ugr']  : '&nbsp;').'</td><td align=center>'.($linha['recurso_pi'] ? $linha['recurso_pi']  : '&nbsp;').'</td><td align=right>'.($linha['recurso_quantidade'] ? number_format($linha['recurso_quantidade'], 2, ',', '.')  : '&nbsp;').'</td></tr>';
		$saida .='</table></td></tr>';
		if ($linha['recurso_tipo']==5 && $linha['recurso_nd']) $saida .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>ND</b></td><td>'.utf8_encode($linha['nd']).'</td></tr>';
		}
	if ($linha['recurso_nota']) $saida .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$linha['recurso_nota'].'</td></tr>';
	$saida .= '</table>';

	if ($linha['recurso_tipo'] < 4) $quantidade=(float)$linha['recurso_quantidade'];
	else {
		$sql->adTabela('recurso_tarefa');
		$sql->adCampo('SUM(recurso_tarefa_quantidade)');
		$sql->adOnde('recurso_tarefa_recurso='.(int)$recurso_id);
		if ($tarefa_id) $sql->adOnde('recurso_tarefa_tarefa!='.(int)$tarefa_id);
		$sql->adOnde('recurso_tarefa_aprovado=1');
		$resultado = $sql->Resultado();
		$sql->limpar();
		$quantidade=(float)($linha['recurso_quantidade']-$resultado);
		}
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("valor_hora","value", float_brasileiro($linha['recurso_hora_custo']));
	$objResposta->assign("custo","value", float_brasileiro($linha['recurso_custo']));
	$objResposta->assign("quantidade","value", float_brasileiro($quantidade));
	$objResposta->assign("qnt_maxima","value",$quantidade);
	$objResposta->assign($posicao,"innerHTML", utf8_encode($saida));
	
	
	$objResposta->assign("recurso_tarefa_nd","value", utf8_encode($linha['recurso_nd']));
	$objResposta->assign("recurso_tarefa_categoria_economica","value", utf8_encode($linha['recurso_categoria_economica']));
	$objResposta->assign("recurso_tarefa_grupo_despesa","value", utf8_encode($linha['recurso_grupo_despesa']));
	$objResposta->assign("recurso_tarefa_modalidade_aplicacao","value", utf8_encode($linha['recurso_modalidade_aplicacao']));
	//$objResposta->assign("recurso_tarefa_codigo","value", utf8_encode($linha['recurso_codigo']));
	//$objResposta->assign("recurso_tarefa_fonte","value", utf8_encode($linha['recurso_fonte']));
	//$objResposta->assign("recurso_tarefa_regiao","value", utf8_encode($linha['recurso_regiao']));
	//$objResposta->assign("recurso_tarefa_bdi","value", ($linha['recurso_bdi'] ? number_format($linha['recurso_bdi'], 2, ',', '.') : ''));
	$objResposta->assign("recurso_moeda","value", $linha['recurso_moeda']);
	$nd=vetor_nd($linha['recurso_nd'], true, null, 3 ,$linha['recurso_categoria_economica'], $linha['recurso_grupo_despesa'], $linha['recurso_modalidade_aplicacao']);
	$saida=selecionaVetor($nd, 'recurso_tarefa_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', $linha['recurso_nd']);
	$objResposta->assign("combo_nd1","innerHTML", $saida);
	
	
	
	return $objResposta;
	}	
$xajax->registerFunction("detalhes_recurso");		

	
function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");	
	
function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_nd_ajax");	

function ver_recursos(
	$cia_id=null, 
	$ver_subordinadas=null, 
	$dept_id=null, 
	$recurso_tipo=null, 
	$recurso_responsavel=null, 
	$recurso_ano=null, 
	$recurso_ugr=null, 
	$recurso_ptres=null, 
	$recurso_credito_adicional=null, 
	$recurso_movimentacao_orcamentaria=null, 
	$recurso_identificador_uso=null, 
	$recurso_pesquisa=null
	){
	$recurso_tipos = getSisValor('TipoRecurso');
	$sql = new BDConsulta;
	
	
	if ($ver_subordinadas){
		$vetor_cias=array();
		lista_cias_subordinadas($cia_id, $vetor_cias);
		$vetor_cias[]=$cia_id;
		$lista_cias=implode(',',$vetor_cias);
		}
	else $lista_cias=$cia_id;
	
	
	$sql->adTabela('recursos');
	if ($dept_id){
		$sql->esqUnir('recurso_depts', 'recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
		$sql->adOnde('departamento_id = '.(int)$dept_id);
		}
	if ($recurso_tipo) $sql->adOnde('recurso_tipo = '.(int)$recurso_tipo);
	if ($recurso_responsavel) $sql->adOnde('recurso_responsavel = '.(int)$recurso_responsavel);
	if ($recurso_ano) $sql->adOnde('recurso_ano = "'.$recurso_ano.'"');
	if ($recurso_ugr) $sql->adOnde('recurso_ugr = "'.$recurso_ugr.'"');
	if ($recurso_ptres) $sql->adOnde('recurso_ptres =  "'.$recurso_ptres.'"');
	if ($recurso_credito_adicional) $sql->adOnde('recurso_credito_adicional =  "'.$recurso_credito_adicional.'"');
	if ($recurso_movimentacao_orcamentaria) $sql->adOnde('recurso_movimentacao_orcamentaria =  "'.$recurso_movimentacao_orcamentaria.'"');
	if ($recurso_identificador_uso) $sql->adOnde('recurso_identificador_uso =  "'.$recurso_identificador_uso.'"');
	if ($recurso_pesquisa) $sql->adOnde('(recurso_nome LIKE \'%'.previnirXSS(utf8_decode($recurso_pesquisa)).'%\' OR recurso_chave LIKE \'%'.previnirXSS(utf8_decode($recurso_pesquisa)).'%\' OR recurso_nota LIKE \'%'.previnirXSS(utf8_decode($recurso_pesquisa)).'%\')');
	if ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
	
	$sql->adCampo('recurso_id, recurso_nome, recurso_tipo, recurso_nivel_acesso');
	$sql->adOrdem('recurso_tipo', 'recurso_nome');
	
	$res = $sql->Lista();
	$sql->limpar();
	$todos_recursos = array();
	foreach ($res as $linha) {
		if (permiteAcessarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $todos_recursos[$linha['recurso_id']] = utf8_encode($linha['recurso_nome'].' ('.$recurso_tipos[$linha['recurso_tipo']].')');
		}
	$saida=selecionaVetor($todos_recursos, 'mat_recursos', 'style="width:350px" size="5" class="texto" onclick="selecionar_recurso(this.value);"');
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_recursos',"innerHTML", $saida);
	return $objResposta;
	}	

$xajax->registerFunction("ver_recursos");	



























function calcular_duracao($inicio, $fim, $recurso_id=0, $tarefa_id=0, $tempo_corrido_ponto=null, $porcentagem=100){
	global $config;
	$sql = new BDConsulta;
	$projeto_id=0;
	if ($tarefa_id){
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_cia, tarefa_projeto');
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$linha=$sql->Linha();
		$sql->limpar();
		$cia_id=(isset($linha['tarefa_cia']) ? $linha['tarefa_cia'] : 0);
		$projeto_id=(isset($linha['tarefa_projeto']) ? $linha['tarefa_projeto'] : 0);
		}
	if (!$cia_id){
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos','contatos','usuario_contato=contato_id');
		$sql->adCampo('contato_cia');
		$sql->adOnde('usuario_id = '.(int)$recurso_id);
		$cia_id=$sql->Resultado();
		$sql->limpar();
		}

	if (($tarefa_id && !$config['aceitar_fora_periodo_ponto'])|| $tempo_corrido_ponto) $horas = horas_periodo($inicio, $fim, $cia_id, null, $projeto_id, $recurso_id, $tarefa_id, $tempo_corrido_ponto);
	else $horas = horas_periodo($inicio, $fim, $cia_id, null, null, $recurso_id);

	$horas=$horas*($porcentagem/100);

	$objResposta = new xajaxResponse();
	$objResposta->assign("duracao","value", ($horas ? float_brasileiro($horas) : ''));
	return $objResposta;
	}
$xajax->registerFunction("calcular_duracao");

function data_final_periodo($inicio, $horas, $recurso_id=0, $tarefa_id=0, $tempo_corrido_ponto=false, $porcentagem=100){
	global $config;
	$sql = new BDConsulta;
	$projeto_id=0;
	if ($tarefa_id){
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_cia, tarefa_projeto');
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$linha=$sql->Linha();
		$sql->limpar();
		$cia_id=(isset($linha['tarefa_cia']) ? $linha['tarefa_cia'] : 0);
		$projeto_id=(isset($linha['tarefa_projeto']) ? $linha['tarefa_projeto'] : 0);
		}
	if (!$cia_id){
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos','contatos','usuario_contato=contato_id');
		$sql->adCampo('contato_cia');
		$sql->adOnde('usuario_id = '.(int)$recurso_id);
		$cia_id=$sql->Resultado();
		$sql->limpar();
		}

	$horas=float_americano(abs($horas*($porcentagem/100)));
	
	if (($tarefa_id && !$config['aceitar_fora_periodo_ponto']) || $tempo_corrido_ponto) $data_final = calculo_data_final_periodo($inicio, $horas, $cia_id, null, $projeto_id, $recurso_id, $tarefa_id, $tempo_corrido_ponto);
	else $data_final = calculo_data_final_periodo($inicio, $horas, $cia_id, null, null, $recurso_id);


	$data=new CData($data_final);
	$objResposta = new xajaxResponse();
	$objResposta->assign("data_fim_real","value", $data->format("%Y-%m-%d"));
	$objResposta->assign("data_fim","value", $data->format("%d/%m/%Y"));
	$objResposta->assign("hora_fim","value", $data->format("%H"));
	$objResposta->assign("minuto_fim","value", $data->format("%M"));
	return $objResposta;
	}
$xajax->registerFunction("data_final_periodo");







function incluir_trabalho(
	$inicio='', 
	$fim='', 
	$recurso_id=0,
	$tipo=0, 
	$tarefa_id=0, 
	$duracao=0, 
	$valor_hora=null,
	$custo=null,  
	$obs='', 
	$quantidade=0, 
	$tempo_corrido_ponto=false, 
	$percentual = 100,
	
	$recurso_tarefa_nd='',
	$recurso_tarefa_categoria_economica='',
	$recurso_tarefa_grupo_despesa='',
	$recurso_tarefa_modalidade_aplicacao='',
	$recurso_tarefa_codigo=null,
	$recurso_tarefa_fonte=null,
	$recurso_tarefa_regiao=null,
	$recurso_tarefa_bdi=null,
	$recurso_tarefa_moeda=null,
	$recurso_tarefa_data_moeda=null
	){
		
	global $config;

	
	$recurso_tarefa_cotacao=($recurso_tarefa_moeda > 1 ? cotacao($recurso_tarefa_moeda, $recurso_tarefa_data_moeda) : 1);	


	$duracao=float_americano($duracao);
	$valor_hora=float_americano($valor_hora);
	$custo=float_americano($custo);
  $quantidade=float_americano($quantidade);

	$recurso_tarefa_categoria_economica=previnirXSS(utf8_decode($recurso_tarefa_categoria_economica));
	$recurso_tarefa_grupo_despesa=previnirXSS(utf8_decode($recurso_tarefa_grupo_despesa));
	$recurso_tarefa_modalidade_aplicacao=previnirXSS(utf8_decode($recurso_tarefa_modalidade_aplicacao));
	$recurso_tarefa_codigo=previnirXSS(utf8_decode($recurso_tarefa_codigo));
	$recurso_tarefa_fonte=previnirXSS(utf8_decode($recurso_tarefa_fonte));
	$recurso_tarefa_regiao=previnirXSS(utf8_decode($recurso_tarefa_regiao));


	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_inicio, tarefa_fim');
	$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
	$linha=$sql->linha();
	$sql->limpar();
	if (!$config['aceitar_fora_periodo_ponto'] && (($inicio < $linha['tarefa_inicio']) || ($fim > $linha['tarefa_fim']))){
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_alerta","innerHTML", utf8_encode('Fora dos limites de datas da tarefa'));
		return $objResposta;
		}

	$sql->adTabela('recurso_tarefa');
	$sql->adCampo('MAX(recurso_tarefa_ordem)');
	$sql->adOnde('recurso_tarefa_tarefa ='.(int)$tarefa_id);
	$ordem=$sql->resultado();


	$sql->adTabela('recurso_tarefa');
	$sql->adInserir('recurso_tarefa_ordem', ($ordem+1));
	$sql->adInserir('recurso_tarefa_recurso', $recurso_id);
	$sql->adInserir('recurso_tarefa_tarefa', $tarefa_id);
	if ($tipo!=5) {
		$sql->adInserir('recurso_tarefa_inicio', $inicio);
		$sql->adInserir('recurso_tarefa_fim', $fim);
		$sql->adInserir('recurso_tarefa_duracao', $duracao);
		}
	$sql->adInserir('recurso_tarefa_quantidade', $quantidade);	
  $sql->adInserir('recurso_tarefa_percentual', $percentual);
	$sql->adInserir('recurso_tarefa_valor_hora', $valor_hora);
	$sql->adInserir('recurso_tarefa_custo', $custo);
	$sql->adInserir('recurso_tarefa_corrido', $tempo_corrido_ponto ? 1 : 0);
	$sql->adInserir('recurso_tarefa_obs', previnirXSS(utf8_decode($obs)));
	
	$sql->adInserir('recurso_tarefa_nd', $recurso_tarefa_nd);
	$sql->adInserir('recurso_tarefa_categoria_economica', $recurso_tarefa_categoria_economica);
	$sql->adInserir('recurso_tarefa_grupo_despesa', $recurso_tarefa_grupo_despesa);
	$sql->adInserir('recurso_tarefa_modalidade_aplicacao', $recurso_tarefa_modalidade_aplicacao);
	if ($recurso_tarefa_codigo) $sql->adInserir('recurso_tarefa_codigo', $recurso_tarefa_codigo);
	if ($recurso_tarefa_fonte) $sql->adInserir('recurso_tarefa_fonte', $recurso_tarefa_fonte);
	if ($recurso_tarefa_regiao) $sql->adInserir('recurso_tarefa_regiao', $recurso_tarefa_regiao);
	if ($recurso_tarefa_bdi) $sql->adInserir('recurso_tarefa_bdi', float_americano($recurso_tarefa_bdi));
	$sql->adInserir('recurso_tarefa_moeda', $recurso_tarefa_moeda);
	$sql->adInserir('recurso_tarefa_data_moeda', $recurso_tarefa_data_moeda);
	$sql->adInserir('recurso_tarefa_cotacao', $recurso_tarefa_cotacao);

	$sql->exec();
	$sql->limpar();


	$saida=atualizar_recurso($tarefa_id);

	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_alerta","innerHTML", utf8_encode('trabalho inserido'));
	$objResposta->assign("lista_recursos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_trabalho");

function excluir_trabalho($recurso_tarefa_id=0, $recurso_id=0, $tarefa_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('recurso_tarefa');
	$sql->adOnde('recurso_tarefa_id='.$recurso_tarefa_id);
	$sql->exec();
	$saida=atualizar_recurso($tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_alerta","innerHTML", utf8_encode('trabalho excluído'));
	$objResposta->assign("lista_recursos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_trabalho");



function editar_trabalho($recurso_tarefa_id=0){
	$sql = new BDConsulta;
	$sql->adTabela('recurso_tarefa');
	$sql->adCampo('recurso_tarefa_id, 
	recurso_tarefa_obs, 
	recurso_tarefa_quantidade, 
	recurso_tarefa_percentual, 
	recurso_tarefa_valor_hora, 
	recurso_tarefa_custo, 
	recurso_tarefa_inicio, 
	formatar_data(recurso_tarefa_inicio, \'%d/%m/%Y\') AS data_inicio, 
	formatar_data(recurso_tarefa_inicio, \'%Y-%m-%d\') AS data_inicio_real, 
	formatar_data(recurso_tarefa_inicio, \'%H\') AS hora_inicio, 
	formatar_data(recurso_tarefa_inicio, \'%i\') AS minuto_inicio, 
	recurso_tarefa_fim, formatar_data(recurso_tarefa_fim, \'%d/%m/%Y\') AS data_fim, 
	formatar_data(recurso_tarefa_inicio, \'%Y-%m-%d\') AS data_fim_real, 
	formatar_data(recurso_tarefa_fim, \'%H\') AS hora_fim, 
	formatar_data(recurso_tarefa_fim, \'%i\') AS minuto_fim, 
	recurso_tarefa_duracao, recurso_tarefa_corrido,
	recurso_tarefa_nd,
	recurso_tarefa_categoria_economica,
	recurso_tarefa_grupo_despesa,
	recurso_tarefa_modalidade_aplicacao,
	recurso_tarefa_codigo,
	recurso_tarefa_fonte,
	recurso_tarefa_regiao,
	recurso_tarefa_bdi,
	recurso_tarefa_moeda,
	recurso_tarefa_data_moeda,
	formatar_data(recurso_tarefa_data_moeda, \'%d/%m/%Y\') AS data_moeda'
	
	);
	
	
	$sql->adOnde('recurso_tarefa_id = '.(int)$recurso_tarefa_id);
	$linha=$sql->linha();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("recurso_tarefa_id","value", $linha['recurso_tarefa_id']);
	$objResposta->assign("data_inicio_real","value", $linha['data_inicio_real']);
	$objResposta->assign("data_fim_real","value", $linha['data_fim_real']);
	$objResposta->assign("data_inicio","value", $linha['data_inicio']);
	$objResposta->assign("data_fim","value", $linha['data_fim']);
	$objResposta->assign("hora_inicio","value", $linha['hora_inicio']);
	$objResposta->assign("hora_fim","value", $linha['hora_fim']);
	$objResposta->assign("minuto_inicio","value", $linha['minuto_inicio']);
	$objResposta->assign("minuto_fim","value", $linha['minuto_fim']);
	$objResposta->assign("duracao","value",($linha['recurso_tarefa_duracao'] ? float_brasileiro($linha['recurso_tarefa_duracao']) : ''));
	$objResposta->assign("valor_hora","value",($linha['recurso_tarefa_valor_hora'] ? float_brasileiro($linha['recurso_tarefa_valor_hora']) : ''));
	$objResposta->assign("custo","value",($linha['recurso_tarefa_custo'] ? float_brasileiro($linha['recurso_tarefa_custo']) : ''));
  $objResposta->assign("quantidade","value",($linha['recurso_tarefa_quantidade'] ? float_brasileiro($linha['recurso_tarefa_quantidade']) : ''));
  $objResposta->assign("percentual_alocado","value", ($linha['recurso_tarefa_percentual'] ? (int)$linha['recurso_tarefa_percentual'] : 100));
  $objResposta->assign("tempo_corrido_ponto","checked", (((int)$linha['recurso_tarefa_corrido']) ? true : false));
	$objResposta->assign("texto_apoio_obs","value", utf8_encode($linha['recurso_tarefa_obs']));	
	
	$objResposta->assign("recurso_tarefa_nd","value", utf8_encode($linha['recurso_tarefa_nd']));
	$objResposta->assign("recurso_tarefa_categoria_economica","value", utf8_encode($linha['recurso_tarefa_categoria_economica']));
	$objResposta->assign("recurso_tarefa_grupo_despesa","value", utf8_encode($linha['recurso_tarefa_grupo_despesa']));
	$objResposta->assign("recurso_tarefa_modalidade_aplicacao","value", utf8_encode($linha['recurso_tarefa_modalidade_aplicacao']));
	$objResposta->assign("recurso_tarefa_codigo","value", utf8_encode($linha['recurso_tarefa_codigo']));
	$objResposta->assign("recurso_tarefa_fonte","value", utf8_encode($linha['recurso_tarefa_fonte']));
	$objResposta->assign("recurso_tarefa_regiao","value", utf8_encode($linha['recurso_tarefa_regiao']));
	$objResposta->assign("recurso_tarefa_bdi","value", ($linha['recurso_tarefa_bdi'] ? number_format($linha['recurso_tarefa_bdi'], 2, ',', '.') : ''));
	$objResposta->assign("recurso_tarefa_moeda","value", $linha['recurso_tarefa_moeda']);
	$objResposta->assign("data7_texto","value", $linha['data_moeda']);
	$objResposta->assign("recurso_tarefa_data_moeda","value", $linha['recurso_tarefa_data_moeda']);
	
	$nd=vetor_nd($linha['recurso_tarefa_nd'], true, null, 3 ,$linha['recurso_tarefa_categoria_economica'], $linha['recurso_tarefa_grupo_despesa'], $linha['recurso_tarefa_modalidade_aplicacao']);
	$saida=selecionaVetor($nd, 'recurso_tarefa_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', $linha['recurso_tarefa_nd']);
	$objResposta->assign("combo_nd1","innerHTML", $saida);
	
	
	return $objResposta;

	}
$xajax->registerFunction("editar_trabalho");

function atualizacao_trabalho(
	$recurso_tarefa_id=0, 
	$inicio=null, 
	$fim=null, 
	$duracao=0, 
	$tarefa_id=0, 
	$recurso_id=0, 
	$valor_hora=null, 
	$custo=null, 
	$obs='', 
	$quantidade = 0, 
	$tempo_corrido_ponto=false, 
	$percentual = 100
	){
	global $config;

	$duracao=float_americano($duracao);
	$valor_hora=float_americano($valor_hora);
  $custo=float_americano($custo);
  $quantidade=float_americano($quantidade);

	$sql = new BDConsulta;
	//verificar se já não tem outro trabalho no período
	$sql->adTabela('recurso_tarefa');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = recurso_tarefa.recurso_tarefa_tarefa');
	$sql->adCampo('recurso_tarefa_id, recurso_tarefa_valor_hora, recurso_tarefa_custo, tarefa_nome, formatar_data(recurso_tarefa_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(recurso_tarefa_fim, \'%d/%m/%Y %H:%i\') AS fim');
	$sql->adOnde('recurso_tarefa_recurso = '.(int)$recurso_id);
	$sql->adOnde('recurso_tarefa_id != '.(int)$recurso_tarefa_id);
	$sql->adOnde('recurso_tarefa_duracao > 0');
	$sql->adOnde('(\''.$fim.'\' > recurso_tarefa_inicio  AND \''.$fim.'\' <= recurso_tarefa_fim) OR (\''.$inicio.'\' >= recurso_tarefa_inicio  AND \''.$inicio.'\' < recurso_tarefa_fim)');
	$existe=$sql->linha();
	$sql->limpar();
	if (isset($existe['recurso_tarefa_id']) && $existe['recurso_tarefa_id']){
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_alerta","innerHTML", utf8_encode('Já está cadastrado '.$existe['tarefa_nome'].' de '.$existe['inicio'].' a '.$existe['fim'].'!'));
		return $objResposta;
		}
	//verificar se as datas de início e fim estão dentro da faixa permitida
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_inicio, tarefa_fim');
	$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
	$linha=$sql->linha();
	$sql->limpar();
	if (!$config['aceitar_fora_periodo_ponto'] && !$tempo_corrido_ponto && (($inicio < $linha['tarefa_inicio']) || ($fim > $linha['tarefa_fim']))){
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_alerta","innerHTML", utf8_encode('Fora dos limites de datas da tarefa'));
		return $objResposta;
		}

	$sql->adTabela('recurso_tarefa');
	$sql->adAtualizar('recurso_tarefa_inicio', $inicio);
	$sql->adAtualizar('recurso_tarefa_fim', $fim);
	$sql->adAtualizar('recurso_tarefa_duracao', $duracao);
	$sql->adAtualizar('recurso_tarefa_valor_hora', $valor_hora);
	$sql->adAtualizar('recurso_tarefa_custo', $custo);
  $sql->adAtualizar('recurso_tarefa_quantidade', $quantidade);
  $sql->adAtualizar('recurso_tarefa_percentual', $percentual);
  $sql->adAtualizar('recurso_tarefa_corrido', $tempo_corrido_ponto ? 1 : 0);
	$sql->adAtualizar('recurso_tarefa_obs', previnirXSS(utf8_decode($obs)));
	$sql->adOnde('recurso_tarefa_id='.(int)$recurso_tarefa_id);
	$sql->exec();
	$sql->limpar();
	$saida=atualizar_recurso($tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_alerta","innerHTML", utf8_encode('trabalho atualizado'));
	$objResposta->assign("lista_recursos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("atualizacao_trabalho");

$xajax->processRequest();








































function mudar_posicao_arquivo($recurso_tarefa_arquivo_ordem, $recurso_tarefa_arquivo_id, $direcao, $recurso_tarefa_id=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$recurso_tarefa_arquivo_id) {
		$novo_ui_recurso_tarefa_arquivo_ordem = $recurso_tarefa_arquivo_ordem;
		$sql->adTabela('recurso_tarefa_arquivo');
		$sql->adOnde('recurso_tarefa_arquivo_id != '.$recurso_tarefa_arquivo_id);
		$sql->adOnde('recurso_tarefa_arquivo_ponto = '.$recurso_tarefa_id);
		$sql->adOrdem('recurso_tarefa_arquivo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();

		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_recurso_tarefa_arquivo_ordem;
			$novo_ui_recurso_tarefa_arquivo_ordem--;
			}
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_recurso_tarefa_arquivo_ordem;
			$novo_ui_recurso_tarefa_arquivo_ordem++;
			}
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_recurso_tarefa_arquivo_ordem;
			$novo_ui_recurso_tarefa_arquivo_ordem = 1;
			}
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_recurso_tarefa_arquivo_ordem;
			$novo_ui_recurso_tarefa_arquivo_ordem = count($membros) + 1;
			}
		if ($novo_ui_recurso_tarefa_arquivo_ordem && ($novo_ui_recurso_tarefa_arquivo_ordem <= count($membros) + 1)) {
			$sql->adTabela('recurso_tarefa_arquivo');
			$sql->adAtualizar('recurso_tarefa_arquivo_ordem', $novo_ui_recurso_tarefa_arquivo_ordem);
			$sql->adOnde('recurso_tarefa_arquivo_id = '.$recurso_tarefa_arquivo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_recurso_tarefa_arquivo_ordem) {
					$sql->adTabela('recurso_tarefa_arquivo');
					$sql->adAtualizar('recurso_tarefa_arquivo_ordem', $idx);
					$sql->adOnde('recurso_tarefa_arquivo_id = '.$acao['recurso_tarefa_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('recurso_tarefa_arquivo');
					$sql->adAtualizar('recurso_tarefa_arquivo_ordem', $idx + 1);
					$sql->adOnde('recurso_tarefa_arquivo_id = '.$acao['recurso_tarefa_arquivo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}

	$saida=atualizar_arquivos($recurso_tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_arquivos","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_arquivo");

function atualizar_arquivos($recurso_tarefa_id){
	global $config;

	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
	$sql = new BDConsulta;
	$saida='';
	//arquivo anexo
	$sql->adTabela('recurso_tarefa_arquivo');
	$sql->adCampo('recurso_tarefa_arquivo_id, recurso_tarefa_arquivo_data, recurso_tarefa_arquivo_ordem, recurso_tarefa_arquivo_nome, recurso_tarefa_arquivo_endereco');
	$sql->adOnde('recurso_tarefa_arquivo_ponto='.(int)$recurso_tarefa_id);
	$sql->adOrdem('recurso_tarefa_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();

	if (count($arquivos)) $saida.= '<tr><td colspan=15><table cellspacing=0 cellpadding=0><tr><td colspan=2><b>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</b></td></tr>';
	foreach ($arquivos as $linha) {
		$saida.= '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$linha['recurso_tarefa_arquivo_ordem'].', '.$linha['recurso_tarefa_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
		$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$linha['recurso_tarefa_arquivo_ordem'].', '.$linha['recurso_tarefa_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
		$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$linha['recurso_tarefa_arquivo_ordem'].', '.$linha['recurso_tarefa_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
		$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$linha['recurso_tarefa_arquivo_ordem'].', '.$linha['recurso_tarefa_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
		$saida.= '</td>';
		$saida.= '<td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=calendario&a=recurso_tarefa_pro_download&sem_cabecalho=1&recurso_tarefa_arquivo_id='.$linha['recurso_tarefa_arquivo_id'].'\');">'.utf8_encode($linha['recurso_tarefa_arquivo_nome']).'</a>';
		$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir este arquivo?').'\')) {excluir_arquivo('.$linha['recurso_tarefa_arquivo_id'].');}">'.imagem('icones/remover.png').'</a></td>';
		$saida.= '</tr>';
		}
	if (count($arquivos)) $saida.= '</table></td></tr>';
	return $saida;
	}
$xajax->registerFunction("atualizar_arquivo");


function excluir_arquivo($recurso_tarefa_arquivo_id, $recurso_tarefa_id){
	global $config;
	$sql = new BDConsulta;

	$sql->adTabela('recurso_tarefa_arquivo');
	$sql->adCampo('recurso_tarefa_arquivo_endereco, recurso_tarefa_arquivo_local, recurso_tarefa_arquivo_nome_real');
	$sql->adOnde('recurso_tarefa_arquivo_id ='.(int)$recurso_tarefa_arquivo_id);
	$endereco=$sql->linha();
	$sql->limpar();
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	
	if ($endereco['recurso_tarefa_arquivo_local'])@unlink($base_dir.'/arquivos/'.$endereco['recurso_tarefa_arquivo_local'].$endereco['recurso_tarefa_arquivo_nome_real']);
	else @unlink($base_dir.'/arquivos/recurso_ponto/'.$endereco['recurso_tarefa_arquivo_endereco']);

	$sql->setExcluir('recurso_tarefa_arquivo');
	$sql->adOnde('recurso_tarefa_arquivo_id='.$recurso_tarefa_arquivo_id);
	$sql->exec();
	$saida=atualizar_arquivos($recurso_tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_arquivos","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("excluir_arquivo");

function exibir_arquivo($recurso_tarefa_id){
	$saida=atualizar_arquivos($recurso_tarefa_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_arquivos","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("exibir_arquivo");










$xajax->processRequest();
?>