<?php 
/* 
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


global $config;
$sql = new BDConsulta;
$sql->adTabela('plano_gestao_arquivo');
$sql->adCampo('plano_gestao_arquivo.*');
$sql->adOnde('plano_gestao_arquivo_id ='.(int)getParam($_REQUEST, 'plano_gestao_arquivo_id', null));	
$arquivo=$sql->Linha();	
$sql->limpar();		
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);


if ($arquivo['plano_gestao_arquivo_local']){
	$fnome = $base_dir.'/arquivos/'.$arquivo['plano_gestao_arquivo_local'].$arquivo['plano_gestao_arquivo_nome_real'];
	if (!file_exists($fnome)) {
		$Aplic->setMsg('Arquivo não foi encontrado.', UI_MSG_ERRO);
		exit();
		}
	header('MIME-Version: 1.0');
	header('Pragma: ');
	header('Cache-Control: public');
	if ($arquivo['plano_gestao_arquivo_tamanho']) header('Content-length: '.$arquivo['plano_gestao_arquivo_tamanho']);
	header('Content-type: '.$arquivo['plano_gestao_arquivo_tipo']);
	header('Content-transfer-encoding: 8bit');
	header('Content-disposition: attachment; filename="'.$arquivo['plano_gestao_arquivo_nome'].'"');
	$handle = fopen($base_dir.'/arquivos/'.$arquivo['plano_gestao_arquivo_local'].$arquivo['plano_gestao_arquivo_nome_real'], 'rb');
	if ($handle) {
		while (!feof($handle)) print fread($handle, 8192);
		fclose($handle);
		}
	flush();
	}
else {
	$caminho_completo = $base_dir.'/arquivos/gestao/'.$arquivo['plano_gestao_arquivo_endereco'];
	if (file_exists ($caminho_completo) && !empty($arquivo['plano_gestao_arquivo_nome'])){
	  $tamanho = filesize ($caminho_completo);
	  header("Content-Type: application/open");
		header("Content-Length: ".$tamanho);
		header("Content-Disposition: attachment; filename=".$arquivo['plano_gestao_arquivo_nome']);
		header("Content-Transfer-Encoding: binary");
	  readfile($caminho_completo);
	  } 
	else $Aplic->setMsg('Arquivo não foi encontrado.', UI_MSG_ERRO);   
	}
?>


