

<!DOCTYPE html>
<html>
<head>
</head>

<body>
    <form>
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
        <input type="checkbox" id="computer" name="computer" value="Computer">
        <label for="computer">Нужни ли са компютри</label><br>
        <input type="checkbox" id="whiteboard" name="whiteboard" value="Whiteboard">
        <label for="whiteboard">Нужна ли е дъска</label><br>
        <input type="checkbox" id="projector" name="projector" value="Projector">
        <label for="projector">Нужен ли е прожектор</label>
    </form>

</body>

</html>