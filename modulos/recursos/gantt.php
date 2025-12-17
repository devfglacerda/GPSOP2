<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $localidade_tipo_caract, $projetoStatus, $mostrarInativo, $mostrarTodoGantt, $ordenarTarefasPorNome, $usuario_id, $config, $recurso_id;
ini_set('memory_limit', $config['resetar_limite_memoria']);
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_gantt'));
include_once ($Aplic->getClasseModulo('projetos'));

$tab=getParam($_REQUEST, 'tab', 0);

$largura = min(getParam($_REQUEST, 'width', 600), 1400);
$data_inicio=getParam($_REQUEST, 'data_inicio', null);
$data_fim=getParam($_REQUEST, 'data_fim', null);

$projetoStatus = getSisValor('StatusProjeto');
$projetoStatus = unirVetores(array('-1' => 'Todos '.$config['genero_projeto'].'s '.ucfirst($config['projetos']), '-2' => 'Todos sem progresso'), $projetoStatus);
$recurso_id=getParam($_REQUEST, 'recurso_id', 0);
$usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
if ($Aplic->usuario_id == $usuario_id) $projetoStatus = unirVetores(array('-3' => 'Meus '.$config['projetos']), $projetoStatus);
else $projetoStatus = unirVetores(array('-3' => ucfirst($config['projetos']).' d'.$config['genero_usuario'].'s '.$config['usuarios']), $projetoStatus);
$proFiltro=getParam($_REQUEST, 'proFiltro', '-1');
$pjobj = new CProjeto;
$horas_trabalhadas = $config['horas_trab_diario'];
$sql = new BDConsulta;






if (!$data_inicio && $tab==6) {
	$sql->adTabela('recurso_ponto');
	$sql->adCampo('MIN(recurso_ponto_inicio)');
	$sql->adOnde('recurso_ponto_recurso = '.(int)$recurso_id);
	$data_inicio = $sql->Resultado();
	$sql->limpar();
	}
else if (!$data_inicio) {
	$sql->adTabela('recurso_tarefa');
	$sql->adCampo('MIN(recurso_tarefa_inicio)');
	$sql->adOnde('recurso_tarefa_recurso = '.(int)$recurso_id);
	if ($tab==5) $sql->adOnde('recurso_tarefa_aprovado=1');
	$data_inicio = $sql->Resultado();
	$sql->limpar();
	}	

if (!$data_fim && $tab==6) {
	$sql->adTabela('recurso_ponto');
	$sql->adCampo('MAX(recurso_ponto_fim)');
	$sql->adOnde('recurso_ponto_recurso = '.(int)$recurso_id);
	$data_fim = $sql->Resultado();
	$sql->limpar();
	}
else if (!$data_fim) {
	$sql->adTabela('recurso_tarefa');
	$sql->adCampo('MAX(recurso_tarefa_fim)');
	$sql->adOnde('recurso_tarefa_recurso = '.(int)$recurso_id);
	if ($tab==5) $sql->adOnde('recurso_tarefa_aprovado=1');
	$data_fim = $sql->Resultado();
	$sql->limpar();
	}


if ($tab==6){
	$sql->adTabela('recurso_ponto');
	$sql->esqUnir('tarefas', 'tarefas', 'recurso_ponto_tarefa = tarefas.tarefa_id');
	$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
	$sql->adCampo('projetos.projeto_id, projeto_cor, projeto_nome, projeto_ativo');
	$sql->adCampo('tarefa_marco, recurso_ponto_quantidade, recurso_ponto_inicio, recurso_ponto_fim, tarefa_nome, tarefa_percentagem');
	$sql->adOnde('recurso_ponto_recurso = '.(int)$recurso_id);
	$sql->adOrdem('projetos.projeto_nome, recurso_ponto_inicio ASC');
	
	if ($data_inicio) $sql->adOnde('recurso_ponto_fim >= \''.$data_inicio.'\'');
	if ($data_fim) $sql->adOnde('recurso_ponto_inicio <= \''.$data_fim.'\'');
	$tarefas = $sql->Lista();
	$sql->limpar();
	}
else  {
	$sql->adTabela('recurso_tarefa');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id=recurso_tarefa_tarefa');
	$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id=recurso_tarefa_recurso');
	$sql->adCampo('projetos.projeto_id, projeto_cor, projeto_nome, projeto_ativo');
	$sql->adCampo('tarefa_marco, recurso_tarefa_quantidade AS recurso_ponto_quantidade, recurso_tarefa_inicio AS recurso_ponto_inicio, recurso_tarefa_fim AS recurso_ponto_fim, tarefa_nome, tarefa_percentagem');
	$sql->adOnde('recurso_tarefa_recurso='.(int)$recurso_id);
	if ($tab==5) $sql->adOnde('recurso_tarefa_aprovado=1');
	
	if ($data_inicio) $sql->adOnde('recurso_tarefa_fim >= \''.$data_inicio.'\'');
	if ($data_fim) $sql->adOnde('recurso_tarefa_inicio <= \''.$data_fim.'\'');
	
	$tarefas = $sql->Lista();
	$sql->limpar();
	}
	
$mostrarTodoGantt=getParam($_REQUEST, 'mostrarTodoGantt', '0');
$grafico = new GanttGraph($largura);
if (!$data_inicio && !$data_fim) $grafico->ShowHeaders(GANTT_HYEAR| GANTT_HMONTH);
else $grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$grafico->SetFrame(false);
$grafico->SetBox(true, array(0, 0, 0), 2);
$grafico->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);
$pLocale = setlocale(LC_TIME, 0); 
$res = setlocale(LC_TIME, $Aplic->usuario_linguagem[2]);
if ($res) $grafico->scale->SetDateLocale($Aplic->usuario_linguagem[2]);
setlocale(LC_TIME, $pLocale);
if ($data_inicio && $data_fim) $grafico->SetDateRange($data_inicio, $data_fim);
$grafico->scale->actinfo->vgrid->SetColor('gray');
$grafico->scale->actinfo->SetColor('darkgray');
$grafico->scale->actinfo->SetColTitles(array('Nome d'.$config['genero_tarefa'].' '.$config['tarefa'], ucfirst($config['projeto']), 'Qnt.',' Início ', 'Término'), array(70, 70, 10, 70, 70));


$grafico->scale->tableTitle->Set($projetoStatus[$proFiltro]);
if (!$data_inicio && !$data_fim) $grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 8);
else $grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 12);
$grafico->scale->SetTableTitleBackground('#eeeeee');
$grafico->scale->tableTitle->Show(true);


if ($data_inicio && $data_fim) {
	$minuto_d_inicio = new CData($data_inicio);
	$minuto_d_fim = new CData($data_fim);
	$grafico->SetDateRange($data_inicio, $data_fim);
	} 
else {
	$d_inicio = new CData();
	$d_fim = new CData();
	for ($i = 0, $i_cmp = count($tarefas); $i < $i_cmp; $i++) {
		$inicio = substr($tarefas[$i]['recurso_ponto_inicio'], 0, 10);
		$fim = substr($tarefas[$i]['recurso_ponto_fim'], 0, 10);
		$d_inicio->Date($inicio);
		$d_fim->Date($fim);
		if ($i == 0) {
			$minuto_d_inicio = $d_inicio;
			$minuto_d_fim = $d_fim;
			} 
		else {
			if ($d_inicio->compare($minuto_d_inicio, $d_inicio) > 0) $minuto_d_inicio = $d_inicio;
			if ($d_inicio->compare($minuto_d_fim, $d_fim) < 0) $minuto_d_fim = $d_fim;
			}
		}
	if (!count($tarefas))	{
		$minuto_d_inicio = $d_inicio;
		$minuto_d_fim = $d_fim;
		}
	}


$dia_diferenca = $minuto_d_inicio->dataDiferenca($minuto_d_fim);
if ($dia_diferenca > 360) $grafico->ShowHeaders(GANTT_HYEAR);
elseif ($dia_diferenca > 240) $grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH);
elseif ($dia_diferenca > 90) {
		$grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HWEEK);
		$grafico->scale->week->SetStyle(WEEKSTYLE_WNBR);
		}
$linha = 0;
if (!count($tarefas)) {
	$d = new CData();
	$barra = new GanttBar($linha++, array(' Nenhum'.($config['genero_tarefa']=='a' ? 'a' : '').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'], ' ', ' ', ' '), $d->getData(), $d->getData(), ' ', 0.6);
	$barra->title->SetCOlor('red');
	$grafico->Add($barra);
	}
if (count($tarefas)) {
	foreach ($tarefas as $t) {
		if ($localidade_tipo_caract == 'utf-8' && function_exists('utf8_decode')) $nome = strlen(utf8_decode($t['tarefa_nome'])) > 25 ? substr(utf8_decode($t['tarefa_nome']), 0, 22).'...' : utf8_decode($t['tarefa_nome']);
		else $nome = strlen($t['tarefa_nome']) > 30 ? substr($t['tarefa_nome'], 0, 28).'...' : $t['tarefa_nome'];
		if ($localidade_tipo_caract == 'utf-8' && function_exists('utf8_decode')) $nomeProjeto = strlen(utf8_decode($t['projeto_nome'])) > 25 ? substr(utf8_decode($t['projeto_nome']), 0, 22).'...' : utf8_decode($t['projeto_nome']);
		else $nomeProjeto = strlen($t['projeto_nome']) > 20 ? substr($t['projeto_nome'], 0, 18).'...' : $t['projeto_nome'];
		
		$inicio = ($t['recurso_ponto_inicio'] ? $t['recurso_ponto_inicio'] : null);
		$data_fim = ($t['recurso_ponto_fim'] ? $t['recurso_ponto_fim'] : null);
		$data_fim = new CData($data_fim);
		$fim = $data_fim->getData();
		$inicio = new CData($inicio);
		$inicio = $inicio->getData();
		//$progresso = $t['tarefa_percentagem'] + 0;
		$legenda = '';
		if (!$inicio || $inicio == '0000-00-00') {
			$inicio = !$fim ? date('Y-m-d') : $fim;
			$legenda .= '(sem data início)';
			}
		if (!$fim) {
			$fim = $inicio;
			$legenda .= ' '.'(sem data de término)';
			} 
		else $cap = '';
		
		$datafim = new CData($fim);
		$datainicio = new CData($inicio);

		if ($t['tarefa_marco'] && $mostrarMarco) {
			$datamarco = new CData($inicio);
			$datamarco->setHour(10);
			$barra = new MileStone($linha++, array($nome, $nomeProjeto, $t['recurso_ponto_quantidade'], $datainicio->format('%d/%m/%Y'), ''), substr($inicio, 0, 10));
			$barra->title->SetFont(FF_FONT1, FS_NORMAL, 8);
			$barra->title->SetColor('#CC0000');
			$grafico->Add($barra);
			} 
		if (!$t['tarefa_marco']){
			$barra = new GanttBar($linha++, array($nome, $nomeProjeto, $t['recurso_ponto_quantidade'], $datainicio->format('%d/%m/%Y'), $datafim->format('%d/%m/%Y')), $inicio, $fim, $cap, 0.6);
			$barra->title->SetFont(FF_FONT1, FS_NORMAL, 8);
			$barra->SetFillColor('#'.$t['projeto_cor']);
			$barra->SetPattern(BAND_SOLID, '#'.$t['projeto_cor']);
			$barra->caption = new TextProperty($legenda);
			$barra->caption->Align('left', 'center');
			if ($t['projeto_ativo'] == '0') {
				$barra->caption->SetColor('darkgray');
				$barra->title->SetColor('darkgray');
				$barra->SetColor('darkgray');
				$barra->SetFillColor('gray');
				$barra->progress->SetFillColor('darkgray');
				$barra->progress->SetPattern(BAND_SOLID, 'darkgray', 98);
				}
			$grafico->Add($barra);
			}
		}
	} 
unset($tarefas);
$hoje = date('y-m-d');
$linhaVert = new GanttVLine($hoje, 'Hoje');
$grafico->Add($linhaVert);
$grafico->Stroke();
?>