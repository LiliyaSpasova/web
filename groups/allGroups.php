<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="allGroups.css">
</head>

<body id="show_all_groups">
    

    
<?php
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
   
   $sql = "SELECT id, groupAdm, potok, speciality, year, count FROM studentsgroups";
   $result = $conn->query($sql);
   echo "<table id=subjects_table>";
        echo "<tr>";
        echo "<td>ИД</td>";
        echo "<td>Адмистративна група</td>";
        echo "<td>Поток</td>";
        echo "<td>Специалност</td>";
        echo "<td>Курс";
        echo "<td>Брой студенти";
        echo "<td>Изтрий";
        echo "</tr>";
   if ($result->num_rows > 0) {
     // output data of each row
     while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>$row[id]</td>";
        echo "<td>$row[groupAdm]</td>";
        echo "<td>$row[potok]</td>";
        echo "<td>$row[speciality]</td>";
        echo "<td>$row[year]";
        echo "<td>$row[count]";
        echo "<td><a href='delete_subject.php?id=".$row['id']."'><i class=material-icons>delete</i></a></td>"; 
        echo "</tr>";
     }
   } else {
     echo "0 results";
   }
   $conn->close();
  
?> 

<p style="margin: 20px; font-weight:600">Ако искате да добавите нова група, <a href=group.php>натиснете тук.</a></p>

</body>

</html>