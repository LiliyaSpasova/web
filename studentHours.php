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
    $group_id = $row["group_id"];

    $sql = "SELECT r.building, r.floor, r.room, r.title, r.duration, r.date from roomtaken r join teaches t on t.group_id = r.group_id join users u on t.user_id = u.id where u.id = $userid and now() < (SELECT DATE_ADD(r.date, INTERVAL r.duration HOUR))";
    $result = $conn->query($sql);
    $arrOfClasses = [];
    while(($row = $result->fetch_assoc()) != false) {
        $building = $row["building"];
        $floor = $row["floor"];
        $room = $row["room"];
        $courseName = $row["title"];
        $duration = $row["duration"];
        $date = $row["date"];
        array_push($arrOfClasses, [$building, $floor, $room, $courseName, $date, $duration]);
    }
    
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>


<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="StudentHours.css">
  
<title>Програма</title>
</head>

<body style="background-image:url(img/light_green_background.jpg)">
<a href="indexStudent.php"><i class=material-icons>home</i></a>
<p style="margin: 20px; font-weight:600">Списък с предмети, които предстоят:</p>
    <div id="initialPage">
<?php
    
   echo "<table>";
        echo "<tr>";
        echo "<td>Сграда</td>";
        echo "<td>Етаж</td>";
        echo "<td>Стая</td>";
        echo "<td>Предмет</td>";
        echo "<td>Дата и час на започване</td>";
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
        echo "<td>$row[5]</td>";
        echo "</tr>";
     }
   }
?>
</div>
</body>

</html>