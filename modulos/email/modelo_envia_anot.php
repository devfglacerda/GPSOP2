<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$data_limite=getParam($_REQUEST, 'data_limite', 0);
$data = intval($data_limite) ? new CData($data_limite) : new CData();

if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario=getParam($_REQUEST, 'vetor_modelo_msg_usuario', null);
else if (isset($_REQUEST['modelo_usuario_id']) && $_REQUEST['modelo_usuario_id']) $vetor_modelo_msg_usuario[]=getParam($_REQUEST, 'modelo_usuario_id', null);

$recebido_enviado=(isset($vetor_modelo_msg_usuario) && count($vetor_modelo_msg_usuario));

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID=getParam($_REQUEST, 'modeloID', null);
	else if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']) $modeloID[]=getParam($_REQUEST, 'modelo_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}

$item_menu=getParam($_REQUEST, 'item_menu', '');
$tipo=getParam($_REQUEST, 'tipo', 0);
/*tipo: 4 = anotacao; 1 = despacho; 2=resposta*/
$status=getParam($_REQUEST, 'status', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$outros_emails=getParam($_REQUEST, 'outros_emails','');

//ao voltar do editar despacho recuperar dados que já tenha preenchido aqui
$setar_notifica_criador_nota=getParam($_REQUEST, 'notifica_criador_nota', 0);
$setar_notifica_destinatarios_nota=getParam($_REQUEST, 'notifica_destinatarios_nota', 0);
//preciso verificar coo colocar este texto
$setar_anot=getParam($_REQUEST, 'anot', '');
$status_original=getParam($_REQUEST, 'status_original', 0);
$retornar=getParam($_REQUEST, 'retornar', 'modelo_pesquisar');


if ($tipo == 1) $titulo='Despacho';
else if ($tipo == 2) $titulo='Resposta';
else if ($tipo == 4) $titulo='Anotação';


echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="modelo_grava_anot">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden name="tipo" id="tipo" value="'.$tipo.'">';
echo '<input type=hidden name="arquivar" id="arquivar" value="">';
echo '<input type=hidden name="encaminha" id="encaminha" value="">';

echo '<input type=hidden id="status_original" name="status_original" value="'.$status_original.'">';

//armazenar os cabeçalhos das mensagens
if (isset($vetor_modelo_msg_usuario)) foreach ($vetor_modelo_msg_usuario as $chave => $valor) echo '<input type=hidden id="vetor_modelo_msg_usuario" name=vetor_modelo_msg_usuario[] value="'.$valor.'">';
else foreach ($modeloID as $chave => $valor) echo '<input type=hidden id="modeloID" name=modeloID[] value="'.$valor.'">';

//caso seja despacho, preciso recuperar os destinatários para passar adiante
if ($tipo==1){
	foreach ($ListaPARA as $chave => $valor) echo '<input type=hidden name=ListaPARA[] value="'.$valor.'">';
	foreach ($ListaPARAoculto as $chave => $valor) echo '<input type=hidden name=ListaPARAoculto[] value="'.$valor.'">';
	foreach ($ListaPARAaviso as $chave => $valor) echo '<input type=hidden name=ListaPARAaviso[] value="'.$valor.'">';
	foreach ($ListaPARAexterno as $chave => $valor) echo '<input type=hidden name=ListaPARAexterno[] value="'.$valor.'">';
	}
echo '<input type=hidden name="outros_emails" id="outros_emails" value="'.$outros_emails.'">';


$botoesTitulo = new CBlocoTitulo('Inserir '.$titulo, 'documento.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();





echo estiloTopoCaixa();
echo '<table align="center" class="std2" cellpadding=0 cellspacing=0 width=100% BORDERCOLOR="#000000" ><tr><td>&nbsp;</td></tr>';


$sql = new BDConsulta;
$sql->adTabela('despacho');
$sql->adOnde('despacho_usuario= '.(int)$Aplic->usuario_id);
if ($tipo==1) $sql->adOnde('despacho_despacho=1');
elseif ($tipo==2) $sql->adOnde('despacho_resposta=1');
else $sql->adOnde('despacho_anotacao=1');
$sql->adCampo('despacho_nome, despacho_texto');
$lista = $sql->Lista();
$sql->limpar();
$despacho=array(null => null);
foreach($lista AS $linha) $despacho[$linha['despacho_texto']]=($linha['despacho_nome'] ? $linha['despacho_nome'] : $linha['despacho_texto']);  
if (count($despacho)>1) echo '<tr><td align=left><table cellspacing=0 cellpadding=0><tr><td>'.dica('Modelo','Clique em uma das opções para inserir um texto pré-formatado.').'Modelo:'.dicaF().'</td><td>'.selecionaVetor($despacho, 'texto_despacho', 'style="width:400px;" class="texto" onchange="combo_escolha()"').'</td></tr></table></td></tr>';





echo '<table align="center" border=0 cellspacing=0 width=100%  class="std2" cellpadding=0>';
echo '<tr><td align="left" style="background:#ffffff;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="anot" id="anot" style="width:768px; max-width:768px;"></textarea>';
if ($setar_anot) echo "<script>CKEDITOR.instances['anot'].setData(CKEDITOR.instances['anot'].getData()+'$setar_anot')</script>";
echo '</td></tr>';
echo '<tr><td>&nbsp;</td></tr>';

if ($tipo==1) echo '<tr><td><table><tr><td width="140" align="right">'.dica('Prazo para Responder','Marque esta caixa caso deseja impor um prazo limite para que os desinatários deste despacho tenham que responder ao mesmo.').'<b>Prazo para responder:</b>'.dicaF().'</td><td><input type="checkbox" name="prazo_responder" id="prazo_responder" size=50 value=1 checked="checked" onchange="javascript:if (env.prazo_responder.checked) document.getElementById(\'ver_data\').style.display = \'\'; else document.getElementById(\'ver_data\').style.display = \'none\';"></td><td id="ver_data" style="display:"><input type="hidden" name="data_limite" id="data_limite" value="'.($data ? $data->format('%Y%m%d') : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\');" value="'.($data ? $data->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de início da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.<br><br>Somente serão apresentadas '.$config['genero_tarefa'].'s '.$config['tarefas'].' que tenham iniciado à partir desta data.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';
if ($tipo!=2 && ($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado')) echo '<tr><td><table><tr><td width="380" align="right">'.dica('Notificar o Criador do Documento','Selecione esta caixa caso deseje que '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' seja enviad'.$config['mensagem'].' ao criador do documento notificando sobre a inclusão '.($tipo==1 ? 'deste despacho' : ($tipo==2 ? 'desta resposta' : 'desta nota')).'.').'<b>Notificar o criador do documento:</b>'.dicaF().'</td><td><input type="checkbox" name="notifica_criador_nota" id="notifica_criador_nota"  size=50 value=1 '.($setar_notifica_criador_nota ? "CHECKED" : "").'></td></tr></table></td></tr>';
if (($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado')) echo '<tr><td><table><tr><td width="380" align="right">'.dica('Notificar os Demais Destinatários do Documento','Selecione esta caixa caso deseje que todos os destinatários deste documento sejam notificandos sobre a inclusão '.($tipo==1 ? 'deste despacho' : ($tipo==2 ? 'desta resposta' : 'desta nota')).'.').'<b>Notificar os demais destinatários do documento:</b>'.dicaF().'</td><td><input type="checkbox" name="notifica_destinatarios_nota" id="notifica_destinatarios_nota" size=50 value=1 '.($setar_notifica_destinatarios_nota ? "CHECKED" : "").'></td></tr></table></td></tr>';
if ($tipo==4 && ($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado')) echo '<tr><td><table><tr><td width="380" align="right"><b>Quem pode ler esta nota:</b></td><td>'.dica('Todos', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' poderão ler a nota.').'<input type="radio" name="podeler_nota" value="" checked />Todos'.dicaF().dica('Remetente(s)', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' que lhe enviaram esta documento poderão ler a nota.').'<input type="radio" name="podeler_nota" value="remetentes" />Remetente(s)'.dicaF().dica('Criador d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Somente o criador do documento, ainda que não lhe tenha enviado a mesma, no caso de encaminhamento por terceiros, poderá ler a nota.').'<input type="radio" name="podeler_nota" value="criador" />Criador do documento'.dicaF().'</td></tr></table></td></tr>';


if ($tipo==2) echo '<tr><td><table><tr><td width="380" align="right"><b>Para quem a resposta:</b></td><td>'.dica('Remetente(s)', 'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' que lhe enviaram este documento receberão a resposta.').'<input type="radio" name="receber_resposta" checked value="remetentes" />Remetente(s)'.dicaF().dica('Criador do Documento', 'Somente o criador do documento, ainda que não lhe tenha enviado a mesma, no caso de encaminhamento por terceiros, receberá a resposta.').'<input type="radio" name="receber_resposta" value="criador" />Criador do documento'.dicaF().'</td></tr></table></td></tr>';

echo '<tr><td>&nbsp;</td></tr>';
echo '<tr><td align="center">'.$titulo.' para '.relacao_documentos().'</td></tr>';





echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0 width=100%><tr>';

if ($tipo==1) echo '<td width=99%>'.botao('despachar', 'Despachar', 'Clique neste botão para enviar o despacho.','','btRemeter2_onclick()').'</td><td>'.($recebido_enviado ? botao('despachar e arquivar', 'Despachar e Arquivar', 'Clique neste botão para enviar o despacho.<br><br>O documento será armazenado na caixa dos arquivados.','','btRemeter3_onclick();') : '').'</td><td>'.($recebido_enviado ? botao('despachar e pender', 'Despachar e Pender','Clique neste botão para enviar o despacho<br><br>O documento será armazenado na caixa dos pendentes.','','btRemeter4_onclick();') : '').'</td>';
if ($tipo==2) echo '<td width=99%>'.botao('responder', 'Responder', 'Clique neste botão para enviar a resposta.','','btRemeter2_onclick()').'</td><td>'.($recebido_enviado ? botao('responder e arquivar', 'Responder e Arquivar', 'Clique neste botão para enviar a resposta.<br><br>O documento será armazenado na caixa dos arquivados.','','btRemeter3_onclick();') : '').'</td><td>'.($recebido_enviado ? botao('responder e pender', 'Responder e Pender','Clique neste botão para enviar a resposta.<br><br>O documento será armazenado na caixa dos pendentes.','','btRemeter4_onclick();') : '' ).'</td>';
if ($tipo==4) echo '<td width=99%>'.botao('anotar', 'Anotar','Clique neste botão para escrever uma anotação no documento.','','btRemeter2_onclick();').'</td><td>'.($recebido_enviado ? botao('anotar e arquivar', 'Anotar e Arquivar', 'Clique neste botão para escrever uma anotação no documento.<br><br>O documento será armazenado na caixa dos arquivados.','','btRemeter3_onclick();') : '').'</td><td>'.($recebido_enviado ? botao('anotar e pender', 'Anotar e Pender','Clique neste botão para escrever uma anotação no documento.<br><br>O documento será armazenado na caixa dos pendentes.','','btRemeter4_onclick();') : '').'</td>';
echo '<td align=right>'.botao('sair', 'Sair', 'Clique neste botão para sair desta tela.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td>';


echo '</tr></table></td></tr>';


echo '</table>';
echo estiloFundoCaixa();
echo '</form></body></html>';
?>

<script LANGUAGE="javascript">

  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "data_limite",
  	date :  <?php echo $data->format("%Y%m%d")?>,
  	selection: <?php echo $data->format("%Y%m%d")?>,
    onSelect: function(cal1) {
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("data_limite").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide();
  	}
  });

function tem_conteudo(){
	var editorcontent = CKEDITOR.instances['anot'].getData().replace(/<[^>]*>/gi, '');
  return (editorcontent.length > 0);
	}

//ANOTAR, encaminhar DESPACHO ; RESPONDER
function btRemeter2_onclick() {
	if (!tem_conteudo()) alert("Necessita escrever <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");
	else {
		env.encaminha.value=1;
		env.arquivar.value=0;
		env.submit();
		}
	}

//encaminhar E ARQUIVAR DESPACHO; RESPONDER E ARQUIVAR; ANOTAR E ARQUIVAR
function btRemeter3_onclick() {
	if (!tem_conteudo()) alert("Necessita escrever <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");
	else {
		env.encaminha.value=1;
		env.arquivar.value=1;
		env.submit();
		}
	}

//encaminhar E PENDER DESPACHO; RESPONDER E PENDER; ANOTAR E PENDER
function btRemeter4_onclick() {
	if (!tem_conteudo()) alert("Necessita escrever <?php echo ($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem']?>!");
	else {
		env.encaminha.value=1;
		env.arquivar.value=2;
		env.submit();
		}
	}

function combo_escolha(){
	CKEDITOR.instances['anot'].setData(CKEDITOR.instances['anot'].getData()+env.texto_despacho.value);
	}
</script>