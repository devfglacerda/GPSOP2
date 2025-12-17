<?php

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


$assinatura_id=getParam($_REQUEST, 'assinatura_id', null);
$condicao=getParam($_REQUEST, 'condicao', null);

$tarefa_id=getParam($_REQUEST, 'tarefa_id', null);
$projeto_id=getParam($_REQUEST, 'projeto_id', null);
$pg_perspectiva_id=getParam($_REQUEST, 'pg_perspectiva_id', null);
$tema_id=getParam($_REQUEST, 'tema_id', null);
$objetivo_id=getParam($_REQUEST, 'objetivo_id', null);
$fator_id=getParam($_REQUEST, 'fator_id', null);
$pg_estrategia_id=getParam($_REQUEST, 'pg_estrategia_id', null);
$pg_meta_id=getParam($_REQUEST, 'pg_meta_id', null);
$pratica_id=getParam($_REQUEST, 'pratica_id', null);
$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', null);
$plano_acao_id=getParam($_REQUEST, 'plano_acao_id', null);
$canvas_id=getParam($_REQUEST, 'canvas_id', null);
$risco_id=getParam($_REQUEST, 'risco_id', null);
$risco_resposta_id=getParam($_REQUEST, 'risco_resposta_id', null);
$calendario_id=getParam($_REQUEST, 'calendario_id', null);
$monitoramento_id=getParam($_REQUEST, 'monitoramento_id', null);
$ata_id=getParam($_REQUEST, 'ata_id', null);
$mswot_id=getParam($_REQUEST, 'mswot_id', null);
$swot_id=getParam($_REQUEST, 'swot_id', null);
$operativo_id=getParam($_REQUEST, 'operativo_id', null);
$instrumento_id=getParam($_REQUEST, 'instrumento_id', null);
$recurso_id=getParam($_REQUEST, 'recurso_id', null);
$problema_id=getParam($_REQUEST, 'problema_id', null);
$demanda_id=getParam($_REQUEST, 'demanda_id', null);
$programa_id=getParam($_REQUEST, 'programa_id', null);
$licao_id=getParam($_REQUEST, 'licao_id', null);
$evento_id=getParam($_REQUEST, 'evento_id', null);
$link_id=getParam($_REQUEST, 'link_id', null);
$avaliacao_id=getParam($_REQUEST, 'avaliacao_id', null);
$tgn_id=getParam($_REQUEST, 'tgn_id', null);
$brainstorm_id=getParam($_REQUEST, 'brainstorm_id', null);
$gut_id=getParam($_REQUEST, 'gut_id', null);
$causa_efeito_id=getParam($_REQUEST, 'causa_efeito_id', null);
$arquivo_id=getParam($_REQUEST, 'arquivo_id', null);
$forum_id=getParam($_REQUEST, 'forum_id', null);
$checklist_id=getParam($_REQUEST, 'checklist_id', null);
$agenda_id=getParam($_REQUEST, 'agenda_id', null);
$agrupamento_id=getParam($_REQUEST, 'agrupamento_id', null);
$patrocinador_id=getParam($_REQUEST, 'patrocinador_id', null);
$template_id=getParam($_REQUEST, 'template_id', null);
$painel_id=getParam($_REQUEST, 'painel_id', null);
$painel_odometro_id=getParam($_REQUEST, 'painel_odometro_id', null);
$painel_composicao_id=getParam($_REQUEST, 'painel_composicao_id', null);
$tr_id=getParam($_REQUEST, 'tr_id', null);
$me_id=getParam($_REQUEST, 'me_id', null);
$plano_acao_item_id=getParam($_REQUEST, 'plano_acao_item_id', null);
$beneficio_id=getParam($_REQUEST, 'beneficio_id', null);
$painel_slideshow_id=getParam($_REQUEST, 'painel_slideshow_id', null);
$projeto_viabilidade_id=getParam($_REQUEST, 'projeto_viabilidade_id', null);
$projeto_abertura_id=getParam($_REQUEST, 'projeto_abertura_id', null);
$pg_id=getParam($_REQUEST, 'pg_id', null);
$aviso_id=getParam($_REQUEST, 'aviso_id', null);
$modelo_id=getParam($_REQUEST, 'modelo_id', null);
$ssti_id=getParam($_REQUEST, 'ssti_id', null);
$laudo_id=getParam($_REQUEST, 'laudo_id', null);




if ($ssti_id||$laudo_id) $condicao='nao_aprovou';



$sql = new BDConsulta();

$sql->adTabela('assinatura');
$sql->adCampo('assinatura_id, assinatura_funcao');
$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
$sql->adOnde('assinatura_bloqueado!=1');
if ($condicao=='nao_aprovou') {
	$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao','assinatura_atesta_opcao_id=assinatura_atesta_opcao');
	$sql->adOnde('assinatura_atesta_opcao IS NULL OR assinatura_atesta_opcao_aprova!=1');
	}

if ($tarefa_id) $sql->adOnde('assinatura_tarefa='.(int)$tarefa_id);
elseif ($projeto_id) $sql->adOnde('assinatura_projeto='.(int)$projeto_id);
elseif ($pg_perspectiva_id) $sql->adOnde('assinatura_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('assinatura_tema='.(int)$tema_id);
elseif ($objetivo_id) $sql->adOnde('assinatura_objetivo='.(int)$objetivo_id);
elseif ($fator_id) $sql->adOnde('assinatura_fator='.(int)$fator_id);
elseif ($pg_estrategia_id) $sql->adOnde('assinatura_estrategia='.(int)$pg_estrategia_id);
elseif ($pg_meta_id) $sql->adOnde('assinatura_meta='.(int)$pg_meta_id);
elseif ($pratica_id) $sql->adOnde('assinatura_pratica='.(int)$pratica_id);
elseif ($pratica_indicador_id) $sql->adOnde('assinatura_indicador='.(int)$pratica_indicador_id);
elseif ($plano_acao_id) $sql->adOnde('assinatura_acao='.(int)$plano_acao_id);
elseif ($canvas_id) $sql->adOnde('assinatura_canvas='.(int)$canvas_id);
elseif ($risco_id) $sql->adOnde('assinatura_risco='.(int)$risco_id);
elseif ($risco_resposta_id) $sql->adOnde('assinatura_risco_resposta='.(int)$risco_resposta_id);
elseif ($calendario_id) $sql->adOnde('assinatura_calendario='.(int)$calendario_id);
elseif ($monitoramento_id) $sql->adOnde('assinatura_monitoramento='.(int)$monitoramento_id);
elseif ($ata_id) $sql->adOnde('assinatura_ata='.(int)$ata_id);
elseif ($mswot_id) $sql->adOnde('assinatura_mswot='.(int)$mswot_id);
elseif ($swot_id) $sql->adOnde('assinatura_swot='.(int)$swot_id);
elseif ($operativo_id) $sql->adOnde('assinatura_operativo='.(int)$operativo_id);
elseif ($instrumento_id) $sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
elseif ($recurso_id) $sql->adOnde('assinatura_recurso='.(int)$recurso_id);
elseif ($problema_id) $sql->adOnde('assinatura_problema='.(int)$problema_id);
elseif ($demanda_id) $sql->adOnde('assinatura_demanda='.(int)$demanda_id);
elseif ($programa_id) $sql->adOnde('assinatura_programa='.(int)$programa_id);
elseif ($licao_id) $sql->adOnde('assinatura_licao='.(int)$licao_id);
elseif ($evento_id) $sql->adOnde('assinatura_evento='.(int)$evento_id);
elseif ($link_id) $sql->adOnde('assinatura_link='.(int)$link_id);
elseif ($avaliacao_id) $sql->adOnde('assinatura_avaliacao='.(int)$avaliacao_id);
elseif ($tgn_id) $sql->adOnde('assinatura_tgn='.(int)$tgn_id);
elseif ($brainstorm_id) $sql->adOnde('assinatura_brainstorm='.(int)$brainstorm_id);
elseif ($gut_id) $sql->adOnde('assinatura_gut='.(int)$gut_id);
elseif ($causa_efeito_id) $sql->adOnde('assinatura_causa_efeito='.(int)$causa_efeito_id);
elseif ($arquivo_id) $sql->adOnde('assinatura_arquivo='.(int)$arquivo_id);
elseif ($forum_id) $sql->adOnde('assinatura_forum='.(int)$forum_id);
elseif ($checklist_id) $sql->adOnde('assinatura_checklist='.(int)$checklist_id);
elseif ($agenda_id) $sql->adOnde('assinatura_agenda='.(int)$agenda_id);
elseif ($agrupamento_id) $sql->adOnde('assinatura_agrupamento='.(int)$agrupamento_id);
elseif ($patrocinador_id) $sql->adOnde('assinatura_patrocinador='.(int)$patrocinador_id);
elseif ($template_id) $sql->adOnde('assinatura_template='.(int)$template_id);
elseif ($painel_id) $sql->adOnde('assinatura_painel='.(int)$painel_id);
elseif ($painel_odometro_id) $sql->adOnde('assinatura_painel_odometro='.(int)$painel_odometro_id);
elseif ($painel_composicao_id) $sql->adOnde('assinatura_painel_composicao='.(int)$painel_composicao_id);
elseif ($tr_id) $sql->adOnde('assinatura_tr='.(int)$tr_id);
elseif ($me_id) $sql->adOnde('assinatura_me='.(int)$me_id);
elseif ($plano_acao_item_id) $sql->adOnde('assinatura_plano_acao_item='.(int)$plano_acao_item_id);
elseif ($beneficio_id) $sql->adOnde('assinatura_beneficio='.(int)$beneficio_id);
elseif ($painel_slideshow_id) $sql->adOnde('assinatura_painel_slideshow='.(int)$painel_slideshow_id);
elseif ($projeto_viabilidade_id) $sql->adOnde('assinatura_viabilidade='.(int)$projeto_viabilidade_id);
elseif ($projeto_abertura_id) $sql->adOnde('assinatura_abertura='.(int)$projeto_abertura_id);
elseif ($pg_id) $sql->adOnde('assinatura_plano_gestao='.(int)$pg_id);
elseif ($aviso_id) $sql->adOnde('assinatura_aviso='.(int)$aviso_id);
elseif ($modelo_id) $sql->adOnde('assinatura_modelo='.(int)$modelo_id);
elseif ($ssti_id) $sql->adOnde('assinatura_ssti='.(int)$ssti_id);
elseif ($laudo_id) $sql->adOnde('assinatura_laudo='.(int)$laudo_id);
$funcoes=$sql->listaVetorChave('assinatura_id', 'assinatura_funcao');
$sql->limpar();

$qnt=0;
$primeira_chave=0;
foreach($funcoes as $chave => $valor){
	if (!$qnt) $primeira_chave=$chave;
	$qnt++;
	if (!$valor) $funcoes[$chave]=$qnt.'ª função';
	}

if (count($funcoes)==1) {
	$assinatura_id=$primeira_chave;
	if ($funcoes[$chave]=='1ª função') $funcoes=array();
	}





$funcoes=array(''=>'')+$funcoes;

if ($assinatura_id){
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_atesta, assinatura_atesta_opcao, assinatura_observacao, assinatura_data, assinatura_aprovou');
	$sql->adOnde('assinatura_id='.(int)$assinatura_id);
	$atesta = $sql->linha();
	$sql->limpar();
	}

if ($tarefa_id) $endereco='m=tarefas&a=ver&tarefa_id='.$tarefa_id;
elseif ($projeto_id) $endereco='m=projetos&a=ver&projeto_id='.$projeto_id;
elseif ($pg_perspectiva_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id;
elseif ($tema_id) $endereco='m=praticas&a=tema_ver&tema_id='.$tema_id;
elseif ($objetivo_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$objetivo_id;
elseif ($fator_id) $endereco='m=praticas&a=fator_ver&fator_id='.$fator_id;
elseif ($pg_estrategia_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$pg_estrategia_id;
elseif ($pg_meta_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$pg_meta_id;
elseif ($pratica_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$pratica_id;
elseif ($pratica_indicador_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id;
elseif ($plano_acao_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$plano_acao_id;
elseif ($canvas_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id;
elseif ($risco_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$risco_id;
elseif ($risco_resposta_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id;
elseif ($calendario_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id;
elseif ($monitoramento_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id;
elseif ($ata_id) $endereco='m=atas&a=ata_ver&ata_id='.$ata_id;
elseif ($mswot_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$mswot_id;
elseif ($swot_id) $endereco='m=swot&a=swot_ver&swot_id='.$swot_id;
elseif ($operativo_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$operativo_id;
elseif ($instrumento_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$instrumento_id;
elseif ($recurso_id) $endereco='m=recursos&a=ver&recurso_id='.$recurso_id;
elseif ($problema_id) $endereco='m=problema&a=problema_ver&problema_id='.$problema_id;
elseif ($demanda_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$demanda_id;
elseif ($programa_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$programa_id;
elseif ($licao_id) $endereco='m=projetos&a=licao_ver&licao_id='.$licao_id;
elseif ($evento_id) $endereco='m=calendario&a=ver&evento_id='.$evento_id;
elseif ($link_id) $endereco='m=links&a=ver&link_id='.$link_id;
elseif ($avaliacao_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$avaliacao_id;
elseif ($tgn_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id;
elseif ($brainstorm_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$brainstorm_id;
elseif ($gut_id) $endereco='m=praticas&a=gut_ver&gut_id='.$gut_id;
elseif ($causa_efeito_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$causa_efeito_id;
elseif ($arquivo_id) $endereco='m=arquivos&a=ver&arquivo_id='.$arquivo_id;
elseif ($forum_id) $endereco='m=foruns&a=ver&forum_id='.$forum_id;
elseif ($checklist_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$checklist_id;
elseif ($agenda_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$agenda_id;
elseif ($agrupamento_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$agrupamento_id;
elseif ($patrocinador_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$patrocinador_id;
elseif ($template_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$template_id;
elseif ($painel_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$painel_id;
elseif ($painel_odometro_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$painel_odometro_id;
elseif ($painel_composicao_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$painel_composicao_id;
elseif ($tr_id) $endereco='m=tr&a=tr_ver&tr_id='.$tr_id;
elseif ($me_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$me_id;
elseif ($plano_acao_item_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$plano_acao_item_id;
elseif ($beneficio_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id;
elseif ($painel_slideshow_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$painel_slideshow_id;
elseif ($projeto_viabilidade_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$projeto_viabilidade_id;
elseif ($projeto_abertura_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id;
elseif ($pg_id) $endereco='m=praticas&u=gestao&a=menu&pg_id='.$pg_id;
elseif ($aviso_id) $endereco='m=aviso&&a=aviso_ver&aviso_id='.$aviso_id;
elseif ($modelo_id) $endereco='m=email&&a=modelo_ver&modelo_id='.$modelo_id;
elseif ($ssti_id) $endereco='m=ssti&&a=ssti_ver&ssti_id='.$ssti_id;
elseif ($laudo_id) $endereco='m=ssti&&a=laudo_ver&laudo_id='.$laudo_id;




if (getParam($_REQUEST, 'gravar', null)){
	
	$assinatura_aprovou=getParam($_REQUEST, 'assinatura_aprovou', null);
	
	$sql->adTabela('assinatura');
	$sql->adAtualizar('assinatura_atesta_opcao', getParam($_REQUEST, 'assinatura_atesta_opcao', null));
	$sql->adAtualizar('assinatura_observacao', getParam($_REQUEST, 'obs', null));
	$sql->adAtualizar('assinatura_aprovou', $assinatura_aprovou);
	$sql->adAtualizar('assinatura_data', date('Y-m-d H:i:s'));
	$sql->adOnde('assinatura_id = '.(int)$assinatura_id);
	$sql->exec();
	$sql->limpar();
	
	//checar se está aprovado
	
	//escolheu despacho negativo
	$sql->adTabela('assinatura');
	$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
	$sql->adCampo('count(assinatura_id)');
	if ($tarefa_id) $sql->adOnde('assinatura_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('assinatura_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('assinatura_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('assinatura_tema='.(int)$tema_id);
	elseif ($objetivo_id) $sql->adOnde('assinatura_objetivo='.(int)$objetivo_id);
	elseif ($fator_id) $sql->adOnde('assinatura_fator='.(int)$fator_id);
	elseif ($pg_estrategia_id) $sql->adOnde('assinatura_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('assinatura_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('assinatura_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('assinatura_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('assinatura_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('assinatura_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('assinatura_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('assinatura_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('assinatura_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('assinatura_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('assinatura_ata='.(int)$ata_id);
	elseif ($mswot_id) $sql->adOnde('assinatura_mswot='.(int)$mswot_id);
	elseif ($swot_id) $sql->adOnde('assinatura_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('assinatura_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('assinatura_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('assinatura_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('assinatura_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('assinatura_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('assinatura_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('assinatura_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('assinatura_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('assinatura_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('assinatura_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('assinatura_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('assinatura_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('assinatura_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('assinatura_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('assinatura_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('assinatura_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('assinatura_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('assinatura_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('assinatura_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('assinatura_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('assinatura_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('assinatura_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('assinatura_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('assinatura_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('assinatura_me='.(int)$me_id);
	elseif ($plano_acao_item_id) $sql->adOnde('assinatura_plano_acao_item='.(int)$plano_acao_item_id);
	elseif ($beneficio_id) $sql->adOnde('assinatura_beneficio='.(int)$beneficio_id);
	elseif ($painel_slideshow_id) $sql->adOnde('assinatura_painel_slideshow='.(int)$painel_slideshow_id);
	elseif ($projeto_viabilidade_id) $sql->adOnde('assinatura_viabilidade='.(int)$projeto_viabilidade_id);
	elseif ($projeto_abertura_id) $sql->adOnde('assinatura_abertura='.(int)$projeto_abertura_id);
	elseif ($pg_id) $sql->adOnde('assinatura_plano_gestao='.(int)$pg_id);
	elseif ($aviso_id) $sql->adOnde('assinatura_aviso='.(int)$aviso_id);
	elseif ($modelo_id) $sql->adOnde('assinatura_modelo='.(int)$modelo_id);
	elseif ($ssti_id) $sql->adOnde('assinatura_ssti='.(int)$ssti_id);
	elseif ($laudo_id) $sql->adOnde('assinatura_laudo='.(int)$laudo_id);	
	$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_atesta_opcao > 0');
	$nao_aprovado1 = $sql->resultado();
	$sql->limpar();
	
	//assinatura que nao tem despacho mas foi negativo ou nem assinou
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	if ($tarefa_id) $sql->adOnde('assinatura_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('assinatura_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('assinatura_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('assinatura_tema='.(int)$tema_id);
	elseif ($objetivo_id) $sql->adOnde('assinatura_objetivo='.(int)$objetivo_id);
	elseif ($fator_id) $sql->adOnde('assinatura_fator='.(int)$fator_id);
	elseif ($pg_estrategia_id) $sql->adOnde('assinatura_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('assinatura_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('assinatura_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('assinatura_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('assinatura_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('assinatura_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('assinatura_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('assinatura_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('assinatura_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('assinatura_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('assinatura_ata='.(int)$ata_id);
	elseif ($mswot_id) $sql->adOnde('assinatura_mswot='.(int)$mswot_id);
	elseif ($swot_id) $sql->adOnde('assinatura_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('assinatura_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('assinatura_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('assinatura_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('assinatura_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('assinatura_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('assinatura_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('assinatura_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('assinatura_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('assinatura_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('assinatura_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('assinatura_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('assinatura_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('assinatura_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('assinatura_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('assinatura_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('assinatura_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('assinatura_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('assinatura_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('assinatura_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('assinatura_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('assinatura_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('assinatura_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('assinatura_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('assinatura_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('assinatura_me='.(int)$me_id);
	elseif ($plano_acao_item_id) $sql->adOnde('assinatura_plano_acao_item='.(int)$plano_acao_item_id);
	elseif ($beneficio_id) $sql->adOnde('assinatura_beneficio='.(int)$beneficio_id);
	elseif ($painel_slideshow_id) $sql->adOnde('assinatura_painel_slideshow='.(int)$painel_slideshow_id);
	elseif ($projeto_viabilidade_id) $sql->adOnde('assinatura_viabilidade='.(int)$projeto_viabilidade_id);
	elseif ($projeto_abertura_id) $sql->adOnde('assinatura_abertura='.(int)$projeto_abertura_id);
	elseif ($pg_id) $sql->adOnde('assinatura_plano_gestao='.(int)$pg_id);
	elseif ($aviso_id) $sql->adOnde('assinatura_aviso='.(int)$aviso_id);
	elseif ($modelo_id) $sql->adOnde('assinatura_modelo='.(int)$modelo_id);
	elseif ($ssti_id) $sql->adOnde('assinatura_ssti='.(int)$ssti_id);
	elseif ($laudo_id) $sql->adOnde('assinatura_laudo='.(int)$laudo_id);
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_atesta IS NULL');
	$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
	$nao_aprovado2 = $sql->resultado();
	$sql->limpar();
	
	//assinatura que tem despacho mas nem assinou
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	if ($tarefa_id) $sql->adOnde('assinatura_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('assinatura_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('assinatura_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('assinatura_tema='.(int)$tema_id);
	elseif ($objetivo_id) $sql->adOnde('assinatura_objetivo='.(int)$objetivo_id);
	elseif ($fator_id) $sql->adOnde('assinatura_fator='.(int)$fator_id);
	elseif ($pg_estrategia_id) $sql->adOnde('assinatura_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('assinatura_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('assinatura_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('assinatura_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('assinatura_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('assinatura_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('assinatura_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('assinatura_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('assinatura_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('assinatura_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('assinatura_ata='.(int)$ata_id);
	elseif ($mswot_id) $sql->adOnde('assinatura_mswot='.(int)$mswot_id);
	elseif ($swot_id) $sql->adOnde('assinatura_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('assinatura_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('assinatura_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('assinatura_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('assinatura_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('assinatura_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('assinatura_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('assinatura_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('assinatura_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('assinatura_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('assinatura_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('assinatura_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('assinatura_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('assinatura_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('assinatura_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('assinatura_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('assinatura_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('assinatura_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('assinatura_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('assinatura_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('assinatura_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('assinatura_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('assinatura_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('assinatura_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('assinatura_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('assinatura_me='.(int)$me_id);
	elseif ($plano_acao_item_id) $sql->adOnde('assinatura_plano_acao_item='.(int)$plano_acao_item_id);
	elseif ($beneficio_id) $sql->adOnde('assinatura_beneficio='.(int)$beneficio_id);
	elseif ($painel_slideshow_id) $sql->adOnde('assinatura_painel_slideshow='.(int)$painel_slideshow_id);
	elseif ($projeto_viabilidade_id) $sql->adOnde('assinatura_viabilidade='.(int)$projeto_viabilidade_id);
	elseif ($projeto_abertura_id) $sql->adOnde('assinatura_abertura='.(int)$projeto_abertura_id);
	elseif ($pg_id) $sql->adOnde('assinatura_plano_gestao='.(int)$pg_id);
	elseif ($aviso_id) $sql->adOnde('assinatura_aviso='.(int)$aviso_id);
	elseif ($modelo_id) $sql->adOnde('assinatura_modelo='.(int)$modelo_id);
	elseif ($ssti_id) $sql->adOnde('assinatura_ssti='.(int)$ssti_id);
	elseif ($laudo_id) $sql->adOnde('assinatura_laudo='.(int)$laudo_id);
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_atesta IS NOT NULL');
	$sql->adOnde('assinatura_atesta_opcao IS NULL');
	$nao_aprovado3 = $sql->resultado();
	$sql->limpar();
	
	$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
	
	if ($projeto_id){
		$sql->adTabela('projetos');
		$sql->adAtualizar('projeto_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('projeto_id='.(int)$projeto_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($tr_id){
		$sql->adTabela('tr');
		$sql->adAtualizar('tr_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('tr_id='.(int)$tr_id);
		$sql->exec();
		$sql->limpar();
		}
	elseif ($me_id){
		$sql->adTabela('me');
		$sql->adAtualizar('me_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('me_id='.(int)$me_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($plano_acao_item_id){
		$sql->adTabela('plano_acao_item');
		$sql->adAtualizar('plano_acao_item_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('plano_acao_item_id='.(int)$plano_acao_item_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($beneficio_id){
		$sql->adTabela('beneficio');
		$sql->adAtualizar('beneficio_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('beneficio_id='.(int)$beneficio_id);
		$sql->exec();
		$sql->limpar();
		}			
	elseif ($painel_slideshow_id){
		$sql->adTabela('painel_slideshow');
		$sql->adAtualizar('painel_slideshow_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('painel_slideshow_id='.(int)$painel_slideshow_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($projeto_viabilidade_id){
		$sql->adTabela('projeto_viabilidade');
		$sql->adAtualizar('projeto_viabilidade_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('projeto_viabilidade_id='.(int)$projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		}
	elseif ($projeto_abertura_id){
		$sql->adTabela('projeto_abertura');
		$sql->adAtualizar('projeto_abertura_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('projeto_abertura_id='.(int)$projeto_abertura_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($pg_id){
		$sql->adTabela('plano_gestao');
		$sql->adAtualizar('pg_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('pg_id='.(int)$pg_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($plano_acao_id){
		$sql->adTabela('plano_acao');
		$sql->adAtualizar('plano_acao_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('plano_acao_id='.(int)$plano_acao_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($demanda_id){
		$sql->adTabela('demandas');
		$sql->adAtualizar('demanda_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('demanda_id='.(int)$demanda_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($ata_id){
		$sql->adTabela('ata');
		$sql->adAtualizar('ata_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('ata_id='.(int)$ata_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($instrumento_id){
		$sql->adTabela('instrumento');
		$sql->adAtualizar('instrumento_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('instrumento_id='.(int)$instrumento_id);
		$sql->exec();
		$sql->limpar();
		}
	elseif ($pg_meta_id){
		$sql->adTabela('metas');
		$sql->adAtualizar('pg_meta_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('pg_meta_id='.(int)$pg_meta_id);
		$sql->exec();
		$sql->limpar();
		}			
	elseif ($pg_perspectiva_id){
		$sql->adTabela('perspectivas');
		$sql->adAtualizar('pg_perspectiva_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('pg_perspectiva_id='.(int)$pg_perspectiva_id);
		$sql->exec();
		$sql->limpar();
		}			
	elseif ($tema_id){
		$sql->adTabela('tema');
		$sql->adAtualizar('tema_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('tema_id='.(int)$tema_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($objetivo_id){
		$sql->adTabela('objetivo');
		$sql->adAtualizar('objetivo_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('objetivo_id='.(int)$objetivo_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($fator_id){
		$sql->adTabela('fator');
		$sql->adAtualizar('fator_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('fator_id='.(int)$fator_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($pg_estrategia_id){
		$sql->adTabela('estrategias');
		$sql->adAtualizar('pg_estrategia_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('pg_estrategia_id='.(int)$pg_estrategia_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($pratica_id){
		$sql->adTabela('praticas');
		$sql->adAtualizar('pratica_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('pratica_id='.(int)$pratica_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($pratica_indicador_id){
		$sql->adTabela('pratica_indicador');
		$sql->adAtualizar('pratica_indicador_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($checklist_id){
		$sql->adTabela('checklist');
		$sql->adAtualizar('checklist_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('checklist_id='.(int)$checklist_id);
		$sql->exec();
		$sql->limpar();
		}										
		
	elseif ($brainstorm_id){
		$sql->adTabela('brainstorm');
		$sql->adAtualizar('brainstorm_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('brainstorm_id='.(int)$brainstorm_id);
		$sql->exec();
		$sql->limpar();
		}			
	elseif ($causa_efeito_id){
		$sql->adTabela('causa_efeito');
		$sql->adAtualizar('causa_efeito_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($gut_id){
		$sql->adTabela('gut');
		$sql->adAtualizar('gut_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('gut_id='.(int)$gut_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($canvas_id){
		$sql->adTabela('canvas');
		$sql->adAtualizar('canvas_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('canvas_id='.(int)$canvas_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($avaliacao_id){
		$sql->adTabela('avaliacao');
		$sql->adAtualizar('avaliacao_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('avaliacao_id='.(int)$avaliacao_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($evento_id){
		$sql->adTabela('evento');
		$sql->adAtualizar('evento_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('evento_id='.(int)$evento_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($forum_id){
		$sql->adTabela('foruns');
		$sql->adAtualizar('forum_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('forum_id='.(int)$forum_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($licao_id){
		$sql->adTabela('licao');
		$sql->adAtualizar('licao_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('licao_id='.(int)$licao_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($link_id){
		$sql->adTabela('links');
		$sql->adAtualizar('link_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('link_id='.(int)$link_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($mswot_id){
		$sql->adTabela('mswot');
		$sql->adAtualizar('mswot_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('mswot_id='.(int)$mswot_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($swot_id){
		$sql->adTabela('swot');
		$sql->adAtualizar('swot_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('swot_id='.(int)$swot_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($recurso_id){
		$sql->adTabela('recursos');
		$sql->adAtualizar('recurso_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('recurso_id='.(int)$recurso_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($template_id){
		$sql->adTabela('template');
		$sql->adAtualizar('template_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('template_id='.(int)$template_id);
		$sql->exec();
		$sql->limpar();
		}					
	elseif ($tgn_id){
		$sql->adTabela('tgn');
		$sql->adAtualizar('tgn_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('tgn_id='.(int)$tgn_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($painel_id){
		$sql->adTabela('painel');
		$sql->adAtualizar('painel_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('painel_id='.(int)$painel_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($painel_composicao_id){
		$sql->adTabela('painel_composicao');
		$sql->adAtualizar('painel_composicao_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('painel_composicao_id='.(int)$painel_composicao_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($painel_odometro_id){
		$sql->adTabela('painel_odometro');
		$sql->adAtualizar('painel_odometro_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('painel_odometro_id='.(int)$painel_odometro_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($agrupamento_id){
		$sql->adTabela('agrupamento');
		$sql->adAtualizar('agrupamento_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('agrupamento_id='.(int)$agrupamento_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($monitoramento_id){
		$sql->adTabela('monitoramento');
		$sql->adAtualizar('monitoramento_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('monitoramento_id='.(int)$monitoramento_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($operativo_id){
		$sql->adTabela('operativo');
		$sql->adAtualizar('operativo_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('operativo_id='.(int)$operativo_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($patrocinador_id){
		$sql->adTabela('patrocinadores');
		$sql->adAtualizar('patrocinador_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('patrocinador_id='.(int)$patrocinador_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($problema_id){
		$sql->adTabela('problema');
		$sql->adAtualizar('problema_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('problema_id='.(int)$problema_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($programa_id){
		$sql->adTabela('programa');
		$sql->adAtualizar('programa_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('programa_id='.(int)$programa_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($risco_id){
		$sql->adTabela('risco');
		$sql->adAtualizar('risco_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('risco_id='.(int)$risco_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($risco_resposta_id){
		$sql->adTabela('risco_resposta');
		$sql->adAtualizar('risco_resposta_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('risco_resposta_id='.(int)$risco_resposta_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($arquivo_id){
		$sql->adTabela('arquivo');
		$sql->adAtualizar('arquivo_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('arquivo_id='.(int)$arquivo_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($aviso_id){
		$sql->adTabela('aviso');
		$sql->adAtualizar('aviso_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('aviso_id='.(int)$aviso_id);
		$sql->exec();
		$sql->limpar();
		}		
	elseif ($modelo_id){
		$sql->adTabela('modelos');
		$sql->adAtualizar('modelo_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('modelo_id='.(int)$modelo_id);
		$sql->exec();
		$sql->limpar();
		}	
	elseif ($ssti_id){
		$sql->adTabela('ssti');
		$sql->adAtualizar('ssti_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('ssti_id='.(int)$ssti_id);
		$sql->exec();
		$sql->limpar();
		
		
		
		if (!$nao_aprovado){
			$sql->adTabela('ssti');
			$sql->adAtualizar('ssti_data', date('Y-m-d H:i:s'));
			$sql->adOnde('ssti_id='.(int)$ssti_id);
			$sql->exec();
			$sql->limpar();
			}
		
		
		//checar quem está assinando
		
		
		$sql->adTabela('assinatura');
		$sql->adCampo('assinatura_extra');
		$sql->adOnde('assinatura_id = '.(int)$assinatura_id);
		$ator=$sql->resultado();
		$sql->limpar();
		
		
		if ($ator=='gestor'){
			//se solicitante assinou, liberar para o chefe poder assinar
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('assinatura_atesta_opcao_aprova');
			$sql->adOnde('assinatura_ssti='.(int)$ssti_id);
			$sql->adOnde('assinatura_extra=\'gestor\'');
			$aprovou=$sql->resultado();
			$sql->limpar();
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_bloqueado', ($aprovou==1 ? 0 : 1));
			$sql->adOnde('assinatura_ssti='.(int)$ssti_id);
			$sql->adOnde('assinatura_extra=\'gerente\'');
			$sql->exec();
			$sql->limpar();
			}
		else if ($ator=='gerente'){
			//se chefe assinou negativo, excluir assinatura do demandante e colocar como não aprovado
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('assinatura_atesta_opcao_aprova');
			$sql->adOnde('assinatura_ssti='.(int)$ssti_id);
			$sql->adOnde('assinatura_extra=\'gerente\'');
			$aprovou=$sql->resultado();
			$sql->limpar();
			
			if ($aprovou==-1){
				$sql->adTabela('assinatura');
				$sql->adAtualizar('assinatura_aprovou', 0);
				$sql->adAtualizar('assinatura_aprovou', 0);
				$sql->adAtualizar('assinatura_observacao', null);
				$sql->adAtualizar('assinatura_data', null);
				$sql->adAtualizar('assinatura_atesta_opcao', null);
				$sql->adOnde('assinatura_ssti='.(int)$ssti_id);
				$sql->adOnde('assinatura_extra=\'gestor\'');
				$sql->exec();
				$sql->limpar();
				}
			}
		}						
	elseif ($laudo_id){
		$sql->adTabela('laudo');
		$sql->adAtualizar('laudo_aprovado', ($nao_aprovado ? 0 : 1));
		$sql->adOnde('laudo_id='.(int)$laudo_id);
		$sql->exec();
		$sql->limpar();
		
		
		if (!$nao_aprovado) {
			require_once (BASE_DIR.'/modulos/ssti/laudo.class.php');
			require_once (BASE_DIR.'/modulos/ssti/ssti.class.php');
			
			$sql->adTabela('laudo');
			$sql->adAtualizar('laudo_aprovado_data', date('Y-m-d H:i:s'));
			$sql->adOnde('laudo_id='.(int)$laudo_id);
			$sql->exec();
			$sql->limpar();
			
			
			
			$obj = new CLaudo();
			$obj->load($laudo_id);
			
			//calculo da sugestao de ranking
			$ssti = new CSSTI();
			$ssti->load($obj->laudo_ssti);
			//contar número de objetivos estratégicos
			$pontos=0+($ssti->ssti_demanda_legal ? 5 : 0);
			$sql->adTabela('ssti_gestao');
			$sql->adCampo('count(ssti_gestao_objetivo)');
			$sql->adOnde('ssti_gestao_ssti ='.(int)$ssti->ssti_id);	
			$qnt_objetivo = $sql->resultado();
			$sql->limpar();
			
			if ($qnt_objetivo > 7) $pontos+=2.25;
			elseif ($qnt_objetivo >= 6) $pontos+=1.8;
			elseif ($qnt_objetivo >= 4) $pontos+=1.35;
			elseif ($qnt_objetivo >= 2) $pontos+=0.9;
			elseif ($qnt_objetivo == 1) $pontos+=0.45;
			
			if ($ssti->ssti_estimativa_lucro==1) $pontos+=1.25;
			elseif ($ssti->ssti_estimativa_lucro==2) $pontos+=1;
			elseif ($ssti->ssti_estimativa_lucro==3) $pontos+=0.75;
			elseif ($ssti->ssti_estimativa_lucro==4) $pontos+=0.5;
			elseif ($ssti->ssti_estimativa_lucro==5) $pontos+=0.25;	
			
			//converter em mes se estiver em dia
			$tempo=$obj->laudo_tempo_desenvolvimento_numerico;
			if ($obj->laudo_tempo_desenvolvimento_escala=='dia') $tempo=$tempo/30;
			
			if ($tempo < 6) $pontos+=0.5;
			elseif ($tempo <= 8) $pontos+=0.3;
			elseif ($tempo > 8) $pontos+=0.2;
			
			$abrangencia=count(explode(',',$ssti->ssti_abrangencia));
			
			if ($abrangencia == 4) $pontos+=1;
			elseif ($abrangencia == 3) $pontos+=0.75;
			elseif ($abrangencia == 2) $pontos+=0.5;
			elseif ($abrangencia == 1) $pontos+=0.25;
			
			
			$nr_atual=1;
			$sql->adTabela('laudo');
			$sql->adAtualizar('laudo_pontuacao', $pontos);
			$sql->adAtualizar('laudo_ranking_sugestao', $nr_atual);
			$sql->adOnde('laudo_id = '.(int)$obj->laudo_id);
			$sql->exec();
			$sql->limpar();
			}
		
	
		//checar quem está assinando
		$sql->adTabela('assinatura');
		$sql->adCampo('assinatura_extra');
		$sql->adOnde('assinatura_id = '.(int)$assinatura_id);
		$ator=$sql->resultado();
		$sql->limpar();
		

		if ($ator=='coged_analista'){
			//se solicitante assinou, liberar para o chefe poder assinar
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('assinatura_atesta_opcao_aprova');
			$sql->adOnde('assinatura_laudo='.(int)$laudo_id);
			$sql->adOnde('assinatura_extra=\'coged_analista\'');
			$aprovou=$sql->resultado();
			$sql->limpar();
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_bloqueado', ($aprovou==1 ? 0 : 1));
			$sql->adOnde('assinatura_laudo='.(int)$laudo_id);
			$sql->adOnde('assinatura_extra=\'coged_coordenador\'');
			$sql->exec();
			$sql->limpar();
			}             
		else if ($ator=='coged_coordenador'){
			//se chefe assinou negativo, excluir assinatura do demandante e colocar como não aprovado
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('assinatura_atesta_opcao_aprova');
			$sql->adOnde('assinatura_laudo='.(int)$laudo_id);
			$sql->adOnde('assinatura_extra=\'coged_coordenador\'');
			$aprovou=$sql->resultado();
			$sql->limpar();
			
			if ($aprovou==-1){
				$sql->adTabela('assinatura');
				$sql->adAtualizar('assinatura_aprovou', 0);
				$sql->adAtualizar('assinatura_aprovou', 0);
				$sql->adAtualizar('assinatura_observacao', null);
				$sql->adAtualizar('assinatura_data', null);
				$sql->adAtualizar('assinatura_atesta_opcao', null);
				$sql->adOnde('assinatura_laudo='.(int)$laudo_id);
				$sql->adOnde('assinatura_extra=\'coged_analista\'');
				$sql->exec();
				$sql->limpar();
				}
			}
		
		
		
		
		
		
		}													
	$Aplic->redirecionar($Aplic->getPosicao());
	}
if ($projeto_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['projeto']), 'projeto.png', $m, $m.'.'.$a);
elseif ($demanda_id) $botoesTitulo = new CBlocoTitulo('Assinar Demanda', 'demanda.gif', $m, $m.'.'.$a);
elseif ($plano_acao_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['acao']), 'plano_acao.png', $m, $m.'.'.$a);
elseif ($ata_id) $botoesTitulo = new CBlocoTitulo('Assinar Ata de Reunião', '../../../modulos/atas/imagens/ata.png', $m, $m.'.'.$a);
elseif ($instrumento_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['instrumento']), 'instrumento.png', $m, $m.'.'.$a);
elseif ($pg_meta_id) $botoesTitulo = new CBlocoTitulo('Assinar Meta', 'meta.gif', $m, $m.'.'.$a);
elseif ($pg_perspectiva_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['perspectiva']), 'perspectiva.png', $m, $m.'.'.$a);
elseif ($tema_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['tema']), 'tema.png', $m, $m.'.'.$a);
elseif ($objetivo_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['objetivo']), 'obj_estrategicos.gif', $m, $m.'.'.$a);
elseif ($fator_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['fator']), 'fator.gif', $m, $m.'.'.$a);
elseif ($pg_estrategia_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['iniciativa']), 'estrategia.gif', $m, $m.'.'.$a);
elseif ($pratica_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['pratica']), 'pratica.gif', $m, $m.'.'.$a);
elseif ($pratica_indicador_id) $botoesTitulo = new CBlocoTitulo('Assinar Indicador', 'indicador.gif', $m, $m.'.'.$a);
elseif ($checklist_id) $botoesTitulo = new CBlocoTitulo('Assinar Checklist', 'todo_list.png', $m, $m.'.'.$a);
elseif ($tr_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['tr']), 'tr.png', $m, $m.'.'.$a);
elseif ($brainstorm_id) $botoesTitulo = new CBlocoTitulo('Assinar Brainstorm', 'brainstorm.gif', $m, $m.'.'.$a);
elseif ($causa_efeito_id) $botoesTitulo = new CBlocoTitulo('Assinar Causa-Efeito', 'causaefeito.png', $m, $m.'.'.$a);
elseif ($arquivo_id) $botoesTitulo = new CBlocoTitulo('Assinar Arquivo', 'arquivo.png', $m, $m.'.'.$a);
elseif ($gut_id) $botoesTitulo = new CBlocoTitulo('Assinar Matriz GUT', 'gut.gif', $m, $m.'.'.$a);
elseif ($canvas_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['canvas']), 'canvas.png', $m, $m.'.'.$a);
elseif ($avaliacao_id) $botoesTitulo = new CBlocoTitulo('Assinar Avaliação', 'avaliacao.gif', $m, $m.'.'.$a);
elseif ($evento_id) $botoesTitulo = new CBlocoTitulo('Assinar Evento', 'calendario.png', $m, $m.'.'.$a);
elseif ($forum_id) $botoesTitulo = new CBlocoTitulo('Assinar Fórum', 'forum.png', $m, $m.'.'.$a);
elseif ($licao_id) $botoesTitulo = new CBlocoTitulo('Assinar Lição Aprendida', 'licoes.gif', $m, $m.'.'.$a);
elseif ($link_id) $botoesTitulo = new CBlocoTitulo('Assinar Link', 'links.png', $m, $m.'.'.$a);
elseif ($mswot_id) $botoesTitulo = new CBlocoTitulo('Assinar Matriz SWOT', '../../../modulos/swot/imagens/mswot.png', $m, $m.'.'.$a);
elseif ($swot_id) $botoesTitulo = new CBlocoTitulo('Assinar Campo SWOT', '../../../modulos/swot/imagens/swot.png', $m, $m.'.'.$a);
elseif ($recurso_id) $botoesTitulo = new CBlocoTitulo('Assinar Recurso', 'recursos.png', $m, $m.'.'.$a);
elseif ($template_id) $botoesTitulo = new CBlocoTitulo('Assinar Modelo', 'template.gif', $m, $m.'.'.$a);
elseif ($tgn_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['tgn']), 'tgn.png', $m, $m.'.'.$a);
elseif ($painel_id) $botoesTitulo = new CBlocoTitulo('Assinar Painel', 'indicador.gif', $m, $m.'.'.$a);
elseif ($painel_composicao_id) $botoesTitulo = new CBlocoTitulo('Assinar Composição de Painéis', 'composicao.gif', $m, $m.'.'.$a);
elseif ($painel_odometro_id) $botoesTitulo = new CBlocoTitulo('Assinar Odômetro', 'odometro.png', $m, $m.'.'.$a);
elseif ($agrupamento_id) $botoesTitulo = new CBlocoTitulo('Assinar Agrupamento de '.ucfirst($config['projetos']), '../../../modulos/agrupamento/imagens/agrupamento.png', $m, $m.'.'.$a);
elseif ($monitoramento_id) $botoesTitulo = new CBlocoTitulo('Assinar Monitoramento', 'monitoramento.gif', $m, $m.'.'.$a);
elseif ($operativo_id) $botoesTitulo = new CBlocoTitulo('Assinar Plano Operativo', '../../../modulos/operativo/imagens/operativo.png', $m, $m.'.'.$a);
elseif ($patrocinador_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['patrocinador']), '../../../modulos/patrocinadores/imagens/patrocinador.gif', $m, $m.'.'.$a);
elseif ($problema_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['problema']), '../../../modulos/problema/imagens/problema.png', $m, $m.'.'.$a);
elseif ($programa_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['programa']), 'programa.png', $m, $m.'.'.$a);
elseif ($risco_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['risco']), 'risco.png', $m, $m.'.'.$a);
elseif ($risco_resposta_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['risco_resposta']), 'risco_resposta.png', $m, $m.'.'.$a);
elseif ($plano_acao_item_id) $sql->adOnde('assinatura_plano_acao_item='.(int)$plano_acao_item_id);
elseif ($beneficio_id) $sql->adOnde('assinatura_beneficio='.(int)$beneficio_id);
elseif ($painel_slideshow_id) $sql->adOnde('assinatura_painel_slideshow='.(int)$painel_slideshow_id);
elseif ($me_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['me']), 'me.png', $m, $m.'.'.$a);
elseif ($plano_acao_item_id) $botoesTitulo = new CBlocoTitulo('Assinar Item d'.$config['genero_acao'].' '.ucfirst($config['acao']), 'acao_item.png', $m, $m.'.'.$a);
elseif ($beneficio_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.ucfirst($config['programa']), 'beneficio.png', $m, $m.'.'.$a);
elseif ($painel_slideshow_id) $botoesTitulo = new CBlocoTitulo('Assinar Slideshow de Composições', 'slideshow.pngf', $m, $m.'.'.$a);
elseif ($projeto_viabilidade_id) $botoesTitulo = new CBlocoTitulo('Assinar Estudo de Vabilidade', 'viabilidade.gif', $m, $m.'.'.$a);
elseif ($projeto_abertura_id) $botoesTitulo = new CBlocoTitulo('Assinar Termo de Abertura', 'anexo_projeto.png', $m, $m.'.'.$a);
elseif ($pg_id) $botoesTitulo = new CBlocoTitulo('Assinar Planejamento Estratégico', 'planogestao.png', $m, $m.'.'.$a);
elseif ($aviso_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['aviso']), '../../../modulos/aviso/imagens/aviso.png', $m, $m.'.'.$a);
elseif ($modelo_id) $botoesTitulo = new CBlocoTitulo('Assinar Documento', 'documento.png', $m, $m.'.'.$a);
elseif ($ssti_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['ssti']), 'ssti.png', $m, $m.'.'.$a);
elseif ($laudo_id) $botoesTitulo = new CBlocoTitulo('Assinar '.ucfirst($config['laudo']), 'laudo.png', $m, $m.'.'.$a);

$botoesTitulo->adicionaBotao($endereco, 'voltar','','Voltar','Voltar à tela anterior.');

$botoesTitulo->mostrar();

$Aplic->carregarCKEditorJS();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="pg_perspectiva_id" value="'.$pg_perspectiva_id.'" />';
echo '<input type="hidden" name="tema_id" value="'.$tema_id.'" />';
echo '<input type="hidden" name="objetivo_id" value="'.$objetivo_id.'" />';
echo '<input type="hidden" name="fator_id" value="'.$fator_id.'" />';
echo '<input type="hidden" name="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="pg_meta_id" value="'.$pg_meta_id.'" />';
echo '<input type="hidden" name="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="canvas_id" value="'.$canvas_id.'" />';
echo '<input type="hidden" name="risco_id" value="'.$risco_id.'" />';
echo '<input type="hidden" name="risco_resposta_id" value="'.$risco_resposta_id.'" />';
echo '<input type="hidden" name="calendario_id" value="'.$calendario_id.'" />';
echo '<input type="hidden" name="monitoramento_id" value="'.$monitoramento_id.'" />';
echo '<input type="hidden" name="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="mswot_id" value="'.$mswot_id.'" />';
echo '<input type="hidden" name="swot_id" value="'.$swot_id.'" />';
echo '<input type="hidden" name="operativo_id" value="'.$operativo_id.'" />';
echo '<input type="hidden" name="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input type="hidden" name="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="problema_id" value="'.$problema_id.'" />';
echo '<input type="hidden" name="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="programa_id" value="'.$programa_id.'" />';
echo '<input type="hidden" name="licao_id" value="'.$licao_id.'" />';
echo '<input type="hidden" name="evento_id" value="'.$evento_id.'" />';
echo '<input type="hidden" name="link_id" value="'.$link_id.'" />';
echo '<input type="hidden" name="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="tgn_id" value="'.$tgn_id.'" />';
echo '<input type="hidden" name="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="gut_id" value="'.$gut_id.'" />';
echo '<input type="hidden" name="causa_efeito_id" value="'.$causa_efeito_id.'" />';
echo '<input type="hidden" name="arquivo_id" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="checklist_id" value="'.$checklist_id.'" />';
echo '<input type="hidden" name="agenda_id" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="agrupamento_id" value="'.$agrupamento_id.'" />';
echo '<input type="hidden" name="patrocinador_id" value="'.$patrocinador_id.'" />';
echo '<input type="hidden" name="template_id" value="'.$template_id.'" />';
echo '<input type="hidden" name="painel_id" value="'.$painel_id.'" />';
echo '<input type="hidden" name="painel_odometro_id" value="'.$painel_odometro_id.'" />';
echo '<input type="hidden" name="painel_composicao_id" value="'.$painel_composicao_id.'" />';
echo '<input type="hidden" name="tr_id" value="'.$tr_id.'" />';
echo '<input type="hidden" name="me_id" value="'.$me_id.'" />';
echo '<input type="hidden" name="plano_acao_item_id" value="'.$plano_acao_item_id.'" />';
echo '<input type="hidden" name="beneficio_id" value="'.$beneficio_id.'" />';
echo '<input type="hidden" name="painel_slideshow_id" value="'.$painel_slideshow_id.'" />';
echo '<input type="hidden" name="projeto_viabilidade_id" value="'.$projeto_viabilidade_id.'" />';
echo '<input type="hidden" name="projeto_abertura_id" value="'.$projeto_abertura_id.'" />';
echo '<input type="hidden" name="pg_id" value="'.$pg_id.'" />';
echo '<input type="hidden" name="aviso_id" value="'.$aviso_id.'" />';
echo '<input type="hidden" name="modelo_id" value="'.$modelo_id.'" />';
echo '<input type="hidden" name="ssti_id" value="'.$ssti_id.'" />';
echo '<input type="hidden" name="laudo_id" value="'.$laudo_id.'" />';
echo estiloTopoCaixa();
echo '<table width="100%" align="center" class="std" cellspacing=0 cellpadding=0>';

if ($tarefa_id) $nome=nome_tarefa($tarefa_id);
elseif ($projeto_id) $nome=nome_projeto($projeto_id);
elseif ($pg_perspectiva_id) $nome=nome_perspectiva($pg_perspectiva_id);
elseif ($tema_id) $nome=nome_tema($tema_id);
elseif ($objetivo_id) $nome=nome_objetivo($objetivo_id);
elseif ($fator_id) $nome=nome_fator($fator_id);
elseif ($pg_estrategia_id) $nome=nome_estrategia($pg_estrategia_id);
elseif ($pg_meta_id) $nome=nome_meta($pg_meta_id);
elseif ($pratica_id) $nome=nome_pratica($pratica_id);
elseif ($pratica_indicador_id) $nome=nome_indicador($pratica_indicador_id);
elseif ($plano_acao_id) $nome=nome_acao($plano_acao_id);
elseif ($canvas_id) $nome=nome_canvas($canvas_id);
elseif ($risco_id) $nome=nome_risco($risco_id);
elseif ($risco_resposta_id) $nome=nome_risco_resposta($risco_resposta_id);
elseif ($calendario_id) $nome=nome_calendario($calendario_id);
elseif ($monitoramento_id) $nome=nome_monitoramento($monitoramento_id);
elseif ($ata_id) $nome=nome_ata($ata_id);
elseif ($mswot_id) $nome=nome_mswot($mswot_id);
elseif ($swot_id) $nome=nome_swot($swot_id);
elseif ($operativo_id) $nome=nome_operativo($operativo_id);
elseif ($instrumento_id) $nome=nome_instrumento($instrumento_id);
elseif ($recurso_id) $nome=nome_recurso($recurso_id);
elseif ($problema_id) $nome=nome_problema($problema_id);
elseif ($demanda_id) $nome=nome_demanda($demanda_id);
elseif ($programa_id) $nome=nome_programa($programa_id);
elseif ($licao_id) $nome=nome_licao($licao_id);
elseif ($evento_id) $nome=nome_evento($evento_id);
elseif ($link_id) $nome=nome_link($link_id);
elseif ($avaliacao_id) $nome=nome_avaliacao($avaliacao_id);
elseif ($tgn_id) $nome=nome_tgn($tgn_id);
elseif ($brainstorm_id) $nome=nome_brainstorm($brainstorm_id);
elseif ($gut_id) $nome=nome_gut($gut_id);
elseif ($causa_efeito_id) $nome=nome_causa_efeito($causa_efeito_id);
elseif ($arquivo_id) $nome=nome_arquivo($arquivo_id);
elseif ($forum_id) $nome=nome_forum($forum_id);
elseif ($checklist_id) $nome=nome_checklist($checklist_id);
elseif ($agenda_id) $nome=nome_compromisso($agenda_id);
elseif ($agrupamento_id) $nome=nome_agrupamento($agrupamento_id);
elseif ($patrocinador_id) $nome=nome_patrocinador($patrocinador_id);
elseif ($template_id) $nome=nome_template($template_id);
elseif ($painel_id) $nome=nome_painel($painel_id);
elseif ($painel_odometro_id) $nome=nome_painel_odometro($painel_odometro_id);
elseif ($painel_composicao_id) $nome=nome_painel_composicao($painel_composicao_id);
elseif ($tr_id) $nome=nome_tr($tr_id);
elseif ($me_id) $nome=nome_me($tr_id);
elseif ($plano_acao_item_id) $nome=nome_acao_item($plano_acao_item_id);
elseif ($beneficio_id) $nome=nome_beneficio($beneficio_id);
elseif ($painel_slideshow_id) $nome=nome_painel_slideshow($painel_slideshow_id);
elseif ($projeto_viabilidade_id) $nome=nome_viabilidade($projeto_viabilidade_id);
elseif ($projeto_abertura_id) $nome=nome_abertura($projeto_abertura_id);
elseif ($pg_id) $nome=nome_plano_gestao($pg_id);
elseif ($aviso_id) $nome=nome_aviso($aviso_id);
elseif ($modelo_id) $nome=nome_modelo($modelo_id);
elseif ($ssti_id) $nome=nome_ssti($ssti_id);
elseif ($laudo_id) $nome=nome_laudo($laudo_id);
else $nome='';

echo '<tr><td colspan=20 align=center style="font-weight:bold; font-size:150%">'.$nome.'</td></tr>';

if ($assinatura_id){
	echo '<input type="hidden" name="assinatura_id" value="'.$assinatura_id.'" />';
	echo '<input type="hidden" name="gravar" value="1" />';
	if (isset($funcoes[$assinatura_id]) && $funcoes[$assinatura_id])  echo '<tr><td align="right" width=100>'.dica('Função', 'A função utilizada para assinar.').'Função:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td class="realce">'.$funcoes[$assinatura_id].'</td></tr></table></td></tr>';
	if ($atesta['assinatura_data']) echo '<tr><td align="right" width=100>'.dica('Data', 'Uma observação sobre a aprovação ou reprovação.').'Data:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td class="realce">'.retorna_data($atesta['assinatura_data']).'</td></tr></table></td></tr>';
	
	if ($atesta['assinatura_atesta']){
		$sql->adTabela('assinatura_atesta_opcao');
		$sql->adCampo('assinatura_atesta_opcao_id, assinatura_atesta_opcao_nome');
		$sql->adOnde('assinatura_atesta_opcao_atesta='.(int)$atesta['assinatura_atesta']);
		$sql->adOrdem('assinatura_atesta_opcao_ordem');
		$atesta_vetor = $sql->listaVetorChave('assinatura_atesta_opcao_id', 'assinatura_atesta_opcao_nome');
		$sql->limpar();
		
		$sql->adTabela('assinatura_atesta_opcao');
		$sql->adCampo('assinatura_atesta_opcao_id, assinatura_atesta_opcao_aprova');
		$sql->adOnde('assinatura_atesta_opcao_atesta='.(int)$atesta['assinatura_atesta']);
		$atesta_vetor_resultado = $sql->listaVetorChave('assinatura_atesta_opcao_id', 'assinatura_atesta_opcao_aprova');
		$sql->limpar();
		
		foreach($atesta_vetor_resultado as $chave => $valor) echo '<input type="hidden" name="atesta_'.$chave.'" id="atesta_'.$chave.'" value="'.$valor.'" />';
		
		
		echo '<tr><td align="right" width=100>'.dica('Atesta', 'Uma parecer sobre a aprovação ou reprovação.').'Atesta:'.dicaF().'</td><td>'.selecionaVetor($atesta_vetor, 'assinatura_atesta_opcao', 'style="width:284px;" class="texto" onchange="mudanca_parecer(this.value)"', $atesta['assinatura_atesta_opcao']).'</td></tr>';
		echo '<input type="hidden" name="assinatura_aprovou" id="assinatura_aprovou" value="1" />';
		
		//preciso saber o resultado da primeira opção para caso esteja iniciando a assinatua
		$atesta_vetor1=$atesta_vetor;
		$atesta_vetor1=array_flip($atesta_vetor1);
		$primeiro = array_shift($atesta_vetor1);	
		$atual=($atesta['assinatura_atesta_opcao'] ? (isset($atesta_vetor[$atesta['assinatura_atesta_opcao']]) && isset($atesta_vetor_resultado[$atesta_vetor[$atesta['assinatura_atesta_opcao']]]) ? $atesta_vetor_resultado[$atesta_vetor[$atesta['assinatura_atesta_opcao']]] : $atesta_vetor_resultado[$primeiro]) : $atesta_vetor_resultado[$primeiro]);

		
		echo '<tr id="combo_observacao" style="display:'.($atual==-1 ? '' : 'none').'"><td align="right" width=100>'.dica('Observação', 'Uma observação sobre a aprovação ou reprovação.').'Observação:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="obs" id="obs" style="width:600px;" class="textarea">'.$atesta['assinatura_observacao'].'</textarea></td></tr>';
		}
	else {
		echo '<input type="hidden" name="assinatura_atesta" id="assinatura_atesta" value="" />';
		
		//opção de nao ter poder dar parecer negativo nas SSTI
		if ($ssti_id) {
			echo '<input type="hidden" name="assinatura_aprovou" id="assinatura_aprovou" value="1" />';
			$atual=-1;
			}
		else {
			$opcao=array(0=>'Não', 1=>'Sim');
			echo '<tr><td align="right" width=100>'.dica('Aprovar', 'Aprovar a ata de reunião.').'Aprovar:'.dicaF().'</td><td>'.selecionaVetor($opcao, 'assinatura_aprovou', 'class="texto" onchange="mudanca_simples(this.value)"', $atesta['assinatura_aprovou']).'</td></tr>';
			$atual=($atesta['assinatura_aprovou'] ? 1 : -1);
			}
		
		echo '<tr id="combo_observacao" style="display:'.($atual==-1 ? 'none' : '').'"><td align="right" width=100>'.dica('Observação', 'Uma observação sobre a aprovação ou reprovação.').'Observação:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="obs" id="obs" style="width:600px;" class="textarea">'.$atesta['assinatura_observacao'].'</textarea></td></tr>';
		}
	
	
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.($atesta['assinatura_data'] ? botao('mudar assinatura', 'Mudar Assinatura', 'Clique neste botão para mudar a assinatura.','','salvar();') : botao('assinar', 'Assinar', 'Clique neste botão para assinar.','','salvar()')).'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
	}
else {
	echo '<tr><td align="right" width=100>'.dica('Função', 'Função utilizada para assinar.').'Função:'.dicaF().'</td><td>'.selecionaVetor($funcoes, 'assinatura_id', 'class="texto" onchange="env.submit()"').'</td></tr>';
	}	
	
	
	
	
echo '</table>';
echo estiloFundoCaixa();



echo '</form>';

?>

<script type="text/javascript">
	
	
function salvar(){
	if (document.getElementById('combo_observacao').style.display=='none') {
		CKEDITOR.instances['obs'].setData('');
		}
		
	env.submit();	
		
	}	

function mudanca_simples(assinatura_aprovou){
	
	if (situacao==0) document.getElementById('combo_observacao').style.display='';
	else document.getElementById('combo_observacao').style.display='none';
	}
	
function mudanca_parecer(assinatura_atesta_opcao){
	
	situacao=document.getElementById('atesta_'+assinatura_atesta_opcao).value;
	
	if (situacao==-1) document.getElementById('combo_observacao').style.display='';
	else document.getElementById('combo_observacao').style.display='none';
	
	}
	
	
</script>