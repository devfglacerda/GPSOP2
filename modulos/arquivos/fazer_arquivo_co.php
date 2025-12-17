<?php
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$arquivo_id = getParam($_REQUEST, 'arquivo_id', 0);

// Insere o registro no banco de dados
$sql = new BDConsulta();
$sql->adTabela('arquivo_saida');
$sql->adInserir('arquivo_saida_arquivo', $arquivo_id);
$sql->adInserir('arquivo_saida_usuario', $Aplic->usuario_id);
$sql->adInserir('arquivo_saida_data', date('Y-m-d H:i:s'));
$sql->adInserir('arquivo_saida_versao', getParam($_REQUEST, 'arquivo_saida_versao', 0));
$sql->adInserir('arquivo_saida_acao', getParam($_REQUEST, 'arquivo_saida_acao', null));
$sql->adInserir('arquivo_saida_motivo', getParam($_REQUEST, 'arquivo_saida_motivo', null));
$sql->exec();
$sql->limpar();

// Gera a URL para o download
$download_url = 'codigo/arquivo_visualizar.php?arquivo_id=' . $arquivo_id;

// Inicia o download de forma assíncrona e redireciona usando url_passar
echo '<script type="text/javascript">
    // Cria um iframe invisível para iniciar o download
    var iframe = document.createElement("iframe");
    iframe.style.display = "none";
    iframe.src = "' . $download_url . '";
    document.body.appendChild(iframe);
    
    // Redireciona usando url_passar após um pequeno atraso
    setTimeout(function() {
        url_passar(0, "' . $Aplic->getPosicao() . '");
    }, 500);
</script>';
exit;
?>