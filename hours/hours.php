<!DOCTYPE html>
<html>
<head>
  
<title>Title of the document</title>
</head>

<body>
    
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="hours.css">
<p style="margin: 20px; font-weight:600">Списък с нещата, които преподавате</p>
    <div id="initialPage">
<?php
    session_start();
    $user = $_SESSION['username'];
    $userId = $_SESSION['id'];
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "schedule";
   
   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname);
   // Check connection
   if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
   }
   
   $sql = "SELECT sub_id, group_id  FROM teaches where user_id=$userId";
   $result = $conn->query($sql);
   echo "<table >";
        echo "<tr>";
        echo "<td>Име</td>";
        echo "<td>Име предмет</td>";
        echo "<td>Тип предмет</td>";
        echo "<td>Номер група</td>";
        echo "<td>Поток</td>";
        echo "<td>Специалност</td>";
        echo "<td>Изтрий</td>";
        echo "</tr>";
   if ($result->num_rows > 0) {
     // output data of each row
     while($row = $result->fetch_assoc()) {
        $getSubData = "SELECT subject_name,type  FROM subjects where subject_id=$row[sub_id]";
        $resSub = $conn->query($getSubData);
        $subRow = $resSub->fetch_assoc();
        $getGroupData = "SELECT groupAdm,potok,speciality  FROM studentsGroups where id=$row[group_id]";
        $resGroup = $conn->query($getGroupData);
        $groupRow = $resGroup->fetch_assoc();
        echo "<tr>";
        echo "<td>$user</td>";
        echo "<td>$subRow[subject_name]</td>";
        echo "<td>$subRow[type]</td>";
        echo "<td>$groupRow[groupAdm]</td>";
        echo "<td>$groupRow[potok]</td>";
        echo "<td>$groupRow[speciality]</td>";
        echo "<td><a href='delete_hour.php?sub_id=".$row['sub_id']."&group_id=".$row['group_id']."'><i class=material-icons>delete</i></a></td>"; 
        echo "</tr>";
     }
   } else {
     echo "0 results";
   }
   $conn->close();
  
?> 

<p style="margin: 20px; font-weight:600">Ако искате да добавите нов елемент,<a href=add_hour.php>натиснете тук.</a></p>
</div>
</body>

</html>