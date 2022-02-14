<?php
    function import($file) {
        try {
            if (($open = fopen($file, "r")) != false) {
                while(($data = fgetcsv($open, 1000, ",")) != false) {
                    $arr[] = $data;
                }
                fclose($open);
                return $arr;
            }
        }
        catch(Exception $ex) {
            die("Грешка при четенето: " . $ex->getMessage());
        }
    }

    function getStudentData($arrayOfStudents) {
        try {
            $all_data = [];
            $cyr = ['Љ', 'Њ', 'Џ', 'џ', 'ш', 'ђ', 'ч', 'ћ', 'ж', 'љ', 'њ', 'Ш', 'Ђ', 'Ч', 'Ћ', 'Ж','Ц','ц', 'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п', 'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я', 'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П', 'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
            ];
            $lat = ['Lj', 'Nj', 'Dž', 'dž', 'š', 'đ', 'č', 'ć', 'ž', 'lj', 'nj', 'Š', 'Đ', 'Č', 'Ć', 'Ž','C','c', 'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p', 'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya', 'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P', 'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
            ];

            foreach($arrayOfStudents as $val) {
                $data = explode(" ", $val[0]);
                $fname = str_replace($cyr, $lat, $data[0]);
                $sname = str_replace($cyr, $lat, $data[1]);
                $lname = str_replace($cyr, $lat, $data[2]);
                $username = $fname[0] . $sname[0] . $lname;
                $username = strtolower($username);
                $name = $val[0];
                $email = $username . "@uni-sofia.bg";
                echo $username;
                echo $val[2];
                $password = password_hash($val[2], PASSWORD_DEFAULT);
                array_push($all_data, [$username, $password, $email, $name]);
            }
            return $all_data;
        }
        catch(Exception $ex) {
            die("Грешка при преобразуването: ". $ex->getMessage());
        }
    }

    function inputAccounts($data, $groupId){
        try {
            $sql = "INSERT into users (username, password, email, role_id, name, is_approved) values (:username, :password, :email, 2, :name, true)";
            require_once("config.php");

            foreach($data as $val) {
                if($stmt = $pdo->prepare($sql)) {
                    if ($stmt->execute(
                        array(
                            ":username" => $val[0],
                            ":password" => $val[1],
                            ":email" => $val[2],
                            ":name" => $val[3],
                        )
                    )) 
                    {
                        $id = $pdo->lastInsertId();
                        $insertSql = "INSERT into teaches (user_id, group_id) values (:userid, :groupid)";
                        
                        if($insStmt = $pdo->prepare($insertSql)) {
                            if ($insStmt->execute(
                                array(
                                    ":userid" => $id,
                                    ":groupid" => $groupId
                                )
                            ))
                            {
                                //Success
                            }
                        }
                    }
                }
                unset($stmt);
                unset($insStmt);
            }
            unset($pdo);
        }
        catch(Exception $ex) {
            die("Грешка при изпълнението: " . $ex->getMessage());
        }
    }
?>

<?php
    $nameError = "";
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "schedule";
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    $arrGroups = [];
    $sql = "SELECT id, groupAdm, speciality, year from studentsgroups";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($arrGroups, [$row["id"], $row["speciality"], $row["year"], $row["groupAdm"]]);
        }
    }
    $conn->close();
?>


<?php 

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $target_file = basename($_FILES["inp"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        if(!isset($_POST)) {
            die("data not coming through");
        }

        $groupId = $_POST["group"];
        $check = filesize($_FILES["inp"]["tmp_name"]);
        if($check != false) {
            $uploadOk = 1;
        }
        else {
            $uploadOk = 0;
        }

        $groupError = $fileError = "";

        if ($groupId == "none") {
            $groupError = "Няма такава група";
        }

        if(file_exists($target_file)) {
            $fileError = "Файлът, който сте прикачили вече съществува";
            $uploadOk = 0;
        }

        if ($_FILES["inp"]["size"] > 500000) {
            $fileError = "Файлът, който сте прикачили е твърде голям.";
            $uploadOk = 0;
        }

        if($fileType != "csv") {
            $fileError = "Съжаляваме, приемат се файлове само с .csv разширение.";
            $uploadOk = 0;
        }


        if($uploadOk == 1 && empty($groupError) && empty($fileError)) {

            if (move_uploaded_file($_FILES["inp"]["tmp_name"], $target_file)) {
                $arr = import($target_file);
                $getData = getStudentData($arr);
                inputAccounts($getData, $groupId); 
                unlink($target_file);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавяне на студенти</title>
    
    <link rel="stylesheet" href="import.css">
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <fieldset>
        <label for="group">Изберете специалност и група, в която искате да сложите студентите</label>
        <select id="group" name="group" class="form-control <?php echo (!empty($groupError)) ? 'invalid' : ''; ?>" value="<?php echo $groupError; ?>">
            <option selected="selected" value="none" style="display: none">Изберете едно:</option>
            <?php
                foreach($arrGroups as $item){
                    echo "<option value=$item[0]>спец: $item[1],  год: $item[2],  група: $item[3]</option>";
                }
            ?>
        </select>
        <label for="inp">Файл, от който ще се сложат студентите</label>
        <input type="file" name="inp" id="inp" value="" class="form-control <?php echo (!empty($fileError)) ? 'invalid' : ''; ?>" value="<?php echo $fileError; ?>">
            </br>
        <input type="submit" value="Submit">
        </fieldset>
    </form>
</body>
</html>