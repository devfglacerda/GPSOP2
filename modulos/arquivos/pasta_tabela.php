<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo_pasta é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo_pasta diretamente.');

global $envolvimento, $a, $tab, $dialogo, $estilo_interface, $arquivo_pasta_pasta, $usuario_id, $arquivo_pasta_participante, $pesquisar_texto,  $lista_depts, $lista_cias, $Aplic, $podeEditar, $negar1, $podeAcessar, $podeAdmin, $perms, $cia_id, $dept_id,$m, $a,$filtro_prioridade_arquivo_pasta,
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
	$pdcl_item_id,
	
	$arquivo_pasta_id,
	$arquivo_pasta_usuario;

echo '<form name="frm_arquivo_pasta" id="frm_arquivo_pasta" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';

$ordenar=getParam($_REQUEST, 'ordenar', 'arquivo_pasta_nome');

$ordem=getParam($_REQUEST, 'ordem', '0');



include_once BASE_DIR.'/modulos/arquivos/arquivos.class.php';

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_pastas']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$arquivo_pasta_tipos = getSisValor('TipoArquivo');


$sql = new BDConsulta;

$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'arquivo_pastas\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'arquivo_pastas\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}


$sql->adTabela('arquivo_pasta', 'arquivo_pasta');
$sql->adCampo('count(DISTINCT arquivo_pasta_id)');

$sql->esqUnir('arquivo_pasta_gestao','arquivo_pasta_gestao','arquivo_pasta_gestao_pasta = arquivo_pasta.arquivo_pasta_id');
if ($tarefa_id) $sql->adOnde('arquivo_pasta_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=arquivo_pasta_gestao_tarefa');
	$sql->adOnde('arquivo_pasta_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_pasta_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('arquivo_pasta_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('arquivo_pasta_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('arquivo_pasta_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('arquivo_pasta_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('arquivo_pasta_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('arquivo_pasta_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('arquivo_pasta_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('arquivo_pasta_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('arquivo_pasta_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('arquivo_pasta_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('arquivo_pasta_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('arquivo_pasta_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('arquivo_pasta_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('arquivo_pasta_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('arquivo_pasta_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('arquivo_pasta_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('arquivo_pasta_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('arquivo_pasta_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('arquivo_pasta_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('arquivo_pasta_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('arquivo_pasta_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('arquivo_pasta_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('arquivo_pasta_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('arquivo_pasta_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('arquivo_pasta_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('arquivo_pasta_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('arquivo_pasta_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('arquivo_pasta_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('arquivo_pasta_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('arquivo_pasta_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('arquivo_pasta_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('arquivo_pasta_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('arquivo_pasta_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('arquivo_pasta_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('arquivo_pasta_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('arquivo_pasta_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('arquivo_pasta_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('arquivo_pasta_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('arquivo_pasta_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('arquivo_pasta_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('arquivo_pasta_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('arquivo_pasta_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('arquivo_pasta_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('arquivo_pasta_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('arquivo_pasta_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('arquivo_pasta_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('arquivo_pasta_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('arquivo_pasta_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('arquivo_pasta_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('arquivo_pasta_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('arquivo_pasta_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('arquivo_pasta_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('arquivo_pasta_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('arquivo_pasta_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

elseif ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta_gestao_semelhante IN ('.$arquivo_pasta_id.')');
elseif ($arquivo_pasta_usuario) $sql->adOnde('arquivo_pasta_gestao_usuario IN ('.$arquivo_pasta_usuario.')');
else $sql->adOnde('arquivo_pasta_gestao_usuario=0 OR arquivo_pasta_gestao_usuario IS NULL OR arquivo_pasta_gestao_usuario = '.(int)$Aplic->usuario_id);
	
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('arquivo_pasta_dept','arquivo_pasta_dept', 'arquivo_pasta_dept.arquivo_pasta_dept_pasta=arquivo_pasta.arquivo_pasta_id');
	$sql->adOnde('arquivo_pasta_dept='.(int)$dept_id.' OR arquivo_pasta_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('arquivo_pasta_dept','arquivo_pasta_dept', 'arquivo_pasta_dept.arquivo_pasta_dept_pasta=arquivo_pasta.arquivo_pasta_id');
	$sql->adOnde('arquivo_pasta_dept IN ('.$lista_depts.') OR arquivo_pasta_dept_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('arquivo_pasta_cia', 'arquivo_pasta_cia', 'arquivo_pasta_cia_pasta=arquivo_pasta_id');
	$sql->adOnde('arquivo_pasta_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR arquivo_pasta_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('arquivo_pasta_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('arquivo_pasta_cia IN ('.$lista_cias.')');
	
if ($pesquisar_texto) $sql->adOnde('arquivo_pasta_nome LIKE \'%'.$pesquisar_texto.'%\' OR arquivo_pasta_descricao LIKE \'%'.$pesquisar_texto.'%\'');	
if ($tab==0 || $tab==3 || $a!='pasta_lista') $sql->adOnde('arquivo_pasta_ativo=1');
elseif ($tab==1) $sql->adOnde('arquivo_pasta_ativo!=1 OR arquivo_pasta_ativo IS NULL');	
if ($usuario_id) {
	$sql->esqUnir('arquivo_pasta_usuario', 'arquivo_pasta_usuario', 'arquivo_pasta_usuario_pasta = arquivo_pasta.arquivo_pasta_id');
	$sql->adOnde('arquivo_pasta_dono = '.(int)$usuario_id.' OR arquivo_pasta_usuario_usuario = '.(int)$usuario_id);
	}	
$xtotalregistros=$sql->resultado();
$sql->limpar();	



$arquivo_pasta = array();
$arquivo_pasta_versoes = array();


$sql->adTabela('arquivo_pasta');
$sql->adCampo('arquivo_pasta.*');

$sql->esqUnir('arquivo_pasta_gestao','arquivo_pasta_gestao','arquivo_pasta_gestao_pasta = arquivo_pasta.arquivo_pasta_id');
if ($tarefa_id) $sql->adOnde('arquivo_pasta_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=arquivo_pasta_gestao_tarefa');
	$sql->adOnde('arquivo_pasta_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_pasta_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('arquivo_pasta_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('arquivo_pasta_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('arquivo_pasta_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('arquivo_pasta_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('arquivo_pasta_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('arquivo_pasta_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('arquivo_pasta_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('arquivo_pasta_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('arquivo_pasta_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('arquivo_pasta_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('arquivo_pasta_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('arquivo_pasta_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('arquivo_pasta_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('arquivo_pasta_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('arquivo_pasta_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('arquivo_pasta_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('arquivo_pasta_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('arquivo_pasta_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('arquivo_pasta_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('arquivo_pasta_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('arquivo_pasta_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('arquivo_pasta_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('arquivo_pasta_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('arquivo_pasta_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('arquivo_pasta_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('arquivo_pasta_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('arquivo_pasta_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('arquivo_pasta_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('arquivo_pasta_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('arquivo_pasta_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('arquivo_pasta_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('arquivo_pasta_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('arquivo_pasta_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('arquivo_pasta_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('arquivo_pasta_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('arquivo_pasta_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('arquivo_pasta_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('arquivo_pasta_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('arquivo_pasta_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('arquivo_pasta_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('arquivo_pasta_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('arquivo_pasta_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('arquivo_pasta_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('arquivo_pasta_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('arquivo_pasta_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('arquivo_pasta_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('arquivo_pasta_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('arquivo_pasta_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('arquivo_pasta_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('arquivo_pasta_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('arquivo_pasta_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('arquivo_pasta_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('arquivo_pasta_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('arquivo_pasta_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

elseif ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta_gestao_semelhante IN ('.$arquivo_pasta_id.')');
elseif ($arquivo_pasta_usuario) $sql->adOnde('arquivo_pasta_gestao_usuario IN ('.$arquivo_pasta_usuario.')');
else $sql->adOnde('arquivo_pasta_gestao_usuario=0 OR arquivo_pasta_gestao_usuario IS NULL OR arquivo_pasta_gestao_usuario = '.(int)$Aplic->usuario_id);

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('arquivo_pasta_dept','arquivo_pasta_dept', 'arquivo_pasta_dept.arquivo_pasta_dept_pasta=arquivo_pasta.arquivo_pasta_id');
	$sql->adOnde('arquivo_pasta_dept='.(int)$dept_id.' OR arquivo_pasta_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('arquivo_pasta_dept','arquivo_pasta_dept', 'arquivo_pasta_dept.arquivo_pasta_dept_pasta=arquivo_pasta.arquivo_pasta_id');
	$sql->adOnde('arquivo_pasta_dept IN ('.$lista_depts.') OR arquivo_pasta_dept_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('arquivo_pasta_cia', 'arquivo_pasta_cia', 'arquivo_pasta_cia_pasta=arquivo_pasta_id');
	$sql->adOnde('arquivo_pasta_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR arquivo_pasta_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('arquivo_pasta_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('arquivo_pasta_cia IN ('.$lista_cias.')');

if ($pesquisar_texto) $sql->adOnde('arquivo_pasta_nome LIKE \'%'.$pesquisar_texto.'%\' OR arquivo_pasta_descricao LIKE \'%'.$pesquisar_texto.'%\'');	
if ($tab==0 || $tab==3 || $a!='pasta_lista') $sql->adOnde('arquivo_pasta_ativo=1');
elseif ($tab==1) $sql->adOnde('arquivo_pasta_ativo!=1 OR arquivo_pasta_ativo IS NULL');	
if ($usuario_id) {
	$sql->esqUnir('arquivo_pasta_usuario', 'arquivo_pasta_usuario', 'arquivo_pasta_usuario_pasta = arquivo_pasta.arquivo_pasta_id');
	$sql->adOnde('arquivo_pasta_dono = '.(int)$usuario_id.' OR arquivo_pasta_usuario_usuario = '.(int)$usuario_id);
	}	
$sql->adGrupo('arquivo_pasta.arquivo_pasta_id');		
$sql->setLimite($xmin, $xtamanhoPagina);
$sql->adOrdem($ordenar.($ordem ? ' ASC' : ' DESC'));
$arquivo_pasta = $sql->Lista();

$sql->limpar();





$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Pasta', 'Pastas','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th width=16">&nbsp;</th>';
if ($exibir['arquivo_pasta_cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=arquivo_pasta_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='arquivo_pasta_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação das pastas.').'Cor'.dicaF().'</a></th>';

echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&ordenar=arquivo_pasta_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome', 'Clique para ordenar pelo nome das pastas.').($ordenar=='arquivo_pasta_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome'.dicaF().'</a></th>';
if ($exibir['arquivo_pasta_descricao']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&ordenar=arquivo_pasta_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Descrição', 'Clique para ordenar pela descrição das pastas.<br><br>Ao se enviar um arquivo_pasta, pode-se escrever um texto explicativo para facilitar a compreensão do rquivo e facilitar futuras pesquisas.').($ordenar=='arquivo_pasta_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Descrição'.dicaF().'</th>';
if ($exibir['arquivo_pasta_relacionado']) echo '<th style="white-space: nowrap">'.dica('Relacionada', 'As áreas as quais as pastas estão relacionadas .').'Relacionada'.dicaF().'</th>';
if ($exibir['arquivo_pasta_cia']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=arquivo_pasta_cia&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenar=='arquivo_pasta_cia' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if ($exibir['arquivo_pasta_cias']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['arquivo_pasta_dept']) echo '<th style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=arquivo_pasta_dept&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' responsável.').($ordenar=='arquivo_pasta_dept' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['departamento']).dicaF().'</a></th>';
if ($exibir['arquivo_pasta_depts']) echo '<th style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['arquivo_pasta_dono']) echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=arquivo_pasta_dono&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='arquivo_pasta_dono' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pela pasta.').'Responsável'.dicaF().'</a></th>';
if ($exibir['arquivo_pasta_designados']) echo '<th style="white-space: nowrap">'.dica('Designados', 'Neste campo fica os designados para a pasta.').'Designados'.dicaF().'</th>';

echo '</tr>';

$ultimo = null;


$qnt=0;

foreach ($arquivo_pasta as $linha) {

	if (permiteAcessarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id'])){	
		$qnt++;
		$editar=($podeEditar && permiteEditarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id']));

		echo '<tr>';
		echo '<td style="white-space: nowrap" width="16">'.($editar ? dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a pasta.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar_pasta&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		if ($exibir['arquivo_pasta_cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['arquivo_pasta_cor'].'"><font color="'.melhorCor($linha['arquivo_pasta_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>'.dica($linha['arquivo_pasta_nome'], $linha['arquivo_pasta_descricao']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=ver_pasta&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');">'.$linha['arquivo_pasta_nome'].dicaF().'</a></td>';
		if ($exibir['arquivo_pasta_descricao']) echo '<td>'.($linha['arquivo_pasta_descricao'] ? $linha['arquivo_pasta_descricao'] : '&nbsp;').'</td>';
		
		if ($exibir['arquivo_pasta_relacionado']){
			$sql->adTabela('arquivo_pasta_gestao');
			$sql->adCampo('arquivo_pasta_gestao.*');
			$sql->adOnde('arquivo_pasta_gestao_pasta ='.(int)$linha['arquivo_pasta_id']);	
			$sql->adOrdem('arquivo_pasta_gestao_ordem');
			$lista = $sql->Lista();
			$sql->limpar();
			$qnt_gestao=0;
			echo '<td>';	
			if (count($lista)) {
				foreach($lista as $gestao_data){
					if ($gestao_data['arquivo_pasta_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['arquivo_pasta_gestao_tarefa']);
					elseif ($gestao_data['arquivo_pasta_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['arquivo_pasta_gestao_projeto']);
					elseif ($gestao_data['arquivo_pasta_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['arquivo_pasta_gestao_pratica']);
					elseif ($gestao_data['arquivo_pasta_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['arquivo_pasta_gestao_acao']);
					elseif ($gestao_data['arquivo_pasta_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['arquivo_pasta_gestao_perspectiva']);
					elseif ($gestao_data['arquivo_pasta_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['arquivo_pasta_gestao_tema']);
					elseif ($gestao_data['arquivo_pasta_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['arquivo_pasta_gestao_objetivo']);
					elseif ($gestao_data['arquivo_pasta_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['arquivo_pasta_gestao_fator']);
					elseif ($gestao_data['arquivo_pasta_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['arquivo_pasta_gestao_estrategia']);
					elseif ($gestao_data['arquivo_pasta_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['arquivo_pasta_gestao_meta']);
					elseif ($gestao_data['arquivo_pasta_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['arquivo_pasta_gestao_canvas']);
					elseif ($gestao_data['arquivo_pasta_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['arquivo_pasta_gestao_risco']);
					elseif ($gestao_data['arquivo_pasta_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['arquivo_pasta_gestao_risco_resposta']);
					elseif ($gestao_data['arquivo_pasta_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['arquivo_pasta_gestao_indicador']);
					elseif ($gestao_data['arquivo_pasta_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['arquivo_pasta_gestao_calendario']);
					elseif ($gestao_data['arquivo_pasta_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['arquivo_pasta_gestao_monitoramento']);
					elseif ($gestao_data['arquivo_pasta_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['arquivo_pasta_gestao_ata']);
					elseif ($gestao_data['arquivo_pasta_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['arquivo_pasta_gestao_mswot']);
					elseif ($gestao_data['arquivo_pasta_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['arquivo_pasta_gestao_swot']);
					elseif ($gestao_data['arquivo_pasta_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['arquivo_pasta_gestao_operativo']);
					elseif ($gestao_data['arquivo_pasta_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['arquivo_pasta_gestao_instrumento']);
					elseif ($gestao_data['arquivo_pasta_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['arquivo_pasta_gestao_recurso']);
					elseif ($gestao_data['arquivo_pasta_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['arquivo_pasta_gestao_problema']);
					elseif ($gestao_data['arquivo_pasta_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['arquivo_pasta_gestao_demanda']);	
					elseif ($gestao_data['arquivo_pasta_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['arquivo_pasta_gestao_programa']);
					elseif ($gestao_data['arquivo_pasta_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['arquivo_pasta_gestao_licao']);
					elseif ($gestao_data['arquivo_pasta_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['arquivo_pasta_gestao_evento']);
					elseif ($gestao_data['arquivo_pasta_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['arquivo_pasta_gestao_link']);
					elseif ($gestao_data['arquivo_pasta_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['arquivo_pasta_gestao_avaliacao']);
					elseif ($gestao_data['arquivo_pasta_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['arquivo_pasta_gestao_tgn']);
					elseif ($gestao_data['arquivo_pasta_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['arquivo_pasta_gestao_brainstorm']);
					elseif ($gestao_data['arquivo_pasta_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['arquivo_pasta_gestao_gut']);
					elseif ($gestao_data['arquivo_pasta_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['arquivo_pasta_gestao_causa_efeito']);
					elseif ($gestao_data['arquivo_pasta_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['arquivo_pasta_gestao_arquivo']);
					elseif ($gestao_data['arquivo_pasta_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['arquivo_pasta_gestao_forum']);
					elseif ($gestao_data['arquivo_pasta_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['arquivo_pasta_gestao_checklist']);
					elseif ($gestao_data['arquivo_pasta_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['arquivo_pasta_gestao_agenda']);
					elseif ($gestao_data['arquivo_pasta_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['arquivo_pasta_gestao_agrupamento']);
					elseif ($gestao_data['arquivo_pasta_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['arquivo_pasta_gestao_patrocinador']);
					elseif ($gestao_data['arquivo_pasta_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['arquivo_pasta_gestao_template']);
					elseif ($gestao_data['arquivo_pasta_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['arquivo_pasta_gestao_painel']);
					elseif ($gestao_data['arquivo_pasta_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['arquivo_pasta_gestao_painel_odometro']);
					elseif ($gestao_data['arquivo_pasta_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['arquivo_pasta_gestao_painel_composicao']);		
					elseif ($gestao_data['arquivo_pasta_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['arquivo_pasta_gestao_tr']);	
					elseif ($gestao_data['arquivo_pasta_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['arquivo_pasta_gestao_me']);	
					elseif ($gestao_data['arquivo_pasta_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['arquivo_pasta_gestao_acao_item']);	
					elseif ($gestao_data['arquivo_pasta_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['arquivo_pasta_gestao_beneficio']);	
					elseif ($gestao_data['arquivo_pasta_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['arquivo_pasta_gestao_painel_slideshow']);	
					elseif ($gestao_data['arquivo_pasta_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['arquivo_pasta_gestao_projeto_viabilidade']);	
					elseif ($gestao_data['arquivo_pasta_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['arquivo_pasta_gestao_projeto_abertura']);	
					elseif ($gestao_data['arquivo_pasta_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['arquivo_pasta_gestao_plano_gestao']);	
					elseif ($gestao_data['arquivo_pasta_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['arquivo_pasta_gestao_ssti']);	
					elseif ($gestao_data['arquivo_pasta_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['arquivo_pasta_gestao_laudo']);	
					elseif ($gestao_data['arquivo_pasta_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['arquivo_pasta_gestao_trelo']);	
					elseif ($gestao_data['arquivo_pasta_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['arquivo_pasta_gestao_trelo_cartao']);	
					elseif ($gestao_data['arquivo_pasta_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['arquivo_pasta_gestao_pdcl']);	
					elseif ($gestao_data['arquivo_pasta_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['arquivo_pasta_gestao_pdcl_item']);	
					
					elseif ($gestao_data['arquivo_pasta_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pasta_p.png').link_pasta($gestao_data['arquivo_pasta_gestao_semelhante']);	
					elseif ($gestao_data['arquivo_pasta_gestao_usuario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/usuario_p.gif').link_usuario($gestao_data['arquivo_pasta_gestao_usuario']);	
					}
				}	
			echo '</td>';	
			}	
		if ($exibir['arquivo_pasta_cia']) echo '<td>'.link_cia($linha['arquivo_pasta_cia']).'</td>';
		
		if ($exibir['arquivo_pasta_cias']){
			$sql->adTabela('arquivo_pasta_cia');
			$sql->adCampo('arquivo_pasta_cia_cia');
			$sql->adOnde('arquivo_pasta_cia_pasta = '.(int)$linha['arquivo_pasta_id']);
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
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias_'.$linha['arquivo_pasta_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['arquivo_pasta_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
			
		if ($exibir['arquivo_pasta_dept']) echo '<td>'.link_secao($linha['arquivo_pasta_dept']).'</td>';	
		
		if ($exibir['arquivo_pasta_depts']){
			$sql->adTabela('arquivo_pasta_dept');
			$sql->adCampo('arquivo_pasta_dept_dept');
			$sql->adOnde('arquivo_pasta_dept_pasta = '.(int)$linha['arquivo_pasta_id']);
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
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts_'.$linha['arquivo_pasta_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['arquivo_pasta_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
	
		if ($exibir['arquivo_pasta_dono']) echo '<td>'.link_usuario($linha['arquivo_pasta_dono'],'','','esquerda').'</td>';
		
		if ($exibir['arquivo_pasta_designados']) {
			$sql->adTabela('arquivo_pasta_usuario');
			$sql->adCampo('arquivo_pasta_usuario_usuario');
			$sql->adOnde('arquivo_pasta_usuario_pasta = '.(int)$linha['arquivo_pasta_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['arquivo_pasta_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['arquivo_pasta_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
		
		
		
		
		echo '</tr>';
		}
	} 
if (!count($arquivo_pasta)) echo '<tr><td colspan="13"><p>Nenhuma pasta encontrada.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="13"><p>Não tem autorização para visualizar nenhuma das pastas.</p></td></tr>';		
echo '</table>';

echo '</form>';
?>

<script type="text/JavaScript">
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function ver_historico(arquivo_pasta_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Histórico de Retirada', 800, 600, 'm=arquivo_pasta&a=ver_historico_pro&arquivo_pasta_id='+arquivo_pasta_id, null, window);
	else window.open('./index.php?m=arquivo_pasta&a=ver_historico_pro&arquivo_pasta_id='+arquivo_pasta_id, 'Histórico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

	
function numero_arquivo_pasta_selecionado(){
	var f = eval('document.frm_arquivo_pasta');
	var qnt=0;
	for (var i=0, i_cmp=f.elements.length; i<i_cmp; i++) {
		var e = f.elements[i];
		if(e.type == 'checkbox' && e.checked && e.name.substring(0, 13)=='vetor_arquivo_pasta') qnt++;
		}
	return qnt; 	
	}	
	
function string_arquivo_pasta_selecionado(){
	var f = eval('document.frm_arquivo_pasta');
	var saida='';
	for (var i=0, i_cmp=f.elements.length; i<i_cmp; i++) {
		var e = f.elements[i];
		if(e.type == 'checkbox' && e.checked && e.name.substring(0, 13)=='vetor_arquivo_pasta') saida+=(saida ? ',' :'')+e.value;
		}
	return saida; 	
	}		
	
	
function clonar_arquivo_pasta(){
	if (numero_arquivo_pasta_selecionado() == 0) alert('Selecione ao menos uma pasta');
	else {
		var arquivo_pasta=string_arquivo_pasta_selecionado();
		parent.gpwebApp.popUp('Seleção de repositório dos arquivo_pasta selecionados', 500, 500, 'm=arquivo_pasta&a=selecionar_clone_pro&dialogo=1&arquivo_pasta='+arquivo_pasta, window.setClonar_arquivo_pasta, window);
		}
	}		
	
function marca_sel_todas() {
  with(document.getElementById('frm_arquivo_pasta')) {
	  for(i=0;i<elements.length;i++) {
			thiselm = elements[i];
			thiselm.checked = !thiselm.checked
      }
    }
  }	
	
function expandir(id){
  var elemento = document.getElementById(id);
  elemento.style.display = (elemento.style.display == 'none') ? '' : 'none';
	}
</script>
