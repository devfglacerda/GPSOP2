<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $selecao, $chamarVolta, $selecionado, $edicao, $Aplic, $dialogo, $usuario_id, $plano_acao_item_pesquisa, $cia_id, $lista_cias, $dept_id, $lista_depts, $tab, $estilo_interface, $acao_status,
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

$vetor_selecionado=explode(',', $selecionado);
$selecionado=array();
foreach($vetor_selecionado as $valor) $selecionado[$valor]=$valor;

$permite_editar=$Aplic->checarModulo('praticas', 'editar', null, 'plano_acao');

$ordenar=getParam($_REQUEST, 'ordenar', 'plano_acao_item_ordem');
$ordem=getParam($_REQUEST, 'ordem', '0');
$pagina=getParam($_REQUEST, 'pagina', 1);	
$percentual=getSisValor('TarefaPorcentagem','','','sisvalor_id');
$corItemPlano = getSisValor('StatusAcaoPlanoCor');
$statusItemPlano=array(''=>'')+getSisValor('StatusAcaoPlano');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$xpg_tamanhoPagina = ($dialogo ? 90000 : 30);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 
$xpg_tamanhoPagina = $config['qnt_plano_acao'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);	

if ($lista_depts) $lista_depts=(is_array($lista_depts) ? implode(',', $lista_depts) : $lista_depts);
if ($lista_cias) $lista_cias=(is_array($lista_cias) ? implode(',', $lista_cias) : $lista_cias);

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'acao\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


$sql->adTabela('plano_acao_item');
$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_item_acao');
$sql->esqUnir('plano_acao_item_usuario', 'plano_acao_item_usuario', 'plano_acao_item_usuario_item = plano_acao_item.plano_acao_item_id');
if ($usuario_id) $sql->adOnde('plano_acao_item_responsavel IN ('.$usuario_id.') OR plano_acao_item_usuario_usuario IN ('.$usuario_id.')');
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('plano_acao_dept','plano_acao_dept', 'plano_acao_dept_acao=plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_dept='.(int)$dept_id.' OR plano_acao_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('plano_acao_dept','plano_acao_dept', 'plano_acao_dept_acao=plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_dept IN ('.$lista_depts.') OR plano_acao_dept_dept IN ('.$lista_depts.')');
	}
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('plano_acao_cia', 'plano_acao_cia', 'plano_acao.plano_acao_id=plano_acao_cia_plano_acao');
	$sql->adOnde('plano_acao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_acao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('plano_acao_cia IN ('.$lista_cias.')');

if ($plano_acao_item_pesquisa) $sql->adOnde('plano_acao_nome LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_nome LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_quando LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_oque LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_como LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_onde LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_quanto LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_porque LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_quem LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_observacao LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_descricao LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_codigo LIKE \'%'.$plano_acao_item_pesquisa.'%\'');
	

$sql->esqUnir('plano_acao_gestao','plano_acao_gestao','plano_acao_gestao_acao = plano_acao.plano_acao_id');
if ($tarefa_id) $sql->adOnde('plano_acao_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas', 'tarefa_id=plano_acao_gestao_tarefa');
	$sql->adOnde('plano_acao_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
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
elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr IN ('.$tr_id.')');
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

	
	
	
$sql->adCampo('count(DISTINCT plano_acao_item.plano_acao_item_id)');

if ($a=='plano_acao_itens_lista'){
	if ($tab==0) $sql->adOnde('plano_acao_item_percentagem < 100');
	if ($tab==1) $sql->adOnde('plano_acao_item_percentagem = 100');
	if ($tab==2) $sql->adOnde('plano_acao_ativo = 0');
	else $sql->adOnde('plano_acao_ativo = 1');
	}
elseif ($a=='parafazer'){
	$sql->adOnde('plano_acao_item_percentagem < 100');
	$sql->adOnde('plano_acao_ativo = 1');
	}	
else $sql->adOnde('plano_acao_ativo = 1');


if ($acao_status!='') $sql->adOnde('plano_acao_item_status='.(int)$acao_status);	

$xpg_totalregistros= $sql->Resultado();
$sql->limpar();	

$sql->adTabela('plano_acao_item');
$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_item_acao');
$sql->esqUnir('plano_acao_item_usuario', 'plano_acao_item_usuario', 'plano_acao_item_usuario_item = plano_acao_item.plano_acao_item_id');
if ($usuario_id) $sql->adOnde('plano_acao_item_responsavel IN ('.$usuario_id.') OR plano_acao_item_usuario_usuario IN ('.$usuario_id.')');
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('plano_acao_dept','plano_acao_dept', 'plano_acao_dept_acao=plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_dept='.(int)$dept_id.' OR plano_acao_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('plano_acao_dept','plano_acao_dept', 'plano_acao_dept_acao=plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_dept IN ('.$lista_depts.') OR plano_acao_dept_dept IN ('.$lista_depts.')');
	}
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('plano_acao_cia', 'plano_acao_cia', 'plano_acao.plano_acao_id=plano_acao_cia_plano_acao');
	$sql->adOnde('plano_acao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_acao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('plano_acao_cia IN ('.$lista_cias.')');
	
if ($plano_acao_item_pesquisa) $sql->adOnde('plano_acao_nome LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_nome LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_quando LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_oque LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_como LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_onde LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_quanto LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_porque LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_quem LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_item_observacao LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_descricao LIKE \'%'.$plano_acao_item_pesquisa.'%\' OR plano_acao_codigo LIKE \'%'.$plano_acao_item_pesquisa.'%\'');
	
	

$sql->esqUnir('plano_acao_gestao','plano_acao_gestao','plano_acao_gestao_acao = plano_acao.plano_acao_id');
if ($tarefa_id) $sql->adOnde('plano_acao_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas', 'tarefa_id=plano_acao_gestao_tarefa');
	$sql->adOnde('plano_acao_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
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
elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr IN ('.$tr_id.')');
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

$sql->adCampo('plano_acao_acesso');	
$sql->adCampo('plano_acao_item.plano_acao_item_id, plano_acao_item_nome, plano_acao_item_oque, plano_acao_item_porque, plano_acao_item_onde, plano_acao_item_inicio, plano_acao_item_fim, plano_acao_item_quando, plano_acao_item_quem, plano_acao_item_quanto, plano_acao_item_como, plano_acao_item_peso, plano_acao_item_percentagem, plano_acao_id, plano_acao_item_responsavel, plano_acao_item_status, plano_acao_item_observacao');
$sql->adCampo('(CASE
			WHEN plano_acao_item_percentagem=100 THEN "#aaddaa"
			WHEN plano_acao_item_inicio > NOW() OR plano_acao_item_inicio IS NULL OR plano_acao_item_fim IS NULL THEN "#ffffff"
			WHEN plano_acao_item_fim < NOW() AND plano_acao_item_percentagem<100 THEN "#cc6666"
			WHEN plano_acao_item_fim > NOW() AND plano_acao_item_inicio< NOW() AND plano_acao_item_percentagem > 0 THEN "#e6eedd"
			WHEN 1>0 THEN "#ffeebb"
			END) AS acao_situacao');
if ($a=='plano_acao_itens_lista'){
	if ($tab==0) $sql->adOnde('plano_acao_item_percentagem < 100');
	if ($tab==1) $sql->adOnde('plano_acao_item_percentagem = 100');
	if ($tab==2) $sql->adOnde('plano_acao_ativo = 0');
	else $sql->adOnde('plano_acao_ativo = 1');
	}
elseif ($a=='parafazer'){
	$sql->adOnde('plano_acao_item_percentagem < 100');
	$sql->adOnde('plano_acao_ativo = 1');
	}	
else $sql->adOnde('plano_acao_ativo = 1');
	
if ($acao_status!='') $sql->adOnde('plano_acao_item_status='.(int)$acao_status);		
	
$sql->adOrdem($ordenar.($ordem? ' DESC' : ' ASC'));
$sql->adGrupo('plano_acao_item.plano_acao_item_id');	
$sql->setLimite($xpg_min, $xpg_tamanhoPagina);


$plano_acao_item = $sql->Lista();
$sql->limpar();	
	
$qnt_com_tempo=0;	

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'plano_acao');


$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1 && !$dialogo) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'Item de '.ucfirst($config['acao']), 'Itens de '.ucfirst($config['acoes']),'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table cellpadding=0 cellspacing=0 class="tbl1" align=center width=100%>';
if (count($plano_acao_item)){
	echo '<tr>';
	if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome','O nome do item d'.$config['genero_acao'].' '.$config['acao'].'.').'Nome'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_ordem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_ordem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Número','O número da ação.').'Nr'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_oque&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_oque' ? imagem('icones/'.$seta[$ordem]) : '').dica('O Que','O que será feito.').'O Que'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_porque&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_porque' ? imagem('icones/'.$seta[$ordem]) : '').dica('Por que','Por que será feito.').'Por que'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_onde&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_onde' ? imagem('icones/'.$seta[$ordem]) : '').dica('Onde','Onde será feito.').'Onde'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_quando&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_quando' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quando','Quando será feito.').'Quando'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_quem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_quem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quem','Por quem será feito.').'Quem'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_como&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_como' ? imagem('icones/'.$seta[$ordem]) : '').dica('Como','Como será feito.').'Como'.dicaF().'</a></th>';
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_quanto&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_quanto' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quanto','Quanto custará fazer').'Quanto'.dicaF().'</a></th>';
	if ($Aplic->profissional && $exibir['porcentagem_item']) {
		echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_peso&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_peso' ? imagem('icones/'.$seta[$ordem]) : '').dica('Peso', 'Neste campo fica o peso da ação.').'P'.dicaF().'</a></th>';
		echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'Neste campo fica a percentagem executada da ação.').'%'.dicaF().'</a></th>';
		echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_status&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_status' ? imagem('icones/'.$seta[$ordem]) : '').dica('Status', 'Neste campo fica o status da ação.').'Status'.dicaF().'</a></th>';
		echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=plano_acao_item_observacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_item_observacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Observação', 'Neste campo fica a observação da ação.').'Observação'.dicaF().'</a></th>';
		}
	echo '</tr>';
	$qnt_acao=0;
	$vetor_plano=array();
	foreach($plano_acao_item as $linha) {
		if (permiteAcessarPlanoAcaoItem($linha['plano_acao_acesso'], $linha['plano_acao_item_id'])){
			$editar=permiteEditarPlanoAcaoItem($linha['plano_acao_acesso'], $linha['plano_acao_item_id']);
			if ($edicao && !$editar) continue;
			$qnt_acao++;
			if (!isset($vetor_plano[$linha['plano_acao_item_id']])) $vetor_plano[$linha['plano_acao_item_id']]=link_acao_item($linha['plano_acao_item_id']);
			if ($linha['plano_acao_item_inicio'] && $linha['plano_acao_item_fim']) $qnt_com_tempo++;
			echo '<tr>';
			
			if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['plano_acao_item_id'].'" value="'.$linha['plano_acao_item_id'].'"  onclick="selecionar(this.value)" />';
			if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['plano_acao_item_id'].'" value="'.$linha['plano_acao_item_id'].'" '.(isset($selecionado[$linha['plano_acao_item_id']]) ? 'checked="checked"' : '').' />';

			if ($selecao) echo '<td id="plano_acao_item_nome_'.$linha['plano_acao_item_id'].'">'.$linha['plano_acao_item_nome'].'</td>';
			else echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$vetor_plano[$linha['plano_acao_item_id']].'</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm; width:10px;">'.($qnt_acao < 100 ? '0' : '').($qnt_acao < 10 ? '0' : '').$qnt_acao.'</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_item_oque'] ? $linha['plano_acao_item_oque'] : '&nbsp;').'</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_item_porque'] ? $linha['plano_acao_item_porque'] : '&nbsp;').'</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_item_onde'] ? $linha['plano_acao_item_onde'] : '&nbsp;').'</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm;'.($Aplic->profissional && ($linha['plano_acao_item_inicio'] && $linha['plano_acao_item_fim']) ? ' background: '.$linha['acao_situacao'].';' : '').'">'.$linha['plano_acao_item_quando'];
			if ($linha['plano_acao_item_quando'] && ($linha['plano_acao_item_inicio'] || $linha['plano_acao_item_fim'])) echo '<br>';
			if ($linha['plano_acao_item_inicio']) echo retorna_data($linha['plano_acao_item_inicio'], false);
			if ($linha['plano_acao_item_inicio'] && $linha['plano_acao_item_fim']) echo ' - ';
			if ($linha['plano_acao_item_fim']) echo retorna_data($linha['plano_acao_item_fim'], false);
			if (!$linha['plano_acao_item_quando'] && !$linha['plano_acao_item_inicio'] && !$linha['plano_acao_item_fim']) echo '&nbsp;';	
			echo '</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$linha['plano_acao_item_quem'];
			$sql->adTabela('plano_acao_item_usuario');
			$sql->adCampo('plano_acao_item_usuario_usuario');
			$sql->adOnde('plano_acao_item_usuario_item ='.(int)$linha['plano_acao_item_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			$saida_quem='';
			if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['plano_acao_item_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['plano_acao_item_id'].'"><br>'.$lista.'</span>';
					}
				} 	
			$sql->adTabela('plano_acao_item_dept');
			$sql->adCampo('plano_acao_item_dept_dept');
			$sql->adOnde('plano_acao_item_dept_plano_acao_item ='.(int)$linha['plano_acao_item_id']);
			$depts = $sql->carregarColuna();
			$sql->limpar();
		
			$saida_dept='';
			if ($depts && count($depts)) {
				$saida_dept.= link_secao($depts[0]);
				$qnt_depts=count($depts);
				if ($qnt_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';		
					$saida_dept.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamentos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'depts_'.$linha['plano_acao_item_id'].'\');">(+'.($qnt_depts - 1).')</a><span style="display: none" id="depts_'.$linha['plano_acao_item_id'].'"><br>'.$lista.'</span>';
					}
				} 		
			if ($saida_quem) echo ($linha['plano_acao_item_quem'] ? '<br>' : '').$saida_quem;
			if ($saida_dept) echo ($linha['plano_acao_item_quem'] || $saida_quem ? '<br>' : '').$saida_dept;
			if (!$saida_quem && !$linha['plano_acao_item_quem']&& !$saida_dept) echo '&nbsp;';
			echo '</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_item_como'] ? $linha['plano_acao_item_como'] : '&nbsp;').'</td>';
			echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$linha['plano_acao_item_quanto'];
			$sql->adTabela('plano_acao_item_custos');
			$sql->adCampo('SUM(((plano_acao_item_custos_quantidade*plano_acao_item_custos_custo*plano_acao_item_custos_cotacao)*((100+plano_acao_item_custos_bdi)/100))) as total');
			$sql->adOnde('plano_acao_item_custos_plano_acao_item ='.(int)$linha['plano_acao_item_id']);
			$custo = $sql->Resultado();
			$sql->limpar();
			if ($custo) echo ($linha['plano_acao_item_quanto']? '<br>' : '').$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha de Custos\', 1000, 600, \'m=praticas&a=estimado&dialogo=1&id='.(int)$linha['plano_acao_item_id'].'\', null, window);' : 'window.open(\'./index.php?m=praticas&a=estimado&dialogo=1&id='.(int)$linha['plano_acao_item_id'].'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.dica('Planilha de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar a planilha de custos estimados.').imagem('icones/planilha_estimado.gif').dicaF().'</a>';
			$sql->adTabela('plano_acao_item_gastos');
			$sql->adCampo('SUM(((plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo)*((100+plano_acao_item_gastos_bdi)/100))) as total');
			$sql->adOnde('plano_acao_item_gastos_plano_acao_item ='.(int)$linha['plano_acao_item_id']);
			$gasto = $sql->Resultado();
			$sql->limpar();
			if ($gasto) echo ($linha['plano_acao_item_quanto'] || $custo ? '<br>' : '').$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:'.($Aplic->profissional ? 'parent.gpwebApp.popUp(\'Planilha de Gastos\', 1000, 600, \'m=praticas&a=gasto&dialogo=1&id='.(int)$linha['plano_acao_item_id'].'\', null, window);' : 'window.open(\'./index.php?m=praticas&a=gasto&dialogo=1&id='.(int)$linha['plano_acao_item_id'].'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')').'">'.dica('Planilha de Gastos', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos.').imagem('icones/planilha_gasto.gif').dicaF().'</a>';
			if (!$linha['plano_acao_item_quanto']) echo '&nbsp;';
			echo '</td>';
			
			if ($Aplic->profissional && $exibir['porcentagem_item']){
				echo '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.($linha['plano_acao_item_peso'] ? number_format($linha['plano_acao_item_peso'], 2, ',', '.') : '&nbsp;').'</td>';	
				if ($permite_editar && $editar && !$selecao){
					echo '<td align=center style="white-space: nowrap">'.selecionaVetor($percentual, 'plano_acao_item_percentagem', 'size="1" class="texto" onchange="mudar_percentagem_item_plano('. $linha['plano_acao_item_id'].', this.value);"', (int)$linha['plano_acao_item_percentagem']).'</td>';
					echo '<td align=center id="status_item_plano_'.$linha['plano_acao_item_id'].'" '.(isset($corItemPlano[$linha['plano_acao_item_status']]) ? 'style="white-space: nowrap; background-color:#'.$corItemPlano[$linha['plano_acao_item_status']].';"' : 'style="white-space: nowrap;"').'>'.selecionaVetor($statusItemPlano, 'plano_acao_item_status', 'size="1" class="texto" onchange="mudar_status_item_plano('. $linha['plano_acao_item_id'].', this.value);"', $linha['plano_acao_item_status']).'</td>';
					echo '<td  onClick="editar_obs_item_plano('.$linha['plano_acao_item_id'].');"><a href="javascript: void(0);"><div id="obs_item_plano_'.$linha['plano_acao_item_id'].'">'.($linha['plano_acao_item_observacao'] ? $linha['plano_acao_item_observacao'] : '&nbsp;').'</div></a></td>';
					}
				else {
					echo '<td align=center style="white-space: nowrap" width=40>'.number_format($linha['plano_acao_item_percentagem'], 2, ',', '.').'</td>';
					echo '<td align=center style="white-space: nowrap" width=80 '.(isset($corItemPlano[$linha['plano_acao_item_status']]) ? 'style="background-color:#'.$corItemPlano[$linha['plano_acao_item_status']].';"' : '').'>'.(isset($statusItemPlano[$linha['plano_acao_item_status']]) ? $statusItemPlano[$linha['plano_acao_item_status']] : '&nbsp;').'</td>';
					echo '<td>'.$linha['plano_acao_item_observacao'].'</td>';
					}	
				}
			
			echo '</tr>';
			}
		}
	echo '</table></td></tr>';
	}

if (!count($plano_acao_item)) echo '<tr><td>Nenhuma ação de '.$config['acao'].' encontrada.</td></tr>';
elseif (!$qnt_acao) echo '<tr><td>Sem permissão de ver qusalquer ação de '.$config['acao'].' encontrada.</td></tr>';
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS();').'</td></tr></table></td></tr>';
echo '</table>';	


if ($Aplic->profissional && $qnt_com_tempo && !$selecao){
	echo '<table border=0 cellpadding=0 cellspacing=0 class="std" width="100%"><tr>';
	echo '<td style="white-space: nowrap;border-style:solid;border-width:1px; background: #ffffff;">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica('Prevista', 'Prevista é quando a data de ínicio da ação ainda não passou.').'&nbsp;Para o futuro'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td style="white-space: nowrap;border-style:solid;border-width:1px; background: #e6eedd;">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica('Iniciada e Dentro do Prazo', 'Ação iniciada e dentro do prazo é quando a data de ínicio da mesma já ocorreu, e a mesma já está acima de 0% executada, entretanto ainda não se chegou na data de término.').'&nbsp;Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td style="white-space: nowrap;border-style:solid;border-width:1px; background: #ffeebb;">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica('Deveria ter Iniciada', 'Ação deveria ter iniciada é quando a data de ínicio da mesma já ocorreu, entretanto ainda se encontra em 0% executada.').'&nbsp;Deveria ter iniciada'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td style="white-space: nowrap;border-style:solid;border-width:1px; background: #cc6666;">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica('Em Atraso', 'Ação em atraso é quando a data de término da mesma já ocorreu, entretanto ainda não se encontra em 100% executada.').'&nbsp;Em atraso'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td style="white-space: nowrap;border-style:solid;border-width:1px; background: #aaddaa;">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica('Terminada', 'Ação terminada é quando está 100% executada.').'&nbsp;Terminada'.dicaF().'</td>';
	echo '<td width="100%">&nbsp;</td>';
	echo '</tr></table>';
	}


?>
<script type="text/javascript">
	
function selecionar(plano_acao_item_id){
	var nome=document.getElementById('plano_acao_item_nome_'+plano_acao_item_id).innerHTML;
	setFechar(plano_acao_item_id, nome);
	}
	
function selecionar_multiplo(){
	var qnt=0;	
	var saida='';
	 with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			if (thiselm.checked && thiselm.name=='campos[]') saida=saida+(qnt++ ? ',' : '')+thiselm.value;
      }
    }
	setFechar(saida, '');
	}	
	
function setFechar(chave, valor){
	if(parent && parent.gpwebApp){
		if (chave) parent.gpwebApp._popupCallback(chave, valor); 
		else parent.gpwebApp._popupCallback(null, "");
		} 
	else {
		if (chave!=0) <?php echo 'window.opener.'.($chamarVolta ? $chamarVolta : 'vazio').'(chave, valor);'?> 
		else <?php echo 'window.opener.'.($chamarVolta ? $chamarVolta : 'vazio').'(null, "");'?>  
		window.close();
		}
	}
	
function cancelarSelecao(){
	if(parent && parent.gpwebApp && parent.gpwebApp._popupWin) parent.gpwebApp._popupWin.close(); 
	else window.close();
	}

function marca_sel_todas() {
  with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			if (thiselm.name=='campos[]' || thiselm.name=='todos') thiselm.checked = !thiselm.checked;
      }
    }
  }	
	
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


function editar_obs_item_plano(plano_acao_item_id){
	parent.gpwebApp.popUp('Observação', 850, 300, 'm=praticas&a=plano_acao_item_obs&dialogo=1&plano_acao_item_id='+plano_acao_item_id, window.setObservacao_item_plano, window);
	}

function setObservacao_item_plano(plano_acao_item_id){
	xajax_ver_obs_item_plano(plano_acao_item_id);
	}

function mudar_percentagem_item_plano(plano_acao_item_id, valor){
	xajax_mudar_percentagem_item_plano(plano_acao_item_id, valor);
	}

function mudar_status_item_plano(plano_acao_item_id, valor){
	xajax_mudar_status_item_plano(plano_acao_item_id, valor);
	}
</script>	