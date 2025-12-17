<?php 
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$pratica_id = intval(getParam($_REQUEST, 'pratica_id', 0));



$sql = new BDConsulta;
$sql->adTabela('pratica_requisito');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);
asort($anos);

if (isset($_REQUEST['IdxPraticaAno'])) $Aplic->setEstado('IdxPraticaAno', getParam($_REQUEST, 'IdxPraticaAno', null));
$ano = ($Aplic->getEstado('IdxPraticaAno') !== null && isset($anos[$Aplic->getEstado('IdxPraticaAno')])  ? $Aplic->getEstado('IdxPraticaAno') : $ultimo_ano);

$sql->adTabela('praticas');
$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id = praticas.pratica_id');
$sql->adOnde('praticas.pratica_id='.(int)$pratica_id);
if ($ano) $sql->adOnde('ano = '.(int)$ano);
$pratica=$sql->Linha();
$sql->limpar();


if(!(permiteAcessarPratica($pratica['pratica_acesso'],$pratica_id) && $Aplic->checarModulo('praticas', 'acesso', $Aplic->usuario_id, 'pratica'))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'pratica');
$podeAdicionar=$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'pratica');
$podeExcluir=$Aplic->checarModulo('praticas', 'excluir', $Aplic->usuario_id, 'pratica');



$editar=permiteEditarPratica($pratica['pratica_acesso'],$pratica_id);

$podeEditarTudo=($pratica['pratica_acesso']>=5 ? $editar && (in_array($pratica['pratica_responsavel'], $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'pratica\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


if ($Aplic->profissional){
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_pratica='.(int)$pratica_id);
	$assinar = $sql->linha();
	$sql->limpar();
	
	//tem assinatura que aprova
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_pratica='.(int)$pratica_id);
	$tem_aprovacao = $sql->resultado();
	$sql->limpar();
	}

if (isset($_REQUEST['tab'])) $Aplic->setEstado('PraticaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('PraticaVerTab') !== null ? $Aplic->getEstado('PraticaVerTab') : 0;
$msg = '';

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);
$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos_pauta=array(null=>'')+$sql->ListaChave();
$sql->limpar();
//$modelos[0]='';


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="pratica_ver" />';
echo '<input type="hidden" name="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';

//verifica se já é melhor pratica
$sql->adTabela('melhores_praticas');
$sql->adCampo('count(pratica_id)');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$existe_melhor_pratica=$sql->resultado();
$sql->limpar();

//campos utilizados na regua específica	
$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=0 OR pratica_regra_campo_resultado IS NULL');
$vetor_campos=$sql->carregarColuna();
$sql->limpar();
$ponto_forte=0;	
foreach($vetor_campos as $campo) {
	if (isset($pratica[$campo]) && (($pratica[$campo] && $campo!='pratica_incoerente') || (!$pratica[$campo] && $campo=='pratica_incoerente'))) $ponto_forte++;
	}

$oim=0;	
foreach($vetor_campos as $campo) {
	if (isset($pratica[$campo]) && ((!$pratica[$campo] && $campo!='pratica_incoerente') || ($pratica[$campo] && $campo=='pratica_incoerente')))$oim++;
	}


if (!$dialogo){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'pratica.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #212121">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['praticas']),'Clique neste botão para visualizar a lista de '.$config['praticas'].'.').'Lista de '.ucfirst($config['praticas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_lista\");");
	
	
	if (($podeEditar && $editar) || $podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar)	$km->Add("inserir","inserir_objeto",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Criar um nov'.$config['genero_pratica'].' '.$config['pratica'].'.').'Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar\");");
	if ($podeEditar && $editar) {
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar&pratica_id=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) $km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_pratica=".$pratica_id."\");");	
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'demanda')) $km->Add("inserir","inserir_demanda",dica('Nova Demanda', 'Inserir uma nova demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar&demanda_pratica=".$pratica_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_pratica=".$pratica_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		$km->Add("inserir","diverso",dica('Diversos','Menu de objetos diversos').'Diversos'.dicaF(), "javascript: void(0);'");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("diverso","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("diverso","inserir_forum",dica('Novo Fórum', 'Inserir um novo fórum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("diverso","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("diverso","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'resposta_risco')) $km->Add("diverso","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'adicionar', null, null)) $km->Add("diverso","inserir_instrumento",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Inserir um'.($config['genero_instrumento']=='a' ? 'a' : '').' nov'.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar&instrumento_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('recursos', 'adicionar', null, null)) $km->Add("diverso","inserir_recurso",dica('Nov'.$config['genero_recurso'].' '.ucfirst($config['recurso']), 'Inserir um'.($config['genero_recurso']=='a' ? 'a' : '').' nov'.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.').ucfirst($config['recurso']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar&recurso_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'adicionar', null, null)) $km->Add("diverso","inserir_patrocinador",dica('Nov'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Inserir '.($config['genero_patrocinador']=='o' ? 'um' : 'uma').' nov'.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.').ucfirst($config['patrocinador']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=patrocinadores&a=patrocinador_editar&patrocinador_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'programa')) $km->Add("diverso","inserir_programa",dica('Nov'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Inserir um'.($config['genero_programa']=='a' ? 'a' : '').' nov'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.').ucfirst($config['programa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=programa_pro_editar&programa_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'licao')) $km->Add("diverso","inserir_licao",dica('Nov'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Inserir um'.($config['genero_licao']=='a' ? 'a' : '').' nov'.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.').ucfirst($config['licao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar&licao_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'pratica')) $km->Add("diverso","inserir_pratica",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Inserir um'.($config['genero_pratica']=='a' ? 'a' : '').' nov'.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.').ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'adicionar', null, null)) $km->Add("diverso","inserir_tr",dica('Nov'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Inserir um'.($config['genero_tr']=='a' ? 'a' : '').' nov'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_editar&tr_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'brainstorm')) $km->Add("diverso","inserir_brainstorm",dica('Novo Brainstorm', 'Inserir um novo brainstorm relacionado.').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm_editar&brainstorm_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'gut')) $km->Add("diverso","inserir_gut",dica('Nova Matriz GUT', 'Inserir uma nova matriz GUT relacionado.').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut_editar&gut_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'causa_efeito')) $km->Add("diverso","inserir_causa_efeito",dica('Novo Diagrama de Causa-Efeito', 'Inserir um novo Diagrama de causa-efeito relacionado.').'Diagrama de causa-efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito_editar&causa_efeito_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'tgn')) $km->Add("diverso","inserir_tgn",dica('Nov'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Inserir um'.($config['genero_tgn']=='a' ? 'a' : '').' nov'.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.').ucfirst($config['tgn']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tgn_pro_editar&tgn_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'canvas')) $km->Add("diverso","inserir_canvas",dica('Nov'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Inserir um'.($config['genero_canvas']=='a' ? 'a' : '').' nov'.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.').ucfirst($config['canvas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=canvas_pro_editar&canvas_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_mswot",dica('Nova Matriz SWOT', 'Inserir uma nova matriz SWOT relacionada.').'Matriz SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=mswot_editar&mswot_pratica=".$pratica_id."\");");
			$km->Add("diverso","inserir_swot",dica('Novo Campo SWOT', 'Inserir um novo campo SWOT relacionado.').'Campo SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=swot_editar&swot_pratica=".$pratica_id."\");");
			}
		if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'adicionar', null, null)) $km->Add("diverso","inserir_operativo",dica('Novo Plano Operativo', 'Inserir um novo plano operativo relacionado.').'Plano operativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=operativo&a=operativo_editar&operativo_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'monitoramento')) $km->Add("diverso","inserir_monitoramento",dica('Novo monitoramento', 'Inserir um novo monitoramento relacionado.').'Monitoramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=monitoramento_editar_pro&monitoramento_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'avaliacao_indicador')) $km->Add("diverso","inserir_avaliacao",dica('Nova Avaliação', 'Inserir uma nova avaliação relacionada.').'Avaliação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar&avaliacao_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'checklist')) $km->Add("diverso","inserir_checklist",dica('Novo Checklist', 'Inserir um novo checklist relacionado.').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar&checklist_pratica=".$pratica_id."\");");
		if ($Aplic->profissional) $km->Add("diverso","inserir_agenda",dica('Novo Compromisso', 'Inserir um novo compromisso relacionado.').'Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso&agenda_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'adicionar', null, null)) $km->Add("diverso","inserir_agrupamento",dica('Novo Agrupamento', 'Inserir um novo Agrupamento relacionado.').'Agrupamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=agrupamento&a=agrupamento_editar&agrupamento_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'modelo')) $km->Add("diverso","inserir_template",dica('Novo Modelo', 'Inserir um novo modelo relacionado.').'Modelo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_editar&template_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'painel_indicador')) $km->Add("diverso","inserir_painel",dica('Novo Painel de Indicador', 'Inserir um novo painel de indicador relacionado.').'Painel de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_editar&painel_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'odometro_indicador')) $km->Add("diverso","inserir_painel_odometro",dica('Novo Odômetro de Indicador', 'Inserir um novo odômetro de indicador relacionado.').'Odômetro de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_editar&painel_odometro_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'composicao_painel')) $km->Add("diverso","inserir_painel_composicao",dica('Nova Composição de Painéis', 'Inserir uma nova composição de painéis relacionada.').'Composição de painéis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_editar&painel_composicao_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'slideshow_painel')) $km->Add("diverso","inserir_painel_slideshow",dica('Novo Slideshow de Composições', 'Inserir um novo slideshow de composições relacionado.').'Slideshow de composições'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_slideshow_pro_editar&painel_slideshow_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'ssti')) $km->Add("diverso","inserir_ssti",dica('Nov'.$config['genero_ssti'].' '.ucfirst($config['ssti']), 'Inserir um'.($config['genero_ssti']=='a' ? 'a' : '').' nov'.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.').ucfirst($config['ssti']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=ssti_editar&ssti_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'laudo')) $km->Add("diverso","inserir_laudo",dica('Nov'.$config['genero_laudo'].' '.ucfirst($config['laudo']), 'Inserir um'.($config['genero_laudo']=='a' ? 'a' : '').' nov'.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.').ucfirst($config['laudo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=laudo_editar&laudo_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_trelo",dica('Nov'.$config['genero_trelo'].' '.ucfirst($config['trelo']), 'Inserir um'.($config['genero_trelo']=='a' ? 'a' : '').' nov'.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.').ucfirst($config['trelo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_editar&trelo_pratica=".$pratica_id."\");");
			$km->Add("diverso","inserir_trelo_cartao",dica('Nov'.$config['genero_trelo_cartao'].' '.ucfirst($config['trelo_cartao']), 'Inserir um'.($config['genero_trelo_cartao']=='a' ? 'a' : '').' nov'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.').ucfirst($config['trelo_cartao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_cartao_editar&trelo_cartao_pratica=".$pratica_id."\");");
			}
		if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_pdcl",dica('Nov'.$config['genero_pdcl'].' '.ucfirst($config['pdcl']), 'Inserir um'.($config['genero_pdcl']=='a' ? 'a' : '').' nov'.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.').ucfirst($config['pdcl']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_editar&pdcl_pratica=".$pratica_id."\");");
			$km->Add("diverso","inserir_pdcl_item",dica('Nov'.$config['genero_pdcl_item'].' '.ucfirst($config['pdcl_item']), 'Inserir um'.($config['genero_pdcl_item']=='a' ? 'a' : '').' nov'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.').ucfirst($config['pdcl_item']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_item_editar&pdcl_item_pratica=".$pratica_id."\");");
			}
		$km->Add("inserir","gestao1",dica('Gestao','Menu de objetos de gestão').'Gestao'.dicaF(), "javascript: void(0);'");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'perspectiva')) $km->Add("gestao1","inserir_perspectiva",dica('Nov'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Inserir um'.($config['genero_perspectiva']=='a' ? 'a' : '').' nov'.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_editar&perspectiva_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'tema')) $km->Add("gestao1","inserir_tema",dica('Nov'.$config['genero_tema'].' '.ucfirst($config['tema']), 'Inserir um'.($config['genero_tema']=='a' ? 'a' : '').' nov'.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.').ucfirst($config['tema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_editar&tema_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'objetivo')) $km->Add("gestao1","inserir_objetivo",dica('Nov'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']), 'Inserir um'.($config['genero_objetivo']=='a' ? 'a' : '').' nov'.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.').ucfirst($config['objetivo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_editar&objetivo_pratica=".$pratica_id."\");");
		if ($Aplic->profissional && isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $km->Add("gestao1","inserir_me",dica('Nov'.$config['genero_me'].' '.ucfirst($config['me']), 'Inserir um'.($config['genero_me']=='a' ? 'a' : '').' nov'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=me_editar_pro&me_pratica=".$pratica_id."\");");
		if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator')) $km->Add("gestao1","inserir_fator",dica('Nov'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Inserir um'.($config['genero_fator']=='a' ? 'a' : '').' nov'.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.').ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar&fator_pratica=".$pratica_id."\");"); 
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'iniciativa')) $km->Add("gestao1","inserir_iniciativa",dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Inserir um'.($config['genero_iniciativa']=='a' ? 'a' : '').' nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.').ucfirst($config['iniciativa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_editar&estrategia_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'meta')) $km->Add("gestao1","inserir_meta",dica('Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Inserir um'.($config['genero_meta']=='a' ? 'a' : '').' nov'.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.').ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar&meta_pratica=".$pratica_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'planejamento')) $km->Add("gestao1","inserir_plano_gestao",dica('Novo Planejamento estratégico', 'Inserir um novo planejamento estratégico relacionado.').'Planejamento estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar&plano_gestao_pratica=".$pratica_id."\");");
	
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	
	$bloquear=($pratica['pratica_aprovado'] && $config['trava_aprovacao'] && $tem_aprovacao && !$Aplic->usuario_super_admin);
	if ($Aplic->profissional && isset($assinar['assinatura_id']) && $assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=assinatura&a=assinatura_assinar&pratica_id=".$pratica_id."\");"); 
	
	if ($podeEditarTudo && $podeEditar && !$bloquear) $km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['pratica']),'Editar os detalhes d'.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.').'Editar '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_id=".$pratica_id."\");");
	
	
	if (!$oim && $podeEditarTudo && $podeEditar && !$existe_melhor_pratica && $pratica_modelo_id) $km->Add("acao","acao_editar",dica('Inserir Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja que esta '.$config['pratica'].' pertença ao pool das melhores.').'Inserir Melhor '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_melhores_editar&pratica_id=".$pratica_id."\");");
	elseif (!$oim && $podeEditarTudo && $podeEditar && $existe_melhor_pratica) $km->Add("acao","acao_editar",dica('Editar Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja editar a justificativa para que esta '.$config['pratica'].' pertença ao pool das melhores.').'Editar Melhor '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_melhores_editar&pratica_id=".$pratica_id."\");");
	elseif ($oim && $podeEditarTudo && $podeEditar && $existe_melhor_pratica) 	$km->Add("acao","acao_editar",dica('Remover Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja que esta '.$config['pratica'].' seja removida do pool das melhores, por ter oportunidade de inovação e melhoria.').'Remover Melhor '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='popRemoverMelhorPratica()");

	
	if ($podeExcluir && $podeEditarTudo && !$bloquear) $km->Add("acao","acao_excluir",dica('Excluir','Excluir '.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.').'Excluir '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='excluir()");
	
	//$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_pratica'].' '.$config['pratica'].'.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=praticas&a=imprimir_pratica&dialogo=1&sem_cabecalho=1&tipo=1&pratica_id=".$pratica_id."\");");
	
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes d'.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'], 'Visualize os detalhes d'.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.').' Detalhes d'.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&pratica_id=".$pratica_id."\");");
	
	
	if ($Aplic->profissional && $podeEditarTudo && $podeEditar) $km->Add("acao","acao_exportar",dica('Exportar Link', 'Clique neste ícone '.imagem('icones/exporta_p.png').' para criar um endereço web para visualização em ambiente externo.').imagem('icones/exporta_p.png').' Exportar Link'.dicaF(), "javascript: void(0);' onclick='exportar_link();");	
	
	echo $km->Render();
	echo '<td style="background-color: #e6e6e6" style="white-space: nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().selecionaVetor($modelos_pauta, 'pratica_modelo_id', 'onchange="document.env.submit()" class="texto"', $pratica_modelo_id).'&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados d'.$config['genero_pratica'].' '.$config['genero_pratica'].' inserid'.$config['genero_pratica'].'s no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxPraticaAno', 'onchange="env.submit()" class="texto"', $ano).'</td></tr>';
	}




echo '</form>';

if($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $pratica['pratica_cia'];
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
	echo 	'<font size="4"><center>'.ucfirst($config['pratica']).'</center></font>';
	}




echo '<table id="tblPraticas" cellpadding=0 cellspacing=1 width="100%" '.(!$dialogo ? 'class="std" ' : '').'>';

$cor_indicador=cor_indicador('pratica', null, null, null, null, $pratica['pratica_principal_indicador']);

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$pratica['pratica_cor'].'" colspan="2"><font color="'.melhorCor($pratica['pratica_cor']).'"><b>'.$pratica['pratica_nome'].'<b></font>'.$cor_indicador.'</td></tr>';

$sql->adTabela('pratica_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$designados = $sql->Lista();
$sql->limpar();

$sql->adTabela('pratica_depts');
$sql->adCampo('pratica_depts.dept_id');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$departamentos = $sql->Lista();
$sql->limpar();


if ($pratica['pratica_cia']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($pratica['pratica_cia']).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('pratica_cia');
	$sql->adCampo('pratica_cia_cia');
	$sql->adOnde('pratica_cia_pratica = '.(int)$pratica_id);
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

if ($pratica['pratica_dept']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por '.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($pratica['pratica_dept']).'</td></tr>';

$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]['dept_id']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]['dept_id']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';


if ($pratica['pratica_responsavel']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Responsável pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), ucfirst($config['usuario']).' responsável por gerenciar '.$config['genero_pratica'].' '.$config['pratica'].'.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($pratica['pratica_responsavel'], '','','esquerda').'</td></tr>';		


$saida_quem='';
if ($designados && count($designados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($designados[0]['usuario_id'], '','','esquerda').($designados[0]['contato_dept']? ' - '.link_secao($designados[0]['contato_dept']) : '');
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'], '','','esquerda').($designados[$i]['contato_dept']? ' - '.link_secao($designados[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="designados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';






if ($pratica['pratica_descricao']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Descrição', 'Descrição d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Descrição:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_descricao'].'</td></tr>';



$sql->adTabela('pratica_gestao');
$sql->adCampo('pratica_gestao.*');
$sql->adOnde('pratica_gestao_pratica ='.(int)$pratica_id);	
$sql->adOrdem('pratica_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
$qnt_gestao=0;

if (count($lista)) {
	echo '<tr><td align="right" style="white-space: nowrap" valign="middle">'.dica('Relacionad'.$config['genero_pratica'], 'A que área '.$config['genero_pratica'].' '.$config['pratica'].' está relacionad'.$config['genero_pratica'].'.').'Relacionad'.$config['genero_pratica'].':'.dicaF().'</td></td><td class="realce">';	
	foreach($lista as $gestao_data){
		if ($gestao_data['pratica_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_gestao_tarefa']);
		elseif ($gestao_data['pratica_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_gestao_projeto']);
		
		elseif ($gestao_data['pratica_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_gestao_semelhante']);
		
		elseif ($gestao_data['pratica_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_gestao_acao']);
		elseif ($gestao_data['pratica_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_gestao_perspectiva']);
		elseif ($gestao_data['pratica_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['pratica_gestao_tema']);
		elseif ($gestao_data['pratica_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_gestao_objetivo']);
		elseif ($gestao_data['pratica_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_gestao_fator']);
		elseif ($gestao_data['pratica_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_gestao_estrategia']);
		elseif ($gestao_data['pratica_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_gestao_meta']);
		elseif ($gestao_data['pratica_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_gestao_canvas']);
		elseif ($gestao_data['pratica_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['pratica_gestao_risco']);
		elseif ($gestao_data['pratica_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_gestao_risco_resposta']);
		elseif ($gestao_data['pratica_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['pratica_gestao_indicador']);
		elseif ($gestao_data['pratica_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['pratica_gestao_calendario']);
		elseif ($gestao_data['pratica_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_gestao_monitoramento']);
		elseif ($gestao_data['pratica_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['pratica_gestao_ata']);
		elseif ($gestao_data['pratica_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['pratica_gestao_mswot']);
		elseif ($gestao_data['pratica_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['pratica_gestao_swot']);
		elseif ($gestao_data['pratica_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_gestao_operativo']);
		elseif ($gestao_data['pratica_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_gestao_instrumento']);
		elseif ($gestao_data['pratica_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_gestao_recurso']);
		elseif ($gestao_data['pratica_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['pratica_gestao_problema']);
		elseif ($gestao_data['pratica_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_gestao_demanda']);	
		elseif ($gestao_data['pratica_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['pratica_gestao_programa']);
		elseif ($gestao_data['pratica_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_gestao_licao']);
		elseif ($gestao_data['pratica_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_gestao_evento']);
		elseif ($gestao_data['pratica_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['pratica_gestao_link']);
		elseif ($gestao_data['pratica_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_gestao_avaliacao']);
		elseif ($gestao_data['pratica_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_gestao_tgn']);
		elseif ($gestao_data['pratica_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['pratica_gestao_brainstorm']);
		elseif ($gestao_data['pratica_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['pratica_gestao_gut']);
		elseif ($gestao_data['pratica_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['pratica_gestao_causa_efeito']);
		elseif ($gestao_data['pratica_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_gestao_arquivo']);
		elseif ($gestao_data['pratica_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['pratica_gestao_forum']);
		elseif ($gestao_data['pratica_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_gestao_checklist']);
		elseif ($gestao_data['pratica_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['pratica_gestao_agenda']);
		elseif ($gestao_data['pratica_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['pratica_gestao_agrupamento']);
		elseif ($gestao_data['pratica_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_gestao_patrocinador']);
		elseif ($gestao_data['pratica_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['pratica_gestao_template']);
		elseif ($gestao_data['pratica_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['pratica_gestao_painel']);
		elseif ($gestao_data['pratica_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_gestao_painel_odometro']);
		elseif ($gestao_data['pratica_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['pratica_gestao_painel_composicao']);		
		elseif ($gestao_data['pratica_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['pratica_gestao_tr']);	
		elseif ($gestao_data['pratica_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['pratica_gestao_me']);	
		elseif ($gestao_data['pratica_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['pratica_gestao_acao_item']);	
		elseif ($gestao_data['pratica_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['pratica_gestao_beneficio']);	
		elseif ($gestao_data['pratica_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['pratica_gestao_painel_slideshow']);	
		elseif ($gestao_data['pratica_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['pratica_gestao_projeto_viabilidade']);	
		elseif ($gestao_data['pratica_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['pratica_gestao_projeto_abertura']);	
		elseif ($gestao_data['pratica_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['pratica_gestao_plano_gestao']);	
		elseif ($gestao_data['pratica_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['pratica_gestao_ssti']);
		elseif ($gestao_data['pratica_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['pratica_gestao_laudo']);
		elseif ($gestao_data['pratica_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['pratica_gestao_trelo']);
		elseif ($gestao_data['pratica_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['pratica_gestao_trelo_cartao']);
		elseif ($gestao_data['pratica_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['pratica_gestao_pdcl']);
		elseif ($gestao_data['pratica_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['pratica_gestao_pdcl_item']);
		}
	echo '</td></tr>';
	}	


if ($pratica['pratica_principal_indicador']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'Dentre os indicadores d'.$config['genero_pratica'].' '.$config['pratica'].' mais representativo da situação geral d'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($pratica['pratica_principal_indicador']).'</td></tr>';

if ($pratica['pratica_oque']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('O Que Fazer', 'Sumário sobre o que se trata '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'O Que:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_oque'].'</td></tr>';
if ($pratica['pratica_porque']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Por Que Fazer', 'Por que '.$config['genero_pratica'].' '.$config['pratica'].' será executad'.$config['genero_pratica'].'.').'Por que:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_porque'].'</td></tr>';
if ($pratica['pratica_onde']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Onde Fazer', 'Onde '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Onde:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_onde'].'</td></tr>';
if ($pratica['pratica_quando']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Quando Fazer', 'Quando '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Quando:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_quando'].'</td></tr>';
if ($pratica['pratica_quem']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'Quem:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$pratica['pratica_quem'].'</td></tr>';
if ($pratica['pratica_como']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Como Fazer', 'Como '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Como:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_como'].'</td></tr>';
if ($pratica['pratica_quanto']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Quanto Custa', 'Custo para executar '.$config['genero_pratica'].' '.$config['pratica'].'.').'Quanto:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_quanto'].'</td></tr>';

if ($Aplic->profissional) {
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	if (isset($moedas[$pratica['pratica_moeda']])) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'A moeda padrão utilizada.').'Moeda:'.dicaF().'</td><td class="realce" width="100%">'.$moedas[$pratica['pratica_moeda']].'</td></tr>';	
	}
if ($Aplic->profissional && $tem_aprovacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Aprovado', 'Se '.$config['genero_pratica'].' '.$config['pratica'].' se encontra aprovad'.$config['genero_pratica'].'.').'Aprovad'.$config['genero_pratica'].':'.dicaF().'</td><td  class="realce" width="100%">'.($pratica['pratica_aprovado'] ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';

	
echo '<tr><td align="right">'.dica('Ativ'.$config['genero_pratica'], 'Caso '.$config['genero_pratica'].' '.$config['pratica'].' ainda esteja ativ'.$config['genero_pratica'].' deverá estar marcado este campo.').'Ativ'.$config['genero_pratica'].':'.dicaF().'</td><td class="realce" width="100%">'.($pratica['pratica_ativa'] ? 'Sim' : 'Não').'</td></tr>';	

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('praticas',$pratica['pratica_id'], 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
		


$sql->adTabela('pratica_requisito');
$sql->adCampo('pratica_requisito.*');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$sql->adOnde('ano = '.(int)$ano);
$requisito = $sql->linha();
$sql->limpar();

$sql->adTabela('pratica_nos_verbos');
$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_verbo_id=verbo');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item_id=pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio_id=pratica_item_criterio');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_verbos.pratica=praticas.pratica_id');
$sql->adCampo('COUNT(verbo)');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('praticas.pratica_id='.(int)$pratica_id);
$sql->adOnde('pratica_nos_verbos.ano='.(int)$ano);
$adequacao=$sql->Resultado();
$sql->limpar();

$pratica['pratica_adequada']=(int)$adequacao;







$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=0 OR pratica_regra_campo_resultado IS NULL');
$sql->adOrdem('pratica_regra_campo_id');
$lista=$sql->Lista();
$sql->limpar();

if ($existe_melhor_pratica)  echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Melhor '.ucfirst($config['pratica']), 'Pertence ao pool das melhores '.$config['pratica'].'.').'Melhor '.$config['pratica'].':'.dicaF().'</td><td class="realce" width="100%">Sim</td></tr>';
	
$original=array();	

$original['pratica_controlada']=dica('Controlad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' é controlad'.$config['genero_pratica'].'.').'Controlad'.$config['genero_pratica'].':'.dicaF();
$original['pratica_proativa']=dica('Proativ'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem a capacidade de antecipar-se aos fatos, a fim de prevenir a ocorrência de situações potencialmente indesejáveis e aumentar a confiança e a previsibilidade dos processos gerenciais.').'Proativ'.$config['genero_pratica'].':'.dicaF();
$original['pratica_abrange_pertinentes']=dica('Abrangente',($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem cobertura ou escopo suficientes, horizontal ou vertical, conforme pertinente a cada processo gerencial requerido pelas áreas, processos, produtos ou partes interessadas, considerando-se o perfil d'.$config['genero_organizacao'].' '.$config['organizacao'].' e estratégias.').'Abrangente:'.dicaF();
$original['pratica_continuada']=dica('Uso Continuado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem utilização periódica e ininterrupta, considerando-se a realização de pelo menos um ciclo completo.').'Uso Continuado:'.dicaF();
$original['pratica_refinada']=dica('Refinad'.$config['genero_pratica'], ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta aperfeiçoamento decorrente dos processos de melhoria e inovação.<br><br>Em estágios avançados de refinamento, esse subfator exige processos gerenciais atendidos por '.$config['praticas'].' no estado da arte e que incorporam alguma inovação.').'Refinad'.$config['genero_pratica'].':'.dicaF();
$original['pratica_refinada_implantacao']=dica('Aperfeiçoamento em Implantação','<p>'.$config['genero_pratica'].' '.$config['pratica'].' incorpora ou representa um aperfeiçoamento em implantação.').'Aperfeiçoamento em implantação:'.dicaF();
$original['pratica_melhoria_aprendizado']=dica('Melhorias Decorrentes do Aprendizado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta melhorias decorrentes do aprendizado.').'Melhorias pelo aprendizado:'.dicaF();
$original['pratica_coerente']=dica('Coerente', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem relação harmônica com as estratégias e objetivos d'.$config['genero_organizacao'].' '.$config['organizacao'].', incluindo valores e princípios.').'Coerente:'.dicaF();
$original['pratica_incoerente']=dica('Incoerência Grave','Existe incoerência grave entre os valores, princípios, estratégias e objetivos organizacionais, na realização d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Incoerência grave:'.dicaF();
$original['pratica_interrelacionada']=dica('Inter-relacionad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem implementação de modo complementar com outr'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].', onde apropriado.').'Inter-relacionad'.$config['genero_pratica'].':'.dicaF();
$original['pratica_cooperacao']=dica('Cooperativ'.$config['genero_pratica'],'Há colaboração entre as áreas d'.$config['genero_organizacao'].' '.$config['organizacao'].' na implementação – planejamento, execução, controle ou aperfeiçoamento – n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Cooperativ'.$config['genero_pratica'].':'.dicaF();
$original['pratica_cooperacao_partes']=dica('Cooperação com as Partes Interessadas','Há colaboração com as partes interessadas pertinentes a cada processo gerencial requerido.').'Cooperação com interessados:'.dicaF();
$original['pratica_arte']=dica('Estado-de-Arte',($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' espelha o estado-da-arte.').'Estado-de-arte:'.dicaF();
$original['pratica_inovacao']=dica('Inovador'.($config['genero_pratica']=='a' ? 'a': ''),($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' apresenta uma inovação de ruptura representando um novo benchmark.').'Inovador'.($config['genero_pratica']=='a' ? 'a': '').':'.dicaF();
$original['pratica_gerencial']=dica('Padrão gerencial','Há padrão gerencial suficiente que oriente a execução adequada d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Padrão gerencial:'.dicaF();
$original['pratica_agil']=dica('Agilidade','Há agilidade suficiente nos processos gerenciais exigidos no Critério, incorporados n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Agilidade:'.dicaF();


foreach($lista as $linha) $original[$linha['pratica_regra_campo_nome']]=dica($linha['pratica_regra_campo_texto'], $linha['pratica_regra_campo_descricao']).'<b>'.$linha['pratica_regra_campo_texto'].'</b>:'.dicaF();

echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_controlada'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_controlada'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_controlada'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_controlada'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_proativa'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_proativa'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_proativa'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_proativa'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_abrange_pertinentes'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_abrange_pertinentes'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_abrangencia'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_abrangencia'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_continuada'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_continuada'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_continuada'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_continuada'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_refinada'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_refinada'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_refinada'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_refinada'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_refinada_implantacao'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_refinada_implantacao'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_refinada_implantacao'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_refinada_implantacao'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_melhoria_aprendizado'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_melhoria_aprendizado'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_melhoria_aprendizado'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_melhoria_aprendizado'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_coerente'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_coerente'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_coerente'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_coerente'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_incoerente'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_incoerente'] ? 'color: #ff0000;">Sim' : 'color: #2d8937;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_incoerente'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_incoerente'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_interrelacionada'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_interrelacionada'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_interrelacionada'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_interrelacionada'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_cooperacao'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_cooperacao'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_cooperacao'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_cooperacao'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_cooperacao_partes'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_cooperacao_partes'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_cooperacao_partes'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_cooperacao_partes'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_arte'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_arte'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_arte'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_arte'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_inovacao'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_inovacao'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_inovacao'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_inovacao'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_gerencial'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_gerencial'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_gerencial'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_gerencial'].'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_agil'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_agil'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_agil'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_agil'].'</td></tr></table></td></tr>';


if (!$Aplic->profissional){
	if (!$oim && $editar && !$existe_melhor_pratica && $pratica_modelo_id) echo '<tr><td colspan=2>'.botao('inserir melhor '.$config['pratica'], 'Inserir Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja que esta '.$config['pratica'].' pertença ao pool das melhores.','','url_passar(0, \'m=praticas&a=pratica_melhores_editar&pratica_id='.(int)$pratica_id.'\');').'</td></tr>';
	elseif (!$oim && $editar && $existe_melhor_pratica) echo '<tr><td colspan=2>'.botao('editar melhor '.$config['pratica'], 'Editar Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja editar a justificativa para que esta '.$config['pratica'].' pertença ao pool das melhores.','','url_passar(0, \'m=praticas&a=pratica_melhores_editar&pratica_id='.(int)$pratica_id.'\');').'</td></tr>';
	elseif ($oim && $editar && $existe_melhor_pratica) echo '<tr><td colspan=2>'.botao('remover melhor '.$config['pratica'], 'Remover Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja que esta '.$config['pratica'].' seja removida do pool das melhores, por ter oportunidade de inovação e melhoria.','','popRemoverMelhorPratica()').'</td></tr>';
	}
	




if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/pratica_ver_pro.php';
		

echo '</table>';

if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';

if (!$dialogo) {


	$caixaTab = new CTabBox('m=praticas&a=pratica_ver&pratica_id='.(int)$pratica_id, '', $tab);
	$texto_consulta = '?m=praticas&a=pratica_ver&pratica_id='.(int)$pratica_id;
	

	$qnt_aba=0;
	
	if ($Aplic->checarModulo('log', 'acesso')) {
		$sql->adTabela('log');
		$sql->adCampo('count(log_id)');
		$sql->adOnde('log_pratica = '.(int)$pratica_id);
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver', 'Registro',null,null,'Registro das Ocorrência','Visualizar o registro de ocorrência relacionado.');
			}
		}

	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {

			$sql->adTabela('evento_gestao','evento_gestao');
			$sql->adOnde('evento_gestao_pratica = '.(int)$pratica_id);
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
			$sql->adOnde('arquivo_gestao_pratica = '.(int)$pratica_id);
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
			$sql->adOnde('pratica_indicador_gestao_pratica = '.(int)$pratica_id);
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
			$sql->adOnde('plano_acao_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('plano_acao_item_gestao_pratica = '.(int)$pratica_id);
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
			$sql->adOnde('projeto_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('projeto_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('ata_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('demanda_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('msg_gestao_pratica = '.(int)$pratica_id);
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
			$sql->adOnde('modelo_gestao_pratica = '.(int)$pratica_id);
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
			$sql->adOnde('link_gestao_pratica = '.(int)$pratica_id);
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
			$sql->adOnde('forum_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('problema_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('risco_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('risco_resposta_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('instrumento_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('recurso_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('patrocinador_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('programa_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('beneficio_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('licao_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('pratica_gestao_semelhante = '.(int)$pratica_id);
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
		$sql->adOnde('tr_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('brainstorm_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('gut_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('causa_efeito_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('tgn_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('canvas_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('mswot_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('swot_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('operativo_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('monitoramento_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('avaliacao_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('checklist_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('agenda_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('agrupamento_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('template_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('painel_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('painel_odometro_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('painel_composicao_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('painel_slideshow_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('calendario_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('perspectiva_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('tema_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('objetivo_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('me_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('fator_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('estrategia_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('meta_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('plano_gestao_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('projeto_abertura_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('projeto_viabilidade_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('ssti_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('laudo_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('trelo_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('trelo_cartao_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('pdcl_gestao_pratica = '.(int)$pratica_id);
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
		$sql->adOnde('pdcl_item_gestao_pratica = '.(int)$pratica_id);
		$sql->adOnde('pdcl_item_gestao_pdcl_item IS NOT NULL');
		$sql->adCampo('count(pdcl_item_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/pdcl/pdcl_item_tabela', ucfirst($config['pdcl_item']),null,null,ucfirst($config['pdcl_item']),'Visualizar '.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.');
			}
		}	
	
	if ($qnt_aba) {
		$caixaTab->mostrar('','','','',true);
		echo estiloFundoCaixa('','', $tab);
		}	
	
	}
?>
<script type="text/javascript">

function exportar_link() {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link&dialogo=1&tipo=generico', null, window);
	}	
	
function mudar_ano(){
	document.env.submit();
	}	

function mudar_pauta(){
	document.env.submit();
	}		

function excluir() {
	if (confirm('Tem certeza que deseja excluir <?php echo ($config["genero_pratica"]=="a" ? "esta ": "este ").$config["pratica"]?>?')) {
		var f = document.env;
		f.del.value=1;
		f.a.value='pratica_fazer_sql';
		f.dialogo.value=1;
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>