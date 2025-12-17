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

	
function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento, false);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	$qnt=0;
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento, true);
	$corpo='<table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
	foreach($vetor as $nd_id => $valor) if ($valor!='Retornar a lista de elementos de despesa' && $valor) {
		$corpo.='<tr><td width=16><a href="javascript: void(0);" onclick="retornar(\''.$nd_id.'\', \''.$valor.'\');">'.$valor.'</td></tr>';
		$qnt++;
		}
	if (!$qnt) $corpo.='<tr><td>Nenhum dado</td></tr>';
	$corpo.='</table>';
	$objResposta->assign('corpo',"innerHTML", utf8_encode($corpo));
	
	
	return $objResposta;
	}	

$xajax->registerFunction("mudar_nd_ajax");
	
	
	
$xajax->processRequest();
?>