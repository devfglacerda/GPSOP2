<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de '.ucfirst($config['iniciativa']).'</h1></td></tr>'; 
//estrategias

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>'.ucfirst($config['iniciativa']).'</b></td><td></td></tr>';

	echo '<tr><td><input type="text" name="pg_estrategia_nome" id="pg_estrategia_nome" style="width:400px;" class="texto" value=""></td>';
	echo '<td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td>';
	
	echo '</tr></table></td></tr>';
	}

echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="" />';

echo '<tr><td colspan=20><div id="combo_estrategias">';

$sql->adTabela('plano_gestao_estrategias');
$sql->esqUnir('estrategias', 'estrategias', 'plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
$sql->adCampo('pg_estrategia_nome, pg_estrategia_cor, plano_gestao_estrategias.pg_estrategia_ordem, estrategias.pg_estrategia_id');
$sql->adOnde('plano_gestao_estrategias.pg_id='.(int)$pg_id);
$sql->adOrdem('pg_estrategia_ordem ASC');
$estrategias=$sql->Lista();

if (count($estrategias)) echo '<table class="tbl1" cellspacing=0 cellpadding=0><tr>'.($editarPG ? '<th></th>' : '').'<th style="white-space: nowrap">'.ucfirst($config['iniciativa']).'</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($estrategias as $estrategia) {
	echo'<tr>';
	if ($editarPG){
		echo' <td td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_estrategia('.$estrategia['pg_estrategia_ordem'].', '.$estrategia['pg_estrategia_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_estrategia('.$estrategia['pg_estrategia_ordem'].', '.$estrategia['pg_estrategia_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_estrategia('.$estrategia['pg_estrategia_ordem'].', '.$estrategia['pg_estrategia_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_estrategia('.$estrategia['pg_estrategia_ordem'].', '.$estrategia['pg_estrategia_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td>'.$estrategia['pg_estrategia_nome'].'</td>';
	if ($editarPG)	echo '<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_estrategia('.$estrategia['pg_estrategia_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
	echo '</tr>';
	}
if (count($estrategias)) echo '</table>';


echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'estrategias_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'metas_geral\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';



?>

<script type="text/javascript">
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}
	
function setEstrategia(chave, valor){
	if (chave > 0) {
		xajax_inserir_estrategia(chave, document.getElementById('pg_id').value);
		__buildTooltip();	
		}
	}	
	


function mudar_ordem_estrategia(ordem, pg_estrategia_id, direcao){
	xajax_mudar_ordem_estrategia(ordem, pg_estrategia_id, direcao, document.getElementById('pg_id').value);
	__buildTooltip();
	}

function excluir_estrategia(pg_estrategia_id){
	xajax_excluir_estrategia(pg_estrategia_id, document.getElementById('pg_id').value);
	__buildTooltip();
	}	
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_estrategia_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_estrategia_cor.value;
	}
</script>