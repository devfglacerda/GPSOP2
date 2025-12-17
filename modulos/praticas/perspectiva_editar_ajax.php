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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/perspectiva_editar_ajax_pro.php');


function mudar_posicao_gestao($ordem, $perspectiva_gestao_id, $direcao, $pg_perspectiva_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $perspectiva_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('perspectiva_gestao');
		$sql->adOnde('perspectiva_gestao_id != '.(int)$perspectiva_gestao_id);
		if ($uuid) $sql->adOnde('perspectiva_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('perspectiva_gestao_perspectiva = '.(int)$pg_perspectiva_id);
		$sql->adOrdem('perspectiva_gestao_ordem');
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
			$sql->adTabela('perspectiva_gestao');
			$sql->adAtualizar('perspectiva_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('perspectiva_gestao_id = '.(int)$perspectiva_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('perspectiva_gestao');
					$sql->adAtualizar('perspectiva_gestao_ordem', $idx);
					$sql->adOnde('perspectiva_gestao_id = '.(int)$acao['perspectiva_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('perspectiva_gestao');
					$sql->adAtualizar('perspectiva_gestao_ordem', $idx + 1);
					$sql->adOnde('perspectiva_gestao_id = '.(int)$acao['perspectiva_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($pg_perspectiva_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$pg_perspectiva_id=0, 
	$uuid='',  
	
	$perspectiva_projeto=null,
	$perspectiva_tarefa=null,
	$perspectiva_perspectiva=null,
	$perspectiva_tema=null,
	$perspectiva_objetivo=null,
	$perspectiva_fator=null,
	$perspectiva_estrategia=null,
	$perspectiva_meta=null,
	$perspectiva_pratica=null,
	$perspectiva_acao=null,
	$perspectiva_canvas=null,
	$perspectiva_risco=null,
	$perspectiva_risco_resposta=null,
	$perspectiva_indicador=null,
	$perspectiva_calendario=null,
	$perspectiva_monitoramento=null,
	$perspectiva_ata=null,
	$perspectiva_mswot=null,
	$perspectiva_swot=null,
	$perspectiva_operativo=null,
	$perspectiva_instrumento=null,
	$perspectiva_recurso=null,
	$perspectiva_problema=null,
	$perspectiva_demanda=null,
	$perspectiva_programa=null,
	$perspectiva_licao=null,
	$perspectiva_evento=null,
	$perspectiva_link=null,
	$perspectiva_avaliacao=null,
	$perspectiva_tgn=null,
	$perspectiva_brainstorm=null,
	$perspectiva_gut=null,
	$perspectiva_causa_efeito=null,
	$perspectiva_arquivo=null,
	$perspectiva_forum=null,
	$perspectiva_checklist=null,
	$perspectiva_agenda=null,
	$perspectiva_agrupamento=null,
	$perspectiva_patrocinador=null,
	$perspectiva_template=null,
	$perspectiva_painel=null,
	$perspectiva_painel_odometro=null,
	$perspectiva_painel_composicao=null,
	$perspectiva_tr=null,
	$perspectiva_me=null,
	$perspectiva_acao_item=null,
	$perspectiva_beneficio=null,
	$perspectiva_painel_slideshow=null,
	$perspectiva_projeto_viabilidade=null,
	$perspectiva_projeto_abertura=null,
	$perspectiva_plano_gestao=null,
	$perspectiva_ssti=null,
	$perspectiva_laudo=null,
	$perspectiva_trelo=null,
	$perspectiva_trelo_cartao=null,
	$perspectiva_pdcl=null,
	$perspectiva_pdcl_item=null
	)
	{
	if (
		$perspectiva_projeto || 
		$perspectiva_tarefa || 
		$perspectiva_perspectiva || 
		$perspectiva_tema || 
		$perspectiva_objetivo || 
		$perspectiva_fator || 
		$perspectiva_estrategia || 
		$perspectiva_meta || 
		$perspectiva_pratica || 
		$perspectiva_acao || 
		$perspectiva_canvas || 
		$perspectiva_risco || 
		$perspectiva_risco_resposta || 
		$perspectiva_indicador || 
		$perspectiva_calendario || 
		$perspectiva_monitoramento || 
		$perspectiva_ata || 
		$perspectiva_mswot || 
		$perspectiva_swot || 
		$perspectiva_operativo || 
		$perspectiva_instrumento || 
		$perspectiva_recurso || 
		$perspectiva_problema || 
		$perspectiva_demanda || 
		$perspectiva_programa || 
		$perspectiva_licao || 
		$perspectiva_evento || 
		$perspectiva_link || 
		$perspectiva_avaliacao || 
		$perspectiva_tgn || 
		$perspectiva_brainstorm || 
		$perspectiva_gut || 
		$perspectiva_causa_efeito || 
		$perspectiva_arquivo || 
		$perspectiva_forum || 
		$perspectiva_checklist || 
		$perspectiva_agenda || 
		$perspectiva_agrupamento || 
		$perspectiva_patrocinador || 
		$perspectiva_template || 
		$perspectiva_painel || 
		$perspectiva_painel_odometro || 
		$perspectiva_painel_composicao || 
		$perspectiva_tr || 
		$perspectiva_me || 
		$perspectiva_acao_item || 
		$perspectiva_beneficio || 
		$perspectiva_painel_slideshow || 
		$perspectiva_projeto_viabilidade || 
		$perspectiva_projeto_abertura || 
		$perspectiva_plano_gestao|| 
		$perspectiva_ssti || 
		$perspectiva_laudo || 
		$perspectiva_trelo || 
		$perspectiva_trelo_cartao || 
		$perspectiva_pdcl || 
		$perspectiva_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('perspectiva_gestao');
			if ($uuid) $sql->adOnde('perspectiva_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('perspectiva_gestao_perspectiva ='.(int)$pg_perspectiva_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('perspectiva_gestao');
		$sql->adCampo('count(perspectiva_gestao_id)');
		if ($uuid) $sql->adOnde('perspectiva_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('perspectiva_gestao_perspectiva ='.(int)$pg_perspectiva_id);	
		if ($perspectiva_tarefa) $sql->adOnde('perspectiva_gestao_tarefa='.(int)$perspectiva_tarefa);
		elseif ($perspectiva_projeto) $sql->adOnde('perspectiva_gestao_projeto='.(int)$perspectiva_projeto);
		
		elseif ($perspectiva_perspectiva) $sql->adOnde('perspectiva_gestao_semelhante='.(int)$perspectiva_perspectiva);
		
		elseif ($perspectiva_tema) $sql->adOnde('perspectiva_gestao_tema='.(int)$perspectiva_tema);
		elseif ($perspectiva_objetivo) $sql->adOnde('perspectiva_gestao_objetivo='.(int)$perspectiva_objetivo);
		elseif ($perspectiva_fator) $sql->adOnde('perspectiva_gestao_fator='.(int)$perspectiva_fator);
		elseif ($perspectiva_estrategia) $sql->adOnde('perspectiva_gestao_estrategia='.(int)$perspectiva_estrategia);
		elseif ($perspectiva_acao) $sql->adOnde('perspectiva_gestao_acao='.(int)$perspectiva_acao);
		elseif ($perspectiva_pratica) $sql->adOnde('perspectiva_gestao_pratica='.(int)$perspectiva_pratica);
		elseif ($perspectiva_meta) $sql->adOnde('perspectiva_gestao_meta='.(int)$perspectiva_meta);
		elseif ($perspectiva_canvas) $sql->adOnde('perspectiva_gestao_canvas='.(int)$perspectiva_canvas);
		elseif ($perspectiva_risco) $sql->adOnde('perspectiva_gestao_risco='.(int)$perspectiva_risco);
		elseif ($perspectiva_risco_resposta) $sql->adOnde('perspectiva_gestao_risco_resposta='.(int)$perspectiva_risco_resposta);
		elseif ($perspectiva_indicador) $sql->adOnde('perspectiva_gestao_indicador='.(int)$perspectiva_indicador);
		elseif ($perspectiva_calendario) $sql->adOnde('perspectiva_gestao_calendario='.(int)$perspectiva_calendario);
		elseif ($perspectiva_monitoramento) $sql->adOnde('perspectiva_gestao_monitoramento='.(int)$perspectiva_monitoramento);
		elseif ($perspectiva_ata) $sql->adOnde('perspectiva_gestao_ata='.(int)$perspectiva_ata);
		elseif ($perspectiva_mswot) $sql->adOnde('perspectiva_gestao_mswot='.(int)$perspectiva_mswot);
		elseif ($perspectiva_swot) $sql->adOnde('perspectiva_gestao_swot='.(int)$perspectiva_swot);
		elseif ($perspectiva_operativo) $sql->adOnde('perspectiva_gestao_operativo='.(int)$perspectiva_operativo);
		elseif ($perspectiva_instrumento) $sql->adOnde('perspectiva_gestao_instrumento='.(int)$perspectiva_instrumento);
		elseif ($perspectiva_recurso) $sql->adOnde('perspectiva_gestao_recurso='.(int)$perspectiva_recurso);
		elseif ($perspectiva_problema) $sql->adOnde('perspectiva_gestao_problema='.(int)$perspectiva_problema);
		elseif ($perspectiva_demanda) $sql->adOnde('perspectiva_gestao_demanda='.(int)$perspectiva_demanda);
		elseif ($perspectiva_programa) $sql->adOnde('perspectiva_gestao_programa='.(int)$perspectiva_programa);
		elseif ($perspectiva_licao) $sql->adOnde('perspectiva_gestao_licao='.(int)$perspectiva_licao);
		elseif ($perspectiva_evento) $sql->adOnde('perspectiva_gestao_evento='.(int)$perspectiva_evento);
		elseif ($perspectiva_link) $sql->adOnde('perspectiva_gestao_link='.(int)$perspectiva_link);
		elseif ($perspectiva_avaliacao) $sql->adOnde('perspectiva_gestao_avaliacao='.(int)$perspectiva_avaliacao);
		elseif ($perspectiva_tgn) $sql->adOnde('perspectiva_gestao_tgn='.(int)$perspectiva_tgn);
		elseif ($perspectiva_brainstorm) $sql->adOnde('perspectiva_gestao_brainstorm='.(int)$perspectiva_brainstorm);
		elseif ($perspectiva_gut) $sql->adOnde('perspectiva_gestao_gut='.(int)$perspectiva_gut);
		elseif ($perspectiva_causa_efeito) $sql->adOnde('perspectiva_gestao_causa_efeito='.(int)$perspectiva_causa_efeito);
		elseif ($perspectiva_arquivo) $sql->adOnde('perspectiva_gestao_arquivo='.(int)$perspectiva_arquivo);
		elseif ($perspectiva_forum) $sql->adOnde('perspectiva_gestao_forum='.(int)$perspectiva_forum);
		elseif ($perspectiva_checklist) $sql->adOnde('perspectiva_gestao_checklist='.(int)$perspectiva_checklist);
		elseif ($perspectiva_agenda) $sql->adOnde('perspectiva_gestao_agenda='.(int)$perspectiva_agenda);
		elseif ($perspectiva_agrupamento) $sql->adOnde('perspectiva_gestao_agrupamento='.(int)$perspectiva_agrupamento);
		elseif ($perspectiva_patrocinador) $sql->adOnde('perspectiva_gestao_patrocinador='.(int)$perspectiva_patrocinador);
		elseif ($perspectiva_template) $sql->adOnde('perspectiva_gestao_template='.(int)$perspectiva_template);
		elseif ($perspectiva_painel) $sql->adOnde('perspectiva_gestao_painel='.(int)$perspectiva_painel);
		elseif ($perspectiva_painel_odometro) $sql->adOnde('perspectiva_gestao_painel_odometro='.(int)$perspectiva_painel_odometro);
		elseif ($perspectiva_painel_composicao) $sql->adOnde('perspectiva_gestao_painel_composicao='.(int)$perspectiva_painel_composicao);
		elseif ($perspectiva_tr) $sql->adOnde('perspectiva_gestao_tr='.(int)$perspectiva_tr);
		elseif ($perspectiva_me) $sql->adOnde('perspectiva_gestao_me='.(int)$perspectiva_me);
		elseif ($perspectiva_acao_item) $sql->adOnde('perspectiva_gestao_acao_item='.(int)$perspectiva_acao_item);
		elseif ($perspectiva_beneficio) $sql->adOnde('perspectiva_gestao_beneficio='.(int)$perspectiva_beneficio);
		elseif ($perspectiva_painel_slideshow) $sql->adOnde('perspectiva_gestao_painel_slideshow='.(int)$perspectiva_painel_slideshow);
		elseif ($perspectiva_projeto_viabilidade) $sql->adOnde('perspectiva_gestao_projeto_viabilidade='.(int)$perspectiva_projeto_viabilidade);
		elseif ($perspectiva_projeto_abertura) $sql->adOnde('perspectiva_gestao_projeto_abertura='.(int)$perspectiva_projeto_abertura);
		elseif ($perspectiva_plano_gestao) $sql->adOnde('perspectiva_gestao_plano_gestao='.(int)$perspectiva_plano_gestao);
		elseif ($perspectiva_ssti) $sql->adOnde('perspectiva_gestao_ssti='.(int)$perspectiva_ssti);
		elseif ($perspectiva_laudo) $sql->adOnde('perspectiva_gestao_laudo='.(int)$perspectiva_laudo);
		elseif ($perspectiva_trelo) $sql->adOnde('perspectiva_gestao_trelo='.(int)$perspectiva_trelo);
		elseif ($perspectiva_trelo_cartao) $sql->adOnde('perspectiva_gestao_trelo_cartao='.(int)$perspectiva_trelo_cartao);
		elseif ($perspectiva_pdcl) $sql->adOnde('perspectiva_gestao_pdcl='.(int)$perspectiva_pdcl);
		elseif ($perspectiva_pdcl_item) $sql->adOnde('perspectiva_gestao_pdcl_item='.(int)$perspectiva_pdcl_item);
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('perspectiva_gestao');
			$sql->adCampo('MAX(perspectiva_gestao_ordem)');
			if ($uuid) $sql->adOnde('perspectiva_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('perspectiva_gestao_perspectiva ='.(int)$pg_perspectiva_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('perspectiva_gestao');
			if ($uuid) $sql->adInserir('perspectiva_gestao_uuid', $uuid);
			else $sql->adInserir('perspectiva_gestao_perspectiva', (int)$pg_perspectiva_id);
			
			if ($perspectiva_tarefa) $sql->adInserir('perspectiva_gestao_tarefa', (int)$perspectiva_tarefa);
			if ($perspectiva_projeto) $sql->adInserir('perspectiva_gestao_projeto', (int)$perspectiva_projeto);
			
			elseif ($perspectiva_perspectiva) $sql->adInserir('perspectiva_gestao_semelhante', (int)$perspectiva_perspectiva);
			
			elseif ($perspectiva_tema) $sql->adInserir('perspectiva_gestao_tema', (int)$perspectiva_tema);
			elseif ($perspectiva_objetivo) $sql->adInserir('perspectiva_gestao_objetivo', (int)$perspectiva_objetivo);
			elseif ($perspectiva_fator) $sql->adInserir('perspectiva_gestao_fator', (int)$perspectiva_fator);
			elseif ($perspectiva_estrategia) $sql->adInserir('perspectiva_gestao_estrategia', (int)$perspectiva_estrategia);
			elseif ($perspectiva_acao) $sql->adInserir('perspectiva_gestao_acao', (int)$perspectiva_acao);
			elseif ($perspectiva_pratica) $sql->adInserir('perspectiva_gestao_pratica', (int)$perspectiva_pratica);
			elseif ($perspectiva_meta) $sql->adInserir('perspectiva_gestao_meta', (int)$perspectiva_meta);
			elseif ($perspectiva_canvas) $sql->adInserir('perspectiva_gestao_canvas', (int)$perspectiva_canvas);
			elseif ($perspectiva_risco) $sql->adInserir('perspectiva_gestao_risco', (int)$perspectiva_risco);
			elseif ($perspectiva_risco_resposta) $sql->adInserir('perspectiva_gestao_risco_resposta', (int)$perspectiva_risco_resposta);
			elseif ($perspectiva_indicador) $sql->adInserir('perspectiva_gestao_indicador', (int)$perspectiva_indicador);
			elseif ($perspectiva_calendario) $sql->adInserir('perspectiva_gestao_calendario', (int)$perspectiva_calendario);
			elseif ($perspectiva_monitoramento) $sql->adInserir('perspectiva_gestao_monitoramento', (int)$perspectiva_monitoramento);
			elseif ($perspectiva_ata) $sql->adInserir('perspectiva_gestao_ata', (int)$perspectiva_ata);
			elseif ($perspectiva_mswot) $sql->adInserir('perspectiva_gestao_mswot', (int)$perspectiva_mswot);
			elseif ($perspectiva_swot) $sql->adInserir('perspectiva_gestao_swot', (int)$perspectiva_swot);
			elseif ($perspectiva_operativo) $sql->adInserir('perspectiva_gestao_operativo', (int)$perspectiva_operativo);
			elseif ($perspectiva_instrumento) $sql->adInserir('perspectiva_gestao_instrumento', (int)$perspectiva_instrumento);
			elseif ($perspectiva_recurso) $sql->adInserir('perspectiva_gestao_recurso', (int)$perspectiva_recurso);
			elseif ($perspectiva_problema) $sql->adInserir('perspectiva_gestao_problema', (int)$perspectiva_problema);
			elseif ($perspectiva_demanda) $sql->adInserir('perspectiva_gestao_demanda', (int)$perspectiva_demanda);
			elseif ($perspectiva_programa) $sql->adInserir('perspectiva_gestao_programa', (int)$perspectiva_programa);
			elseif ($perspectiva_licao) $sql->adInserir('perspectiva_gestao_licao', (int)$perspectiva_licao);
			elseif ($perspectiva_evento) $sql->adInserir('perspectiva_gestao_evento', (int)$perspectiva_evento);
			elseif ($perspectiva_link) $sql->adInserir('perspectiva_gestao_link', (int)$perspectiva_link);
			elseif ($perspectiva_avaliacao) $sql->adInserir('perspectiva_gestao_avaliacao', (int)$perspectiva_avaliacao);
			elseif ($perspectiva_tgn) $sql->adInserir('perspectiva_gestao_tgn', (int)$perspectiva_tgn);
			elseif ($perspectiva_brainstorm) $sql->adInserir('perspectiva_gestao_brainstorm', (int)$perspectiva_brainstorm);
			elseif ($perspectiva_gut) $sql->adInserir('perspectiva_gestao_gut', (int)$perspectiva_gut);
			elseif ($perspectiva_causa_efeito) $sql->adInserir('perspectiva_gestao_causa_efeito', (int)$perspectiva_causa_efeito);
			elseif ($perspectiva_arquivo) $sql->adInserir('perspectiva_gestao_arquivo', (int)$perspectiva_arquivo);
			elseif ($perspectiva_forum) $sql->adInserir('perspectiva_gestao_forum', (int)$perspectiva_forum);
			elseif ($perspectiva_checklist) $sql->adInserir('perspectiva_gestao_checklist', (int)$perspectiva_checklist);
			elseif ($perspectiva_agenda) $sql->adInserir('perspectiva_gestao_agenda', (int)$perspectiva_agenda);
			elseif ($perspectiva_agrupamento) $sql->adInserir('perspectiva_gestao_agrupamento', (int)$perspectiva_agrupamento);
			elseif ($perspectiva_patrocinador) $sql->adInserir('perspectiva_gestao_patrocinador', (int)$perspectiva_patrocinador);
			elseif ($perspectiva_template) $sql->adInserir('perspectiva_gestao_template', (int)$perspectiva_template);
			elseif ($perspectiva_painel) $sql->adInserir('perspectiva_gestao_painel', (int)$perspectiva_painel);
			elseif ($perspectiva_painel_odometro) $sql->adInserir('perspectiva_gestao_painel_odometro', (int)$perspectiva_painel_odometro);
			elseif ($perspectiva_painel_composicao) $sql->adInserir('perspectiva_gestao_painel_composicao', (int)$perspectiva_painel_composicao);
			elseif ($perspectiva_tr) $sql->adInserir('perspectiva_gestao_tr', (int)$perspectiva_tr);
			elseif ($perspectiva_me) $sql->adInserir('perspectiva_gestao_me', (int)$perspectiva_me);
			elseif ($perspectiva_acao_item) $sql->adInserir('perspectiva_gestao_acao_item', (int)$perspectiva_acao_item);
			elseif ($perspectiva_beneficio) $sql->adInserir('perspectiva_gestao_beneficio', (int)$perspectiva_beneficio);
			elseif ($perspectiva_painel_slideshow) $sql->adInserir('perspectiva_gestao_painel_slideshow', (int)$perspectiva_painel_slideshow);
			elseif ($perspectiva_projeto_viabilidade) $sql->adInserir('perspectiva_gestao_projeto_viabilidade', (int)$perspectiva_projeto_viabilidade);
			elseif ($perspectiva_projeto_abertura) $sql->adInserir('perspectiva_gestao_projeto_abertura', (int)$perspectiva_projeto_abertura);
			elseif ($perspectiva_plano_gestao) $sql->adInserir('perspectiva_gestao_plano_gestao', (int)$perspectiva_plano_gestao);
			elseif ($perspectiva_ssti) $sql->adInserir('perspectiva_gestao_ssti', (int)$perspectiva_ssti);
			elseif ($perspectiva_laudo) $sql->adInserir('perspectiva_gestao_laudo', (int)$perspectiva_laudo);
			elseif ($perspectiva_trelo) $sql->adInserir('perspectiva_gestao_trelo', (int)$perspectiva_trelo);
			elseif ($perspectiva_trelo_cartao) $sql->adInserir('perspectiva_gestao_trelo_cartao', (int)$perspectiva_trelo_cartao);
			elseif ($perspectiva_pdcl) $sql->adInserir('perspectiva_gestao_pdcl', (int)$perspectiva_pdcl);
			elseif ($perspectiva_pdcl_item) $sql->adInserir('perspectiva_gestao_pdcl_item', (int)$perspectiva_pdcl_item);
			
			$sql->adInserir('perspectiva_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($pg_perspectiva_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($pg_perspectiva_id=0, $uuid='', $perspectiva_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('perspectiva_gestao');
	$sql->adOnde('perspectiva_gestao_id='.(int)$perspectiva_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($pg_perspectiva_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($pg_perspectiva_id=0, $uuid=''){	
	$saida=atualizar_gestao($pg_perspectiva_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($pg_perspectiva_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('perspectiva_gestao');
	$sql->adCampo('perspectiva_gestao.*');
	if ($uuid) $sql->adOnde('perspectiva_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('perspectiva_gestao_perspectiva ='.(int)$pg_perspectiva_id);	
	$sql->adOrdem('perspectiva_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['perspectiva_gestao_ordem'].', '.$gestao_data['perspectiva_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['perspectiva_gestao_ordem'].', '.$gestao_data['perspectiva_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['perspectiva_gestao_ordem'].', '.$gestao_data['perspectiva_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['perspectiva_gestao_ordem'].', '.$gestao_data['perspectiva_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['perspectiva_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['perspectiva_gestao_tarefa']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['perspectiva_gestao_projeto']).'</td>';
		
		elseif ($gestao_data['perspectiva_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['perspectiva_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['perspectiva_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['perspectiva_gestao_tema']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['perspectiva_gestao_objetivo']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['perspectiva_gestao_fator']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['perspectiva_gestao_estrategia']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['perspectiva_gestao_meta']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['perspectiva_gestao_pratica']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['perspectiva_gestao_acao']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['perspectiva_gestao_canvas']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['perspectiva_gestao_risco']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['perspectiva_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['perspectiva_gestao_indicador']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['perspectiva_gestao_calendario']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['perspectiva_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['perspectiva_gestao_ata']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['perspectiva_gestao_mswot']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['perspectiva_gestao_swot']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['perspectiva_gestao_operativo']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['perspectiva_gestao_instrumento']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['perspectiva_gestao_recurso']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['perspectiva_gestao_problema']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['perspectiva_gestao_demanda']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['perspectiva_gestao_programa']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['perspectiva_gestao_licao']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['perspectiva_gestao_evento']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['perspectiva_gestao_link']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['perspectiva_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['perspectiva_gestao_tgn']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['perspectiva_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['perspectiva_gestao_gut']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['perspectiva_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['perspectiva_gestao_arquivo']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['perspectiva_gestao_forum']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['perspectiva_gestao_checklist']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['perspectiva_gestao_agenda']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['perspectiva_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['perspectiva_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['perspectiva_gestao_template']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['perspectiva_gestao_painel']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['perspectiva_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['perspectiva_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['perspectiva_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['perspectiva_gestao_tr']).'</td>';	
		elseif ($gestao_data['perspectiva_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['perspectiva_gestao_me']).'</td>';	
		elseif ($gestao_data['perspectiva_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['perspectiva_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['perspectiva_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['perspectiva_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['perspectiva_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['perspectiva_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['perspectiva_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['perspectiva_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['perspectiva_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['perspectiva_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['perspectiva_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['perspectiva_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['perspectiva_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['perspectiva_gestao_ssti']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['perspectiva_gestao_laudo']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['perspectiva_gestao_trelo']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['perspectiva_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['perspectiva_gestao_pdcl']).'</td>';
		elseif ($gestao_data['perspectiva_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['perspectiva_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['perspectiva_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			



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
$xajax->processRequest();

?>