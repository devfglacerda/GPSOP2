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

global $envolvimento, $selecao, $chamarVolta, $selecionado, $estilo_interface, $Aplic, $recurso_pesquisa, $dialogo, $podeEditar, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $usuario_id,$recurso_tipo,$recurso_ano,$recurso_ugr,$recurso_ptres,$dept_id,$recurso_credito_adicional,$recurso_movimentacao_orcamentaria,$recurso_identificador_uso, $favorito_id, $filtro_prioridade_recurso,
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


$ordenar=getParam($_REQUEST, 'ordenar', 'recurso_chave');
$ordem=getParam($_REQUEST, 'ordem', '0');

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$pagina=getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_recursos']);
$xmin = $xtamanhoPagina * ($pagina - 1);

$sql = new BDConsulta();


$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'recursos\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'recursos\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();
  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}


$sql->adTabela('recursos');
$sql->adCampo('count(DISTINCT recursos.recurso_id)');


$sql->esqUnir('recurso_gestao','recurso_gestao','recurso_gestao_recurso = recursos.recurso_id');
if ($tarefa_id) $sql->adOnde('recurso_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas', 'tarefa_id=recurso_gestao_tarefa');
	$sql->adOnde('recurso_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('recurso_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('recurso_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('recurso_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('recurso_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('recurso_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('recurso_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('recurso_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('recurso_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('recurso_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('recurso_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('recurso_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('recurso_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('recurso_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('recurso_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('recurso_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('recurso_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('recurso_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('recurso_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('recurso_gestao_instrumento IN ('.$instrumento_id.')');

elseif ($recurso_id) $sql->adOnde('recurso_gestao_semelhante IN ('.$recurso_id.')');

elseif ($problema_id) $sql->adOnde('recurso_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('recurso_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('recurso_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('recurso_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('recurso_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('recurso_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('recurso_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('recurso_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('recurso_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('recurso_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('recurso_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('recurso_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('recurso_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('recurso_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('recurso_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('recurso_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('recurso_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('recurso_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('recurso_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('recurso_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('recurso_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('recurso_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('recurso_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('recurso_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('recurso_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('recurso_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('recurso_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('recurso_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('recurso_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('recurso_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('recurso_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('recurso_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('recurso_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('recurso_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('recurso_gestao_pdcl_item IN ('.$pdcl_item_id.')');	


if ($filtro_prioridade_recurso){
	$sql->esqUnir('priorizacao', 'priorizacao', 'recursos.recurso_id=priorizacao_recurso');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_recurso.')');
	}
if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'recursos.recurso_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
	$sql->adOnde('recurso_dept='.(int)$dept_id.' OR recurso_depts.departamento_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
	$sql->adOnde('recurso_dept IN ('.$lista_depts.') OR recurso_depts.departamento_id IN ('.$lista_depts.')');
	}
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('recurso_cia', 'recurso_cia', 'recursos.recurso_id=recurso_cia_recurso');
	$sql->adOnde('recurso_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR recurso_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
if ($usuario_id) {
	$sql->esqUnir('recurso_usuarios', 'recurso_usuarios', 'recursos.recurso_id=recurso_usuarios.recurso_id');
	$sql->adOnde('recurso_responsavel = '.(int)$usuario_id.' OR recurso_usuarios.usuario_id='.(int)$usuario_id);
	}

if ($recurso_tipo) $sql->adOnde('recurso_tipo = '.(int)$recurso_tipo);
if ($recurso_ano) $sql->adOnde('recurso_ano = "'.$recurso_ano.'"');
if ($recurso_ugr) $sql->adOnde('recurso_ugr = "'.$recurso_ugr.'"');
if ($recurso_ptres) $sql->adOnde('recurso_ptres =  "'.$recurso_ptres.'"');
if ($recurso_credito_adicional) $sql->adOnde('recurso_credito_adicional =  "'.$recurso_credito_adicional.'"');
if ($recurso_movimentacao_orcamentaria) $sql->adOnde('recurso_movimentacao_orcamentaria =  "'.$recurso_movimentacao_orcamentaria.'"');
if ($recurso_identificador_uso) $sql->adOnde('recurso_identificador_uso =  "'.$recurso_identificador_uso.'"');
if ($recurso_pesquisa) $sql->adOnde('(recurso_nome LIKE \'%'.$recurso_pesquisa.'%\' OR recurso_chave LIKE \'%'.$recurso_pesquisa.'%\' OR recurso_nota LIKE \'%'.$recurso_pesquisa.'%\')');
if ($tab==0 || $m!='recursos') $sql->adOnde('recurso_ativo=1');
elseif ($tab==1) $sql->adOnde('recurso_ativo!=1 OR recurso_ativo IS NULL');
$xtotalregistros = $sql->Resultado();
$sql->limpar();






$sql->adTabela('recursos');
$sql->adCampo('recursos.*');

$sql->esqUnir('recurso_gestao','recurso_gestao','recurso_gestao_recurso = recursos.recurso_id');
if ($tarefa_id) $sql->adOnde('recurso_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas', 'tarefa_id=recurso_gestao_tarefa');
	$sql->adOnde('recurso_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('recurso_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('recurso_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('recurso_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('recurso_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('recurso_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('recurso_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('recurso_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('recurso_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('recurso_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('recurso_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('recurso_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('recurso_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('recurso_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('recurso_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('recurso_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('recurso_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('recurso_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('recurso_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('recurso_gestao_instrumento IN ('.$instrumento_id.')');

elseif ($recurso_id) $sql->adOnde('recurso_gestao_semelhante IN ('.$recurso_id.')');

elseif ($problema_id) $sql->adOnde('recurso_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('recurso_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('recurso_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('recurso_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('recurso_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('recurso_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('recurso_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('recurso_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('recurso_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('recurso_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('recurso_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('recurso_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('recurso_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('recurso_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('recurso_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('recurso_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('recurso_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('recurso_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('recurso_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('recurso_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('recurso_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('recurso_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('recurso_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('recurso_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('recurso_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('recurso_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('recurso_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('recurso_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('recurso_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('recurso_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('recurso_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('recurso_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('recurso_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('recurso_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('recurso_gestao_pdcl_item IN ('.$pdcl_item_id.')');	


if ($filtro_prioridade_recurso){
	$sql->esqUnir('priorizacao', 'priorizacao', 'recursos.recurso_id=priorizacao_recurso');
	if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_recurso = recursos.recurso_id AND priorizacao_modelo IN ('.$filtro_prioridade_recurso.')) AS priorizacao');
	else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_recurso = recursos.recurso_id AND priorizacao_modelo IN ('.$filtro_prioridade_recurso.')) AS priorizacao');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_recurso.')');
	}
if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'recursos.recurso_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
	$sql->adOnde('recurso_dept='.(int)$dept_id.' OR recurso_depts.departamento_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
	$sql->adOnde('recurso_dept IN ('.$lista_depts.') OR recurso_depts.departamento_id IN ('.$lista_depts.')');
	}
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('recurso_cia', 'recurso_cia', 'recursos.recurso_id=recurso_cia_recurso');
	$sql->adOnde('recurso_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR recurso_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
if ($usuario_id) {
	$sql->esqUnir('recurso_usuarios', 'recurso_usuarios', 'recursos.recurso_id=recurso_usuarios.recurso_id');
	$sql->adOnde('recurso_responsavel = '.(int)$usuario_id.' OR recurso_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($recurso_tipo) $sql->adOnde('recurso_tipo = '.(int)$recurso_tipo);
if ($recurso_ano) $sql->adOnde('recurso_ano = "'.$recurso_ano.'"');
if ($recurso_ugr) $sql->adOnde('recurso_ugr = "'.$recurso_ugr.'"');
if ($recurso_ptres) $sql->adOnde('recurso_ptres =  "'.$recurso_ptres.'"');
if ($recurso_credito_adicional) $sql->adOnde('recurso_credito_adicional =  "'.$recurso_credito_adicional.'"');
if ($recurso_movimentacao_orcamentaria) $sql->adOnde('recurso_movimentacao_orcamentaria =  "'.$recurso_movimentacao_orcamentaria.'"');
if ($recurso_identificador_uso) $sql->adOnde('recurso_identificador_uso =  "'.$recurso_identificador_uso.'"');
if ($recurso_pesquisa) $sql->adOnde('(recurso_nome LIKE \'%'.$recurso_pesquisa.'%\' OR recurso_chave LIKE \'%'.$recurso_pesquisa.'%\' OR recurso_nota LIKE \'%'.$recurso_pesquisa.'%\')');
if ($tab==0 || $m!='recursos') $sql->adOnde('recurso_ativo=1');
elseif ($tab==1) $sql->adOnde('recurso_ativo!=1 OR recurso_ativo IS NULL');
if ($Aplic->profissional) $sql->adCampo('(SELECT count(assinatura_id) FROM assinatura WHERE assinatura_aprova=1 AND assinatura_recurso=recursos.recurso_id) AS tem_aprovacao');
$sql->adGrupo('recursos.recurso_id');
$sql->adOrdem(($ordem ? $ordenar.' ASC' :  $ordenar.' DESC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$recursos = $sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Recurso', 'Recursos','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
if (!$dialogo) echo '<th style="white-space: nowrap" width="16">&nbsp;</th>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';
if ($exibir['recurso_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=recurso_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='recurso_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação.').'Cor'.dicaF().'</a></th>';
if ($exibir['recurso_chave']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=recurso_chave&ordem='.($ordem ? '0' : '1').'\');">'.dica('Código', 'Recomenda-se que todo recurso tenha um código para facilitar a catalogação.').($ordenar=='recurso_chave' ? imagem('icones/'.$seta[$ordem]) : '').'Código'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=recurso_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome do Recurso', 'Todo recurso precisa de um nome para facilitar a identificação.').($ordenar=='recurso_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome do Recurso'.dicaF().'</a></th>';
if ($Aplic->profissional && $exibir['recurso_aprovado']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=recurso_aprovado&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='recurso_aprovado' ? imagem('icones/'.$seta[$ordem]) : '').dica('Aprovado', 'Neste campo consta se foi aprovado.').'Ap.'.dicaF().'</a></th>';
if ($filtro_prioridade_recurso) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Prio.'.dicaF().'</a></th>';
if ($exibir['recurso_quantidade']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=recurso_quantidade&ordem='.($ordem ? '0' : '1').'\');">'.dica('Total', 'Total deste recurso que foi disponibilizado para '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').($ordenar=='recurso_quantidade' ? imagem('icones/'.$seta[$ordem]) : '').'Total'.dicaF().'</a></th>';
if ($exibir['recurso_custo']) echo '<th style="white-space: nowrap">'.dica('Valor', 'Valor  deste recurso que foi disponibilizado para '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Valor'.dicaF().'</th>';
if ($exibir['recurso_tipo']) {
	$tipoRecurso = getSisValor('tipoRecurso');
	echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=recurso_tipo&ordem='.($ordem ? '0' : '1').'\');">'.dica('Tipo', 'Tipo deste recurso que foi disponibilizado para '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').($ordenar=='recurso_tipo' ? imagem('icones/'.$seta[$ordem]) : '').'Tipo'.dicaF().'</a></th>';
	}
if ($exibir['recurso_nota']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=recurso_nota&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nota', 'Nota deste recurso que foi disponibilizado para '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').($ordenar=='recurso_nota' ? imagem('icones/'.$seta[$ordem]) : '').'Nota'.dicaF().'</a></th>';
if ($exibir['recurso_relacionamento']) echo '<th style="white-space: nowrap">'.dica('Relacionado', 'A quais áreas do sistema está relacionado.').'Relacionado'.dicaF().'</th>';
if ($exibir['recurso_nd']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=recurso_nd&ordem='.($ordem ? '0' : '1').'\');">'.dica('Natureza da Despesa', 'Natureza de Despesa(ND) deste recurso, se for o caso.').($ordenar=='recurso_nd' ? imagem('icones/'.$seta[$ordem]) : '').'ND'.dicaF().'</a></th>';
if ($exibir['recurso_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=recurso_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='recurso_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['recurso_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['recurso_dept']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').ucfirst($config['departamento']).dicaF().'</th>';
if ($exibir['recurso_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['recurso_responsavel']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=recurso_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='recurso_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo recurso.').'Responsável'.dicaF().'</a></th>';
if ($exibir['recurso_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados'.dicaF().'</th>';
if ($exibir['recurso_nota_icone']) echo '<th style="white-space: nowrap" width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=recurso_nota&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='recurso_nota' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nota', 'Nota deste recurso que foi disponibilizado para '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'N'.dicaF().'</a></th>';
else if ($exibir['recurso_nota']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=recurso_nota&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='recurso_nota' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nota', 'Nota deste recurso que foi disponibilizado para '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Nota'.dicaF().'</a></th>';
echo '</tr>';



$qnt=0;
foreach ($recursos as $linha) {
	if (permiteAcessarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])){
		$qnt++;
		echo '<tr>';
		$editar=permiteEditarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id']);
		if ($Aplic->profissional) $bloquear=($linha['recurso_aprovado'] && $config['trava_aprovacao'] && $linha['tem_aprovacao'] && !$Aplic->usuario_super_admin);
		else $bloquear=0;
		
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['recurso_id'].'" value="'.$linha['recurso_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['recurso_id'].'" value="'.$linha['recurso_id'].'" '.(isset($selecionado[$linha['recurso_id']]) ? 'checked="checked"' : '').' />';
		
		
		
		if (!$dialogo) echo '<td style="white-space: nowrap" width="16">'.($editar && $podeEditar && !$bloquear ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o recurso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=editar&recurso_id='.$linha['recurso_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		
		if ($exibir['recurso_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['recurso_cor'].'"><font color="'.melhorCor($linha['recurso_cor']).'">&nbsp;&nbsp;</font></td>';
		if ($exibir['recurso_chave']) echo '<td>'.dica('Chave', 'Clique neste recurso para ver os detalhes do mesmo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=ver&recurso_id='.$linha['recurso_id'].'\');">'.$linha['recurso_chave'].'</a>'.dicaF().'</td>';
		echo '<td id="recurso_nome_'.$linha['recurso_id'].'">'.(!$selecao ? dica('Nome', 'Clique neste recurso para ver os detalhes do mesmo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=ver&recurso_id='.$linha['recurso_id'].'\');">' : '').$linha['recurso_nome'].(!$selecao ? '</a>'.dicaF() : '').'</td>';

		if ($Aplic->profissional && $exibir['recurso_aprovado']) echo '<td width=30 align="center">'.($linha['recurso_aprovado'] && $linha['tem_aprovacao'] ? 'Sim' : ($linha['tem_aprovacao'] ? 'Não' : 'N/A')).'</td>';
		if ($filtro_prioridade_recurso) echo '<td align="right" style="white-space: nowrap" width=50>'.($linha['priorizacao']).'</td>';	
		if ($exibir['recurso_quantidade']) echo '<td align="right">'.($linha['recurso_tipo']==5 ? $config['simbolo_moeda'].' '.number_format(($linha['recurso_quantidade'] ? $linha['recurso_quantidade'] : 0), 2, ',', '.') : ($linha['recurso_quantidade'] ? number_format($linha['recurso_quantidade'], 2, ',', '.') : '&nbsp;')).'</td>';
		
		if ($exibir['recurso_custo']) {
			if ($linha['recurso_tipo']==1 || $linha['recurso_tipo']==2 || $linha['recurso_tipo']==3) $valor=($linha['recurso_hora_custo'] ? number_format($linha['recurso_hora_custo'], 2, ',', '.') : '&nbsp;');
			elseif ($linha['recurso_tipo']==4) $valor=($linha['recurso_custo'] ? number_format($linha['recurso_custo'], 2, ',', '.') : '&nbsp;');
			else $valor='';
			echo '<td align="right">'.$valor.'</td>';
			}

		if ($exibir['recurso_tipo']) echo '<td align=center>'.(isset($tipoRecurso[$linha['recurso_tipo']]) ? $tipoRecurso[$linha['recurso_tipo']] : '&nbsp;').'</td>';
		
		
		if ($exibir['recurso_relacionamento']){
			$sql->adTabela('recurso_gestao');
			$sql->adCampo('recurso_gestao.*');
			$sql->adOnde('recurso_gestao_recurso ='.(int)$linha['recurso_id']);	
			$sql->adOrdem('recurso_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['recurso_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['recurso_gestao_tarefa']);
					elseif ($gestao_data['recurso_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['recurso_gestao_projeto']);
					elseif ($gestao_data['recurso_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['recurso_gestao_pratica']);
					elseif ($gestao_data['recurso_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['recurso_gestao_acao']);
					elseif ($gestao_data['recurso_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['recurso_gestao_perspectiva']);
					elseif ($gestao_data['recurso_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['recurso_gestao_tema']);
					elseif ($gestao_data['recurso_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['recurso_gestao_objetivo']);
					elseif ($gestao_data['recurso_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['recurso_gestao_fator']);
					elseif ($gestao_data['recurso_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['recurso_gestao_estrategia']);
					elseif ($gestao_data['recurso_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['recurso_gestao_meta']);
					elseif ($gestao_data['recurso_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['recurso_gestao_canvas']);
					elseif ($gestao_data['recurso_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['recurso_gestao_risco']);
					elseif ($gestao_data['recurso_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['recurso_gestao_risco_resposta']);
					elseif ($gestao_data['recurso_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['recurso_gestao_indicador']);
					elseif ($gestao_data['recurso_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['recurso_gestao_calendario']);
					elseif ($gestao_data['recurso_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['recurso_gestao_monitoramento']);
					elseif ($gestao_data['recurso_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['recurso_gestao_ata']);
					elseif ($gestao_data['recurso_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['recurso_gestao_mswot']);
					elseif ($gestao_data['recurso_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['recurso_gestao_swot']);
					elseif ($gestao_data['recurso_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['recurso_gestao_operativo']);
					elseif ($gestao_data['recurso_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['recurso_gestao_instrumento']);
					
					elseif ($gestao_data['recurso_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['recurso_gestao_semelhante']);
					
					elseif ($gestao_data['recurso_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['recurso_gestao_problema']);
					elseif ($gestao_data['recurso_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['recurso_gestao_demanda']);	
					elseif ($gestao_data['recurso_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['recurso_gestao_programa']);
					elseif ($gestao_data['recurso_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['recurso_gestao_licao']);
					elseif ($gestao_data['recurso_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['recurso_gestao_evento']);
					elseif ($gestao_data['recurso_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['recurso_gestao_link']);
					elseif ($gestao_data['recurso_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['recurso_gestao_avaliacao']);
					elseif ($gestao_data['recurso_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['recurso_gestao_tgn']);
					elseif ($gestao_data['recurso_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['recurso_gestao_brainstorm']);
					elseif ($gestao_data['recurso_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['recurso_gestao_gut']);
					elseif ($gestao_data['recurso_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['recurso_gestao_causa_efeito']);
					elseif ($gestao_data['recurso_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['recurso_gestao_arquivo']);
					elseif ($gestao_data['recurso_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['recurso_gestao_forum']);
					elseif ($gestao_data['recurso_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['recurso_gestao_checklist']);
					elseif ($gestao_data['recurso_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['recurso_gestao_agenda']);
					elseif ($gestao_data['recurso_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['recurso_gestao_agrupamento']);
					elseif ($gestao_data['recurso_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['recurso_gestao_patrocinador']);
					elseif ($gestao_data['recurso_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['recurso_gestao_template']);
					elseif ($gestao_data['recurso_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['recurso_gestao_painel']);
					elseif ($gestao_data['recurso_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['recurso_gestao_painel_odometro']);
					elseif ($gestao_data['recurso_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['recurso_gestao_painel_composicao']);		
					elseif ($gestao_data['recurso_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['recurso_gestao_tr']);	
					elseif ($gestao_data['recurso_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['recurso_gestao_me']);	
					elseif ($gestao_data['recurso_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['recurso_gestao_acao_item']);	
					elseif ($gestao_data['recurso_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['recurso_gestao_beneficio']);	
					elseif ($gestao_data['recurso_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['recurso_gestao_painel_slideshow']);	
					elseif ($gestao_data['recurso_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['recurso_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['recurso_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['recurso_gestao_projeto_abertura']);	
					elseif ($gestao_data['recurso_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['recurso_gestao_plano_gestao']);
					elseif ($gestao_data['recurso_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['recurso_gestao_ssti']);	
					elseif ($gestao_data['recurso_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['recurso_gestao_laudo']);	
					elseif ($gestao_data['recurso_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['recurso_gestao_trelo']);	
					elseif ($gestao_data['recurso_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['recurso_gestao_trelo_cartao']);	
					elseif ($gestao_data['recurso_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['recurso_gestao_pdcl']);	
					elseif ($gestao_data['recurso_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['recurso_gestao_pdcl_item']);		
					}
				}	
			echo '</td>';	
			}	
		
		
		
		
		
		
		
		
		
		
		
		
		
		if ($exibir['recurso_nd']) echo '<td align="left">'.(isset($nd[$linha['recurso_nd']])? $nd[$linha['recurso_nd']] : ($linha['recurso_nd'] ? $linha['recurso_nd'] : '&nbsp;')).'</td>';
		
		
		
		
		if ($exibir['recurso_cia']) echo '<td>'.link_cia($linha['recurso_cia']).'</td>';
		if ($exibir['recurso_cias']){
			$sql->adTabela('recurso_cia');
			$sql->adCampo('recurso_cia_cia');
			$sql->adOnde('recurso_cia_recurso = '.(int)$linha['recurso_id']);
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
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['recurso_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['recurso_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
		if ($exibir['recurso_dept']) echo '<td>'.link_secao($linha['recurso_dept']).'</td>';	
		if ($exibir['recurso_depts']){
			$sql->adTabela('recurso_depts');
			$sql->adCampo('departamento_id');
			$sql->adOnde('recurso_id = '.(int)$linha['recurso_id']);
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
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['recurso_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['recurso_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
		
		
		
		
		
		
		
		
		
		if ($exibir['recurso_responsavel']) echo '<td>'.link_usuario($linha['recurso_responsavel'],'','','esquerda').'</td>';
		if ($exibir['recurso_designados']) {
			$sql->adTabela('recurso_usuarios');
			$sql->adCampo('usuario_id');
			$sql->adOnde('recurso_id = '.(int)$linha['recurso_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			$saida_quem='';
			if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['recurso_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['recurso_id'].'"><br>'.$lista.'</span>';
					}
				} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
		
		
		if ($exibir['recurso_nota_icone']) echo '<td width=16>'.($linha['recurso_nota'] ? '<a href="javascript:void(0);" onclick="ver_relato('.$linha['recurso_id'].')">'.imagem('icones/msg10000.gif','Ver Nota','Clique neste ícone '.imagem('icones/msg10000.gif').' para ver a anotação.').'</a>'  : '&nbsp;').'</td>';
		else if ($exibir['recurso_nota']) echo '<td>'.($linha['recurso_nota'] ? $linha['recurso_nota'] : '&nbsp;').'</td>';
		
		
		
		echo '</tr>';
		}
	}
if (!count($recursos)) echo '<tr><td colspan="20"><p>Nenhum recurso encontrado.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="20"><p>Não tem autorização para visualizar nenhum dos recursos.</p></td></tr>';



if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS();').'</td></tr></table></td></tr>';
echo '</table>';


?>


<script type="text/javascript">


function selecionar(recurso_id){
	var nome=document.getElementById('recurso_nome_'+recurso_id).innerHTML;
	setFechar(recurso_id, nome);
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

function ver_relato(recurso_id) {
	parent.gpwebApp.popUp('Relato', 1000, 600, 'm=recursos&a=recurso_nota&dialogo=1&recurso_id='+recurso_id, null, window);
	}	
</script>	