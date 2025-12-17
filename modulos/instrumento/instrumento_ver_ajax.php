<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
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


function mudar_percentagem($instrumento_id=null, $instrumento_porcentagem=null){
	$sql = new BDConsulta;
	$sql->adTabela('instrumento');
	$sql->adAtualizar('instrumento_porcentagem', $instrumento_porcentagem);
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$sql->exec();
	$sql->limpar();
	}
$xajax->registerFunction("mudar_percentagem");

$cor = getSisValor('SituacaoInstrumentoCor');

function mudar_status($instrumento_id=null, $instrumento_situacao=null){
	global $cor;
	$sql = new BDConsulta;
	$sql->adTabela('instrumento');
	$sql->adAtualizar('instrumento_situacao', $instrumento_situacao);
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$sql->exec();
	$sql->limpar();
	if (isset($cor[$instrumento_situacao])){
		$objResposta = new xajaxResponse();
		$objResposta->assign('status_'.$instrumento_id,'style.backgroundColor', '#'.$cor[$instrumento_situacao]);
		return $objResposta;
		}
	}
$xajax->registerFunction("mudar_status");





$xajax->processRequest();
?>