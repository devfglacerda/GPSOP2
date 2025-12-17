<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;

$Aplic->carregarCalendarioJS();
$Aplic->carregarCKEditorJS();

$pratica_indicador_valor_id=getParam($_REQUEST, 'pratica_indicador_valor_id', 0);

$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', null);

$salvar=getParam($_REQUEST, 'salvar', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);
$sql = new BDConsulta;

if (!$pratica_indicador_id){
	$sql->adTabela('pratica_indicador_valor');
	$sql->adCampo('pratica_indicador_valor_indicador');
	$sql->adOnde('pratica_indicador_valor_id = '.(int)$pratica_indicador_valor_id);
	$pratica_indicador_id = $sql->Resultado();
	$sql->limpar();
	}

$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_acumulacao, pratica_indicador_agrupar, pratica_indicador.pratica_indicador_acesso, pratica_indicador.pratica_indicador_nome, pratica_indicador_id');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();


$acaoSolicitada = $pratica_indicador_id ? 'editar' : 'adicionar';

$podeEditar = $Aplic->checarModulo( 'praticas', $acaoSolicitada, $Aplic->usuario_id, 'indicador');
$podeValor=$Aplic->checarModulo( 'praticas', $acaoSolicitada , $Aplic->usuario_id, 'indicador_valor');

$negadoAcaoValor = $Aplic->checarModuloNegado( 'praticas', $acaoSolicitada , $Aplic->usuario_id, 'indicador_valor');

$editar = permiteEditarIndicador($pratica_indicador['pratica_indicador_acesso'],$pratica_indicador['pratica_indicador_id']);

$podeEditarTudo= ( $pratica_indicador['pratica_indicador_acesso']>=5 ? $editar && (in_array($pratica_indicador['pratica_indicador_responsavel'], $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);

if( $negadoAcaoValor || (!$podeEditarTudo && !$podeEditar && !$podeValor) ){
   $Aplic->redirecionar('m=publico&a=acesso_negado');
}


$botoesTitulo = new CBlocoTitulo('Valor do Indicador', 'indicador.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao($Aplic->getPosicao(), 'voltar','','Voltar','Voltar a tela anterior.');
$botoesTitulo->mostrar();


$sql->adTabela('pratica_indicador_valor');
$sql->adCampo('pratica_indicador_valor.*');
$sql->adOnde('pratica_indicador_valor_id='.(int)$pratica_indicador_valor_id);
$pratica_indicado_valor=$sql->Linha();
$sql->limpar();



//verificar se tem restrição de prazo
$sql->adTabela('pratica_indicador_prazo');
$sql->adCampo('count(pratica_indicador_prazo_id)');
$sql->adOnde('pratica_indicador_prazo_indicador='.(int)$pratica_indicador_id);
$tem_prazo=$sql->Resultado();
$sql->limpar();

$faixa = array('inicio' => '1970-01-01 00:00:00', 'fim' => '2999-12-31 23:59:00');

if($tem_prazo){
	$sql->adTabela('pratica_indicador_prazo');
	$sql->adCampo('pratica_indicador_prazo_valor_inicio AS inicio, pratica_indicador_prazo_valor_fim AS fim');
	$sql->adOnde('pratica_indicador_prazo_insercao_inicio <= now()');
	$sql->adOnde('pratica_indicador_prazo_insercao_fim >= now()');
	$sql->adOnde('pratica_indicador_prazo_indicador='.(int)$pratica_indicador_id);
	$faixa=$sql->linha();
	$sql->limpar();
	}
else $faixa=array('inicio'=>null, 'fim'=>null);




$data = isset($pratica_indicado_valor['pratica_indicador_valor_data']) ? new CData($pratica_indicado_valor['pratica_indicador_valor_data']) : new CData();
echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="indicador_editar_valor" />';
echo '<input type="hidden" name="pratica_indicador_valor_id" id="pratica_indicador_valor_id" value="'.$pratica_indicador_valor_id.'" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="pratica_indicador_valor_obs2" id="pratica_indicador_valor_obs2" value="" />';

echo '<input type="hidden" name="tem_prazo" id="tem_prazo" value="'.$tem_prazo.'" />';
echo '<input type="hidden" name="inicio" id="inicio" value="'.($tem_prazo ? $faixa['inicio'] : '').'" />';
echo '<input type="hidden" name="fim" id="fim" value="'.$faixa['fim'].'" />';
echo '<input type="hidden" name="inicio_texto" id="inicio_texto" value="'.retorna_data($faixa['inicio'], false).'" />';
echo '<input type="hidden" name="fim_texto" id="fim_texto" value="'.retorna_data($faixa['fim'], false).'" />';


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="center" colspan="2"><h1>'.$pratica_indicador['pratica_indicador_nome'].'</h1></td></tr>';

echo '<tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica('Valor','Valor a ser inserido ou editado.').'&nbsp;<b>Valor</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Responsável pela Inserção', 'Todo indicador ao ter valor inserido deve ter um responsável.').'Responsável:'.dicaF().'</td><td><input type="hidden" id="pratica_indicador_valor_responsavel" name="pratica_indicador_valor_responsavel" value="'.(isset($pratica_indicado_valor['pratica_indicador_valor_responsavel']) && $pratica_indicado_valor['pratica_indicador_valor_responsavel'] ? $pratica_indicado_valor['pratica_indicador_valor_responsavel'] : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om((isset($pratica_indicado_valor['pratica_indicador_valor_responsavel']) && $pratica_indicado_valor['pratica_indicador_valor_responsavel'] ? $pratica_indicado_valor['pratica_indicador_valor_responsavel'] : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data da Aferição', 'Quando foi aferido o valor.').'Data da Aferição:'.dicaF().'</td><td><input type="hidden" name="pratica_indicador_valor_data" id="pratica_indicador_valor_data" value="'.($data ? $data->format('%Y%m%d') : '').'" /><input type="text" name="data" id="data" style="width:70px;" onchange="setData(\'env\', \'data\', \'pratica_indicador_valor_data\');" value="'.($data ? $data->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data em que foi aferido o valor.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor', 'O valor aferido para este indicador.').'Valor:'.dicaF().'</td><td><input type="text" name="pratica_indicador_valor_valor" id="pratica_indicador_valor_valor" onkeypress="return entradaNumerica(event, this, true, true);" value="'.($pratica_indicado_valor['pratica_indicador_valor_valor'] ? number_format($pratica_indicado_valor['pratica_indicador_valor_valor'], $config['casas_decimais'], ',', '.') : '').'" class="texto" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Observação', 'Observação sobre esta inserção.').'Observação:'.dicaF().'</td><td colspan=2 align="left" style="width:284px; background:#ffffff;"><textarea rows="3" name="pratica_indicador_valor_obs" id="pratica_indicador_valor_obs" data-gpweb-cmp="ckeditor">'.($pratica_indicado_valor['pratica_indicador_valor_obs'] ? $pratica_indicado_valor['pratica_indicador_valor_obs'] : '').'</textarea></td></tr>';

echo '</table></fieldset></td>';

$podeAdicionarValor=$Aplic->checarModulo( 'praticas', 'adicionar' , $Aplic->usuario_id, 'indicador_valor');
$negadoAdicionarValor = $Aplic->checarModuloNegado( 'praticas', 'adicionar' , $Aplic->usuario_id, 'indicador_valor');

if( !$negadoAdicionarValor || ( $podeEditarTudo || $podeEditar || $podeAdicionarValor) ){
    echo '<td id="adicionar_valor" style="display:'.($pratica_indicador_valor_id ? 'none' : '').'"><a href="javascript: void(0);" onclick="incluir_valor();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o valor.').'</a></td>';
}

echo '<td id="confirmar_valor" style="display:'.($pratica_indicador_valor_id ? '' : 'none').'"><a href="javascript: void(0);" onclick="limpar();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do valor.').'</a><a href="javascript: void(0);" onclick="incluir_valor();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do valor.').'</a></td>';
echo '</tr>';
echo '</table></td></tr>';

$sql = new BDConsulta;
$sql->adTabela('pratica_indicador_valor');
$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = pratica_indicador_valor_responsavel');
$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS dono, pratica_indicador_valor_id, pratica_indicador_valor_valor, formatar_data(pratica_indicador_valor_data, "%d/%m/%Y") AS data, pratica_indicador_valor_data, pratica_indicador_valor_obs');
$sql->adOnde('pratica_indicador_valor_indicador = '.(int)$pratica_indicador_id);
$sql->adOrdem('pratica_indicador_valor_data DESC');
$valores = $sql->Lista();
$sql->limpar();


echo '<tr><td colspan=20><div id="combo_valores">';
if (count($valores)){
	echo '<table cellspacing=0 cellpadding=2 class="tbl1" width="100%"><tr>';
	echo '<th>'.dica('Data', 'Data de inserção do valor.').'Data'.dicaF().'</th>';
	echo '<th>'.dica('Valor', 'O valor inserido no indicador.').'Valor'.dicaF().'</th>';
	echo '<th>'.dica('Responsável', 'Responsável pela inserção do valor.').'Responsável'.dicaF().'</th>';
	echo '<th>'.dica('Observações', 'Observações neste valor.').'Observações'.dicaF().'</th>';
	echo '<th></th></tr>';
	echo '';

    $podeEditarValor=$Aplic->checarModulo( 'praticas', 'editar' , $Aplic->usuario_id, 'indicador_valor');
    $negadoEditarValor = $Aplic->checarModuloNegado( 'praticas', 'editar' , $Aplic->usuario_id, 'indicador_valor');
    $podeExcluirValor=$Aplic->checarModulo( 'praticas', 'excluir' , $Aplic->usuario_id, 'indicador_valor');
    $negadoExcluirValor=$Aplic->checarModuloNegado( 'praticas', 'excluir' , $Aplic->usuario_id, 'indicador_valor');

	foreach($valores as $valor){
		echo '<tr><td width="60" style="white-space: nowrap" align=center>'.$valor['data'].'</td>';
		echo '<td width="60" style="white-space: nowrap" align=right>'.number_format($valor['pratica_indicador_valor_valor'], $config['casas_decimais'], ',', '.').'</td>';
		echo '<td>'.$valor['dono'].'</td>';
		echo '<td>'.($valor['pratica_indicador_valor_obs']? $valor['pratica_indicador_valor_obs'] : '&nbsp;').'</td>';
		echo '<td width="'.($Aplic->profissional ? '48' : '32').'" align=center>';
		if (!$Aplic->profissional || ($Aplic->profissional  && !$tem_prazo) || ($Aplic->profissional  && $tem_prazo && $valor['pratica_indicador_valor_data']>=$faixa['inicio'] && $valor['pratica_indicador_valor_data']<=$faixa['fim'])){
            if($podeEditarTudo || $podeEditar || $podeEditarValor || $podeExcluirValor ){
                if(!$negadoEditarValor){
                    if ($Aplic->profissional) echo '<a href="javascript: void(0);" onclick="anexar_arquivo('.$valor['pratica_indicador_valor_id'].');">'.imagem('icones/anexar.png', 'Anexar Arquivo', 'Clique neste ícone '.imagem('icones/anexar.png').' para anexar arquivo junto ao valor.').'</a>';
                    echo '<a href="javascript: void(0);" onclick="editar_valor('.$valor['pratica_indicador_valor_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o valor.').'</a>';
                }

                if(!$negadoExcluirValor){
                    echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este valor?\')) {excluir_valor('.$valor['pratica_indicador_valor_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este valor.').'</a>';
                }
            }
		}
		echo '</td>';
		echo '</tr>';
		}
	}	
echo '</div></table></td></tr>';




echo '</table>';


echo '</form>';


echo estiloFundoCaixa();

?>
<script type="text/javascript">

function anexar_arquivo(pratica_indicador_valor_id){
	parent.gpwebApp.popUp('Anexar Arquivo', 400, 400, 'm=praticas&a=indicador_valor_anexo_pro&dialogo=1&pratica_indicador_valor_id='+pratica_indicador_valor_id, null, window);
	}


function limpar(){
	document.getElementById('pratica_indicador_valor_id').value=null;
	document.getElementById('pratica_indicador_valor_valor').value='';
	CKEDITOR.instances['pratica_indicador_valor_obs'].setData('');
	document.getElementById('adicionar_valor').style.display='';
	document.getElementById('confirmar_valor').style.display='none';
	}

function editar_valor(pratica_indicador_valor_id){
	xajax_editar_valor(pratica_indicador_valor_id);
	CKEDITOR.instances['pratica_indicador_valor_obs'].setData(document.getElementById('pratica_indicador_valor_obs2').value);
	document.getElementById('adicionar_valor').style.display="none";
	document.getElementById('confirmar_valor').style.display="";
	}

function incluir_valor(){
	if (document.getElementById('pratica_indicador_valor_valor').value.length < 1){
		alert('Insira um valor.');
		return false;
		}

	

	if ((document.getElementById('tem_prazo').value > 0) && ((document.getElementById('pratica_indicador_valor_data').value < document.getElementById('inicio').value) || (document.getElementById('pratica_indicador_valor_data').value > document.getElementById('fim').value))){
		alert('A faixa de tempo necessita ser entre '+document.getElementById('inicio_texto').value+' e '+document.getElementById('fim_texto').value);
		
		document.getElementById('data').focus();
		return false;
		}
		
		
	xajax_incluir_valor(
	document.getElementById('pratica_indicador_valor_id').value,
	document.getElementById('pratica_indicador_id').value,
	document.getElementById('pratica_indicador_valor_responsavel').value,
	document.getElementById('pratica_indicador_valor_data').value,
	document.getElementById('pratica_indicador_valor_valor').value,
	CKEDITOR.instances['pratica_indicador_valor_obs'].getData());
	limpar();
	__buildTooltip();
		 
	}

function excluir_valor(pratica_indicador_valor_id){
	xajax_excluir_valor(pratica_indicador_valor_id, document.getElementById('pratica_indicador_id').value);
	__buildTooltip();
	}


function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}
	
function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&usuario_id='+document.getElementById('pratica_indicador_valor_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&usuario_id='+document.getElementById('pratica_indicador_valor_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('pratica_indicador_valor_responsavel').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

 var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "pratica_indicador_valor_data",
  	date :  <?php echo $data->format("%Y%m%d")?>,
  	selection: <?php echo $data->format("%Y%m%d")?>,
    onSelect: function(cal1) {
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pratica_indicador_valor_data").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide();
  	}
  });


function setData( frm_nome, f_data, f_data_real ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
    	}
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

</script>

