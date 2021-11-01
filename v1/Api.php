<?php
   // obtendo a classe dboperation
   require_once '../includes/DbOperation.php';

   // função de validação de todos os parâmetros estão disponíveis
   // vamos passar os parâmetros necessários para esta função
   function isTheseParametersAvailable($params) {

      // assumindo que todos os parâmetros estão disponíveis
      $available = true;
      $missingparams = "";

      foreach($params as $param){
         if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
         $available = false; 
         $missingparams = $missingparams . ", " . $param; 
         }
      }
      //se os parâmetros estão faltando 
      if(!$available){
         $response = array(); 
         $response['error'] = true; 
         $response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';

         // exibindo erro
         echo json_encode($response);

         // parando a execução posterior
         die();
      }
   }
   // uma matriz para exibir a resposta
   $response = array();

   // se for uma chamada api
   // isso significa que um parâmetro get chamado api call é definido no URL
   // e com este parâmetro estamos concluindo que é uma chamada de API
   if (isset($_GET['apicall'])) {
      # code...
      switch ($_GET['apicall']) {

         // a operação CREATE
         // se o valor da chamada da API for 'createhero'
         // vamos criar um registro no banco de dados
         case 'createhero':
            # code...
            // primeiro verifique se os parâmetros necessários para esta solicitação estão disponíveis ou não
            isTheseParametersAvailable(array('name','realname','rating','teamaffiliation'));

            // criando um novo objeto dboperation
            $db = new DbOperation();

            // criando um novo registro no banco de dados
            $result = $db->createHero(
               $_POST['name'],
               $_POST['realname'],
               $_POST['rating'],
               $_POST['teamaffiliation']
            );

            // se o registro for criado adicionando sucesso à resposta
            if ($result) {
               # code...
               $response['error'] = false;

               // na mensagem, temos uma mensagem de sucesso
               $response['message'] = 'Heroi adicionado com sucesso..!';

               // e estamos obtendo todos os heróis do banco de dados na resposta
               $response['heroes'] = $db->getHeroes();

            } else {
               // se o registro não for adicionado, significa que há um erro
               $response['error'] = true;

               // e temos a mensagem de erro
               $response['message'] = 'Ocorreu algum erro, tente novamente';

            }
            break;
         
            // Operação Ler
            // se a chamada for getheroes
            case 'getheroes': 
               $db =  new DbOperation();
               $response['error'] = false;
               $response['message'] = 'Solicitação concluída com sucesso..!';
               $response['heroes'] = $db->getHeroes();
               break;

               // Operação atualizar
               case 'updatehero':
                  isTheseParametersAvailable(array('id','name','realname','rating','teamaffiliation'));
                  $db = new DbOperation();
                  $result = $db->updateHero(
                  $_POST['id'],
                  $_POST['name'],
                  $_POST['realname'],
                  $_POST['rating'],
                  $_POST['teamaffiliation']
                  );

                  if ($result) {
                     # code...
                     $response['error'] = false;
                     $response['message'] = 'Heroi atualizado com sucesso..!';
                     $response['heroes'] = $db->getHeroes();

                  } else {
                     $response['error'] = true;
                     $response['message'] = 'Algum erro ocorreu, por favor tente novamente..!';
                  }
                  break;

                  // Operação deletar
                  case 'deletehero';

                  // para a operação de exclusão, estamos obtendo um parâmetro GET da url com o id do registro a ser excluído
                  if (isset($_GET['id'])) {
                     # code...
                     $db = new DbOperation();
                     if ($db->deleteHero($_GET['id'])) {
                        # code...
                        $response['error'] = false;
                        $response['message'] = 'Heroi deletado com sucesso..!';
                        $response['heroes'] = $db->getHeroes();

                     } else {
                        $response['error'] = true;
                        $response['message'] = 'Algum erro ocorreu por favor tente novamente';

                     }
                     }  else {
                        $response['error'] = true;
                        $response['message'] = 'Nada para deletar, forneça um id, por favor';
                  }

                  break;
      }
   } else {
      // se não for chamada de API
      // empurrando os valores apropriados para a matriz de resposta
      $response['error'] = true; 
      $response['message'] = 'Invalid API Call';
   }

   // exibindo a resposta na estrutura json
   echo json_encode($response);
?>