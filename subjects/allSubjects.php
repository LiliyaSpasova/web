<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
</head>

<body>
    
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="allSubjects.css">
    <div id="initialPage">
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
   
   $sql = "SELECT subject_id,subject_name,duration,computers,whiteboard,projector, type FROM subjects";
   $result = $conn->query($sql);
   echo "<table id=subjects_table>";
        echo "<tr>";
        echo "<td>ИД</td>";
        echo "<td>Име</td>";
        echo "<td>Тип</td>";
        echo "<td>Продължителност</td>";
        echo "<td>Необходими ли са компютри?";
        echo "<td>Необходима ли е бяла дъска?";
        echo "<td>Необходим ли е проектор?</td>";
        echo "<td>Изтрий</td>";
        echo "</tr>";
   if ($result->num_rows > 0) {
     // output data of each row
     while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>$row[subject_id]</td>";
        echo "<td>$row[subject_name]</td>";
        echo "<td>$row[type]</td>";
        echo "<td>$row[duration]</td>";
        echo "<td>$row[computers]";
        echo "<td>$row[whiteboard]";
        echo "<td>$row[projector]</td>";
        echo "<td><a href='delete_subject.php?id=".$row['subject_id']."'><i class=material-icons>delete</i></a></td>"; 
        echo "</tr>";
     }
   } else {
     echo "0 results";
   }
   $conn->close();
  
?> 

<p style="margin: 20px; font-weight:600">Ако искате да добавите нов предмет,<a href=subject.php>натиснете тук.</a></p>
</div>
</body>

</html>