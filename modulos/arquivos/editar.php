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
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$Aplic->carregarCKEditorJS();

$arquivo_pasta=getParam($_REQUEST, 'arquivo_pasta', null);

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');

$arquivo_id =getParam($_REQUEST, 'arquivo_id', null);

$arquivo_projeto=getParam($_REQUEST, 'arquivo_projeto', null);
$arquivo_tarefa=getParam($_REQUEST, 'arquivo_tarefa', null);
$arquivo_perspectiva=getParam($_REQUEST, 'arquivo_perspectiva', null);
$arquivo_tema=getParam($_REQUEST, 'arquivo_tema', null);
$arquivo_objetivo=getParam($_REQUEST, 'arquivo_objetivo', null);
$arquivo_fator=getParam($_REQUEST, 'arquivo_fator', null);
$arquivo_estrategia=getParam($_REQUEST, 'arquivo_estrategia', null);
$arquivo_meta=getParam($_REQUEST, 'arquivo_meta', null);
$arquivo_pratica=getParam($_REQUEST, 'arquivo_pratica', null);
$arquivo_acao=getParam($_REQUEST, 'arquivo_acao', null);
$arquivo_canvas=getParam($_REQUEST, 'arquivo_canvas', null);
$arquivo_risco=getParam($_REQUEST, 'arquivo_risco', null);
$arquivo_risco_resposta=getParam($_REQUEST, 'arquivo_risco_resposta', null);
$arquivo_indicador=getParam($_REQUEST, 'arquivo_indicador', null);
$arquivo_calendario=getParam($_REQUEST, 'arquivo_calendario', null);
$arquivo_monitoramento=getParam($_REQUEST, 'arquivo_monitoramento', null);
$arquivo_ata=getParam($_REQUEST, 'arquivo_ata', null);
$arquivo_mswot=getParam($_REQUEST, 'arquivo_mswot', null);
$arquivo_swot=getParam($_REQUEST, 'arquivo_swot', null);
$arquivo_operativo=getParam($_REQUEST, 'arquivo_operativo', null);
$arquivo_instrumento=getParam($_REQUEST, 'arquivo_instrumento', null);
$arquivo_recurso=getParam($_REQUEST, 'arquivo_recurso', null);
$arquivo_problema=getParam($_REQUEST, 'arquivo_problema', null);
$arquivo_demanda=getParam($_REQUEST, 'arquivo_demanda', null);
$arquivo_programa=getParam($_REQUEST, 'arquivo_programa', null);
$arquivo_licao=getParam($_REQUEST, 'arquivo_licao', null);
$arquivo_evento=getParam($_REQUEST, 'arquivo_evento', null);
$arquivo_link=getParam($_REQUEST, 'arquivo_link', null);
$arquivo_avaliacao=getParam($_REQUEST, 'arquivo_avaliacao', null);
$arquivo_tgn=getParam($_REQUEST, 'arquivo_tgn', null);
$arquivo_brainstorm=getParam($_REQUEST, 'arquivo_brainstorm', null);
$arquivo_gut=getParam($_REQUEST, 'arquivo_gut', null);
$arquivo_causa_efeito=getParam($_REQUEST, 'arquivo_causa_efeito', null);
$arquivo_arquivo=getParam($_REQUEST, 'arquivo_arquivo', null);
$arquivo_forum=getParam($_REQUEST, 'arquivo_forum', null);
$arquivo_checklist=getParam($_REQUEST, 'arquivo_checklist', null);
$arquivo_agenda=getParam($_REQUEST, 'arquivo_agenda', null);
$arquivo_agrupamento=getParam($_REQUEST, 'arquivo_agrupamento', null);
$arquivo_patrocinador=getParam($_REQUEST, 'arquivo_patrocinador', null);
$arquivo_template=getParam($_REQUEST, 'arquivo_template', null);
$arquivo_painel=getParam($_REQUEST, 'arquivo_painel', null);
$arquivo_painel_odometro=getParam($_REQUEST, 'arquivo_painel_odometro', null);
$arquivo_painel_composicao=getParam($_REQUEST, 'arquivo_painel_composicao', null);
$arquivo_tr=getParam($_REQUEST, 'arquivo_tr', null);
$arquivo_me=getParam($_REQUEST, 'arquivo_me', null);
$arquivo_acao_item=getParam($_REQUEST, 'arquivo_acao_item', null);
$arquivo_beneficio=getParam($_REQUEST, 'arquivo_beneficio', null);
$arquivo_painel_slideshow=getParam($_REQUEST, 'arquivo_painel_slideshow', null);
$arquivo_projeto_viabilidade=getParam($_REQUEST, 'arquivo_projeto_viabilidade', null);
$arquivo_projeto_abertura=getParam($_REQUEST, 'arquivo_projeto_abertura', null);
$arquivo_plano_gestao=getParam($_REQUEST, 'arquivo_plano_gestao', null);
$arquivo_ssti=getParam($_REQUEST, 'arquivo_ssti', null);
$arquivo_laudo=getParam($_REQUEST, 'arquivo_laudo', null);
$arquivo_trelo=getParam($_REQUEST, 'arquivo_trelo', null);
$arquivo_trelo_cartao=getParam($_REQUEST, 'arquivo_trelo_cartao', null);
$arquivo_pdcl=getParam($_REQUEST, 'arquivo_pdcl', null);
$arquivo_pdcl_item=getParam($_REQUEST, 'arquivo_pdcl_item', null);


$arquivo_usuario=getParam($_REQUEST, 'arquivo_usuario', null);


$ci=getParam($_REQUEST, 'ci', 0) == 1 ? true : false;

if (!$podeAdicionar && !$arquivo_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$podeEditar && $arquivo_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
$podeAdmin = $Aplic->usuario_super_admin;

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;
$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'arquivo\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();



$msg = '';
$obj = new CArquivo();
$podeExcluir = $obj->podeExcluir($msg, $arquivo_id);
if ($arquivo_id){
	$obj->load($arquivo_id);
	$cia_id=$obj->arquivo_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

	if (
		$arquivo_projeto || 
		$arquivo_tarefa || 
		$arquivo_perspectiva || 
		$arquivo_tema || 
		$arquivo_objetivo || 
		$arquivo_fator || 
		$arquivo_estrategia || 
		$arquivo_meta || 
		$arquivo_pratica || 
		$arquivo_acao || 
		$arquivo_canvas || 
		$arquivo_risco || 
		$arquivo_risco_resposta || 
		$arquivo_indicador || 
		$arquivo_calendario || 
		$arquivo_monitoramento || 
		$arquivo_ata || 
		$arquivo_mswot || 
		$arquivo_swot || 
		$arquivo_operativo || 
		$arquivo_instrumento || 
		$arquivo_recurso || 
		$arquivo_problema || 
		$arquivo_demanda || 
		$arquivo_programa || 
		$arquivo_licao || 
		$arquivo_evento || 
		$arquivo_link || 
		$arquivo_avaliacao || 
		$arquivo_tgn || 
		$arquivo_brainstorm || 
		$arquivo_gut || 
		$arquivo_causa_efeito || 
		$arquivo_arquivo || 
		$arquivo_forum || 
		$arquivo_checklist || 
		$arquivo_agenda || 
		$arquivo_agrupamento || 
		$arquivo_patrocinador || 
		$arquivo_template || 
		$arquivo_painel || 
		$arquivo_painel_odometro || 
		$arquivo_painel_composicao || 
		$arquivo_tr || 
		$arquivo_me || 
		$arquivo_acao_item || 
		$arquivo_beneficio || 
		$arquivo_painel_slideshow || 
		$arquivo_projeto_viabilidade || 
		$arquivo_projeto_abertura || 
		$arquivo_plano_gestao|| 
		$arquivo_ssti || 
		$arquivo_laudo || 
		$arquivo_trelo || 
		$arquivo_trelo_cartao || 
		$arquivo_pdcl || 
		$arquivo_pdcl_item
		){
		$sql->adTabela('cias');
		if ($arquivo_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
		elseif ($arquivo_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
		elseif ($arquivo_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		elseif ($arquivo_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		elseif ($arquivo_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
		elseif ($arquivo_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
		elseif ($arquivo_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		elseif ($arquivo_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
		elseif ($arquivo_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		elseif ($arquivo_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		elseif ($arquivo_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
		elseif ($arquivo_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
		elseif ($arquivo_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
		elseif ($arquivo_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		elseif ($arquivo_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
		elseif ($arquivo_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
		elseif ($arquivo_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
		elseif ($arquivo_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
		elseif ($arquivo_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
		elseif ($arquivo_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
		elseif ($arquivo_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
		elseif ($arquivo_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
		elseif ($arquivo_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
		elseif ($arquivo_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
		elseif ($arquivo_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
		elseif ($arquivo_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
		elseif ($arquivo_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
		elseif ($arquivo_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
		elseif ($arquivo_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
		elseif ($arquivo_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
		elseif ($arquivo_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
		elseif ($arquivo_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
		elseif ($arquivo_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
		elseif ($arquivo_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
		elseif ($arquivo_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
		elseif ($arquivo_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
		elseif ($arquivo_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
		elseif ($arquivo_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
		elseif ($arquivo_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
		elseif ($arquivo_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
		elseif ($arquivo_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
		elseif ($arquivo_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
		elseif ($arquivo_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
		elseif ($arquivo_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
		elseif ($arquivo_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
		elseif ($arquivo_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
		elseif ($arquivo_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
		elseif ($arquivo_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
		elseif ($arquivo_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
		elseif ($arquivo_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
		elseif ($arquivo_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
		elseif ($arquivo_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
		elseif ($arquivo_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
		elseif ($arquivo_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
		elseif ($arquivo_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
		elseif ($arquivo_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
		elseif ($arquivo_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
	
		if ($arquivo_tarefa) $sql->adOnde('tarefa_id = '.(int)$arquivo_tarefa);
		elseif ($arquivo_projeto) $sql->adOnde('projeto_id = '.(int)$arquivo_projeto);
		elseif ($arquivo_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$arquivo_perspectiva);
		elseif ($arquivo_tema) $sql->adOnde('tema_id = '.(int)$arquivo_tema);
		elseif ($arquivo_objetivo) $sql->adOnde('objetivo_id = '.(int)$arquivo_objetivo);
		elseif ($arquivo_fator) $sql->adOnde('fator_id = '.(int)$arquivo_fator);
		elseif ($arquivo_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$arquivo_estrategia);
		elseif ($arquivo_meta) $sql->adOnde('pg_meta_id = '.(int)$arquivo_meta);
		elseif ($arquivo_pratica) $sql->adOnde('pratica_id = '.(int)$arquivo_pratica);
		elseif ($arquivo_acao) $sql->adOnde('plano_acao_id = '.(int)$arquivo_acao);
		elseif ($arquivo_canvas) $sql->adOnde('canvas_id = '.(int)$arquivo_canvas);
		elseif ($arquivo_risco) $sql->adOnde('risco_id = '.(int)$arquivo_risco);
		elseif ($arquivo_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$arquivo_risco_resposta);
		elseif ($arquivo_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$arquivo_indicador);
		elseif ($arquivo_calendario) $sql->adOnde('calendario_id = '.(int)$arquivo_calendario);
		elseif ($arquivo_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$arquivo_monitoramento);
		elseif ($arquivo_ata) $sql->adOnde('ata_id = '.(int)$arquivo_ata);
		elseif ($arquivo_mswot) $sql->adOnde('mswot_id = '.(int)$arquivo_mswot);
		elseif ($arquivo_swot) $sql->adOnde('swot_id = '.(int)$arquivo_swot);
		elseif ($arquivo_operativo) $sql->adOnde('operativo_id = '.(int)$arquivo_operativo);
		elseif ($arquivo_instrumento) $sql->adOnde('instrumento_id = '.(int)$arquivo_instrumento);
		elseif ($arquivo_recurso) $sql->adOnde('recurso_id = '.(int)$arquivo_recurso);
		elseif ($arquivo_problema) $sql->adOnde('problema_id = '.(int)$arquivo_problema);
		elseif ($arquivo_demanda) $sql->adOnde('demanda_id = '.(int)$arquivo_demanda);
		elseif ($arquivo_programa) $sql->adOnde('programa_id = '.(int)$arquivo_programa);
		elseif ($arquivo_licao) $sql->adOnde('licao_id = '.(int)$arquivo_licao);
		elseif ($arquivo_evento) $sql->adOnde('evento_id = '.(int)$arquivo_evento);
		elseif ($arquivo_link) $sql->adOnde('link_id = '.(int)$arquivo_link);
		elseif ($arquivo_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$arquivo_avaliacao);
		elseif ($arquivo_tgn) $sql->adOnde('tgn_id = '.(int)$arquivo_tgn);
		elseif ($arquivo_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$arquivo_brainstorm);
		elseif ($arquivo_gut) $sql->adOnde('gut_id = '.(int)$arquivo_gut);
		elseif ($arquivo_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$arquivo_causa_efeito);
		elseif ($arquivo_arquivo) $sql->adOnde('arquivo_id = '.(int)$arquivo_arquivo);
		elseif ($arquivo_forum) $sql->adOnde('forum_id = '.(int)$arquivo_forum);
		elseif ($arquivo_checklist) $sql->adOnde('checklist_id = '.(int)$arquivo_checklist);
		elseif ($arquivo_agenda) $sql->adOnde('agenda_id = '.(int)$arquivo_agenda);
		elseif ($arquivo_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$arquivo_agrupamento);
		elseif ($arquivo_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$arquivo_patrocinador);
		elseif ($arquivo_template) $sql->adOnde('template_id = '.(int)$arquivo_template);
		elseif ($arquivo_painel) $sql->adOnde('painel_id = '.(int)$arquivo_painel);
		elseif ($arquivo_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$arquivo_painel_odometro);
		elseif ($arquivo_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$arquivo_painel_composicao);
		elseif ($arquivo_tr) $sql->adOnde('tr_id = '.(int)$arquivo_tr);
		elseif ($arquivo_me) $sql->adOnde('me_id = '.(int)$arquivo_me);
		elseif ($arquivo_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$arquivo_acao_item);
		elseif ($arquivo_beneficio) $sql->adOnde('beneficio_id = '.(int)$arquivo_beneficio);
		elseif ($arquivo_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$arquivo_painel_slideshow);
		elseif ($arquivo_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$arquivo_projeto_viabilidade);
		elseif ($arquivo_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$arquivo_projeto_abertura);
		elseif ($arquivo_plano_gestao) $sql->adOnde('pg_id = '.(int)$arquivo_plano_gestao);
		elseif ($arquivo_ssti) $sql->adOnde('ssti_id = '.(int)$arquivo_ssti);
		elseif ($arquivo_laudo) $sql->adOnde('laudo_id = '.(int)$arquivo_laudo);
		elseif ($arquivo_trelo) $sql->adOnde('trelo_id = '.(int)$arquivo_trelo);
		elseif ($arquivo_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$arquivo_trelo_cartao);
		elseif ($arquivo_pdcl) $sql->adOnde('pdcl_id = '.(int)$arquivo_pdcl);
		elseif ($arquivo_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$arquivo_pdcl_item);
		
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}


$usuarios_selecionados = array();
$depts_selecionados = array();
$cias_selecionadas = array();
if ($arquivo_id) {
	$sql->adTabela('arquivo_usuario');
	$sql->adCampo('arquivo_usuario_usuario');
	$sql->adOnde('arquivo_usuario_arquivo = '.(int)$arquivo_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('arquivo_dept');
	$sql->adCampo('arquivo_dept_dept');
	$sql->adOnde('arquivo_dept_arquivo ='.(int)$arquivo_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	
		$sql->adTabela('arquivo_cia');
		$sql->adCampo('arquivo_cia_cia');
		$sql->adOnde('arquivo_cia_arquivo = '.(int)$arquivo_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	




if ($obj->arquivo_saida != $Aplic->usuario_id) $ci = false;

$ttl = $arquivo_id ? 'Editar Arquivo' : 'Adicionar Arquivo';
$ttl = $ci ? 'Arquivo na Caixa de Entrada' : $ttl;
$botoesTitulo = new CBlocoTitulo($ttl, 'arquivo.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
if ($ci) $arquivo_id = null;


if ($arquivo_projeto) $obj->arquivo_projeto=$arquivo_projeto;
if ($arquivo_tarefa) $obj->arquivo_tarefa=$arquivo_tarefa;
else if ($arquivo_pratica) $obj->arquivo_pratica=$arquivo_pratica;
else if ($arquivo_demanda) $obj->arquivo_demanda=$arquivo_demanda;
else if ($arquivo_instrumento) $obj->arquivo_instrumento=$arquivo_instrumento;
else if ($arquivo_acao) $obj->arquivo_acao=$arquivo_acao;
else if ($arquivo_indicador) $obj->arquivo_indicador=$arquivo_indicador;
else if ($arquivo_objetivo) $obj->arquivo_objetivo=$arquivo_objetivo;
else if ($arquivo_tema) $obj->arquivo_tema=$arquivo_tema;
else if ($arquivo_estrategia) $obj->arquivo_estrategia=$arquivo_estrategia;
else if ($arquivo_fator) $obj->arquivo_fator=$arquivo_fator;
else if ($arquivo_meta) $obj->arquivo_meta=$arquivo_meta;
else if ($arquivo_perspectiva) $obj->arquivo_perspectiva=$arquivo_perspectiva;
else if ($arquivo_canvas) $obj->arquivo_canvas=$arquivo_canvas;
else if ($arquivo_calendario) $obj->arquivo_calendario=$arquivo_calendario;
else if ($arquivo_ata) $obj->arquivo_ata=$arquivo_ata;
else if ($arquivo_usuario) $obj->arquivo_usuario=$arquivo_usuario;



$extra = array('onde' => 'projeto_ativo = 1');

echo '<form name="env" id="env" enctype="multipart/form-data" method="post">';
echo '<input type="hidden" name="m" value="arquivos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="arquivo_id" id="arquivo_id" value="'.$arquivo_id.'" />';

echo '<input type="hidden" name="arquivo_versao_id" value="'.$obj->arquivo_versao_id.'" />';
echo '<input type="hidden" name="arquivo_usuario_upload" id="arquivo_usuario_upload" value="'.$obj->arquivo_usuario_upload.'" />';
echo '<input type="hidden" name="chave_publica" value="'.$obj->chave_publica.'" />';
echo '<input type="hidden" name="arquivo_nome_real" value="'.$obj->arquivo_nome_real.'" />';
echo '<input type="hidden" name="arquivo_nome" value="'.$obj->arquivo_nome.'" />';
echo '<input type="hidden" name="assinatura" value="'.$obj->assinatura.'" />';
echo '<input type="hidden" name="arquivo_data" value="'.$obj->arquivo_data.'" />';
echo '<input type="hidden" name="arquivo_tipo" value="'.$obj->arquivo_tipo.'" />';
echo '<input type="hidden" name="arquivo_icone" value="'.$obj->arquivo_icone.'" />';
echo '<input type="hidden" name="arquivo_tamanho" value="'.$obj->arquivo_tamanho.'" />';
echo '<input name="arquivo_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="arquivo_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="arquivo_cias"  id="arquivo_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
$uuid=($arquivo_id ? '' : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';

echo '<input type="hidden" name="arquivo_local" id="arquivo_local" value="'.$obj->arquivo_local.'" />';


echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="std">';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Endereço do arquivo a ser enviado para o servidor'.(ini_get('upload_max_filesize') ? '</br>(tamanho máximo: '.ini_get('upload_max_filesize').')' : '').'.').'Arquivo:'.dicaF().'</td><td align="left"><input type="File" class="arquivo" name="arquivo" size="80" style="width:400px" /></td></tr>';


echo '<tr><td align=right  style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' do arquivo.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'arquivo_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

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
	//echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	


echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por este arquivo.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="arquivo_dept" id="arquivo_dept" value="'.$obj->arquivo_dept.'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($obj->arquivo_dept).'" style="width:400px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:400px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Responsável ', 'Todo arquivo deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="arquivo_dono" name="arquivo_dono" value="'.($obj->arquivo_dono ? $obj->arquivo_dono : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->arquivo_dono ? $obj->arquivo_dono : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:400px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';




echo '<tr><td align="right" style="white-space: nowrap">'.dica('Descrição', 'Ao se enviar um arquivo, pode-se escrever um texto explicativo para facilitar a compreensão do arquivo e facilitar futuras pesquisas.').'Descrição:'.dicaF().'</td><td align="left"><textarea name="arquivo_descricao" data-gpweb-cmp="ckeditor" class="textarea" rows="4" style="width:270px">'.$obj->arquivo_descricao.'</textarea></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap" width=100>'.dica('Pasta', 'A localização virtual do arquivo').'Pasta:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><input type="hidden" name="arquivo_pasta" id="arquivo_pasta" value="'.($arquivo_id ? $obj->arquivo_pasta : $arquivo_pasta).'" /><input type="text" id="pasta_nome" name="pasta_nome" value="'.nome_pasta(($arquivo_id ? $obj->arquivo_pasta : $arquivo_pasta)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPasta();">'.imagem('icones/pasta_p.png','Selecionar Pasta','Clique neste ícone '.imagem('icones/pasta_p.png').' para selecionar uma pasta onde se encontará o arquivo.').'</a></td></tr></table></td></tr>';


$tipos=array(''=>'');

$tipos['usuario']='Particular';

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
	
	
asort($tipos);


if ($arquivo_tarefa) $tipo='tarefa';
elseif ($arquivo_projeto) $tipo='projeto';
elseif ($arquivo_perspectiva) $tipo='perspectiva';
elseif ($arquivo_tema) $tipo='tema';
elseif ($arquivo_objetivo) $tipo='objetivo';
elseif ($arquivo_fator) $tipo='fator';
elseif ($arquivo_estrategia) $tipo='estrategia';
elseif ($arquivo_meta) $tipo='meta';
elseif ($arquivo_pratica) $tipo='pratica';
elseif ($arquivo_acao) $tipo='acao';
elseif ($arquivo_canvas) $tipo='canvas';
elseif ($arquivo_risco) $tipo='risco';
elseif ($arquivo_risco_resposta) $tipo='risco_resposta';
elseif ($arquivo_indicador) $tipo='arquivo_indicador';
elseif ($arquivo_calendario) $tipo='calendario';
elseif ($arquivo_monitoramento) $tipo='monitoramento';
elseif ($arquivo_ata) $tipo='ata';
elseif ($arquivo_mswot) $tipo='mswot';
elseif ($arquivo_swot) $tipo='swot';
elseif ($arquivo_operativo) $tipo='operativo';
elseif ($arquivo_instrumento) $tipo='instrumento';
elseif ($arquivo_recurso) $tipo='recurso';
elseif ($arquivo_problema) $tipo='problema';
elseif ($arquivo_demanda) $tipo='demanda';
elseif ($arquivo_programa) $tipo='programa';
elseif ($arquivo_licao) $tipo='licao';
elseif ($arquivo_evento) $tipo='evento';
elseif ($arquivo_link) $tipo='link';
elseif ($arquivo_avaliacao) $tipo='avaliacao';
elseif ($arquivo_tgn) $tipo='tgn';
elseif ($arquivo_brainstorm) $tipo='brainstorm';
elseif ($arquivo_gut) $tipo='gut';
elseif ($arquivo_causa_efeito) $tipo='causa_efeito';
elseif ($arquivo_arquivo) $tipo='arquivo';
elseif ($arquivo_forum) $tipo='forum';
elseif ($arquivo_checklist) $tipo='checklist';
elseif ($arquivo_agenda) $tipo='agenda';
elseif ($arquivo_agrupamento) $tipo='agrupamento';
elseif ($arquivo_patrocinador) $tipo='patrocinador';
elseif ($arquivo_template) $tipo='template';
elseif ($arquivo_painel) $tipo='painel';
elseif ($arquivo_painel_odometro) $tipo='painel_odometro';
elseif ($arquivo_painel_composicao) $tipo='painel_composicao';
elseif ($arquivo_tr) $tipo='tr';
elseif ($arquivo_me) $tipo='me';
elseif ($arquivo_acao_item) $tipo='acao_item';
elseif ($arquivo_beneficio) $tipo='beneficio';
elseif ($arquivo_painel_slideshow) $tipo='painel_slideshow';
elseif ($arquivo_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($arquivo_projeto_abertura) $tipo='projeto_abertura';
elseif ($arquivo_plano_gestao) $tipo='plano_gestao';
elseif ($arquivo_ssti) $tipo='ssti';
elseif ($arquivo_laudo) $tipo='laudo';
elseif ($arquivo_trelo) $tipo='trelo';
elseif ($arquivo_trelo_cartao) $tipo='trelo_cartao';
elseif ($arquivo_pdcl) $tipo='pdcl';
elseif ($arquivo_pdcl_item) $tipo='pdcl_item';	

elseif ($arquivo_usuario) $tipo='usuario';

else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado', 'A que área o arquivo está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($arquivo_usuario ? '' : 'style="display:none"').' id="usuario" ><td><input type="hidden" name="arquivo_usuario" value="'.$arquivo_usuario.'" /></td></tr>';

echo '<tr '.($arquivo_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_projeto" value="'.$arquivo_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($arquivo_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_tarefa" value="'.$arquivo_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($arquivo_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_perspectiva" value="'.$arquivo_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($arquivo_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_tema" value="'.$arquivo_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($arquivo_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_objetivo" value="'.$arquivo_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($arquivo_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_fator" value="'.$arquivo_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($arquivo_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_estrategia" value="'.$arquivo_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($arquivo_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_meta" value="'.$arquivo_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($arquivo_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_pratica" value="'.$arquivo_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($arquivo_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_acao" value="'.$arquivo_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($arquivo_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_canvas" value="'.$arquivo_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($arquivo_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_risco" value="'.$arquivo_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($arquivo_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_risco_resposta" value="'.$arquivo_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($arquivo_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_indicador" value="'.$arquivo_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($arquivo_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_calendario" value="'.$arquivo_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($arquivo_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_monitoramento" value="'.$arquivo_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($arquivo_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reunião', 'Caso seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_ata" value="'.(isset($arquivo_ata) ? $arquivo_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($arquivo_ata) ? $arquivo_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('icones/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja específico de uma matriz SWOT neste campo deverá constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_mswot" value="'.(isset($arquivo_mswot) ? $arquivo_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($arquivo_mswot) ? $arquivo_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ícone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja específico de um campo de matriz SWOT neste campo deverá constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_swot" value="'.(isset($arquivo_swot) ? $arquivo_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($arquivo_swot) ? $arquivo_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_operativo" value="'.$arquivo_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($arquivo_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_instrumento" value="'.$arquivo_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($arquivo_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_recurso" value="'.$arquivo_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($arquivo_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_problema" value="'.$arquivo_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($arquivo_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_demanda" value="'.$arquivo_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($arquivo_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_programa" value="'.$arquivo_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($arquivo_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja específico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo deverá constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_licao" value="'.$arquivo_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($arquivo_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_evento" value="'.$arquivo_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($arquivo_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_link" value="'.$arquivo_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($arquivo_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avaliação', 'Caso seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_avaliacao" value="'.$arquivo_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($arquivo_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_tgn" value="'.$arquivo_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($arquivo_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_brainstorm" value="'.$arquivo_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($arquivo_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja específico de uma matriz GUT, neste campo deverá constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_gut" value="'.$arquivo_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($arquivo_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_causa_efeito" value="'.$arquivo_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($arquivo_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_arquivo" value="'.$arquivo_arquivo.'" /><input type="text" id="gestao_arquivo_nome" name="gestao_arquivo_nome" value="'.nome_arquivo($arquivo_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('Fórum', 'Caso seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_forum" value="'.$arquivo_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($arquivo_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja específico de um checklist, neste campo deverá constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_checklist" value="'.$arquivo_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($arquivo_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_agenda" value="'.$arquivo_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($arquivo_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_agrupamento" value="'.$arquivo_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($arquivo_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja específico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo deverá constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_patrocinador" value="'.$arquivo_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($arquivo_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ícone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_template" value="'.$arquivo_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($arquivo_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_painel" value="'.$arquivo_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($arquivo_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Odômetro de Indicador', 'Caso seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_painel_odometro" value="'.$arquivo_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($arquivo_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composição de Painéis', 'Caso seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_painel_composicao" value="'.$arquivo_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($arquivo_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/composicao_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_tr" value="'.$arquivo_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($arquivo_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_me" value="'.$arquivo_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($arquivo_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja específico de um item de '.$config['acao'].', neste campo deverá constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_acao_item" value="'.$arquivo_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($arquivo_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_beneficio" value="'.$arquivo_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($arquivo_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composições', 'Caso seja específico de um slideshow de composições, neste campo deverá constar o nome do slideshow de composições.').'Slideshow de composições:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_painel_slideshow" value="'.$arquivo_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($arquivo_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composições','Clique neste ícone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composições.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja específico de um estudo de viabilidade, neste campo deverá constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_projeto_viabilidade" value="'.$arquivo_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($arquivo_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ícone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja específico de um termo de abertura, neste campo deverá constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_projeto_abertura" value="'.$arquivo_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($arquivo_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ícone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estratégico', 'Caso seja específico de um planejamento estratégico, neste campo deverá constar o nome do planejamento estratégico.').'Planejamento estratégico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_plano_gestao" value="'.$arquivo_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($arquivo_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja específico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo deverá constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_ssti" value="'.$arquivo_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($arquivo_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ícone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja específico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo deverá constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_laudo" value="'.$arquivo_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($arquivo_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ícone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja específico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo deverá constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_trelo" value="'.$arquivo_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($arquivo_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ícone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja específico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo deverá constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_trelo_cartao" value="'.$arquivo_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($arquivo_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ícone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja específico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo deverá constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_pdcl" value="'.$arquivo_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($arquivo_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ícone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($arquivo_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja específico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo deverá constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="arquivo_pdcl_item" value="'.$arquivo_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($arquivo_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCLItem();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ícone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';




$sql->adTabela('arquivo_gestao');
$sql->adCampo('arquivo_gestao.*');
if ($uuid) $sql->adOnde('arquivo_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('arquivo_gestao_arquivo ='.(int)$arquivo_id);	
$sql->adOrdem('arquivo_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_gestao_ordem'].', '.$gestao_data['arquivo_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_gestao_ordem'].', '.$gestao_data['arquivo_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_gestao_ordem'].', '.$gestao_data['arquivo_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['arquivo_gestao_ordem'].', '.$gestao_data['arquivo_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['arquivo_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['arquivo_gestao_tarefa']).'</td>';
	elseif ($gestao_data['arquivo_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['arquivo_gestao_projeto']).'</td>';
	elseif ($gestao_data['arquivo_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['arquivo_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['arquivo_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['arquivo_gestao_tema']).'</td>';
	elseif ($gestao_data['arquivo_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['arquivo_gestao_objetivo']).'</td>';
	elseif ($gestao_data['arquivo_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['arquivo_gestao_fator']).'</td>';
	elseif ($gestao_data['arquivo_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['arquivo_gestao_estrategia']).'</td>';
	elseif ($gestao_data['arquivo_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['arquivo_gestao_meta']).'</td>';
	elseif ($gestao_data['arquivo_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['arquivo_gestao_pratica']).'</td>';
	elseif ($gestao_data['arquivo_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['arquivo_gestao_acao']).'</td>';
	elseif ($gestao_data['arquivo_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['arquivo_gestao_canvas']).'</td>';
	elseif ($gestao_data['arquivo_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['arquivo_gestao_risco']).'</td>';
	elseif ($gestao_data['arquivo_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['arquivo_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['arquivo_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['arquivo_gestao_indicador']).'</td>';
	elseif ($gestao_data['arquivo_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['arquivo_gestao_calendario']).'</td>';
	elseif ($gestao_data['arquivo_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['arquivo_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['arquivo_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['arquivo_gestao_ata']).'</td>';
	elseif ($gestao_data['arquivo_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['arquivo_gestao_mswot']).'</td>';
	elseif ($gestao_data['arquivo_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['arquivo_gestao_swot']).'</td>';
	elseif ($gestao_data['arquivo_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['arquivo_gestao_operativo']).'</td>';
	elseif ($gestao_data['arquivo_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['arquivo_gestao_instrumento']).'</td>';
	elseif ($gestao_data['arquivo_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['arquivo_gestao_recurso']).'</td>';
	elseif ($gestao_data['arquivo_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['arquivo_gestao_problema']).'</td>';
	elseif ($gestao_data['arquivo_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['arquivo_gestao_demanda']).'</td>';
	elseif ($gestao_data['arquivo_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['arquivo_gestao_programa']).'</td>';
	elseif ($gestao_data['arquivo_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['arquivo_gestao_licao']).'</td>';
	elseif ($gestao_data['arquivo_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['arquivo_gestao_evento']).'</td>';
	elseif ($gestao_data['arquivo_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['arquivo_gestao_link']).'</td>';
	elseif ($gestao_data['arquivo_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['arquivo_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['arquivo_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['arquivo_gestao_tgn']).'</td>';
	elseif ($gestao_data['arquivo_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['arquivo_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['arquivo_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['arquivo_gestao_gut']).'</td>';
	elseif ($gestao_data['arquivo_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['arquivo_gestao_causa_efeito']).'</td>';
	
	elseif ($gestao_data['arquivo_gestao_semelhante']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['arquivo_gestao_semelhante']).'</td>';
	
	elseif ($gestao_data['arquivo_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['arquivo_gestao_forum']).'</td>';
	elseif ($gestao_data['arquivo_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['arquivo_gestao_checklist']).'</td>';
	elseif ($gestao_data['arquivo_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['arquivo_gestao_agenda']).'</td>';
	elseif ($gestao_data['arquivo_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['arquivo_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['arquivo_gestao_patrocinador']) echo '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['arquivo_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['arquivo_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['arquivo_gestao_template']).'</td>';
	elseif ($gestao_data['arquivo_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['arquivo_gestao_painel']).'</td>';
	elseif ($gestao_data['arquivo_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['arquivo_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['arquivo_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['arquivo_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['arquivo_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['arquivo_gestao_tr']).'</td>';	
	elseif ($gestao_data['arquivo_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['arquivo_gestao_me']).'</td>';	
	elseif ($gestao_data['arquivo_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['arquivo_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['arquivo_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['arquivo_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['arquivo_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['arquivo_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['arquivo_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['arquivo_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['arquivo_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['arquivo_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['arquivo_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['arquivo_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['arquivo_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['arquivo_gestao_ssti']).'</td>';
	elseif ($gestao_data['arquivo_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['arquivo_gestao_laudo']).'</td>';
	elseif ($gestao_data['arquivo_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['arquivo_gestao_trelo']).'</td>';
	elseif ($gestao_data['arquivo_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['arquivo_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['arquivo_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['arquivo_gestao_pdcl']).'</td>';
	elseif ($gestao_data['arquivo_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['arquivo_gestao_pdcl_item']).'</td>';

	elseif ($gestao_data['arquivo_gestao_usuario']) echo '<td align=left>'.imagem('icones/usuario_p.gif').link_usuario($gestao_data['arquivo_gestao_usuario']).'</td>';

	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['arquivo_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';



if ($arquivo_id){
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome do Arquivo', 'Todo arquivo enviado para o Sistema deverá ter um nome, preferencialmente significativo, para facilitar um futura pesquisa.').'Nome do Arquivo:'.dicaF().'</td><td align="left"><input type="text"  name="a_arquivo_nome" value="'.(!strlen($obj->arquivo_nome) ? 'n/d' : $obj->arquivo_nome).'" style="width:400px;" class="texto" READONLY /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tipo de Arquivo', 'Pela extensão do arquivo, o sistema tentará identificar qual o tipo de arquivo.').'Tipo:'.dicaF().'</td><td align="left"><input type="text"  name="a_arquivo_tipo" value="'.$obj->arquivo_tipo.'" style="width:400px;" class="texto" READONLY /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tamanho', 'O tamanho do arquivo em bytes').'Tamanho:'.dicaF().'</td><td align="left"><input type="text"  name="a_arquivo_tamanho" value="'.$obj->arquivo_tamanho.'" style="width:400px;" class="texto" READONLY /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Responsável pela Atualização', 'Nome d'.$config['genero_usuario'].' '.$config['usuario'].' que enviou o arquivo atualizado.').'Atualizado por:'.dicaF().'</td><td align="left"><input type="text"  name="a_Responsavel" value="'.$obj->getResponsavel().'" style="width:400px;" class="texto" READONLY /></td></tr>';
	}

if ($ci) echo '<tr><td align="right" style="white-space: nowrap">Revisão Menor</td><td><input type="Radio" name="revision_tipo" value="minor" checked />'.'</td><tr><td align="right" style="white-space: nowrap">Revisão Maior</td><td><input type="Radio" name="revision_tipo" value="major" /></td>';
else echo '<tr><td align="right" style="white-space: nowrap">'.dica('Versão', 'O Sistema registra as modificações nos arquivos, mantendo um histórico.<ul><li>Insira um número Natural, crescente, se for uma revisão importante, ou um número Real crescente se for uma revisão menor</li></ul>').'Versão:'.dicaF().'</td><td align="left">';

if ($ci || ($podeAdmin && $obj->arquivo_saida == 'final'))	echo '<input type="hidden" name="arquivo_saida" value="" /><input type="hidden" name="arquivo_motivo_saida" value="" />';
else echo '<input type="hidden" name="arquivo_saida" value="'.$obj->arquivo_saida.'" /><input type="hidden" name="arquivo_motivo_saida" value="'.$obj->arquivo_motivo_saida.'" />';

if ($ci) {
	$o_valor = (strlen($obj->arquivo_versao) > 0 ? $obj->arquivo_versao + 0.01 : '1');
	echo '<input type="hidden" name="arquivo_versao" value="'.float_brasileiro($o_valor).'" />';
	}
else {
	$o_valor = (strlen($obj->arquivo_versao) > 0 ? $obj->arquivo_versao : '1');
	echo '<input type="text" class="texto" name="arquivo_versao" maxlength="10" size="5" style="width:400px;" value="'.float_brasileiro($o_valor).'" />';
	}
echo '</td>';


$TipoArquivo=getSisValor('TipoArquivo');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Categoria', 'Os arquivos podem ser categorizados.').'Categoria:'.dicaF().'</td><td align="left">'.selecionaVetor($TipoArquivo, 'arquivo_categoria', 'class="texto" style="width:400px;"', $obj->arquivo_categoria).'</td></tr>';





echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nível de Acesso', 'O arquivo pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem ver e editar</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável (pel'.$config['genero_projeto'].' '.$config['projeto'].'/pel'.$config['genero_tarefa'].' '.$config['tarefa'].') e os (contatos/designados) podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td align="left" colspan="2" valign="top">'.selecionaVetor($niveis_acesso, 'arquivo_acesso', 'class="texto"', ($arquivo_id ? $obj->arquivo_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';



	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_arquivo = '.(int)$arquivo_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'arquivo_principal_indicador', 'class="texto" style="width:400px;"', $obj->arquivo_principal_indicador).'</td></tr>';
	

if ($Aplic->profissional && $exibir['moeda']){
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda padrão utilizada.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'arquivo_moeda', 'class=texto size=1', ($obj->arquivo_moeda ? $obj->arquivo_moeda : 1)).'</td></tr>';
	}	
else echo '<input type="hidden" name="arquivo_moeda" id="arquivo_moeda" value="'.($obj->arquivo_moeda ? $obj->arquivo_moeda : 1).'" />';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milhões possíveis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inserção do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="arquivo_cor" value="'.($obj->arquivo_cor ? $obj->arquivo_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';
echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o arquivo ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="arquivo_ativo" '.($obj->arquivo_ativo || !$arquivo_id ? 'checked="checked"' : '').' /></td></tr>';

if ($ci || ($podeAdmin && $obj->arquivo_saida == 'final')) echo '<tr><td align="right" style="white-space: nowrap">&nbsp;</td><td align="left"><input type="checkbox" name="final_ci" id="final_ci" onclick="finalCI()" /><label for="final_ci">Versão Final</label></td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('arquivos', $arquivo_id, 'editar');
$campos_customizados->imprimirHTML();


if ($arquivo_id) echo '<tr><td align="right">&nbsp;</td><td>'.dica('Download do Arquivo', 'Fazer o download do arquivo.').'<a class="botao" href="javascript:void(0);" onclick="javascript:window.open(\'./codigo/arquivo_visualizar.php?arquivo_id='.$obj->arquivo_id.'\',\'_self\',\'\')"><span><b>download</b></span></a>'.dicaF().'</td></tr>';

$sql->adTabela('arquivo_historico');
$sql->adOnde('arquivo_id = '.(int)$arquivo_id);
$sql->adCampo('arquivo_nome, arquivo_descricao, formatar_data(arquivo_data, \'%d/%m/%Y %H:%i\') AS data, arquivo_historico_id, arquivo_usuario_upload');
$historico = $sql->lista();
$sql->limpar();

if (count($historico)){
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Histórico', 'Ao se enviar um arquivo, pode-se escrever um texto explicativo para facilitar a compreensão do arquivo e facilitar futuras pesquisas.').'Histórico:'.dicaF().'</td><td><table class="tbl1" cellpadding=2 cellspacing=0><th>Data</th><th>Nome</th><th>Responsável pelo upload</th><th>Descrição</th><tr>';
	foreach($historico as $linha){
		echo '<tr><td><a href="javascript:void(0);" onclick="javascript:window.open(\'./codigo/arquivo_visualizar.php?historico=1&arquivo_id='.(int)$linha['arquivo_historico_id'].'\',\'_self\',\'\')">'.$linha['data'].'</a></td><td><a href="javascript:void(0);" onclick="javascript:window.open(\'./codigo/arquivo_visualizar.php?historico=1&arquivo_id='.(int)$linha['arquivo_historico_id'].'\',\'_self\',\'\')">'.$linha['arquivo_nome'].'</a></td><td>'.link_usuario($linha['arquivo_usuario_upload'],'','','esquerda').'</td><td>'.$linha['arquivo_descricao'].'</td></tr>';
		}
	echo'</table><td></tr>';
	}



if ($Aplic->profissional) include_once BASE_DIR.'/modulos/arquivos/editar_pro.php';




echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'area_notificar\').style.display) document.getElementById(\'area_notificar\').style.display=\'\'; else document.getElementById(\'area_notificar\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificar</b></a></td></tr>';
echo '<tr id="area_notificar" style="display:'.($Aplic->getPref('informa_aberto') ? '' : 'none').'"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($arquivo_id > 0 ? 'modificação' : 'criação').' do arquivo.').'Notificar:'.dicaF().'</td>';
echo '<td>';
echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável', 'Caso esta caixa esteja selecionada, uma mensagem será enviada para o responsável por este arquivo.').'<label for="email_responsavel">Responsável pelo arquivo</label>'.dicaF();


echo '<input type="checkbox" name="email_projeto_supervisor" id="email_projeto_supervisor" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica(ucfirst($config['supervisor']), 'Ao selecionar esta opção, o '.$config['supervisor'].' d'.$config['genero_projeto'].' '.$config['projeto'].' será informado '.($arquivo_id > 0 ? 'das alterações realizadas.' : 'da criação.')).ucfirst($config['supervisor']).dicaF().'&nbsp;&nbsp;';


echo '<input type="checkbox" name="email_projeto_cliente" id="email_projeto_cliente" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica(ucfirst($config['cliente']), 'Ao selecionar esta opção, o '.$config['cliente'].' d'.$config['genero_projeto'].' '.$config['projeto'].' será informado '.($arquivo_id > 0 ? 'das alterações realizadas.' : 'da criação.')).ucfirst($config['cliente']).dicaF().'&nbsp;&nbsp;';

echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados', 'Caso esta caixa esteja selecionada, uma mensagem será enviada para os designados para este arquivo.').'<label for="email_designados">Designados para o arquivo</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro do arquivo.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '</table></fieldset></td></tr>';



echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td>'.(!$dialogo ? '<td align="right">'.botao('cancelar', 'Cancelar', 'Abortar esta operação.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td>' : '').'</tr>';
echo '</form></table>';
echo estiloFundoCaixa();

?>
<script type="text/javascript">

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
	var risco_emails = document.getElementById('risco_usuarios');
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



function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('arquivo_cia').value+'&cias_id_selecionadas='+document.getElementById('arquivo_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.arquivo_cias.value = organizacao_id_string;
	document.getElementById('arquivo_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('arquivo_cias').value);
	__buildTooltip();
	}


function setCor(cor) {
	var f = document.env;
	if (cor) f.arquivo_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.arquivo_cor.value;
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('arquivo_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('arquivo_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.arquivo_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('arquivo_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('arquivo_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.arquivo_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('arquivo_dept').value+'&cia_id='+document.getElementById('arquivo_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('arquivo_dept').value+'&cia_id='+document.getElementById('arquivo_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('arquivo_cia').value=cia_id;
	document.getElementById('arquivo_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('arquivo_cia').value+'&usuario_id='+document.getElementById('arquivo_dono').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('arquivo_cia').value+'&usuario_id='+document.getElementById('arquivo_dono').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('arquivo_dono').value=usuario_id;
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}



function getCheckedValue(radioObj) {
	if(!radioObj)	return "";
	var radioLength = radioObj.length;
	if(radioLength == undefined)
		if(radioObj.checked) return radioObj.value;
		else return "";
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) return radioObj[i].value;
		}
	return "";
	}


function enviarDados() {
	var arquivo_id=<?php echo (int)$arquivo_id ?>;
	var f = document.env;


/*

	if (document.getElementById('tipo_relacao').value=='usuario' && f.arquivo_dono.value!=<?php echo $Aplic->usuario_id ?>)	{
		alert('Para que o arquivo seja particular você deverá ser o responsável pelo mesmo.');
		f.nome_gerente.focus();
		return;
		}

	if (document.getElementById('tipo_relacao').value=='calendario' && f.arquivo_calendario.value<1)	{
		alert('Escolha uma agenda');
		return;
		}

	if (document.getElementById('tipo_relacao').value=='ata' && f.arquivo_ata.value<1)	{
		alert('Escolha uma ata');
		return;
		}

	if (document.getElementById('tipo_relacao').value=='projeto' && f.arquivo_projeto.value<1)	{
		alert('Escolha <?php echo ($config["genero_projeto"]=="a" ? "uma ": "um ").$config["projeto"] ?>');
		return;
		}

	if (document.getElementById('tipo_relacao').value=='pratica' && f.arquivo_pratica.value<1)	{
		alert('Escolha <?php echo ($config["genero_pratica"]=="a" ? "uma ": "um ").$config["pratica"] ?>');
		return;
		}

	if (document.getElementById('tipo_relacao').value=='demanda' && f.arquivo_demanda.value<1)	{
		alert('Escolha uma demanda');
		return;
		}
	if (document.getElementById('tipo_relacao').value=='instrumento' && f.arquivo_instrumento.value<1)	{
		alert("Escolha <?php echo ($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento']?>");
		return;
		}
	if (document.getElementById('tipo_relacao').value=='acao' && f.arquivo_acao.value<1)	{
		alert("Escolha <?php echo ($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao']?>");
		return;
		}

	if (document.getElementById('tipo_relacao').value=='indicador' && f.arquivo_indicador.value<1)	{
		alert('Escolha um indicador');
		return;
		}

	if (document.getElementById('tipo_relacao').value=='objetivo' && f.arquivo_objetivo.value<1)	{
		alert("Escolha <?php echo ($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo']?>");
		return;
		}

	if (document.getElementById('tipo_relacao').value=='tema' && f.arquivo_tema.value<1)	{
		alert("Escolha <?php echo ($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema']?>");
		return;
		}

	if (document.getElementById('tipo_relacao').value=='estrategia' && f.arquivo_estrategia.value<1)	{
		alert("Escolha <?php echo ($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa']?>");
		return;
		}

	if (document.getElementById('tipo_relacao').value=='fator' && f.arquivo_fator.value<1)	{
		alert("Escolha <?php echo ($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator']?>");
		return;
		}

	if (document.getElementById('tipo_relacao').value=='meta' && f.arquivo_meta.value<1)	{
		alert('Escolha uma meta');
		return;
		}

	if (document.getElementById('tipo_relacao').value=='perspectiva' && f.arquivo_perspectiva.value<1)	{
		alert("Escolha <?php echo ($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva']?>");
		return;
		}

	if (document.getElementById('tipo_relacao').value=='usuario')	{
		f.arquivo_usuario.value=<?php echo $Aplic->usuario_id ?>;
		}

	*/

	if (f.arquivo_id.value==0 && f.arquivo.value.length<1) {
		alert('Escolha um arquivo a ser enviado!');
		return;
		}

	if (f.arquivo.value.length > 0) {
		f.arquivo_usuario_upload.value=<?php echo $Aplic->usuario_id ?>;
		}



	f.submit();
	}


function excluir() {
	if (confirm( "Tem certeza de que deseja excluir este arquivo?")) {
		var f = document.env;
		f.del.value='1';
		f.submit();
		}
	}


function finalCI() {
	var f = document.env;
	if (f.final_ci.value == '1') {
		f.arquivo_saida.value = 'Final';
		f.arquivo_motivo_saida.value = 'Versão Final';
		}
	else {
		f.arquivo_saida.value = '';
		f.arquivo_motivo_saida.value = '';
		}
	}



function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('arquivo_cia').value,'arquivo_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}

function popPasta() {
	var f = document.env;
	var tipo=document.getElementById('tipo_relacao').value;

	<?php if (!$Aplic->profissional) { ?>

		if (tipo=='projeto' && f.arquivo_projeto.value<1) alert('Necessita escolher um<?php echo ($config["genero_projeto"]=="o" ? "" : "a")." ".$config["projeto"]?>!');
		else if (tipo=='pratica' && f.arquivo_pratica.value<1) alert('Necessita escolher um<?php echo ($config["genero_pratica"]=="o" ? "" : "a")." ".$config["pratica"]?>!');
		else if (tipo=='demanda' && f.arquivo_demanda.value<1) alert('Necessita escolher uma demanda!');
		else if (tipo=='instrumento' && f.arquivo_instrumento.value<1) alert('Necessita escolher <?php echo ($config["genero_instrumento"]=="o" ? "um" : "uma")." ".$config["instrumento"]?>!');
		else if (tipo=='indicador' && f.arquivo_indicador.value<1) alert('Necessita escolher um indicador!');
		else if (tipo=='tema' && f.arquivo_tema.value<1) alert('Necessita escolher <?php echo ($config["genero_tema"]=="o" ? "um" : "uma")." ".$config["tema"]?>!');
		else if (tipo=='acao' && f.arquivo_acao.value<1) alert('Necessita escolher <?php echo ($config["genero_acao"]=="o" ? "um" : "uma")." ".$config["acao"]?>!');
		else if (tipo=='objetivo' && f.arquivo_objetivo.value<1) alert('Necessita escolher <?php echo ($config["genero_objetivo"]=="o" ? "um" : "uma")." ".$config["objetivo"]?>!');
		else if (tipo=='estrategia' && f.arquivo_estrategia.value<1) alert('Necessita escolher <?php echo ($config["genero_iniciativa"]=="o" ? "um" : "uma")." ".$config["iniciativa"]?>!');
		else if (tipo=='fator' && f.arquivo_fator.value<1) alert('Necessita escolher <?php echo ($config["genero_fator"]=="o" ? "um" : "uma")." ".$config["fator"]?>!');
		else if (tipo=='meta' && f.arquivo_meta.value<1) alert('Necessita escolher uma meta!');
		else if (tipo=='perspectiva' && f.arquivo_perspectiva.value<1) alert('Necessita escolher <?php echo ($config["genero_perspectiva"]=="o" ? "um" : "uma")." ".$config["perspectiva"]?>!');
		else if (tipo=='calendario' && f.arquivo_calendario.value<1) alert('Necessita escolher uma agenda!');
		else if (tipo=='ata' && f.arquivo_ata.value<1) alert('Necessita escolher uma ata!');
		else{
			var projeto=(tipo=='projeto' ? f.arquivo_projeto.value : null);
			var tarefa=(tipo=='projeto' ? f.arquivo_tarefa.value : null);
			var pratica=(tipo=='pratica' ? f.arquivo_pratica.value : null);
			var demanda=(tipo=='demanda' ? f.arquivo_demanda.value : null);
			var instrumento=(tipo=='instrumento' ? f.arquivo_instrumento.value : null);
			var acao=(tipo=='acao' ? f.arquivo_acao.value : null);
			var indicador=(tipo=='indicador' ? f.arquivo_indicador.value : null);
			var objetivo=(tipo=='objetivo' ? f.arquivo_objetivo.value : null);
			var tema=(tipo=='tema' ? f.arquivo_tema.value : null);
			var estrategia=(tipo=='estrategia' ? f.arquivo_estrategia.value : null);
			var meta=(tipo=='meta' ? f.arquivo_meta.value : null);
			var perspectiva=(tipo=='perspectiva' ? f.arquivo_perspectiva.value : null);
			var canvas=(tipo=='canvas' ? f.arquivo_canvas.value : null);
			var fator=(tipo=='fator' ? f.arquivo_fator.value : null);
			var calendario=(tipo=='calendario' ? f.arquivo_calendario.value : null);
			var ata=(tipo=='ata' ? f.arquivo_ata.value : null);
			window.open('./index.php?m=publico&a=selecao_unica_pasta&dialogo=1&arquivo_pasta_id='+document.env.arquivo_pasta.value+
			'&chamar_volta=setPasta'+
			(objetivo !=null ? '&objetivo_id='+objetivo : '')+
			(tema !=null ? '&tema_id='+tema : '')+
			(estrategia !=null ? '&pg_estrategia_id='+estrategia : '')+
			(pratica !=null ? '&pratica_id='+pratica : '')+
			(demanda !=null ? '&demanda_id='+demanda : '')+
			(instrumento !=null ? '&instrumento_id='+instrumento : '')+
			(acao !=null ? '&plano_acao_id='+acao : '')+
			(meta !=null ? '&pg_meta_id='+meta : '')+
			(perspectiva !=null ? '&pg_perspectiva_id='+perspectiva : '')+
			(fator !=null ? '&fator_id='+fator : '')+
			(projeto !=null ? '&projeto_id='+projeto: '')+
			(tarefa !=null ? '&tarefa_id='+tarefa: '')+
			(indicador !=null ? '&pratica_indicador_id='+indicador: '')+
			(calendario !=null ? '&calendario_id='+calendario : '')+
			'&cia_id='+document.getElementById('arquivo_cia').value, 'Pastas','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
<?php } else { ?>
		parent.gpwebApp.popUp('Pasta', 500, 500, 'm=publico&a=selecao_unica_pasta&dialogo=1&edicao=1&arquivo=1&arquivo_pasta_id='+document.env.arquivo_pasta.value+'&chamar_volta=setPasta&cia_id='+document.getElementById('arquivo_cia').value+'&uuid='+document.getElementById('uuid').value+'&arquivo_id='+document.getElementById('arquivo_id').value, window.setPasta, window);
<?php } ?>

	}

function setPasta(chave, valor){
	document.env.arquivo_pasta.value = chave;
	document.env.pasta_nome.value = valor;
	}


function mostrar(){
	limpar_tudo();
	esconder_tipo();
	if (document.getElementById('tipo_relacao').value){
		document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
		
		if (document.getElementById('tipo_relacao').value=='usuario') setParticular();
		
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


function setParticular(){
	limpar_tudo();
	document.env.arquivo_usuario.value = <?php echo $Aplic->usuario_id ?>;
	incluir_relacionado();
	}	
	


	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('arquivo_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('arquivo_cia').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.arquivo_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('arquivo_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.arquivo_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('arquivo_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('arquivo_cia').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.arquivo_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}			


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('arquivo_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.arquivo_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('arquivo_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.arquivo_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('arquivo_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.arquivo_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('arquivo_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.arquivo_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('arquivo_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.arquivo_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('arquivo_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.arquivo_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('arquivo_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.arquivo_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('arquivo_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.arquivo_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('arquivo_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.arquivo_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('arquivo_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('arquivo_cia').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.arquivo_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('arquivo_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.arquivo_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('arquivo_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.arquivo_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('arquivo_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.arquivo_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('arquivo_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.arquivo_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('arquivo_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('arquivo_cia').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.arquivo_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('arquivo_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('arquivo_cia').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.arquivo_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('arquivo_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.arquivo_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('arquivo_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.arquivo_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Camçpo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('arquivo_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.arquivo_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('arquivo_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('arquivo_cia').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.arquivo_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('arquivo_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('arquivo_cia').value, 'Instrumento Jurídico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.arquivo_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('arquivo_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('arquivo_cia').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.arquivo_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('arquivo_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.arquivo_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('arquivo_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('arquivo_cia').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.arquivo_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('arquivo_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.arquivo_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('arquivo_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.arquivo_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('arquivo_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('arquivo_cia').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.arquivo_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('arquivo_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('arquivo_cia').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.arquivo_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('arquivo_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('arquivo_cia').value, 'Avaliação','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.arquivo_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('arquivo_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.arquivo_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('arquivo_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('arquivo_cia').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.arquivo_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('arquivo_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('arquivo_cia').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.arquivo_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('arquivo_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('arquivo_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.arquivo_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('arquivo_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('arquivo_cia').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.arquivo_arquivo.value = chave;
	document.env.gestao_arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('arquivo_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('arquivo_cia').value, 'Fórum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.arquivo_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('arquivo_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('arquivo_cia').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.arquivo_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('arquivo_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('arquivo_cia').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.arquivo_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('arquivo_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('arquivo_cia').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.arquivo_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('arquivo_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('arquivo_cia').value, 'Odômetro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.arquivo_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('arquivo_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('arquivo_cia').value, 'Composição de Painéis','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.arquivo_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('arquivo_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.arquivo_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('arquivo_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.arquivo_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('arquivo_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('arquivo_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.arquivo_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('arquivo_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.arquivo_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('arquivo_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('arquivo_cia').value, 'Slideshow de Composições','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.arquivo_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('arquivo_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('arquivo_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.arquivo_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('arquivo_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('arquivo_cia').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.arquivo_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('arquivo_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('arquivo_cia').value, 'Planejamento Estratégico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.arquivo_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

function popSSTI() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('arquivo_cia').value, window.setSSTI, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setSSTI(chave, valor){
		limpar_tudo();
		document.env.arquivo_ssti.value = chave;
		document.env.ssti_nome.value = valor;
		incluir_relacionado();
		}	
					
	function popLaudo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('arquivo_cia').value, window.setLaudo, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setLaudo(chave, valor){
		limpar_tudo();
		document.env.arquivo_laudo.value = chave;
		document.env.laudo_nome.value = valor;
		incluir_relacionado();
		}		
		
	function popTrelo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('arquivo_cia').value, window.setTrelo, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTrelo(chave, valor){
		limpar_tudo();
		document.env.arquivo_trelo.value = chave;
		document.env.trelo_nome.value = valor;
		incluir_relacionado();
		}	
		
	function popTreloCartao() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('arquivo_cia').value, window.setTreloCartao, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTreloCartao(chave, valor){
		limpar_tudo();
		document.env.arquivo_trelo_cartao.value = chave;
		document.env.trelo_cartao_nome.value = valor;
		incluir_relacionado();
		}	
		
	function popPDCL() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('arquivo_cia').value, window.setPDCL, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPDCL(chave, valor){
		limpar_tudo();
		document.env.arquivo_pdcl.value = chave;
		document.env.pdcl_nome.value = valor;
		incluir_relacionado();
		}				
		
	function popPDCLItem() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('arquivo_cia').value, window.setPDCLItem, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCLItem&tabela=pdcl_item&cia_id='+document.getElementById('arquivo_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPDCLItem(chave, valor){
		limpar_tudo();
		document.env.arquivo_pdcl_item.value = chave;
		document.env.pdcl_item_nome.value = valor;
		incluir_relacionado();
		}	


function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.arquivo_projeto.value = null;
	document.env.arquivo_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.arquivo_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.arquivo_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.arquivo_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.arquivo_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.arquivo_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.arquivo_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.arquivo_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.arquivo_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.arquivo_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.arquivo_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.arquivo_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.arquivo_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.arquivo_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.arquivo_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.arquivo_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.arquivo_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.arquivo_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.arquivo_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.arquivo_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.arquivo_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.arquivo_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.arquivo_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.arquivo_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.arquivo_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.arquivo_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.arquivo_link.value = null;
	document.env.link_nome.value = '';
	document.env.arquivo_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.arquivo_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.arquivo_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.arquivo_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.arquivo_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.arquivo_arquivo.value = null;
	document.env.gestao_arquivo_nome.value = '';
	document.env.arquivo_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.arquivo_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.arquivo_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.arquivo_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.arquivo_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.arquivo_template.value = null;
	document.env.template_nome.value = '';
	document.env.arquivo_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.arquivo_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.arquivo_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.arquivo_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.arquivo_me.value = null;
	document.env.me_nome.value = '';
	document.env.arquivo_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.arquivo_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.arquivo_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.arquivo_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.arquivo_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.arquivo_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.arquivo_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.arquivo_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.arquivo_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.arquivo_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.arquivo_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.arquivo_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';			
		
	document.env.arquivo_usuario.value = null;
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('arquivo_id').value,
	document.getElementById('uuid').value,
	f.arquivo_projeto.value,
	f.arquivo_tarefa.value,
	f.arquivo_perspectiva.value,
	f.arquivo_tema.value,
	f.arquivo_objetivo.value,
	f.arquivo_fator.value,
	f.arquivo_estrategia.value,
	f.arquivo_meta.value,
	f.arquivo_pratica.value,
	f.arquivo_acao.value,
	f.arquivo_canvas.value,
	f.arquivo_risco.value,
	f.arquivo_risco_resposta.value,
	f.arquivo_indicador.value,
	f.arquivo_calendario.value,
	f.arquivo_monitoramento.value,
	f.arquivo_ata.value,
	f.arquivo_mswot.value,
	f.arquivo_swot.value,
	f.arquivo_operativo.value,
	f.arquivo_instrumento.value,
	f.arquivo_recurso.value,
	f.arquivo_problema.value,
	f.arquivo_demanda.value,
	f.arquivo_programa.value,
	f.arquivo_licao.value,
	f.arquivo_evento.value,
	f.arquivo_link.value,
	f.arquivo_avaliacao.value,
	f.arquivo_tgn.value,
	f.arquivo_brainstorm.value,
	f.arquivo_gut.value,
	f.arquivo_causa_efeito.value,
	f.arquivo_arquivo.value,
	f.arquivo_forum.value,
	f.arquivo_checklist.value,
	f.arquivo_agenda.value,
	f.arquivo_agrupamento.value,
	f.arquivo_patrocinador.value,
	f.arquivo_template.value,
	f.arquivo_painel.value,
	f.arquivo_painel_odometro.value,
	f.arquivo_painel_composicao.value,
	f.arquivo_tr.value,
	f.arquivo_me.value,
	f.arquivo_acao_item.value,
	f.arquivo_beneficio.value,
	f.arquivo_painel_slideshow.value,
	f.arquivo_projeto_viabilidade.value,
	f.arquivo_projeto_abertura.value,
	f.arquivo_plano_gestao.value,
	f.arquivo_ssti.value,
	f.arquivo_laudo.value,
	f.arquivo_trelo.value,
	f.arquivo_trelo_cartao.value,
	f.arquivo_pdcl.value,
	f.arquivo_pdcl_item.value,
	
	f.arquivo_usuario.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(arquivo_gestao_id){
	xajax_excluir_gestao(document.getElementById('arquivo_id').value, document.getElementById('uuid').value, arquivo_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, arquivo_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, arquivo_gestao_id, direcao, document.getElementById('arquivo_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$arquivo_id && (
	$arquivo_tarefa || 
	$arquivo_projeto || 
	$arquivo_perspectiva || 
	$arquivo_tema || 
	$arquivo_objetivo || 
	$arquivo_fator || 
	$arquivo_estrategia || 
	$arquivo_meta || 
	$arquivo_pratica || 
	$arquivo_acao || 
	$arquivo_canvas || 
	$arquivo_risco || 
	$arquivo_risco_resposta || 
	$arquivo_indicador || 
	$arquivo_calendario || 
	$arquivo_monitoramento || 
	$arquivo_ata || 
	$arquivo_mswot || 
	$arquivo_swot || 
	$arquivo_operativo || 
	$arquivo_instrumento || 
	$arquivo_recurso || 
	$arquivo_problema || 
	$arquivo_demanda || 
	$arquivo_programa || 
	$arquivo_licao || 
	$arquivo_evento || 
	$arquivo_link || 
	$arquivo_avaliacao || 
	$arquivo_tgn || 
	$arquivo_brainstorm || 
	$arquivo_gut || 
	$arquivo_causa_efeito || 
	$arquivo_arquivo || 
	$arquivo_forum || 
	$arquivo_checklist || 
	$arquivo_agenda || 
	$arquivo_agrupamento || 
	$arquivo_patrocinador || 
	$arquivo_template || 
	$arquivo_painel || 
	$arquivo_painel_odometro || 
	$arquivo_painel_composicao || 
	$arquivo_tr || 
	$arquivo_me || 
	$arquivo_acao_item || 
	$arquivo_beneficio || 
	$arquivo_painel_slideshow || 
	$arquivo_projeto_viabilidade || 
	$arquivo_projeto_abertura || 
	$arquivo_plano_gestao || 
	$arquivo_ssti || 
	$arquivo_laudo || 
	$arquivo_trelo || 
	$arquivo_trelo_cartao || 
	$arquivo_pdcl || 
	$arquivo_pdcl_item ||
	
	$arquivo_usuario
	)) echo 'incluir_relacionado();';
	?>	
		
</script>
