<?php

namespace app\models;

use \PDO;
use \PDOException;
use app\models\ConnectModel;


class UsersModel extends ConnectModel{

  private $db;

  public function __construct(){
    $this->db = $this->connect();
    $this->usersTable($this->connect());
  } 
  
  public function getAllUser():array{
    try{
      $sql = $this->db->prepare('SELECT * FROM users');
      $sql->execute();

      $data = $sql->fetchAll(PDO::FETCH_ASSOC);

      return $data;

    }catch(PDOException $pe){
      throw new PDOException("Erro ao buscar o usuário ". $pe->getMessage());
    }
  }

  public function getUser(object|array $user):array{
    try{
      $sql = $this->db->prepare('SELECT * FROM users WHERE userhash = :userhash AND password = :password');
      $sql->bindValue(':userhash', $user['userhash']);
      $sql->bindValue(':password', $user['password']);
      $sql->execute();

      $data = $sql->fetchAll(PDO::FETCH_ASSOC);

      return $data;

    }catch(PDOException $pe){
      throw new PDOException("Erro ao buscar o usuário ". $pe->getMessage());
    }
  }
  
  public function activeUser(int $user):object|array {
    $data = [];

    try{
      $sql = $this->db->prepare('SELECT active FROM users WHERE userhash = :user OUR email = :user');
      $sql->bindValue(':user', $user);
      $sql->execute();
      
      $data = $sql->fetch(PDO::FETCH_ASSOC);

      return $data;
      
    }catch(PDOException $pe){
      return throw new PDOException("Erro ao buscar o usuário ". $pe->getMessage());
    }
  }
  
  public function setNewUser(object|array $data):bool{
    try{
      $sql = $this->db->prepare('INSERT INTO users(userhash, name, email, password, identification, dateofbirth, gender, phone) VALUES(:userhash, :name, :email, :password, :identification, :dateofbirth, :gender, :phone);');
      
      $sql->bindValue(':userhash', $data['userhash']);
      $sql->bindValue(':name', $data['name']);
      $sql->bindValue(':email', $data['email']);
      $sql->bindValue(':password', $data['password']);
      $sql->bindValue(':identification', $data['identification']);
      $sql->bindValue(':dateofbirth', $data['dateofbirth']);
      $sql->bindValue(':gender', $data['gender']);
      $sql->bindValue(':phone', $data['phone']);
      
      if($sql->execute()) {
        
        if($sql->rowCount() > 0){
          
          return true;
          
        } else{ 
          
          return false;
        }
        
      }else { 
        
        return false;
      }
      
    }catch(PDOException $pe){
      throw new PDOException("Erro ao criar o usuário: ". $pe->getMessage());
    }
  }
  
  public function updateDataUser(object|array $data){
    try{

      $sql = $this->db->prepare('UPDATE users SET name = :name, email = :email, password = :password, identification = :identification, dateofbirth = :dateofbirth, gender = :gender, phone = :phone WHERE userhash = :hash');
      
      $sql->bindValue(':name', $data['name']);
      $sql->bindValue(':email', $data['email']);
      $sql->bindValue(':password', $data['password']);
      $sql->bindValue(':identification', $data['identification']);
      $sql->bindValue(':dateofbirth', $data['dateofbirth']);
      $sql->bindValue(':gender', $data['gender']);
      $sql->bindValue(':phone', $data['phone']);
      $sql->bindValue(':hash', $data['hash']);

      if($sql->execute()){
        return true;
      }else{
        return false;
      }
      
    }catch(PDOException $pe){
      throw new PDOException("Erro ao atualizar usuário: ". $pe->getMessage());
    }
  }

  public function desactivateAccount(int $hash){
    try{

      $sql = $this->db->prepare('UPDATE users set active = :value WHERE userhash = :hash');
      $sql->bindValue(':value',false);
      $sql->bindValue(':hash',$hash);

      $sql->execute();

    }catch(PDOException $pe){
      throw new PDOException("Erro ao desativar usuário: ". $pe->getMessage());
    }
  }

  public function deleteUser(int $hash){
    try{
      $sql = $this->db->prepare('DELETE FROM users WHERE userhash = :hash');
      $sql->bindValue(':hash', $hash);
      
      $sql->execute();
    }catch(PDOException $pe){
      throw new PDOException("Erro ao deletar o usuário: ". $pe->getMessage());
    }
  }


}