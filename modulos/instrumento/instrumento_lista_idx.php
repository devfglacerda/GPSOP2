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
if (!$Aplic->checarModulo('instrumento', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

global $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $a, $estilo_interface, $sql, $perms, $Aplic, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $usuario_id, $pesquisar_texto, $dialogo, $podeEditar, $podeExcluir, $instrumento_ano,  $instrumento_tipo, $instrumento_situacao, $podeEditar, $favorito_id, $filtro_prioridade_instrumento,
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

if ($m!='instrumento') $tab=2;

$pagina=getParam($_REQUEST, 'pagina', 1);
$pesquisa=getParam($_REQUEST, 'pesquisa', '');
$ordenar=getParam($_REQUEST, 'ordenar', 'instrumento_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');


$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_instrumentos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$TipoInstrumento = getSisValor('TipoInstrumento');
$ModalidadeLicitacao = getSisValor('ModalidadeLicitacao');
$SituacaoInstrumento = getSisValor('SituacaoInstrumento');

$percentual=getSisValor('TarefaPorcentagem','','','sisvalor_id');
$cor = getSisValor('SituacaoInstrumentoCor');

$sql = new BDConsulta();

$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'instrumentos\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'instrumentos\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}


$sql->adTabela('instrumento');
$sql->adCampo('count(DISTINCT instrumento.instrumento_id)');
if ($Aplic->profissional && $instrumento_ano!=-1 && $instrumento_ano) $sql->adOnde('instrumento_ano IN ('.$instrumento_ano.')');
if ($Aplic->profissional && $instrumento_tipo!=-1 && $instrumento_tipo) $sql->adOnde('instrumento_tipo IN ('.$instrumento_tipo.')');
if ($Aplic->profissional && $instrumento_situacao!=-1 && $instrumento_situacao) $sql->adOnde('instrumento_situacao IN ('.$instrumento_situacao.')');
if ($pesquisar_texto) $sql->adOnde('(instrumento_nome LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_objeto LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_numero LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_entidade LIKE \'%'.$pesquisar_texto.'%\')');


if ($tab==0 || $a!='instrumento_lista') $sql->adOnde('instrumento_ativo=1');
elseif ($tab==1) $sql->adOnde('instrumento_ativo!=1 OR instrumento_ativo IS NULL');	

if($usuario_id) {
	$sql->esqUnir('instrumento_designados','instrumento_designados', 'instrumento_designados.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_designados.usuario_id ='.(int)$usuario_id.' OR instrumento_responsavel='.(int)$usuario_id);
	}

if ($filtro_prioridade_instrumento){
	$sql->esqUnir('priorizacao', 'priorizacao', 'instrumento.instrumento_id=priorizacao_instrumento');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_instrumento.')');
	}

if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'instrumento.instrumento_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}		
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_dept='.(int)$dept_id.' OR instrumento_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_dept IN ('.$lista_depts.') OR instrumento_depts.dept_id IN ('.$lista_depts.')');
	}
if (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('instrumento_cia', 'instrumento_cia', 'instrumento.instrumento_id=instrumento_cia_instrumento');
	$sql->adOnde('instrumento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR instrumento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('instrumento_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('instrumento_cia IN ('.$lista_cias.')');

if(!$Aplic->profissional && $projeto_id){
	$sql->esqUnir('instrumento_recursos', 'instrumento_recursos', 'instrumento_recursos.instrumento_id = instrumento.instrumento_id');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id = instrumento_recursos.recurso_id');
	$sql->esqUnir('recurso_tarefa', 'recurso_tarefa', 'recurso_tarefa_recurso = recursos.recurso_id');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = recurso_tarefa_tarefa');
	$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
	$sql->adOnde('projetos.projeto_id = '.(int)$projeto_id);
	}

$sql->esqUnir('instrumento_gestao','instrumento_gestao','instrumento_gestao_instrumento = instrumento.instrumento_id');
if ($tarefa_id) $sql->adOnde('instrumento_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=instrumento_gestao_tarefa');
	$sql->adOnde('instrumento_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('instrumento_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('instrumento_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('instrumento_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('instrumento_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('instrumento_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('instrumento_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('instrumento_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('instrumento_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('instrumento_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('instrumento_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('instrumento_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('instrumento_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('instrumento_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('instrumento_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('instrumento_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('instrumento_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('instrumento_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('instrumento_gestao_operativo IN ('.$operativo_id.')');

elseif ($instrumento_id) $sql->adOnde('instrumento_gestao_semelhante IN ('.$instrumento_id.')');

elseif ($recurso_id) $sql->adOnde('instrumento_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('instrumento_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('instrumento_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('instrumento_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('instrumento_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('instrumento_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('instrumento_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('instrumento_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('instrumento_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('instrumento_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('instrumento_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('instrumento_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('instrumento_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('instrumento_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('instrumento_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('instrumento_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('instrumento_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('instrumento_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('instrumento_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('instrumento_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('instrumento_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('instrumento_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('instrumento_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('instrumento_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('instrumento_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('instrumento_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('instrumento_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('instrumento_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('instrumento_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('instrumento_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('instrumento_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('instrumento_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('instrumento_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('instrumento_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('instrumento_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('instrumento_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

$xtotalregistros = $sql->Resultado();
$sql->limpar();



$sql->adTabela('instrumento');
$sql->adCampo('instrumento.*');

if ($Aplic->profissional && $instrumento_ano!=-1 && $instrumento_ano) $sql->adOnde('instrumento_ano IN ('.$instrumento_ano.')');
if ($Aplic->profissional && $instrumento_tipo!=-1 && $instrumento_tipo) $sql->adOnde('instrumento_tipo IN ('.$instrumento_tipo.')');
if ($Aplic->profissional && $instrumento_situacao!=-1 && $instrumento_situacao) $sql->adOnde('instrumento_situacao IN ('.$instrumento_situacao.')');


if ($pesquisar_texto) $sql->adOnde('(instrumento_nome LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_objeto LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_numero LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_entidade LIKE \'%'.$pesquisar_texto.'%\')');

if($usuario_id) {
	$sql->esqUnir('instrumento_designados','instrumento_designados', 'instrumento_designados.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_designados.usuario_id ='.(int)$usuario_id.' OR instrumento_responsavel='.(int)$usuario_id);
	}
	
if ($filtro_prioridade_instrumento){
	$sql->esqUnir('priorizacao', 'priorizacao', 'instrumento.instrumento_id=priorizacao_instrumento');
	if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_instrumento = instrumento.instrumento_id AND priorizacao_modelo IN ('.$filtro_prioridade_instrumento.')) AS priorizacao');
	else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_instrumento = instrumento.instrumento_id AND priorizacao_modelo IN ('.$filtro_prioridade_instrumento.')) AS priorizacao');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_instrumento.')');
	}
	
if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'instrumento.instrumento_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}		
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_dept='.(int)$dept_id.' OR instrumento_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_dept IN ('.$lista_depts.') OR instrumento_depts.dept_id IN ('.$lista_depts.')');
	}
if (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('instrumento_cia', 'instrumento_cia', 'instrumento.instrumento_id=instrumento_cia_instrumento');
	$sql->adOnde('instrumento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR instrumento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('instrumento_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('instrumento_cia IN ('.$lista_cias.')');

if ($tab==0 || $a!='instrumento_lista') $sql->adOnde('instrumento_ativo=1');
elseif ($tab==1) $sql->adOnde('instrumento_ativo!=1 OR instrumento_ativo IS NULL');	

if(!$Aplic->profissional && $projeto_id){
	$sql->esqUnir('instrumento_recursos', 'instrumento_recursos', 'instrumento_recursos.instrumento_id = instrumento.instrumento_id');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id = instrumento_recursos.recurso_id');
	$sql->esqUnir('recurso_tarefa', 'recurso_tarefa', 'recurso_tarefa_recurso = recursos.recurso_id');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = recurso_tarefa_tarefa');
	$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
	$sql->adOnde('projetos.projeto_id = '.(int)$projeto_id);
	}

$sql->esqUnir('instrumento_gestao','instrumento_gestao','instrumento_gestao_instrumento = instrumento.instrumento_id');
if ($tarefa_id) $sql->adOnde('instrumento_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=instrumento_gestao_tarefa');
	$sql->adOnde('instrumento_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('instrumento_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('instrumento_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('instrumento_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('instrumento_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('instrumento_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('instrumento_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('instrumento_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('instrumento_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('instrumento_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('instrumento_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('instrumento_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('instrumento_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('instrumento_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('instrumento_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('instrumento_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('instrumento_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('instrumento_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('instrumento_gestao_operativo IN ('.$operativo_id.')');

elseif ($instrumento_id) $sql->adOnde('instrumento_gestao_semelhante IN ('.$instrumento_id.')');

elseif ($recurso_id) $sql->adOnde('instrumento_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('instrumento_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('instrumento_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('instrumento_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('instrumento_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('instrumento_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('instrumento_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('instrumento_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('instrumento_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('instrumento_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('instrumento_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('instrumento_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('instrumento_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('instrumento_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('instrumento_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('instrumento_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('instrumento_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('instrumento_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('instrumento_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('instrumento_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('instrumento_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('instrumento_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('instrumento_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('instrumento_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('instrumento_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('instrumento_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('instrumento_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('instrumento_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('instrumento_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('instrumento_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('instrumento_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('instrumento_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('instrumento_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('instrumento_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('instrumento_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('instrumento_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));

if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_instrumento=instrumento.instrumento_id) AS tem_aprovacao');

$sql->adGrupo('instrumento.instrumento_id');

$sql->setLimite($xmin, $xtamanhoPagina);
$instrumentos = $sql->Lista();
$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Instrumento', 'Instrumentos','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));



echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

if (!$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';

if ($exibir['instrumento_cor']) echo '<th width=16 style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor do Instrumento', 'Neste campo fica a cor de identificação do instrumento.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome do Instrumento', 'Neste campo fica um nome para identificação do instrumento.').'Nome'.dicaF().'</a></th>';
if ($exibir['instrumento_aprovado'] && $Aplic->profissional) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=instrumento_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_instrumento) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';
if ($exibir['instrumento_tipo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_tipo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_tipo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tipo de Instrumento', 'Neste campo fica o tipo do instrumento.').'Tipo'.dicaF().'</a></th>';


if ($exibir['instrumento_porcentagem']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_porcentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_porcentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('%', 'Neste campo fica percentual realizado do instrumento.').'%'.dicaF().'</th>';
if ($exibir['instrumento_situacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_situacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_situacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Situação  do Instrumento', 'Neste campo fica a situação do instrumento.').'Situação'.dicaF().'</a></th>';




if ($exibir['instrumento_relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionado', 'A que área este instrumento está relacionado.').'Relacionado'.dicaF().'</th>';
if ($exibir['instrumento_objeto']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_objeto&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_objeto' ? imagem('icones/'.$seta[$ordem]) : '').dica('Objeto do Instrumento', 'Caso exista um instrumento para página ou arquivo na rede que faça referência ao registro.').'Objeto'.dicaF().'</a></th>';
if ($exibir['instrumento_licitacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_licitacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_licitacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tipo de Licitação', 'Neste campo fica o tipo de licitação do instrumento.').'Licitação'.dicaF().'</a></th>';
if ($exibir['instrumento_valor']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_valor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_valor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Valor', 'Neste campo fica o valor do instrumento.').'Valor'.dicaF().'</th>';

if ($exibir['instrumento_valor_contrapartida']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_valor_contrapartida&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_valor_contrapartida' ? imagem('icones/'.$seta[$ordem]) : '').dica('Contrapartida', 'Neste campo fica o valor da contrapartida no instrumento.').'Contrapartida'.dicaF().'</th>';


if ($exibir['instrumento_data_inicio']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_data_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_data_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Início', 'Neste campo fica a data de início do instrumento.').'Início'.dicaF().'</a></th>';

if ($exibir['instrumento_data_termino']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_data_termino&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_data_termino' ? imagem('icones/'.$seta[$ordem]) : '').dica('Término', 'Neste campo fica a data de término do instrumento.').'Término'.dicaF().'</a></th>';
if ($exibir['instrumento_data_celebracao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_data_celebracao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_data_celebracao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Celebração', 'Neste campo fica a data de celebração do instrumento.').'Celebração'.dicaF().'</a></th>';
if ($exibir['instrumento_data_publicacao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_data_publicacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_data_publicacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Publicação', 'Neste campo fica a data de publicação do instrumento.').'Publicação'.dicaF().'</a></th>';



if ($exibir['instrumento_numero']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_numero&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_numero' ? imagem('icones/'.$seta[$ordem]) : '').dica('Número', 'Neste campo fica o número do instrumento.').'Número'.dicaF().'</th>';
if ($exibir['instrumento_ano']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_ano&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_ano' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ano', 'Neste campo fica o ano do instrumento.').'Ano'.dicaF().'</th>';
if ($exibir['instrumento_edital_nr']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_edital_nr&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_edital_nr' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nº do Edital', 'Neste campo fica o número do edital do instrumento.').'Nº Edital'.dicaF().'</th>';
if ($exibir['instrumento_processo']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_processo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_processo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nº do Processo', 'Neste campo fica o número do processo do instrumento.').'Nº Processo'.dicaF().'</th>';
if ($exibir['instrumento_justificativa']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_justificativa&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_justificativa' ? imagem('icones/'.$seta[$ordem]) : '').dica('Justificativa', 'Neste campo fica a justificativa do instrumento.').'Justificativa'.dicaF().'</th>';
if ($exibir['instrumento_entidade']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_entidade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_entidade' ? imagem('icones/'.$seta[$ordem]) : '').dica('Entidade', 'Neste campo fica a entidade do instrumento.').'Entidade'.dicaF().'</th>';
if ($exibir['instrumento_entidade_cnpj']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_entidade_cnpj&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_entidade_cnpj' ? imagem('icones/'.$seta[$ordem]) : '').dica('CNPJ da Entidade', 'Neste campo fica a CNPJ da entidade do instrumento.').'CNPJ Entidade'.dicaF().'</th>';




if ($exibir['instrumento_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=instrumento_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='instrumento_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['instrumento_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['instrumento_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=instrumento_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='instrumento_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['instrumento_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['instrumento_responsavel']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Responsável'.dicaF().'</a></th>';
if ($exibir['instrumento_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Designados'.dicaF().'</th>';

if ($exibir['instrumento_supervisor']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_supervisor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_supervisor' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['supervisor']), ucfirst($config['supervisor']).' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['supervisor']).dicaF().'</a></th>';
if ($exibir['instrumento_autoridade']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_autoridade&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_autoridade' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['autoridade']), ucfirst($config['autoridade']).'d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['autoridade']).dicaF().'</a></th>';
if ($exibir['instrumento_cliente']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=instrumento_cliente&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_cliente' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['cliente']),ucfirst($config['cliente']).' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['cliente']).dicaF().'</a></th>';


echo '</tr>';

$qnt=0;
foreach ($instrumentos as $linha) {
	if (permiteAcessarInstrumento($linha['instrumento_acesso'], $linha['instrumento_id'])){	
		$qnt++;
		$editar=permiteEditarInstrumento($linha['instrumento_acesso'], $linha['instrumento_id']);
		
		if ($Aplic->profissional) $bloquear=($linha['instrumento_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
		else $bloquear=0;
		if ($edicao && !$editar) continue;
		echo '<tr>';
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['instrumento_id'].'" value="'.$linha['instrumento_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['instrumento_id'].'" value="'.$linha['instrumento_id'].'" '.(isset($selecionado[$linha['instrumento_id']]) ? 'checked="checked"' : '').' />';

		if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.($editar && $podeEditar && !$bloquear ? dica('Editar Instrumento', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o instrumento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=instrumento&a=instrumento_editar&instrumento_id='.$linha['instrumento_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		
		if ($exibir['instrumento_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['instrumento_cor'].'">&nbsp;&nbsp;</td>';
		if ($selecao) echo '<td id="instrumento_nome_'.$linha['instrumento_id'].'">'.$linha['instrumento_nome'].'</td>';
		else echo '<td>'.link_instrumento($linha['instrumento_id']).'</td>';
		//echo '<td>'.dica($linha['instrumento_nome'], 'Clique para visualizar os detalhes deste instrumento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=instrumento&a=instrumento_ver&instrumento_id='.$linha['instrumento_id'].'\');">'.$linha['instrumento_nome'].'</a>'.dicaF().'</td>';
		
		if ($exibir['instrumento_aprovado'] && $Aplic->profissional) echo '<td width=30 align="center">'.($linha['instrumento_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
		if ($filtro_prioridade_instrumento) echo '<td align="right">'.($linha['priorizacao']).'</td>';	
		
		
		if ($exibir['instrumento_tipo']) echo '<td align="center">'.(isset($TipoInstrumento[$linha['instrumento_tipo']]) ? $TipoInstrumento[$linha['instrumento_tipo']] : '&nbsp;').'</td>';
		
		
		
		
		if (!$dialogo && $editar && ($a=='instrumento_lista' || $a=='parafazer')){
			if ($exibir['instrumento_porcentagem']) echo '<td align=center style="white-space: nowrap">'.selecionaVetor($percentual, 'instrumento_porcentagem', 'size="1" class="texto" onchange="mudar_percentagem_instrumento('. $linha['instrumento_id'].', this.value);"', (int)$linha['instrumento_porcentagem']).'</td>';
			if ($exibir['instrumento_situacao']) echo '<td align=center id="status_'.$linha['instrumento_id'].'" '.(isset($cor[$linha['instrumento_situacao']]) ? 'style="white-space: nowrap; background-color:#'.$cor[$linha['instrumento_situacao']].';"' : 'style="white-space: nowrap;"').'>'.selecionaVetor($SituacaoInstrumento, 'instrumento_situacao', 'size="1" class="texto" onchange="mudar_status_instrumento('. $linha['instrumento_id'].', this.value);"', $linha['instrumento_situacao']).'</td>';
			}
		else {
			if ($exibir['instrumento_porcentagem']) echo '<td align=center style="white-space: nowrap" width=40>'.number_format($linha['instrumento_porcentagem'], 2, ',', '.').'</td>';
			if ($exibir['instrumento_situacao']) echo '<td align=center width=80 '.(isset($cor[$linha['instrumento_situacao']]) ? 'style="white-space: nowrap; background-color:#'.$cor[$linha['instrumento_situacao']].';"' : 'style="white-space: nowrap;"').'>'.(isset($SituacaoInstrumento[$linha['instrumento_situacao']]) ? $SituacaoInstrumento[$linha['instrumento_situacao']] : '&nbsp;').'</td>';
			}

		if ($exibir['instrumento_relacionado']){
			$sql->adTabela('instrumento_gestao');
			$sql->adCampo('instrumento_gestao.*');
			$sql->adOnde('instrumento_gestao_instrumento ='.(int)$linha['instrumento_id']);	
			$sql->adOrdem('instrumento_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['instrumento_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']);
					elseif ($gestao_data['instrumento_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']);
					elseif ($gestao_data['instrumento_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']);
					elseif ($gestao_data['instrumento_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']);
					elseif ($gestao_data['instrumento_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']);
					elseif ($gestao_data['instrumento_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']);
					elseif ($gestao_data['instrumento_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']);
					elseif ($gestao_data['instrumento_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']);
					elseif ($gestao_data['instrumento_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']);
					elseif ($gestao_data['instrumento_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']);
					elseif ($gestao_data['instrumento_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']);
					elseif ($gestao_data['instrumento_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']);
					elseif ($gestao_data['instrumento_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']);
					elseif ($gestao_data['instrumento_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']);
					elseif ($gestao_data['instrumento_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']);
					elseif ($gestao_data['instrumento_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']);
					elseif ($gestao_data['instrumento_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']);
					elseif ($gestao_data['instrumento_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['instrumento_gestao_mswot']);
					elseif ($gestao_data['instrumento_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']);
					elseif ($gestao_data['instrumento_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']);
					
					elseif ($gestao_data['instrumento_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['instrumento_gestao_semelhante']);
					
					elseif ($gestao_data['instrumento_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']);
					elseif ($gestao_data['instrumento_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['instrumento_gestao_problema']);
					elseif ($gestao_data['instrumento_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']);	
					elseif ($gestao_data['instrumento_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']);
					elseif ($gestao_data['instrumento_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']);
					elseif ($gestao_data['instrumento_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']);
					elseif ($gestao_data['instrumento_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']);
					elseif ($gestao_data['instrumento_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']);
					elseif ($gestao_data['instrumento_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']);
					elseif ($gestao_data['instrumento_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['instrumento_gestao_brainstorm']);
					elseif ($gestao_data['instrumento_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['instrumento_gestao_gut']);
					elseif ($gestao_data['instrumento_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['instrumento_gestao_causa_efeito']);
					elseif ($gestao_data['instrumento_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']);
					elseif ($gestao_data['instrumento_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']);
					elseif ($gestao_data['instrumento_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']);
					elseif ($gestao_data['instrumento_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']);
					elseif ($gestao_data['instrumento_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']);
					elseif ($gestao_data['instrumento_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']);
					elseif ($gestao_data['instrumento_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['instrumento_gestao_template']);
					elseif ($gestao_data['instrumento_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['instrumento_gestao_painel']);
					elseif ($gestao_data['instrumento_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']);
					elseif ($gestao_data['instrumento_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']);		
					elseif ($gestao_data['instrumento_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']);	
					elseif ($gestao_data['instrumento_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['instrumento_gestao_me']);	
					elseif ($gestao_data['instrumento_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['instrumento_gestao_acao_item']);	
					elseif ($gestao_data['instrumento_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['instrumento_gestao_beneficio']);	
					elseif ($gestao_data['instrumento_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['instrumento_gestao_painel_slideshow']);	
					elseif ($gestao_data['instrumento_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['instrumento_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['instrumento_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['instrumento_gestao_projeto_abertura']);	
					elseif ($gestao_data['instrumento_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['instrumento_gestao_plano_gestao']);	
					elseif ($gestao_data['instrumento_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['instrumento_gestao_ssti']);	
					elseif ($gestao_data['instrumento_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['instrumento_gestao_laudo']);	
					elseif ($gestao_data['instrumento_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['instrumento_gestao_trelo']);	
					elseif ($gestao_data['instrumento_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['instrumento_gestao_trelo_cartao']);	
					elseif ($gestao_data['instrumento_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['instrumento_gestao_pdcl']);	
					elseif ($gestao_data['instrumento_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['instrumento_gestao_pdcl_item']);	

					}
				}	
			echo '</td>';	
			}	
				
				
		if ($exibir['instrumento_objeto']) echo '<td>'.($linha['instrumento_objeto'] ? $linha['instrumento_objeto'] : '&nbsp;').'</td>';
		if ($exibir['instrumento_licitacao']) echo '<td align="center">'.(isset($ModalidadeLicitacao[$linha['instrumento_licitacao']]) ? $ModalidadeLicitacao[$linha['instrumento_licitacao']] : '&nbsp;').'</td>';
		if ($exibir['instrumento_valor']) echo '<td align="right">'.number_format($linha['instrumento_valor'], 2, ',', '.').'</td>';
		if ($exibir['instrumento_valor_contrapartida']) echo '<td align="right">'.number_format($linha['instrumento_valor_contrapartida'], 2, ',', '.').'</td>';
		if ($exibir['instrumento_data_inicio']) echo '<td align="center">'.retorna_data($linha['instrumento_data_inicio'], false).'</td>';
		if ($exibir['instrumento_data_termino']) echo '<td align="center">'.retorna_data($linha['instrumento_data_termino'], false).'</td>';
		if ($exibir['instrumento_data_celebracao']) echo '<td align="center">'.retorna_data($linha['instrumento_data_celebracao'], false).'</td>';
		if ($exibir['instrumento_data_publicacao']) echo '<td align="center">'.retorna_data($linha['instrumento_data_publicacao'], false).'</td>';

		if ($exibir['instrumento_numero']) echo '<td>'.($linha['instrumento_numero'] ? $linha['instrumento_numero'] : '&nbsp;').'</td>';
		if ($exibir['instrumento_ano']) echo '<td>'.($linha['instrumento_ano'] ? $linha['instrumento_ano'] : '&nbsp;').'</td>';
		if ($exibir['instrumento_edital_nr']) echo '<td>'.($linha['instrumento_edital_nr'] ? $linha['instrumento_edital_nr'] : '&nbsp;').'</td>';
		if ($exibir['instrumento_processo']) echo '<td>'.($linha['instrumento_processo'] ? $linha['instrumento_processo'] : '&nbsp;').'</td>';
		if ($exibir['instrumento_justificativa']) echo '<td>'.($linha['instrumento_justificativa'] ? $linha['instrumento_justificativa'] : '&nbsp;').'</td>';
		if ($exibir['instrumento_entidade']) echo '<td>'.($linha['instrumento_entidade'] ? $linha['instrumento_entidade'] : '&nbsp;').'</td>';
		if ($exibir['instrumento_entidade_cnpj']) echo '<td>'.($linha['instrumento_entidade_cnpj'] ? $linha['instrumento_entidade_cnpj'] : '&nbsp;').'</td>';
		



		if ($exibir['instrumento_cia']) echo '<td>'.link_cia($linha['instrumento_cia']).'</td>';
		
		if ($exibir['instrumento_cias']){
			$sql->adTabela('instrumento_cia');
			$sql->adCampo('instrumento_cia_cia');
			$sql->adOnde('instrumento_cia_instrumento = '.(int)$linha['instrumento_id']);
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
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['instrumento_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['instrumento_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
			
		if ($exibir['instrumento_dept']) echo '<td>'.link_secao($linha['instrumento_dept']).'</td>';	
		
		if ($exibir['instrumento_depts']){
			$sql->adTabela('instrumento_depts');
			$sql->adCampo('dept_id');
			$sql->adOnde('instrumento_id = '.(int)$linha['instrumento_id']);
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
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['instrumento_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['instrumento_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
	
		if ($exibir['instrumento_responsavel']) echo '<td>'.link_usuario($linha['instrumento_responsavel'],'','','esquerda').'</td>';
		
		if ($exibir['instrumento_designados']) {
			$sql->adTabela('instrumento_designados');
			$sql->adCampo('usuario_id');
			$sql->adOnde('instrumento_id = '.(int)$linha['instrumento_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['instrumento_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['instrumento_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
		
		if ($exibir['instrumento_supervisor']) echo '<td>'.link_usuario($linha['instrumento_supervisor'],'','','esquerda').'</td>';
		if ($exibir['instrumento_autoridade']) echo '<td>'.link_usuario($linha['instrumento_autoridade'],'','','esquerda').'</td>';
		if ($exibir['instrumento_cliente']) echo '<td>'.link_usuario($linha['instrumento_cliente'],'','','esquerda').'</td>';
		
		echo '</tr>';
		}
	}
	
if (!count($instrumentos)) echo '<tr><td colspan="20"><p>Nenhum'.($config['genero_instrumento']=='o' ? '' : 'a').' '.$config['instrumento'].' encontrad'.$config['genero_instrumento'].'.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="20"><p>Não tem autorização para visualizar '.($config['genero_instrumento']=='o' ? '' : 'a').' d'.$config['genero_instrumento'].'s '.$config['instrumentos'].'.</p></td></tr>';	

if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS();').'</td></tr></table></td></tr>';
	
echo '</table>';
?>
<script type="text/javascript">
	
function selecionar(instrumento_id){
	var nome=document.getElementById('instrumento_nome_'+instrumento_id).innerHTML;
	setFechar(instrumento_id, nome);
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
	
	
function mudar_percentagem_instrumento(instrumento_id, valor){
	xajax_mudar_percentagem_instrumento(instrumento_id, valor);
	}

function mudar_status_instrumento(instrumento_id, valor){
	xajax_mudar_status_instrumento(instrumento_id, valor);
	}	
	
</script>								
