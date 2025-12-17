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
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);


if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/gestao/gestao_editar_pro_ajax.php');


function mudar_posicao_gestao($ordem, $plano_gestao_gestao_id, $direcao, $pg_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $plano_gestao_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_gestao');
		$sql->adOnde('plano_gestao_gestao_id != '.(int)$plano_gestao_gestao_id);
		if ($uuid) $sql->adOnde('plano_gestao_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('plano_gestao_gestao_plano_gestao = '.(int)$pg_id);
		$sql->adOrdem('plano_gestao_gestao_ordem');
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
			$sql->adTabela('plano_gestao_gestao');
			$sql->adAtualizar('plano_gestao_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('plano_gestao_gestao_id = '.(int)$plano_gestao_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_gestao');
					$sql->adAtualizar('plano_gestao_gestao_ordem', $idx);
					$sql->adOnde('plano_gestao_gestao_id = '.(int)$acao['plano_gestao_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_gestao_gestao');
					$sql->adAtualizar('plano_gestao_gestao_ordem', $idx + 1);
					$sql->adOnde('plano_gestao_gestao_id = '.(int)$acao['plano_gestao_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($pg_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$pg_id=0, 
	$uuid='',  
	
	$plano_gestao_projeto=null,
	$plano_gestao_tarefa=null,
	$plano_gestao_perspectiva=null,
	$plano_gestao_tema=null,
	$plano_gestao_objetivo=null,
	$plano_gestao_fator=null,
	$plano_gestao_estrategia=null,
	$plano_gestao_meta=null,
	$plano_gestao_pratica=null,
	$plano_gestao_acao=null,
	$plano_gestao_canvas=null,
	$plano_gestao_risco=null,
	$plano_gestao_risco_resposta=null,
	$plano_gestao_indicador=null,
	$plano_gestao_calendario=null,
	$plano_gestao_monitoramento=null,
	$plano_gestao_ata=null,
	$plano_gestao_mswot=null,
	$plano_gestao_swot=null,
	$plano_gestao_operativo=null,
	$plano_gestao_instrumento=null,
	$plano_gestao_recurso=null,
	$plano_gestao_problema=null,
	$plano_gestao_demanda=null,
	$plano_gestao_programa=null,
	$plano_gestao_licao=null,
	$plano_gestao_evento=null,
	$plano_gestao_link=null,
	$plano_gestao_avaliacao=null,
	$plano_gestao_tgn=null,
	$plano_gestao_brainstorm=null,
	$plano_gestao_gut=null,
	$plano_gestao_causa_efeito=null,
	$plano_gestao_arquivo=null,
	$plano_gestao_forum=null,
	$plano_gestao_checklist=null,
	$plano_gestao_agenda=null,
	$plano_gestao_agrupamento=null,
	$plano_gestao_patrocinador=null,
	$plano_gestao_template=null,
	$plano_gestao_painel=null,
	$plano_gestao_painel_odometro=null,
	$plano_gestao_painel_composicao=null,
	$plano_gestao_tr=null,
	$plano_gestao_me=null,
	$plano_gestao_acao_item=null,
	$plano_gestao_beneficio=null,
	$plano_gestao_painel_slideshow=null,
	$plano_gestao_projeto_viabilidade=null,
	$plano_gestao_projeto_abertura=null,
	$plano_gestao_plano_gestao=null,
	$plano_gestao_ssti=null,
	$plano_gestao_laudo=null,
	$plano_gestao_trelo=null,
	$plano_gestao_trelo_cartao=null,
	$plano_gestao_pdcl=null,
	$plano_gestao_pdcl_item=null
	)
	{
	if (
		$plano_gestao_projeto || 
		$plano_gestao_tarefa || 
		$plano_gestao_perspectiva || 
		$plano_gestao_tema || 
		$plano_gestao_objetivo || 
		$plano_gestao_fator || 
		$plano_gestao_estrategia || 
		$plano_gestao_meta || 
		$plano_gestao_pratica || 
		$plano_gestao_acao || 
		$plano_gestao_canvas || 
		$plano_gestao_risco || 
		$plano_gestao_risco_resposta || 
		$plano_gestao_indicador || 
		$plano_gestao_calendario || 
		$plano_gestao_monitoramento || 
		$plano_gestao_ata || 
		$plano_gestao_mswot || 
		$plano_gestao_swot || 
		$plano_gestao_operativo || 
		$plano_gestao_instrumento || 
		$plano_gestao_recurso || 
		$plano_gestao_problema || 
		$plano_gestao_demanda || 
		$plano_gestao_programa || 
		$plano_gestao_licao || 
		$plano_gestao_evento || 
		$plano_gestao_link || 
		$plano_gestao_avaliacao || 
		$plano_gestao_tgn || 
		$plano_gestao_brainstorm || 
		$plano_gestao_gut || 
		$plano_gestao_causa_efeito || 
		$plano_gestao_arquivo || 
		$plano_gestao_forum || 
		$plano_gestao_checklist || 
		$plano_gestao_agenda || 
		$plano_gestao_agrupamento || 
		$plano_gestao_patrocinador || 
		$plano_gestao_template || 
		$plano_gestao_painel || 
		$plano_gestao_painel_odometro || 
		$plano_gestao_painel_composicao || 
		$plano_gestao_tr || 
		$plano_gestao_me || 
		$plano_gestao_acao_item || 
		$plano_gestao_beneficio || 
		$plano_gestao_painel_slideshow || 
		$plano_gestao_projeto_viabilidade || 
		$plano_gestao_projeto_abertura || 
		$plano_gestao_plano_gestao|| 
		$plano_gestao_ssti || 
		$plano_gestao_laudo || 
		$plano_gestao_trelo || 
		$plano_gestao_trelo_cartao || 
		$plano_gestao_pdcl || 
		$plano_gestao_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('plano_gestao_gestao');
			if ($uuid) $sql->adOnde('plano_gestao_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('plano_gestao_gestao_plano_gestao ='.(int)$pg_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('plano_gestao_gestao');
		$sql->adCampo('count(plano_gestao_gestao_id)');
		if ($uuid) $sql->adOnde('plano_gestao_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('plano_gestao_gestao_plano_gestao ='.(int)$pg_id);	
		if ($plano_gestao_tarefa) $sql->adOnde('plano_gestao_gestao_tarefa='.(int)$plano_gestao_tarefa);
		elseif ($plano_gestao_projeto) $sql->adOnde('plano_gestao_gestao_projeto='.(int)$plano_gestao_projeto);
		elseif ($plano_gestao_perspectiva) $sql->adOnde('plano_gestao_gestao_perspectiva='.(int)$plano_gestao_perspectiva);
		elseif ($plano_gestao_tema) $sql->adOnde('plano_gestao_gestao_tema='.(int)$plano_gestao_tema);
		elseif ($plano_gestao_objetivo) $sql->adOnde('plano_gestao_gestao_objetivo='.(int)$plano_gestao_objetivo);
		elseif ($plano_gestao_fator) $sql->adOnde('plano_gestao_gestao_fator='.(int)$plano_gestao_fator);
		elseif ($plano_gestao_estrategia) $sql->adOnde('plano_gestao_gestao_estrategia='.(int)$plano_gestao_estrategia);
		elseif ($plano_gestao_acao) $sql->adOnde('plano_gestao_gestao_acao='.(int)$plano_gestao_acao);
		elseif ($plano_gestao_pratica) $sql->adOnde('plano_gestao_gestao_pratica='.(int)$plano_gestao_pratica);
		elseif ($plano_gestao_meta) $sql->adOnde('plano_gestao_gestao_meta='.(int)$plano_gestao_meta);
		elseif ($plano_gestao_canvas) $sql->adOnde('plano_gestao_gestao_canvas='.(int)$plano_gestao_canvas);
		elseif ($plano_gestao_risco) $sql->adOnde('plano_gestao_gestao_risco='.(int)$plano_gestao_risco);
		elseif ($plano_gestao_risco_resposta) $sql->adOnde('plano_gestao_gestao_risco_resposta='.(int)$plano_gestao_risco_resposta);
		elseif ($plano_gestao_indicador) $sql->adOnde('plano_gestao_gestao_indicador='.(int)$plano_gestao_indicador);
		elseif ($plano_gestao_calendario) $sql->adOnde('plano_gestao_gestao_calendario='.(int)$plano_gestao_calendario);
		elseif ($plano_gestao_monitoramento) $sql->adOnde('plano_gestao_gestao_monitoramento='.(int)$plano_gestao_monitoramento);
		elseif ($plano_gestao_ata) $sql->adOnde('plano_gestao_gestao_ata='.(int)$plano_gestao_ata);
		elseif ($plano_gestao_mswot) $sql->adOnde('plano_gestao_gestao_mswot='.(int)$plano_gestao_mswot);
		elseif ($plano_gestao_swot) $sql->adOnde('plano_gestao_gestao_swot='.(int)$plano_gestao_swot);
		elseif ($plano_gestao_operativo) $sql->adOnde('plano_gestao_gestao_operativo='.(int)$plano_gestao_operativo);
		elseif ($plano_gestao_instrumento) $sql->adOnde('plano_gestao_gestao_instrumento='.(int)$plano_gestao_instrumento);
		elseif ($plano_gestao_recurso) $sql->adOnde('plano_gestao_gestao_recurso='.(int)$plano_gestao_recurso);
		elseif ($plano_gestao_problema) $sql->adOnde('plano_gestao_gestao_problema='.(int)$plano_gestao_problema);
		elseif ($plano_gestao_demanda) $sql->adOnde('plano_gestao_gestao_demanda='.(int)$plano_gestao_demanda);
		elseif ($plano_gestao_programa) $sql->adOnde('plano_gestao_gestao_programa='.(int)$plano_gestao_programa);
		elseif ($plano_gestao_licao) $sql->adOnde('plano_gestao_gestao_licao='.(int)$plano_gestao_licao);
		elseif ($plano_gestao_evento) $sql->adOnde('plano_gestao_gestao_evento='.(int)$plano_gestao_evento);
		elseif ($plano_gestao_link) $sql->adOnde('plano_gestao_gestao_link='.(int)$plano_gestao_link);
		elseif ($plano_gestao_avaliacao) $sql->adOnde('plano_gestao_gestao_avaliacao='.(int)$plano_gestao_avaliacao);
		elseif ($plano_gestao_tgn) $sql->adOnde('plano_gestao_gestao_tgn='.(int)$plano_gestao_tgn);
		elseif ($plano_gestao_brainstorm) $sql->adOnde('plano_gestao_gestao_brainstorm='.(int)$plano_gestao_brainstorm);
		elseif ($plano_gestao_gut) $sql->adOnde('plano_gestao_gestao_gut='.(int)$plano_gestao_gut);
		elseif ($plano_gestao_causa_efeito) $sql->adOnde('plano_gestao_gestao_causa_efeito='.(int)$plano_gestao_causa_efeito);
		elseif ($plano_gestao_arquivo) $sql->adOnde('plano_gestao_gestao_arquivo='.(int)$plano_gestao_arquivo);
		elseif ($plano_gestao_forum) $sql->adOnde('plano_gestao_gestao_forum='.(int)$plano_gestao_forum);
		elseif ($plano_gestao_checklist) $sql->adOnde('plano_gestao_gestao_checklist='.(int)$plano_gestao_checklist);
		elseif ($plano_gestao_agenda) $sql->adOnde('plano_gestao_gestao_agenda='.(int)$plano_gestao_agenda);
		elseif ($plano_gestao_agrupamento) $sql->adOnde('plano_gestao_gestao_agrupamento='.(int)$plano_gestao_agrupamento);
		elseif ($plano_gestao_patrocinador) $sql->adOnde('plano_gestao_gestao_patrocinador='.(int)$plano_gestao_patrocinador);
		elseif ($plano_gestao_template) $sql->adOnde('plano_gestao_gestao_template='.(int)$plano_gestao_template);
		elseif ($plano_gestao_painel) $sql->adOnde('plano_gestao_gestao_painel='.(int)$plano_gestao_painel);
		elseif ($plano_gestao_painel_odometro) $sql->adOnde('plano_gestao_gestao_painel_odometro='.(int)$plano_gestao_painel_odometro);
		elseif ($plano_gestao_painel_composicao) $sql->adOnde('plano_gestao_gestao_painel_composicao='.(int)$plano_gestao_painel_composicao);
		elseif ($plano_gestao_tr) $sql->adOnde('plano_gestao_gestao_tr='.(int)$plano_gestao_tr);
		elseif ($plano_gestao_me) $sql->adOnde('plano_gestao_gestao_me='.(int)$plano_gestao_me);
		elseif ($plano_gestao_acao_item) $sql->adOnde('plano_gestao_gestao_acao_item='.(int)$plano_gestao_acao_item);
		elseif ($plano_gestao_beneficio) $sql->adOnde('plano_gestao_gestao_beneficio='.(int)$plano_gestao_beneficio);
		elseif ($plano_gestao_painel_slideshow) $sql->adOnde('plano_gestao_gestao_painel_slideshow='.(int)$plano_gestao_painel_slideshow);
		elseif ($plano_gestao_projeto_viabilidade) $sql->adOnde('plano_gestao_gestao_projeto_viabilidade='.(int)$plano_gestao_projeto_viabilidade);
		elseif ($plano_gestao_projeto_abertura) $sql->adOnde('plano_gestao_gestao_projeto_abertura='.(int)$plano_gestao_projeto_abertura);
		
		elseif ($plano_gestao_plano_gestao) $sql->adOnde('plano_gestao_gestao_semelhante='.(int)$plano_gestao_plano_gestao);

		elseif ($plano_gestao_ssti) $sql->adOnde('plano_gestao_gestao_ssti='.(int)$plano_gestao_ssti);
		elseif ($plano_gestao_laudo) $sql->adOnde('plano_gestao_gestao_laudo='.(int)$plano_gestao_laudo);
		elseif ($plano_gestao_trelo) $sql->adOnde('plano_gestao_gestao_trelo='.(int)$plano_gestao_trelo);
		elseif ($plano_gestao_trelo_cartao) $sql->adOnde('plano_gestao_gestao_trelo_cartao='.(int)$plano_gestao_trelo_cartao);
		elseif ($plano_gestao_pdcl) $sql->adOnde('plano_gestao_gestao_pdcl='.(int)$plano_gestao_pdcl);
		elseif ($plano_gestao_pdcl_item) $sql->adOnde('plano_gestao_gestao_pdcl_item='.(int)$plano_gestao_pdcl_item);
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('plano_gestao_gestao');
			$sql->adCampo('MAX(plano_gestao_gestao_ordem)');
			if ($uuid) $sql->adOnde('plano_gestao_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('plano_gestao_gestao_plano_gestao ='.(int)$pg_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('plano_gestao_gestao');
			if ($uuid) $sql->adInserir('plano_gestao_gestao_uuid', $uuid);
			else $sql->adInserir('plano_gestao_gestao_plano_gestao', (int)$pg_id);
			
			if ($plano_gestao_tarefa) $sql->adInserir('plano_gestao_gestao_tarefa', (int)$plano_gestao_tarefa);
			if ($plano_gestao_projeto) $sql->adInserir('plano_gestao_gestao_projeto', (int)$plano_gestao_projeto);
			elseif ($plano_gestao_perspectiva) $sql->adInserir('plano_gestao_gestao_perspectiva', (int)$plano_gestao_perspectiva);
			elseif ($plano_gestao_tema) $sql->adInserir('plano_gestao_gestao_tema', (int)$plano_gestao_tema);
			elseif ($plano_gestao_objetivo) $sql->adInserir('plano_gestao_gestao_objetivo', (int)$plano_gestao_objetivo);
			elseif ($plano_gestao_fator) $sql->adInserir('plano_gestao_gestao_fator', (int)$plano_gestao_fator);
			elseif ($plano_gestao_estrategia) $sql->adInserir('plano_gestao_gestao_estrategia', (int)$plano_gestao_estrategia);
			elseif ($plano_gestao_acao) $sql->adInserir('plano_gestao_gestao_acao', (int)$plano_gestao_acao);
			elseif ($plano_gestao_pratica) $sql->adInserir('plano_gestao_gestao_pratica', (int)$plano_gestao_pratica);
			elseif ($plano_gestao_meta) $sql->adInserir('plano_gestao_gestao_meta', (int)$plano_gestao_meta);
			elseif ($plano_gestao_canvas) $sql->adInserir('plano_gestao_gestao_canvas', (int)$plano_gestao_canvas);
			elseif ($plano_gestao_risco) $sql->adInserir('plano_gestao_gestao_risco', (int)$plano_gestao_risco);
			elseif ($plano_gestao_risco_resposta) $sql->adInserir('plano_gestao_gestao_risco_resposta', (int)$plano_gestao_risco_resposta);
			elseif ($plano_gestao_indicador) $sql->adInserir('plano_gestao_gestao_indicador', (int)$plano_gestao_indicador);
			elseif ($plano_gestao_calendario) $sql->adInserir('plano_gestao_gestao_calendario', (int)$plano_gestao_calendario);
			elseif ($plano_gestao_monitoramento) $sql->adInserir('plano_gestao_gestao_monitoramento', (int)$plano_gestao_monitoramento);
			elseif ($plano_gestao_ata) $sql->adInserir('plano_gestao_gestao_ata', (int)$plano_gestao_ata);
			elseif ($plano_gestao_mswot) $sql->adInserir('plano_gestao_gestao_mswot', (int)$plano_gestao_mswot);
			elseif ($plano_gestao_swot) $sql->adInserir('plano_gestao_gestao_swot', (int)$plano_gestao_swot);
			elseif ($plano_gestao_operativo) $sql->adInserir('plano_gestao_gestao_operativo', (int)$plano_gestao_operativo);
			elseif ($plano_gestao_instrumento) $sql->adInserir('plano_gestao_gestao_instrumento', (int)$plano_gestao_instrumento);
			elseif ($plano_gestao_recurso) $sql->adInserir('plano_gestao_gestao_recurso', (int)$plano_gestao_recurso);
			elseif ($plano_gestao_problema) $sql->adInserir('plano_gestao_gestao_problema', (int)$plano_gestao_problema);
			elseif ($plano_gestao_demanda) $sql->adInserir('plano_gestao_gestao_demanda', (int)$plano_gestao_demanda);
			elseif ($plano_gestao_programa) $sql->adInserir('plano_gestao_gestao_programa', (int)$plano_gestao_programa);
			elseif ($plano_gestao_licao) $sql->adInserir('plano_gestao_gestao_licao', (int)$plano_gestao_licao);
			elseif ($plano_gestao_evento) $sql->adInserir('plano_gestao_gestao_evento', (int)$plano_gestao_evento);
			elseif ($plano_gestao_link) $sql->adInserir('plano_gestao_gestao_link', (int)$plano_gestao_link);
			elseif ($plano_gestao_avaliacao) $sql->adInserir('plano_gestao_gestao_avaliacao', (int)$plano_gestao_avaliacao);
			elseif ($plano_gestao_tgn) $sql->adInserir('plano_gestao_gestao_tgn', (int)$plano_gestao_tgn);
			elseif ($plano_gestao_brainstorm) $sql->adInserir('plano_gestao_gestao_brainstorm', (int)$plano_gestao_brainstorm);
			elseif ($plano_gestao_gut) $sql->adInserir('plano_gestao_gestao_gut', (int)$plano_gestao_gut);
			elseif ($plano_gestao_causa_efeito) $sql->adInserir('plano_gestao_gestao_causa_efeito', (int)$plano_gestao_causa_efeito);
			elseif ($plano_gestao_arquivo) $sql->adInserir('plano_gestao_gestao_arquivo', (int)$plano_gestao_arquivo);
			elseif ($plano_gestao_forum) $sql->adInserir('plano_gestao_gestao_forum', (int)$plano_gestao_forum);
			elseif ($plano_gestao_checklist) $sql->adInserir('plano_gestao_gestao_checklist', (int)$plano_gestao_checklist);
			elseif ($plano_gestao_agenda) $sql->adInserir('plano_gestao_gestao_agenda', (int)$plano_gestao_agenda);
			elseif ($plano_gestao_agrupamento) $sql->adInserir('plano_gestao_gestao_agrupamento', (int)$plano_gestao_agrupamento);
			elseif ($plano_gestao_patrocinador) $sql->adInserir('plano_gestao_gestao_patrocinador', (int)$plano_gestao_patrocinador);
			elseif ($plano_gestao_template) $sql->adInserir('plano_gestao_gestao_template', (int)$plano_gestao_template);
			elseif ($plano_gestao_painel) $sql->adInserir('plano_gestao_gestao_painel', (int)$plano_gestao_painel);
			elseif ($plano_gestao_painel_odometro) $sql->adInserir('plano_gestao_gestao_painel_odometro', (int)$plano_gestao_painel_odometro);
			elseif ($plano_gestao_painel_composicao) $sql->adInserir('plano_gestao_gestao_painel_composicao', (int)$plano_gestao_painel_composicao);
			elseif ($plano_gestao_tr) $sql->adInserir('plano_gestao_gestao_tr', (int)$plano_gestao_tr);
			elseif ($plano_gestao_me) $sql->adInserir('plano_gestao_gestao_me', (int)$plano_gestao_me);
			elseif ($plano_gestao_acao_item) $sql->adInserir('plano_gestao_gestao_acao_item', (int)$plano_gestao_acao_item);
			elseif ($plano_gestao_beneficio) $sql->adInserir('plano_gestao_gestao_beneficio', (int)$plano_gestao_beneficio);
			elseif ($plano_gestao_painel_slideshow) $sql->adInserir('plano_gestao_gestao_painel_slideshow', (int)$plano_gestao_painel_slideshow);
			elseif ($plano_gestao_projeto_viabilidade) $sql->adInserir('plano_gestao_gestao_projeto_viabilidade', (int)$plano_gestao_projeto_viabilidade);
			elseif ($plano_gestao_projeto_abertura) $sql->adInserir('plano_gestao_gestao_projeto_abertura', (int)$plano_gestao_projeto_abertura);
			
			elseif ($plano_gestao_plano_gestao) $sql->adInserir('plano_gestao_gestao_semelhante', (int)$plano_gestao_plano_gestao);
			elseif ($plano_gestao_ssti) $sql->adInserir('plano_gestao_gestao_ssti', (int)$plano_gestao_ssti);
			elseif ($plano_gestao_laudo) $sql->adInserir('plano_gestao_gestao_laudo', (int)$plano_gestao_laudo);
			elseif ($plano_gestao_trelo) $sql->adInserir('plano_gestao_gestao_trelo', (int)$plano_gestao_trelo);
			elseif ($plano_gestao_trelo_cartao) $sql->adInserir('plano_gestao_gestao_trelo_cartao', (int)$plano_gestao_trelo_cartao);
			elseif ($plano_gestao_pdcl) $sql->adInserir('plano_gestao_gestao_pdcl', (int)$plano_gestao_pdcl);
			elseif ($plano_gestao_pdcl_item) $sql->adInserir('plano_gestao_gestao_pdcl_item', (int)$plano_gestao_pdcl_item);
			$sql->adInserir('plano_gestao_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($pg_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($pg_id=0, $uuid='', $plano_gestao_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('plano_gestao_gestao');
	$sql->adOnde('plano_gestao_gestao_id='.(int)$plano_gestao_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($pg_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($pg_id=0, $uuid=''){	
	$saida=atualizar_gestao($pg_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($pg_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_gestao');
	$sql->adCampo('plano_gestao_gestao.*');
	if ($uuid) $sql->adOnde('plano_gestao_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('plano_gestao_gestao_plano_gestao ='.(int)$pg_id);	
	$sql->adOrdem('plano_gestao_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_gestao_gestao_ordem'].', '.$gestao_data['plano_gestao_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_gestao_gestao_ordem'].', '.$gestao_data['plano_gestao_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_gestao_gestao_ordem'].', '.$gestao_data['plano_gestao_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_gestao_gestao_ordem'].', '.$gestao_data['plano_gestao_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['plano_gestao_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['plano_gestao_gestao_tarefa']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['plano_gestao_gestao_projeto']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['plano_gestao_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['plano_gestao_gestao_tema']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['plano_gestao_gestao_objetivo']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['plano_gestao_gestao_fator']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['plano_gestao_gestao_estrategia']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['plano_gestao_gestao_meta']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['plano_gestao_gestao_pratica']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['plano_gestao_gestao_acao']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['plano_gestao_gestao_canvas']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['plano_gestao_gestao_risco']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['plano_gestao_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['plano_gestao_gestao_indicador']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['plano_gestao_gestao_calendario']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['plano_gestao_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['plano_gestao_gestao_ata']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['plano_gestao_gestao_mswot']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['plano_gestao_gestao_swot']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['plano_gestao_gestao_operativo']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['plano_gestao_gestao_instrumento']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['plano_gestao_gestao_recurso']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['plano_gestao_gestao_problema']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['plano_gestao_gestao_demanda']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['plano_gestao_gestao_programa']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['plano_gestao_gestao_licao']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['plano_gestao_gestao_evento']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['plano_gestao_gestao_link']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['plano_gestao_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['plano_gestao_gestao_tgn']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['plano_gestao_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['plano_gestao_gestao_gut']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['plano_gestao_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['plano_gestao_gestao_arquivo']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['plano_gestao_gestao_forum']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['plano_gestao_gestao_checklist']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['plano_gestao_gestao_agenda']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['plano_gestao_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['plano_gestao_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['plano_gestao_gestao_template']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['plano_gestao_gestao_painel']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['plano_gestao_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['plano_gestao_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['plano_gestao_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['plano_gestao_gestao_tr']).'</td>';	
		elseif ($gestao_data['plano_gestao_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['plano_gestao_gestao_me']).'</td>';	
		elseif ($gestao_data['plano_gestao_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['plano_gestao_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['plano_gestao_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['plano_gestao_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['plano_gestao_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['plano_gestao_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['plano_gestao_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['plano_gestao_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['plano_gestao_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['plano_gestao_gestao_projeto_abertura']).'</td>';	
		
		elseif ($gestao_data['plano_gestao_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['plano_gestao_gestao_semelhante']).'</td>';	
		
		elseif ($gestao_data['plano_gestao_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['plano_gestao_gestao_ssti']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['plano_gestao_gestao_laudo']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['plano_gestao_gestao_trelo']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['plano_gestao_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['plano_gestao_gestao_pdcl']).'</td>';
		elseif ($gestao_data['plano_gestao_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['plano_gestao_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['plano_gestao_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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




$sql = new BDConsulta;
$sql->adTabela('assinatura_atesta');
$sql->adCampo('assinatura_atesta_id, assinatura_atesta_nome');
$sql->adOnde('assinatura_atesta_plano_gestao=1');
$sql->adOrdem('assinatura_atesta_ordem');
$atesta_vetor = array(null=>'')+$sql->listaVetorChave('assinatura_atesta_id', 'assinatura_atesta_nome');
$sql->limpar();


function mudar_posicao_assinatura($ordem, $assinatura_id, $direcao, $pg_id=0, $assinatura_uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$assinatura_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('assinatura');
		$sql->adOnde('assinatura_id != '.$assinatura_id);
		if ($assinatura_uuid) $sql->adOnde('assinatura_uuid = \''.$assinatura_uuid.'\'');
		else $sql->adOnde('assinatura_plano_gestao = '.$pg_id);
		$sql->adOrdem('assinatura_ordem');
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
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_ordem', $novo_ui_ordem);
			$sql->adOnde('assinatura_id = '.$assinatura_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('assinatura');
					$sql->adAtualizar('assinatura_ordem', $idx);
					$sql->adOnde('assinatura_id = '.$acao['assinatura_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('assinatura');
					$sql->adAtualizar('assinatura_ordem', $idx + 1);
					$sql->adOnde('assinatura_id = '.$acao['assinatura_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_assinaturas($pg_id, $assinatura_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("assinaturas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_assinatura");	

function incluir_assinatura($pg_id=null, $assinatura_uuid=null, $assinatura_id=null, $assinatura_usuario=null, $assinatura_funcao=null, $assinatura_atesta=null, $assinatura_aprova=null){
	$sql = new BDConsulta;
	$assinatura_funcao=previnirXSS(utf8_decode($assinatura_funcao));
	$assinatura_atesta=previnirXSS(utf8_decode($assinatura_atesta));
	$assinatura_aprova=previnirXSS(utf8_decode($assinatura_aprova));
		
	if ($assinatura_id){
		$sql->adTabela('assinatura');
		$sql->adAtualizar('assinatura_usuario', $assinatura_usuario);	
		$sql->adAtualizar('assinatura_funcao', $assinatura_funcao);	
		$sql->adAtualizar('assinatura_atesta', $assinatura_atesta);
		$sql->adAtualizar('assinatura_aprova', $assinatura_aprova);
		$sql->adOnde('assinatura_id ='.(int)$assinatura_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {	
		$sql->adTabela('assinatura');
		$sql->adCampo('count(assinatura_id) AS soma');
		if ($assinatura_uuid) $sql->adOnde('assinatura_uuid = \''.$assinatura_uuid.'\'');
		else $sql->adOnde('assinatura_plano_gestao ='.$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
	  
		$sql->adTabela('assinatura');
		if ($assinatura_uuid) $sql->adInserir('assinatura_uuid', $assinatura_uuid);
		else $sql->adInserir('assinatura_plano_gestao', $pg_id);
		$sql->adInserir('assinatura_ordem', $soma_total);
		$sql->adInserir('assinatura_funcao', $assinatura_funcao);
		$sql->adInserir('assinatura_atesta', $assinatura_atesta);
		$sql->adInserir('assinatura_aprova', $assinatura_aprova);
		$sql->adInserir('assinatura_usuario', $assinatura_usuario);
		$sql->exec();
		}
		
	verifica_aprovacao($pg_id, $assinatura_uuid);	
		
	$saida=atualizar_assinaturas($pg_id, $assinatura_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("assinaturas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_assinatura");

function excluir_assinatura($assinatura_id, $pg_id, $assinatura_uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('assinatura');
	$sql->adOnde('assinatura_id='.$assinatura_id);
	$sql->exec();
	
	verifica_aprovacao($pg_id, $assinatura_uuid);	
	
	$saida=atualizar_assinaturas($pg_id, $assinatura_uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("assinaturas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_assinatura");	

function atualizar_assinaturas($pg_id=0, $assinatura_uuid=''){
	global $config, $atesta_vetor;
	$sql = new BDConsulta;
	$sql->adTabela('assinatura');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = assinatura_usuario');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	if ($assinatura_uuid) $sql->adOnde('assinatura_uuid = \''.$assinatura_uuid.'\'');
	else $sql->adOnde('assinatura_plano_gestao = '.(int)$pg_id);
	$sql->adCampo('assinatura_id, assinatura_funcao, assinatura_atesta, assinatura_aprova, assinatura_usuario, assinatura_ordem, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_contato');
	$sql->adOrdem('assinatura_ordem');
	$assinaturas=$sql->Lista();
	$sql->limpar();
	$saida='';
	if (is_array($assinaturas) && count($assinaturas)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.dica(ucfirst($config['usuario']), 'Nome d'.$config['genero_usuario'].' '.$config['usuario'].' que assina.').ucfirst($config['usuario']).dicaF().'</th><th>'.dica('Função', 'Função d'.$config['genero_usuario'].' '.$config['usuario'].' que assina.').'Função'.dicaF().'</th><th>'.dica('Tipo de Parecer', 'Tipo de parecer d'.$config['genero_usuario'].' '.$config['usuario'].' que assina.').'Tipo de Parecer'.dicaF().'</th><th>'.dica('Aprova', 'Caso o parecer d'.$config['genero_usuario'].' '.$config['usuario'].' que assina é necessário para a aprovação.').'Aprova'.dicaF().'</th><th></th></tr>';
		foreach ($assinaturas as $assinatura) {
			$saida.= '<tr align="center">';
			$saida.= '<td style="white-space: nowrap" width="40" align="center">';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_assinatura('.$assinatura['assinatura_ordem'].', '.$assinatura['assinatura_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_assinatura('.$assinatura['assinatura_ordem'].', '.$assinatura['assinatura_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_assinatura('.$assinatura['assinatura_ordem'].', '.$assinatura['assinatura_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_assinatura('.$assinatura['assinatura_ordem'].', '.$assinatura['assinatura_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left" style="white-space: nowrap">'.$assinatura['nome_contato'].'</td>';
			$saida.= '<td align="left">'.$assinatura['assinatura_funcao'].'</td>';
			$saida.= '<td align="left">'.(isset($atesta_vetor[$assinatura['assinatura_atesta']]) ? $atesta_vetor[$assinatura['assinatura_atesta']] : '&nbsp;').'</td>';
			$saida.= '<td align="center">'.($assinatura['assinatura_aprova'] > 0 ? 'Sim' : 'Não').'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_assinatura('.$assinatura['assinatura_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_usuario'].' '.$config['usuario'].'.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_assinatura('.$assinatura['assinatura_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir '.$config['genero_usuario'].' '.$config['usuario'].'.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}
	
	
function editar_assinatura($assinatura_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('assinatura');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = assinatura_usuario');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, cia_nome, contato_funcao, assinatura_funcao, assinatura_usuario, assinatura_atesta, assinatura_aprova');
	$sql->adOnde('assinatura_id = '.(int)$assinatura_id);
	$linha=$sql->Linha();
	$sql->limpar();

	$nome=$linha['nome'].($linha['contato_funcao'] ? ' - '.$linha['contato_funcao'] : '').($linha['cia_nome'] && $Aplic->getPref('om_usuario') ? ' - '.$linha['cia_nome'] : '');
	
	$objResposta->assign("assinatura_id","value", $assinatura_id);
	$objResposta->assign("nome_assinatura","value", utf8_encode($nome));
	$objResposta->assign("integrante_id","value", $linha['assinatura_usuario']);	
	$objResposta->assign("assinatura_funcao","value", utf8_encode($linha['assinatura_funcao']));	
	$objResposta->assign("assinatura_atesta","value", utf8_encode($linha['assinatura_atesta']));	
	$objResposta->assign("assinatura_aprova","value", utf8_encode($linha['assinatura_aprova']));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_assinatura");	

function verifica_aprovacao($pg_id=null, $assinatura_uuid=null){
	$sql = new BDConsulta;
	//checar se PG está aprovado
	$sql->adTabela('assinatura');
	$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
	$sql->adCampo('count(assinatura_id)');
	if ($assinatura_uuid) $sql->adOnde('assinatura_uuid = \''.$assinatura_uuid.'\'');
	else $sql->adOnde('assinatura_plano_gestao = '.(int)$pg_id);
	$sql->adOnde('assinatura_atesta_opcao_aprova !=1 OR assinatura_atesta_opcao_aprova IS NULL');
	$sql->adOnde('assinatura_aprova=1');
	$nao_aprovado = $sql->resultado();
	$sql->limpar();
	$sql->adTabela('plano_gestao');
	$sql->adAtualizar('pg_aprovado', ($nao_aprovado ? 0 : 1));
	$sql->adOnde('pg_id = '.(int)$pg_id);
	$sql->exec();
	$sql->limpar();
	}



function mudar_priorizacao($pg_id=null, $priorizacao_modelo_id=null, $valor=null, $uuid=null){
	//verificar se já existe um valor
	$sql = new BDConsulta;

	$sql->adTabela('priorizacao');
	if ($pg_id) $sql->adOnde('priorizacao_plano_gestao = '.(int)$pg_id);
	else $sql->adOnde('priorizacao_uuid = \''.$uuid.'\'');
	$sql->adOnde('priorizacao_modelo = '.(int)$priorizacao_modelo_id);
	$sql->adCampo('count(priorizacao_acao)');
	$existe = $sql->Resultado();
	$sql->limpar();
	if ($existe){
		$sql->adTabela('priorizacao');
		$sql->adAtualizar('priorizacao_valor', ($valor!='' && $valor!=null ? $valor : null));
		if ($pg_id) $sql->adOnde('priorizacao_plano_gestao = '.(int)$pg_id);
		else $sql->adOnde('priorizacao_uuid = \''.$uuid.'\'');
		$sql->adOnde('priorizacao_modelo = '.(int)$priorizacao_modelo_id);
		$sql->exec();
		$sql->limpar();
		}
	else {
		$sql->adTabela('priorizacao');
		$sql->adInserir('priorizacao_valor', ($valor!='' && $valor!=null ? $valor : null));
		if ($pg_id) $sql->adInserir('priorizacao_plano_gestao', (int)$pg_id);
		else $sql->adInserir('priorizacao_uuid', $uuid);
		$sql->adInserir('priorizacao_modelo', (int)$priorizacao_modelo_id);
		$sql->exec();
		$sql->limpar();
		}
	}
$xajax->registerFunction("mudar_priorizacao");

$xajax->processRequest();

?>