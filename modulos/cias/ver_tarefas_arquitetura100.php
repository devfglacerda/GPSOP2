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
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            flex-basis: calc(25% - 20px); /* Ajusta a largura para 4 blocos */
            box-sizing: border-box;
            position: relative;
            overflow: hidden; /* Adiciona overflow:hidden para esconder partes do avatar fora do bloco */
        }


        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

     .card-content {
    font-size: 12px; /* Altere o tamanho da fonte para o desejado */
    margin-top: 90px;
    position: relative;
}

        .avatar {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            overflow: hidden;
            position: absolute;
            top: 20px; /* Move a foto um pouco mais para baixo */
            left: 50%;
            transform: translateX(-50%);
        }

        .avatar img {
            width: 100%;
            height: auto;
        }

        .task-info {
            overflow: hidden;
        }

         .task-info {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .task-info p strong {
            font-size: 12px; /* Define um tamanho de fonte maior para o nome do projeto */
        }

        /* Alinha o texto das datas à direita */
        .task-info p strong + p {
            text-align: right;
        }

        /* Adiciona margem inferior para separar visualmente as tarefas de diferentes profissionais */
        .card:not(:last-child) {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<div class="container">

    <?php
// Aqui vem o código PHP para listar as tarefas e apresentá-las com o estilo do Trello

$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'pr', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->esqUnir('tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('tarefa_depts', 'tp', 'tp.tarefa_id = tarefas.tarefa_id');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = tp.departamento_id');
$sql->adCampo('projeto_nome,  usuario_id, projeto_setor, tarefa_projeto, tarefa_nome, dept_nome, tarefa_percentagem, tarefa_inicio, tarefa_fim, tarefa_prioridade, tarefa_descricao, tarefa_tipo, tarefa_status');        
$sql->adOnde('projeto_ativo = 1');
$sql->adOnde('projeto_template = 0');
$sql->adOnde('departamento_id = 7');
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
    echo '<div class="card">';
    echo '<div class="avatar"><img src="arquivos/contatos/' . $usuario_id . '.jpg" alt="Avatar"></div>';
    echo '<div class="card-content">';
    
    foreach ($tarefas_usuario as $linha) {
        echo '<div class="task-info">';
        echo '<p><strong>Profissional:</strong> '.link_usuario($linha['usuario_id']).'</p>';
        echo '<p><strong>Projeto:</strong> <span style="font-size: 12px;">' . link_projeto($linha['tarefa_projeto']) . '</span></p>'; /* Aqui defino o tamanho da fonte para o nome do projeto */
        echo '<p><strong>Tarefa:</strong> ' . $linha['tarefa_nome'] . '</p>';        
        echo '<p><strong>Porcentagem:</strong> ' . number_format($linha['tarefa_percentagem']) . '%</p>';
        echo '<p><strong>Tipo:</strong> ' . ($linha['tarefa_tipo'] && isset($tipo2[$linha['tarefa_tipo']]) ? $tipo2[$linha['tarefa_tipo']] : '&nbsp;') . '</p>';
        echo '<p><strong>Data Inicial:</strong> <span style="text-align: right;">' . retorna_data($linha['tarefa_inicio']) . '</span></p>';
        echo '<p><strong>Data Final:</strong> <span style="text-align: right;">' . retorna_data($linha['tarefa_fim']) . '</span></p>';
        echo '<p><strong>Descrição:</strong> ' . $linha['tarefa_descricao'] . '</p>';
        echo '</div>'; // Fecha o div task-info
    }
    
    echo '</div>'; // Fecha o div card-content
    echo '</div>'; // Fecha o div card
}

if (empty($tarefas_por_usuario)) {
    echo '<p>Nenhuma tarefa encontrada.</p>';
}
?>


</div>

</body>
</html>
