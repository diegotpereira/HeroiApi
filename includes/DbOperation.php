<?php

   class DbOperation {
       
    // Link de conexão de banco de dados
    private $con;

    // Classe Construtor
    function __construct()
    {
        // Obtendo o arquivo DbConnect.php
        require_once dirname(__FILE__) . '/DbConnect.php';

        // Criação de um objeto DbConnect para se conectar ao banco de dados
        $db = new DbConnect();

        // Inicializando nosso link de conexão desta classe
        // chamando o método connect da classe DbConnect
        $this->con = $db->connect();
    }

    // Operação de criação
    // Quando este método é chamado, um novo registro é criado no banco de dados
    function createHero($name, $realname, $rating, $teamaffiliation){
        $stmt = $this->con->prepare("INSERT INTO heroes (name, realname, rating, teamaffiliation) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $realname, $rating, $teamaffiliation);
        if($stmt->execute())
        return true; 
        return false; 
    }

    // Operação de Leitura
    // Quando este método é chamado ele está retornando todos os registros existentes no banco de dados
    function getHeroes() {

        $stmt = $this->con->prepare("SELECT id, name, realname, rating, teamaffiliation FROM heroes");
        $stmt->execute();
        $stmt->bind_result($id, $name, $realname, $rating, $teamaffiliation);

        $heroes = array();

        while($stmt->fetch()) {
             $hero  = array();
             $hero['id'] = $id;
             $hero['name'] = $name;
             $hero['realname'] = $realname;
             $hero['rating'] = $rating;
             $hero['teamaffiliation'] = $teamaffiliation;

             array_push($heroes, $hero);
        }
        return $heroes;
    }

    // Operação atualizar
    // Quando este método é chamado, o registro com o id fornecido é atualizado com os novos valores fornecidos
    function updateHero($id, $name, $realname, $rating, $teamaffiliation) {
        $stmt = $this->con->prepare("UPDATE heroes SET name =?, realname = ?, rating = ?, teamaffiliation = ? WHERE id = ?");
        $stmt->bind_param("ssisi", $name, $realname, $rating, $teamaffiliation, $id);
        if ($stmt->execute()) 
            # code...
            return true;
            return false;
        
    }

    // Operação deletar
    // Quando este método é chamado, o registro é deletado para o id fornecido
    function deleteHero($id) {
        $stmt = $this->con->prepare("DELETE FROM heroes WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) 
            # code...
            return true;
            return false;
        
    }
   }
?>
