<?php
/**
 * Класс, описывающий логику работы с точками организаций на карте
 * Created by PhpStorm.
 * User: Олег
 * Date: 31.07.2016
 * Time: 22:05
 */

namespace MapLib;


use PDO;

class OrganizationEcoPoint extends StaticEcoPoint
{

    function createPoint()
    {
        // TODO: Implement createPoint() method.
    }

    function updatePoint($objectID)
    {
        // TODO: Implement updatePoint() method.
    }

    function deletePoint($objectID)
    {
        // TODO: Implement deletePoint() method.
    }


    /**
     * Сохраняет данные о новой организации в базу данных
     *
     * @param PDO $db
     */
    static function saveToDB(PDO $db)
    {
        // TODO: Implement saveToDB() method.
    }
}