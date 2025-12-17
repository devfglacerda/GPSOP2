<table>

<?php

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$Aplic->carregarCKEditorJS();

$botoesTitulo = new CBlocoTitulo('Histórico do Arquivo', 'arquivo.png', $m, "$m.$a");
$botoesTitulo->mostrar();



$sql = new BDConsulta; 
$arquivo_id=getParam($_REQUEST, 'arquivo_id', 0);

$sql->adTabela('arquivo_saida');
$sql->esqUnir('arquivo', 'arquivo', 'arquivo_saida_arquivo = arquivo_id');
		$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=arquivo_saida_usuario');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		
$sql->adCampo('arquivo_saida_data, arquivo_saida_motivo, arquivo_saida_acao, arquivo_saida_versao, contato_email, usuarios.usuario_id, contato_nomeguerra');
$sql->adOnde('arquivo_id='.$arquivo_id);
$lista = $sql->Lista();
$sql->limpar();




echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=1 cellspacing=1 class="std">';
echo '<tr><th>Data</th><th>Motivo</th><th>Ação</th><th>Versão</th><th>Usuário</th><th>Email</th></tr>';
foreach($lista as $linha) echo '<tr><td>'.retorna_data($linha['arquivo_saida_data']).'</td><td align="center">'.$linha['arquivo_saida_motivo'].'</td><td align="center">'.$linha['arquivo_saida_acao'].'</td><td align="center">'.$linha['arquivo_saida_versao'].'</td><td align="center">'.$linha['contato_nomeguerra'].'</td><td align="center">'.$linha['contato_email'].'</td></tr>';


echo '</table>';

//echo '<tr><td>'.botao('voltar', 'Voltar', 'Voltar a tela do projeto.','','if(confirm(\'Tem certeza que deseja voltar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr>';

echo estiloFundoCaixa();