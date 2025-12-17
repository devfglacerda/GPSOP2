<?php
global $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $a, $estilo_interface, $dialogo, $tab, $cia_id, $dept_id, $lista_cias, $pesquisar_texto, $licaostatus, $licaocategoria, $licaotipo, $usuario_id, $lista_depts, $data_inicio, $data_fim, $filtro_extra_lista, $usar_periodo, $favorito_id,  $podeEditar, $filtro_prioridade_licao,
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

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');


$licao_categoria=getSisValor('LicaoCategoria');
$licao_status=getSisValor('StatusLicao');

$sql = new BDConsulta;
$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_licoes']);
$xmin = $xtamanhoPagina * ($pagina - 1);  


$ordenar=getParam($_REQUEST, 'ordenar', 'licao_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');


	
$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'licoes\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'licoes\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}



$sql->adTabela('licao');
$sql->adCampo('count(DISTINCT licao.licao_id)');
if ($pesquisar_texto) $sql->adOnde('licao_nome LIKE \'%'.$pesquisar_texto.'%\' OR licao_ocorrencia LIKE \'%'.$pesquisar_texto.'%\' OR licao_consequencia LIKE \'%'.$pesquisar_texto.'%\' OR licao_acao_tomada LIKE \'%'.$pesquisar_texto.'%\' OR licao_aprendizado LIKE \'%'.$pesquisar_texto.'%\'');
if ($usar_periodo) {
	$sql->adOnde('licao_data_final >=\''.$data_inicio->format("%Y-%m-%d").'\'');
	$sql->adOnde('licao_data_final <=\''.$data_fim->format("%Y-%m-%d").'\'');
	}


$sql->esqUnir('licao_gestao','licao_gestao','licao_gestao_licao = licao.licao_id');
if ($tarefa_id) $sql->adOnde('licao_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas', 'tarefa_id=licao_gestao_tarefa');
	$sql->adOnde('licao_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('licao_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('licao_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('licao_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('licao_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('licao_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('licao_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('licao_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('licao_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('licao_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('licao_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('licao_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('licao_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('licao_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('licao_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('licao_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('licao_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('licao_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('licao_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('licao_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('licao_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('licao_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('licao_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('licao_gestao_programa IN ('.$programa_id.')');

elseif ($licao_id) $sql->adOnde('licao_gestao_semelhante IN ('.$licao_id.')');

elseif ($evento_id) $sql->adOnde('licao_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('licao_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('licao_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('licao_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('licao_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('licao_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('licao_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('licao_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('licao_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('licao_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('licao_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('licao_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('licao_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('licao_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('licao_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('licao_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('licao_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('licao_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('licao_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('licao_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('licao_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('licao_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('licao_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('licao_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('licao_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('licao_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('licao_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('licao_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('licao_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('licao_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('licao_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
if ($filtro_prioridade_licao){
	$sql->esqUnir('priorizacao', 'priorizacao', 'licao.licao_id=priorizacao_licao');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_licao.')');
	}
if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'licao.licao_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}		
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('licao_dept','licao_dept', 'licao_dept.licao_dept_licao=licao.licao_id');
	$sql->adOnde('licao_dept_dept='.(int)$dept_id.' OR licao_dept='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('licao_dept','licao_dept', 'licao_dept.licao_dept_licao=licao.licao_id');
	$sql->adOnde('licao_dept_dept IN ('.$lista_depts.') OR licao_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('licao_cia', 'licao_cia', 'licao.licao_id=licao_cia_licao');
	$sql->adOnde('licao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR licao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('licao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('licao_cia IN ('.$lista_cias.')');	
if($usuario_id) {
	$sql->esqUnir('licao_usuario', 'licao_usuario', 'licao.licao_id=licao_usuario_licao');
	$sql->adOnde('licao_responsavel='.(int)$usuario_id.' OR licao_usuario_usuario='.(int)$usuario_id);	
	}
if ($licaostatus && $licaostatus!=-1)$sql->adOnde('licao_status IN ('.$licaostatus.')');
if ($licaocategoria && $licaocategoria!=-1)$sql->adOnde('licao_categoria IN ('.$licaocategoria.')');
if ($licaotipo && $licaotipo!=-1)$sql->adOnde('licao_tipo IN ('.$licaotipo.')');

if ($tab==0 || $a!='licao_lista') $sql->adOnde('licao_ativa=1');	
elseif ($tab==1) $sql->adOnde('licao_ativa=0');	

if($filtro_extra_lista !== false){
  if($filtro_extra_lista) $sql->adOnde('licao.licao_id IN ('.$filtro_extra_lista.')');
  }	
$xtotalregistros = $sql->Resultado();
$sql->limpar();




$sql->adTabela('licao');
$sql->adCampo('licao.*, formatar_data(licao_data_final, \'%d/%m/%Y\') AS data_final');
if ($pesquisar_texto) $sql->adOnde('licao_nome LIKE \'%'.$pesquisar_texto.'%\' OR licao_ocorrencia LIKE \'%'.$pesquisar_texto.'%\' OR licao_consequencia LIKE \'%'.$pesquisar_texto.'%\' OR licao_acao_tomada LIKE \'%'.$pesquisar_texto.'%\' OR licao_aprendizado LIKE \'%'.$pesquisar_texto.'%\'');
if ($usar_periodo) {
	$sql->adOnde('licao_data_final >=\''.$data_inicio->format("%Y-%m-%d").'\'');
	$sql->adOnde('licao_data_final <=\''.$data_fim->format("%Y-%m-%d").'\'');
	}

$sql->esqUnir('licao_gestao','licao_gestao','licao_gestao_licao = licao.licao_id');
if ($tarefa_id) $sql->adOnde('licao_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas', 'tarefa_id=licao_gestao_tarefa');
	$sql->adOnde('licao_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('licao_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('licao_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('licao_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('licao_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('licao_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('licao_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('licao_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('licao_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('licao_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('licao_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('licao_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('licao_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('licao_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('licao_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('licao_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('licao_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('licao_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('licao_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('licao_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('licao_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('licao_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('licao_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('licao_gestao_programa IN ('.$programa_id.')');

elseif ($licao_id) $sql->adOnde('licao_gestao_semelhante IN ('.$licao_id.')');

elseif ($evento_id) $sql->adOnde('licao_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('licao_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('licao_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('licao_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('licao_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('licao_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('licao_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('licao_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('licao_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('licao_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('licao_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('licao_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('licao_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('licao_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('licao_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('licao_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('licao_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('licao_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('licao_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('licao_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('licao_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('licao_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('licao_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('licao_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('licao_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($laudo_id) $sql->adOnde('licao_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('licao_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('licao_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('licao_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('licao_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

if ($filtro_prioridade_licao){
	$sql->esqUnir('priorizacao', 'priorizacao', 'licao.licao_id=priorizacao_licao');
	if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_licao = licao.licao_id AND priorizacao_modelo IN ('.$filtro_prioridade_licao.')) AS priorizacao');
	else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_licao = licao.licao_id AND priorizacao_modelo IN ('.$filtro_prioridade_licao.')) AS priorizacao');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_licao.')');
	}

if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'licao.licao_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}		
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('licao_dept','licao_dept', 'licao_dept.licao_dept_licao=licao.licao_id');
	$sql->adOnde('licao_dept_dept='.(int)$dept_id.' OR licao_dept='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('licao_dept','licao_dept', 'licao_dept.licao_dept_licao=licao.licao_id');
	$sql->adOnde('licao_dept_dept IN ('.$lista_depts.') OR licao_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('licao_cia', 'licao_cia', 'licao.licao_id=licao_cia_licao');
	$sql->adOnde('licao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR licao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('licao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('licao_cia IN ('.$lista_cias.')');	
if($usuario_id) {
	$sql->esqUnir('licao_usuario', 'licao_usuario', 'licao.licao_id=licao_usuario_licao');
	$sql->adOnde('licao_responsavel='.(int)$usuario_id.' OR licao_usuario_usuario='.(int)$usuario_id);	
	}
if ($licaostatus && $licaostatus!=-1)$sql->adOnde('licao_status IN ('.$licaostatus.')');
if ($licaocategoria && $licaocategoria!=-1)$sql->adOnde('licao_categoria IN ('.$licaocategoria.')');
if ($licaotipo && $licaotipo!=-1)$sql->adOnde('licao_tipo IN ('.$licaotipo.')');

if ($tab==0 || $a!='licao_lista') $sql->adOnde('licao_ativa=1');	
elseif ($tab==1) $sql->adOnde('licao_ativa=0');	

if($filtro_extra_lista !== false){
    if($filtro_extra_lista){
        $sql->adOnde('licao.licao_id IN ('.$filtro_extra_lista.')');
        }
    }	
if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_licao=licao.licao_id) AS tem_aprovacao');	
$sql->adGrupo('licao.licao_id');    
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$licao=$sql->Lista();
$sql->limpar();





$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['licao']), ucfirst($config['licoes']),'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

if (!$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
if ($exibir['licao_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação d'.$config['genero_licao'].' '.$config['licao'].'.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação d'.$config['genero_licao'].' '.$config['licao'].'.').'Nome'.dicaF().'</a></th>';
if ($exibir['licao_aprovado'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_licao) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';

if ($exibir['licao_relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionada', 'A qual objeto a lição aprendida está relacionada.').'Relacionada'.dicaF().'</a></th>';

if ($exibir['licao_status']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_status&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_status' ? imagem('icones/'.$seta[$ordem]) : '').dica('Statua', 'Neste campo fica o status d'.$config['genero_licao'].' '.$config['licao'].'.').'Status'.dicaF().'</a></th>';

if ($exibir['licao_tipo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_tipo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_tipo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tipo', 'Neste campo fica o tipo d'.$config['genero_licao'].' '.$config['licao'].'.').'Tipo'.dicaF().'</a></th>';
if ($exibir['licao_categoria']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_categoria&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_categoria' ? imagem('icones/'.$seta[$ordem]) : '').dica('Categoria', 'Neste campo fica a categoria d'.$config['genero_licao'].' '.$config['licao'].'.').'Categoria'.dicaF().'</a></th>';




if ($exibir['licao_ocorrencia']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_ocorrencia&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_ocorrencia' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ocorrência', 'Neste campo fica a ocorrência d'.$config['genero_licao'].' '.$config['licao'].'.').'Ocorrência'.dicaF().'</a></th>';
if ($exibir['licao_consequencia']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_consequencia&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_consequencia' ? imagem('icones/'.$seta[$ordem]) : '').dica('Consequências', 'Neste campo fica a consequências d'.$config['genero_licao'].' '.$config['licao'].'.').'Consequências'.dicaF().'</a></th>';
if ($exibir['licao_acao_tomada']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_acao_tomada&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_acao_tomada' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ação Tomada', 'Neste campo fica a ação tomada n'.$config['genero_licao'].' '.$config['licao'].'.').'Ação Tomada'.dicaF().'</a></th>';
if ($exibir['licao_aprendizado']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_aprendizado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_aprendizado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprendizado', 'Neste campo fica o aprendizado d'.$config['genero_licao'].' '.$config['licao'].'.').'Aprendizado'.dicaF().'</a></th>';
if ($exibir['licao_data_final']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=licao_data_final&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_data_final' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'Neste campo fica a data d'.$config['genero_licao'].' '.$config['licao'].'.').'Data'.dicaF().'</a></th>';




if ($exibir['licao_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=licao_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='licao_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['licao_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['licao_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=licao_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='licao_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['licao_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['licao_responsavel']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=licao_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='licao_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_licao'].' '.$config['licao'].'.').'Responsável'.dicaF().'</a></th>';
if ($exibir['licao_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_licao'].' '.$config['licao'].'.').'Designados'.dicaF().'</th>';



echo '</tr>';

$qnt=0;


foreach ($licao as $linha) {
	$qnt++;
	if (permiteAcessarLicao($linha['licao_acesso'],$linha['licao_id'])){
		$editar=permiteEditarLicao($linha['licao_acesso'],$linha['licao_id']);
		if ($edicao && !$editar) continue;
		if ($Aplic->profissional) $bloquear=($linha['licao_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
		else $bloquear=0;
		echo '<tr>';
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['licao_id'].'" value="'.$linha['licao_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['licao_id'].'" value="'.$linha['licao_id'].'" '.(isset($selecionado[$linha['licao_id']]) ? 'checked="checked"' : '').' />';

		if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.($editar && $podeEditar && !$bloquear ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_licao'].' '.$config['licao'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=licao_editar&licao_id='.$linha['licao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		if ($exibir['licao_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['licao_cor'].'"><font color="'.melhorCor($linha['licao_cor']).'">&nbsp;&nbsp;</font></td>';
		if ($selecao) echo '<td id="licao_nome_'.$linha['licao_id'].'">'.$linha['licao_nome'].'</td>';
		else echo '<td>'.link_licao($linha['licao_id']).'</td>';
		if ($exibir['licao_aprovado'] && $Aplic->profissional) echo '<td width=30 align="center">'.($linha['licao_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
		if ($filtro_prioridade_licao) echo '<td align="right" style="white-space: nowrap" width=50>'.($linha['priorizacao']).'</td>';	
		
		
		if ($exibir['licao_relacionado']){
			$sql->adTabela('licao_gestao');
			$sql->adCampo('licao_gestao.*');
			$sql->adOnde('licao_gestao_licao ='.(int)$linha['licao_id']);
			$sql->adOrdem('licao_gestao_ordem');
		  $lista = $sql->Lista();
		  $sql->limpar();
		  echo '<td>';
		  if (count($lista)) {
				$qnt_gestao=0;
				foreach($lista as $gestao_data){
					if ($gestao_data['licao_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['licao_gestao_tarefa']);
					elseif ($gestao_data['licao_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['licao_gestao_projeto']);
					elseif ($gestao_data['licao_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['licao_gestao_pratica']);
					elseif ($gestao_data['licao_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['licao_gestao_acao']);
					elseif ($gestao_data['licao_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['licao_gestao_perspectiva']);
					elseif ($gestao_data['licao_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['licao_gestao_tema']);
					elseif ($gestao_data['licao_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['licao_gestao_objetivo']);
					elseif ($gestao_data['licao_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['licao_gestao_fator']);
					elseif ($gestao_data['licao_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['licao_gestao_estrategia']);
					elseif ($gestao_data['licao_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['licao_gestao_meta']);
					elseif ($gestao_data['licao_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['licao_gestao_canvas']);
					elseif ($gestao_data['licao_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['licao_gestao_risco']);
					elseif ($gestao_data['licao_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['licao_gestao_risco_resposta']);
					elseif ($gestao_data['licao_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['licao_gestao_indicador']);
					elseif ($gestao_data['licao_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['licao_gestao_calendario']);
					elseif ($gestao_data['licao_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['licao_gestao_monitoramento']);
					elseif ($gestao_data['licao_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['licao_gestao_ata']);
					elseif ($gestao_data['licao_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['licao_gestao_mswot']);
					elseif ($gestao_data['licao_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['licao_gestao_swot']);
					elseif ($gestao_data['licao_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['licao_gestao_operativo']);
					elseif ($gestao_data['licao_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['licao_gestao_instrumento']);
					elseif ($gestao_data['licao_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['licao_gestao_recurso']);
					elseif ($gestao_data['licao_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['licao_gestao_problema']);
					elseif ($gestao_data['licao_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['licao_gestao_demanda']);	
					elseif ($gestao_data['licao_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['licao_gestao_programa']);
					
					elseif ($gestao_data['licao_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['licao_gestao_semelhante']);
					
					elseif ($gestao_data['licao_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['licao_gestao_evento']);
					elseif ($gestao_data['licao_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['licao_gestao_link']);
					elseif ($gestao_data['licao_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['licao_gestao_avaliacao']);
					elseif ($gestao_data['licao_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['licao_gestao_tgn']);
					elseif ($gestao_data['licao_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['licao_gestao_brainstorm']);
					elseif ($gestao_data['licao_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['licao_gestao_gut']);
					elseif ($gestao_data['licao_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['licao_gestao_causa_efeito']);
					elseif ($gestao_data['licao_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['licao_gestao_arquivo']);
					elseif ($gestao_data['licao_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['licao_gestao_forum']);
					elseif ($gestao_data['licao_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['licao_gestao_checklist']);
					elseif ($gestao_data['licao_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['licao_gestao_agenda']);
					elseif ($gestao_data['licao_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['licao_gestao_agrupamento']);
					elseif ($gestao_data['licao_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['licao_gestao_patrocinador']);
					elseif ($gestao_data['licao_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['licao_gestao_template']);
					elseif ($gestao_data['licao_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['licao_gestao_painel']);
					elseif ($gestao_data['licao_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['licao_gestao_painel_odometro']);
					elseif ($gestao_data['licao_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['licao_gestao_painel_composicao']);		
					elseif ($gestao_data['licao_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['licao_gestao_tr']);	
					elseif ($gestao_data['licao_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['licao_gestao_me']);	
					elseif ($gestao_data['licao_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['licao_gestao_acao_item']);	
					elseif ($gestao_data['licao_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['licao_gestao_beneficio']);	
					elseif ($gestao_data['licao_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['licao_gestao_painel_slideshow']);	
					elseif ($gestao_data['licao_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['licao_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['licao_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['licao_gestao_projeto_abertura']);	
					elseif ($gestao_data['licao_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['licao_gestao_plano_gestao']);	
					elseif ($gestao_data['licao_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['licao_gestao_ssti']);	
					elseif ($gestao_data['licao_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['licao_gestao_laudo']);	
					elseif ($gestao_data['licao_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['licao_gestao_trelo']);	
					elseif ($gestao_data['licao_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['licao_gestao_trelo_cartao']);	
					elseif ($gestao_data['licao_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['licao_gestao_pdcl']);	
					elseif ($gestao_data['licao_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['licao_gestao_pdcl_item']);	
					}
				}
			echo '</td>';	
			}
		elseif ($exibir['licao_relacionado']) echo '<td>'.link_projeto($linha['licao_projeto']).'</td>';
		
		if ($exibir['licao_status']) echo '<td align=center>'.(isset($licao_status[$linha['licao_status']]) ? $licao_status[$linha['licao_status']] : '&nbsp;').'</td>';
		if ($exibir['licao_tipo']) echo '<td align=center>'.($linha['licao_tipo']? 'Positiva' : 'Negativa').'</td>';
		if ($exibir['licao_categoria']) echo '<td align=center>'.(isset($licao_categoria[$linha['licao_categoria']]) ? $licao_categoria[$linha['licao_categoria']] : '&nbsp;').'</td>';

		if ($exibir['licao_ocorrencia']) echo '<td>'.($linha['licao_ocorrencia'] ? $linha['licao_ocorrencia'] : '&nbsp;').'</td>';
		if ($exibir['licao_consequencia']) echo '<td>'.($linha['licao_consequencia'] ? $linha['licao_consequencia'] : '&nbsp;').'</td>';
		if ($exibir['licao_acao_tomada']) echo '<td>'.($linha['licao_acao_tomada'] ? $linha['licao_acao_tomada'] : '&nbsp;').'</td>';
		if ($exibir['licao_aprendizado']) echo '<td>'.($linha['licao_aprendizado'] ? $linha['licao_aprendizado'] : '&nbsp;').'</td>';
		if ($exibir['licao_data_final']) echo '<td>'.($linha['data_final'] ? $linha['data_final'] : '&nbsp;').'</td>';
			
		if ($exibir['licao_cia']) echo '<td>'.link_cia($linha['licao_cia']).'</td>';
		
		if ($exibir['licao_cias']){
			$sql->adTabela('licao_cia');
			$sql->adCampo('licao_cia_cia');
			$sql->adOnde('licao_cia_licao = '.(int)$linha['licao_id']);
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
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['licao_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['licao_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
			
		if ($exibir['licao_dept']) echo '<td>'.link_secao($linha['licao_dept']).'</td>';	
		
		if ($exibir['licao_depts']){
			$sql->adTabela('licao_dept');
			$sql->adCampo('licao_dept_dept');
			$sql->adOnde('licao_dept_licao = '.(int)$linha['licao_id']);
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
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['licao_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['licao_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
	
		if ($exibir['licao_responsavel']) echo '<td>'.link_usuario($linha['licao_responsavel'],'','','esquerda').'</td>';
		
		if ($exibir['licao_designados']) {
			$sql->adTabela('licao_usuario');
			$sql->adCampo('licao_usuario_usuario');
			$sql->adOnde('licao_usuario_licao = '.(int)$linha['licao_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['licao_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['licao_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
		
		
		
		echo '</tr>';
		}
	}
if (!count($licao)) echo '<tr><td colspan=20><p>Nenh'.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].' encontrad'.$config['genero_licao'].'.</p></td></tr>';
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS();').'</td></tr></table></td></tr>';

echo '</table>';
?>
<script type="text/javascript">
	
function selecionar(licao_id){
	var nome=document.getElementById('licao_nome_'+licao_id).innerHTML;
	setFechar(licao_id, nome);
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
			thiselm.checked = !thiselm.checked
      }
    }
  }	
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>				