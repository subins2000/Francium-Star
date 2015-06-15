<?php
// This is where AJAX would sent request to

if(isset($_POST['id']) && isset($_POST['rating']) && $_POST['id'] == "index_page"){
  session_start();
  
  require_once __DIR__ . "/config.php";
  $star->id = $_POST['id'];
  $star->addRating($_SESSION['user_id'], $_POST['rating']);
}
