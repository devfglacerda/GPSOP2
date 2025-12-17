<?php  
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$Aplic->carregarCalendarioJS();
$tarefa_data=getParam($_REQUEST, 'tarefa_data', 0);
$data = intval($tarefa_data) ? new CData($tarefa_data) : new CData();


if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario=getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[]=getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();


$destino=getParam($_REQUEST, 'destino_cabecalho', getParam($_REQUEST, 'destino', ''));
$grupo_id=getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);
//tipo:  1=despacho 2=resposta 3=encaminhamento 4=anotacao
$tipo=getParam($_REQUEST, 'tipo', 0);
$status=getParam($_REQUEST, 'status', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$outros_emails=getParam($_REQUEST, 'outros_emails','');

$msg_id_cripto=getParam($_REQUEST, 'msg_id_cripto', null);
$msg_cripto_id=getParam($_REQUEST, 'msg_cripto_id', null);

$tem_cripto=getParam($_REQUEST, 'cripto', 0);
$senha_antiga=getParam($_REQUEST, 'senha_antiga', 0);

$ListaPARAtarefa=getParam($_REQUEST, 'ListaPARAtarefa', array());
$atividade=array();
if (count($ListaPARAtarefa)){
	foreach ($ListaPARAtarefa as $chave => $valor){
		$dupla=explode(':', $valor);
		$atividade[$dupla[0]]=($dupla[1] ? $dupla[1] : 0);
		}
	}

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

//tipo:  1=despacho 2=resposta 3=encaminhamento 4=anotacao

if ($tipo==1) $ttl='Despacho';
elseif ($tipo==2) $ttl='Resposta';
elseif ($tipo==3) $ttl='Encaminhamento';
else $ttl='Anotacao';

$botoesTitulo = new CBlocoTitulo($ttl, 'email1.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="'.$destino.'">';
echo '<input type=hidden id="m" name="m" value="email">';	
echo '<input type=hidden id="destino" name="destino" value="'.$destino.'">';		
echo '<input type=hidden id="status" name="status" value="'.$status.'">';	
echo '<input type=hidden id="status_original" name="status_original" value="'.$status.'">';	
echo '<input type=hidden id="tipo" name="tipo" value="'.$tipo.'">';	
echo '<input type=hidden id="grupo_id" name="grupo_id" value="">';		
echo '<input type=hidden id="grupo_id2" name="grupo_id2" value="">';		
echo '<input type=hidden id="arquivar" name="arquivar" value="">';		
echo '<input type=hidden id="msg_id_cripto" name="msg_id_cripto" value="'.$msg_id_cripto.'">';
echo '<input type=hidden id="msg_cripto_id" name="msg_cripto_id" value="'.$msg_cripto_id.'">';
echo '<input type=hidden name="senha_antiga" id="senha_antiga" value="'.$senha_antiga.'">';
echo '<input type=hidden id="cia_id" name="cia_id" value="'.$cia_id.'">';

foreach ($vetor_msg_usuario as $chave => $valor) echo '<input type=hidden name="vetor_msg_usuario[]" id="vetor_msg_usuario" value="'.$valor.'">'; 


echo estiloTopoCaixa();
echo '<table align="center" class="std" width="100%" cellpadding=0 cellspacing=1>';
echo '<tr height="20" class="std" align="center"><td colspan=2><br><b>Selecione o(s) destinatário(s)<b></td></tr>';

if ($tem_cripto){
	echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0><tr>';
	echo '<td>'.dica('Criptografia'.$tem_cripto,'<ul><li><b>Chaves Públicas</b> - é a mais segura, pois somente o destinatário com a chave particular poderão visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].', entretanto caso o usuário não tenha uma chave particular não poderá ler '.$config['genero_mensagem'].' '.$config['mensagem'].'.<br>Os '.$config['usuarios'].' com pares de chaves pública/privada serão apresentados na cor azul.</li><li><b>Senha</b> - é menos segura, pois uma unica senha é utilizada para criptografar e decriptografar '.$config['genero_mensagem'].' '.$config['mensagem'].', entretanto tem a vantagem que não necessita que os destinatários tenham pares de chaves pública/privada.</li></ul>').'Criptografia:'.dicaF().'</td>';
	echo '<td><input type="radio" class="std2" '.(!$Aplic->chave_privada ? 'disabled = "true"' : '').' name="tipo_cripto" value="1"'.($tem_cripto == '1' ? ' checked="checked"' : '').' onclick="env.senha.type=\'hidden\'" />'.(!$Aplic->chave_privada ? dica('Desabilitado','Carregue a sua chave privada para poder utilizar este método criptográfico.').'Chaves públicas'.dicaF() : 'Chaves públicas').'</td>';
	echo '<td><input type="radio" class="std2" onclick="env.senha.type=\'password\'" name="tipo_cripto" value="2"'.($tem_cripto == '2' ? ' checked="checked"' : '').' />Senha</td>';
	echo '<td><input type="'.($tem_cripto=='2'? 'password' : 'hidden').'" class="texto" id="senha" name="senha" value=""></td>';	
	echo '</tr></table></td></tr>';
	}



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
echo '<td>'.dica('Prazo','Marque esta caixa caso deseja impor um prazo limite para que os desinatários executem a atividade relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.').'Prazo:'.dicaF().'</td><td><input type="checkbox" name="prazo_responder" id="prazo_responder" size=50 value=1 onchange="javascript:if (env.prazo_responder.checked) {env.atividade.checked=true; document.getElementById(\'ver_data\').style.display = \'\';} else document.getElementById(\'ver_data\').style.display = \'none\';"><span id="ver_data" style="display:none"><input type="hidden" name="tarefa_data" id="tarefa_data" value="'.($data ? $data->format('%Y-%m-%d') : '').'" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\', \'tarefa_data\');" value="'.($data ? $data->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar um prazo limite para que os desinatários executem a tarefa relacionada '.($config['genero_mensagem']=='a' ? 'a': 'ao').' '.$config['mensagem'].'.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</span></td>';
echo '</tr></table></td></tr>';

echo '</table></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';


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

echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Destinatários','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos destinatários.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Destinatários</b>&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;">';
foreach($ListaPARA as $chave => $valor){ 	
	echo "<option value=".$valor.">";
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo('usuario_grupo_dept, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuario_id, cia_nome, contato_nomeguerra');
	$sql->adOnde('usuario_id='.$valor);	
	$rs =	$sql->Linha();
	$sql->limpar();
	echo ($rs['usuario_grupo_dept'] ? $rs['contato_nomeguerra'] : nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: ''));
	if (in_array($valor, $ListaPARAoculto, true)) echo ' - oculo';
	if (in_array($valor, $ListaPARAaviso, true)) echo ' - aviso';
	if (in_array($valor, $ListaPARAexterno, true)) echo ' - externo';
	if (isset($atividade[$valor])) echo ' - atividade'.((isset($atividade[$valor]) && $atividade[$valor]) ? ' '.retorna_data($atividade[$valor], false) : '');
	echo '</option>';
	}
echo '</select></fieldset></td></tr>';
echo '<tr><td style="text-align:center">';
echo '<select name="ListaPARAoculto[]" multiple id="ListaPARAoculto" size=4 style="width:100%; display:none">';
foreach($ListaPARAoculto as $chave => $valor) echo "<option value=".$valor."></option>";
echo '</select>';

echo '<select name="ListaPARAaviso[]" multiple id="ListaPARAaviso" size=4 style="width:100%; display:none">';
foreach($ListaPARAaviso as $chave => $valor  ) echo "<option value=".$valor."></option>";
echo '</select>';

echo '<select name="ListaPARAexterno[]" multiple id="ListaPARAexterno" size=4 style="width:100%; display:none">';
foreach($ListaPARAexterno as $chave => $valor) echo "<option value=".$valor."></option>";
echo '</select>';

echo '<select name="ListaPARAtarefa[]" multiple id="ListaPARAtarefa" size=4 style="width:100%; display:none">';
foreach($ListaPARAtarefa as $chave => $valor) echo "<option value=".$valor."></option>";
echo '</select>';

echo '</td></tr>';

echo '<tr><td class=CampoJanela style="text-align:center"><table cellpadding=0 cellspacing=0><tr><td width="150">'.dica('Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinatários.').'<a class="botao" href="javascript:Mover(env.ListaDE, env.ListaPARA)"><span><b>incluir >></b></span></a></td><td>'.dica('Incluir Todos','Clique neste botão para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a class="botao" href="javascript:btSelecionarTodos_onclick()"><span><b>incluir todos</b></span></a>'.dicaF().'</td></tr></table></td><td style="text-align:center"><table cellpadding=0 cellspacing=0 width="100%"><tr><td>'.dica("Remover","Clique neste botão para remover os destinatários selecionados da caixa de destinatários.").'<a class="botao" href="javascript:Mover2(env.ListaPARA, env.ListaDE)"><span><b><< remover</b></span></a></td></tr></table></td></tr>';



if ($config['email_ativo']) echo '<tr><td align=left style="white-space: nowrap" colspan=20><table cellpadding=0 cellspacing=0><tr><td>'.dica('E-mail de Outros Destinatários', 'Insira os e-mails de outros destinatários que não constam nas listas dos grupos acima.<br>Separe os e-mails por ponto-vírgula<br>ex: reinert@hotmail.com;sergio@oi.com.br').'Outros destinatários:'.dicaF().'</td><td><input type="text" name="outros_emails" value="" size="90" maxlength="255" class="texto" /></td></tr></table></td></tr>';
else echo '<input type=hidden id="outros_emails" name="outros_emails" value="">';

echo '<tr><td colspan=20>&nbsp;</td>';
if (count($vetor_msg_usuario)) echo '<tr><td colspan=2 align="center">'.(isset($legendas[$destino]) ? $legendas[$destino].' para ' : '').relacao_mensagens().'</td></tr><tr><td colspan=2 align="center">&nbsp</td></tr>';



if ($destino=='grava_encaminha') $botao='<table cellspacing=0 cellpadding=0><tr><td>'.botao('encaminhar', 'Encaminhar','Clique neste botão para encaminhar.','','btRemeter()').'</td><td>'.botao('encaminhar e arquivar', 'Encaminhar e Arquivar','Clique nesta opção para encaminhar e arquivar.','','btRemeter_arquivar()').'</td><td>'.botao('encaminhar e pender', 'Encaminhar e Pender','Clique nesta opção para encaminhar e pender.','','btRemeter_pender()').'</td></tr></table>';				
else if ($destino=='envia_msg')	$botao=botao('avançar', 'Avançar','Clique neste botão para escrever '.$config['genero_mensagem'].' '.$config['mensagem'].'.','','btRemeter()');
else $botao=botao('despacho', 'Despacho','Clique nesta botão para escrever o despacho.','','btRemeter()');


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td width="100%">'.$botao.'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Clique neste botão para voltar à tela principal d'.($config['genero_mensagem']=='o' ? 'e' : 'a').' '.$config['mensagem'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){env.a.value=\'lista_msg\'; env.submit();;}').'</td></tr></table></td></tr>';







echo '</table></td></tr>';


echo '</table>';
echo estiloFundoCaixa();
echo '</form></body></html>';
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
	
	<?php if ($tem_cripto) echo 'if(env.tipo_cripto[1].checked && env.tipo_cripto[1].value==2 && env.senha.value.length==0) {alert("Insira uma senha!");return 0; }'; ?>	

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



</script>