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

function criar_licao($nome='', $licao_id_antigo=null){
	global $bd;
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	
	$sql->adTabela('licao');
	$sql->adCampo('licao.*');
	$sql->adOnde('licao_id = '.(int)$licao_id_antigo);
  $antigo = $sql->linha();
  $sql->limpar();
	$sql->adTabela('licao');
	foreach($antigo as $campo => $valor)	if ($campo!='licao_id' && $campo!='licao_nome') $sql->adInserir($campo, $valor);
	$sql->adInserir('licao_nome', $nome);
	$sql->exec();
	$licao_id=$bd->Insert_ID('licao','licao_id');
	$sql->limpar();
	
	$sql->adTabela('licao_cia');
	$sql->adCampo('licao_cia_cia');
	$sql->adOnde('licao_cia_licao = '.(int)$licao_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
	foreach($lista as $valor){
		$sql->adTabela('licao_cia');
		$sql->adInserir('licao_cia_cia', $valor);
		$sql->adInserir('licao_cia_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}	
		
	$sql->adTabela('licao_dept');
	$sql->adCampo('licao_dept_dept');
	$sql->adOnde('licao_dept_licao = '.(int)$licao_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
	foreach($lista as $valor){
		$sql->adTabela('licao_dept');
		$sql->adInserir('licao_dept_dept', $valor);
		$sql->adInserir('licao_dept_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}	
	
	$sql->adTabela('licao_gestao');
	$sql->adCampo('licao_gestao.*');
	$sql->adOnde('licao_gestao_licao = '.(int)$licao_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
	foreach($lista as $linha){
		$sql->adTabela('licao_gestao');
		foreach($linha as $campo => $valor)	if ($campo!='licao_gestao_id' && $campo!='licao_gestao_licao') $sql->adInserir($campo, $valor);
		$sql->adInserir('licao_gestao_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}
	
	$sql->adTabela('licao_usuario');
	$sql->adCampo('licao_usuario_usuario');
	$sql->adOnde('licao_usuario_licao = '.(int)$licao_id_antigo);
  $lista=$sql->carregarColuna();
  $sql->limpar();
	foreach($lista as $valor){
		$sql->adTabela('licao_usuario');
		$sql->adInserir('licao_usuario_usuario', $valor);
		$sql->adInserir('licao_usuario_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}	
	
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura.*');
	$sql->adOnde('assinatura_licao = '.(int)$licao_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
	foreach($lista as $linha){
		$sql->adTabela('assinatura');
		foreach($linha as $campo => $valor)	if ($campo!='assinatura_id' && $campo!='assinatura_licao' && $campo!='assinatura_data' && $campo!='assinatura_aprovou' && $campo!='assinatura_observacao' && $campo!='assinatura_atesta_opcao') $sql->adInserir($campo, $valor);
		$sql->adInserir('assinatura_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}
	
	$sql->adTabela('priorizacao');
	$sql->adCampo('priorizacao.*');
	$sql->adOnde('priorizacao_licao = '.(int)$licao_id_antigo);
  $lista=$sql->lista();
  $sql->limpar();
	foreach($lista as $linha){
		$sql->adTabela('priorizacao');
		foreach($linha as $campo => $valor)	if ($campo!='priorizacao_id' && $campo!='priorizacao_licao') $sql->adInserir($campo, $valor);
		$sql->adInserir('priorizacao_licao', $licao_id);
		$sql->exec();
		$sql->limpar();
		}
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("licao_criado","value", $licao_id);
	return $objResposta;
	
	}
$xajax->registerFunction("criar_licao");


function licao_existe($nome='', $licao_id=null, $cia_id=null){
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	$sql->adTabela('licao');
	$sql->adCampo('count(licao_id)');
	$sql->adOnde('licao_nome = \''.$nome.'\'');
	if ($cia_id) $sql->adOnde('licao_cia='.(int)$cia_id);
	if ($licao_id) $sql->adOnde('licao_id!='.(int)$licao_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_licao","value", $existe);
	return $objResposta;
	}
$xajax->registerFunction("licao_existe");


$xajax->processRequest();

?>