<?php
// Seu código PHP aqui

// Definir $expirado com base na lógica original
$expirado = false;
if (isset($config['data_limite']) && $config['data_limite']) {
    $hoje_unix = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $campos_data = explode('/', $config['data_limite']);
    $limite_unix = mktime(0, 0, 0, $campos_data[1], $campos_data[0], $campos_data[2]);
    $expirado = ($hoje_unix > $limite_unix);
}

// Início da saída PHP
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (isset($config['gpweb']) ? $config['gpweb'] : 'gpweb') ?></title>
    <link rel="shortcut icon" href="./estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f2f5;
        }

        .container {
            max-width: 360px;
            width: 100%;
            padding: 32px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            margin-bottom: 24px;
            max-width: 100%;
            height: auto;
        }

        .form-input {
            width: calc(100% - 24px);
            margin-bottom: 16px;
            padding: 12px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
        }

        .form-input::placeholder {
            color: #9e9e9e;
        }

        .submit-btn {
            width: 100%;
            background-color: #4CAF50;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .error-msg {
            color: #f44336;
            margin-top: 8px;
        }

        .highlight-section {
            background-color: #f7f7f7;
            padding: 16px;
            border-radius: 8px;
            margin-top: 24px;
        }

        .highlight-section p {
            margin: 8px 0;
            font-weight: bold;
        }

        .access-request {
            margin-top: 20px;
            font-size: 14px;
        }

        .access-request a {
            color: #007bff;
            text-decoration: none;
        }

        .access-request a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="estilo/rondon/imagens/organizacao/10/logodae.png" alt="Logo" class="logo">
        <form method="post" action="index.php" name="frmlogin" autocomplete="off">
            <input type="hidden" name="login" value="<?= time() ?>" />
            <input type="hidden" name="perdeu_senha" value="0" />
            <input type="hidden" name="login" value="entrar" />
            <input type="hidden" name="celular" value="<?= getParam($_REQUEST, 'celular', 0) ?>" />
            <input type="hidden" name="usuario_externo_endereco" value="<?= $usuario_externo_endereco ?>" />
            <input type="hidden" name="gpweb_url_protocol" value="" />
            <input type="hidden" name="full_url" value="" />
            <input id="usuarioNome" name="usuarioNome" type="text" class="form-input" placeholder="Usuário">
            <input id="senha" name="senha" type="password" class="form-input" placeholder="Senha">
            <button type="submit" class="submit-btn">Entrar</button>
        </form>
        <?php if (!$expirado) : ?>
            <div class="highlight-section">
                <p><strong>Manuais:</strong></p>
                <a href="./manuais/Desbloqueio.pdf" target="_blank">Desbloqueio de Pop-up</a><br>
                <a href="/manuais/manual_projetos.pdf" target="_blank">Download de Projetos</a>
            </div>
            <!-- Adicione este link -->
            <div class="access-request">
                <a href="./codigo/processa_formulario.php" id="solicitarAcesso">Solicitar Acesso</a>
            </div>
            <?php if (isset($config['exemplo']) && $config['exemplo']) : ?>
                <div class="mt-4">
                    <h1>Demonstração</h1>
                    <table>
                        <tr>
                            <th>Login</th>
                        </tr>
                        <tr>
                            <?php foreach ($usuarios as $usuario) : ?>
                                <td><?= $usuario ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td colspan="5">Senha: 123456</td>
                        </tr>
                    </table>
                </div>
            <?php endif; ?>
            <?php if (isset($config['data_limite']) && $config['data_limite']) : ?>
                <div class="mt-4">
                    <p>Limite do demonstrativo: <?= $config['data_limite'] ?></p>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="mt-4">
                <h1>O prazo de uso deste demonstrativo expirou em <?= $config['data_limite'] ?>.</h1>
                <h2>Contate a Sistema GP-Web Ltda. através dos telefones: 0800 606 6003 e (51)3026-7509.</h2>
            </div>
        <?php endif; ?>
        <div class="mt-4 error-msg">
            <?= $Aplic->getMsg() ?>
        </div>
    </div>
    <script>
        document.getElementById('solicitarAcesso').addEventListener('click', function(event) {
            event.preventDefault(); // Evita que o link seja aberto normalmente
            var url = this.href;
            var windowFeatures = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=500,height=600';
            window.open(url, 'Formulário de Cadastro', windowFeatures);
        });
    </script>
</body>

</html>




	<SCRIPT TYPE="text/javascript">
	if(window.parent && window.parent.gpwebApp){
		var gpwebApp = parent.gpwebApp;
		gpwebApp.onLogout();
		frmlogin.gpweb_url_protocol.value = window.parent.location.protocol;
		frmlogin.full_url.value = window.parent.getAbsolutePath()+'server';
	}
	else if(window.parent){
		frmlogin.gpweb_url_protocol.value = window.parent.location.protocol;
		frmlogin.full_url.value = getAbsolutePathFree();
	}

	function submitenter(campo,e){
		var codigo;
		if (window.event) codigo = window.event.keyCode;
		else if (e) codigo = e.which;
		else return true;

		if (codigo == 13) {
		   campo.form.submit();
		   return false;
		   }
		else return true;
		}

	function getAbsolutePathFree() {
		var loc = window.parent.location;
		var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
		return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
	}

	</SCRIPT>

