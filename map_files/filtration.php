<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 03.05.2016
 * Time: 12:30
 * Desc: Скрипт, который вызывается ajax, методом POST в файле geoScript, при изменении состояния checkbox
 * в файле map_files.php
 */
include "connect_DB.php";    //Подключаемся к БД
/**
 * Создаем массив arrayForJSON, который будем заполнять данными геообъекта и выводиться в файл data_for_map.json
 * Массив содержит следующие поля:
 * type - тип объекта. Значение поля должно быть равно "FeatureCollection";
 * features - массив дочерних сущностей коллекции. Дочерние объекты могут быть сущностями или вложенными
 * коллекциями сущностей. Массив features будет содрежать следующие поля:
 * type - тип объекта. Значение поля должно быть равно "Feature";
 * id - идентификационный номер объекта; (опционально)
 * geometry - массив геометрии объекта. Содержит поля type и массив coordinates. Соответствует параметру, передаваемому
 * в конструктор объекта ymaps.GeoObject;
 * Массив geometry на нашей карте содержит следущие поля:
 * type - поле, передающее тип геообъекта. Поскольку в нашем случае все объекты точечные, значение этого поля
 * всегда равно "Point";
 * coordinates - массив, содержит в себе координаты геообъекта, где в индекс [0] передается координата по x, а в индекс
 * [1] координата по y.
 * properties - массив данных геообъекта. Содержит в себе массив с следующими данными:
 * balloonContent - содержит информацию геообъекта, при наведении и нажатии на геообъект мышкой.
 * options - массив опций геообъекта. Содержит в себе следующее поле:
 * preset - поле, который содержит в себе иконку геообъекта.
 * hintContent - Данное поле содержит в себе значение, которое появляется при наведении на балун курсором. Значение
 * этого поля в нашем случае заполняем названием геообъекта.
 */
$arrayForJSON = [];
/**
 * Изменяем файл data_for_map.json при изменении состояния checkbox с именем "check". Если checkbox не отмечен,
 * скрипт не сработает.
 */
$id = 0;                    //Содержит в себе id геообъекта.
$coordinates = [];          //Содержит в себе координаты геообъекта, где индекс [0] содержит координату x, а [1] - y.
$preset = "";               //Содержит в себе иконку геообъекта.
$balloonContent = "";       //Содержит в себе описание геообъекта, при нажатии мышкой по балуну на карте.
$hintContent = "";          //Содрежит в себе краткое описании геообъекта, при наведении на балун мышкой.
$balloonAcceptString = "";  //Содрежит в себе строку с описанием, что какой тип вторсырья принимает пункт.
if ($_POST['check'] == "on" && !empty($_POST['acception'])) {
    foreach ($_POST['acception'] as $item) {
        /**
         * Берем данные из нужных таблиц, где содержатся данные о пунктах приемах вторсырья, выбранных пользователем
         * checkbox-ов.
         */
        $getDataFromBalooncontent_accept = $db->prepare('SELECT * FROM `balooncontent_accept` WHERE '.
            $item.' = 1');
        $getDataFromBalooncontent_accept->execute();
        $getDataFromBalooncontent_accept_type = $db->prepare('SELECT type_rus FROM `balooncontent_accept_type`');
        $getDataFromBalooncontent_accept_type->execute();
        $arrayBalooncontent_accept_type = [];
        foreach ($getDataFromBalooncontent_accept_type as $row2) {
            array_push($arrayBalooncontent_accept_type, $row2['type_rus']);
        }
        foreach ($getDataFromBalooncontent_accept as $row2) {
            for ($i = 1; $i < 8; $i++) {
                if ($row2[$i] == 1 && $i < 7)
                    $balloonAcceptString .= $arrayBalooncontent_accept_type[$i - 1]. ", ";
                if ($row2[$i] == 1 && $i == 7)
                    $balloonAcceptString .= $arrayBalooncontent_accept_type[$i - 1];
            }
        }
        $getDataFromBalooncontent_accept = $db->prepare('SELECT * FROM `balooncontent_accept` WHERE '.
            $item.' = 1');
        $getDataFromBalooncontent_accept->execute();
        foreach ($getDataFromBalooncontent_accept as $value) {
            $getDataFromPresets = $db->prepare('SELECT presets FROM `presets` WHERE id = '.$value['preset_id']);
            $getDataFromPresets->execute();
            foreach($getDataFromPresets as $value2) {
                $preset = $value2['presets'];   //Присваиваем иконку геообъекта
            }
            $getDataFromBalooncontent = $db->prepare('SELECT id, name, time, adres, info FROM `balooncontent`
              WHERE baloonContent_accept_id = '.$value['id']);
            $getDataFromBalooncontent->execute();
            foreach ($getDataFromBalooncontent as $item2) {
                /**
                 * Присваиваем переменные для вставки в балун.
                 */
                $name = $item2['name'];
                $time = $item2['time'];
                $adres = $item2['adres'];
                $info = $item2['info'];
                $balloonContent = "<b>".$name."</b><br>
                    <div><b>Принимают: </b>".$balloonAcceptString."</div>
                    <div><b>Время работы: </b>".$item2['time']."</div>
                    <div><b>Адрес/телефон: </b>".$item2['adres']."</div>
                    <div><b>Дополнительно: </b>".$item2['info']."</div>";
                $hintContent = $item2['name'];
                $getDataFromGeoobjects = $db->prepare('SELECT id, coordinates_x, coordinates_y FROM `geoobjects`
                  WHERE baloonContent_id = '.$item2['id']);
                $getDataFromGeoobjects->execute();
                foreach($getDataFromGeoobjects as $item3) {
                    $coordinates = [$item3['coordinates_x'], $item3['coordinates_y']];
                    $id = $item3['id'];
                    /**
                     * Проверяем повторяющиеся id в списке необходимых геообъектов. Если id повторяется, объект
                     * не будет добавляться на карту.
                     */
                    $idArray = [];
                    foreach ($arrayForJSON as $item4) {
                        array_push($idArray, $item4['id']);
                    }
                    if (!in_array($id, $idArray)) {
                        $arrayForPush = [
                            "type" => "Feature",
                            "id" => $id,
                            "geometry" => [
                                "type" => "Point",
                                "coordinates" => $coordinates
                            ],
                            "options" => [
                                "preset" => $preset
                            ],
                            "properties" => ["balloonContent" => $balloonContent],
                            "hintContent" => $hintContent
                        ];
                        array_push($arrayForJSON, $arrayForPush);
                    }
                }
            }
        }
    }
}
/**
 * Добавив на карту пункты приема вторсырья, добавляем остальные объекты.
 *
 */
if (!empty($_POST['points'])) {
    foreach ($_POST['points'] as $point) {
        /**
         * Выбираем из нужных таблиц id типа необходимой точки.
         */
        $getDataFromBaloon_type = $db->prepare('SELECT id, preset FROM `baloon_type` WHERE type_eng = '.$point);
        $getDataFromBaloon_type->execute();
        $baloon_typeID = 0; //Переменная будет хранить id для таблицы geoobjects
        foreach ($getDataFromBaloon_type as $item) {
            $preset = $item['preset'];  //Присваиваем переменной иконку
            $baloon_typeID = $item['id'];
        }
        $balloonContentID = 0;  //Переменная будет хранить в себе id для таблицы balooncontent
        $getDataFromGeoobjects = $db->prepare('SELECT id, coordinates_x, coordinates_y, baloonContent_id FROM geoobjects
            WHERE baloon_type_id = '.$baloon_typeID);
        $getDataFromGeoobjects->execute();
        foreach ($getDataFromGeoobjects as $item2) {
            $id = $item2['id']; //Присваиваем переменной id точки
            $coordinates = [$item2['coordinates_x'], $item2['coordinates_y']];  //Присваиваем переменной координаты точки
            $balloonContentID = $item2['id'];
        }
        $getDataFromBalooncontent = $db->prepare('SELECT name, time, adres, info FROM `balooncontent`
            WHERE id = '.$balloonContentID);
        $getDataFromBalooncontent->execute();
        foreach ($getDataFromBalooncontent as $item3) {
            $balloonContent = "<b>".$item3['name']."</b><br>
                    <div><b>Время работы: </b>".$item3['time']."</div>
                    <div><b>Адрес/телефон: </b>".$item3['adres']."</div>
                    <div><b>Дополнительно: </b>".$item3['info']."</div>";
            $hintContent = $item3['name'];
        }
        $idArray = [];
        foreach ($arrayForJSON as $item4) {
            array_push($idArray, $item4['id']);
        }
        if (!in_array($id, $idArray)) {
            $arrayForPush = [
                "type" => "Feature",
                "id" => $id,
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => $coordinates
                ],
                "options" => [
                    "preset" => $preset
                ],
                "properties" => ["balloonContent" => $balloonContent],
                "hintContent" => $hintContent
            ];
            array_push($arrayForJSON, $arrayForPush);
        }
    }
}
/**
 * Массив полученных данных кодируем в data_for_map.json файл, и скриптом geoScript добавляем на карту.
 */
$array = ["type" => "FeatureCollection", "features" => $arrayForJSON];
$json = json_encode($array);
print_r($json);

file_put_contents('data.json', $json);
