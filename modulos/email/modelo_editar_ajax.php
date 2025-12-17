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
	
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/email/modelo_editar_ajax_pro.php';	
	
function protocolo_dept_ajax($dept_id=0){
	global $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	
	$sql->adCampo('dept_nup, dept_qnt_nr, dept_prefixo, dept_sufixo');
	$sql->adOnde('dept_id='.(int)$dept_id);	
	$dept = $sql->Linha();
	$sql->limpar();
	$anos=array();
	for($i=(int)$dept['dept_qnt_nr']+1; $i < (int)$dept['dept_qnt_nr']+30; $i++) $anos[$i]=$i; 

	$saida=$dept['dept_prefixo'].selecionaVetor($anos, 'dept_qnt_nr', 'class="texto"',$dept['dept_qnt_nr']+1).$dept['dept_sufixo'];

	$objResposta = new xajaxResponse();
	$objResposta->assign('protocolo_secao',"innerHTML", $saida);
	return $objResposta;
	}	
	
$xajax->registerFunction("protocolo_dept_ajax");


function mudar_posicao_gestao($ordem, $modelo_gestao_id, $direcao, $modelo_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $modelo_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('modelo_gestao');
		$sql->adOnde('modelo_gestao_id != '.(int)$modelo_gestao_id);
		if ($uuid) $sql->adOnde('modelo_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('modelo_gestao_modelo = '.(int)$modelo_id);
		$sql->adOrdem('modelo_gestao_ordem');
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
			$sql->adTabela('modelo_gestao');
			$sql->adAtualizar('modelo_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('modelo_gestao_id = '.(int)$modelo_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('modelo_gestao');
					$sql->adAtualizar('modelo_gestao_ordem', $idx);
					$sql->adOnde('modelo_gestao_id = '.(int)$acao['modelo_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('modelo_gestao');
					$sql->adAtualizar('modelo_gestao_ordem', $idx + 1);
					$sql->adOnde('modelo_gestao_id = '.(int)$acao['modelo_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($modelo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$modelo_id=0, 
	$uuid='',  
	
	$modelo_projeto=null,
	$modelo_tarefa=null,
	$modelo_perspectiva=null,
	$modelo_tema=null,
	$modelo_objetivo=null,
	$modelo_fator=null,
	$modelo_estrategia=null,
	$modelo_meta=null,
	$modelo_pratica=null,
	$modelo_acao=null,
	$modelo_canvas=null,
	$modelo_risco=null,
	$modelo_risco_resposta=null,
	$modelo_indicador=null,
	$modelo_calendario=null,
	$modelo_monitoramento=null,
	$modelo_ata=null,
	$modelo_mswot=null,
	$modelo_swot=null,
	$modelo_operativo=null,
	$modelo_instrumento=null,
	$modelo_recurso=null,
	$modelo_problema=null,
	$modelo_demanda=null,
	$modelo_programa=null,
	$modelo_licao=null,
	$modelo_evento=null,
	$modelo_link=null,
	$modelo_avaliacao=null,
	$modelo_tgn=null,
	$modelo_brainstorm=null,
	$modelo_gut=null,
	$modelo_causa_efeito=null,
	$modelo_arquivo=null,
	$modelo_forum=null,
	$modelo_checklist=null,
	$modelo_agenda=null,
	$modelo_agrupamento=null,
	$modelo_patrocinador=null,
	$modelo_template=null,
	$modelo_painel=null,
	$modelo_painel_odometro=null,
	$modelo_painel_composicao=null,
	$modelo_tr=null,
	$modelo_me=null,
	$modelo_acao_item=null,
	$modelo_beneficio=null,
	$modelo_painel_slideshow=null,
	$modelo_projeto_viabilidade=null,
	$modelo_projeto_abertura=null,
	$modelo_plano_gestao=null,
	$modelo_ssti=null,
	$modelo_laudo=null,
	$modelo_trelo=null,
	$modelo_trelo_cartao=null,
	$modelo_pdcl=null,
	$modelo_pdcl_item=null
	)
	{
	if (
		$modelo_projeto || 
		$modelo_tarefa || 
		$modelo_perspectiva || 
		$modelo_tema || 
		$modelo_objetivo || 
		$modelo_fator || 
		$modelo_estrategia || 
		$modelo_meta || 
		$modelo_pratica || 
		$modelo_acao || 
		$modelo_canvas || 
		$modelo_risco || 
		$modelo_risco_resposta || 
		$modelo_indicador || 
		$modelo_calendario || 
		$modelo_monitoramento || 
		$modelo_ata || 
		$modelo_mswot || 
		$modelo_swot || 
		$modelo_operativo || 
		$modelo_instrumento || 
		$modelo_recurso || 
		$modelo_problema || 
		$modelo_demanda || 
		$modelo_programa || 
		$modelo_licao || 
		$modelo_evento || 
		$modelo_link || 
		$modelo_avaliacao || 
		$modelo_tgn || 
		$modelo_brainstorm || 
		$modelo_gut || 
		$modelo_causa_efeito || 
		$modelo_arquivo || 
		$modelo_forum || 
		$modelo_checklist || 
		$modelo_agenda || 
		$modelo_agrupamento || 
		$modelo_patrocinador || 
		$modelo_template || 
		$modelo_painel || 
		$modelo_painel_odometro || 
		$modelo_painel_composicao || 
		$modelo_tr || 
		$modelo_me || 
		$modelo_acao_item || 
		$modelo_beneficio || 
		$modelo_painel_slideshow || 
		$modelo_projeto_viabilidade || 
		$modelo_projeto_abertura || 
		$modelo_plano_gestao|| 
		$modelo_ssti || 
		$modelo_laudo || 
		$modelo_trelo || 
		$modelo_trelo_cartao || 
		$modelo_pdcl || 
		$modelo_pdcl_item
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		
		if (!$Aplic->profissional) {
			$sql->setExcluir('modelo_gestao');
			if ($uuid) $sql->adOnde('modelo_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('modelo_gestao_modelo ='.(int)$modelo_id);	
			$sql->exec();
			}
		
		$sql->adTabela('modelo_gestao');
		$sql->adCampo('count(modelo_gestao_id)');
		if ($uuid) $sql->adOnde('modelo_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('modelo_gestao_modelo ='.(int)$modelo_id);	
		if ($modelo_tarefa) $sql->adOnde('modelo_gestao_tarefa='.(int)$modelo_tarefa);
		elseif ($modelo_projeto) $sql->adOnde('modelo_gestao_projeto='.(int)$modelo_projeto);
		elseif ($modelo_perspectiva) $sql->adOnde('modelo_gestao_perspectiva='.(int)$modelo_perspectiva);
		elseif ($modelo_tema) $sql->adOnde('modelo_gestao_tema='.(int)$modelo_tema);
		elseif ($modelo_objetivo) $sql->adOnde('modelo_gestao_objetivo='.(int)$modelo_objetivo);
		elseif ($modelo_fator) $sql->adOnde('modelo_gestao_fator='.(int)$modelo_fator);
		elseif ($modelo_estrategia) $sql->adOnde('modelo_gestao_estrategia='.(int)$modelo_estrategia);
		elseif ($modelo_acao) $sql->adOnde('modelo_gestao_acao='.(int)$modelo_acao);
		elseif ($modelo_pratica) $sql->adOnde('modelo_gestao_pratica='.(int)$modelo_pratica);
		elseif ($modelo_meta) $sql->adOnde('modelo_gestao_meta='.(int)$modelo_meta);
		elseif ($modelo_canvas) $sql->adOnde('modelo_gestao_canvas='.(int)$modelo_canvas);
		elseif ($modelo_risco) $sql->adOnde('modelo_gestao_risco='.(int)$modelo_risco);
		elseif ($modelo_risco_resposta) $sql->adOnde('modelo_gestao_risco_resposta='.(int)$modelo_risco_resposta);
		elseif ($modelo_indicador) $sql->adOnde('modelo_gestao_indicador='.(int)$modelo_indicador);
		elseif ($modelo_calendario) $sql->adOnde('modelo_gestao_calendario='.(int)$modelo_calendario);
		elseif ($modelo_monitoramento) $sql->adOnde('modelo_gestao_monitoramento='.(int)$modelo_monitoramento);
		elseif ($modelo_ata) $sql->adOnde('modelo_gestao_ata='.(int)$modelo_ata);
		elseif ($modelo_mswot) $sql->adOnde('modelo_gestao_mswot='.(int)$modelo_mswot);
		elseif ($modelo_swot) $sql->adOnde('modelo_gestao_swot='.(int)$modelo_swot);
		elseif ($modelo_operativo) $sql->adOnde('modelo_gestao_operativo='.(int)$modelo_operativo);
		elseif ($modelo_instrumento) $sql->adOnde('modelo_gestao_instrumento='.(int)$modelo_instrumento);
		elseif ($modelo_recurso) $sql->adOnde('modelo_gestao_recurso='.(int)$modelo_recurso);
		elseif ($modelo_problema) $sql->adOnde('modelo_gestao_problema='.(int)$modelo_problema);
		elseif ($modelo_demanda) $sql->adOnde('modelo_gestao_demanda='.(int)$modelo_demanda);
		elseif ($modelo_programa) $sql->adOnde('modelo_gestao_programa='.(int)$modelo_programa);
		elseif ($modelo_licao) $sql->adOnde('modelo_gestao_licao='.(int)$modelo_licao);
		elseif ($modelo_evento) $sql->adOnde('modelo_gestao_evento='.(int)$modelo_evento);
		elseif ($modelo_link) $sql->adOnde('modelo_gestao_link='.(int)$modelo_link);
		elseif ($modelo_avaliacao) $sql->adOnde('modelo_gestao_avaliacao='.(int)$modelo_avaliacao);
		elseif ($modelo_tgn) $sql->adOnde('modelo_gestao_tgn='.(int)$modelo_tgn);
		elseif ($modelo_brainstorm) $sql->adOnde('modelo_gestao_brainstorm='.(int)$modelo_brainstorm);
		elseif ($modelo_gut) $sql->adOnde('modelo_gestao_gut='.(int)$modelo_gut);
		elseif ($modelo_causa_efeito) $sql->adOnde('modelo_gestao_causa_efeito='.(int)$modelo_causa_efeito);
		elseif ($modelo_arquivo) $sql->adOnde('modelo_gestao_arquivo='.(int)$modelo_arquivo);
		elseif ($modelo_forum) $sql->adOnde('modelo_gestao_forum='.(int)$modelo_forum);
		elseif ($modelo_checklist) $sql->adOnde('modelo_gestao_checklist='.(int)$modelo_checklist);
		elseif ($modelo_agenda) $sql->adOnde('modelo_gestao_agenda='.(int)$modelo_agenda);
		elseif ($modelo_agrupamento) $sql->adOnde('modelo_gestao_agrupamento='.(int)$modelo_agrupamento);
		elseif ($modelo_patrocinador) $sql->adOnde('modelo_gestao_patrocinador='.(int)$modelo_patrocinador);
		elseif ($modelo_template) $sql->adOnde('modelo_gestao_template='.(int)$modelo_template);
		elseif ($modelo_painel) $sql->adOnde('modelo_gestao_painel='.(int)$modelo_painel);
		elseif ($modelo_painel_odometro) $sql->adOnde('modelo_gestao_painel_odometro='.(int)$modelo_painel_odometro);
		elseif ($modelo_painel_composicao) $sql->adOnde('modelo_gestao_painel_composicao='.(int)$modelo_painel_composicao);
		elseif ($modelo_tr) $sql->adOnde('modelo_gestao_tr='.(int)$modelo_tr);
		elseif ($modelo_me) $sql->adOnde('modelo_gestao_me='.(int)$modelo_me);
		elseif ($modelo_acao_item) $sql->adOnde('modelo_gestao_acao_item='.(int)$modelo_acao_item);
		elseif ($modelo_beneficio) $sql->adOnde('modelo_gestao_beneficio='.(int)$modelo_beneficio);
		elseif ($modelo_painel_slideshow) $sql->adOnde('modelo_gestao_painel_slideshow='.(int)$modelo_painel_slideshow);
		elseif ($modelo_projeto_viabilidade) $sql->adOnde('modelo_gestao_projeto_viabilidade='.(int)$modelo_projeto_viabilidade);
		elseif ($modelo_projeto_abertura) $sql->adOnde('modelo_gestao_projeto_abertura='.(int)$modelo_projeto_abertura);
		elseif ($modelo_plano_gestao) $sql->adOnde('modelo_gestao_plano_gestao='.(int)$modelo_plano_gestao);
		elseif ($modelo_ssti) $sql->adOnde('modelo_gestao_ssti='.(int)$modelo_ssti);
		elseif ($modelo_laudo) $sql->adOnde('modelo_gestao_laudo='.(int)$modelo_laudo);
		elseif ($modelo_trelo) $sql->adOnde('modelo_gestao_trelo='.(int)$modelo_trelo);
		elseif ($modelo_trelo_cartao) $sql->adOnde('modelo_gestao_trelo_cartao='.(int)$modelo_trelo_cartao);
		elseif ($modelo_pdcl) $sql->adOnde('modelo_gestao_pdcl='.(int)$modelo_pdcl);
		elseif ($modelo_pdcl_item) $sql->adOnde('modelo_gestao_pdcl_item='.(int)$modelo_pdcl_item);
		
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('modelo_gestao');
			$sql->adCampo('MAX(modelo_gestao_ordem)');
			if ($uuid) $sql->adOnde('modelo_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('modelo_gestao_modelo ='.(int)$modelo_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('modelo_gestao');
			if ($uuid) $sql->adInserir('modelo_gestao_uuid', $uuid);
			else $sql->adInserir('modelo_gestao_modelo', (int)$modelo_id);
			
			if ($modelo_tarefa) $sql->adInserir('modelo_gestao_tarefa', (int)$modelo_tarefa);
			if ($modelo_projeto) $sql->adInserir('modelo_gestao_projeto', (int)$modelo_projeto);
			elseif ($modelo_perspectiva) $sql->adInserir('modelo_gestao_perspectiva', (int)$modelo_perspectiva);
			elseif ($modelo_tema) $sql->adInserir('modelo_gestao_tema', (int)$modelo_tema);
			elseif ($modelo_objetivo) $sql->adInserir('modelo_gestao_objetivo', (int)$modelo_objetivo);
			elseif ($modelo_fator) $sql->adInserir('modelo_gestao_fator', (int)$modelo_fator);
			elseif ($modelo_estrategia) $sql->adInserir('modelo_gestao_estrategia', (int)$modelo_estrategia);
			elseif ($modelo_acao) $sql->adInserir('modelo_gestao_acao', (int)$modelo_acao);
			elseif ($modelo_pratica) $sql->adInserir('modelo_gestao_pratica', (int)$modelo_pratica);
			elseif ($modelo_meta) $sql->adInserir('modelo_gestao_meta', (int)$modelo_meta);
			elseif ($modelo_canvas) $sql->adInserir('modelo_gestao_canvas', (int)$modelo_canvas);
			elseif ($modelo_risco) $sql->adInserir('modelo_gestao_risco', (int)$modelo_risco);
			elseif ($modelo_risco_resposta) $sql->adInserir('modelo_gestao_risco_resposta', (int)$modelo_risco_resposta);
			elseif ($modelo_indicador) $sql->adInserir('modelo_gestao_indicador', (int)$modelo_indicador);
			elseif ($modelo_calendario) $sql->adInserir('modelo_gestao_calendario', (int)$modelo_calendario);
			elseif ($modelo_monitoramento) $sql->adInserir('modelo_gestao_monitoramento', (int)$modelo_monitoramento);
			elseif ($modelo_ata) $sql->adInserir('modelo_gestao_ata', (int)$modelo_ata);
			elseif ($modelo_mswot) $sql->adInserir('modelo_gestao_mswot', (int)$modelo_mswot);
			elseif ($modelo_swot) $sql->adInserir('modelo_gestao_swot', (int)$modelo_swot);
			elseif ($modelo_operativo) $sql->adInserir('modelo_gestao_operativo', (int)$modelo_operativo);
			elseif ($modelo_instrumento) $sql->adInserir('modelo_gestao_instrumento', (int)$modelo_instrumento);
			elseif ($modelo_recurso) $sql->adInserir('modelo_gestao_recurso', (int)$modelo_recurso);
			elseif ($modelo_problema) $sql->adInserir('modelo_gestao_problema', (int)$modelo_problema);
			elseif ($modelo_demanda) $sql->adInserir('modelo_gestao_demanda', (int)$modelo_demanda);
			elseif ($modelo_programa) $sql->adInserir('modelo_gestao_programa', (int)$modelo_programa);
			elseif ($modelo_licao) $sql->adInserir('modelo_gestao_licao', (int)$modelo_licao);
			elseif ($modelo_evento) $sql->adInserir('modelo_gestao_evento', (int)$modelo_evento);
			elseif ($modelo_link) $sql->adInserir('modelo_gestao_link', (int)$modelo_link);
			elseif ($modelo_avaliacao) $sql->adInserir('modelo_gestao_avaliacao', (int)$modelo_avaliacao);
			elseif ($modelo_tgn) $sql->adInserir('modelo_gestao_tgn', (int)$modelo_tgn);
			elseif ($modelo_brainstorm) $sql->adInserir('modelo_gestao_brainstorm', (int)$modelo_brainstorm);
			elseif ($modelo_gut) $sql->adInserir('modelo_gestao_gut', (int)$modelo_gut);
			elseif ($modelo_causa_efeito) $sql->adInserir('modelo_gestao_causa_efeito', (int)$modelo_causa_efeito);
			elseif ($modelo_arquivo) $sql->adInserir('modelo_gestao_arquivo', (int)$modelo_arquivo);
			elseif ($modelo_forum) $sql->adInserir('modelo_gestao_forum', (int)$modelo_forum);
			elseif ($modelo_checklist) $sql->adInserir('modelo_gestao_checklist', (int)$modelo_checklist);
			elseif ($modelo_agenda) $sql->adInserir('modelo_gestao_agenda', (int)$modelo_agenda);
			elseif ($modelo_agrupamento) $sql->adInserir('modelo_gestao_agrupamento', (int)$modelo_agrupamento);
			elseif ($modelo_patrocinador) $sql->adInserir('modelo_gestao_patrocinador', (int)$modelo_patrocinador);
			elseif ($modelo_template) $sql->adInserir('modelo_gestao_template', (int)$modelo_template);
			elseif ($modelo_painel) $sql->adInserir('modelo_gestao_painel', (int)$modelo_painel);
			elseif ($modelo_painel_odometro) $sql->adInserir('modelo_gestao_painel_odometro', (int)$modelo_painel_odometro);
			elseif ($modelo_painel_composicao) $sql->adInserir('modelo_gestao_painel_composicao', (int)$modelo_painel_composicao);
			elseif ($modelo_tr) $sql->adInserir('modelo_gestao_tr', (int)$modelo_tr);
			elseif ($modelo_me) $sql->adInserir('modelo_gestao_me', (int)$modelo_me);
			elseif ($modelo_acao_item) $sql->adInserir('modelo_gestao_acao_item', (int)$modelo_acao_item);
			elseif ($modelo_beneficio) $sql->adInserir('modelo_gestao_beneficio', (int)$modelo_beneficio);
			elseif ($modelo_painel_slideshow) $sql->adInserir('modelo_gestao_painel_slideshow', (int)$modelo_painel_slideshow);
			elseif ($modelo_projeto_viabilidade) $sql->adInserir('modelo_gestao_projeto_viabilidade', (int)$modelo_projeto_viabilidade);
			elseif ($modelo_projeto_abertura) $sql->adInserir('modelo_gestao_projeto_abertura', (int)$modelo_projeto_abertura);
			elseif ($modelo_plano_gestao) $sql->adInserir('modelo_gestao_plano_gestao', (int)$modelo_plano_gestao);
			elseif ($modelo_ssti) $sql->adInserir('modelo_gestao_ssti', (int)$modelo_ssti);
			elseif ($modelo_laudo) $sql->adInserir('modelo_gestao_laudo', (int)$modelo_laudo);
			elseif ($modelo_trelo) $sql->adInserir('modelo_gestao_trelo', (int)$modelo_trelo);
			elseif ($modelo_trelo_cartao) $sql->adInserir('modelo_gestao_trelo_cartao', (int)$modelo_trelo_cartao);
			elseif ($modelo_pdcl) $sql->adInserir('modelo_gestao_pdcl', (int)$modelo_pdcl);
			elseif ($modelo_pdcl_item) $sql->adInserir('modelo_gestao_pdcl_item', (int)$modelo_pdcl_item);
	
			$sql->adInserir('modelo_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($modelo_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($modelo_id=0, $uuid='', $modelo_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('modelo_gestao');
	$sql->adOnde('modelo_gestao_id='.(int)$modelo_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($modelo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($modelo_id=0, $uuid=''){	
	$saida=atualizar_gestao($modelo_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($modelo_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('modelo_gestao');
	$sql->adCampo('modelo_gestao.*');
	if ($uuid) $sql->adOnde('modelo_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('modelo_gestao_modelo ='.(int)$modelo_id);	
	$sql->adOrdem('modelo_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['modelo_gestao_ordem'].', '.$gestao_data['modelo_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['modelo_gestao_ordem'].', '.$gestao_data['modelo_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['modelo_gestao_ordem'].', '.$gestao_data['modelo_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['modelo_gestao_ordem'].', '.$gestao_data['modelo_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['modelo_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['modelo_gestao_tarefa']).'</td>';
		elseif ($gestao_data['modelo_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['modelo_gestao_projeto']).'</td>';
		elseif ($gestao_data['modelo_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['modelo_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['modelo_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['modelo_gestao_tema']).'</td>';
		elseif ($gestao_data['modelo_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['modelo_gestao_objetivo']).'</td>';
		elseif ($gestao_data['modelo_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['modelo_gestao_fator']).'</td>';
		elseif ($gestao_data['modelo_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['modelo_gestao_estrategia']).'</td>';
		elseif ($gestao_data['modelo_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['modelo_gestao_meta']).'</td>';
		elseif ($gestao_data['modelo_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['modelo_gestao_pratica']).'</td>';
		elseif ($gestao_data['modelo_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['modelo_gestao_acao']).'</td>';
		elseif ($gestao_data['modelo_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['modelo_gestao_canvas']).'</td>';
		elseif ($gestao_data['modelo_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['modelo_gestao_risco']).'</td>';
		elseif ($gestao_data['modelo_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['modelo_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['modelo_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['modelo_gestao_indicador']).'</td>';
		elseif ($gestao_data['modelo_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['modelo_gestao_calendario']).'</td>';
		elseif ($gestao_data['modelo_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['modelo_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['modelo_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['modelo_gestao_ata']).'</td>';
		elseif ($gestao_data['modelo_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['modelo_gestao_mswot']).'</td>';
		elseif ($gestao_data['modelo_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['modelo_gestao_swot']).'</td>';
		elseif ($gestao_data['modelo_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['modelo_gestao_operativo']).'</td>';
		elseif ($gestao_data['modelo_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['modelo_gestao_instrumento']).'</td>';
		elseif ($gestao_data['modelo_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['modelo_gestao_recurso']).'</td>';
		elseif ($gestao_data['modelo_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['modelo_gestao_problema']).'</td>';
		elseif ($gestao_data['modelo_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['modelo_gestao_demanda']).'</td>';
		elseif ($gestao_data['modelo_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['modelo_gestao_programa']).'</td>';
		elseif ($gestao_data['modelo_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['modelo_gestao_licao']).'</td>';
		elseif ($gestao_data['modelo_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['modelo_gestao_evento']).'</td>';
		elseif ($gestao_data['modelo_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['modelo_gestao_link']).'</td>';
		elseif ($gestao_data['modelo_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['modelo_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['modelo_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['modelo_gestao_tgn']).'</td>';
		elseif ($gestao_data['modelo_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['modelo_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['modelo_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['modelo_gestao_gut']).'</td>';
		elseif ($gestao_data['modelo_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['modelo_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['modelo_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['modelo_gestao_arquivo']).'</td>';
		elseif ($gestao_data['modelo_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['modelo_gestao_forum']).'</td>';
		elseif ($gestao_data['modelo_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['modelo_gestao_checklist']).'</td>';
		elseif ($gestao_data['modelo_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['modelo_gestao_agenda']).'</td>';
		elseif ($gestao_data['modelo_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['modelo_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['modelo_gestao_patrocinador']) $saida.= '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['modelo_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['modelo_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['modelo_gestao_template']).'</td>';
		elseif ($gestao_data['modelo_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['modelo_gestao_painel']).'</td>';
		elseif ($gestao_data['modelo_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['modelo_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['modelo_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['modelo_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['modelo_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['modelo_gestao_tr']).'</td>';	
		elseif ($gestao_data['modelo_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['modelo_gestao_me']).'</td>';	
		elseif ($gestao_data['modelo_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['modelo_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['modelo_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['modelo_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['modelo_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['modelo_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['modelo_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['modelo_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['modelo_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['modelo_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['modelo_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['modelo_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['modelo_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['modelo_gestao_ssti']).'</td>';
		elseif ($gestao_data['modelo_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['modelo_gestao_laudo']).'</td>';
		elseif ($gestao_data['modelo_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['modelo_gestao_trelo']).'</td>';
		elseif ($gestao_data['modelo_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['modelo_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['modelo_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['modelo_gestao_pdcl']).'</td>';
		elseif ($gestao_data['modelo_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['modelo_gestao_pdcl_item']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['modelo_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}		




$xajax->processRequest();

?>