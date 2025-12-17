<?php
/*
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
$sql = new BDConsulta;

$sql->adTabela('pg_swot');
$sql->esqUnir('swot', 'swot', 'pg_swot_swot=swot_id');
$sql->adCampo('pg_swot_nome, swot_nome');
$sql->adOnde('pg_swot_pg='.(int)$pg_id);
$sql->adOnde('pg_swot_tipo=\'o\'');
$sql->adOrdem('pg_swot_ordem ASC');
$oportunidades=$sql->Lista();
$sql->limpar();
$saida_oportunidades='<table width=100% cellspacing=0 cellpadding=0><tr><th>Oportunidades (O)</th></tr>';
foreach ($oportunidades as $oportunidade) {

	$saida_oportunidades.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.($oportunidade['swot_nome'] ? $oportunidade['swot_nome'] : $oportunidade['pg_swot_nome']).'</td></tr>';
	}
$saida_oportunidades.= '</table>';


$sql->adTabela('pg_swot');
$sql->esqUnir('swot', 'swot', 'pg_swot_swot=swot_id');
$sql->adCampo('pg_swot_nome, swot_nome');
$sql->adOnde('pg_swot_pg='.(int)$pg_id);
$sql->adOnde('pg_swot_tipo=\'t\'');
$sql->adOrdem('pg_swot_ordem ASC');
$ameacas=$sql->Lista();
$sql->limpar();
$saida_ameacas='<table width=100% cellspacing=0 cellpadding=0><tr><th>Ameaças (T)</th></tr>';
foreach ($ameacas as $ameaca) {
	$saida_ameacas.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.($ameaca['swot_nome'] ? $ameaca['swot_nome'] : $ameaca['pg_swot_nome']).'</td></tr>';
	}
$saida_ameacas.= '</table>';

$sql->adTabela('pg_swot');
$sql->esqUnir('swot', 'swot', 'pg_swot_swot=swot_id');
$sql->adCampo('pg_swot_nome, swot_nome');
$sql->adOnde('pg_swot_pg='.(int)$pg_id);
$sql->adOnde('pg_swot_tipo=\'s\'');
$sql->adOrdem('pg_swot_ordem ASC');
$ponto_fortes=$sql->Lista();
$sql->limpar();
$saida_ponto_fortes='<table width=100% cellspacing=0 cellpadding=0><tr><th>Forças (S)</th></tr>';
foreach ($ponto_fortes as $ponto_forte)	$saida_ponto_fortes.='<tr><td style="margin-bottom:0cm; margin-top:0cm;">'.($ponto_forte['swot_nome'] ? $ponto_forte['swot_nome'] : $ponto_forte['pg_swot_nome']).'</td></tr>';

$saida_ponto_fortes.= '</table>';



$sql->adTabela('pg_swot');
$sql->esqUnir('swot', 'swot', 'pg_swot_swot=swot_id');
$sql->adCampo('pg_swot_nome, swot_nome');
$sql->adOnde('pg_swot_pg='.(int)$pg_id);
$sql->adOnde('pg_swot_tipo=\'w\'');
$sql->adOrdem('pg_swot_ordem ASC');
$oportunidade_melhorias=$sql->Lista();
$sql->limpar();
$saida_oportunidade_melhorias='<table width=100% cellspacing=0 cellpadding=0><tr><th>Fraquezas (W)</th></tr>';
foreach ($oportunidade_melhorias as $oportunidade_melhoria) {
	$saida_oportunidade_melhorias.='<tr><td>'.($oportunidade_melhoria['swot_nome'] ? $oportunidade_melhoria['swot_nome'] : $oportunidade_melhoria['pg_swot_nome']).'</td></tr>';
	}
$saida_oportunidade_melhorias.= '</table>';

echo '<table cellspacing=0 cellpadding=0>';
echo '<tr><td></td><td align=center style="background-color: #ccefa9"><b><br>Positivo<br>&nbsp;</b></td><td align=center style="background-color: #f4a5a5"><b>Negativo</b></td></tr>';
echo '<tr><td style="background-color: #faf2b4"><b>Interno</b></td><td valign=top style="background-color: #e0ed84">'.$saida_ponto_fortes.'</td><td valign=top style="background-color: #f7c082">'.$saida_oportunidade_melhorias.'</td></tr>';
echo '<tr><td style="background-color: #c2d4e8"><b>Externo</b></td><td valign=top style="background-color: #a7ceb9">'.$saida_oportunidades.'</td><td valign=top style="background-color: #c0a2b6">'.$saida_ameacas.'</td></tr>';

echo '</td></tr></table>';
?>