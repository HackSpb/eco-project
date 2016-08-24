<?php
/**
 * Класс, описывающий базовую логику всех объектов типа EcoPoint
 * Created by PhpStorm.
 * User: Олег
 * Date: 31.07.2016
 * Time: 21:16
 */

namespace MapLib;

use PDO;

abstract class EcoPoint
{
    public $objectID;
    protected $pointName;
    protected $mapIcon;
    protected $info = array();

    protected $coordinates = array();


    abstract function createPoint();

    abstract function updatePoint($objectID);

    abstract function deletePoint($objectID);


    /**
     * Возвращает координаты точки
     *
     * @return array
     */
    function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @return int
     */
    public function getXCoordinate()
    {
        return $this->coordinates[0];
    }

    /**
     * @param integer $x_coordinate
     */
    public function setXCoordinate($x_coordinate)
    {
        $this->coordinates[0] = $x_coordinate;
    }

    /**
     * @return integer
     */
    public function getYCoordinate()
    {
        return $this->coordinates[1];
    }

    /**
     * @param integer $y_coordinate
     */
    public function setYCoordinate($y_coordinate)
    {
        $this->coordinates[1] = $y_coordinate;
    }

    /**
     * @return string
     */
    public function getPointName()
    {
        return $this->pointName;
    }

    /**
     * @param string $pointName
     */
    public function setPointName($pointName)
    {
        $this->pointName = $pointName;
    }


}