<?php
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $projeto_id; // vem do contexto do GPweb

// Carrega o projeto principal para descobrir a raiz da estrutura
$sp_obj = new CProjeto();
$sp_obj->load($projeto_id);
$original_projeto_id = (int)$sp_obj->projeto_superior_original;

// ============================
// Consulta: somente projetos ATIVOS dessa estrutura
// ============================
$sql = new BDConsulta;
$sql->adTabela('projetos');
$sql->adCampo('projeto_id');
$sql->adOnde('projeto_superior_original = ' . $original_projeto_id);
$sql->adOnde('projeto_ativo = 1');
$sql->adOrdem('projeto_nome ASC');
$rows = $sql->lista();
$sql->limpar();

$ids = array();
if (is_array($rows)) {
    foreach ($rows as $r) {
        if (!empty($r['projeto_id'])) $ids[] = (int)$r['projeto_id'];
    }
}

$total = count($ids);
if ($total === 0) {
    echo '<div style="padding:8px;">Nenhum projeto ativo encontrado.</div>';
    return;
}

// Divide em 2 blocos equilibrados
$metade   = (int) ceil($total / 2);
$col1_ids = array_slice($ids, 0, $metade);
$col2_ids = array_slice($ids, $metade);

// ============================
// Helper para renderizar uma coluna/tabela
// ============================
if (!function_exists('renderProjetosColuna')) {
    function renderProjetosColuna(array $projeto_ids) {
        $x = 0;
        echo '<table border="0" cellpadding="5" cellspacing="1" bgcolor="black" width="100%">';
        echo '<tr bgcolor="#cccccc">';
        echo '  <th width="16">&nbsp;</th>';
        echo '  <th width="60%">Projeto</th>';
        echo '  <th width="20%">Fiscal</th>';
        echo '  <th width="20%">Construtora</th>';
        echo '</tr>';

        foreach ($projeto_ids as $pid) {
            $p = new CProjeto();
            $p->load($pid);

            $x++;
            $linha_class = ($x % 2) ? 'style="background:#ffffff;"' : 'style="background:#f0f0f0;"';

            echo '<tr>';
            // Ícone editar
            echo '  <td '.$linha_class.' align="center">';
            echo '    <a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=editar&projeto_id='.$pid.'\');">';
            echo '      <img src="'.acharImagem('icones/editar.gif').'" border="0" />';
            echo '    </a>';
            echo '  </td>';

            // Projeto (link)
            echo '  <td '.$linha_class.'>'.link_projeto($pid).'</td>';

            // Fiscal e Construtora (seguindo o mapeamento já usado no sistema)
            echo '  <td '.$linha_class.'>'.link_usuario($p->projeto_cliente).'</td>';     // Fiscal
            echo '  <td '.$linha_class.'>'.link_usuario($p->projeto_supervisor).'</td>';  // Construtora
            echo '</tr>';
        }
        echo '</table>';
    }
}

// ============================
// Layout: duas tabelas lado a lado (alinhadas)
// ============================
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>';
echo '  <td width="50%" valign="top" style="padding-right:10px;">';
renderProjetosColuna($col1_ids);
echo '  </td>';
echo '  <td width="50%" valign="top" style="padding-left:10px;">';
renderProjetosColuna($col2_ids);
echo '  </td>';
echo '</tr></table>';
?>
