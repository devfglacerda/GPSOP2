<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/viabilidade.class.php');
$projeto_viabilidade_id = intval(getParam($_REQUEST, 'projeto_viabilidade_id', 0));

$obj = new CViabilidade();
$obj->load($projeto_viabilidade_id);
$sql = new BDConsulta();

$sql->adTabela('demanda_config');
$sql->adCampo('demanda_config.*');
$configuracao = $sql->linha();
$sql->limpar();


//$editar=($podeEditar && ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'viabilidade') || $Aplic->usuario_super_admin));


$editar=permiteEditarViabilidade($obj->projeto_viabilidade_acesso,$obj->projeto_viabilidade_id);

$podeEditarTudo=($obj->projeto_viabilidade_acesso>=5 ? $editar && (in_array($obj->projeto_viabilidade_responsavel, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);

if (!permiteAcessarViabilidade($obj->projeto_viabilidade_acesso,$obj->projeto_viabilidade_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ViabilidadeVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ViabilidadeVerTab') !== null ? $Aplic->getEstado('ViabilidadeVerTab') : 0;
$msg = '';

$sql->adTabela('demandas');
$sql->adCampo('demanda_termo_abertura');
$sql->adOnde('demanda_viabilidade = '.(int)$obj->projeto_viabilidade_id);
$demanda = $sql->linha();
$sql->limpar();

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'viabilidade\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if ($Aplic->profissional){	
	//tem assinatura que aprova
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_viabilidade='.(int)$obj->projeto_viabilidade_id);
	$tem_aprovacao = $sql->resultado();
	$sql->limpar();
	}



if (!$dialogo){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Estudo de Viabilidade', 'viabilidade.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_viabilidade='.(int)$obj->projeto_viabilidade_id);
	$assinar = $sql->linha();
	$sql->limpar();
	
	
	
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #212121" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Estudos de Viabilidades','Clique neste botão para visualizar a lista de estudos de viabilidade.').'Lista de Estudos de Viabilidades'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_lista\");");
	if ($obj->projeto_viabilidade_demanda) $km->Add("ver","ver_demanda",dica('Demanda','Clique neste botão para visualizar a demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_ver&demanda_id=".$obj->projeto_viabilidade_demanda."\");");	
	if ($demanda['demanda_termo_abertura']) $km->Add("ver","ver_abertura",dica('Termo de Abertura','Clique neste botão para visualizar o termo de abertura relacionado.').'Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_ver&projeto_abertura_id=".$demanda['demanda_termo_abertura']."\");");	
	if ($obj->projeto_viabilidade_projeto) $km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste botão para visualizar d'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$obj->projeto_viabilidade_projeto."\");");		
	
	$bloquear=($obj->projeto_viabilidade_aprovado && $config['trava_aprovacao'] && $tem_aprovacao && !$Aplic->usuario_super_admin);
	

	
	

	if ($podeEditar && $editar) {
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		if (!$bloquear && ($Aplic->checarModulo('projetos', 'adicionar', null, 'viabilidade_custo') || $Aplic->checarModulo('projetos', 'editar', null, 'viabilidade_custo'))) $km->Add("inserir","inserir_custo",dica('Planilha de Custos', 'Inserir a planilha de custos do estudo de viabilidade.').'Planilha de Custos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_estimado_pro&projeto_viabilidade_id=".$projeto_viabilidade_id."\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar&projeto_viabilidade_id=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) $km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_projeto_viabilidade=".$projeto_viabilidade_id."\");");	
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'demanda')) $km->Add("inserir","inserir_demanda",dica('Nova Demanda', 'Inserir uma nova demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar&demanda_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($config['doc_interno'] && $Aplic->checarModulo('email', 'adicionar', $Aplic->usuario_id, 'criar_modelo')){
			$sql->adTabela('modelos_tipo');
			$sql->esqUnir('modelo_cia', 'modelo_cia', 'modelo_cia_tipo=modelo_tipo_id');
			$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, imagem');
			$sql->adOnde('organizacao='.(int)$config['militar']);
			$sql->adOnde('modelo_cia_cia='.(int)$Aplic->usuario_cia);
			$modelos = $sql->Lista();
			$sql->limpar();
			if (count($modelos)){
				$km->Add("inserir","criar_documentos","Documento");
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_projeto_viabilidade=".$projeto_viabilidade_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		$km->Add("inserir","diverso",dica('Diversos','Menu de objetos diversos').'Diversos'.dicaF(), "javascript: void(0);'");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("diverso","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("diverso","inserir_forum",dica('Novo Fórum', 'Inserir um novo fórum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("diverso","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("diverso","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'resposta_risco')) $km->Add("diverso","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'adicionar', null, null)) $km->Add("diverso","inserir_instrumento",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Inserir um'.($config['genero_instrumento']=='a' ? 'a' : '').' nov'.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar&instrumento_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('recursos', 'adicionar', null, null)) $km->Add("diverso","inserir_recurso",dica('Nov'.$config['genero_recurso'].' '.ucfirst($config['recurso']), 'Inserir um'.($config['genero_recurso']=='a' ? 'a' : '').' nov'.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.').ucfirst($config['recurso']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar&recurso_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'adicionar', null, null)) $km->Add("diverso","inserir_patrocinador",dica('Nov'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Inserir '.($config['genero_patrocinador']=='o' ? 'um' : 'uma').' nov'.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.').ucfirst($config['patrocinador']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=patrocinadores&a=patrocinador_editar&patrocinador_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'programa')) $km->Add("diverso","inserir_programa",dica('Nov'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Inserir um'.($config['genero_programa']=='a' ? 'a' : '').' nov'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.').ucfirst($config['programa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=programa_pro_editar&programa_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'licao')) $km->Add("diverso","inserir_licao",dica('Nov'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Inserir um'.($config['genero_licao']=='a' ? 'a' : '').' nov'.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.').ucfirst($config['licao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar&licao_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'pratica')) $km->Add("diverso","inserir_pratica",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Inserir um'.($config['genero_pratica']=='a' ? 'a' : '').' nov'.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.').ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'adicionar', null, null)) $km->Add("diverso","inserir_tr",dica('Nov'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Inserir um'.($config['genero_tr']=='a' ? 'a' : '').' nov'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_editar&tr_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'brainstorm')) $km->Add("diverso","inserir_brainstorm",dica('Novo Brainstorm', 'Inserir um novo brainstorm relacionado.').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm_editar&brainstorm_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'gut')) $km->Add("diverso","inserir_gut",dica('Nova Matriz GUT', 'Inserir uma nova matriz GUT relacionado.').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut_editar&gut_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'causa_efeito')) $km->Add("diverso","inserir_causa_efeito",dica('Novo Diagrama de Causa-Efeito', 'Inserir um novo Diagrama de causa-efeito relacionado.').'Diagrama de causa-efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito_editar&causa_efeito_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'tgn')) $km->Add("diverso","inserir_tgn",dica('Nov'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Inserir um'.($config['genero_tgn']=='a' ? 'a' : '').' nov'.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.').ucfirst($config['tgn']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tgn_pro_editar&tgn_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'canvas')) $km->Add("diverso","inserir_canvas",dica('Nov'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Inserir um'.($config['genero_canvas']=='a' ? 'a' : '').' nov'.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.').ucfirst($config['canvas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=canvas_pro_editar&canvas_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_mswot",dica('Nova Matriz SWOT', 'Inserir uma nova matriz SWOT relacionada.').'Matriz SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=mswot_editar&mswot_projeto_viabilidade=".$projeto_viabilidade_id."\");");
			$km->Add("diverso","inserir_swot",dica('Novo Campo SWOT', 'Inserir um novo campo SWOT relacionado.').'Campo SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=swot_editar&swot_projeto_viabilidade=".$projeto_viabilidade_id."\");");
			}
		if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'adicionar', null, null)) $km->Add("diverso","inserir_operativo",dica('Novo Plano Operativo', 'Inserir um novo plano operativo relacionado.').'Plano operativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=operativo&a=operativo_editar&operativo_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'monitoramento')) $km->Add("diverso","inserir_monitoramento",dica('Novo monitoramento', 'Inserir um novo monitoramento relacionado.').'Monitoramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=monitoramento_editar_pro&monitoramento_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'avaliacao_indicador')) $km->Add("diverso","inserir_avaliacao",dica('Nova Avaliação', 'Inserir uma nova avaliação relacionada.').'Avaliação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar&avaliacao_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'checklist')) $km->Add("diverso","inserir_checklist",dica('Novo Checklist', 'Inserir um novo checklist relacionado.').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar&checklist_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional) $km->Add("diverso","inserir_agenda",dica('Novo Compromisso', 'Inserir um novo compromisso relacionado.').'Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso&agenda_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'adicionar', null, null)) $km->Add("diverso","inserir_agrupamento",dica('Novo Agrupamento', 'Inserir um novo Agrupamento relacionado.').'Agrupamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=agrupamento&a=agrupamento_editar&agrupamento_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'modelo')) $km->Add("diverso","inserir_template",dica('Novo Modelo', 'Inserir um novo modelo relacionado.').'Modelo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_editar&template_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'painel_indicador')) $km->Add("diverso","inserir_painel",dica('Novo Painel de Indicador', 'Inserir um novo painel de indicador relacionado.').'Painel de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_editar&painel_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'odometro_indicador')) $km->Add("diverso","inserir_painel_odometro",dica('Novo Odômetro de Indicador', 'Inserir um novo odômetro de indicador relacionado.').'Odômetro de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_editar&painel_odometro_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'composicao_painel')) $km->Add("diverso","inserir_painel_composicao",dica('Nova Composição de Painéis', 'Inserir uma nova composição de painéis relacionada.').'Composição de painéis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_editar&painel_composicao_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'slideshow_painel')) $km->Add("diverso","inserir_painel_slideshow",dica('Novo Slideshow de Composições', 'Inserir um novo slideshow de composições relacionado.').'Slideshow de composições'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_slideshow_pro_editar&painel_slideshow_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'ssti')) $km->Add("diverso","inserir_ssti",dica('Nov'.$config['genero_ssti'].' '.ucfirst($config['ssti']), 'Inserir um'.($config['genero_ssti']=='a' ? 'a' : '').' nov'.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.').ucfirst($config['ssti']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=ssti_editar&ssti_projeto_viabilidade=".$projeto_viabilidade_id."\");");
			if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'laudo')) $km->Add("diverso","inserir_laudo",dica('Nov'.$config['genero_laudo'].' '.ucfirst($config['laudo']), 'Inserir um'.($config['genero_laudo']=='a' ? 'a' : '').' nov'.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.').ucfirst($config['laudo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=laudo_editar&laudo_projeto_viabilidade=".$projeto_viabilidade_id."\");");
			if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'adicionar', null, null)) {
				$km->Add("diverso","inserir_trelo",dica('Nov'.$config['genero_trelo'].' '.ucfirst($config['trelo']), 'Inserir um'.($config['genero_trelo']=='a' ? 'a' : '').' nov'.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.').ucfirst($config['trelo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_editar&trelo_projeto_viabilidade=".$projeto_viabilidade_id."\");");
				$km->Add("diverso","inserir_trelo_cartao",dica('Nov'.$config['genero_trelo_cartao'].' '.ucfirst($config['trelo_cartao']), 'Inserir um'.($config['genero_trelo_cartao']=='a' ? 'a' : '').' nov'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.').ucfirst($config['trelo_cartao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_cartao_editar&trelo_cartao_projeto_viabilidade=".$projeto_viabilidade_id."\");");
				}
			if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'adicionar', null, null)) {
				$km->Add("diverso","inserir_pdcl",dica('Nov'.$config['genero_pdcl'].' '.ucfirst($config['pdcl']), 'Inserir um'.($config['genero_pdcl']=='a' ? 'a' : '').' nov'.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.').ucfirst($config['pdcl']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_editar&pdcl_projeto_viabilidade=".$projeto_viabilidade_id."\");");
				$km->Add("diverso","inserir_pdcl_item",dica('Nov'.$config['genero_pdcl_item'].' '.ucfirst($config['pdcl_item']), 'Inserir um'.($config['genero_pdcl_item']=='a' ? 'a' : '').' nov'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.').ucfirst($config['pdcl_item']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_item_editar&pdcl_item_projeto_viabilidade=".$projeto_viabilidade_id."\");");
				}
		$km->Add("inserir","gestao1",dica('Gestao','Menu de objetos de gestão').'Gestao'.dicaF(), "javascript: void(0);'");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'perspectiva')) $km->Add("gestao1","inserir_perspectiva",dica('Nov'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Inserir um'.($config['genero_perspectiva']=='a' ? 'a' : '').' nov'.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_editar&perspectiva_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'tema')) $km->Add("gestao1","inserir_tema",dica('Nov'.$config['genero_tema'].' '.ucfirst($config['tema']), 'Inserir um'.($config['genero_tema']=='a' ? 'a' : '').' nov'.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.').ucfirst($config['tema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_editar&tema_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'objetivo')) $km->Add("gestao1","inserir_objetivo",dica('Nov'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']), 'Inserir um'.($config['genero_objetivo']=='a' ? 'a' : '').' nov'.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.').ucfirst($config['objetivo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_editar&objetivo_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->profissional && isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $km->Add("gestao1","inserir_me",dica('Nov'.$config['genero_me'].' '.ucfirst($config['me']), 'Inserir um'.($config['genero_me']=='a' ? 'a' : '').' nov'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=me_editar_pro&me_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator')) $km->Add("gestao1","inserir_fator",dica('Nov'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Inserir um'.($config['genero_fator']=='a' ? 'a' : '').' nov'.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.').ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar&fator_projeto_viabilidade=".$projeto_viabilidade_id."\");"); 
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'iniciativa')) $km->Add("gestao1","inserir_iniciativa",dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Inserir um'.($config['genero_iniciativa']=='a' ? 'a' : '').' nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.').ucfirst($config['iniciativa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_editar&estrategia_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'meta')) $km->Add("gestao1","inserir_meta",dica('Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Inserir um'.($config['genero_meta']=='a' ? 'a' : '').' nov'.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.').ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar&meta_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'planejamento')) $km->Add("gestao1","inserir_plano_gestao",dica('Novo Planejamento estratégico', 'Inserir um novo planejamento estratégico relacionado.').'Planejamento estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar&plano_gestao_projeto_viabilidade=".$projeto_viabilidade_id."\");");
		
		}	
	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	
	if (isset($assinar['assinatura_id']) && $assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura no estudo de viabilidade.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar o estudo de viabilidade.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=assinatura&a=assinatura_assinar&projeto_viabilidade_id=".$obj->projeto_viabilidade_id."\");"); 
	
	
	if ($podeEditarTudo && $podeEditar && !$bloquear) $km->Add("acao","acao_editar",dica('Editar Estudo de Viabilidade','Editar os detalhes deste estudo de viabilidade.').'Editar Estudos de Viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_editar&projeto_viabilidade_id=".$obj->projeto_viabilidade_id."\");");
	if ($podeEditarTudo && $podeExcluir && !$bloquear) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este estudo de viabilidade.').'Excluir Estudo de Viabilidade'.dicaF(), "javascript: void(0);' onclick='excluir()");
		
	if (($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') || $Aplic->usuario_super_admin) && $obj->projeto_viabilidade_viavel && !$demanda['demanda_termo_abertura'] && ($obj->projeto_viabilidade_aprovado || !$Aplic->profissional)) {
		$km->Add("acao","acao_abertura",dica('Elaborar Termo de Abertura','Entrará na tela em que se elaborará o termo de abertura d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Elaborar Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_editar&projeto_viabilidade_id=".$obj->projeto_viabilidade_id."&demanda_id=".$obj->projeto_viabilidade_demanda."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'canvas'))$km->Add("acao","acao_abertura_canvas",dica('Elaborar Termo de Abertura de '.ucfirst($config['canvas']),'Entrará na tela em que se elaborará o termo de abertura d'.$config['genero_projeto'].' '.$config['projeto'].' carregando dados de '.$config['canvas'].' selecionad'.$config['genero_canvas'].'.').'Elaborar Termo de Abertura de '.ucfirst($config['canvas']).dicaF(), "javascript: void(0);' onclick='popCanvas();");
		}
	
	$sql->adTabela('projeto_viabilidade_custo');
	$sql->adCampo('count(projeto_viabilidade_custo_id)');
	$sql->adOnde('projeto_viabilidade_custo_projeto_viabilidade='.(int)$obj->projeto_viabilidade_id);
	$tem_custo = $sql->resultado();
	$sql->limpar();
	
	
	if ($Aplic->profissional && $tem_custo && $editar && $Aplic->checarModulo('projetos', 'aprovar', null, 'viabilidade_custo')) $km->Add("acao","acao_aprovar_custo",dica('Aprovar Planilha de Custo','Acesse interface onde será possível aprovar a planilha de custo deste estudo de viabilidade.').'Aprovar Planilha de Custo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_aprovar_custos_pro&projeto_viabilidade_id=".$obj->projeto_viabilidade_id."\");");
	
	
	
	
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Estudo de Viabilidade', 'Visualize o estudo de viabilidade.').'Estudo de Viabilidade.'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=viabilidade_imprimir&dialogo=1&projeto_viabilidade_id=".$obj->projeto_viabilidade_id."\");");	
	$km->Add("acao_imprimir","acao_imprimir2",dica('Detalhes deste Estudo de Viabilidade', 'Visualize os detalhes deste estudo de viabilidade.').'Detalhes deste Estudo de Viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&projeto_viabilidade_id=".$obj->projeto_viabilidade_id."\");");
	
	if ($Aplic->profissional && $podeEditarTudo && $podeEditar) $km->Add("acao","acao_exportar",dica('Exportar Link', 'Clique neste ícone '.imagem('icones/exporta_p.png').' para criar um endereço web para visualização em ambiente externo.').imagem('icones/exporta_p.png').' Exportar Link'.dicaF(), "javascript: void(0);' onclick='exportar_link();");	

	echo $km->Render();
	echo '</td></tr></table>';
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_viabilidade_id" value="'.$obj->projeto_viabilidade_id.'" />';
echo '<input type="hidden" name="canvas_id" id="canvas_id" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '</form>';


if($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $obj->projeto_viabilidade_cia;
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
	echo '<table align="left" cellspacing=0 cellpadding=0 width=100%><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	echo 	'<font size="4"><center>Estudos de Viabilidade</center></font>';
	}

echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%">';


echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_viabilidade_cor.'" colspan="2"><font color="'.melhorCor($obj->projeto_viabilidade_cor).'"><b>'.$obj->projeto_viabilidade_nome.'<b></font></td></tr>';

if ($obj->projeto_viabilidade_viavel) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viável', ucfirst($config['genero_projeto']).' possível '.$config['projeto'].' foi analisado quanto a viabilidade de execução.').'Viável:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;"><b>'.($obj->projeto_viabilidade_viavel==1 ? 'Sim' : 'Não').'</b></td></tr>';
if ($obj->projeto_viabilidade_cia) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', $config['organizacao'].' do estudo de viabilidade.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->projeto_viabilidade_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('projeto_viabilidade_cia');
	$sql->adCampo('projeto_viabilidade_cia_cia');
	$sql->adOnde('projeto_viabilidade_cia_projeto_viabilidade = '.(int)$obj->projeto_viabilidade_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}


if ($obj->projeto_viabilidade_dept) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->projeto_viabilidade_dept).'</td></tr>';

$sql->adTabela('projeto_viabilidade_dept');
$sql->adCampo('projeto_viabilidade_dept_dept');
$sql->adOnde('projeto_viabilidade_dept_projeto_viabilidade = '.(int)$obj->projeto_viabilidade_id);
$lista_depts=$sql->carregarColuna();
$sql->limpar();

$saida_depts='';
if ($lista_depts && count($lista_depts)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($lista_depts[0]);
		$qnt_lista_depts=count($lista_depts);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($lista_depts[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';


$sql->adTabela('projeto_viabilidade_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=projeto_viabilidade_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_viabilidade_id = '.(int)$obj->projeto_viabilidade_id);
$participantes = $sql->Lista();
$sql->limpar();

if ($obj->projeto_viabilidade_responsavel) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Responsável pelo estudo de Viabilidade', ucfirst($config['usuario']).' responsável pelo estudo de viabilidade.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_viabilidade_responsavel, '','','esquerda').'</td></tr>';		

$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Designado', 'Qual '.strtolower($config['usuarios']).' está envolvid'.$config['genero_usuario'].'.').'Designado:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';




$sql->adTabela('projeto_viabilidade_patrocinadores');
$sql->adUnir('contatos','contatos','contatos.contato_id=projeto_viabilidade_patrocinadores.contato_id');
$sql->adCampo('contatos.contato_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_viabilidade_id = '.(int)$obj->projeto_viabilidade_id);
$patrocinadores = $sql->Lista();
$sql->limpar();

$saida_patrocinadores='';
if ($patrocinadores && count($patrocinadores)) {
		$saida_patrocinadores.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_patrocinadores.= '<tr><td>'.link_contato($patrocinadores[0]['contato_id']).($patrocinadores[0]['contato_dept']? ' - '.link_secao($patrocinadores[0]['contato_dept']) : '');
		$qnt_patrocinadores=count($patrocinadores);
		if ($qnt_patrocinadores > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_patrocinadores; $i < $i_cmp; $i++) $lista.=link_contato($patrocinadores[$i]['contato_id']).($patrocinadores[$i]['contato_dept']? ' - '.link_secao($patrocinadores[$i]['contato_dept']) : '').'<br>';		
				$saida_patrocinadores.= dica('Outros Patrocinadores', 'Clique para visualizar os demais patrocinadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'patrocinadores\');">(+'.($qnt_patrocinadores - 1).')</a>'.dicaF(). '<span style="display: none" id="patrocinadores"><br>'.$lista.'</span>';
				}
		$saida_patrocinadores.= '</td></tr></table>';
		} 
if ($saida_patrocinadores) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Patrocinador', 'Qual contato foi elencado como patrocinador.').'Patrocinador:'.dicaF().'</td><td width="100%" class="realce" colspan="2">'.$saida_patrocinadores.'</td></td></tr>';
	
	

$sql->adTabela('projeto_viabilidade_interessados');
$sql->adUnir('contatos','contatos','contatos.contato_id=projeto_viabilidade_interessados.contato_id');
$sql->adCampo('contatos.contato_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_viabilidade_id = '.(int)$obj->projeto_viabilidade_id);
$interessados = $sql->Lista();
$sql->limpar();
$saida_interessados='';
if ($interessados && count($interessados)) {
		$saida_interessados.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_interessados.= '<tr><td>'.link_contato($interessados[0]['contato_id']).($interessados[0]['contato_dept']? ' - '.link_secao($interessados[0]['contato_dept']) : '');
		$qnt_interessados=count($interessados);
		if ($qnt_interessados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_interessados; $i < $i_cmp; $i++) $lista.=link_contato($interessados[$i]['contato_id']).($interessados[$i]['contato_dept']? ' - '.link_secao($interessados[$i]['contato_dept']) : '').'<br>';		
				$saida_interessados.= dica('Outros Interessados', 'Clique para visualizar os demais interessados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'interessados\');">(+'.($qnt_interessados - 1).')</a>'.dicaF(). '<span style="display: none" id="interessados"><br>'.$lista.'</span>';
				}
		$saida_interessados.= '</td></tr></table>';
		} 
if ($saida_interessados) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Parte Interessada', 'Qual contato foi elencado como parte interessada.').'Parte interessada:'.dicaF().'</td><td width="100%" class="realce" colspan="2">'.$saida_interessados.'</td></td></tr>';

	

if (isset($obj->projeto_viabilidade_codigo) && $obj->projeto_viabilidade_codigo) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Código', 'O código do estudo de viabilidade.').'Código:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_viabilidade_codigo.'</td></tr>';
if (isset($obj->projeto_viabilidade_setor) && $obj->projeto_viabilidade_setor) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o estudo de viabilidade.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSetor().'</td></tr>';
if (isset($obj->projeto_viabilidade_segmento) && $obj->projeto_viabilidade_segmento) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o estudo de viabilidade.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSegmento().'</td></tr>';
if (isset($obj->projeto_viabilidade_intervencao) && $obj->projeto_viabilidade_intervencao) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o estudo de viabilidade.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getIntervencao().'</td></tr>';
if (isset($obj->projeto_viabilidade_tipo_intervencao) && $obj->projeto_viabilidade_tipo_intervencao) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o estudo de viabilidade.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getTipoIntervencao().'</td></tr>';
if ($obj->projeto_viabilidade_necessidade) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Necessidade', 'Descrever o problema que se deseja resolver por meio do projeto. Se possível, apresentar dados numéricos que deem sustentação à demanda (custos, desperdício de recursos etc).').'Necessidade:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_necessidade.'</td></tr>';
if ($obj->projeto_viabilidade_alinhamento) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Alinhamento Estratégico', 'Descrever o alinhamento do estudo de viabilidade com os instrumentos de planejamento institucional. Esse item consta no Documento de Oficialização da Demanda (DOD) pode ser complementado/revisado neste documento.').'Alinhamento estratégico:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_alinhamento.'</td></tr>';
if ($obj->projeto_viabilidade_requisitos) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Requisitos Básicos', 'Descrever os principais requisitos identificados para o projeto, a partir da requisição da área solicitante. Os requisitos podem ser: de negócio, tecnológico, recursos humanos, legais, segurança, sociais, ambientais, culturais, etc.').'Requisitos básicos:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_requisitos.'</td></tr>';
if ($obj->projeto_viabilidade_solucoes) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Soluções Possíveis', 'Listar as possibilidades de atendimento da necessidade, com análise das vantagens e desvantagens de cada opção.').'Soluções possíveis:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_solucoes.'</td></tr>';
if ($obj->projeto_viabilidade_viabilidade_tecnica) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viabilidade Técnica', 'Avaliar o estudo de viabilidade técnica do projeto, observando a capacidade técnica da organização para realizar o projeto, estrutura física (material e estrutural) e de pessoal (conhecimento técnico).').'Viabilidade técnica:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_viabilidade_tecnica.'</td></tr>';
if ($obj->projeto_viabilidade_financeira) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viabilidade Financeira', 'Levantar e avaliar os custos estimados para cada solução possível e verificar a disponibilidade orçamentária para a execução do projeto.').'Viabilidade financeira:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_financeira.'</td></tr>';
if ($obj->projeto_viabilidade_institucional) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Viabilidade Institucional', 'Avaliar ambiente institucional, o que inclui o clima político e organizacional para a realização do projeto, identificando possíveis entraves e oportunidades, assim como o impacto dos resultados do projeto sobre as rotinas da instituição.').'Viabilidade institucional:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_institucional.'</td></tr>';
if ($obj->projeto_viabilidade_solucao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicação de Solução', 'Indicar a solução escolhida estimando o tempo  para implantação da solução e justificá-la, observando o alinhamento da estratégia da organização e a necessidade de negócio.').'Indicação de solução:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_solucao.'</td></tr>';
if ($obj->projeto_viabilidade_continuidade) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Parecer Sobre a Continuidade', 'Os envolvidos na elaboração deste documento deverão deliberar sobre a continuidade ou não do projeto e justificar. Obs. colocar a data da decisão e descrever o nome dos decisores e seus respectivos cargos.').'Parecer sobre a continuidade:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_continuidade.'</td></tr>';

if ($obj->projeto_viabilidade_tempo) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Parecer Sobre o Tempo', 'Os envolvidos na elaboração deste documento deverão deliberar sobre o tempo necessário para a execução do projeto.').'Parecer sobre o tempo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_tempo.'</td></tr>';
if ($obj->projeto_viabilidade_custo) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Parecer Sobre o Custo', 'Os envolvidos na elaboração deste documento deverão deliberar sobre o custo do projeto.').'Parecer sobre o custo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_custo.'</td></tr>';
if ($obj->projeto_viabilidade_observacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Observações', 'Observações sobre o estudo de viabilidade.').'Observações:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_viabilidade_observacao.'</td></tr>';


if ($obj->projeto_viabilidade_demanda) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Demanda', 'Visualização dos detalhes da demanda que originou este estudo de viabilidade.').'Demanda:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_demanda($obj->projeto_viabilidade_demanda).'</td></tr>';				
if ($demanda['demanda_termo_abertura']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Termo de Abertura', 'Visualização dos detalhes do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_termo_abertura($demanda['demanda_termo_abertura']).'</td></tr>';				
if ($obj->projeto_viabilidade_projeto) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['projeto']), 'Visualização dos detalhes do '.$config['projeto'].' que foi criado basead'.$config['genero_projeto'].' neste estudo de viabilidade.').ucfirst($config['projeto']).':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_projeto($obj->projeto_viabilidade_projeto).'</td></tr>';				


$custo_estimado=($Aplic->profissional ? $obj->custo_estimado() : 0);
if ($custo_estimado > 0) echo '<tr><td align="right" style="white-space: nowrap;width:110px;">'.dica('Planilha de Custos', 'A planilha de custos é a soma dos valores dos itens.').'Planilha de custos:'.dicaF().'</td><td class="realce" width="300">'.$moedas[$obj->projeto_viabilidade_moeda].' '.number_format($custo_estimado, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:window.parent.gpwebApp.popUp(\'Planilha\', 1024, 600, \'m=projetos&a=viabilidade_planilha&dialogo=1&projeto_viabilidade_id='.$obj->projeto_viabilidade_id.'\', null, window);">'.dica('Planilha de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar a planilha de custos estimados.').imagem('icones/planilha_estimado.gif').dicaF().'</a></td></tr>';









$sql->adTabela('projeto_viabilidade_gestao');
$sql->adCampo('projeto_viabilidade_gestao.*');
$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade ='.(int)$obj->projeto_viabilidade_id);	
$sql->adOrdem('projeto_viabilidade_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
$qnt_gestao=0;

if (count($lista)) {
	echo '<tr><td align="right" style="white-space: nowrap" valign="middle">'.dica('Relacionado', 'A que área este estudo de viabilidade está relacionado.').'Relacionado:'.dicaF().'</td></td><td class="realce">';	
	foreach($lista as $gestao_data){
		if ($gestao_data['projeto_viabilidade_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_viabilidade_gestao_tarefa']);
		elseif ($gestao_data['projeto_viabilidade_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_viabilidade_gestao_projeto']);
		elseif ($gestao_data['projeto_viabilidade_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_viabilidade_gestao_pratica']);
		elseif ($gestao_data['projeto_viabilidade_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_viabilidade_gestao_acao']);
		elseif ($gestao_data['projeto_viabilidade_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_viabilidade_gestao_perspectiva']);
		elseif ($gestao_data['projeto_viabilidade_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['projeto_viabilidade_gestao_tema']);
		elseif ($gestao_data['projeto_viabilidade_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_viabilidade_gestao_objetivo']);
		elseif ($gestao_data['projeto_viabilidade_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_viabilidade_gestao_fator']);
		elseif ($gestao_data['projeto_viabilidade_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_viabilidade_gestao_estrategia']);
		elseif ($gestao_data['projeto_viabilidade_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_viabilidade_gestao_meta']);
		elseif ($gestao_data['projeto_viabilidade_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_viabilidade_gestao_canvas']);
		elseif ($gestao_data['projeto_viabilidade_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['projeto_viabilidade_gestao_risco']);
		elseif ($gestao_data['projeto_viabilidade_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_viabilidade_gestao_risco_resposta']);
		elseif ($gestao_data['projeto_viabilidade_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_viabilidade_gestao_indicador']);
		elseif ($gestao_data['projeto_viabilidade_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_viabilidade_gestao_calendario']);
		elseif ($gestao_data['projeto_viabilidade_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_viabilidade_gestao_monitoramento']);
		elseif ($gestao_data['projeto_viabilidade_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_viabilidade_gestao_ata']);
		elseif ($gestao_data['projeto_viabilidade_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_viabilidade_gestao_mswot']);
		elseif ($gestao_data['projeto_viabilidade_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['projeto_viabilidade_gestao_swot']);
		elseif ($gestao_data['projeto_viabilidade_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_viabilidade_gestao_operativo']);
		elseif ($gestao_data['projeto_viabilidade_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_viabilidade_gestao_instrumento']);
		elseif ($gestao_data['projeto_viabilidade_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_viabilidade_gestao_recurso']);
		elseif ($gestao_data['projeto_viabilidade_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['projeto_viabilidade_gestao_problema']);
		elseif ($gestao_data['projeto_viabilidade_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_viabilidade_gestao_demanda']);	
		elseif ($gestao_data['projeto_viabilidade_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['projeto_viabilidade_gestao_programa']);
		elseif ($gestao_data['projeto_viabilidade_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_viabilidade_gestao_licao']);
		elseif ($gestao_data['projeto_viabilidade_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_viabilidade_gestao_evento']);
		elseif ($gestao_data['projeto_viabilidade_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['projeto_viabilidade_gestao_link']);
		elseif ($gestao_data['projeto_viabilidade_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_viabilidade_gestao_avaliacao']);
		elseif ($gestao_data['projeto_viabilidade_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_viabilidade_gestao_tgn']);
		elseif ($gestao_data['projeto_viabilidade_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_viabilidade_gestao_brainstorm']);
		elseif ($gestao_data['projeto_viabilidade_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_viabilidade_gestao_gut']);
		elseif ($gestao_data['projeto_viabilidade_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_viabilidade_gestao_causa_efeito']);
		elseif ($gestao_data['projeto_viabilidade_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_viabilidade_gestao_arquivo']);
		elseif ($gestao_data['projeto_viabilidade_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_viabilidade_gestao_forum']);
		elseif ($gestao_data['projeto_viabilidade_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_viabilidade_gestao_checklist']);
		elseif ($gestao_data['projeto_viabilidade_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_viabilidade_gestao_agenda']);
		elseif ($gestao_data['projeto_viabilidade_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_viabilidade_gestao_agrupamento']);
		elseif ($gestao_data['projeto_viabilidade_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_viabilidade_gestao_patrocinador']);
		elseif ($gestao_data['projeto_viabilidade_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['projeto_viabilidade_gestao_template']);
		elseif ($gestao_data['projeto_viabilidade_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['projeto_viabilidade_gestao_painel']);
		elseif ($gestao_data['projeto_viabilidade_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_viabilidade_gestao_painel_odometro']);
		elseif ($gestao_data['projeto_viabilidade_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_viabilidade_gestao_painel_composicao']);		
		elseif ($gestao_data['projeto_viabilidade_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['projeto_viabilidade_gestao_tr']);	
		elseif ($gestao_data['projeto_viabilidade_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['projeto_viabilidade_gestao_me']);	
		elseif ($gestao_data['projeto_viabilidade_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_viabilidade_gestao_acao_item']);	
		elseif ($gestao_data['projeto_viabilidade_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_viabilidade_gestao_beneficio']);	
		elseif ($gestao_data['projeto_viabilidade_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_viabilidade_gestao_painel_slideshow']);	
		
		elseif ($gestao_data['projeto_viabilidade_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_viabilidade_gestao_semelhante']);	
		
		elseif ($gestao_data['projeto_viabilidade_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_viabilidade_gestao_projeto_abertura']);	
		elseif ($gestao_data['projeto_viabilidade_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_viabilidade_gestao_plano_gestao']);	
		elseif ($gestao_data['projeto_viabilidade_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_viabilidade_gestao_ssti']);
		elseif ($gestao_data['projeto_viabilidade_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_viabilidade_gestao_laudo']);
		elseif ($gestao_data['projeto_viabilidade_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_viabilidade_gestao_trelo']);
		elseif ($gestao_data['projeto_viabilidade_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_viabilidade_gestao_trelo_cartao']);
		elseif ($gestao_data['projeto_viabilidade_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_viabilidade_gestao_pdcl']);
		elseif ($gestao_data['projeto_viabilidade_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_viabilidade_gestao_pdcl_item']);
		
		}
	echo '</td></tr>';
	}	

if ($Aplic->profissional && $exibir['moeda'] && isset($moedas[$obj->projeto_viabilidade_moeda])) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'A moeda padrão utilizada na demanda.').'Moeda:'.dicaF().'</td><td class="realce" width="100%">'.$moedas[$obj->projeto_viabilidade_moeda].'</td></tr>';

if ($Aplic->profissional && $tem_aprovacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Aprovado', 'Se o estudo de viabilidade se encontra aprovado.').'Aprovado:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->projeto_viabilidade_aprovado ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ativo', 'O estudo de viabilidade se encontra ativo.').'Ativo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->projeto_viabilidade_ativo ? 'Sim' : 'Não').'</td></tr>';
		
	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('viabilidade', $obj->projeto_viabilidade_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}			
					
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/viabilidade_ver_pro.php';		
		
		
echo '</table></td></tr></table>';


if (!$dialogo){
	$caixaTab = new CTabBox('m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$obj->projeto_viabilidade_id, '', $tab);
	$qnt_aba=0;
	
	if ($Aplic->checarModulo('log', 'acesso')) {
		$sql->adTabela('log');
		$sql->adCampo('count(log_id)');
		$sql->adOnde('log_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver', 'Registro',null,null,'Registro das Ocorrência','Visualizar o registro de ocorrência relacionado.');
			}
		}

	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {

			$sql->adTabela('evento_gestao','evento_gestao');
			$sql->adOnde('evento_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
			$sql->adOnde('evento_gestao_evento IS NOT NULL');
			$sql->adCampo('count(evento_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$data_inicio=null;
			$data_fim=null;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Evento',null,null,'Evento','Visualizar o evento relacionado.');
			}
		}
		
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) {

			$sql->adTabela('arquivo_gestao','arquivo_gestao');
			$sql->adOnde('arquivo_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
			$sql->adOnde('arquivo_gestao_arquivo IS NOT NULL');
			$sql->adCampo('count(arquivo_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/arquivos/index_tabela', 'Arquivo',null,null,'Arquivo','Visualizar o arquivo relacionado.');
			}
		}
	
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) {

			$sql->adTabela('pratica_indicador_gestao','pratica_indicador_gestao');
			$sql->adOnde('pratica_indicador_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
			$sql->adOnde('pratica_indicador_gestao_indicador IS NOT NULL');
			$sql->adCampo('count(pratica_indicador_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicador',null,null,'Indicador','Visualizar o indicador relacionado.');
			}
		}
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) {

			$sql->adTabela('plano_acao_gestao','plano_acao_gestao');
			$sql->adOnde('plano_acao_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
			$sql->adOnde('plano_acao_gestao_acao IS NOT NULL');
			$sql->adCampo('count(plano_acao_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acao']),null,null,ucfirst($config['acao']),'Visualizar '.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao_item')) {
		$sql->adTabela('plano_acao_item_gestao');
		$sql->adOnde('plano_acao_item_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('plano_acao_item_gestao_plano_acao_item IS NOT NULL');
		$sql->adCampo('count(plano_acao_item_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_itens_idx','Item de '.$config['acao'],null,null,'Item de '.$config['acao'],'Visualizar o item de '.$config['acao'].' relacionado.');
			}
		}	
		
	if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) {

			$sql->adTabela('projeto_gestao');
			$sql->adOnde('projeto_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
			$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=projeto_gestao_projeto');
			$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
			$sql->adOnde('projeto_portfolio=0');
			$sql->adCampo('count(projeto_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos', ucfirst($config['projeto']),null,null,ucfirst($config['projeto']),'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.');
			}
		
		
		
		$sql->adTabela('projeto_gestao');
		$sql->adOnde('projeto_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
		$sql->esqUnir('projetos', 'projetos', 'projeto_id=projeto_gestao_projeto');
		$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
		$sql->adOnde('projeto_portfolio=1');
		$sql->adCampo('count(projeto_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_portifolio_pro', ucfirst($config['portfolio']),null,null,ucfirst($config['portfolio']),'Visualizar '.$config['genero_portfolio'].' '.$config['portfolio'].' relacionad'.$config['genero_portfolio'].'.');
			}	
		
		}		
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) {
		$sql->adTabela('ata_gestao','ata_gestao');
		$sql->adOnde('ata_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('ata_gestao_ata IS NOT NULL');
		$sql->adCampo('count(ata_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Ata',null,null,'Ata','Visualizar a ata de reunião relacionada.');
			}
		}
			
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'demanda')) {
		$sql->adTabela('demanda_gestao');
		$sql->adOnde('demanda_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('demanda_gestao_demanda IS NOT NULL');
		$sql->adCampo('count(demanda_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/demanda_tabela','Demanda',null,null,'Demanda','Visualizar a demanda relacionada.');
			}
		}				
			
	if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$sql->adTabela('msg_gestao','msg_gestao');
		$sql->adOnde('msg_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('msg_gestao_msg IS NOT NULL');
		$sql->adCampo('count(msg_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
				$qnt_aba++;
				$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg', ucfirst($config['mensagem']),null,null,ucfirst($config['mensagem']),'Visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.');
				}
		if ($config['doc_interno']) {
			$sql->adTabela('modelo_gestao','modelo_gestao');
			$sql->adOnde('modelo_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
			$sql->adOnde('modelo_gestao_modelo IS NOT NULL');
			$sql->adCampo('count(modelo_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			if ($existe) {
				$qnt_aba++;
				$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo', 'Documento',null,null,'Documento','Visualizar o documento relacionado.');
				}
			}
		}	
		
	if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) {

			$sql->adTabela('link_gestao','link_gestao');
			$sql->adOnde('link_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
			$sql->adOnde('link_gestao_link IS NOT NULL');
			$sql->adCampo('count(link_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Link',null,null,'Link','Visualizar o link relacionado.');
			}
		}
	
	if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) {

			$sql->adTabela('forum_gestao','forum_gestao');
			$sql->adOnde('forum_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
			$sql->adOnde('forum_gestao_forum IS NOT NULL');
			$sql->adCampo('count(forum_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fórum',null,null,'Fórum','Visualizar o fórum relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) {
		$sql->adTabela('problema_gestao','problema_gestao');
		$sql->adOnde('problema_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('problema_gestao_problema IS NOT NULL');
		$sql->adCampo('count(problema_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problema']),null,null,ucfirst($config['problema']),'Visualizar '.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) {
		$sql->adTabela('risco_gestao','risco_gestao');
		$sql->adOnde('risco_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('risco_gestao_risco IS NOT NULL');
		$sql->adCampo('count(risco_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['risco']),null,null,ucfirst($config['risco']),'Visualizar '.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco_resposta')) {		
		$sql->adTabela('risco_resposta_gestao');
		$sql->esqUnir('risco_resposta','risco_resposta', 'risco_resposta_id=risco_resposta_gestao_risco_resposta');
		$sql->adOnde('risco_resposta_ativo=1');
		$sql->adOnde('risco_resposta_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('risco_resposta_gestao_risco_resposta IS NOT NULL');
		$sql->adCampo('count(risco_resposta_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_resposta_pro_ver_idx', ucfirst($config['risco_resposta']),null,null,ucfirst($config['risco_resposta']),'Visualizar '.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.');
			}
		}

	if ($Aplic->modulo_ativo('instrumento')  && $Aplic->checarModulo('instrumento', 'acesso')) {
		$sql->adTabela('instrumento_gestao','instrumento_gestao');
		$sql->adOnde('instrumento_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('instrumento_gestao_instrumento IS NOT NULL');
		$sql->adCampo('count(instrumento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/instrumento/instrumento_lista_idx', ucfirst($config['instrumento']),null,null,ucfirst($config['instrumento']),'Visualizar '.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.');
			}
		}
	
	if ($Aplic->checarModulo('recursos', 'acesso', null, null)) {
		$sql->adTabela('recurso_gestao');
		$sql->adOnde('recurso_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('recurso_gestao_recurso IS NOT NULL');
		$sql->adCampo('count(recurso_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/ver_recursos', ucfirst($config['recurso']),null,null,ucfirst($config['recurso']),'Visualizar '.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.');
			}
		}
		
	if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'acesso', null, null)) {
		$sql->adTabela('patrocinador_gestao');
		$sql->adOnde('patrocinador_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('patrocinador_gestao_patrocinador IS NOT NULL');
		$sql->adCampo('count(patrocinador_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/patrocinadores/patrocinador_ver_idx',ucfirst($config['patrocinador']),null,null,ucfirst($config['patrocinador']),'Visualizar '.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.');
			}
		}
			
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'programa')) {
		$sql->adTabela('programa_gestao');
		$sql->adOnde('programa_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('programa_gestao_programa IS NOT NULL');
		$sql->adCampo('count(programa_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/programa_pro_ver_idx', ucfirst($config['programa']),null,null,ucfirst($config['programa']),'Visualizar '.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.');
			}
		}	
			
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'beneficio')) {
		$sql->adTabela('beneficio_gestao');
		$sql->adOnde('beneficio_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('beneficio_gestao_beneficio IS NOT NULL');
		$sql->adCampo('count(beneficio_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/beneficio_pro_ver_idx',ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.$config['programa'],null,null,ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.$config['programa'],'Visualizar '.$config['genero_beneficio'].' '.$config['beneficio'].' d'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_beneficio'].'.');
			}
		}		
	
	if ($Aplic->checarModulo('projeto', 'acesso', 'licao')) {
		$sql->adTabela('licao_gestao','licao_gestao');
		$sql->adOnde('licao_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('licao_gestao_licao IS NOT NULL');
		$sql->adCampo('count(licao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/licao_tabela', ucfirst($config['licao']),null,null,ucfirst($config['licao']),'Visualizar '.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.');
			}
		}	
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'pratica')) {
		$sql->adTabela('pratica_gestao');
		$sql->adOnde('pratica_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('pratica_gestao_pratica IS NOT NULL');
		$sql->adCampo('count(pratica_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/praticas_ver_idx', ucfirst($config['pratica']),null,null,ucfirst($config['pratica']),'Visualizar '.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'acesso', null, null)) {
		$sql->adTabela('tr_gestao');
		$sql->adOnde('tr_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('tr_gestao_tr IS NOT NULL');
		$sql->adCampo('count(tr_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/tr/tr_tabela','Termo de Referência',null,null,'Termo de Referência','Visualizar o termos de referência relacionado.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'brainstorm')) {
		$sql->adTabela('brainstorm_gestao');
		$sql->adOnde('brainstorm_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('brainstorm_gestao_brainstorm IS NOT NULL');
		$sql->adCampo('count(brainstorm_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/brainstorm_ver_idx','Brainstorm',null,null,'Brainstorm','Visualizar o brainstorm relacionado.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'gut')) {
		$sql->adTabela('gut_gestao');
		$sql->adOnde('gut_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('gut_gestao_gut IS NOT NULL');
		$sql->adCampo('count(gut_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/gut_ver_idx','GUT',null,null,'GUT','Visualizar a matriz G.U.T. relacionada.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'causa_efeito')) {
		$sql->adTabela('causa_efeito_gestao');
		$sql->adOnde('causa_efeito_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('causa_efeito_gestao_causa_efeito IS NOT NULL');
		$sql->adCampo('count(causa_efeito_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/causa_efeito_ver_idx','Causa-Efeito',null,null,'Causa-Efeito','Visualizar o diagrama de causa-efeito relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'tgn')) {
		$sql->adTabela('tgn_gestao');
		$sql->adOnde('tgn_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('tgn_gestao_tgn IS NOT NULL');
		$sql->adCampo('count(tgn_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/tgn_pro_ver_idx', ucfirst($config['tgn']),null,null,ucfirst($config['tgn']),'Visualizar '.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'canvas')) {
		$sql->adTabela('canvas_gestao');
		$sql->adOnde('canvas_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('canvas_gestao_canvas IS NOT NULL');
		$sql->adCampo('count(canvas_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/canvas_pro_ver_idx', ucfirst($config['canvas']),null,null,ucfirst($config['canvas']),'Visualizar '.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null)) {
		$sql->adTabela('mswot_gestao');
		$sql->adOnde('mswot_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('mswot_gestao_mswot IS NOT NULL');
		$sql->adCampo('count(mswot_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/swot/mswot_tabela','Matriz SWOT',null,null,'Matriz SWOT','Visualizar a matriz SWOT relacionada.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null)) {
		$sql->adTabela('swot_gestao');
		$sql->adOnde('swot_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('swot_gestao_swot IS NOT NULL');
		$sql->adCampo('count(swot_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/swot/swot_tabela','Campo SWOT',null,null,'Campo SWOT','Visualizar o campos SWOT relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'acesso', null, null)) {
		$sql->adTabela('operativo_gestao');
		$sql->adOnde('operativo_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('operativo_gestao_operativo IS NOT NULL');
		$sql->adCampo('count(operativo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/operativo/operativo_tabela','Plano Operativo',null,null,'Plano Operativo','Visualizar o plano operativo relacionado.');
			}
		}	
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'monitoramento')) {
		$sql->adTabela('monitoramento_gestao');
		$sql->adOnde('monitoramento_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('monitoramento_gestao_monitoramento IS NOT NULL');
		$sql->adCampo('count(monitoramento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/monitoramento_ver_idx_pro','Monitoramento',null,null,'Monitoramento','Visualizar o monitoramento relacionado.');
			}
		}
	
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'avaliacao_indicador')) {
		$sql->adTabela('avaliacao_gestao');
		$sql->adOnde('avaliacao_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('avaliacao_gestao_avaliacao IS NOT NULL');
		$sql->adCampo('count(avaliacao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/avaliacao_ver_idx','Avaliação',null,null,'Avaliação','Visualizar a avaliação de indicadores relacionada.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'checklist')) {
		$sql->adTabela('checklist_gestao');
		$sql->adOnde('checklist_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('checklist_gestao_checklist IS NOT NULL');
		$sql->adCampo('count(checklist_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/checklist_ver_idx','Checklist',null,null,'Checklist','Visualizar o checklist relacionado.');
			}
		}	
	
	if ($Aplic->profissional) {
		$sql->adTabela('agenda_gestao');
		$sql->adOnde('agenda_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('agenda_gestao_agenda IS NOT NULL');
		$sql->adCampo('count(agenda_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/email/compromisso_ver_idx_pro','Compromisso',null,null,'Compromisso','Visualizar o compromisso relacionado.');
			}
		}	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'acesso', null, null)) {
		$sql->adTabela('agrupamento_gestao');
		$sql->adOnde('agrupamento_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('agrupamento_gestao_agrupamento IS NOT NULL');
		$sql->adCampo('count(agrupamento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/agrupamento/agrupamento_tabela','Agrupamento',null,null,'Agrupamento','Visualizar o agrupamento relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'modelo')) {
		$sql->adTabela('template_gestao');
		$sql->adOnde('template_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('template_gestao_template IS NOT NULL');
		$sql->adCampo('count(template_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/template_pro_ver_idx','Modelo',null,null,'Modelo','Visualizar o modelo de '.$config['projeto'].' relacionado.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'painel_indicador')) {
		$sql->adTabela('painel_gestao');
		$sql->adOnde('painel_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('painel_gestao_painel IS NOT NULL');
		$sql->adCampo('count(painel_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_pro_lista_idx','Painel',null,null,'Painel','Visualizar o painel de indicador relacionado.');
			}
		}		
		
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'odometro_indicador')) {
		$sql->adTabela('painel_odometro_gestao');
		$sql->adOnde('painel_odometro_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('painel_odometro_gestao_painel_odometro IS NOT NULL');
		$sql->adCampo('count(painel_odometro_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/odometro_pro_lista_idx','Odômetro',null,null,'Odômetro','Visualizar o odômetro de indicador relacionado.');
			}
		}				
		
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'composicao_painel')) {
		$sql->adTabela('painel_composicao_gestao');
		$sql->adOnde('painel_composicao_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('painel_composicao_gestao_painel_composicao IS NOT NULL');
		$sql->adCampo('count(painel_composicao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_composicao_pro_lista_idx','Composição de painéis',null,null,'Composição de painéis','Visualizar a composição de painéis relacionada.');
			}
		}	
			
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'slideshow_painel')) {
		$sql->adTabela('painel_slideshow_gestao');
		$sql->adOnde('painel_slideshow_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('painel_slideshow_gestao_painel_slideshow IS NOT NULL');
		$sql->adCampo('count(painel_slideshow_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_slideshow_pro_lista_idx','Slideshow',null,null,'Slideshow','Visualizar o slideshow de painéis relacionado.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->checarModulo('agenda', 'acesso', null, null)) {
		$sql->adTabela('calendario_gestao');
		$sql->adOnde('calendario_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('calendario_gestao_calendario IS NOT NULL');
		$sql->adCampo('count(calendario_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/sistema/calendario/calendario_ver_idx','Agenda Coletiva',null,null,'Agenda Coletiva','Visualizar a agendas coletiva relacionada.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'perspectiva')) {
		$sql->adTabela('perspectiva_gestao');
		$sql->adOnde('perspectiva_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('perspectiva_gestao_perspectiva IS NOT NULL');
		$sql->adCampo('count(perspectiva_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/perspectivas_ver_idx', ucfirst($config['perspectiva']),null,null,ucfirst($config['perspectiva']),'Visualizar '.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.');
			}
		}		
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'tema')) {
		$sql->adTabela('tema_gestao');
		$sql->adOnde('tema_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('tema_gestao_tema IS NOT NULL');
		$sql->adCampo('count(tema_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/tema_ver_idx', ucfirst($config['tema']),null,null,ucfirst($config['tema']),'Visualizar '.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.');
			}
		}				
		

	if ($Aplic->checarModulo('praticas', 'acesso', null, 'objetivo')) {
		$sql->adTabela('objetivo_gestao');
		$sql->adOnde('objetivo_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('objetivo_gestao_objetivo IS NOT NULL');
		$sql->adCampo('count(objetivo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/obj_estrategicos_ver_idx', ucfirst($config['objetivo']),null,null,ucfirst($config['objetivo']),'Visualizar '.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'me')) {
		$sql->adTabela('me_gestao');
		$sql->adOnde('me_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('me_gestao_me IS NOT NULL');
		$sql->adCampo('count(me_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/me_ver_idx_pro', ucfirst($config['me']),null,null,ucfirst($config['me']),'Visualizar '.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.');
			}
		}	
		
	if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'acesso', null, 'fator')) {
		$sql->adTabela('fator_gestao');
		$sql->adOnde('fator_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('fator_gestao_fator IS NOT NULL');
		$sql->adCampo('count(fator_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/fatores_ver_idx', ucfirst($config['fator']),null,null,ucfirst($config['fator']),'Visualizar '.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'iniciativa')) {
		$sql->adTabela('estrategia_gestao');
		$sql->adOnde('estrategia_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('estrategia_gestao_estrategia IS NOT NULL');
		$sql->adCampo('count(estrategia_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/estrategias_ver_idx', ucfirst($config['iniciativa']),null,null,ucfirst($config['iniciativa']),'Visualizar '.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.');
			}
		}
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'meta')) {
		$sql->adTabela('meta_gestao');
		$sql->adOnde('meta_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('meta_gestao_meta IS NOT NULL');
		$sql->adCampo('count(meta_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/metas_ver_idx', ucfirst($config['meta']),null,null,ucfirst($config['meta']),'Visualizar '.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento')) {
		$sql->adTabela('plano_gestao_gestao');
		$sql->adOnde('plano_gestao_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('plano_gestao_gestao_plano_gestao IS NOT NULL');
		$sql->adCampo('count(plano_gestao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/gestao/gestao_tabela','Planejamento estratégico',null,null,'Planejamento estratégico','Visualizar o planejamento estratégico relacionado.');
			}
		}				

	if ($Aplic->checarModulo('projetos', 'acesso', null, 'abertura')) {
		$sql->adTabela('projeto_abertura_gestao');
		$sql->adOnde('projeto_abertura_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('projeto_abertura_gestao_projeto_abertura IS NOT NULL');
		$sql->adCampo('count(projeto_abertura_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/termo_abertura_tabela','Termo de abertura',null,null,'Termo de abertura','Visualizar o YYY relacionado.');
			}
		}			
			
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'viabilidade')) {
		$sql->adTabela('projeto_viabilidade_gestao');
		$sql->adOnde('projeto_viabilidade_gestao_semelhante = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade IS NOT NULL');
		$sql->adCampo('count(projeto_viabilidade_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/viabilidade_tabela','Estudo de viabilidade',null,null,'Estudo de viabilidade','Visualizar o estudo de viabilidade relacionado.');
			}
		}		
		
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso')) {
		$sql->adTabela('ssti_gestao','ssti_gestao');
		$sql->adOnde('ssti_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('ssti_gestao_ssti IS NOT NULL');
		$sql->adCampo('count(ssti_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/ssti/ssti_tabela', ucfirst($config['ssti']),null,null,ucfirst($config['ssti']),'Visualizar '.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso',null, 'laudo')) {
		$sql->adTabela('laudo_gestao','laudo_gestao');
		$sql->adOnde('laudo_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('laudo_gestao_laudo IS NOT NULL');
		$sql->adCampo('count(laudo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/ssti/laudo_tabela', ucfirst($config['laudo']),null,null,ucfirst($config['laudo']),'Visualizar '.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'acesso')) {
		$sql->adTabela('trelo_gestao','trelo_gestao');
		$sql->adOnde('trelo_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('trelo_gestao_trelo IS NOT NULL');
		$sql->adCampo('count(trelo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/trelo/trelo_tabela', ucfirst($config['trelo']),null,null,ucfirst($config['trelo']),'Visualizar '.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.');
			}
		}	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'acesso')) {
		$sql->adTabela('trelo_cartao_gestao','trelo_cartao_gestao');
		$sql->adOnde('trelo_cartao_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('trelo_cartao_gestao_trelo_cartao IS NOT NULL');
		$sql->adCampo('count(trelo_cartao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/trelo/trelo_cartao_tabela', ucfirst($config['trelo_cartao']),null,null,ucfirst($config['trelo_cartao']),'Visualizar '.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'acesso')) {
		$sql->adTabela('pdcl_gestao','pdcl_gestao');
		$sql->adOnde('pdcl_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('pdcl_gestao_pdcl IS NOT NULL');
		$sql->adCampo('count(pdcl_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/pdcl/pdcl_tabela', ucfirst($config['pdcl']),null,null,ucfirst($config['pdcl']),'Visualizar '.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'acesso')) {
		$sql->adTabela('pdcl_item_gestao','pdcl_item_gestao');
		$sql->adOnde('pdcl_item_gestao_projeto_viabilidade = '.(int)$projeto_viabilidade_id);
		$sql->adOnde('pdcl_item_gestao_pdcl_item IS NOT NULL');
		$sql->adCampo('count(pdcl_item_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/pdcl/pdcl_item_tabela', ucfirst($config['pdcl_item']),null,null,ucfirst($config['pdcl_item']),'Visualizar '.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.');
			}
		}		
		
	if($qnt_aba){	
		$caixaTab->mostrar('','','','',true);
		}
	echo estiloFundoCaixa();
	}		
	

?>
<script type="text/javascript">

function exportar_link() {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link&dialogo=1&tipo=generico', null, window);
	}

function excluir() {
	if (confirm('Tem certeza que deseja excluir este estudo de viabilidade')) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql_viabilidade';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+<?php echo $obj->projeto_viabilidade_cia?>, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('projeto_viabilidade_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	if (chave > 0) {
		document.env.canvas_id.value=chave;
		document.env.a.value='termo_abertura_canvas_pro';
		document.env.submit();
		}
	}
<?php }?>		
	
	
</script>