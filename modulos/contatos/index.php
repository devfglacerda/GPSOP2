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


if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$paises = getPais('Paises');
$filtro_adicional = '';

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ContatosTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ContatosTab') !== null ? $Aplic->getEstado('ContatosTab') : 0);


if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

if (isset($_REQUEST['onde'])) $Aplic->setEstado('onde', getParam($_REQUEST, 'onde', null));
$onde = ($Aplic->getEstado('onde') !== null ? $Aplic->getEstado('onde') : null);

if (isset($_REQUEST['modocontato'])) $Aplic->setEstado('modocontato', getParam($_REQUEST, 'modocontato', null));
$modocontato = ($Aplic->getEstado('modocontato') !== null ? $Aplic->getEstado('modocontato') : 'lista');

if (isset($_REQUEST['estado_sigla'])) $Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : '');

if (isset($_REQUEST['municipio_id'])) $Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '');

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

//para mostrar cia na primeira entrada
if (isset($_REQUEST['filtro_contato'])) $Aplic->setEstado('filtro_contato', getParam($_REQUEST, 'filtro_contato', null));
$filtro_contato = ($Aplic->getEstado('filtro_contato') !== null ? $Aplic->getEstado('filtro_contato') : null);

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : ($filtro_contato  ? '' : $Aplic->usuario_cia));

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

$pagina=getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_contatos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$ordenar=getParam($_REQUEST, 'ordenar', 'contato_nomeguerra');
$ordem=getParam($_REQUEST, 'ordem', '0');

$sql = new BDConsulta;

$exibir = array();
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'contatos\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();
if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'contatos\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}
	

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();


if (isset($_REQUEST['contatotextobusca'])) $Aplic->setEstado('contatotextobusca', getParam($_REQUEST, 'contatotextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('contatotextobusca') ? $Aplic->getEstado('contatotextobusca') : '');


$let = ":";

$sql->adTabela('contatos');
$sql->adCampo('DISTINCT UPPER(SUBSTRING(contato_nomeguerra,1,1)) as L');
$sql->adOnde('contato_privado=0 OR contato_privado IS NULL OR (contato_privado=1 AND contato_dono='.$Aplic->usuario_id.') OR contato_dono IS NULL');
if ($usuario_id) $sql->adOnde('contato_dono='.$usuario_id);
if ($lista_cias && !$usuario_id) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
else if ($cia_id && !$usuario_id) $sql->adOnde('contato_cia='.(int)$cia_id);
else if (!$usuario_id) $sql->adOnde('contato_cia IS NULL');
if ($lista_depts)  $sql->adOnde('contato_dept IN ('.$lista_depts.')');
else if ($dept_id) $sql->adOnde('contato_dept ='.(int)$dept_id);
$arr = $sql->Lista();
foreach ($arr as $L) $let .= @$L['L'];   //EUZ @ para esconder erro
$sql->limpar();



$sql->adTabela('contatos');
$sql->adCampo('count(DISTINCT contato_id)');
if ($pesquisar_texto) $sql->adOnde('(contato_nomecompleto LIKE \'%'.$pesquisar_texto.'%\' OR contato_nomeguerra LIKE \'%'.$pesquisar_texto.'%\')');
if ($onde) $sql->adOnde('UPPER(SUBSTRING(contato_nomecompleto,1,1))=\''.$onde.'\' OR UPPER(SUBSTRING(contato_nomeguerra,1,1))=\''.$onde.'\''); 
$sql->adOnde('(contato_privado=0 OR contato_privado IS NULL OR (contato_privado=1 AND contato_dono='.(int)$Aplic->usuario_id.')	OR contato_dono IS NULL)');
if ($usuario_id) $sql->adOnde('contato_dono='.(int)$usuario_id);
if ($lista_cias && !$usuario_id) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
else if ($cia_id && !$usuario_id) $sql->adOnde('contato_cia='.(int)$cia_id);
else if (!$usuario_id) $sql->adOnde('contato_cia IS NULL');
if ($lista_depts)  $sql->adOnde('contato_dept IN ('.$lista_depts.')');
else if ($dept_id) $sql->adOnde('contato_dept ='.(int)$dept_id);
if ($estado_sigla) $sql->adOnde('contato_estado = \''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('contato_cidade = \''.$municipio_id.'\'');
if ($modocontato=='lista' && $tab==0) $sql->adOnde('contato_ativo = 1');
elseif ($modocontato=='lista' && $tab==1) $sql->adOnde('contato_ativo = 0');
if ($modocontato!='lista') $sql->adOnde('contato_ativo = 1');
$xtotalregistros = $sql->Resultado();
$sql->limpar();




$sql->adTabela('contatos');
$sql->esqUnir('usuarios', 'usuarios', 'contato_id = usuario_contato');
$sql->esqUnir('municipios', 'municipios', 'contato_cidade = municipios.municipio_id');
$sql->esqUnir('municipios', 'municipios2', 'contato_natural_cidade = municipios2.municipio_id');
$sql->adCampo('municipios.municipio_nome, municipios2.municipio_nome AS cidade_natal, contatos.*, usuario_id');
if ($pesquisar_texto) $sql->adOnde('(contato_nomecompleto LIKE \'%'.$pesquisar_texto.'%\' OR contato_nomeguerra LIKE \'%'.$pesquisar_texto.'%\')');
if ($onde) $sql->adOnde('UPPER(SUBSTRING(contato_nomecompleto,1,1))=\''.$onde.'\' OR UPPER(SUBSTRING(contato_nomeguerra,1,1))=\''.$onde.'\''); 
$sql->adOnde('(contato_privado=0 OR contato_privado IS NULL OR (contato_privado=1 AND contato_dono='.(int)$Aplic->usuario_id.')	OR contato_dono IS NULL)');
if ($usuario_id) $sql->adOnde('contato_dono='.(int)$usuario_id);
if ($lista_cias && !$usuario_id) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
else if ($cia_id && !$usuario_id) $sql->adOnde('contato_cia='.(int)$cia_id);
else if (!$usuario_id) $sql->adOnde('contato_cia IS NULL');
if ($lista_depts)  $sql->adOnde('contato_dept IN ('.$lista_depts.')');
else if ($dept_id) $sql->adOnde('contato_dept ='.(int)$dept_id);
if ($estado_sigla) $sql->adOnde('contato_estado = \''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('contato_cidade = \''.$municipio_id.'\'');
if ($modocontato=='lista' && $tab==0) $sql->adOnde('contato_ativo = 1');
elseif ($modocontato=='lista' && $tab==1) $sql->adOnde('contato_ativo = 0');

if ($modocontato!='lista') $sql->adOnde('contato_ativo = 1');

if ($modocontato=='ficha') $sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
else $sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$linhas = $sql->Lista();
$sql->limpar();



$linhaLargura = 4;



$alfabeto = '<tr><td align="right">'.dica('Mostrar', 'Selecione à direita por qual letra deseja filtrar '.$config['genero_contato'].'s '.$config['contatos'].'. Serão pesquisados pelos nomes de guerra e postos/graduação.').'Mostrar:'.dicaF().' </td>';
$alfabeto .= '<td colspan=20><table cellpadding=1 cellspacing=0 border=0><tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=index&onde=\');">'.dica('Mostrar Todos', 'Mostrar tod'.$config['genero_contato'].'s '.$config['genero_contato'].'s '.$config['contatos'].'.').'Todos'.dicaF().'</a></td>';
for ($c = 65; $c < 91; $c++) {
	$cu = chr($c);
	$cell = strpos($let, $cu) > 0 ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=index&onde='.$cu.'\');">'.dica('Filtrar pela Letra '.$cu, 'Mostrar '.$config['genero_contato'].'s '.$config['contatos'].' em que o nome de guerra ou posto/gradução começem com a letra '.$cu.'.').$cu.dicaF().'</a>' : '<font color="#999999">'.$cu.'</font>';
	$alfabeto .= '<td>'.$cell.'</td>';
	}
$alfabeto .= '</tr></table></td></tr>';


$procurar_usuario='<tr><td align="right">'.dica('Responsável pel'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').'Responsável:'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$procurar_estado='<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_contato'].' '.$config['contato'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:250px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
$procurar_municipio='<tr><td align="right">'.dica('Município', 'Selecione o município d'.$config['genero_contato'].' '.$config['contato'].'.').'Município:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax( $estado_sigla, 'municipio_id', 'class="texto" style="width:250px;"', '', $municipio_id, true, false).'</div></td></tr>';






$pesquisar='<tr><td align="right">'.dica('Pesquisar', 'Pesquisar contatos que correspondam ao texto da caixa de pesquisa.').'Pesquisar:'.dicaF().'</td><td><input class="texto" type="text" name="contatotextobusca" value="'.$pesquisar_texto.'" onchange="document.filtro.submit();" style="width:250px;" /></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&contatotextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Pressione este botão para limpar a pesquisa de contatos por palavra chave').'</a></td></tr>';

echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" name="filtro_contato" id="filtro_contato" value="1" />';


if (!$dialogo){

	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['contatos']), 'contatos.png', $m, $m.'.'.$a);

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"', '&nbsp;').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\';  document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('contatos_p.png').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$novo=($podeAdicionar ? '<tr><td style="white-space: nowrap">'.dica('Nov'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Adicionar um nov'.$config['genero_contato'].' '.$config['contato'].'.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=contatos&a=editar\');" >'.imagem('contatos_novo.png').'</a>'.dicaF().'</td></tr>' : '');
	$imprimir='<tr><td>'.dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir '.$config['genero_tarefa'].'s '.$config['tarefas'].' e ata para este mês.').'<a href="javascript: void(0);" onclick ="url_passar(1,\'m=contatos&a=index&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';
	$filtrar='<tr><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td></tr>';
	
	$vetor_modo=array('lista'=>'Lista', 'ficha'=>'Ficha');
	$modo_contato='<tr><td align="right">'.dica('Modo de Exibição', 'Selecione o modo de exibição d'.$config['genero_contato'].'s '.$config['contatos'].'.').'Modo de exibição:'.dicaF().'</td><td><div id="combo_cidade">'.selecionaVetor($vetor_modo, 'modocontato', 'class="texto" style="width:250px;" size="1" onchange="mudar_modo();"', $modocontato).'</div></td></tr>';

	$botao_campos=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="popCamposExibir();">'.imagem('icones/campos_p.gif', 'Campos' , 'Clique neste ícone '.imagem('campos_p.gif').' para escolha quais campos deseja exibir.').'</a>'.dicaF().'</td></tr>' : '');

	$saida.='<tr><td valign=top><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$procurar_estado.$procurar_municipio.$modo_contato.$pesquisar.$alfabeto.'</table></td><td valign=top><table cellspacing=0 cellpadding=0>'.$filtrar.$novo.$imprimir.$botao_campos.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}

echo '</form>';
if($Aplic->profissional) $Aplic->carregarComboMultiSelecaoJS();



if ($exibir['contato_religiao'])$religiao=array(null=>'')+getSisValor('Religiao');
if ($exibir['contato_sangue']) $sangue=array(null=>'')+getSisValor('Sangue');
if ($exibir['contato_grau_instrucao']) $escolaridade=array(null=>'')+getSisValor('Escolaridade');

$ver_custo=$Aplic->checarModulo('usuarios', 'acesso', $Aplic->usuario_id, 'hora_custo');
$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($modocontato=='ficha'){
	$col=0;	
	echo estiloTopoCaixa();
	
	if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['contato']), ucfirst($config['contatos']),'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
	
	echo '<table width="100%" border=0 cellpadding="1" cellspacing=0 class="std2">';
	foreach ($linhas as $linha) {
		if ($col==$linhaLargura) {
			echo '</tr>';
			$col=0;
			}
		if ($col==0) echo '<tr>';
		$col++;
		echo '<td valign=top>';
		echo '<table width="100%" cellspacing=0 cellpadding=0 class="tbl4" style="background: #ffffff" valign=top><tr><td valign=top><table width="100%" cellspacing=0 cellpadding=0 style="background: #ffffff">';
		echo '<tr><td align=left colspan=20><table width="100%" cellspacing=0 cellpadding=0 valign=top>';
		echo '<tr><td style="white-space: nowrap; text-align:left">'.dica('Ver Detalhes d'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Clique no nome para visualizar os detalhes de '.$linha['contato_posto'].' '.$linha['contato_nomeguerra']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=ver&contato_id='.$linha['contato_id'].'\');"><b>'.$linha['contato_posto'].' '.$linha['contato_nomeguerra'].($linha['contato_arma'] && $config['militar'] < 10 ? ' - '.$linha['contato_arma'] : '').'</b></a>'.dicaF().'</td>';
		echo '<td style="white-space: nowrap;text-align:right">';
		if ($linha['usuario_id']) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=admin&a=ver_usuario&usuario_id='.$linha['usuario_id'].'\');">'.imagem('icones/usuario_mini.png', 'Detalhes d'.$config['genero_usuario'].' '.ucfirst($config['usuario']), 'Este contato também é '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].', clique neste ícone '.imagem('icones/usuario_mini.png').' para ver seus detalhes.').'</a>';
		echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=vcard_exporta&sem_cabecalho=true&contato_id='.$linha['contato_id'].'\');" >'.imagem('icones/cartao.png', 'Exportar Dados', 'Clique neste ícone '.imagem('icones/cartao.png').' para exportar o vCard deste contato.').'</a>';
		
		if ($linha['contato_pedido_atualizacao'] && (!$linha['contato_ultima_atualizacao'] || $linha['contato_ultima_atualizacao'] == 0) && $linha['contato_chave_atualizacao']) {
			$ultimo_pedido = new CData($linha['contato_pedido_atualizacao']);
			echo imagem('icones/informacao.gif', 'Aguardando Atualização', 'Aguardando pela atualização d'.$config['genero_contato'].' '.$config['contato'].'.<br><br>(Pedido em: '.$ultimo_pedido->format('%d/%m/%Y %H:%M').')');
			}
		elseif ($linha['contato_pedido_atualizacao'] && (!$linha['contato_ultima_atualizacao'] || $linha['contato_ultima_atualizacao'] == 0) && !$linha['contato_chave_atualizacao']) {
			$ultimo_pedido = new CData($linha['contato_pedido_atualizacao']);
			echo imagem('icones/log-error.gif','Não Atualizou', 'Espera muito longa pela atualização d'.$config['genero_contato'].' '.$config['contato'].'!(Pedir  '.$ultimo_pedido->format('%d/%m/%Y %H:%M').')');
			}
		elseif ($linha['contato_ultima_atualizacao'] && !$linha['contato_chave_atualizacao']) {
			$ultimo_pedido = new CData($linha['contato_ultima_atualizacao']);
			echo imagem('icones/ok.gif','Atualização d'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Atualizou em: '.$ultimo_pedido->format('%d/%m/%Y'));
			}
		
		if (($linha['contato_dono']==$Aplic->usuario_id) || $Aplic->usuario_super_admin || ($linha['usuario_id']==$Aplic->usuario_id)) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=contatos&a=editar&contato_id='.$linha['contato_id'].'\');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este contato').'</a>';
		echo '</td></tr></table></td></tr>';
		
		if ($exibir['contato_foto'] && $linha['contato_foto']) echo '<tr><td colspan2><table cellpadding=0 cellspacing=0 cellspacing=0 width="100%"><tr><td><table cellpadding=0 cellspacing=0 cellspacing=0 width="100%">';
		if ($linha['contato_cia'] && $lista_cias) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' d'.$config['genero_contato'].' '.$config['contato'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td  class="realce" style="white-space: nowrap">'.link_cia($linha['contato_cia']).'</a></td></tr>';
		if ($linha['contato_dept']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' d'.$config['genero_contato'].' '.$config['contato'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td class="realce" width="100%">'.link_secao($linha['contato_dept']).'</td></tr>';
		if ($exibir['contato_funcao'] && $linha['contato_funcao']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Cargo/Função', 'O Cargo/Função d'.$config['genero_contato'].' '.$config['contato'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Função:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['contato_funcao'].'</td></tr>';
		if ($config['militar'] < 10 && $linha['contato_arma']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Arma/Quadro/Sv', 'A Arma/Quadro/Sv d'.$config['genero_contato'].' '.$config['contato'].'.').'Arma/Quadro/Sv:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['contato_arma'].'</td></tr>';
		if ($exibir['contato_tipo'] && $linha['contato_tipo']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Tipo', 'O tipo d'.$config['genero_contato'].' '.$config['contato'].'.').'Tipo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['contato_tipo'].'</td></tr>';
		if ($exibir['contato_codigo'] && $linha['contato_codigo']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Código', 'Código d'.$config['genero_contato'].' '.$config['contato'].'.').'Código:'.dicaF().'</td><td class="realce" width="100%">'.$linha['contato_codigo'].'</td></tr>';
		if ($exibir['contato_identidade'] && $linha['contato_identidade']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Identidade', 'A identidade d'.$config['genero_contato'].' '.$config['contato'].'.').'Identidade:'.dicaF().'</td><td style="white-space: nowrap" class="realce">'.$linha['contato_identidade'].'</td></tr>';
		if ($exibir['contato_cpf'] && $linha['contato_cpf']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('CPF', 'O CPF d'.$config['genero_contato'].' '.$config['contato'].'.').'CPF:'.dicaF().'</td><td style="white-space: nowrap" class="realce">'.$linha['contato_cpf'].'</td></tr>';
		if ($exibir['contato_cnpj'] && $linha['contato_cnpj']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('CNPJ', 'O CNPJ d'.$config['genero_contato'].' '.$config['contato'].'.').'CNPJ:'.dicaF().'</td><td style="white-space: nowrap" class="realce">'.$linha['contato_cnpj'].'</td></tr>';
		if ($exibir['contato_endereco'] && $linha['contato_endereco1'] || $linha['contato_endereco2'] || $linha['contato_cidade'] || $linha['contato_estado']) echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=10>'.dica('Endereço', 'O endereço d'.$config['genero_contato'].' '.$config['contato'].'.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['contato_endereco1'].($linha['contato_endereco1'] && $linha['contato_endereco2'] ? '<br />' :'').$linha['contato_endereco2'].($linha['municipio_nome'] && ($linha['contato_endereco2'] || $linha['contato_endereco1']) ? '<br />' :'').$linha['municipio_nome'].($linha['contato_estado'] ? '-'.$linha['contato_estado'] : '').($linha['contato_cep'] ? '<br />'.$linha['contato_cep'] : '').'</td></tr>';
		if ($exibir['contato_tel'] && $linha['contato_tel']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Telefone Comercial', 'O telefone comercial d'.$config['genero_contato'].' '.$config['contato'].'.').'Tel. Com.:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['contato_tel'].'</td></tr>';
		if ($exibir['contato_tel2'] && $linha['contato_tel2']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Telefone Residencial', 'O telefone residencial d'.$config['genero_contato'].' '.$config['contato'].'.').'Tel. Res.:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['contato_tel2'].'</td></tr>';
		if ($exibir['contato_cel'] && $linha['contato_cel']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Celular', 'O celular d'.$config['genero_contato'].' '.$config['contato'].'.').'Cel.:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['contato_cel'].'</td></tr>';
		if ($exibir['contato_email'] && $linha['contato_email']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'. dica('E-mail', 'O e-mail d'.$config['genero_contato'].' '.$config['contato'].'.').'E-mail:'.dicaF().'</td><td class="realce" style="white-space: nowrap">'.link_email($linha['contato_email'], $linha['contato_id']).'</td></tr>';
		if ($exibir['contato_email2'] && $linha['contato_email2']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'. dica('E-mail Alternativo', 'O e-mail alternativo d'.$config['genero_contato'].' '.$config['contato'].'.').'E-mail alt.:'.dicaF().'</td><td class="realce" style="white-space: nowrap">'.link_email($linha['contato_email2'], $linha['contato_id']).'</td></tr>';
		if ($exibir['contato_skype'] && $linha['contato_skype']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Skype', 'A conta Skype d'.$config['genero_contato'].' '.$config['contato'].'.').'Skype:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="skype:'.$linha['contato_skype'].'?call">'.$linha['contato_skype'].'</a></td></tr>';
		if ($exibir['contato_hora_custo'] && $ver_custo && $linha['contato_hora_custo']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Custo da Hora', 'O custo da hora de trabalho d'.$config['genero_contato'].' '.$config['contato'].'.').'Custo hora:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format($linha['contato_hora_custo'], 2, ',', '.').'</td></tr>';
		if ($exibir['contato_nascimento'] && $linha['contato_nascimento'] && $linha['contato_nascimento']!='0000-00-00') echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Nascimento', 'O nascimento d'.$config['genero_contato'].' '.$config['contato'].'.').'Nascimento:'.dicaF().'</td><td style="white-space: nowrap" class="realce">'.retorna_data($linha['contato_nascimento'], false).'</td></tr>';	
		if ($exibir['contato_religiao'] && $linha['contato_religiao']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Religião', 'A religião d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Religiao:'.dicaF().'</td><td class="realce" style="text-align: left;">'.(isset($religiao[$linha['contato_religiao']]) ? $religiao[$linha['contato_religiao']] : '').'</td></tr>';
		if ($exibir['contato_sangue'] && $linha['contato_sangue']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Sangue', 'O tipo sanguínio d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Sangue:'.dicaF().'</td><td class="realce" style="text-align: left;">'.(isset($sangue[$linha['contato_sangue']]) ? $sangue[$linha['contato_sangue']] : '').'</td></tr>';
		if ($exibir['contato_vivo']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Viv'.$config['genero_usuario'], 'Se '.$config['genero_usuario'].' '.$config['usuario'].' se encontra viv'.$config['genero_usuario'].'.').'Viv'.$config['genero_usuario'].':'.dicaF().'</td><td class="realce" style="text-align: left;">'.($linha['contato_vivo'] ? 'Sim' : 'Não').'</td></tr>';
		if ($exibir['contato_natural_cidade'] && $linha['contato_natural_cidade']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Natural', 'O cidade de nascimento d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Natural:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$linha['cidade_natal'].' - '.$linha['contato_natural_estado'].'</td></tr>';
		if ($exibir['contato_grau_instrucao'] && $linha['contato_grau_instrucao']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Grau de Instrução', 'O Grau de instrução d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Instrução:'.dicaF().'</td><td class="realce" style="text-align: left;">'.(isset($escolaridade[$linha['contato_grau_instrucao']]) ? $escolaridade[$linha['contato_grau_instrucao']] : '').'</td></tr>';
		if ($exibir['contato_formacao'] && $linha['contato_formacao']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Formação', 'A formação d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Formação:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$linha['contato_formacao'].'</td></tr>';
		if ($exibir['contato_profissao'] && $linha['contato_profissao']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Profissão', 'A profissão d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Profissão:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$linha['contato_profissao'].'</td></tr>';
		if ($exibir['contato_ocupacao'] && $linha['contato_ocupacao']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Ocupação', 'A ocupação d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Ocupação:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$linha['contato_ocupacao'].'</td></tr>';
		if ($exibir['contato_especialidade'] && $linha['contato_especialidade']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Especialidade', 'A especialidade d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Especialidade:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$linha['contato_especialidade'].'</td></tr>';
		if ($exibir['contato_notas'] && $linha['contato_notas']) echo '<tr><td align="right" style="white-space: nowrap" width=10>'.dica('Observação', 'Observação sobre '.$config['genero_contato'].' '.$config['contato'].'.').'Observação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['contato_notas'].'</td></tr>';
		if ($exibir['contato_foto'] && $linha['contato_foto']) echo '</table></td><td align=right valign=top><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/contatos/'.$linha['contato_foto'].'" width=100 height=133 /></td></tr></table></td></tr>';
		echo '</table></td></tr></table></td>';
		}
	if (!count($linhas)) echo '</tr><tr><td colspan=20>Não foram encontrad'.$config['genero_contato'].'s '.$config['contatos'].'</td>';
	echo '</tr></table>';
	echo estiloFundoCaixa();
	}
else {
	
	
	$caixaTab = new CTabBox('m=contatos&a=index', '', $tab);
	if (!$dialogo){
		$caixaTab->adicionar(BASE_DIR.'/modulos/contatos/contato_idx', 'Ativ'.$config['genero_contato'].'s',null,null,'Ativ'.$config['genero_contato'].'s','Visualizar '.$config['genero_contato'].'s '.$config['contatos'].' ativ'.$config['genero_contato'].'s.');
		$caixaTab->adicionar(BASE_DIR.'/modulos/contatos/contato_idx', 'Inativ'.$config['genero_contato'].'s',null,null,'Inativ'.$config['genero_contato'].'s','Visualizar '.$config['genero_contato'].'s '.$config['contatos'].' inativ'.$config['genero_contato'].'s.');
		$caixaTab->adicionar(BASE_DIR.'/modulos/contatos/contato_idx', 'Tod'.$config['genero_contato'].'s',null,null,'Tod'.$config['genero_contato'].'s','Visualizar tod'.$config['genero_contato'].'s '.$config['genero_contato'].'s '.$config['contatos'].'.');
		$caixaTab->mostrar('','','','',true);
		echo estiloFundoCaixa('','', $tab);
		}
	else {
		include_once(BASE_DIR.'/modulos/contatos/contato_idx.php');
		if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';
		}
	
	
	}	
?>
<script type="text/javascript">

function mudar_modo(){
	env.submit();
	}

function popCamposExibir(){
	parent.gpwebApp.popUp('Campos', 500, 500, 'm=publico&a=campos&dialogo=1&campo_formulario_tipo=contatos', window.setCamposExibir, window);
	}
	
function setCamposExibir(){
	url_passar(0, 'm=contatos&a=index');
	}	


function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:250px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>));
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('usuario_id').value=usuario_id;
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		document.env.submit();
		}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	env.submit();
	}

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function mudar_om(){
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;');
	}

function mudar_usuario(){
	var cia_id=document.getElementById('cia_id').value;
	var usuario_id=document.getElementById('usuario_id').value;
	xajax_mudar_usuario_ajax(cia_id, usuario_id, 'usuario_id','combo_usuario', 'class="texto" size=1 style="width:250px;" onchange="escolheu_usuario();"');
	}

function escolheu_usuario(){
	document.env.cia_id.value=document.env.cia_id.value;
	}
</script>
