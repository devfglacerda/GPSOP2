<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic;
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
require_once (BASE_DIR.'/modulos/projetos/projeto.class.php');
$obj = new CDemanda(true);
$obj->load($projeto_id);


$impressao=getParam($_REQUEST, 'impressao', 0);
$tipo=getParam($_REQUEST, 'tipo', '');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');

$unidade=getSisValor('TipoUnidade');
echo '<table width="100%"><tr><td width="10%">&nbsp;</td><td width="80% align="center"><center><h1>Custos Estimados</h1></center></td><td align="right" width="10%">'.(!$impressao ? dica('Imprimir a Planilha', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a planilha.').'<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=projetos&a=projeto_planilha&impressao=1&dialogo=1&projeto_id='.$projeto_id.'&projetos_subordinadas='.$obj->projetos_subordinadas.'&tipo='.$tipo.'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('imprimir_p.png').'</a>'.dicaF() : '').'</td></tr></table>';
if (!$impressao) echo estiloTopoCaixa();
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
echo '<tr><td valign="top" align="center">';
$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('projeto_custo', 'projeto_custo');
$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id=projeto_custo_projeto');
$sql->adCampo('projeto_nome');
$sql->adCampo('projeto_custo.*,((projeto_custo_quantidade*projeto_custo_custo)*((100+projeto_custo_bdi)/100)) AS valor');
$sql->adOnde('projeto_custo.projeto_custo_projeto IN ('.$obj->projetos_subordinadas.')');
if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('projeto_custo_aprovado = 1');
$sql->adOrdem('projeto_custo_ordem');	
$linhas=$sql->Lista();

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

$qnt=0;
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>
	<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de referência para o item.').'Un.'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade projetoda do ítem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
	(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
	'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>
	<th></th></tr>';
$total=array();
$custo=array();
$projeto_atual=0;
foreach ($linhas as $linha) {
	if ($linha['projeto_custo_projeto']!=$projeto_atual) {
			echo '<tr><td colspan=20>'.$linha['projeto_nome'].'</td></tr>';
			$projeto_atual=$linha['projeto_custo_projeto'];	
			}
	$nd=($linha['projeto_custo_categoria_economica'] && $linha['projeto_custo_grupo_despesa'] && $linha['projeto_custo_modalidade_aplicacao'] ? $linha['projeto_custo_categoria_economica'].'.'.$linha['projeto_custo_grupo_despesa'].'.'.$linha['projeto_custo_modalidade_aplicacao'].'.' : '').$linha['projeto_custo_nd'];
	echo '<tr align="center">';
	echo '<td align="left">'.++$qnt.' - '.$linha['projeto_custo_nome'].'</td>';
	echo '<td align="left">'.($linha['projeto_custo_descricao'] ? $linha['projeto_custo_descricao'] : '&nbsp;').'</td>';
	echo '<td style="white-space: nowrap">'.$unidade[$linha['projeto_custo_tipo']].'</td>';
	echo '<td style="white-space: nowrap">'.number_format($linha['projeto_custo_quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['projeto_custo_moeda']].' '.number_format($linha['projeto_custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right">'.number_format($linha['projeto_custo_bdi'], 2, ',', '.').'</td>';

	echo '<td style="white-space: nowrap">'.$nd.'</td>';
	echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['projeto_custo_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
	
	if (isset($exibir['codigo']) && $exibir['codigo']) echo '<td align="center">'.($linha['projeto_custo_codigo'] ? $linha['projeto_custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte']) echo '<td align="center">'.($linha['projeto_custo_fonte'] ? $linha['projeto_custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo '<td align="center">'.($linha['projeto_custo_regiao'] ? $linha['projeto_custo_regiao'] : '&nbsp;').'</td>'; 
	
	
	echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['projeto_custo_usuario'],'','','esquerda').'</td>';
	echo '<tr>';
	
	if (isset($custo[$linha['projeto_custo_moeda']][$nd])) $custo[$linha['projeto_custo_moeda']][$nd] += (float)$linha['valor'];
	else $custo[$linha['projeto_custo_moeda']][$nd]=(float)$linha['valor'];
	
	if (isset($total[$linha['projeto_custo_moeda']])) $total[$linha['projeto_custo_moeda']]+=$linha['valor'];
	else $total[$linha['projeto_custo_moeda']]=$linha['valor']; 
	
	}
$tem_total=false;
foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
	
if ($tem_total) {
	foreach ($custo as $tipo_moeda => $linha) {
		echo '<tr><td colspan="'.($config['bdi'] ? 7 : 6).'" class="std" align="right">';
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda], 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}	
	}
if (!$qnt) echo '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';
echo '</table></td></tr>';

if (!$impressao) {
	echo '<tr><td><table width="100%"><tr>'.(!$Aplic->profissional ? '<td align="left">'.botao('fechar', 'Fechar','Fechar esta tela.','','window.opener = window; window.close();').'</td>' : '');	
	$link='';


	
	echo '</tr></table></td></tr>';
	}
echo '</td></tr></table></form>';
if (!$impressao) echo estiloFundoCaixa();
elseif($impressao && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script>self.print();</script>';


?>
