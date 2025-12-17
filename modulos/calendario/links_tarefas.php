<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

function getProblemaLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=null, $envolvimento=null, $cia_id = null, $dept_id=null, 
	$projeto_id=null,
	$tarefa_id=null,
	$pg_perspectiva_id=null,
	$tema_id=null,
	$objetivo_id=null,
	$fator_id=null,
	$pg_estrategia_id=null,
	$pg_meta_id=null,
	$pratica_id=null,
	$pratica_indicador_id=null,
	$plano_acao_id=null,
	$canvas_id=null,
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null,
	$monitoramento_id=null,
	$ata_id=null,
	$mswot_id=null,
	$swot_id=null,
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$evento_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null,
	$plano_acao_item_id=null,
	$beneficio_id=null,
	$painel_slideshow_id=null,
	$projeto_viabilidade_id=null,
	$projeto_abertura_id=null,
	$pg_id=null,
	$ssti_id=null,
	$laudo_id=null,
	$trelo_id=null,
	$trelo_cartao_id=null,
	$pdcl_id=null,
	$pdcl_item_id=null) {
	global $a, $Aplic, $config;

	$db_inicio=$inicioPeriodo->format('%Y-%m-%d %H:%M:%S');
	$db_fim=$fimPeriodo->format('%Y-%m-%d %H:%M:%S');
	$tarefas_filtro = '';
	
	$sql = new BDConsulta;
	$sql->adTabela('problema');
	if ($usuario_id) $sql->esqUnir('problema_usuarios', 'problema_usuarios', 'problema_usuarios.problema_id=problema.problema_id');

	
	if ($dept_id) {
		$sql->esqUnir('problema_depts', 'problema_depts', 'problema_depts.problema_id = problema.problema_id');
		$sql->adOnde('problema_dept IN ('.$dept_id.') OR problema_depts.dept_id IN ('.$dept_id.')');
		}
	elseif (!$envolvimento && $Aplic->profissional && $cia_id) {
		$sql->esqUnir('problema_cia', 'problema_cia', 'problema_cia_problema = problema.problema_id');
		$sql->adOnde('problema_cia IN ('.$cia_id.') OR problema_cia_cia IN ('.$cia_id.')');
		}
	elseif ($cia_id) $sql->adOnde('problema_cia IN ('.$cia_id.')');

	
	
	
		
	$sql->adCampo('DISTINCT problema.problema_id, problema_nome, problema_descricao, problema_acesso, problema_inicio, problema_fim, problema_cor AS cor');
	$sql->adOnde('problema_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('problema_fim >= \''.$db_inicio. '\'');
	$sql->adOnde('problema_inicio IS NOT NULL');
	$sql->adOnde('problema_fim IS NOT NULL');
	
	$sql->esqUnir('problema_gestao', 'problema_gestao', 'problema.problema_id = problema_gestao_problema');
	
	if ($tarefa_id) $sql->adOnde('problema_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=problema_gestao_tarefa');
		$sql->adOnde('problema_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('problema_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('problema_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('problema_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('problema_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('problema_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('problema_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('problema_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('problema_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('problema_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('problema_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('problema_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('problema_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('problema_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('problema_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('problema_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('problema_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('problema_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('problema_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('problema_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('problema_gestao_recurso IN ('.$recurso_id.')');
	
	elseif ($problema_id) $sql->adOnde('problema_gestao_semelhante IN ('.$problema_id.')');
	
	elseif ($demanda_id) $sql->adOnde('problema_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('problema_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('problema_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('problema_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('problema_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('problema_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('problema_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('problema_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('problema_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('problema_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('problema_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('problema_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('problema_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('problema_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('problema_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('problema_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('problema_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('problema_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('problema_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('problema_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('problema_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('problema_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('problema_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('problema_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('problema_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('problema_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('problema_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('problema_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('problrma_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('problrma_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('problrma_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('problrma_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('problrma_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('problrma_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	if ($usuario_id) $sql->adOnde('problema_usuarios.usuario_id='.(int)$usuario_id.' OR problema_responsavel='.(int)$usuario_id);
	
	if ($problema_id) $sql->adOnde('problema.problema_id = '.(int)$problema_id);
	
	$sql->adOrdem('problema_inicio');
	$itens = $sql->Lista();
	$sql->limpar();

	$link = array();
	foreach ($itens as $linha) {
		if (permiteAcessarProblema($linha['problema_acesso'], $linha['problema_id'])){	

			$inicio = new CData($linha['problema_inicio']);
			$fim = new CData($linha['problema_fim']);

			$data = $inicio;
			for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
				$meio=false;
				if ($data->format('%d/%m/%Y')==$inicio->format('%d/%m/%Y') && $data->format('%d/%m/%Y')==$fim->format('%d/%m/%Y')) $inicio_fim=imagem('icones/inicio.gif').$inicio->format('%H:%M').imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format('%H:%M').imagem('icones/fim.gif');
				elseif ($data->format('%d/%m/%Y')==$inicio->format('%d/%m/%Y')) $inicio_fim=imagem('icones/inicio.gif').$inicio->format('%H:%M').imagem('icones/vazio.gif');
				elseif ($data->format('%d/%m/%Y')==$fim->format('%d/%m/%Y')) $inicio_fim=imagem('icones/vazio.gif').$fim->format('%H:%M').imagem('icones/fim.gif');
				else {
					$meio=true;
					$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
					}
				$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.imagem('icones/problema_p.png').$linha['problema_nome'].'</td></tr>';
				if ($minutoiCal) $link = array('problema' => true, 'texto_mini' => $texto);
				else {
					$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=problema&a=problema_ver&problema_id='.(int)$linha['problema_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.dica(ucfirst($config['problema']), $linha['problema_descricao']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=problema&a=problema_ver&problema_id='.(int)$linha['problema_id'].'\');">'.imagem('icones/problema_p.png').$linha['problema_nome'].'</a>'.dicaF().'</td></tr>';
					$link['texto_mini']=$texto;
					$link['problema']=true;
					}	
				if ($periodo_todo || !$meio) $links[$data->format('%Y%m%d')][] = $link;
	
				$data = $data->getNextDay();
				}
			}	
		}
	return $links;
	}

function getAtaLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=null, $envolvimento=null, $cia_id = null, $dept_id=null, 
	$projeto_id=null,
	$tarefa_id=null,
	$pg_perspectiva_id=null,
	$tema_id=null,
	$objetivo_id=null,
	$fator_id=null,
	$pg_estrategia_id=null,
	$pg_meta_id=null,
	$pratica_id=null,
	$pratica_indicador_id=null,
	$plano_acao_id=null,
	$canvas_id=null,
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null,
	$monitoramento_id=null,
	$ata_id=null,
	$mswot_id=null,
	$swot_id=null,
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$evento_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null,
	$plano_acao_item_id=null,
	$beneficio_id=null,
	$painel_slideshow_id=null,
	$projeto_viabilidade_id=null,
	$projeto_abertura_id=null,
	$pg_id=null,
	$ssti_id=null,
	$laudo_id=null,
	$trelo_id=null,
	$trelo_cartao_id=null,
	$pdcl_id=null,
	$pdcl_item_id=null) {
	global $a, $Aplic, $config;
	
	$db_inicio=$inicioPeriodo->format('%Y-%m-%d %H:%M:%S');
	$db_fim=$fimPeriodo->format('%Y-%m-%d %H:%M:%S');
	$tarefas_filtro = '';
	
	$sql = new BDConsulta;
	$sql->adTabela('ata', 'ata');
	if ($usuario_id) $sql->esqUnir('ata_usuario', 'ata_usuario', 'ata_usuario_ata=ata.ata_id');
	
	if ($dept_id) {
		$sql->esqUnir('ata_dept', 'ata_dept', 'ata_dept_ata = ata.ata_id');
		$sql->adOnde('ata_dept IN ('.$dept_id.') OR ata_dept_dept IN ('.$dept_id.')');
		}
	elseif (!$envolvimento && $Aplic->profissional && $cia_id) {
		$sql->esqUnir('ata_cia', 'ata_cia', 'ata_cia_ata = ata.ata_id');
		$sql->adOnde('ata_cia IN ('.$cia_id.') OR ata_cia_cia IN ('.$cia_id.')');
		}
	elseif ($cia_id) $sql->adOnde('ata_cia IN ('.$cia_id.')');
	
	
		
	$sql->adCampo('DISTINCT ata.ata_id, ata_titulo, ata_acesso, ata_data_inicio, ata_data_fim, ata_cor AS cor, ata_titulo');
	$sql->adOnde('ata_data_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('ata_data_fim >= \''.$db_inicio. '\'');
	$sql->adOnde('ata_data_inicio IS NOT NULL');
	$sql->adOnde('ata_data_fim IS NOT NULL');
	
	$sql->esqUnir('ata_gestao', 'ata_gestao', 'ata.ata_id = ata_gestao_acao');
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
	
	elseif ($ata_id) $sql->adOnde('ata_gestao_semelhante IN ('.$ata_id.')');
	
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
	elseif ($tr_id) $sql->adOnde('ata_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('ata_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('ata_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('ata_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('ata_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('ata_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('ata_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('ata_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('ata_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('ata_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('ata_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('ata_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('ata_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('ata_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	if ($usuario_id) $sql->adOnde('ata_acao_usuario_usuario='.(int)$usuario_id.' OR ata_acao_responsavel='.(int)$usuario_id);
	if ($ata_id) $sql->adOnde('ata.ata_id = '.(int)$ata_id);
	if ($dept_id) $sql->adOnde('ata_dept IN ('.$dept_id.') OR ata_dept_dept IN ('.$dept_id.')');
	$sql->adOrdem('ata_data_inicio');

	$itens = $sql->Lista();
	$sql->limpar();

	$link = array();
	foreach ($itens as $linha) {
		if (permiteAcessarAta($linha['ata_acesso'], $linha['ata_id'])){	

			$inicio = new CData($linha['ata_data_inicio']);
			$fim = new CData($linha['ata_data_fim']);

			$data = $inicio;
			for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
				$meio=false;
				if ($data->format('%d/%m/%Y')==$inicio->format('%d/%m/%Y') && $data->format('%d/%m/%Y')==$fim->format('%d/%m/%Y')) $inicio_fim=imagem('icones/inicio.gif').$inicio->format('%H:%M').imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format('%H:%M').imagem('icones/fim.gif');
				elseif ($data->format('%d/%m/%Y')==$inicio->format('%d/%m/%Y')) $inicio_fim=imagem('icones/inicio.gif').$inicio->format('%H:%M').imagem('icones/vazio.gif');
				elseif ($data->format('%d/%m/%Y')==$fim->format('%d/%m/%Y')) $inicio_fim=imagem('icones/vazio.gif').$fim->format('%H:%M').imagem('icones/fim.gif');
				else {
					$meio=true;
					$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
					}
				$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.imagem('icones/ata_p.png').$linha['ata_titulo'].'</td></tr>';
				if ($minutoiCal) $link = array('ata' => true, 'texto_mini' => $texto);
				else {
					$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=atas&a=ata_ver&ata_id='.(int)$linha['ata_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.dica('Ata de Reunião', $linha['ata_titulo']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=atas&a=ata_ver&ata_id='.(int)$linha['ata_id'].'\');">'.imagem('icones/ata_p.png').$linha['ata_titulo'].'</a>'.dicaF().'</td></tr>';
					$link['texto_mini']=$texto;
					$link['ata']=true;
					}	
				if ($periodo_todo || !$meio) $links[$data->format('%Y%m%d')][] = $link;
	
				$data = $data->getNextDay();
				}
			}	
		}
	return $links;
	}







function getAtaAcaoLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=null, $envolvimento=null, $cia_id = null, $dept_id=null, 
	$projeto_id=null,
	$tarefa_id=null,
	$pg_perspectiva_id=null,
	$tema_id=null,
	$objetivo_id=null,
	$fator_id=null,
	$pg_estrategia_id=null,
	$pg_meta_id=null,
	$pratica_id=null,
	$pratica_indicador_id=null,
	$plano_acao_id=null,
	$canvas_id=null,
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null,
	$monitoramento_id=null,
	$ata_id=null,
	$mswot_id=null,
	$swot_id=null,
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$evento_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null,
	$plano_acao_item_id=null,
	$beneficio_id=null,
	$painel_slideshow_id=null,
	$projeto_viabilidade_id=null,
	$projeto_abertura_id=null,
	$pg_id=null,
	$ssti_id=null,
	$laudo_id=null,
	$trelo_id=null,
	$trelo_cartao_id=null,
	$pdcl_id=null,
	$pdcl_item_id=null) {
	global $a, $Aplic, $config;
	
	$db_inicio=$inicioPeriodo->format('%Y-%m-%d %H:%M:%S');
	$db_fim=$fimPeriodo->format('%Y-%m-%d %H:%M:%S');
	$tarefas_filtro = '';
	
	$sql = new BDConsulta;
	$sql->adTabela('ata_acao','ata_acao');
	$sql->esqUnir('ata', 'ata', 'ata.ata_id = ata_acao_ata');
	if ($usuario_id) $sql->esqUnir('ata_acao_usuario', 'ata_acao_usuario', 'ata_acao_usuario_acao=ata_acao.ata_acao_id');
	
	if ($dept_id) {
		$sql->esqUnir('ata_dept', 'ata_dept', 'ata_dept_ata = ata.ata_id');
		$sql->adOnde('ata_dept IN ('.$dept_id.') OR ata_dept_dept IN ('.$dept_id.')');
		}
	elseif (!$envolvimento && $Aplic->profissional && $cia_id) {
		$sql->esqUnir('ata_cia', 'ata_cia', 'ata_cia_ata = ata.ata_id');
		$sql->adOnde('ata_cia IN ('.$cia_id.') OR ata_cia_cia IN ('.$cia_id.')');
		}
	elseif ($cia_id) $sql->adOnde('ata_cia IN ('.$cia_id.')');
	
	
	
		
	$sql->adCampo('DISTINCT ata_acao.ata_acao_id, ata_acao_texto, ata_titulo, ata_acesso, ata_acao_inicio, ata_acao_fim, ata_cor AS cor, ata_titulo, ata.ata_id');
	$sql->adOnde('ata_acao_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('ata_acao_fim >= \''.$db_inicio. '\'');
	$sql->adOnde('ata_acao_inicio IS NOT NULL');
	$sql->adOnde('ata_acao_fim IS NOT NULL');
	

	$sql->esqUnir('ata_gestao', 'ata_gestao', 'ata.ata_id = ata_gestao_acao');
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
	
	elseif ($ata_id) $sql->adOnde('ata_gestao_semelhante IN ('.$ata_id.')');
	
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
	elseif ($tr_id) $sql->adOnde('ata_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('ata_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('ata_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('ata_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('ata_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('ata_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('ata_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('ata_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('ata_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('ata_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('ata_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('ata_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('ata_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('ata_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	if ($usuario_id) $sql->adOnde('ata_acao_usuario_usuario='.(int)$usuario_id.' OR ata_acao_responsavel='.(int)$usuario_id);
	if ($ata_id) $sql->adOnde('ata.ata_id = '.(int)$ata_id);
	if ($dept_id) $sql->adOnde('ata_dept IN ('.$dept_id.') OR ata_dept_dept IN ('.$dept_id.')');
	$sql->adOrdem('ata_acao_inicio');

	$itens = $sql->Lista();
	$sql->limpar();

	$link = array();
	foreach ($itens as $linha) {
		if (permiteAcessarAta($linha['ata_acesso'], $linha['ata_id'])){	

			$inicio = new CData($linha['ata_acao_inicio']);
			$fim = new CData($linha['ata_acao_fim']);

			$data = $inicio;
			for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
				$meio=false;
				if ($data->format('%d/%m/%Y')==$inicio->format('%d/%m/%Y') && $data->format('%d/%m/%Y')==$fim->format('%d/%m/%Y')) $inicio_fim=imagem('icones/inicio.gif').$inicio->format('%H:%M').imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format('%H:%M').imagem('icones/fim.gif');
				elseif ($data->format('%d/%m/%Y')==$inicio->format('%d/%m/%Y')) $inicio_fim=imagem('icones/inicio.gif').$inicio->format('%H:%M').imagem('icones/vazio.gif');
				elseif ($data->format('%d/%m/%Y')==$fim->format('%d/%m/%Y')) $inicio_fim=imagem('icones/vazio.gif').$fim->format('%H:%M').imagem('icones/fim.gif');
				else {
					$meio=true;
					$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
					}
				$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.imagem('icones/ata_acao_p.png').$linha['ata_titulo'].'</td></tr>';
				if ($minutoiCal) $link = array('ata_acao' => true, 'texto_mini' => $texto);
				else {
					$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=atas&a=ata_ver&ata_id='.(int)$linha['ata_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.dica('Ação de Ata de Reunião', $linha['ata_acao_texto']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=atas&a=ata_ver&ata_id='.(int)$linha['ata_id'].'\');">'.imagem('icones/ata_acao_p.png').$linha['ata_titulo'].'</a>'.dicaF().'</td></tr>';
					$link['texto_mini']=$texto;
					$link['ata_acao']=true;
					}	
				if ($periodo_todo || !$meio) $links[$data->format('%Y%m%d')][] = $link;
	
				$data = $data->getNextDay();
				}
			}	
		}
	return $links;
	}





function getAcaoLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=null, $envolvimento=null, $cia_id = null, $dept_id=null, 
	$projeto_id=null,
	$tarefa_id=null,
	$pg_perspectiva_id=null,
	$tema_id=null,
	$objetivo_id=null,
	$fator_id=null,
	$pg_estrategia_id=null,
	$pg_meta_id=null,
	$pratica_id=null,
	$pratica_indicador_id=null,
	$plano_acao_id=null,
	$canvas_id=null,
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null,
	$monitoramento_id=null,
	$ata_id=null,
	$mswot_id=null,
	$swot_id=null,
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$evento_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null,
	$plano_acao_item_id=null,
	$beneficio_id=null,
	$painel_slideshow_id=null,
	$projeto_viabilidade_id=null,
	$projeto_abertura_id=null,
	$pg_id=null,
	$ssti_id=null,
	$laudo_id=null,
	$trelo_id=null,
	$trelo_cartao_id=null,
	$pdcl_id=null,
	$pdcl_item_id=null) {
	global $a, $Aplic, $config;

	
	
	$db_inicio=$inicioPeriodo->format('%Y-%m-%d %H:%M:%S');
	$db_fim=$fimPeriodo->format('%Y-%m-%d %H:%M:%S');
	$tarefas_filtro = '';
	
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_item_acao');
	if ($usuario_id) $sql->esqUnir('plano_acao_item_usuario', 'plano_acao_item_usuario', 'plano_acao_item_usuario_item=plano_acao_item.plano_acao_item_id');
		
		
	if ($dept_id) {
		$sql->esqUnir('plano_acao_item_dept', 'plano_acao_item_dept', 'plano_acao_item_dept_plano_acao_item = plano_acao_item.plano_acao_item_id');
		$sql->adOnde('plano_acao_item_dept IN ('.$dept_id.') OR plano_acao_item_dept_dept IN ('.$dept_id.')');
		}
	elseif (!$envolvimento && $Aplic->profissional && $cia_id) {
		$sql->esqUnir('plano_acao_item_cia', 'plano_acao_item_cia', 'plano_acao_item_cia_plano_acao_item = plano_acao_item.plano_acao_item_id');
		$sql->adOnde('plano_acao_item_cia IN ('.$cia_id.') OR plano_acao_item_cia_cia IN ('.$cia_id.')');
		}
	elseif ($cia_id) $sql->adOnde('plano_acao_item_cia IN ('.$cia_id.')');	
		
		
	$sql->adCampo('plano_acao_item.plano_acao_item_id, plano_acao_item_nome, plano_acao_nome, plano_acao_item_acesso, plano_acao_item_inicio, plano_acao_item_fim, plano_acao_cor AS cor, plano_acao_item_oque, plano_acao_id');
	$sql->adOnde('plano_acao_item_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('plano_acao_item_fim >= \''.$db_inicio. '\'');
	$sql->adOnde('plano_acao_item_inicio IS NOT NULL');
	$sql->adOnde('plano_acao_item_fim IS NOT NULL');
	
	if ($plano_acao_id) $sql->adOnde('plano_acao.plano_acao_id='.(int)$plano_acao_id);
	$sql->esqUnir('plano_acao_gestao', 'plano_acao_gestao', 'plano_acao.plano_acao_id = plano_acao_gestao_acao');
	if ($tarefa_id) $sql->adOnde('plano_acao_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=plano_acao_gestao_tarefa');
		$sql->adOnde('plano_acao_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('plano_acao_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('plano_acao_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('plano_acao_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('plano_acao_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('plano_acao_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('plano_acao_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('plano_acao_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('plano_acao_gestao_indicador IN ('.$pratica_indicador_id.')');
	
	elseif ($plano_acao_id) $sql->adOnde('plano_acao_gestao_semelhante IN ('.$plano_acao_id.')');
	
	elseif ($canvas_id) $sql->adOnde('plano_acao_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('plano_acao_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('plano_acao_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('plano_acao_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('plano_acao_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('plano_acao_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('plano_acao_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('plano_acao_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('plano_acao_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('plano_acao_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('plano_acao_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('plano_acao_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('plano_acao_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('plano_acao_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('plano_acao_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('plano_acao_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('plano_acao_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('plano_acao_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('plano_acao_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('plano_acao_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('plano_acao_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('plano_acao_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('plano_acao_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('plano_acao_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('plano_acao_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('plano_acao_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('plano_acao_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('plano_acao_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('plano_acao_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('plano_acao_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('plano_acao_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('plano_acao_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('plano_acao_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('plano_acao_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('plano_acao_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('plano_acao_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('plano_acao_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('plano_acao_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('plano_acao_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('plano_acao_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('plano_acao_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('plano_acao_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('plano_acao_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('plano_acao_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('plano_acao_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	if ($usuario_id) $sql->adOnde('(plano_acao_item_usuario_usuario='.(int)$usuario_id.' OR plano_acao_item_responsavel='.(int)$usuario_id.')');

	if ($plano_acao_id) $sql->adOnde('plano_acao.plano_acao_id = '.(int)$plano_acao_id);
	$sql->adOrdem('plano_acao_item_inicio');
	$sql->adGrupo('plano_acao_item.plano_acao_item_id');
	$itens = $sql->Lista();
	$sql->limpar();

	$link = array();
	foreach ($itens as $linha) {
		
		if (permiteAcessarPlanoAcaoItem($linha['plano_acao_item_acesso'], $linha['plano_acao_item_id'])){	

			$inicio = new CData($linha['plano_acao_item_inicio']);
			$fim = new CData($linha['plano_acao_item_fim']);

			$data = $inicio;
			for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
				$meio=false;
				if ($data->format('%d/%m/%Y')==$inicio->format('%d/%m/%Y') && $data->format('%d/%m/%Y')==$fim->format('%d/%m/%Y')) $inicio_fim=imagem('icones/inicio.gif').$inicio->format('%H:%M').imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format('%H:%M').imagem('icones/fim.gif');
				elseif ($data->format('%d/%m/%Y')==$inicio->format('%d/%m/%Y')) $inicio_fim=imagem('icones/inicio.gif').$inicio->format('%H:%M').imagem('icones/vazio.gif');
				elseif ($data->format('%d/%m/%Y')==$fim->format('%d/%m/%Y')) $inicio_fim=imagem('icones/vazio.gif').$fim->format('%H:%M').imagem('icones/fim.gif');
				else {
					$meio=true;
					$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
					}
				$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.imagem('icones/plano_acao_p.gif').($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']).'</td></tr>';
				if ($minutoiCal) $link = array('acao' => true, 'texto_mini' => $texto);
				else {
					$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.(int)$linha['plano_acao_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.imagem('icones/plano_acao_p.gif').link_acao_item($linha['plano_acao_item_id'],'','',$strMaxLarg).'</td></tr>';
					$link['texto_mini']=$texto;
					$link['acao']=true;
					}	
				if ($periodo_todo || !$meio) $links[$data->format('%Y%m%d')][] = $link;
	
				$data = $data->getNextDay();
				}
			}	
		
		}
	return $links;
	}


function getTarefaLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=0, $envolvimento=false, $cia_id = '', $dept_id='', 
	$projeto_id=null,
	$tarefa_id=null,
	$pg_perspectiva_id=null,
	$tema_id=null,
	$objetivo_id=null,
	$fator_id=null,
	$pg_estrategia_id=null,
	$pg_meta_id=null,
	$pratica_id=null,
	$pratica_indicador_id=null,
	$plano_acao_id=null,
	$canvas_id=null,
	$risco_id=null,
	$risco_resposta_id=null,
	$calendario_id=null,
	$monitoramento_id=null,
	$ata_id=null,
	$mswot_id=null,
	$swot_id=null,
	$operativo_id=null,
	$instrumento_id=null,
	$recurso_id=null,
	$problema_id=null,
	$demanda_id=null,
	$programa_id=null,
	$licao_id=null,
	$evento_id=null,
	$link_id=null,
	$avaliacao_id=null,
	$tgn_id=null,
	$brainstorm_id=null,
	$gut_id=null,
	$causa_efeito_id=null,
	$arquivo_id=null,
	$forum_id=null,
	$checklist_id=null,
	$agenda_id=null,
	$agrupamento_id=null,
	$patrocinador_id=null,
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null,
	$plano_acao_item_id=null,
	$beneficio_id=null,
	$painel_slideshow_id=null,
	$projeto_viabilidade_id=null,
	$projeto_abertura_id=null,
	$pg_id=null,
	$ssti_id=null,
	$laudo_id=null,
	$trelo_id=null,
	$trelo_cartao_id=null,
	$pdcl_id=null,
	$pdcl_item_id=null
	) {
	global $a, $Aplic, $config;
	require_once ($Aplic->getClasseModulo('tarefas'));
	$tarefas = CTarefa::getTarefasParaPeriodo($inicioPeriodo, $fimPeriodo, $usuario_id, $envolvimento, $cia_id, $dept_id, 
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
		$pg_id,
		$ssti_id,
		$laudo_id,
		$trelo_id,
		$trelo_cartao_id,
		$pdcl_id,
		$pdcl_item_id
		);
	
	
        
	$link = array();
    
    $dtInicio = $inicioPeriodo->format('%Y-%m-%d');
    $dtFim = $fimPeriodo->format('%Y-%m-%d');
    
    if(!$periodo_todo){
        //somente inicio e fim
        foreach ($tarefas as $linha) {
            if (permiteAcessar($linha['tarefa_acesso'], $linha['projeto_id'], $linha['tarefa_id'])){
                $inicio = substr($linha['tarefa_inicio'], 0,10);
                $fim = substr($linha['tarefa_fim'], 0,10);
                if($dtInicio > $inicio) $inicio = null;
                if($dtFim < $fim) $fim = null;

                if($inicio || $fim){
                    if($fim == $inicio){
                        //inicio e fim no mesmo dia
                        $dataIni = new CData($linha['tarefa_inicio']);
                        $dataFim = new CData($linha['tarefa_fim']);
                        $imagem = imagem('icones/inicio.gif').$dataIni->format('%H:%M').imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$dataFim->format('%H:%M').imagem('icones/fim.gif');
                        $texto='<tr valign=middle><td>'.$imagem.'</td><td>'.$linha['tarefa_nome'].'</td></tr>';
                        if ($minutoiCal) $link = array('tarefa' => true, 'texto_mini' => $texto);
                        else {
                            $link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$linha['tarefa_id'].'\');">'.$imagem.'</a></td><td valign=middle>'.link_tarefa($linha['tarefa_id'],'','',$strMaxLarg).'</td></tr>';
                            $link['texto_mini']=$texto;
                            $link['tarefa']=true;
                            }
                        $links[$dataIni->format('%Y%m%d')][] = $link;
                        }
                    else{
                        if($inicio){
                            $data = new CData($linha['tarefa_inicio']);
                            $imagem = imagem('icones/inicio.gif').$data->format('%H:%M').imagem('icones/vazio.gif');
                            $texto='<tr valign=middle><td>'.$imagem.'</td><td>'.$linha['tarefa_nome'].'</td></tr>';
                            if ($minutoiCal) $link = array('tarefa' => true, 'texto_mini' => $texto);
                            else {
                                $link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$linha['tarefa_id'].'\');">'.$imagem.'</a></td><td valign=middle>'.link_tarefa($linha['tarefa_id'],'','',$strMaxLarg).'</td></tr>';
                                $link['texto_mini'] =$texto;
                                $link['tarefa']=true;
                                }
                                
                            $links[$data->format('%Y%m%d')][] = $link;
                            }
                            
                        if($fim){
                            $data = new CData($linha['tarefa_fim']);
                            $imagem = imagem('icones/vazio.gif').$data->format('%H:%M').imagem('icones/fim.gif');
                            $texto='<tr valign=middle><td>'.$imagem.'</td><td>'.$linha['tarefa_nome'].'</td></tr>';
                            if ($minutoiCal) $link = array('tarefa' => true, 'texto_mini' => $texto);
                            else {
                                $link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$linha['tarefa_id'].'\');">'.$imagem.'</a></td><td valign=middle>'.link_tarefa($linha['tarefa_id'],'','',$strMaxLarg).'</td></tr>';
                                $link['texto_mini']=$texto;
                                $link['tarefa']=true;
                                }
                                
                            $links[$data->format('%Y%m%d')][] = $link;
                            }
                        }
                    }
                }
            }
        }
    else{
        //todos os dias
        foreach ($tarefas as $linha) {
            if (permiteAcessar($linha['tarefa_acesso'], $linha['projeto_id'], $linha['tarefa_id'])){
                $inicio = substr($linha['tarefa_inicio'], 0,10);
                $fim = substr($linha['tarefa_fim'], 0,10);
                
                $inicioReal = $inicio;
                $fimReal = $fim;
                
                if($dtInicio > $inicio) $inicio = $dtInicio;
                if($dtFim < $fim) $fim = $dtFim;
                
                $dataObj = new CData($inicio);
                $data = $dataObj->format('%Y-%m-%d');
                while($data <= $fim){                   
                    if($data == $inicioReal && $data == $fimReal){
                        //inicio e fim no mesmo dia
                        $dataIni = new CData($linha['tarefa_inicio']);
                        $dataFim = new CData($linha['tarefa_fim']);
                        $imagem = imagem('icones/inicio.gif').$dataIni->format('%H:%M').imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$dataFim->format('%H:%M').imagem('icones/fim.gif');
                        }
                    else if($data == $inicioReal){
                        $data = new CData($linha['tarefa_inicio']);
                        $imagem = imagem('icones/inicio.gif').$data->format('%H:%M').imagem('icones/vazio.gif');
                        }
                    else if($data == $fimReal){
                        $data = new CData($linha['tarefa_fim']);
                        $imagem = imagem('icones/vazio.gif').$data->format('%H:%M').imagem('icones/fim.gif');
                        }
                    else{
                        //no meio da tarefa
                        $imagem = imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
                        }
                        
                    $texto='<tr valign=middle><td>'.$imagem.'</td><td>'.$linha['tarefa_nome'].'</td></tr>';
                    if ($minutoiCal) $link = array('tarefa' => true, 'texto_mini' => $texto);
                    else {
                        $link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$linha['tarefa_id'].'\');">'.$imagem.'</a></td><td valign=middle>'.link_tarefa($linha['tarefa_id'],'','',$strMaxLarg).'</td></tr>';
                        $link['texto_mini']=$texto;
                        $link['tarefa']=true;
                        }
                        
                    $links[$dataObj->format('%Y%m%d')][] = $link;
                    
                    $dataObj = $dataObj->getNextDay();
                    $data = $dataObj->format('%Y-%m-%d');
                    }
                }
            }
        }
    
	
	return $links;
	}



?>