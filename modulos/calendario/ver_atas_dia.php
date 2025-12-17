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

global $perms, $periodo_todo, $este_dia, $primeiraData, $ultimaData, $cia_id, $Aplic, $titulo, $usuario_id, $cia_id, $dept_id,
	$projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id;


$db_inicio = $primeiraData->format('%Y-%m-%d %H:%M:%S');
$db_fim = $ultimaData->format('%Y-%m-%d %H:%M:%S');



$links = array();

$sql = new BDConsulta;
$sql->adTabela('ata_acao','ata_acao');
$sql->esqUnir('ata', 'ata', 'ata.ata_id = ata_acao_ata');
if ($usuario_id) $sql->esqUnir('ata_acao_usuario', 'ata_acao_usuario', 'ata_acao_usuario_acao=ata_acao.ata_acao_id');
if ($dept_id)	$sql->esqUnir('ata_dept', 'ata_dept', 'ata_dept_ata = ata.ata_id');
	
$sql->adCampo('DISTINCT ata_acao.ata_acao_id, ata_acao_texto, ata_titulo, ata_acesso, ata_acao_inicio, ata_acao_fim, ata_cor AS cor, ata_titulo, ata.ata_id');
$sql->adOnde('ata_acao_inicio <= \''.$db_fim.'\'');
$sql->adOnde('ata_acao_fim >= \''.$db_inicio. '\'');
$sql->adOnde('ata_acao_inicio IS NOT NULL');
$sql->adOnde('ata_acao_fim IS NOT NULL');
if ($Aplic->profissional){
	$sql->esqUnir('ata_gestao','ata_gestao','ata_gestao_ata = ata.ata_id');
	if ($tarefa_id) $sql->adOnde('ata_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=ata_gestao_tarefa');
		$sql->adOnde('ata_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('ata_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('ata_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('ata_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('ata_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('ata_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('ata_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('ata_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('ata_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('ata_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('ata_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('ata_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('ata_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('ata_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('ata_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('ata_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('ata_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('ata_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('ata_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('ata_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('ata_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('ata_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('ata_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('ata_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('ata_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('ata_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('ata_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('ata_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('ata_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('ata_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('ata_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('ata_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('ata_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('ata_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('ata_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('ata_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('ata_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('ata_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('ata_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('ata_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('ata_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('ata_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('ata_gestao_tr IN ('.$tr_id.')');
	elseif ($me_id) $sql->adOnde('ata_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('ata_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('ata_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('ata_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('ata_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('ata_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('ata_gestao_plano_gestao IN ('.$pg_id.')');
	}
if ($usuario_id) $sql->adOnde('ata_acao_usuario_usuario='.(int)$usuario_id.' OR ata_acao_responsavel='.(int)$usuario_id);
if ($cia_id) $sql->adOnde('ata_cia IN ('.$cia_id.')');
if ($ata_id) $sql->adOnde('ata.ata_id = '.(int)$ata_id);
if ($dept_id) $sql->adOnde('ata_dept IN ('.$dept_id.') OR ata_dept_dept IN ('.$dept_id.')');
$sql->adOrdem('ata_acao_inicio');
$itens = $sql->Lista();
$sql->limpar();

$itens2 = array();
$inicio_hora = $config['cal_dia_inicio'];
$fim_hora = $config['cal_dia_fim'];
$data=getParam($_REQUEST, 'data', '');

//MUDAR DEPOIS POIS NÃO ESTÁ CONSIDERANDO EXPEDIENTE POSSIVELMENTE CADASTRADO
foreach ($itens as $linha) {
	if(permiteAcessarAta($linha['ata_acesso'], $linha['ata_id'])){	

		$inicio = new CData($linha['ata_acao_inicio']);
		$fim = new CData($linha['ata_acao_fim']);
		
		//preciso arredondar os minutos para exibição
		if ($inicio->minuto <= 7) $inicio->minuto=0;
		elseif ($inicio->minuto <= 22) $inicio->minuto=15;
		elseif ($inicio->minuto <= 37) $inicio->minuto=30;
		elseif ($inicio->minuto <= 52) $inicio->minuto=45;
		else {
			$inicio->minuto=0;
			if ($inicio->hora < 23) $fim->hora++;
			}
		
		if ($fim->minuto <= 7) $fim->minuto=0;
		elseif ($fim->minuto <= 22) $fim->minuto=15;
		elseif ($fim->minuto <= 37) $fim->minuto=30;
		elseif ($fim->minuto <= 52) $fim->minuto=45;
		else {
			$fim->minuto=0;
			if ($fim->hora < 23) $fim->hora++;
			}
		
		
		
		//inicia e termina no mesmo dia
		if ($inicio->format("%Y%m%d")==$fim->format("%Y%m%d")){
			$itens2[$inicio->format('%H%M%S')][] = $linha;
			if ($inicio_hora > $inicio->format('%H')) $inicio_hora = $inicio->format('%H');
			if ($fim_hora < $fim->format('%H')) $fim_hora = $fim->format('%H');
			}
		elseif($data==$inicio->format("%Y%m%d")){  
			// estou no 1o dia
			if ($inicio_hora > $inicio->format('%H')) $inicio_hora = $inicio->format('%H');
			$linha['ata_acao_fim']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_fim']< 10 ? '0' : '').$config['cal_dia_fim'].':00:00';
			$itens2[$inicio->format('%H%M%S')][] = $linha;
			}
		elseif($data==$fim->format("%Y%m%d")){ 
			// estou no ultimo dia
			$linha['ata_acao_inicio']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].':00:00';
			$itens2[($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].'0000'][] = $linha;
			if ($fim_hora < $fim->format('%H')) $fim_hora = $fim->format('%H');
			}
		else{
			//um dia no meio
			$linha['ata_acao_fim']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_fim']< 10 ? '0' : '').$config['cal_dia_fim'].':00:00';
			$linha['ata_acao_inicio']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].':00:00';
			$itens2[($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].'0000'][] = $linha;
			}
		}
	}

	

$diaFormato = $este_dia->format('%Y%m%d');

$inicio=0;
$fim=24;
$inc=15;

$este_dia->setTime($inicio, 0, 0);
$saida = '<table cellpadding=0 cellspacing=0 class="tbl1" width="100%" style="background-color:#ffffff">';
$linhas = 0;
for ($i = 0, $n = (($fim - $inicio) * 60 / $inc); $i <= $n; $i++) {
	$saida .= '<tr>';
	$tm = $este_dia->format('%H:%M');
	$saida .= '<td width="1%" align="right" style="white-space: nowrap">'.($este_dia->getMinute() ? $tm : '<b>'.$tm.'</b>').'</td>';
	$formato_horas = $este_dia->format('%H%M%S');
	if (isset($itens2[$formato_horas]) && $itens2[$formato_horas]) {
		$quantidade = count($itens2[$formato_horas]);
		for ($j = 0; $j < $quantidade; $j++) {
			
			$linha = $itens2[$formato_horas][$j];
			$et = new CData($linha['ata_acao_fim']);
			$linhas = ((($et->getHour() * 60 + $et->getMinute()) - ($este_dia->getHour() * 60 + $este_dia->getMinute())) / $inc)+1;
			$saida .= '<td style="color:#'.melhorCor($linha['cor']).';background-color:#'.$linha['cor'].'" rowspan="'.$linhas.'" valign="top">';
			$saida .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=atas&a=ata_ver&ata_id='.(int)$linha['ata_id'].'\');">'.dica('Ação', $linha['ata_acao_texto']).$linha['ata_titulo'].dicaF().'</a></td>';
			}
		} 
	elseif (--$linhas <= 0) $saida .= '<td>&nbsp;</td>';
	$saida .= '</tr>';
	$este_dia->adSegundos(60 * $inc);
	}
$saida .= '</table>';
echo $saida;
?>