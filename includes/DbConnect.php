<?php
    
    class DbConnect {

        // Variável para armazenar o link do banco de dados
        private $con;

        // Classe Construtor
        function __construct()
        {
            
        }

        // Este método irá conectar ao banco de dados
        function connect() {

            // Incluindo o arquivo constants.php para obter as constantes do banco de dados
            include_once dirname(__FILE__) . '/Constants.php';

            // conectando ao banco de dados mysql
            $this->con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // Verificar se ocorreu algum erro durante a conexão
            if (mysqli_connect_errno()) {
                # code...
                echo "Falha ao conectar com banco de dados mysql: " .mysqli_connect_error();
            }

            // finalmente retornando o link de conexão
            return $this->con;
        }
    }
?>