<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 29.05.2016
 * Time: 14:32
 */

namespace MapLib;

include_once 'DataBaseConnection.php';

class PresetRefresher
{
    /**
     * Метод обновляет файл preset.json
     * @param $path, путь к json файлу, который необходимо обновить
     */
    public function refresh($path) {
        $var = new DataBase();
        $db = $var->getDb();

        $points = [];
        $stmt = $db->query('SELECT type_eng, preset FROM `baloon_type`');

        foreach ($stmt as $item) {
            if ($item['preset'] != null) {
                $points[$item['type_eng']] = $item['preset'];
            }
        }

        $stmt = $db->query('SELECT * FROM presets');

        $acceptionTypeArr = [];
        $stmt1 = $db->query('SELECT type_eng FROM balooncontent_accept_type');
        foreach($stmt1 as $val) {
            array_push($acceptionTypeArr, $val['type_eng']);
        }

        $acception = [];
        foreach ($stmt as $item) {
            $string = "";
            for ($i = 0; $i < 7; $i++) {
                if ($item[$i + 1] == 1) {
                    $string = $string.$acceptionTypeArr[$i]." ";
                }
            }

            $acception[$string] = $item['presets'];
        }

        $arr = [$points, $acception];
        $json = json_encode($arr);
        file_put_contents($path, $json);
    }
}