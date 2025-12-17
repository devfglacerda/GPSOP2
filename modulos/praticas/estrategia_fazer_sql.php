<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/praticas/estrategia.class.php');

$sql = new BDConsulta;
$_REQUEST['pg_estrategia_ativo']=(isset($_REQUEST['pg_estrategia_ativo']) ? 1 : 0);

if (isset($_REQUEST['pg_estrategia_percentagem'])) $_REQUEST['pg_estrategia_percentagem']=float_americano($_REQUEST['pg_estrategia_percentagem']);
if (isset($_REQUEST['pg_estrategia_ponto_alvo'])) $_REQUEST['pg_estrategia_ponto_alvo']=float_americano($_REQUEST['pg_estrategia_ponto_alvo']);

$del = intval(getParam($_REQUEST, 'del', 0));
$pg_estrategia_id=getParam($_REQUEST, 'pg_estrategia_id', null);

$pg_estrategia_tipo_pontuacao_antigo=getParam($_REQUEST, 'pg_estrategia_tipo_pontuacao_antigo', null);
$pg_estrategia_percentagem_antigo=getParam($_REQUEST, 'pg_estrategia_percentagem_antigo', null);

$estrategia_perspectiva_antigo=getParam($_REQUEST, 'estrategia_perspectiva_antigo', null);
$estrategia_tema_antigo=getParam($_REQUEST, 'estrategia_tema_antigo', null);
$estrategia_objetivo_antigo=getParam($_REQUEST, 'estrategia_objetivo_antigo', null);
$estrategia_fator_antigo=getParam($_REQUEST, 'estrategia_fator_antigo', null);

$percentagem=getParam($_REQUEST, 'percentagem', null);

if ($Aplic->profissional && !getParam($_REQUEST, 'pg_estrategia_tipo_pontuacao', null)) $_REQUEST['pg_estrategia_percentagem']=$percentagem;


$obj = new CEstrategia();
if ($pg_estrategia_id) $obj->_mensagem = 'atualizad'.$config['genero_iniciativa'];
else $obj->_mensagem = 'adicionad'.$config['genero_iniciativa'];

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=estrategia_lista');
	}
$Aplic->setMsg('Iniciativa estratégia');
if ($del) {
	
	if ($Aplic->profissional){
		$sql->adTabela('estrategia_observador');
		$sql->adCampo('estrategia_observador.*');
		$sql->adOnde('estrategia_observador_estrategia ='.(int)$pg_estrategia_id);
		$lista = $sql->lista();
		$sql->limpar();
		}
	else $lista=array();
	$obj->load($pg_estrategia_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=praticas&a=estrategia_ver&pg_estrategia_id='.$pg_estrategia_id);
		} 
	else {
		
		if ($Aplic->profissional){
			
			$qnt_objetivo=0;
			$qnt_me=0;
			$qnt_fator=0;
			
			foreach($lista as $linha){
				if ($linha['estrategia_observador_perspectiva']){
					if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
					$obj= new CPerspectiva();
					$obj->load($linha['estrategia_observador_perspectiva']);
					if (method_exists($obj, $linha['estrategia_observador_metodo'])){
						$obj->{$linha['estrategia_observador_metodo']}();
						}
					}	
				elseif ($linha['estrategia_observador_tema']){
					if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
					$obj= new CTema();
					$obj->load($linha['estrategia_observador_tema']);
					if (method_exists($obj, $linha['estrategia_observador_metodo'])){
						$obj->{$linha['estrategia_observador_metodo']}();
						}
					}	
				elseif ($linha['estrategia_observador_objetivo']){
					if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
					$obj= new CObjetivo();
					$obj->load($linha['estrategia_observador_objetivo']);
					if (method_exists($obj, $linha['estrategia_observador_metodo'])){
						$obj->{$linha['estrategia_observador_metodo']}();
						}
					}	
				elseif ($linha['estrategia_observador_me']){
					if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
					$obj= new CMe();
					$obj->load($linha['estrategia_observador_me']);
					if (method_exists($obj, $linha['estrategia_observador_metodo'])){
						$obj->{$linha['estrategia_observador_metodo']}();
						}
					}	
				elseif ($linha['estrategia_observador_fator']){
					if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
					$obj= new CFator();
					$obj->load($linha['estrategia_observador_fator']);
					if (method_exists($obj, $linha['estrategia_observador_metodo'])){
						$obj->{$linha['estrategia_observador_metodo']}();
						}
					}	
				}	
			}
	
		$Aplic->setMsg('excluída', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=estrategia_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($pg_estrategia_id ? 'atualizad'.$config['genero_iniciativa'] : 'adicionad'.$config['genero_iniciativa'], UI_MSG_OK, true);
	}
	
if ($Aplic->profissional){	
	
	
	$pontuacao=$obj->calculo_percentagem();
	$obj->disparo_observador('fisico');	
	}	


$sql->adTabela('estrategia_gestao');
$sql->adCampo('estrategia_gestao.*');
$sql->adOnde('estrategia_gestao_estrategia='.(int)$obj->pg_estrategia_id);
$sql->adOrdem('estrategia_gestao_ordem ASC');
$linha=$sql->linha();
$sql->limpar();

$sql->adTabela('estrategia_gestao');
$sql->adCampo('count(estrategia_gestao_id)');
$sql->adOnde('estrategia_gestao_estrategia='.(int)$obj->pg_estrategia_id);
$qnt=$sql->Resultado();
$sql->limpar();

if ($linha['estrategia_gestao_tarefa'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['estrategia_gestao_tarefa'];
elseif ($linha['estrategia_gestao_projeto'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['estrategia_gestao_projeto'];
elseif ($linha['estrategia_gestao_perspectiva'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['estrategia_gestao_perspectiva'];
elseif ($linha['estrategia_gestao_tema'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['estrategia_gestao_tema'];
elseif ($linha['estrategia_gestao_objetivo'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['estrategia_gestao_objetivo'];
elseif ($linha['estrategia_gestao_fator'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['estrategia_gestao_fator'];

elseif ($linha['estrategia_gestao_semelhante'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['estrategia_gestao_semelhante'];

elseif ($linha['estrategia_gestao_meta'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['estrategia_gestao_meta'];
elseif ($linha['estrategia_gestao_pratica'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['estrategia_gestao_pratica'];
elseif ($linha['estrategia_gestao_indicador'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['estrategia_gestao_indicador'];
elseif ($linha['estrategia_gestao_acao'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['estrategia_gestao_acao'];
elseif ($linha['estrategia_gestao_canvas'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['estrategia_gestao_canvas'];
elseif ($linha['estrategia_gestao_risco'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['estrategia_gestao_risco'];
elseif ($linha['estrategia_gestao_risco_resposta'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['estrategia_gestao_risco_resposta'];
elseif ($linha['estrategia_gestao_calendario'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['estrategia_gestao_calendario'];
elseif ($linha['estrategia_gestao_monitoramento'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['estrategia_gestao_monitoramento'];
elseif ($linha['estrategia_gestao_ata'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['estrategia_gestao_ata'];
elseif ($linha['estrategia_gestao_mswot'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['estrategia_gestao_mswot'];
elseif ($linha['estrategia_gestao_swot'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['estrategia_gestao_swot'];
elseif ($linha['estrategia_gestao_operativo'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['estrategia_gestao_operativo'];
elseif ($linha['estrategia_gestao_instrumento'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['estrategia_gestao_instrumento'];
elseif ($linha['estrategia_gestao_recurso'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['estrategia_gestao_recurso'];
elseif ($linha['estrategia_gestao_problema'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['estrategia_gestao_problema'];
elseif ($linha['estrategia_gestao_demanda'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['estrategia_gestao_demanda'];
elseif ($linha['estrategia_gestao_programa'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['estrategia_gestao_programa'];
elseif ($linha['estrategia_gestao_licao'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['estrategia_gestao_licao'];
elseif ($linha['estrategia_gestao_evento'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=calendario&a=ver&evento_id='.$linha['estrategia_gestao_evento'];
elseif ($linha['estrategia_gestao_link'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=links&a=ver&link_id='.$linha['estrategia_gestao_link'];
elseif ($linha['estrategia_gestao_avaliacao'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['estrategia_gestao_avaliacao'];
elseif ($linha['estrategia_gestao_tgn'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['estrategia_gestao_tgn'];
elseif ($linha['estrategia_gestao_brainstorm'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['estrategia_gestao_brainstorm'];
elseif ($linha['estrategia_gestao_gut'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['estrategia_gestao_gut'];
elseif ($linha['estrategia_gestao_causa_efeito'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['estrategia_gestao_causa_efeito'];
elseif ($linha['estrategia_gestao_arquivo'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['estrategia_gestao_arquivo'];
elseif ($linha['estrategia_gestao_forum'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=foruns&a=ver&forum_id='.$linha['estrategia_gestao_forum'];
elseif ($linha['estrategia_gestao_checklist'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['estrategia_gestao_checklist'];
elseif ($linha['estrategia_gestao_agenda'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['estrategia_gestao_agenda'];
elseif ($linha['estrategia_gestao_agrupamento'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['estrategia_gestao_agrupamento'];
elseif ($linha['estrategia_gestao_patrocinador'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['estrategia_gestao_patrocinador'];
elseif ($linha['estrategia_gestao_template'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['estrategia_gestao_template'];
elseif ($linha['estrategia_gestao_painel'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['estrategia_gestao_painel'];
elseif ($linha['estrategia_gestao_painel_odometro'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['estrategia_gestao_painel_odometro'];
elseif ($linha['estrategia_gestao_painel_composicao'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['estrategia_gestao_painel_composicao'];
elseif ($linha['estrategia_gestao_tr'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['estrategia_gestao_tr'];
elseif ($linha['estrategia_gestao_me'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['estrategia_gestao_me'];
elseif ($linha['estrategia_gestao_acao_item'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['estrategia_gestao_acao_item'];
elseif ($linha['estrategia_gestao_beneficio'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['estrategia_gestao_beneficio'];
elseif ($linha['estrategia_gestao_painel_slideshow'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['estrategia_gestao_painel_slideshow'];
elseif ($linha['estrategia_gestao_projeto_viabilidade'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['estrategia_gestao_projeto_viabilidade'];
elseif ($linha['estrategia_gestao_projeto_abertura'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['estrategia_gestao_projeto_abertura'];
elseif ($linha['estrategia_gestao_plano_gestao'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['estrategia_gestao_plano_gestao'];
elseif ($linha['estrategia_gestao_ssti'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['estrategia_gestao_ssti'];
elseif ($linha['estrategia_gestao_laudo'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['estrategia_gestao_laudo'];
elseif ($linha['estrategia_gestao_trelo'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['estrategia_gestao_trelo'];
elseif ($linha['estrategia_gestao_trelo_cartao'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['estrategia_gestao_trelo_cartao'];
elseif ($linha['estrategia_gestao_pdcl'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['estrategia_gestao_pdcl'];
elseif ($linha['estrategia_gestao_pdcl_item'] && $qnt==1 && !$pg_estrategia_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['estrategia_gestao_pdcl_item'];

else $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.(int)$obj->pg_estrategia_id;
$Aplic->redirecionar($endereco);
?>