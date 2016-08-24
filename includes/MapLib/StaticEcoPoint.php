<?php
/**
 * Класс, описывающий базовую логику статичных
 * точек на карте (пункты приема вторсырья, организации)
 * Created by PhpStorm.
 * User: Олег
 * Date: 01.08.2016
 * Time: 11:25
 */

namespace MapLib;


abstract class StaticEcoPoint extends EcoPoint
{
    protected $schedule = array();


}