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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/estrategia_editar_pro_ajax.php');

function mudar_posicao_gestao($ordem, $estrategia_gestao_id, $direcao, $pg_estrategia_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $estrategia_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('estrategia_gestao');
		$sql->adOnde('estrategia_gestao_id != '.(int)$estrategia_gestao_id);
		if ($uuid) $sql->adOnde('estrategia_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('estrategia_gestao_estrategia = '.(int)$pg_estrategia_id);
		$sql->adOrdem('estrategia_gestao_ordem');
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
			$sql->adTabela('estrategia_gestao');
			$sql->adAtualizar('estrategia_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('estrategia_gestao_id = '.(int)$estrategia_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('estrategia_gestao');
					$sql->adAtualizar('estrategia_gestao_ordem', $idx);
					$sql->adOnde('estrategia_gestao_id = '.(int)$acao['estrategia_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('estrategia_gestao');
					$sql->adAtualizar('estrategia_gestao_ordem', $idx + 1);
					$sql->adOnde('estrategia_gestao_id = '.(int)$acao['estrategia_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($pg_estrategia_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$pg_estrategia_id=0, 
	$uuid='',  
	
	$estrategia_projeto=null,
	$estrategia_tarefa=null,
	$estrategia_perspectiva=null,
	$estrategia_tema=null,
	$estrategia_objetivo=null,
	$estrategia_fator=null,
	$estrategia_estrategia=null,
	$estrategia_meta=null,
	$estrategia_pratica=null,
	$estrategia_acao=null,
	$estrategia_canvas=null,
	$estrategia_risco=null,
	$estrategia_risco_resposta=null,
	$estrategia_indicador=null,
	$estrategia_calendario=null,
	$estrategia_monitoramento=null,
	$estrategia_ata=null,
	$estrategia_mswot=null,
	$estrategia_swot=null,
	$estrategia_operativo=null,
	$estrategia_instrumento=null,
	$estrategia_recurso=null,
	$estrategia_problema=null,
	$estrategia_demanda=null,
	$estrategia_programa=null,
	$estrategia_licao=null,
	$estrategia_evento=null,
	$estrategia_link=null,
	$estrategia_avaliacao=null,
	$estrategia_tgn=null,
	$estrategia_brainstorm=null,
	$estrategia_gut=null,
	$estrategia_causa_efeito=null,
	$estrategia_arquivo=null,
	$estrategia_forum=null,
	$estrategia_checklist=null,
	$estrategia_agenda=null,
	$estrategia_agrupamento=null,
	$estrategia_patrocinador=null,
	$estrategia_template=null,
	$estrategia_painel=null,
	$estrategia_painel_odometro=null,
	$estrategia_painel_composicao=null,
	$estrategia_tr=null,
	$estrategia_me=null,
	$estrategia_acao_item=null,
	$estrategia_beneficio=null,
	$estrategia_painel_slideshow=null,
	$estrategia_projeto_viabilidade=null,
	$estrategia_projeto_abertura=null,
	$estrategia_plano_gestao=null,
	$estrategia_ssti=null,
	$estrategia_laudo=null,
	$estrategia_trelo=null,
	$estrategia_trelo_cartao=null,
	$estrategia_pdcl=null,
	$estrategia_pdcl_item=null
	)
	{
	if (
		$estrategia_projeto || 
		$estrategia_tarefa || 
		$estrategia_perspectiva || 
		$estrategia_tema || 
		$estrategia_objetivo || 
		$estrategia_fator || 
		$estrategia_estrategia || 
		$estrategia_meta || 
		$estrategia_pratica || 
		$estrategia_acao || 
		$estrategia_canvas || 
		$estrategia_risco || 
		$estrategia_risco_resposta || 
		$estrategia_indicador || 
		$estrategia_calendario || 
		$estrategia_monitoramento || 
		$estrategia_ata || 
		$estrategia_mswot || 
		$estrategia_swot || 
		$estrategia_operativo || 
		$estrategia_instrumento || 
		$estrategia_recurso || 
		$estrategia_problema || 
		$estrategia_demanda || 
		$estrategia_programa || 
		$estrategia_licao || 
		$estrategia_evento || 
		$estrategia_link || 
		$estrategia_avaliacao || 
		$estrategia_tgn || 
		$estrategia_brainstorm || 
		$estrategia_gut || 
		$estrategia_causa_efeito || 
		$estrategia_arquivo || 
		$estrategia_forum || 
		$estrategia_checklist || 
		$estrategia_agenda || 
		$estrategia_agrupamento || 
		$estrategia_patrocinador || 
		$estrategia_template || 
		$estrategia_painel || 
		$estrategia_painel_odometro || 
		$estrategia_painel_composicao || 
		$estrategia_tr || 
		$estrategia_me || 
		$estrategia_acao_item || 
		$estrategia_beneficio || 
		$estrategia_painel_slideshow || 
		$estrategia_projeto_viabilidade || 
		$estrategia_projeto_abertura || 
		$estrategia_plano_gestao|| 
		$estrategia_ssti || 
		$estrategia_laudo || 
		$estrategia_trelo || 
		$estrategia_trelo_cartao || 
		$estrategia_pdcl || 
		$estrategia_pdcl_item
		){
		global $Aplic;
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('estrategia_gestao');
			if ($uuid) $sql->adOnde('estrategia_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('estrategia_gestao_estrategia ='.(int)$pg_estrategia_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('estrategia_gestao');
		$sql->adCampo('count(estrategia_gestao_id)');
		if ($uuid) $sql->adOnde('estrategia_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('estrategia_gestao_estrategia ='.(int)$pg_estrategia_id);	
		if ($estrategia_tarefa) $sql->adOnde('estrategia_gestao_tarefa='.(int)$estrategia_tarefa);
		elseif ($estrategia_projeto) $sql->adOnde('estrategia_gestao_projeto='.(int)$estrategia_projeto);
		elseif ($estrategia_perspectiva) $sql->adOnde('estrategia_gestao_perspectiva='.(int)$estrategia_perspectiva);
		elseif ($estrategia_tema) $sql->adOnde('estrategia_gestao_tema='.(int)$estrategia_tema);
		elseif ($estrategia_objetivo) $sql->adOnde('estrategia_gestao_objetivo='.(int)$estrategia_objetivo);
		elseif ($estrategia_fator) $sql->adOnde('estrategia_gestao_fator='.(int)$estrategia_fator);
		
		elseif ($estrategia_estrategia) $sql->adOnde('estrategia_gestao_semelhante='.(int)$estrategia_estrategia);
		
		elseif ($estrategia_acao) $sql->adOnde('estrategia_gestao_acao='.(int)$estrategia_acao);
		elseif ($estrategia_pratica) $sql->adOnde('estrategia_gestao_pratica='.(int)$estrategia_pratica);
		elseif ($estrategia_meta) $sql->adOnde('estrategia_gestao_meta='.(int)$estrategia_meta);
		elseif ($estrategia_canvas) $sql->adOnde('estrategia_gestao_canvas='.(int)$estrategia_canvas);
		elseif ($estrategia_risco) $sql->adOnde('estrategia_gestao_risco='.(int)$estrategia_risco);
		elseif ($estrategia_risco_resposta) $sql->adOnde('estrategia_gestao_risco_resposta='.(int)$estrategia_risco_resposta);
		elseif ($estrategia_indicador) $sql->adOnde('estrategia_gestao_indicador='.(int)$estrategia_indicador);
		elseif ($estrategia_calendario) $sql->adOnde('estrategia_gestao_calendario='.(int)$estrategia_calendario);
		elseif ($estrategia_monitoramento) $sql->adOnde('estrategia_gestao_monitoramento='.(int)$estrategia_monitoramento);
		elseif ($estrategia_ata) $sql->adOnde('estrategia_gestao_ata='.(int)$estrategia_ata);
		elseif ($estrategia_mswot) $sql->adOnde('estrategia_gestao_mswot='.(int)$estrategia_mswot);
		elseif ($estrategia_swot) $sql->adOnde('estrategia_gestao_swot='.(int)$estrategia_swot);
		elseif ($estrategia_operativo) $sql->adOnde('estrategia_gestao_operativo='.(int)$estrategia_operativo);
		elseif ($estrategia_instrumento) $sql->adOnde('estrategia_gestao_instrumento='.(int)$estrategia_instrumento);
		elseif ($estrategia_recurso) $sql->adOnde('estrategia_gestao_recurso='.(int)$estrategia_recurso);
		elseif ($estrategia_problema) $sql->adOnde('estrategia_gestao_problema='.(int)$estrategia_problema);
		elseif ($estrategia_demanda) $sql->adOnde('estrategia_gestao_demanda='.(int)$estrategia_demanda);
		elseif ($estrategia_programa) $sql->adOnde('estrategia_gestao_programa='.(int)$estrategia_programa);
		elseif ($estrategia_licao) $sql->adOnde('estrategia_gestao_licao='.(int)$estrategia_licao);
		elseif ($estrategia_evento) $sql->adOnde('estrategia_gestao_evento='.(int)$estrategia_evento);
		elseif ($estrategia_link) $sql->adOnde('estrategia_gestao_link='.(int)$estrategia_link);
		elseif ($estrategia_avaliacao) $sql->adOnde('estrategia_gestao_avaliacao='.(int)$estrategia_avaliacao);
		elseif ($estrategia_tgn) $sql->adOnde('estrategia_gestao_tgn='.(int)$estrategia_tgn);
		elseif ($estrategia_brainstorm) $sql->adOnde('estrategia_gestao_brainstorm='.(int)$estrategia_brainstorm);
		elseif ($estrategia_gut) $sql->adOnde('estrategia_gestao_gut='.(int)$estrategia_gut);
		elseif ($estrategia_causa_efeito) $sql->adOnde('estrategia_gestao_causa_efeito='.(int)$estrategia_causa_efeito);
		elseif ($estrategia_arquivo) $sql->adOnde('estrategia_gestao_arquivo='.(int)$estrategia_arquivo);
		elseif ($estrategia_forum) $sql->adOnde('estrategia_gestao_forum='.(int)$estrategia_forum);
		elseif ($estrategia_checklist) $sql->adOnde('estrategia_gestao_checklist='.(int)$estrategia_checklist);
		elseif ($estrategia_agenda) $sql->adOnde('estrategia_gestao_agenda='.(int)$estrategia_agenda);
		elseif ($estrategia_agrupamento) $sql->adOnde('estrategia_gestao_agrupamento='.(int)$estrategia_agrupamento);
		elseif ($estrategia_patrocinador) $sql->adOnde('estrategia_gestao_patrocinador='.(int)$estrategia_patrocinador);
		elseif ($estrategia_template) $sql->adOnde('estrategia_gestao_template='.(int)$estrategia_template);
		elseif ($estrategia_painel) $sql->adOnde('estrategia_gestao_painel='.(int)$estrategia_painel);
		elseif ($estrategia_painel_odometro) $sql->adOnde('estrategia_gestao_painel_odometro='.(int)$estrategia_painel_odometro);
		elseif ($estrategia_painel_composicao) $sql->adOnde('estrategia_gestao_painel_composicao='.(int)$estrategia_painel_composicao);
		elseif ($estrategia_tr) $sql->adOnde('estrategia_gestao_tr='.(int)$estrategia_tr);
		elseif ($estrategia_me) $sql->adOnde('estrategia_gestao_me='.(int)$estrategia_me);
		elseif ($estrategia_acao_item) $sql->adOnde('estrategia_gestao_acao_item='.(int)$estrategia_acao_item);
		elseif ($estrategia_beneficio) $sql->adOnde('estrategia_gestao_beneficio='.(int)$estrategia_beneficio);
		elseif ($estrategia_painel_slideshow) $sql->adOnde('estrategia_gestao_painel_slideshow='.(int)$estrategia_painel_slideshow);
		elseif ($estrategia_projeto_viabilidade) $sql->adOnde('estrategia_gestao_projeto_viabilidade='.(int)$estrategia_projeto_viabilidade);
		elseif ($estrategia_projeto_abertura) $sql->adOnde('estrategia_gestao_projeto_abertura='.(int)$estrategia_projeto_abertura);
		elseif ($estrategia_plano_gestao) $sql->adOnde('estrategia_gestao_plano_gestao='.(int)$estrategia_plano_gestao);
		elseif ($estrategia_ssti) $sql->adOnde('estrategia_gestao_ssti='.(int)$estrategia_ssti);
		elseif ($estrategia_laudo) $sql->adOnde('estrategia_gestao_laudo='.(int)$estrategia_laudo);
		elseif ($estrategia_trelo) $sql->adOnde('estrategia_gestao_trelo='.(int)$estrategia_trelo);
		elseif ($estrategia_trelo_cartao) $sql->adOnde('estrategia_gestao_trelo_cartao='.(int)$estrategia_trelo_cartao);
		elseif ($estrategia_pdcl) $sql->adOnde('estrategia_gestao_pdcl='.(int)$estrategia_pdcl);
		elseif ($estrategia_pdcl_item) $sql->adOnde('estrategia_gestao_pdcl_item='.(int)$estrategia_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('estrategia_gestao');
			$sql->adCampo('MAX(estrategia_gestao_ordem)');
			if ($uuid) $sql->adOnde('estrategia_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('estrategia_gestao_estrategia ='.(int)$pg_estrategia_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('estrategia_gestao');
			if ($uuid) $sql->adInserir('estrategia_gestao_uuid', $uuid);
			else $sql->adInserir('estrategia_gestao_estrategia', (int)$pg_estrategia_id);
			
			if ($estrategia_tarefa) $sql->adInserir('estrategia_gestao_tarefa', (int)$estrategia_tarefa);
			if ($estrategia_projeto) $sql->adInserir('estrategia_gestao_projeto', (int)$estrategia_projeto);
			elseif ($estrategia_perspectiva) $sql->adInserir('estrategia_gestao_perspectiva', (int)$estrategia_perspectiva);
			elseif ($estrategia_tema) $sql->adInserir('estrategia_gestao_tema', (int)$estrategia_tema);
			elseif ($estrategia_objetivo) $sql->adInserir('estrategia_gestao_objetivo', (int)$estrategia_objetivo);
			elseif ($estrategia_fator) $sql->adInserir('estrategia_gestao_fator', (int)$estrategia_fator);
			
			elseif ($estrategia_estrategia) $sql->adInserir('estrategia_gestao_semelhante', (int)$estrategia_estrategia);
			
			elseif ($estrategia_acao) $sql->adInserir('estrategia_gestao_acao', (int)$estrategia_acao);
			elseif ($estrategia_pratica) $sql->adInserir('estrategia_gestao_pratica', (int)$estrategia_pratica);
			elseif ($estrategia_meta) $sql->adInserir('estrategia_gestao_meta', (int)$estrategia_meta);
			elseif ($estrategia_canvas) $sql->adInserir('estrategia_gestao_canvas', (int)$estrategia_canvas);
			elseif ($estrategia_risco) $sql->adInserir('estrategia_gestao_risco', (int)$estrategia_risco);
			elseif ($estrategia_risco_resposta) $sql->adInserir('estrategia_gestao_risco_resposta', (int)$estrategia_risco_resposta);
			elseif ($estrategia_indicador) $sql->adInserir('estrategia_gestao_indicador', (int)$estrategia_indicador);
			elseif ($estrategia_calendario) $sql->adInserir('estrategia_gestao_calendario', (int)$estrategia_calendario);
			elseif ($estrategia_monitoramento) $sql->adInserir('estrategia_gestao_monitoramento', (int)$estrategia_monitoramento);
			elseif ($estrategia_ata) $sql->adInserir('estrategia_gestao_ata', (int)$estrategia_ata);
			elseif ($estrategia_mswot) $sql->adInserir('estrategia_gestao_mswot', (int)$estrategia_mswot);
			elseif ($estrategia_swot) $sql->adInserir('estrategia_gestao_swot', (int)$estrategia_swot);
			elseif ($estrategia_operativo) $sql->adInserir('estrategia_gestao_operativo', (int)$estrategia_operativo);
			elseif ($estrategia_instrumento) $sql->adInserir('estrategia_gestao_instrumento', (int)$estrategia_instrumento);
			elseif ($estrategia_recurso) $sql->adInserir('estrategia_gestao_recurso', (int)$estrategia_recurso);
			elseif ($estrategia_problema) $sql->adInserir('estrategia_gestao_problema', (int)$estrategia_problema);
			elseif ($estrategia_demanda) $sql->adInserir('estrategia_gestao_demanda', (int)$estrategia_demanda);
			elseif ($estrategia_programa) $sql->adInserir('estrategia_gestao_programa', (int)$estrategia_programa);
			elseif ($estrategia_licao) $sql->adInserir('estrategia_gestao_licao', (int)$estrategia_licao);
			elseif ($estrategia_evento) $sql->adInserir('estrategia_gestao_evento', (int)$estrategia_evento);
			elseif ($estrategia_link) $sql->adInserir('estrategia_gestao_link', (int)$estrategia_link);
			elseif ($estrategia_avaliacao) $sql->adInserir('estrategia_gestao_avaliacao', (int)$estrategia_avaliacao);
			elseif ($estrategia_tgn) $sql->adInserir('estrategia_gestao_tgn', (int)$estrategia_tgn);
			elseif ($estrategia_brainstorm) $sql->adInserir('estrategia_gestao_brainstorm', (int)$estrategia_brainstorm);
			elseif ($estrategia_gut) $sql->adInserir('estrategia_gestao_gut', (int)$estrategia_gut);
			elseif ($estrategia_causa_efeito) $sql->adInserir('estrategia_gestao_causa_efeito', (int)$estrategia_causa_efeito);
			elseif ($estrategia_arquivo) $sql->adInserir('estrategia_gestao_arquivo', (int)$estrategia_arquivo);
			elseif ($estrategia_forum) $sql->adInserir('estrategia_gestao_forum', (int)$estrategia_forum);
			elseif ($estrategia_checklist) $sql->adInserir('estrategia_gestao_checklist', (int)$estrategia_checklist);
			elseif ($estrategia_agenda) $sql->adInserir('estrategia_gestao_agenda', (int)$estrategia_agenda);
			elseif ($estrategia_agrupamento) $sql->adInserir('estrategia_gestao_agrupamento', (int)$estrategia_agrupamento);
			elseif ($estrategia_patrocinador) $sql->adInserir('estrategia_gestao_patrocinador', (int)$estrategia_patrocinador);
			elseif ($estrategia_template) $sql->adInserir('estrategia_gestao_template', (int)$estrategia_template);
			elseif ($estrategia_painel) $sql->adInserir('estrategia_gestao_painel', (int)$estrategia_painel);
			elseif ($estrategia_painel_odometro) $sql->adInserir('estrategia_gestao_painel_odometro', (int)$estrategia_painel_odometro);
			elseif ($estrategia_painel_composicao) $sql->adInserir('estrategia_gestao_painel_composicao', (int)$estrategia_painel_composicao);
			elseif ($estrategia_tr) $sql->adInserir('estrategia_gestao_tr', (int)$estrategia_tr);
			elseif ($estrategia_me) $sql->adInserir('estrategia_gestao_me', (int)$estrategia_me);
			elseif ($estrategia_acao_item) $sql->adInserir('estrategia_gestao_acao_item', (int)$estrategia_acao_item);
			elseif ($estrategia_beneficio) $sql->adInserir('estrategia_gestao_beneficio', (int)$estrategia_beneficio);
			elseif ($estrategia_painel_slideshow) $sql->adInserir('estrategia_gestao_painel_slideshow', (int)$estrategia_painel_slideshow);
			elseif ($estrategia_projeto_viabilidade) $sql->adInserir('estrategia_gestao_projeto_viabilidade', (int)$estrategia_projeto_viabilidade);
			elseif ($estrategia_projeto_abertura) $sql->adInserir('estrategia_gestao_projeto_abertura', (int)$estrategia_projeto_abertura);
			elseif ($estrategia_plano_gestao) $sql->adInserir('estrategia_gestao_plano_gestao', (int)$estrategia_plano_gestao);
			elseif ($estrategia_ssti) $sql->adInserir('estrategia_gestao_ssti', (int)$estrategia_ssti);
			elseif ($estrategia_laudo) $sql->adInserir('estrategia_gestao_laudo', (int)$estrategia_laudo);
			elseif ($estrategia_trelo) $sql->adInserir('estrategia_gestao_trelo', (int)$estrategia_trelo);
			elseif ($estrategia_trelo_cartao) $sql->adInserir('estrategia_gestao_trelo_cartao', (int)$estrategia_trelo_cartao);
			elseif ($estrategia_pdcl) $sql->adInserir('estrategia_gestao_pdcl', (int)$estrategia_pdcl);
			elseif ($estrategia_pdcl_item) $sql->adInserir('estrategia_gestao_pdcl_item', (int)$estrategia_pdcl_item);
	
			$sql->adInserir('estrategia_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($pg_estrategia_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($pg_estrategia_id=0, $uuid='', $estrategia_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('estrategia_gestao');
	$sql->adOnde('estrategia_gestao_id='.(int)$estrategia_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($pg_estrategia_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($pg_estrategia_id=0, $uuid=''){	
	$saida=atualizar_gestao($pg_estrategia_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($pg_estrategia_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('estrategia_gestao');
	$sql->adCampo('estrategia_gestao.*');
	if ($uuid) $sql->adOnde('estrategia_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('estrategia_gestao_estrategia ='.(int)$pg_estrategia_id);	
	$sql->adOrdem('estrategia_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['estrategia_gestao_ordem'].', '.$gestao_data['estrategia_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['estrategia_gestao_ordem'].', '.$gestao_data['estrategia_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['estrategia_gestao_ordem'].', '.$gestao_data['estrategia_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['estrategia_gestao_ordem'].', '.$gestao_data['estrategia_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['estrategia_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['estrategia_gestao_tarefa']).'</td>';
		elseif ($gestao_data['estrategia_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['estrategia_gestao_projeto']).'</td>';
		elseif ($gestao_data['estrategia_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['estrategia_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['estrategia_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['estrategia_gestao_tema']).'</td>';
		elseif ($gestao_data['estrategia_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['estrategia_gestao_objetivo']).'</td>';
		elseif ($gestao_data['estrategia_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['estrategia_gestao_fator']).'</td>';
		
		elseif ($gestao_data['estrategia_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['estrategia_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['estrategia_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['estrategia_gestao_meta']).'</td>';
		elseif ($gestao_data['estrategia_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['estrategia_gestao_pratica']).'</td>';
		elseif ($gestao_data['estrategia_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['estrategia_gestao_acao']).'</td>';
		elseif ($gestao_data['estrategia_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['estrategia_gestao_canvas']).'</td>';
		elseif ($gestao_data['estrategia_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['estrategia_gestao_risco']).'</td>';
		elseif ($gestao_data['estrategia_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['estrategia_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['estrategia_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['estrategia_gestao_indicador']).'</td>';
		elseif ($gestao_data['estrategia_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['estrategia_gestao_calendario']).'</td>';
		elseif ($gestao_data['estrategia_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['estrategia_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['estrategia_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['estrategia_gestao_ata']).'</td>';
		elseif ($gestao_data['estrategia_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['estrategia_gestao_mswot']).'</td>';
		elseif ($gestao_data['estrategia_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['estrategia_gestao_swot']).'</td>';
		elseif ($gestao_data['estrategia_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['estrategia_gestao_operativo']).'</td>';
		elseif ($gestao_data['estrategia_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['estrategia_gestao_instrumento']).'</td>';
		elseif ($gestao_data['estrategia_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['estrategia_gestao_recurso']).'</td>';
		elseif ($gestao_data['estrategia_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['estrategia_gestao_problema']).'</td>';
		elseif ($gestao_data['estrategia_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['estrategia_gestao_demanda']).'</td>';
		elseif ($gestao_data['estrategia_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['estrategia_gestao_programa']).'</td>';
		elseif ($gestao_data['estrategia_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['estrategia_gestao_licao']).'</td>';
		elseif ($gestao_data['estrategia_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['estrategia_gestao_evento']).'</td>';
		elseif ($gestao_data['estrategia_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['estrategia_gestao_link']).'</td>';
		elseif ($gestao_data['estrategia_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['estrategia_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['estrategia_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['estrategia_gestao_tgn']).'</td>';
		elseif ($gestao_data['estrategia_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['estrategia_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['estrategia_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['estrategia_gestao_gut']).'</td>';
		elseif ($gestao_data['estrategia_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['estrategia_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['estrategia_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['estrategia_gestao_arquivo']).'</td>';
		elseif ($gestao_data['estrategia_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['estrategia_gestao_forum']).'</td>';
		elseif ($gestao_data['estrategia_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['estrategia_gestao_checklist']).'</td>';
		elseif ($gestao_data['estrategia_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['estrategia_gestao_agenda']).'</td>';
		elseif ($gestao_data['estrategia_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['estrategia_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['estrategia_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['estrategia_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['estrategia_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['estrategia_gestao_template']).'</td>';
		elseif ($gestao_data['estrategia_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['estrategia_gestao_painel']).'</td>';
		elseif ($gestao_data['estrategia_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['estrategia_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['estrategia_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['estrategia_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['estrategia_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['estrategia_gestao_tr']).'</td>';	
		elseif ($gestao_data['estrategia_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['estrategia_gestao_me']).'</td>';	
		elseif ($gestao_data['estrategia_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['estrategia_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['estrategia_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['estrategia_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['estrategia_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['estrategia_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['estrategia_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['estrategia_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['estrategia_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['estrategia_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['estrategia_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['estrategia_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['estrategia_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['estrategia_gestao_ssti']).'</td>';
		elseif ($gestao_data['estrategia_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['estrategia_gestao_laudo']).'</td>';
		elseif ($gestao_data['estrategia_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['estrategia_gestao_trelo']).'</td>';
		elseif ($gestao_data['estrategia_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['estrategia_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['estrategia_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['estrategia_gestao_pdcl']).'</td>';
		elseif ($gestao_data['estrategia_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['estrategia_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['estrategia_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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