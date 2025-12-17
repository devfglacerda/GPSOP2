<?php
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/foruns/editar_ajax_pro.php';


function mudar_posicao_gestao($ordem, $forum_gestao_id, $direcao, $forum_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $forum_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('forum_gestao');
		$sql->adOnde('forum_gestao_id != '.(int)$forum_gestao_id);
		if ($uuid) $sql->adOnde('forum_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('forum_gestao_forum = '.(int)$forum_id);
		$sql->adOrdem('forum_gestao_ordem');
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
			$sql->adTabela('forum_gestao');
			$sql->adAtualizar('forum_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('forum_gestao_id = '.(int)$forum_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('forum_gestao');
					$sql->adAtualizar('forum_gestao_ordem', $idx);
					$sql->adOnde('forum_gestao_id = '.(int)$acao['forum_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('forum_gestao');
					$sql->adAtualizar('forum_gestao_ordem', $idx + 1);
					$sql->adOnde('forum_gestao_id = '.(int)$acao['forum_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($forum_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$forum_id=0, 
	$uuid='',  
	
	$forum_projeto=null,
	$forum_tarefa=null,
	$forum_perspectiva=null,
	$forum_tema=null,
	$forum_objetivo=null,
	$forum_fator=null,
	$forum_estrategia=null,
	$forum_meta=null,
	$forum_pratica=null,
	$forum_acao=null,
	$forum_canvas=null,
	$forum_risco=null,
	$forum_risco_resposta=null,
	$forum_indicador=null,
	$forum_calendario=null,
	$forum_monitoramento=null,
	$forum_ata=null,
	$forum_mswot=null,
	$forum_swot=null,
	$forum_operativo=null,
	$forum_instrumento=null,
	$forum_recurso=null,
	$forum_problema=null,
	$forum_demanda=null,
	$forum_programa=null,
	$forum_licao=null,
	$forum_evento=null,
	$forum_link=null,
	$forum_avaliacao=null,
	$forum_tgn=null,
	$forum_brainstorm=null,
	$forum_gut=null,
	$forum_causa_efeito=null,
	$forum_arquivo=null,
	$forum_forum=null,
	$forum_checklist=null,
	$forum_agenda=null,
	$forum_agrupamento=null,
	$forum_patrocinador=null,
	$forum_template=null,
	$forum_painel=null,
	$forum_painel_odometro=null,
	$forum_painel_composicao=null,
	$forum_tr=null,
	$forum_me=null,
	$forum_acao_item=null,
	$forum_beneficio=null,
	$forum_painel_slideshow=null,
	$forum_projeto_viabilidade=null,
	$forum_projeto_abertura=null,
	$forum_plano_gestao=null,
	$forum_ssti=null,
	$forum_laudo=null,
	$forum_trelo=null,
	$forum_trelo_cartao=null,
	$forum_pdcl=null,
	$forum_pdcl_item=null
	)
	{
	if (
		$forum_projeto || 
		$forum_tarefa || 
		$forum_perspectiva || 
		$forum_tema || 
		$forum_objetivo || 
		$forum_fator || 
		$forum_estrategia || 
		$forum_meta || 
		$forum_pratica || 
		$forum_acao || 
		$forum_canvas || 
		$forum_risco || 
		$forum_risco_resposta || 
		$forum_indicador || 
		$forum_calendario || 
		$forum_monitoramento || 
		$forum_ata || 
		$forum_mswot || 
		$forum_swot || 
		$forum_operativo || 
		$forum_instrumento || 
		$forum_recurso || 
		$forum_problema || 
		$forum_demanda || 
		$forum_programa || 
		$forum_licao || 
		$forum_evento || 
		$forum_link || 
		$forum_avaliacao || 
		$forum_tgn || 
		$forum_brainstorm || 
		$forum_gut || 
		$forum_causa_efeito || 
		$forum_arquivo || 
		$forum_forum || 
		$forum_checklist || 
		$forum_agenda || 
		$forum_agrupamento || 
		$forum_patrocinador || 
		$forum_template || 
		$forum_painel || 
		$forum_painel_odometro || 
		$forum_painel_composicao || 
		$forum_tr || 
		$forum_me || 
		$forum_acao_item || 
		$forum_beneficio || 
		$forum_painel_slideshow || 
		$forum_projeto_viabilidade || 
		$forum_projeto_abertura || 
		$forum_plano_gestao|| 
		$forum_ssti || 
		$forum_laudo || 
		$forum_trelo || 
		$forum_trelo_cartao || 
		$forum_pdcl || 
		$forum_pdcl_item
		){
		
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('forum_gestao');
			if ($uuid) $sql->adOnde('forum_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('forum_gestao_forum ='.(int)$forum_id);	
			$sql->exec();
			}
		
		
		//verificar se já não inseriu antes
		$sql->adTabela('forum_gestao');
		$sql->adCampo('count(forum_gestao_id)');
		if ($uuid) $sql->adOnde('forum_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('forum_gestao_forum ='.(int)$forum_id);	
		if ($forum_tarefa) $sql->adOnde('forum_gestao_tarefa='.(int)$forum_tarefa);
		elseif ($forum_projeto) $sql->adOnde('forum_gestao_projeto='.(int)$forum_projeto);
		elseif ($forum_perspectiva) $sql->adOnde('forum_gestao_perspectiva='.(int)$forum_perspectiva);
		elseif ($forum_tema) $sql->adOnde('forum_gestao_tema='.(int)$forum_tema);
		elseif ($forum_objetivo) $sql->adOnde('forum_gestao_objetivo='.(int)$forum_objetivo);
		elseif ($forum_fator) $sql->adOnde('forum_gestao_fator='.(int)$forum_fator);
		elseif ($forum_estrategia) $sql->adOnde('forum_gestao_estrategia='.(int)$forum_estrategia);
		elseif ($forum_acao) $sql->adOnde('forum_gestao_acao='.(int)$forum_acao);
		elseif ($forum_pratica) $sql->adOnde('forum_gestao_pratica='.(int)$forum_pratica);
		elseif ($forum_meta) $sql->adOnde('forum_gestao_meta='.(int)$forum_meta);
		elseif ($forum_canvas) $sql->adOnde('forum_gestao_canvas='.(int)$forum_canvas);
		elseif ($forum_risco) $sql->adOnde('forum_gestao_risco='.(int)$forum_risco);
		elseif ($forum_risco_resposta) $sql->adOnde('forum_gestao_risco_resposta='.(int)$forum_risco_resposta);
		elseif ($forum_indicador) $sql->adOnde('forum_gestao_indicador='.(int)$forum_indicador);
		elseif ($forum_calendario) $sql->adOnde('forum_gestao_calendario='.(int)$forum_calendario);
		elseif ($forum_monitoramento) $sql->adOnde('forum_gestao_monitoramento='.(int)$forum_monitoramento);
		elseif ($forum_ata) $sql->adOnde('forum_gestao_ata='.(int)$forum_ata);
		elseif ($forum_mswot) $sql->adOnde('forum_gestao_mswot='.(int)$forum_mswot);
		elseif ($forum_swot) $sql->adOnde('forum_gestao_swot='.(int)$forum_swot);
		elseif ($forum_operativo) $sql->adOnde('forum_gestao_operativo='.(int)$forum_operativo);
		elseif ($forum_instrumento) $sql->adOnde('forum_gestao_instrumento='.(int)$forum_instrumento);
		elseif ($forum_recurso) $sql->adOnde('forum_gestao_recurso='.(int)$forum_recurso);
		elseif ($forum_problema) $sql->adOnde('forum_gestao_problema='.(int)$forum_problema);
		elseif ($forum_demanda) $sql->adOnde('forum_gestao_demanda='.(int)$forum_demanda);
		elseif ($forum_programa) $sql->adOnde('forum_gestao_programa='.(int)$forum_programa);
		elseif ($forum_licao) $sql->adOnde('forum_gestao_licao='.(int)$forum_licao);
		elseif ($forum_evento) $sql->adOnde('forum_gestao_evento='.(int)$forum_evento);
		elseif ($forum_link) $sql->adOnde('forum_gestao_link='.(int)$forum_link);
		elseif ($forum_avaliacao) $sql->adOnde('forum_gestao_avaliacao='.(int)$forum_avaliacao);
		elseif ($forum_tgn) $sql->adOnde('forum_gestao_tgn='.(int)$forum_tgn);
		elseif ($forum_brainstorm) $sql->adOnde('forum_gestao_brainstorm='.(int)$forum_brainstorm);
		elseif ($forum_gut) $sql->adOnde('forum_gestao_gut='.(int)$forum_gut);
		elseif ($forum_causa_efeito) $sql->adOnde('forum_gestao_causa_efeito='.(int)$forum_causa_efeito);
		elseif ($forum_arquivo) $sql->adOnde('forum_gestao_arquivo='.(int)$forum_arquivo);
		
		elseif ($forum_forum) $sql->adOnde('forum_gestao_semelhante='.(int)$forum_forum);
		
		elseif ($forum_checklist) $sql->adOnde('forum_gestao_checklist='.(int)$forum_checklist);
		elseif ($forum_agenda) $sql->adOnde('forum_gestao_agenda='.(int)$forum_agenda);
		elseif ($forum_agrupamento) $sql->adOnde('forum_gestao_agrupamento='.(int)$forum_agrupamento);
		elseif ($forum_patrocinador) $sql->adOnde('forum_gestao_patrocinador='.(int)$forum_patrocinador);
		elseif ($forum_template) $sql->adOnde('forum_gestao_template='.(int)$forum_template);
		elseif ($forum_painel) $sql->adOnde('forum_gestao_painel='.(int)$forum_painel);
		elseif ($forum_painel_odometro) $sql->adOnde('forum_gestao_painel_odometro='.(int)$forum_painel_odometro);
		elseif ($forum_painel_composicao) $sql->adOnde('forum_gestao_painel_composicao='.(int)$forum_painel_composicao);
		elseif ($forum_tr) $sql->adOnde('forum_gestao_tr='.(int)$forum_tr);
		elseif ($forum_me) $sql->adOnde('forum_gestao_me='.(int)$forum_me);
		elseif ($forum_acao_item) $sql->adOnde('forum_gestao_acao_item='.(int)$forum_acao_item);
		elseif ($forum_beneficio) $sql->adOnde('forum_gestao_beneficio='.(int)$forum_beneficio);
		elseif ($forum_painel_slideshow) $sql->adOnde('forum_gestao_painel_slideshow='.(int)$forum_painel_slideshow);
		elseif ($forum_projeto_viabilidade) $sql->adOnde('forum_gestao_projeto_viabilidade='.(int)$forum_projeto_viabilidade);
		elseif ($forum_projeto_abertura) $sql->adOnde('forum_gestao_projeto_abertura='.(int)$forum_projeto_abertura);
		elseif ($forum_plano_gestao) $sql->adOnde('forum_gestao_plano_gestao='.(int)$forum_plano_gestao);
		elseif ($forum_ssti) $sql->adOnde('forum_gestao_ssti='.(int)$forum_ssti);
		elseif ($forum_laudo) $sql->adOnde('forum_gestao_laudo='.(int)$forum_laudo);
		elseif ($forum_trelo) $sql->adOnde('forum_gestao_trelo='.(int)$forum_trelo);
		elseif ($forum_trelo_cartao) $sql->adOnde('forum_gestao_trelo_cartao='.(int)$forum_trelo_cartao);
		elseif ($forum_pdcl) $sql->adOnde('forum_gestao_pdcl='.(int)$forum_pdcl);
		elseif ($forum_pdcl_item) $sql->adOnde('forum_gestao_pdcl_item='.(int)$forum_pdcl_item);
	  $existe = $sql->Resultado();
	  $sql->limpar();
	  
	  
	  
	  
		if (!$existe){
			$sql->adTabela('forum_gestao');
			$sql->adCampo('MAX(forum_gestao_ordem)');
			if ($uuid) $sql->adOnde('forum_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('forum_gestao_forum ='.(int)$forum_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('forum_gestao');
			if ($uuid) $sql->adInserir('forum_gestao_uuid', $uuid);
			else $sql->adInserir('forum_gestao_forum', (int)$forum_id);
			
			if ($forum_tarefa) $sql->adInserir('forum_gestao_tarefa', (int)$forum_tarefa);
			if ($forum_projeto) $sql->adInserir('forum_gestao_projeto', (int)$forum_projeto);
			elseif ($forum_perspectiva) $sql->adInserir('forum_gestao_perspectiva', (int)$forum_perspectiva);
			elseif ($forum_tema) $sql->adInserir('forum_gestao_tema', (int)$forum_tema);
			elseif ($forum_objetivo) $sql->adInserir('forum_gestao_objetivo', (int)$forum_objetivo);
			elseif ($forum_fator) $sql->adInserir('forum_gestao_fator', (int)$forum_fator);
			elseif ($forum_estrategia) $sql->adInserir('forum_gestao_estrategia', (int)$forum_estrategia);
			elseif ($forum_acao) $sql->adInserir('forum_gestao_acao', (int)$forum_acao);
			elseif ($forum_pratica) $sql->adInserir('forum_gestao_pratica', (int)$forum_pratica);
			elseif ($forum_meta) $sql->adInserir('forum_gestao_meta', (int)$forum_meta);
			elseif ($forum_canvas) $sql->adInserir('forum_gestao_canvas', (int)$forum_canvas);
			elseif ($forum_risco) $sql->adInserir('forum_gestao_risco', (int)$forum_risco);
			elseif ($forum_risco_resposta) $sql->adInserir('forum_gestao_risco_resposta', (int)$forum_risco_resposta);
			elseif ($forum_indicador) $sql->adInserir('forum_gestao_indicador', (int)$forum_indicador);
			elseif ($forum_calendario) $sql->adInserir('forum_gestao_calendario', (int)$forum_calendario);
			elseif ($forum_monitoramento) $sql->adInserir('forum_gestao_monitoramento', (int)$forum_monitoramento);
			elseif ($forum_ata) $sql->adInserir('forum_gestao_ata', (int)$forum_ata);
			elseif ($forum_mswot) $sql->adInserir('forum_gestao_mswot', (int)$forum_mswot);
			elseif ($forum_swot) $sql->adInserir('forum_gestao_swot', (int)$forum_swot);
			elseif ($forum_operativo) $sql->adInserir('forum_gestao_operativo', (int)$forum_operativo);
			elseif ($forum_instrumento) $sql->adInserir('forum_gestao_instrumento', (int)$forum_instrumento);
			elseif ($forum_recurso) $sql->adInserir('forum_gestao_recurso', (int)$forum_recurso);
			elseif ($forum_problema) $sql->adInserir('forum_gestao_problema', (int)$forum_problema);
			elseif ($forum_demanda) $sql->adInserir('forum_gestao_demanda', (int)$forum_demanda);
			elseif ($forum_programa) $sql->adInserir('forum_gestao_programa', (int)$forum_programa);
			elseif ($forum_licao) $sql->adInserir('forum_gestao_licao', (int)$forum_licao);
			elseif ($forum_evento) $sql->adInserir('forum_gestao_evento', (int)$forum_evento);
			elseif ($forum_link) $sql->adInserir('forum_gestao_link', (int)$forum_link);
			elseif ($forum_avaliacao) $sql->adInserir('forum_gestao_avaliacao', (int)$forum_avaliacao);
			elseif ($forum_tgn) $sql->adInserir('forum_gestao_tgn', (int)$forum_tgn);
			elseif ($forum_brainstorm) $sql->adInserir('forum_gestao_brainstorm', (int)$forum_brainstorm);
			elseif ($forum_gut) $sql->adInserir('forum_gestao_gut', (int)$forum_gut);
			elseif ($forum_causa_efeito) $sql->adInserir('forum_gestao_causa_efeito', (int)$forum_causa_efeito);
			elseif ($forum_arquivo) $sql->adInserir('forum_gestao_arquivo', (int)$forum_arquivo);
			
			elseif ($forum_forum) $sql->adInserir('forum_gestao_semelhante', (int)$forum_forum);
			
			elseif ($forum_checklist) $sql->adInserir('forum_gestao_checklist', (int)$forum_checklist);
			elseif ($forum_agenda) $sql->adInserir('forum_gestao_agenda', (int)$forum_agenda);
			elseif ($forum_agrupamento) $sql->adInserir('forum_gestao_agrupamento', (int)$forum_agrupamento);
			elseif ($forum_patrocinador) $sql->adInserir('forum_gestao_patrocinador', (int)$forum_patrocinador);
			elseif ($forum_template) $sql->adInserir('forum_gestao_template', (int)$forum_template);
			elseif ($forum_painel) $sql->adInserir('forum_gestao_painel', (int)$forum_painel);
			elseif ($forum_painel_odometro) $sql->adInserir('forum_gestao_painel_odometro', (int)$forum_painel_odometro);
			elseif ($forum_painel_composicao) $sql->adInserir('forum_gestao_painel_composicao', (int)$forum_painel_composicao);
			elseif ($forum_tr) $sql->adInserir('forum_gestao_tr', (int)$forum_tr);
			elseif ($forum_me) $sql->adInserir('forum_gestao_me', (int)$forum_me);
			elseif ($forum_acao_item) $sql->adInserir('forum_gestao_acao_item', (int)$forum_acao_item);
			elseif ($forum_beneficio) $sql->adInserir('forum_gestao_beneficio', (int)$forum_beneficio);
			elseif ($forum_painel_slideshow) $sql->adInserir('forum_gestao_painel_slideshow', (int)$forum_painel_slideshow);
			elseif ($forum_projeto_viabilidade) $sql->adInserir('forum_gestao_projeto_viabilidade', (int)$forum_projeto_viabilidade);
			elseif ($forum_projeto_abertura) $sql->adInserir('forum_gestao_projeto_abertura', (int)$forum_projeto_abertura);
			elseif ($forum_plano_gestao) $sql->adInserir('forum_gestao_plano_gestao', (int)$forum_plano_gestao);
			elseif ($forum_ssti) $sql->adInserir('forum_gestao_ssti', (int)$forum_ssti);
			elseif ($forum_laudo) $sql->adInserir('forum_gestao_laudo', (int)$forum_laudo);
			elseif ($forum_trelo) $sql->adInserir('forum_gestao_trelo', (int)$forum_trelo);
			elseif ($forum_trelo_cartao) $sql->adInserir('forum_gestao_trelo_cartao', (int)$forum_trelo_cartao);
			elseif ($forum_pdcl) $sql->adInserir('forum_gestao_pdcl', (int)$forum_pdcl);
			elseif ($forum_pdcl_item) $sql->adInserir('forum_gestao_pdcl_item', (int)$forum_pdcl_item);
			$sql->adInserir('forum_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($forum_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($forum_id=0, $uuid='', $forum_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('forum_gestao');
	$sql->adOnde('forum_gestao_id='.(int)$forum_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($forum_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($forum_id=0, $uuid=''){	
	$saida=atualizar_gestao($forum_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($forum_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('forum_gestao');
	$sql->adCampo('forum_gestao.*');
	if ($uuid) $sql->adOnde('forum_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('forum_gestao_forum ='.(int)$forum_id);	
	$sql->adOrdem('forum_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['forum_gestao_ordem'].', '.$gestao_data['forum_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['forum_gestao_ordem'].', '.$gestao_data['forum_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['forum_gestao_ordem'].', '.$gestao_data['forum_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['forum_gestao_ordem'].', '.$gestao_data['forum_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['forum_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['forum_gestao_tarefa']).'</td>';
		elseif ($gestao_data['forum_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['forum_gestao_projeto']).'</td>';
		elseif ($gestao_data['forum_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['forum_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['forum_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['forum_gestao_tema']).'</td>';
		elseif ($gestao_data['forum_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['forum_gestao_objetivo']).'</td>';
		elseif ($gestao_data['forum_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['forum_gestao_fator']).'</td>';
		elseif ($gestao_data['forum_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['forum_gestao_estrategia']).'</td>';
		elseif ($gestao_data['forum_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['forum_gestao_meta']).'</td>';
		elseif ($gestao_data['forum_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['forum_gestao_pratica']).'</td>';
		elseif ($gestao_data['forum_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['forum_gestao_acao']).'</td>';
		elseif ($gestao_data['forum_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['forum_gestao_canvas']).'</td>';
		elseif ($gestao_data['forum_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['forum_gestao_risco']).'</td>';
		elseif ($gestao_data['forum_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['forum_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['forum_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['forum_gestao_indicador']).'</td>';
		elseif ($gestao_data['forum_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['forum_gestao_calendario']).'</td>';
		elseif ($gestao_data['forum_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['forum_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['forum_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['forum_gestao_ata']).'</td>';
		elseif ($gestao_data['forum_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['forum_gestao_mswot']).'</td>';
		elseif ($gestao_data['forum_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['forum_gestao_swot']).'</td>';
		elseif ($gestao_data['forum_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['forum_gestao_operativo']).'</td>';
		elseif ($gestao_data['forum_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['forum_gestao_instrumento']).'</td>';
		elseif ($gestao_data['forum_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['forum_gestao_recurso']).'</td>';
		elseif ($gestao_data['forum_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['forum_gestao_problema']).'</td>';
		elseif ($gestao_data['forum_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['forum_gestao_demanda']).'</td>';
		elseif ($gestao_data['forum_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['forum_gestao_programa']).'</td>';
		elseif ($gestao_data['forum_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['forum_gestao_licao']).'</td>';
		elseif ($gestao_data['forum_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['forum_gestao_evento']).'</td>';
		elseif ($gestao_data['forum_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['forum_gestao_link']).'</td>';
		elseif ($gestao_data['forum_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['forum_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['forum_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['forum_gestao_tgn']).'</td>';
		elseif ($gestao_data['forum_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['forum_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['forum_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['forum_gestao_gut']).'</td>';
		elseif ($gestao_data['forum_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['forum_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['forum_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['forum_gestao_arquivo']).'</td>';
		
		elseif ($gestao_data['forum_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['forum_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['forum_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['forum_gestao_checklist']).'</td>';
		elseif ($gestao_data['forum_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['forum_gestao_agenda']).'</td>';
		elseif ($gestao_data['forum_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['forum_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['forum_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['forum_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['forum_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['forum_gestao_template']).'</td>';
		elseif ($gestao_data['forum_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['forum_gestao_painel']).'</td>';
		elseif ($gestao_data['forum_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['forum_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['forum_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['forum_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['forum_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['forum_gestao_tr']).'</td>';	
		elseif ($gestao_data['forum_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['forum_gestao_me']).'</td>';	
		elseif ($gestao_data['forum_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['forum_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['forum_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['forum_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['forum_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['forum_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['forum_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['forum_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['forum_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['forum_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['forum_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['forum_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['forum_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['forum_gestao_ssti']).'</td>';
		elseif ($gestao_data['forum_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['forum_gestao_laudo']).'</td>';
		elseif ($gestao_data['forum_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['forum_gestao_trelo']).'</td>';
		elseif ($gestao_data['forum_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['forum_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['forum_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['forum_gestao_pdcl']).'</td>';
		elseif ($gestao_data['forum_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['forum_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['forum_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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