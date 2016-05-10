<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 16.04.2016
 * Time: 1:50
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавление точек на карту</title>
    <style>
        #box {
            background-color: #fff;
            position: absolute;
            right: 150px;
            top: 50px;
            width: 200px;
            padding: 10px;
        }

        html {
            width: 100%;
            height: 100%;
        }

        body {
            font-size: 16px;
            width: 100%;
            height: 100%;
        }

        #map {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            cursor: default;
        }

        #map :hover {
            cursor: default;
        }
    </style>
    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script src="../js/mapScripts/mapbasics.js" type="text/javascript"></script>
    <script language="JavaScript" src="../lib/jquery.min.js"></script>
</head>
<body>
<div id="map"></div>
<div id="box">
    <form action="geoobjectsToDB.php" name="addingPoints" method="POST">Добавить точку на карту <br><br>
        <div>
            <label><b>Координаты</b> <br><input value="" name="coords" id="coords" type="text"></label><br><br>
            <label><b>Тип объекта</b> <br>
                <select name="list" id="list" onchange="chooseSelection()">
                    <option value="Пункт приема вторсырья">Пункт приема вторсырья</option>
                    <option value="Предстоящее мероприятие">Предстоящее мероприятие</option>
                    <option value="Пункт велопроката">Пункт велопроката</option>
                    <option value="Эко-кафе">Эко-кафе</option>
                    <option value="Приют для животных">Приют для животных</option>
                    <option value="Магазин с веганскими товарами">Магазин с веганскими товарами</option>
                    <option value="Магазин с экотоварами">Магазин с экотоварами</option>
                </select><br><br>
                <div id="accept" ><b>Что принимают</b><br>
                    <input name="acception[]" value="dangerousGarbage" type="checkbox">Опасные отходы<br>
                    <input name="acception[]" value="paper" type="checkbox">Бумага<br>
                    <input name="acception[]" value="glass" type="checkbox">Стекло<br>
                    <input name="acception[]" value="plastic" type="checkbox">Пластик<br>
                    <input name="acception[]" value="metal" type="checkbox">Металл<br>
                    <input name="acception[]" value="clothes" type="checkbox">Одежда<br>
                    <input name="acception[]" value="other" type="checkbox">Иное<br><br>
                </div>
                <label><b>Название точки</b> <br><input name="pointName" type="text"></label><br><br>
                <label><b>Время работы точки</b> <br><input name="pointTime" type="text"></label><br><br>
                <label><b>Адрес точки</b> <br><input name="pointAdres" type="text"></label><br><br>
                <label><b>Описание точки</b> <br><textarea name="pointDesc" id="" cols="25"
                                                           rows="10"></textarea></label><br>
                <input type="submit" value="Отправить на сервер">
        </div>
    </form>
</div>
</body>
</html>