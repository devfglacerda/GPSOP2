<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GPSOP</title>
    <link rel="shortcut icon" href="/estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/x-icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --pbi-yellow: #F2C811; 
            --pbi-dark: #252423; 
            --pbi-gray-bg: #EAEAEA; 
            --pbi-text: #333333; 
            --pbi-blue: #0078D4; 
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--pbi-dark); /* Fundo escuro para contraste */
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Container do Cartão de Login */
        .login-card {
            background-color: white;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 4px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            text-align: center;
            box-sizing: border-box;
            position: relative;
        }

        /* Barra colorida no topo do cartão */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background-color: var(--pbi-yellow);
            border-radius: 4px 4px 0 0;
        }

        .login-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--pbi-dark);
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .login-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        /* Estilo dos Inputs */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
            transition: border-color 0.3s;
            outline: none;
        }

        .input-group input:focus {
            border-color: var(--pbi-blue);
            box-shadow: 0 0 0 2px rgba(0, 120, 212, 0.2);
        }

        /* Botão de Entrar */
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--pbi-dark);
            color: var(--pbi-yellow);
            border: none;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn-login:hover {
            background-color: black;
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        /* Mensagem de Erro (Oculta por padrão) */
        .error-msg {
            display: none;
            background-color: #fde7e9;
            color: #a94442;
            padding: 10px;
            font-size: 13px;
            border-radius: 3px;
            border: 1px solid #ebccd1;
            margin-bottom: 20px;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 11px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-title">GPSOP</div>
        <div class="login-subtitle">Painel de Gestão de Demandas</div>

        <div class="error-msg" id="msgErro">Usuário ou senha incorretos.</div>

        <form id="formLogin">
            <div class="input-group">
                <label for="usuario">Usuário</label>
                <input type="text" id="usuario" name="usuario" placeholder="Digite seu login" required>
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn-login" id="btnEntrar">Entrar</button>
        </form>

        <div class="footer-text">
            &copy; <?php echo date('Y'); ?> Gestão de Projetos e Demandas
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#formLogin').on('submit', function(e) {
                e.preventDefault(); // Impede o recarregamento da página
                
                let btn = $('#btnEntrar');
                let msg = $('#msgErro');
                let usuario = $('#usuario').val();
                let senha = $('#senha').val();

                // Feedback visual de carregamento
                msg.hide();
                btn.prop('disabled', true).text('Verificando...');

                // Envia para o autenticador real
                $.ajax({
                    url: 'auth.php',
                    method: 'POST',
                    data: { usuario: usuario, senha: senha },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Sucesso: Redireciona
                            window.location.href = response.redirect;
                        } else {
                            // Erro: Mostra mensagem e reseta botão
                            msg.text(response.message).fadeIn();
                            btn.prop('disabled', false).text('ENTRAR');
                            // Treme a caixa (efeito visual opcional)
                            $('.login-card').addClass('shake');
                            setTimeout(function(){ $('.login-card').removeClass('shake'); }, 500);
                        }
                    },
                    error: function() {
                        msg.text('Erro de conexão com o servidor.').fadeIn();
                        btn.prop('disabled', false).text('ENTRAR');
                    }
                });
            });
        });
    </script>
</body>
</html>