<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/






echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de '.ucfirst($config['perspectivas']).'</h1></td></tr>'; 
//perspectivas

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>'.ucfirst($config['perspectiva']).'</b></td><td></td></tr>';
	echo '<tr><td><input type="text" name="pg_perspectiva_nome" id="pg_perspectiva_nome" style="width:400px;" class="texto" value=""></td>';
	echo '<td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Inserir '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].'.').'</a></td>';
	echo '</tr></table></td></tr>';
	}


echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="" />';

echo '<tr><td colspan=20><div id="combo_perspectivas">';

$sql->adTabela('plano_gestao_perspectivas');
$sql->esqUnir('perspectivas', 'perspectivas', 'plano_gestao_perspectivas.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
$sql->adCampo('pg_perspectiva_nome, pg_perspectiva_cor, plano_gestao_perspectivas.pg_perspectiva_ordem, perspectivas.pg_perspectiva_id');
$sql->adOnde('plano_gestao_perspectivas.pg_id='.(int)$pg_id);
$sql->adOrdem('pg_perspectiva_ordem ASC');
$perspectivas=$sql->Lista();

if (count($perspectivas)) echo '<table class="tbl1" cellspacing=0 cellpadding=0><tr>'.($editarPG ? '<th></th>' : '').'<th style="white-space: nowrap">'.ucfirst($config['perspectiva']).'</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($perspectivas as $perspectiva) {
	echo'<tr>';
	if ($editarPG){
		echo' <td td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_perspectiva('.$perspectiva['pg_perspectiva_ordem'].', '.$perspectiva['pg_perspectiva_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_perspectiva('.$perspectiva['pg_perspectiva_ordem'].', '.$perspectiva['pg_perspectiva_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_perspectiva('.$perspectiva['pg_perspectiva_ordem'].', '.$perspectiva['pg_perspectiva_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_perspectiva('.$perspectiva['pg_perspectiva_ordem'].', '.$perspectiva['pg_perspectiva_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td style="background-color: #'.$perspectiva['pg_perspectiva_cor'].'; color:#'.melhorCor($perspectiva['pg_perspectiva_cor']).'">'.$perspectiva['pg_perspectiva_nome'].'</td>';
	if ($editarPG)	echo '<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_perspectiva('.$perspectiva['pg_perspectiva_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
	echo '</tr>';
	}
if (count($perspectivas)) echo '</table>';





echo '</div></td></tr>';



echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'diretrizes\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'temas\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';



?>

<script type="text/javascript">
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}		

function setPerspectiva(chave, valor){
	if (chave > 0) {
		xajax_inserir_perspectiva(chave, document.getElementById('pg_id').value);
		__buildTooltip();	
		}
	}	
	


function mudar_ordem_perspectiva(ordem, pg_perspectiva_id, direcao){
	xajax_mudar_ordem_perspectiva(ordem, pg_perspectiva_id, direcao, document.getElementById('pg_id').value);
	__buildTooltip();
	}

function excluir_perspectiva(pg_perspectiva_id){
	xajax_excluir_perspectiva(pg_perspectiva_id, document.getElementById('pg_id').value);
	__buildTooltip();
	}	
	

function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_perspectiva_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_perspectiva_cor.value;
	}

</script>