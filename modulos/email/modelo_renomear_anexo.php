<?php  
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
$modelo_anexo_id=getParam($_REQUEST, 'modelo_anexo_id', 0);
$posicao=getParam($_REQUEST, 'posicao', 0);
$renomear=getParam($_REQUEST, 'renomear', 0);
$novo_nome=getParam($_REQUEST, 'novo_nome', '');
$sql = new BDConsulta;

if ($renomear){
	$sql->adTabela('modelo_anexo');
	$sql->adCampo('modelo_anexo_modelo');
	$sql->adOnde('modelo_anexo_id='.$modelo_anexo_id);
	$modelo_id=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('modelo_anexo');
	$sql->adAtualizar('modelo_anexo_nome_fantasia', $novo_nome);
	$sql->adOnde('modelo_anexo_id='.$modelo_anexo_id);
	if (!$sql->exec()) echo db_error();
	$sql->limpar();

	$sql->adTabela('modelo_anexo');
	$sql->adUnir('usuarios','usuarios', 'modelo_anexo_usuario=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('modelo_anexo.*, contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('modelo_anexo_modelo = '.(int)$modelo_id);
	$anexos = $sql->Lista();
	
	$sql->limpar();
	$saida='';
	foreach($anexos as $rs_anexo){
		$saida.='<div><a href="javascript:void(0);">&nbsp;</a><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=download_arquivo&sem_cabecalho=1&modelo_anexo_id='.$rs_anexo['modelo_anexo_id'].'\');">'.($rs_anexo['modelo_anexo_nome_fantasia'] ? $rs_anexo['modelo_anexo_nome_fantasia'] : $rs_anexo['modelo_anexo_nome']).'</a>&nbsp;<a href="javascript:void(0);" onclick="popRenomear('.$rs_anexo['modelo_anexo_id'].', '.$posicao.')">'.imagem('icones/editar.gif').'</a><a href="javascript:void(0);" onclick="popExcluir('.$rs_anexo['modelo_anexo_id'].', '.$posicao.')">&nbsp;'.imagem('icones/excluir.gif').'</a></div>';
		}
	$saida=addslashes($saida);
	
	?>
	<script type="text/javascript">
		try {
			if(parent && parent.gpwebApp) parent.gpwebApp._popupCallback('<?php echo $saida?>', <?php echo $posicao?>);
   		else window.opener.reescrever_anexos('<?php echo $saida?>', <?php echo $posicao?>); 
		 	} 
		catch(e) {
		  alert("falha");
		 	} 
		finally {
		 	window.close();
		 	} 
	</script>
	<?php
	}

$sql->adTabela('modelo_anexo');
$sql->adUnir('usuarios','usuarios', 'modelo_anexo_usuario=usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('modelo_anexo.*, contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adOnde('modelo_anexo_id = '.$modelo_anexo_id);
$anexo = $sql->Linha();
$sql->limpar();



echo '<form method="POST" name="env" id="env" enctype="multipart/form-data">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="modelo_renomear_anexo">';
echo '<input type=hidden id="modelo_anexo_id" name="modelo_anexo_id" value="'.$modelo_anexo_id.'">';
echo '<input type=hidden id="posicao" name="posicao" value="'.$posicao.'">';
echo '<input type=hidden id="renomear" name="renomear" value="">';
echo '<input type=hidden id="dialogo" name="dialogo" value="1">';

echo estiloTopoCaixa(500); 
echo '<table class="std" align="center" cellspacing="3" width="500"  cellpadding=0>';
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td align="right" width="150"><b>Remetente</b>:</td><td>'.($Aplic->usuario_prefs['nomefuncao'] ? ($anexo['modelo_anexo_nome_de'] ? $anexo['modelo_anexo_nome_de'] : $anexo['nome_usuario']) : ($anexo['modelo_anexo_funcao_de'] ? $anexo['modelo_anexo_funcao_de'] : $anexo['contato_funcao'])).'</td></tr>';
echo '<tr><td align="right"><b>Anexado em</b>:</td><td>'.retorna_data($anexo['modelo_anexo_data_envio']).'</td></tr>';
if ($anexo['modelo_anexo_doc_nr']) echo '<tr><td align="right"><b>Referência</b>:</td><td>'.$anexo['modelo_anexo_doc_nr'].'</td></tr>';
if ($anexo['modelo_anexo_tipo_doc']) echo '<tr><td align="right"><b>Tipo</b>:</td><td>'.$anexo['modelo_anexo_tipo_doc'].'</td></tr>';
echo '<tr><td colspan=2 align="center">&nbsp;</td></tr>';
echo '<tr><td colspan=2 align="center"><input type="text" style="width:200px;" class="texto" name="novo_nome" value="'.($anexo['modelo_anexo_nome_fantasia'] ? $anexo['modelo_anexo_nome_fantasia'] : $anexo['modelo_anexo_nome']).'"></td></tr>';
echo '<tr><td colspan=2 align="center">&nbsp;</td></tr>';
echo '<tr><td colspan="2" align="center"><table><tr><td>&nbsp;</td><td><a class="botao" href="javascript:void(0);" onclick="javascript:env.renomear.value=1; env.submit();"><span><b>Confirmar</b></span></a></td><td  width="100"><a class="botao" href="javascript:void(0);" onclick="javascript:window.close();"><span><b>Cancelar</b></span></a></td></tr></table></td></tr>';
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '</table>'; 
echo estiloFundoCaixa(500); 			 
echo '</form>'

?>