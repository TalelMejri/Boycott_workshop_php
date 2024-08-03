<?php

require_once('../db_connect/index.php');
session_start();
$errors = [];

$email = "";
$password = "";

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];


    if (empty($email)) {
        $errors['email'] = "email Required";
    }

    if (empty($password)) {
        $errors['password'] = "password Required";
    }

    if (empty($errors)) {
        $res=$pdo->prepare("select * from users where email=:email");
        $res->execute([
            "email"=>$email
        ]);
        $user=$res->fetch();
        if($user){
            if(password_verify($password,$user['password'])){
                $_SESSION['nom']=$user['nom'];
                $_SESSION['prenom']=$user['prenom'];
                $_SESSION['iduser']=$user['id'];
                $_SESSION['email']=$user['email'];
                header("location:../index.php");
            }else{
                $errors['global']="Email Or  Password Inccorect";
            }
        }else{
            $errors['global']="Email Not Exist";
        }
    }
}



$page_titel = "Login";
$template = "login";
$show = true;
include "../layout.phtml";
