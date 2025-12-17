<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
require_once BASE_DIR.'/modulos/praticas/brainstorm.class.php';
require_once $Aplic->getClasseSistema('CampoCustomizados');
$Aplic->carregarCalendarioJS();
$brainstorm_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$brainstorm_id=getParam($_REQUEST, 'brainstorm_id', null);
$salvar=getParam($_REQUEST, 'salvar', 0);

$Aplic->carregarCKEditorJS();


$percentual =getSisValor('TarefaPorcentagem','','','sisvalor_id');

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'brainstorm\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$brainstormjeto=getParam($_REQUEST, 'brainstormjeto', null);
$brainstorm_tarefa=getParam($_REQUEST, 'brainstorm_tarefa', null);
$brainstorm_perspectiva=getParam($_REQUEST, 'brainstorm_perspectiva', null);
$brainstorm_tema=getParam($_REQUEST, 'brainstorm_tema', null);
$brainstorm_objetivo=getParam($_REQUEST, 'brainstorm_objetivo', null);
$brainstorm_fator=getParam($_REQUEST, 'brainstorm_fator', null);
$brainstorm_estrategia=getParam($_REQUEST, 'brainstorm_estrategia', null);
$brainstorm_meta=getParam($_REQUEST, 'brainstorm_meta', null);
$brainstorm_pratica=getParam($_REQUEST, 'brainstorm_pratica', null);
$brainstorm_acao=getParam($_REQUEST, 'brainstorm_acao', null);
$brainstorm_canvas=getParam($_REQUEST, 'brainstorm_canvas', null);
$brainstorm_risco=getParam($_REQUEST, 'brainstorm_risco', null);
$brainstorm_risco_resposta=getParam($_REQUEST, 'brainstorm_risco_resposta', null);
$brainstorm_indicador=getParam($_REQUEST, 'brainstorm_indicador', null);
$brainstorm_calendario=getParam($_REQUEST, 'brainstorm_calendario', null);
$brainstorm_monitoramento=getParam($_REQUEST, 'brainstorm_monitoramento', null);
$brainstorm_ata=getParam($_REQUEST, 'brainstorm_ata', null);
$brainstorm_mswot=getParam($_REQUEST, 'brainstorm_mswot', null);
$brainstorm_swot=getParam($_REQUEST, 'brainstorm_swot', null);
$brainstorm_operativo=getParam($_REQUEST, 'brainstorm_operativo', null);
$brainstorm_instrumento=getParam($_REQUEST, 'brainstorm_instrumento', null);
$brainstorm_recurso=getParam($_REQUEST, 'brainstorm_recurso', null);
$brainstormblema=getParam($_REQUEST, 'brainstormblema', null);
$brainstorm_demanda=getParam($_REQUEST, 'brainstorm_demanda', null);
$brainstormgrama=getParam($_REQUEST, 'brainstormgrama', null);
$brainstorm_licao=getParam($_REQUEST, 'brainstorm_licao', null);
$brainstorm_evento=getParam($_REQUEST, 'brainstorm_evento', null);
$brainstorm_link=getParam($_REQUEST, 'brainstorm_link', null);
$brainstorm_avaliacao=getParam($_REQUEST, 'brainstorm_avaliacao', null);
$brainstorm_tgn=getParam($_REQUEST, 'brainstorm_tgn', null);
$brainstorm_brainstorm=getParam($_REQUEST, 'brainstorm_brainstorm', null);
$brainstorm_gut=getParam($_REQUEST, 'brainstorm_gut', null);
$brainstorm_causa_efeito=getParam($_REQUEST, 'brainstorm_causa_efeito', null);
$brainstorm_arquivo=getParam($_REQUEST, 'brainstorm_arquivo', null);
$brainstorm_forum=getParam($_REQUEST, 'brainstorm_forum', null);
$brainstorm_checklist=getParam($_REQUEST, 'brainstorm_checklist', null);
$brainstorm_agenda=getParam($_REQUEST, 'brainstorm_agenda', null);
$brainstorm_agrupamento=getParam($_REQUEST, 'brainstorm_agrupamento', null);
$brainstorm_patrocinador=getParam($_REQUEST, 'brainstorm_patrocinador', null);
$brainstorm_template=getParam($_REQUEST, 'brainstorm_template', null);
$brainstorm_painel=getParam($_REQUEST, 'brainstorm_painel', null);
$brainstorm_painel_odometro=getParam($_REQUEST, 'brainstorm_painel_odometro', null);
$brainstorm_painel_composicao=getParam($_REQUEST, 'brainstorm_painel_composicao', null);
$brainstorm_tr=getParam($_REQUEST, 'brainstorm_tr', null);
$brainstorm_me=getParam($_REQUEST, 'brainstorm_me', null);
$brainstorm_acao_item=getParam($_REQUEST, 'brainstorm_acao_item', null);
$brainstorm_beneficio=getParam($_REQUEST, 'brainstorm_beneficio', null);
$brainstorm_painel_slideshow=getParam($_REQUEST, 'brainstorm_painel_slideshow', null);
$brainstormjeto_viabilidade=getParam($_REQUEST, 'brainstormjeto_viabilidade', null);
$brainstormjeto_abertura=getParam($_REQUEST, 'brainstormjeto_abertura', null);
$brainstorm_plano_gestao=getParam($_REQUEST, 'brainstorm_plano_gestao', null);
$brainstorm_ssti=getParam($_REQUEST, 'brainstorm_ssti', null);
$brainstorm_laudo=getParam($_REQUEST, 'brainstorm_laudo', null);
$brainstorm_trelo=getParam($_REQUEST, 'brainstorm_trelo', null);
$brainstorm_trelo_cartao=getParam($_REQUEST, 'brainstorm_trelo_cartao', null);
$brainstorm_pdcl=getParam($_REQUEST, 'brainstorm_pdcl', null);
$brainstorm_pdcl_item=getParam($_REQUEST, 'brainstorm_pdcl_item', null);

$obj = new CBrainstorm();
if ($brainstorm_id){
	$obj->load($brainstorm_id);
	$cia_id=$obj->brainstorm_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

	if (
		$brainstormjeto || 
		$brainstorm_tarefa || 
		$brainstorm_perspectiva || 
		$brainstorm_tema || 
		$brainstorm_objetivo || 
		$brainstorm_fator || 
		$brainstorm_estrategia || 
		$brainstorm_meta || 
		$brainstorm_pratica || 
		$brainstorm_acao || 
		$brainstorm_canvas || 
		$brainstorm_risco || 
		$brainstorm_risco_resposta || 
		$brainstorm_indicador || 
		$brainstorm_calendario || 
		$brainstorm_monitoramento || 
		$brainstorm_ata || 
		$brainstorm_mswot || 
		$brainstorm_swot || 
		$brainstorm_operativo || 
		$brainstorm_instrumento || 
		$brainstorm_recurso || 
		$brainstormblema || 
		$brainstorm_demanda || 
		$brainstormgrama || 
		$brainstorm_licao || 
		$brainstorm_evento || 
		$brainstorm_link || 
		$brainstorm_avaliacao || 
		$brainstorm_tgn || 
		$brainstorm_brainstorm || 
		$brainstorm_gut || 
		$brainstorm_causa_efeito || 
		$brainstorm_arquivo || 
		$brainstorm_forum || 
		$brainstorm_checklist || 
		$brainstorm_agenda || 
		$brainstorm_agrupamento || 
		$brainstorm_patrocinador || 
		$brainstorm_template || 
		$brainstorm_painel || 
		$brainstorm_painel_odometro || 
		$brainstorm_painel_composicao || 
		$brainstorm_tr || 
		$brainstorm_me || 
		$brainstorm_acao_item || 
		$brainstorm_beneficio || 
		$brainstorm_painel_slideshow || 
		$brainstormjeto_viabilidade || 
		$brainstormjeto_abertura || 
		$brainstorm_plano_gestao|| 
		$brainstorm_ssti || 
		$brainstorm_laudo || 
		$brainstorm_trelo || 
		$brainstorm_trelo_cartao || 
		$brainstorm_pdcl || 
		$brainstorm_pdcl_item
		){
		$sql->adTabela('cias');
		if ($brainstorm_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
		elseif ($brainstormjeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
		elseif ($brainstorm_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		elseif ($brainstorm_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		elseif ($brainstorm_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
		elseif ($brainstorm_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
		elseif ($brainstorm_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		elseif ($brainstorm_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
		elseif ($brainstorm_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		elseif ($brainstorm_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		elseif ($brainstorm_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
		elseif ($brainstorm_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
		elseif ($brainstorm_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
		elseif ($brainstorm_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		elseif ($brainstorm_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
		elseif ($brainstorm_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
		elseif ($brainstorm_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
		elseif ($brainstorm_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
		elseif ($brainstorm_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
		elseif ($brainstorm_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
		elseif ($brainstorm_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
		elseif ($brainstorm_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
		elseif ($brainstormblema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
		elseif ($brainstorm_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
		elseif ($brainstormgrama) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
		elseif ($brainstorm_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
		elseif ($brainstorm_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
		elseif ($brainstorm_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
		elseif ($brainstorm_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
		elseif ($brainstorm_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
		elseif ($brainstorm_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
		elseif ($brainstorm_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
		elseif ($brainstorm_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
		elseif ($brainstorm_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
		elseif ($brainstorm_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
		elseif ($brainstorm_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
		elseif ($brainstorm_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
		elseif ($brainstorm_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
		elseif ($brainstorm_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
		elseif ($brainstorm_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
		elseif ($brainstorm_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
		elseif ($brainstorm_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
		elseif ($brainstorm_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
		elseif ($brainstorm_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
		elseif ($brainstorm_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
		elseif ($brainstorm_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
		elseif ($brainstorm_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
		elseif ($brainstorm_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
		elseif ($brainstormjeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
		elseif ($brainstormjeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
		elseif ($brainstorm_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
		elseif ($brainstorm_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
		elseif ($brainstorm_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
		elseif ($brainstorm_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
		elseif ($brainstorm_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
		elseif ($brainstorm_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
		elseif ($brainstorm_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
	
		if ($brainstorm_tarefa) $sql->adOnde('tarefa_id = '.(int)$brainstorm_tarefa);
		elseif ($brainstormjeto) $sql->adOnde('projeto_id = '.(int)$brainstormjeto);
		elseif ($brainstorm_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$brainstorm_perspectiva);
		elseif ($brainstorm_tema) $sql->adOnde('tema_id = '.(int)$brainstorm_tema);
		elseif ($brainstorm_objetivo) $sql->adOnde('objetivo_id = '.(int)$brainstorm_objetivo);
		elseif ($brainstorm_fator) $sql->adOnde('fator_id = '.(int)$brainstorm_fator);
		elseif ($brainstorm_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$brainstorm_estrategia);
		elseif ($brainstorm_meta) $sql->adOnde('pg_meta_id = '.(int)$brainstorm_meta);
		elseif ($brainstorm_pratica) $sql->adOnde('pratica_id = '.(int)$brainstorm_pratica);
		elseif ($brainstorm_acao) $sql->adOnde('plano_acao_id = '.(int)$brainstorm_acao);
		elseif ($brainstorm_canvas) $sql->adOnde('canvas_id = '.(int)$brainstorm_canvas);
		elseif ($brainstorm_risco) $sql->adOnde('risco_id = '.(int)$brainstorm_risco);
		elseif ($brainstorm_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$brainstorm_risco_resposta);
		elseif ($brainstorm_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$brainstorm_indicador);
		elseif ($brainstorm_calendario) $sql->adOnde('calendario_id = '.(int)$brainstorm_calendario);
		elseif ($brainstorm_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$brainstorm_monitoramento);
		elseif ($brainstorm_ata) $sql->adOnde('ata_id = '.(int)$brainstorm_ata);
		elseif ($brainstorm_mswot) $sql->adOnde('mswot_id = '.(int)$brainstorm_mswot);
		elseif ($brainstorm_swot) $sql->adOnde('swot_id = '.(int)$brainstorm_swot);
		elseif ($brainstorm_operativo) $sql->adOnde('operativo_id = '.(int)$brainstorm_operativo);
		elseif ($brainstorm_instrumento) $sql->adOnde('instrumento_id = '.(int)$brainstorm_instrumento);
		elseif ($brainstorm_recurso) $sql->adOnde('recurso_id = '.(int)$brainstorm_recurso);
		elseif ($brainstormblema) $sql->adOnde('problema_id = '.(int)$brainstormblema);
		elseif ($brainstorm_demanda) $sql->adOnde('demanda_id = '.(int)$brainstorm_demanda);
		elseif ($brainstormgrama) $sql->adOnde('programa_id = '.(int)$brainstormgrama);
		elseif ($brainstorm_licao) $sql->adOnde('licao_id = '.(int)$brainstorm_licao);
		elseif ($brainstorm_evento) $sql->adOnde('evento_id = '.(int)$brainstorm_evento);
		elseif ($brainstorm_link) $sql->adOnde('link_id = '.(int)$brainstorm_link);
		elseif ($brainstorm_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$brainstorm_avaliacao);
		elseif ($brainstorm_tgn) $sql->adOnde('tgn_id = '.(int)$brainstorm_tgn);
		elseif ($brainstorm_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$brainstorm_brainstorm);
		elseif ($brainstorm_gut) $sql->adOnde('gut_id = '.(int)$brainstorm_gut);
		elseif ($brainstorm_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$brainstorm_causa_efeito);
		elseif ($brainstorm_arquivo) $sql->adOnde('arquivo_id = '.(int)$brainstorm_arquivo);
		elseif ($brainstorm_forum) $sql->adOnde('forum_id = '.(int)$brainstorm_forum);
		elseif ($brainstorm_checklist) $sql->adOnde('checklist_id = '.(int)$brainstorm_checklist);
		elseif ($brainstorm_agenda) $sql->adOnde('agenda_id = '.(int)$brainstorm_agenda);
		elseif ($brainstorm_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$brainstorm_agrupamento);
		elseif ($brainstorm_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$brainstorm_patrocinador);
		elseif ($brainstorm_template) $sql->adOnde('template_id = '.(int)$brainstorm_template);
		elseif ($brainstorm_painel) $sql->adOnde('painel_id = '.(int)$brainstorm_painel);
		elseif ($brainstorm_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$brainstorm_painel_odometro);
		elseif ($brainstorm_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$brainstorm_painel_composicao);
		elseif ($brainstorm_tr) $sql->adOnde('tr_id = '.(int)$brainstorm_tr);
		elseif ($brainstorm_me) $sql->adOnde('me_id = '.(int)$brainstorm_me);
		elseif ($brainstorm_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$brainstorm_acao_item);
		elseif ($brainstorm_beneficio) $sql->adOnde('beneficio_id = '.(int)$brainstorm_beneficio);
		elseif ($brainstorm_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$brainstorm_painel_slideshow);
		elseif ($brainstormjeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$brainstormjeto_viabilidade);
		elseif ($brainstormjeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$brainstormjeto_abertura);
		elseif ($brainstorm_plano_gestao) $sql->adOnde('pg_id = '.(int)$brainstorm_plano_gestao);
		elseif ($brainstorm_ssti) $sql->adOnde('ssti_id = '.(int)$brainstorm_ssti);
		elseif ($brainstorm_laudo) $sql->adOnde('laudo_id = '.(int)$brainstorm_laudo);
		elseif ($brainstorm_trelo) $sql->adOnde('trelo_id = '.(int)$brainstorm_trelo);
		elseif ($brainstorm_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$brainstorm_trelo_cartao);
		elseif ($brainstorm_pdcl) $sql->adOnde('pdcl_id = '.(int)$brainstorm_pdcl);
		elseif ($brainstorm_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$brainstorm_pdcl_item);
		
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}

if($brainstorm_id && !(permiteEditarBrainstorm($obj->brainstorm_acesso,$brainstorm_id) && $Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'brainstorm'))) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$brainstorm_id && !$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'brainstorm'))$Aplic->redirecionar('m=publico&a=acesso_negado');



$sql = new BDConsulta();

$botoesTitulo = new CBlocoTitulo(($brainstorm_id ? 'Editar' : 'Criar').' Brainstorm', 'brainstorm.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
$cias_selecionadas = array();
$usuarios_selecionados =array();
$depts_selecionados=array();
if ($brainstorm_id) {
	$sql->adTabela('brainstorm_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('brainstorm_id = '.(int)$brainstorm_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('brainstorm_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('brainstorm_id = '.(int)$brainstorm_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('brainstorm_cia');
		$sql->adCampo('brainstorm_cia_cia');
		$sql->adOnde('brainstorm_cia_brainstorm = '.(int)$brainstorm_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}

	}

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="brainstorm_fazer_sql" />';
echo '<input type="hidden" name="brainstorm_id" id="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input name="brainstorm_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="brainstorm_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
$uuid=($brainstorm_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';
echo '<input name="brainstorm_cias"  id="brainstorm_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';


echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';


echo '<input type="hidden" name="brainstorm_objeto" id="brainstorm_objeto" value="" />';







echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right" width="100">'.dica('Nome', 'Todo brainstorm necessita ter um nome para identificação.').'Nome:'.dicaF().'</td><td><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.$obj->brainstorm_nome.'" style="width:400px;" class="texto" />*</td></tr>';
echo '<tr><td align=right style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' do brainstorm.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'brainstorm_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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

if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por este brainstorm.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="brainstorm_dept" id="brainstorm_dept" value="'.($brainstorm_id ? $obj->brainstorm_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($brainstorm_id ? $obj->brainstorm_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:400px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Responsável', 'Todo brainstorm deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="brainstorm_responsavel" name="brainstorm_responsavel" value="'.($obj->brainstorm_responsavel ? $obj->brainstorm_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->brainstorm_responsavel ? $obj->brainstorm_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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



if ($brainstorm_tarefa) $tipo='tarefa';
elseif ($brainstormjeto) $tipo='projeto';
elseif ($brainstorm_perspectiva) $tipo='perspectiva';
elseif ($brainstorm_tema) $tipo='tema';
elseif ($brainstorm_objetivo) $tipo='objetivo';
elseif ($brainstorm_fator) $tipo='fator';
elseif ($brainstorm_estrategia) $tipo='estrategia';
elseif ($brainstorm_meta) $tipo='meta';
elseif ($brainstorm_pratica) $tipo='pratica';
elseif ($brainstorm_acao) $tipo='acao';
elseif ($brainstorm_canvas) $tipo='canvas';
elseif ($brainstorm_risco) $tipo='risco';
elseif ($brainstorm_risco_resposta) $tipo='risco_resposta';
elseif ($brainstorm_indicador) $tipo='brainstorm_indicador';
elseif ($brainstorm_calendario) $tipo='calendario';
elseif ($brainstorm_monitoramento) $tipo='monitoramento';
elseif ($brainstorm_ata) $tipo='ata';
elseif ($brainstorm_mswot) $tipo='mswot';
elseif ($brainstorm_swot) $tipo='swot';
elseif ($brainstorm_operativo) $tipo='operativo';
elseif ($brainstorm_instrumento) $tipo='instrumento';
elseif ($brainstorm_recurso) $tipo='recurso';
elseif ($brainstormblema) $tipo='problema';
elseif ($brainstorm_demanda) $tipo='demanda';
elseif ($brainstormgrama) $tipo='programa';
elseif ($brainstorm_licao) $tipo='licao';
elseif ($brainstorm_evento) $tipo='evento';
elseif ($brainstorm_link) $tipo='link';
elseif ($brainstorm_avaliacao) $tipo='avaliacao';
elseif ($brainstorm_tgn) $tipo='tgn';
elseif ($brainstorm_brainstorm) $tipo='brainstorm';
elseif ($brainstorm_gut) $tipo='gut';
elseif ($brainstorm_causa_efeito) $tipo='causa_efeito';
elseif ($brainstorm_arquivo) $tipo='arquivo';
elseif ($brainstorm_forum) $tipo='forum';
elseif ($brainstorm_checklist) $tipo='checklist';
elseif ($brainstorm_agenda) $tipo='agenda';
elseif ($brainstorm_agrupamento) $tipo='agrupamento';
elseif ($brainstorm_patrocinador) $tipo='patrocinador';
elseif ($brainstorm_template) $tipo='template';
elseif ($brainstorm_painel) $tipo='painel';
elseif ($brainstorm_painel_odometro) $tipo='painel_odometro';
elseif ($brainstorm_painel_composicao) $tipo='painel_composicao';
elseif ($brainstorm_tr) $tipo='tr';
elseif ($brainstorm_me) $tipo='me';
elseif ($brainstorm_acao_item) $tipo='acao_item';
elseif ($brainstorm_beneficio) $tipo='beneficio';
elseif ($brainstorm_painel_slideshow) $tipo='painel_slideshow';
elseif ($brainstormjeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($brainstormjeto_abertura) $tipo='projeto_abertura';
elseif ($brainstorm_plano_gestao) $tipo='plano_gestao';
elseif ($brainstorm_ssti) $tipo='ssti';
elseif ($brainstorm_laudo) $tipo='laudo';
elseif ($brainstorm_trelo) $tipo='trelo';
elseif ($brainstorm_trelo_cartao) $tipo='trelo_cartao';
elseif ($brainstorm_pdcl) $tipo='pdcl';
elseif ($brainstorm_pdcl_item) $tipo='pdcl_item';	
else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado', 'A que área o brainstorm está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($brainstormjeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstormjeto" value="'.$brainstormjeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($brainstormjeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_tarefa" value="'.$brainstorm_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($brainstorm_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_perspectiva" value="'.$brainstorm_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($brainstorm_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_tema" value="'.$brainstorm_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($brainstorm_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_objetivo" value="'.$brainstorm_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($brainstorm_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_fator" value="'.$brainstorm_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($brainstorm_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_estrategia" value="'.$brainstorm_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($brainstorm_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_meta" value="'.$brainstorm_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($brainstorm_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_pratica" value="'.$brainstorm_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($brainstorm_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_acao" value="'.$brainstorm_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($brainstorm_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_canvas" value="'.$brainstorm_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($brainstorm_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_risco" value="'.$brainstorm_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($brainstorm_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_risco_resposta" value="'.$brainstorm_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($brainstorm_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_indicador" value="'.$brainstorm_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($brainstorm_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_calendario" value="'.$brainstorm_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($brainstorm_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_monitoramento" value="'.$brainstorm_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($brainstorm_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reunião', 'Caso seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_ata" value="'.(isset($brainstorm_ata) ? $brainstorm_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($brainstorm_ata) ? $brainstorm_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('icones/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja específico de uma matriz SWOT neste campo deverá constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_mswot" value="'.(isset($brainstorm_mswot) ? $brainstorm_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($brainstorm_mswot) ? $brainstorm_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ícone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja específico de um campo de matriz SWOT neste campo deverá constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_swot" value="'.(isset($brainstorm_swot) ? $brainstorm_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($brainstorm_swot) ? $brainstorm_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_operativo" value="'.$brainstorm_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($brainstorm_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_instrumento" value="'.$brainstorm_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($brainstorm_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_recurso" value="'.$brainstorm_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($brainstorm_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstormblema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstormblema" value="'.$brainstormblema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($brainstormblema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_demanda" value="'.$brainstorm_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($brainstorm_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstormgrama ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstormgrama" value="'.$brainstormgrama.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($brainstormgrama).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja específico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo deverá constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_licao" value="'.$brainstorm_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($brainstorm_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_evento" value="'.$brainstorm_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($brainstorm_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_link" value="'.$brainstorm_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($brainstorm_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avaliação', 'Caso seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_avaliacao" value="'.$brainstorm_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($brainstorm_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_tgn" value="'.$brainstorm_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($brainstorm_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_brainstorm" value="'.$brainstorm_brainstorm.'" /><input type="text" id="gestao_brainstorm_nome" name="gestao_brainstorm_nome" value="'.nome_brainstorm($brainstorm_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja específico de uma matriz GUT, neste campo deverá constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_gut" value="'.$brainstorm_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($brainstorm_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_causa_efeito" value="'.$brainstorm_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($brainstorm_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_arquivo" value="'.$brainstorm_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($brainstorm_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('Fórum', 'Caso seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_forum" value="'.$brainstorm_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($brainstorm_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja específico de um checklist, neste campo deverá constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_checklist" value="'.$brainstorm_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($brainstorm_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_agenda" value="'.$brainstorm_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($brainstorm_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_agrupamento" value="'.$brainstorm_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($brainstorm_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja específico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo deverá constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_patrocinador" value="'.$brainstorm_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($brainstorm_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_template" value="'.$brainstorm_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($brainstorm_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_painel" value="'.$brainstorm_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($brainstorm_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Odômetro de Indicador', 'Caso seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_painel_odometro" value="'.$brainstorm_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($brainstorm_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composição de Painéis', 'Caso seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_painel_composicao" value="'.$brainstorm_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($brainstorm_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/composicao_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_tr" value="'.$brainstorm_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($brainstorm_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_me" value="'.$brainstorm_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($brainstorm_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja específico de um item de '.$config['acao'].', neste campo deverá constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_acao_item" value="'.$brainstorm_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($brainstorm_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_beneficio" value="'.$brainstorm_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($brainstorm_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composições', 'Caso seja específico de um slideshow de composições, neste campo deverá constar o nome do slideshow de composições.').'Slideshow de composições:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_painel_slideshow" value="'.$brainstorm_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($brainstorm_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composições','Clique neste ícone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composições.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstormjeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja específico de um estudo de viabilidade, neste campo deverá constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstormjeto_viabilidade" value="'.$brainstormjeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($brainstormjeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ícone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstormjeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja específico de um termo de abertura, neste campo deverá constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstormjeto_abertura" value="'.$brainstormjeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($brainstormjeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ícone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estratégico', 'Caso seja específico de um planejamento estratégico, neste campo deverá constar o nome do planejamento estratégico.').'Planejamento estratégico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_plano_gestao" value="'.$brainstorm_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($brainstorm_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja específico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo deverá constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_ssti" value="'.$brainstorm_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($brainstorm_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ícone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja específico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo deverá constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_laudo" value="'.$brainstorm_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($brainstorm_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ícone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja específico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo deverá constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_trelo" value="'.$brainstorm_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($brainstorm_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ícone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja específico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo deverá constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_trelo_cartao" value="'.$brainstorm_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($brainstorm_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ícone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja específico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo deverá constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_pdcl" value="'.$brainstorm_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($brainstorm_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ícone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($brainstorm_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja específico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo deverá constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_pdcl_item" value="'.$brainstorm_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($brainstorm_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCLItem();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ícone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';
	

$sql->adTabela('brainstorm_gestao');
$sql->adCampo('brainstorm_gestao.*');
if ($uuid) $sql->adOnde('brainstorm_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('brainstorm_gestao_brainstorm ='.(int)$brainstorm_id);	
$sql->adOrdem('brainstorm_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['brainstorm_gestao_ordem'].', '.$gestao_data['brainstorm_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['brainstorm_gestao_ordem'].', '.$gestao_data['brainstorm_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['brainstorm_gestao_ordem'].', '.$gestao_data['brainstorm_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['brainstorm_gestao_ordem'].', '.$gestao_data['brainstorm_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['brainstorm_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['brainstorm_gestao_tarefa']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['brainstorm_gestao_projeto']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['brainstorm_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['brainstorm_gestao_tema']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['brainstorm_gestao_objetivo']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['brainstorm_gestao_fator']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['brainstorm_gestao_estrategia']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['brainstorm_gestao_meta']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['brainstorm_gestao_pratica']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['brainstorm_gestao_acao']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['brainstorm_gestao_canvas']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['brainstorm_gestao_risco']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['brainstorm_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['brainstorm_gestao_indicador']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['brainstorm_gestao_calendario']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['brainstorm_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['brainstorm_gestao_ata']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['brainstorm_gestao_mswot']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['brainstorm_gestao_swot']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['brainstorm_gestao_operativo']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['brainstorm_gestao_instrumento']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['brainstorm_gestao_recurso']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['brainstorm_gestao_problema']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['brainstorm_gestao_demanda']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['brainstorm_gestao_programa']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['brainstorm_gestao_licao']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['brainstorm_gestao_evento']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['brainstorm_gestao_link']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['brainstorm_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['brainstorm_gestao_tgn']).'</td>';
	
	elseif ($gestao_data['brainstorm_gestao_semelhante']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['brainstorm_gestao_semelhante']).'</td>';
	
	elseif ($gestao_data['brainstorm_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['brainstorm_gestao_gut']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['brainstorm_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['brainstorm_gestao_arquivo']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['brainstorm_gestao_forum']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['brainstorm_gestao_checklist']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['brainstorm_gestao_agenda']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['brainstorm_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['brainstorm_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['brainstorm_gestao_template']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['brainstorm_gestao_painel']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['brainstorm_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['brainstorm_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['brainstorm_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['brainstorm_gestao_tr']).'</td>';	
	elseif ($gestao_data['brainstorm_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['brainstorm_gestao_me']).'</td>';	
	elseif ($gestao_data['brainstorm_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['brainstorm_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['brainstorm_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['brainstorm_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['brainstorm_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['brainstorm_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['brainstorm_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['brainstorm_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['brainstorm_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['brainstorm_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['brainstorm_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['brainstorm_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['brainstorm_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['brainstorm_gestao_ssti']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['brainstorm_gestao_laudo']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['brainstorm_gestao_trelo']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['brainstorm_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['brainstorm_gestao_pdcl']).'</td>';
	elseif ($gestao_data['brainstorm_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['brainstorm_gestao_pdcl_item']).'</td>';
	
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['brainstorm_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';


$data_inicio = intval($obj->brainstorm_data) ? new CData($obj->brainstorm_data) :  new CData(date("Y-m-d H:i:s"));
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data', 'Digite ou escolha no calendário a data.').'Data:'.dicaF().'</td><td style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="brainstorm_data" id="brainstorm_data" value="'.($data_inicio ? $data_inicio->format("%Y-%m-%d") : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'brainstorm_data\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data de Início', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data provável de início.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calendário" />'.dicaF().'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Descrição', 'O relato detalhado do brainstorm.').'Descrição:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="brainstorm_descricao" id="brainstorm_descricao" style="width:750px;" class="textarea">'.$obj->brainstorm_descricao.'</textarea></td></tr>';


if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_brainstorm = '.(int)$brainstorm_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'brainstorm_principal_indicador', 'class="texto" style="width:400px;"', $obj->brainstorm_principal_indicador).'</td></tr>';
	}
	
if ($Aplic->profissional && $exibir['moeda']){
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda padrão utilizada.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'brainstorm_moeda', 'class=texto size=1', ($obj->brainstorm_moeda ? $obj->brainstorm_moeda : 1)).'</td></tr>';
	}	
else echo '<input type="hidden" name="brainstorm_moeda" id="brainstorm_moeda" value="'.($obj->brainstorm_moeda ? $obj->brainstorm_moeda : 1).'" />';
	
	
	
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nível de Acesso', 'Pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o brainstorm.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designados para o brainstorm podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável e os designados para o brainstorm ver e editar o brainstorm</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável e os designados para o brainstorm podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($brainstorm_acesso, 'brainstorm_acesso', 'class="texto"', ($brainstorm_id ? $obj->brainstorm_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milhões possíveis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inserção do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="brainstorm_cor" value="'.($obj->brainstorm_cor ? $obj->brainstorm_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o brainstorm ainda esteja ativa deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="brainstorm_ativo" '.($obj->brainstorm_ativo || !$brainstorm_id ? 'checked="checked"' : '').' /></td></tr>';


$campos_customizados = new CampoCustomizados('brainstorm', $brainstorm_id, 'editar');
$campos_customizados->imprimirHTML();



require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";
$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;
$arvore->EditNodeEnable = true;
$arvore->DragAndDropEnable=true;
$arvore->multipleSelectEnable = true;
$vetor=array();
$vetor_utf=mjson_decode($obj->brainstorm_objeto);
$i=0;
foreach((array)$vetor_utf as $campo){
	foreach($campo as $chave => $valor) $vetor[$i][$chave]=($chave!='obs' ? utf8_decode($valor) : $valor);
	$i++;
	}
if (!$obj->brainstorm_objeto){
	$root = $arvore->getRootNode();
	$root->text = "Ideia central";
	$root->expand=true;
	$root->image="ball_glass_redS.gif";
	}
else{
	$root = $arvore->getRootNode();
	$root->text=(isset($vetor[0]) && isset($vetor[0]['texto']) ? $vetor[0]['texto'] : '');
	$root->addData("observacao", (isset($vetor[0]) && isset($vetor[0]['obs']) ? $vetor[0]['obs'] : ''));
	$root->expand=true;
	$root->image="ball_glass_redS.gif";
	}
$maiorfilho=0;
$imagens=array();
foreach ($vetor as $chave => $campo){
	if($chave>0){
		$imagens[$campo['filho']]=(isset($imagens[$campo['pai']])&& $imagens[$campo['pai']]=='ball_glass_greenS.gif' ? 'ball_glass_redS.gif' : 'ball_glass_greenS.gif');
		if (substr($campo['filho'],0,6)=='nodulo' && (int)substr($campo['filho'],6)> $maiorfilho) $maiorfilho=(int)substr($campo['filho'],6);
		$nodulo=$arvore->Add($campo['pai'],$campo['filho'],$campo['texto'],false,$imagens[$campo['filho']]);
		if ($campo['obs']) $nodulo->addData("observacao", $campo['obs']);
		}
	}
echo '<script>var _count = '.$maiorfilho.';</script>';
//nova tabela
echo '<tr><td></td><td colspan=20 class="realce"><table id="geral" width="100%" cellspacing="0" cellpadding="0" style="display:">';
echo '<tr><td colspan=20><table align="left" cellspacing=0 cellpadding=0 id="botaos_criar" style="display:">';
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","adicionar",dica('Adicionar','Clique neste botão para adicionar nódulo.').'adicionar'.dicaF(), "javascript: void(0);' onclick='adicionar();");
	$km->Add("root","editar",dica('Editar','Clique neste botão para editar um nódulo').'editar'.dicaF(), "javascript: void(0);' onclick='editar();");
	$km->Add("root","remover",dica('Remover','Clique neste botão para remover um nódulo').'remover'.dicaF(), "javascript: void(0);' onclick='remover_nodulos_selecionados();");
	echo $km->Render();
	echo '</td></tr></table></td></tr>';

echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr>';
echo '<tr><td colspan=20><div id="observacao"></div></td></tr>';
echo '<tr><td colspan=20><table id="inserir" style="display:" cellspacing=0 cellpadding=0>';
	echo '<tr id="inserir_nodulo" style="display:none"><td><table cellspacing=0 cellpadding=0>';
		echo '<tr><td align="right"></td><td align="left"><b>Nódulo</b></td></tr>';
		echo '<tr><td align="right">texto:</td><td align="left"><input class="texto" id="nome" name="nome" type="text" style="width:300px;"/></td></tr>';
		echo '<tr><td align="right">Obs:</td><td align="left"><textarea data-gpweb-cmp="ckeditor" name="campo_observacao" id="campo_observacao" cols="60" rows="2" class="textarea"></textarea></td></tr>';
		echo '<tr id="confirmar_adicao" style="display:none"><td colspan=2><table width=100%><tr><td>'.botao('confirmar','Confirmar','Confirmar a adição do nódulo','','adicionar_nodulo();').'</td><td align=right>'.botao('cancelar','Cancelar','Cancelar a adição do nódulo','','esconder();').'</td></tr></table></td></tr>';
		echo '<tr id="confirmar_alteracao" style="display:none"><td colspan=2><table width=100%><tr><td style="width:100px;"></td><td>'.botao('confirmar','Confirmar','Confirmar a alteração do nódulo','','alterar_nodulo(); atualizar_obs();').'</td><td align=right>'.botao('cancelar','Cancelar','Cancelar a alteração do nódulo','','esconder();').'</td></tr></table></td></tr>';
	echo '</table></td></tr>';
echo '</table></td></tr>';
echo '</table></td></tr>';

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/brainstorm_editar_pro.php';	

echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'area_notificar\').style.display) document.getElementById(\'area_notificar\').style.display=\'\'; else document.getElementById(\'area_notificar\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificar</b></a></td></tr>';
echo '<tr id="area_notificar" style="display:'.($Aplic->getPref('informa_aberto') ? '' : 'none').'"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';


echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($brainstorm_id > 0 ? 'modificação' : 'criação').' do brainstorm.').'Notificar:'.dicaF().'</td>';
echo '<td>';
echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por este brainstorm.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para este brainstorm.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro do brainstorm.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? ''.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';

echo '</table></fieldset></td></tr>';


echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','salvarDados();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($brainstorm_id ? 'edição' : 'criação').' do brainstorm.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script type="text/javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('brainstorm_cia').value+'&cias_id_selecionadas='+document.getElementById('brainstorm_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.brainstorm_cias.value = organizacao_id_string;
	document.getElementById('brainstorm_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('brainstorm_cias').value);
	__buildTooltip();
	}

var vetor_observacao=Array();

function nodeSelect_handle(sender,arg){
	var treenode = treeview.getNode(arg.NodeId);
	var observacao = treenode.getData("observacao");
	//sobrescreve com o dado recente
	if (vetor_observacao[arg.NodeId])observacao=vetor_observacao[arg.NodeId];
	if(observacao) document.getElementById('observacao').innerHTML = "<br><table cellspacing=3 cellpadding=3 border=1><tr><td>" + observacao + "</td></tr></table>";
	else document.getElementById('observacao').innerHTML = "";
	}

function atualizar_obs(){
	var _ids_selecionados = treeview.getSelectedIds();
	var treenode = treeview.getNode(_ids_selecionados[0]);

	var observacao = treenode.getData("observacao");
	//sobrescreve com o dado recente
	if (vetor_observacao[_ids_selecionados[0]])observacao=vetor_observacao[_ids_selecionados[0]];
	if(observacao) document.getElementById('observacao').innerHTML = "<br><table cellspacing=3 cellpadding=3 border=1><tr><td>" + observacao + "</td></tr></table>";
	else document.getElementById('observacao').innerHTML = "";
	}

function editar(){
	var _ids_selecionados = treeview.getSelectedIds();
	var treenode = treeview.getNode(_ids_selecionados[0]);
	var observacao = treenode.getData("observacao");
	//sobrescreve com o dado recente
	if (vetor_observacao[_ids_selecionados[0]])observacao=vetor_observacao[_ids_selecionados[0]];
	env.nome.value=treenode.getText();
	CKEDITOR.instances['campo_observacao'].setData(observacao);
	document.getElementById('inserir_nodulo').style.display="";
	document.getElementById('confirmar_alteracao').style.display="";
	}


function alterar_nodulo(){
	var _ids_selecionados = treeview.getSelectedIds();
	var treenode = treeview.getNode(_ids_selecionados[0]);
	var nome=env.nome.value;
	var observacao=CKEDITOR.instances['campo_observacao'].getData();
	
	if (nome!=""){
		treenode.setText(nome);
		if (observacao)	vetor_observacao[_ids_selecionados[0]]=observacao;
		}
	else alert('Necessário ter um texto!');
	esconder();
	}


//poder mostrar dados extras ao selecionar nodulo
treeview.registerEvent("OnSelect",nodeSelect_handle);

function adicionar(){
	document.getElementById("botaos_criar").style.display="none";
	env.nome.value="";
	CKEDITOR.instances['campo_observacao'].setData('');
	document.getElementById("inserir").style.display="";
	document.getElementById("inserir_nodulo").style.display="";
	document.getElementById('confirmar_adicao').style.display="";
	}

function esconder(){
	document.getElementById("botaos_criar").style.display="";
	env.nome.value="";
	CKEDITOR.instances['campo_observacao'].setData('');
	document.getElementById("inserir_nodulo").style.display="none";
	document.getElementById('confirmar_adicao').style.display="none";
	document.getElementById('confirmar_alteracao').style.display="none";
	}

function expandir_nodulos_selecionados(){
	var _ids_selecionados = treeview.getSelectedIds();
	for(var i=0;i<_ids_selecionados.length;i++){
		var _tree_node = treeview.getNode(_ids_selecionados[i]);
		if (_tree_node.isExpanded()){
			_tree_node.collapse();
			}
		else{
			_tree_node.expand();
			}
		}
	}

function expandir_nodulos(){
	treeview.expandAll();
	}

//Remove todos os nodulos selecionados
function remover_nodulos_selecionados(){
	var _ids_selecionados = treeview.getSelectedIds();
	for(var i=0;i<_ids_selecionados.length;i++){
		treeview.removeNode(_ids_selecionados[i]);
		}
	}

function adicionar_nodulo(){
	var _node_name=env.nome.value;
	var _observacao=CKEDITOR.instances['campo_observacao'].getData();
	if (_node_name!=""){
		//pega lista de nodulos selecionados
		var _ids_selecionados = treeview.getSelectedIds();
		//se nenhum nodulo selecionado, adiciona a treeview.root
		if (_ids_selecionados.length==0){
			_count++;
			var nodulo=treeview.getNode("treeview.root").addChildNode("nodulo"+_count,_node_name,"lib/coolcss/CoolControls/CoolTreeView/icons/ball_glass_greenS.gif");
			}
		else{
			//se existe nodulos selecionados, adicionar o novo neles
			for(var i=0;i<_ids_selecionados.length;i++){
				_count++;
				treeview.getNode(_ids_selecionados[i]).addChildNode("nodulo"+_count,_node_name, "lib/coolcss/CoolControls/CoolTreeView/icons/"+(verde_vermelho(_ids_selecionados[i])? 'ball_glass_redS.gif': 'ball_glass_greenS.gif'));
				vetor_observacao["nodulo"+_count]=_observacao;
				}
			}
		}
	esconder();
	}

function deselecionar_todos_nodulos(){
	treeview.unselectAll();
	}


var objeto_passar=[];
var s='';
var i=0;


function verde_vermelho(nodulo){
	var qnt=0;
	var atual = treeview.getNode(nodulo);

	while(atual.NodeId !='treeview.root'){
		qnt++;
		nodulo = treeview.getNode(nodulo).getParentId();
		atual = treeview.getNode(nodulo);
		}
	return (qnt%2);
	}

function dados_filhos(pai){
	var ids_filhos = treeview.getNode(pai).getChildIds();

	for(var i=0;i<ids_filhos.length;i++) {
		if (pai=='treeview.root') pai='root';
		objeto_passar.push({
            "pai" : pai,
            "filho" : ids_filhos[i],
            "texto" : treeview.getNode(ids_filhos[i]).getText(),
            "obs" : (vetor_observacao[ids_filhos[i]] ? vetor_observacao[ids_filhos[i]] : treeview.getNode(ids_filhos[i]).getData('observacao'))
            });
		dados_filhos(ids_filhos[i]);
		}
	}

function salvar_brainstorm(){

	objeto_passar.push({
        "pai" : "root",
        "filho" : "root",
        "texto" : treeview.getNode('treeview.root').getText(),
        "obs" : (vetor_observacao['treeview.root'] ? vetor_observacao['treeview.root'] : treeview.getNode('treeview.root').getData('observacao'))
        });
	dados_filhos('treeview.root');
	document.env.brainstorm_objeto.value=JSON.stringify(objeto_passar);
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('brainstorm_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('brainstorm_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.brainstorm_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}

var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('brainstorm_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('brainstorm_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.brainstorm_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('brainstorm_dept').value+'&cia_id='+document.getElementById('brainstorm_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('brainstorm_dept').value+'&cia_id='+document.getElementById('brainstorm_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('brainstorm_cia').value=cia_id;
	document.getElementById('brainstorm_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('brainstorm_cia').value+'&usuario_id='+document.getElementById('brainstorm_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('brainstorm_cia').value+'&usuario_id='+document.getElementById('brainstorm_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('brainstorm_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function salvarDados(){
	if (document.getElementById('brainstorm_nome').value.length < 3){
		alert('Necessita de um nome válido.');
		brainstorm_nome.focus();
		return;
		}
	salvar_brainstorm();
	env.submit();
	}


function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('brainstorm_cia').value,'brainstorm_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}


function mostrar_proxima(){
	if (document.getElementById('tem_proxima').checked) {
		document.getElementById('ver_proxima').style.display='';
		document.getElementById('ver_local_proxima').style.display='';
		}
	else {
		document.getElementById('ver_proxima').style.display='none';
		document.getElementById('ver_local_proxima').style.display='none';
		}
	}


function mostrar(){
	if (document.getElementById('ver_brainstorm_entrega').style.display=='none') {
		document.getElementById('ver_brainstorm_entrega').style.display='';
		}
	else {
		document.getElementById('ver_brainstorm_entrega').style.display='none';
		}
	}



var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "brainstorm_data",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("brainstorm_data").value = Calendario.printDate(date, "%Y-%m-%d");
	    }
		cal1.hide();
		}
	});


function setData( frm_nome, f_data,  f_data_real){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
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


function popCor(){
	parent.gpwebApp.popUp("Cor", 300, 300, 'm=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor', window.setCor, window);
	}



function setCor(cor) {
	var f = document.env;
	if (cor) f.brainstorm_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.brainstorm_cor.value;
	}

function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');

	parent.gpwebApp.popUp("Contatos", 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
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

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir?")) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_ata';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}


//assinaturas
function mudar_posicao_assinatura(ordem, assinatura_id, direcao){
	xajax_mudar_posicao_assinatura(ordem, assinatura_id, direcao, document.getElementById('brainstorm_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function editar_assinatura(assinatura_id){
	xajax_editar_assinatura(assinatura_id);
	document.getElementById('adicionar_assinatura').style.display="none";
	document.getElementById('confirmar_assinatura').style.display="";
	}

function incluir_assinatura(){
	if (document.getElementById('integrante_id').value > 0){
		xajax_incluir_assinatura(
		document.getElementById('brainstorm_id').value, 
		document.getElementById('uuid').value, 
		document.getElementById('assinatura_id').value,
		document.getElementById('integrante_id').value, 
		document.getElementById('assinatura_funcao').value, 
		document.getElementById('assinatura_atesta').value, 
		document.getElementById('assinatura_aprova').value
		);
		
		document.getElementById('assinatura_id').value=null;
		document.getElementById('integrante_id').value=null;
		document.getElementById('assinatura_funcao').value='';
		document.getElementById('nome_assinatura').value='';
		document.getElementById('assinatura_atesta').value='';
		document.getElementById('adicionar_assinatura').style.display='';
		document.getElementById('confirmar_assinatura').style.display='none';
		__buildTooltip();
		}
	else alert('Escolha um <?php echo ucfirst($config["usuario"])?>.');
	}

function excluir_assinatura(assinatura_id){
	xajax_excluir_assinatura(assinatura_id, document.getElementById('brainstorm_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function popAssinatura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['usuario'])?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setIntegrante&cia_id='+document.getElementById('brainstorm_cia').value+'&usuario_id='+document.getElementById('integrante_id').value, window.setAssinatura, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAssinatura&cia_id='+document.getElementById('brainstorm_cia').value+'&usuario_id='+document.getElementById('integrante_id').value, "<?php echo ucfirst($config['usuario'])?>",'height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAssinatura(usuario_id, posto, nome, funcao, campo, nome_cia, assinatura_atesta){
	document.getElementById('integrante_id').value=usuario_id;
	document.getElementById('nome_assinatura').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}



function	mudar_priorizacao(priorizacao_modelo_id, valor){
	xajax_mudar_priorizacao(document.getElementById('brainstorm_id').value, priorizacao_modelo_id, valor, document.getElementById('uuid').value);
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
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('brainstorm_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('brainstorm_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.brainstorm_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('brainstorm_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.brainstorm_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('brainstorm_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('brainstorm_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.brainstorm_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('brainstorm_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.brainstormjeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('brainstorm_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.brainstorm_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('brainstorm_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.brainstorm_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('brainstorm_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.brainstorm_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('brainstorm_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.brainstorm_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('brainstorm_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.brainstorm_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('brainstorm_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.brainstorm_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('brainstorm_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.brainstorm_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('brainstorm_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.brainstorm_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('brainstorm_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('brainstorm_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.brainstorm_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('brainstorm_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.brainstorm_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('brainstorm_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.brainstorm_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('brainstorm_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.brainstorm_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('brainstorm_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.brainstorm_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('brainstorm_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('brainstorm_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.brainstorm_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('brainstorm_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('brainstorm_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.brainstorm_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('brainstorm_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.brainstorm_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('brainstorm_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.brainstorm_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Camçpo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('brainstorm_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.brainstorm_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('brainstorm_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('brainstorm_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.brainstorm_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('brainstorm_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('brainstorm_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.brainstorm_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('brainstorm_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('brainstorm_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.brainstorm_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('brainstorm_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.brainstormblema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('brainstorm_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('brainstorm_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.brainstorm_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('brainstorm_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.brainstormgrama.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('brainstorm_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.brainstorm_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('brainstorm_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('brainstorm_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.brainstorm_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('brainstorm_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('brainstorm_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.brainstorm_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('brainstorm_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('brainstorm_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.brainstorm_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('brainstorm_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.brainstorm_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('brainstorm_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('brainstorm_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.brainstorm_brainstorm.value = chave;
	document.env.gestao_brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('brainstorm_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('brainstorm_cia').value, 'Matriz GUT','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.brainstorm_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('brainstorm_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('brainstorm_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.brainstorm_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('brainstorm_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('brainstorm_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.brainstorm_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('brainstorm_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('brainstorm_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.brainstorm_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('brainstorm_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('brainstorm_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.brainstorm_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('brainstorm_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('brainstorm_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.brainstorm_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('brainstorm_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('brainstorm_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.brainstorm_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('brainstorm_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('brainstorm_cia').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.brainstorm_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('brainstorm_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('brainstorm_cia').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.brainstorm_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('brainstorm_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.brainstorm_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('brainstorm_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.brainstorm_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('brainstorm_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('brainstorm_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.brainstorm_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('brainstorm_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.brainstorm_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('brainstorm_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('brainstorm_cia').value, 'Slideshow de Composições','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.brainstorm_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('brainstorm_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('brainstorm_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.brainstormjeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('brainstorm_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('brainstorm_cia').value, 'Termo de Abertura','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.brainstormjeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('brainstorm_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('brainstorm_cia').value, 'Planejamento Estratégico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.brainstorm_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

	
function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('brainstorm_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.brainstorm_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('brainstorm_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.brainstorm_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('brainstorm_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.brainstorm_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('brainstorm_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.brainstorm_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('brainstorm_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.brainstorm_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function popPDCLItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('brainstorm_cia').value, window.setPDCLItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('brainstorm_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCLItem(chave, valor){
	limpar_tudo();
	document.env.brainstorm_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.brainstormjeto.value = null;
	document.env.brainstorm_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.brainstorm_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.brainstorm_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.brainstorm_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.brainstorm_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.brainstorm_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.brainstorm_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.brainstorm_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.brainstorm_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.brainstorm_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.brainstorm_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.brainstorm_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.brainstorm_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.brainstorm_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.brainstorm_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.brainstorm_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.brainstorm_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.brainstorm_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.brainstorm_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.brainstorm_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.brainstorm_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.brainstormblema.value = null;
	document.env.problema_nome.value = '';
	document.env.brainstorm_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.brainstormgrama.value = null;
	document.env.programa_nome.value = '';
	document.env.brainstorm_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.brainstorm_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.brainstorm_link.value = null;
	document.env.link_nome.value = '';
	document.env.brainstorm_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.brainstorm_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.brainstorm_brainstorm.value = null;
	document.env.gestao_brainstorm_nome.value = '';
	document.env.brainstorm_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.brainstorm_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.brainstorm_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.brainstorm_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.brainstorm_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.brainstorm_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.brainstorm_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.brainstorm_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.brainstorm_template.value = null;
	document.env.template_nome.value = '';
	document.env.brainstorm_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.brainstorm_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.brainstorm_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.brainstorm_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.brainstorm_me.value = null;
	document.env.me_nome.value = '';
	document.env.brainstorm_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.brainstorm_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.brainstorm_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.brainstormjeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.brainstormjeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.brainstorm_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.brainstorm_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.brainstorm_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.brainstorm_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.brainstorm_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.brainstorm_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.brainstorm_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';			
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('brainstorm_id').value,
	document.getElementById('uuid').value,
	f.brainstormjeto.value,
	f.brainstorm_tarefa.value,
	f.brainstorm_perspectiva.value,
	f.brainstorm_tema.value,
	f.brainstorm_objetivo.value,
	f.brainstorm_fator.value,
	f.brainstorm_estrategia.value,
	f.brainstorm_meta.value,
	f.brainstorm_pratica.value,
	f.brainstorm_acao.value,
	f.brainstorm_canvas.value,
	f.brainstorm_risco.value,
	f.brainstorm_risco_resposta.value,
	f.brainstorm_indicador.value,
	f.brainstorm_calendario.value,
	f.brainstorm_monitoramento.value,
	f.brainstorm_ata.value,
	f.brainstorm_mswot.value,
	f.brainstorm_swot.value,
	f.brainstorm_operativo.value,
	f.brainstorm_instrumento.value,
	f.brainstorm_recurso.value,
	f.brainstormblema.value,
	f.brainstorm_demanda.value,
	f.brainstormgrama.value,
	f.brainstorm_licao.value,
	f.brainstorm_evento.value,
	f.brainstorm_link.value,
	f.brainstorm_avaliacao.value,
	f.brainstorm_tgn.value,
	f.brainstorm_brainstorm.value,
	f.brainstorm_gut.value,
	f.brainstorm_causa_efeito.value,
	f.brainstorm_arquivo.value,
	f.brainstorm_forum.value,
	f.brainstorm_checklist.value,
	f.brainstorm_agenda.value,
	f.brainstorm_agrupamento.value,
	f.brainstorm_patrocinador.value,
	f.brainstorm_template.value,
	f.brainstorm_painel.value,
	f.brainstorm_painel_odometro.value,
	f.brainstorm_painel_composicao.value,
	f.brainstorm_tr.value,
	f.brainstorm_me.value,
	f.brainstorm_acao_item.value,
	f.brainstorm_beneficio.value,
	f.brainstorm_painel_slideshow.value,
	f.brainstormjeto_viabilidade.value,
	f.brainstormjeto_abertura.value,
	f.brainstorm_plano_gestao.value,
	f.brainstorm_ssti.value,
	f.brainstorm_laudo.value,
	f.brainstorm_trelo.value,
	f.brainstorm_trelo_cartao.value,
	f.brainstorm_pdcl.value,
	f.brainstorm_pdcl_item.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(brainstorm_gestao_id){
	xajax_excluir_gestao(document.getElementById('brainstorm_id').value, document.getElementById('uuid').value, brainstorm_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, brainstorm_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, brainstorm_gestao_id, direcao, document.getElementById('brainstorm_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$brainstorm_id && (
	$brainstorm_tarefa || 
	$brainstormjeto || 
	$brainstorm_perspectiva || 
	$brainstorm_tema || 
	$brainstorm_objetivo || 
	$brainstorm_fator || 
	$brainstorm_estrategia || 
	$brainstorm_meta || 
	$brainstorm_pratica || 
	$brainstorm_acao || 
	$brainstorm_canvas || 
	$brainstorm_risco || 
	$brainstorm_risco_resposta || 
	$brainstorm_indicador || 
	$brainstorm_calendario || 
	$brainstorm_monitoramento || 
	$brainstorm_ata || 
	$brainstorm_mswot || 
	$brainstorm_swot || 
	$brainstorm_operativo || 
	$brainstorm_instrumento || 
	$brainstorm_recurso || 
	$brainstormblema || 
	$brainstorm_demanda || 
	$brainstormgrama || 
	$brainstorm_licao || 
	$brainstorm_evento || 
	$brainstorm_link || 
	$brainstorm_avaliacao || 
	$brainstorm_tgn || 
	$brainstorm_brainstorm || 
	$brainstorm_gut || 
	$brainstorm_causa_efeito || 
	$brainstorm_arquivo || 
	$brainstorm_forum || 
	$brainstorm_checklist || 
	$brainstorm_agenda || 
	$brainstorm_agrupamento || 
	$brainstorm_patrocinador || 
	$brainstorm_template || 
	$brainstorm_painel || 
	$brainstorm_painel_odometro || 
	$brainstorm_painel_composicao || 
	$brainstorm_tr || 
	$brainstorm_me || 
	$brainstorm_acao_item || 
	$brainstorm_beneficio || 
	$brainstorm_painel_slideshow || 
	$brainstormjeto_viabilidade || 
	$brainstormjeto_abertura || 
	$brainstorm_plano_gestao || 
	$brainstorm_ssti || 
	$brainstorm_laudo || 
	$brainstorm_trelo || 
	$brainstorm_trelo_cartao || 
	$brainstorm_pdcl || 
	$brainstorm_pdcl_item
	)) echo 'incluir_relacionado();';
	?>	
</script>

