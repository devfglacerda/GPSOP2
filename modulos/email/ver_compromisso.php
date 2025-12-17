<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
 
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

require_once (BASE_DIR.'/modulos/email/email.class.php');
$agenda_id = intval(getParam($_REQUEST, 'agenda_id', null));

if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerAgendaTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerAgendaTab') !== null ? $Aplic->getEstado('VerAgendaTab') : 0;


$sql = new BDConsulta;
$podeEditar = true;
$msg = '';
$obj = new CAgenda();


if (!$obj->load($agenda_id)) {
	$Aplic->setMsg('Compromisso');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=email&a=ver_mes');
	} 

$podeExcluir = $obj->podeExcluir();
$tipos = getSisValor('TipoCompromisso');
$recorrencia = array('Nunca', 'A cada hora', 'Diario', 'Semanalmente', 'Quinzenal', 'Mensal', 'Quadrimensal', 'Semestral', 'Anual');

if ($obj->agenda_dono != $Aplic->usuario_id) $podeEditar = false;


$data_inicio = $obj->agenda_inicio ? new CData($obj->agenda_inicio) : new CData();
$data_fim = $obj->agenda_fim ? new CData($obj->agenda_fim) : new CData();


if (!$dialogo){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes do Compromisso', 'compromisso.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #212121" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_compromissos",dica('Visão Mensal','Visualizar a lista de todos os compromisso cadastrados na visão mensal.').'Visão Mensal'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=ver_mes&data=".$data_inicio->format('%Y%m%d')."\");");
	$km->Add("ver","ver_lista_compromissos",dica('Visão Semanal','Visualizar a lista de todos os compromisso cadastrados na visão semanal.').'Visão Semanal'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=ver_semana&data=".$data_inicio->format('%Y%m%d')."\");");
	$km->Add("ver","ver_lista_compromissos",dica('Visão Diária','Visualizar a lista de todos os compromisso cadastrados na visão diária.').'Visão Mensal'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=ver_dia&tab=0&data=".$data_inicio->format('%Y%m%d')."\");");
	if ($podeEditar) {
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_compromisso",dica('Novo Compromisso', 'Criar um novo compromisso.').'Novo Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso\");");
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar&agenda_id=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_agenda=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_agenda=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_agenda=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo fórum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_agenda=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_agenda=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_agenda=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_agenda=".$agenda_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_agenda=".$agenda_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_ver&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_agenda=".$agenda_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_agenda=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_agenda=".$agenda_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) {
			$km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_agenda=".$agenda_id."\");");
			$km->Add("inserir","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_agenda=".$agenda_id."\");");
			}
		
		
		
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($podeEditar) $km->Add("acao","acao_editar",dica('Editar Compromisso','Editar os detalhes deste compromisso.').'Editar Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=editar_compromisso&agenda_id=".$agenda_id."\");");
	if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este compromisso.').'Excluir Compromisso'.dicaF(), "javascript: void(0);' onclick='excluir()");	
	
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes do Compromisso', 'Visualize os detalhes deste compromisso.').' Detalhes do Compromisso'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=email&a=ver_compromisso&dialogo=1&agenda_id=".$agenda_id."\");");

	
	
	echo $km->Render();
	echo '</td></tr></table>';
	}



if($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $obj->agenda_cia;
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
	echo 	'<font size="4"><center>Compromisso</center></font>';
	}











echo '<form name="env" id="env" method="POST">';
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="a" value="ver_compromisso" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="agenda_id" value="'.$agenda_id.'" />';	
echo '<input type="hidden" name="sem_cabecalho" value="" />';
echo '<input type="hidden" name="agenda_arquivo_id" value="" />';
echo '</form>';


echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_agenda_aed" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="agenda_id" value="'.$agenda_id.'" />';
echo '</form>';


echo '<table cellpadding=0 cellspacing=0 width="100%"><tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->agenda_cor.' !important;color:#'.melhorCor($obj->agenda_cor).' !important;" colspan=2 class="realce" onclick="if (document.getElementById(\'tblagenda\').style.display) {document.getElementById(\'tblagenda\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'mostrar\').style.display=\'none\';} else {document.getElementById(\'tblagenda\').style.display=\'none\'; document.getElementById(\'contrair\').style.display=\'none\'; document.getElementById(\'mostrar\').style.display=\'\';} if(window.onResizeDetalhesProjeto) window.onResizeDetalhesProjeto(); xajax_painel_agenda(document.getElementById(\'tblagenda\').style.display);"><a href="javascript: void(0);"><span id="mostrar" style="display:none">'.imagem('icones/mostrar.gif', 'Mostrar Detalhes', 'Clique neste ícone '.imagem('icones/mostrar.gif').' para mostrar os detalhes do compromisso.').'</span><span id="contrair">'.imagem('icones/contrair.gif', 'Ocultar Detalhes', 'Clique neste ícone '.imagem('icones/contrair.gif').' para ocultar os detalhes do compromisso.').'</span><b>'.$obj->agenda_titulo.'<b></font></td></tr></table>';

$painel_agenda = $Aplic->getEstado('painel_agenda') !== null ? $Aplic->getEstado('painel_agenda') : 1;
echo '<table id="tblagenda" cellpadding=0 cellspacing=1 width="100%" '.(!$dialogo ? 'class="std" ' : '').' style="display:'.($painel_agenda ? '' : 'none').'">';

if ($obj->agenda_descricao) echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Descrição', 'Um resumo sobre o compromisso.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->agenda_descricao.'</td></tr>';
if ($obj->agenda_oque) echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=50>'.dica('O Que Fazer', 'Sumário sobre o que se trata este compromisso.').'O Que:'.dicaF().'</td><td class="realce" width="100%">'.$obj->agenda_oque.'</td></tr>';
if ($obj->agenda_quem) echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=50>'.dica('Quem', 'Quem está relacinado com este compromisso.').'Quem:'.dicaF().'</td><td class="realce" width="100%">'.$obj->agenda_quem.'</td></tr>';
if ($obj->agenda_dono!=$Aplic->usuario_id) echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Criador', 'O criador do compromisso.').'Criador:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->agenda_dono,'','','esquerda').'</td></tr>';




$participantes = $obj->getDesignado('nao_decidiu', false);
$saida_quem='';
		if ($participantes && count($participantes)) {
			$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
			$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda');
			$qnt_participantes=count($participantes);
			if ($qnt_participantes > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'nao_decidiu\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="nao_decidiu"><br>'.$lista.'</span>';
					}
			$saida_quem.= '</td></tr></table>';
			} 
if ($saida_quem) echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Sem Confirmação', 'Quais '.$config['genero_usuario'].'s '.$config['usuarios'].' que tem ainda não confirmaram presença neste compromisso.').'Sem confirmação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';



$participantes = $obj->getDesignado('aceitou', false);
$saida_quem='';
		if ($participantes && count($participantes)) {
			$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
			$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda');
			$qnt_participantes=count($participantes);
			if ($qnt_participantes > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'aceitou\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="aceitou"><br>'.$lista.'</span>';
					}
			$saida_quem.= '</td></tr></table>';
			} 
if ($saida_quem) echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Confirmação', 'Quais '.$config['genero_usuario'].'s '.$config['usuarios'].' que tem confirmaram presença neste compromisso.').'Confirmou:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';



$participantes = $obj->getDesignado('recusou', false);
$saida_quem='';
		if ($participantes && count($participantes)) {
			$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
			$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda');
			$qnt_participantes=count($participantes);
			if ($qnt_participantes > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'recusou\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="recusou"><br>'.$lista.'</span>';
					}
			$saida_quem.= '</td></tr></table>';
			} 
if ($saida_quem) echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Recusa', 'Quais '.$config['genero_usuario'].'s '.$config['usuarios'].' que tem recusaram participar neste compromisso.').'Recusou:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$saida_quem.'</td></tr>';





if ($obj->agenda_quando) echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=50>'.dica('Quando Fazer', 'Quando o compromisso é executado.').'Quando:'.dicaF().'</td><td class="realce" width="100%">'.$obj->agenda_quando.'</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Início', 'Data e hora do iníio do compromisso.').'Início:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($data_inicio ? $data_inicio->format('%d/%m/%Y %H:%M') : '&nbsp;').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Término', 'Data e hora de término do compromisso.').'Término:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($data_fim ? $data_fim->format('%d/%m/%Y %H:%M') : '&nbsp;').'</td></tr>';
if ($obj->agenda_recorrencias) echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Recorrência', 'De quanto em quanto tempo este compromisso se repete.').'Recorrência:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$recorrencia[$obj->agenda_recorrencias].($obj->agenda_recorrencias ? ' ('.$obj->agenda_nr_recorrencias.' vez'.((int)$obj->agenda_nr_recorrencias > 1 ? 'es':''). ')' : '').'</td></tr>';

if ($obj->agenda_onde) echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=50>'.dica('Onde Fazer', 'Onde o compromisso é executado.').'Onde:'.dicaF().'</td><td class="realce" width="100%">'.$obj->agenda_onde.'</td></tr>';
if ($obj->agenda_porque) echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=50>'.dica('Por Que Fazer', 'Por que o compromisso será executado.').'Por que:'.dicaF().'</td><td class="realce" width="100%">'.$obj->agenda_porque.'</td></tr>';
if ($obj->agenda_como) echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=50>'.dica('Como Fazer', 'Como o compromisso é executado.').'Como:'.dicaF().'</td><td class="realce" width="100%">'.$obj->agenda_como.'</td></tr>';
if ($obj->agenda_quanto) echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=50>'.dica('Quanto Custa', 'Custo para executar o compromisso.').'Quanto:'.dicaF().'</td><td class="realce" width="100%">'.$obj->agenda_quanto.'</td></tr>';





if ($Aplic->profissional){
	$sql->adTabela('agenda_gestao');
	$sql->adCampo('agenda_gestao.*');
	$sql->adOnde('agenda_gestao_agenda ='.(int)$agenda_id);	
	$sql->adOrdem('agenda_gestao_ordem');
	$lista = $sql->Lista();
	$sql->limpar();
	$qnt_gestao=0;
	
	if (count($lista)) {
		echo '<tr><td align="right" style="white-space: nowrap" valign="middle">'.dica('Relacionado', 'A que área o compromisso está relacionado.').'Relacionado:'.dicaF().'</td></td><td class="realce">';	
		foreach($lista as $gestao_data){
			if ($gestao_data['agenda_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['agenda_gestao_tarefa']);
			elseif ($gestao_data['agenda_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['agenda_gestao_projeto']);
			elseif ($gestao_data['agenda_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['agenda_gestao_pratica']);
			elseif ($gestao_data['agenda_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['agenda_gestao_acao']);
			elseif ($gestao_data['agenda_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['agenda_gestao_perspectiva']);
			elseif ($gestao_data['agenda_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['agenda_gestao_tema']);
			elseif ($gestao_data['agenda_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['agenda_gestao_objetivo']);
			elseif ($gestao_data['agenda_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['agenda_gestao_fator']);
			elseif ($gestao_data['agenda_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['agenda_gestao_estrategia']);
			elseif ($gestao_data['agenda_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['agenda_gestao_meta']);
			elseif ($gestao_data['agenda_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['agenda_gestao_canvas']);
			elseif ($gestao_data['agenda_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['agenda_gestao_risco']);
			elseif ($gestao_data['agenda_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['agenda_gestao_risco_resposta']);
			elseif ($gestao_data['agenda_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['agenda_gestao_indicador']);
			elseif ($gestao_data['agenda_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['agenda_gestao_calendario']);
			elseif ($gestao_data['agenda_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['agenda_gestao_monitoramento']);
			elseif ($gestao_data['agenda_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['agenda_gestao_ata']);
			elseif ($gestao_data['agenda_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['agenda_gestao_mswot']);
			elseif ($gestao_data['agenda_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['agenda_gestao_swot']);
			elseif ($gestao_data['agenda_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['agenda_gestao_operativo']);
			elseif ($gestao_data['agenda_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['agenda_gestao_instrumento']);
			elseif ($gestao_data['agenda_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['agenda_gestao_recurso']);
			elseif ($gestao_data['agenda_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['agenda_gestao_problema']);
			elseif ($gestao_data['agenda_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['agenda_gestao_demanda']);	
			elseif ($gestao_data['agenda_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['agenda_gestao_programa']);
			elseif ($gestao_data['agenda_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['agenda_gestao_licao']);
			elseif ($gestao_data['agenda_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['agenda_gestao_evento']);
			elseif ($gestao_data['agenda_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['agenda_gestao_link']);
			elseif ($gestao_data['agenda_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['agenda_gestao_avaliacao']);
			elseif ($gestao_data['agenda_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['agenda_gestao_tgn']);
			elseif ($gestao_data['agenda_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['agenda_gestao_brainstorm']);
			elseif ($gestao_data['agenda_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['agenda_gestao_gut']);
			elseif ($gestao_data['agenda_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['agenda_gestao_causa_efeito']);
			elseif ($gestao_data['agenda_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['agenda_gestao_arquivo']);
			elseif ($gestao_data['agenda_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['agenda_gestao_forum']);
			elseif ($gestao_data['agenda_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['agenda_gestao_checklist']);
			
			elseif ($gestao_data['agenda_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['agenda_gestao_semelhante']);
			
			elseif ($gestao_data['agenda_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['agenda_gestao_agrupamento']);
			elseif ($gestao_data['agenda_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['agenda_gestao_patrocinador']);
			elseif ($gestao_data['agenda_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['agenda_gestao_template']);
			elseif ($gestao_data['agenda_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['agenda_gestao_painel']);
			elseif ($gestao_data['agenda_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['agenda_gestao_painel_odometro']);
			elseif ($gestao_data['agenda_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['agenda_gestao_painel_composicao']);		
			elseif ($gestao_data['agenda_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['agenda_gestao_tr']);	
			elseif ($gestao_data['agenda_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['agenda_gestao_me']);	
			elseif ($gestao_data['agenda_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['agenda_gestao_acao_item']);	
			elseif ($gestao_data['agenda_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['agenda_gestao_beneficio']);	
			elseif ($gestao_data['agenda_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['agenda_gestao_painel_slideshow']);	
			elseif ($gestao_data['agenda_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['agenda_gestao_projeto_viabilidade']);	
			elseif ($gestao_data['agenda_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['agenda_gestao_projeto_abertura']);	
			elseif ($gestao_data['agenda_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['agenda_gestao_plano_gestao']);	
			}
		echo '</td></tr>';
		}	
	}


if ($obj->agenda_principal_indicador) echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Indicador Principal', 'Dentre os indicadores relacionados, o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->agenda_principal_indicador).'</td></tr>';


require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados('agenda', $obj->agenda_id, 'ver');
$campos_customizados->imprimirHTML();




if (!$Aplic->profissional) echo '<tr><td align="right" colspan="2">'.botao('sair', 'Sair', 'Sair do detalhe de compromisso.','','url_passar(0, \'m=email&a=ver_mes\');').'</td></tr>';
echo '</table>';

if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';

if (!$dialogo) {
	$caixaTab = new CTabBox('m=email&a=ver_compromisso&agenda_id='.$agenda_id, '', $tab);
	$texto_consulta = '?m=email&a=ver_compromisso&agenda_id='.$agenda_id;
	$qnt_aba=0;

	if ($Aplic->checarModulo('log', 'acesso')) {
		$sql->adTabela('log');
		$sql->adCampo('count(log_id)');
		$sql->adOnde('log_agenda = '.(int)$agenda_id);
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver', 'Registro',null,null,'Registro das Ocorrência','Visualizar o registro de ocorrência relacionado.');
			}
		}

	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {
		if ($Aplic->profissional) {
			$sql->adTabela('evento_gestao','evento_gestao');
			$sql->adOnde('evento_gestao_agenda = '.(int)$agenda_id);
			$sql->adOnde('evento_gestao_evento IS NOT NULL');
			$sql->adCampo('count(evento_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			}
		else $existe=false;		
		if ($existe) {
			$qnt_aba++;
			$data_inicio=null;
			$data_fim=null;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Evento',null,null,'Evento','Visualizar o evento relacionado.');
			}
		}
		
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) {

			$sql->adTabela('arquivo_gestao','arquivo_gestao');
			$sql->adOnde('arquivo_gestao_agenda = '.(int)$agenda_id);
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
		if ($Aplic->profissional) {
			$sql->adTabela('pratica_indicador_gestao','pratica_indicador_gestao');
			$sql->adOnde('pratica_indicador_gestao_agenda = '.(int)$agenda_id);
			$sql->adOnde('pratica_indicador_gestao_indicador IS NOT NULL');
			$sql->adCampo('count(pratica_indicador_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			}
		else $existe=false;		
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicador',null,null,'Indicador','Visualizar o indicador relacionado.');
			}
		}
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) {
		if ($Aplic->profissional) {
			$sql->adTabela('plano_acao_gestao','plano_acao_gestao');
			$sql->adOnde('plano_acao_gestao_agenda = '.(int)$agenda_id);
			$sql->adOnde('plano_acao_gestao_acao IS NOT NULL');
			$sql->adCampo('count(plano_acao_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			}
		else $existe=false;		
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acao']),null,null,ucfirst($config['acao']),'Visualizar '.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao_item')) {
		$sql->adTabela('plano_acao_item_gestao');
		$sql->adOnde('plano_acao_item_gestao_agenda = '.(int)$agenda_id);
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
		if ($Aplic->profissional) {
			$sql->adTabela('projeto_gestao');
			$sql->adOnde('projeto_gestao_agenda = '.(int)$agenda_id);
			$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=projeto_gestao_projeto');
			$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
			$sql->adOnde('projeto_portfolio=0');
			$sql->adCampo('count(projeto_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			}
		else $existe=false;		
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos', ucfirst($config['projeto']),null,null,ucfirst($config['projeto']),'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.');
			}
		
		
		
		$sql->adTabela('projeto_gestao');
		$sql->adOnde('projeto_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('ata_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('demanda_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('msg_gestao_agenda = '.(int)$agenda_id);
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
			$sql->adOnde('modelo_gestao_agenda = '.(int)$agenda_id);
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
		if ($Aplic->profissional) {
			$sql->adTabela('link_gestao','link_gestao');
			$sql->adOnde('link_gestao_agenda = '.(int)$agenda_id);
			$sql->adOnde('link_gestao_link IS NOT NULL');
			$sql->adCampo('count(link_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			}
		else $existe=false;	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Link',null,null,'Link','Visualizar o link relacionado.');
			}
		}
	
	if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) {
		if ($Aplic->profissional) {
			$sql->adTabela('forum_gestao','forum_gestao');
			$sql->adOnde('forum_gestao_agenda = '.(int)$agenda_id);
			$sql->adOnde('forum_gestao_forum IS NOT NULL');
			$sql->adCampo('count(forum_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			}
		else $existe=false;		
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fórum',null,null,'Fórum','Visualizar o fórum relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) {
		$sql->adTabela('problema_gestao','problema_gestao');
		$sql->adOnde('problema_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('risco_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('risco_resposta_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('instrumento_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('recurso_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('patrocinador_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('programa_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('beneficio_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('licao_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('pratica_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('tr_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('brainstorm_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('gut_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('causa_efeito_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('tgn_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('canvas_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('mswot_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('swot_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('operativo_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('monitoramento_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('avaliacao_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('checklist_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('agenda_gestao_semelhante = '.(int)$agenda_id);
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
		$sql->adOnde('agrupamento_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('template_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('painel_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('painel_odometro_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('painel_composicao_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('painel_slideshow_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('calendario_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('perspectiva_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('tema_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('objetivo_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('me_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('fator_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('estrategia_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('meta_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('plano_gestao_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('projeto_abertura_gestao_agenda = '.(int)$agenda_id);
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
		$sql->adOnde('projeto_viabilidade_gestao_agenda = '.(int)$agenda_id);
		$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade IS NOT NULL');
		$sql->adCampo('count(projeto_viabilidade_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/viabilidade_tabela','Estudo de viabilidade',null,null,'Estudo de viabilidade','Visualizar o estudo de viabilidade relacionado.');
			}
		}	
	
	if ($qnt_aba) {
		$caixaTab->mostrar('','','','',true);
		echo estiloFundoCaixa('','', $tab);
		}	
	
	}

?>
<script type="text/javascript">
function excluir() {
	if (confirm( "Tem certeza que deseja excluir o compromisso?" )) document.frmExcluir.submit();
	}
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>
