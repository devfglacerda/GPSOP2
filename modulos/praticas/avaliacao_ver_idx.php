<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $estilo_interface, $sql, $perms, $Aplic, $cia_id, $lista_cias, $lista_depts, $dept_id, $tab, $tabAtualId, $tabNomeAtual, $estah_tab, $st_praticas_arr, $ordem, $ordenar, $perspectiva_tab, $dialogo, $pesquisar_texto, $usuario_id, $favorito_id,
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

$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_objetivos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$ordenar=getParam($_REQUEST, 'ordenar', 'avaliacao_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('avaliacao');
$sql->adCampo('count(DISTINCT avaliacao.avaliacao_id) as soma');

if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'avaliacao.avaliacao_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}		
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_dept='.(int)$dept_id.' OR avaliacao_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_dept IN ('.$lista_depts.') OR avaliacao_dept_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('avaliacao_cia', 'avaliacao_cia', 'avaliacao.avaliacao_id=avaliacao_cia_avaliacao');
	$sql->adOnde('avaliacao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR avaliacao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('avaliacao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('avaliacao_cia IN ('.$lista_cias.')');
if ($pesquisar_texto) $sql->adOnde('avaliacao_nome LIKE \'%'.$pesquisar_texto.'%\' OR avaliacao_descricao LIKE \'%'.$pesquisar_texto.'%\'');
if ($usuario_id) {
	$sql->esqUnir('avaliacao_usuarios', 'avaliacao_usuarios', 'avaliacao_usuarios.avaliacao_id = avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_responsavel = '.(int)$usuario_id.' OR avaliacao_usuarios.usuario_id='.(int)$usuario_id);
	}

$sql->esqUnir('avaliacao_gestao','avaliacao_gestao','avaliacao_gestao_avaliacao = avaliacao.avaliacao_id');
if ($tarefa_id) $sql->adOnde('avaliacao_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=avaliacao_gestao_tarefa');
	$sql->adOnde('avaliacao_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('avaliacao_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('avaliacao_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('avaliacao_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('avaliacao_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('avaliacao_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('avaliacao_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('avaliacao_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('avaliacao_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('avaliacao_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('avaliacao_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('avaliacao_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('avaliacao_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('avaliacao_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('avaliacao_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('avaliacao_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('avaliacao_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('avaliacao_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('avaliacao_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('avaliacao_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('avaliacao_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('avaliacao_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('avaliacao_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('avaliacao_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('avaliacao_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('avaliacao_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('avaliacao_gestao_link IN ('.$link_id.')');

elseif ($avaliacao_id) $sql->adOnde('avaliacao_gestao_semelhante IN ('.$avaliacao_id.')');

elseif ($tgn_id) $sql->adOnde('avaliacao_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('avaliacao_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('avaliacao_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('avaliacao_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('avaliacao_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('avaliacao_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('avaliacao_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('avaliacao_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('avaliacao_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('avaliacao_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('avaliacao_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('avaliacao_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('avaliacao_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('avaliacao_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('avaliacao_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('avaliacao_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('avaliacao_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('avaliacao_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('avaliacao_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('avaliacao_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('avaliacao_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('avaliacao_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('avaliacao_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('avaliacao_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('avaliacao_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('avaliacao_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('avaliacao_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('avaliacao_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	
if ($a=='avaliacao_lista'){
	if ($tab==0) $sql->adOnde('avaliacao_ativa=1');
	elseif ($tab==1) $sql->adOnde('avaliacao_ativa!=1 OR avaliacao_ativa IS NULL');
	}
else $sql->adOnde('avaliacao_ativa=1');	
	
	
$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('avaliacao');
$sql->adCampo('avaliacao.avaliacao_id, avaliacao_nome, avaliacao_responsavel, avaliacao_acesso, avaliacao_cor, avaliacao_descricao, formatar_data(avaliacao_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(avaliacao_fim, \'%d/%m/%Y %H:%i\') AS fim');

if ($favorito_id){
	$sql->internoUnir('favorito_lista', 'favorito_lista', 'avaliacao.avaliacao_id=favorito_lista_campo');
	$sql->adOnde('favorito_lista_favorito='.(int)$favorito_id);
	}		
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_dept='.(int)$dept_id.' OR avaliacao_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_dept IN ('.$lista_depts.') OR avaliacao_dept_dept IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('avaliacao_cia', 'avaliacao_cia', 'avaliacao.avaliacao_id=avaliacao_cia_avaliacao');
	$sql->adOnde('avaliacao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR avaliacao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$lista_cias) $sql->adOnde('avaliacao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('avaliacao_cia IN ('.$lista_cias.')');
if ($pesquisar_texto) $sql->adOnde('avaliacao_nome LIKE \'%'.$pesquisar_texto.'%\' OR avaliacao_descricao LIKE \'%'.$pesquisar_texto.'%\'');
if ($usuario_id) {
	$sql->esqUnir('avaliacao_usuarios', 'avaliacao_usuarios', 'avaliacao_usuarios.avaliacao_id = avaliacao.avaliacao_id');
	$sql->adOnde('avaliacao_responsavel = '.(int)$usuario_id.' OR avaliacao_usuarios.usuario_id='.(int)$usuario_id);
	}

$sql->esqUnir('avaliacao_gestao','avaliacao_gestao','avaliacao_gestao_avaliacao = avaliacao.avaliacao_id');
if ($tarefa_id) $sql->adOnde('avaliacao_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=avaliacao_gestao_tarefa');
	$sql->adOnde('avaliacao_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('avaliacao_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('avaliacao_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('avaliacao_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('avaliacao_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('avaliacao_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('avaliacao_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('avaliacao_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('avaliacao_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('avaliacao_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('avaliacao_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('avaliacao_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('avaliacao_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('avaliacao_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('avaliacao_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('avaliacao_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('avaliacao_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('avaliacao_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('avaliacao_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('avaliacao_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('avaliacao_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('avaliacao_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('avaliacao_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('avaliacao_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('avaliacao_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('avaliacao_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('avaliacao_gestao_link IN ('.$link_id.')');

elseif ($avaliacao_id) $sql->adOnde('avaliacao_gestao_semelhante IN ('.$avaliacao_id.')');

elseif ($tgn_id) $sql->adOnde('avaliacao_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('avaliacao_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('avaliacao_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('avaliacao_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('avaliacao_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('avaliacao_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('avaliacao_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('avaliacao_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('avaliacao_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('avaliacao_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('avaliacao_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('avaliacao_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('avaliacao_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('avaliacao_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('avaliacao_gestao_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('avaliacao_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('avaliacao_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('avaliacao_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('avaliacao_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('avaliacao_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('avaliacao_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('avaliacao_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('avaliacao_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('avaliacao_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('avaliacao_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('avaliacao_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('avaliacao_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('avaliacao_gestao_pdcl_item IN ('.$pdcl_item_id.')');	

if ($a=='avaliacao_lista'){
	if ($tab==0) $sql->adOnde('avaliacao_ativa=1');
	elseif ($tab==1) $sql->adOnde('avaliacao_ativa!=1 OR avaliacao_ativa IS NULL');
	}
else $sql->adOnde('avaliacao_ativa=1');	


$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$sql->adGrupo('avaliacao.avaliacao_id');
$avaliacoes=$sql->Lista();
$sql->limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;

if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Avaliação', 'Avaliaçãos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

if (!$impressao && !$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor da Avaliação', 'Neste campo fica a cor de identificação da avaliação.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da Avaliação', 'Neste campo fica um nome para identificação da avaliação.').'Nome'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição da Avaliação', 'Neste campo fica a descrição da avaliação.').'Descrição'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap">'.dica('Relacionada', 'A que área a avaliação está relacionada.').'Relacionada'.dicaF().'</th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pela avaliação.').'Responsável'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Início', 'A data de ínicio da avaliação.').'Início'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=avaliacao_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='avaliacao_fim' ? imagem('icones/'.$seta[$ordem]) : '').dica('Término', 'A data de término da avaliação.').'Término'.dicaF().'</a></th>';


echo '</tr>';

$qnt=0;
foreach ($avaliacoes as $linha) {
	if (permiteAcessarAvaliacao($linha['avaliacao_acesso'],$linha['avaliacao_id'])){
		$qnt++;
		$editar=permiteEditarAvaliacao($linha['avaliacao_acesso'],$linha['avaliacao_id']);
		if ($edicao && !$editar) continue;
		echo '<tr>';
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['avaliacao_id'].'" value="'.$linha['avaliacao_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['avaliacao_id'].'" value="'.$linha['avaliacao_id'].'" '.(isset($selecionado[$linha['avaliacao_id']]) ? 'checked="checked"' : '').' />';

		if (!$impressao && !$dialogo) echo '<td style="white-space: nowrap" width="16">'.($editar ? dica('Editar Avaliação', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a avaliação.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=avaliacao_editar&avaliacao_id='.$linha['avaliacao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['avaliacao_cor'].'"><font color="'.melhorCor($linha['avaliacao_cor']).'">&nbsp;&nbsp;</font></td>';
		if ($selecao) echo '<td id="avaliacao_nome_'.$linha['avaliacao_id'].'">'.$linha['avaliacao_nome'].'</td>';
		else echo '<td>'.link_avaliacao($linha['avaliacao_id']).'</td>';
		echo '<td>'.($linha['avaliacao_descricao'] ? $linha['avaliacao_descricao'] : '&nbsp;').'</td>';
		
		

		$sql->adTabela('avaliacao_gestao');
		$sql->adCampo('avaliacao_gestao.*');
		$sql->adOnde('avaliacao_gestao_avaliacao ='.(int)$linha['avaliacao_id']);	
		$sql->adOrdem('avaliacao_gestao_ordem');
		$lista = $sql->Lista();
		$sql->limpar();
		$qnt_gestao=0;
		echo '<td>';	
		if (count($lista)) {
			foreach($lista as $gestao_data){
				if ($gestao_data['avaliacao_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['avaliacao_gestao_tarefa']);
				elseif ($gestao_data['avaliacao_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['avaliacao_gestao_projeto']);
				elseif ($gestao_data['avaliacao_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['avaliacao_gestao_pratica']);
				elseif ($gestao_data['avaliacao_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['avaliacao_gestao_acao']);
				elseif ($gestao_data['avaliacao_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['avaliacao_gestao_perspectiva']);
				elseif ($gestao_data['avaliacao_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['avaliacao_gestao_tema']);
				elseif ($gestao_data['avaliacao_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['avaliacao_gestao_objetivo']);
				elseif ($gestao_data['avaliacao_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['avaliacao_gestao_fator']);
				elseif ($gestao_data['avaliacao_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['avaliacao_gestao_estrategia']);
				elseif ($gestao_data['avaliacao_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['avaliacao_gestao_meta']);
				elseif ($gestao_data['avaliacao_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['avaliacao_gestao_canvas']);
				elseif ($gestao_data['avaliacao_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['avaliacao_gestao_risco']);
				elseif ($gestao_data['avaliacao_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['avaliacao_gestao_risco_resposta']);
				elseif ($gestao_data['avaliacao_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['avaliacao_gestao_indicador']);
				elseif ($gestao_data['avaliacao_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['avaliacao_gestao_calendario']);
				elseif ($gestao_data['avaliacao_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['avaliacao_gestao_monitoramento']);
				elseif ($gestao_data['avaliacao_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['avaliacao_gestao_ata']);
				elseif ($gestao_data['avaliacao_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['avaliacao_gestao_mswot']);
				elseif ($gestao_data['avaliacao_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['avaliacao_gestao_swot']);
				elseif ($gestao_data['avaliacao_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['avaliacao_gestao_operativo']);
				elseif ($gestao_data['avaliacao_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['avaliacao_gestao_instrumento']);
				elseif ($gestao_data['avaliacao_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['avaliacao_gestao_recurso']);
				elseif ($gestao_data['avaliacao_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['avaliacao_gestao_problema']);
				elseif ($gestao_data['avaliacao_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['avaliacao_gestao_demanda']);	
				elseif ($gestao_data['avaliacao_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['avaliacao_gestao_programa']);
				elseif ($gestao_data['avaliacao_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['avaliacao_gestao_licao']);
				elseif ($gestao_data['avaliacao_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['avaliacao_gestao_evento']);
				elseif ($gestao_data['avaliacao_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['avaliacao_gestao_link']);
				
				elseif ($gestao_data['avaliacao_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['avaliacao_gestao_semelhante']);
				
				elseif ($gestao_data['avaliacao_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['avaliacao_gestao_tgn']);
				elseif ($gestao_data['avaliacao_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['avaliacao_gestao_brainstorm']);
				elseif ($gestao_data['avaliacao_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['avaliacao_gestao_gut']);
				elseif ($gestao_data['avaliacao_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['avaliacao_gestao_causa_efeito']);
				elseif ($gestao_data['avaliacao_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['avaliacao_gestao_arquivo']);
				elseif ($gestao_data['avaliacao_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['avaliacao_gestao_forum']);
				elseif ($gestao_data['avaliacao_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['avaliacao_gestao_checklist']);
				elseif ($gestao_data['avaliacao_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['avaliacao_gestao_agenda']);
				elseif ($gestao_data['avaliacao_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['avaliacao_gestao_agrupamento']);
				elseif ($gestao_data['avaliacao_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['avaliacao_gestao_patrocinador']);
				elseif ($gestao_data['avaliacao_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['avaliacao_gestao_template']);
				elseif ($gestao_data['avaliacao_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['avaliacao_gestao_painel']);
				elseif ($gestao_data['avaliacao_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['avaliacao_gestao_painel_odometro']);
				elseif ($gestao_data['avaliacao_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['avaliacao_gestao_painel_composicao']);		
				elseif ($gestao_data['avaliacao_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['avaliacao_gestao_tr']);	
				elseif ($gestao_data['avaliacao_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['avaliacao_gestao_me']);	
				elseif ($gestao_data['avaliacao_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['avaliacao_gestao_acao_item']);	
				elseif ($gestao_data['avaliacao_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['avaliacao_gestao_beneficio']);	
				elseif ($gestao_data['avaliacao_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['avaliacao_gestao_painel_slideshow']);	
				elseif ($gestao_data['avaliacao_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['avaliacao_gestao_projeto_viabilidade']);	
				elseif ($gestao_data['avaliacao_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['avaliacao_gestao_projeto_abertura']);	
				elseif ($gestao_data['avaliacao_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['avaliacao_gestao_plano_gestao']);	
				elseif ($gestao_data['avaliacao_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['avaliacao_gestao_ssti']);	
				elseif ($gestao_data['avaliacao_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['avaliacao_gestao_laudo']);	
				elseif ($gestao_data['avaliacao_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['avaliacao_gestao_trelo']);	
				elseif ($gestao_data['avaliacao_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['avaliacao_gestao_trelo_cartao']);	
				elseif ($gestao_data['avaliacao_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['avaliacao_gestao_pdcl']);	
				elseif ($gestao_data['avaliacao_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['avaliacao_gestao_pdcl_item']);	

				}
			}	
		echo '</td>';	
		
		echo '<td>'.link_usuario($linha['avaliacao_responsavel'],'','','esquerda').'</td>';
		echo '<td width=110>'.$linha['inicio'].'</td>';
		echo '<td width=110>'.$linha['fim'].'</td>';
		echo '</tr>';
		}
	}
if (!count($avaliacoes)) echo '<tr><td colspan=20><p>Nenhuma avaliação encontrada.</p></td></tr>';
elseif(count($avaliacoes) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer das avaliações.</p></td></tr>';
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS();').'</td></tr></table></td></tr>';

echo '</table>';

?>

<script type="text/javascript">

function selecionar(avaliacao_id){
	var nome=document.getElementById('avaliacao_nome_'+avaliacao_id).innerHTML;
	setFechar(avaliacao_id, nome);
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