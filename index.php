<?php
    session_start();
    include('rooms.php');
    if (!isset($_SESSION["username"])) {
        die("Опитвате се да влезете след като сте излезли от друг профил");
    }
    $user = $_SESSION['username'];
    $id = $_SESSION['id'];
    $sqlForNomenclatures = "SELECT s.lecturer, s.title, s.duration, s.type from `users` u join `teaches` t on u.id = t.user_id join `subjects` s on t.sub_id = s.sub_id"; 
    $role = $_SESSION["role"];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "schedule";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT subject_id, subject_name, type from subjects s join teaches t on t.sub_id = s.subject_id JOIN users u on u.id = t.user_id where u.id = $id";
    $result = $conn->query($sql);

    $arrSubjects = [];
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            array_push($arrSubjects, [$row["subject_id"], $row["subject_name"], $row["type"]]);
        }
    }
    unset($result);
    $arrGroups = [];
    $sql = "select u.id, groupAdm, speciality, year from users u join teaches t on u.id = t.user_id join studentsgroups sg on t.group_id = sg.id where u.id=$id";
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
?>
<!DOCTYPE html5>
<html lang = "bg">
    <head>
        <meta charset = "UTF-8">
        <title>Проект по Уеб технологии - Управление на график по етажи</title>
        <meta name="author" content = "Антония Няголова, Яна Спасова, продължение: Лилия Спасова, Мартин Соколов">
        <link rel = "stylesheet" href = "style.css">  
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <script src="fixCursor.js"></script>
    </head>
    <body>
        <nav id = "building-nav" class = "topNavBar">
            <section id = "ФХФ-button" class = "topNavButton">ФХФ</section>
            <section id = "ФзФ-button" class = "topNavButton">ФзФ</section>
            <section id = "ФМИ-button" class = "topNavButton selectedButton">ФМИ</section>
        </nav>
        <main>
            <nav id = "ФМИ-nav" class = "sideNavBar">
                <section>
                    <section class = "sideNavButton">Етаж 0</section>
                    <section class = "sideNavButton">Етаж 1</section>
                    <section class = "sideNavButton">Етаж 2</section>
                </section>
                <section>
                    <section class = "sideNavButton selectedButton">Етаж 3</section>
                    <section class = "sideNavButton">Етаж 4</section>
                    <section class = "sideNavButton">Етаж 5</section>
                </section>
            </nav>
            <nav id = "ФХФ-nav" class = "sideNavBar hidden">
                <section>
                    <section class = "sideNavButton">Етаж 1</section>
                    <section class = "sideNavButton selectedButton">Етаж 2</section>
                </section>
                <section>
                    <section class = "sideNavButton">Етаж 6</section>
                </section>
            </nav>
            <nav id = "ФзФ-nav" class = "sideNavBar hidden">
                <section>
                    <section class = "sideNavButton selectedButton">Етаж 2</section>
                </section>
            </nav>
            <nav id = "setTime">
                <section class = "coolLabel">Дата:</section>
                <input id = "dateInput" type = "date" class = "glowyBox">
                <section class = "coolLabel">Час:</section>
                <input id = "timeInput" type = "time" class = "glowyBox">
                <section id = "checkAvailability" class = "coolButton selectedButton">Виж заетост</section>
                <section id = "openForm" class = "coolButton selectedButton">Запази зала</section>
            </nav>
            <figure id = "map">
            </figure>



            <nav id="pointToPoint">
                <section class="dropdown">
                    <button onclick="fC()" class="dropbtn"><?php echo "$user"?>
                    </button>
                    <section id="myDropdown" class="dropdown-content">
                        <a href="profile.php">Профил</a>
                        <a href="hours\hours.php">Моите часове</a>
                        <a href="groups\allGroups.php">Групи</a>
                        <a href="subjects\allSubjects.php">Предмети</a>
                    </section>
                </section>  
            <a style="padding: 10px;"href="logout.php"><i style="background-color:white; padding:5px"class=material-icons>logout</i></a>
            <a style="padding: 10px;"href="importStudents.php"><i style="background-color:white; padding:5px"class=material-icons>attachment</i></a>
            </nav>


            <figure id = "pop-up-room" class = "hidden">
                <section class = "darker"></section>
                <section  id = "pop-up-room-img">
                    <section id = "pop-up-room-img-title"></section>
                    <section id = "pop-up-room-img-side-text"></section>
                </section>
                <section id = "saveFromRoom">Запази зала</section>
                <section id = "x">X</section>
            </figure>
            <section id = 'formContainer' class = 'hidden'>
                <section class = "darker"></section>
                <section id = "formBackground"></section>
                <form id = "saveRoom" method = post name = "saveRoom" action = "rooms.php">  
                    <section class = "coolLabel biggerTitle">Запази стая:</section>
                    <section class = "inputContainer">
                        <input id = "building" type="text" name = "building" placeholder = "Сграда">  
                        <input id = "floor" type="text" name = "floor" placeholder = "Етаж">
                        <input id = "room" type="text" name = "room" placeholder = "Стая">
                    </section>
                    <section class = "inputContainer">
                        <input id = "saveDate" type="date" name = "day">  
                        <input id = "saveTime" type="time" name = "time">
                    </section>
                    <input type="text" id="lecturerName" name="lecturerName" value=<?php echo $name ?> disabled>
                    <select id="subject" name="subject" class="form-control <?php echo (!empty($subjectError)) ? 'invalid' : ''; ?>" value="<?php echo $subjectError; ?>">
                        <option selected="selected" value="none" style="display: none">Изберете едно:</option>
                        <?php
                            foreach($arrSubjects as $item){
                                echo "<option value=$item[0]>$item[1] $item[2]</option>";
                            }
                        ?>
                    </select>
                    <select id="group" name="group" class="form-control <?php echo (!empty($groupError)) ? 'invalid' : ''; ?>" value="<?php echo $groupError; ?>">
                        <option selected="selected" value="none" style="display: none">Изберете едно:</option>
                        <?php
                            foreach($arrGroups as $item){
                                echo "<option value=$item[0]>спец: $item[1],  год: $item[2],  група: $item[3]</option>";
                            }
                        ?>
                    </select>
                    <section id = "formButtons">
                        <section id = "saveForm" class = "coolButton selectedButton">Запази зала</section>
                        <section id = "closeForm" class = "coolButton selectedButton">Отказ</section>
                    </section>
                </form>
            </section>
        </main>

        <!--<script src = "./js/code.js"></script>-->
    </body>
</html>
