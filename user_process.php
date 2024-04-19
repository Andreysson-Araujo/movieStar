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
  $auth = new UserDAO($conn, $BASE_URL);
  $userData = $auth->verifyToken();


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

  // Upload de imagem
  if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

    $image = $_FILES["image"];

    // Checando tipo da imagem
    if(in_array($image["type"], ["image/jpeg", "image/jpg", "image/png"])) {

      // Checa se é jpg
      if(in_array($image["type"], ["image/jpeg", "image/jpg"])) {
        $imageFile = imagecreatefromjpeg($image["tmp_name"]);
      } else {
        $imageFile = imagecreatefrompng($image["tmp_name"]);
      }

      $imageName = $user->imageGenerateName();

      imagejpeg($imageFile, "./img/users/".$imageName, 100);

      $userData->image = $imageName;

    } else {
      $message->setMessage("Tipo inválido de imagem, envie jpg ou png!", "error", "editprofile.php");
    }
  }

  $userDao->update($userData);


  
  
  //Atualizar senha
} else if($type === "changepassword") {

    $password = filter_input(INPUT_POST, "password");
    $confirmPassword = filter_input(INPUT_POST, "confirmpassword");
    
    //resgata dados do ususario
    $userData = $userDao->verifyToken();
    $id = $userData->id;

    if($password == $confirmPassword) {

      $user = new User();

      $finalPassword = $user->generatePassword($password);
      $user->id = $id;

      $userDao->changePassword($user);

    }else {
      $message->setMessage("Senhas não são iguais!" , "error", "back");
    }


}else{
  $message->setMessage("Informações invalidas!", "error", "index.php");
}