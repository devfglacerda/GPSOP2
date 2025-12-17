<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
$usuario_id=getParam($_REQUEST, 'usuario_id', 0);

$incluir=getParam($_REQUEST, 'incluir', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);

$sql = new BDConsulta;

$sql->adTabela('usuarios');
$sql->adCampo('usuario_assinatura_nome, usuario_assinatura_local');
$sql->adOnde('usuario_id = '.$usuario_id);
$caminho=$sql->linha();
$sql->limpar();

if ($incluir && isset($_FILES['assinatura']['name']) && file_exists($_FILES['assinatura']['tmp_name']) && !empty($_FILES['assinatura']['tmp_name'])){
	$tipo=strtolower(pathinfo($_FILES['assinatura']['name'], PATHINFO_EXTENSION));
	$permitido=getSisValor('downloadPermitido');
	$proibido=getSisValor('downloadProibido');
  $verificar_malicioso=explode('.',$_FILES['assinatura']['name']);
 	$malicioso=false;
 	foreach($verificar_malicioso as $extensao) {
 		if (in_array(strtolower($extensao), $proibido)) {
 			$malicioso=$extensao;
 			break;
 			}
 		}
 	if ($malicioso) {
  	ver2('Extensão '.$malicioso.' não é permitida!');
  	}
  elseif (!in_array($tipo, $permitido)) {
  	ver2('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido');
  	}
	else {

		//apagar antigo
		if ($caminho['usuario_assinatura_nome']) @unlink($base_dir.'/arquivos/'.$caminho['usuario_assinatura_local'].$caminho['usuario_assinatura_nome']);
		$caminho = $usuario_id.'_'.$_FILES['assinatura']['name'];
		
		$dia=date('d');
		$mes=date('m');
		$ano=date('Y');
		
		if (!is_dir($base_dir.'/arquivos/'.$ano)){
			$res = mkdir($base_dir.'/arquivos/'.$ano, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do ano para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos', UI_MSG_ALERTA);
				return false;
				}
			}
		if (!is_dir($base_dir.'/arquivos/'.$ano.'/'.$mes)){
			$res = mkdir($base_dir.'/arquivos/'.$ano.'/'.$mes, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do mês para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\'.$ano, UI_MSG_ALERTA);
				return false;
				}
			}
		if (!is_dir($base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia)){
			$res = mkdir($base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta do dia para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\\'.$ano.'\\'.$mes, UI_MSG_ALERTA);
				return false;
				}
			}
		
		
		$caminho_completo = $base_dir.'/arquivos/'.$ano.'/'.$mes.'/'.$dia.'/'.$caminho;
		move_uploaded_file($_FILES['assinatura']['tmp_name'], $caminho_completo);
		
		$sql->adTabela('usuarios');
		$sql->adAtualizar('usuario_assinatura_nome', $caminho);
		$sql->adAtualizar('usuario_assinatura_local', $ano.'/'.$mes.'/'.$dia.'/');
		$sql->adOnde('usuario_id = '.(int)$usuario_id);
		$retorno=$sql->exec();
		$sql->limpar();
		}
	}

if ($excluir){
	if ($caminho['usuario_assinatura_nome']) @unlink($base_dir.'/arquivos/'.$caminho['usuario_assinatura_local'].$caminho['usuario_assinatura_nome']);
	$sql->adTabela('usuarios');
	$sql->adAtualizar('usuario_assinatura_nome', null);
	$sql->adAtualizar('usuario_assinatura_local', null);
	$sql->adOnde('usuario_id = '.$usuario_id);
	$retorno=$sql->exec();
	$sql->limpar();
	$caminho='';
	}




$sql->adTabela('usuarios');
$sql->adCampo('usuario_assinatura_nome, usuario_assinatura_local');
$sql->adOnde('usuario_id = '.$usuario_id);
$caminho=$sql->linha();
$sql->limpar();




echo '<form method="POST" id="env" name="env" enctype="multipart/form-data">';
echo '<input type=hidden id="m" name="m" value="admin">';
echo '<input type=hidden id="a" name="a" value="assinatura">';
echo '<input type=hidden id="dialogo" name="dialogo" value="1">';
echo '<input type=hidden id="usuario_id" name="usuario_id" value="'.$usuario_id.'">';	
echo '<input type=hidden id="incluir" name="incluir" value="">';	
echo '<input type=hidden id="excluir" name="excluir" value="">';	

$botoesTitulo = new CBlocoTitulo('Assinatura d'.$config['genero_usuario'].' '.ucfirst($config['usuario']).'', 'usuario.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
echo estiloTopoCaixa();

echo '<table width="100%" align="center" class="std" cellspacing=0 cellpadding=0 >';



if ($caminho['usuario_assinatura_nome']) echo '<tr><td colspan=20><table><tr><td><img src="'.$base_url.'/arquivos/'.$caminho['usuario_assinatura_local'].$caminho['usuario_assinatura_nome'].'" /></td></tr><tr><td align=center>'.botao('excluir','','','','env.excluir.value=1; env.submit()').'</td></tr></table></td></tr>';


echo '<tr><td align="left"><b>Imagem:</b><input type="File" class="arquivo" name="assinatura" size="59" /></td></tr>';
echo '<tr><td align=left>'.botao(($caminho['usuario_assinatura_nome'] ? 'atualizar' : 'enviar'), '','','','env.incluir.value=1; env.submit()').'</td><td align=right>'.botao('retornar', '','','',"url_passar(0,'m=admin&a=ver_usuario&usuario_id=".$usuario_id."');").'</td></tr>';

echo '</table>';

echo estiloFundoCaixa();
echo '</form>';
?>