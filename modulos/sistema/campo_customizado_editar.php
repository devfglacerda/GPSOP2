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
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campo_customizado_id=getParam($_REQUEST, 'campo_customizado_id', 0);
$excluirCampo=getParam($_REQUEST, 'excluir', 0);


$nome_modulo=getParam($_REQUEST, 'nome_modulo', '');
$modulo=getParam($_REQUEST, 'modulo', null);
$selecionarNovoItem=getParam($_REQUEST, 'selecionarNovoItem', null);
$selecionarNovoItemChave=getParam($_REQUEST, 'selecionarNovoItemChave', null);
$selecionarItens=getParam($_REQUEST, 'selecionarItens', array());
$excluirItem=getParam($_REQUEST, 'excluirItem', '');
$sim_nao = getSisValor('SimNaoGlobal');

$botoesTitulo = new CBlocoTitulo(($campo_customizado_id  ? 'Editar' : 'Adicionar').' Campo Customizado', 'customizado.png', 'admin', 'admin.campo_customizado_editar');
$botoesTitulo->mostrar();



$sql = new BDConsulta;
$sql->adTabela('campo_customizado');
$sql->adCampo('campo_customizado_id, campo_customizado_nome');
$sql->adOnde('campo_customizado_modulo = \''.$modulo.'\'');
$sql->adOnde('campo_customizado_tipo_html = \'valor\'');
$sql->adOrdem('campo_customizado_nome');
$variaveis=$sql->listaVetorChave('campo_customizado_id','campo_customizado_nome');
$sql->limpar();



if ($selecionarNovoItem != null) $selecionarItens[$selecionarNovoItemChave] = $selecionarNovoItem;

if ($excluirItem){
	$novo_itens_selecionados = array();
	foreach ($selecionarItens as $chave => $itm) if ($chave != $excluirItem) $novo_itens_selecionados[$chave] = $itm;

	$opcoes = new ListaOpcoesCustomizadas($campo_customizado_id);
	$opcoes->setOpcoes($novo_itens_selecionados);
	$opcoes->armazenar();

	unset($selecionarItens);
	$selecionarItens = $novo_itens_selecionados;
	}

if ($campo_customizado_id) {
	$campos_customizados = new CampoCustomizados($modulo, null, 'editar');

	if ($excluirCampo) {
		$campos_customizados->excluirCampo($campo_customizado_id);
		$Aplic->redirecionar('m=sistema&a=campo_customizado');
		}
	$cf = $campos_customizados->campoComId($campo_customizado_id);
	

	
	if (is_object($cf)) {
		$campo_customizado_nome = $cf->campoNome();
		$campo_customizado_formula = $cf->campoFormula();
		$campo_customizado_descricao = $cf->campoDescricao();
		$campo_customizado_tipo_html = $cf->campoTipoHtml();
		$campo_customizado_tags_extras = $cf->campoTagExtra();
		$campo_customizado_ordem = $cf->campoOrdem();
		$campo_customizado_publicado = $cf->campoPublicado();
		
		$campo_customizado_descendente=$cf->campoDescendente();
		$campo_customizado_por_chave=$cf->campoPorChave();
		
		
		
		if ($campo_customizado_tipo_html == 'selecionar') {
			$opcoesSelecao = new ListaOpcoesCustomizadas($campo_customizado_id);
			$opcoesSelecao->load();
			$selecionarItens = $opcoesSelecao->getOpcoes();
			}
		}
	else {
		$Aplic->setMsg('Não foi possível carregar o campo customizado. Pode ter sido excluído de alguma maneira.');
		$Aplic->redirecionar('m=sistema&a=campo_customizado');
		}
	$editarTitulo = 'Editar campo customizado em ';
	}
else {
	$editarTitulo = 'Novo campo customizado em ';
	$campo_customizado_nome=getParam($_REQUEST, 'campo_customizado_nome', null);
	$campo_customizado_descricao=getParam($_REQUEST, 'campo_customizado_descricao', null);
	$campo_customizado_formula=getParam($_REQUEST, 'campo_customizado_formula', null);
	$campo_customizado_tipo_html=getParam($_REQUEST, 'campo_customizado_tipo_html', 'textinput');
	$campo_customizado_tags_extras=getParam($_REQUEST, 'campo_customizado_tags_extras', null);
	$campo_customizado_ordem=getParam($_REQUEST, 'campo_customizado_ordem', null);
	$campo_customizado_publicado=getParam($_REQUEST, 'campo_customizado_publicado', 0);
	$campo_customizado_descendente=getParam($_REQUEST, 'campo_customizado_descendente', 0);
	$campo_customizado_por_chave=getParam($_REQUEST, 'campo_customizado_por_chave', 0);
	
	
	}
$html_tipos = array('textinput' => 'Texto de entrada', 'textarea' => 'Área de texto', 'checkbox' => 'Caixa de seleção', 'selecionar' => 'Lista de seleção', 'label' => 'Rótulo', 'separator' => 'Separador', 'href' => 'Link Web');
if ($Aplic->profissional) {
    $html_tipos['data']='Data';
	$html_tipos['valor']='Valor';
	$html_tipos['formula']='Fórmula';
	}

$estadoVisibilidade = array();
foreach ($html_tipos as $k => $ht) {
	if ($k == $campo_customizado_tipo_html) $estadoVisibilidade['div_'.$k] = 'display : block';
	else $estadoVisibilidade['div_'.$k] = 'display : none';
	}

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="a" value="campo_customizado" />';
echo '<input type="hidden" name="campo_customizado_id" id="campo_customizado_id" value="'.$campo_customizado_id.'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($campo_customizado_id ? null : uuid()).'" />';
echo '<input type="hidden" name="modulo" value="'.$modulo.'" /> ';
echo '<input type="hidden" name="nome_modulo" value="'.$nome_modulo.'" /> ';
echo '<input type="hidden" name="fazerSQL" id="fazerSQL" value="fazer_campo_customizado_aed" />';

echo estiloTopoCaixa();
echo '<table class="std" width="100%" cellspacing=0 cellpadding=0 >';
echo '<tr><td colspan="2"><h1><center>'.$editarTitulo.($nome_modulo ? $nome_modulo : $modulo).'</center></h1></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap" width="100">'.dica ('Identificação','Cada campo deverá ter um nome distinto e não pode conter espaços entre as palavras.').'Identificação:'.dicaF().'</td><td><input type="text" class="texto" name="campo_customizado_nome" id="campo_customizado_nome" maxlength="100" value="'.$campo_customizado_nome.'" onblur=\'this.value=this.value.replace(/[^a-z|^A-Z|^0-9]*/gi,"");\' /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica ('Descrição','Texto que será mostrado ao lado do campo, nas telas do sistema.').'Descrição:'.dicaF().'</td><td><input type="text" class="texto" name="campo_customizado_descricao" size="40" maxlength="250" value="'.$campo_customizado_descricao.'" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica ('Tipo','Escolha um tipo de campo que atenda a necessidade de armazenamento do tipo de informação necessária.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($html_tipos, 'campo_customizado_tipo_html', 'class="texto" onChange="javascript:mostrarAtributos()"', $campo_customizado_tipo_html).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica ('Ordem','Ordem em que os campos customizados irão ser mostrado nas telas do sistema. Valores mais baixo são mostrado primeiramente.').'Ordem:'.dicaF().'</td><td><input style="text-align:right" type="text" class="texto" size="4" name="campo_customizado_ordem" maxlength="3" value="'.$campo_customizado_ordem.'" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica ('Código HTML','Informações extra que deseja embutir no campo. Pode ser desde uma formatação, até código javascript').'HTML:'.dicaF().'</td><td><input type="text" class="texto" size="80" name="campo_customizado_tags_extras" value="'.$campo_customizado_tags_extras.'" /></td></tr>';

$tipo_ordenacao=array(0 => 'Pelos Valores', 1=>'Pelas Chaves');
echo '<tr id="extra1_selecionar" style="display:'.($campo_customizado_tipo_html=='selecionar' ? '' : 'none').'"><td align="right" style="white-space: nowrap">'.dica ('Tipo de Ordenação','Escolha se a ordenação da lista será pelos valores ou pelas chaves.').'Tipo de Ordenação:'.dicaF().'</td><td>'.selecionaVetor($tipo_ordenacao, 'campo_customizado_por_chave', 'class="texto"', $campo_customizado_por_chave).'</td></tr>';
$vetor_descendente=array(0 => 'Ascentende', 1=>'Descendente');
echo '<tr id="extra2_selecionar" style="display:'.($campo_customizado_tipo_html=='selecionar' ? '' : 'none').'"><td align="right" style="white-space: nowrap">'.dica ('Ordenação','Escolha se a ordenação da lista será ascendente ou descentente, alfabeticamente.').'Ordenação:'.dicaF().'</td><td>'.selecionaVetor($vetor_descendente, 'campo_customizado_descendente', 'class="texto"', $campo_customizado_descendente).'</td></tr>';


if ($Aplic->profissional){
	echo '<tr><td colspan="2"><div id="div_formula" style="'.$estadoVisibilidade['div_formula'].'"><table id="atbl_formula">';
	if (count($variaveis)){
		echo '<tr><td><table cellpadding=0 cellspacing=0 class="tbl1">';
		foreach($variaveis as $chave => $valor){
			echo '<tr><td align="right" > I'.$chave.'</td><td>'.$valor.'</td></tr>';
			}
		echo '</table></td></tr>';
		}
	echo '<tr><td align="right">'.dica ('Fórmula','Texto que será mostrado ao lado do campo, nas telas do sistema.').'Fórmula:'.dicaF().'</td><td><textarea name="campo_customizado_formula" style="width:284px;" rows="3" class="textarea">'.$campo_customizado_formula.'</textarea></td></tr>';
	echo '</div></table></td></tr>';
	}




echo '<tr><td colspan="2"><div id="div_selecionar" style="'.$estadoVisibilidade['div_selecionar'].'">';
echo '<table cellspacing=0 cellpadding=0 id="atbl_selecionar">';



echo '<tr><td><table cellspacing=0 cellpadding=0>';
echo '<input type="hidden" name="campo_customizado_lista_id" id="campo_customizado_lista_id" value="" />';

echo '<tr><td align="right" style="white-space: nowrap" width=106>'.dica('Chave', 'A chave da opção da lista.').'Chave:'.dicaF().'</td><td><input type="text" class="texto" name="campo_customizado_lista_opcao" id="campo_customizado_lista_opcao" value="" maxlength="255" style="width:391px;" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap" width=106>'.dica('Valor', 'O valor apresentado da opção da lista.').'Valor:'.dicaF().'</td><td><input type="text" class="texto" name="campo_customizado_lista_valor" id="campo_customizado_lista_valor" value="" maxlength="50" style="width:391px;" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milhões possíveis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inserção do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="campo_customizado_lista_cor" id="campo_customizado_lista_cor" value="FFFFFF" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';

echo '</table></td>';
echo '<td id="adicionar_lista" style="display:"><a href="javascript: void(0);" onclick="incluir_lista();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o item.').'</a></td>';
echo '<td id="confirmar_lista" style="display:none"><a href="javascript: void(0);" onclick="limpar_lista();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do item.').'</a><a href="javascript: void(0);" onclick="incluir_lista();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do item.').'</a></td>';
echo '</tr></table>';

echo '<div id="combo_lista">';
$sql->adTabela('campo_customizado_lista');
$sql->adCampo('campo_customizado_lista.*');
$sql->adOnde('campo_customizado_lista_campo ='.(int)$campo_customizado_id);
$linhas=$sql->Lista();
$sql->limpar();


if (count($linhas)){
	echo '<tr><td width=106>&nbsp;</td><td><table cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th></th><th>'.dica('Chave', 'A chave da opção da lista.').'Chave'.dicaF().'</th><th>'.dica('Valor', 'O valor apresentado da opção da lista.').'Valor'.dicaF().'</th><th>'.dica('Cor', 'A cor apresentada da opção da lista.').'Cor'.dicaF().'</th><th></th></tr>';
	}
$total=0;
$lista=array();
foreach ($linhas as $linha) {
	echo '<tr align="center">';
	echo '<td width="16">'.dica('Editar Item', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item.').'<a href="javascript:void(0);" onclick="javascript:editar_lista('.$linha['campo_customizado_lista_id'].'	);">'.imagem('icones/editar.gif').'</a>'.dicaF().'</td>';
	echo '<td align="center">'.$linha['campo_customizado_lista_opcao'].'</td>';
	echo '<td align="left">'.($linha['campo_customizado_lista_valor'] ? $linha['campo_customizado_lista_valor'] : '&nbsp;').'</td>';
	echo '<td width="16" align="right" style="background-color:#'.($linha['campo_customizado_lista_cor'] ? $linha['campo_customizado_lista_cor'] : 'FFFFFF').'">&nbsp;&nbsp;</td>';
	echo '<td width="16">'.dica('Excluir Item', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item.').'<a href="javascript:void(0);" onclick="javascript:excluir_lista('.$linha['campo_customizado_lista_id'].');">'.imagem('icones/remover.png').'</a>'.dicaF().'</td>';	
	echo '</tr>';
	}
if (count($linhas)) echo '</table></td></tr>';

echo '</div>';


















echo '</div></td></tr>';


if ($Aplic->profissional) echo '<tr><td colspan="2" id="div_valor" style="'.$estadoVisibilidade['div_valor'].'"><table id="atbl_valor"></table><td></tr>';
echo '<tr id="div_textinput" style="'.$estadoVisibilidade['div_textinput'].'"><td colspan="2" ><table id="atbl_textinput"></table><td></tr>';
echo '<tr id="div_textarea" style="'.$estadoVisibilidade['div_textarea'].'"><td colspan="2"><table id="atbl_textarea"></table><td></tr>';
echo '<tr id="div_checkbox" style="'.$estadoVisibilidade['div_checkbox'].'"><td colspan="2"><table id="atbl_checkbox"></table><td></tr>';
echo '<tr id="div_label" style="'.$estadoVisibilidade['div_label'].'"><td colspan="2"><table id="atbl_label"></table><td></tr>';
echo '<tr id="div_separator" style="'.$estadoVisibilidade['div_separator'].'"><td colspan="2"><table id="atbl_separator"></table><td></tr>';
if ($Aplic->profissional) echo '<tr id="div_data" style="'.$estadoVisibilidade['div_data'].'"><td colspan="2"><table id="atbl_data"></table><td></tr>';

echo '<tr><td colspan="2" align="right"><table width="100%"><tr><td>'.botao('salvar', 'Salvar','Pressione este botão para confirmar os dados e salvar no sistema.','','adicionarItemSelecionado()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Retornar à tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr></table></td></tr>';
echo '</table>';
echo '</form>';
echo estiloFundoCaixa();
?>
<script>

function setCor(cor) {
	if (cor) 	document.getElementById('campo_customizado_lista_cor').value=cor;
	document.getElementById('teste').style.background = '#' + document.getElementById('campo_customizado_lista_cor').value;
	}


function editar_lista(campo_customizado_lista_id){
	xajax_editar_lista(campo_customizado_lista_id);
	document.getElementById('adicionar_lista').style.display="none";
	document.getElementById('confirmar_lista').style.display="";
	}


function limpar_lista(){

	document.getElementById('campo_customizado_lista_id').value=null;
	document.getElementById('campo_customizado_lista_valor').value='';
	document.getElementById('campo_customizado_lista_cor').value='';
	document.getElementById('campo_customizado_lista_opcao').value='';
	document.getElementById('adicionar_lista').style.display='';
	document.getElementById('confirmar_lista').style.display='none';
	}



function incluir_lista(edicao){
	xajax_incluir_lista_ajax(
		document.getElementById('campo_customizado_id').value,
		document.getElementById('uuid').value,
		document.getElementById('campo_customizado_lista_id').value,
		document.getElementById('campo_customizado_lista_opcao').value,
		document.getElementById('campo_customizado_lista_valor').value,
		document.getElementById('campo_customizado_lista_cor').value
		);
	__buildTooltip();
	limpar_lista();
	}

function excluir_lista(campo_customizado_lista_id){
	if (confirm('Tem certeza que deseja excluir?')) {
		xajax_excluir_lista_ajax(campo_customizado_lista_id, document.getElementById('campo_customizado_id').value, document.getElementById('uuid').value);
		__buildTooltip();
		}
	}



function ocultarTudo() {
	var selobj = document.getElementById('campo_customizado_tipo_html');
	for (i = 0, i_cmp = selobj.options.length; i < i_cmp; i++) {
		var atbl = document.getElementById('atbl_'+selobj.options[i].value);
		var adiv = document.getElementById('div_'+selobj.options[i].value);
		if (atbl != null) atbl.style.visibility = 'hidden';
		if (adiv != null) adiv.style.display = 'none';
		}
	document.getElementById('extra1_selecionar').style.display = 'none';
	document.getElementById('extra2_selecionar').style.display = 'none';	
	}

function mostrarAtributos() {
	ocultarTudo();
	var selobj = document.getElementById('campo_customizado_tipo_html');
	var seltipo = selobj.options[selobj.selectedIndex].value;
	var atbl = document.getElementById('atbl_'+seltipo);
	var adiv = document.getElementById('div_'+seltipo);

	atbl.style.visibility = 'visible';
	adiv.style.display = 'block';
	
	if (seltipo=='selecionar') {
		document.getElementById('extra1_selecionar').style.display = '';
		document.getElementById('extra2_selecionar').style.display = '';
		}
	}

function adicionarItemSelecionado() {
	if (env.campo_customizado_nome.value.length < 3) {
		alert('Escreva um Identificação válida');
		env.campo_customizado_nome.focus();
		}
	else env.submit();
	}

function excluirItem( itmname ) {
	del = document.getElementById('excluirItem');
	del.value = itmname;
	document.getElementById('fazerSQL').value='';
	adicionarItemSelecionado();
	}

</script>
