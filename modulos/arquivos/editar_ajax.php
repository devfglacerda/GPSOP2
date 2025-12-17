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


if ($Aplic->profissional) include_once BASE_DIR.'/modulos/arquivos/editar_ajax_pro.php';



function mudar_posicao_gestao($ordem, $arquivo_gestao_id, $direcao, $arquivo_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $arquivo_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('arquivo_gestao');
		$sql->adOnde('arquivo_gestao_id != '.(int)$arquivo_gestao_id);
		if ($uuid) $sql->adOnde('arquivo_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('arquivo_gestao_arquivo = '.(int)$arquivo_id);
		$sql->adOrdem('arquivo_gestao_ordem');
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
			$sql->adTabela('arquivo_gestao');
			$sql->adAtualizar('arquivo_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('arquivo_gestao_id = '.(int)$arquivo_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('arquivo_gestao');
					$sql->adAtualizar('arquivo_gestao_ordem', $idx);
					$sql->adOnde('arquivo_gestao_id = '.(int)$acao['arquivo_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('arquivo_gestao');
					$sql->adAtualizar('arquivo_gestao_ordem', $idx + 1);
					$sql->adOnde('arquivo_gestao_id = '.(int)$acao['arquivo_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($arquivo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$arquivo_id=0, 
	$uuid='',  
	
	$arquivo_projeto=null,
	$arquivo_tarefa=null,
	$arquivo_perspectiva=null,
	$arquivo_tema=null,
	$arquivo_objetivo=null,
	$arquivo_fator=null,
	$arquivo_estrategia=null,
	$arquivo_meta=null,
	$arquivo_pratica=null,
	$arquivo_acao=null,
	$arquivo_canvas=null,
	$arquivo_risco=null,
	$arquivo_risco_resposta=null,
	$arquivo_indicador=null,
	$arquivo_calendario=null,
	$arquivo_monitoramento=null,
	$arquivo_ata=null,
	$arquivo_mswot=null,
	$arquivo_swot=null,
	$arquivo_operativo=null,
	$arquivo_instrumento=null,
	$arquivo_recurso=null,
	$arquivo_problema=null,
	$arquivo_demanda=null,
	$arquivo_programa=null,
	$arquivo_licao=null,
	$arquivo_evento=null,
	$arquivo_link=null,
	$arquivo_avaliacao=null,
	$arquivo_tgn=null,
	$arquivo_brainstorm=null,
	$arquivo_gut=null,
	$arquivo_causa_efeito=null,
	$arquivo_arquivo=null,
	$arquivo_forum=null,
	$arquivo_checklist=null,
	$arquivo_agenda=null,
	$arquivo_agrupamento=null,
	$arquivo_patrocinador=null,
	$arquivo_template=null,
	$arquivo_painel=null,
	$arquivo_painel_odometro=null,
	$arquivo_painel_composicao=null,
	$arquivo_tr=null,
	$arquivo_me=null,
	$arquivo_acao_item=null,
	$arquivo_beneficio=null,
	$arquivo_painel_slideshow=null,
	$arquivo_projeto_viabilidade=null,
	$arquivo_projeto_abertura=null,
	$arquivo_plano_gestao=null,
	$arquivo_ssti=null,
	$arquivo_laudo=null,
	$arquivo_trelo=null,
	$arquivo_trelo_cartao=null,
	$arquivo_pdcl=null,
	$arquivo_pdcl_item=null,
	$arquivo_usuario=null
	)
	{
	if (
		$arquivo_projeto || 
		$arquivo_tarefa || 
		$arquivo_perspectiva || 
		$arquivo_tema || 
		$arquivo_objetivo || 
		$arquivo_fator || 
		$arquivo_estrategia || 
		$arquivo_meta || 
		$arquivo_pratica || 
		$arquivo_acao || 
		$arquivo_canvas || 
		$arquivo_risco || 
		$arquivo_risco_resposta || 
		$arquivo_indicador || 
		$arquivo_calendario || 
		$arquivo_monitoramento || 
		$arquivo_ata || 
		$arquivo_mswot || 
		$arquivo_swot || 
		$arquivo_operativo || 
		$arquivo_instrumento || 
		$arquivo_recurso || 
		$arquivo_problema || 
		$arquivo_demanda || 
		$arquivo_programa || 
		$arquivo_licao || 
		$arquivo_evento || 
		$arquivo_link || 
		$arquivo_avaliacao || 
		$arquivo_tgn || 
		$arquivo_brainstorm || 
		$arquivo_gut || 
		$arquivo_causa_efeito || 
		$arquivo_arquivo || 
		$arquivo_forum || 
		$arquivo_checklist || 
		$arquivo_agenda || 
		$arquivo_agrupamento || 
		$arquivo_patrocinador || 
		$arquivo_template || 
		$arquivo_painel || 
		$arquivo_painel_odometro || 
		$arquivo_painel_composicao || 
		$arquivo_tr || 
		$arquivo_me || 
		$arquivo_acao_item || 
		$arquivo_beneficio || 
		$arquivo_painel_slideshow || 
		$arquivo_projeto_viabilidade || 
		$arquivo_projeto_abertura || 
		$arquivo_plano_gestao|| 
		$arquivo_ssti || 
		$arquivo_laudo || 
		$arquivo_trelo || 
		$arquivo_trelo_cartao || 
		$arquivo_pdcl || 
		$arquivo_pdcl_item || 
		
		$arquivo_usuario
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('arquivo_gestao');
			if ($uuid) $sql->adOnde('arquivo_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('arquivo_gestao_arquivo ='.(int)$arquivo_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('count(arquivo_gestao_id)');
		if ($uuid) $sql->adOnde('arquivo_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('arquivo_gestao_arquivo ='.(int)$arquivo_id);	
		if ($arquivo_tarefa) $sql->adOnde('arquivo_gestao_tarefa='.(int)$arquivo_tarefa);
		elseif ($arquivo_projeto) $sql->adOnde('arquivo_gestao_projeto='.(int)$arquivo_projeto);
		elseif ($arquivo_perspectiva) $sql->adOnde('arquivo_gestao_perspectiva='.(int)$arquivo_perspectiva);
		elseif ($arquivo_tema) $sql->adOnde('arquivo_gestao_tema='.(int)$arquivo_tema);
		elseif ($arquivo_objetivo) $sql->adOnde('arquivo_gestao_objetivo='.(int)$arquivo_objetivo);
		elseif ($arquivo_fator) $sql->adOnde('arquivo_gestao_fator='.(int)$arquivo_fator);
		elseif ($arquivo_estrategia) $sql->adOnde('arquivo_gestao_estrategia='.(int)$arquivo_estrategia);
		elseif ($arquivo_acao) $sql->adOnde('arquivo_gestao_acao='.(int)$arquivo_acao);
		elseif ($arquivo_pratica) $sql->adOnde('arquivo_gestao_pratica='.(int)$arquivo_pratica);
		elseif ($arquivo_meta) $sql->adOnde('arquivo_gestao_meta='.(int)$arquivo_meta);
		elseif ($arquivo_canvas) $sql->adOnde('arquivo_gestao_canvas='.(int)$arquivo_canvas);
		elseif ($arquivo_risco) $sql->adOnde('arquivo_gestao_risco='.(int)$arquivo_risco);
		elseif ($arquivo_risco_resposta) $sql->adOnde('arquivo_gestao_risco_resposta='.(int)$arquivo_risco_resposta);
		elseif ($arquivo_indicador) $sql->adOnde('arquivo_gestao_indicador='.(int)$arquivo_indicador);
		elseif ($arquivo_calendario) $sql->adOnde('arquivo_gestao_calendario='.(int)$arquivo_calendario);
		elseif ($arquivo_monitoramento) $sql->adOnde('arquivo_gestao_monitoramento='.(int)$arquivo_monitoramento);
		elseif ($arquivo_ata) $sql->adOnde('arquivo_gestao_ata='.(int)$arquivo_ata);
		elseif ($arquivo_mswot) $sql->adOnde('arquivo_gestao_mswot='.(int)$arquivo_mswot);
		elseif ($arquivo_swot) $sql->adOnde('arquivo_gestao_swot='.(int)$arquivo_swot);
		elseif ($arquivo_operativo) $sql->adOnde('arquivo_gestao_operativo='.(int)$arquivo_operativo);
		elseif ($arquivo_instrumento) $sql->adOnde('arquivo_gestao_instrumento='.(int)$arquivo_instrumento);
		elseif ($arquivo_recurso) $sql->adOnde('arquivo_gestao_recurso='.(int)$arquivo_recurso);
		elseif ($arquivo_problema) $sql->adOnde('arquivo_gestao_problema='.(int)$arquivo_problema);
		elseif ($arquivo_demanda) $sql->adOnde('arquivo_gestao_demanda='.(int)$arquivo_demanda);
		elseif ($arquivo_programa) $sql->adOnde('arquivo_gestao_programa='.(int)$arquivo_programa);
		elseif ($arquivo_licao) $sql->adOnde('arquivo_gestao_licao='.(int)$arquivo_licao);
		elseif ($arquivo_evento) $sql->adOnde('arquivo_gestao_evento='.(int)$arquivo_evento);
		elseif ($arquivo_link) $sql->adOnde('arquivo_gestao_link='.(int)$arquivo_link);
		elseif ($arquivo_avaliacao) $sql->adOnde('arquivo_gestao_avaliacao='.(int)$arquivo_avaliacao);
		elseif ($arquivo_tgn) $sql->adOnde('arquivo_gestao_tgn='.(int)$arquivo_tgn);
		elseif ($arquivo_brainstorm) $sql->adOnde('arquivo_gestao_brainstorm='.(int)$arquivo_brainstorm);
		elseif ($arquivo_gut) $sql->adOnde('arquivo_gestao_gut='.(int)$arquivo_gut);
		elseif ($arquivo_causa_efeito) $sql->adOnde('arquivo_gestao_causa_efeito='.(int)$arquivo_causa_efeito);
		
		elseif ($arquivo_arquivo) $sql->adOnde('arquivo_gestao_semelhante='.(int)$arquivo_arquivo);
		
		elseif ($arquivo_forum) $sql->adOnde('arquivo_gestao_forum='.(int)$arquivo_forum);
		elseif ($arquivo_checklist) $sql->adOnde('arquivo_gestao_checklist='.(int)$arquivo_checklist);
		elseif ($arquivo_agenda) $sql->adOnde('arquivo_gestao_agenda='.(int)$arquivo_agenda);
		elseif ($arquivo_agrupamento) $sql->adOnde('arquivo_gestao_agrupamento='.(int)$arquivo_agrupamento);
		elseif ($arquivo_patrocinador) $sql->adOnde('arquivo_gestao_patrocinador='.(int)$arquivo_patrocinador);
		elseif ($arquivo_template) $sql->adOnde('arquivo_gestao_template='.(int)$arquivo_template);
		elseif ($arquivo_painel) $sql->adOnde('arquivo_gestao_painel='.(int)$arquivo_painel);
		elseif ($arquivo_painel_odometro) $sql->adOnde('arquivo_gestao_painel_odometro='.(int)$arquivo_painel_odometro);
		elseif ($arquivo_painel_composicao) $sql->adOnde('arquivo_gestao_painel_composicao='.(int)$arquivo_painel_composicao);
		elseif ($arquivo_tr) $sql->adOnde('arquivo_gestao_tr='.(int)$arquivo_tr);
		elseif ($arquivo_me) $sql->adOnde('arquivo_gestao_me='.(int)$arquivo_me);
		elseif ($arquivo_acao_item) $sql->adOnde('arquivo_gestao_acao_item='.(int)$arquivo_acao_item);
		elseif ($arquivo_beneficio) $sql->adOnde('arquivo_gestao_beneficio='.(int)$arquivo_beneficio);
		elseif ($arquivo_painel_slideshow) $sql->adOnde('arquivo_gestao_painel_slideshow='.(int)$arquivo_painel_slideshow);
		elseif ($arquivo_projeto_viabilidade) $sql->adOnde('arquivo_gestao_projeto_viabilidade='.(int)$arquivo_projeto_viabilidade);
		elseif ($arquivo_projeto_abertura) $sql->adOnde('arquivo_gestao_projeto_abertura='.(int)$arquivo_projeto_abertura);
		elseif ($arquivo_plano_gestao) $sql->adOnde('arquivo_gestao_plano_gestao='.(int)$arquivo_plano_gestao);
		elseif ($arquivo_ssti) $sql->adOnde('arquivo_gestao_ssti='.(int)$arquivo_ssti);
		elseif ($arquivo_laudo) $sql->adOnde('arquivo_gestao_laudo='.(int)$arquivo_laudo);
		elseif ($arquivo_trelo) $sql->adOnde('arquivo_gestao_trelo='.(int)$arquivo_trelo);
		elseif ($arquivo_trelo_cartao) $sql->adOnde('arquivo_gestao_trelo_cartao='.(int)$arquivo_trelo_cartao);
		elseif ($arquivo_pdcl) $sql->adOnde('arquivo_gestao_pdcl='.(int)$arquivo_pdcl);
		elseif ($arquivo_pdcl_item) $sql->adOnde('arquivo_gestao_pdcl_item='.(int)$arquivo_pdcl_item);
	
		elseif ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario='.(int)$arquivo_usuario);

	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('arquivo_gestao');
			$sql->adCampo('MAX(arquivo_gestao_ordem)');
			if ($uuid) $sql->adOnde('arquivo_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('arquivo_gestao_arquivo ='.(int)$arquivo_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('arquivo_gestao');
			if ($uuid) $sql->adInserir('arquivo_gestao_uuid', $uuid);
			else $sql->adInserir('arquivo_gestao_arquivo', (int)$arquivo_id);
			
			if ($arquivo_tarefa) $sql->adInserir('arquivo_gestao_tarefa', (int)$arquivo_tarefa);
			if ($arquivo_projeto) $sql->adInserir('arquivo_gestao_projeto', (int)$arquivo_projeto);
			elseif ($arquivo_perspectiva) $sql->adInserir('arquivo_gestao_perspectiva', (int)$arquivo_perspectiva);
			elseif ($arquivo_tema) $sql->adInserir('arquivo_gestao_tema', (int)$arquivo_tema);
			elseif ($arquivo_objetivo) $sql->adInserir('arquivo_gestao_objetivo', (int)$arquivo_objetivo);
			elseif ($arquivo_fator) $sql->adInserir('arquivo_gestao_fator', (int)$arquivo_fator);
			elseif ($arquivo_estrategia) $sql->adInserir('arquivo_gestao_estrategia', (int)$arquivo_estrategia);
			elseif ($arquivo_acao) $sql->adInserir('arquivo_gestao_acao', (int)$arquivo_acao);
			elseif ($arquivo_pratica) $sql->adInserir('arquivo_gestao_pratica', (int)$arquivo_pratica);
			elseif ($arquivo_meta) $sql->adInserir('arquivo_gestao_meta', (int)$arquivo_meta);
			elseif ($arquivo_canvas) $sql->adInserir('arquivo_gestao_canvas', (int)$arquivo_canvas);
			elseif ($arquivo_risco) $sql->adInserir('arquivo_gestao_risco', (int)$arquivo_risco);
			elseif ($arquivo_risco_resposta) $sql->adInserir('arquivo_gestao_risco_resposta', (int)$arquivo_risco_resposta);
			elseif ($arquivo_indicador) $sql->adInserir('arquivo_gestao_indicador', (int)$arquivo_indicador);
			elseif ($arquivo_calendario) $sql->adInserir('arquivo_gestao_calendario', (int)$arquivo_calendario);
			elseif ($arquivo_monitoramento) $sql->adInserir('arquivo_gestao_monitoramento', (int)$arquivo_monitoramento);
			elseif ($arquivo_ata) $sql->adInserir('arquivo_gestao_ata', (int)$arquivo_ata);
			elseif ($arquivo_mswot) $sql->adInserir('arquivo_gestao_mswot', (int)$arquivo_mswot);
			elseif ($arquivo_swot) $sql->adInserir('arquivo_gestao_swot', (int)$arquivo_swot);
			elseif ($arquivo_operativo) $sql->adInserir('arquivo_gestao_operativo', (int)$arquivo_operativo);
			elseif ($arquivo_instrumento) $sql->adInserir('arquivo_gestao_instrumento', (int)$arquivo_instrumento);
			elseif ($arquivo_recurso) $sql->adInserir('arquivo_gestao_recurso', (int)$arquivo_recurso);
			elseif ($arquivo_problema) $sql->adInserir('arquivo_gestao_problema', (int)$arquivo_problema);
			elseif ($arquivo_demanda) $sql->adInserir('arquivo_gestao_demanda', (int)$arquivo_demanda);
			elseif ($arquivo_programa) $sql->adInserir('arquivo_gestao_programa', (int)$arquivo_programa);
			elseif ($arquivo_licao) $sql->adInserir('arquivo_gestao_licao', (int)$arquivo_licao);
			elseif ($arquivo_evento) $sql->adInserir('arquivo_gestao_evento', (int)$arquivo_evento);
			elseif ($arquivo_link) $sql->adInserir('arquivo_gestao_link', (int)$arquivo_link);
			elseif ($arquivo_avaliacao) $sql->adInserir('arquivo_gestao_avaliacao', (int)$arquivo_avaliacao);
			elseif ($arquivo_tgn) $sql->adInserir('arquivo_gestao_tgn', (int)$arquivo_tgn);
			elseif ($arquivo_brainstorm) $sql->adInserir('arquivo_gestao_brainstorm', (int)$arquivo_brainstorm);
			elseif ($arquivo_gut) $sql->adInserir('arquivo_gestao_gut', (int)$arquivo_gut);
			elseif ($arquivo_causa_efeito) $sql->adInserir('arquivo_gestao_causa_efeito', (int)$arquivo_causa_efeito);
			
			elseif ($arquivo_arquivo) $sql->adInserir('arquivo_gestao_semelhante', (int)$arquivo_arquivo);
			
			elseif ($arquivo_forum) $sql->adInserir('arquivo_gestao_forum', (int)$arquivo_forum);
			elseif ($arquivo_checklist) $sql->adInserir('arquivo_gestao_checklist', (int)$arquivo_checklist);
			elseif ($arquivo_agenda) $sql->adInserir('arquivo_gestao_agenda', (int)$arquivo_agenda);
			elseif ($arquivo_agrupamento) $sql->adInserir('arquivo_gestao_agrupamento', (int)$arquivo_agrupamento);
			elseif ($arquivo_patrocinador) $sql->adInserir('arquivo_gestao_patrocinador', (int)$arquivo_patrocinador);
			elseif ($arquivo_template) $sql->adInserir('arquivo_gestao_template', (int)$arquivo_template);
			elseif ($arquivo_painel) $sql->adInserir('arquivo_gestao_painel', (int)$arquivo_painel);
			elseif ($arquivo_painel_odometro) $sql->adInserir('arquivo_gestao_painel_odometro', (int)$arquivo_painel_odometro);
			elseif ($arquivo_painel_composicao) $sql->adInserir('arquivo_gestao_painel_composicao', (int)$arquivo_painel_composicao);
			elseif ($arquivo_tr) $sql->adInserir('arquivo_gestao_tr', (int)$arquivo_tr);
			elseif ($arquivo_me) $sql->adInserir('arquivo_gestao_me', (int)$arquivo_me);
			elseif ($arquivo_acao_item) $sql->adInserir('arquivo_gestao_acao_item', (int)$arquivo_acao_item);
			elseif ($arquivo_beneficio) $sql->adInserir('arquivo_gestao_beneficio', (int)$arquivo_beneficio);
			elseif ($arquivo_painel_slideshow) $sql->adInserir('arquivo_gestao_painel_slideshow', (int)$arquivo_painel_slideshow);
			elseif ($arquivo_projeto_viabilidade) $sql->adInserir('arquivo_gestao_projeto_viabilidade', (int)$arquivo_projeto_viabilidade);
			elseif ($arquivo_projeto_abertura) $sql->adInserir('arquivo_gestao_projeto_abertura', (int)$arquivo_projeto_abertura);
			elseif ($arquivo_plano_gestao) $sql->adInserir('arquivo_gestao_plano_gestao', (int)$arquivo_plano_gestao);
			elseif ($arquivo_ssti) $sql->adInserir('arquivo_gestao_ssti', (int)$arquivo_ssti);
			elseif ($arquivo_laudo) $sql->adInserir('arquivo_gestao_laudo', (int)$arquivo_laudo);
			elseif ($arquivo_trelo) $sql->adInserir('arquivo_gestao_trelo', (int)$arquivo_trelo);
			elseif ($arquivo_trelo_cartao) $sql->adInserir('arquivo_gestao_trelo_cartao', (int)$arquivo_trelo_cartao);
			elseif ($arquivo_pdcl) $sql->adInserir('arquivo_gestao_pdcl', (int)$arquivo_pdcl);
			elseif ($arquivo_pdcl_item) $sql->adInserir('arquivo_gestao_pdcl_item', (int)$arquivo_pdcl_item);
			
			elseif ($arquivo_usuario) $sql->adInserir('arquivo_gestao_usuario', (int)$arquivo_usuario);
			$sql->adInserir('arquivo_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($arquivo_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($arquivo_id=0, $uuid='', $arquivo_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('arquivo_gestao');
	$sql->adOnde('arquivo_gestao_id='.(int)$arquivo_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($arquivo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($arquivo_id=0, $uuid=''){	
	$saida=atualizar_gestao($arquivo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($arquivo_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('arquivo_gestao');
	$sql->adCampo('arquivo_gestao.*');
	if ($uuid) $sql->adOnde('arquivo_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('arquivo_gestao_arquivo ='.(int)$arquivo_id);	
	$sql->adOrdem('arquivo_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_gestao_ordem'].', '.$gestao_data['arquivo_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_gestao_ordem'].', '.$gestao_data['arquivo_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_gestao_ordem'].', '.$gestao_data['arquivo_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_gestao_ordem'].', '.$gestao_data['arquivo_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['arquivo_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['arquivo_gestao_tarefa']).'</td>';
		elseif ($gestao_data['arquivo_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['arquivo_gestao_projeto']).'</td>';
		elseif ($gestao_data['arquivo_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['arquivo_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['arquivo_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['arquivo_gestao_tema']).'</td>';
		elseif ($gestao_data['arquivo_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['arquivo_gestao_objetivo']).'</td>';
		elseif ($gestao_data['arquivo_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['arquivo_gestao_fator']).'</td>';
		elseif ($gestao_data['arquivo_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['arquivo_gestao_estrategia']).'</td>';
		elseif ($gestao_data['arquivo_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['arquivo_gestao_meta']).'</td>';
		elseif ($gestao_data['arquivo_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['arquivo_gestao_pratica']).'</td>';
		elseif ($gestao_data['arquivo_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['arquivo_gestao_acao']).'</td>';
		elseif ($gestao_data['arquivo_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['arquivo_gestao_canvas']).'</td>';
		elseif ($gestao_data['arquivo_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['arquivo_gestao_risco']).'</td>';
		elseif ($gestao_data['arquivo_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['arquivo_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['arquivo_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['arquivo_gestao_indicador']).'</td>';
		elseif ($gestao_data['arquivo_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['arquivo_gestao_calendario']).'</td>';
		elseif ($gestao_data['arquivo_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['arquivo_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['arquivo_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['arquivo_gestao_ata']).'</td>';
		elseif ($gestao_data['arquivo_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['arquivo_gestao_mswot']).'</td>';
		elseif ($gestao_data['arquivo_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['arquivo_gestao_swot']).'</td>';
		elseif ($gestao_data['arquivo_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['arquivo_gestao_operativo']).'</td>';
		elseif ($gestao_data['arquivo_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['arquivo_gestao_instrumento']).'</td>';
		elseif ($gestao_data['arquivo_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['arquivo_gestao_recurso']).'</td>';
		elseif ($gestao_data['arquivo_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['arquivo_gestao_problema']).'</td>';
		elseif ($gestao_data['arquivo_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['arquivo_gestao_demanda']).'</td>';
		elseif ($gestao_data['arquivo_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['arquivo_gestao_programa']).'</td>';
		elseif ($gestao_data['arquivo_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['arquivo_gestao_licao']).'</td>';
		elseif ($gestao_data['arquivo_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['arquivo_gestao_evento']).'</td>';
		elseif ($gestao_data['arquivo_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['arquivo_gestao_link']).'</td>';
		elseif ($gestao_data['arquivo_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['arquivo_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['arquivo_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['arquivo_gestao_tgn']).'</td>';
		elseif ($gestao_data['arquivo_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['arquivo_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['arquivo_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['arquivo_gestao_gut']).'</td>';
		elseif ($gestao_data['arquivo_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['arquivo_gestao_causa_efeito']).'</td>';
		
		elseif ($gestao_data['arquivo_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['arquivo_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['arquivo_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['arquivo_gestao_forum']).'</td>';
		elseif ($gestao_data['arquivo_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['arquivo_gestao_checklist']).'</td>';
		elseif ($gestao_data['arquivo_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['arquivo_gestao_agenda']).'</td>';
		elseif ($gestao_data['arquivo_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['arquivo_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['arquivo_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['arquivo_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['arquivo_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['arquivo_gestao_template']).'</td>';
		elseif ($gestao_data['arquivo_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['arquivo_gestao_painel']).'</td>';
		elseif ($gestao_data['arquivo_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['arquivo_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['arquivo_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['arquivo_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['arquivo_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['arquivo_gestao_tr']).'</td>';	
		elseif ($gestao_data['arquivo_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['arquivo_gestao_me']).'</td>';	
		elseif ($gestao_data['arquivo_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['arquivo_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['arquivo_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['arquivo_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['arquivo_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['arquivo_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['arquivo_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['arquivo_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['arquivo_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['arquivo_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['arquivo_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['arquivo_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['arquivo_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['arquivo_gestao_ssti']).'</td>';
		elseif ($gestao_data['arquivo_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['arquivo_gestao_laudo']).'</td>';
		elseif ($gestao_data['arquivo_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['arquivo_gestao_trelo']).'</td>';
		elseif ($gestao_data['arquivo_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['arquivo_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['arquivo_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['arquivo_gestao_pdcl']).'</td>';
		elseif ($gestao_data['arquivo_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['arquivo_gestao_pdcl_item']).'</td>';

		elseif ($gestao_data['arquivo_gestao_usuario']) $saida.= '<td align=left>'.imagem('icones/usuario_p.gif').link_usuario($gestao_data['arquivo_gestao_usuario']).'</td>';
				
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['arquivo_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
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