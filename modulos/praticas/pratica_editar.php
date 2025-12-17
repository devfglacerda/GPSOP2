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
require_once ($Aplic->getClasseModulo('praticas'));

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();
$pratica_id=getParam($_REQUEST, 'pratica_id', null);


$pratica_projeto=getParam($_REQUEST, 'pratica_projeto', null);
$pratica_tarefa=getParam($_REQUEST, 'pratica_tarefa', null);
$pratica_perspectiva=getParam($_REQUEST, 'pratica_perspectiva', null);
$pratica_tema=getParam($_REQUEST, 'pratica_tema', null);
$pratica_objetivo=getParam($_REQUEST, 'pratica_objetivo', null);
$pratica_fator=getParam($_REQUEST, 'pratica_fator', null);
$pratica_estrategia=getParam($_REQUEST, 'pratica_estrategia', null);
$pratica_meta=getParam($_REQUEST, 'pratica_meta', null);
$pratica_pratica=getParam($_REQUEST, 'pratica_pratica', null);
$pratica_acao=getParam($_REQUEST, 'pratica_acao', null);
$pratica_canvas=getParam($_REQUEST, 'pratica_canvas', null);
$pratica_risco=getParam($_REQUEST, 'pratica_risco', null);
$pratica_risco_resposta=getParam($_REQUEST, 'pratica_risco_resposta', null);
$pratica_indicador=getParam($_REQUEST, 'pratica_indicador', null);
$pratica_calendario=getParam($_REQUEST, 'pratica_calendario', null);
$pratica_monitoramento=getParam($_REQUEST, 'pratica_monitoramento', null);
$pratica_ata=getParam($_REQUEST, 'pratica_ata', null);
$pratica_mswot=getParam($_REQUEST, 'pratica_mswot', null);
$pratica_swot=getParam($_REQUEST, 'pratica_swot', null);
$pratica_operativo=getParam($_REQUEST, 'pratica_operativo', null);
$pratica_instrumento=getParam($_REQUEST, 'pratica_instrumento', null);
$pratica_recurso=getParam($_REQUEST, 'pratica_recurso', null);
$pratica_problema=getParam($_REQUEST, 'pratica_problema', null);
$pratica_demanda=getParam($_REQUEST, 'pratica_demanda', null);
$pratica_programa=getParam($_REQUEST, 'pratica_programa', null);
$pratica_licao=getParam($_REQUEST, 'pratica_licao', null);
$pratica_evento=getParam($_REQUEST, 'pratica_evento', null);
$pratica_link=getParam($_REQUEST, 'pratica_link', null);
$pratica_avaliacao=getParam($_REQUEST, 'pratica_avaliacao', null);
$pratica_tgn=getParam($_REQUEST, 'pratica_tgn', null);
$pratica_brainstorm=getParam($_REQUEST, 'pratica_brainstorm', null);
$pratica_gut=getParam($_REQUEST, 'pratica_gut', null);
$pratica_causa_efeito=getParam($_REQUEST, 'pratica_causa_efeito', null);
$pratica_arquivo=getParam($_REQUEST, 'pratica_arquivo', null);
$pratica_forum=getParam($_REQUEST, 'pratica_forum', null);
$pratica_checklist=getParam($_REQUEST, 'pratica_checklist', null);
$pratica_agenda=getParam($_REQUEST, 'pratica_agenda', null);
$pratica_agrupamento=getParam($_REQUEST, 'pratica_agrupamento', null);
$pratica_patrocinador=getParam($_REQUEST, 'pratica_patrocinador', null);
$pratica_template=getParam($_REQUEST, 'pratica_template', null);
$pratica_painel=getParam($_REQUEST, 'pratica_painel', null);
$pratica_painel_odometro=getParam($_REQUEST, 'pratica_painel_odometro', null);
$pratica_painel_composicao=getParam($_REQUEST, 'pratica_painel_composicao', null);
$pratica_tr=getParam($_REQUEST, 'pratica_tr', null);
$pratica_me=getParam($_REQUEST, 'pratica_me', null);
$pratica_acao_item=getParam($_REQUEST, 'pratica_acao_item', null);
$pratica_beneficio=getParam($_REQUEST, 'pratica_beneficio', null);
$pratica_painel_slideshow=getParam($_REQUEST, 'pratica_painel_slideshow', null);
$pratica_projeto_viabilidade=getParam($_REQUEST, 'pratica_projeto_viabilidade', null);
$pratica_projeto_abertura=getParam($_REQUEST, 'pratica_projeto_abertura', null);
$pratica_plano_gestao=getParam($_REQUEST, 'pratica_plano_gestao', null);
$pratica_ssti=getParam($_REQUEST, 'pratica_ssti', null);
$pratica_laudo=getParam($_REQUEST, 'pratica_laudo', null);
$pratica_trelo=getParam($_REQUEST, 'pratica_trelo', null);
$pratica_trelo_cartao=getParam($_REQUEST, 'pratica_trelo_cartao', null);
$pratica_pdcl=getParam($_REQUEST, 'pratica_pdcl', null);
$pratica_pdcl_item=getParam($_REQUEST, 'pratica_pdcl_item', null);


$obj= new CPratica();
if ($pratica_id){
	$obj->load($pratica_id);
	$cia_id=$obj->pratica_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

	if (
		$pratica_projeto || 
		$pratica_tarefa || 
		$pratica_perspectiva || 
		$pratica_tema || 
		$pratica_objetivo || 
		$pratica_fator || 
		$pratica_estrategia || 
		$pratica_meta || 
		$pratica_pratica || 
		$pratica_acao || 
		$pratica_canvas || 
		$pratica_risco || 
		$pratica_risco_resposta || 
		$pratica_indicador || 
		$pratica_calendario || 
		$pratica_monitoramento || 
		$pratica_ata || 
		$pratica_mswot || 
		$pratica_swot || 
		$pratica_operativo || 
		$pratica_instrumento || 
		$pratica_recurso || 
		$pratica_problema || 
		$pratica_demanda || 
		$pratica_programa || 
		$pratica_licao || 
		$pratica_evento || 
		$pratica_link || 
		$pratica_avaliacao || 
		$pratica_tgn || 
		$pratica_brainstorm || 
		$pratica_gut || 
		$pratica_causa_efeito || 
		$pratica_arquivo || 
		$pratica_forum || 
		$pratica_checklist || 
		$pratica_agenda || 
		$pratica_agrupamento || 
		$pratica_patrocinador || 
		$pratica_template || 
		$pratica_painel || 
		$pratica_painel_odometro || 
		$pratica_painel_composicao || 
		$pratica_tr || 
		$pratica_me || 
		$pratica_acao_item || 
		$pratica_beneficio || 
		$pratica_painel_slideshow || 
		$pratica_projeto_viabilidade || 
		$pratica_projeto_abertura || 
		$pratica_plano_gestao|| 
		$pratica_ssti || 
		$pratica_laudo || 
		$pratica_trelo || 
		$pratica_trelo_cartao || 
		$pratica_pdcl || 
		$pratica_pdcl_item
		){
		$sql->adTabela('cias');
		if ($pratica_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
		elseif ($pratica_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
		elseif ($pratica_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		elseif ($pratica_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		elseif ($pratica_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
		elseif ($pratica_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
		elseif ($pratica_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		elseif ($pratica_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
		elseif ($pratica_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		elseif ($pratica_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		elseif ($pratica_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
		elseif ($pratica_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
		elseif ($pratica_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
		elseif ($pratica_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		elseif ($pratica_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
		elseif ($pratica_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
		elseif ($pratica_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
		elseif ($pratica_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
		elseif ($pratica_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
		elseif ($pratica_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
		elseif ($pratica_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
		elseif ($pratica_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
		elseif ($pratica_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
		elseif ($pratica_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
		elseif ($pratica_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
		elseif ($pratica_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
		elseif ($pratica_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
		elseif ($pratica_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
		elseif ($pratica_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
		elseif ($pratica_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
		elseif ($pratica_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
		elseif ($pratica_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
		elseif ($pratica_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
		elseif ($pratica_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
		elseif ($pratica_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
		elseif ($pratica_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
		elseif ($pratica_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
		elseif ($pratica_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
		elseif ($pratica_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
		elseif ($pratica_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
		elseif ($pratica_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
		elseif ($pratica_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
		elseif ($pratica_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
		elseif ($pratica_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
		elseif ($pratica_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
		elseif ($pratica_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
		elseif ($pratica_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
		elseif ($pratica_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
		elseif ($pratica_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
		elseif ($pratica_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
		elseif ($pratica_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
		elseif ($pratica_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
		elseif ($pratica_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
		elseif ($pratica_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
		elseif ($pratica_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
		elseif ($pratica_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
		elseif ($pratica_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
	
		if ($pratica_tarefa) $sql->adOnde('tarefa_id = '.(int)$pratica_tarefa);
		elseif ($pratica_projeto) $sql->adOnde('projeto_id = '.(int)$pratica_projeto);
		elseif ($pratica_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$pratica_perspectiva);
		elseif ($pratica_tema) $sql->adOnde('tema_id = '.(int)$pratica_tema);
		elseif ($pratica_objetivo) $sql->adOnde('objetivo_id = '.(int)$pratica_objetivo);
		elseif ($pratica_fator) $sql->adOnde('fator_id = '.(int)$pratica_fator);
		elseif ($pratica_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$pratica_estrategia);
		elseif ($pratica_meta) $sql->adOnde('pg_meta_id = '.(int)$pratica_meta);
		elseif ($pratica_pratica) $sql->adOnde('pratica_id = '.(int)$pratica_pratica);
		elseif ($pratica_acao) $sql->adOnde('plano_acao_id = '.(int)$pratica_acao);
		elseif ($pratica_canvas) $sql->adOnde('canvas_id = '.(int)$pratica_canvas);
		elseif ($pratica_risco) $sql->adOnde('risco_id = '.(int)$pratica_risco);
		elseif ($pratica_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$pratica_risco_resposta);
		elseif ($pratica_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador);
		elseif ($pratica_calendario) $sql->adOnde('calendario_id = '.(int)$pratica_calendario);
		elseif ($pratica_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$pratica_monitoramento);
		elseif ($pratica_ata) $sql->adOnde('ata_id = '.(int)$pratica_ata);
		elseif ($pratica_mswot) $sql->adOnde('mswot_id = '.(int)$pratica_mswot);
		elseif ($pratica_swot) $sql->adOnde('swot_id = '.(int)$pratica_swot);
		elseif ($pratica_operativo) $sql->adOnde('operativo_id = '.(int)$pratica_operativo);
		elseif ($pratica_instrumento) $sql->adOnde('instrumento_id = '.(int)$pratica_instrumento);
		elseif ($pratica_recurso) $sql->adOnde('recurso_id = '.(int)$pratica_recurso);
		elseif ($pratica_problema) $sql->adOnde('problema_id = '.(int)$pratica_problema);
		elseif ($pratica_demanda) $sql->adOnde('demanda_id = '.(int)$pratica_demanda);
		elseif ($pratica_programa) $sql->adOnde('programa_id = '.(int)$pratica_programa);
		elseif ($pratica_licao) $sql->adOnde('licao_id = '.(int)$pratica_licao);
		elseif ($pratica_evento) $sql->adOnde('evento_id = '.(int)$pratica_evento);
		elseif ($pratica_link) $sql->adOnde('link_id = '.(int)$pratica_link);
		elseif ($pratica_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$pratica_avaliacao);
		elseif ($pratica_tgn) $sql->adOnde('tgn_id = '.(int)$pratica_tgn);
		elseif ($pratica_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$pratica_brainstorm);
		elseif ($pratica_gut) $sql->adOnde('gut_id = '.(int)$pratica_gut);
		elseif ($pratica_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$pratica_causa_efeito);
		elseif ($pratica_arquivo) $sql->adOnde('arquivo_id = '.(int)$pratica_arquivo);
		elseif ($pratica_forum) $sql->adOnde('forum_id = '.(int)$pratica_forum);
		elseif ($pratica_checklist) $sql->adOnde('checklist_id = '.(int)$pratica_checklist);
		elseif ($pratica_agenda) $sql->adOnde('agenda_id = '.(int)$pratica_agenda);
		elseif ($pratica_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$pratica_agrupamento);
		elseif ($pratica_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$pratica_patrocinador);
		elseif ($pratica_template) $sql->adOnde('template_id = '.(int)$pratica_template);
		elseif ($pratica_painel) $sql->adOnde('painel_id = '.(int)$pratica_painel);
		elseif ($pratica_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$pratica_painel_odometro);
		elseif ($pratica_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$pratica_painel_composicao);
		elseif ($pratica_tr) $sql->adOnde('tr_id = '.(int)$pratica_tr);
		elseif ($pratica_me) $sql->adOnde('me_id = '.(int)$pratica_me);
		elseif ($pratica_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$pratica_acao_item);
		elseif ($pratica_beneficio) $sql->adOnde('beneficio_id = '.(int)$pratica_beneficio);
		elseif ($pratica_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$pratica_painel_slideshow);
		elseif ($pratica_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$pratica_projeto_viabilidade);
		elseif ($pratica_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$pratica_projeto_abertura);
		elseif ($pratica_plano_gestao) $sql->adOnde('pg_id = '.(int)$pratica_plano_gestao);
		elseif ($pratica_ssti) $sql->adOnde('ssti_id = '.(int)$pratica_ssti);
		elseif ($pratica_laudo) $sql->adOnde('laudo_id = '.(int)$pratica_laudo);
		elseif ($pratica_trelo) $sql->adOnde('trelo_id = '.(int)$pratica_trelo);
		elseif ($pratica_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$pratica_trelo_cartao);
		elseif ($pratica_pdcl) $sql->adOnde('pdcl_id = '.(int)$pratica_pdcl);
		elseif ($pratica_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$pratica_pdcl_item);
		
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}

if($pratica_id && !(permiteEditarPratica($obj->pratica_acesso,$pratica_id) && $Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'pratica'))) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$pratica_id && !$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'pratica'))$Aplic->redirecionar('m=publico&a=acesso_negado');

$salvar=getParam($_REQUEST, 'salvar', 0);

$sql = new BDConsulta;
$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();



//lista de anos existentes
$sql->adTabela('pratica_requisito');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$qnt_anos=count($ultimo_ano);
$ultimo_ano=array_pop($ultimo_ano);

for ($i=((int)date('Y'))-15; $i<=(int)date('Y')+5; $i++) $anos[$i]=$i;
asort($anos);

if (isset($_REQUEST['IdxPraticaAno'])) $Aplic->setEstado('IdxPraticaAno', getParam($_REQUEST, 'IdxPraticaAno', null));
$ano = ($Aplic->getEstado('IdxPraticaAno') !== null && isset($anos[$Aplic->getEstado('IdxPraticaAno')]) ? $Aplic->getEstado('IdxPraticaAno') : ($ultimo_ano ? $ultimo_ano : date('Y')));

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$pratica_acesso = getSisValor('NivelAcesso','','','sisvalor_id');



$sql->adTabela('pratica_requisito');
$sql->adCampo('pratica_requisito.*');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$sql->adOnde('ano = '.(int)$ano);
$requisito = $sql->linha();
$sql->limpar();

$usuarios_selecionados =array();
$depts_selecionados = array();
$cias_selecionadas = array();
if ($pratica_id) {
	$sql->adTabela('pratica_usuarios', 'pratica_usuarios');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('usuarios.usuario_id');
	$sql->adOnde('pratica_id = '.(int)$pratica_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('pratica_depts', 'pd');
	$sql->adTabela('depts', 'deps');
	$sql->adCampo('deps.dept_id');
	$sql->adOnde('pratica_id ='.(int)$pratica_id);
	$sql->adOnde('pd.dept_id = deps.dept_id');
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('pratica_cia');
		$sql->adCampo('pratica_cia_cia');
		$sql->adOnde('pratica_cia_pratica = '.(int)$pratica_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="pratica_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
echo '<input name="pratica_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pratica_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="pratica_cias"  id="pratica_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="del" value="" />';
$uuid=($pratica_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';


$botoesTitulo = new CBlocoTitulo(($pratica_id ? 'Editar '.ucfirst($config['pratica']) : 'Criar '.ucfirst($config['pratica'])), 'pratica.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaCelula(dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados d'.$config['genero_pratica'].' '.$config['pratica'].' inseridos no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxPraticaAno', 'onchange="mudar_ano();" class="texto"', $ano));
if ($Aplic->profissional && $qnt_anos) $botoesTitulo->adicionaCelula('<a href="javascript: void(0)" onclick="popImportar();">'.imagem('icones/importar_p.png', 'Importar' , 'Clique neste ícone '.imagem('importar_p.png').' para importar os dados d'.$config['genero_pratica'].' '.$config['pratica'].' registrados noutro ano.').'</a>'.dicaF());
$botoesTitulo->mostrar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'pratica\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right" width="150">'.dica('Nome d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Tod'.$config['genero_pratica'].' '.$config['pratica'].' necessita ter um nome para identificação.').'Nome:'.dicaF().'</td><td><input type="text" name="pratica_nome" value="'.$obj->pratica_nome.'" style="width:400px;" class="texto" />*</td></tr>';
echo '<tr><td align="right" width="150">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'pratica_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}

if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="pratica_dept" id="pratica_dept" value="'.($pratica_id ? $obj->pratica_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($pratica_id ? $obj->pratica_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:400px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';
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
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap" width="125">'.dica('Responsável pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Tod'.$config['genero_pratica'].' '.$config['pratica'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="pratica_responsavel" name="pratica_responsavel" value="'.($obj->pratica_responsavel ? $obj->pratica_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->pratica_responsavel ? $obj->pratica_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';





if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_pratica = '.(int)$pratica_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
else{
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_pratica = '.(int)$pratica_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}

if (count($indicadores)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_pratica'].' '.$config['pratica'].' mais representativo da situação geral d'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.').'Indicador principal:'.dicaF().'</td><td colspan="2">'.selecionaVetor($indicadores, 'pratica_principal_indicador', 'class="texto" style="width:267px;"', $obj->pratica_principal_indicador).'</td></tr>';


if ($exibir['pratica_descricao']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('Descrição', 'Descrição sobre o que se trata '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'Descrição:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_descricao" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_descricao']) ? $requisito['pratica_descricao'] : '').'</textarea></td></tr>';


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


if ($pratica_tarefa) $tipo='tarefa';
elseif ($pratica_projeto) $tipo='projeto';
elseif ($pratica_perspectiva) $tipo='perspectiva';
elseif ($pratica_tema) $tipo='tema';
elseif ($pratica_objetivo) $tipo='objetivo';
elseif ($pratica_fator) $tipo='fator';
elseif ($pratica_estrategia) $tipo='estrategia';
elseif ($pratica_meta) $tipo='meta';
elseif ($pratica_pratica) $tipo='pratica';
elseif ($pratica_acao) $tipo='acao';
elseif ($pratica_canvas) $tipo='canvas';
elseif ($pratica_risco) $tipo='risco';
elseif ($pratica_risco_resposta) $tipo='risco_resposta';
elseif ($pratica_indicador) $tipo='pratica_indicador';
elseif ($pratica_calendario) $tipo='calendario';
elseif ($pratica_monitoramento) $tipo='monitoramento';
elseif ($pratica_ata) $tipo='ata';
elseif ($pratica_mswot) $tipo='mswot';
elseif ($pratica_swot) $tipo='swot';
elseif ($pratica_operativo) $tipo='operativo';
elseif ($pratica_instrumento) $tipo='instrumento';
elseif ($pratica_recurso) $tipo='recurso';
elseif ($pratica_problema) $tipo='problema';
elseif ($pratica_demanda) $tipo='demanda';
elseif ($pratica_programa) $tipo='programa';
elseif ($pratica_licao) $tipo='licao';
elseif ($pratica_evento) $tipo='evento';
elseif ($pratica_link) $tipo='link';
elseif ($pratica_avaliacao) $tipo='avaliacao';
elseif ($pratica_tgn) $tipo='tgn';
elseif ($pratica_brainstorm) $tipo='brainstorm';
elseif ($pratica_gut) $tipo='gut';
elseif ($pratica_causa_efeito) $tipo='causa_efeito';
elseif ($pratica_arquivo) $tipo='arquivo';
elseif ($pratica_forum) $tipo='forum';
elseif ($pratica_checklist) $tipo='checklist';
elseif ($pratica_agenda) $tipo='agenda';
elseif ($pratica_agrupamento) $tipo='agrupamento';
elseif ($pratica_patrocinador) $tipo='patrocinador';
elseif ($pratica_template) $tipo='template';
elseif ($pratica_painel) $tipo='painel';
elseif ($pratica_painel_odometro) $tipo='painel_odometro';
elseif ($pratica_painel_composicao) $tipo='painel_composicao';
elseif ($pratica_tr) $tipo='tr';
elseif ($pratica_me) $tipo='me';
elseif ($pratica_acao_item) $tipo='acao_item';
elseif ($pratica_beneficio) $tipo='beneficio';
elseif ($pratica_painel_slideshow) $tipo='painel_slideshow';
elseif ($pratica_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($pratica_projeto_abertura) $tipo='projeto_abertura';
elseif ($pratica_plano_gestao) $tipo='plano_gestao';
elseif ($pratica_ssti) $tipo='ssti';
elseif ($pratica_laudo) $tipo='laudo';
elseif ($pratica_trelo) $tipo='trelo';
elseif ($pratica_trelo_cartao) $tipo='trelo_cartao';
elseif ($pratica_pdcl) $tipo='pdcl';
elseif ($pratica_pdcl_item) $tipo='pdcl_item';	
else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionad'.$config['genero_pratica'], 'A que área '.$config['genero_pratica'].' '.$config['pratica'].' está relacionad'.$config['genero_pratica'].'.').'Relacionad'.$config['genero_pratica'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($pratica_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_projeto" value="'.$pratica_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($pratica_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_tarefa" value="'.$pratica_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($pratica_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_perspectiva" value="'.$pratica_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($pratica_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_tema" value="'.$pratica_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($pratica_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_objetivo" value="'.$pratica_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($pratica_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_fator" value="'.$pratica_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($pratica_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_estrategia" value="'.$pratica_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($pratica_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_meta" value="'.$pratica_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($pratica_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_pratica" value="'.$pratica_pratica.'" /><input type="text" id="gestao_pratica_nome" name="gestao_pratica_nome" value="'.nome_pratica($pratica_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_acao" value="'.$pratica_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($pratica_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_canvas" value="'.$pratica_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($pratica_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_risco" value="'.$pratica_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($pratica_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_risco_resposta" value="'.$pratica_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($pratica_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador" value="'.$pratica_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($pratica_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_calendario" value="'.$pratica_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($pratica_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_monitoramento" value="'.$pratica_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($pratica_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reunião', 'Caso seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_ata" value="'.(isset($pratica_ata) ? $pratica_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($pratica_ata) ? $pratica_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('icones/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja específico de uma matriz SWOT neste campo deverá constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_mswot" value="'.(isset($pratica_mswot) ? $pratica_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($pratica_mswot) ? $pratica_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ícone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja específico de um campo de matriz SWOT neste campo deverá constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_swot" value="'.(isset($pratica_swot) ? $pratica_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($pratica_swot) ? $pratica_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_operativo" value="'.$pratica_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($pratica_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_instrumento" value="'.$pratica_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($pratica_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_recurso" value="'.$pratica_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($pratica_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_problema" value="'.$pratica_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($pratica_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_demanda" value="'.$pratica_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($pratica_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_programa" value="'.$pratica_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($pratica_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja específico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo deverá constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_licao" value="'.$pratica_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($pratica_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_evento" value="'.$pratica_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($pratica_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_link" value="'.$pratica_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($pratica_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avaliação', 'Caso seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_avaliacao" value="'.$pratica_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($pratica_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_tgn" value="'.$pratica_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($pratica_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_brainstorm" value="'.$pratica_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($pratica_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja específico de uma matriz GUT, neste campo deverá constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_gut" value="'.$pratica_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($pratica_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_causa_efeito" value="'.$pratica_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($pratica_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_arquivo" value="'.$pratica_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($pratica_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('Fórum', 'Caso seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_forum" value="'.$pratica_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($pratica_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja específico de um checklist, neste campo deverá constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_checklist" value="'.$pratica_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($pratica_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_agenda" value="'.$pratica_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($pratica_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_agrupamento" value="'.$pratica_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($pratica_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja específico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo deverá constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_patrocinador" value="'.$pratica_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($pratica_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ícone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_template" value="'.$pratica_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($pratica_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_painel" value="'.$pratica_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($pratica_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Odômetro de Indicador', 'Caso seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_painel_odometro" value="'.$pratica_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($pratica_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composição de Painéis', 'Caso seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_painel_composicao" value="'.$pratica_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($pratica_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/composicao_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_tr" value="'.$pratica_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($pratica_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_me" value="'.$pratica_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($pratica_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja específico de um item de '.$config['acao'].', neste campo deverá constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_acao_item" value="'.$pratica_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($pratica_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_beneficio" value="'.$pratica_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($pratica_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composições', 'Caso seja específico de um slideshow de composições, neste campo deverá constar o nome do slideshow de composições.').'Slideshow de composições:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_painel_slideshow" value="'.$pratica_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($pratica_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composições','Clique neste ícone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composições.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja específico de um estudo de viabilidade, neste campo deverá constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_projeto_viabilidade" value="'.$pratica_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($pratica_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ícone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja específico de um termo de abertura, neste campo deverá constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_projeto_abertura" value="'.$pratica_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($pratica_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ícone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estratégico', 'Caso seja específico de um planejamento estratégico, neste campo deverá constar o nome do planejamento estratégico.').'Planejamento estratégico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_plano_gestao" value="'.$pratica_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($pratica_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja específico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo deverá constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_ssti" value="'.$pratica_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($pratica_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ícone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja específico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo deverá constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_laudo" value="'.$pratica_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($pratica_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ícone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja específico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo deverá constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_trelo" value="'.$pratica_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($pratica_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ícone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja específico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo deverá constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_trelo_cartao" value="'.$pratica_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($pratica_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ícone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja específico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo deverá constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_pdcl" value="'.$pratica_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($pratica_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ícone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja específico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo deverá constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_pdcl_item" value="'.$pratica_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($pratica_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCLItem();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ícone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';


$sql->adTabela('pratica_gestao');
$sql->adCampo('pratica_gestao.*');
if ($uuid) $sql->adOnde('pratica_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('pratica_gestao_pratica ='.(int)$pratica_id);	
$sql->adOrdem('pratica_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_gestao_ordem'].', '.$gestao_data['pratica_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_gestao_ordem'].', '.$gestao_data['pratica_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_gestao_ordem'].', '.$gestao_data['pratica_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_gestao_ordem'].', '.$gestao_data['pratica_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['pratica_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_gestao_tarefa']).'</td>';
	elseif ($gestao_data['pratica_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_gestao_projeto']).'</td>';
	elseif ($gestao_data['pratica_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['pratica_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['pratica_gestao_tema']).'</td>';
	elseif ($gestao_data['pratica_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_gestao_objetivo']).'</td>';
	elseif ($gestao_data['pratica_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_gestao_fator']).'</td>';
	elseif ($gestao_data['pratica_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_gestao_estrategia']).'</td>';
	elseif ($gestao_data['pratica_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_gestao_meta']).'</td>';
	
	elseif ($gestao_data['pratica_gestao_semelhante']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_gestao_semelhante']).'</td>';
	
	elseif ($gestao_data['pratica_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_gestao_acao']).'</td>';
	elseif ($gestao_data['pratica_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_gestao_canvas']).'</td>';
	elseif ($gestao_data['pratica_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['pratica_gestao_risco']).'</td>';
	elseif ($gestao_data['pratica_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['pratica_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['pratica_gestao_indicador']).'</td>';
	elseif ($gestao_data['pratica_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['pratica_gestao_calendario']).'</td>';
	elseif ($gestao_data['pratica_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['pratica_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['pratica_gestao_ata']).'</td>';
	elseif ($gestao_data['pratica_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['pratica_gestao_mswot']).'</td>';
	elseif ($gestao_data['pratica_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['pratica_gestao_swot']).'</td>';
	elseif ($gestao_data['pratica_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_gestao_operativo']).'</td>';
	elseif ($gestao_data['pratica_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_gestao_instrumento']).'</td>';
	elseif ($gestao_data['pratica_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_gestao_recurso']).'</td>';
	elseif ($gestao_data['pratica_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['pratica_gestao_problema']).'</td>';
	elseif ($gestao_data['pratica_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_gestao_demanda']).'</td>';
	elseif ($gestao_data['pratica_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['pratica_gestao_programa']).'</td>';
	elseif ($gestao_data['pratica_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_gestao_licao']).'</td>';
	elseif ($gestao_data['pratica_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_gestao_evento']).'</td>';
	elseif ($gestao_data['pratica_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['pratica_gestao_link']).'</td>';
	elseif ($gestao_data['pratica_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['pratica_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_gestao_tgn']).'</td>';
	elseif ($gestao_data['pratica_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['pratica_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['pratica_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['pratica_gestao_gut']).'</td>';
	elseif ($gestao_data['pratica_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['pratica_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['pratica_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_gestao_arquivo']).'</td>';
	elseif ($gestao_data['pratica_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['pratica_gestao_forum']).'</td>';
	elseif ($gestao_data['pratica_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_gestao_checklist']).'</td>';
	elseif ($gestao_data['pratica_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['pratica_gestao_agenda']).'</td>';
	elseif ($gestao_data['pratica_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['pratica_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['pratica_gestao_patrocinador']) echo '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['pratica_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['pratica_gestao_template']).'</td>';
	elseif ($gestao_data['pratica_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['pratica_gestao_painel']).'</td>';
	elseif ($gestao_data['pratica_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['pratica_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['pratica_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['pratica_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['pratica_gestao_tr']).'</td>';	
	elseif ($gestao_data['pratica_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['pratica_gestao_me']).'</td>';	
	elseif ($gestao_data['pratica_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['pratica_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['pratica_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['pratica_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['pratica_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['pratica_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['pratica_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['pratica_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['pratica_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['pratica_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['pratica_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['pratica_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['pratica_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['pratica_gestao_ssti']).'</td>';
	elseif ($gestao_data['pratica_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['pratica_gestao_laudo']).'</td>';
	elseif ($gestao_data['pratica_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['pratica_gestao_trelo']).'</td>';
	elseif ($gestao_data['pratica_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['pratica_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['pratica_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['pratica_gestao_pdcl']).'</td>';
	elseif ($gestao_data['pratica_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['pratica_gestao_pdcl_item']).'</td>';
	
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['pratica_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';




if ($Aplic->profissional && $exibir['moeda']){
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda padrão utilizada.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'pratica_moeda', 'class=texto size=1', ($obj->pratica_moeda ? $obj->pratica_moeda : 1)).'</td></tr>';
	}	
else echo '<input type="hidden" name="pratica_moeda" id="pratica_moeda" value="'.($obj->pratica_moeda ? $obj->pratica_moeda : 1).'" />';


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milhões possíveis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inserção do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="pratica_cor" value="'.($obj->pratica_cor ? $obj->pratica_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nível de Acesso', ($config['genero_pratica']=='a' ? 'As ': 'Os ').$config['praticas'].' podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.$config['genero_pratica'].' '.$config['pratica'].'.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designados para '.($config['genero_pratica']=='a' ? 'a ': 'o ').$config['pratica'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável e os designados par'.($config['genero_pratica']=='a' ? 'a a ': 'a o ').$config['pratica'].' ver e editar '.$config['genero_pratica'].' '.$config['pratica'].'</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável e os designados par'.($config['genero_pratica']=='a' ? 'a a ': 'a o ').$config['pratica'].' podem ver '.$config['genero_pratica'].' mesm'.$config['genero_pratica'].', e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td colspan="2">'.selecionaVetor($pratica_acesso, 'pratica_acesso', 'class="texto"', ($pratica_id ? $obj->pratica_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right">'.dica('Ativ'.$config['genero_pratica'], 'Caso '.$config['genero_pratica'].' '.$config['pratica'].' ainda esteja ativ'.$config['genero_pratica'].' deverá estar marcado este campo.').'Ativ'.$config['genero_pratica'].':'.dicaF().'</td><td><input type="checkbox" value="1" name="pratica_ativa" '.($obj->pratica_ativa || !$pratica_id ? 'checked="checked"' : '').' /></td></tr>';


$campos_customizados = new CampoCustomizados('praticas', $pratica_id, 'editar');
$campos_customizados->imprimirHTML();


$cincow2h=($exibir['pratica_oque'] && $exibir['pratica_quem'] && $exibir['pratica_quando'] && $exibir['pratica_onde'] && $exibir['pratica_porque'] && $exibir['pratica_como'] && $exibir['pratica_quanto']);
if ($cincow2h){
	echo '<tr><td style="height:1px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}

if ($exibir['pratica_oque']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('O Que Fazer', 'Sumário sobre o que se trata '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'O Que:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_oque" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_oque']) ? $requisito['pratica_oque'] : '').'</textarea></td></tr>';
if ($exibir['pratica_porque']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('Por Que Fazer', 'Por que '.$config['genero_pratica'].' '.$config['pratica'].' será executad'.$config['genero_pratica'].'.').'Por que:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_porque" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_porque']) ? $requisito['pratica_porque'] : '').'</textarea></td></tr>';
if ($exibir['pratica_onde']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('Onde Fazer', 'Onde '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Onde:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_onde" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_onde']) ? $requisito['pratica_onde'] : '').'</textarea></td></tr>';
if ($exibir['pratica_quando']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('Quando Fazer', 'Quando '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Quando:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_quando" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_quando']) ? $requisito['pratica_quando'] : '').'</textarea></td></tr>';
if ($exibir['pratica_como']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('Como Fazer', 'Como '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Como:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_como" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_como']) ? $requisito['pratica_como'] : '').'</textarea></td></tr>';
if ($exibir['pratica_quanto']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('Quanto Custa', 'Custo para executar '.$config['genero_pratica'].' '.$config['pratica'].'.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_quanto" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_quanto']) ? $requisito['pratica_quanto'] : '').'</textarea></td></tr>';
if ($exibir['pratica_quem']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'Quem:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_quem" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_quem']) ? $requisito['pratica_quem'] : '').'</textarea></td></tr>';

if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	}


echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'bsc\').style.display) document.getElementById(\'bsc\').style.display=\'\'; else document.getElementById(\'bsc\').style.display=\'none\';"><a class="aba" href="javascript: void(0);">'.dica('Balanced Score Card','Clique para exibir ou esconder a lista de requisitos atendidos de acordo com BSC.').'<b>Balanced Score Card</b>'.dicaF().'</a></td></tr>';
echo '<tr id="bsc" style="display:none"><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right" width=100><div id="legenda_pratica_controlada">'.dica('Controlad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' é controlad'.$config['genero_pratica'].'.').'Controlad'.$config['genero_pratica'].':'.dicaF().'</div></td><td width=16><input type="checkbox"  class="texto" name="pratica_controlada" value="1" '.(isset($requisito['pratica_controlada']) && $requisito['pratica_controlada'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_controlada" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_controlada']) ? $requisito['pratica_justificativa_controlada']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_proativa">'.dica('Proativ'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem a capacidade de antecipar-se aos fatos, a fim de prevenir a ocorrência de situações potencialmente indesejáveis e aumentar a confiança e a previsibilidade dos processos gerenciais.').'Proativ'.$config['genero_pratica'].':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_proativa" value="1" '.(isset($requisito['pratica_proativa']) && $requisito['pratica_proativa'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_proativa" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_proativa']) ? $requisito['pratica_justificativa_proativa']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_abrange_pertinentes">'.dica('Abrangente',($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem cobertura ou escopo suficientes, horizontal ou vertical, conforme pertinente a cada processo gerencial requerido pelas áreas, processos, produtos ou partes interessadas, considerando-se o perfil d'.$config['genero_organizacao'].' '.$config['organizacao'].' e estratégias.').'Abrangente:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_abrange_pertinentes" value="1" '.(isset($requisito['pratica_abrange_pertinentes']) && $requisito['pratica_abrange_pertinentes'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_abrangencia" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_abrangencia']) ? $requisito['pratica_justificativa_abrangencia']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_continuada">'.dica('Uso Continuado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem utilização periódica e ininterrupta, considerando-se a realização de pelo menos um ciclo completo.').'Uso Continuado:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_continuada" value="1" '.(isset($requisito['pratica_continuada']) && $requisito['pratica_continuada'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_continuada" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_continuada']) ? ($requisito['pratica_justificativa_continuada'] || !$pratica_id ? $requisito['pratica_justificativa_continuada'] : ''): '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_refinada">'.dica('Refinad'.$config['genero_pratica'], ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta aperfeiçoamento decorrente dos processos de melhoria e inovação.<br><br>Em estágios avançados de refinamento, esse subfator exige processos gerenciais atendidos por '.$config['praticas'].' no estado da arte e que incorporam alguma inovação.').'Refinad'.$config['genero_pratica'].':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_refinada" value="1" '.(isset($requisito['pratica_refinada']) && $requisito['pratica_refinada'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_refinada" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_refinada']) ? $requisito['pratica_justificativa_refinada']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_refinada_implantacao">'.dica('Aperfeiçoamento em Implantação','<p>'.$config['genero_pratica'].' '.$config['pratica'].' incorpora ou representa um aperfeiçoamento em implantação.').'Aperfeiçoamento em implantação:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_refinada_implantacao" value="1" '.(isset($requisito['pratica_refinada_implantacao']) && $requisito['pratica_refinada_implantacao'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_refinada_implantacao" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_refinada_implantacao']) ? $requisito['pratica_justificativa_refinada_implantacao']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_melhoria_aprendizado">'.dica('Melhorias Decorrentes do Aprendizado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta melhorias decorrentes do aprendizado.').'Melhorias pelo aprendizado:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_melhoria_aprendizado" value="1" '.(isset($requisito['pratica_melhoria_aprendizado']) && $requisito['pratica_melhoria_aprendizado'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_melhoria_aprendizado" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_melhoria_aprendizado']) ? $requisito['pratica_justificativa_melhoria_aprendizado']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_coerente">'.dica('Coerente', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem relação harmônica com as estratégias e objetivos d'.$config['genero_organizacao'].' '.$config['organizacao'].', incluindo valores e princípios.').'Coerente:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_coerente" value="1" '.(isset($requisito['pratica_coerente']) && $requisito['pratica_coerente'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_coerente" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_coerente']) ? $requisito['pratica_justificativa_coerente']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_incoerente">'.dica('Incoerência Grave','Existe incoerência grave entre os valores, princípios, estratégias e objetivos organizacionais, na realização d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Incoerência grave:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_incoerente" value="1" '.(isset($requisito['pratica_incoerente']) && $requisito['pratica_incoerente'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_incoerente" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_incoerente']) ? $requisito['pratica_justificativa_incoerente']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_interrelacionada">'.dica('Inter-relacionad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem implementação de modo complementar com outr'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].', onde apropriado.').'Inter-relacionad'.$config['genero_pratica'].':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_interrelacionada" value="1" '.(isset($requisito['pratica_interrelacionada']) && $requisito['pratica_interrelacionada'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_interrelacionada" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_interrelacionada']) ? $requisito['pratica_justificativa_interrelacionada']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_cooperacao">'.dica('Cooperativ'.$config['genero_pratica'],'Há colaboração entre as áreas d'.$config['genero_organizacao'].' '.$config['organizacao'].' na implementação – planejamento, execução, controle ou aperfeiçoamento – n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Cooperativ'.$config['genero_pratica'].':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_cooperacao" value="1" '.(isset($requisito['pratica_cooperacao']) && $requisito['pratica_cooperacao'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_cooperacao" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_cooperacao']) ? $requisito['pratica_justificativa_cooperacao']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_cooperacao_partes">'.dica('Cooperação com as Partes Interessadas','Há colaboração com as partes interessadas pertinentes a cada processo gerencial requerido.').'Cooperação com interessados:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_cooperacao_partes" value="1" '.(isset($requisito['pratica_cooperacao_partes']) && $requisito['pratica_cooperacao_partes'] || !$pratica_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_cooperacao_partes" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_cooperacao_partes']) ? $requisito['pratica_justificativa_cooperacao_partes']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_arte">'.dica('Estado-de-Arte',($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' espelha o estado-da-arte.').'Estado-de-arte:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_arte" value="1" '.(isset($requisito['pratica_arte']) && $requisito['pratica_arte'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_arte" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_arte']) ? $requisito['pratica_justificativa_arte']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_inovacao">'.dica('Inovador'.($config['genero_pratica']=='a' ? 'a': ''),($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' apresenta uma inovação de ruptura representando um novo benchmark.').'Inovador'.($config['genero_pratica']=='a' ? 'a': '').':'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_inovacao" value="1" '.(isset($requisito['pratica_inovacao']) && $requisito['pratica_inovacao'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_inovacao" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_inovacao']) ? $requisito['pratica_justificativa_inovacao']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_gerencial">'.dica('Padrão gerencial','Há padrão gerencial suficiente que oriente a execução adequada d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Padrão gerencial:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_gerencial" value="1" '.(isset($requisito['pratica_gerencial']) && $requisito['pratica_gerencial'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_gerencial" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_gerencial']) ? $requisito['pratica_justificativa_gerencial']: '').'</textarea></td></tr>';
echo '<tr><td align="right"><div id="legenda_pratica_agil">'.dica('Agilidade','Há agilidade suficiente nos processos gerenciais exigidos no Critério, incorporados n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Agilidade:'.dicaF().'</div></td><td><input type="checkbox" class="texto" name="pratica_agil" value="1" '.(isset($requisito['pratica_agil']) && $requisito['pratica_agil'] ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_justificativa_agil" cols="60" rows="2" class="textarea">'.(isset($requisito['pratica_justificativa_agil']) ? $requisito['pratica_justificativa_agil']: '').'</textarea></td></tr>';
echo '</table></td></tr>';

echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'area_pauta\').style.display) document.getElementById(\'area_pauta\').style.display=\'\'; else document.getElementById(\'area_pauta\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Pauta</b></a></td></tr>';
echo '<tr id="area_pauta" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td align="right" style="white-space: nowrap">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.<br><br>Caso esteja editando '.$config['genero_pratica'].' '.$config['pratica'].', ao mudar de uma pauta para outra '.$config['genero_marcador'].'s '.$config['marcadores'].' selecionad'.$config['genero_marcador'].'s serão salv'.$config['genero_marcador'].'s.<br><br>No caso de ser '.$config['genero_pratica'].' '.$config['pratica'].' nov'.$config['genero_pratica'].' é necessário primeiramente salvar para poder selecionar '.$config['marcadores'].' de multiplas pautas.').'Pauta:'.dicaF().'</td><td>'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="mudar_pauta();" class="texto"', $pratica_modelo_id).'</td></tr></table></td></tr>';
echo '<tr><td colspan=20><div id="combo_pauta"></div></td></tr>';
echo '</table></fieldset></td></tr>';

if ($Aplic->profissional){
	echo '<tr><td style="height:1px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'legislacao\').style.display) document.getElementById(\'legislacao\').style.display=\'\'; else document.getElementById(\'legislacao\').style.display=\'none\';"><a class="aba" href="javascript: void(0);">'.dica('Legislação','Clique para exibir ou esconder a lista de legislação relacionada.').'<b>Legislação</b>'.dicaF().'</a></td></tr>';
	
	echo '<tr id="legislacao" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0>';
	
		echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
		echo '<tr><td align="right" style="width:85px;">'.dica('Norma ', 'O nome da norma.').'Norma:'.dicaF().'</td><td><input type="text" id="pratica_legislacao_norma" name="pratica_legislacao_norma" value="" style="width:350px;" class="texto" /></td></tr>';
		$PraticaLegislacaoEsfera=getSisValor('PraticaLegislacaoEsfera','','','sisvalor_id');
		echo '<tr><td align="right" style="width:85px;">'.dica('Esfera', 'A esfera de criação da legislação.').'Esfera:'.dicaF().'</td><td>'.selecionaVetor($PraticaLegislacaoEsfera, 'pratica_legislacao_esfera', 'size="1" class="texto"').'</td></tr>';
		echo '<tr><td align="right">'.dica('Descrição', 'O detalhamento do legislacao de recurso.').'Descrição:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" rows="2" class="texto" name="pratica_legislacao_detalhe" id="pratica_legislacao_detalhe" style="width:550px;"></textarea></td></tr>';
		$PraticaLegislacaoImpacto=getSisValor('PraticaLegislacaoImpacto','','','sisvalor_id');
		echo '<tr><td align="right" style="width:85px;">'.dica('Impacto', 'O Impacto da legislação n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Impacto:'.dicaF().'</td><td>'.selecionaVetor($PraticaLegislacaoImpacto, 'pratica_legislacao_impacto', 'size="1" class="texto"').'</td></tr>';
		echo '</table></td>';
		
	echo '<input type="hidden" id="pratica_legislacao_id" name="pratica_legislacao_id" value="" /><td id="adicionar_legislacao" style="display:"><a href="javascript: void(0);" onclick="incluir_legislacao();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o legislacao de recurso.').'</a></td>';
	echo '<td id="confirmar_legislacao" style="display:none"><a href="javascript: void(0);" onclick="cancelar_legislacao();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do legislacao de recurso.').'</a><a href="javascript: void(0);" onclick="incluir_legislacao();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do legislacao de recurso.').'</a></td></tr>';
	echo '</td></tr>';
	$sql->adTabela('pratica_legislacao');
	$sql->adOnde('pratica_legislacao_pratica = '.(int)$pratica_id);
	$sql->adCampo('pratica_legislacao.*');
	$sql->adOrdem('pratica_legislacao_ordem');
	$legislacoes=$sql->ListaChave('pratica_legislacao_id');
	$sql->limpar();
	echo '<tr><td colspan=20 align=left><table cellspacing=0 cellpadding=0><tr><td style="width:85px;"></td><td><div id="legislacoes">';
	if (count($legislacoes)) {
		echo '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Norma</th><th>Esfera</th><th>Detalhamento</th><th>Impacto</th><th></th></tr>';
		foreach ($legislacoes as $pratica_legislacao_id => $linha) {
			echo '<tr>';
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_legislacao('.$linha['pratica_legislacao_ordem'].', '.$linha['pratica_legislacao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_legislacao('.$linha['pratica_legislacao_ordem'].', '.$linha['pratica_legislacao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_legislacao('.$linha['pratica_legislacao_ordem'].', '.$linha['pratica_legislacao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_legislacao('.$linha['pratica_legislacao_ordem'].', '.$linha['pratica_legislacao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			echo '<td align="left">'.$linha['pratica_legislacao_norma'].'</td>';
			echo '<td align="left">'.(isset($PraticaLegislacaoEsfera[$linha['pratica_legislacao_esfera']]) && $PraticaLegislacaoEsfera[$linha['pratica_legislacao_esfera']] ? $PraticaLegislacaoEsfera[$linha['pratica_legislacao_esfera']] : '&nbsp;').'</td>';
			echo '<td align="left">'.($linha['pratica_legislacao_detalhe'] ? $linha['pratica_legislacao_detalhe'] : '&nbsp;').'</td>';
			echo '<td align="left">'.(isset($PraticaLegislacaoImpacto[$linha['pratica_legislacao_impacto']]) && $PraticaLegislacaoImpacto[$linha['pratica_legislacao_impacto']] ? $PraticaLegislacaoImpacto[$linha['pratica_legislacao_impacto']] : '&nbsp;').'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_legislacao('.$linha['pratica_legislacao_id'].');">'.imagem('icones/editar.gif', 'Editar legislacao', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o legislacao de recurso.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este legislacao?\')) {excluir_legislacao('.$linha['pratica_legislacao_id'].');}">'.imagem('icones/remover.png', 'Excluir legislacao', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este legislacao de recurso.').'</a></td>';
			echo '</tr>';
			}
		echo '</table></td></tr></table>';
		}
	echo '</div></td></tr>';
	echo '</table></td></tr>';
	echo '</table></td></tr>';
	}





if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/pratica_editar_pro.php');
















echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'area_notificar\').style.display) document.getElementById(\'area_notificar\').style.display=\'\'; else document.getElementById(\'area_notificar\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificar</b></a></td></tr>';
echo '<tr id="area_notificar" style="display:'.($Aplic->getPref('informa_aberto') ? '' : 'none').'"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Notificar', 'Um aviso da '.($pratica_id ? 'modificação' : 'criação').' '.($config['genero_pratica']=='a' ? 'desta ': 'deste ').$config['pratica'].' será enviado').'Notificar:'.dicaF().'</td><td width="100%" colspan="2" valign="top"><table cellspacing=0 cellpadding=0><tr>';
echo '<td>'.dica('Responsável pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Ao selecionar esta opção, o responsável pel'.$config['genero_pratica'].' '.$config['pratica'].' será informado '.($pratica_id ? 'das alterações realizadas n'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.' : 'da criação d'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.')).'Responsável'.dicaF().'</td><td><input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" /></td>';
echo '<td>'.dica('Designados d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Ao selecionar esta opção, os designados '.($config['genero_pratica']=='a' ? 'desta ': 'deste ').$config['pratica'].' serão informado '.($pratica_id ? 'das alterações realizadas n'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.' : 'da criação d'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.')).'Designados'.dicaF().'</td><td><input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' value="1" /></td>';
echo '</table></td></tr>';
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<tr><td></td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td valign="top">'.($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso') ?  botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail.','','popEmailContatos()') : '').'</td>'.($config['email_ativo'] ? ''.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Extra:'.dicaF().'</td><td><input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '</table></fieldset></td></tr>';




echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($pratica_id ? 'edição' : 'criação').' do pratica.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';

echo '</form>';


echo estiloFundoCaixa();

?>
<script type="text/javascript">


function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('pratica_cia').value+'&cias_id_selecionadas='+document.getElementById('pratica_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.pratica_cias.value = organizacao_id_string;
	document.getElementById('pratica_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('pratica_cias').value);
	__buildTooltip();
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.pratica_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pratica_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pratica_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pratica_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pratica_dept').value+'&cia_id='+document.getElementById('pratica_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pratica_dept').value+'&cia_id='+document.getElementById('pratica_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('pratica_cia').value=cia_id;
	document.getElementById('pratica_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function cancelar_legislacao(){
	document.getElementById('pratica_legislacao_id').value=0;
	document.getElementById('pratica_legislacao_detalhe').value='';
	document.getElementById('pratica_legislacao_norma').value='';
	document.getElementById('adicionar_legislacao').style.display='';
	document.getElementById('confirmar_legislacao').style.display='none';

	}


function mudar_posicao_legislacao(pratica_legislacao_ordem, pratica_legislacao_id, direcao){
	xajax_mudar_posicao_legislacao_ajax(pratica_legislacao_ordem, pratica_legislacao_id, direcao, document.getElementById('pratica_id').value, document.getElementById('uuid').value);
	}

function editar_legislacao(pratica_legislacao_id){
	xajax_editar_legislacao(pratica_legislacao_id);
	document.getElementById('adicionar_legislacao').style.display="none";
	document.getElementById('confirmar_legislacao').style.display="";
	}

function incluir_legislacao(){
	if (document.getElementById('pratica_legislacao_norma').value!=''){
		xajax_incluir_legislacao_ajax(
			document.getElementById('pratica_id').value, 
			document.getElementById('uuid').value, 
			document.getElementById('pratica_legislacao_id').value, 
			document.getElementById('pratica_legislacao_norma').value, 
			document.getElementById('pratica_legislacao_detalhe').value, 
			document.getElementById('pratica_legislacao_impacto').value, 
			document.getElementById('pratica_legislacao_esfera').value
			);
			
		document.getElementById('pratica_legislacao_id').value=null;
		document.getElementById('pratica_legislacao_norma').value='';
		document.getElementById('pratica_legislacao_detalhe').value='';
		document.getElementById('adicionar_legislacao').style.display='';
		document.getElementById('confirmar_legislacao').style.display='none';
		}
	else alert('Insira um nome para o legislacao de recurso.');
	}

function excluir_legislacao(pratica_legislacao_id){
	xajax_excluir_legislacao_ajax(pratica_legislacao_id, document.getElementById('pratica_id').value, document.getElementById('uuid').value);
	}



function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Contatos', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
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




function popImportar(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Importar', 500, 500, 'm=praticas&a=pratica_importar_pro&dialogo=1&pratica_id='+document.getElementById('pratica_id').value+'&ano='+document.getElementById('IdxPraticaAno').value, null, window);
	else window.open('./index.php?m=praticas&a=pratica_importar_pro&dialogo=1&pratica_id='+document.getElementById('pratica_id').value+'&ano='+document.getElementById('IdxPraticaAno').value, 'Importar','left=0,top=0,height=250,width=250,scrollbars=no, resizable=no');
	}

var pauta_atual=document.getElementById('pratica_modelo_id').value;

function mudar_ano(){
	env.a.value='pratica_editar';
	env.fazerSQL.value='';
	env.submit();
	}



function marcar_marcador(chave){
 	if (document.getElementById('checagem_'+chave).checked) document.getElementById('caixa_'+chave).style.backgroundColor='#FFFF00';
 	else document.getElementById('caixa_'+chave).style.backgroundColor='#f8f7f5';
 	xajax_marcar_marcador(document.getElementById('pratica_id').value, document.getElementById('uuid').value, chave, document.getElementById('checagem_'+chave).checked, document.getElementById('IdxPraticaAno').value);
	}

function marcar_verbo(chave, marcador_id){
 	if (document.getElementById('verbo_'+chave).checked) document.getElementById('caixa2_'+chave).style.backgroundColor='#ffddab';
 	else document.getElementById('caixa2_'+chave).style.backgroundColor='#f8f7f5';
 	xajax_marcar_verbo(document.getElementById('pratica_id').value, document.getElementById('uuid').value, marcador_id, chave, document.getElementById('verbo_'+chave).checked, document.getElementById('IdxPraticaAno').value);
	}


function marcar_complemento(marcador_id){
 	if (document.getElementById('complemento_'+marcador_id).checked) document.getElementById('caixa3_'+marcador_id).style.backgroundColor='#abfeff';
 	else document.getElementById('caixa3_'+marcador_id).style.backgroundColor='#f8f7f5';
 	xajax_marcar_complemento(document.getElementById('pratica_id').value, document.getElementById('uuid').value, marcador_id, document.getElementById('complemento_'+marcador_id).checked, document.getElementById('IdxPraticaAno').value);
	}


function marcar_evidencia(marcador_id){
 	if (document.getElementById('evidencia_'+marcador_id).checked) document.getElementById('caixa4_'+marcador_id).style.backgroundColor='#abffaf';
 	else document.getElementById('caixa4_'+marcador_id).style.backgroundColor='#f8f7f5';
 	xajax_marcar_evidencia(document.getElementById('pratica_id').value, document.getElementById('uuid').value, marcador_id, document.getElementById('evidencia_'+marcador_id).checked, document.getElementById('IdxPraticaAno').value);
	}



function mudar_pauta(){
	xajax_mudar_pauta(document.getElementById('pratica_id').value, document.getElementById('uuid').value, document.getElementById('pratica_modelo_id').value, document.getElementById('IdxPraticaAno').value);
	__buildTooltip();
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pratica_cia').value+'&usuario_id='+document.getElementById('pratica_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pratica_cia').value+'&usuario_id='+document.getElementById('pratica_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('pratica_responsavel').value=usuario_id;
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function mudar_om(){
	var pratica_cia=document.getElementById('pratica_cia').value;
	xajax_selecionar_om_ajax(pratica_cia,'pratica_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir <?php echo ($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica']?>?" )) {
		var f = document.env;
		f.del.value=1;
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.pratica_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pratica_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.pratica_nome.value.length < 3) {
		alert('Escreva um nome para a <?php echo $config["pratica"]?> válido');
		f.pratica_nome.focus();
		}
	else if (f.pratica_cia.options[f.pratica_cia.selectedIndex].value < 1) {
		alert('Necessário escolher uma <?php echo $config["om"]?>');
		f.pratica_cia.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}


function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Contatos', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_cia').value+'&usuarios_id_selecionados='+contatos_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_cia').value+'&usuarios_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
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
	
	

<?php  if ($Aplic->profissional) { ?>
	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('pratica_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('pratica_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.pratica_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('pratica_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.pratica_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('pratica_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('pratica_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.pratica_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('pratica_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.pratica_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('pratica_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.pratica_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pratica_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.pratica_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('pratica_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.pratica_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('pratica_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.pratica_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('pratica_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.pratica_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('pratica_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.pratica_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('pratica_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.pratica_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('pratica_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.pratica_pratica.value = chave;
	document.env.gestao_pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('pratica_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('pratica_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('pratica_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.pratica_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('pratica_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.pratica_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('pratica_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.pratica_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('pratica_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('pratica_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.pratica_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('pratica_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('pratica_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.pratica_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('pratica_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('pratica_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.pratica_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('pratica_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.pratica_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('pratica_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.pratica_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Camçpo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('pratica_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.pratica_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('pratica_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('pratica_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.pratica_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('pratica_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('pratica_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.pratica_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('pratica_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('pratica_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.pratica_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('pratica_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.pratica_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('pratica_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('pratica_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.pratica_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('pratica_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.pratica_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('pratica_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.pratica_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('pratica_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('pratica_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.pratica_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('pratica_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('pratica_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.pratica_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('pratica_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('pratica_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.pratica_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('pratica_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.pratica_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('pratica_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('pratica_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.pratica_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('pratica_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('pratica_cia').value, 'Matriz GUT','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.pratica_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('pratica_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('pratica_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.pratica_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('pratica_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('pratica_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.pratica_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('pratica_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('pratica_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.pratica_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('pratica_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('pratica_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.pratica_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('pratica_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('pratica_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.pratica_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('pratica_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('pratica_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.pratica_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('pratica_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('pratica_cia').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.pratica_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('pratica_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('pratica_cia').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.pratica_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('pratica_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.pratica_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('pratica_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.pratica_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('pratica_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('pratica_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.pratica_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('pratica_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.pratica_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('pratica_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('pratica_cia').value, 'Slideshow de Composições','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.pratica_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('pratica_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('pratica_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.pratica_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('pratica_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('pratica_cia').value, 'Termo de Abertura','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.pratica_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('pratica_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('pratica_cia').value, 'Planejamento Estratégico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.pratica_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		
	
	
function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('pratica_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.pratica_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('pratica_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.pratica_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('pratica_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.pratica_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('pratica_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.pratica_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('pratica_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.pratica_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function popPDCLItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('pratica_cia').value, window.setPDCLItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('pratica_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCLItem(chave, valor){
	limpar_tudo();
	document.env.pratica_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	
		

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.pratica_projeto.value = null;
	document.env.pratica_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.pratica_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.pratica_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.pratica_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.pratica_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.pratica_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.pratica_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.pratica_pratica.value = null;
	document.env.gestao_pratica_nome.value = '';
	document.env.pratica_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.pratica_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.pratica_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.pratica_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.pratica_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.pratica_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.pratica_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.pratica_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.pratica_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.pratica_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.pratica_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.pratica_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.pratica_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.pratica_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.pratica_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.pratica_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.pratica_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.pratica_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.pratica_link.value = null;
	document.env.link_nome.value = '';
	document.env.pratica_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.pratica_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.pratica_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.pratica_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.pratica_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.pratica_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.pratica_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.pratica_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.pratica_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.pratica_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.pratica_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.pratica_template.value = null;
	document.env.template_nome.value = '';
	document.env.pratica_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.pratica_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.pratica_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.pratica_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.pratica_me.value = null;
	document.env.me_nome.value = '';
	document.env.pratica_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.pratica_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.pratica_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.pratica_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.pratica_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.pratica_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.pratica_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.pratica_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.pratica_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.pratica_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.pratica_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.pratica_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';			
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('pratica_id').value,
	document.getElementById('uuid').value,
	f.pratica_projeto.value,
	f.pratica_tarefa.value,
	f.pratica_perspectiva.value,
	f.pratica_tema.value,
	f.pratica_objetivo.value,
	f.pratica_fator.value,
	f.pratica_estrategia.value,
	f.pratica_meta.value,
	f.pratica_pratica.value,
	f.pratica_acao.value,
	f.pratica_canvas.value,
	f.pratica_risco.value,
	f.pratica_risco_resposta.value,
	f.pratica_indicador.value,
	f.pratica_calendario.value,
	f.pratica_monitoramento.value,
	f.pratica_ata.value,
	f.pratica_mswot.value,
	f.pratica_swot.value,
	f.pratica_operativo.value,
	f.pratica_instrumento.value,
	f.pratica_recurso.value,
	f.pratica_problema.value,
	f.pratica_demanda.value,
	f.pratica_programa.value,
	f.pratica_licao.value,
	f.pratica_evento.value,
	f.pratica_link.value,
	f.pratica_avaliacao.value,
	f.pratica_tgn.value,
	f.pratica_brainstorm.value,
	f.pratica_gut.value,
	f.pratica_causa_efeito.value,
	f.pratica_arquivo.value,
	f.pratica_forum.value,
	f.pratica_checklist.value,
	f.pratica_agenda.value,
	f.pratica_agrupamento.value,
	f.pratica_patrocinador.value,
	f.pratica_template.value,
	f.pratica_painel.value,
	f.pratica_painel_odometro.value,
	f.pratica_painel_composicao.value,
	f.pratica_tr.value,
	f.pratica_me.value,
	f.pratica_acao_item.value,
	f.pratica_beneficio.value,
	f.pratica_painel_slideshow.value,
	f.pratica_projeto_viabilidade.value,
	f.pratica_projeto_abertura.value,
	f.pratica_plano_gestao.value,
	f.pratica_ssti.value,
	f.pratica_laudo.value,
	f.pratica_trelo.value,
	f.pratica_trelo_cartao.value,
	f.pratica_pdcl.value,
	f.pratica_pdcl_item.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(pratica_gestao_id){
	xajax_excluir_gestao(document.getElementById('pratica_id').value, document.getElementById('uuid').value, pratica_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, pratica_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, pratica_gestao_id, direcao, document.getElementById('pratica_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$pratica_id && (
	$pratica_tarefa || 
	$pratica_projeto || 
	$pratica_perspectiva || 
	$pratica_tema || 
	$pratica_objetivo || 
	$pratica_fator || 
	$pratica_estrategia || 
	$pratica_meta || 
	$pratica_pratica || 
	$pratica_acao || 
	$pratica_canvas || 
	$pratica_risco || 
	$pratica_risco_resposta || 
	$pratica_indicador || 
	$pratica_calendario || 
	$pratica_monitoramento || 
	$pratica_ata || 
	$pratica_mswot || 
	$pratica_swot || 
	$pratica_operativo || 
	$pratica_instrumento || 
	$pratica_recurso || 
	$pratica_problema || 
	$pratica_demanda || 
	$pratica_programa || 
	$pratica_licao || 
	$pratica_evento || 
	$pratica_link || 
	$pratica_avaliacao || 
	$pratica_tgn || 
	$pratica_brainstorm || 
	$pratica_gut || 
	$pratica_causa_efeito || 
	$pratica_arquivo || 
	$pratica_forum || 
	$pratica_checklist || 
	$pratica_agenda || 
	$pratica_agrupamento || 
	$pratica_patrocinador || 
	$pratica_template || 
	$pratica_painel || 
	$pratica_painel_odometro || 
	$pratica_painel_composicao || 
	$pratica_tr || 
	$pratica_me || 
	$pratica_acao_item || 
	$pratica_beneficio || 
	$pratica_painel_slideshow || 
	$pratica_projeto_viabilidade || 
	$pratica_projeto_abertura || 
	$pratica_plano_gestao|| 
	$pratica_ssti || 
	$pratica_laudo || 
	$pratica_trelo || 
	$pratica_trelo_cartao || 
	$pratica_pdcl || 
	$pratica_pdcl_item
	)) echo 'incluir_relacionado();';
	?>	
		


mudar_pauta();



</script>

