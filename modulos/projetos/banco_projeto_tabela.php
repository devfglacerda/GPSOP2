<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


global $envolvimento, $a, $estilo_interface, $dialogo, $cia_id, $lista_cias, $dept_id, $lista_depts, $tab, $pesquisar_texto, $viabilidade_setor, $viabilidade_segmento, $viabilidade_intervencao, $viabilidade_tipo_intervencao, $responsavel,
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
	$pdcl_item_id;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;

$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_banco_projeto']);
$xmin = $xtamanhoPagina * ($pagina - 1);  






$ordenar=getParam($_REQUEST, 'ordenar', ($tab ? 'projeto_abertura_nome' : 'projeto_viabilidade_nome'));
$ordem=getParam($_REQUEST, 'ordem', '0');

$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \''.($tab==0 || $a!='banco_projeto' ? 'projeto_viabilidades' : 'projeto_aberturas').'\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \''.($tab==0 || $a!='banco_projeto' ? 'projeto_viabilidades' : 'projeto_aberturas').'\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();
  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}


if ($tab==0 || $a!='banco_projeto'){
	$sql->adTabela('projeto_viabilidade');
	$sql->esqUnir('demandas', 'demandas', 'demandas.demanda_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
	$sql->adCampo('DISTINCT projeto_viabilidade.projeto_viabilidade_id');
	
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_dept='.(int)$dept_id.' OR projeto_viabilidade_dept.projeto_viabilidade_dept_dept='.(int)$dept_id);
		}	
	elseif ($lista_depts){
		$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_dept IN ('.$lista_depts.') OR  projeto_viabilidade_dept.projeto_viabilidade_dept_dept IN ('.$lista_depts.')');
		}	
	elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('projeto_viabilidade_cia', 'projeto_viabilidade_cia', 'projeto_viabilidade.projeto_viabilidade_id=projeto_viabilidade_cia_projeto_viabilidade');
		$sql->adOnde('projeto_viabilidade_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_viabilidade_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}		
	elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_viabilidade_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('projeto_viabilidade_cia IN ('.$lista_cias.')');		
		
		
	if ($pesquisar_texto) $sql->adOnde('projeto_viabilidade_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_codigo LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_observacao LIKE \'%'.$pesquisar_texto.'%\'');
	$sql->adOnde('projeto_viabilidade_ativo=1');
	
	if ($viabilidade_setor) $sql->adOnde('projeto_viabilidade_setor IN ('.$viabilidade_setor.')');
	if ($viabilidade_segmento) $sql->adOnde('projeto_viabilidade_segmento IN ('.$viabilidade_segmento.')');
	if ($viabilidade_intervencao) $sql->adOnde('projeto_viabilidade_intervencao IN ('.$viabilidade_intervencao.')');
	if ($viabilidade_tipo_intervencao) $sql->adOnde('projeto_viabilidade_tipo_intervencao IN ('.$viabilidade_tipo_intervencao.')');
	
	if($responsavel) {
		$sql->esqUnir('projeto_viabilidade_usuarios', 'projeto_viabilidade_usuarios', 'projeto_viabilidade_usuarios.projeto_viabilidade_id = projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_responsavel IN ('.$responsavel.') OR projeto_viabilidade_usuarios.usuario_id IN ('.$responsavel.')');
		}
	
	if ($Aplic->profissional){
		$sql->esqUnir('projeto_viabilidade_gestao','projeto_viabilidade_gestao','projeto_viabilidade_gestao_projeto_viabilidade = projeto_viabilidade.projeto_viabilidade_id');
		if ($tarefa_id) $sql->adOnde('projeto_viabilidade_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=projeto_viabilidade_gestao_tarefa');
			$sql->adOnde('projeto_viabilidade_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('projeto_viabilidade_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('projeto_viabilidade_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('projeto_viabilidade_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('projeto_viabilidade_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('projeto_viabilidade_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('projeto_viabilidade_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('projeto_viabilidade_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('projeto_viabilidade_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('projeto_viabilidade_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('projeto_viabilidade_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('projeto_viabilidade_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('projeto_viabilidade_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('projeto_viabilidade_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('projeto_viabilidade_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('projeto_viabilidade_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('projeto_viabilidade_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('projeto_viabilidade_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('projeto_viabilidade_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('projeto_viabilidade_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('projeto_viabilidade_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('projeto_viabilidade_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('projeto_viabilidade_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('projeto_viabilidade_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('projeto_viabilidade_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('projeto_viabilidade_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('projeto_viabilidade_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('projeto_viabilidade_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('projeto_viabilidade_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('projeto_viabilidade_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('projeto_viabilidade_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('projeto_viabilidade_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('projeto_viabilidade_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('projeto_viabilidade_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('projeto_viabilidade_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('projeto_viabilidade_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('projeto_viabilidade_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('projeto_viabilidade_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('projeto_viabilidade_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('projeto_viabilidade_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('projeto_viabilidade_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('projeto_viabilidade_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('projeto_viabilidade_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('projeto_viabilidade_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('projeto_viabilidade_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('projeto_viabilidade_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('projeto_viabilidade_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('projeto_viabilidade_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('projeto_viabilidade_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('projeto_viabilidade_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('projeto_viabilidade_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('projeto_viabilidade_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('projeto_viabilidade_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('projeto_viabilidade_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('projeto_viabilidade_gestao_pdcl_item IN ('.$pdcl_item_id.')');		
		}
	$sql->adOnde('projeto_viabilidade_projeto IS NULL');	
	$sql->adOnde('projeto_viabilidade_viavel=1');	
	$sql->adOnde('demanda_termo_abertura IS NULL');	
	}
else{
	$sql->adTabela('projeto_abertura');
	$sql->adCampo('DISTINCT projeto_abertura.projeto_abertura_id');
	
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_dept='.(int)$dept_id.' OR projeto_abertura_dept.projeto_abertura_dept_dept='.(int)$dept_id);
		}	
	elseif ($lista_depts){
		$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_dept IN ('.$lista_depts.') OR  projeto_abertura_dept.projeto_abertura_dept_dept IN ('.$lista_depts.')');
		}	
	elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('projeto_abertura_cia', 'projeto_abertura_cia', 'projeto_abertura.projeto_abertura_id=projeto_abertura_cia_projeto_abertura');
		$sql->adOnde('projeto_abertura_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_abertura_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}		
	elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_abertura_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('projeto_abertura_cia IN ('.$lista_cias.')');		
		
			
	if ($pesquisar_texto) $sql->adOnde('projeto_abertura_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_abertura_codigo LIKE \'%'.$pesquisar_texto.'%\' OR projeto_abertura_observacao LIKE \'%'.$pesquisar_texto.'%\'');
		
	if ($viabilidade_setor) $sql->adOnde('projeto_abertura_setor IN ('.$viabilidade_setor.')');
	if ($viabilidade_segmento) $sql->adOnde('projeto_abertura_segmento IN ('.$viabilidade_segmento.')');
	if ($viabilidade_intervencao) $sql->adOnde('projeto_abertura_intervencao IN ('.$viabilidade_intervencao.')');
	if ($viabilidade_tipo_intervencao) $sql->adOnde('projeto_abertura_tipo_intervencao IN ('.$viabilidade_tipo_intervencao.')');
	
	if($responsavel) {
		$sql->esqUnir('projeto_abertura_usuarios', 'projeto_abertura_usuarios', 'projeto_abertura_usuarios.projeto_abertura_id = projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_responsavel IN ('.$responsavel.') OR projeto_abertura_usuarios.usuario_id IN ('.$responsavel.')');
		}
	
	if ($Aplic->profissional){
		$sql->esqUnir('projeto_abertura_gestao','projeto_abertura_gestao','projeto_abertura_gestao_projeto_abertura = projeto_abertura.projeto_abertura_id');
		if ($tarefa_id) $sql->adOnde('projeto_abertura_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=projeto_abertura_gestao_tarefa');
			$sql->adOnde('projeto_abertura_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('projeto_abertura_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('projeto_abertura_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('projeto_abertura_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('projeto_abertura_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('projeto_abertura_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('projeto_abertura_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('projeto_abertura_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('projeto_abertura_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('projeto_abertura_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('projeto_abertura_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('projeto_abertura_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('projeto_abertura_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('projeto_abertura_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('projeto_abertura_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('projeto_abertura_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('projeto_abertura_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('projeto_abertura_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('projeto_abertura_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('projeto_abertura_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('projeto_abertura_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('projeto_abertura_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('projeto_abertura_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('projeto_abertura_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('projeto_abertura_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('projeto_abertura_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('projeto_abertura_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('projeto_abertura_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('projeto_abertura_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('projeto_abertura_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('projeto_abertura_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('projeto_abertura_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('projeto_abertura_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('projeto_abertura_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('projeto_abertura_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('projeto_abertura_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('projeto_abertura_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('projeto_abertura_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('projeto_abertura_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('projeto_abertura_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('projeto_abertura_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('projeto_abertura_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('projeto_abertura_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('projeto_abertura_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('projeto_abertura_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('projeto_abertura_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('projeto_abertura_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_abertura_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('projeto_abertura_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('projeto_abertura_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('projeto_abertura_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('projeto_abertura_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('projeto_abertura_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('projeto_abertura_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('projeto_abertura_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('projeto_viabilidade_gestao_pdcl_item IN ('.$pdcl_item_id.')');		
		}
	$sql->adOnde('projeto_abertura_ativo=1');
	$sql->adOnde('projeto_abertura_projeto IS NULL');	
	}	
$xtotalregistros = $sql->Resultado();
$sql->limpar();






























if ($tab==0 || $a!='banco_projeto'){
	$sql->adTabela('projeto_viabilidade');
	$sql->esqUnir('demandas', 'demandas', 'demandas.demanda_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
	$sql->adCampo('projeto_viabilidade.*');
	$sql->adCampo('formatar_data(projeto_viabilidade_data, \'%d/%m/%Y\') AS data');
	if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_viabilidade=projeto_viabilidade.projeto_viabilidade_id) AS tem_aprovacao');
	
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_dept='.(int)$dept_id.' OR projeto_viabilidade_dept.projeto_viabilidade_dept_dept='.(int)$dept_id);
		}	
	elseif ($lista_depts){
		$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_dept IN ('.$lista_depts.') OR  projeto_viabilidade_dept.projeto_viabilidade_dept_dept IN ('.$lista_depts.')');
		}	
	elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('projeto_viabilidade_cia', 'projeto_viabilidade_cia', 'projeto_viabilidade.projeto_viabilidade_id=projeto_viabilidade_cia_projeto_viabilidade');
		$sql->adOnde('projeto_viabilidade_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_viabilidade_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}		
	elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_viabilidade_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('projeto_viabilidade_cia IN ('.$lista_cias.')');	
	
	if ($pesquisar_texto) $sql->adOnde('projeto_viabilidade_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_codigo LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_observacao LIKE \'%'.$pesquisar_texto.'%\'');
	$sql->adOnde('projeto_viabilidade_ativo=1');
	
	if ($viabilidade_setor) $sql->adOnde('projeto_viabilidade_setor IN ('.$viabilidade_setor.')');
	if ($viabilidade_segmento) $sql->adOnde('projeto_viabilidade_segmento IN ('.$viabilidade_segmento.')');
	if ($viabilidade_intervencao) $sql->adOnde('projeto_viabilidade_intervencao IN ('.$viabilidade_intervencao.')');
	if ($viabilidade_tipo_intervencao) $sql->adOnde('projeto_viabilidade_tipo_intervencao IN ('.$viabilidade_tipo_intervencao.')');
	
	if($responsavel) {
		$sql->esqUnir('projeto_viabilidade_usuarios', 'projeto_viabilidade_usuarios', 'projeto_viabilidade_usuarios.projeto_viabilidade_id = projeto_viabilidade.projeto_viabilidade_id');
		$sql->adOnde('projeto_viabilidade_responsavel IN ('.$responsavel.') OR projeto_viabilidade_usuarios.usuario_id IN ('.$responsavel.')');
		}
	

	$sql->esqUnir('projeto_viabilidade_gestao','projeto_viabilidade_gestao','projeto_viabilidade_gestao_projeto_viabilidade = projeto_viabilidade.projeto_viabilidade_id');
	if ($tarefa_id) $sql->adOnde('projeto_viabilidade_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=projeto_viabilidade_gestao_tarefa');
		$sql->adOnde('projeto_viabilidade_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('projeto_viabilidade_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('projeto_viabilidade_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('projeto_viabilidade_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('projeto_viabilidade_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('projeto_viabilidade_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('projeto_viabilidade_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('projeto_viabilidade_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('projeto_viabilidade_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('projeto_viabilidade_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('projeto_viabilidade_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('projeto_viabilidade_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('projeto_viabilidade_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('projeto_viabilidade_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('projeto_viabilidade_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('projeto_viabilidade_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('projeto_viabilidade_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('projeto_viabilidade_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('projeto_viabilidade_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('projeto_viabilidade_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('projeto_viabilidade_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('projeto_viabilidade_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('projeto_viabilidade_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('projeto_viabilidade_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('projeto_viabilidade_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('projeto_viabilidade_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('projeto_viabilidade_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('projeto_viabilidade_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('projeto_viabilidade_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('projeto_viabilidade_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('projeto_viabilidade_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('projeto_viabilidade_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('projeto_viabilidade_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('projeto_viabilidade_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('projeto_viabilidade_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('projeto_viabilidade_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('projeto_viabilidade_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('projeto_viabilidade_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('projeto_viabilidade_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('projeto_viabilidade_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('projeto_viabilidade_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('projeto_viabilidade_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('projeto_viabilidade_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('projeto_viabilidade_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('projeto_viabilidade_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('projeto_viabilidade_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('projeto_viabilidade_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('projeto_viabilidade_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('projeto_viabilidade_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('projeto_viabilidade_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('projeto_viabilidade_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('projeto_viabilidade_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('projeto_viabilidade_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('projeto_viabilidade_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('projeto_viabilidade_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
	$sql->adOnde('projeto_viabilidade_projeto IS NULL');	
	$sql->adOnde('projeto_viabilidade_viavel=1');	
	$sql->adOnde('demanda_termo_abertura IS NULL');	
	$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
	$sql->setLimite($xmin, $xtamanhoPagina);
	$sql->adGrupo('projeto_viabilidade.projeto_viabilidade_id');
	}
else{
	$sql->adTabela('projeto_abertura');
	$sql->adCampo('projeto_abertura.*');
	$sql->adCampo('formatar_data(projeto_abertura_data, \'%d/%m/%Y\') AS data');
	if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_abertura=projeto_abertura.projeto_abertura_id) AS tem_aprovacao');
	
	if ($dept_id && !$lista_depts) {
		$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_dept='.(int)$dept_id.' OR projeto_abertura_dept.projeto_abertura_dept_dept='.(int)$dept_id);
		}	
	elseif ($lista_depts){
		$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_dept IN ('.$lista_depts.') OR  projeto_abertura_dept.projeto_abertura_dept_dept IN ('.$lista_depts.')');
		}	
	elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('projeto_abertura_cia', 'projeto_abertura_cia', 'projeto_abertura.projeto_abertura_id=projeto_abertura_cia_projeto_abertura');
		$sql->adOnde('projeto_abertura_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_abertura_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}		
	elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_abertura_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('projeto_abertura_cia IN ('.$lista_cias.')');		
	
	if ($pesquisar_texto) $sql->adOnde('projeto_abertura_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_abertura_codigo LIKE \'%'.$pesquisar_texto.'%\' OR projeto_abertura_observacao LIKE \'%'.$pesquisar_texto.'%\'');
	
	$sql->adOnde('projeto_abertura_ativo=1');
	
	if ($viabilidade_setor) $sql->adOnde('projeto_abertura_setor IN ('.$viabilidade_setor.')');
	if ($viabilidade_segmento) $sql->adOnde('projeto_abertura_segmento IN ('.$viabilidade_segmento.')');
	if ($viabilidade_intervencao) $sql->adOnde('projeto_abertura_intervencao IN ('.$viabilidade_intervencao.')');
	if ($viabilidade_tipo_intervencao) $sql->adOnde('projeto_abertura_tipo_intervencao IN ('.$viabilidade_tipo_intervencao.')');
	
	if($responsavel) {
		$sql->esqUnir('projeto_abertura_usuarios', 'projeto_abertura_usuarios', 'projeto_abertura_usuarios.projeto_abertura_id = projeto_abertura.projeto_abertura_id');
		$sql->adOnde('projeto_abertura_responsavel IN ('.$responsavel.') OR projeto_abertura_usuarios.usuario_id IN ('.$responsavel.')');
		}
	
	if ($Aplic->profissional){
		$sql->esqUnir('projeto_abertura_gestao','projeto_abertura_gestao','projeto_abertura_gestao_projeto_abertura = projeto_abertura.projeto_abertura_id');
		if ($tarefa_id) $sql->adOnde('projeto_abertura_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=projeto_abertura_gestao_tarefa');
			$sql->adOnde('projeto_abertura_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('projeto_abertura_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('projeto_abertura_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('projeto_abertura_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('projeto_abertura_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('projeto_abertura_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('projeto_abertura_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('projeto_abertura_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('projeto_abertura_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('projeto_abertura_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('projeto_abertura_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('projeto_abertura_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('projeto_abertura_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('projeto_abertura_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('projeto_abertura_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('projeto_abertura_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('projeto_abertura_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('projeto_abertura_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('projeto_abertura_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('projeto_abertura_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('projeto_abertura_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('projeto_abertura_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('projeto_abertura_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('projeto_abertura_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('projeto_abertura_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('projeto_abertura_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('projeto_abertura_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('projeto_abertura_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('projeto_abertura_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('projeto_abertura_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('projeto_abertura_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('projeto_abertura_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('projeto_abertura_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('projeto_abertura_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('projeto_abertura_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('projeto_abertura_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('projeto_abertura_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('projeto_abertura_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('projeto_abertura_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('projeto_abertura_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('projeto_abertura_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('projeto_abertura_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('projeto_abertura_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('projeto_abertura_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('projeto_abertura_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('projeto_abertura_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('projeto_abertura_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_abertura_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('projeto_abertura_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('projeto_abertura_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('projeto_abertura_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('projeto_abertura_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('projeto_abertura_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('projeto_abertura_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('projeto_abertura_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('projeto_viabilidade_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
		}
	
	$sql->adOnde('projeto_abertura_projeto IS NULL');	
	$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
	$sql->setLimite($xmin, $xtamanhoPagina);
	$sql->adGrupo('projeto_abertura_id');
	}	
$listagem=$sql->Lista();
$sql->limpar();




$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Possível '.$config['projeto'], 'Possíveis '.$config['projetos'],'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';

if ($tab==0 || $a!='banco_projeto'){
	
	$podeEditar=$Aplic->checarModulo('projetos', 'editar', $Aplic->usuario_id, 'viabilidade');
	
	if ($exibir['projeto_viabilidade_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação do estudo de viabilidade.').'Cor'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação do possível projeto.').'Nome'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_aprovado'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_data']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_data&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_data' ? imagem('icones/'.$seta[$ordem]) : '').dica('Dara', 'Neste campo fica a data do estudo de viabilidade.').'Data'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_necessidade']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_necessidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_necessidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Necessidade', 'Neste campo fica a necessidade.').'Necessidade'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_alinhamento']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_alinhamento&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_alinhamento' ? imagem('icones/'.$seta[$ordem]) : '').dica('Alinhamento', 'Neste campo fica o alinhamento estratégico.').'Alinhamento'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_requisitos']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_requisitos&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_requisitos' ? imagem('icones/'.$seta[$ordem]) : '').dica('Requisitos Básicos', 'Neste campo fica os requisitos básicos.').'Requisitos'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_solucoes']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_solucoes&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_solucoes' ? imagem('icones/'.$seta[$ordem]) : '').dica('Soluções Possíveis', 'Neste campo fica as soluções possíveis.').'Soluções'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_viabilidade_tecnica']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_viabilidade_tecnica&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_viabilidade_tecnica' ? imagem('icones/'.$seta[$ordem]) : '').dica('Viabilidade Técnica', 'Neste campo fica a viabilidade técnica.').'Viabilidade Técnica'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_financeira']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_financeira&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_financeira' ? imagem('icones/'.$seta[$ordem]) : '').dica('Viabilidade Financeira', 'Neste campo fica a viabilidade financeira.').'Viabilidade Financeira'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_institucional']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_institucional&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_institucional' ? imagem('icones/'.$seta[$ordem]) : '').dica('Viabilidade Institucional', 'Neste campo fica a viabilidade institucional.').'Viabilidade Institucional'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_solucao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_solucao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_solucao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Indicação de Solução', 'Neste campo fica a indicação de solução.').'Indicação de Solução'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_continuidade']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_continuidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_continuidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Parecer Sobre a Continuidade', 'Neste campo fica o parecer sobre a continuidade.').'Parecer Sobre a Continuidade'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_tempo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_tempo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_tempo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Parecer Sobre o Tempo', 'Neste campo fica o parecer sobre o tempo.').'Parecer Sobre o Tempo'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_custo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_custo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_custo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Parecer Sobre o Custo', 'Neste campo fica o parecer sobre o custo.').'Parecer Sobre o Custo'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_observacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_observacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_observacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Observação', 'Neste campo fica a observação sobre o estudo de viabilidade.').'Observação'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_codigo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_codigo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_codigo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Código', 'Neste campo fica o código do estudo de viabilidade.').'Código'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_relacionado'] && $Aplic->profissional) echo '<th style="white-space: nowrap">'.dica('Relacionado', 'A que área este estudo de viabilidade está relacionado.').'Relacionado'.dicaF().'</th>';
	if ($exibir['projeto_viabilidade_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=projeto_viabilidade_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='projeto_viabilidade_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
	if ($exibir['projeto_viabilidade_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=projeto_viabilidade_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='projeto_viabilidade_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';
	if ($exibir['projeto_viabilidade_responsavel']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=projeto_viabilidade_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_viabilidade_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo estudo de viabilidade.').'Responsável'.dicaF().'</a></th>';
	if ($exibir['projeto_viabilidade_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para o estudo de viabilidade.').'Designados'.dicaF().'</th>';
	}
else{
	$podeEditar=$Aplic->checarModulo('projetos', 'editar', $Aplic->usuario_id, 'abertura');
	if ($exibir['projeto_abertura_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação do termo de abertura.').'Cor'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação do possível projeto.').'Nome'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_aprovado'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_data']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_data&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_data' ? imagem('icones/'.$seta[$ordem]) : '').dica('Dara', 'Neste campo fica a data do termo de abertura.').'Data'.dicaF().'</a></th>';
	
	if ($exibir['projeto_abertura_codigo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_codigo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_codigo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Código', 'Neste campo fica o código do termo de abertura.').'Código'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_justificativa']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_justificativa&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_justificativa' ? imagem('icones/'.$seta[$ordem]) : '').dica('Justificativa', 'Neste campo fica a justificativa do termo de abertura.').'Justificativa'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_objetivo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_objetivo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_objetivo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Objetivo', 'Neste campo fica o objetivo do termo de abertura.').'Objetivo'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_escopo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_escopo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_escopo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Declaração de Escopo', 'Neste campo fica a declaração de escopo do termo de abertura.').'D. Escopo'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_nao_escopo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_nao_escopo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_nao_escopo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Não Escopo', 'Neste campo fica o não escopo do termo de abertura.').'Não Escopo'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_tempo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_tempo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_tempo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tempo Estimado', 'Neste campo fica o tempo estimado do termo de abertura.').'Tempo Estimado'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_custo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_custo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_custo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Custo Estimado e Fonte de Recurso', 'Neste campo fica o custo estimado e fonte de recurso do termo de abertura.').'Custo'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_premissas']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_premissas&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_premissas' ? imagem('icones/'.$seta[$ordem]) : '').dica('Premissas', 'Neste campo ficam as premissas do termo de abertura.').'Premissas'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_restricoes']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_restricoes&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_restricoes' ? imagem('icones/'.$seta[$ordem]) : '').dica('Restrições', 'Neste campo ficam as restrições do termo de abertura.').'Restrições'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_riscos']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_riscos&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_riscos' ? imagem('icones/'.$seta[$ordem]) : '').dica('Riscos', 'Neste campo ficam os riscos do termo de abertura.').'Riscos'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_infraestrutura']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_infraestrutura&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_infraestrutura' ? imagem('icones/'.$seta[$ordem]) : '').dica('Infraestrutura', 'Neste campo fica a infraestrutura do termo de abertura.').'Infraestrutura'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_observacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_observacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_observacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Observações', 'Neste campo fica as observações do termo de abertura.').'Observações'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_descricao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('O Que', 'Neste campo fica o campo O Que do termo de abertura.').'O Que'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_objetivos']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_objetivos&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_objetivos' ? imagem('icones/'.$seta[$ordem]) : '').dica('Por Que', 'Neste campo fica o campo Por Que do termo de abertura.').'Por Que'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_como']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_como&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_como' ? imagem('icones/'.$seta[$ordem]) : '').dica('Como', 'Neste campo fica o campo Como do termo de abertura.').'Como'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_localizacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_localizacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_localizacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Onde', 'Neste campo fica o campo Onde do termo de abertura.').'Onde'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_beneficiario']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_beneficiario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_beneficiario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Benefícios', 'Neste campo fica os benefícios do termo de abertura.').'Benefícios'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_objetivo_especifico']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_objetivo_especifico&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_objetivo_especifico' ? imagem('icones/'.$seta[$ordem]) : '').dica('Objetivo Específico', 'Neste campo fica os objetivo específico do termo de abertura.').'Obj. Específico'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_orcamento']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_orcamento&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_orcamento' ? imagem('icones/'.$seta[$ordem]) : '').dica('Custos e Recurso', 'Neste campo fica o campo Custos e Recurso do termo de abertura.').'Custos e Recurso'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_beneficio']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_beneficio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_beneficio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Benefícios', 'Neste campo fica os benefícios do termo de abertura.').'Benefícios'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_produto']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_produto&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_produto' ? imagem('icones/'.$seta[$ordem]) : '').dica('Produtos', 'Neste campo fica os produtos do termo de abertura.').'Produtos'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_requisito']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_requisito&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_requisito' ? imagem('icones/'.$seta[$ordem]) : '').dica('Requisitos', 'Neste campo fica os requisitos do termo de abertura.').'Requisitos'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_aprovacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_aprovacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_aprovacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Justificativa da Aprovação', 'Neste campo fica a justificativa da aprovação do termo de abertura.').'Just. aprovação'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_recusa']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_recusa&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_recusa' ? imagem('icones/'.$seta[$ordem]) : '').dica('Justificativa da Reprovação', 'Neste campo fica a Justificativa da reprovação do termo de abertura.').'Just. reprovação'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_relacionado'] && $Aplic->profissional) echo '<th style="white-space: nowrap">'.dica('Relacionada', ' que área este demanda está relacionada.').'Relacionada'.dicaF().'</th>';
	if ($exibir['projeto_abertura_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=projeto_abertura_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='projeto_abertura_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
	if ($exibir['projeto_abertura_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
	if ($exibir['projeto_abertura_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=projeto_abertura_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='projeto_abertura_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
	if ($exibir['projeto_abertura_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';
	if ($exibir['projeto_abertura_responsavel']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=projeto_abertura_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='projeto_abertura_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo termo de abertura.').'Responsável'.dicaF().'</a></th>';
	if ($exibir['projeto_abertura_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para o termo de abertura.').'Designados'.dicaF().'</th>';
	}	
	
echo '</tr>';

$qnt_itens=0;
foreach ($listagem as $linha) {
	$qnt_itens++;
	if ($tab==0 || $a!='banco_projeto'){
		if (permiteAcessarViabilidade($linha['projeto_viabilidade_acesso'],$linha['projeto_viabilidade_id'])){
			echo '<tr>';	
			$editar=permiteEditarViabilidade($linha['projeto_viabilidade_acesso'],$linha['projeto_viabilidade_id']);
				
				if ($Aplic->profissional) $bloquear=($linha['projeto_viabilidade_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
				else $bloquear=0;
				
				if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.($editar && $podeEditar && !$bloquear ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a viabilidade.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=viabilidade_editar&projeto_viabilidade_id='.$linha['projeto_viabilidade_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_viabilidade_cor'].'"><font color="'.melhorCor($linha['projeto_viabilidade_cor']).'">&nbsp;&nbsp;</font></td>';
				echo '<td>'.link_viabilidade($linha['projeto_viabilidade_id']).'</td>';
				
				if ($exibir['projeto_viabilidade_aprovado'] && $Aplic->profissional) echo '<td width=30 align="center">'.($linha['projeto_viabilidade_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
				
				if ($exibir['projeto_viabilidade_data']) echo '<td>'.($linha['projeto_viabilidade_data'] ? $linha['data']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_necessidade']) echo '<td>'.($linha['projeto_viabilidade_necessidade'] ? $linha['projeto_viabilidade_necessidade']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_alinhamento']) echo '<td>'.($linha['projeto_viabilidade_alinhamento'] ? $linha['projeto_viabilidade_alinhamento']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_requisitos']) echo '<td>'.($linha['projeto_viabilidade_requisitos'] ? $linha['projeto_viabilidade_requisitos']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_solucoes']) echo '<td>'.($linha['projeto_viabilidade_solucoes'] ? $linha['projeto_viabilidade_solucoes']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_viabilidade_tecnica']) echo '<td>'.($linha['projeto_viabilidade_viabilidade_tecnica'] ? $linha['projeto_viabilidade_viabilidade_tecnica']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_financeira']) echo '<td>'.($linha['projeto_viabilidade_financeira'] ? $linha['projeto_viabilidade_financeira']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_institucional']) echo '<td>'.($linha['projeto_viabilidade_institucional'] ? $linha['projeto_viabilidade_institucional']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_solucao']) echo '<td>'.($linha['projeto_viabilidade_solucao'] ? $linha['projeto_viabilidade_solucao']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_continuidade']) echo '<td>'.($linha['projeto_viabilidade_continuidade'] ? $linha['projeto_viabilidade_continuidade']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_tempo']) echo '<td>'.($linha['projeto_viabilidade_tempo'] ? $linha['projeto_viabilidade_tempo']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_custo']) echo '<td>'.($linha['projeto_viabilidade_custo'] ? $linha['projeto_viabilidade_custo']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_observacao']) echo '<td>'.($linha['projeto_viabilidade_observacao'] ? $linha['projeto_viabilidade_observacao']: '&nbsp;').'</td>';
				if ($exibir['projeto_viabilidade_codigo']) echo '<td>'.($linha['projeto_viabilidade_codigo'] ? $linha['projeto_viabilidade_codigo']: '&nbsp;').'</td>';
				
				if ($exibir['projeto_viabilidade_relacionado'] && $Aplic->profissional){
					$sql->adTabela('projeto_viabilidade_gestao');
					$sql->adCampo('projeto_viabilidade_gestao.*');
					$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade ='.(int)$linha['projeto_viabilidade_id']);	
					$sql->adOrdem('projeto_viabilidade_gestao_ordem');
					$lista2 = $sql->Lista();
					$sql->limpar();
					$qnt_gestao=0;
					echo '<td>';	
					if (count($lista2)) {
						foreach($lista2 as $gestao_data){
							if ($gestao_data['projeto_viabilidade_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_viabilidade_gestao_tarefa']);
							elseif ($gestao_data['projeto_viabilidade_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_viabilidade_gestao_projeto']);
							elseif ($gestao_data['projeto_viabilidade_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_viabilidade_gestao_pratica']);
							elseif ($gestao_data['projeto_viabilidade_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_viabilidade_gestao_acao']);
							elseif ($gestao_data['projeto_viabilidade_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_viabilidade_gestao_perspectiva']);
							elseif ($gestao_data['projeto_viabilidade_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['projeto_viabilidade_gestao_tema']);
							elseif ($gestao_data['projeto_viabilidade_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_viabilidade_gestao_objetivo']);
							elseif ($gestao_data['projeto_viabilidade_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_viabilidade_gestao_fator']);
							elseif ($gestao_data['projeto_viabilidade_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_viabilidade_gestao_estrategia']);
							elseif ($gestao_data['projeto_viabilidade_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_viabilidade_gestao_meta']);
							elseif ($gestao_data['projeto_viabilidade_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_viabilidade_gestao_canvas']);
							elseif ($gestao_data['projeto_viabilidade_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['projeto_viabilidade_gestao_risco']);
							elseif ($gestao_data['projeto_viabilidade_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_viabilidade_gestao_risco_resposta']);
							elseif ($gestao_data['projeto_viabilidade_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_viabilidade_gestao_indicador']);
							elseif ($gestao_data['projeto_viabilidade_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_viabilidade_gestao_calendario']);
							elseif ($gestao_data['projeto_viabilidade_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_viabilidade_gestao_monitoramento']);
							elseif ($gestao_data['projeto_viabilidade_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_viabilidade_gestao_ata']);
							elseif ($gestao_data['projeto_viabilidade_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_viabilidade_gestao_mswot']);
							elseif ($gestao_data['projeto_viabilidade_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['projeto_viabilidade_gestao_swot']);
							elseif ($gestao_data['projeto_viabilidade_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_viabilidade_gestao_operativo']);
							elseif ($gestao_data['projeto_viabilidade_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_viabilidade_gestao_instrumento']);
							elseif ($gestao_data['projeto_viabilidade_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_viabilidade_gestao_recurso']);
							elseif ($gestao_data['projeto_viabilidade_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['projeto_viabilidade_gestao_problema']);
							elseif ($gestao_data['projeto_viabilidade_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_viabilidade_gestao_demanda']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['projeto_viabilidade_gestao_programa']);
							elseif ($gestao_data['projeto_viabilidade_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_viabilidade_gestao_licao']);
							elseif ($gestao_data['projeto_viabilidade_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_viabilidade_gestao_evento']);
							elseif ($gestao_data['projeto_viabilidade_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['projeto_viabilidade_gestao_link']);
							elseif ($gestao_data['projeto_viabilidade_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_viabilidade_gestao_avaliacao']);
							elseif ($gestao_data['projeto_viabilidade_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_viabilidade_gestao_tgn']);
							elseif ($gestao_data['projeto_viabilidade_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_viabilidade_gestao_brainstorm']);
							elseif ($gestao_data['projeto_viabilidade_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_viabilidade_gestao_gut']);
							elseif ($gestao_data['projeto_viabilidade_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_viabilidade_gestao_causa_efeito']);
							elseif ($gestao_data['projeto_viabilidade_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_viabilidade_gestao_arquivo']);
							elseif ($gestao_data['projeto_viabilidade_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_viabilidade_gestao_forum']);
							elseif ($gestao_data['projeto_viabilidade_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_viabilidade_gestao_checklist']);
							elseif ($gestao_data['projeto_viabilidade_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_viabilidade_gestao_agenda']);
							elseif ($gestao_data['projeto_viabilidade_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_viabilidade_gestao_agrupamento']);
							elseif ($gestao_data['projeto_viabilidade_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_viabilidade_gestao_patrocinador']);
							elseif ($gestao_data['projeto_viabilidade_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['projeto_viabilidade_gestao_template']);
							elseif ($gestao_data['projeto_viabilidade_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['projeto_viabilidade_gestao_painel']);
							elseif ($gestao_data['projeto_viabilidade_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_viabilidade_gestao_painel_odometro']);
							elseif ($gestao_data['projeto_viabilidade_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_viabilidade_gestao_painel_composicao']);		
							elseif ($gestao_data['projeto_viabilidade_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['projeto_viabilidade_gestao_tr']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['projeto_viabilidade_gestao_me']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_viabilidade_gestao_acao_item']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_viabilidade_gestao_beneficio']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_viabilidade_gestao_painel_slideshow']);	
							
							elseif ($gestao_data['projeto_viabilidade_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_viabilidade_gestao_semelhante']);	
							
							elseif ($gestao_data['projeto_viabilidade_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_viabilidade_gestao_projeto_abertura']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_viabilidade_gestao_plano_gestao']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_viabilidade_gestao_ssti']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_viabilidade_gestao_laudo']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_viabilidade_gestao_trelo']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_viabilidade_gestao_trelo_cartao']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_viabilidade_gestao_pdcl']);	
							elseif ($gestao_data['projeto_viabilidade_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_viabilidade_gestao_pdcl_item']);	
							}
						}	
					echo '</td>';	
					}
	
				if ($exibir['projeto_viabilidade_cia']) echo '<td>'.link_cia($linha['projeto_viabilidade_cia']).'</td>';
			
				if ($exibir['projeto_viabilidade_cias']){
					$sql->adTabela('projeto_viabilidade_cia');
					$sql->adCampo('projeto_viabilidade_cia_cia');
					$sql->adOnde('projeto_viabilidade_cia_projeto_viabilidade = '.(int)$linha['projeto_viabilidade_id']);
					$cias = $sql->carregarColuna();
					$sql->limpar();
					$saida_cias='';
					if (isset($cias) && count($cias)) {
						$plural=(count($cias)>1 ? 's' : '');
						$saida_cias.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
						$saida_cias.= '<tr><td style="border:0px;">'.link_cia($cias[0]);
						$qnt_cias=count($cias);
						if ($qnt_cias > 1) {
							$lista='';
							for ($j = 1, $i_cmp = $qnt_cias; $j < $i_cmp; $j++) $lista.=link_cia($cias[$j]).'<br>';
							$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['projeto_viabilidade_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['projeto_viabilidade_id'].'"><br>'.$lista.'</span>';
							}
						$saida_cias.= '</td></tr></table>';
						$plural=(count($cias)>1 ? 's' : '');
						}
					echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
					}
					
				if ($exibir['projeto_viabilidade_dept']) echo '<td>'.link_secao($linha['projeto_viabilidade_dept']).'</td>';	
				
				if ($exibir['projeto_viabilidade_depts']){
					$sql->adTabela('projeto_viabilidade_dept');
					$sql->adCampo('projeto_viabilidade_dept_dept');
					$sql->adOnde('projeto_viabilidade_dept_projeto_viabilidade = '.(int)$linha['projeto_viabilidade_id']);
					$depts = $sql->carregarColuna();
					$sql->limpar();
					$saida_depts='';
					if (isset($depts) && count($depts)) {
						$plural=(count($depts)>1 ? 's' : '');
						$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
						$saida_depts.= '<tr><td style="border:0px;">'.link_secao($depts[0]);
						$qnt_depts=count($depts);
						if ($qnt_depts > 1) {
							$lista='';
							for ($j = 1, $i_cmp = $qnt_depts; $j < $i_cmp; $j++) $lista.=link_secao($depts[$j]).'<br>';
							$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['projeto_viabilidade_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['projeto_viabilidade_id'].'"><br>'.$lista.'</span>';
							}
						$saida_depts.= '</td></tr></table>';
						$plural=(count($depts)>1 ? 's' : '');
						}
					echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
					}
			
				if ($exibir['projeto_viabilidade_responsavel']) echo '<td>'.link_usuario($linha['projeto_viabilidade_responsavel'],'','','esquerda').'</td>';
				
				if ($exibir['projeto_viabilidade_designados']) {
					$sql->adTabela('projeto_viabilidade_usuarios');
					$sql->adCampo('usuario_id');
					$sql->adOnde('projeto_viabilidade_id = '.(int)$linha['projeto_viabilidade_id']);
					$participantes = $sql->carregarColuna();
					$sql->limpar();
					
					$saida_quem='';
					if ($participantes && count($participantes)) {
							$saida_quem.= link_usuario($participantes[0], '','','esquerda');
							$qnt_participantes=count($participantes);
							if ($qnt_participantes > 1) {		
									$lista='';
									for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
									$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['projeto_viabilidade_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['projeto_viabilidade_id'].'"><br>'.$lista.'</span>';
									}
							} 
					echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
					}
			echo '</tr>';	
			}
		}
	else{
		if (permiteAcessarTermoAbertura($linha['projeto_abertura_acesso'],$linha['projeto_abertura_id'])){
			echo '<tr>';	
			$editar=permiteEditarTermoAbertura($linha['projeto_abertura_acesso'],$linha['projeto_abertura_id']);
			if ($Aplic->profissional) $bloquear=($linha['projeto_abertura_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
			else $bloquear=0;
			if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.($editar && $podeEditar && !$bloquear ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o termo de abertura.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=termo_abertura_editar&projeto_abertura_id='.$linha['projeto_abertura_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_abertura_cor'].'"><font color="'.melhorCor($linha['projeto_abertura_cor']).'">&nbsp;&nbsp;</font></td>';
			echo '<td>'.link_termo_abertura($linha['projeto_abertura_id']).'</td>';
			if ($exibir['projeto_abertura_aprovado'] && $Aplic->profissional) echo '<td width=30 align="center">'.($linha['projeto_abertura_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
			if ($exibir['projeto_abertura_data']) echo '<td>'.($linha['projeto_abertura_data'] ? $linha['data']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_codigo']) echo '<td>'.($linha['projeto_abertura_codigo'] ? $linha['projeto_abertura_codigo']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_justificativa']) echo '<td>'.($linha['projeto_abertura_justificativa'] ? $linha['projeto_abertura_justificativa']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_objetivo']) echo '<td>'.($linha['projeto_abertura_objetivo'] ? $linha['projeto_abertura_objetivo']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_escopo']) echo '<td>'.($linha['projeto_abertura_escopo'] ? $linha['projeto_abertura_escopo']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_nao_escopo']) echo '<td>'.($linha['projeto_abertura_nao_escopo'] ? $linha['projeto_abertura_nao_escopo']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_tempo']) echo '<td>'.($linha['projeto_abertura_tempo'] ? $linha['projeto_abertura_tempo']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_custo']) echo '<td>'.($linha['projeto_abertura_custo'] ? $linha['projeto_abertura_custo']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_premissas']) echo '<td>'.($linha['projeto_abertura_premissas'] ? $linha['projeto_abertura_premissas']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_restricoes']) echo '<td>'.($linha['projeto_abertura_restricoes'] ? $linha['projeto_abertura_restricoes']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_riscos']) echo '<td>'.($linha['projeto_abertura_riscos'] ? $linha['projeto_abertura_riscos']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_infraestrutura']) echo '<td>'.($linha['projeto_abertura_infraestrutura'] ? $linha['projeto_abertura_infraestrutura']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_observacao']) echo '<td>'.($linha['projeto_abertura_observacao'] ? $linha['projeto_abertura_observacao']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_descricao']) echo '<td>'.($linha['projeto_abertura_descricao'] ? $linha['projeto_abertura_descricao']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_objetivos']) echo '<td>'.($linha['projeto_abertura_objetivos'] ? $linha['projeto_abertura_objetivos']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_como']) echo '<td>'.($linha['projeto_abertura_como'] ? $linha['projeto_abertura_como']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_localizacao']) echo '<td>'.($linha['projeto_abertura_localizacao'] ? $linha['projeto_abertura_localizacao']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_beneficiario']) echo '<td>'.($linha['projeto_abertura_beneficiario'] ? $linha['projeto_abertura_beneficiario']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_objetivo_especifico']) echo '<td>'.($linha['projeto_abertura_objetivo_especifico'] ? $linha['projeto_abertura_objetivo_especifico']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_orcamento']) echo '<td>'.($linha['projeto_abertura_orcamento'] ? $linha['projeto_abertura_orcamento']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_beneficio']) echo '<td>'.($linha['projeto_abertura_beneficio'] ? $linha['projeto_abertura_beneficio']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_produto']) echo '<td>'.($linha['projeto_abertura_produto'] ? $linha['projeto_abertura_produto']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_requisito']) echo '<td>'.($linha['projeto_abertura_requisito'] ? $linha['projeto_abertura_requisito']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_aprovacao']) echo '<td>'.($linha['projeto_abertura_aprovacao'] ? $linha['projeto_abertura_aprovacao']: '&nbsp;').'</td>';
			if ($exibir['projeto_abertura_recusa']) echo '<td>'.($linha['projeto_abertura_recusa'] ? $linha['projeto_abertura_recusa']: '&nbsp;').'</td>';
			
			if ($exibir['projeto_abertura_relacionado'] && $Aplic->profissional){
				$sql->adTabela('projeto_abertura_gestao');
				$sql->adCampo('projeto_abertura_gestao.*');
				$sql->adOnde('projeto_abertura_gestao_projeto_abertura ='.(int)$linha['projeto_abertura_id']);	
				$sql->adOrdem('projeto_abertura_gestao_ordem');
				$lista = $sql->Lista();
				$sql->limpar();
				$qnt_gestao=0;
				echo '<td>';	
				if (count($lista)) {
					foreach($lista as $gestao_data){
						if ($gestao_data['projeto_abertura_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_abertura_gestao_tarefa']);
						elseif ($gestao_data['projeto_abertura_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_abertura_gestao_projeto']);
						elseif ($gestao_data['projeto_abertura_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_abertura_gestao_pratica']);
						elseif ($gestao_data['projeto_abertura_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_abertura_gestao_acao']);
						elseif ($gestao_data['projeto_abertura_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_abertura_gestao_perspectiva']);
						elseif ($gestao_data['projeto_abertura_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['projeto_abertura_gestao_tema']);
						elseif ($gestao_data['projeto_abertura_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_abertura_gestao_objetivo']);
						elseif ($gestao_data['projeto_abertura_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_abertura_gestao_fator']);
						elseif ($gestao_data['projeto_abertura_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_abertura_gestao_estrategia']);
						elseif ($gestao_data['projeto_abertura_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_abertura_gestao_meta']);
						elseif ($gestao_data['projeto_abertura_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_abertura_gestao_canvas']);
						elseif ($gestao_data['projeto_abertura_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['projeto_abertura_gestao_risco']);
						elseif ($gestao_data['projeto_abertura_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_abertura_gestao_risco_resposta']);
						elseif ($gestao_data['projeto_abertura_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_abertura_gestao_indicador']);
						elseif ($gestao_data['projeto_abertura_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_abertura_gestao_calendario']);
						elseif ($gestao_data['projeto_abertura_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_abertura_gestao_monitoramento']);
						elseif ($gestao_data['projeto_abertura_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_abertura_gestao_ata']);
						elseif ($gestao_data['projeto_abertura_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_abertura_gestao_mswot']);
						elseif ($gestao_data['projeto_abertura_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['projeto_abertura_gestao_swot']);
						elseif ($gestao_data['projeto_abertura_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_abertura_gestao_operativo']);
						elseif ($gestao_data['projeto_abertura_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_abertura_gestao_instrumento']);
						elseif ($gestao_data['projeto_abertura_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_abertura_gestao_recurso']);
						elseif ($gestao_data['projeto_abertura_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['projeto_abertura_gestao_problema']);
						elseif ($gestao_data['projeto_abertura_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_abertura_gestao_demanda']);	
						elseif ($gestao_data['projeto_abertura_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['projeto_abertura_gestao_programa']);
						elseif ($gestao_data['projeto_abertura_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_abertura_gestao_licao']);
						elseif ($gestao_data['projeto_abertura_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_abertura_gestao_evento']);
						elseif ($gestao_data['projeto_abertura_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['projeto_abertura_gestao_link']);
						elseif ($gestao_data['projeto_abertura_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_abertura_gestao_avaliacao']);
						elseif ($gestao_data['projeto_abertura_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_abertura_gestao_tgn']);
						elseif ($gestao_data['projeto_abertura_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_abertura_gestao_brainstorm']);
						elseif ($gestao_data['projeto_abertura_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_abertura_gestao_gut']);
						elseif ($gestao_data['projeto_abertura_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_abertura_gestao_causa_efeito']);
						elseif ($gestao_data['projeto_abertura_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_abertura_gestao_arquivo']);
						elseif ($gestao_data['projeto_abertura_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_abertura_gestao_forum']);
						elseif ($gestao_data['projeto_abertura_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_abertura_gestao_checklist']);
						elseif ($gestao_data['projeto_abertura_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_abertura_gestao_agenda']);
						elseif ($gestao_data['projeto_abertura_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_abertura_gestao_agrupamento']);
						elseif ($gestao_data['projeto_abertura_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_abertura_gestao_patrocinador']);
						elseif ($gestao_data['projeto_abertura_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['projeto_abertura_gestao_template']);
						elseif ($gestao_data['projeto_abertura_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['projeto_abertura_gestao_painel']);
						elseif ($gestao_data['projeto_abertura_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_abertura_gestao_painel_odometro']);
						elseif ($gestao_data['projeto_abertura_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_abertura_gestao_painel_composicao']);		
						elseif ($gestao_data['projeto_abertura_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['projeto_abertura_gestao_tr']);	
						elseif ($gestao_data['projeto_abertura_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['projeto_abertura_gestao_me']);	
						elseif ($gestao_data['projeto_abertura_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_abertura_gestao_acao_item']);	
						elseif ($gestao_data['projeto_abertura_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_abertura_gestao_beneficio']);	
						elseif ($gestao_data['projeto_abertura_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_abertura_gestao_painel_slideshow']);	
						elseif ($gestao_data['projeto_abertura_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_abertura_gestao_projeto_viabilidade']);	
						
						elseif ($gestao_data['projeto_abertura_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_abertura_gestao_semelhante']);	
						
						elseif ($gestao_data['projeto_abertura_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_abertura_gestao_plano_gestao']);	
						elseif ($gestao_data['projeto_abertura_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_abertura_gestao_ssti']);	
						elseif ($gestao_data['projeto_abertura_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_abertura_gestao_laudo']);	
						elseif ($gestao_data['projeto_abertura_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_abertura_gestao_trelo']);	
						elseif ($gestao_data['projeto_abertura_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_abertura_gestao_trelo_cartao']);	
						elseif ($gestao_data['projeto_abertura_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_abertura_gestao_pdcl']);	
						elseif ($gestao_data['projeto_abertura_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_abertura_gestao_pdcl_item']);	
						}
					}	
				echo '</td>';	
				}	
			
			
			if ($exibir['projeto_abertura_cia']) echo '<td>'.link_cia($linha['projeto_abertura_cia']).'</td>';
			if ($exibir['projeto_abertura_cias']){
				$sql->adTabela('projeto_abertura_cia');
				$sql->adCampo('projeto_abertura_cia_cia');
				$sql->adOnde('projeto_abertura_cia_projeto_abertura = '.(int)$linha['projeto_abertura_id']);
				$cias = $sql->carregarColuna();
				$sql->limpar();
				$saida_cias='';
				if (isset($cias) && count($cias)) {
					$plural=(count($cias)>1 ? 's' : '');
					$saida_cias.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
					$saida_cias.= '<tr><td style="border:0px;">'.link_cia($cias[0]);
					$qnt_cias=count($cias);
					if ($qnt_cias > 1) {
						$lista='';
						for ($j = 1, $i_cmp = $qnt_cias; $j < $i_cmp; $j++) $lista.=link_cia($cias[$j]).'<br>';
						$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['projeto_abertura_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['projeto_abertura_id'].'"><br>'.$lista.'</span>';
						}
					$saida_cias.= '</td></tr></table>';
					$plural=(count($cias)>1 ? 's' : '');
					}
				echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
				}
			if ($exibir['projeto_abertura_dept']) echo '<td>'.link_secao($linha['projeto_abertura_dept']).'</td>';	
			if ($exibir['projeto_abertura_depts']){
				$sql->adTabela('projeto_abertura_dept');
				$sql->adCampo('projeto_abertura_dept_dept');
				$sql->adOnde('projeto_abertura_dept_projeto_abertura = '.(int)$linha['projeto_abertura_id']);
				$depts = $sql->carregarColuna();
				$sql->limpar();
				$saida_depts='';
				if (isset($depts) && count($depts)) {
					$plural=(count($depts)>1 ? 's' : '');
					$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
					$saida_depts.= '<tr><td style="border:0px;">'.link_secao($depts[0]);
					$qnt_depts=count($depts);
					if ($qnt_depts > 1) {
						$lista='';
						for ($j = 1, $i_cmp = $qnt_depts; $j < $i_cmp; $j++) $lista.=link_secao($depts[$j]).'<br>';
						$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['projeto_abertura_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['projeto_abertura_id'].'"><br>'.$lista.'</span>';
						}
					$saida_depts.= '</td></tr></table>';
					$plural=(count($depts)>1 ? 's' : '');
					}
				echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
				}
			if ($exibir['projeto_abertura_responsavel']) echo '<td>'.link_usuario($linha['projeto_abertura_responsavel'],'','','esquerda').'</td>';
			if ($exibir['projeto_abertura_designados']) {
				$sql->adTabela('projeto_abertura_usuarios');
				$sql->adCampo('usuario_id');
				$sql->adOnde('projeto_abertura_id = '.(int)$linha['projeto_abertura_id']);
				$participantes = $sql->carregarColuna();
				$sql->limpar();
				$saida_quem='';
				if ($participantes && count($participantes)) {
						$saida_quem.= link_usuario($participantes[0], '','','esquerda');
						$qnt_participantes=count($participantes);
						if ($qnt_participantes > 1) {		
								$lista='';
								for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
								$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['projeto_abertura_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['projeto_abertura_id'].'"><br>'.$lista.'</span>';
								}
						} 
				echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
				}
			echo '</tr>';	
			}
		}	
	}
	
if (!count($listagem)) echo '<tr><td colspan=20><p>Nenhum possível '.$config['projeto'].($tab ? ' com minuta do termo de abertura' : '').'</p></td></tr>';
echo '</table>';
?>
<script type="text/javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>		