<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 17.04.2016
 * Time: 0:22
 */

/**
 * Подключаемся к БД и получаем данные с нужных таблиц
 */

include '../../map_files/connect_DB.php';
$balContAcceptType = $db->prepare('SELECT * FROM `balooncontent_accept_type`');  //Содержит в себе все варианты
$balContAcceptType->execute();                                                   //типов пунктов приема вторсырья

$balType = $db->prepare('SELECT * FROM `baloon_type`');       //Содержит в себе все варианты типов геообъектов
$balType->execute();

$presetsType = $db->prepare('SELECT * FROM `presets`');
$presetsType->execute();

/**
 * Получаем $baloon_type_id геообъекта для таблицы geoobjects
 */

$baloon_type_id = 0; // Переменная будет хранить id типа балуна

foreach ($balType as $balTypeConf) {
    if ($_POST['list'] == $balTypeConf['type_rus']) {
        $baloon_type_id = $balTypeConf['id'];
    }
}

/**
 * Получаем $baloonContent_accept_id для таблицы balooncontent и определяем, что принимает пункт приема вторсырья.
 * Данные о том, что принимают, представлены в виде массива $acceptArray, где 1 - принимают, 0 - не принимают.
 */

if ($_POST['list'] == "Пункт приема вторсырья") {

    $acceptTypeArray = [];  //Массив содержит в себе типы принимаемых материалов
    foreach ($balContAcceptType as $acceptType) {
        array_push($acceptTypeArray, $acceptType['type_eng']);
    }

    $acceptArray = [];  //Массив содержит в себе, какое вторсырье принимает геообъект (в виде 1, 0)
    while (count($acceptArray) < 7) {    //В цикле заполняем массив $acceptArray значениями по-умолчанию (0)
        array_push($acceptArray, 0);
    }

    if (!empty($_POST['acception'])) {  //Если ни один из checkbox не выбран, условие не выполнится
        for ($i = 0; $i < count($acceptTypeArray); $i++) {   //В цикле заполняем массив $AcceptArray данным из формы
            foreach ($_POST['acception'] as $acception) {
                if ($acception == $acceptTypeArray[$i]) {
                    $acceptArray[$i] = 1;
                }
            }
        }
    }

    $createNewEmptyRow = $db->prepare('INSERT INTO `balooncontent_accept` VALUES ()'); //Создаем пустую строку в БД,
    $createNewEmptyRow->execute();                                                     //заполненную значения по-умолчанию (0)

    $getIDFromNewRow = $db->prepare('SELECT id FROM `balooncontent_accept` ORDER BY ID DESC LIMIT 0,1');
    $getIDFromNewRow->execute();

    $baloonContent_accept_id = 0;   //Принимаем id новой созданной пустой строки в таблице balooncontent_accept
    foreach ($getIDFromNewRow as $id) {
        $baloonContent_accept_id = $id['id'];
    }

    foreach ($presetsType as $item) {
        $count = 0;
        for ($i = 1; $i < 8; $i++) {
            if ($item[$i] == $acceptArray[$i - 1]) {
                $count++;
            }
        }

        if ($count == 7) {
            $setPresetIDForNewRow = $db->prepare("UPDATE `balooncontent_accept` SET preset_id = ".$item[0]." WHERE
            id = ".$baloonContent_accept_id);
            $setPresetIDForNewRow->execute();
        }
    }

    for ($i = 0; $i < count($acceptArray); $i++) {  //В цикле изменяем таблицу balooncontent_accept данными из формы
        $setNewRow = $db->prepare('UPDATE `balooncontent_accept` SET ' . $acceptTypeArray[$i] . ' = ' . $acceptArray[$i] . '
        WHERE id = ' . $baloonContent_accept_id);
        $setNewRow->execute();
    }
}
else
    $baloonContent_accept_id = 0;

/**
 * Добавляем данные нового геообъекта из формы "addingPoints" в таблицу balooncontent и geoobjects
 */

$coords = explode(" ", $_POST['coords']);   //Переменная получает массив координат. $coords[0] -- x, $coords[1] -- y
$name = $_POST['pointName'];    //Название точки
$time = $_POST['pointTime'];    //Время работы
$adres = $_POST['pointAdres'];  //Адрес точки
$info = $_POST['pointDesc'];    //Дополнительная информация

/**
 * Используя $baloonContent_accept_id заполняем сначала таблицу balooncontent
 */

$addNewPointToBalooncontent = $db->prepare('INSERT INTO `balooncontent` (name, baloonContent_accept_id, time, adres,
  info) VALUES ("'.$name.'", '.$baloonContent_accept_id.', "'.$time.'", "'.$adres.'", "'.$info.'")');
$addNewPointToBalooncontent->execute();

$idOfNewPointInBaloonContent = 0;   //В эту переменную будет записано id новой созданной точки
$getIDFromNewPointInBaloonContent = $db->prepare('SELECT id FROM `balooncontent` ORDER BY id DESC LIMIT 0, 1');
$getIDFromNewPointInBaloonContent->execute();

foreach ($getIDFromNewPointInBaloonContent as $id) {
    $idOfNewPointInBaloonContent = $id['id'];
}

/**
 * Используя $baloon_type_id и $idOfNewPointInBaloonContent заполняем таблицу geoobjects
 */

$addNewPointToGeoobjects = $db->prepare('INSERT INTO `geoobjects` (coordinates_x, coordinates_y, baloon_type_id,
baloonContent_id) VALUES ("'.$coords[0].'", "'.$coords[1].'", '.$baloon_type_id.', '.$idOfNewPointInBaloonContent.')');
$addNewPointToGeoobjects->execute();

/**
 * Выводим объекты в файл data.json
 */

$arrForJSON = [];   //массив, который будет преобразовываться в json-файл
$getDataFromGeoObj = $db->query('SELECT * FROM `geoobjects`');
foreach ($getDataFromGeoObj as $item) {
    if ($item['baloon_type_id'] == 1) { //Проверяем, является ли точка пунктом приема вторсырья
        $getDataFromTables = $db->prepare('SELECT b.name, b.time, b.adres, b.info, c.presets
                                                FROM `balooncontent` as b INNER JOIN `presets` as c
                                                                          INNER JOIN `balooncontent_accept` as d
                                                WHERE b.id = ? AND b.baloonContent_accept_id = d.id
                                                                            AND d.preset_id = c.id');
        $getDataFromTables->execute(array($item['baloonContent_id']));
        $data = $getDataFromTables->fetchAll();

        $balloonContent = "<b>".$data[0]['name']."</b><br>
                    <div><b>Время работы: </b>".$data[0]['time']."</div>
                    <div><b>Адрес/телефон: </b>".$data[0]['adres']."</div>
                    <div><b>Дополнительно: </b>".$data[0]['info']."</div>";
        $coordinates = [$item['coordinates_x'], $item['coordinates_y']];

        $arrForPushToJSON = [
            "type" => "Feature",
            "id" => $item['id'],
            "geometry" => [
                "type" => "Point",
                "coordinates" => $coordinates
            ],
            "options" => [
                "preset" => $data[0]['presets']
            ],
            "properties" => ["balloonContent" => $balloonContent],
            "hintContent" => $data[0]['name']
        ];

        array_push($arrForJSON, $arrForPushToJSON);
    }
    else {  //Точки, которые не являются
        $getDataFromTables = $db->prepare('SELECT b.name, b.time, b.adres, b.info, c.preset
                                            FROM `balooncontent` as b INNER JOIN `baloon_type` as c
                                            WHERE b.id = ? AND c.id = ?');
        $getDataFromTables->execute(array($item['baloonContent_id'], $item['baloon_type_id']));
        $data = $getDataFromTables->fetchAll();

        print_r($data[0]);

        $balloonContent = "<b>".$data[0]['name']."</b><br>
                    <div><b>Время работы: </b>".$data[0]['time']."</div>
                    <div><b>Адрес/телефон: </b>".$data[0]['adres']."</div>
                    <div><b>Дополнительно: </b>".$data[0]['info']."</div>";
        $coordinates = [$item['coordinates_x'], $item['coordinates_y']];

        $arrForPushToJSON = [
            "type" => "Feature",
            "id" => $item['id'],
            "geometry" => [
                "type" => "Point",
                "coordinates" => $coordinates
            ],
            "options" => [
                "preset" => $data[0]['preset']
            ],
            "properties" => ["balloonContent" => $balloonContent],
            "hintContent" => $data[0]['name']
        ];

        print_r($arrForPushToJSON);
    }
}

$array = ["type" => "FeatureCollection", "features" => $arrForJSON];
$json = json_encode($array);

file_put_contents('../../map_files/data.json', $json);


header("Location: addPointToMap.php");
?>