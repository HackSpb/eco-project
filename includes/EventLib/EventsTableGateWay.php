<?php

/**
 * Class describe model of events table in database
 */
class EventsTableGateWay
{
    private $pdo;
    static private $tableName = 'events';
    private $daysOfWeek = array(
        'Monday' => 'Пн',
        'Tuesday' =>'Вт',
        'Wednesday' => 'Ср',
        'Thursday' => 'Чт',
        'Friday' => 'Пт',
        'Saturday' => 'Сб',
        'Sunday' => 'Вс'
    );


    /**
     * Constructor of class EventsTableGateWay.
     * @param $pdo - PDO object of connection to database.
     */
    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Function getting data from table `events` in database, to put
     * data into calendar.
     * @param $month - Integer. Number of searching month.
     * @param $year - Integer. Searching year.
     * @return array $dataForCal - array of data to calendar.
     */
    public function getDataForCalendar($month, $year)
    {
        $month++;
        $sql = "SELECT ev_id, ev_title, ev_begin_time, ev_begin_date, ev_end_time, ev_end_date, ev_slug, ev_address, ev_url 
                FROM " . self::$tableName . "
				WHERE ev_begin_date LIKE '" . $year . "-%" . $month . "_%'";

        $stmt = $this->pdo->query($sql);
        $dataForCal = array();
        foreach ($stmt as $value) {
            $dataForCal[] = array(
                'id' => $value['ev_id'],
                'name' => $value['ev_title'],
                'beginDate' => $value['ev_begin_date'],
                'beginTime' => $value['ev_begin_time'],
                'endDate' => $value['ev_end_date'],
                'endTime' => $value['ev_end_time'],
                'slug' => $value['ev_slug'],
                'address' => $value['ev_address'],
                'url' => $value['ev_url'],
            );

        }

        return $dataForCal;
    }

    /**
     * Function getting data from database for calendar list.
     * @param $limit - Integer, upper limit of sql-statement.
     * @return array - data for calendar list.
     */
    public function getCalendarListData($limit)
    {
        $limitUpper = intval($limit);
        $limitLower = $limitUpper - 5;
        $sql = "SELECT ev_id, ev_title, ev_begin_time, ev_begin_date, ev_slug, ev_address, ev_url
                FROM " . self::$tableName . "
                WHERE ev_begin_date >= CURDATE() ORDER BY ev_begin_date LIMIT $limitLower, $limitUpper ";

        $stmt = $this->pdo->query($sql);
        $dataForCal = array();
        foreach ($stmt as $row) {
            $dateTimeArray = $this->getFormattedDateTime($row['ev_begin_date'], $row['ev_begin_time']);
            $address = $this->getFormattedAddress($row['ev_address']);

            $dataForCal[] = array(
                'id' => $row['ev_id'],
                'name' => $row['ev_title'],
                'beginDateDay' => $dateTimeArray['beginDateDay'],
                'DayOfWeek' => $this->getDayOfWeek($row['ev_begin_date']),
                'beginDateMonth' => $dateTimeArray['beginDateMonth'],
                'beginTime' => $dateTimeArray['beginTimeHM'],
                'slug' => $row['ev_slug'],
                'address' => $address,
                'url' => $row['ev_url'],
            );
        }

        return $dataForCal;
    }

    private function getFormattedDateTime($date, $time) {
        $beginDateDay = substr($date, 8, 9);
        $beginDateMonth = substr($date, 5, 6);
        $beginTimeHM = substr($time, 0, 5);

        $formattedDateTimeArray = array(
            'beginDateDay' => $beginDateDay,
            'beginDateMonth' => $beginDateMonth,
            'beginTimeHM' => $beginTimeHM,
        );

        return $formattedDateTimeArray;
    }

    private function getFormattedAddress($address) {
        $addresses = preg_split('/[|]/', $address);

        return $addresses[0];
    }

    private function getDayOfWeek($date)
    {
        try {
            $objDate = new DateTime($date);
        }
        catch (Exception $e) {
            return 'Ошибка';
        }

        $objDayOfWeek = $objDate->format('l');

        return $this->daysOfWeek[$objDayOfWeek];
    }
}