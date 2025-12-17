<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

require_once (BASE_DIR.'/modulos/praticas/brainstorm.class.php');

$sql = new BDConsulta;
$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));


$brainstorm_id=getParam($_REQUEST, 'brainstorm_id', null);
$_REQUEST['brainstorm_ativo']=(isset($_REQUEST['brainstorm_ativo']) ? 1 : 0);


$Aplic->setMsg('Brainstorm');
$obj = new CBrainstorm();

if ($excluir) {
	$obj->load($brainstorm_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);

		$Aplic->redirecionar('m=praticas&a=brainstorm_lista');
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=brainstorm_lista');

		} 
	}

if ($brainstorm_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=brainstorm_lista');
	}



if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($brainstorm_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}

if ($dialogo){
	echo '<script type="text/javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	} 
else {
	
if ($Aplic->profissional){
	$sql->adTabela('brainstorm_gestao');
	$sql->adCampo('brainstorm_gestao.*');
	$sql->adOnde('brainstorm_gestao_brainstorm='.(int)$obj->brainstorm_id);
	$sql->adOrdem('brainstorm_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('brainstorm_gestao');
	$sql->adCampo('count(brainstorm_gestao_id)');
	$sql->adOnde('brainstorm_gestao_brainstorm='.(int)$obj->brainstorm_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['brainstorm_gestao_tarefa'] && $qnt==1 && !$brainstorm_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['brainstorm_gestao_tarefa'];
	elseif ($linha['brainstorm_gestao_projeto'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['brainstorm_gestao_projeto'];
	elseif ($linha['brainstorm_gestao_perspectiva'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['brainstorm_gestao_perspectiva'];
	elseif ($linha['brainstorm_gestao_tema'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['brainstorm_gestao_tema'];
	elseif ($linha['brainstorm_gestao_objetivo'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['brainstorm_gestao_objetivo'];
	elseif ($linha['brainstorm_gestao_fator'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['brainstorm_gestao_fator'];
	elseif ($linha['brainstorm_gestao_estrategia'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['brainstorm_gestao_estrategia'];
	elseif ($linha['brainstorm_gestao_meta'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['brainstorm_gestao_meta'];
	elseif ($linha['brainstorm_gestao_pratica'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['brainstorm_gestao_pratica'];
	elseif ($linha['brainstorm_gestao_indicador'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['brainstorm_gestao_indicador'];
	elseif ($linha['brainstorm_gestao_acao'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['brainstorm_gestao_acao'];
	elseif ($linha['brainstorm_gestao_canvas'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['brainstorm_gestao_canvas'];
	elseif ($linha['brainstorm_gestao_risco'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['brainstorm_gestao_risco'];
	elseif ($linha['brainstorm_gestao_risco_resposta'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['brainstorm_gestao_risco_resposta'];
	elseif ($linha['brainstorm_gestao_calendario'] && $qnt==1 && !$brainstorm_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['brainstorm_gestao_calendario'];
	elseif ($linha['brainstorm_gestao_monitoramento'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['brainstorm_gestao_monitoramento'];
	elseif ($linha['brainstorm_gestao_ata'] && $qnt==1 && !$brainstorm_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['brainstorm_gestao_ata'];
	elseif ($linha['brainstorm_gestao_mswot'] && $qnt==1 && !$brainstorm_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['brainstorm_gestao_mswot'];
	elseif ($linha['brainstorm_gestao_swot'] && $qnt==1 && !$brainstorm_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['brainstorm_gestao_swot'];
	elseif ($linha['brainstorm_gestao_operativo'] && $qnt==1 && !$brainstorm_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['brainstorm_gestao_operativo'];
	elseif ($linha['brainstorm_gestao_instrumento'] && $qnt==1 && !$brainstorm_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['brainstorm_gestao_instrumento'];
	elseif ($linha['brainstorm_gestao_recurso'] && $qnt==1 && !$brainstorm_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['brainstorm_gestao_recurso'];
	elseif ($linha['brainstorm_gestao_problema'] && $qnt==1 && !$brainstorm_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['brainstorm_gestao_problema'];
	elseif ($linha['brainstorm_gestao_demanda'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['brainstorm_gestao_demanda'];
	elseif ($linha['brainstorm_gestao_programa'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['brainstorm_gestao_programa'];
	elseif ($linha['brainstorm_gestao_licao'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['brainstorm_gestao_licao'];
	elseif ($linha['brainstorm_gestao_evento'] && $qnt==1 && !$brainstorm_id) $endereco='m=calendario&a=ver&evento_id='.$linha['brainstorm_gestao_evento'];
	elseif ($linha['brainstorm_gestao_link'] && $qnt==1 && !$brainstorm_id) $endereco='m=links&a=ver&link_id='.$linha['brainstorm_gestao_link'];
	elseif ($linha['brainstorm_gestao_avaliacao'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['brainstorm_gestao_avaliacao'];
	elseif ($linha['brainstorm_gestao_tgn'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['brainstorm_gestao_tgn'];
	
	elseif ($linha['brainstorm_gestao_semelhante'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['brainstorm_gestao_semelhante'];
	
	elseif ($linha['brainstorm_gestao_gut'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['brainstorm_gestao_gut'];
	elseif ($linha['brainstorm_gestao_causa_efeito'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['brainstorm_gestao_causa_efeito'];
	elseif ($linha['brainstorm_gestao_arquivo'] && $qnt==1 && !$brainstorm_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['brainstorm_gestao_arquivo'];
	elseif ($linha['brainstorm_gestao_forum'] && $qnt==1 && !$brainstorm_id) $endereco='m=foruns&a=ver&forum_id='.$linha['brainstorm_gestao_forum'];
	elseif ($linha['brainstorm_gestao_checklist'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['brainstorm_gestao_checklist'];
	elseif ($linha['brainstorm_gestao_agenda'] && $qnt==1 && !$brainstorm_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['brainstorm_gestao_agenda'];
	elseif ($linha['brainstorm_gestao_agrupamento'] && $qnt==1 && !$brainstorm_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['brainstorm_gestao_agrupamento'];
	elseif ($linha['brainstorm_gestao_patrocinador'] && $qnt==1 && !$brainstorm_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['brainstorm_gestao_patrocinador'];
	elseif ($linha['brainstorm_gestao_template'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['brainstorm_gestao_template'];
	elseif ($linha['brainstorm_gestao_painel'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['brainstorm_gestao_painel'];
	elseif ($linha['brainstorm_gestao_painel_odometro'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['brainstorm_gestao_painel_odometro'];
	elseif ($linha['brainstorm_gestao_painel_composicao'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['brainstorm_gestao_painel_composicao'];
	elseif ($linha['brainstorm_gestao_tr'] && $qnt==1 && !$brainstorm_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['brainstorm_gestao_tr'];
	elseif ($linha['brainstorm_gestao_me'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['brainstorm_gestao_me'];
	elseif ($linha['brainstorm_gestao_acao_item'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['brainstorm_gestao_acao_item'];
	elseif ($linha['brainstorm_gestao_beneficio'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['brainstorm_gestao_beneficio'];
	elseif ($linha['brainstorm_gestao_painel_slideshow'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['brainstorm_gestao_painel_slideshow'];
	elseif ($linha['brainstorm_gestao_projeto_viabilidade'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['brainstorm_gestao_projeto_viabilidade'];
	elseif ($linha['brainstorm_gestao_projeto_abertura'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['brainstorm_gestao_projeto_abertura'];
	elseif ($linha['brainstorm_gestao_plano_gestao'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['brainstorm_gestao_plano_gestao'];
	elseif ($linha['brainstorm_gestao_ssti'] && $qnt==1 && !$brainstorm_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['brainstorm_gestao_ssti'];
	elseif ($linha['brainstorm_gestao_laudo'] && $qnt==1 && !$brainstorm_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['brainstorm_gestao_laudo'];
	elseif ($linha['brainstorm_gestao_trelo'] && $qnt==1 && !$brainstorm_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['brainstorm_gestao_trelo'];
	elseif ($linha['brainstorm_gestao_trelo_cartao'] && $qnt==1 && !$brainstorm_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['brainstorm_gestao_trelo_cartao'];
	elseif ($linha['brainstorm_gestao_pdcl'] && $qnt==1 && !$brainstorm_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['brainstorm_gestao_pdcl'];
	elseif ($linha['brainstorm_gestao_pdcl_item'] && $qnt==1 && !$brainstorm_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['brainstorm_gestao_pdcl_item'];

	else $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.(int)$obj->brainstorm_id;
	$Aplic->redirecionar($endereco);
	}	 
else $Aplic->redirecionar('m=praticas&a=brainstorm_ver&brainstorm_id='.(int)$obj->brainstorm_id);	

	
	
}	

















$Aplic->redirecionar('m=praticas&a=brainstorm_ver&brainstorm_id='.$obj->brainstorm_id);

?>