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
$del=getParam($_REQUEST, 'del', 0);
$projeto_id=getParam($_REQUEST, 'projeto_id', null);
$nao_eh_novo=getParam($_REQUEST, 'projeto_id', null);
$wbs=getParam($_REQUEST, 'wbs', 0);
$editar_gantt= (int) getParam($_REQUEST, 'editar_gantt', 0);
$sql = new BDConsulta;

//ajuste de permissão para sub-módulo projetos
global $podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar;
list($podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar)  = listaPermissoes('projetos', 'projetos_lista');

include_once BASE_DIR.'/modulos/tarefas/funcoes.php';
//permissoes
if ($del) {
	//checar permissao excluir projeto
	$objeto = new CProjeto();
	$objeto->load($projeto_id);
	if (!$podeExcluir || !permiteEditar($objeto->projeto_acesso,$projeto_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif ($nao_eh_novo) {
		//checar permissao editar projeto
	$objeto = new CProjeto();
	$objeto->load($projeto_id);	
		
	if (!$podeEditar || !permiteEditar($objeto->projeto_acesso,$projeto_id)) 	$Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif (!$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado'); //checar permissao inserir projeto

$obj = new CProjeto();
$msg = '';
$notificar_responsavel = (isset($_REQUEST['email_projeto_responsavel_box']) ? 1 : 0);
$notificar_supervisor = (isset($_REQUEST['email_projeto_supervisor_box']) ? 1 : 0);
$notificar_autoridade = (isset($_REQUEST['email_projeto_autoridade_box']) ? 1 : 0);
$notificar_cliente = (isset($_REQUEST['email_projeto_cliente_box']) ? 1 : 0);
$notificar_contatos = (isset($_REQUEST['email_projeto_contatos_box']) ? 1 : 0);
$notificar_designados = (isset($_REQUEST['email_projeto_designados_box']) ? 1 : 0);
$notificar_stakeholders = (isset($_REQUEST['email_projeto_stakeholder_box']) ? 1 : 0);

$email_contatos=getParam($_REQUEST, 'email_contatos', null);
$email_extras=getParam($_REQUEST, 'email_extras', null);
$email_texto=getParam($_REQUEST, 'email_texto', null);


//gravar as modificações	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));


$datas=array('projeto_data_inicio', 'projeto_data_fim');
$superior='projeto_superior';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index');
	}

if ($obj->projeto_data_inicio) {
	$data = new CData($obj->projeto_data_inicio);
	$obj->projeto_data_inicio = $data->format('%Y-%m-%d %H:%M:%S');
	}
	
if ($obj->projeto_data_fim) {
	$data = new CData($obj->projeto_data_fim);
	$data->setTime(23, 59, 59);
	$obj->projeto_data_fim = $data->format('%Y-%m-%d %H:%M:%S');
	}
	
if ($obj->projeto_fim_atualizado) {
	$data = new CData($obj->projeto_fim_atualizado);
	$obj->projeto_fim_atualizado = $data->format('%Y-%m-%d %H:%M:%S');
	}
	
$codigo=$obj->getCodigo();
if ($codigo) $obj->projeto_codigo=$codigo;
	

if ($del) {

	$sql->adTabela('projeto_observador');
	$sql->adCampo('projeto_observador.*');
	$sql->adOnde('projeto_observador_projeto ='.(int)$projeto_id);
	$lista = $sql->lista();
	$sql->limpar();
	$qnt_programa=0;
	$qnt_perspectiva=0;
	$qnt_tema=0;
	$qnt_objetivo=0;
	$qnt_me=0;
	$qnt_fator=0;
	$qnt_estrategia=0;
	$qnt_meta=0;
	$qnt_acao=0;
	$qnt_risco=0;
	$qnt_risco_resposta=0;

	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=index');
		exit();
		} 
	else {

		$obj->projeto_id=$projeto_id;
		
		if ($Aplic->profissional){
			foreach($lista as $linha){
				
				if ($linha['projeto_observador_portfolio']){
					$obj1=new CProjeto();
					$obj1->load($linha['projeto_observador_portfolio']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}
					
				elseif ($linha['projeto_observador_programa']){
					if (!($qnt_programa++)) require_once BASE_DIR.'/modulos/projetos/programa_pro.class.php';
					$obj1= new CPrograma();
					$obj1->load($linha['projeto_observador_programa']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}
					
				elseif ($linha['projeto_observador_perspectiva']){
					if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
					$obj1= new CPerspectiva();
					$obj1->load($linha['projeto_observador_perspectiva']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}
					
				elseif ($linha['projeto_observador_tema']){
					if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
					$obj1= new CTema();
					$obj1->load($linha['projeto_observador_tema']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}
					
				elseif ($linha['projeto_observador_objetivo']){
					if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
					$obj1= new CObjetivo();
					$obj1->load($linha['projeto_observador_objetivo']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}
					
				elseif ($linha['projeto_observador_me']){
					if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
					$obj1= new CMe();
					$obj1->load($linha['projeto_observador_me']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}	
					
				elseif ($linha['projeto_observador_fator']){
					if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
					$obj1= new CFator();
					$obj1->load($linha['projeto_observador_fator']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}
					
				elseif ($linha['projeto_observador_estrategia']){
					if (!($qnt_estrategia++)) require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
					$obj1= new CEstrategia();
					$obj1->load($linha['projeto_observador_estrategia']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}
					
				elseif ($linha['projeto_observador_meta']){
					if (!($qnt_meta++)) require_once BASE_DIR.'/modulos/praticas/meta.class.php';
					$obj1= new CMeta();
					$obj1->load($linha['projeto_observador_meta']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}
		
				elseif ($linha['projeto_observador_risco']){
					if (!($qnt_risco++)) require_once BASE_DIR.'/modulos/praticas/risco_pro.class.php';
					$obj1= new CRisco();
					$obj1->load($linha['projeto_observador_risco']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}	
				
				elseif ($linha['projeto_observador_risco_resposta']){
					if (!($qnt_risco_resposta++)) require_once BASE_DIR.'/modulos/praticas/risco_resposta_pro.class.php';
					$obj1= new CRiscoResposta();
					$obj1->load($linha['projeto_observador_risco_resposta']);
					if (method_exists($obj1, $linha['projeto_observador_metodo'])){
						$obj1->{$linha['projeto_observador_metodo']}();
						}
					}	
					
				elseif ($linha['projeto_observador_pdcl_item']){
					if (!($qnt_pdcl_item++)) require_once BASE_DIR.'/modulos/pdcl/pdcl_item.class.php';
					$obj= new CPDCLItem();
					$obj->load($linha['projeto_observador_pdcl_item']);
					if (method_exists($obj, $linha['projeto_observador_metodo'])){
						$obj->{$linha['projeto_observador_metodo']}();
						}
					}				
					
						
				}
			}	
		
		if ($notificar_responsavel) {
				if ($msg = $obj->notificarResponsavel(1,'gerente', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			if ($notificar_supervisor) {
				if ($msg = $obj->notificarResponsavel(1,'supervisor', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}
			if ($notificar_autoridade) {
				if ($msg = $obj->notificarResponsavel(1,'autoridade', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			if ($notificar_cliente) {
				if ($msg = $obj->notificarResponsavel(1,'cliente', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}	
			if ($notificar_stakeholders) {
				if ($msg = $obj->notificar(1,'stakeholders', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			if ($notificar_contatos) {
				if ($msg = $obj->notificar(1,'contatos', $email_texto)) $Aplic->setMsg($msg, UI_MSG_ERRO);
				}
			if ($email_contatos) {
				if ($msg = $obj->notificar(1,'outros', $email_texto, $email_contatos)) $Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			if ($notificar_designados) {
				if ($msg = $obj->notificar(1,'designados', $email_texto)) $Aplic->setMsg($msg, UI_MSG_ERRO);
				}
			if ($email_extras) {
				if ($msg = $obj->notificar(1,'extras', $email_texto, $email_extras)) $Aplic->setMsg($msg, UI_MSG_ERRO);
				}		
			
		$Aplic->setMsg(ucfirst($config['projeto']).' excluíd'.$config['genero_projeto'], UI_MSG_ALERTA);
		
		if (!$wbs) $Aplic->redirecionar('m=projetos&a=index');
		else echo '<script type="text/javascript">window.close();</script>';
		exit();
		}
	} 
else {
	if (($msg = $obj->armazenar())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=index');
		exit();
		}
	else {
		$nao_eh_novo=getParam($_REQUEST, 'projeto_id', null);
		if (!$obj->projeto_superior) {
			$obj->projeto_superior = $obj->projeto_id;
			$obj->projeto_superior_original = $obj->projeto_id;
			} 
		else {
			$superior_projeto = new CProjeto();
			$superior_projeto->load($obj->projeto_superior);
			$obj->projeto_superior_original = $superior_projeto->projeto_superior_original;
			}
		if (!$obj->projeto_superior_original)	$obj->projeto_superior_original = $obj->projeto_id;
		$obj->armazenar();
		if ($importarTarefa_projetoId=getParam($_REQUEST, 'importarTarefa_projetoId', '0')) {
			$obj->importarTarefas($importarTarefa_projetoId, getParam($_REQUEST, 'importar_data_inicio', ''));
			}

		
		if ($notificar_responsavel) {
			if ($msg = $obj->notificarResponsavel($nao_eh_novo,'gerente', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}		
		if ($notificar_supervisor) {
			if ($msg = $obj->notificarResponsavel($nao_eh_novo,'supervisor', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		if ($notificar_autoridade) {
			if ($msg = $obj->notificarResponsavel($nao_eh_novo,'autoridade', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}		
		if ($notificar_cliente) {
			if ($msg = $obj->notificarResponsavel($nao_eh_novo,'cliente', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}	
		if ($notificar_stakeholders) {
			if ($msg = $obj->notificar($nao_eh_novo,'stakeholders', $email_texto))	$Aplic->setMsg($msg, UI_MSG_ERRO);
			}		
		if ($notificar_contatos) {
			if ($msg = $obj->notificar($nao_eh_novo,'contatos', $email_texto)) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		if ($email_contatos) {
			if ($msg = $obj->notificar($nao_eh_novo,'outros', $email_texto, $email_contatos)) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}		
		if ($notificar_designados) {
			if ($msg = $obj->notificar($nao_eh_novo,'designados', $email_texto)) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		if ($email_extras) {
			if ($msg = $obj->notificar($nao_eh_novo,'extras', $email_texto, $email_extras)) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}	
		
		$Aplic->setMsg($nao_eh_novo ? ucfirst($config['projeto']).' atualizad'.$config['genero_projeto'] : ucfirst($config['projeto']).' inserid'.$config['genero_projeto'], UI_MSG_OK);
		}
	

	$obj->setSequencial();
	
	//SSTI se projeto está concluído deixar tudo na SSTI inativo
	if ($Aplic->modulo_ativo('ssti') && $obj->projeto_status==2){
		
		//checar se está relacionado com uma SSTI
		$sql->adTabela('laudo');
		$sql->adCampo('laudo_id, laudo_ssti');
		$sql->adOnde('laudo_projeto='.(int)$obj->projeto_id);
		$linha=$sql->linha();
		$sql->limpar();
		
		if ($linha['laudo_ssti']){
			$sql->adTabela('ssti');
			$sql->adAtualizar('ssti_ativo', 0);
			$sql->adOnde('ssti_id = '.(int)$linha['laudo_ssti']);
			$sql->exec();
			$sql->limpar();
			}
		
		if ($linha['laudo_id']){
			$sql->adTabela('laudo');
			$sql->adAtualizar('laudo_ativo', 0);
			$sql->adOnde('laudo_id = '.(int)$linha['laudo_id']);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	
	

	if (!$wbs) {
		$sql->adTabela('projeto_gestao');
		$sql->adCampo('projeto_gestao.*');
		$sql->adOnde('projeto_gestao_projeto='.(int)$obj->projeto_id);
		$sql->adOrdem('projeto_gestao_ordem ASC');
		$linha=$sql->linha();
		$sql->limpar();
		
		$sql->adTabela('projeto_gestao');
		$sql->adCampo('count(projeto_gestao_id)');
		$sql->adOnde('projeto_gestao_projeto='.(int)$obj->projeto_id);
		$qnt=$sql->Resultado();
		$sql->limpar();
		
		if ($linha['projeto_gestao_tarefa'] && $qnt==1 && !$projeto_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['projeto_gestao_tarefa'];
		
		elseif ($linha['projeto_gestao_semelhante'] && $qnt==1 && !$projeto_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['projeto_gestao_semelhante'];
		
		elseif ($linha['projeto_gestao_perspectiva'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['projeto_gestao_perspectiva'];
		elseif ($linha['projeto_gestao_tema'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['projeto_gestao_tema'];
		elseif ($linha['projeto_gestao_objetivo'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['projeto_gestao_objetivo'];
		elseif ($linha['projeto_gestao_fator'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['projeto_gestao_fator'];
		elseif ($linha['projeto_gestao_estrategia'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['projeto_gestao_estrategia'];
		elseif ($linha['projeto_gestao_meta'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['projeto_gestao_meta'];
		elseif ($linha['projeto_gestao_pratica'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['projeto_gestao_pratica'];
		elseif ($linha['projeto_gestao_indicador'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['projeto_gestao_indicador'];
		elseif ($linha['projeto_gestao_acao'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['projeto_gestao_acao'];
		elseif ($linha['projeto_gestao_canvas'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['projeto_gestao_canvas'];
		elseif ($linha['projeto_gestao_risco'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['projeto_gestao_risco'];
		elseif ($linha['projeto_gestao_risco_resposta'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['projeto_gestao_risco_resposta'];
		elseif ($linha['projeto_gestao_calendario'] && $qnt==1 && !$projeto_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['projeto_gestao_calendario'];
		elseif ($linha['projeto_gestao_monitoramento'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['projeto_gestao_monitoramento'];
		elseif ($linha['projeto_gestao_ata'] && $qnt==1 && !$projeto_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['projeto_gestao_ata'];
		elseif ($linha['projeto_gestao_mswot'] && $qnt==1 && !$projeto_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['projeto_gestao_mswot'];
		elseif ($linha['projeto_gestao_swot'] && $qnt==1 && !$projeto_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['projeto_gestao_swot'];
		elseif ($linha['projeto_gestao_operativo'] && $qnt==1 && !$projeto_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['projeto_gestao_operativo'];
		elseif ($linha['projeto_gestao_instrumento'] && $qnt==1 && !$projeto_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['projeto_gestao_instrumento'];
		elseif ($linha['projeto_gestao_recurso'] && $qnt==1 && !$projeto_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['projeto_gestao_recurso'];
		elseif ($linha['projeto_gestao_problema'] && $qnt==1 && !$projeto_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['projeto_gestao_problema'];
		elseif ($linha['projeto_gestao_demanda'] && $qnt==1 && !$projeto_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['projeto_gestao_demanda'];
		elseif ($linha['projeto_gestao_programa'] && $qnt==1 && !$projeto_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['projeto_gestao_programa'];
		elseif ($linha['projeto_gestao_licao'] && $qnt==1 && !$projeto_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['projeto_gestao_licao'];
		elseif ($linha['projeto_gestao_evento'] && $qnt==1 && !$projeto_id) $endereco='m=calendario&a=ver&evento_id='.$linha['projeto_gestao_evento'];
		elseif ($linha['projeto_gestao_link'] && $qnt==1 && !$projeto_id) $endereco='m=links&a=ver&link_id='.$linha['projeto_gestao_link'];
		elseif ($linha['projeto_gestao_avaliacao'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['projeto_gestao_avaliacao'];
		elseif ($linha['projeto_gestao_tgn'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['projeto_gestao_tgn'];
		elseif ($linha['projeto_gestao_brainstorm'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['projeto_gestao_brainstorm'];
		elseif ($linha['projeto_gestao_gut'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['projeto_gestao_gut'];
		elseif ($linha['projeto_gestao_causa_efeito'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['projeto_gestao_causa_efeito'];
		elseif ($linha['projeto_gestao_arquivo'] && $qnt==1 && !$projeto_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['projeto_gestao_arquivo'];
		elseif ($linha['projeto_gestao_forum'] && $qnt==1 && !$projeto_id) $endereco='m=foruns&a=ver&forum_id='.$linha['projeto_gestao_forum'];
		elseif ($linha['projeto_gestao_checklist'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['projeto_gestao_checklist'];
		elseif ($linha['projeto_gestao_agenda'] && $qnt==1 && !$projeto_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['projeto_gestao_agenda'];
		elseif ($linha['projeto_gestao_agrupamento'] && $qnt==1 && !$projeto_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['projeto_gestao_agrupamento'];
		elseif ($linha['projeto_gestao_patrocinador'] && $qnt==1 && !$projeto_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['projeto_gestao_patrocinador'];
		elseif ($linha['projeto_gestao_template'] && $qnt==1 && !$projeto_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['projeto_gestao_template'];
		elseif ($linha['projeto_gestao_painel'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['projeto_gestao_painel'];
		elseif ($linha['projeto_gestao_painel_odometro'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['projeto_gestao_painel_odometro'];
		elseif ($linha['projeto_gestao_painel_composicao'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['projeto_gestao_painel_composicao'];
		elseif ($linha['projeto_gestao_tr'] && $qnt==1 && !$projeto_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['projeto_gestao_tr'];
		elseif ($linha['projeto_gestao_me'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['projeto_gestao_me'];
		elseif ($linha['projeto_gestao_acao_item'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['projeto_gestao_acao_item'];
		elseif ($linha['projeto_gestao_beneficio'] && $qnt==1 && !$projeto_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['projeto_gestao_beneficio'];
		elseif ($linha['projeto_gestao_painel_slideshow'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['projeto_gestao_painel_slideshow'];
		elseif ($linha['projeto_gestao_projeto_viabilidade'] && $qnt==1 && !$projeto_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['projeto_gestao_projeto_viabilidade'];
		elseif ($linha['projeto_gestao_projeto_abertura'] && $qnt==1 && !$projeto_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['projeto_gestao_projeto_abertura'];
		elseif ($linha['projeto_gestao_plano_gestao'] && $qnt==1 && !$projeto_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['projeto_gestao_plano_gestao'];
		elseif ($linha['projeto_gestao_ssti'] && $qnt==1 && !$projeto_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['projeto_gestao_ssti'];
		elseif ($linha['projeto_gestao_laudo'] && $qnt==1 && !$projeto_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['projeto_gestao_laudo'];
		elseif ($linha['projeto_gestao_trelo'] && $qnt==1 && !$projeto_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['projeto_gestao_trelo'];
		elseif ($linha['projeto_gestao_trelo_cartao'] && $qnt==1 && !$projeto_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['projeto_gestao_trelo_cartao'];
		elseif ($linha['projeto_gestao_pdcl'] && $qnt==1 && !$projeto_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['projeto_gestao_pdcl'];
		elseif ($linha['projeto_gestao_pdcl_item'] && $qnt==1 && !$projeto_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['projeto_gestao_pdcl_item'];
			
			
		
		else{
            $endereco = 'm=projetos&a=ver&projeto_id='.(int)$obj->projeto_id;
		    if($editar_gantt){
                $endereco = 'm=projetos&a=wbs_completo&projeto_id='.(int)$obj->projeto_id.'baseline_id=0';
                }
            }

		    $Aplic->redirecionar($endereco);
		}
	else echo '<script type="text/javascript">window.close();</script>';
	}
$Aplic->redirecionar('m=projetos');	
exit();
?>