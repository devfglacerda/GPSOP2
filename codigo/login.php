<?php
// Definir $expirado com base na lógica original
$expirado = false;
if (isset($config['data_limite']) && $config['data_limite']) {
    $hoje_unix = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    $campos_data = explode('/', $config['data_limite']);
    $limite_unix = mktime(0, 0, 0, $campos_data[1], $campos_data[0], $campos_data[2]);
    $expirado = ($hoje_unix > $limite_unix);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (isset($config['gpweb']) ? $config['gpweb'] : 'GPweb') ?></title>
    <link rel="shortcut icon" href="./estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        /* Reset b�sico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
        }

        .login-container {
            background: #fff;
            width: 100%;
            max-width: 400px;
            padding: 40px 32px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 0.7s ease;
        }

        .login-container img {
            width: 360px;
            margin-bottom: 20px;
        }

        .login-container h1 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 24px;
        }

        .form-input {
            width: 100%;
            padding: 14px;
            margin-bottom: 18px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .form-input:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 6px rgba(76, 175, 80, 0.3);
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #43A047, #2E7D32);
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            padding: 14px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #388E3C, #1B5E20);
            transform: translateY(-2px);
        }

        .error-msg {
            margin-top: 14px;
            font-size: 14px;
            color: #f44336;
        }

        .highlight-section {
            margin-top: 22px;
            padding: 14px;
            background: #f9f9f9;
            border-radius: 10px;
            font-size: 14px;
            text-align: left;
        }

        .highlight-section a {
            color: #2E7D32;
            text-decoration: none;
            font-weight: 500;
        }

        .highlight-section a:hover {
            text-decoration: underline;
        }

        .highlight-section {
            text-align: center;     
        }   

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }

            
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="estilo/rondon/imagens/organizacao/10/logodae.png" alt="Logo">

        <h1>Acesso ao Sistema</h1>

        <form method="post" action="index.php" name="frmlogin" autocomplete="off">
            <input type="hidden" name="login" value="<?= time() ?>" />
            <input type="hidden" name="perdeu_senha" value="0" />
            <input type="hidden" name="login" value="entrar" />
            <input type="hidden" name="celular" value="<?= getParam($_REQUEST, 'celular', 0) ?>" />
            <input type="hidden" name="usuario_externo_endereco" value="<?= $usuario_externo_endereco ?>" />
            <input type="hidden" name="gpweb_url_protocol" value="" />
            <input type="hidden" name="full_url" value="" />

            <input id="usuarioNome" name="usuarioNome" type="text" class="form-input" placeholder="Usu&aacute;rio">
            <input id="senha" name="senha" type="password" class="form-input" placeholder="Senha">
            <button type="submit" class="submit-btn">Entrar</button>
        </form>

        <?php if (!$expirado) : ?>
           <div class="highlight-section" style="text-align: center;">
    
    <a href="/manuais/manual_projetos.pdf" target="_blank"><strong>MANUAL GPSOP</strong></a>
</div>

<div class="highlight-section" style="text-align: center;">
    <a href="https://gpsop.sop.ce.gov.br/dashboard/" target="_blank"><strong>DASHBOARD INTERATIVA</strong></a>
</div>

            <?php if (isset($config['exemplo']) && $config['exemplo']) : ?>
                <div class="highlight-section">
                    <p><strong>Demonstraçãoo:</strong></p>
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
                <div class="highlight-section">
                    <p>Limite do demonstrativo: <?= $config['data_limite'] ?></p>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="highlight-section">
                <h2>O prazo de uso deste demonstrativo expirou em <?= $config['data_limite'] ?>.</h2>
                <p>Contate a Sistema GP-Web Ltda. através dos telefones: <br> 0800 606 6003 e (51) 3026-7509.</p>
            </div>
        <?php endif; ?>

        <div class="error-msg">
            <?= $Aplic->getMsg() ?>
        </div>
    </div>

    <script>
        if (window.parent && window.parent.gpwebApp) {
            var gpwebApp = parent.gpwebApp;
            gpwebApp.onLogout();
            frmlogin.gpweb_url_protocol.value = window.parent.location.protocol;
            frmlogin.full_url.value = window.parent.getAbsolutePath() + 'server';
        } else if (window.parent) {
            frmlogin.gpweb_url_protocol.value = window.parent.location.protocol;
            frmlogin.full_url.value = getAbsolutePathFree();
        }

        function getAbsolutePathFree() {
            var loc = window.parent.location;
            var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
            return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
        }
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