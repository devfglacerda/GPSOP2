<?php
$Aplic->carregarCKEditorJS();

$plano_acao_item_id=getParam($_REQUEST, 'plano_acao_item_id', null);
$plano_acao_item_observacao=getParam($_REQUEST, 'plano_acao_item_observacao', null);

$sql = new BDConsulta;

$sql->adTabela('plano_acao_item');
$sql->adCampo('plano_acao_item_observacao');
$sql->adOnde('plano_acao_item_id='.(int)$plano_acao_item_id);
$obs=$sql->Resultado();
$sql->limpar();


if (getParam($_REQUEST, 'salvar', null)){
	
	$sql->adTabela('plano_acao_item');
	$sql->adAtualizar('plano_acao_item_observacao', $plano_acao_item_observacao);
	$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	
	echo '<script type="text/javascript">parent.gpwebApp._popupCallback('.$plano_acao_item_id.');</script>';
	}
	
echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" id="plano_acao_item_id" name="plano_acao_item_id" value="'.$plano_acao_item_id.'" />';
echo '<input type="hidden" id="salvar" name="salvar" value="1" />';
echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width=100%>';
echo '<tr><td align="right" width="80">'.dica('Observação', 'Observação do item do plano de ação.').'Observação:'.dicaF().'</td><td style="width:750px;"><textarea name="plano_acao_item_observacao" id="plano_acao_item_observacao" style="width:720px;" class="textarea" data-gpweb-cmp="ckeditor">'.$obs.'</textarea></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','salvar_obs();').'</td></tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script type="text/javascript">

function salvar_obs(){	
	env.submit();
	}

</script>