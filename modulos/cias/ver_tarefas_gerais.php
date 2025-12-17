<!DOCTYPE html>
<html>
<head>
    <title>Tarefas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f5f7;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center; /* Centraliza os itens horizontalmente */
            margin-top: 20px;
        }

        .column {
            flex-basis: 0;
            flex-grow: 1;
            background-color: #f4f5f7;
            margin: 0 10px;
        }

        .column-header {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
            text-align: center;
            white-space: nowrap; /* Impede a quebra de texto */
            overflow: hidden; /* Oculta o texto que ultrapassa a largura da coluna */
            text-overflow: ellipsis; /* Adiciona "..." quando o texto é cortado */
            font-weight: bold; /* Adiciona negrito */
            font-size: 16px; /* Aumenta o tamanho da fonte */
            cursor: pointer; /* Adiciona o cursor de apontar */
        }

        .column-header:hover {
            background-color: #f0f0f0; /* Muda a cor de fundo quando o mouse passa por cima */
        }

        .card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            display: flex; /* Adiciona display flex */
            flex-direction: column; /* Altera a direção do flex para empilhar verticalmente */
            align-items: center; /* Alinha os itens ao centro verticalmente */
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .avatar {
            width: 52.5px; /* 75px - 30% */
            height: 52.5px; /* 75px - 30% */
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 10px; /* Adiciona margem na parte inferior para separar dos textos */
            position: relative;
            display: flex; /* Adiciona display flex */
            align-items: center; /* Alinha o botão verticalmente */
            justify-content: center; /* Alinha o botão horizontalmente */
            cursor: pointer; /* Muda o cursor para indicar que é clicável */
        }

        .avatar img {
            width: 100%;
            height: auto;
        }

        .task-info {
            overflow: hidden;
            width: 100%; /* Garante que as informações da tarefa ocupem toda a largura do cartão */
            text-align: center; /* Alinha o texto ao centro */
        }

        .task-info p {
            margin: 0; /* Remove a margem padrão dos parágrafos */
            padding: 5px 0; /* Adiciona um pequeno espaçamento entre as informações da tarefa */
        }

        .task-info p strong {
            font-size: 12px; 
        }

        .task-info p strong + p {
            text-align: right;
        }

        .column:not(:last-child) {
            margin-right: 10px;
        }

        .hidden {
            display: none; /* Oculta o elemento */
        }
    </style>
</head>
<body>

<div class="container">

<?php
// Array com os IDs dos departamentos que você deseja filtrar e os títulos correspondentes
$departamentos = [
    26 => 'Elétrica',
    27 => 'Hidrossanitária',
    28 => 'Estrutura',
    29 => 'Mecânica'
];

foreach ($departamentos as $dept_id => $titulo) {
    echo '<div id="dept_'.$dept_id.'" class="column">';
    echo '<div class="column-header" onclick="toggleTasks(\'dept_'.$dept_id.'\')">' . $titulo . '</div>'; // Adiciona um evento onclick para chamar a função JavaScript
    // Restante do código permanece o mesmo

    // Modifica a consulta SQL para incluir o filtro de departamento
    $sql = new BDConsulta;
    $sql->adTabela('tarefas');
    $sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
    $sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
    $sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
    $sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
    $sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');
    $sql->adOnde('projeto_ativo = 1');
    $sql->adOnde('projeto_template = 0');
    $sql->adOnde('departamento_id = ' . $dept_id); // Filtro de departamento
    $sql->adOnde('tarefa_status = 3');
    $sql->adOnde('tarefa_percentagem < 100');
    $sql->adOnde('tarefa_dinamica = 0');
    $sql->adOrdem('usuario_id'); // Ordena por ID do usuário para agrupar as tarefas

    $lista = $sql->Lista();
    $sql->limpar();

    // Array para armazenar as tarefas agrupadas por usuário
    $tarefas_por_usuario = [];

    foreach ($lista as $linha) {
        $usuario_id = $linha['usuario_id'];
        // Agrupa as tarefas por usuário
        $tarefas_por_usuario[$usuario_id][] = $linha;
    }

    // Remove os usuários sem tarefas
    $tarefas_por_usuario = array_filter($tarefas_por_usuario);

    foreach ($tarefas_por_usuario as $usuario_id => $tarefas_usuario) {
        // Exibe o avatar antes do loop de tarefas do usuário, mas dentro do cartão
        echo '<div class="card">';
        echo '<div class="avatar" onclick="toggleInfo('.$usuario_id.')"><img src="arquivos/contatos/' . $usuario_id . '.jpg" alt="Avatar"></div>'; // Adiciona a função de alternância diretamente no avatar

        // Inicia com a classe "hidden" para ocultar as informações do profissional por padrão
        echo '<div id="'.$usuario_id.'" class="task-info hidden">';

        foreach ($tarefas_usuario as $linha) {
            echo '<p><strong>Profissional:</strong> '.link_usuario($linha['usuario_id']).'</p>';
            echo '<p><strong>Projeto:</strong> <span style="font-size: 12px;">' . link_projeto($linha['tarefa_projeto']) . '</span></p>';
            echo '<p><strong>Tarefa:</strong> ' . $linha['tarefa_nome'] . '</p>';
            echo '<p><strong>Porcentagem:</strong> ' . number_format($linha['tarefa_percentagem']) . '%</p>';
            echo '<p><strong>Data Inicial:</strong> <span style="text-align: right;">' . retorna_data($linha['tarefa_inicio']) . '</span></p>';
            echo '<p><strong>Data Final:</strong> <span style="text-align: right;">' . retorna_data($linha['tarefa_fim']) . '</span></p>';
            echo '<p><strong>Descrição:</strong> ' . $linha['tarefa_descricao'] . '</p>';
        }

        echo '</div>'; // Fecha o div task-info
        echo '</div>'; // Fecha o div card
    }

    if (empty($tarefas_por_usuario)) {
        echo '<p>Nenhuma tarefa encontrada.</p>';
    }

    echo '</div>'; // Fecha a coluna atual
}
?>

</div> <!-- Fecha a div container -->

<script>
function toggleInfo(usuario_id) {
    // Encontra o elemento com o ID correspondente ao usuário
    var info = document.getElementById(usuario_id);
    // Alterna a classe .hidden para ocultar ou mostrar as informações
    info.classList.toggle('hidden');
}

function toggleTasks(deptId) {
    var column = document.getElementById(deptId); // Encontra a coluna do departamento
    var cards = column.getElementsByClassName('card'); // Encontra todos os cartões dentro da coluna

    // Verifica se as tarefas estão expandidas ou recolhidas
    var areTasksExpanded = !cards[0].getElementsByClassName('task-info')[0].classList.contains('hidden');

    // Itera sobre todos os cartões e alterna a visibilidade de suas informações
    for (var i = 0; i < cards.length; i++) {
        var taskInfo = cards[i].getElementsByClassName('task-info')[0];
        if (areTasksExpanded) {
            taskInfo.classList.add('hidden'); // Adiciona a classe 'hidden' para ocultar as informações da tarefa
        } else {
            taskInfo.classList.remove('hidden'); // Remove a classe 'hidden' para mostrar as informações da tarefa
        }
    }
}
</script>

</body>
</html>
