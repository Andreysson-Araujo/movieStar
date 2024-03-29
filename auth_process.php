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

if ($type == "register") {
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");
  $confirmPassword = filter_input(INPUT_POST, "confirmpassword");
  
  
  if ($name && $lastname && $email && $password) {
    
    if ($password === $confirmPassword) {
      
      
      //Verificar se o e-mail ja esta cadastrado no sistema
      if ($userDao->findyByEmail($email) === false) {
        
        $user = new User();
        
        $userToken = $user->generateToken();
        $finalPassword = $user->generatePassword($password);
        
        $user->name = $name;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = $finalPassword;
        $user->token = $userToken;
        
        $auth = true;
        
        $userDao->create($user, $auth);
        
      } else {
        
        $message->setMessage("usuario cadastrado tente outro email.", "error", "back");
      }
      
      
    } else {
      $message->setMessage("As senhas não são inguais", "error", "back");
    }
    
  } else {
    $message->setMessage("Por favor prencha todos os campos", "error", "back");
  }
  
} else if ($type === "login") {
  
  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");
  
  //Tenta autenticar usuario
  if ($userDao->authenticateUser($email, $password)) {
    
    $message->setMessage("Seja bem vindo!", "success", "editprofile.php");
    
  } else {
    $message->setMessage("Usuario ou senha incorretos", "error", "back");
  }
  
} else {

  $message->setMessage("Informações invalidas", "error", "back");
  
}