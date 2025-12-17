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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/tema_editar_ajax_pro.php');




function mudar_posicao_gestao($ordem, $tema_gestao_id, $direcao, $tema_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $tema_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('tema_gestao');
		$sql->adOnde('tema_gestao_id != '.(int)$tema_gestao_id);
		if ($uuid) $sql->adOnde('tema_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('tema_gestao_tema = '.(int)$tema_id);
		$sql->adOrdem('tema_gestao_ordem');
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
			$sql->adTabela('tema_gestao');
			$sql->adAtualizar('tema_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('tema_gestao_id = '.(int)$tema_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('tema_gestao');
					$sql->adAtualizar('tema_gestao_ordem', $idx);
					$sql->adOnde('tema_gestao_id = '.(int)$acao['tema_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('tema_gestao');
					$sql->adAtualizar('tema_gestao_ordem', $idx + 1);
					$sql->adOnde('tema_gestao_id = '.(int)$acao['tema_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($tema_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$tema_id=0, 
	$uuid='',  
	
	$tema_projeto=null,
	$tema_tarefa=null,
	$tema_perspectiva=null,
	$tema_tema=null,
	$tema_objetivo=null,
	$tema_fator=null,
	$tema_estrategia=null,
	$tema_meta=null,
	$tema_pratica=null,
	$tema_acao=null,
	$tema_canvas=null,
	$tema_risco=null,
	$tema_risco_resposta=null,
	$tema_indicador=null,
	$tema_calendario=null,
	$tema_monitoramento=null,
	$tema_ata=null,
	$tema_mswot=null,
	$tema_swot=null,
	$tema_operativo=null,
	$tema_instrumento=null,
	$tema_recurso=null,
	$tema_problema=null,
	$tema_demanda=null,
	$tema_programa=null,
	$tema_licao=null,
	$tema_evento=null,
	$tema_link=null,
	$tema_avaliacao=null,
	$tema_tgn=null,
	$tema_brainstorm=null,
	$tema_gut=null,
	$tema_causa_efeito=null,
	$tema_arquivo=null,
	$tema_forum=null,
	$tema_checklist=null,
	$tema_agenda=null,
	$tema_agrupamento=null,
	$tema_patrocinador=null,
	$tema_template=null,
	$tema_painel=null,
	$tema_painel_odometro=null,
	$tema_painel_composicao=null,
	$tema_tr=null,
	$tema_me=null,
	$tema_acao_item=null,
	$tema_beneficio=null,
	$tema_painel_slideshow=null,
	$tema_projeto_viabilidade=null,
	$tema_projeto_abertura=null,
	$tema_plano_gestao=null,
	$tema_ssti=null,
	$tema_laudo=null,
	$tema_trelo=null,
	$tema_trelo_cartao=null,
	$tema_pdcl=null,
	$tema_pdcl_item=null
	)
	{
	if (
		$tema_projeto || 
		$tema_tarefa || 
		$tema_perspectiva || 
		$tema_tema || 
		$tema_objetivo || 
		$tema_fator || 
		$tema_estrategia || 
		$tema_meta || 
		$tema_pratica || 
		$tema_acao || 
		$tema_canvas || 
		$tema_risco || 
		$tema_risco_resposta || 
		$tema_indicador || 
		$tema_calendario || 
		$tema_monitoramento || 
		$tema_ata || 
		$tema_mswot || 
		$tema_swot || 
		$tema_operativo || 
		$tema_instrumento || 
		$tema_recurso || 
		$tema_problema || 
		$tema_demanda || 
		$tema_programa || 
		$tema_licao || 
		$tema_evento || 
		$tema_link || 
		$tema_avaliacao || 
		$tema_tgn || 
		$tema_brainstorm || 
		$tema_gut || 
		$tema_causa_efeito || 
		$tema_arquivo || 
		$tema_forum || 
		$tema_checklist || 
		$tema_agenda || 
		$tema_agrupamento || 
		$tema_patrocinador || 
		$tema_template || 
		$tema_painel || 
		$tema_painel_odometro || 
		$tema_painel_composicao || 
		$tema_tr || 
		$tema_me || 
		$tema_acao_item || 
		$tema_beneficio || 
		$tema_painel_slideshow || 
		$tema_projeto_viabilidade || 
		$tema_projeto_abertura || 
		$tema_plano_gestao|| 
		$tema_ssti || 
		$tema_laudo || 
		$tema_trelo || 
		$tema_trelo_cartao || 
		$tema_pdcl || 
		$tema_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('tema_gestao');
			if ($uuid) $sql->adOnde('tema_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('tema_gestao_tema ='.(int)$tema_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('tema_gestao');
		$sql->adCampo('count(tema_gestao_id)');
		if ($uuid) $sql->adOnde('tema_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('tema_gestao_tema ='.(int)$tema_id);	
		if ($tema_tarefa) $sql->adOnde('tema_gestao_tarefa='.(int)$tema_tarefa);
		elseif ($tema_projeto) $sql->adOnde('tema_gestao_projeto='.(int)$tema_projeto);
		elseif ($tema_perspectiva) $sql->adOnde('tema_gestao_perspectiva='.(int)$tema_perspectiva);
		
		elseif ($tema_tema) $sql->adOnde('tema_gestao_semelhante='.(int)$tema_tema);
		
		elseif ($tema_objetivo) $sql->adOnde('tema_gestao_objetivo='.(int)$tema_objetivo);
		elseif ($tema_fator) $sql->adOnde('tema_gestao_fator='.(int)$tema_fator);
		elseif ($tema_estrategia) $sql->adOnde('tema_gestao_estrategia='.(int)$tema_estrategia);
		elseif ($tema_acao) $sql->adOnde('tema_gestao_acao='.(int)$tema_acao);
		elseif ($tema_pratica) $sql->adOnde('tema_gestao_pratica='.(int)$tema_pratica);
		elseif ($tema_meta) $sql->adOnde('tema_gestao_meta='.(int)$tema_meta);
		elseif ($tema_canvas) $sql->adOnde('tema_gestao_canvas='.(int)$tema_canvas);
		elseif ($tema_risco) $sql->adOnde('tema_gestao_risco='.(int)$tema_risco);
		elseif ($tema_risco_resposta) $sql->adOnde('tema_gestao_risco_resposta='.(int)$tema_risco_resposta);
		elseif ($tema_indicador) $sql->adOnde('tema_gestao_indicador='.(int)$tema_indicador);
		elseif ($tema_calendario) $sql->adOnde('tema_gestao_calendario='.(int)$tema_calendario);
		elseif ($tema_monitoramento) $sql->adOnde('tema_gestao_monitoramento='.(int)$tema_monitoramento);
		elseif ($tema_ata) $sql->adOnde('tema_gestao_ata='.(int)$tema_ata);
		elseif ($tema_mswot) $sql->adOnde('tema_gestao_mswot='.(int)$tema_mswot);
		elseif ($tema_swot) $sql->adOnde('tema_gestao_swot='.(int)$tema_swot);
		elseif ($tema_operativo) $sql->adOnde('tema_gestao_operativo='.(int)$tema_operativo);
		elseif ($tema_instrumento) $sql->adOnde('tema_gestao_instrumento='.(int)$tema_instrumento);
		elseif ($tema_recurso) $sql->adOnde('tema_gestao_recurso='.(int)$tema_recurso);
		elseif ($tema_problema) $sql->adOnde('tema_gestao_problema='.(int)$tema_problema);
		elseif ($tema_demanda) $sql->adOnde('tema_gestao_demanda='.(int)$tema_demanda);
		elseif ($tema_programa) $sql->adOnde('tema_gestao_programa='.(int)$tema_programa);
		elseif ($tema_licao) $sql->adOnde('tema_gestao_licao='.(int)$tema_licao);
		elseif ($tema_evento) $sql->adOnde('tema_gestao_evento='.(int)$tema_evento);
		elseif ($tema_link) $sql->adOnde('tema_gestao_link='.(int)$tema_link);
		elseif ($tema_avaliacao) $sql->adOnde('tema_gestao_avaliacao='.(int)$tema_avaliacao);
		elseif ($tema_tgn) $sql->adOnde('tema_gestao_tgn='.(int)$tema_tgn);
		elseif ($tema_brainstorm) $sql->adOnde('tema_gestao_brainstorm='.(int)$tema_brainstorm);
		elseif ($tema_gut) $sql->adOnde('tema_gestao_gut='.(int)$tema_gut);
		elseif ($tema_causa_efeito) $sql->adOnde('tema_gestao_causa_efeito='.(int)$tema_causa_efeito);
		elseif ($tema_arquivo) $sql->adOnde('tema_gestao_arquivo='.(int)$tema_arquivo);
		elseif ($tema_forum) $sql->adOnde('tema_gestao_forum='.(int)$tema_forum);
		elseif ($tema_checklist) $sql->adOnde('tema_gestao_checklist='.(int)$tema_checklist);
		elseif ($tema_agenda) $sql->adOnde('tema_gestao_agenda='.(int)$tema_agenda);
		elseif ($tema_agrupamento) $sql->adOnde('tema_gestao_agrupamento='.(int)$tema_agrupamento);
		elseif ($tema_patrocinador) $sql->adOnde('tema_gestao_patrocinador='.(int)$tema_patrocinador);
		elseif ($tema_template) $sql->adOnde('tema_gestao_template='.(int)$tema_template);
		elseif ($tema_painel) $sql->adOnde('tema_gestao_painel='.(int)$tema_painel);
		elseif ($tema_painel_odometro) $sql->adOnde('tema_gestao_painel_odometro='.(int)$tema_painel_odometro);
		elseif ($tema_painel_composicao) $sql->adOnde('tema_gestao_painel_composicao='.(int)$tema_painel_composicao);
		elseif ($tema_tr) $sql->adOnde('tema_gestao_tr='.(int)$tema_tr);
		elseif ($tema_me) $sql->adOnde('tema_gestao_me='.(int)$tema_me);
		elseif ($tema_acao_item) $sql->adOnde('tema_gestao_acao_item='.(int)$tema_acao_item);
		elseif ($tema_beneficio) $sql->adOnde('tema_gestao_beneficio='.(int)$tema_beneficio);
		elseif ($tema_painel_slideshow) $sql->adOnde('tema_gestao_painel_slideshow='.(int)$tema_painel_slideshow);
		elseif ($tema_projeto_viabilidade) $sql->adOnde('tema_gestao_projeto_viabilidade='.(int)$tema_projeto_viabilidade);
		elseif ($tema_projeto_abertura) $sql->adOnde('tema_gestao_projeto_abertura='.(int)$tema_projeto_abertura);
		elseif ($tema_plano_gestao) $sql->adOnde('tema_gestao_plano_gestao='.(int)$tema_plano_gestao);
		elseif ($tema_ssti) $sql->adOnde('tema_gestao_ssti='.(int)$tema_ssti);
		elseif ($tema_laudo) $sql->adOnde('tema_gestao_laudo='.(int)$tema_laudo);
		elseif ($tema_trelo) $sql->adOnde('tema_gestao_trelo='.(int)$tema_trelo);
		elseif ($tema_trelo_cartao) $sql->adOnde('tema_gestao_trelo_cartao='.(int)$tema_trelo_cartao);
		elseif ($tema_pdcl) $sql->adOnde('tema_gestao_pdcl='.(int)$tema_pdcl);
		elseif ($tema_pdcl_item) $sql->adOnde('tema_gestao_pdcl_item='.(int)$tema_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('tema_gestao');
			$sql->adCampo('MAX(tema_gestao_ordem)');
			if ($uuid) $sql->adOnde('tema_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('tema_gestao_tema ='.(int)$tema_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('tema_gestao');
			if ($uuid) $sql->adInserir('tema_gestao_uuid', $uuid);
			else $sql->adInserir('tema_gestao_tema', (int)$tema_id);
			
			if ($tema_tarefa) $sql->adInserir('tema_gestao_tarefa', (int)$tema_tarefa);
			if ($tema_projeto) $sql->adInserir('tema_gestao_projeto', (int)$tema_projeto);
			elseif ($tema_perspectiva) $sql->adInserir('tema_gestao_perspectiva', (int)$tema_perspectiva);
			
			elseif ($tema_tema) $sql->adInserir('tema_gestao_semelhante', (int)$tema_tema);
			
			elseif ($tema_objetivo) $sql->adInserir('tema_gestao_objetivo', (int)$tema_objetivo);
			elseif ($tema_fator) $sql->adInserir('tema_gestao_fator', (int)$tema_fator);
			elseif ($tema_estrategia) $sql->adInserir('tema_gestao_estrategia', (int)$tema_estrategia);
			elseif ($tema_acao) $sql->adInserir('tema_gestao_acao', (int)$tema_acao);
			elseif ($tema_pratica) $sql->adInserir('tema_gestao_pratica', (int)$tema_pratica);
			elseif ($tema_meta) $sql->adInserir('tema_gestao_meta', (int)$tema_meta);
			elseif ($tema_canvas) $sql->adInserir('tema_gestao_canvas', (int)$tema_canvas);
			elseif ($tema_risco) $sql->adInserir('tema_gestao_risco', (int)$tema_risco);
			elseif ($tema_risco_resposta) $sql->adInserir('tema_gestao_risco_resposta', (int)$tema_risco_resposta);
			elseif ($tema_indicador) $sql->adInserir('tema_gestao_indicador', (int)$tema_indicador);
			elseif ($tema_calendario) $sql->adInserir('tema_gestao_calendario', (int)$tema_calendario);
			elseif ($tema_monitoramento) $sql->adInserir('tema_gestao_monitoramento', (int)$tema_monitoramento);
			elseif ($tema_ata) $sql->adInserir('tema_gestao_ata', (int)$tema_ata);
			elseif ($tema_mswot) $sql->adInserir('tema_gestao_mswot', (int)$tema_mswot);
			elseif ($tema_swot) $sql->adInserir('tema_gestao_swot', (int)$tema_swot);
			elseif ($tema_operativo) $sql->adInserir('tema_gestao_operativo', (int)$tema_operativo);
			elseif ($tema_instrumento) $sql->adInserir('tema_gestao_instrumento', (int)$tema_instrumento);
			elseif ($tema_recurso) $sql->adInserir('tema_gestao_recurso', (int)$tema_recurso);
			elseif ($tema_problema) $sql->adInserir('tema_gestao_problema', (int)$tema_problema);
			elseif ($tema_demanda) $sql->adInserir('tema_gestao_demanda', (int)$tema_demanda);
			elseif ($tema_programa) $sql->adInserir('tema_gestao_programa', (int)$tema_programa);
			elseif ($tema_licao) $sql->adInserir('tema_gestao_licao', (int)$tema_licao);
			elseif ($tema_evento) $sql->adInserir('tema_gestao_evento', (int)$tema_evento);
			elseif ($tema_link) $sql->adInserir('tema_gestao_link', (int)$tema_link);
			elseif ($tema_avaliacao) $sql->adInserir('tema_gestao_avaliacao', (int)$tema_avaliacao);
			elseif ($tema_tgn) $sql->adInserir('tema_gestao_tgn', (int)$tema_tgn);
			elseif ($tema_brainstorm) $sql->adInserir('tema_gestao_brainstorm', (int)$tema_brainstorm);
			elseif ($tema_gut) $sql->adInserir('tema_gestao_gut', (int)$tema_gut);
			elseif ($tema_causa_efeito) $sql->adInserir('tema_gestao_causa_efeito', (int)$tema_causa_efeito);
			elseif ($tema_arquivo) $sql->adInserir('tema_gestao_arquivo', (int)$tema_arquivo);
			elseif ($tema_forum) $sql->adInserir('tema_gestao_forum', (int)$tema_forum);
			elseif ($tema_checklist) $sql->adInserir('tema_gestao_checklist', (int)$tema_checklist);
			elseif ($tema_agenda) $sql->adInserir('tema_gestao_agenda', (int)$tema_agenda);
			elseif ($tema_agrupamento) $sql->adInserir('tema_gestao_agrupamento', (int)$tema_agrupamento);
			elseif ($tema_patrocinador) $sql->adInserir('tema_gestao_patrocinador', (int)$tema_patrocinador);
			elseif ($tema_template) $sql->adInserir('tema_gestao_template', (int)$tema_template);
			elseif ($tema_painel) $sql->adInserir('tema_gestao_painel', (int)$tema_painel);
			elseif ($tema_painel_odometro) $sql->adInserir('tema_gestao_painel_odometro', (int)$tema_painel_odometro);
			elseif ($tema_painel_composicao) $sql->adInserir('tema_gestao_painel_composicao', (int)$tema_painel_composicao);
			elseif ($tema_tr) $sql->adInserir('tema_gestao_tr', (int)$tema_tr);
			elseif ($tema_me) $sql->adInserir('tema_gestao_me', (int)$tema_me);
			elseif ($tema_acao_item) $sql->adInserir('tema_gestao_acao_item', (int)$tema_acao_item);
			elseif ($tema_beneficio) $sql->adInserir('tema_gestao_beneficio', (int)$tema_beneficio);
			elseif ($tema_painel_slideshow) $sql->adInserir('tema_gestao_painel_slideshow', (int)$tema_painel_slideshow);
			elseif ($tema_projeto_viabilidade) $sql->adInserir('tema_gestao_projeto_viabilidade', (int)$tema_projeto_viabilidade);
			elseif ($tema_projeto_abertura) $sql->adInserir('tema_gestao_projeto_abertura', (int)$tema_projeto_abertura);
			elseif ($tema_plano_gestao) $sql->adInserir('tema_gestao_plano_gestao', (int)$tema_plano_gestao);
			elseif ($tema_ssti) $sql->adInserir('tema_gestao_ssti', (int)$tema_ssti);
			elseif ($tema_laudo) $sql->adInserir('tema_gestao_laudo', (int)$tema_laudo);
			elseif ($tema_trelo) $sql->adInserir('tema_gestao_trelo', (int)$tema_trelo);
			elseif ($tema_trelo_cartao) $sql->adInserir('tema_gestao_trelo_cartao', (int)$tema_trelo_cartao);
			elseif ($tema_pdcl) $sql->adInserir('tema_gestao_pdcl', (int)$tema_pdcl);
			elseif ($tema_pdcl_item) $sql->adInserir('tema_gestao_pdcl_item', (int)$tema_pdcl_item);
			$sql->adInserir('tema_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($tema_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($tema_id=0, $uuid='', $tema_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('tema_gestao');
	$sql->adOnde('tema_gestao_id='.(int)$tema_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($tema_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($tema_id=0, $uuid=''){	
	$saida=atualizar_gestao($tema_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($tema_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('tema_gestao');
	$sql->adCampo('tema_gestao.*');
	if ($uuid) $sql->adOnde('tema_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('tema_gestao_tema ='.(int)$tema_id);	
	$sql->adOrdem('tema_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['tema_gestao_ordem'].', '.$gestao_data['tema_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['tema_gestao_ordem'].', '.$gestao_data['tema_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['tema_gestao_ordem'].', '.$gestao_data['tema_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['tema_gestao_ordem'].', '.$gestao_data['tema_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['tema_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['tema_gestao_tarefa']).'</td>';
		elseif ($gestao_data['tema_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['tema_gestao_projeto']).'</td>';
		elseif ($gestao_data['tema_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['tema_gestao_perspectiva']).'</td>';
		
		elseif ($gestao_data['tema_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['tema_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['tema_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['tema_gestao_objetivo']).'</td>';
		elseif ($gestao_data['tema_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['tema_gestao_fator']).'</td>';
		elseif ($gestao_data['tema_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['tema_gestao_estrategia']).'</td>';
		elseif ($gestao_data['tema_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['tema_gestao_meta']).'</td>';
		elseif ($gestao_data['tema_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['tema_gestao_pratica']).'</td>';
		elseif ($gestao_data['tema_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['tema_gestao_acao']).'</td>';
		elseif ($gestao_data['tema_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['tema_gestao_canvas']).'</td>';
		elseif ($gestao_data['tema_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['tema_gestao_risco']).'</td>';
		elseif ($gestao_data['tema_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['tema_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['tema_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['tema_gestao_indicador']).'</td>';
		elseif ($gestao_data['tema_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['tema_gestao_calendario']).'</td>';
		elseif ($gestao_data['tema_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['tema_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['tema_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['tema_gestao_ata']).'</td>';
		elseif ($gestao_data['tema_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['tema_gestao_mswot']).'</td>';
		elseif ($gestao_data['tema_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['tema_gestao_swot']).'</td>';
		elseif ($gestao_data['tema_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['tema_gestao_operativo']).'</td>';
		elseif ($gestao_data['tema_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['tema_gestao_instrumento']).'</td>';
		elseif ($gestao_data['tema_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['tema_gestao_recurso']).'</td>';
		elseif ($gestao_data['tema_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['tema_gestao_problema']).'</td>';
		elseif ($gestao_data['tema_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['tema_gestao_demanda']).'</td>';
		elseif ($gestao_data['tema_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['tema_gestao_programa']).'</td>';
		elseif ($gestao_data['tema_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['tema_gestao_licao']).'</td>';
		elseif ($gestao_data['tema_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['tema_gestao_evento']).'</td>';
		elseif ($gestao_data['tema_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['tema_gestao_link']).'</td>';
		elseif ($gestao_data['tema_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['tema_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['tema_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['tema_gestao_tgn']).'</td>';
		elseif ($gestao_data['tema_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['tema_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['tema_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['tema_gestao_gut']).'</td>';
		elseif ($gestao_data['tema_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['tema_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['tema_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['tema_gestao_arquivo']).'</td>';
		elseif ($gestao_data['tema_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['tema_gestao_forum']).'</td>';
		elseif ($gestao_data['tema_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['tema_gestao_checklist']).'</td>';
		elseif ($gestao_data['tema_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['tema_gestao_agenda']).'</td>';
		elseif ($gestao_data['tema_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['tema_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['tema_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['tema_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['tema_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['tema_gestao_template']).'</td>';
		elseif ($gestao_data['tema_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['tema_gestao_painel']).'</td>';
		elseif ($gestao_data['tema_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['tema_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['tema_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['tema_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['tema_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['tema_gestao_tr']).'</td>';	
		elseif ($gestao_data['tema_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['tema_gestao_me']).'</td>';	
		elseif ($gestao_data['tema_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['tema_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['tema_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['tema_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['tema_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['tema_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['tema_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['tema_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['tema_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['tema_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['tema_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['tema_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['tema_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['tema_gestao_ssti']).'</td>';
		elseif ($gestao_data['tema_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['tema_gestao_laudo']).'</td>';
		elseif ($gestao_data['tema_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['tema_gestao_trelo']).'</td>';
		elseif ($gestao_data['tema_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['tema_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['tema_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['tema_gestao_pdcl']).'</td>';
		elseif ($gestao_data['tema_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['tema_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['tema_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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

	
function atualizar_perspectiva_ajax($cia_id=1, $ano='', $posicao){
	global $Aplic;
	$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : null;
	$sql = new BDConsulta;
	$sql->adTabela('plano_gestao_perspectivas');
	$sql->esqUnir('perspectivas','perspectivas','perspectivas.pg_perspectiva_id=plano_gestao_perspectivas.pg_perspectiva_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_perspectivas.pg_id');
	$sql->adCampo('pg_perspectiva_id, pg_perspectiva_nome');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($ano) $sql->adOnde('pg_inicio<=\''.$ano.'-12-31\' AND pg_fim>=\''.$ano.'-01-01\'');
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	$sql->adOrdem('pg_perspectiva_ordem ASC');
	$lista=$sql->Lista();
	$sql->limpar();
	
	$perspectiva=array();
	foreach ((array)$lista as $linha) $perspectiva[(int)$linha['pg_perspectiva_id']]=utf8_encode($linha['pg_perspectiva_nome']);
	$perspectiva[0]='';
	$saida=selecionaVetor($perspectiva, 'tema_perspectiva', 'class="texto" size=1');
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
	
	
	
$xajax->registerFunction("atualizar_perspectiva_ajax");
$xajax->processRequest();

?>