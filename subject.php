<?php 
    require_once "config.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_POST)) {
            echo("data not coming through");
        }

        $computer = $whiteboard = $projector = false;
        $nameError = $timeError = $typeError = "";

        $name = $_POST["subjectName"];
        $duration = $_POST["duration"];
        $subjectType = $_POST["subjectType"];
        if(isset($_POST["computer"]))
            $computer = true;
        else 
            $computer = false;
        if(isset($_POST["projector"]))
            $projector = true;
        else 
            $projector = false;
        if(isset($_POST["whiteboard"]))
            $whiteboard = true;
        else
            $whiteboard = false;
        if (empty(trim($name))) {
            $nameError = "Моля въведете име на курса.";
        }
        if (empty($duration) || $duration < 1 || $duration > 5) {
            $timeError = "Моля въведете правилно време за протичане на курса.";
        }
        if ($subjectType == "none") {
            $typeError = "Моля изберете вида на урока.";
        }

        if (empty($nameError) && empty($timeError) && empty($typeError)) {
            $sql = "INSERT into subjects (subject_name, duration, type, computers, whiteBoard, projector) values (:subjname, :duration, :subjtype, :computer, :projector, :whiteboard)";

            if($stmt = $pdo->prepare($sql)) {
                if ($stmt->execute(
                    array(
                        ":subjname" => $name,
                        ":duration" => $duration,
                        ":subjtype" => $subjectType,
                        ":computer" => $computer,
                        ":projector" => $projector,
                        ":whiteboard" => $whiteboard
                    )
                    )) 
                    {
                        echo '<script type="text/javascript">';
                        echo ' alert("Предметът е успешно записан!")';
                        echo '</script>';
                    }
                    else {
                        echo ("Нещо се обърка. Моля опитайте по-късно.");
                    }
                    unset($stmt);
                }
        }
        unset($pdo);
    }
?>



<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="register_style.css">
</head>


<link rel="stylesheet" href="forms.css" >
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registration_form">
        <fieldset>
            <label for="subjectName">Име</label>
            <input type="text" id="subjectName" name="subjectName" class="form-control <?php echo (!empty($nameError)) ? 'invalid' : ''; ?>" value="<?php echo $nameError; ?>">
            <label for="duration">Времетраене (между 1 и 5 часа)</label>
            <input type="number" id="duration" name="duration" min="1" max="5" class="form-control <?php echo (!empty($timeError)) ? 'invalid' : ''; ?>" value="<?php echo $timeError; ?>">
            <label for="subjectType">Вид на урока</label>
            <select id="subjectType" name="subjectType" class="form-control <?php echo (!empty($typeError)) ? 'invalid' : ''; ?>" value="<?php echo $typeError; ?>">
                <option value="none">Изберете един от видовете:</option>
                <option value="л">Лекция</option>
                <option value="у">Упражнение</option>
                <option value="с">Семинар</option>
            </select>
            <input type="checkbox" id="computer" name="computer" value="Computer">
            <label for="computer">Нужни ли са компютри</label><br>
            <input type="checkbox" id="whiteboard" name="whiteboard" value="Whiteboard">
            <label for="whiteboard">Нужна ли е дъска</label><br>
            <input type="checkbox" id="projector" name="projector" value="Projector">
            <label for="projector">Нужен ли е прожектор</label>
            <input type="submit">
        </fieldset>
    </form>

</body>

</html>