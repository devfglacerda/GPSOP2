<?php 
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $Aplic, $config, $cal_sdf;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

$Aplic->carregarCKEditorJS();

$cia_id=$Aplic->usuario_cia;


$agenda_id=getParam($_REQUEST, 'agenda_id', null);
$direcao=getParam($_REQUEST, 'cmd', '');
$agenda_arquivo_id=getParam($_REQUEST, 'agenda_arquivo_id', null);

$agenda_projeto=getParam($_REQUEST, 'agenda_projeto', null);
$agenda_tarefa=getParam($_REQUEST, 'agenda_tarefa', null);
$agenda_perspectiva=getParam($_REQUEST, 'agenda_perspectiva', null);
$agenda_tema=getParam($_REQUEST, 'agenda_tema', null);
$agenda_objetivo=getParam($_REQUEST, 'agenda_objetivo', null);
$agenda_fator=getParam($_REQUEST, 'agenda_fator', null);
$agenda_estrategia=getParam($_REQUEST, 'agenda_estrategia', null);
$agenda_meta=getParam($_REQUEST, 'agenda_meta', null);
$agenda_pratica=getParam($_REQUEST, 'agenda_pratica', null);
$agenda_acao=getParam($_REQUEST, 'agenda_acao', null);
$agenda_canvas=getParam($_REQUEST, 'agenda_canvas', null);
$agenda_risco=getParam($_REQUEST, 'agenda_risco', null);
$agenda_risco_resposta=getParam($_REQUEST, 'agenda_risco_resposta', null);
$agenda_indicador=getParam($_REQUEST, 'agenda_indicador', null);
$agenda_calendario=getParam($_REQUEST, 'agenda_calendario', null);
$agenda_monitoramento=getParam($_REQUEST, 'agenda_monitoramento', null);
$agenda_ata=getParam($_REQUEST, 'agenda_ata', null);
$agenda_mswot=getParam($_REQUEST, 'agenda_mswot', null);
$agenda_swot=getParam($_REQUEST, 'agenda_swot', null);
$agenda_operativo=getParam($_REQUEST, 'agenda_operativo', null);
$agenda_instrumento=getParam($_REQUEST, 'agenda_instrumento', null);
$agenda_recurso=getParam($_REQUEST, 'agenda_recurso', null);
$agenda_problema=getParam($_REQUEST, 'agenda_problema', null);
$agenda_demanda=getParam($_REQUEST, 'agenda_demanda', null);
$agenda_programa=getParam($_REQUEST, 'agenda_programa', null);
$agenda_licao=getParam($_REQUEST, 'agenda_licao', null);
$agenda_evento=getParam($_REQUEST, 'agenda_evento', null);
$agenda_link=getParam($_REQUEST, 'agenda_link', null);
$agenda_avaliacao=getParam($_REQUEST, 'agenda_avaliacao', null);
$agenda_tgn=getParam($_REQUEST, 'agenda_tgn', null);
$agenda_brainstorm=getParam($_REQUEST, 'agenda_brainstorm', null);
$agenda_gut=getParam($_REQUEST, 'agenda_gut', null);
$agenda_causa_efeito=getParam($_REQUEST, 'agenda_causa_efeito', null);
$agenda_arquivo=getParam($_REQUEST, 'agenda_arquivo', null);
$agenda_forum=getParam($_REQUEST, 'agenda_forum', null);
$agenda_checklist=getParam($_REQUEST, 'agenda_checklist', null);
$agenda_agenda=getParam($_REQUEST, 'agenda_agenda', null);
$agenda_agrupamento=getParam($_REQUEST, 'agenda_agrupamento', null);
$agenda_patrocinador=getParam($_REQUEST, 'agenda_patrocinador', null);
$agenda_template=getParam($_REQUEST, 'agenda_template', null);
$agenda_painel=getParam($_REQUEST, 'agenda_painel', null);
$agenda_painel_odometro=getParam($_REQUEST, 'agenda_painel_odometro', null);
$agenda_painel_composicao=getParam($_REQUEST, 'agenda_painel_composicao', null);
$agenda_tr=getParam($_REQUEST, 'agenda_tr', null);
$agenda_me=getParam($_REQUEST, 'agenda_me', null);
$agenda_acao_item=getParam($_REQUEST, 'agenda_acao_item', null);
$agenda_beneficio=getParam($_REQUEST, 'agenda_beneficio', null);
$agenda_painel_slideshow=getParam($_REQUEST, 'agenda_painel_slideshow', null);
$agenda_projeto_viabilidade=getParam($_REQUEST, 'agenda_projeto_viabilidade', null);
$agenda_projeto_abertura=getParam($_REQUEST, 'agenda_projeto_abertura', null);
$agenda_plano_gestao=getParam($_REQUEST, 'agenda_plano_gestao', null);




$ordem=getParam($_REQUEST, 'ordem', '0');
$salvaranexo=getParam($_REQUEST, 'salvaranexo', 0);
$excluiranexo=getParam($_REQUEST, 'excluiranexo', 0);



$grupo_id=getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());

if (!$grupo_id && !$grupo_id2) {
	$grupo_id=($Aplic->usuario_prefs['grupoid'] ? $Aplic->usuario_prefs['grupoid'] : null);
	$grupo_id2=($Aplic->usuario_prefs['grupoid2'] ? $Aplic->usuario_prefs['grupoid2'] : null);
	}
	
	
$sql = new BDConsulta;


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'agenda\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();



echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_agenda_aed" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="agenda_id" value="'.$agenda_id.'" />';
echo '</form>';


require_once (BASE_DIR.'/modulos/email/email.class.php');
$Aplic->carregarCalendarioJS();
$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');

$eh_conflito = isset($_SESSION['agenda_eh_conflito']) ? $_SESSION['agenda_eh_conflito'] : false;

if (isset($_REQUEST['agenda_tipo_id'])) $Aplic->setEstado('CalIdxAgenda_tipo', getParam($_REQUEST, 'agenda_tipo_id', null));
$agenda_tipo_id = $Aplic->getEstado('CalIdxAgenda_tipo', 0);

$data=getParam($_REQUEST, 'data', null);
$obj = new CAgenda();
$vazio=array();

//vindo de conflito
$objeto=getParam($_REQUEST, 'objeto', null);
if ($objeto) {
	$_REQUEST=unserialize(base64_decode($objeto));
	$obj->join($_REQUEST);
	$agenda_id=($obj->agenda_id ? $obj->agenda_id : null);
	$eh_conflito=true;
	}
else {
	$obj->load($agenda_id);
	$eh_conflito=false;
	}

$designado = array();
if ($eh_conflito) {
	$lista_designados=getParam($_REQUEST, 'agenda_designado', null);
	if (isset($lista_designados) && $lista_designados) {
		$sql->adTabela('usuarios', 'u');
		$sql->adTabela('contatos', 'con');
		$sql->esqUnir('cias', 'cias','con.contato_cia=cias.cia_id');
		$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' as nome_usuario, contato_funcao, cia_nome');
		$sql->adOnde('usuario_id IN ('.$lista_designados.')');
		$sql->adOnde('usuario_contato = contato_id');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$designado = $sql->lista();
		$sql->limpar();
		} 
	} 
elseif (!$agenda_id) $designado[] = array('nome_usuario'=> $Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra, 'usuario_id' => $Aplic->usuario_id, 'contato_funcao' => $Aplic->usuario_funcao, 'cia_nome' => nome_cia($Aplic->usuario_cia));
else {
	$sql->adTabela('agenda_usuarios', 'ue');
	$sql->esqUnir('usuarios', 'u', 'u.usuario_id=ue.usuario_id');
	$sql->esqUnir('contatos', 'con','u.usuario_contato=con.contato_id');
	$sql->esqUnir('cias', 'cias','con.contato_cia=cias.cia_id');
	$sql->esqUnir('agenda', 'e','e.agenda_id=ue.agenda_id');
	$sql->adCampo('u.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' as nome_usuario, contato_funcao, cia_nome');
	$sql->adOnde('e.agenda_id = '.(int)$agenda_id);
	$designado=$sql->Lista();
	$sql->limpar();
	}

$botoesTitulo = new CBlocoTitulo(($agenda_id ? 'Editar Compromisso' : 'Adicionar Compromisso'), 'compromisso.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$recorrencia = array('Nunca', 'A cada hora', 'Diario', 'Semanalmente', 'Quinzenal', 'Mensal', 'Quadrimensal', 'Semestral', 'Anual');
$lembrar = array('0'=>'', '900' => '15 mins', '1800' => '30 mins', '3600' => '1 hora', '7200' => '2 horas', '14400' => '4 horas', '28800' => '8 horas', '56600' => '16 horas', '86400' => '1 dia', '172800' => '2 dias');
	
$sql->adTabela('agenda_tipo');
$sql->adCampo('agenda_tipo_id, nome');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$sql->adOrdem('nome');
$agenda_tipos = $sql->listaVetorChave('agenda_tipo_id', 'nome');
$sql->limpar();
$agenda_tipos=array(null => '')+$agenda_tipos;

$sql->adTabela('agenda_tipo');
$sql->adCampo('agenda_tipo_id, cor');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$sql->adOrdem('nome');
$agenda_tipos_cor = $sql->Lista();
$sql->limpar();

echo '<script type="text/javascript">var valores_cor=new Array();';
foreach ($agenda_tipos_cor as $linha) echo 'valores_cor['.$linha['agenda_tipo_id'].']="'.$linha['cor'].'";';
echo '</script>';
	
$sql->adTabela('grupo');
$sql->esqUnir('grupo_permissao','gp1','gp1.grupo_permissao_grupo = grupo.grupo_id');
$sql->esqUnir('grupo_permissao','gp2','gp2.grupo_permissao_grupo=grupo.grupo_id AND gp2.grupo_permissao_usuario = '.$Aplic->usuario_id);
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia');
$sql->adCampo('COUNT(gp1.grupo_permissao_usuario) AS protegido');
$sql->adCampo('COUNT(gp2.grupo_permissao_usuario) AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_descricao ASC');
$sql->adGrupo('grupo.grupo_id, grupo_descricao, grupo_cia');
$achados=$sql->Lista();
$sql->limpar();

$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se há grupo privado da cia, se houver não haverá opção de ver todos o usuários da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) {
	$grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
	if (!$grupo_id && !$grupo_id2) $grupo_id=-1;
	}
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;


echo '<form name="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="email" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_agenda_aed" />';
echo '<input type="hidden" name="agenda_id" id="agenda_id" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="agenda_designado" value="" />';
echo '<input type="hidden" name="data" value="'.$data.'" />';
echo '<input type="hidden" name="retornar" value="" />';
echo '<input type="hidden" name="agenda_cia" id="agenda_cia" value="'.($agenda_id ? $obj->agenda_cia : $Aplic->usuario_cia).'" />';
echo '<input type="hidden" name="agenda_dono" value="'.($agenda_id ? $obj->agenda_dono : $Aplic->usuario_id).'" />';
$uuid=($agenda_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';

echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td align="right" width="100">'.dica('Nome', 'Qual o nome do compromisso. Cada compromisso deve ter um nome que facilite a compreensão do mesmo').'Nome:'.dicaF().'</td><td><input type="text" class="texto" size="25" name="agenda_titulo" value="'.$obj->agenda_titulo.'" style="width:400px;" maxlength="255" />*</td></tr>';
if ($exibir['agenda_descricao']) echo '<tr><td align="right" valign="middle" style="white-space: nowrap">'.dica('Descrição', 'Um resumo sobre o compromisso.').'Descrição:'.dicaF().'</td><td><textarea name="agenda_descricao" data-gpweb-cmp="ckeditor" cols="60" rows="2" style="width:400px;" class="textarea">'.$obj->agenda_descricao.'</textarea></td></tr>';

if (count($agenda_tipos)>1) echo '<tr><td align="right">'.dica('Agenda', 'Escolha em qual agenda deseja que este compromisso seja armazenado.').'Agenda:'.dicaF().'</td><td align="left">'.selecionaVetor($agenda_tipos, 'agenda_tipo', 'class="texto" style="width:400px;"', ($obj->agenda_tipo ? $obj->agenda_tipo : $agenda_tipo_id) ).'</td></tr>';
else echo '<input type="hidden" name="agenda_tipo" id="agenda_tipo" value="" />';

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
	}
asort($tipos);


if ($agenda_tarefa) $tipo='tarefa';
elseif ($agenda_projeto) $tipo='projeto';
elseif ($agenda_perspectiva) $tipo='perspectiva';
elseif ($agenda_tema) $tipo='tema';
elseif ($agenda_objetivo) $tipo='objetivo';
elseif ($agenda_fator) $tipo='fator';
elseif ($agenda_estrategia) $tipo='estrategia';
elseif ($agenda_meta) $tipo='meta';
elseif ($agenda_pratica) $tipo='pratica';
elseif ($agenda_acao) $tipo='acao';
elseif ($agenda_canvas) $tipo='canvas';
elseif ($agenda_risco) $tipo='risco';
elseif ($agenda_risco_resposta) $tipo='risco_resposta';
elseif ($agenda_indicador) $tipo='agenda_indicador';
elseif ($agenda_calendario) $tipo='calendario';
elseif ($agenda_monitoramento) $tipo='monitoramento';
elseif ($agenda_ata) $tipo='ata';
elseif ($agenda_mswot) $tipo='mswot';
elseif ($agenda_swot) $tipo='swot';
elseif ($agenda_operativo) $tipo='operativo';
elseif ($agenda_instrumento) $tipo='instrumento';
elseif ($agenda_recurso) $tipo='recurso';
elseif ($agenda_problema) $tipo='problema';
elseif ($agenda_demanda) $tipo='demanda';
elseif ($agenda_programa) $tipo='programa';
elseif ($agenda_licao) $tipo='licao';
elseif ($agenda_evento) $tipo='evento';
elseif ($agenda_link) $tipo='link';
elseif ($agenda_avaliacao) $tipo='avaliacao';
elseif ($agenda_tgn) $tipo='tgn';
elseif ($agenda_brainstorm) $tipo='brainstorm';
elseif ($agenda_gut) $tipo='gut';
elseif ($agenda_causa_efeito) $tipo='causa_efeito';
elseif ($agenda_arquivo) $tipo='arquivo';
elseif ($agenda_forum) $tipo='forum';
elseif ($agenda_checklist) $tipo='checklist';
elseif ($agenda_agenda) $tipo='agenda';
elseif ($agenda_agrupamento) $tipo='agrupamento';
elseif ($agenda_patrocinador) $tipo='patrocinador';
elseif ($agenda_template) $tipo='template';
elseif ($agenda_painel) $tipo='painel';
elseif ($agenda_painel_odometro) $tipo='painel_odometro';
elseif ($agenda_painel_composicao) $tipo='painel_composicao';
elseif ($agenda_tr) $tipo='tr';
elseif ($agenda_me) $tipo='me';
elseif ($agenda_acao_item) $tipo='acao_item';
elseif ($agenda_beneficio) $tipo='beneficio';
elseif ($agenda_painel_slideshow) $tipo='painel_slideshow';
elseif ($agenda_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($agenda_projeto_abertura) $tipo='projeto_abertura';
elseif ($agenda_plano_gestao) $tipo='plano_gestao';
else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado', 'A que área o compromisso está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($agenda_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_projeto" value="'.$agenda_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($agenda_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_tarefa" value="'.$agenda_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($agenda_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_perspectiva" value="'.$agenda_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($agenda_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_tema" value="'.$agenda_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($agenda_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_objetivo" value="'.$agenda_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($agenda_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_fator" value="'.$agenda_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($agenda_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_estrategia" value="'.$agenda_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($agenda_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_meta" value="'.$agenda_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($agenda_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_pratica" value="'.$agenda_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($agenda_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_acao" value="'.$agenda_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($agenda_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_canvas" value="'.$agenda_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($agenda_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_risco" value="'.$agenda_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($agenda_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_risco_resposta" value="'.$agenda_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($agenda_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_indicador" value="'.$agenda_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($agenda_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_calendario" value="'.$agenda_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($agenda_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_monitoramento" value="'.$agenda_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($agenda_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reunião', 'Caso seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_ata" value="'.(isset($agenda_ata) ? $agenda_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($agenda_ata) ? $agenda_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('icones/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja específico de uma matriz SWOT neste campo deverá constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_mswot" value="'.(isset($agenda_mswot) ? $agenda_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($agenda_mswot) ? $agenda_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ícone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja específico de um campo de matriz SWOT neste campo deverá constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_swot" value="'.(isset($agenda_swot) ? $agenda_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($agenda_swot) ? $agenda_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_operativo" value="'.$agenda_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($agenda_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_instrumento" value="'.$agenda_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($agenda_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_recurso" value="'.$agenda_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($agenda_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_problema" value="'.$agenda_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($agenda_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_demanda" value="'.$agenda_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($agenda_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_programa" value="'.$agenda_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($agenda_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja específico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo deverá constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_licao" value="'.$agenda_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($agenda_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_evento" value="'.$agenda_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($agenda_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_link" value="'.$agenda_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($agenda_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avaliação', 'Caso seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_avaliacao" value="'.$agenda_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($agenda_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_tgn" value="'.$agenda_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($agenda_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_brainstorm" value="'.$agenda_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($agenda_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja específico de uma matriz GUT, neste campo deverá constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_gut" value="'.$agenda_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($agenda_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_causa_efeito" value="'.$agenda_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($agenda_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_arquivo" value="'.$agenda_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($agenda_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('Fórum', 'Caso seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_forum" value="'.$agenda_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($agenda_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja específico de um checklist, neste campo deverá constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_checklist" value="'.$agenda_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($agenda_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_agenda" value="'.$agenda_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($agenda_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_agrupamento" value="'.$agenda_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($agenda_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja específico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo deverá constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_patrocinador" value="'.$agenda_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($agenda_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ícone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_template" value="'.$agenda_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($agenda_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_painel" value="'.$agenda_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($agenda_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Odômetro de Indicador', 'Caso seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_painel_odometro" value="'.$agenda_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($agenda_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composição de Painéis', 'Caso seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_painel_composicao" value="'.$agenda_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($agenda_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/composicao_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_tr" value="'.$agenda_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($agenda_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_me" value="'.$agenda_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($agenda_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja específico de um item de '.$config['acao'].', neste campo deverá constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_acao_item" value="'.$agenda_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($agenda_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_beneficio" value="'.$agenda_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($agenda_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composições', 'Caso seja específico de um slideshow de composições, neste campo deverá constar o nome do slideshow de composições.').'Slideshow de composições:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_painel_slideshow" value="'.$agenda_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($agenda_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composições','Clique neste ícone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composições.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja específico de um estudo de viabilidade, neste campo deverá constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_projeto_viabilidade" value="'.$agenda_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($agenda_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ícone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja específico de um termo de abertura, neste campo deverá constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_projeto_abertura" value="'.$agenda_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($agenda_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ícone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($agenda_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estratégico', 'Caso seja específico de um planejamento estratégico, neste campo deverá constar o nome do planejamento estratégico.').'Planejamento estratégico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="agenda_plano_gestao" value="'.$agenda_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($agenda_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';



if ($Aplic->profissional){
	$sql->adTabela('agenda_gestao');
	$sql->adCampo('agenda_gestao.*');
	if ($uuid) $sql->adOnde('agenda_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('agenda_gestao_agenda ='.(int)$agenda_id);	
	$sql->adOrdem('agenda_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
	echo '<tr><td></td><td><div id="combo_gestao">';
	if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		echo '<tr align="center">';
		echo '<td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['agenda_gestao_ordem'].', '.$gestao_data['agenda_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['agenda_gestao_ordem'].', '.$gestao_data['agenda_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['agenda_gestao_ordem'].', '.$gestao_data['agenda_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['agenda_gestao_ordem'].', '.$gestao_data['agenda_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		if ($gestao_data['agenda_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['agenda_gestao_tarefa']).'</td>';
		elseif ($gestao_data['agenda_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['agenda_gestao_projeto']).'</td>';
		elseif ($gestao_data['agenda_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['agenda_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['agenda_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['agenda_gestao_tema']).'</td>';
		elseif ($gestao_data['agenda_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['agenda_gestao_objetivo']).'</td>';
		elseif ($gestao_data['agenda_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['agenda_gestao_fator']).'</td>';
		elseif ($gestao_data['agenda_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['agenda_gestao_estrategia']).'</td>';
		elseif ($gestao_data['agenda_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['agenda_gestao_meta']).'</td>';
		elseif ($gestao_data['agenda_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['agenda_gestao_pratica']).'</td>';
		elseif ($gestao_data['agenda_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['agenda_gestao_acao']).'</td>';
		elseif ($gestao_data['agenda_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['agenda_gestao_canvas']).'</td>';
		elseif ($gestao_data['agenda_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['agenda_gestao_risco']).'</td>';
		elseif ($gestao_data['agenda_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['agenda_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['agenda_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['agenda_gestao_indicador']).'</td>';
		elseif ($gestao_data['agenda_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['agenda_gestao_calendario']).'</td>';
		elseif ($gestao_data['agenda_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['agenda_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['agenda_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['agenda_gestao_ata']).'</td>';
		elseif ($gestao_data['agenda_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['agenda_gestao_mswot']).'</td>';
		elseif ($gestao_data['agenda_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['agenda_gestao_swot']).'</td>';
		elseif ($gestao_data['agenda_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['agenda_gestao_operativo']).'</td>';
		elseif ($gestao_data['agenda_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['agenda_gestao_instrumento']).'</td>';
		elseif ($gestao_data['agenda_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['agenda_gestao_recurso']).'</td>';
		elseif ($gestao_data['agenda_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['agenda_gestao_problema']).'</td>';
		elseif ($gestao_data['agenda_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['agenda_gestao_demanda']).'</td>';
		elseif ($gestao_data['agenda_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['agenda_gestao_programa']).'</td>';
		elseif ($gestao_data['agenda_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['agenda_gestao_licao']).'</td>';
		elseif ($gestao_data['agenda_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['agenda_gestao_evento']).'</td>';
		elseif ($gestao_data['agenda_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['agenda_gestao_link']).'</td>';
		elseif ($gestao_data['agenda_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['agenda_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['agenda_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['agenda_gestao_tgn']).'</td>';
		elseif ($gestao_data['agenda_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['agenda_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['agenda_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['agenda_gestao_gut']).'</td>';
		elseif ($gestao_data['agenda_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['agenda_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['agenda_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['agenda_gestao_arquivo']).'</td>';
		elseif ($gestao_data['agenda_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['agenda_gestao_forum']).'</td>';
		elseif ($gestao_data['agenda_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['agenda_gestao_checklist']).'</td>';
		
		elseif ($gestao_data['agenda_gestao_semelhante']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['agenda_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['agenda_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['agenda_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['agenda_gestao_patrocinador']) echo '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['agenda_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['agenda_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['agenda_gestao_template']).'</td>';
		elseif ($gestao_data['agenda_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['agenda_gestao_painel']).'</td>';
		elseif ($gestao_data['agenda_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['agenda_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['agenda_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['agenda_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['agenda_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['agenda_gestao_tr']).'</td>';	
		elseif ($gestao_data['agenda_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['agenda_gestao_me']).'</td>';	
		elseif ($gestao_data['agenda_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['agenda_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['agenda_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['agenda_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['agenda_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['agenda_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['agenda_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['agenda_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['agenda_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['agenda_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['agenda_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['agenda_gestao_plano_gestao']).'</td>';	
	
		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['agenda_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) echo '</table>';
	echo '</div></td></tr>';
	}


$inicio = 0;
$fim = 24;
$inc = 1;
$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	$horas[$chave_atual] = $atual;
	}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;


$data_inicio = intval($obj->agenda_inicio) ? new CData($obj->agenda_inicio) : false;
$data_fim = intval($obj->agenda_fim) ? new CData( $obj->agenda_fim ) : false;
if($Aplic->profissional && (!$data_inicio || !$data_fim)){
	require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
	$cache = CTarefaCache::getInstance();
	$exped = $cache->getExpedienteParaHoje((int)$cia_id, null, null, ($data ? substr($data, 0, 4).'-'.substr($data, 4, 2).'-'.substr($data, 6, 2): null));
	if(!$data_inicio){
		$data_inicio = $exped['inicio'];
		}
	if(!$data_fim){
		$desloc = $config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8;
		$data_fim = $cache->deslocaDataPraFrente($data_inicio, $desloc, (int)$cia_id);
		$data_fim = $cache->ajustaInicioPeriodo($data_fim, (int)$cia_id);
		}
	$obj->agenda_duracao = $cache->horasPeriodo($data_inicio, $data_fim, (int)$cia_id);
	if(is_string($data_inicio)) $data_inicio = new CData($data_inicio);
	if(is_string($data_fim)) $data_fim = new CData($data_fim);
	}
if (!$data_inicio) $data_inicio= new CData();
if (!$data_fim) $data_fim = new CData();

echo '<input name="agenda_inicio" id="agenda_inicio" type="hidden" value="'.$data_inicio->format('%Y-%m-%d %H:%M:%S').'" />';
echo '<input name="agenda_fim" id="agenda_fim" type="hidden" value="'.$data_fim->format('%Y-%m-%d %H:%M:%S').'" />';
echo '<tr><td align=right width="100" >'.dica('Data de Início', 'A data de início do compromisso.').'Início:'.dicaF().'</td><td><input type="hidden" id="oculto_data_inicio" name="oculto_data_inicio"  value="'.$data_inicio->format('%Y-%m-%d').'" /><input type="text" onchange="setData(\'env\', \'data_inicio\'); data_ajax();" class="texto" style="width:70px;" id="data_inicio" name="data_inicio" value="'.$data_inicio->format('%d/%m/%Y').'" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data de início.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário"" border=0 /></a>'.dicaF().dica('Hora do Início', 'Selecione na caixa de seleção a hora do ínicio do compromisso.'). selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="CompararDatas(); data_ajax();" class="texto"', $data_inicio->getHour()).' : '.dica('Minutos do Início', 'Selecione na caixa de seleção os minutos do ínicio do compromisso.').selecionaVetor($minutos, 'inicio_minuto', 'size="1" class="texto" onchange="CompararDatas(); data_ajax();"', $data_inicio->getMinute()).'</td></tr>';
echo '<tr><td align=right>'.dica('Data de Término', 'A data de término do compromisso.').'Término:'.dicaF().'</td><td><input type="hidden" id="oculto_data_fim" name="oculto_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'env\', \'data_fim\'); horas_ajax();" class="texto" style="width:70px;" id="data_fim" name="data_fim" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" />'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário"" border=0 /></a>'.dicaF().dica('Hora do Término', 'Selecione na caixa de seleção a hora do término.</p>Caso não saiba a hora provável de término, deixe em branco este campo e clique no botão <b>Data de Término</b>').selecionaVetor($horas, 'fim_hora', 'size="1" onchange="CompararDatas(); horas_ajax();" class="texto"', $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do Término', 'Selecione na caixa de seleção os minutos do término. </p>Caso não saiba os minutos prováveis de término, deixe em branco este campo e clique no botão <b>Data de Término</b>').selecionaVetor($minutos, 'fim_minuto', 'size="1" class="texto" onchange="CompararDatas(); horas_ajax();"', $data_fim ? $data_fim->getMinute() : '00').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('A duração do compromisso em dias.').'Duração:'.dicaF().'</td><td nowrap="nowrap"><input type="text" onchange="data_ajax();" onkeypress="return somenteFloat(event)" class="texto" name="agenda_duracao" id="agenda_duracao" maxlength="30" size="2" value="'.float_brasileiro((float)$obj->agenda_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)).'" />&nbsp;dias</td></tr>';



echo '<tr><td align="right">'.dica('Recorrência', 'De quanto em quanto tempo este compromisso se repete.').'Recorrência:'.dicaF().'</td><td>'.selecionaVetor($recorrencia, 'agenda_recorrencias', 'size="1" class="texto"', $obj->agenda_recorrencias).dica('Número de Recorrencias', 'Escolha o número de vezes que a faixa de tempo escolhida repetirá.').'x'.dicaF().'<input type="text" class="texto" name="agenda_nr_recorrencias" value="'.((isset($obj->agenda_nr_recorrencias)) ? ($obj->agenda_nr_recorrencias) : '1').'" maxlength="2" size="3" />'.dica('Número de Recorrencias', 'Escolha o número de vezes que a faixa de tempo escolhida repetirá.').'vezes'.dicaF().'</td></tr>';
echo '<tr><td align="right">'.dica('Lembrar', 'Envio de mensagem para lembrar do compromisso.').'Lembrar:'.dicaF().'</td><td>'.selecionaVetor($lembrar, 'agenda_lembrar', 'size="1" class="texto"', $obj->agenda_lembrar).' antes</td></tr>';


echo '<tr><td align="right">'.dica('Dias Úteis', 'Marque esta caixa para que a faixa de tempo do compromisso não inclua os fim-de-semana.').'<label for="agenda_diautil">Dias úteis:</label>'.dicaF().'</td><td><input type="checkbox" value="1" name="agenda_diautil" id="agenda_diautil" '.($obj->agenda_diautil ? 'checked="checked"' : '').' /></td></tr>';




echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milhões possíveis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inserção do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="agenda_cor" id="agenda_cor" value="'.($obj->agenda_cor ? $obj->agenda_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';


echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o compromisso ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="agenda_ativo" '.($obj->agenda_ativo || !$agenda_id ? 'checked="checked"' : '').' /></td></tr>';



	
require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados('agenda', $obj->agenda_id, 'editar');
$campos_customizados->imprimirHTML();
	
		
$cincow2h=($exibir['agenda_oque'] && $exibir['agenda_quem'] && $exibir['agenda_quando'] && $exibir['agenda_onde'] && $exibir['agenda_porque'] && $exibir['agenda_como'] && $exibir['agenda_quanto']);

if ($cincow2h){
	echo '<tr><td style="height:1px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}

if ($exibir['agenda_oque']) echo '<tr><td align="right" style="white-space: nowrap;width:150px">'.dica('O Que Fazer', 'Sumário sobre o que se trata este agenda.').'O Que:'.dicaF().'</td><td colspan="2"><textarea name="agenda_oque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->agenda_oque.'</textarea></td></tr>';
if ($exibir['agenda_quem']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quem', 'Quem executar o agenda.').'Quem:'.dicaF().'</td><td colspan="2"><textarea name="agenda_quem" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->agenda_quem.'</textarea></td></tr>';
if ($exibir['agenda_quando']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quando Fazer', 'Quando o agenda é executado.').'Quando:'.dicaF().'</td><td colspan="2"><textarea name="agenda_quando" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->agenda_quando.'</textarea></td></tr>';
if ($exibir['agenda_onde']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Onde Fazer', 'Onde o agenda é executado.').'Onde:'.dicaF().'</td><td colspan="2"><textarea name="agenda_onde" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->agenda_onde.'</textarea></td></tr>';
if ($exibir['agenda_porque']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Por Que Fazer', 'Por que o agenda será executado.').'Por que:'.dicaF().'</td><td colspan="2"><textarea name="agenda_porque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->agenda_porque.'</textarea></td></tr>';
if ($exibir['agenda_como']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Como Fazer', 'Como o agenda é executado.').'Como:'.dicaF().'</td><td colspan="2"><textarea name="agenda_como" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->agenda_como.'</textarea></td></tr>';
if ($exibir['agenda_quanto']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quanto Custa', 'Custo para executar o agenda.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea name="agenda_quanto" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->agenda_quanto.'</textarea></td></tr>';

if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	}		
		
		
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'participantes\').style.display) document.getElementById(\'participantes\').style.display=\'\'; else document.getElementById(\'participantes\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Convidados</b></a></td></tr>';
echo '<tr id="participantes" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width=100%>';














echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td align=right width=100>'.dica('Exibição', 'Forma de apresentar os ').'Exibição:'.dicaF().'</td><td><input type="radio" name="modo_exibicao" value="dept" id="dept" onChange="mudar_usuarios_designados()" checked>'.ucfirst($config['departamento']).'<input type="radio" name="modo_exibicao" value="simples" id="simples" onChange="mudar_usuarios_designados();">Lista simples</td></tr>';
$sql->adTabela('grupo');
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia, (SELECT COUNT(grupo_permissao_usuario) FROM grupo_permissao AS gp1 WHERE gp1.grupo_permissao_grupo=grupo.grupo_id) AS protegido, (SELECT COUNT(grupo_permissao_usuario) FROM grupo_permissao AS gp2 WHERE gp2.grupo_permissao_grupo=grupo.grupo_id AND gp2.grupo_permissao_usuario='.(int)$Aplic->usuario_id.') AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
$achados=$sql->Lista();
$sql->limpar();
$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se há grupo privado da cia, se houver não haverá opção de ver todos o usuários da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) {
	$grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
	if (!$grupo_id && !$grupo_id2) $grupo_id=-1;
	}
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;

if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align=right width=100>'.dica(ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia_designados">'.selecionar_om($Aplic->usuario_cia, 'cia_designados', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td></tr>';

if (!$grupo_id && !$grupo_id2) {
	$grupo_id=($Aplic->usuario_prefs['grupoid'] ? $Aplic->usuario_prefs['grupoid'] : null);
	$grupo_id2=($Aplic->usuario_prefs['grupoid2'] ? $Aplic->usuario_prefs['grupoid2'] : null);
	}
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo','Escolha '.$config['usuarios'].' incluíd'.$config['genero_usuario'].'s em um dos grupos.').'Grupo:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:400px" class="texto" onchange="env.grupo_b.value=0; mudar_usuarios_designados()"',$grupo_id).'</td></tr>';
else echo '<input type="hidden" name="grupo_a" id="grupo_a" value="" />';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.(int)$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'')+$grupos;
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo Particular','Escolha '.$config['usuarios'].' incluíd'.$config['genero_usuario'].'s em um dos seus grupos particulares.').'Grupo Particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_b', 'style="width:400px" size="1" class="texto" onchange="env.grupo_a.value=0; mudar_usuarios_designados();"',$grupo_id2).'</td></tr>';
else echo '<input type="hidden" name="grupo_b" id="grupo_b" value="" />';
echo '<tr><td align=right width=100>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:400px;" name="busca" id="busca" onchange="env.grupo_a.value=0; env.grupo_b.value=0; mudar_usuarios_designados();" value=""/></td><td><a href="javascript:void(0);" onclick="env.busca.value=\'\'; mudar_usuarios_designados()">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
echo '</table></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';

echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0>';
echo '<tr><td style="text-align:left" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['usuarios']),'Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para adiciona-lo à lista de destinatário.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão INCLUIR.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;'.ucfirst($config['usuarios']).'&nbsp</legend>';
echo '<div id="combo_de">';
if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
else {
	echo '<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';

	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('grupo_usuario','grupo_usuario','grupo_usuario_usuario=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
		$sql->adOnde('usuario_ativo=1');
		if ($grupo_id2) $sql->adOnde('grupo_usuario_grupo='.$grupo_id2);
		elseif ($grupo_id > 0) $sql->adOnde('grupo_usuario_grupo='.$grupo_id);
		elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
		$sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor');
		$usuarios = $sql->Lista();
		$sql->limpar();
   	foreach ($usuarios as $rs)	 echo '<option value="'.$rs['usuario_id'].'">'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').'</option>';
    }
	echo '</select>';
	}
echo '</div></fieldset>';
echo '</td>';






echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Chamar','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos convidados.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Chamar</b>&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;">';
foreach($designado as $rs) echo '<option value='.$rs['usuario_id'].'>'.($Aplic->usuario_prefs['nomefuncao'] ? $rs['nome_usuario'].($rs['contato_funcao'] && $rs['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '').($rs['nome_usuario'] && $rs['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').$rs['nome_usuario']).' - '.$rs['cia_nome'].'</option>';
echo '</select></fieldset></td></tr>';






echo '<tr><td class=CampoJanela style="text-align:center"><table width="50%"><tr><td width="150"  style="white-space: nowrap">'.dica('Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa dos chamados.').'<a class="botao" href="javascript:Mover(env.ListaDE, env.ListaPARA)"><span>incluir >></span></a></td><td width="200"  style="white-space: nowrap">'.dica('Incluir Todos','Clique neste botão para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a class="botao" href="javascript:btSelecionarTodos_onclick()"><span>incluir todos</span></a>'.dicaF().'</td></tr></table></td><td style="text-align:center"><table><tr><td>'.dica("Remover","Clique neste botão para remover os destinatários selecionados da caixa os chamados.").'<a class="botao" href="javascript:Mover2(env.ListaPARA, env.ListaDE)"><span><< remover</span></a></td><td width=230>&nbsp;</td></tr></table></td></tr>';
echo '<tr><td align="right">'.dica('Notificar', 'Marque esta caixa para avisar '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados para o compromisso.').'<label for="email_convidado">Notificar:</label>'.dicaF().'</td><td><input type="checkbox" name="email_convidado" id="email_convidado" '.($Aplic->usuario_prefs['tarefaemailreg']&8 ? 'checked="checked"' : '').' /></td></tr>';
echo '</table></td></tr>';
echo '</table></fieldset></td></tr>';
echo '</form>';	


echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Retornar à tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr>';
echo '</table></td></tr></table>';
echo estiloFundoCaixa();
?>
<script type="text/javascript">

function mudar_usuarios_designados(){
	var tipo=document.env.modo_exibicao.value;
	grupo=document.getElementById('grupo_b').value;
	if (!grupo|| grupo==0) grupo=document.getElementById('grupo_a').value;
	if (grupo==-1) grupo=null;
	if (tipo=='dept')	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'ListaDE', 'combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"', document.getElementById('busca').value, grupo);
	else xajax_mudar_usuario_grupo_ajax(grupo, document.getElementById('busca').value);
	}
	
function mudar_om_designados(){
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1);
	}	
	
	
	
	
	
	
	
	
	
function mudar_om_designados(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1); 	
	}
	
function Mover(ListaDE,ListaPARA) {
	//checar se já existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value > 0) {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text.replace(/(^[\s]+|[\s]+$)/g, '');
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) { 
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA.options[ListaPARA.options.length] = no;		
				}
			}
		}
	}

function Mover2(ListaPARA,ListaDE) {
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value > 0) {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista de destinatários e efetua o submit
function selecionar() {
	for (var i=0; i < env.ListaPARA.length ; i++) {
		env.ListaPARA.options[i].selected = true;
		}
	}

// Seleciona todos os campos da lista de usuários
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
	}
	Mover(env.ListaDE, env.ListaPARA);
}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir o compromisso?" )) document.frmExcluir.submit();
	}

	
function enviarDados(){
	var form = document.env;
	if (form.agenda_titulo.value.length < 3) {
		alert('Insira o nome do compromisso');
		form.agenda_titulo.focus();
		return;
		}
	if (form.agenda_inicio.value.length < 3){
		alert('Insira a data de ínicio');
		form.agenda_inicio.focus();
		return;
		}
	if (form.agenda_fim.value.length < 3){
		alert('Insira a data de término');
		form.agenda_fim.focus();
		return;
		}
	if ( (!(form.agenda_nr_recorrencias.value>0)) 
		&& (form.agenda_recorrencias[0].selected!=true) ) {
		alert('Insira o número de recorrências');
		form.agenda_nr_recorrencias.value=1;
		form.agenda_nr_recorrencias.focus();
		return;
		} 
	var designado = form.ListaPARA;
	var len = designado.length;
	var usuarios = form.agenda_designado;
	usuarios.value = '';
	for (var i = 0; i < len; i++) {
		if (i) usuarios.value += ',';
		usuarios.value += designado.options[i].value;
		}
		
	document.getElementById('agenda_inicio').value=document.getElementById('oculto_data_inicio').value+' '+document.getElementById('inicio_hora').value+':'+document.getElementById('inicio_minuto').value+':00';
	document.getElementById('agenda_fim').value=document.getElementById('oculto_data_fim').value+' '+document.getElementById('fim_hora').value+':'+document.getElementById('fim_minuto').value+':00';	
		
		
	form.submit();
	}




var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "oculto_data_inicio",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    data_ajax();
	    }
		cal1.hide();
		}
	});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "oculto_data_fim",
	date : <?php echo $data_fim->format("%Y-%m-%d")?>,
	selection : <?php echo $data_fim->format("%Y-%m-%d")?>,
  onSelect : function(cal2) {
	  var date = cal2.selection.get();
	  if (date){
	    date = Calendario.intToDate(date);
	    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    horas_ajax();
	    }
		cal2.hide();
		}
	});


function CompararDatas(){
  var str1 = document.getElementById("oculto_data_inicio").value
  var dia1  = parseInt(str1.substring(8,10),10);
  var mes1 = parseInt(str1.substring(5,7),10);
  var ano1  = parseInt(str1.substring(0,4),10);
  var hora1  = parseInt(document.getElementById("inicio_hora").value,10);
	var minuto1  = parseInt(document.getElementById("inicio_minuto").value,10);
	var data1 = new Date(ano1, mes1, dia1, hora1, minuto1);

	var str2 = document.getElementById("oculto_data_fim").value
  var dia2  = parseInt(str2.substring(8,10),10);
  var mes2 = parseInt(str2.substring(5,7),10);
  var ano2  = parseInt(str2.substring(0,4),10);
  var hora2  = parseInt(document.getElementById("fim_hora").value,10);
	var minuto2  = parseInt(document.getElementById("fim_minuto").value,10);
	var data2 = new Date(ano2, mes2, dia2, hora2, minuto2);
  if(data2 < data1){
    document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
    document.getElementById("oculto_data_fim").value=document.getElementById("oculto_data_inicio").value;
    document.getElementById("fim_minuto").value=document.getElementById("inicio_minuto").value;
    document.getElementById("fim_hora").value=document.getElementById("inicio_hora").value;
  	}
	}

function setData(frm_nome, f_data) {
	campo_data = eval( 'document.'+frm_nome+'.'+f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.'+'oculto_'+f_data );
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
      //data final fazer ao menos no mesmo dia da inicial
      CompararDatas();
			}
		}
	else campo_data_real.value = '';
	}

function horas_ajax(){
	var f=document.env;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minuto.value+':00';
	var fim=f.oculto_data_fim.value+' '+f.fim_hora.value+':'+f.fim_minuto.value+':00';
	xajax_calcular_duracao(inicio, fim, document.getElementById('agenda_cia').value);
	}

function data_ajax(){
	var f=document.env;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minuto.value+':00';
	var horas=f.agenda_duracao.value;
	xajax_data_final_periodo(inicio, horas, document.getElementById('agenda_cia').value);
	}

function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}





function setCor(cor) {
	var f = document.env;
	if (cor) f.agenda_cor.value = cor;
	document.getElementById('agenda_cor').style.background = '#' + f.agenda_cor.value;
	}
	
<?php if (count($agenda_tipos)>1) echo 'if (env.agenda_tipo.options[env.agenda_tipo.selectedIndex].value) setCor(valores_cor[env.agenda_tipo.options[env.agenda_tipo.selectedIndex].value]);' ?>	


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
	}
	
	


	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('agenda_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('agenda_cia').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.agenda_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('agenda_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.agenda_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('agenda_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('agenda_cia').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.agenda_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('agenda_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.agenda_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('agenda_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.agenda_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('agenda_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.agenda_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('agenda_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.agenda_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('agenda_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.agenda_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('agenda_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.agenda_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('agenda_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.agenda_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('agenda_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.agenda_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('agenda_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.agenda_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('agenda_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('agenda_cia').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.agenda_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('agenda_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.agenda_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('agenda_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.agenda_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('agenda_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.agenda_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('agenda_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('agenda_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.agenda_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('agenda_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('agenda_cia').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.agenda_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('agenda_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('agenda_cia').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.agenda_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('agenda_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.agenda_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('agenda_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.agenda_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Camçpo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('agenda_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.agenda_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('agenda_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('agenda_cia').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.agenda_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('agenda_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('agenda_cia').value, 'Instrumento Jurídico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.agenda_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('agenda_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('agenda_cia').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.agenda_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('agenda_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.agenda_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('agenda_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('agenda_cia').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.agenda_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('agenda_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.agenda_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('agenda_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.agenda_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('agenda_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('agenda_cia').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.agenda_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('agenda_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('agenda_cia').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.agenda_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('agenda_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('agenda_cia').value, 'Avaliação','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.agenda_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('agenda_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.agenda_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}

function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('agenda_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('agenda_cia').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.agenda_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('agenda_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('agenda_cia').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.agenda_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('agenda_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('agenda_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.agenda_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('agenda_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('agenda_cia').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.agenda_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('agenda_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('agenda_cia').value, 'Fórum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.agenda_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('agenda_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('agenda_cia').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.agenda_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('agenda_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('agenda_cia').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.agenda_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('agenda_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('agenda_cia').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.agenda_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('agenda_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('agenda_cia').value, 'Odômetro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.agenda_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('agenda_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('agenda_cia').value, 'Composição de Painéis','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.agenda_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('agenda_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.agenda_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('agenda_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.agenda_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('agenda_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('agenda_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.agenda_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('agenda_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('agenda_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.agenda_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('agenda_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('agenda_cia').value, 'Slideshow de Composições','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.agenda_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('agenda_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('agenda_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.agenda_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('agenda_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('agenda_cia').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.agenda_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('agenda_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('agenda_cia').value, 'Planejamento Estratégico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.agenda_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.agenda_projeto.value = null;
	document.env.agenda_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.agenda_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.agenda_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.agenda_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.agenda_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.agenda_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.agenda_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.agenda_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.agenda_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.agenda_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.agenda_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.agenda_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.agenda_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.agenda_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.agenda_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.agenda_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.agenda_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.agenda_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.agenda_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.agenda_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.agenda_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.agenda_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.agenda_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.agenda_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.agenda_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.agenda_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.agenda_link.value = null;
	document.env.link_nome.value = '';
	document.env.agenda_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.agenda_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.agenda_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.agenda_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.agenda_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.agenda_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.agenda_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.agenda_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.agenda_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.agenda_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.agenda_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.agenda_template.value = null;
	document.env.template_nome.value = '';
	document.env.agenda_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.agenda_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.agenda_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.agenda_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.agenda_me.value = null;
	document.env.me_nome.value = '';
	document.env.agenda_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.agenda_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.agenda_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.agenda_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.agenda_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.agenda_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('agenda_id').value,
	document.getElementById('uuid').value,
	f.agenda_projeto.value,
	f.agenda_tarefa.value,
	f.agenda_perspectiva.value,
	f.agenda_tema.value,
	f.agenda_objetivo.value,
	f.agenda_fator.value,
	f.agenda_estrategia.value,
	f.agenda_meta.value,
	f.agenda_pratica.value,
	f.agenda_acao.value,
	f.agenda_canvas.value,
	f.agenda_risco.value,
	f.agenda_risco_resposta.value,
	f.agenda_indicador.value,
	f.agenda_calendario.value,
	f.agenda_monitoramento.value,
	f.agenda_ata.value,
	f.agenda_mswot.value,
	f.agenda_swot.value,
	f.agenda_operativo.value,
	f.agenda_instrumento.value,
	f.agenda_recurso.value,
	f.agenda_problema.value,
	f.agenda_demanda.value,
	f.agenda_programa.value,
	f.agenda_licao.value,
	f.agenda_evento.value,
	f.agenda_link.value,
	f.agenda_avaliacao.value,
	f.agenda_tgn.value,
	f.agenda_brainstorm.value,
	f.agenda_gut.value,
	f.agenda_causa_efeito.value,
	f.agenda_arquivo.value,
	f.agenda_forum.value,
	f.agenda_checklist.value,
	f.agenda_agenda.value,
	f.agenda_agrupamento.value,
	f.agenda_patrocinador.value,
	f.agenda_template.value,
	f.agenda_painel.value,
	f.agenda_painel_odometro.value,
	f.agenda_painel_composicao.value,
	f.agenda_tr.value,
	f.agenda_me.value,
	f.agenda_acao_item.value,
	f.agenda_beneficio.value,
	f.agenda_painel_slideshow.value,
	f.agenda_projeto_viabilidade.value,
	f.agenda_projeto_abertura.value,
	f.agenda_plano_gestao.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(agenda_gestao_id){
	xajax_excluir_gestao(document.getElementById('agenda_id').value, document.getElementById('uuid').value, agenda_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, agenda_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, agenda_gestao_id, direcao, document.getElementById('agenda_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$agenda_id && (
	$agenda_tarefa || 
	$agenda_projeto || 
	$agenda_perspectiva || 
	$agenda_tema || 
	$agenda_objetivo || 
	$agenda_fator || 
	$agenda_estrategia || 
	$agenda_meta || 
	$agenda_pratica || 
	$agenda_acao || 
	$agenda_canvas || 
	$agenda_risco || 
	$agenda_risco_resposta || 
	$agenda_indicador || 
	$agenda_calendario || 
	$agenda_monitoramento || 
	$agenda_ata || 
	$agenda_mswot || 
	$agenda_swot || 
	$agenda_operativo || 
	$agenda_instrumento || 
	$agenda_recurso || 
	$agenda_problema || 
	$agenda_demanda || 
	$agenda_programa || 
	$agenda_licao || 
	$agenda_evento || 
	$agenda_link || 
	$agenda_avaliacao || 
	$agenda_tgn || 
	$agenda_brainstorm || 
	$agenda_gut || 
	$agenda_causa_efeito || 
	$agenda_arquivo || 
	$agenda_forum || 
	$agenda_checklist || 
	$agenda_agenda || 
	$agenda_agrupamento || 
	$agenda_patrocinador || 
	$agenda_template || 
	$agenda_painel || 
	$agenda_painel_odometro || 
	$agenda_painel_composicao || 
	$agenda_tr || 
	$agenda_me || 
	$agenda_acao_item || 
	$agenda_beneficio || 
	$agenda_painel_slideshow || 
	$agenda_projeto_viabilidade || 
	$agenda_projeto_abertura || 
	$agenda_plano_gestao
	)) echo 'incluir_relacionado();';
	?>	
		


</script>
