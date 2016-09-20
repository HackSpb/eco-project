<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 31.07.2016
 * Time: 22:06
 */

namespace MapLib;


use PDO;

class EventEcoPoint extends EcoPoint
{
    protected $eventCreateDate;
    protected $eventBeginDate;
    protected $eventBeginTime;
    protected $eventEndDate;
    protected $eventEndTime;
    protected $eventAddress;
    protected $eventDescription;
    public $isActive;
    protected $address;

    function __construct(array $coordinates, array $info, array $dateTime = NULL, $geoObjectID = NULL)
    {
        $this->coordinates = $coordinates;
        $this->pointName = $info['pointName'];
        $this->eventDescription = $info['description'];
        $this->mapIcon = new EventMapIcon();
        $this->address = $info['address'];

        if (!is_null($dateTime)) {
            $this->eventBeginDate = $dateTime['beginDate'];
            $this->eventBeginTime = $dateTime['beginTime'];
            $this->eventEndDate = $dateTime['endDate'];
            $this->eventEndTime = $dateTime['endTime'];
        }

        if (!is_null($geoObjectID))
            $this->objectID = $geoObjectID;
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
     * Сохраняет информацию о новой точке в базу данных
     * 
     * @param PDO $db подключение к базе данных
     * @param $objID
     */
    function saveToDB(PDO $db, $objID)
    {
        $sql = 'INSERT INTO `geo_points` (obj_id, geo_x, geo_y, geo_address) VALUES (:id, :geo_x, :geo_y, :address)';
        $stmt = $db->prepare($sql);
        $this->objectID = $objID;
        
        $pointData = array(
            'id' => $objID,
            'geo_x' => $this->getXCoordinate(),
            'geo_y' => $this->getYCoordinate(),
            'address' => $this->address
        );
        
        $stmt->execute($pointData);

    }


    /**
     * Возвращает массив, который будет преобразован в JSON файл,
     * используемый для создания точек на карте
     *
     * @return array
     */
    function createJSONArray() {
        // TODO: Сделать, чтобы строка контента метки формировалась из шаблона
        $balloonContentBodyString = "Начало: $this->eventBeginDate $this->eventBeginTime <br> 
                                     Конец: $this->eventEndDate $this->eventEndTime <br>
                                     Адрес: $this->address <br>
                                     Описание: $this->eventDescription";

        $JSONArray = array(
            'type' => 'feature',
            'id' => $this->objectID,
            'geometry' => array(
                'type' => 'Point',
                'coordinates' => array(
                    $this->getXCoordinate(),
                    $this->getYCoordinate()
                )
            ),
            'options' => array(
                'preset' => $this->mapIcon->getIconName()
            ),
            'properties' => array(
                'balloonContentHeader' => $this->pointName,
                'balloonContentBody' => $balloonContentBodyString
            ),
            'hintContent' => $this->pointName,
            'hintHideTimeout' => 0
        );

        return $JSONArray;
    }
}