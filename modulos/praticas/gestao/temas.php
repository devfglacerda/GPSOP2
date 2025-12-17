<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/



echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de '.ucfirst($config['temas']).'</h1></td></tr>'; 
//tema

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>'.ucfirst($config['tema']).'</b></td><td></td></tr>';

	echo '<tr><td><input type="text" name="tema_nome" id="tema_nome" style="width:400px;" class="texto" value=""></td>';
	echo '<td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Inserir '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].' a ser inserid'.$config['genero_tema'].'.').'</a></td>';
	
	echo '</tr></table></td></tr>';
	}

echo '<input type="hidden" name="tema_id" id="tema_id" value="" />';


echo '<tr><td colspan=20><div id="combo_temas">';



$sql->adTabela('plano_gestao_tema');
$sql->esqUnir('tema', 'tema', 'plano_gestao_tema.tema_id=tema.tema_id');
$sql->adCampo('tema_nome, tema_cor, plano_gestao_tema.tema_ordem, tema.tema_id');
$sql->adOnde('plano_gestao_tema.pg_id='.(int)$pg_id);
$sql->adOrdem('tema_ordem ASC');
$temas=$sql->Lista();

if (count($temas)) echo '<table class="tbl1" cellspacing=0 cellpadding=0><tr>'.($editarPG ? '<th></th>' : '').'<th style="white-space: nowrap">'.ucfirst($config['tema']).'</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($temas as $tema) {
	echo'<tr>';
	if ($editarPG){
		echo' <td td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_tema('.$tema['tema_ordem'].', '.$tema['tema_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_tema('.$tema['tema_ordem'].', '.$tema['tema_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_tema('.$tema['tema_ordem'].', '.$tema['tema_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_tema('.$tema['tema_ordem'].', '.$tema['tema_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td style="background-color: #'.$tema['tema_cor'].'; color:#'.melhorCor($tema['tema_cor']).'">'.$tema['tema_nome'].'</td>';
	if ($editarPG)	echo '<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_tema('.$tema['tema_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
	echo '</tr>';
	}
if (count($temas)) echo '</table>';

echo '</div></td></tr>';


echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'perspectivas\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'objetivo_geral\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';



?>

<script type="text/javascript">
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	
	
function setTema(chave, valor){
	if (chave > 0) {
		xajax_inserir_tema(chave, document.getElementById('pg_id').value);
		__buildTooltip();	
		}
	}	
	
function mudar_ordem_tema(ordem, tema_id, direcao){
	xajax_mudar_ordem_tema(ordem, tema_id, direcao, document.getElementById('pg_id').value);
	__buildTooltip();
	}

function excluir_tema(tema_id){
	xajax_excluir_tema(tema_id, document.getElementById('pg_id').value);
	__buildTooltip();
	}	
	
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.tema_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.tema_cor.value;
	}
</script>