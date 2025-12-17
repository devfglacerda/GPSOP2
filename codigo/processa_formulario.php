<?php
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $empresa = $_POST['empresa'];
    $responsavel = $_POST['responsavel'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cnpj = $_POST['cnpj'];
    
    // Caminho para a biblioteca PHPMailer
    require 'lib/PHPMailer/class.phpmailer.php';
    require 'lib/PHPMailer/class.smtp.php';

    // Instancia o PHPMailer
    $mail = new PHPMailer();

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'webmail.sop.ce.gov.br'; // Novo servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'gpsop@sop.ce.gov.br'; // Novo endereço de e-mail
        $mail->Password = 'GPwebSOP2024'; // Nova senha
        $mail->SMTPSecure = 'ssl'; // Protocolo SSL
        $mail->Port = 465; // Nova porta do servidor

        // Configurações do email
        $mail->setFrom('gpsop@sop.ce.gov.br', 'GPSOP - Solicitação de Cadastro');
        $mail->addAddress('projetos@sop.ce.gov.br');
        $mail->Subject = 'Solicitação de Cadastro';
        
        // Definindo a codificação de caracteres
        $mail->CharSet = 'UTF-8';
        
        // Corpo do email
        $mail->isHTML(false); // Defina como true se o corpo do email contiver HTML
        $mail->Body = "Nome da Empresa: $empresa\n"
                    . "Nome do Responsável: $responsavel\n"
                    . "Email: $email\n"
                    . "Telefone: $telefone\n"
                    . "CNPJ: $cnpj";

        // Envie o email
        $mail->send();
        $mensagem = 'O email foi enviado com sucesso!';
    } catch (Exception $e) {
        $mensagem = "O email não pôde ser enviado. Erro: {$mail->ErrorInfo}";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center; /* Centraliza todo o conteúdo */
        }
        #logo {
            max-width: 250px; /* Ajuste conforme necessário */
            margin-bottom: 20px; /* Adiciona um espaço abaixo da logo */
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            text-align: left; /* Alinha o texto do formulário à esquerda */
        }
        input[type="text"], input[type="email"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 4px;	
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<img src="estilo/logodae.png" alt="Logo da Empresa" id="logo"> <!-- Substitua "estilo/logodae.png" pelo caminho correto da sua logo -->

<h2 style="margin-bottom: 20px;">Formulário de Cadastro</h2> <!-- Adiciona um espaço abaixo do título -->

<?php if (isset($mensagem)): ?>
    <p><?php echo $mensagem; ?></p>
<?php endif; ?>

<form method="post">
    <label for="empresa">Nome da Empresa:</label>
    <input type="text" id="empresa" name="empresa" required>

    <label for="responsavel">Nome do Responsável:</label>
    <input type="text" id="responsavel" name="responsavel" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="telefone">Telefone:</label>
    <input type="tel" id="telefone" name="telefone" required>

    <label for="cnpj">CNPJ:</label>
    <input type="text" id="cnpj" name="cnpj" required>

    <input type="submit" value="Enviar">
</form>

</body>
</html>
