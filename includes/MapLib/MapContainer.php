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

    /**
     * @return array
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Добавляет новую точку в контейнер
     *
     * @param EcoPoint $ecoPoint
     */
    function add(EcoPoint $ecoPoint) {
        $this->container[$ecoPoint->objectID] = $ecoPoint;
    }

    /**
     * Очищает контейнер точек
     */
    function clear() {
        $this->container = array();
    }

    /**
     * Удаляет с конкретным objectID точку из контейнера 
     *
     * @param $objectID
     */
    function delete($objectID) {
        header('Content-Type: text/html; charset=utf-8');
        $this->container[$objectID] = null;
    }

    /**
     * Выводит данные о эко точках на карту
     */
    function show() {
        header('Content-Type: text/html; charset=utf-8');

        $json = array(
            'type' => 'FeatureCollection',
            'features' => array()
        );

        foreach ($this->container as $item) {
            $json['features'][] = $item->createJSONArray();
        }

        file_put_contents('map_files/data.json', json_encode($json, JSON_UNESCAPED_UNICODE));
    }

}