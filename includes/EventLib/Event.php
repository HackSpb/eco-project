<?php

namespace EventLib;

/**
 * Class describe entity of event. 
 */
class Event implements Post
{
    protected $id;
    protected $name;
    protected $address;
    protected $createDate;
    protected $beginDateDay;
    protected $beginDateMonth;
    protected $month;
    protected $beginDateYear;
    protected $dayOfWeek;
    protected $beginTimeHM;
    protected $endDateTime;
    protected $endMonth;
    protected $url;
    protected $img;
    protected $slug;
    protected $description;

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $createDate
     */
    public function setCreateDate($createDate) {
        $this->createDate = $createDate;
    }

    /**
     * @param $beginDateTime
     * @param $beginMonth
     */
    public function setBeginDateTime($beginDateTime, $beginMonth)
    {
        $this->beginDateYear = $beginDateTime['beginDateYear'];
        $this->beginDateMonth = $beginDateTime['beginDateMonth'];
        $this->beginDateDay = $beginDateTime['beginDateDay'];
        $this->beginTimeHM = $beginDateTime['beginTimeHM'];
        
        $this->month = $beginMonth;
    }

    /**
     * @param $endDateTime
     * @param $endMonth
     */
    public function setEndDateTime($endDateTime, $endMonth) {
        $this->endDateTime = $endDateTime;
        
        $this->endMonth = $endMonth;
    }

    /**
     * @param $addresses
     */
    public function setAddresses($addresses) {
        $this->address = $addresses;
    }

    /**
     * @param $desc
     */
    public function setDescription($desc) {
        $this->description = $desc;
    }
    
    public function setImg($img) {
        $this->img = $img;
    }

    /**
     * @param $data
     */
    public function setPostInfo($data)
    {
        $this->setName($data['name']);
        $this->setAddresses($data['addresses']);
        $this->setDescription($data['description']);
        $this->setBeginDateTime($data['beginDateTime'], $data['beginMonth']);
        $this->setEndDateTime($data['endDateTime'], $data['endMonth']);

        isset($data['img']) ? $this->setImg($data['img']) : null;
    }

    /**
     * @return array
     */
    public function getPostInfo() {
        $data = array(
            'name' => $this->name,
            'addresses' =>$this->address,
            'description' => $this->description,
            'beginDateTime' => $this->beginDateYear.' '.$this->month.' '.$this->beginDateDay.' '.$this->beginTimeHM,
            'endDateTime' => $this->endDateTime['beginDateYear'].' '.$this->endMonth.' '.$this->endDateTime['beginDateDay'].' '.$this->endDateTime['beginTimeHM'],
            'img' => $this->img,    
        );

        return $data;
    }

    /**
     * Function set data of this Event type object from array
     * @param $eventData - array of event data
     */
    public function setDataFromArray($eventData)
    {
        $this->id = $this->isEmpty($eventData['id']);
        $this->name = $this->isEmpty($eventData['name']);
        $this->address = $this->isEmpty($eventData['address']);
        $this->createDate = $this->isEmpty($eventData['createDate']);
        $this->beginDateDay = $this->isEmpty($eventData['beginDateDay']);
        $this->dayOfWeek = $this->isEmpty($eventData['DayOfWeek']);
        $this->beginDateMonth = $this->isEmpty($eventData['beginDateMonth']);
        $this->beginDateYear = $this->isEmpty($eventData['beginDateYear']);
        $this->month = $this->isEmpty($eventData['month']);
        $this->beginTimeHM = $this->isEmpty($eventData['beginTime']);
        $this->url = $this->isEmpty($eventData['url']);
        $this->slug = $this->isEmpty($eventData['slug']);
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
            'createDate' => $this->createDate,
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

    private function isEmpty($item) {
        return isset($item) ? $item : null;
    }
}