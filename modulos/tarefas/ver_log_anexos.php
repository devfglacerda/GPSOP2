<?php
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/

$log_id=getParam($_REQUEST, 'log_id', 0);

$sql = new BDConsulta();
//arquivo anexo
$sql->adTabela('log_arquivo');
$sql->adCampo('log_arquivo_id, log_arquivo_usuario, log_arquivo_data, log_arquivo_ordem, log_arquivo_nome, log_arquivo_endereco');
$sql->adOnde('log_arquivo_log='.(int)$log_id);
$sql->adOrdem('log_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();


echo '<table cellspacing=0 cellpadding=0>';
foreach ($arquivos as $arquivo) {
	echo '<tr><td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=praticas&a=log_download&sem_cabecalho=1&log_arquivo_id='.$arquivo['log_arquivo_id'].'\');">'.$arquivo['log_arquivo_nome'].'</a></td></tr>';
	}
	
echo '</table>';	
?>
