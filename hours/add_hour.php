<?php
    $nameError = "";
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "schedule";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT subject_id, subject_name, type from subjects";
    $result = $conn->query($sql);

    $arrSubjects = [];
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($arrSubjects, [$row["subject_id"], $row["subject_name"], $row["type"]]);
        }
    }
    unset($result);
    $arrGroups = [];
    $sql = "SELECT id, groupAdm, speciality, year from studentsgroups";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($arrGroups, [$row["id"], $row["speciality"], $row["year"], $row["groupAdm"]]);
        }
    }
    $id = $_SESSION["id"];
    $sql = "SELECT name from users where id=$id";
    $name = "";
    if($result = $conn->query($sql)) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
    }

    if(empty($name)) {
        $nameError = "Нещо се обърка!";
    }
    $conn->close();
?>

<?php
    $groupError = $subjectError = "";
    require_once("../config.php");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!isset($_POST)) {
            echo("data not coming through");
        }
        $groupId = $_POST["group"];
        $subjectId = $_POST["subject"];

        if ($groupId == "none") {
            $groupError = "Изберете група!";
        }
        if ($subjectId == "none") {
            $subjectError = "Изберете предмет!";
        }

        if(empty($subjectError) && empty($groupError)) {
            $sql = "INSERT into teaches (user_id, sub_id, group_id) values($id, $subjectId, $groupId)";
            if($stmt = $pdo->prepare($sql)) {
                if($stmt->execute(
                    array()
                )){
                    echo '<script type="text/javascript">';
                    echo ' alert("Заявката е успешна!")';
                    echo '</script>';
                    header("location: hours.php");
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
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавяне на час</title>
    
    <link rel="stylesheet" href="../groups/allGroups.css">
</head>

<body id="add_hour">
    <form id="add_hour"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <?php if ($groupError != ''): ?>
            <p style="color: red;">
            <?php echo $groupError; ?>
            </p>
            <?php endif; ?>
            <?php if ($subjectError != ''): ?>
            <p style="color: red;">
            <?php echo $subjectError; ?>
            </p>
            <?php endif; ?>
    <fieldset>
        <label for="name">Вашето име:</label>
        <input type="text" id="name" name="name" value=<?php echo $name ?> disabled>
        <label for="subject">Изберете предмет и тип урок</label>
        <select id="subject" name="subject" class="form-control <?php echo (!empty($subjectError)) ? 'invalid' : ''; ?>" value="<?php echo $subjectError; ?>">
            <option selected="selected" value="none" style="display: none">Изберете едно:</option>
            <?php
                foreach($arrSubjects as $item){
                    echo "<option value=$item[0]>$item[1] $item[2]</option>";
                }
            ?>
        </select>
        <label for="group">Изберете специалност и група, на която ще преподавате</label>
        <select id="group" name="group" class="form-control <?php echo (!empty($groupError)) ? 'invalid' : ''; ?>" value="<?php echo $groupError; ?>">
            <option selected="selected" value="none" style="display: none">Изберете едно:</option>
            <?php
                foreach($arrGroups as $item){
                    echo "<option value=$item[0]>спец: $item[1],  год: $item[2],  група: $item[3]</option>";
                }
            ?>
        </select>
        <input type="submit" value="Submit" id="submit_button">
        </fieldset>
    </form>
</body>