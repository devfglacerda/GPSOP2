<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $estilo_interface, $sql, $perms, $dialogo, $Aplic, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $favorito_id, $usuario_id, $pesquisar_texto, $filtro_pg_id, $filtro_prioridade_estrategia,
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
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina=getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_estrategias']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$ordenar=getParam($_REQUEST, 'ordenar', 'pg_estrategia_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');

$tipo_pontuacao=array(
		''=>'Manual',
		'media_ponderada'=>'Média ponderada das percentagens dos objetos relacionados',
		'pontos_completos'=>'Pontos dos objetos relacionados desde que com percentagens completas',
		'pontos_parcial'=>'Pontos dos objetos relacionados mesmo com percentagens incompletas',
		'indicador'=>'Indicador principal'
		);


$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'estrategias\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'estrategias\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}



$sql->adTabela('estrategias');
$sql->adCampo('count(DISTINCT estrategias.pg_estrategia_id)');

$sql->esqUnir('estrategia_gestao','estrategia_gestao','estrategia_gestao_estrategia = estrategias.pg_estrategia_id');
if ($tarefa_id) $sql->adOnde('estrategia_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=estrategia_gestao_tarefa');
	$sql->adOnde('estrategia_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('estrategia_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('estrategia_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('estrategia_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('estrategia_gestao_fator IN ('.$fator_id.')');

elseif ($pg_estrategia_id) $sql->adOnde('estrategia_gestao_semelhante IN ('.$pg_estrategia_id.')');

elseif ($pg_meta_id) $sql->adOnde('estrategia_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('estrategia_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('estrategia_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('estrategia_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('estrategia_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('estrategia_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('estrategia_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('estrategia_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('estrategia_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('estrategia_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('estrategia_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('estrategia_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('estrategia_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('estrategia_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('estrategia_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('estrategia_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('estrategia_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('estrategia_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('estrategia_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('estrategia_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('estrategia_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('estrategia_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('estrategia_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('estrategia_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('estrategia_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('estrategia_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('estrategia_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('estrategia_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('estrategia_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('estrategia_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('estrategia_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('estrategia_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('estrategia_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('estrategia_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('estrategia_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('estrategia_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('estrategia_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('estrategia_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('estrategia_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('estrategia_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('estrategia_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('estrategia_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('estrategia_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('estrategia_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('estrategia_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('estrategia_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('estrategia_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('estrategia_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('estrategia_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('estrategia_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

if ($filtro_prioridade_estrategia){
	$sql->esqUnir('priorizacao', 'priorizacao', 'estrategias.pg_estrategia_id=priorizacao_estrategia');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_estrategia.')');
	}
	
if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'estrategias.pg_estrategia_id=favorito_lista_campo');
	$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
	$sql->adOnde('favorito.favorito_id='.(int)$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_dept='.(int)$dept_id.' OR estrategias_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_dept IN ('.$lista_depts.') OR estrategias_depts.dept_id IN ('.$lista_depts.')');
	}
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('estrategia_cia', 'estrategia_cia', 'estrategias.pg_estrategia_id=estrategia_cia_estrategia');
	$sql->adOnde('pg_estrategia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR estrategia_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$favorito_id && !$lista_cias) $sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
elseif ($cia_id && !$favorito_id && $lista_cias) $sql->adOnde('pg_estrategia_cia IN ('.$lista_cias.')');

if ($filtro_pg_id){
	$sql->esqUnir('plano_gestao_estrategias','plano_gestao_estrategias','plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_estrategias.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$filtro_pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('estrategias_usuarios', 'estrategias_usuarios', 'estrategias_usuarios.pg_estrategia_id = estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_usuario = '.(int)$usuario_id.' OR estrategias_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_estrategia_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_estrategia_descricao LIKE \'%'.$pesquisar_texto.'%\'');


if ($tab==0 && $a=='estrategia_lista') $sql->adOnde('pg_estrategia_percentagem < 100');
if ($tab==1 && $a=='estrategia_lista') $sql->adOnde('pg_estrategia_percentagem = 100');
if ($tab==2 && $a=='estrategia_lista') $sql->adOnde('pg_estrategia_ativo = 0');
else $sql->adOnde('pg_estrategia_ativo = 1');
$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('estrategias');
$sql->adCampo('estrategias.*');

$sql->esqUnir('estrategia_gestao','estrategia_gestao','estrategia_gestao_estrategia = estrategias.pg_estrategia_id');
if ($tarefa_id) $sql->adOnde('estrategia_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=estrategia_gestao_tarefa');
	$sql->adOnde('estrategia_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('estrategia_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('estrategia_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('estrategia_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('estrategia_gestao_fator IN ('.$fator_id.')');

elseif ($pg_estrategia_id) $sql->adOnde('estrategia_gestao_semelhante IN ('.$pg_estrategia_id.')');

elseif ($pg_meta_id) $sql->adOnde('estrategia_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('estrategia_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('estrategia_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('estrategia_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('estrategia_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('estrategia_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('estrategia_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('estrategia_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('estrategia_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('estrategia_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('estrategia_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('estrategia_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('estrategia_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('estrategia_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('estrategia_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('estrategia_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('estrategia_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('estrategia_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('estrategia_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('estrategia_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('estrategia_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('estrategia_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('estrategia_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('estrategia_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('estrategia_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('estrategia_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('estrategia_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('estrategia_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('estrategia_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('estrategia_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('estrategia_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('estrategia_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('estrategia_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('estrategia_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('estrategia_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('estrategia_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('estrategia_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('estrategia_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('estrategia_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('estrategia_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('estrategia_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('estrategia_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('estrategia_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('estrategia_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('estrategia_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('estrategia_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('estrategia_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('estrategia_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('estrategia_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('estrategia_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

if ($filtro_prioridade_estrategia){
	$sql->esqUnir('priorizacao', 'priorizacao', 'estrategias.pg_estrategia_id=priorizacao_estrategia');
	if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_estrategia = estrategias.pg_estrategia_id AND priorizacao_modelo IN ('.$filtro_prioridade_estrategia.')) AS priorizacao');
	else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_estrategia = estrategias.pg_estrategia_id AND priorizacao_modelo IN ('.$filtro_prioridade_estrategia.')) AS priorizacao');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_estrategia.')');
	}
		
if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'estrategias.pg_estrategia_id=favorito_lista_campo');
	$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
	$sql->adOnde('favorito.favorito_id='.(int)$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_dept='.(int)$dept_id.' OR estrategias_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_dept IN ('.$lista_depts.') OR estrategias_depts.dept_id IN ('.$lista_depts.')');
	}
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('estrategia_cia', 'estrategia_cia', 'estrategias.pg_estrategia_id=estrategia_cia_estrategia');
	$sql->adOnde('pg_estrategia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR estrategia_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$favorito_id && !$lista_cias) $sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
elseif ($cia_id && !$favorito_id && $lista_cias) $sql->adOnde('pg_estrategia_cia IN ('.$lista_cias.')');

if ($pesquisar_texto) $sql->adOnde('pg_estrategia_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_estrategia_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pg_estrategia_codigo LIKE \'%'.$pesquisar_texto.'%\'');

	
if ($tab==0 && $a=='estrategia_lista') $sql->adOnde('pg_estrategia_percentagem < 100');
if ($tab==1 && $a=='estrategia_lista') $sql->adOnde('pg_estrategia_percentagem = 100');
if ($tab==2 && $a=='estrategia_lista') $sql->adOnde('pg_estrategia_ativo = 0');
else $sql->adOnde('pg_estrategia_ativo = 1');

if ($filtro_pg_id){
	$sql->esqUnir('plano_gestao_estrategias','plano_gestao_estrategias','plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_estrategias.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$filtro_pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('estrategias_usuarios', 'estrategias_usuarios', 'estrategias_usuarios.pg_estrategia_id = estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_usuario = '.(int)$usuario_id.' OR estrategias_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_estrategia_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_estrategia_descricao LIKE \'%'.$pesquisar_texto.'%\'');
if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_estrategia=estrategias.pg_estrategia_id) AS tem_aprovacao');

$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('estrategias.pg_estrategia_id');
$sql->setLimite($xmin, $xtamanhoPagina);
$estrategias=$sql->Lista();
$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['iniciativa']), ucfirst($config['iniciativas']),'','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" border=0 cellpadding="2" cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

if ($exibir['pg_estrategia_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Nome'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_aprovado'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_estrategia) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';

if ($exibir['pg_estrategia_descricao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Neste campo fica a descrição d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Descrição'.dicaF().'</a></th>';

if ($exibir['pg_estrategia_relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionad'.$config['genero_iniciativa'], 'Os objetos aos quais '.$config['genero_iniciativa'].' '.$config['iniciativa'].' está relacionad'.$config['genero_iniciativa'].'.').'Relacionad'.$config['genero_iniciativa'].dicaF().'</a></th>';



if ($exibir['pg_estrategia_inicio']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Início', 'A data de ínicio d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Início'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_fim']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_fim' ? imagem('icones/'.$seta[$ordem]) : '').dica('Término', 'A data de término d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Término'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_ano']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_ano&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_ano' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ano', 'O ano base dos'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Ano'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_codigo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_codigo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_codigo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Código', 'Os códigos d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Código'.dicaF().'</a></th>';


if ($exibir['pg_estrategia_oque']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_oque&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_oque' ? imagem('icones/'.$seta[$ordem]) : '').dica('O Que', 'Neste campo fica o que fazer d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'O Que'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_onde']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_onde&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_onde' ? imagem('icones/'.$seta[$ordem]) : '').dica('Onde ', 'Neste campo fica onde fazer '.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Onde'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_quando']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_quando&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_quando' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quando', 'Neste campo fica quando fazer '.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Quando'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_como']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_como&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_como' ? imagem('icones/'.$seta[$ordem]) : '').dica('Como', 'Neste campo fica como fazer '.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Como'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_porque']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_porque&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_porque' ? imagem('icones/'.$seta[$ordem]) : '').dica('Por Que', 'Neste campo fica por que fazer '.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Por Que'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_quanto']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_quanto&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_quanto' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quanto', 'Neste campo fica quanto custa '.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Quanto'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_quem']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_quem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_quem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quem', 'Neste campo fica quem faz '.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Quem'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_controle']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_controle&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_controle' ? imagem('icones/'.$seta[$ordem]) : '').dica('Controle', 'Neste campo fica o método de controle d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Controle'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_melhorias']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_melhorias&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_melhorias' ? imagem('icones/'.$seta[$ordem]) : '').dica('Melhorias', 'Neste campo fica as melhorias efetuadas n'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Melhorias'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_metodo_aprendizado']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_metodo_aprendizado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_metodo_aprendizado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprendizado', 'Neste campo fica o método de aprendizado n'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Aprendizado'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_desde_quando']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_desde_quando&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_desde_quando' ? imagem('icones/'.$seta[$ordem]) : '').dica('Desde Quando', 'Neste campo fica desde quando é realizad'.$config['genero_iniciativa'].' '.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Desde Quando'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_tipo_pontuacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_tipo_pontuacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_tipo_pontuacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Sistema de Pontuação', 'Neste campo fica o sistema de pontuação d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Sistema'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=pg_estrategia_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='pg_estrategia_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['pg_estrategia_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['pg_estrategia_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=pg_estrategia_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='pg_estrategia_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['pg_estrategia_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';

if ($exibir['pg_estrategia_usuario']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').'Responsável'.dicaF().'</a></th>';
if ($exibir['pg_estrategia_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Designados'.dicaF().'</th>';
if ($exibir['pg_estrategia_percentagem'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem d'.$config['genero_iniciativa'].' '.$config['iniciativa'].' executada.').'%'.dicaF().'</a></th>';

echo '</tr>';

$agora =date('Y-m-d');

$qnt=0;

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'iniciativa');

foreach ($estrategias as $linha) {
	if (permiteAcessarEstrategia($linha['pg_estrategia_acesso'],$linha['pg_estrategia_id'])){
		$editar=permiteEditarEstrategia($linha['pg_estrategia_acesso'],$linha['pg_estrategia_id']);
		if ($edicao && !$editar) continue;
		if ($Aplic->profissional) $bloquear=($linha['pg_estrategia_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
		else $bloquear=0;
		$estilo ='';
		if($linha['pg_estrategia_inicio'] && $linha['pg_estrategia_fim']){
			if ($agora < $linha['pg_estrategia_inicio'] && $linha['pg_estrategia_percentagem'] < 100) $estilo = 'style="background-color:#ffffff"';
			if ($agora > $linha['pg_estrategia_inicio'] && $agora < $linha['pg_estrategia_fim'] && $linha['pg_estrategia_percentagem'] > 0 && $linha['pg_estrategia_percentagem'] < 100 ) $estilo = 'style="background-color:#e6eedd"';
			if ($agora > $linha['pg_estrategia_inicio'] && $agora < $linha['pg_estrategia_fim'] && $linha['pg_estrategia_percentagem'] == 0) $estilo = 'style="background-color:#ffeebb"';
			if ($agora > $linha['pg_estrategia_fim'] && $linha['pg_estrategia_percentagem'] < 100) $estilo = 'style="background-color:#cc6666"';
			elseif ($linha['pg_estrategia_percentagem'] == 100) $estilo = 'style="background-color:#aaddaa"';
			}

		$qnt++;
		echo '<tr>';
		if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.($podeEditar && $editar && !$bloquear ? dica('Editar '.ucfirst($config['acao']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=estrategia_editar&pg_estrategia_id='.$linha['pg_estrategia_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['pg_estrategia_id'].'" value="'.$linha['pg_estrategia_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['pg_estrategia_id'].'" value="'.$linha['pg_estrategia_id'].'" '.(isset($selecionado[$linha['pg_estrategia_id']]) ? 'checked="checked"' : '').' />';

		if ($exibir['pg_estrategia_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pg_estrategia_cor'].'">&nbsp;&nbsp;</td>';
		if ($selecao) echo '<td id="pg_estrategia_nome_'.$linha['pg_estrategia_id'].'">'.$linha['pg_estrategia_nome'].'</td>';
		else echo '<td '.$estilo.' >'.link_estrategia($linha['pg_estrategia_id'],true).'</td>';
		
		if ($Aplic->profissional) echo '<td width=30 align="center">'.($linha['pg_estrategia_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
		if ($filtro_prioridade_estrategia) echo '<td align="right" style="white-space: nowrap" width=50>'.($linha['priorizacao']).'</td>';

		if ($exibir['pg_estrategia_descricao']) echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_descricao'] ? $linha['pg_estrategia_descricao']: '&nbsp;').'</td>';
		
		
		if ($exibir['pg_estrategia_relacionado']){
			$sql->adTabela('estrategia_gestao');
			$sql->adCampo('estrategia_gestao.*');
			$sql->adOnde('estrategia_gestao_estrategia ='.(int)$linha['pg_estrategia_id']);	
			$sql->adOrdem('estrategia_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['estrategia_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['estrategia_gestao_tarefa']);
					elseif ($gestao_data['estrategia_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['estrategia_gestao_projeto']);
					elseif ($gestao_data['estrategia_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['estrategia_gestao_pratica']);
					elseif ($gestao_data['estrategia_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['estrategia_gestao_acao']);
					elseif ($gestao_data['estrategia_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['estrategia_gestao_perspectiva']);
					elseif ($gestao_data['estrategia_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['estrategia_gestao_tema']);
					elseif ($gestao_data['estrategia_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['estrategia_gestao_objetivo']);
					elseif ($gestao_data['estrategia_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['estrategia_gestao_fator']);
					
					elseif ($gestao_data['estrategia_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['estrategia_gestao_semelhante']);
					
					elseif ($gestao_data['estrategia_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['estrategia_gestao_meta']);
					elseif ($gestao_data['estrategia_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['estrategia_gestao_canvas']);
					elseif ($gestao_data['estrategia_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['estrategia_gestao_risco']);
					elseif ($gestao_data['estrategia_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['estrategia_gestao_risco_resposta']);
					elseif ($gestao_data['estrategia_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['estrategia_gestao_indicador']);
					elseif ($gestao_data['estrategia_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['estrategia_gestao_calendario']);
					elseif ($gestao_data['estrategia_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['estrategia_gestao_monitoramento']);
					elseif ($gestao_data['estrategia_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['estrategia_gestao_ata']);
					elseif ($gestao_data['estrategia_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['estrategia_gestao_mswot']);
					elseif ($gestao_data['estrategia_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['estrategia_gestao_swot']);
					elseif ($gestao_data['estrategia_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['estrategia_gestao_operativo']);
					elseif ($gestao_data['estrategia_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['estrategia_gestao_instrumento']);
					elseif ($gestao_data['estrategia_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['estrategia_gestao_recurso']);
					elseif ($gestao_data['estrategia_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['estrategia_gestao_problema']);
					elseif ($gestao_data['estrategia_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['estrategia_gestao_demanda']);	
					elseif ($gestao_data['estrategia_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['estrategia_gestao_programa']);
					elseif ($gestao_data['estrategia_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['estrategia_gestao_licao']);
					elseif ($gestao_data['estrategia_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['estrategia_gestao_evento']);
					elseif ($gestao_data['estrategia_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['estrategia_gestao_link']);
					elseif ($gestao_data['estrategia_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['estrategia_gestao_avaliacao']);
					elseif ($gestao_data['estrategia_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['estrategia_gestao_tgn']);
					elseif ($gestao_data['estrategia_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['estrategia_gestao_brainstorm']);
					elseif ($gestao_data['estrategia_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['estrategia_gestao_gut']);
					elseif ($gestao_data['estrategia_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['estrategia_gestao_causa_efeito']);
					elseif ($gestao_data['estrategia_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['estrategia_gestao_arquivo']);
					elseif ($gestao_data['estrategia_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['estrategia_gestao_forum']);
					elseif ($gestao_data['estrategia_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['estrategia_gestao_checklist']);
					elseif ($gestao_data['estrategia_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['estrategia_gestao_agenda']);
					elseif ($gestao_data['estrategia_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['estrategia_gestao_agrupamento']);
					elseif ($gestao_data['estrategia_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['estrategia_gestao_patrocinador']);
					elseif ($gestao_data['estrategia_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['estrategia_gestao_template']);
					elseif ($gestao_data['estrategia_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['estrategia_gestao_painel']);
					elseif ($gestao_data['estrategia_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['estrategia_gestao_painel_odometro']);
					elseif ($gestao_data['estrategia_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['estrategia_gestao_painel_composicao']);		
					elseif ($gestao_data['estrategia_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['estrategia_gestao_tr']);	
					elseif ($gestao_data['estrategia_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['estrategia_gestao_me']);	
					elseif ($gestao_data['estrategia_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['estrategia_gestao_acao_item']);	
					elseif ($gestao_data['estrategia_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['estrategia_gestao_beneficio']);	
					elseif ($gestao_data['estrategia_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['estrategia_gestao_painel_slideshow']);	
					elseif ($gestao_data['estrategia_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['estrategia_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['estrategia_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['estrategia_gestao_projeto_abertura']);	
					elseif ($gestao_data['estrategia_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['estrategia_gestao_plano_gestao']);	
					elseif ($gestao_data['estrategia_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['estrategia_gestao_ssti']);	
					elseif ($gestao_data['estrategia_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['estrategia_gestao_laudo']);	
					elseif ($gestao_data['estrategia_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['estrategia_gestao_trelo']);	
					elseif ($gestao_data['estrategia_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['estrategia_gestao_trelo_cartao']);	
					elseif ($gestao_data['estrategia_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['estrategia_gestao_pdcl']);	
					elseif ($gestao_data['estrategia_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['estrategia_gestao_pdcl_item']);	
				
					}
				}	
			echo '</td>';	
			}	
		
		
		
			
		if ($exibir['pg_estrategia_inicio'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_inicio'] ? retorna_data($linha['pg_estrategia_inicio'], false): '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_fim'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_fim'] ? retorna_data($linha['pg_estrategia_fim'], false): '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_ano'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_ano'] ? $linha['pg_estrategia_ano'] : '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_codigo'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_codigo'] ? $linha['pg_estrategia_codigo'] : '&nbsp;').'</td>';
		
		
		
		if ($exibir['pg_estrategia_oque']) echo '<td>'.($linha['pg_estrategia_oque'] ? $linha['pg_estrategia_oque']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_onde']) echo '<td>'.($linha['pg_estrategia_onde'] ? $linha['pg_estrategia_onde']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_quando']) echo '<td>'.($linha['pg_estrategia_quando'] ? $linha['pg_estrategia_quando']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_como']) echo '<td>'.($linha['pg_estrategia_como'] ? $linha['pg_estrategia_como']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_porque']) echo '<td>'.($linha['pg_estrategia_porque'] ? $linha['pg_estrategia_porque']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_quanto']) echo '<td>'.($linha['pg_estrategia_quanto'] ? $linha['pg_estrategia_quanto']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_quem']) echo '<td>'.($linha['pg_estrategia_quem'] ? $linha['pg_estrategia_quem']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_controle']) echo '<td>'.($linha['pg_estrategia_controle'] ? $linha['pg_estrategia_controle']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_melhorias']) echo '<td>'.($linha['pg_estrategia_melhorias'] ? $linha['pg_estrategia_melhorias']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_metodo_aprendizado']) echo '<td>'.($linha['pg_estrategia_metodo_aprendizado'] ? $linha['pg_estrategia_metodo_aprendizado']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_desde_quando']) echo '<td>'.($linha['pg_estrategia_desde_quando'] ? $linha['pg_estrategia_desde_quando']: '&nbsp;').'</td>';
		if ($exibir['pg_estrategia_tipo_pontuacao']) echo '<td>'.(isset($tipo_pontuacao[$linha['pg_estrategia_tipo_pontuacao']]) ? $tipo_pontuacao[$linha['pg_estrategia_tipo_pontuacao']] : '&nbsp;').'</td>';

		if ($exibir['pg_estrategia_cia']) echo '<td>'.link_cia($linha['pg_estrategia_cia']).'</td>';
		if ($exibir['pg_estrategia_cias']){
			$sql->adTabela('estrategia_cia');
			$sql->adCampo('estrategia_cia_cia');
			$sql->adOnde('estrategia_cia_estrategia = '.(int)$linha['pg_estrategia_id']);
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
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['pg_estrategia_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['pg_estrategia_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
		if ($exibir['pg_estrategia_dept']) echo '<td>'.link_secao($linha['pg_estrategia_dept']).'</td>';	
		if ($exibir['pg_estrategia_depts']){
			$sql->adTabela('estrategias_depts');
			$sql->adCampo('dept_id');
			$sql->adOnde('pg_estrategia_id = '.(int)$linha['pg_estrategia_id']);
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
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['pg_estrategia_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['pg_estrategia_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
		
		
		if ($exibir['pg_estrategia_usuario']) echo '<td>'.link_usuario($linha['pg_estrategia_usuario'],'','','esquerda').'</td>';
		
		if ($exibir['pg_estrategia_designados']) {
			$sql->adTabela('estrategias_usuarios');
			$sql->adCampo('usuario_id');
			$sql->adOnde('pg_estrategia_id = '.(int)$linha['pg_estrategia_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['pg_estrategia_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['pg_estrategia_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
		
		if ($exibir['pg_estrategia_percentagem'] && $Aplic->profissional) echo '<td style="white-space: nowrap" align=right width=30>'.number_format($linha['pg_estrategia_percentagem'], 2, ',', '.').'</td>';
		
		
		echo '</tr>';
		}
	}
	
	
if (!count($estrategias)) echo '<tr><td colspan=20><p>Nenhum'.($config['genero_iniciativa']=='a' ? 'a' : '').' '.$config['iniciativa'].' encontrad'.$config['genero_iniciativa'].'.</p></td></tr>';
elseif(count($estrategias) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.</p></td></tr>';	
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS();').'</td></tr></table></td></tr>';
	
echo '</table>';

if (!$selecao){
	echo '<table border=0 cellpadding=2 cellspacing=2 '.($dialogo ? '' : 'class="std"').' width="100%"><tr>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#ffffff">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Futur'.$config['genero_iniciativa'], ucfirst($config['iniciativa']).' futur'.$config['genero_iniciativa'].' é '.$config['genero_iniciativa'].' que a data de ínicio ainda não ocorreu.').ucfirst($config['iniciativa']).' futur'.$config['genero_iniciativa'].dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#e6eedd">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Iniciad'.$config['genero_iniciativa'].' e Dentro do Prazo', ucfirst($config['iniciativa']).' iniciad'.$config['genero_iniciativa'].' e dentro do prazo é '.$config['genero_iniciativa'].' que a data de ínicio já ocorreu, e já está acima de 0% executada, entretanto ainda não se chegou na data de término.').'Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#ffeebb">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' que Deveria ter Iniciad'.$config['genero_iniciativa'], ucfirst($config['iniciativa']).' deveria ter iniciad'.$config['genero_iniciativa'].' é '.$config['genero_iniciativa'].' que a data de ínicio d'.$config['genero_iniciativa'].' mesm'.$config['genero_iniciativa'].' já ocorreu, entretanto ainda se encontra em 0% executad'.$config['genero_iniciativa'].'.').'Deveria ter iniciad'.$config['genero_iniciativa'].''.dicaF().'</td><td>&nbsp; &nbsp;</td>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#cc6666">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Atrasad'.$config['genero_iniciativa'], ucfirst($config['iniciativa']).' em atraso é '.$config['genero_iniciativa'].' que a data de término d'.$config['genero_iniciativa'].' mesm'.$config['genero_iniciativa'].' já ocorreu, entretanto ainda não se encontra em 100% executad'.$config['genero_iniciativa'].'.').'Em atraso'.dicaF().'</td>';
	echo '<td style="white-space: nowrap; border-style:solid;border-width:1px" bgcolor="#aaddaa">&nbsp; &nbsp;</td><td style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Terminad'.$config['genero_iniciativa'], ucfirst($config['iniciativa']).' terminad'.$config['genero_iniciativa'].' é é quando está 100% executad'.$config['genero_iniciativa'].'.').'Terminad'.$config['genero_iniciativa'].dicaF().'</td>';
	echo '<td width="100%">&nbsp;</td>';
	echo '</tr></table>';
	}

?>
<script type="text/javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
function selecionar(pg_estrategia_id){
	var nome=document.getElementById('pg_estrategia_nome_'+pg_estrategia_id).innerHTML;
	setFechar(pg_estrategia_id, nome);
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
	
</script>	