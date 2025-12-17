<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

require_once '../base.php';
require_once BASE_DIR.'/config.php';
require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/ui.class.php';
require_once BASE_DIR.'/classes/evento_recorrencia.class.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once (BASE_DIR.'/estilo/rondon/sobrecarga.php');
global $config, $bd;





	
$sql = new BDConsulta;


$sql->adTabela('tarefa_log');	
$sql->adCampo('tarefa_log.*');
$lista = $sql->lista();
$sql->limpar();
$novo_id=array();
foreach($lista AS $linha) {
	$sql->adTabela('log');
	$sql->adInserir('log_tarefa', $linha['tarefa_log_tarefa']);
	$sql->adInserir('log_criador', $linha['tarefa_log_criador']);
	//$sql->adInserir('log_correcao', $linha['tarefa_log_correcao']);
	$sql->adInserir('log_nome', $linha['tarefa_log_nome']);
	$sql->adInserir('log_descricao', $linha['tarefa_log_descricao']);
	$sql->adInserir('log_horas', $linha['tarefa_log_horas']);
	$sql->adInserir('log_data', $linha['tarefa_log_data']);
	$sql->adInserir('log_custo', $linha['tarefa_log_custo']);
	$sql->adInserir('log_nd', $linha['tarefa_log_nd']);
	$sql->adInserir('log_categoria_economica', $linha['tarefa_log_categoria_economica']);
	$sql->adInserir('log_grupo_despesa', $linha['tarefa_log_grupo_despesa']);
	$sql->adInserir('log_modalidade_aplicacao', $linha['tarefa_log_modalidade_aplicacao']);
	$sql->adInserir('log_corrigir', $linha['tarefa_log_problema']);
	$sql->adInserir('log_tipo_problema', $linha['tarefa_log_tipo_problema']);
	$sql->adInserir('log_referencia', $linha['tarefa_log_referencia']);
	$sql->adInserir('log_url_relacionada', $linha['tarefa_log_url_relacionada']);
	$sql->adInserir('log_reg_mudanca_inicio', $linha['tarefa_log_reg_mudanca_inicio']);
	$sql->adInserir('log_reg_mudanca_fim', $linha['tarefa_log_reg_mudanca_fim']);
	$sql->adInserir('log_reg_mudanca_duracao', $linha['tarefa_log_reg_mudanca_duracao']);
	$sql->adInserir('log_reg_mudanca_percentagem', $linha['tarefa_log_reg_mudanca_percentagem']);
	$sql->adInserir('log_reg_mudanca_realizado', $linha['tarefa_log_reg_mudanca_realizado']);
	$sql->adInserir('log_reg_mudanca_status', $linha['tarefa_log_reg_mudanca_status']);
	$sql->adInserir('log_acesso', $linha['tarefa_log_acesso']);
	$sql->adInserir('log_aprovou', $linha['tarefa_log_aprovou']);
	$sql->adInserir('log_aprovado', $linha['tarefa_log_aprovado']);
	$sql->adInserir('log_data_aprovado', $linha['tarefa_log_data_aprovado']);
	$sql->exec();
	$log_id=$bd->Insert_ID('log','log_id');
	$sql->limpar();
	$novo_id[$linha['tarefa_log_id']]=$log_id;
	}	
foreach($lista AS $linha) {
	if ($linha['tarefa_log_correcao'] && isset($novo_id[$linha['tarefa_log_correcao']]) && isset($novo_id[$linha['tarefa_log_id']])){
		$sql->adTabela('log');
		$sql->adAtualizar('log_correcao', $novo_id[$linha['tarefa_log_correcao']]);
		$sql->adOnde('log_id='.(int)$novo_id[$linha['tarefa_log_id']]);
		$sql->exec();
		$sql->limpar();
		}
	}	






$sql->adTabela('tarefa_log_arquivo');	
$sql->adCampo('tarefa_log_arquivo.*');
$lista = $sql->lista();
$sql->limpar();	

foreach($lista AS $linha) {
	if (isset($novo_id[$linha['tarefa_log_arquivo_tarefa_log_id']])){
		$sql->adTabela('log_arquivo');
		$sql->adInserir('log_arquivo_log', $novo_id[$linha['tarefa_log_arquivo_tarefa_log_id']]);
		$sql->adInserir('log_arquivo_usuario', $linha['tarefa_log_arquivo_usuario']);
		$sql->adInserir('log_arquivo_ordem', $linha['tarefa_log_arquivo_ordem']);
		$sql->adInserir('log_arquivo_endereco', $linha['tarefa_log_arquivo_endereco']);
		$sql->adInserir('log_arquivo_data', $linha['tarefa_log_arquivo_data']);
		$sql->adInserir('log_arquivo_nome', $linha['tarefa_log_arquivo_nome']);
		$sql->adInserir('log_arquivo_tipo', $linha['tarefa_log_arquivo_tipo']);
		$sql->adInserir('log_arquivo_extensao', $linha['tarefa_log_arquivo_extensao']);
		$sql->exec();
		$sql->limpar();
		}
	}	
		

		
		
			
?>