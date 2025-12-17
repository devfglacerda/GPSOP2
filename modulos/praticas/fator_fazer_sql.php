<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/fator.class.php');

$sql = new BDConsulta;

$_REQUEST['fator_ativo']=(isset($_REQUEST['fator_ativo']) ? 1 : 0);

if (isset($_REQUEST['fator_percentagem'])) $_REQUEST['fator_percentagem']=float_americano($_REQUEST['fator_percentagem']);
if (isset($_REQUEST['fator_ponto_alvo'])) $_REQUEST['fator_ponto_alvo']=float_americano($_REQUEST['fator_ponto_alvo']);


$del = intval(getParam($_REQUEST, 'del', 0));
$fator_id=getParam($_REQUEST, 'fator_id', null);

$fator_tipo_pontuacao_antigo=getParam($_REQUEST, 'fator_tipo_pontuacao_antigo', null);
$fator_percentagem_antigo=getParam($_REQUEST, 'fator_percentagem_antigo', null);
$fator_objetivo_antigo=getParam($_REQUEST, 'fator_objetivo_antigo', null);
$percentagem=getParam($_REQUEST, 'percentagem', null);

if (!$Aplic->profissional || ($Aplic->profissional && !getParam($_REQUEST, 'fator_tipo_pontuacao', ''))) $_REQUEST['fator_percentagem']=$percentagem;

if ($Aplic->profissional && $fator_id && (getParam($_REQUEST, 'fator_tipo_pontuacao', null)==$fator_tipo_pontuacao_antigo)){
	$fator_media=getParam($_REQUEST, 'fator_media', null);
	$fator_media = unserialize($fator_media);
	
	$sql->adTabela('fator_media');
	$sql->adCampo('fator_media_projeto AS projeto, fator_media_acao AS acao, fator_media_peso AS peso, fator_media_ponto AS ponto, fator_media_estrategia AS estrategia');
	$sql->adOnde('fator_media_fator='.(int)$fator_id);
	$sql->adOnde('fator_media_tipo=\''.getParam($_REQUEST, 'fator_tipo_pontuacao', null).'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	
	if (count($fator_media) != count($lista)) $alteracao_fator_media=true;
	else {
		$igual=0;
		foreach ($lista as $linha) {
			foreach ($fator_media as $linha_antiga) {
				if (($linha_antiga['projeto']==$linha['projeto']) && ($linha_antiga['acao']==$linha['acao']) && ($linha_antiga['estrategia']==$linha['estrategia']) && ($linha_antiga['peso']==$linha['peso']) && ($linha_antiga['ponto']==$linha['ponto'])) $igual++;
				}
			}
		$alteracao_fator_media=($igual == count($fator_media) ? false : true);
		}
	}
else $alteracao_fator_media=false; 	

$obj = new CFator();
if ($fator_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=fator_lista');
	}
$Aplic->setMsg(ucfirst($config['fator']));

if ($del) {
	$obj->load($fator_id);
	
	if ($Aplic->profissional){
			$sql->adTabela('fator_observador');
			$sql->adCampo('fator_observador.*');
			$sql->adOnde('fator_observador_fator ='.(int)$fator_id);
			$lista = $sql->lista();
			$sql->limpar();
			$qnt_objetivo=0;
			$qnt_me=0;
			}
	
	
	
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=fator_ver&fator_id='.$fator_id);
		} 
	else {
		if ($Aplic->profissional){
			foreach($lista as $linha){
				if ($linha['fator_observador_objetivo']){
					if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
					$obj= new CObjetivo();
					$obj->load($linha['fator_observador_objetivo']);
					if (method_exists($obj, $linha['fator_observador_metodo'])){
						$obj->{$linha['fator_observador_metodo']}();
						}
					}	
				elseif ($linha['fator_observador_me']){
					if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
					$obj= new CMe();
					$obj->load($linha['fator_observador_me']);
					if (method_exists($obj, $linha['fator_observador_metodo'])){
						$obj->{$linha['fator_observador_metodo']}();
						}
					}	
				}	
			}

		
		$Aplic->setMsg('excluíd'.$config['genero_fator'], UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=fator_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($fator_id ? 'atualizad'.$config['genero_fator'] : 'adicionad'.$config['genero_fator'], UI_MSG_OK, true);
	}
	
	
if ($Aplic->profissional){	
	$pontuacao=$obj->calculo_percentagem();	
	$obj->disparo_observador('fisico');		
	}


$sql->adTabela('fator_gestao');
$sql->adCampo('fator_gestao.*');
$sql->adOnde('fator_gestao_fator='.(int)$obj->fator_id);
$sql->adOrdem('fator_gestao_ordem ASC');
$linha=$sql->linha();
$sql->limpar();

$sql->adTabela('fator_gestao');
$sql->adCampo('count(fator_gestao_id)');
$sql->adOnde('fator_gestao_fator='.(int)$obj->fator_id);
$qnt=$sql->Resultado();
$sql->limpar();

if ($linha['fator_gestao_tarefa'] && $qnt==1 && !$fator_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['fator_gestao_tarefa'];
elseif ($linha['fator_gestao_projeto'] && $qnt==1 && !$fator_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['fator_gestao_projeto'];
elseif ($linha['fator_gestao_perspectiva'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['fator_gestao_perspectiva'];
elseif ($linha['fator_gestao_tema'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['fator_gestao_tema'];
elseif ($linha['fator_gestao_objetivo'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['fator_gestao_objetivo'];

elseif ($linha['fator_gestao_semelhante'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['fator_gestao_semelhante'];

elseif ($linha['fator_gestao_estrategia'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['fator_gestao_estrategia'];
elseif ($linha['fator_gestao_meta'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['fator_gestao_meta'];
elseif ($linha['fator_gestao_pratica'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['fator_gestao_pratica'];
elseif ($linha['fator_gestao_indicador'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['fator_gestao_indicador'];
elseif ($linha['fator_gestao_acao'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['fator_gestao_acao'];
elseif ($linha['fator_gestao_canvas'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['fator_gestao_canvas'];
elseif ($linha['fator_gestao_risco'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['fator_gestao_risco'];
elseif ($linha['fator_gestao_risco_resposta'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['fator_gestao_risco_resposta'];
elseif ($linha['fator_gestao_calendario'] && $qnt==1 && !$fator_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['fator_gestao_calendario'];
elseif ($linha['fator_gestao_monitoramento'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['fator_gestao_monitoramento'];
elseif ($linha['fator_gestao_ata'] && $qnt==1 && !$fator_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['fator_gestao_ata'];
elseif ($linha['fator_gestao_mswot'] && $qnt==1 && !$fator_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['fator_gestao_mswot'];
elseif ($linha['fator_gestao_swot'] && $qnt==1 && !$fator_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['fator_gestao_swot'];
elseif ($linha['fator_gestao_operativo'] && $qnt==1 && !$fator_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['fator_gestao_operativo'];
elseif ($linha['fator_gestao_instrumento'] && $qnt==1 && !$fator_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['fator_gestao_instrumento'];
elseif ($linha['fator_gestao_recurso'] && $qnt==1 && !$fator_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['fator_gestao_recurso'];
elseif ($linha['fator_gestao_problema'] && $qnt==1 && !$fator_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['fator_gestao_problema'];
elseif ($linha['fator_gestao_demanda'] && $qnt==1 && !$fator_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['fator_gestao_demanda'];
elseif ($linha['fator_gestao_programa'] && $qnt==1 && !$fator_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['fator_gestao_programa'];
elseif ($linha['fator_gestao_licao'] && $qnt==1 && !$fator_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['fator_gestao_licao'];
elseif ($linha['fator_gestao_evento'] && $qnt==1 && !$fator_id) $endereco='m=calendario&a=ver&evento_id='.$linha['fator_gestao_evento'];
elseif ($linha['fator_gestao_link'] && $qnt==1 && !$fator_id) $endereco='m=links&a=ver&link_id='.$linha['fator_gestao_link'];
elseif ($linha['fator_gestao_avaliacao'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['fator_gestao_avaliacao'];
elseif ($linha['fator_gestao_tgn'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['fator_gestao_tgn'];
elseif ($linha['fator_gestao_brainstorm'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['fator_gestao_brainstorm'];
elseif ($linha['fator_gestao_gut'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['fator_gestao_gut'];
elseif ($linha['fator_gestao_causa_efeito'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['fator_gestao_causa_efeito'];
elseif ($linha['fator_gestao_arquivo'] && $qnt==1 && !$fator_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['fator_gestao_arquivo'];
elseif ($linha['fator_gestao_forum'] && $qnt==1 && !$fator_id) $endereco='m=foruns&a=ver&forum_id='.$linha['fator_gestao_forum'];
elseif ($linha['fator_gestao_checklist'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['fator_gestao_checklist'];
elseif ($linha['fator_gestao_agenda'] && $qnt==1 && !$fator_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['fator_gestao_agenda'];
elseif ($linha['fator_gestao_agrupamento'] && $qnt==1 && !$fator_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['fator_gestao_agrupamento'];
elseif ($linha['fator_gestao_patrocinador'] && $qnt==1 && !$fator_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['fator_gestao_patrocinador'];
elseif ($linha['fator_gestao_template'] && $qnt==1 && !$fator_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['fator_gestao_template'];
elseif ($linha['fator_gestao_painel'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['fator_gestao_painel'];
elseif ($linha['fator_gestao_painel_odometro'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['fator_gestao_painel_odometro'];
elseif ($linha['fator_gestao_painel_composicao'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['fator_gestao_painel_composicao'];
elseif ($linha['fator_gestao_tr'] && $qnt==1 && !$fator_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['fator_gestao_tr'];
elseif ($linha['fator_gestao_me'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['fator_gestao_me'];
elseif ($linha['fator_gestao_acao_item'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['fator_gestao_acao_item'];
elseif ($linha['fator_gestao_beneficio'] && $qnt==1 && !$fator_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['fator_gestao_beneficio'];
elseif ($linha['fator_gestao_painel_slideshow'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['fator_gestao_painel_slideshow'];
elseif ($linha['fator_gestao_projeto_viabilidade'] && $qnt==1 && !$fator_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['fator_gestao_projeto_viabilidade'];
elseif ($linha['fator_gestao_projeto_abertura'] && $qnt==1 && !$fator_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['fator_gestao_projeto_abertura'];
elseif ($linha['fator_gestao_plano_gestao'] && $qnt==1 && !$fator_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['fator_gestao_plano_gestao'];
elseif ($linha['fator_gestao_ssti'] && $qnt==1 && !$fator_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['fator_gestao_ssti'];
elseif ($linha['fator_gestao_laudo'] && $qnt==1 && !$fator_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['fator_gestao_laudo'];
elseif ($linha['fator_gestao_trelo'] && $qnt==1 && !$fator_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['fator_gestao_trelo'];
elseif ($linha['fator_gestao_trelo_cartao'] && $qnt==1 && !$fator_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['fator_gestao_trelo_cartao'];
elseif ($linha['fator_gestao_pdcl'] && $qnt==1 && !$fator_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['fator_gestao_pdcl'];
elseif ($linha['fator_gestao_pdcl_item'] && $qnt==1 && !$fator_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['fator_gestao_pdcl_item'];

else $endereco='m=praticas&a=fator_ver&fator_id='.(int)$obj->fator_id;
$Aplic->redirecionar($endereco);
?>