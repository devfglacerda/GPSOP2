<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


require_once (BASE_DIR.'/modulos/praticas/log.class.php');

$sql = new BDConsulta;

$del = intval(getParam($_REQUEST, 'del', 0));

$log_tarefa=getParam($_REQUEST, 'log_tarefa', null);
$log_projeto=getParam($_REQUEST, 'log_projeto', null);
$log_indicador=getParam($_REQUEST, 'log_indicador', null);
$log_pratica=getParam($_REQUEST, 'log_pratica', null);
$log_perspectiva=getParam($_REQUEST, 'log_perspectiva', null);
$log_tema=getParam($_REQUEST, 'log_tema', null);
$log_objetivo=getParam($_REQUEST, 'log_objetivo', null);
$log_fator=getParam($_REQUEST, 'log_fator', null);
$log_estrategia=getParam($_REQUEST, 'log_estrategia', null);
$log_acao=getParam($_REQUEST, 'log_acao', null);
$log_meta=getParam($_REQUEST, 'log_meta', null);
$log_canvas=getParam($_REQUEST, 'log_canvas', null);
$log_risco=getParam($_REQUEST, 'log_risco', null);
$log_risco_resposta=getParam($_REQUEST, 'log_risco_resposta', null);
$log_problema=getParam($_REQUEST, 'log_problema', null);
$log_calendario=getParam($_REQUEST, 'log_calendario', null);
$log_monitoramento=getParam($_REQUEST, 'log_monitoramento', null);
$log_ata=getParam($_REQUEST, 'log_ata', null);
$log_mswot=getParam($_REQUEST, 'log_mswot', null);
$log_swot=getParam($_REQUEST, 'log_swot', null);
$log_operativo=getParam($_REQUEST, 'log_operativo', null);
$log_instrumento=getParam($_REQUEST, 'log_instrumento', null);
$log_recurso=getParam($_REQUEST, 'log_recurso', null);
$log_demanda=getParam($_REQUEST, 'log_demanda', null);
$log_programa=getParam($_REQUEST, 'log_programa', null);
$log_licao=getParam($_REQUEST, 'log_licao', null);
$log_evento=getParam($_REQUEST, 'log_evento', null);
$log_link=getParam($_REQUEST, 'log_link', null);
$log_avaliacao=getParam($_REQUEST, 'log_avaliacao', null);
$log_tgn=getParam($_REQUEST, 'log_tgn', null);
$log_brainstorm=getParam($_REQUEST, 'log_brainstorm', null);
$log_gut=getParam($_REQUEST, 'log_gut', null);
$log_causa_efeito=getParam($_REQUEST, 'log_causa_efeito', null);
$log_arquivo=getParam($_REQUEST, 'log_arquivo', null);
$log_forum=getParam($_REQUEST, 'log_forum', null);
$log_checklist=getParam($_REQUEST, 'log_checklist', null);
$log_agenda=getParam($_REQUEST, 'log_agenda', null);
$log_agrupamento=getParam($_REQUEST, 'log_agrupamento', null);
$log_patrocinador=getParam($_REQUEST, 'log_patrocinador', null);
$log_template=getParam($_REQUEST, 'log_template', null);
$log_painel=getParam($_REQUEST, 'log_painel', null);
$log_painel_odometro=getParam($_REQUEST, 'log_painel_odometro', null);
$log_painel_composicao=getParam($_REQUEST, 'log_painel_composicao', null);
$log_tr=getParam($_REQUEST, 'log_tr', null);

$log_me=getParam($_REQUEST, 'log_me', null);
$log_acao_item=getParam($_REQUEST, 'log_acao_item', null);
$log_beneficio=getParam($_REQUEST, 'log_beneficio', null);
$log_painel_slideshow=getParam($_REQUEST, 'log_painel_slideshow', null);
$log_projeto_viabilidade=getParam($_REQUEST, 'log_projeto_viabilidade', null);
$log_projeto_abertura=getParam($_REQUEST, 'log_projeto_abertura', null);
$log_plano_gestao=getParam($_REQUEST, 'log_plano_gestao', null);
$log_ssti=getParam($_REQUEST, 'log_ssti', null);
$log_laudo=getParam($_REQUEST, 'log_laudo', null);
$log_trelo=getParam($_REQUEST, 'log_trelo', null);
$log_trelo_cartao=getParam($_REQUEST, 'log_trelo_cartao', null);
$log_pdcl=getParam($_REQUEST, 'log_pdcl', null);
$log_pdcl_item=getParam($_REQUEST, 'log_pdcl_item', null);





$log_id=getParam($_REQUEST, 'log_id', null);

$endereco='';
if ($log_tarefa) $endereco='m=tarefas&a=ver&tab=0&tarefa_id='.(int)$log_tarefa; 
elseif ($log_projeto) $endereco='m=projetos&a=ver&tab=0&projeto_id='.(int)$log_projeto; 	
elseif ($log_indicador) $endereco='m=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.(int)$log_indicador; 
elseif ($log_pratica) $endereco='m=praticas&a=pratica_ver&tab=0&pratica_id='.(int)$log_pratica; 
elseif ($log_perspectiva) $endereco='m=praticas&a=perspectiva_ver&tab=0&pg_perspectiva_id='.(int)$log_perspectiva;
elseif ($log_tema) $endereco='m=praticas&a=tema_ver&tab=0&tema_id='.(int)$log_tema;
elseif ($log_objetivo) $endereco='m=praticas&a=obj_estrategico_ver&tab=0&objetivo_id='.(int)$log_objetivo;
elseif ($log_fator) $endereco='m=praticas&a=fator_ver&tab=0&fator_id='.(int)$log_fator;
elseif ($log_estrategia) $endereco='m=praticas&a=estrategia_ver&tab=0&pg_estrategia_id='.(int)$log_estrategia;
elseif ($log_acao) $endereco='m=praticas&a=plano_acao_ver&tab=0&plano_acao_id='.(int)$log_acao;
elseif ($log_meta) $endereco='m=praticas&a=meta_ver&tab=0&pg_meta_id='.(int)$log_meta;
elseif ($log_canvas) $endereco='m=praticas&a=canvas_pro_ver&tab=0&canvas_id='.(int)$log_canvas;
elseif ($log_risco) $endereco='m=praticas&a=risco_pro_ver&tab=0&risco_id='.(int)$log_risco;
elseif ($log_risco_resposta) $endereco='m=praticas&a=risco_resposta_pro_ver&tab=0&risco_resposta_id='.(int)$log_risco_resposta;
elseif ($log_problema) $endereco='m=problema&a=problema_ver&tab=0&problema_id='.(int)$log_problema;
elseif ($log_calendario) $endereco='m=sistema&u=calendario&a=calendario_ver&tab=0&calendario_id='.(int)$log_calendario;
elseif ($log_monitoramento) $endereco='m=praticas&a=monitoramento_ver_pro&tab=0&monitoramento_id='.(int)$log_monitoramento;
elseif ($log_ata) $endereco='m=atas&a=ata_ver&tab=0&ata_id='.(int)$log_ata;
elseif ($log_mswot) $endereco='m=swot&a=mswot_ver&tab=0&mswot_id='.(int)$log_mswot;
elseif ($log_swot) $endereco='m=swot&a=swot_ver&tab=0&swot_id='.(int)$log_swot;
elseif ($log_operativo) $endereco='m=operativo&a=operativo_ver&tab=0&operativo_id='.(int)$log_operativo;
elseif ($log_instrumento) $endereco='m=instrumento&a=instrumento_ver&tab=0&instrumento_id='.(int)$log_instrumento;
elseif ($log_recurso) $endereco='m=recursos&a=ver&tab=0&recurso_id='.(int)$log_recurso;
elseif ($log_demanda) $endereco='m=projetos&a=demanda_ver&tab=0&demanda_id='.(int)$log_demanda;
elseif ($log_programa) $endereco='m=projetos&a=programa_ver&tab=0&programa_id='.(int)$log_programa;
elseif ($log_licao) $endereco='m=projetos&a=licao_ver&tab=0&licao_id='.(int)$log_licao;
elseif ($log_evento) $endereco='m=calendario&a=ver&tab=0&evento_id='.(int)$log_evento;
elseif ($log_link) $endereco='m=links&a=ver&tab=0&link_id='.(int)$log_link;
elseif ($log_avaliacao) $endereco='m=praticas&a=avaliacao_ver&tab=0&avaliacao_id='.(int)$log_avaliacao;
elseif ($log_tgn) $endereco='m=praticas&a=tgn_pro_ver&tab=0&tgn_id='.(int)$log_tgn;
elseif ($log_brainstorm) $endereco='m=praticas&a=brainstorm_ver&tab=0&brainstorm_id='.(int)$log_brainstorm;
elseif ($log_gut) $endereco='m=praticas&a=gut_ver&tab=0&gut_id='.(int)$log_gut;
elseif ($log_causa_efeito) $endereco='m=praticas&a=causa_efeito_ver&tab=0&causa_efeito_id='.(int)$log_causa_efeito;
elseif ($log_arquivo) $endereco='m=arquivos&a=ver&tab=0&arquivo_id='.(int)$log_arquivo;
elseif ($log_forum) $endereco='m=foruns&a=ver&tab=0&forum_id='.(int)$log_forum;
elseif ($log_checklist) $endereco='m=praticas&a=checklist_ver&tab=0&checklist_id='.(int)$log_checklist;
elseif ($log_agenda) $endereco='m=email&a=ver_compromisso&tab=0&agenda_id='.(int)$log_agenda;
elseif ($log_agrupamento) $endereco='m=agrupamento&a=agrupamento_ver&tab=0&agrupamento_id='.(int)$log_agrupamento;
elseif ($log_patrocinador) $endereco='m=patrocinadores&a=patrocinador_ver&tab=0&patrocinador_id='.(int)$log_patrocinador;
elseif ($log_template) $endereco='m=projetos&a=template_pro_ver&tab=0&template_id='.(int)$log_template;
elseif ($log_painel) $endereco='m=praticas&a=painel_pro_ver&tab=0&painel_id='.(int)$log_painel;
elseif ($log_painel_odometro) $endereco='m=praticas&a=odometro_pro_ver&tab=0&painel_odometro_id='.(int)$log_painel_odometro;
elseif ($log_painel_composicao) $endereco='m=praticas&a=painel_composicao_pro_ver&tab=0&painel_composicao_id='.(int)$log_painel_composicao;
elseif ($log_tr) $endereco='m=tr&a=tr_ver&tab=0&tr_id='.(int)$log_tr;
elseif ($log_me) $endereco='m=praticas&a=me_ver_pro&tab=0&me_id='.(int)$log_me;
elseif ($log_acao_item) $endereco='m=praticas&a=plano_acao_item_ver&tab=0&plano_acao_item_id='.(int)$log_acao_item;
elseif ($log_beneficio) $endereco='m=projetos&a=beneficio_pro_ver&tab=0&beneficio_id='.(int)$log_beneficio;
elseif ($log_painel_slideshow) $endereco='m=praticas&a=painel_slideshow_pro_ver&tab=0&jquery=1&painel_slideshow_id='.(int)$log_painel_slideshow;
elseif ($log_projeto_viabilidade) $endereco='m=projetos&a=viabilidade_ver&tab=0&projeto_viabilidade_id='.(int)$log_projeto_viabilidade;
elseif ($log_projeto_abertura) $endereco='m=projetos&a=termo_abertura_ver&tab=0&projeto_abertura_id='.(int)$log_projeto_abertura;
elseif ($log_plano_gestao) $endereco='m=praticas&u=gestao&a=menu&tab=0&pg_id='.(int)$log_plano_gestao;
elseif ($log_ssti) $endereco='m=ssti&a=ssti_ver&ssti_id='.(int)$log_ssti;
elseif ($log_laudo) $endereco='m=ssti&a=laudo_ver&laudo_id='.(int)$log_laudo;
elseif ($log_trelo) $endereco='m=trelo&a=trelo_ver&trelo_id='.(int)$log_trelo;
elseif ($log_trelo_cartao) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.(int)$log_trelo_cartao;
elseif ($log_pdcl) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.(int)$log_pdcl;
elseif ($log_pdcl_item) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.(int)$log_pdcl_item;







$obj = new CLog();

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar($endereco);
	}


if ($del) {
	$obj->load($log_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar($endereco);
		} 
	else {
		$Aplic->setMsg('Registro de ocorrência excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar($endereco);
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($log_id ? 'Registro de ocorrência atualizado' : 'Registro de ocorrência adicionado', UI_MSG_OK, true);
	
	if ($log_projeto){
		if ($_REQUEST['projeto_status_antigo']!=$_REQUEST['log_reg_mudanca_status']){
			//if (!$aprova_registro){
			$sql->adTabela('projetos');
			$sql->adAtualizar('projeto_status', getParam($_REQUEST, 'log_reg_mudanca_status', null));
			$sql->adOnde('projeto_id='.(int)$log_projeto);
			$sql->exec();
			$sql->limpar();
			//}
			}
		if ($_REQUEST['projeto_fase_antigo']!=$_REQUEST['log_reg_mudanca_fase']){
			//if (!$aprova_registro){
			$sql->adTabela('projetos');
			$sql->adAtualizar('projeto_fase', getParam($_REQUEST, 'log_reg_mudanca_fase', null));
			$sql->adOnde('projeto_id='.(int)$log_projeto);
			$sql->exec();
			$sql->limpar();
			//}
			}
		}	
	
	
	
	
	
	
	
	}


if ($dialogo){
	echo '<script type="text/javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	} 	
else $Aplic->redirecionar($endereco);
	

?>