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

global $Aplic, $cia_id,$perms, $podeEditar, $podeEditarDept;

$sql = new BDConsulta;
$sql->adTabela('depts');
$sql->esqUnir('contatos', 'contatos', 'contatos.contato_dept = dept_id');
$sql->adCampo('dept_id, COUNT(contatos.contato_dept) AS dept_usuarios, dept_superior, dept_acesso');
$sql->adOnde('dept_cia = '.(int)$cia_id);
$sql->adOnde('dept_ativo = 1');
$sql->adGrupo('dept_id');
$sql->adOrdem('dept_superior, dept_ordem, dept_nome');
$s = '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
$s .= '<tr>';
$linhas = $sql->Lista();
$sql->limpar();


if (count($linhas)) $s .= '<th>&nbsp;</th><th width="100%">Nome</th><th>'.ucfirst($config['usuarios']).'</th>';
else $s .= '<td><p>Nenhum'.($config['genero_dept']=='a' ? 'a' : '').' '.strtolower($config['departamento']).' encontrad'.$config['genero_dept'].'.</p></td>';
$s .= '</tr>';
echo $s;
foreach ($linhas as $linha) {
	if (!$linha['dept_superior']) {
		mostrarDeptSubordinado_comp($linha);
		acharDeptSubordinado_comp($linhas, $linha['dept_id']);
		}
	}
echo '</table>';


function mostrarDeptSubordinado_comp(&$a, $nivel = 0) {
	global $Aplic, $config, $podeEditar, $podeEditarDept;
	$s = '<td>'.($podeEditarDept && permiteEditarDept($a['dept_acesso'], $a['dept_id']) ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=depts&a=editar&dept_id='.$a["dept_id"].'\');" >'.imagem('icones/editar.gif','Editar '.$config['departamento'], 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']).'.').'</a>' : '&nbsp;').'</td>';
	$s .='<td>';
	for ($y = 0; $y < $nivel; $y++) {
		if ($y + 1 == $nivel) $s .= '<img src="'.acharImagem('subnivel.gif').'" width="16" height="12" border=0>';
		else $s .= '<img src="'.acharImagem('shim.gif').'" width="16" height="12" border=0>';
		}
	$s .= link_secao($a['dept_id']);
	$s .= '</td>';
	$s .= '<td align="center">'.($a['dept_usuarios'] ? $a['dept_usuarios']:'&nbsp').'</td>';
	echo '<tr>'.$s.'</tr>';
	}

function acharDeptSubordinado_comp(&$linha, $superior, $nivel = 0) {
	$nivel = $nivel + 1;
	$n = count($linha);
	for ($x = 0; $x < $n; $x++) {
		if ($linha[$x]['dept_superior'] == $superior && $linha[$x]['dept_superior'] != $linha[$x]['dept_id']) {
			mostrarDeptSubordinado_comp($linha[$x], $nivel);
			acharDeptSubordinado_comp($linha, $linha[$x]['dept_id'], $nivel);
			}
		}
	}
?>