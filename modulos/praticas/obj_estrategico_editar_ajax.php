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

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/obj_estrategico_editar_pro_ajax.php');




function mudar_posicao_gestao($ordem, $objetivo_gestao_id, $direcao, $objetivo_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $objetivo_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('objetivo_gestao');
		$sql->adOnde('objetivo_gestao_id != '.(int)$objetivo_gestao_id);
		if ($uuid) $sql->adOnde('objetivo_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('objetivo_gestao_objetivo = '.(int)$objetivo_id);
		$sql->adOrdem('objetivo_gestao_ordem');
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
			$sql->adTabela('objetivo_gestao');
			$sql->adAtualizar('objetivo_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('objetivo_gestao_id = '.(int)$objetivo_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('objetivo_gestao');
					$sql->adAtualizar('objetivo_gestao_ordem', $idx);
					$sql->adOnde('objetivo_gestao_id = '.(int)$acao['objetivo_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('objetivo_gestao');
					$sql->adAtualizar('objetivo_gestao_ordem', $idx + 1);
					$sql->adOnde('objetivo_gestao_id = '.(int)$acao['objetivo_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($objetivo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$objetivo_id=0, 
	$uuid='',  
	
	$objetivo_projeto=null,
	$objetivo_tarefa=null,
	$objetivo_perspectiva=null,
	$objetivo_tema=null,
	$objetivo_objetivo=null,
	$objetivo_fator=null,
	$objetivo_estrategia=null,
	$objetivo_meta=null,
	$objetivo_pratica=null,
	$objetivo_acao=null,
	$objetivo_canvas=null,
	$objetivo_risco=null,
	$objetivo_risco_resposta=null,
	$objetivo_indicador=null,
	$objetivo_calendario=null,
	$objetivo_monitoramento=null,
	$objetivo_ata=null,
	$objetivo_mswot=null,
	$objetivo_swot=null,
	$objetivo_operativo=null,
	$objetivo_instrumento=null,
	$objetivo_recurso=null,
	$objetivo_problema=null,
	$objetivo_demanda=null,
	$objetivo_programa=null,
	$objetivo_licao=null,
	$objetivo_evento=null,
	$objetivo_link=null,
	$objetivo_avaliacao=null,
	$objetivo_tgn=null,
	$objetivo_brainstorm=null,
	$objetivo_gut=null,
	$objetivo_causa_efeito=null,
	$objetivo_arquivo=null,
	$objetivo_forum=null,
	$objetivo_checklist=null,
	$objetivo_agenda=null,
	$objetivo_agrupamento=null,
	$objetivo_patrocinador=null,
	$objetivo_template=null,
	$objetivo_painel=null,
	$objetivo_painel_odometro=null,
	$objetivo_painel_composicao=null,
	$objetivo_tr=null,
	$objetivo_me=null,
	$objetivo_acao_item=null,
	$objetivo_beneficio=null,
	$objetivo_painel_slideshow=null,
	$objetivo_projeto_viabilidade=null,
	$objetivo_projeto_abertura=null,
	$objetivo_plano_gestao=null,
	$objetivo_ssti=null,
	$objetivo_laudo=null,
	$objetivo_trelo=null,
	$objetivo_trelo_cartao=null,
	$objetivo_pdcl=null,
	$objetivo_pdcl_item=null
	)
	{
	if (
		$objetivo_projeto || 
		$objetivo_tarefa || 
		$objetivo_perspectiva || 
		$objetivo_tema || 
		$objetivo_objetivo || 
		$objetivo_fator || 
		$objetivo_estrategia || 
		$objetivo_meta || 
		$objetivo_pratica || 
		$objetivo_acao || 
		$objetivo_canvas || 
		$objetivo_risco || 
		$objetivo_risco_resposta || 
		$objetivo_indicador || 
		$objetivo_calendario || 
		$objetivo_monitoramento || 
		$objetivo_ata || 
		$objetivo_mswot || 
		$objetivo_swot || 
		$objetivo_operativo || 
		$objetivo_instrumento || 
		$objetivo_recurso || 
		$objetivo_problema || 
		$objetivo_demanda || 
		$objetivo_programa || 
		$objetivo_licao || 
		$objetivo_evento || 
		$objetivo_link || 
		$objetivo_avaliacao || 
		$objetivo_tgn || 
		$objetivo_brainstorm || 
		$objetivo_gut || 
		$objetivo_causa_efeito || 
		$objetivo_arquivo || 
		$objetivo_forum || 
		$objetivo_checklist || 
		$objetivo_agenda || 
		$objetivo_agrupamento || 
		$objetivo_patrocinador || 
		$objetivo_template || 
		$objetivo_painel || 
		$objetivo_painel_odometro || 
		$objetivo_painel_composicao || 
		$objetivo_tr || 
		$objetivo_me || 
		$objetivo_acao_item || 
		$objetivo_beneficio || 
		$objetivo_painel_slideshow || 
		$objetivo_projeto_viabilidade || 
		$objetivo_projeto_abertura || 
		$objetivo_plano_gestao|| 
		$objetivo_ssti || 
		$objetivo_laudo || 
		$objetivo_trelo || 
		$objetivo_trelo_cartao || 
		$objetivo_pdcl || 
		$objetivo_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('objetivo_gestao');
			if ($uuid) $sql->adOnde('objetivo_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('objetivo_gestao_objetivo ='.(int)$objetivo_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('objetivo_gestao');
		$sql->adCampo('count(objetivo_gestao_id)');
		if ($uuid) $sql->adOnde('objetivo_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('objetivo_gestao_objetivo ='.(int)$objetivo_id);	
		if ($objetivo_tarefa) $sql->adOnde('objetivo_gestao_tarefa='.(int)$objetivo_tarefa);
		elseif ($objetivo_projeto) $sql->adOnde('objetivo_gestao_projeto='.(int)$objetivo_projeto);
		elseif ($objetivo_perspectiva) $sql->adOnde('objetivo_gestao_perspectiva='.(int)$objetivo_perspectiva);
		elseif ($objetivo_tema) $sql->adOnde('objetivo_gestao_tema='.(int)$objetivo_tema);
		
		elseif ($objetivo_objetivo) $sql->adOnde('objetivo_gestao_semelhante='.(int)$objetivo_objetivo);
		
		elseif ($objetivo_fator) $sql->adOnde('objetivo_gestao_fator='.(int)$objetivo_fator);
		elseif ($objetivo_estrategia) $sql->adOnde('objetivo_gestao_estrategia='.(int)$objetivo_estrategia);
		elseif ($objetivo_acao) $sql->adOnde('objetivo_gestao_acao='.(int)$objetivo_acao);
		elseif ($objetivo_pratica) $sql->adOnde('objetivo_gestao_pratica='.(int)$objetivo_pratica);
		elseif ($objetivo_meta) $sql->adOnde('objetivo_gestao_meta='.(int)$objetivo_meta);
		elseif ($objetivo_canvas) $sql->adOnde('objetivo_gestao_canvas='.(int)$objetivo_canvas);
		elseif ($objetivo_risco) $sql->adOnde('objetivo_gestao_risco='.(int)$objetivo_risco);
		elseif ($objetivo_risco_resposta) $sql->adOnde('objetivo_gestao_risco_resposta='.(int)$objetivo_risco_resposta);
		elseif ($objetivo_indicador) $sql->adOnde('objetivo_gestao_indicador='.(int)$objetivo_indicador);
		elseif ($objetivo_calendario) $sql->adOnde('objetivo_gestao_calendario='.(int)$objetivo_calendario);
		elseif ($objetivo_monitoramento) $sql->adOnde('objetivo_gestao_monitoramento='.(int)$objetivo_monitoramento);
		elseif ($objetivo_ata) $sql->adOnde('objetivo_gestao_ata='.(int)$objetivo_ata);
		elseif ($objetivo_mswot) $sql->adOnde('objetivo_gestao_mswot='.(int)$objetivo_mswot);
		elseif ($objetivo_swot) $sql->adOnde('objetivo_gestao_swot='.(int)$objetivo_swot);
		elseif ($objetivo_operativo) $sql->adOnde('objetivo_gestao_operativo='.(int)$objetivo_operativo);
		elseif ($objetivo_instrumento) $sql->adOnde('objetivo_gestao_instrumento='.(int)$objetivo_instrumento);
		elseif ($objetivo_recurso) $sql->adOnde('objetivo_gestao_recurso='.(int)$objetivo_recurso);
		elseif ($objetivo_problema) $sql->adOnde('objetivo_gestao_problema='.(int)$objetivo_problema);
		elseif ($objetivo_demanda) $sql->adOnde('objetivo_gestao_demanda='.(int)$objetivo_demanda);
		elseif ($objetivo_programa) $sql->adOnde('objetivo_gestao_programa='.(int)$objetivo_programa);
		elseif ($objetivo_licao) $sql->adOnde('objetivo_gestao_licao='.(int)$objetivo_licao);
		elseif ($objetivo_evento) $sql->adOnde('objetivo_gestao_evento='.(int)$objetivo_evento);
		elseif ($objetivo_link) $sql->adOnde('objetivo_gestao_link='.(int)$objetivo_link);
		elseif ($objetivo_avaliacao) $sql->adOnde('objetivo_gestao_avaliacao='.(int)$objetivo_avaliacao);
		elseif ($objetivo_tgn) $sql->adOnde('objetivo_gestao_tgn='.(int)$objetivo_tgn);
		elseif ($objetivo_brainstorm) $sql->adOnde('objetivo_gestao_brainstorm='.(int)$objetivo_brainstorm);
		elseif ($objetivo_gut) $sql->adOnde('objetivo_gestao_gut='.(int)$objetivo_gut);
		elseif ($objetivo_causa_efeito) $sql->adOnde('objetivo_gestao_causa_efeito='.(int)$objetivo_causa_efeito);
		elseif ($objetivo_arquivo) $sql->adOnde('objetivo_gestao_arquivo='.(int)$objetivo_arquivo);
		elseif ($objetivo_forum) $sql->adOnde('objetivo_gestao_forum='.(int)$objetivo_forum);
		elseif ($objetivo_checklist) $sql->adOnde('objetivo_gestao_checklist='.(int)$objetivo_checklist);
		elseif ($objetivo_agenda) $sql->adOnde('objetivo_gestao_agenda='.(int)$objetivo_agenda);
		elseif ($objetivo_agrupamento) $sql->adOnde('objetivo_gestao_agrupamento='.(int)$objetivo_agrupamento);
		elseif ($objetivo_patrocinador) $sql->adOnde('objetivo_gestao_patrocinador='.(int)$objetivo_patrocinador);
		elseif ($objetivo_template) $sql->adOnde('objetivo_gestao_template='.(int)$objetivo_template);
		elseif ($objetivo_painel) $sql->adOnde('objetivo_gestao_painel='.(int)$objetivo_painel);
		elseif ($objetivo_painel_odometro) $sql->adOnde('objetivo_gestao_painel_odometro='.(int)$objetivo_painel_odometro);
		elseif ($objetivo_painel_composicao) $sql->adOnde('objetivo_gestao_painel_composicao='.(int)$objetivo_painel_composicao);
		elseif ($objetivo_tr) $sql->adOnde('objetivo_gestao_tr='.(int)$objetivo_tr);
		elseif ($objetivo_me) $sql->adOnde('objetivo_gestao_me='.(int)$objetivo_me);
		elseif ($objetivo_acao_item) $sql->adOnde('objetivo_gestao_acao_item='.(int)$objetivo_acao_item);
		elseif ($objetivo_beneficio) $sql->adOnde('objetivo_gestao_beneficio='.(int)$objetivo_beneficio);
		elseif ($objetivo_painel_slideshow) $sql->adOnde('objetivo_gestao_painel_slideshow='.(int)$objetivo_painel_slideshow);
		elseif ($objetivo_projeto_viabilidade) $sql->adOnde('objetivo_gestao_projeto_viabilidade='.(int)$objetivo_projeto_viabilidade);
		elseif ($objetivo_projeto_abertura) $sql->adOnde('objetivo_gestao_projeto_abertura='.(int)$objetivo_projeto_abertura);
		elseif ($objetivo_plano_gestao) $sql->adOnde('objetivo_gestao_plano_gestao='.(int)$objetivo_plano_gestao);
		elseif ($objetivo_ssti) $sql->adOnde('objetivo_gestao_ssti='.(int)$objetivo_ssti);
		elseif ($objetivo_laudo) $sql->adOnde('objetivo_gestao_laudo='.(int)$objetivo_laudo);
		elseif ($objetivo_trelo) $sql->adOnde('objetivo_gestao_trelo='.(int)$objetivo_trelo);
		elseif ($objetivo_trelo_cartao) $sql->adOnde('objetivo_gestao_trelo_cartao='.(int)$objetivo_trelo_cartao);
		elseif ($objetivo_pdcl) $sql->adOnde('objetivo_gestao_pdcl='.(int)$objetivo_pdcl);
		elseif ($objetivo_pdcl_item) $sql->adOnde('objetivo_gestao_pdcl_item='.(int)$objetivo_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('objetivo_gestao');
			$sql->adCampo('MAX(objetivo_gestao_ordem)');
			if ($uuid) $sql->adOnde('objetivo_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('objetivo_gestao_objetivo ='.(int)$objetivo_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('objetivo_gestao');
			if ($uuid) $sql->adInserir('objetivo_gestao_uuid', $uuid);
			else $sql->adInserir('objetivo_gestao_objetivo', (int)$objetivo_id);
			
			if ($objetivo_tarefa) $sql->adInserir('objetivo_gestao_tarefa', (int)$objetivo_tarefa);
			if ($objetivo_projeto) $sql->adInserir('objetivo_gestao_projeto', (int)$objetivo_projeto);
			elseif ($objetivo_perspectiva) $sql->adInserir('objetivo_gestao_perspectiva', (int)$objetivo_perspectiva);
			elseif ($objetivo_tema) $sql->adInserir('objetivo_gestao_tema', (int)$objetivo_tema);
			
			elseif ($objetivo_objetivo) $sql->adInserir('objetivo_gestao_semelhante', (int)$objetivo_objetivo);
			
			elseif ($objetivo_fator) $sql->adInserir('objetivo_gestao_fator', (int)$objetivo_fator);
			elseif ($objetivo_estrategia) $sql->adInserir('objetivo_gestao_estrategia', (int)$objetivo_estrategia);
			elseif ($objetivo_acao) $sql->adInserir('objetivo_gestao_acao', (int)$objetivo_acao);
			elseif ($objetivo_pratica) $sql->adInserir('objetivo_gestao_pratica', (int)$objetivo_pratica);
			elseif ($objetivo_meta) $sql->adInserir('objetivo_gestao_meta', (int)$objetivo_meta);
			elseif ($objetivo_canvas) $sql->adInserir('objetivo_gestao_canvas', (int)$objetivo_canvas);
			elseif ($objetivo_risco) $sql->adInserir('objetivo_gestao_risco', (int)$objetivo_risco);
			elseif ($objetivo_risco_resposta) $sql->adInserir('objetivo_gestao_risco_resposta', (int)$objetivo_risco_resposta);
			elseif ($objetivo_indicador) $sql->adInserir('objetivo_gestao_indicador', (int)$objetivo_indicador);
			elseif ($objetivo_calendario) $sql->adInserir('objetivo_gestao_calendario', (int)$objetivo_calendario);
			elseif ($objetivo_monitoramento) $sql->adInserir('objetivo_gestao_monitoramento', (int)$objetivo_monitoramento);
			elseif ($objetivo_ata) $sql->adInserir('objetivo_gestao_ata', (int)$objetivo_ata);
			elseif ($objetivo_mswot) $sql->adInserir('objetivo_gestao_mswot', (int)$objetivo_mswot);
			elseif ($objetivo_swot) $sql->adInserir('objetivo_gestao_swot', (int)$objetivo_swot);
			elseif ($objetivo_operativo) $sql->adInserir('objetivo_gestao_operativo', (int)$objetivo_operativo);
			elseif ($objetivo_instrumento) $sql->adInserir('objetivo_gestao_instrumento', (int)$objetivo_instrumento);
			elseif ($objetivo_recurso) $sql->adInserir('objetivo_gestao_recurso', (int)$objetivo_recurso);
			elseif ($objetivo_problema) $sql->adInserir('objetivo_gestao_problema', (int)$objetivo_problema);
			elseif ($objetivo_demanda) $sql->adInserir('objetivo_gestao_demanda', (int)$objetivo_demanda);
			elseif ($objetivo_programa) $sql->adInserir('objetivo_gestao_programa', (int)$objetivo_programa);
			elseif ($objetivo_licao) $sql->adInserir('objetivo_gestao_licao', (int)$objetivo_licao);
			elseif ($objetivo_evento) $sql->adInserir('objetivo_gestao_evento', (int)$objetivo_evento);
			elseif ($objetivo_link) $sql->adInserir('objetivo_gestao_link', (int)$objetivo_link);
			elseif ($objetivo_avaliacao) $sql->adInserir('objetivo_gestao_avaliacao', (int)$objetivo_avaliacao);
			elseif ($objetivo_tgn) $sql->adInserir('objetivo_gestao_tgn', (int)$objetivo_tgn);
			elseif ($objetivo_brainstorm) $sql->adInserir('objetivo_gestao_brainstorm', (int)$objetivo_brainstorm);
			elseif ($objetivo_gut) $sql->adInserir('objetivo_gestao_gut', (int)$objetivo_gut);
			elseif ($objetivo_causa_efeito) $sql->adInserir('objetivo_gestao_causa_efeito', (int)$objetivo_causa_efeito);
			elseif ($objetivo_arquivo) $sql->adInserir('objetivo_gestao_arquivo', (int)$objetivo_arquivo);
			elseif ($objetivo_forum) $sql->adInserir('objetivo_gestao_forum', (int)$objetivo_forum);
			elseif ($objetivo_checklist) $sql->adInserir('objetivo_gestao_checklist', (int)$objetivo_checklist);
			elseif ($objetivo_agenda) $sql->adInserir('objetivo_gestao_agenda', (int)$objetivo_agenda);
			elseif ($objetivo_agrupamento) $sql->adInserir('objetivo_gestao_agrupamento', (int)$objetivo_agrupamento);
			elseif ($objetivo_patrocinador) $sql->adInserir('objetivo_gestao_patrocinador', (int)$objetivo_patrocinador);
			elseif ($objetivo_template) $sql->adInserir('objetivo_gestao_template', (int)$objetivo_template);
			elseif ($objetivo_painel) $sql->adInserir('objetivo_gestao_painel', (int)$objetivo_painel);
			elseif ($objetivo_painel_odometro) $sql->adInserir('objetivo_gestao_painel_odometro', (int)$objetivo_painel_odometro);
			elseif ($objetivo_painel_composicao) $sql->adInserir('objetivo_gestao_painel_composicao', (int)$objetivo_painel_composicao);
			elseif ($objetivo_tr) $sql->adInserir('objetivo_gestao_tr', (int)$objetivo_tr);
			elseif ($objetivo_me) $sql->adInserir('objetivo_gestao_me', (int)$objetivo_me);
			elseif ($objetivo_acao_item) $sql->adInserir('objetivo_gestao_acao_item', (int)$objetivo_acao_item);
			elseif ($objetivo_beneficio) $sql->adInserir('objetivo_gestao_beneficio', (int)$objetivo_beneficio);
			elseif ($objetivo_painel_slideshow) $sql->adInserir('objetivo_gestao_painel_slideshow', (int)$objetivo_painel_slideshow);
			elseif ($objetivo_projeto_viabilidade) $sql->adInserir('objetivo_gestao_projeto_viabilidade', (int)$objetivo_projeto_viabilidade);
			elseif ($objetivo_projeto_abertura) $sql->adInserir('objetivo_gestao_projeto_abertura', (int)$objetivo_projeto_abertura);
			elseif ($objetivo_plano_gestao) $sql->adInserir('objetivo_gestao_plano_gestao', (int)$objetivo_plano_gestao);
			elseif ($objetivo_ssti) $sql->adInserir('objetivo_gestao_ssti', (int)$objetivo_ssti);
			elseif ($objetivo_laudo) $sql->adInserir('objetivo_gestao_laudo', (int)$objetivo_laudo);
			elseif ($objetivo_trelo) $sql->adInserir('objetivo_gestao_trelo', (int)$objetivo_trelo);
			elseif ($objetivo_trelo_cartao) $sql->adInserir('objetivo_gestao_trelo_cartao', (int)$objetivo_trelo_cartao);
			elseif ($objetivo_pdcl) $sql->adInserir('objetivo_gestao_pdcl', (int)$objetivo_pdcl);
			elseif ($objetivo_pdcl_item) $sql->adInserir('objetivo_gestao_pdcl_item', (int)$objetivo_pdcl_item);
			
			$sql->adInserir('objetivo_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($objetivo_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($objetivo_id=0, $uuid='', $objetivo_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('objetivo_gestao');
	$sql->adOnde('objetivo_gestao_id='.(int)$objetivo_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($objetivo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($objetivo_id=0, $uuid=''){	
	$saida=atualizar_gestao($objetivo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($objetivo_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('objetivo_gestao');
	$sql->adCampo('objetivo_gestao.*');
	if ($uuid) $sql->adOnde('objetivo_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('objetivo_gestao_objetivo ='.(int)$objetivo_id);	
	$sql->adOrdem('objetivo_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['objetivo_gestao_ordem'].', '.$gestao_data['objetivo_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['objetivo_gestao_ordem'].', '.$gestao_data['objetivo_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['objetivo_gestao_ordem'].', '.$gestao_data['objetivo_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['objetivo_gestao_ordem'].', '.$gestao_data['objetivo_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['objetivo_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['objetivo_gestao_tarefa']).'</td>';
		elseif ($gestao_data['objetivo_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['objetivo_gestao_projeto']).'</td>';
		elseif ($gestao_data['objetivo_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['objetivo_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['objetivo_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['objetivo_gestao_tema']).'</td>';
		
		elseif ($gestao_data['objetivo_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['objetivo_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['objetivo_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['objetivo_gestao_fator']).'</td>';
		elseif ($gestao_data['objetivo_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['objetivo_gestao_estrategia']).'</td>';
		elseif ($gestao_data['objetivo_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['objetivo_gestao_meta']).'</td>';
		elseif ($gestao_data['objetivo_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['objetivo_gestao_pratica']).'</td>';
		elseif ($gestao_data['objetivo_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['objetivo_gestao_acao']).'</td>';
		elseif ($gestao_data['objetivo_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['objetivo_gestao_canvas']).'</td>';
		elseif ($gestao_data['objetivo_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['objetivo_gestao_risco']).'</td>';
		elseif ($gestao_data['objetivo_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['objetivo_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['objetivo_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['objetivo_gestao_indicador']).'</td>';
		elseif ($gestao_data['objetivo_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['objetivo_gestao_calendario']).'</td>';
		elseif ($gestao_data['objetivo_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['objetivo_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['objetivo_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['objetivo_gestao_ata']).'</td>';
		elseif ($gestao_data['objetivo_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['objetivo_gestao_mswot']).'</td>';
		elseif ($gestao_data['objetivo_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['objetivo_gestao_swot']).'</td>';
		elseif ($gestao_data['objetivo_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['objetivo_gestao_operativo']).'</td>';
		elseif ($gestao_data['objetivo_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['objetivo_gestao_instrumento']).'</td>';
		elseif ($gestao_data['objetivo_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['objetivo_gestao_recurso']).'</td>';
		elseif ($gestao_data['objetivo_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['objetivo_gestao_problema']).'</td>';
		elseif ($gestao_data['objetivo_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['objetivo_gestao_demanda']).'</td>';
		elseif ($gestao_data['objetivo_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['objetivo_gestao_programa']).'</td>';
		elseif ($gestao_data['objetivo_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['objetivo_gestao_licao']).'</td>';
		elseif ($gestao_data['objetivo_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['objetivo_gestao_evento']).'</td>';
		elseif ($gestao_data['objetivo_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['objetivo_gestao_link']).'</td>';
		elseif ($gestao_data['objetivo_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['objetivo_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['objetivo_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['objetivo_gestao_tgn']).'</td>';
		elseif ($gestao_data['objetivo_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['objetivo_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['objetivo_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['objetivo_gestao_gut']).'</td>';
		elseif ($gestao_data['objetivo_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['objetivo_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['objetivo_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['objetivo_gestao_arquivo']).'</td>';
		elseif ($gestao_data['objetivo_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['objetivo_gestao_forum']).'</td>';
		elseif ($gestao_data['objetivo_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['objetivo_gestao_checklist']).'</td>';
		elseif ($gestao_data['objetivo_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['objetivo_gestao_agenda']).'</td>';
		elseif ($gestao_data['objetivo_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['objetivo_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['objetivo_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['objetivo_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['objetivo_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['objetivo_gestao_template']).'</td>';
		elseif ($gestao_data['objetivo_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['objetivo_gestao_painel']).'</td>';
		elseif ($gestao_data['objetivo_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['objetivo_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['objetivo_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['objetivo_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['objetivo_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['objetivo_gestao_tr']).'</td>';	
		elseif ($gestao_data['objetivo_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['objetivo_gestao_me']).'</td>';	
		elseif ($gestao_data['objetivo_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['objetivo_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['objetivo_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['objetivo_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['objetivo_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['objetivo_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['objetivo_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['objetivo_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['objetivo_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['objetivo_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['objetivo_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['objetivo_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['objetivo_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['objetivo_gestao_ssti']).'</td>';
		elseif ($gestao_data['objetivo_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['objetivo_gestao_laudo']).'</td>';
		elseif ($gestao_data['objetivo_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['objetivo_gestao_trelo']).'</td>';
		elseif ($gestao_data['objetivo_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['objetivo_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['objetivo_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['objetivo_gestao_pdcl']).'</td>';
		elseif ($gestao_data['objetivo_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['objetivo_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['objetivo_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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