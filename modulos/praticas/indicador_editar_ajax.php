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

$social=$Aplic->modulo_ativo('social');
if ($social) require_once BASE_DIR.'/modulos/social/social.class.php';

if ($Aplic->profissional) require_once BASE_DIR.'/modulos/praticas/indicador_editar_ajax_pro.php';



function mudar_posicao_gestao($ordem, $pratica_indicador_gestao_id, $direcao, $pratica_indicador_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $pratica_indicador_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('pratica_indicador_gestao');
		$sql->adOnde('pratica_indicador_gestao_id != '.(int)$pratica_indicador_gestao_id);
		if ($uuid) $sql->adOnde('pratica_indicador_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_gestao_indicador = '.(int)$pratica_indicador_id);
		$sql->adOrdem('pratica_indicador_gestao_ordem');
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
			$sql->adTabela('pratica_indicador_gestao');
			$sql->adAtualizar('pratica_indicador_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('pratica_indicador_gestao_id = '.(int)$pratica_indicador_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('pratica_indicador_gestao');
					$sql->adAtualizar('pratica_indicador_gestao_ordem', $idx);
					$sql->adOnde('pratica_indicador_gestao_id = '.(int)$acao['pratica_indicador_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('pratica_indicador_gestao');
					$sql->adAtualizar('pratica_indicador_gestao_ordem', $idx + 1);
					$sql->adOnde('pratica_indicador_gestao_id = '.(int)$acao['pratica_indicador_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($pratica_indicador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$pratica_indicador_id=0, 
	$uuid='',  
	
	$pratica_indicador_projeto=null,
	$pratica_indicador_tarefa=null,
	$pratica_indicador_perspectiva=null,
	$pratica_indicador_tema=null,
	$pratica_indicador_objetivo=null,
	$pratica_indicador_fator=null,
	$pratica_indicador_estrategia=null,
	$pratica_indicador_meta=null,
	$pratica_indicador_pratica=null,
	$pratica_indicador_acao=null,
	$pratica_indicador_canvas=null,
	$pratica_indicador_risco=null,
	$pratica_indicador_risco_resposta=null,
	$pratica_indicador_indicador=null,
	$pratica_indicador_calendario=null,
	$pratica_indicador_monitoramento=null,
	$pratica_indicador_ata=null,
	$pratica_indicador_mswot=null,
	$pratica_indicador_swot=null,
	$pratica_indicador_operativo=null,
	$pratica_indicador_instrumento=null,
	$pratica_indicador_recurso=null,
	$pratica_indicador_problema=null,
	$pratica_indicador_demanda=null,
	$pratica_indicador_programa=null,
	$pratica_indicador_licao=null,
	$pratica_indicador_evento=null,
	$pratica_indicador_link=null,
	$pratica_indicador_avaliacao=null,
	$pratica_indicador_tgn=null,
	$pratica_indicador_brainstorm=null,
	$pratica_indicador_gut=null,
	$pratica_indicador_causa_efeito=null,
	$pratica_indicador_arquivo=null,
	$pratica_indicador_forum=null,
	$pratica_indicador_checklist=null,
	$pratica_indicador_agenda=null,
	$pratica_indicador_agrupamento=null,
	$pratica_indicador_patrocinador=null,
	$pratica_indicador_template=null,
	$pratica_indicador_painel=null,
	$pratica_indicador_painel_odometro=null,
	$pratica_indicador_painel_composicao=null,
	$pratica_indicador_tr=null,
	$pratica_indicador_me=null,
	$pratica_indicador_acao_item=null,
	$pratica_indicador_beneficio=null,
	$pratica_indicador_painel_slideshow=null,
	$pratica_indicador_projeto_viabilidade=null,
	$pratica_indicador_projeto_abertura=null,
	$pratica_indicador_plano_gestao=null,
	$indicador_ssti=null,
	$indicador_laudo=null,
	$indicador_trelo=null,
	$indicador_trelo_cartao=null,
	$indicador_pdcl=null,
	$indicador_pdcl_item=null
	)
	{
	if (
		$pratica_indicador_projeto || 
		$pratica_indicador_tarefa || 
		$pratica_indicador_perspectiva || 
		$pratica_indicador_tema || 
		$pratica_indicador_objetivo || 
		$pratica_indicador_fator || 
		$pratica_indicador_estrategia || 
		$pratica_indicador_meta || 
		$pratica_indicador_pratica || 
		$pratica_indicador_acao || 
		$pratica_indicador_canvas || 
		$pratica_indicador_risco || 
		$pratica_indicador_risco_resposta || 
		$pratica_indicador_indicador || 
		$pratica_indicador_calendario || 
		$pratica_indicador_monitoramento || 
		$pratica_indicador_ata || 
		$pratica_indicador_mswot || 
		$pratica_indicador_swot || 
		$pratica_indicador_operativo || 
		$pratica_indicador_instrumento || 
		$pratica_indicador_recurso || 
		$pratica_indicador_problema || 
		$pratica_indicador_demanda || 
		$pratica_indicador_programa || 
		$pratica_indicador_licao || 
		$pratica_indicador_evento || 
		$pratica_indicador_link || 
		$pratica_indicador_avaliacao || 
		$pratica_indicador_tgn || 
		$pratica_indicador_brainstorm || 
		$pratica_indicador_gut || 
		$pratica_indicador_causa_efeito || 
		$pratica_indicador_arquivo || 
		$pratica_indicador_forum || 
		$pratica_indicador_checklist || 
		$pratica_indicador_agenda || 
		$pratica_indicador_agrupamento || 
		$pratica_indicador_patrocinador || 
		$pratica_indicador_template || 
		$pratica_indicador_painel || 
		$pratica_indicador_painel_odometro || 
		$pratica_indicador_painel_composicao || 
		$pratica_indicador_tr || 
		$pratica_indicador_me || 
		$pratica_indicador_acao_item || 
		$pratica_indicador_beneficio || 
		$pratica_indicador_painel_slideshow || 
		$pratica_indicador_projeto_viabilidade || 
		$pratica_indicador_projeto_abertura || 
		$pratica_indicador_plano_gestao|| 
		$indicador_ssti || 
		$indicador_laudo || 
		$indicador_trelo || 
		$indicador_trelo_cartao || 
		$indicador_pdcl || 
		$indicador_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('pratica_indicador_gestao');
			if ($uuid) $sql->adOnde('pratica_indicador_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$pratica_indicador_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('pratica_indicador_gestao');
		$sql->adCampo('count(pratica_indicador_gestao_id)');
		if ($uuid) $sql->adOnde('pratica_indicador_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$pratica_indicador_id);	
		if ($pratica_indicador_tarefa) $sql->adOnde('pratica_indicador_gestao_tarefa='.(int)$pratica_indicador_tarefa);
		elseif ($pratica_indicador_projeto) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$pratica_indicador_projeto);
		elseif ($pratica_indicador_perspectiva) $sql->adOnde('pratica_indicador_gestao_perspectiva='.(int)$pratica_indicador_perspectiva);
		elseif ($pratica_indicador_tema) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$pratica_indicador_tema);
		elseif ($pratica_indicador_objetivo) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pratica_indicador_objetivo);
		elseif ($pratica_indicador_fator) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pratica_indicador_fator);
		elseif ($pratica_indicador_estrategia) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pratica_indicador_estrategia);
		elseif ($pratica_indicador_acao) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$pratica_indicador_acao);
		elseif ($pratica_indicador_pratica) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_indicador_pratica);
		elseif ($pratica_indicador_meta) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pratica_indicador_meta);
		elseif ($pratica_indicador_canvas) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$pratica_indicador_canvas);
		elseif ($pratica_indicador_risco) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$pratica_indicador_risco);
		elseif ($pratica_indicador_risco_resposta) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$pratica_indicador_risco_resposta);
		
		elseif ($pratica_indicador_indicador) $sql->adOnde('pratica_indicador_gestao_semelhante='.(int)$pratica_indicador_indicador);
		
		elseif ($pratica_indicador_calendario) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$pratica_indicador_calendario);
		elseif ($pratica_indicador_monitoramento) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$pratica_indicador_monitoramento);
		elseif ($pratica_indicador_ata) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$pratica_indicador_ata);
		elseif ($pratica_indicador_mswot) $sql->adOnde('pratica_indicador_gestao_mswot='.(int)$pratica_indicador_mswot);
		elseif ($pratica_indicador_swot) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$pratica_indicador_swot);
		elseif ($pratica_indicador_operativo) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$pratica_indicador_operativo);
		elseif ($pratica_indicador_instrumento) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$pratica_indicador_instrumento);
		elseif ($pratica_indicador_recurso) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$pratica_indicador_recurso);
		elseif ($pratica_indicador_problema) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$pratica_indicador_problema);
		elseif ($pratica_indicador_demanda) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$pratica_indicador_demanda);
		elseif ($pratica_indicador_programa) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$pratica_indicador_programa);
		elseif ($pratica_indicador_licao) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$pratica_indicador_licao);
		elseif ($pratica_indicador_evento) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$pratica_indicador_evento);
		elseif ($pratica_indicador_link) $sql->adOnde('pratica_indicador_gestao_link='.(int)$pratica_indicador_link);
		elseif ($pratica_indicador_avaliacao) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$pratica_indicador_avaliacao);
		elseif ($pratica_indicador_tgn) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$pratica_indicador_tgn);
		elseif ($pratica_indicador_brainstorm) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$pratica_indicador_brainstorm);
		elseif ($pratica_indicador_gut) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$pratica_indicador_gut);
		elseif ($pratica_indicador_causa_efeito) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$pratica_indicador_causa_efeito);
		elseif ($pratica_indicador_arquivo) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$pratica_indicador_arquivo);
		elseif ($pratica_indicador_forum) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$pratica_indicador_forum);
		elseif ($pratica_indicador_checklist) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$pratica_indicador_checklist);
		elseif ($pratica_indicador_agenda) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$pratica_indicador_agenda);
		elseif ($pratica_indicador_agrupamento) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$pratica_indicador_agrupamento);
		elseif ($pratica_indicador_patrocinador) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$pratica_indicador_patrocinador);
		elseif ($pratica_indicador_template) $sql->adOnde('pratica_indicador_gestao_template='.(int)$pratica_indicador_template);
		elseif ($pratica_indicador_painel) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$pratica_indicador_painel);
		elseif ($pratica_indicador_painel_odometro) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$pratica_indicador_painel_odometro);
		elseif ($pratica_indicador_painel_composicao) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$pratica_indicador_painel_composicao);
		elseif ($pratica_indicador_tr) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$pratica_indicador_tr);
		elseif ($pratica_indicador_me) $sql->adOnde('pratica_indicador_gestao_me='.(int)$pratica_indicador_me);
		elseif ($pratica_indicador_acao_item) $sql->adOnde('pratica_indicador_gestao_acao_item='.(int)$pratica_indicador_acao_item);
		elseif ($pratica_indicador_beneficio) $sql->adOnde('pratica_indicador_gestao_beneficio='.(int)$pratica_indicador_beneficio);
		elseif ($pratica_indicador_painel_slideshow) $sql->adOnde('pratica_indicador_gestao_painel_slideshow='.(int)$pratica_indicador_painel_slideshow);
		elseif ($pratica_indicador_projeto_viabilidade) $sql->adOnde('pratica_indicador_gestao_projeto_viabilidade='.(int)$pratica_indicador_projeto_viabilidade);
		elseif ($pratica_indicador_projeto_abertura) $sql->adOnde('pratica_indicador_gestao_projeto_abertura='.(int)$pratica_indicador_projeto_abertura);
		elseif ($pratica_indicador_plano_gestao) $sql->adOnde('pratica_indicador_gestao_plano_gestao='.(int)$pratica_indicador_plano_gestao);
		elseif ($indicador_ssti) $sql->adOnde('pratica_indicador_gestao_ssti='.(int)$indicador_ssti);
		elseif ($indicador_laudo) $sql->adOnde('pratica_indicador_gestao_laudo='.(int)$indicador_laudo);
		elseif ($indicador_trelo) $sql->adOnde('pratica_indicador_gestao_trelo='.(int)$indicador_trelo);
		elseif ($indicador_trelo_cartao) $sql->adOnde('pratica_indicador_gestao_trelo_cartao='.(int)$indicador_trelo_cartao);
		elseif ($indicador_pdcl) $sql->adOnde('pratica_indicador_gestao_pdcl='.(int)$indicador_pdcl);
		elseif ($indicador_pdcl_item) $sql->adOnde('pratica_indicador_gestao_pdcl_item='.(int)$indicador_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('pratica_indicador_gestao');
			$sql->adCampo('MAX(pratica_indicador_gestao_ordem)');
			if ($uuid) $sql->adOnde('pratica_indicador_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$pratica_indicador_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('pratica_indicador_gestao');
			if ($uuid) $sql->adInserir('pratica_indicador_gestao_uuid', $uuid);
			else $sql->adInserir('pratica_indicador_gestao_indicador', (int)$pratica_indicador_id);
			
			if ($pratica_indicador_tarefa) $sql->adInserir('pratica_indicador_gestao_tarefa', (int)$pratica_indicador_tarefa);
			if ($pratica_indicador_projeto) $sql->adInserir('pratica_indicador_gestao_projeto', (int)$pratica_indicador_projeto);
			elseif ($pratica_indicador_perspectiva) $sql->adInserir('pratica_indicador_gestao_perspectiva', (int)$pratica_indicador_perspectiva);
			elseif ($pratica_indicador_tema) $sql->adInserir('pratica_indicador_gestao_tema', (int)$pratica_indicador_tema);
			elseif ($pratica_indicador_objetivo) $sql->adInserir('pratica_indicador_gestao_objetivo', (int)$pratica_indicador_objetivo);
			elseif ($pratica_indicador_fator) $sql->adInserir('pratica_indicador_gestao_fator', (int)$pratica_indicador_fator);
			elseif ($pratica_indicador_estrategia) $sql->adInserir('pratica_indicador_gestao_estrategia', (int)$pratica_indicador_estrategia);
			elseif ($pratica_indicador_acao) $sql->adInserir('pratica_indicador_gestao_acao', (int)$pratica_indicador_acao);
			elseif ($pratica_indicador_pratica) $sql->adInserir('pratica_indicador_gestao_pratica', (int)$pratica_indicador_pratica);
			elseif ($pratica_indicador_meta) $sql->adInserir('pratica_indicador_gestao_meta', (int)$pratica_indicador_meta);
			elseif ($pratica_indicador_canvas) $sql->adInserir('pratica_indicador_gestao_canvas', (int)$pratica_indicador_canvas);
			elseif ($pratica_indicador_risco) $sql->adInserir('pratica_indicador_gestao_risco', (int)$pratica_indicador_risco);
			elseif ($pratica_indicador_risco_resposta) $sql->adInserir('pratica_indicador_gestao_risco_resposta', (int)$pratica_indicador_risco_resposta);
			
			elseif ($pratica_indicador_indicador) $sql->adInserir('pratica_indicador_gestao_semelhante', (int)$pratica_indicador_indicador);
			
			elseif ($pratica_indicador_calendario) $sql->adInserir('pratica_indicador_gestao_calendario', (int)$pratica_indicador_calendario);
			elseif ($pratica_indicador_monitoramento) $sql->adInserir('pratica_indicador_gestao_monitoramento', (int)$pratica_indicador_monitoramento);
			elseif ($pratica_indicador_ata) $sql->adInserir('pratica_indicador_gestao_ata', (int)$pratica_indicador_ata);
			elseif ($pratica_indicador_mswot) $sql->adInserir('pratica_indicador_gestao_mswot', (int)$pratica_indicador_mswot);
			elseif ($pratica_indicador_swot) $sql->adInserir('pratica_indicador_gestao_swot', (int)$pratica_indicador_swot);
			elseif ($pratica_indicador_operativo) $sql->adInserir('pratica_indicador_gestao_operativo', (int)$pratica_indicador_operativo);
			elseif ($pratica_indicador_instrumento) $sql->adInserir('pratica_indicador_gestao_instrumento', (int)$pratica_indicador_instrumento);
			elseif ($pratica_indicador_recurso) $sql->adInserir('pratica_indicador_gestao_recurso', (int)$pratica_indicador_recurso);
			elseif ($pratica_indicador_problema) $sql->adInserir('pratica_indicador_gestao_problema', (int)$pratica_indicador_problema);
			elseif ($pratica_indicador_demanda) $sql->adInserir('pratica_indicador_gestao_demanda', (int)$pratica_indicador_demanda);
			elseif ($pratica_indicador_programa) $sql->adInserir('pratica_indicador_gestao_programa', (int)$pratica_indicador_programa);
			elseif ($pratica_indicador_licao) $sql->adInserir('pratica_indicador_gestao_licao', (int)$pratica_indicador_licao);
			elseif ($pratica_indicador_evento) $sql->adInserir('pratica_indicador_gestao_evento', (int)$pratica_indicador_evento);
			elseif ($pratica_indicador_link) $sql->adInserir('pratica_indicador_gestao_link', (int)$pratica_indicador_link);
			elseif ($pratica_indicador_avaliacao) $sql->adInserir('pratica_indicador_gestao_avaliacao', (int)$pratica_indicador_avaliacao);
			elseif ($pratica_indicador_tgn) $sql->adInserir('pratica_indicador_gestao_tgn', (int)$pratica_indicador_tgn);
			elseif ($pratica_indicador_brainstorm) $sql->adInserir('pratica_indicador_gestao_brainstorm', (int)$pratica_indicador_brainstorm);
			elseif ($pratica_indicador_gut) $sql->adInserir('pratica_indicador_gestao_gut', (int)$pratica_indicador_gut);
			elseif ($pratica_indicador_causa_efeito) $sql->adInserir('pratica_indicador_gestao_causa_efeito', (int)$pratica_indicador_causa_efeito);
			elseif ($pratica_indicador_arquivo) $sql->adInserir('pratica_indicador_gestao_arquivo', (int)$pratica_indicador_arquivo);
			elseif ($pratica_indicador_forum) $sql->adInserir('pratica_indicador_gestao_forum', (int)$pratica_indicador_forum);
			elseif ($pratica_indicador_checklist) $sql->adInserir('pratica_indicador_gestao_checklist', (int)$pratica_indicador_checklist);
			elseif ($pratica_indicador_agenda) $sql->adInserir('pratica_indicador_gestao_agenda', (int)$pratica_indicador_agenda);
			elseif ($pratica_indicador_agrupamento) $sql->adInserir('pratica_indicador_gestao_agrupamento', (int)$pratica_indicador_agrupamento);
			elseif ($pratica_indicador_patrocinador) $sql->adInserir('pratica_indicador_gestao_patrocinador', (int)$pratica_indicador_patrocinador);
			elseif ($pratica_indicador_template) $sql->adInserir('pratica_indicador_gestao_template', (int)$pratica_indicador_template);
			elseif ($pratica_indicador_painel) $sql->adInserir('pratica_indicador_gestao_painel', (int)$pratica_indicador_painel);
			elseif ($pratica_indicador_painel_odometro) $sql->adInserir('pratica_indicador_gestao_painel_odometro', (int)$pratica_indicador_painel_odometro);
			elseif ($pratica_indicador_painel_composicao) $sql->adInserir('pratica_indicador_gestao_painel_composicao', (int)$pratica_indicador_painel_composicao);
			elseif ($pratica_indicador_tr) $sql->adInserir('pratica_indicador_gestao_tr', (int)$pratica_indicador_tr);
			elseif ($pratica_indicador_me) $sql->adInserir('pratica_indicador_gestao_me', (int)$pratica_indicador_me);
			elseif ($pratica_indicador_acao_item) $sql->adInserir('pratica_indicador_gestao_acao_item', (int)$pratica_indicador_acao_item);
			elseif ($pratica_indicador_beneficio) $sql->adInserir('pratica_indicador_gestao_beneficio', (int)$pratica_indicador_beneficio);
			elseif ($pratica_indicador_painel_slideshow) $sql->adInserir('pratica_indicador_gestao_painel_slideshow', (int)$pratica_indicador_painel_slideshow);
			elseif ($pratica_indicador_projeto_viabilidade) $sql->adInserir('pratica_indicador_gestao_projeto_viabilidade', (int)$pratica_indicador_projeto_viabilidade);
			elseif ($pratica_indicador_projeto_abertura) $sql->adInserir('pratica_indicador_gestao_projeto_abertura', (int)$pratica_indicador_projeto_abertura);
			elseif ($pratica_indicador_plano_gestao) $sql->adInserir('pratica_indicador_gestao_plano_gestao', (int)$pratica_indicador_plano_gestao);
			elseif ($indicador_ssti) $sql->adInserir('pratica_indicador_gestao_ssti', (int)$indicador_ssti);
			elseif ($indicador_laudo) $sql->adInserir('pratica_indicador_gestao_laudo', (int)$indicador_laudo);
			elseif ($indicador_trelo) $sql->adInserir('pratica_indicador_gestao_trelo', (int)$indicador_trelo);
			elseif ($indicador_trelo_cartao) $sql->adInserir('pratica_indicador_gestao_trelo_cartao', (int)$indicador_trelo_cartao);
			elseif ($indicador_pdcl) $sql->adInserir('pratica_indicador_gestao_pdcl', (int)$indicador_pdcl);
			elseif ($indicador_pdcl_item) $sql->adInserir('pratica_indicador_gestao_pdcl_item', (int)$indicador_pdcl_item);
			
			$sql->adInserir('pratica_indicador_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($pratica_indicador_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($pratica_indicador_id=0, $uuid='', $pratica_indicador_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('pratica_indicador_gestao');
	$sql->adOnde('pratica_indicador_gestao_id='.(int)$pratica_indicador_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($pratica_indicador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($pratica_indicador_id=0, $uuid=''){	
	$saida=atualizar_gestao($pratica_indicador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($pratica_indicador_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('pratica_indicador_gestao');
	$sql->adCampo('pratica_indicador_gestao.*');
	if ($uuid) $sql->adOnde('pratica_indicador_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$pratica_indicador_id);	
	$sql->adOrdem('pratica_indicador_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_indicador_gestao_ordem'].', '.$gestao_data['pratica_indicador_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_indicador_gestao_ordem'].', '.$gestao_data['pratica_indicador_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_indicador_gestao_ordem'].', '.$gestao_data['pratica_indicador_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_indicador_gestao_ordem'].', '.$gestao_data['pratica_indicador_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['pratica_indicador_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_indicador_gestao_tarefa']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_indicador_gestao_projeto']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_indicador_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['pratica_indicador_gestao_tema']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_indicador_gestao_objetivo']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_indicador_gestao_fator']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_indicador_gestao_estrategia']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_indicador_gestao_meta']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_indicador_gestao_pratica']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_indicador_gestao_acao']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_indicador_gestao_canvas']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['pratica_indicador_gestao_risco']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_indicador_gestao_risco_resposta']).'</td>';
		
		elseif ($gestao_data['pratica_indicador_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['pratica_indicador_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['pratica_indicador_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['pratica_indicador_gestao_calendario']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_indicador_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['pratica_indicador_gestao_ata']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['pratica_indicador_gestao_mswot']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['pratica_indicador_gestao_swot']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_indicador_gestao_operativo']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_indicador_gestao_instrumento']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_indicador_gestao_recurso']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['pratica_indicador_gestao_problema']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_indicador_gestao_demanda']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['pratica_indicador_gestao_programa']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_indicador_gestao_licao']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_indicador_gestao_evento']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['pratica_indicador_gestao_link']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_indicador_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_indicador_gestao_tgn']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['pratica_indicador_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['pratica_indicador_gestao_gut']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['pratica_indicador_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_indicador_gestao_arquivo']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['pratica_indicador_gestao_forum']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_indicador_gestao_checklist']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['pratica_indicador_gestao_agenda']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['pratica_indicador_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_indicador_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['pratica_indicador_gestao_template']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['pratica_indicador_gestao_painel']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_indicador_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['pratica_indicador_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['pratica_indicador_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['pratica_indicador_gestao_tr']).'</td>';	
		elseif ($gestao_data['pratica_indicador_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['pratica_indicador_gestao_me']).'</td>';	
		elseif ($gestao_data['pratica_indicador_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['pratica_indicador_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['pratica_indicador_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['pratica_indicador_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['pratica_indicador_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['pratica_indicador_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['pratica_indicador_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['pratica_indicador_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['pratica_indicador_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['pratica_indicador_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['pratica_indicador_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['pratica_indicador_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['pratica_indicador_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['pratica_indicador_gestao_ssti']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['pratica_indicador_gestao_laudo']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['pratica_indicador_gestao_trelo']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['pratica_indicador_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['pratica_indicador_gestao_pdcl']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['pratica_indicador_gestao_pdcl_item']).'</td>';

		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['pratica_indicador_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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

function editar_filtro($pratica_indicador_filtro_id=null){
	$social=$Aplic->modulo_ativo('social');
	
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_filtro');
	$sql->adCampo('pratica_indicador_filtro.*');
	$sql->adOnde('pratica_indicador_filtro_id = '.(int)$pratica_indicador_filtro_id);
	$linha = $sql->linha();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	
	$objResposta->assign("pratica_indicador_filtro_id","value", ($pratica_indicador_filtro_id ? $pratica_indicador_filtro_id : null));
	$objResposta->assign("pratica_indicador_filtro_status","value", ($linha['pratica_indicador_filtro_status'] ? utf8_encode($linha['pratica_indicador_filtro_status']) : ''));
	$tarefa_tipos=vetor_campo_sistema('TipoTarefa', $linha['pratica_indicador_filtro_tipo']);
	$objResposta->assign("combo_tarefa_tipo","innerHTML", utf8_encode(selecionaVetor($tarefa_tipos, 'pratica_indicador_filtro_tipo', 'class="texto" size=1 style="width:284px;" onchange="mudar_tarefa_tipo();"', $linha['pratica_indicador_filtro_tipo'])));
	$objResposta->assign("pratica_indicador_filtro_prioridade","value", ($linha['pratica_indicador_filtro_prioridade'] ? utf8_encode($linha['pratica_indicador_filtro_prioridade']) : ''));
	$objResposta->assign("pratica_indicador_filtro_setor","value",  ($linha['pratica_indicador_filtro_setor'] ? utf8_encode($linha['pratica_indicador_filtro_setor']) : ''));
	
	$segmento=array('' => '');
	if ($linha['pratica_indicador_filtro_segmento']){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaSegmento\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$linha['pratica_indicador_filtro_setor'].'\'');
		$sql->adOrdem('sisvalor_valor');
		$segmento+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	$objResposta->assign("combo_segmento_tarefa","innerHTML", utf8_encode(selecionaVetor($segmento, 'pratica_indicador_filtro_segmento', 'style="width:284px;" class="texto" onchange="mudar_intervencao_tarefa();"', $linha['pratica_indicador_filtro_segmento'])));

	$intervencao=array('' => '');
	if ($linha['pratica_indicador_filtro_intervencao']){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaIntervencao\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$linha['pratica_indicador_filtro_segmento'].'\'');
		$sql->adOrdem('sisvalor_valor');
		$intervencao+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	$objResposta->assign("combo_intervencao_tarefa","innerHTML", utf8_encode(selecionaVetor($intervencao, 'pratica_indicador_filtro_intervencao', 'style="width:284px;" class="texto" onchange="mudar_tipo_intervencao_tarefa();"', $linha['pratica_indicador_filtro_intervencao'])));

	$tipo_intervencao=array('' => '');
	if ($linha['pratica_indicador_filtro_tipo_intervencao']){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaTipoIntervencao\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$linha['pratica_indicador_filtro_intervencao'].'\'');
		$sql->adOrdem('sisvalor_valor');
		$tipo_intervencao+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	$objResposta->assign("combo_tipo_intervencao_tarefa","innerHTML", utf8_encode(selecionaVetor($tipo_intervencao, 'pratica_indicador_filtro_tipo_intervencao', 'style="width:284px;" class="texto"', $linha['pratica_indicador_filtro_tipo_intervencao'])));

	if ($social) {
		$objResposta->assign("pratica_indicador_filtro_social","value", ($linha['pratica_indicador_filtro_social'] ? utf8_encode($linha['pratica_indicador_filtro_social']) : ''));
		require_once BASE_DIR.'/modulos/social/social.class.php';
		$objResposta->assign("acao_combo_tarefa","innerHTML", utf8_encode(selecionar_acao_para_ajax($linha['pratica_indicador_filtro_social'], 'pratica_indicador_filtro_acao', 'size="1" style="width:284px;" class="texto"', '', $linha['pratica_indicador_filtro_acao'], false)));
		}

	$objResposta->assign("pratica_indicador_filtro_estado","value", ($linha['pratica_indicador_filtro_estado'] ? utf8_encode($linha['pratica_indicador_filtro_estado']) : ''));
	$objResposta->assign("combo_cidade_tarefa","innerHTML", utf8_encode(selecionar_cidades_para_ajax($linha['pratica_indicador_filtro_estado'], 'pratica_indicador_filtro_cidade', 'class="texto" '.($social ? 'onchange="mudar_comunidades_tarefa()"' : '').' style="width:284px;"', '', $linha['pratica_indicador_filtro_cidade'], true, false)));
	if ($social) $objResposta->assign("combo_comunidade_tarefa","innerHTML", utf8_encode(selecionar_comunidade_para_ajax($linha['pratica_indicador_filtro_cidade'],'pratica_indicador_filtro_comunidade', 'class="texto" style="width:284px;"', '', $linha['pratica_indicador_filtro_comunidade'], false)));
	$objResposta->assign("pratica_indicador_filtro_texto","value", ($linha['pratica_indicador_filtro_texto'] ? utf8_encode($linha['pratica_indicador_filtro_texto']) : ''));

	return $objResposta;
	}
$xajax->registerFunction("editar_filtro");



function excluir_filtro($pratica_indicador_filtro_id, $pratica_indicador_id=null, $uuid=null){
	$sql = new BDConsulta;	
	$sql->setExcluir('pratica_indicador_filtro');
	$sql->adOnde('pratica_indicador_filtro_id = '.(int)$pratica_indicador_filtro_id);
	$sql->exec();
	$sql->limpar();
	$saida=exibir_filtros($pratica_indicador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_filtros',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_filtro");

function filtros($pratica_indicador_id=null, $uuid=null){
	$saida=exibir_filtros($pratica_indicador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_filtros',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("filtros");

function exibir_filtros($pratica_indicador_id=null, $uuid=null){
	global $Aplic, $config;
	if (!$Aplic->profissional) return;
	$sql = new BDConsulta;
	$sql->adTabela('pratica_indicador_filtro');
	$sql->adCampo('pratica_indicador_filtro.*');
	if ($pratica_indicador_id) $sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
	else $sql->adOnde('uuid = \''.$uuid.'\'');
	$filtros = $sql->lista();
	$sql->limpar();
	$saida='';
	if (count($filtros)){
	
		$social=$Aplic->modulo_ativo('social');
	
		$saida.= '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Filtro</th><th></th></tr>';
		foreach($filtros as $linha) {
			$saida.= '<tr><td><table cellpadding=0 cellspacing=0 width="100%">';
			if ($linha['pratica_indicador_filtro_status'] && !isset($status_tarefa)) $status_tarefa = getSisValor('StatusTarefa');
			if ($linha['pratica_indicador_filtro_status'] && isset($status_tarefa[$linha['pratica_indicador_filtro_status']])) $saida.='<tr><td align=right width="90">Status:</td><td>'.$status_tarefa[$linha['pratica_indicador_filtro_status']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_tipo']) $saida.='<tr><td align=right width="90">Tipo de '.$config['tarefa'].':</td><td>'.getSisValorCampo('TipoTarefa', $linha['pratica_indicador_filtro_tipo']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_prioridade'] && !isset($prioridade_tarefa)) $prioridade_tarefa = getSisValor('PrioridadeTarefa');
			if ($linha['pratica_indicador_filtro_prioridade'] && isset($prioridade_tarefa[$linha['pratica_indicador_filtro_prioridade']])) $saida.='<tr><td align=right width="90">Prioridade:</td><td>'.$prioridade_tarefa[$linha['pratica_indicador_filtro_prioridade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_setor']) $saida.='<tr><td align=right width="90">Setor:</td><td>'.getSisValorCampo('TarefaSetor', $linha['pratica_indicador_filtro_setor']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_segmento']) $saida.='<tr><td align=right width="90">Segmento:</td><td>'.getSisValorCampo('TarefaSegmento', $linha['pratica_indicador_filtro_segmento']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_intervencao']) $saida.='<tr><td align=right width="90">Intervenção:</td><td>'.getSisValorCampo('TarefaIntervencao', $linha['pratica_indicador_filtro_intervencao']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_tipo_intervencao']) $saida.='<tr><td align=right width="90">Tipo:</td><td>'.getSisValorCampo('TarefaTipoIntervencao', $linha['pratica_indicador_filtro_tipo_intervencao']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_social'] && !isset($programa_social)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social', 'social', 'social_id=pratica_indicador_filtro_social');
				$sql->adCampo('social_id, social_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$programa_social = $sql->listaVetorChave('social_id', 'social_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_social'] && isset($programa_social[$linha['pratica_indicador_filtro_social']])) $saida.='<tr><td align=right width="90">Programa:</td><td>'.$programa_social[$linha['pratica_indicador_filtro_social']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_acao'] && !isset($acao_social)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=pratica_indicador_filtro_acao');
				$sql->adCampo('social_acao_id, social_acao_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$acao_social = $sql->listaVetorChave('social_acao_id', 'social_acao_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_acao'] && isset($acao_social[$linha['pratica_indicador_filtro_acao']])) $saida.='<tr><td align=right width="90">Ação:</td><td>'.$acao_social[$linha['pratica_indicador_filtro_acao']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_estado'] && !isset($estado)) {
				$sql->adTabela('estado');
				$sql->adCampo('estado_sigla, estado_nome');
				$sql->adOrdem('estado_nome');
				$estado=$sql->listaVetorChave('estado_sigla', 'estado_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_estado'] && isset($estado[$linha['pratica_indicador_filtro_estado']])) $saida.='<tr><td align=right width="90">Estado:</td><td>'.$estado[$linha['pratica_indicador_filtro_estado']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_cidade'] && !isset($municipio)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('municipios', 'municipios', 'municipios.municipio_id=pratica_indicador_filtro_cidade');
				$sql->adCampo('municipio_id, municipio_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$municipio = $sql->listaVetorChave('municipio_id', 'municipio_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_cidade'] && isset($municipio[$linha['pratica_indicador_filtro_cidade']])) $saida.='<tr><td align=right width="90">Município:</td><td>'.$municipio[$linha['pratica_indicador_filtro_cidade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_comunidade'] && !isset($comunidade)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=pratica_indicador_filtro_comunidade');
				$sql->adCampo('social_comunidade_id, social_comunidade_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$comunidade = $sql->listaVetorChave('social_comunidade_id', 'social_comunidade_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_comunidade'] && isset($comunidade[$linha['pratica_indicador_filtro_comunidade']])) $saida.='<tr><td align=right width="90">Comunidade:</td><td>'.$comunidade[$linha['pratica_indicador_filtro_comunidade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_texto']) $saida.='<tr><td align=right width="90">Texto:</td><td>'.$linha['pratica_indicador_filtro_texto'].'</td></tr>';
			$saida.= '</table></td><td><a href="javascript: void(0);" onclick="editar_filtro('.$linha['pratica_indicador_filtro_id'].');">'.imagem('icones/editar.gif', 'Editar Filtro', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este filtro.').'</a><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este filtro?\')) {excluir_filtro('.$linha['pratica_indicador_filtro_id'].');}">'.imagem('icones/remover.png', 'Excluir Filtro', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este filtro.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;	
	}


function incluir_filtro(
	$filtro_id=null, 
	$indicador=null, 
	$uuid=null, 
	$filtro_status=null, 
	$prioridade=null, 
	$tipo=null, 
	$setor=null, 
	$segmento=null, 
	$intervencao=null, 
	$tipo_intervencao=null, 
	$social=null, 
	$acao=null, 
	$estado=null, 
	$cidade=null, 
	$comunidade=null, 
	$texto=null){
	
	global $Aplic;
	
	if (!$Aplic->profissional) return;
	
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_filtro');
	if (!$filtro_id){
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica_indicador_filtro_indicador', (int)$indicador);
		
		if ($filtro_status) $sql->adInserir('pratica_indicador_filtro_status', previnirXSS(utf8_decode($filtro_status)));
		if ($prioridade) $sql->adInserir('pratica_indicador_filtro_prioridade', previnirXSS(utf8_decode($prioridade)));
		if ($tipo) $sql->adInserir('pratica_indicador_filtro_tipo', previnirXSS(utf8_decode($tipo)));
		if ($setor) $sql->adInserir('pratica_indicador_filtro_setor', previnirXSS(utf8_decode($setor)));
		if ($segmento) $sql->adInserir('pratica_indicador_filtro_segmento', previnirXSS(utf8_decode($segmento)));
		if ($intervencao) $sql->adInserir('pratica_indicador_filtro_intervencao', previnirXSS(utf8_decode($intervencao)));
		if ($tipo_intervencao) $sql->adInserir('pratica_indicador_filtro_tipo_intervencao', previnirXSS(utf8_decode($tipo_intervencao)));
		if ($social) $sql->adInserir('pratica_indicador_filtro_social', previnirXSS(utf8_decode($social)));
		if ($acao) $sql->adInserir('pratica_indicador_filtro_acao', previnirXSS(utf8_decode($acao)));
		if ($estado) $sql->adInserir('pratica_indicador_filtro_estado', previnirXSS(utf8_decode($estado)));
		if ($cidade) $sql->adInserir('pratica_indicador_filtro_cidade', previnirXSS(utf8_decode($cidade)));
		if ($comunidade) $sql->adInserir('pratica_indicador_filtro_comunidade', previnirXSS(utf8_decode($comunidade)));
		if ($texto) $sql->adInserir('pratica_indicador_filtro_texto', previnirXSS(utf8_decode($texto)));
		$sql->exec();
		$sql->limpar();
		}
	else{
		$sql->adAtualizar('pratica_indicador_filtro_status', previnirXSS(utf8_decode(($filtro_status ? $filtro_status : null))));
		$sql->adAtualizar('pratica_indicador_filtro_prioridade', previnirXSS(utf8_decode(($prioridade ? $prioridade : null))));
		$sql->adAtualizar('pratica_indicador_filtro_tipo', previnirXSS(utf8_decode(($tipo ? $tipo : null))));
		$sql->adAtualizar('pratica_indicador_filtro_setor', previnirXSS(utf8_decode(($setor ? $setor : null))));
		$sql->adAtualizar('pratica_indicador_filtro_segmento', previnirXSS(utf8_decode(($segmento ? $segmento : null))));
		$sql->adAtualizar('pratica_indicador_filtro_intervencao', previnirXSS(utf8_decode(($intervencao ? $intervencao : null))));
		$sql->adAtualizar('pratica_indicador_filtro_tipo_intervencao', previnirXSS(utf8_decode(($tipo_intervencao ? $tipo_intervencao : null))));
		$sql->adAtualizar('pratica_indicador_filtro_social', previnirXSS(utf8_decode(($social ? $social : null))));
		$sql->adAtualizar('pratica_indicador_filtro_acao', previnirXSS(utf8_decode(($acao ? $acao : null))));
		$sql->adAtualizar('pratica_indicador_filtro_estado', previnirXSS(utf8_decode(($estado ? $estado : null))));
		$sql->adAtualizar('pratica_indicador_filtro_cidade', previnirXSS(utf8_decode(($cidade ? $cidade : null))));
		$sql->adAtualizar('pratica_indicador_filtro_comunidade', previnirXSS(utf8_decode(($comunidade ? $comunidade : null))));
		$sql->adAtualizar('pratica_indicador_filtro_texto', previnirXSS(utf8_decode(($texto ? $texto : null))));
		$sql->adOnde('pratica_indicador_filtro_id = '.(int)$filtro_id);
		$sql->exec();
		$sql->limpar();
		}
	$saida=exibir_filtros($indicador, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_filtros',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_filtro");




function acao_ajax($social_id=0){
	$saida=selecionar_acao_para_ajax($social_id, 'pratica_indicador_filtro_acao', 'size="1" class="texto" style="width:284px;');
	$objResposta = new xajaxResponse();
	$objResposta->assign("acao_combo_tarefa","innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("acao_ajax");	

function selecionar_comunidade_ajax($municipio_id='', $campo='', $posicao='', $script='', $vazio='', $tarefa_comunidade=0){
	$saida=selecionar_comunidade_para_ajax($municipio_id, $campo, $script, $vazio, $tarefa_comunidade);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("selecionar_comunidade_ajax");

function mudar_tarefa_tipo($tarefa_tipo='', $campo='', $posicao='', $script=''){
	$vetor=vetor_campo_sistema('TipoTarefa',$tarefa_tipo, true);
	$saida=selecionaVetor($vetor, $campo, $script, $tarefa_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_tarefa_tipo");	

function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("selecionar_cidades_ajax");	



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

function qnt_metas($pratica_indicador_id=null, $uuid=''){
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_meta');
	$sql->adCampo('count(pratica_indicador_meta_id)');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_meta_indicador = '.(int)$pratica_indicador_id);
	$qnt = $sql->resultado();
	$sql->limpar();
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("qnt_metas","value", (int)$qnt);
	return $objResposta;
	}
$xajax->registerFunction("qnt_metas");




function editar_meta($pratica_indicador_meta_id=null){
	
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_meta');
	$sql->adCampo('formatar_data(pratica_indicador_meta_data, "%d/%m/%Y") as data, formatar_data(pratica_indicador_meta_data_meta, "%d/%m/%Y") as data_meta');
	$sql->adCampo('pratica_indicador_meta.*');
	$sql->adOnde('pratica_indicador_meta_id = '.(int)$pratica_indicador_meta_id);
	$linha = $sql->linha();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("pratica_indicador_meta_id","value", $pratica_indicador_meta_id);
	$objResposta->assign("pratica_indicador_meta_data","value", $linha['pratica_indicador_meta_data']);
	$objResposta->assign("data_inicio","value", $linha['data']);
	$objResposta->assign("pratica_indicador_meta_data_meta","value", $linha['pratica_indicador_meta_data_meta']);
	$objResposta->assign("data","value", $linha['data_meta']);
	$objResposta->assign("pratica_indicador_meta_valor_meta","value", ($linha['pratica_indicador_meta_valor_meta']!=null ? number_format($linha['pratica_indicador_meta_valor_meta'], 2, ',', '.') : ''));
	$objResposta->assign("pratica_indicador_meta_proporcao","checked", ($linha['pratica_indicador_meta_proporcao'] ? true : false));
	$objResposta->assign("pratica_indicador_meta_valor_meta_boa","value", ($linha['pratica_indicador_meta_valor_meta_boa']!=null ? number_format($linha['pratica_indicador_meta_valor_meta_boa'], 2, ',', '.') : ''));
	$objResposta->assign("pratica_indicador_meta_valor_meta_regular","value", ($linha['pratica_indicador_meta_valor_meta_regular']!=null ? number_format($linha['pratica_indicador_meta_valor_meta_regular'], 2, ',', '.') : ''));
	$objResposta->assign("pratica_indicador_meta_valor_meta_ruim","value", ($linha['pratica_indicador_meta_valor_meta_ruim']!=null ? number_format($linha['pratica_indicador_meta_valor_meta_ruim'], 2, ',', '.') : ''));
	$objResposta->assign("pratica_indicador_meta_valor_referencial","value", ($linha['pratica_indicador_meta_valor_referencial']!=null ? number_format($linha['pratica_indicador_meta_valor_referencial'], 2, ',', '.') : ''));
	return $objResposta;
	}
$xajax->registerFunction("editar_meta");

function excluir_meta($pratica_indicador_meta_id=null, $pratica_indicador_meta_indicador=null, $uuid=null){
	$sql = new BDConsulta;	
	$sql->setExcluir('pratica_indicador_meta');
	$sql->adOnde('pratica_indicador_meta_id = '.(int)$pratica_indicador_meta_id);
	$sql->exec();
	$sql->limpar();
	$saida=exibe_metas($pratica_indicador_meta_indicador, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("metas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_meta");

function incluir_meta(
	$pratica_indicador_meta_id=null, 
	$pratica_indicador_meta_indicador=null, 
	$uuid=null, 
	$pratica_indicador_meta_data=null, 
	$pratica_indicador_meta_valor_referencial=null, 
	$pratica_indicador_meta_valor_meta=null, 
	$pratica_indicador_meta_proporcao=null, 
	$pratica_indicador_meta_valor_meta_boa=null, 
	$pratica_indicador_meta_valor_meta_regular=null, 
	$pratica_indicador_meta_valor_meta_ruim=null, 
	$pratica_indicador_meta_data_meta=null){

	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_meta');
	if (!$pratica_indicador_meta_id){
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica_indicador_meta_indicador', (int)$pratica_indicador_meta_indicador);
		if ($pratica_indicador_meta_valor_referencial != '') $sql->adInserir('pratica_indicador_meta_valor_referencial', float_americano($pratica_indicador_meta_valor_referencial));
		$sql->adInserir('pratica_indicador_meta_valor_meta', float_americano($pratica_indicador_meta_valor_meta));
		$sql->adInserir('pratica_indicador_meta_proporcao', ($pratica_indicador_meta_proporcao ? 1 : 0));
		if ($pratica_indicador_meta_valor_meta_boa != '') $sql->adInserir('pratica_indicador_meta_valor_meta_boa', float_americano($pratica_indicador_meta_valor_meta_boa));
		if ($pratica_indicador_meta_valor_meta_regular != '') $sql->adInserir('pratica_indicador_meta_valor_meta_regular', float_americano($pratica_indicador_meta_valor_meta_regular));
		if ($pratica_indicador_meta_valor_meta_ruim != '') $sql->adInserir('pratica_indicador_meta_valor_meta_ruim', float_americano($pratica_indicador_meta_valor_meta_ruim));
		$sql->adInserir('pratica_indicador_meta_data_meta', $pratica_indicador_meta_data_meta);
		$sql->adInserir('pratica_indicador_meta_data', $pratica_indicador_meta_data);
		$sql->exec();
		$sql->limpar();
		}
	else{
		$sql->adAtualizar('pratica_indicador_meta_valor_referencial', ($pratica_indicador_meta_valor_referencial !='' ? float_americano($pratica_indicador_meta_valor_referencial) : null));
		$sql->adAtualizar('pratica_indicador_meta_valor_meta', float_americano($pratica_indicador_meta_valor_meta));
		$sql->adAtualizar('pratica_indicador_meta_proporcao', ($pratica_indicador_meta_proporcao ? 1 : 0));
		$sql->adAtualizar('pratica_indicador_meta_valor_meta_boa', ($pratica_indicador_meta_valor_meta_boa !='' ? float_americano($pratica_indicador_meta_valor_meta_boa) : null));
		$sql->adAtualizar('pratica_indicador_meta_valor_meta_regular', ($pratica_indicador_meta_valor_meta_regular !='' ? float_americano($pratica_indicador_meta_valor_meta_regular) : null));
		$sql->adAtualizar('pratica_indicador_meta_valor_meta_ruim', ($pratica_indicador_meta_valor_meta_ruim !='' ? float_americano($pratica_indicador_meta_valor_meta_ruim) : null));
		$sql->adAtualizar('pratica_indicador_meta_data_meta', $pratica_indicador_meta_data_meta);
		$sql->adAtualizar('pratica_indicador_meta_data', $pratica_indicador_meta_data);
		$sql->adOnde('pratica_indicador_meta_id = '.$pratica_indicador_meta_id);
		$sql->exec();
		$sql->limpar();
		}
	$saida=exibe_metas($pratica_indicador_meta_indicador, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("metas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("incluir_meta");


function exibe_metas($pratica_indicador_id=null, $uuid=''){
	global $Aplic;
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_meta');
	$sql->adCampo('formatar_data(pratica_indicador_meta_data, "%d/%m/%Y") as data, formatar_data(pratica_indicador_meta_data_meta, "%d/%m/%Y") as data_meta');
	$sql->adCampo('pratica_indicador_meta_id, pratica_indicador_meta_valor_referencial, pratica_indicador_meta_valor_meta, pratica_indicador_meta_proporcao, pratica_indicador_meta_valor_meta_boa, pratica_indicador_meta_valor_meta_regular, pratica_indicador_meta_valor_meta_ruim');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_meta_indicador = '.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_meta_data');
	$metas = $sql->lista();
	
	$sql->limpar();
	
	$saida='';
	if (count($metas)){
		$saida.= '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Meta</th>'.($Aplic->profissional ? '<th>Ciclo Anterior</th><th>Bom</th><th>Regular</th><th>Ruim</th>' : '').'<th>Início</th><th>Limite</th><th>Referencial</th><th></th></tr>';
		foreach($metas as $linha) {
			$saida.= '<tr>';
			$saida.= '<td align=right>'.number_format($linha['pratica_indicador_meta_valor_meta'], 2, ',', '.').'</td>';
			if ($Aplic->profissional){
				$saida.= '<td align=center>'.($linha['pratica_indicador_meta_proporcao'] ? 'X' : '&nbsp;').'</td>';
				$saida.= '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_boa'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_boa'], 2, ',', '.') : '&nbsp;').'</td>';
				$saida.= '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_regular'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_regular'], 2, ',', '.') : '&nbsp;').'</td>';
				$saida.= '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_ruim'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_ruim'], 2, ',', '.') : '&nbsp;').'</td>';
				}
			$saida.= '<td>'.$linha['data'].'</td><td>'.$linha['data_meta'].'</td>';
			$saida.= '<td>'.($linha['pratica_indicador_meta_valor_referencial'] != null ? number_format($linha['pratica_indicador_meta_valor_referencial'], 2, ',', '.') : '&nbsp;').'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_meta('.$linha['pratica_indicador_meta_id'].');">'.imagem('icones/editar.gif', 'Editar Meta', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta meta.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta meta?\')) {excluir_meta('.$linha['pratica_indicador_meta_id'].');}">'.imagem('icones/remover.png', 'Excluir Meta', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta meta.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.='</table>';
		}
	return $saida;
	}

function marcar_marcador($pratica_indicador_id=0, $uuid='', $pratica_marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	$sql->setExcluir('pratica_indicador_nos_marcadores');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
	$sql->adOnde('ano = '.(int)$ano);
	$sql->adOnde('pratica_marcador_id = '.(int)$pratica_marcador_id);
	$sql->exec();
	$sql->limpar();
	
	if ($marcado){
		$sql->adTabela('pratica_indicador_nos_marcadores');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica_indicador_id', (int)$pratica_indicador_id);
		$sql->adInserir('ano', (int)$ano);
		$sql->adInserir('pratica_marcador_id', (int)$pratica_marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	}
$xajax->registerFunction("marcar_marcador");		
	
	
function mudar_indicador_tipo_ajax($indicador_tipo='', $campo='', $posicao='', $script=''){
	$vetor=vetor_campo_sistema('IndicadorTipo',$indicador_tipo, true);
	$saida=selecionaVetor($vetor, $campo, $script, $indicador_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_indicador_tipo_ajax");	

function gravar_resultados($pratica_indicador_id=0, $pratica_modelo_id=0, $campos){
	$sql = new BDConsulta;
	$campos=explode (',', $campos);
	
	if ($pratica_indicador_id && $pratica_modelo_id){
		$sql->setExcluir('pratica_indicador_nos_marcadores');
		$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_modelo_id = '.(int)$pratica_modelo_id);
		$sql->exec();
		$sql->limpar();
		foreach($campos as $chave => $pratica_marcador_id){
			if($pratica_marcador_id){
				$sql->adTabela('pratica_indicador_nos_marcadores');
				$sql->adInserir('pratica_indicador_id', (int)$pratica_indicador_id);
				$sql->adInserir('pratica_marcador_id', (int)$pratica_marcador_id);
				$sql->adInserir('pratica_modelo_id', (int)$pratica_modelo_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		}
	}
$xajax->registerFunction("gravar_resultados");	

function mudar_pauta($pratica_indicador_id=0, $uuid='', $pratica_modelo_id=0, $ano=''){
	global $config;
	
	$sql = new BDConsulta;
	
	$sql->adTabela('pratica_criterio');
	$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=1');
	$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
	$sql->limpar();

	$sql->adTabela('pratica_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_oculto');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=1');
	$itens=$sql->ListaChaveSimples('pratica_item_id');
	$sql->limpar();
	
	$sql->adTabela('pratica_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador.pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra, pratica_marcador_evidencia, pratica_marcador_orientacao');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=1');
	$sql->adOrdem('pratica_criterio_numero');
	$sql->adOrdem('pratica_item_numero');
	$sql->adOrdem('pratica_marcador_letra');
	$marcadores=$sql->Lista();
	$sql->limpar();
	
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_nos_marcadores.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador.pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_indicador_nos_marcadores.uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_nos_marcadores.pratica_indicador_id = '.(int)$pratica_indicador_id);	
	$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
	$lista_marcadores=$sql->Lista();
	$sql->limpar();
	
	$sql->adTabela('pratica_indicador_complemento');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_complemento_indicador=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_complemento_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_indicador_complemento_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_complemento_indicador = '.(int)$pratica_indicador_id);
	$sql->adOnde('pratica_indicador_complemento_ano='.(int)$ano);
	$atuais_complementos=$sql->carregarColuna();
	$sql->limpar();
	
	$sql->adTabela('pratica_indicador_evidencia');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_evidencia_indicador=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_evidencia_marcador');

	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_indicador_evidencia_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_evidencia_indicador = '.(int)$pratica_indicador_id);
	$sql->adOnde('pratica_indicador_evidencia_ano='.(int)$ano);
	$atuais_evidencias=$sql->carregarColuna();
	$sql->limpar();
	
	$atuais=array();
	foreach($lista_marcadores as $chave => $valor) $atuais[]=$valor['pratica_marcador_id'];
	$criterio_atual='';
	$item_atual='';
	$saida='<table cellpadding=0 cellspacing=1>';
	if ($marcadores && count($marcadores)) $saida.= '<tr><td align="left" colspan=2 style="white-space: nowrap"><p><b>'.ucfirst($config['marcadores']).' atendid'.$config['genero_marcador'].'s pelo indicador</b></p></td></tr>';
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			if ($criterio_atual) $saida.='</table></td></tr>';
			$criterio_atual=$dado['pratica_criterio_id'];
			$saida.= '<tr><td align="left" colspan=2 style="white-space: nowrap"><a href="javascript: void(0);" onclick="expandir_colapsar(\'criterio_'.$criterio_atual.'\')">'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</a></td></tr>';
			$saida.='<tr id="criterio_'.$criterio_atual.'"><td colspan=2><table cellpadding=0 cellspacing=0>';
			}
		if ($dado['pratica_item_id']!=$item_atual){
			$item_atual=$dado['pratica_item_id'];
			if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) $saida.='<tr><td align="left" colspan=2 style="white-space: nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</td></tr>';
			}
		$marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais));
		$complemento_marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_complementos));
		$evidencia_marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_evidencias));
		$saida.='<tr><td align="right" style="white-space: nowrap" valign="top" width=40><input name="pratica_marcador_id[]" value="'.$dado['pratica_marcador_id'].'" id="checagem_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:middle"  onclick="marcar_marcador('.$dado['pratica_marcador_id'].');" '.($marcado ? 'checked="checked"' : '').' /><b>'.$dado['pratica_marcador_letra'].'.</b></td><td><table cellpadding=0 cellspacing=0><tr style="line-height: 18px;"><td id="caixa_'.$dado['pratica_marcador_id'].'" '.($marcado ? ' style="vertical-align:top; background-color:#FFFF00;"' : 'style="vertical-align:top"').'>'.($dado['pratica_marcador_orientacao'] ? dica('Orientações', $dado['pratica_marcador_orientacao']) : '').$dado['pratica_marcador_texto'].($dado['pratica_marcador_orientacao'] ? dicaF() : '').'</td></tr></table></td></tr>';
		
		if ($dado['pratica_marcador_extra']) $saida.='<tr><td></td><td align="left" valign="top">'.dica('Complementos para a Excelência','Deverá ser marcado caso '.$config['genero_pratica'].' '.$config['pratica'].' atende os requisitos dos complementos para a excelência.').'<table cellpadding=0 cellspacing=0><tr><td style="vertical-align:top"><input name="pratica_complemento_id[]" '.($complemento_marcado ? 'checked="checked"' : '').' value="'.$dado['pratica_marcador_id'].'" id="complemento_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:top" onclick="marcar_complemento('.$dado['pratica_marcador_id'].');" /></td><td id="caixa3_'.$dado['pratica_marcador_id'].'" '.($complemento_marcado ? 'checked="checked" style="background-color:#abfeff;"' : '').'>'.$dado['pratica_marcador_extra'].'</td></tr></table>'.dicaF().'</td></tr>';
		if ($dado['pratica_marcador_evidencia']) $saida.='<tr><td></td><td align="left" valign="top">'.dica('Evidências','Deverá ser marcado caso '.$config['genero_pratica'].' '.$config['pratica'].' atende os requisitos da evidência.').'<table cellpadding=0 cellspacing=0><tr><td style="vertical-align:top"><input name="pratica_evidencia_id[]" '.($evidencia_marcado ? 'checked="checked"' : '').' value="'.$dado['pratica_marcador_id'].'" id="evidencia_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:top" onclick="marcar_evidencia('.$dado['pratica_marcador_id'].');" /></td><td  id="caixa4_'.$dado['pratica_marcador_id'].'" '.($evidencia_marcado ? 'checked="checked" style="background-color:#abffaf;"' : '').'>'.$dado['pratica_marcador_evidencia'].'</td></tr></table>'.dicaF().'</td></tr>';
		}
	if ($criterio_atual) $saida.='</table>';	
	$saida.='</table>';
	$saida=utf8_encode($saida);

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_pauta',"innerHTML", $saida);
	//mudar as legendas
	$sql->adTabela('pratica_regra_campo');
	$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
	$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_regra_campo_resultado=1');
	$sql->adOrdem('pratica_regra_campo_id');
	$lista=$sql->Lista();
	$sql->limpar();
	$vetor_existe=array(
		'pratica_indicador_tendencia',
		'pratica_indicador_favoravel',
		'pratica_indicador_superior',
		'pratica_indicador_relevante',
		'pratica_indicador_atendimento',
		'pratica_indicador_lider',
		'pratica_indicador_excelencia',
		'pratica_indicador_estrategico'
		);
	$original=array();	
	$usou=array();	
	foreach($lista as $linha){	
		if (in_array($linha['pratica_regra_campo_nome'], $vetor_existe)){
			$campo=utf8_encode(dica($linha['pratica_regra_campo_texto'], $linha['pratica_regra_campo_descricao']).'<b>'.$linha['pratica_regra_campo_texto'].'</b>:'.dicaF());
			$objResposta->assign('legenda_'.$linha['pratica_regra_campo_nome'],"innerHTML", $campo);
			$usou[$linha['pratica_regra_campo_nome']]=1;
			}
		}
	if (!isset($usou['pratica_indicador_tendencia'])) $original['pratica_indicador_tendencia']=dica('Tem Tendência','Este indicador tem tendência.').'Tem tendência:'.dicaF();	
	if (!isset($usou['pratica_indicador_favoravel'])) $original['pratica_indicador_favoravel']=dica('Tendência Favorável','Este indicador tem tendência favorável.').'Tendência favorável:'.dicaF();
	if (!isset($usou['pratica_indicador_superior'])) $original['pratica_indicador_superior']=dica('Superior ao Referêncial','Este indicador é superior ao referêncial comparativo.').'Superior ao referêncial:'.dicaF();
	if (!isset($usou['pratica_indicador_relevante'])) $original['pratica_indicador_relevante']=dica('Relevante','O grau do resultado apresentado por este indicador é importante para o alcance de '.$config['genero_objetivo'].'s ou operacional d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Relevante:'.dicaF();
	if (!isset($usou['pratica_indicador_atendimento'])) $original['pratica_indicador_atendimento']=dica('Atende a Requisitos','O nível do resultado demonstra o atendimento aos principais requisitos relacionados com necessidades e expectativas de partes interessadas.').'Atende a requisitos:'.dicaF();
	if (!isset($usou['pratica_indicador_lider'])) $original['pratica_indicador_lider']=dica('Liderança','O nível do resultado deste indicador demonstra '.$config['genero_organizacao'].' '.$config['organizacao'].' ser líder do mercado ou do setor de atuação.').'Liderança:'.dicaF();
	if (!isset($usou['pratica_indicador_excelencia'])) $original['pratica_indicador_excelencia']=dica('Referência de Excelência','O nível do resultado deste indicador demonstra ser referencial de excelência.').'Referência de excelência:'.dicaF();
	if (!isset($usou['pratica_indicador_estrategico'])) $original['pratica_indicador_estrategico']=dica('Estratégico','Este indicador é estrátégico.').'Estratégico:'.dicaF();
	foreach($original as $chave => $valor) $objResposta->assign('legenda_'.$chave,"innerHTML", utf8_encode($valor));
	return $objResposta;
	}

$xajax->registerFunction("mudar_pauta");	

function marcar_evidencia($pratica_indicador_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	if (!$marcado){
		$sql->setExcluir('pratica_indicador_evidencia');
		if ($uuid) $sql->adOnde('pratica_indicador_evidencia_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_evidencia_indicador = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_indicador_evidencia_ano = '.(int)$ano);
		$sql->adOnde('pratica_indicador_evidencia_marcador = '.(int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	else{
		//garantir que nso ira marcar duas vezes
		$sql->adTabela('pratica_indicador_evidencia');
		$sql->adCampo('count(pratica_indicador_evidencia_id)');
		if ($uuid) $sql->adOnde('pratica_indicador_evidencia_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_evidencia_indicador = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_indicador_evidencia_ano = '.(int)$ano);
		$sql->adOnde('pratica_indicador_evidencia_marcador = '.(int)$marcador_id);
		$existe=$sql->Resultado();
		$sql->limpar();
	
		if (!$existe){
			$sql->adTabela('pratica_indicador_evidencia');
			if ($uuid) $sql->adInserir('uuid', $uuid);
			else $sql->adInserir('pratica_indicador_evidencia_indicador', (int)$pratica_indicador_id);
			$sql->adInserir('pratica_indicador_evidencia_ano', (int)$ano);
			$sql->adInserir('pratica_indicador_evidencia_marcador', (int)$marcador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
$xajax->registerFunction("marcar_evidencia");	


function marcar_complemento($pratica_indicador_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	if (!$marcado){
		$sql->setExcluir('pratica_indicador_complemento');
		if ($uuid) $sql->adOnde('pratica_indicador_complemento_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_complemento_indicador = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_indicador_complemento_ano = '.(int)$ano);
		$sql->adOnde('pratica_indicador_complemento_marcador = '.(int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	else{
		//garantir que nso ira marcar duas vezes
		$sql->adTabela('pratica_indicador_complemento');
		$sql->adCampo('count(pratica_indicador_complemento_id)');
		if ($uuid) $sql->adOnde('pratica_indicador_complemento_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_complemento_indicador = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_indicador_complemento_ano = '.(int)$ano);
		$sql->adOnde('pratica_indicador_complemento_marcador = '.(int)$marcador_id);
		$existe=$sql->Resultado();
		$sql->limpar();
	
		if (!$existe){
			$sql->adTabela('pratica_indicador_complemento');
			if ($uuid) $sql->adInserir('uuid', $uuid);
			else $sql->adInserir('pratica_indicador_complemento_indicador', (int)$pratica_indicador_id);
			$sql->adInserir('pratica_indicador_complemento_ano', (int)$ano);
			$sql->adInserir('pratica_indicador_complemento_marcador', (int)$marcador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
$xajax->registerFunction("marcar_complemento");	



function exibir_combo($posicao, $tabela, $chave='', $campo='', $onde='', $ordem='', $script='', $campo_id='', $campoatual='', $campobranco=true, $tabela2='', $uniao2='', $tabela3='', $uniao3=''){
		$sql = new BDConsulta;
		$sql->adTabela($tabela);
		if ($tabela2) $sql->esqUnir($tabela2, $tabela2, $uniao2);
		if ($tabela3) $sql->esqUnir($tabela3, $tabela3, $uniao3);
		if ($chave) $sql->adCampo($chave);
		if ($campo) $sql->adCampo($campo);
		if ($onde) $sql->adOnde($onde);
		if ($ordem) $sql->adOrdem($ordem);
		$linhas=$sql->Lista();
		$sql->limpar();
		$vetor=array();
		$chave=explode('.',$chave); 
		$chave = array_pop($chave);
		if ($campobranco) $vetor[]='';
		foreach($linhas as $linha)$vetor[$linha[$chave]]=utf8_encode($linha[$campo]);
		$saida=selecionaVetor($vetor, $campo_id, $script, $campoatual);
		$objResposta = new xajaxResponse();
		$objResposta->assign($posicao,"innerHTML", $saida);
		return $objResposta;
		}
$xajax->registerFunction("exibir_combo");	

	

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");	
	
function mudar_usuario_ajax($cia_id=0, $usuario_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao=''){
	global $Aplic, $config;

	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	if ($segunda_tabela && $condicao){
		$sql->esqUnir($segunda_tabela,$segunda_tabela,$condicao);
		}
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();	
	$vetor[0]='';
	foreach((array)$linhas as $linha) {
		$vetor[$linha['usuario_id']]=utf8_encode($linha['nome_usuario']);
		}
		
	if (count($vetor)==1) $vetor[-1]='';
	$saida=selecionaVetor($vetor, $campo, $script, $usuario_id);

	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	

$xajax->registerFunction("mudar_usuario_ajax");

$xajax->processRequest();

?>