<?php 
    $degreeError = $courseGroupError = $courseError = $groupError = $numStudentsError = "";
    require_once "config.php";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_POST)) {
            echo("data not coming through");
        }

        $degreeName = $_POST["degreeName"];
        $course = $_POST["course"];
        $courseGroup = $_POST["courseGroup"];
        $group = $_POST["group"];
        $numStudents = $_POST["numStudents"];

        if (empty(trim($degreeName))) {
            $degreeError = "Моля въведете име на курса.";
        }
        if (empty($course) || $course < 1 || $course > 4) {
            $courseError = "Курсът не може да притежава такава стойност.";
        }
        if ($courseGroup == "none") {
            $courseGroupError = "Моля изберете потока, за който е предметът.";
        }
        if (empty($group)) {
            $groupError = "Моля изберете правилна стойност за група.";
        }
        if ($numStudents == "none") {
            $numStudentsError = "Моля изберете правилен брой студенти.";
        }

        if (empty($degreeError) && empty($courseError) && empty($courseGroupError) && empty($groupError) && empty($numStudentsError)) {
            $sql = "INSERT into studentsgroups (groupAdm, potok, speciality, year, count) values (:group, :courseGroup, :degreeName, :course, :numStudents)";

            if($stmt = $pdo->prepare($sql)) {
                if ($stmt->execute(
                    array(
                        ":group" => $group,
                        ":courseGroup" => $courseGroup,
                        ":degreeName" => $degreeName,
                        ":course" => $course,
                        ":numStudents" => $numStudents,
                    )
                    )) 
                    {
                        echo '<script type="text/javascript">';
                        echo ' alert("Курсът е успешно записан!")';
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

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registration_form">
        <fieldset>
            <label for="degreeName">Име на специалност:</label>
            <input type="text" id="degreeName" name="degreeName" class="form-control <?php echo (!empty($degreeError)) ? 'invalid' : ''; ?>" value="<?php echo $degreeError; ?>">
            <label for="course">Курс (от 1-ви до 4-ти)</label>
            <input type="number" id="course" name="course" min="1" max="4" class="form-control <?php echo (!empty($courseError)) ? 'invalid' : ''; ?>" value="<?php echo $courseError; ?>">
            <label for="courseGroup">Поток</label>
            <select id="courseGroup" name="courseGroup" class="form-control <?php echo (!empty($courseGroupError)) ? 'invalid' : ''; ?>" value="<?php echo $courseGroupError; ?>">
                <option value="none">Изберете потока, от който сте:</option>
                <option value="1">Първи</option>
                <option value="2">Втори</option>
            </select>
            <label for="group">Група:</label>
            <input type="number" id="group" name="group" min="1" max="8" class="form-control <?php echo (!empty($groupError)) ? 'invalid' : ''; ?>" value="<?php echo $groupError; ?>">
            <label for="group">Приблизителен брой студенти:</label>
            <select id="numStudents" name="numStudents" class="form-control <?php echo (!empty($numStudentsError)) ? 'invalid' : ''; ?>" value="<?php echo $numStudentsError; ?>">
                <option value="none">Изберете едно</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="60">60</option>
                <option value="70">70</option>
                <option value="80">80</option>
                <option value="90">90</option>
                <option value="100">100</option>
            </select>
            <input type="submit">
        </fieldset>
    </form>

</body>

</html>