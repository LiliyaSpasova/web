
<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "schedule";
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if(isset($_POST['gr_id'])) {
            $group_id = $_POST['gr_id'];
           
            $sql = "SELECT groupAdm, speciality,year FROM studentsgroups where id='$group_id' ";
            $result = mysqli_query($conn, $sql);
            $data = []; // Save the data into an arbitrary array.
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            echo json_encode($data); // This will encode the data into a variable that JavaScript can decode.
        }
        $conn->close();
?>
