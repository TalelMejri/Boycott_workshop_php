<?php 
 session_start();
 require_once('../db_connect/index.php');

 $query=$pdo->prepare("Select * from categories");
 $query->execute();
 $category=$query->fetchAll();
 $page_titel="ListCategory";
 $template="ListCategory";
 include "../dashboard.phtml";
 
 ?>