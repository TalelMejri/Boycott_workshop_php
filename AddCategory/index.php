<?php
session_start();
require_once('../db_connect/index.php');
$errors = [];
if (isset($_POST['submit'])) {
    $nom = $_POST['nom'];
    $name_file = $_FILES['photo']['name'];
    $extension = pathinfo($name_file, PATHINFO_EXTENSION);
    $type_disponible = ['png', 'jpg', 'svg', 'jpeg'];
    if (!in_array($extension, $type_disponible)) {
        $errors['file'] = "Path Info Incorrect";
        goto show;
    }
    $size_file = $_FILES['photo']['size'];
    if ($size_file > 999999) {
        $errors['file'] = "Size Image invalid";
        goto show;
    }

    $name_file = md5(rand()) .'.'. $extension;
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], '../Storage/' . $name_file)) {
        $errors['file'] = "Upload Failed";
        goto show;
    }

    $query=$pdo->prepare("INSERT INTO categories (`nom`,`image`) VALUES (:nom,:photo)");
    $query->execute([
        "nom"=>$nom,
        "photo"=>$name_file
    ]);
    header("location:../ListCategory/index.php");

}

show:
$page_titel = "AddCategory";
$template = "AddCategory";
include "../dashboard.phtml";
