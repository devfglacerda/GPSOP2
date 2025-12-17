<?php
// Configuração do Banco de Dados MySQL
$config['tipoBd'] = 'mysql';
$config['hospedadoBd'] = 'localhost';
$config['nomeBd'] = 'BD_gpsop';
$config['prefixoBd'] = '';
$config['usuarioBd'] = 'root';
$config['senhaBd'] = '';
$config['persistenteBd'] = false;

// Conexão com o banco de dados usando PDO
try {
    $dsn = $config['tipoBd'] . ':host=' . $config['hospedadoBd'] . ';dbname=' . $config['nomeBd'] . ';charset=utf8';
    $options = $config['persistenteBd'] ? array(PDO::ATTR_PERSISTENT => true) : array();
    $conn = new PDO($dsn, $config['usuarioBd'], $config['senhaBd'], $options);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para selecionar apenas projeto_nome
    $sql = "SELECT projeto_nome 
            FROM " . $config['prefixoBd'] . "projetos 
            WHERE projeto_nome IS NOT NULL AND TRIM(projeto_nome) != '' 
            ORDER BY projeto_nome";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $projetos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $totalProjetos = count($projetos);
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Projetos - Estilo Power BI</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f2f1;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin: 0 0 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0078d4;
            color: #fff;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .debug {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }
        .no-data {
            text-align: center;
            color: #777;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard de Projetos</h1>
        <div class="debug">
            <p>Total de projetos exibidos: <?php echo $totalProjetos; ?></p>
        </div>
        <?php if ($totalProjetos > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Projeto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projetos as $projeto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($projeto['projeto_nome']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">Nenhum projeto encontrado.</div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Fechar a conexão
$conn = null;
?>