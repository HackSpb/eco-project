<?php
/**
 * Класс описывающий логику создания, хранения и
 * использования пунктов переработки сырья на карте.
 *
 * Created by PhpStorm.
 * User: Олег
 * Date: 31.07.2016
 * Time: 22:04
 */

namespace MapLib;

use PDO;

class RecycleEcoPoint extends StaticEcoPoint
{
    protected $acception = array();


    function __construct(array $coordinates, array $schedule, array $info, array $acception)
    {
        $this->coordinates = $coordinates;
        $this->schedule = $schedule;
        $this->info = $info;
        $this->acception = $acception;
    }

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
     * Сохраняет данные о новой точке в базу данных
     *
     * @param PDO $db
     */
    static function saveToDB(PDO $db)
    {
        // TODO: Implement saveToDB() method.

    }
}