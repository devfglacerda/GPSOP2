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

$historico_id = defVal(getParam($_REQUEST, 'historico_id', null), 0);
if ($historico_id && !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$historico_id && !$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$acao=getParam($_REQUEST, 'acao', null);
$sql = new BDConsulta;
if ($acao) {
	$historico_descricao=getParam($_REQUEST, 'historico_descricao', '');
	$historico_projeto=getParam($_REQUEST, 'historico_projeto', '');
	$usuarioid = $Aplic->usuario_id;
	if ($acao == 'adicionar') {
		if (!$Aplic->checarModulo('historico', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
		$sql->adTabela('historico');
		$sql->adInserir('historico_tabela', "historico");
		$sql->adInserir('historico_acao', "add");
		$sql->adInserir('historico_data', date('Y-m-d H:i:s'));
		$sql->adInserir('historico_descricao', $historico_descricao);
		$sql->adInserir('historico_usuario', $usuarioid);
		$sql->adInserir('historico_projeto', $historico_projeto);
		$okMsg = 'Histórico adicionado';
		} 
	elseif ($acao == 'atualizar') {
		if (!$Aplic->checarModulo('historico', 'editar'))	$Aplic->redirecionar('m=publico&a=acesso_negado');
		$sql->adTabela('historico');
		$sql->adAtualizar('historico_descricao', $historico_descricao);
		$sql->adAtualizar('historico_projeto', $historico_projeto);
		$sql->adOnde('historico_id ='.$historico_id);
		$okMsg = 'Histórico atualizado';
		} 
	elseif ($acao == 'del') {
		if (!$Aplic->checarModulo('historico', 'excluir')) $Aplic->redirecionar('m=publico&a=acesso_negado');
		$sql->setExcluir('historico');
		$sql->adOnde('historico_id ='.$historico_id);
		$okMsg = 'Histórico excluído';
		}
	if (!$sql->exec()) $Aplic->setMsg(db_error());
	else {
		$Aplic->setMsg($okMsg);
		if ($acao == 'adicionar') $sql->limpar();
		$sql->adTabela('historico');
		$sql->adAtualizar('historico_item = historico_id');
		$sql->adOnde('historico_tabela = \'historico\'');
		$okMsg = 'Histórico excluído';
		}
	$sql->limpar();
	$Aplic->redirecionar('m=historico');
	}
$sql->adTabela('historico');
$sql->adCampo('*');
$sql->adOnde('historico_id ='.$historico_id);
$historico = $sql->Linha();
$sql->limpar();
$botoesTitulo = new CBlocoTitulo($historico_id ? 'Editar histórico' : 'Novo histórico', 'historico.png', 'historico', 'historico.'.$a);
if ($podeExcluir) $botoesTitulo->adicionaBotaoExcluir('excluir histórico', $podeExcluir, $msg,'Excluir Histórico','Excluir este histórico.');
$botoesTitulo->mostrar();
?>
<script>
function excluir() {
	document.AdEditar.acao.value = 'del';
	document.AdEditar.submit();
	}	
</script>
<?php
echo '<form name="AdEditar" method="post">';
echo '<input name="acao" type="hidden" value="'.($historico_id ? 'atualizar' : 'adicionar').'" />';
echo '<table border=0 cellpadding=0 cellspacing=1 width="100%" class="std">';
echo '<tr><td><table border="1" cellpadding=0 cellspacing=1 width="100%" class="std">	';	
echo '<tr><td align="right" style="white-space: nowrap">'.ucfirst($config['projeto']).':</td><td width="60%">'.projetoEscolhe('historico_projeto', 'size="1" class="texto"', $historico['historico_projeto']).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">Descrição:</td><td width="60%"><textarea name="historico_descricao" class="textarea" cols="60" rows="5">'.$historico['historico_descricao'].'</textarea></td></tr>';	
echo '</table>';
echo '<table border=0 cellspacing=0 cellpadding="3" width="100%">';
echo '<tr><td height="40" width="30%">&nbsp;</td><td  height="40" width="35%" align="right">';
echo '<table><tr><td>'.botao('cancelar', 'Cancelar', 'Retornar à tela anterior.','','if(confirm(\'Tem certeza que deseja cancelar?\'))url_passar(0, \''.$Aplic->getPosicao().'\');').'</td><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','AdEditar.submit()').'</td></tr></table>';
echo '</td></tr></table></td></tr></form>';
?>