<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
require_once (BASE_DIR.'/modulos/projetos/viabilidade.class.php');
require_once (BASE_DIR.'/modulos/projetos/demanda.class.php');

$Aplic->carregarCKEditorJS();

$Aplic->carregarCalendarioJS();
$projeto_viabilidade_id =getParam($_REQUEST, 'projeto_viabilidade_id', null);
$demanda_id =getParam($_REQUEST, 'demanda_id', null);
$salvar=getParam($_REQUEST, 'salvar', 0);

$projeto_viabilidade_projeto=getParam($_REQUEST, 'projeto_viabilidade_projeto', null);
$projeto_viabilidade_tarefa=getParam($_REQUEST, 'projeto_viabilidade_tarefa', null);
$projeto_viabilidade_perspectiva=getParam($_REQUEST, 'projeto_viabilidade_perspectiva', null);
$projeto_viabilidade_tema=getParam($_REQUEST, 'projeto_viabilidade_tema', null);
$projeto_viabilidade_objetivo=getParam($_REQUEST, 'projeto_viabilidade_objetivo', null);
$projeto_viabilidade_fator=getParam($_REQUEST, 'projeto_viabilidade_fator', null);
$projeto_viabilidade_estrategia=getParam($_REQUEST, 'projeto_viabilidade_estrategia', null);
$projeto_viabilidade_meta=getParam($_REQUEST, 'projeto_viabilidade_meta', null);
$projeto_viabilidade_pratica=getParam($_REQUEST, 'projeto_viabilidade_pratica', null);
$projeto_viabilidade_acao=getParam($_REQUEST, 'projeto_viabilidade_acao', null);
$projeto_viabilidade_canvas=getParam($_REQUEST, 'projeto_viabilidade_canvas', null);
$projeto_viabilidade_risco=getParam($_REQUEST, 'projeto_viabilidade_risco', null);
$projeto_viabilidade_risco_resposta=getParam($_REQUEST, 'projeto_viabilidade_risco_resposta', null);
$projeto_viabilidade_indicador=getParam($_REQUEST, 'projeto_viabilidade_indicador', null);
$projeto_viabilidade_calendario=getParam($_REQUEST, 'projeto_viabilidade_calendario', null);
$projeto_viabilidade_monitoramento=getParam($_REQUEST, 'projeto_viabilidade_monitoramento', null);
$projeto_viabilidade_ata=getParam($_REQUEST, 'projeto_viabilidade_ata', null);
$projeto_viabilidade_mswot=getParam($_REQUEST, 'projeto_viabilidade_mswot', null);
$projeto_viabilidade_swot=getParam($_REQUEST, 'projeto_viabilidade_swot', null);
$projeto_viabilidade_operativo=getParam($_REQUEST, 'projeto_viabilidade_operativo', null);
$projeto_viabilidade_instrumento=getParam($_REQUEST, 'projeto_viabilidade_instrumento', null);
$projeto_viabilidade_recurso=getParam($_REQUEST, 'projeto_viabilidade_recurso', null);
$projeto_viabilidade_problema=getParam($_REQUEST, 'projeto_viabilidade_problema', null);
$projeto_viabilidade_demanda=getParam($_REQUEST, 'projeto_viabilidade_demanda', null);
$projeto_viabilidade_programa=getParam($_REQUEST, 'projeto_viabilidade_programa', null);
$projeto_viabilidade_licao=getParam($_REQUEST, 'projeto_viabilidade_licao', null);
$projeto_viabilidade_evento=getParam($_REQUEST, 'projeto_viabilidade_evento', null);
$projeto_viabilidade_link=getParam($_REQUEST, 'projeto_viabilidade_link', null);
$projeto_viabilidade_avaliacao=getParam($_REQUEST, 'projeto_viabilidade_avaliacao', null);
$projeto_viabilidade_tgn=getParam($_REQUEST, 'projeto_viabilidade_tgn', null);
$projeto_viabilidade_brainstorm=getParam($_REQUEST, 'projeto_viabilidade_brainstorm', null);
$projeto_viabilidade_gut=getParam($_REQUEST, 'projeto_viabilidade_gut', null);
$projeto_viabilidade_causa_efeito=getParam($_REQUEST, 'projeto_viabilidade_causa_efeito', null);
$projeto_viabilidade_arquivo=getParam($_REQUEST, 'projeto_viabilidade_arquivo', null);
$projeto_viabilidade_forum=getParam($_REQUEST, 'projeto_viabilidade_forum', null);
$projeto_viabilidade_checklist=getParam($_REQUEST, 'projeto_viabilidade_checklist', null);
$projeto_viabilidade_agenda=getParam($_REQUEST, 'projeto_viabilidade_agenda', null);
$projeto_viabilidade_agrupamento=getParam($_REQUEST, 'projeto_viabilidade_agrupamento', null);
$projeto_viabilidade_patrocinador=getParam($_REQUEST, 'projeto_viabilidade_patrocinador', null);
$projeto_viabilidade_template=getParam($_REQUEST, 'projeto_viabilidade_template', null);
$projeto_viabilidade_painel=getParam($_REQUEST, 'projeto_viabilidade_painel', null);
$projeto_viabilidade_painel_odometro=getParam($_REQUEST, 'projeto_viabilidade_painel_odometro', null);
$projeto_viabilidade_painel_composicao=getParam($_REQUEST, 'projeto_viabilidade_painel_composicao', null);
$projeto_viabilidade_tr=getParam($_REQUEST, 'projeto_viabilidade_tr', null);
$projeto_viabilidade_me=getParam($_REQUEST, 'projeto_viabilidade_me', null);
$projeto_viabilidade_acao_item=getParam($_REQUEST, 'projeto_viabilidade_acao_item', null);
$projeto_viabilidade_beneficio=getParam($_REQUEST, 'projeto_viabilidade_beneficio', null);
$projeto_viabilidade_painel_slideshow=getParam($_REQUEST, 'projeto_viabilidade_painel_slideshow', null);
$projeto_viabilidade_projeto_viabilidade=getParam($_REQUEST, 'projeto_viabilidade_projeto_viabilidade', null);
$projeto_viabilidade_projeto_abertura=getParam($_REQUEST, 'projeto_viabilidade_projeto_abertura', null);
$projeto_viabilidade_plano_gestao=getParam($_REQUEST, 'projeto_viabilidade_plano_gestao', null);
$projeto_viabilidade_ssti=getParam($_REQUEST, 'projeto_viabilidade_ssti', null);
$projeto_viabilidade_laudo=getParam($_REQUEST, 'projeto_viabilidade_laudo', null);
$projeto_viabilidade_trelo=getParam($_REQUEST, 'projeto_viabilidade_trelo', null);
$projeto_viabilidade_trelo_cartao=getParam($_REQUEST, 'projeto_viabilidade_trelo_cartao', null);
$projeto_viabilidade_pdcl=getParam($_REQUEST, 'projeto_viabilidade_pdcl', null);
$projeto_viabilidade_pdcl_item=getParam($_REQUEST, 'projeto_viabilidade_pdcl_item', null);



$sql = new BDConsulta;







$demanda = new CDemanda();
if ($demanda_id) $demanda->load($demanda_id);


$obj = new CViabilidade();

if ($projeto_viabilidade_id){
	$obj->load($projeto_viabilidade_id);
	$cia_id=$obj->projeto_viabilidade_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
	if (
		$projeto_viabilidade_projeto || 
		$projeto_viabilidade_tarefa || 
		$projeto_viabilidade_perspectiva || 
		$projeto_viabilidade_tema || 
		$projeto_viabilidade_objetivo || 
		$projeto_viabilidade_fator || 
		$projeto_viabilidade_estrategia || 
		$projeto_viabilidade_meta || 
		$projeto_viabilidade_pratica || 
		$projeto_viabilidade_acao || 
		$projeto_viabilidade_canvas || 
		$projeto_viabilidade_risco || 
		$projeto_viabilidade_risco_resposta || 
		$projeto_viabilidade_indicador || 
		$projeto_viabilidade_calendario || 
		$projeto_viabilidade_monitoramento || 
		$projeto_viabilidade_ata || 
		$projeto_viabilidade_mswot || 
		$projeto_viabilidade_swot || 
		$projeto_viabilidade_operativo || 
		$projeto_viabilidade_instrumento || 
		$projeto_viabilidade_recurso || 
		$projeto_viabilidade_problema || 
		$projeto_viabilidade_demanda || 
		$projeto_viabilidade_programa || 
		$projeto_viabilidade_licao || 
		$projeto_viabilidade_evento || 
		$projeto_viabilidade_link || 
		$projeto_viabilidade_avaliacao || 
		$projeto_viabilidade_tgn || 
		$projeto_viabilidade_brainstorm || 
		$projeto_viabilidade_gut || 
		$projeto_viabilidade_causa_efeito || 
		$projeto_viabilidade_arquivo || 
		$projeto_viabilidade_forum || 
		$projeto_viabilidade_checklist || 
		$projeto_viabilidade_agenda || 
		$projeto_viabilidade_agrupamento || 
		$projeto_viabilidade_patrocinador || 
		$projeto_viabilidade_template || 
		$projeto_viabilidade_painel || 
		$projeto_viabilidade_painel_odometro || 
		$projeto_viabilidade_painel_composicao || 
		$projeto_viabilidade_tr || 
		$projeto_viabilidade_me || 
		$projeto_viabilidade_acao_item || 
		$projeto_viabilidade_beneficio || 
		$projeto_viabilidade_painel_slideshow || 
		$projeto_viabilidade_projeto_viabilidade || 
		$projeto_viabilidade_projeto_abertura || 
		$projeto_viabilidade_plano_gestao|| 
		$projeto_viabilidade_ssti || 
		$projeto_viabilidade_laudo || 
		$projeto_viabilidade_trelo || 
		$projeto_viabilidade_trelo_cartao || 
		$projeto_viabilidade_pdcl || 
		$projeto_viabilidade_pdcl_item
		){
		$sql->adTabela('cias');
		if ($projeto_viabilidade_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
		elseif ($projeto_viabilidade_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
		elseif ($projeto_viabilidade_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		elseif ($projeto_viabilidade_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		elseif ($projeto_viabilidade_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
		elseif ($projeto_viabilidade_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
		elseif ($projeto_viabilidade_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		elseif ($projeto_viabilidade_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
		elseif ($projeto_viabilidade_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		elseif ($projeto_viabilidade_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		elseif ($projeto_viabilidade_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
		elseif ($projeto_viabilidade_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
		elseif ($projeto_viabilidade_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
		elseif ($projeto_viabilidade_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		elseif ($projeto_viabilidade_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
		elseif ($projeto_viabilidade_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
		elseif ($projeto_viabilidade_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
		elseif ($projeto_viabilidade_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
		elseif ($projeto_viabilidade_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
		elseif ($projeto_viabilidade_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
		elseif ($projeto_viabilidade_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
		elseif ($projeto_viabilidade_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
		elseif ($projeto_viabilidade_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
		elseif ($projeto_viabilidade_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
		elseif ($projeto_viabilidade_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
		elseif ($projeto_viabilidade_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
		elseif ($projeto_viabilidade_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
		elseif ($projeto_viabilidade_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
		elseif ($projeto_viabilidade_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
		elseif ($projeto_viabilidade_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
		elseif ($projeto_viabilidade_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
		elseif ($projeto_viabilidade_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
		elseif ($projeto_viabilidade_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
		elseif ($projeto_viabilidade_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
		elseif ($projeto_viabilidade_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
		elseif ($projeto_viabilidade_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
		elseif ($projeto_viabilidade_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
		elseif ($projeto_viabilidade_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
		elseif ($projeto_viabilidade_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
		elseif ($projeto_viabilidade_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
		elseif ($projeto_viabilidade_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
		elseif ($projeto_viabilidade_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
		elseif ($projeto_viabilidade_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
		elseif ($projeto_viabilidade_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
		elseif ($projeto_viabilidade_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
		elseif ($projeto_viabilidade_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
		elseif ($projeto_viabilidade_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
		elseif ($projeto_viabilidade_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
		elseif ($projeto_viabilidade_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
		elseif ($projeto_viabilidade_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
		elseif ($projeto_viabilidade_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
		elseif ($projeto_viabilidade_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
		elseif ($projeto_viabilidade_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
		elseif ($projeto_viabilidade_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
		elseif ($projeto_viabilidade_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
		elseif ($projeto_viabilidade_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
		elseif ($projeto_viabilidade_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
	
		if ($projeto_viabilidade_tarefa) $sql->adOnde('tarefa_id = '.(int)$projeto_viabilidade_tarefa);
		elseif ($projeto_viabilidade_projeto) $sql->adOnde('projeto_id = '.(int)$projeto_viabilidade_projeto);
		elseif ($projeto_viabilidade_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$projeto_viabilidade_perspectiva);
		elseif ($projeto_viabilidade_tema) $sql->adOnde('tema_id = '.(int)$projeto_viabilidade_tema);
		elseif ($projeto_viabilidade_objetivo) $sql->adOnde('objetivo_id = '.(int)$projeto_viabilidade_objetivo);
		elseif ($projeto_viabilidade_fator) $sql->adOnde('fator_id = '.(int)$projeto_viabilidade_fator);
		elseif ($projeto_viabilidade_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$projeto_viabilidade_estrategia);
		elseif ($projeto_viabilidade_meta) $sql->adOnde('pg_meta_id = '.(int)$projeto_viabilidade_meta);
		elseif ($projeto_viabilidade_pratica) $sql->adOnde('pratica_id = '.(int)$projeto_viabilidade_pratica);
		elseif ($projeto_viabilidade_acao) $sql->adOnde('plano_acao_id = '.(int)$projeto_viabilidade_acao);
		elseif ($projeto_viabilidade_canvas) $sql->adOnde('canvas_id = '.(int)$projeto_viabilidade_canvas);
		elseif ($projeto_viabilidade_risco) $sql->adOnde('risco_id = '.(int)$projeto_viabilidade_risco);
		elseif ($projeto_viabilidade_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$projeto_viabilidade_risco_resposta);
		elseif ($projeto_viabilidade_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$projeto_viabilidade_indicador);
		elseif ($projeto_viabilidade_calendario) $sql->adOnde('calendario_id = '.(int)$projeto_viabilidade_calendario);
		elseif ($projeto_viabilidade_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$projeto_viabilidade_monitoramento);
		elseif ($projeto_viabilidade_ata) $sql->adOnde('ata_id = '.(int)$projeto_viabilidade_ata);
		elseif ($projeto_viabilidade_mswot) $sql->adOnde('mswot_id = '.(int)$projeto_viabilidade_mswot);
		elseif ($projeto_viabilidade_swot) $sql->adOnde('swot_id = '.(int)$projeto_viabilidade_swot);
		elseif ($projeto_viabilidade_operativo) $sql->adOnde('operativo_id = '.(int)$projeto_viabilidade_operativo);
		elseif ($projeto_viabilidade_instrumento) $sql->adOnde('instrumento_id = '.(int)$projeto_viabilidade_instrumento);
		elseif ($projeto_viabilidade_recurso) $sql->adOnde('recurso_id = '.(int)$projeto_viabilidade_recurso);
		elseif ($projeto_viabilidade_problema) $sql->adOnde('problema_id = '.(int)$projeto_viabilidade_problema);
		elseif ($projeto_viabilidade_demanda) $sql->adOnde('demanda_id = '.(int)$projeto_viabilidade_demanda);
		elseif ($projeto_viabilidade_programa) $sql->adOnde('programa_id = '.(int)$projeto_viabilidade_programa);
		elseif ($projeto_viabilidade_licao) $sql->adOnde('licao_id = '.(int)$projeto_viabilidade_licao);
		elseif ($projeto_viabilidade_evento) $sql->adOnde('evento_id = '.(int)$projeto_viabilidade_evento);
		elseif ($projeto_viabilidade_link) $sql->adOnde('link_id = '.(int)$projeto_viabilidade_link);
		elseif ($projeto_viabilidade_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$projeto_viabilidade_avaliacao);
		elseif ($projeto_viabilidade_tgn) $sql->adOnde('tgn_id = '.(int)$projeto_viabilidade_tgn);
		elseif ($projeto_viabilidade_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$projeto_viabilidade_brainstorm);
		elseif ($projeto_viabilidade_gut) $sql->adOnde('gut_id = '.(int)$projeto_viabilidade_gut);
		elseif ($projeto_viabilidade_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$projeto_viabilidade_causa_efeito);
		elseif ($projeto_viabilidade_arquivo) $sql->adOnde('arquivo_id = '.(int)$projeto_viabilidade_arquivo);
		elseif ($projeto_viabilidade_forum) $sql->adOnde('forum_id = '.(int)$projeto_viabilidade_forum);
		elseif ($projeto_viabilidade_checklist) $sql->adOnde('checklist_id = '.(int)$projeto_viabilidade_checklist);
		elseif ($projeto_viabilidade_agenda) $sql->adOnde('agenda_id = '.(int)$projeto_viabilidade_agenda);
		elseif ($projeto_viabilidade_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$projeto_viabilidade_agrupamento);
		elseif ($projeto_viabilidade_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$projeto_viabilidade_patrocinador);
		elseif ($projeto_viabilidade_template) $sql->adOnde('template_id = '.(int)$projeto_viabilidade_template);
		elseif ($projeto_viabilidade_painel) $sql->adOnde('painel_id = '.(int)$projeto_viabilidade_painel);
		elseif ($projeto_viabilidade_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$projeto_viabilidade_painel_odometro);
		elseif ($projeto_viabilidade_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$projeto_viabilidade_painel_composicao);
		elseif ($projeto_viabilidade_tr) $sql->adOnde('tr_id = '.(int)$projeto_viabilidade_tr);
		elseif ($projeto_viabilidade_me) $sql->adOnde('me_id = '.(int)$projeto_viabilidade_me);
		elseif ($projeto_viabilidade_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$projeto_viabilidade_acao_item);
		elseif ($projeto_viabilidade_beneficio) $sql->adOnde('beneficio_id = '.(int)$projeto_viabilidade_beneficio);
		elseif ($projeto_viabilidade_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$projeto_viabilidade_painel_slideshow);
		elseif ($projeto_viabilidade_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_projeto_viabilidade);
		elseif ($projeto_viabilidade_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$projeto_viabilidade_projeto_abertura);
		elseif ($projeto_viabilidade_plano_gestao) $sql->adOnde('pg_id = '.(int)$projeto_viabilidade_plano_gestao);
		elseif ($projeto_viabilidade_ssti) $sql->adOnde('ssti_id = '.(int)$projeto_viabilidade_ssti);
		elseif ($projeto_viabilidade_laudo) $sql->adOnde('laudo_id = '.(int)$projeto_viabilidade_laudo);
		elseif ($projeto_viabilidade_trelo) $sql->adOnde('trelo_id = '.(int)$projeto_viabilidade_trelo);
		elseif ($projeto_viabilidade_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$projeto_viabilidade_trelo_cartao);
		elseif ($projeto_viabilidade_pdcl) $sql->adOnde('pdcl_id = '.(int)$projeto_viabilidade_pdcl);
		elseif ($projeto_viabilidade_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$projeto_viabilidade_pdcl_item);
		
		
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}


if(!(($projeto_viabilidade_id && permiteEditarViabilidade($obj->projeto_viabilidade_acesso,$projeto_viabilidade_id) && $Aplic->checarModulo('projetos', 'editar', $Aplic->usuario_id, 'viabilidade')) || (!$projeto_viabilidade_id && $Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'viabilidade')))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'viabilidade\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$projeto_viabilidade_acesso = getSisValor('NivelAcesso','','','sisvalor_id');




$botoesTitulo = new CBlocoTitulo(($projeto_viabilidade_id ? 'Editar Estudo de Viabilidade' : 'Criar Estudo de Viabilidade'), 'viabilidade.gif', $m, $m.'.'.$a);


$botoesTitulo->mostrar();

$usuarios_selecionados=array();
$patrocinadores_selecionados=array();
$contatos_selecionados=array();
$depts_selecionados=array();
$cias_selecionadas=array();
if ($projeto_viabilidade_id) {
	$sql->adTabela('projeto_viabilidade_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_viabilidade_patrocinadores');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$patrocinadores_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_viabilidade_interessados');
	$sql->adCampo('contato_id');
	$sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_viabilidade_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_viabilidade_dept');
	$sql->adCampo('projeto_viabilidade_dept_dept');
	$sql->adOnde('projeto_viabilidade_dept_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
	$depts_selecionados=$sql->carregarColuna();
	$sql->limpar();
	
	
	if ($Aplic->profissional){
		$sql->adTabela('projeto_viabilidade_cia');
		$sql->adCampo('projeto_viabilidade_cia_cia');
		$sql->adOnde('projeto_viabilidade_cia_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}
else if($demanda_id){
	$sql->adTabela('demanda_usuarios');
	$sql->adCampo('demanda_usuarios.usuario_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('demanda_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('demanda_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('demanda_id = '.(int)$demanda_id);
	$depts_selecionados=$sql->carregarColuna();
	$sql->limpar();
	
	if ($Aplic->profissional){
		$sql->adTabela('demanda_cia');
		$sql->adCampo('demanda_cia_cia');
		$sql->adOnde('demanda_cia_demanda = '.(int)$demanda_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	
	}


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_viabilidade" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="projeto_viabilidade_id" id="projeto_viabilidade_id" value="'.$projeto_viabilidade_id.'" />';
echo '<input name="projeto_viabilidade_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="projeto_viabilidade_patrocinadores" type="hidden" value="'.implode(',', $patrocinadores_selecionados).'" />';
echo '<input name="projeto_viabilidade_interessados" type="hidden" value="'.implode(',', $contatos_selecionados).'" />';
echo '<input name="projeto_viabilidade_depts" id="projeto_viabilidade_depts" type="hidden" value="'.implode(',',$depts_selecionados).'" />';
echo '<input name="projeto_viabilidade_cias"  id="projeto_viabilidade_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
$uuid=($projeto_viabilidade_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="projeto_viabilidade_demanda" value="'.($obj->projeto_viabilidade_demanda ? $obj->projeto_viabilidade_demanda : $demanda_id).'" />';
echo '<input type="hidden" name="projeto_viabilidade_projeto" value="'.$obj->projeto_viabilidade_projeto.'" />';









echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Possível Nome d'.$config['genero_projeto'].' '.$config['projeto'], 'Tod'.$config['genero_projeto'].' '.$config['projeto'].' necessita ter um nome para identificação.').'Nome d'.$config['genero_projeto'].' '.$config['projeto'].':'.dicaF().'</td><td><input type="text" name="projeto_viabilidade_nome" value="'.($obj->projeto_viabilidade_nome ? $obj->projeto_viabilidade_nome : $demanda->demanda_nome).'" style="width:600px;" class="texto" />*</td></tr>';
$viavel=array(1=>'Sim', -1=>'Não');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viável', 'Baseado na análise deste estudo a demanda é viável de ser transformar em um projeto').'<b>Viável</b>:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($viavel, 'projeto_viabilidade_viavel', 'class="texto"', $obj->projeto_viabilidade_viavel).'</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' do Estudo de Viabilidade', 'A qual '.$config['organizacao'].' pertence este estudo de viabilidade.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'projeto_viabilidade_cia', 'class=texto size=1 style="width:280px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

if ($Aplic->profissional) {
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else $saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}


if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="projeto_viabilidade_dept" id="projeto_viabilidade_dept" value="'.($projeto_viabilidade_id ? $obj->projeto_viabilidade_dept : $demanda->demanda_dept).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($projeto_viabilidade_id ? $obj->projeto_viabilidade_dept : $demanda->demanda_dept)).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

$saida_depts='';
if (count($depts_selecionados)) {
		$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Responsável pela Demanda', 'Todo estudo de viabilidade deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_viabilidade_responsavel" name="projeto_viabilidade_responsavel" value="'.($obj->projeto_viabilidade_responsavel ? $obj->projeto_viabilidade_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->projeto_viabilidade_responsavel ? $obj->projeto_viabilidade_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';


$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s Designados', 'Clique para visualizar '.$config['genero_usuario'].'s demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';

$saida_patrocinadores='';
if (count($patrocinadores_selecionados)) {
		$saida_patrocinadores.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_patrocinadores.= '<tr><td>'.link_contato($patrocinadores_selecionados[0],'','','esquerda');
		$qnt_lista_patrocinadores=count($patrocinadores_selecionados);
		if ($qnt_lista_patrocinadores > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_patrocinadores; $i < $i_cmp; $i++) $lista.=link_contato($patrocinadores_selecionados[$i],'','','esquerda').'<br>';
				$saida_patrocinadores.= dica('Outros Patrocinadores', 'Clique para visualizar os demais patrocinadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_patrocinadores\');">(+'.($qnt_lista_patrocinadores - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_patrocinadores"><br>'.$lista.'</span>';
				}
		$saida_patrocinadores.= '</td></tr></table>';
		}
else $saida_patrocinadores.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Patrocinador', 'Qual contato foi elencado como patrocinador envolvido.').'Patrocinador:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_patrocinadores">'.$saida_patrocinadores.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar patrocinador.','popPatrocinadores()').'</td></tr></table></td></tr>';


$saida_contatos='';
if (count($contatos_selecionados)) {
		$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
		$qnt_lista_contatos=count($contatos_selecionados);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
				$saida_contatos.= dica('Outros Interessados', 'Clique para visualizar os demais interessados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos.= '</td></tr></table>';
		}
else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Parte Interessada', 'Qual contato foi elencado como parte interessada.').'Parte interessada:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar parte interessada.','popContatos()').'</td></tr></table></td></tr>';







if ($exibir['projeto_viabilidade_codigo'])echo '<tr><td align="right">'.dica('Código', 'Escreva, caso exista, o código do estudo de viabilidade.').'Código:'.dicaF().'</td><td><input type="text" style="width:284px;" class="texto" name="projeto_viabilidade_codigo" value="'.($obj->projeto_viabilidade_codigo ? $obj->projeto_viabilidade_codigo : $demanda->demanda_codigo).'" size="30" maxlength="255" /></td></tr>';
if ($exibir['projeto_viabilidade_ano']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ano', 'A qual ano deverá o estudo de viabilidade estar relacionada.').'Ano:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="projeto_viabilidade_ano" value="'.($obj->projeto_viabilidade_ano ? $obj->projeto_viabilidade_ano : ($demanda->demanda_ano ? $demanda->demanda_ano : date('Y'))).'" size="4" class="texto" /></td></tr>';
$setor = array('' => '') + getSisValor('Setor');
if ($exibir['projeto_viabilidade_setor']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o estudo de viabilidade.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_viabilidade_setor', 'style="width:284px;" class="texto" onchange="mudar_segmento();"', ($obj->projeto_viabilidade_setor ? $obj->projeto_viabilidade_setor : $demanda->demanda_setor)).'</td></tr>';
$segmento=array('' => '');
if (($obj->projeto_viabilidade_setor ? $obj->projeto_viabilidade_setor : $demanda->demanda_setor)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Segmento"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_viabilidade_setor ? $obj->projeto_viabilidade_setor : $demanda->demanda_setor).'"');
	$sql->adOrdem('sisvalor_valor');
	$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_viabilidade_segmento']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o estudo de viabilidade.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_viabilidade_segmento', 'style="width:284px;" class="texto" onchange="mudar_intervencao();"', ($obj->projeto_viabilidade_segmento ? $obj->projeto_viabilidade_segmento : $demanda->demanda_segmento)).'</div></td></tr>';
$intervencao=array('' => '');
if (($obj->projeto_viabilidade_segmento ? $obj->projeto_viabilidade_segmento : $demanda->demanda_segmento)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Intervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_viabilidade_segmento ? $obj->projeto_viabilidade_segmento : $demanda->demanda_segmento).'"');
	$sql->adOrdem('sisvalor_valor');
	$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_viabilidade_intervencao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o estudo de viabilidade.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_viabilidade_intervencao', 'style="width:284px;" class="texto" onchange="mudar_tipo_intervencao();"', ($obj->projeto_viabilidade_intervencao ? $obj->projeto_viabilidade_intervencao : $demanda->demanda_intervencao)).'</div></td></tr>';

$tipo_intervencao=array('' => '');
if (($obj->projeto_viabilidade_intervencao ? $obj->projeto_viabilidade_intervencao : $demanda->demanda_intervencao)){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.($obj->projeto_viabilidade_intervencao ? $obj->projeto_viabilidade_intervencao : $demanda->demanda_intervencao).'"');
	$sql->adOrdem('sisvalor_valor');
	$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['projeto_viabilidade_tipo_intervencao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o estudo de viabilidade.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_viabilidade_tipo_intervencao', 'style="width:284px;" class="texto"', ($obj->projeto_viabilidade_tipo_intervencao ? $obj->projeto_viabilidade_tipo_intervencao : $demanda->demanda_tipo_intervencao)).'</div></td></tr>';

echo '<input type="hidden" name="projeto_viabilidade_data" value="'.($obj->projeto_viabilidade_data ? $obj->projeto_viabilidade_data  : date('Y-m-d H:i:s')).'" />';
if ($exibir['projeto_viabilidade_necessidade']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Necessidade', 'Descrever o problema que se deseja resolver por meio do projeto. Se possível, apresentar dados numéricos que deem sustentação à demanda (custos, desperdício de recursos etc).').'Necessidade:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_necessidade" style="width:800px;" class="textarea">'.($obj->projeto_viabilidade_necessidade ? $obj->projeto_viabilidade_necessidade : $demanda->demanda_identificacao).'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_alinhamento']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Alinhamento Estratégico', 'Descrever o alinhamento da demanda com os instrumentos de planejamento institucional. Esse item consta no Documento de Oficialização da Demanda (DOD) pode ser complementado/revisado neste documento.').'Alinhamento estratégico:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_alinhamento" style="width:800px;" class="textarea">'.($obj->projeto_viabilidade_alinhamento ? $obj->projeto_viabilidade_alinhamento : $demanda->demanda_alinhamento).'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_requisitos']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Requisitos Básicos', 'Descrever os principais requisitos identificados para o projeto, a partir da requisição da área solicitante. Os requisitos podem ser: de negócio, tecnológico, recursos humanos, legais, segurança, sociais, ambientais, culturais, etc.').'Requisitos básicos:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_requisitos" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_requisitos.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_solucoes']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Soluções Possíveis', 'Listar as possibilidades de atendimento da necessidade, com análise das vantagens e desvantagens de cada opção.').'Soluções possíveis:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_solucoes" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_solucoes.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_viabilidade_tecnica']) echo '<tr align="right" style="white-space: nowrap"><td align="right">'.dica('Viabilidade Técnica', 'Avaliar a viabilidade técnica do projeto, observando a capacidade técnica da organização para realizar o projeto, estrutura física (material e estrutural) e de pessoal (conhecimento técnico).').'Viabilidade técnica:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_viabilidade_tecnica" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_viabilidade_tecnica.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_financeira']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viabilidade Financeira', 'Levantar e avaliar os custos estimados para cada solução possível e verificar a disponibilidade orçamentária para a execução do projeto.').'Viabilidade financeira:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_financeira" style="width:800px;" class="textarea">'.($projeto_viabilidade_id ? $obj->projeto_viabilidade_financeira : $demanda->demanda_fonte_recurso).'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_institucional']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viabilidade Institucional', 'Avaliar ambiente institucional, o que inclui o clima político e organizacional para a realização do projeto, identificando possíveis entraves e oportunidades, assim como o impacto dos resultados do projeto sobre as rotinas da instituição.').'Viabilidade institucional:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_institucional" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_institucional.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_solucao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicação de Solução', 'Indicar a solução escolhida estimando o tempo  para implantação da solução e justificá-la, observando o alinhamento da estratégia da organização e a necessidade de negócio.').'Indicação de solução:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_solucao" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_solucao.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_continuidade']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Parecer Sobre a Continuidade', 'Os envolvidos na elaboração deste documento deverão deliberar sobre a continuidade ou não do projeto e justificar. Obs. colocar a data da decisão e descrever o nome dos decisores e seus respectivos cargos.').'Parecer sobre a continuidade:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_continuidade" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_continuidade.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_tempo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Parecer Sobre o Tempo', 'Os envolvidos na elaboração deste documento deverão deliberar sobre o tempo necessário para a execução do projeto.').'Parecer sobre o tempo:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_tempo" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_tempo.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_custo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Parecer Sobre o Custo', 'Os envolvidos na elaboração deste documento deverão deliberar sobre o custo do projeto.').'Parecer sobre o custo:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_custo" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_custo.'</textarea></td></tr>';
if ($exibir['projeto_viabilidade_observacao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Observações', 'Observações sobre o estudo de viabilidades.').'Observações:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="projeto_viabilidade_observacao" style="width:800px;" class="textarea">'.$obj->projeto_viabilidade_observacao.'</textarea></td></tr>';


if ($Aplic->profissional && $exibir['moeda']){
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda padrão utilizada.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'projeto_viabilidade_moeda', 'class=texto size=1', ($obj->projeto_viabilidade_moeda ? $obj->projeto_viabilidade_moeda : 1)).'</td></tr>';
	}	
else echo '<input type="hidden" name="projeto_viabilidade_moeda" id="projeto_viabilidade_moeda" value="'.($obj->projeto_viabilidade_moeda ? $obj->projeto_viabilidade_moeda : 1).'" />';










if ($uuid){
	$sql->adTabela('demanda_gestao');
	$sql->adCampo('demanda_gestao.*');
	$sql->adOnde('demanda_gestao_demanda = '.(int)$demanda_id);
	$lista = $sql->lista();
	$sql->limpar();
	foreach ($lista as $linha){
		$sql->adTabela('projeto_viabilidade_gestao');
		$sql->adInserir('projeto_viabilidade_gestao_uuid', $uuid);
		if ($linha['demanda_gestao_projeto']) $sql->adInserir('projeto_viabilidade_gestao_projeto', $linha['demanda_gestao_projeto']);
		if ($linha['demanda_gestao_tarefa']) $sql->adInserir('projeto_viabilidade_gestao_tarefa', $linha['demanda_gestao_tarefa']);
		
		elseif ($linha['demanda_gestao_perspectiva']) $sql->adInserir('projeto_viabilidade_gestao_perspectiva', $linha['demanda_gestao_perspectiva']);
		elseif ($linha['demanda_gestao_tema']) $sql->adInserir('projeto_viabilidade_gestao_tema', $linha['demanda_gestao_tema']);
		elseif ($linha['demanda_gestao_objetivo']) $sql->adInserir('projeto_viabilidade_gestao_objetivo', $linha['demanda_gestao_objetivo']);
		elseif ($linha['demanda_gestao_fator']) $sql->adInserir('projeto_viabilidade_gestao_fator', $linha['demanda_gestao_fator']);
		elseif ($linha['demanda_gestao_estrategia']) $sql->adInserir('projeto_viabilidade_gestao_estrategia', $linha['demanda_gestao_estrategia']);
		elseif ($linha['demanda_gestao_meta']) $sql->adInserir('projeto_viabilidade_gestao_meta', $linha['demanda_gestao_meta']);
		elseif ($linha['demanda_gestao_pratica']) $sql->adInserir('projeto_viabilidade_gestao_pratica', $linha['demanda_gestao_pratica']);
		elseif ($linha['demanda_gestao_indicador']) $sql->adInserir('projeto_viabilidade_gestao_indicador', $linha['demanda_gestao_indicador']);
		elseif ($linha['demanda_gestao_acao']) $sql->adInserir('projeto_viabilidade_gestao_acao', $linha['demanda_gestao_acao']);
		elseif ($linha['demanda_gestao_canvas']) $sql->adInserir('projeto_viabilidade_gestao_canvas', $linha['demanda_gestao_canvas']);
		elseif ($linha['demanda_gestao_risco']) $sql->adInserir('projeto_viabilidade_gestao_risco', $linha['demanda_gestao_risco']);
		elseif ($linha['demanda_gestao_risco_resposta']) $sql->adInserir('projeto_viabilidade_gestao_risco_resposta', $linha['demanda_gestao_risco_resposta']);
		elseif ($linha['demanda_gestao_calendario']) $sql->adInserir('projeto_viabilidade_gestao_calendario', $linha['demanda_gestao_calendario']);
		elseif ($linha['demanda_gestao_monitoramento']) $sql->adInserir('projeto_viabilidade_gestao_monitoramento', $linha['demanda_gestao_monitoramento']);
		elseif ($linha['demanda_gestao_ata']) $sql->adInserir('projeto_viabilidade_gestao_ata', $linha['demanda_gestao_ata']);
		elseif ($linha['demanda_gestao_mswot']) $sql->adInserir('projeto_viabilidade_gestao_mswot', $linha['demanda_gestao_mswot']);
		elseif ($linha['demanda_gestao_swot']) $sql->adInserir('projeto_viabilidade_gestao_swot', $linha['demanda_gestao_swot']);
		elseif ($linha['demanda_gestao_operativo']) $sql->adInserir('projeto_viabilidade_gestao_operativo', $linha['demanda_gestao_operativo']);
		elseif ($linha['demanda_gestao_instrumento']) $sql->adInserir('projeto_viabilidade_gestao_instrumento', $linha['demanda_gestao_instrumento']);
		elseif ($linha['demanda_gestao_recurso']) $sql->adInserir('projeto_viabilidade_gestao_recurso', $linha['demanda_gestao_recurso']);
		elseif ($linha['demanda_gestao_problema']) $sql->adInserir('projeto_viabilidade_gestao_problema', $linha['demanda_gestao_problema']);
		
		elseif ($linha['demanda_gestao_semelhante']) $sql->adInserir('projeto_viabilidade_gestao_demanda', $linha['demanda_gestao_semelhante']);
		
		elseif ($linha['demanda_gestao_programa']) $sql->adInserir('projeto_viabilidade_gestao_programa', $linha['demanda_gestao_programa']);
		elseif ($linha['demanda_gestao_licao']) $sql->adInserir('projeto_viabilidade_gestao_licao', $linha['demanda_gestao_licao']);
		elseif ($linha['demanda_gestao_evento']) $sql->adInserir('projeto_viabilidade_gestao_evento', $linha['demanda_gestao_evento']);
		elseif ($linha['demanda_gestao_link']) $sql->adInserir('projeto_viabilidade_gestao_link', $linha['demanda_gestao_link']);
		elseif ($linha['demanda_gestao_avaliacao']) $sql->adInserir('projeto_viabilidade_gestao_avaliacao', $linha['demanda_gestao_avaliacao']);
		elseif ($linha['demanda_gestao_tgn']) $sql->adInserir('projeto_viabilidade_gestao_tgn', $linha['demanda_gestao_tgn']);
		elseif ($linha['demanda_gestao_brainstorm']) $sql->adInserir('projeto_viabilidade_gestao_brainstorm', $linha['demanda_gestao_brainstorm']);
		elseif ($linha['demanda_gestao_gut']) $sql->adInserir('projeto_viabilidade_gestao_gut', $linha['demanda_gestao_gut']);
		elseif ($linha['demanda_gestao_causa_efeito']) $sql->adInserir('projeto_viabilidade_gestao_causa_efeito', $linha['demanda_gestao_causa_efeito']);
		elseif ($linha['demanda_gestao_arquivo']) $sql->adInserir('projeto_viabilidade_gestao_arquivo', $linha['demanda_gestao_arquivo']);
		elseif ($linha['demanda_gestao_forum']) $sql->adInserir('projeto_viabilidade_gestao_forum', $linha['demanda_gestao_forum']);
		elseif ($linha['demanda_gestao_checklist']) $sql->adInserir('projeto_viabilidade_gestao_checklist', $linha['demanda_gestao_checklist']);
		elseif ($linha['demanda_gestao_agenda']) $sql->adInserir('projeto_viabilidade_gestao_agenda', $linha['demanda_gestao_agenda']);
		elseif ($linha['demanda_gestao_agrupamento']) $sql->adInserir('projeto_viabilidade_gestao_agrupamento', $linha['demanda_gestao_agrupamento']);
		elseif ($linha['demanda_gestao_patrocinador']) $sql->adInserir('projeto_viabilidade_gestao_patrocinador', $linha['demanda_gestao_patrocinador']);
		elseif ($linha['demanda_gestao_template']) $sql->adInserir('projeto_viabilidade_gestao_template', $linha['demanda_gestao_template']);
		elseif ($linha['demanda_gestao_painel']) $sql->adInserir('projeto_viabilidade_gestao_painel', $linha['demanda_gestao_painel']);
		elseif ($linha['demanda_gestao_painel_odometro']) $sql->adInserir('projeto_viabilidade_gestao_painel_odometro', $linha['demanda_gestao_painel_odometro']);
		elseif ($linha['demanda_gestao_painel_composicao']) $sql->adInserir('projeto_viabilidade_gestao_painel_composicao', $linha['demanda_gestao_painel_composicao']);
		elseif ($linha['demanda_gestao_tr']) $sql->adInserir('projeto_viabilidade_gestao_tr', $linha['demanda_gestao_tr']);
		elseif ($linha['demanda_gestao_me']) $sql->adInserir('projeto_viabilidade_gestao_me', $linha['demanda_gestao_me']);
		elseif ($linha['demanda_gestao_acao_item']) $sql->adInserir('projeto_viabilidade_gestao_acao_item', $linha['demanda_gestao_acao_item']);
		elseif ($linha['demanda_gestao_beneficio']) $sql->adInserir('projeto_viabilidade_gestao_beneficio', $linha['demanda_gestao_beneficio']);
		elseif ($linha['demanda_gestao_painel_slideshow']) $sql->adInserir('projeto_viabilidade_gestao_painel_slideshow', $linha['demanda_gestao_painel_slideshow']);
		
		elseif ($linha['demanda_gestao_projeto_viabilidade']) $sql->adInserir('projeto_viabilidade_gestao_semelhante', $linha['demanda_gestao_projeto_viabilidade']);
		
		elseif ($linha['demanda_gestao_projeto_abertura']) $sql->adInserir('projeto_viabilidade_gestao_projeto_abertura', $linha['demanda_gestao_projeto_abertura']);
		elseif ($linha['demanda_gestao_plano_gestao']) $sql->adInserir('projeto_viabilidade_gestao_plano_gestao', $linha['demanda_gestao_plano_gestao']);
		elseif ($linha['demanda_gestao_ssti']) $sql->adInserir('projeto_viabilidade_gestao_ssti', $linha['demanda_gestao_plano_gestao']);
		elseif ($linha['demanda_gestao_laudo']) $sql->adInserir('projeto_viabilidade_gestao_laudo', $linha['demanda_gestao_laudo']);
		elseif ($linha['demanda_gestao_trelo']) $sql->adInserir('projeto_viabilidade_gestao_trelo', $linha['demanda_gestao_trelo']);
		elseif ($linha['demanda_gestao_trelo_cartao']) $sql->adInserir('projeto_viabilidade_gestao_trelo_cartao', $linha['demanda_gestao_trelo_cartao']);
		elseif ($linha['demanda_gestao_pdcl']) $sql->adInserir('projeto_viabilidade_gestao_pdcl', $linha['demanda_gestao_pdcl']);
		elseif ($linha['demanda_gestao_pdcl_item']) $sql->adInserir('projeto_viabilidade_gestao_pdcl_item', $linha['demanda_gestao_pdcl_item']);
		
		if ($linha['demanda_gestao_ordem']) $sql->adInserir('projeto_viabilidade_gestao_ordem', $linha['demanda_gestao_ordem']);
		$sql->exec();
		$sql->limpar();
		}
	}


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













	

if ($projeto_viabilidade_tarefa) $tipo='tarefa';
elseif ($projeto_viabilidade_projeto) $tipo='projeto';
elseif ($projeto_viabilidade_perspectiva) $tipo='perspectiva';
elseif ($projeto_viabilidade_tema) $tipo='tema';
elseif ($projeto_viabilidade_objetivo) $tipo='objetivo';
elseif ($projeto_viabilidade_fator) $tipo='fator';
elseif ($projeto_viabilidade_estrategia) $tipo='estrategia';
elseif ($projeto_viabilidade_meta) $tipo='meta';
elseif ($projeto_viabilidade_pratica) $tipo='pratica';
elseif ($projeto_viabilidade_acao) $tipo='acao';
elseif ($projeto_viabilidade_canvas) $tipo='canvas';
elseif ($projeto_viabilidade_risco) $tipo='risco';
elseif ($projeto_viabilidade_risco_resposta) $tipo='risco_resposta';
elseif ($projeto_viabilidade_indicador) $tipo='projeto_viabilidade_indicador';
elseif ($projeto_viabilidade_calendario) $tipo='calendario';
elseif ($projeto_viabilidade_monitoramento) $tipo='monitoramento';
elseif ($projeto_viabilidade_ata) $tipo='ata';
elseif ($projeto_viabilidade_mswot) $tipo='mswot';
elseif ($projeto_viabilidade_swot) $tipo='swot';
elseif ($projeto_viabilidade_operativo) $tipo='operativo';
elseif ($projeto_viabilidade_instrumento) $tipo='instrumento';
elseif ($projeto_viabilidade_recurso) $tipo='recurso';
elseif ($projeto_viabilidade_problema) $tipo='problema';
elseif ($projeto_viabilidade_demanda) $tipo='demanda';
elseif ($projeto_viabilidade_programa) $tipo='programa';
elseif ($projeto_viabilidade_licao) $tipo='licao';
elseif ($projeto_viabilidade_evento) $tipo='evento';
elseif ($projeto_viabilidade_link) $tipo='link';
elseif ($projeto_viabilidade_avaliacao) $tipo='avaliacao';
elseif ($projeto_viabilidade_tgn) $tipo='tgn';
elseif ($projeto_viabilidade_brainstorm) $tipo='brainstorm';
elseif ($projeto_viabilidade_gut) $tipo='gut';
elseif ($projeto_viabilidade_causa_efeito) $tipo='causa_efeito';
elseif ($projeto_viabilidade_arquivo) $tipo='arquivo';
elseif ($projeto_viabilidade_forum) $tipo='forum';
elseif ($projeto_viabilidade_checklist) $tipo='checklist';
elseif ($projeto_viabilidade_agenda) $tipo='agenda';
elseif ($projeto_viabilidade_agrupamento) $tipo='agrupamento';
elseif ($projeto_viabilidade_patrocinador) $tipo='patrocinador';
elseif ($projeto_viabilidade_template) $tipo='template';
elseif ($projeto_viabilidade_painel) $tipo='painel';
elseif ($projeto_viabilidade_painel_odometro) $tipo='painel_odometro';
elseif ($projeto_viabilidade_painel_composicao) $tipo='painel_composicao';
elseif ($projeto_viabilidade_tr) $tipo='tr';
elseif ($projeto_viabilidade_me) $tipo='me';
elseif ($projeto_viabilidade_acao_item) $tipo='acao_item';
elseif ($projeto_viabilidade_beneficio) $tipo='beneficio';
elseif ($projeto_viabilidade_painel_slideshow) $tipo='painel_slideshow';
elseif ($projeto_viabilidade_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($projeto_viabilidade_projeto_abertura) $tipo='projeto_abertura';
elseif ($projeto_viabilidade_plano_gestao) $tipo='plano_gestao';
elseif ($projeto_viabilidade_ssti) $tipo='ssti';
elseif ($projeto_viabilidade_laudo) $tipo='laudo';
elseif ($projeto_viabilidade_trelo) $tipo='trelo';
elseif ($projeto_viabilidade_trelo_cartao) $tipo='trelo_cartao';
elseif ($projeto_viabilidade_pdcl) $tipo='pdcl';
elseif ($projeto_viabilidade_pdcl_item) $tipo='pdcl_item';

else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado','A qual parte do sistema a demanda está relacionada.').'Relacionada:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($projeto_viabilidade_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso a demanda seja específica de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_gestao_projeto" value="'.$projeto_viabilidade_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($projeto_viabilidade_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso a demanda seja específica de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_tarefa" value="'.$projeto_viabilidade_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($projeto_viabilidade_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso a demanda seja específica de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_perspectiva" value="'.$projeto_viabilidade_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($projeto_viabilidade_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso a demanda seja específica de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_tema" value="'.$projeto_viabilidade_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($projeto_viabilidade_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso a demanda seja específica de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_objetivo" value="'.$projeto_viabilidade_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($projeto_viabilidade_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso a demanda seja específica de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_fator" value="'.$projeto_viabilidade_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($projeto_viabilidade_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso a demanda seja específica de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_estrategia" value="'.$projeto_viabilidade_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($projeto_viabilidade_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso a demanda seja específica de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_meta" value="'.$projeto_viabilidade_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($projeto_viabilidade_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso a demanda seja específica de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_pratica" value="'.$projeto_viabilidade_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($projeto_viabilidade_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso a demanda seja específica de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_acao" value="'.$projeto_viabilidade_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($projeto_viabilidade_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso a demanda seja específica de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_canvas" value="'.$projeto_viabilidade_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($projeto_viabilidade_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso a demanda seja específica de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_risco" value="'.$projeto_viabilidade_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($projeto_viabilidade_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso a demanda seja específica de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_risco_resposta" value="'.$projeto_viabilidade_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($projeto_viabilidade_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso a demanda seja específica de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_indicador" value="'.$projeto_viabilidade_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($projeto_viabilidade_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso a demanda seja específica de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_calendario" value="'.$projeto_viabilidade_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($projeto_viabilidade_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso a demanda seja específica de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_monitoramento" value="'.$projeto_viabilidade_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($projeto_viabilidade_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reunião', 'Caso a demanda seja específica de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_ata" value="'.(isset($projeto_viabilidade_ata) ? $projeto_viabilidade_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($projeto_viabilidade_ata) ? $projeto_viabilidade_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('icones/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso a demanda seja específica de uma matriz SWOT neste campo deverá constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_mswot" value="'.(isset($projeto_viabilidade_mswot) ? $projeto_viabilidade_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($projeto_viabilidade_mswot) ? $projeto_viabilidade_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ícone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso a demanda seja específica de um campo de matriz SWOT neste campo deverá constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_swot" value="'.(isset($projeto_viabilidade_swot) ? $projeto_viabilidade_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($projeto_viabilidade_swot) ? $projeto_viabilidade_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso a demanda seja específica de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_operativo" value="'.$projeto_viabilidade_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($projeto_viabilidade_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso a demanda seja específica de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_instrumento" value="'.$projeto_viabilidade_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($projeto_viabilidade_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso a demanda seja específica de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_recurso" value="'.$projeto_viabilidade_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($projeto_viabilidade_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso a demanda seja específica de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_problema" value="'.$projeto_viabilidade_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($projeto_viabilidade_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso a demanda seja específica de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="gestao_viabilidade_demanda" value="'.$projeto_viabilidade_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($projeto_viabilidade_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso a demanda seja específica de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_programa" value="'.$projeto_viabilidade_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($projeto_viabilidade_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso a demanda seja específica de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo deverá constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_licao" value="'.$projeto_viabilidade_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($projeto_viabilidade_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso a demanda seja específica de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_evento" value="'.$projeto_viabilidade_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($projeto_viabilidade_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso a demanda seja específica de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_link" value="'.$projeto_viabilidade_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($projeto_viabilidade_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avaliação', 'Caso a demanda seja específica de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_avaliacao" value="'.$projeto_viabilidade_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($projeto_viabilidade_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso a demanda seja específica de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_tgn" value="'.$projeto_viabilidade_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($projeto_viabilidade_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso a demanda seja específica de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_brainstorm" value="'.$projeto_viabilidade_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($projeto_viabilidade_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso a demanda seja específica de uma matriz GUT, neste campo deverá constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_gut" value="'.$projeto_viabilidade_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($projeto_viabilidade_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso a demanda seja específica de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_causa_efeito" value="'.$projeto_viabilidade_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($projeto_viabilidade_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso a demanda seja específica de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_arquivo" value="'.$projeto_viabilidade_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($projeto_viabilidade_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('Fórum', 'Caso a demanda seja específica de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_forum" value="'.$projeto_viabilidade_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($projeto_viabilidade_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso a demanda seja específica de um checklist, neste campo deverá constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_checklist" value="'.$projeto_viabilidade_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($projeto_viabilidade_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso a demanda seja específica de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_agenda" value="'.$projeto_viabilidade_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($projeto_viabilidade_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso a demanda seja específica de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_agrupamento" value="'.$projeto_viabilidade_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($projeto_viabilidade_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica('Patrocinador', 'Caso a demanda seja específica de um patrocinador, neste campo deverá constar o nome do patrocinador.').'Patrocinador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_patrocinador" value="'.$projeto_viabilidade_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($projeto_viabilidade_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso a demanda seja específica de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_template" value="'.$projeto_viabilidade_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($projeto_viabilidade_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso a demanda seja específica de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_painel" value="'.$projeto_viabilidade_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($projeto_viabilidade_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Odômetro de Indicador', 'Caso a demanda seja específica de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_painel_odometro" value="'.$projeto_viabilidade_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($projeto_viabilidade_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composição de Painéis', 'Caso a demanda seja específica de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_painel_composicao" value="'.$projeto_viabilidade_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($projeto_viabilidade_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/composicao_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso a demanda seja específica de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_tr" value="'.$projeto_viabilidade_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($projeto_viabilidade_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso a demanda seja específica de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_me" value="'.$projeto_viabilidade_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($projeto_viabilidade_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso a demanda seja específica de um item de '.$config['acao'].', neste campo deverá constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_acao_item" value="'.$projeto_viabilidade_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($projeto_viabilidade_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso a demanda seja específica de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_beneficio" value="'.$projeto_viabilidade_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($projeto_viabilidade_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composições', 'Caso a demanda seja específica de um slideshow de composições, neste campo deverá constar o nome do slideshow de composições.').'Slideshow de composições:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_painel_slideshow" value="'.$projeto_viabilidade_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($projeto_viabilidade_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composições','Clique neste ícone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composições.').'</a></td></tr></table></td></tr>';

echo '<tr '.($projeto_viabilidade_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso a demanda seja específica de um estudo de viabilidade, neste campo deverá constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_projeto_viabilidade" value="'.$projeto_viabilidade_projeto_viabilidade.'" /><input type="text" id="semelhante_nome" name="semelhante_nome" value="'.nome_viabilidade($projeto_viabilidade_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ícone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';

echo '<tr '.($projeto_viabilidade_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso a demanda seja específica de um termo de abertura, neste campo deverá constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_projeto_abertura" value="'.$projeto_viabilidade_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($projeto_viabilidade_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ícone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estratégico', 'Caso a demanda seja específica de um planejamento estratégico, neste campo deverá constar o nome do planejamento estratégico.').'Planejamento estratégico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_plano_gestao" value="'.$projeto_viabilidade_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($projeto_viabilidade_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja específico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo deverá constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_ssti" value="'.$projeto_viabilidade_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($projeto_viabilidade_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ícone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja específico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo deverá constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_laudo" value="'.$projeto_viabilidade_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($projeto_viabilidade_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ícone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja específico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo deverá constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_trelo" value="'.$projeto_viabilidade_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($projeto_viabilidade_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ícone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja específico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo deverá constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_trelo_cartao" value="'.$projeto_viabilidade_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($projeto_viabilidade_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ícone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja específico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo deverá constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_pdcl" value="'.$projeto_viabilidade_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($projeto_viabilidade_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ícone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_viabilidade_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja específico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo deverá constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_viabilidade_pdcl_item" value="'.$projeto_viabilidade_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($projeto_viabilidade_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCLItem();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ícone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';




$sql->adTabela('projeto_viabilidade_gestao');
$sql->adCampo('projeto_viabilidade_gestao.*');
if ($projeto_viabilidade_id) $sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade ='.(int)$projeto_viabilidade_id);
else $sql->adOnde('projeto_viabilidade_gestao_uuid =\''.$uuid.'\'');
$sql->adOrdem('projeto_viabilidade_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_viabilidade_gestao_ordem'].', '.$gestao_data['projeto_viabilidade_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_viabilidade_gestao_ordem'].', '.$gestao_data['projeto_viabilidade_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_viabilidade_gestao_ordem'].', '.$gestao_data['projeto_viabilidade_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_viabilidade_gestao_ordem'].', '.$gestao_data['projeto_viabilidade_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['projeto_viabilidade_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_viabilidade_gestao_tarefa']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_viabilidade_gestao_projeto']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_viabilidade_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['projeto_viabilidade_gestao_tema']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_viabilidade_gestao_objetivo']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_viabilidade_gestao_fator']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_viabilidade_gestao_estrategia']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_viabilidade_gestao_meta']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_viabilidade_gestao_pratica']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_viabilidade_gestao_acao']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_viabilidade_gestao_canvas']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['projeto_viabilidade_gestao_risco']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_viabilidade_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_viabilidade_gestao_indicador']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_viabilidade_gestao_calendario']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_viabilidade_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_viabilidade_gestao_ata']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_viabilidade_gestao_mswot']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['projeto_viabilidade_gestao_swot']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_viabilidade_gestao_operativo']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_viabilidade_gestao_instrumento']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_viabilidade_gestao_recurso']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['projeto_viabilidade_gestao_problema']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_viabilidade_gestao_demanda']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['projeto_viabilidade_gestao_programa']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_viabilidade_gestao_licao']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_viabilidade_gestao_evento']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['projeto_viabilidade_gestao_link']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_viabilidade_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_viabilidade_gestao_tgn']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_viabilidade_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_viabilidade_gestao_gut']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_viabilidade_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_viabilidade_gestao_arquivo']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_viabilidade_gestao_forum']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_viabilidade_gestao_checklist']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_viabilidade_gestao_agenda']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_viabilidade_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_viabilidade_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['projeto_viabilidade_gestao_template']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['projeto_viabilidade_gestao_painel']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_viabilidade_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_viabilidade_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['projeto_viabilidade_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['projeto_viabilidade_gestao_tr']).'</td>';	
	elseif ($gestao_data['projeto_viabilidade_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['projeto_viabilidade_gestao_me']).'</td>';	
	elseif ($gestao_data['projeto_viabilidade_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_viabilidade_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['projeto_viabilidade_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_viabilidade_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['projeto_viabilidade_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_viabilidade_gestao_painel_slideshow']).'</td>';	
	
	elseif ($gestao_data['projeto_viabilidade_gestao_semelhante']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_viabilidade_gestao_semelhante']).'</td>';	
	
	elseif ($gestao_data['projeto_viabilidade_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_viabilidade_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['projeto_viabilidade_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_viabilidade_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['projeto_viabilidade_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_viabilidade_gestao_ssti']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_viabilidade_gestao_laudo']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_viabilidade_gestao_trelo']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_viabilidade_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_viabilidade_gestao_pdcl']).'</td>';
	elseif ($gestao_data['projeto_viabilidade_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_viabilidade_gestao_pdcl_item']).'</td>';
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['projeto_viabilidade_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';





echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nível de Acesso', 'O estudo de viabilidade pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o estudo de viabilidade.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designados para o estudo de viabilidade podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável e os designados para o estudo de viabilidade ver e editar o estudo de viabilidade</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável e os designados para o estudo de viabilidade podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($projeto_viabilidade_acesso, 'projeto_viabilidade_acesso', 'class="texto"', ($projeto_viabilidade_id ? $obj->projeto_viabilidade_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milhões possíveis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inserção do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="projeto_viabilidade_cor" value="'.($obj->projeto_viabilidade_cor ? $obj->projeto_viabilidade_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ativo', 'Caso o estudo de viabilidade ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="projeto_viabilidade_ativo" '.($obj->projeto_viabilidade_ativo || !$projeto_viabilidade_id ? 'checked="checked"' : '').' /></td></tr>';

$campos_customizados = new CampoCustomizados('viabilidade', $projeto_viabilidade_id, 'editar');
$campos_customizados->imprimirHTML();

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/viabilidade_editar_pro.php';

echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_notificar\').style.display) document.getElementById(\'apresentar_notificar\').style.display=\'\'; else document.getElementById(\'apresentar_notificar\').style.display=\'none\';"><a class="aba" href="javascript: void(0);"><b>Notificar</b></a></td></tr>';
echo '<tr id="apresentar_notificar" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($projeto_viabilidade_id > 0 ? 'modificação' : 'criação').' do estudo de viabilidade.').'Notificar:'.dicaF().'</td>';
echo '<td>';
echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pelo Estudo de Viabilidade', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por este estudo de viabilidade.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para o Estudo de Viabilidade', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para este estudo de viabilidade.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro do estudo de viabilidade.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';
echo '</td></table></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($projeto_viabilidade_id ? 'edição' : 'criação').' do estudo de viabilidade.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '</table>';
echo '</form>';
echo estiloFundoCaixa();




?>
<script type="text/javascript">
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&cias_id_selecionadas='+document.getElementById('projeto_viabilidade_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.projeto_viabilidade_cias.value = organizacao_id_string;
	document.getElementById('projeto_viabilidade_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('projeto_viabilidade_cias').value);
	__buildTooltip();
	}	
	
	
var contatos_id_selecionados = '<?php echo implode(",", $contatos_selecionados)?>';

function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, '<?php echo ucfirst($config["contatos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.projeto_viabilidade_interessados.value = contato_id_string;
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados);
	__buildTooltip();
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.projeto_viabilidade_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.projeto_viabilidade_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

var patrocinadores_id_selecionados = '<?php echo implode(",", $patrocinadores_selecionados)?>';

function popPatrocinadores() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setPatrocinadores&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&patrocinadores_id_selecionados='+patrocinadores_id_selecionados, window.setPatrocinadores, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setPatrocinadores&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&patrocinadores_id_selecionados='+patrocinadores_id_selecionados, 'Patrocinador','height=500,width=500,resizable,scrollbars=yes');
	}

function setPatrocinadores(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.projeto_viabilidade_patrocinadores.value = contato_id_string;
	patrocinadores_id_selecionados = contato_id_string;
	xajax_exibir_patrocinadores(patrocinadores_id_selecionados);
	__buildTooltip();
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('projeto_viabilidade_dept').value+'&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('projeto_viabilidade_dept').value+'&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('projeto_viabilidade_cia').value=cia_id;
	document.getElementById('projeto_viabilidade_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function mudar_segmento(){
	document.getElementById('projeto_viabilidade_intervencao').length=0;
	document.getElementById('projeto_viabilidade_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('projeto_viabilidade_setor').value, 'Segmento', 'projeto_viabilidade_segmento','combo_segmento', 'style="width:284px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	document.getElementById('projeto_viabilidade_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('projeto_viabilidade_segmento').value, 'Intervencao', 'projeto_viabilidade_intervencao','combo_intervencao', 'style="width:284px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('projeto_viabilidade_intervencao').value, 'TipoIntervencao', 'projeto_viabilidade_tipo_intervencao','combo_tipo_intervencao', 'style="width:284px;" class="texto" size=1');
	}


function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('viabilidades_usuarios');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&usuario_id='+document.getElementById('projeto_viabilidade_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('projeto_viabilidade_cia').value+'&usuario_id='+document.getElementById('projeto_viabilidade_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('projeto_viabilidade_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	var cia_id=document.getElementById('projeto_viabilidade_cia').value;
	xajax_selecionar_om_ajax(cia_id,'projeto_viabilidade_cia','combo_cia', 'class="texto" size=1 style="width:280px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este estudo de viabilidade?")) {
		var f = document.env;
		f.excluir.value=1;
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.projeto_viabilidade_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.projeto_viabilidade_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.projeto_viabilidade_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.projeto_viabilidade_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
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
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.projeto_viabilidade_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.projeto_viabilidade_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.projeto_viabilidade_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_gestao_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.projeto_viabilidade_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Camçpo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	
	document.env.gestao_viabilidade_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Matriz GUT','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Slideshow de Composições','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_projeto_viabilidade.value = chave;
	document.env.semelhante_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Termo de Abertura','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, 'Planejamento Estratégico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

		
	
function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function popPDCLItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('projeto_viabilidade_cia').value, window.setPDCLItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCLItem(chave, valor){
	limpar_tudo();
	document.env.projeto_viabilidade_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.projeto_viabilidade_gestao_projeto.value = null;
	document.env.projeto_viabilidade_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.projeto_viabilidade_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.projeto_viabilidade_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.projeto_viabilidade_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.projeto_viabilidade_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.projeto_viabilidade_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.projeto_viabilidade_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.projeto_viabilidade_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.projeto_viabilidade_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.projeto_viabilidade_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.projeto_viabilidade_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.projeto_viabilidade_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.projeto_viabilidade_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.projeto_viabilidade_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.projeto_viabilidade_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.projeto_viabilidade_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.projeto_viabilidade_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.projeto_viabilidade_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.projeto_viabilidade_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.projeto_viabilidade_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.projeto_viabilidade_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.projeto_viabilidade_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.gestao_viabilidade_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.projeto_viabilidade_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.projeto_viabilidade_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.projeto_viabilidade_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.projeto_viabilidade_link.value = null;
	document.env.link_nome.value = '';
	document.env.projeto_viabilidade_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.projeto_viabilidade_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.projeto_viabilidade_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.projeto_viabilidade_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.projeto_viabilidade_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.projeto_viabilidade_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.projeto_viabilidade_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.projeto_viabilidade_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.projeto_viabilidade_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.projeto_viabilidade_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.projeto_viabilidade_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.projeto_viabilidade_template.value = null;
	document.env.template_nome.value = '';
	document.env.projeto_viabilidade_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.projeto_viabilidade_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.projeto_viabilidade_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.projeto_viabilidade_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.projeto_viabilidade_me.value = null;
	document.env.me_nome.value = '';
	document.env.projeto_viabilidade_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.projeto_viabilidade_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.projeto_viabilidade_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	
	document.env.projeto_viabilidade_projeto_viabilidade.value = null;
	document.env.semelhante_nome.value = '';
	
	document.env.projeto_viabilidade_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.projeto_viabilidade_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	
	document.env.projeto_viabilidade_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.projeto_viabilidade_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.projeto_viabilidade_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.projeto_viabilidade_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.projeto_viabilidade_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.projeto_viabilidade_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';			
	}

function incluir_relacionado(){
	var f=document.env;
	
	xajax_incluir_relacionado(
	document.getElementById('projeto_viabilidade_id').value,
	document.getElementById('uuid').value,
	f.projeto_viabilidade_gestao_projeto.value,
	f.projeto_viabilidade_tarefa.value,
	f.projeto_viabilidade_perspectiva.value,
	f.projeto_viabilidade_tema.value,
	f.projeto_viabilidade_objetivo.value,
	f.projeto_viabilidade_fator.value,
	f.projeto_viabilidade_estrategia.value,
	f.projeto_viabilidade_meta.value,
	f.projeto_viabilidade_pratica.value,
	f.projeto_viabilidade_acao.value,
	f.projeto_viabilidade_canvas.value,
	f.projeto_viabilidade_risco.value,
	f.projeto_viabilidade_risco_resposta.value,
	f.projeto_viabilidade_indicador.value,
	f.projeto_viabilidade_calendario.value,
	f.projeto_viabilidade_monitoramento.value,
	f.projeto_viabilidade_ata.value,
	f.projeto_viabilidade_mswot.value,
	f.projeto_viabilidade_swot.value,
	f.projeto_viabilidade_operativo.value,
	f.projeto_viabilidade_instrumento.value,
	f.projeto_viabilidade_recurso.value,
	f.projeto_viabilidade_problema.value,
	f.gestao_viabilidade_demanda.value,
	f.projeto_viabilidade_programa.value,
	f.projeto_viabilidade_licao.value,
	f.projeto_viabilidade_evento.value,
	f.projeto_viabilidade_link.value,
	f.projeto_viabilidade_avaliacao.value,
	f.projeto_viabilidade_tgn.value,
	f.projeto_viabilidade_brainstorm.value,
	f.projeto_viabilidade_gut.value,
	f.projeto_viabilidade_causa_efeito.value,
	f.projeto_viabilidade_arquivo.value,
	f.projeto_viabilidade_forum.value,
	f.projeto_viabilidade_checklist.value,
	f.projeto_viabilidade_agenda.value,
	f.projeto_viabilidade_agrupamento.value,
	f.projeto_viabilidade_patrocinador.value,
	f.projeto_viabilidade_template.value,
	f.projeto_viabilidade_painel.value,
	f.projeto_viabilidade_painel_odometro.value,
	f.projeto_viabilidade_painel_composicao.value,
	f.projeto_viabilidade_tr.value,
	f.projeto_viabilidade_me.value,
	f.projeto_viabilidade_acao_item.value,
	f.projeto_viabilidade_beneficio.value,
	f.projeto_viabilidade_painel_slideshow.value,
	f.projeto_viabilidade_projeto_viabilidade.value,
	f.projeto_viabilidade_projeto_abertura.value,
	f.projeto_viabilidade_plano_gestao.value,
	f.projeto_viabilidade_ssti.value,
	f.projeto_viabilidade_laudo.value,
	f.projeto_viabilidade_trelo.value,
	f.projeto_viabilidade_trelo_cartao.value,
	f.projeto_viabilidade_pdcl.value,
	f.projeto_viabilidade_pdcl_item.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(projeto_viabilidade_gestao_id){
	xajax_excluir_gestao(document.getElementById('projeto_viabilidade_id').value, document.getElementById('uuid').value, projeto_viabilidade_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, projeto_viabilidade_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, projeto_viabilidade_gestao_id, direcao, document.getElementById('projeto_viabilidade_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$projeto_viabilidade_id && (
	$projeto_viabilidade_tarefa || 
	$projeto_viabilidade_projeto || 
	$projeto_viabilidade_perspectiva || 
	$projeto_viabilidade_tema || 
	$projeto_viabilidade_objetivo || 
	$projeto_viabilidade_fator || 
	$projeto_viabilidade_estrategia || 
	$projeto_viabilidade_meta || 
	$projeto_viabilidade_pratica || 
	$projeto_viabilidade_acao || 
	$projeto_viabilidade_canvas || 
	$projeto_viabilidade_risco || 
	$projeto_viabilidade_risco_resposta || 
	$projeto_viabilidade_indicador || 
	$projeto_viabilidade_calendario || 
	$projeto_viabilidade_monitoramento || 
	$projeto_viabilidade_ata || 
	$projeto_viabilidade_mswot || 
	$projeto_viabilidade_swot || 
	$projeto_viabilidade_operativo || 
	$projeto_viabilidade_instrumento || 
	$projeto_viabilidade_recurso || 
	$projeto_viabilidade_problema || 
	$projeto_viabilidade_demanda || 
	$projeto_viabilidade_programa || 
	$projeto_viabilidade_licao || 
	$projeto_viabilidade_evento || 
	$projeto_viabilidade_link || 
	$projeto_viabilidade_avaliacao || 
	$projeto_viabilidade_tgn || 
	$projeto_viabilidade_brainstorm || 
	$projeto_viabilidade_gut || 
	$projeto_viabilidade_causa_efeito || 
	$projeto_viabilidade_arquivo || 
	$projeto_viabilidade_forum || 
	$projeto_viabilidade_checklist || 
	$projeto_viabilidade_agenda || 
	$projeto_viabilidade_agrupamento || 
	$projeto_viabilidade_patrocinador || 
	$projeto_viabilidade_template || 
	$projeto_viabilidade_painel || 
	$projeto_viabilidade_painel_odometro || 
	$projeto_viabilidade_painel_composicao || 
	$projeto_viabilidade_tr || 
	$projeto_viabilidade_me || 
	$projeto_viabilidade_acao_item || 
	$projeto_viabilidade_beneficio || 
	$projeto_viabilidade_painel_slideshow || 
	$projeto_viabilidade_projeto_viabilidade || 
	$projeto_viabilidade_projeto_abertura || 
	$projeto_viabilidade_plano_gestao|| 
	$projeto_viabilidade_ssti || 
	$projeto_viabilidade_laudo || 
	$projeto_viabilidade_trelo || 
	$projeto_viabilidade_trelo_cartao || 
	$projeto_viabilidade_pdcl || 
	$projeto_viabilidade_pdcl_item
	)) echo 'incluir_relacionado();';
	?>	

</script>

