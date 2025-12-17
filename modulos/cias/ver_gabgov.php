<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$cia_id = intval(getParam($_REQUEST, 'cia_id', 0));
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (isset($_REQUEST['tab'])) $Aplic->setEstado('CiaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('CiaVerTab') !== null ? $Aplic->getEstado('CiaVerTab') : 0;

$podeEditarDept=$Aplic->checarModulo('depts', 'editar');

$sql = new BDConsulta;

$msg = '';
$obj = new CCia();

$obj->load($cia_id);



if (!$obj) {
	$Aplic->setMsg($config['organizacao']);
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=cias');
	} 
else $Aplic->salvarPosicao();
if (!permiteAcessarCia($obj->cia_acesso, $cia_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$permiteEditar=permiteEditarCia($obj->cia_acesso, $cia_id);


if (getParam($_REQUEST, 'superior', 0) && $podeEditar && $permiteEditar && ($Aplic->usuario_super_admin || ($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin))){
	$sql->adTabela('cias');
	$sql->adAtualizar('cia_superior', $cia_id);
	$sql->adOnde('cia_superior IS NULL OR cia_superior=cia_id');
	if (!$sql->exec()) die('Não foi possível atualizar cias.');
	$sql->limpar();
	
	$sql->adTabela('cias');
	$sql->adAtualizar('cia_superior', null);
	$sql->adOnde('cia_id='.(int)$cia_id);
	if (!$sql->exec()) die('Não foi possível atualizar cias.');
	$sql->limpar();
	ver2(ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' se tornou '.$config['genero_organizacao'].' primeir'.$config['genero_organizacao'].' no organograma.');
	}

$sql->adTabela('cias');
$sql->esqUnir('estado', 'estado', 'cia_estado=estado.estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'cia_cidade=municipio_id');
$sql->adCampo('estado_nome, municipio_nome');
$sql->adOnde('cia_id='.(int)$cia_id);
$endereco= $sql->Linha();
$sql->limpar();


$projStatus = getSisValor('StatusProjeto');
$tipos = getSisValor('TipoOrganizacao');
$paises = getPais('Paises');


if ($podeExcluir) {
	echo '<form name="frmExcluir" method="post">';
	echo '<input type="hidden" name="m" value="cias" />';
	echo '<input name="a" type="hidden" value="vazio" />';
	echo '<input name="u" type="hidden" value="" />';
	echo '<input type="hidden" name="fazerSQL" value="fazer_cia_aed" />';
	echo '<input type="hidden" name="del" value="1" />';
	echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';
	echo '</form>';
	}




$sql->adTabela('cia_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=cia_usuario_usuario');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('cia_usuario_cia = '.(int)$cia_id);
$designados = $sql->Lista();
$sql->limpar();

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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td class="realce">'.$saida_quem.'</td></tr>';










require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $obj->cia_id, 'ver');
$campos_customizados->imprimirHTML();
echo '</table>';

if (!$dialogo){
	$caixaTab = new CTabBox('m=cias&a=ver_gabgov&cia_id='.(int)$cia_id, '', $tab);
	
		$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_gabgov', 'DEMANDAS',null,null,'DEMANDAS','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
	
	
		
		//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_iniciar', 'Tarefas a iniciar',null,null,'Tarefas a iniciar','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
		//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_paralisadas', 'Tarefas Paralisadas',null,null,'Tarefas Paralisadas','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
		//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_pendentes', 'Tarefas Pendentes',null,null,'Tarefas Pendentes','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
		//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_atrasadas', 'Tarefas Atrasadas',null,null,'Tarefas Atrasadas','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
			//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_canceladas', 'Tarefas Canceladas',null,null,'Tarefas Canceladas','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
			//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_devolvidas', 'Tarefas Devolvidas',null,null,'Tarefas Devolvidas','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
			//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_concluidas', 'Tarefas Concluidas',null,null,'Tarefas Concluidas','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
			
		//	$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_tarefas_todas', 'Resumo Geral',null,null,'Resumo Geral','Visualizar as tarefas d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
		
		
		
		
		
		
		
		
		
		
	
	//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_depts', ucfirst($config['departamentos']),null,null,ucfirst($config['departamentos']),'Visualizar '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.');
	//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_usuarios', 'Integrantes',null,null,'Integrantes','Visualizar os integrantes dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.');
	//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_contatos', 'Contatos',null,null,'Contatos','Visualizar os contatos d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');

	
	
	//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados à '.$config['organizacao'].' ou de '.strtolower($config['departamentos']).' internas.');
//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados à '.$config['organizacao'].' ou de '.strtolower($config['departamentos']).' internas.');
	//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_cias', 'Subordinad'.$config['genero_organizacao'].'s',null,null,'Subordinad'.$config['genero_organizacao'].'s','Visualizar '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s.');	
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa();
	}
?>
<script language="javascript">
function excluir() {
	if (confirm( "Tem certeza que desejas excluir esta <?php echo $config['organizacao']?>? Todos os dados vinculados como <?php echo $config['usuarios']?>, <?php echo $config['projetos']?>, etc. serão perdidos." )) document.frmExcluir.submit();
	}
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>
