<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
global $config;
if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'gera_notebook')) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
include_once BASE_DIR.'/modulos/social/funcoes.php';

$exportar=getParam($_REQUEST, 'exportar', 0);
 
$botoesTitulo = new CBlocoTitulo('Gerar Arquivo de Preparação de Dispositivo Off-Line', 'importar.gif', $m, "$m.$a");
$botoesTitulo->adicionaBotao('m=sistema&a=index&u=', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar(); 
 
 
 if ($exportar){
	
	$nome=exportar_social();
	
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20 align=center><table><tr><td align=right>Arquivo criado:</td><td><b><a href="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/temp/'.$nome.'.zip">'.$nome.'.zip</a></b></td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	}


echo '<form name="env" id="env" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="exportar" value="0" />'; 
 
if(!$exportar){
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20><table align=center><tr><td>'.botao('gerar arquivo', 'Gerar Arquivo', 'Clique neste botão para gerar o arquivo o arquivo de praparação dos dispositivos que irão trabalhar off-line no cadastramento de beneficiários, com os programas, as ações, comitês e comunidades.','','env.exportar.value=1; env.submit()').'</td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	} 
 
 
 
 
 
echo '</form>';



 
 
 

 
 
 
?>