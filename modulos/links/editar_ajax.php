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


if ($Aplic->profissional) include_once BASE_DIR.'/modulos/links/editar_ajax_pro.php';



function mudar_posicao_gestao($ordem, $link_gestao_id, $direcao, $link_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $link_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('link_gestao');
		$sql->adOnde('link_gestao_id != '.(int)$link_gestao_id);
		if ($uuid) $sql->adOnde('link_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('link_gestao_link = '.(int)$link_id);
		$sql->adOrdem('link_gestao_ordem');
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
			$sql->adTabela('link_gestao');
			$sql->adAtualizar('link_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('link_gestao_id = '.(int)$link_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('link_gestao');
					$sql->adAtualizar('link_gestao_ordem', $idx);
					$sql->adOnde('link_gestao_id = '.(int)$acao['link_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('link_gestao');
					$sql->adAtualizar('link_gestao_ordem', $idx + 1);
					$sql->adOnde('link_gestao_id = '.(int)$acao['link_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($link_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$link_id=0, 
	$uuid='',  
	
	$link_projeto=null,
	$link_tarefa=null,
	$link_perspectiva=null,
	$link_tema=null,
	$link_objetivo=null,
	$link_fator=null,
	$link_estrategia=null,
	$link_meta=null,
	$link_pratica=null,
	$link_acao=null,
	$link_canvas=null,
	$link_risco=null,
	$link_risco_resposta=null,
	$link_indicador=null,
	$link_calendario=null,
	$link_monitoramento=null,
	$link_ata=null,
	$link_mswot=null,
	$link_swot=null,
	$link_operativo=null,
	$link_instrumento=null,
	$link_recurso=null,
	$link_problema=null,
	$link_demanda=null,
	$link_programa=null,
	$link_licao=null,
	$link_evento=null,
	$link_link=null,
	$link_avaliacao=null,
	$link_tgn=null,
	$link_brainstorm=null,
	$link_gut=null,
	$link_causa_efeito=null,
	$link_arquivo=null,
	$link_forum=null,
	$link_checklist=null,
	$link_agenda=null,
	$link_agrupamento=null,
	$link_patrocinador=null,
	$link_template=null,
	$link_painel=null,
	$link_painel_odometro=null,
	$link_painel_composicao=null,
	$link_tr=null,
	$link_me=null,
	$link_acao_item=null,
	$link_beneficio=null,
	$link_painel_slideshow=null,
	$link_projeto_viabilidade=null,
	$link_projeto_abertura=null,
	$link_plano_gestao=null,
	$link_ssti=null,
	$link_laudo=null,
	$link_trelo=null,
	$link_trelo_cartao=null,
	$link_pdcl=null,
	$link_pdcl_item=null
	)
	{
	if (
		$link_projeto || 
		$link_tarefa || 
		$link_perspectiva || 
		$link_tema || 
		$link_objetivo || 
		$link_fator || 
		$link_estrategia || 
		$link_meta || 
		$link_pratica || 
		$link_acao || 
		$link_canvas || 
		$link_risco || 
		$link_risco_resposta || 
		$link_indicador || 
		$link_calendario || 
		$link_monitoramento || 
		$link_ata || 
		$link_mswot || 
		$link_swot || 
		$link_operativo || 
		$link_instrumento || 
		$link_recurso || 
		$link_problema || 
		$link_demanda || 
		$link_programa || 
		$link_licao || 
		$link_evento || 
		$link_link || 
		$link_avaliacao || 
		$link_tgn || 
		$link_brainstorm || 
		$link_gut || 
		$link_causa_efeito || 
		$link_arquivo || 
		$link_forum || 
		$link_checklist || 
		$link_agenda || 
		$link_agrupamento || 
		$link_patrocinador || 
		$link_template || 
		$link_painel || 
		$link_painel_odometro || 
		$link_painel_composicao || 
		$link_tr || 
		$link_me || 
		$link_acao_item || 
		$link_beneficio || 
		$link_painel_slideshow || 
		$link_projeto_viabilidade || 
		$link_projeto_abertura || 
		$link_plano_gestao|| 
		$link_ssti || 
		$link_laudo || 
		$link_trelo || 
		$link_trelo_cartao || 
		$link_pdcl || 
		$link_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('link_gestao');
			if ($uuid) $sql->adOnde('link_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('link_gestao_link ='.(int)$link_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('link_gestao');
		$sql->adCampo('count(link_gestao_id)');
		if ($uuid) $sql->adOnde('link_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('link_gestao_link ='.(int)$link_id);	
		if ($link_tarefa) $sql->adOnde('link_gestao_tarefa='.(int)$link_tarefa);
		elseif ($link_projeto) $sql->adOnde('link_gestao_projeto='.(int)$link_projeto);
		elseif ($link_perspectiva) $sql->adOnde('link_gestao_perspectiva='.(int)$link_perspectiva);
		elseif ($link_tema) $sql->adOnde('link_gestao_tema='.(int)$link_tema);
		elseif ($link_objetivo) $sql->adOnde('link_gestao_objetivo='.(int)$link_objetivo);
		elseif ($link_fator) $sql->adOnde('link_gestao_fator='.(int)$link_fator);
		elseif ($link_estrategia) $sql->adOnde('link_gestao_estrategia='.(int)$link_estrategia);
		elseif ($link_acao) $sql->adOnde('link_gestao_acao='.(int)$link_acao);
		elseif ($link_pratica) $sql->adOnde('link_gestao_pratica='.(int)$link_pratica);
		elseif ($link_meta) $sql->adOnde('link_gestao_meta='.(int)$link_meta);
		elseif ($link_canvas) $sql->adOnde('link_gestao_canvas='.(int)$link_canvas);
		elseif ($link_risco) $sql->adOnde('link_gestao_risco='.(int)$link_risco);
		elseif ($link_risco_resposta) $sql->adOnde('link_gestao_risco_resposta='.(int)$link_risco_resposta);
		elseif ($link_indicador) $sql->adOnde('link_gestao_indicador='.(int)$link_indicador);
		elseif ($link_calendario) $sql->adOnde('link_gestao_calendario='.(int)$link_calendario);
		elseif ($link_monitoramento) $sql->adOnde('link_gestao_monitoramento='.(int)$link_monitoramento);
		elseif ($link_ata) $sql->adOnde('link_gestao_ata='.(int)$link_ata);
		elseif ($link_mswot) $sql->adOnde('link_gestao_mswot='.(int)$link_mswot);
		elseif ($link_swot) $sql->adOnde('link_gestao_swot='.(int)$link_swot);
		elseif ($link_operativo) $sql->adOnde('link_gestao_operativo='.(int)$link_operativo);
		elseif ($link_instrumento) $sql->adOnde('link_gestao_instrumento='.(int)$link_instrumento);
		elseif ($link_recurso) $sql->adOnde('link_gestao_recurso='.(int)$link_recurso);
		elseif ($link_problema) $sql->adOnde('link_gestao_problema='.(int)$link_problema);
		elseif ($link_demanda) $sql->adOnde('link_gestao_demanda='.(int)$link_demanda);
		elseif ($link_programa) $sql->adOnde('link_gestao_programa='.(int)$link_programa);
		elseif ($link_licao) $sql->adOnde('link_gestao_licao='.(int)$link_licao);
		elseif ($link_evento) $sql->adOnde('link_gestao_evento='.(int)$link_evento);
		
		elseif ($link_link) $sql->adOnde('link_gestao_semelhante='.(int)$link_link);
		
		elseif ($link_avaliacao) $sql->adOnde('link_gestao_avaliacao='.(int)$link_avaliacao);
		elseif ($link_tgn) $sql->adOnde('link_gestao_tgn='.(int)$link_tgn);
		elseif ($link_brainstorm) $sql->adOnde('link_gestao_brainstorm='.(int)$link_brainstorm);
		elseif ($link_gut) $sql->adOnde('link_gestao_gut='.(int)$link_gut);
		elseif ($link_causa_efeito) $sql->adOnde('link_gestao_causa_efeito='.(int)$link_causa_efeito);
		elseif ($link_arquivo) $sql->adOnde('link_gestao_arquivo='.(int)$link_arquivo);
		elseif ($link_forum) $sql->adOnde('link_gestao_forum='.(int)$link_forum);
		elseif ($link_checklist) $sql->adOnde('link_gestao_checklist='.(int)$link_checklist);
		elseif ($link_agenda) $sql->adOnde('link_gestao_agenda='.(int)$link_agenda);
		elseif ($link_agrupamento) $sql->adOnde('link_gestao_agrupamento='.(int)$link_agrupamento);
		elseif ($link_patrocinador) $sql->adOnde('link_gestao_patrocinador='.(int)$link_patrocinador);
		elseif ($link_template) $sql->adOnde('link_gestao_template='.(int)$link_template);
		elseif ($link_painel) $sql->adOnde('link_gestao_painel='.(int)$link_painel);
		elseif ($link_painel_odometro) $sql->adOnde('link_gestao_painel_odometro='.(int)$link_painel_odometro);
		elseif ($link_painel_composicao) $sql->adOnde('link_gestao_painel_composicao='.(int)$link_painel_composicao);
		elseif ($link_tr) $sql->adOnde('link_gestao_tr='.(int)$link_tr);
		elseif ($link_me) $sql->adOnde('link_gestao_me='.(int)$link_me);
		elseif ($link_acao_item) $sql->adOnde('link_gestao_acao_item='.(int)$link_acao_item);
		elseif ($link_beneficio) $sql->adOnde('link_gestao_beneficio='.(int)$link_beneficio);
		elseif ($link_painel_slideshow) $sql->adOnde('link_gestao_painel_slideshow='.(int)$link_painel_slideshow);
		elseif ($link_projeto_viabilidade) $sql->adOnde('link_gestao_projeto_viabilidade='.(int)$link_projeto_viabilidade);
		elseif ($link_projeto_abertura) $sql->adOnde('link_gestao_projeto_abertura='.(int)$link_projeto_abertura);
		elseif ($link_plano_gestao) $sql->adOnde('link_gestao_plano_gestao='.(int)$link_plano_gestao);
		elseif ($link_ssti) $sql->adOnde('link_gestao_ssti='.(int)$link_ssti);
		elseif ($link_laudo) $sql->adOnde('link_gestao_laudo='.(int)$link_laudo);
		elseif ($link_trelo) $sql->adOnde('link_gestao_trelo='.(int)$link_trelo);
		elseif ($link_trelo_cartao) $sql->adOnde('link_gestao_trelo_cartao='.(int)$link_trelo_cartao);
		elseif ($link_pdcl) $sql->adOnde('link_gestao_pdcl='.(int)$link_pdcl);
		elseif ($link_pdcl_item) $sql->adOnde('link_gestao_pdcl_item='.(int)$link_pdcl_item);
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('link_gestao');
			$sql->adCampo('MAX(link_gestao_ordem)');
			if ($uuid) $sql->adOnde('link_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('link_gestao_link ='.(int)$link_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('link_gestao');
			if ($uuid) $sql->adInserir('link_gestao_uuid', $uuid);
			else $sql->adInserir('link_gestao_link', (int)$link_id);
			
			if ($link_tarefa) $sql->adInserir('link_gestao_tarefa', (int)$link_tarefa);
			if ($link_projeto) $sql->adInserir('link_gestao_projeto', (int)$link_projeto);
			elseif ($link_perspectiva) $sql->adInserir('link_gestao_perspectiva', (int)$link_perspectiva);
			elseif ($link_tema) $sql->adInserir('link_gestao_tema', (int)$link_tema);
			elseif ($link_objetivo) $sql->adInserir('link_gestao_objetivo', (int)$link_objetivo);
			elseif ($link_fator) $sql->adInserir('link_gestao_fator', (int)$link_fator);
			elseif ($link_estrategia) $sql->adInserir('link_gestao_estrategia', (int)$link_estrategia);
			elseif ($link_acao) $sql->adInserir('link_gestao_acao', (int)$link_acao);
			elseif ($link_pratica) $sql->adInserir('link_gestao_pratica', (int)$link_pratica);
			elseif ($link_meta) $sql->adInserir('link_gestao_meta', (int)$link_meta);
			elseif ($link_canvas) $sql->adInserir('link_gestao_canvas', (int)$link_canvas);
			elseif ($link_risco) $sql->adInserir('link_gestao_risco', (int)$link_risco);
			elseif ($link_risco_resposta) $sql->adInserir('link_gestao_risco_resposta', (int)$link_risco_resposta);
			elseif ($link_indicador) $sql->adInserir('link_gestao_indicador', (int)$link_indicador);
			elseif ($link_calendario) $sql->adInserir('link_gestao_calendario', (int)$link_calendario);
			elseif ($link_monitoramento) $sql->adInserir('link_gestao_monitoramento', (int)$link_monitoramento);
			elseif ($link_ata) $sql->adInserir('link_gestao_ata', (int)$link_ata);
			elseif ($link_mswot) $sql->adInserir('link_gestao_mswot', (int)$link_mswot);
			elseif ($link_swot) $sql->adInserir('link_gestao_swot', (int)$link_swot);
			elseif ($link_operativo) $sql->adInserir('link_gestao_operativo', (int)$link_operativo);
			elseif ($link_instrumento) $sql->adInserir('link_gestao_instrumento', (int)$link_instrumento);
			elseif ($link_recurso) $sql->adInserir('link_gestao_recurso', (int)$link_recurso);
			elseif ($link_problema) $sql->adInserir('link_gestao_problema', (int)$link_problema);
			elseif ($link_demanda) $sql->adInserir('link_gestao_demanda', (int)$link_demanda);
			elseif ($link_programa) $sql->adInserir('link_gestao_programa', (int)$link_programa);
			elseif ($link_licao) $sql->adInserir('link_gestao_licao', (int)$link_licao);
			elseif ($link_evento) $sql->adInserir('link_gestao_evento', (int)$link_evento);
			
			elseif ($link_link) $sql->adInserir('link_gestao_semelhante', (int)$link_link);
			
			elseif ($link_avaliacao) $sql->adInserir('link_gestao_avaliacao', (int)$link_avaliacao);
			elseif ($link_tgn) $sql->adInserir('link_gestao_tgn', (int)$link_tgn);
			elseif ($link_brainstorm) $sql->adInserir('link_gestao_brainstorm', (int)$link_brainstorm);
			elseif ($link_gut) $sql->adInserir('link_gestao_gut', (int)$link_gut);
			elseif ($link_causa_efeito) $sql->adInserir('link_gestao_causa_efeito', (int)$link_causa_efeito);
			elseif ($link_arquivo) $sql->adInserir('link_gestao_arquivo', (int)$link_arquivo);
			elseif ($link_forum) $sql->adInserir('link_gestao_forum', (int)$link_forum);
			elseif ($link_checklist) $sql->adInserir('link_gestao_checklist', (int)$link_checklist);
			elseif ($link_agenda) $sql->adInserir('link_gestao_agenda', (int)$link_agenda);
			elseif ($link_agrupamento) $sql->adInserir('link_gestao_agrupamento', (int)$link_agrupamento);
			elseif ($link_patrocinador) $sql->adInserir('link_gestao_patrocinador', (int)$link_patrocinador);
			elseif ($link_template) $sql->adInserir('link_gestao_template', (int)$link_template);
			elseif ($link_painel) $sql->adInserir('link_gestao_painel', (int)$link_painel);
			elseif ($link_painel_odometro) $sql->adInserir('link_gestao_painel_odometro', (int)$link_painel_odometro);
			elseif ($link_painel_composicao) $sql->adInserir('link_gestao_painel_composicao', (int)$link_painel_composicao);
			elseif ($link_tr) $sql->adInserir('link_gestao_tr', (int)$link_tr);
			elseif ($link_me) $sql->adInserir('link_gestao_me', (int)$link_me);
			elseif ($link_acao_item) $sql->adInserir('link_gestao_acao_item', (int)$link_acao_item);
			elseif ($link_beneficio) $sql->adInserir('link_gestao_beneficio', (int)$link_beneficio);
			elseif ($link_painel_slideshow) $sql->adInserir('link_gestao_painel_slideshow', (int)$link_painel_slideshow);
			elseif ($link_projeto_viabilidade) $sql->adInserir('link_gestao_projeto_viabilidade', (int)$link_projeto_viabilidade);
			elseif ($link_projeto_abertura) $sql->adInserir('link_gestao_projeto_abertura', (int)$link_projeto_abertura);
			elseif ($link_plano_gestao) $sql->adInserir('link_gestao_plano_gestao', (int)$link_plano_gestao);
			elseif ($link_ssti) $sql->adInserir('link_gestao_ssti', (int)$link_ssti);
			elseif ($link_laudo) $sql->adInserir('link_gestao_laudo', (int)$link_laudo);
			elseif ($link_trelo) $sql->adInserir('link_gestao_trelo', (int)$link_trelo);
			elseif ($link_trelo_cartao) $sql->adInserir('link_gestao_trelo_cartao', (int)$link_trelo_cartao);
			elseif ($link_pdcl) $sql->adInserir('link_gestao_pdcl', (int)$link_pdcl);
			elseif ($link_pdcl_item) $sql->adInserir('link_gestao_pdcl_item', (int)$link_pdcl_item);
			$sql->adInserir('link_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($link_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($link_id=0, $uuid='', $link_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('link_gestao');
	$sql->adOnde('link_gestao_id='.(int)$link_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($link_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($link_id=0, $uuid=''){	
	$saida=atualizar_gestao($link_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($link_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('link_gestao');
	$sql->adCampo('link_gestao.*');
	if ($uuid) $sql->adOnde('link_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('link_gestao_link ='.(int)$link_id);	
	$sql->adOrdem('link_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['link_gestao_ordem'].', '.$gestao_data['link_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['link_gestao_ordem'].', '.$gestao_data['link_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['link_gestao_ordem'].', '.$gestao_data['link_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['link_gestao_ordem'].', '.$gestao_data['link_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['link_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['link_gestao_tarefa']).'</td>';
		elseif ($gestao_data['link_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['link_gestao_projeto']).'</td>';
		elseif ($gestao_data['link_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['link_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['link_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['link_gestao_tema']).'</td>';
		elseif ($gestao_data['link_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['link_gestao_objetivo']).'</td>';
		elseif ($gestao_data['link_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['link_gestao_fator']).'</td>';
		elseif ($gestao_data['link_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['link_gestao_estrategia']).'</td>';
		elseif ($gestao_data['link_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['link_gestao_meta']).'</td>';
		elseif ($gestao_data['link_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['link_gestao_pratica']).'</td>';
		elseif ($gestao_data['link_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['link_gestao_acao']).'</td>';
		elseif ($gestao_data['link_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['link_gestao_canvas']).'</td>';
		elseif ($gestao_data['link_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['link_gestao_risco']).'</td>';
		elseif ($gestao_data['link_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['link_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['link_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['link_gestao_indicador']).'</td>';
		elseif ($gestao_data['link_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['link_gestao_calendario']).'</td>';
		elseif ($gestao_data['link_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['link_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['link_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['link_gestao_ata']).'</td>';
		elseif ($gestao_data['link_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['link_gestao_mswot']).'</td>';
		elseif ($gestao_data['link_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['link_gestao_swot']).'</td>';
		elseif ($gestao_data['link_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['link_gestao_operativo']).'</td>';
		elseif ($gestao_data['link_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['link_gestao_instrumento']).'</td>';
		elseif ($gestao_data['link_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['link_gestao_recurso']).'</td>';
		elseif ($gestao_data['link_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['link_gestao_problema']).'</td>';
		elseif ($gestao_data['link_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['link_gestao_demanda']).'</td>';
		elseif ($gestao_data['link_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['link_gestao_programa']).'</td>';
		elseif ($gestao_data['link_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['link_gestao_licao']).'</td>';
		elseif ($gestao_data['link_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['link_gestao_evento']).'</td>';
		
		elseif ($gestao_data['link_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['link_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['link_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['link_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['link_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['link_gestao_tgn']).'</td>';
		elseif ($gestao_data['link_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['link_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['link_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['link_gestao_gut']).'</td>';
		elseif ($gestao_data['link_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['link_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['link_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['link_gestao_arquivo']).'</td>';
		elseif ($gestao_data['link_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['link_gestao_forum']).'</td>';
		elseif ($gestao_data['link_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['link_gestao_checklist']).'</td>';
		elseif ($gestao_data['link_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['link_gestao_agenda']).'</td>';
		elseif ($gestao_data['link_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['link_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['link_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['link_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['link_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['link_gestao_template']).'</td>';
		elseif ($gestao_data['link_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['link_gestao_painel']).'</td>';
		elseif ($gestao_data['link_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['link_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['link_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['link_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['link_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['link_gestao_tr']).'</td>';	
		elseif ($gestao_data['link_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['link_gestao_me']).'</td>';	
		elseif ($gestao_data['link_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['link_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['link_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['link_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['link_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['link_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['link_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['link_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['link_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['link_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['link_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['link_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['link_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['link_gestao_ssti']).'</td>';
		elseif ($gestao_data['link_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['link_gestao_laudo']).'</td>';
		elseif ($gestao_data['link_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['link_gestao_trelo']).'</td>';
		elseif ($gestao_data['link_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['link_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['link_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['link_gestao_pdcl']).'</td>';
		elseif ($gestao_data['link_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['link_gestao_pdcl_item']).'</td>';

		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['link_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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