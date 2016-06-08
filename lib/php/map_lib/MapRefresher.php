<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 23.05.2016
 * Time: 14:22
 * Desc: класс для обновления данных в базе данных и вывод их на карту
 */

namespace MapLib;

include "BalloonTempComposer.php";
include_once "DataBaseConnection.php";
use PDO;

class MapRefresher
{
    private $tempPath = "../../map_files/templates/balloon_temp.html";  //Переменная хранит в себе путь
                                                                        //к шаблону для содержимого балуна

    /**
     * @param string $tempPath
     */
    public function setTempPath($tempPath)
    {
        $this->tempPath = $tempPath;
    }

    /**
     * @param PDO $db
     * @param $balContID
     * @return array, массив, который содержит в себе данные по индексам:
     *      ['balloonContent'] - содержание балуна
     *      ['hintContent'] - содержание балуна, при наведении на него мышью
     *      ['preset'] - содержит в себе внешний вид балуна
     *
     * Аналогичное действие выполняет метод getNotRePoints, но собирает
     * данные из других таблиц о других типах геообъектов.
     */
    private function getRePoints(PDO $db, $balContID) {
        $dataFromGeoTables = $db->prepare('SELECT a.name, a.time, a.adres, a.info, c.presets
                                                    FROM `balooncontent` as a INNER JOIN `balooncontent_accept` as b
                                                                            INNER JOIN `presets` as c
                                                    WHERE a.id = :balContID AND a.baloonContent_accept_id = b.id
                                                                            AND b.preset_id = c.id');
        $dataFromGeoTables->execute(array('balContID' => $balContID));
        $geoDataArr = $dataFromGeoTables->fetchAll();
        $geoData = $geoDataArr[0];

        $path = $this->tempPath;
        $var = new BalloonTempComposer($path, $geoData['name'], $geoData['time'], $geoData['adres'], $geoData['info']);
        $balloonContent = $var->getBalloonContent();

        $returningArray = ['balloonContent' => $balloonContent, "hintContent" => $geoData['name'],
            "preset" => $geoData['presets']];

        return $returningArray;
    }

    private function getNotRePoints(PDO $db, $balContID, $balTypeID) {
        if ($balTypeID != 2) {
            $dataFromGeoTables = $db->prepare('SELECT a.name, a.time, a.adres, a.info, b.preset
                                                    FROM `balooncontent` as a INNER JOIN `baloon_type` as b
                                                    WHERE a.id = :balContID AND b.id = :balTypeID');
            $dataFromGeoTables->execute(array('balContID' => $balContID, 'balTypeID' => $balTypeID));
            $geoDataArr = $dataFromGeoTables->fetchAll();
            $geoData = $geoDataArr[0];


            $path = "../../map_files/templates/balloon_temp.html";
            $var = new BalloonTempComposer($path, $geoData['name'], $geoData['time'], $geoData['adres'], $geoData['info']);
            $balloonContent = $var->getBalloonContent();


            $returningArray = ['balloonContent' => $balloonContent, "hintContent" => $geoData['name'],
                "preset" => $geoData['preset']];

            return $returningArray;
        }
        else {
            $returningArray = [];
            $dataFromEventTables = $db->prepare('SELECT a.name, a.time, a.adres, a.info, b.preset, c.end_date
                                                    FROM `balooncontent` as a INNER JOIN `baloon_type` as b 
                                                                              INNER JOIN `event` as c
                                                    WHERE a.id = :balContID AND b.id = :balTypeID
                                                                            AND a.id = c.geoobject_id');
            $dataFromEventTables->execute(array('balContID' => $balContID, 'balTypeID' => $balTypeID));

            $geoDataArr = $dataFromEventTables->fetchAll();
            $geoData = $geoDataArr[0];

            if ($this->checkExpiredDate($geoData['end_date'])) {

                $path = $this->tempPath;
                $var = new BalloonTempComposer($path, $geoData['name'], $geoData['time'], $geoData['adres'], $geoData['info']);
                $balloonContent = $var->getBalloonContent();

                $returningArray = ['balloonContent' => $balloonContent, "hintContent" => $geoData['name'],
                    "preset" => $geoData['preset']];

                return $returningArray;
            }
            else {
                return $returningArray;
            }
        }
    }

    private function checkExpiredDate($endDate) {
        $dateNow = date('Y-m-d H-i-s');
        $dateNowObj = new \DateTime($dateNow);
        $endDateObj = new \DateTime($endDate);

        if ($dateNowObj > $endDateObj)
            return true;
        else
            return false;
    }

    /**
     * @param array $pointData
     * @param $id
     * @param array $coordinates
     * @return array
     */
    private function createArrForPush(array $pointData, $id, array $coordinates) {
        $arrForPushToJSON = [
            "type" => "Feature",
            "id" => $id,
            "geometry" => [
                "type" => "Point",
                "coordinates" => $coordinates
            ],
            "options" => [
                "preset" => $pointData['preset']
            ],
            "properties" => ["balloonContent" => $pointData['balloonContent']],
            "hintContent" => $pointData['hintContent']
        ];

        return $arrForPushToJSON;
    }

    private function createCoordsArr($x, $y) {
        return [$x, $y];
    }

    /**
     * Метод обновляет карту, добавляя данные из БД
     */
    public function refresh() {
        $arrForJson = [];
        $var = new DataBase();
        $db = $var->getDb();

        $getDataFromGeoObj = $db->query('SELECT * FROM `geoobjects`');
        foreach ($getDataFromGeoObj as $geoData) {
            if ($geoData['baloon_type_id'] == 1) {
                $rePoints = $this->getRePoints($db, $geoData['baloonContent_id']);
                $coords = $this->createCoordsArr($geoData['coordinates_x'], $geoData['coordinates_y']);

                $arrForPush = $this->createArrForPush($rePoints, $geoData['id'], $coords);
                array_push($arrForJson, $arrForPush);
            }
            else {
                $notRePoints = $this->getNotRePoints($db, $geoData['baloonContent_id'], $geoData['baloon_type_id']);
                if (empty($notRePoints)) {
                    continue;
                }
                $coords = $this->createCoordsArr($geoData['coordinates_x'], $geoData['coordinates_y']);

                $arrForPush = $this->createArrForPush($notRePoints, $geoData['id'], $coords);

                array_push($arrForJson, $arrForPush);
            }
        }

        $array = ["type" => "FeatureCollection", "features" => $arrForJson];
        $json = json_encode($array);

        file_put_contents('../../map_files/data.json', $json);
    }
}