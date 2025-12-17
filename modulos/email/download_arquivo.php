<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
global $config;

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);	

$sql = new BDConsulta;

if (isset($_REQUEST['modelo_anexo_id']) && $_REQUEST['modelo_anexo_id']){
	$sql->adTabela('modelo_anexo');
	$sql->adCampo('modelo_anexo.*');
	$sql->adOnde('modelo_anexo_id ='.(int)getParam($_REQUEST, 'modelo_anexo_id', null));	
	$arquivo=$sql->Linha();	
	$sql->limpar();
	
	$sql->adTabela('modelo_leitura');
	$sql->adInserir('datahora_leitura', date('Y-m-d H:i:s'));
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('modelo_id', $arquivo['modelo_anexo_modelo']);
	$sql->adInserir('download', 1);
	$sql->exec();
	$sql->limpar();	
	
	
	if ($arquivo['modelo_anexo_local']){
		$fnome = $base_dir.'/arquivos/'.$arquivo['modelo_anexo_local'].$arquivo['modelo_anexo_nome_real'];
		if (!file_exists($fnome)) {
			$Aplic->setMsg('Arquivo não foi encontrado.', UI_MSG_ERRO);
			exit();
			}
		header('MIME-Version: 1.0');
		header('Pragma: ');
		header('Cache-Control: public');
		if ($arquivo['modelo_anexo_tamanho']) header('Content-length: '.$arquivo['modelo_anexo_tamanho']);
		header('Content-type: '.$arquivo['modelo_anexo_tipo']);
		header('Content-transfer-encoding: 8bit');
		header('Content-disposition: attachment; filename="'.$arquivo['modelo_anexo_nome'].'"');
		$handle = fopen($base_dir.'/arquivos/'.$arquivo['modelo_anexo_local'].$arquivo['modelo_anexo_nome_real'], 'rb');
		if ($handle) {
			while (!feof($handle)) print fread($handle, 8192);
			fclose($handle);
			}
		flush();
		}
	else {
		$caminho = $arquivo['modelo_anexo_caminho'];
		$nome = $arquivo['modelo_anexo_nome'];
		$nome = removerSimbolos($nome);
		$nome = removerSimbolos($nome);
		$nome = removerSimbolos($nome);
		$caminho_completo=$base_dir.'/'.($config['pasta_anexos'] ? $config['pasta_anexos'].(isset($_REQUEST['modelo_id']) ? '_modelos' : '').'/':'').$caminho;
		if (file_exists ($caminho_completo) && !empty($nome)){
		  $tamanho = filesize ($caminho_completo);
		  header("Content-Type: application/open");
			header("Content-Length: ".$tamanho);
			header("Content-Disposition: attachment; filename=".$nome);
			header("Content-Transfer-Encoding: binary");
		  readfile($caminho_completo);
		  } 
		else $Aplic->setMsg('Arquivo não foi encontrado.', UI_MSG_ERRO);    
		}
	}
else{
	
	$anexo_id=getParam($_REQUEST, 'anexo', null);
	
	$sql->adTabela('anexo');
	$sql->adCampo('anexo.*');
	$sql->adOnde('anexo_id ='.(int)$anexo_id);	
	$arquivo=$sql->Linha();	
	$sql->limpar();		
	
	$msg_id = $arquivo['anexo_msg'];
	$sql->adTabela('msg_usuario');
	$sql->adCampo('count(de_id)');
	$sql->adOnde('msg_id ='.(int)$msg_id);	
	$sql->adOnde('(de_id='.(int)$Aplic->usuario_id.' OR para_id='.(int)$Aplic->usuario_id.')');	
	$achado=$sql->Resultado();	
	$sql->limpar();		
	if (!$achado && !$Aplic->usuario_admin) exit('Acesso negado.');
	else{
		$sql->adTabela('anexo_leitura');
		$sql->adInserir('datahora_leitura', date('Y-m-d H:i:s'));
		$sql->adInserir('usuario_id', $Aplic->usuario_id);
		$sql->adInserir('anexo_id', $anexo_id);
		$sql->adInserir('download', 1);
		$sql->exec();
		$sql->limpar();
		}
	

	if ($arquivo['anexo_local']){
		$fnome = $base_dir.'/arquivos/'.$arquivo['anexo_local'].$arquivo['anexo_nome_real'];
		if (!file_exists($fnome)) {
			$Aplic->setMsg('Arquivo não foi encontrado.', UI_MSG_ERRO);
			exit();
			}
		header('MIME-Version: 1.0');
		header('Pragma: ');
		header('Cache-Control: public');
		if ($arquivo['anexo_tamanho']) header('Content-length: '.$arquivo['anexo_tamanho']);
		header('Content-type: '.$arquivo['anexo_tipo']);
		header('Content-transfer-encoding: 8bit');
		header('Content-disposition: attachment; filename="'.$arquivo['anexo_nome'].'"');
		$handle = fopen($base_dir.'/arquivos/'.$arquivo['anexo_local'].$arquivo['anexo_nome_real'], 'rb');
		if ($handle) {
			while (!feof($handle)) print fread($handle, 8192);
			fclose($handle);
			}
		flush();
		}
	else {
		
		$caminho = $arquivo['anexo_caminho'];
		$nome = $arquivo['anexo_nome'];
		$nome = removerSimbolos($nome);
		$nome = removerSimbolos($nome);
		$nome = removerSimbolos($nome);
		$caminho_completo=$base_dir.'/'.($config['pasta_anexos'] ? $config['pasta_anexos'].(isset($_REQUEST['modelo_id']) &&  $_REQUEST['modelo_id'] ? '_modelos' : '').'/':'').$caminho;
		if (file_exists ($caminho_completo) && !empty($nome)){
		  $tamanho = filesize ($caminho_completo);
		  header("Content-Type: application/open");
			header("Content-Length: ".$tamanho);
			header("Content-Disposition: attachment; filename=".$nome);
			header("Content-Transfer-Encoding: binary");
		  readfile($caminho_completo);
		  }
		else $Aplic->setMsg('Arquivo não foi encontrado.', UI_MSG_ERRO);     
		}
	}
?>


