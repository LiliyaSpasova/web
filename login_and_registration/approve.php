<?php
$hash = $_GET['h'];
$email = $_GET['e'];

if($hash == hash('sha512', 'ACCEPT')){

  //ACCESS MYSQL DATABASE
  //FIND THE USER AND SET user_approved = 1
  //WHERE email = $email

}else if($hash == hash('sha512', 'DECLINE')){

  //MAIL THE USER NOTIFYING THAT THE ACCOUNT HAS NOT BEEN APPROVED

}
?>