<?php

require_once('../db_connect/index.php');
include "../send.php";

use PHPMailer\PHPMailer\PHPMailer;

$errors = [];
$nom = "";
$prenom = "";
$email = "";
$password = "";

if (isset($_POST['submit'])) {

    $date = date('Y-m-d');
    $token = md5($email) . mt_rand(999, 999999);
    $verified = 0;

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($nom)) {
        $errors['nom'] = "Nom Required";
    }

    if (empty($prenom)) {
        $errors['prenom'] = "Prenom Required";
    }

    if (empty($email)) {
        $errors['email'] = "email Required";
    }

    if (empty($password)) {
        $errors['password'] = "password Required";
    }


    if (empty($errors)) {
        $user_email = $pdo->prepare("select * from users where email=:email");
        $user_email->execute([
            "email" => $email
        ]);

        $verify = $user_email->fetchAll();
        if (count($verify) == 0) {
            $user = $pdo->prepare("INSERT INTO `users`( `nom`, `prenom`, `email`, `password`,`verified`,`token`,`date_creation_email`) VALUES (:nom,:prenom,:email,:pass,:ver,:token,:date_creation)");
            $user->execute([
                "nom" => $nom,
                "prenom" => $prenom,
                "email" => $email,
                "pass" => password_hash($password, PASSWORD_DEFAULT),
                "ver" => $verified,
                "token" => $token,
                "date_creation" => $date
            ]);
            $link = "<a href='http://localhost/Boycott/VerifiedAccount/index.php?email=" . $email . "&token=" . $token . "&name=".$nom."'>
                Click And Verify Email
            </a>";
            sendmail("BoycottTeam", $email, "LIEN DE VERIFICATION", "Cliquez sur ce lien pour vérifier l'e-mail'.$link.'");
            header("location:../index.php?message=User Added");
        } else {
            $errors['email'] = "Email Already exist";
        }
    }
}



$page_titel = "Create An Account";
$template = "signup";
$show = true;
include "../layout.phtml";
