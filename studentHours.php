<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "schedule";
    session_start();
    $userid = $_SESSION["id"];
    $sql = "SELECT t.group_id from users u join teaches t on t.user_id = $userid";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if(mysqli_num_rows($result) == 0) {
        die("Има грешка в профила Ви, свържете се с Отдел Студенти.");
    }
    $group_id = $row["group_id"];

    $sql = "SELECT r.building, r.floor, r.room, r.title, r.duration from roomtaken r join studentsgroups g on r.group_id = $group_id";
    $result = $conn->query($sql);
    $arrOfClasses = [];
    while(($row = $result->fetch_assoc()) != false) {
        $building = $row["building"];
        $floor = $row["floor"];
        $room = $row["room"];
        $courseName = $row["title"];
        $duration = $row["duration"];
        array_push($arrOfClasses, [$building, $floor, $room, $courseName, $duration]);
    }
    
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>


<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="/hours/hours.css">
  
<title>Програма</title>
</head>

<body>
<a href="..\index.php"><i class=material-icons>home</i></a>
<p style="margin: 20px; font-weight:600">Списък с предмети, които предстоят:</p>
    <div id="initialPage">
<?php
    
   echo "<table>";
        echo "<tr>";
        echo "<td>Сграда</td>";
        echo "<td>Етаж</td>";
        echo "<td>Стая</td>";
        echo "<td>Предмет</td>";
        echo "<td>Продължителност</td>";
        echo "</tr>";
   if (count($arrOfClasses) > 0) {
        foreach($arrOfClasses as $row) {
        echo "<tr>";
        echo "<td>$row[0]</td>";
        echo "<td>$row[1]</td>";
        echo "<td>$row[2]</td>";
        echo "<td>$row[3]</td>";
        echo "<td>$row[4]</td>";
        echo "</tr>";
     }
   } else {
     echo "0 results";
   }
?>
</div>
</body>

</html>