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

function mudar_posicao_gestao($ordem, $avaliacao_gestao_id, $direcao, $avaliacao_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $avaliacao_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('avaliacao_gestao');
		$sql->adOnde('avaliacao_gestao_id != '.(int)$avaliacao_gestao_id);
		if ($uuid) $sql->adOnde('avaliacao_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('avaliacao_gestao_avaliacao = '.(int)$avaliacao_id);
		$sql->adOrdem('avaliacao_gestao_ordem');
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
			$sql->adTabela('avaliacao_gestao');
			$sql->adAtualizar('avaliacao_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('avaliacao_gestao_id = '.(int)$avaliacao_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('avaliacao_gestao');
					$sql->adAtualizar('avaliacao_gestao_ordem', $idx);
					$sql->adOnde('avaliacao_gestao_id = '.(int)$acao['avaliacao_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('avaliacao_gestao');
					$sql->adAtualizar('avaliacao_gestao_ordem', $idx + 1);
					$sql->adOnde('avaliacao_gestao_id = '.(int)$acao['avaliacao_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($avaliacao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$avaliacao_id=0, 
	$uuid='',  
	
	$avaliacao_projeto=null,
	$avaliacao_tarefa=null,
	$avaliacao_perspectiva=null,
	$avaliacao_tema=null,
	$avaliacao_objetivo=null,
	$avaliacao_fator=null,
	$avaliacao_estrategia=null,
	$avaliacao_meta=null,
	$avaliacao_pratica=null,
	$avaliacao_acao=null,
	$avaliacao_canvas=null,
	$avaliacao_risco=null,
	$avaliacao_risco_resposta=null,
	$avaliacao_indicador=null,
	$avaliacao_calendario=null,
	$avaliacao_monitoramento=null,
	$avaliacao_ata=null,
	$avaliacao_mswot=null,
	$avaliacao_swot=null,
	$avaliacao_operativo=null,
	$avaliacao_instrumento=null,
	$avaliacao_recurso=null,
	$avaliacao_problema=null,
	$avaliacao_demanda=null,
	$avaliacao_programa=null,
	$avaliacao_licao=null,
	$avaliacao_evento=null,
	$avaliacao_link=null,
	$avaliacao_avaliacao=null,
	$avaliacao_tgn=null,
	$avaliacao_brainstorm=null,
	$avaliacao_gut=null,
	$avaliacao_causa_efeito=null,
	$avaliacao_arquivo=null,
	$avaliacao_forum=null,
	$avaliacao_checklist=null,
	$avaliacao_agenda=null,
	$avaliacao_agrupamento=null,
	$avaliacao_patrocinador=null,
	$avaliacao_template=null,
	$avaliacao_painel=null,
	$avaliacao_painel_odometro=null,
	$avaliacao_painel_composicao=null,
	$avaliacao_tr=null,
	$avaliacao_me=null,
	$avaliacao_acao_item=null,
	$avaliacao_beneficio=null,
	$avaliacao_painel_slideshow=null,
	$avaliacao_projeto_viabilidade=null,
	$avaliacao_projeto_abertura=null,
	$avaliacao_plano_gestao=null,
	$avaliacao_ssti=null,
	$avaliacao_laudo=null,
	$avaliacao_trelo=null,
	$avaliacao_trelo_cartao=null,
	$avaliacao_pdcl=null,
	$avaliacao_pdcl_item=null
	)
	{
	if (
		$avaliacao_projeto || 
		$avaliacao_tarefa || 
		$avaliacao_perspectiva || 
		$avaliacao_tema || 
		$avaliacao_objetivo || 
		$avaliacao_fator || 
		$avaliacao_estrategia || 
		$avaliacao_meta || 
		$avaliacao_pratica || 
		$avaliacao_acao || 
		$avaliacao_canvas || 
		$avaliacao_risco || 
		$avaliacao_risco_resposta || 
		$avaliacao_indicador || 
		$avaliacao_calendario || 
		$avaliacao_monitoramento || 
		$avaliacao_ata || 
		$avaliacao_mswot || 
		$avaliacao_swot || 
		$avaliacao_operativo || 
		$avaliacao_instrumento || 
		$avaliacao_recurso || 
		$avaliacao_problema || 
		$avaliacao_demanda || 
		$avaliacao_programa || 
		$avaliacao_licao || 
		$avaliacao_evento || 
		$avaliacao_link || 
		$avaliacao_avaliacao || 
		$avaliacao_tgn || 
		$avaliacao_brainstorm || 
		$avaliacao_gut || 
		$avaliacao_causa_efeito || 
		$avaliacao_arquivo || 
		$avaliacao_forum || 
		$avaliacao_checklist || 
		$avaliacao_agenda || 
		$avaliacao_agrupamento || 
		$avaliacao_patrocinador || 
		$avaliacao_template || 
		$avaliacao_painel || 
		$avaliacao_painel_odometro || 
		$avaliacao_painel_composicao || 
		$avaliacao_tr || 
		$avaliacao_me || 
		$avaliacao_acao_item || 
		$avaliacao_beneficio || 
		$avaliacao_painel_slideshow || 
		$avaliacao_projeto_viabilidade || 
		$avaliacao_projeto_abertura || 
		$avaliacao_plano_gestao|| 
		$avaliacao_ssti || 
		$avaliacao_laudo || 
		$avaliacao_trelo || 
		$avaliacao_trelo_cartao || 
		$avaliacao_pdcl || 
		$avaliacao_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('avaliacao_gestao');
			if ($uuid) $sql->adOnde('avaliacao_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('avaliacao_gestao_avaliacao ='.(int)$avaliacao_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('avaliacao_gestao');
		$sql->adCampo('count(avaliacao_gestao_id)');
		if ($uuid) $sql->adOnde('avaliacao_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('avaliacao_gestao_avaliacao ='.(int)$avaliacao_id);	
		if ($avaliacao_tarefa) $sql->adOnde('avaliacao_gestao_tarefa='.(int)$avaliacao_tarefa);
		elseif ($avaliacao_projeto) $sql->adOnde('avaliacao_gestao_projeto='.(int)$avaliacao_projeto);
		elseif ($avaliacao_perspectiva) $sql->adOnde('avaliacao_gestao_perspectiva='.(int)$avaliacao_perspectiva);
		elseif ($avaliacao_tema) $sql->adOnde('avaliacao_gestao_tema='.(int)$avaliacao_tema);
		elseif ($avaliacao_objetivo) $sql->adOnde('avaliacao_gestao_objetivo='.(int)$avaliacao_objetivo);
		elseif ($avaliacao_fator) $sql->adOnde('avaliacao_gestao_fator='.(int)$avaliacao_fator);
		elseif ($avaliacao_estrategia) $sql->adOnde('avaliacao_gestao_estrategia='.(int)$avaliacao_estrategia);
		elseif ($avaliacao_acao) $sql->adOnde('avaliacao_gestao_acao='.(int)$avaliacao_acao);
		elseif ($avaliacao_pratica) $sql->adOnde('avaliacao_gestao_pratica='.(int)$avaliacao_pratica);
		elseif ($avaliacao_meta) $sql->adOnde('avaliacao_gestao_meta='.(int)$avaliacao_meta);
		elseif ($avaliacao_canvas) $sql->adOnde('avaliacao_gestao_canvas='.(int)$avaliacao_canvas);
		elseif ($avaliacao_risco) $sql->adOnde('avaliacao_gestao_risco='.(int)$avaliacao_risco);
		elseif ($avaliacao_risco_resposta) $sql->adOnde('avaliacao_gestao_risco_resposta='.(int)$avaliacao_risco_resposta);
		elseif ($avaliacao_indicador) $sql->adOnde('avaliacao_gestao_indicador='.(int)$avaliacao_indicador);
		elseif ($avaliacao_calendario) $sql->adOnde('avaliacao_gestao_calendario='.(int)$avaliacao_calendario);
		elseif ($avaliacao_monitoramento) $sql->adOnde('avaliacao_gestao_monitoramento='.(int)$avaliacao_monitoramento);
		elseif ($avaliacao_ata) $sql->adOnde('avaliacao_gestao_ata='.(int)$avaliacao_ata);
		elseif ($avaliacao_mswot) $sql->adOnde('avaliacao_gestao_mswot='.(int)$avaliacao_mswot);
		elseif ($avaliacao_swot) $sql->adOnde('avaliacao_gestao_swot='.(int)$avaliacao_swot);
		elseif ($avaliacao_operativo) $sql->adOnde('avaliacao_gestao_operativo='.(int)$avaliacao_operativo);
		elseif ($avaliacao_instrumento) $sql->adOnde('avaliacao_gestao_instrumento='.(int)$avaliacao_instrumento);
		elseif ($avaliacao_recurso) $sql->adOnde('avaliacao_gestao_recurso='.(int)$avaliacao_recurso);
		elseif ($avaliacao_problema) $sql->adOnde('avaliacao_gestao_problema='.(int)$avaliacao_problema);
		elseif ($avaliacao_demanda) $sql->adOnde('avaliacao_gestao_demanda='.(int)$avaliacao_demanda);
		elseif ($avaliacao_programa) $sql->adOnde('avaliacao_gestao_programa='.(int)$avaliacao_programa);
		elseif ($avaliacao_licao) $sql->adOnde('avaliacao_gestao_licao='.(int)$avaliacao_licao);
		elseif ($avaliacao_evento) $sql->adOnde('avaliacao_gestao_evento='.(int)$avaliacao_evento);
		elseif ($avaliacao_link) $sql->adOnde('avaliacao_gestao_link='.(int)$avaliacao_link);
		
		elseif ($avaliacao_avaliacao) $sql->adOnde('avaliacao_gestao_semelhante='.(int)$avaliacao_avaliacao);
		
		elseif ($avaliacao_tgn) $sql->adOnde('avaliacao_gestao_tgn='.(int)$avaliacao_tgn);
		elseif ($avaliacao_brainstorm) $sql->adOnde('avaliacao_gestao_brainstorm='.(int)$avaliacao_brainstorm);
		elseif ($avaliacao_gut) $sql->adOnde('avaliacao_gestao_gut='.(int)$avaliacao_gut);
		elseif ($avaliacao_causa_efeito) $sql->adOnde('avaliacao_gestao_causa_efeito='.(int)$avaliacao_causa_efeito);
		elseif ($avaliacao_arquivo) $sql->adOnde('avaliacao_gestao_arquivo='.(int)$avaliacao_arquivo);
		elseif ($avaliacao_forum) $sql->adOnde('avaliacao_gestao_forum='.(int)$avaliacao_forum);
		elseif ($avaliacao_checklist) $sql->adOnde('avaliacao_gestao_checklist='.(int)$avaliacao_checklist);
		elseif ($avaliacao_agenda) $sql->adOnde('avaliacao_gestao_agenda='.(int)$avaliacao_agenda);
		elseif ($avaliacao_agrupamento) $sql->adOnde('avaliacao_gestao_agrupamento='.(int)$avaliacao_agrupamento);
		elseif ($avaliacao_patrocinador) $sql->adOnde('avaliacao_gestao_patrocinador='.(int)$avaliacao_patrocinador);
		elseif ($avaliacao_template) $sql->adOnde('avaliacao_gestao_template='.(int)$avaliacao_template);
		elseif ($avaliacao_painel) $sql->adOnde('avaliacao_gestao_painel='.(int)$avaliacao_painel);
		elseif ($avaliacao_painel_odometro) $sql->adOnde('avaliacao_gestao_painel_odometro='.(int)$avaliacao_painel_odometro);
		elseif ($avaliacao_painel_composicao) $sql->adOnde('avaliacao_gestao_painel_composicao='.(int)$avaliacao_painel_composicao);
		elseif ($avaliacao_tr) $sql->adOnde('avaliacao_gestao_tr='.(int)$avaliacao_tr);
		elseif ($avaliacao_me) $sql->adOnde('avaliacao_gestao_me='.(int)$avaliacao_me);
		elseif ($avaliacao_acao_item) $sql->adOnde('avaliacao_gestao_acao_item='.(int)$avaliacao_acao_item);
		elseif ($avaliacao_beneficio) $sql->adOnde('avaliacao_gestao_beneficio='.(int)$avaliacao_beneficio);
		elseif ($avaliacao_painel_slideshow) $sql->adOnde('avaliacao_gestao_painel_slideshow='.(int)$avaliacao_painel_slideshow);
		elseif ($avaliacao_projeto_viabilidade) $sql->adOnde('avaliacao_gestao_projeto_viabilidade='.(int)$avaliacao_projeto_viabilidade);
		elseif ($avaliacao_projeto_abertura) $sql->adOnde('avaliacao_gestao_projeto_abertura='.(int)$avaliacao_projeto_abertura);
		elseif ($avaliacao_plano_gestao) $sql->adOnde('avaliacao_gestao_plano_gestao='.(int)$avaliacao_plano_gestao);
		elseif ($avaliacao_ssti) $sql->adOnde('avaliacao_gestao_ssti='.(int)$avaliacao_ssti);
		elseif ($avaliacao_laudo) $sql->adOnde('avaliacao_gestao_laudo='.(int)$avaliacao_laudo);
		elseif ($avaliacao_trelo) $sql->adOnde('avaliacao_gestao_trelo='.(int)$avaliacao_trelo);
		elseif ($avaliacao_trelo_cartao) $sql->adOnde('avaliacao_gestao_trelo_cartao='.(int)$avaliacao_trelo_cartao);
		elseif ($avaliacao_pdcl) $sql->adOnde('avaliacao_gestao_pdcl='.(int)$avaliacao_pdcl);
		elseif ($avaliacao_pdcl_item) $sql->adOnde('avaliacao_gestao_pdcl_item='.(int)$avaliacao_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('avaliacao_gestao');
			$sql->adCampo('MAX(avaliacao_gestao_ordem)');
			if ($uuid) $sql->adOnde('avaliacao_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('avaliacao_gestao_avaliacao ='.(int)$avaliacao_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('avaliacao_gestao');
			if ($uuid) $sql->adInserir('avaliacao_gestao_uuid', $uuid);
			else $sql->adInserir('avaliacao_gestao_avaliacao', (int)$avaliacao_id);
			
			if ($avaliacao_tarefa) $sql->adInserir('avaliacao_gestao_tarefa', (int)$avaliacao_tarefa);
			if ($avaliacao_projeto) $sql->adInserir('avaliacao_gestao_projeto', (int)$avaliacao_projeto);
			elseif ($avaliacao_perspectiva) $sql->adInserir('avaliacao_gestao_perspectiva', (int)$avaliacao_perspectiva);
			elseif ($avaliacao_tema) $sql->adInserir('avaliacao_gestao_tema', (int)$avaliacao_tema);
			elseif ($avaliacao_objetivo) $sql->adInserir('avaliacao_gestao_objetivo', (int)$avaliacao_objetivo);
			elseif ($avaliacao_fator) $sql->adInserir('avaliacao_gestao_fator', (int)$avaliacao_fator);
			elseif ($avaliacao_estrategia) $sql->adInserir('avaliacao_gestao_estrategia', (int)$avaliacao_estrategia);
			elseif ($avaliacao_acao) $sql->adInserir('avaliacao_gestao_acao', (int)$avaliacao_acao);
			elseif ($avaliacao_pratica) $sql->adInserir('avaliacao_gestao_pratica', (int)$avaliacao_pratica);
			elseif ($avaliacao_meta) $sql->adInserir('avaliacao_gestao_meta', (int)$avaliacao_meta);
			elseif ($avaliacao_canvas) $sql->adInserir('avaliacao_gestao_canvas', (int)$avaliacao_canvas);
			elseif ($avaliacao_risco) $sql->adInserir('avaliacao_gestao_risco', (int)$avaliacao_risco);
			elseif ($avaliacao_risco_resposta) $sql->adInserir('avaliacao_gestao_risco_resposta', (int)$avaliacao_risco_resposta);
			elseif ($avaliacao_indicador) $sql->adInserir('avaliacao_gestao_indicador', (int)$avaliacao_indicador);
			elseif ($avaliacao_calendario) $sql->adInserir('avaliacao_gestao_calendario', (int)$avaliacao_calendario);
			elseif ($avaliacao_monitoramento) $sql->adInserir('avaliacao_gestao_monitoramento', (int)$avaliacao_monitoramento);
			elseif ($avaliacao_ata) $sql->adInserir('avaliacao_gestao_ata', (int)$avaliacao_ata);
			elseif ($avaliacao_mswot) $sql->adInserir('avaliacao_gestao_mswot', (int)$avaliacao_mswot);
			elseif ($avaliacao_swot) $sql->adInserir('avaliacao_gestao_swot', (int)$avaliacao_swot);
			elseif ($avaliacao_operativo) $sql->adInserir('avaliacao_gestao_operativo', (int)$avaliacao_operativo);
			elseif ($avaliacao_instrumento) $sql->adInserir('avaliacao_gestao_instrumento', (int)$avaliacao_instrumento);
			elseif ($avaliacao_recurso) $sql->adInserir('avaliacao_gestao_recurso', (int)$avaliacao_recurso);
			elseif ($avaliacao_problema) $sql->adInserir('avaliacao_gestao_problema', (int)$avaliacao_problema);
			elseif ($avaliacao_demanda) $sql->adInserir('avaliacao_gestao_demanda', (int)$avaliacao_demanda);
			elseif ($avaliacao_programa) $sql->adInserir('avaliacao_gestao_programa', (int)$avaliacao_programa);
			elseif ($avaliacao_licao) $sql->adInserir('avaliacao_gestao_licao', (int)$avaliacao_licao);
			elseif ($avaliacao_evento) $sql->adInserir('avaliacao_gestao_evento', (int)$avaliacao_evento);
			elseif ($avaliacao_link) $sql->adInserir('avaliacao_gestao_link', (int)$avaliacao_link);
			
			elseif ($avaliacao_avaliacao) $sql->adInserir('avaliacao_gestao_semelhante', (int)$avaliacao_avaliacao);
			
			elseif ($avaliacao_tgn) $sql->adInserir('avaliacao_gestao_tgn', (int)$avaliacao_tgn);
			elseif ($avaliacao_brainstorm) $sql->adInserir('avaliacao_gestao_brainstorm', (int)$avaliacao_brainstorm);
			elseif ($avaliacao_gut) $sql->adInserir('avaliacao_gestao_gut', (int)$avaliacao_gut);
			elseif ($avaliacao_causa_efeito) $sql->adInserir('avaliacao_gestao_causa_efeito', (int)$avaliacao_causa_efeito);
			elseif ($avaliacao_arquivo) $sql->adInserir('avaliacao_gestao_arquivo', (int)$avaliacao_arquivo);
			elseif ($avaliacao_forum) $sql->adInserir('avaliacao_gestao_forum', (int)$avaliacao_forum);
			elseif ($avaliacao_checklist) $sql->adInserir('avaliacao_gestao_checklist', (int)$avaliacao_checklist);
			elseif ($avaliacao_agenda) $sql->adInserir('avaliacao_gestao_agenda', (int)$avaliacao_agenda);
			elseif ($avaliacao_agrupamento) $sql->adInserir('avaliacao_gestao_agrupamento', (int)$avaliacao_agrupamento);
			elseif ($avaliacao_patrocinador) $sql->adInserir('avaliacao_gestao_patrocinador', (int)$avaliacao_patrocinador);
			elseif ($avaliacao_template) $sql->adInserir('avaliacao_gestao_template', (int)$avaliacao_template);
			elseif ($avaliacao_painel) $sql->adInserir('avaliacao_gestao_painel', (int)$avaliacao_painel);
			elseif ($avaliacao_painel_odometro) $sql->adInserir('avaliacao_gestao_painel_odometro', (int)$avaliacao_painel_odometro);
			elseif ($avaliacao_painel_composicao) $sql->adInserir('avaliacao_gestao_painel_composicao', (int)$avaliacao_painel_composicao);
			elseif ($avaliacao_tr) $sql->adInserir('avaliacao_gestao_tr', (int)$avaliacao_tr);
			elseif ($avaliacao_me) $sql->adInserir('avaliacao_gestao_me', (int)$avaliacao_me);
			elseif ($avaliacao_acao_item) $sql->adInserir('avaliacao_gestao_acao_item', (int)$avaliacao_acao_item);
			elseif ($avaliacao_beneficio) $sql->adInserir('avaliacao_gestao_beneficio', (int)$avaliacao_beneficio);
			elseif ($avaliacao_painel_slideshow) $sql->adInserir('avaliacao_gestao_painel_slideshow', (int)$avaliacao_painel_slideshow);
			elseif ($avaliacao_projeto_viabilidade) $sql->adInserir('avaliacao_gestao_projeto_viabilidade', (int)$avaliacao_projeto_viabilidade);
			elseif ($avaliacao_projeto_abertura) $sql->adInserir('avaliacao_gestao_projeto_abertura', (int)$avaliacao_projeto_abertura);
			elseif ($avaliacao_plano_gestao) $sql->adInserir('avaliacao_gestao_plano_gestao', (int)$avaliacao_plano_gestao);
			elseif ($avaliacao_ssti) $sql->adInserir('avaliacao_gestao_ssti', (int)$avaliacao_ssti);
			elseif ($avaliacao_laudo) $sql->adInserir('avaliacao_gestao_laudo', (int)$avaliacao_laudo);
			elseif ($avaliacao_trelo) $sql->adInserir('avaliacao_gestao_trelo', (int)$avaliacao_trelo);
			elseif ($avaliacao_trelo_cartao) $sql->adInserir('avaliacao_gestao_trelo_cartao', (int)$avaliacao_trelo_cartao);
			elseif ($avaliacao_pdcl) $sql->adInserir('avaliacao_gestao_pdcl', (int)$avaliacao_pdcl);
			elseif ($avaliacao_pdcl_item) $sql->adInserir('avaliacao_gestao_pdcl_item', (int)$avaliacao_pdcl_item);
			$sql->adInserir('avaliacao_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($avaliacao_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($avaliacao_id=0, $uuid='', $avaliacao_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('avaliacao_gestao');
	$sql->adOnde('avaliacao_gestao_id='.(int)$avaliacao_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($avaliacao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($avaliacao_id=0, $uuid=''){	
	$saida=atualizar_gestao($avaliacao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($avaliacao_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('avaliacao_gestao');
	$sql->adCampo('avaliacao_gestao.*');
	if ($uuid) $sql->adOnde('avaliacao_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('avaliacao_gestao_avaliacao ='.(int)$avaliacao_id);	
	$sql->adOrdem('avaliacao_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['avaliacao_gestao_ordem'].', '.$gestao_data['avaliacao_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['avaliacao_gestao_ordem'].', '.$gestao_data['avaliacao_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['avaliacao_gestao_ordem'].', '.$gestao_data['avaliacao_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['avaliacao_gestao_ordem'].', '.$gestao_data['avaliacao_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['avaliacao_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['avaliacao_gestao_tarefa']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['avaliacao_gestao_projeto']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['avaliacao_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['avaliacao_gestao_tema']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['avaliacao_gestao_objetivo']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['avaliacao_gestao_fator']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['avaliacao_gestao_estrategia']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['avaliacao_gestao_meta']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['avaliacao_gestao_pratica']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['avaliacao_gestao_acao']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['avaliacao_gestao_canvas']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['avaliacao_gestao_risco']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['avaliacao_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['avaliacao_gestao_indicador']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['avaliacao_gestao_calendario']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['avaliacao_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['avaliacao_gestao_ata']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['avaliacao_gestao_mswot']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['avaliacao_gestao_swot']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['avaliacao_gestao_operativo']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['avaliacao_gestao_instrumento']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['avaliacao_gestao_recurso']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['avaliacao_gestao_problema']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['avaliacao_gestao_demanda']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['avaliacao_gestao_programa']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['avaliacao_gestao_licao']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['avaliacao_gestao_evento']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['avaliacao_gestao_link']).'</td>';
		
		elseif ($gestao_data['avaliacao_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['avaliacao_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['avaliacao_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['avaliacao_gestao_tgn']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['avaliacao_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['avaliacao_gestao_gut']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['avaliacao_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['avaliacao_gestao_arquivo']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['avaliacao_gestao_forum']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['avaliacao_gestao_checklist']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['avaliacao_gestao_agenda']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['avaliacao_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['avaliacao_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['avaliacao_gestao_template']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['avaliacao_gestao_painel']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['avaliacao_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['avaliacao_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['avaliacao_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['avaliacao_gestao_tr']).'</td>';	
		elseif ($gestao_data['avaliacao_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['avaliacao_gestao_me']).'</td>';	
		elseif ($gestao_data['avaliacao_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['avaliacao_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['avaliacao_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['avaliacao_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['avaliacao_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['avaliacao_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['avaliacao_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['avaliacao_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['avaliacao_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['avaliacao_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['avaliacao_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['avaliacao_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['avaliacao_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['avaliacao_gestao_ssti']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['avaliacao_gestao_laudo']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['avaliacao_gestao_trelo']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['avaliacao_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['avaliacao_gestao_pdcl']).'</td>';
		elseif ($gestao_data['avaliacao_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['avaliacao_gestao_pdcl_item']).'</td>';

		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['avaliacao_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}	
	
	
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


$xajax->processRequest();

?>