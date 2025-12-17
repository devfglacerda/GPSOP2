<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa GP-Web
O GP-Web é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $tab;

//ajuste de permissão para sub-módulo projetos
global $podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar;
list($podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar)  = listaPermissoes('projetos', 'projetos_lista');

$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

if (isset($_REQUEST['baseline_id'])) $Aplic->setEstado('baseline_id', getParam($_REQUEST, 'baseline_id', null));
$baseline_id = ($Aplic->getEstado('baseline_id') !== null ? $Aplic->getEstado('baseline_id') : null);

if (isset($_REQUEST['financeiro'])) $Aplic->setEstado('financeiro', getParam($_REQUEST, 'financeiro', null));
$financeiro = ($Aplic->getEstado('financeiro') !== null ? $Aplic->getEstado('financeiro') : null);

$imprimir_detalhe=getParam($_REQUEST, 'imprimir_detalhe', 0);

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projeto\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();



	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_projeto='.(int)$projeto_id);
	$assinar = $sql->linha();
	$sql->limpar();

	//tem assinatura que aprova
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_projeto='.(int)$projeto_id);
	$tem_aprovacao = $sql->resultado();
	$sql->limpar();
	

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

//verificar se baseline é deste projeto
if ($baseline_id){
	$sql->adTabela('baseline');
	$sql->adCampo('baseline_projeto_id');
	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$baseline_projeto=$sql->resultado();
	$sql->limpar();
	if ($baseline_projeto!=$projeto_id){
		$Aplic->setEstado('baseline_id', null);
		$baseline_id = null;
		}
	$sql->adTabela('baseline');
	$sql->adCampo('baseline_data');
	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$hoje=$sql->resultado();
	$sql->limpar();
	}
else $hoje=date('Y-m-d H:i:s');

$sql->adTabela('baseline');
$sql->adCampo('baseline_id, concatenar_tres(formatar_data(baseline_data, "%d/%m/%Y %H:%i"), \' - \', baseline_nome) AS nome ');
$sql->adOnde('baseline_projeto_id = '.(int)$projeto_id);
$baselines=array(0=>'')+$sql->listaVetorChave('baseline_id','nome');
$sql->limpar();



$duplicar=getParam($_REQUEST, 'duplicar', 0);
if ($duplicar && $projeto_id){
	require_once BASE_DIR.'/modulos/tarefas/funcoes_pro.php';
	duplicar_tarefa($duplicar, getParam($_REQUEST, 'nome_tarefa', $config['tarefa'].'_'.$duplicar));
	atualizar_percentagem($projeto_id);
	}
$clonar_tarefas=getParam($_REQUEST, 'clonar_tarefas', 0);
if ($clonar_tarefas){
	require_once BASE_DIR.'/modulos/tarefas/funcoes_pro.php';
	clonar_tarefas($clonar_tarefas, getParam($_REQUEST, 'selecionado_tarefa', null));
	}
$mover_tarefas=getParam($_REQUEST, 'mover_tarefas', 0);
if ($mover_tarefas){
	require_once BASE_DIR.'/modulos/tarefas/funcoes_pro.php';
	mover_tarefas($mover_tarefas, getParam($_REQUEST, 'selecionado_tarefa', null));
	atualizar_percentagem($projeto_id);
	atualizar_percentagem($mover_tarefas);
	}

$obj = new CProjeto(($baseline_id ? $baseline_id : false));


$obj->load($projeto_id, true, $baseline_id);


$obj->projeto_nome = htmlspecialchars($obj->projeto_nome, ENT_QUOTES, "ISO-8859-1");

$statusExternas = array('novas'=>0, 'total'=>0);
if($Aplic->profissional && !$baseline_id){
	require_once(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php');
	if(!((int)$obj->projeto_portfolio)){
		$statusExternas = CTarefaCache::processaLinks($projeto_id);
		}
	}

//projeto não existe mais
if(!$obj->projeto_id){
	$Aplic->redirecionar('m=publico&a=nao_existe&campo='.$config['projeto'].'&masculino='.$config['genero_projeto']);
	}

$paises = getPais('Paises');

if (!$projeto_id){
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar ver detalhes d'.$config['genero_projeto'].' '.$config['projeto'], UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index');
	exit();
	}

if (!($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1) && !($podeAcessar && permiteAcessar($obj->projeto_acesso,$obj->projeto_id))){
	$Aplic->redirecionar('m=publico&a=acesso_negado');
	exit();
	}

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ProjetoVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ProjetoVerTab') !== null ? $Aplic->getEstado('ProjetoVerTab') : 0;
$msg = '';

$projeto_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$editar=(permiteEditar($obj->projeto_acesso, $projeto_id) && $podeEditar);

$podeEditarTudo=($obj->projeto_acesso>=5 ? $editar && (in_array($obj->projeto_responsavel, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);


$tarefasCriticas = ($projeto_id > 0) ? $obj->getTarefasCriticas($projeto_id) : null;
$PrioridadeProjeto = getSisValor('PrioridadeProjeto');
$corPrioridadeProjeto = getSisValor('CorPrioridadeProjeto');

$filho_portfolio = 0;

	$sql->adTabela('projeto_portfolio');
	$sql->adCampo('projeto_portfolio_pai');
	$sql->adOnde('projeto_portfolio_filho = '.(int)$projeto_id);
	$filho_portfolio=$sql->resultado();
	$sql->limpar();


$sql->adTabela('municipios_coordenadas');
$sql->adCampo('count(municipio_id)');
$tem_coordenadas=$sql->resultado();
$sql->limpar();



$lista_projeto=0;
if ($Aplic->profissional){
	$vetor=array($projeto_id => $projeto_id);
	portfolio_projetos($projeto_id, $vetor);
	$lista_projeto=implode(',',$vetor);
	}



$sql->adTabela('tarefas');
$sql->adCampo('COUNT(distinct tarefas.tarefa_id) AS total_tarefas');
$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
$sql->adOnde('tarefa_projetoex_id IS NULL');
$temTarefas = $sql->Resultado();
$sql->limpar();


if (!$obj){
	$Aplic->setMsg('informações erradas sobre '.$config['genero_projeto'].' '.$config['projeto'].'.', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=projetos');
	}
else $Aplic->salvarPosicao();

if ($temTarefas){
	$sql->adTabela('log');
	$sql->esqUnir('tarefas', 'tarefas', 'log_tarefa = tarefa_id');
	$sql->adCampo('ROUND(SUM(log_horas),2)');
	$sql->adOnde('tarefa_projetoex_id IS NULL');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$horas_trabalhadas_registros = $sql->Resultado();
	$sql->limpar();
	$horas_trabalhadas_registros = rtrim($horas_trabalhadas_registros, '.');

	$sql->adTabela('tarefas');
	$sql->adCampo('SUM(tarefa_duracao)');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$sql->adOnde('tarefa_dinamica != 1');
	$sql->adOnde('tarefa_projetoex_id IS NULL');
	$totalHoras = $sql->Resultado();
	$sql->limpar();

	$sql->limpar();
	$sql->adTabela('tarefa_designados');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = tarefa_designados.tarefa_id');
	$sql->adCampo('ROUND(SUM(tarefa_duracao*perc_designado/100),2)');
	$sql->adOnde('tarefa_projetoex_id IS NULL');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id).' AND tarefa_dinamica != 1 AND tarefa_duracao!=0');
	$totalhoras_designados_tarefas = $sql->Resultado();
	$sql->limpar();
	}
else  $horas_trabalhadas_registros = $totalHoras = $totalhoras_designados_tarefas = 0.00;

if ($obj->projeto_portfolio){
	$totalHoras=portfolio_horas($projeto_id);
	}


echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="projetos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_projeto_aed" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '</form>';


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$obj->projeto_cia.'" />';
echo '<input type="hidden" name="existe_projeto" id="existe_projeto" value="0" />';

if (!$Aplic->profissional || ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1))) echo '<input type="hidden" name="baseline_id" id="baseline_id" value="'.$baseline_id.'" />';
else if (count($baselines)==1 && $Aplic->profissional) echo '<input type="hidden" name="baseline_id" id="baseline_id" value="" />';

$data_fim = intval($obj->projeto_data_fim) ? new CData($obj->projeto_data_fim) : null;

if ($obj->projeto_portfolio){
	$data_fim_atual=portfolio_tarefa_fim($projeto_id);
	$data_inicio_atual=portfolio_tarefa_inicio($projeto_id);

	$vetor=array();
	portfolio_tarefas($projeto_id, $vetor, $baseline_id);
	if(count($vetor)){
		$lista=implode(',',$vetor);
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_id IN ('.$lista.')');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('tarefa_inicio=\''.$data_inicio_atual.'\'');
		$id_tarefa_inicio_atual = $sql->resultado();
		$sql->limpar();

		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_projetoex_id IS NULL');
		$sql->adOnde('tarefa_id IN ('.$lista.')');
		if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('tarefa_fim=\''.$data_fim_atual.'\'');
		$id_tarefa_fim_atual = $sql->resultado();
		$sql->limpar();
		}
	}
else {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	$sql->adCampo('MIN(tarefa_inicio)');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$sql->adOnde('tarefa_projetoex_id IS NULL');
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adOnde("tarefa_inicio IS NOT NULL AND tarefa_inicio != '000-00-00 00:00:00'");
	$data_inicio_atual = $sql->resultado();
	$sql->limpar();

	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$sql->adOnde('tarefa_projetoex_id IS NULL');
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adOnde('tarefa_inicio=\''.$data_inicio_atual.'\'');
	$id_tarefa_inicio_atual = $sql->resultado();
	$sql->limpar();


	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	$sql->adCampo('MAX(tarefa_fim)');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$sql->adOnde('tarefa_projetoex_id IS NULL');
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adOnde("tarefa_fim IS NOT NULL AND tarefa_fim != '000-00-00 00:00:00'");
	$data_fim_atual = $sql->resultado();
	$sql->limpar();

	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$sql->adOnde('tarefa_projetoex_id IS NULL');
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adOnde('tarefa_fim=\''.$data_fim_atual.'\'');
	$id_tarefa_fim_atual = $sql->resultado();
	$sql->limpar();
	}


if (isset($_REQUEST['textobusca'])) $Aplic->setEstado('textobusca', getParam($_REQUEST, 'textobusca', null));
$pesquisar_texto = $Aplic->getEstado('textobusca') ? $Aplic->getEstado('textobusca') : '';

if ($Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso')) {
	$sql->adTabela('financeiro_config');
	$sql->adCampo('financeiro_config_campo, financeiro_config_valor');
	$configuracao = $sql->listaVetorChave('financeiro_config_campo','financeiro_config_valor');
	$sql->limpar();
	}		


if (!$dialogo){
  echo '<div id="container_detalhes_projeto">';
	$botoesTitulo = new CBlocoTitulo('Detalhes '.($obj->projeto_portfolio ? 'd'.$config['genero_portfolio'].' '.ucfirst($config['portfolio']) : 'd'.$config['genero_projeto'].' '.ucfirst($config['projeto'])), 'projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">';
	echo '<tr><td colspan=2 style="background-color: #212121" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	
	
	if ($config['anexo_mpog']) {
		//$km->Add("inserir","artefato",dica(ucfirst($config['artefato']),'Inserir '.$config['genero_artefato'].' '.$config['artefato'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['artefatos']).dicaF(), "javascript: void(0);' onclick='menu_anexos()");
		$km->Add("ver","artefato",dica(ucfirst($config['artefato']),ucfirst($config['genero_artefato']).'s '.$config['artefatos'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['artefatos']).dicaF(), "javascript: void(0);");
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'demanda')){
			$sql->adTabela('demandas');
			$sql->adCampo('demanda_id, demanda_viabilidade, demanda_termo_abertura');
			$sql->adOnde('demanda_projeto='.(int)$projeto_id);
			$linha=$sql->linha();
			$sql->limpar();
			if ($linha['demanda_id']) $km->Add("artefato","artefato_demanda",dica('Documento de Oficialização da Demanda', 'Acessar o documento de oficialização da demanda d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Documento de Oficialização da Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_ver&demanda_id=".$linha['demanda_id']."\");");
			if ($linha['demanda_viabilidade']) $km->Add("artefato","artefato_viabilidade",dica('Análise de Viabilidade', 'Acessar a análise de viabilidade d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Análise de Viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_ver&projeto_viabilidade_id=".$linha['demanda_viabilidade']."\");");
			if ($linha['demanda_termo_abertura']) $km->Add("artefato","artefato_abertura",dica('Termo de Abertura', 'Acessar o termo de abertura d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_ver&projeto_abertura_id=".$linha['demanda_termo_abertura']."\");");
			if  ($podeEditar && $editar && $projeto_id && !$linha['demanda_id']) $km->Add("artefato","artefato_gerar",dica('Gerar os Documentos Anteriores', 'Gerar os documentos anteriores (demanda, estudo de viabilidade e termo de abertura) d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Gerar os Documentos Anteriores'.dicaF(), "javascript: void(0);' onclick='gerar_demanda(".$projeto_id.")");
			}
		$km->Add("artefato","artefato_qualidade",dica('Plano de Qualidade', 'Acessar o plano de qualidade d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Plano de Qualidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=qualidade_ver&projeto_id=".$projeto_id."\");");
		$km->Add("artefato","artefato_comunicacao",dica('Plano de Comunicação', 'Acessar o plano de comunicação d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Plano de Comunicação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=comunicacao_ver&projeto_id=".$projeto_id."\");");
		$km->Add("artefato","artefato_risco",dica('Plano de Gerenciamento de Riscos', 'Acessar o plano de gerenciamento de riscos d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Plano de Gerenciamento de Riscos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=risco_ver&projeto_id=".$projeto_id."\");");
		$km->Add("artefato","artefato_mudanca",dica('Formulário de Solicitação de Mudanças', 'Acessar o formulário de solicitação de mudanças d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Formulário de Solicitação de Mudanças'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=mudanca_lista&projeto_id=".$projeto_id."\");");
		$km->Add("artefato","artefato_recebimento",dica('Termo de Recebimento de Produto/Serviço', 'Acessar o Termo de Recebimento de Produto/Serviço d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Termo de Recebimento de Produto/Serviço'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=recebimento_lista&projeto_id=".$projeto_id."\");");
		$km->Add("artefato","artefato_ata",dica('Ata de Reunião', 'Acessar as atas de reunião d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Ata de Reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ata_lista&projeto_id=".$projeto_id."\");");
		$km->Add("artefato","artefato_encerramento",dica('Termo de Encerramento', 'Acessar o termo de encerramento d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=encerramento_ver&projeto_id=".$projeto_id."\");");
		$km->Add("artefato","artefato_licao",dica('Lições Aprendidas', 'Acessar as lições aprendidas d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Lições Aprendidas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_projeto&projeto_id=".$projeto_id."\");");
		}

	
	if ($config['anexo_eb']){

			$sql->adTabela('demanda_config');
			$sql->adCampo('demanda_config.*');
			$linha = $sql->linha();
			$sql->limpar();

			$km->Add("ver","negapeb",dica(ucfirst($config['anexo_eb_nome']),'Visualizar '.$config['genero_anexo_eb_nome'].' '.$config['anexo_eb_nome'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['anexo_eb_nome']).dicaF(), "javascript: void(0);");
			if ($linha['demanda_config_ativo_diretriz_iniciacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_diretriz_iniciacao']),ucfirst($linha['demanda_config_diretriz_iniciacao']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_diretriz_iniciacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=iniciacao_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_estudo_viabilidade']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_estudo_viabilidade']),ucfirst($linha['demanda_config_estudo_viabilidade']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_estudo_viabilidade']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=viabilidade_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_diretriz_implantacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_diretriz_implantacao']),ucfirst($linha['demanda_config_diretriz_implantacao']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_diretriz_implantacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=implantacao_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_declaracao_escopo']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_declaracao_escopo']),ucfirst($linha['demanda_config_declaracao_escopo']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_declaracao_escopo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=escopo_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_estrutura_analitica']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_estrutura_analitica']),ucfirst($linha['demanda_config_estrutura_analitica']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_estrutura_analitica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=estrutura_analitica_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_dicionario_eap']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_dicionario_eap']),ucfirst($linha['demanda_config_dicionario_eap']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_dicionario_eap']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=dicionario_eap_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_cronograma_fisico']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_cronograma_fisico']),ucfirst($linha['demanda_config_cronograma_fisico']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_cronograma_fisico']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=cronograma_financeiro_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_plano_projeto']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_plano_projeto']),ucfirst($linha['demanda_config_plano_projeto']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_plano_projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=plano_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_cronograma']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_cronograma']),ucfirst($linha['demanda_config_cronograma']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_cronograma']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=cronograma_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_planejamento_custo']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_planejamento_custo']),ucfirst($linha['demanda_config_planejamento_custo']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_planejamento_custo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=custo_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_humanos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_humanos']),ucfirst($linha['demanda_config_gerenciamento_humanos']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_humanos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=humano_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_comunicacoes']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_comunicacoes']),ucfirst($linha['demanda_config_gerenciamento_comunicacoes']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_comunicacoes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=comunicacao_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_partes']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_partes']),ucfirst($linha['demanda_config_gerenciamento_partes']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_partes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=interessado_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_riscos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_riscos']),ucfirst($linha['demanda_config_gerenciamento_riscos']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_riscos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=risco_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_qualidade']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_qualidade']),ucfirst($linha['demanda_config_gerenciamento_qualidade']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_qualidade']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=qualidade_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_gerenciamento_mudanca']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_mudanca']),ucfirst($linha['demanda_config_gerenciamento_mudanca']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_mudanca']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=mudanca_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_controle_mudanca']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_controle_mudanca']),ucfirst($linha['demanda_config_controle_mudanca']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_controle_mudanca']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=mudanca_controle_lista&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_aceite_produtos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_aceite_produtos']),ucfirst($linha['demanda_config_aceite_produtos']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_aceite_produtos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=aceite_lista&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_relatorio_situacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_relatorio_situacao']),ucfirst($linha['demanda_config_relatorio_situacao']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_relatorio_situacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=situacao_ver&projeto_id=".$projeto_id."\");");
			if ($linha['demanda_config_ativo_termo_encerramento']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_termo_encerramento']),ucfirst($linha['demanda_config_termo_encerramento']).' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_termo_encerramento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=encerramento_ver&projeto_id=".$projeto_id."\");");
			//$km->Add("negapeb","eb_fluxograma",dica('Fluxograma do Ciclo de Vida','Visualizar o fluxograma do ciclo de vida d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Fluxograma do Ciclo de Vida'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=fluxograma_ver&projeto_id=".$projeto_id."\");");
			$km->Add("negapeb","eb_status",dica('Status dos Documentos','Visualizar o status dos documento d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Status dos documentos'.dicaF(), "javascript: void(0);' onclick='status_pro()");
			}	
	
	
	
	
	
	if (($podeEditar && $editar) || $podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar)	{

		$km->Add("inserir","inserir_objeto",dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Criar uma nov'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar\");");


		//$km->Add("inserir_objeto","inserir_clone",dica('Clone d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).' Atual', 'Criar clone d'.$config['genero_projeto'].' '.$config['projeto'].' atual.').'Clone d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).' atual'.dicaF(), "javascript: void(0);' onclick='clonar();");


		}
	if ($podeEditar && $podeEditarTudo && $Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'adicionar') && !$obj->projeto_portfolio){
		$km->Add("inserir","inserir_tarefa",dica('Nov'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Criar uma nov'.$config['genero_tarefa'].' '.$config['tarefa'].' n'.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].'.').ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=editar&tarefa_projeto=".$projeto_id."\");");
		if ($Aplic->profissional) $km->Add("inserir","inserir_modelo",dica(ucfirst($config['tarefa']).'de Modelo', 'Importar '.$config['tarefas'].' de modelo cadastrado.').ucfirst($config['tarefa']).' de Modelo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_importar&projeto_id=".$projeto_id."\");");
		}
	
	
	if ($podeEditar && $editar) {
		if ($Aplic->checarModulo('log', 'adicionar'))
	//	$km->Add("inserir","inserir_expediente",dica('Expediente', 'Expediente relacionado a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.').'Expediente'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=jornada_editar&projeto_id=".$projeto_id."\");");

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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_projeto=".$projeto_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}


	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_projeto=".$projeto_id."\");");
//	if ($Aplic->checarModulo('projetos', 'adicionar', null, 'projeto_custo') || $Aplic->checarModulo('projetos', 'editar', null, 'projeto_custo')) $km->Add("inserir","inserir_custo",dica('Custos d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])), 'Inserir a planilha de custos d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Estes valores precisarão depois serem transferidos para '.$config['tarefas'].'.').'Custos d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=projeto_estimado_pro&projeto_id=".$projeto_id."\");");








 
		if (!$obj->projeto_portfolio) 
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar&projeto_id=".$projeto_id."\");");
		$km->Add("inserir","inserir_baseline",dica('Baseline','Gerencie baselines do '.$config['projeto'].'.<br>Baseline é um instantâneo que é tirado d'.$config['genero_projeto'].' '.$config['projeto'].' para posterior comparação com as modificações realizadas, ao longo do tempo.').'Baseline'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=".($Aplic->profissional ? 'baseline' : 'baseline')."&projeto_id=".$projeto_id."\");");
		//if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_projeto=".$projeto_id."\");");
		
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) $km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_projeto=".$projeto_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_projeto=".$projeto_id."\");");
	//	if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_projeto=".$projeto_id."\");");	
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_projeto=".$projeto_id."\");");
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'demanda')) $km->Add("inserir","inserir_demanda",dica('Nova Demanda', 'Inserir uma nova demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar&demanda_projeto=".$projeto_id."\");");
	
			}
		$km->Add("inserir","diverso",dica('Diversos','Menu de objetos diversos').'Diversos'.dicaF(), "javascript: void(0);'");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("diverso","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_projeto=".$projeto_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("diverso","inserir_forum",dica('Novo Fórum', 'Inserir um novo fórum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("diverso","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_projeto=".$projeto_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("diverso","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_projeto=".$projeto_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'resposta_risco')) $km->Add("diverso","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_projeto=".$projeto_id."\");");
		if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'adicionar', null, null)) $km->Add("diverso","inserir_instrumento",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Inserir um'.($config['genero_instrumento']=='a' ? 'a' : '').' nov'.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar&instrumento_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('recursos', 'adicionar', null, null)) $km->Add("diverso","inserir_recurso",dica('Nov'.$config['genero_recurso'].' '.ucfirst($config['recurso']), 'Inserir um'.($config['genero_recurso']=='a' ? 'a' : '').' nov'.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.').ucfirst($config['recurso']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar&recurso_projeto=".$projeto_id."\");");
		if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'adicionar', null, null)) $km->Add("diverso","inserir_patrocinador",dica('Nov'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Inserir '.($config['genero_patrocinador']=='o' ? 'um' : 'uma').' nov'.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.').ucfirst($config['patrocinador']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=patrocinadores&a=patrocinador_editar&patrocinador_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'programa')) $km->Add("diverso","inserir_programa",dica('Nov'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Inserir um'.($config['genero_programa']=='a' ? 'a' : '').' nov'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.').ucfirst($config['programa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=programa_pro_editar&programa_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'licao')) $km->Add("diverso","inserir_licao",dica('Nov'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Inserir um'.($config['genero_licao']=='a' ? 'a' : '').' nov'.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.').ucfirst($config['licao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar&licao_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'pratica')) $km->Add("diverso","inserir_pratica",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Inserir um'.($config['genero_pratica']=='a' ? 'a' : '').' nov'.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.').ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'adicionar', null, null)) $km->Add("diverso","inserir_tr",dica('Nov'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Inserir um'.($config['genero_tr']=='a' ? 'a' : '').' nov'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_editar&tr_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'brainstorm')) $km->Add("diverso","inserir_brainstorm",dica('Novo Brainstorm', 'Inserir um novo brainstorm relacionado.').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm_editar&brainstorm_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'gut')) $km->Add("diverso","inserir_gut",dica('Nova Matriz GUT', 'Inserir uma nova matriz GUT relacionado.').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut_editar&gut_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'causa_efeito')) $km->Add("diverso","inserir_causa_efeito",dica('Novo Diagrama de Causa-Efeito', 'Inserir um novo Diagrama de causa-efeito relacionado.').'Diagrama de causa-efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito_editar&causa_efeito_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'tgn')) $km->Add("diverso","inserir_tgn",dica('Nov'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Inserir um'.($config['genero_tgn']=='a' ? 'a' : '').' nov'.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.').ucfirst($config['tgn']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tgn_pro_editar&tgn_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'canvas')) $km->Add("diverso","inserir_canvas",dica('Nov'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Inserir um'.($config['genero_canvas']=='a' ? 'a' : '').' nov'.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.').ucfirst($config['canvas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=canvas_pro_editar&canvas_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_mswot",dica('Nova Matriz SWOT', 'Inserir uma nova matriz SWOT relacionada.').'Matriz SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=mswot_editar&mswot_projeto=".$projeto_id."\");");
			$km->Add("diverso","inserir_swot",dica('Novo Campo SWOT', 'Inserir um novo campo SWOT relacionado.').'Campo SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=swot_editar&swot_projeto=".$projeto_id."\");");
			}
		if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'adicionar', null, null)) $km->Add("diverso","inserir_operativo",dica('Novo Plano Operativo', 'Inserir um novo plano operativo relacionado.').'Plano operativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=operativo&a=operativo_editar&operativo_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'monitoramento')) $km->Add("diverso","inserir_monitoramento",dica('Novo monitoramento', 'Inserir um novo monitoramento relacionado.').'Monitoramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=monitoramento_editar_pro&monitoramento_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'avaliacao_indicador')) $km->Add("diverso","inserir_avaliacao",dica('Nova Avaliação', 'Inserir uma nova avaliação relacionada.').'Avaliação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar&avaliacao_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'checklist')) $km->Add("diverso","inserir_checklist",dica('Novo Checklist', 'Inserir um novo checklist relacionado.').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar&checklist_projeto=".$projeto_id."\");");
		$km->Add("diverso","inserir_agenda",dica('Novo Compromisso', 'Inserir um novo compromisso relacionado.').'Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso&agenda_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'adicionar', null, null)) $km->Add("diverso","inserir_agrupamento",dica('Novo Agrupamento', 'Inserir um novo Agrupamento relacionado.').'Agrupamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=agrupamento&a=agrupamento_editar&agrupamento_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'modelo')) $km->Add("diverso","inserir_template",dica('Novo Modelo', 'Inserir um novo modelo relacionado.').'Modelo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_editar&template_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'painel_indicador')) $km->Add("diverso","inserir_painel",dica('Novo Painel de Indicador', 'Inserir um novo painel de indicador relacionado.').'Painel de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_editar&painel_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'odometro_indicador')) $km->Add("diverso","inserir_painel_odometro",dica('Novo Odômetro de Indicador', 'Inserir um novo odômetro de indicador relacionado.').'Odômetro de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_editar&painel_odometro_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'composicao_painel')) $km->Add("diverso","inserir_painel_composicao",dica('Nova Composição de Painéis', 'Inserir uma nova composição de painéis relacionada.').'Composição de painéis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_editar&painel_composicao_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'slideshow_painel')) $km->Add("diverso","inserir_painel_slideshow",dica('Novo Slideshow de Composições', 'Inserir um novo slideshow de composições relacionado.').'Slideshow de composições'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_slideshow_pro_editar&painel_slideshow_projeto=".$projeto_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'ssti')) $km->Add("diverso","inserir_ssti",dica('Nov'.$config['genero_ssti'].' '.ucfirst($config['ssti']), 'Inserir um'.($config['genero_ssti']=='a' ? 'a' : '').' nov'.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.').ucfirst($config['ssti']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=ssti_editar&ssti_projeto=".$projeto_id."\");");
			if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'laudo')) $km->Add("diverso","inserir_laudo",dica('Nov'.$config['genero_laudo'].' '.ucfirst($config['laudo']), 'Inserir um'.($config['genero_laudo']=='a' ? 'a' : '').' nov'.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.').ucfirst($config['laudo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=laudo_editar&laudo_projeto=".$projeto_id."\");");
			if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'adicionar', null, null)) {
				$km->Add("diverso","inserir_trelo",dica('Nov'.$config['genero_trelo'].' '.ucfirst($config['trelo']), 'Inserir um'.($config['genero_trelo']=='a' ? 'a' : '').' nov'.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.').ucfirst($config['trelo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_editar&trelo_projeto=".$projeto_id."\");");
				$km->Add("diverso","inserir_trelo_cartao",dica('Nov'.$config['genero_trelo_cartao'].' '.ucfirst($config['trelo_cartao']), 'Inserir um'.($config['genero_trelo_cartao']=='a' ? 'a' : '').' nov'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.').ucfirst($config['trelo_cartao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_cartao_editar&trelo_cartao_projeto=".$projeto_id."\");");
				}
			if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'adicionar', null, null)) {
				$km->Add("diverso","inserir_pdcl",dica('Nov'.$config['genero_pdcl'].' '.ucfirst($config['pdcl']), 'Inserir um'.($config['genero_pdcl']=='a' ? 'a' : '').' nov'.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.').ucfirst($config['pdcl']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_editar&pdcl_projeto=".$projeto_id."\");");
				$km->Add("diverso","inserir_pdcl_item",dica('Nov'.$config['genero_pdcl_item'].' '.ucfirst($config['pdcl_item']), 'Inserir um'.($config['genero_pdcl_item']=='a' ? 'a' : '').' nov'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.').ucfirst($config['pdcl_item']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_item_editar&pdcl_item_projeto=".$projeto_id."\");");
				}
		$km->Add("inserir","gestao1",dica('Gestao','Menu de objetos de gestão').'Gestao'.dicaF(), "javascript: void(0);'");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'perspectiva')) $km->Add("gestao1","inserir_perspectiva",dica('Nov'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Inserir um'.($config['genero_perspectiva']=='a' ? 'a' : '').' nov'.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_editar&perspectiva_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'tema')) $km->Add("gestao1","inserir_tema",dica('Nov'.$config['genero_tema'].' '.ucfirst($config['tema']), 'Inserir um'.($config['genero_tema']=='a' ? 'a' : '').' nov'.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.').ucfirst($config['tema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_editar&tema_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'objetivo')) $km->Add("gestao1","inserir_objetivo",dica('Nov'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']), 'Inserir um'.($config['genero_objetivo']=='a' ? 'a' : '').' nov'.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.').ucfirst($config['objetivo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_editar&objetivo_projeto=".$projeto_id."\");");
		if ($Aplic->profissional && isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('gestao1', 'adicionar', null, 'me')) $km->Add("gestao1","inserir_me",dica('Nov'.$config['genero_me'].' '.ucfirst($config['me']), 'Inserir um'.($config['genero_me']=='a' ? 'a' : '').' nov'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=me_editar_pro&me_projeto=".$projeto_id."\");");
		if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator')) $km->Add("gestao1","inserir_fator",dica('Nov'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Inserir um'.($config['genero_fator']=='a' ? 'a' : '').' nov'.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.').ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar&fator_projeto=".$projeto_id."\");"); 
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'iniciativa')) $km->Add("gestao1","inserir_iniciativa",dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Inserir um'.($config['genero_iniciativa']=='a' ? 'a' : '').' nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.').ucfirst($config['iniciativa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_editar&estrategia_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'meta')) $km->Add("gestao1","inserir_meta",dica('Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Inserir um'.($config['genero_meta']=='a' ? 'a' : '').' nov'.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.').ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar&meta_projeto=".$projeto_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'planejamento')) $km->Add("gestao1","inserir_plano_gestao",dica('Novo Planejamento estratégico', 'Inserir um novo planejamento estratégico relacionado.').'Planejamento estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar&plano_gestao_projeto=".$projeto_id."\");");

			
		
		if ($Aplic->checarModulo('tarefas', 'adicionar') && !$obj->projeto_portfolio){
			if ($Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'projetos_wbs')) $km->Add("ver","ver_eap",dica('Estrutura Analítica do Projeto - Work Breakdown Structure','Visualizar a estrutura analíticas d'.$config['genero_projeto'].' '.$config['projeto'].'.<br>É uma ferramenta de decomposição do trabalho d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' em partes manejáveis. É estruturada em árvore exaustiva, hierárquica (de mais geral para mais específica) orientada às entregas que precisam ser feitas para completar '.($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto'].'.').'EAP (WBS)'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_vertical&jquery=1&projeto_id=".$projeto_id."\");");
			if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'projetos_wbsgrafico')) $km->Add("ver","ver_eap_grafica",dica('Estrutura Analítica do Projeto Gráfica','Visualizar a estrutura analíticas d'.$config['genero_projeto'].' '.$config['projeto'].' em formato gráfico.<br>É uma ferramenta de decomposição do trabalho d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' em partes manejáveis. É estruturada em árvore exaustiva, hierárquica (de mais geral para mais específica) orientada às entregas que precisam ser feitas para completar '.($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto'].'.').'EAP (WBS) gráfica'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_grafico_pro&projeto_id=".$projeto_id."\");");
			if ($Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'projetos_rapido')) $km->Add("ver","ver_rapido",dica('Gantt Interativo','Exibir interface de criação e edição de '.$config['projetos'],' utilizando gráfico Gantt interativo.').'Gantt Interativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_completo&projeto_id=".$projeto_id."\");");
			}
		if ($Aplic->checarModulo('relatorios', 'acesso')) $km->Add("ver","ver_relatorios",dica('Relatórios','Visualizar a lista de relatórios.<br><br>Os relatórios são modos convenientes de se ter uma visão panorâmica de como as divers'.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' estão se desenvolvendo.').'Relatórios'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=relatorios&a=index&projeto_id=".$projeto_id."\");");
	//	if ($Aplic->profissional) $km->Add("ver","ver_grafico",dica('Gráficos','Visualizar a ferramenta de gráficos customizados.').'Gráficos'.dicaF(), "javascript: void(0);' onclick='parent.gpwebApp.graficosProjeto(".$projeto_id.",".(isset($baseline_id) ? $baseline_id: 0).",\"".$obj->projeto_nome."\");");
		}
	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");

	$bloquear=($obj->projeto_aprovado && $config['trava_aprovacao'] && $tem_aprovacao && !$Aplic->usuario_super_admin);
	if (isset($assinar['assinatura_id']) && $assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=assinatura&a=assinatura_assinar&projeto_id=".$projeto_id."\");");

	
	if ($podeEditarTudo && $podeEditar && !$bloquear) $km->Add("acao","acao_editar",dica('Editar '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])),'Editar os detalhes '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').'Editar '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_id=".$projeto_id."\");");
	if ($podeEditarTudo && $podeExcluir && !$bloquear) $km->Add("acao","acao_excluir",dica('Excluir '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])),'Excluir '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).' do sistema.<br><br>Todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' pertencentes a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).' também serão excluíd'.$config['genero_tarefa'].'s.').'Excluir '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])).dicaF(), "javascript: void(0);' onclick='excluir()");
	
	if ($podeEditarTudo && $podeEditar && !$bloquear) {	
		if($statusExternas['total']) $km->Add("acao","acao_atualizar_externas",dica('Atualizar '.' '.ucfirst($config['tarefas']).' Extern'.($config['genero_tarefa']=='o' ? 'os' : 'as'),($config['genero_tarefa']=='o' ? 'Todos os ' : 'Todas as ').$config['tarefas'].' extern'.($config['genero_tarefa']=='o' ? 'os' : 'as').' que estão desatualizad'.($config['genero_tarefa']=='o' ? 'os' : 'as').' serão listad'.($config['genero_tarefa']=='o' ? 'os' : 'as').' para que você possa escolher quais deseja atualizar.').'Atualizar '.ucfirst($config['tarefas']).' Extern'.($config['genero_tarefa']=='o' ? 'os' : 'as').dicaF(), "javascript: void(0);' onclick='atualizarLinks(".$projeto_id.")");
		if ($obj->projeto_portfolio) {
			
			$sql->adTabela('projeto_custo');
			$sql->adCampo('count(projeto_custo_id)');
			$sql->adOnde('projeto_custo_projeto='.(int)$projeto_id);
			$projeto_custo = $sql->resultado();
			$sql->limpar();
			
			if ($projeto_custo) {
				$km->Add("acao","acao_aprovar_custo_projeto",dica('Aprovar Planilha de Custo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])),'Acesse interface onde será possível aprovar a planilha de custo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' previamente registradas.').'Aprovar Planilha de Custo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=projeto_aprovar_custos_pro&projeto_id=".$projeto_id."\");");
				$km->Add("acao","acao_transferir_custo_projeto",dica('Transferir Custo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])),'Acesse interface onde será possível transferir itens de custo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' para '.$config['tarefas'].'.').'Transferir Custo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=projeto_transferir_custos_pro&projeto_id=".$projeto_id."\");");
				}
			
			$km->Add("acao","acao_aprovar_custo_recurso",dica('Aprovar Alocação de Recurso n'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']),'Acesse interface onde será possível aprovar períodos alocados n'.$config['genero_tarefa'].'s '.$config['tarefas'].' pelos recursos.').'Aprovar Alocação de Recurso n'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_recurso_custo_pro&projeto_id=".$projeto_id."\");");
			//$km->Add("inserir","acao_gasto_mo",dica('Gasto com Mão de Obra','Acesse interface onde será possível inserir períodos trabalhados '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelos designados.').'Gasto com Mão de Obra'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=folha_ponto_pro&projeto_id=".$projeto_id."\");");
			$km->Add("acao","acao_aprovar_mo",dica('Aprovar Gasto com Mão de Obra n'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']),'Acesse interface onde será possível aprovar períodos trabalhados '.$config['genero_tarefa'].'s '.$config['tarefas'].' previamente registrados.').'Aprovar Gasto com Mão de Obra n'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_gasto_mo_pro&projeto_id=".$projeto_id."\");");
			//$km->Add("inserir","acao_gasto_recurso",dica('Gasto com Recurso','Acesse interface onde será possível inserir períodos trabalhados '.$config['genero_tarefa'].'s '.$config['tarefas'].' pelos recursos.').'Gasto com Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=recurso_ponto_pro&projeto_id=".$projeto_id."\");");
			$km->Add("acao","acao_aprovar_recurso",dica('Aprovar Gasto com Recurso n'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']),'Acesse interface onde será possível aprovar períodos trabalhados '.$config['genero_tarefa'].'s '.$config['tarefas'].' previamente registrados.').'Aprovar Gasto com Recurso n'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_recurso_pro&projeto_id=".$projeto_id."\");");
			$km->Add("acao","acao_aprovar_custo",dica('Aprovar Planilha de Custo d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']),'Acesse interface onde será possível aprovar a planilha de custo d'.$config['genero_tarefa'].'s '.$config['tarefas'].' previamente registradas.').'Aprovar Planilha de Custo d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_custos_pro&projeto_id=".$projeto_id."\");");
			$km->Add("acao","acao_aprovar_gasto",dica('Aprovar Planilha de Gasto  d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']),'Acesse interface onde será possível aprovar a planilha de gasto d'.$config['genero_tarefa'].'s '.$config['tarefas'].' previamente registradas.').'Aprovar Planilha de Gasto d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_gastos_pro&projeto_id=".$projeto_id."\");");
			if ($obj->projeto_aprova_registro) $km->Add("acao","acao_aprovar_registro",dica('Aprovar Registro de Ocorrência','Acesse interface onde será possível aprovar os registros de ocorrências d'.$config['genero_tarefa'].'s '.$config['tarefas'].' previamente cadastrados.').'Aprovar Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_registros_pro&projeto_id=".$projeto_id."\");");
			if ($Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso')) {
				$km->Add("acao","siafi",dica(ucfirst($config['siafi']),'Opções d'.$config['genero_siafi'].' '.$config['siafi'].' com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['siafi']).dicaF(), "javascript: void(0);");
				if ($configuracao['organizacao']!='sema_mt') $km->Add("siafi","siafi_nc",dica('Importar Nota de Crédito','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de crédito relacionadas com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Importar NC para '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_nc_pro&projeto_id=".$projeto_id."\");");
				$km->Add("siafi","siafi_ne",dica('Importar Nota de Empenho','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de empenho relacionadas com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Importar NE para '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ne_pro&projeto_id=".$projeto_id."\");");
				if ($configuracao['organizacao']!='sema_mt') $km->Add("siafi","siafi_rel_ne",dica('Relacionar Itens do Empenho com Planilha de Gasto','Relacionar itens do empenho com itens de planilha de gasto d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Relacionar itens do empenho com planilha de gasto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_rel_item_ne_pro&projeto_id=".$projeto_id."\");");
				if ($configuracao['organizacao']=='sema_mt') $km->Add("siafi","siafi_ns",dica('Importar Nota de Liquidação','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de liquidação relacionadas com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Importar NL para '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ns_pro&projeto_id=".$projeto_id."\");");
				else $km->Add("siafi","siafi_ns",dica('Importar Nota de Sistema','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as notas de sistema relacionadas com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Importar NS para '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ns_pro&projeto_id=".$projeto_id."\");");
				$km->Add("siafi","siafi_ob",dica('Importar Ordem Bancária','Importar d'.$config['genero_siafi'].' '.$config['siafi'].' as ordens bancárias relacionadas com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Importar OB para '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=financeiro&a=siafi_ob_pro&projeto_id=".$projeto_id."\");");
				}
			$km->Add("acao","financeiro",dica('Estágios da Despesa','Inserir empenhado, liquidado e pago nos gastos d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Estágios da Despesa'.dicaF(), "javascript: void(0);");
			$km->Add("financeiro","financeiro_planilha",dica('Planilha de Gasto','Acesse interface onde será possível colocar as planilhas de gasto d'.$config['genero_tarefa'].'s '.$config['tarefas'].' como empenhado, liquidado ou pago.').'Planilha de Gasto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_planilha_pro&projeto_id=".$projeto_id."\");");
			$km->Add("financeiro","financeiro_recurso",dica('Recursos','Acesse interface onde será possível colocar os gastos com recursos d'.$config['genero_tarefa'].'s '.$config['tarefas'].' como empenhado, liquidado ou pago.').' Gasto com Recursos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_recurso_pro&projeto_id=".$projeto_id."\");");
			$km->Add("financeiro","financeiro_mo",dica('Mão de Obra','Acesse interface onde será possível colocar os gastos com mão de obra d'.$config['genero_tarefa'].'s '.$config['tarefas'].' como empenhado, liquidado ou pago.').'Gasto com Mão de Obra'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_mo_pro&projeto_id=".$projeto_id."\");");
			}
		$legenda = $obj->projeto_portfolio ? $config['portfolio'] : $config['projeto'];
		$uclegenda = $obj->projeto_portfolio ? ucfirst($config['portfolio']) : ucfirst($config['projeto']);
		$genero = $obj->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto'];

	//	if ($Aplic->profissional) $km->add('acao','area',dica("Área d$genero $uclegenda","Editar ou importar área para $genero $legenda.").'Áreas'.dicaF(), "javascript: void(0);");
		//$km->add("area","editar_area",dica('Editar Área', "Visualizar e editar as áreas d$genero $legenda.").'Editar áreas'.dicaF(), "javascript: void(0);' onclick='javascript:popEditarPoligono()'");
	//	if ($Aplic->profissional) $km->add("area","importar_area",dica('Importar arquivo KML', "Importar áreas de um arquivo KML").'Importar arquivo KML'.dicaF(), "javascript: void(0);' onclick='javascript:popImportarKML()'");
		}
	else if($statusExternas['total']) $km->Add("acao","acao_atualizar_externas",dica('Visualizar '.' '.ucfirst($config['tarefas']).' Extern'.($config['genero_tarefa']=='o' ? 'os' : 'as').' Desatualizad'.($config['genero_tarefa']=='o' ? 'os' : 'as'),($config['genero_tarefa']=='o' ? 'Todos os' : 'Todas as').' '.$config['genero_tarefa'].'s '.$config['tarefas'].' extern'.($config['genero_tarefa']=='o' ? 'os' : 'as').' que estão desatualizad'.($config['genero_tarefa']=='o' ? 'os' : 'as').' serão listad'.($config['genero_tarefa']=='o' ? 'os' : 'as')).'Visualizar '.ucfirst($config['tarefas']).' Extern'.($config['genero_tarefa']=='o' ? 'os' : 'as').' Desatualizad'.($config['genero_tarefa']=='o' ? 'os' : 'as').dicaF(), "javascript: void(0);' onclick='atualizarLinks(".$projeto_id.",true)");
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['projetos']),'Visualizar a lista de '.($config['genero_projeto']=='o' ? 'todos os' : 'todas as').' '.$config['projetos'].'.').'Lista de '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=index\");");
	if ($filho_portfolio)  $km->Add("ver","ver_portfolio",dica(ucfirst($config['portfolio']),'Veja '.$config['genero_portfolio'].' '.$config['portfolio'].' '.' que '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).' faz parte.').ucfirst($config['portfolio']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$filho_portfolio."\");");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir decumentos d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='imprimir();");

	$km->Add("root","ver_lista",dica('Lista de '.ucfirst($config['projetos']),'Visualizar a lista de '.($config['genero_projeto']=='o' ? 'todos os' : 'todas as').' '.$config['projetos'].'.').'Lista de '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=index\");");
	if ($Aplic->profissional) {
		if ($podeEditarTudo && $podeEditar) {
			$km->Add("acao","exportar_link",dica('Exportar Link','Endereço web para visualização em ambiente externo dados d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Exportar Link'.dicaF(), "javascript: void(0);");
			$km->Add("exportar_link","exportar_gantt",dica('Gantt','Endereço web para visualização em ambiente externo do gráfico Gantt d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Gantt'.dicaF(), "javascript: void(0);' onclick='exportar_link(".($obj->projeto_portfolio ? '"portfolio_gantt"' : '"projeto_gantt"').");");
			$km->Add("exportar_link","exportar_dashboard",dica('Dashboard Geral','Endereço web para visualização em ambiente externo o dashboard geral d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Dashboard geral'.dicaF(), "javascript: void(0);' onclick='exportar_link(\"projeto_dashboard\");");
			$km->Add("exportar_link","exportar_detalhes",dica('Detalhes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])),'Endereço web para visualização em ambiente externo o detalhamento d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Detalhes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).dicaF(), "javascript: void(0);' onclick='exportar_link(\"projeto_ver\");");
			}
		$km->Add("acao","dashboard",dica('Dashboard','Dash Board d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Dashboard'.dicaF(), "javascript: void(0);");
		$km->Add("dashboard","dashboard_geral",dica('Dashboard Geral','Dashboard Geral com as informações mais pertinentes d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Geral'.dicaF(), "javascript: void(0);' onclick='url_passar(\"dashboard_geral_".$projeto_id."\", \"m=projetos&a=deshboard_geral_pro&jquery=1&dialogo=1&projeto_id=".$projeto_id."\");");
		$km->Add("acao","acao_excel",dica('Exportar '.$config['genero_projeto'].' '.ucfirst($config['projeto']).' em Formato Excel', 'Clique neste ícone '.imagem('icones/excel.png').' para exportar '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' no formato de planilha Excel.').imagem('icones/excel.png').'Exportar para Excel'.dicaF(), "javascript: void(0);' onclick='exportarProjetoExcel(".(isset($baseline_id) ? $baseline_id : '0').','.$projeto_id.");");
		}


		$selecionar_baseline=(count($baselines)> 1 && $Aplic->profissional ? '<td align="right" style="white-space: nowrap; background-color: #e6e6e6">'.dica('Baseline', 'Escolha na caixa de opção à direita a baseline que deseja visualizar.').'Baseline:'.dicaF().'</td><td style="white-space: nowrap; background-color: #e6e6e6">'.selecionaVetor($baselines, 'baseline_id', 'class="texto" style="width:200px;" size="1" onchange="mudar_baseline();"', $baseline_id).'</td>' : '<input type="hidden" name="baseline_id" id="baseline_id" value="" />');
	echo $km->Render();
	echo '</td>'.$selecionar_baseline.'</tr>';
	echo '</table>';
	}


if ($imprimir_detalhe){
	
	$rodape_varivel=array();
	$rodape_varivel['gerente']=(int)getParam($_REQUEST, 'gerente', 0);
	$rodape_varivel['supervisor']=(int)getParam($_REQUEST, 'supervisor', 0);
	$rodape_varivel['autoridade']=(int)getParam($_REQUEST, 'autoridade', 0);
	$rodape_varivel['cliente']=(int)getParam($_REQUEST, 'cliente', 0);
	$rodape_varivel['barra']=(int)getParam($_REQUEST, 'barra', 0);
	echo '<table align="left" cellspacing=0 cellpadding=0 width="100%">';
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$titulo_cabecalho='ESTÁGIOS DA DESPESA';
	if ($Aplic->profissional) {
		$barra=codigo_barra('projeto', $projeto_id, $baseline_id);
		if ($barra['cabecalho']) echo $barra['imagem'];
		}
	$sql = new BDConsulta;
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos', 'projetos');
	$sql->adCampo('projeto_id, projeto_cia, projeto_nome, projeto_codigo');
	$sql->adOnde('projeto_id = '.(int)$projeto_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$dados = $sql->Linha();
	$sql->limpar();
	$dados['titulo_cabecalho']='DETALHAMENTO';
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_projeto_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
	echo '<tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		}
	echo $tpl->exibir($modelo->edicao);
	echo '</td></tr>';
	echo '</table>';
	}


echo '</form>';

$alertaDes = '';
$plural = $statusExternas['novas'] > 1 ? 's' : '';
if($statusExternas['novas']) $alertaDes = $statusExternas['novas'].' nov'.$config['genero_tarefa'].$plural.' '.($plural ? $config['tarefas'] : $config['tarefa']).' extern'.$config['genero_tarefa'].$plural.($plural?' estão' : ' esta').' desatualizad'.$config['genero_tarefa'].$plural;

$saida_externas = '';
$plural = $statusExternas['total'] > 1 ? 's' : '';
if($statusExternas['total']){
	$saida_externas = '&nbsp;<a href="javascript: void(0);" onclick="javascript:atualizarLinks('.$projeto_id.')">'.imagem('icones/gantt.png','Atualizar '.$config['tarefas'].' extern'.$config['genero_tarefa'].'s','Clique neste ícone '.imagem('icones/gantt.png').' para visualizar uma interface onde será listad'.$config['genero_tarefa'].' '.$config['genero_tarefa'].'s '.$config['tarefas'].' extern'.$config['genero_tarefa'].'s que estão desatualizad'.$config['genero_tarefa'].'s neste '.$config['projeto'].'.').'</a>';
	$saida_externas .= '&nbsp;<span style="color: red;">'.$statusExternas['total'].' '.($plural ? $config['tarefas'] : $config['tarefa']).' extern'.$config['genero_tarefa'].$plural.($plural?' estão' : ' esta').' desatualizad'.$config['genero_tarefa'].$plural.'</span>';
	}

$cor_indicador=cor_indicador('projeto', $projeto_id);

$painel_projeto = $Aplic->getEstado('painel_projeto') !== null ? $Aplic->getEstado('painel_projeto') : 1;

echo '<table id="table_nome_projeto" cellpadding=0 cellspacing=0 width="100%"><tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_cor.'" colspan="2" onclick="if (document.getElementById(\'tblProjetos\').style.display) {document.getElementById(\'tblProjetos\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'mostrar\').style.display=\'none\';} else {document.getElementById(\'tblProjetos\').style.display=\'none\'; document.getElementById(\'contrair\').style.display=\'none\'; document.getElementById(\'mostrar\').style.display=\'\';} if(window.onResizeDetalhesProjeto) window.onResizeDetalhesProjeto(); xajax_painel_projeto(document.getElementById(\'tblProjetos\').style.display);"><a href="javascript: void(0);"><span id="mostrar" style="display:none">'.imagem('icones/mostrar.gif', 'Mostrar Detalhes', 'Clique neste ícone '.imagem('icones/mostrar.gif').' para mostrar os detalhes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</span><span id="contrair">'.imagem('icones/contrair.gif', 'Ocultar Detalhes', 'Clique neste ícone '.imagem('icones/contrair.gif').' para ocultar os detalhes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</span><font color="'.melhorCor($obj->projeto_cor).'"><b>'.$obj->projeto_nome.'<b>&nbsp;</font></a>'.$cor_indicador.$saida_externas.'</td></tr></table>';


echo '<table id="tblProjetos" cellpadding=0 cellspacing=1 width="100%" '.(!$imprimir_detalhe ? 'class="std" ' : '').'style="display:'.($painel_projeto ? '' : 'none').'">';

//if (isset($obj->projeto_cia) && $obj->projeto_cia) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->projeto_cia).'</td></tr>';

$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_cia','projeto_cia');
$sql->adCampo('projeto_cia_cia');
$sql->adOnde('projeto_cia_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
if ($baseline_id) $sql->adOnde('projeto_cia.baseline_id='.(int)$baseline_id);
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
			$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
			}
	$saida_cias.= '</td></tr></table>';
	}
if ($saida_cias) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].' com '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';





//if (isset($obj->projeto_dept) && $obj->projeto_dept) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->projeto_dept).'</td></tr>';



$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_depts','projeto_depts');
$sql->adCampo('departamento_id');
$sql->adOnde('projeto_id '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
if ($baseline_id) $sql->adOnde('projeto_depts.baseline_id='.(int)$baseline_id);
$depts = $sql->carregarColuna();
$sql->limpar();

$saida_depts='';
if (isset($depts) && count($depts)){
		$plural=(count($depts)>1 ? 's' : '');
		$saida_depts.= '<table cellspacing=0 cellpadding=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($depts[0]);
		$qnt_depts=count($depts);
		if ($qnt_depts > 1){
				$lista='';
				for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_depts\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}

if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamento'.$plural]).' Envolvid'.$config['genero_dept'].$plural, ucfirst($config['departamento'.$plural]).' envolvid'.$config['genero_dept'].$plural.'  n'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['departamento'.$plural]).' envolvid'.$config['genero_dept'].$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->projeto_principal_indicador) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'O indicador mais representativo da situação geral d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->projeto_principal_indicador).'</td></tr>';




//if (isset($obj->projeto_codigo) && $obj->projeto_codigo) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Código', 'O código d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Código:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_codigo.'</td></tr>';
if (isset($obj->projeto_setor) && $obj->projeto_setor) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSetor().'</td></tr>';
if (isset($obj->projeto_segmento) && $obj->projeto_segmento) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSegmento().'</td></tr>';
if (isset($obj->projeto_intervencao) && $obj->projeto_intervencao) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getIntervencao().'</td></tr>';
if (isset($obj->projeto_tipo_intervencao) && $obj->projeto_tipo_intervencao) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getTipoIntervencao().'</td></tr>';

if (isset($obj->projeto_descricao) && $obj->projeto_descricao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('O Que', 'O que é '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'O Que:'.dicaF().'</td><td class="realce" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_descricao.'</td></tr>';
if (isset($obj->projeto_objetivos) && $obj->projeto_objetivos) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Por Que', 'Por que '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' será executad'.$config['genero_projeto'].'.').'Por Que:'.dicaF().'</td><td class="realce" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_objetivos.'</td></tr>';
if (isset($obj->projeto_como) && $obj->projeto_como) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Como', 'Como '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' será executad'.$config['genero_projeto'].'.').'Como:'.dicaF().'</td><td class="realce" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_como.'</td></tr>';
if (isset($obj->projeto_localizacao) && $obj->projeto_localizacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Localização d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])), 'No caso de '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser um obra, atividade em local definido, ou que seja uma situação parecida, este campo deve ser preenchido.').'Onde:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_localizacao.'</td></tr>';
if (isset($obj->projeto_beneficiario) && $obj->projeto_beneficiario) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Beneficiário', 'O público atendido pel'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Beneficiário:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_beneficiario.'</td></tr>';

//if ($obj->projeto_data_inicio) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Início Previsto', 'Data de início d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Início previsto:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.retorna_data($obj->projeto_data_inicio, false).'</td></tr>';
//if ($obj->projeto_data_fim) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Término Previsto', 'Data estimada de término d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Término previsto:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.retorna_data($obj->projeto_data_fim, false).'</td></tr>';




$data_final=($projeto_id > 0 ? ($data_fim_atual ? '<span '.($data_fim_atual > $obj->projeto_data_fim ? 'style="color:red; font-weight:bold"' : '').'>'.retorna_data($data_fim_atual, false).'</span>'.($id_tarefa_fim_atual ? ' - '.link_tarefa($id_tarefa_fim_atual) : '') : '') : null);
$data_inicial=($projeto_id > 0 ? ($data_inicio_atual ? '<span '.($data_inicio_atual > $obj->projeto_data_inicio ? 'style="color:red; font-weight:bold"' : '').'>'.retorna_data($data_inicio_atual, false).'</span>'.($id_tarefa_inicio_atual ? ' - '.link_tarefa($id_tarefa_inicio_atual) : '') : '') : null);
if ($data_inicial) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data Inicial Atualizada', 'O sistema registra automaticamente, baseado na primeir'.$config['genero_tarefa'].' '.$config['tarefa'].' que necesita ser realizad'.$config['genero_tarefa'].'.').'Início atualizado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$data_inicial.'</td></tr>';
if ($data_final) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data Final Atualizada', 'O sistema registra automaticamente, baseado na ultim'.$config['genero_tarefa'].' '.$config['tarefa'].' que necesita ser realizad'.$config['genero_tarefa'].'.').'Final atualizado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$data_final.'</td></tr>';
if ($obj->projeto_encerramento) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data de Encerramento', 'A data de encerramento d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Data de encerramento:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.retorna_data($obj->projeto_encerramento, false).'</td></tr>';
//if (isset($obj->projeto_responsavel) && $obj->projeto_responsavel) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['gerente']), ucfirst($config['genero_gerente']).' '.$config['gerente'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['gerente']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->projeto_responsavel, '','','esquerda').'</td></tr>';
if (isset($obj->projeto_supervisor) && $obj->projeto_supervisor) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['supervisor']), ucfirst($config['genero_supervisor']).' '.$config['supervisor'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['supervisor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->projeto_supervisor, '','','esquerda').'</td></tr>';
//if (isset($obj->projeto_autoridade) && $obj->projeto_autoridade) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['autoridade']), ucfirst($config['genero_autoridade']).' '.$config['autoridade'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['autoridade']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->projeto_autoridade, '','','esquerda').'</td></tr>';
if (isset($obj->projeto_cliente) && $obj->projeto_cliente) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['cliente']), ucfirst($config['genero_cliente']).' '.$config['cliente'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['cliente']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->projeto_cliente, '','','esquerda').'</td></tr>';

$empregosObra=$obj->getEmpregosObra($baseline_id);
$empregosDiretos=$obj->getEmpregosDiretos($baseline_id);
$empregosIndiretos=$obj->getEmpregosIndiretos($baseline_id);

if ($empregosObra) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Empregos Gerados Durante a Execução', 'O número de empregos gerados durante a execução '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').'Empregos (execução) :'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$empregosObra.'</td></tr>';
if ($empregosDiretos) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Empregos Diretos Gerados', 'O número de empregos diretos gerados após a conclusão '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').'Empregos diretos :'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$empregosDiretos.'</td></tr>';
if ($empregosIndiretos) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Empregos Indiretos Gerados', 'O número de empregos indiretos gerados após a conclusão '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').'Empregos indiretos :'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$empregosIndiretos.'</td></tr>';
$projTipo = getSisValor('TipoProjeto');
//if (isset($projTipo[$obj->projeto_tipo])) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['categoria']), ucfirst($config['genero_categoria']).' '.$config['categoria'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['categoria']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$projTipo[$obj->projeto_tipo].'</td></tr>';
if (isset($obj->projeto_url) && $obj->projeto_url) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Link URL para '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])), 'O endereço URL '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').'URL:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="'.$obj->projeto_url.'" target="_new">'.$obj->projeto_url.'</a></td></tr>';
if (isset($obj->projeto_url_externa) && $obj->projeto_url_externa) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Página Web', 'O endereço na WWW '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].' para ser visito pelo público externo.').'Página Web:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="'.get_protocol().$obj->projeto_url_externa.'" target="_new">'.$obj->projeto_url_externa.'</a></td></tr>';



//if ($totalhoras_designados_tarefas) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Horas de Trabalho', 'Somatório das horas prevista de trabalhado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].' pelos designados para '.$config['genero_tarefa'].'s '.$config['tarefas'].' levando-se em conta o percentual de alocação. O calendário individual de cada designado não é levado em consideração neste cálculo aproximado.').'Horas de trabalho:'.dicaF().'</td><td class="realce" width="100%">'.number_format($totalhoras_designados_tarefas, 2, ',', '.').'&nbsp;'.($totalhoras_designados_tarefas > 0 ? '('.(int)((float)$totalhoras_designados_tarefas/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)).' dias)' : '').'</td></tr>';
//if ($horas_trabalhadas_registros) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Horas dos Registros', 'Somatório das horas trabalhadas n'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].' que foram inseridas nos registros d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Horas dos registros:'.dicaF().'</td><td class="realce" width="100%">'.number_format($horas_trabalhadas_registros, 2, ',', '.').'&nbsp;'.($horas_trabalhadas_registros > 0 ? '('.(int)((float)$horas_trabalhadas_registros/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)).' dias)' : '').'</td></tr>';
//echo '<tr><td align="right" style="white-space: nowrap">'.dica('Horas d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])), 'Somatório das cargas horárias d'.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' não considerando o número de '.$config['usuarios'].' designados.').'Horas d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).':'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$totalHoras, 2, ',', '.').'&nbsp;'.($totalHoras > 0 ? '('.(int)((float)$totalHoras/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)).' dias)' : '').'</td></tr>';
//if ($totalhoras_designados_tarefas && $totalHoras) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Homem/Hora n'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])), 'Somatório das cargas horárias d'.$config['genero_tarefa'].'s '.$config['tarefas'].' multiplicadas pelo número de designados com suas respectivas porcentagens, e por fim dividido pelo número de horas d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.<br><br>Exemplo:<ul><li>Um '.ucfirst($config['projeto']).' de 2 '.$config['tarefas'].' de 5 horas cada (total de 10 horas) designado amb'.$config['genero_tarefa'].'s '.$config['tarefas'].' para 2 '.$config['usuario'].', sendo o 1º à 100% e o outro à 50% dará um total de 15 horas trabalhadas.</li><li>Ao dividir pelo tempo total trabalhado (10hs) dá um valor de 1.5 homem/hora</li></ul>').'Homem/hora:'.dicaF().'</td><td class="realce" width="100%">'.number_format(($totalhoras_designados_tarefas/$totalHoras), 2, ',', '.').'&nbsp;h/hr</td></tr>';

$sql->adTabela('projetos');
$sql->esqUnir('estado', 'estado', 'projeto_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'projeto_cidade=municipio_id');
$sql->adCampo('estado_nome, municipio_nome');
$sql->adOnde('projeto_id='.(int)$projeto_id);
$endereco=$sql->Linha();
$sql->limpar();


/*if (isset($obj->projeto_endereco1) && $obj->projeto_endereco1) echo '<tr valign="top"><td align="right" style="white-space: nowrap">'.dica('Endereço', 'O enderço d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.dica('Google Maps', 'Clique esta imagem para visualizar no Google Maps, aberto em uma nova janela, o endereço d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'<a href="'.get_protocol().'maps.google.com/maps?key=AIzaSyAsFbkGMNJdcsHBSQySo8jpA7zqBhlg1Pg&q='.utf8_encode($obj->projeto_endereco1).'+'.utf8_encode($obj->projeto_endereco2).'+'.utf8_encode(municipio_nome($endereco['municipio_nome'])).'+'.utf8_encode($obj->projeto_estado).'+'.utf8_encode($obj->projeto_cep).'+'.utf8_encode($obj->projeto_pais).'" target="_blank"><img align="right" src="'.acharImagem('google_map.png').'" alt="Achar no Google Maps" /></a>'.dicaF().$obj->projeto_endereco1.(($obj->projeto_endereco2) ? '<br />'.$obj->projeto_endereco2 : '') .($obj->projeto_cidade || $obj->projeto_estado || $obj->projeto_pais ? '<br>' : '').$endereco['municipio_nome'].($obj->projeto_estado ? ' - ' : '').$obj->projeto_estado.($obj->projeto_pais ? ' - '.$paises[$obj->projeto_pais] : '').(($obj->projeto_cep) ? '<br />'.$obj->projeto_cep : '').'</td></tr>';
elseif ($endereco['municipio_nome']) echo '<tr valign="top"><td align="right" style="white-space: nowrap">'.dica('Endereço', 'O enderço d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.dica('Google Maps', 'Clique esta imagem para visualizar no Google Maps, aberto em uma nova janela, o endereço d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'<a href="'.get_protocol().'maps.google.com/maps?key=AIzaSyAsFbkGMNJdcsHBSQySo8jpA7zqBhlg1Pg&q='.$endereco['municipio_nome'].'+'.$obj->projeto_estado.'+'.$obj->projeto_pais.'" target="_blank"><img align="right" src="'.acharImagem('google_map.png').'" alt="Achar no Google Maps" /></a>'.dicaF().$endereco['municipio_nome'].($obj->projeto_estado ? ' - ' : '').$endereco['estado_nome'].($obj->projeto_pais ? ' - '.$paises[$obj->projeto_pais] : '').'</td></tr>';
if (isset($obj->projeto_latitude) && isset($obj->projeto_longitude) && $obj->projeto_latitude && $obj->projeto_longitude) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Coordenadas Geográficas', 'As coordenadas geográficas da localização d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Coordenadas:'.dicaF().'</td><td class="realce" width="100%">'.$obj->projeto_latitude.'º '.$obj->projeto_longitude.'º&nbsp;<a href="javascript: void(0);" onclick="popCoordenadas('.$obj->projeto_latitude.', '.$obj->projeto_longitude.', 0, 0, 0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Coordenadas', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa as coordenadas geográficas.').'</a></td></tr>';*/

$sql->adTabela(($baseline_id ? 'baseline_' : '').'municipio_lista','municipio_lista');
$sql->esqUnir('municipios', 'municipios', 'municipios.municipio_id=municipio_lista_municipio');
$sql->adCampo('DISTINCT municipios.municipio_id, municipio_nome, estado_sigla');
$sql->adOnde('municipio_lista_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$sql->adOrdem('estado_sigla, municipio_nome');
$lista_municipios = $sql->Lista();
$sql->limpar();

$plural_municipio=(count($lista_municipios)>1 ? 's' : '');

$sql->adTabela('projeto_area');
$sql->adCampo('DISTINCT projeto_area_id, projeto_area_nome, projeto_area_obs');
$sql->adOnde('projeto_area_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
$sql->adOrdem('projeto_area_tarefa ASC');
$lista_areas = $sql->Lista();
$sql->limpar();

$saida_areas='';
$todas_areas='';
$plural='';
if (isset($lista_areas) && count($lista_areas)){
		$plural=(count($lista_areas)>1 ? 's' : '');
		$saida_areas.= '<table cellspacing=0 cellpadding=0 width="100%">';
		$saida_areas.= '<tr><td><a href="javascript: void(0);" onclick="popCoordenadas(0,0,'.$lista_areas[0]['projeto_area_id'].', 0, 0);">'.dica('Visualizar Área ou Ponto', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área ou ponto.'.($lista_areas[0]['projeto_area_obs'] ? '<br>'.$lista_areas[0]['projeto_area_obs'] : '')).imagem('icones/coordenadas_p.png').$lista_areas[0]['projeto_area_nome'].dicaF().'</a>';
		$qnt_lista_areas=count($lista_areas);
		if ($qnt_lista_areas > 1){
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_areas; $i < $i_cmp; $i++) $lista.='<a href="javascript: void(0);" onclick="popCoordenadas(0,0,'.$lista_areas[$i]['projeto_area_id'].', 0, 0);">'.dica('Visualizar Área ou Ponto', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área ou ponto.'.($lista_areas[$i]['projeto_area_obs'] ? '<br>'.$lista_areas[$i]['projeto_area_obs'] : '')).imagem('icones/coordenadas_p.png').$lista_areas[$i]['projeto_area_nome'].'</a><br>';
				$saida_areas.= dica('Outras Áreas', 'Clique para visualizar as demais áreas.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_areas\');">(+'.($qnt_lista_areas - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_areas"><br>'.$lista.'</span>';
				$todas_areas=dica('Todas as Áreas', 'visualização de todas as áreas').'<a href="javascript: void(0);" onclick="popCoordenadas(0,0,0,'.$projeto_id.',0);">'.imagem('icones/coordenadas_p.png').'Todas as áreas'.dicaF().'</a>';
				}
		$saida_areas.= '</td></tr></table>';
		}
if ($saida_areas || (count($lista_municipios) && $tem_coordenadas)) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Área'.$plural, 'Área'.$plural.' relacionada'.$plural.' com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto'])).'Área'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce"><table cellspacing=0 cellpadding=0><tr><td>'.$saida_areas.$todas_areas.'</td><td>'.((count($lista_municipios) && $tem_coordenadas) ? '&nbsp;&nbsp;&nbsp;'.dica('Área'.$plural_municipio.' do'.$plural_municipio.' Município'.$plural_municipio, 'Visualizar a área do'.$plural_municipio.' município'.$plural_municipio.'.').'Município'.$plural_municipio.'<a href="javascript: void(0);" onclick="popAreaMunicipio(0,'.$projeto_id.',0);">'.imagem('icones/coordenadas_p.png', 'Área'.$plural_municipio.' do'.$plural_municipio.' Município'.$plural_municipio, 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a'.$plural_municipio.' área'.$plural_municipio.' do'.$plural_municipio.' município'.$plural_municipio.' incluído'.$plural_municipio.' neste '.($config['genero_projeto']=='a' ? 'nesta': 'neste').' '.$config['projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';


$saida_municipios='';
if (isset($lista_municipios) && count($lista_municipios)){
		$plural=(count($lista_municipios)>1 ? 's' : '');
		$saida_municipios.= '<table cellspacing=0 cellpadding=0 width="100%">';
		$saida_municipios.= '<tr><td>'.$lista_municipios[0]['municipio_nome'].'-'.$lista_municipios[0]['estado_sigla'].($tem_coordenadas ? '<a href="javascript: void(0);" onclick="popAreaMunicipio('.$lista_municipios[0]['municipio_id'].',0,0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Área do Município', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área do município.').'</a>' : '');
		$qnt_lista_municipios=count($lista_municipios);
		if ($qnt_lista_municipios > 1){
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_municipios; $i < $i_cmp; $i++) $lista.=$lista_municipios[$i]['municipio_nome'].'-'.$lista_municipios[$i]['estado_sigla'].($tem_coordenadas ? '<a href="javascript: void(0);" onclick="popAreaMunicipio('.$lista_municipios[$i]['municipio_id'].',0,0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Área do Município', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área do município.').'</a>' : '').'<br>';
				$saida_municipios.= dica('Outros Municípios', 'Clique para visualizar os demais municípios.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_municipios\');">(+'.($qnt_lista_municipios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_municipios"><br>'.$lista.'</span>';
				}
		$saida_municipios.= '</td></tr></table>';
		}
if ($saida_municipios) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Município'.$plural, 'Município'.$plural.' relacionado'.$plural.' com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto'])).'Município'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_municipios.'</td></tr>';


if ($obj->projeto_justificativa) echo '<tr><td align="right">'.dica('Justificativa', 'Descreve de forma clara a justificativa contendo um breve histórico e as motivações d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Justificativa:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_justificativa.'</td></tr>';
if ($obj->projeto_objetivo) echo '<tr><td align="right">'.dica('Objetivo', 'Descreve qual o objetivo para a qual órgão está realizando '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', que pode ser: descrição concreta de que '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' quer alcançar, uma posição estratégica a ser alcançada, um resultado a ser obtido, um produto a ser produzido ou um serviço a ser realizado. Os objetivos devem ser específicos, mensuráveis, realizáveis, realísticos, e baseados no tempo.').'Objetivo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_objetivo.'</td></tr>';
if ($obj->projeto_objetivo_especifico) echo '<tr><td align="right">'.dica('Objetivos Específicos', 'Descreve qual são os objetivos específicos d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Objetivos específicos:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_objetivo_especifico.'</td></tr>';
if ($obj->projeto_escopo) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Declaração de Escopo', 'Descreve a declaração do escopo, que inclui as principais entregas, fornece uma base documentada para futuras decisões d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' e para confirmar ou desenvolver um entendimento comum do escopo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' entre as partes interessadas.').'Escopo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_escopo.'</td></tr>';
if ($obj->projeto_nao_escopo) echo '<tr><td align="right">'.dica('Não escopo', 'Descreve de forma explícita o que está excluído d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', para evitar que uma parte interessada possa supor que um produto, serviço ou resultado específico é um produto d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Não escopo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_nao_escopo.'</td></tr>';
if ($obj->projeto_premissas) echo '<tr><td align="right">'.dica('Premissas', 'Descreve as premissas d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. As premissas são fatores que, para fins de planejamento, são considerados verdadeiros, reais ou certos sem prova ou demonstração. As premissas afetam todos os aspectos do planejamento d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' e fazem parte da elaboração progressiva d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Frequentemente, as equipes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' identificam, documentam e validam as premissas durante o processo de planejamento. Geralmente, as premissas envolvem um grau de risco.').'Premissas:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_premissas.'</td></tr>';
if ($obj->projeto_restricoes) echo '<tr><td align="right">'.dica('Restrições', 'Descreve as restrições d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Uma restrição é uma limitação aplicável, interna ou externa d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', que afetará o desempenho d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ou de um processo. Por exemplo, uma restrição do cronograma é qualquer limitação ou condição colocada em relação ao cronograma d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' que afeta o momento em que uma atividade do cronograma pode ser agendada e geralmente está na forma de datas impostas fixas.').'Restrições:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_restricoes.'</td></tr>';
if ($obj->projeto_orcamento) echo '<tr><td align="right">'.dica('Custos Estimados e Fontes de Recursos', 'Descreve a estimativa de custos e fontes de recursos d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Custos e fontes:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_orcamento.'</td></tr>';
if ($obj->projeto_beneficio) echo '<tr><td align="right">'.dica('Benefícios', 'Descreve os benefícios advindo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Declarações que mostram como o produto, sua característica ou vantagem satisfaz uma necessidade explícita.').'Benefícios:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_beneficio.'</td></tr>';
if ($obj->projeto_produto) echo '<tr><td align="right">'.dica('Produtos', 'Descreve os produtos advindo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Produtos:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_produto.'</td></tr>';
if ($obj->projeto_requisito) echo '<tr><td align="right">'.dica('Requisitos', 'Descreve os requisitos para '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Os requisitos refletem as necessidades e as expectativas das partes interessadas no projeto. Eles devem ser analisados e registrados com detalhes suficientes para serem medidos, uma vez que vão ser a base para definir as alternativas de condução do projeto e se transformarão na fundação da EAP. Custo, Cronograma e o planejamento da qualidade são baseados no requisitos.').'Requisitos:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_requisito.'</td></tr>';




$sql->adTabela('projeto_gestao');
$sql->adCampo('projeto_gestao.*');
$sql->adOnde('projeto_gestao_projeto ='.(int)$projeto_id);	
$sql->adOrdem('projeto_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
$qnt_gestao=0;

if (count($lista)) {
	echo '<tr><td align="right" style="white-space: nowrap" valign="middle">'.dica('Relacionad'.($obj->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto']), 'A que área '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' está relacionad'.($obj->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto']).'.').'Projeto Padrã'.($obj->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto']).':'.dicaF().'</td></td><td class="realce">';	
	foreach($lista as $gestao_data){
		if ($gestao_data['projeto_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_gestao_tarefa']);
		
		elseif ($gestao_data['projeto_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_gestao_semelhante']);
		
		elseif ($gestao_data['projeto_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_gestao_pratica']);
		elseif ($gestao_data['projeto_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_gestao_acao']);
		elseif ($gestao_data['projeto_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_gestao_perspectiva']);
		elseif ($gestao_data['projeto_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['projeto_gestao_tema']);
		elseif ($gestao_data['projeto_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_gestao_objetivo']);
		elseif ($gestao_data['projeto_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_gestao_fator']);
		elseif ($gestao_data['projeto_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_gestao_estrategia']);
		elseif ($gestao_data['projeto_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_gestao_meta']);
		elseif ($gestao_data['projeto_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_gestao_canvas']);
		elseif ($gestao_data['projeto_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['projeto_gestao_risco']);
		elseif ($gestao_data['projeto_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_gestao_risco_resposta']);
		elseif ($gestao_data['projeto_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_gestao_indicador']);
		elseif ($gestao_data['projeto_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_gestao_calendario']);
		elseif ($gestao_data['projeto_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_gestao_monitoramento']);
		elseif ($gestao_data['projeto_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_gestao_ata']);
		elseif ($gestao_data['projeto_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_gestao_mswot']);
		elseif ($gestao_data['projeto_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['projeto_gestao_swot']);
		elseif ($gestao_data['projeto_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_gestao_operativo']);
		elseif ($gestao_data['projeto_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_gestao_instrumento']);
		elseif ($gestao_data['projeto_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_gestao_recurso']);
		elseif ($gestao_data['projeto_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['projeto_gestao_problema']);
		elseif ($gestao_data['projeto_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_gestao_demanda']);	
		elseif ($gestao_data['projeto_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['projeto_gestao_programa']);
		elseif ($gestao_data['projeto_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_gestao_licao']);
		elseif ($gestao_data['projeto_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_gestao_evento']);
		elseif ($gestao_data['projeto_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['projeto_gestao_link']);
		elseif ($gestao_data['projeto_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_gestao_avaliacao']);
		elseif ($gestao_data['projeto_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_gestao_tgn']);
		elseif ($gestao_data['projeto_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_gestao_brainstorm']);
		elseif ($gestao_data['projeto_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_gestao_gut']);
		elseif ($gestao_data['projeto_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_gestao_causa_efeito']);
		elseif ($gestao_data['projeto_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_gestao_arquivo']);
		elseif ($gestao_data['projeto_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_gestao_forum']);
		elseif ($gestao_data['projeto_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_gestao_checklist']);
		elseif ($gestao_data['projeto_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_gestao_agenda']);
		elseif ($gestao_data['projeto_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_gestao_agrupamento']);
		elseif ($gestao_data['projeto_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_gestao_patrocinador']);
		elseif ($gestao_data['projeto_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['projeto_gestao_template']);
		elseif ($gestao_data['projeto_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['projeto_gestao_painel']);
		elseif ($gestao_data['projeto_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_gestao_painel_odometro']);
		elseif ($gestao_data['projeto_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_gestao_painel_composicao']);		
		elseif ($gestao_data['projeto_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['projeto_gestao_tr']);	
		elseif ($gestao_data['projeto_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['projeto_gestao_me']);	
		elseif ($gestao_data['projeto_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_gestao_acao_item']);	
		elseif ($gestao_data['projeto_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_gestao_beneficio']);	
		elseif ($gestao_data['projeto_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_gestao_painel_slideshow']);	
		elseif ($gestao_data['projeto_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_gestao_projeto_viabilidade']);	
		elseif ($gestao_data['projeto_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_gestao_projeto_abertura']);	
		elseif ($gestao_data['projeto_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_gestao_plano_gestao']);	
		elseif ($gestao_data['projeto_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_gestao_ssti']);
		elseif ($gestao_data['projeto_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_gestao_laudo']);
		elseif ($gestao_data['projeto_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_gestao_trelo']);
		elseif ($gestao_data['projeto_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_gestao_trelo_cartao']);
		elseif ($gestao_data['projeto_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_gestao_pdcl']);
		elseif ($gestao_data['projeto_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_gestao_pdcl_item']);
	
		}
	echo '</td></tr>';
	}	

//if (isset($obj->projeto_fonte) && $obj->projeto_fonte) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto_fonte']), ucfirst($config['projeto_fonte']).' '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['projeto_fonte']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_fonte.'</td></tr>';
//if (isset($obj->projeto_regiao) && $obj->projeto_regiao) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto_regiao']), ucfirst($config['projeto_regiao']).' '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['projeto_regiao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_regiao.'</td></tr>';
if (isset($obj->projeto_observacao) && $obj->projeto_observacao) echo '<tr><td align="right">'.dica('Observações', 'Observações sobre '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Observações:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->projeto_observacao.'</td></tr>';


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nível de Acesso', ($obj->projeto_portfolio ? ucfirst($config['genero_portfolio']).'s '.$config['portfolios'] : ucfirst($config['genero_projeto']).'s '.$config['projetos']).' podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' e os integrantes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' pel'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' podem editar.</li><li><b>Participante I</b> - Somente o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' e os integrantes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' podem ver e editar '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' e os integrantes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' podem ver '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', e o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$projeto_acesso[$obj->projeto_acesso].'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Prioridade', 'A prioridade para fins de filtragem.').'Prioridade:'.dicaF().'</td><td class="realce" style="background-color:'.$corPrioridadeProjeto[$obj->projeto_prioridade].'" width="100%" >'.prioridade($obj->projeto_prioridade, true, true).'</td></tr>';


//if (isset($projStatus[$obj->projeto_status])) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Status d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.ucfirst($config['portfolio']) : $config['genero_projeto'].' '.ucfirst($config['projeto'])), 'O Status d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Status:'.dicaF().'</td><td class="realce" width="100%">'.$projStatus[$obj->projeto_status].'</td></tr>';

if ($exibir['projeto_fase']){
	$projetoFase = getSisValor('projetoFase');
	if (isset($projetoFase[$obj->projeto_fase])) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Fase', 'A Fase d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Fase:'.dicaF().'</td><td class="realce" width="100%">'.$projetoFase[$obj->projeto_fase].'</td></tr>';
	}





	$sql->adTabela('demandas');
	$sql->adOnde('demanda_projeto = '.(int)$projeto_id);
	$sql->adCampo('demanda_id');
	$sql->adOrdem('demanda_nome');
	$demandas=$sql->carregarColuna();
	$sql->limpar();
	$saida_demanda=array();
	foreach($demandas as $demanda) $saida_demanda[]=link_demanda($demanda);
	if (count($saida_demanda)) echo '<tr><td align="right">'.dica('Demanda', 'Demanda relacionada com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Demanda:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.implode('<br>', $saida_demanda).'</td></tr>';



	$sql->adTabela('pi');
	$sql->adOnde('pi_projeto = '.(int)$projeto_id);
	$sql->adCampo('pi_pi');
	$sql->adOrdem('pi_ordem');
	$pi=$sql->carregarColuna();
	$sql->limpar();
	if (count($pi)) echo '<tr><td align="right">'.dica('PI', 'Os PI relacionados com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'PI:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.implode('<br>', $pi).'</td></tr>';

	$sql->adTabela('ptres');
	$sql->adOnde('ptres_projeto = '.(int)$projeto_id);
	$sql->adCampo('ptres_ptres');
	$sql->adOrdem('ptres_ordem');
	$ptres=$sql->carregarColuna();
	$sql->limpar();
	if (count($ptres)) echo '<tr><td align="right">'.dica('PTRES', 'Os PTRES relacionados com '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'PTRES:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.implode('<br>', $ptres).'</td></tr>';
	



	//echo '<tr><td align="right" style="white-space: nowrap">'.dica('Alerta Ativo', 'Caso esteja marcado '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' será incluíd'.$config['genero_projeto'].' no sistema de alertas automáticos (precisa ser executado em background o arquivo server/alertas/alertas_pro.php).').'Alerta ativo:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_alerta ? 'Sim' : 'Não').'</td></tr>';
	//echo '<tr><td align="right" style="white-space: nowrap">'.dica('Físico Através de Registro', 'Caso esteja marcado a execução física d'.$config['genero_tarefa'].'s '.$config['tarefas'].' só se modificam através de registros de ocorrências.').'Físico através de registro:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_fisico_registro ? 'Sim' : 'Não').'</td></tr>';
	//echo '<tr><td align="right" style="white-space: nowrap">'.dica('Travar Datas', 'Caso esteja marcado as datas de início e térrmino d'.$config['genero_tarefa'].'s '.$config['tarefas'].' só poderão ser editadas por quem tem permissão de editar '.$config['genero_projeto'].' '.$config['projeto'].'.').'Travar datas:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_trava_data ? 'Sim' : 'Não').'</td></tr>';
	//echo '<tr><td align="right" style="white-space: nowrap">'.dica('Aprovar Registro', 'Caso esteja marcado as mudanças de status, execução física, datas de início e término d'.$config['genero_tarefa'].'s '.$config['tarefas'].' efetuadas em registro de ocorrência só se efetivarão após a aprovação dos registros.').'Aprovar registro:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_aprova_registro ? 'Sim' : 'Não').'</td></tr>';
	
echo '<tr><td align="right" style="white-space: nowrap">'.dica(($obj->projeto_portfolio ? ucfirst($config['portfolio']) : ucfirst($config['projeto'])).' Ativo', 'Caso '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' não tenha se encerrado, nem se encontre suspenso e já tenha iniciado os trabalhos, deverá estar ativo').'Ativo:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_ativo ? 'Sim' : 'Não').'</td></tr>';


$velocidade_fisico=($Aplic->profissional ? $obj->fisico_velocidade($hoje) : 0);
if ($obj->projeto_portfolio) $obj->projeto_percentagem=portfolio_porcentagem($projeto_id);
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Progresso', 'O percentual d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' já executad'.$config['genero_projeto'].'.').'Progresso:'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$obj->projeto_percentagem, 2, ',', '.').'%</td></tr>';
if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Físico Planejado', 'O percentual d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' previsto para ser na data atual.').'Físico planejado:'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$obj->fisico_previsto($hoje), 2, ',', '.').'%</td></tr>';
if ($Aplic->profissional)	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Velocidade do Físico', 'O razão entre o progresso e físico previsto d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' para a data atual.').'Velocidade do físico:'.dicaF().'</td><td class="realce" width="100%">'.number_format((float)$velocidade_fisico, 2, ',', '.').'</td></tr>';

if ($Aplic->profissional && isset($moedas[$obj->projeto_moeda])) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'A moeda padrão utilizada n'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Moeda:'.dicaF().'</td><td class="realce" width="100%">'.$moedas[$obj->projeto_moeda].'</td></tr>';
if ($obj->projeto_meta_custo)	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Meta de custo', 'Meta inicial de custo d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Servirá de comparação com o custo efetivo que é a soma de tod'.$config['genero_tarefa'].'s '.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Valor contratado:'.dicaF().'</td><td class="realce" width="100%">'.$moedas[$obj->projeto_moeda].'&nbsp;'.number_format((float)$obj->projeto_meta_custo, 2, ',', '.').'</td></tr>';

if ($Aplic->profissional && $tem_aprovacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Aprovado', 'Se '.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' se encontra aprovad'.($obj->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto']).'.').'Aprovad'.($obj->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto']).':'.dicaF().'</td><td  class="realce" width="100%">'.($obj->projeto_aprovado ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $obj->projeto_id, 'ver');

if ($campos_customizados->count()){
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}

echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="exibir_financeiro();"><a href="javascript: void(0);" class="aba"><b>Financeiro</b></a></td></tr>';
echo '<tr><td colspan="3"><table width="100%" cellspacing=0 cellpadding=0 id="ver_financeiro" style="display:none"><tr><td><div id="combo_financeiro">';
echo '</div></td></tr></table></td></tr>';


//contatos
$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_contatos','projeto_contatos');
$sql->esqUnir('contatos', 'c', 'c.contato_id = projeto_contatos.contato_id');
$sql->esqUnir('cias', 'cias', 'cias.cia_id = c.contato_cia');
$sql->adCampo('envolvimento, projeto_contatos.contato_id, perfil, cia_nome, contato_funcao');
$sql->adOnde('projeto_id = '.$projeto_id);
if ($baseline_id) $sql->adOnde('projeto_contatos.baseline_id='.(int)$baseline_id);
$sql->adOrdem('ordem ASC');
$contatos = $sql->ListaChave('contato_id');
$sql->limpar();
if (count($contatos)){
		echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_envolvidos\').style.display) document.getElementById(\'apresentar_envolvidos\').style.display=\'\'; else document.getElementById(\'apresentar_envolvidos\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Contatos</b></a></td></tr>';
		echo '<tr id="apresentar_envolvidos" style="display:'.($dialogo ? '' : 'none').'"><td colspan=20><table cellspacing=0 cellpadding=0 class="tbl1">';
		echo '<tr><th>Nome</th><th>'.ucfirst($config['organizacao']).'</th><th>Função</th><th>Relevância</th><th>Característica/Perfil</th></tr>';
		foreach ($contatos as $contato_id => $contato_data){
			echo '<tr class="realce" align="center">';
			echo '<td align="left">'.link_contato($contato_id,'','','esquerda').'</td>';
			echo '<td align="left">'.$contato_data['cia_nome'].'</td>';
			echo '<td align="left">'.$contato_data['contato_funcao'].'</td>';
			echo '<td align="left">'.$contato_data['envolvimento'].'</td>';
			echo '<td align="left">'.$contato_data['perfil'].'</td>';
			echo '</tr>';
			}
		echo '</table></td></tr>';
		}


//integrantes
$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_integrantes','projeto_integrantes');
$sql->adCampo('projeto_integrante_competencia, projeto_integrante_atributo, contato_id, projeto_integrantes_necessidade, projeto_integrantes_situacao');
$sql->adOnde('projeto_id = '.$projeto_id);
if ($baseline_id) $sql->adOnde('projeto_integrantes.baseline_id='.(int)$baseline_id);
$sql->adOrdem('ordem ASC');
$contatos = $sql->ListaChave('contato_id');
$sql->limpar();
if (count($contatos)){
		echo '<tr><td width="100%" cellspacing=0 cellpadding=0 colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_integrantes\').style.display) document.getElementById(\'apresentar_integrantes\').style.display=\'\'; else document.getElementById(\'apresentar_integrantes\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Integrantes d'.($obj->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'</b></a></td></tr>';
		echo '<tr><td colspan="3"><table cellspacing=0 cellpadding=0 id="apresentar_integrantes" style="display:'.($dialogo ? '' : 'none').'" class="tbl1">';
		echo '<tr><th>Nome</th><th>Compentência</th></tr>';
		//echo '<tr><th>Nome</th><th>Compentência</th><th>Atributos</th><th>Situação</th><th>Necessidade</th></tr>';
		foreach ($contatos as $contato_id => $contato_data){
			echo '<tr class="realce" align="center">';
			echo '<td align="left">'.link_contato($contato_id, '','','esquerda').'</td>';
			echo '<td align="left">'.($contato_data['projeto_integrante_competencia'] ? $contato_data['projeto_integrante_competencia'] : '&nbsp;').'</td>';
			//echo '<td align="left">'.($contato_data['projeto_integrante_atributo'] ? $contato_data['projeto_integrante_atributo'] : '&nbsp;').'</td>';
			//echo '<td align="left">'.($contato_data['projeto_integrantes_situacao'] ? $contato_data['projeto_integrantes_situacao'] : '&nbsp;').'</td>';
			//echo '<td align="left">'.($contato_data['projeto_integrantes_necessidade'] ? $contato_data['projeto_integrantes_necessidade'] : '&nbsp;').'</td>';
			echo '</tr>';
			}
		echo '</table></td></tr>';
		}



//stackholders


	$sql->adTabela('projeto_stakeholder');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = projeto_stakeholder_contato');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, cia_nome');
	$sql->adOnde('projeto_stakeholder_projeto = '.(int)$projeto_id);
	$sql->adCampo('projeto_stakeholder.*');
	$sql->adOrdem('projeto_stakeholder_ordem');
	$stakeholders=$sql->ListaChave('projeto_stakeholder_id');
	$sql->limpar();
	if (count($stakeholders)){
		$StakeholderPerfil=getSisValor('StakeholderPerfil','','','sisvalor_id');
		$faixas=array('3'=>'Alta', '2'=>'Média','1'=>'Baixa');
		$faixasM=array('3'=>'Alto', '2'=>'Médio','1'=>'Baixo');

		echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'stakeholder\').style.display) document.getElementById(\'stakeholder\').style.display=\'\'; else document.getElementById(\'stakeholder\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Stakeholders</b></a></td></tr>';
		echo '<tr><td colspan="3"><table cellspacing=0 cellpadding=0 class="tbl1" cellpadding=0 id="stakeholder" style="display:'.($dialogo ? '' : 'none').'">';
		echo '<tr><th>Nome</th><th>'.ucfirst($config['organizacao']).'</th><th>Perfil</th><th>Autoridade</th><th>Interesse</th><th>Influência</th><th>Impacto</th><th>Descrição</th></tr>';
		foreach ($stakeholders as $projeto_stakeholder_id => $linha){
			echo '<tr align="center">';
			echo '<td align="left">'.link_contato($linha['projeto_stakeholder_contato'], '','','esquerda').'</td>';
			echo '<td align="left">'.$linha['cia_nome'].'</td>';
			echo '<td align="left">'.(isset($StakeholderPerfil[$linha['projeto_stakeholder_perfil']]) ? $StakeholderPerfil[$linha['projeto_stakeholder_perfil']] : '').'</td>';
			echo '<td align="left">'.(isset($faixas[$linha['projeto_stakeholder_autoridade']]) ? $faixas[$linha['projeto_stakeholder_autoridade']] : '').'</td>';
			echo '<td align="left">'.(isset($faixasM[$linha['projeto_stakeholder_interesse']]) ? $faixasM[$linha['projeto_stakeholder_interesse']] : '').'</td>';
			echo '<td align="left">'.(isset($faixas[$linha['projeto_stakeholder_influencia']]) ? $faixas[$linha['projeto_stakeholder_influencia']] : '').'</td>';
			echo '<td align="left">'.(isset($faixasM[$linha['projeto_stakeholder_impacto']]) ? $faixasM[$linha['projeto_stakeholder_impacto']] : '').'</td>';
			echo '<td align="left">'.$linha['projeto_stakeholder_descricao'].'</td>';
			echo '</tr>';
			}
		echo '</table></td></tr>';
		}
	


if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/projeto_ver_pro.php';


$sql->adTabela('projetos');
$sql->adCampo('COUNT(projeto_id)');
$sql->adOnde('projeto_superior_original = '.(int)($obj->projeto_superior_original ? $obj->projeto_superior_original : $projeto_id));
$quantidade_projetos = $sql->Resultado();
$sql->limpar();

if ($quantidade_projetos > 1){
		echo '<tr><td colspan="2">'.dica('Mostrar Multi'.$config['projetos'], 'Clique neste ícone '.imagem('icones/expandir.gif').' para mostrar a estrutura.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'multiprojeto\', \'tblProjetos\')"><img id="multiprojeto_expandir" src="'.acharImagem('icones/expandir.gif').'" width="12" height="12" style="display:none">'.dicaF().dica('Ocultar Multiprojeto', 'Clique neste ícone '.imagem('icones/colapsar.gif').' para ocultar a estrutura').'<img id="multiprojeto_colapsar" src="'.acharImagem('icones/colapsar.gif').'" width="12" height="12" style="display:">'.dicaF().'</a>&nbsp;<b>'.ucfirst($config['genero_projeto']).' '.ucfirst($config['projetos']).' é parte de uma estrutura multiprojetos<b></td></tr>';
		echo '<tr id="multiprojeto" style="visibility:colapsar;display:"><td style="background-color:#f2f0ec;" colspan="2" class="realce">';
		include_once BASE_DIR.'/modulos/projetos/ver_sub_projetos.php';
		echo '</td></tr>';
		}



echo '</table>';



if ($imprimir_detalhe  && $Aplic->profissional && $barra['rodape']) echo $barra['imagem'];

if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';




if (!$dialogo){
 echo '</div><div id="tab_panel_container">';     
	$caixaTab = new CTabBox('m=projetos&a=ver&projeto_id='.(int)$projeto_id, '', $tab);
	$texto_consulta = '?m=projetos&a=ver&projeto_id='.(int)$projeto_id;
	$mostrar_tarefa = ($Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'acesso'));
	$mostrar_calendario=($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso'));
	$mostrar_arquivos=($Aplic->checarModulo('arquivos', 'acesso') && $Aplic->modulo_ativo('arquivos'));
	$mostrar_links=($Aplic->checarModulo('links', 'acesso') && $Aplic->modulo_ativo('links'));
	$mostrar_historico=($config['registrar_mudancas'] && $Aplic->checarModulo('historico', 'acesso') && $Aplic->modulo_ativo('historico'));
	$mostrar_praticas=($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso'));

	$qnt_aba=0;
	
	if ($mostrar_tarefa && !$obj->projeto_portfolio){
	  if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/tarefas_projeto_pro', ucfirst($config['tarefas']),null,null,ucfirst($config['tarefas']),'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' relacionadas a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.');
	  else $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/tarefas_grande', ucfirst($config['tarefas']).' Resumo',null,null,ucfirst($config['tarefas']).' Resumo','Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' relacionadas a '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].' de 20 em 20.');
		if (!$Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/tarefas_projeto', ucfirst($config['tarefas']),null,null,ucfirst($config['tarefas']),'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' relacionadas a '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'este' : 'esta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto']).'.');
		$qnt_aba++;
		}
	$portfolio=$projeto_id;
	if ($obj->projeto_portfolio){
    if(!$Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_gantt', 'Gantt',null,null,'Gráfico Gantt','Visualizar o gráfico Gantt d'.$config['genero_portfolio'].' '.$config['portfolio'].'.');
    else $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_gantt_pro', 'Gantt',null,null,'Gráfico Gantt','Visualizar o gráfico Gantt d'.$config['genero_portfolio'].' '.$config['portfolio'].'.');
    $qnt_aba++;
    }
    
  if ($obj->projeto_portfolio) {$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' '.($config['genero_portfolio']=='a' ? 'desta' : 'deste').' '.$config['portfolio'].'.');
  	$qnt_aba++;
  	}

	if ($mostrar_tarefa){
		if (!$obj->projeto_portfolio){
			if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_gantt_pro', 'Gantt',null,null,'Gráfico Gantt','Visualizar o gráfico Gantt '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.');
			else $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_gantt', 'Gantt',null,null,'Gráfico Gantt','Visualizar o gráfico Gantt '.($obj->projeto_portfolio ? ($config['genero_portfolio']=='o' ? 'deste' : 'desta').' '.$config['portfolio'] : ($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto']).'.');
			$qnt_aba++;
			}
		}
	
	 
  if ($Aplic->checarModulo('log', 'acesso')) {
	$sql->adTabela('log');
	$sql->adCampo('count(log_id)');
	$sql->adOnde('log_projeto = '.(int)$projeto_id);
	$existe=$sql->resultado();
	$sql->limpar();
	if ($existe) {
		$qnt_aba++;
		$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver', 'Registro',null,null,'Registro das Ocorrência','Visualizar o registro de ocorrência relacionado.');
		}
	}
	
	
	
	
	if ($mostrar_tarefa){
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'log', 'log');
		$sql->esqUnir('tarefas','tarefas', 'log.log_tarefa=tarefas.tarefa_id');
		$sql->adCampo('count(log_id)');
		$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
		$existe=$sql->resultado();
		$sql->limpar();
	
		
		if ($existe) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_logs', 'Registros d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']),null,null,'Registros d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']),'Visualizar os registros de '.$config['tarefas'].'.');
		}

	
	if ($Aplic->profissional) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_entrega','tarefa_entrega');
		$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas','tarefas','tarefa_id=tarefa_entrega_tarefa');
		if ($baseline_id){
			$sql->adOnde('tarefa_entrega.baseline_id='.(int)$baseline_id);
			$sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
			}
		$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
		$sql->adCampo('count(tarefa_entrega_id)');
		$existe=$sql->resultado();
		$sql->limpar();

		if ($existe) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/entrega_lista_pro', 'Entregas',null,null,'Entregas','Visualizar as entregas a '.($config['genero_projeto']=='a' ? 'esta ': 'este ').$config['projeto'].'.');
		}


	if ($Aplic->profissional && $Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso')) {

		$sql->adTabela('financeiro_rel_nc');
		$sql->adOnde('financeiro_rel_nc_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
		$sql->adCampo('count(financeiro_rel_nc_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) $caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_nc', 'NC',null,null,'Notas de Crédito','Visualizar as notas de crédito relacionadas a '.($config['genero_projeto']=='a' ? 'esta ': 'este ').$config['projeto'].'.');

		$sql->adTabela('financeiro_rel_ne');
		$sql->adOnde('financeiro_rel_ne_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
		$sql->adCampo('count(financeiro_rel_ne_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) $caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_ne', 'NE',null,null,'Notas de Empenho','Visualizar as notas de empenho relacionadas a '.($config['genero_projeto']=='a' ? 'esta ': 'este ').$config['projeto'].'.');

		$sql->adTabela('financeiro_rel_ns');
		$sql->adOnde('financeiro_rel_ns_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
		$sql->adCampo('count(financeiro_rel_ns_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe && $configuracao['organizacao']=='sema_mt') $caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_ns', 'NL',null,null,'Notas de Liquidação','Visualizar as notas de liquidação relacionadas a '.($config['genero_projeto']=='a' ? 'esta ': 'este ').$config['projeto'].'.');
		elseif ($existe) $caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_ns', 'NS',null,null,'Notas de Sistema','Visualizar as notas de sistema relacionadas a '.($config['genero_projeto']=='a' ? 'esta ': 'este ').$config['projeto'].'.');
		
		$sql->adTabela('financeiro_rel_ob');
		$sql->adOnde('financeiro_rel_ob_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
		$sql->adCampo('count(financeiro_rel_ob_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) $caixaTab->adicionar(BASE_DIR.'/modulos/financeiro/financeiro_rel_ob', 'OB',null,null,'Ordens Bancárias','Visualizar as ordens bancárias relacionadas a '.($config['genero_projeto']=='a' ? 'esta ': 'este ').$config['projeto'].'.');
		}

	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'evento_gestao','evento_gestao');
			$sql->esqUnir(($baseline_id ? 'baseline_' : '').'eventos','eventos', 'evento_id=evento_gestao_evento');
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=evento_gestao_tarefa');
			if ($baseline_id){
				$sql->adOnde('evento_gestao.baseline_id='.(int)$baseline_id);
				$sql->adOnde('eventos.baseline_id='.(int)$baseline_id);
				}
			$sql->adOnde('evento_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'arquivo_gestao','arquivo_gestao');
			$sql->esqUnir('arquivo','arquivo', 'arquivo_id=arquivo_gestao_arquivo');
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=arquivo_gestao_tarefa');
			if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
			$sql->adOnde('arquivo_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'pratica_indicador_gestao','pratica_indicador_gestao');
			$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador_id=pratica_indicador_gestao_indicador');
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=pratica_indicador_gestao_tarefa');
			if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
			$sql->adOnde('pratica_indicador_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_acao_gestao','plano_acao_gestao');
			$sql->esqUnir('plano_acao','plano_acao', 'plano_acao_id=plano_acao_gestao_acao');
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=plano_acao_gestao_tarefa');
			if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
			$sql->adOnde('plano_acao_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_acao_item_gestao');
		$sql->esqUnir('plano_acao_item','plano_acao_item', 'plano_acao_item_id=plano_acao_item_gestao_plano_acao_item');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=plano_acao_item_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('plano_acao_item_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_gestao');
			$sql->esqUnir('projetos','projetos', 'projeto_id=projeto_gestao_projeto');
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=projeto_gestao_tarefa');
			if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
			$sql->adOnde('projeto_gestao_semelhante='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
			$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
			$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
			$sql->adCampo('count(projeto_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos', ucfirst($config['projeto']),null,null,ucfirst($config['projeto']),'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.');
			}
		}		
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'ata_gestao','ata_gestao');
		$sql->esqUnir('ata','ata', 'ata_id=ata_gestao_ata');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=ata_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('ata_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'demanda_gestao');
		$sql->esqUnir('demandas','demandas', 'demanda_id=demanda_gestao_demanda');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=demanda_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('demanda_ativa=1');
		$sql->adOnde('demanda_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'msg_gestao','msg_gestao');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=msg_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('msg_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
		$sql->adOnde('msg_gestao_msg IS NOT NULL');
		$sql->adCampo('count(msg_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
				$qnt_aba++;
				$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg', ucfirst($config['mensagem']),null,null,ucfirst($config['mensagem']),'Visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.');
				}
		if ($config['doc_interno']) {
			$sql->adTabela(($baseline_id ? 'baseline_' : '').'modelo_gestao','modelo_gestao');
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=modelo_gestao_tarefa');
			if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
			$sql->adOnde('modelo_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'link_gestao','link_gestao');
			$sql->esqUnir('links','links', 'link_id=link_gestao_link');
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=link_gestao_tarefa');
			if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
			$sql->adOnde('link_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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

			$sql->adTabela(($baseline_id ? 'baseline_' : '').'forum_gestao','forum_gestao');
			$sql->esqUnir('foruns','foruns', 'forum_id=forum_gestao_forum');
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=forum_gestao_tarefa');
			if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
			$sql->adOnde('forum_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'problema_gestao','problema_gestao');
		$sql->esqUnir('problema','problema', 'problema_id=problema_gestao_problema');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=problema_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('problema_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'risco_gestao','risco_gestao');
		$sql->esqUnir('risco','risco', 'risco_id=risco_gestao_risco');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=risco_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('risco_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'risco_resposta_gestao', 'risco_resposta_gestao');
		$sql->esqUnir('risco_resposta','risco_resposta', 'risco_resposta_id=risco_resposta_gestao_risco_resposta');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=risco_resposta_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('risco_resposta_gestao.baseline_id='.(int)$baseline_id);
		$sql->adOnde('risco_resposta_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'instrumento_gestao','instrumento_gestao');
		$sql->esqUnir('instrumento','instrumento', 'instrumento_id=instrumento_gestao_instrumento');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=instrumento_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('instrumento_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'recurso_gestao');
		$sql->esqUnir('recursos','recursos', 'recurso_id=recurso_gestao_recurso');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=recurso_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('recurso_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'patrocinador_gestao');
		$sql->esqUnir('patrocinadores','patrocinadores', 'patrocinador_id=patrocinador_gestao_patrocinador');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=patrocinador_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('patrocinador_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'programa_gestao');
		$sql->esqUnir('programa','programa', 'programa_id=programa_gestao_programa');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=programa_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('programa_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'beneficio_gestao');
		$sql->esqUnir('beneficio','beneficio', 'beneficio_id=beneficio_gestao_beneficio');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=beneficio_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('beneficio_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'licao_gestao','licao_gestao');
		$sql->esqUnir('licao','licao', 'licao_id=licao_gestao_licao');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=licao_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('licao_ativa=1');
		$sql->adOnde('licao_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'pratica_gestao');
		$sql->esqUnir('praticas','praticas', 'pratica_id=pratica_gestao_pratica');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=pratica_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('pratica_ativa=1');
		$sql->adOnde('pratica_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tr_gestao');
		$sql->esqUnir('tr','tr', 'tr_id=tr_gestao_tr');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=tr_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('tr_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'brainstorm_gestao');
		$sql->esqUnir('brainstorm','brainstorm', 'brainstorm_id=brainstorm_gestao_brainstorm');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=brainstorm_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('brainstorm_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'gut_gestao');
		$sql->esqUnir('gut','gut', 'gut_id=gut_gestao_gut');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=gut_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('gut_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'causa_efeito_gestao');
		$sql->esqUnir('causa_efeito','causa_efeito', 'causa_efeito_id=causa_efeito_gestao_causa_efeito');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=causa_efeito_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('causa_efeito_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tgn_gestao');
		$sql->esqUnir('tgn','tgn', 'tgn_id=tgn_gestao_tgn');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=tgn_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('tgn_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'canvas_gestao');
		$sql->esqUnir('canvas','canvas', 'canvas_id=canvas_gestao_canvas');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=canvas_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('canvas_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'mswot_gestao');
		$sql->esqUnir('mswot','mswot', 'mswot_id=mswot_gestao_mswot');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=mswot_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('mswot_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'swot_gestao');
		$sql->esqUnir('swot','swot', 'swot_id=swot_gestao_swot');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=swot_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('swot_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'operativo_gestao');
		$sql->esqUnir('operativo','operativo', 'operativo_id=operativo_gestao_operativo');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=operativo_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('operativo_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'monitoramento_gestao');
		$sql->esqUnir('monitoramento','monitoramento', 'monitoramento_id=monitoramento_gestao_monitoramento');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=monitoramento_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('monitoramento_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'avaliacao_gestao');
		$sql->esqUnir('avaliacao','avaliacao', 'avaliacao_id=avaliacao_gestao_avaliacao');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=avaliacao_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('avaliacao_ativa=1');
		$sql->adOnde('avaliacao_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'checklist_gestao');
		$sql->esqUnir('checklist','checklist', 'checklist_id=checklist_gestao_checklist');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=checklist_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('checklist_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'agenda_gestao');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=agenda_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('agenda_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'agrupamento_gestao');
		$sql->esqUnir('agrupamento','agrupamento', 'agrupamento_id=agrupamento_gestao_agrupamento');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=agrupamento_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('agrupamento_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'template_gestao');
		$sql->esqUnir('template','template', 'template_id=template_gestao_template');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=template_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('template_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'painel_gestao');
		$sql->esqUnir('painel','painel', 'painel_id=painel_gestao_painel');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=painel_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('painel_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'painel_odometro_gestao');
		$sql->esqUnir('painel_odometro','painel_odometro', 'painel_odometro_id=painel_odometro_gestao_painel_odometro');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=painel_odometro_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('painel_odometro_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'painel_composicao_gestao');
		$sql->esqUnir('painel_composicao','painel_composicao', 'painel_composicao_id=painel_composicao_gestao_painel_composicao');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=painel_composicao_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('painel_composicao_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'painel_slideshow_gestao');
		$sql->esqUnir('painel_slideshow','painel_slideshow', 'painel_slideshow_id=painel_slideshow_gestao_painel_slideshow');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=painel_slideshow_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('painel_slideshow_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'calendario_gestao');
		$sql->esqUnir('calendario','calendario', 'calendario_id=calendario_gestao_calendario');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=calendario_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('calendario_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'perspectiva_gestao');
		$sql->esqUnir('perspectivas','perspectivas', 'pg_perspectiva_id=perspectiva_gestao_perspectiva');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=perspectiva_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('perspectiva_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'tema_gestao');
		$sql->esqUnir('tema','tema', 'tema_id=tema_gestao_tema');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=tema_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('tema_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'objetivo_gestao');
		$sql->esqUnir('objetivo','objetivo', 'objetivo_id=objetivo_gestao_objetivo');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=objetivo_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('objetivo_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'me_gestao');
		$sql->esqUnir('me','me', 'me_id=me_gestao_me');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=me_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('me_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'fator_gestao');
		$sql->esqUnir('fator','fator', 'fator_id=fator_gestao_fator');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=fator_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('fator_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'estrategia_gestao');
		$sql->esqUnir('estrategias','estrategias', 'pg_estrategia_id=estrategia_gestao_estrategia');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=estrategia_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('estrategia_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'meta_gestao');
		$sql->esqUnir('metas','metas', 'pg_meta_id=meta_gestao_meta');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=meta_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('meta_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_gestao_gestao');
		$sql->esqUnir('plano_gestao','plano_gestao', 'pg_id=plano_gestao_gestao_plano_gestao');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=plano_gestao_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('plano_gestao_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_abertura_gestao');
		$sql->esqUnir('projeto_abertura','projeto_abertura', 'projeto_abertura_id=projeto_abertura_gestao_projeto_abertura');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=projeto_abertura_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('projeto_abertura_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_viabilidade_gestao');
		$sql->esqUnir('projeto_viabilidade','projeto_viabilidade', 'projeto_viabilidade_id=projeto_viabilidade_gestao_projeto_viabilidade');
		$sql->esqUnir('tarefas','tarefas', 'tarefa_id=projeto_viabilidade_gestao_tarefa');
		if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
		$sql->adOnde('projeto_viabilidade_gestao_projeto='.(int)$projeto_id.' OR tarefa_projeto='.(int)$projeto_id);
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
		$sql->adOnde('ssti_gestao_projeto = '.(int)$projeto_id);
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
		$sql->adOnde('laudo_gestao_projeto = '.(int)$projeto_id);
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
		$sql->adOnde('trelo_gestao_projeto = '.(int)$projeto_id);
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
		$sql->adOnde('trelo_cartao_gestao_projeto = '.(int)$projeto_id);
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
		$sql->adOnde('pdcl_gestao_projeto = '.(int)$projeto_id);
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
		$sql->adOnde('pdcl_item_gestao_projeto = '.(int)$projeto_id);
		$sql->adOnde('pdcl_item_gestao_pdcl_item IS NOT NULL');
		$sql->adCampo('count(pdcl_item_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/pdcl/pdcl_item_tabela', ucfirst($config['pdcl_item']),null,null,ucfirst($config['pdcl_item']),'Visualizar '.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.');
			}
		}	

	if ($qnt_aba) $caixaTab->mostrar('','','','',true);

 
      echo '</div>';
      }
	

if (!$imprimir_detalhe) echo estiloFundoCaixa();

function isSerialized($str){
	return ($str == serialize(false) || @unserialize($str) !== false);
	}

?>

<script type="text/JavaScript">

function gerar_demanda(projeto_id){
	if(confirm('Tem certeza que deseja criar o Documento de Oficialização da Demanda, a Análise de Viabilidade do Projeto e o Termo de Abertura do Projeto?')){
		url_passar(0, 'm=projetos&a=menu_anexos&gerar=1&projeto_id='+projeto_id); 
		}
	}


var novasDes = '<?php echo $alertaDes?>';
if(novasDes) alert(novasDes);

var financeiro_carregado=0;

function exibir_financeiro(){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
	
	if (document.getElementById('ver_financeiro').style.display) document.getElementById('ver_financeiro').style.display='';
	else document.getElementById('ver_financeiro').style.display='none';
	
	if (!financeiro_carregado) {
		
		document.getElementById('combo_financeiro').innerHTML='Processando dados';
		
		xajax_exibir_financeiro(document.getElementById('projeto_id').value, baseline_id);
		__buildTooltip();
		}

	financeiro_carregado=1;
	}

function exportar_link(tipo) {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link&dialogo=1&tipo='+tipo+'&id='+document.getElementById('projeto_id').value, null, window);
	}


function status_pro(){
	window.parent.gpwebApp.popUp("Status dos Documentos", 500, 500, 'm=projetos&u=eb&a=status&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, status_retorno_pro, window);
	}

function status_retorno_pro(endereco){
	url_passar(0,endereco);
	}

function brainstorm(brainstorm_id){
	if(window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Brainstorm", 1024, 600, 'm=praticas&a=brainstorm&dialogo=1&sem_impressao=1&brainstorm_id='+brainstorm_id+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=praticas&a=brainstorm&dialogo=1&sem_impressao=1&brainstorm_id='+brainstorm_id+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'brainstorm','height=500,width=1024,resizable,scrollbars=yes');
	}

function causa_efeito(causa_efeito_id){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Causa Efeito", 1024, 600, 'm=praticas&a=causa_efeito&dialogo=1&sem_impressao=1&causa_efeito_id='+causa_efeito_id+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=praticas&a=causa_efeito&dialogo=1&sem_impressao=1&causa_efeito_id='+causa_efeito_id+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Causa Efeito','height=500,width=1024,resizable,scrollbars=yes');
	}

function gut(gut_id){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Matriz GUT", 1024, 600, 'm=praticas&a=gut&dialogo=1&sem_impressao=1&gut_id='+gut_id+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=praticas&a=gut&dialogo=1&sem_impressao=1&gut_id='+gut_id+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Matriz GUT','height=500,width=1024,resizable,scrollbars=yes');
	}

function canvas(canvas_id){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1024, 800, 'm=praticas&a=canvas_pro_ver&dialogo=1&sem_impressao=1&canvas_id='+canvas_id+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=praticas&a=canvas_pro_ver&dialogo=1&sem_impressao=1&canvas_id='+canvas_id+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Matriz GUT','height=500,width=1024,resizable,scrollbars=yes');
	}

function pagamento(tipo){
	if(window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Pagamentos", 1024, 600, 'm=tarefas&a=planilha_pagamento_pro&dialogo=1&'+tipo+'=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=tarefas&a=planilha_pagamento_pro&dialogo=1&'+tipo+'=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Planilha', 'height=500,width=1024,resizable,scrollbars=yes');
	}

function planilha_gasto_mo(financeiro){
  if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Planilha de Mão de Obra", 1024, 600, 'm=projetos&a=planilha_mao_obra&dialogo=1&financeiro='+financeiro+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=projetos&a=planilha_mao_obra&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}

function planilha_gasto_recurso(financeiro){
  if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Planilha de Recursos", 1024, 600, 'm=projetos&a=planilha_recurso&dialogo=1&financeiro='+financeiro+'&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=projetos&a=planilha_recurso&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}


function planilha_custo_recurso(){
  if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Planilha de Recursos", 1024, 600, 'm=tarefas&a=lista_recursos&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=tarefas&a=lista_recursos&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}


function planilha_custo_final(tipo, financeiro){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Planilha", 1024, 600, 'm=projetos&a=planilha&dialogo=1&financeiro='+financeiro+'&projeto_id='+document.getElementById('projeto_id').value+'&tipo='+tipo+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=projetos&a=planilha&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&tipo='+tipo+'&baseline_id='+document.getElementById('baseline_id').value, 'Planilha', 'height=500,width=1024,resizable,scrollbars=yes');
	}


function planilha_projeto_custo(){
  if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp('Planilha', 1024, 600, 'm=projetos&a=planilha_projeto_custo&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, null, window);
	else window.open('./index.php?m=projetos&a=planilha_projeto_custo&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}



function imprimir(){
	url_passar(Math.floor(Math.random()*1000), 'm=projetos&a=imprimir_selecionar&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value);
	}


function mudar_baseline(){
	url_passar(0, 'm=projetos&a=ver&tab=<?php echo $tab ?>&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value);
	}

function menu_anexos(){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Menu dos Artefatos", 500, 400, 'm=projetos&a=menu_anexos&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, window.url_passar, window);
	else window.open('./index.php?m=projetos&a=menu_anexos&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&baseline_id='+document.getElementById('baseline_id').value, 'Menu dos Artefatos','height=400,width=500px,resizable,scrollbars=yes');
	}

function popCoordenadas(latitude, longitude, projeto_area_id, projeto_id, tarefa_id){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Ver Coordenada",  770, 467, 'm=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : '')+(projeto_area_id ? '&projeto_area_id='+projeto_area_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : '')+(projeto_area_id ? '&projeto_area_id='+projeto_area_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), 'Ver Coordenada','height=467,width=770px,resizable,scrollbars=no');
	}

function popAreaMunicipio(municipio_id, projeto_id, tarefa_id){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Município", 770, 467, 'm=publico&a=coordenadas_municipios&dialogo=1'+(municipio_id ? '&municipio_id='+municipio_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas_municipios&dialogo=1'+(municipio_id ? '&municipio_id='+municipio_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), 'Município','height=467,width=770px,resizable,scrollbars=no');
	}


function expandir_colapsar_item(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function expandir_colapsar(id,tabelaNome,option,opt_nivel,root){
	var expandir=(option=='expandir'?1:0);
	var colapsar=(option=='colapsar'?1:0);
	var nivel=(opt_nivel==0?0:(opt_nivel>0?opt_nivel:-1));
	var include_root=(root?root:0);var done=false;
	var encontrado=false;var trs=document.getElementsByTagName('tr');
	for(var i=0;i<trs.length;i++){
		var tr_nome=trs.item(i).id;
		if((tr_nome.indexOf(id)>=0)&&nivel<0){
			var tr=document.getElementById(tr_nome);
			if(colapsar||expandir){
				if(colapsar){
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="none";
						img_expandir.style.display="inline";
						}
					else{
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="none";
						img_expandir.style.display="inline";
						}
					}
				else{
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="visible";
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
						img_colapsar.style.display="inline";
						img_expandir.style.display="none";
						}
				else{
					tr.style.display="";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
					img_colapsar.style.display="inline";
					img_expandir.style.display="none";
					}
				}
			}
		else {
			if(navigator.family=="gecko"||navigator.family=="opera"){
				tr.style.visibility=(tr.style.visibility==''||tr.style.visibility=="colapsar") ? "visible":"colapsar";
				tr.style.display=(tr.style.display=="none")? "" : "none";
				var img_expandir=document.getElementById(tr_nome+'_expandir');
				var img_colapsar=document.getElementById(tr_nome+'_colapsar');
				if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
				if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
				img_colapsar.style.display=(tr.style.visibility=='visible') ? "inline" : "none";
				img_expandir.style.display=(tr.style.visibility==''||tr.style.visibility=="colapsar")?"inline":"none";
				}
			else{
				tr.style.display=(tr.style.display=="none")?"":"none";
				var img_expandir=document.getElementById(tr_nome+'_expandir');
				var img_colapsar=document.getElementById(tr_nome+'_colapsar');
				if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
				if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
				img_colapsar.style.display=(tr.style.display=='')?"inline":"none";
				img_expandir.style.display=(tr.style.display=='none')?"inline":"none";
				}
			}
		}
		else if((tr_nome.indexOf(id)>=0)&&nivel>=0&&!done&&!encontrado&&!include_root){
			encontrado=true;
			var tr=document.getElementById(tr_nome);
			var img_expandir=document.getElementById(tr_nome+'_expandir');
			var img_colapsar=document.getElementById(tr_nome+'_colapsar');
			if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
			if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
			if(!(img_colapsar==null)) img_colapsar.style.display=(img_colapsar.style.display=='none')?"inline":"none";
			if(!(img_expandir==null)){
				img_expandir.style.display=(img_expandir.style.display=='none')?"inline":"none";
				opt=(img_expandir.style.display=="inline")?"colapsar":"expandir";
				colapsar=(opt=='colapsar'?1:0);expandir=(opt=='expandir'?1:0);
				}
			}
		else if((tr_nome.indexOf(id)>=0)&&nivel>=0&&include_root){
			encontrado=true;
			var tr=document.getElementById(tr_nome);
			nivel_atual=parseInt(tr_nome.substr(tr_nome.indexOf('>')+1,tr_nome.indexOf('<')-tr_nome.indexOf('>')-1));
			if(colapsar){
				if(navigator.family=="gecko"||navigator.family=="opera"){
					if((include_root==1&&nivel==0)||(nivel_atual>0)){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						}
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null)) img_colapsar.style.display="none";
					if(!(img_expandir==null)) img_expandir.style.display="inline";
					}
				else{
					if((include_root==1&&nivel==0)||(nivel_atual>0)) tr.style.display="none";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null))	img_colapsar.style.display="none";
					if(!(img_expandir==null))	img_expandir.style.display="inline";
					}


				}
			else{
				if(navigator.family=="gecko"||navigator.family=="opera"){
					if((include_root==1&&nivel==0)||(nivel_atual>0)) tr.style.visibility="visible";tr.style.display="";
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null) var img_colapsar=document.getElementById(id+'_colapsar');
					if(!(img_colapsar==null))	img_colapsar.style.display="inline";
					if(!(img_expandir==null))	img_expandir.style.display="none";
					}
			else{
				if((include_root==1&&nivel==0)||(nivel_atual>0)){
					tr.style.display=""}
					var img_expandir=document.getElementById(tr_nome+'_expandir');
					var img_colapsar=document.getElementById(tr_nome+'_colapsar');
					if(img_expandir==null) var img_expandir=document.getElementById(id+'_expandir');
					if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
					if(!(img_colapsar==null)){img_colapsar.style.display="inline"}
					if(!(img_expandir==null)){img_expandir.style.display="none"}
					}
				}
			}
		else if(nivel>0&&!done&&(encontrado||nivel==0)){
			nivel_atual=parseInt(tr_nome.substr(tr_nome.indexOf('>')+1,tr_nome.indexOf('<')-tr_nome.indexOf('>')-1));
			if(nivel_atual<nivel){
				done=true;
				return;
				}
			else{
				var tr=document.getElementById(tr_nome);
				if(colapsar){
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="colapsar";
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null)var img_expandir=document.getElementById(id+'_expandir');
						if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
						if(!(img_colapsar==null)){img_colapsar.style.display="none"}
						if(!(img_expandir==null)){img_expandir.style.display="inline"}
						}
					else{
						tr.style.display="none";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){var img_expandir=document.getElementById(id+'_expandir')}
						if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
						if(!(img_colapsar==null)){img_colapsar.style.display="none"}
						if(!(img_expandir==null)){img_expandir.style.display="inline"}
						}
					}
				else{
					if(navigator.family=="gecko"||navigator.family=="opera"){
						tr.style.visibility="visible";
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){var img_expandir=document.getElementById(id+'_expandir')}
						if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
						if(!(img_colapsar==null)){img_colapsar.style.display="inline"}
						if(!(img_expandir==null)){img_expandir.style.display="none"}
						}
					else{
						tr.style.display="";
						var img_expandir=document.getElementById(tr_nome+'_expandir');
						var img_colapsar=document.getElementById(tr_nome+'_colapsar');
						if(img_expandir==null){var img_expandir=document.getElementById(id+'_expandir')}
						if(img_colapsar==null){var img_colapsar=document.getElementById(id+'_colapsar')}
						if(!(img_colapsar==null)){img_colapsar.style.display="inline"}
						if(!(img_expandir==null)){img_expandir.style.display="none"}
						}
					}
				}
			}
		}
	}


function expandir_multiprojeto(id, tabelaNome){
  var trs = document.getElementsByTagName('tr');
  for (var i=0, i_cmp=trs.length;i < i_cmp;i++){
    var tr_nome = trs.item(i).id;
    if (tr_nome.indexOf(id) >= 0){
     	var tr = document.getElementById(tr_nome);
     	tr.style.visibility = (tr.style.visibility == '' || tr.style.visibility == 'colapsar') ? 'visible' : 'colapsar';
     	var img_expandir = document.getElementById(id+'_expandir');
     	var img_colapsar = document.getElementById(id+'_colapsar');
     	img_colapsar.style.display = (tr.style.visibility == 'visible') ? 'inline' : 'none';
     	img_expandir.style.display = (tr.style.visibility == '' || tr.style.visibility == 'colapsar') ? 'inline' : 'none';
			}
		}
	}

function excluir(){
	if (confirm('Tem certeza que deseja excluir <?php echo $config["genero_projeto"]." ".$config["projeto"]?>?')) document.frmExcluir.submit();
	}

function recalcularProjeto(){
    var projeto_id = document.getElementById('projeto_id').value;
    if(!projeto_id) return;
    parent.Ext.getBody().mask('Recalculando o projeto...');
    parent.Ext.defer(function(){
        this.xajax_recalcular_projeto_pro(projeto_id);
        this.parent.Ext.getBody().unmask();
        this.location.reload();
    }, 10, window);

}
function clonar(){
	var nome_projeto = prompt("Nome d<?php echo $config['genero_projeto'].' '.$config['projeto'] ?>:","");
  
  xajax_projeto_existe(nome_projeto, document.getElementById('cia_id').value);
 
  if (nome_projeto && document.getElementById("existe_projeto").value==0){
      parent.gpwebApp.popUp('Clonar', 1000, 700, 'm=projetos&a=clonar_pro&dialogo=1&projeto_id='+document.getElementById('projeto_id').value+'&nome_projeto='+nome_projeto, window.setClone, window);
      }
  else if (document.getElementById('existe_projeto').value > 0) alert("Já existe <?php echo $config['projeto'].' com este nome.'?>");
  else alert('Escreva um nome válido.');

	}

function setClone(projeto_id){
	alert('Clonagem realizada');
	url_passar(0, 'm=projetos&a=ver&projeto_id='+projeto_id);
	}

function atualizarLinks(projeto, visualizar){
	window.parent.gpwebApp.tarefasDesatualizadasAjax(projeto, !visualizar, function(){
    url_passar(false, 'm=projetos&a=ver&projeto_id='+projeto);
  }, this);
}

function popImportarKML(){
	parent.gpwebApp.popUp('Importar Área', 1024, 500, 'm=projetos&a=editar_poligono_pro&dialogo=1&uuid=&projeto_id='+<?php echo $projeto_id ?>, null, window);
}

function popEditarPoligono() {
	parent.gpwebApp.editarAreaProjeto(<?php echo $projeto_id ?>);
}

function onResizeDetalhesProjeto(){
    if(window.resizeGrid) window.resizeGrid();
    if(window.resizeGanttPanelEx) window.resizeGanttPanelEx();
}

<?php if ($dialogo) echo 'exibir_financeiro();';?>
</script>
