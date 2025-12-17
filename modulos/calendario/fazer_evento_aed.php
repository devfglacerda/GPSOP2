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
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

//vindo do adicionar evento no plano de comunicacoes
$projeto_comunicacao_evento_id=getParam($_REQUEST, 'projeto_comunicacao_evento_id', null);
$_REQUEST['evento_ativo']=(isset($_REQUEST['evento_ativo']) ? 1 : 0);

if (isset($_REQUEST['evento_duracao'])) $_REQUEST['evento_duracao']=float_americano(getParam($_REQUEST, 'evento_duracao', null))*$config['horas_trab_diario'];

$obj = new CEvento();
$msg = '';
$del=getParam($_REQUEST, 'del', 0);
$nao_eh_novo=getParam($_REQUEST, 'evento_id', null);
$evento_id=getParam($_REQUEST, 'evento_id', null);

if ($del && !$podeExcluir) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($nao_eh_novo && !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$nao_eh_novo && !$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['evento_inicio'])) $_REQUEST['evento_inicio']=getParam($_REQUEST, 'evento_inicio', null).' '.getParam($_REQUEST, 'inicio_hora', null);
if (isset($_REQUEST['evento_fim'])) $_REQUEST['evento_fim']=getParam($_REQUEST, 'evento_fim', null).' '.getParam($_REQUEST, 'fim_hora', null);


$email_responsavel=getParam($_REQUEST, 'email_responsavel', null);
$email_designados=getParam($_REQUEST, 'email_designados', null);
$email_designados_novos=getParam($_REQUEST, 'email_designados_novos', null);
$email_convidados=getParam($_REQUEST, 'email_convidados', null);
$email_convidados_novos=getParam($_REQUEST, 'email_convidados_novos', null);
$email_contatos_extra=getParam($_REQUEST, 'email_contatos_extra', null);
$email_extras=getParam($_REQUEST, 'email_extras', null);
$email_texto=getParam($_REQUEST, 'email_texto', null);

$sql = new BDConsulta;

if ($email_designados_novos && $evento_id){
	$sql->adTabela('evento_usuario');
	$sql->adCampo('evento_usuario_usuario');
	$sql->adOnde('evento_usuario_evento='.(int)$evento_id);
	$lista_designados_antigo=$sql->carregarColuna();
	$sql->limpar();
	}
else $lista_designados_antigo=array();


if ($email_convidados_novos && $evento_id){
	$sql->adTabela('evento_participante');
	$sql->adCampo('evento_participante_usuario');
	$sql->adOnde('evento_participante_evento='.(int)$evento_id);
	$lista_convidados_antigo=$sql->carregarColuna();
	$sql->limpar();
	}
else $lista_convidados_antigo=array();

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=calendario&a=index&data='.$obj->evento_inicio);
	}
		
if (!$obj->evento_recorrencias) $obj->evento_nr_recorrencias = 0;


require_once $Aplic->getClasseSistema('CampoCustomizados');

if ($del) {
	if (!$obj->podeExcluir($msg)) {
		$Aplic->setMsg('Evento '.$msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=calendario&a=index&data='.$obj->evento_inicio);
		}
	if (($msg = $obj->excluir())) $Aplic->setMsg('Evento '.$msg, UI_MSG_ERRO);
	elseif(!$projeto_comunicacao_evento_id) $Aplic->setMsg('Evento excluído', UI_MSG_OK, true);
	
	
	$Aplic->redirecionar('m=calendario&a=index&data='.$obj->evento_inicio);
	} 
else {
	if ($_REQUEST['evento_designado'] !='' && ($conflito = $obj->checarConflito(getParam($_REQUEST, 'evento_designado', null)))) {
		$Aplic->redirecionar('m=calendario&a=conflito&projeto_comunicacao_evento_id='.$projeto_comunicacao_evento_id.'&conflito='.implode(',',$conflito).'&objeto='.base64_encode(serialize($_REQUEST)));
		exit();
		} 
	else {
		if (($msg = $obj->armazenar())) $Aplic->setMsg('Evento '.$msg, UI_MSG_ERRO);
		else {
			if (!$projeto_comunicacao_evento_id) $Aplic->setMsg($nao_eh_novo ? 'Evento atualizado' : 'Evento adicionado', UI_MSG_OK, true);
			if (isset($_REQUEST['evento_designado'])) $obj->atualizarDesignados(explode(',', getParam($_REQUEST, 'evento_designado', null)),explode(',', getParam($_REQUEST, 'evento_designado_porcentagem', null)));
			if (($_REQUEST['evento_inicio_antigo'] && $_REQUEST['evento_inicio_antigo']!=$_REQUEST['evento_inicio']) || ($_REQUEST['evento_fim_antigo'] && $_REQUEST['evento_fim_antigo']!=$_REQUEST['evento_fim'])) $obj->atualizarDuracao(explode(',', getParam($_REQUEST, 'evento_designado', null)),explode(',', getParam($_REQUEST, 'evento_designado_porcentagem', null)));
			
			
			
			$vetor_notificar=array();
			if ($email_responsavel) $vetor_notificar['email_responsavel']=1;
			if ($email_designados) $vetor_notificar['email_designados']=1;
			if ($email_designados_novos) $vetor_notificar['email_designados_novos']=1;
			if ($email_convidados) $vetor_notificar['email_convidados']=1;
			if ($email_convidados_novos) $vetor_notificar['email_convidados_novos']=1;
			if ($email_extras) $vetor_notificar['email_extras']=1;
			if ($email_contatos_extra) $vetor_notificar['email_contatos_extra']=1;
			
			if (count($vetor_notificar)) if ($msg = $obj->notificar(
				$email_texto, 
				$nao_eh_novo, 
				$vetor_notificar, 
				$lista_designados_antigo, 
				$email_contatos_extra, 
				$email_extras, 
				$lista_convidados_antigo
				)) $Aplic->setMsg($msg, UI_MSG_ERRO);
					
			
			$obj->adLembrete();
			}
		
		
		if ($_REQUEST['evento_nr_recorrencias'] > 0 && $_REQUEST['evento_recorrencias'] > 0 && !$_REQUEST['evento_recorrencia_pai']) {
			
			//verificar se nunca criou recorrencias
			$sql->adTabela('eventos');
			$sql->adCampo('count(evento_id)');
			$sql->adOnde('evento_recorrencia_pai='.(int)$obj->evento_id);
			$existe_recorrencia=$sql->Resultado();
			$sql->limpar();
			if (!$existe_recorrencia) $obj->criar_recorrencias();
			}

		}
	}

//vindo do adicionar evento no plano de comunicacoes	
if ($projeto_comunicacao_evento_id){
	echo '<script type="text/javascript">parent.gpwebApp._popupCallback('.$projeto_comunicacao_evento_id.', '.$obj->evento_id.');</script>';
	}	
elseif ($dialogo){
	echo '<script type="text/javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	}
if (getParam($_REQUEST, 'uuid', null)){
	$sql->adTabela('evento_gestao');
	$sql->adCampo('evento_gestao.*');
	$sql->adOnde('evento_gestao_evento='.(int)(int)$obj->evento_id);
	$sql->adOrdem('evento_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('evento_gestao');
	$sql->adCampo('count(evento_gestao_id)');
	$sql->adOnde('evento_gestao_evento='.(int)$obj->evento_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha['evento_gestao_tarefa'] && $qnt==1 && !$evento_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['evento_gestao_tarefa'];
	elseif ($linha['evento_gestao_projeto'] && $qnt==1 && !$evento_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['evento_gestao_projeto'];
	elseif ($linha['evento_gestao_perspectiva'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['evento_gestao_perspectiva'];
	elseif ($linha['evento_gestao_tema'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['evento_gestao_tema'];
	elseif ($linha['evento_gestao_objetivo'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['evento_gestao_objetivo'];
	elseif ($linha['evento_gestao_fator'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['evento_gestao_fator'];
	elseif ($linha['evento_gestao_estrategia'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['evento_gestao_estrategia'];
	elseif ($linha['evento_gestao_meta'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['evento_gestao_meta'];
	elseif ($linha['evento_gestao_pratica'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['evento_gestao_pratica'];
	elseif ($linha['evento_gestao_indicador'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['evento_gestao_indicador'];
	elseif ($linha['evento_gestao_acao'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['evento_gestao_acao'];
	elseif ($linha['evento_gestao_canvas'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['evento_gestao_canvas'];
	elseif ($linha['evento_gestao_risco'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['evento_gestao_risco'];
	elseif ($linha['evento_gestao_risco_resposta'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['evento_gestao_risco_resposta'];
	elseif ($linha['evento_gestao_calendario'] && $qnt==1 && !$evento_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['evento_gestao_calendario'];
	elseif ($linha['evento_gestao_monitoramento'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['evento_gestao_monitoramento'];
	elseif ($linha['evento_gestao_ata'] && $qnt==1 && !$evento_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['evento_gestao_ata'];
	elseif ($linha['evento_gestao_mswot'] && $qnt==1 && !$evento_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['evento_gestao_mswot'];
	elseif ($linha['evento_gestao_swot'] && $qnt==1 && !$evento_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['evento_gestao_swot'];
	elseif ($linha['evento_gestao_operativo'] && $qnt==1 && !$evento_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['evento_gestao_operativo'];
	elseif ($linha['evento_gestao_instrumento'] && $qnt==1 && !$evento_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['evento_gestao_instrumento'];
	elseif ($linha['evento_gestao_recurso'] && $qnt==1 && !$evento_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['evento_gestao_recurso'];
	elseif ($linha['evento_gestao_problema'] && $qnt==1 && !$evento_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['evento_gestao_problema'];
	elseif ($linha['evento_gestao_demanda'] && $qnt==1 && !$evento_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['evento_gestao_demanda'];
	elseif ($linha['evento_gestao_licao'] && $qnt==1 && !$evento_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['evento_gestao_licao'];
	
	elseif ($linha['evento_gestao_semelhante'] && $qnt==1 && !$evento_id) $endereco='m=calendario&a=ver&evento_id='.$linha['evento_gestao_semelhante'];
	
	elseif ($linha['evento_gestao_programa'] && $qnt==1 && !$evento_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['evento_gestao_programa'];
	elseif ($linha['evento_gestao_link'] && $qnt==1 && !$evento_id) $endereco='m=links&a=ver&link_id='.$linha['evento_gestao_link'];
	elseif ($linha['evento_gestao_avaliacao'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['evento_gestao_avaliacao'];
	elseif ($linha['evento_gestao_tgn'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['evento_gestao_tgn'];
	elseif ($linha['evento_gestao_brainstorm'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['evento_gestao_brainstorm'];
	elseif ($linha['evento_gestao_gut'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['evento_gestao_gut'];
	elseif ($linha['evento_gestao_causa_efeito'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['evento_gestao_causa_efeito'];
	elseif ($linha['evento_gestao_arquivo'] && $qnt==1 && !$evento_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['evento_gestao_arquivo'];
	elseif ($linha['evento_gestao_forum'] && $qnt==1 && !$evento_id) $endereco='m=foruns&a=ver&forum_id='.$linha['evento_gestao_forum'];
	elseif ($linha['evento_gestao_checklist'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['evento_gestao_checklist'];
	elseif ($linha['evento_gestao_agenda'] && $qnt==1 && !$evento_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['evento_gestao_agenda'];
	elseif ($linha['evento_gestao_agrupamento'] && $qnt==1 && !$evento_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['evento_gestao_agrupamento'];
	elseif ($linha['evento_gestao_patrocinador'] && $qnt==1 && !$evento_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['evento_gestao_patrocinador'];
	elseif ($linha['evento_gestao_template'] && $qnt==1 && !$evento_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['evento_gestao_template'];
	elseif ($linha['evento_gestao_painel'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['evento_gestao_painel'];
	elseif ($linha['evento_gestao_painel_odometro'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['evento_gestao_painel_odometro'];
	elseif ($linha['evento_gestao_painel_composicao'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['evento_gestao_painel_composicao'];
	elseif ($linha['evento_gestao_tr'] && $qnt==1 && !$evento_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['evento_gestao_tr'];
	elseif ($linha['evento_gestao_me'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['evento_gestao_me'];
	elseif ($linha['evento_gestao_acao_item'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['evento_gestao_acao_item'];
	elseif ($linha['evento_gestao_beneficio'] && $qnt==1 && !$evento_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['evento_gestao_beneficio'];
	elseif ($linha['evento_gestao_painel_slideshow'] && $qnt==1 && !$evento_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['evento_gestao_painel_slideshow'];
	elseif ($linha['evento_gestao_projeto_viabilidade'] && $qnt==1 && !$evento_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['evento_gestao_projeto_viabilidade'];
	elseif ($linha['evento_gestao_projeto_abertura'] && $qnt==1 && !$evento_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['evento_gestao_projeto_abertura'];
	elseif ($linha['evento_gestao_plano_gestao'] && $qnt==1 && !$evento_id) $endereco='m=praticas&u=gestao&a=menu&pg_id='.$linha['evento_gestao_plano_gestao'];
	elseif ($linha['evento_gestao_ssti'] && $qnt==1 && !$evento_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['evento_gestao_ssti'];
	elseif ($linha['evento_gestao_laudo'] && $qnt==1 && !$evento_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['evento_gestao_laudo'];
	elseif ($linha['evento_gestao_trelo'] && $qnt==1 && !$evento_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['evento_gestao_trelo'];
	elseif ($linha['evento_gestao_trelo_cartao'] && $qnt==1 && !$evento_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['evento_gestao_trelo_cartao'];
	elseif ($linha['evento_gestao_pdcl'] && $qnt==1 && !$evento_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['evento_gestao_pdcl'];
	elseif ($linha['evento_gestao_pdcl_item'] && $qnt==1 && !$evento_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['evento_gestao_pdcl_item'];

	else $endereco='m=calendario&a=index&data='.$obj->evento_inicio;
	$Aplic->redirecionar($endereco);
	}
else $Aplic->redirecionar('m=calendario&a=index&data='.$obj->evento_inicio);
?>