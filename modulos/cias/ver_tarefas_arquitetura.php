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
            background-color: #f4f5f7; /* Fundo claro para a área dos cartões */
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding-bottom: 40px; /* Adiciona espaço abaixo do container para separar os cartões */
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            padding: 10px;
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
            background-color: #fff; /* Fundo branco para o título */
            border-radius: 5px; /* Borda arredondada */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            flex-basis: calc(25% - 20px);
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-content {
            font-size: 12px;
            margin-top: 90px;
            position: relative;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .card.expanded .card-content {
            max-height: 500px;
        }

        .avatar {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            overflow: hidden;
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            cursor: pointer;
        }

         .avatar img {
            width: 100%;
            height: auto;
        }

        .task-info {
            overflow: hidden;
            width: 100%;
            text-align: center;
        }

        .task-info p {
            margin: 0;
            padding: 5px 0;
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
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="title" onclick="toggleAllCards()">
        Arquitetura
    </div>

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
        echo '<div class="card" id="card_' . $usuario_id . '">';
        echo '<div class="avatar" onclick="toggleCard(\'card_' . $usuario_id . '\')"><img src="arquivos/contatos/' . $usuario_id . '.jpg" alt="Avatar"></div>';
        echo '<div class="card-content">';
        
        foreach ($tarefas_usuario as $linha) {
            echo '<div class="task-info">';
            echo '<p><strong>Profissional:</strong> '.link_usuario($linha['usuario_id']).'</p>';
            echo '<p><strong>Projeto:</strong> <span style="font-size: 12px;">' . link_projeto($linha['tarefa_projeto']) . '</span></p>'; /* Aqui defino o tamanho da fonte para o nome do projeto */
            echo '<p><strong>Tarefa:</strong> ' . $linha['tarefa_nome'] . '</p>';        
            echo '<p><strong>Porcentagem:</strong> ' . number_format($linha['tarefa_percentagem']) . '%</p>';
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

<script>
    // Função para expandir e recolher o card ao clicar na foto do profissional
    function toggleCard(cardId) {
        var card = document.getElementById(cardId);
        card.classList.toggle('expanded');
    }

    // Função para expandir ou recolher todas as tarefas ao clicar no título "Arquitetura"
    function toggleAllCards() {
        var cards = document.querySelectorAll('.card'); // Seleciona todos os cartões
        cards.forEach(function(card) {
            var cardId = card.id;
            toggleCard(cardId); // Chama a função toggleCard para cada cartão
        });
    }
</script>

</body>
</html>
