<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


$campo_formulario_tipo=getParam($_REQUEST, 'campo_formulario_tipo', 'atas');
$campo_formulario_tipo_extra=getParam($_REQUEST, 'campo_formulario_tipo_extra', null);

if(empty($campo_formulario_tipo_extra)){
    $campo_formulario_tipo_extra = $campo_formulario_tipo;
    }

$campo_formulario_tipo_extra .= '_ex';

$sql = new BDConsulta;


if (getParam($_REQUEST, 'salvar', null)){
	$campo=getParam($_REQUEST, 'campo', array());
	
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo, campo_formulario_descricao, campo_formulario_tipo, campo_formulario_customizado');
  //campos extras são do mesmo tipo porem com sufixo 'ex'
	$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$lista = $sql->lista();
	$sql->limpar();
	
	$sql->setExcluir('campo_formulario');
  //campos extras são do mesmo tipo porem com sufixo 'ex'
  $sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
	$sql->adOnde('campo_formulario_usuario ='.(int)$Aplic->usuario_id);
	$sql->exec();
	$sql->limpar();	

	
	foreach($lista as $linha) {
		$sql->adTabela('campo_formulario');
		$sql->adInserir('campo_formulario_ativo', (isset($campo[$linha['campo_formulario_campo']]) ? '1' : '0'));
		$sql->adInserir('campo_formulario_campo', $linha['campo_formulario_campo']);
		$sql->adInserir('campo_formulario_tipo', $linha['campo_formulario_tipo']);
    $sql->adInserir('campo_formulario_descricao', $linha['campo_formulario_descricao']);
    if(isset($linha['campo_formulario_customizado'])) $sql->adInserir('campo_formulario_customizado', $linha['campo_formulario_customizado']);
		$sql->adInserir('campo_formulario_usuario', $Aplic->usuario_id);
		$sql->exec();
		$sql->limpar();
		}

	echo '<script type="text/javascript">parent.gpwebApp._popupCallback();</script>';	
	}

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_descricao');
//campos extras são do mesmo tipo porem com sufixo 'ex'
$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$sql->adOrdem('campo_formulario_descricao');
$legenda = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_descricao');
$sql->limpar();


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
//campos extras são do mesmo tipo porem com sufixo 'ex'
$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
$sql->adOrdem('campo_formulario_descricao');
$lista = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if (!count($lista)){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	//campos extras são do mesmo tipo porem com sufixo 'ex'
  $sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$sql->adOrdem('campo_formulario_descricao');
	$lista = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();
	}	



foreach($legenda as $chave => $valor){
	$valor=str_replace('Seção', ucfirst($config['departamento']), $valor);
	$valor=str_replace('Seções', ucfirst($config['departamentos']), $valor);
	
	$valor=str_replace('Organização', ucfirst($config['organizacao']), $valor);
	$valor=str_replace('Organizações', ucfirst($config['organizacoes']), $valor);
	
	$legenda[$chave]=$valor;
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input name="a" type="hidden" value="'.$a.'" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="salvar" type="hidden" value="" />';
echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%" align="center">';
foreach($legenda as $chave => $legenda) echo '<tr><td width=16 ><input class="texto" type="checkbox" name="campo['.$chave.']" value="1" '.(isset($lista[$chave]) && $lista[$chave] ? 'checked="checked"': '').'  /></td><td>'.(isset($config[$chave]) ? ucfirst($config[$chave]) : (isset($config[strtolower($legenda)]) ? ucfirst($config[strtolower($legenda)]) : ucfirst($legenda))).'</td></tr>';
echo '<tr><td colspan=2><table><tr><td>'.botao('salvar', '', '','','env.salvar.value=1; env.submit();').'</td></tr></table></td></tr>';
echo '</table></form>';
echo estiloFundoCaixa();
?>