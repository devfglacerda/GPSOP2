<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function exibir_cias($cias){
	global $config;
	$cias_selecionadas=explode(',', $cias);
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0>';
			$saida_cias.= '<tr><td class="texto" style="width:400px;">'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';		
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			} 
	else 	$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_cias',"innerHTML", utf8_encode($saida_cias));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_cias");	
	

function exibir_usuarios($usuarios){
	global $config;
	$usuarios_selecionados=explode(',', $usuarios);
	$saida_usuarios='';
	if (count($usuarios_selecionados)) {
			$saida_usuarios.= '<table cellpadding=0 cellspacing=0>';
			$saida_usuarios.= '<tr><td class="texto" style="width:400px;">'.link_usuario($usuarios_selecionados[0],'','','esquerda');
			$qnt_lista_usuarios=count($usuarios_selecionados);
			if ($qnt_lista_usuarios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';		
					$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
					}
			$saida_usuarios.= '</td></tr></table>';
			} 
	else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_usuarios',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios");

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_secao($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			} 
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");	


function mudar_posicao_gestao($ordem, $calendario_gestao_id, $direcao, $calendario_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $calendario_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('calendario_gestao');
		$sql->adOnde('calendario_gestao_id != '.(int)$calendario_gestao_id);
		if ($uuid) $sql->adOnde('calendario_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('calendario_gestao_calendario = '.(int)$calendario_id);
		$sql->adOrdem('calendario_gestao_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('calendario_gestao');
			$sql->adAtualizar('calendario_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('calendario_gestao_id = '.(int)$calendario_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('calendario_gestao');
					$sql->adAtualizar('calendario_gestao_ordem', $idx);
					$sql->adOnde('calendario_gestao_id = '.(int)$acao['calendario_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('calendario_gestao');
					$sql->adAtualizar('calendario_gestao_ordem', $idx + 1);
					$sql->adOnde('calendario_gestao_id = '.(int)$acao['calendario_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($calendario_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$calendario_id=0, 
	$uuid='',  
	
	$calendario_projeto=null,
	$calendario_tarefa=null,
	$calendario_perspectiva=null,
	$calendario_tema=null,
	$calendario_objetivo=null,
	$calendario_fator=null,
	$calendario_estrategia=null,
	$calendario_meta=null,
	$calendario_pratica=null,
	$calendario_acao=null,
	$calendario_canvas=null,
	$calendario_risco=null,
	$calendario_risco_resposta=null,
	$calendario_indicador=null,
	$calendario_calendario=null,
	$calendario_monitoramento=null,
	$calendario_ata=null,
	$calendario_mswot=null,
	$calendario_swot=null,
	$calendario_operativo=null,
	$calendario_instrumento=null,
	$calendario_recurso=null,
	$calendario_problema=null,
	$calendario_demanda=null,
	$calendario_programa=null,
	$calendario_licao=null,
	$calendario_evento=null,
	$calendario_link=null,
	$calendario_avaliacao=null,
	$calendario_tgn=null,
	$calendario_brainstorm=null,
	$calendario_gut=null,
	$calendario_causa_efeito=null,
	$calendario_arquivo=null,
	$calendario_forum=null,
	$calendario_checklist=null,
	$calendario_agenda=null,
	$calendario_agrupamento=null,
	$calendario_patrocinador=null,
	$calendario_template=null,
	$calendario_painel=null,
	$calendario_painel_odometro=null,
	$calendario_painel_composicao=null,
	$calendario_tr=null,
	$calendario_me=null,
	$calendario_acao_item=null,
	$calendario_beneficio=null,
	$calendario_painel_slideshow=null,
	$calendario_projeto_viabilidade=null,
	$calendario_projeto_abertura=null,
	$calendario_plano_gestao=null
	)
	{
	if (
		$calendario_projeto || 
		$calendario_tarefa || 
		$calendario_perspectiva || 
		$calendario_tema || 
		$calendario_objetivo || 
		$calendario_fator || 
		$calendario_estrategia || 
		$calendario_meta || 
		$calendario_pratica || 
		$calendario_acao || 
		$calendario_canvas || 
		$calendario_risco || 
		$calendario_risco_resposta || 
		$calendario_indicador || 
		$calendario_calendario || 
		$calendario_monitoramento || 
		$calendario_ata || 
		$calendario_mswot || 
		$calendario_swot || 
		$calendario_operativo || 
		$calendario_instrumento || 
		$calendario_recurso || 
		$calendario_problema || 
		$calendario_demanda || 
		$calendario_programa || 
		$calendario_licao || 
		$calendario_evento || 
		$calendario_link || 
		$calendario_avaliacao || 
		$calendario_tgn || 
		$calendario_brainstorm || 
		$calendario_gut || 
		$calendario_causa_efeito || 
		$calendario_arquivo || 
		$calendario_forum || 
		$calendario_checklist || 
		$calendario_agenda || 
		$calendario_agrupamento || 
		$calendario_patrocinador || 
		$calendario_template || 
		$calendario_painel || 
		$calendario_painel_odometro || 
		$calendario_painel_composicao || 
		$calendario_tr || 
		$calendario_me || 
		$calendario_acao_item || 
		$calendario_beneficio || 
		$calendario_painel_slideshow || 
		$calendario_projeto_viabilidade || 
		$calendario_projeto_abertura || 
		$calendario_plano_gestao
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('calendario_gestao');
			if ($uuid) $sql->adOnde('calendario_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('calendario_gestao_calendario ='.(int)$calendario_id);	
			$sql->exec();
			}
		
		
		//verificar se já não inseriu antes
		$sql->adTabela('calendario_gestao');
		$sql->adCampo('count(calendario_gestao_id)');
		if ($uuid) $sql->adOnde('calendario_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('calendario_gestao_calendario ='.(int)$calendario_id);	
		if ($calendario_tarefa) $sql->adOnde('calendario_gestao_tarefa='.(int)$calendario_tarefa);
		elseif ($calendario_projeto) $sql->adOnde('calendario_gestao_projeto='.(int)$calendario_projeto);
		elseif ($calendario_perspectiva) $sql->adOnde('calendario_gestao_perspectiva='.(int)$calendario_perspectiva);
		elseif ($calendario_tema) $sql->adOnde('calendario_gestao_tema='.(int)$calendario_tema);
		elseif ($calendario_objetivo) $sql->adOnde('calendario_gestao_objetivo='.(int)$calendario_objetivo);
		elseif ($calendario_fator) $sql->adOnde('calendario_gestao_fator='.(int)$calendario_fator);
		elseif ($calendario_estrategia) $sql->adOnde('calendario_gestao_estrategia='.(int)$calendario_estrategia);
		elseif ($calendario_acao) $sql->adOnde('calendario_gestao_acao='.(int)$calendario_acao);
		elseif ($calendario_pratica) $sql->adOnde('calendario_gestao_pratica='.(int)$calendario_pratica);
		elseif ($calendario_meta) $sql->adOnde('calendario_gestao_meta='.(int)$calendario_meta);
		elseif ($calendario_canvas) $sql->adOnde('calendario_gestao_canvas='.(int)$calendario_canvas);
		elseif ($calendario_risco) $sql->adOnde('calendario_gestao_risco='.(int)$calendario_risco);
		elseif ($calendario_risco_resposta) $sql->adOnde('calendario_gestao_risco_resposta='.(int)$calendario_risco_resposta);
		elseif ($calendario_indicador) $sql->adOnde('calendario_gestao_indicador='.(int)$calendario_indicador);
		
		elseif ($calendario_calendario) $sql->adOnde('calendario_gestao_semelhante='.(int)$calendario_calendario);
		
		elseif ($calendario_monitoramento) $sql->adOnde('calendario_gestao_monitoramento='.(int)$calendario_monitoramento);
		elseif ($calendario_ata) $sql->adOnde('calendario_gestao_ata='.(int)$calendario_ata);
		elseif ($calendario_mswot) $sql->adOnde('calendario_gestao_mswot='.(int)$calendario_mswot);
		elseif ($calendario_swot) $sql->adOnde('calendario_gestao_swot='.(int)$calendario_swot);
		elseif ($calendario_operativo) $sql->adOnde('calendario_gestao_operativo='.(int)$calendario_operativo);
		elseif ($calendario_instrumento) $sql->adOnde('calendario_gestao_instrumento='.(int)$calendario_instrumento);
		elseif ($calendario_recurso) $sql->adOnde('calendario_gestao_recurso='.(int)$calendario_recurso);
		elseif ($calendario_problema) $sql->adOnde('calendario_gestao_problema='.(int)$calendario_problema);
		elseif ($calendario_demanda) $sql->adOnde('calendario_gestao_demanda='.(int)$calendario_demanda);
		elseif ($calendario_programa) $sql->adOnde('calendario_gestao_programa='.(int)$calendario_programa);
		elseif ($calendario_licao) $sql->adOnde('calendario_gestao_licao='.(int)$calendario_licao);
		elseif ($calendario_evento) $sql->adOnde('calendario_gestao_evento='.(int)$calendario_evento);
		elseif ($calendario_link) $sql->adOnde('calendario_gestao_link='.(int)$calendario_link);
		elseif ($calendario_avaliacao) $sql->adOnde('calendario_gestao_avaliacao='.(int)$calendario_avaliacao);
		elseif ($calendario_tgn) $sql->adOnde('calendario_gestao_tgn='.(int)$calendario_tgn);
		elseif ($calendario_brainstorm) $sql->adOnde('calendario_gestao_brainstorm='.(int)$calendario_brainstorm);
		elseif ($calendario_gut) $sql->adOnde('calendario_gestao_gut='.(int)$calendario_gut);
		elseif ($calendario_causa_efeito) $sql->adOnde('calendario_gestao_causa_efeito='.(int)$calendario_causa_efeito);
		elseif ($calendario_arquivo) $sql->adOnde('calendario_gestao_arquivo='.(int)$calendario_arquivo);
		elseif ($calendario_forum) $sql->adOnde('calendario_gestao_forum='.(int)$calendario_forum);
		elseif ($calendario_checklist) $sql->adOnde('calendario_gestao_checklist='.(int)$calendario_checklist);
		elseif ($calendario_agenda) $sql->adOnde('calendario_gestao_agenda='.(int)$calendario_agenda);
		elseif ($calendario_agrupamento) $sql->adOnde('calendario_gestao_agrupamento='.(int)$calendario_agrupamento);
		elseif ($calendario_patrocinador) $sql->adOnde('calendario_gestao_patrocinador='.(int)$calendario_patrocinador);
		elseif ($calendario_template) $sql->adOnde('calendario_gestao_template='.(int)$calendario_template);
		elseif ($calendario_painel) $sql->adOnde('calendario_gestao_painel='.(int)$calendario_painel);
		elseif ($calendario_painel_odometro) $sql->adOnde('calendario_gestao_painel_odometro='.(int)$calendario_painel_odometro);
		elseif ($calendario_painel_composicao) $sql->adOnde('calendario_gestao_painel_composicao='.(int)$calendario_painel_composicao);
		elseif ($calendario_tr) $sql->adOnde('calendario_gestao_tr='.(int)$calendario_tr);
		elseif ($calendario_me) $sql->adOnde('calendario_gestao_me='.(int)$calendario_me);
		elseif ($calendario_acao_item) $sql->adOnde('calendario_gestao_acao_item='.(int)$calendario_acao_item);
		elseif ($calendario_beneficio) $sql->adOnde('calendario_gestao_beneficio='.(int)$calendario_beneficio);
		elseif ($calendario_painel_slideshow) $sql->adOnde('calendario_gestao_painel_slideshow='.(int)$calendario_painel_slideshow);
		elseif ($calendario_projeto_viabilidade) $sql->adOnde('calendario_gestao_projeto_viabilidade='.(int)$calendario_projeto_viabilidade);
		elseif ($calendario_projeto_abertura) $sql->adOnde('calendario_gestao_projeto_abertura='.(int)$calendario_projeto_abertura);
		elseif ($calendario_plano_gestao) $sql->adOnde('calendario_gestao_plano_gestao='.(int)$calendario_plano_gestao);

	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('calendario_gestao');
			$sql->adCampo('MAX(calendario_gestao_ordem)');
			if ($uuid) $sql->adOnde('calendario_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('calendario_gestao_calendario ='.(int)$calendario_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('calendario_gestao');
			if ($uuid) $sql->adInserir('calendario_gestao_uuid', $uuid);
			else $sql->adInserir('calendario_gestao_calendario', (int)$calendario_id);
			
			if ($calendario_tarefa) $sql->adInserir('calendario_gestao_tarefa', (int)$calendario_tarefa);
			if ($calendario_projeto) $sql->adInserir('calendario_gestao_projeto', (int)$calendario_projeto);
			elseif ($calendario_perspectiva) $sql->adInserir('calendario_gestao_perspectiva', (int)$calendario_perspectiva);
			elseif ($calendario_tema) $sql->adInserir('calendario_gestao_tema', (int)$calendario_tema);
			elseif ($calendario_objetivo) $sql->adInserir('calendario_gestao_objetivo', (int)$calendario_objetivo);
			elseif ($calendario_fator) $sql->adInserir('calendario_gestao_fator', (int)$calendario_fator);
			elseif ($calendario_estrategia) $sql->adInserir('calendario_gestao_estrategia', (int)$calendario_estrategia);
			elseif ($calendario_acao) $sql->adInserir('calendario_gestao_acao', (int)$calendario_acao);
			elseif ($calendario_pratica) $sql->adInserir('calendario_gestao_pratica', (int)$calendario_pratica);
			elseif ($calendario_meta) $sql->adInserir('calendario_gestao_meta', (int)$calendario_meta);
			elseif ($calendario_canvas) $sql->adInserir('calendario_gestao_canvas', (int)$calendario_canvas);
			elseif ($calendario_risco) $sql->adInserir('calendario_gestao_risco', (int)$calendario_risco);
			elseif ($calendario_risco_resposta) $sql->adInserir('calendario_gestao_risco_resposta', (int)$calendario_risco_resposta);
			elseif ($calendario_indicador) $sql->adInserir('calendario_gestao_indicador', (int)$calendario_indicador);
			
			elseif ($calendario_calendario) $sql->adInserir('calendario_gestao_semelhante', (int)$calendario_calendario);
			
			elseif ($calendario_monitoramento) $sql->adInserir('calendario_gestao_monitoramento', (int)$calendario_monitoramento);
			elseif ($calendario_ata) $sql->adInserir('calendario_gestao_ata', (int)$calendario_ata);
			elseif ($calendario_mswot) $sql->adInserir('calendario_gestao_mswot', (int)$calendario_mswot);
			elseif ($calendario_swot) $sql->adInserir('calendario_gestao_swot', (int)$calendario_swot);
			elseif ($calendario_operativo) $sql->adInserir('calendario_gestao_operativo', (int)$calendario_operativo);
			elseif ($calendario_instrumento) $sql->adInserir('calendario_gestao_instrumento', (int)$calendario_instrumento);
			elseif ($calendario_recurso) $sql->adInserir('calendario_gestao_recurso', (int)$calendario_recurso);
			elseif ($calendario_problema) $sql->adInserir('calendario_gestao_problema', (int)$calendario_problema);
			elseif ($calendario_demanda) $sql->adInserir('calendario_gestao_demanda', (int)$calendario_demanda);
			elseif ($calendario_programa) $sql->adInserir('calendario_gestao_programa', (int)$calendario_programa);
			elseif ($calendario_licao) $sql->adInserir('calendario_gestao_licao', (int)$calendario_licao);
			elseif ($calendario_evento) $sql->adInserir('calendario_gestao_evento', (int)$calendario_evento);
			elseif ($calendario_link) $sql->adInserir('calendario_gestao_link', (int)$calendario_link);
			elseif ($calendario_avaliacao) $sql->adInserir('calendario_gestao_avaliacao', (int)$calendario_avaliacao);
			elseif ($calendario_tgn) $sql->adInserir('calendario_gestao_tgn', (int)$calendario_tgn);
			elseif ($calendario_brainstorm) $sql->adInserir('calendario_gestao_brainstorm', (int)$calendario_brainstorm);
			elseif ($calendario_gut) $sql->adInserir('calendario_gestao_gut', (int)$calendario_gut);
			elseif ($calendario_causa_efeito) $sql->adInserir('calendario_gestao_causa_efeito', (int)$calendario_causa_efeito);
			elseif ($calendario_arquivo) $sql->adInserir('calendario_gestao_arquivo', (int)$calendario_arquivo);
			elseif ($calendario_forum) $sql->adInserir('calendario_gestao_forum', (int)$calendario_forum);
			elseif ($calendario_checklist) $sql->adInserir('calendario_gestao_checklist', (int)$calendario_checklist);
			elseif ($calendario_agenda) $sql->adInserir('calendario_gestao_agenda', (int)$calendario_agenda);
			elseif ($calendario_agrupamento) $sql->adInserir('calendario_gestao_agrupamento', (int)$calendario_agrupamento);
			elseif ($calendario_patrocinador) $sql->adInserir('calendario_gestao_patrocinador', (int)$calendario_patrocinador);
			elseif ($calendario_template) $sql->adInserir('calendario_gestao_template', (int)$calendario_template);
			elseif ($calendario_painel) $sql->adInserir('calendario_gestao_painel', (int)$calendario_painel);
			elseif ($calendario_painel_odometro) $sql->adInserir('calendario_gestao_painel_odometro', (int)$calendario_painel_odometro);
			elseif ($calendario_painel_composicao) $sql->adInserir('calendario_gestao_painel_composicao', (int)$calendario_painel_composicao);
			elseif ($calendario_tr) $sql->adInserir('calendario_gestao_tr', (int)$calendario_tr);
			elseif ($calendario_me) $sql->adInserir('calendario_gestao_me', (int)$calendario_me);
			elseif ($calendario_acao_item) $sql->adInserir('calendario_gestao_acao_item', (int)$calendario_acao_item);
			elseif ($calendario_beneficio) $sql->adInserir('calendario_gestao_beneficio', (int)$calendario_beneficio);
			elseif ($calendario_painel_slideshow) $sql->adInserir('calendario_gestao_painel_slideshow', (int)$calendario_painel_slideshow);
			elseif ($calendario_projeto_viabilidade) $sql->adInserir('calendario_gestao_projeto_viabilidade', (int)$calendario_projeto_viabilidade);
			elseif ($calendario_projeto_abertura) $sql->adInserir('calendario_gestao_projeto_abertura', (int)$calendario_projeto_abertura);
			elseif ($calendario_plano_gestao) $sql->adInserir('calendario_gestao_plano_gestao', (int)$calendario_plano_gestao);
			$sql->adInserir('calendario_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($calendario_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($calendario_id=0, $uuid='', $calendario_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('calendario_gestao');
	$sql->adOnde('calendario_gestao_id='.(int)$calendario_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($calendario_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($calendario_id=0, $uuid=''){	
	$saida=atualizar_gestao($calendario_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($calendario_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('calendario_gestao');
	$sql->adCampo('calendario_gestao.*');
	if ($uuid) $sql->adOnde('calendario_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('calendario_gestao_calendario ='.(int)$calendario_id);	
	$sql->adOrdem('calendario_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['calendario_gestao_ordem'].', '.$gestao_data['calendario_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['calendario_gestao_ordem'].', '.$gestao_data['calendario_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['calendario_gestao_ordem'].', '.$gestao_data['calendario_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['calendario_gestao_ordem'].', '.$gestao_data['calendario_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['calendario_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['calendario_gestao_tarefa']).'</td>';
		elseif ($gestao_data['calendario_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['calendario_gestao_projeto']).'</td>';
		elseif ($gestao_data['calendario_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['calendario_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['calendario_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['calendario_gestao_tema']).'</td>';
		elseif ($gestao_data['calendario_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['calendario_gestao_objetivo']).'</td>';
		elseif ($gestao_data['calendario_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['calendario_gestao_fator']).'</td>';
		elseif ($gestao_data['calendario_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['calendario_gestao_estrategia']).'</td>';
		elseif ($gestao_data['calendario_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['calendario_gestao_meta']).'</td>';
		elseif ($gestao_data['calendario_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['calendario_gestao_pratica']).'</td>';
		elseif ($gestao_data['calendario_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['calendario_gestao_acao']).'</td>';
		elseif ($gestao_data['calendario_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['calendario_gestao_canvas']).'</td>';
		elseif ($gestao_data['calendario_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['calendario_gestao_risco']).'</td>';
		elseif ($gestao_data['calendario_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['calendario_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['calendario_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['calendario_gestao_indicador']).'</td>';
		
		elseif ($gestao_data['calendario_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['calendario_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['calendario_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['calendario_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['calendario_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['calendario_gestao_ata']).'</td>';
		elseif ($gestao_data['calendario_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['calendario_gestao_mswot']).'</td>';
		elseif ($gestao_data['calendario_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['calendario_gestao_swot']).'</td>';
		elseif ($gestao_data['calendario_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['calendario_gestao_operativo']).'</td>';
		elseif ($gestao_data['calendario_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['calendario_gestao_instrumento']).'</td>';
		elseif ($gestao_data['calendario_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['calendario_gestao_recurso']).'</td>';
		elseif ($gestao_data['calendario_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['calendario_gestao_problema']).'</td>';
		elseif ($gestao_data['calendario_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['calendario_gestao_demanda']).'</td>';
		elseif ($gestao_data['calendario_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['calendario_gestao_programa']).'</td>';
		elseif ($gestao_data['calendario_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['calendario_gestao_licao']).'</td>';
		elseif ($gestao_data['calendario_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['calendario_gestao_evento']).'</td>';
		elseif ($gestao_data['calendario_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['calendario_gestao_link']).'</td>';
		elseif ($gestao_data['calendario_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['calendario_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['calendario_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['calendario_gestao_tgn']).'</td>';
		elseif ($gestao_data['calendario_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['calendario_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['calendario_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['calendario_gestao_gut']).'</td>';
		elseif ($gestao_data['calendario_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['calendario_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['calendario_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['calendario_gestao_arquivo']).'</td>';
		elseif ($gestao_data['calendario_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['calendario_gestao_forum']).'</td>';
		elseif ($gestao_data['calendario_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['calendario_gestao_checklist']).'</td>';
		elseif ($gestao_data['calendario_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['calendario_gestao_agenda']).'</td>';
		elseif ($gestao_data['calendario_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['calendario_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['calendario_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['calendario_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['calendario_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['calendario_gestao_template']).'</td>';
		elseif ($gestao_data['calendario_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['calendario_gestao_painel']).'</td>';
		elseif ($gestao_data['calendario_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['calendario_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['calendario_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['calendario_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['calendario_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['calendario_gestao_tr']).'</td>';	
		elseif ($gestao_data['calendario_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['calendario_gestao_me']).'</td>';	
		elseif ($gestao_data['calendario_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['calendario_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['calendario_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['calendario_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['calendario_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['calendario_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['calendario_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['calendario_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['calendario_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['calendario_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['calendario_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['calendario_gestao_plano_gestao']).'</td>';	
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['calendario_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		
		



$xajax->processRequest();

?>