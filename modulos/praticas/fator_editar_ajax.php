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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/fator_editar_pro_ajax.php');


function mudar_posicao_gestao($ordem, $fator_gestao_id, $direcao, $fator_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $fator_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('fator_gestao');
		$sql->adOnde('fator_gestao_id != '.(int)$fator_gestao_id);
		if ($uuid) $sql->adOnde('fator_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('fator_gestao_fator = '.(int)$fator_id);
		$sql->adOrdem('fator_gestao_ordem');
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
			$sql->adTabela('fator_gestao');
			$sql->adAtualizar('fator_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('fator_gestao_id = '.(int)$fator_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('fator_gestao');
					$sql->adAtualizar('fator_gestao_ordem', $idx);
					$sql->adOnde('fator_gestao_id = '.(int)$acao['fator_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('fator_gestao');
					$sql->adAtualizar('fator_gestao_ordem', $idx + 1);
					$sql->adOnde('fator_gestao_id = '.(int)$acao['fator_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($fator_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$fator_id=0, 
	$uuid='',  
	
	$fator_projeto=null,
	$fator_tarefa=null,
	$fator_perspectiva=null,
	$fator_tema=null,
	$fator_objetivo=null,
	$fator_fator=null,
	$fator_estrategia=null,
	$fator_meta=null,
	$fator_pratica=null,
	$fator_acao=null,
	$fator_canvas=null,
	$fator_risco=null,
	$fator_risco_resposta=null,
	$fator_indicador=null,
	$fator_calendario=null,
	$fator_monitoramento=null,
	$fator_ata=null,
	$fator_mswot=null,
	$fator_swot=null,
	$fator_operativo=null,
	$fator_instrumento=null,
	$fator_recurso=null,
	$fator_problema=null,
	$fator_demanda=null,
	$fator_programa=null,
	$fator_licao=null,
	$fator_evento=null,
	$fator_link=null,
	$fator_avaliacao=null,
	$fator_tgn=null,
	$fator_brainstorm=null,
	$fator_gut=null,
	$fator_causa_efeito=null,
	$fator_arquivo=null,
	$fator_forum=null,
	$fator_checklist=null,
	$fator_agenda=null,
	$fator_agrupamento=null,
	$fator_patrocinador=null,
	$fator_template=null,
	$fator_painel=null,
	$fator_painel_odometro=null,
	$fator_painel_composicao=null,
	$fator_tr=null,
	$fator_me=null,
	$fator_acao_item=null,
	$fator_beneficio=null,
	$fator_painel_slideshow=null,
	$fator_projeto_viabilidade=null,
	$fator_projeto_abertura=null,
	$fator_plano_gestao=null,
	$fator_ssti=null,
	$fator_laudo=null,
	$fator_trelo=null,
	$fator_trelo_cartao=null,
	$fator_pdcl=null,
	$fator_pdcl_item=null
	)
	{
	if (
		$fator_projeto || 
		$fator_tarefa || 
		$fator_perspectiva || 
		$fator_tema || 
		$fator_objetivo || 
		$fator_fator || 
		$fator_estrategia || 
		$fator_meta || 
		$fator_pratica || 
		$fator_acao || 
		$fator_canvas || 
		$fator_risco || 
		$fator_risco_resposta || 
		$fator_indicador || 
		$fator_calendario || 
		$fator_monitoramento || 
		$fator_ata || 
		$fator_mswot || 
		$fator_swot || 
		$fator_operativo || 
		$fator_instrumento || 
		$fator_recurso || 
		$fator_problema || 
		$fator_demanda || 
		$fator_programa || 
		$fator_licao || 
		$fator_evento || 
		$fator_link || 
		$fator_avaliacao || 
		$fator_tgn || 
		$fator_brainstorm || 
		$fator_gut || 
		$fator_causa_efeito || 
		$fator_arquivo || 
		$fator_forum || 
		$fator_checklist || 
		$fator_agenda || 
		$fator_agrupamento || 
		$fator_patrocinador || 
		$fator_template || 
		$fator_painel || 
		$fator_painel_odometro || 
		$fator_painel_composicao || 
		$fator_tr || 
		$fator_me || 
		$fator_acao_item || 
		$fator_beneficio || 
		$fator_painel_slideshow || 
		$fator_projeto_viabilidade || 
		$fator_projeto_abertura || 
		$fator_plano_gestao|| 
		$fator_ssti || 
		$fator_laudo || 
		$fator_trelo || 
		$fator_trelo_cartao || 
		$fator_pdcl || 
		$fator_pdcl_item
		){
		global $Aplic;
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('fator_gestao');
			if ($uuid) $sql->adOnde('fator_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('fator_gestao_fator ='.(int)$fator_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('fator_gestao');
		$sql->adCampo('count(fator_gestao_id)');
		if ($uuid) $sql->adOnde('fator_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('fator_gestao_fator ='.(int)$fator_id);	
		if ($fator_tarefa) $sql->adOnde('fator_gestao_tarefa='.(int)$fator_tarefa);
		elseif ($fator_projeto) $sql->adOnde('fator_gestao_projeto='.(int)$fator_projeto);
		elseif ($fator_perspectiva) $sql->adOnde('fator_gestao_perspectiva='.(int)$fator_perspectiva);
		elseif ($fator_tema) $sql->adOnde('fator_gestao_tema='.(int)$fator_tema);
		elseif ($fator_objetivo) $sql->adOnde('fator_gestao_objetivo='.(int)$fator_objetivo);
		
		elseif ($fator_fator) $sql->adOnde('fator_gestao_semelhante='.(int)$fator_fator);
		
		elseif ($fator_estrategia) $sql->adOnde('fator_gestao_estrategia='.(int)$fator_estrategia);
		elseif ($fator_acao) $sql->adOnde('fator_gestao_acao='.(int)$fator_acao);
		elseif ($fator_pratica) $sql->adOnde('fator_gestao_pratica='.(int)$fator_pratica);
		elseif ($fator_meta) $sql->adOnde('fator_gestao_meta='.(int)$fator_meta);
		elseif ($fator_canvas) $sql->adOnde('fator_gestao_canvas='.(int)$fator_canvas);
		elseif ($fator_risco) $sql->adOnde('fator_gestao_risco='.(int)$fator_risco);
		elseif ($fator_risco_resposta) $sql->adOnde('fator_gestao_risco_resposta='.(int)$fator_risco_resposta);
		elseif ($fator_indicador) $sql->adOnde('fator_gestao_indicador='.(int)$fator_indicador);
		elseif ($fator_calendario) $sql->adOnde('fator_gestao_calendario='.(int)$fator_calendario);
		elseif ($fator_monitoramento) $sql->adOnde('fator_gestao_monitoramento='.(int)$fator_monitoramento);
		elseif ($fator_ata) $sql->adOnde('fator_gestao_ata='.(int)$fator_ata);
		elseif ($fator_mswot) $sql->adOnde('fator_gestao_mswot='.(int)$fator_mswot);
		elseif ($fator_swot) $sql->adOnde('fator_gestao_swot='.(int)$fator_swot);
		elseif ($fator_operativo) $sql->adOnde('fator_gestao_operativo='.(int)$fator_operativo);
		elseif ($fator_instrumento) $sql->adOnde('fator_gestao_instrumento='.(int)$fator_instrumento);
		elseif ($fator_recurso) $sql->adOnde('fator_gestao_recurso='.(int)$fator_recurso);
		elseif ($fator_problema) $sql->adOnde('fator_gestao_problema='.(int)$fator_problema);
		elseif ($fator_demanda) $sql->adOnde('fator_gestao_demanda='.(int)$fator_demanda);
		elseif ($fator_programa) $sql->adOnde('fator_gestao_programa='.(int)$fator_programa);
		elseif ($fator_licao) $sql->adOnde('fator_gestao_licao='.(int)$fator_licao);
		elseif ($fator_evento) $sql->adOnde('fator_gestao_evento='.(int)$fator_evento);
		elseif ($fator_link) $sql->adOnde('fator_gestao_link='.(int)$fator_link);
		elseif ($fator_avaliacao) $sql->adOnde('fator_gestao_avaliacao='.(int)$fator_avaliacao);
		elseif ($fator_tgn) $sql->adOnde('fator_gestao_tgn='.(int)$fator_tgn);
		elseif ($fator_brainstorm) $sql->adOnde('fator_gestao_brainstorm='.(int)$fator_brainstorm);
		elseif ($fator_gut) $sql->adOnde('fator_gestao_gut='.(int)$fator_gut);
		elseif ($fator_causa_efeito) $sql->adOnde('fator_gestao_causa_efeito='.(int)$fator_causa_efeito);
		elseif ($fator_arquivo) $sql->adOnde('fator_gestao_arquivo='.(int)$fator_arquivo);
		elseif ($fator_forum) $sql->adOnde('fator_gestao_forum='.(int)$fator_forum);
		elseif ($fator_checklist) $sql->adOnde('fator_gestao_checklist='.(int)$fator_checklist);
		elseif ($fator_agenda) $sql->adOnde('fator_gestao_agenda='.(int)$fator_agenda);
		elseif ($fator_agrupamento) $sql->adOnde('fator_gestao_agrupamento='.(int)$fator_agrupamento);
		elseif ($fator_patrocinador) $sql->adOnde('fator_gestao_patrocinador='.(int)$fator_patrocinador);
		elseif ($fator_template) $sql->adOnde('fator_gestao_template='.(int)$fator_template);
		elseif ($fator_painel) $sql->adOnde('fator_gestao_painel='.(int)$fator_painel);
		elseif ($fator_painel_odometro) $sql->adOnde('fator_gestao_painel_odometro='.(int)$fator_painel_odometro);
		elseif ($fator_painel_composicao) $sql->adOnde('fator_gestao_painel_composicao='.(int)$fator_painel_composicao);
		elseif ($fator_tr) $sql->adOnde('fator_gestao_tr='.(int)$fator_tr);
		elseif ($fator_me) $sql->adOnde('fator_gestao_me='.(int)$fator_me);
		elseif ($fator_acao_item) $sql->adOnde('fator_gestao_acao_item='.(int)$fator_acao_item);
		elseif ($fator_beneficio) $sql->adOnde('fator_gestao_beneficio='.(int)$fator_beneficio);
		elseif ($fator_painel_slideshow) $sql->adOnde('fator_gestao_painel_slideshow='.(int)$fator_painel_slideshow);
		elseif ($fator_projeto_viabilidade) $sql->adOnde('fator_gestao_projeto_viabilidade='.(int)$fator_projeto_viabilidade);
		elseif ($fator_projeto_abertura) $sql->adOnde('fator_gestao_projeto_abertura='.(int)$fator_projeto_abertura);
		elseif ($fator_plano_gestao) $sql->adOnde('fator_gestao_plano_gestao='.(int)$fator_plano_gestao);
		elseif ($fator_ssti) $sql->adOnde('fator_gestao_ssti='.(int)$fator_ssti);
		elseif ($fator_laudo) $sql->adOnde('fator_gestao_laudo='.(int)$fator_laudo);
		elseif ($fator_trelo) $sql->adOnde('fator_gestao_trelo='.(int)$fator_trelo);
		elseif ($fator_trelo_cartao) $sql->adOnde('fator_gestao_trelo_cartao='.(int)$fator_trelo_cartao);
		elseif ($fator_pdcl) $sql->adOnde('fator_gestao_pdcl='.(int)$fator_pdcl);
		elseif ($fator_pdcl_item) $sql->adOnde('fator_gestao_pdcl_item='.(int)$fator_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('fator_gestao');
			$sql->adCampo('MAX(fator_gestao_ordem)');
			if ($uuid) $sql->adOnde('fator_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('fator_gestao_fator ='.(int)$fator_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('fator_gestao');
			if ($uuid) $sql->adInserir('fator_gestao_uuid', $uuid);
			else $sql->adInserir('fator_gestao_fator', (int)$fator_id);
			
			if ($fator_tarefa) $sql->adInserir('fator_gestao_tarefa', (int)$fator_tarefa);
			if ($fator_projeto) $sql->adInserir('fator_gestao_projeto', (int)$fator_projeto);
			elseif ($fator_perspectiva) $sql->adInserir('fator_gestao_perspectiva', (int)$fator_perspectiva);
			elseif ($fator_tema) $sql->adInserir('fator_gestao_tema', (int)$fator_tema);
			elseif ($fator_objetivo) $sql->adInserir('fator_gestao_objetivo', (int)$fator_objetivo);
			
			elseif ($fator_fator) $sql->adInserir('fator_gestao_semelhante', (int)$fator_fator);
			
			elseif ($fator_estrategia) $sql->adInserir('fator_gestao_estrategia', (int)$fator_estrategia);
			elseif ($fator_acao) $sql->adInserir('fator_gestao_acao', (int)$fator_acao);
			elseif ($fator_pratica) $sql->adInserir('fator_gestao_pratica', (int)$fator_pratica);
			elseif ($fator_meta) $sql->adInserir('fator_gestao_meta', (int)$fator_meta);
			elseif ($fator_canvas) $sql->adInserir('fator_gestao_canvas', (int)$fator_canvas);
			elseif ($fator_risco) $sql->adInserir('fator_gestao_risco', (int)$fator_risco);
			elseif ($fator_risco_resposta) $sql->adInserir('fator_gestao_risco_resposta', (int)$fator_risco_resposta);
			elseif ($fator_indicador) $sql->adInserir('fator_gestao_indicador', (int)$fator_indicador);
			elseif ($fator_calendario) $sql->adInserir('fator_gestao_calendario', (int)$fator_calendario);
			elseif ($fator_monitoramento) $sql->adInserir('fator_gestao_monitoramento', (int)$fator_monitoramento);
			elseif ($fator_ata) $sql->adInserir('fator_gestao_ata', (int)$fator_ata);
			elseif ($fator_mswot) $sql->adInserir('fator_gestao_mswot', (int)$fator_mswot);
			elseif ($fator_swot) $sql->adInserir('fator_gestao_swot', (int)$fator_swot);
			elseif ($fator_operativo) $sql->adInserir('fator_gestao_operativo', (int)$fator_operativo);
			elseif ($fator_instrumento) $sql->adInserir('fator_gestao_instrumento', (int)$fator_instrumento);
			elseif ($fator_recurso) $sql->adInserir('fator_gestao_recurso', (int)$fator_recurso);
			elseif ($fator_problema) $sql->adInserir('fator_gestao_problema', (int)$fator_problema);
			elseif ($fator_demanda) $sql->adInserir('fator_gestao_demanda', (int)$fator_demanda);
			elseif ($fator_programa) $sql->adInserir('fator_gestao_programa', (int)$fator_programa);
			elseif ($fator_licao) $sql->adInserir('fator_gestao_licao', (int)$fator_licao);
			elseif ($fator_evento) $sql->adInserir('fator_gestao_evento', (int)$fator_evento);
			elseif ($fator_link) $sql->adInserir('fator_gestao_link', (int)$fator_link);
			elseif ($fator_avaliacao) $sql->adInserir('fator_gestao_avaliacao', (int)$fator_avaliacao);
			elseif ($fator_tgn) $sql->adInserir('fator_gestao_tgn', (int)$fator_tgn);
			elseif ($fator_brainstorm) $sql->adInserir('fator_gestao_brainstorm', (int)$fator_brainstorm);
			elseif ($fator_gut) $sql->adInserir('fator_gestao_gut', (int)$fator_gut);
			elseif ($fator_causa_efeito) $sql->adInserir('fator_gestao_causa_efeito', (int)$fator_causa_efeito);
			elseif ($fator_arquivo) $sql->adInserir('fator_gestao_arquivo', (int)$fator_arquivo);
			elseif ($fator_forum) $sql->adInserir('fator_gestao_forum', (int)$fator_forum);
			elseif ($fator_checklist) $sql->adInserir('fator_gestao_checklist', (int)$fator_checklist);
			elseif ($fator_agenda) $sql->adInserir('fator_gestao_agenda', (int)$fator_agenda);
			elseif ($fator_agrupamento) $sql->adInserir('fator_gestao_agrupamento', (int)$fator_agrupamento);
			elseif ($fator_patrocinador) $sql->adInserir('fator_gestao_patrocinador', (int)$fator_patrocinador);
			elseif ($fator_template) $sql->adInserir('fator_gestao_template', (int)$fator_template);
			elseif ($fator_painel) $sql->adInserir('fator_gestao_painel', (int)$fator_painel);
			elseif ($fator_painel_odometro) $sql->adInserir('fator_gestao_painel_odometro', (int)$fator_painel_odometro);
			elseif ($fator_painel_composicao) $sql->adInserir('fator_gestao_painel_composicao', (int)$fator_painel_composicao);
			elseif ($fator_tr) $sql->adInserir('fator_gestao_tr', (int)$fator_tr);
			elseif ($fator_me) $sql->adInserir('fator_gestao_me', (int)$fator_me);
			elseif ($fator_acao_item) $sql->adInserir('fator_gestao_acao_item', (int)$fator_acao_item);
			elseif ($fator_beneficio) $sql->adInserir('fator_gestao_beneficio', (int)$fator_beneficio);
			elseif ($fator_painel_slideshow) $sql->adInserir('fator_gestao_painel_slideshow', (int)$fator_painel_slideshow);
			elseif ($fator_projeto_viabilidade) $sql->adInserir('fator_gestao_projeto_viabilidade', (int)$fator_projeto_viabilidade);
			elseif ($fator_projeto_abertura) $sql->adInserir('fator_gestao_projeto_abertura', (int)$fator_projeto_abertura);
			elseif ($fator_plano_gestao) $sql->adInserir('fator_gestao_plano_gestao', (int)$fator_plano_gestao);
			elseif ($fator_ssti) $sql->adInserir('fator_gestao_ssti', (int)$fator_ssti);
			elseif ($fator_laudo) $sql->adInserir('fator_gestao_laudo', (int)$fator_laudo);
			elseif ($fator_trelo) $sql->adInserir('fator_gestao_trelo', (int)$fator_trelo);
			elseif ($fator_trelo_cartao) $sql->adInserir('fator_gestao_trelo_cartao', (int)$fator_trelo_cartao);
			elseif ($fator_pdcl) $sql->adInserir('fator_gestao_pdcl', (int)$fator_pdcl);
			elseif ($fator_pdcl_item) $sql->adInserir('fator_gestao_pdcl_item', (int)$fator_pdcl_item);
			$sql->adInserir('fator_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($fator_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($fator_id=0, $uuid='', $fator_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('fator_gestao');
	$sql->adOnde('fator_gestao_id='.(int)$fator_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($fator_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($fator_id=0, $uuid=''){	
	$saida=atualizar_gestao($fator_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($fator_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('fator_gestao');
	$sql->adCampo('fator_gestao.*');
	if ($uuid) $sql->adOnde('fator_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('fator_gestao_fator ='.(int)$fator_id);	
	$sql->adOrdem('fator_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['fator_gestao_ordem'].', '.$gestao_data['fator_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['fator_gestao_ordem'].', '.$gestao_data['fator_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['fator_gestao_ordem'].', '.$gestao_data['fator_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['fator_gestao_ordem'].', '.$gestao_data['fator_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['fator_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['fator_gestao_tarefa']).'</td>';
		elseif ($gestao_data['fator_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['fator_gestao_projeto']).'</td>';
		elseif ($gestao_data['fator_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['fator_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['fator_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['fator_gestao_tema']).'</td>';
		elseif ($gestao_data['fator_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['fator_gestao_objetivo']).'</td>';
		
		elseif ($gestao_data['fator_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['fator_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['fator_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['fator_gestao_estrategia']).'</td>';
		elseif ($gestao_data['fator_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['fator_gestao_meta']).'</td>';
		elseif ($gestao_data['fator_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['fator_gestao_pratica']).'</td>';
		elseif ($gestao_data['fator_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['fator_gestao_acao']).'</td>';
		elseif ($gestao_data['fator_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['fator_gestao_canvas']).'</td>';
		elseif ($gestao_data['fator_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['fator_gestao_risco']).'</td>';
		elseif ($gestao_data['fator_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['fator_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['fator_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['fator_gestao_indicador']).'</td>';
		elseif ($gestao_data['fator_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['fator_gestao_calendario']).'</td>';
		elseif ($gestao_data['fator_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['fator_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['fator_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['fator_gestao_ata']).'</td>';
		elseif ($gestao_data['fator_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['fator_gestao_mswot']).'</td>';
		elseif ($gestao_data['fator_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['fator_gestao_swot']).'</td>';
		elseif ($gestao_data['fator_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['fator_gestao_operativo']).'</td>';
		elseif ($gestao_data['fator_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['fator_gestao_instrumento']).'</td>';
		elseif ($gestao_data['fator_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['fator_gestao_recurso']).'</td>';
		elseif ($gestao_data['fator_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['fator_gestao_problema']).'</td>';
		elseif ($gestao_data['fator_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['fator_gestao_demanda']).'</td>';
		elseif ($gestao_data['fator_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['fator_gestao_programa']).'</td>';
		elseif ($gestao_data['fator_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['fator_gestao_licao']).'</td>';
		elseif ($gestao_data['fator_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['fator_gestao_evento']).'</td>';
		elseif ($gestao_data['fator_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['fator_gestao_link']).'</td>';
		elseif ($gestao_data['fator_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['fator_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['fator_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['fator_gestao_tgn']).'</td>';
		elseif ($gestao_data['fator_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['fator_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['fator_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['fator_gestao_gut']).'</td>';
		elseif ($gestao_data['fator_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['fator_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['fator_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['fator_gestao_arquivo']).'</td>';
		elseif ($gestao_data['fator_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['fator_gestao_forum']).'</td>';
		elseif ($gestao_data['fator_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['fator_gestao_checklist']).'</td>';
		elseif ($gestao_data['fator_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['fator_gestao_agenda']).'</td>';
		elseif ($gestao_data['fator_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['fator_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['fator_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['fator_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['fator_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['fator_gestao_template']).'</td>';
		elseif ($gestao_data['fator_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['fator_gestao_painel']).'</td>';
		elseif ($gestao_data['fator_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['fator_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['fator_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['fator_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['fator_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['fator_gestao_tr']).'</td>';	
		elseif ($gestao_data['fator_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['fator_gestao_me']).'</td>';	
		elseif ($gestao_data['fator_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['fator_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['fator_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['fator_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['fator_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['fator_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['fator_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['fator_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['fator_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['fator_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['fator_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['fator_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['fator_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['fator_gestao_ssti']).'</td>';
		elseif ($gestao_data['fator_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['fator_gestao_laudo']).'</td>';
		elseif ($gestao_data['fator_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['fator_gestao_trelo']).'</td>';
		elseif ($gestao_data['fator_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['fator_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['fator_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['fator_gestao_pdcl']).'</td>';
		elseif ($gestao_data['fator_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['fator_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['fator_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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