<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMAo GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global  $tarefas, $prioridades, $projeto_id, $dialogo, $Aplic, $cia_id, $obj, $config, $tab, $projeto_id;

$Aplic->carregarCKEditorJS();
$mostrarNomeProjeto=nome_projeto($projeto_id);
$usuario_id = getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$data_inicio= getParam($_REQUEST, 'reg_data_inicio', '');
$data_fim= getParam($_REQUEST, 'reg_data_fim', '');
$tipo2 = getSisValor('TipoTarefa');
$status = getSisValor('StatusTarefa');
$tipo = getSisValor('Setor');
$sql = new BDConsulta; 
if (isset($_REQUEST['cia_id'])) $cia_id=getParam($_REQUEST, 'cia_id', 0);
if ($projeto_id) $sql->adOnde('t.tarefa_projeto = '.(int)$projeto_id);
$sql = new BDConsulta;
if (count($tarefas)<1){
	

echo '<table style="background-color: rgb(166, 166, 166); " class="std" cellpadding="2" cellspacing="2" border="1"><tbody><tr><td>';
	
	
$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');		
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
 
$sql->adOnde('usuario_id = 82');
$sql->adOnde('tarefa_status = 3');
$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
$lista = $sql->Lista();
$sql->limpar();	}

if (!($linha = $sql->Lista())) {
		
		
		foreach($lista as $linha)  {
		
		
		echo '<table style="background-color: rgb(166, 166, 166);" class="tbl4" border="1" cellpadding="1" cellspacing="1"><tbody><tr><td colspan="1" rowspan="8"><img
     style="width: 102px; height: 125px;" alt="" src="/arquivos/contatos/82/breno_3X4.png"></td><td style="width: 130px;" align="left"><b>Profissional:</b></td>
      <td style="width: 200px;" align="left">'.link_usuario($linha['usuario_id']).'</td></tr>
	  <tr><td style="width: 130px;" align="left"><b>Tarefa:</b></td><td style="width: 200px;" align="left">'.$linha['tarefa_nome'].'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Projeto:</b></td><td style="width: 200px;" align="left">'.link_projeto($linha['tarefa_projeto']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Porcentagem:</b></td><td style="width: 200px;" align="left">'.number_format($linha['tarefa_percentagem']).'%'.'</td></tr>
    <tr><td align="left" style="width: 130px;"><b>Tipo:</b></td><td align="left" style="width: 200px;">'.($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;').'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>DataInicial:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_inicio']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Data Final:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_fim']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Descri&ccedil;&atilde;o:</b></td><td style="width: 400px;" align="left">'.$linha['tarefa_descricao'].'</td></tr></tbody></table>';	}}
		echo '</td>';
	
	
	
	
	/***************************************************************************************************/
	
	
		echo '<td>';
		
		
		
if (count($tarefas)<1){		
$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');		
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
 
$sql->adOnde('usuario_id = 79');
$sql->adOnde('tarefa_status = 3');
$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
$lista = $sql->Lista();
$sql->limpar();	}

if (!($linha = $sql->Lista())) {
		
		
		foreach($lista as $linha)  {
			
		echo '<table style="background-color: rgb(166, 166, 166);" class="tbl4" border="1" cellpadding="1" cellspacing="1"><tbody><tr><td colspan="1" rowspan="8"><img
     style="width: 102px; height: 125px;" alt="" src="/arquivos/contatos/79/lessa_3X4.png"></td><td style="width: 130px;" align="left"><b>Profissional:</b></td>
      <td style="width: 200px;" align="left">'.link_usuario($linha['usuario_id']).'</td></tr>
	  <tr><td style="width: 130px;" align="left"><b>Tarefa:</b></td><td style="width: 200px;" align="left">'.$linha['tarefa_nome'].'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Projeto:</b></td><td style="width: 200px;" align="left">'.link_projeto($linha['tarefa_projeto']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Porcentagem:</b></td><td style="width: 200px;" align="left">'.number_format($linha['tarefa_percentagem']).'%'.'</td></tr>
    <tr><td align="left" style="width: 130px;"><b>Tipo:</b></td><td align="left" style="width: 200px;">'.($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;').'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>DataInicial:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_inicio']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Data Final:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_fim']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Descri&ccedil;&atilde;o:</b></td><td style="width: 400px;" align="left">'.$linha['tarefa_descricao'].'</td></tr></tbody></table>';	}}
		
		echo '</td>';
		
		/***************************************************************************************************/
		
		
		echo '<td>';
		
		
if (count($tarefas)<1){		
$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');		
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
 
$sql->adOnde('usuario_id = 77');
$sql->adOnde('tarefa_status = 3');
$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
$lista = $sql->Lista();
$sql->limpar();	}

if (!($linha = $sql->Lista())) {
		
		
		foreach($lista as $linha)  {
		
	echo '<table style="background-color: rgb(166, 166, 166);" class="tbl4" border="1" cellpadding="1" cellspacing="1"><tbody><tr><td colspan="1" rowspan="8"><img
     style="width: 102px; height: 125px;" alt="" src="/arquivos/contatos/77/VILANICE_3X4.png"></td><td style="width: 130px;" align="left"><b>Profissional:</b></td>
      <td style="width: 200px;" align="left">'.link_usuario($linha['usuario_id']).'</td></tr>
	  <tr><td style="width: 130px;" align="left"><b>Tarefa:</b></td><td style="width: 200px;" align="left">'.$linha['tarefa_nome'].'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Projeto:</b></td><td style="width: 200px;" align="left">'.link_projeto($linha['tarefa_projeto']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Porcentagem:</b></td><td style="width: 200px;" align="left">'.number_format($linha['tarefa_percentagem']).'%'.'</td></tr>
    <tr><td align="left" style="width: 130px;"><b>Tipo:</b></td><td align="left" style="width: 200px;">'.($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;').'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>DataInicial:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_inicio']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Data Final:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_fim']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Descri&ccedil;&atilde;o:</b></td><td style="width: 400px;" align="left">'.$linha['tarefa_descricao'].'</td></tr></tbody></table>';	}}
		
		echo '</td></tr>';
		
		/***************************************************************************************************/
		
		
		echo '<td>';
		
		
if (count($tarefas)<1){		
$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');		
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
 
$sql->adOnde('usuario_id = 78');
$sql->adOnde('tarefa_status = 3');
$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
$lista = $sql->Lista();
$sql->limpar();	}

if (!($linha = $sql->Lista())) {
		
		
		foreach($lista as $linha)  {
		
		echo '<table style="background-color: rgb(166, 166, 166);" class="tbl4" border="1" cellpadding="1" cellspacing="1"><tbody><tr><td colspan="1" rowspan="8"><img
     style="width: 102px; height: 125px;" alt="" src="/arquivos/contatos/78/MIKAELA_3X4.png"></td><td style="width: 130px;" align="left"><b>Profissional:</b></td>
      <td style="width: 200px;" align="left">'.link_usuario($linha['usuario_id']).'</td></tr>
	  <tr><td style="width: 130px;" align="left"><b>Tarefa:</b></td><td style="width: 200px;" align="left">'.$linha['tarefa_nome'].'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Projeto:</b></td><td style="width: 200px;" align="left">'.link_projeto($linha['tarefa_projeto']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Porcentagem:</b></td><td style="width: 200px;" align="left">'.number_format($linha['tarefa_percentagem']).'%'.'</td></tr>
    <tr><td align="left" style="width: 130px;"><b>Tipo:</b></td><td align="left" style="width: 200px;">'.($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;').'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>DataInicial:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_inicio']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Data Final:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_fim']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Descri&ccedil;&atilde;o:</b></td><td style="width: 400px;" align="left">'.$linha['tarefa_descricao'].'</td></tr></tbody></table>';	}}
		
		echo '</td>';
		
		/***************************************************************************************************/
		
		
		echo '<td>';
		
		
if (count($tarefas)<1){		
$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');		
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
 
$sql->adOnde('usuario_id = 80');
$sql->adOnde('tarefa_status = 3');
$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
$lista = $sql->Lista();
$sql->limpar();	}

if (!($linha = $sql->Lista())) {
		
		
		foreach($lista as $linha)  {
		
			echo '<table style="background-color: rgb(166, 166, 166);" class="tbl4" border="1" cellpadding="1" cellspacing="1"><tbody><tr><td colspan="1" rowspan="8"><img
     style="width: 102px; height: 125px;" alt="" src="/arquivos/contatos/80/JONAS_3X4.jpg"></td><td style="width: 130px;" align="left"><b>Profissional:</b></td>
      <td style="width: 200px;" align="left">'.link_usuario($linha['usuario_id']).'</td></tr>
	  <tr><td style="width: 130px;" align="left"><b>Tarefa:</b></td><td style="width: 200px;" align="left">'.$linha['tarefa_nome'].'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Projeto:</b></td><td style="width: 200px;" align="left">'.link_projeto($linha['tarefa_projeto']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Porcentagem:</b></td><td style="width: 200px;" align="left">'.number_format($linha['tarefa_percentagem']).'%'.'</td></tr>
    <tr><td align="left" style="width: 130px;"><b>Tipo:</b></td><td align="left" style="width: 200px;">'.($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;').'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>DataInicial:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_inicio']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Data Final:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_fim']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Descri&ccedil;&atilde;o:</b></td><td style="width: 400px;" align="left">'.$linha['tarefa_descricao'].'</td></tr></tbody></table>';	}}
		
		echo '</td>';
		
		
		/***************************************************************************************************/
		
		
		echo '<td>';
		
		
if (count($tarefas)<1){		
$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');		
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
 
$sql->adOnde('usuario_id = 126');
$sql->adOnde('tarefa_status = 3');
$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
$lista = $sql->Lista();
$sql->limpar();	}

if (!($linha = $sql->Lista())) {
		
		
		foreach($lista as $linha)  {
		
		echo '<table style="background-color: rgb(166, 166, 166);" class="tbl4" border="1" cellpadding="1" cellspacing="1"><tbody><tr><td colspan="1" rowspan="8"><img
     style="width: 102px; height: 125px;" alt="" src="/arquivos/contatos/126/PEDROJR_3X4.png"></td><td style="width: 130px;" align="left"><b>Profissional:</b></td>
      <td style="width: 200px;" align="left">'.link_usuario($linha['usuario_id']).'</td></tr>
	  <tr><td style="width: 130px;" align="left"><b>Tarefa:</b></td><td style="width: 200px;" align="left">'.$linha['tarefa_nome'].'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Projeto:</b></td><td style="width: 200px;" align="left">'.link_projeto($linha['tarefa_projeto']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Porcentagem:</b></td><td style="width: 200px;" align="left">'.number_format($linha['tarefa_percentagem']).'%'.'</td></tr>
    <tr><td align="left" style="width: 130px;"><b>Tipo:</b></td><td align="left" style="width: 200px;">'.($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;').'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>DataInicial:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_inicio']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Data Final:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_fim']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Descri&ccedil;&atilde;o:</b></td><td style="width: 400px;" align="left">'.$linha['tarefa_descricao'].'</td></tr></tbody></table>';	}}
		
		echo '</td></tr>';
		
		
/***************************************************************************************************/
		
		echo '<td>';
		
		
if (count($tarefas)<1){		
$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');		
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
 
$sql->adOnde('usuario_id = 72');
$sql->adOnde('tarefa_status = 3');
$sql->adOnde('tarefa_percentagem < 100');
$sql->adOnde('tarefa_dinamica = 0');
$lista = $sql->Lista();
$sql->limpar();	}

if (!($linha = $sql->Lista())) {
		
		
		foreach($lista as $linha)  {
		
		echo '<table style="background-color: rgb(166, 166, 166);" class="tbl4" border="1" cellpadding="1" cellspacing="1"><tbody><tr><td colspan="1" rowspan="8"><img
     style="width: 102px; height: 125px;" alt="" src="/arquivos/contatos/72/THIAGO_3X4.jpg"></td><td style="width: 130px;" align="left"><b>Profissional:</b></td>
      <td style="width: 200px;" align="left">'.link_usuario($linha['usuario_id']).'</td></tr>
	  <tr><td style="width: 130px;" align="left"><b>Tarefa:</b></td><td style="width: 200px;" align="left">'.$linha['tarefa_nome'].'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Projeto:</b></td><td style="width: 200px;" align="left">'.link_projeto($linha['tarefa_projeto']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Porcentagem:</b></td><td style="width: 200px;" align="left">'.number_format($linha['tarefa_percentagem']).'%'.'</td></tr>
    <tr><td align="left" style="width: 130px;"><b>Tipo:</b></td><td align="left" style="width: 200px;">'.($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;').'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>DataInicial:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_inicio']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Data Final:</b></td><td style="width: 200px;" align="left">'.retorna_data($linha['tarefa_fim']).'</td></tr>
    <tr><td style="width: 130px;" align="left"><b>Descri&ccedil;&atilde;o:</b></td><td style="width: 400px;" align="left">'.$linha['tarefa_descricao'].'</td></tr></tbody></table>';	}}
		
		echo '</td>';
		
		
	
?>


