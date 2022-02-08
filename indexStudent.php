<?php
    session_start();
    include('rooms.php');
    $user = $_SESSION['username'];
    $id = $_SESSION['id'];
    $sqlForNomenclatures = "SELECT s.lecturer, s.title, s.duration, s.type from `users` u join `teaches` t on u.id = t.user_id join `subjects` s on t.sub_id = s.sub_id"; 
    $role = $_SESSION["role"];
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
            </nav>
            <figure id = "map">
            </figure>





            <nav id="pointToPoint">
                <section class="dropdown">
                    <button onclick="fC()" class="dropbtn"><?php echo "$user"?>
                    </button>
                    <section id="myDropdown" class="dropdown-content">
                        <a href="profile.php">Профил</a>
                        <a href="studentHours.php">Моите часове</a>
                    </section>
                </section>  
            <a style="padding: 10px;"href="logout.php"><i style="background-color:white; padding:5px"class=material-icons>logout</i></a>
            </nav>








            <figure id = "pop-up-room" class = "hidden">
                <section class = "darker"></section>
                <section  id = "pop-up-room-img">
                    <section id = "pop-up-room-img-title"></section>
                    <section id = "pop-up-room-img-side-text"></section>
                </section>
                <section id = "x">X</section>
            </figure>
           
        </main>

        <!--<script src = "./js/code.js"></script>-->
    </body>
</html>
