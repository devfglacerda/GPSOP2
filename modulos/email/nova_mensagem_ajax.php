<?php
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function mudar_posicao_gestao($ordem, $msg_gestao_id, $direcao, $msg_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $msg_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('msg_gestao');
		$sql->adOnde('msg_gestao_id != '.(int)$msg_gestao_id);
		if ($uuid) $sql->adOnde('msg_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('msg_gestao_msg = '.(int)$msg_id);
		$sql->adOrdem('msg_gestao_ordem');
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
			$sql->adTabela('msg_gestao');
			$sql->adAtualizar('msg_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('msg_gestao_id = '.(int)$msg_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('msg_gestao');
					$sql->adAtualizar('msg_gestao_ordem', $idx);
					$sql->adOnde('msg_gestao_id = '.(int)$acao['msg_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('msg_gestao');
					$sql->adAtualizar('msg_gestao_ordem', $idx + 1);
					$sql->adOnde('msg_gestao_id = '.(int)$acao['msg_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($msg_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$msg_id=0, 
	$uuid='',  
	
	$msg_projeto=null,
	$msg_tarefa=null,
	$msg_perspectiva=null,
	$msg_tema=null,
	$msg_objetivo=null,
	$msg_fator=null,
	$msg_estrategia=null,
	$msg_meta=null,
	$msg_pratica=null,
	$msg_acao=null,
	$msg_canvas=null,
	$msg_risco=null,
	$msg_risco_resposta=null,
	$msg_indicador=null,
	$msg_calendario=null,
	$msg_monitoramento=null,
	$msg_ata=null,
	$msg_mswot=null,
	$msg_swot=null,
	$msg_operativo=null,
	$msg_instrumento=null,
	$msg_recurso=null,
	$msg_problema=null,
	$msg_demanda=null,
	$msg_programa=null,
	$msg_licao=null,
	$msg_evento=null,
	$msg_link=null,
	$msg_avaliacao=null,
	$msg_tgn=null,
	$msg_brainstorm=null,
	$msg_gut=null,
	$msg_causa_efeito=null,
	$msg_arquivo=null,
	$msg_forum=null,
	$msg_checklist=null,
	$msg_agenda=null,
	$msg_agrupamento=null,
	$msg_patrocinador=null,
	$msg_template=null,
	$msg_painel=null,
	$msg_painel_odometro=null,
	$msg_painel_composicao=null,
	$msg_tr=null,
	$msg_me=null,
	$msg_acao_item=null,
	$msg_beneficio=null,
	$msg_painel_slideshow=null,
	$msg_projeto_viabilidade=null,
	$msg_projeto_abertura=null,
	$msg_plano_gestao=null,
	$msg_ssti=null,
	$msg_laudo=null,
	$msg_trelo=null,
	$msg_trelo_cartao=null,
	$msg_pdcl=null,
	$msg_pdcl_item=null
	)
	{
	if (
		$msg_projeto || 
		$msg_tarefa || 
		$msg_perspectiva || 
		$msg_tema || 
		$msg_objetivo || 
		$msg_fator || 
		$msg_estrategia || 
		$msg_meta || 
		$msg_pratica || 
		$msg_acao || 
		$msg_canvas || 
		$msg_risco || 
		$msg_risco_resposta || 
		$msg_indicador || 
		$msg_calendario || 
		$msg_monitoramento || 
		$msg_ata || 
		$msg_mswot || 
		$msg_swot || 
		$msg_operativo || 
		$msg_instrumento || 
		$msg_recurso || 
		$msg_problema || 
		$msg_demanda || 
		$msg_programa || 
		$msg_licao || 
		$msg_evento || 
		$msg_link || 
		$msg_avaliacao || 
		$msg_tgn || 
		$msg_brainstorm || 
		$msg_gut || 
		$msg_causa_efeito || 
		$msg_arquivo || 
		$msg_forum || 
		$msg_checklist || 
		$msg_agenda || 
		$msg_agrupamento || 
		$msg_patrocinador || 
		$msg_template || 
		$msg_painel || 
		$msg_painel_odometro || 
		$msg_painel_composicao || 
		$msg_tr || 
		$msg_me || 
		$msg_acao_item || 
		$msg_beneficio || 
		$msg_painel_slideshow || 
		$msg_projeto_viabilidade || 
		$msg_projeto_abertura || 
		$msg_plano_gestao|| 
		$msg_ssti || 
		$msg_laudo || 
		$msg_trelo || 
		$msg_trelo_cartao || 
		$msg_pdcl || 
		$msg_pdcl_item
		){
		global $Aplic;	
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('msg_gestao');
			if ($uuid) $sql->adOnde('msg_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('msg_gestao_msg ='.(int)$msg_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		
		//verificar se já não inseriu antes
		$sql->adTabela('msg_gestao');
		$sql->adCampo('count(msg_gestao_id)');
		if ($uuid) $sql->adOnde('msg_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('msg_gestao_msg ='.(int)$msg_id);	
		if ($msg_tarefa) $sql->adOnde('msg_gestao_tarefa='.(int)$msg_tarefa);
		elseif ($msg_projeto) $sql->adOnde('msg_gestao_projeto='.(int)$msg_projeto);
		elseif ($msg_perspectiva) $sql->adOnde('msg_gestao_perspectiva='.(int)$msg_perspectiva);
		elseif ($msg_tema) $sql->adOnde('msg_gestao_tema='.(int)$msg_tema);
		elseif ($msg_objetivo) $sql->adOnde('msg_gestao_objetivo='.(int)$msg_objetivo);
		elseif ($msg_fator) $sql->adOnde('msg_gestao_fator='.(int)$msg_fator);
		elseif ($msg_estrategia) $sql->adOnde('msg_gestao_estrategia='.(int)$msg_estrategia);
		elseif ($msg_acao) $sql->adOnde('msg_gestao_acao='.(int)$msg_acao);
		elseif ($msg_pratica) $sql->adOnde('msg_gestao_pratica='.(int)$msg_pratica);
		elseif ($msg_meta) $sql->adOnde('msg_gestao_meta='.(int)$msg_meta);
		elseif ($msg_canvas) $sql->adOnde('msg_gestao_canvas='.(int)$msg_canvas);
		elseif ($msg_risco) $sql->adOnde('msg_gestao_risco='.(int)$msg_risco);
		elseif ($msg_risco_resposta) $sql->adOnde('msg_gestao_risco_resposta='.(int)$msg_risco_resposta);
		elseif ($msg_indicador) $sql->adOnde('msg_gestao_indicador='.(int)$msg_indicador);
		elseif ($msg_calendario) $sql->adOnde('msg_gestao_calendario='.(int)$msg_calendario);
		elseif ($msg_monitoramento) $sql->adOnde('msg_gestao_monitoramento='.(int)$msg_monitoramento);
		elseif ($msg_ata) $sql->adOnde('msg_gestao_ata='.(int)$msg_ata);
		elseif ($msg_mswot) $sql->adOnde('msg_gestao_mswot='.(int)$msg_mswot);
		elseif ($msg_swot) $sql->adOnde('msg_gestao_swot='.(int)$msg_swot);
		elseif ($msg_operativo) $sql->adOnde('msg_gestao_operativo='.(int)$msg_operativo);
		elseif ($msg_instrumento) $sql->adOnde('msg_gestao_instrumento='.(int)$msg_instrumento);
		elseif ($msg_recurso) $sql->adOnde('msg_gestao_recurso='.(int)$msg_recurso);
		elseif ($msg_problema) $sql->adOnde('msg_gestao_problema='.(int)$msg_problema);
		elseif ($msg_demanda) $sql->adOnde('msg_gestao_demanda='.(int)$msg_demanda);
		elseif ($msg_programa) $sql->adOnde('msg_gestao_programa='.(int)$msg_programa);
		elseif ($msg_licao) $sql->adOnde('msg_gestao_licao='.(int)$msg_licao);
		elseif ($msg_evento) $sql->adOnde('msg_gestao_evento='.(int)$msg_evento);
		elseif ($msg_link) $sql->adOnde('msg_gestao_link='.(int)$msg_link);
		elseif ($msg_avaliacao) $sql->adOnde('msg_gestao_avaliacao='.(int)$msg_avaliacao);
		elseif ($msg_tgn) $sql->adOnde('msg_gestao_tgn='.(int)$msg_tgn);
		elseif ($msg_brainstorm) $sql->adOnde('msg_gestao_brainstorm='.(int)$msg_brainstorm);
		elseif ($msg_gut) $sql->adOnde('msg_gestao_gut='.(int)$msg_gut);
		elseif ($msg_causa_efeito) $sql->adOnde('msg_gestao_causa_efeito='.(int)$msg_causa_efeito);
		elseif ($msg_arquivo) $sql->adOnde('msg_gestao_arquivo='.(int)$msg_arquivo);
		elseif ($msg_forum) $sql->adOnde('msg_gestao_forum='.(int)$msg_forum);
		elseif ($msg_checklist) $sql->adOnde('msg_gestao_checklist='.(int)$msg_checklist);
		elseif ($msg_agenda) $sql->adOnde('msg_gestao_agenda='.(int)$msg_agenda);
		elseif ($msg_agrupamento) $sql->adOnde('msg_gestao_agrupamento='.(int)$msg_agrupamento);
		elseif ($msg_patrocinador) $sql->adOnde('msg_gestao_patrocinador='.(int)$msg_patrocinador);
		elseif ($msg_template) $sql->adOnde('msg_gestao_template='.(int)$msg_template);
		elseif ($msg_painel) $sql->adOnde('msg_gestao_painel='.(int)$msg_painel);
		elseif ($msg_painel_odometro) $sql->adOnde('msg_gestao_painel_odometro='.(int)$msg_painel_odometro);
		elseif ($msg_painel_composicao) $sql->adOnde('msg_gestao_painel_composicao='.(int)$msg_painel_composicao);
		elseif ($msg_tr) $sql->adOnde('msg_gestao_tr='.(int)$msg_tr);
		elseif ($msg_me) $sql->adOnde('msg_gestao_me='.(int)$msg_me);
		elseif ($msg_acao_item) $sql->adOnde('msg_gestao_acao_item='.(int)$msg_acao_item);
		elseif ($msg_beneficio) $sql->adOnde('msg_gestao_beneficio='.(int)$msg_beneficio);
		elseif ($msg_painel_slideshow) $sql->adOnde('msg_gestao_painel_slideshow='.(int)$msg_painel_slideshow);
		elseif ($msg_projeto_viabilidade) $sql->adOnde('msg_gestao_projeto_viabilidade='.(int)$msg_projeto_viabilidade);
		elseif ($msg_projeto_abertura) $sql->adOnde('msg_gestao_projeto_abertura='.(int)$msg_projeto_abertura);
		elseif ($msg_plano_gestao) $sql->adOnde('msg_gestao_plano_gestao='.(int)$msg_plano_gestao);
		elseif ($msg_ssti) $sql->adOnde('msg_gestao_ssti='.(int)$msg_ssti);
		elseif ($msg_laudo) $sql->adOnde('msg_gestao_laudo='.(int)$msg_laudo);
		elseif ($msg_trelo) $sql->adOnde('msg_gestao_trelo='.(int)$msg_trelo);
		elseif ($msg_trelo_cartao) $sql->adOnde('msg_gestao_trelo_cartao='.(int)$msg_trelo_cartao);
		elseif ($msg_pdcl) $sql->adOnde('msg_gestao_pdcl='.(int)$msg_pdcl);
		elseif ($msg_pdcl_item) $sql->adOnde('msg_gestao_pdcl_item='.(int)$msg_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('msg_gestao');
			$sql->adCampo('MAX(msg_gestao_ordem)');
			if ($uuid) $sql->adOnde('msg_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('msg_gestao_msg ='.(int)$msg_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('msg_gestao');
			if ($uuid) $sql->adInserir('msg_gestao_uuid', $uuid);
			else $sql->adInserir('msg_gestao_msg', (int)$msg_id);
			
			if ($msg_tarefa) $sql->adInserir('msg_gestao_tarefa', (int)$msg_tarefa);
			if ($msg_projeto) $sql->adInserir('msg_gestao_projeto', (int)$msg_projeto);
			elseif ($msg_perspectiva) $sql->adInserir('msg_gestao_perspectiva', (int)$msg_perspectiva);
			elseif ($msg_tema) $sql->adInserir('msg_gestao_tema', (int)$msg_tema);
			elseif ($msg_objetivo) $sql->adInserir('msg_gestao_objetivo', (int)$msg_objetivo);
			elseif ($msg_fator) $sql->adInserir('msg_gestao_fator', (int)$msg_fator);
			elseif ($msg_estrategia) $sql->adInserir('msg_gestao_estrategia', (int)$msg_estrategia);
			elseif ($msg_acao) $sql->adInserir('msg_gestao_acao', (int)$msg_acao);
			elseif ($msg_pratica) $sql->adInserir('msg_gestao_pratica', (int)$msg_pratica);
			elseif ($msg_meta) $sql->adInserir('msg_gestao_meta', (int)$msg_meta);
			elseif ($msg_canvas) $sql->adInserir('msg_gestao_canvas', (int)$msg_canvas);
			elseif ($msg_risco) $sql->adInserir('msg_gestao_risco', (int)$msg_risco);
			elseif ($msg_risco_resposta) $sql->adInserir('msg_gestao_risco_resposta', (int)$msg_risco_resposta);
			elseif ($msg_indicador) $sql->adInserir('msg_gestao_indicador', (int)$msg_indicador);
			elseif ($msg_calendario) $sql->adInserir('msg_gestao_calendario', (int)$msg_calendario);
			elseif ($msg_monitoramento) $sql->adInserir('msg_gestao_monitoramento', (int)$msg_monitoramento);
			elseif ($msg_ata) $sql->adInserir('msg_gestao_ata', (int)$msg_ata);
			elseif ($msg_mswot) $sql->adInserir('msg_gestao_mswot', (int)$msg_mswot);
			elseif ($msg_swot) $sql->adInserir('msg_gestao_swot', (int)$msg_swot);
			elseif ($msg_operativo) $sql->adInserir('msg_gestao_operativo', (int)$msg_operativo);
			elseif ($msg_instrumento) $sql->adInserir('msg_gestao_instrumento', (int)$msg_instrumento);
			elseif ($msg_recurso) $sql->adInserir('msg_gestao_recurso', (int)$msg_recurso);
			elseif ($msg_problema) $sql->adInserir('msg_gestao_problema', (int)$msg_problema);
			elseif ($msg_demanda) $sql->adInserir('msg_gestao_demanda', (int)$msg_demanda);
			elseif ($msg_programa) $sql->adInserir('msg_gestao_programa', (int)$msg_programa);
			elseif ($msg_licao) $sql->adInserir('msg_gestao_licao', (int)$msg_licao);
			elseif ($msg_evento) $sql->adInserir('msg_gestao_evento', (int)$msg_evento);
			elseif ($msg_link) $sql->adInserir('msg_gestao_link', (int)$msg_link);
			elseif ($msg_avaliacao) $sql->adInserir('msg_gestao_avaliacao', (int)$msg_avaliacao);
			elseif ($msg_tgn) $sql->adInserir('msg_gestao_tgn', (int)$msg_tgn);
			elseif ($msg_brainstorm) $sql->adInserir('msg_gestao_brainstorm', (int)$msg_brainstorm);
			elseif ($msg_gut) $sql->adInserir('msg_gestao_gut', (int)$msg_gut);
			elseif ($msg_causa_efeito) $sql->adInserir('msg_gestao_causa_efeito', (int)$msg_causa_efeito);
			elseif ($msg_arquivo) $sql->adInserir('msg_gestao_arquivo', (int)$msg_arquivo);
			elseif ($msg_forum) $sql->adInserir('msg_gestao_forum', (int)$msg_forum);
			elseif ($msg_checklist) $sql->adInserir('msg_gestao_checklist', (int)$msg_checklist);
			elseif ($msg_agenda) $sql->adInserir('msg_gestao_agenda', (int)$msg_agenda);
			elseif ($msg_agrupamento) $sql->adInserir('msg_gestao_agrupamento', (int)$msg_agrupamento);
			elseif ($msg_patrocinador) $sql->adInserir('msg_gestao_patrocinador', (int)$msg_patrocinador);
			elseif ($msg_template) $sql->adInserir('msg_gestao_template', (int)$msg_template);
			elseif ($msg_painel) $sql->adInserir('msg_gestao_painel', (int)$msg_painel);
			elseif ($msg_painel_odometro) $sql->adInserir('msg_gestao_painel_odometro', (int)$msg_painel_odometro);
			elseif ($msg_painel_composicao) $sql->adInserir('msg_gestao_painel_composicao', (int)$msg_painel_composicao);
			elseif ($msg_tr) $sql->adInserir('msg_gestao_tr', (int)$msg_tr);
			elseif ($msg_me) $sql->adInserir('msg_gestao_me', (int)$msg_me);
			elseif ($msg_acao_item) $sql->adInserir('msg_gestao_acao_item', (int)$msg_acao_item);
			elseif ($msg_beneficio) $sql->adInserir('msg_gestao_beneficio', (int)$msg_beneficio);
			elseif ($msg_painel_slideshow) $sql->adInserir('msg_gestao_painel_slideshow', (int)$msg_painel_slideshow);
			elseif ($msg_projeto_viabilidade) $sql->adInserir('msg_gestao_projeto_viabilidade', (int)$msg_projeto_viabilidade);
			elseif ($msg_projeto_abertura) $sql->adInserir('msg_gestao_projeto_abertura', (int)$msg_projeto_abertura);
			elseif ($msg_plano_gestao) $sql->adInserir('msg_gestao_plano_gestao', (int)$msg_plano_gestao);
			elseif ($msg_ssti) $sql->adInserir('msg_gestao_ssti', (int)$msg_ssti);
			elseif ($msg_laudo) $sql->adInserir('msg_gestao_laudo', (int)$msg_laudo);
			elseif ($msg_trelo) $sql->adInserir('msg_gestao_trelo', (int)$msg_trelo);
			elseif ($msg_trelo_cartao) $sql->adInserir('msg_gestao_trelo_cartao', (int)$msg_trelo_cartao);
			elseif ($msg_pdcl) $sql->adInserir('msg_gestao_pdcl', (int)$msg_pdcl);
			elseif ($msg_pdcl_item) $sql->adInserir('msg_gestao_pdcl_item', (int)$msg_pdcl_item);
			
			$sql->adInserir('msg_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($msg_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($msg_id=0, $uuid='', $msg_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('msg_gestao');
	$sql->adOnde('msg_gestao_id='.(int)$msg_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($msg_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($msg_id=0, $uuid=''){	
	$saida=atualizar_gestao($msg_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($msg_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('msg_gestao');
	$sql->adCampo('msg_gestao.*');
	if ($uuid) $sql->adOnde('msg_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('msg_gestao_msg ='.(int)$msg_id);	
	$sql->adOrdem('msg_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['msg_gestao_ordem'].', '.$gestao_data['msg_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['msg_gestao_ordem'].', '.$gestao_data['msg_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['msg_gestao_ordem'].', '.$gestao_data['msg_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['msg_gestao_ordem'].', '.$gestao_data['msg_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['msg_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['msg_gestao_tarefa']).'</td>';
		elseif ($gestao_data['msg_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['msg_gestao_projeto']).'</td>';
		elseif ($gestao_data['msg_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['msg_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['msg_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['msg_gestao_tema']).'</td>';
		elseif ($gestao_data['msg_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['msg_gestao_objetivo']).'</td>';
		elseif ($gestao_data['msg_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['msg_gestao_fator']).'</td>';
		elseif ($gestao_data['msg_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['msg_gestao_estrategia']).'</td>';
		elseif ($gestao_data['msg_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['msg_gestao_meta']).'</td>';
		elseif ($gestao_data['msg_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['msg_gestao_pratica']).'</td>';
		elseif ($gestao_data['msg_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['msg_gestao_acao']).'</td>';
		elseif ($gestao_data['msg_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['msg_gestao_canvas']).'</td>';
		elseif ($gestao_data['msg_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['msg_gestao_risco']).'</td>';
		elseif ($gestao_data['msg_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['msg_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['msg_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['msg_gestao_indicador']).'</td>';
		elseif ($gestao_data['msg_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['msg_gestao_calendario']).'</td>';
		elseif ($gestao_data['msg_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['msg_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['msg_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['msg_gestao_ata']).'</td>';
		elseif ($gestao_data['msg_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['msg_gestao_mswot']).'</td>';
		elseif ($gestao_data['msg_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['msg_gestao_swot']).'</td>';
		elseif ($gestao_data['msg_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['msg_gestao_operativo']).'</td>';
		elseif ($gestao_data['msg_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['msg_gestao_instrumento']).'</td>';
		elseif ($gestao_data['msg_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['msg_gestao_recurso']).'</td>';
		elseif ($gestao_data['msg_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['msg_gestao_problema']).'</td>';
		elseif ($gestao_data['msg_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['msg_gestao_demanda']).'</td>';
		elseif ($gestao_data['msg_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['msg_gestao_programa']).'</td>';
		elseif ($gestao_data['msg_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['msg_gestao_licao']).'</td>';
		elseif ($gestao_data['msg_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['msg_gestao_evento']).'</td>';
		elseif ($gestao_data['msg_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['msg_gestao_link']).'</td>';
		elseif ($gestao_data['msg_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['msg_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['msg_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['msg_gestao_tgn']).'</td>';
		elseif ($gestao_data['msg_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['msg_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['msg_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['msg_gestao_gut']).'</td>';
		elseif ($gestao_data['msg_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['msg_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['msg_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['msg_gestao_arquivo']).'</td>';
		elseif ($gestao_data['msg_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['msg_gestao_forum']).'</td>';
		elseif ($gestao_data['msg_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['msg_gestao_checklist']).'</td>';
		elseif ($gestao_data['msg_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['msg_gestao_agenda']).'</td>';
		elseif ($gestao_data['msg_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['msg_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['msg_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['msg_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['msg_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['msg_gestao_template']).'</td>';
		elseif ($gestao_data['msg_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['msg_gestao_painel']).'</td>';
		elseif ($gestao_data['msg_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['msg_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['msg_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['msg_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['msg_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['msg_gestao_tr']).'</td>';	
		elseif ($gestao_data['msg_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['msg_gestao_me']).'</td>';	
		elseif ($gestao_data['msg_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['msg_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['msg_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['msg_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['msg_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['msg_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['msg_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['msg_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['msg_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['msg_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['msg_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['msg_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['msg_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['msg_gestao_ssti']).'</td>';
		elseif ($gestao_data['msg_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['msg_gestao_laudo']).'</td>';
		elseif ($gestao_data['msg_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['msg_gestao_trelo']).'</td>';
		elseif ($gestao_data['msg_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['msg_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['msg_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['msg_gestao_pdcl']).'</td>';
		elseif ($gestao_data['msg_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['msg_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['msg_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");







function mudar_usuario_ajax($cia_id=null, $usuario_id=null, $campo=null, $posicao=null, $script=null, $pesquisa=null, $grupo_id=null){
	global $Aplic;
	$pesquisa=previnirXSS(utf8_decode($pesquisa));
	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$saida=mudar_usuario_em_dept(true, $cia_id, 0, $campo, $posicao, $script, null, null, null, null, $pesquisa, null, null, $grupo_id);
	$objResposta = New xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_ajax");	


function mudar_usuario_grupo_ajax($grupo_id=null, $pesquisar=null){
	global $Aplic, $config;
	$pesquisar=previnirXSS(utf8_decode($pesquisar));
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('grupo_usuario','grupo_usuario','grupo_usuario_usuario=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	if ($pesquisar) $sql->adOnde('contato_nomeguerra LIKE \'%'.$pesquisar.'%\' OR contato_nomecompleto LIKE \'%'.$pesquisar.'%\' OR contato_funcao LIKE \'%'.$pesquisar.'%\'');
	if ($grupo_id > 0) $sql->adOnde('grupo_usuario_grupo='.(int)$grupo_id);
	elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
  $sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor');	
	$usuarios = $sql->Lista();
	$sql->limpar();

	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) $saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode(nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';

	$saida.='</select>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_grupo_ajax");	

$xajax->processRequest();
?>