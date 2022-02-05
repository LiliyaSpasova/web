<!DOCTYPE html>
<html>
<head>
</head>


<link rel="stylesheet" href="forms.css" >
<body>
    <form>
        <fieldset>
        <label for="subjectName">Име</label>
        <input type="text" id="subjectName" name="subjectName"><br>
        <label for="duration">Времетраене (между 1 и 5 часа)</label>
        <input type="number" id="duration" name="duration" min="1" max="5">
        <label for="subjectType">Вид на урока</label>
        <select id="subjectType" name="subjectType"><br>
            <option value="none" selected disabled hidden>Изберете един от видовете:</option>
            <option value="л">Лекция</option>
            <option value="у">Упражнение</option>
            <option value="с">Семинар</option>
        </select>
        <div class="pref">
        <label for="computer">Нужни ли са компютри</label>
        <input type="checkbox" id="computer" name="computer" value="Computer">
        </div>
        <div class="pref">
        <label  for="whiteboard">Нужна ли е дъска</label>
        <input type="checkbox" id="whiteboard" name="whiteboard" value="Whiteboard"></div>
        <div class="pref">
        <label  for="projector">Нужен ли е прожектор</label>
        <input  type="checkbox" id="projector" name="projector" value="Projector">
       </div>
        </fieldset>
    </form>

</body>

</html>