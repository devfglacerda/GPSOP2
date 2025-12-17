<?php

global $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $Aplic, $filtro_prioridade_forum, $ver_subordinadas, $estilo_interface, $lista_cias, $lista_depts, $tab, $usuario_id, $cia_id, $dept_id, $dialogo, $favorito_id, $podeEditar,
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

$ordenar=getParam($_REQUEST, 'ordenar', 'forum_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$pagina=getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = $config['qnt_foruns'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$max_larg_msg = 30;


$sql = new BDConsulta;

$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'foruns\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'foruns\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}








$sql->adTabela('foruns');
$sql->adCampo('count(DISTINCT foruns.forum_id)');
if ($filtro_prioridade_forum){
	$sql->esqUnir('priorizacao', 'priorizacao', 'foruns.forum_id=priorizacao_forum');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_forum.')');
	}
$sql->esqUnir('forum_gestao','forum_gestao','forum_gestao_forum = foruns.forum_id');
if ($m=='foruns' && $a=='index' && $tab==0 ) $sql->adOnde('forum_ativo=1');
elseif ($m=='foruns' && $a=='index' && $tab==1) $sql->adOnde('forum_ativo!=1 OR forum_ativo IS NULL');
if ($tarefa_id) $sql->adOnde('forum_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=forum_gestao_tarefa');
	$sql->adOnde('forum_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('forum_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('forum_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('forum_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('forum_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('forum_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('forum_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('forum_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('forum_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('forum_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('forum_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('forum_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('forum_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('forum_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('forum_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('forum_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('forum_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('forum_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('forum_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('forum_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('forum_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('forum_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('forum_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('forum_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('forum_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('forum_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('forum_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('forum_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('forum_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('forum_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('forum_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('forum_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('forum_gestao_arquivo IN ('.$arquivo_id.')');

elseif ($forum_id) $sql->adOnde('forum_gestao_semelhante IN ('.$forum_id.')');

elseif ($checklist_id) $sql->adOnde('forum_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('forum_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('forum_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('forum_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('forum_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('forum_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('forum_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('forum_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('forum_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('forum_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('forum_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('forum_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('forum_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('forum_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('forum_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('forum_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('forum_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('forum_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('forum_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('forum_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('forum_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('forum_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'foruns.forum_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}		
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('forum_dept','forum_dept', 'forum_dept.forum_dept_forum=foruns.forum_id');
	$sql->adOnde('forum_dept='.(int)$dept_id.' OR forum_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('forum_dept','forum_dept', 'forum_dept.forum_dept_forum=foruns.forum_id');
	$sql->adOnde('forum_dept IN ('.$lista_depts.') OR forum_dept_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('forum_cia', 'forum_cia', 'foruns.forum_id=forum_cia_forum');
	$sql->adOnde('forum_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR forum_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('forum_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('forum_cia IN ('.$lista_cias.')');	
if ($usuario_id) {
	$sql->esqUnir('forum_usuario','forum_usuario', 'forum_usuario_forum=foruns.forum_id');
	$sql->adOnde('forum_dono = '.(int)$usuario_id.' OR forum_moderador = '.(int)$usuario_id.' OR forum_usuario_usuario= '.(int)$usuario_id);
	}
$xpg_totalregistros = $sql->resultado();
$sql->limpar();









	
$sql->adTabela('foruns');
$sql->esqUnir('usuarios', 'u','usuario_id = forum_dono');
$sql->esqUnir('forum_mensagens', 'l', 'l.mensagem_id = forum_ultimo_id');
$sql->esqUnir('forum_mensagens', 'c', 'c.mensagem_forum = forum_id');
$sql->esqUnir('forum_acompanhar', 'w', 'acompanhar_forum = forum_id');
$sql->esqUnir('forum_visitas', 'v', 'visita_usuario = '.(int)$Aplic->usuario_id.' AND visita_forum = forum_id and visita_mensagem = c.mensagem_id');
$sql->esqUnir('contatos', 'cts', 'contato_id = u.usuario_contato');
if ($filtro_prioridade_forum){
		$sql->esqUnir('priorizacao', 'priorizacao', 'foruns.forum_id=priorizacao_forum');
		if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_forum = foruns.forum_id AND priorizacao_modelo IN ('.$filtro_prioridade_forum.')) AS priorizacao');
		else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_forum = foruns.forum_id AND priorizacao_modelo IN ('.$filtro_prioridade_forum.')) AS priorizacao');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_forum.')');
		}
$sql->adCampo('foruns.*');
$sql->adCampo('forum_moderador, forum_data_criacao, forum_ultima_data');
$sql->adCampo('SUM(CASE WHEN c.mensagem_superior IS NULL THEN 1 ELSE 0 END ) AS forum_topicos');
$sql->adCampo('SUM(CASE WHEN c.mensagem_superior > 0 THEN 1 ELSE 0 END) AS forum_respostas');
$sql->adCampo('usuario_login, concatenar_tres(contato_posto, \' \',contato_nomeguerra) nome_responsavel');
$sql->adCampo('l.mensagem_texto, l.mensagem_titulo');
$sql->adCampo('LENGTH(l.mensagem_texto) message_length, acompanhar_usuario, l.mensagem_superior, l.mensagem_id');
$sql->adCampo('count(distinct v.visita_mensagem) as visit_contagem, count(distinct c.mensagem_id) as message_contagem');


$sql->esqUnir('forum_gestao','forum_gestao','forum_gestao_forum = foruns.forum_id');
if ($m=='foruns' && $a=='index' && $tab==0 ) $sql->adOnde('forum_ativo=1');
elseif ($m=='foruns' && $a=='index' && $tab==1) $sql->adOnde('forum_ativo!=1 OR forum_ativo IS NULL');
if ($tarefa_id) $sql->adOnde('forum_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=forum_gestao_tarefa');
	$sql->adOnde('forum_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('forum_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('forum_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('forum_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('forum_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('forum_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('forum_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('forum_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('forum_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('forum_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('forum_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('forum_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('forum_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('forum_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('forum_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('forum_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('forum_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('forum_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('forum_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('forum_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('forum_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('forum_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('forum_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('forum_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('forum_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('forum_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('forum_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('forum_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('forum_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('forum_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('forum_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('forum_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('forum_gestao_arquivo IN ('.$arquivo_id.')');

elseif ($forum_id) $sql->adOnde('forum_gestao_semelhante IN ('.$forum_id.')');

elseif ($checklist_id) $sql->adOnde('forum_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('forum_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('forum_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('forum_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('forum_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('forum_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('forum_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('forum_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('forum_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('forum_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('forum_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('forum_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('forum_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('forum_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('forum_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('forum_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('forum_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('forum_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('forum_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('forum_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('forum_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('forum_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'foruns.forum_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}		
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('forum_dept','forum_dept', 'forum_dept.forum_dept_forum=foruns.forum_id');
	$sql->adOnde('forum_dept='.(int)$dept_id.' OR forum_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('forum_dept','forum_dept', 'forum_dept.forum_dept_forum=foruns.forum_id');
	$sql->adOnde('forum_dept IN ('.$lista_depts.') OR forum_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('forum_cia', 'forum_cia', 'foruns.forum_id=forum_cia_forum');
	$sql->adOnde('forum_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR forum_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('forum_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('forum_cia IN ('.$lista_cias.')');	
	

if ($usuario_id) {
	$sql->esqUnir('forum_usuario','forum_usuario', 'forum_usuario_forum=foruns.forum_id');
	$sql->adOnde('forum_dono = '.(int)$usuario_id.' OR forum_moderador = '.(int)$usuario_id.' OR forum_usuario_usuario= '.(int)$usuario_id);
	}

if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_forum=foruns.forum_id) AS tem_aprovacao');
$sql->adGrupo('forum_id');
$sql->setLimite($xpg_min, $xpg_tamanhoPagina);	
$sql->adOrdem($ordenar.' '.($ordem ? 'ASC' : 'DESC'));
$foruns = $sql->Lista();
$sql->limpar();

$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1 && !$dialogo) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'Fórum', 'Fóruns','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellspacing=0 cellpadding="2" border=0 class="tbl1">';
echo '<tr>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

if (!$dialogo) echo '<th></th>';

if ($exibir['forum_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=forum_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='forum_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação do fórum.').'Cor'.dicaF().'</a></th>';

if (!$dialogo) echo '<th align="right"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&ordenar=acompanhar_usuario\');" class="hdr">'.dica('Acompanhar', 'Marque as caixas abaixo e clique o botão <b>acompanhar</b> para ser informado sobre atualizações nos fóruns marcados.<br><br>Quando se está acompanhando um fórum, o sistema avisa caso houver mensagens não lidas.').'A'.dicaF().'</a></th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=forum_nome&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Nome', 'Nome do fórum.').($ordenar=='forum_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome'.dicaF().'</a></th>';
if ($exibir['forum_aprovado'] &&  $Aplic->profissional) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=forum_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='forum_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_forum) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';

if ($exibir['forum_relacionado'])  echo '<th>'.dica('Relacionado', 'A quais áreas do sistema está relacionado.').'Relacionado'.dicaF().'</th>';

if ($exibir['forum_descricao']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=forum_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='forum_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Neste campo fica a descrição do fórum.').'Descrição'.dicaF().'</a></th>';


if ($exibir['forum_cia']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=forum_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='forum_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['forum_cias']) echo '<th>'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['forum_dept']) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=forum_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='forum_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['forum_depts']) echo '<th>'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['forum_dono']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=forum_dono&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='forum_dono' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo fórum.').'Responsável'.dicaF().'</a></th>';
if ($exibir['forum_moderador']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=forum_moderador&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='forum_moderador' ? imagem('icones/'.$seta[$ordem]) : '').dica('Moderador', 'O '.$config['usuario'].' moderador do fórum.').'Moderador'.dicaF().'</a></th>';

if ($exibir['forum_designados']) echo '<th>'.dica('Designados', 'Neste campo fica os designados para o fórum.').'Designados'.dicaF().'</th>';



echo '<th align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=forum_topicos&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Tópicos', 'Cada fórum pode ter um ou mais tópicos.<br><br>Pode imaginar tópicos como subassuntos do fórum ou perguntas relacionadas ao fórum.').($ordenar=='forum_topicos' ? imagem('icones/'.$seta[$ordem]) : '').'Top.'.dicaF().'</a></th>';
echo '<th align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=forum_respostas&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Respostas', 'Cada tópico poderá ter diversas respostas (postagens).').($ordenar=='forum_respostas' ? imagem('icones/'.$seta[$ordem]) : '').'Resp.'.dicaF().'</a></th>';
echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=forum_ultima_data&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Data', 'Data da última resposta inserida em um dos tópicos.').($ordenar=='forum_ultima_data' ? imagem('icones/'.$seta[$ordem]) : '').'Data'.dicaF().'</a></th>';



echo '</tr>';


				


$permiteEditar=$Aplic->checarModulo('foruns', 'editar');


$agora = new CData();

foreach ($foruns as $linha) {
	if (isset($linha['forum_id']) && $linha['forum_id'] && permiteAcessarForum($linha['forum_acesso'],  $linha['forum_id'])){
		$editar=permiteEditarForum($linha['forum_acesso'], $linha['forum_id']);
		if ($Aplic->profissional) $bloquear=($linha['forum_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
		else $bloquear=0;
		if ($edicao && !$editar) continue;
		echo '<tr>';
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['forum_id'].'" value="'.$linha['forum_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['forum_id'].'" value="'.$linha['forum_id'].'" '.(isset($selecionado[$linha['forum_id']]) ? 'checked="checked"' : '').' />';

		if (!$dialogo){
			echo '<td align="center" width=16>';
			if (!$dialogo && $editar && $podeEditar && !$bloquear) echo dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' caso deseja editar este fórum.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=editar&forum_id='.$linha['forum_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF();
			if ($linha['visit_contagem'] != $linha['message_contagem']) echo '&nbsp;'.imagem('icones/msg_nova.png', 'Mensagem Não Lida','Você tem mensagem não lida neste fórum.');
			echo '</td>';
			}
		if ($exibir['forum_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['forum_cor'].'"><font color="'.melhorCor($linha['forum_cor']).'">&nbsp;&nbsp;</font></td>';

		
		
		if (!$dialogo) echo '<td align="center" width=16>'.dica('Acompanhar', 'Marque esta caixa e clique o botão <b>acompanhar</b> para ser informado sobre atualizações neste fórum.<br><br>Caso esteja acompanhando este fórum, o sistema avisará se houver mensagens não lidas.').'<input type="checkbox" name="forum_'.$linha['forum_id'].'" '.($linha['acompanhar_usuario'] ? 'checked="checked"' : '').' />'.dicaF().'</td>';
		
		
		$mensagem_data = intval($linha['forum_ultima_data']) ? new CData($linha['forum_ultima_data']) : null;
		$criar_data = $linha['forum_data_criacao'] ? new CData($linha['forum_data_criacao']) : null;
	
		if ($selecao) echo '<td id="forum_nome_'.$linha['forum_id'].'">'.$linha['forum_nome'].'</td>';
		else echo '<td>'.dica($linha['forum_nome'], 'Clique em cima do nome do fórum para ver os detalhes do mesmo.').'<span style="font-size:10pt;font-weight:bold"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$linha['forum_id'].'\');">'.$linha['forum_nome'].'</a></td>';
		
		if ($exibir['forum_aprovado'] && $Aplic->profissional) echo '<td width=30 align="center">'.($linha['forum_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
		if ($filtro_prioridade_forum) echo '<td align="right" style="white-space: nowrap" width=50>'.($linha['priorizacao']).'</td>';	

		
		if ($exibir['forum_relacionado']){
			$sql->adTabela('forum_gestao');
			$sql->adCampo('forum_gestao.*');
			$sql->adOnde('forum_gestao_forum ='.(int)$linha['forum_id']);	
			$sql->adOrdem('forum_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['forum_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['forum_gestao_tarefa']);
					elseif ($gestao_data['forum_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['forum_gestao_projeto']);
					elseif ($gestao_data['forum_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['forum_gestao_pratica']);
					elseif ($gestao_data['forum_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['forum_gestao_acao']);
					elseif ($gestao_data['forum_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['forum_gestao_perspectiva']);
					elseif ($gestao_data['forum_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['forum_gestao_tema']);
					elseif ($gestao_data['forum_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['forum_gestao_objetivo']);
					elseif ($gestao_data['forum_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['forum_gestao_fator']);
					elseif ($gestao_data['forum_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['forum_gestao_estrategia']);
					elseif ($gestao_data['forum_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['forum_gestao_meta']);
					elseif ($gestao_data['forum_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['forum_gestao_canvas']);
					elseif ($gestao_data['forum_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['forum_gestao_risco']);
					elseif ($gestao_data['forum_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['forum_gestao_risco_resposta']);
					elseif ($gestao_data['forum_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['forum_gestao_indicador']);
					elseif ($gestao_data['forum_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['forum_gestao_calendario']);
					elseif ($gestao_data['forum_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['forum_gestao_monitoramento']);
					elseif ($gestao_data['forum_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['forum_gestao_ata']);
					elseif ($gestao_data['forum_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['forum_gestao_mswot']);
					elseif ($gestao_data['forum_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['forum_gestao_swot']);
					elseif ($gestao_data['forum_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['forum_gestao_operativo']);
					elseif ($gestao_data['forum_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['forum_gestao_instrumento']);
					elseif ($gestao_data['forum_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['forum_gestao_recurso']);
					elseif ($gestao_data['forum_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['forum_gestao_problema']);
					elseif ($gestao_data['forum_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['forum_gestao_demanda']);	
					elseif ($gestao_data['forum_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['forum_gestao_programa']);
					elseif ($gestao_data['forum_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['forum_gestao_licao']);
					elseif ($gestao_data['forum_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['forum_gestao_evento']);
					elseif ($gestao_data['forum_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['forum_gestao_link']);
					elseif ($gestao_data['forum_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['forum_gestao_avaliacao']);
					elseif ($gestao_data['forum_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['forum_gestao_tgn']);
					elseif ($gestao_data['forum_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['forum_gestao_brainstorm']);
					elseif ($gestao_data['forum_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['forum_gestao_gut']);
					elseif ($gestao_data['forum_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['forum_gestao_causa_efeito']);
					elseif ($gestao_data['forum_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['forum_gestao_arquivo']);
					
					elseif ($gestao_data['forum_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['forum_gestao_semelhante']);
					
					elseif ($gestao_data['forum_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['forum_gestao_checklist']);
					elseif ($gestao_data['forum_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['forum_gestao_agenda']);
					elseif ($gestao_data['forum_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['forum_gestao_agrupamento']);
					elseif ($gestao_data['forum_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['forum_gestao_patrocinador']);
					elseif ($gestao_data['forum_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['forum_gestao_template']);
					elseif ($gestao_data['forum_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['forum_gestao_painel']);
					elseif ($gestao_data['forum_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['forum_gestao_painel_odometro']);
					elseif ($gestao_data['forum_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['forum_gestao_painel_composicao']);		
					elseif ($gestao_data['forum_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['forum_gestao_tr']);	
					elseif ($gestao_data['forum_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['forum_gestao_me']);	
					elseif ($gestao_data['forum_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['forum_gestao_acao_item']);	
					elseif ($gestao_data['forum_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['forum_gestao_beneficio']);	
					elseif ($gestao_data['forum_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['forum_gestao_painel_slideshow']);	
					elseif ($gestao_data['forum_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['forum_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['forum_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['forum_gestao_projeto_abertura']);	
					elseif ($gestao_data['forum_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['forum_gestao_plano_gestao']);	
					elseif ($gestao_data['forum_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['forum_gestao_ssti']);	
					elseif ($gestao_data['forum_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['forum_gestao_laudo']);	
					elseif ($gestao_data['forum_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['forum_gestao_trelo']);	
					elseif ($gestao_data['forum_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['forum_gestao_trelo_cartao']);	
					elseif ($gestao_data['forum_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['forum_gestao_pdcl']);	
					elseif ($gestao_data['forum_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['forum_gestao_pdcl_item']);	
					}
				}	
			echo '</td>';	
			}	
			
		if ($exibir['forum_descricao']) echo '<td>'.($linha['forum_descricao'] ? $linha['forum_descricao']: '&nbsp;').'</td>';	
			
				
		if ($exibir['forum_cia']) echo '<td>'.link_cia($linha['forum_cia']).'</td>';
		
		if ($exibir['forum_cias']){
			$sql->adTabela('forum_cia');
			$sql->adCampo('forum_cia_cia');
			$sql->adOnde('forum_cia_forum = '.(int)$linha['forum_id']);
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
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['forum_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['forum_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
			
		if ($exibir['forum_dept']) echo '<td>'.link_secao($linha['forum_dept']).'</td>';	
		
		if ($exibir['forum_depts']){
			$sql->adTabela('forum_dept');
			$sql->adCampo('forum_dept_dept');
			$sql->adOnde('forum_dept_forum = '.(int)$linha['forum_id']);
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
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['forum_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['forum_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
	
		if ($exibir['forum_dono']) echo '<td>'.link_usuario($linha['forum_dono'],'','','esquerda').'</td>';
		if ($exibir['forum_moderador']) echo '<td>'.link_usuario($linha['forum_moderador'],'','','esquerda').'</td>';
		
		if ($exibir['forum_designados']) {
			$sql->adTabela('forum_usuario');
			$sql->adCampo('forum_usuario_usuario');
			$sql->adOnde('forum_usuario_forum = '.(int)$linha['forum_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['forum_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['forum_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
		
		echo '<td align="center">'.$linha['forum_topicos'].'</td>';
		echo '<td align="center">'.$linha['forum_respostas'].'</td>';
		
		
		
		
		
		echo '<td>';
		if ($mensagem_data !== null) {
			echo $mensagem_data->format('%d/%m/%Y %H:%M');
			$ultimo = new Data_Intervalo();
			$ultimo->setFromDateDiff($agora, $mensagem_data);
			echo '<br /><font color=#999966>(Ultima Postagem ';
			printf('%.1f', $ultimo->format('%d'));
			echo ' dias atrás) </font>';
			$id = $linha['mensagem_superior'] < 0 ? $linha['mensagem_id'] : $linha['mensagem_superior'];
			echo '<br />'.dica($linha['mensagem_titulo'], $linha['mensagem_texto']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$linha['forum_id'].'&mensagem_id='.$id.'\');">';
			echo substr($linha['mensagem_texto'],0,$max_larg_msg);
			echo $linha['message_length'] > $max_larg_msg ? '...' : '';
			echo dicaF().'</a>';
			}
		else echo '';
		echo '</td></tr>';
		}
	}
if (!$xpg_totalregistros)  echo '<tr><td colspan=20>Nenhum fórum encontrado.</td></tr>';
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS();').'</td></tr></table></td></tr>';

echo '</table>';
if ($xpg_totalregistros && !$selecao) {
	echo '<table width="100%" cellspacing=0 cellpadding=0 border=0 class="std">';
	echo '<tr><td align="left">'.botao('acompanhar', 'Acompanhar', 'Acompanhar os fóruns marcados acima.<br><br>Quando se está acompanhando um fórum, o sistema avisa caso houver mensagens não lidas.','','env.submit();').'</td></tr>';
	echo '</table>';
	}

?>
<script type="text/javascript">

function selecionar(forum_id){
	var nome=document.getElementById('forum_nome_'+forum_id).innerHTML;
	setFechar(forum_id, nome);
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
  
</script>  