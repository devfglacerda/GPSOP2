<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

//ajuste de permissão para sub-módulo projetos
global $podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar;
list($podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar)  = listaPermissoes('projetos', 'projetos_lista');

$inserir=getParam($_REQUEST, 'inserir', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);

$inserido=getParam($_REQUEST, 'inserido', 0);
$alterado=getParam($_REQUEST, 'alterado', 0);

$Aplic->carregarCKEditorJS();


$projeto_id=getParam($_REQUEST, 'projeto_id', 0);


$sql = new BDConsulta;

$sql->adTabela('projetos');
$sql->adCampo('projeto_acesso');
$sql->adOnde('projeto_id='.(int)$projeto_id);
$projeto_acesso = $sql->Resultado();
$sql->limpar();

if (!permiteEditar($projeto_acesso, $projeto_id) || !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$botoesTitulo = new CBlocoTitulo('Baseline d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'projeto.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();




echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="projetos">';
echo '<input type=hidden id="a" name="a" value="baseline">';
echo '<input type=hidden id="projeto_id" name="projeto_id" value="'.$projeto_id.'">';
echo '<input type=hidden id="apoio_baseline_descricao" name="apoio_baseline_descricao" value="">';




echo estiloTopoCaixa();

echo '<table cellspacing=0 cellpadding=0 class="std" width="100%">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=2>';
echo '<tr><td align="right">'.dica('Nome', 'O nome da baseline.').'Nome:'.dicaF().'</td><td><input type="text" id="baseline_nome" name="baseline_nome" value="" style="width:400px;" class="texto" /></td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição','Descrição da baseline.').'Descrição:'.dicaF().'</td><td><textarea rows="4" class="texto" name="baseline_descricao" id="baseline_descricao" style="width:600px" data-gpweb-cmp="ckeditor" class="textarea"></textarea></td></tr>';






echo '<input type="hidden" id="baseline_id" name="baseline_id" value="" /></table></td><td id="adicionar_baseline" style="display:"><a href="javascript: void(0);" onclick="incluir_baseline();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir a baseline.').'</a></td>';
echo '<td id="confirmar_baseline" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'baseline_id\').value=0;document.getElementById(\'baseline_nome\').value=\'\'; document.getElementById(\'adicionar_baseline\').style.display=\'\';	document.getElementById(\'confirmar_baseline\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição da baseline .').'</a><a href="javascript: void(0);" onclick="incluir_baseline();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição da baseline.').'</a></td></tr>';
echo '<tr><td colspan=20 align=left>&nbsp</td></tr>';
$sql = new BDConsulta;

$sql->adTabela('baseline');
$sql->adOnde('baseline_projeto_id = '.(int)$projeto_id);
$sql->adCampo('baseline.*');
$sql->adOrdem('baseline_data');
$baselines=$sql->ListaChave('baseline_id');
$sql->limpar();

echo '<tr><td colspan=20 align=left><div id="combo_baselines">';
if (count($baselines)) {
	echo '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th>Nome</th><th>Descrição</th><th></th></tr>';
	foreach ($baselines as $baseline_id => $linha) {
		echo '<tr align="center">';

		echo '<td align="left">'.$linha['baseline_nome'].'</td>';
		echo '<td>'.$linha['baseline_descricao'].'</td>';
		echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_baseline('.$linha['baseline_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a baseline.').'</a>';
		
		
		$sql->adTabela('baseline_tarefas');
		$sql->adCampo('tempo_em_segundos(diferenca_tempo(NOW(),MIN(tarefa_inicio)))/3600');
		$sql->adOnde('baseline_id='.(int)$baseline_id);
		$resultado=$sql->resultado();
		$sql->limpar();

		if ($resultado > 0) echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta baseline?\')) {excluir_baseline('.$linha['baseline_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta baseline.').'</a>';
		echo '</td>';

		echo '</tr>';
		}
	echo '</table></td></tr></table>';
	}
echo '</div></td></tr>';
echo '</table></td></tr>';
echo '<tr><td colspan=20 align=center>&nbsp</td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td align="right">'.botao('retornar', 'Retornar', 'Retornar a tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table></td></tr>';

echo '</table>';
echo estiloFundoCaixa();









echo '</form>';

?>

<script type="text/javascript">

function editar_baseline(baseline_id){
	xajax_editar_baseline(baseline_id);
	
	
	
	CKEDITOR.instances['baseline_descricao'].setData(document.getElementById('apoio_baseline_descricao').value);
	
	
	
	
	document.getElementById('adicionar_baseline').style.display="none";
	document.getElementById('confirmar_baseline').style.display="";

	}

function incluir_baseline(){
	if (document.getElementById('baseline_nome').value!=''){
		xajax_incluir_baseline_ajax(<?php echo (int)$projeto_id ?>, document.getElementById('baseline_id').value, document.getElementById('baseline_nome').value, CKEDITOR.instances['baseline_descricao'].getData());
		document.getElementById('baseline_id').value=null;
		document.getElementById('baseline_nome').value='';
		
		CKEDITOR.instances['baseline_descricao'].setData('');
		
		document.getElementById('adicionar_baseline').style.display='';
		document.getElementById('confirmar_baseline').style.display='none';
		}
	else alert('Escolha um nome para a baseline.');
	}

function excluir_baseline(baseline_id){
	xajax_excluir_baseline_ajax(baseline_id);
	}
	
</script>