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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/plano_acao_editar_ajax_pro.php';
require_once BASE_DIR.'/modulos/tarefas/funcoes.php';
require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';



function mudar_posicao_gestao($ordem, $plano_acao_gestao_id, $direcao, $plano_acao_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $plano_acao_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_acao_gestao');
		$sql->adOnde('plano_acao_gestao_id != '.(int)$plano_acao_gestao_id);
		if ($uuid) $sql->adOnde('plano_acao_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('plano_acao_gestao_acao = '.(int)$plano_acao_id);
		$sql->adOrdem('plano_acao_gestao_ordem');
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
			$sql->adTabela('plano_acao_gestao');
			$sql->adAtualizar('plano_acao_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('plano_acao_gestao_id = '.(int)$plano_acao_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_acao_gestao');
					$sql->adAtualizar('plano_acao_gestao_ordem', $idx);
					$sql->adOnde('plano_acao_gestao_id = '.(int)$acao['plano_acao_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_acao_gestao');
					$sql->adAtualizar('plano_acao_gestao_ordem', $idx + 1);
					$sql->adOnde('plano_acao_gestao_id = '.(int)$acao['plano_acao_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($plano_acao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$plano_acao_id=0, 
	$uuid='',  
	
	$plano_acao_projeto=null,
	$plano_acao_tarefa=null,
	$plano_acao_perspectiva=null,
	$plano_acao_tema=null,
	$plano_acao_objetivo=null,
	$plano_acao_fator=null,
	$plano_acao_estrategia=null,
	$plano_acao_meta=null,
	$plano_acao_pratica=null,
	$plano_acao_acao=null,
	$plano_acao_canvas=null,
	$plano_acao_risco=null,
	$plano_acao_risco_resposta=null,
	$plano_acao_indicador=null,
	$plano_acao_calendario=null,
	$plano_acao_monitoramento=null,
	$plano_acao_ata=null,
	$plano_acao_mswot=null,
	$plano_acao_swot=null,
	$plano_acao_operativo=null,
	$plano_acao_instrumento=null,
	$plano_acao_recurso=null,
	$plano_acao_problema=null,
	$plano_acao_demanda=null,
	$plano_acao_programa=null,
	$plano_acao_licao=null,
	$plano_acao_evento=null,
	$plano_acao_link=null,
	$plano_acao_avaliacao=null,
	$plano_acao_tgn=null,
	$plano_acao_brainstorm=null,
	$plano_acao_gut=null,
	$plano_acao_causa_efeito=null,
	$plano_acao_arquivo=null,
	$plano_acao_forum=null,
	$plano_acao_checklist=null,
	$plano_acao_agenda=null,
	$plano_acao_agrupamento=null,
	$plano_acao_patrocinador=null,
	$plano_acao_template=null,
	$plano_acao_painel=null,
	$plano_acao_painel_odometro=null,
	$plano_acao_painel_composicao=null,
	$plano_acao_tr=null,
	$plano_acao_me=null,
	$plano_acao_acao_item=null,
	$plano_acao_beneficio=null,
	$plano_acao_painel_slideshow=null,
	$plano_acao_projeto_viabilidade=null,
	$plano_acao_projeto_abertura=null,
	$plano_acao_plano_gestao=null,
	$acao_ssti=null,
	$acao_laudo=null,
	$acao_trelo=null,
	$acao_trelo_cartao=null,
	$acao_pdcl=null,
	$acao_pdcl_item=null
	)
	{
	if (
		$plano_acao_projeto || 
		$plano_acao_tarefa || 
		$plano_acao_perspectiva || 
		$plano_acao_tema || 
		$plano_acao_objetivo || 
		$plano_acao_fator || 
		$plano_acao_estrategia || 
		$plano_acao_meta || 
		$plano_acao_pratica || 
		$plano_acao_acao || 
		$plano_acao_canvas || 
		$plano_acao_risco || 
		$plano_acao_risco_resposta || 
		$plano_acao_indicador || 
		$plano_acao_calendario || 
		$plano_acao_monitoramento || 
		$plano_acao_ata || 
		$plano_acao_mswot || 
		$plano_acao_swot || 
		$plano_acao_operativo || 
		$plano_acao_instrumento || 
		$plano_acao_recurso || 
		$plano_acao_problema || 
		$plano_acao_demanda || 
		$plano_acao_programa || 
		$plano_acao_licao || 
		$plano_acao_evento || 
		$plano_acao_link || 
		$plano_acao_avaliacao || 
		$plano_acao_tgn || 
		$plano_acao_brainstorm || 
		$plano_acao_gut || 
		$plano_acao_causa_efeito || 
		$plano_acao_arquivo || 
		$plano_acao_forum || 
		$plano_acao_checklist || 
		$plano_acao_agenda || 
		$plano_acao_agrupamento || 
		$plano_acao_patrocinador || 
		$plano_acao_template || 
		$plano_acao_painel || 
		$plano_acao_painel_odometro || 
		$plano_acao_painel_composicao || 
		$plano_acao_tr || 
		$plano_acao_me || 
		$plano_acao_acao_item || 
		$plano_acao_beneficio || 
		$plano_acao_painel_slideshow || 
		$plano_acao_projeto_viabilidade || 
		$plano_acao_projeto_abertura || 
		$plano_acao_plano_gestao|| 
		$acao_ssti || 
		$acao_laudo || 
		$acao_trelo || 
		$acao_trelo_cartao || 
		$acao_pdcl || 
		$acao_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('plano_acao_gestao');
			if ($uuid) $sql->adOnde('plano_acao_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('plano_acao_gestao_acao ='.(int)$plano_acao_id);	
			$sql->exec();
			}
				
		//verificar se já não inseriu antes
		$sql->adTabela('plano_acao_gestao');
		$sql->adCampo('count(plano_acao_gestao_id)');
		if ($uuid) $sql->adOnde('plano_acao_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('plano_acao_gestao_acao ='.(int)$plano_acao_id);	
		if ($plano_acao_tarefa) $sql->adOnde('plano_acao_gestao_tarefa='.(int)$plano_acao_tarefa);
		elseif ($plano_acao_projeto) $sql->adOnde('plano_acao_gestao_projeto='.(int)$plano_acao_projeto);
		elseif ($plano_acao_perspectiva) $sql->adOnde('plano_acao_gestao_perspectiva='.(int)$plano_acao_perspectiva);
		elseif ($plano_acao_tema) $sql->adOnde('plano_acao_gestao_tema='.(int)$plano_acao_tema);
		elseif ($plano_acao_objetivo) $sql->adOnde('plano_acao_gestao_objetivo='.(int)$plano_acao_objetivo);
		elseif ($plano_acao_fator) $sql->adOnde('plano_acao_gestao_fator='.(int)$plano_acao_fator);
		elseif ($plano_acao_estrategia) $sql->adOnde('plano_acao_gestao_estrategia='.(int)$plano_acao_estrategia);
		
		elseif ($plano_acao_acao) $sql->adOnde('plano_acao_gestao_semelhante='.(int)$plano_acao_acao);
		
		elseif ($plano_acao_pratica) $sql->adOnde('plano_acao_gestao_pratica='.(int)$plano_acao_pratica);
		elseif ($plano_acao_meta) $sql->adOnde('plano_acao_gestao_meta='.(int)$plano_acao_meta);
		elseif ($plano_acao_canvas) $sql->adOnde('plano_acao_gestao_canvas='.(int)$plano_acao_canvas);
		elseif ($plano_acao_risco) $sql->adOnde('plano_acao_gestao_risco='.(int)$plano_acao_risco);
		elseif ($plano_acao_risco_resposta) $sql->adOnde('plano_acao_gestao_risco_resposta='.(int)$plano_acao_risco_resposta);
		elseif ($plano_acao_indicador) $sql->adOnde('plano_acao_gestao_indicador='.(int)$plano_acao_indicador);
		elseif ($plano_acao_calendario) $sql->adOnde('plano_acao_gestao_calendario='.(int)$plano_acao_calendario);
		elseif ($plano_acao_monitoramento) $sql->adOnde('plano_acao_gestao_monitoramento='.(int)$plano_acao_monitoramento);
		elseif ($plano_acao_ata) $sql->adOnde('plano_acao_gestao_ata='.(int)$plano_acao_ata);
		elseif ($plano_acao_mswot) $sql->adOnde('plano_acao_gestao_mswot='.(int)$plano_acao_mswot);
		elseif ($plano_acao_swot) $sql->adOnde('plano_acao_gestao_swot='.(int)$plano_acao_swot);
		elseif ($plano_acao_operativo) $sql->adOnde('plano_acao_gestao_operativo='.(int)$plano_acao_operativo);
		elseif ($plano_acao_instrumento) $sql->adOnde('plano_acao_gestao_instrumento='.(int)$plano_acao_instrumento);
		elseif ($plano_acao_recurso) $sql->adOnde('plano_acao_gestao_recurso='.(int)$plano_acao_recurso);
		elseif ($plano_acao_problema) $sql->adOnde('plano_acao_gestao_problema='.(int)$plano_acao_problema);
		elseif ($plano_acao_demanda) $sql->adOnde('plano_acao_gestao_demanda='.(int)$plano_acao_demanda);
		elseif ($plano_acao_programa) $sql->adOnde('plano_acao_gestao_programa='.(int)$plano_acao_programa);
		elseif ($plano_acao_licao) $sql->adOnde('plano_acao_gestao_licao='.(int)$plano_acao_licao);
		elseif ($plano_acao_evento) $sql->adOnde('plano_acao_gestao_evento='.(int)$plano_acao_evento);
		elseif ($plano_acao_link) $sql->adOnde('plano_acao_gestao_link='.(int)$plano_acao_link);
		elseif ($plano_acao_avaliacao) $sql->adOnde('plano_acao_gestao_avaliacao='.(int)$plano_acao_avaliacao);
		elseif ($plano_acao_tgn) $sql->adOnde('plano_acao_gestao_tgn='.(int)$plano_acao_tgn);
		elseif ($plano_acao_brainstorm) $sql->adOnde('plano_acao_gestao_brainstorm='.(int)$plano_acao_brainstorm);
		elseif ($plano_acao_gut) $sql->adOnde('plano_acao_gestao_gut='.(int)$plano_acao_gut);
		elseif ($plano_acao_causa_efeito) $sql->adOnde('plano_acao_gestao_causa_efeito='.(int)$plano_acao_causa_efeito);
		elseif ($plano_acao_arquivo) $sql->adOnde('plano_acao_gestao_arquivo='.(int)$plano_acao_arquivo);
		elseif ($plano_acao_forum) $sql->adOnde('plano_acao_gestao_forum='.(int)$plano_acao_forum);
		elseif ($plano_acao_checklist) $sql->adOnde('plano_acao_gestao_checklist='.(int)$plano_acao_checklist);
		elseif ($plano_acao_agenda) $sql->adOnde('plano_acao_gestao_agenda='.(int)$plano_acao_agenda);
		elseif ($plano_acao_agrupamento) $sql->adOnde('plano_acao_gestao_agrupamento='.(int)$plano_acao_agrupamento);
		elseif ($plano_acao_patrocinador) $sql->adOnde('plano_acao_gestao_patrocinador='.(int)$plano_acao_patrocinador);
		elseif ($plano_acao_template) $sql->adOnde('plano_acao_gestao_template='.(int)$plano_acao_template);
		elseif ($plano_acao_painel) $sql->adOnde('plano_acao_gestao_painel='.(int)$plano_acao_painel);
		elseif ($plano_acao_painel_odometro) $sql->adOnde('plano_acao_gestao_painel_odometro='.(int)$plano_acao_painel_odometro);
		elseif ($plano_acao_painel_composicao) $sql->adOnde('plano_acao_gestao_painel_composicao='.(int)$plano_acao_painel_composicao);
		elseif ($plano_acao_tr) $sql->adOnde('plano_acao_gestao_tr='.(int)$plano_acao_tr);
		elseif ($plano_acao_me) $sql->adOnde('plano_acao_gestao_me='.(int)$plano_acao_me);
		elseif ($plano_acao_acao_item) $sql->adOnde('plano_acao_gestao_acao_item='.(int)$plano_acao_acao_item);
		elseif ($plano_acao_beneficio) $sql->adOnde('plano_acao_gestao_beneficio='.(int)$plano_acao_beneficio);
		elseif ($plano_acao_painel_slideshow) $sql->adOnde('plano_acao_gestao_painel_slideshow='.(int)$plano_acao_painel_slideshow);
		elseif ($plano_acao_projeto_viabilidade) $sql->adOnde('plano_acao_gestao_projeto_viabilidade='.(int)$plano_acao_projeto_viabilidade);
		elseif ($plano_acao_projeto_abertura) $sql->adOnde('plano_acao_gestao_projeto_abertura='.(int)$plano_acao_projeto_abertura);
		elseif ($plano_acao_plano_gestao) $sql->adOnde('plano_acao_gestao_plano_gestao='.(int)$plano_acao_plano_gestao);
		elseif ($acao_ssti) $sql->adOnde('plano_acao_gestao_ssti='.(int)$acao_ssti);
		elseif ($acao_laudo) $sql->adOnde('plano_acao_gestao_laudo='.(int)$acao_laudo);
		elseif ($acao_trelo) $sql->adOnde('plano_acao_gestao_trelo='.(int)$acao_trelo);
		elseif ($acao_trelo_cartao) $sql->adOnde('plano_acao_gestao_trelo_cartao='.(int)$acao_trelo_cartao);
		elseif ($acao_pdcl) $sql->adOnde('plano_acao_gestao_pdcl='.(int)$acao_pdcl);
		elseif ($acao_pdcl_item) $sql->adOnde('plano_acao_gestao_pdcl_item='.(int)$acao_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('plano_acao_gestao');
			$sql->adCampo('MAX(plano_acao_gestao_ordem)');
			if ($uuid) $sql->adOnde('plano_acao_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('plano_acao_gestao_acao ='.(int)$plano_acao_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('plano_acao_gestao');
			if ($uuid) $sql->adInserir('plano_acao_gestao_uuid', $uuid);
			else $sql->adInserir('plano_acao_gestao_acao', (int)$plano_acao_id);
			
			if ($plano_acao_tarefa) $sql->adInserir('plano_acao_gestao_tarefa', (int)$plano_acao_tarefa);
			if ($plano_acao_projeto) $sql->adInserir('plano_acao_gestao_projeto', (int)$plano_acao_projeto);
			elseif ($plano_acao_perspectiva) $sql->adInserir('plano_acao_gestao_perspectiva', (int)$plano_acao_perspectiva);
			elseif ($plano_acao_tema) $sql->adInserir('plano_acao_gestao_tema', (int)$plano_acao_tema);
			elseif ($plano_acao_objetivo) $sql->adInserir('plano_acao_gestao_objetivo', (int)$plano_acao_objetivo);
			elseif ($plano_acao_fator) $sql->adInserir('plano_acao_gestao_fator', (int)$plano_acao_fator);
			elseif ($plano_acao_estrategia) $sql->adInserir('plano_acao_gestao_estrategia', (int)$plano_acao_estrategia);
			
			elseif ($plano_acao_acao) $sql->adInserir('plano_acao_gestao_semelhante', (int)$plano_acao_acao);
			
			elseif ($plano_acao_pratica) $sql->adInserir('plano_acao_gestao_pratica', (int)$plano_acao_pratica);
			elseif ($plano_acao_meta) $sql->adInserir('plano_acao_gestao_meta', (int)$plano_acao_meta);
			elseif ($plano_acao_canvas) $sql->adInserir('plano_acao_gestao_canvas', (int)$plano_acao_canvas);
			elseif ($plano_acao_risco) $sql->adInserir('plano_acao_gestao_risco', (int)$plano_acao_risco);
			elseif ($plano_acao_risco_resposta) $sql->adInserir('plano_acao_gestao_risco_resposta', (int)$plano_acao_risco_resposta);
			elseif ($plano_acao_indicador) $sql->adInserir('plano_acao_gestao_indicador', (int)$plano_acao_indicador);
			elseif ($plano_acao_calendario) $sql->adInserir('plano_acao_gestao_calendario', (int)$plano_acao_calendario);
			elseif ($plano_acao_monitoramento) $sql->adInserir('plano_acao_gestao_monitoramento', (int)$plano_acao_monitoramento);
			elseif ($plano_acao_ata) $sql->adInserir('plano_acao_gestao_ata', (int)$plano_acao_ata);
			elseif ($plano_acao_mswot) $sql->adInserir('plano_acao_gestao_mswot', (int)$plano_acao_mswot);
			elseif ($plano_acao_swot) $sql->adInserir('plano_acao_gestao_swot', (int)$plano_acao_swot);
			elseif ($plano_acao_operativo) $sql->adInserir('plano_acao_gestao_operativo', (int)$plano_acao_operativo);
			elseif ($plano_acao_instrumento) $sql->adInserir('plano_acao_gestao_instrumento', (int)$plano_acao_instrumento);
			elseif ($plano_acao_recurso) $sql->adInserir('plano_acao_gestao_recurso', (int)$plano_acao_recurso);
			elseif ($plano_acao_problema) $sql->adInserir('plano_acao_gestao_problema', (int)$plano_acao_problema);
			elseif ($plano_acao_demanda) $sql->adInserir('plano_acao_gestao_demanda', (int)$plano_acao_demanda);
			elseif ($plano_acao_programa) $sql->adInserir('plano_acao_gestao_programa', (int)$plano_acao_programa);
			elseif ($plano_acao_licao) $sql->adInserir('plano_acao_gestao_licao', (int)$plano_acao_licao);
			elseif ($plano_acao_evento) $sql->adInserir('plano_acao_gestao_evento', (int)$plano_acao_evento);
			elseif ($plano_acao_link) $sql->adInserir('plano_acao_gestao_link', (int)$plano_acao_link);
			elseif ($plano_acao_avaliacao) $sql->adInserir('plano_acao_gestao_avaliacao', (int)$plano_acao_avaliacao);
			elseif ($plano_acao_tgn) $sql->adInserir('plano_acao_gestao_tgn', (int)$plano_acao_tgn);
			elseif ($plano_acao_brainstorm) $sql->adInserir('plano_acao_gestao_brainstorm', (int)$plano_acao_brainstorm);
			elseif ($plano_acao_gut) $sql->adInserir('plano_acao_gestao_gut', (int)$plano_acao_gut);
			elseif ($plano_acao_causa_efeito) $sql->adInserir('plano_acao_gestao_causa_efeito', (int)$plano_acao_causa_efeito);
			elseif ($plano_acao_arquivo) $sql->adInserir('plano_acao_gestao_arquivo', (int)$plano_acao_arquivo);
			elseif ($plano_acao_forum) $sql->adInserir('plano_acao_gestao_forum', (int)$plano_acao_forum);
			elseif ($plano_acao_checklist) $sql->adInserir('plano_acao_gestao_checklist', (int)$plano_acao_checklist);
			elseif ($plano_acao_agenda) $sql->adInserir('plano_acao_gestao_agenda', (int)$plano_acao_agenda);
			elseif ($plano_acao_agrupamento) $sql->adInserir('plano_acao_gestao_agrupamento', (int)$plano_acao_agrupamento);
			elseif ($plano_acao_patrocinador) $sql->adInserir('plano_acao_gestao_patrocinador', (int)$plano_acao_patrocinador);
			elseif ($plano_acao_template) $sql->adInserir('plano_acao_gestao_template', (int)$plano_acao_template);
			elseif ($plano_acao_painel) $sql->adInserir('plano_acao_gestao_painel', (int)$plano_acao_painel);
			elseif ($plano_acao_painel_odometro) $sql->adInserir('plano_acao_gestao_painel_odometro', (int)$plano_acao_painel_odometro);
			elseif ($plano_acao_painel_composicao) $sql->adInserir('plano_acao_gestao_painel_composicao', (int)$plano_acao_painel_composicao);
			elseif ($plano_acao_tr) $sql->adInserir('plano_acao_gestao_tr', (int)$plano_acao_tr);
			elseif ($plano_acao_me) $sql->adInserir('plano_acao_gestao_me', (int)$plano_acao_me);
			elseif ($plano_acao_acao_item) $sql->adInserir('plano_acao_gestao_acao_item', (int)$plano_acao_acao_item);
			elseif ($plano_acao_beneficio) $sql->adInserir('plano_acao_gestao_beneficio', (int)$plano_acao_beneficio);
			elseif ($plano_acao_painel_slideshow) $sql->adInserir('plano_acao_gestao_painel_slideshow', (int)$plano_acao_painel_slideshow);
			elseif ($plano_acao_projeto_viabilidade) $sql->adInserir('plano_acao_gestao_projeto_viabilidade', (int)$plano_acao_projeto_viabilidade);
			elseif ($plano_acao_projeto_abertura) $sql->adInserir('plano_acao_gestao_projeto_abertura', (int)$plano_acao_projeto_abertura);
			elseif ($plano_acao_plano_gestao) $sql->adInserir('plano_acao_gestao_plano_gestao', (int)$plano_acao_plano_gestao);
			elseif ($acao_ssti) $sql->adInserir('plano_acao_gestao_ssti', (int)$acao_ssti);
			elseif ($acao_laudo) $sql->adInserir('plano_acao_gestao_laudo', (int)$acao_laudo);
			elseif ($acao_trelo) $sql->adInserir('plano_acao_gestao_trelo', (int)$acao_trelo);
			elseif ($acao_trelo_cartao) $sql->adInserir('plano_acao_gestao_trelo_cartao', (int)$acao_trelo_cartao);
			elseif ($acao_pdcl) $sql->adInserir('plano_acao_gestao_pdcl', (int)$acao_pdcl);
			elseif ($acao_pdcl_item) $sql->adInserir('plano_acao_gestao_pdcl_item', (int)$acao_pdcl_item);
			
			$sql->adInserir('plano_acao_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($plano_acao_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($plano_acao_id=0, $uuid='', $plano_acao_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('plano_acao_gestao');
	$sql->adOnde('plano_acao_gestao_id='.(int)$plano_acao_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($plano_acao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($plano_acao_id=0, $uuid=''){	
	$saida=atualizar_gestao($plano_acao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($plano_acao_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_gestao');
	$sql->adCampo('plano_acao_gestao.*');
	if ($uuid) $sql->adOnde('plano_acao_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('plano_acao_gestao_acao ='.(int)$plano_acao_id);	
	$sql->adOrdem('plano_acao_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_acao_gestao_ordem'].', '.$gestao_data['plano_acao_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_acao_gestao_ordem'].', '.$gestao_data['plano_acao_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_acao_gestao_ordem'].', '.$gestao_data['plano_acao_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_acao_gestao_ordem'].', '.$gestao_data['plano_acao_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['plano_acao_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['plano_acao_gestao_tarefa']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['plano_acao_gestao_projeto']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['plano_acao_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['plano_acao_gestao_tema']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['plano_acao_gestao_objetivo']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['plano_acao_gestao_fator']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['plano_acao_gestao_estrategia']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['plano_acao_gestao_meta']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['plano_acao_gestao_pratica']).'</td>';
		
		elseif ($gestao_data['plano_acao_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['plano_acao_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['plano_acao_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['plano_acao_gestao_canvas']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['plano_acao_gestao_risco']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['plano_acao_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['plano_acao_gestao_indicador']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['plano_acao_gestao_calendario']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['plano_acao_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['plano_acao_gestao_ata']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['plano_acao_gestao_mswot']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['plano_acao_gestao_swot']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['plano_acao_gestao_operativo']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['plano_acao_gestao_instrumento']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['plano_acao_gestao_recurso']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['plano_acao_gestao_problema']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['plano_acao_gestao_demanda']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['plano_acao_gestao_programa']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['plano_acao_gestao_licao']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['plano_acao_gestao_evento']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['plano_acao_gestao_link']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['plano_acao_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['plano_acao_gestao_tgn']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['plano_acao_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['plano_acao_gestao_gut']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['plano_acao_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['plano_acao_gestao_arquivo']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['plano_acao_gestao_forum']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['plano_acao_gestao_checklist']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['plano_acao_gestao_agenda']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['plano_acao_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['plano_acao_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['plano_acao_gestao_template']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['plano_acao_gestao_painel']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['plano_acao_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['plano_acao_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['plano_acao_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['plano_acao_gestao_tr']).'</td>';	
		elseif ($gestao_data['plano_acao_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['plano_acao_gestao_me']).'</td>';	
		elseif ($gestao_data['plano_acao_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['plano_acao_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['plano_acao_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['plano_acao_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['plano_acao_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['plano_acao_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['plano_acao_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['plano_acao_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['plano_acao_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['plano_acao_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['plano_acao_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['plano_acao_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['plano_acao_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['plano_acao_gestao_ssti']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['plano_acao_gestao_laudo']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['plano_acao_gestao_trelo']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['plano_acao_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['plano_acao_gestao_pdcl']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['plano_acao_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['plano_acao_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		


function calcular_duracao($inicio, $fim, $cia_id){
	global $config;
	$horas = horas_periodo($inicio, $fim, $cia_id);
	$objResposta = new xajaxResponse();
	$resultado=(float)$horas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$resultado=str_replace('.', ',',$resultado);
	$objResposta->assign("plano_acao_duracao","value", $resultado);
	return $objResposta;
	}
$xajax->registerFunction("calcular_duracao");		


function data_final_periodo($inicio, $dias, $cia_id){
	$dias=float_americano($dias);
	$horas=abs($dias*config('horas_trab_diario'));
	$data_final = calculo_data_final_periodo($inicio, $horas, $cia_id);
	$data=new CData($data_final);
	$objResposta = new xajaxResponse();
	$objResposta->assign("oculto_data_fim","value", $data->format("%Y-%m-%d"));
	$objResposta->assign("data_fim","value", $data->format("%d/%m/%Y"));
	$objResposta->assign("fim_hora","value", $data->format("%H"));
	$objResposta->assign("fim_minuto","value", $data->format("%M"));
	return $objResposta;
	}	
$xajax->registerFunction("data_final_periodo");	



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


function exibir_contatos($contatos, $posicao='combo_contatos'){
	global $config;
	$contatos_selecionados=explode(',', $contatos);
	$saida_contatos='';
	if (count($contatos_selecionados)) {
			$saida_contatos.= '<table cellpadding=0 cellspacing=0>';
			$saida_contatos.= '<tr><td class="texto" style="width:400px;">'.link_contato($contatos_selecionados[0],'','','esquerda');
			$qnt_lista_contatos=count($contatos_selecionados);
			if ($qnt_lista_contatos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';		
					$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';
			} 
	else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_contatos',"innerHTML", utf8_encode($saida_contatos));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_contatos");

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

function verificar_datas($plano_acao_id=null, $uuid=null, $data_obrigatoria=null){
	$sql = new BDConsulta;
	
	if ($data_obrigatoria){
		
		$sql->adTabela('plano_acao_item');
		$sql->adCampo('count(plano_acao_item_id)');
		$sql->adOnde('plano_acao_item_inicio IS NULL OR plano_acao_item_fim IS NULL');
		if ($plano_acao_id) $sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_id);
		else $sql->adOnde('plano_acao_item_uuid = \''.$uuid.'\'');
		$qnt=$sql->Resultado();
		$sql->limpar();
		}
	else $qnt=0;
		
	$objResposta = new xajaxResponse();
	$objResposta->assign("verificar_datas","value", $qnt);
	return $objResposta;
	}
$xajax->registerFunction("verificar_datas");



function acao_existe($nome='', $plano_acao_id=0){
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao');
	$sql->adCampo('count(plano_acao_id)');
	$sql->adOnde('plano_acao_nome = "'.$nome.'"');
	if ($plano_acao_id) $sql->adOnde('plano_acao_id != '.(int)$plano_acao_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_acao","value", (int)$existe);
	return $objResposta;
	}
$xajax->registerFunction("acao_existe");

function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script){
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	$sql->adOnde('sisvalor_projeto IS NULL');
	$sql->adOrdem('sisvalor_valor');
	
	if(get_magic_quotes_gpc()) $script = stripslashes($script);

	$lista=$sql->Lista();
	$sql->limpar();
	$vetor=array(0 => '&nbsp;');	
	foreach($lista as $linha) $vetor[utf8_encode($linha['sisvalor_valor_id'])]=utf8_encode($linha['sisvalor_valor']);	
	$saida=selecionaVetor($vetor, $campo, $script);

	$objResposta = new xajaxResponse(); 
	$objResposta->assign($posicao,"innerHTML", $saida); 
	return $objResposta; 
	}	
$xajax->registerFunction("mudar_ajax");



function exibir_usuarios2($usuarios){
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
	$objResposta->assign('combo_usuarios2',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios2");

function exibir_depts2($depts){
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
	$objResposta->assign('combo_depts2',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts2");

	
function calcular_duracao2($inicio, $fim, $cia_id){
	global $config;
	$horas = horas_periodo($inicio, $fim, $cia_id);
	$objResposta = new xajaxResponse();
	$resultado=(float)$horas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$resultado=str_replace('.', ',',$resultado);
	$objResposta->assign("plano_acao_item_duracao","value", $resultado);
	return $objResposta;
	}
$xajax->registerFunction("calcular_duracao2");		


function data_final_periodo2($inicio, $dias, $cia_id){
	$dias=float_americano($dias);
	$horas=abs($dias*config('horas_trab_diario'));
	$data_final = calculo_data_final_periodo($inicio, $horas, $cia_id);
	$data=new CData($data_final);
	$objResposta = new xajaxResponse();
	$objResposta->assign("oculto_data_fim2","value", $data->format("%Y-%m-%d"));
	$objResposta->assign("data_fim2","value", $data->format("%d/%m/%Y"));
	$objResposta->assign("fim_hora2","value", $data->format("%H"));
	$objResposta->assign("fim_minuto2","value", $data->format("%M"));
	return $objResposta;
	}	
$xajax->registerFunction("data_final_periodo2");	


function exibir($plano_acao_item_acao=null, $uuid=null){
	$saida=atualizar_acoes($plano_acao_item_acao, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("acoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir");	

function mudar_posicao_acao_ajax($plano_acao_item_ordem, $plano_acao_item_id, $direcao, $plano_acao_item_acao=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$plano_acao_item_id) {
		$novo_ui_plano_acao_item_ordem = $plano_acao_item_ordem;
		$sql->adTabela('plano_acao_item');
		$sql->adOnde('plano_acao_item_id != '.(int)$plano_acao_item_id);
		
		if ($uuid) $sql->adOnde('plano_acao_item_uuid = \''.$uuid.'\'');
		else $sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_item_acao);
		
		$sql->adOrdem('plano_acao_item_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_plano_acao_item_ordem;
			$novo_ui_plano_acao_item_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_plano_acao_item_ordem;
			$novo_ui_plano_acao_item_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_plano_acao_item_ordem;
			$novo_ui_plano_acao_item_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_plano_acao_item_ordem;
			$novo_ui_plano_acao_item_ordem = count($membros) + 1;
			}
		if ($novo_ui_plano_acao_item_ordem && ($novo_ui_plano_acao_item_ordem <= count($membros) + 1)) {
			$sql->adTabela('plano_acao_item');
			$sql->adAtualizar('plano_acao_item_ordem', $novo_ui_plano_acao_item_ordem);
			$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_plano_acao_item_ordem) {
					$sql->adTabela('plano_acao_item');
					$sql->adAtualizar('plano_acao_item_ordem', $idx);
					$sql->adOnde('plano_acao_item_id = '.(int)$acao['plano_acao_item_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_acao_item');
					$sql->adAtualizar('plano_acao_item_ordem', $idx + 1);
					$sql->adOnde('plano_acao_item_id = '.(int)$acao['plano_acao_item_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	$saida=atualizar_acoes($plano_acao_item_acao, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("acoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_acao_ajax");		
	

function incluir_acao_ajax(
	$plano_acao_item_acao=null, 
	$uuid=null,
	$plano_acao_item_id=null, 
	$uuid2=null,
	$plano_acao_item_responsavel=null,
	$plano_acao_item_cia=null, 
	$plano_acao_item_nome=null, 
	$plano_acao_item_quando=null, 
	$plano_acao_item_oque=null, 
	$plano_acao_item_como=null, 
	$plano_acao_item_onde=null, 
	$plano_acao_item_quanto=null, 
	$plano_acao_item_porque=null, 
	$plano_acao_item_quem=null, 
	$plano_acao_item_inicio=null, 
	$plano_acao_item_fim=null, 
	$plano_acao_item_duracao=null,
	$tem_inicio=null,
	$tem_fim=null,
	$plano_acao_item_usuarios=null,
	$plano_acao_item_depts=null,
	$plano_acao_item_percentagem=null,
	$plano_acao_item_peso=null,
	$plano_acao_calculo_porcentagem=null,
	$exibir_porcentagem_item=null
){
	global $bd, $Aplic, $config;
	$sql = new BDConsulta;
	$plano_acao_item_nome=previnirXSS(utf8_decode($plano_acao_item_nome));
	$plano_acao_item_quando=previnirXSS(utf8_decode($plano_acao_item_quando));
	$plano_acao_item_oque=previnirXSS(utf8_decode($plano_acao_item_oque));
	$plano_acao_item_como=previnirXSS(utf8_decode($plano_acao_item_como));
	$plano_acao_item_onde=previnirXSS(utf8_decode($plano_acao_item_onde));
	$plano_acao_item_quanto=previnirXSS(utf8_decode($plano_acao_item_quanto));
	$plano_acao_item_porque=previnirXSS(utf8_decode($plano_acao_item_porque));
	$plano_acao_item_quem=previnirXSS(utf8_decode($plano_acao_item_quem));
	$plano_acao_item_duracao = float_americano($plano_acao_item_duracao);
	if ($plano_acao_item_id){
		$sql->adTabela('plano_acao_item');
		$sql->adAtualizar('plano_acao_item_responsavel', ($plano_acao_item_responsavel ? $plano_acao_item_responsavel : null));
		$sql->adAtualizar('plano_acao_item_cia', ($plano_acao_item_cia ? $plano_acao_item_cia : null));	
		$sql->adAtualizar('plano_acao_item_nome', $plano_acao_item_nome);	
		$sql->adAtualizar('plano_acao_item_quando', $plano_acao_item_quando);	
		$sql->adAtualizar('plano_acao_item_oque', $plano_acao_item_oque);	
		$sql->adAtualizar('plano_acao_item_como', $plano_acao_item_como);	
		$sql->adAtualizar('plano_acao_item_onde', $plano_acao_item_onde);	
		$sql->adAtualizar('plano_acao_item_quanto', $plano_acao_item_quanto);	
		$sql->adAtualizar('plano_acao_item_porque', $plano_acao_item_porque);	
		$sql->adAtualizar('plano_acao_item_quem', $plano_acao_item_quem);	
		$sql->adAtualizar('plano_acao_item_inicio', ($tem_inicio ? $plano_acao_item_inicio : null));	
		$sql->adAtualizar('plano_acao_item_fim', ($tem_fim ? $plano_acao_item_fim : null));	
		$sql->adAtualizar('plano_acao_item_duracao', $plano_acao_item_duracao * ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8));	
		$sql->adAtualizar('plano_acao_item_percentagem', $plano_acao_item_percentagem);	
		$sql->adAtualizar('plano_acao_item_peso', float_americano($plano_acao_item_peso));	
		$sql->adOnde('plano_acao_item_id ='.(int)$plano_acao_item_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {	
		$sql->adTabela('plano_acao_item');
		$sql->adCampo('count(plano_acao_item_id) AS soma');
		
	  if ($uuid) $sql->adOnde('plano_acao_item_uuid = \''.$uuid.'\'');
		else $sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_item_acao);
	  
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->limpar();
		
		$sql->adTabela('plano_acao_item');
		
		if ($uuid) $sql->adInserir('plano_acao_item_uuid', $uuid);
		else $sql->adInserir('plano_acao_item_acao', $plano_acao_item_acao);
		
		$sql->adInserir('plano_acao_item_ordem', $soma_total);
		$sql->adInserir('plano_acao_item_responsavel', ($plano_acao_item_responsavel ? $plano_acao_item_responsavel : null));
		$sql->adInserir('plano_acao_item_cia', ($plano_acao_item_cia ? $plano_acao_item_cia : null));	
		$sql->adInserir('plano_acao_item_nome', $plano_acao_item_nome);	
		$sql->adInserir('plano_acao_item_quando', $plano_acao_item_quando);	
		$sql->adInserir('plano_acao_item_oque', $plano_acao_item_oque);	
		$sql->adInserir('plano_acao_item_como', $plano_acao_item_como);	
		$sql->adInserir('plano_acao_item_onde', $plano_acao_item_onde);	
		$sql->adInserir('plano_acao_item_quanto', $plano_acao_item_quanto);	
		$sql->adInserir('plano_acao_item_porque', $plano_acao_item_porque);	
		$sql->adInserir('plano_acao_item_quem', $plano_acao_item_quem);	
		$sql->adInserir('plano_acao_item_inicio', ($tem_inicio ? $plano_acao_item_inicio : null));	
		$sql->adInserir('plano_acao_item_fim', ($tem_fim ? $plano_acao_item_fim : null));	
		$sql->adInserir('plano_acao_item_duracao', $plano_acao_item_duracao * ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8));	
		$sql->adInserir('plano_acao_item_percentagem', $plano_acao_item_percentagem);	
		$sql->adInserir('plano_acao_item_peso', float_americano($plano_acao_item_peso));	
		$sql->exec();
		$sql->limpar();
		$plano_acao_item_id=$bd->Insert_ID('plano_acao_item','plano_acao_item_id');
		
		if ($Aplic->profissional){
			$sql->adTabela('plano_acao_item_custos');
			$sql->adAtualizar('plano_acao_item_custos_plano_acao_item', $plano_acao_item_id);
			$sql->adOnde('plano_acao_item_custos_uuid =\''.$uuid2.'\'');
			$sql->exec();
		  $sql->limpar();
			}	
			
		}
	$usuarios=explode(',', $plano_acao_item_usuarios);
	$sql->setExcluir('plano_acao_item_usuario');
	$sql->adOnde('plano_acao_item_usuario_item = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	if (count($usuarios)){
		foreach($usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('plano_acao_item_usuario');
				$sql->adInserir('plano_acao_item_usuario_item', (int)$plano_acao_item_id);
				$sql->adInserir('plano_acao_item_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
		}
	$depts=explode(',', $plano_acao_item_depts);
	$sql->setExcluir('plano_acao_item_dept');
	$sql->adOnde('plano_acao_item_dept_plano_acao_item = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	if (count($depts)){
		foreach($depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('plano_acao_item_dept');
				$sql->adInserir('plano_acao_item_dept_plano_acao_item', (int)$plano_acao_item_id);
				$sql->adInserir('plano_acao_item_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}	
		}	
	if ($uuid2){
		$sql->adTabela('plano_acao_item_custos');
		$sql->adAtualizar('plano_acao_item_custos_plano_acao_item', (int)$plano_acao_item_id);
		$sql->adAtualizar('plano_acao_item_custos_uuid', null);
		$sql->adOnde('plano_acao_item_custos_uuid=\''.$uuid2.'\'');
		$sql->exec();
		$sql->limpar();
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_plano_acao_item', (int)$plano_acao_item_id);
		$sql->adAtualizar('plano_acao_item_gastos_uuid', null);
		$sql->adOnde('plano_acao_item_gastos_uuid=\''.$uuid2.'\'');
		$sql->exec();
		$sql->limpar();	
		}
	//calculo de porcentagem
	if ($Aplic->profissional && $plano_acao_calculo_porcentagem){
		$sql->adTabela('plano_acao_item');
		$sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_item_acao);
		$sql->adCampo('plano_acao_item_percentagem, plano_acao_item_peso');
		$lista=$sql->Lista();
		$sql->limpar();
		$numerador=0;
		$denominador=0;
		foreach($lista as $linha) {
			$numerador+=($linha['plano_acao_item_percentagem']*$linha['plano_acao_item_peso']);
			$denominador+=$linha['plano_acao_item_peso'];
			}
		$percentagem_calculada=($denominador ? $numerador/$denominador : 0);
		$obj = new CPlanoAcao();
		$obj->load($plano_acao_item_acao);		
		if ($obj->plano_acao_percentagem!=$percentagem_calculada && $Aplic->profissional)	{
			$sql->adTabela('plano_acao');
			$sql->adAtualizar('plano_acao_percentagem', $percentagem_calculada);
			$sql->adOnde('plano_acao_id='.(int)$plano_acao_item_acao);
			$sql->exec();
			$sql->limpar();			
			$obj->disparo_observador('fisico');
			}
		}	
	$saida=atualizar_acoes($plano_acao_item_acao, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("acoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_acao_ajax");	


function excluir_acao_ajax($plano_acao_item_id, $plano_acao_item_acao=null, $uuid=null){
	$sql = new BDConsulta;
	$sql->setExcluir('plano_acao_item');
	$sql->adOnde('plano_acao_item_id='.(int)$plano_acao_item_id);
	$sql->exec();
	$saida=atualizar_acoes($plano_acao_item_acao, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("acoes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_acao_ajax");	


function atualizar_acoes($plano_acao_item_acao=null, $uuid=null){
	global $config, $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao_item');
	if ($uuid) $sql->adOnde('plano_acao_item_uuid = \''.$uuid.'\'');
	else $sql->adOnde('plano_acao_item_acao = '.(int)$plano_acao_item_acao);
	$sql->adCampo('plano_acao_item.*');
	$sql->adOrdem('plano_acao_item_ordem');
	$acoes=$sql->ListaChave('plano_acao_item_id');
	$sql->limpar();
	$saida='';
	if ($Aplic->profissional){
		$sql->adTabela('campo_formulario');
		$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
		$sql->adOnde('campo_formulario_tipo = \'acao\'');
		$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
		$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
		$sql->limpar();
		}
	if (count($acoes)) {
		$saida.= '<table cellspacing=0 cellpadding=0 width=100%><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left width=100%><tr><th>&nbsp;</th><th>O Que</th><th>Por que</th><th>Onde</th><th>Quando</th><th>Quem</th><th>Como</th><th>Quanto</th>'.($Aplic->profissional && $exibir['porcentagem_item'] ? '<th>Peso</th><th>%</th>' : '').'<th>&nbsp;</th></tr>';
		foreach ($acoes as $plano_acao_item_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverPrimeiro\');">'.imagem('icones/2setacima.gif', 'Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição.').'</a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverParaCima\');">'.imagem('icones/1setacima.gif', 'Posição Acima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover uma posição acima.').'</a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverParaBaixo\');">'.imagem('icones/1setabaixo.gif', 'Posição Abaixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover uma posição abaixo.').'</a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_acao('.$linha['plano_acao_item_ordem'].', '.$linha['plano_acao_item_id'].', \'moverUltimo\');">'.imagem('icones/2setabaixo.gif', 'Última Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição.').'</a>';
			$saida.= '</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_oque'] ? $linha['plano_acao_item_oque'] : '&nbsp;').'</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_porque'] ? $linha['plano_acao_item_porque'] : '&nbsp;').'</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_onde'] ? $linha['plano_acao_item_onde'] : '&nbsp;').'</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quando'];
				if ($linha['plano_acao_item_quando'] && ($linha['plano_acao_item_inicio'] || $linha['plano_acao_item_fim'])) $saida.= '<br>';
				if ($linha['plano_acao_item_inicio']) $saida.= retorna_data($linha['plano_acao_item_inicio']);
				if ($linha['plano_acao_item_inicio'] && $linha['plano_acao_item_fim']) $saida.= '<br>';
				if ($linha['plano_acao_item_fim']) $saida.= retorna_data($linha['plano_acao_item_fim']);
				if (!$linha['plano_acao_item_quando'] && !$linha['plano_acao_item_inicio'] && !$linha['plano_acao_item_fim']) $saida.= '&nbsp;';	
			$saida.= '</td>';
			
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quem'];
			
			$sql->adTabela('plano_acao_item_usuario');
			$sql->adCampo('plano_acao_item_usuario_usuario');
			$sql->adOnde('plano_acao_item_usuario_item = '.$linha['plano_acao_item_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
		
			$saida_quem='';
			if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['plano_acao_item_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['plano_acao_item_id'].'"><br>'.$lista.'</span>';
					}
				} 	
			$sql->adTabela('plano_acao_item_dept');
			$sql->adCampo('plano_acao_item_dept_dept');
			$sql->adOnde('plano_acao_item_dept_plano_acao_item = '.$linha['plano_acao_item_id']);
			$depts = $sql->carregarColuna();
			$sql->limpar();
		
			$saida_dept='';
			if ($depts && count($depts)) {
				$saida_dept.= link_secao($depts[0]);
				$qnt_depts=count($depts);
				if ($qnt_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';		
					$saida_dept.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamentos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'depts_'.$linha['plano_acao_item_id'].'\');">(+'.($qnt_depts - 1).')</a><span style="display: none" id="depts_'.$linha['plano_acao_item_id'].'"><br>'.$lista.'</span>';
					}
				} 		
			if ($saida_quem) $saida.= ($linha['plano_acao_item_quem'] ? '<br>' : '').$saida_quem;
			if ($saida_dept) $saida.= ($linha['plano_acao_item_quem'] || $saida_quem ? '<br>' : '').$saida_dept;
			if (!$saida_quem && !$linha['plano_acao_item_quem'] && !$saida_dept) $saida.= '&nbsp;';
			$saida.= '</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.($linha['plano_acao_item_como'] ? $linha['plano_acao_item_como'] : '&nbsp;').'</td>';
			$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: left; vertical-align:text-top;">'.$linha['plano_acao_item_quanto'];
			$sql->adTabela('plano_acao_item_custos');
			$sql->adCampo('SUM(((plano_acao_item_custos_quantidade*plano_acao_item_custos_custo*plano_acao_item_custos_cotacao)*((100+plano_acao_item_custos_bdi)/100))) as total');
			$sql->adOnde('plano_acao_item_custos_plano_acao_item = '.$linha['plano_acao_item_id']);
			$custo = $sql->Resultado();
			$sql->limpar();
			if ($custo) $saida.= ($linha['plano_acao_item_quanto']? '<br>' : '').'custo: '.$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.');
			$sql->adTabela('plano_acao_item_gastos');
				
			
			$sql->adCampo('SUM(((plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo)*((100+plano_acao_item_gastos_bdi)/100))) as total');
			$sql->adOnde('plano_acao_item_gastos_plano_acao_item = '.$linha['plano_acao_item_id']);
			$gasto = $sql->Resultado();
			$sql->limpar();
			if ($gasto) $saida.= ($linha['plano_acao_item_quanto'] || $custo ? '<br>' : '').'gasto: '.$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.');
			if (!$linha['plano_acao_item_quanto']) $saida.= '&nbsp;';
			$saida.= '</td>';
			if ($Aplic->profissional && $exibir['porcentagem_item']){
				$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.($linha['plano_acao_item_peso'] ? number_format($linha['plano_acao_item_peso'], 2, ',', '.') : '&nbsp;').'</td>';
				$saida.= '<td style="margin-bottom:0cm; margin-top:0cm; text-align: right; vertical-align:text-top;">'.(int)$linha['plano_acao_item_percentagem'].'</td>';
				}
			
			$saida.= '<td width=32><a href="javascript: void(0);" onclick="editar_acao('.$linha['plano_acao_item_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a ação.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este acao?\')) {excluir_acao('.$linha['plano_acao_item_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir a ação.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	return $saida;	
	}	

$xajax->registerFunction("atualizar_acoes");		
	
function editar_acao($plano_acao_item_id, $data_obrigatoria=null, $inicio=null, $fim=null){
	global $config, $Aplic;
	
	$objResposta = new xajaxResponse();
	
	$sql = new BDConsulta;

	$sql->adTabela('plano_acao_item');
	$sql->adCampo('plano_acao_item.*');
	$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
	$sql->adOrdem('plano_acao_item_ordem');
	$linha=$sql->Linha();
	$sql->limpar();
	$saida='';	
	
	
	
	if ($data_obrigatoria){
		if (!$linha['plano_acao_item_inicio']) $linha['plano_acao_item_inicio']=$inicio;
		if (!$linha['plano_acao_item_fim']) $linha['plano_acao_item_fim']=$fim;
		}
	
	$linha['plano_acao_item_duracao'] = horas_periodo($linha['plano_acao_item_inicio'], $linha['plano_acao_item_fim'], $linha['plano_acao_item_cia']);
	

	$objResposta->assign("plano_acao_item_duracao","value", str_replace('.', ',', (float)$linha['plano_acao_item_duracao']/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)));	
	$data = new CData($linha['plano_acao_item_inicio']);
	$objResposta->assign("oculto_data_inicio2","value", $data->format('%Y-%m-%d'));
	$objResposta->assign("data_inicio2","value", $data->format('%d/%m/%Y'));	
	$objResposta->assign("inicio_hora2","value", $data->format('%H'));	
	$objResposta->assign("inicio_minuto2","value", $data->format('%M'));
	
	$data = new CData($linha['plano_acao_item_fim']);
	$objResposta->assign("oculto_data_fim2","value", $data->format('%Y-%m-%d'));
	$objResposta->assign("data_fim2","value", $data->format('%d/%m/%Y'));	
	$objResposta->assign("fim_hora2","value", $data->format('%H'));	
	$objResposta->assign("fim_minuto2","value", $data->format('%M'));
	
	
	
	
	
	
	$objResposta->assign("plano_acao_item_id","value", $plano_acao_item_id);
	$objResposta->assign("plano_acao_item_responsavel","value", ($linha['plano_acao_item_responsavel'] ? $linha['plano_acao_item_responsavel'] : $Aplic->usuario_id));
	$objResposta->assign("nome_responsavel","value", utf8_encode(nome_om(($linha['plano_acao_item_responsavel'] ? $linha['plano_acao_item_responsavel'] : $Aplic->usuario_id), $Aplic->getPref('om_usuario'))));
	$objResposta->assign("plano_acao_item_cia","value", $linha['plano_acao_item_cia']);	
	
	
	
	

	
	

	
	$objResposta->assign("plano_acao_item_nome","value", utf8_encode($linha['plano_acao_item_nome']));	
	
	$objResposta->assign("apoio_plano_acao_item_quando","value", utf8_encode($linha['plano_acao_item_quando']));	
	$objResposta->assign("apoio_plano_acao_item_oque","value", utf8_encode($linha['plano_acao_item_oque']));	
	$objResposta->assign("apoio_plano_acao_item_como","value", utf8_encode($linha['plano_acao_item_como']));	
	$objResposta->assign("apoio_plano_acao_item_onde","value", utf8_encode($linha['plano_acao_item_onde']));	
	$objResposta->assign("apoio_plano_acao_item_quanto","value", utf8_encode($linha['plano_acao_item_quanto']));	
	$objResposta->assign("apoio_plano_acao_item_porque","value", utf8_encode($linha['plano_acao_item_porque']));	
	$objResposta->assign("apoio_plano_acao_item_quem","value", utf8_encode($linha['plano_acao_item_quem']));	
	
	$objResposta->assign("plano_acao_item_percentagem","value", (int)$linha['plano_acao_item_percentagem']);	
	$objResposta->assign("plano_acao_item_peso","value", number_format($linha['plano_acao_item_peso'], 2, ',', '.'));	
	$objResposta->assign("tem_inicio","checked", ($linha['plano_acao_item_inicio'] || $data_obrigatoria ? true : false));	
	$objResposta->assign("tem_fim","checked", ($linha['plano_acao_item_fim'] || $data_obrigatoria ? true : false));
	
	$sql->adTabela('plano_acao_item_usuario');
	$sql->adCampo('plano_acao_item_usuario_usuario');
	$sql->adOnde('plano_acao_item_usuario_item = '.(int)$plano_acao_item_id);
	$usuarios=$sql->carregarColuna();
	$sql->limpar();	

	$objResposta->assign("plano_acao_item_usuarios","value", implode(',',$usuarios));
	
	
	$sql->adTabela('plano_acao_item_dept');
	$sql->adCampo('plano_acao_item_dept_dept');
	$sql->adOnde('plano_acao_item_dept_plano_acao_item = '.(int)$plano_acao_item_id);
	$depts=$sql->carregarColuna();
	$sql->limpar();	

	$objResposta->assign("plano_acao_item_depts","value", implode(',',$depts));

	return $objResposta;
	}	

$xajax->registerFunction("editar_acao");		



$xajax->processRequest();
?>