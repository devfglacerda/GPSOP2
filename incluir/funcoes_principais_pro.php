<?php

// Verificando se a classe já foi definida antes de declará-la
if (!class_exists('CArquivo')) {

    require_once ($Aplic->getClasseSistema('libmail'));

    class CArquivo {
        // Outros métodos e propriedades da classe CArquivo aqui...

        // Função para gerar o link do arquivo para e-mail externo
        private function link_email_externo($usuario_id, $url) {
            // Substitua esta linha pelo código que gera o link externo específico do seu aplicativo
            // Aqui está um exemplo simples:
            return 'https://www.seusite.com/arquivos/externo.php?id=' . $usuario_id . '&url=' . urlencode($url);
        }

        public function notificar($post = array()) {
            global $Aplic, $config, $localidade_tipo_caract;

            require_once ($Aplic->getClasseSistema('libmail'));

            // Seu código existente...

            foreach ($usuarios as $usuario) {
                if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])) {

                    // Seu código existente...

                    // Obtendo o link do arquivo
                    $link_arquivo = $this->gerarLinkArquivoEmail($usuario['usuario_id'], $this->arquivo_id);

                    // Adicionando o link no corpo do e-mail
                    $corpo_interno .= '<br><a href="' . $link_arquivo . '"><b>Clique para acessar o arquivo</b></a>';

                    // Verificando se o link externo deve ser adicionado
                    if ($Aplic->profissional) {
                        $endereco = $this->link_email_externo($usuario['usuario_id'], $link_arquivo);
                        if ($endereco) $corpo_externo .= '<br><a href="' . $endereco . '"><b>Clique para acessar o arquivo</b></a>';
                    }

                    // Seu código existente...
                }
            }
        }

        // Outros métodos da classe CArquivo aqui...
    }
}

?>
