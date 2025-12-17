<?php
$pratica_indicador_id = intval(getParam($_REQUEST, 'pratica_indicador_id', 0));
include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';

$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_calculo, pratica_indicador_formula');
$sql->adOnde('pratica_indicador_id='.$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();
echo estiloTopoCaixa();

echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';
echo '<tt><td><h1>'.nome_indicador($pratica_indicador_id).'</h1></td></tr>';
if ($pratica_indicador['pratica_indicador_formula'] && $pratica_indicador['pratica_indicador_calculo']) {
	$sql->adTabela('pratica_indicador_formula');
	$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador_id=pratica_indicador_formula_filho');
	$sql->esqUnir('cias','cias', 'cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_formula_filho, pratica_indicador_agrupar, pratica_indicador_formula_ordem, pratica_indicador_nome, cia_nome, pratica_indicador_formula_rocado');
	$sql->adOnde('pratica_indicador_formula_pai = '.(int)$pratica_indicador_id);
	$lista_formula = $sql->Lista();
	if ($lista_formula && count($lista_formula)) {
		echo '<tr><td align=center>&nbsp;</td></tr>';
		echo '<tr><td>Fórmula='.strtoupper($pratica_indicador['pratica_indicador_calculo']).'</td></tr>';
		echo '<tr><td align=center>&nbsp;</td></tr>';
		echo '<tr><td><table cellspacing=0 cellpadding=0 class="tbl1">';
		
		$objPai = new Indicador($pratica_indicador_id);
		$valorTotal=$objPai->Valor_atual($objPai->pratica_indicador_agrupar);
		
		
		
		$qnt_lista_formula=count($lista_formula);
		for ($i = 0, $i_cmp = $qnt_lista_formula; $i < $i_cmp; $i++) {
			$obj = new Indicador($lista_formula[$i]['pratica_indicador_formula_filho']);
			$valor=$obj->Valor_atual($objPai->pratica_indicador_agrupar);
			echo '<tr><td>'.$lista_formula[$i]['pratica_indicador_nome'].' - '.$lista_formula[$i]['cia_nome'].($lista_formula[$i]['pratica_indicador_formula_rocado'] ? ' - deslocado' : '').'</td><td align=center>I'.($lista_formula[$i]['pratica_indicador_formula_ordem']< 10 ? '0' : '').$lista_formula[$i]['pratica_indicador_formula_ordem'].'</td><td align=right>'.($valor !==null ? number_format($valor, $config['casas_decimais'], ',', '.') : 'sem valor').'</td></tr>';		
			}
			
		echo '<tr><td colspan=2 align=center><b>Total</b></td><td><b>'.($valorTotal !==null ? number_format($valorTotal, $config['casas_decimais'], ',', '.') : 'sem valor').'</b></td></tr>';
			
			
			
		echo '</table></td></tr>';
		} 
	}
else echo '<tr><td>Não há campos nesta fórmula!</td></tr>';	
echo '</table>';	
echo estiloFundoCaixa();	
?>