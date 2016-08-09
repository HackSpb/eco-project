<?php

/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 21.05.2016
 * Time: 8:23
 * Desc: Класс для обработки формы событий
 */

namespace MapLib;

class EventGeoObjToDB
{
    /**
     * Переменные, хранящие в себе данные о событии.
     */
    private $name;
    private $time;
    private $adres;
    private $info;
    private $coords = [];
    private $id;    //Переменная содержит в себе id нового геообъекта
    private $JSONPath = "";
    private $tempPath = "";

    /**
     * EventGeoObjToDB constructor.
     * @param $name
     * @param $time
     * @param $adres
     * @param $info
     * @param array $coords , где по индексу [0] - координата х, а по индексу [1] - координата y.
     * @param $JSONPath
     * @param $tempPath
     */
    public function __construct($name, $time, $adres, $info, array $coords, $JSONPath, $tempPath)
    {
        $this->name = $name;
        $this->time = $time;
        $this->adres = $adres;
        $this->info = $info;
        $this->coords = $coords;
        $this->JSONPath = $JSONPath;
        $this->tempPath = $tempPath;
    }

    private function addToDB()
    {

        $var = new MapLib\DataBase();  //подключаемся к БД
        $db = $var->getDb();
        $insertDataToDB = $db->prepare('INSERT INTO `balooncontent` (name, baloonContent_accept_id, time, adres, info)
                                        VALUES (:name, 0, :time, :adres, :info)');
        $insertDataToDB->execute(array('name' => $this->name, 'time' => $this->time,
            'adres' => $this->adres, 'info' => $this->info));   //Вносим данные в balooncontent

        $getIDFromNewRow = $db->query('SELECT id
          FROM `balooncontent` ORDER BY id DESC LIMIT 0,1');
        $id = $getIDFromNewRow->fetchColumn();   //Получаем id последней, созданной строчки
        $this->id = $id;

        $insertDataIntoGeoObj = $db->prepare('INSERT INTO `geoobjects` (coordinates_x, coordinates_y, baloon_type_id,
                                                baloonContent_id) VALUES (:x, :y, 2, :bal_cont_id)');
        $insertDataIntoGeoObj->execute(array('x' => $this->coords[0], 'y' => $this->coords[1], 'bal_cont_id' => $id)); //Вносим данные в geoobjects
    }


    private function addToMapFromDB()
    {
        $file = file_get_contents($this->JSONPath);
        $json = json_decode($file, true);

        $tempPath = $this->tempPath;
        $obj = new BalloonTempComposer($tempPath, $this->name, $this->time, $this->adres, $this->info);
        $balloonContent = $obj->getBalloonContent();

        $arrayForPush = [
            "type" => "Feature",
            "id" => $this->id,
            "geometry" => [
                "type" => "Point",
                "coordinates" => $this->coords
            ],
            "options" => [
                "preset" => "islands#brownDotIcon"
            ],
            "properties" => ["balloonContent" => $balloonContent],
            "hintContent" => $this->name
        ];

        array_push($json['features'], $arrayForPush);
        file_put_contents('data.json', json_encode($json));
    }

    /**
     * Добавляет данные о событии в БД и на карту
     */
    public function addEventToMap() {
        $this->addToDB();
        $this->addToMapFromDB();
    }

    /**
     * @return mixed
     * Возвращает id новой, созданной строки
     */
    public function getId()
    {
        return $this->id;
    }

}