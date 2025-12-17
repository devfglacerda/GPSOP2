<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');	


function mudar_posicao_gestao($ordem, $arquivo_pasta_gestao_id, $direcao, $arquivo_pasta_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $arquivo_pasta_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('arquivo_pasta_gestao');
		$sql->adOnde('arquivo_pasta_gestao_id != '.(int)$arquivo_pasta_gestao_id);
		if ($uuid) $sql->adOnde('arquivo_pasta_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('arquivo_pasta_gestao_pasta = '.(int)$arquivo_pasta_id);
		$sql->adOrdem('arquivo_pasta_gestao_ordem');
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
			$sql->adTabela('arquivo_pasta_gestao');
			$sql->adAtualizar('arquivo_pasta_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('arquivo_pasta_gestao_id = '.(int)$arquivo_pasta_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('arquivo_pasta_gestao');
					$sql->adAtualizar('arquivo_pasta_gestao_ordem', $idx);
					$sql->adOnde('arquivo_pasta_gestao_id = '.(int)$acao['arquivo_pasta_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('arquivo_pasta_gestao');
					$sql->adAtualizar('arquivo_pasta_gestao_ordem', $idx + 1);
					$sql->adOnde('arquivo_pasta_gestao_id = '.(int)$acao['arquivo_pasta_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($arquivo_pasta_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$arquivo_pasta_id=0, 
	$uuid='',  
	
	$arquivo_pasta_projeto=null,
	$arquivo_pasta_tarefa=null,
	$arquivo_pasta_perspectiva=null,
	$arquivo_pasta_tema=null,
	$arquivo_pasta_objetivo=null,
	$arquivo_pasta_fator=null,
	$arquivo_pasta_estrategia=null,
	$arquivo_pasta_meta=null,
	$arquivo_pasta_pratica=null,
	$arquivo_pasta_acao=null,
	$arquivo_pasta_canvas=null,
	$arquivo_pasta_risco=null,
	$arquivo_pasta_risco_resposta=null,
	$arquivo_pasta_indicador=null,
	$arquivo_pasta_calendario=null,
	$arquivo_pasta_monitoramento=null,
	$arquivo_pasta_ata=null,
	$arquivo_pasta_mswot=null,
	$arquivo_pasta_swot=null,
	$arquivo_pasta_operativo=null,
	$arquivo_pasta_instrumento=null,
	$arquivo_pasta_recurso=null,
	$arquivo_pasta_problema=null,
	$arquivo_pasta_demanda=null,
	$arquivo_pasta_programa=null,
	$arquivo_pasta_licao=null,
	$arquivo_pasta_evento=null,
	$arquivo_pasta_link=null,
	$arquivo_pasta_avaliacao=null,
	$arquivo_pasta_tgn=null,
	$arquivo_pasta_brainstorm=null,
	$arquivo_pasta_gut=null,
	$arquivo_pasta_causa_efeito=null,
	$arquivo_pasta_arquivo=null,
	$arquivo_pasta_forum=null,
	$arquivo_pasta_checklist=null,
	$arquivo_pasta_agenda=null,
	$arquivo_pasta_agrupamento=null,
	$arquivo_pasta_patrocinador=null,
	$arquivo_pasta_template=null,
	$arquivo_pasta_painel=null,
	$arquivo_pasta_painel_odometro=null,
	$arquivo_pasta_painel_composicao=null,
	$arquivo_pasta_tr=null,
	$arquivo_pasta_me=null,
	$arquivo_pasta_acao_item=null,
	$arquivo_pasta_beneficio=null,
	$arquivo_pasta_painel_slideshow=null,
	$arquivo_pasta_projeto_viabilidade=null,
	$arquivo_pasta_projeto_abertura=null,
	$arquivo_pasta_plano_gestao=null,
	$arquivo_pasta_ssti=null,
	$arquivo_pasta_laudo=null,
	$arquivo_pasta_trelo=null,
	$arquivo_pasta_trelo_cartao=null,
	$arquivo_pasta_pdcl=null,
	$arquivo_pasta_pdcl_item=null,
	
	$arquivo_pasta_usuario=null,
	$arquivo_pasta_pasta=null
	)
	{
	if (
		$arquivo_pasta_projeto || 
		$arquivo_pasta_tarefa || 
		$arquivo_pasta_perspectiva || 
		$arquivo_pasta_tema || 
		$arquivo_pasta_objetivo || 
		$arquivo_pasta_fator || 
		$arquivo_pasta_estrategia || 
		$arquivo_pasta_meta || 
		$arquivo_pasta_pratica || 
		$arquivo_pasta_acao || 
		$arquivo_pasta_canvas || 
		$arquivo_pasta_risco || 
		$arquivo_pasta_risco_resposta || 
		$arquivo_pasta_indicador || 
		$arquivo_pasta_calendario || 
		$arquivo_pasta_monitoramento || 
		$arquivo_pasta_ata || 
		$arquivo_pasta_mswot || 
		$arquivo_pasta_swot || 
		$arquivo_pasta_operativo || 
		$arquivo_pasta_instrumento || 
		$arquivo_pasta_recurso || 
		$arquivo_pasta_problema || 
		$arquivo_pasta_demanda || 
		$arquivo_pasta_programa || 
		$arquivo_pasta_licao || 
		$arquivo_pasta_evento || 
		$arquivo_pasta_link || 
		$arquivo_pasta_avaliacao || 
		$arquivo_pasta_tgn || 
		$arquivo_pasta_brainstorm || 
		$arquivo_pasta_gut || 
		$arquivo_pasta_causa_efeito || 
		$arquivo_pasta_arquivo || 
		$arquivo_pasta_forum || 
		$arquivo_pasta_checklist || 
		$arquivo_pasta_agenda || 
		$arquivo_pasta_agrupamento || 
		$arquivo_pasta_patrocinador || 
		$arquivo_pasta_template || 
		$arquivo_pasta_painel || 
		$arquivo_pasta_painel_odometro || 
		$arquivo_pasta_painel_composicao || 
		$arquivo_pasta_tr || 
		$arquivo_pasta_me || 
		$arquivo_pasta_acao_item || 
		$arquivo_pasta_beneficio || 
		$arquivo_pasta_painel_slideshow || 
		$arquivo_pasta_projeto_viabilidade || 
		$arquivo_pasta_projeto_abertura || 
		$arquivo_pasta_plano_gestao || 
		$arquivo_pasta_ssti || 
		$arquivo_pasta_laudo || 
		$arquivo_pasta_trelo || 
		$arquivo_pasta_trelo_cartao || 
		$arquivo_pasta_pdcl || 
		$arquivo_pasta_pdcl_item ||
		
		$arquivo_pasta_usuario ||
		$arquivo_pasta_pasta
		){

		global $Aplic;
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('arquivo_pasta_gestao');
			if ($uuid) $sql->adOnde('arquivo_pasta_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('arquivo_pasta_gestao_pasta ='.(int)$arquivo_pasta_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('arquivo_pasta_gestao');
		$sql->adCampo('count(arquivo_pasta_gestao_id)');
		if ($uuid) $sql->adOnde('arquivo_pasta_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('arquivo_pasta_gestao_pasta ='.(int)$arquivo_pasta_id);	
		if ($arquivo_pasta_tarefa) $sql->adOnde('arquivo_pasta_gestao_tarefa='.(int)$arquivo_pasta_tarefa);
		elseif ($arquivo_pasta_projeto) $sql->adOnde('arquivo_pasta_gestao_projeto='.(int)$arquivo_pasta_projeto);
		elseif ($arquivo_pasta_perspectiva) $sql->adOnde('arquivo_pasta_gestao_perspectiva='.(int)$arquivo_pasta_perspectiva);
		elseif ($arquivo_pasta_tema) $sql->adOnde('arquivo_pasta_gestao_tema='.(int)$arquivo_pasta_tema);
		elseif ($arquivo_pasta_objetivo) $sql->adOnde('arquivo_pasta_gestao_objetivo='.(int)$arquivo_pasta_objetivo);
		elseif ($arquivo_pasta_fator) $sql->adOnde('arquivo_pasta_gestao_fator='.(int)$arquivo_pasta_fator);
		elseif ($arquivo_pasta_estrategia) $sql->adOnde('arquivo_pasta_gestao_estrategia='.(int)$arquivo_pasta_estrategia);
		elseif ($arquivo_pasta_acao) $sql->adOnde('arquivo_pasta_gestao_acao='.(int)$arquivo_pasta_acao);
		elseif ($arquivo_pasta_pratica) $sql->adOnde('arquivo_pasta_gestao_pratica='.(int)$arquivo_pasta_pratica);
		elseif ($arquivo_pasta_meta) $sql->adOnde('arquivo_pasta_gestao_meta='.(int)$arquivo_pasta_meta);
		elseif ($arquivo_pasta_canvas) $sql->adOnde('arquivo_pasta_gestao_canvas='.(int)$arquivo_pasta_canvas);
		elseif ($arquivo_pasta_risco) $sql->adOnde('arquivo_pasta_gestao_risco='.(int)$arquivo_pasta_risco);
		elseif ($arquivo_pasta_risco_resposta) $sql->adOnde('arquivo_pasta_gestao_risco_resposta='.(int)$arquivo_pasta_risco_resposta);
		elseif ($arquivo_pasta_indicador) $sql->adOnde('arquivo_pasta_gestao_indicador='.(int)$arquivo_pasta_indicador);
		elseif ($arquivo_pasta_calendario) $sql->adOnde('arquivo_pasta_gestao_calendario='.(int)$arquivo_pasta_calendario);
		elseif ($arquivo_pasta_monitoramento) $sql->adOnde('arquivo_pasta_gestao_monitoramento='.(int)$arquivo_pasta_monitoramento);
		elseif ($arquivo_pasta_ata) $sql->adOnde('arquivo_pasta_gestao_ata='.(int)$arquivo_pasta_ata);
		elseif ($arquivo_pasta_mswot) $sql->adOnde('arquivo_pasta_gestao_mswot='.(int)$arquivo_pasta_mswot);
		elseif ($arquivo_pasta_swot) $sql->adOnde('arquivo_pasta_gestao_swot='.(int)$arquivo_pasta_swot);
		elseif ($arquivo_pasta_operativo) $sql->adOnde('arquivo_pasta_gestao_operativo='.(int)$arquivo_pasta_operativo);
		elseif ($arquivo_pasta_instrumento) $sql->adOnde('arquivo_pasta_gestao_instrumento='.(int)$arquivo_pasta_instrumento);
		elseif ($arquivo_pasta_recurso) $sql->adOnde('arquivo_pasta_gestao_recurso='.(int)$arquivo_pasta_recurso);
		elseif ($arquivo_pasta_problema) $sql->adOnde('arquivo_pasta_gestao_problema='.(int)$arquivo_pasta_problema);
		elseif ($arquivo_pasta_demanda) $sql->adOnde('arquivo_pasta_gestao_demanda='.(int)$arquivo_pasta_demanda);
		elseif ($arquivo_pasta_programa) $sql->adOnde('arquivo_pasta_gestao_programa='.(int)$arquivo_pasta_programa);
		elseif ($arquivo_pasta_licao) $sql->adOnde('arquivo_pasta_gestao_licao='.(int)$arquivo_pasta_licao);
		elseif ($arquivo_pasta_evento) $sql->adOnde('arquivo_pasta_gestao_evento='.(int)$arquivo_pasta_evento);
		elseif ($arquivo_pasta_link) $sql->adOnde('arquivo_pasta_gestao_link='.(int)$arquivo_pasta_link);
		elseif ($arquivo_pasta_avaliacao) $sql->adOnde('arquivo_pasta_gestao_avaliacao='.(int)$arquivo_pasta_avaliacao);
		elseif ($arquivo_pasta_tgn) $sql->adOnde('arquivo_pasta_gestao_tgn='.(int)$arquivo_pasta_tgn);
		elseif ($arquivo_pasta_brainstorm) $sql->adOnde('arquivo_pasta_gestao_brainstorm='.(int)$arquivo_pasta_brainstorm);
		elseif ($arquivo_pasta_gut) $sql->adOnde('arquivo_pasta_gestao_gut='.(int)$arquivo_pasta_gut);
		elseif ($arquivo_pasta_causa_efeito) $sql->adOnde('arquivo_pasta_gestao_causa_efeito='.(int)$arquivo_pasta_causa_efeito);
		elseif ($arquivo_pasta_arquivo) $sql->adOnde('arquivo_pasta_gestao_arquivo='.(int)$arquivo_pasta_arquivo);
		elseif ($arquivo_pasta_forum) $sql->adOnde('arquivo_pasta_gestao_forum='.(int)$arquivo_pasta_forum);
		elseif ($arquivo_pasta_checklist) $sql->adOnde('arquivo_pasta_gestao_checklist='.(int)$arquivo_pasta_checklist);
		elseif ($arquivo_pasta_agenda) $sql->adOnde('arquivo_pasta_gestao_agenda='.(int)$arquivo_pasta_agenda);
		elseif ($arquivo_pasta_agrupamento) $sql->adOnde('arquivo_pasta_gestao_agrupamento='.(int)$arquivo_pasta_agrupamento);
		elseif ($arquivo_pasta_patrocinador) $sql->adOnde('arquivo_pasta_gestao_patrocinador='.(int)$arquivo_pasta_patrocinador);
		elseif ($arquivo_pasta_template) $sql->adOnde('arquivo_pasta_gestao_template='.(int)$arquivo_pasta_template);
		elseif ($arquivo_pasta_painel) $sql->adOnde('arquivo_pasta_gestao_painel='.(int)$arquivo_pasta_painel);
		elseif ($arquivo_pasta_painel_odometro) $sql->adOnde('arquivo_pasta_gestao_painel_odometro='.(int)$arquivo_pasta_painel_odometro);
		elseif ($arquivo_pasta_painel_composicao) $sql->adOnde('arquivo_pasta_gestao_painel_composicao='.(int)$arquivo_pasta_painel_composicao);
		elseif ($arquivo_pasta_tr) $sql->adOnde('arquivo_pasta_gestao_tr='.(int)$arquivo_pasta_tr);
		elseif ($arquivo_pasta_me) $sql->adOnde('arquivo_pasta_gestao_me='.(int)$arquivo_pasta_me);
		elseif ($arquivo_pasta_acao_item) $sql->adOnde('arquivo_pasta_gestao_acao_item='.(int)$arquivo_pasta_acao_item);
		elseif ($arquivo_pasta_beneficio) $sql->adOnde('arquivo_pasta_gestao_beneficio='.(int)$arquivo_pasta_beneficio);
		elseif ($arquivo_pasta_painel_slideshow) $sql->adOnde('arquivo_pasta_gestao_painel_slideshow='.(int)$arquivo_pasta_painel_slideshow);
		elseif ($arquivo_pasta_projeto_viabilidade) $sql->adOnde('arquivo_pasta_gestao_projeto_viabilidade='.(int)$arquivo_pasta_projeto_viabilidade);
		elseif ($arquivo_pasta_projeto_abertura) $sql->adOnde('arquivo_pasta_gestao_projeto_abertura='.(int)$arquivo_pasta_projeto_abertura);
		elseif ($arquivo_pasta_plano_gestao) $sql->adOnde('arquivo_pasta_gestao_plano_gestao='.(int)$arquivo_pasta_plano_gestao);
		elseif ($arquivo_pasta_ssti) $sql->adOnde('arquivo_pasta_gestao_ssti='.(int)$arquivo_pasta_ssti);
		elseif ($arquivo_pasta_laudo) $sql->adOnde('arquivo_pasta_gestao_laudo='.(int)$arquivo_pasta_laudo);
		elseif ($arquivo_pasta_trelo) $sql->adOnde('arquivo_pasta_gestao_trelo='.(int)$arquivo_pasta_trelo);
		elseif ($arquivo_pasta_trelo_cartao) $sql->adOnde('arquivo_pasta_gestao_trelo_cartao='.(int)$arquivo_pasta_trelo_cartao);
		elseif ($arquivo_pasta_pdcl) $sql->adOnde('arquivo_pasta_gestao_pdcl='.(int)$arquivo_pasta_pdcl);
		elseif ($arquivo_pasta_pdcl_item) $sql->adOnde('arquivo_pasta_gestao_pdcl_item='.(int)$arquivo_pasta_pdcl_item); 
		
		elseif ($arquivo_pasta_usuario) $sql->adOnde('arquivo_pasta_gestao_usuario='.(int)$arquivo_pasta_usuario);
		elseif ($arquivo_pasta_pasta) $sql->adOnde('arquivo_pasta_gestao_semelhante='.(int)$arquivo_pasta_pasta);



	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('arquivo_pasta_gestao');
			$sql->adCampo('MAX(arquivo_pasta_gestao_ordem)');
			if ($uuid) $sql->adOnde('arquivo_pasta_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('arquivo_pasta_gestao_pasta ='.(int)$arquivo_pasta_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('arquivo_pasta_gestao');
			if ($uuid) $sql->adInserir('arquivo_pasta_gestao_uuid', $uuid);
			else $sql->adInserir('arquivo_pasta_gestao_pasta', (int)$arquivo_pasta_id);
			
			if ($arquivo_pasta_tarefa) $sql->adInserir('arquivo_pasta_gestao_tarefa', (int)$arquivo_pasta_tarefa);
			if ($arquivo_pasta_projeto) $sql->adInserir('arquivo_pasta_gestao_projeto', (int)$arquivo_pasta_projeto);
			elseif ($arquivo_pasta_perspectiva) $sql->adInserir('arquivo_pasta_gestao_perspectiva', (int)$arquivo_pasta_perspectiva);
			elseif ($arquivo_pasta_tema) $sql->adInserir('arquivo_pasta_gestao_tema', (int)$arquivo_pasta_tema);
			elseif ($arquivo_pasta_objetivo) $sql->adInserir('arquivo_pasta_gestao_objetivo', (int)$arquivo_pasta_objetivo);
			elseif ($arquivo_pasta_fator) $sql->adInserir('arquivo_pasta_gestao_fator', (int)$arquivo_pasta_fator);
			elseif ($arquivo_pasta_estrategia) $sql->adInserir('arquivo_pasta_gestao_estrategia', (int)$arquivo_pasta_estrategia);
			elseif ($arquivo_pasta_acao) $sql->adInserir('arquivo_pasta_gestao_acao', (int)$arquivo_pasta_acao);
			elseif ($arquivo_pasta_pratica) $sql->adInserir('arquivo_pasta_gestao_pratica', (int)$arquivo_pasta_pratica);
			elseif ($arquivo_pasta_meta) $sql->adInserir('arquivo_pasta_gestao_meta', (int)$arquivo_pasta_meta);
			elseif ($arquivo_pasta_canvas) $sql->adInserir('arquivo_pasta_gestao_canvas', (int)$arquivo_pasta_canvas);
			elseif ($arquivo_pasta_risco) $sql->adInserir('arquivo_pasta_gestao_risco', (int)$arquivo_pasta_risco);
			elseif ($arquivo_pasta_risco_resposta) $sql->adInserir('arquivo_pasta_gestao_risco_resposta', (int)$arquivo_pasta_risco_resposta);
			elseif ($arquivo_pasta_indicador) $sql->adInserir('arquivo_pasta_gestao_indicador', (int)$arquivo_pasta_indicador);
			elseif ($arquivo_pasta_calendario) $sql->adInserir('arquivo_pasta_gestao_calendario', (int)$arquivo_pasta_calendario);
			elseif ($arquivo_pasta_monitoramento) $sql->adInserir('arquivo_pasta_gestao_monitoramento', (int)$arquivo_pasta_monitoramento);
			elseif ($arquivo_pasta_ata) $sql->adInserir('arquivo_pasta_gestao_ata', (int)$arquivo_pasta_ata);
			elseif ($arquivo_pasta_mswot) $sql->adInserir('arquivo_pasta_gestao_mswot', (int)$arquivo_pasta_mswot);
			elseif ($arquivo_pasta_swot) $sql->adInserir('arquivo_pasta_gestao_swot', (int)$arquivo_pasta_swot);
			elseif ($arquivo_pasta_operativo) $sql->adInserir('arquivo_pasta_gestao_operativo', (int)$arquivo_pasta_operativo);
			elseif ($arquivo_pasta_instrumento) $sql->adInserir('arquivo_pasta_gestao_instrumento', (int)$arquivo_pasta_instrumento);
			elseif ($arquivo_pasta_recurso) $sql->adInserir('arquivo_pasta_gestao_recurso', (int)$arquivo_pasta_recurso);
			elseif ($arquivo_pasta_problema) $sql->adInserir('arquivo_pasta_gestao_problema', (int)$arquivo_pasta_problema);
			elseif ($arquivo_pasta_demanda) $sql->adInserir('arquivo_pasta_gestao_demanda', (int)$arquivo_pasta_demanda);
			elseif ($arquivo_pasta_programa) $sql->adInserir('arquivo_pasta_gestao_programa', (int)$arquivo_pasta_programa);
			elseif ($arquivo_pasta_licao) $sql->adInserir('arquivo_pasta_gestao_licao', (int)$arquivo_pasta_licao);
			elseif ($arquivo_pasta_evento) $sql->adInserir('arquivo_pasta_gestao_evento', (int)$arquivo_pasta_evento);
			elseif ($arquivo_pasta_link) $sql->adInserir('arquivo_pasta_gestao_link', (int)$arquivo_pasta_link);
			elseif ($arquivo_pasta_avaliacao) $sql->adInserir('arquivo_pasta_gestao_avaliacao', (int)$arquivo_pasta_avaliacao);
			elseif ($arquivo_pasta_tgn) $sql->adInserir('arquivo_pasta_gestao_tgn', (int)$arquivo_pasta_tgn);
			elseif ($arquivo_pasta_brainstorm) $sql->adInserir('arquivo_pasta_gestao_brainstorm', (int)$arquivo_pasta_brainstorm);
			elseif ($arquivo_pasta_gut) $sql->adInserir('arquivo_pasta_gestao_gut', (int)$arquivo_pasta_gut);
			elseif ($arquivo_pasta_causa_efeito) $sql->adInserir('arquivo_pasta_gestao_causa_efeito', (int)$arquivo_pasta_causa_efeito);
			elseif ($arquivo_pasta_arquivo) $sql->adInserir('arquivo_pasta_gestao_arquivo', (int)$arquivo_pasta_arquivo);
			elseif ($arquivo_pasta_forum) $sql->adInserir('arquivo_pasta_gestao_forum', (int)$arquivo_pasta_forum);
			elseif ($arquivo_pasta_checklist) $sql->adInserir('arquivo_pasta_gestao_checklist', (int)$arquivo_pasta_checklist);
			elseif ($arquivo_pasta_agenda) $sql->adInserir('arquivo_pasta_gestao_agenda', (int)$arquivo_pasta_agenda);
			elseif ($arquivo_pasta_agrupamento) $sql->adInserir('arquivo_pasta_gestao_agrupamento', (int)$arquivo_pasta_agrupamento);
			elseif ($arquivo_pasta_patrocinador) $sql->adInserir('arquivo_pasta_gestao_patrocinador', (int)$arquivo_pasta_patrocinador);
			elseif ($arquivo_pasta_template) $sql->adInserir('arquivo_pasta_gestao_template', (int)$arquivo_pasta_template);
			elseif ($arquivo_pasta_painel) $sql->adInserir('arquivo_pasta_gestao_painel', (int)$arquivo_pasta_painel);
			elseif ($arquivo_pasta_painel_odometro) $sql->adInserir('arquivo_pasta_gestao_painel_odometro', (int)$arquivo_pasta_painel_odometro);
			elseif ($arquivo_pasta_painel_composicao) $sql->adInserir('arquivo_pasta_gestao_painel_composicao', (int)$arquivo_pasta_painel_composicao);
			elseif ($arquivo_pasta_tr) $sql->adInserir('arquivo_pasta_gestao_tr', (int)$arquivo_pasta_tr);
			elseif ($arquivo_pasta_me) $sql->adInserir('arquivo_pasta_gestao_me', (int)$arquivo_pasta_me);
			elseif ($arquivo_pasta_acao_item) $sql->adInserir('arquivo_pasta_gestao_acao_item', (int)$arquivo_pasta_acao_item);
			elseif ($arquivo_pasta_beneficio) $sql->adInserir('arquivo_pasta_gestao_beneficio', (int)$arquivo_pasta_beneficio);
			elseif ($arquivo_pasta_painel_slideshow) $sql->adInserir('arquivo_pasta_gestao_painel_slideshow', (int)$arquivo_pasta_painel_slideshow);
			elseif ($arquivo_pasta_projeto_viabilidade) $sql->adInserir('arquivo_pasta_gestao_projeto_viabilidade', (int)$arquivo_pasta_projeto_viabilidade);
			elseif ($arquivo_pasta_projeto_abertura) $sql->adInserir('arquivo_pasta_gestao_projeto_abertura', (int)$arquivo_pasta_projeto_abertura);
			elseif ($arquivo_pasta_plano_gestao) $sql->adInserir('arquivo_pasta_gestao_plano_gestao', (int)$arquivo_pasta_plano_gestao);
			elseif ($arquivo_pasta_ssti) $sql->adInserir('arquivo_pasta_gestao_ssti', (int)$arquivo_pasta_ssti);
			elseif ($arquivo_pasta_laudo) $sql->adInserir('arquivo_pasta_gestao_laudo', (int)$arquivo_pasta_laudo);
			elseif ($arquivo_pasta_trelo) $sql->adInserir('arquivo_pasta_gestao_trelo', (int)$arquivo_pasta_trelo);
			elseif ($arquivo_pasta_trelo_cartao) $sql->adInserir('arquivo_pasta_gestao_trelo_cartao', (int)$arquivo_pasta_trelo_cartao);
			elseif ($arquivo_pasta_pdcl) $sql->adInserir('arquivo_pasta_gestao_pdcl', (int)$arquivo_pasta_pdcl);
			elseif ($arquivo_pasta_pdcl_item) $sql->adInserir('arquivo_pasta_gestao_pdcl_item', (int)$arquivo_pasta_pdcl_item);
			
			elseif ($arquivo_pasta_usuario) $sql->adInserir('arquivo_pasta_gestao_usuario', (int)$arquivo_pasta_usuario);
			elseif ($arquivo_pasta_pasta) $sql->adInserir('arquivo_pasta_gestao_semelhante', (int)$arquivo_pasta_pasta);
			
			$sql->adInserir('arquivo_pasta_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($arquivo_pasta_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($arquivo_pasta_id=0, $uuid='', $arquivo_pasta_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('arquivo_pasta_gestao');
	$sql->adOnde('arquivo_pasta_gestao_id='.(int)$arquivo_pasta_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($arquivo_pasta_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($arquivo_pasta_id=0, $uuid=''){	
	$saida=atualizar_gestao($arquivo_pasta_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($arquivo_pasta_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('arquivo_pasta_gestao');
	$sql->adCampo('arquivo_pasta_gestao.*');
	if ($uuid) $sql->adOnde('arquivo_pasta_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('arquivo_pasta_gestao_pasta ='.(int)$arquivo_pasta_id);	
	$sql->adOrdem('arquivo_pasta_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_pasta_gestao_ordem'].', '.$gestao_data['arquivo_pasta_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_pasta_gestao_ordem'].', '.$gestao_data['arquivo_pasta_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_pasta_gestao_ordem'].', '.$gestao_data['arquivo_pasta_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_pasta_gestao_ordem'].', '.$gestao_data['arquivo_pasta_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['arquivo_pasta_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['arquivo_pasta_gestao_tarefa']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['arquivo_pasta_gestao_projeto']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['arquivo_pasta_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['arquivo_pasta_gestao_tema']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['arquivo_pasta_gestao_objetivo']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['arquivo_pasta_gestao_fator']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['arquivo_pasta_gestao_estrategia']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['arquivo_pasta_gestao_meta']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['arquivo_pasta_gestao_pratica']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['arquivo_pasta_gestao_acao']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['arquivo_pasta_gestao_canvas']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['arquivo_pasta_gestao_risco']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['arquivo_pasta_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['arquivo_pasta_gestao_indicador']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['arquivo_pasta_gestao_calendario']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['arquivo_pasta_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['arquivo_pasta_gestao_ata']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['arquivo_pasta_gestao_mswot']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['arquivo_pasta_gestao_swot']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['arquivo_pasta_gestao_operativo']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['arquivo_pasta_gestao_instrumento']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['arquivo_pasta_gestao_recurso']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['arquivo_pasta_gestao_problema']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['arquivo_pasta_gestao_demanda']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['arquivo_pasta_gestao_programa']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['arquivo_pasta_gestao_licao']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['arquivo_pasta_gestao_evento']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['arquivo_pasta_gestao_link']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['arquivo_pasta_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['arquivo_pasta_gestao_tgn']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['arquivo_pasta_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['arquivo_pasta_gestao_gut']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['arquivo_pasta_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['arquivo_pasta_gestao_arquivo']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['arquivo_pasta_gestao_forum']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['arquivo_pasta_gestao_checklist']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['arquivo_pasta_gestao_agenda']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['arquivo_pasta_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['arquivo_pasta_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['arquivo_pasta_gestao_template']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['arquivo_pasta_gestao_painel']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['arquivo_pasta_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['arquivo_pasta_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['arquivo_pasta_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['arquivo_pasta_gestao_tr']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['arquivo_pasta_gestao_me']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['arquivo_pasta_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['arquivo_pasta_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['arquivo_pasta_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['arquivo_pasta_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['arquivo_pasta_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['arquivo_pasta_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['arquivo_pasta_gestao_ssti']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['arquivo_pasta_gestao_laudo']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['arquivo_pasta_gestao_trelo']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['arquivo_pasta_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['arquivo_pasta_gestao_pdcl']).'</td>';
		elseif ($gestao_data['arquivo_pasta_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['arquivo_pasta_gestao_pdcl_item']).'</td>';

		elseif ($gestao_data['arquivo_pasta_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/pasta_p.png').link_pasta($gestao_data['arquivo_pasta_gestao_semelhante']).'</td>';	
		elseif ($gestao_data['arquivo_pasta_gestao_usuario']) $saida.= '<td align=left>'.imagem('icones/usuario_p.gif').link_usuario($gestao_data['arquivo_pasta_gestao_usuario']).'</td>';	
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['arquivo_pasta_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}	

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
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

$xajax->processRequest();
?>