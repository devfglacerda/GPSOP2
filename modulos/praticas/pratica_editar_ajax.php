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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/pratica_editar_ajax_pro.php';



function mudar_posicao_gestao($ordem, $pratica_gestao_id, $direcao, $pratica_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $pratica_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('pratica_gestao');
		$sql->adOnde('pratica_gestao_id != '.(int)$pratica_gestao_id);
		if ($uuid) $sql->adOnde('pratica_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_gestao_pratica = '.(int)$pratica_id);
		$sql->adOrdem('pratica_gestao_ordem');
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
			$sql->adTabela('pratica_gestao');
			$sql->adAtualizar('pratica_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('pratica_gestao_id = '.(int)$pratica_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('pratica_gestao');
					$sql->adAtualizar('pratica_gestao_ordem', $idx);
					$sql->adOnde('pratica_gestao_id = '.(int)$acao['pratica_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('pratica_gestao');
					$sql->adAtualizar('pratica_gestao_ordem', $idx + 1);
					$sql->adOnde('pratica_gestao_id = '.(int)$acao['pratica_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($pratica_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$pratica_id=0, 
	$uuid='',  
	
	$pratica_projeto=null,
	$pratica_tarefa=null,
	$pratica_perspectiva=null,
	$pratica_tema=null,
	$pratica_objetivo=null,
	$pratica_fator=null,
	$pratica_estrategia=null,
	$pratica_meta=null,
	$pratica_pratica=null,
	$pratica_acao=null,
	$pratica_canvas=null,
	$pratica_risco=null,
	$pratica_risco_resposta=null,
	$pratica_indicador=null,
	$pratica_calendario=null,
	$pratica_monitoramento=null,
	$pratica_ata=null,
	$pratica_mswot=null,
	$pratica_swot=null,
	$pratica_operativo=null,
	$pratica_instrumento=null,
	$pratica_recurso=null,
	$pratica_problema=null,
	$pratica_demanda=null,
	$pratica_programa=null,
	$pratica_licao=null,
	$pratica_evento=null,
	$pratica_link=null,
	$pratica_avaliacao=null,
	$pratica_tgn=null,
	$pratica_brainstorm=null,
	$pratica_gut=null,
	$pratica_causa_efeito=null,
	$pratica_arquivo=null,
	$pratica_forum=null,
	$pratica_checklist=null,
	$pratica_agenda=null,
	$pratica_agrupamento=null,
	$pratica_patrocinador=null,
	$pratica_template=null,
	$pratica_painel=null,
	$pratica_painel_odometro=null,
	$pratica_painel_composicao=null,
	$pratica_tr=null,
	$pratica_me=null,
	$pratica_acao_item=null,
	$pratica_beneficio=null,
	$pratica_painel_slideshow=null,
	$pratica_projeto_viabilidade=null,
	$pratica_projeto_abertura=null,
	$pratica_plano_gestao=null,
	$pratica_ssti=null,
	$pratica_laudo=null,
	$pratica_trelo=null,
	$pratica_trelo_cartao=null,
	$pratica_pdcl=null,
	$pratica_pdcl_item=null
	)
	{
	if (
		$pratica_projeto || 
		$pratica_tarefa || 
		$pratica_perspectiva || 
		$pratica_tema || 
		$pratica_objetivo || 
		$pratica_fator || 
		$pratica_estrategia || 
		$pratica_meta || 
		$pratica_pratica || 
		$pratica_acao || 
		$pratica_canvas || 
		$pratica_risco || 
		$pratica_risco_resposta || 
		$pratica_indicador || 
		$pratica_calendario || 
		$pratica_monitoramento || 
		$pratica_ata || 
		$pratica_mswot || 
		$pratica_swot || 
		$pratica_operativo || 
		$pratica_instrumento || 
		$pratica_recurso || 
		$pratica_problema || 
		$pratica_demanda || 
		$pratica_programa || 
		$pratica_licao || 
		$pratica_evento || 
		$pratica_link || 
		$pratica_avaliacao || 
		$pratica_tgn || 
		$pratica_brainstorm || 
		$pratica_gut || 
		$pratica_causa_efeito || 
		$pratica_arquivo || 
		$pratica_forum || 
		$pratica_checklist || 
		$pratica_agenda || 
		$pratica_agrupamento || 
		$pratica_patrocinador || 
		$pratica_template || 
		$pratica_painel || 
		$pratica_painel_odometro || 
		$pratica_painel_composicao || 
		$pratica_tr || 
		$pratica_me || 
		$pratica_acao_item || 
		$pratica_beneficio || 
		$pratica_painel_slideshow || 
		$pratica_projeto_viabilidade || 
		$pratica_projeto_abertura || 
		$pratica_plano_gestao|| 
		$pratica_ssti || 
		$pratica_laudo || 
		$pratica_trelo || 
		$pratica_trelo_cartao || 
		$pratica_pdcl || 
		$pratica_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('pratica_gestao');
			if ($uuid) $sql->adOnde('pratica_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('pratica_gestao_pratica ='.(int)$pratica_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes	
		$sql->adTabela('pratica_gestao');
		$sql->adCampo('count(pratica_gestao_id)');
		if ($uuid) $sql->adOnde('pratica_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_gestao_pratica ='.(int)$pratica_id);	
		if ($pratica_tarefa) $sql->adOnde('pratica_gestao_tarefa='.(int)$pratica_tarefa);
		elseif ($pratica_projeto) $sql->adOnde('pratica_gestao_projeto='.(int)$pratica_projeto);
		elseif ($pratica_perspectiva) $sql->adOnde('pratica_gestao_perspectiva='.(int)$pratica_perspectiva);
		elseif ($pratica_tema) $sql->adOnde('pratica_gestao_tema='.(int)$pratica_tema);
		elseif ($pratica_objetivo) $sql->adOnde('pratica_gestao_objetivo='.(int)$pratica_objetivo);
		elseif ($pratica_fator) $sql->adOnde('pratica_gestao_fator='.(int)$pratica_fator);
		elseif ($pratica_estrategia) $sql->adOnde('pratica_gestao_estrategia='.(int)$pratica_estrategia);
		elseif ($pratica_acao) $sql->adOnde('pratica_gestao_acao='.(int)$pratica_acao);
		
		elseif ($pratica_pratica) $sql->adOnde('pratica_gestao_semelhante='.(int)$pratica_pratica);
		
		elseif ($pratica_meta) $sql->adOnde('pratica_gestao_meta='.(int)$pratica_meta);
		elseif ($pratica_canvas) $sql->adOnde('pratica_gestao_canvas='.(int)$pratica_canvas);
		elseif ($pratica_risco) $sql->adOnde('pratica_gestao_risco='.(int)$pratica_risco);
		elseif ($pratica_risco_resposta) $sql->adOnde('pratica_gestao_risco_resposta='.(int)$pratica_risco_resposta);
		elseif ($pratica_indicador) $sql->adOnde('pratica_gestao_indicador='.(int)$pratica_indicador);
		elseif ($pratica_calendario) $sql->adOnde('pratica_gestao_calendario='.(int)$pratica_calendario);
		elseif ($pratica_monitoramento) $sql->adOnde('pratica_gestao_monitoramento='.(int)$pratica_monitoramento);
		elseif ($pratica_ata) $sql->adOnde('pratica_gestao_ata='.(int)$pratica_ata);
		elseif ($pratica_mswot) $sql->adOnde('pratica_gestao_mswot='.(int)$pratica_mswot);
		elseif ($pratica_swot) $sql->adOnde('pratica_gestao_swot='.(int)$pratica_swot);
		elseif ($pratica_operativo) $sql->adOnde('pratica_gestao_operativo='.(int)$pratica_operativo);
		elseif ($pratica_instrumento) $sql->adOnde('pratica_gestao_instrumento='.(int)$pratica_instrumento);
		elseif ($pratica_recurso) $sql->adOnde('pratica_gestao_recurso='.(int)$pratica_recurso);
		elseif ($pratica_problema) $sql->adOnde('pratica_gestao_problema='.(int)$pratica_problema);
		elseif ($pratica_demanda) $sql->adOnde('pratica_gestao_demanda='.(int)$pratica_demanda);
		elseif ($pratica_programa) $sql->adOnde('pratica_gestao_programa='.(int)$pratica_programa);
		elseif ($pratica_licao) $sql->adOnde('pratica_gestao_licao='.(int)$pratica_licao);
		elseif ($pratica_evento) $sql->adOnde('pratica_gestao_evento='.(int)$pratica_evento);
		elseif ($pratica_link) $sql->adOnde('pratica_gestao_link='.(int)$pratica_link);
		elseif ($pratica_avaliacao) $sql->adOnde('pratica_gestao_avaliacao='.(int)$pratica_avaliacao);
		elseif ($pratica_tgn) $sql->adOnde('pratica_gestao_tgn='.(int)$pratica_tgn);
		elseif ($pratica_brainstorm) $sql->adOnde('pratica_gestao_brainstorm='.(int)$pratica_brainstorm);
		elseif ($pratica_gut) $sql->adOnde('pratica_gestao_gut='.(int)$pratica_gut);
		elseif ($pratica_causa_efeito) $sql->adOnde('pratica_gestao_causa_efeito='.(int)$pratica_causa_efeito);
		elseif ($pratica_arquivo) $sql->adOnde('pratica_gestao_arquivo='.(int)$pratica_arquivo);
		elseif ($pratica_forum) $sql->adOnde('pratica_gestao_forum='.(int)$pratica_forum);
		elseif ($pratica_checklist) $sql->adOnde('pratica_gestao_checklist='.(int)$pratica_checklist);
		elseif ($pratica_agenda) $sql->adOnde('pratica_gestao_agenda='.(int)$pratica_agenda);
		elseif ($pratica_agrupamento) $sql->adOnde('pratica_gestao_agrupamento='.(int)$pratica_agrupamento);
		elseif ($pratica_patrocinador) $sql->adOnde('pratica_gestao_patrocinador='.(int)$pratica_patrocinador);
		elseif ($pratica_template) $sql->adOnde('pratica_gestao_template='.(int)$pratica_template);
		elseif ($pratica_painel) $sql->adOnde('pratica_gestao_painel='.(int)$pratica_painel);
		elseif ($pratica_painel_odometro) $sql->adOnde('pratica_gestao_painel_odometro='.(int)$pratica_painel_odometro);
		elseif ($pratica_painel_composicao) $sql->adOnde('pratica_gestao_painel_composicao='.(int)$pratica_painel_composicao);
		elseif ($pratica_tr) $sql->adOnde('pratica_gestao_tr='.(int)$pratica_tr);
		elseif ($pratica_me) $sql->adOnde('pratica_gestao_me='.(int)$pratica_me);
		elseif ($pratica_acao_item) $sql->adOnde('pratica_gestao_acao_item='.(int)$pratica_acao_item);
		elseif ($pratica_beneficio) $sql->adOnde('pratica_gestao_beneficio='.(int)$pratica_beneficio);
		elseif ($pratica_painel_slideshow) $sql->adOnde('pratica_gestao_painel_slideshow='.(int)$pratica_painel_slideshow);
		elseif ($pratica_projeto_viabilidade) $sql->adOnde('pratica_gestao_projeto_viabilidade='.(int)$pratica_projeto_viabilidade);
		elseif ($pratica_projeto_abertura) $sql->adOnde('pratica_gestao_projeto_abertura='.(int)$pratica_projeto_abertura);
		elseif ($pratica_plano_gestao) $sql->adOnde('pratica_gestao_plano_gestao='.(int)$pratica_plano_gestao);
		elseif ($pratica_ssti) $sql->adOnde('pratica_gestao_ssti='.(int)$pratica_ssti);
		elseif ($pratica_laudo) $sql->adOnde('pratica_gestao_laudo='.(int)$pratica_laudo);
		elseif ($pratica_trelo) $sql->adOnde('pratica_gestao_trelo='.(int)$pratica_trelo);
		elseif ($pratica_trelo_cartao) $sql->adOnde('pratica_gestao_trelo_cartao='.(int)$pratica_trelo_cartao);
		elseif ($pratica_pdcl) $sql->adOnde('pratica_gestao_pdcl='.(int)$pratica_pdcl);
		elseif ($pratica_pdcl_item) $sql->adOnde('pratica_gestao_pdcl_item='.(int)$pratica_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('pratica_gestao');
			$sql->adCampo('MAX(pratica_gestao_ordem)');
			if ($uuid) $sql->adOnde('pratica_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('pratica_gestao_pratica ='.(int)$pratica_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('pratica_gestao');
			if ($uuid) $sql->adInserir('pratica_gestao_uuid', $uuid);
			else $sql->adInserir('pratica_gestao_pratica', (int)$pratica_id);
			
			if ($pratica_tarefa) $sql->adInserir('pratica_gestao_tarefa', (int)$pratica_tarefa);
			if ($pratica_projeto) $sql->adInserir('pratica_gestao_projeto', (int)$pratica_projeto);
			elseif ($pratica_perspectiva) $sql->adInserir('pratica_gestao_perspectiva', (int)$pratica_perspectiva);
			elseif ($pratica_tema) $sql->adInserir('pratica_gestao_tema', (int)$pratica_tema);
			elseif ($pratica_objetivo) $sql->adInserir('pratica_gestao_objetivo', (int)$pratica_objetivo);
			elseif ($pratica_fator) $sql->adInserir('pratica_gestao_fator', (int)$pratica_fator);
			elseif ($pratica_estrategia) $sql->adInserir('pratica_gestao_estrategia', (int)$pratica_estrategia);
			elseif ($pratica_acao) $sql->adInserir('pratica_gestao_acao', (int)$pratica_acao);
			
			elseif ($pratica_pratica) $sql->adInserir('pratica_gestao_semelhante', (int)$pratica_pratica);
			
			elseif ($pratica_meta) $sql->adInserir('pratica_gestao_meta', (int)$pratica_meta);
			elseif ($pratica_canvas) $sql->adInserir('pratica_gestao_canvas', (int)$pratica_canvas);
			elseif ($pratica_risco) $sql->adInserir('pratica_gestao_risco', (int)$pratica_risco);
			elseif ($pratica_risco_resposta) $sql->adInserir('pratica_gestao_risco_resposta', (int)$pratica_risco_resposta);
			elseif ($pratica_indicador) $sql->adInserir('pratica_gestao_indicador', (int)$pratica_indicador);
			elseif ($pratica_calendario) $sql->adInserir('pratica_gestao_calendario', (int)$pratica_calendario);
			elseif ($pratica_monitoramento) $sql->adInserir('pratica_gestao_monitoramento', (int)$pratica_monitoramento);
			elseif ($pratica_ata) $sql->adInserir('pratica_gestao_ata', (int)$pratica_ata);
			elseif ($pratica_mswot) $sql->adInserir('pratica_gestao_mswot', (int)$pratica_mswot);
			elseif ($pratica_swot) $sql->adInserir('pratica_gestao_swot', (int)$pratica_swot);
			elseif ($pratica_operativo) $sql->adInserir('pratica_gestao_operativo', (int)$pratica_operativo);
			elseif ($pratica_instrumento) $sql->adInserir('pratica_gestao_instrumento', (int)$pratica_instrumento);
			elseif ($pratica_recurso) $sql->adInserir('pratica_gestao_recurso', (int)$pratica_recurso);
			elseif ($pratica_problema) $sql->adInserir('pratica_gestao_problema', (int)$pratica_problema);
			elseif ($pratica_demanda) $sql->adInserir('pratica_gestao_demanda', (int)$pratica_demanda);
			elseif ($pratica_programa) $sql->adInserir('pratica_gestao_programa', (int)$pratica_programa);
			elseif ($pratica_licao) $sql->adInserir('pratica_gestao_licao', (int)$pratica_licao);
			elseif ($pratica_evento) $sql->adInserir('pratica_gestao_evento', (int)$pratica_evento);
			elseif ($pratica_link) $sql->adInserir('pratica_gestao_link', (int)$pratica_link);
			elseif ($pratica_avaliacao) $sql->adInserir('pratica_gestao_avaliacao', (int)$pratica_avaliacao);
			elseif ($pratica_tgn) $sql->adInserir('pratica_gestao_tgn', (int)$pratica_tgn);
			elseif ($pratica_brainstorm) $sql->adInserir('pratica_gestao_brainstorm', (int)$pratica_brainstorm);
			elseif ($pratica_gut) $sql->adInserir('pratica_gestao_gut', (int)$pratica_gut);
			elseif ($pratica_causa_efeito) $sql->adInserir('pratica_gestao_causa_efeito', (int)$pratica_causa_efeito);
			elseif ($pratica_arquivo) $sql->adInserir('pratica_gestao_arquivo', (int)$pratica_arquivo);
			elseif ($pratica_forum) $sql->adInserir('pratica_gestao_forum', (int)$pratica_forum);
			elseif ($pratica_checklist) $sql->adInserir('pratica_gestao_checklist', (int)$pratica_checklist);
			elseif ($pratica_agenda) $sql->adInserir('pratica_gestao_agenda', (int)$pratica_agenda);
			elseif ($pratica_agrupamento) $sql->adInserir('pratica_gestao_agrupamento', (int)$pratica_agrupamento);
			elseif ($pratica_patrocinador) $sql->adInserir('pratica_gestao_patrocinador', (int)$pratica_patrocinador);
			elseif ($pratica_template) $sql->adInserir('pratica_gestao_template', (int)$pratica_template);
			elseif ($pratica_painel) $sql->adInserir('pratica_gestao_painel', (int)$pratica_painel);
			elseif ($pratica_painel_odometro) $sql->adInserir('pratica_gestao_painel_odometro', (int)$pratica_painel_odometro);
			elseif ($pratica_painel_composicao) $sql->adInserir('pratica_gestao_painel_composicao', (int)$pratica_painel_composicao);
			elseif ($pratica_tr) $sql->adInserir('pratica_gestao_tr', (int)$pratica_tr);
			elseif ($pratica_me) $sql->adInserir('pratica_gestao_me', (int)$pratica_me);
			elseif ($pratica_acao_item) $sql->adInserir('pratica_gestao_acao_item', (int)$pratica_acao_item);
			elseif ($pratica_beneficio) $sql->adInserir('pratica_gestao_beneficio', (int)$pratica_beneficio);
			elseif ($pratica_painel_slideshow) $sql->adInserir('pratica_gestao_painel_slideshow', (int)$pratica_painel_slideshow);
			elseif ($pratica_projeto_viabilidade) $sql->adInserir('pratica_gestao_projeto_viabilidade', (int)$pratica_projeto_viabilidade);
			elseif ($pratica_projeto_abertura) $sql->adInserir('pratica_gestao_projeto_abertura', (int)$pratica_projeto_abertura);
			elseif ($pratica_plano_gestao) $sql->adInserir('pratica_gestao_plano_gestao', (int)$pratica_plano_gestao);
			elseif ($pratica_ssti) $sql->adInserir('pratica_gestao_ssti', (int)$pratica_ssti);
			elseif ($pratica_laudo) $sql->adInserir('pratica_gestao_laudo', (int)$pratica_laudo);
			elseif ($pratica_trelo) $sql->adInserir('pratica_gestao_trelo', (int)$pratica_trelo);
			elseif ($pratica_trelo_cartao) $sql->adInserir('pratica_gestao_trelo_cartao', (int)$pratica_trelo_cartao);
			elseif ($pratica_pdcl) $sql->adInserir('pratica_gestao_pdcl', (int)$pratica_pdcl);
			elseif ($pratica_pdcl_item) $sql->adInserir('pratica_gestao_pdcl_item', (int)$pratica_pdcl_item);
	
			$sql->adInserir('pratica_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($pratica_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($pratica_id=0, $uuid='', $pratica_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('pratica_gestao');
	$sql->adOnde('pratica_gestao_id='.(int)$pratica_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($pratica_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($pratica_id=0, $uuid=''){	
	$saida=atualizar_gestao($pratica_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($pratica_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('pratica_gestao');
	$sql->adCampo('pratica_gestao.*');
	if ($uuid) $sql->adOnde('pratica_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_gestao_pratica ='.(int)$pratica_id);	
	$sql->adOrdem('pratica_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_gestao_ordem'].', '.$gestao_data['pratica_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_gestao_ordem'].', '.$gestao_data['pratica_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_gestao_ordem'].', '.$gestao_data['pratica_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_gestao_ordem'].', '.$gestao_data['pratica_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['pratica_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_gestao_tarefa']).'</td>';
		elseif ($gestao_data['pratica_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_gestao_projeto']).'</td>';
		elseif ($gestao_data['pratica_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['pratica_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['pratica_gestao_tema']).'</td>';
		elseif ($gestao_data['pratica_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_gestao_objetivo']).'</td>';
		elseif ($gestao_data['pratica_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_gestao_fator']).'</td>';
		elseif ($gestao_data['pratica_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_gestao_estrategia']).'</td>';
		elseif ($gestao_data['pratica_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_gestao_meta']).'</td>';
		
		elseif ($gestao_data['pratica_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['pratica_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_gestao_acao']).'</td>';
		elseif ($gestao_data['pratica_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_gestao_canvas']).'</td>';
		elseif ($gestao_data['pratica_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['pratica_gestao_risco']).'</td>';
		elseif ($gestao_data['pratica_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['pratica_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['pratica_gestao_indicador']).'</td>';
		elseif ($gestao_data['pratica_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['pratica_gestao_calendario']).'</td>';
		elseif ($gestao_data['pratica_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['pratica_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['pratica_gestao_ata']).'</td>';
		elseif ($gestao_data['pratica_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['pratica_gestao_mswot']).'</td>';
		elseif ($gestao_data['pratica_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['pratica_gestao_swot']).'</td>';
		elseif ($gestao_data['pratica_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_gestao_operativo']).'</td>';
		elseif ($gestao_data['pratica_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_gestao_instrumento']).'</td>';
		elseif ($gestao_data['pratica_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_gestao_recurso']).'</td>';
		elseif ($gestao_data['pratica_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['pratica_gestao_problema']).'</td>';
		elseif ($gestao_data['pratica_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_gestao_demanda']).'</td>';
		elseif ($gestao_data['pratica_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['pratica_gestao_programa']).'</td>';
		elseif ($gestao_data['pratica_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_gestao_licao']).'</td>';
		elseif ($gestao_data['pratica_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_gestao_evento']).'</td>';
		elseif ($gestao_data['pratica_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['pratica_gestao_link']).'</td>';
		elseif ($gestao_data['pratica_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['pratica_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_gestao_tgn']).'</td>';
		elseif ($gestao_data['pratica_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['pratica_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['pratica_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['pratica_gestao_gut']).'</td>';
		elseif ($gestao_data['pratica_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['pratica_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['pratica_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_gestao_arquivo']).'</td>';
		elseif ($gestao_data['pratica_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['pratica_gestao_forum']).'</td>';
		elseif ($gestao_data['pratica_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_gestao_checklist']).'</td>';
		elseif ($gestao_data['pratica_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['pratica_gestao_agenda']).'</td>';
		elseif ($gestao_data['pratica_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['pratica_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['pratica_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['pratica_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['pratica_gestao_template']).'</td>';
		elseif ($gestao_data['pratica_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['pratica_gestao_painel']).'</td>';
		elseif ($gestao_data['pratica_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['pratica_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['pratica_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['pratica_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['pratica_gestao_tr']).'</td>';	
		elseif ($gestao_data['pratica_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['pratica_gestao_me']).'</td>';	
		elseif ($gestao_data['pratica_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['pratica_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['pratica_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['pratica_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['pratica_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['pratica_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['pratica_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['pratica_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['pratica_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['pratica_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['pratica_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['pratica_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['pratica_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['pratica_gestao_ssti']).'</td>';
		elseif ($gestao_data['pratica_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['pratica_gestao_laudo']).'</td>';
		elseif ($gestao_data['pratica_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['pratica_gestao_trelo']).'</td>';
		elseif ($gestao_data['pratica_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['pratica_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['pratica_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['pratica_gestao_pdcl']).'</td>';
		elseif ($gestao_data['pratica_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['pratica_gestao_pdcl_item']).'</td>';

		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['pratica_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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

function marcar_evidencia($pratica_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;

	if (!$marcado){
		$sql->setExcluir('pratica_evidencia');
		if ($uuid) $sql->adOnde('pratica_evidencia_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_evidencia_pratica = '.(int)$pratica_id);
		if ($ano) $sql->adOnde('pratica_evidencia_ano = '.(int)$ano);
		$sql->adOnde('pratica_evidencia_marcador = '.(int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	else{
		//garantir que não ira marcar duas vezes
		$sql->adTabela('pratica_evidencia');
		$sql->adCampo('count(pratica_evidencia_id)');
		if ($uuid) $sql->adOnde('pratica_evidencia_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_evidencia_pratica = '.(int)$pratica_id);
		if ($ano) $sql->adOnde('pratica_evidencia_ano = '.(int)$ano);
		$sql->adOnde('pratica_evidencia_marcador = '.(int)$marcador_id);
		$existe=$sql->Resultado();
		$sql->limpar();
	
		if (!$existe){
			$sql->adTabela('pratica_evidencia');
			if ($uuid) $sql->adInserir('pratica_evidencia_uuid', $uuid);
			else $sql->adInserir('pratica_evidencia_pratica', (int)$pratica_id);
			if ($ano) $sql->adInserir('pratica_evidencia_ano', (int)$ano);
			$sql->adInserir('pratica_evidencia_marcador', (int)$marcador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
$xajax->registerFunction("marcar_evidencia");	


function marcar_complemento($pratica_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	if (!$marcado){
		$sql->setExcluir('pratica_complemento');
		if ($uuid) $sql->adOnde('pratica_complemento_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_complemento_pratica = '.(int)$pratica_id);
		$sql->adOnde('pratica_complemento_ano = '.(int)$ano);
		$sql->adOnde('pratica_complemento_marcador = '.(int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	else{
		//garantir que nso ira marcar duas vezes
		$sql->adTabela('pratica_complemento');
		$sql->adCampo('count(pratica_complemento_id)');
		if ($uuid) $sql->adOnde('pratica_complemento_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_complemento_pratica = '.(int)$pratica_id);
		$sql->adOnde('pratica_complemento_ano = '.(int)$ano);
		$sql->adOnde('pratica_complemento_marcador = '.(int)$marcador_id);
		$existe=$sql->Resultado();
		$sql->limpar();
	
		if (!$existe){
			$sql->adTabela('pratica_complemento');
			if ($uuid) $sql->adInserir('pratica_complemento_uuid', $uuid);
			else $sql->adInserir('pratica_complemento_pratica', (int)$pratica_id);
			$sql->adInserir('pratica_complemento_ano', (int)$ano);
			$sql->adInserir('pratica_complemento_marcador', (int)$marcador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
$xajax->registerFunction("marcar_complemento");	




function marcar_marcador($pratica_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	$sql->setExcluir('pratica_nos_marcadores');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica = '.(int)$pratica_id);
	$sql->adOnde('ano = '.(int)$ano);
	$sql->adOnde('marcador = '.(int)$marcador_id);
	$sql->exec();
	$sql->limpar();

	if ($marcado){
		$sql->adTabela('pratica_nos_marcadores');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica', (int)$pratica_id);
		$sql->adInserir('ano', (int)$ano);
		$sql->adInserir('marcador', (int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	}
	
$xajax->registerFunction("marcar_marcador");	


function marcar_verbo($pratica_id=0, $uuid='', $marcador_id=0, $verbo_id=0, $marcado=false, $ano=0){
	
	$sql = new BDConsulta;
	
	//contar quantas o pai tinha antes
	$sql->adTabela('pratica_nos_verbos');
	$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_verbo_id=verbo');
	$sql->adCampo('count(verbo)');
	$sql->adOnde('ano = '.(int)$ano);
	$sql->adOnde('pratica_verbo_marcador='.(int)$marcador_id);
	if ($uuid) $sql->adOnde('uuid=\''.$uuid.'\'');
	else $sql->adOnde('pratica='.(int)$pratica_id);
	$quantidade=$sql->Resultado();
	$sql->limpar();

	$sql->adTabela('pratica_nos_verbos');
	$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_nos_verbos.verbo=pratica_verbo_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
	
	$sql->setExcluir('pratica_nos_verbos');
	if ($uuid) $sql->adOnde('uuid=\''.$uuid.'\'');
	else $sql->adOnde('pratica='.(int)$pratica_id);
	$sql->adOnde('ano='.(int)$ano);
	$sql->adOnde('verbo='.(int)$verbo_id);
	$sql->exec();
	$sql->limpar();

	if ($marcado){
		$sql->adTabela('pratica_nos_verbos');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica', (int)$pratica_id);
		$sql->adInserir('verbo', (int)$verbo_id);
		$sql->adInserir('ano', (int)$ano);
		$sql->exec();
		$sql->limpar();
		}
	
	//preciso marcar o pai
	if ($marcado && !$quantidade){
		marcar_marcador($pratica_id, $uuid, $marcador_id, true, $ano);
		$objResposta = new xajaxResponse();
		$objResposta->assign('caixa_'.$marcador_id, "style.backgroundColor",	"#FFFF00");
		$objResposta->assign('checagem_'.$marcador_id, "checked",	"checked");
	
		return $objResposta;	
		}
	
	//preciso desmarcar o pai
	if (!$marcado && $quantidade==1){
		marcar_marcador($pratica_id, $uuid, $marcador_id, false, $ano);
		$objResposta = new xajaxResponse();
		$objResposta->assign('caixa_'.$marcador_id, "style.backgroundColor",	"#f8f7f5");
		$objResposta->assign('checagem_'.$marcador_id, "checked",	"");
		return $objResposta;	
		}	
		

	}
	
$xajax->registerFunction("marcar_verbo");	


function mudar_pauta($pratica_id=0, $uuid='', $pratica_modelo_id=0, $ano=0){
	global $config;
	
	$sql = new BDConsulta;
	
	$sql->adTabela('pratica_criterio');
	$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=0');
	$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
	$sql->limpar();
	
	$sql->adTabela('pratica_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_orientacao, pratica_item_oculto');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=0');
	$itens=$sql->ListaChaveSimples('pratica_item_id');
	$sql->limpar();
	
	$sql->adTabela('pratica_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra, pratica_marcador_evidencia, pratica_marcador_orientacao');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=0');
	$sql->adOrdem('pratica_criterio_numero');
	$sql->adOrdem('pratica_item_numero');
	$sql->adOrdem('pratica_marcador_letra');
	$marcadores=$sql->Lista();
	$sql->limpar();
	
	$sql->adTabela('pratica_nos_marcadores');
	$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica = '.(int)$pratica_id);
	$sql->adOnde('ano='.(int)$ano);
	$atuais_marcadores=$sql->carregarColuna();
	$sql->limpar();
	
	
	
	$sql->adTabela('pratica_complemento');
	$sql->esqUnir('praticas', 'praticas', 'pratica_complemento_pratica=praticas.pratica_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_complemento_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_complemento_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_complemento_pratica = '.(int)$pratica_id);
	$sql->adOnde('pratica_complemento_ano='.(int)$ano);
	$atuais_complementos=$sql->carregarColuna();
	$sql->limpar();
	
	
	$sql->adTabela('pratica_evidencia');
	$sql->esqUnir('praticas', 'praticas', 'pratica_evidencia_pratica=praticas.pratica_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_evidencia_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_evidencia_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_evidencia_pratica = '.(int)$pratica_id);
	$sql->adOnde('pratica_evidencia_ano='.(int)$ano);
	$atuais_evidencias=$sql->carregarColuna();
	$sql->limpar();
	
	
	
	
	
	
	$sql->adTabela('pratica_verbo');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item_id=pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio_id=pratica_item_criterio');
	$sql->adCampo('pratica_verbo_id, pratica_verbo_texto, pratica_verbo_marcador');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=0');
	$sql->adOrdem('pratica_criterio_numero');
	$sql->adOrdem('pratica_item_numero');
	$sql->adOrdem('pratica_marcador_letra');
	$sql->adOrdem('pratica_verbo_id');
	$lista_verbos=$sql->Lista();
	$sql->limpar();
	$verbo=array();
	foreach($lista_verbos as $linha) $verbo[$linha['pratica_verbo_marcador']][$linha['pratica_verbo_id']]=$linha['pratica_verbo_texto']; 

	
	$sql->adTabela('pratica_nos_verbos');
	$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_nos_verbos.verbo=pratica_verbo_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item_id=pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio_id=pratica_item_criterio');
	$sql->adCampo('verbo');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica = '.(int)$pratica_id);
	$sql->adOnde('ano='.(int)$ano);
	$atuais_verbos=$sql->carregarColuna();
	$sql->limpar();
	

	$criterio_atual='';
	$item_atual='';
	
	$saida='<table border=0 cellpadding=0 cellspacing=1 width="100%">';
	if ($marcadores && count($marcadores)) $saida.='<tr><td align="left" colspan=2 style="white-space: nowrap"><p><b>'.ucfirst($config['marcadores']).' atendid'.$config['genero_marcador'].'s pel'.$config['genero_pratica'].' '.$config['pratica'].'<b></p></td></tr>';
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			if ($criterio_atual) $saida.='</table></td></tr>';
			$criterio_atual=$dado['pratica_criterio_id'];
			$saida.='<tr><td align="left" colspan=2 style="white-space: nowrap"><b>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</b></td></tr>';
			$saida.='<tr id="criterio_'.$criterio_atual.'"><td colspan=2><table cellpadding=0 cellspacing=0 width="100%">';
			}
			
		if ($dado['pratica_item_id']!=$item_atual){
			$item_atual=$dado['pratica_item_id'];
			if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) $saida.='<tr><td align="left" colspan=20 style="white-space: nowrap">'.($itens[$dado['pratica_item_id']]['pratica_item_orientacao'] ? dica('Orientações', $itens[$dado['pratica_item_id']]['pratica_item_orientacao']) : '').'<b>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</b>'.($itens[$dado['pratica_item_id']]['pratica_item_orientacao'] ? dicaF() : '').'</td></tr>';
			}
		
		$marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_marcadores));
		
		$complemento_marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_complementos));
		$evidencia_marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_evidencias));
		
		$saida.='<tr><td align="right" style="white-space: nowrap" valign="top" width=40><input name="pratica_marcador_id[]" value="'.$dado['pratica_marcador_id'].'" id="checagem_'.$dado['pratica_marcador_id'].'" type="checkbox" DISABLED style="vertical-align:middle"  onclick="marcar_marcador('.$dado['pratica_marcador_id'].');" '.($marcado ? 'checked="checked"' : '').' /><b>'.$dado['pratica_marcador_letra'].'.</b></td><td><table cellpadding=0 cellspacing=0><tr style="line-height: 18px;"><td id="caixa_'.$dado['pratica_marcador_id'].'" '.($marcado ? ' style="vertical-align:top; background-color:#FFFF00;"' : 'style="vertical-align:top"').'>'.($dado['pratica_marcador_orientacao'] ? dica('Orientações', $dado['pratica_marcador_orientacao']) : '').$dado['pratica_marcador_texto'].($dado['pratica_marcador_orientacao'] ? dicaF() : '').'</td></tr></table></td></tr>';
		
		if ($dado['pratica_marcador_extra']) $saida.='<tr><td></td><td align="left" valign="top">'.dica('Complementos para a Excelência','Deverá ser marcado caso '.$config['genero_pratica'].' '.$config['pratica'].' atende os requisitos dos complementos para a excelência.').'<table cellpadding=0 cellspacing=0><tr><td style="vertical-align:top"><input name="pratica_complemento_id[]" '.($complemento_marcado ? 'checked="checked"' : '').' value="'.$dado['pratica_marcador_id'].'" id="complemento_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:top" onclick="marcar_complemento('.$dado['pratica_marcador_id'].');" /></td><td id="caixa3_'.$dado['pratica_marcador_id'].'" '.($complemento_marcado ? 'checked="checked" style="background-color:#abfeff;"' : '').'>'.$dado['pratica_marcador_extra'].'</td></tr></table>'.dicaF().'</td></tr>';
		if ($dado['pratica_marcador_evidencia']) $saida.='<tr><td></td><td align="left" valign="top">'.dica('Evidências','Deverá ser marcado caso '.$config['genero_pratica'].' '.$config['pratica'].' atende os requisitos da evidência.').'<table cellpadding=0 cellspacing=0><tr><td style="vertical-align:top"><input name="pratica_evidencia_id[]" '.($evidencia_marcado ? 'checked="checked"' : '').' value="'.$dado['pratica_marcador_id'].'" id="evidencia_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:top" onclick="marcar_evidencia('.$dado['pratica_marcador_id'].');" /></td><td  id="caixa4_'.$dado['pratica_marcador_id'].'" '.($evidencia_marcado ? 'checked="checked" style="background-color:#abffaf;"' : '').'>'.$dado['pratica_marcador_evidencia'].'</td></tr></table>'.dicaF().'</td></tr>';
		
		
		if (isset($verbo[$dado['pratica_marcador_id']])){
			foreach($verbo[$dado['pratica_marcador_id']] as $chave => $texto) {
				$marcado=in_array($chave, $atuais_verbos);
				$saida.='<tr><td align="left" valign="top">&nbsp;</td><td><table cellpadding=0 cellspacing=0><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<input name="pratica_verbo_id[]" '.($marcado ? 'checked="checked"' : '').' value="'.$chave.'" id="verbo_'.$chave.'" type="checkbox" style="vertical-align:middle" onclick="marcar_verbo('.$chave.', '.$dado['pratica_marcador_id'].');" /></td><td id="caixa2_'.$chave.'" '.($marcado ? 'checked="checked" style="background-color:#ffddab;"' : '').'>'.$texto.'</td></tr></table></td></tr>';
				}
			$saida.='<tr><td colspan=2>&nbsp;</td></tr>';	
			}
		
		
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
	$sql->adOnde('pratica_regra_campo_resultado=0 OR pratica_regra_campo_resultado IS NULL');
	$sql->adOrdem('pratica_regra_campo_id');
	$lista=$sql->Lista();
	$sql->limpar();
	
	$vetor_existe=array(
		'pratica_controlada',
		'pratica_proativa',
		'pratica_abrange_pertinentes',
		'pratica_continuada',
		'pratica_refinada',
		'pratica_melhoria_aprendizado',
		'pratica_coerente',
		'pratica_interrelacionada',
		'pratica_cooperacao',
		'pratica_cooperacao_partes',
		'pratica_arte',
		'pratica_inovacao',
		'pratica_gerencial',
		'pratica_agil',
		'pratica_refinada_implantacao',
		'pratica_incoerente'
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
		
	if (!isset($usou['pratica_controlada'])) $original['pratica_controlada']=dica('Controlad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' é controlad'.$config['genero_pratica'].'.').'Controlad'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_proativa'])) $original['pratica_proativa']=dica('Proativ'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem a capacidade de antecipar-se aos fatos, a fim de prevenir a ocorrência de situações potencialmente indesejáveis e aumentar a confiança e a previsibilidade dos processos gerenciais.').'Proativ'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_abrange_pertinentes'])) $original['pratica_abrange_pertinentes']=dica('Abrangente',($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem cobertura ou escopo suficientes, horizontal ou vertical, conforme pertinente a cada processo gerencial requerido pelas áreas, processos, produtos ou partes interessadas, considerando-se o perfil d'.$config['genero_organizacao'].' '.$config['organizacao'].' e estratégias.').'Abrangente:'.dicaF();
	if (!isset($usou['pratica_continuada'])) $original['pratica_continuada']=dica('Uso Continuado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem utilização periódica e ininterrupta, considerando-se a realização de pelo menos um ciclo completo.').'Uso Continuado:'.dicaF();
	if (!isset($usou['pratica_refinada'])) $original['pratica_refinada']=dica('Refinad'.$config['genero_pratica'], ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta aperfeiçoamento decorrente dos processos de melhoria e inovação.<br><br>Em estágios avançados de refinamento, esse subfator exige processos gerenciais atendidos por '.$config['praticas'].' no estado da arte e que incorporam alguma inovação.').'Refinad'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_melhoria_aprendizado'])) $original['pratica_melhoria_aprendizado']=dica('Melhorias Decorrentes do Aprendizado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta melhorias decorrentes do aprendizado.').'Melhorias pelo aprendizado:'.dicaF();
	if (!isset($usou['pratica_coerente'])) $original['pratica_coerente']=dica('Coerente', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem relação harmônica com as estratégias e objetivos d'.$config['genero_organizacao'].' '.$config['organizacao'].', incluindo valores e princípios.').'Coerente:'.dicaF();
	if (!isset($usou['pratica_interrelacionada'])) $original['pratica_interrelacionada']=dica('Inter-relacionad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem implementação de modo complementar com outr'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].', onde apropriado.').'Inter-relacionad'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_cooperacao'])) $original['pratica_cooperacao']=dica('Cooperativ'.$config['genero_pratica'],'Há colaboração entre as áreas d'.$config['genero_organizacao'].' '.$config['organizacao'].' na implementação – planejamento, execução, controle ou aperfeiçoamento – n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Cooperativ'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_cooperacao_partes'])) $original['pratica_cooperacao_partes']=dica('Cooperação com as Partes Interessadas','Há colaboração com as partes interessadas pertinentes a cada processo gerencial requerido.').'Cooperação com interessados:'.dicaF();
	if (!isset($usou['pratica_arte'])) $original['pratica_arte']=dica('Estado-de-Arte',($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' espelha o estado-da-arte.').'Estado-de-arte:'.dicaF();
	if (!isset($usou['pratica_inovacao'])) $original['pratica_inovacao']=dica('Inovador'.($config['genero_pratica']=='a' ? 'a': ''),($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' apresenta uma inovação de ruptura representando um novo benchmark.').'Inovador'.($config['genero_pratica']=='a' ? 'a': '').':'.dicaF();
	if (!isset($usou['pratica_gerencial'])) $original['pratica_gerencial']=dica('Padrão gerencial','Há padrão gerencial suficiente que oriente a execução adequada d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Padrão gerencial:'.dicaF();
	if (!isset($usou['pratica_agil'])) $original['pratica_agil']=dica('Agilidade','Há agilidade suficiente nos processos gerenciais exigidos no Critério, incorporados n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Agilidade:'.dicaF();
	if (!isset($usou['pratica_agil'])) $original['refinada_implantacao']=dica('Aperfeiçoamento em Implantação','<p>'.$config['genero_pratica'].' '.$config['pratica'].' incorpora ou representa um aperfeiçoamento em implantação.').'Aperfeiçoamento em implantação:'.dicaF();
	if (!isset($usou['pratica_agil'])) $original['pratica_incoerente']=dica('Incoerência grave','Existe incoerência grave entre os valores, princípios, estratégias e objetivos organizacionais, na realização d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Incoerência grave:'.dicaF();
	foreach($original as $chave => $valor) $objResposta->assign('legenda_'.$chave,"innerHTML", utf8_encode($valor));
		
	return $objResposta;
	}

$xajax->registerFunction("mudar_pauta");		
	
	

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