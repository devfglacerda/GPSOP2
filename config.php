<?php
if (!defined('BASE_DIR')) {
    define('BASE_DIR', 'D:/xampp/htdocs/GPSOP_2023');
}

/******** Configuração do Banco de Dados MySQL ********/
$config['tipoBd'] = 'mysql';
$config['hospedadoBd'] = 'localhost';
$config['nomeBd'] = 'BD_gpsop';
$config['prefixoBd'] = '';
$config['usuarioBd'] = 'root';
$config['senhaBd'] = '';
$config['persistenteBd'] = false;
$config['militar'] = 10; // Supondo que seja um parâmetro de configuração (ajuste se necessário)

// Definição da classe Aplic
class Aplic {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function carregarCalendarioJS() {
        // Implementação mínima: carrega o jQuery UI para o datepicker
        // Ajuste conforme necessário para sua lógica real
        echo '<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>';
    }

    // Método para obter a configuração do banco (opcional, para uso futuro)
    public function getConfig() {
        return $this->config;
    }
}

// Instanciação de $Aplic com as configurações
$Aplic = new Aplic($config);
?>