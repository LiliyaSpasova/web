<?php include('rooms.php');?>
<!DOCTYPE html5>
<html lang = "bg">
    <head>
        <meta charset = "UTF-8">
        <title>Проект по Уеб технологии - Управление на график по етажи</title>
        <meta name="author" content = "Антония Няголова, Яна Спасова">
        <link rel = "stylesheet" href = "style.css">
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
                <section class = "coolLabel">Начална точка:</section>
                <section class="dropdown">
                    <button class="dropbtn">ФМИ</button>
                    <section class="dropdown-content">
                        <section class="dropdown-content">
                            <a href="#">link1</a>
                        </section>
                    </section>
                </section>
                
                <section class="dropdown">
                    <button class="dropbtn">ФХФ</button>
                    <section class="dropdown-content">
                        <section class="dropdown-content">
                            <a href="#">link1</a>
                        </section>
                    </section>
                </section>

                <section class="dropdown">
                    <button class="dropbtn">ФХФ</button>
                    <section class="dropdown-content">
                        <section class="dropdown-content">
                            <a href="#">link1</a>
                        </section>
                    </section>
                </section>

                <section class = "coolLabel">Крайна точка</section>
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
                <form id = "saveRoom" method = post name = "saveRoom" action = "room.php">  
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
                    <section class = "inputContainer">
                        <input id = "duration" type="number" name = "duration" placeholder = "Продължителност">  
                    </section>
                    <section class = "inputContainer">
                        <input id = "lecturerName" type = "text" name = "lecturerName" placeholder = "Преподавател"> 
                        <input id = "subjectTitle" type = "text" name = "subjectTitle" placeholder = "Предмет"> 
                        <select id = "courseType" type = "text" name = "courseType">  
                            <option value = "с">семинар</option> 
                            <option value = "л">лекция</option> 
                            <option value = "п">практикум</option> 
                        </select>
                    </section>
                    <section class = "inputContainer">
                        <input id = "speciality" type = "text" name = "speciality" placeholder = "Специалност"> 
                        <input id = "year" type = "text" name = "year" placeholder = "Курс"> 
                        <input id = "groupAdm" type = "text" name = "groupAdm" placeholder = "Група">  
                        
                    </section>
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
