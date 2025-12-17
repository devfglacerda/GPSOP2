<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$sql = new BDConsulta;

$_REQUEST['pratica_indicador_ativo']=(isset($_REQUEST['pratica_indicador_ativo']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_relevante']=(isset($_REQUEST['pratica_indicador_requisito_relevante']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_lider']=(isset($_REQUEST['pratica_indicador_requisito_lider']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_excelencia']=(isset($_REQUEST['pratica_indicador_requisito_excelencia']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_atendimento']=(isset($_REQUEST['pratica_indicador_requisito_atendimento']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_estrategico']=(isset($_REQUEST['pratica_indicador_requisito_estrategico']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_favoravel']=(isset($_REQUEST['pratica_indicador_requisito_favoravel']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_tendencia']=(isset($_REQUEST['pratica_indicador_requisito_tendencia']) ? 1 : 0);
$_REQUEST['pratica_indicador_requisito_superior']=(isset($_REQUEST['pratica_indicador_requisito_superior']) ? 1 : 0);
$_REQUEST['pratica_indicador_resultado']=(isset($_REQUEST['pratica_indicador_resultado']) ? 1 : 0);
$_REQUEST['pratica_indicador_mostrar_valor']=(isset($_REQUEST['pratica_indicador_mostrar_valor']) ? 1 : 0);
$_REQUEST['pratica_indicador_mostrar_titulo']=(isset($_REQUEST['pratica_indicador_mostrar_titulo']) ? 1 : 0);
$_REQUEST['pratica_indicador_media_movel']=(isset($_REQUEST['pratica_indicador_media_movel']) ? 1 : 0);
$_REQUEST['pratica_indicador_periodo_anterior']=(isset($_REQUEST['pratica_indicador_periodo_anterior']) ? 1 : 0);
$_REQUEST['pratica_indicador_max_min']=(isset($_REQUEST['pratica_indicador_max_min']) ? 1 : 0);
$_REQUEST['pratica_indicador_composicao']=(isset($_REQUEST['pratica_indicador_composicao']) ? 1 : 0);
$_REQUEST['pratica_indicador_formula']=(isset($_REQUEST['pratica_indicador_formula']) ? 1 : 0);
$_REQUEST['pratica_indicador_formula_simples']=(isset($_REQUEST['pratica_indicador_formula_simples']) && $Aplic->profissional ? 1 : 0);
$_REQUEST['pratica_indicador_formula_simples_variacao']=(isset($_REQUEST['pratica_indicador_formula_simples_variacao']) && $Aplic->profissional ? 1 : 0);
$_REQUEST['pratica_indicador_campo_projeto']=(isset($_REQUEST['pratica_indicador_campo_projeto']) && $Aplic->profissional ? 1 : 0);
$_REQUEST['pratica_indicador_campo_tarefa']=(isset($_REQUEST['pratica_indicador_campo_tarefa']) && $Aplic->profissional ? 1 : 0);
$_REQUEST['pratica_indicador_campo_acao']=(isset($_REQUEST['pratica_indicador_campo_acao']) && $Aplic->profissional ? 1 : 0);
$_REQUEST['pratica_indicador_checklist_valor']=(isset($_REQUEST['pratica_indicador_checklist_valor']) && $Aplic->profissional ? 1 : 0);

if (isset($_REQUEST['pratica_indicador_calculo'])) $_REQUEST['pratica_indicador_calculo']=strtoupper($_REQUEST['pratica_indicador_calculo']);

if (isset($_REQUEST['pratica_indicador_campo_projeto']) && isset($_REQUEST['pratica_indicador_campo_tarefa']) && isset($_REQUEST['pratica_indicador_campo_acao']) && ($_REQUEST['pratica_indicador_campo_projeto'] || $_REQUEST['pratica_indicador_campo_tarefa']  || $_REQUEST['pratica_indicador_campo_acao'])){
	$_REQUEST['pratica_indicador_acumulacao']='saldo';
	$_REQUEST['pratica_indicador_agrupar']='nenhum';
	}

$del = intval(getParam($_REQUEST, 'del', 0));
$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', null);

$obj = new CIndicador();
if ($pratica_indicador_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id);
	}
$Aplic->setMsg('Indicador');
if ($del) {
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=indicador_lista');
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=indicador_lista');
		}
	}

$codigo=$obj->getCodigo();
if ($codigo) $obj->pratica_indicador_codigo=$codigo;

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	
	$obj->notificar($_REQUEST);
	
	$Aplic->setMsg($pratica_indicador_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
if ($dialogo){
	echo '<script type="text/javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	} 	
else {
	$sql->adTabela('pratica_indicador_gestao');
	$sql->adCampo('pratica_indicador_gestao.*');
	$sql->adOnde('pratica_indicador_gestao_indicador='.(int)$obj->pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('pratica_indicador_gestao');
	$sql->adCampo('count(pratica_indicador_gestao_id)');
	$sql->adOnde('pratica_indicador_gestao_indicador='.(int)$obj->pratica_indicador_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['pratica_indicador_gestao_tarefa'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['pratica_indicador_gestao_tarefa'];
	elseif ($linha['pratica_indicador_gestao_projeto'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['pratica_indicador_gestao_projeto'];
	elseif ($linha['pratica_indicador_gestao_perspectiva'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['pratica_indicador_gestao_perspectiva'];
	elseif ($linha['pratica_indicador_gestao_tema'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['pratica_indicador_gestao_tema'];
	elseif ($linha['pratica_indicador_gestao_objetivo'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['pratica_indicador_gestao_objetivo'];
	elseif ($linha['pratica_indicador_gestao_fator'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['pratica_indicador_gestao_fator'];
	elseif ($linha['pratica_indicador_gestao_estrategia'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['pratica_indicador_gestao_estrategia'];
	elseif ($linha['pratica_indicador_gestao_meta'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['pratica_indicador_gestao_meta'];
	elseif ($linha['pratica_indicador_gestao_pratica'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['pratica_indicador_gestao_pratica'];
	
	elseif ($linha['pratica_indicador_gestao_semelhante'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['pratica_indicador_gestao_semelhante'];
	
	elseif ($linha['pratica_indicador_gestao_acao'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['pratica_indicador_gestao_acao'];
	elseif ($linha['pratica_indicador_gestao_canvas'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['pratica_indicador_gestao_canvas'];
	elseif ($linha['pratica_indicador_gestao_risco'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['pratica_indicador_gestao_risco'];
	elseif ($linha['pratica_indicador_gestao_risco_resposta'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['pratica_indicador_gestao_risco_resposta'];
	elseif ($linha['pratica_indicador_gestao_calendario'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['pratica_indicador_gestao_calendario'];
	elseif ($linha['pratica_indicador_gestao_monitoramento'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['pratica_indicador_gestao_monitoramento'];
	elseif ($linha['pratica_indicador_gestao_ata'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['pratica_indicador_gestao_ata'];
	elseif ($linha['pratica_indicador_gestao_mswot'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['pratica_indicador_gestao_mswot'];
	elseif ($linha['pratica_indicador_gestao_swot'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['pratica_indicador_gestao_swot'];
	elseif ($linha['pratica_indicador_gestao_operativo'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['pratica_indicador_gestao_operativo'];
	elseif ($linha['pratica_indicador_gestao_instrumento'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['pratica_indicador_gestao_instrumento'];
	elseif ($linha['pratica_indicador_gestao_recurso'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['pratica_indicador_gestao_recurso'];
	elseif ($linha['pratica_indicador_gestao_problema'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['pratica_indicador_gestao_problema'];
	elseif ($linha['pratica_indicador_gestao_demanda'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['pratica_indicador_gestao_demanda'];
	elseif ($linha['pratica_indicador_gestao_licao'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['pratica_indicador_gestao_licao'];
	elseif ($linha['pratica_indicador_gestao_programa'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['pratica_indicador_gestao_programa'];
	elseif ($linha['pratica_indicador_gestao_evento'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=calendario&a=ver&evento_id='.$linha['pratica_indicador_gestao_evento'];
	elseif ($linha['pratica_indicador_gestao_link'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=links&a=ver&link_id='.$linha['pratica_indicador_gestao_link'];
	elseif ($linha['pratica_indicador_gestao_avaliacao'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['pratica_indicador_gestao_avaliacao'];
	elseif ($linha['pratica_indicador_gestao_tgn'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['pratica_indicador_gestao_tgn'];
	elseif ($linha['pratica_indicador_gestao_brainstorm'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['pratica_indicador_gestao_brainstorm'];
	elseif ($linha['pratica_indicador_gestao_gut'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['pratica_indicador_gestao_gut'];
	elseif ($linha['pratica_indicador_gestao_causa_efeito'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['pratica_indicador_gestao_causa_efeito'];
	elseif ($linha['pratica_indicador_gestao_arquivo'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['pratica_indicador_gestao_arquivo'];
	elseif ($linha['pratica_indicador_gestao_forum'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=foruns&a=ver&forum_id='.$linha['pratica_indicador_gestao_forum'];
	elseif ($linha['pratica_indicador_gestao_checklist'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['pratica_indicador_gestao_checklist'];
	elseif ($linha['pratica_indicador_gestao_agenda'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['pratica_indicador_gestao_agenda'];
	elseif ($linha['pratica_indicador_gestao_agrupamento'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['pratica_indicador_gestao_agrupamento'];
	elseif ($linha['pratica_indicador_gestao_patrocinador'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['pratica_indicador_gestao_patrocinador'];
	elseif ($linha['pratica_indicador_gestao_template'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['pratica_indicador_gestao_template'];
	elseif ($linha['pratica_indicador_gestao_painel'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['pratica_indicador_gestao_painel'];
	elseif ($linha['pratica_indicador_gestao_painel_odometro'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['pratica_indicador_gestao_painel_odometro'];
	elseif ($linha['pratica_indicador_gestao_painel_composicao'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['pratica_indicador_gestao_painel_composicao'];
	elseif ($linha['pratica_indicador_gestao_tr'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['pratica_indicador_gestao_tr'];
	elseif ($linha['pratica_indicador_gestao_me'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['pratica_indicador_gestao_me'];
	elseif ($linha['pratica_indicador_gestao_acao_item'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['pratica_indicador_gestao_acao_item'];
	elseif ($linha['pratica_indicador_gestao_beneficio'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['pratica_indicador_gestao_beneficio'];
	elseif ($linha['pratica_indicador_gestao_painel_slideshow'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['pratica_indicador_gestao_painel_slideshow'];
	elseif ($linha['pratica_indicador_gestao_projeto_viabilidade'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['pratica_indicador_gestao_projeto_viabilidade'];
	elseif ($linha['pratica_indicador_gestao_projeto_abertura'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['pratica_indicador_gestao_projeto_abertura'];
	elseif ($linha['pratica_indicador_gestao_plano_gestao'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=praticas&u=gestao&a=menu&pg_id='.$linha['pratica_indicador_gestao_plano_gestao'];
	elseif ($linha['pratica_indicador_gestao_ssti'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['pratica_indicador_gestao_ssti'];
	elseif ($linha['pratica_indicador_gestao_laudo'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['pratica_indicador_gestao_laudo'];
	elseif ($linha['pratica_indicador_gestao_trelo'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['pratica_indicador_gestao_trelo'];
	elseif ($linha['pratica_indicador_gestao_trelo_cartao'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['pratica_indicador_gestao_trelo_cartao'];
	elseif ($linha['pratica_indicador_gestao_pdcl'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['pratica_indicador_gestao_pdcl'];
	elseif ($linha['pratica_indicador_gestao_pdcl_item'] && $qnt==1 && !$pratica_indicador_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['pratica_indicador_gestao_pdcl_item'];
	else $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.(int)$obj->pratica_indicador_id;
	$Aplic->redirecionar($endereco);

	}
?>