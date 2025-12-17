<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $estilo_interface, $sql, $perms, $dialogo, $Aplic, $cia_id, $lista_cias, $dept_id, $lista_depts, $tab,  $favorito_id, $usuario_id, $pesquisar_texto, $filtro_pg_id, $favorito_id, $filtro_prioridade_perspectiva,
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


$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'perspectiva');

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$pagina=getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_perspectivas']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$ordenar=getParam($_REQUEST, 'ordenar', 'pg_perspectiva_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');
if(empty($ordem)) $ordem = '0';

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
$sql->adOnde('campo_formulario_tipo = \'perspectivas\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'perspectivas\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}


$sql->adTabela('perspectivas');
$sql->adCampo('count(DISTINCT perspectivas.pg_perspectiva_id)');


$sql->esqUnir('perspectiva_gestao','perspectiva_gestao','perspectiva_gestao_perspectiva = perspectivas.pg_perspectiva_id');
if ($tarefa_id) $sql->adOnde('perspectiva_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=perspectiva_gestao_tarefa');
	$sql->adOnde('perspectiva_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}

elseif ($pg_perspectiva_id) $sql->adOnde('perspectiva_gestao_semelhante IN ('.$pg_perspectiva_id.')');

elseif ($tema_id) $sql->adOnde('perspectiva_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('perspectiva_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('perspectiva_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('perspectiva_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('perspectiva_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('perspectiva_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('perspectiva_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('perspectiva_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('perspectiva_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('perspectiva_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('perspectiva_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('perspectiva_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('perspectiva_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('perspectiva_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('perspectiva_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('perspectiva_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('perspectiva_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('perspectiva_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('perspectiva_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('perspectiva_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('perspectiva_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('perspectiva_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('perspectiva_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('perspectiva_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('perspectiva_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('perspectiva_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('perspectiva_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('perspectiva_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('perspectiva_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('perspectiva_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('perspectiva_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('perspectiva_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('perspectiva_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('perspectiva_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('perspectiva_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('perspectiva_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('perspectiva_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('perspectiva_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('perspectiva_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('perspectiva_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('perspectiva_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('perspectiva_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('perspectiva_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('perspectiva_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('perspectiva_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('perspectiva_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('perspectiva_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('perspectiva_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('perspectiva_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('perspectiva_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('perspectiva_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('perspectiva_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('perspectiva_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('perspectiva_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
if ($filtro_prioridade_perspectiva){
	$sql->esqUnir('priorizacao', 'priorizacao', 'perspectivas.pg_perspectiva_id=priorizacao_perspectiva');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_perspectiva.')');
	}

if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'perspectivas.pg_perspectiva_id=favorito_lista_campo');
	$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
	$sql->adOnde('favorito.favorito_id='.(int)$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('perspectivas_depts','perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adOnde('pg_perspectiva_dept='.(int)$dept_id.' OR perspectivas_depts.dept_id='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('perspectivas_depts','perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adOnde('pg_perspectiva_dept IN ('.$lista_depts.') OR perspectivas_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('perspectiva_cia', 'perspectiva_cia', 'perspectivas.pg_perspectiva_id=perspectiva_cia_perspectiva');
	$sql->adOnde('pg_perspectiva_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR perspectiva_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_perspectiva_cia='.(int)$cia_id);
elseif  ($lista_cias) $sql->adOnde('pg_perspectiva_cia IN ('.$lista_cias.')');

if ($tab==0 || $a!='perspectiva_lista') $sql->adOnde('pg_perspectiva_ativo=1');
elseif ($tab==1) $sql->adOnde('pg_perspectiva_ativo!=1 OR pg_perspectiva_ativo IS NULL');	
if ($usuario_id) {
	$sql->esqUnir('perspectivas_usuarios', 'perspectivas_usuarios', 'perspectivas_usuarios.pg_perspectiva_id = perspectivas.pg_perspectiva_id');
	$sql->adOnde('pg_perspectiva_usuario = '.(int)$usuario_id.' OR perspectivas_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_perspectiva_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_perspectiva_descricao LIKE \'%'.$pesquisar_texto.'%\'');

if ($filtro_pg_id){
	$sql->esqUnir('plano_gestao_perspectivas','plano_gestao_perspectivas','plano_gestao_perspectivas.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_perspectivas.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$filtro_pg_id);
	}
$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('perspectivas');
$sql->adCampo('perspectivas.*');


$sql->esqUnir('perspectiva_gestao','perspectiva_gestao','perspectiva_gestao_perspectiva = perspectivas.pg_perspectiva_id');
if ($tarefa_id) $sql->adOnde('perspectiva_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=perspectiva_gestao_tarefa');
	$sql->adOnde('perspectiva_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}

elseif ($pg_perspectiva_id) $sql->adOnde('perspectiva_gestao_semelhante IN ('.$pg_perspectiva_id.')');

elseif ($tema_id) $sql->adOnde('perspectiva_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('perspectiva_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('perspectiva_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('perspectiva_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('perspectiva_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('perspectiva_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('perspectiva_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('perspectiva_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('perspectiva_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('perspectiva_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('perspectiva_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('perspectiva_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('perspectiva_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('perspectiva_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('perspectiva_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('perspectiva_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('perspectiva_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('perspectiva_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('perspectiva_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('perspectiva_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('perspectiva_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('perspectiva_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('perspectiva_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('perspectiva_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('perspectiva_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('perspectiva_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('perspectiva_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('perspectiva_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('perspectiva_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('perspectiva_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('perspectiva_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('perspectiva_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('perspectiva_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('perspectiva_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('perspectiva_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('perspectiva_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('perspectiva_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('perspectiva_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('perspectiva_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('perspectiva_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('perspectiva_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('perspectiva_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('perspectiva_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('perspectiva_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('perspectiva_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('perspectiva_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('perspectiva_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('perspectiva_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('perspectiva_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('perspectiva_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('perspectiva_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('perspectiva_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('perspectiva_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('perspectiva_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

if ($filtro_prioridade_perspectiva){
	$sql->esqUnir('priorizacao', 'priorizacao', 'perspectivas.pg_perspectiva_id=priorizacao_perspectiva');
	if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_perspectiva = perspectivas.pg_perspectiva_id AND priorizacao_modelo IN ('.$filtro_prioridade_perspectiva.')) AS priorizacao');
	else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_perspectiva = perspectivas.pg_perspectiva_id AND priorizacao_modelo IN ('.$filtro_prioridade_perspectiva.')) AS priorizacao');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_perspectiva.')');
	}

if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'perspectivas.pg_perspectiva_id=favorito_lista_campo');
	$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
	$sql->adOnde('favorito.favorito_id='.(int)$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('perspectivas_depts','perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adOnde('pg_perspectiva_dept='.(int)$dept_id.' OR perspectivas_depts.dept_id='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('perspectivas_depts','perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adOnde('pg_perspectiva_dept IN ('.$lista_depts.') OR perspectivas_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('perspectiva_cia', 'perspectiva_cia', 'perspectivas.pg_perspectiva_id=perspectiva_cia_perspectiva');
	$sql->adOnde('pg_perspectiva_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR perspectiva_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_perspectiva_cia='.(int)$cia_id);
elseif  ($lista_cias) $sql->adOnde('pg_perspectiva_cia IN ('.$lista_cias.')');


if ($tab==0 || $a!='perspectiva_lista') $sql->adOnde('pg_perspectiva_ativo=1');
elseif ($tab==1) $sql->adOnde('pg_perspectiva_ativo!=1 OR pg_perspectiva_ativo IS NULL');

if ($filtro_pg_id){
	$sql->esqUnir('plano_gestao_perspectivas','plano_gestao_perspectivas','plano_gestao_perspectivas.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_perspectivas.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$filtro_pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('perspectivas_usuarios', 'perspectivas_usuarios', 'perspectivas_usuarios.pg_perspectiva_id = perspectivas.pg_perspectiva_id');
	$sql->adOnde('pg_perspectiva_usuario = '.(int)$usuario_id.' OR perspectivas_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_perspectiva_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_perspectiva_descricao LIKE \'%'.$pesquisar_texto.'%\'');

$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_perspectiva=perspectivas.pg_perspectiva_id) AS tem_aprovacao');
$sql->adGrupo('perspectivas.pg_perspectiva_id');
$sql->setLimite($xmin, $xtamanhoPagina);

$perspectivas=$sql->Lista();
$sql->limpar();



$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['perspectiva']), ucfirst($config['perspectivas']),'','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$impressao && !$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

if ($exibir['pg_perspectiva_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor d'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']).'', 'Neste campo fica a cor de identificação d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome d'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']).'', 'Neste campo fica um nome para identificação d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Nome'.dicaF().'</a></th>';

if ($exibir['pg_perspectiva_aprovado'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovad'.$config['genero_perspectiva'], 'Neste campo consta se '.$config['genero_perspectiva'].' '.$config['perspectiva'].' foi aprovad'.$config['genero_perspectiva'].'.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_perspectiva) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_descricao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição d'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']).'', 'Neste campo fica a descrição d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Descrição'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_oque']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_oque&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_oque' ? imagem('icones/'.$seta[$ordem]) : '').dica('O Que', 'Neste campo fica o que fazer d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'O Que'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_onde']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_onde&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_onde' ? imagem('icones/'.$seta[$ordem]) : '').dica('Onde ', 'Neste campo fica onde fazer '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Onde'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_quando']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_quando&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_quando' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quando', 'Neste campo fica quando fazer '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Quando'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_como']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_como&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_como' ? imagem('icones/'.$seta[$ordem]) : '').dica('Como', 'Neste campo fica como fazer '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Como'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_porque']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_porque&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_porque' ? imagem('icones/'.$seta[$ordem]) : '').dica('Por Que', 'Neste campo fica por que fazer '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Por Que'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_quanto']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_quanto&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_quanto' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quanto', 'Neste campo fica quanto custa '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Quanto'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_quem']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_quem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_quem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quem', 'Neste campo fica quem faz '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Quem'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_controle']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_controle&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_controle' ? imagem('icones/'.$seta[$ordem]) : '').dica('Controle', 'Neste campo fica o método de controle d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Controle'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_melhorias']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_melhorias&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_melhorias' ? imagem('icones/'.$seta[$ordem]) : '').dica('Melhorias', 'Neste campo fica as melhorias efetuadas n'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Melhorias'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_metodo_aprendizado']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_metodo_aprendizado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_metodo_aprendizado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprendizado', 'Neste campo fica o método de aprendizado n'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Aprendizado'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_desde_quando']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_desde_quando&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_desde_quando' ? imagem('icones/'.$seta[$ordem]) : '').dica('Desde Quando', 'Neste campo fica desde quando é realizad'.$config['genero_perspectiva'].' '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Desde Quando'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_tipo_pontuacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_tipo_pontuacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_tipo_pontuacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Sistema de Pontuação', 'Neste campo fica o sistema de pontuação d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Sistema'.dicaF().'</a></th>';

if ($exibir['pg_perspectiva_relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionad'.$config['genero_perspectiva'], 'A que área '.$config['genero_perspectiva'].' '.$config['perspectiva'].' está relacionad'.$config['genero_perspectiva'].'.').'Relacionad'.$config['genero_perspectiva'].dicaF().'</th>';



if ($exibir['pg_perspectiva_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=pg_perspectiva_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='pg_perspectiva_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['pg_perspectiva_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['pg_perspectiva_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=pg_perspectiva_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='pg_perspectiva_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['pg_perspectiva_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';

if ($exibir['pg_perspectiva_usuario']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'Responsável'.dicaF().'</a></th>';
if ($exibir['pg_perspectiva_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_perspectiva'].'s '.$config['perspectivas'].'.').'Designados'.dicaF().'</th>';



if ($exibir['pg_perspectiva_percentagem'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_perspectiva_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_perspectiva_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem d'.$config['genero_perspectiva'].' '.$config['perspectiva'].' executada.').'%'.dicaF().'</a></th>';
echo '</tr>';
$qnt=0;

foreach ($perspectivas as $linha) {
	if (permiteAcessarPerspectiva($linha['pg_perspectiva_acesso'],$linha['pg_perspectiva_id'])){
		$qnt++;
		$editar=permiteEditarPerspectiva($linha['pg_perspectiva_acesso'],$linha['pg_perspectiva_id']);
		
		if ($edicao && !$editar) continue;
		
		if ($Aplic->profissional) $bloquear=($linha['pg_perspectiva_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
		else $bloquear=0;
		 
		
		echo '<tr>';
		if (!$impressao && !$dialogo) echo '<td style="white-space: nowrap" width="16">'.($podeEditar && $editar && !$bloquear ? dica('Editar '.ucfirst($config['perspectiva']).'', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=perspectiva_editar&pg_perspectiva_id='.$linha['pg_perspectiva_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['pg_perspectiva_id'].'" value="'.$linha['pg_perspectiva_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['pg_perspectiva_id'].'" value="'.$linha['pg_perspectiva_id'].'" '.(isset($selecionado[$linha['pg_perspectiva_id']]) ? 'checked="checked"' : '').' />';

		if ($exibir['pg_perspectiva_cor']) echo '<td id="ignore_td_" width="16" align="right" style="background-color:#'.$linha['pg_perspectiva_cor'].'"><font color="'.melhorCor($linha['pg_perspectiva_cor']).'">&nbsp;&nbsp;</font></td>';
		
		if ($selecao) echo '<td id="perspectiva_nome_'.$linha['pg_perspectiva_id'].'">'.$linha['pg_perspectiva_nome'].'</td>';
		else echo '<td>'.link_perspectiva($linha['pg_perspectiva_id']).'</td>';
		
		if ($exibir['pg_perspectiva_aprovado'] && $Aplic->profissional) echo '<td width=30 align="center">'.($linha['pg_perspectiva_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
		
		if ($filtro_prioridade_perspectiva) echo '<td align="right" style="white-space: nowrap" width=50>'.($linha['priorizacao']).'</td>';

		if ($exibir['pg_perspectiva_descricao']) echo '<td>'.($linha['pg_perspectiva_descricao'] ? $linha['pg_perspectiva_descricao']: '&nbsp;').'</td>';
		
		if ($exibir['pg_perspectiva_oque']) echo '<td>'.($linha['pg_perspectiva_oque'] ? $linha['pg_perspectiva_oque']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_onde']) echo '<td>'.($linha['pg_perspectiva_onde'] ? $linha['pg_perspectiva_onde']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_quando']) echo '<td>'.($linha['pg_perspectiva_quando'] ? $linha['pg_perspectiva_quando']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_como']) echo '<td>'.($linha['pg_perspectiva_como'] ? $linha['pg_perspectiva_como']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_porque']) echo '<td>'.($linha['pg_perspectiva_porque'] ? $linha['pg_perspectiva_porque']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_quanto']) echo '<td>'.($linha['pg_perspectiva_quanto'] ? $linha['pg_perspectiva_quanto']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_quem']) echo '<td>'.($linha['pg_perspectiva_quem'] ? $linha['pg_perspectiva_quem']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_controle']) echo '<td>'.($linha['pg_perspectiva_controle'] ? $linha['pg_perspectiva_controle']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_melhorias']) echo '<td>'.($linha['pg_perspectiva_melhorias'] ? $linha['pg_perspectiva_melhorias']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_metodo_aprendizado']) echo '<td>'.($linha['pg_perspectiva_metodo_aprendizado'] ? $linha['pg_perspectiva_metodo_aprendizado']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_desde_quando']) echo '<td>'.($linha['pg_perspectiva_desde_quando'] ? $linha['pg_perspectiva_desde_quando']: '&nbsp;').'</td>';
		if ($exibir['pg_perspectiva_tipo_pontuacao']) echo '<td>'.(isset($tipo_pontuacao[$linha['pg_perspectiva_tipo_pontuacao']]) ? $tipo_pontuacao[$linha['pg_perspectiva_tipo_pontuacao']] : '&nbsp;').'</td>';

		if ($exibir['pg_perspectiva_relacionado']){
			$sql->adTabela('perspectiva_gestao');
			$sql->adCampo('perspectiva_gestao.*');
			$sql->adOnde('perspectiva_gestao_perspectiva ='.(int)$linha['pg_perspectiva_id']);	
			$sql->adOrdem('perspectiva_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['perspectiva_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['perspectiva_gestao_tarefa']);
					elseif ($gestao_data['perspectiva_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['perspectiva_gestao_projeto']);
					elseif ($gestao_data['perspectiva_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['perspectiva_gestao_pratica']);
					elseif ($gestao_data['perspectiva_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['perspectiva_gestao_acao']);
					
					elseif ($gestao_data['perspectiva_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['perspectiva_gestao_semelhante']);
					
					elseif ($gestao_data['perspectiva_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['perspectiva_gestao_tema']);
					elseif ($gestao_data['perspectiva_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['perspectiva_gestao_objetivo']);
					elseif ($gestao_data['perspectiva_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['perspectiva_gestao_fator']);
					elseif ($gestao_data['perspectiva_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['perspectiva_gestao_estrategia']);
					elseif ($gestao_data['perspectiva_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['perspectiva_gestao_meta']);
					elseif ($gestao_data['perspectiva_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['perspectiva_gestao_canvas']);
					elseif ($gestao_data['perspectiva_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['perspectiva_gestao_risco']);
					elseif ($gestao_data['perspectiva_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['perspectiva_gestao_risco_resposta']);
					elseif ($gestao_data['perspectiva_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['perspectiva_gestao_indicador']);
					elseif ($gestao_data['perspectiva_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['perspectiva_gestao_calendario']);
					elseif ($gestao_data['perspectiva_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['perspectiva_gestao_monitoramento']);
					elseif ($gestao_data['perspectiva_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['perspectiva_gestao_ata']);
					elseif ($gestao_data['perspectiva_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['perspectiva_gestao_mswot']);
					elseif ($gestao_data['perspectiva_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['perspectiva_gestao_swot']);
					elseif ($gestao_data['perspectiva_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['perspectiva_gestao_operativo']);
					elseif ($gestao_data['perspectiva_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['perspectiva_gestao_instrumento']);
					elseif ($gestao_data['perspectiva_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['perspectiva_gestao_recurso']);
					elseif ($gestao_data['perspectiva_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['perspectiva_gestao_problema']);
					elseif ($gestao_data['perspectiva_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['perspectiva_gestao_demanda']);	
					elseif ($gestao_data['perspectiva_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['perspectiva_gestao_programa']);
					elseif ($gestao_data['perspectiva_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['perspectiva_gestao_licao']);
					elseif ($gestao_data['perspectiva_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['perspectiva_gestao_evento']);
					elseif ($gestao_data['perspectiva_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['perspectiva_gestao_link']);
					elseif ($gestao_data['perspectiva_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['perspectiva_gestao_avaliacao']);
					elseif ($gestao_data['perspectiva_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['perspectiva_gestao_tgn']);
					elseif ($gestao_data['perspectiva_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['perspectiva_gestao_brainstorm']);
					elseif ($gestao_data['perspectiva_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['perspectiva_gestao_gut']);
					elseif ($gestao_data['perspectiva_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['perspectiva_gestao_causa_efeito']);
					elseif ($gestao_data['perspectiva_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['perspectiva_gestao_arquivo']);
					elseif ($gestao_data['perspectiva_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['perspectiva_gestao_forum']);
					elseif ($gestao_data['perspectiva_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['perspectiva_gestao_checklist']);
					elseif ($gestao_data['perspectiva_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['perspectiva_gestao_agenda']);
					elseif ($gestao_data['perspectiva_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['perspectiva_gestao_agrupamento']);
					elseif ($gestao_data['perspectiva_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['perspectiva_gestao_patrocinador']);
					elseif ($gestao_data['perspectiva_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['perspectiva_gestao_template']);
					elseif ($gestao_data['perspectiva_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['perspectiva_gestao_painel']);
					elseif ($gestao_data['perspectiva_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['perspectiva_gestao_painel_odometro']);
					elseif ($gestao_data['perspectiva_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['perspectiva_gestao_painel_composicao']);		
					elseif ($gestao_data['perspectiva_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['perspectiva_gestao_tr']);	
					elseif ($gestao_data['perspectiva_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['perspectiva_gestao_me']);	
					elseif ($gestao_data['perspectiva_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['perspectiva_gestao_acao_item']);	
					elseif ($gestao_data['perspectiva_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['perspectiva_gestao_beneficio']);	
					elseif ($gestao_data['perspectiva_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['perspectiva_gestao_painel_slideshow']);	
					elseif ($gestao_data['perspectiva_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['perspectiva_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['perspectiva_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['perspectiva_gestao_projeto_abertura']);	
					elseif ($gestao_data['perspectiva_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['perspectiva_gestao_plano_gestao']);	
					elseif ($gestao_data['perspectiva_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['perspectiva_gestao_ssti']);	
					elseif ($gestao_data['perspectiva_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['perspectiva_gestao_laudo']);	
					elseif ($gestao_data['perspectiva_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['perspectiva_gestao_trelo']);	
					elseif ($gestao_data['perspectiva_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['perspectiva_gestao_trelo_cartao']);	
					elseif ($gestao_data['perspectiva_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['perspectiva_gestao_pdcl']);	
					elseif ($gestao_data['perspectiva_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['perspectiva_gestao_pdcl_item']);	
					}
				}	
			echo '</td>';	
			}	


		if ($exibir['pg_perspectiva_cia']) echo '<td>'.link_cia($linha['pg_perspectiva_cia']).'</td>';
		if ($exibir['pg_perspectiva_cias']){
			$sql->adTabela('perspectiva_cia');
			$sql->adCampo('perspectiva_cia_cia');
			$sql->adOnde('perspectiva_cia_perspectiva = '.(int)$linha['pg_perspectiva_id']);
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
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['pg_perspectiva_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['pg_perspectiva_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
		if ($exibir['pg_perspectiva_dept']) echo '<td>'.link_secao($linha['pg_perspectiva_dept']).'</td>';	
		if ($exibir['pg_perspectiva_depts']){
			$sql->adTabela('perspectivas_depts');
			$sql->adCampo('dept_id');
			$sql->adOnde('pg_perspectiva_id = '.(int)$linha['pg_perspectiva_id']);
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
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['pg_perspectiva_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['pg_perspectiva_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
			
		
		if ($exibir['pg_perspectiva_usuario'])echo '<td>'.link_usuario($linha['pg_perspectiva_usuario'],'','','esquerda').'</td>';
		
		if ($exibir['pg_perspectiva_designados']){
			$sql->adTabela('perspectivas_usuarios');
			$sql->adCampo('usuario_id');
			$sql->adOnde('pg_perspectiva_id = '.(int)$linha['pg_perspectiva_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['pg_perspectiva_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['pg_perspectiva_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}

		if ($exibir['pg_perspectiva_percentagem'] && $Aplic->profissional) echo '<td style="white-space: nowrap" align=right width=30>'.number_format($linha['pg_perspectiva_percentagem'], 2, ',', '.').'</td>';
		
		echo '</tr>';
		}
	}
if (!count($perspectivas)) echo '<tr><td colspan="8"><p>Nenh'.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].' encontrad'.$config['genero_perspectiva'].'.</p></td></tr>';
elseif(count($perspectivas) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer d'.$config['genero_perspectiva'].'s '.$config['perspectivas'].'.</p></td></tr>';
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS()').'</td></tr></table></td></tr>';
echo '</table>';

?>
<script type="text/javascript">
	
function selecionar(pg_perspectiva_id){
	var nome=document.getElementById('perspectiva_nome_'+pg_perspectiva_id).innerHTML;
	setFechar(pg_perspectiva_id, nome);
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