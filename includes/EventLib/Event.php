<?php

/**
 * Abstract entity of event. This class will be extend by ActualEvent and ExpiredEvent
 */
class Event
{
    protected $id;
    protected $name;
    protected $address;
    protected $beginDateDay;
    protected $beginDateMonth;
    protected $month;
    protected $beginDateYear;
    protected $dayOfWeek;
    protected $beginTimeHM;
    protected $url;
    protected $slug;

    function __construct()
    {
        # code...
    }


    /**
     * Function set data of this Event type object from array
     * @param $eventData - array of event data
     */
    public function setDataFromArray($eventData)
    {
        $this->id = $eventData['id'];
        $this->name = $eventData['name'];
        $this->address = $eventData['address'];
        $this->beginDateDay = $eventData['beginDateDay'];
        $this->dayOfWeek = $eventData['DayOfWeek'];
        $this->beginDateMonth = $eventData['beginDateMonth'];
        $this->beginDateYear = $eventData['beginDateYear'];
        $this->month = $eventData['month'];
        $this->beginTimeHM = $eventData['beginTime'];
        $this->url = $eventData['url'];
        $this->slug = $eventData['slug'];
    }


    /**
     * Getting data of this object in form of array
     * @return array $data - array
     */
    public function getArrayData()
    {
        $data = array(
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'beginDateDay' => $this->beginDateDay,
            'beginDateMonth' => $this->beginDateMonth,
            'beginDateYear' => $this->beginDateYear,
            'month' => $this->month,
            'DayOfWeek' => $this->dayOfWeek,
            'beginTime' => $this->beginTimeHM,
            'url' => $this->url,
            'slug' => $this->slug,
        );

        return $data;
    }
}