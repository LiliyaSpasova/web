<?php
$hash = $_GET['h'];
$user = $_GET['e'];

if($hash == hash('sha512', 'ACCEPT')){

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "schedule";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "UPDATE users SET is_approved=true WHERE username = '$user'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
      } else {
        echo "Error updating record: " . $conn->error;
      }
      
      $conn->close();

}else if($hash == hash('sha512', 'DECLINE')){

  //MAIL THE USER NOTIFYING THAT THE ACCOUNT HAS NOT BEEN APPROVED

}
?>