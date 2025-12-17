<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$salvar=getParam($_REQUEST, 'salvar', 0);
$salvaranexo=getParam($_REQUEST, 'salvaranexo', 0);
$excluiranexo=getParam($_REQUEST, 'excluiranexo', 0);
$plano_gestao_arquivo_id=getParam($_REQUEST, 'plano_gestao_arquivo_id', 0);

$sql = new BDConsulta;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerGestaoTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerGestaoTab') !== null ? $Aplic->getEstado('VerGestaoTab') : 0;


if (isset($_REQUEST['gestao_pagina'])) $Aplic->setEstado('gestao_pagina', getParam($_REQUEST, 'gestao_pagina', null));
$gestao_pagina = ($Aplic->getEstado('gestao_pagina') !== null ? $Aplic->getEstado('gestao_pagina') : 'inicial');

if (isset($_REQUEST['editarPG'])) $Aplic->setEstado('editarPG', getParam($_REQUEST, 'editarPG', null));
$editarPG = ($Aplic->getEstado('editarPG') !== null ? $Aplic->getEstado('editarPG') : 0);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : null;
if ($dept_id) $ver_subordinadas = null;

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if (isset($_REQUEST['ver_dept_subordinados'])) $Aplic->setEstado('ver_dept_subordinados', getParam($_REQUEST, 'ver_dept_subordinados', null));
$ver_dept_subordinados = ($Aplic->getEstado('ver_dept_subordinados') !== null ? $Aplic->getEstado('ver_dept_subordinados') : (($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) ? $Aplic->usuario_prefs['ver_dept_subordinados'] : 0));
if ($ver_subordinadas) $ver_dept_subordinados=0;

$lista_depts='';
if ($ver_dept_subordinados){
	$vetor_depts=array();
	lista_depts_subordinados($dept_id, $vetor_depts);
	$vetor_depts[]=$dept_id;
	$lista_depts=implode(',',$vetor_depts);
	}

$pg_id=getParam($_REQUEST, 'pg_id', 0);

require_once (BASE_DIR.'/modulos/praticas/gestao/gestao.class.php');
$obj = new CGestao();
$obj->load($pg_id);

if($pg_id &&!(permiteAcessarPlanoGestao($obj->pg_acesso,$pg_id) && $Aplic->checarModulo('praticas', 'acesso', $Aplic->usuario_id, 'planejamento'))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'planejamento');
$podeAdicionar=$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'planejamento');
$podeExcluir=$Aplic->checarModulo('praticas', 'excluir', $Aplic->usuario_id, 'planejamento');

$editar=permiteEditarPlanoGestao($obj->pg_acesso, $pg_id);

$podeEditarTudo=($obj->pg_acesso>=5 ? $editar && (in_array($obj->pg_usuario, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'planejamento\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();



$sql->adTabela('assinatura');
$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
$sql->adOnde('assinatura_plano_gestao='.(int)$pg_id);
$assinar = $sql->linha();
$sql->limpar();

//tem assinatura que aprova
$sql->adTabela('assinatura');
$sql->adCampo('count(assinatura_id)');
$sql->adOnde('assinatura_aprova=1');
$sql->adOnde('assinatura_plano_gestao='.(int)$pg_id);
$tem_aprovacao = $sql->resultado();
$sql->limpar();


$usuarios_selecionados=array();
$depts_selecionados=array();
if ($pg_id) {
	$sql->adTabela('plano_gestao_usuario');
	$sql->adCampo('plano_gestao_usuario_usuario');
	$sql->adOnde('plano_gestao_usuario_plano = '.(int)$pg_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('plano_gestao_dept');
	$sql->adCampo('plano_gestao_dept_dept');
	$sql->adOnde('plano_gestao_dept_plano ='.(int)$pg_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}


echo '<form name="env" id="env" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="menu" />';
echo '<input type="hidden" name="u" value="gestao" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="pg_id" id="pg_id" value="'.$pg_id.'" />';
echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$obj->pg_cia.'" />';
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input name="gestao_pagina" type="hidden" value="'.$gestao_pagina.'" />';






if (!$dialogo){
	$Aplic->salvarPosicao();
	
	$botoesTitulo = new CBlocoTitulo('Planejamento Estratégico', 'planogestao.png', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/planogestao_p.png').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';
	$botoesTitulo->mostrar();
	}



require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";
	
$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;

$root = $arvore->getRootNode();
$root->image="xpMyDoc.gif";

if ($exibir['perfil']) $arvore->Add("root","orgperfil","Organização-Perfil",true,"","");
if ($exibir['estrutura'] && $exibir['perfil']) $arvore->Add("orgperfil","EstruturaOrganizacional","<a href='javascript:void(0);' onclick='carregar(\"estrutura_organizacional\");'>Estrutura Organizacional</a>",true,"ball_glass_redS.gif","");
if ($exibir['fornecedores'] && $exibir['perfil']) $arvore->Add("orgperfil","FornecedoreseInsumos_geral","<a href='javascript:void(0);' onclick='carregar(\"fornecedores_insumos_geral\");'>Fornecedores e Insumos</a>",true,"ball_glass_redS.gif","");
if ($exibir['fornecedores'] && $exibir['perfil']) $arvore->Add("FornecedoreseInsumos_geral","FornecedoreseInsumos","<a href='javascript:void(0);' onclick='carregar(\"fornecedores_insumos\");'>Lista de Fornecedores e Insumos</a>",true,"square_redS.gif","");
if ($exibir['processos'] && $exibir['perfil']) $arvore->Add("orgperfil","ProcessosProdutosServicos","<a href='javascript:void(0);' onclick='carregar(\"processos_produtos_servicos\");'>Processos e Produtos/Serviços</a>",true,"ball_glass_redS.gif","");
if ($exibir['clientes'] && $exibir['perfil']) $arvore->Add("orgperfil","Clientes","<a href='javascript:void(0);' onclick='carregar(\"clientes\");'>Clientes/Usuários</a>",true,"ball_glass_redS.gif","");
if ($exibir['pessoal'] && $exibir['perfil']) $arvore->Add("orgperfil","QuadrodePessoal_geral","<a href='javascript:void(0);' onclick='carregar(\"quadropessoal_geral\");'>Quadro de Pessoal</a>",true,"ball_glass_redS.gif","");
if ($exibir['pessoal'] && $exibir['perfil']) $arvore->Add("QuadrodePessoal_geral","QuadrodePessoal","<a href='javascript:void(0);' onclick='carregar(\"quadropessoal\");'>Lista de Pessoal</a>",true,"square_redS.gif","");
if ($exibir['programa'] && $exibir['perfil']) $arvore->Add("orgperfil","ProgramaseAcoes","<a href='javascript:void(0);' onclick='carregar(\"programasacoes\");'>Programas e Ações</a>",true,"ball_glass_redS.gif","");
if ($exibir['premiacao'] && $exibir['perfil']) $arvore->Add("orgperfil","PremiacaoemGestao_geral","<a href='javascript:void(0);' onclick='carregar(\"premiacoes_geral\");'>Premiação em Gestão</a>",true,"ball_glass_redS.gif","");
if ($exibir['premiacao'] && $exibir['perfil']) $arvore->Add("PremiacaoemGestao_geral","PremiacaoemGestao","<a href='javascript:void(0);' onclick='carregar(\"premiacoes\");'>Lista de Premiações em Gestão</a>",true,"square_redS.gif","");

$arvore->Add("root","planodegestao",'Planejamento e Gestão',true,"","");	
$arvore->Add("planodegestao","Missao","<a href='javascript:void(0);' onclick='carregar(\"missao\");'>Missão</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("planodegestao","visaofuturo","<a href='javascript:void(0);' onclick='carregar(\"visaofuturo\");'>Visão de Futuro</a>",true,"ball_glass_blueS.gif","");

if($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento_swot')){
	$arvore->Add("planodegestao","DiagEstra","<a href='javascript:void(0);' onclick='carregar(\"swot\");'>Diagnóstico Estratégico (SWOT)</a>",true,"ball_glass_blueS.gif","");
	$arvore->Add("DiagEstra","AmbInterno","<a href='javascript:void(0);' onclick='carregar(\"swot\");'>Ambiente Interno</a>",true,"square_blueS.gif","");
	$arvore->Add("AmbInterno","AmbInternoPF_geral","<a href='javascript:void(0);' onclick='carregar(\"ponto_forte_geral\");'>Forças</a>",true,"triangle_blueS.gif","");
	$arvore->Add("AmbInternoPF_geral","AmbInternoPF","<a href='javascript:void(0);' onclick='carregar(\"ponto_forte\");'>Lista de Forças</a>",true,"losangulo_azul.gif","");
	$arvore->Add("AmbInterno","AmbInternoOIM_geral","<a href='javascript:void(0);' onclick='carregar(\"oportunidade_melhoria_geral\");'>Fraquezas</a>",true,"triangle_blueS.gif","");
	$arvore->Add("AmbInternoOIM_geral","AmbInternoOIM","<a href='javascript:void(0);' onclick='carregar(\"oportunidade_melhoria\");'>Lista de Fraquezas</a>",true,"losangulo_azul.gif","");
	$arvore->Add("DiagEstra","AmbExterno","<a href='javascript:void(0);' onclick='carregar(\"swot\");'>Ambiente Externo</a>",true,"square_blueS.gif","");
	$arvore->Add("AmbExterno","AmbExternoOport_geral","<a href='javascript:void(0);' onclick='carregar(\"oportunidades_geral\");'>Oportunidades</a>",true,"triangle_blueS.gif","");
	$arvore->Add("AmbExternoOport_geral","AmbExternoOport","<a href='javascript:void(0);' onclick='carregar(\"oportunidades\");'>Lista de Oportunidades</a>",true,"losangulo_azul.gif","");
	$arvore->Add("AmbExterno","AmbExternoAmeaca_geral","<a href='javascript:void(0);' onclick='carregar(\"ameacas_geral\");'>Ameaças</a>",true,"triangle_blueS.gif","");
	$arvore->Add("AmbExternoAmeaca_geral","AmbExternoAmeaca","<a href='javascript:void(0);' onclick='carregar(\"ameacas\");'>Lista de Ameaças</a>",true,"losangulo_azul.gif","");
	}
$arvore->Add("planodegestao","princCrenVal","<a href='javascript:void(0);' onclick='carregar(\"principios\");'>Princípios, Crenças e Valores</a>",true,"ball_glass_blueS.gif","");

$arvore->Add("planodegestao","dirEscSup_geral","<a href='javascript:void(0);' onclick='carregar(\"diretrizes_superiores_geral\");'>Diretrizes do Escalão Superior</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("dirEscSup_geral","dirEscSup","<a href='javascript:void(0);' onclick='carregar(\"diretrizes_superiores\");'>Lista de Diretrizes do Escalão Superior</a>",true,"square_blueS.gif","");


$arvore->Add("planodegestao","dirCmt_geral","<a href='javascript:void(0);' onclick='carregar(\"diretrizes_geral\");'>Diretrizes Internas</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("dirCmt_geral","dirCmt","<a href='javascript:void(0);' onclick='carregar(\"diretrizes\");'>Lista de Diretrizes Internas</a>",true,"square_blueS.gif","");

$arvore->Add("planodegestao","perspectiva","<a href='javascript:void(0);' onclick='carregar(\"perspectivas\");'>".ucfirst($config['perspectivas'])."</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("planodegestao","tema","<a href='javascript:void(0);' onclick='carregar(\"temas\");'>".ucfirst($config['temas'])."</a>",true,"ball_glass_blueS.gif","");

$arvore->Add("planodegestao","objEstOrg_geral","<a href='javascript:void(0);' onclick='carregar(\"objetivo_geral\");'>".ucfirst($config['objetivos'])."</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("objEstOrg_geral","objEstOrg","<a href='javascript:void(0);' onclick='carregar(\"objetivo\");'>Lista de ".ucfirst($config['objetivos'])."</a>",true,"square_blueS.gif","");

if($Aplic->profissional && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'acesso', null, 'me')){
	$arvore->Add("planodegestao","me","<a href='javascript:void(0);' onclick='carregar(\"mes_pro\");'>".ucfirst($config['mes'])."</a>",true,"ball_glass_blueS.gif","");
	}
if(!$Aplic->profissional || ($Aplic->profissional && $Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'acesso', null, 'fator'))){
	$arvore->Add("planodegestao","fatCritSuc_geral","<a href='javascript:void(0);' onclick='carregar(\"fator_geral\");'>".ucfirst($config['fatores'])."</a>",true,"ball_glass_blueS.gif","");
	$arvore->Add("fatCritSuc_geral","fatCritSuc","<a href='javascript:void(0);' onclick='carregar(\"fator\");'>Lista de ".ucfirst($config['fatores'])."</a>",true,"square_blueS.gif","");
	}
$arvore->Add("planodegestao","estrat_geral","<a href='javascript:void(0);' onclick='carregar(\"estrategias_geral\");'>".ucfirst($config['iniciativas'])."</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("estrat_geral","estrat","<a href='javascript:void(0);' onclick='carregar(\"estrategias\");'>Lista de ".ucfirst($config['iniciativas'])."</a>",true,"square_blueS.gif","");

$arvore->Add("planodegestao","metasOrg_geral","<a href='javascript:void(0);' onclick='carregar(\"metas_geral\");'>".ucfirst($config['metas'])."</a>",true,"ball_glass_blueS.gif","");
$arvore->Add("metasOrg_geral","metasOrg","<a href='javascript:void(0);' onclick='carregar(\"metas\");'>Lista de ".ucfirst($config['metas'])."</a>",true,"square_blueS.gif","");

echo estiloTopoCaixa();


if (!$dialogo){	

	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_pg",dica('Lista de Planejamentos Estratégicos','Visualizar a lista de todos os planejamentos estratégicos.').'Lista de Planejamentos Estratégicos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_lista\");");


	if (($podeEditar && $editar) || $podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar)	$km->Add("inserir","inserir_objeto",dica('Novo Planejamento Estratégico', 'Criar um novo planejamento estratégico.').'Novo Planejamento Estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar\");");
	if ($podeEditar && $editar) {
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar&pg_id=".$pg_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) $km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_plano_gestao=".$pg_id."\");");	
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'demanda')) $km->Add("inserir","inserir_demanda",dica('Nova Demanda', 'Inserir uma nova demanda relacionada.').'Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar&demanda_plano_gestao=".$pg_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_plano_gestao=".$pg_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		$km->Add("inserir","diverso",dica('Diversos','Menu de objetos diversos').'Diversos'.dicaF(), "javascript: void(0);'");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("diverso","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("diverso","inserir_forum",dica('Novo Fórum', 'Inserir um novo fórum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("diverso","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("diverso","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'resposta_risco')) $km->Add("diverso","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'adicionar', null, null)) $km->Add("diverso","inserir_instrumento",dica('Nov'.$config['genero_instrumento'].' '.ucfirst($config['instrumento']), 'Inserir um'.($config['genero_instrumento']=='a' ? 'a' : '').' nov'.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.').ucfirst($config['instrumento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=instrumento&a=instrumento_editar&instrumento_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('recursos', 'adicionar', null, null)) $km->Add("diverso","inserir_recurso",dica('Nov'.$config['genero_recurso'].' '.ucfirst($config['recurso']), 'Inserir um'.($config['genero_recurso']=='a' ? 'a' : '').' nov'.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.').ucfirst($config['recurso']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=recursos&a=editar&recurso_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'adicionar', null, null)) $km->Add("diverso","inserir_patrocinador",dica('Nov'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Inserir '.($config['genero_patrocinador']=='o' ? 'um' : 'uma').' nov'.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.').ucfirst($config['patrocinador']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=patrocinadores&a=patrocinador_editar&patrocinador_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'programa')) $km->Add("diverso","inserir_programa",dica('Nov'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Inserir um'.($config['genero_programa']=='a' ? 'a' : '').' nov'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.').ucfirst($config['programa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=programa_pro_editar&programa_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('projetos', 'adicionar', null, 'licao')) $km->Add("diverso","inserir_licao",dica('Nov'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Inserir um'.($config['genero_licao']=='a' ? 'a' : '').' nov'.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.').ucfirst($config['licao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=licao_editar&licao_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'pratica')) $km->Add("diverso","inserir_pratica",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Inserir um'.($config['genero_pratica']=='a' ? 'a' : '').' nov'.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.').ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'adicionar', null, null)) $km->Add("diverso","inserir_tr",dica('Nov'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Inserir um'.($config['genero_tr']=='a' ? 'a' : '').' nov'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_editar&tr_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'brainstorm')) $km->Add("diverso","inserir_brainstorm",dica('Novo Brainstorm', 'Inserir um novo brainstorm relacionado.').'Brainstorm'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=brainstorm_editar&brainstorm_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'gut')) $km->Add("diverso","inserir_gut",dica('Nova Matriz GUT', 'Inserir uma nova matriz GUT relacionado.').'Matriz GUT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=gut_editar&gut_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'causa_efeito')) $km->Add("diverso","inserir_causa_efeito",dica('Novo Diagrama de Causa-Efeito', 'Inserir um novo Diagrama de causa-efeito relacionado.').'Diagrama de causa-efeito'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=causa_efeito_editar&causa_efeito_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'tgn')) $km->Add("diverso","inserir_tgn",dica('Nov'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Inserir um'.($config['genero_tgn']=='a' ? 'a' : '').' nov'.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.').ucfirst($config['tgn']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tgn_pro_editar&tgn_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'canvas')) $km->Add("diverso","inserir_canvas",dica('Nov'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Inserir um'.($config['genero_canvas']=='a' ? 'a' : '').' nov'.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.').ucfirst($config['canvas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=canvas_pro_editar&canvas_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'adicionar', null, null)) {
			$km->Add("diverso","inserir_mswot",dica('Nova Matriz SWOT', 'Inserir uma nova matriz SWOT relacionada.').'Matriz SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=mswot_editar&mswot_plano_gestao=".$pg_id."\");");
			$km->Add("diverso","inserir_swot",dica('Novo Campo SWOT', 'Inserir um novo campo SWOT relacionado.').'Campo SWOT'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=swot&a=swot_editar&swot_plano_gestao=".$pg_id."\");");
			}
		if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'adicionar', null, null)) $km->Add("diverso","inserir_operativo",dica('Novo Plano Operativo', 'Inserir um novo plano operativo relacionado.').'Plano operativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=operativo&a=operativo_editar&operativo_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'monitoramento')) $km->Add("diverso","inserir_monitoramento",dica('Novo monitoramento', 'Inserir um novo monitoramento relacionado.').'Monitoramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=monitoramento_editar_pro&monitoramento_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'avaliacao_indicador')) $km->Add("diverso","inserir_avaliacao",dica('Nova Avaliação', 'Inserir uma nova avaliação relacionada.').'Avaliação'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=avaliacao_editar&avaliacao_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'checklist')) $km->Add("diverso","inserir_checklist",dica('Novo Checklist', 'Inserir um novo checklist relacionado.').'Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar&checklist_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional) $km->Add("diverso","inserir_agenda",dica('Novo Compromisso', 'Inserir um novo compromisso relacionado.').'Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso&agenda_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'adicionar', null, null)) $km->Add("diverso","inserir_agrupamento",dica('Novo Agrupamento', 'Inserir um novo Agrupamento relacionado.').'Agrupamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=agrupamento&a=agrupamento_editar&agrupamento_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'modelo')) $km->Add("diverso","inserir_template",dica('Novo Modelo', 'Inserir um novo modelo relacionado.').'Modelo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=template_pro_editar&template_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'painel_indicador')) $km->Add("diverso","inserir_painel",dica('Novo Painel de Indicador', 'Inserir um novo painel de indicador relacionado.').'Painel de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_pro_editar&painel_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'odometro_indicador')) $km->Add("diverso","inserir_painel_odometro",dica('Novo Odômetro de Indicador', 'Inserir um novo odômetro de indicador relacionado.').'Odômetro de indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=odometro_pro_editar&painel_odometro_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'adicionar', null, 'composicao_painel')) $km->Add("diverso","inserir_painel_composicao",dica('Nova Composição de Painéis', 'Inserir uma nova composição de painéis relacionada.').'Composição de painéis'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_composicao_pro_editar&painel_composicao_plano_gestao=".$pg_id."\");");
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'ssti')) $km->Add("diverso","inserir_ssti",dica('Nov'.$config['genero_ssti'].' '.ucfirst($config['ssti']), 'Inserir um'.($config['genero_ssti']=='a' ? 'a' : '').' nov'.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.').ucfirst($config['ssti']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=ssti_editar&ssti_plano_gestao=".$pg_id."\");");
			if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'adicionar', null, 'laudo')) $km->Add("diverso","inserir_laudo",dica('Nov'.$config['genero_laudo'].' '.ucfirst($config['laudo']), 'Inserir um'.($config['genero_laudo']=='a' ? 'a' : '').' nov'.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.').ucfirst($config['laudo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=ssti&a=laudo_editar&laudo_plano_gestao=".$pg_id."\");");
			if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'adicionar', null, null)) {
				$km->Add("diverso","inserir_trelo",dica('Nov'.$config['genero_trelo'].' '.ucfirst($config['trelo']), 'Inserir um'.($config['genero_trelo']=='a' ? 'a' : '').' nov'.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.').ucfirst($config['trelo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_editar&trelo_plano_gestao=".$pg_id."\");");
				$km->Add("diverso","inserir_trelo_cartao",dica('Nov'.$config['genero_trelo_cartao'].' '.ucfirst($config['trelo_cartao']), 'Inserir um'.($config['genero_trelo_cartao']=='a' ? 'a' : '').' nov'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.').ucfirst($config['trelo_cartao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=trelo&a=trelo_cartao_editar&trelo_cartao_plano_gestao=".$pg_id."\");");
				}
			if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'adicionar', null, null)) {
				$km->Add("diverso","inserir_pdcl",dica('Nov'.$config['genero_pdcl'].' '.ucfirst($config['pdcl']), 'Inserir um'.($config['genero_pdcl']=='a' ? 'a' : '').' nov'.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.').ucfirst($config['pdcl']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_editar&pdcl_plano_gestao=".$pg_id."\");");
				$km->Add("diverso","inserir_pdcl_item",dica('Nov'.$config['genero_pdcl_item'].' '.ucfirst($config['pdcl_item']), 'Inserir um'.($config['genero_pdcl_item']=='a' ? 'a' : '').' nov'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.').ucfirst($config['pdcl_item']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=pdcl&a=pdcl_item_editar&pdcl_item_plano_gestao=".$pg_id."\");");
				}
		if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'adicionar', null, 'slideshow_painel')) $km->Add("diverso","inserir_painel_slideshow",dica('Novo Slideshow de Composições', 'Inserir um novo slideshow de composições relacionado.').'Slideshow de composições'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=painel_slideshow_pro_editar&painel_slideshow_plano_gestao=".$pg_id."\");");
		$km->Add("inserir","gestao1",dica('Gestao','Menu de objetos de gestão').'Gestao'.dicaF(), "javascript: void(0);'");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'perspectiva')) $km->Add("gestao1","inserir_perspectiva",dica('Nov'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Inserir um'.($config['genero_perspectiva']=='a' ? 'a' : '').' nov'.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.').ucfirst($config['perspectiva']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=perspectiva_editar&perspectiva_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'tema')) $km->Add("gestao1","inserir_tema",dica('Nov'.$config['genero_tema'].' '.ucfirst($config['tema']), 'Inserir um'.($config['genero_tema']=='a' ? 'a' : '').' nov'.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.').ucfirst($config['tema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=tema_editar&tema_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'objetivo')) $km->Add("gestao1","inserir_objetivo",dica('Nov'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']), 'Inserir um'.($config['genero_objetivo']=='a' ? 'a' : '').' nov'.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.').ucfirst($config['objetivo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=obj_estrategico_editar&objetivo_plano_gestao=".$pg_id."\");");
		if ($Aplic->profissional && isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $km->Add("gestao1","inserir_me",dica('Nov'.$config['genero_me'].' '.ucfirst($config['me']), 'Inserir um'.($config['genero_me']=='a' ? 'a' : '').' nov'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=me_editar_pro&me_plano_gestao=".$pg_id."\");");
		if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator')) $km->Add("gestao1","inserir_fator",dica('Nov'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Inserir um'.($config['genero_fator']=='a' ? 'a' : '').' nov'.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.').ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar&fator_plano_gestao=".$pg_id."\");"); 
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'iniciativa')) $km->Add("gestao1","inserir_iniciativa",dica('Nov'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Inserir um'.($config['genero_iniciativa']=='a' ? 'a' : '').' nov'.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.').ucfirst($config['iniciativa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=estrategia_editar&estrategia_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'meta')) $km->Add("gestao1","inserir_meta",dica('Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Inserir um'.($config['genero_meta']=='a' ? 'a' : '').' nov'.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.').ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar&meta_plano_gestao=".$pg_id."\");");
		if ($Aplic->checarModulo('praticas', 'adicionar', null, 'planejamento')) $km->Add("gestao1","inserir_plano_gestao",dica('Novo Planejamento estratégico', 'Inserir um novo planejamento estratégico relacionado.').'Planejamento estratégico'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar&plano_gestao_plano_gestao=".$pg_id."\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	
	$bloquear=($obj->pg_aprovado && $config['trava_aprovacao'] && $tem_aprovacao && !$Aplic->usuario_super_admin);
	if (isset($assinar['assinatura_id']) && $assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=assinatura&a=assinatura_assinar&pg_id=".$pg_id."\");"); 
	
	if ($podeEditarTudo && $podeEditar && !$bloquear) {
		$km->Add("acao","acao_editar",dica('Editar Detalhameto','Clique neste ícone '.imagem('editar.gif').' para editar o detalhamento deste planejamento estratégico.').imagem('editar.gif').'Editar Detalhameto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&u=gestao&a=gestao_editar&pg_id=".(int)$pg_id."\");");
		if ($editarPG) $km->Add("acao","acao_editar",dica('Cancelar Edição de Tópicos','Clique neste ícone '.imagem('planogestao_cancelar_editar.png').' para cancelar a Edição dos tópicos deste planejamento estratégico.').imagem('planogestao_cancelar_editar.png').' Cancelar Edição de Tópicos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=menu&u=gestao&editarPG=0&pg_id=".(int)$pg_id."\");");
		elseif($pg_id) $km->Add("acao","acao_editar",dica('Editar Tópicos','Clique neste ícone '.imagem('planogestao_editar.png').' para editar os tópicos deste planejamento estratégico.').imagem('planogestao_editar.png').' Editar Tópicos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=menu&u=gestao&editarPG=1&pg_id=".(int)$pg_id."\");");
		}
	if ($podeEditarTudo && $podeExcluir && !$bloquear) $km->Add("acao","acao_excluir",dica('Excluir','Clique neste ícone '.imagem('remover.png').' para excluir o planejamento estratégico do sistema.').imagem('remover.png').' Excluir Planejamento Estratégico'.dicaF(), "javascript: void(0);' onclick='excluir()");	
	

	if ($Aplic->profissional) {
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir decumentos do planejamento estratégico.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='imprimir();");
		//$km->Add("acao_imprimir","dashboard_geral",dica('Grau de Consecução','Dashboard do grau de consecução do planejamento estratégico.').'Grau de Consecução'.dicaF(), "javascript: void(0);' onclick='url_passar(\"dashboard_geral_".$pg_id."\", \"m=praticas&u=gestao&a=gestao_deshboard_geral_pro&jquery=1&dialogo=1&pg_id=".$pg_id."\");");
		$km->Add("acao_imprimir","dashboard_geral",dica('Grau de Consecução','Dashboard do grau de consecução do planejamento estratégico.').'Grau de Consecução'.dicaF(), "javascript: void(0);' onclick='selecionar_data(\"gestao_deshboard_geral_pro\", ".$pg_id.");");
		
		//$km->Add("acao_imprimir","objetivo_tarefa",dica('Status de '.ucfirst($config['tarefas']).' por '.ucfirst($config['objetivos']),'Quadro de status de '.$config['tarefa'].' por '.$config['objetivos'].'.').'Status de '.ucfirst($config['tarefas']).' por '.ucfirst($config['objetivos']).dicaF(), "javascript: void(0);' onclick='url_passar(\"gestao_relatorio_objetivo_tarefa_geral_".$pg_id."\", \"m=praticas&u=gestao&a=gestao_relatorio_objetivo_tarefa_pro&dialogo=1&pg_id=".$pg_id."\");");
		$km->Add("acao_imprimir","objetivo_tarefa",dica('Status de '.ucfirst($config['tarefas']).' por '.ucfirst($config['objetivos']),'Quadro de status de '.$config['tarefa'].' por '.$config['objetivos'].'.').'Status de '.ucfirst($config['tarefas']).' por '.ucfirst($config['objetivos']).dicaF(), "javascript: void(0);' onclick='selecionar_data(\"gestao_relatorio_objetivo_tarefa_pro\", ".$pg_id.");");
		
		//$km->Add("acao_imprimir","dashboard_consecucao_objetivos",dica('Grau de Consecução por '.ucfirst($config['objetivos']),'Dashboard do grau de consecução por '.$config['objetivos'].'.').'Grau de Consecução por '.ucfirst($config['objetivos']).dicaF(), "javascript: void(0);' onclick='url_passar(\"dashboard_consecucao_objetivos_".$pg_id."\", \"m=praticas&u=gestao&a=gestao_deshboard_consecucao_objetivos_pro&jquery=1&dialogo=1&pg_id=".$pg_id."\");");
		$km->Add("acao_imprimir","dashboard_consecucao_objetivos",dica('Grau de Consecução por '.ucfirst($config['objetivos']),'Dashboard do grau de consecução por '.$config['objetivos'].'.').'Grau de Consecução por '.ucfirst($config['objetivos']).dicaF(), "javascript: void(0);' onclick='selecionar_data(\"gestao_deshboard_consecucao_objetivos_pro\", ".$pg_id.");");

		//$km->Add("acao_imprimir","relatorio_acompanhamento",dica('Relatório de Acompanhamento','O relatório de acompanhamento do planejamrnto estratégico e '.$config['objetivos'].'.').'Relatório de Acompanhamento'.dicaF(), "javascript: void(0);' onclick='url_passar(\"relatorio_acompanhamento_".$pg_id."\", \"m=praticas&u=gestao&a=gestao_relatorio_acompanhamento_pro&jquery=1&dialogo=1&pg_id=".$pg_id."\");");
		$km->Add("acao_imprimir","relatorio_acompanhamento",dica('Relatório de Acompanhamento','O relatório de acompanhamento do planejamrnto estratégico e '.$config['objetivos'].'.').'Relatório de Acompanhamento'.dicaF(), "javascript: void(0);' onclick='selecionar_data(\"gestao_relatorio_acompanhamento_pro\", ".$pg_id.");");
		}
	
	
	
	
	
	
	
	echo $km->Render();
	echo '</td></tr></table>';
	}



echo '<table id="tblPraticas" border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';



echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'detalhamento\').style.display) document.getElementById(\'detalhamento\').style.display=\'\'; else document.getElementById(\'detalhamento\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Detalhamento</b></a></td></tr>';
echo '<tr id="detalhamento" style="display:none"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';



echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome', 'Neste campo consta um nome para identificação.').'Nome:'.dicaF().'</td><td align="left" class="realce">'.$obj->pg_nome.'</td></tr>';
if ($obj->pg_cia) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável pelo plano de gestão.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->pg_cia).'</td></tr>';


if ($Aplic->profissional){
	$sql->adTabela('plano_gestao_cia');
	$sql->adCampo('plano_gestao_cia_cia');
	$sql->adOnde('plano_gestao_cia_plano = '.(int)$pg_id);
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





if ($obj->pg_dept) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->pg_dept).'</td></tr>';
$saida_depts='';
if ($depts_selecionados && count($depts_selecionados)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].' com este link.').ucfirst($config['departamento']).' envolvid'.$config['genero_dept'].':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';
if ($obj->pg_usuario) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável por gerenciar.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->pg_usuario, '','','esquerda').'</td></tr>';		
$saida_quem='';
if ($usuarios_selecionados && count($usuarios_selecionados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($usuarios_selecionados[0], '','','esquerda');
		$qnt_usuarios_selecionados=count($usuarios_selecionados);
		if ($qnt_usuarios_selecionados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_usuarios_selecionados; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i], '','','esquerda').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais usuarios_selecionados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'usuarios_selecionados\');">(+'.($qnt_usuarios_selecionados - 1).')</a>'.dicaF(). '<span style="display: none" id="usuarios_selecionados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';
$link_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nível de Acesso', 'Pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designado podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável os designados podem ver e editar</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" class="realce">'.$link_acesso[$obj->pg_acesso].'</td></tr>';
if ($obj->pg_descricao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Descrição', 'Um texto explicativo para facilitar a compreensão e facilitar futuras pesquisas.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->pg_descricao.'</td>';


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data Inicial', 'Digite ou escolha no calendário a data de início.').'De:'.dicaF().'</td><td align="left" class="realce">'.retorna_data($obj->pg_inicio, false).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data Final', 'Digite ou escolha no calendário a data final.').'Até:'.dicaF().'</td><td align="left" class="realce">'.retorna_data($obj->pg_fim, false).'</td></tr>';



$sql->adTabela('plano_gestao_gestao');
$sql->adCampo('plano_gestao_gestao.*');
$sql->adOnde('plano_gestao_gestao_plano_gestao ='.(int)$pg_id);	
$sql->adOrdem('plano_gestao_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
$qnt_gestao=0;

if (count($lista)) {
	echo '<tr><td align="right" style="white-space: nowrap" valign="middle">'.dica('Relacionad'.$config['genero_plano_gestao'], 'A que área '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].' está relacionad'.$config['genero_plano_gestao'].'.').'Relacionad'.$config['genero_plano_gestao'].':'.dicaF().'</td></td><td class="realce">';	
	foreach($lista as $gestao_data){
		if ($gestao_data['plano_gestao_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['plano_gestao_gestao_tarefa']);
		elseif ($gestao_data['plano_gestao_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['plano_gestao_gestao_projeto']);
		elseif ($gestao_data['plano_gestao_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['plano_gestao_gestao_pratica']);
		elseif ($gestao_data['plano_gestao_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['plano_gestao_gestao_acao']);
		elseif ($gestao_data['plano_gestao_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['plano_gestao_gestao_perspectiva']);
		elseif ($gestao_data['plano_gestao_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['plano_gestao_gestao_tema']);
		elseif ($gestao_data['plano_gestao_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['plano_gestao_gestao_objetivo']);
		elseif ($gestao_data['plano_gestao_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['plano_gestao_gestao_fator']);
		elseif ($gestao_data['plano_gestao_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['plano_gestao_gestao_estrategia']);
		elseif ($gestao_data['plano_gestao_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['plano_gestao_gestao_meta']);
		elseif ($gestao_data['plano_gestao_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['plano_gestao_gestao_canvas']);
		elseif ($gestao_data['plano_gestao_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['plano_gestao_gestao_risco']);
		elseif ($gestao_data['plano_gestao_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['plano_gestao_gestao_risco_resposta']);
		elseif ($gestao_data['plano_gestao_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['plano_gestao_gestao_indicador']);
		elseif ($gestao_data['plano_gestao_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['plano_gestao_gestao_calendario']);
		elseif ($gestao_data['plano_gestao_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['plano_gestao_gestao_monitoramento']);
		elseif ($gestao_data['plano_gestao_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['plano_gestao_gestao_ata']);
		elseif ($gestao_data['plano_gestao_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['plano_gestao_gestao_mswot']);
		elseif ($gestao_data['plano_gestao_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['plano_gestao_gestao_swot']);
		elseif ($gestao_data['plano_gestao_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['plano_gestao_gestao_operativo']);
		elseif ($gestao_data['plano_gestao_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['plano_gestao_gestao_instrumento']);
		elseif ($gestao_data['plano_gestao_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['plano_gestao_gestao_recurso']);
		elseif ($gestao_data['plano_gestao_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['plano_gestao_gestao_problema']);
		elseif ($gestao_data['plano_gestao_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['plano_gestao_gestao_demanda']);	
		elseif ($gestao_data['plano_gestao_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['plano_gestao_gestao_programa']);
		elseif ($gestao_data['plano_gestao_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['plano_gestao_gestao_licao']);
		elseif ($gestao_data['plano_gestao_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['plano_gestao_gestao_evento']);
		elseif ($gestao_data['plano_gestao_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['plano_gestao_gestao_link']);
		elseif ($gestao_data['plano_gestao_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['plano_gestao_gestao_avaliacao']);
		elseif ($gestao_data['plano_gestao_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['plano_gestao_gestao_tgn']);
		elseif ($gestao_data['plano_gestao_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['plano_gestao_gestao_brainstorm']);
		elseif ($gestao_data['plano_gestao_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['plano_gestao_gestao_gut']);
		elseif ($gestao_data['plano_gestao_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['plano_gestao_gestao_causa_efeito']);
		elseif ($gestao_data['plano_gestao_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['plano_gestao_gestao_arquivo']);
		elseif ($gestao_data['plano_gestao_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['plano_gestao_gestao_forum']);
		elseif ($gestao_data['plano_gestao_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['plano_gestao_gestao_checklist']);
		elseif ($gestao_data['plano_gestao_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['plano_gestao_gestao_agenda']);
		elseif ($gestao_data['plano_gestao_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['plano_gestao_gestao_agrupamento']);
		elseif ($gestao_data['plano_gestao_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['plano_gestao_gestao_patrocinador']);
		elseif ($gestao_data['plano_gestao_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['plano_gestao_gestao_template']);
		elseif ($gestao_data['plano_gestao_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['plano_gestao_gestao_painel']);
		elseif ($gestao_data['plano_gestao_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['plano_gestao_gestao_painel_odometro']);
		elseif ($gestao_data['plano_gestao_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['plano_gestao_gestao_painel_composicao']);		
		elseif ($gestao_data['plano_gestao_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['plano_gestao_gestao_tr']);	
		elseif ($gestao_data['plano_gestao_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['plano_gestao_gestao_me']);	
		elseif ($gestao_data['plano_gestao_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['plano_gestao_gestao_acao_item']);	
		elseif ($gestao_data['plano_gestao_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['plano_gestao_gestao_beneficio']);	
		elseif ($gestao_data['plano_gestao_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['plano_gestao_gestao_painel_slideshow']);	
		elseif ($gestao_data['plano_gestao_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['plano_gestao_gestao_projeto_viabilidade']);	
		elseif ($gestao_data['plano_gestao_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['plano_gestao_gestao_projeto_abertura']);	
		
		elseif ($gestao_data['plano_gestao_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['plano_gestao_gestao_semelhante']);	
		
		elseif ($gestao_data['plano_gestao_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['plano_gestao_gestao_ssti']);
		elseif ($gestao_data['plano_gestao_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['plano_gestao_gestao_laudo']);
		elseif ($gestao_data['plano_gestao_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['plano_gestao_gestao_trelo']);
		elseif ($gestao_data['plano_gestao_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['plano_gestao_gestao_trelo_cartao']);
		elseif ($gestao_data['plano_gestao_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['plano_gestao_gestao_pdcl']);
		elseif ($gestao_data['plano_gestao_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['plano_gestao_gestao_pdcl_item']);
		}
	echo '</td></tr>';
	}	



if ($Aplic->profissional){
	$tipo_pontuacao=array(
		''=>'Manual',
		'media_ponderada'=>'Média ponderada das percentagens dos objetos relacionados',
		'pontos_completos'=>'Pontos dos objetos relacionados desde que com percentagens completas',
		'pontos_parcial'=>'Pontos dos objetos relacionados mesmo com percentagens incompletas',
		'indicador'=>'Indicador principal'
		);
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Sistema de Percentagem', 'Qual forma a percentagem d'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].' é calculada.').'Sistema de percentagem:'.dicaF().'</td><td class="realce">'.(isset($tipo_pontuacao[$obj->pg_tipo_pontuacao]) ? $tipo_pontuacao[$obj->pg_tipo_pontuacao] : '').'</td></tr>';
	if ($obj->pg_tipo_pontuacao && $obj->pg_tipo_pontuacao!='indicador'){
		$sql->adTabela('plano_gestao_media');
		$sql->adOnde('plano_gestao_media_plano_gestao = '.(int)$pg_id);
		$sql->adOnde('plano_gestao_media_tipo = \''.$obj->pg_tipo_pontuacao.'\'');
		$sql->adCampo('plano_gestao_media.*');
		$sql->adOrdem('plano_gestao_media_ordem');
		$medias=$sql->Lista();
		$sql->limpar();
		
		$tipo=$obj->pg_tipo_pontuacao;
		$inserir_pontuacao=(($tipo=='pontos_completos' || $tipo=='pontos_parcial') ? true : false);
		$inserir_peso=($tipo=='media_ponderada' ? true : false);

		if (is_array($medias) && count($medias)){
			echo '<tr><td align="right" style="white-space: nowrap"></td><td><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr><th>Objeto</th>'.($inserir_peso ? '<th>Peso</th>' : '').($inserir_pontuacao ? '<th>Pontuação</th>' : '').'</tr>';
			foreach ($medias as $contato_id => $media) {
				echo '<tr align="center">';
				if ($media['plano_gestao_media_projeto']) echo '<td align="left">'.imagem('icones/projeto_p.gif').link_projeto($media['plano_gestao_media_projeto']).'</td>';
				elseif ($media['plano_gestao_media_acao']) echo '<td align="left">'.imagem('icones/plano_acao_p.gif').link_acao($media['plano_gestao_media_acao']).'</td>';
				elseif ($media['plano_gestao_media_perspectiva']) echo '<td align="left">'.imagem('icones/perspectiva_p.png').link_perspectiva($media['plano_gestao_media_perspectiva']).'</td>';
				if ($inserir_peso) echo '<td align="right">'.number_format($media['plano_gestao_media_peso'], 2, ',', '.').'</td>';
				if ($inserir_pontuacao) echo '<td align="right">'.number_format($media['plano_gestao_media_ponto'], 2, ',', '.').'</td>';
				echo '</tr>';
				}
			echo '</table></td></tr>';
			}
		}
		
	if ($obj->pg_tipo_pontuacao=='pontos_completos' || $obj->pg_tipo_pontuacao=='pontos_parcial') echo '<tr><td align=right>'.dica('Pontuação Alvo', 'A pontuação necessária da soma das filhas para que '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].' fique com progresso em 100%.').'Pontuação Alvo:'.dicaF().'</td><td class="realce">'.number_format((float)$obj->pg_ponto_alvo, 2, ',', '.').'</td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Progresso', 'O progresso pode ir de 0% (não iniciado) até 100% (completado).').'Progresso:'.dicaF().'</td><td class="realce">'.number_format((float)$obj->pg_percentagem, 2, ',', '.').'%</td></tr>';
	
	if($obj->pg_tipo_pontuacao){
	
		$fisico_previsto=$obj->fisico_previsto(date('Y-m-d H:i:s'));
		echo '<tr><td align="right" style="white-space: nowrap">'.dica('Físico Previsto', 'O físico previsto pode ir de 0% (não iniciado) até 100% (completado).').'Físico previsto:'.dicaF().'</td><td class="realce">'.number_format((float)$fisico_previsto, 2, ',', '.').'%</td></tr>';
		
		$velocidade_fisico=($fisico_previsto > 0 ? $obj->pg_percentagem/$fisico_previsto : 0);
		echo '<tr><td align="right" style="white-space: nowrap">'.dica('Velocidade do Físico', 'A velocidade do físico de forma normalizada.').'Velocidade do físico:'.dicaF().'</td><td class="realce">'.number_format((float)$velocidade_fisico, 2, ',', '.').'</td></tr>';
		}
		
	}





if ($Aplic->profissional && $tem_aprovacao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Aprovado', 'Se o planejamento estratégico se encontra aprovado.').'Aprovado:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->pg_aprovado ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';

if ($Aplic->profissional) {
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	if (isset($moedas[$obj->pg_moeda])) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'A moeda padrão utilizada.').'Moeda:'.dicaF().'</td><td class="realce" width="100%">'.$moedas[$obj->pg_moeda].'</td></tr>';	
	}


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ativo', 'Se o planejamento estratégico se encontra ativo.').'Ativo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->pg_ativo ? 'Sim' : 'Não').'</td></tr>';











$sql->adTabela('assinatura');
$sql->adOnde('assinatura_plano_gestao = '.(int)$pg_id);
$sql->adCampo('assinatura_id, assinatura_funcao, assinatura_atesta, assinatura_aprova, assinatura_usuario, assinatura_ordem');
$sql->adOrdem('assinatura_ordem');
$assinaturas=$sql->Lista();
$sql->limpar();
if (is_array($assinaturas) && count($assinaturas)) {
	$sql->adTabela('assinatura_atesta');
	$sql->adCampo('assinatura_atesta_id, assinatura_atesta_nome');
	$sql->adOnde('assinatura_atesta_plano_gestao=1');
	$sql->adOrdem('assinatura_atesta_ordem');
	$atesta_vetor = array(null=>'')+$sql->listaVetorChave('assinatura_atesta_id', 'assinatura_atesta_nome');
	$sql->limpar();
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_assinaturas\').style.display) document.getElementById(\'apresentar_assinaturas\').style.display=\'\'; else document.getElementById(\'apresentar_assinaturas\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Assinam</b></a></td></tr>';
	echo '<tr id="apresentar_assinaturas" style="display:'.(!$dialogo ? 'none' : '').'"><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td colspan=20 align=left>';
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th>'.dica(ucfirst($config['usuario']), ucfirst($config['genero_usuario']).' '.$config['usuario'].' relacionad'.$config['genero_usuario'].'.').ucfirst($config['usuario']).dicaF().'</th><th>Função</th><th>Tipo de Parecer</th><th>Aprova</th></tr>';
	foreach ($assinaturas as $integrante) {
		echo '<tr align="center">';
		echo '<td align="left" style="white-space: nowrap">'.link_usuario($integrante['assinatura_usuario'], '','','esquerda').'</td>';
		echo '<td align="left">'.$integrante['assinatura_funcao'].'</td>';
		echo '<td align="left">'.(isset($atesta_vetor[$integrante['assinatura_atesta']]) ? $atesta_vetor[$integrante['assinatura_atesta']] : '&nbsp;').'</td>';
		echo '<td align="center">'.($integrante['assinatura_aprova'] > 0 ? 'Sim' : 'Não').'</td>';
		echo '</tr>';
		}
	echo '</table>';
	echo '</td></tr>';
	echo '</table></td></tr>';
	}


$sql->adTabela('assinatura');
$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
$sql->adOnde('assinatura_plano_gestao = '.(int)$pg_id);
$sql->adOnde('assinatura_data IS NOT NULL');
$sql->adCampo('assinatura_funcao, assinatura_atesta_opcao_nome, assinatura_aprovou, assinatura_usuario, formatar_data(assinatura_data, \'%d/%m/%Y\') AS data, assinatura_observacao, assinatura_data');
$sql->adCampo('assinatura_atesta_opcao, assinatura_aprova, assinatura_atesta_opcao_aprova, assinatura_atesta');
$sql->adOrdem('assinatura_ordem');
$assinaturas=$sql->Lista();
$sql->limpar();
if (is_array($assinaturas) && count($assinaturas)) {
	echo '<tr><td style="height:1px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_assinados\').style.display) document.getElementById(\'apresentar_assinados\').style.display=\'\'; else document.getElementById(\'apresentar_assinados\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Assinaram</b></a></td></tr>';
	echo '<tr id="apresentar_assinados" style="display:'.(!$dialogo ? 'none' : '').'"><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td colspan=20 align=left>';
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left>';
	foreach ($assinaturas as $integrante) {
		echo '<tr><td align=center>';
		if ($integrante['assinatura_atesta_opcao_nome']) echo $integrante['assinatura_atesta_opcao_nome'].'</br></br>';
		echo link_usuario($integrante['assinatura_usuario']).'</br>';
		echo $integrante['assinatura_funcao'].'</br>';
		$reprova=false;
		if ($integrante['assinatura_aprova']==1 && $integrante['assinatura_atesta_opcao']>0 && $integrante['assinatura_atesta_opcao_aprova']!=1) $reprova=true;
		if ($integrante['assinatura_aprova']==1 && !$integrante['assinatura_atesta'] && (!$integrante['assinatura_data'] || ($integrante['assinatura_data'] && !$integrante['assinatura_aprovou']))) $reprova=true;
		if ($integrante['assinatura_aprova']==1 && $integrante['assinatura_atesta']>0 && !$integrante['assinatura_atesta_opcao']) $reprova=true;
		echo ($reprova ? '<span style="color:#ff0000">' : '').$integrante['data'].($reprova ? '</span>' : '').'</br>';
		if ($integrante['assinatura_observacao']) echo '</br>'.$integrante['assinatura_observacao'].'</br>';
		echo '</td></tr>';
		}
	echo '</table>';
	echo '</td></tr>';
	echo '</table></td></tr>';
	}

if ($Aplic->profissional && isset($exibir['priorizacao']) && $exibir['priorizacao']){
	//Carregar respostas
	$sql->adTabela('priorizacao');
	$sql->adCampo('priorizacao_modelo, priorizacao_valor');
	$sql->adOnde('priorizacao_plano_gestao = '.(int)$pg_id);
	$priorizacao=$sql->listaVetorChave('priorizacao_modelo', 'priorizacao_valor');
	$sql->limpar();
	
	$tem_priorizacao=false;
	foreach($priorizacao as $chave => $valor) if ($valor!=null) $tem_priorizacao=true;
	
	if (count($priorizacao) && $tem_priorizacao){
		echo '<tr><td style="height:1px;"></td></tr>';
		echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_priorizacoes\').style.display) document.getElementById(\'apresentar_priorizacoes\').style.display=\'\'; else document.getElementById(\'apresentar_priorizacoes\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Priorização</b></a></td></tr>';
		echo '<tr id="apresentar_priorizacoes" style="display:none"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';
		
		//carregar as questões
		$sql->adTabela('priorizacao_modelo');
		$sql->adCampo('priorizacao_modelo_id, priorizacao_modelo_nome, priorizacao_modelo_tipo, priorizacao_modelo_descricao');
		$sql->adOnde('priorizacao_modelo_plano_gestao = 1');
		$sql->adOrdem('priorizacao_modelo_ordem');
		$priorizacoes=$sql->lista();
		$sql->limpar();

		foreach($priorizacoes as $linha){
			if ($linha['priorizacao_modelo_tipo']!='lista' || ($linha['priorizacao_modelo_tipo']=='lista' && isset($priorizacao[$linha['priorizacao_modelo_id']]) && $priorizacao[$linha['priorizacao_modelo_id']]!=null)){
				echo '<tr><td align=right style="white-space: nowrap">'.dica($linha['priorizacao_modelo_nome'], $linha['priorizacao_modelo_descricao']).$linha['priorizacao_modelo_nome'].dicaF().':</td><td class="realce" width="100%">';
				if ($linha['priorizacao_modelo_tipo']=='lista'){
					$sql->adTabela('priorizacao_modelo_opcao');
					$sql->adCampo('priorizacao_modelo_opcao_valor, priorizacao_modelo_opcao_nome');
					$sql->adOnde('priorizacao_modelo_opcao_modelo = '.(int)$linha['priorizacao_modelo_id']);
					$sql->adOrdem('priorizacao_modelo_opcao_ordem');
					$vetor=array(''=>'')+$sql->listaVetorChave('priorizacao_modelo_opcao_valor', 'priorizacao_modelo_opcao_nome');
					$sql->limpar();
					echo (isset($vetor[(isset($priorizacao[$linha['priorizacao_modelo_id']]) ? $priorizacao[$linha['priorizacao_modelo_id']] : null)]) ? $vetor[(isset($priorizacao[$linha['priorizacao_modelo_id']]) ? $priorizacao[$linha['priorizacao_modelo_id']] : null)] :'&nbsp;');
					}
				elseif ($linha['priorizacao_modelo_tipo']=='valor'){
					echo (isset($priorizacao[$linha['priorizacao_modelo_id']]) ? $priorizacao[$linha['priorizacao_modelo_id']] : '&nbsp;');
					}
				elseif ($linha['priorizacao_modelo_tipo']=='check'){
					$vetor=array(''=>'', 0=>'Não', 100=>'Sim');
					echo (isset($vetor[(isset($priorizacao[$linha['priorizacao_modelo_id']]) ? $priorizacao[$linha['priorizacao_modelo_id']] : null)]) ? $vetor[(isset($priorizacao[$linha['priorizacao_modelo_id']]) ? $priorizacao[$linha['priorizacao_modelo_id']] : null)] : '&nbsp;');
					}
				echo '</td></tr>';
				}
			}	
		echo '</table></td></tr>';
		}
	}	













echo '</table></td></tr>';




echo '<tr>';
echo '<td style="background:#FFFFFF;" valign="top"><div style="padding:10px;">'.$arvore->Render().'</div></td>';
echo '<td width="100%" valign="top">';

include_once BASE_DIR.'/modulos/praticas/gestao/'.$gestao_pagina.'.php';

echo '</td></tr></table>';
echo '</form>';
echo estiloFundoCaixa();





if (!$dialogo){
	$caixaTab = new CTabBox('m=praticas&u=gestao&a=menu&pg_id='.$obj->pg_id, '', $tab);
	$qnt_aba=0;
	
	
	
	if ($Aplic->checarModulo('log', 'acesso')) {
		$sql->adTabela('log');
		$sql->adCampo('count(log_id)');
		$sql->adOnde('log_plano_gestao = '.(int)$pg_id);
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver', 'Registro',null,null,'Registro das Ocorrência','Visualizar o registro de ocorrência relacionado.');
			}
		}

	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {

			$sql->adTabela('evento_gestao','evento_gestao');
			$sql->adOnde('evento_gestao_plano_gestao = '.(int)$pg_id);
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
			$sql->adOnde('arquivo_gestao_plano_gestao = '.(int)$pg_id);
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
			$sql->adOnde('pratica_indicador_gestao_plano_gestao = '.(int)$pg_id);
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
			$sql->adOnde('plano_acao_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('plano_acao_item_gestao_plano_gestao = '.(int)$pg_id);
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
			$sql->adOnde('projeto_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('projeto_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('ata_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('demanda_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('msg_gestao_plano_gestao = '.(int)$pg_id);
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
			$sql->adOnde('modelo_gestao_plano_gestao = '.(int)$pg_id);
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
			$sql->adOnde('link_gestao_plano_gestao = '.(int)$pg_id);
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
			$sql->adOnde('forum_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('problema_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('risco_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('risco_resposta_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('instrumento_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('recurso_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('patrocinador_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('programa_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('beneficio_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('licao_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('pratica_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('tr_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('brainstorm_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('gut_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('causa_efeito_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('tgn_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('canvas_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('mswot_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('swot_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('operativo_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('monitoramento_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('avaliacao_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('checklist_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('agenda_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('agrupamento_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('template_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('painel_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('painel_odometro_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('painel_composicao_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('painel_slideshow_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('calendario_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('perspectiva_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('tema_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('objetivo_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('me_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('fator_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('estrategia_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('meta_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('plano_gestao_gestao_semelhante = '.(int)$pg_id);
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
		$sql->adOnde('projeto_abertura_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('projeto_viabilidade_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('ssti_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('laudo_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('trelo_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('trelo_cartao_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('pdcl_gestao_plano_gestao = '.(int)$pg_id);
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
		$sql->adOnde('pdcl_item_gestao_plano_gestao = '.(int)$pg_id);
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
		echo estiloFundoCaixa();
		}
	}	
?>
<script type="text/javascript">

function selecionar_data(endereco, pg_id){
	parent.gpwebApp.popUp('Selecionar Data Base', 400, 300, 'm=praticas&u=gestao&a=selecionar_data&dialogo=1&endereco='+endereco+'&pg_id='+pg_id, relatorio_data, window, true, false);
	}

function relatorio_data (data1, pg_id, endereco){
	url_passar('relatorio_'+pg_id, 'm=praticas&u=gestao&a='+endereco+'&jquery=1&dialogo=1&pg_id='+pg_id+'&data='+data1);
	}



function pg_download(plano_gestao_arquivo_id){
	url_passar(0, 'm=praticas&a=download_arquivo&sem_cabecalho=1&plano_gestao_arquivo_id='+plano_gestao_arquivo_id);
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir o planejamento estratégico?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_gestao_aed';
		f.submit();
		}
	}

function nova_gestao(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Criar Planejamento Estratégico', 700, 400, 'm=praticas&u=gestao&a=criar_pg&dialogo=1', window.criar_pg, window);
	else window.open('?m=praticas&u=gestao&a=criar_pg&dialogo=1', 'Criar Planejamento Estratégico', 'width=600, height=400, left=0, top=0, scrollbars=no, resizable=no');
	}


function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	env.submit();
	}



function criar_pg(pg_id){
	env.pg_id.value=pg_id;	
	env.submit();
	}
	
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	<?php if ($Aplic->profissional){ ?>
	env.dept_id.value=null;
	document.getElementById('combo_dept').style.display='none';
	<?php } ?>
	}	
	
function carregar(pagina){
	env.gestao_pagina.value=pagina;
	env.submit();
	}	
	

treeview.expandAll();
	
	
</script> 
	
