<?php
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/

$Aplic->carregarCKEditorJS(200);
$Aplic->carregarCalendarioJS();

$msg_id=getParam($_REQUEST, 'msg_id', null);

$data = new CData();

$grupo_id=(int)getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=(int)getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);

$status=getParam($_REQUEST, 'status', 0);

$sql = new BDConsulta;
$legendas=array('grava_encaminha'=>'Encaminhamento',' envia_msg'=>'', 'envia_email'=>'Encaminhamento por e-mail', 'envia_anot'=>'Despacho');
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;


$msg_projeto=getParam($_REQUEST, 'msg_projeto', null);
$msg_tarefa=getParam($_REQUEST, 'msg_tarefa', null);
$msg_perspectiva=getParam($_REQUEST, 'msg_perspectiva', null);
$msg_tema=getParam($_REQUEST, 'msg_tema', null);
$msg_objetivo=getParam($_REQUEST, 'msg_objetivo', null);
$msg_fator=getParam($_REQUEST, 'msg_fator', null);
$msg_estrategia=getParam($_REQUEST, 'msg_estrategia', null);
$msg_meta=getParam($_REQUEST, 'msg_meta', null);
$msg_pratica=getParam($_REQUEST, 'msg_pratica', null);
$msg_acao=getParam($_REQUEST, 'msg_acao', null);
$msg_canvas=getParam($_REQUEST, 'msg_canvas', null);
$msg_risco=getParam($_REQUEST, 'msg_risco', null);
$msg_risco_resposta=getParam($_REQUEST, 'msg_risco_resposta', null);
$msg_indicador=getParam($_REQUEST, 'msg_indicador', null);
$msg_calendario=getParam($_REQUEST, 'msg_calendario', null);
$msg_monitoramento=getParam($_REQUEST, 'msg_monitoramento', null);
$msg_ata=getParam($_REQUEST, 'msg_ata', null);
$msg_mswot=getParam($_REQUEST, 'msg_mswot', null);
$msg_swot=getParam($_REQUEST, 'msg_swot', null);
$msg_operativo=getParam($_REQUEST, 'msg_operativo', null);
$msg_instrumento=getParam($_REQUEST, 'msg_instrumento', null);
$msg_recurso=getParam($_REQUEST, 'msg_recurso', null);
$msg_problema=getParam($_REQUEST, 'msg_problema', null);
$msg_demanda=getParam($_REQUEST, 'msg_demanda', null);
$msg_programa=getParam($_REQUEST, 'msg_programa', null);
$msg_licao=getParam($_REQUEST, 'msg_licao', null);
$msg_evento=getParam($_REQUEST, 'msg_evento', null);
$msg_link=getParam($_REQUEST, 'msg_link', null);
$msg_avaliacao=getParam($_REQUEST, 'msg_avaliacao', null);
$msg_tgn=getParam($_REQUEST, 'msg_tgn', null);
$msg_brainstorm=getParam($_REQUEST, 'msg_brainstorm', null);
$msg_gut=getParam($_REQUEST, 'msg_gut', null);
$msg_causa_efeito=getParam($_REQUEST, 'msg_causa_efeito', null);
$msg_arquivo=getParam($_REQUEST, 'msg_arquivo', null);
$msg_forum=getParam($_REQUEST, 'msg_forum', null);
$msg_checklist=getParam($_REQUEST, 'msg_checklist', null);
$msg_agenda=getParam($_REQUEST, 'msg_agenda', null);
$msg_agrupamento=getParam($_REQUEST, 'msg_agrupamento', null);
$msg_patrocinador=getParam($_REQUEST, 'msg_patrocinador', null);
$msg_template=getParam($_REQUEST, 'msg_template', null);
$msg_painel=getParam($_REQUEST, 'msg_painel', null);
$msg_painel_odometro=getParam($_REQUEST, 'msg_painel_odometro', null);
$msg_painel_composicao=getParam($_REQUEST, 'msg_painel_composicao', null);
$msg_tr=getParam($_REQUEST, 'msg_tr', null);
$msg_me=getParam($_REQUEST, 'msg_me', null);
$msg_acao_item=getParam($_REQUEST, 'msg_acao_item', null);
$msg_beneficio=getParam($_REQUEST, 'msg_beneficio', null);
$msg_painel_slideshow=getParam($_REQUEST, 'msg_painel_slideshow', null);
$msg_projeto_viabilidade=getParam($_REQUEST, 'msg_projeto_viabilidade', null);
$msg_projeto_abertura=getParam($_REQUEST, 'msg_projeto_abertura', null);
$msg_plano_gestao=getParam($_REQUEST, 'msg_plano_gestao', null);
$msg_ssti=getParam($_REQUEST, 'msg_ssti', null);
$msg_laudo=getParam($_REQUEST, 'msg_laudo', null);
$msg_trelo=getParam($_REQUEST, 'msg_trelo', null);
$msg_trelo_cartao=getParam($_REQUEST, 'msg_trelo_cartao', null);
$msg_pdcl=getParam($_REQUEST, 'msg_pdcl', null);
$msg_pdcl_item=getParam($_REQUEST, 'msg_pdcl_item', null);

if (
	$msg_projeto ||
	$msg_tarefa ||
	$msg_perspectiva ||
	$msg_tema ||
	$msg_objetivo ||
	$msg_fator ||
	$msg_estrategia ||
	$msg_meta ||
	$msg_pratica ||
	$msg_acao ||
	$msg_canvas ||
	$msg_risco ||
	$msg_risco_resposta ||
	$msg_indicador ||
	$msg_calendario ||
	$msg_monitoramento ||
	$msg_ata ||
	$msg_mswot ||
	$msg_swot ||
	$msg_operativo ||
	$msg_instrumento ||
	$msg_recurso ||
	$msg_problema ||
	$msg_demanda ||
	$msg_programa ||
	$msg_licao ||
	$msg_evento ||
	$msg_link ||
	$msg_avaliacao ||
	$msg_tgn ||
	$msg_brainstorm ||
	$msg_gut ||
	$msg_causa_efeito ||
	$msg_arquivo ||
	$msg_forum ||
	$msg_checklist ||
	$msg_agenda ||
	$msg_agrupamento ||
	$msg_patrocinador ||
	$msg_template ||
	$msg_painel ||
	$msg_painel_odometro ||
	$msg_painel_composicao ||
	$msg_tr	||
	$msg_me ||
	$msg_acao_item ||
	$msg_beneficio ||
	$msg_painel_slideshow ||
	$msg_projeto_viabilidade ||
	$msg_projeto_abertura ||
	$msg_plano_gestao|| 
	$msg_ssti || 
	$msg_laudo || 
	$msg_trelo || 
	$msg_trelo_cartao || 
	$msg_pdcl || 
	$msg_pdcl_item
	){
	$sql->adTabela('cias');
	if ($msg_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($msg_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($msg_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($msg_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($msg_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
	elseif ($msg_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
	elseif ($msg_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($msg_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($msg_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($msg_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	elseif ($msg_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($msg_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($msg_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($msg_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	elseif ($msg_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($msg_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($msg_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($msg_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
	elseif ($msg_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($msg_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($msg_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
	elseif ($msg_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($msg_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($msg_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($msg_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($msg_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($msg_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
	elseif ($msg_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($msg_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($msg_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($msg_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($msg_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($msg_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($msg_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
	elseif ($msg_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($msg_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
	elseif ($msg_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($msg_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($msg_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
	elseif ($msg_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
	elseif ($msg_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
	elseif ($msg_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
	elseif ($msg_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
	elseif ($msg_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
	elseif ($msg_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
	elseif ($msg_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
	elseif ($msg_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
	elseif ($msg_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
	elseif ($msg_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
	elseif ($msg_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
	elseif ($msg_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
	elseif ($msg_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
	elseif ($msg_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
	elseif ($msg_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
	elseif ($msg_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
	elseif ($msg_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
	elseif ($msg_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
	
	if ($msg_tarefa) $sql->adOnde('tarefa_id = '.(int)$msg_tarefa);
	elseif ($msg_projeto) $sql->adOnde('projeto_id = '.(int)$msg_projeto);
	elseif ($msg_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$msg_perspectiva);
	elseif ($msg_tema) $sql->adOnde('tema_id = '.(int)$msg_tema);
	elseif ($msg_objetivo) $sql->adOnde('objetivo_id = '.(int)$msg_objetivo);
	elseif ($msg_fator) $sql->adOnde('fator_id = '.(int)$msg_fator);
	elseif ($msg_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$msg_estrategia);
	elseif ($msg_meta) $sql->adOnde('pg_meta_id = '.(int)$msg_meta);
	elseif ($msg_pratica) $sql->adOnde('pratica_id = '.(int)$msg_pratica);
	elseif ($msg_acao) $sql->adOnde('plano_acao_id = '.(int)$msg_acao);
	elseif ($msg_canvas) $sql->adOnde('canvas_id = '.(int)$msg_canvas);
	elseif ($msg_risco) $sql->adOnde('risco_id = '.(int)$msg_risco);
	elseif ($msg_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$msg_risco_resposta);
	elseif ($msg_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$msg_indicador);
	elseif ($msg_calendario) $sql->adOnde('calendario_id = '.(int)$msg_calendario);
	elseif ($msg_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$msg_monitoramento);
	elseif ($msg_ata) $sql->adOnde('ata_id = '.(int)$msg_ata);
	elseif ($msg_mswot) $sql->adOnde('mswot_id = '.(int)$msg_mswot);
	elseif ($msg_swot) $sql->adOnde('swot_id = '.(int)$msg_swot);
	elseif ($msg_operativo) $sql->adOnde('operativo_id = '.(int)$msg_operativo);
	elseif ($msg_instrumento) $sql->adOnde('instrumento_id = '.(int)$msg_instrumento);
	elseif ($msg_recurso) $sql->adOnde('recurso_id = '.(int)$msg_recurso);
	elseif ($msg_problema) $sql->adOnde('problema_id = '.(int)$msg_problema);
	elseif ($msg_demanda) $sql->adOnde('demanda_id = '.(int)$msg_demanda);
	elseif ($msg_programa) $sql->adOnde('programa_id = '.(int)$msg_programa);
	elseif ($msg_licao) $sql->adOnde('licao_id = '.(int)$msg_licao);
	elseif ($msg_evento) $sql->adOnde('evento_id = '.(int)$msg_evento);
	elseif ($msg_link) $sql->adOnde('link_id = '.(int)$msg_link);
	elseif ($msg_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$msg_avaliacao);
	elseif ($msg_tgn) $sql->adOnde('tgn_id = '.(int)$msg_tgn);
	elseif ($msg_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$msg_brainstorm);
	elseif ($msg_gut) $sql->adOnde('gut_id = '.(int)$msg_gut);
	elseif ($msg_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$msg_causa_efeito);
	elseif ($msg_arquivo) $sql->adOnde('arquivo_id = '.(int)$msg_arquivo);
	elseif ($msg_forum) $sql->adOnde('forum_id = '.(int)$msg_forum);
	elseif ($msg_checklist) $sql->adOnde('checklist_id = '.(int)$msg_checklist);
	elseif ($msg_agenda) $sql->adOnde('agenda_id = '.(int)$msg_agenda);
	elseif ($msg_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$msg_agrupamento);
	elseif ($msg_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$msg_patrocinador);
	elseif ($msg_template) $sql->adOnde('template_id = '.(int)$msg_template);
	elseif ($msg_painel) $sql->adOnde('painel_id = '.(int)$msg_painel);
	elseif ($msg_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$msg_painel_odometro);
	elseif ($msg_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$msg_painel_composicao);
	elseif ($msg_tr) $sql->adOnde('tr_id = '.(int)$msg_tr);
	elseif ($msg_me) $sql->adOnde('me_id = '.(int)$msg_me);
	elseif ($msg_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$msg_acao_item);
	elseif ($msg_beneficio) $sql->adOnde('beneficio_id = '.(int)$msg_beneficio);
	elseif ($msg_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$msg_painel_slideshow);
	elseif ($msg_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$msg_projeto_viabilidade);
	elseif ($msg_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$msg_projeto_abertura);
	elseif ($msg_plano_gestao) $sql->adOnde('pg_id = '.(int)$msg_plano_gestao);
	elseif ($msg_ssti) $sql->adOnde('ssti_id = '.(int)$msg_ssti);
	elseif ($msg_laudo) $sql->adOnde('laudo_id = '.(int)$msg_laudo);
	elseif ($msg_trelo) $sql->adOnde('trelo_id = '.(int)$msg_trelo);
	elseif ($msg_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$msg_trelo_cartao);
	elseif ($msg_pdcl) $sql->adOnde('pdcl_id = '.(int)$msg_pdcl);
	elseif ($msg_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$msg_pdcl_item);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}


if (!$grupo_id && !$grupo_id2) {
	$grupo_id=($Aplic->usuario_prefs['grupoid'] ? $Aplic->usuario_prefs['grupoid'] : null);
	$grupo_id2=($Aplic->usuario_prefs['grupoid2'] ? $Aplic->usuario_prefs['grupoid2'] : null);
	}

echo '<form method="POST" name="env" id="env" enctype="multipart/form-data">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="grava_msg">';
echo '<input type=hidden id="status" name="status" value="0">';
echo '<input type=hidden id="status_original" name="status_original" value="0">';
echo '<input type=hidden id="tipo" name="tipo" value="0">';
echo '<input type=hidden id="grupo_id" name="grupo_id" value="">';
echo '<input type=hidden id="grupo_id2" name="grupo_id2" value="">';
echo '<input type=hidden id="arquivar" name="arquivar" value="">';
echo '<input type=hidden id="cia_id" name="cia_id" value="'.$cia_id.'">';
echo '<input type=hidden id="msg_id" name="msg_id" value="'.$msg_id.'">';
$uuid=($msg_id ? '' : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';


$botoesTitulo = new CBlocoTitulo('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'email1.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();


echo estiloTopoCaixa();
echo '<table align="center" class="std" width="100%" cellpadding=0 cellspacing=0>';







echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td align=right width=100>'.dica('Exibição', 'Forma de apresentar os ').'Exibição:'.dicaF().'</td><td><input type="radio" name="modo_exibicao" value="dept" id="dept" onChange="mudar_usuarios_designados()" checked>'.ucfirst($config['departamento']).'<input type="radio" name="modo_exibicao" value="simples" id="simples" onChange="mudar_usuarios_designados();">Lista simples</td></tr>';
$sql->adTabela('grupo');
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia, (SELECT COUNT(grupo_permissao_usuario) FROM grupo_permissao AS gp1 WHERE gp1.grupo_permissao_grupo=grupo.grupo_id) AS protegido, (SELECT COUNT(grupo_permissao_usuario) FROM grupo_permissao AS gp2 WHERE gp2.grupo_permissao_grupo=grupo.grupo_id AND gp2.grupo_permissao_usuario='.(int)$Aplic->usuario_id.') AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
$achados=$sql->Lista();
$sql->limpar();
$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se há grupo privado da cia, se houver não haverá opção de ver todos o usuários da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) {
	$grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
	if (!$grupo_id && !$grupo_id2) $grupo_id=-1;
	}
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;

if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align=right width=100>'.dica(ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia_designados">'.selecionar_om($Aplic->usuario_cia, 'cia_designados', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td></tr>';

if (!$grupo_id && !$grupo_id2) {
	$grupo_id=($Aplic->usuario_prefs['grupoid'] ? $Aplic->usuario_prefs['grupoid'] : null);
	$grupo_id2=($Aplic->usuario_prefs['grupoid2'] ? $Aplic->usuario_prefs['grupoid2'] : null);
	}
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo','Escolha '.$config['usuarios'].' incluíd'.$config['genero_usuario'].'s em um dos grupos.').'Grupo:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:400px" class="texto" onchange="env.grupo_b.value=0; mudar_usuarios_designados();"',$grupo_id).'</td></tr>';
else echo '<input type="hidden" name="grupo_a" id="grupo_a" value="" />';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.(int)$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'')+$grupos;
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo Particular','Escolha '.$config['usuarios'].' incluíd'.$config['genero_usuario'].'s em um dos seus grupos particulares.').'Grupo Particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_b', 'style="width:400px" size="1" class="texto" onchange="env.grupo_a.value=0; mudar_usuarios_designados();"',$grupo_id2).'</td></tr>';
else echo '<input type="hidden" name="grupo_b" id="grupo_b" value="" />';
echo '<tr><td align=right width=100>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:400px;" name="busca" id="busca" onchange="env.grupo_a.value=0; env.grupo_b.value=0; mudar_usuarios_designados();" value=""/></td><td><a href="javascript:void(0);" onclick="env.busca.value=\'\'; mudar_usuarios_designados()">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';

if ($config['email_ativo']) echo '<tr><td align=right style="white-space: nowrap">'.dica('E-mail de Outros Destinatários', 'Insira os e-mails de outros destinatários que não constam nas listas dos grupos acima.<br>Separe os e-mails por ponto-vírgula<br>ex: reinert@hotmail.com;sergio@oi.com.br').'Outros destinatários:'.dicaF().'</td><td><input type="text" name="outros_emails" value="" style="width:350px" maxlength="255" class="texto" /></td></tr>';
else echo '<input type=hidden id="outros_emails" name="outros_emails" value="">';
echo '<tr><td align=right style="white-space: nowrap">'.dica('Aviso de Leitura','Selecione esta caixa caso deseje receber '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' de notificação assim que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados lerem '.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Aviso de leitura:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr>';
echo  '<td><input type="checkbox" name="aviso" id="aviso" value="1">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo ($Aplic->usuario_pode_oculta ? '<td>'.dica('Destinatário Oculto','Selecione esta caixa caso deseje que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados não apareçam na lista de encaminhados dos outros destinatários d'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Oculto:'.dicaF().'</td><td><input type="checkbox" name="oculto" id="oculto" value="1" >&nbsp;&nbsp;&nbsp;&nbsp;</td>' : '<input type="checkbox" name="oculto" id="oculto" value="0" style="display:none">');
echo ($config['email_ativo'] ? '<td>'.dica('E-Mail Externo','Selecione esta caixa caso deseje que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados que tenham e-mails externos cadastrados recebam uma cópia d'.$config['genero_mensagem'].' '.$config['mensagem'].' em suas contas de e-mail.').'E-mail:'.dicaF().'</td><td><input type="checkbox" name="externo" id="externo" value="1">&nbsp;&nbsp;&nbsp;&nbsp;</td>' : '<input type="hidden" name="externo" id="externo" value="0">');
echo '<td>'.dica('Atividade','Selecione esta caixa caso deseje que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados recebam '.$config['genero_mensagem'].' '.$config['mensagem'].' como uma atividade, que deverão indicar o progresso da mesma entre 0% - 100%.').'Atividade:'.dicaF().'</td><td><input type="checkbox" name="atividade" id="atividade" value="1">&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td>'.dica('Prazo para a Atividade','Marque esta caixa caso deseja impor um prazo limite para que os desinatários executem a atividade relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.').'Prazo:'.dicaF().'</td><td><input type="checkbox" name="prazo_responder" id="prazo_responder" size=50 value=1 onchange="javascript:if (env.prazo_responder.checked) {env.atividade.checked=true; document.getElementById(\'ver_data\').style.display = \'\';} else document.getElementById(\'ver_data\').style.display = \'none\';"><span id="ver_data" style="display:none"><input type="hidden" name="tarefa_data" id="tarefa_data" value="'.($data ? $data->format('%Y-%m-%d') : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\', \'tarefa_data\');" value="'.($data ? $data->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar um prazo limite para que os desinatários executem a tarefa relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</span></td>';
echo '</tr></table></td></tr>';

echo '</table></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';
echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0>';
echo '<tr><td style="text-align:left" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['usuarios']),'Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para adiciona-lo à lista de destinatário.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão INCLUIR.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;'.ucfirst($config['usuarios']).'&nbsp</legend>';
echo '<div id="combo_de">';
if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
else {
	echo '<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';

	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('grupo_usuario','grupo_usuario','grupo_usuario_usuario=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
		$sql->adOnde('usuario_ativo=1');
		if ($grupo_id2) $sql->adOnde('grupo_usuario_grupo='.$grupo_id2);
		elseif ($grupo_id > 0) $sql->adOnde('grupo_usuario_grupo='.$grupo_id);
		elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
		$sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor');
		$usuarios = $sql->Lista();
		$sql->limpar();
   	foreach ($usuarios as $rs)	 echo '<option value="'.$rs['usuario_id'].'">'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').'</option>';
    }
	echo '</select>';
	}
echo '</div></fieldset>';
echo '</td>';

echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Destinatários','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos destinatários.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Destinatários</b>&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;"></select></fieldset></td></tr>';

echo '<tr><td style="text-align:center"><select name="ListaPARAoculto[]" multiple id="ListaPARAoculto" size=4 style="width:100%; display:none"></select>';
echo '<select name="ListaPARAaviso[]" multiple id="ListaPARAaviso" size=4 style="width:100%; display:none"></select>';
echo '<select name="ListaPARAexterno[]" multiple id="ListaPARAexterno" size=4 style="width:100%; display:none"></select>';
echo '<select name="ListaPARAtarefa[]" multiple id="ListaPARAtarefa" size=4 style="width:100%; display:none"></select></td></tr>';
echo '<tr><td class=CampoJanela style="text-align:center"><table cellpadding=0 cellspacing=0><tr><td width="150">'.dica('Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinatários.').'<a class="botao" href="javascript:Mover(env.ListaDE, env.ListaPARA)"><span><b>incluir >></b></span></a></td><td>'.dica('Incluir Todos','Clique neste botão para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a class="botao" href="javascript:btSelecionarTodos_onclick()"><span><b>incluir todos</b></span></a>'.dicaF().'</td></tr></table></td><td style="text-align:center"><table cellpadding=0 cellspacing=0><tr><td>'.dica("Remover","Clique neste botão para remover os destinatários selecionados da caixa de destinatários.").'<a class="botao" href="javascript:Mover2(env.ListaPARA, env.ListaDE)"><span><b><< remover</b></span></a></td><td width=230>&nbsp;</td></tr></table></td></tr>';

echo '</table></td></tr>';


$tipos=array(''=>'');
if ($Aplic->checarModulo('projetos', 'editar', null, 'projetos_lista')) $tipos['projeto']=ucfirst($config['projeto']); 
if ($Aplic->checarModulo('tarefas', 'editar', null, null)) $tipos['tarefa']=ucfirst($config['tarefa']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'perspectiva')) $tipos['perspectiva']=ucfirst($config['perspectiva']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'tema')) $tipos['tema']=ucfirst($config['tema']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'objetivo')) $tipos['objetivo']=ucfirst($config['objetivo']); 
if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'editar', null, 'fator')) $tipos['fator']=ucfirst($config['fator']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'iniciativa')) $tipos['estrategia']=ucfirst($config['iniciativa']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'meta')) $tipos['meta']=ucfirst($config['meta']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao')) $tipos['acao']=ucfirst($config['acao']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'pratica')) $tipos['pratica']=ucfirst($config['pratica']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'indicador')) $tipos['indicador']='Indicador'; 
if ($Aplic->checarModulo('agenda', 'editar', null, null)) $tipos['calendario']='Agenda';
if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'editar', null, null)) $tipos['instrumento']=ucfirst($config['instrumento']);
if ($Aplic->checarModulo('recursos', 'editar', null, null)) $tipos['recurso']=ucfirst($config['recurso']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'demanda')) $tipos['demanda']='Demanda';
if ($Aplic->checarModulo('projetos', 'editar', null, 'licao')) $tipos['licao']=ucfirst($config['licao']);
if ($Aplic->checarModulo('eventos', 'editar', null, null)) $tipos['evento']='Evento';
if ($Aplic->checarModulo('links', 'editar', null, null)) $tipos['link']='Link';
if ($Aplic->checarModulo('praticas', 'editar', null, 'avaliacao_indicador')) $tipos['avaliacao']='Avaliação';
if ($Aplic->checarModulo('praticas', 'editar', null, 'brainstorm')) $tipos['brainstorm']='Brainstorm';
if ($Aplic->checarModulo('praticas', 'editar', null, 'gut')) $tipos['gut']='Matriz GUT';
if ($Aplic->checarModulo('praticas', 'editar', null, 'causa_efeito')) $tipos['causa_efeito']='Diagrama de causa-efeito';
if ($Aplic->checarModulo('arquivos', 'editar', null,  null)) $tipos['arquivo']='Arquivo';
if ($Aplic->checarModulo('foruns', 'editar', null, null)) $tipos['forum']='Fórum';
if ($Aplic->checarModulo('praticas', 'editar', null, 'checklist')) $tipos['checklist']='Checklist';
if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'editar', null, null)) $tipos['patrocinador']=ucfirst($config['patrocinador']);
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao_item')) $tipos['acao_item']='Item de '.ucfirst($config['acao']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'viabilidade')) $tipos['projeto_viabilidade']='Estudo de viabilidade';
if ($Aplic->checarModulo('projetos', 'editar', null, 'abertura')) $tipos['projeto_abertura']='Termo de abertura';
if ($Aplic->checarModulo('praticas', 'editar', null, 'planejamento')) $tipos['plano_gestao']='Planejamento estratégico';
if ($Aplic->profissional) {
	$tipos['agenda']='Compromisso';
	if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'editar', null, null)) $tipos['operativo']='Plano operativo';
	if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'editar', null, null)) $tipos['ata']='Ata de reunião';	
	if ($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'editar', null, null)) {
		$tipos['mswot']='Matriz SWOT';
		$tipos['swot']='Campo SWOT';
		}
	if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'editar', null, null)) $tipos['problema']=ucfirst($config['problema']);
	if ($Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'editar', null, null)) $tipos['agrupamento']='Agrupamento';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'canvas')) $tipos['canvas']=ucfirst($config['canvas']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'risco')) $tipos['risco']=ucfirst($config['risco']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'resposta_risco')) $tipos['risco_resposta']=ucfirst($config['risco_resposta']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'monitoramento')) $tipos['monitoramento']='Monitoramento';
	if ($Aplic->checarModulo('projetos', 'editar', null, 'programa')) $tipos['programa']=ucfirst($config['programa']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'tgn')) $tipos['tgn']=ucfirst($config['tgn']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'modelo')) $tipos['template']='Modelo';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'painel_indicador')) $tipos['painel']='Painel de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'odometro_indicador')) $tipos['painel_odometro']='Odômetro de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'composicao_painel')) $tipos['painel_composicao']='Composição de painéis';
	if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'editar', null, null)) $tipos['tr']=ucfirst($config['tr']);
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'editar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'beneficio')) $tipos['beneficio']=ucfirst($config['beneficio']).' de '.$config['programa'];
	if ($Aplic->checarModulo('projetos', 'editar', null, 'slideshow_painel')) $tipos['painel_slideshow']='Slideshow de composições';
	if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'editar', null, 'ssti')) $tipos['ssti']=ucfirst($config['ssti']);
	if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'editar', null, 'laudo')) $tipos['laudo']=ucfirst($config['laudo']);
	if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'editar', null, null)) {
		$tipos['trelo']=ucfirst($config['trelo']);
		$tipos['trelo_cartao']=ucfirst($config['trelo_cartao']);
		}
	if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'editar', null, null)) {
		$tipos['pdcl']=ucfirst($config['pdcl']);
		$tipos['pdcl_item']=ucfirst($config['pdcl_item']);
		}
	}	
asort($tipos);


if ($msg_tarefa) $tipo='tarefa';
elseif ($msg_projeto) $tipo='projeto';
elseif ($msg_perspectiva) $tipo='perspectiva';
elseif ($msg_tema) $tipo='tema';
elseif ($msg_objetivo) $tipo='objetivo';
elseif ($msg_fator) $tipo='fator';
elseif ($msg_estrategia) $tipo='estrategia';
elseif ($msg_meta) $tipo='meta';
elseif ($msg_pratica) $tipo='pratica';
elseif ($msg_acao) $tipo='acao';
elseif ($msg_canvas) $tipo='canvas';
elseif ($msg_risco) $tipo='risco';
elseif ($msg_risco_resposta) $tipo='risco_resposta';
elseif ($msg_indicador) $tipo='msg_indicador';
elseif ($msg_calendario) $tipo='calendario';
elseif ($msg_monitoramento) $tipo='monitoramento';
elseif ($msg_ata) $tipo='ata';
elseif ($msg_mswot) $tipo='mswot';
elseif ($msg_swot) $tipo='swot';
elseif ($msg_operativo) $tipo='operativo';
elseif ($msg_instrumento) $tipo='instrumento';
elseif ($msg_recurso) $tipo='recurso';
elseif ($msg_problema) $tipo='problema';
elseif ($msg_demanda) $tipo='demanda';
elseif ($msg_programa) $tipo='programa';
elseif ($msg_licao) $tipo='licao';
elseif ($msg_evento) $tipo='evento';
elseif ($msg_link) $tipo='link';
elseif ($msg_avaliacao) $tipo='avaliacao';
elseif ($msg_tgn) $tipo='tgn';
elseif ($msg_brainstorm) $tipo='brainstorm';
elseif ($msg_gut) $tipo='gut';
elseif ($msg_causa_efeito) $tipo='causa_efeito';
elseif ($msg_arquivo) $tipo='arquivo';
elseif ($msg_forum) $tipo='forum';
elseif ($msg_checklist) $tipo='checklist';
elseif ($msg_agenda) $tipo='agenda';
elseif ($msg_agrupamento) $tipo='agrupamento';
elseif ($msg_patrocinador) $tipo='patrocinador';
elseif ($msg_template) $tipo='template';
elseif ($msg_painel) $tipo='painel';
elseif ($msg_painel_odometro) $tipo='painel_odometro';
elseif ($msg_painel_composicao) $tipo='painel_composicao';
elseif ($msg_tr) $tipo='tr';
elseif ($msg_me) $tipo='me';
elseif ($msg_acao_item) $tipo='acao_item';
elseif ($msg_beneficio) $tipo='beneficio';
elseif ($msg_painel_slideshow) $tipo='painel_slideshow';
elseif ($msg_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($msg_projeto_abertura) $tipo='projeto_abertura';
elseif ($msg_plano_gestao) $tipo='plano_gestao';
elseif ($msg_ssti) $tipo='ssti';
elseif ($msg_laudo) $tipo='laudo';
elseif ($msg_trelo) $tipo='trelo';
elseif ($msg_trelo_cartao) $tipo='trelo_cartao';
elseif ($msg_pdcl) $tipo='pdcl';
elseif ($msg_pdcl_item) $tipo='pdcl_item';	
else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionad'.$config['genero_mensagem'], 'A que área '.$config['genero_mensagem'].' '.$config['mensagem'].' está relacionad'.$config['genero_mensagem'].'.').'Relacionad'.$config['genero_mensagem'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($msg_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_projeto" value="'.$msg_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($msg_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_tarefa" value="'.$msg_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($msg_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_perspectiva" value="'.$msg_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($msg_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_tema" value="'.$msg_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($msg_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_objetivo" value="'.$msg_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($msg_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_fator" value="'.$msg_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($msg_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_estrategia" value="'.$msg_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($msg_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_meta" value="'.$msg_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($msg_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_pratica" value="'.$msg_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($msg_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_acao" value="'.$msg_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($msg_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_canvas" value="'.$msg_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($msg_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_risco" value="'.$msg_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($msg_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_risco_resposta" value="'.$msg_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($msg_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_indicador" value="'.$msg_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($msg_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_calendario" value="'.$msg_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($msg_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_monitoramento" value="'.$msg_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($msg_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reunião', 'Caso seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_ata" value="'.(isset($msg_ata) ? $msg_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($msg_ata) ? $msg_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('icones/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja específico de uma matriz SWOT neste campo deverá constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_mswot" value="'.(isset($msg_mswot) ? $msg_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($msg_mswot) ? $msg_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ícone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja específico de um campo de matriz SWOT neste campo deverá constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_swot" value="'.(isset($msg_swot) ? $msg_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($msg_swot) ? $msg_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_operativo" value="'.$msg_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($msg_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_instrumento" value="'.$msg_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($msg_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_recurso" value="'.$msg_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($msg_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_problema" value="'.$msg_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($msg_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_demanda" value="'.$msg_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($msg_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_programa" value="'.$msg_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($msg_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja específico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo deverá constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_licao" value="'.$msg_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($msg_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_evento" value="'.$msg_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($msg_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_link" value="'.$msg_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($msg_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avaliação', 'Caso seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_avaliacao" value="'.$msg_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($msg_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_tgn" value="'.$msg_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($msg_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_brainstorm" value="'.$msg_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($msg_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja específico de uma matriz GUT, neste campo deverá constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_gut" value="'.$msg_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($msg_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_causa_efeito" value="'.$msg_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($msg_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_arquivo" value="'.$msg_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($msg_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('Fórum', 'Caso seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_forum" value="'.$msg_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($msg_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja específico de um checklist, neste campo deverá constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_checklist" value="'.$msg_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($msg_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_agenda" value="'.$msg_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($msg_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_agrupamento" value="'.$msg_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($msg_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja específico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo deverá constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_patrocinador" value="'.$msg_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($msg_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_template" value="'.$msg_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($msg_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_painel" value="'.$msg_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($msg_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Odômetro de Indicador', 'Caso seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_painel_odometro" value="'.$msg_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($msg_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composição de Painéis', 'Caso seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_painel_composicao" value="'.$msg_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($msg_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/composicao_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_tr" value="'.$msg_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($msg_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_me" value="'.$msg_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($msg_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja específico de um item de '.$config['acao'].', neste campo deverá constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_acao_item" value="'.$msg_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($msg_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_beneficio" value="'.$msg_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($msg_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composições', 'Caso seja específico de um slideshow de composições, neste campo deverá constar o nome do slideshow de composições.').'Slideshow de composições:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_painel_slideshow" value="'.$msg_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($msg_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composições','Clique neste ícone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composições.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja específico de um estudo de viabilidade, neste campo deverá constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_projeto_viabilidade" value="'.$msg_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($msg_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ícone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja específico de um termo de abertura, neste campo deverá constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_projeto_abertura" value="'.$msg_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($msg_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ícone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estratégico', 'Caso seja específico de um planejamento estratégico, neste campo deverá constar o nome do planejamento estratégico.').'Planejamento estratégico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_plano_gestao" value="'.$msg_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($msg_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja específico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo deverá constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_ssti" value="'.$msg_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($msg_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ícone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja específico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo deverá constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_laudo" value="'.$msg_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($msg_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ícone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja específico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo deverá constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_trelo" value="'.$msg_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($msg_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ícone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja específico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo deverá constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_trelo_cartao" value="'.$msg_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($msg_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ícone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja específico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo deverá constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_pdcl" value="'.$msg_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($msg_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ícone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($msg_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja específico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo deverá constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="msg_pdcl_item" value="'.$msg_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($msg_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCLItem();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ícone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';


$sql->adTabela('msg_gestao');
$sql->adCampo('msg_gestao.*');
if ($uuid) $sql->adOnde('msg_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('msg_gestao_msg ='.(int)$msg_id);	
$sql->adOrdem('msg_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['msg_gestao_ordem'].', '.$gestao_data['msg_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['msg_gestao_ordem'].', '.$gestao_data['msg_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['msg_gestao_ordem'].', '.$gestao_data['msg_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['msg_gestao_ordem'].', '.$gestao_data['msg_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['msg_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['msg_gestao_tarefa']).'</td>';
	elseif ($gestao_data['msg_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['msg_gestao_projeto']).'</td>';
	elseif ($gestao_data['msg_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['msg_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['msg_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['msg_gestao_tema']).'</td>';
	elseif ($gestao_data['msg_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['msg_gestao_objetivo']).'</td>';
	elseif ($gestao_data['msg_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['msg_gestao_fator']).'</td>';
	elseif ($gestao_data['msg_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['msg_gestao_estrategia']).'</td>';
	elseif ($gestao_data['msg_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['msg_gestao_meta']).'</td>';
	elseif ($gestao_data['msg_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['msg_gestao_pratica']).'</td>';
	elseif ($gestao_data['msg_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['msg_gestao_acao']).'</td>';
	elseif ($gestao_data['msg_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['msg_gestao_canvas']).'</td>';
	elseif ($gestao_data['msg_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['msg_gestao_risco']).'</td>';
	elseif ($gestao_data['msg_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['msg_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['msg_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['msg_gestao_indicador']).'</td>';
	elseif ($gestao_data['msg_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['msg_gestao_calendario']).'</td>';
	elseif ($gestao_data['msg_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['msg_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['msg_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['msg_gestao_ata']).'</td>';
	elseif ($gestao_data['msg_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['msg_gestao_mswot']).'</td>';
	elseif ($gestao_data['msg_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['msg_gestao_swot']).'</td>';
	elseif ($gestao_data['msg_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['msg_gestao_operativo']).'</td>';
	elseif ($gestao_data['msg_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['msg_gestao_instrumento']).'</td>';
	elseif ($gestao_data['msg_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['msg_gestao_recurso']).'</td>';
	elseif ($gestao_data['msg_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['msg_gestao_problema']).'</td>';
	elseif ($gestao_data['msg_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['msg_gestao_demanda']).'</td>';
	elseif ($gestao_data['msg_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['msg_gestao_programa']).'</td>';
	elseif ($gestao_data['msg_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['msg_gestao_licao']).'</td>';
	elseif ($gestao_data['msg_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['msg_gestao_evento']).'</td>';
	elseif ($gestao_data['msg_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['msg_gestao_link']).'</td>';
	elseif ($gestao_data['msg_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['msg_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['msg_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['msg_gestao_tgn']).'</td>';
	elseif ($gestao_data['msg_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['msg_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['msg_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['msg_gestao_gut']).'</td>';
	elseif ($gestao_data['msg_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['msg_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['msg_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['msg_gestao_arquivo']).'</td>';
	elseif ($gestao_data['msg_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['msg_gestao_forum']).'</td>';
	elseif ($gestao_data['msg_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['msg_gestao_checklist']).'</td>';
	elseif ($gestao_data['msg_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['msg_gestao_agenda']).'</td>';
	elseif ($gestao_data['msg_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['msg_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['msg_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['msg_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['msg_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['msg_gestao_template']).'</td>';
	elseif ($gestao_data['msg_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['msg_gestao_painel']).'</td>';
	elseif ($gestao_data['msg_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['msg_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['msg_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['msg_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['msg_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['msg_gestao_tr']).'</td>';	
	elseif ($gestao_data['msg_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['msg_gestao_me']).'</td>';	
	elseif ($gestao_data['msg_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['msg_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['msg_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['msg_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['msg_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['msg_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['msg_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['msg_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['msg_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['msg_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['msg_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['msg_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['msg_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['msg_gestao_ssti']).'</td>';
	elseif ($gestao_data['msg_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['msg_gestao_laudo']).'</td>';
	elseif ($gestao_data['msg_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['msg_gestao_trelo']).'</td>';
	elseif ($gestao_data['msg_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['msg_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['msg_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['msg_gestao_pdcl']).'</td>';
	elseif ($gestao_data['msg_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['msg_gestao_pdcl_item']).'</td>';

	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['msg_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';





echo '<tr>';
if ($config['permitir_cripto']){
	echo '<td align=right style="white-space: nowrap">'.dica('Criptografia','<ul><li><b>Chaves Públicas</b> - é a mais segura, pois somente o destinatário com a chave particular poderão visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].', entretanto caso o usuário não tenha uma chave particular não poderá ler '.$config['genero_mensagem'].' '.$config['mensagem'].'.<br>Os '.$config['usuarios'].' com pares de chaves pública/privada serão apresentados na cor azul.</li><li><b>Senha</b> - é menos segura, pois uma unica senha é utilizada para criptografar e decriptografar '.$config['genero_mensagem'].' '.$config['mensagem'].', entretanto tem a vantagem que não necessita que os destinatários tenham pares de chaves pública/privada.</li></ul>').'Criptografia:'.dicaF().'</td>';
	echo '<td><table cellspacing=0 cellpadding=0><tr><td><input type="radio" class="std2" name="tipo_cripto" value="0" onclick="document.getElementById(\'senha\').style.display=\'none\';" checked="checked" />Nenhuma</td>';
	if (function_exists('openssl_sign')) echo '<td><input type="radio" class="std2" '.(!$Aplic->chave_privada ? 'disabled = "true"' : '').' name="tipo_cripto" value="1" onclick="document.getElementById(\'senha\').style.display=\'none\';" />'.(!$Aplic->chave_privada ? dica('Desabilitado','Carregue a sua chave privada para poder utilizar este método criptográfico.').'Chaves públicas'.dicaF() : 'Chaves públicas').'</td>';
	echo '<td><input type="radio" class="std2" onclick="document.getElementById(\'senha\').style.display=\'\';" name="tipo_cripto" value="2" />Senha</td>';
	echo '<td><input type="password" class="texto" id="senha" name="senha" style="display:none; width:163px;" value=""></td>';
	echo '</tr></table></td>';
	}
echo '</tr>';

$precedencia=getSisValor('precedencia','','','sisvalor_valor_id ASC');
$class_sigilosa=getSisValor('class_sigilosa', '','CAST(sisvalor_valor_id AS '. ( $config['tipoBd']==	'mysql' ? 'UNSIGNED' : '' ). ' INTEGER) <= '.(int)$Aplic->usuario_acesso_email, 'sisvalor_valor_id ASC');
if ($config['msg_precedencia']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Precedência','A precedência d'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Precedência:'.dicaF().'</td><td>'.selecionaVetor($precedencia, 'precedencia','class="texto" size=1 style="width:110px"').'</td></tr>';
if ($config['msg_class_sigilosa']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Classificação Sigilosa','A classificação sigilosa d'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Classificação Sigilosa:'.dicaF().'</td><td>'.selecionaVetor($class_sigilosa, 'class_sigilosa','class="texto" size=1 style="width:110px"').'</td></tr>';

echo '<tr><td>&nbsp;</td></tr>';
echo '<tr><td align="right">'.dica('Assunto','O assunto d'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Assunto:'.dicaF().'</td><td align="left"><input class="texto" type="text" name="referencia" id="referencia" size="79" maxlength="79"></td></tr>';
echo '<tr><td colspan=20 align="center">'.dica('Texto','O texto d'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Texto'.dicaF().'</td></tr>';
echo '<tr><td colspan=20 align="left" style="background:#ffffff; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="texto" id="texto" ></textarea></td></tr>';
echo '<tr><td align="center" colspan=20><table cellspacing=0 cellpadding=0><tr><td align="right">'.botao('referenciar '.$config['mensagem'].'', 'Referenciar '.ucfirst($config['mensagem']), 'Abre uma janela para procurar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' '.($config['genero_mensagem']=='a' ? 'à': 'ao').' qual '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' fará referência.','','popMensagem();').'</td>';
echo ($config['doc_interno'] ? '<td align="center">'.botao('referenciar documento', 'Referenciar Documento', 'Abre uma janela para procurar um documento criado no '.$config['gpweb'].', à partir de modelo pré-definido, à qual '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' fará referência.','','popDocumentos_referencia();').'</td>' : '');
echo ($config['doc_interno'] ? '<td align="left">'.botao('anexar documento', 'Anexar Documento', 'Abre uma janela para procurar um documento criado no '.$config['gpweb'].', à partir de modelo pré-definido, que deseja anexar n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.','','popDocumentos();').'</td>' : '');
echo '</tr></table></td></tr>';
echo '<tr id="mensagens_referencia" style="display:none"><td align="center" colspan=20><table cellpadding=0 cellspacing=0 width="100%"><tr><td>'.ucfirst($config['mensagem']).' Referenciad'.$config['genero_mensagem'].'</td></tr><tr><td><select name="lista_msg_referencia[]" id="lista_msg_referencia" multiple size=3 class="texto" style="width:745px;" ondblClick="javascript:remover_msg(); return false;"></select></td></tr></table></td></tr>';
echo '<tr id="documentos_referencia" style="display:none"><td align="center" colspan=20><table cellpadding=0 cellspacing=0 width="100%"><tr><td>Documento Referenciado</td></tr><tr><td><select name="lista_doc_referencia[]" id="lista_doc_referencia" multiple size=3 class="texto" style="width:745px;" ondblClick="javascript:remover_referencia(); return false;"></select></td></tr></table></td></tr>';
echo '<tr id="documentos" style="display:none"><td align="center" colspan=20><table cellpadding=0 cellspacing=0 width="100%"><tr><td>Documento Anexado</td></tr><tr><td><select name="lista_doc[]" id="lista_doc" multiple size=3 class="texto" style="width:745px;" ondblClick="javascript:remover(); return false;"></select></td></tr></table></td></tr>';
echo '<tr><td colspan=20 align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Anexar arquivos','Clique neste link para anexar um arquivo a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.<br>Caso necessite anexar múltiplos arquivos basta clicar aqui sucessivamente para criar os campos necessários.').'<b>Anexar arquivos</b>'.dicaF().'</a></td></tr>';
echo '<tr><td colspan=20 align="center"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td align="center">'.dica('Enviar '.ucfirst($config['mensagem']),'Clique neste botão para enviar '.$config['genero_mensagem'].' '.$config['mensagem'].' aos destinatários selecionados.').'<a  class="botao" href="javascript:void(0);" onclick="javascript:enviar();"><span><b>enviar</b></span></a></td><td align="right">'.botao('retornar', 'Retornar', 'Retornar a tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';

?>

<script LANGUAGE="javascript">

function mudar_usuarios_designados(){
	var tipo=document.env.modo_exibicao.value;
	grupo=document.getElementById('grupo_b').value;
	if (!grupo|| grupo==0) grupo=document.getElementById('grupo_a').value;
	if (grupo==-1) grupo=null;
	if (tipo=='dept')	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'ListaDE', 'combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"', document.getElementById('busca').value, grupo);
	else xajax_mudar_usuario_grupo_ajax(grupo, document.getElementById('busca').value);
	}
	
function mudar_om_designados(){
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1);
	}


function incluir_arquivo(){
	var r  = document.createElement('TR');
  var ca = document.createElement('TD');
	var ta = document.createTextNode('Tipo:');
	meuselect = document.createElement("SELECT");
	meuselect.className="texto";
	meuselect.style.width="120px";
	meuselect.name="doc_tipo[]";
	ca.appendChild(ta);
	<?php
	foreach (getSisValor('tipo_anexo','','','sisvalor_id ASC') as $chave => $valor){
		echo 'opcao=document.createElement("OPTION");';
		echo 'texto=document.createTextNode("'.$valor.'");';
		echo 'opcao.setAttribute("value","'.$chave.'");';
		echo 'opcao.appendChild(texto);';
		echo 'meuselect.appendChild(opcao);';
		}
	?>
	ca.appendChild(meuselect);

	var ta = document.createTextNode(' Nº:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'doc_nr[]';
	campo.type = 'text';
	campo.value = '';
	campo.size=6;
	campo.className="texto";
	ca.appendChild(campo);

	var ta = document.createTextNode(' Nome:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'nome_fantasia[]';
	campo.type = 'text';
	campo.value = '';
	campo.size=30;
	campo.className="texto";
	ca.appendChild(campo);

	var ta = document.createTextNode(' Arq:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'doc[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=30;
	campo.className="texto";
	ca.appendChild(campo);

	r.appendChild(ca);
	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}

function popDocumentos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1', window.anexar_documento, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&anexar_documento=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}

function popDocumentos_referencia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=modelo_pesquisar&dialogo=1&referenciar_documento=1', window.anexar_documento_referencia, window);
	else window.open('./index.php?m=email&a=modelo_pesquisar&dialogo=1&referenciar_documento=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}

function popMensagem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 800, 500, 'm=email&a=mensagem_pesquisar&dialogo=1&referenciar_mensagem=1', window.anexar_mensagem_referencia, window);
	else window.open('./index.php?m=email&a=mensagem_pesquisar&dialogo=1&referenciar_mensagem=1', '','height=600, width=1010, resizable, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no, status=no');
	}

// Limpa Vazios
function limpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		limpaVazios(box, box_len);
		}
	}

function remover(){
	for(var i=0; i < document.getElementById('lista_doc').options.length; i++) {
		if (document.getElementById('lista_doc').options[i].selected && document.getElementById('lista_doc').options[i].value) {
			document.getElementById('lista_doc').options[i].value = "";
			document.getElementById('lista_doc').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_doc'), document.getElementById('lista_doc').options.length);
	if (!document.getElementById('lista_doc').options.length) document.getElementById('documentos').style.display = 'none';
	}

function remover_referencia(){
	for(var i=0; i < document.getElementById('lista_doc_referencia').options.length; i++) {
		if (document.getElementById('lista_doc_referencia').options[i].selected && document.getElementById('lista_doc_referencia').options[i].value) {
			document.getElementById('lista_doc_referencia').options[i].value = "";
			document.getElementById('lista_doc_referencia').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_doc_referencia'), document.getElementById('lista_doc_referencia').options.length);
	if (!document.getElementById('lista_doc_referencia').options.length) document.getElementById('documentos_referencia').style.display = 'none';
	}

function remover_msg(){
	for(var i=0; i < document.getElementById('lista_msg_referencia').options.length; i++) {
		if (document.getElementById('lista_msg_referencia').options[i].selected && document.getElementById('lista_msg_referencia').options[i].value) {
			document.getElementById('lista_msg_referencia').options[i].value = "";
			document.getElementById('lista_msg_referencia').options[i].text = "";
			}
		}
	limpaVazios(document.getElementById('lista_msg_referencia'), document.getElementById('lista_msg_referencia').options.length);
	if (!document.getElementById('lista_msg_referencia').options.length) document.getElementById('mensagens_referencia').style.display = 'none';
	}

function anexar_mensagem_referencia(msg_id, texto){
	document.getElementById('mensagens_referencia').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_msg_referencia').options.length; k++){
		if (document.getElementById('lista_msg_referencia').options[k].value == msg_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert("Est<?php echo ($config['genero_mensagem']=='a' ? 'a': 'e').' '.$config['mensagem']?> já havia sido referenciad<?php echo $config['genero_mensagem']?>");
	else {
		var item = new Option();
		item.value = msg_id;
		item.text = texto;
		document.getElementById('lista_msg_referencia').options[document.getElementById('lista_msg_referencia').options.length] = item;
		}
	}

function anexar_documento(modelo_id, texto){
	document.getElementById('documentos').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_doc').options.length; k++){
		if (document.getElementById('lista_doc').options[k].value == modelo_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert('Este documento já havia sido anexado');
	else {
		var item = new Option();
		item.value = modelo_id;
		item.text = texto;
		document.getElementById('lista_doc').options[document.getElementById('lista_doc').options.length] = item;
		}
	}

function anexar_documento_referencia(modelo_id, texto){
	document.getElementById('documentos_referencia').style.display = '';
	var aviso=0;
	for(var k=0; k < document.getElementById('lista_doc_referencia').options.length; k++){
		if (document.getElementById('lista_doc_referencia').options[k].value == modelo_id) {
			aviso=1;
			break;
			}
		}
	if (aviso) alert('Este documento já havia sido referenciado');
	else {
		var item = new Option();
		item.value = modelo_id;
		item.text = texto;
		document.getElementById('lista_doc_referencia').options[document.getElementById('lista_doc_referencia').options.length] = item;
		}
	}

function enviar() {
	if (env.referencia.value=="") {
		alert("Escreva o assunto d<?php echo $config['genero_mensagem'].' '.$config['mensagem']?>!");
		env.referencia.focus();
		exit;
		}
	for (var i=0; i < document.getElementById('lista_doc').length ; i++) {
		document.getElementById('lista_doc').options[i].selected = true;
		}
	for (var i=0; i < document.getElementById('lista_doc_referencia').length ; i++) {
		document.getElementById('lista_doc_referencia').options[i].selected = true;
		}
	for (var i=0; i < document.getElementById('lista_msg_referencia').length ; i++) {
		document.getElementById('lista_msg_referencia').options[i].selected = true;
		}
	if (selecionar()) env.submit();
	}
env.referencia.focus();

function mudar_om_designados(){
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:350px;" onchange="javascript:mudar_om_designados();"','',1);
	}


var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "tarefa_data",
	date :  <?php echo $data->format("%Y%m%d")?>,
	selection: <?php echo $data->format("%Y%m%d")?>,
  onSelect: function(cal1) {
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("tarefa_data").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal1.hide();
	}
});

function setData(frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
        alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
        campo_data_real.value = '';
        campo_data.style.backgroundColor = 'red';
      	}
    else {
      	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
      	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
        campo_data.style.backgroundColor = '';
				}
		}
	else campo_data_real.value = '';
	}

function Mover(ListaDE,ListaPARA) {
	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value > 0) {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text.replace(/(^[\s]+|[\s]+$)/g, '');
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) {
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				if (env.oculto.checked) {
					var no2 = new Option();
					no2.value = ListaDE.options[i].value;
					no2.text = ListaDE.options[i].text;
					env.ListaPARAoculto.options[env.ListaPARAoculto.options.length] = no2;
					no.text = no.text+' - oculto';
					}
				if (env.aviso.checked) {
					var no3 = new Option();
					no3.value = ListaDE.options[i].value;
					no3.text = ListaDE.options[i].text;
					env.ListaPARAaviso.options[env.ListaPARAaviso.options.length] = no3;
					no.text = no.text+' - aviso';
					}
				if (env.externo.checked) {
					var no4 = new Option();
					no4.value = ListaDE.options[i].value;
					no4.text = ListaDE.options[i].text;
					env.ListaPARAexterno.options[env.ListaPARAexterno.options.length] = no4;
					no.text = no.text+' - externo';
					}
				if (env.atividade.checked) {
					var no5 = new Option();
					no5.value = ListaDE.options[i].value+':'+(env.prazo_responder.checked ? env.tarefa_data.value : '');
					no5.text = ListaDE.options[i].text;
					env.ListaPARAtarefa.options[env.ListaPARAtarefa.options.length] = no5;
					no.text = no.text+' - atividade'+(env.prazo_responder.checked ? ' '+env.data.value : '');
					}
				ListaPARA.options[ListaPARA.options.length] = no;
				}
			}
		}
	}

function Mover2(ListaPARA,ListaDE) {
	var oculto;
	var aviso;
	var externo;
	var tarefa=0;
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value > 0) {
			oculto=0;
			aviso=0;
			externo=0;
			tarefa=0;
			for(var j=0; j < env.ListaPARAoculto.options.length; j++){
				if (env.ListaPARAoculto.options[j].value == env.ListaPARA.options[i].value) {
					oculto=1;
					break;
					}
				}
			for(var k=0; k < env.ListaPARAaviso.options.length; k++){
				if (env.ListaPARAaviso.options[k].value == env.ListaPARA.options[i].value) {
					aviso=1;
					break;
					}
				}
			for(var e=0; e < env.ListaPARAexterno.options.length; e++){
				if (env.ListaPARAexterno.options[e].value == env.ListaPARA.options[i].value) {
					externo=1;
					break;
					}
				}
			for(var d=0; d < env.ListaPARAtarefa.options.length; d++){
				if (env.ListaPARAtarefa.options[d].value == env.ListaPARA.options[i].value) {
					tarefa=1;
					break;
					}
				}
			if (oculto==1){
					env.ListaPARAoculto.options[j].value = "";
					env.ListaPARAoculto.options[j].text = "";
					}
			if (aviso==1){
				env.ListaPARAaviso.options[k].value = "";
				env.ListaPARAaviso.options[k].text = "";
				}
			if (externo==1){
				env.ListaPARAexterno.options[e].value = "";
				env.ListaPARAexterno.options[e].text = "";
				}
			if (tarefa==1){
				env.ListaPARAtarefa.options[d].value = "";
				env.ListaPARAtarefa.options[d].text = "";
				}

			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	LimpaVazios(env.ListaPARAoculto, env.ListaPARAoculto.options.length);
  LimpaVazios(env.ListaPARAaviso, env.ListaPARAaviso.options.length);
  LimpaVazios(env.ListaPARAexterno, env.ListaPARAexterno.options.length);
  LimpaVazios(env.ListaPARAtarefa, env.ListaPARAtarefa.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista de destinatários e efetua o submit
function selecionar() {
	if (env.ListaPARA.length== 0 && env.outros_emails.value.length==0) {
		alert("Selecione ao menos um destinatário!");
		return 0;
		}
	for (var i=0; i < env.ListaPARA.length ; i++) {
		env.ListaPARA.options[i].selected = true;
		}
	for (var i=0; i < env.ListaPARAoculto.length ; i++) {
		env.ListaPARAoculto.options[i].selected = true;
	  }
	for (var i=0; i < env.ListaPARAaviso.length ; i++) {
		env.ListaPARAaviso.options[i].selected = true;
		}
	for (var i=0; i < env.ListaPARAexterno.length ; i++) {
		env.ListaPARAexterno.options[i].selected = true;
		}
	for (var i=0; i < env.ListaPARAtarefa.length ; i++) {
		env.ListaPARAtarefa.options[i].selected = true;
		}
	if (env.prazo_responder.checked==false) env.tarefa_data.value='';
	return 1;
	}

function btRemeter() {
	if (selecionar()) env.submit();
	}

function btRemeter_arquivar() {
	env.status.value=4;
	env.arquivar.value=1;
	if (selecionar()) env.submit();
	}

function btRemeter_pender() {
	env.status.value=3;
	env.arquivar.value=2;
	if (selecionar()) env.submit();
	}

// Seleciona todos os campos da lista de usuários
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
		}
	Mover(env.ListaDE, env.ListaPARA);
	}

function campos(){
	for (var i=0; i < env.ListaPARA.length ; i++) env.ListaPARA.options[i].selected = true;
	for (var i=0; i < env.ListaPARAoculto.length ; i++) env.ListaPARAoculto.options[i].selected = true;
	for (var i=0; i < env.ListaPARAaviso.length ; i++) env.ListaPARAaviso.options[i].selected = true;
	for (var i=0; i < env.ListaPARAexterno.length ; i++) env.ListaPARAexterno.options[i].selected = true;
	for (var i=0; i < env.ListaPARAtarefa.length ; i++) env.ListaPARAtarefa.options[i].selected = true;

	env.a.value = "editar_grupos";
	env.submit();
	}


function mostrar(){
	limpar_tudo();
	esconder_tipo();
	if (document.getElementById('tipo_relacao').value){
		document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
		}
	}

function esconder_tipo(){
	document.getElementById('projeto').style.display='none';
	document.getElementById('tarefa').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('objetivo').style.display='none';	
	document.getElementById('fator').style.display='none';	
	document.getElementById('estrategia').style.display='none';
	document.getElementById('meta').style.display='none';
	document.getElementById('pratica').style.display='none';
	document.getElementById('acao').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('risco').style.display='none';
	document.getElementById('risco_resposta').style.display='none';
	document.getElementById('indicador').style.display='none';
	document.getElementById('calendario').style.display='none';
	document.getElementById('monitoramento').style.display='none';
	document.getElementById('ata').style.display='none';
	document.getElementById('mswot').style.display='none';
	document.getElementById('swot').style.display='none';
	document.getElementById('operativo').style.display='none';
	document.getElementById('instrumento').style.display='none';
	document.getElementById('recurso').style.display='none';
	document.getElementById('problema').style.display='none';
	document.getElementById('demanda').style.display='none';
	document.getElementById('programa').style.display='none';
	document.getElementById('licao').style.display='none';
	document.getElementById('evento').style.display='none';
	document.getElementById('link').style.display='none';
	document.getElementById('avaliacao').style.display='none';
	document.getElementById('tgn').style.display='none';
	document.getElementById('brainstorm').style.display='none';
	document.getElementById('gut').style.display='none';
	document.getElementById('causa_efeito').style.display='none';
	document.getElementById('arquivo').style.display='none';
	document.getElementById('forum').style.display='none';
	document.getElementById('checklist').style.display='none';
	document.getElementById('agenda').style.display='none';
	document.getElementById('agrupamento').style.display='none';
	document.getElementById('patrocinador').style.display='none';
	document.getElementById('template').style.display='none';
	document.getElementById('painel').style.display='none';
	document.getElementById('painel_odometro').style.display='none';
	document.getElementById('painel_composicao').style.display='none';
	document.getElementById('tr').style.display='none';
	document.getElementById('me').style.display='none';
	document.getElementById('acao_item').style.display='none';
	document.getElementById('beneficio').style.display='none';
	document.getElementById('painel_slideshow').style.display='none';
	document.getElementById('projeto_viabilidade').style.display='none';
	document.getElementById('projeto_abertura').style.display='none';
	document.getElementById('plano_gestao').style.display='none';
	document.getElementById('ssti').style.display='none';
	document.getElementById('laudo').style.display='none';
	document.getElementById('trelo').style.display='none';
	document.getElementById('trelo_cartao').style.display='none';
	document.getElementById('pdcl').style.display='none';
	document.getElementById('pdcl_item').style.display='none';
	
	}
	
	


	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.msg_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.msg_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.msg_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.msg_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('cia_id').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.msg_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.msg_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.msg_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.msg_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.msg_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.msg_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.msg_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.msg_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.msg_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.msg_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.msg_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.msg_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.msg_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.msg_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.msg_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('cia_id').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.msg_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('cia_id').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.msg_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Camçpo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.msg_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.msg_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.msg_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.msg_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.msg_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.msg_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.msg_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.msg_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.msg_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.msg_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.msg_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.msg_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.msg_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, 'Matriz GUT','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.msg_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.msg_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.msg_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.msg_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.msg_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.msg_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.msg_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.msg_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.msg_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.msg_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.msg_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('cia_id').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('cia_id').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.msg_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('cia_id').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.msg_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('cia_id').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('cia_id').value, 'Slideshow de Composições','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.msg_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('cia_id').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('cia_id').value, 'Estudo de Viabilidade','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.msg_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('cia_id').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('cia_id').value, 'Termo de Abertura','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.msg_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('cia_id').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('cia_id').value, 'Planejamento Estratégico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.msg_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

	
function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('cia_id').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.msg_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('cia_id').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.msg_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('cia_id').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.msg_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('cia_id').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.msg_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('cia_id').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.msg_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function popPDCLItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('cia_id').value, window.setPDCLItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCLItem(chave, valor){
	limpar_tudo();
	document.env.msg_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.msg_projeto.value = null;
	document.env.msg_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.msg_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.msg_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.msg_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.msg_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.msg_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.msg_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.msg_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.msg_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.msg_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.msg_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.msg_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.msg_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.msg_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.msg_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.msg_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.msg_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.msg_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.msg_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.msg_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.msg_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.msg_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.msg_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.msg_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.msg_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.msg_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.msg_link.value = null;
	document.env.link_nome.value = '';
	document.env.msg_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.msg_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.msg_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.msg_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.msg_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.msg_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.msg_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.msg_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.msg_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.msg_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.msg_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.msg_template.value = null;
	document.env.template_nome.value = '';
	document.env.msg_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.msg_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.msg_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.msg_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.msg_me.value = null;
	document.env.me_nome.value = '';
	document.env.msg_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.msg_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.msg_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.msg_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.msg_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.msg_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.msg_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.msg_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.msg_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.msg_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.msg_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.msg_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';		
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('msg_id').value,
	document.getElementById('uuid').value,
	f.msg_projeto.value,
	f.msg_tarefa.value,
	f.msg_perspectiva.value,
	f.msg_tema.value,
	f.msg_objetivo.value,
	f.msg_fator.value,
	f.msg_estrategia.value,
	f.msg_meta.value,
	f.msg_pratica.value,
	f.msg_acao.value,
	f.msg_canvas.value,
	f.msg_risco.value,
	f.msg_risco_resposta.value,
	f.msg_indicador.value,
	f.msg_calendario.value,
	f.msg_monitoramento.value,
	f.msg_ata.value,
	f.msg_mswot.value,
	f.msg_swot.value,
	f.msg_operativo.value,
	f.msg_instrumento.value,
	f.msg_recurso.value,
	f.msg_problema.value,
	f.msg_demanda.value,
	f.msg_programa.value,
	f.msg_licao.value,
	f.msg_evento.value,
	f.msg_link.value,
	f.msg_avaliacao.value,
	f.msg_tgn.value,
	f.msg_brainstorm.value,
	f.msg_gut.value,
	f.msg_causa_efeito.value,
	f.msg_arquivo.value,
	f.msg_forum.value,
	f.msg_checklist.value,
	f.msg_agenda.value,
	f.msg_agrupamento.value,
	f.msg_patrocinador.value,
	f.msg_template.value,
	f.msg_painel.value,
	f.msg_painel_odometro.value,
	f.msg_painel_composicao.value,
	f.msg_tr.value,
	f.msg_me.value,
	f.msg_acao_item.value,
	f.msg_beneficio.value,
	f.msg_painel_slideshow.value,
	f.msg_projeto_viabilidade.value,
	f.msg_projeto_abertura.value,
	f.msg_plano_gestao.value,
	f.msg_ssti.value,
	f.msg_laudo.value,
	f.msg_trelo.value,
	f.msg_trelo_cartao.value,
	f.msg_pdcl.value,
	f.msg_pdcl_item.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(msg_gestao_id){
	xajax_excluir_gestao(document.getElementById('msg_id').value, document.getElementById('uuid').value, msg_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, msg_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, msg_gestao_id, direcao, document.getElementById('msg_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$msg_id && (
	$msg_tarefa || 
	$msg_projeto || 
	$msg_perspectiva || 
	$msg_tema || 
	$msg_objetivo || 
	$msg_fator || 
	$msg_estrategia || 
	$msg_meta || 
	$msg_pratica || 
	$msg_acao || 
	$msg_canvas || 
	$msg_risco || 
	$msg_risco_resposta || 
	$msg_indicador || 
	$msg_calendario || 
	$msg_monitoramento || 
	$msg_ata || 
	$msg_mswot || 
	$msg_swot || 
	$msg_operativo || 
	$msg_instrumento || 
	$msg_recurso || 
	$msg_problema || 
	$msg_demanda || 
	$msg_programa || 
	$msg_licao || 
	$msg_evento || 
	$msg_link || 
	$msg_avaliacao || 
	$msg_tgn || 
	$msg_brainstorm || 
	$msg_gut || 
	$msg_causa_efeito || 
	$msg_arquivo || 
	$msg_forum || 
	$msg_checklist || 
	$msg_agenda || 
	$msg_agrupamento || 
	$msg_patrocinador || 
	$msg_template || 
	$msg_painel || 
	$msg_painel_odometro || 
	$msg_painel_composicao || 
	$msg_tr || 
	$msg_me || 
	$msg_acao_item || 
	$msg_beneficio || 
	$msg_painel_slideshow || 
	$msg_projeto_viabilidade || 
	$msg_projeto_abertura || 
	$msg_plano_gestao|| 
	$msg_ssti || 
	$msg_laudo || 
	$msg_trelo || 
	$msg_trelo_cartao || 
	$msg_pdcl || 
	$msg_pdcl_item
	)) echo 'incluir_relacionado();';
	?>	

</script>