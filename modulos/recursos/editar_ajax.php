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


if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/recursos/editar_ajax_pro.php');

function mudar_posicao_gestao($ordem, $recurso_gestao_id, $direcao, $recurso_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $recurso_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('recurso_gestao');
		$sql->adOnde('recurso_gestao_id != '.(int)$recurso_gestao_id);
		if ($uuid) $sql->adOnde('recurso_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('recurso_gestao_recurso = '.(int)$recurso_id);
		$sql->adOrdem('recurso_gestao_ordem');
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
			$sql->adTabela('recurso_gestao');
			$sql->adAtualizar('recurso_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('recurso_gestao_id = '.(int)$recurso_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('recurso_gestao');
					$sql->adAtualizar('recurso_gestao_ordem', $idx);
					$sql->adOnde('recurso_gestao_id = '.(int)$acao['recurso_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('recurso_gestao');
					$sql->adAtualizar('recurso_gestao_ordem', $idx + 1);
					$sql->adOnde('recurso_gestao_id = '.(int)$acao['recurso_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($recurso_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$recurso_id=0, 
	$uuid='',  
	
	$recurso_projeto=null,
	$recurso_tarefa=null,
	$recurso_perspectiva=null,
	$recurso_tema=null,
	$recurso_objetivo=null,
	$recurso_fator=null,
	$recurso_estrategia=null,
	$recurso_meta=null,
	$recurso_pratica=null,
	$recurso_acao=null,
	$recurso_canvas=null,
	$recurso_risco=null,
	$recurso_risco_resposta=null,
	$recurso_indicador=null,
	$recurso_calendario=null,
	$recurso_monitoramento=null,
	$recurso_ata=null,
	$recurso_mswot=null,
	$recurso_swot=null,
	$recurso_operativo=null,
	$recurso_instrumento=null,
	$recurso_recurso=null,
	$recurso_problema=null,
	$recurso_demanda=null,
	$recurso_programa=null,
	$recurso_licao=null,
	$recurso_evento=null,
	$recurso_link=null,
	$recurso_avaliacao=null,
	$recurso_tgn=null,
	$recurso_brainstorm=null,
	$recurso_gut=null,
	$recurso_causa_efeito=null,
	$recurso_arquivo=null,
	$recurso_forum=null,
	$recurso_checklist=null,
	$recurso_agenda=null,
	$recurso_agrupamento=null,
	$recurso_patrocinador=null,
	$recurso_template=null,
	$recurso_painel=null,
	$recurso_painel_odometro=null,
	$recurso_painel_composicao=null,
	$recurso_tr=null,
	$recurso_me=null,
	$recurso_acao_item=null,
	$recurso_beneficio=null,
	$recurso_painel_slideshow=null,
	$recurso_projeto_viabilidade=null,
	$recurso_projeto_abertura=null,
	$recurso_plano_gestao=null,
	$recurso_ssti=null,
	$recurso_laudo=null,
	$recurso_trelo=null,
	$recurso_trelo_cartao=null,
	$recurso_pdcl=null,
	$recurso_pdcl_item=null
	)
	{
	if (
		$recurso_projeto || 
		$recurso_tarefa || 
		$recurso_perspectiva || 
		$recurso_tema || 
		$recurso_objetivo || 
		$recurso_fator || 
		$recurso_estrategia || 
		$recurso_meta || 
		$recurso_pratica || 
		$recurso_acao || 
		$recurso_canvas || 
		$recurso_risco || 
		$recurso_risco_resposta || 
		$recurso_indicador || 
		$recurso_calendario || 
		$recurso_monitoramento || 
		$recurso_ata || 
		$recurso_mswot || 
		$recurso_swot || 
		$recurso_operativo || 
		$recurso_instrumento || 
		$recurso_recurso || 
		$recurso_problema || 
		$recurso_demanda || 
		$recurso_programa || 
		$recurso_licao || 
		$recurso_evento || 
		$recurso_link || 
		$recurso_avaliacao || 
		$recurso_tgn || 
		$recurso_brainstorm || 
		$recurso_gut || 
		$recurso_causa_efeito || 
		$recurso_arquivo || 
		$recurso_forum || 
		$recurso_checklist || 
		$recurso_agenda || 
		$recurso_agrupamento || 
		$recurso_patrocinador || 
		$recurso_template || 
		$recurso_painel || 
		$recurso_painel_odometro || 
		$recurso_painel_composicao || 
		$recurso_tr || 
		$recurso_me || 
		$recurso_acao_item || 
		$recurso_beneficio || 
		$recurso_painel_slideshow || 
		$recurso_projeto_viabilidade || 
		$recurso_projeto_abertura || 
		$recurso_plano_gestao|| 
		$recurso_ssti || 
		$recurso_laudo || 
		$recurso_trelo || 
		$recurso_trelo_cartao || 
		$recurso_pdcl || 
		$recurso_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('recurso_gestao');
			if ($uuid) $sql->adOnde('recurso_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('recurso_gestao_recurso ='.(int)$recurso_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('recurso_gestao');
		$sql->adCampo('count(recurso_gestao_id)');
		if ($uuid) $sql->adOnde('recurso_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('recurso_gestao_recurso ='.(int)$recurso_id);	
		if ($recurso_tarefa) $sql->adOnde('recurso_gestao_tarefa='.(int)$recurso_tarefa);
		elseif ($recurso_projeto) $sql->adOnde('recurso_gestao_projeto='.(int)$recurso_projeto);
		elseif ($recurso_perspectiva) $sql->adOnde('recurso_gestao_perspectiva='.(int)$recurso_perspectiva);
		elseif ($recurso_tema) $sql->adOnde('recurso_gestao_tema='.(int)$recurso_tema);
		elseif ($recurso_objetivo) $sql->adOnde('recurso_gestao_objetivo='.(int)$recurso_objetivo);
		elseif ($recurso_fator) $sql->adOnde('recurso_gestao_fator='.(int)$recurso_fator);
		elseif ($recurso_estrategia) $sql->adOnde('recurso_gestao_estrategia='.(int)$recurso_estrategia);
		elseif ($recurso_acao) $sql->adOnde('recurso_gestao_acao='.(int)$recurso_acao);
		elseif ($recurso_pratica) $sql->adOnde('recurso_gestao_pratica='.(int)$recurso_pratica);
		elseif ($recurso_meta) $sql->adOnde('recurso_gestao_meta='.(int)$recurso_meta);
		elseif ($recurso_canvas) $sql->adOnde('recurso_gestao_canvas='.(int)$recurso_canvas);
		elseif ($recurso_risco) $sql->adOnde('recurso_gestao_risco='.(int)$recurso_risco);
		elseif ($recurso_risco_resposta) $sql->adOnde('recurso_gestao_risco_resposta='.(int)$recurso_risco_resposta);
		elseif ($recurso_indicador) $sql->adOnde('recurso_gestao_indicador='.(int)$recurso_indicador);
		elseif ($recurso_calendario) $sql->adOnde('recurso_gestao_calendario='.(int)$recurso_calendario);
		elseif ($recurso_monitoramento) $sql->adOnde('recurso_gestao_monitoramento='.(int)$recurso_monitoramento);
		elseif ($recurso_ata) $sql->adOnde('recurso_gestao_ata='.(int)$recurso_ata);
		elseif ($recurso_mswot) $sql->adOnde('recurso_gestao_mswot='.(int)$recurso_mswot);
		elseif ($recurso_swot) $sql->adOnde('recurso_gestao_swot='.(int)$recurso_swot);
		elseif ($recurso_operativo) $sql->adOnde('recurso_gestao_operativo='.(int)$recurso_operativo);
		elseif ($recurso_instrumento) $sql->adOnde('recurso_gestao_instrumento='.(int)$recurso_instrumento);
		
		elseif ($recurso_recurso) $sql->adOnde('recurso_gestao_semelhante='.(int)$recurso_recurso);
		
		elseif ($recurso_problema) $sql->adOnde('recurso_gestao_problema='.(int)$recurso_problema);
		elseif ($recurso_demanda) $sql->adOnde('recurso_gestao_demanda='.(int)$recurso_demanda);
		elseif ($recurso_programa) $sql->adOnde('recurso_gestao_programa='.(int)$recurso_programa);
		elseif ($recurso_licao) $sql->adOnde('recurso_gestao_licao='.(int)$recurso_licao);
		elseif ($recurso_evento) $sql->adOnde('recurso_gestao_evento='.(int)$recurso_evento);
		elseif ($recurso_link) $sql->adOnde('recurso_gestao_link='.(int)$recurso_link);
		elseif ($recurso_avaliacao) $sql->adOnde('recurso_gestao_avaliacao='.(int)$recurso_avaliacao);
		elseif ($recurso_tgn) $sql->adOnde('recurso_gestao_tgn='.(int)$recurso_tgn);
		elseif ($recurso_brainstorm) $sql->adOnde('recurso_gestao_brainstorm='.(int)$recurso_brainstorm);
		elseif ($recurso_gut) $sql->adOnde('recurso_gestao_gut='.(int)$recurso_gut);
		elseif ($recurso_causa_efeito) $sql->adOnde('recurso_gestao_causa_efeito='.(int)$recurso_causa_efeito);
		elseif ($recurso_arquivo) $sql->adOnde('recurso_gestao_arquivo='.(int)$recurso_arquivo);
		elseif ($recurso_forum) $sql->adOnde('recurso_gestao_forum='.(int)$recurso_forum);
		elseif ($recurso_checklist) $sql->adOnde('recurso_gestao_checklist='.(int)$recurso_checklist);
		elseif ($recurso_agenda) $sql->adOnde('recurso_gestao_agenda='.(int)$recurso_agenda);
		elseif ($recurso_agrupamento) $sql->adOnde('recurso_gestao_agrupamento='.(int)$recurso_agrupamento);
		elseif ($recurso_patrocinador) $sql->adOnde('recurso_gestao_patrocinador='.(int)$recurso_patrocinador);
		elseif ($recurso_template) $sql->adOnde('recurso_gestao_template='.(int)$recurso_template);
		elseif ($recurso_painel) $sql->adOnde('recurso_gestao_painel='.(int)$recurso_painel);
		elseif ($recurso_painel_odometro) $sql->adOnde('recurso_gestao_painel_odometro='.(int)$recurso_painel_odometro);
		elseif ($recurso_painel_composicao) $sql->adOnde('recurso_gestao_painel_composicao='.(int)$recurso_painel_composicao);
		elseif ($recurso_tr) $sql->adOnde('recurso_gestao_tr='.(int)$recurso_tr);
		elseif ($recurso_me) $sql->adOnde('recurso_gestao_me='.(int)$recurso_me);
		elseif ($recurso_acao_item) $sql->adOnde('recurso_gestao_acao_item='.(int)$recurso_acao_item);
		elseif ($recurso_beneficio) $sql->adOnde('recurso_gestao_beneficio='.(int)$recurso_beneficio);
		elseif ($recurso_painel_slideshow) $sql->adOnde('recurso_gestao_painel_slideshow='.(int)$recurso_painel_slideshow);
		elseif ($recurso_projeto_viabilidade) $sql->adOnde('recurso_gestao_projeto_viabilidade='.(int)$recurso_projeto_viabilidade);
		elseif ($recurso_projeto_abertura) $sql->adOnde('recurso_gestao_projeto_abertura='.(int)$recurso_projeto_abertura);
		elseif ($recurso_plano_gestao) $sql->adOnde('recurso_gestao_plano_gestao='.(int)$recurso_plano_gestao);
		elseif ($recurso_ssti) $sql->adOnde('recurso_gestao_ssti='.(int)$recurso_ssti);
		elseif ($recurso_laudo) $sql->adOnde('recurso_gestao_laudo='.(int)$recurso_laudo);
		elseif ($recurso_trelo) $sql->adOnde('recurso_gestao_trelo='.(int)$recurso_trelo);
		elseif ($recurso_trelo_cartao) $sql->adOnde('recurso_gestao_trelo_cartao='.(int)$recurso_trelo_cartao);
		elseif ($recurso_pdcl) $sql->adOnde('recurso_gestao_pdcl='.(int)$recurso_pdcl);
		elseif ($recurso_pdcl_item) $sql->adOnde('recurso_gestao_pdcl_item='.(int)$recurso_pdcl_item);
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('recurso_gestao');
			$sql->adCampo('MAX(recurso_gestao_ordem)');
			if ($uuid) $sql->adOnde('recurso_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('recurso_gestao_recurso ='.(int)$recurso_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('recurso_gestao');
			if ($uuid) $sql->adInserir('recurso_gestao_uuid', $uuid);
			else $sql->adInserir('recurso_gestao_recurso', (int)$recurso_id);
			
			if ($recurso_tarefa) $sql->adInserir('recurso_gestao_tarefa', (int)$recurso_tarefa);
			if ($recurso_projeto) $sql->adInserir('recurso_gestao_projeto', (int)$recurso_projeto);
			elseif ($recurso_perspectiva) $sql->adInserir('recurso_gestao_perspectiva', (int)$recurso_perspectiva);
			elseif ($recurso_tema) $sql->adInserir('recurso_gestao_tema', (int)$recurso_tema);
			elseif ($recurso_objetivo) $sql->adInserir('recurso_gestao_objetivo', (int)$recurso_objetivo);
			elseif ($recurso_fator) $sql->adInserir('recurso_gestao_fator', (int)$recurso_fator);
			elseif ($recurso_estrategia) $sql->adInserir('recurso_gestao_estrategia', (int)$recurso_estrategia);
			elseif ($recurso_acao) $sql->adInserir('recurso_gestao_acao', (int)$recurso_acao);
			elseif ($recurso_pratica) $sql->adInserir('recurso_gestao_pratica', (int)$recurso_pratica);
			elseif ($recurso_meta) $sql->adInserir('recurso_gestao_meta', (int)$recurso_meta);
			elseif ($recurso_canvas) $sql->adInserir('recurso_gestao_canvas', (int)$recurso_canvas);
			elseif ($recurso_risco) $sql->adInserir('recurso_gestao_risco', (int)$recurso_risco);
			elseif ($recurso_risco_resposta) $sql->adInserir('recurso_gestao_risco_resposta', (int)$recurso_risco_resposta);
			elseif ($recurso_indicador) $sql->adInserir('recurso_gestao_indicador', (int)$recurso_indicador);
			elseif ($recurso_calendario) $sql->adInserir('recurso_gestao_calendario', (int)$recurso_calendario);
			elseif ($recurso_monitoramento) $sql->adInserir('recurso_gestao_monitoramento', (int)$recurso_monitoramento);
			elseif ($recurso_ata) $sql->adInserir('recurso_gestao_ata', (int)$recurso_ata);
			elseif ($recurso_mswot) $sql->adInserir('recurso_gestao_mswot', (int)$recurso_mswot);
			elseif ($recurso_swot) $sql->adInserir('recurso_gestao_swot', (int)$recurso_swot);
			elseif ($recurso_operativo) $sql->adInserir('recurso_gestao_operativo', (int)$recurso_operativo);
			elseif ($recurso_instrumento) $sql->adInserir('recurso_gestao_instrumento', (int)$recurso_instrumento);
			
			elseif ($recurso_recurso) $sql->adInserir('recurso_gestao_semelhante', (int)$recurso_recurso);
			
			elseif ($recurso_problema) $sql->adInserir('recurso_gestao_problema', (int)$recurso_problema);
			elseif ($recurso_demanda) $sql->adInserir('recurso_gestao_demanda', (int)$recurso_demanda);
			elseif ($recurso_programa) $sql->adInserir('recurso_gestao_programa', (int)$recurso_programa);
			elseif ($recurso_licao) $sql->adInserir('recurso_gestao_licao', (int)$recurso_licao);
			elseif ($recurso_evento) $sql->adInserir('recurso_gestao_evento', (int)$recurso_evento);
			elseif ($recurso_link) $sql->adInserir('recurso_gestao_link', (int)$recurso_link);
			elseif ($recurso_avaliacao) $sql->adInserir('recurso_gestao_avaliacao', (int)$recurso_avaliacao);
			elseif ($recurso_tgn) $sql->adInserir('recurso_gestao_tgn', (int)$recurso_tgn);
			elseif ($recurso_brainstorm) $sql->adInserir('recurso_gestao_brainstorm', (int)$recurso_brainstorm);
			elseif ($recurso_gut) $sql->adInserir('recurso_gestao_gut', (int)$recurso_gut);
			elseif ($recurso_causa_efeito) $sql->adInserir('recurso_gestao_causa_efeito', (int)$recurso_causa_efeito);
			elseif ($recurso_arquivo) $sql->adInserir('recurso_gestao_arquivo', (int)$recurso_arquivo);
			elseif ($recurso_forum) $sql->adInserir('recurso_gestao_forum', (int)$recurso_forum);
			elseif ($recurso_checklist) $sql->adInserir('recurso_gestao_checklist', (int)$recurso_checklist);
			elseif ($recurso_agenda) $sql->adInserir('recurso_gestao_agenda', (int)$recurso_agenda);
			elseif ($recurso_agrupamento) $sql->adInserir('recurso_gestao_agrupamento', (int)$recurso_agrupamento);
			elseif ($recurso_patrocinador) $sql->adInserir('recurso_gestao_patrocinador', (int)$recurso_patrocinador);
			elseif ($recurso_template) $sql->adInserir('recurso_gestao_template', (int)$recurso_template);
			elseif ($recurso_painel) $sql->adInserir('recurso_gestao_painel', (int)$recurso_painel);
			elseif ($recurso_painel_odometro) $sql->adInserir('recurso_gestao_painel_odometro', (int)$recurso_painel_odometro);
			elseif ($recurso_painel_composicao) $sql->adInserir('recurso_gestao_painel_composicao', (int)$recurso_painel_composicao);
			elseif ($recurso_tr) $sql->adInserir('recurso_gestao_tr', (int)$recurso_tr);
			elseif ($recurso_me) $sql->adInserir('recurso_gestao_me', (int)$recurso_me);
			elseif ($recurso_acao_item) $sql->adInserir('recurso_gestao_acao_item', (int)$recurso_acao_item);
			elseif ($recurso_beneficio) $sql->adInserir('recurso_gestao_beneficio', (int)$recurso_beneficio);
			elseif ($recurso_painel_slideshow) $sql->adInserir('recurso_gestao_painel_slideshow', (int)$recurso_painel_slideshow);
			elseif ($recurso_projeto_viabilidade) $sql->adInserir('recurso_gestao_projeto_viabilidade', (int)$recurso_projeto_viabilidade);
			elseif ($recurso_projeto_abertura) $sql->adInserir('recurso_gestao_projeto_abertura', (int)$recurso_projeto_abertura);
			elseif ($recurso_plano_gestao) $sql->adInserir('recurso_gestao_plano_gestao', (int)$recurso_plano_gestao);
			elseif ($recurso_ssti) $sql->adInserir('recurso_gestao_ssti', (int)$recurso_ssti);
			elseif ($recurso_laudo) $sql->adInserir('recurso_gestao_laudo', (int)$recurso_laudo);
			elseif ($recurso_trelo) $sql->adInserir('recurso_gestao_trelo', (int)$recurso_trelo);
			elseif ($recurso_trelo_cartao) $sql->adInserir('recurso_gestao_trelo_cartao', (int)$recurso_trelo_cartao);
			elseif ($recurso_pdcl) $sql->adInserir('recurso_gestao_pdcl', (int)$recurso_pdcl);
			elseif ($recurso_pdcl_item) $sql->adInserir('recurso_gestao_pdcl_item', (int)$recurso_pdcl_item);
			$sql->adInserir('recurso_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($recurso_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($recurso_id=0, $uuid='', $recurso_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('recurso_gestao');
	$sql->adOnde('recurso_gestao_id='.(int)$recurso_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($recurso_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($recurso_id=0, $uuid=''){	
	$saida=atualizar_gestao($recurso_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($recurso_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('recurso_gestao');
	$sql->adCampo('recurso_gestao.*');
	if ($uuid) $sql->adOnde('recurso_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('recurso_gestao_recurso ='.(int)$recurso_id);	
	$sql->adOrdem('recurso_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['recurso_gestao_ordem'].', '.$gestao_data['recurso_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['recurso_gestao_ordem'].', '.$gestao_data['recurso_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['recurso_gestao_ordem'].', '.$gestao_data['recurso_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['recurso_gestao_ordem'].', '.$gestao_data['recurso_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['recurso_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['recurso_gestao_tarefa']).'</td>';
		elseif ($gestao_data['recurso_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['recurso_gestao_projeto']).'</td>';
		elseif ($gestao_data['recurso_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['recurso_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['recurso_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['recurso_gestao_tema']).'</td>';
		elseif ($gestao_data['recurso_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['recurso_gestao_objetivo']).'</td>';
		elseif ($gestao_data['recurso_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['recurso_gestao_fator']).'</td>';
		elseif ($gestao_data['recurso_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['recurso_gestao_estrategia']).'</td>';
		elseif ($gestao_data['recurso_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['recurso_gestao_meta']).'</td>';
		elseif ($gestao_data['recurso_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['recurso_gestao_pratica']).'</td>';
		elseif ($gestao_data['recurso_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['recurso_gestao_acao']).'</td>';
		elseif ($gestao_data['recurso_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['recurso_gestao_canvas']).'</td>';
		elseif ($gestao_data['recurso_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['recurso_gestao_risco']).'</td>';
		elseif ($gestao_data['recurso_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['recurso_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['recurso_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['recurso_gestao_indicador']).'</td>';
		elseif ($gestao_data['recurso_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['recurso_gestao_calendario']).'</td>';
		elseif ($gestao_data['recurso_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['recurso_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['recurso_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['recurso_gestao_ata']).'</td>';
		elseif ($gestao_data['recurso_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['recurso_gestao_mswot']).'</td>';
		elseif ($gestao_data['recurso_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['recurso_gestao_swot']).'</td>';
		elseif ($gestao_data['recurso_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['recurso_gestao_operativo']).'</td>';
		elseif ($gestao_data['recurso_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['recurso_gestao_instrumento']).'</td>';
		
		elseif ($gestao_data['recurso_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['recurso_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['recurso_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['recurso_gestao_problema']).'</td>';
		elseif ($gestao_data['recurso_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['recurso_gestao_demanda']).'</td>';
		elseif ($gestao_data['recurso_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['recurso_gestao_programa']).'</td>';
		elseif ($gestao_data['recurso_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['recurso_gestao_licao']).'</td>';
		elseif ($gestao_data['recurso_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['recurso_gestao_evento']).'</td>';
		elseif ($gestao_data['recurso_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['recurso_gestao_link']).'</td>';
		elseif ($gestao_data['recurso_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['recurso_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['recurso_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['recurso_gestao_tgn']).'</td>';
		elseif ($gestao_data['recurso_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['recurso_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['recurso_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['recurso_gestao_gut']).'</td>';
		elseif ($gestao_data['recurso_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['recurso_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['recurso_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['recurso_gestao_arquivo']).'</td>';
		elseif ($gestao_data['recurso_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['recurso_gestao_forum']).'</td>';
		elseif ($gestao_data['recurso_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['recurso_gestao_checklist']).'</td>';
		elseif ($gestao_data['recurso_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['recurso_gestao_agenda']).'</td>';
		elseif ($gestao_data['recurso_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['recurso_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['recurso_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['recurso_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['recurso_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['recurso_gestao_template']).'</td>';
		elseif ($gestao_data['recurso_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['recurso_gestao_painel']).'</td>';
		elseif ($gestao_data['recurso_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['recurso_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['recurso_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['recurso_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['recurso_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['recurso_gestao_tr']).'</td>';	
		elseif ($gestao_data['recurso_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['recurso_gestao_me']).'</td>';	
		elseif ($gestao_data['recurso_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['recurso_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['recurso_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['recurso_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['recurso_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['recurso_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['recurso_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['recurso_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['recurso_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['recurso_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['recurso_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['recurso_gestao_plano_gestao']).'</td>';	
		
		elseif ($gestao_data['recurso_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['recurso_gestao_ssti']).'</td>';
		elseif ($gestao_data['recurso_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['recurso_gestao_laudo']).'</td>';
		elseif ($gestao_data['recurso_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['recurso_gestao_trelo']).'</td>';
		elseif ($gestao_data['recurso_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['recurso_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['recurso_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['recurso_gestao_pdcl']).'</td>';
		elseif ($gestao_data['recurso_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['recurso_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['recurso_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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
function mudar_nd_ajax($nd_id='', $campo='', $posicao='', $script='', $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento=''){
	$vetor=vetor_nd($nd_id, true, null, $nd_classe, $nd_grupo, $nd_subgrupo, $nd_elemento_subelemento);
	$saida=selecionaVetor($vetor, $campo, $script, $nd_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	

$xajax->registerFunction("mudar_nd_ajax");	
$xajax->registerFunction("selecionar_om_ajax");
$xajax->processRequest();

?>