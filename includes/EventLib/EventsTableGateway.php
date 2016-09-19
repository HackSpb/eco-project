<?php

/**
* Class describe model of events table in database
*/
class EventsTableGateWay
{
	private $pdo;
	
	/**
	 * Constructor of class EventsTableGateWay.
	 * @param $pdo - PDO object of connection to databse.
	 */
	function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	/**
	 * Function geting datafrom table `events` in database, to put
	 * data into calendar.
	 * @param $year - Integer. Searching year.
	 * @param $month - Integer. Number of searching month.
	 * @return $dataForCal - array of data to calendar.
	 */
	public function getDataForCalendar($month, $year) {
		$month++;
		$sql = "SELECT ev_id, ev_title, ev_begin_time, ev_begin_date, ev_end_time, ev_end_date, ev_slug, ev_address, ev_url FROM `events` 
				WHERE ev_begin_date LIKE '".$year."-%".$month."_%'";

		$stmt = $this->pdo->query($sql);
		
		$dataForCal = array();
		foreach ($stmt as $value) {
			$dataForCal[] = array(
				'id' => $value['ev_id'],
				'name' => $value['ev_begin_time'],
				'beginDate' => $value['ev_begin_date'],
				'beginTime' => $value['ev_begin_time'],
				'endDate' => $value['ev_end_date'],
				'endTime' => $value['ev_end_time'],
				'slug' => $value['ev_slug'],
				'ev_address' => $value['ev_address'],
				'url' => $value['ev_url'],
			);
		}

		return $dataForCal;
	}
}