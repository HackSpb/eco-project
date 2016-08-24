<?php
/**
 * Класс, являющийся контейнером для точек.
 * Created by PhpStorm.
 * User: Олег
 * Date: 02.08.2016
 * Time: 9:31
 */

namespace MapLib;


class MapContainer
{
    private $container = array();

    function add(EcoPoint $ecoPoint) {
        $this->container[$ecoPoint->objectID] = $ecoPoint;
    }

    function delete($objectID) {
        $this->container[$objectID] = null;
    }
}