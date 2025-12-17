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

$contato_id = intval(getParam($_REQUEST, 'contato_id', 0));
if (!$dialogo) $Aplic->salvarPosicao();
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$msg = '';
$sql = new BDConsulta;
$sql->adTabela('contatos');
$sql->esqUnir('estado', 'estado', 'contato_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'contato_cidade = municipio_id');
$sql->adCampo('estado_nome, municipio_nome');
$sql->adOnde('contato_id='.$contato_id);
$endereco=$sql->Linha();
$sql->limpar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'contato\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$obj = new CContato();
$podeExcluir = $obj->podeExcluir($msg, $contato_id);
$eh_usuario = $obj->ehUsuario($contato_id);
if (!$obj->load($contato_id) && $contato_id > 0) {
	$Aplic->setMsg('Contatos');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=contatos');
	} 
elseif ($obj->contato_privado && ($obj->contato_dono != $Aplic->usuario_id) && $obj->contato_dono && $contato_id) $Aplic->redirecionar('m=publico&a=acesso_negado');

$paises = getPais('Paises');
$usuario=$obj->ehUsuario();

/*if (!$dialogo) {
//$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_contato'].' '.ucfirst($config['contato']), 'contatos.png', $m, $m.'.'.$a);
//$botoesTitulo->adicionaBotao('m=contatos', 'lista','','Lista de '.ucfirst($config['contatos']),'Lista de '.$config['contatos'].'.');
//if (($obj->contato_dono==$Aplic->usuario_id || $usuario=$Aplic->usuario_id || $Aplic->usuario_super_admin) && $podeEditar && $contato_id) $botoesTitulo->adicionaBotao('m=contatos&a=editar&contato_id='.$contato_id, 'editar','','Editar '.ucfirst($config['contato']),'Editar os dados d'.$config['genero_contato'].' '.$config['contato'].'.');
//if (($obj->contato_dono==$Aplic->usuario_id || $Aplic->usuario_super_admin) && $podeExcluir && $contato_id) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir '.ucfirst($config['contato']),'Excluir '.$config['genero_contato'].' '.$config['contato'].'.');
$botoesTitulo->mostrar();
echo estiloTopoCaixa();
}*/


if (!$dialogo){	
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_contato'].' '.ucfirst($config['contato']), 'contatos.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #212121" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_links",dica('Lista de '.ucfirst($config['contatos']),'Visualizar a lista de '.$config['contatos'].'.').'Lista de '.ucfirst($config['contatos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos\");");
	
	
	if ($podeAdicionar) {
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_link",dica('Nov'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Criar nov'.$config['genero_contato'].' '.$config['contato'].'.').'Nov'.$config['genero_contato'].' '.ucfirst($config['contato']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos&a=editar\");");
		}	
		
	$editar=((($obj->contato_dono==$Aplic->usuario_id || $usuario=$Aplic->usuario_id || $Aplic->usuario_super_admin) && $podeEditar && $contato_id) ? true : false);
	$excluir=((($obj->contato_dono==$Aplic->usuario_id || $Aplic->usuario_super_admin) && $podeExcluir && $contato_id) ? true : false);	
		
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");

	if ($editar) {
		$km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['contato']),'Editar os detalhes d'.$config['genero_contato'].' '.$config['contato'].'.').'Editar '.ucfirst($config['contato']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos&a=editar&contato_id=".(int)$contato_id."\");");
		if ($exibir['contato_foto']) $km->Add("acao","acao_foto",dica('Foto 3X4', 'Incluir ou alterar a foto 3X4 d'.$config['genero_contato'].' '.$config['contato'].'.').'Foto 3X4'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=contatos&a=foto&jquery=1&contato_id=".$contato_id."\");");
		}
	
	if ($excluir) $km->Add("acao","acao_excluir",dica('Excluir '.ucfirst($config['contato']),'Excluir '.$config['genero_contato'].' '.$config['contato'].'.').'Excluir '.ucfirst($config['contato']).dicaF(), "javascript: void(0);' onclick='excluir()");

	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes d'.$config['genero_contato'].' '.ucfirst($config['contato']), 'Visualize os detalhes d'.$config['genero_contato'].' '.$config['contato'].'.').'Detalhes d'.$config['genero_contato'].' '.ucfirst($config['contato']).dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&contato_id=".$contato_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}
	
	
echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="contatos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_contato_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="contato_id" value="'.$contato_id.'" />';
echo '<input type="hidden" name="contato_dono" value="'.($obj->contato_dono ? $obj->contato_dono : $Aplic->usuario_id).'" />';
echo '</form>';

if ($exibir['contato_foto'] && $obj->contato_foto) echo '<table cellpadding=0 cellspacing=0 cellspacing=0 width="100%" '.(!$dialogo ? 'class="std" ' : '').'><tr><td width="99%" valign=top><table cellpadding=0 cellspacing=1 width="100%">';
else echo '<table cellpadding=0 cellspacing=1 width="100%" '.(!$dialogo ? 'class="std" ' : '').'>';

if ($obj->contato_nomeguerra) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome', 'Nome d'.$config['genero_contato'].' '.$config['contato'].'.').'Nome:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->contato_posto ? $obj->contato_posto.' ' : '').$obj->contato_nomeguerra.'</td></tr>';
if ($obj->contato_nomecompleto) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome Completo', 'Nome completo d'.$config['genero_contato'].' '.$config['contato'].'.').'Nome completo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_nomecompleto.'</td></tr>';
if ($obj->contato_cia) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' d'.$config['genero_contato'].' '.$config['contato'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td  class="realce" style="white-space: nowrap">'.link_cia($obj->contato_cia).'</a></td></tr>';
if ($obj->contato_dept) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' d'.$config['genero_contato'].' '.$config['contato'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->contato_dept).'</td></tr>';
if ($exibir['contato_funcao'] && $obj->contato_funcao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cargo/Função', 'O Cargo/Função d'.$config['genero_contato'].' '.$config['contato'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Função:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_funcao.'</td></tr>';
if ($config['militar'] < 10 && $obj->contato_arma) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Arma/Quadro/Sv', 'A Arma/Quadro/Sv d'.$config['genero_contato'].' '.$config['contato'].'.').'Arma/Quadro/Sv:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_arma.'</td></tr>';
if ($exibir['contato_tipo'] && $obj->contato_tipo) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tipo', 'O tipo d'.$config['genero_contato'].' '.$config['contato'].'.').'Tipo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_tipo.'</td></tr>';
if ($exibir['contato_codigo'] && $obj->contato_codigo) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Código', 'Código d'.$config['genero_contato'].' '.$config['contato'].'.').'Código:'.dicaF().'</td><td class="realce" width="100%">'.$obj->contato_codigo.'</td></tr>';
if ($exibir['contato_identidade'] && $obj->contato_identidade) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Identidade', 'A identidade d'.$config['genero_contato'].' '.$config['contato'].'.').'Identidade:'.dicaF().'</td><td style="white-space: nowrap" class="realce">'.$obj->contato_identidade.'</td></tr>';
if ($exibir['contato_cpf'] && $obj->contato_cpf) echo '<tr><td align="right" style="white-space: nowrap">'.dica('CPF', 'O CPF d'.$config['genero_contato'].' '.$config['contato'].'.').'CPF:'.dicaF().'</td><td style="white-space: nowrap" class="realce">'.$obj->contato_cpf.'</td></tr>';
if ($exibir['contato_cnpj'] && $obj->contato_cnpj) echo '<tr><td align="right" style="white-space: nowrap">'.dica('CNPJ', 'O CNPJ d'.$config['genero_contato'].' '.$config['contato'].'.').'CNPJ:'.dicaF().'</td><td style="white-space: nowrap" class="realce">'.$obj->contato_cnpj.'</td></tr>';
if ($exibir['contato_endereco'] && $obj->contato_endereco1 || $obj->contato_endereco2 || $obj->contato_cidade || $obj->contato_estado) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Endereço', 'O endereço d'.$config['genero_contato'].' '.$config['contato'].'.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_endereco1.($obj->contato_endereco1 ? '<br />' :'').$obj->contato_endereco2.($obj->contato_endereco2 ? '<br />' :'').$endereco['municipio_nome'].' '.$endereco['estado_nome'].' '.$obj->contato_cep.($obj->contato_cidade || $obj->contato_estado || $obj->contato_cep ? '<br />' :'').($paises[$obj->contato_pais] ? $paises[$obj->contato_pais] : $obj->contato_pais).'</td></tr>';
if ($exibir['contato_endereco'] && $obj->contato_endereco1 || $obj->contato_endereco2 || $obj->contato_cidade || $obj->contato_estado) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Visualizar Endereço', 'Clique no símbolo do Google Maps à direita para visualizar o endereço d'.$config['genero_contato'].' '.$config['contato'].'.').'Visualizar Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.dica('Google Maps', 'Clique nesta imagem para visualizar no Google Maps, aberto em uma nova janela, o endereço d'.$config['genero_contato'].' '.$config['contato'].'.').'<a target="_blank" href="'.get_protocol().'maps.google.com/maps?key=AIzaSyAsFbkGMNJdcsHBSQySo8jpA7zqBhlg1Pg&q='.utf8_encode($obj->contato_endereco1).'+'.utf8_encode($obj->contato_endereco2).'+'.utf8_encode($endereco['municipio_nome']).'+'.utf8_encode($obj->contato_estado).'+'.utf8_encode($obj->contato_cep).'+'.utf8_encode($obj->contato_pais).'"><img align="left" src="'.acharImagem('google_map.png').'" alt="Achar no Google Maps" /></a>'.dicaF().'</td></tr>';
if ($exibir['contato_tel'] && $obj->contato_tel) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Telefone Comercial', 'O telefone comercial d'.$config['genero_contato'].' '.$config['contato'].'.').'Telefone Comercial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_tel.'</td></tr>';
if ($exibir['contato_tel2'] && $obj->contato_tel2) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Telefone Residencial', 'O telefone residencial d'.$config['genero_contato'].' '.$config['contato'].'.').'Telefone Residencial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_tel2.'</td></tr>';
if ($exibir['contato_cel'] && $obj->contato_cel) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Celular', 'O celular d'.$config['genero_contato'].' '.$config['contato'].'.').'Celular:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_cel.'</td></tr>';
if ($exibir['contato_email'] && $obj->contato_email) echo '<tr><td align="right" style="white-space: nowrap">'. dica('E-mail', 'O e-mail d'.$config['genero_contato'].' '.$config['contato'].'.').'E-mail:'.dicaF().'</td><td class="realce" style="white-space: nowrap">'.link_email($obj->contato_email, $contato_id).'</td></tr>';
if ($exibir['contato_email2'] && $obj->contato_email2) echo '<tr><td align="right" style="white-space: nowrap">'. dica('E-mail Alternativo', 'O e-mail alternativo d'.$config['genero_contato'].' '.$config['contato'].'.').'E-mail alternativo:'.dicaF().'</td><td class="realce" style="white-space: nowrap">'.link_email($obj->contato_email2, $contato_id).'</td></tr>';
if ($exibir['contato_skype'] && $obj->contato_skype) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Skype', 'A conta Skype d'.$config['genero_contato'].' '.$config['contato'].'.').'Skype:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="skype:'.$obj->contato_skype.'?call">'.$obj->contato_skype.'</a></td></tr>';
if ($obj->contato_hora_custo && $Aplic->checarModulo('usuarios', 'acesso', $Aplic->usuario_id, 'hora_custo')) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Custo da Hora', 'O custo da hora de trabalho d'.$config['genero_contato'].' '.$config['contato'].'.').'Custo hora:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format($obj->contato_hora_custo, 2, ',', '.').'</td></tr>';
if ($exibir['contato_nascimento'] && $obj->contato_nascimento && $obj->contato_nascimento!='0000-00-00') echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nascimento', 'O nascimento d'.$config['genero_contato'].' '.$config['contato'].'.').'Nascimento:'.dicaF().'</td><td style="white-space: nowrap" class="realce">'.retorna_data($obj->contato_nascimento, false).'</td></tr>';	
if ($exibir['contato_religiao'] && $obj->contato_religiao) echo '<tr><td align="right">'.dica('Religião', 'A religião d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Religião:'.dicaF().'</td><td class="realce" style="text-align: left;">'.getSisValorCampo('Religiao',$obj->contato_religiao).'</td></tr>';
if ($exibir['contato_sangue'] && $obj->contato_sangue) echo '<tr><td align="right">'.dica('Sangue', 'O tipo sanguínio d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Sangue:'.dicaF().'</td><td class="realce" style="text-align: left;">'.getSisValorCampo('Sangue', $obj->contato_sangue).'</td></tr>';
if ($exibir['contato_vivo']) echo '<tr><td align="right">'.dica('Viv'.$config['genero_usuario'], 'Se '.$config['genero_usuario'].' '.$config['usuario'].' se encontra viv'.$config['genero_usuario'].'.').'Viv'.$config['genero_usuario'].'.:'.dicaF().'</td><td class="realce" style="text-align: left;">'.($obj->contato_vivo ? 'Sim' : 'Não').'</td></tr>';
if ($exibir['contato_natural_cidade'] && $obj->contato_natural_cidade) {
	$sql->adTabela('contatos');
	$sql->esqUnir('estado', 'estado', 'contato_estado=estado_sigla');
	$sql->esqUnir('municipios', 'municipios', 'contato_natural_cidade = municipio_id');
	$sql->adCampo('estado_nome, municipio_nome');
	$sql->adOnde('contato_id='.(int)$obj->contato_id);
	$endereco=$sql->Linha();
	$sql->limpar();
	echo '<tr><td align="right">'.dica('Natural', 'O cidade de nascimento d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Natural:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$endereco['municipio_nome'].' - '.$endereco['estado_nome'].'</td></tr>';
	}
if ($exibir['contato_grau_instrucao'] && $obj->contato_grau_instrucao) echo '<tr><td align="right">'.dica('Grau de Instrução', 'O Grau de instrução d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Grau de instrução:'.dicaF().'</td><td class="realce" style="text-align: left;">'.getSisValorCampo('Escolaridade',$obj->contato_grau_instrucao).'</td></tr>';
if ($exibir['contato_formacao'] && $obj->contato_formacao) echo '<tr><td align="right">'.dica('Formação', 'A formação d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Formação:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$obj->contato_formacao.'</td></tr>';
if ($exibir['contato_profissao'] && $obj->contato_profissao) echo '<tr><td align="right">'.dica('Profissão', 'A profissão d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Profissão:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$obj->contato_profissao.'</td></tr>';
if ($exibir['contato_ocupacao'] && $obj->contato_ocupacao) echo '<tr><td align="right">'.dica('Ocupação', 'A ocupação d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Ocupação:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$obj->contato_ocupacao.'</td></tr>';
if ($exibir['contato_especialidade'] && $obj->contato_especialidade) echo '<tr><td align="right">'.dica('Especialidade', 'A especialidade d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Especialidade:'.dicaF().'</td><td class="realce" style="text-align: left;">'.$obj->contato_especialidade.'</td></tr>';
if ($exibir['contato_notas'] && $obj->contato_notas) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Observação', 'Observação sobre '.$config['genero_contato'].' '.$config['contato'].'.').'Observação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->contato_notas.'</td></tr>';








if ($obj->contato_chave_atualizacao || $obj->contato_pedido_atualizacao || $obj->contato_ultima_atualizacao) {

	if ($obj->contato_chave_atualizacao) echo '<tr><td align="right"  style="white-space: nowrap">Aguardando:</td><td align="left"><input type="checkbox" value="1" name="contato_pedido_atualizacao" READONLY '.($obj->contato_chave_atualizacao ? 'checked="checked"' : '').' /></td></tr>';	
	if ($obj->contato_pedido_atualizacao) echo '<tr><td align="right" style="white-space: nowrap">Último Pedido:</td><td align="left" style="white-space: nowrap">'.($obj->contato_pedido_atualizacao ? retorna_data($obj->contato_pedido_atualizacao) : '').'</td></tr>';	
	if ($obj->contato_ultima_atualizacao) echo '<tr><td align="right" style="white-space: nowrap">Atualização:</td><td align="left" style="white-space: nowrap">'.(($obj->contato_ultima_atualizacao && !($obj->contato_ultima_atualizacao == 0)) ? retorna_data($obj->contato_ultima_atualizacao) : '').'</td></tr>';
	}
	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $obj->contato_id, 'ver');
if ($campos_customizados->count()) echo $campos_customizados->imprimirHTML();


if ($exibir['contato_funcao'] && $obj->contato_foto) echo '</table></td><td valign=top align=left><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/contatos/'.$obj->contato_foto.'" width=100 height=133 /></td></tr></table>';
else echo '</table>';
echo '</form>';
if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';
?>
<script language="JavaScript">
function excluir(){
  var form = document.env;
  if(confirm( 'Tem certeza que deseja excluir?')) {
    form.del.value = '<?php echo $contato_id; ?>';
    form.submit();
  	}
	}
</script>
