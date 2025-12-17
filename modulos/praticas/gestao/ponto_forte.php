<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if ($editarPG) $Aplic->carregarCKEditorJS();

echo '<input type="hidden" id="pg_swot_swot" name="pg_swot_swot" value="" />';
echo '<input type="hidden" id="pg_swot_id" name="pg_swot_id" value="" />';
echo '<input type="hidden" id="apoio1" name="apoio1" value="" />';

echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Ambiente Interno - Lista de Forças</h1></td></tr>';

//ponto_fortes
$swot_ativo=($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null));

if ($editarPG){
	if ($swot_ativo) {
		echo '<tr><td align="right" width="10" style="white-space: nowrap">'.dica('Força', 'Selecione uma força.').'Força:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" id="nome_swot" name="nome_swot" value="" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT2();">'.imagem('icones/swot_p.png','Selecionar','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar uma força.').'</a></td></tr></table></td></tr>';
		echo '<input type="hidden" id="pg_swot_nome" name="pg_swot_nome" value="" />';
		}
	else {
		echo '<tr><td width="810"><textarea data-gpweb-cmp="ckeditor" rows="3" name="pg_swot_nome" id="pg_swot_nome" ></textarea></td>';
		echo '<td id="adicionar_swot" style="display:"><a href="javascript: void(0);" onclick="inserir_swot();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir.').'</a></td>';
		echo '<td id="confirmar_swot" style="display:none"><a href="javascript: void(0);" onclick="cancelar_edicao_swot();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar.').'</a><a href="javascript: void(0);" onclick="inserir_swot();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar.').'</a></td></tr>';
		}
	}


echo '<tr><td colspan=20><div id="combo_swots">';


$sql->adTabela('pg_swot');
$sql->esqUnir('swot', 'swot', 'pg_swot_swot=swot_id');
$sql->adCampo('pg_swot_id, pg_swot_nome, swot_nome, pg_swot_ordem');
$sql->adOnde('pg_swot_pg='.(int)$pg_id);
$sql->adOnde('pg_swot_tipo=\'s\'');
$sql->adOrdem('pg_swot_ordem ASC');
$swots=$sql->Lista();
$sql->limpar();

if (count($swots)) echo '<table class="tbl1" cellspacing=0 cellpadding=0><tr>'.($editarPG ? '<th></th>' : '').'<th style="white-space: nowrap">Força</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($swots as $swot) {
	echo'<tr>';
	if ($editarPG){
		echo' <td td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td>'.($swot['swot_nome'] ? $swot['swot_nome'] : $swot['pg_swot_nome']).'</td>';
	if ($editarPG){
		echo ($swot_ativo ?'<td width="16" align="center">' : '<td width="32" align="center"><a href="javascript: void(0);" onclick="editar_swot('.$swot['pg_swot_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar.').'</a>');
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_swot('.$swot['pg_swot_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		}
	echo '</tr>';
	}
if (count($swots)) echo '</table>';

echo '</div></td></tr>';




echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'ponto_forte_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'oportunidade_melhoria_geral\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';

?>
<script type="text/javascript">

var swot_ativo=<?php echo ($swot_ativo ? 1 : 0)?>;

function popSWOT2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Força', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setSWOT&swot_tipo=s&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT2, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setSWOT2&swot_tipo=s&tabela=swot&cia_id='+document.getElementById('cia_id').value, 'Força','left=0,top=0,height=700,width=1000,scrollbars=yes, resizable=yes');
	}

function setSWOT2(chave, valor){
	document.getElementById('pg_swot_swot').value=chave;
	if (chave > 0) inserir_swot();	
	}
	
function inserir_swot(){
	if (!swot_ativo && !CKEDITOR.instances['pg_swot_nome'].getData()){
		alert('Escreva um texto');
		document.getElementById('pg_swot_nome').focus();
		return;
		}

	xajax_inserir_swot(
		document.getElementById('pg_swot_id').value,
		document.getElementById('pg_id').value,
		document.getElementById('pg_swot_swot').value,
		(!swot_ativo ? CKEDITOR.instances['pg_swot_nome'].getData() : null),
		's'
		);
	__buildTooltip();	
	cancelar_edicao_swot();
	}

function mudar_ordem_swot(ordem, pg_swot_id, direcao){
	xajax_mudar_ordem_swot(ordem, pg_swot_id, direcao, document.getElementById('pg_id').value, 's');
	__buildTooltip();
	}

function excluir_swot(pg_swot_id){
	xajax_excluir_swot(pg_swot_id, document.getElementById('pg_id').value, 's');
	__buildTooltip();
	}

function editar_swot(pg_swot_id){
	xajax_editar_swot(pg_swot_id);

	CKEDITOR.instances['pg_swot_nome'].setData(document.getElementById('apoio1').value);
	document.getElementById('adicionar_swot').style.display="none";
	document.getElementById('confirmar_swot').style.display='';
	}

function cancelar_edicao_swot(){
	document.getElementById('pg_swot_id').value=null;
	document.getElementById('pg_swot_swot').value=null;
	if (!swot_ativo) {
		CKEDITOR.instances['pg_swot_nome'].setData('');
		document.getElementById('confirmar_swot').style.display='none';
		document.getElementById('adicionar_swot').style.display='';
		}
	}	
	
	

</script>