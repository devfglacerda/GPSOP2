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

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function mudar_percentagem_item_plano($plano_acao_item_id=null, $plano_acao_item_percentagem=null){
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	$sql->adAtualizar('plano_acao_item_percentagem', $plano_acao_item_percentagem);
	$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	}
$xajax->registerFunction("mudar_percentagem_item_plano");

$corItemPlano = getSisValor('StatusAcaoPlanoCor');

function mudar_status_item_plano($plano_acao_item_id=null, $plano_acao_item_status=null){
	global $corItemPlano;
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	$sql->adAtualizar('plano_acao_item_status', $plano_acao_item_status);
	$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	if (isset($corItemPlano[$plano_acao_item_status])){
		$objResposta = new xajaxResponse();
		$objResposta->assign('status_item_plano_'.$plano_acao_item_id,'style.backgroundColor', '#'.$corItemPlano[$plano_acao_item_status]);
		return $objResposta;
		}
	}
$xajax->registerFunction("mudar_status_item_plano");

function ver_obs_item_plano($plano_acao_item_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	$sql->adCampo('plano_acao_item_observacao');
	$sql->adOnde('plano_acao_item_id='.(int)$plano_acao_item_id);
	$obs=$sql->Resultado();
	$sql->limpar();
	$objResposta->assign("obs_item_plano_".$plano_acao_item_id,"innerHTML", utf8_encode($obs));	
	return $objResposta;
	}	
$xajax->registerFunction("ver_obs_item_plano");	


$xajax->processRequest();

?>