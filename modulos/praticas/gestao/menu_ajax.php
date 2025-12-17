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

function painel_filtro($visao){
	global $Aplic;
	if ($visao=='none') $painel_filtro=0; 
	else  $painel_filtro=1;
	$Aplic->setEstado('painel_filtro',$painel_filtro);
	}
$xajax->registerFunction("painel_filtro");

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");



$swot_ativo=($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null));

function inserir_swot($pg_swot_id=null, $pg_swot_pg=null, $pg_swot_swot=null, $pg_swot_nome=null, $pg_swot_tipo=null){
	global $Aplic;
	$sql = new BDConsulta;
	if (!$pg_swot_id){
	 	$sql->adTabela('pg_swot');
		$sql->adCampo('count(pg_swot_id) AS soma');
		$sql->adOnde('pg_swot_pg ='.(int)$pg_swot_pg);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
		$sql->adTabela('pg_swot');
		$sql->adInserir('pg_swot_pg', $pg_swot_pg);
		if ($pg_swot_nome) $sql->adInserir('pg_swot_nome', previnirXSS(utf8_decode($pg_swot_nome)));
		if ($pg_swot_swot) $sql->adInserir('pg_swot_swot', $pg_swot_swot);
		$sql->adInserir('pg_swot_ordem', $soma_total);
		$sql->adInserir('pg_swot_tipo', $pg_swot_tipo);
		$sql->exec();
		$sql->limpar();
		}
	else{
		$sql->adTabela('pg_swot');
		$sql->adAtualizar('pg_swot_nome', previnirXSS(utf8_decode($pg_swot_nome)));
		$sql->adAtualizar('pg_swot_swot', ($pg_swot_swot ? $pg_swot_swot : null));
		$sql->adAtualizar('pg_swot_tipo', $pg_swot_tipo);
		$sql->adOnde('pg_swot_id = '.(int)$pg_swot_id);
		$sql->exec();
		$sql->limpar();
		}
	$saida=atualizar_swot($pg_swot_pg, $pg_swot_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_swots","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("inserir_swot");	

function atualizar_swot($pg_swot_pg=0, $pg_swot_tipo='s'){
	global $Aplic, $swot_ativo;
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('pg_swot');
	$sql->esqUnir('swot', 'swot', 'pg_swot_swot=swot_id');
	$sql->adCampo('pg_swot_id, pg_swot_nome, swot_nome, pg_swot_ordem');
	$sql->adOnde('pg_swot_pg='.(int)$pg_swot_pg);
	$sql->adOnde('pg_swot_tipo=\''.$pg_swot_tipo.'\'');
	$sql->adOrdem('pg_swot_ordem ASC');
	$swots=$sql->Lista();
	$sql->limpar();
	
	if ($pg_swot_tipo=='s') $legenda='Força';
	elseif ($pg_swot_tipo=='w') $legenda='Fraqueza';
	elseif ($pg_swot_tipo=='o') $legenda='Oportunidade';
	else $legenda='Ameaça';
	
	
	if (count($swots)) $saida.='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th></th><th style="white-space: nowrap">'.$legenda.'</th><th></th></tr>';
	foreach ($swots as $swot) {
		$saida.='<tr>';
		$saida.='<td style="white-space: nowrap" width="40" align="center">';
		$saida.=dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.='</td>';
		$saida.='<td>'.($swot['swot_nome'] ? $swot['swot_nome'] : $swot['pg_swot_nome']).'</td>';
		$saida.=($swot_ativo ?'<td width="16" align="center">' : '<td width="32" align="center"><a href="javascript: void(0);" onclick="editar_swot('.$swot['pg_swot_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar.').'</a>');
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_swot('.$swot['pg_swot_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		$saida.='</tr>';
		}
	if (count($swots)) $saida.='</table>';
	return $saida;	
	}
	
	
function exibir_swot($pg_swot_pg=0, $pg_swot_tipo){
	$saida=atualizar_swot($pg_swot_pg, $pg_swot_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_swots","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_swot");
	
	
function mudar_ordem_swot($ordem, $pg_swot_id, $direcao, $pg_swot_pg=0, $pg_swot_tipo='s'){
	$sql = new BDConsulta;
	$novo_ui_ordem = $ordem;
	$sql->adTabela('pg_swot');
	$sql->adOnde('pg_swot_id != '.(int)$pg_swot_id);
	$sql->adOnde('pg_swot_pg='.(int)$pg_swot_pg);
	$sql->adOnde('pg_swot_tipo=\''.$pg_swot_tipo.'\'');
	$sql->adOrdem('pg_swot_ordem');
	$swots = $sql->Lista();
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
		$novo_ui_ordem = count($swots) + 1;
		}
	if ($novo_ui_ordem && ($novo_ui_ordem <= count($swots) + 1)) {
		$sql->adTabela('pg_swot');
		$sql->adAtualizar('pg_swot_ordem', $novo_ui_ordem);
		$sql->adOnde('pg_swot_id = '.(int)$pg_swot_id);
		$sql->exec();
		$sql->limpar();
		$idx = 1;
		foreach ($swots as $swot) {
			if ((int)$idx != (int)$novo_ui_ordem) {
				$sql->adTabela('pg_swot');
				$sql->adAtualizar('pg_swot_ordem', $idx);
				$sql->adOnde('pg_swot_id = '.(int)$swot['pg_swot_id']);
				$sql->exec();
				$sql->limpar();
				$idx++;
				} 
			else {
				$sql->adTabela('pg_swot');
				$sql->adAtualizar('pg_swot_ordem', $idx + 1);
				$sql->adOnde('pg_swot_id = '.(int)$swot['pg_swot_id']);
				$sql->exec();
				$sql->limpar();
				$idx = $idx + 2;
				}
			}		
		}
	$saida=atualizar_swot($pg_swot_pg, $pg_swot_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_swots","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("mudar_ordem_swot");
	
function excluir_swot($pg_swot_id, $pg_swot_pg=0, $pg_swot_tipo=''){
	$sql = new BDConsulta;
	$sql->setExcluir('pg_swot');
	$sql->adOnde('pg_swot_id='.(int)$pg_swot_id);
	$sql->exec();
	$sql->limpar();	
	$saida=atualizar_swot($pg_swot_pg, $pg_swot_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_swots","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_swot");	
	
function editar_swot($pg_swot_id){
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('pg_swot');
	$sql->adCampo('pg_swot_nome, pg_swot_tipo');
	$sql->adOnde('pg_swot_id='.(int)$pg_swot_id);
	$linha=$sql->linha();
	$sql->limpar();	
	$objResposta = new xajaxResponse();
	$objResposta->assign("pg_swot_id","value", $pg_swot_id);
	$objResposta->assign("pg_swot_tipo","value", $linha['pg_swot_tipo']);		
	$objResposta->assign("apoio1","value", utf8_encode($linha['pg_swot_nome']));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_swot");		






























function inserir_perspectiva($pg_perspectiva_id=null, $pg_id=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$sql->adTabela('plano_gestao_perspectivas');
	$sql->adCampo('count(pg_perspectiva_id)');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
	$sql->adOnde('pg_perspectiva_id ='.(int)$pg_perspectiva_id);	
  $existe = $sql->Resultado();
  $sql->limpar();
	
	
	if (!$existe){
	 	$sql->adTabela('plano_gestao_perspectivas');
		$sql->adCampo('count(pg_perspectiva_id) AS soma');
		$sql->adOnde('pg_id ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('plano_gestao_perspectivas');
		$sql->adInserir('pg_id', $pg_id);
		$sql->adInserir('pg_perspectiva_id', $pg_perspectiva_id);
		$sql->adInserir('pg_perspectiva_ordem', $soma_total);
		$sql->exec();
		$sql->limpar();
		
		$saida=atualizar_perspectiva($pg_id);
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_perspectivas","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
	}
$xajax->registerFunction("inserir_perspectiva");	

function atualizar_perspectiva($pg_id=0){
	global $Aplic, $config;
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_perspectivas');
	$sql->esqUnir('perspectivas', 'perspectivas', 'plano_gestao_perspectivas.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adCampo('pg_perspectiva_nome, pg_perspectiva_cor, plano_gestao_perspectivas.pg_perspectiva_ordem, perspectivas.pg_perspectiva_id');
	$sql->adOnde('plano_gestao_perspectivas.pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_perspectiva_ordem ASC');
	$perspectivas=$sql->Lista();
	
	if (count($perspectivas)) $saida.='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th></th><th style="white-space: nowrap">'.ucfirst($config['perspectiva']).'</th><th></th></tr>';
	foreach ($perspectivas as $perspectiva) {
		$saida.='<tr>';
		$saida.=' <td td style="white-space: nowrap" width="40" align="center">';
		$saida.=dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_perspectiva('.$perspectiva['pg_perspectiva_ordem'].', '.$perspectiva['pg_perspectiva_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_perspectiva('.$perspectiva['pg_perspectiva_ordem'].', '.$perspectiva['pg_perspectiva_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_perspectiva('.$perspectiva['pg_perspectiva_ordem'].', '.$perspectiva['pg_perspectiva_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_perspectiva('.$perspectiva['pg_perspectiva_ordem'].', '.$perspectiva['pg_perspectiva_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.='</td>';
		$saida.='<td style="background-color: #'.$perspectiva['pg_perspectiva_cor'].'; color:#'.melhorCor($perspectiva['pg_perspectiva_cor']).'">'.$perspectiva['pg_perspectiva_nome'].'</td>';
		$saida.='<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_perspectiva('.$perspectiva['pg_perspectiva_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		$saida.='</tr>';
		}
	if (count($perspectivas)) $saida.='</table>';
	return $saida;	
	}
	

	
function mudar_ordem_perspectiva($ordem, $pg_perspectiva_id, $direcao, $pg_id=0){
	$sql = new BDConsulta;
	$novo_ui_ordem = $ordem;
	$sql->adTabela('plano_gestao_perspectivas');
	$sql->adOnde('pg_perspectiva_id != '.(int)$pg_perspectiva_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_perspectiva_ordem');
	$perspectivas = $sql->Lista();
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
		$novo_ui_ordem = count($perspectivas) + 1;
		}
	if ($novo_ui_ordem && ($novo_ui_ordem <= count($perspectivas) + 1)) {
		$sql->adTabela('plano_gestao_perspectivas');
		$sql->adAtualizar('pg_perspectiva_ordem', $novo_ui_ordem);
		$sql->adOnde('pg_perspectiva_id = '.(int)$pg_perspectiva_id);
		$sql->adOnde('pg_id='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		$idx = 1;
		foreach ($perspectivas as $perspectiva) {
			if ((int)$idx != (int)$novo_ui_ordem) {
				$sql->adTabela('plano_gestao_perspectivas');
				$sql->adAtualizar('pg_perspectiva_ordem', $idx);
				$sql->adOnde('pg_perspectiva_id = '.(int)$perspectiva['pg_perspectiva_id']);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx++;
				} 
			else {
				$sql->adTabela('plano_gestao_perspectivas');
				$sql->adAtualizar('pg_perspectiva_ordem', $idx + 1);
				$sql->adOnde('pg_perspectiva_id = '.(int)$perspectiva['pg_perspectiva_id']);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx = $idx + 2;
				}
			}		
		}
	$saida=atualizar_perspectiva($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("mudar_ordem_perspectiva");
	
function excluir_perspectiva($pg_perspectiva_id, $pg_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_gestao_perspectivas');
	$sql->adOnde('pg_perspectiva_id='.(int)$pg_perspectiva_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->exec();
	$sql->limpar();	
	$saida=atualizar_perspectiva($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_perspectivas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_perspectiva");	







function inserir_tema($tema_id=null, $pg_id=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$sql->adTabela('plano_gestao_tema');
	$sql->adCampo('count(tema_id)');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
	$sql->adOnde('tema_id ='.(int)$tema_id);	
  $existe = $sql->Resultado();
  $sql->limpar();
	
	
	if (!$existe){
	 	$sql->adTabela('plano_gestao_tema');
		$sql->adCampo('count(tema_id) AS soma');
		$sql->adOnde('pg_id ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('plano_gestao_tema');
		$sql->adInserir('pg_id', $pg_id);
		$sql->adInserir('tema_id', $tema_id);
		$sql->adInserir('tema_ordem', $soma_total);
		$sql->exec();
		$sql->limpar();
		
		$saida=atualizar_tema($pg_id);
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_temas","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
	}
$xajax->registerFunction("inserir_tema");	

function atualizar_tema($pg_id=0){
	global $Aplic, $config;
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_tema');
	$sql->esqUnir('tema', 'tema', 'plano_gestao_tema.tema_id=tema.tema_id');
	$sql->adCampo('tema_nome, tema_cor, plano_gestao_tema.tema_ordem, tema.tema_id');
	$sql->adOnde('plano_gestao_tema.pg_id='.(int)$pg_id);
	$sql->adOrdem('tema_ordem ASC');
	$temas=$sql->Lista();
	
	if (count($temas)) $saida.='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th></th><th style="white-space: nowrap">'.ucfirst($config['tema']).'</th><th></th></tr>';
	foreach ($temas as $tema) {
		$saida.='<tr>';
		$saida.=' <td td style="white-space: nowrap" width="40" align="center">';
		$saida.=dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_tema('.$tema['tema_ordem'].', '.$tema['tema_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_tema('.$tema['tema_ordem'].', '.$tema['tema_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_tema('.$tema['tema_ordem'].', '.$tema['tema_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_tema('.$tema['tema_ordem'].', '.$tema['tema_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.='</td>';
		$saida.='<td style="background-color: #'.$tema['tema_cor'].'; color:#'.melhorCor($tema['tema_cor']).'">'.$tema['tema_nome'].'</td>';
		$saida.='<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_tema('.$tema['tema_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		$saida.='</tr>';
		}
	if (count($temas)) $saida.='</table>';
	return $saida;	
	}
	

	
function mudar_ordem_tema($ordem, $tema_id, $direcao, $pg_id=0){
	$sql = new BDConsulta;
	$novo_ui_ordem = $ordem;
	$sql->adTabela('plano_gestao_tema');
	$sql->adOnde('tema_id != '.(int)$tema_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->adOrdem('tema_ordem');
	$temas = $sql->Lista();
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
		$novo_ui_ordem = count($temas) + 1;
		}
	if ($novo_ui_ordem && ($novo_ui_ordem <= count($temas) + 1)) {
		$sql->adTabela('plano_gestao_tema');
		$sql->adAtualizar('tema_ordem', $novo_ui_ordem);
		$sql->adOnde('tema_id = '.(int)$tema_id);
		$sql->adOnde('pg_id='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		$idx = 1;
		foreach ($temas as $tema) {
			if ((int)$idx != (int)$novo_ui_ordem) {
				$sql->adTabela('plano_gestao_tema');
				$sql->adAtualizar('tema_ordem', $idx);
				$sql->adOnde('tema_id = '.(int)$tema['tema_id']);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx++;
				} 
			else {
				$sql->adTabela('plano_gestao_tema');
				$sql->adAtualizar('tema_ordem', $idx + 1);
				$sql->adOnde('tema_id = '.(int)$tema['tema_id']);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx = $idx + 2;
				}
			}		
		}
	$saida=atualizar_tema($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_temas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("mudar_ordem_tema");
	
function excluir_tema($tema_id, $pg_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_gestao_tema');
	$sql->adOnde('tema_id='.(int)$tema_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->exec();
	$sql->limpar();	
	$saida=atualizar_tema($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_temas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_tema");	









function inserir_objetivo($plano_gestao_objetivo_objetivo=null, $pg_id=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$sql->adTabela('plano_gestao_objetivo');
	$sql->adCampo('count(plano_gestao_objetivo_objetivo)');
	$sql->adOnde('plano_gestao_objetivo_plano_gestao ='.(int)$pg_id);	
	$sql->adOnde('plano_gestao_objetivo_objetivo ='.(int)$plano_gestao_objetivo_objetivo);	
  $existe = $sql->Resultado();
  $sql->limpar();
	
	
	if (!$existe){
	 	$sql->adTabela('plano_gestao_objetivo');
		$sql->adCampo('count(plano_gestao_objetivo_objetivo) AS soma');
		$sql->adOnde('plano_gestao_objetivo_plano_gestao ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('plano_gestao_objetivo');
		$sql->adInserir('plano_gestao_objetivo_plano_gestao', $pg_id);
		$sql->adInserir('plano_gestao_objetivo_objetivo', $plano_gestao_objetivo_objetivo);
		$sql->adInserir('plano_gestao_objetivo_ordem', $soma_total);
		$sql->exec();
		$sql->limpar();
		
		$saida=atualizar_objetivo($pg_id);
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_objetivos","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
	}
$xajax->registerFunction("inserir_objetivo");	

function atualizar_objetivo($pg_id=0){
	global $Aplic, $config;
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_objetivo');
	$sql->esqUnir('objetivo', 'objetivo', 'plano_gestao_objetivo_objetivo=objetivo.objetivo_id');
	$sql->adCampo('objetivo_nome, objetivo_cor, plano_gestao_objetivo_ordem, objetivo.objetivo_id');
	$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
	$sql->adOrdem('plano_gestao_objetivo_ordem ASC');
	$objetivos=$sql->Lista();
	
	if (count($objetivos)) $saida.='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th></th><th style="white-space: nowrap">'.ucfirst($config['objetivo']).'</th><th></th></tr>';
	foreach ($objetivos as $objetivo) {
		$saida.='<tr>';
		$saida.=' <td td style="white-space: nowrap" width="40" align="center">';
		$saida.=dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_objetivo('.$objetivo['plano_gestao_objetivo_ordem'].', '.$objetivo['objetivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_objetivo('.$objetivo['plano_gestao_objetivo_ordem'].', '.$objetivo['objetivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_objetivo('.$objetivo['plano_gestao_objetivo_ordem'].', '.$objetivo['objetivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_objetivo('.$objetivo['plano_gestao_objetivo_ordem'].', '.$objetivo['objetivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.='</td>';
		$saida.='<td>'.$objetivo['objetivo_nome'].'</td>';
		$saida.='<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_objetivo('.$objetivo['objetivo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		$saida.='</tr>';
		}
	if (count($objetivos)) $saida.='</table>';
	return $saida;	
	}
	

	
function mudar_ordem_objetivo($ordem, $plano_gestao_objetivo_objetivo, $direcao, $pg_id=0){
	$sql = new BDConsulta;
	$novo_ui_ordem = $ordem;
	$sql->adTabela('plano_gestao_objetivo');
	$sql->adOnde('plano_gestao_objetivo_objetivo != '.(int)$plano_gestao_objetivo_objetivo);
	$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
	$sql->adOrdem('plano_gestao_objetivo_ordem');
	$objetivos = $sql->Lista();
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
		$novo_ui_ordem = count($objetivos) + 1;
		}
	if ($novo_ui_ordem && ($novo_ui_ordem <= count($objetivos) + 1)) {
		$sql->adTabela('plano_gestao_objetivo');
		$sql->adAtualizar('plano_gestao_objetivo_ordem', $novo_ui_ordem);
		$sql->adOnde('plano_gestao_objetivo_objetivo = '.(int)$plano_gestao_objetivo_objetivo);
		$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		$idx = 1;
		foreach ($objetivos as $objetivo) {
			if ((int)$idx != (int)$novo_ui_ordem) {
				$sql->adTabela('plano_gestao_objetivo');
				$sql->adAtualizar('plano_gestao_objetivo_ordem', $idx);
				$sql->adOnde('plano_gestao_objetivo_objetivo = '.(int)$objetivo['plano_gestao_objetivo_objetivo']);
				$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx++;
				} 
			else {
				$sql->adTabela('plano_gestao_objetivo');
				$sql->adAtualizar('plano_gestao_objetivo_ordem', $idx + 1);
				$sql->adOnde('plano_gestao_objetivo_objetivo = '.(int)$objetivo['plano_gestao_objetivo_objetivo']);
				$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx = $idx + 2;
				}
			}		
		}
	$saida=atualizar_objetivo($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_objetivos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("mudar_ordem_objetivo");
	
function excluir_objetivo($plano_gestao_objetivo_objetivo, $pg_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_gestao_objetivo');
	$sql->adOnde('plano_gestao_objetivo_objetivo='.(int)$plano_gestao_objetivo_objetivo);
	$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
	$sql->exec();
	$sql->limpar();	
	$saida=atualizar_objetivo($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_objetivos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_objetivo");	












function inserir_me($plano_gestao_me_me=null, $pg_id=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$sql->adTabela('plano_gestao_me');
	$sql->adCampo('count(plano_gestao_me_me)');
	$sql->adOnde('plano_gestao_me_pg ='.(int)$pg_id);	
	$sql->adOnde('plano_gestao_me_me ='.(int)$plano_gestao_me_me);	
  $existe = $sql->Resultado();
  $sql->limpar();
	
	
	if (!$existe){
	 	$sql->adTabela('plano_gestao_me');
		$sql->adCampo('count(plano_gestao_me_me) AS soma');
		$sql->adOnde('plano_gestao_me_pg ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('plano_gestao_me');
		$sql->adInserir('plano_gestao_me_pg', $pg_id);
		$sql->adInserir('plano_gestao_me_me', $plano_gestao_me_me);
		$sql->adInserir('plano_gestao_me_ordem', $soma_total);
		$sql->exec();
		$sql->limpar();
		
		$saida=atualizar_me($pg_id);
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_mes","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
	}
$xajax->registerFunction("inserir_me");	

function atualizar_me($pg_id=0){
	global $Aplic, $config;
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_me');
	$sql->esqUnir('me', 'me', 'plano_gestao_me_me=me.me_id');
	$sql->adCampo('me_nome, me_cor, plano_gestao_me_ordem, me.me_id');
	$sql->adOnde('plano_gestao_me_pg='.(int)$pg_id);
	$sql->adOrdem('plano_gestao_me_ordem ASC');
	$mes=$sql->Lista();
	
	if (count($mes)) $saida.='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th></th><th style="white-space: nowrap">'.ucfirst($config['me']).'</th><th></th></tr>';
	foreach ($mes as $me) {
		$saida.='<tr>';
		$saida.=' <td td style="white-space: nowrap" width="40" align="center">';
		$saida.=dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_me('.$me['plano_gestao_me_ordem'].', '.$me['me_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_me('.$me['plano_gestao_me_ordem'].', '.$me['me_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_me('.$me['plano_gestao_me_ordem'].', '.$me['me_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_me('.$me['plano_gestao_me_ordem'].', '.$me['me_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.='</td>';
		$saida.='<td>'.$me['me_nome'].'</td>';
		$saida.='<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_me('.$me['me_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		$saida.='</tr>';
		}
	if (count($mes)) $saida.='</table>';
	return $saida;	
	}
	

	
function mudar_ordem_me($ordem, $plano_gestao_me_me, $direcao, $pg_id=0){
	$sql = new BDConsulta;
	$novo_ui_ordem = $ordem;
	$sql->adTabela('plano_gestao_me');
	$sql->adOnde('plano_gestao_me_me != '.(int)$plano_gestao_me_me);
	$sql->adOnde('plano_gestao_me_pg='.(int)$pg_id);
	$sql->adOrdem('plano_gestao_me_ordem');
	$mes = $sql->Lista();
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
		$novo_ui_ordem = count($mes) + 1;
		}
	if ($novo_ui_ordem && ($novo_ui_ordem <= count($mes) + 1)) {
		$sql->adTabela('plano_gestao_me');
		$sql->adAtualizar('plano_gestao_me_ordem', $novo_ui_ordem);
		$sql->adOnde('plano_gestao_me_me = '.(int)$plano_gestao_me_me);
		$sql->adOnde('plano_gestao_me_pg='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		$idx = 1;
		foreach ($mes as $me) {
			if ((int)$idx != (int)$novo_ui_ordem) {
				$sql->adTabela('plano_gestao_me');
				$sql->adAtualizar('plano_gestao_me_ordem', $idx);
				$sql->adOnde('plano_gestao_me_me = '.(int)$me['plano_gestao_me_me']);
				$sql->adOnde('plano_gestao_me_pg='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx++;
				} 
			else {
				$sql->adTabela('plano_gestao_me');
				$sql->adAtualizar('plano_gestao_me_ordem', $idx + 1);
				$sql->adOnde('plano_gestao_me_me = '.(int)$me['plano_gestao_me_me']);
				$sql->adOnde('plano_gestao_me_pg='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx = $idx + 2;
				}
			}		
		}
	$saida=atualizar_me($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_mes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("mudar_ordem_me");
	
function excluir_me($plano_gestao_me_me, $pg_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_gestao_me');
	$sql->adOnde('plano_gestao_me_me='.(int)$plano_gestao_me_me);
	$sql->adOnde('plano_gestao_me_pg='.(int)$pg_id);
	$sql->exec();
	$sql->limpar();	
	$saida=atualizar_me($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_mes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_me");	








function inserir_fator($plano_gestao_fator_fator=null, $pg_id=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$sql->adTabela('plano_gestao_fator');
	$sql->adCampo('count(plano_gestao_fator_fator)');
	$sql->adOnde('plano_gestao_fator_plano_gestao ='.(int)$pg_id);	
	$sql->adOnde('plano_gestao_fator_fator ='.(int)$plano_gestao_fator_fator);	
  $existe = $sql->Resultado();
  $sql->limpar();
	
	
	if (!$existe){
	 	$sql->adTabela('plano_gestao_fator');
		$sql->adCampo('count(plano_gestao_fator_fator) AS soma');
		$sql->adOnde('plano_gestao_fator_plano_gestao ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('plano_gestao_fator');
		$sql->adInserir('plano_gestao_fator_plano_gestao', $pg_id);
		$sql->adInserir('plano_gestao_fator_fator', $plano_gestao_fator_fator);
		$sql->adInserir('plano_gestao_fator_ordem', $soma_total);
		$sql->exec();
		$sql->limpar();
		
		$saida=atualizar_fator($pg_id);
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_fators","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
	}
$xajax->registerFunction("inserir_fator");	

function atualizar_fator($pg_id=0){
	global $Aplic, $config;
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_fator');
	$sql->esqUnir('fator', 'fator', 'plano_gestao_fator_fator=fator.fator_id');
	$sql->adCampo('fator_nome, fator_cor, plano_gestao_fator_ordem, fator.fator_id');
	$sql->adOnde('plano_gestao_fator_plano_gestao='.(int)$pg_id);
	$sql->adOrdem('plano_gestao_fator_ordem ASC');
	$fators=$sql->Lista();
	
	if (count($fators)) $saida.='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th></th><th style="white-space: nowrap">'.ucfirst($config['fator']).'</th><th></th></tr>';
	foreach ($fators as $fator) {
		$saida.='<tr>';
		$saida.=' <td td style="white-space: nowrap" width="40" align="center">';
		$saida.=dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_fator('.$fator['plano_gestao_fator_ordem'].', '.$fator['fator_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_fator('.$fator['plano_gestao_fator_ordem'].', '.$fator['fator_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_fator('.$fator['plano_gestao_fator_ordem'].', '.$fator['fator_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_fator('.$fator['plano_gestao_fator_ordem'].', '.$fator['fator_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.='</td>';
		$saida.='<td>'.$fator['fator_nome'].'</td>';
		$saida.='<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_fator('.$fator['fator_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		$saida.='</tr>';
		}
	if (count($fators)) $saida.='</table>';
	return $saida;	
	}
	

	
function mudar_ordem_fator($ordem, $plano_gestao_fator_fator, $direcao, $pg_id=0){
	$sql = new BDConsulta;
	$novo_ui_ordem = $ordem;
	$sql->adTabela('plano_gestao_fator');
	$sql->adOnde('plano_gestao_fator_fator != '.(int)$plano_gestao_fator_fator);
	$sql->adOnde('plano_gestao_fator_plano_gestao='.(int)$pg_id);
	$sql->adOrdem('plano_gestao_fator_ordem');
	$fators = $sql->Lista();
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
		$novo_ui_ordem = count($fators) + 1;
		}
	if ($novo_ui_ordem && ($novo_ui_ordem <= count($fators) + 1)) {
		$sql->adTabela('plano_gestao_fator');
		$sql->adAtualizar('plano_gestao_fator_ordem', $novo_ui_ordem);
		$sql->adOnde('plano_gestao_fator_fator = '.(int)$plano_gestao_fator_fator);
		$sql->adOnde('plano_gestao_fator_plano_gestao='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		$idx = 1;
		foreach ($fators as $fator) {
			if ((int)$idx != (int)$novo_ui_ordem) {
				$sql->adTabela('plano_gestao_fator');
				$sql->adAtualizar('plano_gestao_fator_ordem', $idx);
				$sql->adOnde('plano_gestao_fator_fator = '.(int)$fator['plano_gestao_fator_fator']);
				$sql->adOnde('plano_gestao_fator_plano_gestao='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx++;
				} 
			else {
				$sql->adTabela('plano_gestao_fator');
				$sql->adAtualizar('plano_gestao_fator_ordem', $idx + 1);
				$sql->adOnde('plano_gestao_fator_fator = '.(int)$fator['plano_gestao_fator_fator']);
				$sql->adOnde('plano_gestao_fator_plano_gestao='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx = $idx + 2;
				}
			}		
		}
	$saida=atualizar_fator($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_fators","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("mudar_ordem_fator");
	
function excluir_fator($plano_gestao_fator_fator, $pg_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_gestao_fator');
	$sql->adOnde('plano_gestao_fator_fator='.(int)$plano_gestao_fator_fator);
	$sql->adOnde('plano_gestao_fator_plano_gestao='.(int)$pg_id);
	$sql->exec();
	$sql->limpar();	
	$saida=atualizar_fator($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_fators","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_fator");	








function inserir_estrategia($pg_estrategia_id=null, $pg_id=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$sql->adTabela('plano_gestao_estrategias');
	$sql->adCampo('count(pg_estrategia_id)');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
	$sql->adOnde('pg_estrategia_id ='.(int)$pg_estrategia_id);	
  $existe = $sql->Resultado();
  $sql->limpar();
	
	
	if (!$existe){
	 	$sql->adTabela('plano_gestao_estrategias');
		$sql->adCampo('count(pg_estrategia_id) AS soma');
		$sql->adOnde('pg_id ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('plano_gestao_estrategias');
		$sql->adInserir('pg_id', $pg_id);
		$sql->adInserir('pg_estrategia_id', $pg_estrategia_id);
		$sql->adInserir('pg_estrategia_ordem', $soma_total);
		$sql->exec();
		$sql->limpar();
		
		$saida=atualizar_estrategia($pg_id);
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_estrategias","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
	}
$xajax->registerFunction("inserir_estrategia");	

function atualizar_estrategia($pg_id=0){
	global $Aplic, $config;
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_estrategias');
	$sql->esqUnir('estrategias', 'estrategias', 'plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adCampo('pg_estrategia_nome, pg_estrategia_cor, plano_gestao_estrategias.pg_estrategia_ordem, estrategias.pg_estrategia_id');
	$sql->adOnde('plano_gestao_estrategias.pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_estrategia_ordem ASC');
	$estrategias=$sql->Lista();
	
	if (count($estrategias)) $saida.='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th></th><th style="white-space: nowrap">'.ucfirst($config['iniciativa']).'</th><th></th></tr>';
	foreach ($estrategias as $estrategia) {
		$saida.='<tr>';
		$saida.=' <td td style="white-space: nowrap" width="40" align="center">';
		$saida.=dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_estrategia('.$estrategia['pg_estrategia_ordem'].', '.$estrategia['pg_estrategia_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_estrategia('.$estrategia['pg_estrategia_ordem'].', '.$estrategia['pg_estrategia_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_estrategia('.$estrategia['pg_estrategia_ordem'].', '.$estrategia['pg_estrategia_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_estrategia('.$estrategia['pg_estrategia_ordem'].', '.$estrategia['pg_estrategia_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.='</td>';
		$saida.='<td>'.$estrategia['pg_estrategia_nome'].'</td>';
		$saida.='<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_estrategia('.$estrategia['pg_estrategia_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		$saida.='</tr>';
		}
	if (count($estrategias)) $saida.='</table>';
	return $saida;	
	}
	

	
function mudar_ordem_estrategia($ordem, $pg_estrategia_id, $direcao, $pg_id=0){
	$sql = new BDConsulta;
	$novo_ui_ordem = $ordem;
	$sql->adTabela('plano_gestao_estrategias');
	$sql->adOnde('pg_estrategia_id != '.(int)$pg_estrategia_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_estrategia_ordem');
	$estrategias = $sql->Lista();
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
		$novo_ui_ordem = count($estrategias) + 1;
		}
	if ($novo_ui_ordem && ($novo_ui_ordem <= count($estrategias) + 1)) {
		$sql->adTabela('plano_gestao_estrategias');
		$sql->adAtualizar('pg_estrategia_ordem', $novo_ui_ordem);
		$sql->adOnde('pg_estrategia_id = '.(int)$pg_estrategia_id);
		$sql->adOnde('pg_id='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		$idx = 1;
		foreach ($estrategias as $estrategia) {
			if ((int)$idx != (int)$novo_ui_ordem) {
				$sql->adTabela('plano_gestao_estrategias');
				$sql->adAtualizar('pg_estrategia_ordem', $idx);
				$sql->adOnde('pg_estrategia_id = '.(int)$estrategia['pg_estrategia_id']);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx++;
				} 
			else {
				$sql->adTabela('plano_gestao_estrategias');
				$sql->adAtualizar('pg_estrategia_ordem', $idx + 1);
				$sql->adOnde('pg_estrategia_id = '.(int)$estrategia['pg_estrategia_id']);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx = $idx + 2;
				}
			}		
		}
	$saida=atualizar_estrategia($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_estrategias","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("mudar_ordem_estrategia");
	
function excluir_estrategia($pg_estrategia_id, $pg_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_gestao_estrategias');
	$sql->adOnde('pg_estrategia_id='.(int)$pg_estrategia_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->exec();
	$sql->limpar();	
	$saida=atualizar_estrategia($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_estrategias","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_estrategia");	






function inserir_meta($pg_meta_id=null, $pg_id=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$sql->adTabela('plano_gestao_metas');
	$sql->adCampo('count(pg_meta_id)');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
	$sql->adOnde('pg_meta_id ='.(int)$pg_meta_id);	
  $existe = $sql->Resultado();
  $sql->limpar();
	
	
	if (!$existe){
	 	$sql->adTabela('plano_gestao_metas');
		$sql->adCampo('count(pg_meta_id) AS soma');
		$sql->adOnde('pg_id ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('plano_gestao_metas');
		$sql->adInserir('pg_id', $pg_id);
		$sql->adInserir('pg_meta_id', $pg_meta_id);
		$sql->adInserir('pg_meta_ordem', $soma_total);
		$sql->exec();
		$sql->limpar();
		
		$saida=atualizar_meta($pg_id);
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_metas","innerHTML", utf8_encode($saida));
		return $objResposta;
		}
	}
$xajax->registerFunction("inserir_meta");	

function atualizar_meta($pg_id=0){
	global $Aplic, $config;
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_metas');
	$sql->esqUnir('metas', 'metas', 'plano_gestao_metas.pg_meta_id=metas.pg_meta_id');
	$sql->adCampo('pg_meta_nome, pg_meta_cor, plano_gestao_metas.pg_meta_ordem, metas.pg_meta_id');
	$sql->adOnde('plano_gestao_metas.pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_meta_ordem ASC');
	$metas=$sql->Lista();
	
	if (count($metas)) $saida.='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th></th><th style="white-space: nowrap">'.ucfirst($config['meta']).'</th><th></th></tr>';
	foreach ($metas as $meta) {
		$saida.='<tr>';
		$saida.=' <td td style="white-space: nowrap" width="40" align="center">';
		$saida.=dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_meta('.$meta['pg_meta_ordem'].', '.$meta['pg_meta_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_meta('.$meta['pg_meta_ordem'].', '.$meta['pg_meta_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_meta('.$meta['pg_meta_ordem'].', '.$meta['pg_meta_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.=dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_meta('.$meta['pg_meta_ordem'].', '.$meta['pg_meta_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.='</td>';
		$saida.='<td>'.$meta['pg_meta_nome'].'</td>';
		$saida.='<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_meta('.$meta['pg_meta_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		$saida.='</tr>';
		}
	if (count($metas)) $saida.='</table>';
	return $saida;	
	}
	

	
function mudar_ordem_meta($ordem, $pg_meta_id, $direcao, $pg_id=0){
	$sql = new BDConsulta;
	$novo_ui_ordem = $ordem;
	$sql->adTabela('plano_gestao_metas');
	$sql->adOnde('pg_meta_id != '.(int)$pg_meta_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->adOrdem('pg_meta_ordem');
	$metas = $sql->Lista();
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
		$novo_ui_ordem = count($metas) + 1;
		}
	if ($novo_ui_ordem && ($novo_ui_ordem <= count($metas) + 1)) {
		$sql->adTabela('plano_gestao_metas');
		$sql->adAtualizar('pg_meta_ordem', $novo_ui_ordem);
		$sql->adOnde('pg_meta_id = '.(int)$pg_meta_id);
		$sql->adOnde('pg_id='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		$idx = 1;
		foreach ($metas as $meta) {
			if ((int)$idx != (int)$novo_ui_ordem) {
				$sql->adTabela('plano_gestao_metas');
				$sql->adAtualizar('pg_meta_ordem', $idx);
				$sql->adOnde('pg_meta_id = '.(int)$meta['pg_meta_id']);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx++;
				} 
			else {
				$sql->adTabela('plano_gestao_metas');
				$sql->adAtualizar('pg_meta_ordem', $idx + 1);
				$sql->adOnde('pg_meta_id = '.(int)$meta['pg_meta_id']);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				$idx = $idx + 2;
				}
			}		
		}
	$saida=atualizar_meta($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_metas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("mudar_ordem_meta");
	
function excluir_meta($pg_meta_id, $pg_id=0){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_gestao_metas');
	$sql->adOnde('pg_meta_id='.(int)$pg_meta_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	$sql->exec();
	$sql->limpar();	
	$saida=atualizar_meta($pg_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_metas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_meta");	



$xajax->processRequest();

?>