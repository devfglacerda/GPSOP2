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


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $bd;


$arquivo_id = intval(getParam($_REQUEST, 'arquivo_id', 0));
$del = intval(getParam($_REQUEST, 'del', 0));
$duplicar = intval(getParam($_REQUEST, 'duplicar', 0));



$sql = new BDConsulta;

$nao_eh_novo=getParam($_REQUEST, 'arquivo_id', null);

if (!$arquivo_id &&!$Aplic->checarModulo('arquivos', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($arquivo_id &&!$Aplic->checarModulo('arquivos', 'editar')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$obj = new CArquivo();
if ($arquivo_id) {
	$obj->_mensagem = 'atualizado';
	$antigoObj = new CArquivo();
	$antigoObj->load($arquivo_id);

	//Se foi inserido um novo arquivo gravar histórico do antigo
	if(isset($_FILES['arquivo']['size']) && $_FILES['arquivo']['size'] > 0){
		$sql->adTabela('arquivo_historico');
		foreach(get_class_vars('CArquivo') as $chave => $valor_inutil)	if (substr($chave, 0, 1)!='_' && $antigoObj->{$chave}!='') $sql->adInserir($chave, $antigoObj->{$chave});
		$sql->exec();
		$sql->limpar();
		}	
	} 
else $obj->_mensagem = 'adicionado';



$obj->arquivo_categoria = intval(getParam($_REQUEST, 'arquivo_categoria', 0));

if (isset($_REQUEST['arquivo_versao'])) $_REQUEST['arquivo_versao']=float_americano($_REQUEST['arquivo_versao']);

$versao=getParam($_REQUEST, 'arquivo_versao', 0);

$revisao_tipo=getParam($_REQUEST, 'revision_tipo', 0);
if (strcasecmp('major', $revisao_tipo) == 0) {
	$maior_num = strtok($versao, '.') + 1;
	$_REQUEST['arquivo_versao'] = $maior_num;
	}
if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=arquivos');
	}
	
$Aplic->setMsg('Arquivo');
if ($duplicar) {
	$obj->load($arquivo_id);
	$novo_arquivo = new CArquivo();
	$novo_arquivo = $obj->duplicar();
	$novo_arquivo->arquivo_pasta = null;
	if (!($dup_nome_real = $obj->duplicarArquivo($obj->arquivo_projeto, $obj->arquivo_nome_real))) {
		$Aplic->setMsg('Não foi possível duplicar o arquivo, verifique as permissões de arquivo', UI_MSG_ERRO);
		} 
	else {
		$novo_arquivo->arquivo_nome_real = $dup_nome_real;
		$novo_arquivo->arquivo_data = date('Y-m-d H:i:s');
		if (($msg = $novo_arquivo->armazenar())) {
			$Aplic->setMsg($msg, UI_MSG_ERRO);
			} 
		else {
			$Aplic->setMsg('duplicado', UI_MSG_OK, true);
			}
		}
	if ($dialogo){
		echo '<script type="text/javascript">';
		echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
		echo 'else self.close();';
		echo '</script>';	
		} 
	else{
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('arquivo_gestao.*');
		$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
		$sql->adOrdem('arquivo_gestao_ordem ASC');
		$linha=$sql->linha();
		$sql->limpar();
		
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('count(arquivo_gestao_id)');
		$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
		$qnt=$sql->Resultado();
		$sql->limpar();
		
		if ($linha['arquivo_gestao_tarefa'] && $qnt==1 && !$arquivo_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['arquivo_gestao_tarefa'];
		elseif ($linha['arquivo_gestao_projeto'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['arquivo_gestao_projeto'];
		elseif ($linha['arquivo_gestao_perspectiva'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['arquivo_gestao_perspectiva'];
		elseif ($linha['arquivo_gestao_tema'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['arquivo_gestao_tema'];
		elseif ($linha['arquivo_gestao_objetivo'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['arquivo_gestao_objetivo'];
		elseif ($linha['arquivo_gestao_fator'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['arquivo_gestao_fator'];
		elseif ($linha['arquivo_gestao_estrategia'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['arquivo_gestao_estrategia'];
		elseif ($linha['arquivo_gestao_meta'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['arquivo_gestao_meta'];
		elseif ($linha['arquivo_gestao_pratica'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['arquivo_gestao_pratica'];
		elseif ($linha['arquivo_gestao_indicador'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['arquivo_gestao_indicador'];
		elseif ($linha['arquivo_gestao_acao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['arquivo_gestao_acao'];
		elseif ($linha['arquivo_gestao_canvas'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['arquivo_gestao_canvas'];
		elseif ($linha['arquivo_gestao_risco'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['arquivo_gestao_risco'];
		elseif ($linha['arquivo_gestao_risco_resposta'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['arquivo_gestao_risco_resposta'];
		elseif ($linha['arquivo_gestao_calendario'] && $qnt==1 && !$arquivo_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['arquivo_gestao_calendario'];
		elseif ($linha['arquivo_gestao_monitoramento'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['arquivo_gestao_monitoramento'];
		elseif ($linha['arquivo_gestao_ata'] && $qnt==1 && !$arquivo_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['arquivo_gestao_ata'];
		elseif ($linha['arquivo_gestao_mswot'] && $qnt==1 && !$arquivo_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['arquivo_gestao_mswot'];
		elseif ($linha['arquivo_gestao_swot'] && $qnt==1 && !$arquivo_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['arquivo_gestao_swot'];
		elseif ($linha['arquivo_gestao_operativo'] && $qnt==1 && !$arquivo_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_operativo'];
		elseif ($linha['arquivo_gestao_instrumento'] && $qnt==1 && !$arquivo_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['arquivo_gestao_instrumento'];
		elseif ($linha['arquivo_gestao_recurso'] && $qnt==1 && !$arquivo_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['arquivo_gestao_recurso'];
		elseif ($linha['arquivo_gestao_problema'] && $qnt==1 && !$arquivo_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['arquivo_gestao_problema'];
		elseif ($linha['arquivo_gestao_demanda'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['arquivo_gestao_demanda'];
		elseif ($linha['arquivo_gestao_programa'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['arquivo_gestao_programa'];
		elseif ($linha['arquivo_gestao_licao'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['arquivo_gestao_licao'];
		elseif ($linha['arquivo_gestao_evento'] && $qnt==1 && !$arquivo_id) $endereco='m=calendario&a=ver&evento_id='.$linha['arquivo_gestao_evento'];
		elseif ($linha['arquivo_gestao_link'] && $qnt==1 && !$arquivo_id) $endereco='m=links&a=ver&link_id='.$linha['arquivo_gestao_link'];
		elseif ($linha['arquivo_gestao_avaliacao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['arquivo_gestao_avaliacao'];
		elseif ($linha['arquivo_gestao_tgn'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['arquivo_gestao_tgn'];
		elseif ($linha['arquivo_gestao_brainstorm'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['arquivo_gestao_brainstorm'];
		elseif ($linha['arquivo_gestao_gut'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['arquivo_gestao_gut'];
		elseif ($linha['arquivo_gestao_causa_efeito'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['arquivo_gestao_causa_efeito'];
		
		elseif ($linha['arquivo_gestao_semelhante'] && $qnt==1 && !$arquivo_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['arquivo_gestao_semelhante'];
	
		elseif ($linha['arquivo_gestao_forum'] && $qnt==1 && !$arquivo_id) $endereco='m=foruns&a=ver&forum_id='.$linha['arquivo_gestao_forum'];
		elseif ($linha['arquivo_gestao_checklist'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['arquivo_gestao_checklist'];
		elseif ($linha['arquivo_gestao_agenda'] && $qnt==1 && !$arquivo_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['arquivo_gestao_agenda'];
		elseif ($linha['arquivo_gestao_agrupamento'] && $qnt==1 && !$arquivo_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['arquivo_gestao_agrupamento'];
		elseif ($linha['arquivo_gestao_patrocinador'] && $qnt==1 && !$arquivo_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['arquivo_gestao_patrocinador'];
		elseif ($linha['arquivo_gestao_template'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['arquivo_gestao_template'];
		elseif ($linha['arquivo_gestao_painel'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['arquivo_gestao_painel'];
		elseif ($linha['arquivo_gestao_painel_odometro'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['arquivo_gestao_painel_odometro'];
		elseif ($linha['arquivo_gestao_painel_composicao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['arquivo_gestao_painel_composicao'];
		elseif ($linha['arquivo_gestao_tr'] && $qnt==1 && !$arquivo_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['arquivo_gestao_tr'];
		elseif ($linha['arquivo_gestao_me'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['arquivo_gestao_me'];
		elseif ($linha['arquivo_gestao_acao_item'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['arquivo_gestao_acao_item'];
		elseif ($linha['arquivo_gestao_beneficio'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['arquivo_gestao_beneficio'];
		elseif ($linha['arquivo_gestao_painel_slideshow'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['arquivo_gestao_painel_slideshow'];
		elseif ($linha['arquivo_gestao_projeto_viabilidade'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['arquivo_gestao_projeto_viabilidade'];
		elseif ($linha['arquivo_gestao_projeto_abertura'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['arquivo_gestao_projeto_abertura'];
		elseif ($linha['arquivo_gestao_plano_gestao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['arquivo_gestao_plano_gestao'];
		elseif ($linha['arquivo_gestao_ssti'] && $qnt==1 && !$arquivo_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['arquivo_gestao_ssti'];
		elseif ($linha['arquivo_gestao_laudo'] && $qnt==1 && !$arquivo_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['arquivo_gestao_laudo'];
		elseif ($linha['arquivo_gestao_trelo'] && $qnt==1 && !$arquivo_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['arquivo_gestao_trelo'];
		elseif ($linha['arquivo_gestao_trelo_cartao'] && $qnt==1 && !$arquivo_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['arquivo_gestao_trelo_cartao'];
		elseif ($linha['arquivo_gestao_pdcl'] && $qnt==1 && !$arquivo_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['arquivo_gestao_pdcl'];
		elseif ($linha['arquivo_gestao_pdcl_item'] && $qnt==1 && !$arquivo_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['arquivo_gestao_pdcl_item'];
		
		else $endereco='m=arquivos&a=ver&arquivo_id='.$obj->arquivo_id;
		$Aplic->redirecionar($endereco);
		}	
	}
	
if ($del) {
	$obj->load($arquivo_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		} 
	else {
		$obj->notificar($_REQUEST);
		$Aplic->setMsg('excluído', UI_MSG_OK, true);
		}
	if ($dialogo){
		echo '<script type="text/javascript">';
		echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
		echo 'else self.close();';
		echo '</script>';	
		}
	else{
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('arquivo_gestao.*');
		$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
		$sql->adOrdem('arquivo_gestao_ordem ASC');
		$linha=$sql->linha();
		$sql->limpar();
		
		$sql->adTabela('arquivo_gestao');
		$sql->adCampo('count(arquivo_gestao_id)');
		$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
		$qnt=$sql->Resultado();
		$sql->limpar();
		
		if ($linha['arquivo_gestao_tarefa'] && $qnt==1 && !$arquivo_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['arquivo_gestao_tarefa'];
		elseif ($linha['arquivo_gestao_projeto'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['arquivo_gestao_projeto'];
		elseif ($linha['arquivo_gestao_perspectiva'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['arquivo_gestao_perspectiva'];
		elseif ($linha['arquivo_gestao_tema'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['arquivo_gestao_tema'];
		elseif ($linha['arquivo_gestao_objetivo'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['arquivo_gestao_objetivo'];
		elseif ($linha['arquivo_gestao_fator'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['arquivo_gestao_fator'];
		elseif ($linha['arquivo_gestao_estrategia'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['arquivo_gestao_estrategia'];
		elseif ($linha['arquivo_gestao_meta'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['arquivo_gestao_meta'];
		elseif ($linha['arquivo_gestao_pratica'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['arquivo_gestao_pratica'];
		elseif ($linha['arquivo_gestao_indicador'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['arquivo_gestao_indicador'];
		elseif ($linha['arquivo_gestao_acao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['arquivo_gestao_acao'];
		elseif ($linha['arquivo_gestao_canvas'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['arquivo_gestao_canvas'];
		elseif ($linha['arquivo_gestao_risco'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['arquivo_gestao_risco'];
		elseif ($linha['arquivo_gestao_risco_resposta'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['arquivo_gestao_risco_resposta'];
		elseif ($linha['arquivo_gestao_calendario'] && $qnt==1 && !$arquivo_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['arquivo_gestao_calendario'];
		elseif ($linha['arquivo_gestao_monitoramento'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['arquivo_gestao_monitoramento'];
		elseif ($linha['arquivo_gestao_ata'] && $qnt==1 && !$arquivo_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['arquivo_gestao_ata'];
		elseif ($linha['arquivo_gestao_mswot'] && $qnt==1 && !$arquivo_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['arquivo_gestao_mswot'];
		elseif ($linha['arquivo_gestao_swot'] && $qnt==1 && !$arquivo_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['arquivo_gestao_swot'];
		elseif ($linha['arquivo_gestao_operativo'] && $qnt==1 && !$arquivo_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_operativo'];
		elseif ($linha['arquivo_gestao_instrumento'] && $qnt==1 && !$arquivo_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['arquivo_gestao_instrumento'];
		elseif ($linha['arquivo_gestao_recurso'] && $qnt==1 && !$arquivo_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['arquivo_gestao_recurso'];
		elseif ($linha['arquivo_gestao_problema'] && $qnt==1 && !$arquivo_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['arquivo_gestao_problema'];
		elseif ($linha['arquivo_gestao_demanda'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['arquivo_gestao_demanda'];
		elseif ($linha['arquivo_gestao_programa'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['arquivo_gestao_programa'];
		elseif ($linha['arquivo_gestao_licao'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['arquivo_gestao_licao'];
		elseif ($linha['arquivo_gestao_evento'] && $qnt==1 && !$arquivo_id) $endereco='m=calendario&a=ver&evento_id='.$linha['arquivo_gestao_evento'];
		elseif ($linha['arquivo_gestao_link'] && $qnt==1 && !$arquivo_id) $endereco='m=links&a=ver&link_id='.$linha['arquivo_gestao_link'];
		elseif ($linha['arquivo_gestao_avaliacao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['arquivo_gestao_avaliacao'];
		elseif ($linha['arquivo_gestao_tgn'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['arquivo_gestao_tgn'];
		elseif ($linha['arquivo_gestao_brainstorm'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['arquivo_gestao_brainstorm'];
		elseif ($linha['arquivo_gestao_gut'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['arquivo_gestao_gut'];
		elseif ($linha['arquivo_gestao_causa_efeito'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['arquivo_gestao_causa_efeito'];
		
		elseif ($linha['arquivo_gestao_semelhante'] && $qnt==1 && !$arquivo_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['arquivo_gestao_semelhante'];
		
		elseif ($linha['arquivo_gestao_forum'] && $qnt==1 && !$arquivo_id) $endereco='m=foruns&a=ver&forum_id='.$linha['arquivo_gestao_forum'];
		elseif ($linha['arquivo_gestao_checklist'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['arquivo_gestao_checklist'];
		elseif ($linha['arquivo_gestao_agenda'] && $qnt==1 && !$arquivo_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['arquivo_gestao_agenda'];
		elseif ($linha['arquivo_gestao_agrupamento'] && $qnt==1 && !$arquivo_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['arquivo_gestao_agrupamento'];
		elseif ($linha['arquivo_gestao_patrocinador'] && $qnt==1 && !$arquivo_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['arquivo_gestao_patrocinador'];
		elseif ($linha['arquivo_gestao_template'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['arquivo_gestao_template'];
		elseif ($linha['arquivo_gestao_painel'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['arquivo_gestao_painel'];
		elseif ($linha['arquivo_gestao_painel_odometro'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['arquivo_gestao_painel_odometro'];
		elseif ($linha['arquivo_gestao_painel_composicao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['arquivo_gestao_painel_composicao'];
		elseif ($linha['arquivo_gestao_tr'] && $qnt==1 && !$arquivo_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['arquivo_gestao_tr'];
		elseif ($linha['arquivo_gestao_me'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['arquivo_gestao_me'];
		elseif ($linha['arquivo_gestao_acao_item'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['arquivo_gestao_acao_item'];
		elseif ($linha['arquivo_gestao_beneficio'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['arquivo_gestao_beneficio'];
		elseif ($linha['arquivo_gestao_painel_slideshow'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['arquivo_gestao_painel_slideshow'];
		elseif ($linha['arquivo_gestao_projeto_viabilidade'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['arquivo_gestao_projeto_viabilidade'];
		elseif ($linha['arquivo_gestao_projeto_abertura'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['arquivo_gestao_projeto_abertura'];
		elseif ($linha['arquivo_gestao_plano_gestao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['arquivo_gestao_plano_gestao'];
		elseif ($linha['arquivo_gestao_ssti'] && $qnt==1 && !$arquivo_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['arquivo_gestao_ssti'];
		elseif ($linha['arquivo_gestao_laudo'] && $qnt==1 && !$arquivo_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['arquivo_gestao_laudo'];
		elseif ($linha['arquivo_gestao_trelo'] && $qnt==1 && !$arquivo_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['arquivo_gestao_trelo'];
		elseif ($linha['arquivo_gestao_trelo_cartao'] && $qnt==1 && !$arquivo_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['arquivo_gestao_trelo_cartao'];
		elseif ($linha['arquivo_gestao_pdcl'] && $qnt==1 && !$arquivo_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['arquivo_gestao_pdcl'];
		elseif ($linha['arquivo_gestao_pdcl_item'] && $qnt==1 && !$arquivo_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['arquivo_gestao_pdcl_item'];
		else $endereco='m=arquivos&a=index';
		$Aplic->redirecionar($endereco);
		}		 		
	}
	
ignore_user_abort(1);
$upload = null;

if (isset($_FILES['arquivo'])) {
	$upload = $_FILES['arquivo'];
	$tipo=explode('/',$upload['type']);	
	$tipo=strtolower(pathinfo($upload['name'], PATHINFO_EXTENSION));
	$permitido=getSisValor('downloadPermitido');
	
	$proibido=getSisValor('downloadProibido');
  $verificar_malicioso=explode('.',$_FILES['arquivo']['name']);
 	$malicioso=false;
 	foreach($verificar_malicioso as $extensao) {
 		if (in_array(strtolower($extensao), $proibido)) {
 			$malicioso=$extensao;
 			break;
 			}
 		}
 	if ($malicioso) {
  	$Aplic->setMsg('Extensão '.$malicioso.' não é permitida!', UI_MSG_ERRO);
  	$Aplic->redirecionar('m=arquivos');
  	}
  elseif ($upload['size'] < 1) {
		if (!$arquivo_id) {
			$Aplic->setMsg('Arquivo enviado tem tamanho zero. Processo abortado.', UI_MSG_ERRO);
			$Aplic->redirecionar('m=arquivos');
			}
		}
  else if (!in_array($tipo, $permitido)) {
  	$Aplic->setMsg('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido', UI_MSG_ERRO);
		$Aplic->redirecionar('m=arquivos');
  	}	
	else {
		$obj->arquivo_nome = $upload['name'];
		$obj->arquivo_tipo = $upload['type'];
		$obj->arquivo_tamanho = $upload['size'];
		$obj->arquivo_data = date('Y-m-d H:i:s');
		$obj->arquivo_nome_real = md5(uniqid(rand(), true));
		$res = $obj->moverTemp($upload);
		
		if (!$res) {
			$Aplic->setMsg('Não foi possível escrever o arquivo', UI_MSG_ERRO);
			$Aplic->redirecionar('m=arquivos');
			}
		}
	}
	
	
if (!$arquivo_id) {
	$obj->arquivo_dono = $Aplic->usuario_id;
	if (!$obj->arquivo_versao_id) {
		$sql->adTabela('arquivo');
		$sql->adCampo('arquivo_versao_id');
		$sql->adOrdem('arquivo_versao_id DESC');
		$sql->setLimite(1);
		$ultimo_arquivo_versao = $sql->Resultado();
		$sql->limpar();
		$obj->arquivo_versao_id = $ultimo_arquivo_versao + 1;
		} 
	else {
		$sql->adTabela('arquivo');
		$sql->adAtualizar('arquivo_saida', '');
		$sql->adOnde('arquivo_versao_id = '.(int)$obj->arquivo_versao_id);
		$sql->exec();
		$sql->limpar();
		}
	}
if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->load($obj->arquivo_id);
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($arquivo_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}

if ($dialogo){
	echo '<script type="text/javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	}

if (getParam($_REQUEST, 'uuid', null)){
	
	$sql->adTabela('arquivo_gestao');
	$sql->adCampo('arquivo_gestao.*');
	$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
	$sql->adOrdem('arquivo_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('arquivo_gestao');
	$sql->adCampo('count(arquivo_gestao_id)');
	$sql->adOnde('arquivo_gestao_arquivo='.(int)$obj->arquivo_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['arquivo_gestao_tarefa'] && $qnt==1 && !$arquivo_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['arquivo_gestao_tarefa'];
	elseif ($linha['arquivo_gestao_projeto'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['arquivo_gestao_projeto'];
	elseif ($linha['arquivo_gestao_perspectiva'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['arquivo_gestao_perspectiva'];
	elseif ($linha['arquivo_gestao_tema'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['arquivo_gestao_tema'];
	elseif ($linha['arquivo_gestao_objetivo'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['arquivo_gestao_objetivo'];
	elseif ($linha['arquivo_gestao_fator'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['arquivo_gestao_fator'];
	elseif ($linha['arquivo_gestao_estrategia'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['arquivo_gestao_estrategia'];
	elseif ($linha['arquivo_gestao_meta'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['arquivo_gestao_meta'];
	elseif ($linha['arquivo_gestao_pratica'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['arquivo_gestao_pratica'];
	elseif ($linha['arquivo_gestao_indicador'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['arquivo_gestao_indicador'];
	elseif ($linha['arquivo_gestao_acao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['arquivo_gestao_acao'];
	elseif ($linha['arquivo_gestao_canvas'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['arquivo_gestao_canvas'];
	elseif ($linha['arquivo_gestao_risco'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['arquivo_gestao_risco'];
	elseif ($linha['arquivo_gestao_risco_resposta'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['arquivo_gestao_risco_resposta'];
	elseif ($linha['arquivo_gestao_calendario'] && $qnt==1 && !$arquivo_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['arquivo_gestao_calendario'];
	elseif ($linha['arquivo_gestao_monitoramento'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['arquivo_gestao_monitoramento'];
	elseif ($linha['arquivo_gestao_ata'] && $qnt==1 && !$arquivo_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['arquivo_gestao_ata'];
	elseif ($linha['arquivo_gestao_mswot'] && $qnt==1 && !$arquivo_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['arquivo_gestao_mswot'];
	elseif ($linha['arquivo_gestao_swot'] && $qnt==1 && !$arquivo_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['arquivo_gestao_swot'];
	elseif ($linha['arquivo_gestao_operativo'] && $qnt==1 && !$arquivo_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['arquivo_gestao_operativo'];
	elseif ($linha['arquivo_gestao_instrumento'] && $qnt==1 && !$arquivo_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['arquivo_gestao_instrumento'];
	elseif ($linha['arquivo_gestao_recurso'] && $qnt==1 && !$arquivo_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['arquivo_gestao_recurso'];
	elseif ($linha['arquivo_gestao_problema'] && $qnt==1 && !$arquivo_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['arquivo_gestao_problema'];
	elseif ($linha['arquivo_gestao_demanda'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['arquivo_gestao_demanda'];
	elseif ($linha['arquivo_gestao_programa'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['arquivo_gestao_programa'];
	elseif ($linha['arquivo_gestao_licao'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['arquivo_gestao_licao'];
	elseif ($linha['arquivo_gestao_evento'] && $qnt==1 && !$arquivo_id) $endereco='m=calendario&a=ver&evento_id='.$linha['arquivo_gestao_evento'];
	elseif ($linha['arquivo_gestao_link'] && $qnt==1 && !$arquivo_id) $endereco='m=links&a=ver&link_id='.$linha['arquivo_gestao_link'];
	elseif ($linha['arquivo_gestao_avaliacao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['arquivo_gestao_avaliacao'];
	elseif ($linha['arquivo_gestao_tgn'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['arquivo_gestao_tgn'];
	elseif ($linha['arquivo_gestao_brainstorm'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['arquivo_gestao_brainstorm'];
	elseif ($linha['arquivo_gestao_gut'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['arquivo_gestao_gut'];
	elseif ($linha['arquivo_gestao_causa_efeito'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['arquivo_gestao_causa_efeito'];
	
	elseif ($linha['arquivo_gestao_semelhante'] && $qnt==1 && !$arquivo_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['arquivo_gestao_semelhante'];
	
	elseif ($linha['arquivo_gestao_forum'] && $qnt==1 && !$arquivo_id) $endereco='m=foruns&a=ver&forum_id='.$linha['arquivo_gestao_forum'];
	elseif ($linha['arquivo_gestao_checklist'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['arquivo_gestao_checklist'];
	elseif ($linha['arquivo_gestao_agenda'] && $qnt==1 && !$arquivo_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['arquivo_gestao_agenda'];
	elseif ($linha['arquivo_gestao_agrupamento'] && $qnt==1 && !$arquivo_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['arquivo_gestao_agrupamento'];
	elseif ($linha['arquivo_gestao_patrocinador'] && $qnt==1 && !$arquivo_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['arquivo_gestao_patrocinador'];
	elseif ($linha['arquivo_gestao_template'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['arquivo_gestao_template'];
	elseif ($linha['arquivo_gestao_painel'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['arquivo_gestao_painel'];
	elseif ($linha['arquivo_gestao_painel_odometro'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['arquivo_gestao_painel_odometro'];
	elseif ($linha['arquivo_gestao_painel_composicao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['arquivo_gestao_painel_composicao'];
	elseif ($linha['arquivo_gestao_tr'] && $qnt==1 && !$arquivo_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['arquivo_gestao_tr'];
	elseif ($linha['arquivo_gestao_me'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['arquivo_gestao_me'];
	elseif ($linha['arquivo_gestao_acao_item'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['arquivo_gestao_acao_item'];
	elseif ($linha['arquivo_gestao_beneficio'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['arquivo_gestao_beneficio'];
	elseif ($linha['arquivo_gestao_painel_slideshow'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['arquivo_gestao_painel_slideshow'];
	elseif ($linha['arquivo_gestao_projeto_viabilidade'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['arquivo_gestao_projeto_viabilidade'];
	elseif ($linha['arquivo_gestao_projeto_abertura'] && $qnt==1 && !$arquivo_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['arquivo_gestao_projeto_abertura'];
	elseif ($linha['arquivo_gestao_plano_gestao'] && $qnt==1 && !$arquivo_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['arquivo_gestao_plano_gestao'];
	elseif ($linha['arquivo_gestao_ssti'] && $qnt==1 && !$arquivo_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['arquivo_gestao_ssti'];
	elseif ($linha['arquivo_gestao_laudo'] && $qnt==1 && !$arquivo_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['arquivo_gestao_laudo'];
	elseif ($linha['arquivo_gestao_trelo'] && $qnt==1 && !$arquivo_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['arquivo_gestao_trelo'];
	elseif ($linha['arquivo_gestao_trelo_cartao'] && $qnt==1 && !$arquivo_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['arquivo_gestao_trelo_cartao'];
	elseif ($linha['arquivo_gestao_pdcl'] && $qnt==1 && !$arquivo_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['arquivo_gestao_pdcl'];
	elseif ($linha['arquivo_gestao_pdcl_item'] && $qnt==1 && !$arquivo_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['arquivo_gestao_pdcl_item'];
	else $endereco='m=arquivos&a=ver&arquivo_id='.$obj->arquivo_id;
	$Aplic->redirecionar($endereco);
	}
else $Aplic->redirecionar('m=arquivos&a=ver&arquivo_id='.$obj->arquivo_id);
?>