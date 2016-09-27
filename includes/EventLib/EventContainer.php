<?php

/**
 * This class describes container of event objects
 */
class EventContainer
{

    private $container = array();

    /**
     * Function add new event object to container
     * @param $event - Event type.
     */
    public function add(Event $event)
    {
        $this->container[] = $event;
    }

    /**
     * Creating json string from container
     * @return string $json - json string.
     */
    public function crateJSON()
    {
        $jsonArray = array();
        foreach ($this->container as $item) {
            $jsonArray[] = $item->getArrayData();
        }


        $json = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
        return $json;
    }

    /**
     * Creating array from container
     * @return array - calendar data
     */
    public function createArrayData() {
        $dataArray = array();
        foreach ($this->container as $item) {
            $dataArray[] = $item->getArrayData();
        }

        return $dataArray;
    }

}