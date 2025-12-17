<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$pratica_indicador_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$pratica_indicador_id = intval(getParam($_REQUEST, 'pratica_indicador_id', 0));

if (isset($_REQUEST['tab'])) $Aplic->setEstado('IndicadorVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('IndicadorVerTab') !== null ? $Aplic->getEstado('IndicadorVerTab') : 0;

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'indicador\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if ($Aplic->profissional){
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_indicador='.(int)$pratica_indicador_id);
	$assinar = $sql->linha();
	$sql->limpar();

	//tem assinatura que aprova
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_indicador='.(int)$pratica_indicador_id);
	$tem_aprovacao = $sql->resultado();
	$sql->limpar();
	}

$sql->adTabela('pratica_indicador_requisito');
$sql->adCampo('DISTINCT pratica_indicador_requisito_ano');
$sql->adOnde('pratica_indicador_requisito_indicador='.(int)$pratica_indicador_id);
$sql->adOrdem('pratica_indicador_requisito_ano');
$anos=array(''=>'')+$sql->listaVetorChave('pratica_indicador_requisito_ano','pratica_indicador_requisito_ano');
$sql->limpar();

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos_pauta=array(''=>'')+$sql->listaVetorChave('pratica_modelo_id', 'pratica_modelo_nome');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);
asort($anos);

if (isset($_REQUEST['IdxIndicadorAno'])) $Aplic->setEstado('IdxIndicadorAno', getParam($_REQUEST, 'IdxIndicadorAno', null));
$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null && isset($anos[$Aplic->getEstado('IdxIndicadorAno')]) ? $Aplic->getEstado('IdxIndicadorAno') : null);

$sql->adTabela('pratica_indicador');
if (!$ano) $sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
else {
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adOnde('pratica_indicador_requisito_ano='.(int)$ano);
	}

$sql->adCampo('pratica_indicador.*, pratica_indicador_requisito.*');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();


if($pratica_indicador_id && !(permiteAcessarIndicador($pratica_indicador['pratica_indicador_acesso'],$pratica_indicador_id) && $Aplic->checarModulo('praticas', 'acesso', $Aplic->usuario_id, 'indicador'))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'indicador');
$podeAdicionar=$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'indicador');
$podeExcluir=$Aplic->checarModulo('praticas', 'excluir', $Aplic->usuario_id, 'indicador');


//$podeValor=$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'indicador_valor');
$podeValor=permiteEditarValorIndicador($pratica_indicador_id);
$editar=permiteEditarIndicador($pratica_indicador['pratica_indicador_acesso'],$pratica_indicador_id);
$podeEditarTudo=($pratica_indicador['pratica_indicador_acesso']>=5 ? $editar && (in_array($pratica_indicador['pratica_indicador_responsavel'], $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);





include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
$obj = new Indicador($pratica_indicador_id, $ano);

$valor=$obj->Valor_atual($pratica_indicador['pratica_indicador_agrupar'], $ano);

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="indicador_ver" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '<input type="hidden" name="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="pratica_indicador_nome" value="" />';



if (!$dialogo) $botoesTitulo = new CBlocoTitulo('Detalhes do Indicador', 'indicador.gif', $m, $m.'.'.$a);


if (!$dialogo) $botoesTitulo->mostrar();




$data = ($obj->pratica_indicador_data_meta!=null ? new CData($obj->pratica_indicador_data_meta) : new CData());
$data_desde = isset($pratica_indicador['pratica_indicador_desde_quando']) ? new CData($pratica_indicador['pratica_indicador_desde_quando']) : new CData();



 if (!$dialogo) echo estiloTopoCaixa();

if (!$dialogo){
	$Aplic->salvarPosicao();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">';
	echo '<tr><td colspan=2 style="background-color: #212121" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Indicadores','Visualizar a lista de Indicadores.').'Lista de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_lista\");");
	if ((($podeEditar || $podeValor) && $editar) || $podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar)	$km->Add("inserir","inserir_objeto",dica('Novo Indicador', 'Criar um novo indicador.').'Novo Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar\");");
	if ($podeEditar && $editar) {
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar&pratica_indicador_id=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) $km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_indicador=".$pratica_indicador_id."\");");	
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'demanda')) $km->Add("inserir","inserir_demanda",dica('Nova Demanda', 'Inserir uma nova demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar&demanda_indicador=".$pratica_indicador_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_indicador=".$pratica_indicador_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		$km->Add("inserir","diverso",dica('Diversos','Menu de objetos diversos').'Diversos'.dicaF(), "javascript: void(0);'");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("diverso","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("diverso","inserir_forum",dica('Novo Fórum', 'Inserir um novo fórum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("diverso","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("diverso","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'resposta_risco')) $km->Add("diverso","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'adicionar', null, null)) $km->Add("diverso","inserir_instrumento",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Inserir um'.($config['genero_instrumento']=='a' ? 'a' : '').' nov'.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar&instrumento_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('recursos', 'adicionar', null, null)) $km->Add("diverso","inserir_recurso",dica('Nov'.$config['genero_recurso'].' '.ucfirst($config['recurso']), 'Inserir um'.($config['genero_recurso']=='a' ? 'a' : '').' nov'.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.').ucfirst($config['recurso']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar&recurso_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'adicionar', null, null)) $km->Add("diverso","inserir_patrocinador",dica('Nov'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Inserir '.($config['genero_patrocinador']=='o' ? 'um' : 'uma').' nov'.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.').ucfirst($config['patrocinador']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=patrocinadores&a=patrocinador_editar&patrocinador_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'programa')) $km->Add("diverso","inserir_programa",dica('Nov'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Inserir um'.($config['genero_programa']=='a' ? 'a' : '').' nov'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.').ucfirst($config['programa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=programa_pro_editar&programa_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'licao')) $km->Add("diverso","inserir_licao",dica('Nov'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Inserir um'.($config['genero_licao']=='a' ? 'a' : '').' nov'.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.').ucfirst($config['licao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar&licao_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'pratica')) $km->Add("diverso","inserir_pratica",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Inserir um'.($config['genero_pratica']=='a' ? 'a' : '').' nov'.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.').ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'adicionar', null, null)) $km->Add("diverso","inserir_tr",dica('Nov'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Inserir um'.($config['genero_tr']=='a' ? 'a' : '').' nov'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_editar&tr_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'brainstorm')) $km->Add("diverso","inserir_brainstorm",dica('Novo Brainstorm', 'Inserir um novo brainstorm relacionado.').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm_editar&brainstorm_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'gut')) $km->Add("diverso","inserir_gut",dica('Nova Matriz GUT', 'Inserir uma nova matriz GUT relacionado.').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut_editar&gut_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'causa_efeito')) $km->Add("diverso","inserir_causa_efeito",dica('Novo Diagrama de Causa-Efeito', 'Inserir um novo Diagrama de causa-efeito relacionado.').'Diagrama de causa-efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito_editar&causa_efeito_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'tgn')) $km->Add("diverso","inserir_tgn",dica('Nov'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Inserir um'.($config['genero_tgn']=='a' ? 'a' : '').' nov'.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.').ucfirst($config['tgn']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tgn_pro_editar&tgn_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'canvas')) $km->Add("diverso","inserir_canvas",dica('Nov'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Inserir um'.($config['genero_canvas']=='a' ? 'a' : '').' nov'.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.').ucfirst($config['canvas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=canvas_pro_editar&canvas_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_mswot",dica('Nova Matriz SWOT', 'Inserir uma nova matriz SWOT relacionada.').'Matriz SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=mswot_editar&mswot_indicador=".$pratica_indicador_id."\");");
			$km->Add("diverso","inserir_swot",dica('Novo Campo SWOT', 'Inserir um novo campo SWOT relacionado.').'Campo SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=swot_editar&swot_indicador=".$pratica_indicador_id."\");");
			}
		if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'adicionar', null, null)) $km->Add("diverso","inserir_operativo",dica('Novo Plano Operativo', 'Inserir um novo plano operativo relacionado.').'Plano operativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=operativo&a=operativo_editar&operativo_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'monitoramento')) $km->Add("diverso","inserir_monitoramento",dica('Novo monitoramento', 'Inserir um novo monitoramento relacionado.').'Monitoramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=monitoramento_editar_pro&monitoramento_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'avaliacao_indicador')) $km->Add("diverso","inserir_avaliacao",dica('Nova Avaliação', 'Inserir uma nova avaliação relacionada.').'Avaliação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar&avaliacao_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'checklist')) $km->Add("diverso","inserir_checklist",dica('Novo Checklist', 'Inserir um novo checklist relacionado.').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar&checklist_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional) $km->Add("diverso","inserir_agenda",dica('Novo Compromisso', 'Inserir um novo compromisso relacionado.').'Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso&agenda_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'adicionar', null, null)) $km->Add("diverso","inserir_agrupamento",dica('Novo Agrupamento', 'Inserir um novo Agrupamento relacionado.').'Agrupamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=agrupamento&a=agrupamento_editar&agrupamento_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'modelo')) $km->Add("diverso","inserir_template",dica('Novo Modelo', 'Inserir um novo modelo relacionado.').'Modelo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_editar&template_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'painel_indicador')) $km->Add("diverso","inserir_painel",dica('Novo Painel de Indicador', 'Inserir um novo painel de indicador relacionado.').'Painel de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_editar&painel_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'odometro_indicador')) $km->Add("diverso","inserir_painel_odometro",dica('Novo Odômetro de Indicador', 'Inserir um novo odômetro de indicador relacionado.').'Odômetro de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_editar&painel_odometro_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'composicao_painel')) $km->Add("diverso","inserir_painel_composicao",dica('Nova Composição de Painéis', 'Inserir uma nova composição de painéis relacionada.').'Composição de painéis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_editar&painel_composicao_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'slideshow_painel')) $km->Add("diverso","inserir_painel_slideshow",dica('Novo Slideshow de Composições', 'Inserir um novo slideshow de composições relacionado.').'Slideshow de composições'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_slideshow_pro_editar&painel_slideshow_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'ssti')) $km->Add("diverso","inserir_ssti",dica('Nov'.$config['genero_ssti'].' '.ucfirst($config['ssti']), 'Inserir um'.($config['genero_ssti']=='a' ? 'a' : '').' nov'.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.').ucfirst($config['ssti']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=ssti_editar&ssti_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'laudo')) $km->Add("diverso","inserir_laudo",dica('Nov'.$config['genero_laudo'].' '.ucfirst($config['laudo']), 'Inserir um'.($config['genero_laudo']=='a' ? 'a' : '').' nov'.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.').ucfirst($config['laudo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=laudo_editar&laudo_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_trelo",dica('Nov'.$config['genero_trelo'].' '.ucfirst($config['trelo']), 'Inserir um'.($config['genero_trelo']=='a' ? 'a' : '').' nov'.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.').ucfirst($config['trelo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_editar&trelo_indicador=".$pratica_indicador_id."\");");
			$km->Add("diverso","inserir_trelo_cartao",dica('Nov'.$config['genero_trelo_cartao'].' '.ucfirst($config['trelo_cartao']), 'Inserir um'.($config['genero_trelo_cartao']=='a' ? 'a' : '').' nov'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.').ucfirst($config['trelo_cartao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_cartao_editar&trelo_cartao_indicador=".$pratica_indicador_id."\");");
			}
		if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_pdcl",dica('Nov'.$config['genero_pdcl'].' '.ucfirst($config['pdcl']), 'Inserir um'.($config['genero_pdcl']=='a' ? 'a' : '').' nov'.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.').ucfirst($config['pdcl']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_editar&pdcl_indicador=".$pratica_indicador_id."\");");
			$km->Add("diverso","inserir_pdcl_item",dica('Nov'.$config['genero_pdcl_item'].' '.ucfirst($config['pdcl_item']), 'Inserir um'.($config['genero_pdcl_item']=='a' ? 'a' : '').' nov'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.').ucfirst($config['pdcl_item']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_item_editar&pdcl_item_indicador=".$pratica_indicador_id."\");");
			}
		
		$km->Add("inserir","gestao1",dica('Gestao','Menu de objetos de gestão').'Gestao'.dicaF(), "javascript: void(0);'");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'perspectiva')) $km->Add("gestao1","inserir_perspectiva",dica('Nov'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Inserir um'.($config['genero_perspectiva']=='a' ? 'a' : '').' nov'.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_editar&perspectiva_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'tema')) $km->Add("gestao1","inserir_tema",dica('Nov'.$config['genero_tema'].' '.ucfirst($config['tema']), 'Inserir um'.($config['genero_tema']=='a' ? 'a' : '').' nov'.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.').ucfirst($config['tema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_editar&tema_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'objetivo')) $km->Add("gestao1","inserir_objetivo",dica('Nov'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']), 'Inserir um'.($config['genero_objetivo']=='a' ? 'a' : '').' nov'.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.').ucfirst($config['objetivo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_editar&objetivo_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->profissional && isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $km->Add("gestao1","inserir_me",dica('Nov'.$config['genero_me'].' '.ucfirst($config['me']), 'Inserir um'.($config['genero_me']=='a' ? 'a' : '').' nov'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=me_editar_pro&me_indicador=".$pratica_indicador_id."\");");
		if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator')) $km->Add("gestao1","inserir_fator",dica('Nov'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Inserir um'.($config['genero_fator']=='a' ? 'a' : '').' nov'.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.').ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar&fator_indicador=".$pratica_indicador_id."\");"); 
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'iniciativa')) $km->Add("gestao1","inserir_iniciativa",dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Inserir um'.($config['genero_iniciativa']=='a' ? 'a' : '').' nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.').ucfirst($config['iniciativa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_editar&estrategia_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'meta')) $km->Add("gestao1","inserir_meta",dica('Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Inserir um'.($config['genero_meta']=='a' ? 'a' : '').' nov'.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.').ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar&meta_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'planejamento')) $km->Add("gestao1","inserir_plano_gestao",dica('Novo Planejamento estratégico', 'Inserir um novo planejamento estratégico relacionado.').'Planejamento estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar&plano_gestao_indicador=".$pratica_indicador_id."\");");
		}

	if (($podeEditar && $podeEditarTudo) || $podeValor){
		if (!$pratica_indicador['pratica_indicador_composicao'] && !$pratica_indicador['pratica_indicador_formula'] && !$pratica_indicador['pratica_indicador_formula_simples'] && !$pratica_indicador['pratica_indicador_checklist'] && !$pratica_indicador['pratica_indicador_campo_projeto'] && !$pratica_indicador['pratica_indicador_campo_tarefa'] && !$pratica_indicador['pratica_indicador_campo_acao'] && !$pratica_indicador['pratica_indicador_externo']) $km->Add("inserir","inserir_valor",dica('Valor','Inserir valor neste indicador.').'Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar_valor&pratica_indicador_id=".(int)$pratica_indicador_id."\");");
		elseif ($pratica_indicador['pratica_indicador_checklist']) $km->Add("inserir","inserir_valor",dica('Valor de Checklist','Inserir respostas para o checklist deste indicador.').'Valor de Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar_valor&pratica_indicador_id=".(int)$pratica_indicador_id."\");");
		elseif ($pratica_indicador['pratica_indicador_formula_simples']) $km->Add("inserir","inserir_valor",dica('Valor','Inserir valor neste indicador.').'Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar_valor_pro&pratica_indicador_id=".(int)$pratica_indicador_id."\");");
		elseif ($pratica_indicador['pratica_indicador_externo']) $km->Add("inserir","inserir_valor",dica('Importar Valor','Importar valor de base externa para este indicador.').'Importar Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_importar_valor_pro&pratica_indicador_id=".(int)$pratica_indicador_id."\");");
		}

	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");

	$bloquear=($pratica_indicador['pratica_indicador_aprovado'] && $config['trava_aprovacao'] && $tem_aprovacao && !$Aplic->usuario_super_admin);
	if ($Aplic->profissional && isset($assinar['assinatura_id']) && $assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=assinatura&a=assinatura_assinar&pratica_indicador_id=".$pratica_indicador_id."\");");


	if ($podeEditarTudo && $podeEditar && !$bloquear) { 
		$km->Add("acao","acao_editar",dica('Editar', 'Editar os detalhes deste indicador.').'Editar Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&&pratica_indicador_id=".(int)$pratica_indicador_id."\");");

		if (!$pratica_indicador['pratica_indicador_composicao'] && !$pratica_indicador['pratica_indicador_formula'] && !$pratica_indicador['pratica_indicador_formula_simples'] && !$pratica_indicador['pratica_indicador_checklist'] && !$pratica_indicador['pratica_indicador_campo_projeto'] && !$pratica_indicador['pratica_indicador_campo_tarefa'] && !$pratica_indicador['pratica_indicador_campo_acao'] && !$pratica_indicador['pratica_indicador_externo']) $km->Add("acao","acao_valor",dica('Valor','Inserir valor neste indicador.').'Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar_valor&pratica_indicador_id=".(int)$pratica_indicador_id."\");");
		elseif ($pratica_indicador['pratica_indicador_checklist']) $km->Add("acao","acao_valor",dica('Valor de Checklist','Inserir respostas para o checklist deste indicador.').'Valor de Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar_valor&pratica_indicador_id=".(int)$pratica_indicador_id."\");");
		elseif ($pratica_indicador['pratica_indicador_formula_simples']) $km->Add("acao","acao_valor",dica('Valor','Inserir valor neste indicador.').'Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar_valor_pro&pratica_indicador_id=".(int)$pratica_indicador_id."\");");
		elseif ($pratica_indicador['pratica_indicador_externo']) $km->Add("acao","acao_valor",dica('Importar Valor','Importar valor de base externa para este indicador.').'Importar Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_importar_valor_pro&pratica_indicador_id=".(int)$pratica_indicador_id."\");");

		$km->Add("acao","acao_valor",dica('Avaliação dos Resultados','Inserir as causas de sucesso ou fracasso do indicador.').'Avaliação dos Resultados'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_avaliacao&pratica_indicador_id=".(int)$pratica_indicador_id."\");");
		$km->Add("acao","acao_duplicar",dica('Duplicar','Duplicar o indicador, criando uma cópia idêntica, mas sem qualquer valor inserido.').'Duplicar o Indicador'.dicaF(), "javascript: void(0);' onclick='duplicar_indicador();");
		}
	
	if ($podeEditarTudo && $podeExcluir && !$bloquear) $km->Add("acao","acao_excluir",dica('Excluir este Indicador','Excluir este indicador.').'Excluir Indicador'.dicaF(), "javascript: void(0);' onclick='excluir()");
		
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");

	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes deste Indicador', 'Visualize os detalhes deste indicador.').'Detalhes'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&pratica_indicador_id=".$pratica_indicador_id."\");");
	$km->Add("acao_imprimir","acao_imprimir2",dica('Resumo', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o resumo deste indicador.').'Resumo'.dicaF(), "javascript: void(0);' onclick='imprimir(".$pratica_indicador_id.");");

	if ($Aplic->profissional && $podeEditarTudo && $podeEditar) $km->Add("acao","acao_exportar",dica('Exportar Link', 'Clique neste ícone '.imagem('icones/exporta_p.png').' para criar um endereço web para visualização em ambiente externo.').imagem('icones/exporta_p.png').' Exportar Link'.dicaF(), "javascript: void(0);' onclick='exportar_link();");


	echo $km->Render();
	echo '<td  style="background-color: #e6e6e6" style="white-space: nowrap" align="right"><table><tr><td style="white-space: nowrap">'.dica('Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().selecionaVetor($modelos_pauta, 'pratica_modelo_id', 'onchange="document.env.submit()" class="texto"', $pratica_modelo_id).'</td><td style="white-space: nowrap">'.dica('Ano', 'Utilize esta opção para visualizar os dados do indicador no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxIndicadorAno', 'onchange="env.submit()" class="texto"', $ano).'</td></tr></table></td></tr>';
	echo '</table>';
	}

echo '</form>';


if($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $pratica_indicador['pratica_indicador_cia'];
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
	echo 	'<font size="4"><center>Indicador</center></font>';
	}




echo '<table id="tblPraticas" cellpadding=0 cellspacing=1 width="100%"><tr><td style="border: outset #d1d1cd 1px;background-color:#'.$pratica_indicador['pratica_indicador_cor'].' !important;color:#'.melhorCor($pratica_indicador['pratica_indicador_cor']).' !important;" colspan=2 class="realce" onclick="if (document.getElementById(\'tblProjetos\').style.display) {document.getElementById(\'tblProjetos\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'mostrar\').style.display=\'none\';} else {document.getElementById(\'tblProjetos\').style.display=\'none\'; document.getElementById(\'contrair\').style.display=\'none\'; document.getElementById(\'mostrar\').style.display=\'\';} if(window.onResizeDetalhesProjeto) window.onResizeDetalhesProjeto(); xajax_painel_indicador(document.getElementById(\'tblProjetos\').style.display);"><a href="javascript: void(0);"><span id="mostrar" style="display:none">'.imagem('icones/mostrar.gif', 'Mostrar Detalhes', 'Clique neste ícone '.imagem('icones/mostrar.gif').' para mostrar os detalhes do indicador.').'</span><span id="contrair">'.imagem('icones/contrair.gif', 'Ocultar Detalhes', 'Clique neste ícone '.imagem('icones/contrair.gif').' para ocultar os detalhes do indicador.').'</span><b>'.$pratica_indicador['pratica_indicador_nome'].'<b></td></tr></table>';
$painel_indicador = $Aplic->getEstado('painel_indicador') !== null ? $Aplic->getEstado('painel_indicador') : 1;
echo '<table id="tblProjetos" cellpadding=0 cellspacing=1 width="100%" '.(!$dialogo ? 'class="std"' : '').' style="display:'.($painel_indicador ? '' : 'none').'">';




if ($pratica_indicador['pratica_indicador_cia']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável por este indicador.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($pratica_indicador['pratica_indicador_cia']).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('indicador_cia');
	$sql->adCampo('indicador_cia_cia');
	$sql->adOnde('indicador_cia_indicador = '.(int)$pratica_indicador_id);
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
if ($pratica_indicador['pratica_indicador_dept']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por este indicador.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($pratica_indicador['pratica_indicador_dept']).'</td></tr>';
$sql->adTabela('pratica_indicador_depts');
$sql->adCampo('pratica_indicador_depts.dept_id');
$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
$departamentos = $sql->carregarColuna();
$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$saida_depts.'</td></tr>';

if ($pratica_indicador['pratica_indicador_responsavel']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Responsável pelo Indicador', 'Todo indicador deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan=2 class="realce">'.link_usuario($pratica_indicador['pratica_indicador_responsavel'],'','','esquerda').'</td></tr>';


$sql->adTabela('pratica_indicador_usuarios', 'pratica_indicador_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_indicador_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, contato_dept');
$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
$participantes = $sql->Lista();
$sql->limpar();

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
if ($saida_quem) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$saida_quem.'</td></tr>';



$sql->adTabela('pratica_indicador_gestao');
$sql->adCampo('pratica_indicador_gestao.*');
$sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$pratica_indicador_id);	
$sql->adOrdem('pratica_indicador_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
$qnt_gestao=0;

if (count($lista)) {
	echo '<tr><td align="right" style="white-space: nowrap" valign="middle">'.dica('Relacionado', 'A que área o indicador está relacionado.').'Relacionado:'.dicaF().'</td></td><td class="realce">';	
	foreach($lista as $gestao_data){
		if ($gestao_data['pratica_indicador_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_indicador_gestao_tarefa']);
		elseif ($gestao_data['pratica_indicador_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_indicador_gestao_projeto']);
		elseif ($gestao_data['pratica_indicador_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_indicador_gestao_pratica']);
		elseif ($gestao_data['pratica_indicador_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_indicador_gestao_acao']);
		elseif ($gestao_data['pratica_indicador_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_indicador_gestao_perspectiva']);
		elseif ($gestao_data['pratica_indicador_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['pratica_indicador_gestao_tema']);
		elseif ($gestao_data['pratica_indicador_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_indicador_gestao_objetivo']);
		elseif ($gestao_data['pratica_indicador_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_indicador_gestao_fator']);
		elseif ($gestao_data['pratica_indicador_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_indicador_gestao_estrategia']);
		elseif ($gestao_data['pratica_indicador_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_indicador_gestao_meta']);
		elseif ($gestao_data['pratica_indicador_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_indicador_gestao_canvas']);
		elseif ($gestao_data['pratica_indicador_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['pratica_indicador_gestao_risco']);
		elseif ($gestao_data['pratica_indicador_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_indicador_gestao_risco_resposta']);
		
		elseif ($gestao_data['pratica_indicador_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['pratica_indicador_gestao_semelhante']);
		
		elseif ($gestao_data['pratica_indicador_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['pratica_indicador_gestao_calendario']);
		elseif ($gestao_data['pratica_indicador_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_indicador_gestao_monitoramento']);
		elseif ($gestao_data['pratica_indicador_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['pratica_indicador_gestao_ata']);
		elseif ($gestao_data['pratica_indicador_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['pratica_indicador_gestao_mswot']);
		elseif ($gestao_data['pratica_indicador_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['pratica_indicador_gestao_swot']);
		elseif ($gestao_data['pratica_indicador_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_indicador_gestao_operativo']);
		elseif ($gestao_data['pratica_indicador_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_indicador_gestao_instrumento']);
		elseif ($gestao_data['pratica_indicador_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_indicador_gestao_recurso']);
		elseif ($gestao_data['pratica_indicador_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['pratica_indicador_gestao_problema']);
		elseif ($gestao_data['pratica_indicador_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_indicador_gestao_demanda']);	
		elseif ($gestao_data['pratica_indicador_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['pratica_indicador_gestao_programa']);
		elseif ($gestao_data['pratica_indicador_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_indicador_gestao_licao']);
		elseif ($gestao_data['pratica_indicador_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_indicador_gestao_evento']);
		elseif ($gestao_data['pratica_indicador_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['pratica_indicador_gestao_link']);
		elseif ($gestao_data['pratica_indicador_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_indicador_gestao_avaliacao']);
		elseif ($gestao_data['pratica_indicador_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_indicador_gestao_tgn']);
		elseif ($gestao_data['pratica_indicador_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['pratica_indicador_gestao_brainstorm']);
		elseif ($gestao_data['pratica_indicador_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['pratica_indicador_gestao_gut']);
		elseif ($gestao_data['pratica_indicador_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['pratica_indicador_gestao_causa_efeito']);
		elseif ($gestao_data['pratica_indicador_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_indicador_gestao_arquivo']);
		elseif ($gestao_data['pratica_indicador_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['pratica_indicador_gestao_forum']);
		elseif ($gestao_data['pratica_indicador_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_indicador_gestao_checklist']);
		elseif ($gestao_data['pratica_indicador_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['pratica_indicador_gestao_agenda']);
		elseif ($gestao_data['pratica_indicador_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['pratica_indicador_gestao_agrupamento']);
		elseif ($gestao_data['pratica_indicador_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_indicador_gestao_patrocinador']);
		elseif ($gestao_data['pratica_indicador_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['pratica_indicador_gestao_template']);
		elseif ($gestao_data['pratica_indicador_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['pratica_indicador_gestao_painel']);
		elseif ($gestao_data['pratica_indicador_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_indicador_gestao_painel_odometro']);
		elseif ($gestao_data['pratica_indicador_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['pratica_indicador_gestao_painel_composicao']);		
		elseif ($gestao_data['pratica_indicador_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['pratica_indicador_gestao_tr']);	
		elseif ($gestao_data['pratica_indicador_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['pratica_indicador_gestao_me']);	
		elseif ($gestao_data['pratica_indicador_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['pratica_indicador_gestao_acao_item']);	
		elseif ($gestao_data['pratica_indicador_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['pratica_indicador_gestao_beneficio']);	
		elseif ($gestao_data['pratica_indicador_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['pratica_indicador_gestao_painel_slideshow']);	
		elseif ($gestao_data['pratica_indicador_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['pratica_indicador_gestao_projeto_viabilidade']);	
		elseif ($gestao_data['pratica_indicador_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['pratica_indicador_gestao_projeto_abertura']);	
		elseif ($gestao_data['pratica_indicador_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['pratica_indicador_gestao_plano_gestao']);	
		elseif ($gestao_data['pratica_indicador_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['pratica_indicador_gestao_ssti']);
		elseif ($gestao_data['pratica_indicador_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['pratica_indicador_gestao_laudo']);
		elseif ($gestao_data['pratica_indicador_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['pratica_indicador_gestao_trelo']);
		elseif ($gestao_data['pratica_indicador_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['pratica_indicador_gestao_trelo_cartao']);
		elseif ($gestao_data['pratica_indicador_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['pratica_indicador_gestao_pdcl']);
		elseif ($gestao_data['pratica_indicador_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['pratica_indicador_gestao_pdcl_item']);
		
		}
	echo '</td></tr>';
	}	
	

if ($pratica_indicador['pratica_indicador_requisito_descricao']) echo '<tr><td align="right">'.dica('Descrição', 'Descrição do indicador.').'Descrição:'.dicaF().'</td><td class="realce" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica_indicador['pratica_indicador_requisito_descricao'].'</td></tr>';
if ($Aplic->profissional){
	if ($pratica_indicador['pratica_indicador_ano']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ano Inicial', 'O ano em que se iniciou a utilização deste indicador.').'Ano inicial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$pratica_indicador['pratica_indicador_ano'].'</td></tr>';
	if ($pratica_indicador['pratica_indicador_codigo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Código', 'O código do indicador.').'Código:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$pratica_indicador['pratica_indicador_codigo'].'</td></tr>';
	$obj_indicador = new CIndicador($pratica_indicador_id);
	$obj_indicador->load($pratica_indicador_id);
	if ($pratica_indicador['pratica_indicador_setor']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o indicador.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj_indicador->getSetor().'</td></tr>';
	if ($pratica_indicador['pratica_indicador_segmento']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o indicador.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj_indicador->getSegmento().'</td></tr>';
	if ($pratica_indicador['pratica_indicador_intervencao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o indicador.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj_indicador->getIntervencao().'</td></tr>';
	if ($pratica_indicador['pratica_indicador_tipo_intervencao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o indicador.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj_indicador->getTipoIntervencao().'</td></tr>';
	}



if ($pratica_indicador['pratica_indicador_composicao']){

	$sql->adTabela('pratica_indicador_composicao');
	$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_id=pratica_indicador_composicao_filho');
	$sql->adCampo('pratica_indicador_composicao_filho, pratica_indicador_composicao_peso, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_composicao_pai ='.(int)$pratica_indicador_id);
	$composicao = $sql->Lista();
	$sql->limpar();

	$saida_composicao='';
	if ($composicao && count($composicao)) {
		$saida_composicao.= '<table cellspacing=0 cellpadding=0 width="100%">';
		$saida_composicao.= '<tr><td>'.number_format($composicao[0]['pratica_indicador_composicao_peso'], 2, '.', '').' - '.link_indicador($composicao[0]['pratica_indicador_composicao_filho']);
		$qnt_composicao=count($composicao);
		if ($qnt_composicao > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_composicao; $i < $i_cmp; $i++) $lista.=number_format($composicao[$i]['pratica_indicador_composicao_peso'], 2, '.', '').' - '.link_indicador($composicao[$i]['pratica_indicador_composicao_filho']).'<br>';
				$saida_composicao.= dica('Outros Indicadores', 'Clique para visualizar os demais indicadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'composicao\');">(+'.($qnt_composicao - 1).')</a>'.dicaF(). '<span style="display: none" id="composicao"><br>'.$lista.'</span>';
				}
		$saida_composicao.= '</td></tr></table>';
		}

	if ($saida_composicao) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Composição', 'Este indicador é composto da média ponderada de outros indicadores.').'Composição:'.dicaF().'</td><td class="realce" width="100%"><table cellpadding=0 cellspacing=0><tr><td>'.$saida_composicao.'</td><td><a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\'Composição\', 830, 630, \'m=praticas&a=indicador_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', null, window) : window.open(\'./index.php?m=praticas&a=indicador_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Composição\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/indicador_exp_p.png','Composição','Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição do indicador.').'</a></td></tr></table></td></tr>';
	}




if ($pratica_indicador['pratica_indicador_formula'] && $pratica_indicador['pratica_indicador_calculo']) {
	$sql->adTabela('pratica_indicador_formula');
	$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador_id=pratica_indicador_formula_filho');
	$sql->esqUnir('cias','cias', 'cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_formula_filho, pratica_indicador_agrupar, pratica_indicador_formula_ordem, pratica_indicador_nome, cia_nome, pratica_indicador_formula_rocado');
	$sql->adOnde('pratica_indicador_formula_pai = '.(int)$pratica_indicador_id);
	$lista_formula = $sql->Lista();

	$saida_formula='';
	$lista='';
	if ($lista_formula && count($lista_formula)) {
			$saida_formula.= '<table cellspacing=0 cellpadding=0 width=100%>';
			$saida_formula.= '<tr><td>'.$pratica_indicador['pratica_indicador_calculo'];
			$qnt_lista_formula=count($lista_formula);
			foreach ($lista_formula as $formula) {
				$objFormulula = new Indicador($formula['pratica_indicador_formula_filho']);
				$valorF=$objFormulula->Valor_atual($pratica_indicador['pratica_indicador_agrupar'], $ano);
				$lista.='<tr><td>'.link_indicador($formula['pratica_indicador_id']).' - '.$formula['cia_nome'].($formula['pratica_indicador_formula_rocado'] ? ' - deslocado' : '').'</td><td align=center>I'.($formula['pratica_indicador_formula_ordem']< 10 ? '0' : '').$formula['pratica_indicador_formula_ordem'].'</td><td align=right>'.($valorF !==null ? number_format($valorF, $config['casas_decimais'], ',', '.') : 'sem valor').'</td></tr>';		
				}



			$saida_formula.= dica('Legenda dos Indicadores', 'Clique para visualizar a legenda dos indicadores que compoem a fórmula.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_formula\');">('.($qnt_lista_formula).')</a>'.dicaF(). '<div style="display: none" id="lista_formula"><br><table class="tbl1">'.$lista.'</table></div>';
			$saida_formula.= '</td></tr></table>';
			}
	echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Fórmula', 'Fórmula.').'Fórmula:'.dicaF().'</td><td class="realce" width="100%">'.$saida_formula.'</td></tr>';
	}


if ($pratica_indicador['pratica_indicador_formula_simples'] && $pratica_indicador['pratica_indicador_calculo']) {
	$sql->adTabela('pratica_indicador_formula_simples');
	$sql->adOnde('pratica_indicador_formula_simples_indicador = '.(int)$pratica_indicador_id);
	$sql->adCampo('pratica_indicador_formula_simples.*');
	$sql->adOrdem('ordem');
	$lista_formula = $sql->Lista();
	$sql->limpar();

	$saida_formula='';
	$lista='';
	if ($lista_formula && count($lista_formula)) {
			$saida_formula.= '<table cellspacing=0 cellpadding=0>';
			$saida_formula.= '<tr><td>'.$pratica_indicador['pratica_indicador_calculo'];
			$qnt_lista_formula=count($lista_formula);
			for ($i = 0, $i_cmp = $qnt_lista_formula; $i < $i_cmp; $i++) $lista.='I'.($lista_formula[$i]['ordem']< 10 ? '0' : '').$lista_formula[$i]['ordem'].' - '.$lista_formula[$i]['pratica_indicador_formula_simples_nome'].'<br>';
			$saida_formula.= dica('Legenda das Variáveis', 'Clique para visualizar a legenda das variáveis que compoem a fórmula.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_formula_simples\');">('.($qnt_lista_formula).')</a>'.dicaF(). '<span style="display: none" id="lista_formula_simples"><br>'.$lista.'</span>';
			$saida_formula.= '</td></tr></table>';
			}
	echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Fórmula', 'Fórmula.').'Fórmula:'.dicaF().'</td><td class="realce" width="100%">'.$saida_formula.'</td></tr>';
	}


if ($pratica_indicador['pratica_indicador_checklist']) {
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Checklist', 'O checklist utilizado para ste indicador.').'Checklist:'.dicaF().'</td><td class="realce" width="100%">'.link_checklist($pratica_indicador['pratica_indicador_checklist']).'</td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor do Checklist', 'Caso afirmativo, em vez do resultado do checklist ser a porcentagem de respostas marcadas como sim, o retorno será a soma dos pesos das linhas marcadas como sim.').'Valor do checklist:'.dicaF().'</td><td class="realce" width="100%">'.($pratica_indicador['pratica_indicador_checklist_valor'] ? 'Sim' : 'Não').'</td></tr>';
	}


if ($pratica_indicador['pratica_indicador_requisito_oque']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('O Que Fazer', 'Sumário sobre o que se trata este indicador.').'O Que:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_oque'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_porque']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Por Que Fazer', 'Por que este indicador será mensurado.').'Por que:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_porque'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_onde']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Onde Fazer', 'Onde o indicador é mensurado.').'Onde:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_onde'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_quando']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Quando Fazer', 'Quando o indicador é mensurado.').'Quando:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_quando'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_como']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Como Fazer', 'Como  o indicador é mensurado.').'Como:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_como'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_quanto']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Quanto Custa', 'Custo para mensurar o indicador.').'Quanto:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_quanto'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_quem']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão encarregados deste indicador.').'Quem:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$pratica_indicador['pratica_indicador_requisito_quem'].'</td></tr>';

if ($pratica_indicador['pratica_indicador_tipo']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Tipo', 'O tipo de indicador.').'Tipo:'.dicaF().'</td><td class="realce" width="100%">'.getSisValorCampo('IndicadorTipo',$pratica_indicador['pratica_indicador_tipo']).'</td></tr>';

if (isset($pratica_indicador_acesso[$pratica_indicador['pratica_indicador_acesso']])) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Nível de Acesso', 'Os indicadores podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o indicador.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designados para o indicador podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar e os designados inserir valores quando for o caso.</li><li><b>Participante I</b> - Somente o responsável e os designados para o indicador podem ver e editar o mesmo</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável e os designados para o indicador podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$pratica_indicador_acesso[$pratica_indicador['pratica_indicador_acesso']].'</td></tr>';


if ($Aplic->profissional){

	$parametros_projeto = array(
		'' => '',
		'fisico_previsto' => 'Físico - Previsto até a data atual',
		'fisico_velocidade' => 'Físico - Velocidade',
		'progresso' => 'Físico - Percentagem executada',
		'financeiro_previsto' => 'Financeiro - Previsto até a data atual',
		'total_estimado_total' => 'Financeiro - Previsto até o final',
		'financeiro_velocidade' => 'Financeiro - Velocidade',
		'recurso_previsto' => 'Recursos - Custo até a data atual',
		'recurso_valor_agregado' => 'Recursos - Valor agregado',
		'recurso_ept' => 'Recursos - Estimativa para Terminar (EPT)',
		'recurso_previsto_total' => 'Recursos - Custo até o final',
		'recurso_gasto' => 'Recursos - Gasto',
		'mo_previsto' => 'Mão de obra - Custo previsto até a data atual',
		'mo_valor_agregado' => 'Mão de obra - Valor agregado',
		'mao_obra_ept' => 'Mão de obra - Estimativa para Terminar (EPT)',
		'mo_previsto_total' => 'Mão de obra - Custo até o final',
		'mo_gasto' => 'Mão de obra - Gasto',
		'custo_estimado_hoje' => 'Planilha de custo - Até a data atual',
		'custo_valor_agregado' => 'Planilha de custo - Valor agregado',
		'custo_ept' => 'Planilha de custo - Estimativa para Terminar (EPT)',
		'custo_estimado' => 'Planilha de custo -  Até o final',
		'valor_agregado' => 'Valor agregado',
		'ept' => 'Estimativa para Terminar (EPT)',
		'idc' => 'Índice de Desempenho de Custos (IDC)',
		'idpt' => 'Índice de desempenho para Término (IDPT)',
		'total_recursos' => 'Recursos financeiros alocados',
		'gasto_efetuado' => 'Planilha de Gastos - Total',
		'gasto_registro' => 'Gastos extras' ,
		'homem_hora' => 'Homem/Hora',
		'ata_acao' => 'Deliberações de atas de reunião concluídas'
		);
	$parametros_projeto['emprego_obra']='Empregos gerados durante a execução';
	$parametros_projeto['emprego_direto']='Empregos diretos após a conclusão';
	$parametros_projeto['emprego_indireto']='Empregos indiretos após a conclusão';
	$parametros_projeto['quantidade_adquirida']='Quantidade adquirida';
	$parametros_projeto['quantidade_prevista']='Quantidade prevista';
	$parametros_projeto['quantidade_realizada']='Quantidade realizada';
	$parametros_projeto['realizada_prevista']='Quantidade realizada pela prevista (%)';
	$parametros_projeto['adquirida_prevista']='Quantidade adquirida pela prevista (%)';
	$parametros_projeto['prioridade']='Priorização';
	$parametros_projeto['prioridade_fisico']='Priorização X Físico';
	$parametros_projeto['prioridade_vel_fisico']='Priorização X Velocidade do físico';

	if ($pratica_indicador['pratica_indicador_campo_projeto'] && $pratica_indicador['pratica_indicador_parametro_projeto'] && isset($parametros_projeto[$pratica_indicador['pratica_indicador_parametro_projeto']])) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Valor de '.ucfirst($config['projeto']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Valor de '.$config['projeto'].':'.dicaF().'</td><td class="realce" width="100%">'.$parametros_projeto[$pratica_indicador['pratica_indicador_parametro_projeto']].'</td></tr>';

		$parametros_tarefa = array(
		'' => '',
		'fisico_previsto' => 'Físico - Previsto até a data atual',
		'fisico_velocidade' => 'Físico - Velocidade',
		'financeiro_previsto' => 'Financeiro - Previsto até a data atual',
		'total_estimado_total' => 'Financeiro - Previsto até o final',
		'financeiro_velocidade' => 'Financeiro - Velocidade',
		'recurso_previsto' => 'Recursos - Custo até a data atual',
		'recurso_valor_agregado' => 'Recursos - Valor agregado',
		'recurso_ept' => 'Recursos - Estimativa para Terminar (EPT)',
		'recurso_previsto_total' => 'Recursos - Custo até o final',
		'recurso_gasto' => 'Recursos - Gasto',
		'mo_previsto' => 'Mão de obra - Custo previsto até a data atual',
		'mo_valor_agregado' => 'Mão de obra - Valor agregado',
		'mao_obra_ept' => 'Mão de obra - Estimativa para Terminar (EPT)',
		'mo_previsto_total' => 'Mão de obra - Custo até o final',
		'mo_gasto' => 'Mão de obra - Gasto',
		'custo_estimado_hoje' => 'Planilha de custo - Até a data atual',
		'custo_valor_agregado' => 'Planilha de custo - Valor agregado',
		'custo_ept' => 'Planilha de custo - Estimativa para Terminar (EPT)',
		'custo_estimado' => 'Planilha de custo -  Até o final',
		'valor_agregado' => 'Valor agregado',
		'ept' => 'Estimativa para Terminar (EPT)',
		'idc' => 'Índice de Desempenho de Custos (IDC)',
		'idpt' => 'Índice de desempenho para Término (IDPT)',
		'progresso' => 'Percentagem executada',
		'total_recursos' => 'Recursos financeiros alocados',
		'gasto_efetuado' => 'Planilha de Gastos - Total',
		'gasto_registro' => 'Gastos extras',
		'ata_acao' => 'Deliberações de atas de reunião concluídas'
		);
	$parametros_tarefa['emprego_obra']='Empregos gerados durante a execução';
	$parametros_tarefa['emprego_direto']='Empregos diretos após a conclusão';
	$parametros_tarefa['emprego_indireto']='Empregos indiretos após a conclusão';
	$parametros_tarefa['quantidade_adquirida']='Quantidade adquirida';
	$parametros_tarefa['quantidade_prevista']='Quantidade prevista';
	$parametros_tarefa['quantidade_realizada']='Quantidade realizada';
	$parametros_tarefa['realizada_prevista']='Quantidade realizada pela prevista (%)';
	$parametros_tarefa['adquirida_prevista']='Quantidade adquirida pela prevista (%)';

	$parametros_acao = array(
		'' => '',
		'progresso' => 'Percentagem executada'
		);

	if ($pratica_indicador['pratica_indicador_campo_tarefa'] && $pratica_indicador['pratica_indicador_parametro_tarefa'] && isset($parametros_tarefa[$pratica_indicador['pratica_indicador_parametro_tarefa']])) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Valor de '.ucfirst($config['tarefa']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Valor de '.$config['tarefa'].':'.dicaF().'</td><td class="realce" width="100%">'.$parametros_tarefa[$pratica_indicador['pratica_indicador_parametro_tarefa']].'</td></tr>';
	if ($pratica_indicador['pratica_indicador_campo_acao'] && $pratica_indicador['pratica_indicador_parametro_acao'] && isset($parametros_acao[$pratica_indicador['pratica_indicador_parametro_acao']])) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Valor de '.ucfirst($config['acao']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_acao'].' '.$config['acao'].'.').'Valor de '.$config['acao'].':'.dicaF().'</td><td class="realce" width="100%">'.$parametros_acao[$pratica_indicador['pratica_indicador_parametro_acao']].'</td></tr>';

	$sql->adTabela('pratica_indicador_filtro');
	$sql->adCampo('pratica_indicador_filtro.*');
	if ($pratica_indicador_id) $sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
	else $sql->adOnde('uuid = \''.$uuid.'\'');
	$filtros = $sql->lista();
	$sql->limpar();
	if (count($filtros)){

		echo '<tr><td align="right" valign="middle" style="white-space: nowrap">'.dica('Filtros', 'Os filtros dos valores retirados automaticamente d'.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Filtros:'.dicaF().'</td><td><table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
		$social=$Aplic->modulo_ativo('social');
		foreach($filtros as $linha) {
			echo '<tr><td><table cellpadding=0 cellspacing=0 width="100%">';
			if ($linha['pratica_indicador_filtro_status'] && !isset($status_tarefa)) $status_tarefa = getSisValor('StatusTarefa');
			if ($linha['pratica_indicador_filtro_status'] && isset($status_tarefa[$linha['pratica_indicador_filtro_status']])) echo'<tr><td align=right width="90">Status:</td><td>'.$status_tarefa[$linha['pratica_indicador_filtro_status']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_tipo']) echo'<tr><td align=right width="90">Tipo de '.$config['tarefa'].':</td><td>'.getSisValorCampo('TipoTarefa', $linha['pratica_indicador_filtro_tipo']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_prioridade'] && !isset($prioridade_tarefa)) $prioridade_tarefa = getSisValor('PrioridadeTarefa');
			if ($linha['pratica_indicador_filtro_prioridade'] && isset($prioridade_tarefa[$linha['pratica_indicador_filtro_prioridade']])) echo'<tr><td align=right width="90">Prioridade:</td><td>'.$prioridade_tarefa[$linha['pratica_indicador_filtro_prioridade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_setor']) echo'<tr><td align=right width="90">Setor:</td><td>'.getSisValorCampo('TarefaSetor', $linha['pratica_indicador_filtro_setor']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_segmento']) echo'<tr><td align=right width="90">Segmento:</td><td>'.getSisValorCampo('TarefaSegmento', $linha['pratica_indicador_filtro_segmento']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_intervencao']) echo'<tr><td align=right width="90">Intervenção:</td><td>'.getSisValorCampo('TarefaIntervencao', $linha['pratica_indicador_filtro_intervencao']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_tipo_intervencao']) echo'<tr><td align=right width="90">Tipo:</td><td>'.getSisValorCampo('TarefaTipoIntervencao', $linha['pratica_indicador_filtro_tipo_intervencao']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_social'] && !isset($programa_social)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social', 'social', 'social_id=pratica_indicador_filtro_social');
				$sql->adCampo('social_id, social_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$programa_social = $sql->listaVetorChave('social_id', 'social_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_social'] && isset($programa_social[$linha['pratica_indicador_filtro_social']])) echo'<tr><td align=right width="90">Programa:</td><td>'.$programa_social[$linha['pratica_indicador_filtro_social']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_acao'] && !isset($acao_social)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=pratica_indicador_filtro_acao');
				$sql->adCampo('social_acao_id, social_acao_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$acao_social = $sql->listaVetorChave('social_acao_id', 'social_acao_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_acao'] && isset($acao_social[$linha['pratica_indicador_filtro_acao']])) echo'<tr><td align=right width="90">Ação:</td><td>'.$acao_social[$linha['pratica_indicador_filtro_acao']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_estado'] && !isset($estado)) {
				$sql->adTabela('estado');
				$sql->adCampo('estado_sigla, estado_nome');
				$sql->adOrdem('estado_nome');
				$estado=$sql->listaVetorChave('estado_sigla', 'estado_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_estado'] && isset($estado[$linha['pratica_indicador_filtro_estado']])) echo'<tr><td align=right width="90">Estado:</td><td>'.$estado[$linha['pratica_indicador_filtro_estado']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_cidade'] && !isset($municipio)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('municipios', 'municipios', 'municipios.municipio_id=pratica_indicador_filtro_cidade');
				$sql->adCampo('municipio_id, municipio_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$municipio = $sql->listaVetorChave('municipio_id', 'municipio_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_cidade'] && isset($municipio[$linha['pratica_indicador_filtro_cidade']])) echo'<tr><td align=right width="90">Município:</td><td>'.$municipio[$linha['pratica_indicador_filtro_cidade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_comunidade'] && !isset($comunidade)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=pratica_indicador_filtro_comunidade');
				$sql->adCampo('social_comunidade_id, social_comunidade_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$comunidade = $sql->listaVetorChave('social_comunidade_id', 'social_comunidade_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_comunidade'] && isset($comunidade[$linha['pratica_indicador_filtro_comunidade']])) echo'<tr><td align=right width="90">Comunidade:</td><td>'.$comunidade[$linha['pratica_indicador_filtro_comunidade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_texto']) echo'<tr><td align=right width="90">Texto:</td><td>'.$linha['pratica_indicador_filtro_texto'].'</td></tr>';
			echo '</table></td></tr>';
			}
		echo '</table></td></tr>';
		}

	}








if ($pratica_indicador['pratica_indicador_externo']) {
	$sql->adTabela('pratica_indicador_externo');
	$sql->adOnde('pratica_indicador_externo_indicador = '.(int)$pratica_indicador_id);
	$sql->adCampo('pratica_indicador_externo.*');
	$externo = $sql->linha();
	$sql->limpar();

	$tipo_base=array('mysql'=> 'MySQL', 'postgresql'=>'PostgreSQL', 'oracle11g'=>'Oracle 11g', 'oracle10'=>'Oracle 10g', 'sqlserver' => 'SQL Server');

	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.dica('Externo','Este indicador retira seus valores de uma base externa.').'&nbsp;<b>Externo</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0 width=100%>';


	if (isset($tipo_base[$externo['pratica_indicador_externo_tipo']])) echo '<tr><td align="right" style="white-space: nowrap" width=100>'.dica('SGBD', 'O sistema gerenciador de banco de dados.').'SGBD:'.dicaF().'</td><td class="realce">'.$tipo_base[$externo['pratica_indicador_externo_tipo']].'</td></tr>';
	if ($podeEditar && $externo['pratica_indicador_externo_usuario']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Login', 'O login para acessar o banco de dados.').'Login:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_usuario'].'</td></tr>';
	if ($podeEditar && $externo['pratica_indicador_externo_senha']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Senha', 'A senha para acessar o banco de dados.').'Senha:'.dicaF().'</td><td class="realce">'.str_repeat("*", strlen($externo['pratica_indicador_externo_senha'])).'</td></tr>';
	if ($externo['pratica_indicador_externo_base']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Banco de Dados', 'Nome do banco de dados onde se encontra a tabela ou a visão a ser lida.').'Banco de Dados:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_base'].'</td></tr>';
	if ($externo['pratica_indicador_externo_tabela']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tabela', 'Nomre da tabela a ser consultada.').'Tabela:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_tabela'].'</td></tr>';
	if ($externo['pratica_indicador_externo_data']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Campo Data', 'Nome da coluna da tabela que representa a data do valor.').'Campo Data:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_data'].'</td></tr>';
	if ($externo['pratica_indicador_externo_valor']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Campo Valor', 'Nome da coluna da tabela que representa o valor.').'Campo Valor:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_valor'].'</td></tr>';
	if ($externo['pratica_indicador_externo_chave']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Chave de Pesquisa', 'Chave de filtragem da tabela pesquisada, para o comando SQL WHERE.<br>Ex: Em SELECT XX FROM YY WHERE <b>indicador</b>=25 seria indicador').'Chave de Pesquisa:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_chave'].'</td></tr>';
	if ($externo['pratica_indicador_externo_chave_valor']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor da Chave', 'O valor da chave de filtragem da tabela cacadastrada, para o comando SQL WHERE.<br>Ex: Em SELECT XX FROM YY WHERE indicador=<b>25</b> seria o valor 25').'Valor da Chave:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_chave_valor'].'</td></tr>';
	if ($externo['pratica_indicador_externo_charset']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Charset', 'Tipo de chatset da tabela a ser lida.').'Charset:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_charset'].'</td></tr>';
	if ($externo['pratica_indicador_externo_modo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Modo', 'Modo da tabela a ser lida.').'Modo:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_modo'].'</td></tr>';
	if ($externo['pratica_indicador_externo_string_conexao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Conexão', 'String de conexão geral').'Conexão:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_string_conexao'].'</td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor Simples', 'Caso o objetivo do indicador é buscar apenas um único valor consolidado, em vez d um vetor de valores dispostos no tempo, deverá estar marcado como sim este cammpo').'Valor Simples:'.dicaF().'</td><td class="realce">'.($externo['pratica_indicador_externo_simples'] ? 'Sim' : 'Não').'</td></tr>';
	if ($externo['pratica_indicador_externo_sql']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Consulta SQL', 'Comando SQL de consulta para os casos de acesso a valor simples.').'Consulta SQL:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_sql'].'</td></tr>';


	echo '</table></fieldset></td></tr>';
	}












//campos utilizados na regua específica
$sql->adTabela('pratica_regra');
$sql->esqUnir('pratica_regra_campo', 'pratica_regra_campo', 'pratica_regra_campo_nome=pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=1');
$sql->adOrdem('subitem ASC, pratica_regra_ordem');
$sql->adGrupo('pratica_regra_campo_nome');
$vetor_campos=$sql->lista();
$sql->limpar();


if ($pratica_indicador['pratica_indicador_desde_quando']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Desde Quando é Mensurado', 'Desde quando o indicador é mensurado.').'Desde quando:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$data_desde->format('%d/%m/%Y').'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_melhorias']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Melhorias efetuadas no Indicador', 'Quais as melhorias realizadas no indicador após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.($pratica_indicador['pratica_indicador_requisito_melhorias']).'</td></tr>';

$tipo_polaridade=array(0 => 'Melhor se menor', 1 => 'Melhor se maior', 2 => 'Melhor se no centro');
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Polaridade', 'Qual a polaridade dos valores do indicador.').'Polaridade:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.(isset($tipo_polaridade[$pratica_indicador['pratica_indicador_sentido']]) ? $tipo_polaridade[$pratica_indicador['pratica_indicador_sentido']] : 'não definido').'</td></tr>';


if ($pratica_indicador['pratica_indicador_requisito_referencial']) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Referencial Comparativo', 'Qual o referencial comparativo para este indicador.').'Referencial comparativo:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$pratica_indicador['pratica_indicador_requisito_referencial'].'</td></tr>';
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Valor do Referencial Comparativo', 'Qual o valor do referencial comparativo.').'Valor do referencial'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').':'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.number_format($obj->pratica_indicador_valor_referencial, $config['casas_decimais'], ',', '.').'</td></tr>';
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Meta', 'Qual o valor a ser alcançado pelo indicador para que seje considerado excelente.').'Meta:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.(!$pratica_indicador['pratica_indicador_composicao'] ? number_format($obj->pratica_indicador_valor_meta, $config['casas_decimais'], ',', '.') : 100).($obj->pratica_indicador_meta_proporcao ? ' x período anterior' : '').($pratica_indicador['pratica_indicador_unidade'] ? ' '.$pratica_indicador['pratica_indicador_unidade'] : '').'</td></tr>';

if ($obj->pratica_indicador_valor_meta_boa!=null) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Nível Bom', 'Qual o valor do indicador é aceitável com bom.').'Nível bom'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').':'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.number_format($obj->pratica_indicador_valor_meta_boa, $config['casas_decimais'], ',', '.').'</td></tr>';
if ($obj->pratica_indicador_valor_meta_regular!=null) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Nível Regular', 'Qual o valor do indicador é aceitável com regulr.').'Nível regular'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').':'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.number_format($obj->pratica_indicador_valor_meta_regular, $config['casas_decimais'], ',', '.').'</td></tr>';
if ($obj->pratica_indicador_valor_meta_ruim!=null) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Nível Ruim', 'Qual o valor do indicador é considerado ruim.').'Nível ruim'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').':'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.number_format($obj->pratica_indicador_valor_meta_ruim, $config['casas_decimais'], ',', '.').'</td></tr>';
if ($obj->pratica_indicador_data_meta) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Data para a Meta', 'Qual a data estipulada para alcançar a meta.').'Data para meta:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.retorna_data($obj->pratica_indicador_data_meta, false).'</td></tr>';
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Valor do Período', ($pratica_indicador['pratica_indicador_composicao'] ? 'No caso de composição o valor é sempre a pontuação. A média ponderada da pontuação dos indicadores pertencentes a composição.<br><br>A pontuação de cada indicador componente é a razão entre o valor do indicador e a meta estipulada, em porcentagem. Quanto mais próximo de 100%, melhor.' : 'O último valor do período considerado.')).'Valor do Período:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.($valor !==null ? number_format($valor, $config['casas_decimais'], ',', '.') : 'sem valor').'</td></tr>';

echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Pontuação', ($pratica_indicador['pratica_indicador_composicao'] ? 'A média ponderada da pontuação dos indicadores pertencentes a composição.<br><br>A pontuação de cada indicador componente é a razão entre o valor do indicador e a meta estipulada, em porcentagem. Quanto mais próximo de 100%, melhor.' : 'A pontuação é a razão entre o valor do indicador e a meta estipulada, em porcentagem. Quanto mais próximo de 100%, melhor.')).'Pontuação:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$obj->Pontuacao($ano, null, null, true).'%</td></tr>';
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Tipo de tendência', 'Qual a tendência dos valores deste indicador.').'Tipo de tendência:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$obj->Tendencia().'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tolerância', 'A tolerância da pontuação obtida em relação à meta.<br>Ex:Com a tolerância de 15% uma pontuação final de 85% seria visualizada como 100%.').'Tolerância'.dicaF().':</td><td width="100%" colspan=2 class="realce">'.number_format($pratica_indicador['pratica_indicador_tolerancia'], 2, ',', '.').'%</td><tr>';


//if ($Aplic->profissional) echo '<tr id="formula_simples_variacao" '.($obj->pratica_indicador_formula_simples ? 'style="display:"' : 'style="display:none"').'><td align="right" style="white-space: nowrap">'.dica('Variáveis nos Períodos', 'Caso esteja marcado, em vez de utilizar o resultado de cada ponto, as variaveis são somadas antes dentro dos períodos definidos..').'Variáveis nos períodos:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" class="texto" name="pratica_indicador_formula_simples_variacao" value="1" '.(isset($obj->pratica_indicador_formula_simples_variacao) && $obj->pratica_indicador_formula_simples_variacao ? 'checked="checked"' : '').' /></td></td></tr></table></td></tr>';

if ($obj->pratica_indicador_formula_simples) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Variáveis nos Períodos', 'Caso esteja marcado, em vez de utilizar o resultado de cada ponto, as variaveis são computadas antes dentro dos períodos definidos.').'Variáveis nos períodos'.dicaF().':</td><td width="100%" colspan=2 class="realce">'.($obj->pratica_indicador_formula_simples_variacao ? 'Sim' : 'Não').'</td><tr>';



$tipo_agrupamento=array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'Mês','bimestre' => 'Bimestre','trimestre' => 'Trimestre','quadrimestre' => 'Quadrimestre','semestre' => 'Semestre', 'ano' => 'Ano', 'nenhum' => 'Nenhum agrupamento');
$tipo_acumulacao=array('media_simples' => 'Média simples dos valores do período', 'soma' => 'Soma dos valores do período', 'saldo' => 'Último valor do período');
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Tipo de Acumulação', 'Qual a forma de acumulação dos valores do indicador inseridos no período.').'Tipo de acumulação:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.(isset($tipo_acumulacao[$pratica_indicador['pratica_indicador_acumulacao']]) ? $tipo_acumulacao[$pratica_indicador['pratica_indicador_acumulacao']] : 'não definido').'</td></tr>';
echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Período', 'Qual o período de agrupamento dos valores</li></ul>').'Período:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.(isset($tipo_agrupamento[$pratica_indicador['pratica_indicador_agrupar']]) ? $tipo_agrupamento[$pratica_indicador['pratica_indicador_agrupar']] : 'não definido').'</td></tr>';

echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Pontuação do Período Anterior', 'Caso positivo os cálculos consideram apenas do período anterior para trás (pois o atual não fecha até o final do mesmo).').'Pontuação do período anterior:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.($obj->pratica_indicador_periodo_anterior ? 'Sim' : 'Não').'</td></tr>';


$sql->adTabela('pratica_indicador_requisito');
$sql->adCampo('pratica_indicador_requisito.*');
if (!$ano) $sql->adOnde('pratica_indicador_requisito_id = '.(int)$pratica_indicador['pratica_indicador_requisito']);
else $sql->adOnde('pratica_indicador_requisito_ano = '.(int)$ano);
$sql->adOnde('pratica_indicador_requisito_indicador = '.(int)$pratica_indicador_id);
$requisito = $sql->linha();
$sql->limpar();


$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=1');
$sql->adOrdem('pratica_regra_campo_id');
$lista=$sql->Lista();
$sql->limpar();

$vetor_existe=array(
	'pratica_indicador_tendencia',
	'pratica_indicador_favoravel',
	'pratica_indicador_superior',
	'pratica_indicador_relevante',
	'pratica_indicador_atendimento',
	'pratica_indicador_lider',
	'pratica_indicador_excelencia',
	'pratica_indicador_estrategico'
	);


$original=array();
foreach($lista as $linha){
	if (in_array($linha['pratica_regra_campo_nome'], $vetor_existe))	$original[$linha['pratica_regra_campo_nome']]=dica($linha['pratica_regra_campo_texto'], $linha['pratica_regra_campo_descricao']).$linha['pratica_regra_campo_texto'].':'.dicaF();
	}
if (in_array('pratica_indicador_tendencia', $vetor_campos)) echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_indicador_tendencia'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_indicador_requisito_tendencia'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_tendencia'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_tendencia'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_favoravel', $vetor_campos)) echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_indicador_favoravel'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_indicador_requisito_favoravel'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_favoravel'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_favoravel'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_superior', $vetor_campos)) echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_indicador_superior'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_indicador_requisito_superior'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_superior'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_superior'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_relevante', $vetor_campos)) echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_indicador_relevante'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_indicador_requisito_relevante'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_relevante'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_relevante'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_atendimento', $vetor_campos)) echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_indicador_atendimento'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_indicador_requisito_atendimento'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_atendimento'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_atendimento'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_lider', $vetor_campos)) echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_indicador_lider'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_indicador_requisito_lider'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_lider'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_lider'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_excelencia', $vetor_campos)) echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_indicador_excelencia'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_indicador_requisito_excelencia'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_excelencia'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_excelencia'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_estrategico', $vetor_campos)) echo '<tr><td align="right" style="white-space: nowrap">'.$original['pratica_indicador_estrategico'].'</td><td><table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap;width:35px; '.($requisito['pratica_indicador_requisito_estrategico'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_estrategico'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_estrategico'].'</td></tr></table></td></tr>';


if ($Aplic->profissional) {
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	if (isset($moedas[$pratica_indicador['pratica_indicador_moeda']])) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'A moeda padrão utilizada.').'Moeda:'.dicaF().'</td><td class="realce" width="100%">'.$moedas[$pratica_indicador['pratica_indicador_moeda']].'</td></tr>';
	}
if ($Aplic->profissional && $tem_aprovacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Aprovado', 'Se o indicador se encontra aprovado.').'Aprovado:'.dicaF().'</td><td  class="realce" width="100%">'.($pratica_indicador['pratica_indicador_aprovado'] ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';





echo '<tr><td align="right">'.dica('Ativo', 'Indica se o indicador ainda está ativo.').'Ativo:'.dicaF().'</td><td class="realce" width="100%">'.($pratica_indicador['pratica_indicador_ativo']  ? 'Sim' : 'Não').'</td></tr>';
if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Alerta Ativo', 'Caso esteja marcado, o indicador será incluído no sistema de alertas automáticos (precisa ser executado em background o arquivo server/alertas/alertas_pro.php).').'Alerta ativo:'.dicaF().'</td><td class="realce" width="100%">'.($pratica_indicador['pratica_indicador_alerta'] ? 'Sim' : 'Não').'</td></tr>';


require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('indicadores', $pratica_indicador['pratica_indicador_id'], 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan=2>';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}




if (!$pratica_indicador['pratica_indicador_composicao']){
	//echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.dica('Meta','Lista de metas vinculadas a este indicador.').'&nbsp;<b>Metas</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';

	$sql->adTabela('pratica_indicador_meta');
	$sql->adCampo('formatar_data(pratica_indicador_meta_data, "%d/%m/%Y") as data, formatar_data(pratica_indicador_meta_data_meta, "%d/%m/%Y") as data_meta');
	$sql->adCampo('pratica_indicador_meta_id, pratica_indicador_meta_valor_referencial, pratica_indicador_meta_valor_meta, pratica_indicador_meta_valor_meta_boa, pratica_indicador_meta_valor_meta_regular, pratica_indicador_meta_valor_meta_ruim, pratica_indicador_meta_proporcao');
	$sql->adOnde('pratica_indicador_meta_indicador = '.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_meta_data');
	$metas = $sql->lista();
	$sql->limpar();

	if (count($metas)){

	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Meta','Lista de metas vinculadas a este indicador.').'Metas:'.dicaF().'</td><td>';



		echo '<table cellpadding=0 cellspacing=0 class="tbl1"><tr><th>Meta</th>'.($Aplic->profissional ? '<th>Ciclo Anterior</th><th>Bom</th><th>Regular</th><th>Ruim</th>' : '').'<th>Início</th><th>Limite</th><th>Referencial</th></tr>';
		foreach($metas as $linha) {
			echo '<tr>';
			echo '<td align=right>'.number_format($linha['pratica_indicador_meta_valor_meta'], 2, ',', '.').'</td>';
			if ($Aplic->profissional){
				echo '<td align=center>'.($linha['pratica_indicador_meta_proporcao'] ? 'X' : '&nbsp;').'</td>';
				echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_boa'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_boa'], 2, ',', '.') : '&nbsp;').'</td>';
				echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_regular'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_regular'], 2, ',', '.') : '&nbsp;').'</td>';
				echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_ruim'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_ruim'], 2, ',', '.') : '&nbsp;').'</td>';
				}
			echo '<td>'.$linha['data'].'</td><td>'.$linha['data_meta'].'</td>';
			echo '<td>'.($linha['pratica_indicador_meta_valor_referencial'] != null ? number_format($linha['pratica_indicador_meta_valor_referencial'], 2, ',', '.') : '&nbsp;').'</td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</td></tr>';
	}



if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador_avaliacao');
	$sql->adCampo('pratica_indicador_avaliacao.*');
	$sql->adOnde('pratica_indicador_avaliacao_indicador = '.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_avaliacao_ordem');
	$causas=$sql->Lista();
	$sql->limpar();
	if (count($causas)) {
		echo '<tr><td colspan=20 align=left><b>Avaliação dos Resultados</b></td></tr>';
		echo '<tr><td colspan=20 align=left>';
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left width="100%">';
		echo '<tr><td style="font-weight:bold" align=center>Sucesso</td><td style="font-weight:bold" align=center>Insucesso</td><td style="font-weight:bold" align=center>Causa</td><td style="font-weight:bold" align=center>Medidas para Sanar</td></tr>';
		foreach ($causas as $causa) {
			echo '<tr align="center">';
			echo '<td align=center width=40>'.($causa['pratica_indicador_avaliacao_sucesso'] ? '<b>X</b>' : '&nbsp;').'</td>';
			echo '<td align=center width=40>'.(!$causa['pratica_indicador_avaliacao_sucesso'] ? '<b>X</b>' : '&nbsp;').'</td>';
			echo '<td align=left>'.($causa['pratica_indicador_avaliacao_causa'] ? $causa['pratica_indicador_avaliacao_causa'] : '&nbsp;').'</td>';
			echo '<td align=left>'.($causa['pratica_indicador_avaliacao_sanar'] ? $causa['pratica_indicador_avaliacao_sanar'] : '&nbsp;').'</td>';
			echo '</tr>';
			}
		echo '</table></td></tr>';
		}
	}

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/indicador_ver_pro.php';

echo '</table>';


if (!$dialogo) echo estiloFundoCaixa();
else {
	
	$faixas=getParam($_REQUEST, 'faixas', 0);
	$pratica_indicador_mostrar_valor=getParam($_REQUEST, 'pratica_indicador_mostrar_valor', (isset($_REQUEST['pratica_indicador_nr_pontos']) ? 0 : $pratica_indicador['pratica_indicador_mostrar_valor']));
	$pratica_indicador_mostrar_pontuacao=getParam($_REQUEST, 'pratica_indicador_mostrar_pontuacao', 0);
	$pratica_indicador_mostrar_titulo=getParam($_REQUEST, 'pratica_indicador_mostrar_titulo', (isset($_REQUEST['pratica_indicador_nr_pontos']) ? 0 : $pratica_indicador['pratica_indicador_mostrar_titulo']));
	$pratica_indicador_max_min=getParam($_REQUEST, 'pratica_indicador_max_min', (isset($_REQUEST['pratica_indicador_nr_pontos']) ? 0 : $pratica_indicador['pratica_indicador_max_min']));
	$pratica_indicador_tipografico=getParam($_REQUEST, 'pratica_indicador_tipografico', $pratica_indicador['pratica_indicador_tipografico']); 
	$pratica_indicador_agrupar=(isset($_REQUEST['pratica_indicador_agrupar']) ? getParam($_REQUEST, 'pratica_indicador_agrupar', null) : $pratica_indicador['pratica_indicador_agrupar']);
	$pratica_indicador_nr_pontos=(isset($_REQUEST['pratica_indicador_nr_pontos']) ? getParam($_REQUEST, 'pratica_indicador_nr_pontos', null) : $pratica_indicador['pratica_indicador_nr_pontos']);
		
	$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.$ano.'&faixas='.$faixas.'&mostrar_valor='.$pratica_indicador_mostrar_valor.'&mostrar_pontuacao='.$pratica_indicador_mostrar_pontuacao.'&data_final='.$data->format("%Y-%m-%d").'&nr_pontos='.$pratica_indicador_nr_pontos.'&mostrar_titulo='.$pratica_indicador_mostrar_titulo.'&max_min='.$pratica_indicador_max_min.'&agrupar='.$pratica_indicador_agrupar.'&tipografico='.$pratica_indicador_tipografico.'&pratica_indicador_id='.$pratica_indicador_id."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
	echo "<table cellspacing='0' cellpadding='0' align='center' class='tbl3'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table>";
	if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';
	}
if (!$dialogo){
	$caixaTab = new CTabBox('m=praticas&a=indicador_ver&pratica_indicador_id='.(int)$pratica_indicador_id, '', $tab);
	$texto_consulta = '?m=praticas&a=indicador_ver&pratica_indicador_id='.(int)$pratica_indicador_id;


	$qnt_aba=0;

	if (!$pratica_indicador['pratica_indicador_composicao'] && !$pratica_indicador['pratica_indicador_formula'] && !$pratica_indicador['pratica_indicador_formula_simples'] && !$pratica_indicador['pratica_indicador_checklist'] && !$pratica_indicador['pratica_indicador_campo_projeto'] && !$pratica_indicador['pratica_indicador_campo_tarefa'] && !$pratica_indicador['pratica_indicador_campo_acao']) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_lista_valor', 'Valores',null,null,'Valores','Visualizar a lista de inserções de valores neste indicador.');
	elseif ($pratica_indicador['pratica_indicador_checklist']) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/checklist_lista_valor', 'Checklist',null,null,'Valores de Checklist','Visualizar a lista de resultados de checklist deste indicador.');
	elseif ($pratica_indicador['pratica_indicador_formula_simples']) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_lista_valor_pro', 'Valores',null,null,'Valores','Visualizar a lista de inserções de valores neste indicador.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_grafico', 'Gráfico',null,null,'Gráfico','Visualizar o gráfico deste indicador.');
	$qnt_aba++;

	if ($pratica_modelo_id) {
		$sql->adTabela('pratica_indicador_nos_marcadores');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_nos_marcadores.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
		$sql->adCampo('count(pratica_marcador_letra)');
		$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_ver_marcadores', ucfirst($config['marcadores']),null,null,ucfirst($config['marcadores']),'Visualizar '.$config['genero_marcador'].'s '.$config['marcadores'].' atendid'.$config['genero_marcador'].'s pel'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' que utilizam este indicador.');
			}
		}



	if ($Aplic->checarModulo('log', 'acesso')) {
		$sql->adTabela('log');
		$sql->adCampo('count(log_id)');
		$sql->adOnde('log_indicador = '.(int)$pratica_indicador_id);
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver', 'Registro',null,null,'Registro das Ocorrência','Visualizar o registro de ocorrência relacionado.');
			}
		}

	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {

			$sql->adTabela('evento_gestao','evento_gestao');
			$sql->adOnde('evento_gestao_indicador = '.(int)$pratica_indicador_id);
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
			$sql->adOnde('arquivo_gestao_indicador = '.(int)$pratica_indicador_id);
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
			$sql->adOnde('pratica_indicador_gestao_semelhante = '.(int)$pratica_indicador_id);
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
			$sql->adOnde('plano_acao_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('plano_acao_item_gestao_indicador = '.(int)$pratica_indicador_id);
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
			$sql->adOnde('projeto_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('projeto_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('ata_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('demanda_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('msg_gestao_indicador = '.(int)$pratica_indicador_id);
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
			$sql->adOnde('modelo_gestao_indicador = '.(int)$pratica_indicador_id);
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
			$sql->adOnde('link_gestao_indicador = '.(int)$pratica_indicador_id);
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
			$sql->adOnde('forum_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('problema_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('risco_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('risco_resposta_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('instrumento_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('recurso_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('patrocinador_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('programa_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('beneficio_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('licao_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('pratica_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('tr_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('brainstorm_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('gut_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('causa_efeito_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('tgn_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('canvas_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('mswot_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('swot_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('operativo_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('monitoramento_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('avaliacao_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('checklist_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('agenda_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('agrupamento_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('template_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('painel_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('painel_odometro_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('painel_composicao_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('painel_slideshow_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('calendario_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('perspectiva_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('tema_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('objetivo_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('me_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('fator_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('estrategia_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('meta_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('plano_gestao_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('projeto_abertura_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('projeto_viabilidade_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('ssti_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('laudo_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('trelo_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('trelo_cartao_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('pdcl_gestao_indicador = '.(int)$pratica_indicador_id);
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
		$sql->adOnde('pdcl_item_gestao_indicador = '.(int)$pratica_indicador_id);
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

function duplicar_indicador(pratica_indicador_id){
	var nome=prompt("Entre o nome do clone","<?php echo $pratica_indicador['pratica_indicador_nome']?>");
	if (nome){
		document.env.pratica_indicador_nome.value=nome;
		document.env.a.value='indicador_duplicar';
		document.env.submit();
		}
	else alert('O indicador precisa ter um nome!');
	}

function imprimir(pratica_indicador_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 900, 500, 'm=praticas&a=imprimir_indicador&dialogo=1&tipo=1&pratica_indicador_id='+pratica_indicador_id, null, window);
	else window.open('index.php?m=praticas&a=imprimir_indicador&dialogo=1&tipo=1&pratica_indicador_id='+pratica_indicador_id, '','width=900, height=900, menubar=1, scrollbars=1');

	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este indicador?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='indicador_fazer_sql';
		f.submit();
		}
	}



function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>