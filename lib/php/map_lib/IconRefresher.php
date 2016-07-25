<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 29.05.2016
 * Time: 14:56
 */

namespace MapLib;
include_once 'DataBaseConnection.php';

class IconRefresher
{
    private function createArrForPush($row) {
        $iconPath = "../../map_files/icon/".$row['iconImageHref'];

        $arrForPush = [
            "iconLayout" => $row['iconLayout'],
            "iconImageHref" => $iconPath

        ];

        return $arrForPush;
    }

    public function refresh($path) {
        include "../../../config.php";
        global $db;
        
        $arrForJSON = [];

        $getDataFromIcons = $db->query('SELECT * FROM `icons`');
        foreach ($getDataFromIcons as $row) {
            array_push($arrForJSON, $this->createArrForPush($row));
        }

        file_put_contents($path, json_encode($arrForJSON));
    }
}