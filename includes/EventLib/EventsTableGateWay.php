<?php

namespace EventLib;

use DateTime;
use Exception;
use PDO;

/**
 * Class describe model of events table in database
 */
class EventsTableGateWay
{
    private $pdo;
    static private $tableName = 'events';
    private $daysOfWeek = array(
        'Monday' => 'Пн',
        'Tuesday' => 'Вт',
        'Wednesday' => 'Ср',
        'Thursday' => 'Чт',
        'Friday' => 'Пт',
        'Saturday' => 'Сб',
        'Sunday' => 'Вс'
    );
    public $months = [
        "ЯНВАРЬ",
        "ФЕВРАЛЬ",
        "МАРТ",
        "АПРЕЛ",
        "МАЙ",
        "ИЮНЬ",
        "ИЮЛЬ",
        "АВГУСТ",
        "СЕНТЯБРЬ",
        "ОКТЯБРЬ",
        "НОЯБРЬ",
        "ДЕКАБРЬ"
    ];

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
        $sql = "SELECT ev_id, ev_title, ev_create_date, ev_begin_time, ev_begin_date, ev_end_time, ev_end_date, ev_slug, ev_address, ev_url 
                FROM " . self::$tableName . "
				WHERE ev_begin_date LIKE '" . $year . "-%" . $month . "_%'";

        $stmt = $this->pdo->query($sql);
        $dataForCal = array();
        foreach ($stmt as $value) {
            $dataForCal[] = array(
                'id' => $value['ev_id'],
                'name' => $value['ev_title'],
                'createDate' => $value['ev_create_date'],
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

        $sql = "SELECT ev_id, ev_title, ev_create_date, ev_begin_time, ev_begin_date, ev_slug, ev_address, ev_url, UNIX_TIMESTAMP(ev_begin_date) as ev_begin_date_timestamp
                FROM " . self::$tableName . "
                WHERE ev_begin_date >= CURDATE() ORDER BY ev_begin_date LIMIT $limitLower, $limitUpper ";

        $stmt = $this->pdo->query($sql);
        $dataForCal = array();
        foreach ($stmt as $row) {
            $dateTimeArray = $this->getFormattedDateTime($row['ev_begin_date'], $row['ev_begin_time']);
            $address = $this->getFormattedAddresses($row['ev_address'])[0];

            $dataForCal[] = array(
                'id' => $row['ev_id'],
                'name' => $row['ev_title'],
                'createDate' => $row['ev_create_date'],
                'beginDateDay' => $dateTimeArray['beginDateDay'],
                'DayOfWeek' => $this->getDayOfWeek($row['ev_begin_date']),
                'beginDateMonth' => $dateTimeArray['beginDateMonth'],
                'month' => $this->months[date("n", $row['ev_begin_date_timestamp']) - 1],
                'beginTime' => $dateTimeArray['beginTimeHM'],
                'beginDateYear' => $dateTimeArray['beginDateYear'],
                'slug' => $row['ev_slug'],
                'address' => $address,
                'url' => $row['ev_url'],
            );
        }

        return $dataForCal;
    }

    private function getFormattedDateTime($date, $time)
    {
        $beginDateDay = substr($date, 8, 9);
        $beginDateMonth = substr($date, 5, 6);
        $beginTimeHM = substr($time, 0, 5);
        $beginDateYear = substr($date, 0, 4);

        $formattedDateTimeArray = array(
            'beginDateDay' => $beginDateDay,
            'beginDateMonth' => $beginDateMonth,
            'beginTimeHM' => $beginTimeHM,
            'beginDateYear' => $beginDateYear,
        );

        return $formattedDateTimeArray;
    }

    private function getFormattedAddresses($address)
    {
        $addresses = preg_split('/[|]/', $address);

        return $addresses;
    }

    private function getDayOfWeek($date)
    {
        try {
            $objDate = new DateTime($date);
        } catch (Exception $e) {
            return 'Ошибка';
        }

        $objDayOfWeek = $objDate->format('l');

        return $this->daysOfWeek[$objDayOfWeek];
    }

    /**
     * Function gets data of events which was created by user with $authorID.
     * @param $authorID .
     * @return array of Even type.
     */
    public function getUserEvents($authorID)
    {
        $events = array();
        $tableName = self::$tableName;
        $sql = "SELECT ev_id, ev_title, ev_slug, ev_create_date FROM $tableName WHERE u_id = :id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('id' => $authorID));

        $eventData = array();
        foreach ($stmt as $row) {
            $eventData[] = array(
                'id' => $row['ev_id'],
                'name' => $row['ev_title'],
                'slug' => $row['ev_slug'],
                'createDate' => $row['ev_create_date'],
            );
        }

        foreach ($eventData as $item) {
            $event = new Event();

            $event->setId($item['id']);
            $event->setName($item['name']);
            $event->setSlug($item['slug']);
            $event->setCreateDate($item['createDate']);

            $events[] = $event;
        }

        return $events;
    }

    /**
     * @param $slug
     * @return array
     */
    public function getEventBySlug($slug)
    {
        $tableName = self::$tableName;
        $sql = "SELECT ev_begin_date, ev_begin_time, ev_end_date, ev_end_time, ev_address, ev_title, ev_description, ev_img
                FROM $tableName WHERE ev_slug = :slug";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('slug' => $slug));

        $item = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $item[0];

        $beginDateTime = $this->getFormattedDateTime($data['ev_begin_date'], $data['ev_begin_time']);
        $endDateTime = $this->getFormattedDateTime($data['ev_end_date'], $data['ev_end_time']);
        $address = $this->getFormattedAddresses($data['ev_address']);


        $eventData[] = array(
            'name' => $data['ev_title'],
            'addresses' => $address,
            'beginDateTime' => $beginDateTime,
            'beginMonth' => $this->months[intval($beginDateTime['beginDateMonth']) - 1],
            'endDateTime' => $endDateTime,
            'endMonth' => $this->months[intval($endDateTime['beginDateMonth']) -1],
            'description' => $data['ev_description'],
        );

        isset($data['ev_img']) ? $eventData['img'] = $data['ev_img'] : null;
        
        $event = new Event();
        $event->setPostInfo($eventData[0]);

        return $event;
    }

}