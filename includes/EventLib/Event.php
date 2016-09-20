<?php

/**
* Abstract entity of event. This class will be extend by ActualEvent and ExpiredEvent
*/
class Event
{
	protected $id;
	protected $name;
	protected $address;
	protected $beginDateTime;
	protected $endDateTime;
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
	public function setDataFromArray($eventData) {
		$this->id = $eventData['id'];
		$this->name = $eventData['name'];
		$this->address = $eventData['address'];
		$this->beginDateTime = $eventData['beginTime'];
		$this->endDateTime = $eventData['endTime'];
		$this->url = $eventData['url'];
		$this->slug = $eventData['slug'];
	}


	/**
	 * Getting data of this object in form of array
	 * @return $data - array
	 */
	public function getArrayData() {
		$data = array(
			'id' => $this->id,
			'name' => $this->name,
			'address' => $this->address,
			'beginDateTime' => $this->beginDateTime,
			'endDateTime' => $this->endDateTime,
			'url' => $this->url,
			'slug' => $this->slug,
		);

		return $data;
	}
}