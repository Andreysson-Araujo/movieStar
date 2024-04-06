<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("models/Message.php");
require_once("models/User.php");

//require_once("templates/header.php");
$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);


$type = filter_input(INPUT_POST, "type");

if($type === "update") {
  //Resgatando dados do Usuario
  $userData = $userDao->verifyToken();


  //Rece dados do post
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");

  //cria um novo objeto de usuario
  $user = new User();

  //prencher dados do ususario
  $userData->name = $name;
  $userData->lastname = $lastname;
  $userData->email = $email;
  $userData->bio = $bio;

  $userDao->update($userData);


  
  
  //Atualizar senha
} else if($type === "changepassword") {

}else{
  $message->setMessage("Informações invalidas!", "error", "index.php");
}