<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMAo GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $mostrarCaixachecarEditar, $tarefas, $prioridades, $projeto_id, $dialogo, $Aplic, $cia_id, $obj, $config, $tab;
global $m, $a, $data, $mostrar_marcada, $mostra_projeto_completo, $mostraProjetosEspera, $mostrar_tarefa_dinamica, $mostrar_tarefa_baixa, $mostrar_sem_data, $usuario_id, $dept_id, $tarefa_tipo, $cia_id;
global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;
global $Aplic, $cal_sdf, $projeto_id, $designados, $ver_todos_projetos;

$Aplic->carregarCKEditorJS();
$mostrarNomeProjeto=nome_projeto($projeto_id);
$qnt=0;
$Aplic->carregarCalendarioJS();
$usuario_id = getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$grupo=getParam($_REQUEST, 'grupo', 'designado');
$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', 0);
$usar_periodo = getParam($_REQUEST, 'usar_periodo', 0);
$log_pdf = 1;
$dias = getParam($_REQUEST, 'dias', 30);
$data_inicio= getParam($_REQUEST, 'reg_data_inicio', '');
$data_fim= getParam($_REQUEST, 'reg_data_fim', '');
$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', 0);
$periodo_valor = getParam($_REQUEST, 'pvalor', 1);





echo '<form name="frm_botoes" method="post">';
echo '<input type="hidden" name="m" value="depts" />';
echo '<input type="hidden" name="a" value="ver" />';
echo '<input type="hidden" name="tab" value="2" />';
echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$cia_id.'" />';




$botoesTitulo = new CBlocoTitulo('', 'logo_dpge.png', $m, "$m.$a");
$botoesTitulo->mostrar();





$ordenar = getParam($_REQUEST, 'ordenar', 'tarefa_projeto');
$ordem = getParam($_REQUEST, 'ordem', '0');
if ($ordenar=='tarefa_projeto') $ordenar='tarefa_projeto'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='tarefa_nome') $ordenar='tarefa_nome'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='usuario_id') $ordenar='usuario_id'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='tarefa_percentagem') $ordenar='tarefa_percentagem'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='tarefa_prioridade') $ordenar='tarefa_prioridade'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='tarefa_inicio') $ordenar='tarefa_inicio'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='tarefa_fim') $ordenar='tarefa_fim'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='dept_nome') $ordenar='dept_nome'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='projeto_setor') $ordenar='projeto_setor'.($ordem ? ' DESC' : ' ASC' ); 


$sql = new BDConsulta; 
if (isset($_REQUEST['cia_id'])) $cia_id=getParam($_REQUEST, 'cia_id', 0);
if ($projeto_id) $sql->adOnde('t.tarefa_projeto = '.(int)$projeto_id);
$sql = new BDConsulta;

if (count($tarefas)<1){
$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');

$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');		
		
		
		
				
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
$sql->adOnde('projeto_setor = 06');

$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
$sql->adOrdem($ordenar);
	
$lista = $sql->Lista();

$sql->limpar();	}



if (!($linha = $sql->Lista())) {
		
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=tarefa_projeto&ordem='.($ordem ? '0' : '1').'\');">'. dica('Projeto', 'Clique para ordenar pelos projetos.') .'Projeto'.dicaF().'</a></th>';	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=tarefa_nome&ordem='.($ordem ? '0' : '1').'\');">'. dica('Tarefa', 'Clique para ordenar os contatos pelas tarefas.') .'Tarefas'.dicaF().'</a></th>';
	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=dept_nome&ordem='.($ordem ? '0' : '1').'\');">'. dica('Seção', 'Clique para ordenar pelo setor.') .'Setor'.dicaF().'</a></th>';
	
	
	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=usuario_id&ordem='.($ordem ? '0' : '1').'\');">'. dica('Designado', 'Clique para ordenar pelo Designado.') .'Designado'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=tarefa_percentagem&ordem='.($ordem ? '0' : '1').'\');">'. dica('Porcentagem', 'Clique para ordenar pela porcentagem.') .'Porcentagem'.dicaF().'</a></th>';
	
	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=tarefa_tipo&ordem='.($ordem ? '0' : '1').'\');">'. dica('Tipo', 'Clique para ordenar pelo tipo.') .'Tipo'.dicaF().'</a></th>';
	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=tarefa_status&ordem='.($ordem ? '0' : '1').'\');">'. dica('Status', 'Clique para ordenar pelo Status.') .'Status'.dicaF().'</a></th>';
	
	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=tarefa_prioridade&ordem='.($ordem ? '0' : '1').'\');">'. dica('Prioridade', 'Clique para ordenar pela prioridade.') .'Prioridade'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=tarefa_inicio&ordem='.($ordem ? '0' : '1').'\');">'. dica('Data Inicial', 'Clique para ordenar pela data inicia.') .'Data Inicial'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver_dpge&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=tarefa_fim&ordem='.($ordem ? '0' : '1').'\');">'. dica('Data Final', 'Clique para ordenar pela data final.') .'Data Final'.dicaF().'</a></th>';
	

	echo '</tr>';
	
	$tipo2 = getSisValor('TipoTarefa');
	$status = getSisValor('StatusTarefa');
	$tipo = getSisValor('Setor');	
		
	foreach($lista as $linha)  {
		echo '<tr>';
		echo '<td>'.link_projeto($linha['tarefa_projeto']).'</td>';
		echo '<td>'.$linha['tarefa_nome'].'</td>';
		echo '<td align="center">'.$linha['dept_nome'].'</td>';
		echo '<td align="center">'.link_usuario($linha['usuario_id']).'</td>';
		echo '<td align="center">'.number_format($linha['tarefa_percentagem']).'%'.'</td>';
		
		echo '<td align="center">'.($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;').'</td>';
		echo '<td align="center">'.($linha['tarefa_status'] && isset($status[$linha['tarefa_status']]) ? $status[$linha['tarefa_status']] : '&nbsp;').'</td>';
		
		echo '<td align="center">'.prioridade($linha['tarefa_prioridade']).'</td>';
		echo '<td align="right">'.retorna_data($linha['tarefa_inicio']).'</td>';
		echo '<td align="right">'.retorna_data($linha['tarefa_fim']).'</td>
		
		
	
		
		
		
		</tr>';
							
		
		
		}
	}


if (!$dialogo) {
	echo '</td></tr></table>';	
	
	}
	
	echo '<table>
  <tbody>
    <tr>
      <td><span>Legenda - Prioridade:</span></td>
      <td><span><img style="vertical-align: middle;"
 src="/estilo/rondon/imagens/icones/prioridade-2.gif"
 alt="" border="0"> Muito baixa</span><span></span></td>
      <td><span><img style="vertical-align: middle;"
 src="/estilo/rondon/imagens/icones/prioridade-1.gif"
 alt="" border="0"> Baixa</span><span></span></td>
      <td><span><img style="vertical-align: middle;"
 src="/estilo/rondon/imagens/icones/prioridade0.gif"
 alt="" border="0"> Normal</span><span></span></td>
      <td><span><img style="vertical-align: middle;"
 src="/estilo/rondon/imagens/icones/prioridade+1.gif"
 alt="" border="0"> Alta</span><span></span></td>
      <td><span><img style="vertical-align: middle;"
 src="/estilo/rondon/imagens/icones/prioridade+2.gif"
 alt="" border="0"> Muito alta</span><span></span></td>
    </tr>
  </tbody>
</table>

';


?>


