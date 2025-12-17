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

$tarefa_id = intval(getParam($_REQUEST, 'tarefa_id', 0));
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));
$baseline_id = intval(getParam($_REQUEST, 'baseline_id', 0));

echo '<form name="mudar" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="baseline_id" id="baseline_id" value="'.$baseline_id.'" />';

$botoesTitulo = new CBlocoTitulo('Imprimir Documento d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'impressao.png');
$botoesTitulo->mostrar();
$sql = new BDConsulta;
	
echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=2 width="100%" class="std">';

echo '<tr><td colspan=20><table><tr><td>'.dica('Tela', 'Exibir o relatório na tela do navegador.').'<input type="radio" id="tela" name="exibicao" value="tela" checked />Tela'.dicaF().dica('PDF','Enviar relatório para arquivo PDF.').'<input type="radio" id="pdf" name="exibicao" value="pdf" />PDF'.dicaF().'</td></tr></table></td></tr>';	

echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=tarefas&a=imprimir_tarefa&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id.'\');">Visão geral</a></td></tr>';


$vetor=array($projeto_id => $projeto_id);
if ($Aplic->profissional) portfolio_projetos($projeto_id, $vetor);
$lista_projeto=implode(',',$vetor);


if ($tarefa_id){
	include_once BASE_DIR.'/modulos/tarefas/tarefas.class.php';
	$obj = new CTarefa(($baseline_id ? true : false), true);
	$obj->load($tarefa_id);
	$listas_tarefas=($obj->tarefas_subordinadas ? $obj->tarefas_subordinadas : $tarefa_id);
	}

if ($Aplic->profissional) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'&jquery=1&m=projetos&a=financeiro_pizza_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id.'\');">Estágios da despesa (empenho, liquidação e pagamento)</a></td></tr>';
if ($Aplic->profissional) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_geral_tarefas_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id.'\');">Relatório geral de '.$config['tarefas'].'</a></td></tr>';
if ($Aplic->profissional) {
	$sql->adTabela('ata');
	$sql->esqUnir('ata_gestao','ata_gestao','ata_gestao.ata_gestao_ata=ata.ata_id');
	$sql->adCampo('ata_id, ata_numero');
	if ($tarefa_id) $sql->adOnde('ata_gestao_tarefa IN ('.$listas_tarefas.')');
	else $sql->adOnde('ata_gestao_projeto IN ('.$lista_projeto.')');
	$vetor_ata = array(0 => '')+$sql->listaVetorChave('ata_id','ata_numero');
	$sql->limpar();
	if (count($vetor_ata)>1) echo '<tr><td colspan=20>Ata de Reunião:'.selecionaVetor($vetor_ata,'ata_id','class="texto" onchange="imprimir_ata_reuniao();"').'</td></tr>';
	}

$dias=array(''=>'');
for ($i = 1; $i <= 60; $i++)$dias[$i]=$i;
if ($Aplic->profissional) echo '<tr><td colspan=20>Resumo d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' com eventos no período:'.selecionaVetor($dias,'dias','class="texto" onchange="imprimir_resumo_evento();"').' dias</td></tr>';


$sql->adTabela('projeto_area');
$sql->adCampo('projeto_area_id, projeto_area_nome, projeto_area_obs');
if ($tarefa_id) $sql->adOnde('projeto_area_tarefa IN ('.$listas_tarefas.')');
else $sql->adOnde('projeto_area_projeto IN ('.$lista_projeto.')');

$sql->adOrdem('projeto_area_tarefa ASC');
$lista_areas = array(0=>'', -2=>'Áreas d'.$config['genero_tarefa'].'s '.$config['tarefas'])+$sql->listaVetorChave('projeto_area_id','projeto_area_nome');
$sql->limpar();
$tipo_area=array('cor'=>'Cor das  áreas', 'fisico_tarefa'=>'Físico executado d'.$config['genero_tarefa'].'s '.$config['tarefas'], 'fisico_projeto'=>'Físico executado d'.$config['genero_projeto'].' '.$config['projeto'], 'status_tarefa'=>'Status d'.$config['genero_tarefa'].'s '.$config['tarefas'], 'status_projeto'=>'Status d'.$config['genero_projeto'].' '.$config['projeto']);
if (count($lista_areas)>2) echo '<tr><td colspan=20>Áreas d'.$config['genero_projeto'].' '.$config['projeto'].':'.selecionaVetor($lista_areas,'lista_areas','class="texto" onchange="imprimir_area();"').selecionaVetor($tipo_area,'tipo_area','class="texto"').'</td></tr>';		


echo '</form>';

echo '</table>';
echo estiloFundoCaixa();
?>
<script type="text/JavaScript">

function imprimir_area(){
	var elmId = document.getElementById('lista_areas');
	var tipo = document.getElementById('tipo_area').value;
	if(!elmId.selectedIndex) return;
	var url = 'm=projetos&a=imprimir_area_pro&tipo='+tipo+'&projeto_area_id='+ elmId.value+'&baseline_id='+document.getElementById('baseline_id').value+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value;
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}


function ir_para2(url){
	var pdf=document.getElementById('pdf').checked;
	if (pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	}


function ir_para(m, a, u){
	url_passar(0, 'm='+m+'&a='+a+'&u='+u+'<?php echo "&baseline_id=".$baseline_id."&projeto_id=".$projeto_id."&tarefa_id=".$tarefa_id ?>');
	}
	
function imprimir_ata_reuniao(){
	var elmId = document.getElementById('ata_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=ata_imprimir&ata_id='+ elmId.value+'<?php echo "&baseline_id=".$baseline_id."&projeto_id=".$projeto_id."&tarefa_id=".$tarefa_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}	
	     

	     
function imprimir_resumo_evento(){
	var elmId = document.getElementById('dias');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=resumo_evento_imprimir_pro&dias='+ elmId.value+'<?php echo "&baseline_id=".$baseline_id."&projeto_id=".$projeto_id."&tarefa_id=".$tarefa_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
</script>