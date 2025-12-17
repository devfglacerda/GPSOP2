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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/termo_abertura_editar_ajax_pro.php';


function mudar_posicao_gestao($ordem, $projeto_abertura_gestao_id, $direcao, $projeto_abertura_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $projeto_abertura_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_abertura_gestao');
		$sql->adOnde('projeto_abertura_gestao_id != '.(int)$projeto_abertura_gestao_id);
		if ($uuid) $sql->adOnde('projeto_abertura_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_abertura_gestao_projeto_abertura = '.(int)$projeto_abertura_id);
		$sql->adOrdem('projeto_abertura_gestao_ordem');
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
			$sql->adTabela('projeto_abertura_gestao');
			$sql->adAtualizar('projeto_abertura_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_abertura_gestao_id = '.(int)$projeto_abertura_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_abertura_gestao');
					$sql->adAtualizar('projeto_abertura_gestao_ordem', $idx);
					$sql->adOnde('projeto_abertura_gestao_id = '.(int)$acao['projeto_abertura_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_abertura_gestao');
					$sql->adAtualizar('projeto_abertura_gestao_ordem', $idx + 1);
					$sql->adOnde('projeto_abertura_gestao_id = '.(int)$acao['projeto_abertura_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($projeto_abertura_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$projeto_abertura_id=0, 
	$uuid='',  
	
	$projeto_abertura_projeto=null,
	$projeto_abertura_tarefa=null,
	$projeto_abertura_perspectiva=null,
	$projeto_abertura_tema=null,
	$projeto_abertura_objetivo=null,
	$projeto_abertura_fator=null,
	$projeto_abertura_estrategia=null,
	$projeto_abertura_meta=null,
	$projeto_abertura_pratica=null,
	$projeto_abertura_acao=null,
	$projeto_abertura_canvas=null,
	$projeto_abertura_risco=null,
	$projeto_abertura_risco_resposta=null,
	$projeto_abertura_indicador=null,
	$projeto_abertura_calendario=null,
	$projeto_abertura_monitoramento=null,
	$projeto_abertura_ata=null,
	$projeto_abertura_mswot=null,
	$projeto_abertura_swot=null,
	$projeto_abertura_operativo=null,
	$projeto_abertura_instrumento=null,
	$projeto_abertura_recurso=null,
	$projeto_abertura_problema=null,
	$projeto_abertura_demanda=null,
	$projeto_abertura_programa=null,
	$projeto_abertura_licao=null,
	$projeto_abertura_evento=null,
	$projeto_abertura_link=null,
	$projeto_abertura_avaliacao=null,
	$projeto_abertura_tgn=null,
	$projeto_abertura_brainstorm=null,
	$projeto_abertura_gut=null,
	$projeto_abertura_causa_efeito=null,
	$projeto_abertura_arquivo=null,
	$projeto_abertura_forum=null,
	$projeto_abertura_checklist=null,
	$projeto_abertura_agenda=null,
	$projeto_abertura_agrupamento=null,
	$projeto_abertura_patrocinador=null,
	$projeto_abertura_template=null,
	$projeto_abertura_painel=null,
	$projeto_abertura_painel_odometro=null,
	$projeto_abertura_painel_composicao=null,
	$projeto_abertura_tr=null,
	$projeto_abertura_me=null,
	$projeto_abertura_acao_item=null,
	$projeto_abertura_beneficio=null,
	$projeto_abertura_painel_slideshow=null,
	$projeto_abertura_projeto_viabilidade=null,
	$projeto_abertura_projeto_abertura=null,
	$projeto_abertura_plano_gestao=null,
	$projeto_abertura_ssti=null,
	$projeto_abertura_laudo=null,
	$projeto_abertura_trelo=null,
	$projeto_abertura_trelo_cartao=null,
	$projeto_abertura_pdcl=null,
	$projeto_abertura_pdcl_item=null
	)
	{
	if (
		$projeto_abertura_projeto || 
		$projeto_abertura_tarefa || 
		$projeto_abertura_perspectiva || 
		$projeto_abertura_tema || 
		$projeto_abertura_objetivo || 
		$projeto_abertura_fator || 
		$projeto_abertura_estrategia || 
		$projeto_abertura_meta || 
		$projeto_abertura_pratica || 
		$projeto_abertura_acao || 
		$projeto_abertura_canvas || 
		$projeto_abertura_risco || 
		$projeto_abertura_risco_resposta || 
		$projeto_abertura_indicador || 
		$projeto_abertura_calendario || 
		$projeto_abertura_monitoramento || 
		$projeto_abertura_ata || 
		$projeto_abertura_mswot || 
		$projeto_abertura_swot || 
		$projeto_abertura_operativo || 
		$projeto_abertura_instrumento || 
		$projeto_abertura_recurso || 
		$projeto_abertura_problema || 
		$projeto_abertura_demanda || 
		$projeto_abertura_programa || 
		$projeto_abertura_licao || 
		$projeto_abertura_evento || 
		$projeto_abertura_link || 
		$projeto_abertura_avaliacao || 
		$projeto_abertura_tgn || 
		$projeto_abertura_brainstorm || 
		$projeto_abertura_gut || 
		$projeto_abertura_causa_efeito || 
		$projeto_abertura_arquivo || 
		$projeto_abertura_forum || 
		$projeto_abertura_checklist || 
		$projeto_abertura_agenda || 
		$projeto_abertura_agrupamento || 
		$projeto_abertura_patrocinador || 
		$projeto_abertura_template || 
		$projeto_abertura_painel || 
		$projeto_abertura_painel_odometro || 
		$projeto_abertura_painel_composicao || 
		$projeto_abertura_tr || 
		$projeto_abertura_me || 
		$projeto_abertura_acao_item || 
		$projeto_abertura_beneficio || 
		$projeto_abertura_painel_slideshow || 
		$projeto_abertura_projeto_viabilidade || 
		$projeto_abertura_projeto_abertura || 
		$projeto_abertura_plano_gestao|| 
		$projeto_abertura_ssti || 
		$projeto_abertura_laudo || 
		$projeto_abertura_trelo || 
		$projeto_abertura_trelo_cartao || 
		$projeto_abertura_pdcl || 
		$projeto_abertura_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('projeto_abertura_gestao');
			if ($uuid) $sql->adOnde('projeto_abertura_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('projeto_abertura_gestao_projeto_abertura ='.(int)$projeto_abertura_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('projeto_abertura_gestao');
		$sql->adCampo('count(projeto_abertura_gestao_id)');
		if ($uuid) $sql->adOnde('projeto_abertura_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_abertura_gestao_projeto_abertura ='.(int)$projeto_abertura_id);	
		if ($projeto_abertura_tarefa) $sql->adOnde('projeto_abertura_gestao_tarefa='.(int)$projeto_abertura_tarefa);
		elseif ($projeto_abertura_projeto) $sql->adOnde('projeto_abertura_gestao_projeto='.(int)$projeto_abertura_projeto);
		elseif ($projeto_abertura_perspectiva) $sql->adOnde('projeto_abertura_gestao_perspectiva='.(int)$projeto_abertura_perspectiva);
		elseif ($projeto_abertura_tema) $sql->adOnde('projeto_abertura_gestao_tema='.(int)$projeto_abertura_tema);
		elseif ($projeto_abertura_objetivo) $sql->adOnde('projeto_abertura_gestao_objetivo='.(int)$projeto_abertura_objetivo);
		elseif ($projeto_abertura_fator) $sql->adOnde('projeto_abertura_gestao_fator='.(int)$projeto_abertura_fator);
		elseif ($projeto_abertura_estrategia) $sql->adOnde('projeto_abertura_gestao_estrategia='.(int)$projeto_abertura_estrategia);
		elseif ($projeto_abertura_acao) $sql->adOnde('projeto_abertura_gestao_acao='.(int)$projeto_abertura_acao);
		elseif ($projeto_abertura_pratica) $sql->adOnde('projeto_abertura_gestao_pratica='.(int)$projeto_abertura_pratica);
		elseif ($projeto_abertura_meta) $sql->adOnde('projeto_abertura_gestao_meta='.(int)$projeto_abertura_meta);
		elseif ($projeto_abertura_canvas) $sql->adOnde('projeto_abertura_gestao_canvas='.(int)$projeto_abertura_canvas);
		elseif ($projeto_abertura_risco) $sql->adOnde('projeto_abertura_gestao_risco='.(int)$projeto_abertura_risco);
		elseif ($projeto_abertura_risco_resposta) $sql->adOnde('projeto_abertura_gestao_risco_resposta='.(int)$projeto_abertura_risco_resposta);
		elseif ($projeto_abertura_indicador) $sql->adOnde('projeto_abertura_gestao_indicador='.(int)$projeto_abertura_indicador);
		elseif ($projeto_abertura_calendario) $sql->adOnde('projeto_abertura_gestao_calendario='.(int)$projeto_abertura_calendario);
		elseif ($projeto_abertura_monitoramento) $sql->adOnde('projeto_abertura_gestao_monitoramento='.(int)$projeto_abertura_monitoramento);
		elseif ($projeto_abertura_ata) $sql->adOnde('projeto_abertura_gestao_ata='.(int)$projeto_abertura_ata);
		elseif ($projeto_abertura_mswot) $sql->adOnde('projeto_abertura_gestao_mswot='.(int)$projeto_abertura_mswot);
		elseif ($projeto_abertura_swot) $sql->adOnde('projeto_abertura_gestao_swot='.(int)$projeto_abertura_swot);
		elseif ($projeto_abertura_operativo) $sql->adOnde('projeto_abertura_gestao_operativo='.(int)$projeto_abertura_operativo);
		elseif ($projeto_abertura_instrumento) $sql->adOnde('projeto_abertura_gestao_instrumento='.(int)$projeto_abertura_instrumento);
		elseif ($projeto_abertura_recurso) $sql->adOnde('projeto_abertura_gestao_recurso='.(int)$projeto_abertura_recurso);
		elseif ($projeto_abertura_problema) $sql->adOnde('projeto_abertura_gestao_problema='.(int)$projeto_abertura_problema);
		elseif ($projeto_abertura_demanda) $sql->adOnde('projeto_abertura_gestao_demanda='.(int)$projeto_abertura_demanda);
		elseif ($projeto_abertura_programa) $sql->adOnde('projeto_abertura_gestao_programa='.(int)$projeto_abertura_programa);
		elseif ($projeto_abertura_licao) $sql->adOnde('projeto_abertura_gestao_licao='.(int)$projeto_abertura_licao);
		elseif ($projeto_abertura_evento) $sql->adOnde('projeto_abertura_gestao_evento='.(int)$projeto_abertura_evento);
		elseif ($projeto_abertura_link) $sql->adOnde('projeto_abertura_gestao_link='.(int)$projeto_abertura_link);
		elseif ($projeto_abertura_avaliacao) $sql->adOnde('projeto_abertura_gestao_avaliacao='.(int)$projeto_abertura_avaliacao);
		elseif ($projeto_abertura_tgn) $sql->adOnde('projeto_abertura_gestao_tgn='.(int)$projeto_abertura_tgn);
		elseif ($projeto_abertura_brainstorm) $sql->adOnde('projeto_abertura_gestao_brainstorm='.(int)$projeto_abertura_brainstorm);
		elseif ($projeto_abertura_gut) $sql->adOnde('projeto_abertura_gestao_gut='.(int)$projeto_abertura_gut);
		elseif ($projeto_abertura_causa_efeito) $sql->adOnde('projeto_abertura_gestao_causa_efeito='.(int)$projeto_abertura_causa_efeito);
		elseif ($projeto_abertura_arquivo) $sql->adOnde('projeto_abertura_gestao_arquivo='.(int)$projeto_abertura_arquivo);
		elseif ($projeto_abertura_forum) $sql->adOnde('projeto_abertura_gestao_forum='.(int)$projeto_abertura_forum);
		elseif ($projeto_abertura_checklist) $sql->adOnde('projeto_abertura_gestao_checklist='.(int)$projeto_abertura_checklist);
		elseif ($projeto_abertura_agenda) $sql->adOnde('projeto_abertura_gestao_agenda='.(int)$projeto_abertura_agenda);
		elseif ($projeto_abertura_agrupamento) $sql->adOnde('projeto_abertura_gestao_agrupamento='.(int)$projeto_abertura_agrupamento);
		elseif ($projeto_abertura_patrocinador) $sql->adOnde('projeto_abertura_gestao_patrocinador='.(int)$projeto_abertura_patrocinador);
		elseif ($projeto_abertura_template) $sql->adOnde('projeto_abertura_gestao_template='.(int)$projeto_abertura_template);
		elseif ($projeto_abertura_painel) $sql->adOnde('projeto_abertura_gestao_painel='.(int)$projeto_abertura_painel);
		elseif ($projeto_abertura_painel_odometro) $sql->adOnde('projeto_abertura_gestao_painel_odometro='.(int)$projeto_abertura_painel_odometro);
		elseif ($projeto_abertura_painel_composicao) $sql->adOnde('projeto_abertura_gestao_painel_composicao='.(int)$projeto_abertura_painel_composicao);
		elseif ($projeto_abertura_tr) $sql->adOnde('projeto_abertura_gestao_tr='.(int)$projeto_abertura_tr);
		elseif ($projeto_abertura_me) $sql->adOnde('projeto_abertura_gestao_me='.(int)$projeto_abertura_me);
		elseif ($projeto_abertura_acao_item) $sql->adOnde('projeto_abertura_gestao_acao_item='.(int)$projeto_abertura_acao_item);
		elseif ($projeto_abertura_beneficio) $sql->adOnde('projeto_abertura_gestao_beneficio='.(int)$projeto_abertura_beneficio);
		elseif ($projeto_abertura_painel_slideshow) $sql->adOnde('projeto_abertura_gestao_painel_slideshow='.(int)$projeto_abertura_painel_slideshow);
		elseif ($projeto_abertura_projeto_viabilidade) $sql->adOnde('projeto_abertura_gestao_projeto_viabilidade='.(int)$projeto_abertura_projeto_viabilidade);
		
		elseif ($projeto_abertura_projeto_abertura) $sql->adOnde('projeto_abertura_gestao_semelhante='.(int)$projeto_abertura_projeto_abertura);
		
		elseif ($projeto_abertura_plano_gestao) $sql->adOnde('projeto_abertura_gestao_plano_gestao='.(int)$projeto_abertura_plano_gestao);
		elseif ($projeto_abertura_ssti) $sql->adOnde('projeto_abertura_gestao_ssti='.(int)$projeto_abertura_ssti);
		elseif ($projeto_abertura_laudo) $sql->adOnde('projeto_abertura_gestao_laudo='.(int)$projeto_abertura_laudo);
		elseif ($projeto_abertura_trelo) $sql->adOnde('projeto_abertura_gestao_trelo='.(int)$projeto_abertura_trelo);
		elseif ($projeto_abertura_trelo_cartao) $sql->adOnde('projeto_abertura_gestao_trelo_cartao='.(int)$projeto_abertura_trelo_cartao);
		elseif ($projeto_abertura_pdcl) $sql->adOnde('projeto_abertura_gestao_pdcl='.(int)$projeto_abertura_pdcl);
		elseif ($projeto_abertura_pdcl_item) $sql->adOnde('projeto_abertura_gestao_pdcl_item='.(int)$projeto_abertura_pdcl_item);
	
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('projeto_abertura_gestao');
			$sql->adCampo('MAX(projeto_abertura_gestao_ordem)');
			if ($uuid) $sql->adOnde('projeto_abertura_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('projeto_abertura_gestao_projeto_abertura ='.(int)$projeto_abertura_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('projeto_abertura_gestao');
			if ($uuid) $sql->adInserir('projeto_abertura_gestao_uuid', $uuid);
			else $sql->adInserir('projeto_abertura_gestao_projeto_abertura', (int)$projeto_abertura_id);
			
			if ($projeto_abertura_tarefa) $sql->adInserir('projeto_abertura_gestao_tarefa', (int)$projeto_abertura_tarefa);
			if ($projeto_abertura_projeto) $sql->adInserir('projeto_abertura_gestao_projeto', (int)$projeto_abertura_projeto);
			elseif ($projeto_abertura_perspectiva) $sql->adInserir('projeto_abertura_gestao_perspectiva', (int)$projeto_abertura_perspectiva);
			elseif ($projeto_abertura_tema) $sql->adInserir('projeto_abertura_gestao_tema', (int)$projeto_abertura_tema);
			elseif ($projeto_abertura_objetivo) $sql->adInserir('projeto_abertura_gestao_objetivo', (int)$projeto_abertura_objetivo);
			elseif ($projeto_abertura_fator) $sql->adInserir('projeto_abertura_gestao_fator', (int)$projeto_abertura_fator);
			elseif ($projeto_abertura_estrategia) $sql->adInserir('projeto_abertura_gestao_estrategia', (int)$projeto_abertura_estrategia);
			elseif ($projeto_abertura_acao) $sql->adInserir('projeto_abertura_gestao_acao', (int)$projeto_abertura_acao);
			elseif ($projeto_abertura_pratica) $sql->adInserir('projeto_abertura_gestao_pratica', (int)$projeto_abertura_pratica);
			elseif ($projeto_abertura_meta) $sql->adInserir('projeto_abertura_gestao_meta', (int)$projeto_abertura_meta);
			elseif ($projeto_abertura_canvas) $sql->adInserir('projeto_abertura_gestao_canvas', (int)$projeto_abertura_canvas);
			elseif ($projeto_abertura_risco) $sql->adInserir('projeto_abertura_gestao_risco', (int)$projeto_abertura_risco);
			elseif ($projeto_abertura_risco_resposta) $sql->adInserir('projeto_abertura_gestao_risco_resposta', (int)$projeto_abertura_risco_resposta);
			elseif ($projeto_abertura_indicador) $sql->adInserir('projeto_abertura_gestao_indicador', (int)$projeto_abertura_indicador);
			elseif ($projeto_abertura_calendario) $sql->adInserir('projeto_abertura_gestao_calendario', (int)$projeto_abertura_calendario);
			elseif ($projeto_abertura_monitoramento) $sql->adInserir('projeto_abertura_gestao_monitoramento', (int)$projeto_abertura_monitoramento);
			elseif ($projeto_abertura_ata) $sql->adInserir('projeto_abertura_gestao_ata', (int)$projeto_abertura_ata);
			elseif ($projeto_abertura_mswot) $sql->adInserir('projeto_abertura_gestao_mswot', (int)$projeto_abertura_mswot);
			elseif ($projeto_abertura_swot) $sql->adInserir('projeto_abertura_gestao_swot', (int)$projeto_abertura_swot);
			elseif ($projeto_abertura_operativo) $sql->adInserir('projeto_abertura_gestao_operativo', (int)$projeto_abertura_operativo);
			elseif ($projeto_abertura_instrumento) $sql->adInserir('projeto_abertura_gestao_instrumento', (int)$projeto_abertura_instrumento);
			elseif ($projeto_abertura_recurso) $sql->adInserir('projeto_abertura_gestao_recurso', (int)$projeto_abertura_recurso);
			elseif ($projeto_abertura_problema) $sql->adInserir('projeto_abertura_gestao_problema', (int)$projeto_abertura_problema);
			elseif ($projeto_abertura_demanda) $sql->adInserir('projeto_abertura_gestao_demanda', (int)$projeto_abertura_demanda);
			elseif ($projeto_abertura_programa) $sql->adInserir('projeto_abertura_gestao_programa', (int)$projeto_abertura_programa);
			elseif ($projeto_abertura_licao) $sql->adInserir('projeto_abertura_gestao_licao', (int)$projeto_abertura_licao);
			elseif ($projeto_abertura_evento) $sql->adInserir('projeto_abertura_gestao_evento', (int)$projeto_abertura_evento);
			elseif ($projeto_abertura_link) $sql->adInserir('projeto_abertura_gestao_link', (int)$projeto_abertura_link);
			elseif ($projeto_abertura_avaliacao) $sql->adInserir('projeto_abertura_gestao_avaliacao', (int)$projeto_abertura_avaliacao);
			elseif ($projeto_abertura_tgn) $sql->adInserir('projeto_abertura_gestao_tgn', (int)$projeto_abertura_tgn);
			elseif ($projeto_abertura_brainstorm) $sql->adInserir('projeto_abertura_gestao_brainstorm', (int)$projeto_abertura_brainstorm);
			elseif ($projeto_abertura_gut) $sql->adInserir('projeto_abertura_gestao_gut', (int)$projeto_abertura_gut);
			elseif ($projeto_abertura_causa_efeito) $sql->adInserir('projeto_abertura_gestao_causa_efeito', (int)$projeto_abertura_causa_efeito);
			elseif ($projeto_abertura_arquivo) $sql->adInserir('projeto_abertura_gestao_arquivo', (int)$projeto_abertura_arquivo);
			elseif ($projeto_abertura_forum) $sql->adInserir('projeto_abertura_gestao_forum', (int)$projeto_abertura_forum);
			elseif ($projeto_abertura_checklist) $sql->adInserir('projeto_abertura_gestao_checklist', (int)$projeto_abertura_checklist);
			elseif ($projeto_abertura_agenda) $sql->adInserir('projeto_abertura_gestao_agenda', (int)$projeto_abertura_agenda);
			elseif ($projeto_abertura_agrupamento) $sql->adInserir('projeto_abertura_gestao_agrupamento', (int)$projeto_abertura_agrupamento);
			elseif ($projeto_abertura_patrocinador) $sql->adInserir('projeto_abertura_gestao_patrocinador', (int)$projeto_abertura_patrocinador);
			elseif ($projeto_abertura_template) $sql->adInserir('projeto_abertura_gestao_template', (int)$projeto_abertura_template);
			elseif ($projeto_abertura_painel) $sql->adInserir('projeto_abertura_gestao_painel', (int)$projeto_abertura_painel);
			elseif ($projeto_abertura_painel_odometro) $sql->adInserir('projeto_abertura_gestao_painel_odometro', (int)$projeto_abertura_painel_odometro);
			elseif ($projeto_abertura_painel_composicao) $sql->adInserir('projeto_abertura_gestao_painel_composicao', (int)$projeto_abertura_painel_composicao);
			elseif ($projeto_abertura_tr) $sql->adInserir('projeto_abertura_gestao_tr', (int)$projeto_abertura_tr);
			elseif ($projeto_abertura_me) $sql->adInserir('projeto_abertura_gestao_me', (int)$projeto_abertura_me);
			elseif ($projeto_abertura_acao_item) $sql->adInserir('projeto_abertura_gestao_acao_item', (int)$projeto_abertura_acao_item);
			elseif ($projeto_abertura_beneficio) $sql->adInserir('projeto_abertura_gestao_beneficio', (int)$projeto_abertura_beneficio);
			elseif ($projeto_abertura_painel_slideshow) $sql->adInserir('projeto_abertura_gestao_painel_slideshow', (int)$projeto_abertura_painel_slideshow);
			elseif ($projeto_abertura_projeto_viabilidade) $sql->adInserir('projeto_abertura_gestao_projeto_viabilidade', (int)$projeto_abertura_projeto_viabilidade);
			
			elseif ($projeto_abertura_projeto_abertura) $sql->adInserir('projeto_abertura_gestao_semelhante', (int)$projeto_abertura_projeto_abertura);
			
			elseif ($projeto_abertura_plano_gestao) $sql->adInserir('projeto_abertura_gestao_plano_gestao', (int)$projeto_abertura_plano_gestao);
			elseif ($projeto_abertura_ssti) $sql->adInserir('projeto_abertura_gestao_ssti', (int)$projeto_abertura_ssti);
			elseif ($projeto_abertura_laudo) $sql->adInserir('projeto_abertura_gestao_laudo', (int)$projeto_abertura_laudo);
			elseif ($projeto_abertura_trelo) $sql->adInserir('projeto_abertura_gestao_trelo', (int)$projeto_abertura_trelo);
			elseif ($projeto_abertura_trelo_cartao) $sql->adInserir('projeto_abertura_gestao_trelo_cartao', (int)$projeto_abertura_trelo_cartao);
			elseif ($projeto_abertura_pdcl) $sql->adInserir('projeto_abertura_gestao_pdcl', (int)$projeto_abertura_pdcl);
			elseif ($projeto_abertura_pdcl_item) $sql->adInserir('projeto_abertura_gestao_pdcl_item', (int)$projeto_abertura_pdcl_item);
			$sql->adInserir('projeto_abertura_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($projeto_abertura_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($projeto_abertura_id=0, $uuid='', $projeto_abertura_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_abertura_gestao');
	$sql->adOnde('projeto_abertura_gestao_id='.(int)$projeto_abertura_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($projeto_abertura_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($projeto_abertura_id=0, $uuid=''){	
	$saida=atualizar_gestao($projeto_abertura_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($projeto_abertura_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('projeto_abertura_gestao');
	$sql->adCampo('projeto_abertura_gestao.*');
	if ($uuid) $sql->adOnde('projeto_abertura_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_abertura_gestao_projeto_abertura ='.(int)$projeto_abertura_id);	
	$sql->adOrdem('projeto_abertura_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_abertura_gestao_ordem'].', '.$gestao_data['projeto_abertura_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_abertura_gestao_ordem'].', '.$gestao_data['projeto_abertura_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_abertura_gestao_ordem'].', '.$gestao_data['projeto_abertura_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_abertura_gestao_ordem'].', '.$gestao_data['projeto_abertura_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['projeto_abertura_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_abertura_gestao_tarefa']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_abertura_gestao_projeto']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_abertura_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['projeto_abertura_gestao_tema']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_abertura_gestao_objetivo']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_abertura_gestao_fator']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_abertura_gestao_estrategia']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_abertura_gestao_meta']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_abertura_gestao_pratica']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_abertura_gestao_acao']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_abertura_gestao_canvas']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['projeto_abertura_gestao_risco']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_abertura_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_abertura_gestao_indicador']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_abertura_gestao_calendario']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_abertura_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_abertura_gestao_ata']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_abertura_gestao_mswot']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['projeto_abertura_gestao_swot']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_abertura_gestao_operativo']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_abertura_gestao_instrumento']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_abertura_gestao_recurso']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['projeto_abertura_gestao_problema']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_abertura_gestao_demanda']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['projeto_abertura_gestao_programa']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_abertura_gestao_licao']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_abertura_gestao_evento']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['projeto_abertura_gestao_link']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_abertura_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_abertura_gestao_tgn']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_abertura_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_abertura_gestao_gut']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_abertura_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_abertura_gestao_arquivo']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_abertura_gestao_forum']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_abertura_gestao_checklist']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_abertura_gestao_agenda']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_abertura_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_abertura_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['projeto_abertura_gestao_template']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['projeto_abertura_gestao_painel']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_abertura_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_abertura_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['projeto_abertura_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['projeto_abertura_gestao_tr']).'</td>';	
		elseif ($gestao_data['projeto_abertura_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['projeto_abertura_gestao_me']).'</td>';	
		elseif ($gestao_data['projeto_abertura_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_abertura_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['projeto_abertura_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_abertura_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['projeto_abertura_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_abertura_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['projeto_abertura_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_abertura_gestao_projeto_viabilidade']).'</td>';	
		
		elseif ($gestao_data['projeto_abertura_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_abertura_gestao_semelhante']).'</td>';	
		
		elseif ($gestao_data['projeto_abertura_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_abertura_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['projeto_abertura_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_abertura_gestao_ssti']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_abertura_gestao_laudo']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_abertura_gestao_trelo']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_abertura_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_abertura_gestao_pdcl']).'</td>';
		elseif ($gestao_data['projeto_abertura_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_abertura_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['projeto_abertura_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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

function exibir_contatos($contatos, $campo){
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
	$objResposta->assign($campo,"innerHTML", utf8_encode($saida_contatos));
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


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}

$xajax->registerFunction("selecionar_om_ajax");

function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script){
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	$sql->adOnde('sisvalor_projeto IS NULL');
	$sql->adOrdem('sisvalor_valor');
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
	
$xajax->processRequest();

?>