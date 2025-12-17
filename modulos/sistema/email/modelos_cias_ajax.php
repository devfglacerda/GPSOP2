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

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	global $config;
	
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	
	$saida2='';
	$sql = new BDConsulta;
	$sql->adTabela('modelos_tipo');
	$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, descricao');
	$sql->adOnde('organizacao='.(int)$config['militar']);
	$tipos=$sql->lista();
	$sql->limpar();
	
	$sql->adTabela('modelo_cia');
	$sql->adCampo('modelo_cia_tipo');
	$sql->adOnde('modelo_cia_cia='.(int)$cia_id);
	$selecionados=$sql->carregarColuna();
	$sql->limpar();
	$saida2.= '<table cellspacing=0 cellpadding=0 class="tbl1">';
	$saida2.= '<tr><th></th><th>Nome</th><th>Descrição</th></tr>';
	foreach($tipos as $linha){
		$saida2.= '<tr><td><input type="checkbox" value="1" name="priorizacao_'.$linha['modelo_tipo_id'].'" id="priorizacao_'.$linha['modelo_tipo_id'].'" '.(in_array($linha['modelo_tipo_id'], $selecionados) ? 'checked="checked"' : '').' onchange="mudar('.$linha['modelo_tipo_id'].')" /></td><td align=left>'.$linha['modelo_tipo_nome'].'</td><td align=left>'.$linha['descricao'].'</td></tr>';
		}
	$saida2.= '</table>';	

	$objResposta->assign('combo_tabela',"innerHTML", utf8_encode($saida2));
	
	
	return $objResposta;
	}
	
$xajax->registerFunction("selecionar_om_ajax");	


function mudar($cia_id=1, $modelo_tipo_id, $inserir=false){
	$sql = new BDConsulta;
	$sql->setExcluir('modelo_cia');
	$sql->adOnde('modelo_cia_cia = '.(int)$cia_id);
	$sql->adOnde('modelo_cia_tipo = '.(int)$modelo_tipo_id);
	$sql->exec();
	$sql->limpar();

	if ($inserir){
		$sql->adTabela('modelo_cia');
		$sql->adInserir('modelo_cia_cia', (int)$cia_id);
		$sql->adInserir('modelo_cia_tipo', (int)$modelo_tipo_id);
		$sql->exec();
		$sql->limpar();
		}

	}
	
$xajax->registerFunction("mudar");	


$xajax->processRequest();

?>