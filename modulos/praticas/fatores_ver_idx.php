<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $estilo_interface, $sql, $perms, $dialogo, $Aplic, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $favorito_id, $usuario_id, $pesquisar_texto, $filtro_pg_id, $filtro_prioridade_fator,
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

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_fatores']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$ordenar=getParam($_REQUEST, 'ordenar', 'fator_nome');
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
$sql->adOnde('campo_formulario_tipo = \'fatores\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'fatores\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}

$sql->adTabela('fator');
$sql->adCampo('count(DISTINCT fator.fator_id)');

$sql->esqUnir('fator_gestao','fator_gestao','fator_gestao_fator = fator.fator_id');
if ($tarefa_id) $sql->adOnde('fator_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=fator_gestao_tarefa');
	$sql->adOnde('fator_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('fator_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('fator_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('fator_gestao_objetivo IN ('.$objetivo_id.')');

elseif ($fator_id) $sql->adOnde('fator_gestao_semelhante IN ('.$fator_id.')');

elseif ($pg_estrategia_id) $sql->adOnde('fator_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('fator_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('fator_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('fator_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('fator_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('fator_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('fator_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('fator_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('fator_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('fator_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('fator_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('fator_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('fator_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('fator_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('fator_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('fator_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('fator_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('fator_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('fator_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('fator_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('fator_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('fator_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('fator_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('fator_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('fator_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('fator_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('fator_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('fator_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('fator_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('fator_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('fator_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('fator_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('fator_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('fator_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('fator_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('fator_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('fator_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('fator_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('fator_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('fator_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('fator_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('fator_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('fator_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('fator_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('fator_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('fator_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('fator_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('fator_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('fator_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('fator_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('fator_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

if ($filtro_prioridade_fator){
	$sql->esqUnir('priorizacao', 'priorizacao', 'fator.fator_id=priorizacao_fator');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_fator.')');
	}
	
if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'fator.fator_id=favorito_lista_campo');
	$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
	$sql->adOnde('favorito.favorito_id='.(int)$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('fator_dept','fator_dept', 'fator_dept_fator=fator.fator_id');
	$sql->adOnde('fator_dept='.(int)$dept_id.' OR fator_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('fator_dept','fator_dept', 'fator_dept_fator=fator.fator_id');
	$sql->adOnde('fator_dept IN ('.$lista_depts.') OR fator_dept_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('fator_cia', 'fator_cia', 'fator.fator_id=fator_cia_fator');
	$sql->adOnde('fator_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR fator_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif  ($cia_id && !$lista_cias) $sql->adOnde('fator_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('fator_cia IN ('.$lista_cias.')');	


if ($tab==0 || $a!='fator_lista') $sql->adOnde('fator_ativo=1');
elseif ($tab==1) $sql->adOnde('fator_ativo!=1 OR fator_ativo IS NULL');

if ($filtro_pg_id){
	$sql->esqUnir('plano_gestao_fator','plano_gestao_fator','plano_gestao_fator_fator=fator.fator_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_fator_plano_gestao');
	$sql->adOnde('plano_gestao.pg_id='.(int)$filtro_pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('fator_usuario', 'fator_usuario', 'fator_usuario_fator = fator.fator_id');
	$sql->adOnde('fator_usuario = '.(int)$usuario_id.' OR fator_usuario_usuario='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('fator_nome LIKE \'%'.$pesquisar_texto.'%\' OR fator_descricao LIKE \'%'.$pesquisar_texto.'%\'');



$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('fator');

$sql->adCampo('fator.*');

$sql->esqUnir('fator_gestao','fator_gestao','fator_gestao_fator = fator.fator_id');
if ($tarefa_id) $sql->adOnde('fator_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=fator_gestao_tarefa');
	$sql->adOnde('fator_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('fator_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('fator_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('fator_gestao_objetivo IN ('.$objetivo_id.')');

elseif ($fator_id) $sql->adOnde('fator_gestao_semelhante IN ('.$fator_id.')');

elseif ($pg_estrategia_id) $sql->adOnde('fator_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('fator_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('fator_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('fator_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('fator_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('fator_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('fator_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('fator_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('fator_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('fator_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('fator_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('fator_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('fator_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('fator_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('fator_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('fator_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('fator_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('fator_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('fator_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('fator_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('fator_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('fator_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('fator_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('fator_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('fator_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('fator_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('fator_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('fator_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('fator_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('fator_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('fator_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('fator_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('fator_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('fator_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('fator_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('fator_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('fator_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('fator_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('fator_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('fator_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('fator_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('fator_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('fator_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('fator_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('fator_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('fator_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('fator_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('fator_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('fator_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('fator_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('fator_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

if ($filtro_prioridade_fator){
	$sql->esqUnir('priorizacao', 'priorizacao', 'fator.fator_id=priorizacao_fator');
	if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_fator = fator.fator_id AND priorizacao_modelo IN ('.$filtro_prioridade_fator.')) AS priorizacao');
	else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_fator = fator.fator_id AND priorizacao_modelo IN ('.$filtro_prioridade_fator.')) AS priorizacao');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_fator.')');
	}

if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'fator.fator_id=favorito_lista_campo');
	$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
	$sql->adOnde('favorito.favorito_id='.(int)$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('fator_dept','fator_dept', 'fator_dept_fator=fator.fator_id');
	$sql->adOnde('fator_dept='.(int)$dept_id.' OR fator_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('fator_dept','fator_dept', 'fator_dept_fator=fator.fator_id');
	$sql->adOnde('fator_dept IN ('.$lista_depts.') OR fator_dept_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('fator_cia', 'fator_cia', 'fator.fator_id=fator_cia_fator');
	$sql->adOnde('fator_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR fator_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif  ($cia_id && !$lista_cias) $sql->adOnde('fator_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('fator_cia IN ('.$lista_cias.')');	

if ($tab==0 || $a!='fator_lista') $sql->adOnde('fator_ativo=1');
elseif ($tab==1) $sql->adOnde('fator_ativo!=1 OR fator_ativo IS NULL');

if ($filtro_pg_id){
	$sql->esqUnir('plano_gestao_fator','plano_gestao_fator','plano_gestao_fator_fator=fator.fator_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_fator_plano_gestao');
	$sql->adOnde('plano_gestao.pg_id='.(int)$filtro_pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('fator_usuario', 'fator_usuario', 'fator_usuario_fator = fator.fator_id');
	$sql->adOnde('fator_usuario = '.(int)$usuario_id.' OR fator_usuario_usuario='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('fator_nome LIKE \'%'.$pesquisar_texto.'%\' OR fator_descricao LIKE \'%'.$pesquisar_texto.'%\'');
if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_fator=fator.fator_id) AS tem_aprovacao');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('fator.fator_id');
$sql->setLimite($xmin, $xtamanhoPagina);
$fatores=$sql->Lista();
$sql->limpar();



$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['fator']), ucfirst($config['fatores']),'','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao && !$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

if ($exibir['fator_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Neste campo fica a cor de identificação d'.$config['genero_fator'].' '.$config['fator'].'.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Neste campo fica um nome para identificação d'.$config['genero_fator'].' '.$config['fator'].'.').'Nome'.dicaF().'</a></th>';

if ($exibir['fator_aprovado'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_fator) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';

if ($exibir['fator_descricao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Neste campo fica a descrição d'.$config['genero_fator'].' '.$config['fator'].'.').'Descrição'.dicaF().'</a></th>';

if ($exibir['fator_relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionad'.$config['genero_fator'], ucfirst($config['genero_perspectiva']).'s '.$config['perspectiva'].' n'.$config['genero_perspectiva'].'s quais '.$config['genero_fator'].' '.$config['fator'].' está relacionad'.$config['genero_fator'].'.').'Relacionad'.$config['genero_fator'].dicaF().'</a></th>';

if ($exibir['fator_oque']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_oque&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_oque' ? imagem('icones/'.$seta[$ordem]) : '').dica('O Que', 'Neste campo fica o que fazer d'.$config['genero_fator'].' '.$config['fator'].'.').'O Que'.dicaF().'</a></th>';
if ($exibir['fator_onde']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_onde&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_onde' ? imagem('icones/'.$seta[$ordem]) : '').dica('Onde ', 'Neste campo fica onde fazer '.$config['genero_fator'].' '.$config['fator'].'.').'Onde'.dicaF().'</a></th>';
if ($exibir['fator_quando']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_quando&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_quando' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quando', 'Neste campo fica quando fazer '.$config['genero_fator'].' '.$config['fator'].'.').'Quando'.dicaF().'</a></th>';
if ($exibir['fator_como']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_como&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_como' ? imagem('icones/'.$seta[$ordem]) : '').dica('Como', 'Neste campo fica como fazer '.$config['genero_fator'].' '.$config['fator'].'.').'Como'.dicaF().'</a></th>';
if ($exibir['fator_porque']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_porque&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_porque' ? imagem('icones/'.$seta[$ordem]) : '').dica('Por Que', 'Neste campo fica por que fazer '.$config['genero_fator'].' '.$config['fator'].'.').'Por Que'.dicaF().'</a></th>';
if ($exibir['fator_quanto']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_quanto&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_quanto' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quanto', 'Neste campo fica quanto custa '.$config['genero_fator'].' '.$config['fator'].'.').'Quanto'.dicaF().'</a></th>';
if ($exibir['fator_quem']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_quem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_quem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quem', 'Neste campo fica quem faz '.$config['genero_fator'].' '.$config['fator'].'.').'Quem'.dicaF().'</a></th>';
if ($exibir['fator_controle']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_controle&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_controle' ? imagem('icones/'.$seta[$ordem]) : '').dica('Controle', 'Neste campo fica o método de controle d'.$config['genero_fator'].' '.$config['fator'].'.').'Controle'.dicaF().'</a></th>';
if ($exibir['fator_melhorias']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_melhorias&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_melhorias' ? imagem('icones/'.$seta[$ordem]) : '').dica('Melhorias', 'Neste campo fica as melhorias efetuadas n'.$config['genero_fator'].' '.$config['fator'].'.').'Melhorias'.dicaF().'</a></th>';
if ($exibir['fator_metodo_aprendizado']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_metodo_aprendizado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_metodo_aprendizado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprendizado', 'Neste campo fica o método de aprendizado n'.$config['genero_fator'].' '.$config['fator'].'.').'Aprendizado'.dicaF().'</a></th>';
if ($exibir['fator_desde_quando']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_desde_quando&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_desde_quando' ? imagem('icones/'.$seta[$ordem]) : '').dica('Desde Quando', 'Neste campo fica desde quando é realizad'.$config['genero_fator'].' '.$config['genero_fator'].' '.$config['fator'].'.').'Desde Quando'.dicaF().'</a></th>';
if ($exibir['fator_tipo_pontuacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=fator_tipo_pontuacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_tipo_pontuacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Sistema de Pontuação', 'Neste campo fica o sistema de pontuação d'.$config['genero_fator'].' '.$config['fator'].'.').'Sistema'.dicaF().'</a></th>';
if ($exibir['fator_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=fator_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='fator_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['fator_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['fator_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=fator_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='fator_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['fator_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';


if ($exibir['fator_usuario']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=fator_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_fator'].' '.$config['fator'].'.').'Responsável'.dicaF().'</a></th>';
if ($exibir['fator_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_fator'].'s '.$config['fatores'].'.').'Designados'.dicaF().'</th>';
if ($exibir['fator_percentagem'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=fator_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='fator_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem d'.$config['genero_fator'].' '.$config['fator'].' executada.').'%'.dicaF().'</a></th>';
echo '</tr>';

$qnt=0;

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'fator');

foreach ($fatores as $linha) {
	if (permiteAcessarFator($linha['fator_acesso'], $linha['fator_id'])){
		$editar=permiteEditarFator($linha['fator_acesso'], $linha['fator_id']);
		if ($edicao && !$editar) continue;
		$qnt++;
		if ($Aplic->profissional) $bloquear=($linha['fator_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
		else $bloquear=0;
		
		echo '<tr>';
		if (!$impressao && !$dialogo) echo '<td style="white-space: nowrap" width="16">'.($podeEditar && $editar && !$bloquear ? dica('Editar '.$config['genero_fator'].' '.ucfirst($config['fator']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_fator'].' '.$config['fator'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=fator_editar&fator_id='.$linha['fator_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['fator_id'].'" value="'.$linha['fator_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['fator_id'].'" value="'.$linha['fator_id'].'" '.(isset($selecionado[$linha['fator_id']]) ? 'checked="checked"' : '').' />';

		if ($exibir['fator_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['fator_cor'].'"><font color="'.melhorCor($linha['fator_cor']).'">&nbsp;&nbsp;</font></td>';
		if ($selecao) echo '<td id="fator_nome_'.$linha['fator_id'].'">'.$linha['fator_nome'].'</td>';
		else echo '<td>'.link_fator($linha['fator_id'], true).'</td>';
		
		if ($exibir['fator_aprovado'] && $Aplic->profissional) echo '<td width=30 align="center">'.($linha['fator_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
		if ($filtro_prioridade_fator) echo '<td align="right" style="white-space: nowrap" width=50>'.($linha['priorizacao']).'</td>';

		if ($exibir['fator_descricao']) echo '<td>'.($linha['fator_descricao'] ? $linha['fator_descricao'] : '&nbsp;').'</td>';
		
		if ($exibir['fator_relacionado']){
			$sql->adTabela('fator_gestao');
			$sql->adCampo('fator_gestao.*');
			$sql->adOnde('fator_gestao_fator ='.(int)$linha['fator_id']);	
			$sql->adOrdem('fator_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['fator_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['fator_gestao_tarefa']);
					elseif ($gestao_data['fator_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['fator_gestao_projeto']);
					elseif ($gestao_data['fator_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['fator_gestao_pratica']);
					elseif ($gestao_data['fator_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['fator_gestao_acao']);
					elseif ($gestao_data['fator_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['fator_gestao_perspectiva']);
					elseif ($gestao_data['fator_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['fator_gestao_tema']);
					elseif ($gestao_data['fator_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['fator_gestao_objetivo']);
					
					elseif ($gestao_data['fator_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['fator_gestao_semelhante']);
					
					elseif ($gestao_data['fator_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['fator_gestao_estrategia']);
					elseif ($gestao_data['fator_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['fator_gestao_meta']);
					elseif ($gestao_data['fator_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['fator_gestao_canvas']);
					elseif ($gestao_data['fator_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['fator_gestao_risco']);
					elseif ($gestao_data['fator_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['fator_gestao_risco_resposta']);
					elseif ($gestao_data['fator_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['fator_gestao_indicador']);
					elseif ($gestao_data['fator_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['fator_gestao_calendario']);
					elseif ($gestao_data['fator_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['fator_gestao_monitoramento']);
					elseif ($gestao_data['fator_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['fator_gestao_ata']);
					elseif ($gestao_data['fator_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['fator_gestao_mswot']);
					elseif ($gestao_data['fator_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['fator_gestao_swot']);
					elseif ($gestao_data['fator_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['fator_gestao_operativo']);
					elseif ($gestao_data['fator_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['fator_gestao_instrumento']);
					elseif ($gestao_data['fator_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['fator_gestao_recurso']);
					elseif ($gestao_data['fator_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['fator_gestao_problema']);
					elseif ($gestao_data['fator_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['fator_gestao_demanda']);	
					elseif ($gestao_data['fator_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['fator_gestao_programa']);
					elseif ($gestao_data['fator_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['fator_gestao_licao']);
					elseif ($gestao_data['fator_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['fator_gestao_evento']);
					elseif ($gestao_data['fator_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['fator_gestao_link']);
					elseif ($gestao_data['fator_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['fator_gestao_avaliacao']);
					elseif ($gestao_data['fator_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['fator_gestao_tgn']);
					elseif ($gestao_data['fator_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['fator_gestao_brainstorm']);
					elseif ($gestao_data['fator_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['fator_gestao_gut']);
					elseif ($gestao_data['fator_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['fator_gestao_causa_efeito']);
					elseif ($gestao_data['fator_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['fator_gestao_arquivo']);
					elseif ($gestao_data['fator_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['fator_gestao_forum']);
					elseif ($gestao_data['fator_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['fator_gestao_checklist']);
					elseif ($gestao_data['fator_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['fator_gestao_agenda']);
					elseif ($gestao_data['fator_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['fator_gestao_agrupamento']);
					elseif ($gestao_data['fator_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['fator_gestao_patrocinador']);
					elseif ($gestao_data['fator_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['fator_gestao_template']);
					elseif ($gestao_data['fator_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['fator_gestao_painel']);
					elseif ($gestao_data['fator_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['fator_gestao_painel_odometro']);
					elseif ($gestao_data['fator_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['fator_gestao_painel_composicao']);		
					elseif ($gestao_data['fator_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['fator_gestao_tr']);	
					elseif ($gestao_data['fator_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['fator_gestao_me']);	
					elseif ($gestao_data['fator_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['fator_gestao_acao_item']);	
					elseif ($gestao_data['fator_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['fator_gestao_beneficio']);	
					elseif ($gestao_data['fator_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['fator_gestao_painel_slideshow']);	
					elseif ($gestao_data['fator_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['fator_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['fator_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['fator_gestao_projeto_abertura']);	
					elseif ($gestao_data['fator_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['fator_gestao_plano_gestao']);	
					elseif ($gestao_data['fator_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['fator_gestao_ssti']);	
					elseif ($gestao_data['fator_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['fator_gestao_laudo']);	
					elseif ($gestao_data['fator_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['fator_gestao_trelo']);	
					elseif ($gestao_data['fator_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['fator_gestao_trelo_cartao']);	
					elseif ($gestao_data['fator_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['fator_gestao_pdcl']);	
					elseif ($gestao_data['fator_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['fator_gestao_pdcl_item']);	

					}
				}	
			echo '</td>';	
			}	
		
		if ($exibir['fator_oque']) echo '<td>'.($linha['fator_oque'] ? $linha['fator_oque']: '&nbsp;').'</td>';
		if ($exibir['fator_onde']) echo '<td>'.($linha['fator_onde'] ? $linha['fator_onde']: '&nbsp;').'</td>';
		if ($exibir['fator_quando']) echo '<td>'.($linha['fator_quando'] ? $linha['fator_quando']: '&nbsp;').'</td>';
		if ($exibir['fator_como']) echo '<td>'.($linha['fator_como'] ? $linha['fator_como']: '&nbsp;').'</td>';
		if ($exibir['fator_porque']) echo '<td>'.($linha['fator_porque'] ? $linha['fator_porque']: '&nbsp;').'</td>';
		if ($exibir['fator_quanto']) echo '<td>'.($linha['fator_quanto'] ? $linha['fator_quanto']: '&nbsp;').'</td>';
		if ($exibir['fator_quem']) echo '<td>'.($linha['fator_quem'] ? $linha['fator_quem']: '&nbsp;').'</td>';
		if ($exibir['fator_controle']) echo '<td>'.($linha['fator_controle'] ? $linha['fator_controle']: '&nbsp;').'</td>';
		if ($exibir['fator_melhorias']) echo '<td>'.($linha['fator_melhorias'] ? $linha['fator_melhorias']: '&nbsp;').'</td>';
		if ($exibir['fator_metodo_aprendizado']) echo '<td>'.($linha['fator_metodo_aprendizado'] ? $linha['fator_metodo_aprendizado']: '&nbsp;').'</td>';
		if ($exibir['fator_desde_quando']) echo '<td>'.($linha['fator_desde_quando'] ? $linha['fator_desde_quando']: '&nbsp;').'</td>';
		if ($exibir['fator_tipo_pontuacao']) echo '<td>'.(isset($tipo_pontuacao[$linha['fator_tipo_pontuacao']]) ? $tipo_pontuacao[$linha['fator_tipo_pontuacao']] : '&nbsp;').'</td>';

		if ($exibir['fator_cia']) echo '<td>'.link_cia($linha['fator_cia']).'</td>';
		if ($exibir['fator_cias']){
			$sql->adTabela('fator_cia');
			$sql->adCampo('fator_cia_cia');
			$sql->adOnde('fator_cia_fator = '.(int)$linha['fator_id']);
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
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['fator_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['fator_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
		
		if ($exibir['fator_dept']) echo '<td>'.link_secao($linha['fator_dept']).'</td>';	
		
		if ($exibir['fator_depts']){
			$sql->adTabela('fator_dept');
			$sql->adCampo('fator_dept_dept');
			$sql->adOnde('fator_dept_fator = '.(int)$linha['fator_id']);
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
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['fator_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['fator_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}


		
		
		if ($exibir['fator_usuario']) echo '<td>'.link_usuario($linha['fator_usuario'],'','','esquerda').'</td>';
		
		if ($exibir['fator_designados']) {
			$sql->adTabela('fator_usuario');
			$sql->adCampo('fator_usuario_usuario');
			$sql->adOnde('fator_usuario_fator = '.(int)$linha['fator_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['fator_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['fator_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
			
		if ($exibir['fator_percentagem'] && $Aplic->profissional) echo '<td style="white-space: nowrap" align=right width=30>'.number_format($linha['fator_percentagem'], 2, ',', '.').'</td>';
		
		echo '</tr>';
		}
	}
if (!count($fatores)) echo '<tr><td colspan=20><p>Nenhum'.($config['genero_fator']=='a' ? 'a' : '').' '.$config['fator'].' encontrad'.$config['genero_fator'].'.</p></td></tr>';
elseif(count($fatores) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer d'.$config['genero_fator'].'s '.$config['fatores'].'.</p></td></tr>';
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS()').'</td></tr></table></td></tr>';

echo '</table>';

?>
<script type="text/javascript">
	
function selecionar(fator_id){
	var nome=document.getElementById('fator_nome_'+fator_id).innerHTML;
	setFechar(fator_id, nome);
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