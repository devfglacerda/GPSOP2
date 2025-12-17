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
transforma_vazio_em_nulo($_REQUEST);
$del=getParam($_REQUEST, 'del', 0);
$obj = new CCia();
$msg = '';
$cia_id=getParam($_REQUEST, 'cia_id', null);
$sql= new BDConsulta;
$_REQUEST['cia_ativo']=(isset($_REQUEST['cia_ativo']) ? 1 : 0);
require_once BASE_DIR.'/modulos/projetos/projetos.class.php';

if ($del) {
	if (!$podeExcluir) $Aplic->redirecionar('m=publico&a=acesso_negado');
	//excluir cia
	$sql->adTabela('depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('dept_cia='.(int)$cia_id);
	$depts=$sql->Lista();
	$sql->limpar();
	
	foreach($depts AS $dept){
		$sql->setExcluir('dept_contatos');
		$sql->adOnde('dept_contato_dept='.$dept['dept_id']);
		if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela dept_contatos!'.$bd->stderr(true));
		$sql->limpar();
		}
	
	$sql->setExcluir('depts');
	$sql->adOnde('dept_cia='.(int)$cia_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela depts!'.$bd->stderr(true));
	$sql->limpar();
	
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_id');
	$sql->adOnde('projeto_cia='.(int)$cia_id);
	$projetos=$sql->Lista();
	$sql->limpar();

	foreach($projetos AS $projeto){
		$obj = new CProjeto();
		$podeExcluir = $obj->podeExcluir($msg, $projeto['projeto_id']);
		if (!$podeExcluir) {
			$Aplic->setMsg($msg, UI_MSG_ERRO);
			$Aplic->redirecionar('m=cias&a=index');
			}
		if (($msg = $obj->excluir())) {
			$Aplic->setMsg($msg, UI_MSG_ERRO);
			$Aplic->redirecionar('m=cias&a=index');
			} 
		}
	$sql->setExcluir('cias');
	$sql->adOnde('cia_id='.(int)$cia_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela depts!'.$bd->stderr(true));
	$sql->limpar();
		
	///fazer depois exclusão de plano de gestão
	$Aplic->setMsg(ucfirst($config['organizacao']).' excluída', UI_MSG_OK);
	$Aplic->redirecionar('m=cias&a=index');
	} 
elseif ($cia_id) {
	$obj = New CCia();
	$obj->load($cia_id);
	if ($cia_id && !($podeEditar && permiteEditarCia($obj->cia_acesso, $cia_id) && ($Aplic->usuario_super_admin || ($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin)))) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
else{
	if (!($podeAdicionar && $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
	//garantir que não haja cia_superior vazia
	if (!$_REQUEST['cia_superior']){
		$sql->adTabela('cias');
		$sql->adCampo('cia_superior');
		$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
		$_REQUEST['cia_superior']=$sql->resultado();
		$sql->limpar();
		}
	}

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=cias&a=index');
	}

$Aplic->setMsg(ucfirst($config['organizacao']));
if ($del) {
	if (!$obj->podeExcluir($msg)) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=cias&a=index');
		}
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=cias&a=index');
		} 
	else {
		$Aplic->setMsg(ucfirst($config['organizacao']).' excluída', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=cias&a=index');
		}
	} 
else {
	if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		$Aplic->setMsg($_REQUEST['cia_id'] ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
		}
		
	//garantir que não haja duas organizações com cia_id=cia_superior
	$sql->adTabela('cias');
	$sql->adCampo('count(cia_id)');
	$sql->adOnde('cia_id=cia_superior');
	$qnt=$sql->resultado();
	$sql->limpar();
	if ($qnt > 1) {
		//define que a cia de ID mais baixo é a DE FATO cia superior e as demais apontem para ela
		$sql->adTabela('cias');
		$sql->adCampo('cia_id');
		$sql->adOnde('cia_id=cia_superior');
		$sql->adOrdem('cia_id ASC');
		$cia_superior=$sql->Resultado();
		$sql->limpar();
		$sql->adTabela('cias');
		$sql->adAtualizar('cia_superior', (int)$cia_superior);
		$sql->adOnde('cia_id=cia_superior');
		$sql->adOnde('cia_id!='.(int)$cia_superior);
		$sql->exec();
		$sql->limpar();
		}	
		
	$Aplic->redirecionar('m=cias&a=ver&cia_id='.(int)$obj->cia_id);
	}
$Aplic->redirecionar('m=cias');	
?>