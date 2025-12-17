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

global $Aplic, $projeto_id, $m, $dialogo;


if (!$projeto_id) $projeto_id=getParam($_REQUEST, 'projeto_id', 0);

if (!$Aplic->checarModulo('log', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$vetor_tarefa=array();

if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
	$vetor_projeto=ser_portfolio($projeto_id);
	if (!$vetor_projeto) $vetor_projeto=$projeto_id;
	}
else $vetor_projeto=$projeto_id;
$tem_subordinada=true;





$problema = intval(getParam($_REQUEST, 'problem', null));
$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$RefRegistroTarefaImagem = getSisValor('RefRegistroTarefaImagem');
$ordenar=getParam($_REQUEST, 'ordenar', 'log_data');
$ordem=getParam($_REQUEST, 'ordem', '0');
$podeExcluir = $Aplic->checarModulo('log', 'excluir');
echo '<form name="frmExcluir2" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_log_tarefa" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="log_id" value="0" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '</form>';

$sql = new BDConsulta();

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();


$sql->adTabela('projetos');
$sql->adCampo('projeto_moeda');
$sql->adOnde('projeto_id ='.(int)$projeto_id);	
$projeto_moeda=$sql->Resultado();
$sql->limpar();

$divisor_cotacao=($projeto_moeda!=1 ? cotacao($projeto_moeda, date('Y-m-d')) : 1);

$sql->adTabela('log');
$sql->esqUnir('log', 'log_corrigiu', 'log_corrigiu.log_id=log.log_correcao');
$sql->esqUnir('log', 'log_corrigido', 'log_corrigido.log_correcao=log.log_id');
$sql->esqUnir('tarefas','t', 'log.log_tarefa=t.tarefa_id');
$sql->esqUnir('usuarios', '', 'log.log_criador = usuario_id');
$sql->esqUnir('contatos', 'ct', 'contato_id = usuario_contato');
$sql->adCampo('log.*, usuario_login, contato_id, tarefa_projeto');
$sql->adCampo('concatenar_tres(formatar_data(log_corrigiu.log_data, "%d/%m/%Y"), \' - \', log_corrigiu.log_nome) AS corrigido, concatenar_tres(formatar_data(log_corrigido.log_data, "%d/%m/%Y"), \' - \', log_corrigido.log_nome) AS corrigiu');
$sql->adOnde('tarefa_projeto IN ('.$vetor_projeto.')'.($problema ? ' AND log.log_corrigir > 0' : ''));
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$logs = $sql->Lista();
$sql->limpar();



echo '<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr><td>';
echo '<table cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

if (!$dialogo) echo '<th width=16></th>';
echo '<th width=50><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data', 'Data de inserção do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='log_data' ? imagem('icones/'.$seta[$ordem]) : '').'Data'.dicaF().'</a></th>';

if ($tem_subordinada) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=tarefa_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica(ucfirst($config['tarefa']), ucfirst($config['tarefa']).' relacionad'.$config['genero_tarefa'].' ao registro.').($ordenar=='tarefa_nome' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['tarefa']).dicaF().'</a></th>';


//echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_referencia&ordem='.($ordem ? '0' : '1').'\');">'.dica('Referência', 'A forma como se chegou aos dados que estão registrandos.').($ordenar=='log_referencia' ? imagem('icones/'.$seta[$ordem]) : '').'Ref.'.dicaF().'</a></th>';
//echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome', 'Nome do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='log_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Título'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_reg_mudanca_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.dica('Percentagem', 'Caso tenha sido modificada a percentagem, será registrado nesta coluna para qual valor ficou.').($ordenar=='log_reg_mudanca_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').'%'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_reg_mudanca_status&ordem='.($ordem ? '0' : '1').'\');">'.dica('Status', 'Caso tenha sido modificada o status, será registrado nesta coluna para qual situação ficou.').($ordenar=='log_reg_mudanca_status' ? imagem('icones/'.$seta[$ordem]) : '').'Status'.dicaF().'</a></th>';
//echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_reg_mudanca_realizado&ordem='.($ordem ? '0' : '1').'\');">'.dica('Quantidade', 'Caso tenha sido modificada a quantidade executada, será registrado nesta coluna para qual valor ficou.').($ordenar=='log_reg_mudanca_realizado' ? imagem('icones/'.$seta[$ordem]) : '').'Qnt'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_url_relacionada&ordem='.($ordem ? '0' : '1').'\');">'.dica('Endereço Eletrônico da Referência', 'Caso exista um link para página ou arquivo na rede que faça referência ao registro.').($ordenar=='log_url_relacionada' ? imagem('icones/'.$seta[$ordem]) : '').'URL'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_criador&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Responsável pela inserção do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Responsável'.dicaF().($ordenar=='log_criador' ? imagem('icones/'.$seta[$ordem]) : '').'</a></th>';
//echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_horas&ordem='.($ordem ? '0' : '1').'\');">'.dica('Horas', 'Horas trabalhadas n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='log_horas' ? imagem('icones/'.$seta[$ordem]) : '').'Horas'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&projeto_id='.$projeto_id.'&ordenar=log_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Comentários', 'Comentários sobre o registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='log_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Comentários'.dicaF().'</a></th>';
//echo '<th>'.dica('Custos', 'Custos planejados no registro.').'Custos'.dicaF().'</th>';
//echo '<th width="100">'.dica('Gastos', 'Gastos efetuados no registro.').'Gastos'.dicaF().'</th>';
if ($podeExcluir && !$dialogo) echo '<th>&nbsp;</th>';
echo '</tr>';

$hrs = 0;
$custo=array();
$podeEditar = $Aplic->checarModulo('log', 'editar');
$qnt=0;


$status = getSisValor('StatusTarefa');

foreach ($logs as $linha) {

		
	$permiteEditar=permiteEditar($linha['log_acesso'], $linha['tarefa_projeto'], $linha['log_tarefa']);
	
	if ($linha['log_aprovado']==1 || $linha['log_aprovado']==-1) $permiteEditar=0;
	
	$qnt++;
	
	
	if ($linha['log_correcao']) $estilo='background-color:#a1fb99;color:#000000';
	else if ($linha['corrigiu'])	$estilo='background-color:#e9ea87;color:#000000';
	else if ($linha['log_corrigir']) $estilo='background-color:#cc6666;color:#ffffff';
	else $estilo='';
		
	
	echo '<tr bgcolor="white" valign="middle">';
	if ($podeEditar && $permiteEditar && !$dialogo) {
		echo '<td>';
		if (isset($tab) && $tab == -1) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver_log_atualizar&tab='.$Aplic->getEstado('TarefaLogVerTab');
		else 	echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver_log_atualizar&tab=1&log_id='.$linha['log_id'].'\');">'.imagem('icones/editar.gif','Editar Registro','Clique neste ícone '.imagem('icones/editar.gif').' para editar o registro.').'</a>';
		echo '</td>';	
		}
	else if(!$dialogo) echo '<td>&nbsp;</td>';	
	
	echo '<td style="white-space: nowrap" valign="middle">'.retorna_data($linha['log_data'], false).'</td>';
	
	if ($tem_subordinada) echo '<td>'.link_tarefa($linha['log_tarefa']).'</td>';
	
	
	$imagem_referencia = '-';
	/*if ($linha['log_referencia'] > 0) {
		if (isset($RefRegistroTarefaImagem[$linha['log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroTarefaImagem[$linha['log_referencia']], imagem('icones/'.$RefRegistroTarefaImagem[$linha['log_referencia']]).' '.$RefRegistroTarefa[$linha['log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
		elseif (isset($RefRegistroTarefa[$linha['log_referencia']])) $imagem_referencia = $RefRegistroTarefa[$linha['log_referencia']];
		}
	echo '<td align="center" valign="middle">'.$imagem_referencia.'</td>';*/
	
	if ($Aplic->profissional){
		$sql->adTabela('log_arquivo');
		$sql->adCampo('count(log_arquivo_id)');
		$sql->adOnde('log_arquivo_log='.(int)$linha['log_id']);
		$arquivos=$sql->Resultado();
		$sql->limpar();
		$plural=($arquivos > 1 ? 's' : '');
		$anexo=($arquivos ? '<a href="javascript:popArquivos('.$linha['log_id'].');" >'.imagem('icones/clip.png','Anexo'.$plural,'Clique neste ícone '.imagem('icones/clip.png').' para visualizar o'.$plural.($plural=='s' ? ' '.$arquivos : '').' anexo'.$plural.'.').'</a>' : '');
		}
	else $anexo='';
	
//	echo '<td valign="middle" style="'.$estilo.'">'.($linha['log_correcao'] ? dica('Registro em que Solucionou Problema', $linha['corrigido']) : '').($linha['corrigiu'] ? dica('Registro Responsável pela Solução', $linha['corrigiu']) : '').$linha['log_nome'].($linha['corrigiu'] || $linha['corrigido'] ? dicaF() : '').$anexo.'</td>';
	echo '<td valign="middle" align="center">'.($linha['log_reg_mudanca_percentagem'] ? (int)$linha['log_reg_mudanca_percentagem'] : '&nbsp;').'</td>';
	echo '<td valign="middle" align="center">'.($linha['log_reg_mudanca_status'] && isset($status[$linha['log_reg_mudanca_status']]) ? $status[$linha['log_reg_mudanca_status']] : '&nbsp;').'</td>';
	//echo '<td valign="middle" align="center">'.($linha['log_reg_mudanca_realizado'] ? number_format($linha['log_reg_mudanca_realizado'], 1, ',', '.') : '&nbsp;').'</td>';
	echo !empty($linha['log_url_relacionada']) ? '<td align="center" valign="middle">'.dica('Link', 'Clique neste ícone '.imagem('icones/link.png').' para  acessar:<ul><li>'.$linha['log_url_relacionada'].'</ul>').'<a href="'.$linha['log_url_relacionada'].'">'.imagem('icones/link.png').'</a>'.dicaF().'</td>' : '<td>&nbsp;</td>';
	echo '<td valign="middle">'.link_usuario($linha['log_criador'],'','','esquerda').'</td>';
	/*echo '<td align="right" valign="middle">';
	$minutos = (int)(($linha['log_horas'] - ((int)$linha['log_horas'])) * 60);
	$minutos = ((strlen($minutos) == 1) ? ('0'.$minutos) : $minutos);
	echo($linha['log_horas']!=0 ? (int)$linha['log_horas'].':'.$minutos : '&nbsp;').'</td>';*/
	echo'<td valign="middle">'.'<a name="tarefalog'.$linha['log_id'].'"></a>'.str_replace("\n", '<br />', ($linha['log_descricao'])).'</td>';
	
	
	
	$sql->adTabela('custo');
	$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS valor');
	$sql->adOnde('custo_log ='.(int)$linha['log_id']);	
	$sql->adOnde('custo_gasto!=1');
	$custo=$sql->Resultado();
	$sql->limpar();

	$sql->adTabela('custo');
	$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS valor');
	$sql->adOnde('custo_log ='.(int)$linha['log_id']);	
	$sql->adOnde('custo_gasto=1');
	$gasto=$sql->Resultado();
	$sql->limpar();
	
	
	//echo '<td width="100" align="right" valign="middle" style="white-space: nowrap">'.($custo ? $moedas[$projeto_moeda].' '.number_format($custo/$divisor_cotacao, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:planilha_custo('.$linha['log_id'].', 0)">'.dica('Planilha de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar a planilha de custos estimados.').imagem('icones/planilha_estimado.gif').dicaF().'</a>' : '&nbsp;').'</td>';
	//echo '<td width="100" align="right" valign="middle" style="white-space: nowrap">'.($gasto ? $moedas[$projeto_moeda].' '.number_format($gasto/$divisor_cotacao, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:planilha_custo('.$linha['log_id'].', 1)">'.dica('Planilha de Gastos Efetuados', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos efetuados.').imagem('icones/planilha_gasto.gif').dicaF().'</a>' : '&nbsp;').'</td>';

		
	
	if ($podeExcluir && $permiteEditar && !$dialogo) echo '<td width="16" valign="middle"><a href="javascript:excluir2('.$linha['log_id'].');" >'.imagem('icones/remover.png','Excluir Registro','Clique neste ícone '.imagem('icones/remover.png').' para excluir o registro.').'</a></td>';
	else if ($podeExcluir && !$dialogo) echo'<td>&nbsp;</td>';
	
	echo '</tr>';
	
	}
if (!$qnt) {
	echo '<tr><td bgcolor="white" colspan=20><p>Nenhum registro encontrado.</p></td></tr></table></td></tr></table>';	
	}
if (!$dialogo && $qnt){	
	echo '<table width="100%" class="std2"><tr><td><table><tr><td>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Legenda:</td>';
	echo '<td>&nbsp; &nbsp;</td><td bgcolor="#ffffff" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro Normal', 'Todos os registros que não forem marcados como tendo problema serão considerados normais.').'Normal'.dicaF().'&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td bgcolor="#cc6666" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro de Problema', 'Todos os registros que forem marcados como tendo problema aparecerão com o sumário na cor vermelha.').'Problema&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';
	echo '<td bgcolor="#e9ea87" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Problema Solucionado', 'Todos os registros marcados como problema em que outro registro tenha sido marcado como tendo solicionado estes problemas.').'Problema Solucionado&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';
	echo '<td bgcolor="#a1fb99" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Solucionou Problema', 'Todos os registros que forem marcados como tendo solucionado problema de outro registro.').'Solucionou Problema&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';
	echo '<td align="right"><a href="javascript: void(0);" onclick ="imprimir_registros('.$projeto_id.');">'.imagem('imprimir_p.png', 'Imprimir os Registros d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de registros d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</a></td>';
	echo '</tr></table></td></tr></table></td></tr>';
	}

echo '</table>';


function tarefas_subordinada($tarefa_pai=0, &$vetor){
	global $arvore, $Aplic;
	$vetor[$tarefa_pai]=(int)$tarefa_pai;
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_superior ='.(int)$tarefa_pai.' AND tarefa_id!='.(int)$tarefa_pai);
	$lista=$sql->carregarColuna();
	$sql->limpar();
	foreach($lista as $tarefa_id){
		$vetor[$tarefa_id]=$tarefa_id;
		tarefas_subordinada($tarefa_id, $vetor);
		}
	}

if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';


?>
<script type="text/javascript">

function planilha_custo(log_id, gasto){
	parent.gpwebApp.popUp('Planilha de '+(gasto ? 'Gasto' : 'Custo'), 1000, 500, 'm=praticas&a=log_custo&log_id='+log_id+'&gasto='+gasto, null, window);
	}

function popArquivos(log_id){
	parent.gpwebApp.popUp('Arquivos', 400, 400, 'm=tarefas&a=ver_log_anexos&dialogo=1&log_id='+log_id, null, window);
	}

function excluir2(id) {
	if (confirm( 'Tem certeza que deseja excluir o registro d<?php echo $config["genero_tarefa"]." ".$config["tarefa"]?>?' )) {
		document.frmExcluir2.log_id.value = id;
		document.frmExcluir2.submit();
		}
	}	
	
function imprimir_registros(projeto_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Registros', 1024, 500, 'm=projetos&a=ver_logs&dialogo=1&projeto_id='+projeto_id, null, window);
	else window.open('./index.php?m=projetos&a=ver_logs&dialogo=1&projeto_id='+projeto_id, 'Registros','height=500,width=1020,resizable,scrollbars=yes');
	}	
	
	
</script>