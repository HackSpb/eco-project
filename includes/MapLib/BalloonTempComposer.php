<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 23.05.2016
 * Time: 19:38
 * Desc: Класс создает шаблон балуна
 */

namespace MapLib;


class BalloonTempComposer
{
    private $balloonContent = "";

    private $name = "";
    private $time = "";
    private $adres = "";
    private $info = "";

    /**
     * BalloonTempComposer constructor.
     * @param $tempPath
     * @param $name
     * @param $time
     * @param $adres
     * @param $info
     */
    public function __construct($tempPath, $name, $time, $adres, $info)
    {
        $this->name = $name;
        $this->time = $time;
        $this->adres = $adres;
        $this->info = $info;

        $this->composeBalCont($tempPath);
    }

    private function composeBalCont($path) {
        $string = file_get_contents($path);

        $string = str_replace("{name}", $this->name, $string);
        $string = str_replace("{time}", $this->time, $string);
        $string = str_replace("{adres}", $this->adres, $string);
        $string = str_replace("{info}", $this->info, $string);

        $this->balloonContent = $string;
    }

    /**
     * @return string.
     */
    public function getBalloonContent()
    {
        return $this->balloonContent;
    }
}