<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

global $estilo_interface, $localidade_tipo_caract, $pesquisar_texto, $dialogo, $tab, $usuario_id;
require_once ($Aplic->getClasseSistema('libmail'));

$Aplic->carregarCKEditorJS();

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['pasta'])) $Aplic->setEstado('DespachosIdxPasta', getParam($_REQUEST, 'pasta', 0));
$pasta = ($Aplic->getEstado('DespachosIdxPasta')!== null ? $Aplic->getEstado('DespachosIdxPasta') : 0);

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$ordenar=getParam($_REQUEST, 'ordenar', 'data_limite');
$ordem=getParam($_REQUEST, 'ordem', '0');


$mover=getParam($_REQUEST, 'mover', array());
$retornar=getParam($_REQUEST, 'retornar', '');


$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_despachos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 



$data=getParam($_REQUEST, 'data', '');



$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', array());

$sql = new BDConsulta;
$tem_pasta=0;
//entrada
$sql->adTabela('pasta');
$sql->adCampo('count(pasta_id) as soma');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$tem_pasta = $sql->Resultado();
$sql->limpar();


if (getParam($_REQUEST, 'enviar_msg', 0)){
	$vetor_despacho=getParam($_REQUEST, 'vetor_despacho', array());
	$vetor_despacho=implode(',',$vetor_despacho);
	$referencia=getParam($_REQUEST, 'referencia', '');
	$texto=getParam($_REQUEST, 'texto', '');
	if ($tab==1){
		//enviados sem resposta
		$sql->adTabela('msg_usuario');
		$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=para_id');
		$sql->esqUnir('contatos','contatos','usuarios.usuario_contato=contatos.contato_id');
		$sql->adCampo('DISTINCT usuario_id, contato_email');
		$sql->adOnde('msg_usuario.de_id = '.$Aplic->usuario_id);
		$sql->adOnde('tipo=1');
		$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
		$sql->adOnde('msg_usuario.msg_usuario_id IN ('.$vetor_despacho.')');
		$destinatarios = $sql->Lista();
		$sql->limpar();


		foreach($destinatarios as $usuario){

			$email = new Mail;
            $email->De($config['email'], $Aplic->usuario_nome);

            if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                $email->ResponderPara($Aplic->usuario_email);
                }
            else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                $email->ResponderPara($Aplic->usuario_email2);
                }

			$email->Assunto($referencia, $localidade_tipo_caract);
			$email->Corpo($texto, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			if ($usuario['usuario_id']!=$Aplic->usuario_id){
				msg_email_interno ('', $referencia, $texto,'',$usuario['usuario_id']);
				if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo']){
					$email->Para($usuario['contato_email'], true);
					$email->Enviar();
					}
				}
			}
		$Aplic->setMsg(ucfirst($config['mensagem']).' enviad'.$config['genero_mensagem'], UI_MSG_OK);
		}
	if ($tab==0){
		$sql->adTabela('msg_usuario');
		$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=para_id');
		$sql->esqUnir('contatos','contatos','usuarios.usuario_contato=contatos.contato_id');
		$sql->adCampo('DISTINCT msg_usuario_id');
		$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
		$sql->adOnde('tipo=1');
		$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
		$sql->adOnde('msg_usuario.msg_usuario_id IN ('.$vetor_despacho.')');
		$despachos = $sql->Lista();
		$id_mensagens=array();
		foreach ($despachos as $despacho) $id_mensagens[]=$despacho['msg_usuario_id'];
		$sql->limpar();
		$id_mensagens=implode(',',$id_mensagens);
		$sql->adTabela('msg_usuario');
		$sql->adAtualizar('data_retorno', date('Y-m-d H:i:s'));
		$sql->adAtualizar('resposta_despacho', $texto);
		$sql->adOnde('msg_usuario_id IN ('.$id_mensagens.')');
		if (!$sql->exec()) die('Não foi possível atualizar msg_usuario.');
		$sql->limpar();
		$Aplic->setMsg('Resposta ao despacho enviada', UI_MSG_OK);
		}
	}



$sql->adTabela('msg_usuario');
$sql->esqUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
$sql->esqUnir('anotacao','anotacao','anotacao.anotacao_id=msg_usuario.anotacao_id');
$sql->adCampo('count(DISTINCT msg_usuario.msg_usuario_id)');
if ($tab==1 || $tab==3) $sql->adOnde('msg_usuario.de_id = '.$Aplic->usuario_id);
else if ($tab==0 || $tab==2) $sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
if ($usuario_id) {
	if ($tab==1 || $tab==3) $sql->adOnde('msg_usuario.para_id = '.(int)$usuario_id);
	else if ($tab==0 || $tab==2) $sql->adOnde('msg_usuario.de_id = '.(int)$usuario_id);
	}
$sql->adOnde('msg_usuario.tipo=1');
if ($pasta>0 && ($tab==0 || $tab==2)) $sql->adOnde('despacho_pasta_receb='.$pasta);
if ($pasta>0 && ($tab==1 || $tab==3)) $sql->adOnde('despacho_pasta_envio='.$pasta);
if ($pasta==0 && ($tab==0 || $tab==2)) $sql->adOnde('despacho_pasta_receb=0 OR despacho_pasta_receb IS NULL');
if ($pasta==0 && ($tab==1 || $tab==3)) $sql->adOnde('despacho_pasta_envio=0 OR despacho_pasta_receb IS NULL');
if ($tab==0 || $tab==1)$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
if ($tab==2 || $tab==3)$sql->adOnde('resposta_despacho IS NOT NULL');
if ($pesquisar_texto) $sql->adOnde('(anotacao.texto LIKE \'%'.$pesquisar_texto.'%\' OR anotacao.nome_de LIKE \'%'.$pesquisar_texto.'%\' OR anotacao.funcao_de LIKE \'%'.$pesquisar_texto.'%\' OR msg_usuario.nome_para LIKE \'%'.$pesquisar_texto.'%\' OR msg_usuario.funcao_para LIKE \'%'.$pesquisar_texto.'%\' OR nota LIKE \'%'.$pesquisar_texto.'%\' OR msg.referencia LIKE \'%'.$pesquisar_texto.'%\' OR resposta_despacho LIKE \'%'.$pesquisar_texto.'%\')');
$sql->adOnde('anotacao.anotacao_id IS NOT NULL');
$xtotalregistros = $sql->Resultado();
$sql->limpar();




$sql->adTabela('msg_usuario');
$sql->esqUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
$sql->esqUnir('anotacao','anotacao','anotacao.anotacao_id=msg_usuario.anotacao_id');
$sql->adCampo('msg_usuario.msg_usuario_id, anotacao.anotacao_id, anotacao.datahora, data_retorno, resposta_despacho, anotacao.texto, data_limite, msg_usuario.de_id, msg_usuario.para_id, msg_usuario.msg_id, msg.referencia');
if ($tab==1 || $tab==3) $sql->adOnde('msg_usuario.de_id = '.$Aplic->usuario_id);
else if ($tab==0 || $tab==2) $sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
if ($usuario_id) {
	if ($tab==1 || $tab==3) $sql->adOnde('msg_usuario.para_id = '.(int)$usuario_id);
	else if ($tab==0 || $tab==2) $sql->adOnde('msg_usuario.de_id = '.(int)$usuario_id);
	}
$sql->adOnde('msg_usuario.tipo=1');
if ($pasta>0 && ($tab==0 || $tab==2)) $sql->adOnde('despacho_pasta_receb='.$pasta);
if ($pasta>0 && ($tab==1 || $tab==3)) $sql->adOnde('despacho_pasta_envio='.$pasta);
if ($pasta==0 && ($tab==0 || $tab==2)) $sql->adOnde('despacho_pasta_receb=0 OR despacho_pasta_receb IS NULL');
if ($pasta==0 && ($tab==1 || $tab==3)) $sql->adOnde('despacho_pasta_envio=0 OR despacho_pasta_receb IS NULL');
if ($tab==0 || $tab==1)$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
if ($tab==2 || $tab==3)$sql->adOnde('resposta_despacho IS NOT NULL');
if ($pesquisar_texto) $sql->adOnde('(anotacao.texto LIKE \'%'.$pesquisar_texto.'%\' OR anotacao.nome_de LIKE \'%'.$pesquisar_texto.'%\' OR anotacao.funcao_de LIKE \'%'.$pesquisar_texto.'%\' OR msg_usuario.nome_para LIKE \'%'.$pesquisar_texto.'%\' OR msg_usuario.funcao_para LIKE \'%'.$pesquisar_texto.'%\' OR nota LIKE \'%'.$pesquisar_texto.'%\' OR msg.referencia LIKE \'%'.$pesquisar_texto.'%\' OR resposta_despacho LIKE \'%'.$pesquisar_texto.'%\')');
$sql->adOnde('anotacao.anotacao_id IS NOT NULL');
$sql->adGrupo('msg_usuario.msg_usuario_id');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$despachos = $sql->Lista();
$sql->limpar();



echo '<input type=hidden name="enviar_msg" id="enviar_msg" value="">';
echo '<input type=hidden name="pasta" id="pasta" value="'.$pasta.'">';
echo '<input type=hidden id="retornar" name="retornar" value="'.$retornar.'">';

echo '<table id="tblTexto" border=0 cellpadding=0 cellspacing=0 width="100%" class="std" style="display:none;">';
if ($tab==1) echo '<tr><td align="left">Assunto:</td><td align="left" colspan=2 width="100%"><input class="texto" type="text" name="referencia" id="referencia" size="79" maxlength="79"></td></tr>';
echo '<tr><td colspan="3" align="left" style="background:#ffffff; width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="texto" id="texto" ></textarea></td></tr>';
echo '<tr><td>'.botao('salvar', 'Salvar','Clique neste botão para salvar '.($tab==0 ? 'a resposta ao despacho.' : $config['genero_mensagem'].' '.$config['mensagem']).'.','','env.submit();').'</td><td align=right>'.botao('cancelar', 'Cancelar','Clique neste botão para cancelar o envio '.($tab==0 ? 'da resposta ao despacho.' : 'd'.$config['genero_mensagem'].' '.$config['mensagem'].'.'),'','document.getElementById(\'tblTexto\').style.display=\'none\'; document.getElementById(\'tblDespachos\').style.display=\'\';').'</td></tr>';
echo '</table>';


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'despacho', 'despachos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));



echo '<table id="tblDespachos" cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
echo '<tr><th></th><th></th>
<th align="left"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=datahora&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='datahora' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data do Despacho', 'Clique para ordenar os despachos pela data em que o mesmo foi expedido.<br><br>Ao clicar em cima da data será mostrado o texto do despacho').'Data'.dicaF().'</a></th>
<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=data_limite&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='data_limite' ? imagem('icones/'.$seta[$ordem]) : '').dica('Prazo', 'Clique para ordenar os despachos pelo prazo limite de resposta.').'Prazo'.dicaF().'</a></th>'.
($tab==1 || $tab==3 ? '<th>Destinatário</th>' : '<th>Remetente</th>').
'<th><a class="hdr" href="index.php?m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=msg_usuario.msg_id&ordem='.($ordem ? '0' : '1').'">'.($ordenar=='msg_usuario.msg_id' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['mensagem']), 'Clique para ordenar os despachos pel'.$config['genero_mensagem'].' '.$config['mensagem'].' '.($config['genero_mensagem']=='a' ? 'a' : 'ao').' qual o mesmo faz referência.<br><br>Ao clicar em cima da data será mostrado o texto do despacho').''.ucfirst($config['mensagem']).''.dicaF().'</a></th>
</tr>';

foreach ($despachos as $linha) {

	$cor=($linha['data_limite'] && $linha['data_limite'] < date('Y-m-d H:i:s') ? 'ff0000' : '27740c');
	echo '<tr style="background-color:#ffffff;">';
	echo '<td width="16"><input type="checkbox" name="vetor_despacho[]" value="'.$linha['msg_usuario_id'].'"></td>';
	echo '<td width="'.($tab > 1 ? '32' : '16').'"><a href="javascript: void(0);" onclick="expandir_colapsar(\'despacho_'.$linha['msg_usuario_id'].'\');">'.imagem('icones/msg10010.gif', 'Exibir Despacho', 'Clique neste ícone '.imagem('icones/msg10010.gif').' para exibir o texto do despacho.').'</a>'.($tab > 1 ? '<a href="javascript: void(0);" onclick="expandir_colapsar(\'resposta_despacho_'.$linha['msg_usuario_id'].'\');">'.imagem('icones/msg10020.gif', 'Exibir Resposta ao Despacho', 'Clique neste ícone '.imagem('icones/msg10020.gif').' para exibir o texto da resposta ao despacho.').'</a>' : '').'</td>';
	echo '<td width=80 align=center>'.retorna_data($linha['datahora'], false).'</td>';
	echo '<td style="color:#'.$cor.';"  width=80 align=center>'.($linha['data_limite'] ? retorna_data($linha['data_limite'], false) :'&nbsp;').'</td>';
	echo ($tab==1 || $tab==3 ? '<td>'.link_usuario($linha['para_id'],'','','esquerda').'</td>' : '<td>'.link_usuario($linha['de_id'],'','','esquerda').'</td>');
	echo '<td>'.$linha['msg_id'].' - '.$linha['referencia'].'</td></tr>';
	echo '<tr style="display: none" id="despacho_'.$linha['msg_usuario_id'].'"><td colspan=20 class="realce">'.$linha['texto'].'</td></tr>';
	if($tab > 1) echo '<tr style="display: none" id="resposta_despacho_'.$linha['msg_usuario_id'].'"><td colspan=20 class="realce">'.$linha['resposta_despacho'].'</td></tr>';
	
	}

//responder clicando no calendario
if ($msg_usuario_id) {
	echo '<input type="hidden" name="vetor_despacho[]" value="'.$msg_usuario_id.'">';
	echo '<script>env.enviar_msg.value=1;document.getElementById("tblTexto").style.display=\'\';document.getElementById("tblDespachos").style.display=\'none\';</script>';
	}
if (!$xtotalregistros) echo '<tr><td colspan=20>Não existe despacho '.($tab==0 || $tab==2 ? 'recebido' : 'enviado').($tab==0 || $tab==1 ? ' não' : '').' respondido'.($pasta>0 ? ' nesta pasta' : '').'</td></tr>';
echo '</table>';

echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
if ($tab==0 && $xtotalregistros) echo '<tr><td colspan=20>'.dica('Responder Despacho','Clique neste botão para enviar uma resposta aos despachos selecionados.').'<a class="botao" href="javascript:void(0);" onclick="javascript:enviar();"><span><b>responder despacho</b></span></a>'.dicaF().'</td></tr>';
if ($tab==1 && $xtotalregistros) echo '<tr><td colspan=20>'.dica('Enviar '.ucfirst($config['mensagem']),'Clique neste botão para enviar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' para os destinatários dos despachos selecionados.').'<a class="botao" href="javascript:void(0);" onclick="javascript:enviar();"><span><b>enviar '.$config['mensagem'].'</b></span></a>'.dicaF().'</td></tr>';
echo '<tr><td colspan=20><table>'.($tem_pasta ? '<td>'.dica('Caixa de Seleção de Pasta','Selecione na caixa de opção em qual pasta deseja entrar para ver '.$config['genero_mensagem'].'s '.$config['mensagens'].' armazenad'.$config['genero_mensagem'].'s.<BR><BR>Para mover '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' para a pasta, selecione a mesma e utilize a próxima caixa de seleção, do lado direito.').'<a>'.comboPasta($Aplic->usuario_id, $pasta).'</a>'.dicaF().'</td>' : '').'</table></td></tr>';

echo '</table>';

echo '</form>';


function comboPasta($usuario_id, $pasta) {
	global $Aplic, $config;

	$sql = new BDConsulta;
	$s = '<select id="codigo_pasta" name="codigo_pasta" class=text size=1 onchange="resulta_combo();">';
	$s .= '<option value="0" '.($pasta==0 ? ' selected="selected"' : '').' >fora das pastas</option>';
	$s .= '<option value="-1" '.($pasta==-1 ? ' selected="selected"' : '').' >tod'.$config['genero_mensagem'].'s '.$config['mensagens'].'</option>';
	$sql->adTabela('pasta');
  $sql->adCampo('pasta_id,nome');
	$sql->adOnde('pasta.usuario_id='.$usuario_id);
	$pastas=$sql->Lista();
	$sql->limpar();
	foreach ($pastas as $linha) $s .= '<option value="'.$linha['pasta_id'].'"'.(($linha['pasta_id'] == $pasta ) ? ' selected="selected"' : '').'>'.$linha['nome'].'</option>';
	$s .= '</select>';
	return $s;
	}


?>

<script language=Javascript>

function resulta_combo() {
  document.getElementById('pasta').value=document.getElementById('codigo_pasta').value;
	document.getElementById('env').submit();
  }


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function enviar(){
 if (verifica_selecao()){
 		env.enviar_msg.value=1
 		document.getElementById("tblTexto").style.display='';
 		document.getElementById("tblDespachos").style.display='none';
 		}
	}

function verifica_selecao(){
	var j=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		if (document.getElementById('env').elements[i].checked) j++;
		}
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos um despacho!");
		return 0;
		}
	}

function editar_pastas(){
 	env.a.value="editar_pastas";
 	env.retornar.value="lista_despacho";
	env.submit();
  }

</script>