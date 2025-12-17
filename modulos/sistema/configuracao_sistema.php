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
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$modulo=array('email'=>'E-mail', 'calendario'=>'Calendário', 'praticas'=> ucfirst($config['praticas']), 'projetos'=>ucfirst($config['projetos']));
$vazio=array();
include_once BASE_DIR.'/localidades/pt/sistema.php';

echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';

$sql = new BDConsulta;
$sql->adTabela('modulos');
$sql->adCampo('mod_diretorio');
$sql->adOnde('mod_tipo !="core"');
$sql->adOnde('mod_ativo=1');
$sql->adOrdem('mod_ui_ordem, mod_ui_nome');
$extras = $sql->carregarColuna();
$sql->limpar();
foreach($extras as $extra) {
	if (is_file(BASE_DIR.'/modulos/'.$extra.'/sistema.php')) include_once BASE_DIR.'/modulos/'.$extra.'/sistema.php';
	}

if (!$Aplic->usuario_super_admin)	$Aplic->redirecionar('m=publico&a=acesso_negado');
$Apliccfg = new CConfig();

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ConfiguracaoSistemaTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('ConfiguracaoSistemaTab') !== null ? $Aplic->getEstado('ConfiguracaoSistemaTab') : 0;
$ativo = intval(!$Aplic->getEstado('ConfigIdxTab'));

//verificar se está instalado em servidor da gpweb compartilhado
$bronze=(isset($config['bronze']) && $config['bronze'] ? 1 : 0);

if ($bronze){
	
	$vetor_trinta=array();
	$vetor_vinte=array();
	
	for($i=20; $i>0; $i--) $vetor_vinte[$i]=$i;
	for($i=30; $i>0; $i--) $vetor_trinta[$i]=$i;
	}

$botoesTitulo = new CBlocoTitulo('Configuração do Sistema', 'config-sistema.png', $m);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

$Aplic->salvarPosicao();


echo '<form name="cfgFrm" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="dialogo" type="hidden" value="1" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sistemaconfig_aed" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%" align="center">';
echo '<tr><td colspan="2">';
echo 'As variáveis abaixo tem um impacto direto no funcionamento do sistema. Uma configuração incorreta poderá tornar o '.$config['gpweb'].' inoperante.<br><br>';

if (is_dir(BASE_DIR.'/instalacao')) {
	$Aplic->setMsg(dica('Exclua Instalação', 'Exclua no servidor a pasta de instalação.<ul><li>'.BASE_DIR.'\<b>instalacao</b></li></ul>').'<a href="javascript: void(0);">Você não removeu o diretório de instalação.</a>'.dicaF(), UI_MSG_ALERTA);
	echo '<span class="error">'.$Aplic->getMsg().'</span>';
	}

if (!function_exists('openssl_sign')) {
	$Aplic->setMsg(dica('Instale o OpenSSL', 'Para instalar a biblioteca OpenSSL, abra o arquivo php.ini e descomente (tirar o ponto-virgula da frente) ou insira a linha extension=php_openssl.dll.').'<a href="javascript: void(0);">Você não instalou a biblioteca OpenSSL. Não poderá assinar eletrônicamente nem enviar e-mail criptografado com chave pública!</a>'.dicaF(), UI_MSG_ALERTA);
	echo '<span class="error">'.$Aplic->getMsg().'</span>';
	}	

if (!is_writable($base_dir.'/arquivos')) {
	$Aplic->setMsg(dica('Dê Permissão de Escrita', 'No ambiente linux basta dar o comando chmod 666 na pasta<br>'.$base_dir.'\arquivos').'<a href="javascript: void(0);">A pasta '.BASE_DIR.'\arquivos não está com permissão de escrita! Não conseguirá anexar arquivos d'.$config['genero_projeto'].'s '.$config['projetos'].'</a>'.dicaF(), UI_MSG_ALERTA);
	echo '<span class="error">'.$Aplic->getMsg().'</span>';
	}	

if (!is_writable($base_dir.'/'.$config['pasta_anexos'])) {
	$Aplic->setMsg(dica('Dê Permissão de Escrita', 'No ambiente linux basta dar o comando chmod 666 na pasta<br>'.$base_dir.'\\'.$config["pasta_anexos"]).'<a href="javascript: void(0);">A pasta '.$base_dir.'\\'.$config["pasta_anexos"].' não está com permissão de escrita! Não conseguirá anexar arquivos nos e-mails</a>'.dicaF(), UI_MSG_ALERTA);
	echo '<span class="error">'.$Aplic->getMsg().'</span>';
	}	

$Aplic->setMsg(dica('Aviso de Compromisso', 'Para que o alarme dos avisos de compromissos e eventos possa funcionar é necessário criar um trabalho Cron (linux) para chamar a cada 10 minutos o script '.$config['gpweb'].'/codigo/lista_espera.php<br><br> ex:  wget -O- '.get_protocol().'seu.dominio/'.$config['gpweb'].'/codigo/lista_espera.php').'<a href="javascript: void(0);">Não esqueça de configurar o sistema de alarme de avisos do '.$config['gpweb'].'!</a>'.dicaF(), UI_MSG_AVISO);
echo '<span class="error">'.$Aplic->getMsg().'</span>';
echo '</td></tr>';

$ultimo_grupo = '';
$rs = $Apliccfg->carregarTudo('config_grupo');


$parametros=array();

foreach ($rs as $c){
	$popup =  (isset($traducao[$c['config_nome'].'_dica']) ? $traducao[$c['config_nome'].'_dica'] : '');
	$valor = '';
	$extra='';
	switch ($c['config_tipo']){
		case 'select':
			if ($c['config_nome']=='ldap_perfil'){
				$sql->adTabela('perfil');
				$sql->adCampo('perfil.*');
				$perfis=$sql->lista();
				$sql->limpar();
				$perfis_arr = array();
				$i=0;
				foreach ($perfis as $perfil) if ($i++) $perfis_arr[$perfil['perfil_id']] = $perfil['perfil_nome'];
				$entrada=selecionaVetor($perfis_arr, 'cfg['.$c['config_nome'].']', 'style="width:300px;" size="1" class="texto"', $c['config_valor']);
				}
			elseif ($c['config_nome']=='externo_perfil'){
				$sql->adTabela('perfil');
				$sql->adCampo('perfil.*');
				$perfis=$sql->lista();
				$sql->limpar();
				$perfis_arr = array();
				$i=0;
				foreach ($perfis as $perfil) if ($i++) $perfis_arr[$perfil['perfil_id']] = $perfil['perfil_nome'];
				$entrada=selecionaVetor($perfis_arr, 'cfg['.$c['config_nome'].']', 'style="width:300px;" size="1" class="texto"', $c['config_valor']);
				}		
			else {
				$entrada = '<select class="texto" style="width:300px;" name="cfg['.$c['config_nome'].']">';
				$subordinada = $Apliccfg->getSubordinada($c['config_nome']);
				foreach ($subordinada as $sub) {
					$entrada .= '<option value="'.$sub['config_lista_nome'].'"'.($sub['config_lista_nome'] == $c['config_valor'] ? ' selected="selected" ' : '').'>'.(isset($traducao[$c['config_nome'].'_'.$sub['config_lista_nome'].'_item_titulo']) ? $traducao[$c['config_nome'].'_'.$sub['config_lista_nome'].'_item_titulo'] : (isset($traducao[$sub['config_lista_nome'].'_item_titulo']) ? $traducao[$sub['config_lista_nome'].'_item_titulo']  : '')).'</option>';
					}
				$entrada .= '</select>';
				}	
			break;
			
		case 'combo_calendario':
			$sql->adTabela('jornada');
			$sql->adCampo('jornada_id, jornada_nome');
			$sql->adOrdem('jornada_nome'); 
			$calendarios=$sql->listaVetorChave('jornada_id','jornada_nome');
			$sql->limpar();
			$entrada=selecionaVetor($calendarios, 'cfg['.$c['config_nome'].']', 'class="texto" style="width:300px;"', $c['config_valor']);
			break;		
			
		case 'combo_cor':
			$entrada = '<input class="jscolor" name="cfg['.$c['config_nome'].']" value="'.($c['config_valor']? $c['config_valor'] : 'FFFFFF').'" size="6" maxlength="6" style="width:57px;" />';
			break;	
			

		case 'checkbox':
			$extra = ($c['config_valor'] == 'true') ? 'checked="checked"': '';
			$valor = 'true';
			$entrada = '<input class="texto" type="'.$c['config_tipo'].'" name="cfg['.$c['config_nome'].']" value="'.$valor.'" '.$extra.'/>';
			break;
		
		case 'usuario':
			$entrada ='<input type="hidden" id="'.$c['config_nome'].'" name="cfg['.$c['config_nome'].']" value="'.$c['config_valor'].'" /><input type="text" id="'.$c['config_nome'].'_nome" name="'.$c['config_nome'].'_nome" value="'.nome_om($c['config_valor'],$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popUsuario(\''.$c['config_nome'].'\');">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a>';
			break;	
			
		case 'dept':
			$entrada ='<input type="hidden" id="'.$c['config_nome'].'" name="cfg['.$c['config_nome'].']" value="'.$c['config_valor'].'" /><input type="text" id="'.$c['config_nome'].'_nome" name="'.$c['config_nome'].'_nome" value="'.nome_dept($c['config_valor']).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popDept(\''.$c['config_nome'].'\');">'.imagem('secoes_p.gif', 'Selecionar '.ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para selecionar '.($config['departamento']=='o' ? 'um' : 'uma').' '.$config['departamento'].'.').'</a>';
			break;	
		
		case 'quantidade':
		if (!$valor) $valor = $c['config_valor'];
			
			if ($bronze) {
				if ($c['config_nome']!='qnt_indicadores') $entrada=selecionaVetor($vetor_trinta, 'cfg['.$c['config_nome'].']', 'class="texto" style="width:300px;"', $c['config_valor']);	
				else $entrada=selecionaVetor($vetor_vinte, 'cfg['.$c['config_nome'].']', 'class="texto" style="width:300px;"', $c['config_valor']);	
				}
			else $entrada ='<input class="texto" style="width:300px;" type="'.$c['config_tipo'].'" name="cfg['.$c['config_nome'].']" id="cfg['.$c['config_nome'].']" value="'.$valor.'" '.$extra.'/>';
			break;	
		
		
		default:
			if (!$valor) $valor = $c['config_valor'];
			if ($c['config_nome']=='padrao_ver_m') $entrada=selecionaVetor($modulo, 'cfg[padrao_ver_m]','class="texto" size=1 onchange="submodulo();"', $valor);
			elseif ($c['config_nome']=='padrao_ver_a') {
				$entrada=selecionaVetor($vazio, 'cfg[padrao_ver_a]','class="texto" size=1 onchange="tab_submodulo();"', $valor);
				echo '<script type="text/javascript">var ver_a_original="'.$valor.'";</script>';
				}
			else $entrada = '<input class="texto" style="width:300px;" type="'.$c['config_tipo'].'" name="cfg['.$c['config_nome'].']" id="cfg['.$c['config_nome'].']" value="'.$valor.'" '.$extra.'/>';
			break;
		}
	

	if ($c['config_grupo'] != $ultimo_grupo) {
		if (count($parametros)){
			if ($ultimo_grupo!='cor') sort($parametros);
			foreach ($parametros as $saida) echo $saida;
			$parametros=array();
			}
		
		echo '<tr><td align="right" style="white-space: nowrap"><br><b>'.(isset($traducao[$c['config_grupo'].'_grupo_titulo']) ? $traducao[$c['config_grupo'].'_grupo_titulo'] : $c['config_grupo']).'</b></td><td width="100%">&nbsp;</td></tr>';
		$ultimo_grupo = $c['config_grupo'];
		}

	//if ($c['config_nome']=='om_padrao') echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Padrão', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' quando da criação de nova conta por '.$config['usuario'].' ainda sem login de acesso.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_superior">'.selecionar_om($c['config_valor'], 'cfg['.$c['config_nome'].']', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"','','','cfg['.$c['config_nome'].']').'</div><input value="'.$c['config_id'].'" type="hidden" name="cfgId['.$c['config_nome'].']" /></td></tr>';
	//else echo '<tr><td align="right" style="white-space: nowrap">'.dica((isset($traducao[$c['config_nome'].'_titulo']) ? $traducao[$c['config_nome'].'_titulo'] : ''), $popup). (isset($traducao[$c['config_nome'].'_titulo']) ? $traducao[$c['config_nome'].'_titulo']  : $c['config_nome']).':'.dicaF().'</td><td align="left">'.$entrada.'<input value="'.$c['config_id'].'" type="hidden" name="cfgId['.$c['config_nome'].']" /></td></tr>';
	
	if ($c['config_nome']=='om_padrao') $parametros[ucfirst($config['organizacao'])]='<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Padrão', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' quando da criação de nova conta por '.$config['usuario'].' ainda sem login de acesso.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_superior">'.selecionar_om($c['config_valor'], 'cfg['.$c['config_nome'].']', 'class=texto size=1 style="width:300px;" onchange="javascript:mudar_om();"','','','cfg['.$c['config_nome'].']').'</div><input value="'.$c['config_id'].'" type="hidden" name="cfgId['.$c['config_nome'].']" /></td></tr>';
	else $parametros[(isset($traducao[$c['config_nome'].'_titulo']) ? $traducao[$c['config_nome'].'_titulo']  : $c['config_nome'])]='<tr><td align="right" style="white-space: nowrap">'.dica((isset($traducao[$c['config_nome'].'_titulo']) ? $traducao[$c['config_nome'].'_titulo'] : ''), $popup).(isset($traducao[$c['config_nome'].'_titulo']) ? $traducao[$c['config_nome'].'_titulo']  : $c['config_nome']).':'.dicaF().'</td><td align="left">'.$entrada.'<input value="'.$c['config_id'].'" type="hidden" name="cfgId['.$c['config_nome'].']" /></td></tr>';
	}
//ultimo bloco
sort($parametros);
foreach ($parametros as $saida) echo $saida;






echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar as configurações.','','cfgFrm.submit()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a a edição das configurações.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr>';
echo '</table></form>';
echo estiloFundoCaixa();
?>
<script type="text/javascript">
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cfg[om_padrao]').value, 'cfg[om_padrao]','combo_superior', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"'); 	
	}		



function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&campo='+campo+'&usuario_id='+document.getElementById(campo).value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&campo='+campo+'&usuario_id='+document.getElementById(campo).value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById(campo).value=usuario_id;
	document.getElementById(campo+'_nome').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}


function popDept(campo) {
  window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&campo='+campo+'&dept_id='+document.getElementById(campo).value,'dept','left=0,top=0,height=600,width=400, scrollbars=yes, resizable');
	}


function setDept(cia, chave, val, campo) {
  if (chave != null && chave !='') {
    document.getElementById(campo).value=chave;
		document.getElementById(campo+'_nome').value=val;	
		} 
  else {
     document.getElementById(campo).value=null;
		document.getElementById(campo+'_nome').value='';	
		}

	}




var tab_original=document.getElementById("cfg[padrao_ver_tab]").value;
var ver_m_original=document.getElementById("cfg[padrao_ver_m]").value;

function submodulo(){
	var f = document.getElementById("cfg[padrao_ver_m]");
  var modulo = f.value;
  a=document.getElementById("cfg[padrao_ver_a]");
  a.length=0; 
  switch (modulo) {
    case "email":
    adicionarOpcao(a, 'lista_msg', 'Caixa de entrada de mensagens');
    adicionarOpcao(a, 'modelo_pesquisar', 'Caixa de entrada de modelos de documentos');
    break;

    case "calendario":
    adicionarOpcao(a, 'ver_dia', 'Eventos do dia ');
    adicionarOpcao(a, 'ver_dia', 'Compromissos do dia ');
   	adicionarOpcao(a, 'ver_dia', '<?php echo ucfirst($config["tarefa"])?> a serem realizadas');
    break;
    
    case "praticas":
    adicionarOpcao(a, 'index', 'Menu geral de gerenciamento da excelência');
    adicionarOpcao(a, 'pratica_lista', 'Lista de <?php echo $config["praticas"]?>');
    adicionarOpcao(a, 'indicador_lista', 'Lista de indicadores');
    break;
    
    case "projetos":
    adicionarOpcao(a, 'index', 'Lista de <?php echo $config["projetos"]?>');
    break;
    } 
  tab_submodulo();         
	}

function adicionarOpcao(selectbox,value,text){

	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;
	
	if (ver_a_original==value){
		optn.selected = true;
		}
	selectbox.options.add(optn);
	}
	

function tab_submodulo(){
	var m=document.getElementById("cfg[padrao_ver_m]").value;
	var indice=document.getElementById("cfg[padrao_ver_a]").selectedIndex;
	var tab=0;
	if (m=='calendario' && indice==0) tab=0;
	if (m=='calendario' && indice==1) tab=1;
	if (m=='calendario' && indice==2) tab=2;
	document.getElementById("cfg[padrao_ver_tab]").value=tab;
	}


submodulo();
tab_submodulo();	
</script>	
