
<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "schedule";
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if(isset($_POST['b'])&&isset($_POST['f'])&&isset($_POST['st'])&&isset($_POST['ty'])&&isset($_POST['l'])&&isset($_POST['s'])
        &&isset($_POST['gr'])&&isset($_POST['y'])&&isset($_POST['d'])&&isset($_POST['dur'])
        &&isset($_POST['r'])&&isset($_POST['s_id'])&&isset($_POST['g_id'])) {
            $building = $_POST['b'];
            $floor = $_POST['f'];
            $subjectTitle = $_POST['st'];
            $room = $_POST['r'];
            $courseType = $_POST['ty'];
            $lecturerName = $_POST['l'];
            $speciality = $_POST['s'];
            $groupAdm = $_POST['gr'];
            $y = $_POST['y'];
            $date = $_POST['d'];
            $duration = $_POST['dur'];
            $sub_id = $_POST['s_id'];
            $group_id = $_POST['g_id'];
            $sql = "INSERT INTO roomtaken 
            VALUES ('$building','$room','$floor','$subjectTitle','$courseType','$lecturerName','$speciality','$groupAdm','$y','$date','$duration','$sub_id','$group_id')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        
        $conn->close();
?>
