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

$obj = new CContato();
$msg = '';
$nao_eh_novo=getParam($_REQUEST, 'contato_id', null);
$del=getParam($_REQUEST, 'del', 0);
$excluir_foto=getParam($_REQUEST, 'excluir_foto', 0);
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

$_REQUEST['contato_ativo']=(isset($_REQUEST['contato_ativo']) && $_REQUEST['contato_ativo'] ? 1 : 0);
$_REQUEST['contato_vivo']=(isset($_REQUEST['contato_vivo']) && $_REQUEST['contato_vivo'] ? 1 : 0);



if ($del && !$Aplic->checarModulo('contatos', 'excluir')) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($nao_eh_novo && !$Aplic->checarModulo('contatos', 'editar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$nao_eh_novo && !$Aplic->checarModulo('contatos', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$notificarSolicitado=getParam($_REQUEST, 'contato_atualizarSolicitado', 0);

if ($notificarSolicitado != 0) $notificarSolicitado = 1;

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=contatos&a=index');
	}
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$del=getParam($_REQUEST, 'del', 0);
$Aplic->setMsg('Contatos');

if ($del) {
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=contatos&a=index');
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=contatos');
		}
	} 
else {
	if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		$campos_customizados = new CampoCustomizados($m, $obj->contato_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$sql = $campos_customizados->armazenar($obj->contato_id);
		$chave_atual = $obj->getChaveAtualizada();
		if ($notificarSolicitado && !$chave_atual) {
			$agora = new CData(date("Y-m-d H:i:s"));
			$obj->contato_chave_atualizacao = MD5($agora->format('%Y%m%dT%H%M%S'));
			$obj->contato_pedido_atualizacao = $agora->format('%Y-%m-%d %H:%M:%S');
			$obj->contato_ultima_atualizacao = '';
			$obj->atualizarNotificar();
			} 
		elseif ($notificarSolicitado && $chave_atual) {
			//nada?
			} 
		else $obj->contato_chave_atualizacao = '';
		$Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
		}
	
	
	if ($excluir_foto){
		
		if ($obj->contato_foto) @unlink($base_dir.'/arquivos/contatos/'.$obj->contato_foto);
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->adAtualizar('contato_foto', null);
		$sql->adOnde('contato_id = '.(int)$obj->contato_id);
		$retorno=$sql->exec();
		$sql->limpar();
		}
	
	//acrescentar foto
	if (isset($_FILES['logo']['name']) && file_exists($_FILES['logo']['tmp_name']) && !empty($_FILES['logo']['tmp_name'])){
		
		if($_FILES['logo']['size']<353600){
			//apagar antigo
			if ($obj->contato_foto) @unlink($base_dir.'/arquivos/contatos/'.$obj->contato_foto);
			$caminho = $obj->contato_id.'/'.$_FILES['logo']['name'];
			$caminho_completo = $base_dir.'/arquivos/contatos/'.$caminho;
			if (!is_dir($base_dir.'/arquivos/contatos/'.$obj->contato_id)){
					$res = mkdir($base_dir.'/arquivos/contatos/'.$obj->contato_id, 0777);
					if (!$res) {
						$Aplic->setMsg('A pasta para '.$config['genero_contato'].' '.$config['contato'].' não foi configurada para receber arquivos - mude as permissões em arquivos\contatos.', UI_MSG_ALERTA);
						return false;
						}
					}
					
			move_uploaded_file($_FILES['logo']['tmp_name'], $caminho_completo);
			$sql = new BDConsulta;
			$sql->adTabela('contatos');
			$sql->adAtualizar('contato_foto', $caminho);
			$sql->adOnde('contato_id = '.(int)$obj->contato_id);
			$retorno=$sql->exec();
			$sql->limpar();
			}
		else ver2('O tamanho máximo permitido é de 350KB!  Enviou foto 3x4 de '.arquivo_tamanho((int)$_FILES['logo']['size']));
		}
	
	$Aplic->redirecionar('m=contatos&a=ver&contato_id='.$obj->contato_id);
	}
$Aplic->redirecionar('m=contatos');	
?>