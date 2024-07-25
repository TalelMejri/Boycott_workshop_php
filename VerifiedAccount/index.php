<?php

require_once('../db_connect/index.php');
$email = $_GET['email'];
$token = $_GET['token'];
$name = $_GET['name'];
$errors = [];
function validateEmail($email, $token, $pdo)
{
    $res = $pdo->prepare("Select * from users where email=:email");
    $res->execute([
        "email" => $email
    ]);
    $user = $res->fetch();

    if ($user) {
        if ($user['verified']) {
            return 1;
        } else {
            if ($user['token'] != $token) {
                return 2;
            }
            $query = $pdo->prepare("Update users Set token=null,verified=1 where email=:email");
            $query->execute([
                "email" => $email
            ]);
            return 3;
        }
    } else {
        return 0;
    }
}

if (!$email || !$token) {
    $errors[0] = "Email or token missing";
    goto show;
} else {
    $resultat = validateEmail($email, $token, $pdo);
    if ($resultat == 1) {
        $errors[0] = "Your Account is already Verified";
        goto show;
    } else if ($resultat == 2) {
        $errors[0] = "Token doesn't match email address";
        goto show;
    } else if ($resultat == 3) {
        header("location:../congrats?name=" . $name);
    } else if ($resultat == 0) {
        $errors[0] = "User doesn't exist";
        goto show;
    }
}

show:
$page_titel = "Verified Account";
$template = "VerifiedAccount";
$show = true;
include "../layout.phtml";
