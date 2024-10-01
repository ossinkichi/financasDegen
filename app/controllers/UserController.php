<?php

namespace app\controllers;

use \app\models\UsersModel;
use \Exception;

class UserController {
  private $users;

  public function __construct(){
    $this->users = new UsersModel;
  }

  public function getAllUser() {
    return $this->users->getAllUser();
  }

  public function getUser(object $data):int {
    $this->verifyMethod('POST','Não é possível enviar os dados por GET');

    foreach($data as $key => $value){
      htmlspecialschars($value);
      if(empty($value)){
        $field[] = $key;
      }
    }

    if(empty($field)){
      http_response_code(400);
      echo json_encode(['error' => 'O campo obrigatorio não preenchido ','filds' => $field.]);
      return;
    }
    
    $userData = $this->users->getUser($data);
    
    if(!$userData['active']){
      http_response_code(403);
      echo json_encode(['error' => 'Usuário está com a conta inativa, para acessar novamente nossa aplcacao é necessário que ative a sua conta']);
      return;
    };

    if(!password_verify($data['password'],$userData['password'])){
      http_response_code(401);
      echo jsone_encode(['error' => 'Senha ou usuário incorreta']);
      return;
    }
  }

  public function setNewUser(array $data){
    $this->verifyMethod('POST','Não é possível enviar os dados por GET');
    
    $user = [
      'name' => filter_var($data['name'], FILTER_SANITIZE_STRING), 
      'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL), 
      'password' => filter_var($data['password'], FILTER_SANITIZE_STRING), 
      'identification' => '', 
      'dateofbirth' => '', 
      'gender' => filter_var($data['gender'], FILTER_SANITIZE_STRING), 
      'phone' => filter_var($data['name'], FILTER_SANITIZE_STRING)
    ];

    foreach ($user as $key => $value) {
      if(empty($value)){
        $error[] = $key;
      }
    }

    if(!empty($error)){
      http_response_code(400);
      echo json_encode(['error' => $error]);
      return;
    }

    $user['userhash'] => $this->createHash($user['identification']);
    
    $this->users->setNewUser($user);    
  }

  private function createHash(string $hash):string {
    return hash( 'sha256', $hash )
  }

  private function verifyMethod($method,$message){
    if($_SERVER['REQUEST_METHOD'] != $method){
      header('Content-Type: application/json');
      http_response_code(405);
      echo json_encode(['Error' => $message]);
    }
  }

  public function userUpdated(array $data){
    $this->verifyMethod('POST','Não é possível enviar os dados por GET');

    $user = [
      'userhash' =>, $filter_var($data['userhash'], FILTER_SANITIZE_INT),
      'name' => filter_var($data['name'], FILTER_SANITIZE_STRING), 
      'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL), 
      'password' => filter_var($data['password'], FILTER_SANITIZE_STRING), 
      'identification' => '', 
      'dateofbirth' => '', 
      'gender' => filter_var($data['gender'], FILTER_SANITIZE_STRING), 
      'phone' => filter_var($data['name'], FILTER_SANITIZE_STRING)
    ];

    foreach ($user as $key => $value) {
      if(empty($value)){
        $error[] = $key;
      }
    }

    if(!empty($error)){
      http_response_code(400);
      echo json_encode(['error' => $error]);
      return;
    }
  }
  
  public function desactivateAccount(int $hash)
}
