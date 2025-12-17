<?php
$recurso_id=getParam($_REQUEST, 'recurso_id', null);
$sql = new BDConsulta;
$sql->adTabela('recursos');
$sql->adCampo('recurso_nota');
$sql->adOnde('recurso_id ='.(int)$recurso_id);
$relato = $sql->Resultado();
$sql->limpar();

echo '<table><tr><td>'.$relato.'</td></tr></table>';
?>