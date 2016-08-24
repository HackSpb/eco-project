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

    function __construct(array $coordinates, array $eventDatetime, array $info)
    {
        // TODO: Написать конструктор для событий
        $this->coordinates = $coordinates;
        $this->pointName = $info['pointName'];
        $this->eventDescription = $info['description'];
        $this->eventCreateDate = $eventDatetime['createDate'];
        $this->eventBeginDate = $eventDatetime['beginDate'];
        $this->eventBeginTime = $eventDatetime['beginTime'];
        $this->eventEndDate = $eventDatetime['endDate'];
        $this->eventEndTime = $eventDatetime['endTime'];
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

    static function saveToDB(PDO $db)
    {
        // TODO: Implement saveToDB() method.
    }

    function isActive() {
        $dateFormat = '';
        $beginDateTime = \DateTime::createFromFormat($dateFormat, $this->eventBeginDate.$this->eventBeginTime);
        $endDateTime = \DateTime::createFromFormat($dateFormat, $this->eventEndDate.$this->eventEndTime);

        $diff = $beginDateTime->diff($endDateTime);

        return $diff;
    }
}