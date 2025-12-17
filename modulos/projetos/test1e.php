<?php
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

// Variável global esperada do contexto do GPweb
global $projeto_id;

// Carrega o projeto principal para determinar a raiz da estrutura
$sp_obj = new CProjeto();
$original_projeto_id = 0;

if (isset($projeto_id) && !empty($projeto_id)) {
    if ($sp_obj->load($projeto_id)) {
        $original_projeto_id = (int)$sp_obj->projeto_superior_original ?: (int)$projeto_id;
    }
} else {
    trigger_error('O parâmetro $projeto_id não foi definido.', E_USER_WARNING');
    return;
}

// Função para obter todos os projetos ativos com hierarquia usando uma consulta ajustada
function getProjetosHierarquia($root_id, $sql) {
    $ids = [];
    $sql->limpar();
    $sql->adTabela('projetos');
    $sql->adCampo('projeto_id, projeto_superior, projeto_nome');
    $sql->adOnde('projeto_ativo = 1');
    $sql->adOnde('(projeto_superior_original = ' . (int)$root_id . ' OR projeto_id = ' . (int)$root_id . ')');
    $sql->adOrdem('projeto_superior ASC, projeto_nome ASC');
    $rows = $sql->lista();

    if (is_array($rows)) {
        $all_ids = [];
        foreach ($rows as $row) {
            $all_ids[$row['projeto_id']] = [
                'id' => (int)$row['projeto_id'],
                'superior' => (int)$row['projeto_superior'],
                'nome' => $row['projeto_nome']
            ];
        }

        // Construir hierarquia iterativamente
        foreach ($all_ids as $id => $data) {
            if ($data['superior'] == 0 || isset($all_ids[$data['superior']])) {
                $ids[] = $data;
            }
        }
    }
    return $ids;
}

// Consulta: obtém a hierarquia de projetos ativos
$sql = new BDConsulta;
$ids = getProjetosHierarquia($original_projeto_id, $sql);

$total = count($ids);
if ($total === 0) {
    echo '<div style="padding:8px;">Nenhum projeto ativo encontrado.</div>';
    return;
}

// Divide em 2 blocos equilibrados
$metade = (int)ceil($total / 2);
$col1_ids = array_slice($ids, 0, $metade);
$col2_ids = array_slice($ids, $metade);

// Helper para renderizar uma coluna/tabela com hierarquia
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

        foreach ($projeto_ids as $projeto) {
            $pid = $projeto['id'];
            $superior = $projeto['superior'];
            $nome = $projeto['nome'];

            $p = new CProjeto();
            $p->load($pid);

            $x++;
            $linha_class = ($x % 2) ? 'style="background:#ffffff;"' : 'style="background:#f0f0f0;"';

            // Calcular nível de indentação
            $nivel = 0;
            $current_id = $pid;
            while ($current_id) {
                $temp = new CProjeto();
                $temp->load($current_id);
                if ($temp->projeto_superior) $nivel++;
                $current_id = $temp->projeto_superior;
            }
            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $nivel);
            if ($nivel > 0) $indent .= imagem('icones/subnivel.gif') . '&nbsp;';

            echo '<tr>';
            echo '  <td ' . $linha_class . ' align="center">';
            echo '    <a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=editar&projeto_id=' . $pid . '\');">';
            echo '      <img src="' . acharImagem('icones/editar.gif') . '" border="0" />';
            echo '    </a>';
            echo '  </td>';
            echo '  <td ' . $linha_class . '>' . $indent . link_projeto($pid, $nome) . '</td>';
            echo '  <td ' . $linha_class . '>' . link_usuario($p->projeto_cliente) . '</td>'; // Fiscal
            echo '  <td ' . $linha_class . '>' . link_usuario($p->projeto_supervisor) . '</td>'; // Construtora
            echo '</tr>';
        }
        echo '</table>';
    }
}

// Layout: duas tabelas lado a lado (alinhadas)
echo '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>';
echo '  <td width="50%" valign="top" style="padding-right:10px;">';
renderProjetosColuna($col1_ids);
echo '  </td>';
echo '  <td width="50%" valign="top" style="padding-left:10px;">';
renderProjetosColuna($col2_ids);
echo '  </td>';
echo '</tr></table>';
?>