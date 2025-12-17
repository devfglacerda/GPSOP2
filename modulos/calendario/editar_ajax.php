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

require_once BASE_DIR.'/modulos/tarefas/funcoes.php';

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/calendario/editar_ajax_pro.php';

function mudar_posicao_gestao($ordem, $evento_gestao_id, $direcao, $evento_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $evento_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('evento_gestao');
		$sql->adOnde('evento_gestao_id != '.(int)$evento_gestao_id);
		if ($uuid) $sql->adOnde('evento_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('evento_gestao_evento = '.(int)$evento_id);
		$sql->adOrdem('evento_gestao_ordem');
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
			$sql->adTabela('evento_gestao');
			$sql->adAtualizar('evento_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('evento_gestao_id = '.(int)$evento_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('evento_gestao');
					$sql->adAtualizar('evento_gestao_ordem', $idx);
					$sql->adOnde('evento_gestao_id = '.(int)$acao['evento_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('evento_gestao');
					$sql->adAtualizar('evento_gestao_ordem', $idx + 1);
					$sql->adOnde('evento_gestao_id = '.(int)$acao['evento_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($evento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$evento_id=0, 
	$uuid='',  
	
	$evento_projeto=null,
	$evento_tarefa=null,
	$evento_perspectiva=null,
	$evento_tema=null,
	$evento_objetivo=null,
	$evento_fator=null,
	$evento_estrategia=null,
	$evento_meta=null,
	$evento_pratica=null,
	$evento_acao=null,
	$evento_canvas=null,
	$evento_risco=null,
	$evento_risco_resposta=null,
	$evento_indicador=null,
	$evento_calendario=null,
	$evento_monitoramento=null,
	$evento_ata=null,
	$evento_mswot=null,
	$evento_swot=null,
	$evento_operativo=null,
	$evento_instrumento=null,
	$evento_recurso=null,
	$evento_problema=null,
	$evento_demanda=null,
	$evento_programa=null,
	$evento_licao=null,
	$evento_evento=null,
	$evento_link=null,
	$evento_avaliacao=null,
	$evento_tgn=null,
	$evento_brainstorm=null,
	$evento_gut=null,
	$evento_causa_efeito=null,
	$evento_arquivo=null,
	$evento_forum=null,
	$evento_checklist=null,
	$evento_agenda=null,
	$evento_agrupamento=null,
	$evento_patrocinador=null,
	$evento_template=null,
	$evento_painel=null,
	$evento_painel_odometro=null,
	$evento_painel_composicao=null,
	$evento_tr=null,
	$evento_me=null,
	$evento_acao_item=null,
	$evento_beneficio=null,
	$evento_painel_slideshow=null,
	$evento_projeto_viabilidade=null,
	$evento_projeto_abertura=null,
	$evento_plano_gestao=null,
	$evento_ssti=null,
	$evento_laudo=null,
	$evento_trelo=null,
	$evento_trelo_cartao=null,
	$evento_pdcl=null,
	$evento_pdcl_item=null
	)
	{
	if (
		$evento_projeto || 
		$evento_tarefa || 
		$evento_perspectiva || 
		$evento_tema || 
		$evento_objetivo || 
		$evento_fator || 
		$evento_estrategia || 
		$evento_meta || 
		$evento_pratica || 
		$evento_acao || 
		$evento_canvas || 
		$evento_risco || 
		$evento_risco_resposta || 
		$evento_indicador || 
		$evento_calendario || 
		$evento_monitoramento || 
		$evento_ata || 
		$evento_mswot || 
		$evento_swot || 
		$evento_operativo || 
		$evento_instrumento || 
		$evento_recurso || 
		$evento_problema || 
		$evento_demanda || 
		$evento_programa || 
		$evento_licao || 
		$evento_evento || 
		$evento_link || 
		$evento_avaliacao || 
		$evento_tgn || 
		$evento_brainstorm || 
		$evento_gut || 
		$evento_causa_efeito || 
		$evento_arquivo || 
		$evento_forum || 
		$evento_checklist || 
		$evento_agenda || 
		$evento_agrupamento || 
		$evento_patrocinador || 
		$evento_template || 
		$evento_painel || 
		$evento_painel_odometro || 
		$evento_painel_composicao || 
		$evento_tr || 
		$evento_me || 
		$evento_acao_item || 
		$evento_beneficio || 
		$evento_painel_slideshow || 
		$evento_projeto_viabilidade || 
		$evento_projeto_abertura || 
		$evento_plano_gestao|| 
		$evento_ssti || 
		$evento_laudo || 
		$evento_trelo || 
		$evento_trelo_cartao || 
		$evento_pdcl || 
		$evento_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('evento_gestao');
			if ($uuid) $sql->adOnde('evento_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
			$sql->exec();
			}
		
		//verificar se já não inseriu antes
		$sql->adTabela('evento_gestao');
		$sql->adCampo('count(evento_gestao_id)');
		if ($uuid) $sql->adOnde('evento_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
		if ($evento_tarefa) $sql->adOnde('evento_gestao_tarefa='.(int)$evento_tarefa);
		elseif ($evento_projeto) $sql->adOnde('evento_gestao_projeto='.(int)$evento_projeto);
		elseif ($evento_perspectiva) $sql->adOnde('evento_gestao_perspectiva='.(int)$evento_perspectiva);
		elseif ($evento_tema) $sql->adOnde('evento_gestao_tema='.(int)$evento_tema);
		elseif ($evento_objetivo) $sql->adOnde('evento_gestao_objetivo='.(int)$evento_objetivo);
		elseif ($evento_fator) $sql->adOnde('evento_gestao_fator='.(int)$evento_fator);
		elseif ($evento_estrategia) $sql->adOnde('evento_gestao_estrategia='.(int)$evento_estrategia);
		elseif ($evento_acao) $sql->adOnde('evento_gestao_acao='.(int)$evento_acao);
		elseif ($evento_pratica) $sql->adOnde('evento_gestao_pratica='.(int)$evento_pratica);
		elseif ($evento_meta) $sql->adOnde('evento_gestao_meta='.(int)$evento_meta);
		elseif ($evento_canvas) $sql->adOnde('evento_gestao_canvas='.(int)$evento_canvas);
		elseif ($evento_risco) $sql->adOnde('evento_gestao_risco='.(int)$evento_risco);
		elseif ($evento_risco_resposta) $sql->adOnde('evento_gestao_risco_resposta='.(int)$evento_risco_resposta);
		elseif ($evento_indicador) $sql->adOnde('evento_gestao_indicador='.(int)$evento_indicador);
		elseif ($evento_calendario) $sql->adOnde('evento_gestao_calendario='.(int)$evento_calendario);
		elseif ($evento_monitoramento) $sql->adOnde('evento_gestao_monitoramento='.(int)$evento_monitoramento);
		elseif ($evento_ata) $sql->adOnde('evento_gestao_ata='.(int)$evento_ata);
		elseif ($evento_mswot) $sql->adOnde('evento_gestao_mswot='.(int)$evento_mswot);
		elseif ($evento_swot) $sql->adOnde('evento_gestao_swot='.(int)$evento_swot);
		elseif ($evento_operativo) $sql->adOnde('evento_gestao_operativo='.(int)$evento_operativo);
		elseif ($evento_instrumento) $sql->adOnde('evento_gestao_instrumento='.(int)$evento_instrumento);
		elseif ($evento_recurso) $sql->adOnde('evento_gestao_recurso='.(int)$evento_recurso);
		elseif ($evento_problema) $sql->adOnde('evento_gestao_problema='.(int)$evento_problema);
		elseif ($evento_demanda) $sql->adOnde('evento_gestao_demanda='.(int)$evento_demanda);
		elseif ($evento_programa) $sql->adOnde('evento_gestao_programa='.(int)$evento_programa);
		elseif ($evento_licao) $sql->adOnde('evento_gestao_licao='.(int)$evento_licao);
		
		elseif ($evento_evento) $sql->adOnde('evento_gestao_semelhante='.(int)$evento_evento);
		
		elseif ($evento_link) $sql->adOnde('evento_gestao_link='.(int)$evento_link);
		elseif ($evento_avaliacao) $sql->adOnde('evento_gestao_avaliacao='.(int)$evento_avaliacao);
		elseif ($evento_tgn) $sql->adOnde('evento_gestao_tgn='.(int)$evento_tgn);
		elseif ($evento_brainstorm) $sql->adOnde('evento_gestao_brainstorm='.(int)$evento_brainstorm);
		elseif ($evento_gut) $sql->adOnde('evento_gestao_gut='.(int)$evento_gut);
		elseif ($evento_causa_efeito) $sql->adOnde('evento_gestao_causa_efeito='.(int)$evento_causa_efeito);
		elseif ($evento_arquivo) $sql->adOnde('evento_gestao_arquivo='.(int)$evento_arquivo);
		elseif ($evento_forum) $sql->adOnde('evento_gestao_forum='.(int)$evento_forum);
		elseif ($evento_checklist) $sql->adOnde('evento_gestao_checklist='.(int)$evento_checklist);
		elseif ($evento_agenda) $sql->adOnde('evento_gestao_agenda='.(int)$evento_agenda);
		elseif ($evento_agrupamento) $sql->adOnde('evento_gestao_agrupamento='.(int)$evento_agrupamento);
		elseif ($evento_patrocinador) $sql->adOnde('evento_gestao_patrocinador='.(int)$evento_patrocinador);
		elseif ($evento_template) $sql->adOnde('evento_gestao_template='.(int)$evento_template);
		elseif ($evento_painel) $sql->adOnde('evento_gestao_painel='.(int)$evento_painel);
		elseif ($evento_painel_odometro) $sql->adOnde('evento_gestao_painel_odometro='.(int)$evento_painel_odometro);
		elseif ($evento_painel_composicao) $sql->adOnde('evento_gestao_painel_composicao='.(int)$evento_painel_composicao);
		elseif ($evento_tr) $sql->adOnde('evento_gestao_tr='.(int)$evento_tr);
		elseif ($evento_me) $sql->adOnde('evento_gestao_me='.(int)$evento_me);
		elseif ($evento_acao_item) $sql->adOnde('evento_gestao_acao_item='.(int)$evento_acao_item);
		elseif ($evento_beneficio) $sql->adOnde('evento_gestao_beneficio='.(int)$evento_beneficio);
		elseif ($evento_painel_slideshow) $sql->adOnde('evento_gestao_painel_slideshow='.(int)$evento_painel_slideshow);
		elseif ($evento_projeto_viabilidade) $sql->adOnde('evento_gestao_projeto_viabilidade='.(int)$evento_projeto_viabilidade);
		elseif ($evento_projeto_abertura) $sql->adOnde('evento_gestao_projeto_abertura='.(int)$evento_projeto_abertura);
		elseif ($evento_plano_gestao) $sql->adOnde('evento_gestao_plano_gestao='.(int)$evento_plano_gestao);
		elseif ($evento_ssti) $sql->adOnde('evento_gestao_ssti='.(int)$evento_ssti);
		elseif ($evento_laudo) $sql->adOnde('evento_gestao_laudo='.(int)$evento_laudo);
		elseif ($evento_trelo) $sql->adOnde('evento_gestao_trelo='.(int)$evento_trelo);
		elseif ($evento_trelo_cartao) $sql->adOnde('evento_gestao_trelo_cartao='.(int)$evento_trelo_cartao);
		elseif ($evento_pdcl) $sql->adOnde('evento_gestao_pdcl='.(int)$evento_pdcl);
		elseif ($evento_pdcl_item) $sql->adOnde('evento_gestao_pdcl_item='.(int)$evento_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('evento_gestao');
			$sql->adCampo('MAX(evento_gestao_ordem)');
			if ($uuid) $sql->adOnde('evento_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('evento_gestao');
			if ($uuid) $sql->adInserir('evento_gestao_uuid', $uuid);
			else $sql->adInserir('evento_gestao_evento', (int)$evento_id);
			
			if ($evento_tarefa) $sql->adInserir('evento_gestao_tarefa', (int)$evento_tarefa);
			if ($evento_projeto) $sql->adInserir('evento_gestao_projeto', (int)$evento_projeto);
			elseif ($evento_perspectiva) $sql->adInserir('evento_gestao_perspectiva', (int)$evento_perspectiva);
			elseif ($evento_tema) $sql->adInserir('evento_gestao_tema', (int)$evento_tema);
			elseif ($evento_objetivo) $sql->adInserir('evento_gestao_objetivo', (int)$evento_objetivo);
			elseif ($evento_fator) $sql->adInserir('evento_gestao_fator', (int)$evento_fator);
			elseif ($evento_estrategia) $sql->adInserir('evento_gestao_estrategia', (int)$evento_estrategia);
			elseif ($evento_acao) $sql->adInserir('evento_gestao_acao', (int)$evento_acao);
			elseif ($evento_pratica) $sql->adInserir('evento_gestao_pratica', (int)$evento_pratica);
			elseif ($evento_meta) $sql->adInserir('evento_gestao_meta', (int)$evento_meta);
			elseif ($evento_canvas) $sql->adInserir('evento_gestao_canvas', (int)$evento_canvas);
			elseif ($evento_risco) $sql->adInserir('evento_gestao_risco', (int)$evento_risco);
			elseif ($evento_risco_resposta) $sql->adInserir('evento_gestao_risco_resposta', (int)$evento_risco_resposta);
			elseif ($evento_indicador) $sql->adInserir('evento_gestao_indicador', (int)$evento_indicador);
			elseif ($evento_calendario) $sql->adInserir('evento_gestao_calendario', (int)$evento_calendario);
			elseif ($evento_monitoramento) $sql->adInserir('evento_gestao_monitoramento', (int)$evento_monitoramento);
			elseif ($evento_ata) $sql->adInserir('evento_gestao_ata', (int)$evento_ata);
			elseif ($evento_mswot) $sql->adInserir('evento_gestao_mswot', (int)$evento_mswot);
			elseif ($evento_swot) $sql->adInserir('evento_gestao_swot', (int)$evento_swot);
			elseif ($evento_operativo) $sql->adInserir('evento_gestao_operativo', (int)$evento_operativo);
			elseif ($evento_instrumento) $sql->adInserir('evento_gestao_instrumento', (int)$evento_instrumento);
			elseif ($evento_recurso) $sql->adInserir('evento_gestao_recurso', (int)$evento_recurso);
			elseif ($evento_problema) $sql->adInserir('evento_gestao_problema', (int)$evento_problema);
			elseif ($evento_demanda) $sql->adInserir('evento_gestao_demanda', (int)$evento_demanda);
			elseif ($evento_programa) $sql->adInserir('evento_gestao_programa', (int)$evento_programa);
			elseif ($evento_licao) $sql->adInserir('evento_gestao_licao', (int)$evento_licao);
			
			elseif ($evento_evento) $sql->adInserir('evento_gestao_semelhante', (int)$evento_evento);
			
			elseif ($evento_link) $sql->adInserir('evento_gestao_link', (int)$evento_link);
			elseif ($evento_avaliacao) $sql->adInserir('evento_gestao_avaliacao', (int)$evento_avaliacao);
			elseif ($evento_tgn) $sql->adInserir('evento_gestao_tgn', (int)$evento_tgn);
			elseif ($evento_brainstorm) $sql->adInserir('evento_gestao_brainstorm', (int)$evento_brainstorm);
			elseif ($evento_gut) $sql->adInserir('evento_gestao_gut', (int)$evento_gut);
			elseif ($evento_causa_efeito) $sql->adInserir('evento_gestao_causa_efeito', (int)$evento_causa_efeito);
			elseif ($evento_arquivo) $sql->adInserir('evento_gestao_arquivo', (int)$evento_arquivo);
			elseif ($evento_forum) $sql->adInserir('evento_gestao_forum', (int)$evento_forum);
			elseif ($evento_checklist) $sql->adInserir('evento_gestao_checklist', (int)$evento_checklist);
			elseif ($evento_agenda) $sql->adInserir('evento_gestao_agenda', (int)$evento_agenda);
			elseif ($evento_agrupamento) $sql->adInserir('evento_gestao_agrupamento', (int)$evento_agrupamento);
			elseif ($evento_patrocinador) $sql->adInserir('evento_gestao_patrocinador', (int)$evento_patrocinador);
			elseif ($evento_template) $sql->adInserir('evento_gestao_template', (int)$evento_template);
			elseif ($evento_painel) $sql->adInserir('evento_gestao_painel', (int)$evento_painel);
			elseif ($evento_painel_odometro) $sql->adInserir('evento_gestao_painel_odometro', (int)$evento_painel_odometro);
			elseif ($evento_painel_composicao) $sql->adInserir('evento_gestao_painel_composicao', (int)$evento_painel_composicao);
			elseif ($evento_tr) $sql->adInserir('evento_gestao_tr', (int)$evento_tr);
			elseif ($evento_me) $sql->adInserir('evento_gestao_me', (int)$evento_me);
			elseif ($evento_acao_item) $sql->adInserir('evento_gestao_acao_item', (int)$evento_acao_item);
			elseif ($evento_beneficio) $sql->adInserir('evento_gestao_beneficio', (int)$evento_beneficio);
			elseif ($evento_painel_slideshow) $sql->adInserir('evento_gestao_painel_slideshow', (int)$evento_painel_slideshow);
			elseif ($evento_projeto_viabilidade) $sql->adInserir('evento_gestao_projeto_viabilidade', (int)$evento_projeto_viabilidade);
			elseif ($evento_projeto_abertura) $sql->adInserir('evento_gestao_projeto_abertura', (int)$evento_projeto_abertura);
			elseif ($evento_plano_gestao) $sql->adInserir('evento_gestao_plano_gestao', (int)$evento_plano_gestao);
			elseif ($evento_ssti) $sql->adInserir('evento_gestao_ssti', (int)$evento_ssti);
			elseif ($evento_laudo) $sql->adInserir('evento_gestao_laudo', (int)$evento_laudo);
			elseif ($evento_trelo) $sql->adInserir('evento_gestao_trelo', (int)$evento_trelo);
			elseif ($evento_trelo_cartao) $sql->adInserir('evento_gestao_trelo_cartao', (int)$evento_trelo_cartao);
			elseif ($evento_pdcl) $sql->adInserir('evento_gestao_pdcl', (int)$evento_pdcl);
			elseif ($evento_pdcl_item) $sql->adInserir('evento_gestao_pdcl_item', (int)$evento_pdcl_item);
			
			$sql->adInserir('evento_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($evento_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($evento_id=0, $uuid='', $evento_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('evento_gestao');
	$sql->adOnde('evento_gestao_id='.(int)$evento_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($evento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($evento_id=0, $uuid=''){	
	$saida=atualizar_gestao($evento_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($evento_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('evento_gestao');
	$sql->adCampo('evento_gestao.*');
	if ($uuid) $sql->adOnde('evento_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
	$sql->adOrdem('evento_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['evento_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['evento_gestao_tarefa']).'</td>';
		elseif ($gestao_data['evento_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['evento_gestao_projeto']).'</td>';
		elseif ($gestao_data['evento_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['evento_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['evento_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['evento_gestao_tema']).'</td>';
		elseif ($gestao_data['evento_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['evento_gestao_objetivo']).'</td>';
		elseif ($gestao_data['evento_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['evento_gestao_fator']).'</td>';
		elseif ($gestao_data['evento_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['evento_gestao_estrategia']).'</td>';
		elseif ($gestao_data['evento_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['evento_gestao_meta']).'</td>';
		elseif ($gestao_data['evento_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['evento_gestao_pratica']).'</td>';
		elseif ($gestao_data['evento_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['evento_gestao_acao']).'</td>';
		elseif ($gestao_data['evento_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['evento_gestao_canvas']).'</td>';
		elseif ($gestao_data['evento_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['evento_gestao_risco']).'</td>';
		elseif ($gestao_data['evento_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['evento_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['evento_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['evento_gestao_indicador']).'</td>';
		elseif ($gestao_data['evento_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['evento_gestao_calendario']).'</td>';
		elseif ($gestao_data['evento_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['evento_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['evento_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['evento_gestao_ata']).'</td>';
		elseif ($gestao_data['evento_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['evento_gestao_mswot']).'</td>';
		elseif ($gestao_data['evento_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['evento_gestao_swot']).'</td>';
		elseif ($gestao_data['evento_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['evento_gestao_operativo']).'</td>';
		elseif ($gestao_data['evento_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['evento_gestao_instrumento']).'</td>';
		elseif ($gestao_data['evento_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['evento_gestao_recurso']).'</td>';
		elseif ($gestao_data['evento_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['evento_gestao_problema']).'</td>';
		elseif ($gestao_data['evento_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['evento_gestao_demanda']).'</td>';
		elseif ($gestao_data['evento_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['evento_gestao_programa']).'</td>';
		elseif ($gestao_data['evento_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['evento_gestao_licao']).'</td>';
		
		elseif ($gestao_data['evento_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['evento_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['evento_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['evento_gestao_link']).'</td>';
		elseif ($gestao_data['evento_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['evento_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['evento_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['evento_gestao_tgn']).'</td>';
		elseif ($gestao_data['evento_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['evento_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['evento_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['evento_gestao_gut']).'</td>';
		elseif ($gestao_data['evento_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['evento_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['evento_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['evento_gestao_arquivo']).'</td>';
		elseif ($gestao_data['evento_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['evento_gestao_forum']).'</td>';
		elseif ($gestao_data['evento_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['evento_gestao_checklist']).'</td>';
		elseif ($gestao_data['evento_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['evento_gestao_agenda']).'</td>';
		elseif ($gestao_data['evento_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['evento_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['evento_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['evento_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['evento_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['evento_gestao_template']).'</td>';
		elseif ($gestao_data['evento_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['evento_gestao_painel']).'</td>';
		elseif ($gestao_data['evento_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['evento_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['evento_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['evento_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['evento_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['evento_gestao_tr']).'</td>';	
		elseif ($gestao_data['evento_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['evento_gestao_me']).'</td>';	
		elseif ($gestao_data['evento_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['evento_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['evento_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['evento_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['evento_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['evento_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['evento_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['evento_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['evento_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['evento_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['evento_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['evento_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['evento_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['evento_gestao_ssti']).'</td>';
		elseif ($gestao_data['evento_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['evento_gestao_laudo']).'</td>';
		elseif ($gestao_data['evento_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['evento_gestao_trelo']).'</td>';
		elseif ($gestao_data['evento_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['evento_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['evento_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['evento_gestao_pdcl']).'</td>';
		elseif ($gestao_data['evento_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['evento_gestao_pdcl_item']).'</td>';
		
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['evento_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			

function calcular_duracao($inicio, $fim, $cia_id){
	global $config;
	$horas = horas_periodo($inicio, $fim, $cia_id);
	$objResposta = new xajaxResponse();
	$resultado=(float)$horas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
	$resultado=str_replace('.', ',',$resultado);
	$objResposta->assign("evento_duracao","value", $resultado);
	return $objResposta;
	}
$xajax->registerFunction("calcular_duracao");		


function data_final_periodo($inicio, $dias, $cia_id){
	$dias=float_americano($dias);
	$horas=abs($dias*config('horas_trab_diario'));
	$data_final = calculo_data_final_periodo($inicio, $horas, $cia_id);
	$data=new CData($data_final);
	$objResposta = new xajaxResponse();
	$objResposta->assign("oculto_data_fim","value", $data->format("%Y-%m-%d"));
	$objResposta->assign("data_fim","value", $data->format("%d/%m/%Y"));
	$objResposta->assign("fim_hora","value", $data->format("%H"));
	$objResposta->assign("fim_minuto","value", $data->format("%M"));
	return $objResposta;
	}	
$xajax->registerFunction("data_final_periodo");	

function exibir_contatos($contatos, $local){
	global $config;
	$contatos_selecionados=explode(',', $contatos);
	$saida_contatos='';
	if (count($contatos_selecionados)) {
			$saida_contatos.= '<table cellpadding=0 cellspacing=0>';
			$saida_contatos.= '<tr><td class="texto" style="width:400px;">'.link_contato($contatos_selecionados[0],'','','esquerda');
			$qnt_lista_contatos=count($contatos_selecionados);
			if ($qnt_lista_contatos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';		
					$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos_'.$local.'\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos_'.$local.'"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';
			} 
	else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign($local,"innerHTML", utf8_encode($saida_contatos));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_contatos");



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

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");
	
function responsavel_ajax($tipo='projeto', $id=0, $evento_id=null, $uuid=null) {
	global $Aplic, $config;
	$sql = new BDConsulta;
	

	$sql->adTabela('evento_gestao');
	$sql->adCampo('evento_gestao.*');
	if ($evento_id) $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
	else $sql->adOnde('evento_gestao_uuid =\''.$uuid.'\'');	
  $lista = $sql->Lista();
  $sql->limpar();
	$responsavel=array();
	foreach($lista as $linha){
		if ($linha['evento_gestao_tarefa']){
			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_dono');
			$sql->adOnde('tarefa_id = '.(int)$linha['evento_gestao_tarefa']);
			}
		elseif ($linha['evento_gestao_projeto']){
			$sql->adTabela('projetos');
			$sql->adCampo('projeto_responsavel');
			$sql->adOnde('projeto_id = '.(int)$linha['evento_gestao_projeto']);
			}	
		elseif ($linha['evento_gestao_perspectiva']){
			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_usuario');
			$sql->adOnde('pg_perspectiva_id = '.(int)$linha['evento_gestao_perspectiva']);
			}	
		elseif ($linha['evento_gestao_tema']){
			$sql->adTabela('tema');
			$sql->adCampo('tema_usuario');
			$sql->adOnde('tema_id = '.(int)$linha['evento_gestao_tema']);
			}	
		elseif ($linha['evento_gestao_objetivo']){
			$sql->adTabela('objetivo');
			$sql->adCampo('objetivo_usuario');
			$sql->adOnde('objetivo_id = '.(int)$linha['evento_gestao_objetivo']);
			}	
		elseif ($linha['evento_gestao_fator']){
			$sql->adTabela('fator');
			$sql->adCampo('fator_usuario');
			$sql->adOnde('fator_id = '.(int)$linha['evento_gestao_fator']);
			}		
			elseif ($linha['evento_gestao_estrategia']){
			$sql->adTabela('estrategias');
			$sql->adCampo('pg_estrategia_usuario');
			$sql->adOnde('pg_estrategia_id = '.(int)$linha['evento_gestao_estrategia']);
			}	
		elseif ($linha['evento_gestao_meta']){
			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_responsavel');
			$sql->adOnde('pg_meta_id = '.(int)$linha['evento_gestao_meta']);
			}			
		elseif ($linha['evento_gestao_pratica']){
			$sql->adTabela('praticas');
			$sql->adCampo('pratica_responsavel');
			$sql->adOnde('pratica_id = '.(int)$linha['evento_gestao_pratica']);
			}
		elseif ($linha['evento_gestao_indicador']){
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_responsavel');
			$sql->adOnde('pratica_indicador_id = '.(int)$linha['evento_gestao_indicador']);
			}
		elseif ($linha['evento_gestao_acao']){
			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_id = '.(int)$linha['evento_gestao_acao']);
			}
		elseif ($linha['evento_gestao_canvas']){
			$sql->adTabela('canvas');
			$sql->adCampo('canvas_usuario');
			$sql->adOnde('canvas_id = '.(int)$linha['evento_gestao_canvas']);
			}
		elseif ($linha['evento_gestao_risco']){
			$sql->adTabela('risco');
			$sql->adCampo('risco_usuario');
			$sql->adOnde('risco_id = '.(int)$linha['evento_gestao_risco']);
			}		
		elseif ($linha['evento_gestao_risco_resposta']){
			$sql->adTabela('risco_resposta');
			$sql->adCampo('risco_resposta_usuario');
			$sql->adOnde('risco_resposta_id = '.(int)$linha['evento_gestao_risco_resposta']);
			}
		elseif ($linha['evento_gestao_calendario']){
			$sql->adTabela('calendario');
			$sql->adCampo('calendario_usuario');
			$sql->adOnde('calendario_id = '.(int)$linha['evento_gestao_calendario']);
			}	
		elseif ($linha['evento_gestao_monitoramento']){
			$sql->adTabela('monitoramento');
			$sql->adCampo('monitoramento_usuario');
			$sql->adOnde('monitoramento_id = '.(int)$linha['evento_gestao_monitoramento']);
			}
		elseif ($linha['evento_gestao_ata']){
			$sql->adTabela('ata');
			$sql->adCampo('ata_responsavel');
			$sql->adOnde('ata_id = '.(int)$linha['evento_gestao_ata']);
			}		
		elseif ($linha['evento_gestao_mswot']){
			$sql->adTabela('mswot');
			$sql->adCampo('mswot_responsavel');
			$sql->adOnde('mswot_id = '.(int)$linha['evento_gestao_mswot']);
			}
		elseif ($linha['evento_gestao_swot']){
			$sql->adTabela('swot');
			$sql->adCampo('swot_responsavel');
			$sql->adOnde('swot_id = '.(int)$linha['evento_gestao_swot']);
			}	
		elseif ($linha['evento_gestao_operativo']){
			$sql->adTabela('operativo');
			$sql->adCampo('operativo_usuario');
			$sql->adOnde('operativo_id = '.(int)$linha['evento_gestao_operativo']);
			}		
		elseif ($linha['evento_gestao_instrumento']){
			$sql->adTabela('instrumento');
			$sql->adCampo('instrumento_responsavel');
			$sql->adOnde('instrumento_id = '.(int)$linha['evento_gestao_instrumento']);
			}
		elseif ($linha['evento_gestao_recurso']){
			$sql->adTabela('recursos');
			$sql->adCampo('recurso_responsavel');
			$sql->adOnde('recurso_id = '.(int)$linha['evento_gestao_recurso']);
			}		
		elseif ($linha['evento_gestao_problema']){
			$sql->adTabela('problema');
			$sql->adCampo('problema_responsavel');
			$sql->adOnde('problema_id = '.(int)$linha['evento_gestao_problema']);
			}
		elseif ($linha['evento_gestao_demanda']){
			$sql->adTabela('demandas');
			$sql->adCampo('demanda_usuario');
			$sql->adOnde('demanda_id = '.(int)$linha['evento_gestao_demanda']);
			}		
		elseif ($linha['evento_gestao_programa']){
			$sql->adTabela('programa');
			$sql->adCampo('programa_usuario');
			$sql->adOnde('programa_id = '.(int)$linha['evento_gestao_programa']);
			}
		elseif ($linha['evento_gestao_licao']){
			$sql->adTabela('licao');
			$sql->adCampo('licao_responsavel');
			$sql->adOnde('licao_id = '.(int)$linha['evento_gestao_licao']);
			}		
		elseif ($linha['evento_gestao_link']){
			$sql->adTabela('links');
			$sql->adCampo('link_dono');
			$sql->adOnde('link_id = '.(int)$linha['evento_gestao_link']);
			}
		elseif ($linha['evento_gestao_avaliacao']){
			$sql->adTabela('avaliacao');
			$sql->adCampo('avaliacao_responsavel');
			$sql->adOnde('avaliacao_id = '.(int)$linha['evento_gestao_avaliacao']);
			}		
		elseif ($linha['evento_gestao_tgn']){
			$sql->adTabela('tgn');
			$sql->adCampo('tgn_usuario');
			$sql->adOnde('tgn_id = '.(int)$linha['evento_gestao_tgn']);
			}
		elseif ($linha['evento_gestao_brainstorm']){
			$sql->adTabela('brainstorm');
			$sql->adCampo('brainstorm_responsavel');
			$sql->adOnde('brainstorm_id = '.(int)$linha['evento_gestao_brainstorm']);
			}		
		elseif ($linha['evento_gestao_gut']){
			$sql->adTabela('gut');
			$sql->adCampo('gut_responsavel');
			$sql->adOnde('gut_id = '.(int)$linha['evento_gestao_gut']);
			}		
		elseif ($linha['evento_gestao_causa_efeito']){
			$sql->adTabela('causa_efeito');
			$sql->adCampo('causa_efeito_responsavel');
			$sql->adOnde('causa_efeito_id = '.(int)$linha['evento_gestao_causa_efeito']);
			}		
		elseif ($linha['evento_gestao_arquivo']){
			$sql->adTabela('arquivo');
			$sql->adCampo('arquivo_dono');
			$sql->adOnde('arquivo_id = '.(int)$linha['evento_gestao_arquivo']);
			}		
		elseif ($linha['evento_gestao_forum']){
			$sql->adTabela('foruns');
			$sql->adCampo('forum_dono');
			$sql->adOnde('forum_id = '.(int)$linha['evento_gestao_forum']);
			}		
		elseif ($linha['evento_gestao_checklist']){
			$sql->adTabela('checklist');
			$sql->adCampo('checklist_responsavel');
			$sql->adOnde('checklist_id = '.(int)$linha['evento_gestao_checklist']);
			}		
		elseif ($linha['evento_gestao_agenda']){
			$sql->adTabela('agenda');
			$sql->adCampo('agenda_dono');
			$sql->adOnde('agenda_id = '.(int)$linha['evento_gestao_agenda']);
			}																																																			
		elseif ($linha['evento_gestao_agrupamento']){
			$sql->adTabela('agrupamento');
			$sql->adCampo('agrupamento_usuario');
			$sql->adOnde('agrupamento_id = '.(int)$linha['evento_gestao_agrupamento']);
			}		
		elseif ($linha['evento_gestao_patrocinador']){
			$sql->adTabela('patrocinadores');
			$sql->adCampo('patrocinador_responsavel');
			$sql->adOnde('patrocinador_id = '.(int)$linha['evento_gestao_patrocinador']);
			}		
		elseif ($linha['evento_gestao_template']){
			$sql->adTabela('template');
			$sql->adCampo('template_responsavel');
			$sql->adOnde('template_id = '.(int)$linha['evento_gestao_template']);
			}		
		elseif ($linha['evento_gestao_painel']){
			$sql->adTabela('painel');
			$sql->adCampo('painel_responsavel');
			$sql->adOnde('painel_id = '.(int)$linha['evento_gestao_painel']);
			}		
		elseif ($linha['evento_gestao_painel_odometro']){
			$sql->adTabela('painel_odometro');
			$sql->adCampo('painel_odometro_responsavel');
			$sql->adOnde('painel_odometro_id = '.(int)$linha['evento_gestao_painel_odometro']);
			}		
		elseif ($linha['evento_gestao_painel_composicao']){
			$sql->adTabela('painel_composicao');
			$sql->adCampo('painel_composicao_responsavel');
			$sql->adOnde('painel_composicao_id = '.(int)$linha['evento_gestao_painel_composicao']);
			}		
		elseif ($linha['evento_gestao_tr']){
			$sql->adTabela('tr');
			$sql->adCampo('tr_responsavel');
			$sql->adOnde('tr_id = '.(int)$linha['evento_gestao_tr']);
			}	
		elseif ($linha['evento_gestao_me']){
			$sql->adTabela('me');
			$sql->adCampo('me_usuario');
			$sql->adOnde('me_id = '.(int)$linha['evento_gestao_me']);
			}		
		elseif ($linha['evento_gestao_acao_item']){
			$sql->adTabela('plano_acao_item');
			$sql->adCampo('plano_acao_item_responsavel');
			$sql->adOnde('plano_acao_item_id = '.(int)$linha['evento_gestao_acao_item']);
			}		
		elseif ($linha['evento_gestao_beneficio']){
			$sql->adTabela('beneficio');
			$sql->adCampo('beneficio_usuario');
			$sql->adOnde('beneficio_id = '.(int)$linha['evento_gestao_beneficio']);
			}				
		elseif ($linha['evento_gestao_painel_slideshow']){
			$sql->adTabela('painel_slideshow');
			$sql->adCampo('painel_slideshow_responsavel');
			$sql->adOnde('painel_slideshow_id = '.(int)$linha['evento_gestao_painel_slideshow']);
			}			
		elseif ($linha['evento_gestao_projeto_viabilidade']){
			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('projeto_viabilidade_responsavel');
			$sql->adOnde('projeto_viabilidade_id = '.(int)$linha['evento_gestao_projeto_viabilidade']);
			}		
		elseif ($linha['evento_gestao_projeto_abertura']){
			$sql->adTabela('projeto_abertura');
			$sql->adCampo('projeto_abertura_responsavel');
			$sql->adOnde('projeto_abertura_id = '.(int)$linha['evento_gestao_projeto_abertura']);
			}				
		elseif ($linha['evento_gestao_plano_gestao']){
			$sql->adTabela('plano_gestao');
			$sql->adCampo('pg_usuario');
			$sql->adOnde('pg_id = '.(int)$linha['evento_gestao_plano_gestao']);
			}				
		elseif ($linha['evento_gestao_ssti']){
			$sql->adTabela('ssti');
			$sql->adCampo('ssti_responsavel');
			$sql->adOnde('ssti_id = '.(int)$linha['evento_gestao_ssti']);
			}			
		elseif ($linha['evento_gestao_laudo']){
			$sql->adTabela('laudo');
			$sql->adCampo('laudo_responsavel');
			$sql->adOnde('laudo_id = '.(int)$linha['evento_gestao_laudo']);
			}			
		elseif ($linha['evento_gestao_trelo']){
			$sql->adTabela('trelo');
			$sql->adCampo('trelo_responsavel');
			$sql->adOnde('trelo_id = '.(int)$linha['evento_gestao_trelo']);
			}			
		elseif ($linha['evento_gestao_trelo_cartao']){
			$sql->adTabela('trelo_cartao');
			$sql->adCampo('trelo_cartao_responsavel');
			$sql->adOnde('trelo_cartao_id = '.(int)$linha['evento_gestao_trelo_cartao']);
			}			
		elseif ($linha['evento_gestao_pdcl']){
			$sql->adTabela('pdcl');
			$sql->adCampo('pdcl_responsavel');
			$sql->adOnde('pdcl_id = '.(int)$linha['evento_gestao_pdcl']);
			}			
		elseif ($linha['evento_gestao_pdcl_item']){
			$sql->adTabela('pdcl_item');
			$sql->adCampo('pdcl_item_responsavel');
			$sql->adOnde('pdcl_item_id = '.(int)$linha['evento_gestao_pdcl_item']);
			}			
				
				
		$resultado=$sql->resultado();
		$sql->limpar();
		if($resultado) $responsavel[$resultado]=$resultado;
		}
	$responsavel=implode(',', $responsavel);
		
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
	$sql->adOnde('usuario_id IN ('.($responsavel ? $responsavel : 0).')');	
	$usuarios = $sql->Lista();
	$sql->limpar();
	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) $saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode(nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';
	$saida.='</select>';
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("responsavel_ajax");	
	
	
function designados_ajax($tipo='projeto', $id=0, $evento_id=null, $uuid=null) {
	global $Aplic, $config;
	$sql = new BDConsulta;
	

	$sql->adTabela('evento_gestao');
	$sql->adCampo('evento_gestao.*');
	if ($evento_id) $sql->adOnde('evento_gestao_evento ='.(int)$evento_id);	
	else $sql->adOnde('evento_gestao_uuid =\''.$uuid.'\'');	
  $lista = $sql->Lista();
  $sql->limpar();
	$designados=array();
	foreach($lista as $linha){
		
		if ($linha['evento_gestao_tarefa']){
			$sql->adTabela('tarefa_designados');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('tarefa_id = '.(int)$linha['evento_gestao_tarefa']);
			}
		elseif ($linha['evento_gestao_projeto']){
			$sql->adTabela('projeto_integrantes');
			$sql->esqUnir('usuarios','usuarios','contato_id=usuario_contato');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('projeto_id = '.(int)$linha['evento_gestao_projeto']);
			}
		elseif ($linha['evento_gestao_perspectiva']){
			$sql->adTabela('perspectivas_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('pg_perspectiva_id = '.(int)$linha['evento_gestao_perspectiva']);
			}	
		elseif ($linha['evento_gestao_tema']){
			$sql->adTabela('tema_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('tema_id = '.(int)$linha['evento_gestao_tema']);
			}	
		elseif ($linha['evento_gestao_objetivo']){
			$sql->adTabela('objetivo_usuario');
			$sql->adCampo('DISTINCT objetivo_usuario_usuario');
			$sql->adOnde('objetivo_usuario_objetivo = '.(int)$linha['evento_gestao_objetivo']);
			}	
		elseif ($linha['evento_gestao_fator']){
			$sql->adTabela('fator_usuario');
			$sql->adCampo('DISTINCT fator_usuario_usuario');
			$sql->adOnde('fator_usuario_fator = '.(int)$linha['evento_gestao_fator']);
			}		
		elseif ($linha['evento_gestao_estrategia']){
			$sql->adTabela('estrategias_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('pg_estrategia_id = '.(int)$linha['evento_gestao_estrategia']);
			}	
		elseif ($linha['evento_gestao_meta']){
			$sql->adTabela('metas_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('pg_meta_id = '.(int)$linha['evento_gestao_meta']);
			}			
		elseif ($linha['evento_gestao_pratica']){
			$sql->adTabela('pratica_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('pratica_id = '.(int)$linha['evento_gestao_pratica']);
			}
		elseif ($linha['evento_gestao_indicador']){
			$sql->adTabela('pratica_indicador_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('pratica_indicador_id = '.(int)$linha['evento_gestao_indicador']);
			}
		elseif ($linha['evento_gestao_acao']){
			$sql->adTabela('plano_acao_usuario');
			$sql->adCampo('DISTINCT plano_acao_usuario_usuario');
			$sql->adOnde('plano_acao_usuario_acao = '.(int)$linha['evento_gestao_acao']);
			}
		elseif ($linha['evento_gestao_canvas']){
			$sql->adTabela('canvas_usuario');
			$sql->adCampo('DISTINCT canvas_usuario_usuario');
			$sql->adOnde('canvas_usuario_canvas = '.(int)$linha['evento_gestao_canvas']);
			}
		elseif ($linha['evento_gestao_risco']){
			$sql->adTabela('risco_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('risco_id = '.(int)$linha['evento_gestao_risco']);
			}	
		elseif ($linha['evento_gestao_risco_resposta']){
			$sql->adTabela('risco_resposta_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('risco_resposta_id = '.(int)$linha['evento_gestao_risco_resposta']);
			}
		elseif ($linha['evento_gestao_calendario']){
			$sql->adTabela('calendario_usuario');
			$sql->adCampo('DISTINCT calendario_usuario_usuario');
			$sql->adOnde('calendario_usuario_calendario = '.(int)$linha['evento_gestao_calendario']);
			}	
		elseif ($linha['evento_gestao_monitoramento']){
			$sql->adTabela('monitoramento_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('monitoramento_id = '.(int)$linha['evento_gestao_monitoramento']);
			}
		elseif ($linha['evento_gestao_ata']){
			$sql->adTabela('ata_usuario');
			$sql->adCampo('DISTINCT ata_usuario_usuario');
			$sql->adOnde('ata_usuario_ata = '.(int)$linha['evento_gestao_ata']);
			}	
		elseif ($linha['evento_gestao_mswot']){
			$sql->adTabela('mswot_usuario');
			$sql->adCampo('DISTINCT mswot_usuario_usuario');
			$sql->adOnde('mswot_usuario_swot= '.(int)$linha['evento_gestao_swot']);
			}
		elseif ($linha['evento_gestao_swot']){
			$sql->adTabela('swot_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('swot_id = '.(int)$linha['evento_gestao_swot']);
			}	
		elseif ($linha['evento_gestao_operativo']){
			$sql->adTabela('operativo_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('operativo_id = '.(int)$linha['evento_gestao_operativo']);
			}	
		elseif ($linha['evento_gestao_instrumento']){
			$sql->adTabela('instrumento_designados');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('instrumento_id = '.(int)$linha['evento_gestao_instrumento']);
			}
		elseif ($linha['evento_gestao_recurso']){
			$sql->adTabela('recurso_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('recurso_id = '.(int)$linha['evento_gestao_recurso']);
			}	
		elseif ($linha['evento_gestao_problema']){
			$sql->adTabela('problema_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('problema_id = '.(int)$linha['evento_gestao_problema']);
			}
		elseif ($linha['evento_gestao_demanda']){
			$sql->adTabela('demanda_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('demanda_id = '.(int)$linha['evento_gestao_demanda']);
			}	
		elseif ($linha['evento_gestao_programa']){
			$sql->adTabela('programa_usuario');
			$sql->adCampo('DISTINCT programa_usuario_usuario');
			$sql->adOnde('programa_usuario_programa = '.(int)$linha['evento_gestao_programa']);
			}
		elseif ($linha['evento_gestao_licao']){
			$sql->adTabela('licao_usuario');
			$sql->adCampo('DISTINCT licao_usuario_usuario');
			$sql->adOnde('licao_usuario_licao = '.(int)$linha['evento_gestao_licao']);
			}	
		elseif ($linha['evento_gestao_link']){
			$sql->adTabela('link_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('link_id = '.(int)$linha['evento_gestao_link']);
			}
		elseif ($linha['evento_gestao_avaliacao']){
			$sql->adTabela('avaliacao_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('avaliacao_id = '.(int)$linha['evento_gestao_avaliacao']);
			}	
		elseif ($linha['evento_gestao_tgn']){
			$sql->adTabela('tgn_usuario');
			$sql->adCampo('DISTINCT tgn_usuario_usuario');
			$sql->adOnde('tgn_usuario_tgn = '.(int)$linha['evento_gestao_tgn']);
			}
		elseif ($linha['evento_gestao_brainstorm']){
			$sql->adTabela('brainstorm_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('brainstorm_id = '.(int)$linha['evento_gestao_brainstorm']);
			}	
		elseif ($linha['evento_gestao_gut']){
			$sql->adTabela('gut_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('gut_id = '.(int)$linha['evento_gestao_gut']);
			}
		elseif ($linha['evento_gestao_causa_efeito']){
			$sql->adTabela('causa_efeito_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('causa_efeito_id = '.(int)$linha['evento_gestao_causa_efeito']);
			}	
		elseif ($linha['evento_gestao_arquivo']){
			$sql->adTabela('arquivo_usuario');
			$sql->adCampo('DISTINCT arquivo_usuario_usuario');
			$sql->adOnde('arquivo_usuario_arquivo = '.(int)$linha['evento_gestao_arquivo']);
			}
		elseif ($linha['evento_gestao_forum']){
			$sql->adTabela('forum_usuario');
			$sql->adCampo('DISTINCT forum_usuario_usuario');
			$sql->adOnde('forum_usuario_forum = '.(int)$linha['evento_gestao_forum']);
			}	
		elseif ($linha['evento_gestao_checklist']){
			$sql->adTabela('checklist_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('checklist_id = '.(int)$linha['evento_gestao_checklist']);
			}
		elseif ($linha['evento_gestao_agenda']){
			$sql->adTabela('agenda_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('agenda_id = '.(int)$linha['evento_gestao_agenda']);
			}	
		elseif ($linha['evento_gestao_agrupamento']){
			$sql->adTabela('agrupamento_usuario');
			$sql->adCampo('DISTINCT agrupamento_usuario_usuario');
			$sql->adOnde('agrupamento_usuario_agrupamento = '.(int)$linha['evento_gestao_agrupamento']);
			}
		elseif ($linha['evento_gestao_patrocinador']){
			$sql->adTabela('patrocinadores_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('patrocinador_id = '.(int)$linha['evento_gestao_patrocinador']);
			}	
		elseif ($linha['evento_gestao_template']){
			$sql->adTabela('template_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('template_id = '.(int)$linha['evento_gestao_template']);
			}
		elseif ($linha['evento_gestao_painel']){
			$sql->adTabela('painel_usuario');
			$sql->adCampo('DISTINCT painel_usuario_usuario');
			$sql->adOnde('painel_usuario_painel = '.(int)$linha['evento_gestao_painel']);
			}	
		elseif ($linha['evento_gestao_painel_odometro']){
			$sql->adTabela('painel_odometro_usuario');
			$sql->adCampo('DISTINCT painel_odometro_usuario_usuario');
			$sql->adOnde('painel_odometro_usuario_painel_odometro = '.(int)$linha['evento_gestao_painel_odometro']);
			}
		elseif ($linha['evento_gestao_painel_composicao']){
			$sql->adTabela('painel_composicao_usuario');
			$sql->adCampo('DISTINCT painel_composicao_usuario_usuario');
			$sql->adOnde('painel_composicao_usuario_painel_composicao = '.(int)$linha['evento_gestao_painel_composicao']);
			}	
		elseif ($linha['evento_gestao_tr']){
			$sql->adTabela('tr_usuario');
			$sql->adCampo('DISTINCT tr_usuario_usuario');
			$sql->adOnde('tr_usuario_tr = '.(int)$linha['evento_gestao_tr']);
			}
		elseif ($linha['evento_gestao_me']){
			$sql->adTabela('me_usuario');
			$sql->adCampo('DISTINCT me_usuario_usuario');
			$sql->adOnde('me_usuario_me = '.(int)$linha['evento_gestao_me']);
			}	

		elseif ($linha['evento_gestao_acao_item']){
			$sql->adTabela('plano_acao_item_usuario');
			$sql->adCampo('DISTINCT plano_acao_item_usuario_usuario');
			$sql->adOnde('plano_acao_item_usuario_item = '.(int)$linha['evento_gestao_acao_item']);
			}		
		elseif ($linha['evento_gestao_beneficio']){
			$sql->adTabela('beneficio_usuario');
			$sql->adCampo('DISTINCT beneficio_usuario_usuario');
			$sql->adOnde('beneficio_usuario_beneficio = '.(int)$linha['evento_gestao_beneficio']);
			}				
		elseif ($linha['evento_gestao_painel_slideshow']){
			$sql->adTabela('painel_slideshow_usuario');
			$sql->adCampo('DISTINCT painel_slideshow_usuario_usuario');
			$sql->adOnde('painel_slideshow_usuario_slideshow = '.(int)$linha['evento_gestao_painel_slideshow']);
			}			
		elseif ($linha['evento_gestao_projeto_viabilidade']){
			$sql->adTabela('projeto_viabilidade_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('projeto_viabilidade_id = '.(int)$linha['evento_gestao_projeto_viabilidade']);
			}		
		elseif ($linha['evento_gestao_projeto_abertura']){
			$sql->adTabela('projeto_abertura_usuarios');
			$sql->adCampo('DISTINCT usuario_id');
			$sql->adOnde('projeto_abertura_id = '.(int)$linha['evento_gestao_projeto_abertura']);
			}				
		elseif ($linha['evento_gestao_plano_gestao']){
			$sql->adTabela('plano_gestao_usuario');
			$sql->adCampo('DISTINCT plano_gestao_usuario_usuario');
			$sql->adOnde('plano_gestao_usuario_plano = '.(int)$linha['evento_gestao_plano_gestao']);
			}	
			
			
		elseif ($linha['evento_gestao_ssti']){
			$sql->adTabela('ssti_usuario');
			$sql->adCampo('DISTINCT ssti_usuario_usuario');
			$sql->adOnde('ssti_usuario_ssti = '.(int)$linha['evento_gestao_ssti']);
			}	
		elseif ($linha['evento_gestao_laudo']){
			$sql->adTabela('laudo_usuario');
			$sql->adCampo('DISTINCT laudo_usuario_usuario');
			$sql->adOnde('laudo_usuario_laudo = '.(int)$linha['evento_gestao_laudo']);
			}	
		elseif ($linha['evento_gestao_trelo']){
			$sql->adTabela('trelo_usuario');
			$sql->adCampo('DISTINCT trelo_usuario_usuario');
			$sql->adOnde('trelo_usuario_trelo = '.(int)$linha['evento_gestao_trelo']);
			}
		elseif ($linha['evento_gestao_trelo_cartao']){
			$sql->adTabela('trelo_cartao_usuario');
			$sql->adCampo('DISTINCT trelo_cartao_usuario_usuario');
			$sql->adOnde('trelo_cartao_usuario_trelo_cartao = '.(int)$linha['evento_gestao_trelo_cartao']);
			}
		elseif ($linha['evento_gestao_pdcl']){
			$sql->adTabela('pdcl_usuario');
			$sql->adCampo('DISTINCT pdcl_usuario_usuario');
			$sql->adOnde('pdcl_usuario_pdcl = '.(int)$linha['evento_gestao_pdcl']);
			}
		elseif ($linha['evento_gestao_pdcl_item']){
			$sql->adTabela('pdcl_item_usuario');
			$sql->adCampo('DISTINCT pdcl_item_usuario_usuario');
			$sql->adOnde('pdcl_item_usuario_item = '.(int)$linha['evento_gestao_pdcl_item']);
			}			
			
		$equipe=$sql->carregarColuna();
		$sql->limpar();
		foreach($equipe as $usuario_id) $designados[$usuario_id]=$usuario_id;
		}
	$designados=implode(',', $designados);

	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
	$sql->adOnde('usuario_id IN ('.($designados ? $designados : 0).')');	
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$usuarios = $sql->Lista();
	$sql->limpar();
	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) $saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode(nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '')).'</option>';
	$saida.='</select>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}		
	
$xajax->registerFunction("designados_ajax");	
	









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