<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $envolvimento, $selecao, $chamarVolta, $selecionado, $edicao, $estilo_interface, $sql, $perms, $dialogo, $Aplic, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $favorito_id, $usuario_id, $pesquisar_texto, $u, $a, $m,
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
	$pg_id;

$vetor_selecionado=explode(',', $selecionado);
$selecionado=array();
foreach($vetor_selecionado as $valor) $selecionado[$valor]=$valor;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$pagina=getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = 90000;
$xmin = $xtamanhoPagina * ($pagina - 1); 




$ordenar=getParam($_REQUEST, 'ordenar', 'calendario_nome');
$ordem=getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('calendario');
$sql->adCampo('count(DISTINCT calendario.calendario_id)');
if ($Aplic->profissional){
	$sql->esqUnir('calendario_gestao','calendario_gestao','calendario_gestao_calendario = calendario.calendario_id');
	if ($tarefa_id) $sql->adOnde('calendario_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=calendario_gestao_tarefa');
		$sql->adOnde('calendario_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('calendario_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('calendario_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('calendario_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('calendario_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('calendario_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('calendario_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('calendario_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('calendario_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('calendario_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('calendario_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('calendario_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('calendario_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	
	elseif ($calendario_id) $sql->adOnde('calendario_gestao_semelhante IN ('.$calendario_id.')');
	
	elseif ($monitoramento_id) $sql->adOnde('calendario_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('calendario_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('calendario_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('calendario_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('calendario_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('calendario_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('calendario_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('calendario_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('calendario_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('calendario_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('calendario_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('calendario_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('calendario_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('calendario_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('calendario_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('calendario_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('calendario_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('calendario_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('calendario_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('calendario_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('calendario_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('calendario_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('calendario_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('calendario_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('calendario_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('calendario_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('calendario_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('calendario_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('calendario_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('calendario_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('calendario_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('calendario_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('calendario_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('calendario_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('calendario_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('calendario_gestao_plano_gestao IN ('.$pg_id.')');
	}
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept_calendario=calendario.calendario_id');
	$sql->adOnde('calendario_dept='.(int)$dept_id.' OR calendario_dept.dept_id='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept.calendario_id=calendario.calendario_id');
	$sql->adOnde('calendario_dept IN ('.$lista_depts.') OR calendario_dept.dept_id IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('calendario_cia', 'calendario_cia', 'calendario.calendario_id=calendario_cia_calendario');
	$sql->adOnde('calendario_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR calendario_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('calendario_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('calendario_cia IN ('.$lista_cias.')');	
		
if ($tab==0 || $a!='calendario_lista') $sql->adOnde('calendario_ativo=1');
elseif ($tab==1) $sql->adOnde('calendario_ativo!=1 OR calendario_ativo IS NULL');	

if($usuario_id) {
	$sql->esqUnir('calendario_usuario', 'calendario_usuario', 'calendario.calendario_id=calendario_usuario_calendario');
	$sql->adOnde('calendario_usuario='.(int)$usuario_id.' OR calendario_usuario_usuario='.(int)$usuario_id);
	}
	
if ($pesquisar_texto) $sql->adOnde('calendario_nome LIKE \'%'.$pesquisar_texto.'%\' OR calendario_descricao LIKE \'%'.$pesquisar_texto.'%\'');

$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('calendario');
$sql->adCampo('calendario.calendario_id, calendario_nome, calendario_usuario, calendario_acesso, calendario_cor, calendario_descricao');
if ($Aplic->profissional){
	$sql->esqUnir('calendario_gestao','calendario_gestao','calendario_gestao_calendario = calendario.calendario_id');
	if ($tarefa_id) $sql->adOnde('calendario_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=calendario_gestao_tarefa');
		$sql->adOnde('calendario_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('calendario_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('calendario_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('calendario_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('calendario_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('calendario_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('calendario_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('calendario_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('calendario_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('calendario_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('calendario_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('calendario_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('calendario_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	
	elseif ($calendario_id) $sql->adOnde('calendario_gestao_semelhante IN ('.$calendario_id.')');
	
	elseif ($monitoramento_id) $sql->adOnde('calendario_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('calendario_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('calendario_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('calendario_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('calendario_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('calendario_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('calendario_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('calendario_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('calendario_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('calendario_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('calendario_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('calendario_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('calendario_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('calendario_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('calendario_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('calendario_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('calendario_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('calendario_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('calendario_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('calendario_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('calendario_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('calendario_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('calendario_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('calendario_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('calendario_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('calendario_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('calendario_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('calendario_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('calendario_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('calendario_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('calendario_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('calendario_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('calendario_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('calendario_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('calendario_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('calendario_gestao_plano_gestao IN ('.$pg_id.')');
	}

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept_calendario=calendario.calendario_id');
	$sql->adOnde('calendario_dept='.(int)$dept_id.' OR calendario_dept.dept_id='.(int)$dept_id);
	}	
elseif ($lista_depts) {
	$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept.calendario_id=calendario.calendario_id');
	$sql->adOnde('calendario_dept IN ('.$lista_depts.') OR calendario_dept.dept_id IN ('.$lista_depts.')');
	}	
elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('calendario_cia', 'calendario_cia', 'calendario.calendario_id=calendario_cia_calendario');
	$sql->adOnde('calendario_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR calendario_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('calendario_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('calendario_cia IN ('.$lista_cias.')');	
		
if ($tab==0 || $a!='calendario_lista') $sql->adOnde('calendario_ativo=1');
elseif ($tab==1) $sql->adOnde('calendario_ativo!=1 OR calendario_ativo IS NULL');	

if($usuario_id) {
	$sql->esqUnir('calendario_usuario', 'calendario_usuario', 'calendario.calendario_id=calendario_usuario_calendario');
	$sql->adOnde('calendario_usuario='.(int)$usuario_id.' OR calendario_usuario_usuario='.(int)$usuario_id);
	}
if ($tab==0) $sql->adOnde('calendario_ativo=1');
elseif ($tab==1) $sql->adOnde('calendario_ativo!=1 OR calendario_ativo IS NULL');
if ($pesquisar_texto) $sql->adOnde('calendario_nome LIKE \'%'.$pesquisar_texto.'%\' OR calendario_descricao LIKE \'%'.$pesquisar_texto.'%\'');
$sql->adGrupo('calendario.calendario_id');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$calendario=$sql->Lista();
$sql->limpar();



$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Agenda Coletiva', 'Agenda Coletiva','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$impressao && !$dialogo) echo '<th style="white-space: nowrap">&nbsp;</th>';
if ($selecao) echo '<th style="white-space: nowrap" width="16">'.($selecao==2 ? '<input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" />' : '').'</th>';	

echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=calendario_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='calendario_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor da '.'Agenda Coletiva'.'', 'Neste campo fica a cor de identificação da agenda coletiva.').'Cor'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=calendario_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='calendario_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome da '.'Agenda Coletiva'.'', 'Neste campo fica um nome para identificação da agenda coletiva.').'Nome'.dicaF().'</a></th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=calendario_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='calendario_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição da '.'Agenda Coletiva'.'', 'Neste campo fica a descrição da agenda coletiva.').'Descrição'.dicaF().'</a></th>';
if ($Aplic->profissional) echo '<th style="white-space: nowrap">'.dica('Relacionada', 'A que área a agenda coletiva está relacionada.').'Relacionada'.dicaF().'</th>';
echo '<th style="white-space: nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=calendario_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='calendario_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pela agenda coletiva.').'Responsável'.dicaF().'</a></th>';

echo '</tr>';

$qnt=0;
foreach ($calendario as $linha) {
	$qnt++;
	
	if (permiteAcessarCalendario($linha['calendario_acesso'],$linha['calendario_id'])){
		$editar=permiteEditarCalendario($linha['calendario_acesso'],$linha['calendario_id']);
		if ($edicao && !$editar) continue;
		echo '<tr>';
		if (!$impressao && !$dialogo) echo '<td style="white-space: nowrap" width="16">'.($editar ? dica('Editar '.'Agenda Coletiva'.'', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a agenda coletiva.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&u='.$u.'&a=calendario_editar&calendario_id='.$linha['calendario_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		if ($selecao==1) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['calendario_id'].'" value="'.$linha['calendario_id'].'"  onclick="selecionar(this.value)" />';
		if ($selecao==2) echo '<td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$linha['calendario_id'].'" value="'.$linha['calendario_id'].'" '.(isset($selecionado[$linha['calendario_id']]) ? 'checked="checked"' : '').' />';
	
	
		echo '<td id="ignore_td_" width="16" align="right" style="background-color:#'.$linha['calendario_cor'].'"><font color="'.melhorCor($linha['calendario_cor']).'">&nbsp;&nbsp;</font></td>';
		
		if ($selecao) echo '<td id="calendario_nome_'.$linha['calendario_id'].'">'.$linha['calendario_nome'].'</td>';
		else echo '<td>'.link_calendario($linha['calendario_id']).'</td>';
		
		echo '<td>'.($linha['calendario_descricao'] ? $linha['calendario_descricao']: '&nbsp;').'</td>';
		
		
		if ($Aplic->profissional){
				$sql->adTabela('calendario_gestao');
				$sql->adCampo('calendario_gestao.*');
				$sql->adOnde('calendario_gestao_calendario ='.(int)$linha['calendario_id']);	
				$sql->adOrdem('calendario_gestao_ordem');
				$lista = $sql->Lista();
				$sql->limpar();
				$qnt_gestao=0;
				echo '<td>';	
				if (count($lista)) {
					foreach($lista as $gestao_data){
						if ($gestao_data['calendario_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['calendario_gestao_tarefa']);
						elseif ($gestao_data['calendario_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['calendario_gestao_projeto']);
						elseif ($gestao_data['calendario_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['calendario_gestao_pratica']);
						elseif ($gestao_data['calendario_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['calendario_gestao_acao']);
						elseif ($gestao_data['calendario_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['calendario_gestao_perspectiva']);
						elseif ($gestao_data['calendario_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['calendario_gestao_tema']);
						elseif ($gestao_data['calendario_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['calendario_gestao_objetivo']);
						elseif ($gestao_data['calendario_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['calendario_gestao_fator']);
						elseif ($gestao_data['calendario_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['calendario_gestao_estrategia']);
						elseif ($gestao_data['calendario_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['calendario_gestao_meta']);
						elseif ($gestao_data['calendario_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['calendario_gestao_canvas']);
						elseif ($gestao_data['calendario_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['calendario_gestao_risco']);
						elseif ($gestao_data['calendario_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['calendario_gestao_risco_resposta']);
						elseif ($gestao_data['calendario_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['calendario_gestao_indicador']);
						
						elseif ($gestao_data['calendario_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['calendario_gestao_semelhante']);
						
						elseif ($gestao_data['calendario_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['calendario_gestao_monitoramento']);
						elseif ($gestao_data['calendario_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['calendario_gestao_ata']);
						elseif ($gestao_data['calendario_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['calendario_gestao_mswot']);
						elseif ($gestao_data['calendario_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['calendario_gestao_swot']);
						elseif ($gestao_data['calendario_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['calendario_gestao_operativo']);
						elseif ($gestao_data['calendario_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['calendario_gestao_instrumento']);
						elseif ($gestao_data['calendario_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['calendario_gestao_recurso']);
						elseif ($gestao_data['calendario_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['calendario_gestao_problema']);
						elseif ($gestao_data['calendario_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['calendario_gestao_demanda']);	
						elseif ($gestao_data['calendario_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['calendario_gestao_programa']);
						elseif ($gestao_data['calendario_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['calendario_gestao_licao']);
						elseif ($gestao_data['calendario_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['calendario_gestao_evento']);
						elseif ($gestao_data['calendario_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['calendario_gestao_link']);
						elseif ($gestao_data['calendario_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['calendario_gestao_avaliacao']);
						elseif ($gestao_data['calendario_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['calendario_gestao_tgn']);
						elseif ($gestao_data['calendario_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['calendario_gestao_brainstorm']);
						elseif ($gestao_data['calendario_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['calendario_gestao_gut']);
						elseif ($gestao_data['calendario_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['calendario_gestao_causa_efeito']);
						elseif ($gestao_data['calendario_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['calendario_gestao_arquivo']);
						elseif ($gestao_data['calendario_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['calendario_gestao_forum']);
						elseif ($gestao_data['calendario_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['calendario_gestao_checklist']);
						elseif ($gestao_data['calendario_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['calendario_gestao_agenda']);
						elseif ($gestao_data['calendario_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['calendario_gestao_agrupamento']);
						elseif ($gestao_data['calendario_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['calendario_gestao_patrocinador']);
						elseif ($gestao_data['calendario_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['calendario_gestao_template']);
						elseif ($gestao_data['calendario_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['calendario_gestao_painel']);
						elseif ($gestao_data['calendario_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['calendario_gestao_painel_odometro']);
						elseif ($gestao_data['calendario_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['calendario_gestao_painel_composicao']);		
						elseif ($gestao_data['calendario_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['calendario_gestao_tr']);	
						elseif ($gestao_data['calendario_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['calendario_gestao_me']);	
						elseif ($gestao_data['calendario_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['calendario_gestao_acao_item']);	
						elseif ($gestao_data['calendario_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['calendario_gestao_beneficio']);	
						elseif ($gestao_data['calendario_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['calendario_gestao_painel_slideshow']);	
						elseif ($gestao_data['calendario_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['calendario_gestao_projeto_viabilidade']);	
						elseif ($gestao_data['calendario_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['calendario_gestao_projeto_abertura']);	
						elseif ($gestao_data['calendario_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['calendario_gestao_plano_gestao']);	
						}
					}	
				echo '</td>';	
				}	
		
		echo '<td>'.link_usuario($linha['calendario_usuario'],'','','esquerda').'</td>';
		echo '</tr>';
		}

	}
if (!count($calendario)) echo '<tr><td colspan=20><p>Nenhuma agenda coletiva encontrada.</p></td></tr>';
if ($selecao==2) echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', 'Confirmar', 'Clique neste botão para confirmar as opções marcadas.','','selecionar_multiplo();').'</td><td>'.botao('nenhum', 'Nenhum', 'Clique neste botão para retornar nenhum.','','javascript:setFechar(null, null)').'</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste botão para fechar esta janela de seleção','','javascript:fecharPopupExtJS();').'</td></tr></table></td></tr>';

echo '</table>';

?>

<script type="text/javascript">

function selecionar(calendario_id){
	var nome=document.getElementById('calendario_nome_'+calendario_id).innerHTML;
	setFechar(calendario_id, nome);
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