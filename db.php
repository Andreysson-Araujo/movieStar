<?php

    $db_name = "moviestar";
    $db_host = "localhost";
    $db_user = "dracula";
    $db_pass = "senha_desejada";

    $conn = new PDO("mysql:dbname=". $db_name .";host=". $db_host, $db_user, $db_pass);

    //habilitar erros PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);