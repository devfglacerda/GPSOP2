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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/projetos/licao_editar_ajax_pro.php');


function mudar_posicao_gestao($ordem, $licao_gestao_id, $direcao, $licao_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $licao_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('licao_gestao');
		$sql->adOnde('licao_gestao_id != '.(int)$licao_gestao_id);
		if ($uuid) $sql->adOnde('licao_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('licao_gestao_licao = '.(int)$licao_id);
		$sql->adOrdem('licao_gestao_ordem');
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
			$sql->adTabela('licao_gestao');
			$sql->adAtualizar('licao_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('licao_gestao_id = '.(int)$licao_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('licao_gestao');
					$sql->adAtualizar('licao_gestao_ordem', $idx);
					$sql->adOnde('licao_gestao_id = '.(int)$acao['licao_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('licao_gestao');
					$sql->adAtualizar('licao_gestao_ordem', $idx + 1);
					$sql->adOnde('licao_gestao_id = '.(int)$acao['licao_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($licao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$licao_id=0, 
	$uuid='',  
	
	$licao_projeto=null,
	$licao_tarefa=null,
	$licao_perspectiva=null,
	$licao_tema=null,
	$licao_objetivo=null,
	$licao_fator=null,
	$licao_estrategia=null,
	$licao_meta=null,
	$licao_pratica=null,
	$licao_acao=null,
	$licao_canvas=null,
	$licao_risco=null,
	$licao_risco_resposta=null,
	$licao_indicador=null,
	$licao_calendario=null,
	$licao_monitoramento=null,
	$licao_ata=null,
	$licao_mswot=null,
	$licao_swot=null,
	$licao_operativo=null,
	$licao_instrumento=null,
	$licao_recurso=null,
	$licao_problema=null,
	$licao_demanda=null,
	$licao_programa=null,
	$licao_licao=null,
	$licao_evento=null,
	$licao_link=null,
	$licao_avaliacao=null,
	$licao_tgn=null,
	$licao_brainstorm=null,
	$licao_gut=null,
	$licao_causa_efeito=null,
	$licao_arquivo=null,
	$licao_forum=null,
	$licao_checklist=null,
	$licao_agenda=null,
	$licao_agrupamento=null,
	$licao_patrocinador=null,
	$licao_template=null,
	$licao_painel=null,
	$licao_painel_odometro=null,
	$licao_painel_composicao=null,
	$licao_tr=null,
	$licao_me=null,
	$licao_acao_item=null,
	$licao_beneficio=null,
	$licao_painel_slideshow=null,
	$licao_projeto_viabilidade=null,
	$licao_projeto_abertura=null,
	$licao_plano_gestao=null,
	$licao_ssti=null,
	$licao_laudo=null,
	$licao_trelo=null,
	$licao_trelo_cartao=null,
	$licao_pdcl=null,
	$licao_pdcl_item=null
	)
	{
	if (
		$licao_projeto || 
		$licao_tarefa || 
		$licao_perspectiva || 
		$licao_tema || 
		$licao_objetivo || 
		$licao_fator || 
		$licao_estrategia || 
		$licao_meta || 
		$licao_pratica || 
		$licao_acao || 
		$licao_canvas || 
		$licao_risco || 
		$licao_risco_resposta || 
		$licao_indicador || 
		$licao_calendario || 
		$licao_monitoramento || 
		$licao_ata || 
		$licao_mswot || 
		$licao_swot || 
		$licao_operativo || 
		$licao_instrumento || 
		$licao_recurso || 
		$licao_problema || 
		$licao_demanda || 
		$licao_programa || 
		$licao_licao || 
		$licao_evento || 
		$licao_link || 
		$licao_avaliacao || 
		$licao_tgn || 
		$licao_brainstorm || 
		$licao_gut || 
		$licao_causa_efeito || 
		$licao_arquivo || 
		$licao_forum || 
		$licao_checklist || 
		$licao_agenda || 
		$licao_agrupamento || 
		$licao_patrocinador || 
		$licao_template || 
		$licao_painel || 
		$licao_painel_odometro || 
		$licao_painel_composicao || 
		$licao_tr || 
		$licao_me || 
		$licao_acao_item || 
		$licao_beneficio || 
		$licao_painel_slideshow || 
		$licao_projeto_viabilidade || 
		$licao_projeto_abertura || 
		$licao_plano_gestao|| 
		$licao_ssti || 
		$licao_laudo || 
		$licao_trelo || 
		$licao_trelo_cartao || 
		$licao_pdcl || 
		$licao_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('licao_gestao');
			if ($uuid) $sql->adOnde('licao_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('licao_gestao_licao ='.(int)$licao_id);		
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('licao_gestao');
		$sql->adCampo('count(licao_gestao_id)');
		if ($uuid) $sql->adOnde('licao_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('licao_gestao_licao ='.(int)$licao_id);	
		if ($licao_tarefa) $sql->adOnde('licao_gestao_tarefa='.(int)$licao_tarefa);
		elseif ($licao_projeto) $sql->adOnde('licao_gestao_projeto='.(int)$licao_projeto);
		elseif ($licao_perspectiva) $sql->adOnde('licao_gestao_perspectiva='.(int)$licao_perspectiva);
		elseif ($licao_tema) $sql->adOnde('licao_gestao_tema='.(int)$licao_tema);
		elseif ($licao_objetivo) $sql->adOnde('licao_gestao_objetivo='.(int)$licao_objetivo);
		elseif ($licao_fator) $sql->adOnde('licao_gestao_fator='.(int)$licao_fator);
		elseif ($licao_estrategia) $sql->adOnde('licao_gestao_estrategia='.(int)$licao_estrategia);
		elseif ($licao_acao) $sql->adOnde('licao_gestao_acao='.(int)$licao_acao);
		elseif ($licao_pratica) $sql->adOnde('licao_gestao_pratica='.(int)$licao_pratica);
		elseif ($licao_meta) $sql->adOnde('licao_gestao_meta='.(int)$licao_meta);
		elseif ($licao_canvas) $sql->adOnde('licao_gestao_canvas='.(int)$licao_canvas);
		elseif ($licao_risco) $sql->adOnde('licao_gestao_risco='.(int)$licao_risco);
		elseif ($licao_risco_resposta) $sql->adOnde('licao_gestao_risco_resposta='.(int)$licao_risco_resposta);
		elseif ($licao_indicador) $sql->adOnde('licao_gestao_indicador='.(int)$licao_indicador);
		elseif ($licao_calendario) $sql->adOnde('licao_gestao_calendario='.(int)$licao_calendario);
		elseif ($licao_monitoramento) $sql->adOnde('licao_gestao_monitoramento='.(int)$licao_monitoramento);
		elseif ($licao_ata) $sql->adOnde('licao_gestao_ata='.(int)$licao_ata);
		elseif ($licao_mswot) $sql->adOnde('licao_gestao_mswot='.(int)$licao_mswot);
		elseif ($licao_swot) $sql->adOnde('licao_gestao_swot='.(int)$licao_swot);
		elseif ($licao_operativo) $sql->adOnde('licao_gestao_operativo='.(int)$licao_operativo);
		elseif ($licao_instrumento) $sql->adOnde('licao_gestao_instrumento='.(int)$licao_instrumento);
		elseif ($licao_recurso) $sql->adOnde('licao_gestao_recurso='.(int)$licao_recurso);
		elseif ($licao_problema) $sql->adOnde('licao_gestao_problema='.(int)$licao_problema);
		elseif ($licao_demanda) $sql->adOnde('licao_gestao_demanda='.(int)$licao_demanda);
		elseif ($licao_programa) $sql->adOnde('licao_gestao_programa='.(int)$licao_programa);
		elseif ($licao_licao) $sql->adOnde('licao_gestao_semelhante='.(int)$licao_licao);
		elseif ($licao_evento) $sql->adOnde('licao_gestao_evento='.(int)$licao_evento);
		elseif ($licao_link) $sql->adOnde('licao_gestao_link='.(int)$licao_link);
		elseif ($licao_avaliacao) $sql->adOnde('licao_gestao_avaliacao='.(int)$licao_avaliacao);
		elseif ($licao_tgn) $sql->adOnde('licao_gestao_tgn='.(int)$licao_tgn);
		elseif ($licao_brainstorm) $sql->adOnde('licao_gestao_brainstorm='.(int)$licao_brainstorm);
		elseif ($licao_gut) $sql->adOnde('licao_gestao_gut='.(int)$licao_gut);
		elseif ($licao_causa_efeito) $sql->adOnde('licao_gestao_causa_efeito='.(int)$licao_causa_efeito);
		elseif ($licao_arquivo) $sql->adOnde('licao_gestao_arquivo='.(int)$licao_arquivo);
		elseif ($licao_forum) $sql->adOnde('licao_gestao_forum='.(int)$licao_forum);
		elseif ($licao_checklist) $sql->adOnde('licao_gestao_checklist='.(int)$licao_checklist);
		elseif ($licao_agenda) $sql->adOnde('licao_gestao_agenda='.(int)$licao_agenda);
		elseif ($licao_agrupamento) $sql->adOnde('licao_gestao_agrupamento='.(int)$licao_agrupamento);
		elseif ($licao_patrocinador) $sql->adOnde('licao_gestao_patrocinador='.(int)$licao_patrocinador);
		elseif ($licao_template) $sql->adOnde('licao_gestao_template='.(int)$licao_template);
		elseif ($licao_painel) $sql->adOnde('licao_gestao_painel='.(int)$licao_painel);
		elseif ($licao_painel_odometro) $sql->adOnde('licao_gestao_painel_odometro='.(int)$licao_painel_odometro);
		elseif ($licao_painel_composicao) $sql->adOnde('licao_gestao_painel_composicao='.(int)$licao_painel_composicao);
		elseif ($licao_tr) $sql->adOnde('licao_gestao_tr='.(int)$licao_tr);
		elseif ($licao_me) $sql->adOnde('licao_gestao_me='.(int)$licao_me);
		elseif ($licao_acao_item) $sql->adOnde('licao_gestao_acao_item='.(int)$licao_acao_item);
		elseif ($licao_beneficio) $sql->adOnde('licao_gestao_beneficio='.(int)$licao_beneficio);
		elseif ($licao_painel_slideshow) $sql->adOnde('licao_gestao_painel_slideshow='.(int)$licao_painel_slideshow);
		elseif ($licao_projeto_viabilidade) $sql->adOnde('licao_gestao_projeto_viabilidade='.(int)$licao_projeto_viabilidade);
		elseif ($licao_projeto_abertura) $sql->adOnde('licao_gestao_projeto_abertura='.(int)$licao_projeto_abertura);
		elseif ($licao_plano_gestao) $sql->adOnde('licao_gestao_plano_gestao='.(int)$licao_plano_gestao);
		elseif ($licao_ssti) $sql->adOnde('licao_gestao_ssti='.(int)$licao_ssti);
		elseif ($licao_laudo) $sql->adOnde('licao_gestao_laudo='.(int)$licao_laudo);
		elseif ($licao_trelo) $sql->adOnde('licao_gestao_trelo='.(int)$licao_trelo);
		elseif ($licao_trelo_cartao) $sql->adOnde('licao_gestao_trelo_cartao='.(int)$licao_trelo_cartao);
		elseif ($licao_pdcl) $sql->adOnde('licao_gestao_pdcl='.(int)$licao_pdcl);
		elseif ($licao_pdcl_item) $sql->adOnde('licao_gestao_pdcl_item='.(int)$licao_pdcl_item);
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('licao_gestao');
			$sql->adCampo('MAX(licao_gestao_ordem)');
			if ($uuid) $sql->adOnde('licao_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('licao_gestao_licao ='.(int)$licao_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('licao_gestao');
			if ($uuid) $sql->adInserir('licao_gestao_uuid', $uuid);
			else $sql->adInserir('licao_gestao_licao', (int)$licao_id);
			
			if ($licao_tarefa) $sql->adInserir('licao_gestao_tarefa', (int)$licao_tarefa);
			if ($licao_projeto) $sql->adInserir('licao_gestao_projeto', (int)$licao_projeto);
			elseif ($licao_perspectiva) $sql->adInserir('licao_gestao_perspectiva', (int)$licao_perspectiva);
			elseif ($licao_tema) $sql->adInserir('licao_gestao_tema', (int)$licao_tema);
			elseif ($licao_objetivo) $sql->adInserir('licao_gestao_objetivo', (int)$licao_objetivo);
			elseif ($licao_fator) $sql->adInserir('licao_gestao_fator', (int)$licao_fator);
			elseif ($licao_estrategia) $sql->adInserir('licao_gestao_estrategia', (int)$licao_estrategia);
			elseif ($licao_acao) $sql->adInserir('licao_gestao_acao', (int)$licao_acao);
			elseif ($licao_pratica) $sql->adInserir('licao_gestao_pratica', (int)$licao_pratica);
			elseif ($licao_meta) $sql->adInserir('licao_gestao_meta', (int)$licao_meta);
			elseif ($licao_canvas) $sql->adInserir('licao_gestao_canvas', (int)$licao_canvas);
			elseif ($licao_risco) $sql->adInserir('licao_gestao_risco', (int)$licao_risco);
			elseif ($licao_risco_resposta) $sql->adInserir('licao_gestao_risco_resposta', (int)$licao_risco_resposta);
			elseif ($licao_indicador) $sql->adInserir('licao_gestao_indicador', (int)$licao_indicador);
			elseif ($licao_calendario) $sql->adInserir('licao_gestao_calendario', (int)$licao_calendario);
			elseif ($licao_monitoramento) $sql->adInserir('licao_gestao_monitoramento', (int)$licao_monitoramento);
			elseif ($licao_ata) $sql->adInserir('licao_gestao_ata', (int)$licao_ata);
			elseif ($licao_mswot) $sql->adInserir('licao_gestao_mswot', (int)$licao_mswot);
			elseif ($licao_swot) $sql->adInserir('licao_gestao_swot', (int)$licao_swot);
			elseif ($licao_operativo) $sql->adInserir('licao_gestao_operativo', (int)$licao_operativo);
			elseif ($licao_instrumento) $sql->adInserir('licao_gestao_instrumento', (int)$licao_instrumento);
			elseif ($licao_recurso) $sql->adInserir('licao_gestao_recurso', (int)$licao_recurso);
			elseif ($licao_problema) $sql->adInserir('licao_gestao_problema', (int)$licao_problema);
			elseif ($licao_demanda) $sql->adInserir('licao_gestao_demanda', (int)$licao_demanda);
			elseif ($licao_programa) $sql->adInserir('licao_gestao_programa', (int)$licao_programa);
			elseif ($licao_licao) $sql->adInserir('licao_gestao_semelhante', (int)$licao_licao);
			elseif ($licao_evento) $sql->adInserir('licao_gestao_evento', (int)$licao_evento);
			elseif ($licao_link) $sql->adInserir('licao_gestao_link', (int)$licao_link);
			elseif ($licao_avaliacao) $sql->adInserir('licao_gestao_avaliacao', (int)$licao_avaliacao);
			elseif ($licao_tgn) $sql->adInserir('licao_gestao_tgn', (int)$licao_tgn);
			elseif ($licao_brainstorm) $sql->adInserir('licao_gestao_brainstorm', (int)$licao_brainstorm);
			elseif ($licao_gut) $sql->adInserir('licao_gestao_gut', (int)$licao_gut);
			elseif ($licao_causa_efeito) $sql->adInserir('licao_gestao_causa_efeito', (int)$licao_causa_efeito);
			elseif ($licao_arquivo) $sql->adInserir('licao_gestao_arquivo', (int)$licao_arquivo);
			elseif ($licao_forum) $sql->adInserir('licao_gestao_forum', (int)$licao_forum);
			elseif ($licao_checklist) $sql->adInserir('licao_gestao_checklist', (int)$licao_checklist);
			elseif ($licao_agenda) $sql->adInserir('licao_gestao_agenda', (int)$licao_agenda);
			elseif ($licao_agrupamento) $sql->adInserir('licao_gestao_agrupamento', (int)$licao_agrupamento);
			elseif ($licao_patrocinador) $sql->adInserir('licao_gestao_patrocinador', (int)$licao_patrocinador);
			elseif ($licao_template) $sql->adInserir('licao_gestao_template', (int)$licao_template);
			elseif ($licao_painel) $sql->adInserir('licao_gestao_painel', (int)$licao_painel);
			elseif ($licao_painel_odometro) $sql->adInserir('licao_gestao_painel_odometro', (int)$licao_painel_odometro);
			elseif ($licao_painel_composicao) $sql->adInserir('licao_gestao_painel_composicao', (int)$licao_painel_composicao);
			elseif ($licao_tr) $sql->adInserir('licao_gestao_tr', (int)$licao_tr);
			elseif ($licao_me) $sql->adInserir('licao_gestao_me', (int)$licao_me);
			elseif ($licao_acao_item) $sql->adInserir('licao_gestao_acao_item', (int)$licao_acao_item);
			elseif ($licao_beneficio) $sql->adInserir('licao_gestao_beneficio', (int)$licao_beneficio);
			elseif ($licao_painel_slideshow) $sql->adInserir('licao_gestao_painel_slideshow', (int)$licao_painel_slideshow);
			elseif ($licao_projeto_viabilidade) $sql->adInserir('licao_gestao_projeto_viabilidade', (int)$licao_projeto_viabilidade);
			elseif ($licao_projeto_abertura) $sql->adInserir('licao_gestao_projeto_abertura', (int)$licao_projeto_abertura);
			elseif ($licao_plano_gestao) $sql->adInserir('licao_gestao_plano_gestao', (int)$licao_plano_gestao);
			elseif ($licao_ssti) $sql->adInserir('licao_gestao_ssti', (int)$licao_ssti);
			elseif ($licao_laudo) $sql->adInserir('licao_gestao_laudo', (int)$licao_laudo);
			elseif ($licao_trelo) $sql->adInserir('licao_gestao_trelo', (int)$licao_trelo);
			elseif ($licao_trelo_cartao) $sql->adInserir('licao_gestao_trelo_cartao', (int)$licao_trelo_cartao);
			elseif ($licao_pdcl) $sql->adInserir('licao_gestao_pdcl', (int)$licao_pdcl);
			elseif ($licao_pdcl_item) $sql->adInserir('licao_gestao_pdcl_item', (int)$licao_pdcl_item);
			$sql->adInserir('licao_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($licao_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($licao_id=0, $uuid='', $licao_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('licao_gestao');
	$sql->adOnde('licao_gestao_id='.(int)$licao_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($licao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($licao_id=0, $uuid=''){	
	$saida=atualizar_gestao($licao_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($licao_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('licao_gestao');
	$sql->adCampo('licao_gestao.*');
	if ($uuid) $sql->adOnde('licao_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('licao_gestao_licao ='.(int)$licao_id);	
	$sql->adOrdem('licao_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['licao_gestao_ordem'].', '.$gestao_data['licao_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['licao_gestao_ordem'].', '.$gestao_data['licao_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['licao_gestao_ordem'].', '.$gestao_data['licao_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['licao_gestao_ordem'].', '.$gestao_data['licao_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['licao_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['licao_gestao_tarefa']).'</td>';
		elseif ($gestao_data['licao_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['licao_gestao_projeto']).'</td>';
		elseif ($gestao_data['licao_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['licao_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['licao_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['licao_gestao_tema']).'</td>';
		elseif ($gestao_data['licao_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['licao_gestao_objetivo']).'</td>';
		elseif ($gestao_data['licao_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['licao_gestao_fator']).'</td>';
		elseif ($gestao_data['licao_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['licao_gestao_estrategia']).'</td>';
		elseif ($gestao_data['licao_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['licao_gestao_meta']).'</td>';
		elseif ($gestao_data['licao_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['licao_gestao_pratica']).'</td>';
		elseif ($gestao_data['licao_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['licao_gestao_acao']).'</td>';
		elseif ($gestao_data['licao_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['licao_gestao_canvas']).'</td>';
		elseif ($gestao_data['licao_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['licao_gestao_risco']).'</td>';
		elseif ($gestao_data['licao_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['licao_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['licao_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['licao_gestao_indicador']).'</td>';
		elseif ($gestao_data['licao_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['licao_gestao_calendario']).'</td>';
		elseif ($gestao_data['licao_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['licao_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['licao_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['licao_gestao_ata']).'</td>';
		elseif ($gestao_data['licao_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['licao_gestao_mswot']).'</td>';
		elseif ($gestao_data['licao_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['licao_gestao_swot']).'</td>';
		elseif ($gestao_data['licao_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['licao_gestao_operativo']).'</td>';
		elseif ($gestao_data['licao_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['licao_gestao_instrumento']).'</td>';
		elseif ($gestao_data['licao_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['licao_gestao_recurso']).'</td>';
		elseif ($gestao_data['licao_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['licao_gestao_problema']).'</td>';
		elseif ($gestao_data['licao_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['licao_gestao_demanda']).'</td>';
		elseif ($gestao_data['licao_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['licao_gestao_programa']).'</td>';
		
		elseif ($gestao_data['licao_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['licao_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['licao_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['licao_gestao_evento']).'</td>';
		elseif ($gestao_data['licao_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['licao_gestao_link']).'</td>';
		elseif ($gestao_data['licao_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['licao_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['licao_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['licao_gestao_tgn']).'</td>';
		elseif ($gestao_data['licao_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['licao_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['licao_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['licao_gestao_gut']).'</td>';
		elseif ($gestao_data['licao_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['licao_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['licao_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['licao_gestao_arquivo']).'</td>';
		elseif ($gestao_data['licao_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['licao_gestao_forum']).'</td>';
		elseif ($gestao_data['licao_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['licao_gestao_checklist']).'</td>';
		elseif ($gestao_data['licao_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['licao_gestao_agenda']).'</td>';
		elseif ($gestao_data['licao_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['licao_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['licao_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['licao_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['licao_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['licao_gestao_template']).'</td>';
		elseif ($gestao_data['licao_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['licao_gestao_painel']).'</td>';
		elseif ($gestao_data['licao_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['licao_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['licao_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['licao_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['licao_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['licao_gestao_tr']).'</td>';	
		elseif ($gestao_data['licao_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['licao_gestao_me']).'</td>';	
		elseif ($gestao_data['licao_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['licao_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['licao_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['licao_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['licao_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['licao_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['licao_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['licao_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['licao_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['licao_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['licao_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['licao_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['licao_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['licao_gestao_ssti']).'</td>';
		elseif ($gestao_data['licao_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['licao_gestao_laudo']).'</td>';
		elseif ($gestao_data['licao_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['licao_gestao_trelo']).'</td>';
		elseif ($gestao_data['licao_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['licao_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['licao_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['licao_gestao_pdcl']).'</td>';
		elseif ($gestao_data['licao_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['licao_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['licao_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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