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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/brainstorm_editar_ajax_pro.php';	

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");

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





function mudar_posicao_gestao($ordem, $brainstorm_gestao_id, $direcao, $brainstorm_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $brainstorm_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('brainstorm_gestao');
		$sql->adOnde('brainstorm_gestao_id != '.(int)$brainstorm_gestao_id);
		if ($uuid) $sql->adOnde('brainstorm_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('brainstorm_gestao_brainstorm = '.(int)$brainstorm_id);
		$sql->adOrdem('brainstorm_gestao_ordem');
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
			$sql->adTabela('brainstorm_gestao');
			$sql->adAtualizar('brainstorm_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('brainstorm_gestao_id = '.(int)$brainstorm_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('brainstorm_gestao');
					$sql->adAtualizar('brainstorm_gestao_ordem', $idx);
					$sql->adOnde('brainstorm_gestao_id = '.(int)$acao['brainstorm_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('brainstorm_gestao');
					$sql->adAtualizar('brainstorm_gestao_ordem', $idx + 1);
					$sql->adOnde('brainstorm_gestao_id = '.(int)$acao['brainstorm_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($brainstorm_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$brainstorm_id=0, 
	$uuid='',  
	
	$brainstormjeto=null,
	$brainstorm_tarefa=null,
	$brainstorm_perspectiva=null,
	$brainstorm_tema=null,
	$brainstorm_objetivo=null,
	$brainstorm_fator=null,
	$brainstorm_estrategia=null,
	$brainstorm_meta=null,
	$brainstorm_pratica=null,
	$brainstorm_acao=null,
	$brainstorm_canvas=null,
	$brainstorm_risco=null,
	$brainstorm_risco_resposta=null,
	$brainstorm_indicador=null,
	$brainstorm_calendario=null,
	$brainstorm_monitoramento=null,
	$brainstorm_ata=null,
	$brainstorm_mswot=null,
	$brainstorm_swot=null,
	$brainstorm_operativo=null,
	$brainstorm_instrumento=null,
	$brainstorm_recurso=null,
	$brainstormblema=null,
	$brainstorm_demanda=null,
	$brainstormgrama=null,
	$brainstorm_licao=null,
	$brainstorm_evento=null,
	$brainstorm_link=null,
	$brainstorm_avaliacao=null,
	$brainstorm_tgn=null,
	$brainstorm_brainstorm=null,
	$brainstorm_gut=null,
	$brainstorm_causa_efeito=null,
	$brainstorm_arquivo=null,
	$brainstorm_forum=null,
	$brainstorm_checklist=null,
	$brainstorm_agenda=null,
	$brainstorm_agrupamento=null,
	$brainstorm_patrocinador=null,
	$brainstorm_template=null,
	$brainstorm_painel=null,
	$brainstorm_painel_odometro=null,
	$brainstorm_painel_composicao=null,
	$brainstorm_tr=null,
	$brainstorm_me=null,
	$brainstorm_acao_item=null,
	$brainstorm_beneficio=null,
	$brainstorm_painel_slideshow=null,
	$brainstormjeto_viabilidade=null,
	$brainstormjeto_abertura=null,
	$brainstorm_plano_gestao=null,
	$brainstorm_ssti=null,
	$brainstorm_laudo=null,
	$brainstorm_trelo=null,
	$brainstorm_trelo_cartao=null,
	$brainstorm_pdcl=null,
	$brainstorm_pdcl_item=null
	)
	{
	if (
		$brainstormjeto || 
		$brainstorm_tarefa || 
		$brainstorm_perspectiva || 
		$brainstorm_tema || 
		$brainstorm_objetivo || 
		$brainstorm_fator || 
		$brainstorm_estrategia || 
		$brainstorm_meta || 
		$brainstorm_pratica || 
		$brainstorm_acao || 
		$brainstorm_canvas || 
		$brainstorm_risco || 
		$brainstorm_risco_resposta || 
		$brainstorm_indicador || 
		$brainstorm_calendario || 
		$brainstorm_monitoramento || 
		$brainstorm_ata || 
		$brainstorm_mswot || 
		$brainstorm_swot || 
		$brainstorm_operativo || 
		$brainstorm_instrumento || 
		$brainstorm_recurso || 
		$brainstormblema || 
		$brainstorm_demanda || 
		$brainstormgrama || 
		$brainstorm_licao || 
		$brainstorm_evento || 
		$brainstorm_link || 
		$brainstorm_avaliacao || 
		$brainstorm_tgn || 
		$brainstorm_brainstorm || 
		$brainstorm_gut || 
		$brainstorm_causa_efeito || 
		$brainstorm_arquivo || 
		$brainstorm_forum || 
		$brainstorm_checklist || 
		$brainstorm_agenda || 
		$brainstorm_agrupamento || 
		$brainstorm_patrocinador || 
		$brainstorm_template || 
		$brainstorm_painel || 
		$brainstorm_painel_odometro || 
		$brainstorm_painel_composicao || 
		$brainstorm_tr || 
		$brainstorm_me || 
		$brainstorm_acao_item || 
		$brainstorm_beneficio || 
		$brainstorm_painel_slideshow || 
		$brainstormjeto_viabilidade || 
		$brainstormjeto_abertura || 
		$brainstorm_plano_gestao|| 
		$brainstorm_ssti || 
		$brainstorm_laudo || 
		$brainstorm_trelo || 
		$brainstorm_trelo_cartao || 
		$brainstorm_pdcl || 
		$brainstorm_pdcl_item
		){
		global $Aplic;
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('brainstorm_gestao');
			if ($uuid) $sql->adOnde('brainstorm_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('brainstorm_gestao_brainstorm ='.(int)$brainstorm_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('brainstorm_gestao');
		$sql->adCampo('count(brainstorm_gestao_id)');
		if ($uuid) $sql->adOnde('brainstorm_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('brainstorm_gestao_brainstorm ='.(int)$brainstorm_id);	
		if ($brainstorm_tarefa) $sql->adOnde('brainstorm_gestao_tarefa='.(int)$brainstorm_tarefa);
		elseif ($brainstormjeto) $sql->adOnde('brainstorm_gestao_projeto='.(int)$brainstormjeto);
		elseif ($brainstorm_perspectiva) $sql->adOnde('brainstorm_gestao_perspectiva='.(int)$brainstorm_perspectiva);
		elseif ($brainstorm_tema) $sql->adOnde('brainstorm_gestao_tema='.(int)$brainstorm_tema);
		elseif ($brainstorm_objetivo) $sql->adOnde('brainstorm_gestao_objetivo='.(int)$brainstorm_objetivo);
		elseif ($brainstorm_fator) $sql->adOnde('brainstorm_gestao_fator='.(int)$brainstorm_fator);
		elseif ($brainstorm_estrategia) $sql->adOnde('brainstorm_gestao_estrategia='.(int)$brainstorm_estrategia);
		elseif ($brainstorm_acao) $sql->adOnde('brainstorm_gestao_acao='.(int)$brainstorm_acao);
		elseif ($brainstorm_pratica) $sql->adOnde('brainstorm_gestao_pratica='.(int)$brainstorm_pratica);
		elseif ($brainstorm_meta) $sql->adOnde('brainstorm_gestao_meta='.(int)$brainstorm_meta);
		elseif ($brainstorm_canvas) $sql->adOnde('brainstorm_gestao_canvas='.(int)$brainstorm_canvas);
		elseif ($brainstorm_risco) $sql->adOnde('brainstorm_gestao_risco='.(int)$brainstorm_risco);
		elseif ($brainstorm_risco_resposta) $sql->adOnde('brainstorm_gestao_risco_resposta='.(int)$brainstorm_risco_resposta);
		elseif ($brainstorm_indicador) $sql->adOnde('brainstorm_gestao_indicador='.(int)$brainstorm_indicador);
		elseif ($brainstorm_calendario) $sql->adOnde('brainstorm_gestao_calendario='.(int)$brainstorm_calendario);
		elseif ($brainstorm_monitoramento) $sql->adOnde('brainstorm_gestao_monitoramento='.(int)$brainstorm_monitoramento);
		elseif ($brainstorm_ata) $sql->adOnde('brainstorm_gestao_ata='.(int)$brainstorm_ata);
		elseif ($brainstorm_mswot) $sql->adOnde('brainstorm_gestao_mswot='.(int)$brainstorm_mswot);
		elseif ($brainstorm_swot) $sql->adOnde('brainstorm_gestao_swot='.(int)$brainstorm_swot);
		elseif ($brainstorm_operativo) $sql->adOnde('brainstorm_gestao_operativo='.(int)$brainstorm_operativo);
		elseif ($brainstorm_instrumento) $sql->adOnde('brainstorm_gestao_instrumento='.(int)$brainstorm_instrumento);
		elseif ($brainstorm_recurso) $sql->adOnde('brainstorm_gestao_recurso='.(int)$brainstorm_recurso);
		elseif ($brainstormblema) $sql->adOnde('brainstorm_gestao_problema='.(int)$brainstormblema);
		elseif ($brainstorm_demanda) $sql->adOnde('brainstorm_gestao_demanda='.(int)$brainstorm_demanda);
		elseif ($brainstormgrama) $sql->adOnde('brainstorm_gestao_programa='.(int)$brainstormgrama);
		elseif ($brainstorm_licao) $sql->adOnde('brainstorm_gestao_licao='.(int)$brainstorm_licao);
		elseif ($brainstorm_evento) $sql->adOnde('brainstorm_gestao_evento='.(int)$brainstorm_evento);
		elseif ($brainstorm_link) $sql->adOnde('brainstorm_gestao_link='.(int)$brainstorm_link);
		elseif ($brainstorm_avaliacao) $sql->adOnde('brainstorm_gestao_avaliacao='.(int)$brainstorm_avaliacao);
		elseif ($brainstorm_tgn) $sql->adOnde('brainstorm_gestao_tgn='.(int)$brainstorm_tgn);
		
		elseif ($brainstorm_brainstorm) $sql->adOnde('brainstorm_gestao_semelhante='.(int)$brainstorm_brainstorm);
		
		elseif ($brainstorm_gut) $sql->adOnde('brainstorm_gestao_gut='.(int)$brainstorm_gut);
		elseif ($brainstorm_causa_efeito) $sql->adOnde('brainstorm_gestao_causa_efeito='.(int)$brainstorm_causa_efeito);
		elseif ($brainstorm_arquivo) $sql->adOnde('brainstorm_gestao_arquivo='.(int)$brainstorm_arquivo);
		elseif ($brainstorm_forum) $sql->adOnde('brainstorm_gestao_forum='.(int)$brainstorm_forum);
		elseif ($brainstorm_checklist) $sql->adOnde('brainstorm_gestao_checklist='.(int)$brainstorm_checklist);
		elseif ($brainstorm_agenda) $sql->adOnde('brainstorm_gestao_agenda='.(int)$brainstorm_agenda);
		elseif ($brainstorm_agrupamento) $sql->adOnde('brainstorm_gestao_agrupamento='.(int)$brainstorm_agrupamento);
		elseif ($brainstorm_patrocinador) $sql->adOnde('brainstorm_gestao_patrocinador='.(int)$brainstorm_patrocinador);
		elseif ($brainstorm_template) $sql->adOnde('brainstorm_gestao_template='.(int)$brainstorm_template);
		elseif ($brainstorm_painel) $sql->adOnde('brainstorm_gestao_painel='.(int)$brainstorm_painel);
		elseif ($brainstorm_painel_odometro) $sql->adOnde('brainstorm_gestao_painel_odometro='.(int)$brainstorm_painel_odometro);
		elseif ($brainstorm_painel_composicao) $sql->adOnde('brainstorm_gestao_painel_composicao='.(int)$brainstorm_painel_composicao);
		elseif ($brainstorm_tr) $sql->adOnde('brainstorm_gestao_tr='.(int)$brainstorm_tr);
		elseif ($brainstorm_me) $sql->adOnde('brainstorm_gestao_me='.(int)$brainstorm_me);
		elseif ($brainstorm_acao_item) $sql->adOnde('brainstorm_gestao_acao_item='.(int)$brainstorm_acao_item);
		elseif ($brainstorm_beneficio) $sql->adOnde('brainstorm_gestao_beneficio='.(int)$brainstorm_beneficio);
		elseif ($brainstorm_painel_slideshow) $sql->adOnde('brainstorm_gestao_painel_slideshow='.(int)$brainstorm_painel_slideshow);
		elseif ($brainstormjeto_viabilidade) $sql->adOnde('brainstorm_gestao_projeto_viabilidade='.(int)$brainstormjeto_viabilidade);
		elseif ($brainstormjeto_abertura) $sql->adOnde('brainstorm_gestao_projeto_abertura='.(int)$brainstormjeto_abertura);
		elseif ($brainstorm_plano_gestao) $sql->adOnde('brainstorm_gestao_plano_gestao='.(int)$brainstorm_plano_gestao);
		elseif ($brainstorm_ssti) $sql->adOnde('brainstorm_gestao_ssti='.(int)$brainstorm_ssti);
		elseif ($brainstorm_laudo) $sql->adOnde('brainstorm_gestao_laudo='.(int)$brainstorm_laudo);
		elseif ($brainstorm_trelo) $sql->adOnde('brainstorm_gestao_trelo='.(int)$brainstorm_trelo);
		elseif ($brainstorm_trelo_cartao) $sql->adOnde('brainstorm_gestao_trelo_cartao='.(int)$brainstorm_trelo_cartao);
		elseif ($brainstorm_pdcl) $sql->adOnde('brainstorm_gestao_pdcl='.(int)$brainstorm_pdcl);
		elseif ($brainstorm_pdcl_item) $sql->adOnde('brainstorm_gestao_pdcl_item='.(int)$brainstorm_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('brainstorm_gestao');
			$sql->adCampo('MAX(brainstorm_gestao_ordem)');
			if ($uuid) $sql->adOnde('brainstorm_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('brainstorm_gestao_brainstorm ='.(int)$brainstorm_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('brainstorm_gestao');
			if ($uuid) $sql->adInserir('brainstorm_gestao_uuid', $uuid);
			else $sql->adInserir('brainstorm_gestao_brainstorm', (int)$brainstorm_id);
			
			if ($brainstorm_tarefa) $sql->adInserir('brainstorm_gestao_tarefa', (int)$brainstorm_tarefa);
			if ($brainstormjeto) $sql->adInserir('brainstorm_gestao_projeto', (int)$brainstormjeto);
			elseif ($brainstorm_perspectiva) $sql->adInserir('brainstorm_gestao_perspectiva', (int)$brainstorm_perspectiva);
			elseif ($brainstorm_tema) $sql->adInserir('brainstorm_gestao_tema', (int)$brainstorm_tema);
			elseif ($brainstorm_objetivo) $sql->adInserir('brainstorm_gestao_objetivo', (int)$brainstorm_objetivo);
			elseif ($brainstorm_fator) $sql->adInserir('brainstorm_gestao_fator', (int)$brainstorm_fator);
			elseif ($brainstorm_estrategia) $sql->adInserir('brainstorm_gestao_estrategia', (int)$brainstorm_estrategia);
			elseif ($brainstorm_acao) $sql->adInserir('brainstorm_gestao_acao', (int)$brainstorm_acao);
			elseif ($brainstorm_pratica) $sql->adInserir('brainstorm_gestao_pratica', (int)$brainstorm_pratica);
			elseif ($brainstorm_meta) $sql->adInserir('brainstorm_gestao_meta', (int)$brainstorm_meta);
			elseif ($brainstorm_canvas) $sql->adInserir('brainstorm_gestao_canvas', (int)$brainstorm_canvas);
			elseif ($brainstorm_risco) $sql->adInserir('brainstorm_gestao_risco', (int)$brainstorm_risco);
			elseif ($brainstorm_risco_resposta) $sql->adInserir('brainstorm_gestao_risco_resposta', (int)$brainstorm_risco_resposta);
			elseif ($brainstorm_indicador) $sql->adInserir('brainstorm_gestao_indicador', (int)$brainstorm_indicador);
			elseif ($brainstorm_calendario) $sql->adInserir('brainstorm_gestao_calendario', (int)$brainstorm_calendario);
			elseif ($brainstorm_monitoramento) $sql->adInserir('brainstorm_gestao_monitoramento', (int)$brainstorm_monitoramento);
			elseif ($brainstorm_ata) $sql->adInserir('brainstorm_gestao_ata', (int)$brainstorm_ata);
			elseif ($brainstorm_mswot) $sql->adInserir('brainstorm_gestao_mswot', (int)$brainstorm_mswot);
			elseif ($brainstorm_swot) $sql->adInserir('brainstorm_gestao_swot', (int)$brainstorm_swot);
			elseif ($brainstorm_operativo) $sql->adInserir('brainstorm_gestao_operativo', (int)$brainstorm_operativo);
			elseif ($brainstorm_instrumento) $sql->adInserir('brainstorm_gestao_instrumento', (int)$brainstorm_instrumento);
			elseif ($brainstorm_recurso) $sql->adInserir('brainstorm_gestao_recurso', (int)$brainstorm_recurso);
			elseif ($brainstormblema) $sql->adInserir('brainstorm_gestao_problema', (int)$brainstormblema);
			elseif ($brainstorm_demanda) $sql->adInserir('brainstorm_gestao_demanda', (int)$brainstorm_demanda);
			elseif ($brainstormgrama) $sql->adInserir('brainstorm_gestao_programa', (int)$brainstormgrama);
			elseif ($brainstorm_licao) $sql->adInserir('brainstorm_gestao_licao', (int)$brainstorm_licao);
			elseif ($brainstorm_evento) $sql->adInserir('brainstorm_gestao_evento', (int)$brainstorm_evento);
			elseif ($brainstorm_link) $sql->adInserir('brainstorm_gestao_link', (int)$brainstorm_link);
			elseif ($brainstorm_avaliacao) $sql->adInserir('brainstorm_gestao_avaliacao', (int)$brainstorm_avaliacao);
			elseif ($brainstorm_tgn) $sql->adInserir('brainstorm_gestao_tgn', (int)$brainstorm_tgn);
			
			elseif ($brainstorm_brainstorm) $sql->adInserir('brainstorm_gestao_semelhante', (int)$brainstorm_brainstorm);
			
			elseif ($brainstorm_gut) $sql->adInserir('brainstorm_gestao_gut', (int)$brainstorm_gut);
			elseif ($brainstorm_causa_efeito) $sql->adInserir('brainstorm_gestao_causa_efeito', (int)$brainstorm_causa_efeito);
			elseif ($brainstorm_arquivo) $sql->adInserir('brainstorm_gestao_arquivo', (int)$brainstorm_arquivo);
			elseif ($brainstorm_forum) $sql->adInserir('brainstorm_gestao_forum', (int)$brainstorm_forum);
			elseif ($brainstorm_checklist) $sql->adInserir('brainstorm_gestao_checklist', (int)$brainstorm_checklist);
			elseif ($brainstorm_agenda) $sql->adInserir('brainstorm_gestao_agenda', (int)$brainstorm_agenda);
			elseif ($brainstorm_agrupamento) $sql->adInserir('brainstorm_gestao_agrupamento', (int)$brainstorm_agrupamento);
			elseif ($brainstorm_patrocinador) $sql->adInserir('brainstorm_gestao_patrocinador', (int)$brainstorm_patrocinador);
			elseif ($brainstorm_template) $sql->adInserir('brainstorm_gestao_template', (int)$brainstorm_template);
			elseif ($brainstorm_painel) $sql->adInserir('brainstorm_gestao_painel', (int)$brainstorm_painel);
			elseif ($brainstorm_painel_odometro) $sql->adInserir('brainstorm_gestao_painel_odometro', (int)$brainstorm_painel_odometro);
			elseif ($brainstorm_painel_composicao) $sql->adInserir('brainstorm_gestao_painel_composicao', (int)$brainstorm_painel_composicao);
			elseif ($brainstorm_tr) $sql->adInserir('brainstorm_gestao_tr', (int)$brainstorm_tr);
			elseif ($brainstorm_me) $sql->adInserir('brainstorm_gestao_me', (int)$brainstorm_me);
			elseif ($brainstorm_acao_item) $sql->adInserir('brainstorm_gestao_acao_item', (int)$brainstorm_acao_item);
			elseif ($brainstorm_beneficio) $sql->adInserir('brainstorm_gestao_beneficio', (int)$brainstorm_beneficio);
			elseif ($brainstorm_painel_slideshow) $sql->adInserir('brainstorm_gestao_painel_slideshow', (int)$brainstorm_painel_slideshow);
			elseif ($brainstormjeto_viabilidade) $sql->adInserir('brainstorm_gestao_projeto_viabilidade', (int)$brainstormjeto_viabilidade);
			elseif ($brainstormjeto_abertura) $sql->adInserir('brainstorm_gestao_projeto_abertura', (int)$brainstormjeto_abertura);
			elseif ($brainstorm_plano_gestao) $sql->adInserir('brainstorm_gestao_plano_gestao', (int)$brainstorm_plano_gestao);
			elseif ($brainstorm_ssti) $sql->adInserir('brainstorm_gestao_ssti', (int)$brainstorm_ssti);
			elseif ($brainstorm_laudo) $sql->adInserir('brainstorm_gestao_laudo', (int)$brainstorm_laudo);
			elseif ($brainstorm_trelo) $sql->adInserir('brainstorm_gestao_trelo', (int)$brainstorm_trelo);
			elseif ($brainstorm_trelo_cartao) $sql->adInserir('brainstorm_gestao_trelo_cartao', (int)$brainstorm_trelo_cartao);
			elseif ($brainstorm_pdcl) $sql->adInserir('brainstorm_gestao_pdcl', (int)$brainstorm_pdcl);
			elseif ($brainstorm_pdcl_item) $sql->adInserir('brainstorm_gestao_pdcl_item', (int)$brainstorm_pdcl_item);
			
			$sql->adInserir('brainstorm_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($brainstorm_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($brainstorm_id=0, $uuid='', $brainstorm_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('brainstorm_gestao');
	$sql->adOnde('brainstorm_gestao_id='.(int)$brainstorm_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($brainstorm_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($brainstorm_id=0, $uuid=''){	
	$saida=atualizar_gestao($brainstorm_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($brainstorm_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('brainstorm_gestao');
	$sql->adCampo('brainstorm_gestao.*');
	if ($uuid) $sql->adOnde('brainstorm_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('brainstorm_gestao_brainstorm ='.(int)$brainstorm_id);	
	$sql->adOrdem('brainstorm_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['brainstorm_gestao_ordem'].', '.$gestao_data['brainstorm_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['brainstorm_gestao_ordem'].', '.$gestao_data['brainstorm_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['brainstorm_gestao_ordem'].', '.$gestao_data['brainstorm_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['brainstorm_gestao_ordem'].', '.$gestao_data['brainstorm_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['brainstorm_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['brainstorm_gestao_tarefa']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['brainstorm_gestao_projeto']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['brainstorm_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['brainstorm_gestao_tema']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['brainstorm_gestao_objetivo']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['brainstorm_gestao_fator']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['brainstorm_gestao_estrategia']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['brainstorm_gestao_meta']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['brainstorm_gestao_pratica']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['brainstorm_gestao_acao']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['brainstorm_gestao_canvas']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['brainstorm_gestao_risco']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['brainstorm_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['brainstorm_gestao_indicador']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['brainstorm_gestao_calendario']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['brainstorm_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['brainstorm_gestao_ata']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['brainstorm_gestao_mswot']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['brainstorm_gestao_swot']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['brainstorm_gestao_operativo']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['brainstorm_gestao_instrumento']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['brainstorm_gestao_recurso']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['brainstorm_gestao_problema']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['brainstorm_gestao_demanda']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['brainstorm_gestao_programa']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['brainstorm_gestao_licao']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['brainstorm_gestao_evento']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['brainstorm_gestao_link']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['brainstorm_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['brainstorm_gestao_tgn']).'</td>';
		
		elseif ($gestao_data['brainstorm_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['brainstorm_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['brainstorm_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['brainstorm_gestao_gut']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['brainstorm_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['brainstorm_gestao_arquivo']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['brainstorm_gestao_forum']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['brainstorm_gestao_checklist']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['brainstorm_gestao_agenda']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['brainstorm_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['brainstorm_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['brainstorm_gestao_template']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['brainstorm_gestao_painel']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['brainstorm_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['brainstorm_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['brainstorm_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['brainstorm_gestao_tr']).'</td>';	
		elseif ($gestao_data['brainstorm_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['brainstorm_gestao_me']).'</td>';	
		elseif ($gestao_data['brainstorm_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['brainstorm_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['brainstorm_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['brainstorm_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['brainstorm_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['brainstorm_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['brainstorm_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['brainstorm_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['brainstorm_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['brainstorm_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['brainstorm_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['brainstorm_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['brainstorm_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['brainstorm_gestao_ssti']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['brainstorm_gestao_laudo']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['brainstorm_gestao_trelo']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['brainstorm_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['brainstorm_gestao_pdcl']).'</td>';
		elseif ($gestao_data['brainstorm_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['brainstorm_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['brainstorm_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		






$xajax->processRequest();
?>	